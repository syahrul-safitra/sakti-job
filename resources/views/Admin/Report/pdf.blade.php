<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Tahun {{ $year }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #222; }
        .title { font-size: 18px; font-weight: bold; margin-bottom: 4px; }
        .subtitle { color: #666; margin-bottom: 16px; }
        .cards { display: flex; gap: 12px; margin-bottom: 16px; }
        .card { flex: 1; border: 1px solid #ddd; border-radius: 6px; padding: 10px; }
        .card h6 { margin: 0; font-size: 12px; color: #777; }
        .card .value { font-size: 20px; font-weight: bold; margin-top: 6px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background: #f6f6f6; }
        .mt-2 { margin-top: 8px; }
        .mt-3 { margin-top: 12px; }
    </style>
</head>
<body>
    <div class="title">Laporan Tahun {{ $year }}</div>
    <div class="subtitle">Rekap per bulan</div>

    <div class="cards">
        <div class="card">
            <h6>Perusahaan Terdaftar</h6>
            <div class="value">{{ $totals['companies'] }}</div>
        </div>
        <div class="card">
            <h6>Lowongan Dipublikasikan</h6>
            <div class="value">{{ $totals['jobs'] }}</div>
        </div>
        <div class="card">
            <h6>User Terdaftar</h6>
            <div class="value">{{ $totals['users'] }}</div>
        </div>
    </div>

    <div class="mt-2">
        <table>
            <thead>
            <tr>
                <th>Bulan</th>
                <th>Perusahaan Terdaftar</th>
                <th>Lowongan Dipublikasikan</th>
                <th>User Terdaftar</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($labels as $i => $label)
                <tr>
                    <td>{{ $label }}</td>
                    <td>{{ $companiesMonthly[$i] }}</td>
                    <td>{{ $jobsMonthly[$i] }}</td>
                    <td>{{ $usersMonthly[$i] }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>

