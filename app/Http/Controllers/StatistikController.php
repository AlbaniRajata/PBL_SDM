<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Auth;

class StatistikController extends Controller
{
    public function admin()
    {
        $breadcrumb = (object) [
            'title' => 'Poin Dosen Jurusan Teknologi Informasi',
            'list' => ['Home', 'Statistik'],
        ];
        $activeMenu = 'statistik admin';
    
        $poinDosen = DB::table('t_user')
            ->leftJoin('t_anggota', 't_user.id_user', '=', 't_anggota.id_user')
            ->leftJoin('t_kegiatan', 't_anggota.id_kegiatan', '=', 't_kegiatan.id_kegiatan')
            ->leftJoin('t_jabatan_kegiatan', 't_anggota.id_jabatan_kegiatan', '=', 't_jabatan_kegiatan.id_jabatan_kegiatan')
            ->select(
                't_user.nama',
                't_user.id_user',
                DB::raw('COUNT(DISTINCT t_anggota.id_kegiatan) as total_kegiatan'),
                DB::raw('COALESCE(SUM(t_jabatan_kegiatan.poin), 0) as total_poin')
            )
            ->where('t_user.level', 'dosen')
            ->groupBy('t_user.id_user', 't_user.nama')
            ->get();
    
        // Fetch detailed activities for each lecturer
        $dosenKegiatan = DB::table('t_user')
            ->leftJoin('t_anggota', 't_user.id_user', '=', 't_anggota.id_user')
            ->leftJoin('t_kegiatan', 't_anggota.id_kegiatan', '=', 't_kegiatan.id_kegiatan')
            ->leftJoin('t_jabatan_kegiatan', 't_anggota.id_jabatan_kegiatan', '=', 't_jabatan_kegiatan.id_jabatan_kegiatan')
            ->select(
                't_user.id_user',
                't_user.nama',
                't_kegiatan.nama_kegiatan',
                't_kegiatan.tanggal_acara',
                't_kegiatan.jenis_kegiatan',
                't_jabatan_kegiatan.jabatan_nama',
                't_jabatan_kegiatan.poin'
            )
            ->where('t_user.level', 'dosen')
            ->orderBy('t_user.nama')
            ->orderBy('t_kegiatan.tanggal_acara')
            ->get()
            ->groupBy('id_user');
    
        return view('admin.statistik.index', compact('breadcrumb', 'activeMenu', 'poinDosen', 'dosenKegiatan'));
    }

    public function pimpinan()
    {
        $breadcrumb = (object) [
            'title' => 'Statistik Dosen Jurusan Teknologi Informasi',
            'list' => ['Home', 'Statistik'],
        ];
        $activeMenu = 'statistik pimpinan';
    
        $poinDosen = DB::table('t_user')
            ->leftJoin('t_anggota', 't_user.id_user', '=', 't_anggota.id_user')
            ->leftJoin('t_kegiatan', 't_anggota.id_kegiatan', '=', 't_kegiatan.id_kegiatan')
            ->leftJoin('t_jabatan_kegiatan', 't_anggota.id_jabatan_kegiatan', '=', 't_jabatan_kegiatan.id_jabatan_kegiatan')
            ->select(
                't_user.nama',
                't_user.id_user',
                DB::raw('COUNT(DISTINCT t_anggota.id_kegiatan) as total_kegiatan'),
                DB::raw('COALESCE(SUM(t_jabatan_kegiatan.poin), 0) as total_poin')
            )
            ->where('t_user.level', 'dosen')
            ->groupBy('t_user.id_user', 't_user.nama')
            ->get();
    
        // Fetch detailed activities for each lecturer
        $dosenKegiatan = DB::table('t_user')
            ->leftJoin('t_anggota', 't_user.id_user', '=', 't_anggota.id_user')
            ->leftJoin('t_kegiatan', 't_anggota.id_kegiatan', '=', 't_kegiatan.id_kegiatan')
            ->leftJoin('t_jabatan_kegiatan', 't_anggota.id_jabatan_kegiatan', '=', 't_jabatan_kegiatan.id_jabatan_kegiatan')
            ->select(
                't_user.id_user',
                't_user.nama',
                't_kegiatan.nama_kegiatan',
                't_kegiatan.tanggal_acara',
                't_kegiatan.jenis_kegiatan',
                't_jabatan_kegiatan.jabatan_nama',
                't_jabatan_kegiatan.poin'
            )
            ->where('t_user.level', 'dosen')
            ->orderBy('t_user.nama')
            ->orderBy('t_kegiatan.tanggal_acara')
            ->get()
            ->groupBy('id_user');
    
        return view('pimpinan.statistik.index', compact('breadcrumb', 'activeMenu', 'poinDosen', 'dosenKegiatan'));
    }

