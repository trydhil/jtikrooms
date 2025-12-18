@extends('layouts.app')

@section('title', ($room->display_name ?? $room->name) . ' - Detail Ruangan')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/roominfo.css') }}">
 
@section('content')

@php
    use Illuminate\Support\Facades\DB;
    
    // Normalize facilities into array
    $facilities = $room->facilities ?? [];
    if (is_string($facilities)) {
        $decoded = json_decode($facilities, true);
        $facilities = is_array($decoded) ? $decoded : [];
    } elseif (is_null($facilities)) {
        $facilities = [];
    }

    // ✅ QUERY SQL LANGSUNG untuk cek status real-time
    $roomStatus = DB::select("
        SELECT 
            r.*,
            CASE 
                WHEN EXISTS (
                    SELECT 1 
                    FROM bookings b 
                    WHERE b.room_name = r.name 
                    AND b.status = 'active'
                    AND b.waktu_mulai <= NOW()
                    AND b.waktu_berakhir > NOW()
                ) THEN 'occupied'
                ELSE r.status
            END as real_time_status
        FROM rooms r
        WHERE r.name = ?
    ", [$room->name])[0] ?? $room;

    $isOccupied = $roomStatus->real_time_status == 'occupied';
    
    // ✅ AMBIL BOOKING AKTIF JIKA ADA
    $activeBooking = $isOccupied ? \App\Models\Booking::where('room_name', $room->name)
        ->where('status', 'active')
        ->where('waktu_mulai', '<=', now()->timezone('Asia/Makassar'))
        ->where('waktu_berakhir', '>', now()->timezone('Asia/Makassar'))
        ->first() : null;

    // Today's queue count
    $todayQueues = \App\Models\Queue::where('room_id', $room->id)
        ->whereDate('created_at', \Carbon\Carbon::today('Asia/Makassar'))
        ->count();
@endphp

<!-- ✅ DEBUG INFO -->
<!-- Real Time Status: {{ $roomStatus->real_time_status }}, Is Occupied: {{ $isOccupied ? 'YES' : 'NO' }}, Active Booking: {{ $activeBooking ? 'YES' : 'NO' }} -->

<div class="detail-container">
    
    <!-- HEADER: TOMBOL KEMBALI SAJA -->
    <div class="top-nav">
        <a href="{{ url('/') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
        </a>
    </div>

    <!-- HERO SECTION -->
    <div class="room-hero">
        <!-- Foto Ruangan -->
        <div class="photo-frame">
            <img src="/img/ruangan.jpg" 
                 alt="Foto Ruangan" 
                 class="room-main-photo"
                 onerror="this.src='https://via.placeholder.com/1200x500/e2e8f0/64748b?text=TIDAK+ADA+GAMBAR'">
            
            <!-- Status Badge - PAKAI STATUS REAL-TIME -->
            @if($isOccupied)
                <div class="photo-badge bg-occupied">
                    <i class="fas fa-user-clock"></i> SEDANG DIGUNAKAN
                </div>
            @elseif($room->status == 'maintenance')
                <div class="photo-badge bg-maintenance">
                    <i class="fas fa-tools"></i> MAINTENANCE
                </div>
            @else
                <div class="photo-badge bg-available">
                    <i class="fas fa-check-circle"></i> TERSEDIA
                </div>
            @endif
        </div>

        <!-- Quick Info Sidebar -->
        <div class="quick-info">
            <div class="info-item">
                <div class="info-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="info-content">
                    <h4>Kapasitas</h4>
                    <p>{{ $room->capacity ?? '40' }} Orang</p>
                </div>
            </div>
            <div class="info-item">
                <div class="info-icon">
                    <i class="fas fa-layer-group"></i>
                </div>
                <div class="info-content">
                    <h4>Lokasi</h4>
                    <p>Lantai {{ $room->lantai ?? '1' }}</p>
                </div>
            </div>
            <div class="info-item">
                <div class="info-icon">
                    <i class="fas fa-cube"></i>
                </div>
                <div class="info-content">
                    <h4>Tipe Ruangan</h4>
                    <p>
                        @if($room->type === 'lab') Laboratorium
                        @elseif($room->type === 'kelas') Kelas
                        @else Ruangan Umum
                        @endif
                    </p>
                </div>
            </div>
            <div class="info-item">
                <div class="info-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="info-content">
                    <h4>Status</h4>
                    <p class="fw-bold 
                        @if($isOccupied) text-danger
                        @elseif($room->status == 'available') text-success
                        @else text-warning @endif">
                        @if($isOccupied) Digunakan
                        @elseif($room->status == 'available') Tersedia
                        @else Maintenance
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- JUDUL RUANGAN -->
    <div class="title-section">
        <h1 class="room-big-name">{{ $room->display_name ?? $room->name }}</h1>
        <div class="room-meta">
            <span class="meta-item">
                <i class="fas fa-hashtag"></i> Kode: {{ $room->name }}
            </span>
            <span class="meta-item">
                <i class="fas fa-map-marker-alt"></i> {{ $room->location ?? 'Gedung JTIK' }}
            </span>
            <span class="meta-item">
                <i class="fas fa-building"></i> {{ $room->lantai ?? 'Lantai 1' }}
            </span>
        </div>
    </div>

    <!-- FASILITAS UTAMA -->
    @if(count($facilities) > 0)
    <div class="main-facility">
        <h3><i class="fas fa-star me-2"></i>Fasilitas Utama</h3>
        <p>{{ $facilities[0] }}</p>
    </div>
    @endif

    <!-- INFO BOOKING / LIVE SESSION -->
    @if($activeBooking)
    @php
        $waktuBerakhir = \Carbon\Carbon::parse($activeBooking->waktu_berakhir)->timezone('Asia/Makassar');
        $sekarang = now()->timezone('Asia/Makassar');
        $diffMinutes = $waktuBerakhir->diffInMinutes($sekarang);
        $hours = floor($diffMinutes / 60);
        $minutes = $diffMinutes % 60;
    @endphp
    <div class="session-card">
        <div class="session-header">
            <span class="session-label">
                <i class="fas fa-broadcast-tower me-2"></i> KELAS BERLANGSUNG
            </span>
            <span class="countdown-timer">
    <i class="fas fa-clock me-2"></i> Sisa Waktu: 
    @php
        $waktuBerakhir = \Carbon\Carbon::parse($activeBooking->waktu_berakhir)
            ->timezone('Asia/Makassar');
        $sekarang = now()->timezone('Asia/Makassar');
        
        // ✅ PAKAI CARBON BUILT-IN FORMAT
        $sisaWaktu = $waktuBerakhir->diff($sekarang);
        $hours = $sisaWaktu->h;
        $minutes = $sisaWaktu->i;
    @endphp
    {{ $hours > 0 ? $hours.'j ' : '' }}{{ $minutes }}m 
        </div>
        
        <div class="session-details">
            <div class="sd-item">
                <h6>KELAS PENGGUNA</h6>
                <p>{{ $activeBooking->username }}</p>
            </div>
            <div class="sd-item">
                <h6>MATA KULIAH</h6>
                <p>{{ $activeBooking->mata_kuliah }}</p>
            </div>
            <div class="sd-item">
                <h6>DOSEN PENGAMPU</h6>
                <p>{{ $activeBooking->dosen }}</p>
            </div>
            <div class="sd-item">
                <h6>WAKTU BERAKHIR</h6>
                <p>{{ $waktuBerakhir->format('H:i') }} WITA</p>
            </div>
        </div>

        <!-- Info Antrian (Queue) -->
        <div class="queue-box">
    <div class="queue-info">
        <i class="fas fa-users fa-lg" style="color: #7f1d1d;"></i>
        <div>
            <span class="queue-count">
                @php
                    // Hitung booking aktif dari tabel booking
                    $activeBookingsCount = \App\Models\Booking::where('room_name', $room->name)
                        ->where('status', 'active')
                        ->where('waktu_berakhir', '>', now()->timezone('Asia/Makassar'))
                        ->count();
                @endphp
                {{ $activeBookingsCount }} Booking aktif
            </span>
        </div>
    </div>
    <a href="{{ route('dashboard.kelas') }}" class="btn-queue">
        <i class="fas fa-plus-circle me-1"></i> Ambil Antrian
    </a>
</div>
    </div>
    @endif

    <!-- SPESIFIKASI UTAMA -->
    <div class="specs-grid">
        <div class="spec-item">
            <i class="fas fa-users spec-icon"></i>
            <span class="spec-label">Kapasitas</span>
            <div class="spec-value">{{ $room->capacity ?? '40' }} Org</div>
        </div>
        <div class="spec-item">
            <i class="fas fa-layer-group spec-icon"></i>
            <span class="spec-label">Lokasi</span>
            <div class="spec-value">Lantai {{ $room->lantai ?? '1' }}</div>
        </div>
        <div class="spec-item">
            <i class="fas fa-cube spec-icon"></i>
            <span class="spec-label">Tipe</span>
            <div class="spec-value">
                @if($room->type === 'lab') Lab
                @elseif($room->type === 'kelas') Kelas
                @else Umum
                @endif
            </div>
        </div>
        <div class="spec-item">
            <i class="fas fa-expand spec-icon"></i>
            <span class="spec-label">Luas</span>
            <div class="spec-value">48 m²</div>
        </div>
    </div>

    <!-- DESKRIPSI & FASILITAS LENGKAP -->
    <div class="info-split">
        <!-- Kolom Kiri: Deskripsi -->
        <div class="desc-box">
            <div class="section-head">
                <i class="fas fa-file-alt"></i> Deskripsi Ruangan
            </div>
            
            <p class="desc-text" id="descText">
                {{ $room->description ?? 'Ruangan ini didesain untuk kenyamanan proses belajar mengajar. Memiliki pencahayaan alami yang cukup serta sistem pendingin udara yang terawat. Mohon untuk mematikan proyektor dan AC setelah selesai menggunakan ruangan ini.' }}
            </p>
            
            <a href="#" class="read-more-link" onclick="showFullDescription(event)">
                Baca Selengkapnya <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        
        <!-- Kolom Kanan: Fasilitas Lengkap -->
        <div>
            <div class="section-head">
                <i class="fas fa-list-check"></i> Fasilitas Lengkap
            </div>
            <ul class="facility-list">
                @if(count($facilities) > 0)
                    @foreach($facilities as $index => $fac)
                        @php
                            // Logika Deteksi Ikon Otomatis
                            $facLower = strtolower($fac);
                            $icon = 'fa-check-circle'; // Default

                            if (str_contains($facLower, 'wifi') || str_contains($facLower, 'internet')) {
                                $icon = 'fa-wifi';
                            } elseif (str_contains($facLower, 'ac') || str_contains($facLower, 'pendingin')) {
                                $icon = 'fa-snowflake';
                            } elseif (str_contains($facLower, 'proyektor') || str_contains($facLower, 'lcd') || str_contains($facLower, 'projector')) {
                                $icon = 'fa-video';
                            } elseif (str_contains($facLower, 'papan') || str_contains($facLower, 'board')) {
                                $icon = 'fa-chalkboard';
                            } elseif (str_contains($facLower, 'kursi') || str_contains($facLower, 'meja')) {
                                $icon = 'fa-chair';
                            } elseif (str_contains($facLower, 'komputer') || str_contains($facLower, 'pc') || str_contains($facLower, 'monitor')) {
                                $icon = 'fa-desktop';
                            } elseif (str_contains($facLower, 'tv') || str_contains($facLower, 'televisi')) {
                                $icon = 'fa-tv';
                            } elseif (str_contains($facLower, 'audio') || str_contains($facLower, 'sound') || str_contains($facLower, 'speaker')) {
                                $icon = 'fa-volume-up';
                            } elseif (str_contains($facLower, 'stop') || str_contains($facLower, 'kontak')) {
                                $icon = 'fa-plug';
                            }
                        @endphp
                        <li>
                            <i class="fas {{ $icon }}"></i> 
                            {{ $fac }}
                            @if($index == 0)
                                <span class="badge bg-primary ms-2">Utama</span>
                            @endif
                        </li>
                    @endforeach
                @else
                    <!-- Fallback Data -->
                    <li><i class="fas fa-snowflake"></i> AC Split (2 Unit)</li>
                    <li><i class="fas fa-video"></i> Proyektor LCD</li>
                    <li><i class="fas fa-chalkboard"></i> Papan Tulis Whiteboard</li>
                    <li><i class="fas fa-chair"></i> Meja Kursi Standar</li>
                    <li><i class="fas fa-wifi"></i> Akses WiFi</li>
                    <li><i class="fas fa-plug"></i> Stop Kontak</li>
                @endif
            </ul>
        </div>
    </div>

    <!-- KOMENTAR & LAPORAN -->
    <div class="comments-wrapper">
        <div class="comments-header">
            <h4 class="comments-title">
                <i class="fas fa-comments me-2"></i>Laporan & Diskusi
            </h4>
            <div class="comments-stats">
                <span class="stat-badge">
                    <i class="fas fa-message"></i> {{ $room->total_comments ?? 0 }} Komentar
                </span>
                <span class="stat-badge">
                    <i class="fas fa-flag"></i> {{ $room->report_comments_count ?? 0 }} Laporan
                </span>
            </div>
        </div>

        <!-- Daftar Komentar Real dari Database -->
        <div class="chat-box">
            @forelse($room->comments ?? [] as $comment)
            <div class="chat-item {{ $comment->type === 'report' ? 'chat-report' : '' }}">
                <div class="chat-avatar {{ $comment->type === 'report' ? 'avatar-report' : 'avatar-general' }}">
                    <i class="fas {{ $comment->type === 'report' ? 'fa-flag' : 'fa-comment' }}"></i>
                </div>
                <div class="chat-bubble">
                    <div class="chat-meta">
                        <span class="chat-sender">
                            @if($comment->is_anonymous)
                                <i class="fas fa-user-secret me-1"></i> Anonim
                            @else
                                <i class="fas fa-user me-1"></i> 
                                {{ $comment->user->name ?? 'Pengguna' }}
                            @endif
                        </span>
                        <span class="chat-time">{{ $comment->created_at->diffForHumans() }}</span>
                    </div>
                    <p class="chat-message">{{ $comment->body }}</p>
                </div>
            </div>
            @empty
            <div class="text-center py-4 text-muted">
                <i class="fas fa-comments fa-2x mb-3"></i>
                <p>Belum ada komentar untuk ruangan ini.</p>
            </div>
            @endforelse
        </div>

        <!-- Form Input Komentar -->
        <form action="{{ route('comments.store') }}" method="POST" class="anon-input-group">
            @csrf
            <input type="hidden" name="room_id" value="{{ $room->id }}">
            <span class="anon-label"><i class="fas fa-mask me-1"></i> Mode Anonim</span>
            <textarea name="body" class="chat-textarea" rows="3" 
                      placeholder="Tulis laporan atau pertanyaan tentang ruangan ini... (Identitas Anda aman)" 
                      required></textarea>
            <button type="submit" class="btn-send">
                <i class="fas fa-paper-plane me-2"></i> Kirim Laporan
            </button>
            <div style="clear: both;"></div>
        </form>
    </div>

</div>

<script>
function takeQueue() {
    if(confirm('Apakah Anda yakin ingin mengambil antrian untuk ruangan ini?')) {
        const roomId = "{{ $room->id }}";
        
        // Kirim request ke server
        fetch(`/queue/take/${roomId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                alert('Antrian berhasil diambil! Nomor: #' + data.queue_number);
                window.location.href = "{{ route('dashboard.kelas') }}";
            } else {
                alert(data.message || 'Gagal mengambil antrian.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat mengambil antrian.');
        });
    }
}

function showFullDescription(event) {
    event.preventDefault();
    const descText = document.getElementById('descText');
    descText.style.webkitLineClamp = 'unset';
    descText.style.overflow = 'visible';
    descText.style.display = 'block';
    event.target.style.display = 'none';
}

// Auto-resize textarea
document.addEventListener('DOMContentLoaded', function() {
    const textarea = document.querySelector('.chat-textarea');
    if (textarea) {
        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });
    }
});
</script>

@endsection