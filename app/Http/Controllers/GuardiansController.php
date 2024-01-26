<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\register_teacher;
use App\Models\register_guardian;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\GuardianFormRequest;

class GuardiansController extends Controller
{
   //Display Guardians Registration Form
   public function create() {
    return view('guardians_reg_form');
}

 //Store Guardians Registration Information

 public function store(GuardianFormRequest $request){

    $adder = register_teacher::where('user_id', Auth::user()->id)
    ->first();
        
    $data = $request->validated();

    $data['created_by'] = $adder->fullname;

    $guardian = register_guardian::create($data);

    return redirect('/guardians_database')->with('message', 'Maa Shaa Allaah! Guardian Added Successfully! Jazaakumul Laahu Khaira!');
}


//Show/Display Guardian in Database
public function show(){

    return view('guardians_database', ['guardians' => register_guardian::latest()
    ->filter(request(['search']))->paginate(10)]);
}


// Show Single Guardian
public function view($id) {
    $guardian = register_guardian::find($id);
    $user = $guardian->user;
    return view('single_guardian', compact('guardian', 'user'));
}

   // Show Edit Form
   public function edit($id){
    $guardian = register_guardian::find($id);
    return view('edit_guardian', compact('guardian'));
}


// Update Guardian
public function update(GuardianFormRequest $request, $id){

    $adder = register_teacher::where('user_id', Auth::user()->id)
    ->first();
        
    $data = $request->validated();

    $data['edited_by'] = $adder->fullname;

    $guardian = register_guardian::where('id', $id)->update($data);

    return redirect('/guardians_database')->with('message', 'Maa Shaa Allaah! Guardian Updated Successfully! Jazaakumul Laahu Khaira!');
}


// Delete Guardian
public function delete($id) {
    $student = register_guardian::where('id', $id)->delete();
    return redirect('/guardians_database')->with('message', 'Maa Shaa Allaah! Guardian Deleted Successfully! Jazaakumul Laahu Khaira!');
}

}
