<?php

namespace App\Http\Controllers;

use App\Models\Hadda;
use Illuminate\Http\Request;
use App\Models\register_teacher;
use App\Models\register_student;
use App\Models\register_guardian;
use Illuminate\Support\Facades\Auth;

class ClassesController extends Controller
{
    // Show Classes page to Admin
    public function index() 
    {
        $allteachers = register_teacher::distinct('class')->pluck('class');

        $malams = register_teacher::with(['students' => function ($query) {
            $query->orderBy('fullname');
        }])
        ->where('status', 'IN SCHOOL')
        ->with('user')
        ->get();
        

        $teachers = register_teacher::where('user_id', Auth::user()->id)
        ->with(['students' => function ($query) 
        {
            $query->orderBy('fullname');
        }])->with('user')
        ->get();
    

        return view('classes', [
        'teachers' => $teachers,
        'allteachers' => $allteachers,
        'malams' => $malams
    ]);
    }

    // SHOW SINGLE TEACHER DASHBOARD TO ADMIN
    public function display($teacher_id)
    {

        $teacher = register_teacher::
        with(['students' => function ($query) 
        {
            $query->where('status', 'IN SCHOOL')
            ->orWhere('grad_type', 'TARTEEL ZALLA')
            ->orderBy('fullname');
        }])
        ->with('user')
        ->where('id', $teacher_id)
        ->firstOrFail();

        $class = $teacher->class;

            $teachers = register_teacher::where('class', $class)
        ->with(['students' => function ($query) 
        {
            $query->where('status', 'IN SCHOOL')->orderBy('fullname');
        }])->with('user')
        ->get();
    
            return view('dashboard', [
            'teacher' => $teacher,
            'class' => $class,
            'teachers' => $teachers,
        ]);
    }

    // Show Class Teachers Names
public function classTeacher()
{
   $class = register_teacher::where('user_id', Auth::user()->id)->value('class');

   $malams = register_teacher::with(['students' => function ($query) {
       $query->orderBy('fullname');
   }])
   ->with('user')
   ->where('class', $class)
   ->get();
   

   $teachers = register_teacher::where('user_id', Auth::user()->id)
   ->with(['students' => function ($query) 
   {
       $query->where('status', 'IN SCHOOL')->orderBy('fullname');
   }])->with('user')
   ->get();
   

   return view('attendance.class_teachers', [
   'teachers' => $teachers,
   'class' => $class,
   'malams' => $malams
]);
}


// Show Class Students Names
public function classStudent()
{

   $teachers = register_teacher::where('user_id', Auth::user()->id)
   ->with(['students' => function ($query) 
   {
       $query->where('status', 'IN SCHOOL')->orWhere('grad_type', 'TARTEEL ZALLA')->orderBy('fullname');
   }])->with('user')
   ->get();
   

   return view('class_students', [
   'teachers' => $teachers,
]);
}

// Show Class Students Names for Hadda
public function studentsHadda()
{

   $teachers = register_teacher::where('user_id', Auth::user()->id)
   ->with(['students' => function ($query) 
   {
       $query->where('status', 'IN SCHOOL')->orWhere('grad_type', 'TARTEEL ZALLA')->orderBy('fullname');
   }])->with('user')
   ->get();
   

   return view('Hadda.studentsHadda', [
   'teachers' => $teachers,
]);

}

// Show Selected Class Teachers Names to Exco
public function selectedClassTeacher($teacher_id)
{
   $class = register_teacher::where('id', $teacher_id)->value('class');

   $malams = register_teacher::with(['students' => function ($query) {
       $query->orderBy('fullname');
   }])
   ->with('user')
   ->where('class', $class)
   ->get();
   

   $teachers = register_teacher::where('user_id', $teacher_id)
   ->with(['students' => function ($query) 
   {
       $query->where('status', 'IN SCHOOL')->orderBy('fullname');
   }])->with('user')
   ->get();
   

   return view('attendance.class_teachers', [
   'teachers' => $teachers,
   'class' => $class,
   'malams' => $malams
]);
}

// Show Selected Class Students Names to Exco
public function selectedClassStudent($teacher_id)
{

   $teachers = register_teacher::where('id', $teacher_id)
   ->with(['students' => function ($query) 
   {
       $query->where('status', 'IN SCHOOL')->orWhere('grad_type', 'TARTEEL ZALLA')->orderBy('fullname');
   }])->with('user')
   ->get();
   

   return view('class_students', [
   'teachers' => $teachers,
]);
}

// Show Selected Class Students Names for Hadda to Exco
public function selectedStudentsHadda($teacher_id)
{

$teacher = register_teacher::where('id', $teacher_id)
            ->with(['students' => function ($query) 
            {
                $query->where('status', 'IN SCHOOL')
                ->orWhere('grad_type', 'TARTEEL ZALLA')
                ->orderBy('fullname');
            }])
            ->first();

            $teacherClass = $teacher->class;

            $studentIds = register_student::where('class', $teacherClass)
            ->pluck('id')
            ->toArray();

            $hadda = Hadda::whereIn('student_id', $studentIds)
            ->latest()
            ->paginate(100);
    
            return view('Hadda.studentsHadda',[
            'teacher' => $teacher, 
            'hadda' => $hadda,   
            ]);

}

}

