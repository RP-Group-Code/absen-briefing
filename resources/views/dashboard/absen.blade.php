@extends('layouts.app')

@section('title', 'Dashboard Absen Briefing')

@section('content')
    <div class="container-fluid px-3 px-md-4 py-4">

        {{-- ── STATS CARDS ── --}}
        <div class="stats-wrapper mb-4">
            <p class="section-title">
                <i class="fa-solid fa-alarm-clock fa-sm"></i>
                Absensi Overview
            </p>
            <div class="table-responsive">
                <table class="myTable table table-hover mb-0 display">
                    <thead class="table-light">
                        <tr>
                            <th width="50">#</th>
                            <th>Nama</th>
                            <th>Alasan</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($absen as $index => $data)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $data->pegawai->nama }}</td>
                                <td>{{ $data->alasan }}</td>
                                <td>{{ $data->created_at->format('d/M/Y') }}</td>
                                
                                <td>
                                    <div class="action-group">
                                        <a data-id="{{ $data->id }}" data-nama="{{ $data->pegawai->nama }}" class="act-btn d tombol-hapus"><i class="fa-solid fa-trash"></i></a>
                                    </div>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(".tombol-hapus").click(function() {
            var id = $(this).attr('data-id');
            var nama = $(this).attr('data-nama');

            Swal.fire({
                title: "Yakin Menghapus Absen "+ nama + " ?",
                text: "Anda tidak akan bisa mengembalikan data ini lagi !",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#4CAF50',
                confirmButtonText: 'Ya, Hapus data !'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location = "/delete-absen/" + id + ""
                    Swal.fire(
                        'Terhapus!',
                        "" + nama + " Berhasil Dihapus.  ",
                        'success'
                    )
                }
            })
        })
    </script>
@endpush
