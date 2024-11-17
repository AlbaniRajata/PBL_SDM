<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JabatanKegiatanSeeder extends Seeder
{
    public function run()
    {
        DB::table('t_jabatan_kegiatan')->insert([
            [
                'jabatan_nama' => 'pic',
                'poin' => 2,
            ],
            [
                'jabatan_nama' => 'sekretaris',
                'poin' => 0.5,
            ],
            [
                'jabatan_nama' => 'bendahara',
                'poin' => 0.5,
            ],
            [
                'jabatan_nama' => 'pembina',
                'poin' => 0.5,
            ],
            [
                'jabatan_nama' => 'anggota1',
                'poin' => 0.5,
            ],[
                'jabatan_nama' => 'anggota2',
                'poin' => 0.5,
            ],
        ]);
    }
}