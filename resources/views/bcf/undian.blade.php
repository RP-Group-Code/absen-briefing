@extends('layouts.app-public')

@section('title', 'BCF Undian')
@section('description', 'Portal undian BCF BO Sriwijaya.')

@push('styles')
    <style>
        :root {
            --undi-navy: #0d3f91;
            --undi-blue: #1560d8;
            --undi-cyan: #5dc9ea;
            --undi-gold: #ffcf40;
            --undi-ink: #16304d;
            --undi-muted: #70819b;
            --undi-soft: #eef5ff;
            --undi-line: #d9e7f6;
        }

        body {
            background:
                radial-gradient(circle at top left, rgba(93, 201, 234, .18), transparent 32%),
                linear-gradient(180deg, #f6fbff 0%, #eef5ff 100%) !important;
            color: var(--undi-ink);
            padding-bottom: 18px !important;
        }

        .undi-shell {
            width: min(1600px, calc(100% - 16px));
            margin: 8px auto 0;
        }

        .undi-hero {
            background: linear-gradient(135deg, rgba(13, 63, 145, .97), rgba(21, 96, 216, .92));
            color: #fff;
            border-radius: 22px;
            padding: 24px 28px;
            box-shadow: 0 24px 60px rgba(9, 47, 108, .22);
            position: relative;
            overflow: hidden;
        }

        .undi-hero::after {
            content: '';
            position: absolute;
            inset: auto -80px -80px auto;
            width: 280px;
            height: 280px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255,255,255,.16) 0%, transparent 70%);
        }

        .undi-kicker {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            color: rgba(255,255,255,.82);
            font-size: .82rem;
            font-weight: 800;
            letter-spacing: .14em;
            text-transform: uppercase;
            margin-bottom: 12px;
        }

        .undi-title {
            font-size: clamp(2rem, 4vw, 3.2rem);
            line-height: .96;
            font-weight: 900;
            letter-spacing: -.04em;
            margin: 0;
            max-width: 820px;
        }

        .undi-subtitle {
            max-width: 760px;
            margin: 14px 0 0;
            color: rgba(255,255,255,.74);
            font-size: .98rem;
        }

        .undi-nav {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 18px;
        }

        .undi-nav a, .undi-admin-back {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border-radius: 12px;
            padding: 12px 14px;
            text-decoration: none;
            font-weight: 800;
            letter-spacing: .02em;
        }

        .undi-nav a {
            background: rgba(255,255,255,.12);
            border: 1px solid rgba(255,255,255,.18);
            color: #fff;
        }

        .undi-admin-back {
            background: #fff;
            color: var(--undi-navy);
            margin-top: 18px;
        }

        .undi-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            margin-top: 12px;
        }

        .undi-stat {
            background: #fff;
            border: 1px solid var(--undi-line);
            border-radius: 20px;
            padding: 18px 20px;
            box-shadow: 0 14px 34px rgba(18, 59, 108, .08);
        }

        .undi-stat small {
            display: block;
            color: var(--undi-muted);
            font-size: .75rem;
            font-weight: 800;
            letter-spacing: .08em;
            text-transform: uppercase;
            margin-bottom: 10px;
        }

        .undi-stat strong {
            display: block;
            color: var(--undi-blue);
            font-size: 2rem;
            line-height: 1;
            font-weight: 900;
        }

        .undi-content {
            display: grid;
            grid-template-columns: 1.15fr .85fr;
            gap: 14px;
            margin-top: 12px;
            align-items: start;
        }

        .undi-card {
            background: #fff;
            border: 1px solid var(--undi-line);
            border-radius: 20px;
            box-shadow: 0 16px 40px rgba(18, 59, 108, .08);
            overflow: hidden;
        }

        .undi-card-head {
            padding: 18px 20px 12px;
            border-bottom: 1px solid #edf3fb;
        }

        .undi-card-head h2 {
            margin: 0;
            font-size: 1.4rem;
            font-weight: 900;
            color: var(--undi-navy);
        }

        .undi-card-head p {
            margin: 7px 0 0;
            color: var(--undi-muted);
        }

        .undi-card-body {
            padding: 18px 20px 20px;
        }

        .undi-section {
            scroll-margin-top: 22px;
            margin-top: 12px;
        }

        .undi-form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }

        .undi-form-grid .full {
            grid-column: 1 / -1;
        }

        .undi-label {
            display: block;
            margin-bottom: 6px;
            color: var(--undi-navy);
            font-size: .8rem;
            font-weight: 800;
            letter-spacing: .04em;
            text-transform: uppercase;
        }

        .undi-input, .undi-select, .undi-textarea {
            width: 100%;
            border: 1px solid #d6e3f2;
            border-radius: 14px;
            padding: 12px 14px;
            font-size: .92rem;
            color: var(--undi-ink);
            background: #fff;
            outline: none;
        }

        .undi-textarea {
            min-height: 110px;
            resize: vertical;
        }

        .undi-input:focus, .undi-select:focus, .undi-textarea:focus {
            border-color: var(--undi-cyan);
            box-shadow: 0 0 0 4px rgba(93, 201, 234, .16);
        }

        .undi-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 14px;
        }

        .undi-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-height: 46px;
            border: 0;
            border-radius: 14px;
            padding: 0 18px;
            font-weight: 800;
            text-decoration: none;
            cursor: pointer;
        }

        .undi-btn-primary { background: var(--undi-blue); color: #fff; }
        .undi-btn-accent { background: var(--undi-gold); color: #3f2f00; }
        .undi-btn-light { background: #fff; color: var(--undi-navy); border: 1px solid #d6e3f2; }

        .undi-stack {
            display: grid;
            gap: 14px;
        }

        .undi-winner-stage {
            background:
                radial-gradient(circle at top center, rgba(255, 207, 64, .2), transparent 34%),
                linear-gradient(180deg, #0f438f 0%, #1a66da 100%);
            color: #fff;
            border-radius: 24px;
            padding: 28px;
            min-height: 360px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
        }

        .undi-winner-stage::after {
            content: '';
            position: absolute;
            inset: auto -40px -60px auto;
            width: 220px;
            height: 220px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255,255,255,.18), transparent 72%);
        }

        .undi-winner-stage small {
            color: rgba(255,255,255,.72);
            font-size: .78rem;
            font-weight: 800;
            letter-spacing: .1em;
            text-transform: uppercase;
        }

        .undi-winner-stage h3 {
            margin: 14px 0 8px;
            font-size: 1.8rem;
            font-weight: 900;
        }

        .undi-winner-stage p {
            margin: 0;
            color: rgba(255,255,255,.78);
        }

        .undi-winner-panel {
            background: rgba(255,255,255,.12);
            border: 1px solid rgba(255,255,255,.14);
            border-radius: 20px;
            padding: 18px;
            backdrop-filter: blur(12px);
        }

        .undi-winner-name {
            font-size: 2rem;
            line-height: 1;
            font-weight: 900;
            margin: 0 0 8px;
        }

        .undi-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border-radius: 999px;
            background: rgba(255,255,255,.14);
            padding: 8px 12px;
            font-size: .78rem;
            font-weight: 800;
        }

        .undi-table-wrap {
            overflow-x: auto;
            border: 1px solid #ebf1f8;
            border-radius: 18px;
        }

        .undi-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 760px;
        }

        .undi-table th {
            background: #f3f8fd;
            color: var(--undi-muted);
            padding: 12px 14px;
            font-size: .72rem;
            font-weight: 900;
            letter-spacing: .08em;
            text-transform: uppercase;
        }

        .undi-table td {
            padding: 14px;
            border-top: 1px solid #edf3fb;
            color: var(--undi-ink);
            vertical-align: top;
        }

        .undi-table strong {
            color: var(--undi-navy);
        }

        .undi-pagination {
            display: flex;
            justify-content: flex-end;
            margin-top: 14px;
        }

        .undi-pagination .pagination {
            margin: 0;
            gap: 4px;
        }

        .undi-pagination .page-link {
            border-radius: 10px;
            border: 1px solid #d6e3f2;
            color: var(--undi-navy);
            font-size: .78rem;
        }

        .undi-pagination .page-item.active .page-link {
            background: var(--undi-blue);
            border-color: var(--undi-blue);
        }

        .undi-help {
            margin-top: 12px;
            color: var(--undi-muted);
            font-size: .82rem;
        }

        @media (max-width: 1100px) {
            .undi-shell {
                width: min(100% - 12px, 1600px);
                margin-top: 6px;
            }

            .undi-hero {
                padding: 20px 18px;
            }

            .undi-grid { grid-template-columns: repeat(2, 1fr); }
            .undi-content { grid-template-columns: 1fr; }
        }
    </style>
