<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    /* ── Fix modal bisa diklik tanpa detach ── */
    .modal {
        z-index: 1055 !important;
    }

    .modal-backdrop {
        z-index: 1054 !important;
    }

    /* ── Matikan stacking context dari page-wrap ── */
    .page-wrap {
        position: relative;
        z-index: auto !important;
        /* ← bukan z-index: 1 */
        isolation: auto !important;
    }

    /* ── glass-card juga jangan buat stacking context ── */
    .glass-card {
        position: relative;
        z-index: auto !important;
        isolation: auto !important;
    }

    /* ── header-card sama ── */
    .header-card {
        position: relative;
        z-index: auto !important;
        isolation: auto !important;
    }

    /* ── select2 tetap di atas modal ── */
    .select2-container--open,
    .select2-dropdown {
        z-index: 9999 !important;
    }

    /* ════════════════════════════════
           BASE
        ════════════════════════════════ */
    *,
    *::before,
    *::after {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    body {
        font-family: 'Outfit', sans-serif;
        font-size: 14px;
        background: linear-gradient(135deg, #0f0c29 0%, #1a1a4e 45%, #24243e 100%);
        min-height: 100vh;
        padding-bottom: 90px;
        position: relative;
        overflow-x: hidden;
    }

    /* Orbs */
    body::before {
        content: '';
        position: fixed;
        width: 420px;
        height: 420px;
        background: radial-gradient(circle, rgba(99, 102, 241, .22) 0%, transparent 70%);
        top: -100px;
        left: -100px;
        border-radius: 50%;
        animation: orbFloat 8s ease-in-out infinite;
        pointer-events: none;
        z-index: 0;
    }

    body::after {
        content: '';
        position: fixed;
        width: 340px;
        height: 340px;
        background: radial-gradient(circle, rgba(236, 72, 153, .18) 0%, transparent 70%);
        bottom: -70px;
        right: -70px;
        border-radius: 50%;
        animation: orbFloat 10s ease-in-out infinite reverse;
        pointer-events: none;
        z-index: 0;
    }

    @keyframes orbFloat {

        0%,
        100% {
            transform: translate(0, 0) scale(1);
        }

        33% {
            transform: translate(20px, -20px) scale(1.05);
        }

        66% {
            transform: translate(-15px, 15px) scale(0.95);
        }
    }

    /* ════════════════════════════════
           WRAPPER
        ════════════════════════════════ */
    .page-wrap {
        position: relative;
        z-index: 1;
        max-width: 600px;
        margin: 0 auto;
        padding: 1.2rem 1rem;
    }

    /* ════════════════════════════════
           HEADER CARD
        ════════════════════════════════ */
    .header-card {
        background: linear-gradient(135deg, rgba(99, 102, 241, .55), rgba(168, 85, 247, .45));
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, .2);
        border-radius: 22px;
        padding: 1.4rem 1.4rem 1.2rem;
        margin-bottom: 1rem;
        box-shadow: 0 8px 32px rgba(0, 0, 0, .35), inset 0 1px 0 rgba(255, 255, 255, .25);
        animation: fadeUp .5s cubic-bezier(.22, .68, 0, 1.2) both;
        position: relative;
        overflow: hidden;
    }

    .header-card::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(140deg, rgba(255, 255, 255, .15) 0%, transparent 55%);
        pointer-events: none;
    }

    .header-brand {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: .8rem;
    }

    .header-icon {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        background: rgba(255, 255, 255, .2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        color: #fff;
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, .3);
    }

    .header-title {
        font-size: 1rem;
        font-weight: 800;
        color: #fff;
        letter-spacing: -.01em;
        line-height: 1.2;
    }

    .header-sub {
        font-size: .7rem;
        color: rgba(255, 255, 255, .55);
        letter-spacing: .05em;
        text-transform: uppercase;
    }

    .header-notice {
        background: rgba(245, 158, 11, .18);
        border: 1px solid rgba(245, 158, 11, .35);
        border-radius: 10px;
        padding: .6rem .9rem;
        font-size: .78rem;
        color: #fcd34d;
        display: flex;
        align-items: flex-start;
        gap: 7px;
    }

    @keyframes fadeUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* ════════════════════════════════
           GLASS CARD
        ════════════════════════════════ */
    .glass-card {
        background: rgba(255, 255, 255, .065);
        backdrop-filter: blur(22px) saturate(180%);
        -webkit-backdrop-filter: blur(22px) saturate(180%);
        border: 1px solid rgba(255, 255, 255, .13);
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 6px 30px rgba(0, 0, 0, .28), inset 0 1px 0 rgba(255, 255, 255, .16);
        margin-bottom: 1rem;
        animation: fadeUp .5s cubic-bezier(.22, .68, 0, 1.2) both;
        position: relative;
    }

    .glass-card::before {
        content: '';
        position: absolute;
        inset: 0;
        border-radius: inherit;
        background: linear-gradient(140deg, rgba(255, 255, 255, .09) 0%, transparent 50%);
        pointer-events: none;
        z-index: 0;
    }

    .glass-card:nth-child(2) {
        animation-delay: .1s;
    }

    .glass-card:nth-child(3) {
        animation-delay: .2s;
    }

    .glass-card-header {
        position: relative;
        z-index: 1;
        padding: 1rem 1.2rem .7rem;
        border-bottom: 1px solid rgba(255, 255, 255, .08);
        background: rgba(255, 255, 255, .03);
        display: flex;
        align-items: center;
        gap: 9px;
    }

    .card-icon {
        width: 32px;
        height: 32px;
        border-radius: 9px;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: .82rem;
        color: #fff;
    }

    .card-icon-purple {
        background: linear-gradient(135deg, rgba(99, 102, 241, .8), rgba(168, 85, 247, .8));
        box-shadow: 0 3px 10px rgba(99, 102, 241, .35);
    }

    .card-icon-green {
        background: linear-gradient(135deg, rgba(16, 185, 129, .8), rgba(5, 150, 105, .7));
        box-shadow: 0 3px 10px rgba(16, 185, 129, .35);
    }

    .card-label {
        font-size: .88rem;
        font-weight: 700;
        color: #fff;
    }

    .card-label-sub {
        font-size: .7rem;
        color: rgba(255, 255, 255, .4);
    }

    .glass-card-body {
        position: relative;
        z-index: 1;
        padding: 1rem 1.2rem;
    }

    /* ════════════════════════════════
           SELECT2 OVERRIDE
        ════════════════════════════════ */
    .select2-container--default .select2-selection--single {
        background: rgba(255, 255, 255, .08) !important;
        border: 1px solid rgba(255, 255, 255, .18) !important;
        border-radius: 12px !important;
        height: 42px !important;
        display: flex !important;
        align-items: center !important;
        color: #fff !important;
        backdrop-filter: blur(6px);
        transition: border-color .2s, background .2s !important;
    }

    .select2-container--default .select2-selection--single:hover {
        border-color: rgba(99, 102, 241, .6) !important;
        background: rgba(255, 255, 255, .12) !important;
    }

    .select2-container--default.select2-container--focus .select2-selection--single,
    .select2-container--default.select2-container--open .select2-selection--single {
        border-color: rgba(99, 102, 241, .75) !important;
        background: rgba(255, 255, 255, .12) !important;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, .18) !important;
        outline: none !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: rgba(255, 255, 255, .85) !important;
        line-height: 42px !important;
        padding-left: 14px !important;
        font-size: .88rem !important;
        font-family: 'Outfit', sans-serif !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__placeholder {
        color: rgba(255, 255, 255, .32) !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 42px !important;
        right: 10px !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow b {
        border-color: rgba(255, 255, 255, .4) transparent transparent !important;
    }

    .select2-container--open .select2-selection--single .select2-selection__arrow b {
        border-color: transparent transparent rgba(255, 255, 255, .4) !important;
    }

    /* Dropdown */
    .select2-dropdown {
        background: rgba(20, 18, 60, .96) !important;
        backdrop-filter: blur(20px) !important;
        border: 1px solid rgba(99, 102, 241, .4) !important;
        border-radius: 14px !important;
        box-shadow: 0 10px 30px rgba(0, 0, 0, .5) !important;
        overflow: hidden;
    }

    .select2-container--default .select2-results__option {
        color: rgba(255, 255, 255, .75) !important;
        font-size: .85rem !important;
        padding: .55rem 1rem !important;
        font-family: 'Outfit', sans-serif !important;
    }

    .select2-container--default .select2-results__option--highlighted {
        background: rgba(99, 102, 241, .3) !important;
        color: #fff !important;
    }

    .select2-container--default .select2-results__option--selected {
        background: rgba(99, 102, 241, .2) !important;
        color: #a5b4fc !important;
    }

    .select2-search--dropdown .select2-search__field {
        background: rgba(255, 255, 255, .08) !important;
        border: 1px solid rgba(255, 255, 255, .15) !important;
        border-radius: 8px !important;
        color: #fff !important;
        padding: .45rem .8rem !important;
        font-size: .85rem !important;
        font-family: 'Outfit', sans-serif !important;
        outline: none !important;
    }

    .select2-search--dropdown .select2-search__field::placeholder {
        color: rgba(255, 255, 255, .3) !important;
    }

    .select2-search--dropdown {
        padding: .6rem .6rem .3rem !important;
    }

    /* Invalid */
    .select2-selection.is-invalid {
        border-color: rgba(239, 68, 68, .7) !important;
        box-shadow: 0 0 0 3px rgba(239, 68, 68, .2) !important;
    }

    .select2-container--open {
        z-index: 9999;
    }

    /* ════════════════════════════════
           ABSEN TABLE (inside modal)
        ════════════════════════════════ */
    .absen-table {
        width: 100%;
        border-collapse: collapse;
    }

    .absen-table thead th {
        background: rgba(255, 255, 255, .06);
        color: rgba(255, 255, 255, .45);
        font-size: .68rem;
        font-weight: 600;
        letter-spacing: .07em;
        text-transform: uppercase;
        padding: .6rem .7rem;
        border-bottom: 1px solid rgba(255, 255, 255, .08);
        white-space: nowrap;
    }

    .absen-table tbody tr {
        border-bottom: 1px solid rgba(255, 255, 255, .05);
    }

    .absen-table tbody tr:last-child {
        border-bottom: none;
    }

    .absen-table tbody td {
        padding: .5rem .5rem;
        vertical-align: middle;
    }

    /* Action buttons */
    .btn-add-row {
        width: 28px;
        height: 28px;
        border-radius: 8px;
        border: 1px solid rgba(16, 185, 129, .4);
        background: rgba(16, 185, 129, .2);
        color: #6ee7b7;
        font-size: 1rem;
        line-height: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all .2s;
        flex-shrink: 0;
    }

    .btn-add-row:hover {
        background: rgba(16, 185, 129, .4);
        color: #fff;
    }

    .btn-rem-row {
        width: 28px;
        height: 28px;
        border-radius: 8px;
        border: 1px solid rgba(239, 68, 68, .35);
        background: rgba(239, 68, 68, .15);
        color: #fca5a5;
        font-size: 1rem;
        line-height: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all .2s;
        flex-shrink: 0;
    }

    .btn-rem-row:hover {
        background: rgba(239, 68, 68, .35);
        color: #fff;
    }

    .btn-rem-row:disabled {
        opacity: .35;
        cursor: not-allowed;
    }

    /* ════════════════════════════════
           OPEN MODAL BUTTON
        ════════════════════════════════ */
    .btn-open-modal {
        width: 100%;
        padding: .85rem;
        border-radius: 14px;
        border: 1px solid rgba(99, 102, 241, .45);
        background: linear-gradient(135deg, rgba(99, 102, 241, .55), rgba(168, 85, 247, .45));
        color: #fff;
        font-family: 'Outfit', sans-serif;
        font-size: .9rem;
        font-weight: 700;
        letter-spacing: .02em;
        cursor: pointer;
        box-shadow: 0 4px 18px rgba(99, 102, 241, .35);
        transition: all .25s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        text-decoration: none;
        position: relative;
        overflow: hidden;
    }

    .btn-open-modal::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(255, 255, 255, .16) 0%, transparent 60%);
        pointer-events: none;
    }

    .btn-open-modal:hover {
        background: linear-gradient(135deg, rgba(99, 102, 241, .75), rgba(168, 85, 247, .65));
        box-shadow: 0 6px 24px rgba(99, 102, 241, .5);
        color: #fff;
        transform: translateY(-1px);
    }

    .btn-open-modal.disabled {
        opacity: .45;
        cursor: not-allowed;
        transform: none;
        pointer-events: none;
    }

    /* Row counter badge */
    .row-badge {
        background: rgba(99, 102, 241, .25);
        border: 1px solid rgba(99, 102, 241, .4);
        color: #a5b4fc;
        border-radius: 6px;
        padding: 2px 7px;
        font-size: .7rem;
        font-weight: 700;
        font-family: monospace;
    }

    /* ════════════════════════════════
           MODAL OVERRIDE
        ════════════════════════════════ */
    .modal-content {
        background: rgba(15, 12, 41, .95) !important;
        backdrop-filter: blur(28px) !important;
        border: 1px solid rgba(255, 255, 255, .14) !important;
        border-radius: 22px !important;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(0, 0, 0, .6) !important;
    }

    .modal-header {
        background: rgba(255, 255, 255, .04);
        border-bottom: 1px solid rgba(255, 255, 255, .08) !important;
        padding: 1rem 1.2rem;
    }

    .modal-title {
        color: #fff !important;
        font-weight: 700;
        font-size: .95rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn-close {
        filter: invert(1) !important;
        opacity: .55 !important;
    }

    .btn-close:hover {
        opacity: 1 !important;
    }

    .modal-body {
        padding: 1rem !important;
        background: transparent !important;
    }

    .modal-footer {
        border-top: 1px solid rgba(255, 255, 255, .07) !important;
        background: rgba(255, 255, 255, .02) !important;
        padding: .8rem 1rem !important;
    }

    /* ════════════════════════════════
           SAVE BAR
        ════════════════════════════════ */
    .save-bar {
        position: fixed;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 1050;
        background: rgba(10, 8, 35, .9);
        backdrop-filter: blur(20px);
        border-top: 1px solid rgba(255, 255, 255, .1);
        padding: .8rem 1rem;
        box-shadow: 0 -8px 30px rgba(0, 0, 0, .4);
    }

    .save-bar .inner {
        max-width: 600px;
        margin: 0 auto;
    }

    .btn-save {
        width: 100%;
        padding: .9rem;
        border-radius: 14px;
        border: none;
        background: linear-gradient(135deg, rgba(16, 185, 129, .85), rgba(5, 150, 105, .75));
        color: #fff;
        font-family: 'Outfit', sans-serif;
        font-size: .95rem;
        font-weight: 700;
        letter-spacing: .02em;
        cursor: pointer;
        box-shadow: 0 4px 18px rgba(16, 185, 129, .4);
        transition: all .25s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        position: relative;
        overflow: hidden;
    }

    .btn-save::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(255, 255, 255, .18) 0%, transparent 55%);
        pointer-events: none;
    }

    .btn-save:hover {
        box-shadow: 0 6px 24px rgba(16, 185, 129, .6);
        transform: translateY(-1px);
    }

    .btn-save:active {
        transform: none;
    }

    .save-note {
        text-align: center;
        margin-top: .45rem;
        font-size: .72rem;
        color: rgba(239, 68, 68, .75);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
    }

    /* ════════════════════════════════
           DONE BUTTON in modal footer
        ════════════════════════════════ */
    .btn-done {
        padding: .6rem 1.5rem;
        border-radius: 50px;
        border: none;
        background: linear-gradient(135deg, rgba(99, 102, 241, .8), rgba(168, 85, 247, .8));
        color: #fff;
        font-family: 'Outfit', sans-serif;
        font-size: .85rem;
        font-weight: 600;
        cursor: pointer;
        box-shadow: 0 3px 12px rgba(99, 102, 241, .35);
        transition: all .2s;
    }

    .btn-done:hover {
        box-shadow: 0 5px 18px rgba(99, 102, 241, .55);
    }

    /* ════════════════════════════════
           SUMMARY BADGE (row count)
        ════════════════════════════════ */
    .summary-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(16, 185, 129, .15);
        border: 1px solid rgba(16, 185, 129, .3);
        border-radius: 20px;
        padding: 4px 12px;
        font-size: .75rem;
        font-weight: 600;
        color: #6ee7b7;
        margin-top: .6rem;
    }

    .summary-pill .dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #34d399;
        box-shadow: 0 0 5px #34d399;
    }

    .summary-pill.empty {
        background: rgba(107, 114, 128, .15);
        border-color: rgba(107, 114, 128, .25);
        color: #9ca3af;
    }

    .summary-pill.empty .dot {
        background: #6b7280;
        box-shadow: none;
    }


    /* ════════════════════════════════
   SWEETALERT2 GLASS DARK THEME
════════════════════════════════ */
    .swal2-popup {
        background: rgba(15, 12, 41, 0.92) !important;
        backdrop-filter: blur(28px) saturate(180%) !important;
        -webkit-backdrop-filter: blur(28px) saturate(180%) !important;
        border: 1px solid rgba(255, 255, 255, .14) !important;
        border-radius: 24px !important;
        box-shadow:
            0 20px 60px rgba(0, 0, 0, .6),
            inset 0 1px 0 rgba(255, 255, 255, .12) !important;
        font-family: 'Outfit', sans-serif !important;
        /* Glossy shine */
        position: relative;
        overflow: hidden;
    }

    .swal2-popup::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(140deg, rgba(255, 255, 255, .08) 0%, transparent 50%);
        pointer-events: none;
        border-radius: inherit;
    }

    /* Title */
    .swal2-title {
        color: #fff !important;
        font-family: 'Outfit', sans-serif !important;
        font-weight: 700 !important;
        font-size: 1.1rem !important;
        letter-spacing: -.01em !important;
    }

    /* Content text */
    .swal2-html-container,
    .swal2-content {
        color: rgba(255, 255, 255, .65) !important;
        font-family: 'Outfit', sans-serif !important;
        font-size: .88rem !important;
    }

    /* Icon colors */
    .swal2-icon.swal2-success {
        border-color: #34d399 !important;
        color: #34d399 !important;
    }

    .swal2-icon.swal2-success .swal2-success-ring {
        border-color: rgba(52, 211, 153, .3) !important;
    }

    .swal2-icon.swal2-success [class^=swal2-success-line] {
        background: #34d399 !important;
    }

    .swal2-icon.swal2-error {
        border-color: #f87171 !important;
        color: #f87171 !important;
    }

    .swal2-icon.swal2-error [class^=swal2-x-mark-line] {
        background: #f87171 !important;
    }

    .swal2-icon.swal2-warning {
        border-color: #fbbf24 !important;
        color: #fbbf24 !important;
    }

    .swal2-icon.swal2-info {
        border-color: #60a5fa !important;
        color: #60a5fa !important;
    }

    .swal2-icon.swal2-question {
        border-color: #a5b4fc !important;
        color: #a5b4fc !important;
    }

    /* Confirm button */
    .swal2-confirm {
        background: linear-gradient(135deg, rgba(99, 102, 241, .85), rgba(168, 85, 247, .85)) !important;
        border: 1px solid rgba(168, 85, 247, .4) !important;
        border-radius: 50px !important;
        font-family: 'Outfit', sans-serif !important;
        font-weight: 600 !important;
        font-size: .88rem !important;
        padding: .55rem 1.6rem !important;
        box-shadow: 0 4px 14px rgba(99, 102, 241, .4) !important;
        transition: all .2s !important;
    }

    .swal2-confirm:hover {
        box-shadow: 0 6px 20px rgba(99, 102, 241, .6) !important;
        transform: translateY(-1px) !important;
    }

    /* Cancel button */
    .swal2-cancel {
        background: rgba(255, 255, 255, .08) !important;
        border: 1px solid rgba(255, 255, 255, .18) !important;
        border-radius: 50px !important;
        color: rgba(255, 255, 255, .75) !important;
        font-family: 'Outfit', sans-serif !important;
        font-weight: 600 !important;
        font-size: .88rem !important;
        padding: .55rem 1.6rem !important;
        transition: all .2s !important;
    }

    .swal2-cancel:hover {
        background: rgba(255, 255, 255, .16) !important;
        color: #fff !important;
    }

    /* Deny button */
    .swal2-deny {
        background: linear-gradient(135deg, rgba(239, 68, 68, .7), rgba(185, 28, 28, .6)) !important;
        border: 1px solid rgba(239, 68, 68, .4) !important;
        border-radius: 50px !important;
        font-family: 'Outfit', sans-serif !important;
        font-weight: 600 !important;
        font-size: .88rem !important;
        padding: .55rem 1.6rem !important;
    }

    /* Timer progress bar */
    .swal2-timer-progress-bar {
        background: linear-gradient(90deg, #6366f1, #a855f7) !important;
        height: 3px !important;
    }

    /* Backdrop */
    .swal2-backdrop-show {
        background: rgba(0, 0, 0, .6) !important;
        backdrop-filter: blur(4px) !important;
    }

    /* Input (jika ada) */
    .swal2-input,
    .swal2-textarea {
        background: rgba(255, 255, 255, .08) !important;
        border: 1px solid rgba(255, 255, 255, .18) !important;
        border-radius: 12px !important;
        color: #fff !important;
        font-family: 'Outfit', sans-serif !important;
    }

    .swal2-input:focus,
    .swal2-textarea:focus {
        border-color: rgba(99, 102, 241, .7) !important;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, .2) !important;
    }
</style>
