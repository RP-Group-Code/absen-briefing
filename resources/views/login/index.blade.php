<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Sistem Absensi</title>

    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- FontAwesome 6 --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    {{-- Google Fonts: Outfit --}}
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        /* ════════════════════════════════════
           BASE
        ════════════════════════════════════ */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Outfit', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #0f0c29 0%, #1a1a4e 45%, #24243e 100%);
            overflow: hidden;
            position: relative;
        }

        /* ── Animated background orbs (sama seperti dashboard) ── */
        .orb {
            position: fixed;
            border-radius: 50%;
            pointer-events: none;
            z-index: 0;
            animation: orbFloat 10s ease-in-out infinite;
        }
        .orb-1 {
            width: 550px; height: 550px;
            background: radial-gradient(circle, rgba(99,102,241,.22) 0%, transparent 70%);
            top: -140px; left: -140px;
        }
        .orb-2 {
            width: 430px; height: 430px;
            background: radial-gradient(circle, rgba(236,72,153,.18) 0%, transparent 70%);
            bottom: -90px; right: -90px;
            animation-delay: -5s; animation-direction: reverse;
        }
        .orb-3 {
            width: 280px; height: 280px;
            background: radial-gradient(circle, rgba(16,185,129,.13) 0%, transparent 70%);
            top: 55%; left: 65%;
            animation-delay: -3s;
        }
        @keyframes orbFloat {
            0%,100% { transform: translate(0,0) scale(1); }
            33%      { transform: translate(25px,-25px) scale(1.05); }
            66%      { transform: translate(-15px,18px) scale(0.95); }
        }

        /* ── Floating particles ── */
        .particle {
            position: fixed;
            width: 3px; height: 3px;
            border-radius: 50%;
            background: rgba(255,255,255,.25);
            pointer-events: none;
            z-index: 0;
            animation: particleDrift linear infinite;
        }
        @keyframes particleDrift {
            0%   { transform: translateY(100vh) scale(0); opacity: 0; }
            10%  { opacity: 1; }
            90%  { opacity: 1; }
            100% { transform: translateY(-10vh) scale(1.5); opacity: 0; }
        }

        /* ════════════════════════════════════
           LOGIN CARD
        ════════════════════════════════════ */
        .login-wrapper {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 440px;
            padding: 1rem;
            animation: cardEntrance .7s cubic-bezier(.22,.68,0,1.2) both;
        }
        @keyframes cardEntrance {
            from { opacity: 0; transform: translateY(40px) scale(.96); }
            to   { opacity: 1; transform: translateY(0)    scale(1);   }
        }

        .login-card {
            background: rgba(255,255,255,.07);
            backdrop-filter: blur(28px) saturate(180%);
            -webkit-backdrop-filter: blur(28px) saturate(180%);
            border: 1px solid rgba(255,255,255,.16);
            border-radius: 28px;
            overflow: hidden;
            box-shadow:
                0 8px 40px rgba(0,0,0,.45),
                0 2px 8px rgba(0,0,0,.25),
                inset 0 1px 0 rgba(255,255,255,.25);
            position: relative;
        }

        /* Glossy top-left shine */
        .login-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(
                140deg,
                rgba(255,255,255,.14) 0%,
                rgba(255,255,255,.04) 35%,
                transparent 55%
            );
            border-radius: inherit;
            pointer-events: none;
            z-index: 0;
        }

        /* ── Card Header ── */
        .card-header-glass {
            position: relative;
            z-index: 1;
            padding: 2rem 2rem 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,.08);
            background: rgba(255,255,255,.03);
            text-align: center;
        }

        .brand-icon {
            width: 64px; height: 64px;
            border-radius: 18px;
            background: linear-gradient(135deg, rgba(99,102,241,.8), rgba(168,85,247,.8));
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            color: #fff;
            margin-bottom: 1rem;
            box-shadow:
                0 6px 20px rgba(99,102,241,.45),
                inset 0 1px 0 rgba(255,255,255,.3);
            animation: iconPulse 3s ease-in-out infinite;
        }
        @keyframes iconPulse {
            0%,100% { box-shadow: 0 6px 20px rgba(99,102,241,.45), inset 0 1px 0 rgba(255,255,255,.3); }
            50%      { box-shadow: 0 8px 28px rgba(99,102,241,.7),  inset 0 1px 0 rgba(255,255,255,.3); }
        }

        .brand-title {
            font-size: 1.4rem;
            font-weight: 800;
            color: #fff;
            letter-spacing: -.02em;
            margin-bottom: 4px;
        }
        .brand-sub {
            font-size: .8rem;
            color: rgba(255,255,255,.4);
            letter-spacing: .04em;
            text-transform: uppercase;
        }

        /* ── Card Body ── */
        .card-body-glass {
            position: relative;
            z-index: 1;
            padding: 1.8rem 2rem 2rem;
        }

        /* ── Alert error ── */
        .glass-alert {
            background: rgba(239,68,68,.18);
            border: 1px solid rgba(239,68,68,.35);
            border-radius: 14px;
            color: #fca5a5;
            font-size: .82rem;
            padding: .75rem 1rem;
            margin-bottom: 1.2rem;
            display: flex;
            align-items: flex-start;
            gap: 8px;
            animation: shakeIn .4s cubic-bezier(.36,.07,.19,.97) both;
        }
        .glass-alert.success {
            background: rgba(16,185,129,.18);
            border-color: rgba(16,185,129,.35);
            color: #6ee7b7;
        }
        @keyframes shakeIn {
            0%,100% { transform: translateX(0); }
            20%      { transform: translateX(-6px); }
            40%      { transform: translateX(6px); }
            60%      { transform: translateX(-4px); }
            80%      { transform: translateX(4px); }
        }

        /* ── Form Group ── */
        .form-group { margin-bottom: 1.2rem; }

        .form-label-glass {
            font-size: .75rem;
            font-weight: 600;
            color: rgba(255,255,255,.5);
            letter-spacing: .07em;
            text-transform: uppercase;
            margin-bottom: 7px;
            display: block;
        }

        .input-wrap {
            position: relative;
        }
        .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255,255,255,.35);
            font-size: .85rem;
            transition: color .2s;
            pointer-events: none;
        }
        .input-wrap:focus-within .input-icon {
            color: #818cf8;
        }

        .glass-input {
            width: 100%;
            background: rgba(255,255,255,.08);
            border: 1px solid rgba(255,255,255,.15);
            border-radius: 14px;
            color: #fff;
            font-family: 'Outfit', sans-serif;
            font-size: .9rem;
            padding: .75rem 3rem .75rem 2.8rem;
            outline: none;
            transition: border-color .25s, background .25s, box-shadow .25s;
            backdrop-filter: blur(6px);
        }
        .glass-input::placeholder { color: rgba(255,255,255,.28); }
        .glass-input:focus {
            border-color: rgba(99,102,241,.7);
            background: rgba(255,255,255,.12);
            box-shadow: 0 0 0 3px rgba(99,102,241,.18);
        }
        .glass-input.is-invalid {
            border-color: rgba(239,68,68,.6) !important;
            box-shadow: 0 0 0 3px rgba(239,68,68,.15) !important;
        }
        .invalid-msg {
            font-size: .75rem;
            color: #fca5a5;
            margin-top: 5px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        /* Toggle password visibility */
        .toggle-pw {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255,255,255,.35);
            font-size: .82rem;
            cursor: pointer;
            transition: color .2s;
            background: none;
            border: none;
            padding: 0;
        }
        .toggle-pw:hover { color: rgba(255,255,255,.7); }

        /* ── Remember & Forgot ── */
        .remember-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
        }
        .glass-check {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }
        .glass-check input[type="checkbox"] {
            width: 16px; height: 16px;
            accent-color: #818cf8;
            cursor: pointer;
        }
        .glass-check span {
            font-size: .8rem;
            color: rgba(255,255,255,.55);
        }
        .forgot-link {
            font-size: .8rem;
            color: rgba(99,102,241,.8);
            text-decoration: none;
            transition: color .2s;
        }
        .forgot-link:hover { color: #a5b4fc; }

        /* ── Submit Button ── */
        .btn-glass-primary {
            width: 100%;
            padding: .85rem;
            border-radius: 14px;
            border: none;
            background: linear-gradient(135deg, rgba(99,102,241,.85), rgba(168,85,247,.85));
            color: #fff;
            font-family: 'Outfit', sans-serif;
            font-size: .95rem;
            font-weight: 700;
            letter-spacing: .02em;
            cursor: pointer;
            box-shadow: 0 6px 20px rgba(99,102,241,.4);
            transition: all .25s;
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .btn-glass-primary::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,.18) 0%, transparent 60%);
            border-radius: inherit;
        }
        .btn-glass-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 28px rgba(99,102,241,.6);
        }
        .btn-glass-primary:active {
            transform: translateY(0);
            box-shadow: 0 4px 12px rgba(99,102,241,.35);
        }
        .btn-glass-primary:disabled {
            opacity: .6;
            cursor: not-allowed;
            transform: none;
        }

        /* Loading spinner on button */
        .btn-spinner {
            display: none;
            width: 18px; height: 18px;
            border: 2px solid rgba(255,255,255,.35);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin .7s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* ── Divider ── */
        .glass-divider {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 1.4rem 0 1.2rem;
        }
        .glass-divider::before,
        .glass-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: rgba(255,255,255,.1);
        }
        .glass-divider span {
            font-size: .72rem;
            color: rgba(255,255,255,.3);
            letter-spacing: .06em;
            text-transform: uppercase;
        }

        /* ── Footer note ── */
        .card-footer-glass {
            position: relative;
            z-index: 1;
            text-align: center;
            padding: 1rem 2rem 1.5rem;
            border-top: 1px solid rgba(255,255,255,.07);
            font-size: .75rem;
            color: rgba(255,255,255,.3);
        }
        .card-footer-glass strong { color: rgba(255,255,255,.5); }
    </style>
