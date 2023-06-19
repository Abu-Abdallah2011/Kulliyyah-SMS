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

    // $student_id = register_student::get('id');
    $studentIds = register_student::pluck('id')->toArray();
    $date = AttendanceModel::pluck('date')->toArray();
    
    $attendance = AttendanceModel::whereIn('student_id', $studentIds)
    ->with(['students' => function ($query) {
        $query->orderBy('fullname');
    }])
    ->get();

    $teachers = register_teacher::where('user_id', Auth::user()->id)
        ->with(['students' => function ($query) {
            $query->orderBy('fullname');
        }])->with('user')
        ->get();
        
    // if ($attendance) {
    //     $attendanceDetails = $attendance->attendanceDetails;
        $statuses = [
            'present' => '<i class="fas fa-check text-green-500"></i>',
            'absent' => '<i class="fas fa-times text-red-500"></i>',
            'late' => '<i class="fas fa-clock text-orange-500"></i>',
            //'excused' => '<i class="YOUR ICON CLASS HERE" style="color: YOUR COLOR HERE;"></i>',
            'excused' => '<i class="fas fa-clock text-orange-500"></i>',
        ];
        //dd($statuses);
        $teacherClass = $teachers->first()->class; // Get the class of the first teacher in the collection
        
        return view('attendance.show', compact('attendance', 'statuses', 'teachers', 'teacherClass', 'date'));
    // } else {
    //     return redirect()->route('attendance.create')->with('error', 'Attendance record not found.');
    // }
}


}
