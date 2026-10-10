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
use App\Support\AcademicPeriodResolver;
use App\Support\HaddaAnalyticsBuilder;
use App\Support\HaddaLeaderboardBuilder;
use App\Support\HaddaRecordGuard;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use setasign\Fpdi\Fpdi;

class HaddaController extends Controller
{
    public function leaderboard(Request $request)
    {
        $hasFullLeaderboardAccess = Gate::allows('isAdmin');
        $teacherClass = register_teacher::where('user_id', Auth::id())->value('class');

        abort_unless($hasFullLeaderboardAccess || $teacherClass, 403);

        $currentSession = sessions::orderBy('created_at', 'desc')->first();
        $selectedSession = $request->input('session', $currentSession?->session);
        $selectedTerm = $request->input('term', $currentSession?->term);
        $selectedClass = $request->input('class');

        if (!$hasFullLeaderboardAccess && $selectedClass !== $teacherClass) {
            $selectedClass = null;
        }

        $selectedPeriod = $request->input('period', 'term');
        $selectedWeekStart = $request->input('week_start', now()->startOfWeek()->format('Y-m-d'));
        [$dateFrom, $dateTo] = $this->leaderboardDateRange($selectedPeriod, $selectedWeekStart);
        $sessions = sessions::orderBy('created_at', 'desc')->get();
        $leaderboardData = HaddaLeaderboardBuilder::build(
            $selectedSession,
            $selectedTerm,
            $selectedClass,
            $dateFrom,
            $dateTo,
            (bool) $selectedClass
        );
        $classes = $hasFullLeaderboardAccess
            ? $leaderboardData['classes']
            : collect([$teacherClass])->filter()->values();
        $schoolWide = !$selectedClass;
        $showLeaderboardDownload = $hasFullLeaderboardAccess || !$schoolWide;
        $leaderboardDownloadUrl = null;
        $leaderboardDownloadLabel = null;

        if ($showLeaderboardDownload) {
            $leaderboardDownloadUrl = $selectedClass
                ? route('hadda.leaderboard.class_pdf', array_merge(['class' => $selectedClass], $request->only(['session', 'term', 'period', 'week_start'])))
                : route('hadda.leaderboard.pdf', $request->query());
            $leaderboardDownloadLabel = $selectedClass ? 'Download Class Leaderboard PDF' : 'Download Top 5 PDF';
        }

        $studentsWithoutRecords = ($hasFullLeaderboardAccess || $selectedClass)
            ? $leaderboardData['studentsWithoutRecords']
            : collect();

        return view('Hadda.leaderboard', [
            'leaderboard' => $leaderboardData['studentRankings'],
            'topStudents' => $leaderboardData['topStudents'],
            'classRankings' => $leaderboardData['classRankings'],
            'summary' => $leaderboardData['summary'],
            'studentsWithoutRecords' => $studentsWithoutRecords,
            'classes' => $classes,
            'sessions' => $sessions,
            'selectedSession' => $selectedSession,
            'selectedTerm' => $selectedTerm,
            'selectedClass' => $selectedClass,
            'selectedPeriod' => $selectedPeriod,
            'selectedWeekStart' => $selectedWeekStart,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'periodLabel' => HaddaLeaderboardBuilder::rankingPeriodLabel($selectedSession, $selectedTerm, $dateFrom, $dateTo),
            'hasFullLeaderboardAccess' => $hasFullLeaderboardAccess,
            'teacherClass' => $teacherClass,
            'canExpandFullSchoolLeaderboard' => $hasFullLeaderboardAccess,
            'showLeaderboardDownload' => $showLeaderboardDownload,
            'leaderboardDownloadUrl' => $leaderboardDownloadUrl,
            'leaderboardDownloadLabel' => $leaderboardDownloadLabel,
        ]);
    }

