<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Job;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
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

    public function dataLowongan() {
        $companies = Company::with(['jobs' => function ($q) {
            $q->latest();
        }])->latest()->get();

        return view('Admin.Job.index', [
            'companies' => $companies
        ]);
    }

    public function dataLowonganDetail(Job $job) {
        return view('Admin.Job.detail', [
            'job' => $job->load('company')
        ]);
    }

    public function report(Request $request) {
        $year = (int) ($request->get('year') ?? Carbon::now()->year);
        $month = $request->get('month');
        $month = $month !== null ? max(1, min(12, (int)$month)) : null;
        $labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $companiesMonthly = array_fill(0, 12, 0);
        $jobsMonthly = array_fill(0, 12, 0);
        $usersMonthly = array_fill(0, 12, 0);

        $companyData = Company::select(
                DB::raw('COUNT(id) as count'),
                DB::raw('MONTH(created_at) as month')
            )
            ->whereYear('created_at', $year)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->get();
        foreach ($companyData as $row) {
            $companiesMonthly[((int)$row->month) - 1] = (int)$row->count;
        }

        $jobData = Job::select(
                DB::raw('COUNT(id) as count'),
                DB::raw('MONTH(created_at) as month')
            )
            ->whereYear('created_at', $year)
            ->where('status', 'published')
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->get();
        foreach ($jobData as $row) {
            $jobsMonthly[((int)$row->month) - 1] = (int)$row->count;
        }

        $userData = User::select(
                DB::raw('COUNT(id) as count'),
                DB::raw('MONTH(created_at) as month')
            )
            ->whereYear('created_at', $year)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->get();
        foreach ($userData as $row) {
            $usersMonthly[((int)$row->month) - 1] = (int)$row->count;
        }

        $totals = [
            'companies' => Company::whereYear('created_at', $year)->count(),
            'jobs'      => Job::whereYear('created_at', $year)->where('status', 'published')->count(),
            'users'     => User::whereYear('created_at', $year)->count(),
        ];

        $years = [];
        $currentYear = Carbon::now()->year;
        for ($i = 0; $i < 6; $i++) {
            $years[] = $currentYear - $i;
        }

        $chartLabels = $labels;
        $companiesChart = $companiesMonthly;
        $jobsChart = $jobsMonthly;
        $usersChart = $usersMonthly;
        if ($month !== null) {
            $chartLabels = [$labels[$month - 1]];
            $companiesChart = [$companiesMonthly[$month - 1]];
            $jobsChart = [$jobsMonthly[$month - 1]];
            $usersChart = [$usersMonthly[$month - 1]];
        }

        return view('Admin.Report.index', [
            'year' => $year,
            'month' => $month,
            'years' => $years,
            'labels' => $labels,
            'companiesMonthly' => $companiesMonthly,
            'jobsMonthly' => $jobsMonthly,
            'usersMonthly' => $usersMonthly,
            'chartLabels' => $chartLabels,
            'companiesChart' => $companiesChart,
            'jobsChart' => $jobsChart,
            'usersChart' => $usersChart,
            'totals' => $totals,
        ]);
    }

    public function reportExportPdf(Request $request) {
        $year = (int) ($request->get('year') ?? Carbon::now()->year);
        $labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $companiesMonthly = array_fill(0, 12, 0);
        $jobsMonthly = array_fill(0, 12, 0);
        $usersMonthly = array_fill(0, 12, 0);

        $companyData = Company::select(
                DB::raw('COUNT(id) as count'),
                DB::raw('MONTH(created_at) as month')
            )
            ->whereYear('created_at', $year)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->get();
        foreach ($companyData as $row) {
            $companiesMonthly[((int)$row->month) - 1] = (int)$row->count;
        }

        $jobData = Job::select(
                DB::raw('COUNT(id) as count'),
                DB::raw('MONTH(created_at) as month')
            )
            ->whereYear('created_at', $year)
            ->where('status', 'published')
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->get();
        foreach ($jobData as $row) {
            $jobsMonthly[((int)$row->month) - 1] = (int)$row->count;
        }

        $userData = User::select(
                DB::raw('COUNT(id) as count'),
                DB::raw('MONTH(created_at) as month')
            )
            ->whereYear('created_at', $year)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->get();
        foreach ($userData as $row) {
            $usersMonthly[((int)$row->month) - 1] = (int)$row->count;
        }

        $totals = [
            'companies' => Company::whereYear('created_at', $year)->count(),
            'jobs'      => Job::whereYear('created_at', $year)->where('status', 'published')->count(),
            'users'     => User::whereYear('created_at', $year)->count(),
        ];

        $pdf = Pdf::loadView('Admin.Report.pdf', [
            'year' => $year,
            'labels' => $labels,
            'companiesMonthly' => $companiesMonthly,
            'jobsMonthly' => $jobsMonthly,
            'usersMonthly' => $usersMonthly,
            'totals' => $totals,
        ])->setPaper('a4', 'portrait');

        return $pdf->download('Laporan-'.$year.'.pdf');
    }

    public function reportExportCsv(Request $request) {
        $year = (int) ($request->get('year') ?? Carbon::now()->year);
        $month = $request->get('month');
        $month = $month !== null ? max(1, min(12, (int)$month)) : null;
        $labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $companiesMonthly = array_fill(0, 12, 0);
        $jobsMonthly = array_fill(0, 12, 0);
        $usersMonthly = array_fill(0, 12, 0);

        $companyData = Company::select(
                DB::raw('COUNT(id) as count'),
                DB::raw('MONTH(created_at) as month')
            )
            ->whereYear('created_at', $year)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->get();
        foreach ($companyData as $row) {
            $companiesMonthly[((int)$row->month) - 1] = (int)$row->count;
        }
        $jobData = Job::select(
                DB::raw('COUNT(id) as count'),
                DB::raw('MONTH(created_at) as month')
            )
            ->whereYear('created_at', $year)
            ->where('status', 'published')
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->get();
        foreach ($jobData as $row) {
            $jobsMonthly[((int)$row->month) - 1] = (int)$row->count;
        }
        $userData = User::select(
                DB::raw('COUNT(id) as count'),
                DB::raw('MONTH(created_at) as month')
            )
            ->whereYear('created_at', $year)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->get();
        foreach ($userData as $row) {
            $usersMonthly[((int)$row->month) - 1] = (int)$row->count;
        }

        $rows = [];
        $rows[] = ['Bulan', 'Perusahaan Terdaftar', 'Lowongan Dipublikasikan', 'User Terdaftar'];
        if ($month !== null) {
            $i = $month - 1;
            $rows[] = [$labels[$i], $companiesMonthly[$i], $jobsMonthly[$i], $usersMonthly[$i]];
        } else {
            for ($i = 0; $i < 12; $i++) {
                $rows[] = [$labels[$i], $companiesMonthly[$i], $jobsMonthly[$i], $usersMonthly[$i]];
            }
        }

        $csv = '';
        foreach ($rows as $r) {
            $csv .= implode(',', array_map(fn($v) => (string)$v, $r)) . "\r\n";
        }

        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="Laporan-'.$year.($month ? '-'.$labels[$month-1] : '').'.csv"',
        ]);
    }

    public function reportExportExcel(Request $request) {
        $year = (int) ($request->get('year') ?? Carbon::now()->year);
        $month = $request->get('month');
        $month = $month !== null ? max(1, min(12, (int)$month)) : null;
        $labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $companiesMonthly = array_fill(0, 12, 0);
        $jobsMonthly = array_fill(0, 12, 0);
        $usersMonthly = array_fill(0, 12, 0);

        $companyData = Company::select(DB::raw('COUNT(id) as count'), DB::raw('MONTH(created_at) as month'))
            ->whereYear('created_at', $year)->groupBy(DB::raw('MONTH(created_at)'))->get();
        foreach ($companyData as $row) {
            $companiesMonthly[((int)$row->month) - 1] = (int)$row->count;
        }
        $jobData = Job::select(DB::raw('COUNT(id) as count'), DB::raw('MONTH(created_at) as month'))
            ->whereYear('created_at', $year)->where('status', 'published')->groupBy(DB::raw('MONTH(created_at)'))->get();
        foreach ($jobData as $row) {
            $jobsMonthly[((int)$row->month) - 1] = (int)$row->count;
        }
        $userData = User::select(DB::raw('COUNT(id) as count'), DB::raw('MONTH(created_at) as month'))
            ->whereYear('created_at', $year)->groupBy(DB::raw('MONTH(created_at)'))->get();
        foreach ($userData as $row) {
            $usersMonthly[((int)$row->month) - 1] = (int)$row->count;
        }

        $rowsHtml = '';
        if ($month !== null) {
            $i = $month - 1;
            $rowsHtml .= '<tr><td>'.$labels[$i].'</td><td>'.$companiesMonthly[$i].'</td><td>'.$jobsMonthly[$i].'</td><td>'.$usersMonthly[$i].'</td></tr>';
        } else {
            for ($i = 0; $i < 12; $i++) {
                $rowsHtml .= '<tr><td>'.$labels[$i].'</td><td>'.$companiesMonthly[$i].'</td><td>'.$jobsMonthly[$i].'</td><td>'.$usersMonthly[$i].'</td></tr>';
            }
        }

        $html = '<html><head><meta charset="utf-8"><style>table{border-collapse:collapse}td,th{border:1px solid #ccc;padding:6px}</style></head><body>';
        $html .= '<h3>Laporan Tahun '.$year.($month ? ' Bulan '.$labels[$month-1] : '').'</h3>';
        $html .= '<table><thead><tr><th>Bulan</th><th>Perusahaan Terdaftar</th><th>Lowongan Dipublikasikan</th><th>User Terdaftar</th></tr></thead><tbody>';
        $html .= $rowsHtml;
        $html .= '</tbody></table></body></html>';

        return response($html, 200, [
            'Content-Type' => 'application/vnd.ms-excel; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="Laporan-'.$year.($month ? '-'.$labels[$month-1] : '').'.xls"',
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

    public function dataUser() {
        return view('Admin.User.index', [
            'users' => User::latest()->get()
        ]);
    }

    public function showUser(User $user) {
        return view('Admin.User.show', [
            'user' => $user
        ]);
    }
}
