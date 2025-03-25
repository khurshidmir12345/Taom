<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Lara Taom </title>
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="{{ asset('src/assets/vendors/select2/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('src/assets/vendors/select2-bootstrap-theme/select2-bootstrap.min.css') }}">

    <!-- plugins:css -->
    <link rel="stylesheet" href="{{asset('/src/assets/vendors/feather/feather.css')}}">
    <link rel="stylesheet" href="{{asset('/src/assets/vendors/mdi/css/materialdesignicons.min.css')}}">
    <link rel="stylesheet" href="{{asset('/src/assets/vendors/ti-icons/css/themify-icons.css')}}">
    <link rel="stylesheet" href="{{asset('/src/assets/vendors/font-awesome/css/font-awesome.min.css')}}">
    <link rel="stylesheet" href="{{asset('/src/assets/vendors/typicons/typicons.css')}}">
    <link rel="stylesheet" href="{{asset('/src/assets/vendors/simple-line-icons/css/simple-line-icons.css')}}">
    <link rel="stylesheet" href="{{asset('/src/assets/vendors/css/vendor.bundle.base.css')}}">
    <link rel="stylesheet" href="{{asset('/src/assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css')}}">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/css/bootstrap-theme.min.css" integrity="sha384-6pzBo3FDv/PJ8r2KRkGHifhEocL+1X2rVCTTkUfGk7/0pbek5mMa1upzvWbrUbOZ" crossorigin="anonymous">
    @yield('styles')
    @livewireStyles

    <!-- endinject -->
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="{{asset('/src/assets/vendors/datatables.net-bs4/dataTables.bootstrap4.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('/src/assets/js/select.dataTables.min.css')}}">
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="{{asset('/src/assets/css/style.css')}}">
    <!-- endinject -->
    <link rel="shortcut icon" href="{{asset('/src/assets/images/favicon.png')}}"/>
</head>
<body class="with-welcome-text">
@include('layouts.admin.navbar')

<div class="container-scroller">
    <div class="container-fluid page-body-wrapper">
        @include('layouts.admin.sidebar')

        <div class="main-panel">
            @yield('content')
        </div>
    </div>
</div>

@yield('scripts')

@livewireScripts
<script src="{{asset('/src/assets/vendors/js/vendor.bundle.base.js')}}"></script>
<script src="{{asset('/src/assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
<!-- endinject -->
<!-- Plugin js for this page -->
<script src="{{asset('/src/assets/vendors/chart.js/chart.umd.js')}}"></script>
<script src="{{asset('/src/assets/vendors/progressbar.js/progressbar.min.js')}}"></script>
<!-- End plugin js for this page -->
<!-- inject:js -->
<script src="{{asset('/src/assets/js/off-canvas.js')}}"></script>
<script src="{{asset('/src/assets/js/template.js')}}"></script>
<script src="{{asset('/src/assets/js/settings.js')}}"></script>
<script src="{{asset('/src/assets/js/hoverable-collapse.js')}}"></script>
<script src="{{asset('/src/assets/js/todolist.js')}}"></script>
<!-- endinject -->
<!-- Custom js for this page-->
<script src="{{asset('/src/assets/js/jquery.cookie.js')}}" type="text/javascript"></script>
<script src="{{asset('/src/assets/js/dashboard.js')}}"></script>


<script src="{{ asset('src/assets/vendors/js/vendor.bundle.base.js') }}"></script>
<script src="{{ asset('src/assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
<!-- endinject -->
<!-- Plugin js for this page -->
<script src="{{ asset('src/assets/vendors/typeahead.js/typeahead.bundle.min.js') }}"></script>
<script src="{{ asset('src/assets/vendors/select2/select2.min.js') }}"></script>
<!-- End plugin js for this page -->
<!-- inject:js -->
<script src="{{ asset('src/assets/js/off-canvas.js') }}"></script>
<script src="{{ asset('src/assets/js/template.js') }}"></script>
<script src="{{ asset('src/assets/js/settings.js') }}"></script>
<script src="{{ asset('src/assets/js/hoverable-collapse.js') }}"></script>
<script src="{{ asset('src/assets/js/todolist.js') }}"></script>
<!-- endinject -->
<!-- Custom js for this page-->
<script src="{{ asset('src/assets/js/file-upload.js') }}"></script>
<script src="{{ asset('src/assets/js/typeahead.js') }}"></script>
<script src="{{ asset('src/assets/js/select2.js') }}"></script>
<!-- Latest compiled and minified JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/js/bootstrap.min.js" integrity="sha384-aJ21OjlMXNL5UyIl/XNwTMqvzeRMZH2w8c5cRVpzpU8Y5bApTppSuUkhZXN0VxHd" crossorigin="anonymous"></script>

</body>
</html>
