<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class StatistikController extends Controller
{
    public function admin()
    {
        $breadcrumb = (object) [
            'title' => 'Home',
            'list' => ['Home', 'Statistik Admin'],
        ];
        $activeMenu = 'statistik admin';

        $poinDosen = DB::table('t_kegiatan')
            ->join('t_anggota', 't_kegiatan.id_kegiatan', '=', 't_anggota.id_kegiatan')
            ->join('t_user', 't_anggota.id_user', '=', 't_user.id_user')
            ->join('t_poin', 't_kegiatan.id_kegiatan', '=', 't_poin.id_kegiatan')
            ->select('t_kegiatan.nama_kegiatan', 't_user.nama', DB::raw('SUM(t_poin.poin) as total_poin'))
            ->groupBy('t_kegiatan.nama_kegiatan', 't_user.nama')
            ->get();
        return view('admin.statistik.index', compact('breadcrumb', 'activeMenu', 'poinDosen'));
    }

    public function pimpinan()
    {
        $breadcrumb = (object) [
            'title' => 'Home',
            'list' => ['Home', 'Statistik Pimpinan'],
        ];
        $activeMenu = 'statistik pimpinan';
        return view('pimpinan.statistik.index', ['breadcrumb' => $breadcrumb, 'activeMenu' => $activeMenu]);
    }

    public function dosenPIC()
    {
        $breadcrumb = (object) [
            'title' => 'Home',
            'list' => ['Home', 'Statistik DosenPIC'],
        ];
        $activeMenu = 'statistik pic';
        return view('dosenPIC.statistik.index', ['breadcrumb' => $breadcrumb, 'activeMenu' => $activeMenu]);
    }

    public function dosenAnggota()
    {
        $breadcrumb = (object) [
            'title' => 'Home',
            'list' => ['Home', 'Statistik Dosen Anggota'],
        ];
        $activeMenu = 'statistik anggota';
        return view('dosenAnggota.statistik.index', ['breadcrumb' => $breadcrumb, 'activeMenu' => $activeMenu]);
    }
}