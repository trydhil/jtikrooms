<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        // 1. CEK LOGIN TERLEBIH DAHULU
        if (!session('loggedin')) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu untuk mengirim laporan/komentar.');
        }

        // 2. CEK ROLE (Hanya Kelas yang bisa komen, Admin mungkin tidak punya ID di tabel kelas)
        // Jika Anda ingin Admin bisa komen, struktur DB harus diubah (polymorphic). 
        // Untuk saat ini kita asumsikan hanya Kelas yang lapor.
        if (session('role') !== 'kelas') {
             return back()->with('error', 'Hanya perwakilan kelas yang dapat mengirim laporan.');
        }

        $request->validate([
            'body' => 'required|string|max:500',
            'room_id' => 'required|exists:rooms,id',
        ]);

        try {
            // 3. Simpan Komentar
            Comment::create([
                'room_id' => $request->room_id,
                'kelas_id' => session('user_id'), // Pastikan ini tidak null
                'body' => $request->body,
                'is_anonymous' => $request->has('anonymous') ? 1 : 0, 
                'type' => 'general', // Default general, bisa diubah jika ada input type
                'status' => 'open'
            ]);

            return back()->with('success', 'Pesan terkirim!');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengirim pesan: ' . $e->getMessage());
        }
    }
}