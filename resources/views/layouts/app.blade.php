<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title>@yield('title', 'Dasher - Dashboard Ruangan')</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
    
    <!-- Animations CSS -->
    <link rel="stylesheet" href="{{ asset('css/animations.css') }}" />
    
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
                <span class="mobile-title">Dasher</span>
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
                    <span>Room List</span>
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

            <!-- About - Always accessible -->
            <li>
                <a href="{{ route('about') }}" class="{{ request()->is('about') ? 'active' : '' }}">
                    <i class="fas fa-info-circle"></i>
                    <span>About</span>
                </a>
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
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title" id="accessAlertLabel">
                        <i class="fas fa-exclamation-triangle me-2"></i>Akses Ditolak
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <div class="mb-3">
                        <i class="fas fa-lock fa-3x text-warning"></i>
                    </div>
                    <h6 class="fw-bold" id="accessMessage"></h6>
                    <p class="text-muted mt-2">Silakan login dengan akun yang sesuai untuk mengakses fitur ini.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <a href="{{ route('login') }}" class="btn btn-warning">
                        <i class="fas fa-sign-in-alt me-2"></i>Login Sekarang
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/script.js') }}"></script>
    <script src="{{ asset('js/animations.js') }}"></script>
    
    <script>
        // Mobile Sidebar Toggle
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebarClose = document.getElementById('sidebarClose');
        const mobileOverlay = document.getElementById('mobileOverlay');
        const mainContent = document.getElementById('mainContent');

        function toggleSidebar() {
            sidebar.classList.toggle('active');
            mobileOverlay.classList.toggle('active');
            document.body.classList.toggle('sidebar-open');
        }

        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', toggleSidebar);
        }

        if (sidebarClose) {
            sidebarClose.addEventListener('click', toggleSidebar);
        }

        if (mobileOverlay) {
            mobileOverlay.addEventListener('click', toggleSidebar);
        }

        // Close sidebar when clicking on menu items (mobile)
        const menuItems = document.querySelectorAll('.sidebar-menu a');
        menuItems.forEach(item => {
            item.addEventListener('click', () => {
                if (window.innerWidth < 992) {
                    toggleSidebar();
                }
            });
        });

        // Touch swipe for mobile
        let touchStartX = 0;
        let touchEndX = 0;

        document.addEventListener('touchstart', e => {
            touchStartX = e.changedTouches[0].screenX;
        });

        document.addEventListener('touchend', e => {
            touchEndX = e.changedTouches[0].screenX;
            handleSwipe();
        });

        function handleSwipe() {
            const swipeThreshold = 50;
            const swipeDistance = touchEndX - touchStartX;

            if (Math.abs(swipeDistance) > swipeThreshold) {
                if (swipeDistance > 0 && touchStartX < 50) {
                    // Swipe right - open sidebar
                    if (!sidebar.classList.contains('active')) {
                        toggleSidebar();
                    }
                } else if (swipeDistance < 0) {
                    // Swipe left - close sidebar
                    if (sidebar.classList.contains('active')) {
                        toggleSidebar();
                    }
                }
            }
        }

        // Handle orientation change
        window.addEventListener('orientationchange', function() {
            // Close sidebar on orientation change for better UX
            if (window.innerWidth >= 992) {
                sidebar.classList.remove('active');
                mobileOverlay.classList.remove('active');
                document.body.classList.remove('sidebar-open');
            }
            
            // Small timeout to ensure CSS recalculation
            setTimeout(() => {
                window.dispatchEvent(new Event('resize'));
            }, 300);
        });

        // Responsive resize handler
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 992) {
                sidebar.classList.remove('active');
                mobileOverlay.classList.remove('active');
                document.body.classList.remove('sidebar-open');
            }
        });

        // Access message function
        function showAccessMessage(roleType) {
            const messages = {
                'admin': 'WAJIB LOGIN SEBAGAI ADMINISTRATOR UNTUK MENGAKSES BAGIAN INI',
                'kelas': 'WAJIB LOGIN SEBAGAI PERWAKILAN KELAS UNTUK MENGAKSES BAGIAN INI'
            };
            
            const message = messages[roleType] || 'Akses ditolak. Silakan login terlebih dahulu.';
            document.getElementById('accessMessage').textContent = message;
            
            const modal = new bootstrap.Modal(document.getElementById('accessAlertModal'));
            modal.show();
        }

        // Prevent zoom on double tap (iOS)
        let lastTouchEnd = 0;
        document.addEventListener('touchend', function (event) {
            const now = (new Date()).getTime();
            if (now - lastTouchEnd <= 300) {
                event.preventDefault();
            }
            lastTouchEnd = now;
        }, false);

        // Load CSS for mobile optimization
        document.addEventListener('DOMContentLoaded', function() {
            // Add touch-specific classes
            if ('ontouchstart' in window || navigator.maxTouchPoints) {
                document.body.classList.add('touch-device');
            } else {
                document.body.classList.add('no-touch-device');
            }

            // Initialize animations
            if (typeof DasherAnimations !== 'undefined') {
                DasherAnimations.init();
            }
        });

        // Login success effects
        @if(session('login_success'))
        document.addEventListener('DOMContentLoaded', function() {
            // Highlight the appropriate menu item based on role
            setTimeout(() => {
                @if(session('user_role') === 'admin')
                    const adminMenu = document.querySelector('a[href="{{ route('dashboard.admin') }}"]');
                    if (adminMenu) {
                        adminMenu.classList.add('pulse-highlight');
                        setTimeout(() => adminMenu.classList.remove('pulse-highlight'), 3000);
                    }
                @else
                    const kelasMenu = document.querySelector('a[href="{{ route('dashboard.kelas') }}"]');
                    if (kelasMenu) {
                        kelasMenu.classList.add('pulse-highlight');
                        setTimeout(() => kelasMenu.classList.remove('pulse-highlight'), 3000);
                    }
                @endif
            }, 1500);
        });
        @endif
    </script>
    
    @stack('scripts')
</body>
</html>