<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\KegiatanModel;
use App\Models\AgendaAnggotaModel;
use App\Models\AnggotaModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgendaAnggotaController extends Controller
{
    public function show($id_kegiatan)
    {
        try {
            $user_id = Auth::id();
            
            // Ambil data kegiatan beserta relasinya
            $kegiatan = KegiatanModel::with([
                'anggota' => function($query) {
                    $query->with([
                        'user:id_user,nama',
                        'jabatan:id_jabatan_kegiatan,jabatan_nama'
                    ]);
                }
            ])
            ->findOrFail($id_kegiatan);

            // Cek authorization
            $isAuthorized = $kegiatan->anggota()
                ->where('id_user', $user_id)
                ->whereHas('jabatan', function($q) {
                    $q->where('jabatan_nama', '!=', 'pic');
                })->exists();

            if (!$isAuthorized) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized access'
                ], 403);
            }

            // Ambil agenda dari t_agenda_anggota
            $agendaList = AgendaAnggotaModel::where('id_anggota', function($query) use ($user_id, $id_kegiatan) {
                $query->select('id_anggota')
                    ->from('t_anggota')
                    ->where('id_user', $user_id)
                    ->where('id_kegiatan', $id_kegiatan)
                    ->first();
            })->get();

            // Transform data untuk response
            $transformedData = [
                'id_kegiatan' => $kegiatan->id_kegiatan,
                'nama_kegiatan' => $kegiatan->nama_kegiatan,
                'tempat_kegiatan' => $kegiatan->tempat_kegiatan,
                'deskripsi_kegiatan' => $kegiatan->deskripsi_kegiatan,
                'tanggal_mulai' => $kegiatan->tanggal_mulai,
                'tanggal_selesai' => $kegiatan->tanggal_selesai,
                'tanggal_acara' => $kegiatan->tanggal_acara,
                'anggota' => $kegiatan->anggota->map(function($anggota) {
                    return [
                        'id_anggota' => $anggota->id_anggota,
                        'id_user' => $anggota->id_user,
                        'nama' => $anggota->user->nama,
                        'jabatan' => [
                            'id_jabatan_kegiatan' => $anggota->jabatan->id_jabatan_kegiatan,
                            'nama_jabatan' => $anggota->jabatan->jabatan_nama
                        ]
                    ];
                }),
                'agenda' => $agendaList->map(function($agenda) {
                    return [
                        'id_agenda_anggota' => $agenda->id_agenda_anggota,
                        'id_agenda' => $agenda->id_agenda,
                        'nama_agenda' => $agenda->nama_agenda
                    ];
                })
            ];

            return response()->json([
                'status' => 'success',
                'data' => $transformedData
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil detail kegiatan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function index()
    {
        try {
            $user_id = Auth::id();
            
            $kegiatan = KegiatanModel::whereHas('anggota', function($query) use ($user_id) {
                $query->where('id_user', $user_id)
                    ->whereHas('jabatan', function($q) {
                        $q->where('jabatan_nama', '!=', 'pic');
                    });
            })
            ->with([
                'anggota' => function($query) {
                    $query->with(['user:id_user,nama', 'jabatan']);
                }
            ])
            ->orderBy('tanggal_mulai', 'desc')
            ->get();

            // Transform dan tambahkan agenda untuk setiap kegiatan
            $transformedData = $kegiatan->map(function($item) use ($user_id) {
                $agendaList = AgendaAnggotaModel::where('id_anggota', function($query) use ($user_id, $item) {
                    $query->select('id_anggota')
                        ->from('t_anggota')
                        ->where('id_user', $user_id)
                        ->where('id_kegiatan', $item->id_kegiatan)
                        ->first();
                })->get();

                return [
                    'id_kegiatan' => $item->id_kegiatan,
                    'nama_kegiatan' => $item->nama_kegiatan,
                    'tempat_kegiatan' => $item->tempat_kegiatan,
                    'tanggal_mulai' => $item->tanggal_mulai,
                    'tanggal_selesai' => $item->tanggal_selesai,
                    'tanggal_acara' => $item->tanggal_acara,
                    'agenda' => $agendaList->map(function($agenda) {
                        return [
                            'id_agenda_anggota' => $agenda->id_agenda_anggota,
                            'id_agenda' => $agenda->id_agenda,
                            'nama_agenda' => $agenda->nama_agenda
                        ];
                    })
                ];
            });

            return response()->json([
                'status' => 'success',
                'data' => $transformedData
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil data: ' . $e->getMessage()
            ], 500);
        }
    }
}