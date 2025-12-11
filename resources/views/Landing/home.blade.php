@extends('Landing.Layouts.main')

@section('content')


    <!-- Carousel Start -->
    <div class="container-fluid p-0">
        <div class="owl-carousel header-carousel position-relative">
            <div class="owl-carousel-item position-relative">
                <img class="img-fluid" src="{{ asset('assets/jobentry/img/carousel-1.jpg') }}" alt="">
                <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center"
                    style="background: rgba(11, 36, 71, .5);">
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-10 col-lg-8 text-center">
                                <h1 class="display-5 text-white animated slideInDown mb-3">Temukan Pekerjaan Terbaik
                                    untuk Masa Depan Anda</h1>
                                <p class="fs-6 fw-medium text-white mb-4 pb-2">Bangun karier impian bersama
                                    SaktiJob. Ribuan lowongan terpercaya dan pencarian cerdas membantu Anda
                                    melangkah lebih cepat.</p>
                                @if (!Auth::guard('user')->check())
                                    <div class="mt-2">
                                        <a href="{{ url('/login') }}"
                                            class="btn btn-primary btn-lg px-4 py-3 me-2 mb-2">Daftar</a>
                                        <a href="{{ url('/login') }}"
                                            class="btn btn-outline-light btn-lg px-4 py-3 mb-2">Masuk</a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="owl-carousel-item position-relative">
                <img class="img-fluid" src="{{ asset('assets/jobentry/img/carousel-2.jpg') }}" alt="">
                <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center"
                    style="background: rgba(11, 36, 71, .5);">
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-10 col-lg-8 text-center">
                                <h1 class="display-5 text-white animated slideInDown mb-3">Lowongan Startup
                                    Terbaik, Peluang Besar untuk Berkembang</h1>
                                <p class="fs-6 fw-medium text-white mb-4 pb-2">Gabung dengan perusahaan bertumbuh
                                    dan peran berdampak. Temukan peluang yang sesuai keterampilan dan tujuan karier
                                    Anda.</p>
                                @if (!Auth::guard('user')->check())
                                    <div class="mt-2">
                                        <a href="{{ url('/login') }}"
                                            class="btn btn-primary btn-lg px-4 py-3 me-2 mb-2">Daftar</a>
                                        <a href="{{ url('/login') }}"
                                            class="btn btn-outline-light btn-lg px-4 py-3 mb-2">Masuk</a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Carousel End -->


    <!-- Search Start -->
    <div class="container-fluid bg-primary mb-5 wow fadeIn" data-wow-delay="0.1s" style="padding: 35px;">
        <div class="container">
            <div class="row g-2">
                <div class="col-md-10">
                    <div class="row g-2">
                        <div class="col-md-4">
                            <input type="text" class="form-control border-0" placeholder="Kata Kunci" />
                        </div>
                        <div class="col-md-4">
                            <select class="form-select border-0">
                                <option selected>Kategori</option>
                                <option value="1">Category 1</option>
                                <option value="2">Category 2</option>
                                <option value="3">Category 3</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select class="form-select border-0">
                                <option selected>Lokasi</option>
                                <option value="1">Location 1</option>
                                <option value="2">Location 2</option>
                                <option value="3">Location 3</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-dark border-0 w-100">Cari</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Search End -->

    <!-- Jobs Start -->
    <div class="container-fliud py-5">
        <div class="container">
            <h1 class="text-center mb-5 wow fadeInUp" data-wow-delay="0.1s">Daftar Lowongan</h1>
            <div class="tab-class text-center wow fadeInUp" data-wow-delay="0.3s">
                <ul class="nav nav-pills d-inline-flex justify-content-center border-bottom mb-5">
                    <li class="nav-item">
                        <a class="d-flex align-items-center text-start mx-3 ms-0 pb-3 active" data-bs-toggle="pill"
                            href="#tab-1">
                            <h6 class="mt-n1 mb-0">Unggulan</h6>
                        </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div id="tab-1" class="tab-pane fade show p-0 active">

                        @if ($jobs->count() > 0)
                            @foreach ($jobs as $job)
                                <div class="job-item p-4 mb-4">
                                    <div class="row g-4">
                                        <div class="col-sm-12 col-md-8 d-flex align-items-center">
                                            <img class="flex-shrink-0 img-fluid border rounded"
                                                src="{{ asset('FileUpload/' . $job->gambar) }}" alt=""
                                                style="width: 80px; height: 80px;">
                                            <div class="text-start ps-4">
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
                                                    href="{{ url('lowongan/detail/' . $job->id) }}">Lamar
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

                        @if ($jobs->count() > 10)
                            <a class="btn btn-primary py-3 px-5" href="{{ url('lowongan') }}">Lihat Lebih Banyak
                                Lowongan</a>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- Jobs End -->

@endsection
