@extends('layouts.template')
@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <div class="card-tools">
            <a href="{{ url('/dosen/kegiatan/export_excel') }}" class="btn btn-sm btn-outline-success btn-hover"><i class="fa fa-file-excel"></i> Ekspor Kegiatan (Excel)</a>
            <a href="{{ url('/dosen/kegiatan/export_pdf') }}" class="btn btn-sm btn-outline-warning btn-hover"><i class="fa fa-file-pdf"></i> Ekspor Kegiatan (PDF)</a>
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

        <div class="row">
            <div class="col-md-12">
                <div class="form-group row">
                    <label class="col-1 control-label col-form-label">Filter:</label>
                    <div class="col-3">
                        <select class="form-control" id="jenis_kegiatan" name="jenis_kegiatan">
                            <option value="">- Semua -</option>
                            <option value="Kegiatan JTI">Kegiatan JTI</option>
                            <option value="Kegiatan Non-JTI">Kegiatan Non-JTI</option>
                        </select>
                    </div>
                    <small class="form-text text-muted">Jenis Kegiatan</small>
                </div>
            </div>
        </div>
        <table class="table table-bordered table-striped table-hover table-sm" id="kegiatan-table">
            <thead>
                <tr>
                    <th style="width: 5%;" class="text-center">No</th>
                    <th style="width: 15%;" class="text-center">Nama Kegiatan</th>
                    <th style="width: 30%;" class="text-center">Deskripsi Kegiatan</th>
                    <th style="width: 10%;" class="text-center">Tanggal Acara</th>
                    <th style="width: 20%;" class="text-center">Tempat Kegiatan</th>
                    <th style="width: 10%;" class="text-center">Jenis Kegiatan</th>
                    <th style="width: 10%;" class="text-center">Jabatan</th>
                </tr>
            </thead>
            <tbody>
                <!-- Data kegiatan akan diisi di sini -->
            </tbody>
        </table>
    </div>
</div>
<script>
$(document).ready(function() {
    $('#kegiatan-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("dosen.kegiatan.data") }}',
            type: 'GET',
            data: function(d) {
                d.jenis_kegiatan = $('#jenis_kegiatan').val();
            }
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', className: "text-center", orderable: false, searchable: false },
            { data: 'nama_kegiatan', name: 'nama_kegiatan', className: "text-center" },
            { data: 'deskripsi_kegiatan', name: 'deskripsi_kegiatan', className: "text-center" },
            { data: 'tanggal_acara', name: 'tanggal_acara', className: "text-center" },
            { data: 'tempat_kegiatan', name: 'tempat_kegiatan', className: "text-center" },
            { data: 'jenis_kegiatan', name: 'jenis_kegiatan', className: "text-center" },
            { 
                data: 'jabatan_nama', 
                name: 'jabatan_nama', 
                className: "text-center",
                render: function(data, type, row) {
                    return data || '-';
                }
            }
        ]
    });

    // Pemicu filter
    $('#jenis_kegiatan').on('change', function() {
        $('#kegiatan-table').DataTable().draw();
    });
});
</script>
@endsection