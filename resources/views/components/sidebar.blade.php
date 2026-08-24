@php
    $mode = session('sidebar_mode', 'all'); // 'bcf' | 'briefing' | 'eyeforce' | 'analytics' | 'all'
@endphp

<nav id="sidebar">

    {{-- Brand --}}
    <div class="sidebar-brand">
        <img src="{{ asset('images/LogoPPOIT.png') }}" alt="Logo" style="height: 65px; margin-right: 2px;">
        <span class="brand-text">Portal SWJ-Tech</span>
    </div>

    {{-- Kembali ke Portal --}}
    <div style="padding: 0.6rem 1rem 0;">
        <a href="{{ route('home') }}"
            class="d-flex align-items-center gap-2 text-decoration-none nav-label"
            style="font-size:.78rem;color:rgba(148,163,184,.7);transition:color .2s;"
            onmouseover="this.style.color='#fff'"
            onmouseout="this.style.color='rgba(148,163,184,.7)'">
            <i class="bi bi-arrow-left-circle"></i>
            <span class="nav-label">Kembali ke Portal</span>
        </a>
    </div>

    {{-- Mode label --}}
    @if($mode === 'bcf')
    <div class="nav-label" style="padding:.5rem 1.5rem .25rem;font-size:.68rem;letter-spacing:.1em;text-transform:uppercase;color:rgba(99,102,241,.8);font-weight:700;">
        ● SISTEM BCF 2026
    </div>
    @elseif($mode === 'briefing')
    <div class="nav-label" style="padding:.5rem 1.5rem .25rem;font-size:.68rem;letter-spacing:.1em;text-transform:uppercase;color:rgba(16,185,129,.8);font-weight:700;">
        ● BRIEFING KANCA-UKER
    </div>
    @elseif($mode === 'eyeforce')
    <div class="nav-label" style="padding:.5rem 1.5rem .25rem;font-size:.68rem;letter-spacing:.1em;text-transform:uppercase;color:rgba(56,189,248,.9);font-weight:700;">
        ● EYEFORCE E-CHANNEL
    </div>
    @elseif($mode === 'analytics')
    <div class="nav-label" style="padding:.5rem 1.5rem .25rem;font-size:.68rem;letter-spacing:.1em;text-transform:uppercase;color:rgba(251,191,36,.9);font-weight:700;">
        ● ANALYTICS BDS
    </div>
    @endif

    {{-- Menu --}}
    <ul class="nav flex-column mt-2 pt-2">

        {{-- Dashboard: link disesuaikan dengan mode --}}
        <li class="nav-item mt-1">
            @if($mode === 'bcf')
            <a href="{{ route('bcf.undian.index') }}" class="nav-link {{ request()->routeIs('bcf.undian.index') ? 'active' : '' }}" target="_blank">
            @elseif($mode === 'briefing')
            <a href="{{ route('absen.dashboard') }}" class="nav-link {{ request()->routeIs('absen.dashboard') ? 'active' : '' }}" target="_blank">
            @elseif($mode === 'eyeforce')
            <a href="{{ route('portal.eyeforce') }}" class="nav-link {{ request()->routeIs('portal.eyeforce') ? 'active' : '' }}">
            @elseif($mode === 'analytics')
            <a href="{{ route('portal.analytics') }}" class="nav-link {{ request()->routeIs('portal.analytics') ? 'active' : '' }}">
            @else
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" target="_blank">
            @endif
                <i class="bi bi-speedometer2"></i>
                <span class="nav-label">Dashboard</span>
            </a>
        </li>

        {{-- ════ MODE: BRIEFING ════ --}}
        @if($mode === 'briefing' || $mode === 'all')

        {{-- Data Pegawai (hanya mode all) --}}
        @if($mode === 'all')
        <li class="nav-item">
            <a class="nav-link d-flex align-items-center justify-content-between" href="#menuPegawai"
                data-bs-toggle="collapse" role="button"
                aria-expanded="{{ request()->routeIs('pegawai.*') ? 'true' : 'false' }}">
                <span class="d-flex align-items-center gap-2">
                    <i class="bi bi-people"></i>
                    <span class="nav-label">Data Pegawai</span>
                </span>
                <i class="bi bi-chevron-down nav-label" style="font-size:.7rem;transition:transform .2s" id="chevronPegawai"></i>
            </a>
            <div class="collapse {{ request()->routeIs('pegawai.*') ? 'show' : '' }}" id="menuPegawai">
                <ul class="nav flex-column ms-3 mt-1">
                    <li class="nav-item">
                        <a href="{{ route('pegawai.dashboard') }}"
                            class="nav-link {{ request()->routeIs('pegawai.dashboard*') ? 'active' : '' }}"
                            style="padding:.55rem 1rem;font-size:.85rem" target="_blank">
                            <i class="bi bi-person-lines-fill" style="font-size:.9rem"></i>
                            <span class="nav-label">Master Pegawai Unit</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('pegawai.kanca.index') }}"
                            class="nav-link {{ request()->routeIs('pegawai.kanca.*') ? 'active' : '' }}"
                            style="padding:.55rem 1rem;font-size:.85rem" target="_blank">
                            <i class="bi bi-person-badge" style="font-size:.9rem"></i>
                            <span class="nav-label">Master Pegawai Kanca</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('pegawai.import') }}"
                            class="nav-link {{ request()->routeIs('pegawai.import*') ? 'active' : '' }}"
                            style="padding:.55rem 1rem;font-size:.85rem" target="_blank">
                            <i class="bi bi-file-earmark-arrow-up" style="font-size:.9rem"></i>
                            <span class="nav-label">Import Pegawai Unit</span>
                        </a>
                    </li>
                </ul>
            </div>
        </li>
        @endif

        {{-- Data Absen / Briefing --}}
        <li class="nav-item">
            <a class="nav-link d-flex align-items-center justify-content-between" href="#menuAbsen"
                data-bs-toggle="collapse" role="button"
                aria-expanded="{{ request()->routeIs('absen.*') ? 'true' : 'false' }}">
                <span class="d-flex align-items-center gap-2">
                    <i class="bi bi-journal-check"></i>
                    <span class="nav-label">{{ $mode === 'briefing' ? 'Briefing' : 'Data Absen' }}</span>
                </span>
                <i class="bi bi-chevron-down nav-label" style="font-size:.7rem;transition:transform .2s" id="chevronabsen"></i>
            </a>
            <div class="collapse {{ request()->routeIs('absen.*') || $mode === 'briefing' ? 'show' : '' }}" id="menuAbsen">
                <ul class="nav flex-column ms-3 mt-1">
                    @if($mode !== 'briefing')
                    <li class="nav-item">
                        <a href="{{ route('absen.dashboard') }}"
                            class="nav-link {{ request()->routeIs('absen.dashboard*') ? 'active' : '' }}"
                            style="padding:.55rem 1rem;font-size:.85rem" target="_blank">
                            <i class="bi bi-person-lines-fill" style="font-size:.9rem"></i>
                            <span class="nav-label">Data Absen</span>
                        </a>
                    </li>
                    @endif
                    <li class="nav-item">
                        <a href="{{ route('Input-Index-Kanca') }}"
                            class="nav-link {{ request()->routeIs('Input-Index-Kanca*') ? 'active' : '' }}"
                            style="padding:.55rem 1rem;font-size:.85rem" target="_blank">
                            <i class="bi bi-briefcase" style="font-size:.9rem"></i>
                            <span class="nav-label">Briefing Kanca</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('Input.Index') }}"
                            class="nav-link {{ request()->routeIs('Input.Index*') ? 'active' : '' }}"
                            style="padding:.55rem 1rem;font-size:.85rem" target="_blank">
                            <i class="fa-solid fa-people-group" style="font-size:.9rem"></i>
                            <span class="nav-label">Briefing Uker</span>
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        @endif

        {{-- ════ MODE: BCF ════ --}}
        @if($mode === 'bcf' || $mode === 'all')

        <li class="nav-item">
            <a class="nav-link d-flex align-items-center justify-content-between" href="#menuBCF"
                data-bs-toggle="collapse" role="button"
                aria-expanded="{{ request()->routeIs('bcf.*') || $mode === 'bcf' ? 'true' : 'false' }}">
                <span class="d-flex align-items-center gap-2">
                    <i class="bi bi-file-earmark-text"></i>
                    <span class="nav-label">BCF</span>
                </span>
                <i class="bi bi-chevron-down nav-label" style="font-size:.7rem;transition:transform .2s" id="chevronBCF"></i>
            </a>
            <div class="collapse {{ request()->routeIs('bcf.*') || $mode === 'bcf' ? 'show' : '' }}" id="menuBCF">
                <ul class="nav flex-column ms-3 mt-1">
                    <li class="nav-item">
                        <a href="{{ route('bcf.registrasi.index') }}"
                            class="nav-link {{ request()->routeIs('bcf.registrasi.index') ? 'active' : '' }}"
                            style="padding:.55rem 1rem;font-size:.85rem" target="_blank">
                            <i class="bi bi-file-earmark-text" style="font-size:.9rem"></i>
                            <span class="nav-label">BCF Registrasi</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('bcf.registrasi.admin') }}"
                            class="nav-link {{ request()->routeIs('bcf.registrasi.admin') ? 'active' : '' }}"
                            style="padding:.55rem 1rem;font-size:.85rem" target="_blank">
                            <i class="bi bi-person-badge" style="font-size:.9rem"></i>
                            <span class="nav-label">BCF Admin</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('bcf.undian.index') }}"
                            class="nav-link {{ request()->routeIs('bcf.undian.*') ? 'active' : '' }}"
                            style="padding:.55rem 1rem;font-size:.85rem" target="_blank">
                            <i class="bi bi-gift" style="font-size:.9rem"></i>
                            <span class="nav-label">BCF Undian</span>
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        @endif

    </ul>
</nav>
