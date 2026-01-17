@extends('Company.Layouts.main')

@section('content')
    <div class="page-heading">
        <h3>Edit Data Usaha</h3>
        <p class="text-muted">

            @if ($company->status == 'pending')
                Silahkan buat deskripsi usaha anda.
                <span class="badge bg-warning">Belum di verifikasi</span>
            @else
                <span class="badge bg-success">Sudah di verifikasi</span>
            @endif
        </p>
    </div>

    <div class="page-content">
        <div class="row">
            <div class="col-12 col-lg-8">
                <div class="card">
                    <div class="card-body">

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>

                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach

                                </ul>

                            </div>
                        @endif

                        {{-- Asumsi variabel $profile adalah objek Model yang berisi data yang ada --}}
                        {{-- Asumsi Trix Editor CSS/JS sudah dimuat --}}

                        <form action="{{ url('update-profile/' . $company->id) }}" method="post">
                            @csrf

                            {{-- Email Field --}}
                            {{-- Pada form edit, email harus diisi dari data $profile --}}
                            <div class="form-group mb-4">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" id="email"
                                    class="form-control @error('email') is-invalid @enderror" placeholder="Alamat Email"
                                    value="{{ old('email', $company->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Password Field (Kosongkan kecuali ingin diubah) --}}
                            <div class="form-group mb-4">
                                <label for="password" class="form-label">Kata Sandi Baru (Kosongkan jika tidak
                                    diubah)</label>
                                <input type="password" name="password" id="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    placeholder="Kata Sandi Baru (Min. 8 karakter)">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Confirm Password Field --}}
                            <div class="form-group mb-4">
                                <label for="password_confirmation" class="form-label">Konfirmasi Kata Sandi Baru</label>
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                    class="form-control @error('password') is-invalid @enderror"
                                    placeholder="Ketik Ulang Kata Sandi Baru">
                            </div>

                            <h3>Detail Usaha</h3>
                            <hr class="mb-4">

                            {{-- Name Field --}}
                            <div class="form-group mb-4">
                                <label for="name" class="form-label">Nama Usaha</label>
                                <input type="text" name="name" id="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    placeholder="Nama Resmi Perusahaan" value="{{ old('name', $company->name ?? '') }}"
                                    required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Status Field --}}
                            <div class="form-group mb-4">
                                <label for="status" class="form-label">Status Usaha</label>
                                <select id="status" class="form-control @error('status') is-invalid @enderror" readonly>
                                    <option value="pending">Pending
                                    </option>
                                </select>
                            </div>

                            {{-- Address Field --}}
                            <div class="form-group mb-4">
                                <label for="address" class="form-label">Alamat Lengkap</label>
                                <textarea name="address" id="address" class="form-control @error('address') is-invalid @enderror" rows="3"
                                    maxlength="240" placeholder="Alamat Kantor Pusat">{{ old('address', $company->address ?? '') }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Phone Field --}}
                            <div class="form-group mb-4">
                                <label for="phone" class="form-label">Nomor Telepon</label>
                                <input type="text" name="phone" id="phone"
                                    class="form-control @error('phone') is-invalid @enderror"
                                    placeholder="Contoh: 0812xxxxxx" value="{{ old('phone', $company->phone ?? '') }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Link Website Field (Menggantikan 'website' CI4) --}}
                            <div class="form-group mb-4">
                                <label for="link_website" class="form-label">Link Website</label>
                                <input type="url" name="link_website" id="link_website"
                                    class="form-control @error('link_website') is-invalid @enderror"
                                    placeholder="Contoh: https://www.perusahaan.com"
                                    value="{{ old('link_website', $company->link_website ?? '') }}">
                                @error('link_website')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-4">
                                <label for="description-trix" class="form-label">Deskripsi Perusahaan</label>

                                <input id="description-trix" type="hidden" name="description"
                                    value="{{ old('description', $company->description ?? '') }}">

                                <trix-editor input="description-trix"></trix-editor>

                                @error('description')
                                    <div class="text-danger mt-1">
                                        <small>{{ $message }}</small>
                                    </div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary">
                                {{ ($company->status ?? 'pending') === 'verified' ? 'Simpan Perubahan' : 'Kirim untuk Verifikasi' }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="mb-2">Kenapa perlu verifikasi?</h5>
                        <p class="text-muted mb-0">Verifikasi meningkatkan kepercayaan pencari kerja terhadap lowongan Anda
                            dan membantu sistem memprioritaskan postingan terverifikasi.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @session('swal')
        <script src="{{ asset('assets/admindash/assets/extensions/sweetalert2/sweetalert2.min.js') }}"></script>
        <script>
            const swalData = @json(session('swal'));

            console.log(swalData)
            Swal.fire({
                icon: swalData.icon,
                title: swalData.title,
                text: swalData.text,
                timer: 3000
            });
        </script>
    @endsession
@endsection
