<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AgendaSeeder extends Seeder
{
    public function run()
    {
        DB::table('t_agenda')->insert([
            [
                'id_kegiatan' => 1,
                'id_dokumen' => 1,
            ],
            [
                'id_kegiatan' => 2,
                'id_dokumen' => 1,
            ],
            [
                'id_kegiatan' => 3,
                'id_dokumen' => 1,
            ],
            [
                'id_kegiatan' => 4,
                'id_dokumen' => 1,
            ],
            [
                'id_kegiatan' => 5,
                'id_dokumen' => 1,
            ],
        ]);
    }
}
