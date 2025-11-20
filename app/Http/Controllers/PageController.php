<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;

class PageController extends Controller
{
    public function index()
    {
        return view('index'); // ✅ PERBAIKI DI SINI
    }

    public function roomInfo($name)
    {
        // Decode nama ruangan dari URL
        $roomName = urldecode($name);
        
        // Cari ruangan di database
        $room = Room::where('name', $roomName)->first();
        
        // Jika tidak ditemukan, buat data dummy untuk testing
        if (!$room) {
            $room = new Room();
            $room->name = $roomName;
            $room->display_name = $roomName . ' - Ruang Kelas';
            $room->location = 'Gedung JTIK';
            $room->description = 'Ruangan kelas dengan fasilitas lengkap untuk pembelajaran';
            $room->capacity = 40;
            $room->status = 'available';
            $room->facilities = ['AC', 'Proyektor', 'Whiteboard', 'WiFi', 'Kursi Ergonomis'];
        }
        
        // Kirim data ke view
        return view('room.info', compact('room'));
    }

    public function about()
    {
        return view('about');
    }
     // ✅ TAMBAH METHOD INI
    public function informasi()
    {
        return view('informasi');
    }
}