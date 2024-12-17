<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Log;

class StatistikController extends Controller
{
    public function admin()
    {
        $breadcrumb = (object) [
            'title' => 'Poin Dosen Jurusan Teknologi Informasi',
            'list' => ['Home', 'Statistik'],
        ];
        $activeMenu = 'statistik admin';

        return view('admin.statistik.index', compact('breadcrumb', 'activeMenu'));
    }

    public function list(Request $request)
    {
        try {
            $query = DB::table('m_user')
                ->leftJoin('t_anggota', 'm_user.id_user', '=', 't_anggota.id_user')
                ->leftJoin('t_kegiatan', 't_anggota.id_kegiatan', '=', 't_kegiatan.id_kegiatan')
                ->leftJoin('t_jabatan_kegiatan', 't_anggota.id_jabatan_kegiatan', '=', 't_jabatan_kegiatan.id_jabatan_kegiatan')
                ->select(
                    'm_user.id_user',
                    'm_user.nama',
                    DB::raw('COUNT(DISTINCT t_anggota.id_kegiatan) as total_kegiatan'),
                    DB::raw('COALESCE(SUM(t_jabatan_kegiatan.poin), 0) as total_poin')
                )
                ->where('m_user.level', 'dosen')
                ->groupBy('m_user.id_user', 'm_user.nama');

            // Point filter
            if ($request->has('point_filter') && $request->point_filter) {
                $pointFilter = $request->point_filter;
                $query->when($pointFilter === '0-10', function ($q) {
                    return $q->havingRaw('COALESCE(SUM(t_jabatan_kegiatan.poin), 0) BETWEEN 0 AND 10');
                })->when($pointFilter === '11-30', function ($q) {
                    return $q->havingRaw('COALESCE(SUM(t_jabatan_kegiatan.poin), 0) BETWEEN 11 AND 30');
                })->when($pointFilter === '31-50', function ($q) {
                    return $q->havingRaw('COALESCE(SUM(t_jabatan_kegiatan.poin), 0) BETWEEN 31 AND 50');
                })->when($pointFilter === '>51', function ($q) {
                    return $q->havingRaw('COALESCE(SUM(t_jabatan_kegiatan.poin), 0) > 51');
                });
            }

            return DataTables::of($query)
            ->addColumn('DT_RowIndex', function ($row) {
                static $index = 0; // Buat index manual
                return ++$index;
            })
                ->addColumn('aksi', function($row) {
                    return '
                        <button onclick="showDetails('.$row->id_user.')" class="btn btn-sm btn-info">
                            <i class="fa-solid fa-eye"></i> Detail
                        </button>
                    ';
                })
                ->rawColumns(['aksi'])
                ->make(true);
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('DataTables list error: ' . $e->getMessage());
            
            // Return a JSON response with error details
            return response()->json([
                'error' => true, 
                'message' => 'Error retrieving data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function details(Request $request)
    {
        try {
            // Validate input
            $request->validate([
                'dosen_id' => 'required|exists:m_user,id_user'
            ]);
    
            $dosenId = $request->input('dosen_id');
    
            // Fetch detailed activity information
            $kegiatan = DB::table('t_anggota')
                ->join('t_kegiatan', 't_anggota.id_kegiatan', '=', 't_kegiatan.id_kegiatan')
                ->join('t_jabatan_kegiatan', 't_anggota.id_jabatan_kegiatan', '=', 't_jabatan_kegiatan.id_jabatan_kegiatan')
                ->join('m_user', 't_anggota.id_user', '=', 'm_user.id_user')
                ->select(
                    't_kegiatan.nama_kegiatan',
                    't_kegiatan.tanggal_acara',
                    't_kegiatan.jenis_kegiatan',
                    // Subquery menggunakan kolom yang benar
                    DB::raw('(SELECT jabatan_nama 
                              FROM t_jabatan_kegiatan 
                              WHERE id_jabatan_kegiatan = t_anggota.id_jabatan_kegiatan) as jabatan_nama'),
                    't_jabatan_kegiatan.poin'
                )
                ->where('t_anggota.id_user', $dosenId)
                ->get();
    
            // Return HTML content
            $html = view('admin.statistik.details', compact('kegiatan'))->render();
            return response()->json(['html' => $html]);
        } catch (\Exception $e) {
            // Log full error details
            Log::error('Details fetch error: ', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
    
            return response()->json([
                'error' => true, 
                'message' => 'Error fetching details: ' . $e->getMessage()
            ], 500);
        }
    }
    

    public function pimpinan()
    {
        $breadcrumb = (object) [
            'title' => 'Statistik Dosen Jurusan Teknologi Informasi',
            'list' => ['Home', 'Statistik'],
        ];
        $activeMenu = 'statistik pimpinan';
    
        $poinDosen = DB::table('m_user')
            ->leftJoin('t_anggota', 'm_user.id_user', '=', 't_anggota.id_user')
            ->leftJoin('t_kegiatan', 't_anggota.id_kegiatan', '=', 't_kegiatan.id_kegiatan')
            ->leftJoin('t_jabatan_kegiatan', 't_anggota.id_jabatan_kegiatan', '=', 't_jabatan_kegiatan.id_jabatan_kegiatan')
            ->select(
                'm_user.nama',
                'm_user.id_user',
                DB::raw('COUNT(DISTINCT t_anggota.id_kegiatan) as total_kegiatan'),
                DB::raw('COALESCE(SUM(t_jabatan_kegiatan.poin), 0) as total_poin')
            )
            ->where('m_user.level', 'dosen')
            ->groupBy('m_user.id_user', 'm_user.nama')
            ->get();
    
        // Fetch detailed activities for each lecturer
        $dosenKegiatan = DB::table('m_user')
            ->leftJoin('t_anggota', 'm_user.id_user', '=', 't_anggota.id_user')
            ->leftJoin('t_kegiatan', 't_anggota.id_kegiatan', '=', 't_kegiatan.id_kegiatan')
            ->leftJoin('t_jabatan_kegiatan', 't_anggota.id_jabatan_kegiatan', '=', 't_jabatan_kegiatan.id_jabatan_kegiatan')
            ->select(
                'm_user.id_user',
                'm_user.nama',
                't_kegiatan.nama_kegiatan',
                't_kegiatan.tanggal_acara',
                't_kegiatan.jenis_kegiatan',
                't_jabatan_kegiatan.jabatan_nama',
                't_jabatan_kegiatan.poin'
            )
            ->where('m_user.level', 'dosen')
            ->orderBy('m_user.nama')
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

        $poinDosen = DB::table('m_user')
            ->join('t_anggota', 'm_user.id_user', '=', 't_anggota.id_user')
            ->join('t_jabatan_kegiatan', 't_anggota.id_jabatan_kegiatan', '=', 't_jabatan_kegiatan.id_jabatan_kegiatan')
            ->join('t_kegiatan', 't_anggota.id_kegiatan', '=', 't_kegiatan.id_kegiatan')
            ->select(
                'm_user.nama',
                't_jabatan_kegiatan.jabatan_nama as jabatan',
                't_kegiatan.nama_kegiatan as judul_kegiatan',
                't_jabatan_kegiatan.poin'
            )
            ->where('m_user.id_user', $userId)
            ->get();

        return view('dosen.statistik.index', compact('breadcrumb', 'activeMenu', 'poinDosen'));
    }

    public function exportPdf()
{
    // Dapatkan level pengguna yang sedang login
    $userLevel = Auth::user()->level;

    // Siapkan data berdasarkan level pengguna
    if ($userLevel === 'admin' || $userLevel === 'pimpinan') {
        $poinDosen = DB::table('m_user')
            ->leftJoin('t_anggota', 'm_user.id_user', '=', 't_anggota.id_user')
            ->leftJoin('t_kegiatan', 't_anggota.id_kegiatan', '=', 't_kegiatan.id_kegiatan')
            ->leftJoin('t_jabatan_kegiatan', 't_anggota.id_jabatan_kegiatan', '=', 't_jabatan_kegiatan.id_jabatan_kegiatan')
            ->select(
                'm_user.nama',
                'm_user.id_user',
                DB::raw('COUNT(DISTINCT t_anggota.id_kegiatan) as total_kegiatan'),
                DB::raw('COALESCE(SUM(t_jabatan_kegiatan.poin), 0) as total_poin')
            )
            ->where('m_user.level', 'dosen')
            ->groupBy('m_user.id_user', 'm_user.nama')
            ->get();

        $dosenKegiatan = DB::table('m_user')
            ->leftJoin('t_anggota', 'm_user.id_user', '=', 't_anggota.id_user')
            ->leftJoin('t_kegiatan', 't_anggota.id_kegiatan', '=', 't_kegiatan.id_kegiatan')
            ->leftJoin('t_jabatan_kegiatan', 't_anggota.id_jabatan_kegiatan', '=', 't_jabatan_kegiatan.id_jabatan_kegiatan')
            ->select(
                'm_user.id_user',
                'm_user.nama',
                't_kegiatan.nama_kegiatan',
                't_kegiatan.tanggal_acara',
                't_kegiatan.jenis_kegiatan',
                't_jabatan_kegiatan.jabatan_nama',
                't_jabatan_kegiatan.poin'
            )
            ->where('m_user.level', 'dosen')
            ->orderBy('m_user.nama')
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
            $poinDosen = DB::table('m_user')
            ->leftJoin('t_anggota', 'm_user.id_user', '=', 't_anggota.id_user')
            ->leftJoin('t_kegiatan', 't_anggota.id_kegiatan', '=', 't_kegiatan.id_kegiatan')
            ->leftJoin('t_jabatan_kegiatan', 't_anggota.id_jabatan_kegiatan', '=', 't_jabatan_kegiatan.id_jabatan_kegiatan')
            ->select(
                'm_user.nama',
                'm_user.id_user',
                DB::raw('COUNT(DISTINCT t_anggota.id_kegiatan) as total_kegiatan'),
                DB::raw('COALESCE(SUM(t_jabatan_kegiatan.poin), 0) as total_poin')
            )
            ->where('m_user.level', 'dosen')
            ->groupBy('m_user.id_user', 'm_user.nama')
            ->get();

        $dosenKegiatan = DB::table('m_user')
            ->leftJoin('t_anggota', 'm_user.id_user', '=', 't_anggota.id_user')
            ->leftJoin('t_kegiatan', 't_anggota.id_kegiatan', '=', 't_kegiatan.id_kegiatan')
            ->leftJoin('t_jabatan_kegiatan', 't_anggota.id_jabatan_kegiatan', '=', 't_jabatan_kegiatan.id_jabatan_kegiatan')
            ->select(
                'm_user.id_user',
                'm_user.nama',
                't_kegiatan.nama_kegiatan',
                't_kegiatan.tanggal_acara',
                't_kegiatan.jenis_kegiatan',
                't_jabatan_kegiatan.jabatan_nama',
                't_jabatan_kegiatan.poin'
            )
            ->where('m_user.level', 'dosen')
            ->orderBy('m_user.nama')
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
    
            $poinDosen = DB::table('m_user')
                ->leftJoin('t_anggota', 'm_user.id_user', '=', 't_anggota.id_user')
                ->leftJoin('t_kegiatan', 't_anggota.id_kegiatan', '=', 't_kegiatan.id_kegiatan')
                ->leftJoin('t_jabatan_kegiatan', 't_anggota.id_jabatan_kegiatan', '=', 't_jabatan_kegiatan.id_jabatan_kegiatan')
                ->select(
                    'm_user.nama',
                    'm_user.id_user',
                    DB::raw('COUNT(DISTINCT t_anggota.id_kegiatan) as total_kegiatan'),
                    DB::raw('COALESCE(SUM(t_jabatan_kegiatan.poin), 0) as total_poin')
                )
                ->where('m_user.level', 'dosen')
                ->where('m_user.id_user', $userId)
                ->groupBy('m_user.id_user', 'm_user.nama')
                ->get();
    
            $dosenKegiatan = DB::table('m_user')
                ->leftJoin('t_anggota', 'm_user.id_user', '=', 't_anggota.id_user')
                ->leftJoin('t_kegiatan', 't_anggota.id_kegiatan', '=', 't_kegiatan.id_kegiatan')
                ->leftJoin('t_jabatan_kegiatan', 't_anggota.id_jabatan_kegiatan', '=', 't_jabatan_kegiatan.id_jabatan_kegiatan')
                ->select(
                    'm_user.id_user',
                    'm_user.nama',
                    't_kegiatan.nama_kegiatan',
                    't_kegiatan.tanggal_acara',
                    't_kegiatan.jenis_kegiatan',
                    't_jabatan_kegiatan.jabatan_nama',
                    't_jabatan_kegiatan.poin'
                )
                ->where('m_user.level', 'dosen')
                ->where('m_user.id_user', $userId)
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
            $poinDosen = DB::table('m_user')
                ->leftJoin('t_anggota', 'm_user.id_user', '=', 't_anggota.id_user')
                ->leftJoin('t_kegiatan', 't_anggota.id_kegiatan', '=', 't_kegiatan.id_kegiatan')
                ->leftJoin('t_jabatan_kegiatan', 't_anggota.id_jabatan_kegiatan', '=', 't_jabatan_kegiatan.id_jabatan_kegiatan')
                ->select(
                    'm_user.nama',
                    'm_user.id_user',
                    DB::raw('COUNT(DISTINCT t_anggota.id_kegiatan) as total_kegiatan'),
                    DB::raw('COALESCE(SUM(t_jabatan_kegiatan.poin), 0) as total_poin')
                )
                ->where('m_user.level', 'dosen')
                ->groupBy('m_user.id_user', 'm_user.nama')
                ->get();
    
            $dosenKegiatan = DB::table('m_user')
                ->leftJoin('t_anggota', 'm_user.id_user', '=', 't_anggota.id_user')
                ->leftJoin('t_kegiatan', 't_anggota.id_kegiatan', '=', 't_kegiatan.id_kegiatan')
                ->leftJoin('t_jabatan_kegiatan', 't_anggota.id_jabatan_kegiatan', '=', 't_jabatan_kegiatan.id_jabatan_kegiatan')
                ->select(
                    'm_user.id_user',
                    'm_user.nama',
                    't_kegiatan.nama_kegiatan',
                    't_kegiatan.tanggal_acara',
                    't_kegiatan.jenis_kegiatan',
                    't_jabatan_kegiatan.jabatan_nama',
                    't_jabatan_kegiatan.poin'
                )
                ->where('m_user.level', 'dosen')
                ->orderBy('m_user.nama')
                ->orderBy('t_kegiatan.tanggal_acara')
                ->get()
                ->groupBy('id_user');
        } elseif ($userLevel === 'dosen') {
            $userId = Auth::id();
    
            $poinDosen = DB::table('m_user')
                ->leftJoin('t_anggota', 'm_user.id_user', '=', 't_anggota.id_user')
                ->leftJoin('t_kegiatan', 't_anggota.id_kegiatan', '=', 't_kegiatan.id_kegiatan')
                ->leftJoin('t_jabatan_kegiatan', 't_anggota.id_jabatan_kegiatan', '=', 't_jabatan_kegiatan.id_jabatan_kegiatan')
                ->select(
                    'm_user.nama',
                    'm_user.id_user',
                    DB::raw('COUNT(DISTINCT t_anggota.id_kegiatan) as total_kegiatan'),
                    DB::raw('COALESCE(SUM(t_jabatan_kegiatan.poin), 0) as total_poin')
                )
                ->where('m_user.level', 'dosen')
                ->where('m_user.id_user', $userId)
                ->groupBy('m_user.id_user', 'm_user.nama')
                ->get();
    
            $dosenKegiatan = DB::table('m_user')
                ->leftJoin('t_anggota', 'm_user.id_user', '=', 't_anggota.id_user')
                ->leftJoin('t_kegiatan', 't_anggota.id_kegiatan', '=', 't_kegiatan.id_kegiatan')
                ->leftJoin('t_jabatan_kegiatan', 't_anggota.id_jabatan_kegiatan', '=', 't_jabatan_kegiatan.id_jabatan_kegiatan')
                ->select(
                    'm_user.id_user',
                    'm_user.nama',
                    't_kegiatan.nama_kegiatan',
                    't_kegiatan.tanggal_acara',
                    't_kegiatan.jenis_kegiatan',
                    't_jabatan_kegiatan.jabatan_nama',
                    't_jabatan_kegiatan.poin'
                )
                ->where('m_user.level', 'dosen')
                ->where('m_user.id_user', $userId)
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