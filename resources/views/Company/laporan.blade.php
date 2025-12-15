<!DOCTYPE html>
<html>

<head>
    <title>Laporan Lowongan - {{ $job->title }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.5;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #444;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .job-title {
            font-size: 18px;
            text-transform: uppercase;
            margin-bottom: 5px;
            color: #007bff;
        }

        .section-title {
            background: #f4f4f4;
            padding: 5px 10px;
            font-weight: bold;
            border-left: 4px solid #007bff;
            margin: 15px 0 10px 0;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .info-table td {
            padding: 5px;
            vertical-align: top;
        }

        .label {
            width: 30%;
            font-weight: bold;
            color: #555;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .data-table th,
        .data-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .data-table th {
            background-color: #007bff;
            color: white;
            font-size: 11px;
        }

        .badge {
            padding: 3px 6px;
            border-radius: 4px;
            font-size: 10px;
            color: white;
        }

        .bg-success {
            background-color: #28a745;
        }

        .bg-warning {
            background-color: #ffc107;
            color: #000;
        }

        .bg-danger {
            background-color: #dc3545;
        }

        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 10px;
            font-style: italic;
        }
    </style>
</head>

<body>

    <div class="header">
        <div class="job-title">{{ $job->title }}</div>
        <div>{{ $job->location }} | {{ $job->employment_type }}</div>
    </div>

    <div class="section-title">Detail Lowongan</div>
    <table class="info-table">
        <tr>
            <td class="label">Status</td>
            <td>: {{ ucfirst($job->status) }}</td>
        </tr>
        <tr>
            <td class="label">Rentang Gaji</td>
            <td>: Rp {{ number_format($job->salary_min, 0, ',', '.') }} - Rp
                {{ number_format($job->salary_max, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="label">Deskripsi</td>
            <td>: {!! $job->description !!}</td>
        </tr>
    </table>

    <div class="section-title">Daftar Pelamar</div>
    <table class="data-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="25%">Nama Pelamar</th>
                <th width="10%">Kontak</th>
                <th width="15%">Tanggal Lamar</th>
                <th width="20%">Alamat</th>
                <th width="10%">Status</th>
                <th width="15%">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($job->applyJobs as $index => $apply)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td><strong>{{ $apply->user->name }}</strong><br>{{ $apply->user->email }}</td>
                    <td>{{ $apply->user->phone ?? '-' }}</td>
                    <td>{{ date('d-m-Y', strtotime($apply->user->created_at)) ?? '-' }}</td>
                    <td>{{ $apply->user->address ?? '-' }}</td>
                    <td>
                        {{ ucfirst($apply->status) }}
                    </td>
                    <td>{{ $apply->keterangan ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada: {{ date('d/m/Y H:i') }}
    </div>

</body>

</html>
