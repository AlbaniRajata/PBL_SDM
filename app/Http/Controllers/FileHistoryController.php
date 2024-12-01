<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DokumenModel; // Pastikan model Dokumen telah dibuat
use Illuminate\Support\Facades\Storage;

class FileHistoryController extends Controller
{
    public function index(Request $request)
    {
        $breadcrumb = (object) [
            'title' => 'File',
            'list' => ['Home', 'File History'],
        ];
        $activeMenu = 'file';

        $query = DokumenModel::with('kegiatan');

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('nama_dokumen', 'like', "%{$search}%")
                  ->orWhereHas('kegiatan', function ($q) use ($search) {
                      $q->where('nama_kegiatan', 'like', "%{$search}%");
                  });
        }

        $dokumen = $query->paginate(10);

        return view('admin.file.index', [
            'breadcrumb' => $breadcrumb,
            'activeMenu' => $activeMenu,
            'dokumen' => $dokumen,
        ]);
    }

    public function download($id)
    {
        $dokumen = DokumenModel::findOrFail($id);

        // Pastikan jalur file benar
        $filePath = storage_path('app/public/' . $dokumen->file_path);

        if (file_exists($filePath)) {
            return response()->download($filePath);
        }

        return back()->with('error', 'File tidak ditemukan.');
    }

    public function destroy($id)
    {
        $dokumen = DokumenModel::findOrFail($id);

        // Hapus file dari storage
        $filePath = 'public/' . $dokumen->file_path;
        if (Storage::exists($filePath)) {
            Storage::delete($filePath);
        } else {
            return response()->json(['status' => false, 'message' => 'File tidak ditemukan di storage.']);
        }

        // Hapus data dari database
        $dokumen->delete();

        return response()->json(['status' => true, 'message' => 'File berhasil dihapus.']);
    }
}
