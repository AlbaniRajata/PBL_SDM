<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DokumenSeeder extends Seeder
{
    public function run()
    {
        DB::table('t_dokumen')->insert([
            [
                'nama_dokumen' => 'Dokumen A',
                'progress' => 20,
            ],
            [
                'nama_dokumen' => 'Dokumen B',
                'progress' => 40,
            ],
            [
                'nama_dokumen' => 'Dokumen C',
                'progress' => 60,
            ],
            [
                'nama_dokumen' => 'Dokumen D',
                'progress' => 80,
            ],
            [
                'nama_dokumen' => 'Dokumen E',
                'progress' => 100,
            ],
        ]);
    }
}