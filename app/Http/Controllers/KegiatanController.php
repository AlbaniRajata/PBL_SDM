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
use App\Models\AgendaModel;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpWord\SimpleType\Jc;
use Illuminate\Validation\ValidationException;


class KegiatanController extends Controller
{
    // function index
    public function admin()
    {
        $breadcrumb = (object) [
            'title' => 'Data Kegiatan',
            'list' => ['Home', 'Kegiatan (Admin)'],
        ];
        $activeMenu = 'kegiatan admin'; // Ambil tahun unik dari tabel kegiatan

        $years = KegiatanModel::selectRaw('YEAR(tanggal_acara) as year')
            ->distinct()
            ->orderBy('year', 'asc')
            ->pluck('year'); // Hanya mengambil nilai tahun saja

        // Kirimkan data ke view
        return view('admin.kegiatan.index', [
            'breadcrumb' => $breadcrumb,
            'activeMenu' => $activeMenu,
            'years' => $years, // Tambahkan $years di sini
        ]);
    }

    public function pimpinan()
    {
        $breadcrumb = (object) [
            'title' => 'Data Kegiatan',
            'list' => ['Home', 'Kegiatan Pimpinan'],
        ];
        $activeMenu = 'kegiatan pimpinan';

        $years = KegiatanModel::selectRaw('YEAR(tanggal_acara) as year')
            ->distinct()
            ->orderBy('year', 'asc')
            ->pluck('year'); 

        return view('pimpinan.kegiatan.index', [
            'breadcrumb' => $breadcrumb,
            'activeMenu' => $activeMenu,
            'years' => $years, 
        ]);
    }

    public function getPeriodeKegiatan()
    {
        $years = KegiatanModel::selectRaw('YEAR(tanggal_acara) as year')
            ->distinct()
            ->orderBy('year', 'asc')
            ->pluck('year');

        return view('pimpinan.kegiatan.index', compact('years'));
    }

    public function dosen(): mixed
    {
        $user = Auth::user();  // Mengambil data pengguna yang sedang login
        $breadcrumb = (object) [
            'title' => 'Data Kegiatan Dosen ' . $user->nama,  // Menggunakan nama pengguna untuk title
            'list' => ['Home', 'Kegiatan Dosen'],
        ];
        $activeMenu = 'kegiatan dosen';
    
        $userId = $user->id;
    
        $kegiatanAkanDatang = KegiatanModel::whereHas('anggota', function ($query) use ($userId) {
            $query->where('id_user', $userId);
        })
            ->where('tanggal_mulai', '>=', now())
            ->get()
            ->map(function ($kegiatan) {
                $kegiatan->tanggal_mulai = Carbon::parse($kegiatan->tanggal_mulai);
                return $kegiatan;
            });
    
        return view('dosen.kegiatan.index', [
            'breadcrumb' => $breadcrumb,
            'activeMenu' => $activeMenu,
            'kegiatanAkanDatang' => $kegiatanAkanDatang
        ]);
    }
    

    public function data(Request $request)
    {
        // Ambil ID pengguna yang sedang login
        $userId = Auth::id();
    
        $query = DB::table('t_anggota AS anggota')
            ->join('t_kegiatan AS k', 'anggota.id_kegiatan', '=', 'k.id_kegiatan')
            ->join('t_jabatan_kegiatan AS jk', 'anggota.id_jabatan_kegiatan', '=', 'jk.id_jabatan_kegiatan')
            ->select(
                'k.nama_kegiatan',
                'k.deskripsi_kegiatan', 
                'k.tanggal_acara', 
                'k.tempat_kegiatan', 
                'k.jenis_kegiatan', 
                'jk.jabatan_nama'
            )
            ->where('anggota.id_user', $userId);
    
        // Filter by jenis_kegiatan if provided
        if ($request->has('jenis_kegiatan') && $request->jenis_kegiatan != '') {
            $query->where('k.jenis_kegiatan', $request->jenis_kegiatan);
        }
    
        return DataTables::of($query)
            ->addIndexColumn()
            ->make(true);
    }

    public function dosenPIC()
    {
        $breadcrumb = (object) [
            'title' => 'Data Kegiatan',
            'list' => ['Home', 'Kegiatan PIC'],
        ];
        $activeMenu = 'kegiatan pic';

        return view('dosenPIC.kegiatan.index', ['breadcrumb' => $breadcrumb, 'activeMenu' => $activeMenu]);
    }

    public function dosenAnggota()
    {
        $breadcrumb = (object) [
            'title' => 'Data Kegiatan',
            'list' => ['Home', 'Kegiatan Anggota'],
        ];

        $user = Auth::id(); // Define the $user variable

        $kegiatan = KegiatanModel::with(['dokumen'])
        ->whereHas('anggota', function ($query) use ($user) {
            $query->where('id_user', $user)
                  ->where('id_jabatan_kegiatan', '!=', 1);
        });

        $activeMenu = 'kegiatan anggota';
        return view('dosenAnggota.kegiatan.index', ['breadcrumb' => $breadcrumb, 'activeMenu' => $activeMenu, 'kegiatan' => $kegiatan]);
    }

