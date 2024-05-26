<?php

namespace App\Http\Controllers;

use App\Models\sessions;
use Illuminate\Http\Request;
use App\Models\register_teacher;
use App\Models\register_student;
use App\Models\SchoolFeesModel;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\SchoolFeesRequest;

class SchoolFeesController extends Controller
{
    // Show Fees Database
public function show()
{
    $teachers = register_teacher::where('user_id', Auth::user()->id)
        ->with(['students' => function ($query) {
            $query->where('status', 'IN SCHOOL')
            ->orWhere('grad_type', 'TARTEEL ZALLA')
            ->orderBy('fullname');
        }])->with('user')
        ->get();
        

    
    $students = register_student::with(['fees' => function($query) {
        $studentIds = register_student::where('status', 'IN SCHOOL')
        ->orWhere('grad_type', 'TARTEEL ZALLA')
        ->pluck('id');
        $query->whereIn('student_id', $studentIds);
        }])
        ->where('status', 'IN SCHOOL')
        ->orWhere('grad_type', 'TARTEEL ZALLA')
        ->orderBy('class')
        ->get();

        $session = sessions::pluck('session')->last();
        $term = sessions::pluck('term')->last();

$currentSession = sessions::pluck('session')->last();
$clearedStatuses = ['PAID', 'FREE'];


        foreach ($students as $student) {
            // Check if a fees record exists for the current term and session
            $existingRecord = SchoolFeesModel::where([
                'student_id' => $student->id,
                'term' => $term,
                'session' => $session
            ])->first();

            // If no record exists, create a new one
            if (!$existingRecord) {
                $fees = new SchoolFeesModel();
                $fees->student_id = $student->id;
                $fees->class = $student->class;
                $fees->term = $term;
                $fees->session = $session;
                $fees->status = 'UNCLEARED';
                $fees->save();
            }

    $allCleared = $student->fees->filter(function($fee) use ($currentSession) {
        return $fee->session != $currentSession;
    })->every(function ($fee) use ($clearedStatuses) {
        return in_array($fee->status, $clearedStatuses);
    });

    if ($allCleared) {
        // Mark student as eligible
        $student->eligible = true;
    } else {
        // Mark student as not eligible
        $student->eligible = false;
    }
        }

$feesRecord = SchoolFeesModel::where('term', $term)
->where('session', $session)
->get(); 

$firstTerm = sessions::where('session', $session)
    ->where(function ($query) {
        $query->where('term', '1st Term')
              ->orWhere('term', '1ST TERM')
              ->orWhere('term', 'FIRST TERM')
              ->orWhere('term', 'First Term');
    })
    ->first();

    $secondTerm = sessions::where('session', $session)
    ->where(function ($query) {
        $query->where('term', '2nd Term')
              ->orWhere('term', '2ND TERM')
              ->orWhere('term', 'SECOND TERM')
              ->orWhere('term', 'Second Term');
    })
    ->first();

    $thirdTerm = sessions::where('session', $session)
    ->where(function ($query) {
        $query->where('term', '3rd Term')
              ->orWhere('term', '3RD TERM')
              ->orWhere('term', 'THIRD TERM')
              ->orWhere('term', 'Third Term');
    })
    ->first();

    $statusIcons = [
        'present' => '<i class="fas fa-check text-green-500"></i>',
        'absent' => '<i class="fas fa-times text-red-500"></i>',
        'late' => '<i class="fas fa-clock text-yellow-500"></i>',
        'excused' => '<i class="fas fa-pencil text-purple-500"></i>',
    ];    
        return view('School Fees.fees_database', 
        compact(
            'teachers', 
            'students', 
            'statusIcons', 
            'feesRecord', 
            'session', 
            'term',
            'firstTerm',
            'secondTerm',
            'thirdTerm',
        ));
}

 // Show Fees Entry Form
 public function create($studentId)
 {

    $student = register_student::where('id', $studentId)->first();
     $teachers = register_teacher::where('user_id', Auth::user()->id)
     ->with(['students' => function ($query) 
     {
         $query->where('status', 'IN SCHOOL')
         ->orWhere('grad_type', 'TARTEEL ZALLA')
         ->orderBy('fullname');
     }])->with('user')
     ->get();
     return view('School Fees.create', 
     [
        'teachers' => $teachers,
        'student' => $student,
    ]);
 }

//Store School Fees Information
public function store(SchoolFeesRequest $request, $studentId){

    $data = $request->validated();
    
    $student = register_student::where('id', $studentId)->first();
    $teacher = register_teacher::where('user_id', Auth::user()->id)->first();
    
    $formData = $request->only([
        'date',
        'status',
        'amount',
        'paid_for',
        'balance',
        'term',
        'session',
        'description',
    ]);
        
    $data = array_merge($formData, [ 
        'class' => $student->class,
        'student_id' => $student->id,
        'added_by' => $teacher->fullname,
    ]);
    
    $save = SchoolFeesModel::create($data);

    return redirect('/fees_database/show')->with(['message' => 'Fees Record saved Successfully!']);
    
    }

         // Show Edit Form
public function edit($studentId, $term, $session){
    $student = register_student::where('id', $studentId)->first();
    $studentTermFees = $student->fees->where('student_id', $studentId)
                                      ->where('term', $term)
                                      ->where('session', str_replace('_', '/', $session))
                                      ->first();
    return view('School Fees.edit_term_fees', compact('studentTermFees', 'student'));
}

//UPDATE School Fees Information
public function update(SchoolFeesRequest $request, $studentId, $term, $session){

    $data = $request->validated();
    
    $student = register_student::where('id', $studentId)->first();
    $teacher = register_teacher::where('user_id', Auth::user()->id)->first();
    
    $formData = $request->only([
        'date',
        'status',
        'amount',
        'paid_for',
        'balance',
        'term',
        'session',
        'description',
    ]);
        
    $data = array_merge($formData, [ 
        'class' => $student->class,
        'student_id' => $student->id,
        'edited_by' => $teacher->fullname,
    ]);
    
    $save = SchoolFeesModel::where('student_id', $studentId)
    ->where('term', $term)
    ->where('session', str_replace('_', '/', $session))
    ->update($data);

    return redirect('/fees_database/show')->with(['message' => 'Fees Record updated Successfully!']);
    
 }

 // Delete Fees Record
public function delete($studentId, $term, $session) {
    $deleteFees = SchoolFeesModel::where('student_id', $studentId)
    ->where('term', $term)
    ->where('session', str_replace('_', '/', $session))
    ->delete();
    return redirect('/fees_database/show')->with(['message' => 'Fees Record deleted Successfully!']);
}

// Show Previous Sessions Fees Records
public function showPreviousSessions($studentId) {
    $session = sessions::pluck('session')->last();

    $paymentStatus = SchoolFeesModel::where('student_id', $studentId)
    // ->where('session', '!=', $session)
    ->get();
    return view('School Fees.ShowPreviousTerms', 
    [
        'session' => $session,
        'studentId' => $studentId,
        'paymentStatus' => $paymentStatus,
    ]
);
}

// Show Reciept For Student For Term And Session
public function showStudentRecieptForTerm($studentId, $term, $session) {

    $student = register_student::where('id', $studentId)
    ->with(['fees' => function ($query) use ($term, $session) 
     {
         $query->where('term', $term)
         ->where('session', str_replace('_', '/', $session));
     }])
     ->first();

     $studentFees = $student->fees
     ->where('term', $term)
     ->where('session', str_replace('_', '/', $session))
     ->first();
     
    return view('School Fees.reciept', 
    [
        'student' => $student,
        'studentFees' => $studentFees,
    ]
);
}

}
