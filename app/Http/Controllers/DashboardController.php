<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use App\Models\register_teacher;
use App\Models\register_guardian;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Foundation\Auth\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function view()
     {
        $user = Auth::user();
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
            $query->orderBy('fullname');
        }])->with('user')
        ->get();
        
        $guardians = register_guardian::where('user_id', Auth::user()->id)->with('students')->get();

        return view('dashboard', [
        'teachers' => $teachers,
        'guardians' => $guardians,
        'class' => $class,
        'malams' => $malams
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
