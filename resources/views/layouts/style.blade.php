  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

  {{-- Bootstrap 5 CSS --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">

  {{-- Select2 --}}
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">

  {{-- ✅ DataTables — pakai SATU versi saja (Bootstrap5 adapter) --}}
  {{-- <link rel="stylesheet" href="https://cdn.datatables.net/2.3.7/css/dataTables.dataTables.css" /> --}}

  <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap5.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.2/css/buttons.bootstrap5.min.css">

  {{-- FontAwesome --}}
  <script src="https://kit.fontawesome.com/49e1d285c0.js" crossorigin="anonymous"></script>

  <style>
      body {
          font-family: 'Outfit', sans-serif;
          background: linear-gradient(135deg, #0f0c29 0%, #1a1a4e 40%, #24243e 100%);
          min-height: 100vh;
          position: relative;
          overflow-x: hidden;
      }

      body::before {
          content: '';
          position: fixed;
          width: 500px;
          height: 500px;
          background: radial-gradient(circle, rgba(99, 102, 241, 0.25) 0%, transparent 70%);
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
          width: 400px;
          height: 400px;
          background: radial-gradient(circle, rgba(236, 72, 153, 0.2) 0%, transparent 70%);
          bottom: -80px;
          right: -80px;
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
              transform: translate(30px, -30px) scale(1.05);
          }

          66% {
              transform: translate(-20px, 20px) scale(0.95);
          }
      }

      /* ── Sidebar ── */
      #sidebar {
          width: 250px;
          min-height: 100vh;
          background-color: #1e293b;
          transition: width 0.3s ease;
          flex-shrink: 0;
          position: relative;
          z-index: 10;
      }

      #sidebar.collapsed {
          width: 70px;
      }

      #sidebar .sidebar-brand {
          height: 60px;
          background-color: #232D49;
          display: flex;
          align-items: center;
          padding: 0 1rem;
          white-space: nowrap;
          overflow: hidden;
      }

      #sidebar .brand-text {
          color: #fff;
          font-weight: 700;
          font-size: 1rem;
          margin-left: 0.5rem;
          transition: opacity 0.2s;
      }

      #sidebar.collapsed .brand-text,
      #sidebar.collapsed .nav-label {
          opacity: 0;
          pointer-events: none;
          width: 0;
          overflow: hidden;
      }

      #sidebar .nav-link {
          color: #94a3b8;
          padding: 0.75rem 1rem;
          display: flex;
          align-items: center;
          gap: 0.75rem;
          white-space: nowrap;
          border-radius: 0.375rem;
          margin: 2px 8px;
          transition: background 0.2s, color 0.2s;
      }

      #sidebar .nav-link:hover,
      #sidebar .nav-link.active {
          background-color: #334155;
          color: #fff;
      }

      #sidebar .nav-link i {
          font-size: 1.1rem;
          flex-shrink: 0;
      }

      /* ── Topbar ── */
      #topbar {
          height: 60px;
          background: #212C44;
          border-bottom: 1px solid #34347D;
          display: flex;
          align-items: center;
          padding: 0 1.25rem;
          gap: 1rem;
          position: sticky;
          top: 0;
          z-index: 100;
          color: white;
      }

      /* ── Content ── */
      #content {
          flex: 1;
          overflow-x: auto;
          background: radial-gradient(circle, rgba(60, 62, 126, 0.25) 0%, transparent 70%);
          position: relative;
          z-index: 1;
      }

      /* ════════════════════════════════
           TABLE STYLES
        ════════════════════════════════ */
      table.dataTable {
          border-collapse: collapse !important;
          width: 100% !important;
          margin: 0 !important;
      }

      table.dataTable thead th,
      table.dataTable thead td {
          background: rgba(255, 255, 255, .05) !important;
          color: rgba(255, 255, 255, .5) !important;
          font-size: .73rem !important;
          font-weight: 600 !important;
          letter-spacing: .07em !important;
          text-transform: uppercase !important;
          padding: .95rem 1.2rem !important;
          border-bottom: 1px solid rgba(255, 255, 255, .1) !important;
          border-top: none !important;
          white-space: nowrap;
      }

      table.dataTable thead th:hover {
          color: rgba(255, 255, 255, .85) !important;
      }

      table.dataTable thead .dt-column-order::before,
      table.dataTable thead .dt-column-order::after {
          opacity: .25 !important;
      }

      table.dataTable thead th.dt-ordering-asc .dt-column-order::before,
      table.dataTable thead th.dt-ordering-desc .dt-column-order::after {
          opacity: 1 !important;
          color: #818cf8 !important;
      }

      table.dataTable tbody tr {
          border-bottom: 1px solid rgba(255, 255, 255, .05) !important;
          transition: background .2s !important;
      }

      table.dataTable tbody tr:last-child {
          border-bottom: none !important;
      }

      table.dataTable tbody tr:hover>* {
          background: rgba(99, 102, 241, .1) !important;
          box-shadow: none !important;
      }

      table.dataTable tbody tr.selected>* {
          background: rgba(99, 102, 241, .18) !important;
          box-shadow: none !important;
      }

      table.dataTable tbody td {
          color: rgba(255, 255, 255, .78) !important;
          padding: .9rem 1.2rem !important;
          border-top: none !important;
          vertical-align: middle !important;
          background: transparent !important;
      }

      table.dataTable.stripe tbody tr.odd>* {
          background: rgba(255, 255, 255, .02) !important;
      }

      /* Pagination */
      div.dt-container .dt-paging .pagination .page-item .page-link {
          background: rgba(255, 255, 255, .07) !important;
          border: 1px solid rgba(255, 255, 255, .12) !important;
          color: rgba(255, 255, 255, .6) !important;
          border-radius: 9px !important;
          margin: 0 2px !important;
          font-size: .78rem !important;
          font-weight: 600 !important;
          min-width: 32px;
          text-align: center;
          transition: all .2s !important;
      }

      div.dt-container .dt-paging .pagination .page-item .page-link:hover {
          background: rgba(255, 255, 255, .16) !important;
          color: #fff !important;
      }

      div.dt-container .dt-paging .pagination .page-item.active .page-link {
          background: linear-gradient(135deg, rgba(99, 102, 241, .85), rgba(168, 85, 247, .85)) !important;
          border-color: rgba(168, 85, 247, .4) !important;
          color: #fff !important;
          box-shadow: 0 3px 10px rgba(99, 102, 241, .4) !important;
      }

      div.dt-container .dt-paging .pagination .page-item.disabled .page-link {
          opacity: .3 !important;
      }

      /* DT Container */
      div.dt-container {
          padding: 0 !important;
          color: rgba(255, 255, 255, .8) !important;
      }

      div.dt-container .dt-layout-row {
          padding: .9rem 1.4rem !important;
          margin: 0 !important;
      }

      div.dt-container .dt-layout-row:first-child {
          border-bottom: 1px solid rgba(255, 255, 255, .06);
          background: rgba(255, 255, 255, .02);
      }

      div.dt-container .dt-layout-row:last-child {
          border-top: 1px solid rgba(255, 255, 255, .06);
          background: rgba(255, 255, 255, .02);
          padding: .8rem 1.4rem !important;
      }

      div.dt-container select.dt-input {
          background: rgba(255, 255, 255, .08) !important;
          border: 1px solid rgba(255, 255, 255, .15) !important;
          border-radius: 50px !important;
          color: rgba(255, 255, 255, .8) !important;
          font-size: .8rem !important;
          padding: .35rem .9rem !important;
          outline: none;
      }

      div.dt-container select.dt-input option {
          background: #1a1a4e;
      }

      div.dt-container input.dt-input {
          background: rgba(255, 255, 255, .08) !important;
          border: 1px solid rgba(255, 255, 255, .15) !important;
          border-radius: 50px !important;
          color: #fff !important;
          font-size: .8rem !important;
          padding: .38rem 1rem !important;
          outline: none;
          transition: border-color .2s, background .2s;
      }

      div.dt-container input.dt-input:focus {
          border-color: rgba(99, 102, 241, .7) !important;
          background: rgba(255, 255, 255, .13) !important;
      }

      div.dt-container input.dt-input::placeholder {
          color: rgba(255, 255, 255, .35) !important;
      }

      div.dt-container .dt-length label,
      div.dt-container .dt-search label,
      div.dt-container .dt-info {
          color: rgba(255, 255, 255, .45) !important;
          font-size: .78rem !important;
      }

      /* ✅ Buttons plugin override */
      div.dt-container .dt-buttons {
          display: inline-flex;
          gap: 6px;
          flex-wrap: wrap;
      }

      div.dt-container .dt-buttons .btn {
          background: rgba(255, 255, 255, .08) !important;
          border: 1px solid rgba(255, 255, 255, .15) !important;
          border-radius: 50px !important;
          color: rgba(255, 255, 255, .8) !important;
          font-size: .78rem !important;
          font-weight: 600;
          padding: .38rem 1rem !important;
          transition: all .2s;
      }

      div.dt-container .dt-buttons .btn:hover {
          background: rgba(255, 255, 255, .18) !important;
          color: #fff !important;
      }

      /* ✅ Excel button — hijau */
      div.dt-container .dt-buttons .buttons-excel {
          background: linear-gradient(135deg, rgba(16, 185, 129, .75), rgba(5, 150, 105, .65)) !important;
          border-color: rgba(16, 185, 129, .45) !important;
          color: #fff !important;
          box-shadow: 0 3px 12px rgba(16, 185, 129, .35) !important;
      }

      div.dt-container .dt-buttons .buttons-excel:hover {
          box-shadow: 0 5px 18px rgba(16, 185, 129, .55) !important;
      }

      /* ✅ PDF button — merah */
      div.dt-container .dt-buttons .buttons-pdf {
          background: linear-gradient(135deg, rgba(239, 68, 68, .7), rgba(185, 28, 28, .6)) !important;
          border-color: rgba(239, 68, 68, .4) !important;
          color: #fff !important;
          box-shadow: 0 3px 12px rgba(239, 68, 68, .3) !important;
      }

      /* ✅ Print button */
      div.dt-container .dt-buttons .buttons-print {
          background: rgba(255, 255, 255, .1) !important;
      }

      /* Cell components */
      .user-cell {
          display: flex;
          align-items: center;
          gap: 11px;
      }

      .u-avatar {
          width: 36px;
          height: 36px;
          border-radius: 11px;
          flex-shrink: 0;
          display: flex;
          align-items: center;
          justify-content: center;
          font-weight: 700;
          font-size: .85rem;
          color: #fff;
          border: 1.5px solid rgba(255, 255, 255, .2);
      }

      .u-name {
          font-weight: 600;
          color: #fff;
          font-size: .85rem;
          line-height: 1.2;
      }

      .u-email {
          font-size: .72rem;
          color: rgba(255, 255, 255, .42);
          margin-top: 1px;
      }

      .badge-glass {
          display: inline-flex;
          align-items: center;
          gap: 5px;
          padding: 3px 11px;
          border-radius: 20px;
          font-size: .7rem;
          font-weight: 600;
          letter-spacing: .03em;
          white-space: nowrap;
      }

      .badge-glass .dot {
          width: 6px;
          height: 6px;
          border-radius: 50%;
          display: inline-block;
      }

      .badge-active {
          background: rgba(16, 185, 129, .18);
          border: 1px solid rgba(16, 185, 129, .32);
          color: #6ee7b7;
      }

      .badge-active .dot {
          background: #34d399;
          box-shadow: 0 0 5px #34d399;
      }

      .badge-pending {
          background: rgba(245, 158, 11, .18);
          border: 1px solid rgba(245, 158, 11, .32);
          color: #fcd34d;
      }

      .badge-pending .dot {
          background: #fbbf24;
          box-shadow: 0 0 5px #fbbf24;
      }

      .badge-inactive {
          background: rgba(107, 114, 128, .18);
          border: 1px solid rgba(107, 114, 128, .28);
          color: #9ca3af;
      }

      .badge-inactive .dot {
          background: #6b7280;
      }

      .badge-blocked {
          background: rgba(239, 68, 68, .18);
          border: 1px solid rgba(239, 68, 68, .3);
          color: #fca5a5;
      }

      .badge-blocked .dot {
          background: #f87171;
      }

      .role-pill {
          display: inline-flex;
          align-items: center;
          gap: 5px;
          padding: 3px 9px;
          border-radius: 6px;
          font-size: .7rem;
          font-weight: 600;
          background: rgba(255, 255, 255, .08);
          border: 1px solid rgba(255, 255, 255, .12);
          color: rgba(255, 255, 255, .7);
      }

      .role-admin {
          background: rgba(99, 102, 241, .2);
          border-color: rgba(99, 102, 241, .35);
          color: #a5b4fc;
      }

      .role-editor {
          background: rgba(236, 72, 153, .18);
          border-color: rgba(236, 72, 153, .3);
          color: #f9a8d4;
      }

      .role-viewer {
          background: rgba(20, 184, 166, .18);
          border-color: rgba(20, 184, 166, .3);
          color: #5eead4;
      }

      .prog-wrap {
          min-width: 100px;
      }

      .prog-label {
          display: flex;
          justify-content: space-between;
          font-size: .7rem;
          color: rgba(255, 255, 255, .45);
          margin-bottom: 4px;
      }

      .prog-track {
          height: 5px;
          background: rgba(255, 255, 255, .1);
          border-radius: 10px;
          overflow: hidden;
      }

      .prog-fill {
          height: 100%;
          border-radius: 10px;
          transition: width 1s cubic-bezier(.4, 0, .2, 1);
      }

      .p-blue {
          background: linear-gradient(90deg, #6366f1, #818cf8);
      }

      .p-green {
          background: linear-gradient(90deg, #10b981, #34d399);
      }

      .p-yellow {
          background: linear-gradient(90deg, #f59e0b, #fcd34d);
      }

      .p-red {
          background: linear-gradient(90deg, #ef4444, #f87171);
      }

      .action-group {
          display: flex;
          align-items: center;
          gap: 5px;
      }

      .act-btn {
          width: 30px;
          height: 30px;
          border-radius: 8px;
          border: 1px solid rgba(255, 255, 255, .12);
          background: rgba(255, 255, 255, .07);
          color: rgba(255, 255, 255, .6);
          display: flex;
          align-items: center;
          justify-content: center;
          font-size: .75rem;
          cursor: pointer;
          transition: all .2s;
          text-decoration: none;
      }

      .act-btn:hover {
          color: #fff;
          transform: scale(1.12);
      }

      .act-btn.v:hover {
          background: rgba(99, 102, 241, .3);
          border-color: rgba(99, 102, 241, .5);
          color: #a5b4fc;
      }

      .act-btn.e:hover {
          background: rgba(245, 158, 11, .25);
          border-color: rgba(245, 158, 11, .4);
          color: #fcd34d;
      }

      .act-btn.d:hover {
          background: rgba(239, 68, 68, .25);
          border-color: rgba(239, 68, 68, .4);
          color: #fca5a5;
      }

      .glass-btn {
          display: inline-flex;
          align-items: center;
          gap: 6px;
          padding: .42rem 1rem;
          border-radius: 50px;
          font-size: .78rem;
          font-weight: 600;
          cursor: pointer;
          border: 1px solid rgba(255, 255, 255, .18);
          background: rgba(255, 255, 255, .09);
          color: rgba(255, 255, 255, .85);
          transition: all .2s;
          text-decoration: none;
          white-space: nowrap;
          backdrop-filter: blur(6px);
      }

      .glass-btn:hover {
          background: rgba(255, 255, 255, .18);
          color: #fff;
          transform: translateY(-1px);
          box-shadow: 0 4px 14px rgba(0, 0, 0, .2);
      }

      .glass-btn.primary {
          background: linear-gradient(135deg, rgba(99, 102, 241, .75), rgba(168, 85, 247, .75));
          border-color: rgba(168, 85, 247, .4);
          color: #fff;
          box-shadow: 0 4px 14px rgba(99, 102, 241, .3);
      }

      .glass-btn.primary:hover {
          box-shadow: 0 6px 20px rgba(99, 102, 241, .5);
      }

      .stats-wrapper {
          width: 100%;
          position: relative;
          z-index: 1;
      }

      .section-title {
          color: rgba(202, 204, 220, 0.9);
          font-size: 1.1rem;
          font-weight: 600;
          letter-spacing: .06em;
          text-transform: uppercase;
          margin-bottom: 1.5rem;
          display: flex;
          align-items: center;
          gap: 10px;
      }

      .section-title::after {
          content: '';
          flex: 1;
          height: 1px;
          background: linear-gradient(90deg, rgba(99, 102, 241, .35), transparent);
      }

      .glass-card {
          background: rgba(255, 255, 255, 0.06);
          backdrop-filter: blur(24px) saturate(180%);
          -webkit-backdrop-filter: blur(24px) saturate(180%);
          border: 1px solid rgba(255, 255, 255, 0.13);
          border-radius: 24px;
          overflow: hidden;
          z-index: 1;
          position: relative;
          box-shadow: 0 8px 40px rgba(0, 0, 0, .25), inset 0 1px 0 rgba(255, 255, 255, .18);
          animation: fadeUp .5s cubic-bezier(.22, .68, 0, 1.2) .4s both;
      }

      @keyframes fadeUp {
          from {
              opacity: 0;
              transform: translateY(30px)
          }

          to {
              opacity: 1;
              transform: translateY(0)
          }
      }

      .glass-toolbar {
          display: flex;
          align-items: center;
          justify-content: space-between;
          flex-wrap: wrap;
          gap: .75rem;
          padding: 1.1rem 1.4rem;
          border-bottom: 1px solid rgba(255, 255, 255, .08);
          background: rgba(255, 255, 255, .03);
      }

      .glass-toolbar-title {
          font-size: 1rem;
          font-weight: 700;
          color: #fff;
          display: flex;
          align-items: center;
          gap: 8px;
      }

      .glass-toolbar-title .t-icon {
          width: 34px;
          height: 34px;
          border-radius: 10px;
          background: linear-gradient(135deg, rgba(99, 102, 241, .75), rgba(168, 85, 247, .75));
          display: flex;
          align-items: center;
          justify-content: center;
          font-size: .85rem;
          color: #fff;
          box-shadow: 0 3px 10px rgba(99, 102, 241, .35);
      }

      .glass-dt-top {
          padding: 1rem 1.4rem !important;
          border-bottom: 1px solid rgba(255, 255, 255, .08);
          background: rgba(255, 255, 255, .02);
          margin: 0 !important;
      }

      .glass-dt-top .dt-length label,
      .glass-dt-top .dt-search label {
          margin: 0 !important;
      }

      div.dt-container .dt-buttons {
          display: inline-flex;
          gap: 6px;
          margin: 0 !important;
      }

      /* ════════════════════════════════════════
            LENGTH SELECT — dropdown angka tampilkan
        ════════════════════════════════════════ */
      div.dt-container .dt-length {
          display: flex !important;
          align-items: center !important;
      }

      div.dt-container .dt-length label {
          display: flex !important;
          align-items: center !important;
          gap: 8px !important;
          color: rgba(255, 255, 255, .55) !important;
          font-size: .8rem !important;
          margin: 0 !important;
      }

      /* Wrapper select biar bisa styling native select */
      div.dt-container .dt-length label select,
      div.dt-container select.dt-input {
          background-color: rgba(30, 27, 75, 0.85) !important;
          /* dark navy biar terlihat */
          border: 1px solid rgba(99, 102, 241, .5) !important;
          border-radius: 50px !important;
          color: #fff !important;
          font-size: .8rem !important;
          padding: .38rem 2rem .38rem 1rem !important;
          outline: none !important;
          box-shadow: 0 0 0 1px rgba(99, 102, 241, .2), inset 0 1px 0 rgba(255, 255, 255, .08) !important;
          transition: border-color .2s, box-shadow .2s !important;
          cursor: pointer !important;
          -webkit-appearance: auto !important;
          appearance: auto !important;
          /* Warna dropdown arrow */
          color-scheme: dark !important;
      }

      div.dt-container .dt-length label select:hover,
      div.dt-container select.dt-input:hover,
      div.dt-container .dt-length label select:focus,
      div.dt-container select.dt-input:focus {
          border-color: rgba(99, 102, 241, .8) !important;
          box-shadow: 0 0 0 3px rgba(99, 102, 241, .25), inset 0 1px 0 rgba(255, 255, 255, .1) !important;
      }

      div.dt-container .dt-length label select option,
      div.dt-container select.dt-input option {
          background: #1e1b4b !important;
          color: #fff !important;
      }

      /* ════════════════════════════════════════
        SEARCH INPUT
        ════════════════════════════════════════ */
      div.dt-container .dt-search {
          display: flex !important;
          align-items: center !important;
      }

      div.dt-container .dt-search {
          display: flex !important;
          align-items: center !important;
          gap: 8px !important;
          color: rgba(255, 255, 255, .55) !important;
          font-size: .8rem !important;
          margin: 0 !important;
      }

      div.dt-container div.dt-search input,
      div.dt-container input.dt-input {
          background: rgba(255, 255, 255, .07) !important;
          border: 1px solid rgba(99, 102, 241, .4) !important;
          border-radius: 50px !important;
          color: #fff !important;
          font-size: .82rem !important;
          padding: .42rem 1.2rem !important;
          outline: none !important;
          box-shadow: 0 2px 10px rgba(0, 0, 0, .2), inset 0 1px 0 rgba(255, 255, 255, .06) !important;
          transition: border-color .25s, background .25s, box-shadow .25s !important;
          min-width: 210px !important;
          color-scheme: dark !important;
      }

      div.dt-container div.dt-search input:focus,
      div.dt-container input.dt-input:focus {
          border-color: rgba(99, 102, 241, .75) !important;
          background: rgba(255, 255, 255, .11) !important;
          box-shadow: 0 0 0 3px rgba(99, 102, 241, .2), 0 2px 10px rgba(0, 0, 0, .2) !important;
      }

      div.dt-container div.dt-search input::placeholder,
      div.dt-container input.dt-input::placeholder {
          color: rgba(255, 255, 255, .28) !important;
      }

      div.dt-container div.dt-length,
      div.dt-container div.dt-search,
      div.dt-container .dt-info {
          color: rgba(255, 255, 255, .45) !important;
          font-size: .78rem !important;
      }


      /* ── FORCE override search input ── */
      .myTable input {
          background: rgba(30, 27, 75, 0.85) !important;
          border: 1px solid rgba(99, 102, 241, .5) !important;
          border-radius: 50px !important;
          color: #fff !important;
          font-size: .82rem !important;
          padding: .42rem 1.2rem !important;
          outline: none !important;
          box-shadow: 0 0 0 1px rgba(99, 102, 241, .2) !important;
          transition: all .25s !important;
          min-width: 210px !important;
          color-scheme: dark !important;
      }

      .myTable_filter input:focus {
          border-color: rgba(99, 102, 241, .85) !important;
          background: rgba(255, 255, 255, .11) !important;
          box-shadow: 0 0 0 3px rgba(99, 102, 241, .25) !important;
      }

      .myTable_filter input::placeholder {
          color: rgba(255, 255, 255, .3) !important;
      }

      .myTable_filter label {
          color: rgba(255, 255, 255, .5) !important;
          font-size: .8rem !important;
          display: flex !important;
          align-items: center !important;
          gap: 8px !important;
      }

      /* ── FORCE override length select ── */
      .myTable_length select {
          background: rgba(30, 27, 75, 0.85) !important;
          border: 1px solid rgba(99, 102, 241, .5) !important;
          border-radius: 50px !important;
          color: #fff !important;
          font-size: .8rem !important;
          padding: .38rem 1rem !important;
          outline: none !important;
          color-scheme: dark !important;
          cursor: pointer !important;
      }

      .myTable_length select:focus,
      .myTable_length select:hover {
          border-color: rgba(99, 102, 241, .85) !important;
          box-shadow: 0 0 0 3px rgba(99, 102, 241, .2) !important;
      }

      .myTable_length label {
          color: rgba(255, 255, 255, .5) !important;
          font-size: .8rem !important;
          display: flex !important;
          align-items: center !important;
          gap: 8px !important;
      }

      /* ── Chevron rotate saat open ── */
      [href="#menuPegawai"][aria-expanded="true"] #chevronPegawai {
          transform: rotate(180deg);
      }

      /* ── Submenu border kiri ── */
      #menuPegawai .nav-link {
          border-left: 2px solid rgba(99, 102, 241, .25);
          border-radius: 0 8px 8px 0;
          margin: 1px 8px 1px 0;
          color: #64748b;
          font-size: .85rem;
      }

      #menuPegawai .nav-link:hover,
      #menuPegawai .nav-link.active {
          border-left-color: #818cf8;
          background: rgba(99, 102, 241, .15);
          color: #fff;
      }

      /* ── Sembunyikan submenu saat sidebar collapsed ── */
      #sidebar.collapsed #menuPegawai {
          display: none !important;
      }

      #sidebar.collapsed [href="#menuPegawai"] #chevronPegawai {
          display: none;
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
