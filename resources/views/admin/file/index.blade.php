@extends('layouts.template')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">History File Upload</h3>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Kegiatan</th>
                        <th>Nama Dokumen</th>
                        <th>Progress</th>
                        <th>Tanggal Upload</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dokumen as $file)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $file->kegiatan->nama_kegiatan ?? '-' }}</td>
                        <td>{{ $file->nama_dokumen }}</td>
                        <td>
                            <span class="badge badge-{{ $file->progress == 100 ? 'success' : 'warning' }}">
                                {{ $file->progress }}%
                            </span>
                        </td>
                        <td>{{ $file->created_at ? $file->created_at->format('d M Y H:i') : '-' }}</td>
                        <td>
                            <a href="{{ route('file.download', $file->id_dokumen) }}" class="btn btn-sm btn-primary" title="Download">
                                <i class="fas fa-download"></i> Unduh
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $dokumen->links() }} <!-- Pagination -->
        </div>
    </div>
</div>
@endsection

@push('css')
<style>
    .table-striped tbody tr:nth-of-type(odd) {
        background-color: rgba(0,0,0,.05);
    }
</style>
@endpush
