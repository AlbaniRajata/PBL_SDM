<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Data Kegiatan</title>
    <style>
        .border-all, .border-all th, .border-all td {
            border: 1px solid;
        }
        .logo-image {
            max-width: 100px; 
            max-height: 100px;
            width: auto;
            height: auto;
            object-fit: contain;
        }
        .text-center {
            text-align: center;
        }
        .font-11 {
            font-size: 11px;
        }
        .font-13 {
            font-size: 13px;
        }
        .font-10 {
            font-size: 10px;
        }
        .font-bold {
            font-weight: bold;
        }
        .mb-1 {
            margin-bottom: 1px;
        }
        .d-block {
            display: block;
        }
    </style>
</head>
<body>
    <table class="border-bottom-header">
        <tr>
            <td width="15%" class="text-center"><img class="logo-image" src="{{ asset('polinema.png') }}"></td>
            <td width="85%">
                <span class="text-center d-block font-11 font-bold mb-1">KEMENTERIAN PENDIDIKAN, KEBUDAYAAN, RISET, DAN TEKNOLOGI</span>
                <span class="text-center d-block font-13 font-bold mb-1">POLITEKNIK NEGERI MALANG</span>
                <span class="text-center d-block font-10">JL, Soekarno-Hatta No.9 Malang 65141</span>
                <span class="text-center d-block font-10">Telepon (0341) 404424 Pes. 101-105 0341-404420, Fax. (0341) 404420</span>
                <span class="text-center d-block font-10">Laman: www.polinema.ac.id</span>
            </td>
        </tr>
    </table>
    <h3 class="text-center">LAPORAN DATA KEGIATAN</h3>
    <table class="border-all">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Kegiatan</th>
                <th>Deskripsi</th>
                <th>Tanggal Mulai</th>
                <th>Tanggal Selesai</th>
                <th>Tanggal Acara</th>
                <th>Tempat Kegiatan</th>
                <th>Jenis Kegiatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($kegiatan as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->nama_kegiatan }}</td>
                <td>{{ $item->deskripsi_kegiatan }}</td>
                <td>{{ $item->tanggal_mulai }}</td>
                <td>{{ $item->tanggal_selesai }}</td>
                <td>{{ $item->tanggal_acara }}</td>
                <td>{{ $item->tempat_kegiatan }}</td>
                <td>{{ $item->jenis_kegiatan }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>