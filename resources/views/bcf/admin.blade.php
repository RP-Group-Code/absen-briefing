@extends('layouts.app-public')

@section('title', 'Portal Admin BCF')
@section('description', 'Portal admin untuk mengelola registrasi peserta BCF.')

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
        :root { --admin-blue: #075bc7; --admin-deep: #064497; --admin-ink: #14233b; --admin-muted: #718098; --admin-bg: #f3f7fc; }
        body { background: var(--admin-bg) !important; color: var(--admin-ink); padding-bottom: 0 !important; }
        body::before, body::after { display: none !important; }
        .admin-page { min-height: 100svh; padding: 32px 16px 70px; }
        .admin-shell { width: min(1120px, 100%); margin: 0 auto; }
        .admin-head, .admin-card { background: #fff; border: 1px solid #dfe8f2; border-radius: 18px; box-shadow: 0 12px 30px rgba(18, 59, 108, .07); }
        .admin-head { border-top: 5px solid var(--admin-blue); padding: 26px 30px; display: flex; align-items: center; justify-content: space-between; gap: 18px; }
        .admin-eyebrow { color: var(--admin-blue); font-size: .72rem; font-weight: 800; letter-spacing: .12em; text-transform: uppercase; }
        .admin-head h1 { margin: 6px 0 4px; font-size: clamp(1.5rem, 4vw, 2.1rem); font-weight: 800; }
        .admin-head p { margin: 0; color: var(--admin-muted); }
        .admin-back { border: 1px solid #d7e2ee; border-radius: 9px; color: var(--admin-deep); font-size: .8rem; font-weight: 800; padding: 10px 13px; text-decoration: none; white-space: nowrap; }
        .admin-back:hover { background: #edf8fd; }
        .admin-section-title { margin: 30px 0 13px; font-size: 1.1rem; font-weight: 800; }
        .admin-teams { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; }
        .admin-team { background: #fff; border: 1px solid #dfe8f2; border-radius: 13px; padding: 15px; }
        .admin-team-top { display: flex; align-items: center; justify-content: space-between; gap: 10px; }
        .admin-team-name { color: var(--admin-deep); font-weight: 800; }
        .admin-color { display: inline-flex; align-items: center; gap: 6px; color: var(--admin-muted); font-size: .76rem; }
        .admin-dot { display: inline-block; width: 11px; height: 11px; border: 1px solid #d3dce7; border-radius: 50%; }
        .admin-pic { margin-top: 10px; color: var(--admin-muted); font-size: .78rem; }
        .admin-pic strong { color: var(--admin-deep); font-weight: 800; }
        .admin-team-count { display: flex; align-items: baseline; justify-content: space-between; margin-top: 12px; }
        .admin-team-count strong { color: var(--admin-blue); font-size: 1.35rem; }
        .admin-team-count span { color: var(--admin-muted); font-size: .74rem; }
        .admin-progress { height: 6px; margin-top: 10px; overflow: hidden; border-radius: 99px; background: #e9f0f7; }
        .admin-progress span { display: block; height: 100%; border-radius: inherit; background: linear-gradient(90deg, var(--admin-blue), #55c7ed); }
        .admin-card { overflow: hidden; margin-top: 30px; }
        .admin-card.is-loading { opacity: .55; transition: opacity .2s ease; pointer-events: none; }
        .admin-card-head { padding: 22px 25px 16px; }
        .admin-card-head h2 { margin: 0 0 5px; font-size: 1.25rem; font-weight: 800; }
        .admin-card-head p { margin: 0; color: var(--admin-muted); font-size: .86rem; }
        .admin-list-meta { display: flex; align-items: center; justify-content: space-between; gap: 12px; margin: 0 25px 14px; color: var(--admin-muted); font-size: .8rem; }
        .admin-per-page { display: inline-flex; align-items: center; gap: 8px; }
        .admin-per-page select { min-width: 84px; height: 38px; border: 1px solid #d8e2ee; border-radius: 9px; padding: 6px 10px; color: var(--admin-ink); background: #fff; }
        .admin-searchbar { display: flex; gap: 8px; margin: 0 25px 16px; }
        .admin-searchbar input { flex: 1; min-width: 0; height: 42px; border: 1px solid #d8e2ee; border-radius: 9px; padding: 8px 12px; color: var(--admin-ink); }
        .admin-searchbar input:focus { border-color: #55c7ed; box-shadow: 0 0 0 3px rgba(105, 201, 235, .18); outline: 0; }
        .admin-search-submit, .admin-search-reset { display: inline-flex; align-items: center; justify-content: center; border-radius: 9px; font-size: .78rem; font-weight: 800; padding: 0 14px; text-decoration: none; }
        .admin-search-submit { border: 0; background: var(--admin-blue); color: #fff; }
        .admin-search-reset { border: 1px solid #d8e2ee; color: var(--admin-muted); background: #fff; }
        .admin-export { display: inline-flex; align-items: center; justify-content: center; border: 1px solid #d8e2ee; border-radius: 9px; background: #fff; color: var(--admin-deep); font-size: .78rem; font-weight: 800; padding: 0 14px; text-decoration: none; white-space: nowrap; }
        .admin-table-wrap { overflow-x: auto; padding: 0 25px 25px; }
        .admin-table { width: 100%; border-collapse: separate; border-spacing: 0; font-size: .82rem; }
        .admin-table th { background: #f4f8fc; color: var(--admin-muted); font-size: .68rem; letter-spacing: .06em; padding: 11px 12px; text-align: left; text-transform: uppercase; white-space: nowrap; }
        .admin-table th:first-child { border-radius: 9px 0 0 9px; }
        .admin-table th:last-child { border-radius: 0 9px 9px 0; }
        .admin-table td { border-bottom: 1px solid #e7eef6; padding: 12px; vertical-align: middle; }
        .admin-table tbody tr:last-child td { border-bottom: 0; }
        .admin-name { font-weight: 800; white-space: nowrap; }
        .admin-uker { color: var(--admin-muted); max-width: 260px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .admin-action { width: 33px; height: 33px; border: 1px solid #dbe5ef; border-radius: 8px; background: #fff; color: var(--admin-blue); }
        .admin-action.delete { color: #d85c59; }
        .admin-actions { display: flex; gap: 5px; }
        .admin-modal .modal-content { border: 0; border-radius: 16px; overflow: hidden; }
        .admin-modal .modal-header { border: 0; background: var(--admin-blue); color: #fff; }
        .admin-modal .btn-close { filter: brightness(0) invert(1); }
        .admin-label { display: block; margin-bottom: 7px; font-size: .8rem; font-weight: 800; }
        .admin-input { min-height: 44px; border: 1px solid #d8e2ee; border-radius: 9px; }
        .admin-input:focus { border-color: #55c7ed; box-shadow: 0 0 0 3px rgba(105, 201, 235, .18); }
        .admin-save { border: 0; border-radius: 9px; background: var(--admin-blue); color: #fff; font-weight: 800; padding: 11px 18px; }
        .admin-pagination { display: flex; justify-content: flex-end; padding: 0 25px 22px; }
        .admin-pagination nav { margin: 0; }
        .admin-pagination .pagination { margin: 0; gap: 4px; }
        .admin-pagination .page-link { border: 1px solid #d8e2ee; border-radius: 7px; color: var(--admin-deep); font-size: .76rem; }
        .admin-pagination .page-item.active .page-link { border-color: var(--admin-blue); background: var(--admin-blue); color: #fff; }
        .admin-pagination .page-item.disabled .page-link { color: #a4afbd; }
        @media (max-width: 700px) {
            .admin-page { padding: 18px 10px 45px; }
            .admin-head { align-items: flex-start; flex-direction: column; padding: 20px; }
            .admin-head p { font-size: .78rem; }
            .admin-back { font-size: .72rem; padding: 8px 10px; }
            .admin-teams { grid-template-columns: repeat(2, 1fr); gap: 8px; }
            .admin-team { padding: 11px; }
            .admin-team-name { font-size: .78rem; }
            .admin-color { font-size: .65rem; }
            .admin-team-count strong { font-size: 1.1rem; }
            .admin-team-count span { font-size: .65rem; }
            .admin-card-head { padding: 18px 15px 13px; }
            .admin-card-head h2 { font-size: 1.05rem; }
            .admin-card-head p { font-size: .74rem; }
            .admin-searchbar { margin: 0 15px 12px; }
            .admin-list-meta { align-items: flex-start; flex-direction: column; margin: 0 15px 12px; font-size: .72rem; }
            .admin-searchbar input { height: 38px; font-size: .72rem; }
            .admin-search-submit, .admin-search-reset { font-size: .68rem; padding: 0 10px; }
            .admin-table-wrap { padding: 0 10px 12px; }
            .admin-table { min-width: 720px; font-size: .7rem; }
            .admin-table th { font-size: .59rem; padding: 8px 7px; }
            .admin-table td { padding: 8px 7px; }
            .admin-pagination { justify-content: center; padding: 0 10px 16px; }
        }
    </style>
@endpush

@section('content')
    <main class="admin-page">
        <div class="admin-shell">
            <header class="admin-head">
                <div><div class="admin-eyebrow">BCF 2026 · Restricted Access</div><h1>Portal Admin Registrasi</h1><p>Kelola peserta, team, warna, dan kapasitas registrasi.</p></div>
                <a href="{{ route('bcf.registrasi.index') }}" class="admin-back"><i class="fa-solid fa-arrow-left me-2"></i>Kembali ke Form</a>
            </header>

            @if ($errors->any())
                <div class="alert alert-danger border-0 shadow-sm mt-4 mb-0"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
            @endif

            <h2 class="admin-section-title">Kuota Team &amp; Warna</h2>
            <div class="admin-teams">
                @foreach ($teamSummary as $team)
                    @php $percentage = min(100, ($team['used'] / $team['capacity']) * 100); @endphp
                    <div class="admin-team"><div class="admin-team-top"><span class="admin-team-name">{{ $team['team'] }}</span><span class="admin-color"><i class="admin-dot" style="background: {{ $colorHexMap[$team['warna']] ?? '#55c7ed' }}"></i>{{ $team['warna'] }}</span></div><div class="admin-pic">Penanggung jawab: <strong>{{ $team['penanggung_jawab'] }}</strong></div><div class="admin-team-count"><strong>{{ $team['used'] }}/{{ $team['capacity'] }}</strong><span>{{ $team['remaining'] }} tersisa</span></div><div class="admin-progress"><span style="width: {{ $percentage }}%"></span></div></div>
                @endforeach
            </div>

            <section id="daftar-peserta-admin" class="admin-card">
                <div class="admin-card-head"><h2>Daftar Peserta</h2><p>Perubahan team otomatis memperbarui hitungan kuota di atas.</p></div>
                <div class="admin-list-meta">
                    <span>Menampilkan {{ $registrasi->firstItem() ?? 0 }}-{{ $registrasi->lastItem() ?? 0 }} dari {{ $registrasi->total() }} peserta</span>
                    <form method="GET" action="{{ route('bcf.registrasi.admin') }}" class="admin-per-page">
                        @if ($search !== '')
                            <input type="hidden" name="search" value="{{ $search }}">
                        @endif
                        <label for="adminPerPage">Baris per halaman</label>
                        <select id="adminPerPage" name="per_page">
                            @foreach ($perPageOptions as $option)
                                <option value="{{ $option }}" @selected($perPage === $option)>{{ $option }}</option>
                            @endforeach
                        </select>
                    </form>
                </div>
                <form method="GET" action="{{ route('bcf.registrasi.admin') }}" class="admin-searchbar">
                    <input type="search" name="search" value="{{ $search }}" placeholder="Cari nama, PN, team, warna, no urut, atau Uker..." autocomplete="off">
                    <input type="hidden" name="per_page" value="{{ $perPage }}">
                    <button type="submit" class="admin-search-submit"><i class="fa-solid fa-magnifying-glass me-1"></i>Cari</button>
                    <a href="{{ route('bcf.registrasi.export', ['search' => $search]) }}" class="admin-export"><i class="fa-solid fa-file-export me-1"></i>Export Excel</a>
                    @if ($search !== '')<a href="{{ route('bcf.registrasi.admin', ['per_page' => $perPage]) }}" class="admin-search-reset">Reset</a>@endif
                </form>
                <div class="admin-table-wrap">
                    <table class="admin-table"><thead><tr><th>Nama</th><th>PN</th><th>Team</th><th>Warna</th><th>No Urut</th><th>Uker</th><th>Aksi</th></tr></thead><tbody>
                        @forelse ($registrasi as $row)
                            <tr><td class="admin-name">{{ $row->nama }}</td><td>{{ $row->pn }}</td><td>{{ $row->team ?: '-' }}</td><td>{{ $row->warna }}</td><td>{{ $row->nourut ?: '-' }}</td><td class="admin-uker" title="{{ $row->unit_kerja }}">{{ $row->unit_kerja }}</td><td><div class="admin-actions"><button type="button" class="admin-action admin-edit" data-id="{{ $row->id }}" data-nama="{{ $row->nama }}" data-pn="{{ $row->pn }}" data-unit="{{ $row->unit_kerja }}" data-team="{{ $row->team }}" data-warna="{{ $row->warna }}" data-nourut="{{ $row->nourut }}"><i class="fa-solid fa-pen"></i></button><form method="POST" action="{{ route('bcf.registrasi.destroy', $row->id) }}" class="admin-delete" data-nama="{{ $row->nama }}">@csrf @method('DELETE')<button type="submit" class="admin-action delete"><i class="fa-solid fa-trash"></i></button></form></div></td></tr>
                        @empty
                            <tr><td colspan="7" class="text-center text-muted py-4">Belum ada peserta.</td></tr>
                        @endforelse
                    </tbody></table>
                </div>
                @if ($registrasi->hasPages())
                    <div class="admin-pagination">{{ $registrasi->fragment('daftar-peserta-admin')->onEachSide(1)->links('pagination::bootstrap-5') }}</div>
                @endif
            </section>
        </div>
    </main>

    <div class="modal fade admin-modal" id="adminEditModal" tabindex="-1" aria-hidden="true"><div class="modal-dialog modal-dialog-centered"><div class="modal-content"><form id="adminEditForm" method="POST">@csrf @method('PUT')<div class="modal-header"><h5 class="modal-title">Edit Peserta</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body"><div class="row g-3"><div class="col-md-6"><label class="admin-label">Nama</label><input id="admin_nama" name="nama" class="form-control admin-input" required></div><div class="col-md-6"><label class="admin-label">PN</label><input id="admin_pn" name="pn" class="form-control admin-input" required></div><div class="col-12"><label class="admin-label">Uker</label><input id="admin_unit" name="unit_kerja" class="form-control admin-input" required></div><div class="col-md-4"><label class="admin-label">No Urut</label><input id="admin_nourut" type="number" min="1" name="nourut" class="form-control admin-input"></div><div class="col-md-4"><label class="admin-label">Team</label><select id="admin_team" name="team" class="form-select admin-input" required>@foreach ($teamSummary as $team)<option value="{{ $team['team'] }}" data-warna="{{ $team['warna'] }}">{{ $team['team'] }} ({{ $team['used'] }}/{{ $team['capacity'] }})</option>@endforeach</select></div><div class="col-md-4"><label class="admin-label">Warna</label><input id="admin_warna" name="warna" class="form-control admin-input" readonly required></div></div></div><div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button><button type="submit" class="admin-save">Simpan Perubahan</button></div></form></div></div></div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const editForm = document.getElementById('adminEditForm');
            const editModal = document.getElementById('adminEditModal');
            const teamSelect = document.getElementById('admin_team');
            const colorInput = document.getElementById('admin_warna');
            const adminSectionSelector = '#daftar-peserta-admin';

            const syncAdminColor = function () {
                colorInput.value = teamSelect.options[teamSelect.selectedIndex]?.dataset.warna || '';
            };

            teamSelect?.addEventListener('change', syncAdminColor);

            const updateBrowserUrl = function (url) {
                if (window.history?.pushState) {
                    window.history.pushState({}, '', url);
                }
            };

            const replaceSectionFromHtml = function (html, selector) {
                const parser = new DOMParser();
                const documentFromHtml = parser.parseFromString(html, 'text/html');
                const nextSection = documentFromHtml.querySelector(selector);
                const currentSection = document.querySelector(selector);

                if (!nextSection || !currentSection) {
                    window.location.assign(window.location.href);
                    return false;
                }

                currentSection.replaceWith(nextSection);
                return true;
            };

            const fetchSection = function (url, selector, onDone) {
                const section = document.querySelector(selector);
                if (!section || !window.jQuery) {
                    window.location.assign(url);
                    return;
                }

                section.classList.add('is-loading');
                jQuery.get(url)
                    .done(function (html) {
                        if (!replaceSectionFromHtml(html, selector)) {
                            return;
                        }

                        updateBrowserUrl(url);
                        onDone?.();
                    })
                    .fail(function () {
                        window.location.assign(url);
                    });
            };

            const bindAdminActions = function () {
                const perPageSelect = document.getElementById('adminPerPage');
                const searchForm = document.querySelector('.admin-searchbar');

                perPageSelect?.addEventListener('change', function () {
                    const url = `${this.form.action}?${new URLSearchParams(new FormData(this.form)).toString()}#daftar-peserta-admin`;
                    fetchSection(url, adminSectionSelector, bindAdminActions);
                });

                searchForm?.addEventListener('submit', function (event) {
                    event.preventDefault();
                    const url = `${this.action}?${new URLSearchParams(new FormData(this)).toString()}#daftar-peserta-admin`;
                    fetchSection(url, adminSectionSelector, bindAdminActions);
                });

                document.querySelectorAll(`${adminSectionSelector} .admin-search-reset`).forEach(link => link.addEventListener('click', function (event) {
                    event.preventDefault();
                    fetchSection(this.href, adminSectionSelector, bindAdminActions);
                }));

                document.querySelectorAll(`${adminSectionSelector} .admin-pagination a`).forEach(link => link.addEventListener('click', function (event) {
                    event.preventDefault();
                    fetchSection(this.href, adminSectionSelector, bindAdminActions);
                }));

                document.querySelectorAll(`${adminSectionSelector} .admin-edit`).forEach(button => button.addEventListener('click', function () {
                    const data = this.dataset;
                    editForm.action = `{{ url('/bcf-registrasi') }}/${data.id}`;
                    document.getElementById('admin_nama').value = data.nama || '';
                    document.getElementById('admin_pn').value = data.pn || '';
                    document.getElementById('admin_unit').value = data.unit || '';
                    document.getElementById('admin_nourut').value = data.nourut || '';
                    teamSelect.value = data.team || '';
                    syncAdminColor();
                    bootstrap.Modal.getOrCreateInstance(editModal).show();
                }));

                document.querySelectorAll(`${adminSectionSelector} .admin-delete`).forEach(form => form.addEventListener('submit', function (event) {
                    event.preventDefault();
                    const submit = () => form.submit();
                    if (typeof Swal === 'undefined') return confirm(`Hapus ${form.dataset.nama}?`) && submit();
                    Swal.fire({ title: 'Hapus peserta?', text: `${form.dataset.nama} akan dihapus.`, icon: 'warning', showCancelButton: true, confirmButtonColor: '#d85c59', confirmButtonText: 'Ya, hapus', cancelButtonText: 'Batal' }).then(result => result.isConfirmed && submit());
                }));
            };

            bindAdminActions();
        });
    </script>
@endpush
