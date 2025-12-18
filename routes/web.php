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
use App\Http\Controllers\ReportController;
use App\Http\Controllers\CommentAdminController; // Admin Controller
use App\Http\Controllers\CommentController;      // User Controller

// ===== HALAMAN PUBLIK =====
Route::get('/', [PageController::class, 'index'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/informasi', [InformasiController::class, 'index'])->name('informasi');
Route::get('/room/{name}', [PageController::class, 'roomInfo'])->name('room.info');

// ===== AUTH =====
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/check-session', [AuthController::class, 'checkSession'])->name('session.check');

// ===== DASHBOARD =====
Route::get('/dashboard/admin', [DashboardController::class, 'admin'])->name('dashboard.admin');
Route::get('/dashboard/kelas', [DashboardController::class, 'kelas'])->name('dashboard.kelas');

// ===== BOOKING =====
Route::get('/booking/create/{roomName}', [BookingController::class, 'createFromQR'])->name('booking.create');
Route::post('/booking/store', [BookingController::class, 'store'])->name('booking.store');
Route::post('/booking/cancel/{id}', [BookingController::class, 'cancel'])->name('booking.cancel');
Route::get('/booking/active', [BookingController::class, 'getActiveBookings'])->name('booking.active');
Route::get('/booking/all', [BookingController::class, 'getAllBookings'])->name('booking.all');
Route::post('/booking/expire', [BookingController::class, 'expireOldBookings'])->name('booking.expire');

// ===== API =====
Route::prefix('api')->group(function () {
    Route::get('/rooms/status', [APIController::class, 'getAllRoomStatuses']);
    Route::get('/rooms/list', [APIController::class, 'getAvailableRooms']);
    Route::get('/room/{roomName}/status', [APIController::class, 'getRoomStatus']);
    Route::post('/scan-qr', [APIController::class, 'scanQR']);
    Route::post('/quick-booking', [APIController::class, 'quickBooking']);
});

// ===== USER KIRIM KOMENTAR =====
// routes/web.php
Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');// ===== ADMIN PANEL =====
Route::prefix('admin')->group(function () {
    
    // 1. MANAJEMEN KOMENTAR
    Route::get('/comments', [CommentAdminController::class, 'index'])->name('admin.comments.index');
    Route::post('/comments/{id}/resolve', [CommentAdminController::class, 'resolve'])->name('admin.comments.resolve');
    Route::delete('/comments/{id}', [CommentAdminController::class, 'destroy'])->name('admin.comments.destroy');

    // 2. LAPORAN
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export', [ReportController::class, 'exportExcel'])->name('reports.export');

    // 3. RUANGAN
    Route::resource('rooms', RoomController::class);
    Route::post('/rooms/{room}/generate-qr', [RoomController::class, 'generateQR'])->name('rooms.generate-qr');
    Route::get('/rooms/{room}/print', [RoomController::class, 'downloadPdf'])->name('rooms.print');

    // 4. USER
    Route::resource('users', UserController::class);
    Route::post('/users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');

    // 5. INFORMASI
    Route::get('/information', [InformasiController::class, 'adminIndex'])->name('admin.information.index');
    Route::get('/information/edit', [InformasiController::class, 'edit'])->name('admin.information.edit');
    Route::put('/information/update', [InformasiController::class, 'update'])->name('admin.information.update');
});

// ===== FALLBACK =====
Route::get('/qr/scanner', function () { return view('qr-scanner'); })->name('qr.scanner');
Route::fallback(function () { return redirect('/')->with('error', 'Halaman tidak ditemukan.'); });


// ===== TAMBAHAN QUEUE CONTROLLER =====
Route::post('/queue/take/{room}', [QueueController::class, 'takeQueue'])->name('queue.take');
Route::get('/dashboard/kelas', [DashboardController::class, 'kelas'])->name('dashboard.kelas');

Route::post('/booking/store', [BookingController::class, 'store'])
    ->middleware('throttle.booking')
    ->name('booking.store');