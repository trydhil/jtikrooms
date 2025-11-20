@extends('layouts.app')

@section('title', 'Detail Ruangan - Dasher')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/admin.css') }}" />


@section('content')
<div class="content-wrapper">
    <!-- Header -->
    <div class="admin-header">
        <div class="header-content">
            <div class="header-text">
                <h1><i class="fas fa-info-circle me-2"></i>Detail Ruangan</h1>
                <p class="welcome-text">Informasi lengkap ruangan {{ $room->name }}</p>
            </div>
            <div class="header-actions">
                <a href="{{ route('rooms.edit', $room->id) }}" class="btn btn-primary me-2">
                    <i class="fas fa-edit me-2"></i>Edit
                </a>
                <a href="{{ route('rooms.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
            </div>
        </div>
        <div class="header-decoration">
            <div class="decoration-circle circle-1"></div>
            <div class="decoration-circle circle-2"></div>
            <div class="decoration-circle circle-3"></div>
        </div>
    </div>

    <!-- Content Grid -->
    <div class="content-grid">
        <!-- Left Column - Room Details -->
        <div class="content-column">
            <div class="card">
                <div class="card-header">
                    <h3><i class="fas fa-door-open me-2"></i>Informasi Ruangan</h3>
                    <span class="badge bg-primary">{{ $room->name }}</span>
                </div>
                <div class="card-body">
                    <div class="detail-grid">
                        <div class="detail-item">
                            <label class="detail-label">Nama Ruangan</label>
                            <span class="detail-value">{{ $room->name }}</span>
                        </div>
                        
                        <div class="detail-item">
                            <label class="detail-label">Display Name</label>
                            <span class="detail-value">{{ $room->display_name ?? '-' }}</span>
                        </div>
                        
                        <div class="detail-item">
                            <label class="detail-label">Lokasi</label>
                            <span class="detail-value">{{ $room->location ?? 'Gedung JTIK' }}</span>
                        </div>
                        
                        <div class="detail-item">
                            <label class="detail-label">Kapasitas</label>
                            <span class="detail-value">{{ $room->capacity ? $room->capacity . ' orang' : '-' }}</span>
                        </div>
                        
                        <div class="detail-item">
                            <label class="detail-label">Status</label>
                            <span class="detail-value">
                                @if($room->status === 'available')
                                    <span class="status-badge available">Tersedia</span>
                                @elseif($room->status === 'maintenance')
                                    <span class="status-badge maintenance">Maintenance</span>
                                @else
                                    <span class="status-badge occupied">Terpakai</span>
                                @endif
                            </span>
                        </div>

                        @if($room->description)
                        <div class="detail-item full-width">
                            <label class="detail-label">Deskripsi</label>
                            <div class="detail-value">
                                <p class="mb-0">{{ $room->description }}</p>
                            </div>
                        </div>
                        @endif

                        @if($room->facilities && is_array($room->facilities) && count($room->facilities) > 0)
                        <div class="detail-item full-width">
                            <label class="detail-label">Fasilitas</label>
                            <div class="detail-value">
                                <div class="facilities-tags">
                                    @foreach($room->facilities as $facility)
                                        <span class="facility-tag">{{ $facility }}</span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - QR Code & Info -->
        <div class="content-column">
            <!-- QR Code Card -->
            <div class="card">
                <div class="card-header">
                    <h3><i class="fas fa-qrcode me-2"></i>QR Code</h3>
                    @if($room->qr_code)
                        <span class="badge bg-success">Tersedia</span>
                    @else
                        <span class="badge bg-secondary">Belum Generate</span>
                    @endif
                </div>
                <div class="card-body text-center">
                    @if($room->qr_code)
                        <img src="{{ $room->qr_code }}" alt="QR Code {{ $room->name }}" 
                             class="qr-image mb-3" style="max-width: 200px; border-radius: 10px;">
                        <div class="d-grid gap-2">
                            <a href="{{ $room->qr_code }}" download="qr-{{ $room->name }}.png" 
                               class="btn btn-primary">
                                <i class="fas fa-download me-2"></i>Download QR Code
                            </a>
                        </div>
                    @else
                        <div class="empty-state py-4">
                            <div class="empty-icon mb-3">
                                <i class="fas fa-qrcode fa-3x text-muted"></i>
                            </div>
                            <h5 class="text-muted">QR Code Belum Tersedia</h5>
                            <p class="text-muted small mb-3">Generate QR code untuk ruangan ini</p>
                            <a href="{{ route('rooms.generate-qr', $room->id) }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Generate QR Code
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Info Card -->
            <div class="card">
                <div class="card-header">
                    <h3><i class="fas fa-history me-2"></i>Informasi Sistem</h3>
                </div>
                <div class="card-body">
                    <div class="info-list">
                        <div class="info-item">
                            <i class="fas fa-calendar-plus text-primary"></i>
                            <div>
                                <strong>Dibuat</strong>
                                <div class="text-muted small">{{ $room->created_at->format('d M Y H:i') }}</div>
                            </div>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-calendar-check text-success"></i>
                            <div>
                                <strong>Diupdate</strong>
                                <div class="text-muted small">{{ $room->updated_at->format('d M Y H:i') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.detail-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
}

.detail-item {
    display: flex;
    flex-direction: column;
}

.detail-item.full-width {
    grid-column: 1 / -1;
}

.detail-label {
    font-weight: 600;
    color: #64748b;
    font-size: 0.875rem;
    margin-bottom: 0.25rem;
}

.detail-value {
    color: #1e293b;
    font-size: 1rem;
}

.status-badge {
    padding: 0.4rem 0.8rem;
    border-radius: 8px;
    font-size: 0.8rem;
    font-weight: 600;
    display: inline-block;
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

.facilities-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.facility-tag {
    background: #e2e8f0;
    color: #475569;
    padding: 0.4rem 0.8rem;
    border-radius: 8px;
    font-size: 0.8rem;
    font-weight: 500;
}

.info-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.info-item {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.info-item i {
    font-size: 1.2rem;
    width: 24px;
}

.qr-image {
    border: 1px solid #e2e8f0;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.empty-state {
    text-align: center;
}

.empty-icon {
    color: #cbd5e1;
}

@media (max-width: 768px) {
    .detail-grid {
        grid-template-columns: 1fr;
    }
    
    .content-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endsection