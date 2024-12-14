@extends('layouts.template')

@section('content')
    <div class="container-fluid">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <div class="card-tools">
                    <a href="{{ url('/dosen/statistik/export_excel') }}" class="btn btn-sm btn-outline-success btn-hover"><i class="fa fa-file-excel"></i> Export Statistik (Excel)</a>
                    <a href="{{ url('/dosen/statistik/export_pdf') }}" class="btn btn-sm btn-outline-warning btn-hover"><i class="fa fa-file-pdf"></i> Export Statistik (PDF)
                    </a>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped table-hover table-sm" id="table_poin_dosen">
                    <thead>
                        <tr class="text-center">
                            <th>No</th>
                            <th>Nama Kegiatan</th>
                            <th>Jabatan</th>
                            <th>Poin</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $totalPoin = 0; $no = 1; @endphp
                        @foreach ($poinDosen as $poin)
                            <tr class="text-center">
                                <td>{{ $no++ }}</td>
                                <td>{{ $poin->judul_kegiatan }}</td>
                                <td>{{ $poin->jabatan }}</td>
                                <td>{{ $poin->poin }}</td>
                            </tr>
                            @php $totalPoin += $poin->poin; @endphp
                        @endforeach
                    </tbody>
                    <tfoot>
                    <tr class="text-center">
                            <th colspan="3" class="text-center">Total Poin</th>
                            <th>{{ $totalPoin }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection
