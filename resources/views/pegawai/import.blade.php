@extends('layouts.app')

@section('title', 'Import Pegawai')

@section('content')
    <style>
        /* ════════════════════════════════
                       PAGE TITLE
                    ════════════════════════════════ */
        .section-title {
            color: rgba(202, 204, 220, .9);
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

        /* ════════════════════════════════
                       GLASS CARD
                    ════════════════════════════════ */
        .glass-card {
            background: rgba(255, 255, 255, .06);
            backdrop-filter: blur(24px) saturate(180%);
            -webkit-backdrop-filter: blur(24px) saturate(180%);
            border: 1px solid rgba(255, 255, 255, .13);
            border-radius: 24px;
            overflow: hidden;
            position: relative;
            z-index: 1;
            box-shadow: 0 8px 40px rgba(0, 0, 0, .28), inset 0 1px 0 rgba(255, 255, 255, .18);
            animation: fadeUp .5s cubic-bezier(.22, .68, 0, 1.2) both;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(24px)
            }

            to {
                opacity: 1;
                transform: translateY(0)
            }
        }

        .glass-card::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: inherit;
            pointer-events: none;
            z-index: 0;
            background: linear-gradient(140deg, rgba(255, 255, 255, .1) 0%, rgba(255, 255, 255, .03) 35%, transparent 55%);
        }

        /* Toolbar */
        .glass-toolbar {
            position: relative;
            z-index: 2;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: .75rem;
            padding: 1.1rem 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, .08);
            background: rgba(255, 255, 255, .03);
        }

        .glass-toolbar-title {
            font-size: 1rem;
            font-weight: 700;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 9px;
        }

        .t-icon {
            width: 36px;
            height: 36px;
            border-radius: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .88rem;
            color: #fff;
            background: linear-gradient(135deg, rgba(16, 185, 129, .8), rgba(5, 150, 105, .7));
            box-shadow: 0 3px 12px rgba(16, 185, 129, .38);
        }

        /* Glass buttons */
        .glass-btn {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: .46rem 1.1rem;
            border-radius: 50px;
            font-size: .8rem;
            font-weight: 600;
            cursor: pointer;
            border: 1px solid rgba(255, 255, 255, .18);
            background: rgba(255, 255, 255, .09);
            color: rgba(255, 255, 255, .85);
            transition: all .22s;
            text-decoration: none;
            white-space: nowrap;
            font-family: inherit;
        }

        .glass-btn:hover {
            background: rgba(255, 255, 255, .18);
            color: #fff;
            transform: translateY(-1px);
        }

        .glass-btn.primary {
            background: linear-gradient(135deg, rgba(99, 102, 241, .8), rgba(168, 85, 247, .8));
            border-color: rgba(168, 85, 247, .4);
            color: #fff;
            box-shadow: 0 4px 14px rgba(99, 102, 241, .35);
        }

        .glass-btn.primary:hover {
            box-shadow: 0 6px 20px rgba(99, 102, 241, .55);
        }

        .glass-btn.green {
            background: linear-gradient(135deg, rgba(16, 185, 129, .8), rgba(5, 150, 105, .7));
            border-color: rgba(16, 185, 129, .4);
            color: #fff;
            box-shadow: 0 4px 14px rgba(16, 185, 129, .35);
        }

        .glass-btn.green:hover {
            box-shadow: 0 6px 20px rgba(16, 185, 129, .55);
        }

        /* ════════════════════════════════
                       CARD BODY
                    ════════════════════════════════ */
        .card-body-glass {
            position: relative;
            z-index: 2;
            padding: 2rem;
        }

        /* ════════════════════════════════
                       DROPZONE
                    ════════════════════════════════ */
        .dropzone-wrap {
            border: 2px dashed rgba(99, 102, 241, .45);
            border-radius: 20px;
            padding: 3rem 2rem;
            text-align: center;
            background: rgba(99, 102, 241, .05);
            transition: all .25s;
            cursor: pointer;
            position: relative;
        }

        .dropzone-wrap:hover,
        .dropzone-wrap.dragover {
            border-color: rgba(99, 102, 241, .8);
            background: rgba(99, 102, 241, .12);
            box-shadow: 0 0 0 4px rgba(99, 102, 241, .15);
        }

        .dropzone-icon {
            font-size: 3.5rem;
            color: rgba(99, 102, 241, .6);
            margin-bottom: 1rem;
            transition: transform .3s, color .25s;
        }

        .dropzone-wrap:hover .dropzone-icon,
        .dropzone-wrap.dragover .dropzone-icon {
            transform: translateY(-6px) scale(1.05);
            color: rgba(99, 102, 241, .9);
        }

        .dropzone-title {
            font-size: 1rem;
            font-weight: 700;
            color: #fff;
            margin-bottom: .4rem;
        }

        .dropzone-sub {
            font-size: .82rem;
            color: rgba(255, 255, 255, .4);
        }

        .dropzone-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 3px 12px;
            border-radius: 20px;
            margin-top: .8rem;
            font-size: .72rem;
            font-weight: 600;
            background: rgba(99, 102, 241, .2);
            border: 1px solid rgba(99, 102, 241, .35);
            color: #a5b4fc;
        }

        /* Hidden file input */
        #fileInput {
            display: none;
        }

        /* File preview */
        .file-preview {
            display: none;
            align-items: center;
            gap: 12px;
            background: rgba(16, 185, 129, .12);
            border: 1px solid rgba(16, 185, 129, .3);
            border-radius: 14px;
            padding: .85rem 1.2rem;
            margin-top: 1.2rem;
        }

        .file-preview.show {
            display: flex;
        }

        .file-preview-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            flex-shrink: 0;
            background: linear-gradient(135deg, rgba(16, 185, 129, .7), rgba(5, 150, 105, .6));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            color: #fff;
        }

        .file-preview-name {
            font-weight: 600;
            color: #fff;
            font-size: .88rem;
        }

        .file-preview-size {
            font-size: .75rem;
            color: rgba(255, 255, 255, .45);
            margin-top: 2px;
        }

        .file-remove {
            margin-left: auto;
            background: rgba(239, 68, 68, .2);
            border: 1px solid rgba(239, 68, 68, .35);
            border-radius: 8px;
            width: 28px;
            height: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fca5a5;
            cursor: pointer;
            font-size: .75rem;
            transition: all .2s;
            flex-shrink: 0;
        }

        .file-remove:hover {
            background: rgba(239, 68, 68, .4);
            color: #fff;
        }

        /* ════════════════════════════════
                       ALERT
                    ════════════════════════════════ */
        .glass-alert {
            border-radius: 14px;
            padding: .85rem 1.1rem;
            display: flex;
            align-items: flex-start;
            gap: 10px;
            font-size: .82rem;
            margin-bottom: 1.2rem;
            animation: fadeUp .4s both;
        }

        .glass-alert.error {
            background: rgba(239, 68, 68, .15);
            border: 1px solid rgba(239, 68, 68, .3);
            color: #fca5a5;
        }

        .glass-alert.success {
            background: rgba(16, 185, 129, .15);
            border: 1px solid rgba(16, 185, 129, .3);
            color: #6ee7b7;
        }

        /* ════════════════════════════════
                       PANDUAN TABLE
                    ════════════════════════════════ */
        .guide-table {
            width: 100%;
            border-collapse: collapse;
            font-size: .82rem;
            margin-top: 1rem;
        }

        .guide-table th {
            background: rgba(255, 255, 255, .06);
            color: rgba(255, 255, 255, .5);
            font-size: .7rem;
            font-weight: 600;
            letter-spacing: .07em;
            text-transform: uppercase;
            padding: .7rem 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, .1);
            text-align: left;
        }

        .guide-table td {
            padding: .7rem 1rem;
            color: rgba(255, 255, 255, .7);
            border-bottom: 1px solid rgba(255, 255, 255, .05);
        }

        .guide-table tr:last-child td {
            border-bottom: none;
        }

        .guide-table tr:hover td {
            background: rgba(99, 102, 241, .07);
        }

        .req-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 2px 9px;
            border-radius: 20px;
            font-size: .68rem;
            font-weight: 600;
        }

        .req-yes {
            background: rgba(239, 68, 68, .18);
            border: 1px solid rgba(239, 68, 68, .3);
            color: #fca5a5;
        }

        .req-no {
            background: rgba(107, 114, 128, .18);
            border: 1px solid rgba(107, 114, 128, .28);
            color: #9ca3af;
        }

        /* Progress bar */
        .upload-progress {
            display: none;
            margin-top: 1.2rem;
        }

        .upload-progress.show {
            display: block;
        }

        .prog-track {
            height: 6px;
            background: rgba(255, 255, 255, .1);
            border-radius: 10px;
            overflow: hidden;
            margin-top: .5rem;
        }

        .prog-fill-anim {
            height: 100%;
            border-radius: 10px;
            width: 0;
            background: linear-gradient(90deg, #6366f1, #818cf8);
            animation: progAnim 1.5s ease-in-out infinite alternate;
        }

        @keyframes progAnim {
            from {
                width: 20%;
            }

            to {
                width: 90%;
            }
        }
    </style>

    <div class="container-fluid px-3 px-md-4 py-4">

        {{-- Title --}}
        <p class="section-title">
            <i class="fa-solid fa-file-import fa-sm"></i>
            Import Data Pegawai
        </p>

        <div class="row g-4">

            {{-- ══ FORM IMPORT ══ --}}
            <div class="col-12 col-lg-7">
                <div class="glass-card">

                    <div class="glass-toolbar">
                        <div class="glass-toolbar-title">
                            <div class="t-icon"><i class="fa-solid fa-upload fa-xl"></i></div>
                            Upload File Excel
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('pegawai.import.template') }}" class="glass-btn">
                                <i class="fa-solid fa-download fa-xl"></i> Download Template
                            </a>
                            {{-- <a href="#" class="glass-btn">
                            <i class="fa-solid fa-arrow-left fa-xs"></i> Kembali
                        </a> --}}
                        </div>
                    </div>

                    <div class="card-body-glass">

                        {{-- Error --}}
                        @if ($errors->any())
                            <div class="glass-alert error">
                                <i class="fa-solid fa-circle-exclamation mt-1"></i>
                                <div>
                                    <strong>Gagal import:</strong><br>
                                    {!! implode('<br>', $errors->all()) !!}
                                </div>
                            </div>
                        @endif

                        {{-- Success --}}
                        @if (session('success'))
                            <div class="glass-alert success">
                                <i class="fa-solid fa-circle-check mt-1"></i>
                                <span>{!! session('success') !!}</span>
                            </div>
                        @endif

                        {{-- FORM --}}
                        <form method="POST" action="{{ route('pegawai.import.store') }}" enctype="multipart/form-data"
                            id="importForm">
                            @csrf

                            {{-- Dropzone --}}
                            <div class="dropzone-wrap" id="dropzone"
                                onclick="document.getElementById('fileInput').click()">
                                <i class="fa-solid fa-cloud-arrow-up dropzone-icon"></i>
                                <div class="dropzone-title"> Drag & drop file ke sini</div>
                                <div class="dropzone-sub">ATAU KLIK DISINI UNTUK MEMILIH FILE</div>
                                <span class="dropzone-badge">
                                    <i class="fa-solid fa-file-excel fa-xs"></i>
                                    .xlsx &nbsp;·&nbsp; .xls &nbsp;·&nbsp; .csv
                                </span>
                                <div class="dropzone-sub mt-2" style="font-size:.72rem">
                                    Ukuran maksimal 10 MB. !!!
                                </div>
                            </div>

                            {{-- Hidden input --}}
                            <input type="file" name="file" id="fileInput" accept=".xlsx,.xls,.csv">

                            {{-- File preview --}}
                            <div class="file-preview" id="filePreview">
                                <div class="file-preview-icon">
                                    <i class="fa-solid fa-file-excel"></i>
                                </div>
                                <div>
                                    <div class="file-preview-name" id="fileName">—</div>
                                    <div class="file-preview-size" id="fileSize">—</div>
                                </div>
                                <button type="button" class="file-remove" id="fileRemove" title="Hapus file">
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                            </div>

                            {{-- Upload progress (saat submit) --}}
                            <div class="upload-progress" id="uploadProgress">
                                <div style="font-size:.78rem;color:rgba(255,255,255,.5)">
                                    <i class="fa-solid fa-spinner fa-spin me-1"></i>
                                    Sedang memproses file…
                                </div>
                                <div class="prog-track">
                                    <div class="prog-fill-anim"></div>
                                </div>
                            </div>

                            {{-- Submit --}}
                            <div class="d-flex gap-2 mt-3">
                                <button type="submit" class="glass-btn green" id="submitBtn" disabled>
                                    <i class="fa-solid fa-file-import fa-xl"></i>
                                    Import Sekarang
                                </button>
                                <button type="reset" class="glass-btn" id="resetBtn">
                                    <i class="fa-solid fa-rotate fa-xl"></i>
                                    Reset
                                </button>
                            </div>

                        </form>

                        {{-- Contoh --}}
                        <div style="margin-top:1.4rem">
                            <p
                                style="font-size:.78rem;color:rgba(255,255,255,.4);letter-spacing:.05em;text-transform:uppercase;margin-bottom:.6rem">
                                Contoh isi file
                            </p>
                            <div
                                style="background:rgba(0,0,0,.25);border:1px solid rgba(255,255,255,.1);border-radius:12px;padding:1rem;font-family:monospace;font-size:.75rem;color:#a5b4fc;overflow-x:auto">
                                <div style="color:rgba(255,255,255,.35)">uker_id | nama | pn | jabatan</div>
                                <div>1 | Ariani Fransiska Agustina Marpaung | 371467 | CUSTOMER SERVICE</div>
                                <div>1 | Alya Kusuma Asri | 90178222 | SEKRETARIS</div>
                                <div>2 | Viranka Aprillia Arony | 382943 | TELLER</div>
                            </div>
                        </div>

                        {{-- Catatan --}}
                        <div
                            style="margin-top:1.2rem;background:rgba(245,158,11,.1);border:1px solid rgba(245,158,11,.25);border-radius:12px;padding:.85rem 1rem">
                            <p style="font-size:.78rem;color:#fcd34d;margin:0;font-weight:600">
                                <i class="fa-solid fa-triangle-exclamation fa-xs me-1"></i> Catatan
                            </p>
                            <ul style="font-size:.77rem;color:rgba(255,255,255,.55);margin:.5rem 0 0;padding-left:1.2rem">
                                <li>Data dengan PN yang sudah ada akan <strong style="color:rgba(255,255,255,.7)">dilewati
                                        (skip)</strong></li>
                                <li>Baris kosong akan diabaikan otomatis</li>
                                <li>Header file harus sesuai persis (huruf kecil)</li>
                            </ul>
                        </div>

                    </div>
                </div>
            </div>

            {{-- ══ PANDUAN ══ --}}
            <div class="col-12 col-lg-5">
                <div class="glass-card h-100">

                    <div class="glass-toolbar">
                        <div class="glass-toolbar-title">
                            <div class="t-icon"
                                style="background:linear-gradient(135deg,rgba(99,102,241,.8),rgba(168,85,247,.8));box-shadow:0 3px 12px rgba(99,102,241,.38)">
                                <i class="fa-solid fa-book fa-xl"></i>
                            </div>
                            Panduan Format File
                        </div>
                    </div>

                    <div class="card-body-glass">

                        <p style="font-size:.82rem;color:rgba(255,255,255,.55);margin-bottom:1rem">
                            Pastikan file Excel/CSV kamu memiliki kolom header berikut di baris pertama:
                        </p>

                        {{-- Ganti tabel panduan kolom yang lama dengan ini --}}
                        <table class="guide-table">
                            <thead>
                                <tr>
                                    <th>Kolom Header</th>
                                    <th>Keterangan</th>
                                    <th>Wajib</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><code style="color:#a5b4fc">uker_id</code></td>
                                    <td>
                                        ID Unit Kerja (lihat referensi di bawah)
                                    </td>
                                    <td><span class="req-badge req-no">Opsional</span></td>
                                </tr>
                                <tr>
                                    <td><code style="color:#a5b4fc">nama</code></td>
                                    <td>Nama lengkap pegawai</td>
                                    <td><span class="req-badge req-yes">Wajib</span></td>
                                </tr>
                                <tr>
                                    <td><code style="color:#a5b4fc">pn</code></td>
                                    <td>Nomor PN pegawai (unik)</td>
                                    <td><span class="req-badge req-yes">Wajib</span></td>
                                </tr>
                                <tr>
                                    <td><code style="color:#a5b4fc">jabatan</code></td>
                                    <td>Nama jabatan pegawai</td>
                                    <td><span class="req-badge req-no">Opsional</span></td>
                                </tr>
                            </tbody>
                        </table>

                        {{-- ── Referensi uker_id ── --}}
                        <div style="margin-top:1.8rem">

                            <div style="display:flex;align-items:center;gap:8px;margin-bottom:.8rem">
                                <div
                                    style="width:3px;height:18px;background:linear-gradient(180deg,#6366f1,#a855f7);border-radius:2px">
                                </div>
                                <p
                                    style="font-size:.78rem;color:rgba(255,255,255,.4);letter-spacing:.05em;
                  text-transform:uppercase;margin:0">
                                    <i class="fa-solid fa-building fa-xs me-1"></i>
                                    Referensi Angka Untuk Kolom uker_id 
                                    <i class="fa-solid fa-down-long fa-xs me-1"></i>

                                </p>
                            </div>

                            <div
                                style="background:rgba(0,0,0,.2);border:1px solid rgba(255,255,255,.08);
                border-radius:14px;overflow:hidden;max-height:260px;overflow-y:auto">

                                {{-- custom scrollbar --}}
                                <style>
                                    .uker-ref-table-wrap::-webkit-scrollbar {
                                        width: 4px;
                                    }

                                    .uker-ref-table-wrap::-webkit-scrollbar-track {
                                        background: transparent;
                                    }

                                    .uker-ref-table-wrap::-webkit-scrollbar-thumb {
                                        background: rgba(99, 102, 241, .4);
                                        border-radius: 4px;
                                    }
                                </style>

                                <div class="uker-ref-table-wrap" style="max-height:260px;overflow-y:auto">
                                    <table style="width:100%;border-collapse:collapse;font-size:.78rem">
                                        <thead>
                                            <tr style="background:rgba(99,102,241,.12);position:sticky;top:0;z-index:1">
                                                <th
                                                    style="padding:.55rem .9rem;color:rgba(255,255,255,.45);
                                   font-weight:600;letter-spacing:.06em;text-align:left;
                                   border-bottom:1px solid rgba(255,255,255,.08);width:70px">
                                                    ID
                                                </th>
                                                <th
                                                    style="padding:.55rem .9rem;color:rgba(255,255,255,.45);
                                   font-weight:600;letter-spacing:.06em;text-align:left;
                                   border-bottom:1px solid rgba(255,255,255,.08)">
                                                    Nama Unit Kerja
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach (\App\Models\Uker::orderBy('id')->get() as $uker)
                                                <tr style="border-bottom:1px solid rgba(255,255,255,.04);
                               transition:background .15s"
                                                    onmouseover="this.style.background='rgba(99,102,241,.08)'"
                                                    onmouseout="this.style.background='transparent'">
                                                    <td style="padding:.48rem .9rem">
                                                        <span
                                                            style="background:rgba(99,102,241,.2);
                                         border:1px solid rgba(99,102,241,.35);
                                         color:#a5b4fc;border-radius:6px;
                                         padding:2px 10px;font-weight:700;
                                         font-size:.75rem;font-family:monospace">
                                                            {{ $uker->id }}
                                                        </span>
                                                    </td>
                                                    <td style="padding:.48rem .9rem;color:rgba(255,255,255,.65)">
                                                        {{ $uker->nama }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                            </div>

                            <p style="font-size:.90rem;color:rgba(255,255,255,.28);margin-top:.5rem">
                                <i class="fa-solid fa-circle-info fa-xs me-1"></i>
                                Isi kolom <code style="color:#a5b4fc;font-size:.8rem">uker_id</code>
                                di file Excel dengan angka ID sesuai tabel di atas
                            </p>

                        </div>


                    </div>
                </div>
            </div>

        </div>{{-- /row --}}
    </div>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            var dropzone = document.getElementById('dropzone');
            var fileInput = document.getElementById('fileInput');
            var filePreview = document.getElementById('filePreview');
            var fileName = document.getElementById('fileName');
            var fileSize = document.getElementById('fileSize');
            var fileRemove = document.getElementById('fileRemove');
            var submitBtn = document.getElementById('submitBtn');
            var resetBtn = document.getElementById('resetBtn');
            var importForm = document.getElementById('importForm');
            var uploadProg = document.getElementById('uploadProgress');

            /* ── File size formatter ── */
            function formatSize(bytes) {
                if (bytes < 1024) return bytes + ' B';
                if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
                return (bytes / 1048576).toFixed(1) + ' MB';
            }

            /* ── Show file preview ── */
            function showFile(file) {
                fileName.textContent = file.name;
                fileSize.textContent = formatSize(file.size);
                filePreview.classList.add('show');
                submitBtn.disabled = false;
                dropzone.style.borderColor = 'rgba(16,185,129,.6)';
                dropzone.style.background = 'rgba(16,185,129,.06)';
            }

            /* ── Clear file ── */
            function clearFile() {
                fileInput.value = '';
                filePreview.classList.remove('show');
                submitBtn.disabled = true;
                dropzone.style.borderColor = '';
                dropzone.style.background = '';
            }

            /* ── File input change ── */
            fileInput.addEventListener('change', function() {
                if (this.files.length > 0) showFile(this.files[0]);
            });

            /* ── Remove file ── */
            fileRemove.addEventListener('click', function(e) {
                e.stopPropagation();
                clearFile();
            });

            /* ── Drag & Drop ── */
            dropzone.addEventListener('dragover', function(e) {
                e.preventDefault();
                this.classList.add('dragover');
            });
            dropzone.addEventListener('dragleave', function() {
                this.classList.remove('dragover');
            });
            dropzone.addEventListener('drop', function(e) {
                e.preventDefault();
                this.classList.remove('dragover');
                var files = e.dataTransfer.files;
                if (files.length > 0) {
                    fileInput.files = files;
                    showFile(files[0]);
                }
            });

            /* ── Reset ── */
            resetBtn.addEventListener('click', function() {
                clearFile();
            });

            /* ── Submit — show progress ── */
            importForm.addEventListener('submit', function() {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin fa-xs"></i> Memproses…';
                uploadProg.classList.add('show');
            });

        });
    </script>
@endpush
