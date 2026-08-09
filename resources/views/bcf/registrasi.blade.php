@extends('layouts.app')

@section('title', 'BCF Registrasi')

@push('styles')
    <style>
        #createBcfModal .modal-content,
        #editBcfModal .modal-content {
            background: rgba(15, 12, 41, 0.96);
            border: 1px solid rgba(255, 255, 255, .14);
            border-radius: 18px;
            color: rgba(255, 255, 255, .9);
            box-shadow: 0 20px 48px rgba(0, 0, 0, .55);
            backdrop-filter: blur(20px) saturate(160%);
            -webkit-backdrop-filter: blur(20px) saturate(160%);
        }

        #createBcfModal .modal-header,
        #createBcfModal .modal-footer,
        #editBcfModal .modal-header,
        #editBcfModal .modal-footer {
            border-color: rgba(255, 255, 255, .12);
        }

        #createBcfModal .modal-title,
        #createBcfModal .form-label,
        #editBcfModal .modal-title,
        #editBcfModal .form-label {
            color: rgba(255, 255, 255, .9) !important;
        }

        #createBcfModal .btn-close,
        #editBcfModal .btn-close {
            filter: invert(1) grayscale(100%) brightness(200%);
        }

        #createBcfModal .form-control,
        #createBcfModal .form-select,
        #editBcfModal .form-control,
        #editBcfModal .form-select {
            background: rgba(255, 255, 255, .08);
            color: #fff;
            border: 1px solid rgba(255, 255, 255, .16);
        }

        #createBcfModal .form-control:focus,
        #createBcfModal .form-select:focus,
        #editBcfModal .form-control:focus,
        #editBcfModal .form-select:focus {
            background: rgba(255, 255, 255, .12);
            color: #fff;
            border-color: rgba(129, 140, 248, .85);
            box-shadow: 0 0 0 .2rem rgba(99, 102, 241, .22);
        }

        #createBcfModal .form-select option,
        #editBcfModal .form-select option {
            background: #1a1a4e;
            color: #fff;
        }

        .color-preview-badge {
            display: inline-block;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            border: 1px solid rgba(255, 255, 255, 0.4);
            vertical-align: middle;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .action-group {
            display: flex;
            gap: 6px;
        }

        .act-btn {
            border: none;
            padding: 5px 10px;
            border-radius: 8px;
            font-size: 0.8rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .act-btn.e {
            background: rgba(99, 102, 241, 0.2);
            color: #818cf8;
            border: 1px solid rgba(99, 102, 241, 0.4);
        }

        .act-btn.e:hover {
            background: rgba(99, 102, 241, 0.4);
            color: #fff;
        }

        .act-btn.d {
            background: rgba(239, 68, 68, 0.2);
            color: #f87171;
            border: 1px solid rgba(239, 68, 68, 0.4);
        }

        .act-btn.d:hover {
            background: rgba(239, 68, 68, 0.4);
            color: #fff;
        }
    </style>
@endpush

@php
    $colorHexMap = [
        'Ungu' => '#8b5cf6',
        'Hitam' => '#1e293b',
        'Biru Tua' => '#1e3a8a',
        'Biru Muda' => '#38bdf8',
        'Putih' => '#ffffff',
        'Kuning' => '#eab308',
        'Merah' => '#ef4444',
        'Hijau' => '#10b981',
        'Orange' => '#f97316',
    ];
@endphp

@section('content')
    <div class="container-fluid px-3 px-md-4 py-4">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <h5 class="mb-1 fw-bold text-white"><i class="bi bi-file-earmark-text me-2"></i>BCF Registrasi</h5>
                <small class="text-muted">Kelola data pendaftaran BCF, nomor urut, team, dan unit kerja.</small>
            </div>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createBcfModal">
                <i class="fa-solid fa-plus me-1"></i> Tambah Registrasi
            </button>
        </div>

        <div class="stats-wrapper mb-4">
            <div class="table-responsive">
                <table class="myTable table table-hover mb-0 display">
                    <thead class="table-light">
                        <tr>
                            <th width="70">No Urut</th>
                            <th width="120">Warna</th>
                            <th>Nama</th>
                            <th>PN</th>
                            <th>Unit Kerja</th>
                            <th>Team</th>
                            <th width="110">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($registrasi as $row)
                            <tr>
                                <td>
                                    <span class="badge bg-secondary px-2 py-1 fs-6">
                                        {{ $row->nourut }}
                                    </span>
                                </td>
                                <td>
                                    @php
                                        $hexColor = $colorHexMap[$row->warna] ?? '#38bdf8';
                                    @endphp
                                    <span class="color-preview-badge" style="background-color: {{ $hexColor }};" title="{{ $row->warna }}"></span>
                                    <span class="fw-medium ms-1 text-white">{{ $row->warna }}</span>
                                </td>
                                <td class="fw-semibold">{{ $row->nama }}</td>
                                <td><code>{{ $row->pn }}</code></td>
                                <td>
                                    <span class="badge bg-primary bg-opacity-25 text-primary border border-primary border-opacity-25">
                                        {{ $row->unit_kerja }}
                                    </span>
                                </td>
                                <td>{{ $row->team ?? '-' }}</td>
                                <td>
                                    <div class="action-group">
                                        <button type="button" class="act-btn e btn-edit-bcf"
                                            data-id="{{ $row->id }}"
                                            data-nama="{{ $row->nama }}"
                                            data-pn="{{ $row->pn }}"
                                            data-unit="{{ $row->unit_kerja }}"
                                            data-warna="{{ $row->warna }}"
                                            data-nourut="{{ $row->nourut }}"
                                            data-team="{{ $row->team }}">
                                            <i class="fa-solid fa-pen"></i>
                                        </button>

                                        <form action="{{ route('bcf.registrasi.destroy', $row->id) }}" method="POST"
                                            class="form-delete-bcf d-inline" data-nama="{{ $row->nama }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="act-btn d">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Modal Create --}}
    <div class="modal fade" id="createBcfModal" tabindex="-1" aria-labelledby="createBcfModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('bcf.registrasi.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold" id="createBcfModalLabel"><i class="bi bi-plus-circle me-2"></i>Tambah Registrasi BCF</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        {{-- Quick Search Pekerja --}}
                        <div class="mb-3 p-2 rounded" style="background: rgba(99, 102, 241, 0.1); border: 1px dashed rgba(99, 102, 241, 0.3);">
                            <label for="select_pekerja_create" class="form-label text-info small fw-bold">
                                <i class="bi bi-search me-1"></i> Pilih dari Data Pekerja (Otomatis Isi)
                            </label>
                            <select id="select_pekerja_create" class="form-select form-select-sm">
                                <option value="">-- Pilih Pekerja --</option>
                                @foreach ($pegawais as $p)
                                    @php
                                        $pUkerFormatted = optional($p->uker)->kode_uker
                                            ? '( ' . $p->uker->kode_uker . ' ) - ' . $p->uker->nama
                                            : (optional($p->uker)->nama ?? '');
                                    @endphp
                                    <option value="{{ $p->id }}"
                                        data-nama="{{ $p->nama }}"
                                        data-pn="{{ $p->pn }}"
                                        data-unit="{{ $pUkerFormatted }}">
                                        {{ $p->nama }} (PN: {{ $p->pn }}) {{ $pUkerFormatted ? ' — ' . $pUkerFormatted : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="create_nama" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" name="nama" id="create_nama" class="form-control" placeholder="Masukkan nama" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="create_pn" class="form-label">PN (Personal Number) <span class="text-danger">*</span></label>
                                <input type="text" name="pn" id="create_pn" class="form-control" placeholder="Masukkan PN" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="create_unit_kerja" class="form-label">Unit Kerja <span class="text-danger">*</span></label>
                            <select name="unit_kerja" id="create_unit_kerja" class="form-select" required>
                                <option value="">-- Pilih Unit Kerja --</option>
                                @foreach ($ukers as $uk)
                                    @php
                                        $ukerFormatted = $uk->kode_uker ? '( ' . $uk->kode_uker . ' ) - ' . $uk->nama : $uk->nama;
                                    @endphp
                                    <option value="{{ $ukerFormatted }}">{{ $ukerFormatted }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="create_nourut" class="form-label">No Urut</label>
                                <input type="number" name="nourut" id="create_nourut" class="form-control" placeholder="1" min="1">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="create_team" class="form-label">Team</label>
                                <input type="text" name="team" id="create_team" class="form-control" placeholder="Contoh: Team A">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="create_warna" class="form-label">Warna <span class="text-danger">*</span></label>
                                <select name="warna" id="create_warna" class="form-select" required>
                                    @foreach ($warnaOptions as $w)
                                        <option value="{{ $w }}" {{ $w === 'Biru Muda' ? 'selected' : '' }}>{{ $w }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save me-1"></i> Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Edit --}}
    <div class="modal fade" id="editBcfModal" tabindex="-1" aria-labelledby="editBcfModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="editBcfForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold" id="editBcfModalLabel"><i class="bi bi-pencil-square me-2"></i>Edit Registrasi BCF</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_nama" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" name="nama" id="edit_nama" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_pn" class="form-label">PN (Personal Number) <span class="text-danger">*</span></label>
                                <input type="text" name="pn" id="edit_pn" class="form-control" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="edit_unit_kerja" class="form-label">Unit Kerja <span class="text-danger">*</span></label>
                            <select name="unit_kerja" id="edit_unit_kerja" class="form-select" required>
                                <option value="">-- Pilih Unit Kerja --</option>
                                @foreach ($ukers as $uk)
                                    @php
                                        $ukerFormatted = $uk->kode_uker ? '( ' . $uk->kode_uker . ' ) - ' . $uk->nama : $uk->nama;
                                    @endphp
                                    <option value="{{ $ukerFormatted }}">{{ $ukerFormatted }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="edit_nourut" class="form-label">No Urut</label>
                                <input type="number" name="nourut" id="edit_nourut" class="form-control" min="1">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="edit_team" class="form-label">Team</label>
                                <input type="text" name="team" id="edit_team" class="form-control">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="edit_warna" class="form-label">Warna <span class="text-danger">*</span></label>
                                <select name="warna" id="edit_warna" class="form-select" required>
                                    @foreach ($warnaOptions as $w)
                                        <option value="{{ $w }}">{{ $w }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save me-1"></i> Update Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-fill from select pekerja (Data Pekerja)
            const selectPekerja = document.getElementById('select_pekerja_create');
            if (selectPekerja) {
                selectPekerja.addEventListener('change', function() {
                    const selectedOpt = this.options[this.selectedIndex];
                    if (selectedOpt && selectedOpt.value !== '') {
                        document.getElementById('create_nama').value = selectedOpt.dataset.nama || '';
                        document.getElementById('create_pn').value = selectedOpt.dataset.pn || '';
                        const unitKerja = selectedOpt.dataset.unit || '';
                        if (unitKerja) {
                            const unitSelect = document.getElementById('create_unit_kerja');
                            for (let i = 0; i < unitSelect.options.length; i++) {
                                if (unitSelect.options[i].value === unitKerja) {
                                    unitSelect.selectedIndex = i;
                                    break;
                                }
                            }
                        }
                    }
                });
            }

            // Handle Edit Modal populate
            const editButtons = document.querySelectorAll('.btn-edit-bcf');
            const editForm = document.getElementById('editBcfForm');
            const editNama = document.getElementById('edit_nama');
            const editPn = document.getElementById('edit_pn');
            const editUnit = document.getElementById('edit_unit_kerja');
            const editWarna = document.getElementById('edit_warna');
            const editNourut = document.getElementById('edit_nourut');
            const editTeam = document.getElementById('edit_team');

            editButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.dataset.id;
                    editForm.action = `/bcf-registrasi/${id}`;
                    editNama.value = this.dataset.nama || '';
                    editPn.value = this.dataset.pn || '';
                    editUnit.value = this.dataset.unit || '';
                    editWarna.value = this.dataset.warna || 'Biru Muda';
                    editNourut.value = this.dataset.nourut || '';
                    editTeam.value = this.dataset.team || '';

                    const editModal = new bootstrap.Modal(document.getElementById('editBcfModal'));
                    editModal.show();
                });
            });

            // Handle Delete confirmation
            const deleteForms = document.querySelectorAll('.form-delete-bcf');
            deleteForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const nama = this.dataset.nama;
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            title: 'Hapus Data?',
                            text: `Registrasi atas nama ${nama} akan dihapus permanen!`,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#ef4444',
                            cancelButtonColor: '#6b7280',
                            confirmButtonText: 'Ya, Hapus!',
                            cancelButtonText: 'Batal'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                this.submit();
                            }
                        });
                    } else {
                        if (confirm(`Hapus registrasi ${nama}?`)) {
                            this.submit();
                        }
                    }
                });
            });
        });
    </script>
@endpush
