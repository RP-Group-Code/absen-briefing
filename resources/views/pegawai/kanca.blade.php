@extends('layouts.app')

@section('title', 'Master Pegawai Kanca')

@push('styles')
    <style>
        #createKancaModal .modal-content,
        #editKancaModal .modal-content {
            background: rgba(15, 12, 41, 0.96);
            border: 1px solid rgba(255, 255, 255, .14);
            border-radius: 18px;
            color: rgba(255, 255, 255, .9);
            box-shadow: 0 20px 48px rgba(0, 0, 0, .55);
            backdrop-filter: blur(20px) saturate(160%);
            -webkit-backdrop-filter: blur(20px) saturate(160%);
        }

        #createKancaModal .modal-header,
        #createKancaModal .modal-footer,
        #editKancaModal .modal-header,
        #editKancaModal .modal-footer {
            border-color: rgba(255, 255, 255, .12);
        }

        #createKancaModal .modal-title,
        #createKancaModal .form-label,
        #editKancaModal .modal-title,
        #editKancaModal .form-label {
            color: rgba(255, 255, 255, .9) !important;
        }

        #createKancaModal .btn-close,
        #editKancaModal .btn-close {
            filter: invert(1) grayscale(100%) brightness(200%);
        }

        #createKancaModal .form-control,
        #createKancaModal .form-select,
        #editKancaModal .form-control,
        #editKancaModal .form-select {
            background: rgba(255, 255, 255, .08);
            color: #fff;
            border: 1px solid rgba(255, 255, 255, .16);
        }

        #createKancaModal .form-control:focus,
        #createKancaModal .form-select:focus,
        #editKancaModal .form-control:focus,
        #editKancaModal .form-select:focus {
            background: rgba(255, 255, 255, .12);
            color: #fff;
            border-color: rgba(129, 140, 248, .85);
            box-shadow: 0 0 0 .2rem rgba(99, 102, 241, .22);
        }

        #createKancaModal .form-select option,
        #editKancaModal .form-select option {
            background: #1a1a4e;
            color: #fff;
        }

        /* Custom dropdown styling */
        .custom-dropdown-menu {
            background: rgba(20, 16, 55, 0.98) !important;
            border: 1px solid rgba(255, 255, 255, .16) !important;
            border-radius: 12px !important;
            box-shadow: 0 15px 35px rgba(0, 0, 0, .6) !important;
            backdrop-filter: blur(10px) !important;
            -webkit-backdrop-filter: blur(10px) !important;
            padding: 8px 0 !important;
            margin-top: 4px !important;
        }

        .custom-dropdown-menu .dropdown-item {
            color: rgba(255, 255, 255, .85) !important;
            font-size: 0.9rem;
            transition: all 0.2s ease;
        }

        .custom-dropdown-menu .dropdown-item:hover,
        .custom-dropdown-menu .dropdown-item:focus {
            background: rgba(99, 102, 241, 0.25) !important;
            color: #fff !important;
        }

        .custom-dropdown-menu .add-new-wrapper a {
            color: #10b981 !important;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            margin-top: 4px;
            padding-top: 8px;
        }

        .custom-dropdown-menu .add-new-wrapper a:hover {
            background: rgba(16, 185, 129, 0.15) !important;
            color: #34d399 !important;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid px-3 px-md-4 py-4">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <h5 class="mb-1 fw-bold text-white">Master Pegawai Kanca</h5>
                <small class="text-muted">Kelola data divisi, jabatan, dan nama pegawai kanca.</small>
            </div>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createKancaModal">
                <i class="fa-solid fa-plus me-1"></i> Tambah Pegawai
            </button>
        </div>

        <div class="stats-wrapper mb-4">
            <div class="table-responsive">
                <table class="myTable table table-hover mb-0 display">
                    <thead class="table-light">
                        <tr>
                            <th width="50">#</th>
                            <th>Divisi</th>
                            <th>Jabatan</th>
                            <th>Nama</th>
                            <th>Dibuat</th>
                            <th width="110">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($kanca as $index => $row)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $row->division }}</td>
                                <td>{{ $row->jabatan }}</td>
                                <td>{{ $row->name }}</td>
                                <td>{{ optional($row->created_at)->format('d M Y H:i') ?? '-' }}</td>
                                <td>
                                    <div class="action-group">
                                        <button type="button" class="act-btn e btn-edit-kanca"
                                            data-id="{{ $row->id }}" data-division="{{ $row->division }}"
                                            data-jabatan="{{ $row->jabatan }}" data-name="{{ $row->name }}">
                                            <i class="fa-solid fa-pen"></i>
                                        </button>

                                        <form action="{{ route('pegawai.kanca.destroy', $row->id) }}" method="POST"
                                            class="form-delete-kanca d-inline" data-nama="{{ $row->name }}">
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
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">Belum ada data pegawai kanca.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="createKancaModal" tabindex="-1" aria-labelledby="createKancaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form method="POST" action="{{ route('pegawai.kanca.store') }}">
                    @csrf
                    <input type="hidden" name="form_mode" value="create">

                    <div class="modal-header">
                        <h5 class="modal-title text-dark fw-semibold" id="createKancaModalLabel">Tambah Pegawai Kanca</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="create_division" class="form-label text-dark fw-semibold">Divisi</label>
                                <select name="division" id="create_division"
                                    class="form-select @error('division') is-invalid @enderror" required>
                                    <option value="">-- Pilih Divisi --</option>
                                    @foreach ($divisionOptions as $division)
                                        <option value="{{ $division }}" @selected(old('division') === $division)>
                                            {{ $division }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('division')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 position-relative" id="create-jabatan-container">
                                <label for="create_jabatan" class="form-label text-dark fw-semibold">Jabatan</label>
                                <input type="text" name="jabatan" id="create_jabatan"
                                    class="form-control @error('jabatan') is-invalid @enderror"
                                    value="{{ old('jabatan') }}" placeholder="Pilih atau Ketik Jabatan..." required
                                    style="text-transform: uppercase;"
                                    autocomplete="off">
                                <ul class="dropdown-menu custom-dropdown-menu w-100" id="create_jabatan_options" 
                                    style="max-height: 200px; overflow-y: auto; display: none; position: absolute; z-index: 2000; left: 0; right: 0;">
                                    @foreach ($jabatanOptions as $jabatan)
                                        <li>
                                            <a class="dropdown-item py-2 px-3" href="#" data-value="{{ strtoupper($jabatan) }}">
                                                {{ strtoupper($jabatan) }}
                                            </a>
                                        </li>
                                    @endforeach
                                    <li class="add-new-wrapper" style="display: none;">
                                        <a class="dropdown-item fw-semibold py-2 px-3" href="#">
                                            <i class="fa-solid fa-plus-circle me-1"></i> Tambah "<span class="new-val"></span>"
                                        </a>
                                    </li>
                                </ul>
                                @error('jabatan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <label for="create_name" class="form-label text-dark fw-semibold">Nama Pegawai</label>
                                <input type="text" name="name" id="create_name"
                                    class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}"
                                    required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-floppy-disk me-1"></i>
                            Simpan Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editKancaModal" tabindex="-1" aria-labelledby="editKancaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form method="POST" id="formEditKanca">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="form_mode" value="edit">
                    <input type="hidden" name="edit_kanca_id" id="edit_kanca_id" value="{{ old('edit_kanca_id') }}">

                    <div class="modal-header">
                        <h5 class="modal-title text-dark fw-semibold" id="editKancaModalLabel">Edit Pegawai Kanca</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="edit_division" class="form-label text-dark fw-semibold">Divisi</label>
                                <select name="division" id="edit_division"
                                    class="form-select @error('division') is-invalid @enderror" required>
                                    <option value="">-- Pilih Divisi --</option>
                                    @foreach ($divisionOptions as $division)
                                        <option value="{{ $division }}">{{ $division }}</option>
                                    @endforeach
                                </select>
                                @error('division')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 position-relative" id="edit-jabatan-container">
                                <label for="edit_jabatan" class="form-label text-dark fw-semibold">Jabatan</label>
                                <input type="text" name="jabatan" id="edit_jabatan"
                                    class="form-control @error('jabatan') is-invalid @enderror" required
                                    style="text-transform: uppercase;"
                                    autocomplete="off">
                                <ul class="dropdown-menu custom-dropdown-menu w-100" id="edit_jabatan_options" 
                                    style="max-height: 200px; overflow-y: auto; display: none; position: absolute; z-index: 2000; left: 0; right: 0;">
                                    @foreach ($jabatanOptions as $jabatan)
                                        <li>
                                            <a class="dropdown-item py-2 px-3" href="#" data-value="{{ strtoupper($jabatan) }}">
                                                {{ strtoupper($jabatan) }}
                                            </a>
                                        </li>
                                    @endforeach
                                    <li class="add-new-wrapper" style="display: none;">
                                        <a class="dropdown-item fw-semibold py-2 px-3" href="#">
                                            <i class="fa-solid fa-plus-circle me-1"></i> Tambah "<span class="new-val"></span>"
                                        </a>
                                    </li>
                                </ul>
                                @error('jabatan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <label for="edit_name" class="form-label text-dark fw-semibold">Nama Pegawai</label>
                                <input type="text" name="name" id="edit_name"
                                    class="form-control @error('name') is-invalid @enderror" required>
                                @error('name')
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
@endsection

@push('scripts')
    <script>
        $(function() {
            const $createModalEl = $('#createKancaModal');
            const $editModalEl = $('#editKancaModal');
            $createModalEl.appendTo('body');
            $editModalEl.appendTo('body');

            const createModal = new bootstrap.Modal($createModalEl[0]);
            const editModal = new bootstrap.Modal($editModalEl[0]);
            const $formEdit = $('#formEditKanca');

            const updateUrlTemplate = @json(route('pegawai.kanca.update', ['kanca' => '__ID__']));

            function openEditModal(payload) {
                $formEdit.attr('action', updateUrlTemplate.replace('__ID__', payload.id));
                $('#edit_kanca_id').val(payload.id);
                $('#edit_division').val(payload.division ?? '');
                $('#edit_jabatan').val(payload.jabatan ?? '');
                $('#edit_name').val(payload.name ?? '');
                editModal.show();
            }

            $(document).on('click', '.btn-edit-kanca', function() {
                const $btn = $(this);
                openEditModal({
                    id: String($btn.data('id') ?? ''),
                    division: String($btn.data('division') ?? ''),
                    jabatan: String($btn.data('jabatan') ?? ''),
                    name: String($btn.data('name') ?? ''),
                });
            });

            $(document).on('submit', '.form-delete-kanca', function(e) {
                e.preventDefault();

                const form = this;
                const nama = $(this).data('nama');

                Swal.fire({
                    title: `Yakin menghapus ${nama}?`,
                    text: 'Data pegawai kanca beserta data absensi terkait akan ikut terhapus.',
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

            const oldFormMode = @json(old('form_mode'));
            if (oldFormMode === 'create') {
                createModal.show();
            }

            if (oldFormMode === 'edit') {
                const oldId = @json(old('edit_kanca_id'));
                if (oldId) {
                    openEditModal({
                        id: String(oldId),
                        division: @json(old('division')),
                        jabatan: @json(old('jabatan')),
                        name: @json(old('name')),
                    });
                }
            }

            // Custom Searchable Dropdown Logic
            function initSearchableDropdown(inputId, menuId, containerId) {
                const $input = $('#' + inputId);
                const $menu = $('#' + menuId);
                const $container = $('#' + containerId);
                const $addNewWrapper = $menu.find('.add-new-wrapper');
                const $newValSpan = $addNewWrapper.find('.new-val');

                // Force uppercase on input typed
                $input.on('input', function() {
                    this.value = this.value.toUpperCase();
                    filterOptions();
                });

                // Show menu on focus or click
                $input.on('focus click', function() {
                    $menu.show();
                    filterOptions();
                });

                // Hide menu when clicking outside
                $(document).on('click', function(e) {
                    if (!$(e.target).closest($container).length) {
                        $menu.hide();
                    }
                });

                // Hide menu on blur with a slight delay to let click event resolve
                $input.on('blur', function() {
                    setTimeout(function() {
                        if (!$(document.activeElement).closest($container).length) {
                            $menu.hide();
                        }
                    }, 200);
                });

                function filterOptions() {
                    const val = $input.val().toUpperCase();
                    let exactMatch = false;
                    let visibleCount = 0;

                    $menu.find('li').not('.add-new-wrapper').each(function() {
                        const itemVal = $(this).find('a').data('value');
                        if (itemVal && itemVal.toUpperCase().includes(val)) {
                            $(this).show();
                            visibleCount++;
                            if (itemVal.toUpperCase() === val) {
                                exactMatch = true;
                            }
                        } else {
                            $(this).hide();
                        }
                    });

                    if (val.trim() !== '' && !exactMatch) {
                        $newValSpan.text(val);
                        $addNewWrapper.show();
                    } else {
                        $addNewWrapper.hide();
                    }

                    if (val.trim() === '') {
                        $menu.find('li').not('.add-new-wrapper').show();
                        $addNewWrapper.hide();
                    }
                }

                // Handle item selection
                $menu.on('click', 'li a', function(e) {
                    e.preventDefault();
                    let selectedVal = $(this).data('value');
                    if (!selectedVal) {
                        selectedVal = $(this).find('.new-val').text();
                    }
                    if (selectedVal) {
                        $input.val(selectedVal.toUpperCase());
                    }
                    $menu.hide();
                });
            }

            initSearchableDropdown('create_jabatan', 'create_jabatan_options', 'create-jabatan-container');
            initSearchableDropdown('edit_jabatan', 'edit_jabatan_options', 'edit-jabatan-container');
        });
    </script>
@endpush
