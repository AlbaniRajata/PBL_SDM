<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserModel;
use App\Models\KegiatanModel;
use Illuminate\Support\Facades\DB;

class DashboardAdminController extends Controller
{
    public function getTotalDosen()
    {
        try {
            $total = UserModel::where('level', 'dosen')->count();
            
            return response()->json([
                'status' => true,
                'message' => 'Total dosen berhasil diambil',
                'data' => $total
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengambil total dosen: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getTotalKegiatanJTI()
    {
        try {
            $total = KegiatanModel::where('jenis_kegiatan', 'Kegiatan JTI')->count();
            
            return response()->json([
                'status' => true,
                'message' => 'Total kegiatan JTI berhasil diambil',
                'data' => $total
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengambil total kegiatan JTI: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getTotalKegiatanNonJTI()
    {
        try {
            $total = KegiatanModel::where('jenis_kegiatan', '!=', 'Kegiatan JTI')->count();
            
            return response()->json([
                'status' => true,
                'message' => 'Total kegiatan non JTI berhasil diambil',
                'data' => $total
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengambil total kegiatan non JTI: ' . $e->getMessage()
            ], 500);
        }
    }
}