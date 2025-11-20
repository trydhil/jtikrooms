@extends('layouts.app')

@section('title', 'Tambah Perwakilan Kelas - Dasher')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/admin.css') }}" />
 

@section('content')
<div class="content-wrapper">
    <!-- Header -->
    <div class="admin-header">
        <div class="header-content">
            <div class="header-text">
                <h1><i class="fas fa-user-plus me-2"></i>Tambah Perwakilan Kelas Baru</h1>
                <p class="welcome-text">Buat akun perwakilan kelas baru untuk sistem booking</p>
            </div>
            <div class="header-actions">
                <a href="{{ route('users.index') }}" class="btn btn-secondary">
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
            <h3><i class="fas fa-user-plus me-2"></i>Form Tambah Perwakilan Kelas</h3>
            <span class="badge bg-success">Baru</span>
        </div>
        <div class="card-body">
            <form action="{{ route('users.store') }}" method="POST">
                @csrf
                
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Program Studi <span class="text-danger">*</span></label>
                            <select name="prodi" class="form-select @error('prodi') is-invalid @enderror" required>
                                <option value="">Pilih Program Studi</option>
                                <option value="TEKOM" {{ old('prodi') == 'TEKOM' ? 'selected' : '' }}>Teknik Komputer</option>
                                <option value="PTIK" {{ old('prodi') == 'PTIK' ? 'selected' : '' }}>Pendidikan Teknik Informatika</option>
                            </select>
                            @error('prodi')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Kelas <span class="text-danger">*</span></label>
                            <select name="kelas" class="form-select @error('kelas') is-invalid @enderror" required>
                                <option value="">Pilih Kelas</option>
                                <option value="A" {{ old('kelas') == 'A' ? 'selected' : '' }}>A</option>
                                <option value="B" {{ old('kelas') == 'B' ? 'selected' : '' }}>B</option>
                                <option value="C" {{ old('kelas') == 'C' ? 'selected' : '' }}>C</option>
                                <option value="D" {{ old('kelas') == 'D' ? 'selected' : '' }}>D</option>
                                <option value="E" {{ old('kelas') == 'E' ? 'selected' : '' }}>E</option>
                                <option value="F" {{ old('kelas') == 'F' ? 'selected' : '' }}>F</option>
                                <option value="G" {{ old('kelas') == 'G' ? 'selected' : '' }}>G</option>
                                <option value="H" {{ old('kelas') == 'H' ? 'selected' : '' }}>H</option>
                                <option value="I" {{ old('kelas') == 'I' ? 'selected' : '' }}>I</option>
                            </select>
                            @error('kelas')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Angkatan <span class="text-danger">*</span></label>
                            <input type="text" name="angkatan" class="form-control @error('angkatan') is-invalid @enderror" 
                                   value="{{ old('angkatan') }}" required maxlength="4"
                                   placeholder="Contoh: 2023, 2024">
                            @error('angkatan')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Password <span class="text-danger">*</span></label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required 
                           placeholder="Minimal 6 karakter">
                    <small class="text-muted">Password akan dienkripsi menggunakan MD5</small>
                    @error('password')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Username akan digenerate otomatis:</strong><br>
                    • <span id="usernamePreview" class="fw-bold">tekomc24, ptika23, dll</span>
                </div>

                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Perhatian:</strong> Pastikan data program studi, kelas, dan angkatan sudah benar sebelum disimpan.
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-2"></i>Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Simpan Perwakilan Kelas
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

.alert {
    border-radius: 10px;
    border: none;
}
</style>

<script>
// Live preview username
document.addEventListener('DOMContentLoaded', function() {
    const prodiSelect = document.querySelector('select[name="prodi"]');
    const kelasSelect = document.querySelector('select[name="kelas"]');
    const angkatanInput = document.querySelector('input[name="angkatan"]');
    const usernamePreview = document.getElementById('usernamePreview');
    
    function updateUsernamePreview() {
        const prodi = prodiSelect.value;
        const kelas = kelasSelect.value;
        const angkatan = angkatanInput.value;
        
        if (prodi && kelas && angkatan) {
            // Format: tekoma24, ptikb23 (prodi + kelas + 2digit terakhir angkatan)
            const formattedProdi = prodi.toLowerCase();
            const formattedKelas = kelas.toLowerCase();
            const tahun = angkatan.slice(-2); // Ambil 2 digit terakhir
            
            usernamePreview.textContent = formattedProdi + formattedKelas + tahun;
        } else {
            usernamePreview.textContent = 'tekomc24, ptika23, dll';
        }
    }
    
    prodiSelect.addEventListener('change', updateUsernamePreview);
    kelasSelect.addEventListener('change', updateUsernamePreview);
    angkatanInput.addEventListener('input', updateUsernamePreview);
});
</script>
@endsection