<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Data Pengguna</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
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
            font-size: 15px;
        }
        .font-13 {
            font-size: 14px;
        }
        .font-10 {
            font-size: 12px;
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
        .table-container {
            width: 100%;
            margin-top: 20px;
        }
        th, td {
            padding: 8px;
            text-align: center;
        }
        h3 {
            margin-top: 20px;
            margin-bottom: 20px;
        }
        .header-container {
            text-align: center;
        }
        .header-table {
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="header-container">
        <table class="header-table">
            <tr>
                <td width="15%" class="text-center">
                    <img class="logo-image" src="{{ asset('polinema.png') }}">
                </td>
                <td width="85%">
                    <span class="font-11 font-bold d-block mb-1">KEMENTERIAN PENDIDIKAN, KEBUDAYAAN, RISET, DAN TEKNOLOGI</span>
                    <span class="font-13 font-bold d-block mb-1">POLITEKNIK NEGERI MALANG</span>
                    <span class="font-10 d-block">JL, Soekarno-Hatta No.9 Malang 65141</span>
                    <span class="font-10 d-block">Telepon (0341) 404424 Pes. 101-105 0341-404420, Fax. (0341) 404420</span>
                    <span class="font-10 d-block">Laman: www.polinema.ac.id</span>
                </td>
            </tr>
        </table>
    </div>
    <h3 class="text-center">LAPORAN DATA PENGGUNA</h3>
    <div class="table-container">
        <table class="border-all" width="100%">
            <thead>
                <tr>
                    <th>ID Pengguna</th>
                    <th>Username</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>NIP</th>
                    <th>Level</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($user as $key => $user)
                    <tr>
                        <td>{{ $user->id_user }}</td>
                        <td>{{ $user->username }}</td>
                        <td>{{ $user->nama }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->NIP }}</td>
                        <td>{{ $user->level }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
