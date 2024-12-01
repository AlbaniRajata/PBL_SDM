@extends('layouts.template')
@section('content')
<div class="container-fluid">
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">Daftar Dokumen</h3>
            <div class="card-tools">
                <form action="{{ route('admin.file.index') }}" method="GET" class="form-inline">
                    <input type="text" name="search" class="form-control" placeholder="Cari dokumen..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary ml-2">Cari</button>
                </form>
            </div>
        </div>
        <div class="card-body">
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Nama Kegiatan</th>
                        <th class="text-center">Nama Dokumen</th>
                        <th class="text-center">Progress</th>
                        <th class="text-center">Tanggal Upload</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dokumen as $file)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td class="text-center">{{ $file->kegiatan->nama_kegiatan ?? '-' }}</td>
                        <td class="text-center">{{ $file->nama_dokumen }}</td>
                        <td class="text-center">
                            <span class="badge badge-{{ $file->progress == 100 ? 'success' : 'warning' }}">
                                {{ $file->progress }}%
                            </span>
                        </td>
                        <td class="text-center">{{ $file->created_at ? $file->created_at->format('d M Y H:i') : '-' }}</td>
                        <td class="text-center">
                            <a href="{{ route('file.download', $file->id_dokumen) }}" class="btn btn-sm btn-primary" title="Download">
                                <i class="fas fa-download"></i> Unduh
                            </a>
                            <button class="btn btn-sm btn-danger" onclick="hapusFile({{ $file->id_dokumen }})" title="Hapus">
                                <i class="fas fa-trash-alt"></i> Hapus
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $dokumen->links() }} <!-- Pagination -->
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

    function hapusFile(id) {
        if (confirm('Apakah Anda yakin ingin menghapus file ini?')) {
            $.ajax({
                url: '{{ url("/file") }}/' + id,
                type: 'DELETE',
                success: function(response) {
                    if (response.status) {
                        location.reload();
                    } else {
                        alert(response.message);
                    }
                },
                error: function(xhr) {
                    alert('Terjadi kesalahan saat menghapus data.');
                }
            });
        }
    }
</script>
@endpush
@endsection
