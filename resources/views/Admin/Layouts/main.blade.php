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

    {{-- Bootstrap Icon --}}
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.13.1/font/bootstrap-icons.min.css"
        integrity="sha512-t7Few9xlddEmgd3oKZQahkNI4dS6l80+eGEzFQiqtyVYdvcSG2D3Iub77R20BdotfRPA9caaRkg1tyaJiPmO0g=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <script src="{{ asset('assets/admindash/assets/static/js/initTheme.js') }}"></script>
</head>

<body>
    <div id="app">

        @include('Admin.Partial.sidebar')

        <div id="main" class="layout-navbar">

            @include('Admin.Partial.navbar')

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
