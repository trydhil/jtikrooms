<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache; 
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\Booking;
use App\Models\Kelas;
use App\Models\Admin;
use Carbon\Carbon;

class DashboardController extends Controller
{
   // app/Http/Controllers/DashboardController.php

public function admin()
{
    if (!session('loggedin') || session('role') !== 'admin') {
        return redirect()->route('login')->with('error', 'Silakan login sebagai admin.');
    }
    
    // 🚀 Single query with eager loading
    $rooms = Room::with(['activeBooking' => function($query) {
        $query->where('status', 'active')
              ->where('waktu_berakhir', '>', now())
              ->select('id', 'room_name', 'username', 'mata_kuliah', 
                       'dosen', 'waktu_mulai', 'waktu_berakhir');
    }])->orderBy('name')->get();
    
    // 🚀 Cached statistics (60 second cache)
    $stats = Cache::remember('admin_dashboard_stats', 60, function() {
        return [
            'totalRooms' => Room::count(),
            'availableRooms' => Room::where('status', 'available')->count(),
            'totalUsers' => Kelas::count() + Admin::count(),
            'todayBookings' => Booking::whereDate('created_at', today())->count(),
        ];
    });
    
    // 🚀 Optimized active bookings with single query
    $activeBookings = Booking::where('status', 'active')
        ->where('waktu_berakhir', '>', now())
        ->orderBy('waktu_berakhir')
        ->limit(20) // Pagination for large datasets
        ->get();
    
    // Build room status array
    $roomStatus = $rooms->mapWithKeys(function($room) {
        $activeBooking = $room->activeBooking;
        
        return [$room->name => [
            'status' => $activeBooking ? 'occupied' : $room->status,
            'display_name' => $room->display_name,
            'room' => $room,
            'booking' => $activeBooking
        ]];
    });

    return view('dashboard.admin', array_merge($stats, [
        'activeBookings' => $activeBookings,
        'roomStatus' => $roomStatus,
        'rooms' => $rooms
    ]));
}

    public function kelas()
    {
        if (!session('loggedin') || session('role') !== 'kelas') {
            return redirect()->route('login')->with('error', 'Silakan login sebagai perwakilan kelas.');
        }
        
        return view('dashboard.kelas');
    }
}