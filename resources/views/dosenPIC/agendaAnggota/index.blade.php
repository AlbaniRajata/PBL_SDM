@extends('layouts.template')

@section('content')
    <div class="container-fluid">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">{{ $breadcrumb->title }}</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped table-hover table-sm" id="agenda_anggota-table">
                    <thead>
                        <tr>
                            <th style="width: 5%;" class="text-center">No</th>
                            <th style="width: 30%;" class="text-center">Nama Kegiatan</th>
                            <th style="width: 30%;" class="text-center">Anggota</th>
                            <th style="width: 20%;" class="text-center">Tanggal</th>
                            <th style="width: 15%;" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($agendaAnggota as $index => $item)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>{{ $item->nama_kegiatan }}</td>
                                <td>{{ $item->anggota }}</td>
                                <td>{{ $item->tanggal_mulai }} - {{ $item->tanggal_selesai }}</td>
                                <td class="text-center">
                                    <a href="{{ route('agendaAnggota.edit', $item->id_kegiatan) }}" class="btn btn-sm btn-primary">Edit</a>
                                    <a href="{{ route('agendaAnggota.detail', $item->id_kegiatan) }}" class="btn btn-sm btn-info">Detail</a>
                                    <form action="{{ route('agendaAnggota.delete', $item->id_kegiatan) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection