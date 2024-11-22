<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JabatanKegiatanModel;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class JabatanKegiatanController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Jenis Jabatan',
            'list' => ['Home', 'Data Jabatan'],
        ];
        $activeMenu = 'jabatan kegiatan';
        return view('admin.jabatan.index', ['breadcrumb' => $breadcrumb, 'activeMenu' => $activeMenu]);
    }

    public function list(Request $request)
    {
        $jabatan = JabatanKegiatanModel::all();

        return DataTables::of($jabatan)
            ->addIndexColumn()
            ->addColumn('aksi', function ($jabatan) {
                $btn = '<button onclick="modalAction(\'' . url('/admin/jabatan/' . $jabatan->id_jabatan_kegiatan . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/admin/jabatan/' . $jabatan->id_jabatan_kegiatan . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button>';
                return $btn;
                })
        ->rawColumns(['aksi'])
        ->make(true);
    }

    public function create_ajax()
    {
        $jabatan = JabatanKegiatanModel::all();
        return view('admin.jabatan.create_ajax', compact('jabatan'));
    }

    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $validator = Validator::make($request->all(), [
                'jabatan_nama' => 'required|string|max:255',
                'poin' => 'required|numeric|min:0.5|max:2',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'msgField' => $validator->errors()
                ]);
            }

            JabatanKegiatanModel::create([
                'jabatan_nama' => $request->jabatan_nama,
                'poin' => $request->poin,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Jabatan Kegiatan berhasil ditambahkan'
            ]);
        }
        return redirect('/admin/jabatan');
    }

    public function edit_ajax($id)
    {
        $jabatan = JabatanKegiatanModel::find($id);
        if (!$jabatan) {
            return response()->json(['status' => false, 'message' => 'Jabatan Kegiatan tidak ditemukan']);
        }
        return view('admin.jabatan.edit_ajax', compact('jabatan'));
    }

    public function update_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $validator = Validator::make($request->all(), [
                'jabatan_nama' => 'required|string|max:255',
                'poin' => 'required|numeric|min:0.5|max:2',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'msgField' => $validator->errors()
                ]);
            }

            $jabatan = JabatanKegiatanModel::find($id);
            if (!$jabatan) {
                return response()->json(['status' => false, 'message' => 'Jabatan Kegiatan tidak ditemukan']);
            }

            $jabatan->update([
                'jabatan_nama' => $request->jabatan_nama,
                'poin' => $request->poin,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Jabatan Kegiatan berhasil diperbarui'
            ]);
        }
        return redirect('/admin/jabatan');
    }

    public function confirm_ajax($id)
    {
        $jabatan = JabatanKegiatanModel::find($id);
        if (!$jabatan) {
            return response()->json(['status' => false, 'message' => 'Jabatan Kegiatan tidak ditemukan']);
        }
        return view('admin.jabatan.confirm_ajax', compact('jabatan'));
    }

    public function delete_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $jabatan = JabatanKegiatanModel::find($id);
            if ($jabatan) {
                $jabatan->delete();
                return response()->json(['status' => true, 'message' => 'Jabatan Kegiatan berhasil dihapus']);
            }
        }
        return response()->json(['status' => false, 'message' => 'Jabatan Kegiatan tidak ditemukan']);
    }
}