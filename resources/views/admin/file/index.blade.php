@extends('layouts.template')
@section('content')
<div class="container-fluid">
    <div class="card card-outline card-primary">
        <div class="card-header">
            <div class="card-tools">
                <form action="{{ route('admin.file.index') }}" method="GET" class="form-inline"></form>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped table-hover table-sm" id="file-table">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 5%">No</th>
                        <th class="text-center" style="width: 25%">Nama Kegiatan</th>
                        <th class="text-center" style="width: 25%">Nama Dokumen</th>
                        <th class="text-center" style="width: 25%">Tanggal Upload</th>
                        <th class="text-center" style="width: 20%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dokumen as $file)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td class="text-center">{{ $file->kegiatan->nama_kegiatan ?? '-' }}</td>
                        <td class="text-center">{{ $file->nama_dokumen }}</td>
                        <td class="text-center">{{ $file->created_at ? $file->created_at->format('d M Y H:i') : '-' }}</td>
                        <td class="text-center">
                            <a href="{{ route('file.download', $file->id_dokumen) }}" class="btn btn-sm btn-primary" title="Download">
                                <i class="fas fa-download"></i> Unduh
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('css')
@endpush

@push('js')
<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function hapusFile(id) {
            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: 'Apakah Anda yakin ingin menghapus file ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ url("/admin/file") }}/' + id,
                        type: 'DELETE',
                        success: function(response) {
                            if (response.status) {
                                Swal.fire({
                                    title: 'Berhasil!',
                                    text: response.message || 'File berhasil dihapus.',
                                    icon: 'success'
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    title: 'Gagal!',
                                    text: response.message || 'Gagal menghapus file.',
                                    icon: 'error'
                                });
                            }
                        },
                        error: function(xhr) {
                            Swal.fire({
                                title: 'Kesalahan!',
                                text: 'Terjadi kesalahan saat menghapus data.',
                                icon: 'error'
                            });
                        }
                    });
                }
            });
        }

        var dataUser = $('#file-table').DataTable({
            columnDefs: [
                {
                    targets: 0,
                    render: function (data, type, row, meta) {
                        return meta.row + 1;
                    }
                }
            ]
        });
    });
</script>
@endpush
@endsection