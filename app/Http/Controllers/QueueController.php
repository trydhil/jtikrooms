<?php

namespace App\Http\Controllers;

use App\Models\Queue;
use App\Models\Room;
use Illuminate\Http\Request;
use Carbon\Carbon;

class QueueController extends Controller
{
    public function takeQueue(Request $request, $roomId)
    {
        // ✅ PERBAIKI: Gunakan kelas_id dari session
        if (!session('loggedin') || session('role') !== 'kelas') {
            return redirect()->route('login')->with('error', 'Login sebagai kelas dulu!');
        }

        // Cek apakah room exists
        $room = Room::find($roomId);
        if (!$room) {
            return redirect()->back()->with('error', 'Ruangan tidak ditemukan.');
        }

        // Cek apakah sudah ada antrian aktif hari ini
        $existingQueue = Queue::where('room_id', $roomId)
                            ->where('kelas_id', session('user_id'))
                            ->whereDate('created_at', Carbon::today())
                            ->whereIn('status', ['waiting', 'processing'])
                            ->first();

        if ($existingQueue) {
            return redirect()->back()->with('error', 'Anda sudah mengambil antrian untuk ruangan ini hari ini.');
        }

        // Buat antrian baru
        $queue = Queue::create([
            'room_id' => $roomId,
            'kelas_id' => session('user_id'),
            'queue_number' => Queue::getNextQueueNumber($roomId),
            'status' => 'waiting',
            'priority' => 0
        ]);

        return redirect()->route('dashboard.kelas')->with('success', 'Antrian berhasil diambil! Nomor: #' . $queue->queue_number);
    }

    // Method untuk admin mengelola antrian
    public function manageQueues($roomId = null)
    {
        if (!session('loggedin') || session('role') !== 'admin') {
            return redirect()->route('login')->with('error', 'Akses admin required.');
        }

        $query = Queue::with(['kelas', 'room'])
                    ->whereIn('status', ['waiting', 'processing'])
                    ->orderBy('priority', 'desc')
                    ->orderBy('created_at', 'asc');

        if ($roomId) {
            $query->where('room_id', $roomId);
        }

        $queues = $query->get();
        $rooms = Room::all();

        return view('admin.queues.index', compact('queues', 'rooms', 'roomId'));
    }

    // Method untuk memproses antrian
    public function processQueue($queueId)
    {
        if (!session('loggedin') || session('role') !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $queue = Queue::findOrFail($queueId);
        $queue->update(['status' => 'processing']);

        return response()->json(['success' => true, 'message' => 'Antrian diproses']);
    }

    // Method untuk menyelesaikan antrian
    public function completeQueue($queueId)
    {
        if (!session('loggedin') || session('role') !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $queue = Queue::findOrFail($queueId);
        $queue->update(['status' => 'completed']);

        return response()->json(['success' => true, 'message' => 'Antrian selesai']);
    }
}