<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- App title -->
  <title>{{ config('app.name') }}</title>

  <!-- Bootstrap CSS -->
  {{-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous"> --}}

  <!-- Styles -->
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
  <link href="{{ asset('css/statistics-app.css') }}" rel="stylesheet">
</head>

<body class="bg-light">
  <div id="app">
    <!-- Navbar start -->
    @include('layouts.partials.navbar')
    <!-- Navbar end -->

    <!-- Content start -->
    <div class="container">
      @yield('content')
    </div>
    <!-- Content end -->

    <flash message="{{ session('flash') }}"></flash>
  </div>

  <!-- Scripts -->
  <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
