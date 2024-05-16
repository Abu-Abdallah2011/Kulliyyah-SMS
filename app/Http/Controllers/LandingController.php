<?php

namespace App\Http\Controllers;

use App\Models\register_student;
use App\Models\register_teacher;
use App\Models\subjectsModel;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index() {
        $students = register_student::where('status', 'IN SCHOOL')->get();

        $teachers = register_teacher::where('status', 'IN SCHOOL')->get();

        $subjects = subjectsModel::get();

        return view('index',[
            'students' => $students, 
            'teachers' => $teachers,   
            'subjects' => $subjects,   
            ]);
    }
}
