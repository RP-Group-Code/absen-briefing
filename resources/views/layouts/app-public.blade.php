<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $title ?? 'Portal Absen Briefing — BO Sriwijaya' }}</title>
        <meta name="description" content="{{ $description ?? 'App absensi briefing pegawai BO Sriwijaya digital.' }}">
        <meta name="keywords" content="absen, briefing, sistem absensi Sriwijaya">
        <meta name="author" content="BOSWJIT342-roelis-2026">
        <meta name="robots" content="noindex, nofollow"> {{-- private app, jangan diindex Google --}}


        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta property="og:title" content="{{ $title ?? 'Portal Absen Briefing' }}">
        <meta property="og:description"
            content="{{ $description ?? 'Sistem absensi briefing BO Palembang Sriwijaya.' }}">
        <meta property="og:image" content="{{ asset('images/logo_briefing.png') }}">
        <meta property="og:image:width" content="1200">
        <meta property="og:image:height" content="630">
        <meta property="og:site_name" content="Portal Absen Briefing">
        <meta property="og:locale" content="id_ID">


        <meta name="theme-color" content="#1a1a4e">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
        <meta name="apple-mobile-web-app-title" content="Absen Briefing">
        <link rel="apple-touch-icon" href="{{ asset('images/icon-192.png') }}">

        @includeIf('layouts.style-public')
        @stack('styles')
</head>

<body>
    @include('sweetalert::alert')

    @if ($errors->any())
    @endif

    @yield('content')

    @includeIf('layouts.scripts-public')
    @stack('scripts')
</body>

</html>
