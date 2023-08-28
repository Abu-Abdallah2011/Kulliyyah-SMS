<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AttendanceModel;
use App\Models\register_teacher;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AttendanceFormRequest;
use App\Models\register_student;

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

    $studentIds = register_student::pluck('id')->toArray();
    $date = AttendanceModel::pluck('date')->toArray();
    
    $attendance = AttendanceModel::latest()
    ->whereIn('student_id', $studentIds)
    ->with(['students' => function ($query) {
        $query->orderBy('fullname');
    }])
    // ->where('date', $date)
    ->paginate(12);

    $teachers = register_teacher::where('user_id', Auth::user()->id)
        ->with(['students' => function ($query) {
            $query->orderBy('fullname');
        }])->with('user')
        ->get();
        
        // Get the class of the first teacher in the collection
        $teacherClass = $teachers->first()->class; 
        
        return view('attendance.show', compact('attendance', 'teachers', 'teacherClass', 'date'));
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
