<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserModel;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\DomPDF\Facade\Pdf;

class UserController extends Controller
{
    // function index
    public function admin(){
        $breadcrumb = (object) [
            'title' => 'Pengguna',
            'list' => ['Home','Daftar Pengguna'],
        ];
        $activeMenu = 'user admin';
        return view('admin.user.index',['breadcrumb' => $breadcrumb, 'activeMenu' => $activeMenu]);
    }

    public function pimpinan(){
        $breadcrumb = (object) [
            'title' => 'Pengguna',
            'list' => ['Home','Data Pengguna'],
        ];
        $activeMenu = 'user pimpinan';
        return view('pimpinan.user.index',['breadcrumb' => $breadcrumb, 'activeMenu' => $activeMenu]);
    }

    // function list (admin)
    public function list(Request $request)
    {
        $user = UserModel::select('id_user', 'username', 'nama', 'email', 'NIP', 'level', 'poin');

        if ($request->level) {
            $user->where('level', $request->level);
        }
        return DataTables::of($user)
            ->addIndexColumn()
            ->addColumn('aksi', function ($user) {
                $btn = '<button onclick="modalAction(\'' . url('/user/' . $user->id_user . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/user/' . $user->id_user . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/user/' . $user->id_user . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    // function export (admin)
    public function exportPdf()
    {
        $user = UserModel::select('id_user', 'username', 'nama', 'email', 'NIP', 'level', 'poin')->get();
        $pdf = Pdf::loadView('admin.user.export_pdf', ['user' => $user]);
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption("isRemoteEnabled", true);
        $pdf->render();
        return $pdf->stream('Data Pengguna ' . date('Y-m-d H:i:s') . '.pdf');
    }
}
