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
                                <p class="fs-6 fw-medium text-white mb-4 pb-2">Gabung dengan lowongan pekerjaan
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
    {{-- <div class="container-fluid bg-primary mb-5 wow fadeIn" data-wow-delay="0.1s" style="padding: 35px;">
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
    </div> --}}


    <div class="container-fluid bg-primary wow fadeIn mb-5" data-wow-delay="0.1s" style="padding: 35px;">
        <div class="container">
            <form method="GET" action="{{ url('/') }}">
                <div class="row g-2 align-items-center">
                    <div class="col-md-4">
                        <input type="text" class="form-control border-0" name="keyword"
                            placeholder="Cari pekerjaan" value="{{ request('keyword') }}" />
                    </div>
                    <div class="col-md-4">
                        <select class="form-select border-0" name="tipe">
                            <option value="" {{ request('tipe') == '' ? 'selected' : '' }}>Semua Tipe</option>
                            <option value="UMKM" {{ request('tipe') == 'UMKM' ? 'selected' : '' }}>UMKM</option>
                            <option value="Perusahaan" {{ request('tipe') == 'Perusahaan' ? 'selected' : '' }}>Perusahaan</option>
                            <option value="Swasta" {{ request('tipe') == 'Swasta' ? 'selected' : '' }}>Swasta</option>
                            <option value="Butik" {{ request('tipe') == 'Butik' ? 'selected' : '' }}>Butik</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary text-primary fw-semibold w-100">
                            Cari
                        </button>
                    </div>
                    <div class="col-md-2 mt-2 mt-md-0">
                        <a href="{{ url('/lowongan') }}" class="btn btn-outline-light w-100">
                            Cari Lebih Lengkap
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- Search End -->

    <!-- Jobs Start -->
    <div class="container-fliud py-5">
        <div class="container">
            <h1 class="text-center mb-5 wow fadeInUp" data-wow-delay="0.1s">Daftar Lowongan</h1>
            <div class="tab-class text-center wow fadeInUp" data-wow-delay="0.3s">
                @if (isset($loadError) && $loadError)
                    <div class="alert alert-warning border text-start">
                        {{ $loadError }}
                    </div>
                @endif
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
                                <div class="card job-card border-0 shadow-sm mb-3 position-relative">
                                    <div class="card-body p-3 p-md-4">
                                        <div class="row g-3 align-items-center">

                                            {{-- LEFT: Logo + Info --}}
                                            <div class="col-12 col-md-8">
                                                <div class="d-flex gap-3 align-items-start">

                                                    {{-- Logo --}}
                                                    <div
                                                        class="job-logo flex-shrink-0 border rounded-3 bg-white d-flex align-items-center justify-content-center overflow-hidden">
                                                        <img src="{{ asset('FileUpload/' . $job->gambar) }}"
                                                            alt="Logo {{ $job->company->name ?? 'Perusahaan' }}"
                                                            class="img-fluid"
                                                            onerror="this.style.display='none'; this.parentElement.classList.add('job-logo-fallback'); this.parentElement.innerHTML='<span class=\'fw-bold text-primary\'>{{ strtoupper(substr($job->company->name ?? 'S', 0, 1)) }}</span>';">
                                                    </div>

                                                    {{-- Info --}}
                                                    <div class="w-100">
                                                        <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                                                            <h5 class="mb-0 lh-sm">{{ $job->title }}</h5>

                                                            {{-- Badge tipe kerja --}}
                                                            <span
                                                                class="badge rounded-pill bg-primary bg-opacity-10 text-white border border-primary border-opacity-25">
                                                                {{ $job->employment_type }}
                                                            </span>
                                                        </div>

                                                        <div class="text-muted small mb-2 text-start">
                                                            {{ $job->company->name }}
                                                        </div>

                                                        {{-- Meta chips --}}
                                                        <div class="d-flex flex-wrap gap-2">
                                                            <span class="job-chip">
                                                                <i
                                                                    class="fa fa-map-marker-alt text-primary me-2"></i>{{ $job->location }}
                                                            </span>

                                                            <span class="job-chip">
                                                                <i
                                                                    class="far fa-clock text-primary me-2"></i>{{ $job->employment_type }}
                                                            </span>

                                                            <span class="job-chip">
                                                                <i
                                                                    class="far fa-calendar-alt text-primary me-2"></i>{{ $job->created_at->diffForHumans() }}
                                                            </span>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                            {{-- RIGHT: Actions --}}
                                            <div class="col-12 col-md-4">
                                                <div
                                                    class="d-flex flex-md-column align-items-stretch align-items-md-end gap-2">

                                                    <div class="d-flex gap-2 justify-content-md-end">
                                                        <button type="button" class="btn btn-outline-primary btn-icon"
                                                            title="Simpan lowongan" aria-label="Simpan lowongan">
                                                            <i class="bi bi-bookmark-star"></i>
                                                        </button>

                                                        <a class="btn btn-primary px-3"
                                                            href="{{ url('lowongan/detail/' . $job->id) }}">
                                                            Lihat Detail <i class="fa fa-arrow-right ms-2"></i>
                                                        </a>
                                                    </div>

                                                    <div class="text-muted small text-md-end mt-md-2">
                                                        Dipublikasikan {{ $job->created_at->diffForHumans() }}
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <a href="{{ url('lowongan/detail/' . $job->id) }}" class="stretched-link"></a>
                                </div>
                            @endforeach
                        @else
                            <div class="alert alert-light border">Tidak ada lowongan.</div>
                        @endif

                        <a class="btn btn-primary py-3 px-5" href="{{ url('lowongan') }}">Lihat Semua Lowongan</a>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- Jobs End -->

@endsection
