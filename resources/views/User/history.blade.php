<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold">Riwayat Lamaran Pekerjaan</h3>

    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">

            @session("success")
                <div class="alert alert-success rounded-0 mb-0">
                    {{ session("success") }}
                </div>
            @endsession

            <table class="table-bordered table-hover mb-0 table">
                <thead class="table-light">
                    <tr>
                        <th width="5%">#</th>
                        <th>Posisi</th>
                        <th>Perusahaan</th>
                        <th>Tanggal Melamar</th>
                        <th>Cover Letter</th>
                        <th>Status</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($user->applyJobs as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>

                            <td>{{ $item->job->title }}</td>
                            <td>{{ $item->job->company->name }}</td>

                            <td>{{ $item->created_at->format("d M Y") }}</td>
                            <td>{{ $item->cover_letter }}</td>

                            <td>
                                @if ($item->status == "pending")
                                    <span class="badge bg-warning">Menunggu Review</span>
                                @elseif($item->status == "rejected")
                                    <span class="badge bg-danger">Ditolak</span>
                                @elseif($item->status == "accepted")
                                    <span class="badge bg-success">Diterima</span>
                                @endif
                            </td>

                            <td>{{ $item->keterangan }}</td>

                        </tr>

                    @empty
                        <tr>
                            <td colspan="6" class="text-muted py-4 text-center">
                                Belum ada riwayat lamaran pekerjaan.
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>

        </div>
    </div>

</div>
