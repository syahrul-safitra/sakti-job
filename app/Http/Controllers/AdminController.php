<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Job;
use App\Models\User;

class AdminController extends Controller
{
    public function index()
    {

        $dataCompanyAll = Company::all()->count();
        $dataCompanyPending = Company::where('status', 'pending')->count();
        $dataUserAll = User::all()->count();
        $dataJobAll = Job::all()->count();

        return view('Admin.dashboard', [
            'dataCompanyAll' => $dataCompanyAll,
            'dataCompanyPending' => $dataCompanyPending,
            'dataUserAll' => $dataUserAll,
            'dataJobAll' => $dataJobAll,
        ]);
    }

    public function dataCompany()
    {
        return view('Admin.Company.index', [
            'companies' => Company::latest()->get(),
        ]);
    }

    public function verify(Company $company)
    {
        $company->status = 'verified';

        $company->save();

        return redirect('data-company')->with('swal', [
            'icon' => 'success',
            'title' => 'Perusahaan Terverifikasi',
            'text' => 'Profil perusahaan telah diverifikasi.',
        ]);
    }

    public function reject(Company $company)
    {
        $company->status = 'rejected';

        $company->save();

        return redirect('data-company')->with('swal', [
            'icon' => 'warning',
            'title' => 'Perusahaan Ditolak',
            'text' => 'Profil perusahaan ditolak.',
        ]);
    }

    public function lowonganAdmin()
    {
        return view('Admin.Lowongan.index', [
            'collection' => Company::with('jobs')->get(),
        ]);
    }

    public function dataUser()
    {
        return view('Admin.User.index', [
            'users' => User::latest()->get(),
        ]);
    }

    public function showUser(User $user)
    {
        return view('Admin.User.show', [
            'user' => $user,
        ]);
    }
}
