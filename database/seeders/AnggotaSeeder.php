<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AnggotaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('t_anggota')->truncate();

        DB::table('t_anggota')->insert([
            [
                'id_kegiatan' => 1,
                'id_user' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_kegiatan' => 1,
                'id_user' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_kegiatan' => 2,
                'id_user' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_kegiatan' => 2,
                'id_user' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_kegiatan' => 3,
                'id_user' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}