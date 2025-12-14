@extends('Admin.Layouts.main')

@section('content')
    <div class="page-heading">
        <h3>Daftar Pelamar</h3>
        <p class="text-muted">Lowongan: {{ $job->title }} ({{ $job->company->name }})</p>
    </div>

    <div class="page-content">
        <div class="card">
            <div class="card-body">
                @if ($job->applyJobs->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Tanggal Lamar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($job->applyJobs as $apply)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $apply->user->full_name ?? '-' }}</td>
                                        <td>{{ $apply->user->email ?? '-' }}</td>
                                        <td>
                                            <span class="badge
                                                @if ($apply->status === 'accepted') bg-success
                                                @elseif ($apply->status === 'rejected') bg-danger
                                                @else bg-secondary
                                                @endif">
                                                {{ ucfirst($apply->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $apply->created_at->diffForHumans() }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-muted">Belum ada pelamar untuk lowongan ini.</div>
                @endif
            </div>
        </div>
    </div>
@endsection

