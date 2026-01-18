<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ApplyJobController;
use App\Models\Job;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

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
Route::controller(AuthController::class)->group(function() {
    Route::get("/login", 'login')->middleware('isGuest');
    Route::post("/login", 'doLogin')->middleware('isGuest');

    Route::get('/register-company', 'registerCompany')->middleware('isGuest');
    Route::post('/register-company', 'doRegisterCompany')->middleware('isGuest');

    Route::get('/register-user', 'registerUser')->middleware('isGuest');
    Route::post('/register-user', 'doRegisterUser')->middleware('isGuest');

    Route::get('/register-user', 'registerUser')->middleware('isGuest');
    Route::post('/register-user', 'doRegisterUser')->middleware('isGuest');

    Route::post('/logout', 'logout');
});

// Route khusus untuk admin
Route::controller(AdminController::class)->group(function() {
    Route::get('/data-company', 'dataCompany')->middleware('isAdmin');
    Route::get('/data-lowongan', 'dataLowongan')->middleware('isAdmin');
    Route::get('/data-lowongan/detail/{job}', 'dataLowonganDetail')->middleware('isAdmin');
    Route::post('/admin-lowongan/publish/{job}', 'publishJob')->middleware('isAdmin');
    Route::post('/admin-lowongan/unpublish/{job}', 'unpublishJob')->middleware('isAdmin');
    Route::delete('/admin-lowongan/{job}', 'deleteJob')->middleware('isAdmin');
    Route::get('/data-lowongan/pelamar/{job}', 'jobApplicants')->middleware('isAdmin');
    Route::get('/laporan', 'report')->middleware('isAdmin');
    Route::get('/laporan/export-pdf', 'reportExportPdf')->middleware('isAdmin');
    Route::get('/laporan/export-csv', 'reportExportCsv')->middleware('isAdmin');
    Route::get('/laporan/export-excel', 'reportExportExcel')->middleware('isAdmin');
    Route::post('/data-company/verify/{company}', 'verify');
    Route::post('/data-company/reject/{company}', 'reject');

    Route::get('/lowongan-admin', 'lowonganAdmin')->middleware('isAdmin');
});

Route::get('/users', [AdminController::class, 'dataUser'])->middleware('isAdmin');
Route::get('/detail-pelamar-admin/{user}', [AdminController::class, 'showUser'])->middleware('isAdmin');


// =================================================================

// Route khusus company : 
Route::controller(CompanyController::class)->middleware('auth:company')->group(function() {
    Route::get('/dashboard-company', 'index')->middleware('isCompany');
    Route::get('/lengkapi-profile', 'edit')->middleware('isCompany');
    Route::post('/update-profile/{company}', 'update')->middleware('isCompany');
});

Route::controller(JobController::class)->group(function() {
    Route::get('/company-lowongan', 'index')->middleware('isCompany');
    Route::get('/company-lowongan/create', 'create')->middleware('isCompany');
    Route::post('/company-lowongan', 'store')->middleware('isCompany');
    Route::get('/company-lowongan/edit/{job}', 'edit')->middleware('isCompany');
    Route::post('/company/lowongan/publish/{job}', 'publish');
    Route::post('/company/lowongan/unpublish/{job}', 'unpublish');
    Route::put('/company-lowongan/{job}', 'update');
});

Route::controller(ApplyJobController::class)->group(function() {
    Route::get('/company-applyjob', 'indexCompany')->middleware('isCompany');
    Route::get('/company-applyjob/detail/{apply}', 'show')->middleware('isCompany');
    Route::post('/company-applyjob/update-status/{apply}', 'updateStatusPelamar')->middleware('isCompany');
});
Route::post('update-status-pelamar/{apply}', [ApplyJobController::class, 'updateStatusPelamar'])->middleware('isCompany');
Route::post("cetak-laporan-company/{job}", [JobController::class, 'laporan'])->middleware('isCompany');
Route::post("cetak-laporan-company-excel/{job}", [JobController::class, 'exportExcelPelamar'])->middleware('isCompany');

// ==================================================================

// Route khusus user :

Route::get('/', function(Request $request) {
    // $jobsLimited = collect();

    // $hasMore = false;
    // $loadError = null;
    // try {
    //     $jobsLimited = Job::with('company')->where('status', 'published')->latest()->take(7)->get();
    //     $hasMore = Job::where('status', 'published')->count() > 7;
    // } catch (\Throwable $e) {
    //     $loadError = 'Tabel "jobs" membutuhkan perbaikan. Silakan jalankan REPAIR TABLE jobs di database.';
    //     Log::error('Load homepage jobs failed: '.$e->getMessage());
    // }
    // return view('Landing.home', [
    //     'jobs' => $jobsLimited,
    //     'hasMore' => $hasMore,
    //     'loadError' => $loadError,
    // ]);

    $keyword = $request->input('keyword');
    $tipe = $request->input('tipe');

    if ($keyword || $tipe) {
        $jobs = Job::with('company')
            ->where('status', 'published')
            ->where(function ($query) use ($keyword, $tipe) {
                $query->where('title', 'like', '%'.$keyword.'%');
                $query->where('tipe', '=', $tipe);
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

Route::get('/lowongan', function() {
    $jobs = Job::with('company')->where('status', 'published')->latest()->paginate(12);
    $savedJobs = collect();
    if (Auth::guard('user')->check()) {
        $user = Auth::guard('user')->user();
        $ids = collect(json_decode($user->saved_jobs_json ?? '[]', true) ?: []);
        if ($ids->count() > 0) {
            $savedJobs = Job::with('company')->whereIn('id', $ids)->get();
        }
    }
    return view('Landing.lowongan', [
        'jobs' => $jobs,
        'savedJobs' => $savedJobs,
    ]);
});

Route::get('/user-profile',     function() {

    return view('User.profile', [
        'user' => Auth::guard('user')->user()
    ]);
})->middleware('isUser');

Route::put('/edit-profile-user/{user}', [UserController::class, 'update'])->middleware('isUser');
Route::get('/user-history', [UserController::class, 'history'])->middleware('isUser');
Route::get('/user/applications', [UserController::class, 'applications'])->middleware('isUser');
Route::get('/user/saved-jobs', [UserController::class, 'savedJobs'])->middleware('isUser');
Route::post('/user/saved-jobs/toggle/{job}', [UserController::class, 'toggleSavedJob'])->middleware('isUser');

Route::get('/lowongan/detail/{job}', [JobController::class, 'show'])->middleware('isUser');
Route::post('/apply-job/{job}', [ApplyJobController::class, 'store'])->middleware('isUser');
Route::post('/user-pdf/{user}', [UserController::class, 'pdf'])->middleware('isUser');
