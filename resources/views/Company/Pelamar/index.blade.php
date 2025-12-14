@extends('Company.Layouts.main')

@push('styles')
<style>
/* =========================
   Company - Lamaran Masuk UI (Refined + AJAX + Smooth Scroll)
   Scoped to .lm-wrap
   ========================= */

/* card base */
.lm-wrap .card{
    border: 1px solid rgba(255,255,255,.07);
    border-radius: 16px;
    background: rgba(255,255,255,.02);
}

/* inputs */
.lm-wrap .form-control,
.lm-wrap .form-select{
    border-radius: 12px;
    border-color: rgba(255,255,255,.10);
    background: rgba(255,255,255,.03);
}

.lm-wrap .form-control:focus,
.lm-wrap .form-select:focus{
    border-color: rgba(13,110,253,.45);
    box-shadow: 0 0 0 .2rem rgba(13,110,253,.15);
}

/* LEFT list */
.lm-wrap .lm-left .job-list{
    max-height: 70vh;
    overflow: auto;
    padding-right: 6px;
}

.lm-wrap .lm-left .list-group-item{
    border-radius: 14px;
    margin-bottom: 10px;
    border: 1px solid rgba(255,255,255,.08);
    background: rgba(255,255,255,.015);
    padding: 12px 12px;
    transition: transform .14s ease, box-shadow .14s ease, background-color .14s ease, border-color .14s ease;
}

.lm-wrap .lm-left .list-group-item:hover{
    transform: translateY(-1px);
    box-shadow: 0 .45rem .9rem rgba(0,0,0,.22);
    background: rgba(255,255,255,.03);
    border-color: rgba(255,255,255,.12);
}

.lm-wrap .lm-left .list-group-item.active{
    background: rgba(13,110,253,.16);
    border-color: rgba(13,110,253,.35);
    color: inherit;
    box-shadow: 0 .45rem .9rem rgba(0,0,0,.18);
}

.lm-wrap .lm-left .list-group-item.active .opacity-75,
.lm-wrap .lm-left .list-group-item.active .text-muted{
    color: rgba(255,255,255,.72) !important;
}

/* badges softer */
.lm-wrap .badge{
    border-radius: 999px;
    font-weight: 600;
    letter-spacing: .2px;
}

/* scrollbar */
.lm-wrap .lm-left .job-list::-webkit-scrollbar{ width: 8px; }
.lm-wrap .lm-left .job-list::-webkit-scrollbar-thumb{
    background: rgba(255,255,255,.12);
    border-radius: 999px;
}
.lm-wrap .lm-left .job-list::-webkit-scrollbar-thumb:hover{
    background: rgba(255,255,255,.18);
}

/* table */
.lm-wrap .table{ margin-bottom: 0; }
.lm-wrap .table thead th{
    white-space: nowrap;
    border-bottom: 1px solid rgba(255,255,255,.08);
}
.lm-wrap .table td,
.lm-wrap .table th{ vertical-align: middle; }

.lm-wrap .table tbody tr{
    transition: background-color .12s ease;
}
.lm-wrap .table tbody tr:hover{
    background: rgba(255,255,255,.025);
}

/* subtle highlight after save */
.lm-wrap .row-saved{
    outline: 1px solid rgba(25,135,84,.35);
    background: rgba(25,135,84,.06) !important;
}

/* Small toast */
.lm-toast{
    position: fixed;
    right: 16px;
    bottom: 16px;
    z-index: 9999;
    min-width: 240px;
    max-width: 320px;
    padding: 12px 14px;
    border-radius: 14px;
    border: 1px solid rgba(255,255,255,.12);
    background: rgba(20,20,20,.92);
    color: rgba(255,255,255,.9);
    box-shadow: 0 .75rem 1.5rem rgba(0,0,0,.35);
    display: none;
}
.lm-toast.show{ display: block; }
.lm-toast .title{ font-weight: 700; font-size: .95rem; }
.lm-toast .desc{ font-size: .85rem; opacity: .85; }

/* responsive */
@media (max-width: 576px){
    .lm-wrap .lm-left .job-list{ max-height: 45vh; }
}
</style>
@endpush

