@extends('layouts.app')

@section('title', 'Dashboard Admin - JTIKROOMS')
@section('styles')
<link rel="stylesheet" href="{{ asset('css/admin.css') }}" />
 
@section('content')
@php
use App\Models\Booking;
use App\Models\Room;
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
                <span class="stat-label">Tersedia</span>
            </div>
            <div class="stat-trend">
                <i class="fas fa-calendar-check"></i>
            </div>
        </div>
        
        <div class="stat-card info">
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-content">
                <span class="stat-number">{{ $totalUsers }}</span>
                <span class="stat-label">Total Pengguna</span>
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
                    <h3><i class="fas fa-bolt me-2"></i>Quick Actions</h3>
                    <span class="badge">Akses Cepat</span>
                </div>
                <div class="card-body">
                    <div class="actions-grid">
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
                        <button class="action-btn info" onclick="showReports()">
                            <div class="action-icon">
                                <i class="fas fa-chart-bar"></i>
                            </div>
                            <div class="action-text">
                                <span class="action-title">Laporan & Analytics</span>
                                <span class="action-desc">Statistik penggunaan</span>
                            </div>
                            <i class="fas fa-arrow-right action-arrow"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Compact Room Status -->
            <div class="card compact-rooms-card">
                <div class="card-header">
                    <h3><i class="fas fa-map-marked-alt me-2"></i>Status Ruangan</h3>
                    <span class="badge bg-secondary">{{ $rooms->count() }} Ruangan</span>
                </div>
                <div class="card-body">
                    <div class="compact-rooms-grid">
                        @foreach($rooms as $room)
                            @php
                                $statusInfo = $roomStatus[$room->name] ?? ['status' => 'available'];
                                $isOccupied = $statusInfo['status'] === 'occupied';
                                $isAvailable = $statusInfo['status'] === 'available';
                                $isMaintenance = $statusInfo['status'] === 'maintenance';
                                
                                $activeBooking = null;
                                if ($isOccupied) {
                                    $activeBooking = Booking::where('room_name', $room->name)
                                        ->where('status', 'active')
                                        ->where('waktu_berakhir', '>', now())
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
                                        @if($isAvailable)
                                            Tersedia
                                        @elseif($isOccupied)
                                            Terpakai
                                        @else
                                            Maintenance
                                        @endif
                                    </span>
                                    @if($isOccupied && $activeBooking)
                                        <small class="compact-room-time">{{ $activeBooking->waktu_berakhir->format('H:i') }}</small>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Booking Aktif Full Height -->
        <div class="content-column">
            <!-- Active Bookings - Full Height Card -->
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
                                            <span>{{ $booking->mata_kuliah ?? 'N/A' }}</span>
                                        </div>
                                        <div class="detail-item">
                                            <i class="fas fa-user-graduate"></i>
                                            <span>{{ $booking->dosen ?? 'N/A' }}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="booking-footer">
                                        <div class="booking-time">
                                            <i class="fas fa-clock"></i>
                                            <span>{{ $booking->waktu_mulai->format('H:i') }} - {{ $booking->waktu_berakhir->format('H:i') }}</span>
                                        </div>
                                        <div class="booking-actions">
                                            @php
                                                $timeLeft = $booking->waktu_berakhir->diff(now());
                                                $hoursLeft = $timeLeft->h;
                                                $minutesLeft = $timeLeft->i;
                                            @endphp
                                            <span class="time-badge {{ $hoursLeft > 0 ? 'warning' : 'danger' }}">
                                                @if($hoursLeft > 0)
                                                    {{ $hoursLeft }}j {{ $minutesLeft }}m
                                                @else
                                                    {{ $minutesLeft }}m
                                                @endif
                                            </span>
                                            <form action="{{ route('booking.cancel', $booking->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn-cancel" onclick="return confirm('Yakin ingin membatalkan booking ini?')">
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
                                <i class="fas fa-calendar-times"></i>
                            </div>
                            <h4>Tidak ada booking aktif</h4>
                            <p>Semua ruangan sedang tidak digunakan</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<style>/* Stats Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: white;
    border-radius: 20px;
    padding: 2rem;
    display: flex;
    align-items: center;
    gap: 1.5rem;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    border: 1px solid rgba(255, 255, 255, 0.2);
    position: relative;
    overflow: hidden;
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
}

.stat-card.primary::before { background: linear-gradient(90deg, #3b82f6, #6366f1); }
.stat-card.success::before { background: linear-gradient(90deg, #10b981, #059669); }
.stat-card.info::before { background: linear-gradient(90deg, #06b6d4, #0891b2); }
.stat-card.warning::before { background: linear-gradient(90deg, #f59e0b, #d97706); }

.stat-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
}

.stat-icon {
    width: 70px;
    height: 70px;
    border-radius: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.8rem;
    color: white;
}

.stat-card.primary .stat-icon { background: linear-gradient(135deg, #3b82f6, #6366f1); }
.stat-card.success .stat-icon { background: linear-gradient(135deg, #10b981, #059669); }
.stat-card.info .stat-icon { background: linear-gradient(135deg, #06b6d4, #0891b2); }
.stat-card.warning .stat-icon { background: linear-gradient(135deg, #f59e0b, #d97706); }

.stat-content {
    flex: 1;
}

.stat-number {
    display: block;
    font-size: 2.5rem;
    font-weight: 800;
    color: #1e293b;
    line-height: 1;
    margin-bottom: 0.25rem;
}

.stat-label {
    color: #64748b;
    font-size: 0.95rem;
    font-weight: 600;
}

.stat-trend {
    color: #cbd5e1;
    font-size: 2rem;
}</style>

<script>
function showRoomManagement() {
    window.location.href = "/admin/rooms";
}

function showUserManagement() {
    window.location.href = "{{ route('users.index') }}";
}

function showReports() {
    alert('Fitur Laporan & Analytics akan segera hadir!');
}

// Auto refresh every 30 seconds for real-time updates
setInterval(() => {
    // You can implement AJAX refresh here if needed
    console.log('Auto-refresh dashboard data');
}, 30000);
function showInformationManagement() {
    window.location.href = "{{ route('admin.information.index') }}";
}
</script>
@endsection