<?php

namespace App\Http\Controllers;

use App\Models\Hadda;
use App\Models\sessions;
use Illuminate\Http\Request;
use App\Models\register_student;
use App\Models\register_teacher;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\HaddaFormRequest;

class HaddaController extends Controller
{
    // Show Hadda Page
    public function show($student_id)
    {
        $id = register_student::where('id', $student_id)
        ->value('id');

        $hadda = Hadda::latest()
        ->filter(request(['search']))
        ->with(['student'])
        ->where('student_id', $id)
        ->paginate(10);

        $student = register_student::where('id', $student_id)->first();
        return view('Hadda.hadda_page',[
        'student' => $student, 
        'hadda' => $hadda,   
        ]);
    }

    // Show Hadda Entry Form Page
    public function create($student_id)
    {
        $student = register_student::where('id', $student_id)->first();
        return view('Hadda.HaddaForm',[
            'student' => $student,    
            ]);
    }

       //Store Hadda Information
 public function store(HaddaFormRequest $request, $student_id){

    $data = $request->validated();
    
    $student = register_student::where('id', $student_id)->first();
    $teacher = register_teacher::where('user_id', Auth::user()->id)->first();
    $sessions = sessions::first();
    
    $formData = $request->only([
        'date',
        'sura',
        'from',
        'to',
        'grade',
        'comment'
    ]);
        
    $data = array_merge($formData, [
        'class' => $student->class,
        'name' => $student->fullname,
        'teacher' => $teacher->fullname,
        'session' => $sessions->session,
        'term' => $sessions->term,
        'student_id' => $student->id
    ]);
    
    $save = Hadda::create($data);
$hadda = Hadda::where('student_id', $student_id)->get();

    return redirect()->route('hadda_page.show', ['student_id' => $student_id])->with([
        
        'message' => 'Hadda Recorded Successfully!'
    ]);
    
 }

 // Show Edit Form
public function edit($id){
    $hadda = Hadda::find($id);
    return view('Hadda.edit_hadda', compact('hadda'));
}

      //update Hadda Information
      public function update(HaddaFormRequest $request, $id){

        $data = $request->validated();
        
        $entry = Hadda::find($id);
        $student = register_student::where('id', $entry->student_id)->first();

        $teacher = register_teacher::where('user_id', Auth::user()->id)->first();
        $sessions = sessions::first();
        
        $formData = $request->only([
            'date',
            'sura',
            'from',
            'to',
            'grade',
            'comment'
        ]);
            
        $data = array_merge($formData, [
            'class' => $student->class,
            'name' => $student->fullname,
            'teacher' => $teacher->fullname,
            'session' => $sessions->session,
            'term' => $sessions->term,
            'student_id' => $student->id
        ]);
        
        $save = Hadda::where('id', $id)->update($data);
    $hadda = Hadda::where('student_id', $id)->get();
    
        return redirect()->route('hadda_page.show', ['student_id' => $student->id])->with([
            
            'message' => 'Hadda Record Updated Successfully!'
        ]);
        
     }

// Delete Curriculum
public function delete($id) {
    $student = Hadda::where('id', $id)->delete();
    return back()->with('message', 'Hadda Record Deleted Successfully!');
}

}
