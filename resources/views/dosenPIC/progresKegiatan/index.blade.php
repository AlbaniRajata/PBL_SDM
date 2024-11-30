@extends('layouts.template')

@section('content')
<div class="container-fluid">
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">Daftar Kegiatan</h3>
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
            <table class="table table-bordered table-striped table-hover table-sm" id="kegiatan-table">
                <thead>
                    <tr>
                        <th style="width: 5%;" class="text-center">No</th>
                        <th style="width: 50%;" class="text-center">Nama Kegiatan</th>
                        <th style="width: 20%;" class="text-center">Progress</th>
                        <th style="width: 25%;" class="text-center">Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<!-- Modal for Edit and Detail -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <!-- Konten modal akan dimuat di sini melalui AJAX -->
        </div>
    </div>
</div>

@push('js')
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
    function modalAction(url) {
        $.ajax({
            url: url,
            type: 'GET',
            success: function(response) {
                $('#myModal .modal-content').html(response);
                $('#myModal').modal('show');
            },
            error: function(xhr) {
                alert('Terjadi kesalahan saat mengambil data.');
            }
        });
    }

    $(document).ready(function() {
        $('#kegiatan-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route("dosenPIC.progresKegiatan.list") }}',
                type: 'GET',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: "text-center" },
                { data: 'nama_kegiatan', name: 'nama_kegiatan', className: "text-center" },
                { data: 'progress', name: 'progress', className: "text-center" },
                { data: 'aksi', name: 'aksi', orderable: false, searchable: false, className: "text-center" }
            ]
        });
    });
</script>
@endpush
@endsection