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

        <div class="mb-3">
            <label class="form-label">Waktu Berakhir <span class="text-danger">*</span></label>
            <input type="datetime-local" name="waktu_berakhir" class="form-control" required value="{{ old('waktu_berakhir') }}">
            <small class="text-muted">Pilih kapan booking berakhir (minimal 30 menit dari sekarang, maksimal 5 jam)</small>
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
    // Set default value to current time + 1 hour (untuk user experience)
    const now = new Date();
    const defaultTime = new Date(now.getTime() + 60 * 60000); // 1 hour from now
    
    const waktuInput = document.querySelector('input[name="waktu_berakhir"]');
    
    // Set default value if no old value
    if (!waktuInput.value) {
        waktuInput.value = formatDateTimeLocal(defaultTime);
    }
    
    function formatDateTimeLocal(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        const hours = String(date.getHours()).padStart(2, '0');
        const minutes = String(date.getMinutes()).padStart(2, '0');
        
        return `${year}-${month}-${day}T${hours}:${minutes}`;
    }
});
</script>
@endsection