</head>
<body>

{{-- Background orbs --}}
<div class="orb orb-1"></div>
<div class="orb orb-2"></div>
<div class="orb orb-3"></div>

{{-- Login Card --}}
<div class="login-wrapper">
    <div class="login-card">

        {{-- Header --}}
        <div class="card-header-glass">
            <div class="brand-icon">
                <i class="fa-solid fa-fingerprint"></i>
            </div>
            <div class="brand-title">Sistem Briefing Absensi</div>
            <div class="brand-sub">Masuk ke akun Anda</div>
        </div>

        {{-- Body --}}
        <div class="card-body-glass">

            {{-- Flash success (misal setelah logout) --}}
            @if (session('success'))
                <div class="glass-alert success">
                    <i class="fa-solid fa-circle-check fa-sm pt-2"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            {{-- Flash error --}}
            @if (session('error'))
                <div class="glass-alert">
                    <i class="fa-solid fa-circle-exclamation fa-sm pt-2"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            {{-- Form --}}
            <form method="POST" action="{{ route('login') }}" id="loginForm" novalidate>
                @csrf

                {{-- username --}}
                <div class="form-group">
                    <label class="form-label-glass">Username</label>
                    <div class="input-wrap">
                        <i class="fa-regular fa-user input-icon"></i>
                        <input
                            type="text"
                            name="username"
                            id="username"
                            class="glass-input @error('username') is-invalid @enderror"
                            placeholder="Username"
                            value="{{ old('username') }}"
                            autocomplete="username"
                            autofocus
                            required
                        >
                    </div>
                    @error('username')
                        <div class="invalid-msg">
                            <i class="fa-solid fa-circle-exclamation fa-xs"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="form-group">
                    <label class="form-label-glass">Password</label>
                    <div class="input-wrap">
                        <i class="fa-solid fa-lock input-icon"></i>
                        <input
                            type="password"
                            name="password"
                            id="password"
                            class="glass-input @error('password') is-invalid @enderror"
                            placeholder="••••••••"
                            autocomplete="current-password"
                            required
                        >
                        <button type="button" class="toggle-pw" id="togglePw" title="Tampilkan password">
                            <i class="fa-regular fa-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="invalid-msg">
                            <i class="fa-solid fa-circle-exclamation fa-xs"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>
{{-- 
                <div class="remember-row">
                    <label class="glass-check">
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        <span>Ingat saya</span>
                    </label>
                    <a href="{{ route('password.request') }}" class="forgot-link">Lupa password?</a>
                    <a href="#" class="forgot-link">Lupa password?</a>
                </div> --}}

                {{-- Submit --}}
                <button type="submit" class="btn-glass-primary" id="submitBtn">
                    <span class="btn-spinner" id="btnSpinner"></span>
                    <i class="fa-solid fa-arrow-right-to-bracket fa-sm" id="btnIcon"></i>
                    <span id="btnText">Masuk</span>
                </button>

            </form>
        </div>

        {{-- Footer --}}
        <div class="card-footer-glass">
            &copy; {{ date('Y') }} <strong>Briefing Absensi SWJ342</strong> — All rights reserved
        </div>

    </div>{{-- /login-card --}}
