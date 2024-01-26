<?php

namespace App\Http\Controllers;

use App\Models\sets;
use App\Models\classes;
use App\Models\surasModel;
use Illuminate\Http\Request;
use App\Models\register_teacher;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
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

        $adder = register_teacher::where('user_id', Auth::user()->id)
        ->first();
        
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

            $data['created_by'] = $adder->fullname;

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

    $adder = register_teacher::where('user_id', Auth::user()->id)
    ->first();

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

        $data['edited_by'] = $adder->fullname;

    $teacher = register_teacher::where('id', $id)->update($data);

    if (Gate::allows('isAdmin')) {
    return redirect('/teachers_database')->with('message', 'Maa Shaa Allaah! Teacher Updated Successfully! Jazaakumul Laahu Khaira!');
    }
    else
    {
        return redirect('/dashboard')->with('message', 'Maa Shaa Allaah! You have been Transferred Successfully!'); 
    }
}


// Delete Teacher
public function delete($id) {
    $student = register_teacher::where('id', $id)->delete();
    return redirect('/teachers_database')->with('message', 'Maa Shaa Allaah! Teacher Deleted Successfully! Jazaakumul Laahu Khaira!');
}
}
