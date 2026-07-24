<nav id="sidebar">

    {{-- Brand --}}
    <div class="sidebar-brand">
        <i class="bi bi-calendar-check text-primary fs-5"></i>
        <span class="brand-text">Absen Briefing</span>
    </div>

    {{-- Menu --}}
    <ul class="nav flex-column mt-2 pt-4">
        <li class="nav-item mt-2 ">
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i>
                <span class="nav-label">Dashboard</span>
            </a>
        </li>
        {{-- <li class="nav-item mt-2 ">
            <a href="{{ route('Pegawai-Master') }}"
                class="nav-link {{ request()->routeIs('Pegawai-Master.*') ? 'active' : '' }}">
                <i class="bi bi-people"></i>
                <span class="nav-label">Data Pegawai</span>
            </a>
        </li> --}}
        {{-- ── Sidebar Nav Item dengan Dropdown ── --}}
        <li class="nav-item">

            {{-- Trigger toggle --}}
            <a class="nav-link d-flex align-items-center justify-content-between" href="#menuPegawai"
                data-bs-toggle="collapse" role="button"
                aria-expanded="{{ request()->routeIs('pegawai.*') ? 'true' : 'false' }}">

                <span class="d-flex align-items-center gap-2">
                    <i class="bi bi-people"></i>
                    <span class="nav-label">Data Pegawai</span>
                </span>
                <i class="bi bi-chevron-down nav-label" style="font-size:.7rem;transition:transform .2s"
                    id="chevronPegawai"></i>
            </a>

            {{-- Submenu --}}
            <div class="collapse {{ request()->routeIs('pegawai.*') ? 'show' : '' }}" id="menuPegawai">
                <ul class="nav flex-column ms-3 mt-1">

                    <li class="nav-item">
                        <a href="{{ route('pegawai.dashboard') }}"
                            class="nav-link {{ request()->routeIs('pegawai.dashboard*') ? 'active' : '' }}"
                            style="padding:.55rem 1rem;font-size:.85rem">
                            <i class="bi bi-person-lines-fill" style="font-size:.9rem"></i>
                            <span class="nav-label">Master Pegawai Unit</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('pegawai.kanca.index') }}"
                            class="nav-link {{ request()->routeIs('pegawai.kanca.*') ? 'active' : '' }}"
                            style="padding:.55rem 1rem;font-size:.85rem">
                            <i class="bi bi-person-badge" style="font-size:.9rem"></i>
                            <span class="nav-label">Master Pegawai Kanca</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('pegawai.import') }}"
                            class="nav-link {{ request()->routeIs('pegawai.import*') ? 'active' : '' }}"
                            style="padding:.55rem 1rem;font-size:.85rem">
                            <i class="bi bi-file-earmark-arrow-up" style="font-size:.9rem"></i>
                            <span class="nav-label">Import Pegawai Unit</span>
                        </a>
                    </li>

                </ul>
            </div>
        </li>
        <li class="nav-item">

            {{-- Trigger toggle --}}
            <a class="nav-link d-flex align-items-center justify-content-between" href="#menuAbsen"
                data-bs-toggle="collapse" role="button"
                aria-expanded="{{ request()->routeIs('absen.*') ? 'true' : 'false' }}">

                <span class="d-flex align-items-center gap-2">
                    <i class="bi bi-journal-check"></i>

                    <span class="nav-label">Data absen</span>
                </span>
                <i class="bi bi-chevron-down nav-label" style="font-size:.7rem;transition:transform .2s"
                    id="chevronabsen"></i>
            </a>

            {{-- Submenu --}}
            <div class="collapse {{ request()->routeIs('absen.*') ? 'show' : '' }}" id="menuAbsen">
                <ul class="nav flex-column ms-3 mt-1">

                    <li class="nav-item">
                        <a href="{{ route('absen.dashboard') }}"
                            class="nav-link {{ request()->routeIs('absen.dashboard*') ? 'active' : '' }}"
                            style="padding:.55rem 1rem;font-size:.85rem">
                            <i class="bi bi-person-lines-fill" style="font-size:.9rem"></i>
                            <span class="nav-label">Data absen</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('Input-Index-Kanca') }}"
                            class="nav-link {{ request()->routeIs('Input-Index-Kanca*') ? 'active' : '' }}"
                            style="padding:.55rem 1rem;font-size:.85rem" target="__blank">
                            <i class="bi bi-briefcase" style="font-size:.9rem"></i>
                            <span class="nav-label">Briefing Kanca</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('Input.Index') }}"
                            class="nav-link {{ request()->routeIs('Input.Index*') ? 'active' : '' }}"
                            style="padding:.55rem 1rem;font-size:.85rem" target="__blank">
                            <i class="fa-solid fa-people-group" style="font-size:.9rem"></i>
                            <span class="nav-label">Briefing Uker</span>
                        </a>
                    </li>

                </ul>
            </div>
        </li>
        {{-- <li class="nav-item mt-2">
            <a href="{{ route('absen.dashboard') }}"
                class="nav-link {{ request()->routeIs('absen.*') ? 'active' : '' }}">
                <i class="bi bi-journal-check"></i>
                <span class="nav-label">Absensi</span>
            </a>
        </li> --}}
        {{-- <li class="nav-item mt-2">
            <a href="{{ route('Dashboard') }}" 
               class="nav-link {{ request()->routeIs('laporan.*') ? 'active' : '' }}">
                <i class="bi bi-bar-chart-line"></i>
                <span class="nav-label">Laporan</span>
            </a>
        </li>

        <li><hr class="border-secondary mx-3 my-1"></li>

        <li class="nav-item mt-2">
            <a href="{{ route('Dashboard') }}" 
               class="nav-link {{ request()->routeIs('pengaturan.*') ? 'active' : '' }}">
                <i class="bi bi-gear"></i>
                <span class="nav-label">Pengaturan</span>
            </a>
        </li> --}}
        {{-- <li class="nav-item mt-2">
            <form method="POST" action="{{ route('logout') }}" class="text-danger">
                @csrf
                <button type="submit" class="glass-btn nav-link p-2 m-3">

                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                    <span class="nav-label">Logout</span>
                </button>

            </form>
        </li> --}}
        {{-- <li class="nav-item mt-2">
            <form method="POST" action="{{ route('logout') }}" class="nav-link text-danger">
                @csrf
                <button type="submit" class="glass-btn">
                    <i class="fa-solid fa-arrow-right-from-bracket fa-xs"></i>
                    <span class="nav-label">Logout</span>

                </button>
            </form>
        </li> --}}
    </ul>
</nav>
