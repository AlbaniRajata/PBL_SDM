<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AnggotaModel;

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
}