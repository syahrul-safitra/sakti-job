@extends('Landing.Layouts.main')

@section('content')
    @php
        $user = Auth::guard('user')->user();
        $u = optional($user);

        // ======= PERSONAL INFO (untuk offcanvas) =======
        $fullName = $u->full_name ?? ($u->name ?? '');
        $nameParts = preg_split('/\s+/', trim($fullName));
        $firstName = $u->first_name ?? ($nameParts[0] ?? '');
        $lastName = $u->last_name ?? (count($nameParts) > 1 ? implode(' ', array_slice($nameParts, 1)) : '');
        $phone = $u->phone ?? '';
        $location = $u->location ?? '';
        $portfolio = $u->portfolio_url ?? '';

        // Dummy fallback (kalau belum ada tabel/field-nya)
        $summary =
            $u->summary ??
            'Fresh graduate Sistem Informasi dengan pemahaman yang baik dalam web development, analisis bisnis, dan pengelolaan data. Memiliki keterampilan komunikasi, pemecahan masalah, dan adaptasi yang tinggi. Berpengalaman dalam proyek akademik maupun magang.';

        // Kalau skill disimpan sebagai CSV di DB, misal: "Laravel,PHP,MySQL"
        $skills = [];
        if (!empty($u->skills)) {
            $skills = array_filter(array_map('trim', explode(',', $u->skills)));
        } else {
            $skills = [
                'Sistem Informasi',
                'ReactJS',
                'Pemrograman JavaScript',
                'Pemrograman PHP',
                'CodeIgniter',
                'Laravel Framework',
                'Aplikasi Web',
                'Microsoft Office',
                'Pemasaran Digital',
            ];
        }

        $experiences = [];
        if (!empty($u->experiences_json)) {
            $experiences = json_decode($u->experiences_json, true) ?? [];
        }
        $educations = [];
        if (!empty($u->education_json)) {
            $educations = json_decode($u->education_json, true) ?? [];
        }
        $certs = [];
        if (!empty($u->certifications_json)) {
            $certs = json_decode($u->certifications_json, true) ?? [];
        }
        $languages = [];
        if (!empty($u->languages_json)) {
            $languages = json_decode($u->languages_json, true) ?? [];
        }
        $skills = [];
        if (!empty($u->skills_json)) {
            $skills = json_decode($u->skills_json, true) ?? [];
        } else {
            $skills = $skills;
        }
        $resume = [
            'name' => $u->file_cv ?? null,
            'uploaded' => null,
            'url' => $u->file_cv ?? null ? asset('FileUpload/' . $u->file_cv) : '#',
        ];
    @endphp

    <style>
        .profile-hero {
            background: radial-gradient(1200px 400px at 80% -20%, rgba(255, 0, 153, .35), transparent 55%),
                radial-gradient(800px 360px at 100% 40%, rgba(255, 0, 153, .25), transparent 55%),
                radial-gradient(700px 320px at 70% 120%, rgba(0, 195, 255, .18), transparent 55%),
                linear-gradient(135deg, #061a44 0%, #0a2a6a 45%, #071a3f 100%);
            border-radius: 18px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, .08);
        }

        .profile-hero .shape {
            position: absolute;
            border-radius: 999px;
            opacity: .9;
            filter: blur(.1px);
        }

        .profile-hero .shape.pink1 {
            width: 160px;
            height: 160px;
            right: -60px;
            top: -40px;
            background: #ff2aa1;
        }

        .profile-hero .shape.pink2 {
            width: 120px;
            height: 120px;
            right: 20px;
            bottom: -60px;
            background: #ff2aa1;
        }

        .profile-hero .shape.blue1 {
            width: 140px;
            height: 140px;
            right: 90px;
            top: 40px;
            background: #1c4ea8;
            opacity: .45;
        }

        .profile-hero .shape.ring {
            width: 140px;
            height: 140px;
            right: 40px;
            bottom: -70px;
            border: 18px solid rgba(255, 255, 255, .08);
            background: transparent;
        }

        .section-card {
            border-radius: 14px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, .05);
            border: 1px solid rgba(0, 0, 0, .05);
        }

        .chip {
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            padding: .35rem .6rem;
            border-radius: 999px;
            border: 1px solid rgba(0, 0, 0, .08);
            background: #f8fafc;
            font-size: .82rem;
            margin: .25rem .25rem 0 0;
            white-space: nowrap;
        }

        .subtle {
            color: rgba(255, 255, 255, .8);
        }

        /* ===== Offcanvas feel seperti JobStreet ===== */
        .profile-offcanvas {
            width: min(560px, 100vw);
        }

        .profile-offcanvas .offcanvas-header {
            padding: 22px 22px 14px;
        }

        .profile-offcanvas .offcanvas-body {
            padding: 18px 22px 22px;
        }

        .profile-offcanvas .form-label {
            font-weight: 600;
        }

        .profile-offcanvas .btn-primary {
            padding: .6rem 1.3rem;
            border-radius: 10px;
        }
    </style>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <div class="container py-4">
        @if (session('success'))
            <script src="{{ asset('assets/admindash/assets/extensions/sweetalert2/sweetalert2.min.js') }}"></script>
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: @json(session('success')),
                    timer: 3000
                });
            </script>
        @endif

        {{-- HERO --}}
        <div class="profile-hero p-4 p-md-5 text-white mb-4">
            <div class="shape pink1"></div>
            <div class="shape pink2"></div>
            <div class="shape blue1"></div>
            <div class="shape ring"></div>

            <div class="row align-items-center position-relative g-0" style="z-index:2;">
                <div class="col-auto d-flex align-items-center me-3">
                    @if (!empty($u->photo))
                        <img src="{{ asset('FileUpload/' . $u->photo) }}" alt="Foto"
                            style="width:120px;height:120px;border-radius:50%;object-fit:cover;border:3px solid rgba(255,255,255,.3);box-shadow:0 8px 16px rgba(0,0,0,.15);">
                    @else
                        <div
                            style="width:120px;height:120px;border-radius:50%;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:2rem;color:#fff;border:3px solid rgba(255,255,255,.3);box-shadow:0 8px 16px rgba(0,0,0,.15);">
                            {{ strtoupper(substr($firstName, 0, 1)) }}
                        </div>
                    @endif
                </div>
                <div class="col">
                    <h2 class="fw-bold mb-2 text-white">
                        {{ optional($user)->full_name ?? (optional($user)->name ?? 'User') }}
                    </h2>

                    <div class="d-flex flex-wrap gap-3 small subtle">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-geo-alt"></i>
                            <span>{{ optional($user)->location ?? '—' }}</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-house"></i>
                            <span>{{ optional($user)->address ?? '—' }}</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-envelope"></i>
                            <span>{{ optional($user)->email ?? '—' }}</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-link-45deg"></i>
                            <span class="text-truncate" style="max-width:360px;">
                                {{ optional($user)->portfolio_url ?? '—' }}
                            </span>
                        </div>
                    </div>

                    <div class="mt-3">
                        {{-- tombol pemicu slider kanan --}}
                        <button type="button" class="btn btn-outline-light btn-sm px-4" data-bs-toggle="offcanvas"
                            data-bs-target="#offcanvasPersonalInfo" aria-controls="offcanvasPersonalInfo">
                            Ubah
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            {{-- LEFT MAIN --}}
            <div class="col-lg-8">

                {{-- Ringkasan --}}
                <div class="card section-card mb-4">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <h6 class="fw-bold mb-0">Ringkasan pribadi</h6>
                            <a href="#" class="text-muted" title="Edit" data-bs-toggle="offcanvas"
                                data-bs-target="#offcanvasSummary" aria-controls="offcanvasSummary">
                                <i class="bi bi-pencil"></i>
                            </a>
                        </div>
                        <div class="text-muted small" style="line-height:1.6;">
                            {{ $summary }}
                        </div>
                    </div>
                </div>

                {{-- Riwayat Karier --}}
                <div class="card section-card mb-4">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h6 class="fw-bold mb-0">Riwayat karier</h6>
                            <a href="#" class="btn btn-outline-primary btn-sm" data-bs-toggle="offcanvas"
                                data-bs-target="#offcanvasExperience" aria-controls="offcanvasExperience"
                                data-type="experience" data-mode="create">
                                Tambah jabatan
                            </a>
                        </div>

                        @foreach ($experiences as $exp)
                            <div class="d-flex gap-3">
                                <div class="flex-shrink-0">
                                    <div class="d-flex align-items-center justify-content-center"
                                        style="width:40px;height:40px;border-radius:10px;background:#0d6efd14;color:#0d6efd;">
                                        <i class="bi bi-briefcase"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <div class="fw-semibold">{{ $exp['title'] }}</div>
                                            <div class="text-muted small">{{ $exp['company'] }}</div>
                                            <div class="text-muted small">{{ $exp['period'] }}</div>
                                        </div>
                                        <a href="#" class="text-muted" title="Edit" data-bs-toggle="offcanvas"
                                            data-bs-target="#offcanvasExperience" aria-controls="offcanvasExperience"
                                            data-type="experience" data-mode="edit" data-index="{{ $loop->index }}"
                                            data-title="{{ $exp['title'] }}" data-company="{{ $exp['company'] }}"
                                            data-period="{{ $exp['period'] }}" data-desc="{{ $exp['desc'] }}">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    </div>
                                    <div class="text-muted small mt-2" style="line-height:1.6;">
                                        {{ $exp['desc'] }}
                                    </div>
                                    @if (!$loop->last)
                                        <hr class="my-3">
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Pendidikan --}}
                <div class="card section-card mb-4">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h6 class="fw-bold mb-0">Pendidikan</h6>
                            <a href="#" class="btn btn-outline-primary btn-sm" data-bs-toggle="offcanvas"
                                data-bs-target="#offcanvasEducation" aria-controls="offcanvasEducation"
                                data-type="education" data-mode="create">
                                Tambah pendidikan
                            </a>
                        </div>

                        @foreach ($educations as $edu)
                            <div class="d-flex gap-3">
                                <div class="flex-shrink-0">
                                    <div class="d-flex align-items-center justify-content-center"
                                        style="width:40px;height:40px;border-radius:10px;background:#19875414;color:#198754;">
                                        <i class="bi bi-mortarboard"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <div class="fw-semibold">{{ $edu['degree'] }}</div>
                                            <div class="text-muted small">{{ $edu['school'] }}</div>
                                            <div class="text-muted small">{{ $edu['period'] }}</div>
                                        </div>
                                        <a href="#" class="text-muted" title="Edit" data-bs-toggle="offcanvas"
                                            data-bs-target="#offcanvasEducation" aria-controls="offcanvasEducation"
                                            data-type="education" data-mode="edit" data-index="{{ $loop->index }}"
                                            data-degree="{{ $edu['degree'] }}" data-school="{{ $edu['school'] }}"
                                            data-period="{{ $edu['period'] }}" data-desc="{{ $edu['desc'] }}">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    </div>
                                    <div class="text-muted small mt-2" style="line-height:1.6;">
                                        {{ $edu['desc'] }}
                                    </div>
                                    @if (!$loop->last)
                                        <hr class="my-3">
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Lisensi & Sertifikasi --}}
                <div class="card section-card mb-4">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h6 class="fw-bold mb-0">Lisensi & sertifikasi</h6>
                            <a href="#" class="btn btn-outline-primary btn-sm" data-bs-toggle="offcanvas"
                                data-bs-target="#offcanvasCertifications" aria-controls="offcanvasCertifications"
                                data-type="certifications" data-mode="create">
                                Tambah lisensi atau sertifikasi
                            </a>
                        </div>

                        @foreach ($certs as $c)
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="fw-semibold">{{ $c['name'] }}</div>
                                    <div class="text-muted small">{{ $c['issuer'] }}</div>
                                    <div class="text-muted small">{{ $c['exp'] }}</div>
                                </div>
                                <a href="#" class="text-muted" title="Edit" data-bs-toggle="offcanvas"
                                    data-bs-target="#offcanvasCertifications" aria-controls="offcanvasCertifications"
                                    data-type="certifications" data-mode="edit" data-index="{{ $loop->index }}"
                                    data-name="{{ $c['name'] }}" data-issuer="{{ $c['issuer'] }}"
                                    data-exp="{{ $c['exp'] }}">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            </div>
                            @if (!$loop->last)
                                <hr class="my-3">
                            @endif
                        @endforeach
                    </div>
                </div>

                {{-- Keahlian --}}
                <div class="card section-card mb-4">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h6 class="fw-bold mb-0">Keahlian</h6>
                            <a href="#" class="btn btn-outline-primary btn-sm" data-bs-toggle="offcanvas"
                                data-bs-target="#offcanvasSkills" aria-controls="offcanvasSkills">
                                Tambah keahlian
                            </a>
                        </div>

                        <div class="d-flex flex-wrap">
                            @foreach ($skills as $s)
                                <span class="chip">{{ $s }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Bahasa --}}
                <div class="card section-card mb-4">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h6 class="fw-bold mb-0">Bahasa</h6>
                            <a href="#" class="btn btn-outline-primary btn-sm" data-bs-toggle="offcanvas"
                                data-bs-target="#offcanvasLanguages" aria-controls="offcanvasLanguages">
                                Tambah bahasa
                            </a>
                        </div>

                        <div class="d-grid gap-2">
                            @foreach ($languages as $lang)
                                <div class="d-flex justify-content-between align-items-center border rounded-3 p-3">
                                    <div class="fw-semibold">{{ $lang }}</div>
                                    <a href="#" class="text-muted" title="Edit" data-bs-toggle="offcanvas"
                                        data-bs-target="#offcanvasLanguages" aria-controls="offcanvasLanguages">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Resume --}}
                <div class="card section-card mb-4">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3">Resume</h6>

                        <div class="border rounded-3 p-3 d-flex justify-content-between align-items-center">
                            <div>
                                <div class="fw-semibold">{{ $resume['name'] }}</div>
                                <div class="text-muted small">{{ $resume['uploaded'] }}</div>
                                <div class="text-muted small">Resume ini dapat dilihat oleh perusahaan.</div>
                            </div>
                            <a class="btn btn-outline-secondary btn-sm" href="{{ $resume['url'] }}">
                                <i class="bi bi-download"></i>
                            </a>
                        </div>

                        {{-- <div class="mt-3">
                            <a href="{{ url('user/profile/resume') }}" class="btn btn-outline-primary btn-sm">
                                Tambah atau kelola resume
                            </a>
                            <a href="{{ url('user/profile/resume/create') }}" class="btn btn-primary btn-sm ms-2">
                                Buat resume
                            </a>
                        </div> --}}
                    </div>
                </div>
            </div>

            {{-- RIGHT SIDE --}}
            <div class="col-lg-4">
                <div class="card section-card">
                    <div class="card-body">
                        <h6 class="fw-bold mb-2">Ringkasan Profil</h6>
                        <div class="border rounded-3 p-3 mb-2">
                            <div class="text-muted small">Kelengkapan profil</div>
                            @php
                                $checks = [
                                    !empty($u->full_name),
                                    !empty($u->phone),
                                    !empty($u->email),
                                    !empty($summary),
                                    !empty($location),
                                    !empty($portfolio),
                                    !empty($u->photo),
                                    !empty($u->file_cv),
                                    count($skills) > 0,
                                    count($languages) > 0,
                                    count($experiences) > 0,
                                    count($educations) > 0,
                                ];
                                $completed = count(array_filter($checks));
                                $total = count($checks);
                                $percent = $total > 0 ? (int) round(($completed / $total) * 100) : 0;
                            @endphp
                            <div class="d-flex align-items-center gap-2 mt-2">
                                <div class="progress flex-grow-1" style="height:8px;">
                                    <div class="progress-bar" role="progressbar" style="width:{{ $percent }}%">
                                    </div>
                                </div>
                                <div class="small fw-semibold">{{ $percent }}%</div>
                            </div>
                        </div>

                        @php
                            $missing = [];
                            if (empty($summary)) {
                                $missing[] = 'Ringkasan';
                            }
                            if (empty($portfolio)) {
                                $missing[] = 'Portofolio';
                            }
                            if (count($skills) === 0) {
                                $missing[] = 'Keahlian';
                            }
                            if (count($experiences) === 0) {
                                $missing[] = 'Pengalaman';
                            }
                            if (count($educations) === 0) {
                                $missing[] = 'Pendidikan';
                            }
                            if (empty($u->photo)) {
                                $missing[] = 'Foto profil';
                            }
                            if (empty($u->file_cv)) {
                                $missing[] = 'CV';
                            }
                        @endphp

                        <div class="border rounded-3 p-3">
                            <div class="text-muted small">Tips</div>
                            <div class="small mt-1">
                                @if (count($missing) > 0)
                                    Lengkapi: {{ implode(', ', array_slice($missing, 0, 3)) }}.
                                @else
                                    Profil Anda sudah lengkap. Pertahankan!
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="offcanvas offcanvas-end profile-offcanvas" tabindex="-1" id="offcanvasSummary"
        aria-labelledby="offcanvasSummaryLabel">
        <div class="offcanvas-header">
            <h4 class="mb-0 fw-bold" id="offcanvasSummaryLabel">Ubah ringkasan pribadi</h4>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <form action="{{ url('edit-profile-user/' . optional($user)->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="form-label">Ringkasan</label>
                    <textarea class="form-control" name="summary" rows="5" placeholder="Tulis ringkasan singkat">{{ old('summary', $summary) }}</textarea>
                </div>
                <div class="d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="offcanvas">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
    <div class="offcanvas offcanvas-end profile-offcanvas" tabindex="-1" id="offcanvasExperience"
        aria-labelledby="offcanvasExperienceLabel">
        <div class="offcanvas-header">
            <h4 class="mb-0 fw-bold" id="offcanvasExperienceLabel">Kelola pengalaman kerja</h4>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <form action="{{ url('edit-profile-user/' . optional($user)->id) }}" method="POST" id="formExperience">
                @csrf
                @method('PUT')
                <input type="hidden" name="item_type" value="experience">
                <input type="hidden" name="item_mode" id="exp_mode" value="create">
                <input type="hidden" name="item_index" id="exp_index" value="">
                <div class="mb-3">
                    <label class="form-label">Nama jabatan</label>
                    <input type="text" class="form-control" id="experience_title" name="experience_title"
                        placeholder="Contoh: Software Engineer">
                </div>
                <div class="mb-3">
                    <label class="form-label">Perusahaan</label>
                    <input type="text" class="form-control" id="experience_company" name="experience_company"
                        placeholder="Nama perusahaan">
                </div>
                <div class="mb-3">
                    <label class="form-label">Periode</label>
                    <input type="text" class="form-control" id="experience_period" name="experience_period"
                        placeholder="Contoh: 2022 - Sekarang">
                </div>
                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <textarea class="form-control" id="experience_desc" name="experience_desc" rows="4"
                        placeholder="Ringkas deskripsi pekerjaan"></textarea>
                </div>
                <div class="d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="offcanvas">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
    <div class="offcanvas offcanvas-end profile-offcanvas" tabindex="-1" id="offcanvasEducation"
        aria-labelledby="offcanvasEducationLabel">
        <div class="offcanvas-header">
            <h4 class="mb-0 fw-bold" id="offcanvasEducationLabel">Kelola pendidikan</h4>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <form action="{{ url('edit-profile-user/' . optional($user)->id) }}" method="POST" id="formEducation">
                @csrf
                @method('PUT')
                <input type="hidden" name="item_type" value="education">
                <input type="hidden" name="item_mode" id="edu_mode" value="create">
                <input type="hidden" name="item_index" id="edu_index" value="">
                <div class="mb-3">
                    <label class="form-label">Gelar</label>
                    <input type="text" class="form-control" id="education_degree" name="education_degree"
                        placeholder="Contoh: Sarjana Sistem Informasi">
                </div>
                <div class="mb-3">
                    <label class="form-label">Institusi</label>
                    <input type="text" class="form-control" id="education_school" name="education_school"
                        placeholder="Nama kampus/sekolah">
                </div>
                <div class="mb-3">
                    <label class="form-label">Periode</label>
                    <input type="text" class="form-control" id="education_period" name="education_period"
                        placeholder="Contoh: Selesai 2025">
                </div>
                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <textarea class="form-control" id="education_desc" name="education_desc" rows="4"
                        placeholder="Ringkas deskripsi pendidikan"></textarea>
                </div>
                <div class="d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="offcanvas">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
    <div class="offcanvas offcanvas-end profile-offcanvas" tabindex="-1" id="offcanvasCertifications"
        aria-labelledby="offcanvasCertificationsLabel">
        <div class="offcanvas-header">
            <h4 class="mb-0 fw-bold" id="offcanvasCertificationsLabel">Kelola lisensi & sertifikasi</h4>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <form action="{{ url('edit-profile-user/' . optional($user)->id) }}" method="POST" id="formCertifications">
                @csrf
                @method('PUT')
                <input type="hidden" name="item_type" value="certifications">
                <input type="hidden" name="item_mode" id="cert_mode" value="create">
                <input type="hidden" name="item_index" id="cert_index" value="">
                <div class="mb-3">
                    <label class="form-label">Nama sertifikasi</label>
                    <input type="text" class="form-control" id="cert_name" name="cert_name"
                        placeholder="Contoh: AWS Certified">
                </div>
                <div class="mb-3">
                    <label class="form-label">Penerbit</label>
                    <input type="text" class="form-control" id="cert_issuer" name="cert_issuer"
                        placeholder="Contoh: Amazon Web Services">
                </div>
                <div class="mb-3">
                    <label class="form-label">Masa berlaku / kedaluwarsa</label>
                    <input type="text" class="form-control" id="cert_exp" name="cert_exp"
                        placeholder="Contoh: Kedaluwarsa: Jun 2025">
                </div>
                <div class="d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="offcanvas">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
    <div class="offcanvas offcanvas-end profile-offcanvas" tabindex="-1" id="offcanvasSkills"
        aria-labelledby="offcanvasSkillsLabel">
        <div class="offcanvas-header">
            <h4 class="mb-0 fw-bold" id="offcanvasSkillsLabel">Kelola keahlian</h4>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <form action="{{ url('edit-profile-user/' . optional($user)->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="form-label">Keahlian (pisahkan dengan koma)</label>
                    <input type="text" class="form-control" name="skills_text" placeholder="Contoh: Laravel, React"
                        value="{{ implode(', ', $skills) }}">
                </div>
                <div class="d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="offcanvas">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
    <div class="offcanvas offcanvas-end profile-offcanvas" tabindex="-1" id="offcanvasLanguages"
        aria-labelledby="offcanvasLanguagesLabel">
        <div class="offcanvas-header">
            <h4 class="mb-0 fw-bold" id="offcanvasLanguagesLabel">Kelola bahasa</h4>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <form action="{{ url('edit-profile-user/' . optional($user)->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="form-label">Bahasa (pisahkan dengan koma)</label>
                    <input type="text" class="form-control" name="languages_text"
                        placeholder="Contoh: Indonesia, Inggris" value="{{ implode(', ', $languages) }}">
                </div>
                <div class="d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="offcanvas">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
    <div class="offcanvas offcanvas-end profile-offcanvas" tabindex="-1" id="offcanvasPreferences"
        aria-labelledby="offcanvasPreferencesLabel">
        <div class="offcanvas-header">
            <h4 class="mb-0 fw-bold" id="offcanvasPreferencesLabel">Ubah preferensi</h4>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <div class="mb-3">
                <label class="form-label">Preferensi</label>
                <input type="text" class="form-control" placeholder="Contoh: Full-time, Remote">
            </div>
            <div class="d-flex justify-content-end gap-2">
                <button class="btn btn-outline-secondary" data-bs-dismiss="offcanvas">Batal</button>
                <button class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>

    {{-- ===========================
|  OFFCANVAS (SLIDER KANAN)
|  Muncul saat klik "Ubah" di banner
=========================== --}}
    <div class="offcanvas offcanvas-end profile-offcanvas" tabindex="-1" id="offcanvasPersonalInfo"
        aria-labelledby="offcanvasPersonalInfoLabel">

        <div class="offcanvas-header">
            <h4 class="mb-0 fw-bold" id="offcanvasPersonalInfoLabel">Ubah informasi pribadi</h4>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        @if ($errors->any())
            <div class="alert alert-danger">

                <ul>

                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach

                </ul>

            </div>
        @endif


        <div class="offcanvas-body">
            {{-- Ganti action route sesuai punyamu --}}
            <form action="{{ url('edit-profile-user/' . optional($user)->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nama depan</label>
                        <input type="text" class="form-control" name="first_name"
                            value="{{ old('first_name', $firstName) }}" placeholder="Nama depan">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Nama belakang</label>
                        <input type="text" class="form-control" name="last_name"
                            value="{{ old('last_name', $lastName) }}" placeholder="Nama belakang">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Tanggal Lahir</label>
                        <input type="text" id="tanggal_lahir" class="form-control" name="tanggal_lahir"
                            value="{{ old('tanggal_lahir', $user->tanggal_lahir) }}" placeholder="Pilih Tanggal">
                    </div>

                    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
                    <script>
                        // Inisialisasi Flatpickr
                        flatpickr("#tanggal_lahir", {
                            dateFormat: "d-m-Y", // Format yang akan dikirim ke server & ditampilkan (15-01-2026)
                            // Jika database Anda butuh format Y-m-d tapi tampilan ingin d-m-Y:
                            // altInput: true,
                            // altFormat: "d F Y", // Tampilan user: 15 Januari 2026
                            // dateFormat: "Y-m-d", // Data asli yang dikirim ke database: 2026-01-15
                        });
                    </script>

                    <div class="col-md-6">
                        <label class="form-label">Jenis Kelamin</label>
                        <select class="form-select" name="jenis_kelamin">
                            <option value="" disabled selected>Pilih Jenis Kelamin</option>
                            <option value="Laki-laki"
                                {{ old('jenis_kelamin', $user->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>
                                Laki-laki
                            </option>
                            <option value="Perempuan"
                                {{ old('jenis_kelamin', $user->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>
                                Perempuan
                            </option>
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Lokasi rumah</label>
                        <input type="text" class="form-control" name="location"
                            value="{{ old('location', $location) }}" placeholder="Contoh: Telanaipura, Jambi">
                    </div>

                    <div class="col-12 mt-2">
                        <label class="form-label">Nomor telepon <span class="text-muted">(direkomendasikan)</span></label>
                        <div class="row g-2">
                            <div class="col-md-5">
                                <select class="form-select" name="phone_country">
                                    <option value="+62" selected>Indonesia (+62)</option>
                                    <option value="+60">Malaysia (+60)</option>
                                    <option value="+65">Singapore (+65)</option>
                                </select>
                            </div>
                            <div class="col-md-7">
                                <input type="text" class="form-control" name="phone"
                                    value="{{ old('phone', $phone) }}" placeholder="82280955152">
                            </div>
                        </div>
                    </div>

                    <div class="col-12 mt-2">
                        <label class="form-label">Alamat email</label>
                        <input type="email" class="form-control" name="email"
                            value="{{ old('email', optional($user)->email) }}" placeholder="nama@email.com">
                        @error('email')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 mt-2">
                        <label class="form-label">Link portofolio</label>
                        <input type="text" class="form-control" name="portfolio_url"
                            value="{{ old('portfolio_url', $portfolio) }}" placeholder="https://...">
                        @error('portfolio_url')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 mt-2">
                        <label class="form-label">Nama lengkap</label>
                        <input type="text" class="form-control" name="full_name"
                            value="{{ old('full_name', optional($user)->full_name) }}" placeholder="Nama lengkap">
                        @error('full_name')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12 mt-2">
                        <label class="form-label">Alamat</label>
                        <input type="text" class="form-control" name="address"
                            value="{{ old('address', optional($user)->address) }}" placeholder="Alamat lengkap">
                        @error('address')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12 mt-2">
                        <label class="form-label">Foto profil</label>
                        <input type="file" class="form-control" name="photo" accept="image/*">
                        @error('photo')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12 mt-2">
                        <label class="form-label">File CV (PDF)</label>
                        <input type="file" class="form-control" name="file_cv" accept="application/pdf">
                        @error('file_cv')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex align-items-center gap-3 mt-4">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn btn-link text-decoration-none" data-bs-dismiss="offcanvas">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        document.addEventListener('show.bs.offcanvas', function(ev) {
            var trigger = ev.relatedTarget;
            if (!trigger) return;
            var type = trigger.getAttribute('data-type');
            var mode = trigger.getAttribute('data-mode') || 'create';
            var idx = trigger.getAttribute('data-index') || '';
            if (type === 'experience') {
                document.getElementById('exp_mode').value = mode;
                document.getElementById('exp_index').value = idx;
                document.getElementById('experience_title').value = trigger.getAttribute('data-title') || '';
                document.getElementById('experience_company').value = trigger.getAttribute('data-company') || '';
                document.getElementById('experience_period').value = trigger.getAttribute('data-period') || '';
                document.getElementById('experience_desc').value = trigger.getAttribute('data-desc') || '';
            }
            if (type === 'education') {
                document.getElementById('edu_mode').value = mode;
                document.getElementById('edu_index').value = idx;
                document.getElementById('education_degree').value = trigger.getAttribute('data-degree') || '';
                document.getElementById('education_school').value = trigger.getAttribute('data-school') || '';
                document.getElementById('education_period').value = trigger.getAttribute('data-period') || '';
                document.getElementById('education_desc').value = trigger.getAttribute('data-desc') || '';
            }
            if (type === 'certifications') {
                document.getElementById('cert_mode').value = mode;
                document.getElementById('cert_index').value = idx;
                document.getElementById('cert_name').value = trigger.getAttribute('data-name') || '';
                document.getElementById('cert_issuer').value = trigger.getAttribute('data-issuer') || '';
                document.getElementById('cert_exp').value = trigger.getAttribute('data-exp') || '';
            }
        });
    </script>
@endpush
