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
                'username' => 'Fikri',
                'nama' => 'Fikri Setiawan',
                'tanggal_lahir' => '2003-04-05',
                'email' => 'fikri@gmail.com',
                'password' => Hash::make('123456'),
                'NIP' => '1234567890',
                'level' => 'admin',
            ],
            [
                'username' => 'Sofi',
                'nama' => 'Sofi lailatul',
                'tanggal_lahir' => '2003-11-02',
                'email' => 'sofi@gmail.com',
                'password' => Hash::make('123456'),
                'NIP' => '2345678901',
                'level' => 'pimpinan',
            ],
            [
                'username' => 'Albani',
                'nama' => 'Albani rajata',
                'tanggal_lahir' => '2004-11-03',
                'email' => 'bani@gmail.com',
                'password' => Hash::make('123456'),
                'NIP' => '3456789012',
                'level' => 'dosen',
            ],
            [
                'username' => 'Yunika',
                'nama' => 'Yunika Putri',
                'tanggal_lahir' => '2010-04-04',
                'email' => 'Yunika@gmail.com',
                'password' => Hash::make('123456'),
                'NIP' => '4567890123',
                'level' => 'admin',
            ],
            [
                'username' => 'Nur',
                'nama' => 'Nurhidayah',
                'tanggal_lahir' => '2002-05-05',
                'email' => 'nur@gmail.com',
                'password' => Hash::make('123456'),
                'NIP' => '5678901234',
                'level' => 'pimpinan',
            ],[
                'username' => 'Fitri',
                'nama' => 'Fitri Lizahra',
                'tanggal_lahir' => '2001-06-06',
                'email' => 'fitri@gmail.com',
                'password' => Hash::make('123456'),
                'NIP' => '6789012345',
                'level' => 'admin',
            ],[
                'username' => 'AryaW',
                'nama' => 'Arya Wijaya',
                'tanggal_lahir' => '1990-03-12',
                'email' => 'aryawijaya@gmail.com',
                'password' => Hash::make('123456'),
                'NIP' => '1980123456',
                'level' => 'dosen',
            ],[
                'username' => 'RinaP',
                'nama' => 'Rina Permata',
                'tanggal_lahir' => '1985-09-20',
                'email' => 'rinapermata@gmail.com',
                'password' => Hash::make('123456'),
                'NIP' => '1985098765',
                'level' => 'dosen',
            ],[
                'username' => 'DikaR',
                'nama' => 'Dika Ramadhan',
                'tanggal_lahir' => '1995-04-10',
                'email' => 'dikaramadhan@gmail.com',
                'password' => Hash::make('123456'),
                'NIP' => '1995043210',
                'level' => 'dosen',
            ],[
                'username' => 'SitiN',
                'nama' => 'Siti Nurhaliza',
                'tanggal_lahir' => '1992-07-15',
                'email' => 'sitinurhaliza@gmail.com',
                'password' => Hash::make('123456'),
                'NIP' => '1992076543',
                'level' => 'dosen',
            ],[
                'username' => 'FajarH',
                'nama' => 'Fajar Hidayat',
                'tanggal_lahir' => '1998-12-25',
                'email' => 'fajarhidayat@gmail.com',
                'password' => Hash::make('123456'),
                'NIP' => '1998120987',
                'level' => 'dosen',
            ]
        ]);
    }
}