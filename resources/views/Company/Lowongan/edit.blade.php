@extends('Company.Layouts.main')

@section('content')
    <div class="page-heading">
        <h3>Edit Lowongan</h3>
        <p class="text-muted">Lengkapi detail lowongan pekerjaan.</p>
    </div>

    <div class="page-content">
        <div class="card">
            <div class="card-body">
                <form action="{{ url('company-lowongan/' . $job->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Judul</label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                            value="{{ old('title', $job->title) }}">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Lokasi</label>
                        <input type="text" name="location" class="form-control @error('location') is-invalid @enderror"
                            value="{{ old('location', $job->location) }}">
                        @error('location')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jenis Pekerjaan</label>
                        <select name="employment_type" class="form-select @error('employment_type') is-invalid @enderror">
                            <option value="">Pilih jenis</option>
                            <option value="full-time" @selected(old('employment_type', $job->employment_type) === 'full-time')>Full-time</option>
                            <option value="part-time" @selected(old('employment_type', $job->employment_type) === 'part-time')>Part-time</option>
                            <option value="contract" @selected(old('employment_type', $job->employment_type) === 'contract')>Kontrak</option>
                            <option value="internship" @selected(old('employment_type', $job->employment_type) === 'internship')>Magang</option>
                        </select>
                        @error('employment_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Gaji Minimum (opsional)</label>
                            <input type="number" name="salary_min"
                                class="form-control @error('salary_min') is-invalid @enderror"
                                value="{{ old('salary_min', $job->salary_min) }}">
                            @error('salary_min')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Gaji Maksimum (opsional)</label>
                            <input type="number" name="salary_max"
                                class="form-control @error('salary_max') is-invalid @enderror"
                                value="{{ old('salary_max', $job->salary_max) }}">
                            @error('salary_max')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group mb-4">
                        <label for="description-trix" class="form-label">Deskripsi</label>

                        {{-- Input tersembunyi untuk menyimpan data Trix --}}
                        <input id="description-trix" type="hidden" name="description"
                            value="{{ old('description', $job->description) }}">

                        {{-- Editor Trix --}}
                        <trix-editor input="description-trix"></trix-editor>

                        @error('description')
                            <div class="text-danger mt-1">
                                <small>{{ $message }}</small>
                            </div>
                        @enderror
                    </div>

                    {{-- START: PERUBAHAN UNTUK IMAGE PREVIEW --}}
                    <div class="mb-3">
                        <label class="form-label">Gambar (JPG/PNG/WEBP)</label>
                        <input type="file" name="gambar" id="imageInput"
                            class="form-control @error('gambar') is-invalid @enderror" accept="image/*">
                        @error('gambar')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        {{-- ID: imagePreviewContainer digunakan untuk menampilkan gambar yang dipilih --}}
                        <img id="imagePreviewContainer" src="{{ asset('FileUpload/' . $job->gambar) }}"
                            alt="Preview Gambar"
                            style=" max-width: 200px; height: auto; border: 1px solid #ddd; padding: 5px;">
                    </div>
                    {{-- END: PERUBAHAN UNTUK IMAGE PREVIEW --}}

                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ url('company-lowongan') }}" class="btn btn-outline-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const imageInput = document.getElementById('imageInput');
            const imagePreview = document.getElementById('imagePreviewContainer');

            if (imageInput) {
                imageInput.addEventListener('change', function(event) {
                    // Pastikan ada file yang dipilih
                    if (event.target.files && event.target.files[0]) {
                        const reader = new FileReader();

                        reader.onload = function(e) {
                            // Tampilkan gambar dalam elemen <img>
                            imagePreview.src = e.target.result;
                            imagePreview.style.display = 'block'; // Pastikan gambar terlihat
                        }

                        // Baca file sebagai Data URL
                        reader.readAsDataURL(event.target.files[0]);
                    } else {
                        // Jika file dibatalkan, sembunyikan preview
                        imagePreview.src = '#';
                        imagePreview.style.display = 'none';
                    }
                });
            }
        });
    </script>
@endsection
