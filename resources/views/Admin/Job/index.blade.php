@extends('Admin.Layouts.main')

@section('content')
    <div class="page-heading">
        <h3>Data Lowongan</h3>
        <p class="text-muted">Daftar semua lowongan, dipisahkan per perusahaan.</p>
    </div>

    <div class="page-content">
        @foreach ($companies as $company)
            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <h5 class="mb-1">{{ $company->name }}</h5>
                            <div class="text-muted small">{{ $company->phone ?? '-' }}</div>
                        </div>
                        <span class="badge bg-primary">Total: {{ $company->jobs->count() }}</span>
                    </div>

                    @if ($company->jobs->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Judul</th>
                                        <th>Lokasi</th>
                                        <th>Jenis</th>
                                        <th>Status</th>
                                        <th>Dipublikasikan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($company->jobs as $job)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $job->title }}</td>
                                            <td>{{ $job->location }}</td>
                                            <td>{{ $job->employment_type }}</td>
                                            <td>
                                                <span class="badge {{ $job->status === 'published' ? 'bg-success' : 'bg-secondary' }}">
                                                    {{ ucfirst($job->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $job->created_at->diffForHumans() }}</td>
                                            <td>
                                                <a href="{{ url('data-lowongan/detail/' . $job->id) }}" class="btn btn-sm btn-outline-primary">
                                                    Lihat
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-muted">Belum ada lowongan.</div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
@endsection
