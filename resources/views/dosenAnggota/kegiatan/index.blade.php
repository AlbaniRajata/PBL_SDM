@extends('layouts.template')

@section('content')
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
        <table class="table table-bordered table-striped table-hover table-sm" id="table_kegiatan">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Kegiatan</th>
                    <th>Tanggal Mulai</th>
                    <th>Tanggal Selesai</th>
                    <th>Tempat Kegiatan</th>
                    <th>PIC</th>
                    <th>Status</th>
                    <th>Surat Tugas</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
<div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" data-width="75%" aria-hidden="true"></div>
@endsection

@push('css')
@endpush

@push('js')
<script>
    function modalAction(url = '') {
        $('#myModal').load(url, function() {
            $('#myModal').modal('show');
        });
    }

    function deleteAction(url = '') {
        if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
            $.ajax({
                url: url,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                success: function(response) {
                    if (response.status) {
                        $('#table_kegiatan').DataTable().ajax.reload();
                        alert(response.message);
                    } else {
                        alert(response.message);
                    }
                }
            });
        }
    }

    var dataKegiatan;
        $(document).ready(function() {
            dataKegiatan = $('#table_kegiatan').DataTable({
                serverSide: true,
                processing: true, // Tambahkan ini untuk menampilkan loading
                ajax: {
                    url: "{{ route('dosenAnggota.kegiatan.dataDosenA') }}",
                    type: "GET",
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    }
                },
                columns: [
                    { data: "DT_RowIndex", name: "DT_RowIndex", orderable: false, searchable: false },
                    { data: "nama_kegiatan", name: "nama_kegiatan", className: "text-center" },
                    { data: "tanggal_mulai", name: "tanggal_mulai", className: "text-center" },
                    { data: "tanggal_selesai", name: "tanggal_selesai", className: "text-center" },
                    { data: "tempat_acara", name: "tempat_acara", className: "text-center" },
                    { data: "pic", name: "pic", className: "text-center" },
                    { data: "progress", name: "progress", className: "text-center" },
                    {
                        data: "surat_tugas",
                        name: "surat_tugas",
                        className: "text-center",
                        render: function(data, type, row) {
                            if (data) {
                                return `<a href="${data}" class="btn btn-sm btn-info" target="_blank">Download</a>`;
                            } else {
                                return '-';
                            }
                        }
                    }
                ]
            });
        });
</script>
@endpush