<?php

namespace App\Http\Controllers;

use App\Models\sessions;
use Illuminate\Http\Request;
use App\Models\register_student;
use App\Models\register_teacher;
use App\Models\register_guardian;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Foundation\Http\FormRequest;

class DashboardController extends Controller
{
    // Show the Dashboard of User after Authentication
    public function view()
     {
        $class = register_teacher::where('user_id', Auth::user()->id)->value('class');

        $teachers = register_teacher::where('class', $class)
        ->with(['students' => function ($query) 
        {
            $query->where('status', 'IN SCHOOL')
            ->orWhere('grad_type', 'TARTEEL ZALLA')
            ->orderBy('fullname');
        }])->with('user')
        ->get();


        $teacher = register_teacher::where('class', $class)->where('user_id', auth()->user()->id)
        ->with(['students' => function ($query) 
        {
            $query->where('status', 'IN SCHOOL')
            ->orWhere('grad_type', 'TARTEEL ZALLA')
            ->orderBy('fullname');
        }])
        ->first();
        
        $guardians = register_guardian::where('user_id', Auth::user()->id)->with('students')->get();

        $graduates = register_student::where('status', 'GRADUATE');

        $session = sessions::orderBy('created_at', 'desc')->first();

        return view('dashboard', [
        'teacher' => $teacher,
        'teachers' => $teachers,
        'guardians' => $guardians,
        'class' => $class,
        'graduates' => $graduates,
        'session' => $session,
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