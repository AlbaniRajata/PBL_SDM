<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Data Pengguna</title>
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
    <h3 class="text-center">LAPORAN DATA PENGGUNA</h3>
    <table class="border-all">
        <thead>
            <tr>
                <th>ID Pengguna</th>
                <th>Username</th>
                <th>Nama</th>
                <th>Email</th>
                <th>NIP</th>
                <th>Level</th>
                <th>Poin</th>
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
                    <td>{{ $user->poin }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>