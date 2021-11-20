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
    @include('layouts.header')
</head>
@if(Auth::id())
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">        
            @include('layouts.sidebar')        
        <div class="content-wrapper">
            <section class="content">
               @yield('content')
            </section>
        </div>
        @include('layouts.footer') 
    </div>
</body>
@else
<body>
    @yield('content')
    @include('layouts.footer')
</body>
@endif
</html>