    public function leaderboardPdf(Request $request)
    {
        abort_unless(Gate::allows('isAdmin'), 403);

        $currentSession = sessions::orderBy('created_at', 'desc')->first();
        $selectedSession = $request->input('session', $currentSession?->session);
        $selectedTerm = $request->input('term', $currentSession?->term);
        $selectedClass = $request->input('class');
        $selectedPeriod = $request->input('period', 'term');
        $selectedWeekStart = $request->input('week_start', now()->startOfWeek()->format('Y-m-d'));
        [$dateFrom, $dateTo] = $this->leaderboardDateRange($selectedPeriod, $selectedWeekStart);
        $leaderboardData = HaddaLeaderboardBuilder::build($selectedSession, $selectedTerm, $selectedClass, $dateFrom, $dateTo);

        $pdf = PDF::loadView('PDFs.hadda_leaderboard_top_five', [
            'topStudents' => $leaderboardData['studentRankings']->take(5),
            'classRankings' => $leaderboardData['classRankings'],
            'selectedClass' => $selectedClass,
            'periodLabel' => HaddaLeaderboardBuilder::rankingPeriodLabel($selectedSession, $selectedTerm, $dateFrom, $dateTo),
            'term' => $selectedTerm,
            'session' => $selectedSession,
        ])->setPaper('a4', 'portrait');

        $fileScope = $selectedClass ? str_replace(['/', '\\'], '-', $selectedClass) : 'School';
        $filePeriod = $dateFrom && $dateTo ? $dateFrom->format('Y-m-d').'-to-'.$dateTo->format('Y-m-d') : $selectedTerm;

        return $pdf->download($fileScope.' Hadda Top Five - '.$filePeriod.'.pdf');
    }

    public function classLeaderboardPdf(Request $request, $class)
    {
        $hasFullLeaderboardAccess = Gate::allows('isAdmin');
        $teacherClass = register_teacher::where('user_id', Auth::id())->value('class');

        abort_unless($hasFullLeaderboardAccess || $teacherClass === $class, 403);

        $currentSession = sessions::orderBy('created_at', 'desc')->first();
        $selectedSession = $request->input('session', $currentSession?->session);
        $selectedTerm = $request->input('term', $currentSession?->term);
        $selectedPeriod = $request->input('period', 'term');
        $selectedWeekStart = $request->input('week_start', now()->startOfWeek()->format('Y-m-d'));
        [$dateFrom, $dateTo] = $this->leaderboardDateRange($selectedPeriod, $selectedWeekStart);
        $leaderboardData = HaddaLeaderboardBuilder::build($selectedSession, $selectedTerm, $class, $dateFrom, $dateTo, true);
        $schoolLeaderboardData = HaddaLeaderboardBuilder::build($selectedSession, $selectedTerm, null, $dateFrom, $dateTo);
        $classRankSummary = $schoolLeaderboardData['classRankings']->firstWhere('class', $class);

        $pdf = PDF::loadView('PDFs.hadda_leaderboard_top_five', [
            'topStudents' => $leaderboardData['studentRankings'],
            'classRankings' => $leaderboardData['classRankings'],
            'selectedClass' => $class,
            'periodLabel' => HaddaLeaderboardBuilder::rankingPeriodLabel($selectedSession, $selectedTerm, $dateFrom, $dateTo),
            'term' => $selectedTerm,
            'session' => $selectedSession,
            'reportTitle' => $class.' Hadda Leaderboard',
            'classRankSummary' => $classRankSummary,
            'totalClassCount' => $schoolLeaderboardData['classRankings']->count(),
            'excludedStudents' => $leaderboardData['excludedStudents'] ?? collect(),
        ])->setPaper('a4', 'portrait');

        $fileScope = str_replace(['/', '\\'], '-', $class);
        $filePeriod = $dateFrom && $dateTo ? $dateFrom->format('Y-m-d').'-to-'.$dateTo->format('Y-m-d') : $selectedTerm;

        return $pdf->download($fileScope.' Hadda Leaderboard - '.$filePeriod.'.pdf');
    }

    public function analytics(Request $request)
    {
        $hasFullAnalyticsAccess = Gate::allows('isAdmin');
        $teacherClass = register_teacher::where('user_id', Auth::id())->value('class');

        abort_unless($hasFullAnalyticsAccess || $teacherClass, 403);

        $currentSession = sessions::orderBy('created_at', 'desc')->first();
        $selectedSession = $request->input('session', $currentSession?->session);
        $selectedTerm = $request->input('term', $currentSession?->term);
        $selectedClass = $request->input('class');
        $selectedStudentId = $request->integer('student_id') ?: null;

        if (!$hasFullAnalyticsAccess) {
            if (!$request->has('class')) {
                $selectedClass = $teacherClass;
            } elseif ($selectedClass !== null && $selectedClass !== '' && $selectedClass !== $teacherClass) {
                abort(403);
            } else {
                $selectedClass = $selectedClass ?: null;
            }
        }

        $selectedStudent = null;
        if ($selectedStudentId) {
            $selectedStudent = register_student::coverageLeaderboardEligible()->findOrFail($selectedStudentId);

            if (!$hasFullAnalyticsAccess && $selectedStudent->class !== $teacherClass) {
                abort(403);
            }

            $selectedClass = $selectedStudent->class;
        }

        $analytics = HaddaAnalyticsBuilder::build($selectedSession, $selectedTerm, $selectedClass, $selectedStudentId);
        $sessions = sessions::orderBy('created_at', 'desc')->get();
        $classes = $hasFullAnalyticsAccess
            ? HaddaAnalyticsBuilder::classes()
            : collect([$teacherClass])->filter()->values();
        $students = $selectedClass
            ? register_student::coverageLeaderboardEligible()
                ->where('class', $selectedClass)
                ->orderBy('fullname')
                ->get()
            : collect();

        return view('Hadda.analytics', [
            'analytics' => $analytics,
            'sessions' => $sessions,
            'classes' => $classes,
            'students' => $students,
            'selectedSession' => $selectedSession,
            'selectedTerm' => $selectedTerm,
            'selectedClass' => $selectedClass,
            'selectedStudentId' => $selectedStudentId,
            'selectedStudent' => $selectedStudent,
            'hasFullAnalyticsAccess' => $hasFullAnalyticsAccess,
            'teacherClass' => $teacherClass,
        ]);
    }

