@extends('layouts.template')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ $breadcrumb->title }}</h3>
        <h3 class="card-title">Daftar Poin Dosen</h3>
        <div class="card-tools">
            <a href="{{ url('/admin/statistik/export_excel') }}" class="btn btn-sm btn-success mt-1">
                <i class="fa-solid fa-file-excel"></i> Ekspor Excel
            </a>
            <a href="{{ url('/admin/statistik/export_pdf') }}" class="btn btn-sm btn-warning mt-1">
                <i class="fa-solid fa-file-pdf"></i> Ekspor PDF
            </a>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th style="width: 5%;" class="text-center">No</th>
                    <th style="width: 19%;" class="text-center">Nama Dosen</th>
                    <th style="width: 60%;" class="text-center">Detail Kegiatan</th>
                    <th style="width: 7%;" class="text-center">Total Kegiatan</th>
                    <th style="width: 8%;" class="text-center">Total Poin</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($poinDosen as $index => $dosen)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td class="text-center">{{ $dosen->nama }}</td>
                        <td>
                            @if (isset($dosenKegiatan[$dosen->id_user]))
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Nama Kegiatan</th>
                                            <th class="text-center">Tanggal</th>
                                            <th class="text-center">Jenis Kegiatan</th>
                                            <th class="text-center">Jabatan</th>
                                            <th class="text-center">Poin</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($dosenKegiatan[$dosen->id_user] as $kegiatan)
                                            <tr>
                                                <td class="text-center">{{ $kegiatan->nama_kegiatan }}</td>
                                                <td class="text-center">{{ $kegiatan->tanggal_acara }}</td>
                                                <td class="text-center">{{ $kegiatan->jenis_kegiatan }}</td>
                                                <td class="text-center">{{ $kegiatan->jabatan_nama }}</td>
                                                <td class="text-center">{{ $kegiatan->poin }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                Tidak ada kegiatan
                            @endif
                        </td>
                        <td class="text-center">{{ $dosen->total_kegiatan }}</td>
                        <td class="text-center">{{ $dosen->total_poin }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection