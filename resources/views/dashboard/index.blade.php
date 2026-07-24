@extends('layouts.app')

@section('title', 'Master Dashboard')

@section('content')

    <style>
        /* ============================================
                                                                                                                       STAT CARDS
                                                                                                                    ============================================ */
        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        @keyframes orbFloat {

            0%,
            100% {
                transform: translate(0, 0) scale(1);
            }

            33% {
                transform: translate(30px, -30px) scale(1.05);
            }

            66% {
                transform: translate(-20px, 20px) scale(0.95);
            }
        }



        /* Glass Card Base */
        .stat-card {
            position: relative;
            border-radius: 24px;
            padding: 0;
            overflow: hidden;
            backdrop-filter: blur(20px) saturate(180%);
            -webkit-backdrop-filter: blur(20px) saturate(180%);
            border: 1px solid rgba(255, 255, 255, 0.18);
            box-shadow:
                0 8px 32px rgba(0, 0, 0, 0.37),
                0 2px 8px rgba(0, 0, 0, 0.2),
                inset 0 1px 0 rgba(255, 255, 255, 0.3);
            transition: transform .35s cubic-bezier(.22, .68, 0, 1.2), box-shadow .35s ease;
            cursor: pointer;
            animation: cardEntrance .6s cubic-bezier(.22, .68, 0, 1.2) both;
        }

        .stat-card:nth-child(1) {
            animation-delay: .05s
        }

        .stat-card:nth-child(2) {
            animation-delay: .15s
        }

        .stat-card:nth-child(3) {
            animation-delay: .25s
        }

        .stat-card:nth-child(4) {
            animation-delay: .35s
        }

        @keyframes cardEntrance {
            from {
                opacity: 0;
                transform: translateY(40px) scale(0.96);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .stat-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow:
                0 24px 60px rgba(0, 0, 0, 0.5),
                0 6px 20px rgba(0, 0, 0, 0.3),
                inset 0 1px 0 rgba(255, 255, 255, 0.4);
        }

        .stat-card--blue {
            background: linear-gradient(145deg, rgba(59, 130, 246, .55), rgba(37, 99, 235, .45), rgba(29, 78, 216, .35));
        }

        .stat-card--green {
            background: linear-gradient(145deg, rgba(16, 185, 129, .55), rgba(5, 150, 105, .45), rgba(4, 120, 87, .35));
        }

        .stat-card--yellow {
            background: linear-gradient(145deg, rgba(251, 191, 36, .6), rgba(245, 158, 11, .5), rgba(217, 119, 6, .4));
        }

        .stat-card--red {
            background: linear-gradient(145deg, rgba(239, 68, 68, .55), rgba(220, 38, 38, .45), rgba(185, 28, 28, .35));
        }

        .stat-card__shine {
            position: absolute;
            inset: 0;
            border-radius: inherit;
            pointer-events: none;
            z-index: 1;
            background: linear-gradient(135deg, rgba(255, 255, 255, .22) 0%, rgba(255, 255, 255, .06) 40%, transparent 60%);
        }

        .stat-card__bg-icon {
            position: absolute;
            right: -10px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 7rem;
            opacity: .12;
            color: #fff;
            pointer-events: none;
            z-index: 0;
            transition: opacity .3s, transform .3s;
            line-height: 1;
        }

        .stat-card:hover .stat-card__bg-icon {
            opacity: .2;
            transform: translateY(-50%) scale(1.1) rotate(-5deg);
        }

        .stat-card__body {
            position: relative;
            z-index: 2;
            padding: 1.6rem 1.6rem 0;
        }

        .stat-card__badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(255, 255, 255, .18);
            border: 1px solid rgba(255, 255, 255, .25);
            color: rgba(255, 255, 255, .9);
            font-size: .7rem;
            font-weight: 600;
            letter-spacing: .08em;
            text-transform: uppercase;
            padding: 4px 10px;
            border-radius: 20px;
            margin-bottom: .8rem;
            backdrop-filter: blur(4px);
        }

        .stat-card__value {
            font-size: 3rem;
            font-weight: 800;
            color: #fff;
            line-height: 1;
            margin-bottom: .3rem;
            text-shadow: 0 2px 12px rgba(0, 0, 0, .3);
            letter-spacing: -1px;
        }

        .stat-card__value sup {
            font-size: 1.2rem;
            font-weight: 600;
            vertical-align: super;
            margin-left: 1px;
        }

        .stat-card__label {
            font-size: .92rem;
            font-weight: 400;
            color: rgba(255, 255, 255, .82);
            margin-bottom: 0;
        }

        .stat-card__footer {
            position: relative;
            z-index: 2;
            margin-top: 1.4rem;
            padding: .85rem 1.6rem;
            background: rgba(0, 0, 0, .18);
            border-top: 1px solid rgba(255, 255, 255, .12);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .stat-card__more-link {
            color: rgba(255, 255, 255, .85);
            text-decoration: none;
            font-size: .82rem;
            font-weight: 600;
            letter-spacing: .04em;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: color .2s, gap .2s;
        }

        .stat-card__more-link:hover {
            color: #fff;
            gap: 10px;
        }

        .stat-card__trend {
            font-size: .75rem;
            font-weight: 600;
            color: rgba(255, 255, 255, .75);
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .stat-card__trend.up {
            color: #86efac;
        }

        .stat-card__trend.down {
            color: #fca5a5;
        }

        .stat-card__progress-bar {
            position: absolute;
            bottom: 0;
            left: 0;
            height: 3px;
            border-radius: 0 2px 2px 0;
            background: rgba(255, 255, 255, .6);
            transition: width 1.2s cubic-bezier(.4, 0, .2, 1);
        }
    </style>

    {{-- ══ CONTENT ══ --}}
    <div class="container-fluid px-3 px-md-4 py-4">

        {{-- ── STATS CARDS ── --}}
        <div class="stats-wrapper mb-4">
            <p class="section-title">
                <i class="fa-solid fa-chart-line fa-sm"></i>
                Dashboard Overview
            </p>
            @php
                $pct = $Persentase_absenmtd;
                $naik = $pct >= 0;

                $pctkronis = $Persentase_absenmtdkronis;
                $naikkronis = $pct >= 0;
            @endphp
            <div class="row g-4">

                {{-- Card 1 --}}
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="stat-card stat-card--blue h-100">
                        <div class="stat-card__shine"></div>
                        <i class="fa-solid fa-clock stat-card__bg-icon"></i>
                        <div class="stat-card__body">
                            {{-- <span class="stat-card__badge"><i class="fa-solid fa-circle fa-xs" style="color:#86efac"></i>
                                    Live</span> --}}
                            <div class="stat-card__value">{{ $Absen_total }}</div>
                            <p class="stat-card__label">Absen Briefing</p>
                        </div>
                        <div class="stat-card__footer">
                            <a href="#" class="stat-card__more-link">Persentase MTD
                                {{-- <i class="fa-solid fa-arrow-right-long fa-sm"></i> --}}
                            </a>
                            <span class="{{ $naik ? 'badge-glass badge-blocked' : 'badge-glass badge-active' }}">
                                <span class="dot"></span>
                                {{ $naik ? '+' : '' }}{{ $pct }}%
                                <i class="fa-solid fa-arrow-trend-{{ $naik ? 'up' : 'down' }} fa-xs"></i>
                            </span>
                        </div>
                        <div class="stat-card__progress-bar" style="width:75%"></div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="stat-card stat-card--red h-100">
                        <div class="stat-card__shine"></div>
                        <i class="fa-solid fa-chart-bar stat-card__bg-icon"></i>
                        <div class="stat-card__body">
                            {{-- <span class="stat-card__badge"><i class="fa-solid fa-circle fa-xs" style="color:#86efac"></i>
                                    Live</span> --}}
                            <div class="stat-card__value">{{ $totalKronis }}</div>
                            <p class="stat-card__label">Pegawai Absen Briefing Kronis</p>
                        </div>
                        <div class="stat-card__footer">
                            <a href="#" class="stat-card__more-link">Persentase MTD
                                {{-- <i class="fa-solid fa-arrow-right-long fa-sm"></i> --}}
                            </a>
                            <span class="{{ $naikkronis ? 'badge-glass badge-blocked' : 'badge-glass badge-active' }}">
                                <span class="dot"></span>
                                {{ $naikkronis ? '+' : '' }}{{ $pctkronis }}%
                                <i class="fa-solid fa-arrow-trend-{{ $naikkronis ? 'up' : 'down' }} fa-xs"></i>
                            </span>
                        </div>
                        <div class="stat-card__progress-bar" style="width:53%"></div>
                    </div>
                </div>

                {{-- <div class="col-12 col-sm-6 col-xl-3">
                    <div class="stat-card stat-card--yellow h-100">
                        <div class="stat-card__shine"></div>
                        <i class="fa-solid fa-user-plus stat-card__bg-icon"></i>
                        <div class="stat-card__body">
                            <span class="stat-card__badge"><i class="fa-solid fa-circle fa-xs" style="color:#86efac"></i>
                                Today</span>
                            <div class="stat-card__value">44</div>
                            <p class="stat-card__label">User Registrations</p>
                        </div>
                        <div class="stat-card__footer">
                            <a href="#" class="stat-card__more-link">More info <i
                                    class="fa-solid fa-arrow-right-long fa-sm"></i></a>
                            <span class="stat-card__trend up"><i class="fa-solid fa-arrow-trend-up fa-xs"></i> +8%</span>
                        </div>
                        <div class="stat-card__progress-bar" style="width:44%"></div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="stat-card stat-card--red h-100">
                        <div class="stat-card__shine"></div>
                        <i class="fa-solid fa-chart-pie stat-card__bg-icon"></i>
                        <div class="stat-card__body">
                            <span class="stat-card__badge"><i class="fa-solid fa-circle fa-xs" style="color:#86efac"></i>
                                Monthly</span>
                            <div class="stat-card__value">65</div>
                            <p class="stat-card__label">Unique Visitors</p>
                        </div>
                        <div class="stat-card__footer">
                            <a href="#" class="stat-card__more-link">More info <i
                                    class="fa-solid fa-arrow-right-long fa-sm"></i></a>
                            <span class="stat-card__trend up"><i class="fa-solid fa-arrow-trend-up fa-xs"></i> +3%</span>
                        </div>
                        <div class="stat-card__progress-bar" style="width:65%"></div>
                    </div>
                </div> --}}

            </div>{{-- /row cards --}}
        </div>{{-- /stats-wrapper --}}

        {{-- ══════════════════════════════════════════ GLASS TABLE — DataTables ══════════════════════════════════════════ --}}
        <div class="row">
            <div class="col-6">
                <div class="glass-card p-3">
                    <div class="glass-toolbar">
                        <div class="glass-toolbar-title">
                            <div class="t-icon"><i class="fa-solid fa-users fa-xs"></i></div>
                            Data Absen Briefing
                        </div>
                        {{-- <div class="d-flex align-items-center gap-2 flex-wrap">
                            <a href="#" class="glass-btn primary"><i class="fa-solid fa-file-export fa-xs"></i>
                                Export</a>
                        </div> --}}
                    </div>
                    <div class="table-responsive">
                        <table id="tblAbsen" class="table table-hover w-100">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Unit Kerja</th>
                                    <th>{{ \Carbon\Carbon::now()->subMonth()->translatedFormat('F Y') }}</th>
                                    <th>{{ \Carbon\Carbon::now()->translatedFormat('F Y') }}</th>
                                    <th>MTD</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($ukerList as $i => $nama_uker)
                                    @php
                                        $now = $DashboardAbsenNow[$nama_uker]->Jumlah ?? 0;
                                        $last = $DashboardAbsenLast[$nama_uker]->Jumlah ?? 0;
                                        $diff = $now - $last;
                                    @endphp
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ $nama_uker }}</td>
                                        <td>
                                            <span
                                                style="font-weight:700;color:rgba(255,255,255,.6)">{{ $last }}</span>
                                            <span style="font-size:.75rem;color:rgba(255,255,255,.35)"> pekerja</span>
                                        </td>
                                        <td>
                                            <span style="font-weight:700;color:#fff">{{ $now }}</span>
                                            <span style="font-size:.75rem;color:rgba(255,255,255,.4)"> pekerja</span>
                                        </td>
                                        <td>
                                            @if ($diff > 0)
                                                <span class="badge-glass badge-blocked"><span class="dot"></span>
                                                    +{{ $diff }}</span>
                                            @elseif ($diff < 0)
                                                <span class="badge-glass badge-active"><span class="dot"></span>
                                                    {{ $diff }}</span>
                                            @else
                                                <span class="badge-glass badge-inactive"><span class="dot"></span>
                                                    Sama</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                {{-- Biarkan tbody kosong, DataTables yang handle empty state --}}
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            <div class="col-6">
                <div class="glass-card p-3">
                    <div class="glass-toolbar">
                        <div class="glass-toolbar-title">
                            <div class="t-icon"><i class="fa-solid fa-book-skull fa-xl"></i></div>
                            Briefing Kronis
                        </div>
                        {{-- <div class="d-flex align-items-center gap-2 flex-wrap">
                            <a href="#" class="glass-btn primary"><i class="fa-solid fa-file-export fa-xs"></i>
                                Export</a>
                        </div> --}}
                    </div>
                    <div class="table-responsive">
                        <table id="tblKronis" class="table table-hover w-100">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama Pegawai</th>
                                    <th>Unit Kerja</th>
                                    <th>{{ \Carbon\Carbon::now()->subMonth()->translatedFormat('F Y') }}</th>
                                    <th>{{ \Carbon\Carbon::now()->translatedFormat('F Y') }}</th>
                                    <th>MTD</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($kronisIds as $i => $id)
                                    @php
                                        $now = $absenKronisNow[$id] ?? null;
                                        $last = $absenKronisLast[$id] ?? null;
                                        $tNow = $now->total_now ?? 0;
                                        $tLast = $last->total_last ?? 0;
                                        $diff = $tNow - $tLast;
                                        $nama = $now->nama_pegawai ?? ($last->nama_pegawai ?? '-');
                                        $uker = $now->nama_uker ?? ($last->nama_uker ?? '-');
                                    @endphp
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>
                                            <div class="u-name">{{ $nama }}</div>
                                        </td>
                                        <td><span
                                                style="font-size:.8rem;color:rgba(255,255,255,.6)">{{ $uker }}</span>
                                        </td>
                                        <td>
                                            <span
                                                style="font-weight:700;color:rgba(255,255,255,.6)">{{ $tLast }}</span>
                                            <span style="font-size:.72rem;color:rgba(255,255,255,.35)">x</span>
                                        </td>
                                        <td>
                                            <span style="font-weight:700;color:#fff">{{ $tNow }}</span>
                                            <span style="font-size:.72rem;color:rgba(255,255,255,.4)">x</span>
                                        </td>
                                        <td>
                                            @if ($diff > 0)
                                                <span class="badge-glass badge-blocked"><span class="dot"></span>
                                                    +{{ $diff }}</span>
                                            @elseif ($diff < 0)
                                                <span class="badge-glass badge-active"><span class="dot"></span>
                                                    {{ $diff }}</span>
                                            @else
                                                <span class="badge-glass badge-inactive"><span class="dot"></span>
                                                    Sama</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>{{-- /container-fluid --}}

