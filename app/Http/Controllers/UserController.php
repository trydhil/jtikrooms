<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kelas;

class UserController extends Controller
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
        $users = Kelas::orderBy('username')->get();
        \Log::info('Index method - Users data:', $users->toArray());
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'prodi' => 'required|string|max:50',
            'kelas' => 'required|string|max:10',
            'angkatan' => 'required|max:10',
            'password' => 'required|min:6'
        ]);

        try {
            $username = strtolower($request->prodi) . strtolower($request->kelas) . substr($request->angkatan, -2);
            
            Kelas::create([
                'username' => $username,
                'prodi' => $request->prodi,
                'kelas' => $request->kelas,
                'nama_kelas' => $request->prodi . ' ' . $request->kelas,
                'angkatan' => $request->angkatan,
                'password' => $request->password // TANPA MD5
            ]);

            return redirect()->route('users.index')
                ->with('success', 'Perwakilan kelas berhasil ditambahkan!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menambah perwakilan kelas: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(Kelas $user) // UBAH PARAMETER JADI $user
    {
        return view('admin.users.show', compact('user'));
    }

    public function edit(Kelas $user) // UBAH PARAMETER JADI $user
    {
        return view('admin.users.edit', ['user' => $user]);
    }

    public function update(Request $request, Kelas $user) // UBAH PARAMETER JADI $user
    {
        \Log::info('Update attempt:', $request->all());
        \Log::info('Current user data:', $user->toArray());

        $request->validate([
            'prodi' => 'required|string|max:50',
            'kelas' => 'required|string|max:10',
            'angkatan' => 'required|max:10',
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

            if ($request->password) {
                $updateData['password'] = $request->password; // TANPA MD5
            }

            \Log::info('Data to update:', $updateData);
            
            $user->update($updateData);

            \Log::info('Update successful - New data:', $user->fresh()->toArray());

            return redirect()->route('users.index')
                ->with('success', 'Perwakilan kelas berhasil diupdate!');

        } catch (\Exception $e) {
            \Log::error('Update failed:', ['error' => $e->getMessage()]);
            return redirect()->back()
                ->with('error', 'Gagal mengupdate perwakilan kelas: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(Kelas $user) // UBAH PARAMETER JADI $user
    {
        try {
            $activeBookings = \App\Models\Booking::where('username', $user->username)
                ->where('status', 'active')
                ->where('waktu_berakhir', '>', now())
                ->count();

            if ($activeBookings > 0) {
                return redirect()->back()
                    ->with('error', 'Tidak dapat menghapus perwakilan kelas yang memiliki booking aktif!');
            }

            $user->delete();

            return redirect()->route('users.index')
                ->with('success', 'Perwakilan kelas berhasil dihapus!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus perwakilan kelas: ' . $e->getMessage());
        }
    }

    public function resetPassword(Kelas $user) // UBAH PARAMETER JADI $user
    {
        try {
            $user->update([
                'password' => 'password123' // TANPA MD5
            ]);

            return redirect()->route('users.index')
                ->with('success', 'Password berhasil direset ke: password123');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal reset password: ' . $e->getMessage());
        }
    }
}