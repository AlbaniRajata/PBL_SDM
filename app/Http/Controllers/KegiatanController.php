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
                $btn = '<button onclick="modalAction(\'' . url('/kegiatan/' . $kegiatan->id_kegiatan . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/kegiatan/' . $kegiatan->id_kegiatan . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/kegiatan/' . $kegiatan->id_kegiatan . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
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
}