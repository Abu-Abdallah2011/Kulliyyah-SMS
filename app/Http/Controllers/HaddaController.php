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
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use setasign\Fpdi\Fpdi;

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
        $surah = surasModel::all();
        $student = register_student::where('id', $student_id)->first();
        return view('Hadda.HaddaForm', ['student' => $student, 'sura' => $sura, 'surah' => $surah]
        );
    }

       //Store Hadda Information
 public function store(HaddaFormRequest $request, $student_id){

    $data = $request->validated();

    $selectedOptionId = $request->input('from_surah');
    $to_selectedOptionId = $request->input('to_surah');
    
    $student = register_student::where('id', $student_id)->first();
    $teacher = register_teacher::where('user_id', Auth::user()->id)->first();
    $sessions = sessions::orderBy('created_at', 'desc')->first();
    $selectedOption = surasModel::find($selectedOptionId);
    $to_selectedOption = surasModel::find($to_selectedOptionId);
    
    $formData = $request->only([
        'date',
        'from',
        'to',
        'grade',
        'comment',
        'score'
    ]);
        
    $data = array_merge($formData, [ 
        'class' => $student->class,
        'name' => $student->fullname,
        'teacher' => $teacher->fullname,
        'session' => $sessions->session,
        'term' => $sessions->term,
        'student_id' => $student->id,
        'sura' => $selectedOption->sura,
        'to_surah' => $to_selectedOption->sura,
    ]);
    
    $save = Hadda::create($data);
    $hadda = Hadda::where('student_id', $student_id)->get();

    return redirect('studentsHadda/{teacher_id}')->with(['message' => 'Hadda Recorded Successfully!']);
    
 }

 // Show Edit Form
public function edit($id){
    $sura = surasModel::all();
    $surah = surasModel::all();
    $hadda = Hadda::find($id);
    return view('Hadda.edit_hadda', compact('hadda', 'sura', 'surah'));
}

      //update Hadda Information
      public function update(HaddaFormRequest $request, $id){

        $data = $request->validated();
        $selectedOptionId = $request->input('from_surah');
        $to_selectedOptionId = $request->input('to_surah');
        
        $entry = Hadda::find($id);
        $student = register_student::where('id', $entry->student_id)->first();

        $teacher = register_teacher::where('user_id', Auth::user()->id)->first();
        $sessions = sessions::orderBy('created_at', 'desc')->first();

        $selectedOption = surasModel::find($selectedOptionId);
        $to_selectedOption = surasModel::find($to_selectedOptionId);
        
        $formData = $request->only([
            'date',
            'from',
            'to',
            'grade',
            'comment',
            'score'
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

        if ($to_selectedOption) {
            $data['to_surah'] = $to_selectedOption->sura;
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

 public function haddaPdf($student_id)
    {

    $sessions = sessions::orderBy('created_at', 'desc')->first();

        $hadda = Hadda::latest()
        ->filter(request(['search']))
        ->with(['student'])
        ->where('student_id', $student_id)
        ->where('term', $sessions->term)
        ->where('session', $sessions->session)
        ->orderBy('date', 'asc')
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
            $item->grade_en = $gradeMap[$item->grade] ?? $item->grade;
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

        $pdf = Pdf::loadView('PDFs.hadda_pdf', [
        'student' => $student,
        'hadda'   => $hadda,
        'term'    => $sessions->term,
        'session'    => $sessions->session,
        'haddaCount' => $haddaCount,
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
    $students = register_student::where('class', $class)->get();
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
        $hadda = Hadda::latest()
            ->where('student_id', $student->id)
            ->where('term', $term)
            ->where('session', $sessionName)
            ->orderBy('date', 'asc')
            ->get();

            $haddaCount = $hadda->count();


        if ($hadda->isEmpty()) continue;

        $hadda->transform(function ($item) use ($gradeMap, $surahMap) {
            $item->grade_en = $gradeMap[$item->grade] ?? $item->grade;
            $item->sura_en = $surahMap[$item->sura] ?? $item->sura;
            $item->to_surah_en = $surahMap[$item->to_surah] ?? $item->to_surah;
            return $item;
        });

        $pdfContent = Pdf::loadView('PDFs.hadda_pdf', [
            'student' => $student,
            'hadda' => $hadda,
            'term' => $term,
            'session' => $sessionName,
            'haddaCount' => $haddaCount,
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


}