    //Function Dosen Anggota
    public function dataDosenA(Request $request)
    {
        // Ambil ID pengguna yang sedang login
        $dosenId = auth()->user()->id_user;

        // Ambil data kegiatan di mana user login memiliki id_jabatan_kegiatan antara 2 hingga 6
        $query = KegiatanModel::with(['anggota.user', 'anggota.jabatan', 'dokumen'])
            ->whereHas('anggota', function ($query) use ($dosenId) {
                // Filter untuk user login dengan id_jabatan_kegiatan antara 2 hingga 6
                $query->where('id_user', $dosenId)
                ->where('id_jabatan_kegiatan', '!=' , 1);
            });

        // Filter berdasarkan jenis kegiatan jika ada
        if ($request->filled('jenis_kegiatan')) {
            $query->where('jenis_kegiatan', $request->jenis_kegiatan);
        }

        return DataTables::of($query)
            ->addIndexColumn()
            // Kolom PIC
            ->addColumn('pic', function ($row) {
                // Cari anggota dengan id_jabatan_kegiatan = 1
                $pic = $row->anggota->firstWhere('id_jabatan_kegiatan', 1);
                // Tampilkan nama user jika ditemukan, jika tidak tampilkan '-'
                return $pic && $pic->user ? $pic->user->nama : '-';
            })

            // Kolom Surat Tugas
            ->addColumn('surat_tugas', function ($row) {
                $dokumen = $row->dokumen->firstWhere('jenis_dokumen', 'Surat Tugas');
                if ($dokumen) {
                    return '<a href="' . url('storage/' . $dokumen->path) . '" class="btn btn-sm btn-primary" target="_blank">Unduh</a>';
                }
                return '-';
            })

            // Kolom Tanggal Mulai
            ->editColumn('tanggal_mulai', function ($row) {
                return $row->tanggal_mulai ? Carbon::parse($row->tanggal_mulai)->format('d-M-Y') : '-';
            })
            // Kolom Tanggal Selesai
            ->editColumn('tanggal_selesai', function ($row) {
                return $row->tanggal_selesai ? Carbon::parse($row->tanggal_selesai)->format('d-M-Y') : '-';
            })
            //kolom tempat acara
            ->editColumn('tempat_acara', function ($row) {
                return $row->tempat_kegiatan ? $row->tempat_kegiatan : '-';
            })

            // Kolom Aksi
            ->addColumn('aksi', function ($row) {
                $editUrl = url('/kegiatan/' . $row->id_kegiatan . '/edit_ajax');
                $deleteUrl = url('/kegiatan/' . $row->id_kegiatan . '/delete_ajax');

                $btn = '<div class="btn-group">';
                $btn .= '<button onclick="modalAction(\'' . $editUrl . '\')" class="btn btn-sm btn-primary">Edit</button>';
                $btn .= '<button onclick="deleteAction(\'' . $deleteUrl . '\')" class="btn btn-sm btn-danger">Delete</button>';
                $btn .= '</div>';

                return $btn;
            })
            // Izinkan kolom aksi mengandung HTML
            ->rawColumns(['aksi', 'surat_tugas'])
            ->make(true);
    }


