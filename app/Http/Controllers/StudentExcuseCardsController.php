<?php

namespace App\Http\Controllers;

use App\Models\StudentExcuseCard;
use App\Models\StudentExcuseRequest;
use App\Models\register_guardian;
use App\Models\register_student;
use App\Models\sessions;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class StudentExcuseCardsController extends Controller
{
    private const DAYS = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

    public function guardianIndex()
    {
        $guardian = $this->currentGuardian();
        abort_unless($guardian || Gate::allows('isExecutive'), 403);

        $requests = StudentExcuseRequest::with(['student', 'card.approver.teachers'])
            ->when($guardian && !Gate::allows('isExecutive'), fn ($query) => $query->where('guardian_id', $guardian->id))
            ->latest()
            ->paginate(20);

        return view('StudentExcuses.guardian_index', compact('requests'));
    }

    public function create()
    {
        $guardian = $this->currentGuardian();
        abort_unless($guardian, 403);

        $students = $guardian->students()->currentlyEnrolled()->orderBy('fullname')->get();
        $days = self::DAYS;

        return view('StudentExcuses.create', compact('students', 'days'));
    }

    public function store(Request $request)
    {
        $guardian = $this->currentGuardian();
        abort_unless($guardian, 403);

        $data = $this->validateRequestDetails($request);

        $student = register_student::where('id', $data['student_id'])
            ->where('guardian_id', $guardian->id)
            ->currentlyEnrolled()
            ->firstOrFail();

        $period = sessions::orderBy('created_at', 'desc')->first();

        StudentExcuseRequest::create([
            'student_id' => $student->id,
            'guardian_id' => $guardian->id,
            'type' => $data['type'],
            'reason' => $data['reason'],
            'requested_days' => $data['type'] === 'out' ? ['Saturday', 'Sunday'] : ($data['requested_days'] ?? []),
            'requested_start_time' => $data['type'] === 'out' ? '13:00' : null,
            'requested_end_time' => $data['type'] === 'out' ? '14:00' : null,
            'requested_latest_time' => $data['type'] === 'late' ? $data['requested_latest_time'] : null,
            'term' => $period?->term,
            'session' => $period?->session,
            'status' => 'pending',
        ]);

        return redirect()->route('student_excuses.guardian.index')->with('message', 'Excuse request submitted successfully.');
    }

    public function guardianEdit($id)
    {
        $guardian = $this->currentGuardian();
        abort_unless($guardian, 403);

        $excuseRequest = StudentExcuseRequest::with(['student', 'card'])
            ->where('guardian_id', $guardian->id)
            ->findOrFail($id);

        abort_unless($excuseRequest->status === 'pending', 403, 'Only pending requests can be edited by guardians.');

        $students = $guardian->students()->currentlyEnrolled()->orderBy('fullname')->get();
        $days = self::DAYS;

        return view('StudentExcuses.edit_guardian', compact('excuseRequest', 'students', 'days'));
    }

    public function guardianUpdate(Request $request, $id)
    {
        $guardian = $this->currentGuardian();
        abort_unless($guardian, 403);

        $excuseRequest = StudentExcuseRequest::where('guardian_id', $guardian->id)->findOrFail($id);
        abort_unless($excuseRequest->status === 'pending', 403, 'Only pending requests can be edited by guardians.');

        $data = $this->validateRequestDetails($request);

        $student = register_student::where('id', $data['student_id'])
            ->where('guardian_id', $guardian->id)
            ->currentlyEnrolled()
            ->firstOrFail();

        $excuseRequest->update($this->requestPayload($data, $student->id, $guardian->id));
        $period = sessions::where('session', $excuseRequest->session)
            ->where('term', $excuseRequest->term)
            ->latest()
            ->first() ?: sessions::orderBy('created_at', 'desc')->first();

        $this->syncCardFromRequest($excuseRequest->fresh(), $data, ['status' => 'pending'], $period);

        return redirect()->route('student_excuses.guardian.index')->with('message', 'Excuse request updated successfully.');
    }

    public function adminIndex(Request $request)
    {
        $search = trim((string) $request->input('search'));
        $status = $request->input('status');
        $type = $request->input('type');

        $requests = StudentExcuseRequest::with(['student', 'guardian.user', 'card.approver.teachers', 'reviewer'])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('reason', 'like', "%{$search}%")
                        ->orWhere('type', 'like', "%{$search}%")
                        ->orWhere('status', 'like', "%{$search}%")
                        ->orWhere('term', 'like', "%{$search}%")
                        ->orWhere('session', 'like', "%{$search}%")
                        ->orWhereHas('student', function ($query) use ($search) {
                            $query->where('fullname', 'like', "%{$search}%")
                                ->orWhere('class', 'like', "%{$search}%");
                        })
                        ->orWhereHas('guardian', function ($query) use ($search) {
                            $query->where('fullname', 'like', "%{$search}%")
                                ->orWhere('phone', 'like', "%{$search}%");
                        })
                        ->orWhereHas('guardian.user', function ($query) use ($search) {
                            $query->where('email', 'like', "%{$search}%")
                                ->orWhere('username', 'like', "%{$search}%");
                        })
                        ->orWhereHas('card', function ($query) use ($search) {
                            $query->where('card_number', 'like', "%{$search}%")
                                ->orWhere('status', 'like', "%{$search}%");
                        });
                });
            })
            ->when(in_array($status, ['pending', 'approved', 'rejected', 'revoked'], true), function ($query) use ($status) {
                if ($status === 'revoked') {
                    $query->whereHas('card', fn ($query) => $query->where('status', 'revoked'));
                } else {
                    $query->where('status', $status);
                }
            })
            ->when(in_array($type, ['late', 'out'], true), fn ($query) => $query->where('type', $type))
            ->latest()
            ->paginate(30)
            ->withQueryString();

        return view('StudentExcuses.admin_index', compact('requests', 'search', 'status', 'type'));
    }

    public function review($id)
    {
        $excuseRequest = StudentExcuseRequest::with(['student', 'guardian.user', 'card'])->findOrFail($id);
        $days = self::DAYS;

        return view('StudentExcuses.review', compact('excuseRequest', 'days'));
    }

    public function adminEdit($id)
    {
        $excuseRequest = StudentExcuseRequest::with(['student', 'guardian.user', 'card.approver.teachers'])->findOrFail($id);
        $students = register_student::currentlyEnrolled()->orderBy('fullname')->get();
        $days = self::DAYS;

        return view('StudentExcuses.edit_admin', compact('excuseRequest', 'students', 'days'));
    }

    public function adminUpdate(Request $request, $id)
    {
        $excuseRequest = StudentExcuseRequest::with('card')->findOrFail($id);

        $data = $this->validateRequestDetails($request);
        $adminData = $request->validate([
            'status' => ['required', 'in:pending,approved,rejected,revoked'],
            'valid_from' => ['nullable', 'date'],
            'valid_until' => ['nullable', 'date', 'after_or_equal:valid_from'],
            'review_note' => ['nullable', 'string', 'max:2000'],
            'revocation_reason' => ['nullable', 'string', 'max:2000'],
        ]);

        if ($adminData['status'] === 'revoked') {
            $request->validate([
                'revocation_reason' => ['required', 'string', 'max:2000'],
            ]);
        }

        $student = register_student::currentlyEnrolled()->findOrFail($data['student_id']);
        $period = sessions::where('session', $excuseRequest->session)
            ->where('term', $excuseRequest->term)
            ->latest()
            ->first() ?: sessions::orderBy('created_at', 'desc')->first();

        $excuseRequest->update(array_merge(
            $this->requestPayload($data, $student->id, $student->guardian_id),
            [
                'status' => $adminData['status'],
                'reviewed_by' => Auth::id(),
                'reviewed_at' => now(),
                'review_note' => $adminData['review_note'] ?? null,
            ]
        ));

        $card = $this->syncCardFromRequest($excuseRequest->fresh(), $data, $adminData, $period);

        if ($card && in_array($adminData['status'], ['approved', 'revoked'], true)) {
            return redirect()->route('student_excuses.admin.show', $card->id)->with('message', 'Excuse updated successfully.');
        }

        return redirect()->route('student_excuses.admin.index')->with('message', 'Excuse updated successfully.');
    }

    public function approve(Request $request, $id)
    {
        $excuseRequest = StudentExcuseRequest::with('card')->findOrFail($id);

        if ($excuseRequest->card) {
            return back()->with('message', 'This request already has an approved card.');
        }

        $data = $request->validate([
            'valid_days' => ['nullable', 'array'],
            'valid_days.*' => ['in:' . implode(',', self::DAYS)],
            'latest_time' => ['nullable', 'date_format:H:i'],
            'start_time' => ['nullable', 'date_format:H:i'],
            'end_time' => ['nullable', 'date_format:H:i'],
            'review_note' => ['nullable', 'string', 'max:2000'],
        ]);

        if ($excuseRequest->type === 'late') {
            $request->validate([
                'valid_days' => ['required', 'array', 'min:1'],
                'latest_time' => ['required', 'date_format:H:i'],
            ]);
        }

        $period = sessions::where('session', $excuseRequest->session)
            ->where('term', $excuseRequest->term)
            ->latest()
            ->first() ?: sessions::orderBy('created_at', 'desc')->first();

        $excuseRequest->update([
            'status' => 'approved',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
            'review_note' => $data['review_note'] ?? null,
        ]);

        $card = StudentExcuseCard::create([
            'student_excuse_request_id' => $excuseRequest->id,
            'student_id' => $excuseRequest->student_id,
            'guardian_id' => $excuseRequest->guardian_id,
            'card_number' => $this->newCardNumber(),
            'qr_token' => Str::random(64),
            'type' => $excuseRequest->type,
            'valid_days' => $excuseRequest->type === 'out' ? ['Saturday', 'Sunday'] : ($data['valid_days'] ?? []),
            'start_time' => $excuseRequest->type === 'out' ? ($data['start_time'] ?? '13:00') : null,
            'end_time' => $excuseRequest->type === 'out' ? ($data['end_time'] ?? '14:00') : null,
            'latest_time' => $excuseRequest->type === 'late' ? $data['latest_time'] : null,
            'term' => $excuseRequest->term,
            'session' => $excuseRequest->session,
            'valid_from' => $this->parseDate($period?->term_starts),
            'valid_until' => $this->parseDate($period?->term_ends),
            'status' => 'active',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return redirect()->route('student_excuses.admin.show', $card->id)->with('message', 'Excuse card approved and generated successfully.');
    }

    public function reject(Request $request, $id)
    {
        $data = $request->validate([
            'review_note' => ['required', 'string', 'max:2000'],
        ]);

        StudentExcuseRequest::findOrFail($id)->update([
            'status' => 'rejected',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
            'review_note' => $data['review_note'],
        ]);

        return redirect()->route('student_excuses.admin.index')->with('message', 'Excuse request rejected.');
    }

    public function adminShow($id)
    {
        $card = StudentExcuseCard::with(['student', 'guardian', 'request', 'approver.teachers'])->findOrFail($id);
        $verification = $card->verificationStatus();

        return view('StudentExcuses.show', compact('card', 'verification'));
    }

    public function guardianShow($id)
    {
        $guardian = $this->currentGuardian();
        abort_unless($guardian, 403);

        $card = StudentExcuseCard::with(['student', 'guardian', 'request', 'approver.teachers'])
            ->where('guardian_id', $guardian->id)
            ->findOrFail($id);
        $verification = $card->verificationStatus();

        return view('StudentExcuses.show', compact('card', 'verification'));
    }

    public function download($id)
    {
        $card = StudentExcuseCard::with(['student', 'guardian', 'request', 'approver.teachers'])->findOrFail($id);

        if (!Gate::allows('isExecutive')) {
            $guardian = $this->currentGuardian();
            abort_unless($guardian && (int) $card->guardian_id === (int) $guardian->id, 403);
            abort_unless($card->status === 'active', 403, 'This card is not currently active and can no longer be downloaded by a guardian.');
        }

        $verificationUrl = route('student_excuse_cards.verify', $card->qr_token);
        $pdfStatus = $this->pdfCardStatus($card);
        $pdf = Pdf::loadView('PDFs.student_excuse_card', compact('card', 'verificationUrl', 'pdfStatus'))
            ->setPaper([0, 0, 297.64, 419.53], 'portrait');

        return $pdf->download($card->card_number . '.pdf');
    }

    private function pdfCardStatus(StudentExcuseCard $card): array
    {
        $today = now()->startOfDay();

        if ($card->status === 'revoked') {
            return ['label' => 'Revoked', 'color' => '#b91c1c'];
        }

        if ($card->status !== 'active') {
            return ['label' => 'Inactive', 'color' => '#6b7280'];
        }

        if ($card->valid_from && $today->lt($card->valid_from->copy()->startOfDay())) {
            return ['label' => 'Not Yet Valid', 'color' => '#6b7280'];
        }

        if ($card->valid_until && $today->gt($card->valid_until->copy()->startOfDay())) {
            return ['label' => 'Expired', 'color' => '#b91c1c'];
        }

        return ['label' => 'Active', 'color' => '#047857'];
    }

    public function revoke(Request $request, $id)
    {
        $data = $request->validate([
            'revocation_reason' => ['required', 'string', 'max:2000'],
        ]);

        $card = StudentExcuseCard::with('request')->findOrFail($id);

        $card->update([
            'status' => 'revoked',
            'revoked_by' => Auth::id(),
            'revoked_at' => now(),
            'revocation_reason' => $data['revocation_reason'],
        ]);

        $card->request?->update([
            'status' => 'revoked',
        ]);

        return back()->with('message', 'Excuse card revoked.');
    }

    public function verify($token)
    {
        $card = StudentExcuseCard::with(['student', 'guardian', 'request', 'approver.teachers'])
            ->where('qr_token', $token)
            ->firstOrFail();
        $verification = $card->verificationStatus();

        return view('StudentExcuses.verify', compact('card', 'verification'));
    }

    private function currentGuardian(): ?register_guardian
    {
        return register_guardian::where('user_id', Auth::id())->first();
    }

    private function newCardNumber(): string
    {
        do {
            $number = 'EXC-' . now()->format('Ymd') . '-' . Str::upper(Str::random(6));
        } while (StudentExcuseCard::where('card_number', $number)->exists());

        return $number;
    }

    private function validateRequestDetails(Request $request): array
    {
        $data = $request->validate([
            'student_id' => ['required', 'integer'],
            'type' => ['required', 'in:late,out'],
            'reason' => ['required', 'string', 'max:2000'],
            'requested_days' => ['nullable', 'array'],
            'requested_days.*' => ['in:' . implode(',', self::DAYS)],
            'requested_latest_time' => ['nullable', 'date_format:H:i'],
            'requested_start_time' => ['nullable', 'date_format:H:i'],
            'requested_end_time' => ['nullable', 'date_format:H:i'],
        ]);

        if ($data['type'] === 'late') {
            $request->validate([
                'requested_days' => ['required', 'array', 'min:1'],
                'requested_latest_time' => ['required', 'date_format:H:i'],
            ]);
        }

        return $data;
    }

    private function requestPayload(array $data, int $studentId, int|string $guardianId): array
    {
        return [
            'student_id' => $studentId,
            'guardian_id' => $guardianId,
            'type' => $data['type'],
            'reason' => $data['reason'],
            'requested_days' => $data['type'] === 'out' ? ['Saturday', 'Sunday'] : ($data['requested_days'] ?? []),
            'requested_start_time' => $data['type'] === 'out' ? ($data['requested_start_time'] ?? '13:00') : null,
            'requested_end_time' => $data['type'] === 'out' ? ($data['requested_end_time'] ?? '14:00') : null,
            'requested_latest_time' => $data['type'] === 'late' ? $data['requested_latest_time'] : null,
        ];
    }

    private function syncCardFromRequest(StudentExcuseRequest $excuseRequest, array $data, array $adminData, ?sessions $period): ?StudentExcuseCard
    {
        if (!in_array($adminData['status'], ['approved', 'revoked'], true) && !$excuseRequest->card) {
            return null;
        }

        $card = $excuseRequest->card ?: new StudentExcuseCard([
            'student_excuse_request_id' => $excuseRequest->id,
            'card_number' => $this->newCardNumber(),
            'qr_token' => Str::random(64),
        ]);

        $cardStatus = match ($adminData['status']) {
            'approved' => 'active',
            'revoked' => 'revoked',
            default => 'inactive',
        };

        $card->fill([
            'student_id' => $excuseRequest->student_id,
            'guardian_id' => $excuseRequest->guardian_id,
            'type' => $excuseRequest->type,
            'valid_days' => $excuseRequest->type === 'out' ? ['Saturday', 'Sunday'] : ($data['requested_days'] ?? []),
            'start_time' => $excuseRequest->type === 'out' ? ($data['requested_start_time'] ?? '13:00') : null,
            'end_time' => $excuseRequest->type === 'out' ? ($data['requested_end_time'] ?? '14:00') : null,
            'latest_time' => $excuseRequest->type === 'late' ? $data['requested_latest_time'] : null,
            'term' => $excuseRequest->term,
            'session' => $excuseRequest->session,
            'valid_from' => $adminData['valid_from'] ?? $this->parseDate($period?->term_starts),
            'valid_until' => $adminData['valid_until'] ?? $this->parseDate($period?->term_ends),
            'status' => $cardStatus,
            'revocation_reason' => $adminData['status'] === 'revoked' ? ($adminData['revocation_reason'] ?? null) : null,
        ]);

        if ($adminData['status'] === 'approved') {
            $card->approved_by = Auth::id();
            $card->approved_at = now();
            $card->revoked_at = null;
            $card->revoked_by = null;
        }

        if ($adminData['status'] === 'revoked') {
            $card->revoked_by = Auth::id();
            $card->revoked_at = now();
        }

        $card->save();

        return $card;
    }

    private function parseDate(?string $date): ?string
    {
        if (!$date) {
            return null;
        }

        try {
            return Carbon::parse($date)->toDateString();
        } catch (\Exception $exception) {
            return null;
        }
    }
}
