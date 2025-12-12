<!DOCTYPE html>
<html lang="id">

    <head>
        <meta charset="utf-8">
        <title>Detail Lowongan - SaktiJob</title>
        <meta content="width=device-width, initial-scale=1.0" name="viewport">
        <link href="{{ asset("assets/jobentry/img/favicon.ico") }}" rel="icon">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
        <link href="{{ asset("assets/jobentry/lib/animate/animate.min.css") }}" rel="stylesheet">
        <link href="{{ asset("assets/jobentry/css/bootstrap.min.css") }}" rel="stylesheet">
        <link href="{{ asset("assets/jobentry/css/style.css") }}" rel="stylesheet">
    </head>

    <body>
        <div class="container-fliud bg-white p-0">
            <!-- Navbar Start -->
            <!-- Navbar Start -->
            <nav class="navbar navbar-expand-lg navbar-light sticky-top bg-white p-0 shadow">
                <a href="{{ url("/") }}"
                    class="navbar-brand d-flex align-items-center px-lg-5 px-4 py-0 text-center">
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
            <!-- Navbar End -->

            <div class="container-fliud py-5">
                <div class="container">

                    @session("error")
                        <div class="alert alert-danger">
                            {{ session("error") }}
                        </div>
                    @endsession

                    <div class="row g-4">
                        <div class="col-lg-8">
                            <div class="rounded border p-4">
                                <div class="d-flex align-items-center mb-3">
                                    <img class="img-fluid flex-shrink-0 rounded border"
                                        src="{{ asset("FileUpload/" . $job->gambar) }}" alt=""
                                        style="width: 80px; height: 80px;">
                                    <div class="ps-4 text-start">
                                        <h3 class="mb-1">{{ $job->title }}</h3>
                                        <div class="text-muted small mb-2">{{ $job->company->name }}
                                        </div>
                                        <div class="mb-2">
                                            <span class="me-3"><i
                                                    class="fa fa-map-marker-alt text-primary me-2">{{ $job->location }}</span>
                                            <span class="me-3"><i
                                                    class="far fa-clock text-primary me-2"></i>{{ $job->employment_type }}</span>
                                            <span class="me-3"><i class="far fa-money-bill-alt text-primary me-2"></i>
                                                {{ "Rp ." . number_format($job->salary_min, 0, ",", ".") }} -
                                                {{ "Rp ." . number_format($job->salary_max, 0, ",", ".") }}
                                                per month
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <h5>Deskripsi Pekerjaan</h5>
                                    <div class="text-muted" style="white-space:pre-line">
                                        {!! $job->description !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="col-lg-4">
                            <div class="rounded border p-4">
                                <button>Lamar Sekarang</button>
                                <div class="text-muted small mt-3">Dipublikasikan:
                                    {{ $job->created_at->diffForHumans() }}</div>
                            </div>
                            <div class="mt-3 rounded border p-4">
                                <h6 class="mb-2">Tentang Perusahaan</h6>
                                <div class="text-muted" style="white-space:pre-line">{!! $job->company->description !!}</div>

                                @if ($job->company->link_website)
                                    <div class="mt-2"><a href="{{ $job->company->link_website }}"
                                            target="_blank">Website</a>
                                    </div>
                                @endif

                            </div>
                        </div> --}}

                        <div class="col-lg-4">
                            <div class="rounded border p-4">
                                <!-- Button trigger modal -->
                                <button class="btn btn-primary w-100" data-bs-toggle="modal"
                                    data-bs-target="#applyModal">
                                    Lamar Sekarang
                                </button>

                                <div class="text-muted small mt-3">
                                    Dipublikasikan: {{ $job->created_at->diffForHumans() }}
                                </div>
                            </div>

                            <div class="mt-3 rounded border p-4">
                                <h6 class="mb-2">Tentang Perusahaan</h6>
                                <div class="text-muted" style="white-space:pre-line">{!! $job->company->description !!}</div>

                                @if ($job->company->link_website)
                                    <div class="mt-2">
                                        <a href="{{ $job->company->link_website }}" target="_blank">Website</a>
                                    </div>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i
                    class="bi bi-arrow-up"></i></a>

            <div class="container-fluid bg-dark text-white-50 footer mt-5 pt-5">
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
        </div>

        <!-- Modal: Lamar Sekarang -->
        <div class="modal fade" id="applyModal" tabindex="-1" aria-labelledby="applyModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title" id="applyModalLabel">Lamar Pekerjaan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <form action="{{ url("/apply-job/" . $job->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ Auth::guard("user")->user()->id }}">
                        <input type="hidden" name="job_id" value="{{ $job->id }}">

                        <div class="modal-body">

                            <div class="mb-3">
                                <label class="form-label">Catatan / Cover Letter (Opsional)</label>
                                <textarea name="cover_letter" class="form-control" rows="4" maxlength="1000"
                                    placeholder="Tulis pesan untuk HR atau alasan melamar pekerjaan ini">{{ old("cover_letter") }}</textarea>
                            </div>

                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                Tutup
                            </button>
                            <button type="submit" class="btn btn-primary">
                                Kirim Lamaran
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
        <script src="{{ asset("assets/jobentry/lib/wow/wow.min.js") }}"></script>
        <script src="{{ asset("assets/jobentry/lib/easing/easing.min.js") }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
        <script src="{{ asset("assets/jobentry/js/main.js") }}"></script>
    </body>

</html>
