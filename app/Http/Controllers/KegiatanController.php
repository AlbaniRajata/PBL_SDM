<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KegiatanModel;
use App\Models\UserModel;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\DomPDF\Facade\Pdf;

class KegiatanController extends Controller
{
    // function index
    public function admin(){
        $breadcrumb = (object) [
            'title' => 'Kegiatan',
            'list' => ['Home','Kegiatan Admin'],
        ];
        $activeMenu = 'kegiatan admin';
        return view('admin.kegiatan.index',['breadcrumb' => $breadcrumb, 'activeMenu' => $activeMenu]);
    }

    public function pimpinan(){
        $breadcrumb = (object) [
            'title' => 'Kegiatan',
            'list' => ['Home','Kegiatan Pimpinan'],
        ];
        $activeMenu = 'kegiatan pimpinan';
        return view('pimpinan.kegiatan.index',['breadcrumb' => $breadcrumb, 'activeMenu' => $activeMenu]);
    }

    public function dosenPIC(){
        $breadcrumb = (object) [
            'title' => 'Kegiatan',
            'list' => ['Home','Kegiatan PIC'],
        ];
        $activeMenu = 'kegiatan pic';
        return view('dosenPIC.kegiatan.index',['breadcrumb' => $breadcrumb, 'activeMenu' => $activeMenu]);
    }

    public function dosenAnggota(){
        $breadcrumb = (object) [
            'title' => 'Kegiatan',
            'list' => ['Home','Kegiatan Anggota'],
        ];
        $activeMenu = 'kegiatan anggota';
        return view('dosenAnggota.kegiatan.index',['breadcrumb' => $breadcrumb, 'activeMenu' => $activeMenu]);
    }

    // function create (admin)
    public function create_ajax()
    {
        $jenis_kegiatan = ['Kegiatan JTI', 'Kegiatan Non-JTI'];
        $pengguna = UserModel::select('id_user', 'nama')->get();
        return view('admin.kegiatan.create_ajax', ['jenis_kegiatan' => $jenis_kegiatan, 'pengguna' => $pengguna]);
    }

    public function list(Request $request)
    {
        $kegiatan = KegiatanModel::select(
            'id_kegiatan', 
            'nama_kegiatan', 
            'deskripsi_kegiatan', 
            'tanggal_mulai', 
            'tanggal_selesai', 
            'tanggal_acara', 
            'tempat_kegiatan', 
            'jenis_kegiatan', 
            'id_user'
        );

        if ($request->jenis_kegiatan) {
            $kegiatan->where('jenis_kegiatan', $request->jenis_kegiatan);
        }

        return DataTables::of($kegiatan)
            ->addIndexColumn()
            ->addColumn('aksi', function ($kegiatan) {
                $btn = '<button onclick="modalAction(\'' . url('/kegiatan/' . $kegiatan->id_kegiatan . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/kegiatan/' . $kegiatan->id_kegiatan . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/kegiatan/' . $kegiatan->id_kegiatan . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    // function store (admin)
    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'nama_kegiatan' => 'required|string|max:100',
                'deskripsi_kegiatan' => 'required|string',
                'tanggal_mulai' => 'required|date',
                'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
                'jenis_kegiatan' => 'required|string',
                'tempat_kegiatan' => 'required|string',
                'id_user' => 'required|integer|exists:t_user,id_user',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            $kegiatan = KegiatanModel::create([
                'nama_kegiatan' => $request->nama_kegiatan,
                'deskripsi_kegiatan' => $request->deskripsi_kegiatan,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
                'jenis_kegiatan' => $request->jenis_kegiatan,
                'tempat_kegiatan' => $request->tempat_kegiatan,
                'id_user' => $request->id_user,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Data kegiatan berhasil disimpan'
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Invalid request'
        ]);
    }
    
    // ini perlu dihapus tidak
    public function storeAdmin(Request $request){
        $request->validate([
            'nama_kegiatan' => 'required',
            'jenis_kegiatan' => 'required',
            'deskripsi_kegiatan' => 'required',
            'tanggal_acara' => 'required',
            'tempat_kegiatan' => 'required',
            'status' => 'required',
        ]);

        $kegiatan = new KegiatanModel;
        $kegiatan->nama = $request->nama;
        $kegiatan->jenis_kegiatan = $request->jenis_kegiatan;
        $kegiatan->deskripsi = $request->deskripsi;
        $kegiatan->tanggal_acara = $request->tanggal_acara;
        $kegiatan->tempat = $request->tempat;
        $kegiatan->status = $request->status;
        $kegiatan->save();

        return redirect()->back()->with('success','Kegiatan berhasil ditambahkan');
    }

    public function export_pdf()
    {
        $kegiatan = kegiatanModel::select('id_kegiatan', 
            'nama_kegiatan', 
            'deskripsi_kegiatan', 
            'tanggal_mulai', 
            'tanggal_selesai', 
            'tanggal_acara', 
            'tempat_kegiatan', 
            'jenis_kegiatan', 
            'id_user')
            ->get();
        $pdf = Pdf::loadView('admin.kegiatan.export_pdf', ['kegiatan' => $kegiatan]);
        $pdf->setPaper('a4', 'potrait');
        $pdf->setOption("isRemoteEnabled", true);
        $pdf->render();
        return $pdf->stream('Data Pengguna ' . date('Y-m-d H:i:s') . '.pdf');
    }
}
