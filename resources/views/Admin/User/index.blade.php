@extends("Admin.Layouts.main")

@section("content")
    <div class="page-heading">
        <h3>Daftar Pelamar</h3>
        <p class="text-muted">Kelola Data Pelamar.</p>
    </div>
    <div class="page-content">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Daftar Pelamar</h5>
                </div>
                <div class="table-responsive">
                    <table class="table-striped table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Phone</th>
                                <th>Alamat</th>
                                <th>Email</th>
                                <th>Gambar</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>

                            @if (count($users) > 0)
                                @foreach ($users as $user)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $user->full_name }}</td>
                                        <td>{{ $user->phone }}</td>
                                        <td>{{ $user->address }}</td>
                                        <td>{{ $user->email }}</td>

                                        <td>
                                            @if ($user->photo)
                                                <img src="{{ asset("FileUpload/" . $user->photo) }}" alt=""
                                                    style="height:40px;width:40px;object-fit:cover;border-radius:6px">
                                            @endif

                                        </td>

                                        <td>
                                            <a href="{{ url("detail-pelamar-admin/" . $user->id) }}">
                                                <button class="btn btn-info btn-sm mt-2">Lihat Profile</button>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="7" class="text-center">Belum ada pelamar.</td>
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
