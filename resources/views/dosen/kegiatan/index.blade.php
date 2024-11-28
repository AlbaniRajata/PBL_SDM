@extends('layouts.template')
@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">Daftar Kegiatan</h3>
        <div class="card-tools">
            <button onclick="modalAction('{{ url('/dosen/kegiatan/create_ajax') }}')" class="btn btn-sm btn-success mt-1">Tambah Ajax</button>
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

        <!-- Notifikasi Kegiatan Akan Datang -->
        @if ($kegiatanAkanDatang->count() > 0)
            <div class="alert alert-info">
                <h5><i class="icon fas fa-info"></i> Kegiatan Akan Datang</h5>
                <ul>
                    @foreach ($kegiatanAkanDatang as $kegiatan)
                        <li>
                            {{ $kegiatan->nama_kegiatan }} - {{ $kegiatan->tanggal_mulai}}
                        </li>
                    @endforeach
                </ul>
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
                    <th style="width: 15%;" class="text-center">Deskripsi Kegiatan</th>
                    <th style="width: 10%;" class="text-center">Tanggal Mulai</th>
                    <th style="width: 10%;" class="text-center">Tanggal Selesai</th>
                    <th style="width: 10%;" class="text-center">Tanggal Acara</th>
                    <th style="width: 10%;" class="text-center">Tempat Kegiatan</th>
                    <th style="width: 10%;" class="text-center">Jenis Kegiatan</th>
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
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'nama_kegiatan', name: 'nama_kegiatan' },
            { data: 'deskripsi_kegiatan', name: 'deskripsi_kegiatan' },
            { data: 'tanggal_mulai', name: 'tanggal_mulai' },
            { data: 'tanggal_selesai', name: 'tanggal_selesai' },
            { data: 'tanggal_acara', name: 'tanggal_acara' },
            { data: 'tempat_kegiatan', name: 'tempat_kegiatan' },
            { data: 'jenis_kegiatan', name: 'jenis_kegiatan' }
        ]
    });

    // Pemicu filter
    $('#jenis_kegiatan').on('change', function() {
        $('#kegiatan-table').DataTable().draw();
    });
});
</script>
@endsection