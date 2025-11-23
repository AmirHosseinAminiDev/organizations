<!DOCTYPE html>
<html lang="ar" dir="rtl">

<!-- Mirrored from filenter.ir/Noble/template/demo1-rtl/pages/general/blank-page.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 11 Nov 2025 07:04:55 GMT -->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="Responsive HTML Admin Dashboard Template based on Bootstrap 5">
    <meta name="author" content="NobleUI">
    <meta name="keywords" content="nobleui, bootstrap, bootstrap 5, bootstrap5, admin, dashboard, template, responsive, css, sass, html, theme, front-end, ui kit, web">

    <title>پنل سازمان ها</title>

    <script src="{{ asset('assets/js/color-modes.js') }}"></script>

    <link rel="stylesheet" href="{{ asset('assets/vendors/core/core.css') }}">

    <!-- Plugin css for this page -->
    <!-- End plugin css for this page -->

    <!-- inject:css -->
    <link rel="stylesheet" href="{{ asset('assets/fonts/feather-font/css/iconfont.css') }}">
    <!-- endinject -->

    <!-- Layout styles -->
    <link rel="stylesheet" href="{{ asset('assets/css/demo1/style-rtl.css') }}">
    <!-- End layout styles -->

    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}" />

    @stack('css')
</head>
<body>
<div class="main-wrapper">

    <!-- partial:../../partials/_sidebar.html -->
    @include('layout.sidebar')
    <!-- partial -->

    <div class="page-wrapper">
        @include('layout.navbar')
        <!-- partial:../../partials/_navbar.html -->

        <!-- partial -->

        <div class="page-content">
        @yield('content')
        </div>

        <!-- partial:../../partials/_footer.html -->
        <footer
            class="footer d-flex flex-row align-items-center justify-content-between px-4 py-3 border-top small">
            <p class="text-secondary mb-1 mb-md-0">کپی رایت © 2020، تمام حقوق محفوظ است</a>.
            </p>
        </footer>
        <!-- partial -->

    </div>
</div>

<!-- core:js -->
<script src="{{ asset('assets/vendors/core/core.js') }}"></script>
<script src="{{ asset('assets/vendors/jquery/jquery.min.js') }}"></script>
<!-- endinject -->

<!-- Plugin js for this page -->
<!-- End plugin js for this page -->

<!-- inject:js -->
<script src="{{ asset('assets/vendors/feather-icons/feather.min.js') }}"></script>
<script src="{{ asset('assets/js/app.js') }}"></script>
<!-- endinject -->
@stack('js')
</body>

</html>
