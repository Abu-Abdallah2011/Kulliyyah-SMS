<?php

namespace App\Http\Controllers;

use App\Models\sessions;
use App\Models\ExamsModel;
use App\Models\register_student;
use Illuminate\Http\Request;
use App\Models\register_teacher;
use App\Models\subjectsModel;
use Illuminate\Support\Facades\Auth;

class ExamsController extends Controller
{
    //Show/Display Exams Clean Sheet
public function show(){

    $sessions = sessions::first();

    $exam = ExamsModel::get();

    $class = register_teacher::where('user_id', Auth::user()->id)->value('class');

        $teacher = register_teacher::where('class', $class)->where('user_id', auth()->user()->id)
        ->with(['students' => function ($query) 
        {
            $query->where('status', 'IN SCHOOL')
            ->orWhere('grad_type', 'TARTEEL ZALLA')
            // ->orderBy('fullname');
            ;
        }, 'students.attendance'])
        ->first();

        $totalCa = [];

        $attendanceRecords = [];

foreach ($teacher->students as $student) {
    $totalCa[$student->id] = [];

    $attendanceRecords[$student->id] = $student->attendance
    ->where('session', $sessions->session)
    ->where('term', $sessions->term)
    ->filter(function ($record) {
        return in_array($record->status, ['Present', 'present', 'Late', 'late', 'excused', 'Excused']);
    });

    $totalAttendanceRecords = $student->attendance
    ->where('session', $sessions->session)
    ->where('term', $sessions->term)
    ->count();
    $presentAttendanceRecords = $attendanceRecords[$student->id]->count();
    $percentage = $totalAttendanceRecords > 0 ? ($presentAttendanceRecords / $totalAttendanceRecords) * 100 : 0;
    $student->attendancePercentage = $percentage;
    
    foreach ($student->exams as $subject) {

        // $matchingSubjects = $subjects->where('session', $sessions->session)
        //                           ->where('term', $sessions->term)
        //                           ->where('student_id', $student->id)
        //                           ->get();

        // foreach ($matchingSubjects as $subject) {
        // Calculate the total CA score for this subject and student
        $first_cas = is_numeric($subject->first_ca) ? $subject->first_ca : 0;
        $second_cas = is_numeric($subject->second_ca) ? $subject->second_ca : 0;
        $third_cas = is_numeric($subject->third_ca) ? $subject->third_ca : 0;

        $totalCa[$student->id][$subject->subject_id] = $first_cas + $second_cas + $third_cas;
        }
    }
// }

// Initialize an array to store the total scores for each student
$totalScores = [];
$averageTotal = [];

foreach ($teacher->students as $student) {
    $totalScores[$student->id] = 0;
    $averageTotal[$student->id] = 0; 
    
    foreach ($student->exams as $subject) {

//         $matchingSubjects = $subjects->where('session', $sessions->session)
//         ->where('term', $sessions->term)
//         ->where('student_id', $student->id)
//         ->get();

// foreach ($matchingSubjects as $subject) {

        // Calculate the total CA score for this subject and student
        $first_cas = is_numeric($subject->first_ca) ? $subject->first_ca : 0;
        $second_cas = is_numeric($subject->second_ca) ? $subject->second_ca : 0;
        $third_cas = is_numeric($subject->third_ca) ? $subject->third_ca : 0;
        $examss = is_numeric($subject->exams) ? $subject->exams : 0;

        $totalScores[$student->id] +=  $first_cas + $second_cas + $third_cas + $examss;
// }
    }

    $averageTotal[$student->id] = count($student->exams->where('session', $sessions->session)->where('term', $sessions->term)) > 0 
    ? $totalScores[$student->id] / count($student->exams->where('session', $sessions->session)->where('term', $sessions->term)) : 0;

    $student->averageTotal = $averageTotal;
}

// =====================================================
// POSITION CODES
// =====================================================

// Function to add ordinal suffix
function addOrdinalSuffix($position) {
    if ($position % 100 >= 11 && $position % 100 <= 13) {
        return $position . 'th';
    } else {
        switch ($position % 10) {
            case 1:
                return $position . 'st';
            case 2:
                return $position . 'nd';
            case 3:
                return $position . 'rd';
            default:
                return $position . 'th';
        }
    }
}

$orderedStudents = $teacher->students->map(function ($student) {
    $sessions = sessions::first();
    $totalScores = 0;
    $examCount = count($student->exams->where('session', $sessions->session)->where('term', $sessions->term));

    $sessions = sessions::first();

    foreach ($student->exams as $subjects) {

        $matchingSubjects = $subjects->where('session', $sessions->session)
        ->where('term', $sessions->term)
        ->where('student_id', $student->id)
        ->get();

foreach ($matchingSubjects as $subject) {

    $first_cas = is_numeric($subject->first_ca) ? $subject->first_ca : 0;
    $second_cas = is_numeric($subject->second_ca) ? $subject->second_ca : 0;
    $third_cas = is_numeric($subject->third_ca) ? $subject->third_ca : 0;
    $examss = is_numeric($subject->exams) ? $subject->exams : 0;

        $totalScores +=  $first_cas + $second_cas + $third_cas + $examss;
}
    }

    $averageTotal = $examCount > 0 ? $totalScores / $examCount : 0;
    $student->averageTotal = $averageTotal;

    return $student;
})->sortByDesc('averageTotal');
$position = 1;
$previousAverage = null;

foreach ($orderedStudents as $student) {
    if ($previousAverage !== null && $student->averageTotal < $previousAverage) {
        $position++;
    }

    $student->position = addOrdinalSuffix($position);
    $previousAverage = $student->averageTotal;
}

 // =====================================================
// END OF POSITION CODES
// =====================================================


    return view('Exams.cleansheet', ['exam' => $exam,
                                    'sessions' => $sessions,
                                    'class' => $class,
                                    'teacher' => $teacher,
                                    'totalCa' => $totalCa,
                                    'totalScores' => $totalScores,
                                    'averageTotal' => $averageTotal,
                                    'percentage' => $percentage,
                                    'orderedStudents' => $orderedStudents,
]);
}

// Show Cleansheet for selected Teacher to Executive
public function selectedTeacherExams($teacher_id){

    $sessions = sessions::first();

    $exam = ExamsModel::get();

    $class = register_teacher::where('id', $teacher_id)->value('class');

        $teacher = register_teacher::where('class', $class)->where('id', $teacher_id)
        ->with(['students' => function ($query) 
        {
            $query->where('status', 'IN SCHOOL')
            ->orWhere('grad_type', 'TARTEEL ZALLA')
            // ->orderBy('fullname');
            ;
        }, 'students.attendance'])
        ->first();

        $totalCa = [];

        $attendanceRecords = [];

foreach ($teacher->students as $student) {
    $totalCa[$student->id] = [];
    
    $attendanceRecords[$student->id] = $student->attendance->filter(function ($record) {
        return in_array($record->status, ['Present', 'present', 'Late', 'late', 'excused', 'Excused']);
    });

    $totalAttendanceRecords = $student->attendance->count();
    $presentAttendanceRecords = $attendanceRecords[$student->id]->count();
    $percentage = $totalAttendanceRecords > 0 ? ($presentAttendanceRecords / $totalAttendanceRecords) * 100 : 0;
    $student->attendancePercentage = $percentage;
    
    foreach ($student->exams as $subject) {
        // Calculate the total CA score for this subject and student
        $first_ca = is_numeric($subject['1st_ca']) ? $subject['1st_ca'] : 0;
        $second_ca = is_numeric($subject['2nd_ca']) ? $subject['2nd_ca'] : 0;
        $third_ca = is_numeric($subject['3rd_ca']) ? $subject['3rd_ca'] : 0;
        $exams = is_numeric($subject['exams']) ? $subject['exams'] : 0;

        $totalCa[$student->id][$subject->subject_id] = $first_ca + $second_ca + $third_ca;
    }
}

// Initialize an array to store the total scores for each student
$totalScores = [];
$averageTotal = [];

foreach ($teacher->students as $student) {
    $totalScores[$student->id] = 0;
    $averageTotal[$student->id] = 0; 
    
    foreach ($student->exams as $subject) {

        $first_ca = is_numeric($subject['1st_ca']) ? $subject['1st_ca'] : 0;
        $second_ca = is_numeric($subject['2nd_ca']) ? $subject['2nd_ca'] : 0;
        $third_ca = is_numeric($subject['3rd_ca']) ? $subject['3rd_ca'] : 0;
        $exams = is_numeric($subject['exams']) ? $subject['exams'] : 0;

        $totalScores[$student->id] +=  $first_ca + $second_ca + $third_ca + $exams;
    }

    $averageTotal[$student->id] = count($student->exams) > 0 ? $totalScores[$student->id] / count($student->exams) : 0;

    $student->averageTotal = $averageTotal;
}

// =====================================================
// POSITION CODES
// =====================================================

// Function to add ordinal suffix
function teacherOrdinalSuffix($position) {
    if ($position % 100 >= 11 && $position % 100 <= 13) {
        return $position . 'th';
    } else {
        switch ($position % 10) {
            case 1:
                return $position . 'st';
            case 2:
                return $position . 'nd';
            case 3:
                return $position . 'rd';
            default:
                return $position . 'th';
        }
    }
}

// $orderedStudents = $teacher->students->sortByDesc('averageTotal');
$orderedStudents = $teacher->students->map(function ($student) {
    $totalScores = 0;
    $examCount = count($student->exams);

    foreach ($student->exams as $subject) {
        $first_ca = is_numeric($subject['1st_ca']) ? $subject['1st_ca'] : 0;
        $second_ca = is_numeric($subject['2nd_ca']) ? $subject['2nd_ca'] : 0;
        $third_ca = is_numeric($subject['3rd_ca']) ? $subject['3rd_ca'] : 0;
        $exams = is_numeric($subject['exams']) ? $subject['exams'] : 0;

        $totalScores +=  $first_ca + $second_ca + $third_ca + $exams;
    }

    $averageTotal = $examCount > 0 ? $totalScores / $examCount : 0;
    $student->averageTotal = $averageTotal;

    return $student;
})->sortByDesc('averageTotal');
$position = 1;
$previousAverage = null;

foreach ($orderedStudents as $student) {
    if ($previousAverage !== null && $student->averageTotal < $previousAverage) {
        $position++;
    }

    $student->position = teacherOrdinalSuffix($position);
    $previousAverage = $student->averageTotal;
}

 // =====================================================
// END OF POSITION CODES
// =====================================================


    return view('Exams.cleansheet', ['exam' => $exam,
                                    'sessions' => $sessions,
                                    'class' => $class,
                                    'teacher' => $teacher,
                                    'totalCa' => $totalCa,
                                    'totalScores' => $totalScores,
                                    'averageTotal' => $averageTotal,
                                    'percentage' => $percentage,
                                    'orderedStudents' => $orderedStudents,
]);
}


