<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserModel;
use App\Models\AnggotaModel;
use App\Models\JabatanKegiatanModel;
use Illuminate\Support\Facades\DB;

class StatistikAdminController extends Controller
{
    public function index()
    {
        $statistik = UserModel::select('t_user.nama', 
        DB::raw('COUNT(t_anggota.id_kegiatan) as total_kegiatan'),
        DB::raw('SUM(t_jabatan_kegiatan.poin) as total_poin'))
        ->leftJoin('t_anggota', 't_user.id_user', '=', 't_anggota.id_user')
        ->leftJoin('t_jabatan_kegiatan', 't_anggota.id_jabatan_kegiatan', '=', 't_jabatan_kegiatan.id_jabatan_kegiatan')
        ->where('t_user.level', '=', 'dosen')
        ->groupBy('t_user.id_user', 't_user.nama')
        ->get();

        return response()->json($statistik);
    }
}