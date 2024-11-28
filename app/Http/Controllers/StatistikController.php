<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Models\StatistikModel;
use Illuminate\Support\Facades\Auth;

class StatistikController extends Controller
{
    public function admin()
    {
        $breadcrumb = (object) [
            'title' => 'Home',
            'list' => ['Home', 'Statistik'],
        ];
        $activeMenu = 'statistik admin';

        $poinDosen = DB::table('t_user')
            ->leftJoin('t_anggota', 't_user.id_user', '=', 't_anggota.id_user')
            ->leftJoin('t_jabatan_kegiatan', 't_anggota.id_jabatan_kegiatan', '=', 't_jabatan_kegiatan.id_jabatan_kegiatan')
            ->select(
                't_user.nama',
                DB::raw('COUNT(t_anggota.id_kegiatan) as total_kegiatan'),
                DB::raw('COALESCE(SUM(t_jabatan_kegiatan.poin), 0) as total_poin')
            )
            ->where('t_user.level', 'dosen')
            ->groupBy('t_user.nama')
            ->get();

        return view('admin.statistik.index', compact('breadcrumb', 'activeMenu', 'poinDosen'));
    }

    public function pimpinan()
    {
        $breadcrumb = (object) [
            'title' => 'Home',
            'list' => ['Home', 'Statistik'],
        ];
        $activeMenu = 'statistik pimpinan';

        $poinDosen = DB::table('t_user')
            ->leftJoin('t_anggota', 't_user.id_user', '=', 't_anggota.id_user')
            ->leftJoin('t_jabatan_kegiatan', 't_anggota.id_jabatan_kegiatan', '=', 't_jabatan_kegiatan.id_jabatan_kegiatan')
            ->select(
                't_user.nama',
                DB::raw('COUNT(t_anggota.id_kegiatan) as total_kegiatan'),
                DB::raw('COALESCE(SUM(t_jabatan_kegiatan.poin), 0) as total_poin')
            )
            ->where('t_user.level', 'dosen')
            ->groupBy('t_user.nama')
            ->get();

        return view('pimpinan.statistik.index', compact('breadcrumb', 'activeMenu', 'poinDosen'));
    }

    public function dosen()
    {
        $breadcrumb = (object) [
            'title' => 'Home',
            'list' => ['Home', 'Statistik Dosen'],
        ];
        $activeMenu = 'statistik dosen';

        $userId = Auth::id();

        $poinDosen = DB::table('t_user')
            ->join('t_anggota', 't_user.id_user', '=', 't_anggota.id_user')
            ->join('t_jabatan_kegiatan', 't_anggota.id_jabatan_kegiatan', '=', 't_jabatan_kegiatan.id_jabatan_kegiatan')
            ->join('t_kegiatan', 't_anggota.id_kegiatan', '=', 't_kegiatan.id_kegiatan')
            ->select(
                't_user.nama',
                't_jabatan_kegiatan.jabatan_nama as jabatan',
                't_kegiatan.nama_kegiatan as judul_kegiatan',
                't_jabatan_kegiatan.poin'
            )
            ->where('t_user.id_user', $userId)
            ->get();

        return view('dosen.statistik.index', compact('breadcrumb', 'activeMenu', 'poinDosen'));
    }

    public function exportPdf()
    {
        // Dapatkan level pengguna yang sedang login
        $userLevel = Auth::user()->level;
    
        // Siapkan data berdasarkan level pengguna
        if ($userLevel === 'admin') {
            $data = DB::table('t_user')
                ->leftJoin('t_anggota', 't_user.id_user', '=', 't_anggota.id_user')
                ->leftJoin('t_jabatan_kegiatan', 't_anggota.id_jabatan_kegiatan', '=', 't_jabatan_kegiatan.id_jabatan_kegiatan')
                ->select(
                    't_user.nama',
                    DB::raw('COUNT(t_anggota.id_kegiatan) as total_kegiatan'),
                    DB::raw('COALESCE(SUM(t_jabatan_kegiatan.poin), 0) as total_poin')
                )
                ->where('t_user.level', 'dosen')
                ->groupBy('t_user.nama')
                ->get();
    
            $view = 'admin.statistik.export_pdf';
        } elseif ($userLevel === 'pimpinan') {
            $data = DB::table('t_user')
                ->leftJoin('t_anggota', 't_user.id_user', '=', 't_anggota.id_user')
                ->leftJoin('t_jabatan_kegiatan', 't_anggota.id_jabatan_kegiatan', '=', 't_jabatan_kegiatan.id_jabatan_kegiatan')
                ->select(
                    't_user.nama',
                    DB::raw('COUNT(t_anggota.id_kegiatan) as total_kegiatan'),
                    DB::raw('COALESCE(SUM(t_jabatan_kegiatan.poin), 0) as total_poin')
                )
                ->where('t_user.level', 'dosen')
                ->groupBy('t_user.nama')
                ->get();
    
            $view = 'pimpinan.statistik.export_pdf';
        } elseif ($userLevel === 'dosen') {
            $userId = Auth::id();
    
            $data = DB::table('t_user')
                ->join('t_anggota', 't_user.id_user', '=', 't_anggota.id_user')
                ->join('t_jabatan_kegiatan', 't_anggota.id_jabatan_kegiatan', '=', 't_jabatan_kegiatan.id_jabatan_kegiatan')
                ->join('t_kegiatan', 't_anggota.id_kegiatan', '=', 't_kegiatan.id_kegiatan')
                ->select(
                    't_user.nama',
                    't_jabatan_kegiatan.jabatan_nama as jabatan',
                    't_kegiatan.nama_kegiatan as judul_kegiatan',
                    't_jabatan_kegiatan.poin'
                )
                ->where('t_user.id_user', $userId)
                ->get();
    
            $view = 'dosen.statistik.export_pdf';
        } else {
            return redirect()->back()->with('error', 'Level pengguna tidak dikenali.');
        }
    
        // Generate PDF
        $pdf = Pdf::loadView($view, compact('data'));
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption("isRemoteEnabled", true);
        $pdf->render();
    
        // Pratinjau file PDF di browser
        return $pdf->stream('Statistik_' . ucfirst($userLevel) . '_' . date('Y-m-d_H:i:s') . '.pdf');
    }

