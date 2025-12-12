@extends("Company.Layouts.main")

@section("content")
    <div class="page-heading">
        <h3>Lowongan Perusahaan</h3>
        <p class="text-muted">Kelola dan pasang lowongan pekerjaan Anda.</p>
    </div>
    <div class="page-content">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Daftar Lowongan</h5>
                    <a href="{{ url("company-lowongan/create") }}" class="btn btn-primary">Buat Lowongan</a>
                </div>
                <div class="table-responsive">
                    <table class="table-striped table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Gambar</th>
                                <th>Judul</th>
                                <th>Lokasi</th>
                                <th>Jenis</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>

                            @if (count($jobs) > 0)
                                @foreach ($jobs as $job)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            @if ($job->gambar)
                                                <img src="{{ asset("FileUpload/" . $job->gambar) }}" alt=""
                                                    style="height:40px;width:40px;object-fit:cover;border-radius:6px">
                                            @endif

                                        </td>
                                        <td>{{ $job->title }}</td>
                                        <td>{{ $job->location }}</td>
                                        <td>{{ $job->employment_type }}</td>
                                        <td>
                                            @if ($job->status === "draft")
                                                <span class="badge bg-secondary">Draft</span>
                                            @else
                                                <span class="badge bg-success">Published</span>
                                            @endif

                                        </td>
                                        <td>
                                            <a href="{{ url("company-lowongan/edit/" . $job->id) }}"
                                                class="btn btn-sm btn-outline-primary">Edit</a>
                                            <form action="{{ url("company-lowongan/" . $job->id) }}" method="post"
                                                class="d-inline action-delete">
                                                @method("delete")
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                                            </form>

                                            @if ($job->status === "published")
                                                <form action="{{ url("company/lowongan/unpublish/" . $job->id) }}"
                                                    method="post" class="d-inline action-unpublish">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-warning">Unpublish</button>
                                                </form>
                                            @else
                                                <form action="{{ url("company/lowongan/publish/" . $job->id) }}"
                                                    method="post" class="d-inline action-publish">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success">Publish</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="7" class="text-center">Belum ada lowongan.</td>
                                </tr>
                            @endif

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @session("swal")
        <script src="{{ asset("assets/admindash/assets/extensions/sweetalert2/sweetalert2.min.js") }}"></script>
        <script>
            const swalData = @json(session("swal"));

            Swal.fire({
                icon: swalData.icon,
                title: swalData.title,
                text: swalData.text,
                timer: 3000
            });
        </script>
    @endsession

    <script>
        document.querySelectorAll('form.action-delete').forEach(f => {
            f.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Hapus Lowongan?',
                    text: 'Tindakan ini tidak dapat dibatalkan.',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, hapus',
                    cancelButtonText: 'Batal'
                }).then((res) => {
                    if (res.isConfirmed) this.submit();
                });
            });
        });
        document.querySelectorAll('form.action-publish').forEach(f => {
            f.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    icon: 'question',
                    title: 'Publish Lowongan?',
                    showCancelButton: true,
                    confirmButtonText: 'Publish',
                    cancelButtonText: 'Batal'
                }).then((res) => {
                    if (res.isConfirmed) this.submit();
                });
            });
        });
        document.querySelectorAll('form.action-unpublish').forEach(f => {
            f.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    icon: 'question',
                    title: 'Unpublish Lowongan?',
                    showCancelButton: true,
                    confirmButtonText: 'Unpublish',
                    cancelButtonText: 'Batal'
                }).then((res) => {
                    if (res.isConfirmed) this.submit();
                });
            });
        });
    </script>
@endsection