    // Show Hadda Page
    public function show($student_id)
    {
        $id = register_student::where('id', $student_id)
        ->value('id');


        $hadda = Hadda::query()
        ->filter(request(['search']))
        ->with(['student'])
        ->where('student_id', $id)
        ->orderByHaddaDate('desc')
        ->paginate(10)
        ->withQueryString();

        $student = register_student::where('id', $student_id)->first();
        $currentSession = sessions::orderBy('created_at', 'desc')->first();
        $termPageSummary = [
            'session' => $currentSession?->session,
            'term' => $currentSession?->term,
            'pages' => 0,
            'records' => 0,
            'unverified' => 0,
        ];

        if ($currentSession) {
            $termRecords = Hadda::where('student_id', $id)
                ->where('session', $currentSession->session)
                ->where('term', $currentSession->term)
                ->get();

            foreach ($termRecords as $record) {
                $coverage = \App\Support\HaddaCoverageCalculator::analyze($record);
                $termPageSummary['pages'] += $coverage['pages'];
                $termPageSummary['records']++;

                if ($coverage['status'] !== 'page') {
                    $termPageSummary['unverified']++;
                }
            }
        }
        $rankSummary = $currentSession
            ? HaddaLeaderboardBuilder::studentRankSummary($student, $currentSession->session, $currentSession->term)
            : null;

        return view('Hadda.hadda_page',[
        'student' => $student, 
        'hadda' => $hadda,
        'termPageSummary' => $termPageSummary,
        'rankSummary' => $rankSummary,
        ]);
    }

        // Show Hadda Status
        public function showStatus($teacher_id)
        {

            $teacher = register_teacher::where('user_id', Auth::user()->id)
            ->with(['students' => function ($query) 
            {
                $query->currentlyEnrolled()->orderBy('fullname');
            }])
            ->first();
            
            $teacherClass = $teacher->class;

            $studentIds = register_student::where('class', $teacherClass)
            ->currentlyEnrolled()
            ->pluck('id')
            ->toArray();

            $hadda = Hadda::whereIn('student_id', $studentIds)
            ->orderByHaddaDate('desc')
            ->paginate(100)
            ->withQueryString();

            $sessions = sessions::orderBy('created_at', 'desc')->first();
            $leaderboardData = HaddaLeaderboardBuilder::build($sessions?->session, $sessions?->term);
            $classRank = $leaderboardData['classRankings']->firstWhere('class', $teacherClass);
    
            return view('Hadda.studentsHadda',[
            'teacher' => $teacher, 
            'hadda' => $hadda,
            'classRank' => $classRank,
            'rankingSession' => $sessions?->session,
            'rankingTerm' => $sessions?->term,
            ]);
        }

    // Show Hadda Entry Form Page
    public function create($student_id)
    {
        $sura = surasModel::all();
        $surah = surasModel::all();
        $student = register_student::where('id', $student_id)->first();
        $currentSession = sessions::orderBy('created_at', 'desc')->first();
        $academicPeriods = sessions::orderBy('term_starts')->get(['session', 'term_starts', 'term_ends']);
        $existingPageRanges = HaddaRecordGuard::existingPageRanges(
            $student->id,
            $currentSession?->session,
            null
        );

        return view('Hadda.HaddaForm', [
            'student' => $student,
            'sura' => $sura,
            'surah' => $surah,
            'existingPageRanges' => $existingPageRanges,
            'academicPeriods' => $academicPeriods,
        ]
        );
    }

