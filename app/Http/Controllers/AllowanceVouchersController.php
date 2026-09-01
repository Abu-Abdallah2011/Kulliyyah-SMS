<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\register_teacher;
use Illuminate\Http\Request;
use App\Models\AllowanceVoucher;
use App\Support\TeacherAttendanceSummary;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class AllowanceVouchersController extends Controller
{
    public function show()
    {
        $vouchers = AllowanceVoucher::latest()->paginate(15);
        return view('AllowanceVouchers.index', ['vouchers' => $vouchers]);
    }

    public function create(Request $request)
    {
        $month = $request->input('voucher_month', now()->format('F'));
        $year = (int) $request->input('voucher_year', now()->format('Y'));

        $inSchoolTeachers = register_teacher::where('status', 'IN SCHOOL')
            ->orderBy('fullname')
            ->get();

        $otherTeachers = register_teacher::where('status', '!=', 'IN SCHOOL')
            ->orderBy('fullname')
            ->get();

        $this->attachMonthlyAttendanceEligibility($inSchoolTeachers, $month, $year);
        $this->attachMonthlyAttendanceEligibility($otherTeachers, $month, $year);

        return view('AllowanceVouchers.create', [
            'month' => $month,
            'year' => $year,
            'inSchoolTeachers' => $inSchoolTeachers,
            'otherTeachers' => $otherTeachers,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'voucher_month' => ['required', 'string', 'max:30'],
            'voucher_year' => ['required', 'integer', 'min:2000', 'max:3000'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'include' => ['nullable', 'array'],
            'include.*' => ['nullable', 'in:1'],
            'reason' => ['nullable', 'array'],
            'reason.*' => ['nullable', 'string', 'max:255'],
            'deduction' => ['nullable', 'array'],
            'deduction.*' => ['nullable', 'numeric', 'min:0'],
            'bonus' => ['nullable', 'array'],
            'bonus.*' => ['nullable', 'numeric', 'min:0'],
        ]);

        $includedTeacherIds = array_map('intval', array_keys($request->input('include', [])));
        $inSchoolTeacherIds = register_teacher::where('status', 'IN SCHOOL')->pluck('id')->map(function ($id) {
            return (int) $id;
        })->all();
        $voucherTeacherIds = array_values(array_unique(array_merge($inSchoolTeacherIds, $includedTeacherIds)));
        $voucherTeachers = register_teacher::whereIn('id', $voucherTeacherIds)->get();
        $includedTeachers = $voucherTeachers->whereIn('id', $includedTeacherIds);
        $attendanceSummaries = TeacherAttendanceSummary::forMonth($voucherTeacherIds, $validated['voucher_month'], (int) $validated['voucher_year']);

        if ($includedTeachers->isEmpty()) {
            return back()->withInput()->with('message', 'Please include at least one teacher before creating voucher.');
        }

        foreach ($includedTeachers as $teacher) {
            $baseAllowance = (float) ($teacher->allowance ?? 0);
            $deduction = (float) ($request->input("deduction.{$teacher->id}") ?? 0);
            $bonus = (float) ($request->input("bonus.{$teacher->id}") ?? 0);
            $net = $baseAllowance + $bonus - $deduction;
            $summary = $attendanceSummaries[(int) $teacher->id] ?? TeacherAttendanceSummary::empty();
            $reason = trim((string) $request->input("reason.{$teacher->id}", ''));

            if ($net < 0) {
                return back()->withInput()->with('message', "Net allowance cannot be negative for {$teacher->fullname}.");
            }

            if (!$summary['eligible'] && $reason === '') {
                return back()->withInput()->with('message', "{$teacher->fullname} is below the 60% attendance eligibility. Please add a reason before including this teacher.");
            }
        }

        $voucher = null;

        DB::transaction(function () use ($validated, $request, $voucherTeachers, $includedTeacherIds, $attendanceSummaries, &$voucher) {
            $voucher = AllowanceVoucher::create([
                'voucher_no' => $this->generateVoucherNo($validated['voucher_month'], (int) $validated['voucher_year']),
                'voucher_month' => $validated['voucher_month'],
                'voucher_year' => (int) $validated['voucher_year'],
                'status' => 'draft',
                'generated_by_user_id' => Auth::id(),
                'generated_by_name' => Auth::user()->username ?? Auth::user()->email,
                'notes' => $validated['notes'] ?? null,
            ]);

            $totalAmount = 0;
            $teacherCount = 0;

            foreach ($voucherTeachers as $teacher) {
                $isIncluded = in_array((int) $teacher->id, $includedTeacherIds, true);
                $baseAllowance = (float) ($teacher->allowance ?? 0);
                $deduction = $isIncluded ? (float) ($request->input("deduction.{$teacher->id}") ?? 0) : 0;
                $bonus = $isIncluded ? (float) ($request->input("bonus.{$teacher->id}") ?? 0) : 0;
                $allowance = $isIncluded ? $baseAllowance + $bonus - $deduction : 0;
                $summary = $attendanceSummaries[(int) $teacher->id] ?? TeacherAttendanceSummary::empty();
                $reason = $request->input("reason.{$teacher->id}");

                if (!$isIncluded && !$reason) {
                    $reason = $summary['eligible']
                        ? 'Not selected for payment during voucher creation.'
                        : 'Below 60% attendance eligibility for the voucher month.';
                }

                if ($isIncluded) {
                    $totalAmount += $allowance;
                    $teacherCount++;
                }

                $voucher->items()->create([
                    'teacher_id' => $teacher->id,
                    'teacher_fullname' => $teacher->fullname,
                    'bank_branch' => $teacher->bank_branch,
                    'acct_name' => $teacher->acct_name,
                    'acct_no' => $teacher->acct_no,
                    'base_allowance' => $baseAllowance,
                    'deduction_amount' => $deduction,
                    'bonus_amount' => $bonus,
                    'monthly_allowance' => $allowance,
                    'attendance_total' => $summary['total'],
                    'attendance_present' => $summary['present'],
                    'attendance_late' => $summary['late'],
                    'attendance_percentage' => $summary['percentage'],
                    'attendance_eligible' => $summary['eligible'],
                    'included' => $isIncluded,
                    'reason' => $reason,
                ]);
            }

            $voucher->update([
                'teacher_count' => $teacherCount,
                'total_amount' => $totalAmount,
            ]);
        });

        return redirect("/allowance_vouchers/{$voucher->id}")->with('message', 'Voucher draft created. Please review and finalize.');
    }

    public function view($id)
    {
        $voucher = AllowanceVoucher::with('items')->findOrFail($id);
        return view('AllowanceVouchers.show', ['voucher' => $voucher]);
    }

    public function finalize($id)
    {
        $voucher = AllowanceVoucher::with('items')->findOrFail($id);

        if ($voucher->status === 'finalized') {
            return back()->with('message', 'Voucher is already finalized.');
        }

        $duplicate = AllowanceVoucher::where('voucher_month', $voucher->voucher_month)
            ->where('voucher_year', $voucher->voucher_year)
            ->where('status', 'finalized')
            ->where('id', '!=', $voucher->id)
            ->exists();

        if ($duplicate) {
            return back()->with('message', 'A finalized voucher already exists for this month/year.');
        }

        $voucher->update([
            'status' => 'finalized',
            'finalized_at' => now(),
        ]);

        $voucher->refresh();
        $voucher->load('items');

        $pdf = Pdf::loadView('PDFs.allowance_voucher', ['voucher' => $voucher])->setPaper('a4', 'portrait');
        $pdfBinary = $pdf->output();

        $safeMonth = preg_replace('/[^A-Za-z0-9_\-]/', '_', $voucher->voucher_month);
        $fileName = $voucher->voucher_no . '.pdf';
        $path = "allowance_vouchers/{$voucher->voucher_year}/{$safeMonth}/{$fileName}";

        Storage::disk('local')->put($path, $pdfBinary);

        $voucher->update([
            'pdf_path' => $path,
        ]);

        return response()->download(storage_path('app/' . $path), $fileName);
    }

    public function downloadPdf($id)
    {
        $voucher = AllowanceVoucher::findOrFail($id);

        if (!$voucher->pdf_path || !Storage::disk('local')->exists($voucher->pdf_path)) {
            return back()->with('message', 'Voucher PDF file was not found.');
        }

        return response()->download(storage_path('app/' . $voucher->pdf_path), $voucher->voucher_no . '.pdf');
    }

    public function cancel($id)
    {
        $voucher = AllowanceVoucher::findOrFail($id);

        if ($voucher->status === 'finalized') {
            return back()->with('message', 'Finalized voucher cannot be cancelled.');
        }

        $voucher->update(['status' => 'cancelled']);

        return redirect('/allowance_vouchers')->with('message', 'Voucher cancelled successfully.');
    }

    private function generateVoucherNo(string $month, int $year): string
    {
        $monthNum = Carbon::parse("1 {$month} {$year}")->format('m');
        $prefix = "VCH-{$year}-{$monthNum}-";
        $latest = AllowanceVoucher::where('voucher_no', 'like', $prefix . '%')
            ->orderByDesc('id')
            ->first();

        $next = 1;

        if ($latest) {
            $lastPart = (int) substr($latest->voucher_no, -3);
            $next = $lastPart + 1;
        }

        return $prefix . str_pad((string) $next, 3, '0', STR_PAD_LEFT);
    }

    private function attachMonthlyAttendanceEligibility($teachers, string $month, int $year): void
    {
        $summaries = TeacherAttendanceSummary::forMonth($teachers->pluck('id')->all(), $month, $year);

        foreach ($teachers as $teacher) {
            $summary = $summaries[(int) $teacher->id] ?? TeacherAttendanceSummary::empty();

            $teacher->setAttribute('voucher_attendance_total', $summary['total']);
            $teacher->setAttribute('voucher_attendance_present', $summary['present']);
            $teacher->setAttribute('voucher_attendance_late', $summary['late']);
            $teacher->setAttribute('voucher_attendance_percentage', $summary['percentage']);
            $teacher->setAttribute('voucher_attendance_eligible', $summary['eligible']);
        }
    }
}
