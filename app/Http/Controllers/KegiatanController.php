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
use PhpOffice\PhpWord\PhpWord;
use Illuminate\Support\Facades\Storage;

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
    public function editAjaxAdmin($id)
    {
        $kegiatan = KegiatanModel::with('anggota')->find($id);
        if (!$kegiatan) {
            return response()->json(['status' => false, 'message' => 'Kegiatan tidak ditemukan'], 404);
        }
        $jabatan = JabatanKegiatanModel::all();
        $anggota = UserModel::all(); 
        return view('admin.kegiatan.edit_ajax', compact('kegiatan','jabatan', 'anggota'));
    }

    public function updateAjaxAdmin(Request $request, $id)
    {
        if($request->ajax() || $request->wantsJson()){
        $validator = Validator::make($request->all(), [
            'nama_kegiatan' => 'required|string|max:255',
            'jenis_kegiatan' => 'required|string|max:255',
            'deskripsi_kegiatan' => 'required|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date',
            'tanggal_acara' => 'required|date',
            'tempat_kegiatan' => 'required|string|max:255',
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
    
        $kegiatan = KegiatanModel::find($id);
        if (!$kegiatan) {
            return response()->json(['status' => false, 'message' => 'Kegiatan tidak ditemukan'], 404);
        }
    
        $kegiatan->update([
            'nama_kegiatan' => $request->nama_kegiatan,
            'jenis_kegiatan' => $request->jenis_kegiatan,
            'deskripsi_kegiatan' => $request->deskripsi_kegiatan,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'tanggal_acara' => $request->tanggal_acara,
            'tempat_kegiatan' => $request->tempat_kegiatan,
        ]);
    
        // Update anggota
        AnggotaModel::where('id_kegiatan', $id)->delete();
        foreach ($request->anggota_id as $index => $anggota_id) {
            AnggotaModel::create([
                'id_kegiatan' => $kegiatan->id_kegiatan,
                'id_user' => $anggota_id,
                'id_jabatan_kegiatan' => $request->jabatan_id[$index],
            ]);
        }
    
        return response()->json([
            'status' => true,
            'message' => 'Kegiatan berhasil diperbarui'
        ]);
    }
    }

    public function delete_ajax($id)
    {
        $kegiatan = KegiatanModel::find($id);
        if ($kegiatan) {
            $kegiatan->delete();
            return response()->json(['status' => true, 'message' => 'Kegiatan berhasil dihapus']);
        }
        return response()->json(['status' => false, 'message' => 'Kegiatan tidak ditemukan']);
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

    public function exportWord($id)
    {
        $kegiatan = KegiatanModel::find($id);
        $anggota = AnggotaModel::where('id_kegiatan', $id)->with('user', 'jabatan')->get();

        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $phpWord->setDefaultFontName('Times New Roman');
        $phpWord->setDefaultFontSize(12);

        $section = $phpWord->addSection();

        // Add header information (Kementerian Pendidikan header)
        $headerTable = $section->addTable();
        $headerTable->addRow();
        $headerTable->addCell(1500)->addImage(asset('polinema.png'), ['width' => 60, 'height' => 60, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        $headerCell = $headerTable->addCell(8500);

        $textRun = $headerCell->addTextRun(['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        $textRun->addText("KEMENTERIAN PENDIDIKAN, KEBUDAYAAN, RISET, DAN TEKNOLOGI", ['size' => 11]);
        $textRun->addTextBreak();
        $textRun->addText("POLITEKNIK NEGERI MALANG", ['bold' => true, 'size' => 13]);
        $textRun->addTextBreak();
        $textRun->addText("JL, Soekarno-Hatta No.9 Malang 65141", ['size' => 10]);
        $textRun->addTextBreak();
        $textRun->addText("Telepon (0341) 404424 Pes. 101-105 0341-404420, Fax. (0341) 404420", ['size' => 10]);
        $textRun->addTextBreak();
        $textRun->addText("https://www.polinema.ac.id", ['size' => 10]);

        // Add a line break
        $section->addTextBreak(0);

        // Add a horizontal line
        $lineStyle = ['weight' => 1, 'width' => 500, 'height' => 0, 'color' => '000000'];
        $section->addLine($lineStyle);

        // Add a line break
        $section->addTextBreak(0);

        // Add title and document number
        $titleRun = $section->addTextRun(['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        $titleRun->addText("SURAT TUGAS", ['bold' => true, 'size' => 14]);
        $titleRun->addTextBreak();
        $titleRun->addText("Nomor : 31464/PL2.1/KP/2024");
        $section->addTextBreak();

        // Introduction text
        $section->addText("Wakil Direktur I memberikan tugas kepada :", ['size' => 12]);
        $section->addTextBreak();

        // Add table for members (Anggota)
        $table = $section->addTable(['width' => 100 * 50]);
        $table = $section->addTable(['borderSize' => 6, 'borderColor' => '000000', 'width' => 100 * 50]);
        $table->addRow();
        $table->addCell(1000)->addText("NO", ['bold' => true]);
        $table->addCell(4000)->addText("NAMA", ['bold' => true]);
        $table->addCell(4000)->addText("NIP", ['bold' => true]);
        $table->addCell(4000)->addText("JABATAN", ['bold' => true]);

        // Populate table with anggota data
        $no = 1;
        foreach ($anggota as $member) {
            $table = $section->addTable(['borderSize' => 6, 'borderColor' => '000000', 'width' => 100 * 50]);
            $table->addRow();
            $table->addCell(1000)->addText($no);
            $table->addCell(4000)->addText($member->user->nama);
            $table->addCell(4000)->addText($member->user->NIP);
            $table->addCell(4000)->addText($member->jabatan->jabatan_nama);
            $no++;
        }

        // Add kegiatan details in a paragraph
        $section->addTextBreak();
        $section->addText("Untuk menjadi narasumber kegiatan " . $kegiatan->nama_kegiatan . " yang diselenggarakan oleh " . $kegiatan->deskripsi_kegiatan . " pada tanggal " . date('d F Y', strtotime($kegiatan->tanggal_acara)) . " bertempat di " . $kegiatan->tempat_kegiatan . ".", ['size' => 12]);

        $section->addText("Selesai melaksanakan tugas harap melaporkan hasilnya kepada Wakil Direktur I.", ['size' => 12]);
        $section->addText("Demikian untuk dilaksanakan sebaik-baiknya.", ['size' => 12]);
        $section->addTextBreak();

        // Add signature
        $signatureTable = $section->addTable();
        $signatureTable->addRow();
        $signatureCell = $signatureTable->addCell(10000, ['border' => 0]);
        $signatureCell->addText("28 Oktober 2024", null, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::RIGHT]);
        $signatureCell->addText("Direktur,", null, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::RIGHT]);
        $signatureCell->addTextBreak(3);
        $signatureCell->addText("Dr. Kurnia Ekasari, SE., M.M., Ak.", ['bold' => true], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::RIGHT]);
        $signatureCell->addText("NIP. 196602141990032002", null, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::RIGHT]);

        // Save the document
        $fileName = 'Surat_Tugas_' . $kegiatan->nama_kegiatan . '.docx';
        $filePath = storage_path('app/public/' . $fileName);
        $phpWord->save($filePath, 'Word2007');

        return response()->download($filePath)->deleteFileAfterSend(true);
    }

}
