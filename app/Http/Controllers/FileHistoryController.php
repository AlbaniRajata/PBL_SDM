<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DokumenModel; // Pastikan model Dokumen telah dibuat
use App\Models\FileModel; // Import the FileModel class

class FileHistoryController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'File',
            'list' => ['Home', 'File History'],
        ];
        $activeMenu = 'file'; 

        // Ambil data dokumen dari database dengan pagination
        $dokumen = DokumenModel::with('kegiatan')->paginate(10);

        return view('admin.file.index', [
            'breadcrumb' => $breadcrumb,
            'activeMenu' => $activeMenu,
            'dokumen' => $dokumen,
        ]);
    }

    public function download($id)
    {
        $dokumen = DokumenModel::findOrFail($id);

        if (file_exists(public_path($dokumen->file_path))) {
            return response()->download(public_path($dokumen->file_path));
        }

        return back()->with('error', 'File tidak ditemukan.');
    }
}