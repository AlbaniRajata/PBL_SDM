<!DOCTYPE html>
<html>
<head>
    <title>Poin Dosen</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
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
    <h2>Poin Dosen</h2>
    <table>
        <thead>
            <tr>
                <th>Nama Kegiatan</th>
                <th>Nama Dosen</th>
                <th>Poin</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($poinDosen as $poin)
                <tr>
                    <td>{{ $poin->kegiatan->judul_kegiatan }}</td>
                    <td>{{ $poin->dosen->nama }}</td>
                    <td>{{ $poin->poin }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>