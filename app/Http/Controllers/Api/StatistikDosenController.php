<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KegiatanModel;
use App\Models\AnggotaModel;
use App\Models\JabatanKegiatanModel;
use Illuminate\Support\Facades\Auth;

class StatistikDosenController extends Controller
{

    public function index()
    {
        try {
            $userId = Auth::id();
            if (!$userId) {
                return response()->json([
                    'status' => false,
                    'message' => 'User tidak terautentikasi'
                ], 401);
            }

            $kegiatan = KegiatanModel::whereHas('anggota', function($query) use ($userId) {
                $query->where('id_user', $userId);
            })
            ->join('t_anggota', 't_kegiatan.id_kegiatan', '=', 't_anggota.id_kegiatan')
            ->join('t_jabatan_kegiatan', 't_anggota.id_jabatan_kegiatan', '=', 't_jabatan_kegiatan.id_jabatan_kegiatan')
            ->select(
                't_kegiatan.nama_kegiatan', 
                't_jabatan_kegiatan.jabatan_nama as jabatan',
                't_jabatan_kegiatan.poin',
                't_kegiatan.tanggal_acara'
            )
            ->where('t_anggota.id_user', $userId)
            ->get();

            $totalPoin = $kegiatan->sum('poin');

            $statistik = $kegiatan->map(function($item) {
                return [
                    'nama_kegiatan' => $item->nama_kegiatan,
                    'jabatan' => $item->jabatan,
                    'poin' => $item->poin,
                    'tanggal_acara' => $item->tanggal_acara
                ];
            });

            return response()->json([
                'status' => true,
                'message' => 'Data statistik dosen berhasil diambil',
                'data' => [
                    'statistik' => $statistik,
                    'total_poin' => $totalPoin
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengambil data statistik dosen: ' . $e->getMessage()
            ], 500);
        }
    }
}