<!DOCTYPE html>
<html>
<head>
    <title>Draft Surat Tugas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .title {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .table th, .table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        .table th {
            background-color: #f2f2f2;
        }
        .anggota {
            margin-top: 20px;
        }
        .anggota-title {
            font-weight: bold;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="title">Draft Surat Tugas</div>
    <table class="table">
        <tr>
            <th>Nama Kegiatan</th>
            <td>{{ $kegiatan->nama_kegiatan }}</td>
        </tr>
        <tr>
            <th>Deskripsi Kegiatan</th>
            <td>{{ $kegiatan->deskripsi_kegiatan }}</td>
        </tr>
        <tr>
            <th>Tanggal Mulai</th>
            <td>{{ $kegiatan->tanggal_mulai }}</td>
        </tr>
        <tr>
            <th>Tanggal Selesai</th>
            <td>{{ $kegiatan->tanggal_selesai }}</td>
        </tr>
        <tr>
            <th>Tanggal Acara</th>
            <td>{{ $kegiatan->tanggal_acara }}</td>
        </tr>
        <tr>
            <th>Tempat Kegiatan</th>
            <td>{{ $kegiatan->tempat_kegiatan }}</td>
        </tr>
    </table>
    <div class="anggota">
        <div class="anggota-title">Anggota:</div>
        <ul>
            @foreach ($anggota as $member)
                <li>{{ $member->user->nama }} ({{ $member->jabatan->jabatan_nama }})</li>
            @endforeach
        </ul>
    </div>
</body>
</html>