@extends('layouts.app')

@section('title', 'Tambah Ruangan - Dasher')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/admin.css') }}" />
 
@section('content')
<div class="content-wrapper">
    <div class="admin-header">
        <div class="header-content">
            <div class="header-text">
                <h1><i class="fas fa-plus-circle me-2"></i>Tambah Ruangan Baru</h1>
                <p class="welcome-text">Buat ruangan baru & generate QR Code otomatis</p>
            </div>
            <div class="header-actions">
                <a href="{{ route('rooms.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold text-primary"><i class="fas fa-door-open me-2"></i>Form Detail Ruangan</h5>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('rooms.store') }}" method="POST">
                @csrf
                
                <div class="row g-4">
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label class="form-label">Kode Ruangan <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" 
                                   value="{{ old('name') }}" required 
                                   placeholder="Contoh: AE101, LAB_ANIMASI">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nama Tampilan <span class="text-danger">*</span></label>
                            <input type="text" name="display_name" class="form-control" 
                                   value="{{ old('display_name') }}" required 
                                   placeholder="Contoh: Ruang Teori AE 101">
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Kapasitas <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" name="capacity" class="form-control" 
                                               value="{{ old('capacity') }}" min="1" required placeholder="40">
                                        <span class="input-group-text">Orang</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Luas Ruangan <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" name="luas" class="form-control" step="0.01"
                                               value="{{ old('luas') }}" min="5" max="500" required placeholder="48.00">
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
                                        <option value="1" {{ old('lantai') == '1' ? 'selected' : '' }}>Lantai 1</option>
                                        <option value="2" {{ old('lantai') == '2' ? 'selected' : '' }}>Lantai 2</option>
                                        <option value="3" {{ old('lantai') == '3' ? 'selected' : '' }}>Lantai 3</option>
                                        <option value="4" {{ old('lantai') == '4' ? 'selected' : '' }}>Lantai 4</option>
                                        <option value="5" {{ old('lantai') == '5' ? 'selected' : '' }}>Lantai 5</option>
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
                                               value="{{ old('location') }}" 
                                               placeholder="Contoh: Gedung JTIK - Lantai 1">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Tipe Ruangan <span class="text-danger">*</span></label>
                                    <select name="type" class="form-select" required>
                                        <option value="">Pilih Tipe</option>
                                        <option value="kelas" {{ old('type') == 'kelas' ? 'selected' : '' }}>Kelas / Teori</option>
                                        <option value="lab" {{ old('type') == 'lab' ? 'selected' : '' }}>Laboratorium</option>
                                        <option value="other" {{ old('type') == 'other' ? 'selected' : '' }}>Ruangan Lainnya</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select" required> 
                                <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>Tersedia</option>
                                <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                <option value="occupied" {{ old('status') == 'occupied' ? 'selected' : '' }}>Terpakai</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="description" class="form-control" rows="3" 
                                      placeholder="Deskripsi singkat tentang ruangan...">{{ old('description') }}</textarea>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="bg-light p-4 rounded-3 h-100">
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
                                @endphp

                                @foreach($defaultFacilities as $val => $label)
                                    <div class="facility-option">
                                        <input type="checkbox" 
                                               class="btn-check" 
                                               name="facilities[]" 
                                               id="fac_{{ Str::slug($val) }}" 
                                               value="{{ $val }}"
                                               {{ (is_array(old('facilities')) && in_array($val, old('facilities'))) ? 'checked' : '' }}>
                                        
                                        <label class="facility-card" for="fac_{{ Str::slug($val) }}">
                                            <div class="check-icon"><i class="fas fa-check"></i></div>
                                            <span class="facility-name">{{ $label }}</span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-4">
                                <label class="form-label small text-muted">Fasilitas Lainnya (Ketik manual, pisahkan koma)</label>
                                <input type="text" name="custom_facilities" class="form-control" 
                                       placeholder="Contoh: Webcam, Green Screen, Papan Tulis Elektronik"
                                       value="{{ old('custom_facilities') }}">
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <div class="alert alert-info d-flex align-items-center mt-3" role="alert">
                    <i class="fas fa-qrcode fa-2x me-3"></i>
                    <div>
                        <strong>Info QR Code</strong>
                        <div class="small">QR Code unik akan <b>dibuat secara otomatis</b> oleh sistem setelah Anda menekan tombol Simpan.</div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('rooms.index') }}" class="btn btn-light border">Batal</a>
                    <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save me-2"></i>Simpan Ruangan</button>
                </div>
            </form>
        </div>
    </div>
</div>

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
@endsection