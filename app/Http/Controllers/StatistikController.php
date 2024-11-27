<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\StatistikModel;

class StatistikController extends Controller
{
    public function admin()
    {
        $breadcrumb = (object) [
            'title' => 'Home',
            'list' => ['Home', 'Statistik'],
        ];
        $activeMenu = 'statistik admin';

        $poinDosen = DB::table('t_user')
            ->leftJoin('t_anggota', 't_user.id_user', '=', 't_anggota.id_user')
            ->leftJoin('t_jabatan_kegiatan', 't_anggota.id_jabatan_kegiatan', '=', 't_jabatan_kegiatan.id_jabatan_kegiatan')
            ->select(
                't_user.nama',
                DB::raw('COUNT(t_anggota.id_kegiatan) as total_kegiatan'),
                DB::raw('COALESCE(SUM(t_jabatan_kegiatan.poin), 0) as total_poin')
            )
            ->where('t_user.level', 'dosen')
            ->groupBy('t_user.nama')
            ->get();

        return view('admin.statistik.index', compact('breadcrumb', 'activeMenu', 'poinDosen'));
    }

    public function pimpinan()
    {
        $breadcrumb = (object) [
            'title' => 'Home',
            'list' => ['Home', 'Statistik'],
        ];
        $activeMenu = 'statistik pimpinan';

        $poinDosen = DB::table('t_user')
            ->leftJoin('t_anggota', 't_user.id_user', '=', 't_anggota.id_user')
            ->leftJoin('t_jabatan_kegiatan', 't_anggota.id_jabatan_kegiatan', '=', 't_jabatan_kegiatan.id_jabatan_kegiatan')
            ->select(
                't_user.nama',
                DB::raw('COUNT(t_anggota.id_kegiatan) as total_kegiatan'),
                DB::raw('COALESCE(SUM(t_jabatan_kegiatan.poin), 0) as total_poin')
            )
            ->where('t_user.level', 'dosen')
            ->groupBy('t_user.nama')
            ->get();

        return view('pimpinan.statistik.index', compact('breadcrumb', 'activeMenu', 'poinDosen'));
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
