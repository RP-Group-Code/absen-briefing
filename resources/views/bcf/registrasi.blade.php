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

        .badge-status-pending {
            background-color: rgba(245, 158, 11, 0.2);
            color: #fbbf24;
            border: 1px solid rgba(245, 158, 11, 0.4);
        }

        .badge-status-disetujui {
            background-color: rgba(16, 185, 129, 0.2);
            color: #34d399;
            border: 1px solid rgba(16, 185, 129, 0.4);
        }

        .badge-status-ditolak {
            background-color: rgba(239, 68, 68, 0.2);
            color: #f87171;
            border: 1px solid rgba(239, 68, 68, 0.4);
        }

        .badge-status-selesai {
            background-color: rgba(59, 130, 246, 0.2);
            color: #60a5fa;
            border: 1px solid rgba(59, 130, 246, 0.4);
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

@section('content')
    <div class="container-fluid px-3 px-md-4 py-4">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <h5 class="mb-1 fw-bold text-white"><i class="bi bi-file-earmark-text me-2"></i>BCF Registrasi</h5>
                <small class="text-muted">Kelola data pendaftaran dan registrasi BCF.</small>
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
                            <th width="50">#</th>
                            <th>Nama</th>
                            <th>PN</th>
                            <th>Unit Kerja</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Keterangan</th>
                            <th width="110">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($registrasi as $index => $row)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td class="fw-semibold">{{ $row->nama }}</td>
                                <td><code>{{ $row->pn }}</code></td>
                                <td>{{ $row->unit_kerja ?? '-' }}</td>
                                <td>{{ \Carbon\Carbon::parse($row->tanggal)->format('d M Y') }}</td>
                                <td>
                                    @php
                                        $statusClass = match (strtolower($row->status)) {
                                            'disetujui' => 'badge-status-disetujui',
                                            'ditolak' => 'badge-status-ditolak',
                                            'selesai' => 'badge-status-selesai',
                                            default => 'badge-status-pending',
                                        };
                                    @endphp
                                    <span class="badge {{ $statusClass }} px-2 py-1">
                                        {{ $row->status }}
                                    </span>
                                </td>
                                <td>{{ Str::limit($row->keterangan ?? '-', 40) }}</td>
                                <td>
                                    <div class="action-group">
                                        <button type="button" class="act-btn e btn-edit-bcf"
                                            data-id="{{ $row->id }}"
                                            data-nama="{{ $row->nama }}"
                                            data-pn="{{ $row->pn }}"
                                            data-unit="{{ $row->unit_kerja }}"
                                            data-tanggal="{{ \Carbon\Carbon::parse($row->tanggal)->format('Y-m-d') }}"
                                            data-status="{{ $row->status }}"
                                            data-keterangan="{{ $row->keterangan }}">
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
                        <div class="mb-3">
                            <label for="create_nama" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="nama" id="create_nama" class="form-control" placeholder="Masukkan nama lengkap" required>
                        </div>
                        <div class="mb-3">
                            <label for="create_pn" class="form-label">PN (Personal Number) <span class="text-danger">*</span></label>
                            <input type="text" name="pn" id="create_pn" class="form-control" placeholder="Masukkan PN" required>
                        </div>
                        <div class="mb-3">
                            <label for="create_unit_kerja" class="form-label">Unit Kerja / Bagian</label>
                            <input type="text" name="unit_kerja" id="create_unit_kerja" class="form-control" placeholder="Contoh: Operational / Marketing">
                        </div>
                        <div class="mb-3">
                            <label for="create_tanggal" class="form-label">Tanggal Registrasi <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal" id="create_tanggal" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="create_status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status" id="create_status" class="form-select" required>
                                <option value="Pending" selected>Pending</option>
                                <option value="Disetujui">Disetujui</option>
                                <option value="Ditolak">Ditolak</option>
                                <option value="Selesai">Selesai</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="create_keterangan" class="form-label">Keterangan</label>
                            <textarea name="keterangan" id="create_keterangan" class="form-control" rows="3" placeholder="Keterangan tambahan (opsional)"></textarea>
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
                        <div class="mb-3">
                            <label for="edit_nama" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="nama" id="edit_nama" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_pn" class="form-label">PN (Personal Number) <span class="text-danger">*</span></label>
                            <input type="text" name="pn" id="edit_pn" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_unit_kerja" class="form-label">Unit Kerja / Bagian</label>
                            <input type="text" name="unit_kerja" id="edit_unit_kerja" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="edit_tanggal" class="form-label">Tanggal Registrasi <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal" id="edit_tanggal" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status" id="edit_status" class="form-select" required>
                                <option value="Pending">Pending</option>
                                <option value="Disetujui">Disetujui</option>
                                <option value="Ditolak">Ditolak</option>
                                <option value="Selesai">Selesai</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit_keterangan" class="form-label">Keterangan</label>
                            <textarea name="keterangan" id="edit_keterangan" class="form-control" rows="3"></textarea>
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
            // Handle Edit Modal populate
            const editButtons = document.querySelectorAll('.btn-edit-bcf');
            const editForm = document.getElementById('editBcfForm');
            const editNama = document.getElementById('edit_nama');
            const editPn = document.getElementById('edit_pn');
            const editUnit = document.getElementById('edit_unit_kerja');
            const editTanggal = document.getElementById('edit_tanggal');
            const editStatus = document.getElementById('edit_status');
            const editKeterangan = document.getElementById('edit_keterangan');

            editButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.dataset.id;
                    editForm.action = `/bcf-registrasi/${id}`;
                    editNama.value = this.dataset.nama || '';
                    editPn.value = this.dataset.pn || '';
                    editUnit.value = this.dataset.unit || '';
                    editTanggal.value = this.dataset.tanggal || '';
                    editStatus.value = this.dataset.status || 'Pending';
                    editKeterangan.value = this.dataset.keterangan || '';

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
