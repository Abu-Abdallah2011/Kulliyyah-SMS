<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\register_teacher;
use App\Models\register_guardian;

class ClassesController extends Controller
{
    public function index() 
    {
        $allteachers = register_teacher::distinct('class')->pluck('class');

        $malams = register_teacher::with(['students' => function ($query) {
            $query->orderBy('fullname');
        }])
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
        $teacher = register_teacher::with(['students' => function ($query) {
            $query->orderBy('fullname');
        }])
        ->with('user')
        ->where('user_id', $teacher_id)
        ->firstOrFail();

        $class = $teacher->class;

            $malams = register_teacher::with(['students' => function ($query) {
                $query->orderBy('fullname');
            }])
            ->with('user')
            ->where('class', $class)
            ->get();
    
            return view('dashboard', [
            'teacher' => $teacher,
            'class' => $class,
            'malams' => $malams
        ]);
    }
}

