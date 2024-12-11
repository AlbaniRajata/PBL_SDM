<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Poin Dosen</title>
    <style>
        body {
            font-family: "Times New Roman", Times, serif;
            margin: 6px 20px 5px 20px;
            line-height: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        td,
        th {
            padding: 4px 3px;
        }
        th {
            text-align: left;
        }
        .d-block {
            display: block;
        }
        img.image {
            width: auto;
            height: 80px;
            max-width: 150px;
            max-height: 150px;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .p-1 {
            padding: 5px 1px 5px 1px;
        }
        .font-10 {
            font-size: 10pt;
        }
        .font-11 {
            font-size: 11pt;
        }
        .font-12 {
            font-size: 12pt;
        }
        .font-13 {
            font-size: 13pt;
        }
        .border-bottom-header {
            border-bottom: 1px solid;
        }
        .border-all,
        .border-all th,
        .border-all td {
            border: 1px solid;
        }
        .font-bold {
            font-weight: bold;
        }
        .mb-1 {
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <table class="border-bottom-header">
        <tr>
            <td width="15%" class="text-center">
                <img class="image" src="{{ asset('polinema.png') }}"></td>
            <td width="85%">
                <span class="text-center d-block font-11 font-bold mb-1">KEMENTERIAN PENDIDIKAN, KEBUDAYAAN, RISET, DAN TEKNOLOGI</span>
                <span class="text-center d-block font-13 font-bold mb-1">POLITEKNIK NEGERI MALANG</span>
                <span class="text-center d-block font-10">Jl. Soekarno-Hatta No. 9 Malang 65141</span>
                <span class="text-center d-block font-10">Telepon (0341) 404424 Pes. 101-105, 0341-404420, Fax. (0341) 404420</span>
                <span class="text-center d-block font-10">Laman: www.polinema.ac.id</span>
            </td>
        </tr>
    </table>

    <h2 class="text-center">LAPORAN POIN KEGIATAN YANG DIIKUTI</h2>
    
    <p><strong>Nama Dosen:</strong> {{ $poinDosen->first()->nama ?? '-' }}</p>
    <table class="border-all">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Kegiatan</th>
                <th>Tanggal Acara</th>
                <th>Jenis Kegiatan</th>
                <th>Jabatan</th>
                <th>Poin</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($dosenKegiatan->first() as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->nama_kegiatan }}</td>
                    <td>{{ $item->tanggal_acara }}</td>
                    <td>{{ $item->jenis_kegiatan }}</td>
                    <td>{{ $item->jabatan_nama }}</td>
                    <td>{{ $item->poin }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Tidak ada data kegiatan</td>
                </tr>
            @endforelse
            <tr>
                <td colspan="5" class="font-bold text-right">Total Poin</td>
                <td class="font-bold">{{ $poinDosen->sum('total_poin') }}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>
