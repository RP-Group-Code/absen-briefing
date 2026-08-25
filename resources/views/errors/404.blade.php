<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <meta name="theme-color" content="#11143d">
    <title>404 - Halaman Tidak Ditemukan</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root {
            --navy: #0b102f;
            --blue: #1676f3;
            --cyan: #39c6f4;
            --text: #f8fafc;
            --muted: #a8b2cc;
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            min-height: 100%;
            margin: 0;
        }

        body {
            min-height: 100vh;
            overflow-x: hidden;
            background:
                radial-gradient(circle at 15% 20%, rgba(22, 118, 243, .25), transparent 28%),
                radial-gradient(circle at 88% 78%, rgba(57, 198, 244, .14), transparent 28%),
                linear-gradient(145deg, #090d28, #171747 55%, #101536);
            color: var(--text);
            font-family: 'Outfit', sans-serif;
        }

        .error-page {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            position: relative;
            isolation: isolate;
            overflow: hidden;
        }

        .error-page::before {
            content: '';
            position: absolute;
            inset: 0;
            z-index: -3;
            opacity: .22;
            background-image:
                linear-gradient(rgba(57, 198, 244, .11) 1px, transparent 1px),
                linear-gradient(90deg, rgba(57, 198, 244, .11) 1px, transparent 1px);
            background-size: 54px 54px;
            mask-image: radial-gradient(circle at center, #000 15%, transparent 78%);
        }

        .error-orb {
            position: absolute;
            z-index: -2;
            border-radius: 50%;
            pointer-events: none;
        }

        .error-orb--one {
            width: 420px;
            height: 420px;
            top: -230px;
            right: -130px;
            border: 1px solid rgba(57, 198, 244, .28);
            box-shadow: 0 0 100px rgba(57, 198, 244, .12);
            animation: orbitFloat 8s ease-in-out infinite;
        }

        .error-orb--two {
            width: 300px;
            height: 300px;
            bottom: -180px;
            left: -90px;
            background: rgba(22, 118, 243, .13);
            filter: blur(35px);
            animation: orbitFloat 10s ease-in-out infinite reverse;
        }

        .error-nav {
            width: min(1240px, calc(100% - 40px));
            min-height: 76px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            margin: 18px auto 0;
            padding: .65rem 1rem;
            border: 1px solid rgba(255, 255, 255, .08);
            border-radius: 18px;
            background: rgba(21, 29, 65, .72);
            box-shadow: 0 14px 34px rgba(2, 6, 23, .2);
            backdrop-filter: blur(16px);
        }

        .error-brand {
            display: flex;
            align-items: center;
            gap: .7rem;
            min-width: 0;
        }

        .error-brand img {
            width: 46px;
            height: 46px;
            object-fit: contain;
        }

        .error-brand strong,
        .error-brand span {
            display: block;
        }

        .error-brand strong {
            font-size: .95rem;
            letter-spacing: .02em;
        }

        .error-brand span {
            margin-top: .08rem;
            color: var(--muted);
            font-size: .67rem;
            letter-spacing: .12em;
            text-transform: uppercase;
        }

        .error-code-chip {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            padding: .5rem .75rem;
            border: 1px solid rgba(57, 198, 244, .2);
            border-radius: 999px;
            background: rgba(57, 198, 244, .08);
            color: #8de5ff;
            font-size: .7rem;
            font-weight: 700;
            letter-spacing: .1em;
            text-transform: uppercase;
        }

        .error-code-chip span {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: var(--cyan);
            box-shadow: 0 0 12px var(--cyan);
            animation: statusBlink 1.2s ease-in-out infinite;
        }

        .error-main {
            width: min(1180px, calc(100% - 40px));
            flex: 1;
            display: grid;
            grid-template-columns: minmax(330px, .9fr) minmax(430px, 1.1fr);
            align-items: center;
            gap: clamp(2rem, 5vw, 5.5rem);
            margin: 0 auto;
            padding: 2rem 0 3rem;
        }

        .error-visual {
            min-height: 520px;
            display: grid;
            place-items: center;
            position: relative;
        }

        .error-number {
            position: absolute;
            color: transparent;
            font-size: clamp(10rem, 21vw, 17rem);
            font-weight: 800;
            line-height: 1;
            letter-spacing: -.08em;
            -webkit-text-stroke: 1px rgba(57, 198, 244, .16);
            user-select: none;
        }

        .error-ring {
            position: absolute;
            width: 410px;
            height: 410px;
            border: 1px dashed rgba(57, 198, 244, .25);
            border-radius: 50%;
            animation: ringSpin 20s linear infinite;
        }

        .error-ring::before,
        .error-ring::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            background: var(--cyan);
            box-shadow: 0 0 18px var(--cyan);
        }

        .error-ring::before {
            width: 9px;
            height: 9px;
            top: 13%;
            right: 14%;
        }

        .error-ring::after {
            width: 6px;
            height: 6px;
            bottom: 16%;
            left: 12%;
        }

        .error-mascot {
            width: min(320px, 82%);
            height: auto;
            position: relative;
            z-index: 2;
            object-fit: contain;
            filter: drop-shadow(0 28px 38px rgba(0, 0, 0, .4));
            animation: mascotHover 3.2s ease-in-out infinite;
        }

        .error-content {
            position: relative;
            z-index: 2;
        }

        .error-kicker {
            display: inline-flex;
            align-items: center;
            gap: .6rem;
            margin: 0 0 1.2rem;
            color: var(--cyan);
            font-size: .78rem;
            font-weight: 800;
            letter-spacing: .16em;
            text-transform: uppercase;
        }

        .error-kicker i {
            display: grid;
            place-items: center;
            width: 34px;
            height: 34px;
            border-radius: 10px;
            background: rgba(57, 198, 244, .1);
            box-shadow: inset 0 0 0 1px rgba(57, 198, 244, .2);
        }

        h1 {
            max-width: 720px;
            margin: 0;
            font-size: clamp(2.5rem, 5.4vw, 5rem);
            font-weight: 800;
            line-height: .96;
            letter-spacing: -.05em;
            text-transform: uppercase;
        }

        h1 span {
            display: block;
            margin-bottom: .15em;
            color: var(--cyan);
            text-shadow: 0 0 34px rgba(57, 198, 244, .22);
        }

        .error-description {
            max-width: 580px;
            margin: 1.45rem 0 0;
            color: var(--muted);
            font-size: 1rem;
            line-height: 1.75;
        }

        .error-path {
            max-width: 580px;
            display: flex;
            align-items: center;
            gap: .65rem;
            margin-top: 1.3rem;
            padding: .75rem .85rem;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, .08);
            border-radius: 12px;
            background: rgba(255, 255, 255, .035);
            color: rgba(226, 232, 240, .62);
            font-size: .78rem;
        }

        .error-path i {
            flex: 0 0 auto;
            color: var(--cyan);
        }

        .error-path span {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .error-actions {
            display: flex;
            flex-wrap: wrap;
            gap: .8rem;
            margin-top: 1.6rem;
        }

        .error-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: .55rem;
            min-height: 48px;
            padding: .75rem 1.05rem;
            border: 1px solid transparent;
            border-radius: 12px;
            color: #fff;
            font-family: inherit;
            font-size: .84rem;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            transition: transform .2s ease, background .2s ease, border-color .2s ease;
        }

        .error-button:hover {
            transform: translateY(-2px);
        }

        .error-button--primary {
            background: linear-gradient(135deg, #1261d5, #1c8df2);
            box-shadow: 0 12px 28px rgba(22, 118, 243, .25);
        }

        .error-button--secondary {
            border-color: rgba(255, 255, 255, .13);
            background: rgba(255, 255, 255, .05);
        }

        .error-footer {
            padding: 0 1.25rem 1.25rem;
            color: rgba(168, 178, 204, .52);
            font-size: .68rem;
            letter-spacing: .1em;
            text-align: center;
            text-transform: uppercase;
        }

        @keyframes mascotHover {
            0%, 100% { transform: translateY(0) rotate(-1deg); }
            50% { transform: translateY(-13px) rotate(1deg); }
        }

        @keyframes ringSpin {
            to { transform: rotate(360deg); }
        }

        @keyframes statusBlink {
            50% { opacity: .35; transform: scale(.7); }
        }

        @keyframes orbitFloat {
            0%, 100% { transform: translate(0, 0); }
            50% { transform: translate(-18px, 20px); }
        }

        @media (max-width: 900px) {
            .error-main {
                grid-template-columns: 1fr;
                gap: 0;
                padding-top: 1rem;
                text-align: center;
            }

            .error-visual {
                min-height: 335px;
            }

            .error-number {
                font-size: clamp(9rem, 38vw, 13rem);
            }

            .error-ring {
                width: 285px;
                height: 285px;
            }

            .error-mascot {
                width: min(220px, 60vw);
            }

            .error-description,
            .error-path {
                margin-right: auto;
                margin-left: auto;
            }

            .error-kicker,
            .error-actions {
                justify-content: center;
            }
        }

        @media (max-width: 520px) {
            .error-nav {
                width: calc(100% - 24px);
                min-height: 64px;
                margin-top: 12px;
                padding: .5rem .65rem;
                border-radius: 14px;
            }

            .error-brand img {
                width: 38px;
                height: 38px;
            }

            .error-brand span,
            .error-code-chip {
                font-size: .58rem;
            }

            .error-main {
                width: calc(100% - 28px);
                padding-bottom: 2rem;
            }

            .error-visual {
                min-height: 280px;
            }

            .error-ring {
                width: 235px;
                height: 235px;
            }

            .error-mascot {
                width: 185px;
            }

            h1 {
                font-size: clamp(2.15rem, 12vw, 3.2rem);
                line-height: 1;
            }

            .error-description {
                margin-top: 1rem;
                font-size: .9rem;
                line-height: 1.6;
            }

            .error-actions {
                display: grid;
                grid-template-columns: 1fr;
            }

            .error-button {
                width: 100%;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                scroll-behavior: auto !important;
                animation-duration: .01ms !important;
                animation-iteration-count: 1 !important;
            }
        }
    </style>
</head>

<body>
    <main class="error-page">
        <div class="error-orb error-orb--one" aria-hidden="true"></div>
        <div class="error-orb error-orb--two" aria-hidden="true"></div>

        <header class="error-nav">
            <div class="error-brand">
                <img src="{{ asset('images/LogoPPOIT.png') }}" alt="Logo Portal SWJ-Tech">
                <div>
                    <strong>Portal SWJ-Tech</strong>
                    <span>BO Palembang Sriwijaya</span>
                </div>
            </div>
            <div class="error-code-chip"><span></span> Error 404</div>
        </header>

        <section class="error-main">
            <div class="error-visual" aria-hidden="true">
                <div class="error-number">404</div>
                <div class="error-ring"></div>
                <img
                    class="error-mascot"
                    src="{{ asset('images/development-mascot.webp') }}"
                    alt=""
                    width="360"
                    height="540"
                >
            </div>

            <div class="error-content">
                <p class="error-kicker"><i class="bi bi-signpost-split-fill"></i> Jalur tidak ditemukan</p>
                <h1><span>Oops, 404.</span>Halaman tidak ditemukan.</h1>
                <p class="error-description">
                    Alamat yang Anda tuju tidak tersedia, sudah dipindahkan, atau mungkin terdapat kesalahan penulisan URL.
                </p>

                <div class="error-path" title="{{ request()->fullUrl() }}">
                    <i class="bi bi-link-45deg"></i>
                    <span>{{ request()->path() }}</span>
                </div>

                <div class="error-actions">
                    <a class="error-button error-button--primary" href="{{ url('/') }}">
                        <i class="bi bi-house-door-fill"></i>
                        Kembali ke Beranda
                    </a>
                    <button class="error-button error-button--secondary" type="button" onclick="history.back()">
                        <i class="bi bi-arrow-left"></i>
                        Halaman Sebelumnya
                    </button>
                </div>
            </div>
        </section>

        <footer class="error-footer">Portal SWJ-Tech &middot; Sistem Internal BO Palembang Sriwijaya</footer>
    </main>
</body>

</html>
