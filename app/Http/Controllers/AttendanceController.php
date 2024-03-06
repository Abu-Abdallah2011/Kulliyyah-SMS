<?php

namespace App\Http\Controllers;

use App\Models\sessions;
use Illuminate\Http\Request;
use App\Models\AttendanceModel;
use App\Models\register_student;
use App\Models\register_teacher;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AttendanceFormRequest;

class AttendanceController extends Controller
{
    // Show Attendance Form
    public function create()
    {
        $teachers = register_teacher::where('user_id', Auth::user()->id)
        ->with(['students' => function ($query) 
        {
            $query->where('status', 'IN SCHOOL')
            ->orWhere('grad_type', 'TARTEEL ZALLA')
            ->orderBy('fullname');
        }])->with('user')
        ->get();
        return view('attendance.create', ['teachers' => $teachers]);
    }

    // Save Attendance to database
    public function store(Request $request)
{
    $date = $request->input('date');
    $attendance = $request->input('attendance');
    $studentIds = $request->input('student_ids');
    $time = $request->input('time');

    $session = sessions::pluck('session')->last();
    $term = sessions::pluck('term')->last();

    // Loop through all students and save their attendance records
    foreach ($studentIds as $index => $studentId) {
        $record = new AttendanceModel;
        $record->date = $date;
        $record->student_id = $studentId;
        $record->status = $attendance[$index];
        $record->session = $session;
        $record->term = $term;
        $record->time = $time;
        $record->save();
    }
    return redirect('/attendance/show')->with('message', 'Attendance records saved successfully!');
}

// Show Attendance Report
public function show()
{
    $teachers = register_teacher::where('user_id', Auth::user()->id)
        ->with(['students' => function ($query) {
            $query->where('status', 'IN SCHOOL')
            ->orWhere('grad_type', 'TARTEEL ZALLA')
            ->orderBy('fullname');
        }])->with('user')
        ->get(); 

        $session = sessions::pluck('session')->last();
        $term = sessions::pluck('term')->last();
    
    $teacherClass = $teachers->first()->class;

    $studentIds = register_student::where('class', $teacherClass)
    ->pluck('id')
    ->toArray();

$attendance = AttendanceModel::whereIn('student_id', $studentIds)
->where('term', $term)
->where('session', $session)
->with(['students' => function ($query) {
    $query->orderBy('fullname');
}])
->latest()
->paginate(200); 

    $statusIcons = [
        'present' => '<i class="fas fa-check text-green-500"></i>',
        'absent' => '<i class="fas fa-times text-red-500"></i>',
        'late' => '<i class="fas fa-clock text-yellow-500"></i>',
        'excused' => '<i class="fas fa-pencil text-purple-500"></i>',
    ];    
        return view('attendance.show', compact('attendance', 'teachers', 'teacherClass', 'statusIcons'));
}

 // Show Attendance Edit Form
 public function edit($date)
 {

    $teachers = register_teacher::where('user_id', Auth::user()->id)
        ->with(['students' => function ($query) {
            $query->where('status', 'IN SCHOOL')
            ->orWhere('grad_type', 'TARTEEL ZALLA')
            ->orderBy('fullname');
        }])->with('user')
        ->get(); 
    
    $teacherClass = $teachers->first()->class;

    $studentIds = register_student::where('class', $teacherClass)
    ->pluck('id')
    ->toArray();
    
    $attendance = AttendanceModel::whereIn('student_id', $studentIds)
    ->with(['students' => function ($query) {
        $query->orderBy('fullname');
    }])
    ->get();
     return view('attendance.edit_attendance', ['teachers' => $teachers, 'date' => $date, 'attendance' => $attendance]);
 }

  // Update Attendance to database
  public function update(Request $request)
  {
      $date = $request->input('date');
      $attendanceData  = $request->input('attendance');
      $studentIds = $request->input('student_ids');
      $times = $request->input('time');

      $session = sessions::pluck('session')->last();
    $term = sessions::pluck('term')->last();

    foreach ($studentIds as $studentId) {
        foreach ($attendanceData[$studentId] as $time => $status) {
            // Find the existing record for the specific date, student ID, and time
            $existingRecord = AttendanceModel::where([
                ['date', '=', $date],
                ['student_id', '=', $studentId],
                ['time', '=', $time],
            ])->first();

            if ($existingRecord) {
                // Update the attendance status and other data
                $existingRecord->status = $status;
                $existingRecord->session = $session;
                $existingRecord->term = $term;
                $existingRecord->update();
            }
        }
    }
      return redirect('/attendance/show')->with('message', 'Attendance records Updated successfully!');
  }


  // Delete Attendance
public function delete($date) {

    $teacherClass = Auth::user()->teachers->class;
$studentIds = register_student::where('class', $teacherClass)->pluck('id')->toArray();

$attendance = AttendanceModel::where('date', $date)
    ->whereIn('student_id', $studentIds)
    ->delete();

    return redirect('/attendance/show')->with('message', 'Maa Shaa Allaah! Attendance Record Deleted Successfully!');
}

 // Show Attendance Record for selected class to admin
 public function selectedCreate($teacher_id)
 {
     $teachers = register_teacher::where('id', $teacher_id)
     ->with(['students' => function ($query) 
     {
        $query->where('status', 'IN SCHOOL')
        ->orWhere('grad_type', 'TARTEEL ZALLA')
         ->orderBy('fullname');
     }])->with('user')
     ->get();

     $teacherClass = $teachers->pluck('class')->first();

     $studentIds = register_student::where('class', $teacherClass)
     ->pluck('id')
     ->toArray();
 
 $attendance = AttendanceModel::whereIn('student_id', $studentIds)
 ->with(['students' => function ($query) {
     $query->orderBy('fullname');
 }])
 ->latest()
 ->paginate(200); 
 
     $statusIcons = [
         'present' => '<i class="fas fa-check text-green-500"></i>',
         'absent' => '<i class="fas fa-times text-red-500"></i>',
         'late' => '<i class="fas fa-clock text-yellow-500"></i>',
         'excused' => '<i class="fas fa-pencil text-purple-500"></i>',
     ];
     return view('attendance.show', ['teachers' => $teachers, 'attendance' => $attendance, 'statusIcons' => $statusIcons]);
 }

 // Show Attendance Report to Guardian
public function attendanceGuardian($id)
{
   
$student = register_student::where('id', $id)->first();
    
$attendance = AttendanceModel::where('student_id', $id)
->with(['students' => function ($query) {
    $query->orderBy('fullname');
}])
->latest()
->paginate(12); 

    $statusIcons = [
        'present' => '<i class="fas fa-check text-green-500"></i>',
        'absent' => '<i class="fas fa-times text-red-500"></i>',
        'late' => '<i class="fas fa-clock text-yellow-500"></i>',
        'excused' => '<i class="fas fa-pencil text-purple-500"></i>',
    ];
    
        
        return view('attendance.show', compact('attendance', 'statusIcons', 'student'));
}

}
