<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KegiatanModel;
use App\Models\UserModel;
use App\Models\DokumenModel;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\AnggotaModel;
use App\Models\JabatanKegiatanModel;
use App\Models\AgendaAnggotaModel;
use PhpOffice\PhpWord\PhpWord;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class KegiatanController extends Controller
{
    // function index
    public function admin()
    {
        $breadcrumb = (object) [
            'title' => 'Kegiatan',
            'list' => ['Home', 'Kegiatan Admin'],
        ];
        $activeMenu = 'kegiatan admin';
        return view('admin.kegiatan.index', ['breadcrumb' => $breadcrumb, 'activeMenu' => $activeMenu]);
    }

    public function pimpinan()
    {
        $breadcrumb = (object) [
            'title' => 'Kegiatan',
            'list' => ['Home', 'Kegiatan Pimpinan'],
        ];
        $activeMenu = 'kegiatan pimpinan';
        return view('pimpinan.kegiatan.index', ['breadcrumb' => $breadcrumb, 'activeMenu' => $activeMenu]);
    }

    public function dosen(): mixed
    {
        $breadcrumb = (object) [
            'title' => 'Kegiatan',
            'list' => ['Home', 'Kegiatan Dosen'],
        ];
        $activeMenu = 'kegiatan dosen';
        return view('dosen.kegiatan.index', ['breadcrumb' => $breadcrumb, 'activeMenu' => $activeMenu]);
    }

    public function dosenPIC()
    {
        $breadcrumb = (object) [
            'title' => 'Kegiatan',
            'list' => ['Home', 'Kegiatan PIC'],
        ];
        $activeMenu = 'kegiatan pic';
        return view('dosenPIC.kegiatan.index', ['breadcrumb' => $breadcrumb, 'activeMenu' => $activeMenu]);
    }

    public function dosenAnggota()
    {
        $breadcrumb = (object) [
            'title' => 'Kegiatan',
            'list' => ['Home', 'Kegiatan Anggota'],
        ];
        $activeMenu = 'kegiatan anggota';
        return view('dosenAnggota.kegiatan.index', ['breadcrumb' => $breadcrumb, 'activeMenu' => $activeMenu]);
    }



    // function list
    public function listAdmin(Request $request)
    {
        $kegiatan = KegiatanModel::select('id_kegiatan', 'nama_kegiatan', 'deskripsi_kegiatan', 'tanggal_mulai', 'tanggal_selesai', 'tanggal_acara', 'tempat_kegiatan', 'jenis_kegiatan');

        if ($request->jenis_kegiatan) {
            $kegiatan->where('jenis_kegiatan', $request->jenis_kegiatan);
        }
        return DataTables::of($kegiatan)
            ->addIndexColumn()
            ->addColumn('aksi', function ($kegiatan) {
                $btn = '<button onclick="modalAction(\'' . url('/admin/kegiatan/' . $kegiatan->id_kegiatan . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/admin/kegiatan/' . $kegiatan->id_kegiatan . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/admin/kegiatan/' . $kegiatan->id_kegiatan . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function listPimpinan(Request $request)
    {
        $kegiatan = KegiatanModel::select('id_kegiatan', 'nama_kegiatan', 'deskripsi_kegiatan', 'tanggal_mulai', 'tanggal_selesai', 'tanggal_acara', 'tempat_kegiatan', 'jenis_kegiatan');

        if ($request->jenis_kegiatan) {
            $kegiatan->where('jenis_kegiatan', $request->jenis_kegiatan);
        }
        return DataTables::of($kegiatan)
            ->addIndexColumn()
            ->addColumn('aksi', function ($kegiatan) {
                $btn = '<button onclick="modalAction(\'' . url('/pimpinan/kegiatan/' . $kegiatan->id_kegiatan . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function listDosen(Request $request)
    {
        $kegiatan = KegiatanModel::select('id_kegiatan', 'nama_kegiatan', 'deskripsi_kegiatan', 'tanggal_mulai', 'tanggal_selesai', 'tanggal_acara', 'tempat_kegiatan', 'jenis_kegiatan');

        if ($request->jenis_kegiatan) {
            $kegiatan->where('jenis_kegiatan', $request->jenis_kegiatan);
        }
        return DataTables::of($kegiatan)
            ->addIndexColumn()
            ->addColumn('aksi', function ($kegiatan) {
                $btn = '<button onclick="modalAction(\'' . url('/dosen/kegiatan/' . $kegiatan->id_kegiatan . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function listDosenPIC(Request $request)
    {
        $kegiatan = KegiatanModel::select('id_kegiatan', 'nama_kegiatan', 'deskripsi_kegiatan', 'tanggal_mulai', 'tanggal_selesai', 'tanggal_acara', 'tempat_kegiatan', 'jenis_kegiatan');

        if ($request->jenis_kegiatan) {
            $kegiatan->where('jenis_kegiatan', $request->jenis_kegiatan);
        }
        return DataTables::of($kegiatan)
            ->addIndexColumn()
            ->addColumn('aksi', function ($kegiatan) {
                $btn = '<button onclick="modalAction(\'' . url('/dosenPIC/kegiatan/' . $kegiatan->id_kegiatan . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/dosenPIC/kegiatan/' . $kegiatan->id_kegiatan . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/dosenPIC/kegiatan/' . $kegiatan->id_kegiatan . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    // function show
    public function show_ajaxAdmin($id)
    {
        $kegiatan = KegiatanModel::find($id);
        $angggota = anggotaModel::select('id_kegiatan', 'id_anggota', 'id_user', 'id_jabatan_kegiatan')->where('id_kegiatan', $id)->with('user', 'jabatan')->get();

        if (!$kegiatan) {
            return response()->json(['message' => 'Data not found'], 404);
        }

        return view('admin.kegiatan.show_ajax', ['kegiatan' => $kegiatan, 'anggota' => $angggota]);
    }

    public function show_ajaxPimpinan($id)
    {
        $kegiatan = KegiatanModel::find($id);
        $angggota = anggotaModel::select('id_kegiatan', 'id_anggota', 'id_user', 'id_jabatan_kegiatan')->where('id_kegiatan', $id)->with('user', 'jabatan')->get();

        if (!$kegiatan) {
            return response()->json(['message' => 'Data not found'], 404);
        }

        return view('pimpinan.kegiatan.show_ajax', ['kegiatan' => $kegiatan, 'anggota' => $angggota]);
    }

    public function show_ajaxDosen($id)
    {
        $kegiatan = KegiatanModel::find($id);
        $angggota = anggotaModel::select('id_kegiatan', 'id_anggota', 'id_user', 'id_jabatan_kegiatan')->where('id_kegiatan', $id)->with('user', 'jabatan')->get();

        if (!$kegiatan) {
            return response()->json(['message' => 'Data not found'], 404);
        }

        return view('dosen.kegiatan.show_ajax', ['kegiatan' => $kegiatan, 'anggota' => $angggota]);
    }

    public function show_ajaxDosenPIC($id)
    {
        $kegiatan = KegiatanModel::find($id);
        $angggota = anggotaModel::select('id_kegiatan', 'id_anggota', 'id_user', 'id_jabatan_kegiatan')->where('id_kegiatan', $id)->with('user', 'jabatan')->get();

        if (!$kegiatan) {
            return response()->json(['message' => 'Data not found'], 404);
        }

        return view('dosen.kegiatan.show_ajax', ['kegiatan' => $kegiatan, 'anggota' => $angggota]);
    }

    public function create_ajaxAdmin()
    {
        $jenis_kegiatan = KegiatanModel::all();
        $jabatan = JabatanKegiatanModel::all();
        $anggota = UserModel::select('id_user', 'username', 'nama', 'email', 'NIP', 'level')->where('level', 'dosen')->get();
        return view('admin.kegiatan.create_ajax', compact('jenis_kegiatan', 'jabatan', 'anggota'));
    }

    public function create_ajaxDosen()
    {
        $jenis_kegiatan = KegiatanModel::all();
        $jabatan = JabatanKegiatanModel::all();
        $anggota = UserModel::select('id_user', 'username', 'nama', 'email', 'NIP', 'level')->where('level', 'dosen')->get();
        return view('dosen.kegiatan.create_ajax', compact('jenis_kegiatan', 'jabatan', 'anggota'));
    }

    public function storeAdmin(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $validator = Validator::make($request->all(), [
                'nama_kegiatan' => 'required|string|max:255',
                'jenis_kegiatan' => 'required|string',
                'deskripsi' => 'required|string',
                'tanggal_acara' => 'required|date',
                'tanggal_mulai' => 'required|date',
                'tanggal_selesai' => 'required|date',
                'jabatan_id' => 'required|array',
                'anggota_id' => 'required|array',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'msgField' => $validator->errors()
                ]);
            }

            $kegiatan = KegiatanModel::create([
                'nama_kegiatan' => $request->nama_kegiatan,
                'jenis_kegiatan' => $request->jenis_kegiatan,
                'deskripsi_kegiatan' => $request->deskripsi,
                'tanggal_acara' => $request->tanggal_acara,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
            ]);
            $index = 0;
            foreach ($request->anggota_id as $a) {
                AnggotaModel::create([
                    'id_kegiatan' => $kegiatan->id_kegiatan,
                    'id_user' => $request->anggota_id[$index],
                    'id_jabatan_kegiatan' => $request->jabatan_id[$index],
                ]);
                $index++;
            }

            return response()->json([
                'status' => true,
                'message' => 'Kegiatan berhasil ditambahkan'
            ]);
        }
        return redirect('/admin/kegiatan');
    }

    public function storeDosen(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $validator = Validator::make($request->all(), [
                'nama_kegiatan' => 'required|string|max:255',
                'jenis_kegiatan' => 'required|string',
                'deskripsi' => 'required|string',
                'tanggal_acara' => 'required|date',
                'tanggal_mulai' => 'required|date',
                'tanggal_selesai' => 'required|date',
                'tempat_kegiatan' => 'required|string',
                'jabatan_id' => 'required|array',
                'anggota_id' => 'required|array',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'msgField' => $validator->errors()
                ]);
            }

            $kegiatan = KegiatanModel::create([
                'nama_kegiatan' => $request->nama_kegiatan,
                'jenis_kegiatan' => $request->jenis_kegiatan,
                'deskripsi_kegiatan' => $request->deskripsi,
                'tanggal_acara' => $request->tanggal_acara,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
                'tempat_kegiatan' => $request->tempat_kegiatan,
            ]);
            $index = 0;
            foreach ($request->anggota_id as $a) {
                AnggotaModel::create([
                    'id_kegiatan' => $kegiatan->id_kegiatan,
                    'id_user' => $request->anggota_id[$index],
                    'id_jabatan_kegiatan' => $request->jabatan_id[$index],
                ]);
                $index++;
            }

            return response()->json([
                'status' => true,
                'message' => 'Kegiatan berhasil ditambahkan'
            ]);
        }
        return redirect('/dosen/kegiatan');
    }

    public function editAjaxAdmin($id)
    {
        // $kegiatan = KegiatanModel::with('anggota.user', 'anggota.jabatan')->find($id);
        $kegiatan = KegiatanModel::select('id_kegiatan', 'nama_kegiatan', 'jenis_kegiatan', 'deskripsi_kegiatan', 'tanggal_mulai', 'tanggal_selesai', 'tanggal_acara', 'tempat_kegiatan')
            ->where('id_kegiatan', $id)
            ->with('anggota.user', 'anggota.jabatan')
            ->first();
        if (!$kegiatan) {
            return response()->json(['status' => false, 'message' => 'Kegiatan tidak ditemukan'], 404);
        }
        $anggota_kegiatan = AnggotaModel::select('id_anggota', 'id_user', 'id_jabatan_kegiatan')
            ->where('id_kegiatan', $id)
            ->get();
        $jabatan = JabatanKegiatanModel::all();
        $anggota = UserModel::select('id_user', 'username', 'nama', 'email', 'NIP', 'level')->where('level', 'dosen')->get();
        return view('admin.kegiatan.edit_ajax', ['kegiatan' => $kegiatan, 'jabatan' => $jabatan, 'anggota' => $anggota, 'anggota_kegiatan' => $anggota_kegiatan]);
    }

    public function editAjaxDosen($id)
    {
        $kegiatan = KegiatanModel::select('id_kegiatan', 'nama_kegiatan', 'jenis_kegiatan', 'deskripsi_kegiatan', 'tanggal_mulai', 'tanggal_selesai', 'tanggal_acara', 'tempat_kegiatan')
        ->where('id_kegiatan', $id)
        ->with('anggota.user', 'anggota.jabatan')
        ->first();
        if (!$kegiatan) {
            return response()->json(['status' => false, 'message' => 'Kegiatan tidak ditemukan'], 404);
        }
        $anggota_kegiatan = AnggotaModel::select('id_anggota', 'id_user', 'id_jabatan_kegiatan')
        ->where('id_kegiatan', $id)
        ->get();
        $jabatan = JabatanKegiatanModel::all();
        $anggota = UserModel::select('id_user', 'username', 'nama', 'email', 'NIP', 'level')->where('level', 'dosen')->get();
        // return view('dosen.kegiatan.edit_ajax', compact('kegiatan','jabatan', 'anggota'));
        return view('dosen.kegiatan.edit_ajax', ['kegiatan' => $kegiatan, 'jabatan' => $jabatan, 'anggota' => $anggota, 'anggota_kegiatan' => $anggota_kegiatan]);
    }
    
    public function editAjaxDosenPIC($id)
    {
        $kegiatan = KegiatanModel::select('id_kegiatan', 'nama_kegiatan', 'jenis_kegiatan', 'deskripsi_kegiatan', 'tanggal_mulai', 'tanggal_selesai', 'tanggal_acara', 'tempat_kegiatan')
            ->where('id_kegiatan', $id)
            ->with('anggota.user', 'anggota.jabatan')
            ->first();
        if (!$kegiatan) {
            return response()->json(['status' => false, 'message' => 'Kegiatan tidak ditemukan'], 404);
        }
        $anggota_kegiatan = AnggotaModel::select('id_anggota', 'id_user', 'id_jabatan_kegiatan')
            ->where('id_kegiatan', $id)
            ->get();
        $jabatan = JabatanKegiatanModel::all();
        $anggota = UserModel::select('id_user', 'username', 'nama', 'email', 'NIP', 'level')->where('level', 'dosen')->get();
        // return view('dosen.kegiatan.edit_ajax', compact('kegiatan','jabatan', 'anggota'));
        return view('dosenPIC.kegiatan.edit_ajax', ['kegiatan' => $kegiatan, 'jabatan' => $jabatan, 'anggota' => $anggota, 'anggota_kegiatan' => $anggota_kegiatan]);
    }

    public function updateAjaxAdmin(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $validator = Validator::make($request->all(), [
                'nama_kegiatan' => 'required|string|max:255',
                'jenis_kegiatan' => 'required|string|max:255',
                'deskripsi_kegiatan' => 'required|string',
                'tanggal_mulai' => 'required|date',
                'tanggal_selesai' => 'required|date',
                'tanggal_acara' => 'required|date',
                'jabatan_id' => 'required|array',
                'anggota_id' => 'required|array',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'msgField' => $validator->errors()
                ]);
            }

            $kegiatan = KegiatanModel::find($id);
            if (!$kegiatan) {
                return response()->json(['status' => false, 'message' => 'Kegiatan tidak ditemukan'], 404);
            }

            $kegiatan->update([
                'nama_kegiatan' => $request->nama_kegiatan,
                'jenis_kegiatan' => $request->jenis_kegiatan,
                'deskripsi_kegiatan' => $request->deskripsi_kegiatan,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
                'tanggal_acara' => $request->tanggal_acara,
            ]);

            // Update anggota
            // AnggotaModel::where('id_kegiatan', $id);
            // foreach ($request->anggota_id as $index => $anggota_id) {
            //     AnggotaModel::create([
            //         'id_kegiatan' => $kegiatan->id_kegiatan,
            //         'id_user' => $anggota_id,
            //         'id_jabatan_kegiatan' => $request->jabatan_id[$index],
            //     ]);
            // }
            $index = 0;
            foreach ($request->anggota_id as $ag) {
                $check = AnggotaModel::select('id_anggota', 'id_kegiatan', 'id_user', 'id_jabatan_kegiatan')->where('id_kegiatan', $id)->where('id_user', $request->anggota_id[$index])->get();
                if ($check) {
                    $check->update([
                        'id_user' => $request->anggota_id[$index],
                        'id_jabatan_kegiatan' => $request->jabatan_id[$index]
                    ]);
                    $index++;
                }
            }

            return response()->json([
                'status' => true,
                'message' => 'Kegiatan berhasil diperbarui'
            ]);
        }
    }

    public function updateAjaxDosen(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $validator = Validator::make($request->all(), [
                'nama_kegiatan' => 'required|string|max:255',
                'jenis_kegiatan' => 'required|string|max:255',
                'deskripsi_kegiatan' => 'required|string',
                'tanggal_mulai' => 'required|date',
                'tanggal_selesai' => 'required|date',
                'tanggal_acara' => 'required|date',
                'tempat_kegiatan' => 'required|string|max:255',
                'jabatan_id' => 'required|array',
                'anggota_id' => 'required|array',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'msgField' => $validator->errors()
                ]);
            }

            $kegiatan = KegiatanModel::find($id);
            if (!$kegiatan) {
                return response()->json(['status' => false, 'message' => 'Kegiatan tidak ditemukan'], 404);
            }

            $kegiatan->update([
                'nama_kegiatan' => $request->nama_kegiatan,
                'jenis_kegiatan' => $request->jenis_kegiatan,
                'deskripsi_kegiatan' => $request->deskripsi_kegiatan,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
                'tanggal_acara' => $request->tanggal_acara,
                'tempat_kegiatan' => $request->tempat_kegiatan,
            ]);

            // Update anggota
            AnggotaModel::where('id_kegiatan', $id)->delete();
            foreach ($request->anggota_id as $index => $anggota_id) {
                AnggotaModel::create([
                    'id_kegiatan' => $kegiatan->id_kegiatan,
                    'id_user' => $anggota_id,
                    'id_jabatan_kegiatan' => $request->jabatan_id[$index],
                ]);
            }

            return response()->json([
                'status' => true,
                'message' => 'Kegiatan berhasil diperbarui'
            ]);
        }
    }

    public function updateAjaxDosenPIC(Request $request, $id)
    {
        if($request->ajax() || $request->wantsJson()){
            $validator = Validator::make($request->all(), [
                'nama_kegiatan' => 'required|string|max:255',
                'jenis_kegiatan' => 'required|string|max:255',
                'deskripsi_kegiatan' => 'required|string',
                'tanggal_mulai' => 'required|date',
                'tanggal_selesai' => 'required|date',
                'tanggal_acara' => 'required|date',
                'tempat_kegiatan' => 'required|string|max:255',
                'jabatan_id' => 'required|array',
                'anggota_id' => 'required|array',
            ]);
        
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'msgField' => $validator->errors()
                ]);
            }
        
            $kegiatan = KegiatanModel::find($id);
            if (!$kegiatan) {
                return response()->json(['status' => false, 'message' => 'Kegiatan tidak ditemukan'], 404);
            }
        
            $kegiatan->update([
                'nama_kegiatan' => $request->nama_kegiatan,
                'jenis_kegiatan' => $request->jenis_kegiatan,
                'deskripsi_kegiatan' => $request->deskripsi_kegiatan,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
                'tanggal_acara' => $request->tanggal_acara,
                'tempat_kegiatan' => $request->tempat_kegiatan,
            ]);
        
            // Update anggota
            AnggotaModel::where('id_kegiatan', $id)->delete();
            foreach ($request->anggota_id as $index => $anggota_id) {
                AnggotaModel::create([
                    'id_kegiatan' => $kegiatan->id_kegiatan,
                    'id_user' => $anggota_id,
                    'id_jabatan_kegiatan' => $request->jabatan_id[$index],
                ]);
            }
        
            return response()->json([
                'status' => true,
                'message' => 'Kegiatan berhasil diperbarui'
            ]);
        }
    }

    public function confirmAjaxDosen($id)
    {
        $kegiatan = KegiatanModel::find($id);
        return view('dosen.kegiatan.confirm_ajax',['kegiatan' => $kegiatan]);
    }

    public function deleteAjaxDosen($id)
    {
        if (request()->ajax() || request()->wantsJson()) {
            // Cari data kegiatan berdasarkan ID dengan eager loading
            $kegiatan = kegiatanModel::with(['agenda', 'anggota'])->find($id);

            if ($kegiatan) {
                try {
                    DB::beginTransaction();

                    // 1. Hapus semua agenda_anggota terkait
                    AgendaAnggotaModel::whereIn('id_agenda', $kegiatan->agenda->pluck('id_agenda'))
                        ->orWhereIn('id_anggota', $kegiatan->anggota->pluck('id_anggota'))
                        ->delete();

                    // 2. Hapus semua agenda terkait
                    $kegiatan->agenda()->delete();

                    // 3. Hapus semua anggota terkait
                    $kegiatan->anggota()->delete();

                    // 4. Hapus kegiatan
                    $kegiatan->delete();

                    DB::commit();

                    return response()->json([
                        'status' => true,
                        'message' => 'Kegiatan berhasil dihapus beserta semua data terkait'
                    ]);

                } catch (\Exception $e) {
                    DB::rollBack();

                    return response()->json([
                        'status' => false,
                        'message' => 'Gagal menghapus kegiatan. Terjadi kesalahan sistem.',
                        'detail' => $e->getMessage()
                    ], 500);
                }
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data kegiatan tidak ditemukan'
                ], 404);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Permintaan harus melalui AJAX'
            ], 400);
        }
    }

    // function export (admin)
    public function exportPdf()
    {
        $kegiatan = KegiatanModel::select('id_kegiatan', 'nama_kegiatan', 'deskripsi_kegiatan', 'tanggal_mulai', 'tanggal_selesai', 'tanggal_acara', 'tempat_kegiatan', 'jenis_kegiatan')->get();
        $pdf = Pdf::loadView('admin.kegiatan.export_pdf', ['kegiatan' => $kegiatan]);
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption("isRemoteEnabled", true);
        $pdf->render();
        return $pdf->stream('Data Kegiatan ' . date('Y-m-d H:i:s') . '.pdf');
    }

    // function export word
    public function exportWord($id)
    {
        $kegiatan = KegiatanModel::find($id);
        $anggota = AnggotaModel::where('id_kegiatan', $id)->with('user', 'jabatan')->get();

        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $phpWord->setDefaultFontName('Times New Roman');
        $phpWord->setDefaultFontSize(12);

        $section = $phpWord->addSection();

        // Add header information (Kementerian Pendidikan header)
        $headerTable = $section->addTable();
        $headerTable->addRow();
        $headerTable->addCell(1500)->addImage(asset('polinema.png'), ['width' => 60, 'height' => 60, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        $headerCell = $headerTable->addCell(8500);

        $textRun = $headerCell->addTextRun(['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        $textRun->addText("KEMENTERIAN PENDIDIKAN, KEBUDAYAAN, RISET, DAN TEKNOLOGI", ['size' => 11]);
        $textRun->addTextBreak();
        $textRun->addText("POLITEKNIK NEGERI MALANG", ['bold' => true, 'size' => 13]);
        $textRun->addTextBreak();
        $textRun->addText("JL, Soekarno-Hatta No.9 Malang 65141", ['size' => 10]);
        $textRun->addTextBreak();
        $textRun->addText("Telepon (0341) 404424 Pes. 101-105 0341-404420, Fax. (0341) 404420", ['size' => 10]);
        $textRun->addTextBreak();
        $textRun->addText("https://www.polinema.ac.id", ['size' => 10]);

        // Add a line break
        $section->addTextBreak(0);

        // Add a horizontal line
        $lineStyle = ['weight' => 1, 'width' => 500, 'height' => 0, 'color' => '000000'];
        $section->addLine($lineStyle);

        // Add a line break
        $section->addTextBreak(0);

        // Add title and document number
        $titleRun = $section->addTextRun(['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        $titleRun->addText("SURAT TUGAS", ['bold' => true, 'size' => 14]);
        $titleRun->addTextBreak();
        $titleRun->addText("Nomor : 31464/PL2.1/KP/2024");
        $section->addTextBreak();

        // Introduction text
        $section->addText("Wakil Direktur I memberikan tugas kepada :", ['size' => 12]);
        $section->addTextBreak();

        // Add table for members (Anggota)
        $table = $section->addTable(['width' => 100 * 50]);
        $table = $section->addTable(['borderSize' => 6, 'borderColor' => '000000', 'width' => 100 * 50]);
        $table->addRow();
        $table->addCell(1000)->addText("NO", ['bold' => true]);
        $table->addCell(4000)->addText("NAMA", ['bold' => true]);
        $table->addCell(4000)->addText("NIP", ['bold' => true]);
        $table->addCell(4000)->addText("JABATAN", ['bold' => true]);

        // Populate table with anggota data
        $no = 1;
        foreach ($anggota as $member) {
            $table = $section->addTable(['borderSize' => 6, 'borderColor' => '000000', 'width' => 100 * 50]);
            $table->addRow();
            $table->addCell(1000)->addText($no);
            $table->addCell(4000)->addText($member->user->nama);
            $table->addCell(4000)->addText($member->user->NIP);
            $table->addCell(4000)->addText($member->jabatan->jabatan_nama);
            $no++;
        }

        // Add kegiatan details in a paragraph
        $section->addTextBreak();
        $section->addText("Untuk menjadi narasumber kegiatan " . $kegiatan->nama_kegiatan . " yang diselenggarakan oleh " . $kegiatan->deskripsi_kegiatan . " pada tanggal " . date('d F Y', strtotime($kegiatan->tanggal_acara)) . " bertempat di " . $kegiatan->tempat_kegiatan . ".", ['size' => 12]);

        $section->addText("Selesai melaksanakan tugas harap melaporkan hasilnya kepada Wakil Direktur I.", ['size' => 12]);
        $section->addText("Demikian untuk dilaksanakan sebaik-baiknya.", ['size' => 12]);
        $section->addTextBreak();

        // Add signature
        $signatureTable = $section->addTable();
        $signatureTable->addRow();
        $signatureCell = $signatureTable->addCell(10000, ['border' => 0]);
        $signatureCell->addText("28 Oktober 2024", null, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::RIGHT]);
        $signatureCell->addText("Direktur,", null, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::RIGHT]);
        $signatureCell->addTextBreak(3);
        $signatureCell->addText("Dr. Kurnia Ekasari, SE., M.M., Ak.", ['bold' => true], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::RIGHT]);
        $signatureCell->addText("NIP. 196602141990032002", null, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::RIGHT]);

        // Save the document
        $fileName = 'Surat_Tugas_' . $kegiatan->nama_kegiatan . '.docx';
        $filePath = storage_path('app/public/' . $fileName);
        $phpWord->save($filePath, 'Word2007');

        return response()->download($filePath)->deleteFileAfterSend(true);
    }

    public function exportWordDosen($id)
    {
        $kegiatan = KegiatanModel::find($id);
        $anggota = AnggotaModel::where('id_kegiatan', $id)->with('user', 'jabatan')->get();

        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $phpWord->setDefaultFontName('Times New Roman');
        $phpWord->setDefaultFontSize(12);

        $section = $phpWord->addSection();

        // Add header information (Kementerian Pendidikan header)
        $headerTable = $section->addTable();
        $headerTable->addRow();
        $headerTable->addCell(1500)->addImage(asset('polinema.png'), ['width' => 60, 'height' => 60, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        $headerCell = $headerTable->addCell(8500);

        $textRun = $headerCell->addTextRun(['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        $textRun->addText("KEMENTERIAN PENDIDIKAN, KEBUDAYAAN, RISET, DAN TEKNOLOGI", ['size' => 11]);
        $textRun->addTextBreak();
        $textRun->addText("POLITEKNIK NEGERI MALANG", ['bold' => true, 'size' => 13]);
        $textRun->addTextBreak();
        $textRun->addText("JL, Soekarno-Hatta No.9 Malang 65141", ['size' => 10]);
        $textRun->addTextBreak();
        $textRun->addText("Telepon (0341) 404424 Pes. 101-105 0341-404420, Fax. (0341) 404420", ['size' => 10]);
        $textRun->addTextBreak();
        $textRun->addText("https://www.polinema.ac.id", ['size' => 10]);

        // Add a line break
        $section->addTextBreak(0);

        // Add a horizontal line
        $lineStyle = ['weight' => 1, 'width' => 500, 'height' => 0, 'color' => '000000'];
        $section->addLine($lineStyle);

        // Add a line break
        $section->addTextBreak(0);

        // Add title and document number
        $titleRun = $section->addTextRun(['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        $titleRun->addText("SURAT TUGAS", ['bold' => true, 'size' => 14]);
        $titleRun->addTextBreak();
        $titleRun->addText("Nomor : ...../...../../2024");
        $section->addTextBreak();

        // Introduction text
        $section->addText("Wakil Direktur I memberikan tugas kepada :", ['size' => 12]);
        $section->addTextBreak();

        // Add table for members (Anggota)
        $table = $section->addTable(['width' => 100 * 50]);
        $table = $section->addTable(['borderSize' => 6, 'borderColor' => '000000', 'width' => 100 * 50]);
        $table->addRow();
        $table->addCell(1000)->addText("NO", ['bold' => true]);
        $table->addCell(4000)->addText("NAMA", ['bold' => true]);
        $table->addCell(4000)->addText("NIP", ['bold' => true]);
        $table->addCell(4000)->addText("JABATAN", ['bold' => true]);

        // Populate table with anggota data
        $no = 1;
        foreach ($anggota as $member) {
            $table = $section->addTable(['borderSize' => 6, 'borderColor' => '000000', 'width' => 100 * 50]);
            $table->addRow();
            $table->addCell(1000)->addText($no);
            $table->addCell(4000)->addText($member->user->nama);
            $table->addCell(4000)->addText($member->user->NIP);
            $table->addCell(4000)->addText($member->jabatan->jabatan_nama);
            $no++;
        }

        // Add kegiatan details in a paragraph
        $section->addTextBreak();
        $section->addText("Untuk menjadi narasumber kegiatan " . $kegiatan->nama_kegiatan . " yang diselenggarakan oleh " . $kegiatan->deskripsi_kegiatan . " pada tanggal " . date('d F Y', strtotime($kegiatan->tanggal_acara)) . " bertempat di " . $kegiatan->tempat_kegiatan . ".", ['size' => 12]);

        $section->addText("Selesai melaksanakan tugas harap melaporkan hasilnya kepada Wakil Direktur I.", ['size' => 12]);
        $section->addText("Demikian untuk dilaksanakan sebaik-baiknya.", ['size' => 12]);
        $section->addTextBreak();

        // Add signature
        $signatureTable = $section->addTable();
        $signatureTable->addRow();
        $signatureCell = $signatureTable->addCell(10000, ['border' => 0]);
        $signatureCell->addText("28 Oktober 2024", null, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::RIGHT]);
        $signatureCell->addText("Direktur,", null, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::RIGHT]);
        $signatureCell->addTextBreak(3);
        $signatureCell->addText("Dr. Kurnia Ekasari, SE., M.M., Ak.", ['bold' => true], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::RIGHT]);
        $signatureCell->addText("NIP. 196602141990032002", null, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::RIGHT]);

        // Save the document
        $fileName = 'Surat_Tugas_' . $kegiatan->nama_kegiatan . '.docx';
        $filePath = storage_path('app/public/' . $fileName);
        $phpWord->save($filePath, 'Word2007');

        return response()->download($filePath)->deleteFileAfterSend(true);
    }

    // function upload surat tugas
    public function uploadSuratTugas(Request $request, $id)
    {
        $request->validate([
            'dokumen' => 'required|file|mimes:pdf,doc,docx|max:2048',
        ]);

        $kegiatan = KegiatanModel::find($id);

        if ($request->hasFile('dokumen')) {
            $file = $request->file('dokumen');
            $path = $file->store('surat_tugas', 'public');

            // Save the file path to the t_dokumen table
            DokumenModel::create([
                'id_kegiatan' => $kegiatan->id_kegiatan,
                'file_path' => $path,
                'nama_dokumen' => $file->getClientOriginalName(),
                'progress' => 0,
            ]);
        }

        return response()->json(['success' => true]);
    }

    // fungsi agenda kegiatan
    public function agendaAnggota()
    {
        $breadcrumb = (object) [
            'title' => 'Agenda Anggota',
            'list' => ['Home', 'Agenda Anggota'],
        ];
        $activeMenu = 'agenda_anggota';

        $agendaAnggota = DB::table('t_kegiatan')
            ->join('t_anggota', 't_kegiatan.id_kegiatan', '=', 't_anggota.id_kegiatan')
            ->join('t_user', 't_anggota.id_user', '=', 't_user.id_user')
            ->select(
                't_kegiatan.id_kegiatan',
                't_kegiatan.nama_kegiatan',
                DB::raw('GROUP_CONCAT(t_user.nama SEPARATOR ", ") as anggota'),
                't_kegiatan.tanggal_mulai',
                't_kegiatan.tanggal_selesai'
            )
            ->groupBy('t_kegiatan.id_kegiatan', 't_kegiatan.nama_kegiatan', 't_kegiatan.tanggal_mulai', 't_kegiatan.tanggal_selesai')
            ->get();

        return view('dosenPIC.agendaAnggota.index', ['breadcrumb' => $breadcrumb, 'activeMenu' => $activeMenu, 'agendaAnggota' => $agendaAnggota]);
    }

    public function editAgendaAnggota($id) {}

    public function detailAgendaAnggota($id) {}

    public function updateAgendaAnggota(Request $request, $id) {}

    public function deleteAgendaAnggota($id) {}

    public function KegiatanJTI(): mixed
    {
        $breadcrumb = (object) [
            'title' => 'Kegiatan',
            'list' => ['Home', 'Kegiatan Dosen JTI'],
        ];
        $activeMenu = 'kegiatan jti';
        return view('dosen.kegiatan.jti.index', ['breadcrumb' => $breadcrumb, 'activeMenu' => $activeMenu]);
    }


    public function listDosenJTI(Request $request)
    {
        $dosenId = auth()->user()->id_user;

        $kegiatan = KegiatanModel::select('id_kegiatan', 'nama_kegiatan', 'deskripsi_kegiatan', 'tanggal_mulai', 'tanggal_selesai', 'tanggal_acara', 'tempat_kegiatan', 'jenis_kegiatan')
            ->where('jenis_kegiatan', 'Kegiatan JTI')
            ->whereHas('anggota', function ($query) use ($dosenId) {
                $query->where('id_user', $dosenId);
            });


        return DataTables::of($kegiatan)
            ->addIndexColumn()
            ->addColumn('aksi', function ($kegiatan) {
                $btn = '<button onclick="modalAction(\'' . url('/dosen/kegiatan/jti/' . $kegiatan->id_kegiatan . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function show_ajaxDosenJTI($id)
    {
        $kegiatan = KegiatanModel::find($id);
        $angggota = anggotaModel::select('id_kegiatan', 'id_anggota', 'id_user', 'id_jabatan_kegiatan')->where('id_kegiatan', $id)->with('user', 'jabatan')->get();

        if (!$kegiatan) {
            return response()->json(['message' => 'Data not found'], 404);
        }


        return view('dosen.kegiatan.jti.show_ajax', ['kegiatan' => $kegiatan, 'anggota' => $angggota]);
    }

    public function KegiatanNonJTI(): mixed
    {
        $breadcrumb = (object) [
            'title' => 'Kegiatan',
            'list' => ['Home', 'Kegiatan Dosen Non JTI'],
        ];
        $activeMenu = 'kegiatan non jti';
        return view('dosen.kegiatan.nonjti.index', ['breadcrumb' => $breadcrumb, 'activeMenu' => $activeMenu]);
    }


    public function listDosenNonJTI(Request $request)
    {
        $dosenId = auth()->user()->id_user;

        $kegiatan = KegiatanModel::select('id_kegiatan', 'nama_kegiatan', 'deskripsi_kegiatan', 'tanggal_mulai', 'tanggal_selesai', 'tanggal_acara', 'tempat_kegiatan', 'jenis_kegiatan')
            ->where('jenis_kegiatan', 'Kegiatan Non-JTI')
            ->whereHas('anggota', function ($query) use ($dosenId) {
                $query->where('id_user', $dosenId);
            });

        return DataTables::of($kegiatan)
            ->addIndexColumn()
            ->addColumn('aksi', function ($kegiatan) {
                $btn = '<button onclick="modalAction(\'' . url('/dosen/kegiatan/' . $kegiatan->id_kegiatan . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/dosen/kegiatan/' . $kegiatan->id_kegiatan . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/dosen/kegiatan/' . $kegiatan->id_kegiatan . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function show_ajaxDosenNonJTI($id)
    {
        $kegiatan = KegiatanModel::find($id);
        $angggota = anggotaModel::select('id_kegiatan', 'id_anggota', 'id_user', 'id_jabatan_kegiatan')->where('id_kegiatan', $id)->with('user', 'jabatan')->get();

        if (!$kegiatan) {
            return response()->json(['message' => 'Data not found'], 404);
        }


        return view('dosen.kegiatan.nonjti.show_ajax', ['kegiatan' => $kegiatan, 'anggota' => $angggota]);
    }

    public function confirm_ajaxAdmin($id)
    {
        $kegiatan = KegiatanModel::find($id);
        return view('admin.kegiatan.confirm_ajax', ['kegiatan' => $kegiatan]);
    }

    public function delete_ajaxAdmin($id)
    {
        if (request()->ajax() || request()->wantsJson()) {
            // Cari data kegiatan berdasarkan ID dengan eager loading
            $kegiatan = kegiatanModel::with(['agenda', 'anggota'])->find($id);

            if ($kegiatan) {
                try {
                    DB::beginTransaction();

                    // 1. Hapus semua agenda_anggota terkait
                    AgendaAnggotaModel::whereIn('id_agenda', $kegiatan->agenda->pluck('id_agenda'))
                        ->orWhereIn('id_anggota', $kegiatan->anggota->pluck('id_anggota'))
                        ->delete();

                    // 2. Hapus semua agenda terkait
                    $kegiatan->agenda()->delete();

                    // 3. Hapus semua anggota terkait
                    $kegiatan->anggota()->delete();

                    // 4. Hapus kegiatan
                    $kegiatan->delete();

                    DB::commit();

                    return response()->json([
                        'status' => true,
                        'message' => 'Kegiatan berhasil dihapus beserta semua data terkait'
                    ]);
                } catch (\Exception $e) {
                    DB::rollBack();

                    return response()->json([
                        'status' => false,
                        'message' => 'Gagal menghapus kegiatan. Terjadi kesalahan sistem.',
                        'detail' => $e->getMessage()
                    ], 500);
                }
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data kegiatan tidak ditemukan'
                ], 404);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Permintaan harus melalui AJAX'
            ], 400);
        }
    }

    public function getKegiatanEvents()
    {
        $kegiatan = KegiatanModel::select('id_kegiatan as id', 'nama_kegiatan as title', 'deskripsi_kegiatan as description', 'tanggal_mulai as start', 'tanggal_selesai as end')
            ->get();

        return response()->json($kegiatan);
    }

    // Proges Kegiatan
    public function progresKegiatan()
    {
        $breadcrumb = (object) [
            'title' => 'Progres Kegiatan',
            'list' => ['Home', 'Progres Kegiatan'],
        ];
        $activeMenu = 'progres kegiatan pic';

        $progresKegiatan = KegiatanModel::select('id_kegiatan', 'nama_kegiatan', 'progress')->get();

        return view('dosenPIC.progresKegiatan.index', compact('breadcrumb', 'activeMenu', 'progresKegiatan'));
    }

    public function editProgresKegiatan($id)
    {
        $kegiatan = KegiatanModel::find($id);
        if (!$kegiatan) {
            return response()->json(['status' => false, 'message' => 'Kegiatan tidak ditemukan'], 404);
        }

        return response()->json(['status' => true, 'data' => $kegiatan]);
    }

    public function updateProgresKegiatan(Request $request, $id)
    {
        $kegiatan = KegiatanModel::find($id);
        if (!$kegiatan) {
            return response()->json(['status' => false, 'message' => 'Kegiatan tidak ditemukan'], 404);
        }

        $kegiatan->update($request->all());

        return response()->json(['status' => true, 'message' => 'Progres kegiatan berhasil diperbarui']);
    }

    public function detailProgresKegiatan($id)
    {
        $kegiatan = KegiatanModel::find($id);
        if (!$kegiatan) {
            return response()->json(['status' => false, 'message' => 'Kegiatan tidak ditemukan'], 404);
        }

        return response()->json(['status' => true, 'data' => $kegiatan]);
    }

    public function listProgresKegiatan(Request $request)
    {
        if ($request->ajax()) {
            $data = KegiatanModel::select('id_kegiatan', 'nama_kegiatan', 'progress')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('aksi', function ($row) {
                    $btn = '<button class="btn btn-sm btn-primary" onclick="editProgressKegiatan(' . $row->id_kegiatan . ')">Edit</button>';
                    $btn .= ' <button class="btn btn-sm btn-info" onclick="detailProgressKegiatan(' . $row->id_kegiatan . ')">Detail</button>';
                    return $btn;
                })
                ->rawColumns(['aksi'])
                ->make(true);
        }
    }
}
