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
            background-image: linear-gradient(90deg, rgba(4, 51, 122, .55), rgba(5, 86, 190, .08)), url('{{ asset('images/bcf-hero.png') }}');
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
        .bcf-hero-inner { width: min(1120px, calc(100% - 40px)); margin: auto; text-align: center; padding: 48px 0; }
        .bcf-brand { position: absolute; top: 30px; left: 5%; color: #fff; font-weight: 800; letter-spacing: .08em; text-transform: uppercase; font-size: .78rem; }
        .bcf-brand span { display: block; color: var(--bcf-cyan); font-size: .68rem; letter-spacing: .2em; margin-top: 4px; }
        .bcf-hero-copy { max-width: 650px; margin: 0 auto; color: #fff; }
        .bcf-kicker { display: inline-flex; padding: 8px 17px; border-radius: 999px; background: rgba(105, 201, 235, .95); color: var(--bcf-blue-deep); font-weight: 800; font-size: .78rem; letter-spacing: .1em; text-transform: uppercase; }
        .bcf-hero h1 { font-size: clamp(3.2rem, 9vw, 7rem); line-height: .9; letter-spacing: -.08em; margin: 24px 0 18px; font-weight: 800; }
        .bcf-hero h1 em { color: var(--bcf-cyan); font-style: normal; }
        .bcf-hero p { max-width: 470px; margin: 0 auto; font-size: 1.05rem; color: rgba(255,255,255,.84); }
        .bcf-hero-actions { display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; margin-top: 34px; }
        .bcf-btn { border: 0; border-radius: 12px; min-width: 190px; padding: 15px 22px; font-weight: 800; letter-spacing: .03em; text-decoration: none; transition: transform .2s ease, box-shadow .2s ease, background .2s ease; }
        .bcf-btn:hover { transform: translateY(-3px); box-shadow: 0 12px 26px rgba(0,0,0,.2); }
        .bcf-btn-primary { background: #fff; color: var(--bcf-blue-deep); }
        .bcf-btn-secondary { color: #fff; border: 1px solid rgba(255,255,255,.65); background: rgba(6, 64, 150, .34); }
        .bcf-scroll { position: absolute; bottom: 26px; left: 50%; transform: translateX(-50%); color: rgba(255,255,255,.7); font-size: .75rem; letter-spacing: .13em; text-transform: uppercase; }

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
            .bcf-brand { top: 20px; left: 20px; }
            .bcf-hero-inner { width: min(100% - 28px, 540px); }
            .bcf-hero h1 { font-size: clamp(3rem, 18vw, 5rem); }
            .bcf-stats { grid-template-columns: 1fr; }
            .bcf-row { align-items: flex-start; flex-wrap: wrap; }
            .bcf-meta { width: 100%; flex-wrap: wrap; gap: 9px 14px; }
            .bcf-actions { margin-left: auto; }
            .bcf-card-head, .bcf-form-body, .bcf-list { padding-left: 18px; padding-right: 18px; }
            .bcf-assignment-grid { grid-template-columns: 1fr; }
        }
    </style>
@endpush

@section('content')
    <main class="bcf-page">
        <section class="bcf-hero" aria-label="BCF Registration">
            <div class="bcf-brand">BRI <span>Branch Office Palembang Sriwijaya</span></div>
            <div class="bcf-hero-inner">
                <div class="bcf-hero-copy">
                    <span class="bcf-kicker">BCF 2026</span>
                    <h1>BRILiaN<br><em>Culture Fest</em></h1>
                    <p>Registrasi peserta untuk Branch Office Palembang Sriwijaya. Silakan pilih menu yang ingin Anda gunakan.</p>
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
                        <div class="bcf-picker">
                            <label for="select_pekerja_create" class="bcf-label"><i class="fa-solid fa-magnifying-glass me-2"></i>Pilih Nama Peserta <span class="bcf-required">*</span></label>
                            <select id="select_pekerja_create" name="nama" class="form-select bcf-select mt-1" required>
                                <option value="">-- Pilih nama peserta --</option>
                                @foreach ($bcfWorkers as $worker)
                                    @php $isRegistered = in_array($worker['nama'], $registeredNames, true); @endphp
                                    @php $isUnavailable = blank($worker['pn']); @endphp
                                    <option value="{{ $worker['nama'] }}" data-pn="{{ $worker['pn'] ?? '' }}" data-jabatan="{{ $worker['jabatan'] }}" data-uker="{{ $worker['uker'] }}" data-ukuran="{{ $worker['ukuran'] }}" @disabled($isRegistered || $isUnavailable)>{{ $worker['nama'] }} — PN: {{ $worker['pn'] ?: 'belum sinkron' }} — {{ $worker['uker'] }}{{ $isRegistered ? ' (sudah terdaftar)' : '' }}</option>
                                @endforeach
                            </select>
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
                <div class="bcf-card">
                    <div class="bcf-card-head"><h2>Data Peserta</h2><p>Daftar peserta yang sudah melakukan registrasi.</p></div>
                    <div class="bcf-list">
                        @forelse ($registrasi as $row)
                            @php $hexColor = $colorHexMap[$row->warna] ?? '#55c7ed'; @endphp
                            <div class="bcf-row">
                                <div class="bcf-person"><strong>{{ $row->nama }}</strong><small>{{ $row->pn }} · {{ $row->unit_kerja }}</small></div>
                                <div class="bcf-meta"><span class="bcf-number">{{ $row->nourut ?: '-' }}</span><span>{{ $row->team ?: 'Tanpa team' }}</span><span class="bcf-color"><i class="bcf-dot" style="background: {{ $hexColor }}"></i>{{ $row->warna }}</span></div>
                                @auth
                                    <div class="bcf-actions"><button type="button" class="bcf-action btn-edit-bcf" title="Edit" data-id="{{ $row->id }}" data-nama="{{ $row->nama }}" data-pn="{{ $row->pn }}" data-unit="{{ $row->unit_kerja }}" data-warna="{{ $row->warna }}" data-nourut="{{ $row->nourut }}" data-team="{{ $row->team }}"><i class="fa-solid fa-pen"></i></button><form action="{{ route('bcf.registrasi.destroy', $row->id) }}" method="POST" class="form-delete-bcf" data-nama="{{ $row->nama }}">@csrf @method('DELETE')<button type="submit" class="bcf-action delete" title="Hapus"><i class="fa-solid fa-trash"></i></button></form></div>
                                @endauth
                            </div>
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

    <div class="modal fade bcf-modal" id="editBcfModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered"><div class="modal-content"><form id="editBcfForm" method="POST">@csrf @method('PUT')<div class="modal-header"><h5 class="modal-title">Edit Data Peserta</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body"><div class="row g-3"><div class="col-md-6"><label class="bcf-label">Nama Lengkap</label><input id="edit_nama" name="nama" class="form-control bcf-input" required></div><div class="col-md-6"><label class="bcf-label">PN</label><input id="edit_pn" name="pn" class="form-control bcf-input" required></div><div class="col-12"><label class="bcf-label">Unit Kerja</label><select id="edit_unit_kerja" name="unit_kerja" class="form-select bcf-select" required><option value="">-- Pilih Unit Kerja --</option>@foreach ($ukers as $uk) @php $ukerFormatted = $uk->kode_uker ? '( ' . $uk->kode_uker . ' ) - ' . $uk->nama : $uk->nama; @endphp<option value="{{ $ukerFormatted }}">{{ $ukerFormatted }}</option>@endforeach</select></div><div class="col-md-4"><label class="bcf-label">No Urut</label><input id="edit_nourut" type="number" min="1" name="nourut" class="form-control bcf-input"></div><div class="col-md-4"><label class="bcf-label">Team</label><input id="edit_team" name="team" class="form-control bcf-input"></div><div class="col-md-4"><label class="bcf-label">Warna</label><select id="edit_warna" name="warna" class="form-select bcf-select" required>@foreach ($warnaOptions as $w)<option value="{{ $w }}">{{ $w }}</option>@endforeach</select></div></div></div><div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button><button type="submit" class="bcf-submit">Update Data</button></div></form></div></div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
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
                document.getElementById('create_pn').value = option.dataset.pn || 'PN belum sinkron';
                document.getElementById('create_jabatan').value = option.dataset.jabatan || '-';
                document.getElementById('create_unit_kerja').value = option.dataset.uker || '-';
                document.getElementById('create_ukuran').value = option.dataset.ukuran || '-';
                document.getElementById('selectedWorkerName').textContent = option.textContent.split(' — ')[0];
                document.getElementById('selectedWorkerSummary').textContent = `${option.dataset.pn || 'PN belum sinkron'} · ${option.dataset.jabatan || 'Jabatan belum tersedia'} · ${option.dataset.uker || 'Unit kerja belum tersedia'}`;
                feedback.hidden = false;
            };

            if (window.jQuery && jQuery.fn.select2) {
                jQuery(picker).on('change', syncWorkerDetails);
            } else {
                picker?.addEventListener('change', syncWorkerDetails);
            }

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
