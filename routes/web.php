<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\APIController;
use App\Http\Controllers\InformasiController;

// ===== HALAMAN PUBLIK =====
Route::get('/', [PageController::class, 'index'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/informasi', [InformasiController::class, 'index'])->name('informasi');
Route::get('/room/{name}', [PageController::class, 'roomInfo'])->name('room.info');

// ===== AUTHENTICATION =====
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/check-session', [AuthController::class, 'checkSession'])->name('session.check');

// ===== DASHBOARD =====
Route::get('/dashboard/admin', [DashboardController::class, 'admin'])->name('dashboard.admin');
Route::get('/dashboard/kelas', [DashboardController::class, 'kelas'])->name('dashboard.kelas');

// ===== BOOKING SYSTEM =====
// ✅ PASTIKAN INI DI ATAS DAN PAKAI METHOD YANG BENAR
Route::get('/booking/create/{roomName}', [BookingController::class, 'createFromQR'])->name('booking.create');
Route::post('/booking/store', [BookingController::class, 'store'])->name('booking.store');
Route::post('/booking/cancel/{id}', [BookingController::class, 'cancel'])->name('booking.cancel');
Route::get('/booking/active', [BookingController::class, 'getActiveBookings'])->name('booking.active');
Route::get('/booking/all', [BookingController::class, 'getAllBookings'])->name('booking.all');
Route::post('/booking/expire', [BookingController::class, 'expireOldBookings'])->name('booking.expire');

// ===== API ROUTES untuk JavaScript =====
Route::prefix('api')->group(function () {
    Route::get('/rooms/status', [APIController::class, 'getAllRoomStatuses']);
    Route::get('/rooms/list', [APIController::class, 'getAvailableRooms']);
    Route::get('/room/{roomName}/status', [APIController::class, 'getRoomStatus']);
    Route::post('/scan-qr', [APIController::class, 'scanQR']);
    Route::post('/quick-booking', [APIController::class, 'quickBooking']);
});

// ===== ADMIN ROUTES - ROOM MANAGEMENT =====
Route::prefix('admin')->group(function () {
    // Room Management
    Route::get('/rooms', [RoomController::class, 'index'])->name('rooms.index');
    Route::get('/rooms/create', [RoomController::class, 'create'])->name('rooms.create');
    Route::post('/rooms/store', [RoomController::class, 'store'])->name('rooms.store');
    Route::get('/rooms/{room}', [RoomController::class, 'show'])->name('rooms.show');
    Route::get('/rooms/{room}/edit', [RoomController::class, 'edit'])->name('rooms.edit');
    Route::put('/rooms/{room}', [RoomController::class, 'update'])->name('rooms.update');
    Route::delete('/rooms/{room}', [RoomController::class, 'destroy'])->name('rooms.destroy');
});

// ===== ADMIN ROUTES - USER MANAGEMENT =====
Route::prefix('admin')->group(function () {
    // User Management
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users/store', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::post('/users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
});

// ===== INFORMASI ROUTES =====
Route::get('/informasi', [InformasiController::class, 'index'])->name('informasi');
Route::put('/informasi/update', [InformasiController::class, 'update'])->name('informasi.update');

// Admin information routes
Route::prefix('admin')->group(function () {
    Route::get('/information', [InformasiController::class, 'adminIndex'])->name('admin.information.index');
    Route::get('/information/edit', [InformasiController::class, 'edit'])->name('admin.information.edit');
    Route::put('/information/update', [InformasiController::class, 'update'])->name('admin.information.update');
});

// ===== QR SCANNER ROUTE =====
Route::get('/qr/scanner', function () {
    return view('qr-scanner');
})->name('qr.scanner');

// ===== FALLBACK ROUTE =====
Route::fallback(function () {
    return redirect('/')->with('error', 'Halaman tidak ditemukan.');
});