@extends('layouts.template')

@section('content')
    <div class="container-fluid">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">Daftar Poin Dosen</h3>
                <div class="card-tools">
                    <a href="{{ url('/admin/statistik/export_excel') }}" class="btn btn-sm btn-success mt-1">
                        <i class="fa-solid fa-file-excel"></i> Ekspor Excel</a>
                    <a href="{{ url('/admin/statistik/export_pdf') }}" class="btn btn-sm btn-warning mt-1">
                        <i class="fa-solid fa-file-pdf"></i> Ekspor PDF
                    </a>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped table-hover table-sm" id="table_poin_dosen">
                    <thead>
                        <tr>
                            <th>Nama Dosen</th>
                            <th>Total Kegiatan</th>
                            <th>Total Poin</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($poinDosen as $poin)
                            <tr>
                                <td>{{ $poin->nama }}</td>
                                <td>{{ $poin->total_kegiatan }}</td>
                                <td>{{ $poin->total_poin }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection