@extends('layouts.app-public')

@section('title', 'Absen Briefing Kanca')

@section('content')
    @php
        $selectedId = $selectedRow['id'] ?? null;
        $selectedName = $selectedRow['name'] ?? '(Belum memilih pegawai)';
        $selectedNotes = $selectedRow['notes'] ?? '';
        $selectedStatus = $selectedRow['status'] ?? 'Masuk';
    @endphp

    <div class="px-5 py-3">
        <div class="header-card kanca-header">
            <div class="header-brand">
                <div class="header-icon">
                    <i class="fa-solid fa-users-viewfinder"></i>
                </div>
                <div>
                    <div class="header-title">Absen Briefing Kanca</div>
                    <div class="header-sub">BO Palembang Sriwijaya</div>
                </div>
            </div>

            <div class="row">
                <div class="col-4">
                    <div class="header-pills">
                        <span class="info-pill"><i class="fa-solid fa-users"></i> Total: <strong
                                id="totalEmployeesText">{{ $totalEmployees }}</strong></span>
                        <span class="info-pill"><i class="fa-solid fa-building"></i> Scope:
                            <strong
                                id="scopeText">{{ $activeDivision !== '' ? $activeDivision : 'Semua Divisi' }}</strong></span>
                    </div>
                </div>
                <div class="col-8">
                    <div class="header-notice">
                        <i class="fa-solid fa-circle-info mt-1 flex-shrink-0"></i>
                        <span>Klik salah satu baris pegawai untuk ubah status pada tanggal aktif. - Jangan lupa untuk
                            menyimpan status briefing pegawai !!!</span>
                    </div>
                </div>
            </div>



        </div>

        <div class="glass-card toolbar-card">
            <form method="GET" action="{{ route('Input-Index-Kanca') }}" id="filterForm" class="toolbar-form">
                <input type="hidden" name="selected" id="selected_filter" value="{{ $selectedId }}">

                <div class="control-group control-date">
                    <label for="date" class="mini-label">Tanggal</label>
                    <input type="date" id="date" name="date" value="{{ $activeDate }}" class="ctl ctl-input">
                </div>

                <div class="control-group control-nav">
                    <label class="mini-label">Nav Divisi</label>
                    <div class="nav-wrap">
                        <button type="button" class="ctl ctl-nav" id="divisionPrevBtn" title="Divisi Sebelumnya">
                            <i class="fa-solid fa-chevron-left"></i>
                        </button>
                        <button type="button" class="ctl ctl-nav" id="divisionNextBtn" title="Divisi Berikutnya">
                            <i class="fa-solid fa-chevron-right"></i>
                        </button>
                    </div>
                </div>

                <div class="control-group control-division">
                    <label for="division" class="mini-label">Divisi</label>
                    <select id="division" name="division" class="ctl ctl-input">
                        <option value="">Semua Divisi</option>
                        @foreach ($divisions as $division)
                            <option value="{{ $division }}" @selected($activeDivision === $division)>
                                {{ $division }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="control-group control-search">
                    <label for="search" class="mini-label">Search</label>
                    <div class="search-wrap">
                        <input type="text" id="search" name="search" value="{{ $search }}"
                            placeholder="Nama / Jabatan / Divisi" class="ctl ctl-input">
                        <a href="{{ route('absen.kanca.export', ['date' => $activeDate, 'division' => $activeDivision, 'search' => $search]) }}"
                            id="exportExcelBtn" class="btn-standard btn-export">
                            <i class="fa-solid fa-file-excel"></i>
                            <span>Export Excel</span>
                        </a>
                    </div>
                </div>

                {{-- <div class="control-group control-submit">
                    <label class="mini-label">Aksi</label>
                    <button type="submit" class="btn-standard">Apply Filter</button>
                </div> --}}
            </form>
        </div>

        <div class="kanca-grid">
            <div class="glass-card table-card">
                <div class="table-headline">
                    <span class="left"><i class="fa-solid fa-table-list"></i> Daftar Pegawai</span>
                    <span class="right" id="activeDateText">{{ date('d M Y') }}</span>
                </div>

                <div class="table-scroll">
                    <table class="kanca-table" id="attendanceTable">
                        <thead>
                            <tr>
                                <th>Divisi</th>
                                <th>Jabatan</th>
                                <th>Pekerja</th>
                                <th>Status</th>
                                <th>Indikator</th>
                            </tr>
                        </thead>
                        <tbody class="fw-bold">

                            @forelse ($rows as $row)
                                @php
                                    $isSelected = (int) $row['id'] === (int) $selectedId;
                                @endphp

                                <tr class="text-uppercase employee-row {{ $isSelected ? 'selected' : '' }} "
                                    data-kanca-id="{{ $row['id'] }}" data-name="{{ $row['name'] }}"
                                    data-status="{{ $row['status'] }}" data-notes="{{ $row['notes'] }}">
                                    <td>{{ $row['division'] }}</td>
                                    <td>{{ $row['jabatan'] }}</td>
                                    <td>{{ $row['name'] }}</td>
                                    <td>
                                        <span
                                            class="status-tag status-{{ strtolower($row['status']) }}">{{ $row['status'] }}</span>
                                    </td>
                                    <td>{{ $row['indicator'] }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="empty-state">Data pegawai belum tersedia. Jalankan seeder
                                        `KancaSeeder` dulu.</td>
                                </tr>
                            @endforelse

                        </tbody>
                    </table>
                </div>
            </div>

            <aside class="kanca-side">
                <section class="glass-card side-card">
                    <div class="side-title">Ubah Status Pegawai</div>
                    <div id="selectedPegawai" class="selected-label">{{ $selectedName }}</div>

                    <div class="current-status-wrap">
                        <span class="mini-label">Status Terpilih</span>
                        <span id="selectedStatusBadge" class="status-tag status-{{ strtolower($selectedStatus) }}">
                            {{ $selectedStatus }}
                        </span>
                    </div>

                    <form method="POST" action="{{ route('absen.kanca.status.save') }}" id="statusForm">
                        @csrf
                        <input type="hidden" name="date" id="save_date" value="{{ $activeDate }}">
                        <input type="hidden" name="division" id="save_division" value="{{ $activeDivision }}">
                        <input type="hidden" name="search" id="save_search" value="{{ $search }}">
                        <input type="hidden" name="changes_payload" id="changes_payload" value="">
                        <input type="hidden" name="kanca_id" id="kanca_id" value="{{ $selectedId }}">

                        <div class="radio-list" id="statusRadioList">
                            @foreach ($statuses as $status)
                                <label class="radio-item">
                                    <input type="radio" name="status" value="{{ $status }}"
                                        @checked($selectedStatus === $status)>
                                    <span>{{ $status }}</span>
                                </label>
                            @endforeach
                        </div>

                        <label class="mini-label" for="notes">Catatan</label>
                        <textarea name="notes" id="notes" rows="4" placeholder="Tambahkan catatan bila perlu">{{ $selectedNotes }}</textarea>

                        <button type="submit" class="btn-save" @disabled(!$selectedId)>Simpan Status</button>
                        <div class="pending-note" id="pendingNote">Belum ada perubahan.</div>
                    </form>
                </section>

                <section class="glass-card side-card">
                    <div class="side-title">Rekap Realtime</div>
                    <div class="rekap-body">
                        <div><span>Masuk</span><strong id="sumMasuk">{{ $summary['Masuk'] ?? 0 }}</strong></div>
                        <div><span>Telat</span><strong id="sumTelat">{{ $summary['Telat'] ?? 0 }}</strong></div>
                        <div><span>Sakit</span><strong id="sumSakit">{{ $summary['Sakit'] ?? 0 }}</strong></div>
                        <div><span>Absen</span><strong id="sumAbsen">{{ $summary['Absen'] ?? 0 }}</strong></div>
                        <div><span>Izin</span><strong id="sumIzin">{{ $summary['Izin'] ?? 0 }}</strong></div>
                        <div><span>Cuti</span><strong id="sumCuti">{{ $summary['Cuti'] ?? 0 }}</strong></div>
                    </div>
                </section>

                <div class="kanca-copyright">&copy; itg_swj342_rpg0426_v3</div>
            </aside>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        body.kanca-fit-screen {
            background: #d8dfec !important;
            color: #1f2937;
        }

        body.kanca-fit-screen::before,
        body.kanca-fit-screen::after {
            display: none !important;
        }

        body.kanca-fit-screen .header-card,
        body.kanca-fit-screen .glass-card {
            box-shadow: 0 8px 24px rgba(15, 23, 42, .06);
            border: 1px solid #e5e7eb;
            background: #ffffff;
            border-radius: 14px;
        }

        body.kanca-fit-screen .header-card::before,
        body.kanca-fit-screen .glass-card::before {
            display: none;
        }

        .kanca-page {
            max-width: min(1460px, 100%);
            margin: 0 auto;
            padding: 1rem !important;
        }

        .kanca-header {
            margin-bottom: 1rem;
            padding: 1rem 1.1rem;
        }

        .header-brand {
            margin-bottom: .7rem;
        }

        .header-icon {
            background: #eef2ff;
            color: #4f46e5;
            box-shadow: none;
        }

        .header-title {
            color: #111827;
            font-size: 1.02rem;
        }

        .header-sub {
            color: #6b7280;
        }

        .header-pills {
            display: flex;
            flex-wrap: wrap;
            gap: .5rem;
        }

        .info-pill {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            border-radius: 999px;
            border: 1px solid #d1d5db;
            background: #f9fafb;
            color: #374151;
            font-size: .74rem;
            font-weight: 600;
            padding: .34rem .66rem;
        }

        .header-notice {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            color: #4b5563;
            border-radius: 10px;
            font-size: .8rem;
            padding: .65rem .8rem;
        }

        .toolbar-card {
            margin-bottom: 1rem;
            padding: .9rem;
        }

        .toolbar-form {
            display: grid;
            gap: .7rem;
            grid-template-columns: 180px 104px minmax(220px, 1fr) minmax(340px, 1.5fr);
            align-items: end;
        }

        .control-group {
            display: flex;
            flex-direction: column;
            gap: .34rem;
        }

        .mini-label {
            color: #6b7280;
            font-size: .71rem;
            letter-spacing: .04em;
            text-transform: uppercase;
            font-weight: 700;
        }

        .ctl {
            width: 100%;
            min-height: 41px;
            border-radius: 10px;
            border: 1px solid #d1d5db;
            background: #fff;
            color: #111827;
            padding: .5rem .68rem;
            font-size: .84rem;
            outline: none;
            transition: border-color .15s, box-shadow .15s;
        }

        .ctl:focus {
            border-color: #9ca3af;
            box-shadow: 0 0 0 3px rgba(107, 114, 128, .12);
        }

        .control-nav .nav-wrap {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: .45rem;
        }

        .ctl-nav {
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-standard {
            min-height: 41px;
            border-radius: 10px;
            border: 1px solid #d1d5db;
            background: #f9fafb;
            color: #374151;
            font-size: .82rem;
            font-weight: 700;
            transition: background .15s, border-color .15s;
        }

        .btn-standard:hover {
            background: #f3f4f6;
            border-color: #9ca3af;
        }

        .search-wrap {
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            gap: .5rem;
            align-items: center;
        }

        .btn-export {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: .35rem;
            white-space: nowrap;
            text-decoration: none;
            padding: 0 .95rem;
            border-color: #86efac;
            background: #ecfdf3;
            color: #166534;
        }

        .btn-export:hover {
            background: #dcfce7;
            border-color: #4ade80;
        }

        .kanca-grid {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 330px;
            gap: 1rem;
        }

        .table-card {
            overflow: hidden;
            display: flex;
            flex-direction: column;
            min-height: 0;
        }

        .table-headline {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: .8rem .95rem;
            border-bottom: 1px solid #e5e7eb;
            background: #f9fafb;
            color: #374151;
            font-size: .82rem;
            font-weight: 700;
            flex-shrink: 0;
        }

        .table-headline .left {
            display: inline-flex;
            align-items: center;
            gap: .43rem;
        }

        .table-headline .right {
            color: #6b7280;
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
            font-size: .76rem;
        }

        .table-scroll {
            max-height: calc(100vh - 250px);
            overflow: auto;
        }

        .table-scroll::-webkit-scrollbar,
        .kanca-side::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        .table-scroll::-webkit-scrollbar-thumb,
        .kanca-side::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 999px;
        }

        .kanca-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .kanca-table thead th {
            position: sticky;
            top: 0;
            z-index: 2;
            background: #f9fafb;
            color: #6b7280;
            font-size: .69rem;
            letter-spacing: .06em;
            text-transform: uppercase;
            padding: .68rem .72rem;
            border-bottom: 1px solid #e5e7eb;
        }

        .kanca-table tbody td {
            color: #1f2937;
            font-size: 1rem;
            padding: .62rem .72rem;
            border-bottom: 1px solid #f1f5f9;
            white-space: nowrap;
        }

        .employee-row {
            font-size: 5rem;
            cursor: pointer;
            transition: background .14s ease;
        }

        .employee-row:nth-child(even) {
            background: #fbfcfe;
        }

        .employee-row:hover {
            background: #f3f4f6;
        }

        .employee-row.selected {
            background: #eef2ff;
            box-shadow: inset 3px 0 0 #6366f1;
        }

        .employee-row.pending-local {
            box-shadow: inset 3px 0 0 #f59e0b;
        }

        .status-tag {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 68px;
            padding: .2rem .52rem;
            border-radius: 999px;
            font-size: .71rem;
            font-weight: 700;
            border: 1px solid transparent;
        }

        .status-masuk {
            background: #eafaf2;
            border-color: #bbf7d0;
            color: #166534;
        }

        .status-telat {
            background: #fff8e6;
            border-color: #fde68a;
            color: #92400e;
        }

        .status-sakit {
            background: #eff6ff;
            border-color: #bfdbfe;
            color: #1d4ed8;
        }

        .status-absen {
            background: #fef2f2;
            border-color: #fecaca;
            color: #b91c1c;
        }

        .status-izin {
            background: #eef2ff;
            border-color: #c7d2fe;
            color: #3730a3;
        }

        .status-cuti {
            background: #f5f3ff;
            border-color: #ddd6fe;
            color: #6d28d9;
        }

        .kanca-side {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            min-height: 0;
        }

        .side-card {
            padding: .95rem;
        }

        .side-title {
            color: #111827;
            font-size: .9rem;
            font-weight: 700;
            margin-bottom: .68rem;
        }

        .selected-label {
            border: 1px solid #d1d5db;
            background: #f9fafb;
            border-radius: 10px;
            color: #1f2937;
            padding: .58rem .65rem;
            font-size: .81rem;
            margin-bottom: .7rem;
        }

        .current-status-wrap {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: .5rem;
            margin-bottom: .7rem;
        }

        .radio-list {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: .42rem;
            margin-bottom: .75rem;
        }

        .radio-item {
            border: 1px solid #d1d5db;
            background: #fff;
            border-radius: 10px;
            padding: .42rem .52rem;
            display: flex;
            align-items: center;
            gap: .42rem;
            color: #374151;
            font-size: .78rem;
            cursor: pointer;
        }

        .radio-item input {
            accent-color: #6b7280;
        }

        textarea {
            width: 100%;
            border: 1px solid #d1d5db;
            background: #fff;
            color: #1f2937;
            border-radius: 10px;
            padding: .55rem .65rem;
            font-size: .82rem;
            resize: vertical;
            min-height: 84px;
            margin-top: .3rem;
            margin-bottom: .75rem;
            outline: none;
        }

        textarea:focus {
            border-color: #9ca3af;
            box-shadow: 0 0 0 3px rgba(107, 114, 128, .12);
        }

        .btn-save {
            width: 100%;
            min-height: 41px;
            border-radius: 10px;
            border-color: #325e42;
            background: #40ab6d;
            color: #ffffff;
            font-size: .82rem;
            font-weight: 700;
            letter-spacing: .01em;
            transition: background .15s, border-color .15s;
        }

        .btn-save:hover {
            border-color: #7fe499 !important;
            background: #052f14 !important;
        }

        .btn-save:disabled {
            opacity: .58;
            cursor: not-allowed;
        }

        .pending-note {
            margin-top: .55rem;
            color: #6b7280;
            font-size: .72rem;
            text-align: center;
        }

        .rekap-body {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: .42rem;
        }

        .rekap-body div {
            border: 1px solid #e5e7eb;
            background: #f9fafb;
            border-radius: 10px;
            padding: .5rem .62rem;
            color: #4b5563;
            font-size: .8rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .rekap-body strong {
            font-size: .88rem;
            color: #111827;
        }

        .kanca-copyright {
            text-align: center;
            font-size: .72rem;
            color: #9ca3af;
            letter-spacing: .03em;
            margin-top: -.25rem;
        }

        .empty-state {
            text-align: center;
            padding: 1rem !important;
            color: #6b7280 !important;
        }

        .swal2-popup.export-range-swal {
            background: #ffffff !important;
            background-image: none !important;
            color: #111827 !important;
            border: 1px solid #e5e7eb !important;
            box-shadow: 0 16px 36px rgba(15, 23, 42, .2) !important;
        }

        .swal2-popup.export-range-swal .swal2-title {
            color: #111827 !important;
        }

        .swal2-popup.export-range-swal .swal2-html-container {
            color: #374151 !important;
        }

        .swal2-popup.export-range-swal .swal2-input {
            background: #ffffff !important;
            color: #111827 !important;
            border: 1px solid #d1d5db !important;
            box-shadow: none !important;
        }

        .swal2-popup.export-range-swal .swal2-input:focus {
            border-color: #9ca3af !important;
            box-shadow: 0 0 0 3px rgba(107, 114, 128, .14) !important;
        }

        .swal2-popup.export-range-swal .swal2-validation-message {
            background: #fef2f2 !important;
            color: #b91c1c !important;
        }

        .swal2-popup.export-range-swal .swal2-confirm.export-range-confirm {
            background: #16a34a !important;
            color: #ffffff !important;
            border: 0 !important;
            border-radius: 999px !important;
            padding: .58rem 1.2rem !important;
        }

        .swal2-popup.export-range-swal .swal2-cancel.export-range-cancel {
            background: #f3f4f6 !important;
            color: #374151 !important;
            border: 1px solid #d1d5db !important;
            border-radius: 999px !important;
            padding: .58rem 1.2rem !important;
        }

        @media (min-width: 1081px) {
            body.kanca-fit-screen {
                padding-bottom: 0 !important;
                overflow: hidden;
            }

            .kanca-page {
                height: 100dvh;
                max-height: 100dvh;
                display: flex;
                flex-direction: column;
                overflow: hidden;
            }

            .kanca-header,
            .toolbar-card {
                flex-shrink: 0;
            }

            .kanca-grid {
                flex: 1;
                min-height: 0;
                grid-template-rows: minmax(0, 1fr);
            }

            .table-card {
                height: 100%;
            }

            .table-scroll {
                flex: 1;
                min-height: 0;
                max-height: 100%;
            }

            .kanca-side {
                overflow: auto;
                padding-right: .15rem;
            }
        }

        @media (max-width: 1240px) {
            .toolbar-form {
                grid-template-columns: 1fr 104px 1fr;
            }

            .control-search {
                grid-column: 1 / -1;
            }
        }

        @media (max-width: 1080px) {
            body.kanca-fit-screen {
                overflow: auto;
            }

            .kanca-grid {
                grid-template-columns: 1fr;
            }

            .table-scroll {
                max-height: 58vh;
            }

            .kanca-header .col-3,
            .kanca-header .col-9 {
                width: 100%;
            }
        }

        @media (max-width: 860px) {
            .kanca-page {
                padding: .75rem !important;
            }

            .header-pills {
                margin-bottom: .55rem;
            }

            .toolbar-form {
                grid-template-columns: 1fr;
            }

            .control-search {
                grid-column: auto;
            }

            .search-wrap {
                grid-template-columns: 1fr;
            }

            .radio-list,
            .rekap-body {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        (function() {
            document.body.classList.add('kanca-fit-screen');

            const filterForm = document.getElementById('filterForm');
            const statusForm = document.getElementById('statusForm');
            const dateInput = document.getElementById('date');
            const divisionInput = document.getElementById('division');
            const searchInput = document.getElementById('search');
            const exportExcelBtn = document.getElementById('exportExcelBtn');
            const selectedFilter = document.getElementById('selected_filter');
            const divisionPrevBtn = document.getElementById('divisionPrevBtn');
            const divisionNextBtn = document.getElementById('divisionNextBtn');

            const tableBody = document.querySelector('#attendanceTable tbody');
            const tableScroll = document.querySelector('.table-scroll');
            const selectedPegawai = document.getElementById('selectedPegawai');
            const selectedStatusBadge = document.getElementById('selectedStatusBadge');
            const kancaInput = document.getElementById('kanca_id');
            const notesInput = document.getElementById('notes');
            const saveButton = document.querySelector('.btn-save');
            const pendingNote = document.getElementById('pendingNote');
            const saveDateInput = document.getElementById('save_date');
            const saveDivisionInput = document.getElementById('save_division');
            const saveSearchInput = document.getElementById('save_search');
            const changesPayloadInput = document.getElementById('changes_payload');

            const totalEmployeesText = document.getElementById('totalEmployeesText');
            const scopeText = document.getElementById('scopeText');
            const activeDateText = document.getElementById('activeDateText');

            const summaryEls = {
                Masuk: document.getElementById('sumMasuk'),
                Telat: document.getElementById('sumTelat'),
                Sakit: document.getElementById('sumSakit'),
                Absen: document.getElementById('sumAbsen'),
                Izin: document.getElementById('sumIzin'),
                Cuti: document.getElementById('sumCuti'),
            };

            const knownStatuses = ['Masuk', 'Telat', 'Sakit', 'Absen', 'Izin', 'Cuti'];
            const pendingChanges = new Map();
            let debounceTimer = null;
            let requestSeq = 0;
            const exportBaseUrl = @json(route('absen.kanca.export'));
            const initialExportStartDate = @json($exportStartDate ?? $activeDate);
            const initialExportEndDate = @json($exportEndDate ?? $activeDate);

            function escapeHtml(text) {
                return String(text ?? '')
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#039;');
            }

            function normalizeStatus(statusValue) {
                const text = String(statusValue || '').trim().toLowerCase();
                const found = knownStatuses.find((s) => s.toLowerCase() === text);
                return found || 'Masuk';
            }

            function setStatusRadio(statusValue) {
                const radios = document.querySelectorAll('input[name="status"]');
                radios.forEach((radio) => {
                    radio.checked = radio.value === statusValue;
                });
            }

            function setStatusBadge(statusValue) {
                if (!selectedStatusBadge) {
                    return;
                }
                const status = normalizeStatus(statusValue);
                selectedStatusBadge.className = 'status-tag status-' + status.toLowerCase();
                selectedStatusBadge.textContent = status;
            }

            function updatePendingUI() {
                const count = pendingChanges.size;
                changesPayloadInput.value = count > 0 ?
                    JSON.stringify(Array.from(pendingChanges.values())) :
                    '';

                if (pendingNote) {
                    pendingNote.textContent = count > 0 ?
                        `${count} perubahan belum disimpan.` :
                        'Belum ada perubahan.';
                }

                if (saveButton) {
                    saveButton.disabled = count === 0;
                }
            }

            function fitTableScrollHeight() {
                if (!tableScroll) {
                    return;
                }

                if (window.innerWidth <= 1080) {
                    tableScroll.style.height = '';
                    tableScroll.style.maxHeight = '';
                    return;
                }

                const rect = tableScroll.getBoundingClientRect();
                const bottomGap = 14; // ruang bawah agar tidak nempel viewport
                const available = Math.floor(window.innerHeight - rect.top - bottomGap);

                if (available > 220) {
                    tableScroll.style.height = `${available}px`;
                    tableScroll.style.maxHeight = `${available}px`;
                }
            }

            function syncSaveFilters() {
                saveDateInput.value = dateInput.value;
                saveDivisionInput.value = divisionInput.value;
                saveSearchInput.value = searchInput.value;
                updateExportHref();
            }

            function updateExportHref() {
                if (!exportExcelBtn) {
                    return;
                }

                const activeDate = dateInput.value || '';
                const params = new URLSearchParams({
                    date: activeDate,
                    division: divisionInput.value || '',
                    search: searchInput.value || '',
                });
                exportExcelBtn.href = `${exportBaseUrl}?${params.toString()}`;
            }

            async function promptExportDateRange() {
                const fallbackDate = dateInput.value || '';
                const defaultStartDate = initialExportStartDate || fallbackDate;
                const defaultEndDate = initialExportEndDate || fallbackDate;

                const result = await Swal.fire({
                    title: 'Pilih Range Tanggal Export',
                    background: '#ffffff',
                    color: '#111827',
                    customClass: {
                        popup: 'export-range-swal',
                        confirmButton: 'export-range-confirm',
                        cancelButton: 'export-range-cancel',
                    },
                    buttonsStyling: false,
                    html: `
                        <div style="text-align:left;">
                            <label for="swalExportStart" style="display:block;font-size:.8rem;margin-bottom:.25rem;">Tanggal Mulai</label>
                            <input id="swalExportStart" type="date" class="swal2-input" style="margin:.2rem 0 .55rem 0;width:100%;" value="${escapeHtml(defaultStartDate)}">
                            <label for="swalExportEnd" style="display:block;font-size:.8rem;margin-bottom:.25rem;">Tanggal Selesai</label>
                            <input id="swalExportEnd" type="date" class="swal2-input" style="margin:.2rem 0 0 0;width:100%;" value="${escapeHtml(defaultEndDate)}">
                        </div>
                    `,
                    showCancelButton: true,
                    confirmButtonText: 'Download',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#16a34a',
                    cancelButtonColor: '#6b7280',
                    focusConfirm: false,
                    preConfirm: () => {
                        const startDate = document.getElementById('swalExportStart')?.value || '';
                        const endDate = document.getElementById('swalExportEnd')?.value || '';

                        if (!startDate || !endDate) {
                            Swal.showValidationMessage('Tanggal mulai dan selesai wajib diisi.');
                            return false;
                        }

                        if (startDate > endDate) {
                            Swal.showValidationMessage('Tanggal mulai tidak boleh lebih besar dari tanggal selesai.');
                            return false;
                        }

                        return {
                            startDate,
                            endDate,
                        };
                    },
                });

                return result.isConfirmed ? result.value : null;
            }

            function setSelectedRowUI(id) {
                const rows = tableBody.querySelectorAll('.employee-row');
                rows.forEach((row) => {
                    row.classList.toggle('selected', String(row.dataset.kancaId) === String(id));
                });
            }

            function setSelectedData(data) {
                const id = data?.id ? String(data.id) : '';
                const name = data?.name || '(Belum memilih pegawai)';
                const status = normalizeStatus(data?.status || 'Masuk');
                const notes = data?.notes || '';

                kancaInput.value = id;
                selectedFilter.value = id;
                selectedPegawai.textContent = name;
                notesInput.value = notes;

                setStatusRadio(status);
                setStatusBadge(status);
                setSelectedRowUI(id);
            }

            function setRowStatusPreview(row, statusValue, markPending = true) {
                if (!row) {
                    return;
                }

                const status = normalizeStatus(statusValue);
                row.dataset.status = status;
                row.classList.toggle('pending-local', markPending);

                const statusCell = row.children[3];
                const indicatorCell = row.children[4];

                if (statusCell) {
                    statusCell.innerHTML =
                        `<span class="status-tag status-${escapeHtml(status.toLowerCase())}">${escapeHtml(status)}</span>`;
                }

                if (indicatorCell) {
                    indicatorCell.textContent = status.charAt(0).toUpperCase();
                }
            }

            function syncPendingForRow(row) {
                if (!row) {
                    return;
                }

                const id = String(row.dataset.kancaId || '');
                if (!id) {
                    return;
                }

                const currentStatus = normalizeStatus(row.dataset.status || 'Masuk');
                const currentNotes = String(row.dataset.notes || '');
                const originalStatus = normalizeStatus(row.dataset.originalStatus || 'Masuk');
                const originalNotes = String(row.dataset.originalNotes || '');

                if (currentStatus === originalStatus && currentNotes === originalNotes) {
                    pendingChanges.delete(id);
                    row.classList.remove('pending-local');
                } else {
                    pendingChanges.set(id, {
                        kanca_id: Number(id),
                        status: currentStatus,
                        notes: currentNotes,
                    });
                    row.classList.add('pending-local');
                }

                updatePendingUI();
            }

            function recomputeSummaryFromTable() {
                const counts = {
                    Masuk: 0,
                    Telat: 0,
                    Sakit: 0,
                    Absen: 0,
                    Izin: 0,
                    Cuti: 0,
                };

                tableBody.querySelectorAll('.employee-row').forEach((row) => {
                    const status = normalizeStatus(row.dataset.status || 'Masuk');
                    counts[status] = (counts[status] || 0) + 1;
                });

                Object.keys(summaryEls).forEach((key) => {
                    if (summaryEls[key]) {
                        summaryEls[key].textContent = String(counts[key] ?? 0);
                    }
                });
            }

            function findRowById(id) {
                return Array.from(tableBody.querySelectorAll('.employee-row'))
                    .find((row) => String(row.dataset.kancaId) === String(id)) || null;
            }

            function applyPendingToRenderedRows() {
                tableBody.querySelectorAll('.employee-row').forEach((row) => {
                    const pending = pendingChanges.get(String(row.dataset.kancaId || ''));
                    if (!pending) {
                        return;
                    }

                    row.dataset.notes = String(pending.notes || '');
                    setRowStatusPreview(row, pending.status, true);
                });
            }

            function renderRows(rows, selectedId) {
                if (!Array.isArray(rows) || rows.length === 0) {
                    tableBody.innerHTML =
                        '<tr><td colspan="5" class="empty-state">Tidak ada data sesuai filter.</td></tr>';
                    return;
                }

                tableBody.innerHTML = rows.map((row) => {
                    const id = String(row.id ?? '');
                    const isSelected = String(selectedId) === id;
                    const status = normalizeStatus(row.status ?? 'Masuk');
                    const notes = String(row.notes ?? '');

                    return `
                        <tr class="employee-row ${isSelected ? 'selected' : ''}"
                            data-kanca-id="${escapeHtml(id)}"
                            data-name="${escapeHtml(row.name ?? '')}"
                            data-status="${escapeHtml(status)}"
                            data-notes="${escapeHtml(notes)}"
                            data-original-status="${escapeHtml(status)}"
                            data-original-notes="${escapeHtml(notes)}">
                            <td>${escapeHtml(row.division ?? '')}</td>
                            <td>${escapeHtml(row.jabatan ?? '')}</td>
                            <td>${escapeHtml(row.name ?? '')}</td>
                            <td><span class="status-tag status-${escapeHtml(status.toLowerCase())}">${escapeHtml(status)}</span></td>
                            <td>${escapeHtml(row.indicator ?? '')}</td>
                        </tr>
                    `;
                }).join('');
            }

            async function loadKancaData(resetSelected = false) {
                if (resetSelected) {
                    selectedFilter.value = '';
                }
                syncSaveFilters();

                const params = new URLSearchParams({
                    date: dateInput.value || '',
                    division: divisionInput.value || '',
                    search: searchInput.value || '',
                    selected: selectedFilter.value || '',
                    ajax: '1',
                });

                const reqId = ++requestSeq;

                try {
                    const resp = await fetch(`{{ route('Input-Index-Kanca') }}?${params.toString()}`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                    });
                    if (!resp.ok) {
                        return;
                    }

                    const payload = await resp.json();
                    if (reqId !== requestSeq) {
                        return;
                    }

                    const rows = payload.rows || [];
                    const selectedId = selectedFilter.value || payload.selectedRow?.id || rows[0]?.id || '';

                    renderRows(rows, selectedId);
                    applyPendingToRenderedRows();

                    const selectedRow = findRowById(selectedId);
                    if (selectedRow) {
                        setSelectedData({
                            id: selectedRow.dataset.kancaId || '',
                            name: selectedRow.dataset.name || '',
                            status: selectedRow.dataset.status || 'Masuk',
                            notes: selectedRow.dataset.notes || '',
                        });
                    } else {
                        setSelectedData(null);
                    }

                    totalEmployeesText.textContent = String(payload.totalEmployees ?? 0);
                    scopeText.textContent = payload.activeDivision ? payload.activeDivision : 'Semua Divisi';
                    activeDateText.textContent = payload.activeDate || dateInput.value;

                    Object.keys(summaryEls).forEach((key) => {
                        if (summaryEls[key]) {
                            summaryEls[key].textContent = String((payload.summary || {})[key] ?? 0);
                        }
                    });

                    recomputeSummaryFromTable();
                    updatePendingUI();
                    fitTableScrollHeight();
                } catch (err) {
                    console.error(err);
                }
            }

            function debounceLoad() {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    loadKancaData(true);
                }, 300);
            }

            function stepDivision(step) {
                const options = Array.from(divisionInput.options);
                if (options.length < 2) {
                    return;
                }
                const currentIndex = Math.max(0, divisionInput.selectedIndex);
                const nextIndex = (currentIndex + step + options.length) % options.length;
                divisionInput.selectedIndex = nextIndex;
                loadKancaData(true);
            }

            document.querySelectorAll('input[name="status"]').forEach((radio) => {
                radio.addEventListener('change', function() {
                    if (!this.checked) {
                        return;
                    }

                    setStatusBadge(this.value);

                    const selectedRow = tableBody.querySelector('.employee-row.selected');
                    if (!selectedRow) {
                        return;
                    }

                    setRowStatusPreview(selectedRow, this.value, true);
                    syncPendingForRow(selectedRow);
                    recomputeSummaryFromTable();
                });
            });

            notesInput.addEventListener('input', () => {
                const selectedRow = tableBody.querySelector('.employee-row.selected');
                if (!selectedRow) {
                    return;
                }
                selectedRow.dataset.notes = notesInput.value || '';
                syncPendingForRow(selectedRow);
            });

            tableBody.addEventListener('click', (event) => {
                const row = event.target.closest('.employee-row');
                if (!row) {
                    return;
                }

                setSelectedData({
                    id: row.dataset.kancaId || '',
                    name: row.dataset.name || '',
                    status: row.dataset.status || 'Masuk',
                    notes: row.dataset.notes || '',
                });
            });

            filterForm.addEventListener('submit', (event) => {
                event.preventDefault();
                loadKancaData(false);
            });

            divisionInput.addEventListener('change', () => {
                updateExportHref();
                loadKancaData(true);
            });
            dateInput.addEventListener('change', () => {
                updateExportHref();
                loadKancaData(true);
            });
            searchInput.addEventListener('input', () => {
                updateExportHref();
                debounceLoad();
            });
            exportExcelBtn?.addEventListener('click', async (event) => {
                event.preventDefault();

                const range = await promptExportDateRange();
                if (!range) {
                    return;
                }

                const params = new URLSearchParams({
                    date: dateInput.value || '',
                    start_date: range.startDate,
                    end_date: range.endDate,
                    division: divisionInput.value || '',
                    search: searchInput.value || '',
                });

                window.location.href = `${exportBaseUrl}?${params.toString()}`;
            });

            divisionPrevBtn.addEventListener('click', () => stepDivision(-1));
            divisionNextBtn.addEventListener('click', () => stepDivision(1));
            window.addEventListener('resize', fitTableScrollHeight);

            statusForm.addEventListener('submit', (event) => {
                syncSaveFilters();
                if (pendingChanges.size === 0) {
                    event.preventDefault();
                    return;
                }
                changesPayloadInput.value = JSON.stringify(Array.from(pendingChanges.values()));
            });

            syncSaveFilters();
            updatePendingUI();
            updateExportHref();
            fitTableScrollHeight();
        })();
    </script>

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: @json(session('success')),
                timer: 2200,
                timerProgressBar: true,
                showConfirmButton: false,
                background: '#1a1a4e',
                color: '#fff',
                iconColor: '#34d399'
            });
        </script>
    @endif

    @if ($errors->any())
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Data belum lengkap',
                html: @json($errors->all()).map(msg => `• ${msg}`).join('<br>'),
                confirmButtonText: 'Tutup',
                confirmButtonColor: '#6366f1',
                background: '#1a1a4e',
                color: '#fff'
            });
        </script>
    @endif
@endpush
