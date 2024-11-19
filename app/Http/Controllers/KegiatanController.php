<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KegiatanModel;
use App\Models\UserModel;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\AnggotaModel;
use App\Models\JabatanKegiatanModel;

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

    // function list (admin)
    public function list(Request $request)
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

    // function show (admin)
    public function show_ajaxAdmin($id)
    {
        $kegiatan = KegiatanModel::find($id);
        $angggota = anggotaModel::select('id_kegiatan','id_anggota','id_user', 'id_jabatan_kegiatan')->where('id_kegiatan', $id)->with('user', 'jabatan')->get();
    
        if (!$kegiatan) {
            return response()->json(['message' => 'Data not found'], 404);
        }
    
        return view('admin.kegiatan.show_ajax', ['kegiatan' => $kegiatan, 'anggota' => $angggota]);
    }

    public function create_ajaxAdmin()
    {
        $jenis_kegiatan = KegiatanModel::all();
        $jabatan = JabatanKegiatanModel::all();
        $anggota = UserModel::select('id_user', 'username', 'nama', 'email', 'NIP', 'level')->where('level' , 'dosen')->get();
        return view('admin.kegiatan.create_ajax', compact('jenis_kegiatan', 'jabatan', 'anggota'));
    }

    public function storeAdmin(Request $request)
    {
        if($request->ajax() || $request->wantsJson()){
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
}