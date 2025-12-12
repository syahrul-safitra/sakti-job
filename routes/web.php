<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ApplyJobController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\UserController;
use App\Models\Job;
use App\Models\User;
use Illuminate\Http\Request;
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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/dashboard', [AdminController::class, 'index'])->middleware('isAdmin');

// Route untuk login, register && user
Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'login')->middleware('isGuest');
    Route::post('/login', 'doLogin')->middleware('isGuest');

    Route::get('/register-company', 'registerCompany')->middleware('isGuest');
    Route::post('/register-company', 'doRegisterCompany')->middleware('isGuest');

    Route::get('/register-user', 'registerUser')->middleware('isGuest');
    Route::post('/register-user', 'doRegisterUser')->middleware('isGuest');

    Route::get('/register-user', 'registerUser')->middleware('isGuest');
    Route::post('/register-user', 'doRegisterUser')->middleware('isGuest');

    Route::post('/logout', 'logout');
});

// Route khusus untuk admin
Route::controller(AdminController::class)->group(function () {
    Route::get('/data-company', 'dataCompany')->middleware('isAdmin');
    Route::post('/data-company/verify/{company}', 'verify')->middleware('isAdmin');
    Route::post('/data-company/reject/{company}', 'reject')->middleware('isAdmin');
    Route::get('/lowongan-admin', 'lowonganAdmin')->middleware('isAdmin');
});

Route::get('/users', [AdminController::class, 'dataUser'])->middleware('isAdmin');
Route::get('/detail-pelamar-admin/{user}', [AdminController::class, 'showUser'])->middleware('isAdmin');

// =================================================================

// Route khusus company :
Route::controller(CompanyController::class)->group(function () {
    Route::get('/dashboard-company', 'index')->middleware('isCompany');
    Route::get('/lengkapi-profile', 'edit')->middleware('isCompany');
    Route::post('/update-profile/{company}', 'update')->middleware('isCompany');
});

Route::controller(JobController::class)->group(function () {
    Route::get('/company-lowongan', 'index')->middleware('isCompany');
    Route::get('/company-lowongan/create', 'create')->middleware('isCompany');
    Route::post('/company-lowongan', 'store')->middleware('isCompany');
    Route::get('/company-lowongan/edit/{job}', 'edit')->middleware('isCompany');
    Route::post('/company/lowongan/publish/{job}', 'publish')->middleware('isCompany');
    Route::post('/company/lowongan/unpublish/{job}', 'unpublish')->middleware('isCompany');
    Route::put('/company-lowongan/{job}', 'update')->middleware('isCompany');
    Route::delete('/company-lowongan/{job}', 'destroy')->middleware('isCompany');
});

Route::get('/company-applyjob', [ApplyJobController::class, 'indexCompany'])->middleware('isCompany');
Route::post('update-status-pelamar/{apply}', [ApplyJobController::class, 'updateStatusPelamar'])->middleware('isCompany');

Route::get('/detail-pelamar/{apply}', [ApplyJobController::class, 'show'])->middleware('isCompany');

// ==================================================================

// Route khusus user :

Route::get('/', function (Request $request) {

    $keyword = $request->input('keyword');

    if ($keyword) {
        $jobs = Job::with('company')
            ->where('status', 'published')
            ->where(function ($query) use ($keyword) {
                $query->where('title', 'like', '%'.$keyword.'%');
            })
            ->latest()
            ->limit(10)
            ->get();
    } else {
        $jobs = Job::with('company')
            ->where('status', 'published')
            ->latest()
            ->limit(10)
            ->get();
    }

    return view('Landing.home', [
        'jobs' => $jobs,

        'allJobsCount' => Job::where('status', 'published')->count(),
    ]);
});

Route::get('/user-profile', [UserController::class, 'edit'])->middleware('isUser');
Route::get('/user-history', [UserController::class, 'history'])->middleware('isUser');

Route::put('/edit-profile-user/{user}', [UserController::class, 'update'])->middleware('isUser');
Route::get('/lowongan', [JobController::class, 'lowongan'])->middleware('isUser');
Route::get('/lowongan/detail/{job}', [JobController::class, 'show'])->middleware('isUser');
Route::post('/apply-job/{job}', [ApplyJobController::class, 'store'])->middleware('isUser');
