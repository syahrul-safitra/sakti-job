@extends('Company.Layouts.main')

@push('styles')
    <style>
        .dp-wrap .card {
            border-radius: 16px;
        }

        .dp-wrap .muted {
            color: rgba(255, 255, 255, .65);
        }

        .dp-wrap .avatar {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(13, 110, 253, .15);
            color: #0d6efd;
            font-weight: 800;
        }

        .dp-wrap .chip {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            padding: .35rem .6rem;
            border-radius: 999px;
            border: 1px solid rgba(255, 255, 255, .10);
            background: rgba(255, 255, 255, .03);
            font-size: .85rem;
        }

        .dp-wrap .btn-soft {
            border-radius: 12px;
        }

        .dp-wrap .doc-item {
            border: 1px solid rgba(255, 255, 255, .10);
            background: rgba(255, 255, 255, .02);
            border-radius: 14px;
            padding: 12px 12px;
        }

        .dp-wrap .doc-item a {
            text-decoration: none;
        }

        .dp-wrap .section-title {
            font-weight: 700;
            letter-spacing: .2px;
        }

        .dp-wrap .divider {
            border-top: 1px solid rgba(255, 255, 255, .08);
        }

        .dp-toast {
            position: fixed;
            right: 16px;
            bottom: 16px;
            z-index: 9999;
            min-width: 240px;
            max-width: 340px;
            padding: 12px 14px;
            border-radius: 14px;
            border: 1px solid rgba(255, 255, 255, .12);
            background: rgba(20, 20, 20, .92);
            color: rgba(255, 255, 255, .9);
            box-shadow: 0 .75rem 1.5rem rgba(0, 0, 0, .35);
            display: none;
        }

        .dp-toast.show {
            display: block;
        }

        .dp-toast .title {
            font-weight: 800;
            font-size: .95rem;
        }

        .dp-toast .desc {
            font-size: .85rem;
            opacity: .85;
        }
    </style>
@endpush

