@extends('layouts.app')

@section('title', 'Manajemen Perwakilan Kelas - Dasher')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/admin.css') }}" />
 

@section('content')
@php
use App\Models\Booking;
@endphp

<div class="content-wrapper">
    <!-- Header -->
    <div class="admin-header">
        <div class="header-content">
            <div class="header-text">
                <h1><i class="fas fa-users-cog me-2"></i>Manajemen Perwakilan Kelas</h1>
                <p class="welcome-text">Kelola data perwakilan kelas untuk akses booking</p>
            </div>
            <div class="header-actions">
                <a href="{{ route('users.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Tambah Perwakilan
                </a>
            </div>
        </div>
        <div class="header-decoration">
            <div class="decoration-circle circle-1"></div>
            <div class="decoration-circle circle-2"></div>
            <div class="decoration-circle circle-3"></div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Statistics -->
    <div class="stats-grid">
        <div class="stat-card primary">
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-content">
                <span class="stat-number">{{ $users->count() }}</span>
                <span class="stat-label">Total Perwakilan</span>
            </div>
            <div class="stat-trend">
                <i class="fas fa-user-friends"></i>
            </div>
        </div>
        
        <div class="stat-card success">
            <div class="stat-icon">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <div class="stat-content">
                @php
                    $uniqueAngkatan = $users->whereNotNull('angkatan')->pluck('angkatan')->unique()->count();
                @endphp
                <span class="stat-number">{{ $uniqueAngkatan }}</span>
                <span class="stat-label">Total Angkatan</span>
            </div>
            <div class="stat-trend">
                <i class="fas fa-calendar-alt"></i>
            </div>
        </div>
        
        <div class="stat-card info">
            <div class="stat-icon">
                <i class="fas fa-laptop-code"></i>
            </div>
            <div class="stat-content">
                @php
                    $tekoms = $users->where('prodi', 'TEKOM')->count();
                @endphp
                <span class="stat-number">{{ $tekoms }}</span>
                <span class="stat-label">TEKOM</span>
            </div>
            <div class="stat-trend">
                <i class="fas fa-microchip"></i>
            </div>
        </div>
        
        <div class="stat-card warning">
            <div class="stat-icon">
                <i class="fas fa-chalkboard-teacher"></i>
            </div>
            <div class="stat-content">
                @php
                    $ptiks = $users->where('prodi', 'PTIK')->count();
                @endphp
                <span class="stat-number">{{ $ptiks }}</span>
                <span class="stat-label">PTIK</span>
            </div>
            <div class="stat-trend">
                <i class="fas fa-book"></i>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-list me-2"></i>Daftar Perwakilan Kelas</h3>
            <span class="badge bg-primary">{{ $users->count() }} Perwakilan</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="usersTable">
                    <thead>
                        <tr>
                            <th>Kode Kelas</th>
                            <th>Program Studi</th>
                            <th>Kelas</th>
                            <th>Angkatan</th>
                            <th>Password</th>
                            <th>Status Penggunaan</th>
                            <th>Info Booking Aktif</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        @php
                            // Cek booking aktif
                            $activeBookings = \App\Models\Booking::where('username', $user->username)
                                ->where('status', 'active')
                                ->where('waktu_berakhir', '>', now())
                                ->get();
                            
                            $isBooking = $activeBookings->count() > 0;
                            $currentBooking = $activeBookings->first();
                        @endphp
                        <tr>
                            <td>
                                <strong class="username-text">{{ $user->username }}</strong>
                            </td>
                            <td>
                                @if($user->prodi == 'TEKOM')
                                    <span class="prodi-badge tekom">Teknik Komputer</span>
                                @elseif($user->prodi == 'PTIK')
                                    <span class="prodi-badge ptik">Pendidikan Teknik Informatika dan Komputer</span>
                                @else
                                    <span class="prodi-badge other">{{ $user->prodi ?? 'Lainnya' }}</span>
                                @endif
                            </td>
                            <td>
                                <span class="kelas-badge">
                                    @if($user->nama_kelas)
                                        {{ $user->nama_kelas }}
                                    @else
                                        Kelas {{ substr($user->username, -2, 1) }}
                                    @endif
                                </span>
                            </td>
                            <td>
                                <span class="angkatan-badge">{{ $user->angkatan ?? '-' }}</span>
                            </td>
                            <td>
                                <div class="password-field">
                                    <span class="password-toggle" style="cursor: pointer;" 
                                          onclick="togglePassword(this)" 
                                          data-password="{{ $user->password }}"
                                          title="Klik untuk lihat password">
                                        <i class="fas fa-eye me-1"></i>
                                        <span class="password-display">{{ substr($user->password, 0, 3) }}•••</span>
                                    </span>
                                    <small class="text-muted d-block mt-1">Plain Text</small>
                                </div>
                            </td>
                            <td>
                                @if($isBooking)
                                    <span class="status-badge booking">
                                        <i class="fas fa-door-closed me-1"></i>Sedang Memakai Ruangan
                                    </span>
                                @else
                                    <span class="status-badge available">
                                        <i class="fas fa-door-open me-1"></i>Tidak Sedang Booking
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($isBooking && $currentBooking)
                                    <div class="booking-info">
                                        <small>
                                            <strong>Ruangan:</strong> {{ $currentBooking->room_name }}<br>
                                            <strong>Sampai:</strong> {{ $currentBooking->waktu_berakhir->format('H:i') }}
                                        </small>
                                    </div>
                                @else
                                    <small class="text-muted">-</small>
                                @endif
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('users.edit', $user) }}" 
                                       class="btn-action btn-edit" title="Edit Data">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('users.reset-password', $user) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" 
                                                class="btn-action btn-reset" 
                                                title="Reset Password"
                                                onclick="return confirm('Reset password {{ $user->username }} ke default (password123)?')">
                                            <i class="fas fa-key"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn-action btn-delete" 
                                                title="Hapus"
                                                onclick="return confirm('Hapus perwakilan kelas {{ $user->username }}? Tindakan ini tidak dapat dibatalkan!')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div class="empty-state">
                                    <div class="empty-icon">
                                        <i class="fas fa-users fa-3x"></i>
                                    </div>
                                    <h4>Belum ada perwakilan kelas terdaftar</h4>
                                    <p class="text-muted">Mulai dengan menambahkan perwakilan kelas pertama</p>
                                    <a href="{{ route('users.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus me-2"></i>Tambah Perwakilan Kelas
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
.username-text {
    color: #1e293b;
    font-weight: 600;
}

