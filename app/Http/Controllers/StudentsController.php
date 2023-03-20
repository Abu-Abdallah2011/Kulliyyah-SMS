<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\register_student;
use App\Http\Requests\StudentFormRequest;
use Symfony\Component\HttpFoundation\RequestStack;
use Illuminate\Support\Facades\Storage;

class StudentsController extends Controller
{

    //Display Student Registration Form
    public function create() {
        return view('students_registration_form');
    }


    //Store Students Registration Information
    public function store(StudentFormRequest $request){
        
        $data = $request->validated();
        
        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('StudentsPhoto', 'public');
        }

        $student = register_student::create($data);

        return redirect('/students_database')->with('message', 'Maa Shaa Allaah! Student Added Successfully! Jazaakumul Laahu Khaira!');
    }


    //Show/Display Student in Database
    public function show(){

        return view('students_database', ['students' => register_student::latest()
        ->filter(request(['search']))->paginate(6)]);
    }


    //Show Single Student
public function view($id) {
    $student = register_student::find($id);
    return view('single_student', compact('student'));
}


// Show Edit Form
public function edit($id){
    $student = register_student::find($id);
    return view('edit_student', compact('student'));
}


// Edit Student
public function update(StudentFormRequest $request, $id){
        
    $data = $request->validated();

    if ($request->hasFile('photo')) {
        $data['photo'] = $request->file('photo')->store('StudentsPhoto', 'public');
    }

    $student = register_student::where('id', $id)->update($data);
    
    return redirect('/students_database')->with('message', 'Maa Shaa Allaah! Student Updated Successfully! Jazaakumul Laahu Khaira!');
}


// Delete Student
public function delete($id) {
    $student = register_student::where('id', $id)->delete();
    return redirect('/students_database')->with('message', 'Maa Shaa Allaah! Student Deleted Successfully! Jazaakumul Laahu Khaira!');
}
}
