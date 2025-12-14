@extends('Landing.Layouts.main')

@section('content')
<div class="container-fluid py-5">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-4">
            <div>
                <h1 class="mb-1">Semua Lowongan</h1>
                <div class="text-muted">Lowongan terbaru di Sungai Penuh & Kerinci</div>
            </div>

            {{-- (Opsional) tombol ke halaman saved jobs --}}
            @if (Auth::guard('user')->check())
                <a href="{{ url('user/saved-jobs') }}" class="btn btn-outline-primary">
                    <i class="far fa-bookmark me-2"></i> Lowongan Tersimpan
                </a>
            @endif
        </div>

        <div class="row g-4">
            {{-- LEFT: LIST --}}
            <div class="col-12 col-lg-8">

                @if ($jobs->count() > 0)
                    @foreach ($jobs as $job)
                        <div class="card job-card border-0 shadow-sm mb-3">
                            <div class="card-body p-3 p-md-4">
                                <div class="row g-3 align-items-center">

                                    {{-- Info --}}
                                    <div class="col-12 col-md-8">
                                        <div class="d-flex gap-3 align-items-start">
                                            {{-- Logo --}}
                                            <div class="job-logo flex-shrink-0 border rounded-3 bg-white d-flex align-items-center justify-content-center overflow-hidden">
                                                <img
                                                    src="{{ asset('FileUpload/' . $job->gambar) }}"
                                                    alt="Logo {{ $job->company->name ?? 'Perusahaan' }}"
                                                    class="img-fluid"
                                                    onerror="this.style.display='none'; this.parentElement.classList.add('job-logo-fallback'); this.parentElement.innerHTML='<span class=\'fw-bold text-primary\'>{{ strtoupper(substr($job->company->name ?? 'S',0,1)) }}</span>';"
                                                >
                                            </div>

                                            <div class="w-100">
                                                <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                                                    <h5 class="mb-0 lh-sm">{{ $job->title }}</h5>

                                                    <span class="badge rounded-pill bg-primary bg-opacity-10 text-white border border-primary border-opacity-25">
                                                        {{ $job->employment_type }}
                                                    </span>
                                                </div>

                                                <div class="text-muted small mb-2">
                                                    {{ $job->company->name }}
                                                </div>

                                                <div class="d-flex flex-wrap gap-2">
                                                    <span class="job-chip">
                                                        <i class="fa fa-map-marker-alt text-primary me-2"></i>{{ $job->location }}
                                                    </span>
                                                    <span class="job-chip">
                                                        <i class="far fa-calendar-alt text-primary me-2"></i>{{ $job->created_at->diffForHumans() }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Action --}}
                                    <div class="col-12 col-md-4">
                                        <div class="d-flex flex-md-column align-items-stretch align-items-md-end gap-2">
                                            <div class="d-flex gap-2 justify-content-md-end">
                                                @if (Auth::guard('user')->check())
                                                    <form action="{{ url('user/saved-jobs/toggle/' . $job->id) }}" method="POST" class="m-0">
                                                        @csrf
                                                        <button type="submit" class="btn btn-outline-primary btn-icon" title="Simpan lowongan">
                                                            <i class="bi bi-bookmark-star"></i>
                                                        </button>
                                                    </form>
                                                @endif

                                                <a class="btn btn-primary px-3" href="{{ url('lowongan/detail/' . $job->id) }}">
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
                        </div>
                    @endforeach

                    {{-- Pagination --}}
                    <div class="mt-4">
                        {{ $jobs->links() }}
                    </div>
                @else
                    <div class="alert alert-light border">Belum ada lowongan dipublikasikan.</div>
                @endif

            </div>

            {{-- RIGHT: SIDEBAR --}}
            <div class="col-12 col-lg-4">
                <div class="position-sticky" style="top: 90px;">

                    {{-- Card info (mirip “Pencarian tersimpan”) --}}
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-body p-3">
                            <div class="fw-semibold mb-1">Pencarian tersimpan</div>
                            <div class="text-muted small">
                                Gunakan tombol <b>Simpan</b> untuk menyimpan lowongan favorit dan akses cepat nanti.
                            </div>
                        </div>
                    </div>

                    {{-- Saved Jobs --}}
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div class="fw-semibold">Lowongan kerja tersimpan</div>

                                @if (Auth::guard('user')->check())
                                    <a href="{{ url('user/saved-jobs') }}" class="small text-decoration-none">
                                        Lihat semua
                                    </a>
                                @endif
                            </div>

                            @if (!Auth::guard('user')->check())
                                <div class="alert alert-light border mb-0 small">
                                    Masuk untuk melihat lowongan tersimpan.
                                    <a href="{{ url('login') }}">Masuk</a>
                                </div>
                            @else
                                @if (($savedJobs ?? collect())->count() == 0)
                                    <div class="text-muted small">
                                        Belum ada lowongan tersimpan.
                                    </div>
                                @else
                                    <div class="d-grid gap-2">
                                        @foreach (($savedJobs ?? collect())->take(5) as $sj)
                                            <a href="{{ url('lowongan/detail/' . $sj->id) }}"
                                               class="saved-mini card border-0 shadow-sm text-decoration-none">
                                                <div class="card-body p-3">
                                                    <div class="d-flex gap-2 align-items-start">
                                                        <div class="saved-logo flex-shrink-0 border rounded-3 bg-white d-flex align-items-center justify-content-center overflow-hidden">
                                                            <img src="{{ asset('FileUpload/' . $sj->gambar) }}"
                                                                 class="img-fluid"
                                                                 alt="Logo"
                                                                 onerror="this.style.display='none'; this.parentElement.classList.add('job-logo-fallback'); this.parentElement.innerHTML='<span class=\'fw-bold text-primary\'>{{ strtoupper(substr($sj->company->name ?? 'S',0,1)) }}</span>';">
                                                        </div>
                                                        <div class="w-100">
                                                            <div class="fw-semibold text-dark lh-sm">
                                                                {{ $sj->title }}
                                                            </div>
                                                            <div class="text-muted small">
                                                                {{ $sj->company->name }}
                                                            </div>
                                                            <div class="text-muted small mt-1">
                                                                <i class="fa fa-map-marker-alt text-primary me-2"></i>{{ $sj->location }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        @endforeach
                                    </div>

                                    @if (($savedJobs ?? collect())->count() > 5)
                                        <div class="mt-2 small text-muted">
                                            +{{ ($savedJobs ?? collect())->count() - 5 }} lowongan tersimpan lainnya
                                        </div>
                                    @endif
                                @endif
                            @endif

                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>
@endsection
