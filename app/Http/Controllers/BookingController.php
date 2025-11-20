<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class BookingController extends Controller
{
    // CREATE - Form booking dari QR code
    public function createFromQR($roomName)
    {
        // ✅ TEMPORARY: Skip semua validasi QR
        // $referer = request()->header('referer');
        // $isFromQR = $referer && (str_contains($referer, '/qr/scanner') || str_contains($referer, url('/qr/scanner')));
        
        // if (!$isFromQR) {
        //     abort(403, 'Akses booking hanya melalui scan QR code.');
        // }
        
        // Decode URL parameter
        $roomName = urldecode($roomName);
        
        // Validasi ruangan exists
        $validRooms = [
            'AE 101', 'AE 102', 'AE 103', 'AE 104', 'AE 105', 'AE 106', 'AE 107', 'AE 209',
            'Lab Animasi', 'IT Workshop', 'Lab Jaringan', 'Lab Programing', 'Lab Sistem Cerdas', 'Lab Embeded',
            'Sekretariat HIMATIK', 'Ruangan Admin', 'Perpustakaan', 'Ruangan Sekertaris Jurusan',
            'Ruangan Kepala Laboratorium', 'Ruangan Dosen', 'Ruangan Ketua Prodi TEKOM', 
            'Ruangan Ujian', 'Ruangan Ketua Prodi PTIK'
        ];
        
        if (!in_array($roomName, $validRooms)) {
            abort(404, 'Ruangan tidak ditemukan');
        }
        
        return view('booking.create', [
            'roomName' => $roomName
        ]);
    }

    public function store(Request $request)
{
    Log::info('Booking store called', [
        'room' => $request->room_name,
        'user' => session('user'),
        'all_input' => $request->all()
    ]);

    // Validasi input form
    $request->validate([
        'room_name' => 'required',
        'mata_kuliah' => 'required',
        'dosen' => 'required',
        'waktu_berakhir' => 'required|date|after:now',
        'keterangan' => 'nullable'
    ]);

    try {
        // ✅ FIX: Gunakan timezone Asia/Makassar dan parse dengan benar
        $waktuBerakhir = Carbon::parse($request->waktu_berakhir)->setTimezone('Asia/Makassar');
        $waktuMulai = now()->setTimezone('Asia/Makassar');
        
        Log::info('Time validation debug', [
            'waktu_mulai' => $waktuMulai->format('Y-m-d H:i:s'),
            'waktu_berakhir' => $waktuBerakhir->format('Y-m-d H:i:s'),
            'diff_minutes' => $waktuMulai->diffInMinutes($waktuBerakhir),
            'diff_hours' => $waktuMulai->diffInHours($waktuBerakhir),
            'timezone' => 'Asia/Makassar'
        ]);

        // Validasi minimal 30 menit
        if ($waktuMulai->diffInMinutes($waktuBerakhir) < 30) {
            return back()->with('error', 'Booking minimal 30 menit.')->withInput();
        }

        // Validasi maksimal 5 jam
        if ($waktuMulai->diffInHours($waktuBerakhir) > 5) {
            return back()->with('error', 'Booking maksimal 5 jam.')->withInput();
        }

        // Validasi overlap (gunakan timezone yang sama)
        $overlap = Booking::where('room_name', $request->room_name)
            ->where('status', 'active')
            ->where('waktu_berakhir', '>', now()->setTimezone('Asia/Makassar'))
            ->where(function($query) use ($waktuMulai, $waktuBerakhir) {
                $query->whereBetween('waktu_mulai', [$waktuMulai, $waktuBerakhir])
                      ->orWhereBetween('waktu_berakhir', [$waktuMulai, $waktuBerakhir])
                      ->orWhere(function($q) use ($waktuMulai, $waktuBerakhir) {
                          $q->where('waktu_mulai', '<', $waktuMulai)
                            ->where('waktu_berakhir', '>', $waktuBerakhir);
                      });
            })
            ->first();

        if ($overlap) {
            return back()
                ->with('error', 'Ruangan sudah dibooking dari ' 
                    . $overlap->waktu_mulai->setTimezone('Asia/Makassar')->format('H:i') 
                    . ' hingga ' 
                    . $overlap->waktu_berakhir->setTimezone('Asia/Makassar')->format('H:i'))
                ->withInput();
        }

        // Buat booking dengan timezone Asia/Makassar
        $booking = Booking::create([
            'room_name' => $request->room_name,
            'username' => session('user'),
            'mata_kuliah' => $request->mata_kuliah,
            'dosen' => $request->dosen,
            'waktu_mulai' => $waktuMulai,
            'waktu_berakhir' => $waktuBerakhir,
            'status' => 'active',
            'keterangan' => $request->keterangan
        ]);

        Log::info('Booking SUCCESS', [
            'booking_id' => $booking->id,
            'room' => $request->room_name,
            'user' => session('user'),
            'waktu_mulai' => $waktuMulai->format('Y-m-d H:i:s'),
            'waktu_berakhir' => $waktuBerakhir->format('Y-m-d H:i:s'),
            'timezone' => 'Asia/Makassar'
        ]);

        return redirect()
            ->route('dashboard.kelas')
            ->with('success', 'Booking berhasil! Ruangan: ' . $request->room_name);

    } catch (\Exception $e) {
        Log::error('Booking FAILED', [
            'error' => $e->getMessage(),
            'room' => $request->room_name,
            'user' => session('user'),
            'trace' => $e->getTraceAsString()
        ]);

        return back()
            ->with('error', 'Gagal membuat booking. Silakan coba lagi.')
            ->withInput();
    }
}

    // READ - Booking aktif untuk user (di dashboard kelas)
    public function getActiveBookings()
    {
        try {
            return Booking::where('username', session('user'))
                ->where('status', 'active')
                ->where('waktu_berakhir', '>', now())
                ->orderBy('waktu_berakhir')
                ->get();
                
        } catch (\Exception $e) {
            Log::error('Failed to get active bookings', [
                'error' => $e->getMessage(),
                'user' => session('user')
            ]);
            
            return collect();
        }
    }

    // UPDATE - Batalkan booking
    public function cancel($id)
    {
        try {
            $booking = Booking::findOrFail($id);
            
            if ($booking->username !== session('user')) {
                Log::warning('Unauthorized cancel attempt', [
                    'booking_id' => $id,
                    'booking_owner' => $booking->username,
                    'attempt_by' => session('user')
                ]);
                
                return back()->with('error', 'Anda tidak berhak membatalkan booking ini!');
            }

            if ($booking->waktu_berakhir < now()) {
                return back()->with('error', 'Tidak dapat membatalkan booking yang sudah berakhir!');
            }

            $booking->update(['status' => 'cancelled']);

            Log::info('Booking cancelled', [
                'booking_id' => $id,
                'room' => $booking->room_name,
                'user' => session('user')
            ]);

            return back()->with('success', 'Booking berhasil dibatalkan!');

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Booking not found for cancellation', [
                'booking_id' => $id,
                'user' => session('user')
            ]);
            
            return back()->with('error', 'Booking tidak ditemukan!');
            
        } catch (\Exception $e) {
            Log::error('Booking cancellation failed', [
                'error' => $e->getMessage(),
                'booking_id' => $id,
                'user' => session('user')
            ]);
            
            return back()->with('error', 'Gagal membatalkan booking. Silakan coba lagi.');
        }
    }

    // READ - Semua booking aktif (untuk admin)
    public function getAllBookings()
    {
        try {
            return Booking::where('status', 'active')
                ->where('waktu_berakhir', '>', now())
                ->orderBy('waktu_berakhir')
                ->get();
                
        } catch (\Exception $e) {
            Log::error('Failed to get all bookings', [
                'error' => $e->getMessage(),
                'admin' => session('user')
            ]);
            
            return collect();
        }
    }

    // BONUS: Auto-expire booking yang sudah lewat
    public function expireOldBookings()
    {
        try {
            $expired = Booking::where('status', 'active')
                ->where('waktu_berakhir', '<', now())
                ->update(['status' => 'completed']);

            Log::info('Auto-expired bookings', ['count' => $expired]);

            return response()->json([
                'success' => true,
                'expired_count' => $expired
            ]);

        } catch (\Exception $e) {
            Log::error('Auto-expire failed', ['error' => $e->getMessage()]);
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal expire booking'
            ], 500);
        }
    }
}