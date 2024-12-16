<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class UserPimpinanController extends Controller
{
    public function getAllDosen()
    {
        try {
            $dosen = UserModel::where('level', 'dosen')
            ->select('m_user.id_user', 'm_user.username', 'm_user.nama', 
                    'm_user.email', 'm_user.NIP', 'm_user.tanggal_lahir',
                    DB::raw('COUNT(t_anggota.id_kegiatan) as total_kegiatan'),
                    DB::raw('COALESCE(SUM(t_jabatan_kegiatan.poin), 0) as total_poin'))
            ->leftJoin('t_anggota', 'm_user.id_user', '=', 't_anggota.id_user')
            ->leftJoin('t_jabatan_kegiatan', 't_anggota.id_jabatan_kegiatan', '=', 't_jabatan_kegiatan.id_jabatan_kegiatan')
            ->groupBy('m_user.id_user', 'm_user.username', 'm_user.nama', 
                    'm_user.email', 'm_user.NIP', 'm_user.tanggal_lahir')
            ->get();

            if ($dosen->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data dosen tidak ditemukan',
                    'data' => null
                ], 404);
            }

            return response()->json([
                'status' => true,
                'message' => 'Berhasil mengambil data dosen',
                'data' => $dosen
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    public function getDosenDetail($id)
    {
        try {
            $dosen = UserModel::where('m_user.level', 'dosen')
            ->where('m_user.id_user', $id)
            ->select('m_user.id_user', 'm_user.username', 'm_user.nama', 
                    'm_user.email', 'm_user.NIP', 'm_user.tanggal_lahir',
                    DB::raw('COUNT(DISTINCT t_anggota.id_kegiatan) as total_kegiatan'),
                    DB::raw('COALESCE(SUM(t_jabatan_kegiatan.poin), 0) as total_poin'))
            ->leftJoin('t_anggota', 'm_user.id_user', '=', 't_anggota.id_user')
            ->leftJoin('t_jabatan_kegiatan', 't_anggota.id_jabatan_kegiatan', '=', 't_jabatan_kegiatan.id_jabatan_kegiatan')
            ->groupBy('m_user.id_user', 'm_user.username', 'm_user.nama', 'm_user.email', 'm_user.NIP', 'm_user.tanggal_lahir')
            ->first();

            if (!$dosen) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data dosen tidak ditemukan',
                    'data' => null
                ], 404);
            }

            $jabatanKegiatan = DB::table('t_anggota')
                ->join('t_jabatan_kegiatan', 't_anggota.id_jabatan_kegiatan', '=', 't_jabatan_kegiatan.id_jabatan_kegiatan')
                ->join('t_kegiatan', 't_anggota.id_kegiatan', '=', 't_kegiatan.id_kegiatan')
                ->where('t_anggota.id_user', $id)
                ->select('t_jabatan_kegiatan.jabatan_nama', 't_kegiatan.nama_kegiatan')
                ->get();

            $responseData = $dosen->toArray();
            $responseData['jabatan'] = $jabatanKegiatan->pluck('jabatan_nama')->unique()->values()->toArray();
            $responseData['kegiatan'] = $jabatanKegiatan->pluck('nama_kegiatan')->unique()->values()->toArray();

            return response()->json([
                'status' => true,
                'message' => 'Berhasil mengambil detail dosen',
                'data' => $responseData
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    public function searchDosen(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'keyword' => 'required|string|min:3'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $keyword = $request->keyword;
            
            $dosen = UserModel::where('level', 'dosen')
            ->where(function($query) use ($keyword) {
                $query->where('nama', 'LIKE', "%{$keyword}%")
                        ->orWhere('NIP', 'LIKE', "%{$keyword}%");
            })
            ->select('m_user.id_user', 'm_user.username', 'm_user.nama', 
                    'm_user.email', 'm_user.NIP', 'm_user.tanggal_lahir',
                    DB::raw('COUNT(t_anggota.id_kegiatan) as total_kegiatan'),
                    DB::raw('COALESCE(SUM(t_jabatan_kegiatan.poin), 0) as total_poin'))
            ->leftJoin('t_anggota', 'm_user.id_user', '=', 't_anggota.id_user')
            ->leftJoin('t_jabatan_kegiatan', 't_anggota.id_jabatan_kegiatan', '=', 't_jabatan_kegiatan.id_jabatan_kegiatan')
            ->groupBy('m_user.id_user', 'm_user.username', 'm_user.nama', 
                    'm_user.email', 'm_user.NIP', 'm_user.tanggal_lahir')
            ->get();

            if ($dosen->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data dosen tidak ditemukan',
                    'data' => null
                ], 404);
            }

            return response()->json([
                'status' => true,
                'message' => 'Berhasil mengambil data dosen',
                'data' => $dosen
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }
}