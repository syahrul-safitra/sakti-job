@extends("Landing.Layouts.main")

@section("content")
    <!-- Jobs Start -->
    <div class="container-fliud py-5">
        <div class="container">
            <h1 class="wow fadeInUp mb-5 text-center" data-wow-delay="0.1s">Semua Lowongan</h1>
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

                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- Jobs End -->
@endsection
