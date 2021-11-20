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
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('custom/theme/css/nice-select.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('custom/theme/css/style.css') }}">
    @yield('style')
</head>
<body>
    @yield('content')
    <script src="{{ asset('js/app.js') }}"></script>
    <script type="text/javascript" src="{{ asset('custom/theme/js/jquery.nice-select.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('custom/theme/js/custom-script.js') }}"></script>
</body>
</html>