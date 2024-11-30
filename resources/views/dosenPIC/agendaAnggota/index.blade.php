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
                            <th style="width: 25%;" class="text-center">Jenis Kegiatan</th>
                            <th style="width: 20%;" class="text-center">Tempat Kegiatan</th>
                            <th style="width: 20%;" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($agendaAnggota as $index => $item)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>{{ $item->nama_kegiatan }}</td>
                                <td>{{ $item->jenis_kegiatan }}</td>
                                <td>{{ $item->tempat_kegiatan }}</td>
                                <td class="text-center">
                                    <a href="{{ route('agendaAnggota.edit', $item->id_kegiatan) }}" class="btn btn-sm btn-primary">Agenda</a>
                                    <a href="{{ url('/dosenPIC/agendaAnggota/detail/'.$item->id_kegiatan) }}" class="btn btn-sm btn-info">Detail</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection