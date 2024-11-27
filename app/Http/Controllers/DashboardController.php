<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Selamat datang',
            'list' => ['Home', 'Dashboard'],
        ];

        // Gunakan kolom `jenis_kegiatan` untuk filter
        $jumlahDosen = DB::table('t_user')->where('level', 'dosen')->count();
        $jumlahKegiatanJTI = DB::table('t_kegiatan')->where('jenis_kegiatan', 'Kegiatan JTI')->count();
        $jumlahKegiatanNonJTI = DB::table('t_kegiatan')->where('jenis_kegiatan', 'Kegiatan Non-JTI')->count();

        $activeMenu = 'dashboard';
        return view('welcome', [
            'breadcrumb' => $breadcrumb,
            'activeMenu' => $activeMenu,
            'jumlahDosen' => $jumlahDosen,
            'jumlahKegiatanJTI' => $jumlahKegiatanJTI,
            'jumlahKegiatanNonJTI' => $jumlahKegiatanNonJTI
        ]);
    }
}
