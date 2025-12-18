<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // 1. Panggil Seeder Ruangan (Pastikan file AllRoomsFinalSeeder.php ada)
        $this->call([
            AllRoomsFinalSeeder::class, 
        ]);

        // 2. Bersihkan & Isi Ulang Admin
        DB::table('admin')->delete();
        DB::table('admin')->insert([
            'username' => 'admin',
            'password' => 'admin123', // Plain text sesuai Controller Auth
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 3. Bersihkan & Isi Ulang Kelas Dummy
        DB::table('kelas')->delete();
        DB::table('kelas')->insert([
            'username' => 'TEKOM_A',
            'password' => '123456',
            'nama_kelas' => 'Teknik Komputer Kelas A',
            'prodi' => 'TEKOM',
            'kelas' => 'A',
            'angkatan' => '2023',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}