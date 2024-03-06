<?php

namespace App\Http\Controllers;

use App\Models\sessions;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\register_teacher;
use Illuminate\Support\Facades\Auth;
use App\Models\teachersAttendanceModel;

class teachersAttendanceController extends Controller
{
    // Show Attendance Form
    public function create()
    {
        $teachers = register_teacher::where('status', 'IN SCHOOL')
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
public function show()
{
    if (Auth::user()->can('isExecutive')) {
    $teachers = register_teacher::where('status', 'IN SCHOOL')
    ->get(); 
    } else {
        $teachers = register_teacher::where('user_id', Auth::user()->id)
        ->get();
    }

$attendance = teachersAttendanceModel::latest()
->paginate(200); 

    $statusIcons = [
        'present' => '<i class="fas fa-check text-green-500"></i>',
        'absent' => '<i class="fas fa-times text-red-500"></i>',
        'late' => '<i class="fas fa-clock text-yellow-500"></i>',
        'late with an excuse' => '<i class="fas fa-clock text-orange-500"></i>',
        'excused' => '<i class="fas fa-pencil text-purple-500"></i>',
        'closed early' => '<i class="fas bi-alarm-fill text-pink-500"></i>',
        'came late and closed early' => '<i class="fas bi-alarm-fill text-black-500"></i>',
    ];
    
        return view('Teachers Attendance.show', compact('attendance', 'teachers', 'statusIcons'));
}

 // Show Attendance Edit Form
 public function edit($date)
 {

    if (Auth::user()->can('isExecutive')) {
        $teachers = register_teacher::where('status', 'IN SCHOOL')
        ->get(); 
        } else {
            $teachers = register_teacher::where('user_id', Auth::user()->id)
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
  public function update(Request $request)
  {
    $date = $request->input('date');
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

}
