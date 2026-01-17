<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SaktiJob</title>

    <link rel="shortcut icon" href="{{ asset('assets/admindash/assets/compiled/svg/favicon.svg') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('assets/admindash/assets/compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admindash/assets/compiled/css/app-dark.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admindash/assets/compiled/css/auth.css') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Inter:wght@700;800&display=swap"
        rel="stylesheet">

    <style>
        :root {
            --primary: #0b2447;
            --accent: #F28C28;
        }

        body {
            font-family: 'Heebo', 'Inter', sans-serif;
        }

        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
        }

        .btn-primary:hover {
            filter: brightness(1.05);
        }

        .btn-outline-primary {
            color: var(--primary);
            border-color: var(--primary);
        }

        .btn-outline-primary:hover {
            background-color: var(--primary);
            color: #fff;
        }

        .text-primary {
            color: var(--primary) !important;
        }

        /* panel kanan biar rapi & konsisten */
        .right-panel {
            background: linear-gradient(145deg, rgba(242, 140, 40, 0.10) 0%, rgba(11, 36, 71, 0.08) 100%);
            border-left: 1px solid #eee;
            position: relative;
            overflow: hidden;
        }

        .right-panel:before {
            content: "";
            position: absolute;
            width: 520px;
            height: 520px;
            top: -260px;
            right: -260px;
            background: radial-gradient(circle, rgba(11, 36, 71, 0.12), transparent 60%);
        }

        .right-panel:after {
            content: "";
            position: absolute;
            width: 420px;
            height: 420px;
            bottom: -210px;
            left: -210px;
            background: radial-gradient(circle, rgba(242, 140, 40, 0.14), transparent 60%);
        }

        .choice-card {
            border: 1px solid rgba(15, 23, 42, 0.08);
            border-radius: 16px;
            transition: .15s ease;
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(6px);
        }

        .choice-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 34px rgba(15, 23, 42, 0.12);
            border-color: rgba(11, 36, 71, 0.18);
        }

        .badge-soft {
            background: rgba(11, 36, 71, 0.10);
            color: var(--primary);
            border: 1px solid rgba(11, 36, 71, 0.12);
        }
    </style>
</head>

