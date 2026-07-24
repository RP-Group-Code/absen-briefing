@extends('layouts.app')

@section('title', 'Master Dashboard')

{{-- ══════════════════════════════════════════════
     STYLES  — masuk ke <head> via @stack('styles')
══════════════════════════════════════════════ --}}
@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.2/css/buttons.bootstrap5.min.css">
@endpush

@section('content')
<style>
/* ============================================
   STAT CARDS
============================================ */
@keyframes cardEntrance {
    from { opacity:0; transform:translateY(40px) scale(0.96); }
    to   { opacity:1; transform:translateY(0)    scale(1);    }
}
.stats-wrapper { width:100%; position:relative; z-index:1; }

.stat-card {
    position:relative; border-radius:24px; padding:0; overflow:hidden;
    backdrop-filter:blur(20px) saturate(180%);
    -webkit-backdrop-filter:blur(20px) saturate(180%);
    border:1px solid rgba(255,255,255,.18);
    box-shadow:0 8px 32px rgba(0,0,0,.37),0 2px 8px rgba(0,0,0,.2),inset 0 1px 0 rgba(255,255,255,.3);
    transition:transform .35s cubic-bezier(.22,.68,0,1.2),box-shadow .35s ease;
    cursor:pointer;
    animation:cardEntrance .6s cubic-bezier(.22,.68,0,1.2) both;
}
.stat-card:nth-child(1){animation-delay:.05s}
.stat-card:nth-child(2){animation-delay:.15s}
.stat-card:nth-child(3){animation-delay:.25s}
.stat-card:nth-child(4){animation-delay:.35s}
.stat-card:hover{
    transform:translateY(-8px) scale(1.02);
    box-shadow:0 24px 60px rgba(0,0,0,.5),0 6px 20px rgba(0,0,0,.3),inset 0 1px 0 rgba(255,255,255,.4);
}
.stat-card--blue   { background:linear-gradient(145deg,rgba(59,130,246,.55),rgba(37,99,235,.45),rgba(29,78,216,.35)); }
.stat-card--green  { background:linear-gradient(145deg,rgba(16,185,129,.55),rgba(5,150,105,.45),rgba(4,120,87,.35));  }
.stat-card--yellow { background:linear-gradient(145deg,rgba(251,191,36,.6),rgba(245,158,11,.5),rgba(217,119,6,.4));   }
.stat-card--red    { background:linear-gradient(145deg,rgba(239,68,68,.55),rgba(220,38,38,.45),rgba(185,28,28,.35)); }

