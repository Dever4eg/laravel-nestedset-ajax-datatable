<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="api-token" content="{{ Auth::guest() ? '' : Auth::user()->api_token }}">

    <title>Laravel</title>

    <link rel="stylesheet" href="{{mix('css/app.css')}}">
    @yield('styles')
</head>
<body>

    @include('parts.nav')

    <div class="container">
        @yield('content')
    </div>

    <script src="{{mix('js/app.js')}}"></script>

    @yield('scripts')
    @if(config('app.env') == 'local')
        <script src="http://localhost:35729/livereload.js"></script>
    @endif
</body>
</html>
