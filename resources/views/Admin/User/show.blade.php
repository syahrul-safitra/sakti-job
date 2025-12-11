@extends("Admin.Layouts.main")

@section("content")
    <div class="page-heading">
        <h3>Data Pelamar</h3>
        {{-- <p class="text-muted">Seluruh pelamar yang melamar pada lowongan perusahaan terdaftar di bawah ini.
        </p> --}}
    </div>

    <div class="page-content">
        <div class="card mb-4 shadow-sm">
            <div class="card-body">

                <!-- Header -->
                <div class="d-flex align-items-center mb-3">
                    <img src="{{ asset("FileUpload/" . $user->photo) }}" class="rounded-circle me-3" width="80"
                        height="80" style="object-fit: cover;" alt="Foto Pelamar">

                    <div>
                        <h5 class="mb-1">{{ $user->full_name }}</h5>
                        <small class="text-muted">{{ $user->email }}</small>
                    </div>
                </div>

                <hr>

                <!-- Informasi Kontak -->
                <h6 class="fw-bold mb-2">Informasi Kontak</h6>
                <p class="mb-1"><strong>Telepon:</strong> {{ $user->phone }}</p>
                <p class="mb-3"><strong>Alamat:</strong> {{ $user->address }}</p>

                <hr>

                <!-- Pendidikan -->
                <h6 class="fw-bold mb-2">Pendidikan</h6>
                @php $education = json_decode($user->education_json, true); @endphp

                @if ($education)
                    <ul>
                        <li>
                            <strong>{{ $education["program_studi"] ?? "-" }}</strong> —
                            {{ $education["institusi"] }}
                            ({{ " Tahun masuk" . $education["tahun_masuk"] ?? "" }})
                            ({{ " IPK " . $education["gpa"] ?? "" }})
                        </li>
                    </ul>
                @else
                    <p class="text-muted">Tidak ada data</p>
                @endif

                <hr>

                <!-- Sertifikasi -->
                <h6 class="fw-bold mb-2">Sertifikasi</h6>
                @php $certifications = json_decode($user->certifications_json, true); @endphp
                @if ($certifications)
                    <ul>
                        @foreach ($certifications as $cert)
                            <li>{{ $cert }}</li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-muted">Tidak ada data</p>
                @endif

                <hr>

                <!-- Skill -->
                <h6 class="fw-bold mb-2">Keahlian (Skills)</h6>
                @php $skills = json_decode($user->skills_json, true); @endphp
                @if ($skills)
                    <div class="d-flex flex-wrap">
                        @foreach ($skills as $skill)
                            <span class="badge bg-primary mb-1 me-1">{{ $skill }}</span>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted">Tidak ada data</p>
                @endif

                <hr>

                <!-- Bahasa -->
                <h6 class="fw-bold mb-2">Bahasa</h6>
                @php $languages = json_decode($user->languages_json, true); @endphp
                @if ($languages)
                    <div class="d-flex flex-wrap">
                        @foreach ($languages as $lang)
                            <span class="badge bg-secondary mb-1 me-1">{{ $lang }}</span>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted">Tidak ada data</p>
                @endif

                <hr>

                <!-- Pengalaman -->
                <h6 class="fw-bold mb-2">Pengalaman Kerja</h6>
                @php $experiences = json_decode($user->experiences_json, true); @endphp
                @if ($experiences)
                    <ul>
                        @foreach ($experiences as $exp)
                            <li>
                                {{ $exp }}
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-muted">Tidak ada data</p>
                @endif

                <hr>

                <!-- CV -->
                <h6 class="fw-bold mb-2">Curriculum Vitae</h6>
                @if ($user->file_cv)
                    <a href="{{ asset("FileUpload/" . $user->file_cv) }}" target="_blank"
                        class="btn btn-outline-primary btn-sm">
                        Lihat / Unduh CV
                    </a>
                @else
                    <p>Tidak ada data</p>
                @endif

            </div>
        </div>

    </div>
@endsection
