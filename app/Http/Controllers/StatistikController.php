<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StatistikController extends Controller
{
    // function index
    public function admin(){
        $breadcrumb = (object) [
            'title' => 'Home',
            'list' => ['Home','Statistik Admin'],
        ];
        $activeMenu = 'statistik admin';
        return view('admin.statistik.index',['breadcrumb' => $breadcrumb, 'activeMenu' => $activeMenu]);
    }

    public function pimpinan(){
        $breadcrumb = (object) [
            'title' => 'Home',
            'list' => ['Home','Statistik Pimpinan'],
        ];
        $activeMenu = 'statistik pimpinan';
        return view('pimpinan.statistik.index',['breadcrumb' => $breadcrumb, 'activeMenu' => $activeMenu]);
    }

    public function dosenPIC(){
        $breadcrumb = (object) [
            'title' => 'Home',
            'list' => ['Home','Statistik DosenPIC'],
        ];
        $activeMenu = 'statistik pic';
        return view('dosenPIC.statistik.index',['breadcrumb' => $breadcrumb, 'activeMenu' => $activeMenu]);
    }

    public function dosenAnggota(){
        $breadcrumb = (object) [
            'title' => 'Home',
            'list' => ['Home','Statistik Dosen Anggota'],
        ];
        $activeMenu = 'statistik anggota';
        return view('dosenAnggota.statistik.index',['breadcrumb' => $breadcrumb, 'activeMenu' => $activeMenu]);
    }
}