@section('content')
    @php
        $u = $apply->user;
        $job = $apply->job;
        $company = $job->company;

        $name = $u->name ?? 'Pelamar';
        $email = $u->email ?? '-';
        $phone = $u->phone ?? ($u->no_hp ?? ($u->phone_number ?? null));

        // initial
        $initial = strtoupper(substr($name, 0, 1));

        // optional profile fields (sesuaikan dengan DB kamu)
        $summary = $u->summary ?? ($u->about ?? ($u->bio ?? null));
        $skillsRaw = $u->skills ?? null; // bisa string CSV
        $skills = [];
        if (!empty($skillsRaw)) {
            $skills = array_filter(array_map('trim', explode(',', $skillsRaw)));
        }

        // docs fields (sesuaikan)
        $cvFile = $u->cv ?? ($u->resume ?? ($u->file_cv ?? null)); // contoh: "cv.pdf"
        $portfolio = $u->portfolio ?? ($u->portfolio_url ?? null); // contoh: "https://..."
        $linkedin = $u->linkedin ?? ($u->link_linkedin ?? null);

        // applicant extra
        $address = $u->address ?? ($u->alamat ?? null);
        $dob = $u->dob ?? ($u->tanggal_lahir ?? null);
        $gender = $u->gender ?? ($u->jenis_kelamin ?? null);

        // simple WA link
        $wa = null;
        if ($phone) {
            $digits = preg_replace('/\D+/', '', $phone);
            // kalau nomor mulai 0 -> ubah ke 62
            if (str_starts_with($digits, '0')) {
                $digits = '62' . substr($digits, 1);
            }
            $wa = 'https://wa.me/' . $digits;
        }
    @endphp

    <div class="dp-wrap">
        <div class="page-heading mb-3">
            <h3 class="mb-1">Detail Pelamar</h3>
            <p class="text-muted mb-0">Informasi pelamar, dokumen, dan status lamaran.</p>
        </div>

        <div class="page-content">
            <div class="row g-3">
                {{-- LEFT --}}
                <div class="col-12 col-lg-8">
                    {{-- Profil --}}
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="d-flex align-items-start justify-content-between flex-wrap gap-3">
                                <div class="d-flex align-items-start gap-3">
                                    <div class="avatar">{{ $initial }}</div>
                                    <div>
                                        <div class="section-title h5 mb-1">{{ $name }}</div>
                                        <div class="text-muted small mb-1">
                                            <i class="bi bi-envelope me-1"></i>{{ $email }}
                                        </div>
                                        <div class="text-muted small">
                                            <i class="bi bi-telephone me-1"></i>{{ $phone ?: '-' }}
                                        </div>

                                        <div class="mt-3 d-flex flex-wrap gap-2">
                                            @if ($wa)
                                                <a class="btn btn-sm btn-success btn-soft" target="_blank"
                                                    href="{{ $wa }}">
                                                    <i class="bi bi-whatsapp me-1"></i> WhatsApp
                                                </a>
                                            @endif
                                            @if ($phone)
                                                <a class="btn btn-sm btn-outline-secondary btn-soft"
                                                    href="tel:{{ $phone }}">
                                                    <i class="bi bi-telephone-outbound me-1"></i> Telepon
                                                </a>
                                            @endif
                                            @if ($email && $email !== '-')
                                                <a class="btn btn-sm btn-outline-primary btn-soft"
                                                    href="mailto:{{ $email }}">
                                                    <i class="bi bi-send me-1"></i> Email
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="text-end">
                                    <div class="chip mb-2">
                                        <i class="bi bi-calendar2-check"></i>
                                        Dilamar: {{ $apply->created_at->format('d M Y, H:i') }}
                                    </div>
                                    <div class="chip">
                                        <i class="bi bi-tag"></i>
                                        Status:
                                        <span id="statusBadge">
                                            @if (($apply->status ?? 'pending') === 'accepted')
                                                <span class="badge bg-success">Diterima</span>
                                            @elseif(($apply->status ?? 'pending') === 'rejected')
                                                <span class="badge bg-danger">Ditolak</span>
                                            @else
                                                <span class="badge bg-warning text-dark">Pending</span>
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <hr class="divider my-4">

                            {{-- Ringkasan singkat --}}
                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    <div class="text-muted small mb-1">Alamat</div>
                                    <div class="fw-semibold">{{ $address ?: '-' }}</div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="text-muted small mb-1">Jenis Kelamin</div>
                                    <div class="fw-semibold">{{ $gender ?: '-' }}</div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="text-muted small mb-1">Tgl Lahir</div>
                                    <div class="fw-semibold">{{ $dob ?: '-' }}</div>
                                </div>
                            </div>

                            @if (!empty($summary))
                                <div class="mt-4">
                                    <div class="section-title mb-2">Ringkasan Profil</div>
                                    <div class="doc-item" style="white-space:pre-line">{{ $summary }}</div>
                                </div>
                            @endif

                            @if (!empty($skills))
                                <div class="mt-4">
                                    <div class="section-title mb-2">Skill</div>
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach ($skills as $s)
                                            <span class="chip"><i
                                                    class="bi bi-lightning-charge"></i>{{ $s }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="section-title h5 mb-3">Detail Lengkap</div>

                            <div class="row g-3">

                                <div class="doc-item">
                                    <div class="col-12 col-md-6">
                                        <h6 class="fw-bold mb-2">Pendidikan</h6>
                                        @php
                                            $education = json_decode($u->education_json, true);
                                        @endphp

                                        @if ($education && is_array($education))
                                            <ul class="list-unstyled">
                                                @foreach ($education as $edu)
                                                    <li class="mb-2">
                                                        <strong>{{ $edu['degree'] ?? '-' }}</strong> —
                                                        {{ $edu['school'] ?? '-' }}
                                                        <br>
                                                        <small class="text-muted">
                                                            <i class="bi bi-calendar-event"></i>
                                                            {{ $edu['period'] ?? '-' }}
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

                                        <h6 class="fw-bold mb-2">Sertifikasi</h6>
                                        @php
                                            $certifications = json_decode($u->certifications_json, true);
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
                                        @php $skills = json_decode($u->skills_json, true); @endphp
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
                                        @php $languages = json_decode($u->languages_json, true); @endphp
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
                                            $experiences = json_decode($u->experiences_json, true);
                                        @endphp

                                        @if ($experiences && is_array($experiences))
                                            <div class="experience-list">
                                                @foreach ($experiences as $exp)
                                                    <div class="mb-3 border-start ps-3">
                                                        <div class="fw-bold">{{ $exp['title'] ?? '-' }}</div>
                                                        <div class=" small fw-semibold">{{ $exp['company'] ?? '-' }}</div>
                                                        <div class="text-muted small mb-1">
                                                            <i class="bi bi-calendar3 me-1"></i>
                                                            {{ $exp['period'] ?? '-' }}
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
                                        {{-- <h6 class="fw-bold mb-2">Curriculum Vitae</h6>
                                        @if ($u->file_cv)
                                            <a href="{{ asset('FileUpload/' . $u->file_cv) }}" target="_blank"
                                                class="btn btn-outline-primary btn-sm">
                                                Lihat / Unduh CV
                                            </a>
                                        @else
                                            <p>Tidak ada data</p>
                                        @endif --}}

                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- Dokumen --}}
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="section-title h5 mb-3">Dokumen & Link</div>

                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    <div class="doc-item">
                                        <div class="d-flex align-items-start justify-content-between gap-2">
                                            <div>
                                                <div class="fw-semibold"><i class="bi bi-file-earmark-text me-1"></i> CV /
                                                    Resume</div>
                                                <div class="text-muted small">File CV pelamar</div>
                                            </div>

                                            @if ($cvFile)
                                                <a class="btn btn-sm btn-outline-primary btn-soft" target="_blank"
                                                    href="{{ asset('FileUpload/' . $cvFile) }}">
                                                    Lihat
                                                </a>
                                            @else
                                                <span class="badge bg-light text-dark">Tidak ada</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="doc-item">
                                        <div class="d-flex align-items-start justify-content-between gap-2">
                                            <div>
                                                <div class="fw-semibold"><i class="bi bi-link-45deg me-1"></i> Portofolio
                                                </div>
                                                <div class="text-muted small">Link karya/website</div>
                                            </div>

                                            @if ($portfolio)
                                                <a class="btn btn-sm btn-outline-secondary btn-soft" target="_blank"
                                                    href="{{ $portfolio }}">
                                                    Buka
                                                </a>
                                            @else
                                                <span class="badge bg-light text-dark">Tidak ada</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                {{-- <div class="col-12 col-md-6">
                                    <div class="doc-item">
                                        <div class="d-flex align-items-start justify-content-between gap-2">
                                            <div>
                                                <div class="fw-semibold"><i class="bi bi-linkedin me-1"></i> LinkedIn</div>
                                                <div class="text-muted small">Profil profesional</div>
                                            </div>

                                            @if ($linkedin)
                                                <a class="btn btn-sm btn-outline-secondary btn-soft" target="_blank"
                                                    href="{{ $linkedin }}">
                                                    Buka
                                                </a>
                                            @else
                                                <span class="badge bg-light text-dark">Tidak ada</span>
                                            @endif
                                        </div>
                                    </div>
                                </div> --}}
                            </div>

                            {{-- <div class="text-muted small mt-3">
                                *Sesuaikan field CV/Portofolio/LinkedIn jika nama kolom di DB kamu berbeda.
                            </div> --}}
                        </div>
                    </div>

                    {{-- Cover letter --}}
                    <div class="card">
                        <div class="card-body">
                            <div class="section-title h5 mb-3">Catatan / Cover Letter</div>
                            <div class="doc-item" style="white-space:pre-line">
                                {{ $apply->cover_letter ?? 'Tidak ada catatan.' }}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- RIGHT --}}
                <div class="col-12 col-lg-4">
                    {{-- Status Lamaran (AJAX + SweetAlert2 alasan penolakan) --}}
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="section-title h5 mb-3">Status Lamaran</div>

                            <form id="statusForm" action="{{ url('update-status-pelamar/' . $apply->id) }}"
                                method="POST">
                                @csrf

                                <input type="hidden" name="keterangan" id="keteranganHidden"
                                    value="{{ $apply->keterangan }}">

                                <label class="form-label">Status</label>
                                <select name="status" id="statusSelect" class="form-select mb-3">
                                    <option value="pending"
                                        {{ ($apply->status ?? 'pending') === 'pending' ? 'selected' : '' }}>Pending
                                    </option>
                                    <option value="accepted"
                                        {{ ($apply->status ?? 'pending') === 'accepted' ? 'selected' : '' }}>Diterima
                                    </option>
                                    <option value="rejected"
                                        {{ ($apply->status ?? 'pending') === 'rejected' ? 'selected' : '' }}>Ditolak
                                    </option>
                                </select>

                                <label for="form-label">Keterangan</label>
                                <textarea name="keterangan" id="" class="form-control">{{ $apply->keterangan }}</textarea>

                                <button type="submit" id="saveStatusBtn" class="btn btn-success w-100 mt-3">
                                    <i class="bi bi-check2-circle me-1"></i> Simpan
                                </button>

                                @if (!empty($apply->keterangan))
                                    <div class="mt-3">
                                        <div class="text-muted small mb-1">Keterangan terakhir</div>
                                        <div class="doc-item" style="white-space:pre-line">{{ $apply->keterangan }}</div>
                                    </div>
                                @endif
                            </form>
                        </div>
                    </div>

                    {{-- Lowongan + Perusahaan --}}
                    <div class="card">
                        <div class="card-body">
                            <div class="section-title h5 mb-3">Lowongan</div>
                            <div class="fw-semibold">{{ $job->title }}</div>
                            <div class="text-muted small">{{ $job->location }} • {{ $job->employment_type }}</div>
                            <div class="mt-2 d-flex gap-2">
                                <a href="{{ url('lowongan/detail/' . $job->id) }}"
                                    class="btn btn-sm btn-outline-primary btn-soft">
                                    Lihat Lowongan
                                </a>
                                <a href="{{ url('company/lowongan/' . $job->id) }}"
                                    class="btn btn-sm btn-outline-secondary btn-soft">
                                    Kelola Lowongan
                                </a>
                            </div>

                            <hr class="divider my-4">

                            <div class="section-title mb-2">Perusahaan</div>
                            <div class="fw-semibold">{{ $company->name }}</div>
                            <div class="text-muted small"><i
                                    class="bi bi-telephone me-1"></i>{{ $company->phone ?? '-' }}</div>

                            @if (!empty($company->link_website))
                                <div class="mt-2">
                                    <a class="btn btn-sm btn-outline-secondary btn-soft" target="_blank"
                                        href="{{ $company->link_website }}">
                                        <i class="bi bi-globe me-1"></i> Website
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>
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

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toastEl = document.getElementById('dpToast');
            const toastTitle = document.getElementById('dpToastTitle');
            const toastDesc = document.getElementById('dpToastDesc');
            let toastTimer = null;

            function showToast(title, desc) {
                toastTitle.textContent = title || 'Info';
                toastDesc.textContent = desc || '';
                toastEl.classList.add('show');
                clearTimeout(toastTimer);
                toastTimer = setTimeout(() => toastEl.classList.remove('show'), 2400);
            }

            function badgeHtml(status) {
                if (status === 'accepted') return '<span class="badge bg-success">Diterima</span>';
                if (status === 'rejected') return '<span class="badge bg-danger">Ditolak</span>';
                return '<span class="badge bg-warning text-dark">Pending</span>';
            }

            const form = document.getElementById('statusForm');
            const select = document.getElementById('statusSelect');
            const ketHidden = document.getElementById('keteranganHidden');
            const btn = document.getElementById('saveStatusBtn');
            const badge = document.getElementById('statusBadge');

            if (!form) return;

            form.addEventListener('submit', async (e) => {
                e.preventDefault();

                // if rejected -> require reason
                if (select.value === 'rejected') {
                    const {
                        value: reason,
                        isConfirmed
                    } = await Swal.fire({
                        title: 'Alasan penolakan',
                        input: 'textarea',
                        inputPlaceholder: 'Contoh: Kualifikasi belum sesuai / pengalaman kurang / dll.',
                        inputValue: (ketHidden?.value || ''),
                        showCancelButton: true,
                        confirmButtonText: 'Simpan',
                        cancelButtonText: 'Batal',
                        reverseButtons: true,
                        inputValidator: (val) => {
                            if (!val || val.trim().length < 5)
                                return 'Minimal 5 karakter ya.';
                        }
                    });

                    if (!isConfirmed) return;
                    ketHidden.value = reason.trim();
                } else {
                    ketHidden.value = '';
                }

                const original = btn.innerHTML;
                btn.disabled = true;
                btn.innerHTML = 'Menyimpan...';

                try {
                    const url = form.getAttribute('action');
                    const token = form.querySelector('input[name="_token"]')?.value;

                    const fd = new FormData();
                    fd.append('_token', token || '');
                    fd.append('status', select.value);
                    fd.append('keterangan', ketHidden.value || '');

                    const res = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        },
                        body: fd
                    });

                    const ct = res.headers.get('content-type') || '';
                    let data = null;
                    if (ct.includes('application/json')) data = await res.json();
                    else data = {
                        success: res.ok
                    };

                    if (!res.ok || data?.success === false) {
                        showToast('Gagal', data?.message || 'Gagal menyimpan status.');
                        return;
                    }

                    // update badge top
                    if (badge) badge.innerHTML = badgeHtml(select.value);
                    showToast('Berhasil', 'Status lamaran diperbarui.');
                } catch (err) {
                    showToast('Error', 'Terjadi kesalahan jaringan.');
                } finally {
                    btn.disabled = false;
                    btn.innerHTML = original;
                }
            });
        });
    </script>
@endpush
