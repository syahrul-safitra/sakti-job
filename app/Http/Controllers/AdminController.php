<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index() {
        return view('Admin.dashboard');
    }

    public function dataCompany() {
        return view('Admin.Company.index', [
            'companies' => Company::latest()->get()
        ]);
    }

    public function verify(Company $company) {
        $company->status = 'verified';

        $company->save();

        return redirect('data-company')->with('swal', [
            'icon'  => 'success',
            'title' => 'Perusahaan Terverifikasi',
            'text'  => 'Profil perusahaan telah diverifikasi.'
        ]);
    }

    public function reject(Company $company) {
        $company->status = 'rejected';

        $company->save();

        return redirect('data-company')->with('swal', [
            'icon'  => 'warning',
            'title' => 'Perusahaan Ditolak',
            'text'  => 'Profil perusahaan ditolak.'
        ]);
    }
}
