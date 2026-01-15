@extends('Landing.Layouts.main')

@section('content')
    <div class="container-fliud py-5">
        <div class="container">
            @session('error')
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endsession

            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="job-item p-4 mb-4">
                        <div class="d-flex align-items-center mb-3">
                            <img class="flex-shrink-0 img-fluid border rounded"
                                src="{{ asset('FileUpload/' . $job->gambar) }}" alt=""
                                style="width: 80px; height: 80px;">
                            <div class="ps-4 text-start">
                                <h3 class="mb-1">{{ $job->title }}</h3>
                                <div class="text-muted small mb-2">{{ $job->company->name }}</div>
                                <div class="mb-2">
                                    <span class="me-3">
                                        <i class="fa fa-map-marker-alt text-primary me-2"></i>{{ $job->location }}
                                    </span>
                                    <span class="me-3">
                                        <i class="far fa-clock text-primary me-2"></i>{{ $job->employment_type }}
                                    </span>
                                    <span class="me-3">
                                        <i class="far fa-money-bill-alt text-primary me-2"></i>
                                        {{ 'Rp .' . number_format($job->salary_min, 0, ',', '.') }} -
                                        {{ 'Rp .' . number_format($job->salary_max, 0, ',', '.') }} per month
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3">
                            <h5>Deskripsi Pekerjaan</h5>
                            <div class="text-muted" style="white-space:pre-line">
                                {!! $job->description !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="rounded border p-4">
                        <button class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#applyModal">
                            Lamar Sekarang
                        </button>
                        <div class="text-muted small mt-3">
                            Dipublikasikan: {{ $job->created_at->diffForHumans() }}
                        </div>
                        @if (Auth::guard('user')->check())
                            <form action="{{ url('user/saved-jobs/toggle/' . $job->id) }}" method="POST" class="mt-3">
                                @csrf
                                <button type="submit" class="btn btn-outline-secondary w-100">
                                    Simpan Lowongan
                                </button>
                            </form>
                        @endif
                    </div>

                    <div class="mt-3 rounded border p-4">
                        <h6 class="mb-2">Tentang Pemberi Kerja</h6>
                        <div class="text-muted" style="white-space:pre-line">{!! $job->company->description !!}</div>
                        @if ($job->company->link_website)
                            <div class="mt-2">
                                <a href="{{ $job->company->link_website }}" target="_blank">Website</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="applyModal" tabindex="-1" aria-labelledby="applyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="applyModalLabel">Lamar Pekerjaan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ url('/apply-job/' . $job->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="user_id" value="{{ Auth::guard('user')->user()->id }}">
                    <input type="hidden" name="job_id" value="{{ $job->id }}">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Catatan / Cover Letter (Opsional)</label>
                            <textarea name="cover_letter" class="form-control" rows="4" maxlength="1000"
                                placeholder="Tulis pesan untuk HR atau alasan melamar pekerjaan ini">{{ old('cover_letter') }}</textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Kirim Lamaran</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
