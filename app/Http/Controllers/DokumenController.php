<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\KegiatanModel;
use App\Models\UserModel;
use App\Models\DokumenModel;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\AnggotaModel;
use App\Models\JabatanKegiatanModel;
use App\Models\AgendaAnggotaModel;
use App\Models\AgendaModel;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpWord\SimpleType\Jc;
use Illuminate\Validation\ValidationException;


class DokumenController extends Controller
{
       // function export word
       public function exportWord($id)
       {
           $kegiatan = KegiatanModel::find($id);
           $anggota = AnggotaModel::where('id_kegiatan', $id)->with('user', 'jabatan')->get();
       
           $phpWord = new \PhpOffice\PhpWord\PhpWord();
           $phpWord->setDefaultFontName('Times New Roman');
           $phpWord->setDefaultFontSize(12);
       
           $section = $phpWord->addSection();
           // Tambahkan header ke semua section
           $header = $section->addHeader();
           $headerTable = $header->addTable();
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
           $section->addLine(['weight' => 1, 'width' => 500, 'height' => 0, 'color' => '000000']);
   
           if ($anggota->count() <= 2) {
               $section->addTextBreak(0);
               $table = $section->addTable();
               $table->addRow();

               $cell = $table->addCell(9000); // Sesuaikan lebar sel
               $cell->addText("Nomor            :     /    /   /2024");
               $cell = $table->addCell(3000);
               $cell->addText(date('d F Y'), ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::RIGHT]);
               $section->addText("Perihal       : Permohonan Pembuatan Surat Tugas", ['bold' => true]);
               $section->addTextBreak();
       
               $section->addText("Kepada");
               $section->addText("Yth. Pembantu Direktur I Politeknik Negeri Malang");
               $section->addTextBreak();
       
               $section->addText("Dengan Hormat,");
               $section->addTextBreak();
       
               $section->addText("Sehubungan dengan pelaksanaan kegiatan \"" . $kegiatan->nama_kegiatan . "\" D4 Sistem Informasi Bisnis yang diselenggarakan oleh Jurusan Teknologi Informasi pada bulan " . date('F Y', strtotime($kegiatan->tanggal_acara)) . ", mohon untuk dapat dibuatkan surat tugas kepada nama-nama di bawah ini:", ['size' => 12]);
               $section->addTextBreak();
       
               // Tabel Anggota
               $styleTable = [
                   'borderSize' => 4,
                   'borderColor' => '000000',
                   'cellMargin' => 80
               ];
               $phpWord->addTableStyle('Daftar Dosen', $styleTable);
               $table = $section->addTable('Daftar Dosen');
       
               $table->addRow();
               $table->addCell(500)->addText('No', ['bold' => true, 'size' => 10]);
               $cell = $table->addCell(5000);
               $textRun = $cell->addTextRun();
               $textRun->addText('Nama', ['bold' => true]);
               $textRun->addTextBreak();
               $textRun->addText('NIP');
               $table->addCell(3000)->addText('Pangkat/ Gol', ['bold' => true, 'size' => 10]);
               $table->addCell(3000)->addText('Jabatan', ['bold' => true, 'size' => 10]);
       
               foreach ($anggota as $index => $member) {
                   $table->addRow();
                   $table->addCell(500)->addText($index + 1);
                   $cell = $table->addCell(5000);
                   $textRun = $cell->addTextRun();
                   $textRun->addText($member->user->nama, ['bold' => true]);
                   $textRun->addTextBreak();
                   $textRun->addText($member->user->NIP);
                   $table->addCell(3000)->addText('Penata Muda Tingkat I / III/B');
                   $table->addCell(3000)->addText($member->jabatan->jabatan_nama);
               }
               $section->addTextBreak();
       
               $section->addText("Demikian surat permohonan ini dibuat. Atas perhatian dan kerjasamanya, kami sampaikan terima kasih.", ['size' => 12]);

               $signatureTable = $section->addTable();
               $signatureTable->addRow();
               $signatureCell = $signatureTable->addCell(10000, ['border' => 0]);
               $signatureCell->addText("28 Oktober 2024", null, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::RIGHT]);
               $signatureCell->addText("Ketua Jurusan Teknologi Informasi,", null, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::RIGHT]);
               $signatureCell->addTextBreak(2);
               $signatureCell->addText("Dr. Eng Rosa Andrie Asmara, S.T., M.T.", ['bold' => true], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::RIGHT]);
               $signatureCell->addText("NIP. 196602141990032002", null, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::RIGHT]);
       
           } else {
               $section->addTextBreak(0);
               $table = $section->addTable();
               $table->addRow();

               $cell = $table->addCell(9000); // Sesuaikan lebar sel
               $cell->addText("Nomor            :     /    /   /2024", ['size' => 10]);
               $cell = $table->addCell(3000);
               $cell->addText(date('d F Y'), ['alignment' => 'right', 'size' => 10]);
   
               $section->addText("Perihal       : Permohonan Pembuatan Surat Tugas", ['bold' => true, 'size' => 12]);
               $section->addTextBreak();
       
               $section->addText("Yth. Pembantu Direktur I");
               $section->addText("Politeknik Negeri Malang");
               $section->addText("di Tempat");
               $section->addTextBreak();
       
               $section->addText("Sehubungan dengan pelaksanaan kegiatan \"" . $kegiatan->nama_kegiatan . "\" D4 Sistem Informasi Bisnis yang diselenggarakan oleh Jurusan Teknologi Informasi pada " . date('d F Y', strtotime($kegiatan->tanggal_acara)) . ", dengan ini kami mohon dapat diterbitkan surat tugas kepada dosen di bawah ini untuk melaksanakan kegiatan yang dimaksud. Adapun nama–nama dosen tersebut terlampir.");
   
               $section->addText("Atas kerjasama dan perhatiannya, kami ucapkan terima kasih.");
               $section->addTextBreak();
   
               $signatureTable = $section->addTable();
               $signatureTable->addRow();
               $signatureCell = $signatureTable->addCell(10000, ['border' => 0]);
               $signatureCell->addText("28 Oktober 2024", null, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::RIGHT]);
               $signatureCell->addText("Ketua Jurusan Teknologi Informasi,", null, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::RIGHT]);
               $signatureCell->addTextBreak(2);
               $signatureCell->addText("Dr. Eng Rosa Andrie Asmara, S.T., M.T.", ['bold' => true], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::RIGHT]);
               $signatureCell->addText("NIP. 196602141990032002", null, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::RIGHT]);
   
               $section->addPageBreak();

               $section->addLine(['weight' => 1, 'width' => 500, 'height' => 0, 'color' => '000000']);
   
               $section->addText("Daftar Panitia", ['bold' => true, 'size' => 14], ['alignment' => Jc::CENTER]);
               $section->addtext(strtoupper($kegiatan->nama_kegiatan),[], ['alignment' => Jc::CENTER]);
               $section->addtext(date('d F Y', strtotime($kegiatan->tanggal_acara)), [], ['alignment' => Jc::CENTER]);
       
               // Tabel Anggota
               $styleTable = [
                'borderSize' => 4,
                'borderColor' => '000000',
                'cellMargin' => 80
            ];
            $phpWord->addTableStyle('Daftar Dosen', $styleTable);
            $table = $section->addTable('Daftar Dosen');
    
            $table->addRow();
            $table->addCell(500)->addText('No', ['bold' => true, 'size' => 10]);
            $cell = $table->addCell(5000);
            $textRun = $cell->addTextRun();
            $textRun->addText('Nama', ['bold' => true]);
            $textRun->addTextBreak();
            $textRun->addText('NIP');
            $table->addCell(3000)->addText('Pangkat/ Gol', ['bold' => true, 'size' => 10]);
            $table->addCell(3000)->addText('Jabatan', ['bold' => true, 'size' => 10]);
    
            foreach ($anggota as $index => $member) {
                $table->addRow();
                $table->addCell(500)->addText($index + 1);
                $cell = $table->addCell(5000);
                $textRun = $cell->addTextRun();
                $textRun->addText($member->user->nama, ['bold' => true]);
                $textRun->addTextBreak();
                $textRun->addText($member->user->NIP);
                $table->addCell(3000)->addText('Penata Muda Tingkat I / III/B');
                $table->addCell(3000)->addText($member->jabatan->jabatan_nama);
            }
           }

           $fileName = 'Surat_Tugas_' . $kegiatan->nama_kegiatan . '.docx';
           $filePath = storage_path('app/public/' . $fileName);
           $phpWord->save($filePath, 'Word2007');
       
           return response()->download($filePath)->deleteFileAfterSend(true);
       }
   

}
