<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DokumenModel; // Pastikan model Dokumen telah dibuat
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

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
        try {
            $dokumen = DokumenModel::findOrFail($id);
    
            // Daftar ekstensi yang diizinkan
            $allowedExtensions = [
                'docx', 'doc', 'pdf', 'xlsx', 'xls', 
                'jpg', 'jpeg', 'png', 'gif', 'bmp', 
                'txt', 'csv', 'ppt', 'pptx', 'zip', 'rar'
            ];
    
            // Dapatkan ekstensi file
            $extension = strtolower(pathinfo($dokumen->file_path, PATHINFO_EXTENSION));
    
            // Variasi path untuk mencoba menemukan file
            $filePaths = [
                storage_path('app/public/' . $dokumen->file_path),
                storage_path('app/' . $dokumen->file_path),
                storage_path($dokumen->file_path),
                public_path('storage/' . $dokumen->file_path),
                public_path($dokumen->file_path)
            ];
    
            // Cari file yang ada
            $foundPath = null;
            foreach ($filePaths as $path) {
                if (file_exists($path)) {
                    $foundPath = $path;
                    break;
                }
            }
    
            // Jika file tidak ditemukan
            if (!$foundPath) {
                return back()->with('error', 'File tidak ditemukan.');
            }
    
            // Validasi ekstensi file
            if (!in_array($extension, $allowedExtensions)) {
                return back()->with('error', 'Tipe file tidak diizinkan.');
            }
    
            // Generate nama file yang aman
            $fileName = Str::slug(pathinfo($dokumen->nama_dokumen, PATHINFO_FILENAME)) . '.' . $extension;
    
            // Download file
            return response()->download($foundPath, $fileName);
    
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return back()->with('error', 'Dokumen tidak ditemukan.');
        } catch (\Exception $e) {
            // Log error untuk debugging
            Log::error('Kesalahan download file: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat mengunduh file.');
        }
    }
    
    public function destroy($id)
    {
        try {
            $dokumen = DokumenModel::findOrFail($id);
    
            // Daftar ekstensi yang diperbolehkan untuk dihapus
            $allowedExtensions = [
                'docx', 'doc', 'pdf', 'xlsx', 'xls', 
                'jpg', 'jpeg', 'png', 'gif', 'bmp', 
                'txt', 'csv', 'ppt', 'pptx', 'zip', 'rar'
            ];
    
            // Periksa ekstensi file
            $extension = strtolower(pathinfo($dokumen->file_path, PATHINFO_EXTENSION));
            if (!in_array($extension, $allowedExtensions)) {
                return response()->json([
                    'status' => false, 
                    'message' => 'Tipe file tidak diizinkan untuk dihapus.'
                ], 400);
            }
    
            // Variasi path untuk mencoba menghapus
            $filePaths = [
                'public/' . $dokumen->file_path,
                'storage/' . $dokumen->file_path,
                $dokumen->file_path,
                'app/public/' . $dokumen->file_path,
                'app/storage/' . $dokumen->file_path
            ];
    
            $fileDeleted = false;
            foreach ($filePaths as $filePath) {
                if (Storage::exists($filePath)) {
                    Storage::delete($filePath);
                    $fileDeleted = true;
                    break;
                }
            }
    
            // Jika file tidak ditemukan di manapun
            if (!$fileDeleted) {
                return response()->json([
                    'status' => false, 
                    'message' => 'File tidak ditemukan di storage.'
                ], 404);
            }
    
            // Hapus data dari database
            $dokumen->delete();
    
            return response()->json([
                'status' => true, 
                'message' => 'File berhasil dihapus.'
            ]);
    
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => false, 
                'message' => 'Dokumen tidak ditemukan.'
            ], 404);
        } catch (\Exception $e) {
            // Log error untuk debugging
            Log::error('Error menghapus dokumen: ' . $e->getMessage());
    
            return response()->json([
                'status' => false, 
                'message' => 'Terjadi kesalahan saat menghapus file.'
            ], 500);
        }
    }
}
