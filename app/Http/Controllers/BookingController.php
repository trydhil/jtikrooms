<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB; // ✅ TAMBAHKAN INI
use Illuminate\Support\Facades\Cache; // ✅ TAMBAHKAN INI

class BookingController extends Controller
{
    // CREATE FORM - Tetap sama
    public function createFromQR($roomName)
    {
        $roomName = urldecode($roomName);
        
        $roomExists = Room::where('name', $roomName)->exists();
        
        if (!$roomExists) {
            // Fallback logic
        }
        
        return view('booking.create', ['roomName' => $roomName]);
    }

    // STORE BOOKING - Diperbarui
    public function store(Request $request)
    {
        $request->validate([
            'room_name' => 'required',
            'mata_kuliah' => 'required',
            'dosen' => 'required',
            'waktu_berakhir' => 'required|date|after:30 minutes',
            'keterangan' => 'nullable'
        ]);

        try {
            // 🚀 Use Laravel's timezone config (set in config/app.php)
            $waktuMulai = now(); // Already uses Asia/Makassar from config
            $waktuBerakhir = Carbon::parse($request->waktu_berakhir);
            
            // Validation
            if ($waktuMulai->diffInMinutes($waktuBerakhir) < 30) {
                return back()->with('error', 'Booking minimal 30 menit.')->withInput();
            }

            if ($waktuMulai->diffInHours($waktuBerakhir) > 5) {
                return back()->with('error', 'Booking maksimal 5 jam.')->withInput();
            }

            // 🚀 Database lock to prevent race conditions
            DB::transaction(function() use ($request, $waktuMulai, $waktuBerakhir) {
                // Check overlap with row-level locking
                $overlap = Booking::where('room_name', $request->room_name)
                    ->where('status', 'active')
                    ->where('waktu_berakhir', '>', now())
                    ->where(function($query) use ($waktuMulai, $waktuBerakhir) {
                        $query->whereBetween('waktu_mulai', [$waktuMulai, $waktuBerakhir])
                              ->orWhereBetween('waktu_berakhir', [$waktuMulai, $waktuBerakhir])
                              ->orWhere(function($q) use ($waktuMulai, $waktuBerakhir) {
                                  $q->where('waktu_mulai', '<', $waktuMulai)
                                    ->where('waktu_berakhir', '>', $waktuBerakhir);
                              });
                    })
                    ->lockForUpdate() // 🔒 Prevents double booking
                    ->first();

                if ($overlap) {
                    throw new \Exception('Ruangan sudah dibooking dari ' 
                        . $overlap->waktu_mulai->format('H:i') 
                        . ' hingga ' 
                        . $overlap->waktu_berakhir->format('H:i'));
                }

                Booking::create([
                    'room_name' => $request->room_name,
                    'username' => session('user'),
                    'mata_kuliah' => $request->mata_kuliah,
                    'dosen' => $request->dosen,
                    'waktu_mulai' => $waktuMulai,
                    'waktu_berakhir' => $waktuBerakhir,
                    'status' => 'active',
                    'keterangan' => $request->keterangan
                ]);
            });

            // Clear relevant caches
            Cache::forget('admin_dashboard_stats');

            return redirect()
                ->route('dashboard.kelas')
                ->with('success', 'Booking berhasil! Ruangan: ' . $request->room_name);

        } catch (\Exception $e) {
            Log::error('Booking failed', [
                'error' => $e->getMessage(),
                'room' => $request->room_name,
                'user' => session('user')
            ]);

            return back()
                ->with('error', $e->getMessage())
                ->withInput();
        }
    }

    // Method lainnya tetap sama...
    public function getActiveBookings()
    {
        try {
            return Booking::where('username', session('user'))
                ->where('status', 'active')
                ->where('waktu_berakhir', '>', now()->timezone('Asia/Makassar'))
                ->orderBy('waktu_berakhir')
                ->get();
        } catch (\Exception $e) {
            return collect();
        }
    }

    public function cancel($id)
    {
        try {
            $booking = Booking::findOrFail($id);
            
            $currentUser = session('user');
            $currentRole = session('role');

            if ($currentRole !== 'admin' && $booking->username !== $currentUser) {
                return back()->with('error', 'Anda tidak memiliki izin membatalkan booking ini.');
            }

            $booking->update(['status' => 'cancelled']);
            
            return back()->with('success', 'Booking berhasil dibatalkan.');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membatalkan booking: ' . $e->getMessage());
        }
    }

    public function getAllBookings()
    {
        return Booking::where('status', 'active')
            ->where('waktu_berakhir', '>', now()->timezone('Asia/Makassar'))
            ->orderBy('waktu_berakhir')
            ->get();
    }
    
    public function expireOldBookings()
    {
        Booking::where('status', 'active')
            ->where('waktu_berakhir', '<', now())
            ->update(['status' => 'completed']);
            
        return response()->json(['success' => true]);
    }
}