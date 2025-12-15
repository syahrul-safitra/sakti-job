<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>SaktiJob - Portal Pencari Kerja</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="{{ asset('assets/jobentry/img/favicon.png') }}" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Inter:wght@700;800&display=swap"
        rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="{{ asset('assets/jobentry/lib/animate/animate.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/jobentry/lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="{{ asset('assets/jobentry/css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="{{ asset('assets/jobentry/css/style.css') }}" rel="stylesheet">
</head>

<body>
    <div class="container-fliud bg-white p-0">

        <!-- Spinner Start -->
        <div id="spinner"
            class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Memuat...</span>
            </div>
        </div>
        <!-- Spinner End -->


        <!-- Navbar Start -->
        @php
            $user = Auth::guard('user')->user();
            $isLoggedIn = !is_null($user);
            $initial = 'U';

            if ($isLoggedIn) {
                $nameForInitial = $user->full_name ?? ($user->name ?? $user->email);
                $initial = strtoupper(substr($nameForInitial, 0, 1));
            }
        @endphp

        <nav class="navbar navbar-expand-lg bg-white navbar-light shadow sticky-top p-0">
            <a href="{{ url('/') }}" class="navbar-brand d-flex align-items-center text-center py-0 px-4 px-lg-5">
                <h1 class="m-0 text-primary">
                    Sakti<span style="color:#F28C28">Job</span>
                </h1>
            </a>

            <button type="button" class="navbar-toggler me-4" data-bs-toggle="collapse"
                data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarCollapse">
                {{-- LEFT MENU --}}
                <div class="navbar-nav ms-auto p-4 p-lg-0">
                    @if ($isLoggedIn)
                        <a href="{{ url('/') }}"
                            class="nav-item nav-link {{ request()->is('/') ? 'active' : '' }}">
                            Beranda
                        </a>
                        <a href="{{ url('lowongan') }}"
                            class="nav-item nav-link {{ request()->is('lowongan*') ? 'active' : '' }}">
                            Lowongan
                        </a>
                        <a href="#footer" class="nav-item nav-link">
                            Hubungi Kami
                        </a>
                    @else
                        {{-- Guest bisa tetap lihat menu umum kalau mau --}}
                        <a href="{{ url('/') }}"
                            class="nav-item nav-link {{ request()->is('/') ? 'active' : '' }}">
                            Beranda
                        </a>
                        <a href="{{ url('lowongan') }}"
                            class="nav-item nav-link {{ request()->is('lowongan*') ? 'active' : '' }}">
                            Lowongan
                        </a>
                        <a href="#footer" class="nav-item nav-link">
                            Hubungi Kami
                        </a>
                    @endif

                    {{-- TOMBOL LOGIN DI MOBILE (dalam collapse) --}}
                    @if (!$isLoggedIn)
                        <a href="{{ url('login') }}" class="btn btn-primary rounded-0 mt-3 d-lg-none w-100">
                            Masuk / Daftar
                            <i class="fa fa-arrow-right ms-2"></i>
                        </a>
                    @else
                        {{-- QUICK ACCOUNT MENU DI MOBILE (opsional, biar tetap bisa akses di HP) --}}
                        <div class="d-lg-none border-top mt-3 pt-3">
                            <div class="d-flex align-items-center mb-2">
                                <div class="d-flex align-items-center justify-content-center fw-bold me-2"
                                    style="width:36px;height:36px;border-radius:50%;background:#0d6efd1a;color:#0d6efd;">
                                    {{ $initial }}
                                </div>
                                <div>
                                    <div class="fw-semibold small">
                                        {{ $user->full_name ?? ($user->name ?? 'User') }}
                                    </div>
                                    <div class="text-muted small text-truncate">
                                        {{ $user->email }}
                                    </div>
                                </div>
                            </div>

                            <a class="dropdown-item px-0 mb-1" href="{{ url('user-profile') }}">Lihat profil</a>
                            <a class="dropdown-item px-0 mb-2" href="{{ url('user/applications') }}">Lamaran kerja</a>
                            <a class="dropdown-item px-0 mb-1" href="{{ url('user/saved-jobs') }}">Lowongan
                                tersimpan</a>
                            <a class="dropdown-item px-0 mb-2" href="{{ url('user/settings') }}">Pengaturan</a>

                            <form action="{{ url('logout') }}" method="POST" class="mt-2">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                                    Logout
                                </button>
                            </form>
                        </div>
                    @endif
                </div>

                {{-- RIGHT SIDE (DESKTOP): AVATAR + DROPDOWN + LOGOUT / LOGIN BUTTON --}}
                @if ($isLoggedIn)
                    <div class="d-none d-lg-flex align-items-center me-4">
                        {{-- DROPDOWN USER --}}
                        <div class="dropdown">
                            <button class="btn p-0 bg-transparent border-0 d-flex align-items-center" id="userDropdown"
                                type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                {{-- Avatar huruf depan nama --}}
                                <div class="d-flex align-items-center justify-content-center fw-bold"
                                    style="width:36px;height:36px;border-radius:50%;background:#0d6efd1a;color:#0d6efd;">
                                    {{ $initial }}
                                </div>
                                <span class="ms-2 small text-muted">
                                    {{ $user->full_name ?? ($user->name ?? 'User') }}
                                </span>
                                <i class="bi bi-chevron-down ms-2 small"></i>
                            </button>

                            <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="userDropdown"
                                style="min-width: 230px;">
                                {{-- Header akun --}}
                                <li class="px-3 pt-2 pb-2 border-bottom">
                                    <div class="fw-semibold small mb-0">
                                        {{ $user->full_name ?? ($user->name ?? 'User') }}
                                    </div>
                                    <div class="text-muted small text-truncate">
                                        {{ $user->email }}
                                    </div>
                                </li>

                                {{-- Menu items --}}
                                <li>
                                    <a class="dropdown-item d-flex align-items-center"
                                        href="{{ url('user-profile') }}">
                                        <i class="bi bi-person me-2"></i>
                                        <span>Lihat profil</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center"
                                        href="{{ url('user/saved-jobs') }}">
                                        <i class="bi bi-bookmark-star me-2"></i>
                                        <span>Lowongan tersimpan</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center"
                                        href="{{ url('user-history') }}">
                                        <i class="bi bi-file-earmark-text me-2"></i>
                                        <span>Lamaran kerja</span>
                                    </a>
                                </li>

                                <li>
                                    <hr class="dropdown-divider">
                                </li>

                                {{-- <a class="dropdown-item d-flex align-items-center" href="{{ url('user-pdf/') }}">

                                </a> --}}

                                <form action="{{ url('/user-pdf/' . Auth::guard('user')->user()->id) }}"
                                    method="POST" class="dropdown-item d-flex align-items-center">
                                    @csrf
                                    <button class="btn "><i class="bi bi-person-circle me-2"></i>Cetak
                                        Profile</button>
                                </form>

                                </li>
                            </ul>
                        </div>

                        {{-- LOGOUT BUTTON DESKTOP --}}
                        <form action="{{ url('logout') }}" method="POST" class="m-0 ms-3">
                            @csrf
                            <button type="submit" class="btn btn-primary rounded-0 py-4 px-lg-5">
                                Logout <i class="fa fa-arrow-right ms-3"></i>
                            </button>
                        </form>
                    </div>
                @else
                    {{-- BUTTON LOGIN DESKTOP --}}
                    <a href="{{ url('login') }}" class="btn btn-primary rounded-0 py-4 px-lg-5 d-none d-lg-block">
                        Masuk / Daftar
                        <i class="fa fa-arrow-right ms-3"></i>
                    </a>
                @endif

            </div>
        </nav>
        <!-- Navbar End -->



        @yield('content')

        <!-- Back to Top -->
        <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>

        <!-- Footer Start -->
        <div id="footer" class="container-fluid bg-dark text-white-50 footer pt-5 mt-5 wow fadeIn"
            data-wow-delay="0.1s">
            <div class="container py-5">
                <div class="row g-5">
                    <div class="col-lg-4 col-md-6">
                        <h5 class="text-white mb-4">Tentang SaktiJob</h5>
                        <p class="mb-2">Portal pencari kerja untuk menemukan peluang terbaik dan mempercepat proses
                            rekrutmen.</p>
                        <a class="btn btn-link text-white-50" href="{{ url('lowongan') }}">Mulai Cari Lowongan</a>
                        <a class="btn btn-link text-white-50" href="{{ url('register-company') }}">Untuk
                            Perusahaan</a>
                        <a class="btn btn-link text-white-50" href="#">Kebijakan Privasi</a>
                        <a class="btn btn-link text-white-50" href="#">Syarat & Ketentuan</a>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <h5 class="text-white mb-4">Tautan Cepat</h5>
                        <a class="btn btn-link text-white-50" href="{{ url('lowongan') }}">Lowongan</a>
                        <a class="btn btn-link text-white-50" href="#">Panduan Karier</a>
                        <a class="btn btn-link text-white-50" href="#">Tips Wawancara</a>
                        <a class="btn btn-link text-white-50" href="#">Pusat Bantuan</a>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <h5 class="text-white mb-4">Kontak</h5>
                        <p class="mb-2"><i class="fa fa-map-marker-alt me-3"></i>Jambi, Indonesia</p>
                        <p class="mb-2"><i class="fa fa-phone-alt me-3"></i>+62 000 0000 000</p>
                        <p class="mb-2"><i class="fa fa-envelope me-3"></i>info@saktijob.com</p>
                        <div class="d-flex pt-2">
                            <a class="btn btn-outline-light btn-social" href=""><i
                                    class="fab fa-twitter"></i></a>
                            <a class="btn btn-outline-light btn-social" href=""><i
                                    class="fab fa-facebook-f"></i></a>
                            <a class="btn btn-outline-light btn-social" href=""><i
                                    class="fab fa-youtube"></i></a>
                            <a class="btn btn-outline-light btn-social" href=""><i
                                    class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                    {{-- <div class="col-lg-3 col-md-6">
                        <h5 class="text-white mb-4">Newsletter</h5>
                        <p>Dapatkan info lowongan dan tips karier terbaru langsung di email Anda.</p>
                        <div class="position-relative mx-auto" style="max-width: 400px;">
                            <input class="form-control bg-transparent w-100 py-3 ps-4 pe-5" type="text"
                                placeholder="Email Anda">
                            <button type="button"
                                class="btn btn-primary py-2 position-absolute top-0 end-0 mt-2 me-2">Langganan</button>
                        </div>
                    </div> --}}
                </div>
            </div>
            <div class="container">
                <div class="copyright">
                    <div class="row">
                        <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                            &copy; <a class="border-bottom" href="#">SaktiJob</a>
                        </div>
                        <div class="col-md-6 text-center text-md-end">
                            <div class="footer-menu">
                                <a href="{{ url('/') }}">Beranda</a>
                                <a href="{{ url('lowongan') }}">Lowongan</a>
                                <a href="#footer">Hubungi Kami</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer End -->
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('assets/jobentry/lib/wow/wow.min.js') }}"></script>
    <script src="{{ asset('assets/jobentry/lib/easing/easing.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/waypoints/4.0.1/jquery.waypoints.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

    <!-- Template Javascript -->
    <script src="{{ asset('assets/jobentry/js/main.js') }}"></script>
</body>

</html>