.prodi-badge {
    padding: 0.4rem 0.8rem;
    border-radius: 8px;
    font-size: 0.8rem;
    font-weight: 600;
    white-space: nowrap;
}

.prodi-badge.tekom {
    background: #dbeafe;
    color: #1e40af;
}

.prodi-badge.ptik {
    background: #dcfce7;
    color: #166534;
}

.prodi-badge.other {
    background: #f1f5f9;
    color: #64748b;
}

.kelas-badge {
    background: #e0e7ff;
    color: #3730a3;
    padding: 0.3rem 0.6rem;
    border-radius: 6px;
    font-size: 0.8rem;
    font-weight: 600;
}

.angkatan-badge {
    background: #f1f5f9;
    color: #475569;
    padding: 0.3rem 0.6rem;
    border-radius: 6px;
    font-size: 0.8rem;
    font-weight: 600;
}

.password-field {
    position: relative;
}

.password-toggle {
    background: #dbeafe;
    color: #1e40af;
    padding: 0.4rem 0.8rem;
    border-radius: 6px;
    font-size: 0.8rem;
    font-weight: 600;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    user-select: none;
}

.password-toggle:hover {
    transform: scale(1.05);
}

.password-toggle.revealed {
    background: #fef3c7;
    color: #92400e;
}

.status-badge {
    padding: 0.4rem 0.8rem;
    border-radius: 8px;
    font-size: 0.8rem;
    font-weight: 600;
    white-space: nowrap;
    display: inline-flex;
    align-items: center;
}

.status-badge.booking {
    background: #fef3c7;
    color: #92400e;
}

.status-badge.available {
    background: #dcfce7;
    color: #166534;
}

.booking-info {
    font-size: 0.8rem;
    line-height: 1.3;
}

.action-buttons {
    display: flex;
    gap: 0.3rem;
}

.btn-action {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
}

.btn-edit {
    background: #fef3c7;
    color: #d97706;
}

.btn-edit:hover {
    background: #f59e0b;
    color: white;
}

.btn-reset {
    background: #e0e7ff;
    color: #4f46e5;
}

.btn-reset:hover {
    background: #6366f1;
    color: white;
}

.btn-delete {
    background: #fecaca;
    color: #dc2626;
}

.btn-delete:hover {
    background: #ef4444;
    color: white;
}

.empty-state {
    text-align: center;
    padding: 2rem;
}

.empty-icon {
    color: #cbd5e1;
    margin-bottom: 1rem;
}

.empty-state h4 {
    color: #475569;
    margin-bottom: 0.5rem;
}

/* Table improvements */
.table th {
    border-top: none;
    font-weight: 600;
    color: #475569;
    background: #f8fafc;
    padding: 1rem 0.75rem;
}

.table td {
    vertical-align: middle;
    padding: 1rem 0.75rem;
    border-color: #f1f5f9;
}

.table tbody tr:hover {
    background: #f8fafc;
}
</style>

<script>
function togglePassword(element) {
    const passwordDisplay = element.querySelector('.password-display');
    const eyeIcon = element.querySelector('i');
    const actualPassword = element.getAttribute('data-password');
    
    if (passwordDisplay.textContent.includes('•••')) {
        // Show full password
        passwordDisplay.textContent = actualPassword;
        eyeIcon.className = 'fas fa-eye-slash me-1';
        element.classList.add('revealed');
        
        // Auto hide after 5 seconds
        setTimeout(() => {
            if (passwordDisplay.textContent === actualPassword) {
                togglePassword(element);
            }
        }, 5000);
    } else {
        // Show masked password
        passwordDisplay.textContent = actualPassword.substring(0, 3) + '•••';
        eyeIcon.className = 'fas fa-eye me-1';
        element.classList.remove('revealed');
    }
}
</script>
@endsection