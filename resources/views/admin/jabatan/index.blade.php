@extends('layouts.template')
@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">Daftar Jabatan Kegiatan</h3>
        <div class="card-tools">
            <button onclick="modalAction('{{ url('/jabatan_kegiatan/import') }}')" class="btn btn-sm btn-info mt-1">Import Jabatan Kegiatan</button>
            <a href="{{ url('/jabatan_kegiatan/export_excel') }}" class="btn btn-sm btn-primary mt-1"><i class="fa fa-file-excel"></i> Export Jabatan Kegiatan (Excel)</a>
            <a href="{{ url('/jabatan_kegiatan/export_pdf') }}" class="btn btn-sm btn-warning mt-1"><i class="fa fa-file-pdf"></i> Export Jabatan Kegiatan (PDF)</a>
            <button onclick="modalAction('{{ url('jabatan_kegiatan/create_ajax') }}')" class="btn btn-sm btn-success mt-1">Tambah Ajax</button>
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
                    <label for="jenis_kegiatan" class="col-sm-2 col-form-label">Jenis Kegiatan</label>
                    <div class="col-sm-10">
                        <select id="jenis_kegiatan" class="form-control">
                            <option value="">Semua</option>
                            <!-- Tambahkan opsi jenis kegiatan di sini -->
                        </select>
                    </div>
                </div>
                <table id="jabatan-kegiatan-table" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>ID User</th>
                            <th>ID Jabatan Kegiatan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <!-- Konten modal akan dimuat di sini -->
        </div>
    </div>
</div>

@push('scripts')
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

    var dataJabatanKegiatan;
    $(document).ready(function() {
        dataJabatanKegiatan = $('#jabatan-kegiatan-table').DataTable({
            serverSide: true,
            ajax: {
                "url": "{{ route('admin.jabatan_kegiatan.list') }}",
                "dataType": "json",
                "type": "POST",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: function (d) {
                    d.jenis_kegiatan = $('#jenis_kegiatan').val();
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', className: "text-center", orderable: false, searchable: false },
                { data: 'id_user', name: 'id_user', className: "text-center", orderable: true, searchable: true },
                { data: 'id_jabatan_kegiatan', name: 'id_jabatan_kegiatan', className: "text-center", orderable: true, searchable: true },
                { data: 'aksi', name: 'aksi', className: "text-center", orderable: false, searchable: false }
            ],
        });

        $('#jenis_kegiatan').on('change', function() {
            dataJabatanKegiatan.ajax.reload();
        });
    });
</script>
@endpush
@endsection