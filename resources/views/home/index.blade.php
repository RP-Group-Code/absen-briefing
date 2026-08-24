@extends('layouts.app-general')

@section('title', 'Portal SWJ-Tech')

@push('styles')
<style>
    /* ── Portal Hero ── */
    .portal-hero {
        min-height: calc(100vh - 250px);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 3rem 1rem;
        position: relative;
    }

    /* ── Logo area ── */
    .portal-logo-wrap {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 1rem;
        margin-bottom: 3rem;
        animation: fadeDown 0.7s cubic-bezier(.22,.68,0,1.2) both;
    }

    .portal-logo-wrap img {
        width: 380px;
        filter: drop-shadow(0 8px 32px rgba(99,102,241,0.45));
    }

    .portal-logo-title {
        font-size: 1.8rem;
        font-weight: 800;
        color: #fff;
        letter-spacing: 0.04em;
        text-align: center;
    }

    .portal-logo-subtitle {
        font-size: 0.95rem;
        color: rgba(255,255,255,0.5);
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    /* ── Menu grid ── */
    .portal-menu-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 2rem;
        width: 100%;
        max-width: 1080px;
    }

    /* ── Menu card ── */
    .portal-card {
        background: rgba(255,255,255,0.06);
        backdrop-filter: blur(24px) saturate(180%);
        -webkit-backdrop-filter: blur(24px) saturate(180%);
        border: 1px solid rgba(255,255,255,0.13);
        border-radius: 24px;
        padding: 2.5rem 2rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 1.25rem;
        cursor: pointer;
        text-decoration: none;
        transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
        position: relative;
        overflow: hidden;
        animation: fadeUp 0.6s cubic-bezier(.22,.68,0,1.2) both;
    }

    .portal-card:nth-child(1) { animation-delay: 0.15s; }
    .portal-card:nth-child(2) { animation-delay: 0.28s; }
    .portal-card:nth-child(3) { animation-delay: 0.41s; }

    .portal-card::before {
        content: '';
        position: absolute;
        inset: 0;
        background: var(--card-glow);
        opacity: 0;
        transition: opacity 0.3s ease;
        border-radius: 24px;
    }

    .portal-card:hover::before { opacity: 1; }

    .portal-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 60px rgba(0,0,0,0.4), var(--card-shadow);
        border-color: var(--card-border);
    }

    /* BCF card */
    .portal-card.card-bcf {
        --card-glow: linear-gradient(135deg, rgba(99,102,241,0.12), rgba(168,85,247,0.12));
        --card-shadow: 0 0 40px rgba(99,102,241,0.25);
        --card-border: rgba(99,102,241,0.5);
    }

    /* Briefing card */
    .portal-card.card-briefing {
        --card-glow: linear-gradient(135deg, rgba(16,185,129,0.12), rgba(20,184,166,0.12));
        --card-shadow: 0 0 40px rgba(16,185,129,0.25);
        --card-border: rgba(16,185,129,0.5);
    }

    /* EyeForce card */
    .portal-card.card-eyeforce {
        --card-glow: linear-gradient(135deg, rgba(14,165,233,0.14), rgba(59,130,246,0.12));
        --card-shadow: 0 0 40px rgba(14,165,233,0.28);
        --card-border: rgba(56,189,248,0.55);
    }

    /* ── Card icon ── */
    .portal-card-icon {
        width: 72px;
        height: 72px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        color: #fff;
        flex-shrink: 0;
    }

    .card-bcf .portal-card-icon {
        background: linear-gradient(135deg, rgba(99,102,241,0.8), rgba(168,85,247,0.8));
        box-shadow: 0 8px 24px rgba(99,102,241,0.4);
    }

    .card-briefing .portal-card-icon {
        background: linear-gradient(135deg, rgba(16,185,129,0.8), rgba(20,184,166,0.8));
        box-shadow: 0 8px 24px rgba(16,185,129,0.4);
    }

    .card-eyeforce .portal-card-icon {
        background: linear-gradient(135deg, rgba(14,165,233,0.85), rgba(37,99,235,0.85));
        box-shadow: 0 8px 24px rgba(14,165,233,0.42);
    }

    /* ── Card text ── */
    .portal-card-title {
        font-size: 1.15rem;
        font-weight: 700;
        color: #fff;
        text-align: center;
        line-height: 1.3;
    }

    .portal-card-desc {
        font-size: 0.82rem;
        color: rgba(255,255,255,0.5);
        text-align: center;
        line-height: 1.6;
    }

    .portal-card-arrow {
        font-size: 0.8rem;
        color: rgba(255,255,255,0.35);
        display: flex;
        align-items: center;
        gap: 4px;
        transition: color 0.2s, gap 0.2s;
    }

    .portal-card:hover .portal-card-arrow {
        color: rgba(255,255,255,0.8);
        gap: 8px;
    }

    /* ── Divider badge ── */
    .portal-divider {
        display: flex;
        align-items: center;
        gap: 1rem;
        color: rgba(255,255,255,0.3);
        font-size: 0.8rem;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        width: 100%;
        max-width: 720px;
        margin-bottom: 1.5rem;
    }

    .portal-divider::before,
    .portal-divider::after {
        content: '';
        flex: 1;
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.15), transparent);
    }

    /* ── Animations ── */
    @keyframes fadeDown {
        from { opacity: 0; transform: translateY(-20px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(30px); }
        to   { opacity: 1; transform: translateY(0); }
    }
</style>
@endpush

@section('content')
<div class="portal-hero">

    {{-- Logo & judul --}}
    <div class="portal-logo-wrap">
        <img src="{{ asset('images/LogoPPOIT.png') }}" alt="Logo PPOIT">
        <div>
            <div class="portal-logo-title">Portal SWJ-Tech</div>
            <div class="portal-logo-subtitle">BO Sriwijaya — Sustanable Force</div>
        </div> 
    </div>

    {{-- Divider --}}
    <div class="portal-divider">Pilih Sistem</div>

    {{-- Menu grid --}}
    <div class="portal-menu-grid">

        {{-- SISTEM BCF 2026 --}}
        <a href="{{ route('portal.bcf') }}" class="portal-card card-bcf" target="__blank">
            <div class="portal-card-icon">
                <i class="bi bi-file-earmark-text-fill"></i>
            </div>
            <div>
                <div class="portal-card-title">SISTEM BCF 2026</div>
                <div class="portal-card-desc mt-1">
                    Kelola registrasi BCF, admin,<br>dan undian doorprize
                </div>
            </div>
            <span class="portal-card-arrow">
                Masuk <i class="bi bi-arrow-right"></i>
            </span>
        </a>

        {{-- BRIEFING KANCA-UKER --}}
        <a href="{{ route('portal.briefing') }}" class="portal-card card-briefing" target="__blank">
            <div class="portal-card-icon">
                <i class="bi bi-journal-check"></i>
            </div>
            <div>
                <div class="portal-card-title">BRIEFING KANCA-UKER</div>
                <div class="portal-card-desc mt-1">
                    Data absen briefing Kanca<br>dan Unit Kerja
                </div>
            </div>
            <span class="portal-card-arrow">
                Masuk <i class="bi bi-arrow-right"></i>
            </span>
        </a>

        {{-- EYEFORCE E-CHANNEL --}}
        <a href="{{ route('portal.eyeforce') }}" class="portal-card card-eyeforce" target="_blank">
            <div class="portal-card-icon">
                <i class="bi bi-eye-fill"></i>
            </div>
            <div>
                <div class="portal-card-title">EyeForce E-Channel</div>
                <div class="portal-card-desc mt-1">
                    Portal monitoring dan layanan<br>EyeForce E-Channel
                </div>
            </div>
            <span class="portal-card-arrow">
                Masuk <i class="bi bi-arrow-right"></i>
            </span>
        </a>

    </div>
</div>
@endsection
