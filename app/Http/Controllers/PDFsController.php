<?php

namespace App\Http\Controllers;

use App\Models\sessions;
// use Barryvdh\DomPDF\PDF;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use App\Models\ExamsModel;
use App\Models\subjectsModel;
use App\Models\register_student;
use Illuminate\Http\Request;
use App\Models\register_teacher;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PDFsController extends Controller
{
    //Download Exams Clean Sheet
public function cleanSheets(){

    $sessions = sessions::orderBy('created_at', 'desc')->first();

    $exam = ExamsModel::get();

    $class = register_teacher::where('user_id', Auth::user()->id)->value('class');

        $teacher = register_teacher::where('user_id', auth()->user()->id)
        ->with(['students' => function ($query) 
        {
            $query->where('status', 'IN SCHOOL')
            ->orWhere('grad_type', 'TARTEEL ZALLA');
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
    
    foreach ($student->exams as $subjects) {

        $matchingSubjects = $subjects->where('session', $sessions->session)
                                  ->where('term', $sessions->term)
                                  ->where('student_id', $student->id)
                                  ->get();

        foreach ($matchingSubjects as $subject) {
        // Calculate the total CA score for this subject and student
        $first_cas = is_numeric($subject->first_ca) ? $subject->first_ca : null;
        $second_cas = is_numeric($subject->second_ca) ? $subject->second_ca : null;
        $third_cas = is_numeric($subject->third_ca) ? $subject->third_ca : null;

        // $totalCa[$student->id][$subject->subject_id] = $first_cas + $second_cas + $third_cas;
        $totalCa[$student->id][$subject->subject_id] = (!is_null($first_cas) || !is_null($second_cas) || !is_null($third_cas))
    ? ($first_cas + $second_cas + $third_cas)
    : '-';

        }
    }
}

// Initialize an array to store the total scores for each student
$totalScores = [];
$averageTotal = [];

foreach ($teacher->students as $student) {
    $totalScores[$student->id] = 0;
    $averageTotal[$student->id] = 0; 
    
    foreach ($student->exams as $subject) {

        $matchingSubjects = [];

        if ($subject->session == $sessions->session && $subject->term == $sessions->term && $subject->student_id == $student->id) {
            $matchingSubjects[] = $subject;

        // Calculate the total score for this subject and student
        $first_cas = is_numeric($subject->first_ca) ? $subject->first_ca : 0;
        $second_cas = is_numeric($subject->second_ca) ? $subject->second_ca : 0;
        $third_cas = is_numeric($subject->third_ca) ? $subject->third_ca : 0;
        $examss = is_numeric($subject->exams) ? $subject->exams : 0;

        $totalScores[$student->id] +=  $first_cas + $second_cas + $third_cas + $examss;
}
    }

    $averageTotal[$student->id] = count($student->exams->where('session', $sessions->session)->where('term', $sessions->term)) > 0 
    ? $totalScores[$student->id] / count($student->exams->where('session', $sessions->session)->where('term', $sessions->term)) : 0;

    $student->averageTotal = $averageTotal;
}

$cummulativetotalScores = [];
$cummulativeaverageTotal = [];

foreach ($teacher->students as $student) {
    $cummulativetotalScores[$student->id] = 0;
    $cummulativeaverageTotal[$student->id] = 0; 
    
    foreach ($student->exams as $subject) {

        $cummulativematchingSubjects = [];

        if ($subject->session == $sessions->session && $subject->student_id == $student->id) {
            $cummulativematchingSubjects[] = $subject;

        // Calculate the total score for this subject and student
        $first_cas = is_numeric($subject->first_ca) ? $subject->first_ca : 0;
        $second_cas = is_numeric($subject->second_ca) ? $subject->second_ca : 0;
        $third_cas = is_numeric($subject->third_ca) ? $subject->third_ca : 0;
        $examss = is_numeric($subject->exams) ? $subject->exams : 0;

        $cummulativetotalScores[$student->id] +=  $first_cas + $second_cas + $third_cas + $examss;
}
    }

    $cummulativeaverageTotal[$student->id] = count($student->exams->where('session', $sessions->session)) > 0 
    ? $cummulativetotalScores[$student->id] / count($student->exams->where('session', $sessions->session)) : 0;

    $student->cummulativeaverageTotal = $cummulativeaverageTotal;
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

$matchingSubjects = [];

$orderedStudents = $teacher->students->map(function ($student) use ($sessions,  &$matchingSubjects) {

    $matchingSubjects = $student->exams->where('session', $sessions->session)
    // ->where('term', $sessions->term)
    ->where('student_id', $student->id);

    $totalScores = 0;
    $examCount = count($matchingSubjects);

    $sessions = sessions::orderBy('created_at', 'desc')->first();

foreach ($matchingSubjects as $subject) {

    $first_cas = is_numeric($subject->first_ca) ? $subject->first_ca : 0;
    $second_cas = is_numeric($subject->second_ca) ? $subject->second_ca : 0;
    $third_cas = is_numeric($subject->third_ca) ? $subject->third_ca : 0;
    $examss = is_numeric($subject->exams) ? $subject->exams : 0;

        $totalScores +=  $first_cas + $second_cas + $third_cas + $examss;
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
$session = sessions::orderBy('created_at', 'desc')->first('session');
$term = sessions::orderBy('created_at', 'desc')->first('term');

$pdf = PDF::loadView('PDFs.cleanSheet', ['exam' => $exam,
                                    'sessions' => $sessions,
                                    'class' => $class,
                                    'teacher' => $teacher,
                                    'totalCa' => $totalCa,
                                    'totalScores' => $totalScores,
                                    'averageTotal' => $averageTotal,
                                    'percentage' => $percentage,
                                    'orderedStudents' => $orderedStudents,
                                    'matchingSubjects' => $matchingSubjects,
                                    'session' => $session,
                                    'term' => $term,
                                    'cummulativeaverageTotal' => $cummulativeaverageTotal,
                                    'cummulativematchingSubjects' => $cummulativematchingSubjects,
]);

$pdf->setPaper('A4', 'landscape');

 // Return a response with PDF content to download
 return $pdf->download('Clean Sheets ' . $term . ' ' . $session . ' Academic Session.pdf');
}

//Download Report Sheet For Single Student
public function reportSheet($id){

    $session = sessions::pluck('session')->last();
    $term = sessions::pluck('term')->last();

    $exam = ExamsModel::where('student_id', $id)->where('term', $term)->where('session', $session)->get();

    $sessions = sessions::orderBy('created_at', 'desc')->first();

    $subjects = subjectsModel::whereIn('subject', $exam->pluck('subject_id'))->get();

    $class = register_teacher::where('user_id', Auth::user()->id)->value('class');

    $teacher = register_teacher::where('class', $class)->where('user_id', auth()->user()->id)
    ->with(['students' => function ($query) 
    {
        $query->where('status', 'IN SCHOOL')
        ->orWhere('grad_type', 'TARTEEL ZALLA');
    }, 'students.attendance'])
    ->first();

    $dalibi = register_student::where('id', $id)->first();

    $class = register_student::where('class', $dalibi->class)
    ->where('status', 'IN SCHOOL')
    ->orwhere('status', 'TARTEEL ZALLA')
    ->count();

    $totalCa = [];

    $totalCa[$id] = [];

    $first_cas = [];
    $first_cas[$id] = [];

    $second_cas = [];
    $second_cas[$id] = [];

    foreach ($dalibi->exams as $subject) {
        if ($subject->term == $term && $subject->session == $session) {
            $subjectId = $subject->subject_id;
            $studentId = $subject->student_id;

            $first_cas[$subjectId] = !is_null($subject->first_ca) ? $subject->first_ca : "-";
            $second_cas[$subjectId] = !is_null($subject->second_ca) ? $subject->second_ca : "-";
            
            $totalCa[$subjectId] = $subject->first_ca + $subject->second_ca + $subject->third_ca;
            $totalScores[$subjectId] = $subject->first_ca + $subject->second_ca + $subject->third_ca + $subject->exams;
            $totalExam[$subjectId] = !is_null($subject->exams) ? $subject->exams : "-";

            if (!isset($totalScores[$subjectId])) {
                $totalScores[$subjectId] = 0; 
            }

            if ($totalScores[$subjectId] >= 70) {
                $grade[$subjectId] = 'A';
                $remark[$subjectId] = 'Excellent';
            } elseif ($totalScores[$subjectId] >= 60 && $totalScores[$subjectId] < 70) {
                $grade[$subjectId] = 'B';
                $remark[$subjectId] = 'V. Good';
            } elseif ($totalScores[$subjectId] >= 50 && $totalScores[$subjectId] < 60) { 
                $grade[$subjectId] = 'C';
                $remark[$subjectId] = 'Good';
            } elseif ($totalScores[$subjectId] >= 40 && $totalScores[$subjectId] < 50) {
                $grade[$subjectId] = 'D';
                $remark[$subjectId] = 'Pass';
            } elseif ($totalScores[$subjectId] >= 0 && $totalScores[$subjectId] < 40) {
                $grade[$subjectId] = 'F';
                $remark[$subjectId] = 'Fail';
            } else {
                $grade[$subjectId] = '-';
                $remark[$subjectId] = 'Invalid Score'; 
            }
            
            // Assign the values to the subject
            $subject->grade = $grade[$subjectId];
            $subject->remark = $remark[$subjectId];
            
            // Calculate grand total and average total outside the loop
            if (!isset($grandTotal[$studentId])) {
                $grandTotal[$studentId] = 0;
            }
            $grandTotal[$studentId] += $totalScores[$subjectId];
            $averageTotal[$studentId] = count($dalibi->exams) > 0 ? $grandTotal[$studentId] / count($dalibi->exams->where('term', $term)->where('session', $session)) : 0;

            if ($averageTotal[$studentId] >= 70) {
                $TeachersRemark[$studentId] = 'Outstanding performance! Keep aiming higher.';
                $PrincipalsRemark[$studentId] = 'Excellent achievement! Continue to strive for greatness.';
            } elseif ($averageTotal[$studentId] >= 60 && $averageTotal[$studentId] < 70) {
                $TeachersRemark[$studentId] = 'Great job! You are doing very well.';
                $PrincipalsRemark[$studentId] = 'Impressive work! Maintain this momentum and aim for excellence.';
            } elseif ($averageTotal[$studentId] >= 50 && $averageTotal[$studentId] < 60) {
                $TeachersRemark[$studentId] = 'Good effort! You have the potential to achieve even more.';
                $PrincipalsRemark[$studentId] = 'Solid performance! With a little more effort, you can excel further.';
            } elseif ($averageTotal[$studentId] >= 40 && $averageTotal[$studentId] < 50) {
                $TeachersRemark[$studentId] = 'A fair result. You can achieve better with consistent effort.';
                $PrincipalsRemark[$studentId] = 'You’re on the right track. Focus and push yourself to improve.';
            } elseif ($averageTotal[$studentId] >= 0 && $averageTotal[$studentId] < 40) {
                $TeachersRemark[$studentId] = 'Keep trying. You have the ability to improve with hard work.';
                $PrincipalsRemark[$studentId] = 'Do not give up! Hard work and determination will lead to better results.';
            } else {
                $TeachersRemark[$studentId] = 'No remarks available.';
                $PrincipalsRemark[$studentId] = 'No remarks available.'; // Handles any unexpected scores
            }
            
        }
    }

    $cummulativeTotalCa = [];

    $cummulativeTotalCa[$id] = [];

    foreach ($dalibi->exams()->where('session', $session)->cursor() as $subject) {
        if ($subject->session == $session) {
            $subjectId = $subject->subject_id;
            $studentId = $subject->student_id;

            $firstCa = is_numeric($subject->first_ca) ? $subject->first_ca : null;
            $secondCa = is_numeric($subject->second_ca) ? $subject->second_ca : null;
            $thirdCa = is_numeric($subject->third_ca) ? $subject->third_ca : null;
            $examScore = is_numeric($subject->exams) ? $subject->exams : null;

            // $firstCa = $subject->first_ca;
            // $secondCa = $subject->second_ca;
            // $thirdCa = $subject->third_ca;
            // $examScore = $subject->exams;
            
            $cummulativeTotalCa[$subjectId] = $firstCa + $secondCa + $thirdCa;
            $cummulativeTotalScores[$subjectId] = $firstCa + $secondCa + $thirdCa + $examScore;
            $cummulativeTotalExam[$subjectId] = $examScore;
            
            // Calculate grand total and average total outside the loop
            if (!isset($cummulativeGrandTotal[$studentId])) {
                $cummulativeGrandTotal[$studentId] = 0;
            }
            $cummulativeGrandTotal[$studentId] += $cummulativeTotalScores[$subjectId];
            $cummulativeAverageTotal[$studentId] = count($dalibi->exams) > 0 ? $cummulativeGrandTotal[$studentId] / count($dalibi->exams->where('session', $session)) : 0;
        }
    }
    
    $attendanceRecords[$dalibi->id] = $dalibi->attendance->where('term', $term)->where('session', $session)->filter(function ($record) {
        return in_array($record->status, ['Present', 'present', 'Late', 'late', 'excused', 'Excused']);
    });

    $totalAttendanceRecords = $dalibi->attendance->where('term', $term)->where('session', $session)->count();
    $presentAttendanceRecords = $attendanceRecords[$dalibi->id]->count();
    $percentage = $totalAttendanceRecords > 0 ? ($presentAttendanceRecords / $totalAttendanceRecords) * 100 : 0;
    $dalibi->attendancePercentage = $percentage;

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

$matchingSubjects = [];

$orderedStudents = $teacher->students->map(function ($student) use ($session, $term,  &$matchingSubjects) {

    $matchingSubjects = $student->exams->where('session', str_replace('_', '/', $session))
    ->where('student_id', $student->id);

    $totalScores = 0;
    $examCount = count($matchingSubjects);

foreach ($matchingSubjects as $subject) {

    $first_cas = is_numeric($subject->first_ca) ? $subject->first_ca : 0;
    $second_cas = is_numeric($subject->second_ca) ? $subject->second_ca : 0;
    $third_cas = is_numeric($subject->third_ca) ? $subject->third_ca : 0;
    $examss = is_numeric($subject->exams) ? $subject->exams : 0;

        $totalScores +=  $first_cas + $second_cas + $third_cas + $examss;
}

    $averageTotal = $examCount > 0 ? $totalScores / $examCount : 0;
    $student->averageTotal = $averageTotal;

    return $student;
})->sortByDesc('averageTotal');

$position = 1;
$previousAverage = null;

foreach ($orderedStudents as $student) {
    if ($averageTotal !== null && $student->averageTotal < $previousAverage) {
        $position++;
    }
    $student->position = resultOrdinalSuffix($position);
    $studentPositions[$student->id] = resultOrdinalSuffix($position);

    $previousAverage = $student->averageTotal;

}

$studentPosition = null;

foreach ($orderedStudents as $student) {
foreach ($student->exams as $jarabawa) {
    if ($jarabawa->student_id === $id) {
        $studentPosition = $student->position;
        break;
    }
}
}

 // =====================================================
// END OF POSITION CODES
// =====================================================

 // Retrieve the student data
 $student = register_student::findOrFail($id);

 // Generate PDF using Dompdf
 $pdf = PDF::loadView('PDFs.reportSheet', [
    'sessions' => $sessions, 
    'student' => $student,
    'class' => $class,
    'exam' => $exam,
    'subjects' => $subjects,
    'second_cas' => $second_cas,
    'first_cas' => $first_cas,
    'totalCa' => $totalCa,
    'totalScores' => $totalScores,
    'totalExam' => $totalExam,
    'grandTotal' => $grandTotal,
    'averageTotal' => $averageTotal,
    'grade' => $grade,
    'remark' => $remark,
    'TeachersRemark' => $TeachersRemark,
    'PrincipalsRemark' => $PrincipalsRemark,
    'dalibi' => $dalibi,
    'studentPosition' => $studentPosition,
    'cummulativeAverageTotal' => $cummulativeAverageTotal,
])
->setPaper('a4')
        ->setOption('isHtml5ParserEnabled', true)
        ->setOption('isPhpEnabled', true)
        ->setOption('isRemoteEnabled', true)
        ->setOption('chroot', public_path('/'));

 // Return a response with PDF content to download
 return $pdf->download($dalibi->fullname);

}

// DOWNLOAD ALL REPORT SHEETS OF STUDENTS
// public function downloadAllReportSheets()
//     {
//         $session = Sessions::latest('created_at')->first();
//         $term = $session->term;
//         $sessionName = $session->session;

//         $students = register_student::where('status', 'IN SCHOOL')
//             ->orWhere('grad_type', 'TARTEEL ZALLA')
//             ->where('class', '!=', '#')
//             ->get()
//             ->groupBy('class');


//         $mergedPdf = new \setasign\Fpdi\Fpdi();
//         $mergedPdfFileName = storage_path('app/' . $term . ' ' . str_replace('/', '_', $sessionName) . ' ' . 'Academic Session' . '.pdf');

//         foreach ($students as $aji => $classStudents) {
//         foreach ($classStudents as $student) {
//             $exam = ExamsModel::where('student_id', $student->id)
//                 ->where('term', $term)
//                 ->where('session', $sessionName)
//                 ->get();

//             $subjects = SubjectsModel::whereIn('subject', $exam->pluck('subject_id'))->get();
//             $class = $classStudents->count();

//             $totalCa = [];
//             $totalScores = [];
//             $totalExam = [];
//             $grandTotal = [];

//             foreach ($exam as $subject) {
//                 $subjectId = $subject->subject_id;
//                 $totalCa[$subjectId] = $subject->first_ca + $subject->second_ca + $subject->third_ca;
//                 $totalScores[$subjectId] = $totalCa[$subjectId] + $subject->exams;
//                 $totalExam[$subjectId] = $subject->exams;

//                 // Calculate grand total
//                 if (!isset($grandTotal[$student->id])) {
//                     $grandTotal[$student->id] = 0;
//                 }
//                 $grandTotal[$student->id] += $totalScores[$subjectId];
//             }

//             $averageTotal[$student->id] = count($exam) > 0 ? $grandTotal[$student->id] / count($exam) : 0;

//             $attendanceRecords[$student->id] = $student->attendance->where('term', $term)->where('session', $sessionName)->filter(function ($record) {
//                 return in_array($record->status, ['Present', 'present', 'Late', 'late', 'excused', 'Excused']);
//             });

//             // CALCULATE CUMMULATIVE SCORES
//             $cummulativeExam = ExamsModel::where('student_id', $student->id)
//             ->where('session', $sessionName)
//             ->get();

//             $cummulativeTotalCa = [];
//             $cummulativeTotalScores = [];
//             $cummulativeTotalExam = [];
//             $cummulativeGrandTotal = [];

//             foreach ($cummulativeExam as $subject) {
//                 $subjectId = $subject->subject_id;
//                 $cummulativeTotalCa[$subjectId] = $subject->first_ca + $subject->second_ca + $subject->third_ca;
//                 $cummulativeTotalScores[$subjectId] = $cummulativeTotalCa[$subjectId] + $subject->exams;
//                 $cummulativeTotalExam[$subjectId] = $subject->exams;

//                 // Calculate grand total
//                 if (!isset($cummulativeGrandTotal[$student->id])) {
//                     $cummulativeGrandTotal[$student->id] = 0;
//                 }
//                 $cummulativeGrandTotal[$student->id] += $cummulativeTotalScores[$subjectId];
//             }

//             $cummulativeAverageTotal[$student->id] = count($cummulativeExam) > 0 ? $cummulativeGrandTotal[$student->id] / count($cummulativeExam) : 0;

//             $attendanceRecords[$student->id] = $student->attendance->where('term', $term)->where('session', $sessionName)->filter(function ($record) {
//                 return in_array($record->status, ['Present', 'present', 'Late', 'late', 'excused', 'Excused']);
//             });
        
//             $totalAttendanceRecords = $student->attendance->where('term', $term)->where('session', $sessionName)->count();
//             $presentAttendanceRecords = $attendanceRecords[$student->id]->count();
//             $percentage = $totalAttendanceRecords > 0 ? ($presentAttendanceRecords / $totalAttendanceRecords) * 100 : 0;
//             $student->attendancePercentage = $percentage;

//             $data = [
//                 'sessions' => $session,
//                 'class' => $class,
//                 'exam' => $exam,
//                 'subjects' => $subjects,
//                 'totalCa' => $totalCa,
//                 'totalScores' => $totalScores,
//                 'totalExam' => $totalExam,
//                 'grandTotal' => $grandTotal,
//                 'averageTotal' => $averageTotal,
//                 'cummulativeAverageTotal' => $cummulativeAverageTotal,
//                 'dalibi' => $student,
//                 'studentPosition' => $this->getStudentPosition($student->id, $sessionName, $term, $averageTotal)
//             ];

//             // Generate PDF content
//             $pdfContent = PDF::loadView('PDFs.reportsheet', $data)->output();

//             // Add PDF content to merged PDF
//             $tempPdfFile = tempnam(sys_get_temp_dir(), 'report');
//             file_put_contents($tempPdfFile, $pdfContent);

//             $pageCount = $mergedPdf->setSourceFile($tempPdfFile);
//             for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
//                 $templateId = $mergedPdf->importPage($pageNo);
//                 $size = $mergedPdf->getTemplateSize($templateId);

//                 $mergedPdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
//                 $mergedPdf->useTemplate($templateId);
//             }

//             unlink($tempPdfFile);
//         }
//     }

//             // Output merged PDF file
//             $mergedPdf->Output('F', $mergedPdfFileName);

//             // Download the merged PDF file
//             return response()->download($mergedPdfFileName);
//     }

//     private function getStudentPosition($studentId, $session, $term, $averageTotal)
//     {

//         $studentClass = register_student::find($studentId)->class;
//         $teacher = register_teacher::where('class', $studentClass)
//             ->where('user_id', auth()->user()->id)
//             ->with(['students' => function ($query) {
//                 $query->where('status', 'IN SCHOOL')
//                     ->orWhere('grad_type', 'TARTEEL ZALLA');
//             }, 'students.exams'])
//             ->first();

//             if ($teacher === null) {
//                 return('1st');
//             }

//         $orderedStudents = $teacher->students->map(function ($student) use ($session, $term, $averageTotal) {
//             $matchingSubjects = $student->exams->where('session', str_replace('_', '/', $session))
//                 // ->where('term', $term)
//                 ->where('student_id', $student->id);

//             $totalScores = 0;
//             $examCount = count($matchingSubjects);

//             foreach ($matchingSubjects as $subject) {
//                 $first_cas = is_numeric($subject->first_ca) ? $subject->first_ca : 0;
//                 $second_cas = is_numeric($subject->second_ca) ? $subject->second_ca : 0;
//                 $third_cas = is_numeric($subject->third_ca) ? $subject->third_ca : 0;
//                 $examss = is_numeric($subject->exams) ? $subject->exams : 0;

//                 $totalScores +=  $first_cas + $second_cas + $third_cas + $examss;
//             }

//             $averageTotal = $examCount > 0 ? $totalScores / $examCount : 0;
//             $student->averageTotal = $averageTotal;

//             return $student;
//         })->sortByDesc('averageTotal');

//         $position = 1;
//         $previousAverage = null;

//         foreach ($orderedStudents as $student) {
//             if ($averageTotal !== null && $student->averageTotal < $previousAverage) {
//                 $position++;
//             }
//             $student->position = $this->allresultOrdinalSuffix($position);
//             if ($student->id == $studentId) {
//                 return $student->position;
//             }
//             $previousAverage = $student->averageTotal;
//         }

//         return null;
//     }

//     // Function to add ordinal suffix
//     private function allresultOrdinalSuffix($position)
//     {
//         if ($position % 100 >= 11 && $position % 100 <= 13) {
//             return $position . 'th';
//         } else {
//             switch ($position % 10) {
//                 case 1:
//                     return $position . 'st';
//                 case 2:
//                     return $position . 'nd';
//                 case 3:
//                     return $position . 'rd';
//                 default:
//                     return $position . 'th';
//             }
//         }
//     }

    // DOWNLOAD ALL REPORT SHEETS FOR A CLASS
    public function reportSheets()
{
    $session = Sessions::latest('created_at')->first();
    $term = $session->term;
    $sessionName = $session->session;

    $teacherClass = register_teacher::where('user_id', Auth::user()->id)->pluck('class')->first();

    $teacher = register_teacher::where('user_id', auth()->user()->id)
            ->with(['students' => function ($query) {
                $query->where('status', 'IN SCHOOL')
                    ->orWhere('grad_type', 'TARTEEL ZALLA');
            }, 'students.exams'])
            ->first();

    $students = $teacher->students;

    $tempDir = storage_path('app/temp_pdfs');
    if (!file_exists($tempDir)) {
        mkdir($tempDir, 0755, true);
    }

    $mergedPdfFileName = storage_path('app/' . $term . ' ' . str_replace('/', '_', $sessionName) . ' ' . 'Academic Session' . '.pdf');
    $mergedPdf = new \setasign\Fpdi\Fpdi();

    foreach ($students as $student) {
        $exam = ExamsModel::where('student_id', $student->id)
            ->where('term', $term)
            ->where('session', $sessionName)
            ->get();

        $subjects = SubjectsModel::whereIn('subject', $exam->pluck('subject_id'))->get();
        $classCount = $students->count();

        $first_cas = [];
        $second_cas = [];
        $totalCa = [];
        $totalScores = [];
        $totalExam = [];
        $grandTotal = [];

        foreach ($exam as $subject) {
            $subjectId = $subject->subject_id;
            $first_cas[$subjectId] = !is_null($subject->first_ca) ? $subject->first_ca : "-";
            $second_cas[$subjectId] = !is_null($subject->second_ca) ? $subject->second_ca : "-";
            $totalCa[$subjectId] = $subject->first_ca + $subject->second_ca + $subject->third_ca;
            $totalScores[$subjectId] = $totalCa[$subjectId] + $subject->exams;
            $totalExam[$subjectId] = !is_null($subject->exams) ? $subject->exams : "-";

            if (!isset($totalScores[$subjectId])) {
                $totalScores[$subjectId] = 0; // Default to 0 if not set
            }

            if ($totalScores[$subjectId] >= 70) {
                $grade[$subjectId] = 'A';
                $remark[$subjectId] = 'Excellent';
            } elseif ($totalScores[$subjectId] >= 60 && $totalScores[$subjectId] < 70) {
                $grade[$subjectId] = 'B';
                $remark[$subjectId] = 'V. Good';
            } elseif ($totalScores[$subjectId] >= 50 && $totalScores[$subjectId] < 60) { 
                $grade[$subjectId] = 'C';
                $remark[$subjectId] = 'Good';
            } elseif ($totalScores[$subjectId] >= 40 && $totalScores[$subjectId] < 50) {
                $grade[$subjectId] = 'D';
                $remark[$subjectId] = 'Pass';
            } elseif ($totalScores[$subjectId] >= 0 && $totalScores[$subjectId] < 40) {
                $grade[$subjectId] = 'F';
                $remark[$subjectId] = 'Fail';
            } else {
                $grade[$subjectId] = '-';
                $remark[$subjectId] = 'Invalid Score'; // Handles any unexpected scores
            }
            
            // Assign the values to the subject
            $subject->grade = $grade[$subjectId];
            $subject->remark = $remark[$subjectId];

            if (!isset($grandTotal[$student->id])) {
                $grandTotal[$student->id] = 0;
            }
            $grandTotal[$student->id] += $totalScores[$subjectId];
        }

        $averageTotal[$student->id] = count($exam) > 0 ? $grandTotal[$student->id] / count($exam) : 0;

        if ($averageTotal[$student->id] >= 70) {
            $TeachersRemark[$student->id] = 'Outstanding performance! Keep aiming higher.';
            $PrincipalsRemark[$student->id] = 'Excellent achievement! Continue to strive for greatness.';
        } elseif ($averageTotal[$student->id] >= 60 && $averageTotal[$student->id] < 70) {
            $TeachersRemark[$student->id] = 'Great job! You are doing very well.';
            $PrincipalsRemark[$student->id] = 'Impressive work! Maintain this momentum and aim for excellence.';
        } elseif ($averageTotal[$student->id] >= 50 && $averageTotal[$student->id] < 60) {
            $TeachersRemark[$student->id] = 'Good effort! You have the potential to achieve even more.';
            $PrincipalsRemark[$student->id] = 'Solid performance! With a little more effort, you can excel further.';
        } elseif ($averageTotal[$student->id] >= 40 && $averageTotal[$student->id] < 50) {
            $TeachersRemark[$student->id] = 'A fair result. You can achieve better with consistent effort.';
            $PrincipalsRemark[$student->id] = 'You’re on the right track. Focus and push yourself to improve.';
        } elseif ($averageTotal[$student->id] >= 0 && $averageTotal[$student->id] < 40) {
            $TeachersRemark[$student->id] = 'Keep trying. You have the ability to improve with hard work.';
            $PrincipalsRemark[$student->id] = 'Do not give up! Hard work and determination will lead to better results.';
        } else {
            $TeachersRemark[$student->id] = 'No remarks available.';
            $PrincipalsRemark[$student->id] = 'No remarks available.'; // Handles any unexpected scores
        }

        $attendanceRecords[$student->id] = $student->attendance->where('term', $term)->where('session', $sessionName)->filter(function ($record) {
            return in_array($record->status, ['Present', 'present', 'Late', 'late', 'excused', 'Excused']);
        });

        // Calculate cumulative scores
        $cummulativeExam = ExamsModel::where('student_id', $student->id)
            ->where('session', $sessionName)
            ->get();

        $cummulativeTotalCa = [];
        $cummulativeTotalScores = [];
        $cummulativeTotalExam = [];
        $cummulativeGrandTotal = [];

        foreach ($cummulativeExam as $subject) {
            $subjectId = $subject->subject_id;
            $cummulativeTotalCa[$subjectId] = $subject->first_ca + $subject->second_ca + $subject->third_ca;
            $cummulativeTotalScores[$subjectId] = $cummulativeTotalCa[$subjectId] + $subject->exams;
            $cummulativeTotalExam[$subjectId] = $subject->exams;

            if (!isset($cummulativeGrandTotal[$student->id])) {
                $cummulativeGrandTotal[$student->id] = 0;
            }
            $cummulativeGrandTotal[$student->id] += $cummulativeTotalScores[$subjectId];
        }

        $cummulativeAverageTotal[$student->id] = count($cummulativeExam) > 0 ? $cummulativeGrandTotal[$student->id] / count($cummulativeExam) : 0;

        $totalAttendanceRecords = $student->attendance->where('term', $term)->where('session', $sessionName)->count();
        $presentAttendanceRecords = $attendanceRecords[$student->id]->count();
        $percentage = $totalAttendanceRecords > 0 ? ($presentAttendanceRecords / $totalAttendanceRecords) * 100 : 0;
        $student->attendancePercentage = $percentage;

        $data = [
            'sessions' => $session,
            'class' => $classCount,
            'exam' => $exam,
            'subjects' => $subjects,
            'first_cas' => $first_cas,
            'second_cas' => $second_cas,
            'totalCa' => $totalCa,
            'totalScores' => $totalScores,
            'totalExam' => $totalExam,
            'grandTotal' => $grandTotal,
            'averageTotal' => $averageTotal,
            'grade' => $grade,
            'remark' => $remark,
            'TeachersRemark' => $TeachersRemark,
            'PrincipalsRemark' => $PrincipalsRemark,
            'cummulativeAverageTotal' => $cummulativeAverageTotal,
            'dalibi' => $student,
            'studentPosition' => $this->getClassStudentPosition($student->id, $sessionName, $term, $averageTotal)
        ];

        // Generate PDF content
        $pdfContent = PDF::loadView('PDFs.reportSheet', $data)->output();

        // Add PDF content to merged PDF
        $tempPdfFile = tempnam(sys_get_temp_dir(), 'report');
        file_put_contents($tempPdfFile, $pdfContent);

        $pageCount = $mergedPdf->setSourceFile($tempPdfFile);
        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            $templateId = $mergedPdf->importPage($pageNo);
            $size = $mergedPdf->getTemplateSize($templateId);

            $mergedPdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
            $mergedPdf->useTemplate($templateId);
        }

        unlink($tempPdfFile);
    }

    // Output merged PDF file
    $mergedPdf->Output('F', $mergedPdfFileName);

    // Download the merged PDF file
    return response()->download($mergedPdfFileName);
}

private function getClassStudentPosition($studentId, $session, $term, $averageTotal)
{
    $studentClass = register_student::find($studentId)->class;
    $teacher = register_teacher::where('class', $studentClass)
        ->where('user_id', auth()->user()->id)
        ->with(['students' => function ($query) {
            $query->where('status', 'IN SCHOOL')
                ->orWhere('grad_type', 'TARTEEL ZALLA');
        }, 'students.exams'])
        ->first();

    if ($teacher === null) {
        return '1st';
    }

    $orderedStudents = $teacher->students->map(function ($student) use ($session, $term) {
        $matchingSubjects = $student->exams->where('session', str_replace('_', '/', $session))
            ->where('student_id', $student->id);

        $totalScores = 0;
        $examCount = count($matchingSubjects);

        foreach ($matchingSubjects as $subject) {
            $first_cas = is_numeric($subject->first_ca) ? $subject->first_ca : 0;
            $second_cas = is_numeric($subject->second_ca) ? $subject->second_ca : 0;
            $third_cas = is_numeric($subject->third_ca) ? $subject->third_ca : 0;
            $examss = is_numeric($subject->exams) ? $subject->exams : 0;

            $totalScores += $first_cas + $second_cas + $third_cas + $examss;
        }

        $averageTotal = $examCount > 0 ? $totalScores / $examCount : 0;
        $student->averageTotal = $averageTotal;

        return $student;
    })->sortByDesc('averageTotal');

    $position = 1;
    $previousAverage = null;

    foreach ($orderedStudents as $student) {
        if ($student->averageTotal !== null && $student->averageTotal < $previousAverage) {
            $position++;
        }
        $student->position = $this->classresultOrdinalSuffix($position);
        if ($student->id == $studentId) {
            return $student->position;
        }
        $previousAverage = $student->averageTotal;
    }

    return null;
}

private function classresultOrdinalSuffix($position)
{
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

// DOWNLOAD ALL CLEANSHEETS FOR THE TERM FOR ALL THE CLASSES
// public function downloadAllCleanSheets()
// {
//     $sessions = sessions::orderBy('created_at', 'desc')->first();
//     $exam = ExamsModel::get();
//     $teachers = register_teacher::where('status', 'IN SCHOOL')
//     ->where('rank', ['TEACHER', 'CLASS TEACHER'])
//     ->with(['students' => function ($query) {
//         $query->where('status', 'IN SCHOOL')
//             ->orWhere('grad_type', 'TARTEEL ZALLA');
//     }, 'students.attendance'])->get();

//     // Initialize a PDF instance for merging
//     $mergedPdf = new \setasign\Fpdi\Fpdi();

//     foreach ($teachers as $teacher) {
//         $classId = $teacher->class_id;
//         $classData = [
//             'teacher' => $teacher,
//             'students' => [],
//             'totalCa' => [],
//             'attendanceRecords' => [],
//             'totalScores' => [],
//             'averageTotal' => [],
//             'cummulativetotalScores' => [],
//             'cummulativeaverageTotal' => [],
//         ];

//         foreach ($teacher->students as $student) {
//             if (!isset($classData['students'][$student->id])) {
//                 $classData['students'][$student->id] = $student;
//                 $classData['totalCa'][$student->id] = [];
//                 $classData['attendanceRecords'][$student->id] = $student->attendance
//                     ->where('session', $sessions->session)
//                     ->where('term', $sessions->term)
//                     ->filter(function ($record) {
//                         return in_array($record->status, ['Present', 'present', 'Late', 'late', 'excused', 'Excused']);
//                     });

//                 $totalAttendanceRecords = $student->attendance
//                     ->where('session', $sessions->session)
//                     ->where('term', $sessions->term)
//                     ->count();
//                 $presentAttendanceRecords = $classData['attendanceRecords'][$student->id]->count();
//                 $percentage = $totalAttendanceRecords > 0 ? ($presentAttendanceRecords / $totalAttendanceRecords) * 100 : 0;
//                 $student->attendancePercentage = $percentage;
//             }

//             foreach ($student->exams as $subjects) {
//                 $matchingSubjects = $subjects->where('session', $sessions->session)
//                     ->where('term', $sessions->term)
//                     ->where('student_id', $student->id)
//                     ->get();

//                 foreach ($matchingSubjects as $subject) {
//                     $first_cas = is_numeric($subject->first_ca) ? $subject->first_ca : 0;
//                     $second_cas = is_numeric($subject->second_ca) ? $subject->second_ca : 0;
//                     $third_cas = is_numeric($subject->third_ca) ? $subject->third_ca : 0;

//                     $classData['totalCa'][$student->id][$subject->subject_id] = $first_cas + $second_cas + $third_cas;
//                 }
//             }
//         }

//         foreach ($classData['students'] as $student) {
//             $classData['totalScores'][$student->id] = 0;
//             $classData['averageTotal'][$student->id] = 0;

//             foreach ($student->exams as $subject) {
//                 if ($subject->session == $sessions->session && $subject->term == $sessions->term && $subject->student_id == $student->id) {
//                     $first_cas = is_numeric($subject->first_ca) ? $subject->first_ca : 0;
//                     $second_cas = is_numeric($subject->second_ca) ? $subject->second_ca : 0;
//                     $third_cas = is_numeric($subject->third_ca) ? $subject->third_ca : 0;
//                     $examss = is_numeric($subject->exams) ? $subject->exams : 0;

//                     $classData['totalScores'][$student->id] += $first_cas + $second_cas + $third_cas + $examss;
//                 }
//             }

//             $classData['averageTotal'][$student->id] = count($student->exams->where('session', $sessions->session)->where('term', $sessions->term)) > 0
//                 ? $classData['totalScores'][$student->id] / count($student->exams->where('session', $sessions->session)->where('term', $sessions->term))
//                 : 0;

//             $student->averageTotal = $classData['averageTotal'][$student->id];
//         }

//         foreach ($classData['students'] as $student) {
//             $classData['cummulativetotalScores'][$student->id] = 0;
//             $classData['cummulativeaverageTotal'][$student->id] = 0;

//             foreach ($student->exams as $subject) {

//                 $cummulativematchingSubjects = [];
        
//                 if ($subject->session == $sessions->session && $subject->student_id == $student->id) {
//                     $cummulativematchingSubjects[] = $subject;
//         }

//                 if ($subject->session == $sessions->session && $subject->student_id == $student->id) {
//                     $first_cas = is_numeric($subject->first_ca) ? $subject->first_ca : 0;
//                     $second_cas = is_numeric($subject->second_ca) ? $subject->second_ca : 0;
//                     $third_cas = is_numeric($subject->third_ca) ? $subject->third_ca : 0;
//                     $examss = is_numeric($subject->exams) ? $subject->exams : 0;

//                     $classData['cummulativetotalScores'][$student->id] += $first_cas + $second_cas + $third_cas + $examss;
//                 }
//             }

//             $classData['cummulativeaverageTotal'][$student->id] = count($student->exams->where('session', $sessions->session)) > 0
//                 ? $classData['cummulativetotalScores'][$student->id] / count($student->exams->where('session', $sessions->session))
//                 : 0;

//             $student->cummulativeaverageTotal = $classData['cummulativeaverageTotal'][$student->id];
//         }

//         $orderedStudents = collect($classData['students'])->map(function ($student) use ($sessions) {
//             $matchingSubjects = $student->exams->where('session', $sessions->session)->where('student_id', $student->id);
//             $totalScores = 0;
//             $examCount = count($matchingSubjects);

//             foreach ($matchingSubjects as $subject) {
//                 $first_cas = is_numeric($subject->first_ca) ? $subject->first_ca : 0;
//                 $second_cas = is_numeric($subject->second_ca) ? $subject->second_ca : 0;
//                 $third_cas = is_numeric($subject->third_ca) ? $subject->third_ca : 0;
//                 $examss = is_numeric($subject->exams) ? $subject->exams : 0;

//                 $totalScores += $first_cas + $second_cas + $third_cas + $examss;
//             }

//             $averageTotal = $examCount > 0 ? $totalScores / $examCount : 0;
//             $student->averageTotal = $averageTotal;

//             return $student;
//         })->sortByDesc('averageTotal');

//         $position = 1;
//         $previousAverage = null;

//         foreach ($orderedStudents as $student) {
//             if ($previousAverage !== null && $student->averageTotal < $previousAverage) {
//                 $position++;
//             }

//             $student->position = $this->cleanSheetsOrdinalSuffix($position);
//             $previousAverage = $student->averageTotal;
//         }

//         $data = [
//             'exam' => $exam,
//             'sessions' => $sessions,
//             'class' => $classData['teacher']->class,
//             'teacher' => $classData['teacher'],
//             'totalCa' => $classData['totalCa'],
//             'totalScores' => $classData['totalScores'],
//             'averageTotal' => $classData['averageTotal'],
//             'orderedStudents' => $orderedStudents,
//             'matchingSubjects' => $matchingSubjects,
//             'cummulativematchingSubjects' => $cummulativematchingSubjects,
//             'session' => $sessions->session,
//             'term' => $sessions->term,
//             'cummulativeaverageTotal' => $classData['cummulativeaverageTotal'],
//         ];

//         // Generate PDF content for the current class
//         $pdfContent = PDF::loadView('PDFs.cleanSheet', $data)->output();

//         // Add PDF content to merged PDF
//         $tempPdfFile = tempnam(sys_get_temp_dir(), 'cleanSheet');
//         file_put_contents($tempPdfFile, $pdfContent);

//         $pageCount = $mergedPdf->setSourceFile($tempPdfFile);
//         for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
//             $templateId = $mergedPdf->importPage($pageNo);
//             $size = $mergedPdf->getTemplateSize($templateId);

//             $mergedPdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
//             $mergedPdf->useTemplate($templateId);
//         }

//         unlink($tempPdfFile);
//     }

//     // Output merged PDF file
//     $mergedPdfFileName = 'all_clean_sheets.pdf';
//     $mergedPdf->Output($mergedPdfFileName, 'D');
// }


// // ADD ORDINAL SUFFIX FOR ALL CLEANSHEETS
//         function cleanSheetsOrdinalSuffix($position) {
//             if ($position % 100 >= 11 && $position % 100 <= 13) {
//                 return $position . 'th';
//             } else {
//                 switch ($position % 10) {
//                     case 1:
//                         return $position . 'st';
//                     case 2:
//                         return $position . 'nd';
//                     case 3:
//                         return $position . 'rd';
//                     default:
//                         return $position . 'th';
//                 }
//             }
//         }

        //Download Report Sheet For Guardians
public function reportSheetForGuardians($id, $term, $session){

    $exam = ExamsModel::where('student_id', $id)->where('term', $term)->where('session', str_replace('_', '/', $session))->get();

    $sessions = sessions::where('term', $term)->where('session', str_replace('_', '/', $session))->first();

    $subjects = subjectsModel::whereIn('subject', $exam->pluck('subject_id'))->get();

    $class = register_student::where('id', $id)->value('class');

    $teacher = register_teacher::where('class', $class)
    ->with(['students' => function ($query) 
    {
        $query->where('status', 'IN SCHOOL')
        ->orWhere('grad_type', 'TARTEEL ZALLA');
    }, 'students.attendance'])
    ->first();

    $dalibi = register_student::where('id', $id)->first();

    $class = register_student::where('class', $dalibi->class)
    ->where('status', 'IN SCHOOL')
    ->orwhere('status', 'TARTEEL ZALLA')
    ->count();

    $first_cas = [];
    $first_cas[$id] = [];

    $second_cas = [];
    $second_cas[$id] = [];
    $totalCa = [];

    $totalCa[$id] = [];
    $totalScores = [];
    $totalExam = [];
    $grandTotal = [];
    $averageTotal = [];
    $cummulativeAverageTotal = [];

    foreach ($dalibi->exams as $subject) {
        if ($subject->term == $term && $subject->session == str_replace('_', '/', $session)) {
            $subjectId = $subject->subject_id;
            $studentId = $subject->student_id;

            $first_cas[$subjectId] = !is_null($subject->first_ca) ? $subject->first_ca : "-";
            $second_cas[$subjectId] = !is_null($subject->second_ca) ? $subject->second_ca : "-";
            
            $totalCa[$subjectId] = $subject->first_ca + $subject->second_ca + $subject->third_ca;
            $totalScores[$subjectId] = $subject->first_ca + $subject->second_ca + $subject->third_ca + $subject->exams;
            $totalExam[$subjectId] = !is_null($subject->exams) ? $subject->exams : "-";

            if (!isset($totalScores[$subjectId])) {
                $totalScores[$subjectId] = 0; 
            }

            if ($totalScores[$subjectId] >= 70) {
                $grade[$subjectId] = 'A';
                $remark[$subjectId] = 'Excellent';
            } elseif ($totalScores[$subjectId] >= 60 && $totalScores[$subjectId] < 70) {
                $grade[$subjectId] = 'B';
                $remark[$subjectId] = 'V. Good';
            } elseif ($totalScores[$subjectId] >= 50 && $totalScores[$subjectId] < 60) { 
                $grade[$subjectId] = 'C';
                $remark[$subjectId] = 'Good';
            } elseif ($totalScores[$subjectId] >= 40 && $totalScores[$subjectId] < 50) {
                $grade[$subjectId] = 'D';
                $remark[$subjectId] = 'Pass';
            } elseif ($totalScores[$subjectId] >= 0 && $totalScores[$subjectId] < 40) {
                $grade[$subjectId] = 'F';
                $remark[$subjectId] = 'Fail';
            } else {
                $grade[$subjectId] = '-';
                $remark[$subjectId] = 'Invalid Score'; 
            }
            
            // Assign the values to the subject
            $subject->grade = $grade[$subjectId];
            $subject->remark = $remark[$subjectId];
            
            // Calculate grand total and average total outside the loop
            if (!isset($grandTotal[$studentId])) {
                $grandTotal[$studentId] = 0;
            }
            $grandTotal[$studentId] += $totalScores[$subjectId];
            $averageTotal[$studentId] = count($dalibi->exams) > 0 ? $grandTotal[$studentId] / count($dalibi->exams->where('term', $term)->where('session', str_replace('_', '/', $session))) : 0;

            if ($averageTotal[$studentId] >= 70) {
                $TeachersRemark[$studentId] = 'Outstanding performance! Keep aiming higher.';
                $PrincipalsRemark[$studentId] = 'Excellent achievement! Continue to strive for greatness.';
            } elseif ($averageTotal[$studentId] >= 60 && $averageTotal[$studentId] < 70) {
                $TeachersRemark[$studentId] = 'Great job! You are doing very well.';
                $PrincipalsRemark[$studentId] = 'Impressive work! Maintain this momentum and aim for excellence.';
            } elseif ($averageTotal[$studentId] >= 50 && $averageTotal[$studentId] < 60) {
                $TeachersRemark[$studentId] = 'Good effort! You have the potential to achieve even more.';
                $PrincipalsRemark[$studentId] = 'Solid performance! With a little more effort, you can excel further.';
            } elseif ($averageTotal[$studentId] >= 40 && $averageTotal[$studentId] < 50) {
                $TeachersRemark[$studentId] = 'A fair result. You can achieve better with consistent effort.';
                $PrincipalsRemark[$studentId] = 'You’re on the right track. Focus and push yourself to improve.';
            } elseif ($averageTotal[$studentId] >= 0 && $averageTotal[$studentId] < 40) {
                $TeachersRemark[$studentId] = 'Keep trying. You have the ability to improve with hard work.';
                $PrincipalsRemark[$studentId] = 'Do not give up! Hard work and determination will lead to better results.';
            } else {
                $TeachersRemark[$studentId] = 'No remarks available.';
                $PrincipalsRemark[$studentId] = 'No remarks available.'; // Handles any unexpected scores
            }
        }
    }

    $cummulativematchingSubjects = $dalibi->exams
        ->where('session', str_replace('_', '/', $session))
        ->where('student_id', $dalibi->id)
        ->filter(function ($exam) use ($term) {
            // Ensure we only consider terms up to and including the selected term
            return $this->termOrder($exam->term) <= $this->termOrder($term);
        });

    foreach ($cummulativematchingSubjects as $subject) {

            $subjectId = $subject->subject_id;
            $studentId = $subject->student_id;
            
            $cummulativeTotalCa[$subjectId] = $subject->first_ca + $subject->second_ca + $subject->third_ca;
            $cummulativeTotalScores[$subjectId] = $subject->first_ca + $subject->second_ca + $subject->third_ca + $subject->exams;
            $cummulativeTotalExam[$subjectId] = $subject->exams;
            
            // Calculate grand total and average total outside the loop
            if (!isset($cummulativeGrandTotal[$studentId])) {
                $cummulativeGrandTotal[$studentId] = 0;
            }
            $cummulativeGrandTotal[$studentId] += $cummulativeTotalScores[$subjectId];
            $cummulativeAverageTotal[$studentId] = count($cummulativematchingSubjects) > 0 ? $cummulativeGrandTotal[$studentId] / count($cummulativematchingSubjects) : 0;
        }
    
    $attendanceRecords[$dalibi->id] = $dalibi->attendance->where('term', $term)->where('session', str_replace('_', '/', $session))->filter(function ($record) {
        return in_array($record->status, ['Present', 'present', 'Late', 'late', 'excused', 'Excused']);
    });

    $totalAttendanceRecords = $dalibi->attendance->where('term', $term)->where('session', str_replace('_', '/', $session))->count();
    $presentAttendanceRecords = $attendanceRecords[$dalibi->id]->count();
    $percentage = $totalAttendanceRecords > 0 ? ($presentAttendanceRecords / $totalAttendanceRecords) * 100 : 0;
    $dalibi->attendancePercentage = $percentage;

    /**
 * Convert term names to a comparable order value.
 *
 * @param string $term
 * @return int
 */

    // =====================================================
// POSITION CODES
// =====================================================

// Function to add ordinal suffix
function GuardianresultOrdinalSuffix($position) {
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

$matchingSubjects = [];

$orderedStudents = $teacher->students->map(function ($student) use ($session, $term,  &$matchingSubjects) {

    $matchingSubjects = $student->exams->where('session', str_replace('_', '/', $session))
    ->where('student_id', $student->id);

    $totalScores = 0;
    $examCount = count($matchingSubjects);

foreach ($matchingSubjects as $subject) {

    $first_cas = is_numeric($subject->first_ca) ? $subject->first_ca : 0;
    $second_cas = is_numeric($subject->second_ca) ? $subject->second_ca : 0;
    $third_cas = is_numeric($subject->third_ca) ? $subject->third_ca : 0;
    $examss = is_numeric($subject->exams) ? $subject->exams : 0;

        $totalScores +=  $first_cas + $second_cas + $third_cas + $examss;
}

    $averageTotal = $examCount > 0 ? $totalScores / $examCount : 0;
    $student->averageTotal = $averageTotal;

    return $student;
})->sortByDesc('averageTotal');


$position = 1;
$previousAverage = null;

foreach ($orderedStudents as $student) {
    if ($student->averageTotal !== null && $student->averageTotal < $previousAverage) {
        $position++;
    }
    $student->position = GuardianresultOrdinalSuffix($position);
    $studentPositions[$student->id] = GuardianresultOrdinalSuffix($position);

    $previousAverage = $student->averageTotal;

}

$studentPosition = null;

foreach ($orderedStudents as $student) {
foreach ($student->exams as $jarabawa) {
    if ($jarabawa->student_id === $id) {
        $studentPosition = $student->position;
        break;
    }
}
}

 // =====================================================
// END OF POSITION CODES
// =====================================================

 // Retrieve the student data
 $student = register_student::findOrFail($id);

 // Generate PDF using Dompdf
 $pdf = PDF::loadView('PDFs.reportSheet', [
    'sessions' => $sessions, 
    'student' => $student,
    'class' => $class,
    'exam' => $exam,
    'subjects' => $subjects,
    'second_cas' => $second_cas,
    'first_cas' => $first_cas,
    'totalCa' => $totalCa,
    'totalScores' => $totalScores,
    'totalExam' => $totalExam,
    'grandTotal' => $grandTotal,
    'averageTotal' => $averageTotal,
    'grade' => $grade,
    'remark' => $remark,
    'TeachersRemark' => $TeachersRemark,
    'PrincipalsRemark' => $PrincipalsRemark,
    'dalibi' => $dalibi,
    'studentPosition' => $studentPosition,
    'cummulativeAverageTotal' => $cummulativeAverageTotal,
]);

 // Return a response with PDF content to download
 return $pdf->download($dalibi->fullname);

}

private function termOrder($term) {
    $terms = [
        '1st Term' => 1,
        '2nd Term' => 2,
        '3rd Term' => 3,
    ];

    return $terms[$term] ?? 0;
}

}
