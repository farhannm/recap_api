<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            'id' => 1,
            'role' => 'admin'
        ]);

        DB::table('roles')->insert([
            'id' => 2,
            'role' => 'user'
        ]);


        \App\Models\User::create([
            'id' => 1,
            'id_role' => 1,
            'nama' => 'Admin Bara',
            'email' => 'admin.bara@gmail.com',
            'password' => Hash::make('adminbara'),
            'telp' => '08990671253',
            'alamat' => 'Neptunus',
            'jabatan' => 'Administrator',
        ]);

        \App\Models\User::create([
            'id_role' => 2,
            'nama' => 'Farhan Maulana',
            'email' => 'farhannote41@gmail.com',
            'password' => Hash::make('paanmaul'),
            'telp' => '08990632122',
            'alamat' => 'Cihanjuang',
            'jabatan' => 'Assasin',
        ]);

        \App\Models\Pekerjaan::create([
            'id_user' => 1,
            'bulan' => 'Januari',
            'start' => '2023-01-01',
            'end' => '2023-01-31',
            'jam_toleransi' => 22,
            'total_jam' => 162
        ]);

        \App\Models\DetailPekerjaan::create([
            'id_user' => 2,
            'id_pekerjaan' => 1,
            'nama_pekerjaan' => 'Memahami RESTful API',
            'desc_pekerjaan' => 'Memahami dan membuat API sederhana menggunakan PHP Native.',
            'bukti_pekerjaan' => 'image.png',
            'jam_kerja' => 8,
            'tgl_kerja' => '2023-01-21',
            'tipe' => 'Progress weekday'
        ]);
    }
}

