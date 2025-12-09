<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Profil Saya - SaktiJob</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link href="{{ asset('assets/jobentry/img/favicon.ico') }}" rel="icon">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('assets/jobentry/lib/animate/animate.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/jobentry/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/jobentry/css/style.css') }}" rel="stylesheet">
</head>

<body>
    <style>
        .container-fixed {
            max-width: 960px;
            margin-left: auto;
            margin-right: auto;
        }
    </style>
    <nav class="navbar navbar-expand-lg bg-white navbar-light shadow sticky-top p-0">
        <div class="container-fixed d-flex align-items-center w-100">
            <a href="<?= base_url('/') ?>" class="navbar-brand d-flex align-items-center text-center py-0 px-4 px-lg-5">
                <h1 class="m-0 text-primary">Sakti<span style="color:#F28C28">Job</span></h1>
            </a>
            <button type="button" class="navbar-toggler ms-auto me-4" data-bs-toggle="collapse"
                data-bs-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <div class="navbar-nav ms-auto p-4 p-lg-0">
                    <a href="<?= base_url('lowongan') ?>" class="nav-item nav-link">Cari lowongan</a>
                    <?php $up = $profile ?? [];
                    $parts = preg_split('/\s+/', trim($up['full_name'] ?? ''));
                    $initials = strtoupper(substr($parts[0] ?? '', 0, 1) . (isset($parts[1]) ? substr($parts[1], 0, 1) : '')); ?>
                    <div class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userMenu"
                            role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <span
                                class="d-inline-flex justify-content-center align-items-center rounded-circle bg-primary text-white"
                                style="width:36px;height:36px;font-weight:600">
                                <?= !empty($initials) ? esc($initials) : '<i class="bi bi-person"></i>' ?>
                            </span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
                            <li><a class="dropdown-item" href="<?= base_url('user/profile/edit') ?>">Ubah Profil</a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="<?= base_url('logout') ?>">Logout</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="container my-4">
        <div class="p-5 rounded text-white" style="background:#0b2447; position:relative; overflow:hidden">
            <div class="d-flex align-items-start">
                <div class="me-4">
                    <?php $parts = preg_split('/\s+/', trim($up['full_name'] ?? ''));
                    $initials = strtoupper(substr($parts[0] ?? '', 0, 1) . (isset($parts[1]) ? substr($parts[1], 0, 1) : '')); ?>
                    <?php if (!empty($up['photo_url'])): ?>
                    <img src="<?= esc($up['photo_url']) ?>" alt="Foto Profil"
                        style="width:96px;height:96px;object-fit:cover;border-radius:50%;border:3px solid rgba(255,255,255,0.6)">
                    <?php else: ?>
                    <span class="d-inline-flex justify-content-center align-items-center rounded-circle"
                        style="width:96px;height:96px;background:#1b4db1;color:#fff;font-weight:700;font-size:32px;border:3px solid rgba(255,255,255,0.6)"><?= esc($initials ?: 'U') ?></span>
                    <?php endif; ?>
                </div>
                <div class="flex-grow-1">
                    <h2 class="fw-bold mb-3"><?= esc($up['full_name'] ?? 'Nama Lengkap') ?></h2>
                    <div class="mb-3">
                        <div class="mb-1"><i class="bi bi-geo-alt me-2"></i><?= esc($up['location'] ?? '-') ?></div>
                        <div class="mb-1"><i class="bi bi-envelope me-2"></i><?= esc($email ?? '-') ?></div>
                        <?php if (!empty($up['profile_link'])): ?>
                        <div class="mb-1"><i class="bi bi-link-45deg me-2"></i><a class="text-white"
                                href="<?= esc($up['profile_link']) ?>" target="_blank"
                                rel="noopener"><?= esc($up['profile_link']) ?></a></div>
                        <?php endif; ?>
                    </div>
                    <button class="btn btn-light" type="button" data-bs-toggle="offcanvas"
                        data-bs-target="#editProfilePanel" aria-controls="editProfilePanel">Ubah</button>
                </div>
            </div>
        </div>

        <div class="offcanvas offcanvas-end" tabindex="-1" id="editProfilePanel"
            aria-labelledby="editProfilePanelLabel" style="width: 480px;">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="editProfilePanelLabel">Ubah informasi pribadi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <form action="<?= base_url('user/profile') ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="full_name" class="form-control"
                            value="<?= esc($up['full_name'] ?? '') ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Lokasi rumah</label>
                        <input type="text" name="location" class="form-control"
                            value="<?= esc($up['location'] ?? '') ?>">
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Nomor telepon</label>
                            <input type="text" name="phone" class="form-control"
                                value="<?= esc($up['phone'] ?? '') ?>" placeholder="contoh: +62 8123456789">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <input type="text" name="address" class="form-control"
                            value="<?= esc($up['address'] ?? '') ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alamat email</label>
                        <input type="email" class="form-control" value="<?= esc($email ?? '') ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Foto Profil (URL)</label>
                        <input type="url" name="photo_url" class="form-control"
                            value="<?= esc($up['photo_url'] ?? '') ?>" placeholder="https://...">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Link Profil Publik</label>
                        <input type="url" name="profile_link" class="form-control"
                            value="<?= esc($up['profile_link'] ?? '') ?>" placeholder="https://...">
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <button type="button" class="btn btn-outline-secondary"
                            data-bs-dismiss="offcanvas">Batal</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-lg-8">
                <h5 class="mb-3 d-flex justify-content-between align-items-center">
                    <span>Ringkasan pribadi</span>
                    <a href="#" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal"
                        data-bs-target="#modalSummary"><?= empty($up['summary'] ?? '') ? 'Tambah ringkasan' : 'Ubah ringkasan' ?></a>
                </h5>
                <div class="card mb-4">
                    <div class="card-body">
                        <p class="mb-0 text-muted"><?= esc($up['summary'] ?? 'Belum ada ringkasan.') ?></p>
                    </div>
                </div>
            </div>

        </div>

        <div class="row">
            <div class="col-lg-8">
                <h5 class="mb-3">Riwayat karier</h5>
                <div class="card mb-3">
                    <div class="card-body">
                        <?php $career = $up['experience'] ?? null; ?>
                        <?php if($career): ?>
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="mb-1">Pengalaman Kerja</h6>
                                <div class="text-muted">Detail pengalaman</div>
                            </div>
                            <a href="#" class="text-secondary" data-bs-toggle="modal"
                                data-bs-target="#modalExperience"><i class="bi bi-pencil"></i></a>
                        </div>
                        <div class="mt-2 text-muted"><?= esc($career) ?></div>
                        <a href="#" class="d-inline-block mt-2">Lebih banyak</a>
                        <?php else: ?>
                        <div class="text-muted">Belum ada riwayat karier.</div>
                        <?php endif; ?>
                    </div>
                </div>
                <a href="#" class="btn btn-outline-primary mb-4" data-bs-toggle="modal"
                    data-bs-target="#modalExperience">Tambah jabatan</a>

                <h5 class="mb-3">Pendidikan</h5>
                <div class="card mb-3">
                    <div class="card-body">
                        <?php $edu = $up['education'] ?? null; ?>
                        <?php if($edu): ?>
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="mb-1"><?= esc($edu['degree'] ?? 'Pendidikan') ?></h6>
                                <div class="text-muted"><?= esc($edu['school'] ?? '') ?></div>
                                <div class="text-muted small"><?= esc($edu['period'] ?? '') ?></div>
                                <div class="mt-2 text-muted"><?= esc($edu['notes'] ?? '') ?></div>
                            </div>
                            <a href="#" class="text-secondary" data-bs-toggle="modal"
                                data-bs-target="#modalEducation"><i class="bi bi-pencil"></i></a>
                        </div>
                        <?php else: ?>
                        <div class="text-muted">Belum ada pendidikan.</div>
                        <?php endif; ?>
                    </div>
                </div>
                <a href="#" class="btn btn-outline-primary mb-4" data-bs-toggle="modal"
                    data-bs-target="#modalEducation">Tambah pendidikan</a>

                <h5 class="mb-3">Lisensi & sertifikasi</h5>
                <div class="card mb-3">
                    <div class="card-body">
                        <?php $certs = $up['certifications'] ?? []; ?>
                        <?php if(!empty($certs)): ?>
                        <?php foreach($certs as $c): ?>
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <h6 class="mb-1"><?= esc($c['name'] ?? '') ?></h6>
                                <div class="text-muted small"><?= esc($c['issuer'] ?? '') ?> •
                                    <?= esc($c['date'] ?? '') ?></div>
                            </div>
                            <a href="#" class="text-secondary" data-bs-toggle="modal"
                                data-bs-target="#modalCertifications"><i class="bi bi-pencil"></i></a>
                        </div>
                        <?php endforeach; ?>
                        <?php else: ?>
                        <div class="text-muted">Belum ada lisensi/sertifikasi.</div>
                        <?php endif; ?>
                    </div>
                </div>
                <a href="#" class="btn btn-outline-primary mb-4" data-bs-toggle="modal"
                    data-bs-target="#modalCertifications">Tambah lisensi atau sertifikasi</a>
            </div>

            <div class="col-lg-4">
                <h5 class="mb-3">Keahlian</h5>
                <div class="card mb-3">
                    <div class="card-body">
                        <?php $skills = $up['skills'] ?? []; ?>
                        <?php if(!empty($skills)): ?>
                        <?php foreach($skills as $s): ?>
                        <span class="badge bg-light text-dark border me-2 mb-2"><?= esc($s) ?></span>
                        <?php endforeach; ?>
                        <?php else: ?>
                        <div class="text-muted">Belum ada keahlian.</div>
                        <?php endif; ?>
                    </div>
                </div>
                <a href="#" class="btn btn-outline-primary mb-4" data-bs-toggle="modal"
                    data-bs-target="#modalSkills">Tambah keahlian</a>

                <h5 class="mb-3">Bahasa</h5>
                <div class="card mb-3">
                    <div class="card-body">
                        <?php $langs = $up['languages'] ?? []; ?>
                        <?php if(!empty($langs)): ?>
                        <?php foreach($langs as $lang): ?>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div><?= esc($lang['name'] ?? '') ?></div>
                            <a href="#" class="text-secondary" data-bs-toggle="modal"
                                data-bs-target="#modalLanguages"><i class="bi bi-pencil"></i></a>
                        </div>
                        <?php endforeach; ?>
                        <?php else: ?>
                        <div class="text-muted">Belum ada bahasa.</div>
                        <?php endif; ?>
                    </div>
                </div>
                <a href="#" class="btn btn-outline-primary mb-4" data-bs-toggle="modal"
                    data-bs-target="#modalLanguages">Tambah bahasa</a>

                <h5 class="mb-3">Resume</h5>
                <div class="card mb-3">
                    <div class="card-body">
                        <?php $cv = $up['cv_url'] ?? null; ?>
                        <?php if($cv): ?>
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="fw-bold mb-1">CV</div>
                                <a href="<?= esc($cv) ?>" target="_blank" rel="noopener"><?= esc($cv) ?></a>
                                <div class="text-muted small mt-1">Resume ini dapat dilihat oleh perusahaan.</div>
                            </div>
                            <a href="#" class="text-secondary" data-bs-toggle="modal"
                                data-bs-target="#modalResume"><i class="bi bi-pencil"></i></a>
                        </div>
                        <?php else: ?>
                        <div class="text-muted">Belum mengunggah resume.</div>
                        <?php endif; ?>
                    </div>
                </div>
                <a href="#" class="btn btn-outline-primary mb-4" data-bs-toggle="modal"
                    data-bs-target="#modalResume">Tambah atau kelola resume</a>
                <a href="#" class="btn btn-primary">Buat resume</a>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalSummary" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ringkasan pribadi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="<?= base_url('user/profile') ?>" method="post">
                    <div class="modal-body">
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <label class="form-label">Ringkasan</label>
                            <textarea name="summary" class="form-control" rows="4"><?= esc($up['summary'] ?? '') ?></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary"
                            data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalExperience" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Riwayat karier</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="<?= base_url('user/profile') ?>" method="post">
                    <div class="modal-body">
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <label class="form-label">Pengalaman Kerja</label>
                            <textarea name="experience" class="form-control" rows="4"><?= esc($up['experience'] ?? '') ?></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary"
                            data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalEducation" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Pendidikan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="<?= base_url('user/profile') ?>" method="post">
                    <div class="modal-body">
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <label class="form-label">Gelar/Program Studi</label>
                            <input type="text" name="education_degree" class="form-control"
                                value="<?= esc($up['education']['degree'] ?? '') ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Institusi</label>
                            <input type="text" name="education_school" class="form-control"
                                value="<?= esc($up['education']['school'] ?? '') ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Periode</label>
                            <input type="text" name="education_period" class="form-control"
                                value="<?= esc($up['education']['period'] ?? '') ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Catatan</label>
                            <input type="text" name="education_notes" class="form-control"
                                value="<?= esc($up['education']['notes'] ?? '') ?>">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary"
                            data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalCertifications" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Lisensi & sertifikasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="<?= base_url('user/profile') ?>" method="post">
                    <div class="modal-body">
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <label class="form-label">Daftar (Nama|Penerbit|Tanggal, satu per baris)</label>
                            <textarea name="certifications_text" class="form-control" rows="4"><?php
                            $certs = $up['certifications'] ?? [];
                            $lines = [];
                            foreach ($certs as $c) {
                                if (is_array($c)) {
                                    $lines[] = ($c['name'] ?? '') . '|' . ($c['issuer'] ?? '') . '|' . ($c['date'] ?? '');
                                } else {
                                    $lines[] = $c;
                                }
                            }
                            echo esc(implode("\n", $lines));
                            ?></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary"
                            data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalSkills" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Keahlian</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="<?= base_url('user/profile') ?>" method="post">
                    <div class="modal-body">
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <label class="form-label">Pisahkan dengan koma</label>
                            <input type="text" name="skills_text" class="form-control"
                                value="<?= esc(isset($up['skills']) ? implode(', ', $up['skills']) : '') ?>">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary"
                            data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalLanguages" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Bahasa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="<?= base_url('user/profile') ?>" method="post">
                    <div class="modal-body">
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <label class="form-label">Pisahkan dengan koma</label>
                            <?php $langs = $up['languages'] ?? [];
                            $langNames = [];
                            foreach ($langs as $l) {
                                $langNames[] = is_array($l) ? $l['name'] ?? '' : $l;
                            } ?>
                            <input type="text" name="languages_text" class="form-control"
                                value="<?= esc(implode(', ', $langNames)) ?>">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary"
                            data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalResume" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Resume</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="<?= base_url('user/profile') ?>" method="post">
                    <div class="modal-body">
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <label class="form-label">URL CV</label>
                            <input type="url" name="cv_url" class="form-control"
                                value="<?= esc($up['cv_url'] ?? '') ?>" placeholder="https://...">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary"
                            data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
