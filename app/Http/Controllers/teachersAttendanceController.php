<?php

namespace App\Http\Controllers;

use App\Models\sessions;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Carbon\CarbonPeriod;
use App\Models\AllowanceVoucher;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\register_teacher;
use App\Support\TeacherAttendanceSummary;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Models\teachersAttendanceModel;

class teachersAttendanceController extends Controller
{
    // Show Attendance Form
    public function create()
    {
        $teachers = $this->orderedTeachersQuery()
        ->where('status', 'IN SCHOOL')
        ->get();
        return view('Teachers Attendance.create', ['teachers' => $teachers]);
    }

    // Save Attendance to database
    public function store(Request $request)
{
    $date = $request->input('date');
    $attendance = $request->input('attendance');
    $teacherIds = $request->input('teacher_ids');
    $time = $request->input('time');
    $timeIn = $request->input('time_in');
    $timeOut = $request->input('time_out');

    $session = sessions::pluck('session')->last();
    $term = sessions::pluck('term')->last();

    // Loop through all Teachers and save their attendance records
    foreach ($teacherIds as $index => $teacherId) {


        $record = new teachersAttendanceModel;
        $record->date = $date;
        $record->teacher_id = $teacherId;
        $record->status = $attendance[$index];
        $record->session = $session;
        $record->term = $term;
        $record->time = $time;
        $record->time_in = $timeIn[$index];
        $record->time_out = $timeOut[$index];
        $record->save();
    }
    return redirect('/teachersAttendance/show')->with('message', 'Attendance records saved successfully!');
}

// Show Attendance Report
public function show(Request $request)
{
    $month = $request->input('month', now()->format('F'));
    $year = (int) $request->input('year', now()->format('Y'));

    if (Gate::allows('isExecutive')) {
    $teachers = $this->orderedTeachersQuery()
    ->where('status', 'IN SCHOOL')
    ->get(); 
    } else {
        $teachers = $this->orderedTeachersQuery()
        ->where('user_id', Auth::user()->id)
        ->get();
    }

    $teacherIds = $teachers->pluck('id')->all();
    $attendanceSummaries = TeacherAttendanceSummary::forMonth($teacherIds, $month, $year);
    $attendance = TeacherAttendanceSummary::recordsForMonth($teacherIds, $month, $year);
    $finalizedVoucher = AllowanceVoucher::with(['items' => function ($query) use ($teacherIds) {
        $query->whereIn('teacher_id', $teacherIds);
    }])
        ->where('voucher_month', $month)
        ->where('voucher_year', $year)
        ->where('status', 'finalized')
        ->latest('finalized_at')
        ->first();
    $voucherSnapshotItems = $finalizedVoucher
        ? $finalizedVoucher->items->keyBy('teacher_id')
        : collect();
    $months = [
        'January', 'February', 'March', 'April', 'May', 'June',
        'July', 'August', 'September', 'October', 'November', 'December',
    ];
    $eligibilityPercentage = TeacherAttendanceSummary::ELIGIBILITY_PERCENTAGE;

    $statusIcons = [
        'present' => '<i class="fas fa-check text-green-500"></i>',
        'absent' => '<i class="fas fa-times text-red-500"></i>',
        'late' => '<i class="fas fa-clock text-yellow-500"></i>',
        'late with an excuse' => '<i class="fas fa-clock text-orange-500"></i>',
        'excused' => '<i class="fas fa-pencil text-purple-500"></i>',
        'closed early' => '<i class="fas bi-alarm-fill text-pink-500"></i>',
        'came late and closed early' => '<i class="fas bi-alarm-fill text-black-500"></i>',
    ];
    
        return view('Teachers Attendance.show', compact(
            'attendance',
            'teachers',
            'statusIcons',
            'attendanceSummaries',
            'month',
            'year',
            'months',
            'eligibilityPercentage',
            'finalizedVoucher',
            'voucherSnapshotItems'
        ));
}

 // Show Attendance Edit Form
 public function edit($date)
 {

    // if (Auth::user()->can('isExecutive')) {
    if (Gate::allows('isExecutive')) {
        $teachers = $this->orderedTeachersQuery()
        ->where('status', 'IN SCHOOL')
        ->get(); 
        } else {
            $teachers = $this->orderedTeachersQuery()
            ->where('user_id', Auth::user()->id)
            ->get();
        }
    
    $attendance = teachersAttendanceModel::where('date', $date)
    ->get();

     return view('Teachers Attendance.edit_attendance', 
     ['teachers' => $teachers, 
     'date' => $date, 
     'attendance' => $attendance]
    );
 }