<body class="bg-light">
    <div id="auth">
        <div class="row min-vh-100 g-0 justify-content-center align-items-center">
            <div class="col-lg-10 col-12 mx-auto">
                <div class="row g-0 rounded-3 overflow-hidden shadow-lg" style="background:#fff;">

                    {{-- LEFT: FORM LOGIN --}}
                    <div class="col-lg-5 col-12 d-flex align-items-center justify-content-center">
                        <div id="auth-left"
                            style="
                            max-width:420px;
                            width:100%;
                            background:#ffffff;
                            border-radius:18px;
                            padding:32px 28px;
                            box-shadow:0 18px 45px rgba(15, 23, 42, 0.16);
                         ">

                            {{-- Brand kecil di atas --}}
                            <div class="d-flex align-items-center mb-4">
                                <div class="d-flex align-items-center justify-content-center rounded-circle"
                                    style="width:38px;height:38px;background:rgba(11,36,71,0.08);">
                                    <span class="fw-bold text-primary">S</span>
                                </div>
                                <div class="ms-2">
                                    <div class="fw-semibold">SaktiJob</div>
                                    <small class="text-muted">Portal Karier & Lowongan</small>
                                </div>
                            </div>

                            <h1 class="auth-title fs-4 mb-1" style="color:#0b2447;">Masuk ke akun Anda</h1>
                            <p class="auth-subtitle mb-4 fs-6 text-muted">
                                Gunakan email dan kata sandi terdaftar untuk melanjutkan.
                            </p>

                            {{-- Alert session --}}
                            @session('loginFailed')
                                <div class="alert alert-danger py-2 small" role="alert">
                                    {{ session('loginFailed') }}
                                </div>
                            @endsession

                            @session('success')
                                <div class="alert alert-success py-2 small" role="alert">
                                    {{ session('success') }}
                                </div>
                            @endsession

                            @session('swal')
                                <script src="{{ asset('assets/admindash/assets/extensions/sweetalert2/sweetalert2.min.js') }}"></script>
                                <script>
                                    const swalData = @json(session('swal'));
                                    Swal.fire({
                                        icon: swalData.icon,
                                        title: swalData.title,
                                        text: swalData.text,
                                        timer: 3000
                                    });
                                </script>
                            @endsession

                            <form action="{{ url('login') }}" method="post" class="mt-3">
                                @csrf

                                <div class="form-group position-relative has-icon-left mb-3">
                                    <input type="email" name="email" class="form-control" placeholder="Email"
                                        required>
                                    <div class="form-control-icon">
                                        <i class="bi bi-envelope"></i>
                                    </div>
                                </div>

                                <div class="form-group position-relative has-icon-left mb-2">
                                    <input type="password" name="password" class="form-control" placeholder="Kata Sandi"
                                        required>
                                    <div class="form-control-icon">
                                        <i class="bi bi-shield-lock"></i>
                                    </div>
                                </div>

                                {{-- <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div class="form-check small">
                                        <input class="form-check-input" type="checkbox" value="1" id="remember"
                                            name="remember">
                                        <label class="form-check-label text-muted" for="remember">Ingat saya</label>
                                    </div>
                                    <a class="small text-primary text-decoration-none" href="auth-forgot-password.html">
                                        Lupa password?
                                    </a>
                                </div> --}}

                                <button type="submit" class="btn btn-primary btn-block shadow-lg mt-4">Masuk</button>
                            </form>

                            {{-- kecilkan CTA daftar di kiri (opsional) --}}
                            <div class="text-center mt-4">
                                <p class="text-gray-600 fs-6 mb-1">Belum punya akun?</p>
                                <small class="text-muted">Pilih tipe pendaftaran di panel kanan.</small>
                            </div>

                            <p class="text-center text-muted small mt-4 mb-0">
                                Dengan masuk, Anda menyetujui <a href="#" class="text-decoration-none">Syarat &
                                    Ketentuan</a>.
                            </p>
                        </div>
                    </div>

                    {{-- RIGHT: REGISTER CHOOSER --}}
                    <div class="col-lg-7 d-none d-lg-flex align-items-center justify-content-center right-panel">
                        <div class="px-5" style="position:relative;z-index:1;max-width:560px;width:100%;">
                            <div class="mb-4">
                                <span class="badge badge-soft rounded-pill px-3 py-2">
                                    Register
                                </span>
                                <h2 class="fw-bold mt-3 mb-2" style="color:var(--primary);">
                                    Daftar di <span style="color:var(--accent);">SaktiJob</span>
                                </h2>
                                <p class="text-muted mb-0" style="font-size:0.98rem;">
                                    Sebelum isi form, pilih dulu jenis akun yang ingin Anda buat.
                                </p>
                            </div>

                            <div class="row g-3">
                                {{-- PILIH: PENCARI KERJA --}}
                                <div class="col-md-6">
                                    <a href="{{ url('register-user') }}" class="text-decoration-none">
                                        <div class="choice-card p-4 h-100">
                                            <div class="d-flex align-items-center justify-content-between mb-3">
                                                <span class="badge text-bg-success">Pencari Kerja</span>
                                                <i class="bi bi-arrow-right-short fs-4 text-muted"></i>
                                            </div>
                                            <div class="fw-semibold" style="color:var(--primary);">
                                                Daftar sebagai Pencari Kerja
                                            </div>
                                            <div class="text-muted small mt-2">
                                                Buat profil, unggah CV, simpan lowongan, dan lamar dalam beberapa klik.
                                            </div>

                                            <div class="mt-3">
                                                <button type="button" class="btn btn-outline-success w-100">
                                                    Lanjut ke Form Pencari Kerja
                                                </button>
                                            </div>
                                        </div>
                                    </a>
                                </div>

                                {{-- PILIH: PERUSAHAAN --}}
                                <div class="col-md-6">
                                    <a href="{{ url('register-company') }}" class="text-decoration-none">
                                        <div class="choice-card p-4 h-100">
                                            <div class="d-flex align-items-center justify-content-between mb-3">
                                                <span class="badge text-bg-primary">Pemberi Kerja</span>
                                                <i class="bi bi-arrow-right-short fs-4 text-muted"></i>
                                            </div>
                                            <div class="fw-semibold" style="color:var(--primary);">
                                                Daftar sebagai Pemberi Kerja
                                            </div>
                                            <div class="text-muted small mt-2">
                                                Pasang lowongan, kelola kandidat, dan pantau proses rekrutmen.
                                            </div>

                                            <div class="mt-3">
                                                <button type="button" class="btn btn-outline-primary w-100">
                                                    Lanjut ke Form Pemberi Kerja
                                                </button>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>

                            <div class="mt-4 d-flex flex-wrap gap-2">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="rounded-circle"
                                        style="width:8px;height:8px;background:var(--accent);display:inline-block;"></span>
                                    <span class="text-muted small">Pilih tipe akun → lanjut isi form</span>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="rounded-circle"
                                        style="width:8px;height:8px;background:var(--primary);display:inline-block;"></span>
                                    <span class="text-muted small">Tidak ada form register di halaman ini</span>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/admindash/assets/compiled/js/app.js') }}"></script>
</body>

</html>
