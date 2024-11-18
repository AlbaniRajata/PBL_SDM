<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AnggotaSeeder extends Seeder
{
    public function run()
    {
        DB::table('t_anggota')->insert([
            [
                'id_kegiatan' => 1,
                'id_user' => 1,
                'id_jabatan_kegiatan' => 1,
            ],
            [
                'id_kegiatan' => 1,
                'id_user' => 2,
                'id_jabatan_kegiatan' => 2,
            ],
            [
                'id_kegiatan' => 1,
                'id_user' => 3,
                'id_jabatan_kegiatan' => 3,
            ],
            [
                'id_kegiatan' => 1,
                'id_user' => 4,
                'id_jabatan_kegiatan' => 4,
            ],
            [
                'id_kegiatan' => 1,
                'id_user' => 5,
                'id_jabatan_kegiatan' => 5,
            ],
        ]);
    }
}