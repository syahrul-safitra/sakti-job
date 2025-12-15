<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>CV - {{ $user->name }}</title>
    <style>
        @page {
            margin: 0.5cm;
        }

        body {
            font-family: 'Helvetica', sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.4;
        }

        .container {
            width: 100%;
        }

        /* Header Section */
        .header {
            background-color: #f8f9fa;
            padding: 20px;
            border-bottom: 3px solid #007bff;
            margin-bottom: 20px;
        }

        .photo {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 50%;
            float: left;
            border: 2px solid #ddd;
        }

        .header-info {
            margin-left: 120px;
        }

        .name {
            font-size: 20px;
            font-weight: bold;
            color: #007bff;
            text-transform: uppercase;
        }

        /* Layout */
        .row {
            clear: both;
            width: 100%;
        }

        .section {
            margin-bottom: 15px;
        }

        .section-title {
            background: #eee;
            padding: 5px 10px;
            font-weight: bold;
            border-left: 4px solid #007bff;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        /* List Styles */
        .item-title {
            font-weight: bold;
            font-size: 12px;
        }

        .item-sub {
            color: #555;
            font-style: italic;
            margin-bottom: 3px;
        }

        .item-desc {
            text-align: justify;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .table-apply th {
            background: #007bff;
            color: white;
            padding: 5px;
            text-align: left;
        }

        .table-apply td {
            border: 1px solid #ddd;
            padding: 5px;
        }

        .badge {
            background: #6c757d;
            color: white;
            padding: 2px 6px;
            border-radius: 10px;
            font-size: 9px;
            display: inline-block;
            margin: 2px;
        }

        .clear {
            clear: both;
        }
    </style>
</head>

<body>
    <div class="header">
        @if ($user->foto)
            <img src="{{ public_path('storage/' . $user->foto) }}" class="photo">
        @else
            <div class="photo" style="background: #ddd; text-align: center; line-height: 100px;">No Photo</div>
        @endif

        <div class="header-info">
            <div class="name">{{ $user->name }}</div>
            <div>{{ $user->jenis_kelamin }} | {{ $user->phone }} | {{ $user->email }}</div>
            <div>{{ $user->lokasi }}</div>
            <div style="font-style: italic;">{{ $user->alamat }}</div>
        </div>
        <div class="clear"></div>
    </div>

    <div class="container">
        <div class="section">
            <div class="section-title">Pengalaman Kerja</div>
            @php $exps = json_decode($user->experiences_json, true) ?: []; @endphp
            @foreach ($exps as $exp)
                <div class="item-title">{{ $exp['title'] ?? '-' }}</div>
                <div class="item-sub">{{ $exp['company'] ?? '-' }} | {{ $exp['period'] ?? '-' }}</div>
                <div class="item-desc">{{ $exp['desc'] ?? '' }}</div>
            @endforeach
        </div>

        <div class="section">
            <div class="section-title">Pendidikan</div>
            @php $educs = json_decode($user->education_json, true) ?: []; @endphp
            @foreach ($educs as $edu)
                <div class="item-title">{{ $edu['school'] ?? '-' }}</div>
                <div class="item-sub">{{ $edu['degree'] ?? '-' }} ({{ $edu['period'] ?? '-' }})</div>
                <div class="item-desc">{{ $edu['desc'] ?? '' }}</div>
            @endforeach
        </div>

        <div class="row">
            <div style="width: 48%; float: left;">
                <div class="section-title">Skill</div>
                @foreach (json_decode($user->skill_json, true) ?: [] as $skill)
                    <span class="badge">{{ $skill }}</span>
                @endforeach
            </div>
            <div style="width: 48%; float: right;">
                <div class="section-title">Bahasa</div>
                @foreach (json_decode($user->bahasa_json, true) ?: [] as $lang)
                    <span class="badge">{{ $lang }}</span>
                @endforeach
            </div>
            <div class="clear"></div>
        </div>

        <div class="section" style="margin-top: 15px;">
            <div class="section-title">Sertifikasi</div>
            @php $certs = json_decode($user->certifications_json, true) ?: []; @endphp
            @foreach ($certs as $cert)
                <div><strong>{{ $cert['name'] ?? '-' }}</strong> - {{ $cert['issuer'] ?? '-' }}
                    ({{ $cert['exp'] ?? '-' }})</div>
            @endforeach
        </div>

        <div class="section">
            <div class="section-title">Riwayat Lamaran Pekerjaan</div>
            <table class="table-apply">
                <thead>
                    <tr>
                        <th>Lowongan</th>
                        <th>Perusahaan</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($user->applyJobs as $apply)
                        <tr>
                            <td>{{ $apply->job->title }}</td>
                            <td>{{ $apply->job->company->name }}</td>
                            <td>{{ $apply->created_at->format('d/m/Y') }}</td>
                            <td>{{ ucfirst($apply->status) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>
