@extends('layouts.app')

@section('title', 'Dashboard Admin - JTIKROOMS')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/admin.css') }}" />


@section('content')
@php
use App\Models\Booking;
use App\Models\Room;
use Carbon\Carbon;
@endphp

<div class="content-wrapper">
    <!-- Header -->
    <div class="admin-header">
        <div class="header-content">
            <div class="header-text">
                <h1><i class="fas fa-user-shield me-2"></i>Dashboard Administrator</h1>
                <p class="welcome-text">Hello, <strong>{{ session('user') }}</strong> - Selamat datang di panel admin</p>
            </div>
            <div class="header-actions">
                <div class="user-avatar">
                    <i class="fas fa-user-cog"></i>
                </div>
            </div>
        </div>
        <div class="header-decoration">
            <div class="decoration-circle circle-1"></div>
            <div class="decoration-circle circle-2"></div>
            <div class="decoration-circle circle-3"></div>
        </div>
    </div>

    <!-- Real-time Statistics -->
    <div class="stats-grid">
        <div class="stat-card primary">
            <div class="stat-icon">
                <i class="fas fa-door-open"></i>
            </div>
            <div class="stat-content">
                <span class="stat-number">{{ $totalRooms }}</span>
                <span class="stat-label">Total Ruangan</span>
            </div>
            <div class="stat-trend">
                <i class="fas fa-building"></i>
            </div>
        </div>
        
        <div class="stat-card success">
            <div class="stat-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-content">
                <span class="stat-number">{{ $availableRooms }}</span>
                <span class="stat-label">Tersedia Saat Ini</span>
            </div>
            <div class="stat-trend">
                <i class="fas fa-clock"></i>
            </div>
        </div>
        
        <div class="stat-card info">
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-content">
                <span class="stat-number">{{ $totalUsers }}</span>
                <span class="stat-label">Total User</span>
            </div>
            <div class="stat-trend">
                <i class="fas fa-user-plus"></i>
            </div>
        </div>
        
        <div class="stat-card warning">
            <div class="stat-icon">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <div class="stat-content">
                <span class="stat-number">{{ $todayBookings }}</span>
                <span class="stat-label">Booking Hari Ini</span>
            </div>
            <div class="stat-trend">
                <i class="fas fa-chart-line"></i>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="content-grid">
        <!-- Left Column -->
        <div class="content-column">
            <!-- Quick Actions -->
            <div class="card actions-card">
                <div class="card-header">
                    <h3><i class="fas fa-bolt me-2"></i>Menu Utama</h3>
                    <span class="badge">Akses Cepat</span>
                </div>
                <div class="card-body">
                    <div class="actions-grid">
                        <!-- Menu Ruangan -->
                        <button class="action-btn primary" onclick="showRoomManagement()">
                            <div class="action-icon">
                                <i class="fas fa-door-open"></i>
                            </div>
                            <div class="action-text">
                                <span class="action-title">Manajemen Ruangan</span>
                                <span class="action-desc">Kelola data ruangan</span>
                            </div>
                            <i class="fas fa-arrow-right action-arrow"></i>
                        </button>
                        
                        <!-- Menu User -->
                        <button class="action-btn success" onclick="showUserManagement()">
                            <div class="action-icon">
                                <i class="fas fa-users-cog"></i>
                            </div>
                            <div class="action-text">
                                <span class="action-title">Manajemen User</span>
                                <span class="action-desc">Kelola perwakilan kelas</span>
                            </div>
                            <i class="fas fa-arrow-right action-arrow"></i>
                        </button>

                        <!-- Menu Informasi -->
                        <button class="action-btn info" onclick="showInformationManagement()">
                            <div class="action-icon">
                                <i class="fas fa-info-circle"></i>
                            </div>
                            <div class="action-text">
                                <span class="action-title">Manajemen Informasi</span>
                                <span class="action-desc">Kelola informasi JTIK</span>
                            </div>
                            <i class="fas fa-arrow-right action-arrow"></i>
                        </button>

                        <!-- Menu Laporan -->
                        <button class="action-btn warning" onclick="showReports()">
                            <div class="action-icon">
                                <i class="fas fa-chart-bar"></i>
                            </div>
                            <div class="action-text">
                                <span class="action-title">Laporan & Analytics</span>
                                <span class="action-desc">Statistik penggunaan</span>
                            </div>
                            <i class="fas fa-arrow-right action-arrow"></i>
                        </button>

                        <!-- MENU BARU: MANAJEMEN KOMENTAR (Warna Ungu) -->
                        <button class="action-btn" onclick="location.href='{{ route('admin.comments.index') }}'">
                            <div class="action-icon" style="background: #9333ea; color: white;">
                                <i class="fas fa-comments"></i>
                            </div>
                            <div class="action-text">
                                <span class="action-title" >Komentar</span>
                                <span class="action-desc">Inbox & Keluhan</span>
                            </div>
                            <i class="fas fa-arrow-right action-arrow"></i>
                        </button>

                    </div>
                </div>
            </div>

            <!-- Compact Room Status (Realtime) -->
            <div class="card compact-rooms-card">
                <div class="card-header">
                    <h3><i class="fas fa-map-marked-alt me-2"></i>Status Ruangan Live</h3>
                    <span class="badge bg-secondary">{{ $rooms->count() }} Ruangan</span>
                </div>
                <div class="card-body">
                    <div class="compact-rooms-grid">
                        @foreach($rooms as $room)
                            @php
                                // Logika Status Realtime
                                $statusInfo = $roomStatus[$room->name] ?? ['status' => 'available'];
                                $isOccupied = $statusInfo['status'] === 'occupied';
                                $isAvailable = $statusInfo['status'] === 'available';
                                $isMaintenance = $statusInfo['status'] === 'maintenance';
                                
                                $activeBooking = null;
                                if ($isOccupied) {
                                    $activeBooking = Booking::where('room_name', $room->name)
                                        ->where('status', 'active')
                                        ->where('waktu_berakhir', '>', now()->timezone('Asia/Makassar'))
                                        ->first();
                                }
                            @endphp
                            
                            <div class="compact-room-item {{ $statusInfo['status'] }}">
                                <div class="compact-room-icon">
                                    <i class="fas fa-door-{{ $isAvailable ? 'open' : 'closed' }}"></i>
                                    @if($isOccupied)
                                        <div class="room-pulse"></div>
                                    @endif
                                </div>
                                <div class="compact-room-info">
                                    <span class="compact-room-name">{{ $room->display_name ?? $room->name }}</span>
                                    <span class="compact-room-status {{ $statusInfo['status'] }}">
                                        @if($isAvailable) Tersedia @elseif($isOccupied) Terpakai @else Maintenance @endif
                                    </span>
                                    @if($isOccupied && $activeBooking)
                                        <small class="compact-room-time">Sampai {{ $activeBooking->waktu_berakhir->timezone('Asia/Makassar')->format('H:i') }}</small>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Booking Aktif List -->
        <div class="content-column">
            <div class="card bookings-card full-height-card">
                <div class="card-header">
                    <h3><i class="fas fa-list-alt me-2"></i>Booking Aktif</h3>
                    <span class="badge bg-primary">{{ $activeBookings->count() }} Aktif</span>
                </div>
                <div class="card-body scrollable-body">
                    @if($activeBookings->count() > 0)
                        <div class="bookings-list">
                            @foreach($activeBookings as $booking)
                                <div class="booking-item">
                                    <div class="booking-header">
                                        <div class="booking-room">
                                            <i class="fas fa-door-closed"></i>
                                            <strong>{{ $booking->room_name }}</strong>
                                        </div>
                                        <div class="booking-user">
                                            <span class="user-badge">{{ $booking->username }}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="booking-details">
                                        <div class="detail-item">
                                            <i class="fas fa-book"></i>
                                            <span>{{ $booking->mata_kuliah ?? '-' }}</span>
                                        </div>
                                        <div class="detail-item">
                                            <i class="fas fa-user-graduate"></i>
                                            <span>{{ $booking->dosen ?? '-' }}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="booking-footer">
                                        <div class="booking-time">
                                            <i class="fas fa-clock"></i>
                                            <span>{{ $booking->waktu_mulai->timezone('Asia/Makassar')->format('H:i') }} - {{ $booking->waktu_berakhir->timezone('Asia/Makassar')->format('H:i') }} WITA</span>
                                        </div>
                                        <div class="booking-actions">
                                            @php
                                                $waktuBerakhir = $booking->waktu_berakhir->timezone('Asia/Makassar');
                                                $sekarang = now()->timezone('Asia/Makassar');
                                                $timeLeft = $waktuBerakhir->diff($sekarang);
                                                $hoursLeft = $timeLeft->h;
                                                $minutesLeft = $timeLeft->i;
                                                
                                                // Cek apakah sudah lewat (untuk jaga-jaga visual)
                                                $isExpired = $sekarang > $waktuBerakhir;
                                            @endphp
                                            
                                            @if(!$isExpired)
                                                <span class="time-badge {{ $hoursLeft > 0 ? 'warning' : 'danger' }}">
                                                    {{ $hoursLeft > 0 ? $hoursLeft.'j '.$minutesLeft.'m' : $minutesLeft.'m' }}
                                                </span>
                                            @else
                                                <span class="time-badge danger">Selesai</span>
                                            @endif

                                            <form action="{{ route('booking.cancel', $booking->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn-cancel" onclick="return confirm('Yakin ingin menghapus booking ini? Data akan tersimpan di Laporan sebagai Dibatalkan.')">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <h4>Semua Beres!</h4>
                            <p>Tidak ada booking aktif saat ini.</p>
                            <small class="text-muted">Data historis bisa dilihat di menu Laporan.</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function showRoomManagement() {
    window.location.href = "{{ route('rooms.index') }}";
}

function showUserManagement() {
    window.location.href = "{{ route('users.index') }}";
}

function showInformationManagement() {
    window.location.href = "{{ route('admin.information.index') }}";
}

function showReports() {
    window.location.href = "{{ route('reports.index') }}";
}
function showCommentManagement() {
    window.location.href = "{{ route('admin.comments.index') }}";
}

// Auto refresh setiap 60 detik
setInterval(() => {
    window.location.reload();
}, 60000);
</script>
@endsection