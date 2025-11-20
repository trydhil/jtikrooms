<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RoomController extends Controller
{
    public function __construct()
    {
        // Check session untuk semua method
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
        $facilities = [
            'proyektor' => 'Proyektor',
            'ac' => 'AC', 
            'whiteboard' => 'Whiteboard',
            'sound_system' => 'Sound System',
            'komputer' => 'Komputer',
            'internet' => 'Internet',
            'lcd_tv' => 'LCD TV',
            'kursi_ergonomis' => 'Kursi Ergonomis'
        ];

        return view('admin.rooms.create', compact('facilities'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:rooms|max:50',
            'display_name' => 'required|max:100',
            'description' => 'nullable|string',
            'capacity' => 'required|integer|min:1',
            'facilities' => 'nullable|array',
            'location' => 'nullable|string|max:100'
        ]);

        try {
            $room = Room::create([
                'name' => strtoupper($request->name),
                'display_name' => $request->display_name,
                'description' => $request->description,
                'capacity' => $request->capacity,
                'facilities' => $request->facilities,
                'location' => $request->location,
                'status' => 'available'
            ]);

            return redirect()->route('rooms.index')
                ->with('success', 'Ruangan berhasil ditambahkan!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menambah ruangan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(Room $room)
    {
        return view('admin.rooms.show', compact('room'));
    }

    public function edit(Room $room)
    {
        $facilities = [
            'proyektor' => 'Proyektor',
            'ac' => 'AC',
            'whiteboard' => 'Whiteboard', 
            'sound_system' => 'Sound System',
            'komputer' => 'Komputer',
            'internet' => 'Internet',
            'lcd_tv' => 'LCD TV',
            'kursi_ergonomis' => 'Kursi Ergonomis'
        ];

        return view('admin.rooms.edit', compact('room', 'facilities'));
    }

    public function update(Request $request, Room $room)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'display_name' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'capacity' => 'nullable|integer',
            'status' => 'required|in:available,maintenance,occupied',
            'facilities' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        // Convert facilities string to array
        if ($request->has('facilities') && $request->facilities) {
            $validated['facilities'] = array_map('trim', explode(',', $request->facilities));
        } else {
            $validated['facilities'] = null;
        }

        $room->update($validated);

        return redirect()->route('rooms.index')
            ->with('success', 'Ruangan berhasil diupdate!');
    }

    public function destroy(Room $room)
    {
        try {
            $room->delete();

            return redirect()->route('rooms.index')
                ->with('success', 'Ruangan berhasil dihapus!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus ruangan: ' . $e->getMessage());
        }
    }

    // QR Code methods - SIMPLE VERSION
    public function generateQR(Room $room)
    {
        return redirect()->route('rooms.index')
            ->with('info', 'Generate QR Code manual: Buat QR dengan URL: ' . url("/booking/create/" . urlencode($room->name)));
    }

    public function downloadQR(Room $room)
    {
        if (!$room->qr_code) {
            return redirect()->route('rooms.index')
                ->with('error', 'QR Code belum tersedia. Silakan upload manual.');
        }
        
        // Jika QR code adalah path local
        if (strpos($room->qr_code, '/img/qrcodes/') === 0) {
            $filePath = public_path($room->qr_code);
            if (file_exists($filePath)) {
                return response()->download($filePath, "qr-{$room->name}.png");
            }
        }
        
        return redirect()->route('rooms.index')
            ->with('error', 'QR Code tidak ditemukan.');
    }
}