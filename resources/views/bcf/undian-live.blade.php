@extends('layouts.app-public')

@section('title', 'Undian BCF Live')
@section('description', 'Laman live undian BCF BO Sriwijaya.')

@push('styles')
    <style>
        :root {
            --live-bg: #0a4ead;
            --live-bg-soft: #083b82;
            --live-panel: rgba(10, 78, 173, 0.86);
            --live-line: rgba(255, 208, 0, 0.18);
            --live-gold: #ffd31c;
            --live-gold-soft: #ffb000;
            --live-cyan: #27e0ff;
            --live-pink: #ff4a72;
            --live-text: #f7f7f9;
            --live-muted: rgba(247, 247, 249, 0.58);
        }

        /* Nonaktifkan efek blur SweetAlert2 pada halaman */
        body.swal2-shown:not(.swal2-no-backdrop),
        body.swal2-blur,
        .swal2-container ~ body {
            filter: none !important;
        }

        body.swal2-shown > :not(.swal2-container) {
            filter: none !important;
        }

        body {
            margin: 0;
            background:
                linear-gradient(rgba(255, 211, 28, 0.06) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 211, 28, 0.06) 1px, transparent 1px),
                radial-gradient(circle at top center, rgba(255, 211, 28, 0.18), transparent 28%),
                radial-gradient(circle at 70% 72%, rgba(10, 78, 173, 0.22), transparent 30%),
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
                linear-gradient(180deg, rgba(10, 78, 173, 0.88), rgba(6, 40, 100, 0.94));
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
                radial-gradient(circle at 82% 72%, rgba(39, 224, 255, 0.14), transparent 28%);
            pointer-events: none;
        }

        .live-confetti-layer {
            position: absolute;
            inset: 0;
            overflow: hidden;
            pointer-events: none;
            z-index: 80;
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
            background: linear-gradient(180deg, rgba(10, 78, 173, 0.9), rgba(5, 31, 78, 0.95));
            box-shadow: 0 34px 90px rgba(0, 0, 0, 0.34);
            padding: 34px 30px 28px;
            text-align: center;
        }

        .live-winner-box.is-batch {
            width: min(100%, 1360px);
        }

        .live-winner-box.is-batch .live-winner-name {
            min-height: auto;
            font-size: clamp(1.9rem, 3.1vw, 3rem);
            margin-bottom: 10px;
        }

        .live-winner-box.is-batch .live-winner-prize {
            font-size: clamp(1rem, 1.8vw, 1.35rem);
        }

        .live-winner-box.is-grandprize .live-winner-badge {
            display: block;
            width: fit-content;
            margin: 0 auto;
            padding: 0;
            border: 0;
            background: transparent;
            box-shadow: none;
            color: var(--live-gold);
            font-size: clamp(2.6rem, 5vw, 4.9rem);
            font-weight: 900;
            letter-spacing: .12em;
            line-height: .92;
            text-shadow: 0 0 30px rgba(255, 211, 28, 0.28);
        }

        .live-winner-box.is-grandprize .live-winner-badge i {
            display: none;
        }

        .live-winner-box.is-grandprize .live-winner-name {
            margin-top: 12px;
            min-height: clamp(3rem, 5vw, 4rem);
            font-size: clamp(1.8rem, 3vw, 3.25rem);
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

        .live-batch-results {
            display: none;
            margin-top: 22px;
            text-align: left;
        }

        .live-batch-results.is-visible {
            display: block;
        }

        .live-batch-summary {
            margin-bottom: 12px;
            color: rgba(255, 255, 255, 0.84);
            font-weight: 800;
            letter-spacing: .08em;
            text-transform: uppercase;
        }

        .live-batch-table-wrap {
            margin-top: 0;
        }

        .live-modal {
            position: fixed;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 28px;
            background: rgba(7, 6, 16, 0.82);
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
            backdrop-filter: blur(16px);
        }

        .live-modal-dialog {
            position: relative;
            width: min(100%, 1320px);
            padding: 34px;
            border-radius: 36px;
            border: 1px solid rgba(255, 211, 28, 0.18);
            background:
                radial-gradient(circle at top center, rgba(255, 211, 28, 0.12), transparent 32%),
                linear-gradient(180deg, rgba(10, 78, 173, 0.96), rgba(4, 26, 70, 0.98));
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
            border: 1px solid rgba(147, 198, 255, 0.28);
            background: rgba(10, 78, 173, 0.42);
            color: var(--live-text);
            font-size: 1.4rem;
            cursor: pointer;
            box-shadow: 0 12px 28px rgba(10, 78, 173, 0.2);
        }

        .live-modal-card {
            border-radius: 34px;
            border: 2px solid rgba(255, 211, 28, 0.26);
            background: linear-gradient(180deg, rgba(10, 78, 173, 0.95), rgba(4, 27, 72, 0.98));
            box-shadow:
                0 34px 90px rgba(0, 0, 0, 0.34),
                0 0 50px rgba(255, 211, 28, 0.14);
            padding: 46px 40px 38px;
            text-align: center;
        }

        .live-modal-card.is-batch {
            max-height: calc(100vh - 140px);
            overflow: auto;
        }

        .live-modal-card.is-batch .live-brand-logo {
            width: min(100%, 172px);
            margin: 0 auto 14px;
        }

        .live-modal-card .live-brand-logo {
            width: min(100%, 168px);
            margin: 0 auto 18px;
            filter: drop-shadow(0 16px 26px rgba(0, 0, 0, 0.24));
        }

        .live-modal-card.is-batch .live-modal-name {
            margin: 0 0 10px;
            font-size: clamp(2.2rem, 4.8vw, 4.8rem);
        }

        .live-modal-card.is-batch .live-modal-prize {
            font-size: clamp(1.2rem, 2.2vw, 2rem);
        }

        .live-modal-card.is-batch .live-modal-meta {
            display: none;
        }

        .live-modal-card.is-batch .live-batch-summary {
            display: none;
        }

        .live-modal-card.is-batch .live-batch-table-wrap {
            max-height: 420px;
            overflow: auto;
            border-radius: 22px;
        }

        .live-modal-card.is-batch .live-batch-table-wrap .live-table thead th {
            position: sticky;
            top: 0;
            z-index: 2;
            background: rgba(10, 78, 173, 0.96);
            backdrop-filter: blur(8px);
        }

        .live-modal-card.is-grandprize .live-winner-badge {
            display: block;
            width: fit-content;
            margin: 0 auto;
            padding: 0;
            border: 0;
            background: transparent;
            box-shadow: none;
            color: var(--live-gold);
            font-size: clamp(3.3rem, 6.2vw, 6.6rem);
            font-weight: 900;
            letter-spacing: .12em;
            line-height: .9;
            text-shadow: 0 0 40px rgba(255, 211, 28, 0.32);
        }

        .live-modal-card.is-grandprize .live-winner-badge i {
            display: none;
        }

        .live-modal-card.is-grandprize .live-modal-name {
            margin-top: 14px;
            font-size: clamp(2.2rem, 4.7vw, 4.9rem);
            line-height: .95;
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
            background: linear-gradient(135deg, #1d9cff 0%, #0f6fe0 52%, #0a4ead 100%);
            color: #fff;
            font-size: 1rem;
            font-weight: 900;
            letter-spacing: .12em;
            text-transform: uppercase;
            cursor: pointer;
            box-shadow: 0 18px 36px rgba(10, 78, 173, 0.3);
        }

        .live-panel {
            border-radius: 28px;
            border: 1px solid rgba(255, 255, 255, 0.08);
            background: linear-gradient(180deg, rgba(9, 11, 20, 0.92), rgba(22, 7, 34, 0.95));
            background: linear-gradient(180deg, rgba(7, 44, 111, 0.94), rgba(5, 29, 76, 0.97));
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
            background: linear-gradient(135deg, #24b9f1 0%, #0f7be7 54%, #0a4ead 100%);
            color: #fff;
            box-shadow: 0 18px 36px rgba(10, 78, 173, 0.3);
        }

        .live-btn-stop {
            background: linear-gradient(135deg, #943755 0%, #a43c65 55%, #b13f72 100%);
            color: #fff;
            box-shadow: 0 18px 36px rgba(177, 63, 114, 0.24);
        }

        .live-btn-approve {
            background: linear-gradient(135deg, #42c76a 0%, #28a745 54%, #1e7e34 100%);
            color: #fff;
            box-shadow: 0 18px 36px rgba(40, 167, 69, 0.24);
        }

        .live-btn-reject {
            background: linear-gradient(135deg, #f75d6c 0%, #dc3545 55%, #b21f2d 100%);
            color: #fff;
            box-shadow: 0 18px 36px rgba(220, 53, 69, 0.24);
        }

        .live-toast-compact {
            width: auto !important;
            min-width: 0 !important;
            max-width: 420px !important;
            border-radius: 14px !important;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.38) !important;
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

        .live-table-toolbar-actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 14px;
            flex-wrap: wrap;
            margin-left: auto;
        }

        .live-table-export {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            min-height: 54px;
            padding: 0 20px;
            border-radius: 18px;
            border: 1px solid rgba(39, 210, 246, 0.2);
            background: linear-gradient(135deg, #24b9f1 0%, #0f7be7 54%, #0a4ead 100%);
            color: #fff;
            font-size: .94rem;
            font-weight: 900;
            letter-spacing: .08em;
            text-transform: uppercase;
            text-decoration: none;
            box-shadow: 0 16px 32px rgba(10, 78, 173, 0.28);
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
            background: linear-gradient(135deg, #24b9f1 0%, #0f7be7 54%, #0a4ead 100%);
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
        $winnerMode = $winner['mode'] ?? 'single';
        $winnerBatchItems = collect($winner['items'] ?? []);
        $isBatchWinner = $winnerMode === 'batch' && $winnerBatchItems->isNotEmpty();
        $batchWinnerKategori = $winner['kategori'] ?? 'Hadiah Batch';
        $singleWinnerKategori = $winner['kategori'] ?? $recentWinner?->hadiah?->kategori ?? null;
        $normalizedSingleWinnerKategori = \Illuminate\Support\Str::of((string) $singleWinnerKategori)->lower()->squish()->value();
        $isGrandPrizeWinner = in_array($normalizedSingleWinnerKategori, ['hadiah besar', 'grand prize', 'grandprize'], true);
        $displayWinnerName = $isBatchWinner
            ? 'Undian ' . $batchWinnerKategori
            : ($winner['peserta'] ?? $recentWinner?->peserta?->nama ?? '???');
        $displayWinnerPn = $isBatchWinner
            ? ($winnerBatchItems->count() . ' pemenang terpilih')
            : ($winner['pn'] ?? $recentWinner?->peserta?->pn ?? 'Peserta siap diundi');
        $displayWinnerJabatan = $isBatchWinner
            ? null
            : ($winner['jabatan'] ?? $recentWinner?->peserta?->jabatan ?? 'Jabatan belum diisi');
        $displayWinnerUnitKerja = $isBatchWinner
            ? null
            : ($winner['uker'] ?? $recentWinner?->peserta?->unit_kerja ?? 'Unit kerja belum diisi');
        $displayWinnerHadiah = $isBatchWinner
            ? ($winnerBatchItems->count() . ' Penerima')
            : ($winner['hadiah'] ?? $recentWinner?->hadiah?->nama_hadiah ?? 'Silakan pilih hadiah');
        $displayWinnerRound = $isBatchWinner
            ? ($winner['undian_ke_selesai'] ?? null)
            : ($winner['undian_ke'] ?? $recentWinner?->undian_ke ?? null);
        $displayWinnerBadge = $isBatchWinner
            ? 'Hasil ' . $batchWinnerKategori
            : ($isGrandPrizeWinner ? 'GrandPrize' : ($displayWinnerRound ? 'Pemenang Undian ke-' . $displayWinnerRound : 'Siap Diundi'));
        $displayWinnerMeta = $isBatchWinner
            ? ('Undian #' . ($winner['undian_ke_mulai'] ?? '-') . ' sampai #' . ($winner['undian_ke_selesai'] ?? '-') . ' | Tutup modal untuk lanjut undian berikutnya')
            : (($displayWinnerPn ?: 'Peserta siap diundi') . ' | ' . ($displayWinnerJabatan ?: 'Jabatan belum diisi') . ' | ' . ($displayWinnerUnitKerja ?: 'Unit kerja belum diisi'));
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
                    'no_hadiah' => $item->hadiah?->no_urut ?: '-',
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
                    'id' => $item->id,
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

            <div class="live-winner-box {{ $isBatchWinner ? 'is-batch' : '' }} {{ $isGrandPrizeWinner ? 'is-grandprize' : '' }}" id="liveWinnerBox">
                <div class="live-winner-badge" id="liveWinnerBadge">
                    <i class="fa-solid fa-sparkles"></i>
                    {{ $displayWinnerBadge }}
                </div>
                <div class="live-winner-name" id="liveWinnerName">{{ $displayWinnerName }}</div>
                <div class="live-winner-prize" id="liveWinnerPrize">{{ $displayWinnerHadiah }}</div>
                <div class="live-winner-meta" id="liveWinnerMeta">{{ $displayWinnerMeta }}</div>
                <div class="live-batch-results {{ $isBatchWinner ? 'is-visible' : '' }}" id="liveBatchResults">
                    <div class="live-batch-summary">{{ $winnerBatchItems->count() }} pemenang menerima seluruh item {{ $batchWinnerKategori }}</div>
                    <div class="live-table-wrap live-batch-table-wrap">
                        <table class="live-table">
                            <thead>
                                <tr>
                                    <th>No Undian</th>
                                    <th>No Hadiah</th>
                                    <th>Nama Peserta</th>
                                    <th>Hadiah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($winnerBatchItems as $batchItem)
                                    <tr>
                                        <td class="live-table-rank">#{{ $batchItem['undian_ke'] ?? '-' }}</td>
                                        <td class="live-table-rank">{{ $batchItem['no_hadiah'] ?? '-' }}</td>
                                        <td>
                                            <div class="live-table-name">{{ $batchItem['peserta'] ?? '-' }}</div>
                                            <div class="live-table-subtext">{{ $batchItem['pn'] ?: 'PN tidak tersedia' }}</div>
                                        </td>
                                        <td>
                                            <div class="live-table-name">{{ $batchItem['hadiah'] ?? '-' }}</div>
                                            <div class="live-table-subtext">{{ $batchItem['kategori'] ?: 'Tanpa kategori' }}</div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
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
                    <input type="hidden" name="peserta_undi_id" id="liveSelectedParticipantId" value="">
                    <div class="live-filter-row">
                        <input class="live-search" type="text" id="liveHadiahSearch" placeholder="Cari nama hadiah...">
                        <select class="live-search" id="liveHadiahCategory" name="hadiah_kategori">
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
                        <button class="live-btn live-btn-stop is-disabled" type="button" id="liveStopButton"><i class="fa-solid fa-stop"></i> Stop</button>
                    </div>

                    <div class="live-status"><strong>{{ $pesertaTersedia }}</strong> peserta tersedia • <strong>{{ $dashboard['total_pemenang'] }}</strong> pemenang tercatat</div>
                </form>

                <div>
                    <h3 class="live-panel-title" style="font-size:1rem;">Pemenang Terbaru</h3>
                    <div class="live-table-toolbar">
                        <input class="live-table-search" type="text" id="liveWinnerSearch" placeholder="Cari nama pemenang, PN, hadiah, atau kategori...">
                        <div class="live-table-toolbar-actions">
                            <a class="live-table-export" href="{{ route('bcf.undian.rekap.export') }}">
                                <i class="fa-solid fa-file-excel"></i>
                                Export Excel
                            </a>
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
                    </div>
                    <div class="live-table-wrap">
                        <table class="live-table">
                            <thead>
                                <tr>
                                    <th>No Undian</th>
                                    <th>No Hadiah</th>
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
            <button class="live-modal-close" type="button" id="liveWinnerModalClose" aria-label="Tutup pop up hasil undian" style="display: none;">&times;</button>
            <div class="live-modal-card {{ $isBatchWinner ? 'is-batch' : '' }} {{ $isGrandPrizeWinner ? 'is-grandprize' : '' }}" id="liveWinnerModalCard">
                <img class="live-brand-logo" src="{{ asset('images/bcf-logo2.png') }}" alt="Logo BCF BO Sriwijaya">
                @if (! $isBatchWinner)
                    <div class="live-winner-badge" id="liveWinnerModalTitle">
                        <i class="fa-solid fa-crown"></i>
                        {{ $displayWinnerBadge }}
                    </div>
                @endif
                <div class="live-modal-name" id="liveWinnerModalName">{{ $displayWinnerName }}</div>
                <div class="live-modal-prize" id="liveWinnerModalPrize">{{ $displayWinnerHadiah }}</div>
                <div class="live-modal-meta" id="liveWinnerModalMeta">{{ $displayWinnerMeta }}</div>
                <div class="live-batch-results {{ $isBatchWinner ? 'is-visible' : '' }}" id="liveWinnerModalBatchResults">
                    <div class="live-batch-summary">{{ $winnerBatchItems->count() }} pemenang menerima seluruh item {{ $batchWinnerKategori }}</div>
                    <div class="live-table-wrap live-batch-table-wrap">
                        <table class="live-table">
                            <thead>
                                <tr>
                                    <th>No Undian</th>
                                    <th>No Hadiah</th>
                                    <th>Nama Peserta</th>
                                    <th>Hadiah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($winnerBatchItems as $batchItem)
                                    <tr>
                                        <td class="live-table-rank">#{{ $batchItem['undian_ke'] ?? '-' }}</td>
                                        <td class="live-table-rank">{{ $batchItem['no_hadiah'] ?? '-' }}</td>
                                        <td>
                                            <div class="live-table-name">{{ $batchItem['peserta'] ?? '-' }}</div>
                                            <div class="live-table-subtext">{{ $batchItem['pn'] ?: 'PN tidak tersedia' }}</div>
                                        </td>
                                        <td>
                                            <div class="live-table-name">{{ $batchItem['hadiah'] ?? '-' }}</div>
                                            <div class="live-table-subtext">{{ $batchItem['kategori'] ?: 'Tanpa kategori' }}</div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="live-modal-actions" style="display: flex; gap: 12px; justify-content: center; width: 100%; margin-top: 24px;">
                    <button class="live-btn live-btn-approve" type="button" id="liveWinnerModalApprove" style="flex: 1; margin: 0; padding: 14px; text-shadow: none;"><i class="fa-solid fa-check"></i> {{ $shouldCelebrate ? 'Tutup Hasil' : 'Sah / Setujui' }}</button>
                    <button class="live-btn live-btn-reject" type="button" id="liveWinnerModalReject" style="flex: 1; margin: 0; padding: 14px; text-shadow: none;"><i class="fa-solid fa-xmark"></i> Batal / Gugurkan</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        (() => {
            const pool = @json($pesertaPoolJson);
            const initialShouldCelebrate = @json($shouldCelebrate);
            const isInitialBatchWinner = @json($isBatchWinner);
            let isServerResultModal = initialShouldCelebrate;
            const batchCategoryLabels = ['hadiah kecil', 'hadiah sedang'];
            const winnerName = document.getElementById('liveWinnerName');
            const winnerPrize = document.getElementById('liveWinnerPrize');
            const winnerMeta = document.getElementById('liveWinnerMeta');
            const winnerBadge = document.getElementById('liveWinnerBadge');
            const winnerBox = document.getElementById('liveWinnerBox');
            const liveStage = document.getElementById('liveStage');
            const confettiLayer = document.getElementById('liveConfettiLayer');
            const winnerModal = document.getElementById('liveWinnerModal');
            const winnerModalClose = document.getElementById('liveWinnerModalClose');
            const winnerModalApprove = document.getElementById('liveWinnerModalApprove');
            const winnerModalReject = document.getElementById('liveWinnerModalReject');
            const currentWinnerIds = @json($winner['ids'] ?? (!empty($winner['id']) ? [$winner['id']] : []));
            const winnerModalCard = document.getElementById('liveWinnerModalCard');
            const winnerModalName = document.getElementById('liveWinnerModalName');
            const winnerModalPrize = document.getElementById('liveWinnerModalPrize');
            const winnerModalMeta = document.getElementById('liveWinnerModalMeta');
            const batchResults = document.getElementById('liveBatchResults');
            const modalBatchResults = document.getElementById('liveWinnerModalBatchResults');
            const startButton = document.getElementById('liveStartButton');
            const stopButton = document.getElementById('liveStopButton');
            const form = document.getElementById('liveDrawForm');
            const selectedParticipantInput = document.getElementById('liveSelectedParticipantId');
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
            let currentDrawParticipant = null;
            let isSavingWinner = false;
            let winnerCurrentPage = 1;
            const normalizeCategoryValue = (value) => String(value || '')
                .toLowerCase()
                .replace(/\s+/g, ' ')
                .trim();
            const isMassCategory = (value) => batchCategoryLabels.includes(normalizeCategoryValue(value));
            const hasSelectedHadiah = () => String(hadiahSelect?.value || '').trim() !== '';
            const isBatchSelection = () => isMassCategory(hadiahCategory?.value);
            const currentBatchCategoryLabel = () => {
                const value = String(hadiahCategory?.value || '').replace(/\s+/g, ' ').trim();
                return value === '' ? 'Hadiah Batch' : value;
            };

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

            const resetWinnerDisplay = () => {
                if (winnerBadge) {
                    winnerBadge.innerHTML = '<i class="fa-solid fa-sparkles"></i> Siap Diundi';
                }

                winnerBox?.classList.remove('is-batch');
                winnerBox?.classList.remove('is-grandprize');
                winnerModalCard?.classList.remove('is-batch');
                winnerModalCard?.classList.remove('is-grandprize');
                batchResults?.classList.remove('is-visible');
                modalBatchResults?.classList.remove('is-visible');
                winnerName.textContent = '???';
                winnerPrize.textContent = 'Silakan pilih hadiah';
                winnerMeta.textContent = 'Peserta siap diundi';
                if (winnerModalName) {
                    winnerModalName.textContent = '???';
                }
                if (winnerModalPrize) {
                    winnerModalPrize.textContent = 'Silakan pilih hadiah';
                }
                if (winnerModalMeta) {
                    winnerModalMeta.textContent = 'Peserta siap diundi';
                }
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

                if (isServerResultModal) {
                    resetWinnerDisplay();
                }
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
                        item.no_hadiah,
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
                            <td colspan="4">
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
                            <td class="live-table-rank">${item.no_hadiah ?? '-'}</td>
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
                if (isBatchSelection()) {
                    return `${currentBatchCategoryLabel()} - Semua Item`;
                }

                const selectedOption = hadiahSelect.options[hadiahSelect.selectedIndex];
                if (!selectedOption || !selectedOption.value) {
                    return 'Silakan pilih hadiah';
                }

                return selectedOption.textContent;
            };

            const resetDisplayedBatchState = () => {
                winnerBox?.classList.remove('is-batch');
                winnerBox?.classList.remove('is-grandprize');
                winnerModalCard?.classList.remove('is-batch');
                winnerModalCard?.classList.remove('is-grandprize');
                batchResults?.classList.remove('is-visible');
                modalBatchResults?.classList.remove('is-visible');
            };

            const renderParticipant = (participant) => {
                resetDisplayedBatchState();
                currentDrawParticipant = participant;
                if (selectedParticipantInput) {
                    selectedParticipantInput.value = participant.id || '';
                }
                winnerName.textContent = participant.nama;
                winnerPrize.textContent = currentHadiahLabel();
                winnerMeta.textContent = participant.pn + ' | ' + participant.jabatan + ' | ' + participant.uker;
            };

            const clearPendingParticipant = () => {
                currentDrawParticipant = null;
                if (selectedParticipantInput) {
                    selectedParticipantInput.value = '';
                }
            };

            const openPendingWinnerModal = () => {
                if (!currentDrawParticipant) {
                    return;
                }

                winnerModalName.textContent = currentDrawParticipant.nama;
                winnerModalPrize.textContent = currentHadiahLabel();
                winnerModalMeta.textContent = isBatchSelection()
                    ? `${currentDrawParticipant.pn} | ${currentDrawParticipant.jabatan} | ${currentDrawParticipant.uker} | Sistem akan mengundi seluruh item kategori ${currentBatchCategoryLabel()}`
                    : `${currentDrawParticipant.pn} | ${currentDrawParticipant.jabatan} | ${currentDrawParticipant.uker}`;
                openWinnerModal();
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
                syncStartButtonState();
            };

            const syncStartButtonState = () => {
                if (spinTimer) {
                    return;
                }

                startButton.disabled = !isBatchSelection() && !hasSelectedHadiah();
            };

            startButton.addEventListener('click', () => {
                if (!pool.length) {
                    winnerName.textContent = 'Peserta Habis';
                    winnerPrize.textContent = currentHadiahLabel();
                    winnerMeta.textContent = 'Tidak ada peserta aktif yang bisa diundi';
                    return;
                }

                if (!isBatchSelection() && hadiahSelect.options.length <= 1) {
                    winnerName.textContent = 'Hadiah Habis';
                    winnerPrize.textContent = 'Tidak ada hadiah aktif';
                    winnerMeta.textContent = 'Tidak ada hadiah aktif yang bisa diundi';
                    return;
                }

                resetDisplayedBatchState();
                clearPendingParticipant();
                isServerResultModal = false;
                startButton.disabled = true;
                stopButton.classList.remove('is-disabled');

                renderParticipant(randomParticipant());
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

                event.preventDefault();
                setIdleState();
                openPendingWinnerModal();
                celebrateWinner();
            });

            form.addEventListener('submit', () => {
                setIdleState();
            });

            hadiahSearch.addEventListener('input', (event) => {
                renderHadiahOptions(event.target.value, hadiahCategory.value);
                syncStartButtonState();
            });

            hadiahCategory.addEventListener('change', (event) => {
                renderHadiahOptions(hadiahSearch.value, event.target.value);
                if (!spinTimer) {
                    winnerPrize.textContent = currentHadiahLabel();
                    winnerMeta.textContent = isBatchSelection()
                        ? `Sistem akan mengundi seluruh item kategori ${currentBatchCategoryLabel()} sekaligus`
                        : 'Peserta siap diundi';
                }
                syncStartButtonState();
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
                    winnerMeta.textContent = hasSelectedHadiah() || isBatchSelection()
                        ? 'Peserta siap diundi'
                        : 'Silakan pilih hadiah terlebih dahulu';
                }

                syncStartButtonState();
            });

            renderHadiahOptions(hadiahSearch.value, hadiahCategory.value);
            if (isInitialBatchWinner) {
                winnerBox?.classList.add('is-batch');
                winnerModalCard?.classList.add('is-batch');
                batchResults?.classList.add('is-visible');
                modalBatchResults?.classList.add('is-visible');
            }
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

            winnerModalApprove?.addEventListener('click', () => {
                if (isServerResultModal) {
                    closeWinnerModal();
                    return;
                }

                if (!currentDrawParticipant || isSavingWinner) {
                    return;
                }

                isSavingWinner = true;
                winnerModalApprove.disabled = true;
                winnerModalReject.disabled = true;
                winnerModalApprove.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Menyimpan...';

                if (form && typeof form.reportValidity === 'function' && !form.reportValidity()) {
                    isSavingWinner = false;
                    winnerModalApprove.disabled = false;
                    winnerModalReject.disabled = false;
                    winnerModalApprove.innerHTML = '<i class="fa-solid fa-check"></i> Sah / Setujui';
                    return;
                }

                closeWinnerModal();
                if (typeof form.requestSubmit === 'function') {
                    form.requestSubmit();
                } else {
                    HTMLFormElement.prototype.submit.call(form);
                }
            });

            winnerModalReject?.addEventListener('click', () => {
                if (!currentWinnerIds || currentWinnerIds.length === 0) {
                    closeWinnerModal();
                    clearPendingParticipant();
                    resetWinnerDisplay();
                    syncStartButtonState();
                    return;
                }

                Swal.fire({
                    title: 'Gugurkan Pemenang?',
                    text: 'Apakah Anda yakin ingin membatalkan pemenang ini? Hadiah akan dikembalikan ke stok.',
                    icon: 'warning',
                    iconColor: '#ff4a72',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Gugurkan!',
                    cancelButtonText: 'Batal',
                    background: '#083b82',
                    color: '#f7f7f9'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch('{{ route("bcf.undian.reject") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                ids: currentWinnerIds
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: 'Pemenang telah digugurkan.',
                                    background: '#083b82',
                                    color: '#f7f7f9',
                                    confirmButtonColor: '#28a745'
                                }).then(() => {
                                    window.location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal',
                                    text: data.message || 'Terjadi kesalahan saat menggugurkan pemenang.',
                                    background: '#083b82',
                                    color: '#f7f7f9'
                                });
                            }
                        })
                        .catch(err => {
                            console.error(err);
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: 'Koneksi gagal atau terjadi kesalahan server.',
                                background: '#083b82',
                                color: '#f7f7f9'
                            });
                        });
                    }
                });
            });
        })();
    </script>
@endpush
