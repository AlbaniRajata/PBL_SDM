<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Models\StatistikModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
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

            $totalPoin = $data->sum('poin');
            $view = 'dosen.statistik.export_pdf';
        } else {
            return redirect()->back()->with('error', 'Level pengguna tidak dikenali.');
        }

        // Generate PDF
        $pdf = Pdf::loadView($view, compact('data', 'totalPoin'));
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
        } else {
            return redirect()->back()->with('error', 'Level pengguna tidak dikenali.');
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet(); // Get the active sheet

        // Set Header Columns
        if ($userLevel === 'admin' || $userLevel === 'pimpinan') {
            $sheet->setCellValue('A1', 'No');
            $sheet->setCellValue('B1', 'Nama');
            $sheet->setCellValue('C1', 'Total Kegiatan');
            $sheet->setCellValue('D1', 'Total Poin');
        } elseif ($userLevel === 'dosen') {
            $sheet->setCellValue('A1', 'No');
            $sheet->setCellValue('B1', 'Nama');
            $sheet->setCellValue('C1', 'Jabatan');
            $sheet->setCellValue('D1', 'Nama Kegiatan');
            $sheet->setCellValue('E1', 'Poin');
        }

        // Make header bold
        $sheet->getStyle('A1:E1')->getFont()->setBold(true);

        $no = 1;
        $row = 2;
        $totalPoin = 0;

        foreach ($data as $item) {
            $sheet->setCellValue('A' . $row, $no);
            $sheet->setCellValue('B' . $row, $item->nama);
            if ($userLevel === 'admin' || $userLevel === 'pimpinan') {
                $sheet->setCellValue('C' . $row, $item->total_kegiatan);
                $sheet->setCellValue('D' . $row, $item->total_poin);
            } elseif ($userLevel === 'dosen') {
                $sheet->setCellValue('C' . $row, $item->jabatan);
                $sheet->setCellValue('D' . $row, $item->judul_kegiatan);
                $sheet->setCellValue('E' . $row, $item->poin);
                $totalPoin += $item->poin;
            }
            $row++;
            $no++;
        }

        // Add total points row for dosen level
        if ($userLevel === 'dosen') {
            $sheet->setCellValue('D' . $row, 'Total Poin');
            $sheet->setCellValue('E' . $row, $totalPoin);
            $sheet->getStyle('D' . $row . ':E' . $row)->getFont()->setBold(true);
        }

        // Set auto column width for all columns
        foreach (range('A', 'E') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Set sheet title
        $sheet->setTitle('Data Statistik');

        // Create writer
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Statistik_' . ucfirst($userLevel) . '_' . date('Y-m-d_H-i-s') . '.xlsx';

        // Set headers for download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');

        // Save file to output
        $writer->save('php://output');
        exit;
    }
}
