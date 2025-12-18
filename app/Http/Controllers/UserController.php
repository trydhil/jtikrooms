<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kelas;
use App\Models\Booking;

class UserController extends Controller
{
    public function __construct()
    {
        // Middleware: Cuma admin yang boleh masuk sini
        $this->middleware(function ($request, $next) {
            if (!session('loggedin') || session('role') !== 'admin') {
                return redirect()->route('login')->with('error', 'Akses admin required.');
            }
            return $next($request);
        });
    }

    // 1. INDEX (List User)
    public function index()
    {
        $users = Kelas::orderBy('username')->get();
        return view('admin.users.index', compact('users'));
    }

    // 2. CREATE (Form Tambah)
    public function create()
    {
        return view('admin.users.create');
    }

    // 3. STORE (Simpan User Baru)
    public function store(Request $request)
    {
        $request->validate([
            'prodi' => 'required|string|max:50',
            'kelas' => 'required|string|max:10',
            'angkatan' => 'required|max:10',
            'password' => 'required|min:6'
        ]);

        try {
            // Bikin username otomatis (contoh: teknikkomputer2023)
            $username = strtolower($request->prodi) . strtolower($request->kelas) . substr($request->angkatan, -2);
            
            Kelas::create([
                'username' => $username,
                'prodi' => $request->prodi,
                'kelas' => $request->kelas,
                'nama_kelas' => $request->prodi . ' ' . $request->kelas,
                'angkatan' => $request->angkatan,
                'password' => $request->password // Password Biasa (Tanpa Hash)
            ]);

            return redirect()->route('users.index')
                ->with('success', 'User berhasil ditambah! Username: ' . $username);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal: ' . $e->getMessage())->withInput();
        }
    }

    // 4. SHOW (Detail User) - INI YANG TADI HILANG
    public function show(Kelas $user)
    {
        return view('admin.users.show', compact('user'));
    }

    // 5. EDIT (Form Edit) - INI YANG TADI HILANG DAN BIKIN ERROR
    public function edit(Kelas $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    // 6. UPDATE (Simpan Perubahan)
    public function update(Request $request, Kelas $user)
    {
        $request->validate([
            'prodi' => 'required',
            'kelas' => 'required',
            'angkatan' => 'required',
            'password' => 'nullable|min:6'
        ]);

        try {
            $newUsername = strtolower($request->prodi) . strtolower($request->kelas) . substr($request->angkatan, -2);
            
            $updateData = [
                'prodi' => $request->prodi,
                'kelas' => $request->kelas,
                'nama_kelas' => $request->prodi . ' ' . $request->kelas,
                'angkatan' => $request->angkatan,
                'username' => $newUsername
            ];

            // Cek kalau password diisi, update passwordnya
            if ($request->filled('password')) {
                $updateData['password'] = $request->password; // Update tanpa hash
            }

            $user->update($updateData);

            return redirect()->route('users.index')->with('success', 'Data berhasil diupdate!');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal update: ' . $e->getMessage());
        }
    }

    // 7. RESET PASSWORD
    public function resetPassword(Kelas $user)
    {
        try {
            $defaultPass = 'password123';
            
            $user->update([
                'password' => $defaultPass
            ]);

            return redirect()->route('users.index')
                ->with('success', "Password {$user->username} direset jadi: $defaultPass");

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal reset password.');
        }
    }

    // 8. DESTROY (Hapus User)
    public function destroy(Kelas $user)
    {
        // Cek dulu user ini punya booking aktif gak?
        $activeBookings = Booking::where('username', $user->username)
            ->where('status', 'active')
            ->where('waktu_berakhir', '>', now())
            ->exists();

        if ($activeBookings) {
            return back()->with('error', 'User ini sedang meminjam ruangan, tidak bisa dihapus!');
        }

        $user->delete();
        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }
}