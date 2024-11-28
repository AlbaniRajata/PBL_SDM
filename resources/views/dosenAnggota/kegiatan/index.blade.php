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
                    <th>No</th>
                    <th>Nama Kegiatan</th>
                    <th>Tanggal Mulai</th>
                    <th>Tanggal Selesai</th>
                    <th>PIC</th>
                    <th>Status</th>
                    <th>Poin Kegiatan</th>
                    <th>Surat Tugas</th>
                    <th>Aksi</th>
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
                    url: "{{ route('dosen.kegiatan.data') }}",
                    type: "GET",
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    }
                },
                columns: [
                    { data: "DT_RowIndex", name: "DT_RowIndex", orderable: false, searchable: false },
                    { data: "nama_kegiatan", name: "nama_kegiatan" },
                    { data: "tanggal_mulai", name: "tanggal_mulai", className: "text-center" },
                    { data: "tanggal_selesai", name: "tanggal_selesai", className: "text-center" },
                    { data: "pic", name: "pic" },
                    { data: "status", name: "status" },
                    { data: "poin_kegiatan", name: "poin_kegiatan", className: "text-center" },
                    { 
                        data: "surat_tugas", 
                        name: "surat_tugas", 
                        className: "text-center", 
                        render: function(data, type, row) {
                            return data ? `<a href="${data}" class="btn btn-sm btn-info" target="_blank">Download</a>` : '-';
                        }
                    },
                    { 
                        data: "aksi", 
                        name: "aksi", 
                        orderable: false, 
                        searchable: false 
                    }
                ]
            });
        });
</script>
@endpush