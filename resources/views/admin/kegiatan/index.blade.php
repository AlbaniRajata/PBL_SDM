@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">Daftar Kegiatan</h3>
        <div class="card-tools">
            <button onclick="modalAction('{{ url('/kegiatan/import') }}')" class="btn btn-sm btn-info mt-1">Import Kegiatan</button>
            <a href="{{ url('/kegiatan/export_excel') }}" class="btn btn-sm btn-primary mt-1"><i class="fa fa-file-excel"></i> Export Kegiatan (Excel)</a>
            <a href="{{ url('/kegiatan/export_pdf') }}" class="btn btn-sm btn-warning mt-1"><i class="fa fa-file-pdf"></i> Export Kegiatan (PDF)</a>
            <button onclick="modalAction('{{ url('kegiatan/create_ajax') }}')" class="btn btn-sm btn-success mt-1">Tambah Ajax</button>
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
                    <th>No</th>
                    <th>Nama Kegiatan</th>
                    <th>Deskripsi Kegiatan</th>
                    <th>Tanggal Mulai</th>
                    <th>Tanggal Selesai</th>
                    <th>Tanggal Acara</th>
                    <th>Tempat Kegiatan</th>
                    <th>Jenis Kegiatan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
<div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" databackdrop="static" data-keyboard="false" data-width="75%" aria-hidden="true"></div>


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
        var dataKegiatan = $('#kegiatan-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route("admin.kegiatan.list") }}',
                type: 'GET',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: function (d) {
                    d.jenis_kegiatan = $('#jenis_kegiatan').val();
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', className: "text-center", orderable: false, searchable: false },
                { data: 'nama_kegiatan', name: 'nama_kegiatan', orderable: true, searchable: true },
                { data: 'deskripsi_kegiatan', name: 'deskripsi_kegiatan', orderable: true, searchable: true },
                { data: 'tanggal_mulai', name: 'tanggal_mulai', orderable: true, searchable: true },
                { data: 'tanggal_selesai', name: 'tanggal_selesai', orderable: true, searchable: true },
                { data: 'tanggal_acara', name: 'tanggal_acara', orderable: true, searchable: true },
                { data: 'tempat_kegiatan', name: 'tempat_kegiatan', orderable: true, searchable: true },
                { data: 'jenis_kegiatan', name: 'jenis_kegiatan', orderable: true, searchable: true },
                { data: 'aksi', name: 'aksi', orderable: false, searchable: false, className: "text-center" }
            ],
            order: [[1, 'asc']],
            responsive: true,
            autoWidth: false,
        });

        $('#jenis_kegiatan').on('change', function() {
            dataKegiatan.ajax.reload();
        });
    });
</script>
@endpush
@endsection