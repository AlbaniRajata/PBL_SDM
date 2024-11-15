<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class UserSeeder extends Seeder
{
    public function run()
    {
        DB::table('t_user')->insert([
            [
                'username' => 'admin001',
                'nama' => 'Admin Sistem',
                'email' => 'admin@sistem.com',
                'password' => Hash::make('password123'),
                'NIP' => '123456789',
                'level' => 'admin',
                'poin' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'pimpinan001',
                'nama' => 'Pimpinan Utama',
                'email' => 'pimpinan@sistem.com',
                'password' => Hash::make('password123'),
                'NIP' => '987654321',
                'level' => 'pimpinan',
                'poin' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'dosen001',
                'nama' => 'Dosen Satu',
                'email' => 'dosen1@sistem.com',
                'password' => Hash::make('password123'),
                'NIP' => '123123123',
                'level' => 'dosen',
                'poin' => 7.5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'dosenPIC001',
                'nama' => 'Dosen PIC',
                'email' => 'dosenPIC@sistem.com',
                'password' => Hash::make('password123'),
                'NIP' => '456456456',
                'level' => 'dosenPIC',
                'poin' => 8.0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'dosenAnggota001',
                'nama' => 'Dosen Anggota',
                'email' => 'dosenAnggota@sistem.com',
                'password' => Hash::make('password123'),
                'NIP' => '789789789',
                'level' => 'dosenAnggota',
                'poin' => 6.5,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'username' => 'dosenAnggota002',
                'nama' => 'Dosen Anggota2',
                'email' => 'dosenAnggota2@sistem.com',
                'password' => Hash::make('password123'),
                'NIP' => '890890890',
                'level' => 'dosenAnggota',
                'poin' => 5.5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
