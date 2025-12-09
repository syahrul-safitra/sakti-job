<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halman Admin</title>

    <link rel="stylesheet" href="{{ asset('assets/admindash/assets/compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admindash/assets/compiled/css/app-dark.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admindash/assets/compiled/css/iconly.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admindash/assets/extensions/sweetalert2/sweetalert2.css') }}">

    <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
    <script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>

    <script src="{{ asset('assets/admindash/assets/static/js/initTheme.js') }}"></script>

    <style>
        .trix-button-group--file-tools {
            display: none !important;
        }
    </style>
</head>

<body>
    <div id="app">

        @include('Company.Partial.sidebar')

        <div id="main" class="layout-navbar">

            @include('Company.Partial.navbar')

            <div id="main-content">
                @yield('content')
            </div>

        </div>
    </div>

    <script src="{{ asset('assets/admindash/assets/static/js/components/dark.js') }}"></script>
    <script src="{{ asset('assets/admindash/assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/admindash/assets/compiled/js/app.js') }}"></script>
    <script src="{{ asset('assets/admindash/assets/extensions/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/admindash/assets/static/js/pages/dashboard.js') }}"></script>
    <script src="{{ asset('assets/admindash/assets/extensions/sweetalert2/sweetalert2.min.js') }}"></script>
</body>

</html>