  // Update Attendance to database
  public function update(Request $request, $date)
  {
    $attendanceData = $request->input('attendance');
    $teacherIds = $request->input('teacher_ids');
    $times = $request->input('time');
    $timeIn = $request->input('time_in');
    $timeOut = $request->input('time_out');

    $session = sessions::pluck('session')->last();
    $term = sessions::pluck('term')->last();
    
    foreach ($teacherIds as $teacherId) {
        foreach ($attendanceData[$teacherId] as $time => $status) {
        
            $existingRecord = teachersAttendanceModel::where([
                ['date', '=', $date],
                ['teacher_id', '=', $teacherId],
                ['time', '=', $time],
            ])->first();

            if ($existingRecord) {
                // Update the attendance status and other data
                $existingRecord->status = $status;
                $existingRecord->session = $session;
                $existingRecord->term = $term;
                foreach ($timeIn[$teacherId] as $time => $time_in) {
                $existingRecord->time_in = $time_in;
                }
                foreach ($timeOut[$teacherId] as $time => $time_out) {
                $existingRecord->time_out = $time_out;
                }
                $existingRecord->update();
            }

        }
        }

      return redirect('/teachersAttendance/show')->with('message', 'Attendance records Updated successfully!');
  }


  // Delete Attendance
public function delete($date) {

$teacherIds = register_teacher::where('status', 'IN SCHOOL')->pluck('id')->toArray();

$attendance = teachersAttendanceModel::where('date', $date)
    ->whereIn('teacher_id', $teacherIds)
    ->delete();

    return redirect('/teachersAttendance/show')->with('message', 'Maa Shaa Allaah! Attendance Record Deleted Successfully!');
}

public function downloadAttendanceSummaryPDF($id)
{
    $teacher = register_teacher::with('attendance')->findOrFail($id);

    // Group and normalize statuses
    $attendanceRecords = $teacher->attendance
        ->groupBy(['session', 'term'])
        ->map(function ($recordsBySession) {
            return $recordsBySession->map(function ($recordsByTerm) {
                $normalized = $recordsByTerm->map(function ($record) {
                    $record->status = strtolower(trim($record->status));
                    return $record;
                });

                return [
                    'presents' => $normalized->whereIn('status', ['present', 'Closed early', 'closed early'])->count(),
                    'absents'  => $normalized->whereIn('status', ['absent', 'Absent'])->count(),
                    'lates'    => $normalized->whereIn('status', ['late', 'late with an excuse', 'came late and closed early'])->count(),
                    'excuses'  => $normalized->whereIn('status', ['excused', 'Excused'])->count(),
                    'total'    => $normalized->count(),
                ];
            });
        });

    // Generate PDF
    $pdf = Pdf::loadView('PDFs.teacher_attendance_summary', compact('teacher', 'attendanceRecords'))
        ->setPaper('A4', 'portrait');

    return $pdf->download("Attendance_Summary_{$teacher->fullname}.pdf");
}

public function downloadSigningBook($gender)
{
    $gender = strtolower($gender);
    abort_unless(in_array($gender, ['male', 'female'], true), 404);

    $session = sessions::orderBy('created_at', 'desc')->firstOrFail();
    $startDate = Carbon::parse($session->term_starts)->startOfDay();
    $endDate = Carbon::parse($session->term_ends)->startOfDay();

    $teachers = $this->orderedTeachersQuery()
        ->where('status', 'IN SCHOOL')
        ->whereRaw('LOWER(gender) = ?', [$gender])
        ->get();

    $signingDays = collect();

    foreach (CarbonPeriod::create($startDate, $endDate) as $date) {
        if ($date->isFriday()) {
            continue;
        }

        $sessionsForDay = ($date->isSaturday() || $date->isSunday())
            ? ['Morning', 'Evening']
            : ['Daily'];

        foreach ($sessionsForDay as $period) {
            $signingDays->push([
                'date' => $date->copy(),
                'period' => $period,
            ]);
        }
    }

    $pdf = Pdf::loadView('PDFs.teachers_signing_book', [
        'teachers' => $teachers,
        'session' => $session,
        'signingDays' => $signingDays,
        'gender' => ucfirst($gender),
    ])->setPaper('a4', 'landscape');

    return $pdf->download(ucfirst($gender) . '_Teachers_Signing_Book_' . str_replace('/', '-', $session->session) . '_' . str_replace(' ', '_', $session->term) . '.pdf');
}

private function orderedTeachersQuery()
{
    return register_teacher::query()
        ->orderByRaw("CASE WHEN UPPER(gender) = 'MALE' THEN 0 WHEN UPPER(gender) = 'FEMALE' THEN 1 ELSE 2 END")
        ->orderBy('fullname');
}


}
