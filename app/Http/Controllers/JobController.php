<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;
use illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $getCompany = Auth::guard('company')->user();

        return view('Company.Lowongan.index', [
            'jobs' => Job::where('company_id', $getCompany->id)->latest()->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('Company.Lowongan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        // 1. Validasi Input
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'employment_type' => 'required',

            'salary_min' => 'required|numeric|min:0',
            'salary_max' => 'required|numeric|min:0|gt:salary_min', // 'gt' memastikan Max > Min (jika Min diisi)

            'description' => 'required|string',
            // Validasi File Gambar
            'gambar' => 'required|image|mimes:jpg,png,jpeg|max:2048', // Maks 2MB
        ]);

        // 2. Proses Upload Gambar
        $file = $request->file('gambar');

        $fileName = uniqid().'_'.$file->getClientOriginalName();

        $locationFile = 'FileUpload';

        $file->move($locationFile, $fileName);

        $validatedData['gambar'] = $fileName;

        // 3. Siapkan Data untuk Disimpan
        $jobData = $validatedData;

        $jobData['company_id'] = Auth::guard('company')->user()->id;

        // 4. Simpan ke Database
        Job::create($jobData);

        // 5. Redirect dan Beri Pesan Sukses
        return redirect('company-lowongan')->with('swal', [
            'icon' => 'success',
            'title' => 'Data lowongan berhasil di buat!',
            'text' => 'Silahkan publish',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Job $job)
    {
        return view('Landing.detailLowongan', [
            'job' => $job->load('company'),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Job $job)
    {
        return view('Company.Lowongan.edit', [
            'job' => $job,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Job $job)
    {
        // 1. Definisikan Aturan Validasi
        $rules = [
            'title' => [
                'required',
                'string',
                'max:255',
            ],
            'location' => 'required|string|max:255',
            'employment_type' => 'required|',

            'salary_min' => 'required|numeric|min:0',
            'salary_max' => 'required|numeric|min:0|gt:salary_min',

            'description' => 'required|string',

            'gambar' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ];

        $validatedData = $request->validate($rules);

        $jobData = $validatedData;
        $locationFile = 'FileUpload';

        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $fileName = uniqid().'_'.$file->getClientOriginalName();

            $file->move($locationFile, $fileName);

            if ($job->gambar && File::exists(public_path($locationFile.'/'.$job->gambar))) {
                File::delete(public_path($locationFile.'/'.$job->gambar));
            }

            $jobData['gambar'] = $fileName;

        } else {
            unset($jobData['gambar']);
        }

        $job->update($jobData);

        return redirect('company-lowongan')->with('swal', [
            'icon' => 'success',
            'title' => 'Lowongan berhasil diperbarui!',
            'text' => 'Data lowongan telah berhasil diubah.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Job $job)
    {
        File::delete('FileUpload/'.$job->gambar);
        $job->delete();

        return back()->with('swal', [
            'icon' => 'success',
            'title' => 'Lowongan berhasil dihapus',
            'text' => 'Data lowongan telah dihapus dari sistem',
        ]);
    }

    public function publish(Job $job)
    {
        $job->status = 'published';
        $job->save();

        return back()->with('swal', [
            'icon' => 'success',
            'title' => 'Lowongan berhasil di publish',
            'text' => 'Sekarang lowongan dapat dilihat oleh calon karyawan',
        ]);
    }

    public function unpublish(Job $job)
    {
        $job->status = 'draft';
        $job->save();

        return back()->with('swal', [
            'icon' => 'success',
            'title' => 'Lowongan berhasil di unpublish',
            'text' => 'Sekarang lowongan tidka dapat dilihat oleh calon karyawan',
        ]);
    }

    public function lowongan()
    {
        return view('Landing.lowongan', [
            'jobs' => Job::with('company')
                ->where('status', 'published')
                ->latest()
                ->get(),
        ]);
    }
}
