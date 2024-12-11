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

    public function indexPIC()
    {
        try {
            $userId = Auth::id();
            if (!$userId) {
                return response()->json([
                    'status' => false,
                    'message' => 'User tidak terautentikasi'
                ], 401);
            }

            $totalKegiatanJti = KegiatanModel::whereHas('anggota', function($query) use ($userId) {
                $query->where('id_user', $userId)
                    ->whereHas('jabatan', function($query) {
                        $query->where('jabatan_nama', 'PIC');
                    });
            })
            ->where('jenis_kegiatan', 'Kegiatan JTI')
            ->count();

            $totalKegiatanNonJti = KegiatanModel::whereHas('anggota', function($query) use ($userId) {
                $query->where('id_user', $userId)
                    ->whereHas('jabatan', function($query) {
                        $query->where('jabatan_nama', 'PIC');
                    });
            })
            ->where('jenis_kegiatan', 'Kegiatan Non-JTI')
            ->count();

            return response()->json([
                'status' => true,
                'message' => 'Data dashboard PIC berhasil diambil',
                'data' => [
                    'total_kegiatan_jti' => $totalKegiatanJti,
                    'total_kegiatan_non_jti' => $totalKegiatanNonJti
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengambil data dashboard PIC: ' . $e->getMessage()
            ], 500);
        }
    }

    public function indexAnggota()
    {
        try {
            $userId = Auth::id();
            if (!$userId) {
                return response()->json([
                    'status' => false,
                    'message' => 'User tidak terautentikasi'
                ], 401);
            }

            $totalKegiatanJti = KegiatanModel::whereHas('anggota', function($query) use ($userId) {
                $query->where('id_user', $userId)
                    ->whereHas('jabatan', function($query) {
                        $query->where('jabatan_nama', '!=', 'pic');
                    });
            })
            ->where('jenis_kegiatan', 'Kegiatan JTI')
            ->count();

            $totalKegiatanNonJti = KegiatanModel::whereHas('anggota', function($query) use ($userId) {
                $query->where('id_user', $userId)
                    ->whereHas('jabatan', function($query) {
                        $query->where('jabatan_nama', '!=', 'pic');
                    });
            })
            ->where('jenis_kegiatan', 'Kegiatan Non-JTI')
            ->count();

            return response()->json([
                'status' => true,
                'message' => 'Data dashboard Anggota berhasil diambil',
                'data' => [
                    'total_kegiatan_jti' => $totalKegiatanJti,
                    'total_kegiatan_non_jti' => $totalKegiatanNonJti
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengambil data dashboard Anggota: ' . $e->getMessage()
            ], 500);
        }
    }
}