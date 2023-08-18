<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use App\Models\register_teacher;
use App\Models\register_student;
use App\Models\register_guardian;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Foundation\Auth\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    // Show the Dashboard of User after Authentication
    public function view()
     {
        $class = register_teacher::where('user_id', Auth::user()->id)->value('class');

        $teachers = register_teacher::where('class', $class)
        ->with(['students' => function ($query) 
        {
            $query->where('status', 'IN SCHOOL')->orderBy('fullname');
        }])->with('user')
        ->get();

        $teacher = register_teacher::with(['students' => function ($query) {
            $query->orderBy('fullname');
        }])
        ->with('user')
        ->where('id', Auth::user()->id)
        ->get();
        
        $guardians = register_guardian::where('user_id', Auth::user()->id)->with('students')->get();

        $graduates = register_student::where('status', 'GRADUATE');

        return view('dashboard', [
        'teachers' => $teachers,
        'guardians' => $guardians,
        'class' => $class,
        'teacher' => $teacher,
        'graduates' => $graduates,
    ]);
    }
    

    // show the dashboard of selected Guardian for Admin
public function show($guardian_id)
{
    $guardian = register_guardian::findOrFail($guardian_id);
    $students = $guardian->students;
    
    return view('dashboard', [
        'guardian' => $guardian,
        'students' => $students,
    ]);
}


}