@extends('layouts.app')

@section('title', 'Dashboard Kelas - JTIK ROOMS')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/kelas.css') }}">
<style>
    .btn-scan-mobile {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 10px 16px;
        border-radius: 10px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-right: 10px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    .btn-scan-mobile:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        color: white;
    }

    .btn-scan-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 15px 30px;
        border-radius: 15px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        transition: all 0.3s ease;
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.3);
        font-size: 1.1rem;
    }

    .btn-scan-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.5);
        color: white;
    }

    .scan-cta-mobile {
        text-align: center;
        margin: 2rem 0;
    }

    .welcome-card-modern {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 20px;
        padding: 2.5rem;
        margin-bottom: 2rem;
        color: white;
        position: relative;
        overflow: hidden;
    }

    .scan-feature {
        text-align: center;
        padding: 1rem;
    }

    .scan-icon {
        width: 80px;
        height: 80px;
        background: rgba(255,255,255,0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        backdrop-filter: blur(10px);
        border: 2px solid rgba(255,255,255,0.3);
    }

    .scan-icon i {
        font-size: 2rem;
        color: white;
    }

    /* Mobile responsive */
    @media (max-width: 768px) {
        .btn-scan-mobile {
            padding: 8px 12px;
            font-size: 14px;
        }
        
        .btn-scan-mobile span {
            display: none;
        }
        
        .btn-scan-primary {
            padding: 12px 24px;
            font-size: 1rem;
        }
        
        .welcome-card-modern {
            padding: 1.5rem;
        }
        
        .scan-feature {
            padding: 0.5rem;
        }
    }
</style>

@section('content')
@php
    use App\Models\Booking;
    use Carbon\Carbon;
    
    // Set Timezone
    $now = now()->timezone('Asia/Makassar');
    $startOfWeek = $now->copy()->startOfWeek(); // Senin jam 00:00 minggu ini
    $endOfWeek = $now->copy()->endOfWeek();     // Minggu jam 23:59 minggu ini

    // 1. Booking Aktif (Realtime)
    $activeBookings = Booking::where('username', session('user'))
        ->where('status', 'active')
        ->where('waktu_berakhir', '>', $now)
        ->orderBy('waktu_berakhir')
        ->get();
        
    // 2. Total Booking (RESET MINGGUAN)
    // Logic: Hanya menghitung booking yang dibuat antara Senin - Minggu ini
    $totalBookings = Booking::where('username', session('user'))
        ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
        ->count();

    // 3. Booking Selesai (RESET MINGGUAN)
    // Logic: Hanya menghitung booking selesai minggu ini
    $completedBookings = Booking::where('username', session('user'))
        ->where('status', 'completed')
        ->whereBetween('updated_at', [$startOfWeek, $endOfWeek])
        ->count();
@endphp

<div class="dashboard-kelas">
    <!-- Header dengan Button Scan -->
    <div class="header">
        <h2><i class="fas fa-chalkboard me-2"></i>Dashboard Kelas</h2>
        <div class="user-info">
            
        </div>
    </div>

    <!-- Welcome Card Modern -->
    <div class="welcome-card-modern">
        <div class="row align-items-center">
            <div class="col-md-8">
                <div class="welcome-content">
                    <h3 style="color: white; margin-bottom: 1rem;">Selamat Datang, {{ session('user') }}! 👋</h3>
                    <p style="color: rgba(255,255,255,0.9); margin-bottom: 2rem; font-size: 1.1rem;">
                        Kelola booking ruangan dengan mudah. Gunakan fitur scan QR code untuk booking ruangan secara instan.
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="scan-feature">
                    <div class="scan-icon">
                        <i class="fas fa-qrcode"></i>
                    </div>
                    <a href="{{ route('qr.scanner') }}" class="btn-scan-primary d-none d-md-inline-flex">
                        <i class="fas fa-camera"></i>
                        Scan QR Code
                    </a>
                    <p style="color: rgba(255,255,255,0.8); margin-top: 1rem; font-size: 0.9rem;">
                        Buka kamera dan scan QR code di pintu ruangan
                    </p>

                      <!-- CTA Scan untuk Mobile -->
                    <div class="scan-cta-mobile d-block d-md-none">
                        <a href="{{ route('qr.scanner') }}" class="btn-scan-primary">
                            <i class="fas fa-camera"></i>
                            Scan QR Code Sekarang
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="stats-grid-kelas">
        <div class="stat-card-kelas">
            <div class="stat-icon-kelas">
                <i class="fas fa-door-open"></i>
            </div>
            <div class="stat-content-kelas">
                <span class="stat-number-kelas">{{ $activeBookings->count() }}</span>
                <span class="stat-label-kelas">Booking Aktif</span>
            </div>
        </div>
        
        <!-- Ubah Label jadi Booking Minggu Ini -->
        <div class="stat-card-kelas">
            <div class="stat-icon-kelas">
                <i class="fas fa-calendar-week"></i>
            </div>
            <div class="stat-content-kelas">
                <span class="stat-number-kelas">{{ $totalBookings }}</span>
                <span class="stat-label-kelas">Booking Minggu Ini</span>
            </div>
        </div>
        
        <!-- Selesai Minggu Ini -->
        <div class="stat-card-kelas">
            <div class="stat-icon-kelas">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-content-kelas">
                <span class="stat-number-kelas">{{ $completedBookings }}</span>
                <span class="stat-label-kelas">Selesai (Mingguan)</span>
            </div>
        </div>
    </div>

    <!-- Booking Aktif -->
    <div class="active-booking-section">
        <div class="section-header">
            <h4><i class="fas fa-clock me-2"></i>Booking Aktif</h4>
            <span class="badge">{{ $activeBookings->count() }} Aktif</span>
        </div>
        
        <div class="booking-list">
            @if($activeBookings->count() > 0)
                @foreach($activeBookings as $booking)
                @php
                    $waktuBerakhir = $booking->waktu_berakhir->timezone('Asia/Makassar');
                    $sekarang = now()->timezone('Asia/Makassar');
                    $timeLeft = $waktuBerakhir->diffInMinutes($sekarang, false); // false agar negatif jika lewat
                    // Perbaikan logika hampir habis (misal 15 menit)
                    $isAlmostOver = ($timeLeft > 0 && $timeLeft <= 15);
                @endphp
                <div class="booking-card {{ $isAlmostOver ? 'pulse' : '' }}">
                    <div class="booking-header">
                        <div class="booking-title">
                            <h5>{{ $booking->room_name }}</h5>
                            <span class="booking-badge">
                                <i class="fas fa-circle me-1"></i>Aktif
                            </span>
                        </div>
                        <div class="countdown-timer">
                            <i class="fas fa-hourglass-half"></i>
                            {{ $waktuBerakhir->format('H:i') }} WITA
                        </div>
                    </div>
                    
                    <div class="booking-details">
                        <div class="detail-item">
                            <i class="fas fa-book"></i>
                            <div>
                                <strong>Mata Kuliah</strong>
                                <div>{{ $booking->mata_kuliah }}</div>
                            </div>
                        </div>
                        <div class="detail-item">
                            <i class="fas fa-user-graduate"></i>
                            <div>
                                <strong>Dosen</strong>
                                <div>{{ $booking->dosen }}</div>
                            </div>
                        </div>
                        <div class="detail-item">
                            <i class="fas fa-clock"></i>
                            <div>
                                <strong>Sisa Waktu</strong>
                                <div>{{ $waktuBerakhir->diffForHumans($sekarang) }}</div>
                            </div>
                        </div>
                    </div>
                    
                    @if($booking->keterangan)
                    <div class="detail-item mb-2">
                        <i class="fas fa-sticky-note"></i>
                        <div>
                            <strong>Keterangan</strong>
                            <div>{{ $booking->keterangan }}</div>
                        </div>
                    </div>
                    @endif
                    
                    <div class="booking-actions">
                        <form action="{{ route('booking.cancel', $booking->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-end-booking" onclick="return confirm('Yakin ingin mengakhiri booking {{ $booking->room_name }}?')">
                                <i class="fas fa-stop me-1"></i>Akhiri Booking
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            @else
                <div class="no-booking">
                    <i class="fas fa-door-open"></i>
                    <p>Tidak ada booking aktif</p>
                    <small>
                        <a href="{{ route('qr.scanner') }}" class="text-primary">Scan QR code</a> 
                        di pintu ruangan untuk memulai booking
                    </small>
                </div>
            @endif
        </div>
    </div>

    <!-- Panduan Booking -->
    <div class="guide-section">
        <h5><i class="fas fa-info-circle me-2"></i>Cara Booking Ruangan</h5>
        <div class="steps-grid">
            <div class="step-item">
                <div class="step-number">1</div>
                <div class="step-content">
                    <strong>Buka Scanner</strong>
                    <span>Klik tombol "Scan QR" di dashboard</span>
                </div>
            </div>
            <div class="step-item">
                <div class="step-number">2</div>
                <div class="step-content">
                    <strong>Pergi ke Ruangan</strong>
                    <span>Temukan ruangan yang ingin digunakan</span>
                </div>
            </div>
            <div class="step-item">
                <div class="step-number">3</div>
                <div class="step-content">
                    <strong>Scan QR Code</strong>
                    <span>Arahkan kamera ke QR code di pintu</span>
                </div>
            </div>
            <div class="step-item">
                <div class="step-number">4</div>
                <div class="step-content">
                    <strong>Isi Form & Konfirmasi</strong>
                    <span>Lengkapi data dan submit booking</span>
                </div>
            </div>
        </div>
        
    </div>
</div>

<script>
// Auto-refresh setiap 1 menit untuk update status booking
setInterval(() => {
    window.location.reload();
}, 60000);

// Add smooth animations
document.addEventListener('DOMContentLoaded', function() {
    // Animate stats cards on load
    const statCards = document.querySelectorAll('.stat-card-kelas');
    statCards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            card.style.transition = 'all 0.6s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 200);
    });
});
</script>
@endsection
```