    public function dosen()
    {
        $user = Auth::user();
        $breadcrumb = (object) [
            'title' => 'Poin Pencapaian Dosen '. $user->nama,
            'list' => ['Home', 'Statistik Dosen'],
        ];
        $activeMenu = 'statistik dosen';
        $userId = $user->id;
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
    if ($userLevel === 'admin' || $userLevel === 'pimpinan') {
        $poinDosen = DB::table('t_user')
            ->leftJoin('t_anggota', 't_user.id_user', '=', 't_anggota.id_user')
            ->leftJoin('t_kegiatan', 't_anggota.id_kegiatan', '=', 't_kegiatan.id_kegiatan')
            ->leftJoin('t_jabatan_kegiatan', 't_anggota.id_jabatan_kegiatan', '=', 't_jabatan_kegiatan.id_jabatan_kegiatan')
            ->select(
                't_user.nama',
                't_user.id_user',
                DB::raw('COUNT(DISTINCT t_anggota.id_kegiatan) as total_kegiatan'),
                DB::raw('COALESCE(SUM(t_jabatan_kegiatan.poin), 0) as total_poin')
            )
            ->where('t_user.level', 'dosen')
            ->groupBy('t_user.id_user', 't_user.nama')
            ->get();

        $dosenKegiatan = DB::table('t_user')
            ->leftJoin('t_anggota', 't_user.id_user', '=', 't_anggota.id_user')
            ->leftJoin('t_kegiatan', 't_anggota.id_kegiatan', '=', 't_kegiatan.id_kegiatan')
            ->leftJoin('t_jabatan_kegiatan', 't_anggota.id_jabatan_kegiatan', '=', 't_jabatan_kegiatan.id_jabatan_kegiatan')
            ->select(
                't_user.id_user',
                't_user.nama',
                't_kegiatan.nama_kegiatan',
                't_kegiatan.tanggal_acara',
                't_kegiatan.jenis_kegiatan',
                't_jabatan_kegiatan.jabatan_nama',
                't_jabatan_kegiatan.poin'
            )
            ->where('t_user.level', 'dosen')
            ->orderBy('t_user.nama')
            ->orderBy('t_kegiatan.tanggal_acara')
            ->get()
            ->groupBy('id_user');


        // Tentukan view berdasarkan user level
        $view = $userLevel === 'admin' ? 'admin.statistik.export_pdf' : 'pimpinan.statistik.export_pdf';

        // Render PDF
        $pdf = PDF::loadView($view, [   
            'poinDosen' => $poinDosen,
            'dosenKegiatan' => $dosenKegiatan
        ]);
        } elseif ($userLevel === 'pimpinan') {
            // Similar logic to admin
            $poinDosen = DB::table('t_user')
            ->leftJoin('t_anggota', 't_user.id_user', '=', 't_anggota.id_user')
            ->leftJoin('t_kegiatan', 't_anggota.id_kegiatan', '=', 't_kegiatan.id_kegiatan')
            ->leftJoin('t_jabatan_kegiatan', 't_anggota.id_jabatan_kegiatan', '=', 't_jabatan_kegiatan.id_jabatan_kegiatan')
            ->select(
                't_user.nama',
                't_user.id_user',
                DB::raw('COUNT(DISTINCT t_anggota.id_kegiatan) as total_kegiatan'),
                DB::raw('COALESCE(SUM(t_jabatan_kegiatan.poin), 0) as total_poin')
            )
            ->where('t_user.level', 'dosen')
            ->groupBy('t_user.id_user', 't_user.nama')
            ->get();

        $dosenKegiatan = DB::table('t_user')
            ->leftJoin('t_anggota', 't_user.id_user', '=', 't_anggota.id_user')
            ->leftJoin('t_kegiatan', 't_anggota.id_kegiatan', '=', 't_kegiatan.id_kegiatan')
            ->leftJoin('t_jabatan_kegiatan', 't_anggota.id_jabatan_kegiatan', '=', 't_jabatan_kegiatan.id_jabatan_kegiatan')
            ->select(
                't_user.id_user',
                't_user.nama',
                't_kegiatan.nama_kegiatan',
                't_kegiatan.tanggal_acara',
                't_kegiatan.jenis_kegiatan',
                't_jabatan_kegiatan.jabatan_nama',
                't_jabatan_kegiatan.poin'
            )
            ->where('t_user.level', 'dosen')
            ->orderBy('t_user.nama')
            ->orderBy('t_kegiatan.tanggal_acara')
            ->get()
            ->groupBy('id_user');


        // Tentukan view berdasarkan user level
        $view = $userLevel === 'pimpinan' ? 'admin.statistik.export_pdf' : 'pimpinan.statistik.export_pdf';

        // Render PDF
        $pdf = PDF::loadView($view, [   
            'poinDosen' => $poinDosen,
            'dosenKegiatan' => $dosenKegiatan
        ]);

        } elseif ($userLevel === 'dosen') {
            $userId = Auth::id();
    
            $poinDosen = DB::table('t_user')
                ->leftJoin('t_anggota', 't_user.id_user', '=', 't_anggota.id_user')
                ->leftJoin('t_kegiatan', 't_anggota.id_kegiatan', '=', 't_kegiatan.id_kegiatan')
                ->leftJoin('t_jabatan_kegiatan', 't_anggota.id_jabatan_kegiatan', '=', 't_jabatan_kegiatan.id_jabatan_kegiatan')
                ->select(
                    't_user.nama',
                    't_user.id_user',
                    DB::raw('COUNT(DISTINCT t_anggota.id_kegiatan) as total_kegiatan'),
                    DB::raw('COALESCE(SUM(t_jabatan_kegiatan.poin), 0) as total_poin')
                )
                ->where('t_user.level', 'dosen')
                ->where('t_user.id_user', $userId)
                ->groupBy('t_user.id_user', 't_user.nama')
                ->get();
    
            $dosenKegiatan = DB::table('t_user')
                ->leftJoin('t_anggota', 't_user.id_user', '=', 't_anggota.id_user')
                ->leftJoin('t_kegiatan', 't_anggota.id_kegiatan', '=', 't_kegiatan.id_kegiatan')
                ->leftJoin('t_jabatan_kegiatan', 't_anggota.id_jabatan_kegiatan', '=', 't_jabatan_kegiatan.id_jabatan_kegiatan')
                ->select(
                    't_user.id_user',
                    't_user.nama',
                    't_kegiatan.nama_kegiatan',
                    't_kegiatan.tanggal_acara',
                    't_kegiatan.jenis_kegiatan',
                    't_jabatan_kegiatan.jabatan_nama',
                    't_jabatan_kegiatan.poin'
                )
                ->where('t_user.level', 'dosen')
                ->where('t_user.id_user', $userId)
                ->orderBy('t_kegiatan.tanggal_acara')
                ->get()
                ->groupBy('id_user');
    
            $view = 'dosen.statistik.export_pdf';
            $totalPoin = $poinDosen->sum('total_poin');
        } else {
            return redirect()->back()->with('error', 'Level pengguna tidak dikenali.');
        }
    
        // Generate PDF
        $pdf = Pdf::loadView($view, compact('poinDosen', 'dosenKegiatan'));
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
            $poinDosen = DB::table('t_user')
                ->leftJoin('t_anggota', 't_user.id_user', '=', 't_anggota.id_user')
                ->leftJoin('t_kegiatan', 't_anggota.id_kegiatan', '=', 't_kegiatan.id_kegiatan')
                ->leftJoin('t_jabatan_kegiatan', 't_anggota.id_jabatan_kegiatan', '=', 't_jabatan_kegiatan.id_jabatan_kegiatan')
                ->select(
                    't_user.nama',
                    't_user.id_user',
                    DB::raw('COUNT(DISTINCT t_anggota.id_kegiatan) as total_kegiatan'),
                    DB::raw('COALESCE(SUM(t_jabatan_kegiatan.poin), 0) as total_poin')
                )
                ->where('t_user.level', 'dosen')
                ->groupBy('t_user.id_user', 't_user.nama')
                ->get();
    
            $dosenKegiatan = DB::table('t_user')
                ->leftJoin('t_anggota', 't_user.id_user', '=', 't_anggota.id_user')
                ->leftJoin('t_kegiatan', 't_anggota.id_kegiatan', '=', 't_kegiatan.id_kegiatan')
                ->leftJoin('t_jabatan_kegiatan', 't_anggota.id_jabatan_kegiatan', '=', 't_jabatan_kegiatan.id_jabatan_kegiatan')
                ->select(
                    't_user.id_user',
                    't_user.nama',
                    't_kegiatan.nama_kegiatan',
                    't_kegiatan.tanggal_acara',
                    't_kegiatan.jenis_kegiatan',
                    't_jabatan_kegiatan.jabatan_nama',
                    't_jabatan_kegiatan.poin'
                )
                ->where('t_user.level', 'dosen')
                ->orderBy('t_user.nama')
                ->orderBy('t_kegiatan.tanggal_acara')
                ->get()
                ->groupBy('id_user');
        } elseif ($userLevel === 'dosen') {
            $userId = Auth::id();
    
            $poinDosen = DB::table('t_user')
                ->leftJoin('t_anggota', 't_user.id_user', '=', 't_anggota.id_user')
                ->leftJoin('t_kegiatan', 't_anggota.id_kegiatan', '=', 't_kegiatan.id_kegiatan')
                ->leftJoin('t_jabatan_kegiatan', 't_anggota.id_jabatan_kegiatan', '=', 't_jabatan_kegiatan.id_jabatan_kegiatan')
                ->select(
                    't_user.nama',
                    't_user.id_user',
                    DB::raw('COUNT(DISTINCT t_anggota.id_kegiatan) as total_kegiatan'),
                    DB::raw('COALESCE(SUM(t_jabatan_kegiatan.poin), 0) as total_poin')
                )
                ->where('t_user.level', 'dosen')
                ->where('t_user.id_user', $userId)
                ->groupBy('t_user.id_user', 't_user.nama')
                ->get();
    
            $dosenKegiatan = DB::table('t_user')
                ->leftJoin('t_anggota', 't_user.id_user', '=', 't_anggota.id_user')
                ->leftJoin('t_kegiatan', 't_anggota.id_kegiatan', '=', 't_kegiatan.id_kegiatan')
                ->leftJoin('t_jabatan_kegiatan', 't_anggota.id_jabatan_kegiatan', '=', 't_jabatan_kegiatan.id_jabatan_kegiatan')
                ->select(
                    't_user.id_user',
                    't_user.nama',
                    't_kegiatan.nama_kegiatan',
                    't_kegiatan.tanggal_acara',
                    't_kegiatan.jenis_kegiatan',
                    't_jabatan_kegiatan.jabatan_nama',
                    't_jabatan_kegiatan.poin'
                )
                ->where('t_user.level', 'dosen')
                ->where('t_user.id_user', $userId)
                ->orderBy('t_kegiatan.tanggal_acara')
                ->get()
                ->groupBy('id_user');
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
            $sheet->setCellValue('E1', 'Detail Kegiatan');
        
            // Make header bold
            $sheet->getStyle('A1:E1')->getFont()->setBold(true);
        
            $no = 1;
            $row = 2;
            $grandTotalPoin = 0;
        
            foreach ($poinDosen as $dosen) {
                // Calculate total points for this specific user
                $userTotalPoin = 0;
                $activitiesDetail = '';
        
                if (isset($dosenKegiatan[$dosen->id_user])) {
                    foreach ($dosenKegiatan[$dosen->id_user] as $kegiatan) {
                        $activitiesDetail .= sprintf(
                            "%s (%s) - %s [%s poin]\n",
                            $kegiatan->nama_kegiatan,
                            $kegiatan->jenis_kegiatan,
                            $kegiatan->tanggal_acara,
                            $kegiatan->poin
                        );
                        
                        // Sum points for this specific user
                        $userTotalPoin += $kegiatan->poin;
                    }
                }
        
                $sheet->setCellValue('A' . $row, $no);
                $sheet->setCellValue('B' . $row, $dosen->nama);
                $sheet->setCellValue('C' . $row, $dosen->total_kegiatan);
                $sheet->setCellValue('D' . $row, $userTotalPoin); // Use calculated user points
                $sheet->setCellValue('E' . $row, $activitiesDetail);
        
                $grandTotalPoin += $userTotalPoin;
                $row++;
                $no++;
            }
        } elseif ($userLevel === 'dosen') {
            $sheet->setCellValue('A1', 'No');
            $sheet->setCellValue('B1', 'Nama Kegiatan');
            $sheet->setCellValue('C1', 'Jenis Kegiatan');
            $sheet->setCellValue('D1', 'Tanggal Acara');
            $sheet->setCellValue('E1', 'Jabatan');
            $sheet->setCellValue('F1', 'Poin');
    
            // Make header bold
            $sheet->getStyle('A1:F1')->getFont()->setBold(true);
    
            $no = 1;
            $row = 2;
            $totalPoin = 0;
    
            foreach ($dosenKegiatan[$userId] as $kegiatan) {
                $sheet->setCellValue('A' . $row, $no);
                $sheet->setCellValue('B' . $row, $kegiatan->nama_kegiatan);
                $sheet->setCellValue('C' . $row, $kegiatan->jenis_kegiatan);
                $sheet->setCellValue('D' . $row, $kegiatan->tanggal_acara);
                $sheet->setCellValue('E' . $row, $kegiatan->jabatan_nama);
                $sheet->setCellValue('F' . $row, $kegiatan->poin);
    
                $totalPoin += $kegiatan->poin;
                $row++;
                $no++;
            }
    
            // Add total points row
            $sheet->setCellValue('E' . $row, 'Total Poin');
            $sheet->setCellValue('F' . $row, $totalPoin);
            $sheet->getStyle('E' . $row . ':F' . $row)->getFont()->setBold(true);
        }
    
        // Set auto column width for all columns
        foreach (range('A', 'F') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
    
        // Set sheet title
        $sheet->setTitle('Data Statistik');
    
        // Create writer
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data_Statistik_' . ucfirst($userLevel) . '_' . date('Y-m-d_H-i-s') . '.xlsx';
    
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
