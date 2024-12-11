<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\JabatanKegiatanModel;
use App\Models\KegiatanModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Carbon\Carbon;

class KegiatanPimpinanController extends Controller
{
    public function index()
    {
        try {
            $kegiatan = KegiatanModel::with(['anggota.user', 'anggota.jabatan'])->get();
            
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

    public function show($id)
    {
        try {
            $kegiatan = KegiatanModel::with(['anggota.user', 'anggota.jabatan'])->findOrFail($id);
            
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

    public function getDosen()
    {
        try {
            $dosen = UserModel::where('level', 'dosen')->get();
            
            return response()->json([
                'status' => true,
                'message' => 'Data dosen berhasil diambil',
                'data' => $dosen
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengambil data dosen: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getJabatan()
    {
        try {
            $jabatan = JabatanKegiatanModel::all();
            
            return response()->json([
                'status' => true,
                'message' => 'Data jabatan berhasil diambil',
                'data' => $jabatan
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengambil data jabatan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getKegiatanKalender()
    {
        try {
            $kegiatan = KegiatanModel::select(
                'id_kegiatan',
                'nama_kegiatan',
                'tanggal_acara'
            )
            ->whereNotNull('tanggal_acara')
            ->orderBy('tanggal_acara')
            ->get();
            
            $formattedKegiatan = $kegiatan->map(function ($item) {
                $tanggalAcara = Carbon::parse($item->tanggal_acara)->format('d-m-Y');
                
                return [
                    'id_kegiatan' => $item->id_kegiatan,
                    'nama_kegiatan' => $item->nama_kegiatan,
                    'tanggal_acara' => $tanggalAcara,
                ];
            });

            return response()->json([
                'status' => true,
                'message' => 'Data kegiatan kalender berhasil diambil',
                'data' => $formattedKegiatan
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengambil data kegiatan kalender: ' . $e->getMessage()
            ], 500);
        }
    }
}