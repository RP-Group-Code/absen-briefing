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

        .live-winner-name-viewport {
            position: relative;
            margin: 18px 0 8px;
            min-height: clamp(6.4rem, 11vw, 8rem);
            overflow: hidden;
        }

        .live-winner-name-track {
            display: flex;
            flex-direction: column;
            transform: translateY(-100%);
        }

        .live-winner-name-track.is-animating {
            transition: transform .18s cubic-bezier(.2, .72, .28, 1);
            transform: translateY(-200%);
        }

        .live-winner-name {
            min-height: clamp(6.4rem, 11vw, 8rem);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: clamp(3.2rem, 5.8vw, 6.4rem);
            line-height: .94;
            font-weight: 900;
            letter-spacing: .03em;
            text-transform: uppercase;
            text-shadow: 0 0 24px rgba(255, 255, 255, 0.14);
        }

        .live-winner-meta {
            color: rgba(255, 255, 255, 0.78);
            font-size: .9rem;
            letter-spacing: .14em;
            text-transform: uppercase;
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
            background: linear-gradient(135deg, var(--live-gold), var(--live-gold-soft));
            color: #111;
            box-shadow: 0 18px 36px rgba(255, 176, 0, 0.26);
        }

        .live-btn-stop {
            background: linear-gradient(135deg, #ff5b78, var(--live-pink));
            color: #fff;
            box-shadow: 0 18px 36px rgba(255, 74, 114, 0.24);
        }

        .live-btn-stop.is-disabled {
            opacity: .5;
            pointer-events: none;
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

        .live-list {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
            max-height: 100%;
            overflow: auto;
            padding-right: 4px;
        }

        .live-list-item {
            border-radius: 18px;
            border: 1px solid rgba(255, 255, 255, 0.08);
            background: rgba(255, 255, 255, 0.04);
            padding: 14px 16px;
        }

        .live-list-item strong {
            display: block;
            font-size: 1rem;
            color: var(--live-text);
        }

        .live-list-item span {
            display: block;
            color: var(--live-muted);
            margin-top: 4px;
            font-size: .88rem;
        }

        .live-clock {
            color: rgba(255, 211, 28, 0.42);
            font-size: .92rem;
            letter-spacing: .08em;
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

            .live-list {
                grid-template-columns: 1fr;
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
            .live-buttons {
                grid-template-columns: 1fr;
            }

            .live-watermark {
                display: none;
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
        <section class="live-stage">
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
                <div class="live-winner-name-viewport">
                    <div class="live-winner-name-track" id="liveWinnerNameTrack">
                        <div class="live-winner-name" id="liveWinnerNamePrev">{{ $displayWinnerName }}</div>
                        <div class="live-winner-name" id="liveWinnerName">{{ $displayWinnerName }}</div>
                        <div class="live-winner-name" id="liveWinnerNameNext">{{ $displayWinnerName }}</div>
                    </div>
                </div>
                <div class="live-winner-meta" id="liveWinnerMeta">{{ $displayWinnerPn }} | {{ $displayWinnerHadiah }}</div>
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
                    <input class="live-search" type="text" id="liveHadiahSearch" placeholder="Cari nama hadiah...">
                    <select class="live-select" name="hadiah_undi_id" id="liveHadiahSelect">
                        <option value="">-- Pilih Hadiah --</option>
                        @foreach ($hadiahTersedia as $item)
                            <option value="{{ $item->id }}">{{ $item->nama_hadiah }}{{ $item->kategori ? ' - ' . $item->kategori : '' }} ({{ $item->stock_sisa }} tersisa)</option>
                        @endforeach
                    </select>

                    <div class="live-buttons">
                        <button class="live-btn live-btn-start" type="button" id="liveStartButton"><i class="fa-solid fa-dice"></i> Mulai Undi</button>
                        <button class="live-btn live-btn-stop is-disabled" type="submit" id="liveStopButton"><i class="fa-solid fa-stop"></i> Stop</button>
                    </div>

                    <div class="live-status"><strong>{{ $pesertaTersedia }}</strong> peserta tersedia • <strong>{{ $dashboard['total_pemenang'] }}</strong> pemenang tercatat</div>
                </form>

                <div>
                    <h3 class="live-panel-title" style="font-size:1rem;">Pemenang Terbaru</h3>
                    <div class="live-list">
                        @forelse ($pemenangTerbaru as $item)
                            <article class="live-list-item">
                                <strong>#{{ $item->undian_ke }} • {{ $item->peserta?->nama }}</strong>
                                <span>{{ $item->hadiah?->nama_hadiah }}{{ $item->hadiah?->kategori ? ' • ' . $item->hadiah?->kategori : '' }}</span>
                            </article>
                        @empty
                            <article class="live-list-item">
                                <strong>Belum ada pemenang</strong>
                                <span>Mulai undian pertama untuk menampilkan riwayat pemenang di sini.</span>
                            </article>
                        @endforelse
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection

@push('scripts')
    <script>
        (() => {
            const pool = @json($pesertaPoolJson);
            const winnerNameTrack = document.getElementById('liveWinnerNameTrack');
            const winnerNamePrev = document.getElementById('liveWinnerNamePrev');
            const winnerName = document.getElementById('liveWinnerName');
            const winnerNameNext = document.getElementById('liveWinnerNameNext');
            const winnerMeta = document.getElementById('liveWinnerMeta');
            const startButton = document.getElementById('liveStartButton');
            const stopButton = document.getElementById('liveStopButton');
            const form = document.getElementById('liveDrawForm');
            const hadiahSelect = document.getElementById('liveHadiahSelect');
            const hadiahSearch = document.getElementById('liveHadiahSearch');
            const clock = document.getElementById('liveClock');
            const hadiahOptions = Array.from(hadiahSelect.options).map((option) => ({
                value: option.value,
                label: option.textContent,
            }));

            let spinTimer = null;
            let isRollingName = false;
            let queuedParticipant = null;

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

            const randomParticipant = () => pool[Math.floor(Math.random() * pool.length)];

            const renderParticipant = (participant) => {
                winnerName.textContent = participant.nama;
                winnerMeta.textContent = participant.pn + ' | ' + participant.jabatan + ' | ' + participant.uker;
            };

            const rollNameDown = (participant) => {
                if (!participant) {
                    return;
                }

                if (isRollingName) {
                    queuedParticipant = participant;
                    return;
                }

                isRollingName = true;
                queuedParticipant = null;
                winnerNameNext.textContent = participant.nama;
                winnerMeta.textContent = participant.pn + ' | ' + participant.jabatan + ' | ' + participant.uker;

                requestAnimationFrame(() => {
                    winnerNameTrack.classList.add('is-animating');
                });
            };

            const renderHadiahOptions = (keyword = '') => {
                const normalizedKeyword = String(keyword || '').trim().toLowerCase();
                const currentValue = hadiahSelect.value;
                const filteredOptions = hadiahOptions.filter((option, index) => {
                    if (index === 0) {
                        return true;
                    }

                    return option.label.toLowerCase().includes(normalizedKeyword);
                });

                hadiahSelect.innerHTML = '';
                filteredOptions.forEach((option) => {
                    const optionElement = document.createElement('option');
                    optionElement.value = option.value;
                    optionElement.textContent = option.label;
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
                startButton.disabled = false;
            };

            winnerNameTrack.addEventListener('transitionend', () => {
                if (!winnerNameTrack.classList.contains('is-animating')) {
                    return;
                }

                winnerNamePrev.textContent = winnerName.textContent;
                winnerName.textContent = winnerNameNext.textContent;
                winnerNameTrack.classList.remove('is-animating');
                winnerNameTrack.style.transition = 'none';
                winnerNameTrack.style.transform = 'translateY(-100%)';
                winnerNameTrack.offsetHeight;
                winnerNameTrack.style.transition = '';
                winnerNameTrack.style.transform = '';
                isRollingName = false;

                if (queuedParticipant) {
                    const nextParticipant = queuedParticipant;
                    queuedParticipant = null;
                    rollNameDown(nextParticipant);
                }
            });

            startButton.addEventListener('click', () => {
                if (!pool.length) {
                    winnerName.textContent = 'Peserta Habis';
                    winnerMeta.textContent = 'Tidak ada peserta aktif yang bisa diundi';
                    return;
                }

                if (hadiahSelect.options.length <= 1) {
                    winnerName.textContent = 'Hadiah Habis';
                    winnerMeta.textContent = 'Tidak ada hadiah aktif yang bisa diundi';
                    return;
                }

                startButton.disabled = true;
                stopButton.classList.remove('is-disabled');

                spinTimer = window.setInterval(() => {
                    const participant = randomParticipant();
                    rollNameDown(participant);
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
                renderHadiahOptions(event.target.value);
            });

            renderHadiahOptions();
            updateClock();
            window.setInterval(updateClock, 1000);
        })();
    </script>
@endpush
