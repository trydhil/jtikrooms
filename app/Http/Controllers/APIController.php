<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\Booking;

class APIController extends Controller
{
    // === METHOD BARU YANG DIBUTUHKAN ===
    
    /**
     * Get all room statuses for dashboard
     */
    public function getAllRoomStatuses()
{
    try {
        $allRooms = [
            'AE 101', 'AE 102', 'AE 103', 'AE 104', 'AE 105', 'AE 106', 'AE 107', 'AE 209',
            'Lab Animasi', 'IT Workshop', 'Lab Jaringan', 'Lab Programing', 'Lab Sistem Cerdas', 'Lab Embeded',
            'Sekretariat HIMATIK', 'Ruangan Admin', 'Perpustakaan', 'Ruangan Sekertaris Jurusan',
            'Ruangan Kepala Laboratorium', 'Ruangan Dosen', 'Ruangan Ketua Prodi TEKOM', 
            'Ruangan Ujian', 'Ruangan Ketua Prodi PTIK'
        ];
        
        $roomStatuses = [];
        
        foreach ($allRooms as $roomName) {
            // Cek di database rooms
            $room = Room::where('name', $roomName)->first();
            
            if ($room) {
                // Cek booking aktif dengan informasi lengkap
                $activeBooking = Booking::where('room_name', $roomName)
                    ->where('status', 'active')
                    ->where('waktu_berakhir', '>', now())
                    ->first();
                
                if ($activeBooking) {
                    $roomStatuses[$roomName] = [
                        'status' => 'occupied',
                        'booking_info' => [
                            'username' => $activeBooking->username,
                            'mata_kuliah' => $activeBooking->mata_kuliah,
                            'dosen' => $activeBooking->dosen,
                            'waktu_mulai' => $activeBooking->waktu_mulai->format('H:i'),
                            'waktu_berakhir' => $activeBooking->waktu_berakhir->format('H:i'),
                            'time_left' => $activeBooking->waktu_berakhir->diffForHumans(),
                            'minutes_left' => $activeBooking->waktu_berakhir->diffInMinutes(now())
                        ]
                    ];
                } else {
                    $roomStatuses[$roomName] = [
                        'status' => $room->status,
                        'booking_info' => null
                    ];
                }
            } else {
                // Fallback untuk room yang belum ada di database
                $roomStatuses[$roomName] = [
                    'status' => 'available',
                    'booking_info' => null
                ];
            }
        }
        
        return response()->json([
            'success' => true,
            'data' => $roomStatuses
        ]);
        
    } catch (\Exception $e) {
        \Log::error('Error getting room status: ' . $e->getMessage());
        
        return response()->json([
            'success' => false,
            'message' => 'Error getting room status',
            'error' => $e->getMessage()
        ], 500);
    }
}

    /**
     * Get available rooms list
     */
    public function getAvailableRooms()
    {
        try {
            $rooms = Room::all()->map(function($room) {
                return [
                    'name' => $room->name,
                    'display_name' => $room->display_name,
                    'description' => $room->description,
                    'capacity' => $room->capacity,
                    'facilities' => $room->facilities,
                    'location' => $room->location,
                    'status' => $room->status
                ];
            });
            
            return response()->json([
                'success' => true,
                'data' => $rooms
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error getting available rooms: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error getting rooms list',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // === METHOD YANG SUDAH ADA ===
    
    // Scan QR Code & Validasi
    public function scanQR(Request $request)
    {
        try {
            $request->validate([
                'room_name' => 'required|string',
                'user_id' => 'required'
            ]);
            
            $room = Room::where('name', $request->room_name)->first();
            
            if (!$room) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ruangan tidak ditemukan'
                ], 404);
            }
            
            // Cek status ruangan
            if ($room->status !== 'available') {
                return response()->json([
                    'success' => false,
                    'message' => 'Ruangan sedang ' . ($room->status === 'maintenance' ? 'dalam perbaikan' : 'tidak tersedia')
                ], 400);
            }
            
            // Cek booking aktif
            $activeBooking = Booking::where('room_name', $request->room_name)
                ->where('status', 'active')
                ->where('waktu_berakhir', '>', now())
                ->first();
            
            if ($activeBooking) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ruangan sedang digunakan',
                    'booking_info' => [
                        'user' => $activeBooking->username,
                        'mata_kuliah' => $activeBooking->mata_kuliah,
                        'until' => $activeBooking->waktu_berakhir->format('H:i'),
                        'time_left' => $activeBooking->waktu_berakhir->diffForHumans()
                    ]
                ], 400);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Ruangan tersedia untuk booking',
                'room' => $room,
                'booking_url' => url("/booking/create/{$room->name}")
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error scanning QR'
            ], 500);
        }
    }
    
    // Get Room Status (single room)
    public function getRoomStatus($roomName)
    {
        try {
            $room = Room::where('name', $roomName)->first();
            
            if (!$room) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ruangan tidak ditemukan'
                ], 404);
            }
            
            $activeBooking = Booking::where('room_name', $roomName)
                ->where('status', 'active')
                ->where('waktu_berakhir', '>', now())
                ->first();
            
            return response()->json([
                'success' => true,
                'data' => [
                    'room' => $room,
                    'status' => $activeBooking ? 'occupied' : $room->status,
                    'current_booking' => $activeBooking
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error getting room status'
            ], 500);
        }
    }
    
    // Quick Booking via Mobile
    public function quickBooking(Request $request)
    {
        try {
            $request->validate([
                'room_name' => 'required',
                'username' => 'required',
                'mata_kuliah' => 'required',
                'dosen' => 'required',
                'duration_minutes' => 'required|integer|min:30|max:240'
            ]);
            
            // Validasi user
            $userExists = \App\Models\Kelas::where('username', $request->username)->exists();
            if (!$userExists) {
                return response()->json([
                    'success' => false,
                    'message' => 'User tidak terdaftar'
                ], 400);
            }
            
            // Cek overlap
            $waktuBerakhir = now()->addMinutes($request->duration_minutes);
            
            $overlap = Booking::where('room_name', $request->room_name)
                ->where('status', 'active')
                ->where('waktu_berakhir', '>', now())
                ->first();
                
            if ($overlap) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ruangan sudah dibooking sampai ' . $overlap->waktu_berakhir->format('H:i')
                ], 400);
            }
            
            // Create booking
            $booking = Booking::create([
                'room_name' => $request->room_name,
                'username' => $request->username,
                'mata_kuliah' => $request->mata_kuliah,
                'dosen' => $request->dosen,
                'waktu_mulai' => now(),
                'waktu_berakhir' => $waktuBerakhir,
                'status' => 'active',
                'keterangan' => 'Booking via QR Code'
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Booking berhasil! Ruangan: ' . $booking->room_name,
                'booking' => $booking
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat booking'
            ], 500);
        }
    }
}