@extends("Landing.Layouts.main")

@section("content")

    <!-- Carousel Start -->
    <div class="container-fluid p-0">
        <div class="owl-carousel header-carousel position-relative">
            <div class="owl-carousel-item position-relative">
                <img class="img-fluid" src="{{ asset("assets/jobentry/img/carousel-1.jpg") }}" alt="">
                <div class="position-absolute w-100 h-100 d-flex align-items-center start-0 top-0"
                    style="background: rgba(11, 36, 71, .5);">
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-10 col-lg-8 text-center">
                                <h1 class="display-5 animated slideInDown mb-3 text-white">Temukan Pekerjaan Terbaik
                                    untuk Masa Depan Anda</h1>
                                <p class="fs-6 fw-medium mb-4 pb-2 text-white">Bangun karier impian bersama
                                    SaktiJob. Ribuan lowongan terpercaya dan pencarian cerdas membantu Anda
                                    melangkah lebih cepat.</p>
                                <div class="mt-2">
                                    @if (!Auth::guard("user")->check())
                                        <a href="{{ url("/login") }}"
                                            class="btn btn-primary btn-lg mb-2 me-2 px-4 py-3">Daftar</a>
                                        <a href="{{ url("/login") }}"
                                            class="btn btn-outline-light btn-lg mb-2 px-4 py-3">Masuk</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="owl-carousel-item position-relative">
                <img class="img-fluid" src="{{ asset("assets/jobentry/img/carousel-2.jpg") }}" alt="">
                <div class="position-absolute w-100 h-100 d-flex align-items-center start-0 top-0"
                    style="background: rgba(11, 36, 71, .5);">
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-10 col-lg-8 text-center">
                                <h1 class="display-5 animated slideInDown mb-3 text-white">Lowongan Startup
                                    Terbaik, Peluang Besar untuk Berkembang</h1>
                                <p class="fs-6 fw-medium mb-4 pb-2 text-white">Gabung dengan perusahaan bertumbuh
                                    dan peran berdampak. Temukan peluang yang sesuai keterampilan dan tujuan karier
                                    Anda.</p>
                                <div class="mt-2">
                                    @if (!Auth::guard("user")->check())
                                        <a href="{{ url("/login") }}"
                                            class="btn btn-primary btn-lg mb-2 me-2 px-4 py-3">Daftar</a>
                                        <a href="{{ url("/login") }}"
                                            class="btn btn-outline-light btn-lg mb-2 px-4 py-3">Masuk</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Carousel End -->

    <!-- Search Start -->
    {{-- <div class="container-fluid bg-primary wow fadeIn mb-5" data-wow-delay="0.1s" style="padding: 35px;">
        <div class="container">
            <div class="row g-2">
                <div class="col-md-10">
                    <div class="row g-2">
                        <div class="col-md-12">
                            <input type="text" class="form-control border-0" placeholder="Kata Kunci" />
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-dark w-100 border-0">Cari</button>
                </div>
            </div>
        </div>
    </div> --}}

    <div class="container-fluid bg-primary wow fadeIn mb-5" data-wow-delay="0.1s" style="padding: 35px;">
        <div class="container">
            <form method="GET" action="{{ url("/") }}">
                <div class="row g-2">
                    <div class="col-md-10">
                        <div class="row g-2">
                            <div class="col-md-12">
                                <input type="text" class="form-control border-0" name="keyword" placeholder="Kata Kunci"
                                    value="{{ request("keyword") }}" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-dark w-100 border-0">Cari</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- Search End -->

    <!-- Jobs Start -->
    <div class="container-fliud py-5">
        <div class="container">
            <h1 class="wow fadeInUp mb-5 text-center" data-wow-delay="0.1s">Daftar Lowongan</h1>
            <div class="tab-class wow fadeInUp text-center" data-wow-delay="0.3s">
                <ul class="nav nav-pills d-inline-flex justify-content-center border-bottom mb-5">
                    <li class="nav-item">
                        <a class="d-flex align-items-center active mx-3 ms-0 pb-3 text-start" data-bs-toggle="pill"
                            href="#tab-1">
                            <h6 class="mt-n1 mb-0">Unggulan</h6>
                        </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div id="tab-1" class="tab-pane fade show active p-0">

                        @if ($jobs->count() > 0)
                            @foreach ($jobs as $job)
                                <div class="job-item mb-4 p-4">
                                    <div class="row g-4">
                                        <div class="col-sm-12 col-md-8 d-flex align-items-center">
                                            <img class="img-fluid flex-shrink-0 rounded border"
                                                src="{{ asset("FileUpload/" . $job->gambar) }}" alt=""
                                                style="width: 80px; height: 80px;">
                                            <div class="ps-4 text-start">
                                                <h5 class="mb-1">{{ $job->title }}</h5>
                                                <div class="text-muted small mb-2">{{ $job->company->name }}</div>
                                                <span class="text-truncate me-3"><i
                                                        class="fa fa-map-marker-alt text-primary me-2"></i>{{ $job->location }}</span>
                                                <span class="text-truncate me-3"><i
                                                        class="far fa-clock text-primary me-2"></i>{{ $job->employment_type }}</span>
                                            </div>
                                        </div>
                                        <div
                                            class="col-sm-12 col-md-4 d-flex flex-column align-items-start align-items-md-end justify-content-center">
                                            <div class="d-flex mb-3">
                                                <a class="btn btn-light btn-square me-3" href="#"><i
                                                        class="far fa-heart text-primary"></i></a>
                                                <a class="btn btn-primary"
                                                    href="{{ url("lowongan/detail/" . $job->id) }}">Lamar
                                                    Sekarang</a>
                                            </div>
                                            <small class="text-truncate"><i
                                                    class="far fa-calendar-alt text-primary me-2"></i>Dipublikasikan:
                                                {{ $job->created_at->diffForHumans() }}</small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="alert alert-light border">Belum ada lowongan dipublikasikan.</div>
                        @endif

                        @if ($allJobsCount > 10)
                            <a class="btn btn-primary px-5 py-3" href="{{ url("lowongan") }}">Lihat Lebih Banyak
                                Lowongan</a>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- Jobs End -->

@endsection
