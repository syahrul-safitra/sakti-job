@extends('Company.Layouts.main')

@section('content')
    <div class="page-heading">
        <h3>Data Pelamar Usaha Anda</h3>
        <p class="text-muted">Seluruh pelamar yang melamar pada lowongan usaha terdaftar di bawah ini.
        </p>
    </div>

    <div class="page-content">

        @if ($jobs->count() > 0)
            @foreach ($jobs as $item)
                <div class="card mb-4">

                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0">{{ $item->title }}</h5>
                        </div>

                        <div class="d-flex gap-2">
                            <form action="{{ url('cetak-laporan-company/' . $item->id) }}" method="post">
                                @csrf
                                <button class="btn btn-danger btn-sm"><i class="bi bi-file-earmark-pdf me-2"></i>Pdf</button>
                            </form>

                            <form action="{{ url('cetak-laporan-company-excel/' . $item->id) }}" method="post">
                                @csrf
                                <button class="btn btn-success btn-sm"><i class="bi bi-file-earmark-excel me-2"></i> Export
                                    Excel</button>
                            </form>

                            <div>
                                <span class="badge bg-primary">Total: {{ count($item->applyJobs) }} Pelamar</span>
                            </div>
                        </div>

                    </div>

                    <div class="card-body">



                        <div class="table-responsive">
                            {{-- Tambahkan class 'datatable-pelamar' dan ID unik --}}
                            <table class="table-striped table datatable-pelamar" id="table-{{ $item->id }}">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Name</th>
                                        <th>No HP</th>
                                        {{-- <th>Cover Letter</th> --}}
                                        <th>Tanggal Melamar</th>
                                        <th>Status</th>
                                        <th>Keterangan</th>
                                        <th style="width: 10%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($item->applyJobs as $apply)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $apply->user->full_name }}</td>
                                            <td>{{ $apply->user->phone }}</td>
                                            {{-- <td>{{ $apply->cover_letter }}</td> --}}
                                            <td>

                                                @if ($apply->status == 'pending')
                                                    <span class="badge bg-warning">{{ $apply->status }}</span>
                                                @elseif ($apply->status == 'accepted')
                                                    <span class="badge bg-success">{{ $apply->status }}</span>
                                                @else
                                                    <span class="badge bg-danger">{{ $apply->status }}</span>
                                                @endif
                                            </td>
                                            <td>{{ $apply->created_at->format('d M Y') }}
                                            </td>
                                            <td>
                                                {{ $apply->keterangan }}
                                            </td>
                                            <td>

                                                <!-- Tombol untuk membuka modal -->
                                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#modalStatus{{ $apply->id }}">
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>

                                                <a href="{{ url('company-applyjob/detail/' . $apply->id) }}">
                                                    <button class="btn btn-info btn-sm "><i
                                                            class="bi bi-card-text"></i></button>
                                                </a>


                                                <!-- Modal -->
                                                <div class="modal fade" id="modalStatus{{ $apply->id }}" tabindex="-1"
                                                    aria-labelledby="modalStatusLabel{{ $apply->id }}"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <form method="POST"
                                                            action="{{ url('update-status-pelamar/' . $apply->id) }}">
                                                            @csrf
                                                            <div class="modal-content">

                                                                <div class="modal-header">
                                                                    <h5 class="modal-title"
                                                                        id="modalStatusLabel{{ $apply->id }}">
                                                                        Ubah Status Pelamar - {{ $apply->user->full_name }}
                                                                    </h5>
                                                                    <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>

                                                                <div class="modal-body">

                                                                    <label class="form-label">Status</label>
                                                                    <div class="mb-3">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input" type="radio"
                                                                                name="status" value="pending"
                                                                                {{ $apply->status == 'pending' ? 'checked' : '' }}>
                                                                            <label class="form-check-label">Pending</label>
                                                                        </div>

                                                                        <div class="form-check">
                                                                            <input class="form-check-input" type="radio"
                                                                                name="status" value="accepted"
                                                                                {{ $apply->status == 'accepted' ? 'checked' : '' }}>
                                                                            <label class="form-check-label">Accepted</label>
                                                                        </div>

                                                                        <div class="form-check">
                                                                            <input class="form-check-input" type="radio"
                                                                                name="status" value="rejected"
                                                                                {{ $apply->status == 'rejected' ? 'checked' : '' }}>
                                                                            <label class="form-check-label">Rejected</label>
                                                                        </div>
                                                                    </div>

                                                                    <div class="mb-3">
                                                                        <label class="form-label">Keterangan</label>
                                                                        <textarea class="form-control" name="keterangan" rows="3" maxlength="240"
                                                                            placeholder="Tulis keterangan tambahan (opsional)">{{ $apply->keterangan }}</textarea>
                                                                    </div>

                                                                </div>

                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-bs-dismiss="modal">Tutup</button>
                                                                    <button type="submit" class="btn btn-success">Simpan
                                                                        Perubahan</button>
                                                                </div>

                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>

                                            </td>
                                        </tr>
                                    @endforeach
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

    @session('swal')
        <script src="{{ asset('assets/admindash/assets/extensions/sweetalert2/sweetalert2.min.js') }}"></script>
        <script>
            const swalData = @json(session('swal'));

            Swal.fire({
                icon: swalData.icon,
                title: swalData.title,
                text: swalData.text,
                timer: 3000
            });
        </script>
    @endsession


    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // 1. Cari semua tabel dengan class 'datatable-pelamar'
            const allTables = document.querySelectorAll(".datatable-pelamar");
            const dtInstances = {}; // Tempat menyimpan instance tabel

            allTables.forEach(table => {
                const tableId = table.id;

                // Inisialisasi DataTable untuk setiap tabel
                const dt = new simpleDatatables.DataTable(table, {
                    searchable: true,
                    fixedHeight: false,
                    perPage: 5, // Sesuaikan jumlah data per halaman
                    labels: {
                        placeholder: "Cari pelamar...",
                        perPage: "Pilih data per hal",
                        noRows: "Tidak ada pelamar ditemukan",
                        info: "Menampilkan {start} - {end} dari {rows}",
                    }
                });

                // Simpan instance ke object agar bisa diakses oleh filter status
                dtInstances[tableId] = dt;
            });

            // 2. Hubungkan Dropdown Filter Status (Jika Anda menambahkannya)
            const statusFilters = document.querySelectorAll(".filter-status-custom");

            statusFilters.forEach(filter => {
                filter.addEventListener("change", function() {
                    const targetTableId = this.getAttribute("data-target");
                    const dt = dtInstances[targetTableId];
                    const value = this.value.toLowerCase();

                    if (dt) {
                        // Gunakan pencarian internal library untuk memfilter status
                        dt.search(value);
                    }
                });
            });
        });
    </script>
@endsection
