@extends('layouts.template')
@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">Daftar user</h3>
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
                        <select class="form-control" id="id_jenis_pengguna" name="id_jenis_pengguna" required>
                            <option value="">- Semua -</option>
                        </select>
                    </div>
                    <small class="form-text text-muted">Jenis Pengguna</small>
                </div>
            </div>
        </div>
        <table class="table table-bordered table-striped table-hover table-sm" id="table_user">
            <thead>
                <tr>
                    <th style="width: 5%;" class="text-center">ID Pengguna</th>
                    <th style="width: 20%;" class="text-center">Nama</th>
                    <th style="width: 20%;" class="text-center">Email</th>
                    <th style="width: 15%;" class="text-center">NIP</th>
                    <th style="width: 20%;" class="text-center">Jenis Pengguna</th>
                    <th style="width: 20%;" class="text-center">Aksi</th>
                </tr>
            </thead>
        </table>
        <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" data-width="75%" aria-hidden="true"></div>
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

    $(document).ready(function() {
        var dataUser = $('#table_user').DataTable({
            serverSide: true,
            processing: true,
            ajax: {
                url: "{{ route('pimpinan.user.list') }}",
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: function (d) {
                    d.id_jenis_pengguna = $('#id_jenis_pengguna').val();
                }
            },
            columns: [
                { data: 'id_user', name: 'id_user', className: "text-center", orderable: true, searchable: true },
                { data: 'nama', name: 'nama', className: "text-center", orderable: true, searchable: true },
                { data: 'email', name: 'email', className: "text-center", orderable: true, searchable: true },
                { data: 'NIP', name: 'NIP', className: "text-center", orderable: true, searchable: true },
                { data: 'level', name: 'level', className: "text-center", orderable: true, searchable: true },
                { data: 'aksi', name: 'aksi', className: "text-center", orderable: false, searchable: false }
            ],
        });

        $('#id_jenis_pengguna').on('change', function() {
            dataUser.ajax.reload();
        });
    });
</script>
@endpush
@endsection