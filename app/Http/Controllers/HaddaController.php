<?php

namespace App\Http\Controllers;

use App\Models\Hadda;
use App\Models\sessions;
use Illuminate\Http\Request;
use App\Models\register_student;
use App\Models\register_teacher;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\HaddaFormRequest;
use App\Models\surasModel;

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

        // Show Hadda Status
        public function showStatus($teacher_id)
        {

            $teacher = register_teacher::where('user_id', Auth::user()->id)
            ->with(['students' => function ($query) 
            {
                $query->where('status', 'IN SCHOOL')
                ->orWhere('grad_type', 'TARTEEL ZALLA')
                ->orderBy('fullname');
            }])
            ->first();
            
            $teacherClass = $teacher->class;

            $studentIds = register_student::where('class', $teacherClass)
            ->pluck('id')
            ->toArray();

            $hadda = Hadda::whereIn('student_id', $studentIds)
            ->latest()
            ->paginate(100);
    
            return view('Hadda.studentsHadda',[
            'teacher' => $teacher, 
            'hadda' => $hadda,   
            ]);
        }

    // Show Hadda Entry Form Page
    public function create($student_id)
    {
        $sura = surasModel::all();
        $student = register_student::where('id', $student_id)->first();
        return view('Hadda.HaddaForm',[
            'student' => $student, 'sura' => $sura   
            ]);
    }

       //Store Hadda Information
 public function store(HaddaFormRequest $request, $student_id){

    $data = $request->validated();

    $selectedOptionId = $request->input('dynamic_select');
    
    $student = register_student::where('id', $student_id)->first();
    $teacher = register_teacher::where('user_id', Auth::user()->id)->first();
    $sessions = sessions::orderBy('created_at', 'desc')->first();
    $selectedOption = surasModel::find($selectedOptionId);
    
    $formData = $request->only([
        'date',
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
        'student_id' => $student->id,
        'sura' => $selectedOption->sura,
    ]);
    
    $save = Hadda::create($data);
$hadda = Hadda::where('student_id', $student_id)->get();

    return redirect('studentsHadda/{teacher_id}')->with(['message' => 'Hadda Recorded Successfully!']);
    
 }

 // Show Edit Form
public function edit($id){
    $sura = surasModel::all();
    $hadda = Hadda::find($id);
    return view('Hadda.edit_hadda', compact('hadda', 'sura'));
}

      //update Hadda Information
      public function update(HaddaFormRequest $request, $id){

        $data = $request->validated();
        $selectedOptionId = $request->input('dynamic_select');
        
        $entry = Hadda::find($id);
        $student = register_student::where('id', $entry->student_id)->first();

        $teacher = register_teacher::where('user_id', Auth::user()->id)->first();
        $sessions = sessions::orderBy('created_at', 'desc')->first();

        $selectedOption = surasModel::find($selectedOptionId);
        
        $formData = $request->only([
            'date',
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
            'student_id' => $student->id,
        ]);

        if ($selectedOption) {

            $data['sura'] = $selectedOption->sura;
    
            }
        
        $save = Hadda::where('id', $id)->update($data);
    $hadda = Hadda::where('student_id', $id)->get();
    
        return redirect('studentsHadda/{teacher_id}')->with(['message' => 'Hadda Record Updated Successfully!']);
        
     }

// Delete Hadda
public function delete($id) {
    $student = Hadda::where('id', $id)->delete();
    return redirect('studentsHadda/{teacher_id}')->with('message', 'Hadda Record Deleted Successfully!');
}

}
