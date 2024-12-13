<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AnggotaModel;
use Illuminate\Support\Facades\Auth;

class DosenController extends Controller
{
    public function getJabatanKegiatan(Request $request)
    {
        $idUser = $request->input('id_user');
        
        $jabatanKegiatan = AnggotaModel::where('id_user', $idUser)
            ->with('jabatan')
            ->get()
            ->map(function ($anggota) {
                return [
                    'id_kegiatan' => $anggota->id_kegiatan,
                    'jabatan' => $anggota->jabatan->nama_jabatan,
                ];
            });

        return response()->json([
            'jabatan_kegiatan' => $jabatanKegiatan,
        ]);
    }

    public function getNotifikasi(Request $request)
    {
        try {
            $userId = Auth::id();

            $notifikasi = AnggotaModel::with(['kegiatan', 'jabatan'])
                ->where('id_user', $userId)
                ->orderBy('created_at', 'desc')
                ->get();

            $data = [];
            foreach ($notifikasi as $item) {
                if ($item->kegiatan) {
                    $data[] = [
                        'id_anggota' => $item->id_anggota,
                        'id_kegiatan' => $item->kegiatan->id_kegiatan,
                        'nama_kegiatan' => $item->kegiatan->nama_kegiatan,
                        'jabatan' => $item->jabatan->jabatan_nama,
                        'tanggal_acara' => $item->kegiatan->tanggal_acara,
                        'created_at' => $item->created_at
                    ];
                }
            }

            return response()->json([
                'status' => 'success',
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil data notifikasi: ' . $e->getMessage()
            ], 500);
        }
    }
}