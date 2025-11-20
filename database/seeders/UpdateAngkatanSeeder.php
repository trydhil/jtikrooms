<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kelas;

class UpdateAngkatanSeeder extends Seeder
{
    public function run()
    {
        $users = Kelas::all();
        
        foreach ($users as $user) {
            if (!$user->angkatan) {
                // Set angkatan berdasarkan username atau random
                $angkatan = '2023'; // Default
                
                if (strpos($user->username, 'TEKOM') !== false || strpos($user->username, 'PTIK') !== false) {
                    $angkatan = '2023';
                }
                
                $user->update(['angkatan' => $angkatan]);
            }
        }
        
        $this->command->info('✅ Angkatan berhasil diupdate untuk semua user!');
    }
}