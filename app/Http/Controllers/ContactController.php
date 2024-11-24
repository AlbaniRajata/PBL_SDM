<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Kontak',
            'list' => ['Home','Kontak'],
        ];

        $activeMenu='contact';

        return view('contact',['breadcrumb' => $breadcrumb, 'activeMenu' => $activeMenu]);
    }
}