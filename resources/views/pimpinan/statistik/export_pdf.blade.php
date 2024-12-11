<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Poin Dosen</title>
    <style>
        .border-all, .border-all th, .border-all td {
            border: 1px solid;
        }
        .image {
            max-width: 90px; 
            max-height: 90px;
            width: auto;
            height: auto;
            object-fit: contain;
        }
        .text-center {
            text-align: center;
        }
        .font-11 {
            font-size: 14px;
        }
        .font-13 {
            font-size: 16px;
        }
        .font-10 {
            font-size: 13px;
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <!-- Header Section -->
    <table class="border-bottom-header">
        <tr>
            <td width="15%" class="text-center"><img class="image" id="image"
                src="{{ public_path('polinema.png') }}"></td>
            </td>
            <td width="85%">
                <span class="text-center d-block font-11 font-bold mb-1">KEMENTERIAN PENDIDIKAN, KEBUDAYAAN, RISET, DAN TEKNOLOGI</span>
                <span class="text-center d-block font-13 font-bold mb-1">POLITEKNIK NEGERI MALANG</span>
                <span class="text-center d-block font-10">JL, Soekarno-Hatta No.9 Malang 65141</span>
                <span class="text-center d-block font-10">Telepon (0341) 404424 Pes. 101-105 0341-404420, Fax. (0341) 404420</span>
                <span class="text-center d-block font-10">Laman: www.polinema.ac.id</span>
            </td>
        </tr>
    </table>

<body>
    <h2 class="text-center font-bold">Laporan Poin Dosen</h2>
    <table class="border-all">
        <thead>
            <tr>
                <th>Nama Dosen</th>
                <th class="text-center">Jumlah Kegiatan</th>
                <th class="text-center">Poin</th>
                <th class="text-center">Detail Kegiatan</th>
            </tr>
        </thead>
        <tbody>
            @php 
                $totalKegiatan = 0; 
                $totalPoin = 0; 
            @endphp
            @foreach ($poinDosen as $dosen)
                <tr>
                    <td>{{ $dosen->nama }}</td>
                    <td class="text-center">{{ $dosen->total_kegiatan }}</td>
                    <td class="text-center">{{ $dosen->total_poin }}</td>
                    <td>
                        @if (isset($dosenKegiatan[$dosen->id_user]))
                            <div class="detail-activities">
                                @foreach ($dosenKegiatan[$dosen->id_user] as $kegiatan)
                                    <div>
                                        {{ $kegiatan->nama_kegiatan }} 
                                        ({{ $kegiatan->jenis_kegiatan }}) - 
                                        {{ $kegiatan->tanggal_acara }} 
                                        [{{ $kegiatan->poin }} poin]
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </td>
                </tr>
                @php 
                    $totalKegiatan += $dosen->total_kegiatan; 
                    $totalPoin += $dosen->total_poin; 
                @endphp
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th class="text-center">Total</th>
                <th class="text-center">{{ $totalKegiatan }}</th>
                <th class="text-center">{{ $totalPoin }}</th>
                <th></th>
            </tr>
        </tfoot>
    </table>
</body>
</html>