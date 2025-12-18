@extends('layouts.app')

@section('title', 'Manajemen Ruangan - Dasher')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/admin.css') }}" />
<style>
.luas-badge {
    background: #e0f2fe;
    color: #0369a1;
    padding: 0.4rem 0.8rem;
    border-radius: 8px;
    font-size: 0.8rem;
    font-weight: 600;
    display: inline-block;
}
</style>
 
@section('content')
<div class="content-wrapper">
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
                            <th>Luas</th>
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
                                <span class="luas-badge">{{ $room->luas ?? '0' }} m²</span>
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
                                @if($room->qr_code && file_exists(public_path($room->qr_code)))
                                    <div class="qr-preview">
                                        <a href="{{ asset($room->qr_code) }}" download="QR-{{ $room->name }}.png" title="Download QR">
                                            <img src="{{ asset($room->qr_code) }}" class="qr-thumbnail" alt="QR">
                                        </a>
                                    </div>
                                @else
                                    <span class="text-muted small">-</span>
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
                            <td colspan="8" class="text-center py-5">
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Optional: Add DataTables for better table functionality
    // $('#roomsTable').DataTable();
});
</script>
@endsection