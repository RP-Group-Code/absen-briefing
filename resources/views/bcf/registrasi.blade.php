@extends('layouts.app-public')

@section('title', 'BCF Registrasi')
@section('description', 'Registrasi peserta BCF Branch Office Palembang Sriwijaya.')

@php
    $colorHexMap = [
        'Ungu' => '#8b5cf6',
        'Hitam' => '#172033',
        'Biru Tua' => '#1649a4',
        'Biru Muda' => '#55c7ed',
        'Putih' => '#ffffff',
        'Kuning' => '#f3c94b',
        'Merah' => '#ef6b63',
        'Hijau' => '#5bbf91',
        'Orange' => '#f19a55',
    ];
@endphp

@push('styles')
    <style>
        :root {
            --bcf-blue: #075bc7;
            --bcf-blue-deep: #064497;
            --bcf-cyan: #69c9eb;
            --bcf-ink: #14233b;
            --bcf-muted: #6e7b91;
            --bcf-soft: #f3f7fc;
        }

        html { scroll-behavior: smooth; }
        body {
            background: var(--bcf-soft) !important;
            color: var(--bcf-ink);
            padding-bottom: 0 !important;
        }
        body::before, body::after { display: none !important; }
        .bcf-page { overflow: hidden; background: var(--bcf-soft); }

        .bcf-hero {
            min-height: 100svh;
            position: relative;
            display: flex;
            align-items: center;
            isolation: isolate;
            background: var(--bcf-blue);
            background-image: linear-gradient(90deg, rgba(3, 61, 143, .9), rgba(7, 91, 199, .82)), url('{{ asset('images/bcf-hero.png') }}');
            background-size: cover;
            background-position: center;
        }
        .bcf-hero::after {
            content: '';
            position: absolute;
            inset: 0;
            z-index: -1;
            background: linear-gradient(180deg, rgba(2, 31, 86, .22), transparent 45%, rgba(2, 28, 83, .38));
        }
        .bcf-ambient-canvas { position: absolute; inset: 0; z-index: 0; width: 100%; height: 100%; opacity: .68; pointer-events: none; }
        .bcf-brand, .bcf-hero-inner, .bcf-scroll { z-index: 1; }
        .bcf-brand, .bcf-hero-inner, .bcf-scroll { position: relative; }
        .bcf-brand { position: absolute; }
        .bcf-hero-inner { width: min(1120px, calc(100% - 40px)); margin: auto; text-align: center; padding: 112px 0 92px; }
        .bcf-brand { position: absolute; top: 28px; left: max(28px, 5%); color: #fff; font-weight: 800; letter-spacing: .04em; text-transform: uppercase; font-size: 1.15rem; text-align: left; }
        .bcf-brand span { display: block; color: var(--bcf-cyan); font-size: .76rem; letter-spacing: .18em; margin-top: 10px; }
        .bcf-hero-copy { max-width: 650px; margin: 0 auto; color: #fff; }
        .bcf-kicker { display: inline-flex; color: rgba(255, 215, 0, .95); font-weight: 800; font-size: clamp(.95rem, 2vw, 1.35rem); letter-spacing: .08em; text-transform: uppercase; }
        .bcf-hero-logo { display: block; width: min(560px, 84vw); max-height: 390px; object-fit: contain; margin: 30px auto 18px; filter: drop-shadow(0 12px 18px rgba(0, 27, 83, .24)); }
        .bcf-hero h1 { font-size: clamp(3.2rem, 9vw, 7rem); line-height: .9; letter-spacing: -.08em; margin: 24px 0 18px; font-weight: 800; }
        .bcf-hero h1 em { color: var(--bcf-cyan); font-style: normal; }
        .bcf-hero p { max-width: 650px; margin: 0 auto; font-size: clamp(.85rem, 2vw, 1rem); color: rgba(255, 215, 0, .95); font-weight: 700; }
        .bcf-hero-slogan { letter-spacing: .01em; }
        .bcf-hero-actions { display: flex; gap: 14px; justify-content: center; flex-wrap: wrap; margin-top: 42px; }
        .bcf-btn { border: 0; border-radius: 12px; min-width: 190px; padding: 15px 22px; font-weight: 800; letter-spacing: .03em; text-decoration: none; transition: transform .2s ease, box-shadow .2s ease, background .2s ease; }
        .bcf-btn:hover { transform: translateY(-3px); box-shadow: 0 12px 26px rgba(0,0,0,.2); }
        .bcf-btn-primary { background: #fff; color: var(--bcf-blue-deep); }
        .bcf-btn-secondary { color: #fff; border: 2px solid rgba(255,255,255,.72); background: rgba(6, 64, 150, .2); }
        .bcf-scroll { position: absolute; bottom: 24px; left: 50%; display: flex; flex-direction: column; align-items: center; gap: 6px; transform: translateX(-50%); color: rgba(255,255,255,.72); font-size: .75rem; letter-spacing: .13em; text-transform: uppercase; text-align: center; }

        .bcf-content { width: min(980px, calc(100% - 32px)); margin: 0 auto; padding: 70px 0 90px; }
        .bcf-section { scroll-margin-top: 24px; margin-bottom: 34px; }
        .bcf-card { background: #fff; border: 1px solid #e1e9f3; border-radius: 18px; box-shadow: 0 12px 35px rgba(18, 59, 108, .07); overflow: hidden; }
        .bcf-card-head { border-top: 5px solid var(--bcf-blue); padding: 26px 28px 20px; }
        .bcf-card-head h2 { margin: 0 0 7px; font-size: 1.45rem; font-weight: 800; }
        .bcf-card-head p { margin: 0; color: var(--bcf-muted); }
        .bcf-form-body { padding: 0 28px 28px; }
        .bcf-label { font-weight: 700; font-size: .86rem; margin-bottom: 8px; }
        .bcf-required { color: #dc5c55; }
        .bcf-input, .bcf-select { min-height: 48px; border-radius: 10px; border: 1px solid #d8e2ee; padding: 10px 13px; color: var(--bcf-ink); box-shadow: none !important; }
        .bcf-input:focus, .bcf-select:focus { border-color: var(--bcf-cyan); outline: 3px solid rgba(105, 201, 235, .18); }
        .bcf-picker .select2-container { width: 100% !important; }
        .bcf-picker .select2-container .select2-selection--single { height: 48px !important; background: #fff !important; border: 1px solid #d8e2ee !important; border-radius: 10px !important; padding: 9px 13px !important; }
        .bcf-picker .select2-container .select2-selection__rendered { color: var(--bcf-ink) !important; line-height: 28px !important; padding-left: 0 !important; }
        .bcf-picker .select2-container .select2-selection__placeholder { color: #71819a !important; }
        .bcf-picker .select2-container .select2-selection__arrow { height: 46px !important; right: 10px !important; }
        .bcf-picker .select2-container .select2-selection__arrow b { border-color: #71819a transparent transparent !important; }
        .bcf-picker .select2-container--open .select2-selection--single { border-color: var(--bcf-cyan) !important; }
        .bcf-picker .select2-dropdown { border-color: #d8e2ee !important; box-shadow: 0 12px 24px rgba(18, 59, 108, .12) !important; }
        .bcf-picker .select2-search--dropdown::before { content: 'Ketik nama atau PN peserta'; display: block; color: #71819a; font-size: .76rem; font-weight: 700; margin: 0 0 6px 2px; }
        .bcf-picker .select2-search--dropdown .select2-search__field { background: #fff !important; color: var(--bcf-ink) !important; border: 1px solid #cdd9e8 !important; }
        .bcf-picker .select2-search--dropdown .select2-search__field::placeholder { color: #8a98ab !important; }
        .bcf-picker .select2-results__option { padding: 10px 13px; }
        .bcf-picker-mode { display: flex; justify-content: flex-end; margin-top: 10px; }
        .bcf-manual-toggle { display: inline-flex; align-items: center; border: 1px solid #8dd5ed; border-radius: 8px; background: #fff; color: var(--bcf-blue-deep); font-size: .76rem; font-weight: 800; line-height: 1; padding: 8px 11px; transition: background .2s ease, border-color .2s ease, transform .2s ease; }
        .bcf-manual-toggle:hover { border-color: var(--bcf-blue); background: #edf8fd; transform: translateY(-1px); }
        .bcf-selection-feedback { display: flex; align-items: center; gap: 10px; background: #fff; border: 1px solid #b9e4f1; border-radius: 10px; padding: 11px 13px; margin-top: 13px; color: var(--bcf-blue-deep); font-size: .83rem; }
        .bcf-selection-feedback[hidden] { display: none; }
        .bcf-selection-feedback i { color: #28a879; font-size: 1.1rem; }
        .bcf-picker { background: #edf8fd; border: 1px dashed #8dd5ed; border-radius: 12px; padding: 15px; margin-bottom: 22px; }
        .bcf-picker label { color: var(--bcf-blue-deep); }
        .bcf-help { color: var(--bcf-muted); font-size: .78rem; }
        .bcf-submit { background: var(--bcf-blue); color: #fff; border: 0; border-radius: 10px; padding: 13px 22px; font-weight: 800; }
        .bcf-submit:hover { background: var(--bcf-blue-deep); }
        .bcf-stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; margin-bottom: 34px; }
        .bcf-stat { background: #fff; border: 1px solid #e1e9f3; border-radius: 14px; padding: 18px; }
        .bcf-stat strong { display: block; font-size: 1.55rem; color: var(--bcf-blue); }
        .bcf-stat span { color: var(--bcf-muted); font-size: .82rem; }
        .bcf-list { display: grid; gap: 12px; padding: 0 28px 28px; }
        .bcf-row { display: flex; align-items: center; justify-content: space-between; gap: 18px; border: 1px solid #e3ebf4; border-radius: 13px; padding: 16px; }
        .bcf-person { min-width: 0; }
        .bcf-person strong { display: block; font-size: 1rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .bcf-person small { color: var(--bcf-muted); }
        .bcf-meta { display: flex; align-items: center; gap: 16px; color: var(--bcf-muted); font-size: .82rem; }
        .bcf-number { display: inline-grid; place-items: center; width: 34px; height: 34px; border-radius: 10px; background: #eaf3ff; color: var(--bcf-blue-deep); font-weight: 800; }
        .bcf-color { display: inline-flex; align-items: center; gap: 7px; }
        .bcf-dot { width: 13px; height: 13px; border-radius: 50%; display: inline-block; border: 1px solid #d1d9e4; }
        .bcf-actions { display: flex; gap: 6px; flex-shrink: 0; }
        .bcf-action { width: 36px; height: 36px; border-radius: 9px; border: 1px solid #dce6f0; background: #fff; color: var(--bcf-blue); }
        .bcf-action.delete { color: #d85c59; }
        .bcf-empty { color: var(--bcf-muted); text-align: center; padding: 30px 20px; }
        .bcf-table-wrap { padding: 0 28px 28px; overflow-x: auto; }
        .bcf-table { width: 100%; border-collapse: separate; border-spacing: 0; color: var(--bcf-ink); font-size: .82rem; }
        .bcf-table th { background: #f4f8fc; color: var(--bcf-muted); font-size: .72rem; font-weight: 800; letter-spacing: .06em; padding: 12px 14px; text-transform: uppercase; white-space: nowrap; }
        .bcf-table th:first-child { border-radius: 10px 0 0 10px; }
        .bcf-table th:last-child { border-radius: 0 10px 10px 0; }
        .bcf-table td { border-bottom: 1px solid #e7eef6; padding: 11px 12px; vertical-align: middle; }
        .bcf-table tbody tr:last-child td { border-bottom: 0; }
        .bcf-table tbody tr:hover { background: #f8fbfe; }
        .bcf-table .bcf-table-name { font-weight: 800; }
        .bcf-table .bcf-table-uker { color: var(--bcf-muted); max-width: 280px; }
        .bcf-table .bcf-table-name, .bcf-table .bcf-table-uker { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .bcf-table-team { font-weight: 700; color: var(--bcf-blue-deep); }
        .bcf-table-color { display: inline-flex; align-items: center; gap: 7px; white-space: nowrap; }
        .bcf-table-number { display: inline-grid; min-width: 30px; height: 28px; place-items: center; border-radius: 8px; background: #eaf3ff; color: var(--bcf-blue-deep); font-weight: 800; }
        .bcf-table-toolbar { display: flex; justify-content: flex-end; margin-bottom: 14px; }
        .bcf-table-search { position: relative; width: min(360px, 100%); }
        .bcf-table-search i { position: absolute; top: 50%; left: 13px; color: var(--bcf-muted); transform: translateY(-50%); pointer-events: none; }
        .bcf-table-search input { width: 100%; height: 42px; border: 1px solid #d8e2ee; border-radius: 10px; padding: 8px 13px 8px 38px; color: var(--bcf-ink); outline: none; }
        .bcf-table-search input:focus { border-color: var(--bcf-cyan); box-shadow: 0 0 0 3px rgba(105, 201, 235, .18); }
        .bcf-table-search input::placeholder { color: #8996a8; }
        .bcf-table-empty-search { color: var(--bcf-muted); font-size: .84rem; padding: 18px; text-align: center; }
        .bcf-table-empty-search[hidden] { display: none; }
        .bcf-modal .modal-content { border: 0; border-radius: 18px; }
        .bcf-modal .modal-header { background: var(--bcf-blue); color: #fff; border: 0; }
        .bcf-modal .btn-close { filter: brightness(0) invert(1); }
        .bcf-assignment-modal .modal-content { border: 0; border-radius: 20px; overflow: hidden; box-shadow: 0 24px 70px rgba(8, 45, 103, .25); }
        .bcf-assignment-modal .modal-header { background: var(--bcf-blue); color: #fff; border: 0; padding: 24px 26px; }
        .bcf-assignment-modal .modal-body { padding: 26px; }
        .bcf-assignment-modal .modal-footer { border: 0; padding: 0 26px 26px; }
        .bcf-assignment-modal .assignment-lead { color: var(--bcf-muted); margin-bottom: 20px; }
        .bcf-assignment-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; }
        .bcf-assignment-item { background: var(--bcf-soft); border: 1px solid #e0e9f4; border-radius: 13px; padding: 15px; }
        .bcf-assignment-item small { display: block; color: var(--bcf-muted); font-size: .73rem; text-transform: uppercase; letter-spacing: .08em; margin-bottom: 5px; }
        .bcf-assignment-item strong { color: var(--bcf-blue-deep); font-size: 1.05rem; }
        .bcf-assignment-note { background: #edf8fd; color: var(--bcf-blue-deep); border-radius: 10px; padding: 12px 14px; font-size: .82rem; margin-top: 15px; }
        @media (max-width: 700px) {
            .bcf-brand { top: 24px; left: 20px; font-size: 1rem; }
            .bcf-brand span { font-size: .62rem; letter-spacing: .12em; margin-top: 7px; }
            .bcf-hero-inner { width: min(100% - 28px, 540px); }
            .bcf-hero-inner { padding-top: 132px; padding-bottom: 112px; }
            .bcf-hero-logo { width: min(430px, 88vw); max-height: 270px; margin-top: 25px; }
            .bcf-hero-actions { flex-direction: column; align-items: center; gap: 12px; margin-top: 36px; }
            .bcf-btn { width: min(390px, 86vw); }
            .bcf-scroll { bottom: 16px; }
            .bcf-hero h1 { font-size: clamp(3rem, 18vw, 5rem); }
            .bcf-stats { grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 0; padding: 8px 4px; background: #fff; border: 1px solid #e1e9f3; border-radius: 14px; box-shadow: 0 8px 22px rgba(18, 59, 108, .06); }
            .bcf-stat { min-width: 0; border: 0; border-right: 1px solid #e6edf5; border-radius: 0; box-shadow: none; padding: 10px 6px; text-align: center; }
            .bcf-stat:last-child { border-right: 0; }
            .bcf-stat strong { font-size: 1.25rem; }
            .bcf-stat span { display: block; font-size: .68rem; line-height: 1.2; }
            .bcf-row { align-items: flex-start; flex-wrap: wrap; }
            .bcf-meta { width: 100%; flex-wrap: wrap; gap: 9px 14px; }
            .bcf-actions { margin-left: auto; }
            .bcf-card-head, .bcf-form-body { padding-left: 18px; padding-right: 18px; }
            .bcf-section#peserta .bcf-card-head { padding-top: 18px; padding-bottom: 16px; }
            .bcf-section#peserta .bcf-card-head h2 { font-size: 1.2rem; margin-bottom: 4px; }
            .bcf-section#peserta .bcf-card-head p { font-size: .74rem; }
            .bcf-table-wrap { padding: 0 8px 10px; }
            .bcf-table-toolbar { margin-bottom: 8px; }
            .bcf-table-search input { height: 36px; font-size: .72rem; padding-left: 34px; }
            .bcf-table-search i { left: 11px; }
            .bcf-table { min-width: 560px; table-layout: fixed; font-size: .6rem; }
            .bcf-table th { font-size: .53rem; padding: 7px 4px; }
            .bcf-table td { padding: 7px 4px; }
            .bcf-table th:nth-child(1), .bcf-table td:nth-child(1) { width: 28%; }
            .bcf-table th:nth-child(2), .bcf-table td:nth-child(2) { width: 20%; }
            .bcf-table th:nth-child(3), .bcf-table td:nth-child(3) { width: 18%; }
            .bcf-table th:nth-child(4), .bcf-table td:nth-child(4) { width: 13%; text-align: center; }
            .bcf-table th:nth-child(5), .bcf-table td:nth-child(5) { width: 21%; }
            .bcf-table .bcf-table-uker { max-width: 0; }
            .bcf-table-color { gap: 3px; }
            .bcf-table .bcf-dot { width: 9px; height: 9px; }
            .bcf-table-number { min-width: 23px; height: 23px; }
            .bcf-assignment-grid { grid-template-columns: 1fr; }
        }
    </style>
@endpush

@section('content')
    <main class="bcf-page">
        <section class="bcf-hero" aria-label="BCF Registration">
            <canvas id="bcfAmbientCanvas" class="bcf-ambient-canvas" aria-hidden="true"></canvas>
            <div class="bcf-brand">BRI <span>Branch Office Palembang Sriwijaya</span></div>
            <div class="bcf-hero-inner">
                <div class="bcf-hero-copy">
                    <span class="bcf-kicker">BCF BO SRIWIJAYA 2026</span>
                    <img class="bcf-hero-logo" src="{{ asset('images/bcf-logo2.png') }}" alt="BRILiaN Culture Fest 2026">
                    <p class="bcf-hero-slogan">“The Golden Dynasty of Sriwijaya: Rise of the Royals”</p>
                    
                    <div class="bcf-hero-actions">
                        <a class="bcf-btn bcf-btn-primary" href="#registrasi"><i class="fa-solid fa-pen-to-square me-2"></i>REGISTRASI</a>
                        <a class="bcf-btn bcf-btn-secondary" href="#peserta"><i class="fa-solid fa-users me-2"></i>CEK DATA PESERTA</a>
                    </div>
                </div>
            </div>
            <div class="bcf-scroll"><i class="fa-solid fa-arrow-down me-2"></i>Scroll untuk melanjutkan</div>
        </section>

        <div class="bcf-content">
            @if (session('success'))
                <div class="alert alert-success border-0 shadow-sm mb-4">{{ session('success') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger border-0 shadow-sm mb-4">
                    <strong>Data belum dapat disimpan.</strong>
                    <ul class="mb-0 mt-1">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
            @endif

            <div class="bcf-stats">
                <div class="bcf-stat"><strong>{{ $registrasi->count() }}</strong><span>Total peserta terdaftar</span></div>
                <div class="bcf-stat"><strong>{{ $registrasi->unique('unit_kerja')->count() }}</strong><span>Unit kerja terwakili</span></div>
                <div class="bcf-stat"><strong>{{ $registrasi->max('nourut') ?? 0 }}</strong><span>Nomor registrasi terakhir</span></div>
            </div>

            <section id="registrasi" class="bcf-section">
                <div class="bcf-card">
                    <div class="bcf-card-head">
                        <h2>Form Registrasi Peserta</h2>
                        <p>Isi data peserta dengan benar. Kolom bertanda <span class="bcf-required">*</span> wajib diisi.</p>
                    </div>
                    <form id="createBcfForm" action="{{ route('bcf.registrasi.store') }}" method="POST" class="bcf-form-body">
                        @csrf
                        <input type="hidden" name="assignment_token" value="{{ $assignmentToken }}">
                        <div class="bcf-picker">
                            <div id="dropdownEntryWrap">
                                <label for="select_pekerja_create" class="bcf-label"><i class="fa-solid fa-magnifying-glass me-2"></i>Cari Nama / PN Peserta <span class="bcf-required">*</span></label>
                                <select id="select_pekerja_create" name="nama" class="form-select bcf-select mt-1" required>
                                    <option value="">-- Pilih Nama Anda --</option>
                                    @foreach ($bcfWorkers as $worker)
                                        @php $isRegistered = in_array($worker['nama'], $registeredNames, true); @endphp
                                        <option value="{{ $worker['nama'] }}" data-pn="{{ $worker['pn'] ?: 'Non PN' }}" data-jabatan="{{ $worker['jabatan'] }}" data-uker="{{ $worker['uker'] }}" data-ukuran="{{ $worker['ukuran'] }}" @disabled($isRegistered)>{{ $worker['nama'] }} — PN: {{ $worker['pn'] ?: 'Non PN' }} — {{ $worker['uker'] }}{{ $isRegistered ? ' (sudah terdaftar)' : '' }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div id="manualEntryWrap" hidden>
                                <label for="create_nama_manual" class="bcf-label"><i class="fa-solid fa-keyboard me-2"></i>Nama Peserta Manual <span class="bcf-required">*</span></label>
                                <input id="create_nama_manual" class="form-control bcf-input mt-1" placeholder="Ketik nama peserta" disabled>
                            </div>
                            <div class="bcf-picker-mode"><button type="button" id="manualEntryToggle" class="bcf-manual-toggle">Isi nama manual</button></div>
                            <div class="bcf-help mt-2">Ketik nama atau PN pada kolom pencarian, lalu klik hasil peserta yang sesuai.</div>
                            <div id="selectedWorkerFeedback" class="bcf-selection-feedback" hidden><i class="fa-solid fa-circle-check"></i><span>Peserta terpilih: <strong id="selectedWorkerName">-</strong><br><span id="selectedWorkerSummary">PN - Jabatan - Unit Kerja</span></span></div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6"><label for="create_jabatan" class="bcf-label">Jabatan</label><input id="create_jabatan" class="form-control bcf-input" readonly placeholder="Terisi setelah nama dipilih"></div>
                            <div class="col-md-6"><label for="create_unit_kerja" class="bcf-label">Unit Kerja</label><input id="create_unit_kerja" class="form-control bcf-input" readonly placeholder="Terisi setelah nama dipilih"></div>
                            <div class="col-md-6"><label for="create_pn" class="bcf-label">PN</label><input id="create_pn" class="form-control bcf-input" readonly placeholder="Terisi setelah nama dipilih"></div>
                            <div class="col-md-6"><label for="create_ukuran" class="bcf-label">Ukuran Baju</label><input id="create_ukuran" class="form-control bcf-input" readonly placeholder="Terisi setelah nama dipilih"></div>
                        </div>
                        <div class="d-flex justify-content-end mt-4"><button type="submit" class="bcf-submit"><i class="fa-solid fa-check me-2"></i>Simpan Registrasi</button></div>
                    </form>
                </div>
            </section>

            <section id="peserta" class="bcf-section">
                <div class="bcf-card p-0 m-0">
                    <div class="bcf-card-head"><h2>Data Peserta</h2><p>Daftar peserta yang sudah melakukan registrasi.</p></div>
                    <div class="bcf-table-wrap">
                        <div class="bcf-table-toolbar">
                            <label class="bcf-table-search" for="participantSearch">
                                <i class="fa-solid fa-magnifying-glass"></i>
                                <input type="search" id="participantSearch" placeholder="Cari nama, team, warna, no urut, atau uker..." autocomplete="off">
                            </label>
                        </div>
                        @forelse ($registrasi as $row)
                            @php $hexColor = $colorHexMap[$row->warna] ?? '#55c7ed'; @endphp
                            @if ($loop->first)
                                <table class="bcf-table">
                                    <thead><tr><th>Nama</th><th>Team</th><th>Warna</th><th>No Urut</th><th>Uker</th>@auth<th>Aksi</th>@endauth</tr></thead>
                                    <tbody id="participantTableBody">
                            @endif
                                        <tr>
                                            <td class="bcf-table-name" title="{{ $row->nama }}">{{ $row->nama }}</td>
                                            <td class="bcf-table-team">{{ $row->team ?: '-' }}</td>
                                            <td><span class="bcf-table-color"><i class="bcf-dot" style="background: {{ $hexColor }}"></i>{{ $row->warna }}</span></td>
                                            <td><span class="bcf-table-number">{{ $row->nourut ?: '-' }}</span></td>
                                            <td class="bcf-table-uker" title="{{ $row->unit_kerja }}">{{ $row->unit_kerja }}</td>
                                            @auth
                                                <td><div class="bcf-actions"><button type="button" class="bcf-action btn-edit-bcf" title="Edit" data-id="{{ $row->id }}" data-nama="{{ $row->nama }}" data-pn="{{ $row->pn }}" data-unit="{{ $row->unit_kerja }}" data-warna="{{ $row->warna }}" data-nourut="{{ $row->nourut }}" data-team="{{ $row->team }}"><i class="fa-solid fa-pen"></i></button><form action="{{ route('bcf.registrasi.destroy', $row->id) }}" method="POST" class="form-delete-bcf" data-nama="{{ $row->nama }}">@csrf @method('DELETE')<button type="submit" class="bcf-action delete" title="Hapus"><i class="fa-solid fa-trash"></i></button></form></div></td>
                                            @endauth
                                        </tr>
                            @if ($loop->last)
                                    </tbody>
                                </table>
                                <div id="participantSearchEmpty" class="bcf-table-empty-search" hidden>Data peserta tidak ditemukan.</div>
                            @endif
                        @empty
                            <div class="bcf-empty"><i class="fa-regular fa-folder-open fa-2x mb-2"></i><br>Belum ada peserta yang terdaftar.</div>
                        @endforelse
                    </div>
                </div>
            </section>
        </div>
    </main>

    <div class="modal fade bcf-assignment-modal" id="assignmentBcfModal" tabindex="-1" aria-labelledby="assignmentBcfModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered"><div class="modal-content"><div class="modal-header"><h5 class="modal-title" id="assignmentBcfModalLabel"><i class="fa-solid fa-circle-check me-2"></i>Konfirmasi Registrasi</h5></div><div class="modal-body"><p class="assignment-lead mb-0">Data team peserta akan disimpan sebagai berikut:</p><div class="bcf-assignment-grid"><div class="bcf-assignment-item"><small>No Urut</small><strong id="assignment_nourut">{{ $nextNoUrut }}</strong></div><div class="bcf-assignment-item"><small>Warna</small><strong id="assignment_warna">{{ $nextTeam['warna'] ?? '-' }}</strong></div><div class="bcf-assignment-item"><small>Team</small><strong id="assignment_team">{{ $nextTeam['team'] ?? 'Kuota penuh' }}</strong></div></div><div class="bcf-assignment-note"><i class="fa-solid fa-user-tie me-2"></i>Penanggung jawab: <strong id="assignment_pic">{{ $nextTeam['penanggung_jawab'] ?? '-' }}</strong></div></div><div class="modal-footer"><button type="button" id="confirmAssignmentButton" class="bcf-submit"><i class="fa-solid fa-check me-2"></i>Konfirmasi &amp; Simpan</button></div></div></div>
    </div>

    @if (session('bcf_assignment'))
        @php($savedAssignment = session('bcf_assignment'))
        <div class="modal fade bcf-assignment-modal" id="registrationResultModal" tabindex="-1" aria-labelledby="registrationResultModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered"><div class="modal-content"><div class="modal-header"><h5 class="modal-title" id="registrationResultModalLabel"><i class="fa-solid fa-star me-2"></i>Registrasi Berhasil</h5></div><div class="modal-body"><p class="assignment-lead mb-1">{{ $savedAssignment['nama'] }}</p><p class="assignment-lead mb-3">Team random yang Anda dapatkan:</p><div class="bcf-assignment-grid"><div class="bcf-assignment-item"><small>No Urut</small><strong>{{ $savedAssignment['nourut'] }}</strong></div><div class="bcf-assignment-item"><small>Warna</small><strong>{{ $savedAssignment['warna'] }}</strong></div><div class="bcf-assignment-item"><small>Team</small><strong>{{ $savedAssignment['team'] }}</strong></div></div><div class="bcf-assignment-note"><i class="fa-solid fa-user-tie me-2"></i>Penanggung jawab: <strong>{{ $savedAssignment['penanggung_jawab'] }}</strong></div></div><div class="modal-footer"><button type="button" class="bcf-submit" data-bs-dismiss="modal"><i class="fa-solid fa-check me-2"></i>Tutup</button></div></div></div>
        </div>
    @endif

    <div class="modal fade bcf-modal" id="editBcfModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered"><div class="modal-content"><form id="editBcfForm" method="POST">@csrf @method('PUT')<div class="modal-header"><h5 class="modal-title">Edit Data Peserta</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body"><div class="row g-3"><div class="col-md-6"><label class="bcf-label">Nama Lengkap</label><input id="edit_nama" name="nama" class="form-control bcf-input" required></div><div class="col-md-6"><label class="bcf-label">PN</label><input id="edit_pn" name="pn" class="form-control bcf-input" required></div><div class="col-12"><label class="bcf-label">Unit Kerja</label><select id="edit_unit_kerja" name="unit_kerja" class="form-select bcf-select" required><option value="">-- Pilih Unit Kerja --</option>@foreach ($ukers as $uk) @php $ukerFormatted = $uk->kode_uker ? '( ' . $uk->kode_uker . ' ) - ' . $uk->nama : $uk->nama; @endphp<option value="{{ $ukerFormatted }}">{{ $ukerFormatted }}</option>@endforeach</select></div><div class="col-md-4"><label class="bcf-label">No Urut</label><input id="edit_nourut" type="number" min="1" name="nourut" class="form-control bcf-input"></div><div class="col-md-4"><label class="bcf-label">Team</label><input id="edit_team" name="team" class="form-control bcf-input"></div><div class="col-md-4"><label class="bcf-label">Warna</label><select id="edit_warna" name="warna" class="form-select bcf-select" required>@foreach ($warnaOptions as $w)<option value="{{ $w }}">{{ $w }}</option>@endforeach</select></div></div></div><div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button><button type="submit" class="bcf-submit">Update Data</button></div></form></div></div>
    </div>
@endsection

@push('scripts')
    <script>
        if ('scrollRestoration' in history) {
            history.scrollRestoration = 'manual';
        }

        const resetBcfScroll = function () {
            window.scrollTo({ top: 0, left: 0, behavior: 'auto' });
        };

        resetBcfScroll();
        window.addEventListener('load', resetBcfScroll, { once: true });
        window.addEventListener('pageshow', resetBcfScroll);

        document.addEventListener('DOMContentLoaded', function () {
            const ambientCanvas = document.getElementById('bcfAmbientCanvas');
            const ambientHero = document.querySelector('.bcf-hero');
            const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

            if (ambientCanvas && ambientHero && !reducedMotion) {
                const context = ambientCanvas.getContext('2d');
                let particles = [];
                let ornaments = [];
                let width = 0;
                let height = 0;
                let animationFrame;

                const resizeAmbient = function () {
                    const ratio = Math.min(window.devicePixelRatio || 1, 2);
                    width = ambientHero.clientWidth;
                    height = ambientHero.clientHeight;
                    ambientCanvas.width = width * ratio;
                    ambientCanvas.height = height * ratio;
                    context.setTransform(ratio, 0, 0, ratio, 0, 0);
                    const total = Math.min(110, Math.max(45, Math.floor(width / 13)));
                    particles = Array.from({ length: total }, () => ({
                        x: Math.random() * width,
                        y: Math.random() * height,
                        radius: Math.random() * 2.2 + .7,
                        speedX: (Math.random() - .5) * .28,
                        speedY: (Math.random() - .5) * .2,
                        phase: Math.random() * Math.PI * 2,
                        gold: Math.random() > .48,
                    }));
                    const ornamentTotal = Math.min(20, Math.max(10, Math.floor(width / 55)));
                    ornaments = Array.from({ length: ornamentTotal }, (_, index) => ({
                        x: Math.random() * width,
                        y: Math.random() * height,
                        size: Math.random() * 12 + 7,
                        speedX: (Math.random() - .5) * .22,
                        speedY: (Math.random() - .5) * .14,
                        rotation: Math.random() * Math.PI,
                        phase: Math.random() * Math.PI * 2,
                        kind: index % 3,
                    }));
                };

                const drawAmbient = function (time) {
                    context.clearRect(0, 0, width, height);
                    const pulse = (Math.sin(time * .0007) + 1) / 2;
                    const centerX = width * .5;
                    const centerY = height * .5;

                    context.save();
                    context.translate(centerX, centerY);
                    context.rotate(time * .00006);
                    context.strokeStyle = `rgba(190, 234, 250, ${.2 + pulse * .1})`;
                    context.lineWidth = 1.7;
                    context.setLineDash([5, 9]);
                    context.beginPath();
                    context.ellipse(0, 0, Math.min(width, height) * .27, Math.min(width, height) * .43, 0, 0, Math.PI * 2);
                    context.stroke();
                    context.restore();

                    const glows = [
                        [width * .16, height * .25, 'rgba(105, 201, 235, .2)'],
                        [width * .84, height * .34, 'rgba(255, 215, 0, .16)'],
                        [width * .5, height * .78, 'rgba(105, 201, 235, .16)'],
                        [width * .62, height * .12, 'rgba(255, 215, 0, .1)'],
                    ];
                    glows.forEach(([x, y, color]) => {
                        const gradient = context.createRadialGradient(x, y, 0, x, y, Math.min(width, height) * .2);
                        gradient.addColorStop(0, color);
                        gradient.addColorStop(1, 'rgba(105, 201, 235, 0)');
                        context.fillStyle = gradient;
                        context.beginPath();
                        context.arc(x, y, Math.min(width, height) * .2, 0, Math.PI * 2);
                        context.fill();
                    });

                    ornaments.forEach((ornament, index) => {
                        ornament.x += ornament.speedX;
                        ornament.y += ornament.speedY;
                        ornament.rotation += .0025 + Math.sin(time * .0007 + ornament.phase) * .0008;
                        if (ornament.x < -30) ornament.x = width + 30;
                        if (ornament.x > width + 30) ornament.x = -30;
                        if (ornament.y < -30) ornament.y = height + 30;
                        if (ornament.y > height + 30) ornament.y = -30;

                        const ornamentAlpha = .36 + Math.sin(time * .001 + ornament.phase) * .12;
                        context.save();
                        context.translate(ornament.x, ornament.y);
                        context.rotate(ornament.rotation);
                        context.strokeStyle = `rgba(255, 215, 0, ${ornamentAlpha})`;
                        context.fillStyle = `rgba(255, 215, 0, ${ornamentAlpha * .18})`;
                        context.lineWidth = 1.35;
                        context.beginPath();
                        if (ornament.kind === 0) {
                            context.moveTo(0, -ornament.size);
                            context.lineTo(ornament.size * .58, 0);
                            context.lineTo(0, ornament.size);
                            context.lineTo(-ornament.size * .58, 0);
                            context.closePath();
                        } else if (ornament.kind === 1) {
                            context.arc(0, 0, ornament.size * .62, 0, Math.PI * 2);
                        } else {
                            context.moveTo(0, -ornament.size);
                            context.lineTo(ornament.size * .22, -ornament.size * .22);
                            context.lineTo(ornament.size, 0);
                            context.lineTo(ornament.size * .22, ornament.size * .22);
                            context.lineTo(0, ornament.size);
                            context.lineTo(-ornament.size * .22, ornament.size * .22);
                            context.lineTo(-ornament.size, 0);
                            context.lineTo(-ornament.size * .22, -ornament.size * .22);
                            context.closePath();
                        }
                        context.fill();
                        context.stroke();
                        context.restore();
                    });

                    particles.forEach((particle, index) => {
                        particle.x += particle.speedX;
                        particle.y += particle.speedY;
                        if (particle.x < -10) particle.x = width + 10;
                        if (particle.x > width + 10) particle.x = -10;
                        if (particle.y < -10) particle.y = height + 10;
                        if (particle.y > height + 10) particle.y = -10;

                        const glow = .7 + Math.sin(time * .001 + particle.phase) * .2;
                        context.beginPath();
                        context.fillStyle = particle.gold ? `rgba(255, 215, 0, ${glow})` : `rgba(105, 201, 235, ${glow})`;
                        context.arc(particle.x, particle.y, particle.radius, 0, Math.PI * 2);
                        context.fill();

                        if (particle.gold || index % 11 === 0) {
                            context.save();
                            context.translate(particle.x, particle.y);
                            context.rotate(time * .0005 + particle.phase);
                            context.strokeStyle = particle.gold ? `rgba(255, 231, 112, ${glow * .7})` : `rgba(190, 239, 255, ${glow * .55})`;
                            context.lineWidth = .8;
                            context.beginPath();
                            context.moveTo(-5, 0);
                            context.lineTo(5, 0);
                            context.moveTo(0, -5);
                            context.lineTo(0, 5);
                            context.stroke();
                            context.restore();
                        }

                        particles.slice(index + 1).forEach(other => {
                            const distance = Math.hypot(particle.x - other.x, particle.y - other.y);
                            if (distance < 135) {
                                context.beginPath();
                                context.strokeStyle = `rgba(191, 233, 250, ${.17 * (1 - distance / 135)})`;
                                context.moveTo(particle.x, particle.y);
                                context.lineTo(other.x, other.y);
                                context.stroke();
                            }
                        });
                    });

                    animationFrame = window.requestAnimationFrame(drawAmbient);
                };

                resizeAmbient();
                window.addEventListener('resize', resizeAmbient, { passive: true });
                animationFrame = window.requestAnimationFrame(drawAmbient);
                window.addEventListener('pagehide', () => window.cancelAnimationFrame(animationFrame), { once: true });
            }

            const registrationResultModal = document.getElementById('registrationResultModal');
            if (registrationResultModal) {
                window.setTimeout(() => bootstrap.Modal.getOrCreateInstance(registrationResultModal).show(), 250);
            }

            const picker = document.getElementById('select_pekerja_create');
            if (window.jQuery && jQuery.fn.select2) {
                jQuery(picker).select2({
                    placeholder: '-- Cari nama atau PN peserta --',
                    allowClear: true,
                    width: '100%'
                });
            }

            const syncWorkerDetails = function () {
                const option = picker.options[picker.selectedIndex];
                const feedback = document.getElementById('selectedWorkerFeedback');
                if (!option?.value) {
                    feedback.hidden = true;
                    document.getElementById('create_pn').value = '';
                    document.getElementById('create_jabatan').value = '';
                    document.getElementById('create_unit_kerja').value = '';
                    document.getElementById('create_ukuran').value = '';
                    return;
                }
                document.getElementById('create_pn').value = option.dataset.pn || 'Non PN';
                document.getElementById('create_jabatan').value = option.dataset.jabatan || '-';
                document.getElementById('create_unit_kerja').value = option.dataset.uker || '-';
                document.getElementById('create_ukuran').value = option.dataset.ukuran || '-';
                document.getElementById('selectedWorkerName').textContent = option.textContent.split(' — ')[0];
                document.getElementById('selectedWorkerSummary').textContent = `${option.dataset.pn || 'Non PN'} · ${option.dataset.jabatan || 'Jabatan belum tersedia'} · ${option.dataset.uker || 'Unit kerja belum tersedia'}`;
                feedback.hidden = false;
            };

            if (window.jQuery && jQuery.fn.select2) {
                jQuery(picker).on('change', syncWorkerDetails);
            } else {
                picker?.addEventListener('change', syncWorkerDetails);
            }

            const dropdownEntryWrap = document.getElementById('dropdownEntryWrap');
            const manualEntryWrap = document.getElementById('manualEntryWrap');
            const manualEntryToggle = document.getElementById('manualEntryToggle');
            const manualNameInput = document.getElementById('create_nama_manual');
            let manualMode = false;

            const showManualDetails = function () {
                const value = manualNameInput.value.trim();
                const feedback = document.getElementById('selectedWorkerFeedback');
                document.getElementById('create_pn').value = value ? 'Non PN' : '';
                document.getElementById('create_jabatan').value = value ? 'Input Manual' : '';
                document.getElementById('create_unit_kerja').value = value ? 'Input Manual' : '';
                document.getElementById('create_ukuran').value = value ? '-' : '';
                document.getElementById('selectedWorkerName').textContent = value || '-';
                document.getElementById('selectedWorkerSummary').textContent = 'Non PN · Input Manual';
                feedback.hidden = !value;
            };

            manualEntryToggle?.addEventListener('click', function () {
                manualMode = !manualMode;
                dropdownEntryWrap.hidden = manualMode;
                manualEntryWrap.hidden = !manualMode;
                manualNameInput.disabled = !manualMode;
                manualNameInput.required = manualMode;
                picker.disabled = manualMode;
                picker.required = !manualMode;

                if (manualMode) {
                    picker.removeAttribute('name');
                    manualNameInput.name = 'nama';
                    manualEntryToggle.textContent = 'Kembali ke daftar peserta';
                    if (window.jQuery && jQuery.fn.select2) {
                        jQuery(picker).val(null).trigger('change.select2');
                    } else {
                        picker.value = '';
                    }
                    manualNameInput.focus();
                    showManualDetails();
                } else {
                    manualNameInput.removeAttribute('name');
                    picker.name = 'nama';
                    manualNameInput.value = '';
                    manualEntryToggle.textContent = 'Isi nama manual';
                    document.getElementById('selectedWorkerFeedback').hidden = true;
                    showManualDetails();
                }
            });

            manualNameInput?.addEventListener('input', showManualDetails);

            const createForm = document.getElementById('createBcfForm');
            const assignmentModal = document.getElementById('assignmentBcfModal');
            const confirmAssignmentButton = document.getElementById('confirmAssignmentButton');
            let formReadyToSubmit = false;

            createForm?.addEventListener('submit', function (event) {
                if (formReadyToSubmit) return;
                event.preventDefault();
                if (!this.checkValidity()) {
                    this.reportValidity();
                    return;
                }
                bootstrap.Modal.getOrCreateInstance(assignmentModal).show();
            });

            confirmAssignmentButton?.addEventListener('click', function () {
                formReadyToSubmit = true;
                createForm.submit();
            });

            const participantSearch = document.getElementById('participantSearch');
            const participantRows = document.querySelectorAll('#participantTableBody tr');
            const participantSearchEmpty = document.getElementById('participantSearchEmpty');
            participantSearch?.addEventListener('input', function () {
                const keyword = this.value.trim().toLowerCase();
                let visibleRows = 0;

                participantRows.forEach(row => {
                    const matches = row.textContent.toLowerCase().includes(keyword);
                    row.hidden = !matches;
                    if (matches) visibleRows++;
                });

                if (participantSearchEmpty) {
                    participantSearchEmpty.hidden = visibleRows > 0 || keyword === '';
                }
            });

            document.querySelectorAll('.btn-edit-bcf').forEach(button => button.addEventListener('click', function () {
                const data = this.dataset;
                document.getElementById('editBcfForm').action = `{{ url('/bcf-registrasi') }}/${data.id}`;
                document.getElementById('edit_nama').value = data.nama || '';
                document.getElementById('edit_pn').value = data.pn || '';
                document.getElementById('edit_unit_kerja').value = data.unit || '';
                document.getElementById('edit_warna').value = data.warna || 'Biru Muda';
                document.getElementById('edit_nourut').value = data.nourut || '';
                document.getElementById('edit_team').value = data.team || '';
                bootstrap.Modal.getOrCreateInstance(document.getElementById('editBcfModal')).show();
            }));

            document.querySelectorAll('.form-delete-bcf').forEach(form => form.addEventListener('submit', function (event) {
                event.preventDefault();
                const submit = () => form.submit();
                if (typeof Swal === 'undefined') return confirm(`Hapus registrasi ${form.dataset.nama}?`) && submit();
                Swal.fire({ title: 'Hapus data peserta?', text: `Registrasi ${form.dataset.nama} akan dihapus.`, icon: 'warning', showCancelButton: true, confirmButtonColor: '#d85c59', confirmButtonText: 'Ya, hapus', cancelButtonText: 'Batal' }).then(result => result.isConfirmed && submit());
            }));
        });
    </script>
@endpush
