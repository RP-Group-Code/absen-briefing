<style>
    .coming-soon {
        --soon-accent: #38bdf8;
        --soon-accent-rgb: 56, 189, 248;
        min-height: calc(100vh - 118px);
        display: grid;
        place-items: center;
        position: relative;
        isolation: isolate;
        overflow: hidden;
        padding: clamp(1rem, 3vw, 3rem);
        border: 1px solid rgba(255, 255, 255, .09);
        border-radius: 28px;
        background:
            radial-gradient(circle at 18% 42%, rgba(var(--soon-accent-rgb), .18), transparent 31%),
            radial-gradient(circle at 86% 20%, rgba(var(--soon-accent-rgb), .11), transparent 24%),
            linear-gradient(145deg, rgba(11, 21, 54, .96), rgba(16, 20, 56, .9));
        box-shadow: 0 30px 80px rgba(2, 6, 23, .36), inset 0 1px 0 rgba(255, 255, 255, .08);
    }

    .coming-soon::before,
    .coming-soon::after {
        content: '';
        position: absolute;
        z-index: -1;
        border-radius: 50%;
        pointer-events: none;
    }

    .coming-soon::before {
        width: 34rem;
        height: 34rem;
        top: -20rem;
        right: -12rem;
        border: 1px solid rgba(var(--soon-accent-rgb), .25);
        box-shadow: 0 0 90px rgba(var(--soon-accent-rgb), .12);
    }

    .coming-soon::after {
        width: 22rem;
        height: 22rem;
        bottom: -15rem;
        left: 38%;
        background: rgba(var(--soon-accent-rgb), .09);
        filter: blur(50px);
    }

    .coming-soon__grid {
        position: absolute;
        inset: 0;
        z-index: -2;
        opacity: .24;
        background-image:
            linear-gradient(rgba(var(--soon-accent-rgb), .09) 1px, transparent 1px),
            linear-gradient(90deg, rgba(var(--soon-accent-rgb), .09) 1px, transparent 1px);
        background-size: 52px 52px;
        mask-image: linear-gradient(to bottom, transparent, #000 22%, #000 72%, transparent);
    }

    .coming-soon__particles span {
        position: absolute;
        left: var(--x);
        top: var(--y);
        width: 5px;
        height: 5px;
        border-radius: 50%;
        background: var(--soon-accent);
        box-shadow: 0 0 14px 3px rgba(var(--soon-accent-rgb), .55);
        animation: soonParticle 2.4s ease-in-out var(--delay) infinite;
    }

    .coming-soon__inner {
        width: min(1120px, 100%);
        display: grid;
        grid-template-columns: minmax(320px, .9fr) minmax(420px, 1.1fr);
        align-items: center;
        gap: clamp(2rem, 5vw, 5.5rem);
    }

    .coming-soon__visual {
        min-height: 510px;
        display: grid;
        place-items: center;
        position: relative;
    }

    .coming-soon__mascot-glow {
        position: absolute;
        width: 70%;
        aspect-ratio: 1;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(var(--soon-accent-rgb), .34), transparent 68%);
        filter: blur(12px);
        animation: soonGlow 2.2s ease-in-out infinite;
    }

    .coming-soon__mascot {
        width: min(330px, 82%);
        height: auto;
        position: relative;
        z-index: 2;
        object-fit: contain;
        filter: drop-shadow(0 28px 34px rgba(0, 0, 0, .42));
        animation: soonMascot 3.4s ease-in-out infinite;
    }

    .coming-soon__orbit {
        position: absolute;
        border-radius: 50%;
        border: 1px solid rgba(var(--soon-accent-rgb), .26);
        animation: soonOrbit 16s linear infinite;
    }

    .coming-soon__orbit::after {
        content: '';
        position: absolute;
        width: 10px;
        height: 10px;
        top: 13%;
        right: 14%;
        border-radius: 50%;
        background: var(--soon-accent);
        box-shadow: 0 0 20px var(--soon-accent);
    }

    .coming-soon__orbit--outer {
        width: 420px;
        height: 420px;
    }

    .coming-soon__orbit--inner {
        width: 320px;
        height: 320px;
        border-style: dashed;
        animation-direction: reverse;
        animation-duration: 12s;
    }

    .coming-soon__status {
        position: absolute;
        z-index: 3;
        bottom: 35px;
        display: flex;
        align-items: center;
        gap: .55rem;
        padding: .55rem .9rem;
        border: 1px solid rgba(var(--soon-accent-rgb), .3);
        border-radius: 999px;
        background: rgba(8, 15, 38, .82);
        color: rgba(255, 255, 255, .78);
        font-size: .74rem;
        font-weight: 700;
        letter-spacing: .09em;
        text-transform: uppercase;
        backdrop-filter: blur(12px);
    }

    .coming-soon__status span {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: var(--soon-accent);
        box-shadow: 0 0 12px var(--soon-accent);
        animation: soonBlink 1.15s ease-in-out infinite;
    }

    .coming-soon__copy {
        position: relative;
        z-index: 2;
    }

    .coming-soon__eyebrow {
        display: inline-flex;
        align-items: center;
        gap: .65rem;
        margin: 0 0 1.2rem;
        color: var(--soon-accent);
        font-size: .8rem;
        font-weight: 800;
        letter-spacing: .16em;
        text-transform: uppercase;
    }

    .coming-soon__eyebrow i {
        display: grid;
        place-items: center;
        width: 34px;
        height: 34px;
        border-radius: 10px;
        background: rgba(var(--soon-accent-rgb), .14);
        box-shadow: inset 0 0 0 1px rgba(var(--soon-accent-rgb), .24);
        font-size: 1rem;
    }

    .coming-soon h1 {
        max-width: 720px;
        margin: 0;
        color: #f8fafc;
        font-size: clamp(2.3rem, 5vw, 4.8rem);
        font-weight: 800;
        line-height: .98;
        letter-spacing: -.045em;
        text-transform: uppercase;
    }

    .coming-soon h1 span {
        display: block;
        margin-bottom: .22em;
        color: var(--soon-accent);
        text-shadow: 0 0 32px rgba(var(--soon-accent-rgb), .24);
    }

    .coming-soon__description {
        max-width: 620px;
        margin: 1.5rem 0 0;
        color: rgba(226, 232, 240, .67);
        font-size: clamp(.95rem, 1.4vw, 1.08rem);
        line-height: 1.75;
    }

    .coming-soon__progress {
        max-width: 590px;
        margin-top: 2rem;
        padding: 1rem 1.1rem;
        border: 1px solid rgba(255, 255, 255, .08);
        border-radius: 16px;
        background: rgba(255, 255, 255, .035);
    }

    .coming-soon__progress-head {
        display: flex;
        justify-content: space-between;
        gap: 1rem;
        margin-bottom: .7rem;
        color: rgba(226, 232, 240, .55);
        font-size: .74rem;
        font-weight: 700;
        letter-spacing: .08em;
        text-transform: uppercase;
    }

    .coming-soon__progress-head strong {
        color: var(--soon-accent);
    }

    .coming-soon__progress-track {
        height: 7px;
        overflow: hidden;
        border-radius: 999px;
        background: rgba(255, 255, 255, .08);
    }

    .coming-soon__progress-track span {
        display: block;
        width: 58%;
        height: 100%;
        border-radius: inherit;
        background: linear-gradient(90deg, rgba(var(--soon-accent-rgb), .45), var(--soon-accent));
        box-shadow: 0 0 14px rgba(var(--soon-accent-rgb), .5);
        animation: soonProgress 2.2s ease-in-out infinite;
    }

    .coming-soon__steps {
        display: flex;
        flex-wrap: wrap;
        gap: .65rem;
        margin-top: .9rem;
    }

    .coming-soon__steps span {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        padding: .45rem .7rem;
        border-radius: 9px;
        background: rgba(255, 255, 255, .04);
        color: rgba(226, 232, 240, .65);
        font-size: .74rem;
        font-weight: 600;
    }

    .coming-soon__steps i {
        color: var(--soon-accent);
    }

    .coming-soon__back {
        display: inline-flex;
        align-items: center;
        gap: .55rem;
        margin-top: 1.5rem;
        padding: .75rem 1rem;
        border: 1px solid rgba(var(--soon-accent-rgb), .36);
        border-radius: 12px;
        color: #f8fafc;
        font-size: .83rem;
        font-weight: 700;
        text-decoration: none;
        transition: transform .2s ease, background .2s ease, border-color .2s ease;
    }

    .coming-soon__back:hover {
        transform: translateY(-2px);
        border-color: var(--soon-accent);
        background: rgba(var(--soon-accent-rgb), .12);
        color: #fff;
    }

    @keyframes soonMascot {
        0%, 100% { transform: translateY(0) rotate(-1deg); }
        50% { transform: translateY(-12px) rotate(1deg); }
    }

    @keyframes soonOrbit {
        to { transform: rotate(360deg); }
    }

    @keyframes soonGlow {
        0%, 100% { opacity: .66; transform: scale(.93); }
        50% { opacity: 1; transform: scale(1.08); }
    }

    @keyframes soonBlink {
        50% { opacity: .35; transform: scale(.72); }
    }

    @keyframes soonParticle {
        0%, 100% { opacity: .18; transform: translateY(8px) scale(.7); }
        50% { opacity: .9; transform: translateY(-10px) scale(1); }
    }

    @keyframes soonProgress {
        0%, 100% { opacity: .75; }
        50% { opacity: 1; filter: brightness(1.3); }
    }

    @media (max-width: 991.98px) {
        .coming-soon {
            min-height: calc(100vh - 102px);
            padding: 1.5rem 1rem 2rem;
            border-radius: 22px;
        }

        .coming-soon__inner {
            grid-template-columns: 1fr;
            gap: .5rem;
            text-align: center;
        }

        .coming-soon__visual {
            min-height: 300px;
        }

        .coming-soon__mascot {
            width: min(210px, 68vw);
        }

        .coming-soon__orbit--outer {
            width: 260px;
            height: 260px;
        }

        .coming-soon__orbit--inner {
            width: 205px;
            height: 205px;
        }

        .coming-soon__status {
            bottom: 7px;
        }

        .coming-soon__eyebrow,
        .coming-soon__steps,
        .coming-soon__back {
            justify-content: center;
        }

        .coming-soon__eyebrow {
            margin-bottom: 1rem;
        }

        .coming-soon h1 {
            font-size: clamp(2rem, 10vw, 3.4rem);
            line-height: 1.03;
        }

        .coming-soon__description {
            margin: 1rem auto 0;
            line-height: 1.6;
        }

        .coming-soon__progress {
            margin: 1.4rem auto 0;
            text-align: left;
        }

        .coming-soon__steps {
            gap: .4rem;
        }
    }

    @media (max-width: 575.98px) {
        .coming-soon__visual {
            min-height: 270px;
        }

        .coming-soon__mascot {
            width: 185px;
        }

        .coming-soon__orbit--outer {
            width: 230px;
            height: 230px;
        }

        .coming-soon__orbit--inner {
            width: 180px;
            height: 180px;
        }

        .coming-soon__steps span {
            flex: 1 1 auto;
            justify-content: center;
            font-size: .68rem;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .coming-soon *,
        .coming-soon *::before,
        .coming-soon *::after {
            animation-duration: .01ms !important;
            animation-iteration-count: 1 !important;
        }
    }
</style>