 //Show/Display Exams Edit Form
 public function edit($id){

    $sessions = sessions::first();

    $class = register_teacher::where('user_id', Auth::user()->id)->value('class');

        $teacher = register_teacher::where('class', $class)
        ->where('user_id', auth()->user()->id)
        ->with(['students' => function ($query) 
        {
            $query->where('status', 'IN SCHOOL')
            ->orWhere('grad_type', 'TARTEEL ZALLA')
            ->orderBy('fullname');
        }])
        ->first();

        $studentIds = $teacher->students->pluck('id');

        $exam = ExamsModel::whereIn('student_id', $studentIds)
        ->where('subject_id', $id)
        ->get();


        foreach ($teacher->students as $student) {
            $studentExams[$student->id] = $student->exams
            ->where('subject_id', $id)
            ->all();
        }

        $selectedSubject = ExamsModel::where('subject_id', $id)
        ->first();

        $subjectId = ExamsModel::where('subject_id', $id)->pluck('subject_id');

        $examScores = ExamsModel::whereIn('student_id', $studentIds)
        ->where('subject_id', $id)
        ->get();

        $score = [];
    foreach ($examScores as $scoreRecord) {
        $score[$scoreRecord->student_id][$scoreRecord->subject_id] = $scoreRecord->score;
    }

    return view('Exams.ExamsEdit',
     ['exam' => $exam, 
     'sessions' => $sessions, 
     'class' => $class, 
     'teacher' => $teacher, 
     'score' => $score,
     'examScores' => $examScores,
     'subjectId' => $subjectId,
     'selectedSubject' => $selectedSubject,
     'studentExams' => $studentExams,

]);
}

// Show Comment Edit Form
public function examComment(){

    $class = register_teacher::where('user_id', Auth::user()->id)->value('class');

    $teacher = register_teacher::where('class', $class)
    ->where('user_id', auth()->user()->id)
    ->with(['students' => function ($query) 
    {
        $query->where('status', 'IN SCHOOL')
        ->orWhere('grad_type', 'TARTEEL ZALLA')
        ->orderBy('fullname');
    }])
    ->first();

    $studentIds = $teacher->students->pluck('id');

    $exam = ExamsModel::whereIn('student_id', $teacher->students->pluck('id'))
    ->get();

    // ===========================================================================
    // TOTAL SCORES AND AVERAGE CODES IN COMMENT CONTROLLER
    // ===========================================================================
   
    $totalScores = [];
    $averageTotal = [];

foreach ($teacher->students as $student) {
    $totalScores[$student->id] = 0;
    $averageTotal[$student->id] = 0; 
    
    foreach ($student->exams as $subject) {

        $first_ca = is_numeric($subject['1st_ca']) ? $subject['1st_ca'] : 0;
        $second_ca = is_numeric($subject['2nd_ca']) ? $subject['2nd_ca'] : 0;
        $third_ca = is_numeric($subject['3rd_ca']) ? $subject['3rd_ca'] : 0;
        $exams = is_numeric($subject['exams']) ? $subject['exams'] : 0;

        $totalScores[$student->id] +=  $first_ca + $second_ca + $third_ca + $exams;
    }

    $averageTotal[$student->id] = count($student->exams) > 0 ? $totalScores[$student->id] / count($student->exams) : 0;

    $student->averageTotal = $averageTotal;
}

    // ===========================================================================
    // END OF TOTAL SCORES AND AVERAGE CODES IN COMMENT CONTROLLER
    // ===========================================================================

// =====================================================
// POSITION CODES
// =====================================================

// Function to add ordinal suffix
function OrdinalSuffix($position) {
    if ($position % 100 >= 11 && $position % 100 <= 13) {
        return $position . 'th';
    } else {
        switch ($position % 10) {
            case 1:
                return $position . 'st';
            case 2:
                return $position . 'nd';
            case 3:
                return $position . 'rd';
            default:
                return $position . 'th';
        }
    }
}

// $orderedStudents = $teacher->students->sortByDesc('averageTotal');
$orderedStudents = $teacher->students->map(function ($student) {
    $totalScores = 0;
    $examCount = count($student->exams);

    foreach ($student->exams as $subject) {
        $first_ca = is_numeric($subject['1st_ca']) ? $subject['1st_ca'] : 0;
        $second_ca = is_numeric($subject['2nd_ca']) ? $subject['2nd_ca'] : 0;
        $third_ca = is_numeric($subject['3rd_ca']) ? $subject['3rd_ca'] : 0;
        $exams = is_numeric($subject['exams']) ? $subject['exams'] : 0;

        $totalScores +=  $first_ca + $second_ca + $third_ca + $exams;
    }

    $averageTotal = $examCount > 0 ? $totalScores / $examCount : 0;
    $student->averageTotal = $averageTotal;

    return $student;
})->sortByDesc('averageTotal');
$position = 1;
$previousAverage = null;

foreach ($orderedStudents as $student) {
    if ($previousAverage !== null && $student->averageTotal < $previousAverage) {
        $position++;
    }

    $student->position = OrdinalSuffix($position);
    $previousAverage = $student->averageTotal;
}

 // =====================================================
// END OF POSITION CODES
// =====================================================
   
    return view('Exams.commentEditView',
[
    'exam' => $exam,
    'teacher' => $teacher,
    'totalScores' => $totalScores,
    'averageTotal' => $averageTotal,
    'orderedStudents' => $orderedStudents,
]
);
}

// Update Comment
public function updateComment(Request $request, $id) {
    
    $class = register_teacher::where('user_id', Auth::user()->id)->value('class');

    $studentIds = $request->input('student_ids');
    $term = $request->input('term');
    $session = $request->input('session');
    $comments = $request->input('comment');
    $subject_ids = $request->input('subject_ids');

    $session = sessions::pluck('session')->first();
    $term = sessions::pluck('term')->first();

    foreach($studentIds as $studentId) {
        foreach($subject_ids as $subject_id) {

            $existingRecord = ExamsModel::where([
                ['student_id', '=', $studentId],
                ['subject_id', '=', $subject_id],
                ['term', '=', $term],
                ['session', '=', $session],
            ])->first();

            // dd($existingRecord);
            if ($existingRecord) {

                $comment = $comments[$studentId][$subject_id] ?? null;

                $existingRecord->comment = $comment;
                $existingRecord->update();
            }
        }
        }

    return redirect('/exams/show')->with('message', 'Comment Updated Successfully!');

}

//Select Subjects
public function selectSubjects(){

    $class = register_teacher::where('user_id', Auth::user()->id)->value('class');

    $teacher = register_teacher::where('class', $class)->where('user_id', auth()->user()->id)
    ->with(['students' => function ($query) 
    {
        $query->where('status', 'IN SCHOOL')
        ->orWhere('grad_type', 'TARTEEL ZALLA')
        ->orderBy('fullname');
    }])
    ->first();

    $subjects = subjectsModel::get();

    return view('Exams.selectSubjects', ['subjects' => $subjects, 'teacher' => $teacher
]);
}

//Store Subjects
public function subjectsCreate(Request $request){

    $class = register_teacher::where('user_id', Auth::user()->id)->value('class');

    $teacher = register_teacher::where('class', $class)->where('user_id', auth()->user()->id)
        ->with(['students' => function ($query) 
        {
            $query->where('status', 'IN SCHOOL')
            ->orWhere('grad_type', 'TARTEEL ZALLA')
            ->orderBy('fullname');
        }])
        ->first();

    $selectedSubjectIds = $request->input('subjects');

    $studentIds = $request->input('student_ids');

    $session = sessions::pluck('session')->first();
    $term = sessions::pluck('term')->first();

    foreach ($teacher->students as $student) {
        // Loop through selected subjects for association
        foreach ($selectedSubjectIds as $subjectId) {
            // Create a new exam record
            $record = new ExamsModel();
            $record->subject_id = $subjectId;
            $record->student_id = $student->id;
            $record->session = $session;
            $record->term = $term;

            // Associate the exam record with the student
            $record->save();
        }
    }

    return redirect('/exams/show');
}

//Update Exams Record
public function update(Request $request, $id){

    $class = register_teacher::where('user_id', Auth::user()->id)->value('class');

    $selectedSubject = $request->input('subjects');
    $studentIds = $request->input('student_ids');
    $scores = $request->input('scores');
    $term = $request->input('term');
    $session = $request->input('session');

    $session = sessions::pluck('session')->first();
    $term = sessions::pluck('term')->first();

    foreach($studentIds as $studentId) {
    foreach ($selectedSubject as $subject) {

        $firstCA = $scores[$studentId][$subject]['1st_ca'];
        $secondCA = $scores[$studentId][$subject]['2nd_ca'];
        $thirdCA = $scores[$studentId][$subject]['3rd_ca'];
        $exams = $scores[$studentId][$subject]['exams'];
            $existingRecord = ExamsModel::where([
                ['subject_id', '=', $subject],
                ['student_id', '=', $studentId],
                ['term', '=', $term],
                ['session', '=', $session],
            ])->first();

            // dd($existingRecord);

            if ($existingRecord) {

            $existingRecord['1st_ca'] = $firstCA;
            $existingRecord['2nd_ca'] = $secondCA;
            $existingRecord['3rd_ca'] = $thirdCA;
            $existingRecord['exams'] = $exams;
            $existingRecord->update();
            } else{
                return redirect('/exams/show')->with('message', 'Record Not found!');
            }
        }
    }

    return redirect('/exams/show')->with('message', 'Score Updated Successfully!');
}

// Delete Subject
public function delete($id) {

    $teacherClass = Auth::user()->teachers->class;
    $studentIds = register_student::where('class', $teacherClass)->pluck('id')->toArray();
    
    $exam = ExamsModel::where('subject_id', $id)
        ->whereIn('student_id', $studentIds)
        ->delete();

    // $student = ExamsModel::where('id', $id )->delete();
    return redirect('/exams/show')->with('message', 'Subject Deleted Successfully!');
}


//Show Report Sheet
public function reportSheet($id){

    $exam = ExamsModel::where('student_id', $id)->get();

    $sessions = sessions::first();

    $subjects = subjectsModel::whereIn('subject', $exam->pluck('subject_id'))->get();

    $student = register_student::where('id', $id)->first();

    $class = register_student::where('class', $student->class)
    ->where('status', 'IN SCHOOL')
    ->count();

    $totalCa = [];

    $totalCa[$id] = [];
    foreach ($student->exams as $subject) {
        $subjectId = $subject->subject_id;
        $studentId = $subject->student_id;
    $totalCa[$subjectId] = $subject['1st_ca'] + $subject['2nd_ca'] + $subject['3rd_ca'];
    $totalScores[$subjectId] = $subject['1st_ca'] + $subject['2nd_ca'] + $subject['3rd_ca'] + $subject['exams'];
    $totalExam[$subjectId] =  $subject['exams'];
    $grandTotal[$studentId] = $subject['1st_ca'] + $subject['2nd_ca'] + $subject['3rd_ca'] + $subject['exams'];
    $averageTotal[$studentId] = count($student->exams) > 0 ? $grandTotal[$studentId] / count($student->exams) : 0;
    }

    $attendanceRecords[$student->id] = $student->attendance->filter(function ($record) {
        return in_array($record->status, ['Present', 'present', 'Late', 'late', 'excused', 'Excused']);
    });

    $totalAttendanceRecords = $student->attendance->count();
    $presentAttendanceRecords = $attendanceRecords[$student->id]->count();
    $percentage = $totalAttendanceRecords > 0 ? ($presentAttendanceRecords / $totalAttendanceRecords) * 100 : 0;
    $student->attendancePercentage = $percentage;

    // =====================================================
// POSITION CODES
// =====================================================

// Function to add ordinal suffix
function resultOrdinalSuffix($position) {
    if ($position % 100 >= 11 && $position % 100 <= 13) {
        return $position . 'th';
    } else {
        switch ($position % 10) {
            case 1:
                return $position . 'st';
            case 2:
                return $position . 'nd';
            case 3:
                return $position . 'rd';
            default:
                return $position . 'th';
        }
    }
}

// $orderedStudents = $teacher->students->sortByDesc('averageTotal');
$position = 1;
$previousAverage = null;

    // if ($previousAverage !== null && $student->averageTotal < $previousAverage) {
    //     $position++;

    $student->position = resultOrdinalSuffix($position);
    $previousAverage = $student->averageTotal;
// }

 // =====================================================
// END OF POSITION CODES
// =====================================================

    return view('Exams.reportSheet', ['sessions' => $sessions, 'student' => $student,
                'class' => $class,
                'exam' => $exam,
                'subjects' => $subjects,
                'totalCa' => $totalCa,
                'totalScores' => $totalScores,
                'totalExam' => $totalExam,
                'grandTotal' => $grandTotal,
                'averageTotal' => $averageTotal,
]);
}


}

