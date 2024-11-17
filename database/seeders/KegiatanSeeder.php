<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KegiatanSeeder extends Seeder
{
    public function run()
    {
        DB::table('t_kegiatan')->insert([
            [
                'nama_kegiatan' => 'Workshop AI',
                'deskripsi_kegiatan' => 'Workshop tentang Artificial Intelligence',
                'tanggal_mulai' => '2024-12-01',
                'tanggal_selesai' => '2024-12-02',
                'tanggal_acara' => '2024-12-01',
                'tempat_kegiatan' => 'Lab Komputer 1',
                'jenis_kegiatan' => 'Kegiatan JTI',
                'progress' => 50,
            ],
            [
                'nama_kegiatan' => 'Seminar Cyber Security',
                'deskripsi_kegiatan' => 'Seminar tentang keamanan siber',
                'tanggal_mulai' => '2024-12-05',
                'tanggal_selesai' => '2024-12-05',
                'tanggal_acara' => '2024-12-05',
                'tempat_kegiatan' => 'Aula Utama',
                'jenis_kegiatan' => 'Kegiatan JTI',
                'progress' => 70,
            ],
            [
                'nama_kegiatan' => 'Hackathon 2024',
                'deskripsi_kegiatan' => 'Kompetisi coding selama 24 jam',
                'tanggal_mulai' => '2024-12-10',
                'tanggal_selesai' => '2024-12-11',
                'tanggal_acara' => '2024-12-10',
                'tempat_kegiatan' => 'Lab Komputer 2',
                'jenis_kegiatan' => 'Kegiatan NON-JTI',
                'progress' => 30,
            ],
            [
                'nama_kegiatan' => 'Pelatihan Web Development',
                'deskripsi_kegiatan' => 'Pelatihan membuat website dengan Laravel',
                'tanggal_mulai' => '2024-12-15',
                'tanggal_selesai' => '2024-12-16',
                'tanggal_acara' => '2024-12-15',
                'tempat_kegiatan' => 'Lab Komputer 3',
                'jenis_kegiatan' => 'Kegiatan JTI',
                'progress' => 90,
            ],
            [
                'nama_kegiatan' => 'Konferensi Data Science',
                'deskripsi_kegiatan' => 'Konferensi tentang ilmu data',
                'tanggal_mulai' => '2024-12-20',
                'tanggal_selesai' => '2024-12-21',
                'tanggal_acara' => '2024-12-20',
                'tempat_kegiatan' => 'Aula Utama',
                'jenis_kegiatan' => 'Kegiatan NON-JTI',
                'progress' => 100,
            ],
        ]);
    }
}