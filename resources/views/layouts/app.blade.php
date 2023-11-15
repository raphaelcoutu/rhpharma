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
    @vite(['resources/sass/app.scss'])
    <link rel="icon" type="image/png" href="/favicons/calendar.png" sizes="48x48">


    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>
</head>
<body>
    <div id="app">
        @include('layouts.navbar')

        <div class="container">
            @yield('content')
        </div>
        @yield('below-container')
    </div>

    @vite('resources/js/app.js')
</body>
</html>
