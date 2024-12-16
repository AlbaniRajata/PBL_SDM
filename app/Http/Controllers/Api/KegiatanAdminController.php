<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\KegiatanModel;
use App\Models\AnggotaModel;
use App\Models\UserModel;
use App\Models\JabatanKegiatanModel;
use App\Models\AgendaAnggotaModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class KegiatanAdminController extends Controller
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

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nama_kegiatan' => 'required|string|max:255',
                'deskripsi_kegiatan' => 'required|string',
                'tanggal_mulai' => 'required|date',
                'tanggal_selesai' => 'required|date',
                'tanggal_acara' => 'required|date',
                'tempat_kegiatan' => 'required|string',
                'jenis_kegiatan' => 'required|string',
                'anggota' => 'required|array',
                'anggota.*.id_user' => 'required|exists:m_user,id_user',
                'anggota.*.id_jabatan_kegiatan' => 'required|exists:t_jabatan_kegiatan,id_jabatan_kegiatan'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();

            $kegiatan = KegiatanModel::create($request->except('anggota'));

            foreach ($request->anggota as $anggota) {
                AnggotaModel::create([
                    'id_kegiatan' => $kegiatan->id_kegiatan,
                    'id_user' => $anggota['id_user'],
                    'id_jabatan_kegiatan' => $anggota['id_jabatan_kegiatan']
                ]);
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Kegiatan berhasil ditambahkan',
                'data' => $kegiatan->load('anggota.user', 'anggota.jabatan')
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Gagal menambahkan kegiatan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nama_kegiatan' => 'required|string|max:255',
                'deskripsi_kegiatan' => 'required|string',
                'tanggal_mulai' => 'required|date',
                'tanggal_selesai' => 'required|date',
                'tanggal_acara' => 'required|date',
                'tempat_kegiatan' => 'required|string',
                'jenis_kegiatan' => 'required|string',
                'anggota' => 'required|array',
                'anggota.*.id_user' => 'required|exists:m_user,id_user',
                'anggota.*.id_jabatan_kegiatan' => 'required|exists:t_jabatan_kegiatan,id_jabatan_kegiatan'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();

            $kegiatan = KegiatanModel::find($id);
            if (!$kegiatan) {
                return response()->json([
                    'status' => false,
                    'message' => 'Kegiatan tidak ditemukan'
                ], 404);
            }

            $kegiatan->update($request->except('anggota'));

            // Delete existing anggota
            AnggotaModel::where('id_kegiatan', $id)->delete();

            // Create new anggota
            foreach ($request->anggota as $anggota) {
                AnggotaModel::create([
                    'id_kegiatan' => $kegiatan->id_kegiatan,
                    'id_user' => $anggota['id_user'],
                    'id_jabatan_kegiatan' => $anggota['id_jabatan_kegiatan']
                ]);
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Kegiatan berhasil diupdate',
                'data' => $kegiatan->load('anggota.user', 'anggota.jabatan')
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengupdate kegiatan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            
            $kegiatan = KegiatanModel::with(['agenda', 'anggota'])->find($id);
            
            if (!$kegiatan) {
                return response()->json([
                    'status' => false,
                    'message' => 'Kegiatan tidak ditemukan'
                ], 404);
            }

            // 1. Delete agenda_anggota records
            if ($kegiatan->agenda) {
                AgendaAnggotaModel::whereIn('id_agenda', $kegiatan->agenda->pluck('id_agenda'))
                    ->orWhereIn('id_anggota', $kegiatan->anggota->pluck('id_anggota'))
                    ->delete();
            }

            // 2. Delete agenda records
            $kegiatan->agenda()->delete();

            // 3. Delete anggota records
            $kegiatan->anggota()->delete();

            // 4. Finally delete the kegiatan
            $kegiatan->delete();

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Kegiatan berhasil dihapus'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Gagal menghapus kegiatan: ' . $e->getMessage()
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