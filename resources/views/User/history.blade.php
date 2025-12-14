@extends('Landing.Layouts.main')

@section('content')
<div class="container-fliud py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="fw-bold">Riwayat Lamaran Pekerjaan</h3>
        </div>

        @session('success')
            <div class="alert alert-success rounded-0 mb-3">
                {{ session('success') }}
            </div>
        @endsession

        @forelse($user->applyJobs as $item)
            <div class="card shadow-sm mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="fw-bold">{{ $item->job->title }}</div>
                            <div class="text-muted small">{{ $item->job->company->name }}</div>
                            <div class="mt-2">
                                <span class="text-muted small">Dilamar: {{ $item->created_at->format('d M Y') }}</span>
                            </div>
                        </div>
                        <div>
                            @if ($item->status == 'pending')
                                <span class="badge bg-warning">Menunggu Review</span>
                            @elseif($item->status == 'rejected')
                                <span class="badge bg-danger">Ditolak</span>
                            @elseif($item->status == 'accepted')
                                <span class="badge bg-success">Diterima</span>
                            @endif
                        </div>
                    </div>
                    @if (!empty($item->cover_letter))
                        <div class="mt-3 text-muted" style="white-space:pre-line">{{ $item->cover_letter }}</div>
                    @endif
                    @if (!empty($item->keterangan))
                        <div class="mt-2">
                            <span class="badge bg-light text-dark">Keterangan</span>
                            <span class="ms-2 text-muted">{{ $item->keterangan }}</span>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="card shadow-sm">
                <div class="card-body text-center text-muted py-4">
                    Belum ada riwayat lamaran pekerjaan.
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection
