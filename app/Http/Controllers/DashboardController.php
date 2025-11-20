<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\Booking;
use App\Models\Kelas;
use App\Models\Admin;

class DashboardController extends Controller
{
    public function admin()
    {
        if (!session('loggedin') || session('role') !== 'admin') {
            return redirect()->route('login')->with('error', 'Silakan login sebagai admin.');
        }
        
        // DATA REAL dari database
        $totalRooms = Room::count();
        $availableRooms = Room::where('status', 'available')->count();
        $totalUsers = Kelas::count() + Admin::count(); // Total kelas + admin
        
        // Booking data
        $todayBookings = Booking::whereDate('created_at', today())->count();
        $activeBookings = Booking::where('status', 'active')
            ->where('waktu_berakhir', '>', now())
            ->orderBy('waktu_berakhir')
            ->get();

        // Room status data
        $rooms = Room::orderBy('name')->get();
        $roomStatus = [];
        
        foreach ($rooms as $room) {
            $activeBooking = Booking::where('room_name', $room->name)
                ->where('status', 'active')
                ->where('waktu_berakhir', '>', now())
                ->first();
                
            $roomStatus[$room->name] = [
                'status' => $activeBooking ? 'occupied' : $room->status,
                'display_name' => $room->display_name,
                'room' => $room
            ];
        }

        return view('dashboard.admin', [
            'totalRooms' => $totalRooms,
            'availableRooms' => $availableRooms,
            'totalUsers' => $totalUsers,
            'todayBookings' => $todayBookings,
            'activeBookings' => $activeBookings,
            'roomStatus' => $roomStatus,
            'rooms' => $rooms
        ]);
    }

    public function kelas()
    {
        if (!session('loggedin') || session('role') !== 'kelas') {
            return redirect()->route('login')->with('error', 'Silakan login sebagai perwakilan kelas.');
        }
        
        return view('dashboard.kelas');
    }
}