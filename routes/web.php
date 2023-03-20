<?php

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
Route::middleware('can:isExecutive')->controller(App\Http\Controllers\StudentsController::class)->group(function () {
// Show Students Registration Form
Route::get('/students_registration_form', 'create');
// Store Students Data in Database
Route::post('/students_registration_form', 'store');
// Show Student Data in Database
Route::get('/students_database', 'show');
// View Single Student Data
Route::get('/students_database/{id}', 'view');
// Show Student Edit Data Form
Route::get('/students_database/{id}/edit_student', 'edit');
// Update Student
Route::put('/students_database/{id}', 'update');
// Delete Student
Route::delete('students_database/{id}', 'delete');
});


//CRUD for Teachers Details
Route::middleware('can:isExecutive')->controller(TeachersController::class)->group(function () {
    // Show Teachers Registration Form
    Route::get('/teachers_reg_form', 'create');
    // Store Teachers Data in Database
   Route::post('/teachers_reg_form', 'store');
    // Show Teacher Data in Database
    Route::get('/teachers_database', 'show');
    // View Single Teacher Data
    Route::get('/teachers_database/{id}', 'view');
    // Edit Teacher Data
    Route::get('/teachers_database/{id}/edit_teacher', 'edit');
    // Update Teacher
    Route::put('/teachers_database/{id}', 'update');
    // Delete Teacher
    Route::delete('teachers_database/{id}', 'delete');
    });

    //CRUD for Guardians Details
    Route::middleware('can:isExecutive')->controller(GuardiansController::class)->group(function () {
        // Show Guardian Registration Form
        Route::get('/guardians_reg_form', 'create');
        // Store Guardian Data in Database
        Route::post('/guardians_reg_form', 'store');
        // Show Guardian Data in Database
        Route::get('/guardians_database', 'show');
        // View Single Guardian Data
        Route::get('/guardians_database/{id}', 'view');
        // Edit Guardian Data
        Route::get('/guardians_database/{id}/edit_guardian', 'edit');
        // Update Guardian
        Route::put('/guardians_database/{id}', 'update');
        // Delete Guardian
        Route::delete('guardians_database/{id}', 'delete');
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
    