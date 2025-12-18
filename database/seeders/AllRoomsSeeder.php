<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Room;

class AllRoomsSeeder extends Seeder
{
    public function run()
    {
        $rooms = [
            // Ruangan Kelas
            [
                'name' => 'AE 101',
                'display_name' => 'Ruangan AE 101',
                'location' => 'Gedung JTIK - Lantai 1',
                'type' => 'kelas',
                'description' => 'Ruang kelas reguler',
                'capacity' => 40,
                'status' => 'available',
                'facilities' => ['AC', 'Proyektor', 'Whiteboard', 'WiFi']
            ],
            [
                'name' => 'AE 102',
                'display_name' => 'Ruangan AE 102', 
                'location' => 'Gedung JTIK - Lantai 1',
                'type' => 'kelas',
                'description' => 'Ruang kelas standar',
                'capacity' => 35,
                'status' => 'available',
                'facilities' => ['AC', 'Whiteboard', 'WiFi']
            ],
            [
                'name' => 'AE 103',
                'display_name' => 'Ruangan AE 103',
                'location' => 'Gedung JTIK - Lantai 1',
                'type' => 'kelas',
                'description' => 'Ruang kelas standar',
                'capacity' => 35,
                'status' => 'available',
                'facilities' => ['AC', 'Whiteboard', 'WiFi']
            ],
            [
                'name' => 'AE 104',
                'display_name' => 'Ruangan AE 104',
                'location' => 'Gedung JTIK - Lantai 1',
                'type' => 'kelas',
                'description' => 'Ruang kelas standar',
                'capacity' => 35,
                'status' => 'available',
                'facilities' => ['AC', 'Whiteboard', 'WiFi']
            ],
            [
                'name' => 'AE 105',
                'display_name' => 'Ruangan AE 105',
                'location' => 'Gedung JTIK - Lantai 1',
                'type' => 'kelas',
                'description' => 'Ruang kelas standar',
                'capacity' => 35,
                'status' => 'available',
                'facilities' => ['AC', 'Whiteboard', 'WiFi']
            ],
            [
                'name' => 'AE 106',
                'display_name' => 'Ruangan AE 106',
                'location' => 'Gedung JTIK - Lantai 1',
                'type' => 'kelas',
                'description' => 'Ruang kelas standar',
                'capacity' => 35,
                'status' => 'available',
                'facilities' => ['AC', 'Whiteboard', 'WiFi']
            ],
            [
                'name' => 'AE 107',
                'display_name' => 'Ruangan AE 107',
                'location' => 'Gedung JTIK - Lantai 1',
                'type' => 'kelas',
                'description' => 'Ruang kelas standar',
                'capacity' => 35,
                'status' => 'available',
                'facilities' => ['AC', 'Whiteboard', 'WiFi']
            ],
            [
                'name' => 'AE 209',
                'display_name' => 'Ruangan AE 209',
                'location' => 'Gedung JTIK - Lantai 2',
                'type' => 'kelas',
                'description' => 'Ruang kelas lantai 2',
                'capacity' => 40,
                'status' => 'available',
                'facilities' => ['AC', 'Proyektor', 'Whiteboard', 'WiFi']
            ],

            // Laboratorium
            [
                'name' => 'Lab Animasi',
                'display_name' => 'Laboratorium Animasi',
                'location' => 'Gedung JTIK - Lantai 2',
                'type' => 'lab',
                'description' => 'Laboratorium untuk praktikum animasi dan multimedia',
                'capacity' => 30,
                'status' => 'available',
                'facilities' => ['AC', 'Komputer', 'Software Animasi', 'Proyektor', 'WiFi']
            ],
            [
                'name' => 'IT Workshop',
                'display_name' => 'IT Workshop',
                'location' => 'Gedung JTIK - Lantai 2',
                'type' => 'lab',
                'description' => 'Workshop untuk praktikum IT',
                'capacity' => 25,
                'status' => 'available',
                'facilities' => ['AC', 'Komputer', 'Tools IT', 'WiFi']
            ],
            [
                'name' => 'Lab Jaringan',
                'display_name' => 'Laboratorium Jaringan',
                'location' => 'Gedung JTIK - Lantai 2',
                'type' => 'lab',
                'description' => 'Laboratorium jaringan komputer',
                'capacity' => 20,
                'status' => 'available',
                'facilities' => ['AC', 'Router', 'Switch', 'Kabel Jaringan', 'Tools Network']
            ],
            [
                'name' => 'Lab Programing',
                'display_name' => 'Laboratorium Programming',
                'location' => 'Gedung JTIK - Lantai 2',
                'type' => 'lab',
                'description' => 'Laboratorium pemrograman',
                'capacity' => 30,
                'status' => 'available',
                'facilities' => ['AC', 'Komputer', 'Software Development', 'WiFi']
            ],
            [
                'name' => 'Lab Sistem Cerdas',
                'display_name' => 'Laboratorium Sistem Cerdas',
                'location' => 'Gedung JTIK - Lantai 2',
                'type' => 'lab',
                'description' => 'Laboratorium sistem cerdas dan AI',
                'capacity' => 25,
                'status' => 'available',
                'facilities' => ['AC', 'Komputer', 'Software AI', 'WiFi']
            ],
            [
                'name' => 'Lab Embeded',
                'display_name' => 'Laboratorium Embedded',
                'location' => 'Gedung JTIK - Lantai 2',
                'type' => 'lab',
                'description' => 'Laboratorium sistem embedded',
                'capacity' => 20,
                'status' => 'available',
                'facilities' => ['AC', 'Microcontroller', 'Tools Elektronik', 'WiFi']
            ],

            // Ruangan Lainnya
            [
                'name' => 'Sekretariat HIMATIK',
                'display_name' => 'Sekretariat HIMATIK',
                'location' => 'Gedung JTIK - Lantai 2',
                'type' => 'other',
                'description' => 'Ruangan sekretariat himpunan mahasiswa teknik informatika',
                'capacity' => 15,
                'status' => 'available',
                'facilities' => ['AC', 'Meja Kerja', 'Kursi', 'WiFi']
            ],
            [
                'name' => 'Ruangan Admin',
                'display_name' => 'Ruangan Administrator',
                'location' => 'Gedung JTIK - Lantai 2',
                'type' => 'other',
                'description' => 'Ruangan administrasi jurusan',
                'capacity' => 10,
                'status' => 'available',
                'facilities' => ['AC', 'Meja Kerja', 'Komputer', 'Printer', 'WiFi']
            ],
            [
                'name' => 'Perpustakaan',
                'display_name' => 'Perpustakaan JTIK',
                'location' => 'Gedung JTIK - Lantai 2',
                'type' => 'other',
                'description' => 'Perpustakaan jurusan teknik informatika',
                'capacity' => 50,
                'status' => 'available',
                'facilities' => ['AC', 'Rak Buku', 'Meja Baca', 'WiFi', 'Komputer']
            ],
            [
                'name' => 'Ruangan Sekertaris Jurusan',
                'display_name' => 'Ruangan Sekretaris Jurusan',
                'location' => 'Gedung JTIK - Lantai 2',
                'type' => 'other',
                'description' => 'Ruangan sekretaris jurusan',
                'capacity' => 8,
                'status' => 'available',
                'facilities' => ['AC', 'Meja Kerja', 'Komputer', 'Printer', 'WiFi']
            ],
            [
                'name' => 'Ruangan Kepala Laboratorium',
                'display_name' => 'Ruangan Kepala Lab',
                'location' => 'Gedung JTIK - Lantai 2',
                'type' => 'other',
                'description' => 'Ruangan kepala laboratorium',
                'capacity' => 8,
                'status' => 'available',
                'facilities' => ['AC', 'Meja Kerja', 'Komputer', 'WiFi']
            ],
            [
                'name' => 'Ruangan Dosen',
                'display_name' => 'Ruangan Dosen',
                'location' => 'Gedung JTIK - Lantai 2',
                'type' => 'other',
                'description' => 'Ruangan dosen',
                'capacity' => 12,
                'status' => 'available',
                'facilities' => ['AC', 'Meja Kerja', 'Komputer', 'WiFi']
            ],
            [
                'name' => 'Ruangan Ketua Prodi TEKOM',
                'display_name' => 'Ruangan Ketua Prodi TEKOM',
                'location' => 'Gedung JTIK - Lantai 2',
                'type' => 'other',
                'description' => 'Ruangan ketua program studi teknik komputer',
                'capacity' => 8,
                'status' => 'available',
                'facilities' => ['AC', 'Meja Kerja', 'Komputer', 'WiFi']
            ],
            [
                'name' => 'Ruangan Ujian',
                'display_name' => 'Ruangan Ujian',
                'location' => 'Gedung JTIK - Lantai 2',
                'type' => 'other',
                'description' => 'Ruangan khusus ujian',
                'capacity' => 40,
                'status' => 'available',
                'facilities' => ['AC', 'Meja Ujian', 'Kursi', 'Whiteboard']
            ],
            [
                'name' => 'Ruangan Ketua Prodi PTIK',
                'display_name' => 'Ruangan Ketua Prodi PTIK',
                'location' => 'Gedung JTIK - Lantai 2',
                'type' => 'other',
                'description' => 'Ruangan ketua program studi pendidikan teknik informatika',
                'capacity' => 8,
                'status' => 'available',
                'facilities' => ['AC', 'Meja Kerja', 'Komputer', 'WiFi']
            ]
        ];

        foreach ($rooms as $roomData) {
            Room::updateOrCreate(
                ['name' => $roomData['name']],
                $roomData
            );
        }

        $this->command->info('✅ Semua ruangan berhasil dimasukkan ke database!');
        $this->command->info('📊 Total: ' . count($rooms) . ' ruangan');
    }
}