<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Kelas;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        $username = $credentials['username'];
        $password = $credentials['password'];

        // DEBUG: Log credentials
        \Log::info('Login attempt:', ['username' => $username, 'password_input' => $password]);

        // Cek di tabel admin
        $admin = Admin::where('username', $username)
            ->where('password', $password)
            ->first();

        // DEBUG: Log admin check
        \Log::info('Admin check:', ['found' => !is_null($admin), 'admin_data' => $admin]);

        if ($admin) {
            // SIMPAN SESSION
            session([
                'loggedin' => true,
                'user' => $username,
                'role' => 'admin',
                'user_id' => $admin->id
            ]);

            $request->session()->regenerate();
            \Log::info('Session after admin login:', session()->all());

            // TAMBAHKAN SESSION FLASH UNTUK ANIMASI - DENGAN WAKTU INDONESIA
            session()->flash('login_success', true);
            session()->flash('user_name', $username);
            session()->flash('user_role', 'admin');
            session()->flash('login_time', now()->timezone('Asia/Makassar')->format('H:i'));
            session()->flash('login_date', now()->timezone('Asia/Makassar')->translatedFormat('l, d F Y'));

            return redirect()->route('dashboard.admin')->with('success', 'Login admin berhasil!');
        }

        // Cek di tabel kelas
        $kelas = Kelas::where('username', $username)
            ->where('password', $password)
            ->first();

        // DEBUG: Log kelas check
        \Log::info('Kelas check:', ['found' => !is_null($kelas), 'kelas_data' => $kelas]);

        if ($kelas) {
            // SIMPAN SESSION
            session([
                'loggedin' => true,
                'user' => $username,
                'role' => 'kelas', 
                'user_id' => $kelas->id
            ]);

            $request->session()->regenerate();
            \Log::info('Session after kelas login:', session()->all());

            // TAMBAHKAN SESSION FLASH UNTUK ANIMASI - DENGAN WAKTU INDONESIA
            session()->flash('login_success', true);
            session()->flash('user_name', $username);
            session()->flash('user_role', 'kelas');
            session()->flash('login_time', now()->timezone('Asia/Makassar')->format('H:i'));
            session()->flash('login_date', now()->timezone('Asia/Makassar')->translatedFormat('l, d F Y'));

            return redirect()->route('dashboard.kelas')->with('success', 'Login kelas berhasil!');
        }

        // DEBUG: Log failed login
        \Log::warning('Login failed for username: ' . $username);

        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ]);
    }

    public function logout(Request $request)
    {
        \Log::info('Logout:', session()->all());
        
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
            'session_id' => session()->getId()
        ]);
    }
}