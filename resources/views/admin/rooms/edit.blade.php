@extends('layouts.app')

@section('title', 'Edit Ruangan - Dasher')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/admin.css') }}" />


@section('content')
<div class="content-wrapper">
    <!-- Header -->
    <div class="admin-header">
        <div class="header-content">
            <div class="header-text">
                <h1><i class="fas fa-edit me-2"></i>Edit Ruangan</h1>
                <p class="welcome-text">Update informasi ruangan {{ $room->name }}</p>
            </div>
            <div class="header-actions">
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

    <!-- Form Edit -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-door-open me-2"></i>Form Edit Ruangan</h3>
            <span class="badge bg-primary">{{ $room->name }}</span>
        </div>
        <div class="card-body">
            <form action="{{ route('rooms.update', $room->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Nama Ruangan <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" 
                                   value="{{ old('name', $room->name) }}" required>
                            @error('name')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Display Name</label>
                            <input type="text" name="display_name" class="form-control" 
                                   value="{{ old('display_name', $room->display_name) }}"
                                   placeholder="Nama yang ditampilkan">
                            @error('display_name')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Lokasi</label>
                            <input type="text" name="location" class="form-control" 
                                   value="{{ old('location', $room->location) }}"
                                   placeholder="Contoh: Gedung JTIK Lantai 2">
                            @error('location')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Kapasitas</label>
                            <input type="number" name="capacity" class="form-control" 
                                   value="{{ old('capacity', $room->capacity) }}" 
                                   min="1" placeholder="Jumlah orang">
                            @error('capacity')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select" required>
                                <option value="available" {{ old('status', $room->status) == 'available' ? 'selected' : '' }}>Tersedia</option>
                                <option value="maintenance" {{ old('status', $room->status) == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                <option value="occupied" {{ old('status', $room->status) == 'occupied' ? 'selected' : '' }}>Terpakai</option>
                            </select>
                            @error('status')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Fasilitas (pisahkan dengan koma)</label>
                    <textarea name="facilities" class="form-control" rows="3" 
                              placeholder="AC, Proyektor, WiFi, Whiteboard">{{ is_array($room->facilities) ? implode(', ', $room->facilities) : $room->facilities }}</textarea>
                    <small class="text-muted">Contoh: AC, Proyektor, WiFi, Whiteboard</small>
                    @error('facilities')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="description" class="form-control" rows="3" 
                              placeholder="Deskripsi tambahan tentang ruangan">{{ old('description', $room->description) }}</textarea>
                    @error('description')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('rooms.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-2"></i>Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Update Ruangan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.form-control, .form-select {
    border-radius: 8px;
    border: 1px solid #e2e8f0;
    padding: 0.75rem 1rem;
}

.form-control:focus, .form-select:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.form-label {
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.5rem;
}
</style>
@endsection