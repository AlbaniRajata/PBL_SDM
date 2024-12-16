<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AgendaAnggotaSeeder extends Seeder
{
    public function run()
    {
        DB::table('m_agenda_anggota')->insert([
            [
                'id_agenda' => 1,
                'id_anggota' => 1,
                'nama_agenda' => 'Agenda Pertama',
            ],
            [
                'id_agenda' => 2,
                'id_anggota' => 2,
                'nama_agenda' => 'Agenda Kedua',
            ],
            [
                'id_agenda' => 3,
                'id_anggota' => 3,
                'nama_agenda' => 'Agenda Ketiga',
            ],
            [
                'id_agenda' => 4,
                'id_anggota' => 4,
                'nama_agenda' => 'Agenda Keempat',
            ],
            [
                'id_agenda' => 5,
                'id_anggota' => 5,
                'nama_agenda' => 'Agenda Kelima',
            ],
        ]);
    }
}