       //Store Hadda Information
 public function store(HaddaFormRequest $request, $student_id){

    $data = $request->validated();

    $selectedOptionId = $request->input('from_surah');
    $to_selectedOptionId = $request->input('to_surah');
    
    $student = register_student::where('id', $student_id)->first();
    $teacher = register_teacher::where('user_id', Auth::user()->id)->first();
    $sessions = AcademicPeriodResolver::forDate($request->input('date'));
    $selectedOption = surasModel::find($selectedOptionId);
    $to_selectedOption = surasModel::find($to_selectedOptionId);
        $guard = HaddaRecordGuard::inspect(
            $request->input('from'),
            $request->input('to'),
            $student->id,
            $sessions?->session,
            null
        );

    if ($guard['errors']) {
        return back()->withErrors(['from' => implode(' ', $guard['errors'])])->withInput();
    }
    
    $formData = $request->only([
        'date',
        'from',
        'to',
        'grade',
        'comment',
    ]);
        
    $data = array_merge($formData, [ 
        'class' => $student->class,
        'name' => $student->fullname,
        'teacher' => $teacher->fullname,
        'session' => $sessions?->session,
        'term' => $sessions?->term,
        'student_id' => $student->id,
        'sura' => $selectedOption->sura,
        'to_surah' => $to_selectedOption->sura,
    ]);
    
    $save = Hadda::create($data);
    $hadda = Hadda::where('student_id', $student_id)->get();

    $message = 'Hadda Recorded Successfully!';

    if (!empty($guard['warnings'])) {
        $message .= ' Warning: '.implode(' ', $guard['warnings']);
    }

    return redirect('studentsHadda/{teacher_id}')->with(['message' => $message]);
    
 }

 // Show Edit Form
public function edit($id){
    $sura = surasModel::all();
    $surah = surasModel::all();
    $hadda = Hadda::find($id);
    $currentSession = sessions::orderBy('created_at', 'desc')->first();
    $academicPeriods = sessions::orderBy('term_starts')->get(['session', 'term_starts', 'term_ends']);
    $existingPageRanges = HaddaRecordGuard::existingPageRanges(
        $hadda->student_id,
        $currentSession?->session,
        null,
        $hadda->id
    );

    return view('Hadda.edit_hadda', compact('hadda', 'sura', 'surah', 'existingPageRanges', 'academicPeriods'));
}

      //update Hadda Information
      public function update(HaddaFormRequest $request, $id){

        $data = $request->validated();
        $selectedOptionId = $request->input('from_surah');
        $to_selectedOptionId = $request->input('to_surah');
        
        $entry = Hadda::find($id);
        $student = register_student::where('id', $entry->student_id)->first();

        $teacher = register_teacher::where('user_id', Auth::user()->id)->first();
        $sessions = AcademicPeriodResolver::forDate($request->input('date'));

        $selectedOption = surasModel::find($selectedOptionId);
        $to_selectedOption = surasModel::find($to_selectedOptionId);
        $guard = HaddaRecordGuard::inspect(
            $request->input('from'),
            $request->input('to'),
            $student->id,
            $sessions?->session,
            null,
            $entry->id
        );

        if ($guard['errors']) {
            return back()->withErrors(['from' => implode(' ', $guard['errors'])])->withInput();
        }
        
        $formData = $request->only([
            'date',
            'from',
            'to',
            'grade',
            'comment',
        ]);
            
        $data = array_merge($formData, [
            'class' => $student->class,
            'name' => $student->fullname,
            'teacher' => $teacher->fullname,
            'session' => $sessions?->session,
            'term' => $sessions?->term,
            'student_id' => $student->id,
        ]);

        if ($selectedOption) {
            $data['sura'] = $selectedOption->sura;
            }

        if ($to_selectedOption) {
            $data['to_surah'] = $to_selectedOption->sura;
            }
        
        $save = Hadda::where('id', $id)->update($data);
        $hadda = Hadda::where('student_id', $id)->get();
    
        $message = 'Hadda Record Updated Successfully!';

        if (!empty($guard['warnings'])) {
            $message .= ' Warning: '.implode(' ', $guard['warnings']);
        }

        return redirect('studentsHadda/{teacher_id}')->with(['message' => $message]);
        
     }

// Delete Hadda
public function delete($id) {
    $student = Hadda::where('id', $id)->delete();
    return redirect('studentsHadda/{teacher_id}')->with('message', 'Hadda Record Deleted Successfully!');
}

