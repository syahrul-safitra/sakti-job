<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>

    <body>

        <div class="container mt-4">

            @if (session("success"))
                <div class="alert alert-success">{{ session("success") }}</div>
            @endif

            <h3>Edit User Profile</h3>

            <form method="POST" action="{{ url("edit-profile-user/" . $user->id) }}" enctype="multipart/form-data">
                @csrf
                @method("PUT")

                {{-- Full Name --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">Full Name</label>
                    <input type="text" name="full_name" class="form-control @error("full_name") is-invalid @enderror"
                        value="{{ old("full_name", $user->full_name) }}" required>
                    @error("full_name")
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">Email</label>
                    <input type="email" name="email" class="form-control @error("email") is-invalid @enderror"
                        value="{{ old("email", $user->email) }}" required>
                    @error("email")
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">Password (isi jika ingin diganti)</label>
                    <input type="password" name="password" class="form-control @error("password") is-invalid @enderror">
                    @error("password")
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Phone --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">Phone</label>
                    <input type="text" name="phone" class="form-control @error("phone") is-invalid @enderror"
                        value="{{ old("phone", $user->phone) }}">
                    @error("phone")
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Address --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">Address</label>
                    <textarea name="address" class="form-control @error("address") is-invalid @enderror" rows="3">{{ old("address", $user->address) }}</textarea>
                    @error("address")
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Photo --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">Photo</label>
                    <input type="file" name="photo" class="form-control @error("photo") is-invalid @enderror"
                        accept="image/*" onchange="previewPhoto(event)">
                    <img id="photoPreview" class="img-thumbnail {{ $user->photo ? "" : "d-none" }} mt-2" width="150"
                        src="{{ $user->photo ? asset("FileUpload/" . $user->photo) : "" }}">
                    @error("photo")
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- File CV --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">File CV (PDF)</label>
                    <input type="file" name="file_cv" class="form-control @error("file_cv") is-invalid @enderror"
                        accept="application/pdf" onchange="previewCV(event)">
                    <span id="cvPreview"
                        class="d-block text-muted mt-1">{{ $user->file_cv ? basename($user->file_cv) : "" }}</span>
                    @error("file_cv")
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <hr>

                {{-- Buttons untuk modals --}}
                <div class="mb-3">
                    <button type="button" class="btn btn-primary me-2" data-bs-toggle="modal"
                        data-bs-target="#educationModal">Education</button>
                    <button type="button" class="btn btn-secondary me-2" data-bs-toggle="modal"
                        data-bs-target="#certModal">Certifications</button>
                    <button type="button" class="btn btn-warning me-2" data-bs-toggle="modal"
                        data-bs-target="#langModal">Languages</button>
                    <button type="button" class="btn btn-info me-2" data-bs-toggle="modal"
                        data-bs-target="#expModal">Experiences</button>
                    <button type="button" class="btn btn-dark" data-bs-toggle="modal"
                        data-bs-target="#skillsModal">Skills</button>
                </div>

                {{-- Hidden fields untuk JSON --}}
                <input type="hidden" name="education_json" id="education_json"
                    value="{{ old("education_json", $user->education_json) }}">
                <input type="hidden" name="certifications_json" id="certifications_json"
                    value="{{ old("certifications_json", $user->certifications_json) }}">
                <input type="hidden" name="languages_json" id="languages_json"
                    value="{{ old("languages_json", $user->languages_json) }}">
                <input type="hidden" name="experiences_json" id="experiences_json"
                    value="{{ old("experiences_json", $user->experiences_json) }}">
                <input type="hidden" name="skills_json" id="skills_json"
                    value="{{ old("skills_json", $user->skills_json) }}">

                <button class="btn btn-success mt-3">Update</button>
            </form>
        </div>

        {{-- ================= MODALS ================= --}}

        {{-- Education Modal --}}
        <div class="modal fade" id="educationModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Education</h5><button class="btn-close"
                            data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <label class="fw-bold">Program Studi</label>
                        <input type="text" id="edu_program" class="form-control mb-2">
                        <label class="fw-bold">Institusi</label>
                        <input type="text" id="edu_institusi" class="form-control mb-2">
                        <label class="fw-bold">Tahun Masuk</label>
                        <input type="number" id="edu_tahun" class="form-control mb-2">
                        <label class="fw-bold">IPK / GPA</label>
                        <input type="number" step="0.01" id="edu_gpa" class="form-control mb-2">
                        <label class="fw-bold">Catatan</label>
                        <textarea id="edu_catatan" class="form-control mb-2" rows="2"></textarea>
                    </div>
                    <div class="modal-footer"><button class="btn btn-primary"
                            onclick="saveEducation()">Simpan</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Certifications Modal --}}
        <div class="modal fade" id="certModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Certifications</h5><button class="btn-close"
                            data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div id="cert_container"></div>
                        <button type="button" class="btn btn-sm btn-primary mt-2"
                            onclick="addCertItem()">Tambah</button>
                    </div>
                    <div class="modal-footer"><button class="btn btn-success"
                            onclick="saveCertifications()">Simpan</button></div>
                </div>
            </div>
        </div>

        {{-- Languages Modal --}}
        <div class="modal fade" id="langModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Languages</h5><button class="btn-close"
                            data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div id="lang_container"></div>
                        <button type="button" class="btn btn-sm btn-primary mt-2"
                            onclick="addLangItem()">Tambah</button>
                    </div>
                    <div class="modal-footer"><button class="btn btn-success"
                            onclick="saveLanguages()">Simpan</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Experiences Modal --}}
        <div class="modal fade" id="expModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Experiences</h5><button class="btn-close"
                            data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div id="exp_container"></div>
                        <button type="button" class="btn btn-sm btn-primary mt-2"
                            onclick="addExpItem()">Tambah</button>
                    </div>
                    <div class="modal-footer"><button class="btn btn-success"
                            onclick="saveExperiences()">Simpan</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Skills Modal --}}
        <div class="modal fade" id="skillsModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Skills</h5><button class="btn-close"
                            data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div id="skills_container"></div>
                        <button type="button" class="btn btn-sm btn-primary mt-2"
                            onclick="addSkillItem()">Tambah</button>
                    </div>
                    <div class="modal-footer"><button class="btn btn-success" onclick="saveSkills()">Simpan</button>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            document.addEventListener("DOMContentLoaded", function() {

                // Preview Photo
                window.previewPhoto = function(event) {
                    const img = document.getElementById("photoPreview");
                    img.src = URL.createObjectURL(event.target.files[0]);
                    img.classList.remove("d-none");
                }

                // Preview CV
                window.previewCV = function(event) {
                    const file = event.target.files[0];
                    document.getElementById("cvPreview").textContent = file ? file.name : '';
                }

                // ----------------- Education -----------------
                let oldEduRaw = @json(old("education_json", $user->education_json));
                let oldEdu = {};
                try {
                    oldEdu = oldEduRaw ? JSON.parse(oldEduRaw) : {};
                } catch (e) {
                    oldEdu = {};
                }

                document.getElementById('edu_program').value = oldEdu.program_studi ?? '';
                document.getElementById('edu_institusi').value = oldEdu.institusi ?? '';
                document.getElementById('edu_tahun').value = oldEdu.tahun_masuk ?? '';
                document.getElementById('edu_gpa').value = oldEdu.gpa ?? '';
                document.getElementById('edu_catatan').value = oldEdu.catatan ?? '';

                window.saveEducation = function() {
                    const data = {
                        program_studi: document.getElementById("edu_program").value,
                        institusi: document.getElementById("edu_institusi").value,
                        tahun_masuk: document.getElementById("edu_tahun").value,
                        gpa: document.getElementById("edu_gpa").value,
                        catatan: document.getElementById("edu_catatan").value
                    };
                    document.getElementById("education_json").value = JSON.stringify(data);
                    alert("Education JSON tersimpan!");
                }

                // ----------------- Dynamic Items Helper -----------------
                function initDynamic(containerId, oldRaw) {
                    const container = document.getElementById(containerId);
                    let oldArr = [];
                    try {
                        oldArr = oldRaw ? JSON.parse(oldRaw) : [];
                    } catch (e) {
                        oldArr = [];
                    }

                    function addItem(value = "") {
                        const html = `<div class="input-group mb-2">
                            <input type="text" class="form-control" value="${value}">
                            <button type="button" class="btn btn-danger" onclick="this.parentNode.remove()">X</button>
                          </div>`;
                        container.insertAdjacentHTML("beforeend", html);
                    }

                    oldArr.forEach(v => addItem(v));
                    return {
                        addItem
                    };
                }

                // Certifications
                const certHelper = initDynamic('cert_container', @json(old("certifications_json", $user->certifications_json)));
                window.addCertItem = () => certHelper.addItem();
                window.saveCertifications = () => {
                    const arr = [];
                    document.querySelectorAll('#cert_container input').forEach(i => {
                        if (i.value.trim()) arr.push(i.value.trim());
                    });
                    document.getElementById('certifications_json').value = JSON.stringify(arr);
                    alert('Certifications JSON tersimpan!');
                }

                // Languages
                const langHelper = initDynamic('lang_container', @json(old("languages_json", $user->languages_json)));
                window.addLangItem = () => langHelper.addItem();
                window.saveLanguages = () => {
                    const arr = [];
                    document.querySelectorAll('#lang_container input').forEach(i => {
                        if (i.value.trim()) arr.push(i.value.trim());
                    });
                    document.getElementById('languages_json').value = JSON.stringify(arr);
                    alert('Languages JSON tersimpan!');
                }

                // Experiences
                const expHelper = initDynamic('exp_container', @json(old("experiences_json", $user->experiences_json)));
                window.addExpItem = () => expHelper.addItem();
                window.saveExperiences = () => {
                    const arr = [];
                    document.querySelectorAll('#exp_container input').forEach(i => {
                        if (i.value.trim()) arr.push(i.value.trim());
                    });
                    document.getElementById('experiences_json').value = JSON.stringify(arr);
                    alert('Experiences JSON tersimpan!');
                }

                // Skills
                const skillHelper = initDynamic('skills_container', @json(old("skills_json", $user->skills_json)));
                window.addSkillItem = () => skillHelper.addItem();
                window.saveSkills = () => {
                    const arr = [];
                    document.querySelectorAll('#skills_container input').forEach(i => {
                        if (i.value.trim()) arr.push(i.value.trim());
                    });
                    document.getElementById('skills_json').value = JSON.stringify(arr);
                    alert('Skills JSON tersimpan!');
                }

            });
        </script>

    </body>

</html>
