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
                'nama_dokumen' => 'Percobaan 1',
                'progress' => 20,
            ],
        ]);
    }
}
