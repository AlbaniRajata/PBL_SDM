@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">Jenis Pengguna</h3>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-striped table-hover table-sm" id="user-table">
            <thead>
                <tr>
                    <th class="text-center" style="width: 15%">No</th>
                    <th class="text-center" style="width: 85%">Level</th>
                </tr>
            </thead>
            <tbody>
                <!-- Table rows will be populated by DataTables -->
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('css')
@endpush

@push('js')
<script>
    $(document).ready(function() {
        var staticData = [
            { level: 'admin' },
            { level: 'pimpinan' },
            { level: 'dosen' }
        ];

        var dataUser = $('#user-table').DataTable({
            data: staticData,
            columns: [
                { data: null, className: "text-center", orderable: false, searchable: false },
                { data: 'level', className: "text-center", orderable: true, searchable: true }
            ],
            columnDefs: [
                {
                    targets: 0,
                    render: function (data, type, row, meta) {
                        return meta.row + 1; // Add row number
                    }
                }
            ]
        });
    });
</script>
@endpush