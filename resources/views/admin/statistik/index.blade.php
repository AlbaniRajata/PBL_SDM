@extends('layouts.template')

@section('content')
<div class="card shadow-lg rounded-3">
    <div class="card-header">
        <h3 class="card-title text-dark">Statistik Dosen</h3>
        <div class="card-tools">
            <a href="{{ url('/admin/statistik/export_excel') }}" class="btn btn-sm btn-outline-success btn-hover">
                <i class="fa-solid fa-file-excel"></i> Ekspor Excel
            </a>
            <a href="{{ url('/admin/statistik/export_pdf') }}" class="btn btn-sm btn-outline-warning btn-hover">
                <i class="fa-solid fa-file-pdf"></i> Ekspor PDF
            </a>
        </div>
    </div>

    <div class="card-body p-4">
        <!-- Table with minimalistic and elegant design -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped custom-table">
                <thead class="thead-custom">
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Nama Dosen</th>
                        <th class="text-center">Detail Kegiatan</th>
                        <th class="text-center">Total Kegiatan</th>
                        <th class="text-center">Total Poin</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($poinDosen as $index => $dosen)
                        <tr class="table-hover">
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td class="text-center">{{ $dosen->nama }}</td>
                            <td>
                                @if (isset($dosenKegiatan[$dosen->id_user]))
                                    <div class="table-responsive">
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
                                    </div>
                                @else
                                    <p class="text-center text-muted">Tidak ada kegiatan</p>
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
</div>
@endsection

@push('css')
<style>
    /* Global Styling */
    body {
        font-family: 'Inter', sans-serif;
        background-color: #f8f9fa;
    }

    /* Card Styling */
    .card {
        border-radius: 10px;
        background-color: #fff;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .card-header {
        background-color: transparent;
        border-bottom: 2px solid #eee;
        padding: 15px 20px;
        color: #333;
    }

    .card-header .card-title {
        font-weight: 600;
        font-size: 1.2rem;
        color: #333;
    }

    /* Table Styling */
    .custom-table {
        border-radius: 8px;
        overflow: hidden;
        background-color: #fff;
    }

    .thead-custom {
        background-color: #f4f6f9;
        color: #333;
        font-weight: 600;
    }

    .table th, .table td {
        padding: 12px 16px;
        font-size: 14px;
        vertical-align: middle;
    }

    /* Hover effect for rows */
    .table-hover tbody tr:hover {
        background-color: #f7f7f7;
        transform: scale(1.02);
        transition: all 0.2s ease;
    }

    /* Styling for export buttons */
    .btn-outline-success, .btn-outline-warning {
        border-radius: 20px;
        font-size: 14px;
        font-weight: 600;
        padding: 6px 12px;
        transition: transform 0.3s, background-color 0.3s;
    }

    .btn-outline-success:hover, .btn-outline-warning:hover {
        background-color: #f0f0f0;
        transform: scale(1.05);
    }

    /* Styling for empty state */
    .text-muted {
        font-style: italic;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .table th, .table td {
            font-size: 12px;
            padding: 8px;
        }

        .card-header .card-title {
            font-size: 1rem;
        }

        .btn-outline-success, .btn-outline-warning {
            font-size: 12px;
        }
    }
</style>
@endpush
