<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class RoomController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!session('loggedin') || session('role') !== 'admin') {
                return redirect()->route('login')->with('error', 'Akses admin required.');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $rooms = Room::orderBy('name')->get();
        return view('admin.rooms.index', compact('rooms'));
    }

    public function create()
    {
        return view('admin.rooms.create');
    }

    /// Di method store - PERBAIKI VALIDASI
public function store(Request $request)
{
    $request->validate([
        'name' => 'required|unique:rooms|max:50',
        'display_name' => 'required|max:100',
        'status' => 'required|in:available,maintenance,occupied',
        'description' => 'nullable|string',
        'capacity' => 'required|integer|min:1',
        'luas' => 'required|numeric|min:5|max:500', // WAJIB, min 5m² max 500m²
        'facilities' => 'nullable|array',
        'location' => 'nullable|string|max:255',
        'lantai' => 'nullable|string|max:50', // TAMBAH INI
        'type' => 'required|in:kelas,lab,other', // TAMBAH INI
        'custom_facilities' => 'nullable|string'
    ]);

    try {
        // Gabung Fasilitas
        $facilities = $request->facilities ?? [];
        if ($request->custom_facilities) {
            $custom = array_map('trim', explode(',', $request->custom_facilities));
            $custom = array_filter($custom); 
            $facilities = array_merge($facilities, $custom);
        }

        // Generate QR Baru
        $targetUrl = url('/booking/create/' . urlencode(strtoupper($request->name)));
        $qrPath = $this->createQRFile($request->name, $targetUrl);

        // Simpan ke DB - TAMBAH 'lantai' dan 'type'
        Room::create([
            'name' => strtoupper($request->name),
            'display_name' => $request->display_name,
            'description' => $request->description,
            'capacity' => $request->capacity,
            'luas' => $request->luas, // WAJIB
            'location' => $request->location,
            'lantai' => $request->lantai, // TAMBAH
            'type' => $request->type, // TAMBAH
            'status' => $request->status,
            'facilities' => $facilities,
            'qr_code' => $qrPath,
        ]);

        return redirect()->route('rooms.index')
            ->with('success', 'Ruangan berhasil dibuat & QR Code siap!');

    } catch (\Exception $e) {
        Log::error('Gagal store room: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Error: ' . $e->getMessage())->withInput();
    }
}

// Di method update - TAMBAHKAN 'luas', 'lantai', 'type'
public function update(Request $request, Room $room)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'display_name' => 'required|string|max:255',
        'location' => 'nullable|string|max:255',
        'lantai' => 'nullable|string|max:50', // TAMBAH
        'capacity' => 'required|integer',
        'luas' => 'required|numeric|min:5|max:500', // WAJIB
        'type' => 'required|in:kelas,lab,other', // TAMBAH
        'status' => 'required|in:available,maintenance,occupied',
        'facilities' => 'nullable|array',
        'description' => 'nullable|string',
        'custom_facilities' => 'nullable|string'
    ]);

    $facilities = $request->facilities ?? [];
    if ($request->custom_facilities) {
        $custom = array_map('trim', explode(',', $request->custom_facilities));
        $custom = array_filter($custom);
        $facilities = array_merge($facilities, $custom);
    }
    $validated['facilities'] = $facilities;

    $room->update($validated);

    return redirect()->route('rooms.index')->with('success', 'Ruangan berhasil diupdate!');
}
    // === GENERATE ULANG (DENGAN HAPUS FILE LAMA) ===
    public function generateQR(Room $room)
    {
        try {
            // 1. HAPUS FILE LAMA DULU (Fitur yang diminta)
            if ($room->qr_code && File::exists(public_path($room->qr_code))) {
                File::delete(public_path($room->qr_code));
            }

            // 2. Buat File Baru
            $targetUrl = url('/booking/create/' . urlencode(strtoupper($room->name)));
            $qrPath = $this->createQRFile($room->name, $targetUrl);
            
            // 3. Update Database dengan path baru
            $room->update(['qr_code' => $qrPath]);

            return back()->with('success', 'QR Code berhasil diperbarui (File lama dihapus)!');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal generate QR: ' . $e->getMessage());
        }
    }

    // === FUNGSI HELPER PEMBUAT FILE (PNG -> SVG FALLBACK) ===
    private function createQRFile($roomName, $targetUrl)
    {
        $roomNameClean = Str::slug($roomName);
        $pathFolder = public_path('img/qrcodes');

        // Pastikan folder ada
        if (!File::exists($pathFolder)) {
            File::makeDirectory($pathFolder, 0777, true, true);
        }

        // Coba PNG dulu
        try {
            $fileName = 'qr-' . $roomNameClean . '-' . time() . '.png';
            $fullPath = $pathFolder . '/' . $fileName;
            
            QrCode::format('png')
                  ->size(300)
                  ->margin(2)
                  ->generate($targetUrl, $fullPath);
            
            if (File::exists($fullPath)) {
                return 'img/qrcodes/' . $fileName;
            }
            throw new \Exception("Gagal simpan PNG");

        } catch (\Exception $e) {
            // Jika gagal (misal GD error), pakai SVG
            Log::warning("Gagal generate PNG, beralih ke SVG: " . $e->getMessage());
            
            $fileName = 'qr-' . $roomNameClean . '-' . time() . '.svg';
            $fullPath = $pathFolder . '/' . $fileName;
            
            QrCode::format('svg')
                  ->size(300)
                  ->margin(2)
                  ->generate($targetUrl, $fullPath);
                  
            return 'img/qrcodes/' . $fileName;
        }
    }

    // === HAPUS RUANGAN & FILE ===
    public function destroy(Room $room)
    {
        try {
            // Hapus file QR saat ruangan dihapus
            if ($room->qr_code && File::exists(public_path($room->qr_code))) {
                File::delete(public_path($room->qr_code));
            }
            
            $room->delete();
            return redirect()->route('rooms.index')->with('success', 'Ruangan dan QR Code berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal hapus: ' . $e->getMessage());
        }
    }

    public function downloadQR(Room $room)
    {
        if (!$room->qr_code || !File::exists(public_path($room->qr_code))) {
            return back()->with('error', 'File QR Code tidak ditemukan.');
        }
        return response()->download(public_path($room->qr_code));
    }

    public function downloadPdf(Room $room)
    {
        if (!$room->qr_code || !File::exists(public_path($room->qr_code))) {
            return back()->with('error', 'Generate QR Code terlebih dahulu!');
        }

        $pdf = Pdf::loadView('admin.rooms.print', compact('room'));
        $pdf->setPaper('a5', 'portrait');

        return $pdf->download('Label-QR-' . $room->name . '.pdf');
    }
    
    public function edit(Room $room) { return view('admin.rooms.edit', compact('room')); }
    public function show(Room $room) { return view('admin.rooms.show', compact('room')); }
}