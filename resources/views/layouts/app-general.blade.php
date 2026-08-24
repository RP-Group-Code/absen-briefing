<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Portal SWJ-Tech' }}</title>
    <meta name="description" content="{{ $description ?? 'Portal Sistem SWJ-Tech — BO Sriwijaya' }}">
    <meta name="robots" content="noindex, nofollow">

    @includeif('layouts.style')
    @stack('styles')

    <style>
        /* ── General Portal Layout ── */
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        #portal-topbar {
            height: 70px;
            background: #212C44;
            border-bottom: 1px solid #34347D;
            display: flex;
            align-items: center;
            padding: 0 1.5rem;
            gap: 1rem;
            position: sticky;
            top: 0;
            z-index: 100;
            color: white;
            box-shadow: 0 2px 20px rgba(0,0,0,0.3);
        }

        #portal-topbar .portal-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        #portal-topbar .portal-brand-text {
            font-size: 1.1rem;
            font-weight: 700;
            color: #fff;
            letter-spacing: 0.03em;
        }

        #portal-content {
            flex: 1;
            display: flex;
            flex-direction: column;
        }
    </style>
</head>

<body>
    {{-- Portal Topbar (no sidebar toggle) --}}
    <div id="portal-topbar">
        <a href="{{ route('home') }}" class="portal-brand">
            <img src="{{ asset('images/LogoPPOIT.png') }}" alt="Logo" style="height:44px;">
            <span class="portal-brand-text">Portal SWJ-Tech</span>
        </a>

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
                <span class="small fw-medium">{{ auth()->user()->nama ?? (auth()->user()->name ?? 'Admin') }}</span>
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

    <div id="portal-content">
        @yield('content')
        @include('sweetalert::alert')
    </div>

    @includeIf('layouts.scripts')
    @stack('scripts')

    <script>
        // Clock
        function updateClock() {
            const now = new Date();
            document.getElementById('clock').textContent = now.toLocaleTimeString('id-ID');
        }
        updateClock();
        setInterval(updateClock, 1000);
    </script>
</body>

</html>
