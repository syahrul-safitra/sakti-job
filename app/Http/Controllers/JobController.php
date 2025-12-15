<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;
use illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Barryvdh\DomPDF\Facade\Pdf;

class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $getCompany = Auth::guard('company')->user();

        return view('Company.Lowongan.index', [
            'jobs' => Job::where('company_id', $getCompany->id)->latest()->get()
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
        $file = $request->file("gambar");

        $fileName = uniqid() . '_'. $file->getClientOriginalName();
    
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
                        'icon'  => 'success',
                        'title' => 'Data lowongan berhasil di buat!',
                        'text'  => 'Silahkan publish'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Job $job)
    {
        return view('Landing.detailLowongan', [
            'job' => $job->load('company')
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Job $job)
    {
        return view('Company.Lowongan.edit', [
            'job' => $job
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
            $fileName = uniqid() . '_' . $file->getClientOriginalName();
            
            $file->move($locationFile, $fileName);

            if ($job->gambar && File::exists(public_path($locationFile . '/' . $job->gambar))) {
                File::delete(public_path($locationFile . '/' . $job->gambar));
            }
            
            $jobData['gambar'] = $fileName;

        } else {
            unset($jobData['gambar']); 
        }

        $job->update($jobData);

        return redirect('company-lowongan')->with('swal', [
            'icon'  => 'success',
            'title' => 'Lowongan berhasil diperbarui!',
            'text'  => 'Data lowongan telah berhasil diubah.'
        ]);
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Job $job)
    {
        File::delete('FileUpload/' . $job->gambar);
        $job->delete();

        return back()->with('swal', [
            'icon'  => 'success',
            'title' => 'Lowongan berhasil dihapus!',
            'text'  => 'Data lowongan telah berhasil dihapus.'
        ]);

    }

    public function publish(Job $job) {
        $job->status = 'published';
        $job->save();
        
        return back()->with('swal', [
            'icon'  => 'success',
            'title' => 'Lowongan berhasil di publish',
            'text'  => 'Sekarang lowongan dapat dilihat oleh calon karyawan'
        ]);
    }

    public function unpublish(Job $job) {
        $job->status = 'draft';
        $job->save();

        return back()->with('swal', [
            'icon'  => 'success',
            'title' => 'Lowongan berhasil di unpublish',
            'text'  => 'Sekarang lowongan tidka dapat dilihat oleh calon karyawan'
        ]);
    }

    public function laporan(Job $job) {
        $pdf = Pdf::loadView('Company.laporan', [
            'job' => $job->load('applyJobs.user')
        ]);

        return $pdf->download('Laporan' . $job->title . '.pdf');
    }

    public function exportExcelPelamar(Job $job) {
        // 1. Ambil data utama beserta pelamar

        $job = $job->load('applyJobs.user');
        
        // 2. Siapkan baris data pelamar
        $rowsHtml = '';
        foreach ($job->applyJobs as $index => $apply) {
            $rowsHtml .= '
            <tr>
                <td style="text-align:center;">' . ($index + 1) . '</td>
                <td>' . ($apply->user->full_name ?? '-') . '</td>
                <td>' . ($apply->user->jenis_kelamin ?? '-') . '</td>
                <td>' . ($apply->user->email ?? '-') . '</td>
                <td>' . ($apply->user->phone ?? '-') . '</td>
                <td>' . ($apply->user->address ?? '-') . '</td>
                <td>' . ucfirst($apply->status) . '</td>
                <td>' . $apply->created_at->format('d/m/Y') . '</td>
            </tr>';
        }

        // 3. Susun Dokumen HTML (Gabungkan Keterangan Job & Tabel)
        $html = '
        <html>
        <head>
            <meta charset="utf-8">
            <style>
                table { border-collapse: collapse; }
                th, td { border: 1px solid #000000; padding: 5px; vertical-align: top; }
                .header-job { font-weight: bold; background-color: #e9ecef; }
                .title-doc { font-size: 18px; font-weight: bold; text-decoration: underline; }
            </style>
        </head>
        <body>
            <table>
                <tr><td colspan="8" class="title-doc" style="border:none; text-align:center;">LAPORAN DATA LOWONGAN & PELAMAR</td></tr>
                <tr><td colspan="8" style="border:none;">&nbsp;</td></tr>
                
                <tr>
                    <td colspan="2" class="header-job">Nama Lowongan</td>
                    <td colspan="6">' . e($job->title) . '</td>
                </tr>
                <tr>
                    <td colspan="2" class="header-job">Lokasi & Tipe</td>
                    <td colspan="6">' . e($job->location) . ' (' . e($job->employment_type) . ')</td>
                </tr>
                <tr>
                    <td colspan="2" class="header-job">Rentang Gaji</td>
                    <td colspan="6">Rp ' . number_format($job->salary_min, 0, ',', '.') . ' - Rp ' . number_format($job->salary_max, 0, ',', '.') . '</td>
                </tr>
                <tr>
                    <td colspan="2" class="header-job">Status Lowongan</td>
                    <td colspan="6">' . strtoupper($job->status) . '</td>
                </tr>
                <tr>
                    <td colspan="2" class="header-job">Deskripsi Pekerjaan</td>
                    <td colspan="6">' . nl2br(e($job->description)) . '</td>
                </tr>
                
                <tr><td colspan="8" style="border:none;">&nbsp;</td></tr>
                
                <thead>
                    <tr style="background-color: #007bff; color: #ffffff;">
                        <th width="50">No</th>
                        <th width="200">Nama Pelamar</th>
                        <th width="100">L/P</th>
                        <th width="200">Email</th>
                        <th width="150">No HP</th>
                        <th width="250">Alamat</th>
                        <th width="100">Status</th>
                        <th width="150">Tgl Melamar</th>
                    </tr>
                </thead>
                <tbody>
                    ' . $rowsHtml . '
                </tbody>
            </table>
        </body>
        </html>';

        // 4. Response Header
        $filename = 'Laporan-Lowongan-' . str_replace(' ', '-', $job->title) . '.xls';

        return response($html, 200, [
            'Content-Type' => 'application/vnd.ms-excel; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'max-age=0',
        ]);
    }
}