 public function haddaPdf($student_id)
    {

    $sessions = sessions::orderBy('created_at', 'desc')->first();

        $hadda = Hadda::query()
        ->filter(request(['search']))
        ->with(['student'])
        ->where('student_id', $student_id)
        ->where('term', $sessions->term)
        ->where('session', $sessions->session)
        ->orderByHaddaDate('asc')
        ->get();

        $haddaCount = $hadda->count();


        if ($hadda->isEmpty()) {
        return back()->with('error', 'No Hadda records found for the selected term.');
    }

            $gradeMap = [
            'ممتاز'   => 'Excellent',
            'جيدجدا'  => 'Very Good',
            'جيد'     => 'Good',
            'مقبول'   => 'Pass',
        ];

        $hadda->transform(function ($item) use ($gradeMap) {
            $coverage = \App\Support\HaddaCoverageCalculator::analyze($item);
            $item->grade_en = $gradeMap[$item->grade] ?? $item->grade;
            $item->page_count = $coverage['status'] === 'page' ? $coverage['pages'] : null;
            return $item;
        });


    $student = register_student::where('id', $student_id)->first();

    $surahMap = [
    'سورة الفاتحة' => 'Al-Fātiḥah',
    'سورة البقرة' => 'Al-Baqarah',
    'سورة آل عمران' => 'Āl ʿImrān',
    'سورة النساء' => 'An-Nisāʾ',
    'سورة المائدة' => 'Al-Māʾidah',
    'سورة الأنعام' => 'Al-Anʿām',
    'سورة الأعراف' => 'Al-Aʿrāf',
    'سورة الأنفال' => 'Al-Anfāl',
    'سورة التوبة' => 'At-Tawbah',
    'سورة يونس' => 'Yūnus',
    'سورة هود' => 'Hūd',
    'سورة يوسف' => 'Yūsuf',
    'سورة الرعد' => 'Ar-Raʿd',
    'سورة إبراهيم' => 'Ibrāhīm',
    'سورة الحجر' => 'Al-Ḥijr',
    'سورة النحل' => 'An-Naḥl',
    'سورة الإسراء' => 'Al-Isrāʾ',
    'سورة الكهف' => 'Al-Kahf',
    'سورة مريم' => 'Maryam',
    'سورة طه' => 'Ṭā-Hā',
    'سورة الأنبياء' => 'Al-Anbiyāʾ',
    'سورة الحج' => 'Al-Ḥajj',
    'سورة المؤمنون' => 'Al-Muʾminūn',
    'سورة النور' => 'An-Nūr',
    'سورة الفرقان' => 'Al-Furqān',
    'سورة الشعراء' => 'Ash-Shuʿarāʾ',
    'سورة النمل' => 'An-Naml',
    'سورة القصص' => 'Al-Qaṣaṣ',
    'سورة العنكبوت' => 'Al-ʿAnkabūt',
    'سورة الروم' => 'Ar-Rūm',
    'سورة لقمان' => 'Luqmān',
    'سورة السجدة' => 'As-Sajdah',
    'سورة الأحزاب' => 'Al-Aḥzāb',
    'سورة سبإ' => 'Sabaʾ',
    'سورة فاطر' => 'Fāṭir',
    'سورة يس' => 'Yā-Sīn',
    'سورة الصافات' => 'Aṣ-Ṣāffāt',
    'سورة ص' => 'Ṣād',
    'سورة الزمر' => 'Az-Zumar',
    'سورة غافر' => 'Ghāfir',
    'سورة فصلت' => 'Fuṣṣilat',
    'سورة الشورى' => 'Ash-Shūrā',
    'سورة الزخرف' => 'Az-Zukhruf',
    'سورة الدخان' => 'Ad-Dukhān',
    'سورة الجاثية' => 'Al-Jāthiyah',
    'سورة الأحقاف' => 'Al-Aḥqāf',
    'سورة محمد' => 'Muḥammad',
    'سورة الفتح' => 'Al-Fatḥ',
    'سورة الحجرات' => 'Al-Ḥujurāt',
    'سورة ق' => 'Qāf',
    'سورة الذاريات' => 'Adh-Dhāriyāt',
    'سورة الطور' => 'Aṭ-Ṭūr',
    'سورة النجم' => 'An-Najm',
    'سورة القمر' => 'Al-Qamar',
    'سورة الرحمن' => 'Ar-Raḥmān',
    'سورة الواقعة' => 'Al-Wāqiʿah',
    'سورة الحديد' => 'Al-Ḥadīd',
    'سورة المجادلة' => 'Al-Mujādilah',
    'سورة الحشر' => 'Al-Ḥashr',
    'سورة الممتحنة' => 'Al-Mumtaḥanah',
    'سورة الصف' => 'Aṣ-Ṣaff',
    'سورة الجمعة' => 'Al-Jumuʿah',
    'سورة المنافقون' => 'Al-Munāfiqūn',
    'سورة التغابن' => 'At-Taghābun',
    'سورة الطلاق' => 'Aṭ-Ṭalāq',
    'سورة التحريم' => 'At-Taḥrīm',
    'سورة الملك' => 'Al-Mulk',
    'سورة القلم' => 'Al-Qalam',
    'سورة الحاقة' => 'Al-Ḥāqqah',
    'سورة المعارج' => 'Al-Maʿārij',
    'سورة نوح' => 'Nūḥ',
    'سورة الجن' => 'Al-Jinn',
    'سورة المزمل' => 'Al-Muzzammil',
    'سورة المدثر' => 'Al-Muddaththir',
    'سورة القيامة' => 'Al-Qiyāmah',
    'سورة الإنسان' => 'Al-Insān',
    'سورة المرسلات' => 'Al-Mursalāt',
    'سورة النبأ' => 'An-Nabaʾ',
    'سورة النازعات' => 'An-Nāziʿāt',
    'سورة عبس' => 'ʿAbasa',
    'سورة التكوير' => 'At-Takwīr',
    'سورة الانفطار' => 'Al-Infiṭār',
    'سورة المطففين' => 'Al-Muṭaffifīn',
    'سورة الانشقاق' => 'Al-Inshiqāq',
    'سورة البروج' => 'Al-Burūj',
    'سورة الطارق' => 'Aṭ-Ṭāriq',
    'سورة الأعلى' => 'Al-Aʿlā',
    'سورة الغاشية' => 'Al-Ghāshiyah',
    'سورة الفجر' => 'Al-Fajr',
    'سورة البلد' => 'Al-Balad',
    'سورة الشمس' => 'Ash-Shams',
    'سورة الليل' => 'Al-Layl',
    'سورة الضحى' => 'Aḍ-Ḍuḥā',
    'سورة الشرح' => 'Ash-Sharḥ',
    'سورة التين' => 'At-Tīn',
    'سورة العلق' => 'Al-ʿAlaq',
    'سورة القدر' => 'Al-Qadr',
    'سورة البينة' => 'Al-Bayyinah',
    'سورة الزلزلة' => 'Az-Zalzalah',
    'سورة العاديات' => 'Al-ʿĀdiyāt',
    'سورة القارعة' => 'Al-Qāriʿah',
    'سورة التكاثر' => 'At-Takāthur',
    'سورة العصر' => 'Al-ʿAṣr',
    'سورة الهمزة' => 'Al-Humazah',
    'سورة الفيل' => 'Al-Fīl',
    'سورة قريش' => 'Quraysh',
    'سورة الماعون' => 'Al-Māʿūn',
    'سورة الكوثر' => 'Al-Kawthar',
    'سورة الكافرون' => 'Al-Kāfirūn',
    'سورة النصر' => 'An-Naṣr',
    'سورة المسد' => 'Al-Masad',
    'سورة الإخلاص' => 'Al-Ikhlāṣ',
    'سورة الفلق' => 'Al-Falaq',
    'سورة الناس' => 'An-Nās',
];


$hadda->transform(function ($item) use ($surahMap) {
    $item->sura_en = $surahMap[$item->sura] ?? $item->sura;
    $item->to_surah_en = $surahMap[$item->to_surah] ?? $item->to_surah;
    return $item;
});
        $termTotalPages = $hadda->sum(fn ($item) => (int) ($item->page_count ?? 0));
        $rankSummary = HaddaLeaderboardBuilder::studentRankSummary($student, $sessions->session, $sessions->term);

        $pdf = Pdf::loadView('PDFs.hadda_pdf', [
        'student' => $student,
        'hadda'   => $hadda,
        'term'    => $sessions->term,
        'session'    => $sessions->session,
        'haddaCount' => $haddaCount,
        'termTotalPages' => $termTotalPages,
        'rankSummary' => $rankSummary,
    ])->setPaper('a4', 'portrait');

    return $pdf->download(
        $student->fullname . ' - Hadda Record (' . $sessions->term . ').pdf'
    );
    }

