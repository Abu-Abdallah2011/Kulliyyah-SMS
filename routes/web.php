<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentsController;
use App\Http\Controllers\TeachersController;
use App\Http\Controllers\GuardiansController;
use App\Http\Controllers\Users_controller;
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
Route::get('/students_database', 'show')->middleware('can:isAdmin');
// View Single Student Data
Route::get('/students_database/{id}', 'view');
// Show Student Edit Data Form
Route::get('/students_database/{id}/edit_student', 'edit')->middleware('can:isAdmin');
// Update Student
Route::put('/students_database/{id}', 'update')->middleware('can:isAdmin');
// Delete Student
Route::delete('students_database/{id}', 'delete')->middleware('can:isAdmin');
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
    Route::get('/teachers_database/{id}/edit_teacher', 'edit')->middleware('can:isAdmin');
    // Update Teacher
    Route::put('/teachers_database/{id}', 'update')->middleware('can:isAdmin');
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
        Route::get('/users_database/edit_user', 'edit')->middleware('guest');
        // Update user
        Route::put('/users_database/{id}', 'update');
        // Delete user
        Route::delete('users_database/{id}', 'delete');
        });

        // CRUD for Attendance
    // Route::middleware('can:isAssistant')->controller(AttendanceController::class)->group(function () {
    //     // Show Attendance form
    //     Route::get('/attendance', 'create');
    //     // Save Attendance information
    //     Route::post('/attendance', 'store');
    //     // Show Attendance Report
    //     Route::get('/attendance/{id}', 'show')->name('attendance.show');
    //     });  


   