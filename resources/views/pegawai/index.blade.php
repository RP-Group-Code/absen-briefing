@extends('layouts.app')

@section('title', 'Master Pegawai')

@push('styles')
    <style>
        #editPegawaiModal .modal-content {
            background: rgba(15, 12, 41, 0.96);
            border: 1px solid rgba(255, 255, 255, .14);
            border-radius: 18px;
            color: rgba(255, 255, 255, .9);
            box-shadow: 0 20px 48px rgba(0, 0, 0, .55);
            backdrop-filter: blur(20px) saturate(160%);
            -webkit-backdrop-filter: blur(20px) saturate(160%);
        }

        #editPegawaiModal .modal-header,
            #editPegawaiModal .modal-footer {
            border-color: rgba(255, 255, 255, .12);
        }

        #editPegawaiModal .modal-title,
        #editPegawaiModal .form-label {
            color: rgba(255, 255, 255, .9) !important;
        }

        #editPegawaiModal .btn-close {
            filter: invert(1) grayscale(100%) brightness(200%);
        }

        #editPegawaiModal .form-control,
        #editPegawaiModal .form-select {
            background: rgba(255, 255, 255, .08);
            color: #fff;
            border: 1px solid rgba(255, 255, 255, .16);
        }

        #editPegawaiModal .form-control:focus,
        #editPegawaiModal .form-select:focus {
            background: rgba(255, 255, 255, .12);
            color: #fff;
            border-color: rgba(129, 140, 248, .85);
            box-shadow: 0 0 0 .2rem rgba(99, 102, 241, .22);
        }

        #editPegawaiModal .form-select option {
            background: #1a1a4e;
            color: #fff;
        }

        #editPegawaiModal .btn-outline-secondary {
            border-color: rgba(255, 255, 255, .28);
            color: rgba(255, 255, 255, .85);
        }

        #editPegawaiModal .btn-outline-secondary:hover {
            background: rgba(255, 255, 255, .15);
            color: #fff;
            border-color: rgba(255, 255, 255, .4);
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid px-3 px-md-4 py-4">

        <div class="stats-wrapper mb-4">
            <p class="section-title">
                <i class="fa-solid fa-users fa-sm"></i>
                Pegawai Overview
            </p>

            <div class="table-responsive">
                <table class="myTable table table-hover mb-0 display ">
                    <thead class="table-light">
                        <tr>
                            <th width="50">#</th>
                            <th>Uker</th>
                            <th>PN</th>
                            <th>Nama</th>
                            <th>Jabatan</th>
                            <th>Jumlah Absen {{ date('M-Y') }}</th>
                            <th width="100">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pegawai as $index => $data)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>( {{ optional($data->uker)->kode_uker ?? '-' }} ) -
                                    {{ optional($data->uker)->nama ?? '-' }} </td>
                                <td>{{ $data->pn }}</td>
                                <td>{{ $data->nama }}</td>
                                <td>{{ $data->jabatan ?? '-' }}</td>
                                <td>{{ $data->jumlah_absen }}</td>
                                <td>
                                    <div class="action-group">
                                        <button type="button" class="act-btn e btn-edit-pegawai"
                                            data-id="{{ $data->id }}" data-uker-id="{{ $data->uker_id }}"
                                            data-pn="{{ $data->pn }}" data-nama="{{ $data->nama }}"
                                            data-jabatan="{{ $data->jabatan }}">
                                            <i class="fa-solid fa-pen"></i>
                                        </button>

                                        <form action="{{ route('pegawai.destroy', $data->id) }}" method="POST"
                                            class="form-delete-pegawai d-inline" data-nama="{{ $data->nama }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="act-btn d">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="modal fade" id="editPegawaiModal" tabindex="-1" aria-labelledby="editPegawaiModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <form method="POST" id="formEditPegawai">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="edit_pegawai_id" id="edit_pegawai_id"
                            value="{{ old('edit_pegawai_id') }}">

                        <div class="modal-header">
                            <h5 class="modal-title text-dark fw-semibold" id="editPegawaiModalLabel">Edit Data Pegawai</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="uker_id" class="form-label text-dark fw-semibold">Unit Kerja</label>
                                    <select name="uker_id" id="uker_id"
                                        class="form-select @error('uker_id') is-invalid @enderror" required>
                                        <option value="">-- Pilih Unit Kerja --</option>
                                        @foreach ($ukers as $uker)
                                            <option value="{{ $uker->id }}">
                                                ({{ $uker->kode_uker }})
                                                - {{ $uker->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('uker_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="pn" class="form-label text-dark fw-semibold">PN</label>
                                    <input type="text" name="pn" id="pn"
                                        class="form-control @error('pn') is-invalid @enderror" value="{{ old('pn') }}"
                                        required>
                                    @error('pn')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="nama" class="form-label text-dark fw-semibold">Nama Pegawai</label>
                                    <input type="text" name="nama" id="nama"
                                        class="form-control @error('nama') is-invalid @enderror"
                                        value="{{ old('nama') }}" required>
                                    @error('nama')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="jabatan" class="form-label text-dark fw-semibold">Jabatan</label>
                                    <input type="text" name="jabatan" id="jabatan"
                                        class="form-control @error('jabatan') is-invalid @enderror"
                                        value="{{ old('jabatan') }}">
                                    @error('jabatan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fa-solid fa-floppy-disk me-1"></i>
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            const $modalEl = $('#editPegawaiModal');
            $modalEl.appendTo('body');
            const modal = new bootstrap.Modal($modalEl[0]);
            const $form = $('#formEditPegawai');

            const updateUrlTemplate = @json(route('pegawai.update', ['pegawai' => '__ID__']));

            function openEditModal(payload) {
                $form.attr('action', updateUrlTemplate.replace('__ID__', payload.id));
                $('#edit_pegawai_id').val(payload.id);
                $('#uker_id').val(String(payload.uker_id ?? ''));
                $('#pn').val(payload.pn ?? '');
                $('#nama').val(payload.nama ?? '');
                $('#jabatan').val(payload.jabatan ?? '');
                modal.show();
            }

            $(document).on('click', '.btn-edit-pegawai', function() {
                const $btn = $(this);
                openEditModal({
                    id: $btn.data('id'),
                    uker_id: $btn.data('uker-id'),
                    pn: $btn.data('pn'),
                    nama: $btn.data('nama'),
                    jabatan: $btn.data('jabatan')
                });
            });

            $(document).on('submit', '.form-delete-pegawai', function(e) {
                e.preventDefault();

                const form = this;
                const nama = $(this).data('nama');

                Swal.fire({
                    title: `Yakin menghapus ${nama}?`,
                    text: 'Data pegawai beserta data absensi terkait akan ikut terhapus.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });

            const oldEditId = @json(old('edit_pegawai_id'));
            if (oldEditId) {
                openEditModal({
                    id: oldEditId,
                    uker_id: @json(old('uker_id')),
                    pn: @json(old('pn')),
                    nama: @json(old('nama')),
                    jabatan: @json(old('jabatan'))
                });
            }
        });
    </script>
@endpush