    // function list
    public function listAdmin(Request $request)
    {
        $kegiatan = KegiatanModel::select('id_kegiatan', 'nama_kegiatan', 'deskripsi_kegiatan', 'tanggal_mulai', 'tanggal_selesai', 'tanggal_acara', 'tempat_kegiatan', 'jenis_kegiatan');

        if ($request->jenis_kegiatan) {
            $kegiatan->where('jenis_kegiatan', $request->jenis_kegiatan);
        }

        // Filter berdasarkan tahun dari kolom tanggal_acara
        if ($request->filled('periode')) {
            $tahun = $request->periode; // Tahun diambil dari request
            $kegiatan->whereYear('tanggal_acara', '=', $tahun);
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

        // Filter berdasarkan tahun dari kolom tanggal_acara
        if ($request->filled('periode')) {
            $tahun = $request->periode; // Tahun diambil dari request
            $kegiatan->whereYear('tanggal_acara', '=', $tahun);
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

    public function listDosenPIC(Request $request)
    {
        // Ambil ID pengguna yang sedang login
        $userId = Auth::id();

        // Ambil data kegiatan di mana pengguna adalah PIC
        $kegiatan = KegiatanModel::select('id_kegiatan', 'nama_kegiatan', 'deskripsi_kegiatan', 'tanggal_mulai', 'tanggal_selesai', 'tanggal_acara', 'tempat_kegiatan', 'jenis_kegiatan')
            ->whereHas('anggota', function ($query) use ($userId) {
                $query->where('id_user', $userId)
                    ->where('id_jabatan_kegiatan', '1'); // Pastikan kolom 'jabatan' ada di tabel anggota
            });

        if ($request->jenis_kegiatan) {
            $kegiatan->where('jenis_kegiatan', $request->jenis_kegiatan);
        }

        return DataTables::of($kegiatan)
            ->addIndexColumn()
            ->addColumn('aksi', function ($kegiatan) {
                $btn = '<button onclick="modalAction(\'' . url('/dosenPIC/kegiatan/' . $kegiatan->id_kegiatan . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/dosenPIC/kegiatan/' . $kegiatan->id_kegiatan . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
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
            // Ambil dokumen terbaru untuk id_kegiatan
    $dokumenTerbaru = DokumenModel::where('id_kegiatan', $id)
    ->where('jenis_dokumen', 'surat tugas')
    ->latest('created_at') // Urutkan berdasarkan waktu terbaru
    ->first(); // Ambil 1 dokumen terbaru

        if (!$kegiatan) {
            return response()->json(['message' => 'Data not found'], 404);
        }

        return view('admin.kegiatan.show_ajax', ['kegiatan' => $kegiatan, 'anggota' => $angggota, 'dokumenTerbaru'=>$dokumenTerbaru]);
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

        return view('dosenPIC.kegiatan.show_ajax', ['kegiatan' => $kegiatan, 'anggota' => $angggota]);
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
            $currentUserId = auth()->user()->id_user;

            if (!in_array($currentUserId, $request->anggota_id)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Anda harus menjadi anggota dalam kegiatan yang dibuat'
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
            $existingAnggotaIds = AnggotaModel::where('id_kegiatan', $id)->pluck('id_user')->toArray();

            foreach ($request->anggota_id as $index => $anggota_id) {
                if (in_array($anggota_id, $existingAnggotaIds)) {
                    // Update existing anggota
                    AnggotaModel::where('id_kegiatan', $id)
                        ->where('id_user', $anggota_id)
                        ->update([
                            'id_jabatan_kegiatan' => $request->jabatan_id[$index]
                        ]);
                } else {
                    // Create new anggota
                    AnggotaModel::create([
                        'id_kegiatan' => $id,
                        'id_user' => $anggota_id,
                        'id_jabatan_kegiatan' => $request->jabatan_id[$index],
                    ]);
                }
            }

            // Remove anggota that are no longer in the request
            AnggotaModel::where('id_kegiatan', $id)
                ->whereNotIn('id_user', $request->anggota_id)
                ->delete();

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

    public function confirmAjaxDosen($id)
    {
        $kegiatan = KegiatanModel::find($id);
        return view('dosen.kegiatan.confirm_ajax', ['kegiatan' => $kegiatan]);
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

    public function exportExcel()
    {
        // Retrieve all kegiatan with their members and user details
        $kegiatan = KegiatanModel::with(['anggota.user', 'anggota.jabatan'])->get();
    
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet(); 
    
        // Set Header Columns
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'ID Kegiatan');
        $sheet->setCellValue('C1', 'Nama Kegiatan');
        $sheet->setCellValue('D1', 'Tanggal Mulai');
        $sheet->setCellValue('E1', 'Tanggal Selesai');
        $sheet->setCellValue('F1', 'Nama Anggota');
        $sheet->setCellValue('G1', 'Jabatan');
    
        // Make header bold
        $sheet->getStyle('A1:G1')->getFont()->setBold(true);
    
        $no = 1; // Data number starts from 1
        $row = 2; // Data row starts from row 2
    
        // Iterate through all kegiatan
        foreach ($kegiatan as $keg) {
            // Check if there are members for this kegiatan
            if ($keg->anggota->count() > 0) {
                // Iterate through members of each kegiatan
                foreach ($keg->anggota as $anggota) {
                    $sheet->setCellValue('A' . $row, $no);
                    $sheet->setCellValue('B' . $row, $keg->id_kegiatan);
                    $sheet->setCellValue('C' . $row, $keg->nama_kegiatan);
                    $sheet->setCellValue('D' . $row, $keg->tanggal_mulai ? $keg->tanggal_mulai : '-');
                    $sheet->setCellValue('E' . $row, $keg->tanggal_selesai ? $keg->tanggal_selesai : '-');
                    
                    // Check if user and jabatan exist to prevent potential errors
                    $sheet->setCellValue('F' . $row, $anggota->user ? $anggota->user->nama : 'N/A');
                    $sheet->setCellValue('G' . $row, $anggota->jabatan ? $anggota->jabatan->jabatan : 'N/A');
                    
                    $row++;
                    $no++;
                }
            } else {
                // If no members, still show the kegiatan details
                $sheet->setCellValue('A' . $row, $no);
                $sheet->setCellValue('B' . $row, $keg->id_kegiatan);
                $sheet->setCellValue('C' . $row, $keg->nama_kegiatan);
                $sheet->setCellValue('D' . $row, $keg->tanggal_mulai ? $keg->tanggal_mulai : '-');
                $sheet->setCellValue('E' . $row, $keg->tanggal_selesai ? $keg->tanggal_selesai : '-');
                $sheet->setCellValue('F' . $row, 'Tidak ada anggota');
                $sheet->setCellValue('G' . $row, '-');
                
                $row++;
                $no++;
            }
        }
    
        // Set auto column width for all columns
        foreach (range('A', 'G') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
    
        // Set sheet title
        $sheet->setTitle('Data Semua Kegiatan');
    
        // Create writer
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data_Semua_Kegiatan_' . date('Y-m-d_H-i-s') . '.xlsx';
    
        // Set headers for download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');
    
        // Save file to output
        $writer->save('php://output');
        exit;
    }

    public function exportPdf_pic()
    {
        $kegiatan = KegiatanModel::select('id_kegiatan', 'nama_kegiatan', 'deskripsi_kegiatan', 'tanggal_mulai', 'tanggal_selesai', 'tanggal_acara', 'tempat_kegiatan', 'jenis_kegiatan')->get();
        $pdf = Pdf::loadView('dosenPIC.kegiatan.export_pdf', ['kegiatan' => $kegiatan]);
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption("isRemoteEnabled", true);
        $pdf->render();
        return $pdf->stream('Data Kegiatan ' . date('Y-m-d H:i:s') . '.pdf');
    }

    public function exportExcel_pic()
    {
        $kegiatan = DB::table('t_kegiatan')->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set document properties
        $spreadsheet->getProperties()->setCreator('YourAppName')
            ->setLastModifiedBy('YourAppName')
            ->setTitle('Daftar Kegiatan')
            ->setSubject('Daftar Kegiatan')
            ->setDescription('Daftar Kegiatan')
            ->setKeywords('pdf php')
            ->setCategory('Laporan');

        // Add some data
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Nama Kegiatan');
        $sheet->setCellValue('C1', 'Deskripsi Kegiatan');
        $sheet->setCellValue('D1', 'Tanggal Mulai');
        $sheet->setCellValue('E1', 'Tanggal Selesai');
        $sheet->setCellValue('F1', 'Tanggal Acara');
        $sheet->setCellValue('G1', 'Tempat Kegiatan');
        $sheet->setCellValue('H1', 'Jenis Kegiatan');

        // Make header bold
        $sheet->getStyle('A1:H1')->getFont()->setBold(true);

        // Populate data
        $row = 2;
        foreach ($kegiatan as $index => $item) {
            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $item->nama_kegiatan);
            $sheet->setCellValue('C' . $row, $item->deskripsi_kegiatan);
            $sheet->setCellValue('D' . $row, $item->tanggal_mulai);
            $sheet->setCellValue('E' . $row, $item->tanggal_selesai);
            $sheet->setCellValue('F' . $row, $item->tanggal_acara);
            $sheet->setCellValue('G' . $row, $item->tempat_kegiatan);
            $sheet->setCellValue('H' . $row, $item->jenis_kegiatan);
            $row++;
        }

        // Write the file
        $writer = new Xlsx($spreadsheet);
        $fileName = 'Daftar_Kegiatan_' . date('Y-m-d_H:i:s') . '.xlsx';

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;
    }

    public function exportPdf_dosen()
    {
                // Ambil ID pengguna yang sedang login
                $userId = Auth::id();
    
                $kegiatan = DB::table('t_anggota AS anggota')
                    ->join('t_kegiatan AS k', 'anggota.id_kegiatan', '=', 'k.id_kegiatan')
                    ->join('t_jabatan_kegiatan AS jk', 'anggota.id_jabatan_kegiatan', '=', 'jk.id_jabatan_kegiatan')
                    ->select(
                        'k.nama_kegiatan',
                        'k.deskripsi_kegiatan', 
                        'k.tanggal_acara', 
                        'k.tanggal_mulai',
                        'k.tanggal_selesai',
                        'k.tempat_kegiatan', 
                        'k.jenis_kegiatan', 
                    )
                    ->where('anggota.id_user', $userId)
                    ->get();

        $pdf = Pdf::loadView('dosen.kegiatan.export_pdf', ['kegiatan' => $kegiatan]);
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption("isRemoteEnabled", true);
        $pdf->render();

        return $pdf->stream('Data Kegiatan ' . date('Y-m-d H:i:s') . '.pdf');
    }


    public function exportExcel_dosen()
    {
        $userId = Auth::id();

    $user = DB::table('m_user')->where('id_user', $userId)->first();
    $userName = $user->username; // Assuming 'name' is the column storing the user's name
    
    $kegiatan = DB::table('t_anggota AS anggota')
        ->join('t_kegiatan AS k', 'anggota.id_kegiatan', '=', 'k.id_kegiatan')
        ->join('t_jabatan_kegiatan AS jk', 'anggota.id_jabatan_kegiatan', '=', 'jk.id_jabatan_kegiatan')
        ->select(
            'k.nama_kegiatan',
            'k.deskripsi_kegiatan', 
            'k.tanggal_acara', 
            'k.tanggal_mulai',
            'k.tanggal_selesai',
            'k.tempat_kegiatan', 
            'k.jenis_kegiatan', 
            )
        ->where('anggota.id_user', $userId)
        ->get();                   

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $spreadsheet->getProperties()->setCreator('YourAppName')
            ->setLastModifiedBy('YourAppName')
            ->setTitle('Daftar Kegiatan')
            ->setSubject('Daftar Kegiatan')
            ->setDescription('Daftar Kegiatan')
            ->setKeywords('pdf php')
            ->setCategory('Laporan');

        // Add some data
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Nama Kegiatan');
        $sheet->setCellValue('C1', 'Deskripsi Kegiatan');
        $sheet->setCellValue('D1', 'Tanggal Mulai');
        $sheet->setCellValue('E1', 'Tanggal Selesai');
        $sheet->setCellValue('F1', 'Tanggal Acara');
        $sheet->setCellValue('G1', 'Tempat Kegiatan');
        $sheet->setCellValue('H1', 'Jenis Kegiatan');

        // Make header bold
        $sheet->getStyle('A1:H1')->getFont()->setBold(true);

        // Populate data
        $row = 2;
        foreach ($kegiatan as $index => $item) {
            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $item->nama_kegiatan);
            $sheet->setCellValue('C' . $row, $item->deskripsi_kegiatan);
            $sheet->setCellValue('D' . $row, $item->tanggal_mulai);
            $sheet->setCellValue('E' . $row, $item->tanggal_selesai);
            $sheet->setCellValue('F' . $row, $item->tanggal_acara);
            $sheet->setCellValue('G' . $row, $item->tempat_kegiatan);
            $sheet->setCellValue('H' . $row, $item->jenis_kegiatan);
            $row++;
        }

        // Write the file
        $writer = new Xlsx($spreadsheet);
        $fileName = 'daftar_kegiatan_dosen' . strtolower(str_replace(' ', '_', $userName)) . '.xlsx';

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;
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
    public function uploadSurat(Request $request)
    {
        try {
            // Validasi file yang diupload dengan pesan error khusus
            $validator = Validator::make($request->all(), [
                'file' => [
                    'required',
                    'file',
                    function ($attribute, $value, $fail) {
                        // Cek ekstensi file
                        $allowedExtensions = ['pdf', 'doc', 'docx', 'xls', 'xlsx'];
                        $extension = $value->getClientOriginalExtension();
                        if (!in_array(strtolower($extension), $allowedExtensions)) {
                            $fail('Tipe file tidak diizinkan. Hanya file PDF, DOC, DOCX, XLS, dan XLSX yang diperbolehkan.');
                        }
        
                        // Cek ukuran file (2MB = 2048 KB)
                        $maxFileSize = 2048; // dalam KB
                        $fileSize = $value->getSize() / 1024; // konversi ke KB
                        if ($fileSize > $maxFileSize) {
                            $fail('Ukuran file terlalu besar. Maksimal 2 MB.');
                        }
                    },
                ],
                'id_kegiatan' => 'required|exists:t_kegiatan,id_kegiatan',
            ]);
        
            // Jika validasi gagal, lempar exception
            if ($validator->fails()) {
                throw new ValidationException($validator);
            }
        
            // Periksa apakah file ada
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                
                // Buat nama file unik
                $filename = time() . '_' . $file->getClientOriginalName();
                
                // Simpan file di direktori 'public/dokumen'
                $path = $file->storeAs('dokumen', $filename, 'public');
                
                // Buat record dokumen di database
                $dokumen = DokumenModel::create([
                    'id_kegiatan' => $request->id_kegiatan,
                    'nama_dokumen' => $file->getClientOriginalName(),
                    'jenis_dokumen' => 'surat tugas',
                    'file_path' => $path,
                    'progress' => 0, // Progress awal
                ]);
        
                // Kembalikan respon sukses dengan SweetAlert
                return back()->with('swal', [
                    'title' => 'Berhasil!',
                    'text' => 'File berhasil diupload.',
                    'icon' => 'success'
                ]);
            }
        
            // Jika tidak ada file
            return back()->with('swal', [
                'title' => 'Gagal!',
                'text' => 'Tidak ada file yang diupload.',
                'icon' => 'error'
            ]);
        
        } catch (ValidationException $e) {
            // Tangani kesalahan validasi dengan SweetAlert
            $errors = $e->validator->errors()->all();
            return back()->with('swal', [
                'title' => 'Validasi Gagal!',
                'text' => implode('\n', $errors),
                'icon' => 'error'
            ]);
        } catch (\Exception $e) {
            // Tangani kesalahan umum dengan SweetAlert
            return back()->with('swal', [
                'title' => 'Gagal!',
                'text' => 'Gagal mengupload file: ' . $e->getMessage(),
                'icon' => 'error'
            ]);
        }
    }

    public function downloadSurat($id_dokumen)
    {
        try {
            // Cari dokumen berdasarkan ID
            $dokumen = DokumenModel::findOrFail($id_dokumen);
    
            // Periksa apakah jenis dokumen adalah 'surat tugas'
            if ($dokumen->jenis_dokumen !== 'surat tugas') {
                return back()->with('error', 'Dokumen ini bukan surat tugas dan tidak dapat diunduh.');
            }
    
            // Dapatkan path lengkap file
            $filePath = storage_path('app/public/' . $dokumen->file_path);
    
            // Pastikan file ada di server
            if (!file_exists($filePath)) {
                return back()->with('error', 'File tidak ditemukan di server.');
            }
    
            // Return file download
            return response()->download($filePath, $dokumen->nama_dokumen);
        } catch (\Exception $e) {
            // Tangani error
            return back()->with('error', 'Gagal mendownload file: ' . $e->getMessage());
        }
    }
    

    // fungsi agenda kegiatan
    public function agendaAnggota()
    {
        $breadcrumb = (object) [
            'title' => 'Agenda Anggota',
            'list' => ['Home', 'Agenda Anggota'],
        ];
        $activeMenu = 'agenda anggota';

        // Render view untuk halaman pertama kali
        return view('dosenPIC.agendaAnggota.index', [
            'breadcrumb' => $breadcrumb,
            'activeMenu' => $activeMenu,
        ]);
    }

    public function listAgendaAnggota()
    {
        $user = Auth::id();
        $kegiatan = KegiatanModel::with(['anggota.user'])
        ->whereHas('anggota', function ($query) use ($user) {
            $query->where('id_user', $user)
                ->where('id_jabatan_kegiatan', '1');
        })
        ->select([
            'id_kegiatan', 
            'nama_kegiatan', 
            'jenis_kegiatan', 
            'tempat_kegiatan', 
            'tanggal_mulai', 
            'tanggal_selesai'
        ])
        ->withCount('anggota')
        ->withCount('anggota AS total_anggota')
        ->addSelect(DB::raw('(@rownum := @rownum + 1) AS DT_RowIndex'));


        // Mengembalikan data ke DataTables
        return DataTables::of($kegiatan)
            ->addIndexColumn()
            ->addColumn('anggota', function ($kegiatan) {
                // Ambil nama anggota
                $anggota = $kegiatan->anggota->pluck('user.nama')->join(', ');
                return $anggota;
            })
            ->addColumn('aksi', function ($kegiatan) {
                $btn = '<button onclick="modalAction(\'' . url('/dosenPIC/agendaAnggota/' . $kegiatan->id_kegiatan . '/create_ajax') . '\')" class="btn btn-warning btn-sm">Agenda</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/dosenPIC/agendaAnggota/' . $kegiatan->id_kegiatan . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                return $btn;
            })
            ->rawColumns(['aksi', 'anggota'])
            ->make(true);
    }

    public function createAgendaAnggota(Request $request, $id_kegiatan)
    {
        // Ambil data kegiatan berdasarkan ID kegiatan
        $kegiatan = KegiatanModel::findOrFail($id_kegiatan);
        
        // Cek apakah agenda sudah dibuat sebelumnya
        $agenda = AgendaModel::where('id_kegiatan', $id_kegiatan)->first();
        
        // Jika belum ada agenda, buat agenda baru
        if (!$agenda) {
            $agenda = new AgendaModel();
            $agenda->id_kegiatan = $id_kegiatan;
            $agenda->save();
        }
        
        // Ambil semua anggota berdasarkan ID kegiatan
        $anggota = AnggotaModel::with('user:id_user,nama')
            ->where('id_kegiatan', $id_kegiatan)
            ->where('id_jabatan_kegiatan', '!=', 1)
            ->get();
    
        // Cek apakah sudah ada agenda untuk setiap anggota
        $anggotaDenganAgenda = $anggota->map(function ($a) use ($agenda) {
            $agendaAnggota = AgendaAnggotaModel::where('id_anggota', $a->id_anggota)
                ->where('id_agenda', $agenda->id_agenda)
                ->first();
            
            $a->setAttribute('agenda_sudah_dibuat', isset($agendaAnggota) ? true : false);
            return $a;
        });
        
        // Kirim data ke view untuk input agenda anggota
        return view('dosenPIC.agendaAnggota.create_ajax', [
            'nama_kegiatan' => $kegiatan->nama_kegiatan,
            'id_kegiatan' => $id_kegiatan,
            'id_agenda' => $agenda->id_agenda,
            'anggota' => $anggotaDenganAgenda,
            'agenda_sudah_ada' => $anggotaDenganAgenda->contains('agenda_sudah_dibuat', true)
        ]);
    }

    public function storeAgendaAnggota(Request $request)
    {
        // Validasi data yang diterima
        $validated = $request->validate([
            'id_anggota.*' => 'required|exists:t_anggota,id_anggota',
            'agenda.*' => 'required|string|max:255',
            'id_agenda' => 'required|exists:t_agenda,id_agenda',
        ]);

        // Cek apakah sudah ada agenda untuk salah satu anggota
        $existingAgenda = AgendaAnggotaModel::whereIn('id_anggota', $validated['id_anggota'])
            ->where('id_agenda', $validated['id_agenda'])
            ->exists();

        if ($existingAgenda) {
            return response()->json([
                'status' => false,
                'message' => 'Agenda untuk beberapa anggota sudah pernah ditambahkan sebelumnya.',
                'msgField' => []
            ]);
        }

        // Proses penyimpanan agenda untuk setiap anggota
        foreach ($validated['agenda'] as $index => $agendaNama) {
            AgendaAnggotaModel::create([
                'id_anggota' => $validated['id_anggota'][$index],
                'nama_agenda' => $agendaNama,
                'id_agenda' => $validated['id_agenda'],
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Agenda anggota berhasil disimpan!',
        ]);
    }

    public function detailAgendaAnggota($id_kegiatan)
    {
        // Ambil data kegiatan beserta agendanya
        $kegiatan = KegiatanModel::with('agenda')->findOrFail($id_kegiatan);

        $agendaAnggota = DB::table('m_agenda_anggota as aa')
        ->join('t_agenda as ag', 'aa.id_agenda', '=', 'ag.id_agenda')
        ->leftJoin('m_dokumen as dkm', 'dkm.id_dokumen', '=', 'aa.id_dokumen')
        ->select('aa.id_agenda', 'aa.id_dokumen', 'dkm.file_path', 'dkm.nama_dokumen', 'aa.nama_agenda')
        ->where('ag.id_kegiatan', $id_kegiatan)
        ->get();
           
        // Breadcrumb dan metadata
        $breadcrumb = (object) [
            'title' => 'Detail Anggota',
            'list' => ['Home', 'Agenda Anggota', 'Detail'],
        ];
    
        $page = (object) [
            'title' => 'Detail Agenda Anggota',
        ];
    
        $activeMenu = 'agenda anggota';
    
        // Kirim data ke view
        return view('dosenPIC.agendaAnggota.show_ajax', [
            'kegiatan' => $kegiatan,
            'agendaAnggota' => $agendaAnggota,
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu,
        ])->render();
    }

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
        $user = Auth::user();

        $breadcrumb = (object) [
            'title' => 'Daftar Kegiatan Non-JTI Dosen ' . $user->nama,
            'list' => ['Home', 'Kegiatan Dosen Non JTI'],
        ];
        $activeMenu = 'kegiatan non jti';
        $userId = $user->id;
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
        // Mengambil data kegiatan dengan select kolom yang diperlukan
        $kegiatan = KegiatanModel::select('id_kegiatan as id', 'nama_kegiatan as title', 'deskripsi_kegiatan as description', 'tanggal_acara as start', 'tanggal_acara as end', 'jenis_kegiatan')
            ->get();
    
        // Menambahkan warna ke setiap event berdasarkan kategori_kegiatan
        $kegiatan->map(function ($item) {
            // Menentukan warna berdasarkan kategori
            switch ($item->jenis_kegiatan) {
                case 'Kegiatan JTI':
                    $item->color = '#6777EF';  // Warna untuk kategori JTI
                    break;
                case 'Kegiatan Non-JTI':
                    $item->color = '#FFAE03';  // Warna untuk kategori Non-JTI
                    break;
            }
    
            return $item;
        });
    
        // Mengembalikan data kegiatan dalam format JSON, termasuk warna
        return response()->json($kegiatan);
    }
    

    // Proges Kegiatan
    public function ProgresKegiatan()
    {
        $breadcrumb = (object) [
            'title' => 'Progres Kegiatan',
            'list' => ['Home', 'Progres Kegiatan'],
        ];
        $activeMenu = 'progres kegiatan pic';

        $progresKegiatan = KegiatanModel::select('id_kegiatan', 'nama_kegiatan', 'progress');

        return view('dosenPIC.progresKegiatan.index', compact('breadcrumb', 'activeMenu', 'progresKegiatan'));
    }

    public function listProgresKegiatan(Request $request)
    {
        if ($request->ajax()) {
            // Ambil ID pengguna yang sedang login
            $userId = Auth::id();

            // Ambil data kegiatan yang diikuti oleh pengguna yang sedang login
            $data = KegiatanModel::select('id_kegiatan', 'nama_kegiatan', 'progress')
                ->whereHas('anggota', function ($query) use ($userId) {
                    $query->where('id_user', $userId)
                        ->where('id_jabatan_kegiatan', '1');
                })
                ->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('aksi', function ($row) {
                    $btn = '<button onclick="modalAction(\'' . url('/dosenPIC/progresKegiatan/' . $row->id_kegiatan . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                    $btn .= '<button onclick="modalAction(\'' . url('/dosenPIC/progresKegiatan/' . $row->id_kegiatan . '/detail_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                    return $btn;
                })
                ->rawColumns(['aksi'])
                ->make(true);
        }
    }

    public function edit_ajax($id)
    {
        $kegiatan = KegiatanModel::select('id_kegiatan', 'nama_kegiatan', 'progress')->where('id_kegiatan', $id)->first();
        return view('dosenPIC.progresKegiatan.edit_ajax', ['kegiatan' => $kegiatan]);
    }

    public function update_ajax(Request $request, $id)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'progress' => 'required|numeric|min:0|max:100'
        ], [
            'progress.required' => 'Progress harus diisi',
            'progress.numeric' => 'Progress harus berupa angka',
            'progress.min' => 'Progress minimal 0',
            'progress.max' => 'Progress maksimal 100'
        ]);

        // Jika validasi gagal
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ], 400);
        }

        try {
            // Cari kegiatan
            $kegiatan = KegiatanModel::findOrFail($id);

            // Update progress
            $kegiatan->update([
                'progress' => $request->input('progress')
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Progress kegiatan berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal memperbarui progress kegiatan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show_ajax($id)
    {
        $kegiatan = KegiatanModel::select('id_kegiatan', 'nama_kegiatan', 'progress')->where('id_kegiatan', $id)->first();
        return view('dosenPIC.progresKegiatan.show_ajax', ['kegiatan' => $kegiatan]);
    }

    public function import(){
        return view('admin.kegiatan.import');
    }

    public function import_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            // Validasi file
            $validator = Validator::make($request->all(), [
                'file_user' => 'required|mimes:xlsx|max:1024',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            try {
                $file = $request->file('file_user');
                $reader = IOFactory::createReader('Xlsx');
                $reader->setReadDataOnly(true);
                $spreadsheet = $reader->load($file->getRealPath());
                $data = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

                // Validasi struktur kolom
                $headers = $data[1];
                $requiredHeaders = ['A' => 'Nama Kegiatan', 'B' => 'Jenis Kegiatan', 'C' => 'Deskripsi', 
                                    'D' => 'Tanggal Acara', 'E' => 'Tanggal Mulai', 'F' => 'Tanggal Selesai'];
                
                foreach ($requiredHeaders as $col => $header) {
                    if (!isset($headers[$col]) || strtolower(trim($headers[$col])) !== strtolower($header)) {
                        return response()->json([
                            'status' => false,
                            'message' => "Format file tidak sesuai. Kolom $header tidak ditemukan."
                        ]);
                    }
                }

                // Mulai transaksi database
                DB::beginTransaction();

                $successCount = 0;
                $errorCount = 0;
                $errors = [];

                // Proses setiap baris data
                for ($i = 2; $i <= count($data); $i++) {
                    try {
                        $row = $data[$i];

                        // Validasi data yang diperlukan
                        if (empty($row['A']) || empty($row['B']) || empty($row['C']) || 
                            empty($row['D']) || empty($row['E']) || empty($row['F'])) {
                            $errorCount++;
                            $errors[] = "Baris $i: Data tidak lengkap";
                            continue;
                        }

                        // Buat kegiatan
                        $kegiatan = KegiatanModel::create([
                            'nama_kegiatan' => $row['A'],
                            'jenis_kegiatan' => $row['B'],
                            'deskripsi_kegiatan' => $row['C'],
                            'tanggal_acara' => $this->convertToDate($row['D']),
                            'tanggal_mulai' => $this->convertToDate($row['E']),
                            'tanggal_selesai' => $this->convertToDate($row['F']),
                            'progress' => 0 // Default progress
                        ]);

                        // Tambahkan anggota
                        if (isset($row['G']) && !empty($row['G'])) {
                            // Misalkan kolom G berisi ID user
                            $anggotaId = $row['G'];
                            AnggotaModel::create([
                                'id_kegiatan' => $kegiatan->id_kegiatan,
                                'id_user' => $anggotaId,
                                'id_jabatan_kegiatan' => 1 // Default jabatan atau sesuaikan
                            ]);
                        }

                        $successCount++;
                    } catch (\Exception $rowError) {
                        $errorCount++;
                        $errors[] = "Baris $i: " . $rowError->getMessage();
                    }
                }

                // Commit transaksi
                DB::commit();

                // Siapkan respons
                $response = [
                    'status' => true,
                    'message' => "Import berhasil. Berhasil: $successCount, Gagal: $errorCount"
                ];

                // Tambahkan detail error jika ada
                if (!empty($errors)) {
                    $response['errors'] = $errors;
                }

                return response()->json($response);

            } catch (\Exception $e) {
                // Rollback transaksi jika terjadi kesalahan
                DB::rollBack();

                return response()->json([
                    'status' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ]);
            }
        }

        return redirect()->back();
    }

    // Metode bantuan untuk konversi tanggal
    private function convertToDate($dateString)
    {
        try {
            // Coba parse berbagai format tanggal
            return Carbon::parse($dateString)->format('Y-m-d');
        } catch (\Exception $e) {
            throw new \Exception("Format tanggal tidak valid: $dateString");
        }
    }

    public function agenda() {
        $breadcrumb = (object) [
            'title' => 'Agenda Kegiatan',
            'list' => ['Home', 'Agenda Kegiatan'],
        ];
        $activeMenu = 'agenda kegiatan';

        return view('dosenAnggota.agenda.index', ['breadcrumb' => $breadcrumb, 'activeMenu' => $activeMenu]);
    }
    
    public function listAgendaKegiatan(Request $request) 
    {
        if ($request->ajax()) {
            $userId = auth()->user()->id_user;

            $data = AgendaAnggotaModel::select('m_agenda_anggota.id_agenda_anggota', 'm_agenda_anggota.nama_agenda', 't_kegiatan.nama_kegiatan')
                ->join('t_agenda', 'm_agenda_anggota.id_agenda', '=', 't_agenda.id_agenda') // Join dengan tabel agenda
                ->join('t_kegiatan', 't_agenda.id_kegiatan', '=', 't_kegiatan.id_kegiatan') // Join dengan tabel kegiatan
                ->join('t_anggota', 'm_agenda_anggota.id_anggota', '=', 't_anggota.id_anggota') // Join dengan tabel anggota
                ->where('t_anggota.id_user', $userId) // Filter berdasarkan user yang login
                ->where('t_anggota.id_jabatan_kegiatan', '!=', 1) // Filter id_jabatan_kegiatan antara 2 hingga 6
                ->get(); // Ambil hasil query

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('agenda', function ($row) {
                    return $row->nama_agenda; // Correct column name
                })
                ->addColumn('aksi', function ($row) {
                    $btn = '<button onclick="uploadKegiatan(' . $row->id_agenda_anggota . ')" class="btn btn-sm btn-primary">Upload Kegiatan</button>'; // Correct button generation
                    return $btn;
                })
                ->rawColumns(['aksi'])
                ->make(true);
        }

        return response()->json([
            'status' => false,
            'message' => 'Invalid request',
        ], 400);
    }

    public function upload_dokumen(Request $request) {

        // Validator for file upload
        $validator = Validator::make($request->all(), [
            'id_agenda_anggota' => [
                'required',
                'exists:m_agenda_anggota,id_agenda_anggota', // Ensure id_agenda_anggota exists in the table
            ],
            'file' => 'required|mimes:jpeg,jpg,pdf|max:2048', // 2MB max
        ]);
    
        // Check validation
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi Gagal',
                'errors' => $validator->errors()
            ], 400);
        }
    
        try {
            // Get the uploaded file
            $file = $request->file('file');
            
            // Generate unique filename
            $originalName = $file->getClientOriginalName();
            $fileName = time() . '_' . $originalName;
            
            // Store file in public/dokumen directory
            $filePath = $file->storeAs('dokumen', $fileName, 'public');
    
            // Check if the document already exists for this agenda_anggota
            $agendaAnggota = AgendaAnggotaModel::with('agenda')->find($request->id_agenda_anggota);
    
            // Create a new dokumen record
            $dokumen = new DokumenModel();
            $dokumen->id_kegiatan = $agendaAnggota->agenda->id_kegiatan;
            $dokumen->nama_dokumen = $originalName;
            $dokumen->file_path = 'dokumen/' . $fileName; // Relative path
            $dokumen->jenis_dokumen = 'agenda'; // Set jenis_dokumen
            $dokumen->progress = 0; // Initial progress
            $dokumen->save();
    
            // Update id_dokumen in agenda_anggota
            $agendaAnggota->id_dokumen = $dokumen->id_dokumen;
            $agendaAnggota->save();
    
            return response()->json([
                'status' => true,
                'message' => 'Upload Dokumen Berhasil',
                'data' => $dokumen
            ]);
    
        } catch (\Exception $e) {
            // Log the error
            Log::error('Document Upload Error: ' . $e->getMessage());
    
            return response()->json([
                'status' => false,
                'message' => 'Gagal Upload Dokumen',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    

    public function download_dokumen($id_dokumen)
    {
        try {
            // Cari dokumen berdasarkan ID
            $dokumen = DokumenModel::find($id_dokumen);

            // Periksa apakah dokumen ditemukan
            if (!$dokumen) {
                return response()->json([
                    'status' => false,
                    'message' => 'Dokumen tidak ditemukan'
                ], 404);
            }

            // Dapatkan path file dari dokumen
            $filePath = storage_path('app/public/' . $dokumen->file_path);

            // Periksa apakah file ada di server
            if (!file_exists($filePath)) {
                return response()->json([
                    'status' => false,
                    'message' => 'File tidak ditemukan pada server'
                ], 404);
            }

            // Return file untuk didownload
            return response()->download($filePath, $dokumen->nama_dokumen);
        } catch (\Exception $e) {
            // Log error jika ada masalah
            Log::error('Download Dokumen Error: ' . $e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'Gagal mendownload dokumen',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}