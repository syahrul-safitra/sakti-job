<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Job;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
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

        // =============================================================

        // 1. Tentukan rentang 12 bulan terakhir
        $months = [];
        $startDate = Carbon::now()->subMonths(11)->startOfMonth();
        $monthForLabel = [];

        for ($i = 0; $i < 12; $i++) {
            $month = $startDate->copy()->addMonths($i);
            // Kunci untuk Zero-filling: format 'Jan 2024'
            $months[$month->format('M Y')] = 0;
            // $monthForLabel[] = $month->format('M');
        }

        // 2. Kueri menggunakan Query Builder & DB::raw untuk Conditional Aggregation
        $rawData = Job::query() // Menggunakan `Job::query()` atau `Job::select(...)`
            // Batasi rentang waktu 12 bulan
            ->where('created_at', '>=', $startDate->format('Y-m-d H:i:s'))
            ->where('company_id', $company->id)

            // Kolom SELECT: Gunakan DB::raw untuk Conditional Aggregation dan Date Formatting
            ->select(
                // Menghitung status 'draft'
                DB::raw('COUNT(CASE WHEN status = "draft" THEN id END) as draft_count'),
                // Menghitung status 'published'
                DB::raw('COUNT(CASE WHEN status = "published" THEN id END) as published_count'),
                // Format tanggal untuk pengelompokan
                DB::raw('DATE_FORMAT(created_at, "%b %Y") as month_year')
            )

            // Pengelompokan
            ->groupBy(DB::raw('DATE_FORMAT(created_at, "%b %Y")')) // Group by kolom yang baru dibuat

            // Urutkan berdasarkan bulan
            ->orderByRaw('MIN(created_at) ASC')

            ->get();

        // 3. Inisialisasi dan Zero-filling (Logika PHP ini tetap sama)
        $dataJobDraft = $months;
        $dataJobPublished = $months;

        foreach ($rawData as $data) {
            $monthKey = $data->month_year;

            if (isset($dataJobDraft[$monthKey])) {
                $dataJobDraft[$monthKey] = (int) $data->draft_count;
            }
            if (isset($dataJobPublished[$monthKey])) {
                $dataJobPublished[$monthKey] = (int) $data->published_count;
            }
        }

        // =============================================================

        return view('Company.dashboard',
            [
                'dataJobCount' => $dataJob->count(),
                'dataDraft' => $dataDraft,
                'dataPublished' => $dataPublished,
                'dataApplyPending' => $dataApplyPending,
                'dataJob5Latest' => $dataJob->sortByDesc('created_at')->take(5),
                'chartData' => [
                    'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    'drafts' => array_values($dataJobDraft),
                    'published' => array_values($dataJobPublished),
                ],
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
            'company' => Auth::guard('company')->user()
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
                Rule::unique('companies')->ignore($company->id) 
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
                Rule::unique('companies')->ignore($company->id)
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


        if( $company->status == 'pending') {
            // 7. Redirect

            return back()->with('swal', [
                'icon'  => 'success',
                'title' => 'Profil Diperbarui',
                'text'  => 'Perubahan pada profil perusahaan telah disimpan.']);
        }
        
        return back()->with('swal', [
            'icon'  => 'success',
            'title' => 'Profil Diperbarui',
            'text'  => 'Perubahan pada profil perusahaan telah disimpan.'
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
