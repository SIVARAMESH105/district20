<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ (isset($title)) ? $title.' - ' : '' }} {{ config('app.name', 'District10') }}</title>
      <link rel="icon" href="{{ url('/') }}/images/district10.ico" type="image/gif" sizes="16x16">
    <script>
        var siteUrl = "{{ url('/') }}";
        var csrf_token = "{{ csrf_token() }}";
    </script>    
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="{{ asset('custom/theme/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('custom/adminLTE/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('custom/theme/css/nice-select.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('custom/theme/css/full-calendar.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('custom/theme/css/style.css') }}">
    @yield('style')
</head>
<body>
    @include('layouts.user_header')
    <main>
        @yield('content')
    </main>
    @include('layouts.user_footer')    
    <script type="text/javascript" src="{{ asset('custom/theme/js/jquery-3.5.1.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('custom/theme/js/popper.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('custom/theme/js/bootstrap.min.js') }}"></script>    
    <script type="text/javascript" src="{{ asset('custom/theme/js/jquery.nice-select.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('custom/theme/js/custom-script.js') }}"></script>
    @yield('script')
</body>
</html>