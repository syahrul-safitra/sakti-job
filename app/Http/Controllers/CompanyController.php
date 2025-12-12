<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Job;
use Illuminate\Http\Request;
use illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $company = Auth::guard('company')->user();

        $dataJob = Job::with('applyJobs')->where('company_id', $company->id)->get();

        $dataDraft = $dataJob->where('status', 'draft')->count();
        $dataPublished = $dataJob->where('status', 'published')->count();

        $dataApplyPending = 0;

        foreach ($dataJob as $job) {
            $dataApplyPending += $job->applyJobs->where('status', 'pending')->count();
        }

        return view('Company.dashboard',
            [
                // 'company' => Auth::guard('company')->user(),
                'dataJobCount' => $dataJob->count(),
                'dataDraft' => $dataDraft,
                'dataPublished' => $dataPublished,
                'dataApplyPending' => $dataApplyPending,
                'dataJob5Latest' => $dataJob->sortByDesc('created_at')->take(5),
            ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Company $company)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        return view('Company.editProfile', [
            'company' => Auth::guard('company')->user(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Company $company)
    {

        // 1. Definisikan Aturan Validasi Awal (untuk field NON-PASSWORD)
        $rules = [
            'name' => [
                'required',
                'max:255',
                // Pastikan unik, kecuali untuk ID perusahaan saat ini
                Rule::unique('companies')->ignore($company->id),
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('companies')->ignore($company->id),
            ],
            'phone' => [
                'required',
                'max:15',
                Rule::unique('companies')->ignore($company->id),
            ],
            'address' => 'required|string|max:500',
            'link_website' => '',
            'description' => 'required|string', // Data dari Trix Editor
        ];

        // 2. Terapkan Validasi Kondisional untuk Password
        // Kami hanya menambahkan aturan password jika user benar-benar mengisinya.
        if ($request->filled('password')) {
            $rules['password'] = 'required|string|max:20|confirmed';
            // Catatan: 'confirmed' mencari field 'password_confirmation'
        }

        // 3. Jalankan Validasi
        $validatedData = $request->validate($rules);

        // 4. Siapkan Data Profil untuk Update (mengambil semua data yang sudah divalidasi)
        $dataToUpdate = $validatedData;

        // 5. Tangani Password secara Terpisah/Kondisional
        // Hapus password dari data yang diupdate agar password yang lama tidak ter-hash kosong.
        unset($dataToUpdate['password']);

        // Hapus field konfirmasi (jika ada)
        unset($dataToUpdate['password_confirmation']);

        // Tambahkan password yang sudah di-hash HANYA JIKA diisi.
        if ($request->filled('password')) {
            $dataToUpdate['password'] = bcrypt($request->password);
        }

        // 6. Lakukan Update
        // Data Trix (description) dan field profil lainnya diupdate di sini.
        $company->update($dataToUpdate);

        if ($company->status == 'pending') {
            // 7. Redirect

            return back()->with('swal', [
                'icon' => 'success',
                'title' => 'Profil Diperbarui',
                'text' => 'Perubahan pada profil perusahaan telah disimpan.']);
        }

        return back()->with('swal', [
            'icon' => 'success',
            'title' => 'Profil Diperbarui',
            'text' => 'Perubahan pada profil perusahaan telah disimpan.',
        ]);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company)
    {
        //
    }
}