    public function exportExcel()
    {
        // Dapatkan level pengguna yang sedang login
        $userLevel = Auth::user()->level;

        // Siapkan data berdasarkan level pengguna
        if ($userLevel === 'admin' || $userLevel === 'pimpinan') {
            $data = DB::table('t_user')
                ->leftJoin('t_anggota', 't_user.id_user', '=', 't_anggota.id_user')
                ->leftJoin('t_jabatan_kegiatan', 't_anggota.id_jabatan_kegiatan', '=', 't_jabatan_kegiatan.id_jabatan_kegiatan')
                ->select(
                    't_user.nama',
                    DB::raw('COUNT(t_anggota.id_kegiatan) as total_kegiatan'),
                    DB::raw('COALESCE(SUM(t_jabatan_kegiatan.poin), 0) as total_poin')
                )
                ->where('t_user.level', 'dosen')
                ->groupBy('t_user.nama')
                ->get();

            // Create new Spreadsheet object
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Set document properties
            $spreadsheet->getProperties()->setCreator('YourAppName')
                ->setLastModifiedBy('YourAppName')
                ->setTitle('Laporan Statistik Dosen')
                ->setSubject('Laporan Statistik Dosen')
                ->setDescription('Laporan Statistik Dosen')
                ->setKeywords('pdf php')
                ->setCategory('Laporan');

            // Add some data
            $sheet->setCellValue('A1', 'No');
            $sheet->setCellValue('B1', 'Nama Dosen');
            $sheet->setCellValue('C1', 'Total Kegiatan');
            $sheet->setCellValue('D1', 'Total Poin');

            // Make header bold
            $sheet->getStyle('A1:D1')->getFont()->setBold(true);

            // Populate data
            $row = 2;
            foreach ($data as $index => $item) {
                $sheet->setCellValue('A' . $row, $index + 1);
                $sheet->setCellValue('B' . $row, $item->nama);
                $sheet->setCellValue('C' . $row, $item->total_kegiatan);
                $sheet->setCellValue('D' . $row, $item->total_poin);
                $row++;
            }

            // Align numbers to the left
            $sheet->getStyle('A2:A' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

            // Write the file
            $writer = new Xlsx($spreadsheet);
            $fileName = 'Statistik_' . ucfirst($userLevel) . '_' . date('Y-m-d_H:i:s') . '.xlsx';

            // Redirect output to a client’s web browser (Excel2007)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $fileName . '"');
            header('Cache-Control: max-age=0');
            $writer->save('php://output');
            exit;
        } elseif ($userLevel === 'dosen') {
            $userId = Auth::id();

            $data = DB::table('t_user')
                ->join('t_anggota', 't_user.id_user', '=', 't_anggota.id_user')
                ->join('t_jabatan_kegiatan', 't_anggota.id_jabatan_kegiatan', '=', 't_jabatan_kegiatan.id_jabatan_kegiatan')
                ->join('t_kegiatan', 't_anggota.id_kegiatan', '=', 't_kegiatan.id_kegiatan')
                ->select(
                    't_user.nama',
                    't_jabatan_kegiatan.jabatan_nama as jabatan',
                    't_kegiatan.nama_kegiatan as judul_kegiatan',
                    't_jabatan_kegiatan.poin'
                )
                ->where('t_user.id_user', $userId)
                ->get();

            // Create new Spreadsheet object
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Set document properties
            $spreadsheet->getProperties()->setCreator('YourAppName')
                ->setLastModifiedBy('YourAppName')
                ->setTitle('Laporan Statistik Dosen')
                ->setSubject('Laporan Statistik Dosen')
                ->setDescription('Laporan Statistik Dosen')
                ->setKeywords('pdf php')
                ->setCategory('Laporan');

            // Add some data
            $sheet->setCellValue('A1', 'No');
            $sheet->setCellValue('B1', 'Nama Kegiatan');
            $sheet->setCellValue('C1', 'Jabatan');
            $sheet->setCellValue('D1', 'Poin');

            // Make header bold
            $sheet->getStyle('A1:D1')->getFont()->setBold(true);

            // Populate data
            $row = 2;
            foreach ($data as $index => $item) {
                $sheet->setCellValue('A' . $row, $index + 1);
                $sheet->setCellValue('B' . $row, $item->judul_kegiatan);
                $sheet->setCellValue('C' . $row, $item->jabatan);
                $sheet->setCellValue('D' . $row, $item->poin);
                $row++;
            }

            // Align numbers to the left
            $sheet->getStyle('A2:A' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

            // Write the file
            $writer = new Xlsx($spreadsheet);
            $fileName = 'Statistik_' . ucfirst($userLevel) . '_' . date('Y-m-d_H:i:s') . '.xlsx';

            // Redirect output to a client’s web browser (Excel2007)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $fileName . '"');
            header('Cache-Control: max-age=0');
            $writer->save('php://output');
            exit;
        } else {
            return redirect()->back()->with('error', 'Level pengguna tidak dikenali.');
        }
    }
}
