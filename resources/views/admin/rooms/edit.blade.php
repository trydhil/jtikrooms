@extends('layouts.app')

@section('title', 'Edit Ruangan - Dasher')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/admin.css') }}" />
<style>
.form-control, .form-select { 
    border-radius: 8px; 
    border: 1px solid #dee2e6; 
    padding: 0.75rem 1rem; 
}
.form-control:focus, .form-select:focus { 
    border-color: #3b82f6; 
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1); 
}
.form-label { 
    font-weight: 600; 
    color: #374151; 
    font-size: 0.95rem; 
    margin-bottom: 0.5rem; 
}
.facilities-grid { 
    display: grid; 
    grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); 
    gap: 0.75rem; 
    max-height: 400px;
    overflow-y: auto;
    padding: 5px;
}
.facility-card { 
    display: flex; 
    align-items: center; 
    padding: 0.6rem 0.8rem; 
    border: 1px solid #e2e8f0; 
    border-radius: 8px; 
    background-color: #fff; 
    color: #64748b; 
    cursor: pointer; 
    transition: all 0.2s ease; 
    width: 100%; 
    user-select: none; 
}
.facility-card:hover { 
    border-color: #cbd5e1; 
    background-color: #f8fafc; 
}
.btn-check:checked + .facility-card { 
    background-color: #eff6ff; 
    border-color: #3b82f6; 
    color: #1d4ed8; 
    font-weight: 500; 
}
.check-icon { 
    width: 18px; 
    height: 18px; 
    border-radius: 4px; 
    border: 2px solid #cbd5e1; 
    margin-right: 10px; 
    display: flex; 
    align-items: center; 
    justify-content: center; 
    font-size: 10px; 
    color: transparent; 
    transition: all 0.2s; 
}
.btn-check:checked + .facility-card .check-icon { 
    background-color: #3b82f6; 
    border-color: #3b82f6; 
    color: white; 
}
.facility-name { 
    font-size: 0.9rem; 
}
</style>
 
