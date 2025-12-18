<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentAdminController extends Controller
{
    public function index()
    {
        // Ambil semua komentar, urutkan dari yang terbaru
        // Kita juga memuat relasi 'room' dan 'kelas' agar efisien (Eager Loading)
        $comments = Comment::with(['room', 'kelas'])
            ->orderBy('created_at', 'desc')
            ->paginate(15); // Tampilkan 15 per halaman

        return view('admin.comments.index', compact('comments'));
    }

    // Tandai laporan selesai
    public function resolve($id)
    {
        $comment = Comment::findOrFail($id);
        
        // Hanya ubah jika tipenya 'report'
        if ($comment->type === 'report') {
            $comment->update(['status' => 'resolved']);
            return back()->with('success', 'Laporan berhasil ditandai sebagai selesai.');
        }

        return back()->with('error', 'Hanya tipe laporan yang bisa ditandai selesai.');
    }

    // Hapus komentar
    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->delete();

        return back()->with('success', 'Komentar berhasil dihapus.');
    }
}