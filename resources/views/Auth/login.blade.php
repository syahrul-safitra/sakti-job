<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SaktiJob</title>



    <link rel="shortcut icon" href="{{ asset('assets/admindash/assets/compiled/svg/favicon.svg') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('assets/admindash/assets/compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admindash/assets/compiled/css/app-dark.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admindash/assets/compiled/css/auth.css') }}">
</head>

<body>
    <script src="{{ asset('assets/admindash/assets/compiled/js/app.js') }}"></script>
    <div id="auth">

        <div class="row min-vh-100">
            <div class="col-lg-5 col-12 d-flex align-items-center justify-content-center">
                <div id="auth-left"
                    style="max-width:420px;width:100%;background:#fff;border-radius:16px;padding:32px;box-shadow:0 12px 30px rgba(11,36,71,0.12)">

                    <h1 class="auth-title fs-4 text-primary" style="margin-bottom:8px">Masuk</h1>
                    <p class="auth-subtitle mb-4 fs-6">Gunakan Email & Kata Sandi</p>

                    @session('loginFailed')
                        <div class="alert alert-danger mt-3" role="alert">
                            {{ session('loginFailed') }}
                        </div>
                    @endsession

                    @session('success')
                        <div class="alert alert-success mt-3" role="alert">
                            {{ session('success') }}
                        </div>
                    @endsession

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

                    <form action="{{ url('login') }}" method="post">
                        @csrf

                        <div class="form-group position-relative has-icon-left mb-4">
                            <input type="email" name="email" class="form-control" placeholder="Email">
                            <div class="form-control-icon">
                                <i class="bi bi-person"></i>
                            </div>
                        </div>
                        <div class="form-group position-relative has-icon-left mb-4">
                            <input type="password" name="password" class="form-control" placeholder="Kata Sandi">
                            <div class="form-control-icon">
                                <i class="bi bi-shield-lock"></i>
                            </div>
                        </div>
                        {{-- <div class="form-check form-check-lg d-flex align-items-end">
                            <input class="form-check-input me-2" type="checkbox" value="" id="flexCheckDefault">
                            <label class="form-check-label text-gray-600" for="flexCheckDefault">
                                Biarkan Saya Tetap Masuk
                            </label>
                        </div> --}}
                        <button type="submit" class="btn btn-primary btn-block shadow-lg mt-4">Masuk</button>
                    </form>

                    <div class="text-center mt-5 text-lg">
                        <p class="text-gray-600 fs-6">Tidak Mempunyai akun? <a href="{{ url('register-company') }}"
                                class="font-bold fs-6">Daftar Perusahaan</a> atau <a href="{{ url('register-user') }}"
                                class="font-bold fs-6">Daftar User</a>.</p>
                        <p class="fs-6"><a class="font-bold fs-6" href="auth-forgot-password.html">Lupa Password?</a>.
                        </p>
                    </div>

                </div>
            </div>
            <div class="col-lg-7 d-none d-lg-block d-flex align-items-center justify-content-center">
                <div id="auth-right" class="d-flex align-items-center justify-content-center"
                    style="height:100vh;border-radius:25px 0 0 25px;overflow:hidden;position:relative;background: linear-gradient(145deg,rgba(11, 36, 71, 1) 0%, rgba(30, 136, 229, 1) 50%, rgba(242, 140, 40, 1) 100%);">
                    <img src="{{ asset('assets/admindash/assets/compiled/bkt.jpg') }}" alt="Background"
                        style="max-width:100%;max-height:100%;object-fit:cover;border-radius:70px 0 70px 0;">
                    <div style="position:absolute;inset:0;background:rgba(0,0,0,0.35);"></div>
                </div>
            </div>
        </div>

    </div>
</body>

</html>
