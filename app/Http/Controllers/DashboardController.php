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

        $teachers = register_teacher::where('user_id', Auth::user()->id)
        ->with(['students' => function ($query) 
        {
            $query->orderBy('fullname');
        }])->with('user')
        ->get();
        
        $guardians = register_guardian::where('user_id', Auth::user()->id)->with('students')->get();

        return view('/dashboard', ['teachers' => $teachers, 'guardians' => $guardians]);
    }


}
