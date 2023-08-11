<?php

namespace App\Http\Controllers;

use App\Models\sets;
use App\Models\classes;
use App\Models\surasModel;
use Illuminate\Http\Request;
use App\Models\register_teacher;
use App\Http\Requests\TeacherFormRequest;

class TeachersController extends Controller
{
     //Display Teachers Registration Form
     public function create() {

        $classes = classes::all();
        $sets = sets::all();
        return view('teachers_reg_form', ['classes' => $classes, 'sets' => $sets]);
    }


    //Store Teachers Registration Information
    public function store(TeacherFormRequest $request){ 
        $data = $request->validated();

    $selectedOptionId = $request->input('dynamic_select');
    $selectedOption = sets::find($selectedOptionId);

    $selectedClassId = $request->input('select_class');
    $selectedClass = classes::find($selectedClassId);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('TeachersPhoto', 'public');
        }

        $formData = array_merge($data, [ 
            'set' => $selectedOption->set,
            'class' => $selectedClass->class,
        ]);

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

    $classes = classes::all();
    $sets = sets::all();
    $teacher = register_teacher::find($id);
    return view('edit_teacher', compact('teacher', 'classes', 'sets'));
}


// Update Teacher
public function update(TeacherFormRequest $request, $id){
    $data = $request->validated();

    $selectedOptionId = $request->input('dynamic_select');
    $selectedOption = sets::find($selectedOptionId);

    $selectedClassId = $request->input('select_class');
    $selectedClass = classes::find($selectedClassId);

    if ($request->hasFile('photo')) {
        $data['photo'] = $request->file('photo')->store('TeachersPhoto', 'public');
    }

        if ($selectedOption) {

        $data['class'] = $selectedClass->class;

        }
        if ($selectedClass) {

        $data['set'] = $selectedOption->set;

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
