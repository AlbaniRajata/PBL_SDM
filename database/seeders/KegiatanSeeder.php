<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class KegiatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        DB::table('t_kegiatan')->insert([
            [
                'nama_kegiatan' => 'Workshop Laravel',
                'deskripsi_kegiatan' => 'Workshop pengembangan aplikasi web menggunakan Laravel.',
                'tanggal_mulai' => Carbon::parse('2024-11-20'),
                'tanggal_selesai' => Carbon::parse('2024-11-22'),
                'tanggal_acara' => Carbon::parse('2024-11-21'),
                'tempat_kegiatan' => 'Gedung Serba Guna',
                'jenis_kegiatan' => 'Kegiatan JTI',
                'id_user' => 1,
            ],
            [
                'nama_kegiatan' => 'Seminar AI',
                'deskripsi_kegiatan' => 'Seminar pengenalan Artificial Intelligence di era digital.',
                'tanggal_mulai' => Carbon::parse('2024-12-05'),
                'tanggal_selesai' => Carbon::parse('2024-12-05'),
                'tanggal_acara' => Carbon::parse('2024-12-05'),
                'tempat_kegiatan' => 'Aula Kampus',
                'jenis_kegiatan' => 'Kegiatan Non-JTI',
                'id_user' => 3,
            ],
            [
                'nama_kegiatan' => 'Lomba Coding',
                'deskripsi_kegiatan' => 'Lomba coding tingkat universitas.',
                'tanggal_mulai' => Carbon::parse('2024-11-25'),
                'tanggal_selesai' => Carbon::parse('2024-11-26'),
                'tanggal_acara' => Carbon::parse('2024-11-25'),
                'tempat_kegiatan' => 'Lab Komputer 1',
                'jenis_kegiatan' => 'Kegiatan JTI',
                'id_user' => 2,
            ],
        ]);
    }
}