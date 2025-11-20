@extends('layouts.app')

@section('title', ($room->display_name ?? $room->name) . ' - Dasher')

@section('content')
<div class="header">
    <h2><i class="fas fa-door-open me-2"></i>Informasi Ruangan</h2>
    <div class="user-info">
        <div class="user-avatar-small">
            <i class="fas fa-user"></i>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header bg-white">
                <h3 class="card-title mb-0 text-primary">
                    <i class="fas fa-info-circle me-2"></i>{{ $room->display_name ?? $room->name }}
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="room-image-container mb-4">
                            <img src="/img/ruangan.jpg" 
                                 alt="{{ $room->name }}" 
                                 class="room-image-large rounded"
                                 onerror="this.src='https://via.placeholder.com/600x400/3B82F6/FFFFFF?text=Ruangan+JTIK'">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="room-details">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%"><i class="fas fa-tag me-2"></i>Kode</th>
                                    <td>{{ $room->name }}</td>
                                </tr>
                                <tr>
                                    <th><i class="fas fa-map-marker-alt me-2"></i>Lokasi</th>
                                    <td>{{ $room->location ?? 'Gedung JTIK' }}</td>
                                </tr>
                                <tr>
                                    <th><i class="fas fa-building me-2"></i>Lantai</th>
                                    <td>
                                        @if($room->lantai)
                                            Lantai {{ $room->lantai }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th><i class="fas fa-users me-2"></i>Tipe</th>
                                    <td>
                                        @if($room->type === 'kelas')
                                            <span class="badge bg-primary">Ruangan Kelas</span>
                                        @elseif($room->type === 'lab') 
                                            <span class="badge bg-success">Laboratorium</span>
                                        @else
                                            <span class="badge bg-info">Ruangan Khusus</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th><i class="fas fa-check-circle me-2"></i>Status</th>
                                    <td>
                                        @if($room->status === 'available')
                                            <span class="badge bg-success">Tersedia</span>
                                        @elseif($room->status === 'occupied')
                                            <span class="badge bg-warning">Terpakai</span>
                                        @elseif($room->status === 'maintenance')
                                            <span class="badge bg-danger">Maintenance</span>
                                        @else
                                            <span class="badge bg-secondary">Tidak Diketahui</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                @if($room->description)
                <div class="mt-4">
                    <h5><i class="fas fa-file-alt me-2"></i>Deskripsi</h5>
                    <p class="text-muted">{{ $room->description }}</p>
                </div>
                @endif

                @if($room->capacity)
                <div class="mt-3">
                    <h5><i class="fas fa-users me-2"></i>Kapasitas</h5>
                    <p class="text-muted">{{ $room->capacity }} orang</p>
                </div>
                @endif

                @if($room->facilities && is_array($room->facilities) && count($room->facilities) > 0)
                <div class="mt-4">
                    <h5><i class="fas fa-list me-2"></i>Fasilitas</h5>
                    <div class="facilities-grid">
                        @foreach($room->facilities as $facility)
                            @if(!empty(trim($facility)))
                                <div class="facility-item">
                                    <i class="fas fa-check text-success me-2"></i>{{ $facility }}
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
.room-image-large {
    width: 100%;
    height: 300px;
    object-fit: cover;
    border-radius: 10px;
}

.facilities-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 10px;
    margin-top: 10px;
}

.facility-item {
    background: #f8f9fa;
    padding: 10px 15px;
    border-radius: 8px;
    border-left: 4px solid #28a745;
}

.table-borderless th {
    font-weight: 600;
    color: #495057;
    width: 40%;
}

.card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.card-header {
    border-radius: 15px 15px 0 0 !important;
    padding: 1.25rem;
}
</style>
@endsection