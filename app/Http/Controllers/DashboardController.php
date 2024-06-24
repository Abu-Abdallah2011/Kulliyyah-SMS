<?php

namespace App\Http\Controllers;

use App\Models\Hadda;
use App\Models\sessions;
use App\Models\ExamsModel;
use Illuminate\Http\Request;
use App\Models\AttendanceModel;
use App\Models\SchoolFeesModel;
use App\Models\register_student;
use App\Models\register_teacher;
use App\Models\register_guardian;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Models\teachersAttendanceModel;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Foundation\Http\FormRequest;

class DashboardController extends Controller
{
    // Show the Dashboard of User after Authentication
    public function view()
     {
        $class = register_teacher::where('user_id', Auth::user()->id)->value('class');

        $teachers = register_teacher::where('class', $class)
        ->with(['students' => function ($query) 
        {
            $query->where('status', 'IN SCHOOL')
            ->orWhere('grad_type', 'TARTEEL ZALLA')
            ->orderBy('fullname');
        }])->with('user')
        ->get();


        $teacher = register_teacher::where('class', $class)->where('user_id', auth()->user()->id)
        ->with(['students' => function ($query) 
        {
            $query->where('status', 'IN SCHOOL')
            ->orWhere('grad_type', 'TARTEEL ZALLA')
            ->orderBy('fullname');
        }])
        ->first();
        
        $guardians = register_guardian::where('user_id', Auth::user()->id)->with('students')->get();

        $graduates = register_student::where('status', 'GRADUATE');

        $session = sessions::orderBy('created_at', 'desc')->first();

        // STATISTICS

        // SCHOOL FEES STATISTICS

        $SchoolFees = SchoolFeesModel::where('session', sessions::pluck('session')->last())->get();

        // Count the number of records for each status
        $totalrecord = $SchoolFees->count();
        $totalpaid = $SchoolFees->where('status', 'PAID')->count();
        $totalfree = $SchoolFees->where('status', 'FREE')->count();
        $totalnotpaid = $SchoolFees->where('status', 'UNCLEARED')->count();
        $totalpart = $SchoolFees->where('status', 'PART')->count();
    
        // Calculate the percentages
        $percentagepaid = $totalrecord > 0 ? ($totalpaid / $totalrecord) * 100 : 0;
        $percentagefree = $totalrecord > 0 ? ($totalfree / $totalrecord) * 100 : 0;
        $percentagenotpaid = $totalrecord > 0 ? ($totalnotpaid / $totalrecord) * 100 : 0;
        $percentagepart = $totalrecord > 0 ? ($totalpart / $totalrecord) * 100 : 0;

        // STUDENTS ATTENDANCE STATISTICS

        $sessions = sessions::orderBy('created_at', 'desc')->first();
    
        $totalattendancerecordsforterm = AttendanceModel::where('session', $sessions->session)
        ->where('term', $sessions->term)
        ->count();

        $presentattendancerecordsforterm = AttendanceModel::where('session', $sessions->session)
        ->where('term', $sessions->term)
        ->where('status', 'present')
        ->count();

        $absentattendancerecordsforterm = AttendanceModel::where('session', $sessions->session)
        ->where('term', $sessions->term)
        ->where('status', 'absent')
        ->count();

        $excusedattendancerecordsforterm = AttendanceModel::where('session', $sessions->session)
        ->where('term', $sessions->term)
        ->where('status', 'excused')
        ->count();

        $lateattendancerecordsforterm = AttendanceModel::where('session', $sessions->session)
        ->where('term', $sessions->term)
        ->where('status', 'late')
        ->count();

        $presentpercentage = $presentattendancerecordsforterm > 0 ? ($presentattendancerecordsforterm / $totalattendancerecordsforterm) * 100 : 0;
        $absentpercentage = $absentattendancerecordsforterm > 0 ? ($absentattendancerecordsforterm / $totalattendancerecordsforterm) * 100 : 0;
        $excusedpercentage = $excusedattendancerecordsforterm > 0 ? ($excusedattendancerecordsforterm / $totalattendancerecordsforterm) * 100 : 0;
        $latepercentage = $lateattendancerecordsforterm > 0 ? ($lateattendancerecordsforterm / $totalattendancerecordsforterm) * 100 : 0;

    // TEACHERS ATTENDANCE STATISTICS

    $totalattendancerecordsforteachers = teachersAttendanceModel::where('session', $sessions->session)
    ->where('term', $sessions->term)
    ->count();

    $presentattendancerecordsforteachers = teachersAttendanceModel::where('session', $sessions->session)
    ->where('term', $sessions->term)
    ->where('status', 'Present')
    ->count();
    // dd($presentattendancerecordsforteachers);

    $absentattendancerecordsforteachers = teachersAttendanceModel::where('session', $sessions->session)
    ->where('term', $sessions->term)
    ->where('status', 'Absent')
    ->count();

    $excusedattendancerecordsforteachers = teachersAttendanceModel::where('session', $sessions->session)
    ->where('term', $sessions->term)
    ->where('status', 'excused')
    ->count();

    $lateattendancerecordsforteachers = teachersAttendanceModel::where('session', $sessions->session)
    ->where('term', $sessions->term)
    ->where('status', 'Late')
    ->count();

    $excusedlate = teachersAttendanceModel::where('session', $sessions->session)
    ->where('term', $sessions->term)
    ->where('status', 'late with an excuse')
    ->count();

    $closedearly = teachersAttendanceModel::where('session', $sessions->session)
    ->where('term', $sessions->term)
    ->where('status', 'Closed early')
    ->count();

    $camelateandclosedearly = teachersAttendanceModel::where('session', $sessions->session)
    ->where('term', $sessions->term)
    ->where('status', 'Came late and closed early')
    ->count();

    $presentpercentageforteachers = $presentattendancerecordsforteachers > 0 ? ($presentattendancerecordsforteachers / $totalattendancerecordsforteachers) * 100 : 0;
    $absentpercentageforteachers = $absentattendancerecordsforteachers > 0 ? ($absentattendancerecordsforteachers / $totalattendancerecordsforteachers) * 100 : 0;
    $excusedpercentageforteachers = $excusedattendancerecordsforteachers > 0 ? ($excusedattendancerecordsforteachers / $totalattendancerecordsforteachers) * 100 : 0;
    $latepercentageforteachers = $lateattendancerecordsforteachers > 0 ? ($lateattendancerecordsforteachers / $totalattendancerecordsforteachers) * 100 : 0;
    $percentageexcusedlate = $excusedlate > 0 ? ($excusedlate / $totalattendancerecordsforteachers) * 100 : 0;
    $percentageclosedearly = $closedearly > 0 ? ($closedearly / $totalattendancerecordsforteachers) * 100 : 0;
    $percentagelatecominandearlyclose = $camelateandclosedearly > 0 ? ($camelateandclosedearly / $totalattendancerecordsforteachers) * 100 : 0;

        // TOTAL NUMBER OF TEACHERS

        $totalteachers = register_teacher::where('status', 'IN SCHOOL')->count();

        $totalmaleteachers = register_teacher::where('status', 'IN SCHOOL')
        ->where('gender', 'MALE')
        ->count();

        $totalfemaleteachers = register_teacher::where('status', 'IN SCHOOL')
        ->where('gender', 'FEMALE')
        ->count();

        // TOTAL NUMBER OF STUDENTS

        $totalstudents = register_student::where('status', 'IN SCHOOL')->count();

        $totalmalestudents = register_student::where('status', 'IN SCHOOL')
        ->where('gender', 'MALE')
        ->count();
        
        $totalfemalestudents = register_student::where('status', 'IN SCHOOL')
        ->where('gender', 'FEMALE')
        ->count();

        // CALCULATING SINGLE CLASS STUDENTS ATTENDANCE PERCENTAGES

        function getPresentAttendancePercentage($teacher)
        {
            $session = sessions::orderBy('created_at', 'desc')->first();
            $students = $teacher->students;
        
            $totalAttendances = 0;
            $totalPresent = 0;
        
            foreach ($students as $student) {
                $attendances = $student->attendance;
                $totalAttendances += $attendances->where('term', $session->term)->where('session', $session->session)->count();
                $totalPresent += $attendances->where('term', $session->term)->where('session', $session->session)
                    ->where('status', 'present')->count();
            }
        
            if ($totalAttendances == 0) {
                return 0;
            }
        
            return ($totalPresent / $totalAttendances) * 100;
        }
        
        function getLatePercentage($teacher)
        {
            $session = sessions::orderBy('created_at', 'desc')->first();
            $students = $teacher->students;
        
            $totalAttendances = 0;
            $totalLate = 0;
        
            foreach ($students as $student) {
                $attendances = $student->attendance;
                $totalAttendances += $attendances->where('term', $session->term)->where('session', $session->session)->count();
                $totalLate += $attendances->where('term', $session->term)->where('session', $session->session)
                    ->where('status', 'late')->count();
            }
        
            if ($totalAttendances == 0) {
                return 0;
            }
        
            return ($totalLate / $totalAttendances) * 100;
        }
        
        function getExcusedPercentage($teacher)
        {
            $session = sessions::orderBy('created_at', 'desc')->first();
            $students = $teacher->students;
        
            $totalAttendances = 0;
            $totalExcused = 0;
        
            foreach ($students as $student) {
                $attendances = $student->attendance;
                $totalAttendances += $attendances->where('term', $session->term)->where('session', $session->session)->count();
                $totalExcused += $attendances->where('term', $session->term)->where('session', $session->session)
                    ->where('status', 'excused')->count();
            }
        
            if ($totalAttendances == 0) {
                return 0;
            }
        
            return ($totalExcused / $totalAttendances) * 100;
        }
        
        function getAbsentPercentage($teacher)
        {
            $session = sessions::orderBy('created_at', 'desc')->first();
            $students = $teacher->students;
        
            $totalAttendances = 0;
            $totalAbsent = 0;
        
            foreach ($students as $student) {
                $attendances = $student->attendance;
                $totalAttendances += $attendances->where('term', $session->term)->where('session', $session->session)->count();
                $totalAbsent += $attendances->where('term', $session->term)->where('session', $session->session)
                    ->where('status', 'absent')->count();
            }
        
            if ($totalAttendances == 0) {
                return 0;
            }
        
            return ($totalAbsent / $totalAttendances) * 100;
        }
        if (Gate::allows('isAssistant')) {
            $teacher = register_teacher::where('user_id', Auth::user()->id)
                ->with(['students' => function ($query) {
                    $query->where('status', 'IN SCHOOL')
                        ->orWhere('grad_type', 'TARTEEL ZALLA')
                        ->orderBy('fullname');
                }])
                ->first();
        
            if ($teacher) {
                $percentageclasspresent = getPresentAttendancePercentage($teacher);
                $percentageclasslate = getLatePercentage($teacher);
                $percentageclassexcused = getExcusedPercentage($teacher);
                $percentageclassabsent = getAbsentPercentage($teacher);
            }        

return view('dashboard', [
    'teacher' => $teacher,
    'teachers' => $teachers,
    'guardians' => $guardians,
    'class' => $class,
    'graduates' => $graduates,
    'session' => $session,
    'totalpaid' => $totalpaid,
    'totalfree' => $totalfree,
    'totalnotpaid' => $totalnotpaid,
    'totalpart' => $totalpart,
    'percentagepaid' => $percentagepaid,
    'percentagefree' => $percentagefree,
    'percentagenotpaid' => $percentagenotpaid,
    'percentagepart' => $percentagepart,
    'presentpercentage' => $presentpercentage,
    'absentpercentage' => $absentpercentage,
    'excusedpercentage' => $excusedpercentage,
    'latepercentage' => $latepercentage,
    'presentpercentageforteachers' => $presentpercentageforteachers,
    'absentpercentageforteachers' => $absentpercentageforteachers,
    'excusedpercentageforteachers' => $excusedpercentageforteachers,
    'latepercentageforteachers' => $latepercentageforteachers,
    'percentageexcusedlate' => $percentageexcusedlate,
    'percentageclosedearly' => $percentageclosedearly,
    'percentagelatecominandearlyclose' => $percentagelatecominandearlyclose,
    'totalteachers' => $totalteachers,
    'totalmaleteachers' => $totalmaleteachers,
    'totalfemaleteachers' => $totalfemaleteachers,
    'totalstudents' => $totalstudents,
    'totalmalestudents' => $totalmalestudents,
    'totalfemalestudents' => $totalfemalestudents,
    'percentageclasspresent' => $percentageclasspresent,
    'percentageclasslate' => $percentageclasslate,
    'percentageclassexcused' => $percentageclassexcused,
    'percentageclassabsent' => $percentageclassabsent,
]);
}

        return view('dashboard', [
        'teacher' => $teacher,
        'teachers' => $teachers,
        'guardians' => $guardians,
        'class' => $class,
        'graduates' => $graduates,
        'session' => $session,
        'totalpaid' => $totalpaid,
        'totalfree' => $totalfree,
        'totalnotpaid' => $totalnotpaid,
        'totalpart' => $totalpart,
        'percentagepaid' => $percentagepaid,
        'percentagefree' => $percentagefree,
        'percentagenotpaid' => $percentagenotpaid,
        'percentagepart' => $percentagepart,
        'presentpercentage' => $presentpercentage,
        'absentpercentage' => $absentpercentage,
        'excusedpercentage' => $excusedpercentage,
        'latepercentage' => $latepercentage,
        'presentpercentageforteachers' => $presentpercentageforteachers,
        'absentpercentageforteachers' => $absentpercentageforteachers,
        'excusedpercentageforteachers' => $excusedpercentageforteachers,
        'latepercentageforteachers' => $latepercentageforteachers,
        'percentageexcusedlate' => $percentageexcusedlate,
        'percentageclosedearly' => $percentageclosedearly,
        'percentagelatecominandearlyclose' => $percentagelatecominandearlyclose,
        'totalteachers' => $totalteachers,
        'totalmaleteachers' => $totalmaleteachers,
        'totalfemaleteachers' => $totalfemaleteachers,
        'totalstudents' => $totalstudents,
        'totalmalestudents' => $totalmalestudents,
        'totalfemalestudents' => $totalfemalestudents,
    ]);
    }
    

    // show the dashboard of selected Guardian for Admin
public function show($guardian_id)
{
    $guardian = register_guardian::findOrFail($guardian_id);
    $students = $guardian->students;
    
    return view('dashboard', [
        'guardian' => $guardian,
        'students' => $students,
    ]);
}


}