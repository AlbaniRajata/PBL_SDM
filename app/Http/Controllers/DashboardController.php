<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(){
        $breadcrumb = (object) [
            'title' => 'Home',
            'list' => ['Home','Dashboard'],
        ];
        $activeMenu = 'dashboard';
        return view('welcome',['breadcrumb' => $breadcrumb, 'activeMenu' => $activeMenu]);
    }
}