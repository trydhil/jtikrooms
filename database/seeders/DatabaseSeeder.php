<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run()
{
    DB::table('admin')->delete();
    DB::table('kelas')->delete();

    // Insert admin
    DB::table('admin')->insert([
        'username' => 'admin',
        'password' => md5('admin123'),
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    // Insert kelas data dengan field baru
    $kelasData = [
        ['username' => 'TEKOM_A', 'password' => md5('Tka$532'), 'angkatan' => '2023', 'prodi' => 'TEKOM', 'kelas' => 'A'],
        ['username' => 'TEKOM_B', 'password' => md5('Tkb@181'), 'angkatan' => '2023', 'prodi' => 'TEKOM', 'kelas' => 'B'],
        ['username' => 'TEKOM_C', 'password' => md5('Tkc!927'), 'angkatan' => '2023', 'prodi' => 'TEKOM', 'kelas' => 'C'],
        ['username' => 'TEKOM_D', 'password' => md5('Tkd#731'), 'angkatan' => '2023', 'prodi' => 'TEKOM', 'kelas' => 'D'],
        ['username' => 'TEKOM_E', 'password' => md5('Tke%214'), 'angkatan' => '2023', 'prodi' => 'TEKOM', 'kelas' => 'E'],
        ['username' => 'TEKOM_F', 'password' => md5('Tkf*912'), 'angkatan' => '2023', 'prodi' => 'TEKOM', 'kelas' => 'F'],
        ['username' => 'PTIK_A', 'password' => md5('Pta$451'), 'angkatan' => '2023', 'prodi' => 'PTIK', 'kelas' => 'A'],
        ['username' => 'PTIK_B', 'password' => md5('Ptb@619'), 'angkatan' => '2023', 'prodi' => 'PTIK', 'kelas' => 'B'],
        ['username' => 'PTIK_C', 'password' => md5('Ptc!781'), 'angkatan' => '2023', 'prodi' => 'PTIK', 'kelas' => 'C'],
        ['username' => 'PTIK_D', 'password' => md5('Ptd#824'), 'angkatan' => '2023', 'prodi' => 'PTIK', 'kelas' => 'D'],
        ['username' => 'PTIK_E', 'password' => md5('Pte%193'), 'angkatan' => '2023', 'prodi' => 'PTIK', 'kelas' => 'E'],
        ['username' => 'PTIK_F', 'password' => md5('Ptf*500'), 'angkatan' => '2023', 'prodi' => 'PTIK', 'kelas' => 'F'],
        ['username' => 'PTIK_G', 'password' => md5('Ptg@251'), 'angkatan' => '2023', 'prodi' => 'PTIK', 'kelas' => 'G'],
        ['username' => 'PTIK_H', 'password' => md5('Pth#603'), 'angkatan' => '2023', 'prodi' => 'PTIK', 'kelas' => 'H'],
        ['username' => 'PTIK_I', 'password' => md5('Pti$730'), 'angkatan' => '2023', 'prodi' => 'PTIK', 'kelas' => 'I'],
    ];

    foreach ($kelasData as $data) {
        DB::table('kelas')->insert([
            'username' => $data['username'],
            'password' => $data['password'],
            'angkatan' => $data['angkatan'],
            'prodi' => $data['prodi'],
            'kelas' => $data['kelas'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}}