@extends('layouts.app-public')

@section('title', 'Absen Briefing')

@section('content')
    @if ($errors->any())
        <div style="position:fixed;top:1rem;left:50%;transform:translateX(-50%);z-index:9999;max-width:500px;width:90%">
            <div
                style="background:rgba(239,68,68,.18);border:1px solid rgba(239,68,68,.35);border-radius:14px;
                    padding:.85rem 1rem;color:#fca5a5;font-size:.82rem;backdrop-filter:blur(16px)">
                <div style="font-weight:600;margin-bottom:.3rem;display:flex;align-items:center;gap:7px">
                    <i class="fa-solid fa-circle-exclamation"></i> Form belum lengkap
                </div>
                @foreach ($errors->all() as $e)
                    <div>• {{ $e }}</div>
                @endforeach
            </div>
        </div>
    @endif
    <div class="page-wrap">

        {{-- ══ HEADER ══ --}}
        <div class="header-card">
            <div class="header-brand">
                <div class="header-icon">
                    <i class="fa-solid fa-calendar-check"></i>
                </div>
                <div>
                    <div class="header-title">Absen Briefing</div>
                    <div class="header-sub">BO Palembang Sriwijaya</div>
                </div>
            </div>
            <div class="header-notice">
                <i class="fa-solid fa-triangle-exclamation mt-1 flex-shrink-0"></i>
                <span>Hanya input pekerja yang <strong>TIDAK MASUK</strong> — Telat / Izin / Alpha / Cuti</span>
            </div>
        </div>

        <form method="POST" action="{{ route('submit.absen') }}" id="absenForm" enctype="multipart/form-data" novalidate>
            @csrf

            {{-- ══ PILIH UKER ══ --}}
            <div class="glass-card" style="animation-delay:.08s">
                <div class="glass-card-header">
                    <div class="card-icon card-icon-purple">
                        <i class="fa-solid fa-building fa-xl"></i>
                    </div>
                    <div>
                        <div class="card-label">Pilih Unit Kerja</div>
                        <div class="card-label-sub">Wajib dipilih sebelum input pegawai</div>
                    </div>
                </div>
                <div class="glass-card-body">
                    <select name="uker" id="uker" class="form-control select2" style="width:100%">
                        <option value="">Cari Unit Kerja…</option>
                        @foreach ($uker as $datas)
                            <option value="{{ $datas->id }}">
                                {{ $datas->kode_uker }} - {{ $datas->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- ══ PILIH PEGAWAI ══ --}}
            <div class="glass-card" style="animation-delay:.16s">
                <div class="glass-card-header">
                    <div class="card-icon card-icon-green">
                        <i class="fa-solid fa-users fa-xl"></i>
                    </div>
                    <div>
                        <div class="card-label">Pegawai Tidak Masuk</div>
                        <div class="card-label-sub">Klik tombol di bawah untuk input</div>
                    </div>
                </div>
                <div class="glass-card-body">

                    {{-- Summary --}}
                    <div id="summaryPill" class="summary-pill empty" style="margin-bottom:.8rem">
                        <span class="dot"></span>
                        <span id="summaryText">Belum ada pegawai dipilih</span>
                    </div>

                    {{-- Trigger Modal --}}
                    <a class="btn-open-modal disabled" id="openModalBtn" data-bs-toggle="modal" href="#modalAbsen"
                        role="button">
                        <i class="fa-solid fa-user-plus fa-sm"></i>
                        Input Pegawai Tidak Masuk
                    </a>
                </div>
            </div>

            {{-- ══ MODAL ══ --}}
            <div class="modal fade" id="modalAbsen" aria-hidden="true" tabindex="-1">
                <div class="modal-dialog modal-fullscreen-sm-down modal-dialog-centered">
                    <div class="modal-content">

                        <div class="modal-header">
                            <div class="modal-title">
                                <i class="fa-solid fa-table-list fa-sm"></i>
                                Input Absensi Briefing
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <div class="table-responsive">
                                <table class="absen-table">
                                    <thead>
                                        <tr>
                                            <th style="width:70px">Aksi</th>
                                            <th>Pegawai</th>
                                            <th style="width:130px">Alasan</th>
                                        </tr>
                                    </thead>
                                    <tbody id="absenRows">
                                        <tr class="absen-row">
                                            <td>
                                                <div class="d-flex gap-1">
                                                    <button type="button" class="btn-add-row btn-add" title="Tambah">
                                                        <i class="fa-solid fa-plus fa-xs"></i>
                                                    </button>
                                                    <button type="button" class="btn-rem-row btn-remove" title="Hapus"
                                                        disabled>
                                                        <i class="fa-solid fa-xmark fa-xs"></i>
                                                    </button>
                                                </div>
                                            </td>
                                            <td>
                                                <select name="pegawai_id[]" class="form-control select2 pegawai-select"
                                                    style="width:100%">
                                                    <option value="">Pilih Uker dulu</option>
                                                </select>
                                            </td>
                                            <td>
                                                <select name="alasan[]" class="form-control select2 alasan-select"
                                                    style="width:100%">
                                                    <option value="">Alasan</option>
                                                    <option value="Izin">Izin</option>
                                                    <option value="Cuti">Cuti</option>
                                                    <option value="Telat">Telat</option>
                                                    <option value="Absen">Absen</option>
                                                </select>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="modal-footer justify-content-between">
                            <span style="font-size:.75rem;color:rgba(255,255,255,.4)">
                                <i class="fa-solid fa-circle-info fa-xs me-1"></i>
                                Klik + untuk tambah baris
                            </span>
                            <button type="button" class="btn-done" data-bs-dismiss="modal">
                                <i class="fa-solid fa-check fa-xs me-1"></i> Selesai
                            </button>
                        </div>

                    </div>
                </div>
            </div>

            {{-- Template row (hidden) --}}
            <table class="d-none">
                <tbody>
                    <tr id="rowTemplate" class="absen-row">
                        <td>
                            <div class="d-flex gap-1">
                                <button type="button" class="btn-add-row btn-add" title="Tambah">
                                    <i class="fa-solid fa-plus fa-xs"></i>
                                </button>
                                <button type="button" class="btn-rem-row btn-remove" title="Hapus">
                                    <i class="fa-solid fa-xmark fa-xs"></i>
                                </button>
                            </div>
                        </td>
                        <td>
                            <select name="pegawai_id[]" class="form-control select2 pegawai-select" style="width:100%"
                                required>
                                <option value="">Pilih Uker dulu</option>
                            </select>
                        </td>
                        <td>
                            <select name="alasan[]" class="form-control select2 alasan-select" style="width:100%"
                                required>
                                <option value="">Alasan</option>
                                <option value="Izin">Izin</option>
                                <option value="Cuti">Cuti</option>
                                <option value="Telat">Telat</option>
                                <option value="Absen">Absen</option>
                            </select>
                        </td>
                    </tr>
                </tbody>
            </table>

            {{-- ══ SAVE BAR ══ --}}
            <div class="save-bar">
                <div class="inner">
                    <button type="submit" class="btn-save">
                        <i class="fa-solid fa-floppy-disk fa-xl"></i>
                        Simpan Absensi Briefing
                    </button>
                    <div class="save-note">
                        <i class="fa-solid fa-headset fa-sm"></i>
                        Terkendala? Hubungi IT terkait
                    </div>
                </div>
            </div>

        </form>
    </div>

