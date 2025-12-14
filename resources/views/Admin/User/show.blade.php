@extends('Admin.Layouts.main')

@section('content')
    <div class="page-heading">
        <h3>Data User</h3>
        {{-- <p class="text-muted">Seluruh pelamar yang melamar pada lowongan perusahaan terdaftar di bawah ini.
        </p> --}}
    </div>

    <div class="page-content">
        <div class="card mb-4 shadow-sm">
            <div class="card-body">

                <!-- Header -->
                <div class="d-flex align-items-center mb-3">
                    <img src="{{ asset('FileUpload/' . $user->photo) }}" class="rounded-circle me-3" width="80"
                        height="80" style="object-fit: cover;" alt="Foto Pelamar">

                    <div>
                        <h5 class="mb-1">{{ $user->full_name }}</h5>
                        <small class="text-muted">{{ $user->email }}</small>
                    </div>
                </div>

                <hr>

                <!-- Informasi Pribadi -->
                <h6 class="fw-bold mb-2">Informasi Pribadi</h6>
                <p class="mb-1"><strong>Jenis Kelamin:</strong> {{ $user->jenis_kelamin }}</p>
                <p class="mb-3"><strong>Tanggal Lahir:</strong> {{ date('d-m-Y', strtotime($user->tanggal_lahir)) }}</p>

                <!-- Informasi Kontak -->
                <h6 class="fw-bold mb-2">Informasi Kontak</h6>
                <p class="mb-1"><strong>Telepon:</strong> {{ $user->phone }}</p>
                <p class="mb-3"><strong>Alamat:</strong> {{ $user->address }}</p>

                <hr>

                {{-- <!-- Pendidikan -->
                <h6 class="fw-bold mb-2">Pendidikan</h6>
                @php $education = json_decode($user->education_json, true); @endphp

                @if ($education)
                    <ul>
                        <li>
                            <strong>{{ $education['program_studi'] ?? '-' }}</strong> —
                            {{ $education['institusi'] }}
                            ({{ ' Tahun masuk' . $education['tahun_masuk'] ?? '' }})
                            ({{ ' IPK ' . $education['gpa'] ?? '' }})
                        </li>
                    </ul>
                @else
                    <p class="text-muted">Tidak ada data</p>
                @endif --}}

                <h6 class="fw-bold mb-2">Pendidikan</h6>
                @php
                    $education = json_decode($user->education_json, true);
                @endphp

                @if ($education && is_array($education))
                    <ul class="list-unstyled">
                        @foreach ($education as $edu)
                            <li class="mb-2">
                                <strong>{{ $edu['degree'] ?? '-' }}</strong> — {{ $edu['school'] ?? '-' }}
                                <br>
                                <small class="text-muted">
                                    <i class="bi bi-calendar-event"></i> {{ $edu['period'] ?? '-' }}
                                </small>
                                @if (!empty($edu['desc']))
                                    <p class="small mb-0">{{ $edu['desc'] }}</p>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-muted">Tidak ada data pendidikan</p>
                @endif

                <hr>

                <!-- Sertifikasi -->
                {{-- <h6 class="fw-bold mb-2">Sertifikasi</h6>
                @php $certifications = json_decode($user->certifications_json, true); @endphp
                @if ($certifications)
                    <ul>
                        @foreach ($certifications as $cert)
                            <li>{{ $cert }}</li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-muted">Tidak ada data</p>
                @endif --}}

                <h6 class="fw-bold mb-2">Sertifikasi</h6>
                @php
                    $certifications = json_decode($user->certifications_json, true);
                @endphp

                @if ($certifications && is_array($certifications))
                    <ul class="list-unstyled">
                        @foreach ($certifications as $cert)
                            <li class="mb-2">
                                <div class="fw-bold text-primary">{{ $cert['name'] ?? '-' }}</div>
                                <div class="small">
                                    <strong>Penerbit:</strong> {{ $cert['issuer'] ?? '-' }}
                                </div>
                                <div class="small text-muted">
                                    <strong>Masa Berlaku/Tahun:</strong> {{ $cert['exp'] ?? '-' }}
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-muted">Tidak ada data sertifikasi</p>
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

                <h6 class="fw-bold mb-2">Pengalaman Kerja</h6>
                @php
                    $experiences = json_decode($user->experiences_json, true);
                @endphp

                @if ($experiences && is_array($experiences))
                    <div class="experience-list">
                        @foreach ($experiences as $exp)
                            <div class="mb-3 border-start ps-3">
                                <div class="fw-bold">{{ $exp['title'] ?? '-' }}</div>
                                <div class=" small fw-semibold">{{ $exp['company'] ?? '-' }}</div>
                                <div class="text-muted small mb-1">
                                    <i class="bi bi-calendar3 me-1"></i> {{ $exp['period'] ?? '-' }}
                                </div>
                                @if (!empty($exp['desc']))
                                    <p class="small text-secondary mb-0">
                                        {{ $exp['desc'] }}
                                    </p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted small">Tidak ada data pengalaman kerja</p>
                @endif

                <hr>

                <!-- CV -->
                <h6 class="fw-bold mb-2">Curriculum Vitae</h6>
                @if ($user->file_cv)
                    <a href="{{ asset('FileUpload/' . $user->file_cv) }}" target="_blank"
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
