<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\Booking;
use App\Models\Comment;
use App\Models\Queue; // ✅ TAMBAH INI!
use Carbon\Carbon;

class PageController extends Controller
{
   public function index()
{
    // Menggunakan scope optimization agar query database ringan (Hanya 3 query)
    $rooms = Room::withAvailability()->get();

    // Mengirimkan variabel $rooms ke view index
    return view('index', compact('rooms'));
}
    public function roomInfo($name)
{
    // Decode nama ruangan dari URL
    $roomName = urldecode($name);
    
    // Cari ruangan di database dengan booking aktif dan komentar
    $room = Room::where('name', $roomName)
                ->with(['comments' => function($query) {
                    $query->where('status', 'open')
                          ->orderBy('created_at', 'desc')
                          ->take(5);
                }])
                ->first();
    
    if (!$room) {
        // ... data dummy (jika ada)
        abort(404, 'Ruangan tidak ditemukan');
    }

    // Hitung booking aktif untuk ruangan ini
    $activeBookingsCount = Booking::where('room_name', $roomName)
        ->where('status', 'active')
        ->where('waktu_berakhir', '>', now()->timezone('Asia/Makassar'))
        ->count();

    // Cari booking yang sedang berlangsung
    $activeBooking = Booking::where('room_name', $roomName)
        ->where('status', 'active')
        ->where('waktu_mulai', '<=', now()->timezone('Asia/Makassar'))
        ->where('waktu_berakhir', '>', now()->timezone('Asia/Makassar'))
        ->first();
        
    // Hitung antrian hari ini (jika masih perlu)
    $todayQueues = Queue::where('room_id', $room->id)
        ->whereDate('created_at', Carbon::today())
        ->count();
        
    return view('room.info', compact('room', 'activeBooking', 'activeBookingsCount', 'todayQueues'));
}

    public function about()
    {
        return view('about');
    }
    
    public function informasi()
    {
        return view('informasi');
    }
}