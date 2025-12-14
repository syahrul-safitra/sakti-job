@extends('Landing.Layouts.main')

@section('content')
<div class="container-fliud py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="fw-bold">Lowongan Tersimpan</h3>
        </div>

        @session('success')
            <div class="alert alert-success rounded-0 mb-3">
                {{ session('success') }}
            </div>
        @endsession

        @forelse($jobs as $job)
            <div class="card shadow-sm mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="fw-bold">{{ $job->title }}</div>
                            <div class="text-muted small">{{ $job->company->name ?? '-' }}</div>
                            <div class="mt-2 text-muted small">
                                <i class="fa fa-map-marker-alt text-primary me-2"></i>{{ $job->location }}
                                <span class="ms-3"><i class="far fa-money-bill-alt text-primary me-2"></i>{{ number_format($job->salary_min) }} - {{ number_format($job->salary_max) }}</span>
                            </div>
                        </div>
                        <div class="text-end">
                            <a href="{{ url('lowongan/detail/' . $job->id) }}" class="btn btn-sm btn-outline-primary me-2">
                                Lihat
                            </a>
                            <form action="{{ url('user/saved-jobs/toggle/' . $job->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="card shadow-sm">
                <div class="card-body text-center text-muted py-4">
                    Belum ada lowongan tersimpan.
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection
