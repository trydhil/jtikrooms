<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title>@yield('title', 'JTIK ROOM'S - Dashboard Ruangan')</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
    
    <!-- Animations CSS -->
    <link rel="stylesheet" href="{{ asset('css/animations.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/theme.css') }}">
    <!-- Mobile Specific Meta Tags -->
    <meta name="theme-color" content="#2c5aa0" />
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    
    @stack('styles')
</head>
<body>
    <!-- Login Success Animation -->
    @if(session('login_success'))
        @include('components.login-animation')
    @endif

    <!-- Mobile Header (Visible only on mobile) -->
    <div class="mobile-header d-lg-none">
        <div class="mobile-header-content">
            <button class="sidebar-toggle" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>
            <div class="mobile-brand">
                <img src="{{ asset('img/logo1.png') }}" alt="Logo" class="mobile-logo" 
                     onerror="this.style.display='none';" />
                <span class="mobile-title">JTIK ROOM'S</span>
            </div>
            <div class="mobile-user">
                @if(session('loggedin'))
                    <i class="fas fa-user-circle"></i>
                @else
                    <a href="{{ route('login') }}" class="mobile-login-btn">
                        <i class="fas fa-sign-in-alt"></i>
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Mobile Overlay -->
    <div class="mobile-overlay d-lg-none" id="mobileOverlay"></div>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <img src="{{ asset('img/logo2.png') }}" alt="Logo" class="logo-large" 
                 onerror="this.style.display='none'; this.nextElementSibling.style.display='block';" />
            <img src="{{ asset('img/logo1.png') }}" alt="Logo" class="logo-small" 
                 onerror="this.style.display='none';" />
            <button class="sidebar-close d-lg-none" id="sidebarClose">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <ul class="sidebar-menu">
            <!-- Room List - Always accessible -->
            <li>
                <a href="{{ route('home') }}" class="{{ request()->is('/') ? 'active' : '' }}">
                    <i class="fas fa-th-list"></i>
                    <span>Ruangan</span>
                </a>
            </li>

            <!-- Administrator Menu -->
            <li>
                @if(session('loggedin') && session('role') === 'admin')
                    <a href="{{ route('dashboard.admin') }}" class="{{ request()->is('dashboard/admin') ? 'active' : '' }}">
                        <i class="fas fa-user-shield"></i>
                        <span>Administrator</span>
                    </a>
                @else
                    <a href="#" onclick="showAccessMessage('admin')" class="disabled-link">
                        <i class="fas fa-user-shield"></i>
                        <span>Administrator</span>
                    </a>
                @endif
            </li>

            <!-- Kelas Menu -->
            <li>
                @if(session('loggedin') && (session('role') === 'kelas' || session('role') === 'admin'))
                    <a href="{{ route('dashboard.kelas') }}" class="{{ request()->is('dashboard/kelas') ? 'active' : '' }}">
                        <i class="fas fa-chalkboard"></i>
                        <span>Kelas</span>
                    </a>
                @else
                    <a href="#" onclick="showAccessMessage('kelas')" class="disabled-link">
                        <i class="fas fa-chalkboard"></i>
                        <span>Kelas</span>
                    </a>
                @endif
            </li>

            <!-- Informasi - Always accessible --> 
            <li>
                <a href="{{ route('informasi') }}" class="{{ request()->is('informasi') ? 'active' : '' }}">
                    <i class="fas fa-info-circle"></i>
                    <span>Informasi</span>
                </a>
            </li>
        </ul>

        <div class="login-box">
            <div class="login-avatar">
                <i class="fas fa-user"></i>
            </div>
            <div id="loginStatus">
                @if(session('loggedin'))
                    <div class="user-info">
                        <div class="user-name">{{ session('user') }}</div>
                        <small class="user-role">
                            {{ session('role') === 'admin' ? 'Administrator' : 'Perwakilan Kelas' }}
                        </small>
                    </div>
                    <form action="{{ route('logout') }}" method="POST" class="logout-form">
                        @csrf
                        <button type="submit" class="btn-login">
                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn-login">
                        <i class="fas fa-sign-in-alt me-2"></i>Login
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content" id="mainContent">
        @yield('content')
    </div>

    <!-- Access Denied Modal -->
    <div class="modal fade" id="accessAlertModal" tabindex="-1" aria-labelledby="accessAlertLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content access-denied-modal">
                <!-- Header dengan Icon & Judul -->
                <div class="modal-header access-denied-header">
                    <div class="access-denied-icon">
                        <i class="fas fa-lock"></i>
                    </div>
                    <h5 class="modal-title" id="accessAlertLabel">Akses Ditolak</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <!-- Body dengan Pesan -->
                <div class="modal-body access-denied-body">
                    <div class="access-denied-message">
                        <p class="access-denied-text">
                            Maaf, Anda tidak memiliki izin untuk mengakses bagian ini.
                        </p>

                        <div class="access-denied-role" id="accessDeniedRole">
                            <strong>Role Anda:</strong> <span id="userRoleDisplay">-</span>
                        </div>

                        <div class="access-denied-note">
                            <i class="fas fa-info-circle"></i>
                            <div>
                                <p style="margin: 0; font-size: 0.9rem; line-height: 1.5;">
                                    Jika Anda merasa ini adalah kesalahan, silakan hubungi administrator untuk mendapatkan akses yang sesuai.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer dengan Tombol -->
                <div class="modal-footer access-denied-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Tutup
                    </button>
                    <a href="{{ route('home') }}" class="btn" style="background: linear-gradient(90deg, var(--biru-medium) 0%, var(--oranye) 100%); color: white; border: none;">
                        <i class="fas fa-home me-2"></i>Kembali ke Home
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/script.js') }}"></script>
<script src="{{ asset('js/animations.js') }}"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<script>
    /**
     * JTIKRooms Main JavaScript
     * Version: Optimized for Laravel 12 & Alpine.js
     */

    // 1. Inisialisasi Alpine.js untuk Interaksi Kartu Ruangan
    document.addEventListener('alpine:init', () => {
        Alpine.data('roomCard', () => ({
            isHovered: false,
            isBooking: false,
            
            async checkAvailability(roomName) {
                this.isBooking = true;
                try {
                    // Cek status ruangan secara real-time ke API
                    const response = await fetch(`/api/room/${roomName}/status`);
                    const data = await response.json();
                    
                    if (data.success) {
                        if (data.data.status === 'available') {
                            window.location.href = `/room/${roomName}`;
                        } else {
                            this.showStatusModal(data.data);
                        }
                    }
                } catch (error) {
                    console.error('Fetch Error:', error);
                } finally {
                    this.isBooking = false;
                }
            },
            
            showStatusModal(data) {
                // Menampilkan alert sederhana, bisa diganti dengan Modal Bootstrap jika perlu
                alert(`Ruangan ${data.room_name} saat ini ${data.status}.\nDigunakan untuk: ${data.mata_kuliah || '-'}`);
            }
        }));
    });

    // 2. Logika Sidebar & Mobile Navigation
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebarClose = document.getElementById('sidebarClose');
    const mobileOverlay = document.getElementById('mobileOverlay');

    function toggleSidebar() {
        if (sidebar && mobileOverlay) {
            sidebar.classList.toggle('active');
            mobileOverlay.classList.toggle('active');
            document.body.classList.toggle('sidebar-open');
        }
    }

    // Event Listeners untuk Sidebar
    if (sidebarToggle) sidebarToggle.addEventListener('click', toggleSidebar);
    if (sidebarClose) sidebarClose.addEventListener('click', toggleSidebar);
    if (mobileOverlay) mobileOverlay.addEventListener('click', toggleSidebar);

    // 3. Touch Swipe untuk Mobile UX (Buka/Tutup Sidebar dengan Geser)
    let touchStartX = 0;
    let touchEndX = 0;

    document.addEventListener('touchstart', e => { touchStartX = e.changedTouches[0].screenX; }, {passive: true});
    document.addEventListener('touchend', e => { 
        touchEndX = e.changedTouches[0].screenX; 
        handleSwipe();
    }, {passive: true});

    function handleSwipe() {
        const threshold = 100;
        const dist = touchEndX - touchStartX;
        
        if (Math.abs(dist) > threshold) {
            if (dist > 0 && touchStartX < 40) toggleSidebar(); // Swipe kanan (buka)
            if (dist < 0 && sidebar.classList.contains('active')) toggleSidebar(); // Swipe kiri (tutup)
        }
    }

    // 4. Handle Window Resize & Orientation
    window.addEventListener('resize', () => {
        if (window.innerWidth >= 992 && sidebar && sidebar.classList.contains('active')) {
            toggleSidebar();
        }
    });

    // 5. Pencegahan Zoom Berlebih di iOS
    document.addEventListener('touchend', (event) => {
        const now = (new Date()).getTime();
        if (now - (window.lastTouchEnd || 0) <= 300) event.preventDefault();
        window.lastTouchEnd = now;
    }, false);

    // 6. Notifikasi Login (Laravel Session)
    @if(session('login_success'))
        document.addEventListener('DOMContentLoaded', () => {
            console.log("Login sukses: Memberikan efek highlight pada menu.");
            // Logika tambahan untuk highlight menu jika diperlukan
        });
    @endif

    // 7. Utilitas Pesan Akses
    function showAccessMessage(roleType) {
        const msg = roleType === 'admin' ? 'ADMINISTRATOR' : 'PERWAKILAN KELAS';
        const targetElement = document.getElementById('accessMessage');
        if (targetElement) {
            targetElement.textContent = `WAJIB LOGIN SEBAGAI ${msg} UNTUK MENGAKSES BAGIAN INI`;
            const modal = new bootstrap.Modal(document.getElementById('accessAlertModal'));
            modal.show();
        }
    }
</script>
    
    @stack('scripts')
</body>
</html>