</div>{{-- /login-wrapper --}}

{{-- Bootstrap JS --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    /* ── Toggle password visibility ── */
    var togglePw  = document.getElementById('togglePw');
    var pwInput   = document.getElementById('password');
    var eyeIcon   = document.getElementById('eyeIcon');

    togglePw.addEventListener('click', function () {
        var isHidden = pwInput.type === 'password';
        pwInput.type = isHidden ? 'text' : 'password';
        eyeIcon.className = isHidden ? 'fa-regular fa-eye-slash' : 'fa-regular fa-eye';
    });

    /* ── Loading state on submit ── */
    document.getElementById('loginForm').addEventListener('submit', function () {
        var btn     = document.getElementById('submitBtn');
        var spinner = document.getElementById('btnSpinner');
        var icon    = document.getElementById('btnIcon');
        var text    = document.getElementById('btnText');

        btn.disabled       = true;
        spinner.style.display = 'block';
        icon.style.display    = 'none';
        text.textContent      = 'Memproses…';
    });

    /* ── Generate floating particles ── */
    for (var i = 0; i < 18; i++) {
        var p = document.createElement('div');
        p.className = 'particle';
        p.style.left              = Math.random() * 100 + 'vw';
        p.style.width             =
        p.style.height            = (Math.random() * 3 + 1) + 'px';
        p.style.animationDuration = (Math.random() * 12 + 8) + 's';
        p.style.animationDelay    = (Math.random() * 10) + 's';
        p.style.opacity           = Math.random() * .4 + .1;
        document.body.appendChild(p);
    }
});
</script>

</body>
</html>