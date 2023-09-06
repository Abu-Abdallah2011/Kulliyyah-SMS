<?php

namespace App\Http\Controllers;

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
            $query->orderBy('fullname');
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

    // Loop through all students and save their attendance records
    foreach ($studentIds as $index => $studentId) {
        $record = new AttendanceModel;
        $record->date = $date;
        $record->student_id = $studentId;
        $record->status = $attendance[$index];
        $record->save();
    }


    // Redirect back to the attendance form with a success message
    return redirect('/attendance/show')->with('message', 'Attendance records saved successfully!');
}

// Show Attendance Report
public function show()
{
   
    $teachers = register_teacher::where('user_id', Auth::user()->id)
        ->with(['students' => function ($query) {
            $query->orderBy('fullname');
        }])->with('user')
        ->get(); 
    
    $teacherClass = $teachers->first()->class;

    $studentIds = register_student::where('class', $teacherClass)
    ->pluck('id')
    ->toArray();
    
// Paginate the attendance records and retrieve the latest records based on date
$attendance = AttendanceModel::whereIn('student_id', $studentIds)
->with(['students' => function ($query) {
    $query->orderBy('fullname');
}])
->latest('date') // Retrieve the latest records based on the 'date' column
->paginate(16); // You can adjust the number of records per page as needed
    
    // $attendance = AttendanceModel::whereIn('student_id', $studentIds)
    // ->with(['students' => function ($query) {
    //     $query->orderBy('fullname');
    // }])
    // ->get();

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
            $query->orderBy('fullname');
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
      $attendance = $request->input('attendance');
      $studentIds = $request->input('student_ids');
  
      // Loop through all students and update their attendance records
    foreach ($studentIds as $index => $studentId) {
        // Find the existing record to update
        $existingRecord = AttendanceModel::where('date', $date)
            ->where('student_id', $studentId)
            ->first();

        if ($existingRecord) {
            // Update the status
            $existingRecord->status = $attendance[$index];
            $existingRecord->update(); // Save the changes
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

 // Show Attendance Form for selected class to admin
 public function selectedCreate($teacher_id)
 {
     $teachers = register_teacher::where('user_id', $teacher_id)
     ->with(['students' => function ($query) 
     {
         $query->orderBy('fullname');
     }])->with('user')
     ->get();
     return view('attendance.create', ['teachers' => $teachers]);
 }


}
