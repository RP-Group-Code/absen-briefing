{{-- ✅ Bootstrap JS --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

{{-- Select2 --}}
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

{{-- SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- ✅ DataTables — urutan WAJIB: core dulu, baru adapter, baru buttons --}}
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap5.min.js"></script>

{{-- ✅ Buttons — urutan: core → adapter → libs → ekstensi --}}


<script src="https://cdn.datatables.net/buttons/3.0.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.print.min.js"></script>


<script>
    $(document).ready(function() {

        // Select2
        $('.select2').select2();

        // Toggle Sidebar
        $('#sidebarToggle').on('click', function() {
            $('#sidebar').toggleClass('collapsed');
        });

        // Jam real-time
        function updateClock() {
            $('#clock').text(new Date().toLocaleTimeString('id-ID'));
        }
        updateClock();
        setInterval(updateClock, 1000);

        // ✅ DataTables default dengan tombol Excel, PDF, Print
        // Inisialisasi .myTable jika ada di halaman
        if ($('.myTable').length) {
            $('.myTable').DataTable({
                dom: "<'glass-dt-top row align-items-center mb-3'" +
                    "<'col-auto'l>" +
                    "<'col-auto ms-2'B>" +
                    "<'col ms-auto d-flex justify-content-end'f>" +
                    ">" +
                    "<'row'<'col-12'tr>>" +
                    "<'row mt-3'<'col-sm-5'i><'col-sm-7 d-flex justify-content-end'p>>",
                buttons: [{
                        extend: 'excelHtml5',
                        text: '<i class="fa-solid fa-file-excel me-1"></i> Excel',
                        className: 'buttons-excel',
                        title: document.title,
                        exportOptions: {
                            format: {
                                body: function(data) {
                                    return data.replace(/<[^>]+>/g, '').trim();
                                }
                            }
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        text: '<i class="fa-solid fa-file-pdf me-1"></i> PDF',
                        className: 'buttons-pdf',
                        orientation: 'landscape',
                        pageSize: 'A4',
                        title: document.title,
                        exportOptions: {
                            format: {
                                body: function(data) {
                                    return data.replace(/<[^>]+>/g, '').trim();
                                }
                            }
                        }
                    },
                    // {
                    //     extend: 'print',
                    //     text: '<i class="fa-solid fa-print me-1"></i> Print',
                    //     className: 'buttons-print',
                    //     title: document.title,
                    //     exportOptions: {
                    //         format: {
                    //             body: function(data) {
                    //                 return data.replace(/<[^>]+>/g, '').trim();
                    //             }
                    //         }
                    //     }
                    // }
                ],
                language: {
                    search: '',
                    searchPlaceholder: 'Cari data…',
                    lengthMenu: 'Tampilkan _MENU_ data',
                    info: 'Menampilkan _START_–_END_ dari _TOTAL_ data',
                    infoEmpty: 'Tidak ada data',
                    infoFiltered: '(difilter dari _MAX_)',
                    paginate: {
                        previous: '<i class="fa-solid fa-chevron-left fa-xs"></i>',
                        next: '<i class="fa-solid fa-chevron-right fa-xs"></i>'
                    }
                }
            });
        }
    });
</script>
