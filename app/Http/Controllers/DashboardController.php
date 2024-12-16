<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\KegiatanModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Selamat datang',
            'list' => ['Home', 'Dashboard'],
        ];

        $jumlahDosen = DB::table('m_user')->where('level', 'dosen')->count();
        $jumlahKegiatanJTI = DB::table('t_kegiatan')->where('jenis_kegiatan', 'Kegiatan JTI')->count();
        $jumlahKegiatanNonJTI = DB::table('t_kegiatan')->where('jenis_kegiatan', 'Kegiatan Non-JTI')->count();

        $activeMenu = 'dashboard';
        return view('welcome', [
            'breadcrumb' => $breadcrumb,
            'activeMenu' => $activeMenu,
            'jumlahDosen' => $jumlahDosen,
            'jumlahKegiatanJTI' => $jumlahKegiatanJTI,
            'jumlahKegiatanNonJTI' => $jumlahKegiatanNonJTI
        ]);
    }

    public function indexDosen()
    {
        $breadcrumb = (object) [
            'title' => 'Selamat datang',
            'list' => ['Home', 'Dashboard'],
        ];
        $activeMenu = 'dashboard';

        // Ambil ID pengguna yang sedang login
        $userId = Auth::id();

        // Ambil data kegiatan yang terkait dengan pengguna yang sedang login
        $kegiatanAkanDatang = KegiatanModel::whereHas('anggota', function ($query) use ($userId) {
            $query->where('id_user', $userId);
        })
            ->where('tanggal_mulai', '>=', now())
            ->get()
            ->map(function ($kegiatan) {
                $kegiatan->tanggal_mulai = Carbon::parse($kegiatan->tanggal_mulai);
                return $kegiatan;
            });

        return view('dosenWelcome', [
            'breadcrumb' => $breadcrumb,
            'activeMenu' => $activeMenu,
            'kegiatanAkanDatang' => $kegiatanAkanDatang
        ]);
    }
}
