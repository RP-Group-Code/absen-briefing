<section class="coming-soon" style="--soon-accent: {{ $accent }}; --soon-accent-rgb: {{ $accentRgb }};">
    <div class="coming-soon__grid" aria-hidden="true"></div>
    <div class="coming-soon__particles" aria-hidden="true">
        <span style="--x: 8%; --y: 16%; --delay: 0s;"></span>
        <span style="--x: 18%; --y: 78%; --delay: .7s;"></span>
        <span style="--x: 36%; --y: 10%; --delay: 1.4s;"></span>
        <span style="--x: 55%; --y: 84%; --delay: .3s;"></span>
        <span style="--x: 72%; --y: 15%; --delay: 1.1s;"></span>
        <span style="--x: 88%; --y: 68%; --delay: 1.8s;"></span>
        <span style="--x: 94%; --y: 26%; --delay: .5s;"></span>
    </div>

    <div class="coming-soon__inner">
        <div class="coming-soon__visual" aria-hidden="true">
            <div class="coming-soon__orbit coming-soon__orbit--outer"></div>
            <div class="coming-soon__orbit coming-soon__orbit--inner"></div>
            <div class="coming-soon__mascot-glow"></div>
            <img
                class="coming-soon__mascot"
                src="{{ asset('images/development-mascot.webp') }}"
                alt=""
                width="360"
                height="540"
            >
            <div class="coming-soon__status">
                <span></span>
                Development mode
            </div>
        </div>

        <div class="coming-soon__copy">
            <p class="coming-soon__eyebrow">
                <i class="bi {{ $icon }}"></i>
                {{ $productName }}
            </p>

            <h1>
                <span>Sistem segera hadir.</span>
                Dalam proses pengembangan.
            </h1>

            <p class="coming-soon__description">
                Tim kami sedang menyiapkan sistem yang stabil, aman, dan nyaman digunakan.
                Nantikan pembaruan berikutnya di Portal SWJ-Tech.
            </p>

            <div class="coming-soon__progress" aria-label="Status proses pengembangan">
                <div class="coming-soon__progress-head">
                    <span>Development in progress</span>
                    <strong>Building</strong>
                </div>
                <div class="coming-soon__progress-track"><span></span></div>
            </div>

            <div class="coming-soon__steps">
                <span><i class="bi bi-check2"></i> Antarmuka</span>
                <span><i class="bi bi-arrow-repeat"></i> Integrasi data</span>
                <span><i class="bi bi-shield-check"></i> Pengujian</span>
            </div>

            <a class="coming-soon__back" href="{{ route('home') }}">
                <i class="bi bi-arrow-left"></i>
                Kembali ke Portal
            </a>
        </div>
    </div>
</section>
