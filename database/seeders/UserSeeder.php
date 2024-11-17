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
            ]
        ]);
    }
}