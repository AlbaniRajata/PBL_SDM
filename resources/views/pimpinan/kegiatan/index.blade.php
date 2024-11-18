@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">Daftar Kegiatan</h3>
        <div class="card-tools">
            <button onclick="modalAction('{{ url('/kegiatan/import') }}')" class="btn btn-sm btn-info mt-1">Import Kegiatan</button>
            <a href="{{ url('/kegiatan/export_excel') }}" class="btn btn-sm btn-primary mt-1"><i class="fa fa-file-excel"></i> Export Kegiatan (Excel)</a>
            <a href="{{ url('/kegiatan/export_pdf') }}" class="btn btn-sm btn-warning mt-1"><i class="fa fa-file-pdf"></i> Export Kegiatan (PDF)</a>
            <button onclick="modalAction('{{ url('kegiatan/create_ajax') }}')" class="btn btn-sm btn-success mt-1">Tambah Kegiatan</button>
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
        <table class="table table-bordered table-striped table-hover table-sm" id="table_kegiatan">
            <thead>
                <tr>
                    <th class="text-center">Nama Kegiatan</th>
                    <th class="text-center">Tanggal Mulai</th>
                    <th class="text-center">Tanggal Selesai</th>
                    <th class="text-center">PIC</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Poin Kegiatan</th>
                    <th class="text-center">Surat Tugas</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <!-- Table rows will go here -->
            </tbody>
        </table>
    </div>
</div>
<div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" data-width="75%" aria-hidden="true"></div>
@endsection

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

    $(document).ready(function() {
        var dataKegiatan = $('#table_kegiatan').DataTable({
            serverSide: true,
            processing: true,
            ajax: {
                url: "{{ route('admin.kegiatan.list') }}",
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: function (d) {
                    // Add any additional parameters here if needed
                }
            },
            columns: [
                { data: 'nama_kegiatan', name: 'nama_kegiatan', className: "text-center", orderable: true, searchable: true },
                { data: 'tanggal_mulai', name: 'tanggal_mulai', className: "text-center", orderable: true, searchable: true },
                { data: 'tanggal_selesai', name: 'tanggal_selesai', className: "text-center", orderable: true, searchable: true },
                { data: 'pic', name: 'pic', className: "text-center", orderable: true, searchable: true },
                { data: 'status', name: 'status', className: "text-center", orderable: true, searchable: true },
                { data: 'poin_kegiatan', name: 'poin_kegiatan', className: "text-center", orderable: true, searchable: true },
                { data: 'surat_tugas', name: 'surat_tugas', className: "text-center", orderable: true, searchable: true },
                { data: 'aksi', name: 'aksi', className: "text-center", orderable: false, searchable: false }
            ],
        });
    });
</script>
@endpush