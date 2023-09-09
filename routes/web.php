<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HaddaController;
use App\Http\Controllers\Users_controller;
use App\Http\Controllers\ClassesController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SessionsController;
use App\Http\Controllers\StudentsController;
use App\Http\Controllers\TeachersController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GuardiansController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\classesCrudController;
use App\Http\Controllers\CurriculumController;
use App\Http\Controllers\setsController;
use App\Http\Controllers\subjectsController;
use App\Http\Controllers\surasController;
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
// -Sadiq Mustapha Ahmad April, 2023

//Show Home Page
Route::get('/', function () {
    return view('auth.login');
});


//Show Users Registration Form
Route::get('/register', function () {
    return view('auth.register');
});

//Show Dashboard after Authentication
Route::get('/dashboard', [DashboardController::class, 'view'])
->middleware(['auth', 'verified'])->name('dashboard');

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
        // show page with edit form
        Route::get('/sessions/{id}/editform', 'edit')->name('EditSession');
        // update sessions data
        Route::put('/sessions/{id}', 'update');
        });
    
         //CRUD for Hadda Details
    Route::controller(HaddaController::class)->group(function () {
        // Show Hadda Entry Form
        Route::get('/hadda_page/{student_id}/HaddaForm', 'create')->middleware('can:isAssistant');
        // Store Hadda Entry Data in Database
        Route::post('/hadda_page/{student_id}/HaddaForm', 'store')->middleware('can:isAssistant')->name('hadda_page.store');
        // Show Hadda Data in Hadda book page
        Route::get('/hadda_page/{student_id}', 'show')->name('hadda_page.show');
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

        // Delete Set
        Route::delete('/subjects/{id}', 'delete')->name('subject.delete');
            });
    
   