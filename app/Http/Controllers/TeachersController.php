<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\TeacherFormRequest;
use App\Models\register_teacher;

class TeachersController extends Controller
{
     //Display Teachers Registration Form
     public function create() {
        return view('teachers_reg_form');
    }


    //Store Teachers Registration Information
    public function store(TeacherFormRequest $request){ 
        $data = $request->validated();
        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('TeachersPhoto', 'public');
        }
        $teacher = register_teacher::create($data);
        return redirect('/teachers_database')->with('message', 'Maa Shaa Allaah! Teacher Added Successfully! Jazaakumul Laahu Khaira!');
    }


     //Show/Display Teacher in Database
     public function show(){
        return view('teachers_database', ['teachers' => register_teacher::latest()
        ->filter(request(['search']))->paginate(10)]);
    }


    // Show Single Teacher
    public function view($id) {
        $teacher = register_teacher::find($id);
        $user = $teacher->user;
        return view('single_teacher', compact('teacher', 'user'));
    }


    // Show Edit Form
public function edit($id){
    $teacher = register_teacher::find($id);
    return view('edit_teacher', compact('teacher'));
}


// Update Teacher
public function update(TeacherFormRequest $request, $id){
    $data = $request->validated();
    if ($request->hasFile('photo')) {
        $data['photo'] = $request->file('photo')->store('TeachersPhoto', 'public');
    }

    $teacher = register_teacher::where('id', $id)->update($data);

    return redirect('/teachers_database')->with('message', 'Maa Shaa Allaah! Teacher Updated Successfully! Jazaakumul Laahu Khaira!');
}


// Delete Teacher
public function delete($id) {
    $student = register_teacher::where('id', $id)->delete();
    return redirect('/teachers_database')->with('message', 'Maa Shaa Allaah! Teacher Deleted Successfully! Jazaakumul Laahu Khaira!');
}
}
