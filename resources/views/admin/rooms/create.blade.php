@extends('layouts.app')

@section('title', 'Tambah Ruangan - Dasher')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/admin.css') }}" />


@section('content')
<div class="content-wrapper">
    <!-- Header -->
    <div class="admin-header">
        <div class="header-content">
            <div class="header-text">
                <h1><i class="fas fa-plus-circle me-2"></i>Tambah Ruangan Baru</h1>
                <p class="welcome-text">Buat ruangan baru untuk sistem booking</p>
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

    <!-- Form Tambah -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-door-open me-2"></i>Form Tambah Ruangan</h3>
            <span class="badge bg-success">Baru</span>
        </div>
        <div class="card-body">
            <form action="{{ route('rooms.store') }}" method="POST">
                @csrf
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Nama Ruangan <span class="text-danger">*</span></label>
                            <input type="text" name="room_name" class="form-control" 
                                   value="{{ old('room_name') }}" required 
                                   placeholder="Contoh: AE 101, Lab Animasi">
                            @error('room_name')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Tipe Ruangan <span class="text-danger">*</span></label>
                            <select name="room_type" class="form-select" required>
                                <option value="">Pilih Tipe</option>
                                <option value="kelas" {{ old('room_type') == 'kelas' ? 'selected' : '' }}>Ruang Kelas</option>
                                <option value="lab" {{ old('room_type') == 'lab' ? 'selected' : '' }}>Laboratorium</option>
                                <option value="other" {{ old('room_type') == 'other' ? 'selected' : '' }}>Ruangan Lainnya</option>
                            </select>
                            @error('room_type')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Lantai <span class="text-danger">*</span></label>
                            <input type="text" name="floor" class="form-control" 
                                   value="{{ old('floor') }}" required 
                                   placeholder="Contoh: 1, 2, 3">
                            @error('floor')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Kapasitas</label>
                            <input type="number" name="capacity" class="form-control" 
                                   value="{{ old('capacity') }}" 
                                   placeholder="Contoh: 40" min="1">
                            @error('capacity')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select" required>
                                <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>Tersedia</option>
                                <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                <option value="occupied" {{ old('status') == 'occupied' ? 'selected' : '' }}>Terpakai</option>
                            </select>
                            @error('status')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Fasilitas</label>
                    <textarea name="facilities" class="form-control" rows="3" 
                              placeholder="Contoh: AC, Proyektor, Whiteboard, Komputer">{{ old('facilities') }}</textarea>
                    <small class="text-muted">Pisahkan dengan koma</small>
                    @error('facilities')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="description" class="form-control" rows="3" 
                              placeholder="Deskripsi tambahan tentang ruangan...">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('rooms.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-2"></i>Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Simpan Ruangan
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