@section('content')
<div class="lm-wrap">
    <div class="page-heading mb-3">
        <div class="d-flex align-items-start justify-content-between flex-wrap gap-2">
            <div>
                <h3 class="mb-1">Lamaran Masuk</h3>
                <p class="text-muted mb-0">Kelola lamaran kerja berdasarkan lowongan yang dipublish.</p>
            </div>
        </div>
    </div>

    <div class="page-content">
        @php
            $defaultJob = $jobs->firstWhere(fn($j) => $j->applyJobs->count() > 0) ?? $jobs->first();
            $defaultJobId = $defaultJob?->id;
        @endphp

        @if($jobs->count() === 0)
            <div class="card">
                <div class="card-body text-center text-muted">Belum ada lowongan atau lamaran masuk.</div>
            </div>
        @else
            <div class="row g-3">
                {{-- LEFT: LIST LOWONGAN --}}
                <div class="col-12 col-lg-4 col-xl-3 lm-left">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div class="fw-semibold">Lowongan</div>
                                <span class="badge bg-light text-dark">{{ $jobs->count() }}</span>
                            </div>

                            <input type="text" id="jobSearch" class="form-control form-control-sm mb-3"
                                   placeholder="Cari lowongan...">

                            <div class="list-group job-list">
                                @foreach($jobs as $job)
                                    @php
                                        $count = $job->applyJobs->count();
                                        $pending = $job->applyJobs->where('status','pending')->count();
                                        $isActive = $job->id == $defaultJobId;
                                    @endphp

                                    <button
                                        type="button"
                                        class="list-group-item list-group-item-action d-flex align-items-start gap-2 job-item-btn {{ $isActive ? 'active' : '' }}"
                                        data-job-id="{{ $job->id }}"
                                        data-job-title="{{ strtolower($job->title) }}"
                                    >
                                        <div class="flex-grow-1 text-start">
                                            <div class="fw-semibold mb-1 text-truncate">{{ $job->title }}</div>
                                            <div class="small opacity-75 text-truncate">
                                                {{ $job->location }} • {{ $job->employment_type }}
                                            </div>
                                            <div class="mt-2 d-flex flex-wrap gap-2">
                                                <span class="badge bg-primary">Total: {{ $count }}</span>
                                                @if($pending > 0)
                                                    <span class="badge bg-warning text-dark">Pending: {{ $pending }}</span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="text-end small opacity-75">
                                            <i class="bi bi-chevron-right"></i>
                                        </div>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                {{-- RIGHT: DETAIL LAMARAN (PER LOWONGAN) --}}
                <div class="col-12 col-lg-8 col-xl-9">
                    <div id="rightPanelTop"></div>

                    @foreach($jobs as $job)
                        @php $count = $job->applyJobs->count(); @endphp

                        <div class="card job-panel {{ $job->id == $defaultJobId ? '' : 'd-none' }}"
                             data-panel-job-id="{{ $job->id }}">
                            <div class="card-body">
                                <div class="d-flex align-items-start justify-content-between flex-wrap gap-2 mb-3">
                                    <div>
                                        <h5 class="mb-1">{{ $job->title }}</h5>
                                        <div class="text-muted small">{{ $job->location }} • {{ $job->employment_type }}</div>
                                    </div>
                                    <div class="d-flex gap-2 align-items-center">
                                        <span class="badge bg-primary">Total pelamar: {{ $count }}</span>
                                        <a href="{{ url('company/lowongan/'.$job->id) }}" class="btn btn-sm btn-outline-secondary">
                                            Lihat Lowongan
                                        </a>
                                    </div>
                                </div>

                                {{-- search pelamar dalam lowongan ini --}}
                                <div class="row g-2 mb-3">
                                    <div class="col-12 col-md-6">
                                        <input type="text" class="form-control form-control-sm applySearch"
                                               placeholder="Cari pelamar (nama/email)..."
                                               data-target="{{ $job->id }}">
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <select class="form-select form-select-sm applyFilterStatus" data-target="{{ $job->id }}">
                                            <option value="">Semua status</option>
                                            <option value="pending">Pending</option>
                                            <option value="accepted">Diterima</option>
                                            <option value="rejected">Ditolak</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table class="table align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th style="width:60px;">No</th>
                                                <th>Pelamar</th>
                                                <th style="width:140px;">Tanggal</th>
                                                <th style="width:120px;">Status</th>
                                                <th style="min-width:260px;">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody data-apply-body="{{ $job->id }}">
                                            @forelse ($job->applyJobs as $apply)
                                                @php
                                                    $status = $apply->status ?? 'pending';
                                                    $phone = $apply->user->phone ?? $apply->user->no_hp ?? $apply->user->phone_number ?? null;
                                                @endphp

                                                <tr class="apply-row"
                                                    data-name="{{ strtolower($apply->user->name ?? '') }}"
                                                    data-email="{{ strtolower($apply->user->email ?? '') }}"
                                                    data-status="{{ $status }}">
                                                    <td>{{ $loop->iteration }}</td>

                                                    {{-- Pelamar: nama + email + no HP --}}
                                                    <td>
                                                        <div class="fw-semibold">{{ $apply->user->name }}</div>
                                                        <div class="text-muted small">{{ $apply->user->email }}</div>
                                                        <div class="text-muted small">
                                                            <i class="bi bi-telephone me-1"></i>
                                                            {{ $phone ?: '-' }}
                                                        </div>
                                                    </td>

                                                    <td class="text-muted small">
                                                        {{ $apply->created_at->format('d M Y') }}
                                                    </td>

                                                    <td class="cell-status">
                                                        @if ($status === 'accepted')
                                                            <span class="badge bg-success">Diterima</span>
                                                        @elseif ($status === 'rejected')
                                                            <span class="badge bg-danger">Ditolak</span>
                                                        @else
                                                            <span class="badge bg-warning text-dark">Pending</span>
                                                        @endif
                                                    </td>

                                                    <td>
                                                        <div class="btn-toolbar flex-wrap gap-2">
                                                            <div class="btn-group btn-group-sm">
                                                                <a href="{{ url('company-applyjob/detail/' . $apply->id) }}"
                                                                   class="btn btn-outline-primary d-flex align-items-center gap-1">
                                                                    <i class="bi bi-eye"></i>
                                                                    <span>Detail</span>
                                                                </a>
                                                            </div>
                                                            <form
                                                                action="{{ url('company-applyjob/update-status/' . $apply->id) }}"
                                                                method="POST"
                                                                class="js-status-form d-flex align-items-stretch"
                                                                data-apply-id="{{ $apply->id }}"
                                                                data-user-name="{{ $apply->user->name }}"
                                                            >
                                                                @csrf
                                                                <input type="hidden" name="keterangan" class="js-note" value="{{ $apply->keterangan }}">
                                                                <div class="input-group input-group-sm" style="min-width:240px;max-width:360px;">
                                                                    <span class="input-group-text"><i class="bi bi-sliders"></i></span>
                                                                    <select name="status" class="form-select form-select-sm js-status">
                                                                        <option value="pending"  {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                                                                        <option value="accepted" {{ $status === 'accepted' ? 'selected' : '' }}>Diterima</option>
                                                                        <option value="rejected" {{ $status === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                                                                    </select>
                                                                    <button type="submit" class="btn btn-success js-save-btn d-flex align-items-center gap-1">
                                                                        <i class="bi bi-save"></i>
                                                                        <span>Simpan</span>
                                                                    </button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center text-muted">Belum ada pelamar.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>

{{-- toast --}}
<div class="lm-toast" id="lmToast">
    <div class="title" id="lmToastTitle">Berhasil</div>
    <div class="desc" id="lmToastDesc">Status lamaran diperbarui.</div>
</div>
@endsection

@push('scripts')
{{-- SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const jobButtons = document.querySelectorAll('.job-item-btn');
    const panels = document.querySelectorAll('.job-panel');
    const rightPanelTop = document.getElementById('rightPanelTop');

    // Toast helper
    const toastEl = document.getElementById('lmToast');
    const toastTitle = document.getElementById('lmToastTitle');
    const toastDesc = document.getElementById('lmToastDesc');
    let toastTimer = null;

    function showToast(title, desc) {
        if (!toastEl) return;
        toastTitle.textContent = title || 'Info';
        toastDesc.textContent = desc || '';
        toastEl.classList.add('show');
        clearTimeout(toastTimer);
        toastTimer = setTimeout(() => toastEl.classList.remove('show'), 2400);
    }

    function statusBadgeHtml(status) {
        if (status === 'accepted') return '<span class="badge bg-success">Diterima</span>';
        if (status === 'rejected') return '<span class="badge bg-danger">Ditolak</span>';
        return '<span class="badge bg-warning text-dark">Pending</span>';
    }

    // show panel + auto scroll top right panel (smooth)
    function showPanel(jobId) {
        panels.forEach(p => {
            p.classList.toggle('d-none', p.getAttribute('data-panel-job-id') !== String(jobId));
        });
        jobButtons.forEach(b => {
            b.classList.toggle('active', b.getAttribute('data-job-id') === String(jobId));
        });

        const activePanel = document.querySelector(`.job-panel[data-panel-job-id="${jobId}"]`);
        if (activePanel) {
            const target = rightPanelTop || activePanel;
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }

    jobButtons.forEach(btn => {
        btn.addEventListener('click', () => showPanel(btn.getAttribute('data-job-id')));
    });

    // Search lowongan (kiri)
    const jobSearch = document.getElementById('jobSearch');
    if (jobSearch) {
        jobSearch.addEventListener('input', function () {
            const q = this.value.toLowerCase().trim();
            jobButtons.forEach(btn => {
                const title = btn.getAttribute('data-job-title') || '';
                btn.style.display = title.includes(q) ? '' : 'none';
            });
        });
    }

    // Search + filter pelamar (per lowongan)
    function filterApplicants(jobId) {
        const qInput  = document.querySelector(`.applySearch[data-target="${jobId}"]`);
        const sSelect = document.querySelector(`.applyFilterStatus[data-target="${jobId}"]`);
        const q = (qInput?.value || '').toLowerCase().trim();
        const status = (sSelect?.value || '').trim();

        const tbody = document.querySelector(`tbody[data-apply-body="${jobId}"]`);
        if (!tbody) return;

        tbody.querySelectorAll('.apply-row').forEach(row => {
            const name  = row.getAttribute('data-name') || '';
            const email = row.getAttribute('data-email') || '';
            const st    = row.getAttribute('data-status') || '';

            const matchQ = !q || name.includes(q) || email.includes(q);
            const matchS = !status || st === status;

            row.style.display = (matchQ && matchS) ? '' : 'none';
        });
    }

    document.querySelectorAll('.applySearch').forEach(inp => {
        inp.addEventListener('input', () => filterApplicants(inp.getAttribute('data-target')));
    });

    document.querySelectorAll('.applyFilterStatus').forEach(sel => {
        sel.addEventListener('change', () => filterApplicants(sel.getAttribute('data-target')));
    });

    // =========================
    // AJAX Save Status (no reload) + SweetAlert2 reason when rejected
    // =========================
    document.querySelectorAll('.js-status-form').forEach(form => {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            const btn = form.querySelector('.js-save-btn');
            const select = form.querySelector('.js-status');
            const noteHidden = form.querySelector('.js-note');

            const row = form.closest('tr.apply-row');
            const statusCell = row ? row.querySelector('.cell-status') : null;

            if (!btn || !select) return;

            // if rejected -> ask reason (required)
            if (select.value === 'rejected') {
                const userName = form.getAttribute('data-user-name') || 'pelamar';
                const { value: reason, isConfirmed } = await Swal.fire({
                    title: 'Alasan penolakan',
                    html: `<div style="font-size:.9rem;color:#6c757d;margin-bottom:.5rem;">
                            Beri alasan kenapa <b>${userName}</b> ditolak.
                           </div>`,
                    input: 'textarea',
                    inputPlaceholder: 'Contoh: Kualifikasi belum sesuai / pengalaman kurang / dll.',
                    inputValue: (noteHidden?.value || ''),
                    showCancelButton: true,
                    confirmButtonText: 'Simpan',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                    inputValidator: (val) => {
                        if (!val || val.trim().length < 5) return 'Minimal 5 karakter ya.';
                    }
                });

                if (!isConfirmed) return;
                if (noteHidden) noteHidden.value = reason.trim();
            } else {
                // accepted/pending -> clear note
                if (noteHidden) noteHidden.value = '';
            }

            const originalText = btn.textContent;
            btn.disabled = true;
            btn.textContent = 'Menyimpan...';

            try {
                const url = form.getAttribute('action');
                const token = form.querySelector('input[name="_token"]')?.value;

                const payload = new FormData();
                payload.append('_token', token || '');
                payload.append('status', select.value);
                payload.append('keterangan', noteHidden ? noteHidden.value : '');

                const res = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: payload
                });

                const contentType = res.headers.get('content-type') || '';
                let data = null;

                if (contentType.includes('application/json')) data = await res.json();
                else data = { success: res.ok };

                if (!res.ok || data?.success === false) {
                    const msg = data?.message || 'Gagal menyimpan status. Coba lagi.';
                    showToast('Gagal', msg);
                    return;
                }

                // update UI row
                if (row) {
                    row.setAttribute('data-status', select.value);
                    if (statusCell) statusCell.innerHTML = statusBadgeHtml(select.value);

                    row.classList.add('row-saved');
                    setTimeout(() => row.classList.remove('row-saved'), 1200);
                }

                showToast('Berhasil', 'Status lamaran diperbarui.');
            } catch (err) {
                showToast('Error', 'Terjadi kesalahan jaringan.');
            } finally {
                btn.disabled = false;
                btn.textContent = originalText;
            }
        });
    });
});
</script>
@endpush
