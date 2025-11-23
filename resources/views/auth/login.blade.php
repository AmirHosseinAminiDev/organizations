<!DOCTYPE html>
<html lang="ar" dir="rtl">

<!-- Mirrored from filenter.ir/Noble/template/demo1-rtl/pages/auth/login.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 11 Nov 2025 07:04:56 GMT -->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="Responsive HTML Admin Dashboard Template based on Bootstrap 5">
    <meta name="author" content="NobleUI">
    <meta name="keywords" content="nobleui, bootstrap, bootstrap 5, bootstrap5, admin, dashboard, template, responsive, css, sass, html, theme, front-end, ui kit, web">

    <title>NobleUI - قالب HTML مدیریتی بوت استرپ 5</title>

    <!-- color-modes:js -->
    <script src="{{ asset('assets/js/color-modes.js') }}"></script>
    <!-- endinject -->

    <!-- core:css -->
    <link rel="stylesheet" href="{{ asset('assets/vendors/core/core.css') }}">
    <!-- endinject -->

    <!-- Plugin css for this page -->
    <!-- End plugin css for this page -->

    <!-- inject:css -->
    <link rel="stylesheet" href="{{ asset('assets/fonts/feather-font/css/iconfont.css') }}">
    <!-- endinject -->

    <!-- Layout styles -->
    <link rel="stylesheet" href="{{ asset('assets/css/demo1/style-rtl.css') }}">
    <!-- End layout styles -->

    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}" />
</head>
<body>
<div class="main-wrapper">
    <div class="page-wrapper full-page">
        <div class="page-content d-flex align-items-center justify-content-center">

            <div class="row w-100 mx-0 auth-page">
                <div class="col-md-8 col-xl-6 mx-auto">
                    <div class="card">
                        <div class="row">
                            <div class="col-md-4 pe-md-0">
                                <div class="auth-side-wrapper">

                                </div>
                            </div>
                            <div class="col-md-8 ps-md-0">
                                @if ($errors->any())
                                    @foreach ($errors->all() as $error)
                                        <div class="alert alert-danger">
                                            {{ $error }}
                                        </div>
                                    @endforeach
                                @endif

                                <div class="auth-form-wrapper px-4 py-5">
                                    <h5 class="text-muted fw-normal mb-4">خوش آمدید! به حساب کاربری خود وارد شوید
                                    </h5>
                                    <form method="POST" action="{{ route('send-otp') }}" class="forms-sample">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="phone" class="form-label">موبایل</label>
                                            <input type="text" class="form-control" id="phone" name="phone"
                                                   placeholder="شماره تلفن همراه...">
                                            @error('phone')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <button type="submit" class="btn btn-success">ارسال</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
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

<!-- Custom js for this page -->
<!-- End custom js for this page -->

</body>

</html>
