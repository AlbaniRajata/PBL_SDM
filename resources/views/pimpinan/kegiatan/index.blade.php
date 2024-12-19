@extends('layouts.template')
@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">Daftar Kegiatan</h3>
        <div class="card-tools">
            <a href="{{ route('admin.kegiatan.export_excel') }}" class="btn btn-sm btn-primary mt-1"><i class="fa fa-file-excel"></i> Export Kegiatan (Excel)</a>
            <a href="{{ route('admin.kegiatan.export_pdf') }}" class="btn btn-sm btn-warning mt-1"><i class="fa fa-file-pdf"></i> Export Kegiatan (PDF)</a>
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
        <div class="row mb-3">
            <div class="col-md-3">
                <label for="jenis_kegiatan">Jenis Kegiatan:</label>
                <select class="form-control" id="jenis_kegiatan" name="jenis_kegiatan">
                    <option value="">- Pilih Jenis Kegiatan -</option>
                    <option value="Kegiatan JTI">Kegiatan JTI</option>
                    <option value="Kegiatan Non-JTI">Kegiatan Non-JTI</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="periode">Periode Kegiatan:</label>
                <select class="form-control" id="periode" name="periode">
                    <option value="">Pilih Periode Kegiatan</option>
                    @foreach($years as $year)
                        <option value="{{ $year }}">Periode {{ $year }} / {{ $year + 1 }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <table class="table table-bordered table-striped table-hover table-sm" id="kegiatan-table">
            <thead>
                <tr>
                    <th style="width: 5%;" class="text-center">No</th>
                    <th style="width: 20%;" class="text-center">Nama Kegiatan</th>
                    <th style="width: 10%;" class="text-center">Tanggal Mulai</th>
                    <th style="width: 10%;" class="text-center">Tanggal Selesai</th>
                    <th style="width: 10%;" class="text-center">Tanggal Acara</th>
                    <th style="width: 15%;" class="text-center">Tempat Kegiatan</th>
                    <th style="width: 15%;" class="text-center">Jenis Kegiatan</th>
                    <th style="width: 15%;" class="text-center">Aksi</th>
                </tr>
            </thead>
        </table>
        <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" data-width="75%" aria-hidden="true"></div>
        
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
                        "url": "{{ route('pimpinan.kegiatan.list') }}",
                        "dataType": "json",
                        "type": "POST",
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        data: function (d) {
                            d.jenis_kegiatan = $('#jenis_kegiatan').val();
                            d.periode = $('#periode').val(); // Tambahkan '#' sebelum 'periode'
                        }
                    },
                    columns: [
                        { data: 'DT_RowIndex', name: 'DT_RowIndex', className: "text-center", orderable: false, searchable: false },
                        { data: 'nama_kegiatan', name: 'nama_kegiatan', className: "text-center", orderable: true, searchable: true },
                        { data: 'tanggal_mulai', name: 'tanggal_mulai', className: "text-center", orderable: true, searchable: true },
                        { data: 'tanggal_selesai', name: 'tanggal_selesai', className: "text-center", orderable: true, searchable: true },
                        { data: 'tanggal_acara', name: 'tanggal_acara', className: "text-center", orderable: true, searchable: true },
                        { data: 'tempat_kegiatan', name: 'tempat_kegiatan', className: "text-center", orderable: true, searchable: true },
                        { data: 'jenis_kegiatan', name: 'jenis_kegiatan', className: "text-center", orderable: true, searchable: true },
                        { data: 'aksi', name: 'aksi', className: "text-center", orderable: false, searchable: false }
                    ],
                });
        
                // Event listener untuk filter 'Jenis Kegiatan'
                $('#jenis_kegiatan').on('change', function() {
                    dataKegiatan.ajax.reload(); // Reload DataTable ketika filter jenis_kegiatan berubah
                });

                // Event listener untuk filter 'Periode'
                $('#periode').change(function() {
                    dataKegiatan.ajax.reload(); // Reload DataTable ketika filter periode berubah
                });
            });
        </script>
        @endpush
@endsection