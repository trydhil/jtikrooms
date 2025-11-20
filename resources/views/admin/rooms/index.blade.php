@extends('layouts.app')

@section('title', 'Manajemen Ruangan - Dasher')

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
                <h1><i class="fas fa-door-open me-2"></i>Manajemen Ruangan</h1>
                <p class="welcome-text">Kelola data ruangan dan fasilitas</p>
            </div>
            <div class="header-actions">
                <a href="{{ route('rooms.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Tambah Ruangan
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
                <i class="fas fa-door-open"></i>
            </div>
            <div class="stat-content">
                <span class="stat-number">{{ $rooms->count() }}</span>
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
                <span class="stat-number">{{ $rooms->where('status', 'available')->count() }}</span>
                <span class="stat-label">Tersedia</span>
            </div>
            <div class="stat-trend">
                <i class="fas fa-calendar-check"></i>
            </div>
        </div>
        
        <div class="stat-card warning">
            <div class="stat-icon">
                <i class="fas fa-tools"></i>
            </div>
            <div class="stat-content">
                <span class="stat-number">{{ $rooms->where('status', 'maintenance')->count() }}</span>
                <span class="stat-label">Maintenance</span>
            </div>
            <div class="stat-trend">
                <i class="fas fa-wrench"></i>
            </div>
        </div>
        
        <div class="stat-card danger">
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-content">
                <span class="stat-number">{{ $rooms->where('status', 'occupied')->count() }}</span>
                <span class="stat-label">Terpakai</span>
            </div>
            <div class="stat-trend">
                <i class="fas fa-users"></i>
            </div>
        </div>
    </div>

    <!-- Rooms Table -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-list me-2"></i>Daftar Ruangan</h3>
            <span class="badge bg-primary">{{ $rooms->count() }} Ruangan</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="roomsTable">
                    <thead>
                        <tr>
                            <th>Nama Ruangan</th>
                            <th>Kapasitas</th>
                            <th>Fasilitas</th>
                            <th>Status</th>
                            <th>Lokasi</th>
                            <th>QR Code</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rooms as $room)
                        <tr>
                            <td>
                                <div class="room-info">
                                    <strong class="room-name">{{ $room->display_name ?? $room->name }}</strong>
                                    <small class="room-code text-muted">{{ $room->name }}</small>
                                    @if($room->description)
                                    <p class="room-desc">{{ Str::limit($room->description, 50) }}</p>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <span class="capacity-badge">{{ $room->capacity }} orang</span>
                            </td>
                            <td>
                                @if($room->facilities && count($room->facilities) > 0)
                                    <div class="facilities-tags">
                                        @foreach(array_slice($room->facilities, 0, 3) as $facility)
                                            <span class="facility-tag">{{ $facility }}</span>
                                        @endforeach
                                        @if(count($room->facilities) > 3)
                                            <span class="facility-tag-more">+{{ count($room->facilities) - 3 }}</span>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($room->status === 'available')
                                    <span class="status-badge available">Tersedia</span>
                                @elseif($room->status === 'maintenance')
                                    <span class="status-badge maintenance">Maintenance</span>
                                @else
                                    <span class="status-badge occupied">Terpakai</span>
                                @endif
                            </td>
                            <td>
                                <span class="location-text">{{ $room->location ?? 'Gedung JTIK' }}</span>
                            </td>
                            <td>
                                @if($room->qr_code)
                                    <div class="qr-code-preview">
                                        <img src="{{ $room->qr_code }}" alt="QR Code {{ $room->name }}" 
                                             class="qr-image" style="max-width: 60px; height: auto;">
                                        <div class="qr-actions">
                                            <a href="{{ $room->qr_code }}" download="qr-{{ $room->name }}.png" 
                                               class="btn-download-qr" title="Download QR">
                                                <i class="fas fa-download"></i>
                                            </a>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('rooms.show', $room) }}" 
                                       class="btn-action btn-view" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('rooms.edit', $room) }}" 
                                       class="btn-action btn-edit" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('rooms.destroy', $room) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn-action btn-delete" 
                                                title="Hapus"
                                                onclick="return confirm('Hapus ruangan {{ $room->name }}?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="empty-state">
                                    <div class="empty-icon">
                                        <i class="fas fa-door-open fa-3x"></i>
                                    </div>
                                    <h4>Belum ada ruangan</h4>
                                    <p class="text-muted">Mulai dengan menambahkan ruangan pertama</p>
                                    <a href="{{ route('rooms.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus me-2"></i>Tambah Ruangan Pertama
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
/* Hanya style tambahan untuk table dan komponen spesifik */
/* Hapus style stat-card karena sudah ada di admin.css */

.room-info {
    line-height: 1.4;
}

.room-name {
    display: block;
    font-weight: 600;
    color: #1e293b;
}

.room-code {
    font-size: 0.8rem;
}

.room-desc {
    font-size: 0.8rem;
    color: #64748b;
    margin: 0.25rem 0 0 0;
}

.capacity-badge {
    background: #dbeafe;
    color: #1e40af;
    padding: 0.3rem 0.6rem;
    border-radius: 8px;
    font-size: 0.8rem;
    font-weight: 600;
}

.facilities-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 4px;
    max-width: 200px;
}

.facility-tag {
    background: #f1f5f9;
    color: #475569;
    padding: 0.2rem 0.5rem;
    border-radius: 6px;
    font-size: 0.7rem;
    white-space: nowrap;
}

.facility-tag-more {
    background: #e2e8f0;
    color: #64748b;
    padding: 0.2rem 0.5rem;
    border-radius: 6px;
    font-size: 0.7rem;
}

.status-badge {
    padding: 0.3rem 0.8rem;
    border-radius: 8px;
    font-size: 0.8rem;
    font-weight: 600;
    white-space: nowrap;
}

.status-badge.available {
    background: #dcfce7;
    color: #166534;
}

.status-badge.maintenance {
    background: #fef3c7;
    color: #92400e;
}

.status-badge.occupied {
    background: #fecaca;
    color: #dc2626;
}

.location-text {
    color: #64748b;
    font-size: 0.9rem;
}

.qr-code-preview {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.qr-image {
    border-radius: 8px;
    border: 1px solid #e2e8f0;
}

.btn-download-qr {
    color: #64748b;
    text-decoration: none;
    padding: 0.3rem;
    border-radius: 6px;
    transition: all 0.3s ease;
}

.btn-download-qr:hover {
    color: #3b82f6;
    background: #f1f5f9;
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

.btn-view {
    background: #dbeafe;
    color: #1d4ed8;
}

.btn-view:hover {
    background: #3b82f6;
    color: white;
}

.btn-edit {
    background: #fef3c7;
    color: #d97706;
}

.btn-edit:hover {
    background: #f59e0b;
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

/* Tambahan untuk stat-card danger jika masih perlu penyesuaian */
.stat-card.danger .stat-icon {
    background: linear-gradient(135deg, #ef4444, #dc2626) !important;
}

.stat-card.danger::before {
    background: linear-gradient(90deg, #ef4444, #dc2626) !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Optional: Add DataTables for better table functionality
    // $('#roomsTable').DataTable();
});
</script>
@endsection