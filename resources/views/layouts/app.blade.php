<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Portal Absen Briefing — BO Sriwijaya' }}</title>
    <meta name="description" content="{{ $description ?? 'App absensi briefing pegawai BO Sriwijaya digital.' }}">
    <meta name="keywords"    content="absen, briefing, sistem absensi Sriwijaya">
    <meta name="author"      content="BOSWJIT342-roelis-2026">
    <meta name="robots"      content="noindex, nofollow"> {{-- private app, jangan diindex Google --}}
    
    {{-- ════════════════════════════════
         OPEN GRAPH (WhatsApp, FB, Telegram)
    ════════════════════════════════ --}}
    <meta property="og:type"        content="website">
    <meta property="og:url"         content="{{ url()->current() }}">
    <meta property="og:title"       content="{{ $title ?? 'Portal Absen Briefing' }}">
    <meta property="og:description" content="{{ $description ?? 'Sistem absensi briefing BO Palembang Sriwijaya.' }}">
    <meta property="og:image"       content="{{ asset('images/logo_briefing.png') }}">
    <meta property="og:image:width"  content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:site_name"   content="Portal Absen Briefing">
    <meta property="og:locale"      content="id_ID">
    
    <meta name="theme-color"         content="#1a1a4e">
    <meta name="apple-mobile-web-app-capable"          content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title"            content="Absen Briefing">
    <link rel="apple-touch-icon"     href="{{ asset('images/icon-192.png') }}">
    
    @includeif('layouts.style')

    @stack('styles')
</head>

<body>
    <div class="d-flex">

        <x-sidebar />

        <div class="d-flex flex-column flex-grow-1">

            {{-- Topbar --}}
            <div id="topbar">
                <button id="sidebarToggle" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-list fs-5"></i>
                </button>
                <span class="text-white">{{ $title ?? 'SRIWIJAYA DASHBOARD BRIEFING' }}</span>
                <div class="ms-auto d-flex align-items-center gap-3">
                    <span class="text-muted small">
                        <i class="bi bi-clock me-1"></i>
                        <span id="clock"></span>
                    </span>
                    <div class="d-flex align-items-center gap-2">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                            style="width:34px;height:34px;">
                            <i class="bi bi-person-fill"></i>
                        </div>
                        <span
                            class="small fw-medium">{{ auth()->user()->nama ?? (auth()->user()->name ?? 'Admin') }}</span>
                    </div>
                    {{-- Logout --}}
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="glass-btn" style="border:none;cursor:pointer;">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </button>
                    </form>
                </div>
            </div>

            {{-- Konten utama --}}
            <div id="content" class="p-4">
                @yield('content')
                @include('sweetalert::alert')
            </div>

        </div>
    </div>

    @includeIf('layouts.scripts')
    {{-- Stack scripts per halaman --}}
    @stack('scripts')
</body>

</html>
