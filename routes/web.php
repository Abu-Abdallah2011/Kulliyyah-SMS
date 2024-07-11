<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PDFsController;
use App\Http\Controllers\setsController;
use App\Http\Controllers\ExamsController;
use App\Http\Controllers\HaddaController;
use App\Http\Controllers\surasController;
use App\Http\Controllers\Users_controller;
use App\Http\Controllers\ClassesController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SessionsController;
use App\Http\Controllers\StudentsController;
use App\Http\Controllers\subjectsController;
use App\Http\Controllers\TeachersController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GuardiansController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\CurriculumController;
use App\Http\Controllers\SchoolFeesController;
use App\Http\Controllers\classesCrudController;
use App\Http\Controllers\teachersAttendanceController;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


// THIS APPLICATION WAS LAUNCHED IN APRIL 2023, BUT I CAN'T REMEMBER THE PRECISE DATE AS WE WERE SO
// BUSY TRYING TO DEPLOY THEN TESTS THEN CORRECTIONS AND ADJUSTMENTS AND I FORGOT TO NOTE THE PRECISE DATE
// BUT I WILL TRY TO RETRACE MY STEPS TO SEE IF I CAN FIND OUT AND IF I DO I WILL NOTE IT DOWN HERE IN SHAA ALLAAH.
// -Sadiq Mustapha Ahmad. April, 2023.
// THE LANDING PAGE WAS LAUNCHED ON 16/05/2024 AT AROUND 03:00PM.

//Show Home Page
Route::get('/', [LandingController::class, 'index']);

//Go To Login Page
Route::get('/login', function () {
    return view('login');
});

//Show Users Registration Form
Route::get('/register', function () {
    return view('auth.register');
});

//Show Dashboard after Authentication
Route::get('/dashboard', [DashboardController::class, 'view'])
->middleware(['auth', 'verified'])->name('dashboard');

 //Go To Settings Page
 Route::get('settings', function () {
    return view('settings');
})->middleware('can:isExecutive');

Route::controller(App\Http\Controllers\DashboardController::class)->group(function () {

//Show Dashboard after Authentication
// Route::get('/dashboard', 'view')
// ->middleware(['auth', 'verified'])->name('dashboard');

//Show Dashboard of selected user/Guardian
Route::get('/dashboard/guardians/{guardian_id}', 'show')
->middleware(['can:isExecutive'])->name('dashboard.show');
});

//Profile Manipulation
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


