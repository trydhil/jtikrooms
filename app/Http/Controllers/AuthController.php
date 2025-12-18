<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Kelas;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        // 1. Validasi Input
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        $username = $credentials['username'];
        $password = $credentials['password'];

        // Log Debugging
        Log::info('Login attempt:', ['username' => $username]);

        // 2. Cek di tabel ADMIN
        // (Password Plain Text sesuai request kamu)
        $admin = Admin::where('username', $username)->first();

        if ($admin && $admin->password === $password) {
            Log::info('Admin login success');
            // Panggil Helper (Biar kodingan bersih)
            $this->setSession($username, 'admin', $admin->id);
            return redirect()->route('dashboard.admin')->with('success', 'Login admin berhasil!');
        }

        // 3. Cek di tabel KELAS
        $kelas = Kelas::where('username', $username)->first();

        if ($kelas && $kelas->password === $password) {
            Log::info('Kelas login success');
            // Panggil Helper (Biar kodingan bersih)
            $this->setSession($username, 'kelas', $kelas->id);
            return redirect()->route('dashboard.kelas')->with('success', 'Login kelas berhasil!');
        }

        // 4. Login Gagal
        Log::warning('Login failed for: ' . $username);
        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ]);
    }

    /**
     * Helper Function: Mengatur Session & Flash Data
     * (Ini yang bikin kodingan jadi pendek & rapi)
     */
    private function setSession($username, $role, $id)
    {
        // 1. Simpan Session Utama
        session([
            'loggedin' => true,
            'user' => $username,
            'role' => $role,
            'user_id' => $id
        ]);

        session()->regenerate();

        // 2. Simpan Flash Data (Untuk Animasi Frontend & Pesan Welcome)
        session()->flash('login_success', true);
        session()->flash('user_name', $username);
        session()->flash('user_role', $role);
        // Timezone Makassar (WITA)
        session()->flash('login_time', now()->timezone('Asia/Makassar')->format('H:i'));
        session()->flash('login_date', now()->timezone('Asia/Makassar')->translatedFormat('l, d F Y'));
        
        Log::info("Session created for $role: $username");
    }

    public function logout(Request $request)
    {
        Log::info('Logout user:', ['user' => session('user')]);
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/')->with('success', 'Logout berhasil!');
    }

    public function checkSession()
    {
        return response()->json([
            'loggedin' => session('loggedin', false),
            'user' => session('user', ''),
            'role' => session('role', ''),
            'user_id' => session('user_id', ''),
        ]);
    }
}