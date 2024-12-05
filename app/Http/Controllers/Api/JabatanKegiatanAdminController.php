<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\JabatanKegiatanModel;
use Illuminate\Http\Request;

class JabatanKegiatanAdminController extends Controller
{
    //Menampilkan daftar semua jabatan kegiatan
    public function index()
    {
        $jabatan = JabatanKegiatanModel::all();
        return response()->json([
            'status' => 'success',
            'data' => $jabatan
        ]);
    }

    //Menyimpan jabatan kegiatan baru
    public function store(Request $request)
    {
        $request->validate([
            'jabatan_nama' => 'required|string|max:255',
            'poin' => 'required|numeric|min:0.5|max:2'
        ]);

        $jabatan = JabatanKegiatanModel::create([
            'jabatan_nama' => $request->jabatan_nama,
            'poin' => $request->poin
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Jabatan kegiatan berhasil ditambahkan',
            'data' => $jabatan
        ], 201);
    }

    //Menampilkan detail jabatan kegiatan
    public function show($id)
    {
        $jabatan = JabatanKegiatanModel::find($id);
        
        if (!$jabatan) {
            return response()->json([
                'status' => 'error',
                'message' => 'Jabatan kegiatan tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $jabatan
        ]);
    }

    //Mengupdate jabatan kegiatan
    public function update(Request $request, $id)
    {
        $request->validate([
            'jabatan_nama' => 'required|string|max:255',
            'poin' => 'required|numeric'
        ]);

        $jabatan = JabatanKegiatanModel::find($id);

        if (!$jabatan) {
            return response()->json([
                'status' => 'error',
                'message' => 'Jabatan kegiatan tidak ditemukan'
            ], 404);
        }

        $jabatan->update([
            'jabatan_nama' => $request->jabatan_nama,
            'poin' => $request->poin
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Jabatan kegiatan berhasil diupdate',
            'data' => $jabatan
        ]);
    }

    //Menghapus jabatan kegiatan
    public function destroy($id)
    {
        $jabatan = JabatanKegiatanModel::find($id);

        if (!$jabatan) {
            return response()->json([
                'status' => 'error',
                'message' => 'Jabatan kegiatan tidak ditemukan'
            ], 404);
        }

        $jabatan->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Jabatan kegiatan berhasil dihapus'
        ]);
    }
}