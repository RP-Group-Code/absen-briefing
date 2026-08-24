<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Portal SWJ-Tech' }}</title>
    <meta name="description" content="{{ $description ?? 'App absensi briefing pegawai BO Sriwijaya digital.' }}">
    <meta name="keywords" content="absen, briefing, sistem absensi Sriwijaya">
    <meta name="author" content="BOSWJIT342-roelis-2026">
    <meta name="robots" content="noindex, nofollow">

    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ $title ?? 'Portal Absen Briefing' }}">
    <meta property="og:description" content="{{ $description ?? 'Sistem absensi briefing BO Palembang Sriwijaya.' }}">
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

    {{-- Style dari sidebar/app layout (Bootstrap, FontAwesome, dll) --}}
    @includeIf('layouts.style')

    <style>
        /* ── Layout wrapper: sidebar + konten penuh lebar ── */
        .app-with-sidebar-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar tetap di kiri, konten mengisi sisa */
        .app-with-sidebar-content {
            flex: 1;
            min-width: 0;
            overflow-x: hidden;
            /* Reset background body agar konten anak bisa set sendiri */
        }
    </style>

    @stack('styles')
</head>

<body>
    @include('sweetalert::alert')

    <div class="app-with-sidebar-wrapper">
        {{-- Sidebar kiri --}}
        <x-sidebar />

        {{-- Konten utama -- tanpa topbar, konten bebas mengisi area --}}
        <div class="app-with-sidebar-content">
            @yield('content')
        </div>
    </div>

    @includeIf('layouts.scripts')
    @stack('scripts')
</body>

</html>
