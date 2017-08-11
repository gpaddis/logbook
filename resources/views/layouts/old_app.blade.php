<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>

<body>
    <div id="app">
        <!-- Navbar start -->
        @include('layouts.partials.navbar')
        <!-- Navbar end -->

        <!-- Content start -->
        @yield('content')
        <!-- Content end -->
    </div>

    <!-- Scripts start -->
    @include('layouts.partials.scripts')
    <!-- Scripts end -->
</body>
</html>
