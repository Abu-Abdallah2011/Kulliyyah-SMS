<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Foundation\Http\FormRequest;

class Users_controller extends Controller
{
    // Show Users Details in Database
    public function show()
    {
        $query = User::latest()->filter(request(['search']));

        if (!Auth::user()->can('isSuperAdmin')) {
            $query->where('role', '!=', 'SUPER_ADMIN');
        }

        return view('users_database', ['users' => $query->paginate(10)]);
    }

   // Show Edit Form
public function edit($id){
    $user = User::findOrFail($id);

    if (!Auth::user()->can('isSuperAdmin') && $user->role === 'SUPER_ADMIN') {
        abort(403);
    }

    return view('edit_user', compact('user'));
}

// Edit User
public function update(UserUpdateRequest $request, $id){
    $targetUser = User::findOrFail($id);

    if (!Auth::user()->can('isSuperAdmin') && $targetUser->role === 'SUPER_ADMIN') {
        abort(403);
    }

    $data = $request->validated();

    if (!Auth::user()->can('isSuperAdmin') && ($data['role'] ?? null) === 'SUPER_ADMIN') {
        return back()->with('message', 'Only Super Admin can assign Super Admin role.');
    }

    $user = User::where('id', $id)->update($data);

    return redirect('/users_database')->with('message', 'Maa Shaa Allaah! User Updated Successfully! Jazaakumul Laahu Khaira!');;
}

// Delete User
public function delete($id) {
    $targetUser = User::findOrFail($id);

    if (!Auth::user()->can('isSuperAdmin') && $targetUser->role === 'SUPER_ADMIN') {
        abort(403);
    }

    $user = User::where('id', $id)->delete();
    return back()->with('message', 'Maa Shaa Allaah! User Deleted Successfully! Jazaakumul Laahu Khaira!');
}

}
