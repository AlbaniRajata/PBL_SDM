<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JabatanKegiatanModel;
use Yajra\DataTables\DataTables;

class JabatanKegiatanController extends Controller
{
    public function list(Request $request)
    {
        if ($request->ajax()) {
            $data = JabatanKegiatanModel::select('id_user', 'id_jabatan_kegiatan');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('aksi', function($row){
                    $btn = '<a href="javascript:void(0)" onclick="modalAction(\''.route('admin.jabatan_kegiatan.show_ajax', $row->id).'\')" class="edit btn btn-primary btn-sm">View</a>';
                    return $btn;
                })
                ->rawColumns(['aksi'])
                ->make(true);
        }
    }
}