@section('content')
<div class="content-wrapper">
    <div class="admin-header">
        <div class="header-content">
            <div class="header-text">
                <h1><i class="fas fa-edit me-2"></i>Edit Ruangan</h1>
                <p class="welcome-text">Update informasi {{ $room->name }}</p>
            </div>
            <div class="header-actions">
                <a href="{{ route('rooms.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold text-primary"><i class="fas fa-door-open me-2"></i>Form Edit Data</h5>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('rooms.update', $room->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row g-4">
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label class="form-label">Kode Ruangan <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" 
                                   value="{{ old('name', $room->name) }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nama Tampilan <span class="text-danger">*</span></label>
                            <input type="text" name="display_name" class="form-control" 
                                   value="{{ old('display_name', $room->display_name) }}" required>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Kapasitas <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" name="capacity" class="form-control" 
                                               value="{{ old('capacity', $room->capacity) }}" min="1" required>
                                        <span class="input-group-text">Orang</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Luas Ruangan <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" name="luas" class="form-control" step="0.01"
                                               value="{{ old('luas', $room->luas) }}" min="5" max="500" required>
                                        <span class="input-group-text">m²</span>
                                    </div>
                                    <small class="text-muted">Min. 5m², Max. 500m²</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Lantai</label>
                                    <select name="lantai" class="form-select">
                                        <option value="">Pilih Lantai</option>
                                        <option value="1" {{ old('lantai', $room->lantai) == '1' ? 'selected' : '' }}>Lantai 1</option>
                                        <option value="2" {{ old('lantai', $room->lantai) == '2' ? 'selected' : '' }}>Lantai 2</option>
                                        <option value="3" {{ old('lantai', $room->lantai) == '3' ? 'selected' : '' }}>Lantai 3</option>
                                        <option value="4" {{ old('lantai', $room->lantai) == '4' ? 'selected' : '' }}>Lantai 4</option>
                                        <option value="5" {{ old('lantai', $room->lantai) == '5' ? 'selected' : '' }}>Lantai 5</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Lokasi</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="fas fa-map-marker-alt text-danger"></i></span>
                                        <input type="text" name="location" class="form-control" 
                                               value="{{ old('location', $room->location) }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Tipe Ruangan <span class="text-danger">*</span></label>
                                    <select name="type" class="form-select" required>
                                        <option value="">Pilih Tipe</option>
                                        <option value="kelas" {{ old('type', $room->type) == 'kelas' ? 'selected' : '' }}>Kelas / Teori</option>
                                        <option value="lab" {{ old('type', $room->type) == 'lab' ? 'selected' : '' }}>Laboratorium</option>
                                        <option value="other" {{ old('type', $room->type) == 'other' ? 'selected' : '' }}>Ruangan Lainnya</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select" required>
                                <option value="available" {{ old('status', $room->status) == 'available' ? 'selected' : '' }}>Tersedia</option>
                                <option value="maintenance" {{ old('status', $room->status) == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                <option value="occupied" {{ old('status', $room->status) == 'occupied' ? 'selected' : '' }}>Terpakai</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="description" class="form-control" rows="3">{{ old('description', $room->description) }}</textarea>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="bg-light p-4 rounded-3 h-100 border">
                            <label class="form-label d-block fw-bold mb-3"><i class="fas fa-list-check me-2"></i>Fasilitas Ruangan</label>
                            
                            <div class="facilities-grid">
                                @php
                                    $defaultFacilities = [
                                        'AC' => 'AC / Pendingin',
                                        'Proyektor' => 'Proyektor LCD',
                                        'Whiteboard' => 'Papan Tulis',
                                        'WiFi' => 'Koneksi WiFi',
                                        'Komputer' => 'Komputer PC',
                                        'Sound System' => 'Sound System',
                                        'Kursi Ergonomis' => 'Kursi Ergonomis',
                                        'Meja Rapat' => 'Meja Rapat',
                                        'Smart TV' => 'Smart TV / Monitor',
                                        'CCTV' => 'Kamera CCTV',
                                        'Dispenser' => 'Dispenser Air',
                                        'Stop Kontak' => 'Stop Kontak',
                                        'LED Projector' => 'LED Projector',
                                        'Sistem Audio' => 'Sistem Audio',
                                        'Kursi Lipat' => 'Kursi Lipat',
                                        'AC Sentral' => 'AC Sentral'
                                    ];
                                    
                                    $currentFacilities = $room->facilities ?? [];
                                    $customFacilities = array_diff($currentFacilities, array_keys($defaultFacilities));
                                    $customString = implode(', ', $customFacilities);
                                @endphp

                                @foreach($defaultFacilities as $val => $label)
                                    <div class="facility-option">
                                        <input type="checkbox" 
                                               class="btn-check" 
                                               name="facilities[]" 
                                               id="fac_{{ Str::slug($val) }}" 
                                               value="{{ $val }}"
                                               {{ in_array($val, $currentFacilities) ? 'checked' : '' }}>
                                        
                                        <label class="facility-card" for="fac_{{ Str::slug($val) }}">
                                            <div class="check-icon"><i class="fas fa-check"></i></div>
                                            <span class="facility-name">{{ $label }}</span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-4">
                                <label class="form-label small text-muted">Fasilitas Lainnya (Ketik manual)</label>
                                <input type="text" name="custom_facilities" class="form-control" 
                                       placeholder="Contoh: Webcam, Green Screen, Papan Tulis Elektronik"
                                       value="{{ old('custom_facilities', $customString) }}">
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('rooms.index') }}" class="btn btn-light border">Batal</a>
                    <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save me-2"></i>Update Ruangan</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 border-bottom">
            <h5 class="mb-0 fw-bold text-dark"><i class="fas fa-qrcode me-2"></i>Pengaturan QR Code</h5>
        </div>
        <div class="card-body p-4">
            <div class="d-flex align-items-center gap-4 flex-wrap">
                
                <div class="text-center bg-light p-3 border rounded" style="min-width: 120px;">
                    @if($room->qr_code && file_exists(public_path($room->qr_code)))
                        <img src="{{ asset($room->qr_code) }}" alt="QR" width="100" class="d-block mb-2 bg-white p-1 border rounded">
                        <span class="badge bg-success w-100">Aktif</span>
                    @else
                        <div class="d-flex align-items-center justify-content-center bg-white border rounded" style="width:100px; height:100px;">
                            <i class="fas fa-qrcode text-muted fa-3x opacity-25"></i>
                        </div>
                        <span class="badge bg-secondary w-100 mt-2">Non-Aktif</span>
                    @endif
                </div>

                <div class="flex-grow-1">
                    @if($room->qr_code && file_exists(public_path($room->qr_code)))
                        <h6 class="fw-bold text-success mb-1">QR Code Tersedia</h6>
                        <p class="text-muted small mb-3">QR code ini siap digunakan. Anda bisa mendownloadnya atau membuat ulang jika link berubah.</p>
                        
                        <div class="d-flex gap-2 flex-wrap">
                            <a href="{{ asset($room->qr_code) }}" download class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-download me-2"></i>Download Gambar
                            </a>
                            
                            <a href="{{ route('rooms.print', $room->id) }}" class="btn btn-outline-danger btn-sm" target="_blank">
                                <i class="fas fa-file-pdf me-2"></i>Cetak Label PDF
                            </a>

                            <button type="button" onclick="if(confirm('Yakin generate ulang? File lama akan tertimpa.')){document.getElementById('form-generate-qr').submit()}" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-sync-alt me-2"></i>Generate Ulang
                            </button>
                        </div>
                    @else
                        <h6 class="fw-bold text-warning mb-1">QR Code Belum Dibuat</h6>
                        <p class="text-muted small mb-3">Klik tombol di bawah untuk membuat QR Code secara otomatis.</p>
                        
                        <button type="button" onclick="document.getElementById('form-generate-qr').submit()" class="btn btn-primary btn-sm">
                            <i class="fas fa-magic me-2"></i>Buat QR Code Sekarang
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <form id="form-generate-qr" action="{{ route('rooms.generate-qr', $room->id) }}" method="POST" style="display: none;">
        @csrf
    </form>

</div>
@endsection