@extends('layouts.template')
@section('content')
<div class="container-fluid">
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">Daftar Kegiatan</h3>
        </div>
        <div class="card-body">
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <table class="table table-bordered table-striped" id="dataTable">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Nama Kegiatan</th>
                        <th class="text-center">Jenis Kegiatan</th>
                        <th class="text-center">Tempat Kegiatan</th>
                        <th class="text-center">Tanggal Mulai</th>
                        <th class="text-center">Tanggal Selesai</th>
                        <th class="text-center">Anggota</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
            </table>
            <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" data-width="75%" aria-hidden="true"></div>
        </div>
    </div>
</div>
@endsection

@push('css')
@endpush

@push('js')
    <script>
   function modalAction(url = '') {
        $('#myModal').load(url, function() {
            $('#myModal').modal('show');
        });
    }


        $(document).ready(function() {
            $('#dataTable').DataTable({
                searching: false,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('dosenPIC.agendaAnggota.listAgendaAnggota') }}",
                    type: 'GET',
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', className: "text-center" },
                    { data: 'nama_kegiatan', name: 'nama_kegiatan', className: "text-center" },
                    { data: 'jenis_kegiatan', name: 'jenis_kegiatan', className: "text-center" },
                    { data: 'tempat_kegiatan', name: 'tempat_kegiatan', className: "text-center" },
                    { data: 'tanggal_mulai', name: 'tanggal_mulai', className: "text-center" },
                    { data: 'tanggal_selesai', name: 'tanggal_selesai', className: "text-center" },
                    { data: 'total_anggota', name: 'total_anggota', className: "text-center" },
                    { data: 'aksi', name: 'aksi', orderable: false, searchable: false, className: "text-center" },
                ] 
            });
        });

        function modalAction(url) {
            $.ajax({
                url: url,
                type: 'GET',
                success: function(response) {
                    $('#myModal').html(response);
                    $('#myModal').modal('show');
                },
                error: function(xhr) {
                    console.log('data')
                    alert('Terjadi kesalahan saat mengambil data.');
                }
            });
        }
    </script>
@endpush