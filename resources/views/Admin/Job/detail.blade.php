@extends('Admin.Layouts.main')

@section('content')
    <div class="page-heading">
        <h3>Detail Lowongan</h3>
        <p class="text-muted">Informasi lengkap lowongan.</p>
    </div>

    <div class="page-content">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between flex-wrap gap-3">
                    <div>
                        <h4 class="mb-1">{{ $job->title }}</h4>
                        <div class="text-muted">{{ $job->location }} • {{ $job->employment_type }}</div>
                        <div class="mt-2">
                            <span class="badge {{ $job->status === 'published' ? 'bg-success' : 'bg-secondary' }}">
                                {{ ucfirst($job->status) }}
                            </span>
                        </div>
                    </div>
                    <div class="text-end">
                        @if ($job->salary_min || $job->salary_max)
                            <div class="fw-semibold">
                                {{ 'Rp .' . number_format($job->salary_min ?? 0, 0, ',', '.') }}
                                -
                                {{ 'Rp .' . number_format($job->salary_max ?? 0, 0, ',', '.') }}
                                per month
                            </div>
                        @endif
                        <div class="text-muted small mt-2">
                            Dipublikasikan: {{ $job->created_at->diffForHumans() }}
                        </div>
                    </div>
                </div>

                <div class="d-flex flex-wrap gap-2 mt-3">
                    @if ($job->status !== 'published')
                        <form action="{{ url('admin-lowongan/publish/' . $job->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success">Publish</button>
                        </form>
                    @else
                        <form action="{{ url('admin-lowongan/unpublish/' . $job->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-warning">Unpublish</button>
                        </form>
                    @endif
                    <form action="{{ url('admin-lowongan/' . $job->id) }}" method="POST" onsubmit="return confirm('Hapus lowongan ini beserta pelamar terkait?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Hapus Lowongan</button>
                    </form>
                    <a href="{{ url('data-lowongan/pelamar/' . $job->id) }}" class="btn btn-outline-secondary">Lihat Daftar Pelamar</a>
                </div>

                <hr class="my-4">

                <div class="row g-4">
                    <div class="col-lg-8">
                        <h6 class="mb-2">Deskripsi Pekerjaan</h6>
                        <div class="text-muted" style="white-space:pre-line">{!! $job->description !!}</div>
                    </div>
                    <div class="col-lg-4">
                        <div class="border rounded p-3">
                            <div class="mb-2 fw-semibold">Perusahaan</div>
                            <div class="mb-1">{{ $job->company->name }}</div>
                            <div class="text-muted small">{{ $job->company->phone ?? '-' }}</div>
                            @if (!empty($job->company->link_website))
                                <div class="mt-2">
                                    <a href="{{ $job->company->link_website }}" target="_blank">Website</a>
                                </div>
                            @endif
                        </div>
                        <div class="mt-3">
                            <a href="{{ url('lowongan/detail/' . $job->id) }}" class="btn btn-outline-primary w-100">
                                Lihat Halaman Publik
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
