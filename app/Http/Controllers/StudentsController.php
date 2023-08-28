<?php

namespace App\Http\Controllers;

use App\Models\sets;
use App\Models\classes;
use Illuminate\Http\Request;
use App\Models\register_student;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StudentFormRequest;
use Symfony\Component\HttpFoundation\RequestStack;

class StudentsController extends Controller
{

    //Display Student Registration Form
    public function create() {
        $classes = classes::all();
        $sets = sets::all();
        return view('students_registration_form', ['classes' => $classes, 'sets' => $sets]);
    }


    //Store Students Registration Information
    public function store(StudentFormRequest $request){
        
        $data = $request->validated();

    $selectedOptionId = $request->input('dynamic_select');
    $selectedOption = sets::find($selectedOptionId);

    $selectedClassId = $request->input('select_class');
    $selectedClass = classes::find($selectedClassId);
        
        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('StudentsPhoto', 'public');
        }

        if ($selectedOption) {

            $data['class'] = $selectedClass->class;
    
            }
            if ($selectedClass) {
    
            $data['set'] = $selectedOption->set;
    
            }

        $student = register_student::create($data);

        return redirect('/students_database')->with('message', 'Maa Shaa Allaah! Student Added Successfully! Jazaakumul Laahu Khaira!');
    }


    //Show/Display Student in Database
    public function show(){

        return view('students_database', ['students' => register_student::latest()
        ->filter(request(['search']))->paginate(10)]);
    }


    //Show Single Student
public function view($id) {
    $student = register_student::find($id);
    return view('single_student', compact('student'));
}


// Show Edit Form
public function edit($id){

    $classes = classes::all();
        $sets = sets::all();

    $student = register_student::find($id);
    return view('edit_student', compact('student', 'classes', 'sets'));
}


// Edit Student
public function update(StudentFormRequest $request, $id){

    // dd([
    //     'driver' => 's3',
    //     'key' => env('AWS_ACCESS_KEY_ID'),
    //     'secret' => env('AWS_SECRET_ACCESS_KEY'),
    //     'region' => env('AWS_DEFAULT_REGION'),
    //     'bucket' => env('AWS_BUCKET'),
    //     'url' => env('AWS_URL'),
    //     'endpoint' => env('AWS_ENDPOINT'),
    //     'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
    //     'throw' => false,
    // ],);
        
    $data = $request->validated();

    $selectedOptionId = $request->input('dynamic_select');
    $selectedOption = sets::find($selectedOptionId);

    $selectedClassId = $request->input('select_class');
    $selectedClass = classes::find($selectedClassId);

    if ($request->hasFile('photo')) {
        $data['photo'] = $request->file('photo')->store('StudentsPhoto', 's3');
    }

    if ($selectedOption) {

        $data['class'] = $selectedClass->class;

        }
        if ($selectedClass) {

        $data['set'] = $selectedOption->set;

        }

    $student = register_student::where('id', $id)->update($data);

    if (Gate::allows('isAdmin')) {
    return redirect('/students_database')->with('message', 'Maa Shaa Allaah! Student Updated Successfully! Jazaakumul Laahu Khaira!');
    }
    else
    {
        return redirect('/class_students')->with('message', 'Maa Shaa Allaah! Student Transferred Successfully!');
    }
}


// Delete Student
public function delete($id) {
    $student = register_student::where('id', $id)->delete();
    return redirect('/students_database')->with('message', 'Maa Shaa Allaah! Student Deleted Successfully! Jazaakumul Laahu Khaira!');
}

//Show/Display Graduate in Database
public function showGraduates(){

    return view('Graduates.graduates_database', ['students' => register_student::where('status', 'GRADUATE')
    ->latest()
    ->filter(request(['search']))->paginate(10)]);
}
}