@endsection

@push('scripts')
    <script>
        $(function() {

            const $modal = $('#modalAbsen');
            const $uker = $('#uker');
            const $tbody = $('#absenRows');
            const $openBtn = $('#openModalBtn');

            let cachedPegawai = [];

            /* ── Summary pill update ── */
            function updateSummary() {
                const total = $tbody.find('tr.absen-row').length;
                const filled = $tbody.find('tr.absen-row').filter(function() {
                    return $(this).find('.pegawai-select').val() &&
                        $(this).find('.alasan-select').val();
                }).length;

                const $pill = $('#summaryPill');
                const $txt = $('#summaryText');

                if (filled > 0) {
                    $pill.removeClass('empty');
                    $txt.text(filled + ' pegawai sudah diisi' + (total > filled ? ' (' + (total - filled) +
                        ' belum lengkap)' : ''));
                } else {
                    $pill.addClass('empty');
                    $txt.text('Belum ada pegawai dipilih');
                }
            }

            /* ── Enable/disable modal button ── */
            function setOpenBtnEnabled(on) {
                $openBtn.toggleClass('disabled', !on);
            }

            /* ── Init select2 ── */
            function initSelect2($el) {
                if ($el.data('select2')) $el.select2('destroy');
                $el.select2({
                    dropdownParent: $modal,
                    width: '100%'
                });
            }

            /* ── Build pegawai options ── */
            function buildPegawaiOptions($select, selectedValue = null) {
                $select.empty().append(new Option('Cari Pegawai…', '', false, false));
                cachedPegawai.forEach(p => {
                    const sel = String(p.id) === String(selectedValue);
                    $select.append(new Option(p.nama, p.id, false, sel));
                });
            }

            /* ── Refresh all pegawai selects ── */
            function refreshAllPegawaiSelects() {
                $modal.find('.pegawai-select').each(function() {
                    const $sel = $(this);
                    const prev = $sel.val();
                    if ($sel.data('select2')) $sel.select2('destroy');
                    buildPegawaiOptions($sel, prev);
                    initSelect2($sel);
                });
            }

            /* ── Renumber & toggle remove btn ── */
            function renumber() {
                const onlyOne = $tbody.find('tr.absen-row').length === 1;
                $tbody.find('.btn-remove').prop('disabled', onlyOne);
                updateSummary();
            }

            /* ── Fetch pegawai by uker ── */
            function fetchPegawaiByUker(ukerId) {
                if (!ukerId) {
                    cachedPegawai = [];
                    refreshAllPegawaiSelects();
                    return;
                }
                $.getJSON(`/pegawai/by-unit/${ukerId}`)
                    .done(data => {
                        cachedPegawai = data || [];
                        refreshAllPegawaiSelects();
                    })
                    .fail(() => {
                        cachedPegawai = [];
                        refreshAllPegawaiSelects();
                    });
            }

            /* ── Add row ── */
            function addRow() {
                const $newRow = $('#rowTemplate').clone().removeAttr('id');
                $newRow.find('.alasan-select, .pegawai-select').val('');
                $tbody.append($newRow);

                $newRow.find('.alasan-select').each(function() {
                    initSelect2($(this));
                });
                $newRow.find('.pegawai-select').each(function() {
                    buildPegawaiOptions($(this), null);
                    initSelect2($(this));
                });
                renumber();
            }

            /* ── Init uker select2 ── */
            $uker.select2({
                width: '100%'
            });
            setOpenBtnEnabled(!!$uker.val());

            $uker.on('change', function() {
                const ukerId = $(this).val();
                setOpenBtnEnabled(!!ukerId);
                $tbody.find('tr.absen-row:gt(0)').remove();
                renumber();
                fetchPegawaiByUker(ukerId);
            });

            $modal.on('hidden.bs.modal', updateSummary);

            /* ── Modal shown ── */
            $modal.on('shown.bs.modal', function() {
                const ukerId = $uker.val();
                if (!ukerId) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Pilih Unit Kerja Dulu!',
                        text: 'Silakan pilih unit kerja sebelum input pegawai.',
                        confirmButtonColor: '#6366f1',
                        background: '#1a1a4e',
                        color: '#fff'
                    });
                    bootstrap.Modal.getInstance(this)?.hide();
                    return;
                }
                $modal.find('.alasan-select').each(function() {
                    if (!$(this).data('select2')) initSelect2($(this));
                });
                if (cachedPegawai.length === 0) fetchPegawaiByUker(ukerId);
                else refreshAllPegawaiSelects();
                renumber();
            });

            /* ── Modal hidden → update summary ── */
            $modal.on('hidden.bs.modal', updateSummary);

            /* ── Add/Remove row ── */
            $tbody.on('click', '.btn-add', addRow);
            $tbody.on('click', '.btn-remove', function() {
                const $row = $(this).closest('tr');
                $row.find('select').each(function() {
                    if ($(this).data('select2')) $(this).select2('destroy');
                });
                $row.remove();
                renumber();
            });

            /* ── Update summary saat nilai berubah ── */
            $tbody.on('change', '.pegawai-select, .alasan-select', updateSummary);

            /* ── Form validation ── */
            $('#absenForm').on('submit', function(e) {
                let ok = true;
                $modal.find('.pegawai-select, .alasan-select').each(function() {
                    $(this).next('.select2-container').find('.select2-selection').removeClass(
                        'is-invalid');
                });
                $('#absenRows tr.absen-row').each(function() {
                    const $peg = $(this).find('.pegawai-select');
                    const $als = $(this).find('.alasan-select');
                    if (!$peg.val()) {
                        ok = false;
                        $peg.next('.select2-container').find('.select2-selection').addClass(
                            'is-invalid');
                    }
                    if (!$als.val()) {
                        ok = false;
                        $als.next('.select2-container').find('.select2-selection').addClass(
                            'is-invalid');
                    }
                });
                if (!ok) {
                    e.preventDefault();
                    bootstrap.Modal.getOrCreateInstance($modal[0]).show();
                }
            });

            $modal.on('change', '.pegawai-select, .alasan-select', function() {
                $(this).next('.select2-container').find('.select2-selection').removeClass('is-invalid');
            });
        });
    </script>

    {{-- SweetAlert flash --}}
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: @json(session('success')),
                timer: 5000,
                timerProgressBar: true,
                showConfirmButton: false,
                background: '#1a1a4e',
                color: '#fff',
                iconColor: '#34d399'
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: @json(session('error')),
                confirmButtonText: 'Oke',
                confirmButtonColor: '#6366f1',
                background: '#1a1a4e',
                color: '#fff'
            });
        </script>
    @endif

    @if ($errors->any())
        <script>
            Swal.fire({
                icon: 'warning',
                title: 'Form Belum Lengkap',
                html: @json($errors->all()).map(e => `• ${e}`).join('<br>'),
                confirmButtonText: 'Perbaiki',
                confirmButtonColor: '#6366f1',
                background: '#1a1a4e',
                color: '#fff'
            });
        </script>
    @endif
@endpush
