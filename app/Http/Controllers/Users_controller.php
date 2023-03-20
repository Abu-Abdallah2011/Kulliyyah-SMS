<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Foundation\Http\FormRequest;

class Users_controller extends Controller
{
    // Show Users Details in Database
    public function show()
    {
        return view('users_database', ['users' => User::latest()
        ->filter(request(['search']))->paginate(6)
        ]);
    }

   // Show Edit Form
public function edit($id){
    $user = User::find($id);
    return view('edit_user', compact('user'));
}

// Edit Student
public function update(UserUpdateRequest $request, $id){
        
    $data = $request->validated();

    $user = User::where('id', $id)->update([
        'name'=>$data['name'],
        'username'=>$data['username'],
        'role'=>$data['role'],
        'email'=>$data['email'],
    ]);

    return redirect('/users_database')->with('message', 'Maa Shaa Allaah! User Updated Successfully! Jazaakumul Laahu Khaira!');;
}

// Delete User
public function delete($id) {
    $user = User::where('id', $id)->delete();
    return back()->with('message', 'Maa Shaa Allaah! User Deleted Successfully! Jazaakumul Laahu Khaira!');
}

}
