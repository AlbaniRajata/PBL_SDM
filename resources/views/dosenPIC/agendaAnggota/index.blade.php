@extends('layouts.template')

@section('content')
    <div class="container-fluid">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">{{ $breadcrumb->title }}</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped table-hover" id="agenda_anggota-table">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Nama Kegiatan</th>
                            <th class="text-center">Jenis Kegiatan</th>
                            <th class="text-center">Tempat Kegiatan</th>
                            <th class="text-center">Anggota</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        // Dalam script DataTables
        $('.btn-agenda').on('click', function() {
            var idKegiatan = $(this).data('id');
            $.ajax({
                url: "{{ url('dosenPIC/agendaAnggota/buat-agenda') }}/" + idKegiatan,
                method: 'GET',
                success: function(response) {
                    $('#modalAction .modal-content').html(response);
                    $('#modalAction').modal('show');
                }
            });
        });
        $(function () {
            $("#agenda_anggota-table").DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ url('dosenPIC/agendaAnggota') }}", // Make sure this route matches your controller method
                columns: [
                    { 
                        data: 'DT_RowIndex', 
                        name: 'DT_RowIndex', 
                        orderable: false, 
                        searchable: false,
                        className: 'text-center'
                    },
                    { data: 'nama_kegiatan', name: 'nama_kegiatan' },
                    { data: 'jenis_kegiatan', name: 'jenis_kegiatan' },
                    { data: 'tempat_kegiatan', name: 'tempat_kegiatan' },
                    { data: 'anggota', name: 'anggota' },
                    { 
                        data: 'aksi', 
                        name: 'aksi', 
                        orderable: false, 
                        searchable: false,
                        className: 'text-center'
                    }
                ],
                responsive: true,
                autoWidth: false,
                pageLength: 10,
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]]
            });
        });
    </script>
@endpush