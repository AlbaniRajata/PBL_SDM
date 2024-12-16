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
        <table class="table table-bordered table-striped table-hover table-sm" id="table_kegiatan">
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 20%;">Nama Kegiatan</th>
                    <th style="width: 10%;">Tanggal Mulai</th>
                    <th style="width: 10%;">Tanggal Selesai</th>
                    <th style="width: 15%;">Tempat Kegiatan</th>
                    <th style="width: 10%;">Status (%)</th>
                    <th style="width: 10%;">PIC</th>
                    <th style="width: 10%;">Surat Tugas</th>
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
    var dataKegiatan;
    $(document).ready(function() {
        dataKegiatan = $('#table_kegiatan').DataTable({
            serverSide: true,
            processing: true,
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
                { data: "progress", name: "progress", className: "text-center" },
                { data: "pic", name: "pic", className: "text-center" },
                {
                    data: "surat_tugas",
                    name: "surat_tugas",
                    className: "text-center",
                    render: function(data, type, row) {
            // Filter dokumen hanya untuk jenis_dokumen = 'surat tugas'
            var dokumenSuratTugas = row.dokumen.filter(function(dok) {
                            return dok.jenis_dokumen === 'surat tugas';
                        });

                        // Jika ada dokumen dengan jenis 'surat tugas', buat tombol download
                        if (dokumenSuratTugas.length > 0) {
                            var dokumenHtml = '';
                            var dokumenTerakhir = dokumenSuratTugas[dokumenSuratTugas.length - 1];
                            dokumenHtml += '<a href="' + 
                                "{{ route('kegiatan.download-surat', ':id') }}".replace(':id', dokumenTerakhir.id_dokumen) + 
                                '" class="btn btn-sm btn-primary mr-1"><i class="fas fa-download"></i> Download</a>';
                            return dokumenHtml;
                        }

            // Jika tidak ada dokumen dengan jenis 'surat tugas'
            return '<button class="btn btn-sm btn-warning"><i class="fas fa-exclamation-triangle"></i> Tidak ada dokumen</button>';
                    }
                }
            ]
        });
    });

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
</script>
@endpush