.stat-card__shine {
    position:absolute;inset:0;border-radius:inherit;pointer-events:none;z-index:1;
    background:linear-gradient(135deg,rgba(255,255,255,.22) 0%,rgba(255,255,255,.06) 40%,transparent 60%);
}
.stat-card__bg-icon {
    position:absolute;right:-10px;top:50%;transform:translateY(-50%);
    font-size:7rem;opacity:.12;color:#fff;pointer-events:none;z-index:0;
    transition:opacity .3s,transform .3s;line-height:1;
}
.stat-card:hover .stat-card__bg-icon{opacity:.2;transform:translateY(-50%) scale(1.1) rotate(-5deg);}
.stat-card__body{position:relative;z-index:2;padding:1.6rem 1.6rem 0;}
.stat-card__badge{
    display:inline-flex;align-items:center;gap:6px;
    background:rgba(255,255,255,.18);border:1px solid rgba(255,255,255,.25);
    color:rgba(255,255,255,.9);font-size:.7rem;font-weight:600;
    letter-spacing:.08em;text-transform:uppercase;padding:4px 10px;
    border-radius:20px;margin-bottom:.8rem;backdrop-filter:blur(4px);
}
.stat-card__value{
    font-size:3rem;font-weight:800;color:#fff;line-height:1;
    margin-bottom:.3rem;text-shadow:0 2px 12px rgba(0,0,0,.3);letter-spacing:-1px;
}
.stat-card__value sup{font-size:1.2rem;font-weight:600;vertical-align:super;margin-left:1px;}
.stat-card__label{font-size:.92rem;font-weight:400;color:rgba(255,255,255,.82);margin-bottom:0;}
.stat-card__footer{
    position:relative;z-index:2;margin-top:1.4rem;padding:.85rem 1.6rem;
    background:rgba(0,0,0,.18);border-top:1px solid rgba(255,255,255,.12);
    display:flex;align-items:center;justify-content:space-between;
}
.stat-card__more-link{
    color:rgba(255,255,255,.85);text-decoration:none;font-size:.82rem;
    font-weight:600;letter-spacing:.04em;display:inline-flex;align-items:center;
    gap:6px;transition:color .2s,gap .2s;
}
.stat-card__more-link:hover{color:#fff;gap:10px;}
.stat-card__trend{font-size:.75rem;font-weight:600;display:flex;align-items:center;gap:4px;}
.stat-card__trend.up  {color:#86efac;}
.stat-card__trend.down{color:#fca5a5;}
.stat-card__progress-bar{
    position:absolute;bottom:0;left:0;height:3px;
    border-radius:0 2px 2px 0;background:rgba(255,255,255,.6);
    transition:width 1.2s cubic-bezier(.4,0,.2,1);
}
.section-title{
    color:rgba(53,54,70,.9);font-size:1.1rem;font-weight:600;
    letter-spacing:.06em;text-transform:uppercase;margin-bottom:1.5rem;
    display:flex;align-items:center;gap:10px;
}
.section-title::after{
    content:'';flex:1;height:1px;
    background:linear-gradient(90deg,rgba(99,102,241,.35),transparent);
}

/* ============================================
   GLASS TABLE
   z-index:1 — LEBIH RENDAH dari navbar/sidebar
============================================ */
.glass-card{
    background:rgba(255,255,255,.06);
    backdrop-filter:blur(24px) saturate(180%);
    -webkit-backdrop-filter:blur(24px) saturate(180%);
    border:1px solid rgba(255,255,255,.13);
    border-radius:24px;overflow:hidden;
    position:relative; z-index:1;              /* ← kunci: jangan > z-index navbar */
    box-shadow:0 8px 40px rgba(0,0,0,.25),inset 0 1px 0 rgba(255,255,255,.18);
    animation:fadeUpCard .5s cubic-bezier(.22,.68,0,1.2) .4s both;
}
@keyframes fadeUpCard{
    from{opacity:0;transform:translateY(30px);}
    to  {opacity:1;transform:translateY(0);}
}
.glass-toolbar{
    display:flex;align-items:center;justify-content:space-between;
    flex-wrap:wrap;gap:.75rem;padding:1.1rem 1.4rem;
    border-bottom:1px solid rgba(255,255,255,.08);
    background:rgba(255,255,255,.03);
}
.glass-toolbar-title{font-size:1rem;font-weight:700;color:#fff;display:flex;align-items:center;gap:8px;}
.glass-toolbar-title .t-icon{
    width:34px;height:34px;border-radius:10px;
    background:linear-gradient(135deg,rgba(99,102,241,.75),rgba(168,85,247,.75));
    display:flex;align-items:center;justify-content:center;
    font-size:.85rem;color:#fff;box-shadow:0 3px 10px rgba(99,102,241,.35);
}
.glass-btn{
    display:inline-flex;align-items:center;gap:6px;padding:.42rem 1rem;
    border-radius:50px;font-size:.78rem;font-weight:600;cursor:pointer;
    border:1px solid rgba(255,255,255,.18);background:rgba(255,255,255,.09);
    color:rgba(255,255,255,.85);transition:all .2s;text-decoration:none;white-space:nowrap;
    backdrop-filter:blur(6px);
}
.glass-btn:hover{background:rgba(255,255,255,.18);color:#fff;transform:translateY(-1px);}
.glass-btn.primary{
    background:linear-gradient(135deg,rgba(99,102,241,.75),rgba(168,85,247,.75));
    border-color:rgba(168,85,247,.4);color:#fff;box-shadow:0 4px 14px rgba(99,102,241,.3);
}
.glass-btn.primary:hover{box-shadow:0 6px 20px rgba(99,102,241,.5);}

/* ── DataTables override ── */
div.dt-container{padding:0 !important;color:rgba(255,255,255,.8) !important;}
div.dt-container .dt-layout-row{padding:.85rem 1.3rem !important;margin:0 !important;}
div.dt-container .dt-layout-row:first-child{border-bottom:1px solid rgba(255,255,255,.06);background:rgba(255,255,255,.02);}
div.dt-container .dt-layout-row:last-child {border-top:1px solid rgba(255,255,255,.06);background:rgba(255,255,255,.02);}
div.dt-container select.dt-input{
    background:rgba(255,255,255,.08) !important;border:1px solid rgba(255,255,255,.15) !important;
    border-radius:50px !important;color:rgba(255,255,255,.8) !important;
    font-size:.8rem !important;padding:.35rem .9rem !important;outline:none;
}
div.dt-container select.dt-input option{background:#1a1a4e;}
div.dt-container input.dt-input{
    background:rgba(255,255,255,.08) !important;border:1px solid rgba(255,255,255,.15) !important;
    border-radius:50px !important;color:#fff !important;font-size:.8rem !important;
    padding:.38rem 1rem !important;outline:none;transition:border-color .2s,background .2s;
}
div.dt-container input.dt-input:focus{border-color:rgba(99,102,241,.7) !important;background:rgba(255,255,255,.13) !important;}
div.dt-container input.dt-input::placeholder{color:rgba(255,255,255,.35) !important;}
div.dt-container .dt-length label,
div.dt-container .dt-search label,
div.dt-container .dt-info{color:rgba(255,255,255,.45) !important;font-size:.78rem !important;}

table.dataTable{border-collapse:collapse !important;width:100% !important;margin:0 !important;}
table.dataTable thead th,table.dataTable thead td{
    background:rgba(255,255,255,.05) !important;color:rgba(255,255,255,.5) !important;
    font-size:.73rem !important;font-weight:600 !important;letter-spacing:.07em !important;
    text-transform:uppercase !important;padding:.9rem 1.2rem !important;
    border-bottom:1px solid rgba(255,255,255,.1) !important;border-top:none !important;white-space:nowrap;
}
table.dataTable thead th:hover{color:rgba(255,255,255,.85) !important;}
table.dataTable thead .dt-column-order::before,
table.dataTable thead .dt-column-order::after{opacity:.25 !important;}
table.dataTable thead th.dt-ordering-asc .dt-column-order::before,
table.dataTable thead th.dt-ordering-desc .dt-column-order::after{opacity:1 !important;color:#818cf8 !important;}
table.dataTable tbody tr{border-bottom:1px solid rgba(255,255,255,.05) !important;transition:background .2s !important;}
table.dataTable tbody tr:last-child{border-bottom:none !important;}
table.dataTable tbody tr:hover > *{background:rgba(99,102,241,.1) !important;box-shadow:none !important;}
table.dataTable tbody tr.selected > *{background:rgba(99,102,241,.18) !important;box-shadow:none !important;}
table.dataTable tbody td{
    color:rgba(255,255,255,.78) !important;padding:.85rem 1.2rem !important;
    border-top:none !important;vertical-align:middle !important;background:transparent !important;
}
table.dataTable.stripe tbody tr.odd > *{background:rgba(255,255,255,.02) !important;}
div.dt-container .dt-paging .pagination .page-item .page-link{
    background:rgba(255,255,255,.07) !important;border:1px solid rgba(255,255,255,.12) !important;
    color:rgba(255,255,255,.6) !important;border-radius:9px !important;
    margin:0 2px !important;font-size:.78rem !important;font-weight:600 !important;
    min-width:32px;text-align:center;transition:all .2s !important;
}
div.dt-container .dt-paging .pagination .page-item .page-link:hover{background:rgba(255,255,255,.16) !important;color:#fff !important;}
div.dt-container .dt-paging .pagination .page-item.active .page-link{
    background:linear-gradient(135deg,rgba(99,102,241,.85),rgba(168,85,247,.85)) !important;
    border-color:rgba(168,85,247,.4) !important;color:#fff !important;
    box-shadow:0 3px 10px rgba(99,102,241,.4) !important;
}
div.dt-container .dt-paging .pagination .page-item.disabled .page-link{opacity:.3 !important;}

/* Cell components */
.user-cell{display:flex;align-items:center;gap:11px;}
.u-avatar{width:36px;height:36px;border-radius:11px;flex-shrink:0;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.85rem;color:#fff;border:1.5px solid rgba(255,255,255,.2);}
.u-name {font-weight:600;color:#fff;font-size:.85rem;line-height:1.2;}
.u-email{font-size:.72rem;color:rgba(255,255,255,.42);margin-top:1px;}
.badge-glass{display:inline-flex;align-items:center;gap:5px;padding:3px 11px;border-radius:20px;font-size:.7rem;font-weight:600;letter-spacing:.03em;white-space:nowrap;}
.badge-glass .dot{width:6px;height:6px;border-radius:50%;display:inline-block;}
.badge-active  {background:rgba(16,185,129,.18);border:1px solid rgba(16,185,129,.32);color:#6ee7b7;}
.badge-active .dot  {background:#34d399;box-shadow:0 0 5px #34d399;}
.badge-pending {background:rgba(245,158,11,.18);border:1px solid rgba(245,158,11,.32);color:#fcd34d;}
.badge-pending .dot {background:#fbbf24;box-shadow:0 0 5px #fbbf24;}
.badge-inactive{background:rgba(107,114,128,.18);border:1px solid rgba(107,114,128,.28);color:#9ca3af;}
.badge-inactive .dot{background:#6b7280;}
.badge-blocked {background:rgba(239,68,68,.18);border:1px solid rgba(239,68,68,.3);color:#fca5a5;}
.badge-blocked .dot {background:#f87171;}
.role-pill{display:inline-flex;align-items:center;gap:5px;padding:3px 9px;border-radius:6px;font-size:.7rem;font-weight:600;background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.12);color:rgba(255,255,255,.7);}
.role-admin  {background:rgba(99,102,241,.2); border-color:rgba(99,102,241,.35); color:#a5b4fc;}
.role-editor {background:rgba(236,72,153,.18);border-color:rgba(236,72,153,.3);  color:#f9a8d4;}
.role-viewer {background:rgba(20,184,166,.18);border-color:rgba(20,184,166,.3);  color:#5eead4;}
.prog-wrap{min-width:100px;}
.prog-label{display:flex;justify-content:space-between;font-size:.7rem;color:rgba(255,255,255,.45);margin-bottom:4px;}
.prog-track{height:5px;background:rgba(255,255,255,.1);border-radius:10px;overflow:hidden;}
.prog-fill {height:100%;border-radius:10px;transition:width 1s cubic-bezier(.4,0,.2,1);}
.p-blue  {background:linear-gradient(90deg,#6366f1,#818cf8);}
.p-green {background:linear-gradient(90deg,#10b981,#34d399);}
.p-yellow{background:linear-gradient(90deg,#f59e0b,#fcd34d);}
.p-red   {background:linear-gradient(90deg,#ef4444,#f87171);}
.action-group{display:flex;align-items:center;gap:5px;}
.act-btn{width:30px;height:30px;border-radius:8px;border:1px solid rgba(255,255,255,.12);background:rgba(255,255,255,.07);color:rgba(255,255,255,.6);display:flex;align-items:center;justify-content:center;font-size:.75rem;cursor:pointer;transition:all .2s;text-decoration:none;}
.act-btn:hover{color:#fff;transform:scale(1.12);}
.act-btn.v:hover{background:rgba(99,102,241,.3); border-color:rgba(99,102,241,.5); color:#a5b4fc;}
.act-btn.e:hover{background:rgba(245,158,11,.25);border-color:rgba(245,158,11,.4);color:#fcd34d;}
.act-btn.d:hover{background:rgba(239,68,68,.25); border-color:rgba(239,68,68,.4); color:#fca5a5;}
</style>

<div class="container-fluid px-3 px-md-4 py-4">

    {{-- ══ STAT CARDS ══ --}}
    <div class="stats-wrapper mb-4">
        <p class="section-title">
            <i class="fa-solid fa-chart-line fa-sm"></i>
            Dashboard Overview
        </p>
        <div class="row g-4">
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="stat-card stat-card--blue h-100">
                    <div class="stat-card__shine"></div>
                    <i class="fa-solid fa-cart-shopping stat-card__bg-icon"></i>
                    <div class="stat-card__body">
                        <span class="stat-card__badge"><i class="fa-solid fa-circle fa-xs" style="color:#86efac"></i> Live</span>
                        <div class="stat-card__value">150</div>
                        <p class="stat-card__label">New Orders</p>
                    </div>
                    <div class="stat-card__footer">
                        <a href="#" class="stat-card__more-link">More info <i class="fa-solid fa-arrow-right-long fa-sm"></i></a>
                        <span class="stat-card__trend up"><i class="fa-solid fa-arrow-trend-up fa-xs"></i> +12%</span>
                    </div>
                    <div class="stat-card__progress-bar" style="width:75%"></div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="stat-card stat-card--green h-100">
                    <div class="stat-card__shine"></div>
                    <i class="fa-solid fa-chart-bar stat-card__bg-icon"></i>
                    <div class="stat-card__body">
                        <span class="stat-card__badge"><i class="fa-solid fa-circle fa-xs" style="color:#fde68a"></i> Weekly</span>
                        <div class="stat-card__value">53<sup>%</sup></div>
                        <p class="stat-card__label">Bounce Rate</p>
                    </div>
                    <div class="stat-card__footer">
                        <a href="#" class="stat-card__more-link">More info <i class="fa-solid fa-arrow-right-long fa-sm"></i></a>
                        <span class="stat-card__trend down"><i class="fa-solid fa-arrow-trend-down fa-xs"></i> -5%</span>
                    </div>
                    <div class="stat-card__progress-bar" style="width:53%"></div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="stat-card stat-card--yellow h-100">
                    <div class="stat-card__shine"></div>
                    <i class="fa-solid fa-user-plus stat-card__bg-icon"></i>
                    <div class="stat-card__body">
                        <span class="stat-card__badge"><i class="fa-solid fa-circle fa-xs" style="color:#86efac"></i> Today</span>
                        <div class="stat-card__value">44</div>
                        <p class="stat-card__label">User Registrations</p>
                    </div>
                    <div class="stat-card__footer">
                        <a href="#" class="stat-card__more-link">More info <i class="fa-solid fa-arrow-right-long fa-sm"></i></a>
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
                        <span class="stat-card__badge"><i class="fa-solid fa-circle fa-xs" style="color:#86efac"></i> Monthly</span>
                        <div class="stat-card__value">65</div>
                        <p class="stat-card__label">Unique Visitors</p>
                    </div>
                    <div class="stat-card__footer">
                        <a href="#" class="stat-card__more-link">More info <i class="fa-solid fa-arrow-right-long fa-sm"></i></a>
                        <span class="stat-card__trend up"><i class="fa-solid fa-arrow-trend-up fa-xs"></i> +3%</span>
                    </div>
                    <div class="stat-card__progress-bar" style="width:65%"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- ══ GLASS TABLE ══ --}}
    <div class="glass-card">
        <div class="glass-toolbar">
            <div class="glass-toolbar-title">
                <div class="t-icon"><i class="fa-solid fa-users fa-xs"></i></div>
                Manajemen Pengguna
            </div>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <a href="#" class="glass-btn"><i class="fa-solid fa-file-export fa-xs"></i> Export</a>
                <a href="#" class="glass-btn primary"><i class="fa-solid fa-user-plus fa-xs"></i> Tambah User</a>
            </div>
        </div>

        <div class="table-responsive px-1">
            <table id="usersTable" class="table table-hover w-100">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="checkAll" style="width:15px;height:15px;accent-color:#818cf8;cursor:pointer"></th>
                        <th>#</th><th>Pengguna</th><th>Role</th><th>Status</th>
                        <th>Progress</th><th>Bergabung</th><th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><input type="checkbox" class="row-check" style="width:15px;height:15px;accent-color:#818cf8;cursor:pointer"></td>
                        <td><span style="color:rgba(255,255,255,.3);font-size:.78rem">#001</span></td>
                        <td><div class="user-cell"><div class="u-avatar" style="background:linear-gradient(135deg,#6366f1,#8b5cf6)">AH</div><div><div class="u-name">Ahmad Haris</div><div class="u-email">ahmad.haris@email.com</div></div></div></td>
                        <td><span class="role-pill role-admin"><i class="fa-solid fa-shield-halved fa-xs"></i> Admin</span></td>
                        <td><span class="badge-glass badge-active"><span class="dot"></span> Aktif</span></td>
                        <td><div class="prog-wrap"><div class="prog-label"><span>Tugas</span><span>85%</span></div><div class="prog-track"><div class="prog-fill p-blue" data-w="85%" style="width:0"></div></div></div></td>
                        <td><span style="color:rgba(255,255,255,.45);font-size:.8rem">12 Jan 2024</span></td>
                        <td><div class="action-group"><a href="#" class="act-btn v"><i class="fa-solid fa-eye"></i></a><a href="#" class="act-btn e"><i class="fa-solid fa-pen"></i></a><a href="#" class="act-btn d"><i class="fa-solid fa-trash"></i></a></div></td>
                    </tr>
                    <tr>
                        <td><input type="checkbox" class="row-check" style="width:15px;height:15px;accent-color:#818cf8;cursor:pointer"></td>
                        <td><span style="color:rgba(255,255,255,.3);font-size:.78rem">#002</span></td>
                        <td><div class="user-cell"><div class="u-avatar" style="background:linear-gradient(135deg,#ec4899,#f43f5e)">SR</div><div><div class="u-name">Siti Rahayu</div><div class="u-email">siti.rahayu@email.com</div></div></div></td>
                        <td><span class="role-pill role-editor"><i class="fa-solid fa-pen-nib fa-xs"></i> Editor</span></td>
                        <td><span class="badge-glass badge-pending"><span class="dot"></span> Pending</span></td>
                        <td><div class="prog-wrap"><div class="prog-label"><span>Tugas</span><span>42%</span></div><div class="prog-track"><div class="prog-fill p-yellow" data-w="42%" style="width:0"></div></div></div></td>
                        <td><span style="color:rgba(255,255,255,.45);font-size:.8rem">20 Feb 2024</span></td>
                        <td><div class="action-group"><a href="#" class="act-btn v"><i class="fa-solid fa-eye"></i></a><a href="#" class="act-btn e"><i class="fa-solid fa-pen"></i></a><a href="#" class="act-btn d"><i class="fa-solid fa-trash"></i></a></div></td>
                    </tr>
                    <tr>
                        <td><input type="checkbox" class="row-check" style="width:15px;height:15px;accent-color:#818cf8;cursor:pointer"></td>
                        <td><span style="color:rgba(255,255,255,.3);font-size:.78rem">#003</span></td>
                        <td><div class="user-cell"><div class="u-avatar" style="background:linear-gradient(135deg,#10b981,#059669)">BP</div><div><div class="u-name">Budi Prasetyo</div><div class="u-email">budi.p@email.com</div></div></div></td>
                        <td><span class="role-pill role-viewer"><i class="fa-solid fa-eye fa-xs"></i> Viewer</span></td>
                        <td><span class="badge-glass badge-active"><span class="dot"></span> Aktif</span></td>
                        <td><div class="prog-wrap"><div class="prog-label"><span>Tugas</span><span>68%</span></div><div class="prog-track"><div class="prog-fill p-green" data-w="68%" style="width:0"></div></div></div></td>
                        <td><span style="color:rgba(255,255,255,.45);font-size:.8rem">05 Mar 2024</span></td>
                        <td><div class="action-group"><a href="#" class="act-btn v"><i class="fa-solid fa-eye"></i></a><a href="#" class="act-btn e"><i class="fa-solid fa-pen"></i></a><a href="#" class="act-btn d"><i class="fa-solid fa-trash"></i></a></div></td>
                    </tr>
                    <tr>
                        <td><input type="checkbox" class="row-check" style="width:15px;height:15px;accent-color:#818cf8;cursor:pointer"></td>
                        <td><span style="color:rgba(255,255,255,.3);font-size:.78rem">#004</span></td>
                        <td><div class="user-cell"><div class="u-avatar" style="background:linear-gradient(135deg,#f59e0b,#ef4444)">DN</div><div><div class="u-name">Dewi Nuraini</div><div class="u-email">dewi.nuraini@email.com</div></div></div></td>
                        <td><span class="role-pill role-admin"><i class="fa-solid fa-shield-halved fa-xs"></i> Admin</span></td>
                        <td><span class="badge-glass badge-blocked"><span class="dot"></span> Diblokir</span></td>
                        <td><div class="prog-wrap"><div class="prog-label"><span>Tugas</span><span>22%</span></div><div class="prog-track"><div class="prog-fill p-red" data-w="22%" style="width:0"></div></div></div></td>
                        <td><span style="color:rgba(255,255,255,.45);font-size:.8rem">18 Apr 2024</span></td>
                        <td><div class="action-group"><a href="#" class="act-btn v"><i class="fa-solid fa-eye"></i></a><a href="#" class="act-btn e"><i class="fa-solid fa-pen"></i></a><a href="#" class="act-btn d"><i class="fa-solid fa-trash"></i></a></div></td>
                    </tr>
                    <tr>
                        <td><input type="checkbox" class="row-check" style="width:15px;height:15px;accent-color:#818cf8;cursor:pointer"></td>
                        <td><span style="color:rgba(255,255,255,.3);font-size:.78rem">#005</span></td>
                        <td><div class="user-cell"><div class="u-avatar" style="background:linear-gradient(135deg,#14b8a6,#6366f1)">RW</div><div><div class="u-name">Rizky Wahyudi</div><div class="u-email">rizky.w@email.com</div></div></div></td>
                        <td><span class="role-pill role-editor"><i class="fa-solid fa-pen-nib fa-xs"></i> Editor</span></td>
                        <td><span class="badge-glass badge-active"><span class="dot"></span> Aktif</span></td>
                        <td><div class="prog-wrap"><div class="prog-label"><span>Tugas</span><span>91%</span></div><div class="prog-track"><div class="prog-fill p-blue" data-w="91%" style="width:0"></div></div></div></td>
                        <td><span style="color:rgba(255,255,255,.45);font-size:.8rem">30 Mei 2024</span></td>
                        <td><div class="action-group"><a href="#" class="act-btn v"><i class="fa-solid fa-eye"></i></a><a href="#" class="act-btn e"><i class="fa-solid fa-pen"></i></a><a href="#" class="act-btn d"><i class="fa-solid fa-trash"></i></a></div></td>
                    </tr>
                    <tr>
                        <td><input type="checkbox" class="row-check" style="width:15px;height:15px;accent-color:#818cf8;cursor:pointer"></td>
                        <td><span style="color:rgba(255,255,255,.3);font-size:.78rem">#006</span></td>
                        <td><div class="user-cell"><div class="u-avatar" style="background:linear-gradient(135deg,#a855f7,#ec4899)">LP</div><div><div class="u-name">Laila Permata</div><div class="u-email">laila.permata@email.com</div></div></div></td>
                        <td><span class="role-pill role-viewer"><i class="fa-solid fa-eye fa-xs"></i> Viewer</span></td>
                        <td><span class="badge-glass badge-inactive"><span class="dot"></span> Nonaktif</span></td>
                        <td><div class="prog-wrap"><div class="prog-label"><span>Tugas</span><span>55%</span></div><div class="prog-track"><div class="prog-fill p-yellow" data-w="55%" style="width:0"></div></div></div></td>
                        <td><span style="color:rgba(255,255,255,.45);font-size:.8rem">11 Jun 2024</span></td>
                        <td><div class="action-group"><a href="#" class="act-btn v"><i class="fa-solid fa-eye"></i></a><a href="#" class="act-btn e"><i class="fa-solid fa-pen"></i></a><a href="#" class="act-btn d"><i class="fa-solid fa-trash"></i></a></div></td>
                    </tr>
                    <tr>
                        <td><input type="checkbox" class="row-check" style="width:15px;height:15px;accent-color:#818cf8;cursor:pointer"></td>
                        <td><span style="color:rgba(255,255,255,.3);font-size:.78rem">#007</span></td>
                        <td><div class="user-cell"><div class="u-avatar" style="background:linear-gradient(135deg,#0ea5e9,#22d3ee)">FH</div><div><div class="u-name">Fajar Hidayat</div><div class="u-email">fajar.h@email.com</div></div></div></td>
                        <td><span class="role-pill role-admin"><i class="fa-solid fa-shield-halved fa-xs"></i> Admin</span></td>
                        <td><span class="badge-glass badge-active"><span class="dot"></span> Aktif</span></td>
                        <td><div class="prog-wrap"><div class="prog-label"><span>Tugas</span><span>77%</span></div><div class="prog-track"><div class="prog-fill p-green" data-w="77%" style="width:0"></div></div></div></td>
                        <td><span style="color:rgba(255,255,255,.45);font-size:.8rem">03 Jul 2024</span></td>
                        <td><div class="action-group"><a href="#" class="act-btn v"><i class="fa-solid fa-eye"></i></a><a href="#" class="act-btn e"><i class="fa-solid fa-pen"></i></a><a href="#" class="act-btn d"><i class="fa-solid fa-trash"></i></a></div></td>
                    </tr>
                    <tr>
                        <td><input type="checkbox" class="row-check" style="width:15px;height:15px;accent-color:#818cf8;cursor:pointer"></td>
                        <td><span style="color:rgba(255,255,255,.3);font-size:.78rem">#008</span></td>
                        <td><div class="user-cell"><div class="u-avatar" style="background:linear-gradient(135deg,#f97316,#fbbf24)">MS</div><div><div class="u-name">Maya Santoso</div><div class="u-email">maya.santoso@email.com</div></div></div></td>
                        <td><span class="role-pill role-editor"><i class="fa-solid fa-pen-nib fa-xs"></i> Editor</span></td>
                        <td><span class="badge-glass badge-pending"><span class="dot"></span> Pending</span></td>
                        <td><div class="prog-wrap"><div class="prog-label"><span>Tugas</span><span>33%</span></div><div class="prog-track"><div class="prog-fill p-red" data-w="33%" style="width:0"></div></div></div></td>
                        <td><span style="color:rgba(255,255,255,.45);font-size:.8rem">22 Agu 2024</span></td>
                        <td><div class="action-group"><a href="#" class="act-btn v"><i class="fa-solid fa-eye"></i></a><a href="#" class="act-btn e"><i class="fa-solid fa-pen"></i></a><a href="#" class="act-btn d"><i class="fa-solid fa-trash"></i></a></div></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

{{-- ══════════════════════════════════════════════
     SCRIPTS
     ✅ jQuery TIDAK di-load ulang di sini!
        Layout sudah punya jQuery & Bootstrap JS.
        DataTables cukup di-push setelah itu.
══════════════════════════════════════════════ --}}
@push('scripts')
    {{-- DataTables — requires jQuery yg SUDAH ADA di layout --}}
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.print.min.js"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function () {

        /* ── 1. Stat card progress bars ── */
        document.querySelectorAll('.stat-card__progress-bar').forEach(function (bar) {
            var t = bar.style.width;
            bar.style.width = '0%';
            setTimeout(function () { bar.style.width = t; }, 400);
        });

        /* ── 2. Stat card 3D tilt ── */
        document.querySelectorAll('.stat-card').forEach(function (card) {
            card.addEventListener('mousemove', function (e) {
                var r  = card.getBoundingClientRect();
                var rx = ((e.clientY - r.top  - r.height / 2) / (r.height / 2)) * -6;
                var ry = ((e.clientX - r.left - r.width  / 2) / (r.width  / 2)) *  6;
                card.style.transform = 'translateY(-8px) scale(1.02) rotateX('+rx+'deg) rotateY('+ry+'deg)';
            });
            card.addEventListener('mouseleave', function () { card.style.transform = ''; });
        });

        /* ── 3. DataTables init (gunakan $ dari jQuery yg sudah ada di layout) ── */
        $('#usersTable').DataTable({
            pageLength  : 5,
            lengthMenu  : [5, 10, 25, 50],
            language    : {
                search            : '',
                searchPlaceholder : 'Cari pengguna…',
                lengthMenu        : 'Tampilkan _MENU_ data',
                info              : 'Menampilkan _START_–_END_ dari _TOTAL_ pengguna',
                infoEmpty         : 'Tidak ada data',
                infoFiltered      : '(difilter dari _MAX_ total)',
                paginate          : {
                    previous : '<i class="fa-solid fa-chevron-left fa-xs"></i>',
                    next     : '<i class="fa-solid fa-chevron-right fa-xs"></i>'
                },
                emptyTable : 'Tidak ada data tersedia'
            },
            dom : "<'dt-layout-row'<'dt-layout-cell dt-start'l><'dt-layout-cell dt-end'f>>" +
                  "<'dt-layout-row dt-layout-table'<'dt-layout-cell'tr>>"                   +
                  "<'dt-layout-row'<'dt-layout-cell dt-start'i><'dt-layout-cell dt-end'p>>",
            columnDefs  : [
                { orderable  : false, targets : [0, 5, 7] },
                { searchable : false, targets : [0, 1, 5, 7] }
            ],
            order        : [[1, 'asc']],
            drawCallback : function () {
                document.querySelectorAll('.prog-fill').forEach(function (el) {
                    var w = el.dataset.w; el.style.width = '0';
                    setTimeout(function () { el.style.width = w; }, 150);
                });
                document.querySelectorAll('.row-check').forEach(function (c) {
                    c.checked = false; c.closest('tr').style.background = '';
                });
                var ca = document.getElementById('checkAll');
                if (ca) ca.checked = false;
            }
        });

        /* ── 4. Check-all ── */
        var checkAllEl = document.getElementById('checkAll');
        if (checkAllEl) {
            checkAllEl.addEventListener('change', function () {
                document.querySelectorAll('.row-check').forEach(function (c) {
                    c.checked = checkAllEl.checked;
                    c.closest('tr').style.background = checkAllEl.checked ? 'rgba(99,102,241,.15)' : '';
                });
            });
        }
        document.addEventListener('change', function (e) {
            if (!e.target.classList.contains('row-check')) return;
            var all = Array.from(document.querySelectorAll('.row-check'));
            var ca  = document.getElementById('checkAll');
            if (ca) ca.checked = all.every(function (c) { return c.checked; });
            e.target.closest('tr').style.background = e.target.checked ? 'rgba(99,102,241,.15)' : '';
        });

        /* ── 5. Initial progress fill ── */
        document.querySelectorAll('.prog-fill').forEach(function (el) {
            var w = el.dataset.w; el.style.width = '0';
            setTimeout(function () { el.style.width = w; }, 500);
        });
    });
    </script>
@endpush