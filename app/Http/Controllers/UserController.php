<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserModel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\DomPDF\Facade\Pdf;

class UserController extends Controller
{
    public function admin()
    {
        $breadcrumb = (object) [
            'title' => 'Pengguna',
            'list' => ['Home', 'Data Pengguna'],
        ];
        $activeMenu = 'user admin';
        return view('admin.user.index', ['breadcrumb' => $breadcrumb, 'activeMenu' => $activeMenu]);
    }

    public function pimpinan()
    {
        $breadcrumb = (object) [
            'title' => 'Pengguna',
            'list' => ['Home', 'Data Pengguna'],
        ];
        $activeMenu = 'user pimpinan';
        return view('pimpinan.user.index', ['breadcrumb' => $breadcrumb, 'activeMenu' => $activeMenu]);
    }

    public function levelAdmin()
    {
        $breadcrumb = (object) [
            'title' => 'Jenis Pengguna',
            'list' => ['Home', 'Data Jenis Pengguna'],
        ];
        $activeMenu = 'user jenis';
        return view('admin.jenispengguna.index', ['breadcrumb' => $breadcrumb, 'activeMenu' => $activeMenu]);
    }

    // function list (admin)
    public function list(Request $request)
    {
        $user = UserModel::select('id_user', 'username', 'nama', 'email', 'NIP', 'level');

        if ($request->level) {
            $user->where('level', $request->level);
        }

        return DataTables::of($user)
            ->addIndexColumn()
            ->addColumn('aksi', function ($user) {
                $btn = '<button onclick="modalAction(\'' . route('admin.user.show_ajax', $user->id_user) . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . route('admin.user.edit_ajax', $user->id_user) . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . route('admin.user.delete_ajax', $user->id_user) . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create_ajaxAdmin()
    {
        $levels = ['admin', 'user', 'pimpinan'];
        return view('admin.user.create_ajax', compact('levels'));
    }

    public function store_ajaxAdmin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255|unique:t_user',
            'nama' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'email' => 'required|string|email|max:255|unique:t_user',
            'password' => 'required|string|min:8',
            'NIP' => 'required|string|max:255|unique:t_user',
            'level' => 'required|string|in:admin,user,pimpinan',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()->first()]);
        }

        $user = UserModel::create([
            'username' => $request->username,
            'nama' => $request->nama,
            'tanggal_lahir' => $request->tanggal_lahir,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'NIP' => $request->NIP,
            'level' => $request->level,
        ]);

        return response()->json(['status' => true, 'message' => 'User created successfully']);
    }

    // Function to show user details via AJAX (admin)
    public function show_ajaxAdmin(string $id)
    {
        $user = UserModel::find($id);

        if (!$user) {
            return response()->json(['error' => 'Data not found'], 404);
        }

        return view('admin.user.show_ajax', compact('user'));
    }

    // Function to edit user details via AJAX (admin)
    public function edit_ajaxAdmin(string $id)
    {
        $user = UserModel::find($id);

        if (!$user) {
            return response()->json(['error' => 'Data not found'], 404);
        }

        return view('admin.user.edit_ajax', compact('user'));
    }

    // Function to delete user via AJAX (admin)
    public function delete_ajaxAdmin(string $id)
    {
        $user = UserModel::find($id);

        if (!$user) {
            return response()->json(['error' => 'Data not found'], 404);
        }

        $user->delete();

        return response()->json(['success' => 'User deleted successfully']);
    }

    // function export (admin)
    public function exportPdf()
    {
        $user = UserModel::select('id_user', 'username', 'nama', 'email', 'NIP', 'level')->get();
        $pdf = Pdf::loadView('admin.user.export_pdf', ['user' => $user]);
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption("isRemoteEnabled", true);
        $pdf->render();
        return $pdf->stream('Data Pengguna ' . date('Y-m-d H:i:s') . '.pdf');
    }
}