@extends('Company.Layouts.main')

@section('content')
    <div class="page-heading">
        <h3>Menunggu Verifikasi Admin</h3>
        <p class="text-muted">Profil perusahaan Anda telah dikirim. Admin akan meninjau dan memverifikasi sebelum Anda dapat
            mengakses dashboard perusahaan.</p>
    </div>

    <div class="page-content">
        <div class="card">
            <div class="card-body">
                <p class="mb-0">Status saat ini: <span class="badge bg-warning">Pending</span></p>
            </div>
        </div>
    </div>

    @session('swal')
        <script src="{{ asset('assets/admindash/assets/extensions/sweetalert2/sweetalert2.min.js') }}"></script>
        <script>
            const swalData = @json(session('swal'));
            console.log("Testing 222");

            Swal.fire({
                icon: swalData.icon,
                title: swalData.title,
                text: swalData.text,
                timer: 3000
            });
        </script>
    @endsession
@endsection