require __DIR__.'/auth.php';



    //CRUD for Students Details
    Route::controller(App\Http\Controllers\StudentsController::class)->group(function () {
        // Show Students Registration Form
        Route::get('/students_registration_form', 'create')->middleware('can:isAdmin');
        // Store Students Data in Database
        Route::post('/students_registration_form', 'store')->middleware('can:isAdmin');
        // Show Student Data in Database
        Route::get('/students_database', 'show')->middleware('can:isExecutive');
        // View Single Student Data
        Route::get('/students_database/{id}', 'view');
        // Show Student Edit Data Form
        Route::get('/students_database/{id}/edit_student', 'edit')->middleware('can:isAssistant');
        // Update Student
        Route::put('/students_database/{id}', 'update')->middleware('can:isAssistant');
        // Delete Student
        Route::delete('students_database/{id}', 'delete')->middleware('can:isAdmin');
        // Show Graduate Data in Database
        Route::get('/graduates_database', 'showGraduates')->middleware('can:isExecutive');
        });


    //CRUD for Teachers Details
    Route::controller(TeachersController::class)->group(function () {
        // Show Teachers Registration Form
        Route::get('/teachers_reg_form', 'create')->middleware('can:isAdmin');
        // Store Teachers Data in Database
        Route::post('/teachers_reg_form', 'store')->middleware('can:isAdmin');
        // Show Teacher Data in Database
        Route::get('/teachers_database', 'show')->middleware('can:isAdmin');
        // View Single Teacher Data
        Route::get('/teachers_database/{id}', 'view')->middleware('can:isAssistant');
        // Edit Teacher Data
        Route::get('/teachers_database/{id}/edit_teacher', 'edit')->middleware('can:isAssistant');
        // Update Teacher
        Route::put('/teachers_database/{id}', 'update')->middleware('can:isAssistant');
        // Delete Teacher
        Route::delete('teachers_database/{id}', 'delete')->middleware('can:isAdmin');
        });

    //CRUD for Guardians Details
    Route::controller(GuardiansController::class)->group(function () {
        // Show Guardian Registration Form
        Route::get('/guardians_reg_form', 'create')->middleware('can:isExecutive');
        // Store Guardian Data in Database
        Route::post('/guardians_reg_form', 'store')->middleware('can:isExecutive');
        // Show Guardian Data in Database
        Route::get('/guardians_database', 'show')->middleware('can:isExecutive');
        // View Single Guardian Data
        Route::get('/guardians_database/{id}', 'view');
        // Edit Guardian Data
        Route::get('/guardians_database/{id}/edit_guardian', 'edit')->middleware('can:isExecutive');
        // Update Guardian
        Route::put('/guardians_database/{id}', 'update')->middleware('can:isExecutive');
        // Delete Guardian
        Route::delete('guardians_database/{id}', 'delete')->middleware('can:isExecutive');
        });


        //CRUD for Users Details
    Route::middleware('can:isAdmin')->controller(Users_controller::class)->group(function () {
        // Show user Data in Database
        Route::get('/users_database', 'show');
        // Edit user Data
        Route::get('/users_database/{id}/edit_user', 'edit');
        // Update user
        Route::put('/users_database/{id}', 'update');
        // Delete user
        Route::delete('users_database/{id}', 'delete');
        });


    // Route for Classes
    Route::controller(ClassesController::class)->group(function()
        {
            // Show Classes Page
        Route::get('/classes', 'index')->middleware('can:isExecutive');

        // Show Any selected Teachers' Dashboard
        Route::get('/dashboard/classes/{teacher_id}', 'display')->middleware('can:isExecutive')->name('dashboard.display');
        
        //Take User to Class Teachers Page
        Route::get('/class_teachers', 'classTeacher');

        //Take User to Class Students Page
        Route::get('/class_students', 'classStudent');

        //Take User to Class Students Hadda Page
        Route::get('/studentsHadda', 'studentsHadda');

        //Take Admin to Selected Class Teachers Page
        Route::get('/dashboard/class_teachers/{teacher_id}', 'selectedClassTeacher')->middleware('can:isExecutive');

        //Take Admin to Selected Class Students Page
        Route::get('/dashboard/class_students/{teacher_id}', 'selectedClassStudent')->middleware('can:isExecutive');

        //Take Admin to Selected Class Students Page for Hadda
        Route::get('/dashboard/studentsHadda/{teacher_id}', 'selectedStudentsHadda')->middleware('can:isExecutive');
        });

         //CRUD for Curriculum Details
    Route::controller(CurriculumController::class)->group(function () {
        // Show Curriculum Registration Form
        Route::get('/curriculum_form', 'create')->middleware('can:isAssistant');
        // Store Curriculum Data in Database
        Route::post('/curriculum_form', 'store')->middleware('can:isAssistant');
        // Show Curriculum Data in Database
        Route::get('/curriculum_scale', 'show')->middleware('can:isAssistant');
        // Edit Curriculum Data
        Route::get('/curriculum_scale/{id}/edit_curriculum', 'edit')->middleware('can:isAssistant');
        // Update Curriculum
        Route::put('/curriculum_scale/{id}', 'update')->middleware('can:isAssistant');
        // Delete Guardian
        Route::delete('curriculum_scale/{id}', 'delete')->middleware('can:isAssistant');
        // Show Curriculum Scale of any selected class to Executive
        Route::get('curriculum_scale/{teacher_id}', 'display')->middleware('can:isExecutive');
        // Show curriculum Scale of student to whoever clicks the button
        Route::get('curriculum_scale/guardianview/{student_id}', 'displayForGuardian');
        });

    // SESSIONS ROUTES
    Route::middleware('can:isExecutive')->controller(SessionsController::class)->group(function () {
        // Show Sessions form
        Route::get('/sessionsForm', 'create');
        // Save Sessions information
        Route::post('/sessionsForm', 'store')->name('sessions.save');
        // Show Sessions Database
        Route::get('/sessions_database', 'show')->name('sessions.show');
        // show page with edit form
        Route::get('/sessions/{id}/editform', 'edit')->name('EditSession');
        // update sessions data
        Route::put('/sessions/{id}', 'update');
        // Delete Class
        Route::delete('/session/{id}', 'delete')->name('session.delete');
        });
    
         //CRUD for Hadda Details
    Route::controller(HaddaController::class)->group(function () {
        // Show Hadda Entry Form
        Route::get('/hadda_page/{student_id}/HaddaForm', 'create')->middleware('can:isAssistant');
        // Store Hadda Entry Data in Database
        Route::post('/hadda_page/{student_id}/HaddaForm', 'store')->middleware('can:isAssistant')->name('hadda_page.store');
        // Show Hadda Data in Hadda book page
        Route::get('/hadda_page/{student_id}', 'show')->name('hadda_page.show');
        // Show Hadda Status
        Route::get('/studentsHadda/{teacher_id}', 'showStatus')->name('hadda_status.show');
        // Edit Curriculum Data
        Route::get('/hadda_page/{id}/edit_hadda', 'edit')->middleware('can:isAssistant');
        // Update Curriculum
        Route::put('/hadda_page/{id}', 'update')->middleware('can:isAssistant');
        // Delete Hadda
        Route::delete('hadda_page/{id}', 'delete')->middleware('can:isAssistant')->name('hadda.delete');
        });


    // CRUD for Attendance
    Route::middleware('can:isAssistant')->controller(AttendanceController::class)->group(function () {
            // Show Attendance form
            Route::get('/attendance', 'create')->name('attendance.create');

            // Save Attendance information
            Route::post('/attendance', 'store');

            // Show Attendance Report
            Route::get('/attendance/show', 'show')->name('attendance.show');

            // Show Attendance Edit Form
            Route::get('/attendance/{date}/edit_attendance', 'edit');

            // Update Attendance
            Route::put('/attendance/{date}', 'update');

            // Delete Attendance
            Route::delete('/attendance/{date}', 'delete');

            // Show Attendance form
            Route::get('dashboard/attendance/{teacher_id}', 'selectedCreate');

            // Show Attendance Record for Previous Terms
            Route::get('attendanceForPreviousTerms/attendance/{teacher_id}', 'selectedCreate');
        }); 
            // Show Attendance Page to Guardian
            Route::get('/attendance/guardian_view/{id}', [AttendanceController::class, 'attendanceGuardian']); 

            // CRUD for Sets
    Route::middleware('can:isExecutive')->controller(setsController::class)->group(function () {
       
        // Show Sets form
        Route::get('/setsForm', 'create');

        // Save Set information
        Route::post('/setsForm', 'store')->name('sets.save');

        // Show Sets Database
        Route::get('/sets', 'show')->name('sets.show');

        // Edit Sets Data
        Route::get('/sets/{id}/setsEdit', 'edit');

        // Update Set
        Route::put('/sets/{id}', 'update');

        // Delete Set
        Route::delete('/sets/{id}', 'delete')->name('set.delete');
         });

    Route::middleware('can:isExecutive')->controller(classesCrudController::class)->group(function () {
       
        // Show Classes form
        Route::get('/classesForm', 'create');

        // Save Class information
        Route::post('/classesForm', 'store')->name('classes.save');

        // Show Classes Database
        Route::get('/classes_database', 'show')->name('classes.show');

        // Edit Class Data
        Route::get('/class/{id}/classEdit', 'edit');

        // Update Class
        Route::put('/class/{id}', 'update');

        // Delete Class
        Route::delete('/class/{id}', 'delete')->name('class.delete');
            });

    // Surahs Crud
    Route::middleware('can:isExecutive')->controller(surasController::class)->group(function () {

        // Show Surah form
        Route::get('/surasForm', 'create');

        // Save Surah information
        Route::post('/surasForm', 'store')->name('suras.save');

        // Show Surah Database
        Route::get('/suras_database', 'show')->name('suras.show');

        // Edit Surah Data
        Route::get('/sura/{id}/suraEdit', 'edit');

        // Update Surah
        Route::put('/sura/{id}', 'update');

        // Delete Surah
        Route::delete('/sura/{id}', 'delete')->name('sura.delete');
        });

    Route::middleware('can:isExecutive')->controller(subjectsController::class)->group(function () {
       
        // Show Subjects form
        Route::get('/subjectsForm', 'create');

        // Save Subject information
        Route::post('/subjectsForm', 'store')->name('subjects.save');

        // Show Subject Database
        Route::get('/subjects', 'show')->name('subjects.show');

        // Edit Subject Data
        Route::get('/subjects/{id}/subjectsEdit', 'edit');

        // Update Subject
        Route::put('/subjects/{id}', 'update');

        // Delete Subject
        Route::delete('/subjects/{id}', 'delete')->name('subject.delete');
        });
    
    Route::controller(ExamsController::class)->group(function () {
       
        // Show Exams for Executive
        Route::get('dashboard/exams/{teacher_id}', 'selectedTeacherExams');

        // Show Exam Database
        Route::get('/exams/show', 'show')->middleware('can:isAssistant')->name('exams.show');

        // Edit Exam Data
        Route::get('/exams/{id}/examsEdit', 'edit')->middleware('can:isAssistant');

        // Update Exam
        Route::put('/exams/{id}', 'update')->middleware('can:isAssistant');

        // Delete Exam
        Route::delete('/exams/{id}', 'delete')->name('exams.delete')->middleware('can:isAssistant');

        // Select Subjects
        Route::get('/selectSubjects', 'selectSubjects')->middleware('can:isAssistant');

        // Store Subjects
        Route::post('/subjectsCreate', 'subjectsCreate')->middleware('can:isAssistant');

        // Go To Comment
        Route::get('/exams/commentEditView', 'examComment')->middleware('can:isAssistant');

        // Update Comment
        Route::put('/exams/{id}/comment_update', 'updateComment')->middleware('can:isAssistant');

        // Go To Prevous Terms Exams Records
        Route::get('/exams/{class}/examsForPreviousTerms', 'examsForPreviousTerms');

        // Go To Prevous Terms Cleansheet
        Route::get('/previousExams/{term}/{session}', 'PreviousTermsCleansheet')->middleware('can:isAssistant');

        // Go To Prevous Terms Cleansheet
        Route::get('/ExamsRecords/{id}/PreviousTerms', 'PreviousTermsExamsForParents');

        });

        // CRUD for Attendance
    Route::controller(teachersAttendanceController::class)->group(function () {
        // Show Attendance form
        Route::get('/teachersAttendance', 'create')->name('teachersAttendance.create')->middleware('can:isExecutive');

        // Save Attendance information
        Route::post('/teachersAttendance', 'store')->middleware('can:isExecutive');

        // Show Attendance Report
        Route::get('/teachersAttendance/show', 'show')->name('teachersAttendance.show');

        // Show Attendance Edit Form
        Route::get('/teachersAttendance/{date}/edit_attendance', 'edit');

        // Update Attendance
        Route::put('/teachersAttendance/{date}', 'update')->middleware('can:isExecutive');

        // Delete Attendance
        Route::delete('/teachersAttendance/{date}', 'delete')->middleware('can:isExecutive');
        });

            // CRUD for Attendance
    Route::controller(SchoolFeesController::class)->group(function () {
        // Show Fees form
        Route::get('/fees_record/{studentId}', 'create')->name('fees.create')->middleware('can:isFinance');

        // Save Fees information
        Route::post('/fees_record/{studentId}', 'store')->middleware('can:isFinance');

        // Show Fees Report
        Route::get('/fees_database/show', 'show')->name('fees.show')->middleware('can:isFinance');

        // Show Previous Sessions Fees Record
        Route::get('/fees_record/{studentId}/PreviousSessions', 'showPreviousSessions');

        // Show Fees Edit Form
        Route::get('/fees_record/{studentId}/{term}/{session}/edit_fees', 'edit')->middleware('can:isFinance');

        // Update Fees Record
        Route::put('/fees_record/{studentId}/{term}/{session}/update_fees', 'update')->middleware('can:isFinance');

        // Delete Fees Record
        Route::delete('/fees_record/{studentId}/{term}/{session}/delete_fees', 'delete')->middleware('can:isFinance');

        // Go To Reciept
        Route::get('/reciept/{studentId}/{term}/{session}', 'showStudentRecieptForTerm');
    }); 

    // DOWNLOADING PDFS
    Route::controller(PDFsController::class)->group(function () {
        // Go To Report Sheet
        Route::get('/reportSheet/{id}', 'reportSheet');

        // DOWNLOAD ALL STUDENTS REPORT SHEET FOR A PARTICULAR TERM
        Route::get('/download-all-reportsheets', 'downloadAllReportSheets')->name('download.all.reportsheets')->middleware('can:isExecutive');

        // DOWNLOAD CLEAN SHEET FOR A CLASS
        Route::get('/downloadCleanSheets', 'cleanSheets')->name('downloadCleanSheets')->middleware('can:isAssistant');

        // DOWNLOAD REPORT SHEET FOR A CLASS
        Route::get('/downloadReportSheets', 'reportSheets')->name('downloadReportSheets')->middleware('can:isAssistant');

        // DOWNLOAD ALL CLEAN SHEETS FOR A PARTICULAR TERM
        Route::get('/download-all-cleansheets', 'downloadAllCleanSheets')->name('download.all.cleansheets')->middleware('can:isExecutive');

        // Download Report Sheet For Guardians
        Route::get('/reportSheet/{id}/{term}/{session}', 'reportSheetForGuardians');
    });
   