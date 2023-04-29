<?php

namespace App\Http\Controllers;

use App\Models\sessions;
use App\Models\curriculum;
use Illuminate\Http\Request;
use App\Models\register_student;
use App\Models\register_teacher;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CurriculumFormRequest;

class CurriculumController extends Controller
{

    // Show Curriculum Scale Data Entry Form
    public function create()
    {
        return view('CurriculumScale.curriculum_form');
    }
    //Store Curriculum Information
 public function store(CurriculumFormRequest $request){

    $data = $request->validated();

    $teacher = register_teacher::where('user_id', Auth::user()->id)->first();
    $sessions = sessions::first();
    $formData = $request->only([
        'date',
        'sura',
        'from',
        'to',
        'times',
        'bita',
        'grade',
        'hadda',
        'comment'
    ]);
        
    $data = array_merge($formData, [
        'class' => $teacher->class,
        'teacher' => $teacher->fullname,
        'set' => $teacher->set,
        'session' => $sessions->session,
        'term' => $sessions->term
    ]);

    $curriculum = curriculum::create($data);

    return redirect('/curriculum_scale')->with('message', 'Curriculum Added Successfully!');
}

//Show/Display Curriculum in Database
public function show(){

    $set = register_teacher::where('user_id', Auth::user()->id)->value('set');
    // $sessions = sessions::get();

        $sets = curriculum::latest()->filter(request(['search']))->with(['teacher'])
        ->where('set', $set)
        ->paginate(10);

    return view('CurriculumScale.curriculum_scale', [
        // 'sessions' => $sessions,
        'set' => $set,
        'sets' => $sets,
    //     'curriculums' => curriculum::latest()
    // ->filter(request(['search']))->paginate(10)]);
    ]);
    
}

// Show Edit Form
public function edit($id){
    $curriculum = curriculum::find($id);
    return view('CurriculumScale.edit_curriculum', compact('curriculum'));
}

// Update Curriculum
public function update(CurriculumFormRequest $request, $id){
        
    $data = $request->validated();
    $teacher = register_teacher::where('user_id', Auth::user()->id)->first();
    $sessions = sessions::first();
    $formData = $request->only([
        'date',
        'sura',
        'from',
        'to',
        'times',
        'bita',
        'grade',
        'hadda',
        'comment'
    ]);
        
    $data = array_merge($formData, [
        'class' => $teacher->class,
        'teacher' => $teacher->fullname,
        'set' => $teacher->set,
        'session' => $sessions->session,
        'term' => $sessions->term

    ]);

    $curriculum = curriculum::where('id', $id)->update($data);

    return redirect('/curriculum_scale')->with('message', 'Curriculum Updated Successfully!');
}

// Delete Curriculum
public function delete($id) {
    $student = curriculum::where('id', $id)->delete();
    return redirect('/curriculum_scale')->with('message', 'Curriculum Deleted Successfully!');
}


// Display curriculum scale of selected teacher to admin
public function display($teacher_id)
{
    $set = register_teacher::where('id', $teacher_id)->value('set');

    $sets = curriculum::latest()->filter(request(['search']))->with(['teacher'])
    ->where('set', $set)
    ->paginate(10);

return view('CurriculumScale.curriculum_scale', [
    // 'sessions' => $sessions,
    'set' => $set,
    'sets' => $sets,
]);
}

// Display class curriculum scale for whoever clicks the button on single student details
public function displayForGuardian($student_id)
{
    $set = register_student::where('id', $student_id)->value('set');

    $sets = curriculum::latest()->filter(request(['search']))->with(['student'])
    ->where('set', $set)
    ->paginate(10);

return view('CurriculumScale.curriculum_scale', [
    // 'sessions' => $sessions,
    'set' => $set,
    'sets' => $sets,
]);
}
}