@endpush

@section('content')
    <main class="undi-shell">
        <section class="undi-hero">
            <span class="undi-kicker"><i class="fa-solid fa-star"></i> UNDIAN BCF BO SRIWIJAYA</span>
            <h1 class="undi-title">UNDIAN BCF BO SRIWIJAYA</h1>
            <p class="undi-subtitle">Portal desktop-first untuk mengelola peserta, hadiah, undian, pemenang, dan rekap akhir dengan nuansa warna BCF yang seragam.</p>

            <nav class="undi-nav">
                <a href="#dashboard"><i class="fa-solid fa-chart-line"></i> Dashboard</a>
                <a href="#hadiah"><i class="fa-solid fa-gift"></i> Hadiah</a>
                <a href="#peserta"><i class="fa-solid fa-users"></i> Peserta</a>
                <a href="{{ route('bcf.undian.live') }}"><i class="fa-solid fa-wand-magic-sparkles"></i> Undian</a>
                <a href="#pemenang"><i class="fa-solid fa-trophy"></i> Pemenang</a>
                <a href="#rekap"><i class="fa-solid fa-file-export"></i> Rekap</a>
            </nav>

            <a href="{{ route('bcf.registrasi.admin') }}" class="undi-admin-back"><i class="fa-solid fa-arrow-left"></i> Kembali ke Portal Admin</a>
        </section>

        <section id="dashboard" class="undi-section undi-grid">
            <article class="undi-stat"><small>Total Peserta</small><strong>{{ $dashboard['total_peserta'] }}</strong></article>
            <article class="undi-stat"><small>Peserta Aktif</small><strong>{{ $dashboard['peserta_aktif'] }}</strong></article>
            <article class="undi-stat"><small>Total Hadiah</small><strong>{{ $dashboard['total_hadiah'] }}</strong></article>
            <article class="undi-stat"><small>Sisa Hadiah</small><strong>{{ $dashboard['sisa_hadiah'] }}</strong></article>
        </section>

        <div class="undi-content">
            <div class="undi-stack">
                <section id="hadiah" class="undi-card undi-section">
                    <div class="undi-card-head">
                        <h2>Hadiah</h2>
                        <p>Input manual atau import file Excel/CSV untuk stok hadiah undian.</p>
                    </div>
                    <div class="undi-card-body">
                        <div class="undi-form-grid">
                            <form class="full" method="POST" action="{{ route('bcf.undian.hadiah.store') }}">
                                @csrf
                                <div class="undi-form-grid">
                                    <div>
                                        <label class="undi-label">Nama Hadiah</label>
                                        <input class="undi-input" name="nama_hadiah" required>
                                    </div>
                                    <div>
                                        <label class="undi-label">Kategori</label>
                                        <input class="undi-input" name="kategori">
                                    </div>
                                    <div>
                                        <label class="undi-label">Jumlah</label>
                                        <input class="undi-input" type="number" min="1" name="stock_total" value="1" required>
                                    </div>
                                    <div class="full">
                                        <label class="undi-label">Deskripsi</label>
                                        <textarea class="undi-textarea" name="deskripsi"></textarea>
                                    </div>
                                </div>
                                <div class="undi-actions">
                                    <button class="undi-btn undi-btn-primary" type="submit"><i class="fa-solid fa-plus"></i> Simpan Hadiah</button>
                                </div>
                            </form>

                            <form class="full" method="POST" action="{{ route('bcf.undian.hadiah.import') }}" enctype="multipart/form-data">
                                @csrf
                                <label class="undi-label">Import Hadiah dari Excel</label>
                                <input class="undi-input" type="file" name="file" accept=".xlsx,.xls,.csv" required>
                                <p class="undi-help">Header yang didukung: `nama_hadiah/hadiah`, `kategori`, `qty/jumlah`, `deskripsi`.</p>
                                <div class="undi-actions">
                                    <button class="undi-btn undi-btn-light" type="submit"><i class="fa-solid fa-file-arrow-up"></i> Import Hadiah</button>
                                </div>
                            </form>
                        </div>

                        <div class="undi-table-wrap" style="margin-top:18px;">
                            <table class="undi-table">
                                <thead>
                                    <tr><th>Hadiah</th><th>Kategori</th><th>Stok</th><th>Status</th></tr>
                                </thead>
                                <tbody>
                                    @forelse ($hadiah as $row)
                                        <tr>
                                            <td><strong>{{ $row->nama_hadiah }}</strong><br><span style="color:var(--undi-muted)">{{ $row->deskripsi ?: '-' }}</span></td>
                                            <td>{{ $row->kategori ?: '-' }}</td>
                                            <td>{{ $row->stock_sisa }}/{{ $row->stock_total }}</td>
                                            <td>{{ $row->is_active ? 'Aktif' : 'Nonaktif' }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="4">Belum ada hadiah.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        @if ($hadiah->hasPages())
                            <div class="undi-pagination">{{ $hadiah->fragment('hadiah')->links('pagination::bootstrap-5') }}</div>
                        @endif
                    </div>
                </section>

                <section id="peserta" class="undi-card undi-section">
                    <div class="undi-card-head">
                        <h2>Peserta</h2>
                        <p>Kelola daftar peserta undian dari input manual atau import Excel/CSV.</p>
                    </div>
                    <div class="undi-card-body">
                        <form method="POST" action="{{ route('bcf.undian.peserta.store') }}">
                            @csrf
                            <div class="undi-form-grid">
                                <div>
                                    <label class="undi-label">Nama Peserta</label>
                                    <input class="undi-input" name="nama" required>
                                </div>
                                <div>
                                    <label class="undi-label">PN</label>
                                    <input class="undi-input" name="pn">
                                </div>
                                <div>
                                    <label class="undi-label">Unit Kerja</label>
                                    <input class="undi-input" name="unit_kerja">
                                </div>
                                <div class="full">
                                    <label class="undi-label">Keterangan</label>
                                    <textarea class="undi-textarea" name="keterangan"></textarea>
                                </div>
                            </div>
                            <div class="undi-actions">
                                <button class="undi-btn undi-btn-primary" type="submit"><i class="fa-solid fa-user-plus"></i> Simpan Peserta</button>
                            </div>
                        </form>

                        <form method="POST" action="{{ route('bcf.undian.peserta.import') }}" enctype="multipart/form-data" style="margin-top:18px;">
                            @csrf
                            <label class="undi-label">Import Peserta dari Excel</label>
                            <input class="undi-input" type="file" name="file" accept=".xlsx,.xls,.csv" required>
                            <p class="undi-help">Header yang didukung: `nama`, `pn`, `unit_kerja/uker`, `keterangan`.</p>
                            <div class="undi-actions">
                                <button class="undi-btn undi-btn-light" type="submit"><i class="fa-solid fa-file-arrow-up"></i> Import Peserta</button>
                            </div>
                        </form>

                        <div class="undi-table-wrap" style="margin-top:18px;">
                            <table class="undi-table">
                                <thead>
                                    <tr><th>Nama</th><th>PN</th><th>Unit Kerja</th><th>Status</th></tr>
                                </thead>
                                <tbody>
                                    @forelse ($peserta as $row)
                                        <tr>
                                            <td><strong>{{ $row->nama }}</strong><br><span style="color:var(--undi-muted)">{{ $row->keterangan ?: '-' }}</span></td>
                                            <td>{{ $row->pn ?: '-' }}</td>
                                            <td>{{ $row->unit_kerja ?: '-' }}</td>
                                            <td>{{ $row->is_active ? 'Aktif' : 'Nonaktif' }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="4">Belum ada peserta.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        @if ($peserta->hasPages())
                            <div class="undi-pagination">{{ $peserta->fragment('peserta')->links('pagination::bootstrap-5') }}</div>
                        @endif
                    </div>
                </section>
            </div>

            <div class="undi-stack">
                <section id="undian" class="undi-card undi-section">
                    <div class="undi-card-head">
                        <h2>Undian</h2>
                        <p>Laman undian sekarang dibuat terpisah agar lebih fokus untuk layar utama pengocokan dan pengumuman pemenang.</p>
                    </div>
                    <div class="undi-card-body">
                        <div class="undi-winner-stage">
                            <div>
                                <small>Laman Undian Live</small>
                                <h3>Buka layar undian terpisah untuk mode panggung</h3>
                                <p>Tampilan live memakai tema khusus desktop, animasi nama peserta, dan kontrol undi yang terhubung langsung ke database.</p>
                            </div>

                            <div class="undi-winner-panel">
                                @php $winner = session('undian_winner'); @endphp
                                <small style="display:block;color:rgba(255,255,255,.72);font-weight:800;letter-spacing:.08em;text-transform:uppercase;">Highlight Pemenang</small>
                                <div class="undi-winner-name">{{ $winner['peserta'] ?? $recentWinner?->peserta?->nama ?? 'Belum ada pemenang' }}</div>
                                <p style="margin-bottom:12px;">{{ $winner['hadiah'] ?? $recentWinner?->hadiah?->nama_hadiah ?? 'Silakan mulai undian pertama.' }}</p>
                                <div class="undi-badge"><i class="fa-solid fa-ticket"></i> Undian ke-{{ $winner['undian_ke'] ?? $recentWinner?->undian_ke ?? 0 }}</div>
                            </div>
                        </div>

                        <div class="undi-actions" style="margin-top:18px;">
                            <a class="undi-btn undi-btn-accent" href="{{ route('bcf.undian.live') }}"><i class="fa-solid fa-up-right-from-square"></i> Buka Laman Undian</a>
                        </div>
                        <p class="undi-help">Peserta tersedia saat ini: <strong>{{ $pesertaTersedia }}</strong> orang, hadiah tersedia: <strong>{{ $hadiahTersedia->count() }}</strong> item aktif.</p>
                    </div>
                </section>

                <section id="pemenang" class="undi-card undi-section">
                    <div class="undi-card-head">
                        <h2>Pemenang</h2>
                        <p>Rekap data pemenang beserta hadiah yang didapatkan.</p>
                    </div>
                    <div class="undi-card-body">
                        <div class="undi-table-wrap">
                            <table class="undi-table">
                                <thead>
                                    <tr><th>Undian</th><th>Peserta</th><th>Hadiah</th><th>Waktu</th></tr>
                                </thead>
                                <tbody>
                                    @forelse ($pemenang as $row)
                                        <tr>
                                            <td>#{{ $row->undian_ke }}</td>
                                            <td><strong>{{ $row->peserta?->nama }}</strong><br><span style="color:var(--undi-muted)">{{ $row->peserta?->pn ?: '-' }}</span></td>
                                            <td><strong>{{ $row->hadiah?->nama_hadiah }}</strong><br><span style="color:var(--undi-muted)">{{ $row->hadiah?->kategori ?: '-' }}</span></td>
                                            <td>{{ optional($row->won_at)->format('d M Y H:i') }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="4">Belum ada data pemenang.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        @if ($pemenang->hasPages())
                            <div class="undi-pagination">{{ $pemenang->fragment('pemenang')->links('pagination::bootstrap-5') }}</div>
                        @endif
                    </div>
                </section>

                <section id="rekap" class="undi-card undi-section">
                    <div class="undi-card-head">
                        <h2>Rekap</h2>
                        <p>Kumpulan data akhir undian yang bisa diexport ke Excel.</p>
                    </div>
                    <div class="undi-card-body">
                        <div class="undi-actions">
                            <a href="{{ route('bcf.undian.rekap.export') }}" class="undi-btn undi-btn-primary"><i class="fa-solid fa-file-excel"></i> Export Rekap Excel</a>
                        </div>
                        <p class="undi-help">Export berisi data undian ke-, nama peserta, PN, unit kerja, hadiah, kategori hadiah, waktu menang, dan catatan.</p>
                    </div>
                </section>
            </div>
        </div>
    </main>
@endsection
