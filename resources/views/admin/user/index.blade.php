@extends('layouts.template')
@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">Daftar Pengguna</h3>
        <div class="card-tools">
            <button onclick="modalAction('{{ url('/admin/user/import') }}')" class="btn btn-sm btn-info mt-1">Import Pengguna</button>
            <a href="{{ url('/admin/user/export_excel') }}" class="btn btn-sm btn-primary mt-1"><i class="fa fa-file-excel"></i> Export Pengguna (Excel)</a>
            <a href="{{ url('/admin/user/export_pdf') }}" class="btn btn-sm btn-warning mt-1"><i class="fa fa-file-pdf"></i> Export Pengguna (PDF)</a>
            <button onclick="modalAction('{{ url('/admin/user/create_ajax') }}')" class="btn btn-sm btn-success mt-1">Tambah Ajax</button>
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
                        <select class="form-control" id="level" name="level">
                            <option value="">- Semua -</option>
                            <option value="admin">Admin</option>
                            <option value="dosen">Dosen</option>
                            <option value="pimpinan">Pimpinan</option>
                        </select>
                    </div>
                    <small class="form-text text-muted">Jenis Pengguna</small>
                </div>
            </div>
        </div>
        <table class="table table-bordered table-striped table-hover table-sm" id="user-table">
            <thead>
                <tr>
                    <th style="width: 5%;" class="text-center">No</th>
                    <th style="width: 10%;"class="text-center">Username</th>
                    <th style="width: 20%;"class="text-center">Nama</th>
                    <th style="width: 30%;"class="text-center">Email</th>
                    <th style="width: 10%;"class="text-center">NIP</th>
                    <th style="width: 10%;"class="text-center">Level</th>
                    <th style="width: 15%;"class="text-center">Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
<div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" databackdrop="static" data-keyboard="false" data-width="75%" aria-hidden="true"></div>
@endsection

@push(`css`)
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
        
    var dataUser;
    $(document).ready(function() {
       dataUser = $('#user-table').DataTable({
            serverSide: true,
            ajax: {
                "url": "{{ route('admin.user.list') }}",
                "dataType": "json",
                "type": "POST",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: function (d) {
                    d.level = $('#level').val();
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', className: "text-center", orderable: false, searchable: false },
                { data: 'username', name: 'username', className: "text-center", orderable: true, searchable: true },
                { data: 'nama', name: 'nama', className: "text-center", orderable: true, searchable: true },
                { data: 'email', name: 'email', className: "text-center", orderable: true, searchable: true },
                { data: 'NIP', name: 'NIP', className: "text-center", orderable: true, searchable: true },
                { data: 'level', name: 'level', className: "text-center", orderable: true, searchable: true },
                { data: 'aksi', name: 'aksi', className: "text-center", orderable: false, searchable: false, className: "text-center" }
            ],
        });
        // Load user details in modal
        $('#table_user').on('click', '.view-user', function() {
            var userId = $(this).data('id');
            modalAction("{{ route('admin.user.show_ajax', '') }}/" + userId);
        });

        // Load edit user form in modal
        $('#table_user').on('click', '.edit-user', function() {
            var userId = $(this).data('id');
            modalAction("{{ route('admin.user.edit_ajax', '') }}/" + userId);
        });

        // Load delete confirmation in modal
        $('#table_user').on('click', '.delete-user', function() {
            var userId = $(this).data('id');
            modalAction("{{ route('admin.user.delete_ajax', '') }}/" + userId);
        });
    });
</script>
@endpush