@endsection

{{-- ══════════════════════════════════════════════
     SCRIPTS — load after Bootstrap JS
══════════════════════════════════════════════ --}}
{{-- Ganti section scripts --}}
@push('scripts')
    <script>
        $(function() {

            /* ── 1. Progress bars ── */
            document.querySelectorAll('.stat-card__progress-bar').forEach(bar => {
                const target = bar.style.width;
                bar.style.width = '0%';
                setTimeout(() => bar.style.width = target, 400);
            });

            /* ── 2. 3D tilt ── */
            document.querySelectorAll('.stat-card').forEach(card => {
                card.addEventListener('mousemove', e => {
                    const r = card.getBoundingClientRect();
                    const rx = ((e.clientY - r.top - r.height / 2) / (r.height / 2)) * -6;
                    const ry = ((e.clientX - r.left - r.width / 2) / (r.width / 2)) * 6;
                    card.style.transform =
                        `translateY(-8px) scale(1.02) rotateX(${rx}deg) rotateY(${ry}deg)`;
                });
                card.addEventListener('mouseleave', () => card.style.transform = '');
            });

            /* ── 3. DataTables — inisialisasi manual per tabel ── */
            // Tabel Absen Briefing (5 kolom: #, Unit Kerja, Bln Lalu, Bln Ini, MTD)
            $('#tblAbsen').DataTable({
                paging: false,
                info: false,
                searching: false,
                ordering: true,
                columnDefs: [{
                    orderable: false,
                    targets: [0, 4]
                }],
                language: {
                    emptyTable: `
            <div style="padding:2rem;color:rgba(255,255,255,.35);">
                Belum ada data absen briefing
            </div>
        `
                }
            });

            $('#tblKronis').DataTable({
                paging: false,
                info: false,
                searching: false,
                ordering: true,
                columnDefs: [{
                    orderable: false,
                    targets: [0, 5]
                }],
                language: {
                    emptyTable: `
            <div style="padding:2rem;color:rgba(255,255,255,.35);">
                Tidak ada pegawai dengan absen ≥ 3x bulan ini
            </div>
        `
                }
            });

        });
    </script>
@endpush
