<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KegiatanModel;
use Illuminate\Support\Facades\Auth;

class DashboardDosenController extends Controller
{
    public function getTotalKegiatan()
    {
        try {
            $userId = Auth::id();
            if (!$userId) {
                return response()->json([
                    'status' => false,
                    'message' => 'User tidak terautentikasi'
                ], 401);
            }

            // Mengambil total kegiatan JTI
            $totalKegiatanJTI = KegiatanModel::whereHas('anggota', function($query) use ($userId) {
                $query->where('id_user', $userId);
            })
            ->where('jenis_kegiatan', 'Kegiatan JTI')
            ->count();

            // Mengambil total kegiatan Non-JTI
            $totalKegiatanNonJTI = KegiatanModel::whereHas('anggota', function($query) use ($userId) {
                $query->where('id_user', $userId);
            })
            ->where('jenis_kegiatan', 'Kegiatan Non-JTI')
            ->count();

            // Total seluruh kegiatan
            $totalKegiatan = $totalKegiatanJTI + $totalKegiatanNonJTI;

            return response()->json([
                'status' => true,
                'message' => 'Data total kegiatan berhasil diambil',
                'data' => [
                    'total_kegiatan' => $totalKegiatan,
                    'total_kegiatan_jti' => $totalKegiatanJTI,
                    'total_kegiatan_non_jti' => $totalKegiatanNonJTI
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengambil data total kegiatan: ' . $e->getMessage()
            ], 500);
        }
    }
}