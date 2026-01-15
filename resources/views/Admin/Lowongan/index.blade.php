@extends('Admin.Layouts.main')

@section('content')
    <div class="page-heading">
        <h3>Data Lowongan </h3>
        <p class="text-muted">Seluruh lowongan yang diupload oleh perusahaan terdaftar, dikelompokkan per pemberi kerja.</p>
    </div>

    <div class="page-content">

        @if ($collection->count() > 0)
            @foreach ($collection as $item)
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0">{{ $item->name }}</h5>
                            <small class="text-muted">User ID: {{ $item->id }}</small>
                        </div>
                        <span class="badge bg-primary">Total: {{ count($item->jobs) }} lowongan</span>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table-striped table">
                                <thead>
                                    <tr>
                                        <th>Judul</th>
                                        <th>Lokasi</th>
                                        <th>Jenis</th>
                                        <th>Status</th>
                                        <th>Dipublikasikan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($item->jobs as $job)
                                        <tr>
                                            <td>{{ $job->title }}</td>
                                            <td>{{ $job->location }}</td>
                                            <td>{{ $job->employment_type }}</td>
                                            <td><span
                                                    class="badge {{ ($job->status ?? '') === 'published' ? 'bg-success' : 'bg-secondary' }}">{{ $job->status }}</span>
                                            </td>
                                            <td>{{ $job->created_at ? $job->created_at->format('d M Y') : '-' }}
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="card">
                <div class="card-body">
                    <p class="mb-0">Belum ada lowongan.</p>
                </div>
            </div>
        @endif

    </div>
@endsection
