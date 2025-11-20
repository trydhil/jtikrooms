@extends('layouts.app')

@section('title', 'Edit Perwakilan Kelas - Dasher')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/admin.css') }}" />
 

@section('content')
<div class="content-wrapper">
    <!-- Header -->
    <div class="admin-header">
        <div class="header-content">
            <div class="header-text">
                <h1><i class="fas fa-user-edit me-2"></i>Edit Perwakilan Kelas</h1>
                <p class="welcome-text">Update data perwakilan kelas {{ $user->username }}</p>
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

    <!-- Form Edit -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-user-cog me-2"></i>Form Edit Perwakilan Kelas</h3>
            <span class="badge bg-primary">{{ $user->username }}</span>
        </div>
        <div class="card-body">
            <form action="{{ route('users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Program Studi <span class="text-danger">*</span></label>
                            <select name="prodi" class="form-select @error('prodi') is-invalid @enderror" required>
                                <option value="">Pilih Program Studi</option>
                                <option value="TEKOM" {{ old('prodi', $user->prodi) == 'TEKOM' ? 'selected' : '' }}>Teknik Komputer</option>
                                <option value="PTIK" {{ old('prodi', $user->prodi) == 'PTIK' ? 'selected' : '' }}>Pendidikan Teknik Informatika dan Komputer</option>
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
                                <option value="A" {{ old('kelas', $user->kelas) == 'A' ? 'selected' : '' }}>A</option>
                                <option value="B" {{ old('kelas', $user->kelas) == 'B' ? 'selected' : '' }}>B</option>
                                <option value="C" {{ old('kelas', $user->kelas) == 'C' ? 'selected' : '' }}>C</option>
                                <option value="D" {{ old('kelas', $user->kelas) == 'D' ? 'selected' : '' }}>D</option>
                                <option value="E" {{ old('kelas', $user->kelas) == 'E' ? 'selected' : '' }}>E</option>
                                <option value="F" {{ old('kelas', $user->kelas) == 'F' ? 'selected' : '' }}>F</option>
                                <option value="G" {{ old('kelas', $user->kelas) == 'G' ? 'selected' : '' }}>G</option>
                                <option value="H" {{ old('kelas', $user->kelas) == 'H' ? 'selected' : '' }}>H</option>
                                <option value="I" {{ old('kelas', $user->kelas) == 'I' ? 'selected' : '' }}>I</option>
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
                                   value="{{ old('angkatan', $user->angkatan) }}" required maxlength="4"
                                   placeholder="Contoh: 2023">
                            @error('angkatan')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Password Baru (Opsional)</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" 
                           placeholder="Kosongkan jika tidak ingin mengubah">
                    <small class="text-muted">Minimal 6 karakter. Kosongkan jika tidak ingin mengubah password.</small>
                    @error('password')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Username akan digenerate otomatis:</strong><br>
                    • <span id="usernamePreview" class="fw-bold">{{ $user->username }}</span>
                </div>

                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Perhatian:</strong> Mengubah program studi/kelas/angkatan akan mengubah username dan dapat mempengaruhi data booking yang terkait.
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-2"></i>Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Update Perwakilan Kelas
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
            usernamePreview.textContent = '{{ $user->username }}';
        }
    }
    
    prodiSelect.addEventListener('change', updateUsernamePreview);
    kelasSelect.addEventListener('change', updateUsernamePreview);
    angkatanInput.addEventListener('input', updateUsernamePreview);
});
</script>
@endsection