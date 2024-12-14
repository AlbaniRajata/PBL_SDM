@extends('layouts.template')
@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <div class="card-tools">
            <button onclick="modalAction('{{ url('/dosen/kegiatan/create_ajax') }}')" class="btn btn-sm btn-outline-secondary btn-hover"><i class="fa-solid fa-users-gear"></i>Tambah Kegiatan Non-JTI</button>
        </div>
    </div>
    <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
                @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        <table class="table table-bordered table-striped table-hover table-sm" id="kegiatan-table">
            <thead>
                <tr>
                    <th style="width: 5%;" class="text-center">No</th>
                    <th style="width: 15%;" class="text-center">Nama Kegiatan</th>
                    <th style="width: 15%;" class="text-center">Deskripsi Kegiatan</th>
                    <th style="width: 10%;" class="text-center">Tanggal Mulai</th>
                    <th style="width: 10%;" class="text-center">Tanggal Selesai</th>
                    <th style="width: 10%;" class="text-center">Tanggal Acara</th>
                    <th style="width: 10%;" class="text-center">Tempat Kegiatan</th>
                    <th style="width: 10%;" class="text-center">Jenis Kegiatan</th>
                    <th style="width: 15%;" class="text-center">Aksi</th>
                </tr>
            </thead>
        </table>
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="detailModalLabel">Detail Kegiatan</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Konten detail akan dimuat di sini -->
                    </div>
                </div>
            </div>
        </div>
        
        @push('css')
        @endpush
        
        @push('js')
        <script>
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            
        
        function modalAction(url = '') {
        $('#myModal').load(url, function() {
            $('#myModal').modal('show');
        });
    }
        var dataKegiatan;
        $(document).ready(function() {
            dataKegiatan = $('#kegiatan-table').DataTable({
                serverSide: true,
                ajax: {
                    url: "{{route('dosen.kegiatan.nonjti.list') }}", // Fixed URL syntax
                    dataType: "json",
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: function (d) {
                        d.jenis_kegiatan = "Kegiatan Non-JTI";
                    }
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', className: "text-center", orderable: false, searchable: false },
                    { data: 'nama_kegiatan', name: 'nama_kegiatan', className: "text-center", orderable: true, searchable: true },
                    { data: 'deskripsi_kegiatan', name: 'deskripsi_kegiatan', className: "text-center", orderable: true, searchable: true },
                    { data: 'tanggal_mulai', name: 'tanggal_mulai', className: "text-center", orderable: true, searchable: true },
                    { data: 'tanggal_selesai', name: 'tanggal_selesai', className: "text-center", orderable: true, searchable: true },
                    { data: 'tanggal_acara', name: 'tanggal_acara', className: "text-center", orderable: true, searchable: true },
                    { data: 'tempat_kegiatan', name: 'tempat_kegiatan', className: "text-center", orderable: true, searchable: true },
                    { data: 'jenis_kegiatan', name: 'jenis_kegiatan', className: "text-center", orderable: true, searchable: true },
                    { data: 'aksi', name: 'aksi', className: "text-center", orderable: false, searchable: false }
                ]
            });

            $('#jenis_kegiatan').on('change', function() {
                dataKegiatan.ajax.reload();
            });
        });
        </script>
        @endpush
@endsection