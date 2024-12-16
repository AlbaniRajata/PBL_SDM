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
                'id_user' => 3,
                'id_jabatan_kegiatan' => 1,
            ],
            [
                'id_kegiatan' => 1,
                'id_user' => 7,
                'id_jabatan_kegiatan' => 2,
            ],
            [
                'id_kegiatan' => 1,
                'id_user' => 8,
                'id_jabatan_kegiatan' => 3,
            ],
            [
                'id_kegiatan' => 1,
                'id_user' => 9,
                'id_jabatan_kegiatan' => 4,
            ],
            [
                'id_kegiatan' => 1,
                'id_user' => 11,
                'id_jabatan_kegiatan' => 5,
            ],
            [
                'id_kegiatan' => 2,
                'id_user' => 3,
                'id_jabatan_kegiatan' => 1,
            ],
            [
                'id_kegiatan' => 2,
                'id_user' => 7,
                'id_jabatan_kegiatan' => 2,
            ],
            [
                'id_kegiatan' => 2,
                'id_user' => 8,
                'id_jabatan_kegiatan' => 3,
            ],
            [
                'id_kegiatan' => 2,
                'id_user' => 9,
                'id_jabatan_kegiatan' => 4,
            ],
            [
                'id_kegiatan' => 2,
                'id_user' => 11,
                'id_jabatan_kegiatan' => 5,
            ],
            [
                'id_kegiatan' => 3,
                'id_user' => 3,
                'id_jabatan_kegiatan' => 1,
            ],
            [
                'id_kegiatan' => 3,
                'id_user' => 7,
                'id_jabatan_kegiatan' => 2,
            ],
            [
                'id_kegiatan' => 3,
                'id_user' => 8,
                'id_jabatan_kegiatan' => 3,
            ],
            [
                'id_kegiatan' => 3,
                'id_user' => 9,
                'id_jabatan_kegiatan' => 4,
            ],
            [
                'id_kegiatan' => 3,
                'id_user' => 11,
                'id_jabatan_kegiatan' => 5,
            ],
            [
                'id_kegiatan' => 4,
                'id_user' => 3,
                'id_jabatan_kegiatan' => 1,
            ],
            [
                'id_kegiatan' => 4,
                'id_user' => 7,
                'id_jabatan_kegiatan' => 2,
            ],
            [
                'id_kegiatan' => 4,
                'id_user' => 8,
                'id_jabatan_kegiatan' => 3,
            ],
            [
                'id_kegiatan' => 4,
                'id_user' => 9,
                'id_jabatan_kegiatan' => 4,
            ],
            [
                'id_kegiatan' => 4,
                'id_user' => 11,
                'id_jabatan_kegiatan' => 5,
            ],
            [
                'id_kegiatan' => 5,
                'id_user' => 3,
                'id_jabatan_kegiatan' => 1,
            ],
            [
                'id_kegiatan' => 5,
                'id_user' => 7,
                'id_jabatan_kegiatan' => 2,
            ],
            [
                'id_kegiatan' => 5,
                'id_user' => 8,
                'id_jabatan_kegiatan' => 3,
            ],
            [
                'id_kegiatan' => 5,
                'id_user' => 9,
                'id_jabatan_kegiatan' => 4,
            ],
            [
                'id_kegiatan' => 5,
                'id_user' => 11,
                'id_jabatan_kegiatan' => 5,
            ],
        ]);
    }
}