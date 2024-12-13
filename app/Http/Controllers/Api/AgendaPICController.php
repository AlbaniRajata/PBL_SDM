<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\KegiatanModel;
use App\Models\AnggotaModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgendaPICController extends Controller
{
    public function index()
    {
        $user_id = Auth::id();
        
        $kegiatan = KegiatanModel::whereHas('anggota', function($query) use ($user_id) {
            $query->where('id_user', $user_id)
                  ->whereHas('jabatan', function($q) {$q->where('jabatan_nama', 'pic');});
        })
        ->with([
            'anggota.user:id_user,nama',
            'anggota.jabatan',
            'agenda.agendaAnggota',
            'dokumen'
        ])
        ->orderBy('tanggal_mulai', 'desc')
        ->get();

        return response()->json([
            'status' => 'success',
            'data' => $kegiatan
        ]);
    }

    public function show($id_kegiatan)
    {
        $user_id = Auth::id();
        
        $kegiatan = KegiatanModel::with([
            'anggota.user:id_user,nama',
            'anggota.jabatan',
            'agenda.agendaAnggota',
            'dokumen'
        ])
        ->findOrFail($id_kegiatan);

        $isPIC = $kegiatan->anggota()
            ->where('id_user', $user_id)
            ->whereHas('jabatan', function($q) {$q->where('jabatan_nama', 'pic');})->exists();

        if (!$isPIC) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized access'
            ], 403);
        }

        return response()->json([
            'status' => 'success',
            'data' => $kegiatan
        ]);
    }
}