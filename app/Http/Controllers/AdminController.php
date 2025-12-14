<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Job;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index() {
        // 1. Tentukan rentang 12 bulan terakhir untuk Zero-filling
        $months = [];
        $startDate = Carbon::now()->subMonths(11)->startOfMonth();

        for ($i = 0; $i < 12; $i++) {
            $month = $startDate->copy()->addMonths($i);
            // Label bulan (Kunci array)
            $months[$month->format('M Y')] = 0;
        }

        // 2. Kueri Database (Menghitung TOTAL Job per Bulan)
        $rawData = Job::query()
            // Batasi data dalam 12 bulan terakhir
            ->where('created_at', '>=', $startDate->format('Y-m-d H:i:s'))

            ->select(
                DB::raw('COUNT(id) as job_count'), // Hitung total Job
                DB::raw('DATE_FORMAT(created_at, "%b %Y") as month_year') // Format bulan/tahun
            )

            // Pengelompokan berdasarkan bulan
            ->groupBy(DB::raw('DATE_FORMAT(created_at, "%b %Y")'))
            ->orderByRaw('MIN(created_at) ASC')
            ->get();

        // 3. Proses Zero-filling (Mengisi bulan yang kosong dengan 0)
        $dataJobCounts = $months;

        foreach ($rawData as $data) {
            $monthKey = $data->month_year;

            if (isset($dataJobCounts[$monthKey])) {
                // Timpa nilai 0 dengan hitungan nyata dari database
                $dataJobCounts[$monthKey] = (int) $data->job_count;
            }
        }

        // Data siap untuk chart
        $chartData = [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],      // Nama-nama bulan (Sumbu X)
            'counts' => array_values($dataJobCounts), // Total hitungan Job (Sumbu Y)
        ];

        $dataCompanyAll = Company::all()->count();
        $dataCompanyPending = Company::where('status', 'pending')->count();
        $dataUserAll = User::all()->count();
        $dataJobAll = Job::all()->count();

        return view('Admin.dashboard', [
            'dataCompanyAll' => $dataCompanyAll,
            'dataCompanyPending' => $dataCompanyPending,
            'dataUserAll' => $dataUserAll,
            'dataJobAll' => $dataJobAll,
            'chartData' => $chartData,
        ]);
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

      public function dataUser()
    {
        return view('Admin.User.index', [
            'users' => User::latest()->get(),
        ]);
    }

    public function lowonganAdmin()
    {
        return view('Admin.Lowongan.index', [
            'collection' => Company::with('jobs')->get(),
        ]);
    }

     public function showUser(User $user)
    {
        return view('Admin.User.show', [
            'user' => $user,
        ]);
    }
}
