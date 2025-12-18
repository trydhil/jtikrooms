@extends('layouts.app')

@section('title', 'Booking Ruangan - Dasher')

@section('content')
<div class="header">
    <h2><i class="fas fa-calendar-plus me-2"></i>Booking Ruangan</h2>
</div>

<div class="about-card">
    <div class="text-center mb-4">
        <i class="fas fa-qrcode fa-3x text-primary mb-3"></i>
        <h3>Booking Ruangan</h3>
        
        <!-- Room Lock Info -->
        <div class="alert alert-info">
            <i class="fas fa-lock me-2"></i>
            <strong>Ruangan:</strong> {{ $roomName }}
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            @foreach($errors->all() as $error)
                <p class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
        </div>
    @endif

    <form action="{{ route('booking.store') }}" method="POST" id="bookingForm">
        @csrf
        <input type="hidden" name="room_name" value="{{ $roomName }}" id="roomInput">
        
        <div class="mb-3">
            <label class="form-label">Ruangan</label>
            <input type="text" class="form-control" value="{{ $roomName }}" readonly style="background-color: #f8f9fa; font-weight: bold;">
        </div>

        <div class="mb-3">
            <label class="form-label">Mata Kuliah <span class="text-danger">*</span></label>
            <input type="text" name="mata_kuliah" class="form-control" placeholder="Contoh: Pemrograman Web" required value="{{ old('mata_kuliah') }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Dosen Pengajar <span class="text-danger">*</span></label>
            <input type="text" name="dosen" class="form-control" placeholder="Contoh: Dr. Ahmad, M.Kom" required value="{{ old('dosen') }}">
        </div>

        <!-- Waktu Mulai -->
        <div class="mb-3">
            <label class="form-label">Waktu Mulai <span class="text-danger">*</span></label>
            <input type="datetime-local" 
                   name="waktu_mulai" 
                   class="form-control" 
                   id="waktu_mulai"
                   min="{{ now()->timezone('Asia/Makassar')->format('Y-m-d\TH:i') }}"
                   required 
                   value="{{ old('waktu_mulai') }}">
            <small class="text-muted">
                <i class="fas fa-info-circle"></i> 
                Pilih kapan booking dimulai (tidak boleh waktu yang sudah lewat)
            </small>
        </div>

        <!-- Waktu Berakhir -->
        <div class="mb-3">
            <label class="form-label">Waktu Berakhir <span class="text-danger">*</span></label>
            <input type="datetime-local" 
                   name="waktu_berakhir" 
                   class="form-control" 
                   id="waktu_berakhir"
                   required 
                   value="{{ old('waktu_berakhir') }}">
            <small class="text-muted">
                <i class="fas fa-info-circle"></i> 
                Pilih kapan booking berakhir (minimal 30 menit setelah waktu mulai)
            </small>
        </div>

        <div class="mb-3">
            <label class="form-label">Keterangan (opsional)</label>
            <textarea name="keterangan" class="form-control" placeholder="Catatan tambahan...">{{ old('keterangan') }}</textarea>
        </div>

        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="fas fa-check me-2"></i>Konfirmasi Booking
            </button>
            <a href="{{ route('dashboard.kelas') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali ke Dashboard
            </a>
        </div>
    </form>
</div>

<style>
.about-card {
    background: white;
    border-radius: 15px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    max-width: 600px;
    margin: 0 auto;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const waktuMulaiInput = document.getElementById('waktu_mulai');
    const waktuBerakhirInput = document.getElementById('waktu_berakhir');

    // Set minimal waktu mulai (sekarang)
    const now = new Date();
    
    function formatDateTimeLocal(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        const hours = String(date.getHours()).padStart(2, '0');
        const minutes = String(date.getMinutes()).padStart(2, '0');
        
        return `${year}-${month}-${day}T${hours}:${minutes}`;
    }

    // Set default waktu mulai (30 menit dari sekarang)
    if (!waktuMulaiInput.value) {
        const defaultMulai = new Date(now.getTime() + 30 * 60000);
        waktuMulaiInput.value = formatDateTimeLocal(defaultMulai);
    }

    // Set default waktu berakhir (120 menit dari sekarang / 90 menit dari waktu mulai default)
    if (!waktuBerakhirInput.value) {
        const defaultBerakhir = new Date(now.getTime() + 120 * 60000);
        waktuBerakhirInput.value = formatDateTimeLocal(defaultBerakhir);
    }

    // Update waktu berakhir minimal ketika waktu mulai berubah
    waktuMulaiInput.addEventListener('change', function() {
        const waktuMulai = new Date(this.value);
        const minBerakhir = new Date(waktuMulai.getTime() + 30 * 60000);
        
        waktuBerakhirInput.min = formatDateTimeLocal(minBerakhir);
        
        // Jika waktu berakhir sekarang kurang dari waktu mulai + 30 menit, update otomatis
        const waktuBerakhirSekarang = new Date(waktuBerakhirInput.value);
        if (waktuBerakhirSekarang < minBerakhir) {
            waktuBerakhirInput.value = formatDateTimeLocal(minBerakhir);
        }
    });

    // Validasi waktu berakhir harus setelah waktu mulai + 30 menit
    waktuBerakhirInput.addEventListener('change', function() {
        const waktuMulai = new Date(waktuMulaiInput.value);
        const waktuBerakhir = new Date(this.value);
        const minBerakhir = new Date(waktuMulai.getTime() + 30 * 60000);
        
        if (waktuBerakhir < minBerakhir) {
            alert('Waktu berakhir harus minimal 30 menit setelah waktu mulai!');
            this.value = formatDateTimeLocal(minBerakhir);
        }
    });

    // Trigger change event untuk set initial min value
    waktuMulaiInput.dispatchEvent(new Event('change'));
});
</script>
@endsection