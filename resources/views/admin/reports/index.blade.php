@extends('layouts.app')

@section('title', 'Laporan & Analytics - JTIKROOMS')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/admin.css') }}" />
<style>
    .chart-container { position: relative; height: 300px; width: 100%; }
    .period-selector {
        background: #f1f5f9;
        padding: 5px;
        border-radius: 10px;
        display: inline-flex;
        gap: 5px;
    }
    .period-btn {
        padding: 8px 20px;
        border-radius: 8px;
        text-decoration: none;
        color: #64748b;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.3s;
        border: none;
        background: transparent;
    }
    .period-btn:hover { color: #3b82f6; background: #e2e8f0; }
    
    .period-btn.active {
        background: white;
        color: #3b82f6;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    
    @media print {
        .admin-header .btn, .sidebar, .period-selector { display: none !important; }
        .content-wrapper { margin: 0; padding: 0; }
        .card { border: 1px solid #ccc !important; box-shadow: none !important; }
    }
</style>

@section('content')
<div class="content-wrapper">
    <!-- Header -->
    <div class="admin-header">
        <div class="header-content">
            <div class="header-text">
                <h1><i class="fas fa-chart-pie me-2"></i>Laporan & Analytics</h1>
                <p class="welcome-text">Analisis data: <strong>{{ $labelPeriode }}</strong></p>
            </div>
            
            <div class="header-actions">
                <!-- FILTER PERIODE (TIMELINE) -->
                <div class="period-selector me-3">
                    <a href="{{ route('reports.index', ['periode' => 'hari_ini']) }}" 
                       class="period-btn {{ $periode == 'hari_ini' ? 'active' : '' }}">
                       Hari Ini
                    </a>
                    <a href="{{ route('reports.index', ['periode' => 'minggu_ini']) }}" 
                       class="period-btn {{ $periode == 'minggu_ini' ? 'active' : '' }}">
                       Minggu Ini
                    </a>
                    <a href="{{ route('reports.index', ['periode' => 'total']) }}" 
                       class="period-btn {{ $periode == 'total' ? 'active' : '' }}">
                       Total
                    </a>
                </div>

                <div class="d-flex gap-2">
                    <!-- TOMBOL EXCEL -->
                    <!-- Pastikan route 'reports.export' sudah ada di web.php -->
                    <a href="{{ route('reports.export', ['periode' => $periode]) }}" class="btn btn-success text-white" style="background-color: #10b981; border: none;">
                        <i class="fas fa-file-excel me-2"></i>Excel
                    </a>

                    <button onclick="window.print()" class="btn btn-primary">
                        <i class="fas fa-print me-2"></i>Cetak
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="stats-grid mb-4">
        <div class="stat-card primary">
            <div class="stat-icon"><i class="fas fa-clipboard-list"></i></div>
            <div class="stat-content">
                <span class="stat-number">{{ $totalBookings }}</span>
                <span class="stat-label">Total Booking</span>
            </div>
        </div>
        <div class="stat-card success">
            <div class="stat-icon"><i class="fas fa-percentage"></i></div>
            <div class="stat-content">
                <span class="stat-number">{{ $successRate }}%</span>
                <span class="stat-label">Success Rate</span>
            </div>
        </div>
        <div class="stat-card warning">
            <div class="stat-icon"><i class="fas fa-crown"></i></div>
            <div class="stat-content">
                <span class="stat-number">{{ $topUser->username ?? '-' }}</span>
                <span class="stat-label">Top User {{ $periode == 'hari_ini' ? 'Hari Ini' : '' }}</span>
            </div>
            @if($topUser)
            <div class="stat-trend text-muted" style="font-size: 0.9rem;">
                {{ $topUser->total }}x
            </div>
            @endif
        </div>
        
        <!-- PERBAIKAN KARTU MERAH -->
        <!-- Menggunakan class 'danger' agar garis merah muncul di ATAS (sesuai admin.css) -->
        <div class="stat-card danger">
            <div class="stat-icon">
                <i class="fas fa-ban"></i>
            </div>
            <div class="stat-content">
                <span class="stat-number">{{ $cancelledBookings }}</span>
                <span class="stat-label">Dibatalkan</span>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="row g-4 mb-4">
        <!-- Grafik Tren -->
        <div class="col-lg-8">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold text-primary"><i class="fas fa-chart-area me-2"></i>Tren Global (7 Hari)</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="trendChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grafik Populer -->
        <div class="col-lg-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold text-primary"><i class="fas fa-star me-2"></i>Ruangan Populer</h5>
                    @if($periode != 'total')
                        <small class="text-muted d-block">{{ $labelPeriode }}</small>
                    @endif
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="roomChart"></canvas>
                    </div>
                    @if(count($roomLabels) == 0)
                        <p class="text-center text-muted mt-5">Belum ada data untuk periode ini</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Transactions Table -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold text-primary"><i class="fas fa-history me-2"></i>Riwayat Booking - {{ $labelPeriode }}</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-4 py-3">Waktu</th>
                            <th>Peminjam</th>
                            <th>Ruangan</th>
                            <th>Kegiatan</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentBookings as $booking)
                        <tr>
                            <td class="px-4 text-muted small">
                                {{ $booking->created_at->format('d M Y, H:i') }}
                            </td>
                            <td class="fw-bold">{{ $booking->username }}</td>
                            <td><span class="badge bg-info text-dark">{{ $booking->room_name }}</span></td>
                            <td>{{ $booking->mata_kuliah ?? '-' }}</td>
                            <td>
                                @if($booking->status == 'cancelled')
                                    <span class="badge bg-danger">Dibatalkan</span>
                                @elseif($booking->status == 'completed' || $booking->waktu_berakhir < now())
                                    <span class="badge bg-secondary">Selesai</span>
                                @else
                                    <span class="badge bg-success">Aktif</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="fas fa-inbox fa-2x mb-3 d-block"></i>
                                Tidak ada data booking untuk periode <strong>{{ $labelPeriode }}</strong>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Load Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. CHART TREN (Garis)
    const ctxTrend = document.getElementById('trendChart').getContext('2d');
    new Chart(ctxTrend, {
        type: 'line',
        data: {
            labels: {!! json_encode($dates) !!},
            datasets: [{
                label: 'Total Booking',
                data: {!! json_encode($trendData) !!},
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                borderWidth: 2,
                tension: 0.4,
                fill: true
            }]
        },
        options: { 
            responsive: true, 
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 } }
            }
        }
    });

    // 2. CHART POPULER (Donat)
    @if(count($roomLabels) > 0)
    const ctxRoom = document.getElementById('roomChart').getContext('2d');
    new Chart(ctxRoom, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($roomLabels) !!},
            datasets: [{
                data: {!! json_encode($roomData) !!},
                backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6'],
                borderWidth: 0
            }]
        },
        options: { 
            responsive: true, 
            maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom' } }
        }
    });
    @endif
});
</script>
@endsection