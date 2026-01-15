@extends('Admin.Layouts.main')

@section('content')
    <div class="page-heading">
        <h3>Data Pemberi Kerja</h3>
        <p class="text-muted">Verifikasi pemberi kerja yang mendaftar untuk meningkatkan kepercayaan lowongan.</p>
    </div>

    <div class="page-content">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Pemberi Kerja</th>
                                <th>Telepon</th>
                                <th>Website</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>


                            @foreach ($companies as $company)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $company->name }}</td>
                                    <td>{{ $company->phone }}</td>
                                    <td>
                                        @if ($company->link_website)
                                            <a href="{{ url($company->link_website) }}"><span
                                                    class="badge bg-primary">Link</span></a>
                                        @else
                                            <span class="badge bg-danger">Belum ada link</span>
                                        @endif
                                    </td>
                                    <td>

                                        @if ($company->status === 'pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @elseif ($company->status === 'verified')
                                            <span class="badge bg-success">Verified</span>
                                        @else
                                            <span class="badge bg-danger">Rejected</span>
                                        @endif
                                    </td>

                                    <td>


                                        @if ($company->status === 'pending')
                                            <form action="{{ url('data-company/verify/' . $company->id) }}" method="post"
                                                class="d-inline action-verify">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success">Verifikasi</button>
                                            </form>

                                            <form action="{{ url('data-company/reject/' . $company->id) }}" method="post"
                                                class="d-inline action-reject disabled">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-danger">Tolak</button>
                                            </form>
                                        @elseif ($company->status === 'verified')
                                            <form action="{{ url('data-company/reject/' . $company->id) }}" method="post"
                                                class="d-inline action-reject disabled">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-danger">Tolak</button>
                                            </form>
                                        @else
                                            <form action="{{ url('data-company/verify/' . $company->id) }}" method="post"
                                                class="d-inline action-verify">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success">Verifikasi</button>
                                            </form>
                                        @endif


                                        @if ($company->link_website)
                                            <button type="button" class="btn btn-sm btn-info detail-button"
                                                data-bs-toggle="modal" data-bs-target="#detailCompanyModal"
                                                data-company-id="{{ $company->id }}" data-name="{{ $company->name }}"
                                                data-email="{{ $company->email }}" data-phone="{{ $company->phone }}"
                                                data-address="{{ $company->address }}"
                                                data-description="{{ $company->description }}" {{-- Asumsi ini field Trix --}}
                                                data-website="{{ $company->link_website }}">
                                                Detail
                                            </button>
                                        @else
                                            <button type="button" class="btn btn-sm btn-info detail-button"
                                                data-bs-toggle="modal" data-bs-target="#detailCompanyModal"
                                                data-company-id="{{ $company->id }}" data-name="{{ $company->name }}"
                                                data-email="{{ $company->email }}" data-phone="{{ $company->phone }}"
                                                data-address="{{ $company->address }}"
                                                data-description="{{ $company->description }}" {{-- Asumsi ini field Trix --}}
                                                data-website="#">
                                                Detail
                                            </button>
                                        @endif

                                        {{-- -===================================================================- --}}
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- --- MODAL DETAIL PERUSAHAAN --- --}}
    <div class="modal fade" id="detailCompanyModal" tabindex="-1" aria-labelledby="detailCompanyModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailCompanyModalLabel">Detail Perusahaan: <span
                            id="modal-company-name"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-borderless">
                        <tr>
                            <th style="width: 150px;">Email</th>
                            <td>: <span id="modal-email"></span></td>
                        </tr>
                        <tr>
                            <th>Telepon</th>
                            <td>: <span id="modal-phone"></span></td>
                        </tr>
                        <tr>
                            <th>Website</th>
                            <td>: <a href="#" id="modal-website-link" target="_blank"><span
                                        class="badge bg-primary">Kunjungi Website</span></a></td>
                        </tr>
                        <tr>
                            <th>Alamat</th>
                            <td>: <span id="modal-address"></span></td>
                        </tr>
                    </table>

                    <hr>

                    <h6 class="mt-3">Deskripsi Perusahaan (Trix Content)</h6>
                    <div id="modal-description-content" class="p-3 border rounded" style="background-color: #f8f9fa;">
                        {{-- Konten Trix akan dimuat di sini --}}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
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

    <script>
        // console.log("Testing");
        document.querySelectorAll('form.action-verify').forEach(f => {
            f.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    icon: 'question',
                    title: 'Verifikasi Pemberi Kerja?',
                    text: 'Pemberi Kerja akan mendapat status terverifikasi.',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, verifikasi',
                    cancelButtonText: 'Batal'
                }).then((res) => {
                    if (res.isConfirmed) this.submit();
                });
            });
        });

        document.querySelectorAll('form.action-reject').forEach(f => {
            f.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Tolak Pemberi Kerja?',
                    text: 'Status akan ditandai sebagai ditolak.',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, tolak',
                    cancelButtonText: 'Batal'
                }).then((res) => {
                    if (res.isConfirmed) this.submit();
                });
            });
        });

        document.querySelectorAll('.detail-button').forEach(button => {
            button.addEventListener('click', function() {
                // Ambil data dari data attributes tombol yang diklik
                const name = this.getAttribute('data-name');
                const email = this.getAttribute('data-email');
                const phone = this.getAttribute('data-phone');
                const address = this.getAttribute('data-address');
                const website = this.getAttribute('data-website');
                const description = this.getAttribute('data-description');

                // Isi elemen di dalam modal
                document.getElementById('modal-company-name').textContent = name;
                document.getElementById('modal-email').textContent = email;
                document.getElementById('modal-phone').textContent = phone;
                document.getElementById('modal-address').textContent = address;

                // Isi link website
                const websiteLink = document.getElementById('modal-website-link');
                websiteLink.href = website;

                // Isi Konten Trix (HTML)
                // Penting: Karena ini adalah konten Trix (HTML mentah), gunakan innerHTML. 
                // Pastikan data ini sudah dibersihkan/aman di Controller sebelum disimpan ke DB.
                document.getElementById('modal-description-content').innerHTML = description;
            });
        });
    </script>
@endsection
