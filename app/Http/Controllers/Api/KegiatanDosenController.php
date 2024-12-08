<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KegiatanModel;
use App\Models\AnggotaModel;
use Illuminate\Support\Facades\Auth;

class KegiatanDosenController extends Controller
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
            ->select(
                'id_kegiatan', 
                'nama_kegiatan', 
                'tanggal_mulai',
                'tanggal_selesai',
                'tanggal_acara',
                'tempat_kegiatan',
                'jenis_kegiatan',
                'progress'
            )
            ->get();

            return response()->json([
                'status' => true,
                'message' => 'Data kegiatan berhasil diambil',
                'data' => $kegiatan
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengambil data kegiatan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function indexJTI()
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
            ->where('jenis_kegiatan', 'Kegiatan JTI')
            ->with(['anggota' => function($query) use ($userId) {
                $query->where('id_user', $userId)
                    ->with('jabatan:id_jabatan_kegiatan,jabatan_nama');
            }])
            ->select(
                'id_kegiatan',
                'nama_kegiatan', 
                'tanggal_mulai',
                'tanggal_selesai',
                'tanggal_acara',
                'tempat_kegiatan',
                'jenis_kegiatan'
            )
            ->get()
            ->map(function($kegiatan) {
                $jabatan = $kegiatan->anggota->first()->jabatan->jabatan_nama ?? '';
                
                $kegiatanArray = $kegiatan->toArray();
                $kegiatanArray['jabatan'] = $jabatan;
                
                unset($kegiatanArray['anggota']);
                
                return $kegiatanArray;
            });

            return response()->json([
                'status' => true,
                'message' => 'Data kegiatan JTI berhasil diambil',
                'data' => $kegiatan
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengambil data kegiatan JTI: ' . $e->getMessage()
            ], 500);
        }
    }

    public function indexNonJTI()
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
            ->where('jenis_kegiatan', 'Kegiatan Non-JTI')
            ->with(['anggota' => function($query) use ($userId) {
                $query->where('id_user', $userId)
                    ->with('jabatan:id_jabatan_kegiatan,jabatan_nama');
            }])
            ->select(
                'id_kegiatan',
                'nama_kegiatan', 
                'tanggal_mulai',
                'tanggal_selesai',
                'tanggal_acara',
                'tempat_kegiatan',
                'jenis_kegiatan'
            )
            ->get()
            ->map(function($kegiatan) {
                $jabatan = $kegiatan->anggota->first()->jabatan->jabatan_nama ?? '';
                
                $kegiatanArray = $kegiatan->toArray();
                $kegiatanArray['jabatan'] = $jabatan;
                
                unset($kegiatanArray['anggota']);
                
                return $kegiatanArray;
            });

            return response()->json([
                'status' => true,
                'message' => 'Data kegiatan Non JTI berhasil diambil',
                'data' => $kegiatan
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengambil data kegiatan Non JTI: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function show($id)
    {
        try {
            if (!is_numeric($id)) {
                return response()->json([
                    'status' => false,
                    'message' => 'ID kegiatan tidak valid'
                ], 400);
            }

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
            ->where('id_kegiatan', $id)
            ->with(['anggota.user:id_user,nama', 'anggota.jabatan:id_jabatan_kegiatan,jabatan_nama,poin'])
            ->select(
                'id_kegiatan',
                'nama_kegiatan',
                'deskripsi_kegiatan',
                'tanggal_mulai',
                'tanggal_selesai',
                'tanggal_acara',
                'tempat_kegiatan',
                'jenis_kegiatan'
            )
            ->first();

            if (!$kegiatan) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data kegiatan tidak ditemukan atau Anda tidak memiliki akses'
                ], 404);
            }

            $result = $kegiatan->toArray();
            
            $userAnggota = $kegiatan->anggota->where('id_user', $userId)->first();
            $result['jabatan'] = $userAnggota ? $userAnggota->jabatan->jabatan_nama : '';

            $result['anggota'] = $kegiatan->anggota->map(function($anggota) {
                return [
                    'id_anggota' => $anggota->id_anggota,
                    'id_user' => $anggota->id_user,
                    'nama' => $anggota->user->nama,
                    'jabatan' => $anggota->jabatan->jabatan_nama,
                    'poin' => $anggota->jabatan->poin
                ];
            })->values()->toArray();

            return response()->json([
                'status' => true,
                'message' => 'Detail kegiatan berhasil diambil',
                'data' => $result
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengambil detail kegiatan: ' . $e->getMessage()
            ], 500);
        }
    }
}