    // Download all hadda records for students in a class
 public function classHaddaPdfMerged($class)
{
    $session = sessions::latest('created_at')->first();
    $term = $session->term;
    $sessionName = $session->session;

    // Fetch all students in the class
    $students = register_student::where('class', $class)->currentlyEnrolled()->get();
    if ($students->isEmpty()) {
        return back()->with('error', 'No students found in this class.');
    }

    // Temporary directory for storing individual PDFs
    $tempDir = storage_path('app/temp_pdfs');
    if (!file_exists($tempDir)) {
        mkdir($tempDir, 0755, true);
    }

    $mergedPdfFileName = storage_path('app/' . $term . ' ' . str_replace('/', '_', $sessionName) . ' ' . $class . ' Hadda Records.pdf');
    $mergedPdf = new Fpdi();

    // Maps for grades and surah names
    $gradeMap = [
        'ممتاز' => 'Excellent',
        'جيدجدا' => 'Very Good',
        'جيد' => 'Good',
        'مقبول' => 'Pass',
    ];

    $surahMap = [
    'سورة الفاتحة' => 'Al-Fātiḥah',
    'سورة البقرة' => 'Al-Baqarah',
    'سورة آل عمران' => 'Āl ʿImrān',
    'سورة النساء' => 'An-Nisāʾ',
    'سورة المائدة' => 'Al-Māʾidah',
    'سورة الأنعام' => 'Al-Anʿām',
    'سورة الأعراف' => 'Al-Aʿrāf',
    'سورة الأنفال' => 'Al-Anfāl',
    'سورة التوبة' => 'At-Tawbah',
    'سورة يونس' => 'Yūnus',
    'سورة هود' => 'Hūd',
    'سورة يوسف' => 'Yūsuf',
    'سورة الرعد' => 'Ar-Raʿd',
    'سورة إبراهيم' => 'Ibrāhīm',
    'سورة الحجر' => 'Al-Ḥijr',
    'سورة النحل' => 'An-Naḥl',
    'سورة الإسراء' => 'Al-Isrāʾ',
    'سورة الكهف' => 'Al-Kahf',
    'سورة مريم' => 'Maryam',
    'سورة طه' => 'Ṭā-Hā',
    'سورة الأنبياء' => 'Al-Anbiyāʾ',
    'سورة الحج' => 'Al-Ḥajj',
    'سورة المؤمنون' => 'Al-Muʾminūn',
    'سورة النور' => 'An-Nūr',
    'سورة الفرقان' => 'Al-Furqān',
    'سورة الشعراء' => 'Ash-Shuʿarāʾ',
    'سورة النمل' => 'An-Naml',
    'سورة القصص' => 'Al-Qaṣaṣ',
    'سورة العنكبوت' => 'Al-ʿAnkabūt',
    'سورة الروم' => 'Ar-Rūm',
    'سورة لقمان' => 'Luqmān',
    'سورة السجدة' => 'As-Sajdah',
    'سورة الأحزاب' => 'Al-Aḥzāb',
    'سورة سبإ' => 'Sabaʾ',
    'سورة فاطر' => 'Fāṭir',
    'سورة يس' => 'Yā-Sīn',
    'سورة الصافات' => 'Aṣ-Ṣāffāt',
    'سورة ص' => 'Ṣād',
    'سورة الزمر' => 'Az-Zumar',
    'سورة غافر' => 'Ghāfir',
    'سورة فصلت' => 'Fuṣṣilat',
    'سورة الشورى' => 'Ash-Shūrā',
    'سورة الزخرف' => 'Az-Zukhruf',
    'سورة الدخان' => 'Ad-Dukhān',
    'سورة الجاثية' => 'Al-Jāthiyah',
    'سورة الأحقاف' => 'Al-Aḥqāf',
    'سورة محمد' => 'Muḥammad',
    'سورة الفتح' => 'Al-Fatḥ',
    'سورة الحجرات' => 'Al-Ḥujurāt',
    'سورة ق' => 'Qāf',
    'سورة الذاريات' => 'Adh-Dhāriyāt',
    'سورة الطور' => 'Aṭ-Ṭūr',
    'سورة النجم' => 'An-Najm',
    'سورة القمر' => 'Al-Qamar',
    'سورة الرحمن' => 'Ar-Raḥmān',
    'سورة الواقعة' => 'Al-Wāqiʿah',
    'سورة الحديد' => 'Al-Ḥadīd',
    'سورة المجادلة' => 'Al-Mujādilah',
    'سورة الحشر' => 'Al-Ḥashr',
    'سورة الممتحنة' => 'Al-Mumtaḥanah',
    'سورة الصف' => 'Aṣ-Ṣaff',
    'سورة الجمعة' => 'Al-Jumuʿah',
    'سورة المنافقون' => 'Al-Munāfiqūn',
    'سورة التغابن' => 'At-Taghābun',
    'سورة الطلاق' => 'Aṭ-Ṭalāq',
    'سورة التحريم' => 'At-Taḥrīm',
    'سورة الملك' => 'Al-Mulk',
    'سورة القلم' => 'Al-Qalam',
    'سورة الحاقة' => 'Al-Ḥāqqah',
    'سورة المعارج' => 'Al-Maʿārij',
    'سورة نوح' => 'Nūḥ',
    'سورة الجن' => 'Al-Jinn',
    'سورة المزمل' => 'Al-Muzzammil',
    'سورة المدثر' => 'Al-Muddaththir',
    'سورة القيامة' => 'Al-Qiyāmah',
    'سورة الإنسان' => 'Al-Insān',
    'سورة المرسلات' => 'Al-Mursalāt',
    'سورة النبأ' => 'An-Nabaʾ',
    'سورة النازعات' => 'An-Nāziʿāt',
    'سورة عبس' => 'ʿAbasa',
    'سورة التكوير' => 'At-Takwīr',
    'سورة الانفطار' => 'Al-Infiṭār',
    'سورة المطففين' => 'Al-Muṭaffifīn',
    'سورة الانشقاق' => 'Al-Inshiqāq',
    'سورة البروج' => 'Al-Burūj',
    'سورة الطارق' => 'Aṭ-Ṭāriq',
    'سورة الأعلى' => 'Al-Aʿlā',
    'سورة الغاشية' => 'Al-Ghāshiyah',
    'سورة الفجر' => 'Al-Fajr',
    'سورة البلد' => 'Al-Balad',
    'سورة الشمس' => 'Ash-Shams',
    'سورة الليل' => 'Al-Layl',
    'سورة الضحى' => 'Aḍ-Ḍuḥā',
    'سورة الشرح' => 'Ash-Sharḥ',
    'سورة التين' => 'At-Tīn',
    'سورة العلق' => 'Al-ʿAlaq',
    'سورة القدر' => 'Al-Qadr',
    'سورة البينة' => 'Al-Bayyinah',
    'سورة الزلزلة' => 'Az-Zalzalah',
    'سورة العاديات' => 'Al-ʿĀdiyāt',
    'سورة القارعة' => 'Al-Qāriʿah',
    'سورة التكاثر' => 'At-Takāthur',
    'سورة العصر' => 'Al-ʿAṣr',
    'سورة الهمزة' => 'Al-Humazah',
    'سورة الفيل' => 'Al-Fīl',
    'سورة قريش' => 'Quraysh',
    'سورة الماعون' => 'Al-Māʿūn',
    'سورة الكوثر' => 'Al-Kawthar',
    'سورة الكافرون' => 'Al-Kāfirūn',
    'سورة النصر' => 'An-Naṣr',
    'سورة المسد' => 'Al-Masad',
    'سورة الإخلاص' => 'Al-Ikhlāṣ',
    'سورة الفلق' => 'Al-Falaq',
    'سورة الناس' => 'An-Nās',
];


    foreach ($students as $student) {
        $hadda = Hadda::query()
            ->where('student_id', $student->id)
            ->where('term', $term)
            ->where('session', $sessionName)
            ->orderByHaddaDate('asc')
            ->get();

            $haddaCount = $hadda->count();


        if ($hadda->isEmpty()) continue;

        $hadda->transform(function ($item) use ($gradeMap, $surahMap) {
            $coverage = \App\Support\HaddaCoverageCalculator::analyze($item);
            $item->grade_en = $gradeMap[$item->grade] ?? $item->grade;
            $item->sura_en = $surahMap[$item->sura] ?? $item->sura;
            $item->to_surah_en = $surahMap[$item->to_surah] ?? $item->to_surah;
            $item->page_count = $coverage['status'] === 'page' ? $coverage['pages'] : null;
            return $item;
        });
        $termTotalPages = $hadda->sum(fn ($item) => (int) ($item->page_count ?? 0));
        $rankSummary = HaddaLeaderboardBuilder::studentRankSummary($student, $sessionName, $term);

        $pdfContent = Pdf::loadView('PDFs.hadda_pdf', [
            'student' => $student,
            'hadda' => $hadda,
            'term' => $term,
            'session' => $sessionName,
            'haddaCount' => $haddaCount,
            'termTotalPages' => $termTotalPages,
            'rankSummary' => $rankSummary,
        ])->output();

        // Save temporary PDF for merging
        $tempPdfFile = tempnam(sys_get_temp_dir(), 'hadda_');
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

    // Save and download merged PDF
    $mergedPdf->Output('F', $mergedPdfFileName);

    return response()->download($mergedPdfFileName);
}

private function leaderboardDateRange(string $period, ?string $weekStart): array
{
    if ($period !== 'weekly') {
        return [null, null];
    }

    try {
        $dateFrom = Carbon::parse($weekStart ?: now())->startOfDay();
    } catch (\Exception $exception) {
        $dateFrom = now()->startOfWeek()->startOfDay();
    }

    return [$dateFrom, $dateFrom->copy()->addDays(6)->endOfDay()];
}

}
