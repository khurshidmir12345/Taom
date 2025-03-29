<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Food Randomizer') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="/resources/css/app.css">
    <link rel="stylesheet" href="{{ asset('css/settings-sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/randomize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/top-navbar.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    @yield('styles')
    @livewireStyles
</head>
<body>
{{--@include('layouts.user.navbar')--}}
@include('layouts.user.top-navbar')

<main>
    @yield('content')
</main>
@include('layouts.user.sidebar')
@include('layouts.user.settings-sidebar')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@livewireScripts
<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('js/settings-sidebar.js') }}"></script>
@yield('scripts')
</body>
</html>
