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
    'الفاتحة' => 'Al-Fātiḥah',
    'البقرة' => 'Al-Baqarah',
    'آل عمران' => 'Āl ʿImrān',
    'النساء' => 'An-Nisāʾ',
    'المائدة' => 'Al-Māʾidah',
    'الأنعام' => 'Al-Anʿām',
    'الأعراف' => 'Al-Aʿrāf',
    'الأنفال' => 'Al-Anfāl',
    'التوبة' => 'At-Tawbah',
    'يونس' => 'Yūnus',
    'هود' => 'Hūd',
    'يوسف' => 'Yūsuf',
    'الرعد' => 'Ar-Raʿd',
    'إبراهيم' => 'Ibrāhīm',
    'الحجر' => 'Al-Ḥijr',
    'النحل' => 'An-Naḥl',
    'الإسراء' => 'Al-Isrāʾ',
    'الكهف' => 'Al-Kahf',
    'مريم' => 'Maryam',
    'طه' => 'Ṭā-Hā',
    'الأنبياء' => 'Al-Anbiyāʾ',
    'الحج' => 'Al-Ḥajj',
    'المؤمنون' => 'Al-Muʾminūn',
    'النور' => 'An-Nūr',
    'الفرقان' => 'Al-Furqān',
    'الشعراء' => 'Ash-Shuʿarāʾ',
    'النمل' => 'An-Naml',
    'القصص' => 'Al-Qaṣaṣ',
    'العنكبوت' => 'Al-ʿAnkabūt',
    'الروم' => 'Ar-Rūm',
    'لقمان' => 'Luqmān',
    'السجدة' => 'As-Sajdah',
    'الأحزاب' => 'Al-Aḥzāb',
    'سبإ' => 'Sabaʾ',
    'فاطر' => 'Fāṭir',
    'يس' => 'Yā-Sīn',
    'الصافات' => 'Aṣ-Ṣāffāt',
    'ص' => 'Ṣād',
    'الزمر' => 'Az-Zumar',
    'غافر' => 'Ghāfir',
    'فصلت' => 'Fuṣṣilat',
    'الشورى' => 'Ash-Shūrā',
    'الزخرف' => 'Az-Zukhruf',
    'الدخان' => 'Ad-Dukhān',
    'الجاثية' => 'Al-Jāthiyah',
    'الأحقاف' => 'Al-Aḥqāf',
    'محمد' => 'Muḥammad',
    'الفتح' => 'Al-Fatḥ',
    'الحجرات' => 'Al-Ḥujurāt',
    'ق' => 'Qāf',
    'الذاريات' => 'Adh-Dhāriyāt',
    'الطور' => 'Aṭ-Ṭūr',
    'النجم' => 'An-Najm',
    'القمر' => 'Al-Qamar',
    'الرحمن' => 'Ar-Raḥmān',
    'الواقعة' => 'Al-Wāqiʿah',
    'الحديد' => 'Al-Ḥadīd',
    'المجادلة' => 'Al-Mujādilah',
    'الحشر' => 'Al-Ḥashr',
    'الممتحنة' => 'Al-Mumtaḥanah',
    'الصف' => 'Aṣ-Ṣaff',
    'الجمعة' => 'Al-Jumuʿah',
    'المنافقون' => 'Al-Munāfiqūn',
    'التغابن' => 'At-Taghābun',
    'الطلاق' => 'Aṭ-Ṭalāq',
    'التحريم' => 'At-Taḥrīm',
    'الملك' => 'Al-Mulk',
    'القلم' => 'Al-Qalam',
    'الحاقة' => 'Al-Ḥāqqah',
    'المعارج' => 'Al-Maʿārij',
    'نوح' => 'Nūḥ',
    'الجن' => 'Al-Jinn',
    'المزمل' => 'Al-Muzzammil',
    'المدثر' => 'Al-Muddaththir',
    'القيامة' => 'Al-Qiyāmah',
    'الإنسان' => 'Al-Insān',
    'المرسلات' => 'Al-Mursalāt',
    'النبأ' => 'An-Nabaʾ',
    'النازعات' => 'An-Nāziʿāt',
    'عبس' => 'ʿAbasa',
    'التكوير' => 'At-Takwīr',
    'الانفطار' => 'Al-Infiṭār',
    'المطففين' => 'Al-Muṭaffifīn',
    'الانشقاق' => 'Al-Inshiqāq',
    'البروج' => 'Al-Burūj',
    'الطارق' => 'Aṭ-Ṭāriq',
    'الأعلى' => 'Al-Aʿlā',
    'الغاشية' => 'Al-Ghāshiyah',
    'الفجر' => 'Al-Fajr',
    'البلد' => 'Al-Balad',
    'الشمس' => 'Ash-Shams',
    'الليل' => 'Al-Layl',
    'الضحى' => 'Aḍ-Ḍuḥā',
    'الشرح' => 'Ash-Sharḥ',
    'التين' => 'At-Tīn',
    'العلق' => 'Al-ʿAlaq',
    'القدر' => 'Al-Qadr',
    'البينة' => 'Al-Bayyinah',
    'الزلزلة' => 'Az-Zalzalah',
    'العاديات' => 'Al-ʿĀdiyāt',
    'القارعة' => 'Al-Qāriʿah',
    'التكاثر' => 'At-Takāthur',
    'العصر' => 'Al-ʿAṣr',
    'الهمزة' => 'Al-Humazah',
    'الفيل' => 'Al-Fīl',
    'قريش' => 'Quraysh',
    'الماعون' => 'Al-Māʿūn',
    'الكوثر' => 'Al-Kawthar',
    'الكافرون' => 'Al-Kāfirūn',
    'النصر' => 'An-Naṣr',
    'المسد' => 'Al-Masad',
    'الإخلاص' => 'Al-Ikhlāṣ',
    'الفلق' => 'Al-Falaq',
    'الناس' => 'An-Nās',
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
    'الفاتحة' => 'Al-Fātiḥah',
    'البقرة' => 'Al-Baqarah',
    'آل عمران' => 'Āl ʿImrān',
    'النساء' => 'An-Nisāʾ',
    'المائدة' => 'Al-Māʾidah',
    'الأنعام' => 'Al-Anʿām',
    'الأعراف' => 'Al-Aʿrāf',
    'الأنفال' => 'Al-Anfāl',
    'التوبة' => 'At-Tawbah',
    'يونس' => 'Yūnus',
    'هود' => 'Hūd',
    'يوسف' => 'Yūsuf',
    'الرعد' => 'Ar-Raʿd',
    'إبراهيم' => 'Ibrāhīm',
    'الحجر' => 'Al-Ḥijr',
    'النحل' => 'An-Naḥl',
    'الإسراء' => 'Al-Isrāʾ',
    'الكهف' => 'Al-Kahf',
    'مريم' => 'Maryam',
    'طه' => 'Ṭā-Hā',
    'الأنبياء' => 'Al-Anbiyāʾ',
    'الحج' => 'Al-Ḥajj',
    'المؤمنون' => 'Al-Muʾminūn',
    'النور' => 'An-Nūr',
    'الفرقان' => 'Al-Furqān',
    'الشعراء' => 'Ash-Shuʿarāʾ',
    'النمل' => 'An-Naml',
    'القصص' => 'Al-Qaṣaṣ',
    'العنكبوت' => 'Al-ʿAnkabūt',
    'الروم' => 'Ar-Rūm',
    'لقمان' => 'Luqmān',
    'السجدة' => 'As-Sajdah',
    'الأحزاب' => 'Al-Aḥzāb',
    'سبإ' => 'Sabaʾ',
    'فاطر' => 'Fāṭir',
    'يس' => 'Yā-Sīn',
    'الصافات' => 'Aṣ-Ṣāffāt',
    'ص' => 'Ṣād',
    'الزمر' => 'Az-Zumar',
    'غافر' => 'Ghāfir',
    'فصلت' => 'Fuṣṣilat',
    'الشورى' => 'Ash-Shūrā',
    'الزخرف' => 'Az-Zukhruf',
    'الدخان' => 'Ad-Dukhān',
    'الجاثية' => 'Al-Jāthiyah',
    'الأحقاف' => 'Al-Aḥqāf',
    'محمد' => 'Muḥammad',
    'الفتح' => 'Al-Fatḥ',
    'الحجرات' => 'Al-Ḥujurāt',
    'ق' => 'Qāf',
    'الذاريات' => 'Adh-Dhāriyāt',
    'الطور' => 'Aṭ-Ṭūr',
    'النجم' => 'An-Najm',
    'القمر' => 'Al-Qamar',
    'الرحمن' => 'Ar-Raḥmān',
    'الواقعة' => 'Al-Wāqiʿah',
    'الحديد' => 'Al-Ḥadīd',
    'المجادلة' => 'Al-Mujādilah',
    'الحشر' => 'Al-Ḥashr',
    'الممتحنة' => 'Al-Mumtaḥanah',
    'الصف' => 'Aṣ-Ṣaff',
    'الجمعة' => 'Al-Jumuʿah',
    'المنافقون' => 'Al-Munāfiqūn',
    'التغابن' => 'At-Taghābun',
    'الطلاق' => 'Aṭ-Ṭalāq',
    'التحريم' => 'At-Taḥrīm',
    'الملك' => 'Al-Mulk',
    'القلم' => 'Al-Qalam',
    'الحاقة' => 'Al-Ḥāqqah',
    'المعارج' => 'Al-Maʿārij',
    'نوح' => 'Nūḥ',
    'الجن' => 'Al-Jinn',
    'المزمل' => 'Al-Muzzammil',
    'المدثر' => 'Al-Muddaththir',
    'القيامة' => 'Al-Qiyāmah',
    'الإنسان' => 'Al-Insān',
    'المرسلات' => 'Al-Mursalāt',
    'النبأ' => 'An-Nabaʾ',
    'النازعات' => 'An-Nāziʿāt',
    'عبس' => 'ʿAbasa',
    'التكوير' => 'At-Takwīr',
    'الانفطار' => 'Al-Infiṭār',
    'المطففين' => 'Al-Muṭaffifīn',
    'الانشقاق' => 'Al-Inshiqāq',
    'البروج' => 'Al-Burūj',
    'الطارق' => 'Aṭ-Ṭāriq',
    'الأعلى' => 'Al-Aʿlā',
    'الغاشية' => 'Al-Ghāshiyah',
    'الفجر' => 'Al-Fajr',
    'البلد' => 'Al-Balad',
    'الشمس' => 'Ash-Shams',
    'الليل' => 'Al-Layl',
    'الضحى' => 'Aḍ-Ḍuḥā',
    'الشرح' => 'Ash-Sharḥ',
    'التين' => 'At-Tīn',
    'العلق' => 'Al-ʿAlaq',
    'القدر' => 'Al-Qadr',
    'البينة' => 'Al-Bayyinah',
    'الزلزلة' => 'Az-Zalzalah',
    'العاديات' => 'Al-ʿĀdiyāt',
    'القارعة' => 'Al-Qāriʿah',
    'التكاثر' => 'At-Takāthur',
    'العصر' => 'Al-ʿAṣr',
    'الهمزة' => 'Al-Humazah',
    'الفيل' => 'Al-Fīl',
    'قريش' => 'Quraysh',
    'الماعون' => 'Al-Māʿūn',
    'الكوثر' => 'Al-Kawthar',
    'الكافرون' => 'Al-Kāfirūn',
    'النصر' => 'An-Naṣr',
    'المسد' => 'Al-Masad',
    'الإخلاص' => 'Al-Ikhlāṣ',
    'الفلق' => 'Al-Falaq',
    'الناس' => 'An-Nās',
    ];

    foreach ($students as $student) {
        $hadda = Hadda::latest()
            ->where('student_id', $student->id)
            ->where('term', $term)
            ->where('session', $sessionName)
            ->orderBy('date', 'asc')
            ->get();

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
