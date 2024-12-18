<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KegiatanModel;
use App\Models\AnggotaModel;
use App\Models\AgendaAnggotaModel;
use App\Models\DokumenModel;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class KegiatanDosenController extends Controller
{
    public function index() {
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
            ->with(['anggota' => function($query) use ($userId) {
                $query->where('id_user', $userId)
                      ->with('jabatan:id_jabatan_kegiatan,jabatan_nama'); // Add eager loading for jabatan
            }])
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
            ->get()
            ->map(function($kegiatan) {
                $kegiatan->jabatan = $kegiatan->anggota->first()->jabatan->jabatan_nama ?? null;
                unset($kegiatan->anggota); // Remove anggota from response
                return $kegiatan;
            });
    
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
            ->with([
                'anggota.user:id_user,nama', 
                'anggota.jabatan:id_jabatan_kegiatan,jabatan_nama,poin'
            ])
            ->select(
                'id_kegiatan',
                'nama_kegiatan',
                'deskripsi_kegiatan',
                'tanggal_mulai',
                'tanggal_selesai',
                'tanggal_acara',
                'tempat_kegiatan',
                'jenis_kegiatan',
                'progress'
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

    // untuk pic
    public function indexPIC()
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
                $query->where('id_user', $userId)
                    ->whereHas('jabatan', function($query) {
                        $query->where('jabatan_nama', 'PIC');
                    });
            })
            ->select(
                'id_kegiatan',
                'nama_kegiatan', 
                'tanggal_acara',
                'tempat_kegiatan',
                'jenis_kegiatan',
                'progress'
            )
            ->get();

            return response()->json([
                'status' => true,
                'message' => 'Data kegiatan PIC berhasil diambil',
                'data' => $kegiatan
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengambil data kegiatan PIC: ' . $e->getMessage()
            ], 500);
        }
    }

    // untuk anggota
    public function indexAnggota()
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
                $query->where('id_user', $userId)
                    ->whereHas('jabatan', function($q) {
                        $q->where('jabatan_nama', '!=', 'pic');
                    });
            })
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
                'jenis_kegiatan',
                'progress'
            )
            ->get()
            ->map(function($kegiatan) {
                $kegiatan->jabatan = $kegiatan->anggota->first()->jabatan->jabatan_nama ?? null;
                unset($kegiatan->anggota);
                return $kegiatan;
            });

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

    public function showAnggota($id)
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
                $query->where('id_user', $userId)
                    ->whereHas('jabatan', function($q) {
                        $q->where('jabatan_nama', '!=', 'pic');
                    });
            })
            ->where('id_kegiatan', $id)
            ->with([
                'anggota.user:id_user,nama', 
                'anggota.jabatan:id_jabatan_kegiatan,jabatan_nama,poin',
                'dokumen:id_dokumen,id_kegiatan,nama_dokumen,jenis_dokumen,file_path'
            ])
            ->select(
                'id_kegiatan',
                'nama_kegiatan',
                'deskripsi_kegiatan',
                'tanggal_mulai',
                'tanggal_selesai',
                'tanggal_acara',
                'tempat_kegiatan',
                'jenis_kegiatan',
                'progress'
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

            // Tambahkan data dokumen ke response
            $result['dokumen'] = $kegiatan->dokumen->map(function($dokumen) {
                return [
                    'id_dokumen' => $dokumen->id_dokumen,
                    'nama_dokumen' => $dokumen->nama_dokumen,
                    'jenis_dokumen' => $dokumen->jenis_dokumen,
                    'file_path' => $dokumen->file_path
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

    public function showPIC($id)
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
                $query->where('id_user', $userId)
                    ->whereHas('jabatan', function($q) {
                        $q->where('jabatan_nama', 'pic');
                    });
            })
            ->where('id_kegiatan', $id)
            ->with([
                'anggota.user:id_user,nama', 
                'anggota.jabatan:id_jabatan_kegiatan,jabatan_nama,poin',
                'dokumen:id_dokumen,id_kegiatan,nama_dokumen,jenis_dokumen,file_path'
            ])
            ->select(
                'id_kegiatan',
                'nama_kegiatan',
                'deskripsi_kegiatan',
                'tanggal_mulai',
                'tanggal_selesai',
                'tanggal_acara',
                'tempat_kegiatan',
                'jenis_kegiatan',
                'progress'
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

            // Tambahkan data dokumen ke response
            $result['dokumen'] = $kegiatan->dokumen->map(function($dokumen) {
                return [
                    'id_dokumen' => $dokumen->id_dokumen,
                    'nama_dokumen' => $dokumen->nama_dokumen,
                    'jenis_dokumen' => $dokumen->jenis_dokumen,
                    'file_path' => $dokumen->file_path
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

    public function getKegiatanKalenderDosen()
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

    public function getKegiatanKalenderPIC()
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

    public function getKegiatanKalenderAnggota()
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

    public function downloadDokumen($id_dokumen)
    {
        try {
            $userId = Auth::id();
            if (!$userId) {
                return response()->json([
                    'status' => false,
                    'message' => 'User tidak terautentikasi'
                ], 401);
            }

            // Cari dokumen
            $dokumen = DokumenModel::with('kegiatan.anggota')
            ->where('jenis_dokumen', 'surat tugas')
            ->findOrFail($id_dokumen);

            // Cek apakah user adalah anggota dari kegiatan ini
            $isAnggota = $dokumen->kegiatan->anggota->where('id_user', $userId)->isNotEmpty();
            
            if (!$isAnggota) {
                return response()->json([
                    'status' => false,
                    'message' => 'Anda tidak memiliki akses ke dokumen ini'
                ], 403);
            }

            // Dapatkan path lengkap file
            $filePath = storage_path('app/public/' . $dokumen->file_path);

            // Cek keberadaan file
            if (!Storage::exists('public/' . $dokumen->file_path)) {
                return response()->json([
                    'status' => false,
                    'message' => 'File tidak ditemukan'
                ], 404);
            }

            // Return file untuk didownload
            return response()->download($filePath, $dokumen->nama_dokumen);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mendownload dokumen: ' . $e->getMessage()
            ], 500);
        }
    }
}