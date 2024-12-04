@extends('layouts.template')
@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">Jabatan Kegiatan </h3>
            <div class="card-tools">
                <button onclick="modalAction('{{ url('/admin/jabatan/create_ajax') }}')"
                    class="btn btn-sm btn-success mt-1"><i class="fa-solid fa-user-gear"></i>Tambah Jabatan</button>
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
            <table id="jabatan-kegiatan-table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Jabatan</th>
                        <th>Poin</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static"
                data-keyboard="false" data-width="75%" aria-hidden="true"></div>
        </div>
    </div>
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
        var dataJabatan;
        $(document).ready(function() {
            var dataJabatan = $('#jabatan-kegiatan-table').DataTable({
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.jabatan.list') }}",
                    type: 'POST',
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        className: "text-center",
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'jabatan_nama',
                        name: 'jabatan_nama',
                        className: "text-center"
                    },
                    {
                        data: 'poin',
                        name: 'poin',
                        className: "text-center"
                    },
                    {
                        data: 'aksi',
                        name: 'aksi',
                        className: "text-center",
                        orderable: false,
                        searchable: false
                    }
                ],
                columnDefs: [{
                    targets: 0,
                    render: function(data, type, row, meta) {
                        return meta.row + 1;
                    }
                }]
            });
        });
    </script>
@endpush
