<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ApplyJobController;
use App\Models\Job;
use App\Models\User;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [AdminController::class, 'index']);

// Route untuk login, register && user
Route::controller(AuthController::class)->group(function() {
    Route::get("/login", 'login');
    Route::post("/login", 'doLogin');

    Route::get('/register-company', 'registerCompany');
    Route::post('/register-company', 'doRegisterCompany');

    Route::get('/register-user', 'registerUser');
    Route::post('/register-user', 'doRegisterUser');

    Route::get('/register-user', 'registerUser');
    Route::post('/register-user', 'doRegisterUser');

    Route::post('/logout', 'logout');
});

// Route khusus untuk admin
Route::controller(AdminController::class)->group(function() {
    Route::get('/data-company', 'dataCompany');
    Route::post('/data-company/verify/{company}', 'verify');
    Route::post('/data-company/reject/{company}', 'reject');
});

// =================================================================

// Route khusus company : 
Route::controller(CompanyController::class)->group(function() {
    Route::get('/dashboard-company', 'index');
    Route::get('/lengkapi-profile', 'edit');
    Route::post('/update-profile/{company}', 'update');
});

Route::controller(JobController::class)->group(function() {
    Route::get('/company-lowongan', 'index');
    Route::get('/company-lowongan/create', 'create');
    Route::post('/company-lowongan', 'store');
    Route::get('/company-lowongan/edit/{job}', 'edit');
    Route::post('/company/lowongan/publish/{job}', 'publish');
    Route::post('/company/lowongan/unpublish/{job}', 'unpublish');
    Route::put('/company-lowongan/{job}', 'update');
});

// ==================================================================

// Route khusus user :

Route::get('/', function() {
    return view('Landing.home', [
        'jobs' => Job::with('company')->where('status', 'published')->latest()->get()
    ]);
});

Route::get('/user-profile',     function() {

    return view('User.profile', [
        'user' => Auth::guard('user')->user()
    ]);
});

Route::put('/edit-profile-user/{user}', [UserController::class, 'update']);
Route::get('/user-history', [UserController::class, 'history']);    

Route::get('/lowongan/detail/{job}', [JobController::class, 'show']);
Route::post('/apply-job/{job}', [ApplyJobController::class, 'store']);