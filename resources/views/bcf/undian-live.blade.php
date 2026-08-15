@extends('layouts.app-public')

@section('title', 'Undian BCF Live')
@section('description', 'Laman live undian BCF BO Sriwijaya.')

@push('styles')
    <style>
        :root {
            --live-bg: #090a12;
            --live-bg-soft: #17051f;
            --live-panel: rgba(20, 14, 34, 0.86);
            --live-line: rgba(255, 208, 0, 0.18);
            --live-gold: #ffd31c;
            --live-gold-soft: #ffb000;
            --live-cyan: #27e0ff;
            --live-pink: #ff4a72;
            --live-text: #f7f7f9;
            --live-muted: rgba(247, 247, 249, 0.58);
        }

        body {
            margin: 0;
            background:
                linear-gradient(rgba(255, 211, 28, 0.06) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 211, 28, 0.06) 1px, transparent 1px),
                radial-gradient(circle at top center, rgba(255, 211, 28, 0.18), transparent 28%),
                radial-gradient(circle at 70% 72%, rgba(140, 0, 255, 0.18), transparent 30%),
                linear-gradient(180deg, var(--live-bg) 0%, var(--live-bg-soft) 100%) !important;
            background-size: 102px 102px, 102px 102px, auto, auto, auto;
            color: var(--live-text);
        }

        .live-shell {
            min-height: 100vh;
            width: min(100%, 1880px);
            margin: 0 auto;
            padding: 16px 18px 28px;
            display: grid;
            grid-template-columns: 1fr;
            gap: 18px;
        }

        .live-stage {
            position: relative;
            min-height: 980px;
            border: 1px solid rgba(255, 211, 28, 0.08);
            border-radius: 28px;
            padding: 22px 28px 28px;
            background:
                radial-gradient(circle at center, rgba(255, 211, 28, 0.12), transparent 36%),
                linear-gradient(180deg, rgba(11, 11, 19, 0.86), rgba(17, 7, 29, 0.92));
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .live-stage.is-celebrating .live-winner-box {
            animation: liveWinnerPulse 1.2s ease-in-out 2;
            box-shadow:
                0 0 0 1px rgba(255, 211, 28, 0.16),
                0 34px 90px rgba(0, 0, 0, 0.34),
                0 0 48px rgba(255, 211, 28, 0.22);
        }

        .live-stage::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(circle at 20% 16%, rgba(255, 211, 28, 0.14), transparent 22%),
                radial-gradient(circle at 72% 18%, rgba(255, 211, 28, 0.12), transparent 24%),
                radial-gradient(circle at 82% 72%, rgba(153, 0, 255, 0.14), transparent 28%);
            pointer-events: none;
        }

        .live-confetti-layer {
            position: absolute;
            inset: 0;
            overflow: hidden;
            pointer-events: none;
            z-index: 4;
        }

        .live-confetti {
            position: absolute;
            top: -8%;
            width: 14px;
            height: 22px;
            opacity: 0;
            border-radius: 4px;
            animation: liveConfettiFall linear forwards;
            transform-origin: center;
            box-shadow: 0 0 12px rgba(255, 255, 255, 0.14);
        }

        .live-confetti.is-streamer {
            width: 8px;
            height: 32px;
            border-radius: 999px;
        }

        .live-celebrate-ring {
            position: absolute;
            left: 50%;
            top: 43%;
            width: 160px;
            height: 160px;
            margin-left: -80px;
            margin-top: -80px;
            border-radius: 50%;
            border: 2px solid rgba(255, 211, 28, 0.5);
            box-shadow: 0 0 42px rgba(255, 211, 28, 0.18);
            animation: liveCelebrateRing 920ms ease-out forwards;
            pointer-events: none;
            z-index: 3;
        }

        .live-topbar,
        .live-panel-top {
            position: relative;
            z-index: 2;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }

        .live-links {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .live-link {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            padding: 12px 16px;
            border-radius: 16px;
            color: var(--live-text);
            text-decoration: none;
            border: 1px solid rgba(255, 255, 255, 0.12);
            background: rgba(255, 255, 255, 0.04);
            font-weight: 800;
            letter-spacing: .03em;
        }

        .live-link.primary {
            border-color: rgba(255, 211, 28, 0.26);
            background: rgba(255, 211, 28, 0.12);
            color: var(--live-gold);
        }

        .live-copy {
            color: rgba(255, 255, 255, 0.2);
            font-size: .86rem;
        }

        .live-headline {
            position: relative;
            z-index: 2;
            margin-top: 20px;
            flex: 1;
            text-align: center;
        }

        .live-headline h1 {
            margin: 0;
            font-size: clamp(2.3rem, 4.4vw, 4.85rem);
            line-height: .98;
            font-weight: 900;
            letter-spacing: .02em;
            text-transform: uppercase;
            color: var(--live-gold);
            text-shadow: 0 0 30px rgba(255, 211, 28, 0.22);
            white-space: nowrap;
        }

        .live-subtitle {
            margin-top: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            color: var(--live-cyan);
            font-size: .88rem;
            letter-spacing: .26em;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .live-brand-logo {
            margin: 18px auto 0;
            width: min(100%, 250px);
            display: block;
            filter: drop-shadow(0 18px 28px rgba(0, 0, 0, 0.28));
        }

        .live-watermark {
            position: absolute;
            left: 44%;
            bottom: 17%;
            transform: rotate(-20deg);
            color: rgba(255, 211, 28, 0.16);
            font-size: clamp(2rem, 4vw, 4.2rem);
            letter-spacing: .08em;
            text-transform: uppercase;
            pointer-events: none;
            z-index: 1;
        }

        .live-winner-box {
            position: relative;
            z-index: 2;
            width: min(100%, 1120px);
            margin: 34px auto 0;
            z-index: 2;
            border-radius: 34px;
            border: 2px solid rgba(255, 211, 28, 0.28);
            background: linear-gradient(180deg, rgba(45, 6, 68, 0.86), rgba(12, 10, 20, 0.92));
            box-shadow: 0 34px 90px rgba(0, 0, 0, 0.34);
            padding: 34px 30px 28px;
            text-align: center;
        }

        .live-winner-badge {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 10px 16px;
            border-radius: 999px;
            background: rgba(255, 211, 28, 0.08);
            border: 1px solid rgba(255, 211, 28, 0.18);
            color: rgba(255, 255, 255, 0.82);
            font-size: .88rem;
            letter-spacing: .16em;
            text-transform: uppercase;
        }

        .live-winner-name {
            margin: 18px 0 8px;
            min-height: clamp(3.6rem, 6.4vw, 4.8rem);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: clamp(2rem, 4vw, 4rem);
            line-height: 1;
            font-weight: 900;
            letter-spacing: .03em;
            text-transform: uppercase;
            text-shadow: 0 0 24px rgba(255, 255, 255, 0.14);
            width: 100%;
        }

        .live-winner-prize {
            margin-top: 4px;
            color: var(--live-gold);
            font-size: clamp(1.1rem, 2vw, 1.7rem);
            font-weight: 900;
            letter-spacing: .04em;
            text-transform: uppercase;
            text-shadow: 0 0 22px rgba(255, 211, 28, 0.24);
        }

        .live-winner-meta {
            margin-top: 10px;
            color: rgba(255, 255, 255, 0.78);
            font-size: 1rem;
            letter-spacing: .14em;
            text-transform: uppercase;
        }

        .live-modal {
            position: fixed;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 28px;
            background: rgba(7, 6, 16, 0.82);
            backdrop-filter: blur(16px);
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            transition: opacity .24s ease, visibility .24s ease;
            z-index: 60;
        }

        .live-modal.is-open {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
        }

        .live-modal-dialog {
            position: relative;
            width: min(100%, 1320px);
            padding: 34px;
            border-radius: 36px;
            border: 1px solid rgba(255, 211, 28, 0.18);
            background:
                radial-gradient(circle at top center, rgba(255, 211, 28, 0.12), transparent 32%),
                linear-gradient(180deg, rgba(31, 8, 48, 0.96), rgba(11, 10, 21, 0.98));
            box-shadow:
                0 30px 120px rgba(0, 0, 0, 0.46),
                0 0 44px rgba(255, 211, 28, 0.12);
        }

        .live-modal-close {
            position: absolute;
            right: 22px;
            top: 22px;
            min-width: 52px;
            min-height: 52px;
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, 0.12);
            background: rgba(255, 255, 255, 0.05);
            color: var(--live-text);
            font-size: 1.4rem;
            cursor: pointer;
        }

        .live-modal-card {
            border-radius: 34px;
            border: 2px solid rgba(255, 211, 28, 0.26);
            background: linear-gradient(180deg, rgba(58, 8, 86, 0.94), rgba(10, 10, 20, 0.96));
            box-shadow:
                0 34px 90px rgba(0, 0, 0, 0.34),
                0 0 50px rgba(255, 211, 28, 0.14);
            padding: 46px 40px 38px;
            text-align: center;
        }

        .live-modal-card .live-winner-badge {
            font-size: 1rem;
            padding: 12px 20px;
        }

        .live-modal-name {
            margin: 26px 0 12px;
            font-size: clamp(2.8rem, 6vw, 6.3rem);
            line-height: .96;
            font-weight: 900;
            text-transform: uppercase;
            text-shadow: 0 0 30px rgba(255, 255, 255, 0.16);
        }

        .live-modal-prize {
            color: var(--live-gold);
            font-size: clamp(1.4rem, 2.5vw, 2.4rem);
            font-weight: 900;
            text-transform: uppercase;
            text-shadow: 0 0 26px rgba(255, 211, 28, 0.24);
        }

        .live-modal-meta {
            margin-top: 18px;
            color: rgba(255, 255, 255, 0.78);
            font-size: 1.08rem;
            letter-spacing: .16em;
            text-transform: uppercase;
        }

        .live-modal-actions {
            margin-top: 28px;
            display: flex;
            justify-content: center;
        }

        .live-modal-dismiss {
            min-height: 62px;
            padding: 0 30px;
            border-radius: 18px;
            border: 0;
            background: linear-gradient(135deg, #27d2f6 0%, #1a86e2 56%, #1456c5 100%);
            color: #fff;
            font-size: 1rem;
            font-weight: 900;
            letter-spacing: .12em;
            text-transform: uppercase;
            cursor: pointer;
            box-shadow: 0 18px 36px rgba(29, 132, 226, 0.26);
        }

        .live-panel {
            border-radius: 28px;
            border: 1px solid rgba(255, 255, 255, 0.08);
            background: linear-gradient(180deg, rgba(9, 11, 20, 0.92), rgba(22, 7, 34, 0.95));
            box-shadow: 0 24px 60px rgba(0, 0, 0, 0.24);
            padding: 28px 28px 32px;
            display: flex;
            flex-direction: column;
            gap: 18px;
            max-width: 1540px;
            width: 100%;
            margin: 22px auto 0;
        }

        .live-panel-title {
            margin: 0;
            font-size: 1.2rem;
            font-weight: 900;
            color: var(--live-text);
            text-transform: uppercase;
            letter-spacing: .08em;
        }

        .live-panel-subtitle {
            margin: 8px 0 0;
            color: var(--live-muted);
        }

        .live-stats {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 16px;
        }

        .live-stat {
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.08);
            background: rgba(255, 255, 255, 0.04);
            padding: 14px 16px;
        }

        .live-stat span {
            display: block;
            color: var(--live-muted);
            font-size: .72rem;
            letter-spacing: .16em;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .live-stat strong {
            font-size: 2rem;
            line-height: 1;
            font-weight: 900;
            color: var(--live-gold);
        }

        .live-form {
            display: grid;
            gap: 14px;
            margin-top: 8px;
        }

        .live-filter-row {
            display: grid;
            grid-template-columns: minmax(0, 1.4fr) minmax(240px, .72fr);
            gap: 14px;
        }

        .live-search {
            width: 100%;
            min-height: 64px;
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            background: rgba(255, 255, 255, 0.05);
            color: var(--live-text);
            font-size: 1rem;
            padding: 0 20px;
            outline: none;
        }

        .live-search::placeholder {
            color: rgba(247, 247, 249, 0.44);
        }

        .live-search:focus {
            box-shadow: 0 0 0 4px rgba(255, 211, 28, 0.1);
            border-color: rgba(255, 211, 28, 0.24);
        }

        .live-select {
            width: 100%;
            min-height: 76px;
            border-radius: 22px;
            border: 1px solid rgba(255, 211, 28, 0.2);
            background: rgba(255, 255, 255, 0.06);
            color: var(--live-gold);
            font-size: 1.1rem;
            font-weight: 800;
            padding: 0 22px;
            outline: none;
        }

        .live-select:focus {
            box-shadow: 0 0 0 5px rgba(255, 211, 28, 0.12);
        }

        .live-buttons {
            display: grid;
            grid-template-columns: 1fr .78fr;
            gap: 14px;
        }

        .live-btn {
            min-height: 92px;
            border: 0;
            border-radius: 24px;
            font-size: 1.05rem;
            font-weight: 900;
            letter-spacing: .14em;
            text-transform: uppercase;
            cursor: pointer;
            transition: transform .18s ease, opacity .18s ease;
        }

        .live-btn:hover {
            transform: translateY(-2px);
        }

        .live-btn-start {
            background: linear-gradient(135deg, #27d2f6 0%, #1a86e2 56%, #1456c5 100%);
            color: #111;
            box-shadow: 0 18px 36px rgba(29, 132, 226, 0.26);
        }

        .live-btn-stop {
            background: linear-gradient(135deg, #943755 0%, #a43c65 55%, #b13f72 100%);
            color: #fff;
            box-shadow: 0 18px 36px rgba(177, 63, 114, 0.24);
        }

        .live-btn-stop.is-disabled {
            opacity: .5;
            pointer-events: none;
        }

        .live-btn:disabled {
            opacity: .5;
            cursor: not-allowed;
            pointer-events: none;
            transform: none;
            box-shadow: none;
        }

        .live-status {
            text-align: center;
            color: rgba(255, 255, 255, 0.58);
            font-size: .98rem;
            letter-spacing: .18em;
            text-transform: uppercase;
        }

        .live-status strong {
            color: var(--live-text);
        }

        .live-table-wrap {
            margin-top: 10px;
            border-radius: 22px;
            border: 1px solid rgba(255, 255, 255, 0.08);
            background: rgba(255, 255, 255, 0.04);
            overflow: auto;
        }

        .live-table-toolbar {
            margin-top: 12px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            flex-wrap: wrap;
        }

        .live-table-search {
            width: min(100%, 420px);
            min-height: 54px;
            border-radius: 18px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            background: rgba(255, 255, 255, 0.05);
            color: var(--live-text);
            font-size: .98rem;
            padding: 0 18px;
            outline: none;
        }

        .live-table-search::placeholder {
            color: rgba(247, 247, 249, 0.44);
        }

        .live-table-search:focus {
            box-shadow: 0 0 0 4px rgba(39, 210, 246, 0.1);
            border-color: rgba(39, 210, 246, 0.24);
        }

        .live-table-page-size {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            color: rgba(255, 255, 255, 0.72);
            font-weight: 700;
        }

        .live-table-page-size select {
            min-width: 92px;
            min-height: 54px;
            border-radius: 18px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            background: rgba(255, 255, 255, 0.05);
            color: var(--live-text);
            font-size: .98rem;
            padding: 0 14px;
            outline: none;
        }

        .live-table-page-size select:focus {
            box-shadow: 0 0 0 4px rgba(39, 210, 246, 0.1);
            border-color: rgba(39, 210, 246, 0.24);
        }

        .live-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 720px;
        }

        .live-table th,
        .live-table td {
            padding: 16px 18px;
            text-align: left;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .live-table th {
            color: rgba(255, 255, 255, 0.58);
            font-size: .76rem;
            font-weight: 800;
            letter-spacing: .18em;
            text-transform: uppercase;
            background: rgba(255, 255, 255, 0.03);
            white-space: nowrap;
        }

        .live-table td {
            color: var(--live-text);
            font-size: .97rem;
        }

        .live-table tbody tr:last-child td {
            border-bottom: 0;
        }

        .live-table-rank {
            width: 90px;
            white-space: nowrap;
            font-weight: 900;
            color: var(--live-gold);
        }

        .live-table-name {
            font-weight: 800;
        }

        .live-table-subtext {
            color: var(--live-muted);
            font-size: .88rem;
        }

        .live-table-summary {
            margin-top: 12px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            flex-wrap: wrap;
            color: rgba(255, 255, 255, 0.64);
        }

        .live-table-pagination {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .live-table-pagination button {
            min-width: 44px;
            min-height: 44px;
            border-radius: 14px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            background: rgba(255, 255, 255, 0.04);
            color: var(--live-text);
            font-weight: 800;
            cursor: pointer;
            transition: transform .18s ease, background .18s ease, border-color .18s ease, opacity .18s ease;
        }

        .live-table-pagination button:hover {
            transform: translateY(-1px);
        }

        .live-table-pagination button.is-active {
            background: linear-gradient(135deg, #27d2f6 0%, #1a86e2 56%, #1456c5 100%);
            border-color: transparent;
            color: #fff;
        }

        .live-table-pagination button:disabled {
            opacity: .42;
            cursor: default;
            transform: none;
        }

        .live-clock {
            color: rgba(255, 211, 28, 0.42);
            font-size: .92rem;
            letter-spacing: .08em;
        }

        @keyframes liveConfettiFall {
            0% {
                opacity: 0;
                transform: translate3d(0, 0, 0) rotate(0deg) scale(.85);
            }

            8% {
                opacity: 1;
            }

            100% {
                opacity: 1;
                transform: translate3d(var(--confetti-drift, 0px), 118vh, 0) rotate(var(--confetti-spin, 520deg)) scale(1);
            }
        }

        @keyframes liveCelebrateRing {
            0% {
                opacity: .9;
                transform: scale(.3);
            }

            100% {
                opacity: 0;
                transform: scale(3.3);
            }
        }

        @keyframes liveWinnerPulse {
            0%, 100% {
                transform: scale(1);
            }

            25% {
                transform: scale(1.018);
            }

            55% {
                transform: scale(.996);
            }

            75% {
                transform: scale(1.012);
            }
        }

        @media (max-width: 1400px) {
            .live-stage,
            .live-panel {
                min-height: auto;
            }

            .live-winner-box {
                width: 100%;
                margin-top: 28px;
            }

            .live-watermark {
                bottom: 25%;
                left: 24%;
            }

            .live-table {
                min-width: 0;
            }
        }

        @media (max-width: 960px) {
            .live-stage {
                min-height: auto;
            }

            .live-panel-top,
            .live-topbar {
                flex-direction: column;
                align-items: flex-start;
            }

            .live-stats,
            .live-buttons,
            .live-filter-row {
                grid-template-columns: 1fr;
            }

            .live-table-toolbar,
            .live-table-summary {
                align-items: stretch;
            }

            .live-table-search {
                width: 100%;
            }

            .live-table {
                min-width: 640px;
            }

            .live-watermark {
                display: none;
            }

            .live-modal {
                padding: 18px;
            }

            .live-modal-dialog,
            .live-modal-card {
                padding: 26px 20px 24px;
            }

            .live-modal-meta {
                font-size: .92rem;
                letter-spacing: .1em;
            }
        }
    </style>
@endpush

@section('content')
    @php
        $winner = session('undian_winner');
        $displayWinnerName = $winner['peserta'] ?? $recentWinner?->peserta?->nama ?? '???';
        $displayWinnerPn = $winner['pn'] ?? $recentWinner?->peserta?->pn ?? 'Peserta siap diundi';
        $displayWinnerHadiah = $winner['hadiah'] ?? $recentWinner?->hadiah?->nama_hadiah ?? 'Silakan pilih hadiah';
        $displayWinnerRound = $winner['undian_ke'] ?? $recentWinner?->undian_ke ?? null;
        $shouldCelebrate = (bool) session()->has('undian_winner');
        $hadiahCategories = $hadiahTersedia
            ->pluck('kategori')
            ->filter(fn ($item) => filled($item))
            ->map(fn ($item) => trim((string) $item))
            ->unique()
            ->sort()
            ->values();
        $pemenangTerbaruJson = $pemenangTerbaru
            ->map(function ($item) {
                return [
                    'undian_ke' => $item->undian_ke,
                    'nama' => $item->peserta?->nama ?: '-',
                    'pn' => $item->peserta?->pn ?: 'PN tidak tersedia',
                    'hadiah' => $item->hadiah?->nama_hadiah ?: '-',
                    'kategori' => $item->hadiah?->kategori ?: 'Tanpa kategori',
                ];
            })
            ->values();
        $pesertaPoolJson = $pesertaPool
            ->map(function ($item) {
                return [
                    'nama' => $item->nama,
                    'pn' => $item->pn ?: 'PN tidak tersedia',
                    'uker' => $item->unit_kerja ?: 'Unit kerja belum diisi',
                    'jabatan' => $item->jabatan ?: 'Jabatan belum diisi',
                ];
            })
            ->values();
    @endphp

    <main class="live-shell">
        <section class="live-stage" id="liveStage" data-should-celebrate="{{ $shouldCelebrate ? '1' : '0' }}">
            <div class="live-confetti-layer" id="liveConfettiLayer" aria-hidden="true"></div>
            <div class="live-topbar">
                <div class="live-links">
                    <a class="live-link primary" href="{{ route('bcf.undian.index') }}"><i class="fa-solid fa-table-columns"></i> Dashboard Undian</a>
                    <a class="live-link" href="{{ route('bcf.registrasi.admin') }}"><i class="fa-solid fa-shield-halved"></i> Portal Admin</a>
                </div>
                <div class="live-copy">&copy; {{ now()->year }} Undian BCF BO Sriwijaya</div>
            </div>

            <div class="live-headline">
                <h1>Undian BCF BO Sriwijaya</h1>
                <img class="live-brand-logo" src="{{ asset('images/bcf-logo2.png') }}" alt="Logo BCF BO Sriwijaya">
            </div>

            <div class="live-watermark">BRILIAN CULTURE FEST SYSTEM {{ now()->format('m.d.Y') }}</div>

            <div class="live-winner-box">
                <div class="live-winner-badge">
                    <i class="fa-solid fa-sparkles"></i>
                    {{ $displayWinnerRound ? 'Pemenang Undian ke-' . $displayWinnerRound : 'Siap Diundi' }}
                </div>
                <div class="live-winner-name" id="liveWinnerName">{{ $displayWinnerName }}</div>
                <div class="live-winner-prize" id="liveWinnerPrize">{{ $displayWinnerHadiah }}</div>
                <div class="live-winner-meta" id="liveWinnerMeta">{{ $displayWinnerPn }} | {{ $recentWinner?->peserta?->jabatan ?: 'Jabatan belum diisi' }} | {{ $recentWinner?->peserta?->unit_kerja ?: 'Unit kerja belum diisi' }}</div>
            </div>

            <div class="live-panel">
                <div class="live-panel-top">
                    <div>
                        <h2 class="live-panel-title">Panel Kontrol Undian</h2>
                        <p class="live-panel-subtitle">Pemenang final tetap ditentukan server dari database agar hasil undian tetap valid.</p>
                    </div>
                    <div class="live-clock" id="liveClock">{{ now()->format('d M Y H:i:s') }}</div>
                </div>

                <div class="live-stats">
                    <article class="live-stat">
                        <span>Peserta Siap</span>
                        <strong>{{ $pesertaTersedia }}</strong>
                    </article>
                    <article class="live-stat">
                        <span>Hadiah Aktif</span>
                        <strong>{{ $hadiahTersedia->count() }}</strong>
                    </article>
                    <article class="live-stat">
                        <span>Total Pemenang</span>
                        <strong>{{ $dashboard['total_pemenang'] }}</strong>
                    </article>
                </div>

                <form class="live-form" method="POST" action="{{ route('bcf.undian.draw') }}" id="liveDrawForm">
                    @csrf
                    <input type="hidden" name="redirect_to" value="live">
                    <div class="live-filter-row">
                        <input class="live-search" type="text" id="liveHadiahSearch" placeholder="Cari nama hadiah...">
                        <select class="live-search" id="liveHadiahCategory">
                            <option value="">Semua kategori</option>
                            @foreach ($hadiahCategories as $kategori)
                                <option value="{{ $kategori }}">{{ $kategori }}</option>
                            @endforeach
                        </select>
                    </div>
                    <select class="live-select" name="hadiah_undi_id" id="liveHadiahSelect">
                        <option value="">-- Pilih Hadiah --</option>
                        @foreach ($hadiahTersedia as $item)
                            <option value="{{ $item->id }}" data-kategori="{{ $item->kategori ?: '' }}">{{ $item->nama_hadiah }}{{ $item->kategori ? ' - ' . $item->kategori : '' }} ({{ $item->stock_sisa }} tersisa)</option>
                        @endforeach
                    </select>

                    <div class="live-buttons">
                        <button class="live-btn live-btn-start" type="button" id="liveStartButton" disabled><i class="fa-solid fa-dice"></i> Mulai Undi</button>
                        <button class="live-btn live-btn-stop is-disabled" type="submit" id="liveStopButton"><i class="fa-solid fa-stop"></i> Stop</button>
                    </div>

                    <div class="live-status"><strong>{{ $pesertaTersedia }}</strong> peserta tersedia • <strong>{{ $dashboard['total_pemenang'] }}</strong> pemenang tercatat</div>
                </form>

                <div>
                    <h3 class="live-panel-title" style="font-size:1rem;">Pemenang Terbaru</h3>
                    <div class="live-table-toolbar">
                        <input class="live-table-search" type="text" id="liveWinnerSearch" placeholder="Cari nama pemenang, PN, hadiah, atau kategori...">
                        <label class="live-table-page-size">
                            <span>Baris per halaman</span>
                            <select id="liveWinnerPageSize">
                                <option value="5">5</option>
                                <option value="10" selected>10</option>
                                <option value="15">15</option>
                                <option value="20">20</option>
                            </select>
                        </label>
                    </div>
                    <div class="live-table-wrap">
                        <table class="live-table">
                            <thead>
                                <tr>
                                    <th>No Undian</th>
                                    <th>Nama Peserta</th>
                                    <th>Hadiah</th>
                                </tr>
                            </thead>
                            <tbody id="liveWinnerTableBody"></tbody>
                        </table>
                    </div>
                    <div class="live-table-summary">
                        <div id="liveWinnerSummary">Menampilkan 0 data</div>
                        <div class="live-table-pagination" id="liveWinnerPagination"></div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <div class="live-modal" id="liveWinnerModal" aria-hidden="true">
        <div class="live-modal-dialog" role="dialog" aria-modal="true" aria-labelledby="liveWinnerModalTitle">
            <button class="live-modal-close" type="button" id="liveWinnerModalClose" aria-label="Tutup pop up hasil undian">&times;</button>
            <div class="live-modal-card">
                <div class="live-winner-badge" id="liveWinnerModalTitle">
                    <i class="fa-solid fa-crown"></i>
                    {{ $displayWinnerRound ? 'Pemenang Undian ke-' . $displayWinnerRound : 'Hasil Undian' }}
                </div>
                <div class="live-modal-name" id="liveWinnerModalName">{{ $displayWinnerName }}</div>
                <div class="live-modal-prize" id="liveWinnerModalPrize">{{ $displayWinnerHadiah }}</div>
                <div class="live-modal-meta" id="liveWinnerModalMeta">{{ $displayWinnerPn }} | {{ $recentWinner?->peserta?->jabatan ?: 'Jabatan belum diisi' }} | {{ $recentWinner?->peserta?->unit_kerja ?: 'Unit kerja belum diisi' }}</div>
                <div class="live-modal-actions">
                    <button class="live-modal-dismiss" type="button" id="liveWinnerModalDismiss">Tutup Tampilan</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        (() => {
            const pool = @json($pesertaPoolJson);
            const winnerName = document.getElementById('liveWinnerName');
            const winnerPrize = document.getElementById('liveWinnerPrize');
            const winnerMeta = document.getElementById('liveWinnerMeta');
            const liveStage = document.getElementById('liveStage');
            const confettiLayer = document.getElementById('liveConfettiLayer');
            const winnerModal = document.getElementById('liveWinnerModal');
            const winnerModalClose = document.getElementById('liveWinnerModalClose');
            const winnerModalDismiss = document.getElementById('liveWinnerModalDismiss');
            const startButton = document.getElementById('liveStartButton');
            const stopButton = document.getElementById('liveStopButton');
            const form = document.getElementById('liveDrawForm');
            const hadiahSelect = document.getElementById('liveHadiahSelect');
            const hadiahSearch = document.getElementById('liveHadiahSearch');
            const hadiahCategory = document.getElementById('liveHadiahCategory');
            const winnerSearch = document.getElementById('liveWinnerSearch');
            const winnerPageSize = document.getElementById('liveWinnerPageSize');
            const winnerTableBody = document.getElementById('liveWinnerTableBody');
            const winnerSummary = document.getElementById('liveWinnerSummary');
            const winnerPagination = document.getElementById('liveWinnerPagination');
            const clock = document.getElementById('liveClock');
            const winnerRows = @json($pemenangTerbaruJson);
            const hadiahOptions = Array.from(hadiahSelect.options).map((option) => ({
                value: option.value,
                label: option.textContent,
                kategori: option.dataset.kategori || '',
            }));

            let spinTimer = null;
            let winnerCurrentPage = 1;
            const updateClock = () => {
                clock.textContent = new Date().toLocaleString('id-ID', {
                    day: '2-digit',
                    month: 'short',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit',
                });
            };

            const openWinnerModal = () => {
                if (!winnerModal) {
                    return;
                }

                winnerModal.classList.add('is-open');
                winnerModal.setAttribute('aria-hidden', 'false');
                document.body.style.overflow = 'hidden';
            };

            const closeWinnerModal = () => {
                if (!winnerModal) {
                    return;
                }

                winnerModal.classList.remove('is-open');
                winnerModal.setAttribute('aria-hidden', 'true');
                document.body.style.overflow = '';
            };

            const randomParticipant = () => pool[Math.floor(Math.random() * pool.length)];

            const filteredWinnerRows = () => {
                const keyword = String(winnerSearch?.value || '').trim().toLowerCase();

                if (keyword === '') {
                    return winnerRows;
                }

                return winnerRows.filter((item) => {
                    return [
                        '#' + item.undian_ke,
                        item.nama,
                        item.pn,
                        item.hadiah,
                        item.kategori,
                    ].some((value) => String(value || '').toLowerCase().includes(keyword));
                });
            };

            const renderWinnerTable = () => {
                if (!winnerTableBody || !winnerSummary || !winnerPagination || !winnerPageSize) {
                    return;
                }

                const rows = filteredWinnerRows();
                const pageSize = Math.max(1, parseInt(winnerPageSize.value, 10) || 10);
                const totalRows = rows.length;
                const totalPages = Math.max(1, Math.ceil(totalRows / pageSize));

                if (winnerCurrentPage > totalPages) {
                    winnerCurrentPage = totalPages;
                }

                const startIndex = (winnerCurrentPage - 1) * pageSize;
                const endIndex = Math.min(startIndex + pageSize, totalRows);
                const pageRows = rows.slice(startIndex, endIndex);

                winnerTableBody.innerHTML = '';

                if (!pageRows.length) {
                    winnerTableBody.innerHTML = `
                        <tr>
                            <td colspan="3">
                                <div class="live-table-name">Data tidak ditemukan</div>
                                <div class="live-table-subtext">Coba ubah kata kunci pencarian rekap pemenang.</div>
                            </td>
                        </tr>
                    `;
                } else {
                    pageRows.forEach((item) => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td class="live-table-rank">#${item.undian_ke}</td>
                            <td>
                                <div class="live-table-name">${item.nama}</div>
                                <div class="live-table-subtext">${item.pn}</div>
                            </td>
                            <td>
                                <div class="live-table-name">${item.hadiah}</div>
                                <div class="live-table-subtext">${item.kategori}</div>
                            </td>
                        `;
                        winnerTableBody.appendChild(row);
                    });
                }

                if (!totalRows) {
                    winnerSummary.textContent = 'Menampilkan 0 dari 0 data pemenang';
                } else {
                    winnerSummary.textContent = `Menampilkan ${startIndex + 1}-${endIndex} dari ${totalRows} data pemenang`;
                }

                winnerPagination.innerHTML = '';

                const previousButton = document.createElement('button');
                previousButton.type = 'button';
                previousButton.textContent = '‹';
                previousButton.disabled = winnerCurrentPage === 1;
                previousButton.addEventListener('click', () => {
                    if (winnerCurrentPage > 1) {
                        winnerCurrentPage -= 1;
                        renderWinnerTable();
                    }
                });
                winnerPagination.appendChild(previousButton);

                for (let page = 1; page <= totalPages; page += 1) {
                    const pageButton = document.createElement('button');
                    pageButton.type = 'button';
                    pageButton.textContent = String(page);
                    pageButton.classList.toggle('is-active', page === winnerCurrentPage);
                    pageButton.addEventListener('click', () => {
                        winnerCurrentPage = page;
                        renderWinnerTable();
                    });
                    winnerPagination.appendChild(pageButton);
                }

                const nextButton = document.createElement('button');
                nextButton.type = 'button';
                nextButton.textContent = '›';
                nextButton.disabled = winnerCurrentPage === totalPages || totalRows === 0;
                nextButton.addEventListener('click', () => {
                    if (winnerCurrentPage < totalPages) {
                        winnerCurrentPage += 1;
                        renderWinnerTable();
                    }
                });
                winnerPagination.appendChild(nextButton);
            };

            const celebrateWinner = () => {
                if (!liveStage || !confettiLayer) {
                    return;
                }

                liveStage.classList.add('is-celebrating');
                confettiLayer.innerHTML = '';

                const palette = ['#ffd31c', '#27d2f6', '#ff5f86', '#ffffff', '#7d5cff', '#4ef0b7'];
                const pieceCount = 120;

                for (let index = 0; index < pieceCount; index += 1) {
                    const piece = document.createElement('span');
                    const left = Math.random() * 100;
                    const drift = (Math.random() - 0.5) * 220;
                    const duration = 2.4 + (Math.random() * 1.8);
                    const delay = Math.random() * 0.45;
                    const spin = ((Math.random() > 0.5 ? 1 : -1) * (360 + Math.random() * 720)).toFixed(0) + 'deg';
                    const color = palette[Math.floor(Math.random() * palette.length)];

                    piece.className = 'live-confetti' + (Math.random() > 0.72 ? ' is-streamer' : '');
                    piece.style.left = left.toFixed(2) + '%';
                    piece.style.background = color;
                    piece.style.animationDuration = duration.toFixed(2) + 's';
                    piece.style.animationDelay = delay.toFixed(2) + 's';
                    piece.style.setProperty('--confetti-drift', drift.toFixed(0) + 'px');
                    piece.style.setProperty('--confetti-spin', spin);
                    confettiLayer.appendChild(piece);

                    piece.addEventListener('animationend', () => piece.remove(), { once: true });
                }

                for (let burst = 0; burst < 3; burst += 1) {
                    const ring = document.createElement('span');
                    ring.className = 'live-celebrate-ring';
                    ring.style.animationDelay = (burst * 0.16).toFixed(2) + 's';
                    confettiLayer.appendChild(ring);
                    ring.addEventListener('animationend', () => ring.remove(), { once: true });
                }

                window.setTimeout(() => {
                    liveStage.classList.remove('is-celebrating');
                }, 2600);

                try {
                    const AudioContextClass = window.AudioContext || window.webkitAudioContext;
                    if (!AudioContextClass) {
                        return;
                    }

                    const audioContext = new AudioContextClass();
                    const now = audioContext.currentTime + 0.02;

                    const playTone = (frequency, start, duration, type, gainValue) => {
                        const oscillator = audioContext.createOscillator();
                        const gainNode = audioContext.createGain();
                        oscillator.type = type;
                        oscillator.frequency.setValueAtTime(frequency, start);
                        gainNode.gain.setValueAtTime(0.0001, start);
                        gainNode.gain.exponentialRampToValueAtTime(gainValue, start + 0.02);
                        gainNode.gain.exponentialRampToValueAtTime(0.0001, start + duration);
                        oscillator.connect(gainNode);
                        gainNode.connect(audioContext.destination);
                        oscillator.start(start);
                        oscillator.stop(start + duration + 0.04);
                    };

                    [523.25, 659.25, 783.99, 1046.5].forEach((frequency, index) => {
                        playTone(frequency, now + (index * 0.11), 0.28, 'triangle', 0.07);
                        playTone(frequency * 2, now + (index * 0.11), 0.18, 'sine', 0.035);
                    });

                    const buffer = audioContext.createBuffer(1, audioContext.sampleRate * 0.35, audioContext.sampleRate);
                    const channel = buffer.getChannelData(0);
                    for (let index = 0; index < channel.length; index += 1) {
                        channel[index] = (Math.random() * 2 - 1) * (1 - (index / channel.length));
                    }

                    const noise = audioContext.createBufferSource();
                    const noiseFilter = audioContext.createBiquadFilter();
                    const noiseGain = audioContext.createGain();
                    noise.buffer = buffer;
                    noiseFilter.type = 'highpass';
                    noiseFilter.frequency.setValueAtTime(1100, now);
                    noiseGain.gain.setValueAtTime(0.0001, now);
                    noiseGain.gain.exponentialRampToValueAtTime(0.045, now + 0.02);
                    noiseGain.gain.exponentialRampToValueAtTime(0.0001, now + 0.32);
                    noise.connect(noiseFilter);
                    noiseFilter.connect(noiseGain);
                    noiseGain.connect(audioContext.destination);
                    noise.start(now + 0.08);
                    noise.stop(now + 0.42);
                } catch (error) {
                    console.debug('Celebration audio skipped:', error);
                }
            };

            const currentHadiahLabel = () => {
                const selectedOption = hadiahSelect.options[hadiahSelect.selectedIndex];
                if (!selectedOption || !selectedOption.value) {
                    return winnerPrize.textContent || 'Silakan pilih hadiah';
                }

                return selectedOption.textContent;
            };

            const renderParticipant = (participant) => {
                winnerName.textContent = participant.nama;
                winnerPrize.textContent = currentHadiahLabel();
                winnerMeta.textContent = participant.pn + ' | ' + participant.jabatan + ' | ' + participant.uker;
            };

            const renderHadiahOptions = (keyword = '', kategori = '') => {
                const normalizedKeyword = String(keyword || '').trim().toLowerCase();
                const normalizedKategori = String(kategori || '').trim().toLowerCase();
                const currentValue = hadiahSelect.value;
                const filteredOptions = hadiahOptions.filter((option, index) => {
                    if (index === 0) {
                        return true;
                    }

                    const matchKeyword = option.label.toLowerCase().includes(normalizedKeyword);
                    const matchKategori = normalizedKategori === '' || option.kategori.toLowerCase() === normalizedKategori;

                    return matchKeyword && matchKategori;
                });

                hadiahSelect.innerHTML = '';
                filteredOptions.forEach((option) => {
                    const optionElement = document.createElement('option');
                    optionElement.value = option.value;
                    optionElement.textContent = option.label;
                    optionElement.dataset.kategori = option.kategori;
                    hadiahSelect.appendChild(optionElement);
                });

                if ([...hadiahSelect.options].some((option) => option.value === currentValue)) {
                    hadiahSelect.value = currentValue;
                } else {
                    hadiahSelect.value = '';
                }
            };

            const setIdleState = () => {
                window.clearInterval(spinTimer);
                spinTimer = null;
                stopButton.classList.add('is-disabled');
                startButton.disabled = hadiahSelect.value === '';
            };

            const syncStartButtonState = () => {
                if (spinTimer) {
                    return;
                }

                startButton.disabled = hadiahSelect.value === '';
            };

            startButton.addEventListener('click', () => {
                if (!pool.length) {
                    winnerName.textContent = 'Peserta Habis';
                    winnerPrize.textContent = currentHadiahLabel();
                    winnerMeta.textContent = 'Tidak ada peserta aktif yang bisa diundi';
                    return;
                }

                if (hadiahSelect.options.length <= 1) {
                    winnerName.textContent = 'Hadiah Habis';
                    winnerPrize.textContent = 'Tidak ada hadiah aktif';
                    winnerMeta.textContent = 'Tidak ada hadiah aktif yang bisa diundi';
                    return;
                }

                startButton.disabled = true;
                stopButton.classList.remove('is-disabled');

                spinTimer = window.setInterval(() => {
                    const participant = randomParticipant();
                    renderParticipant(participant);
                }, 90);
            });

            stopButton.addEventListener('click', (event) => {
                if (stopButton.classList.contains('is-disabled')) {
                    event.preventDefault();
                    return;
                }

                setIdleState();
            });

            form.addEventListener('submit', () => {
                setIdleState();
            });

            hadiahSearch.addEventListener('input', (event) => {
                renderHadiahOptions(event.target.value, hadiahCategory.value);
            });

            hadiahCategory.addEventListener('change', (event) => {
                renderHadiahOptions(hadiahSearch.value, event.target.value);
            });

            winnerSearch?.addEventListener('input', () => {
                winnerCurrentPage = 1;
                renderWinnerTable();
            });

            winnerPageSize?.addEventListener('change', () => {
                winnerCurrentPage = 1;
                renderWinnerTable();
            });

            hadiahSelect.addEventListener('change', () => {
                if (!spinTimer) {
                    winnerPrize.textContent = currentHadiahLabel();
                }

                syncStartButtonState();
            });

            renderHadiahOptions(hadiahSearch.value, hadiahCategory.value);
            syncStartButtonState();
            renderWinnerTable();
            updateClock();
            window.setInterval(updateClock, 1000);

            if (liveStage?.dataset.shouldCelebrate === '1') {
                window.setTimeout(() => {
                    celebrateWinner();
                    openWinnerModal();
                }, 180);
            }

            winnerModalClose?.addEventListener('click', closeWinnerModal);
            winnerModalDismiss?.addEventListener('click', closeWinnerModal);
            winnerModal?.addEventListener('click', (event) => {
                if (event.target === winnerModal) {
                    closeWinnerModal();
                }
            });

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape' && winnerModal?.classList.contains('is-open')) {
                    closeWinnerModal();
                }
            });
        })();
    </script>
@endpush
