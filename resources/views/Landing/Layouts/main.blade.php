<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <title>SaktiJob - Portal Pencari Kerja</title>
        <meta content="width=device-width, initial-scale=1.0" name="viewport">
        <meta content="" name="keywords">
        <meta content="" name="description">

        <!-- Favicon -->
        <link href="{{ asset("assets/jobentry/img/favicon.ico") }}" rel="icon">

        <!-- Google Web Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link
            href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Inter:wght@700;800&display=swap"
            rel="stylesheet">

        <!-- Icon Font Stylesheet -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

        <!-- Libraries Stylesheet -->
        <link href="{{ asset("assets/jobentry/lib/animate/animate.min.css") }}" rel="stylesheet">
        <link href="{{ asset("assets/jobentry/lib/owlcarousel/assets/owl.carousel.min.css") }}" rel="stylesheet">

        <!-- Customized Bootstrap Stylesheet -->
        <link href="{{ asset("assets/jobentry/css/bootstrap.min.css") }}" rel="stylesheet">

        <!-- Template Stylesheet -->
        <link href="{{ asset("assets/jobentry/css/style.css") }}" rel="stylesheet">
    </head>

    <body>
        <div class="container-fliud bg-white p-0">

            <!-- Spinner Start -->
            <div id="spinner"
                class="show position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center bg-white">
                <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                    <span class="sr-only">Memuat...</span>
                </div>
            </div>
            <!-- Spinner End -->

            <!-- Navbar Start -->
            <nav class="navbar navbar-expand-lg navbar-light sticky-top bg-white p-0 shadow">
                <a href="index.html" class="navbar-brand d-flex align-items-center px-lg-5 px-4 py-0 text-center">
                    <h1 class="text-primary m-0">Sakti<span style="color:#F28C28">Job</span></h1>
                </a>
                <button type="button" class="navbar-toggler me-4" data-bs-toggle="collapse"
                    data-bs-target="#navbarCollapse">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="navbar-collapse collapse" id="navbarCollapse">
                    <div class="navbar-nav p-lg-0 ms-auto p-4">

                        @if (Auth::guard("user")->check())
                            <a href="{{ url("/") }}" class="nav-item nav-link active">Beranda</a>
                            <a href="{{ url("lowongan") }}" class="nav-item nav-link">Lowongan</a>
                            <a href="#" class="nav-item nav-link">Hubungi Kami</a>
                            <a href="{{ url("user-profile") }}" class="nav-item nav-link">Profile</a>
                            <a href="{{ url("user-history") }}" class="nav-item nav-link">Riwayat</a>
                            <div class="nav-item dropdown me-2">
                                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#"
                                    id="userMenu" role="button" data-bs-toggle="dropdown" aria-expanded="false">

                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
                                    <li>
                                        <form action="{{ url("logout") }}" method="POST">
                                            @csrf
                                            <button class="dropdown-item">Logout</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        @else
                            <a href="{{ url("login") }}"
                                class="btn btn-primary rounded-0 px-lg-5 d-none d-lg-block py-4">Masuk/Daftar<i
                                    class="fa fa-arrow-right ms-3"></i></a>
                        @endif

                    </div>
                </div>
            </nav>
            <!-- Navbar End -->

            @yield("content")

            <!-- Back to Top -->
            <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i
                    class="bi bi-arrow-up"></i></a>

            <!-- Footer Start -->
            <div class="container-fluid bg-dark text-white-50 footer wow fadeIn mt-5 pt-5" data-wow-delay="0.1s">
                <div class="container py-5">
                    <div class="row g-5">
                        <div class="col-lg-3 col-md-6">
                            <h5 class="mb-4 text-white">Perusahaan</h5>
                            <a class="btn btn-link text-white-50" href="">Tentang Kami</a>
                            <a class="btn btn-link text-white-50" href="">Hubungi Kami</a>
                            <a class="btn btn-link text-white-50" href="">Layanan Kami</a>
                            <a class="btn btn-link text-white-50" href="">Kebijakan Privasi</a>
                            <a class="btn btn-link text-white-50" href="">Syarat & Ketentuan</a>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <h5 class="mb-4 text-white">Tautan Cepat</h5>
                            <a class="btn btn-link text-white-50" href="">About Us</a>
                            <a class="btn btn-link text-white-50" href="">Contact Us</a>
                            <a class="btn btn-link text-white-50" href="">Our Services</a>
                            <a class="btn btn-link text-white-50" href="">Privacy Policy</a>
                            <a class="btn btn-link text-white-50" href="">Terms & Condition</a>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <h5 class="mb-4 text-white">Kontak</h5>
                            <p class="mb-2"><i class="fa fa-map-marker-alt me-3"></i>123 Street, New York, USA</p>
                            <p class="mb-2"><i class="fa fa-phone-alt me-3"></i>+012 345 67890</p>
                            <p class="mb-2"><i class="fa fa-envelope me-3"></i>info@example.com</p>
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
                        <div class="col-lg-3 col-md-6">
                            <h5 class="mb-4 text-white">Newsletter</h5>
                            <p>Dapatkan info lowongan terbaru dan tips karier langsung di email Anda.</p>
                            <div class="position-relative mx-auto" style="max-width: 400px;">
                                <input class="form-control w-100 bg-transparent py-3 pe-5 ps-4" type="text"
                                    placeholder="Email Anda">
                                <button type="button"
                                    class="btn btn-primary position-absolute end-0 top-0 me-2 mt-2 py-2">Langganan</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container">
                    <div class="copyright">
                        <div class="row">
                            <div class="col-md-6 text-md-start mb-md-0 mb-3 text-center">
                                &copy; <a class="border-bottom" href="#">SaktiJob</a>
                            </div>
                            <div class="col-md-6 text-md-end text-center">
                                <div class="footer-menu">
                                    <a href="{{ url("/") }}">Beranda</a>
                                    <a href="#">FAQ</a>
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
        <script src="{{ asset("assets/jobentry/lib/wow/wow.min.js") }}"></script>
        <script src="{{ asset("assets/jobentry/lib/easing/easing.min.js") }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/waypoints/4.0.1/jquery.waypoints.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

        <!-- Template Javascript -->
        <script src="{{ asset("assets/jobentry/js/main.js") }}"></script>
    </body>

</html>
