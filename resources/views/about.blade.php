@extends('layouts.app')

@section('title', 'Tentang Aplikasi - Dasher')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/about.css') }}" />

@section('content')
<div class="header">
    <h2><i class="fas fa-info-circle me-2"></i>Tentang Aplikasi</h2>
    <div class="user-info">
        <div class="user-avatar-small">
            <i class="fas fa-user"></i>
        </div>
    </div>
</div>

<!-- Hero Section -->
<div class="about-hero">
    <div class="hero-content">
        <div class="hero-icon">
            <i class="fas fa-door-open"></i>
        </div>
        <h1>JTIK ROOM'S - Dasher</h1>
        <p class="hero-subtitle">Sistem Manajemen Ruangan Modern untuk Jurusan Teknik Informatika dan Komputer</p>
        <div class="hero-stats">
            <div class="hero-stat">
                <div class="stat-number" data-count="50">0</div>
                <div class="stat-label">Ruangan</div>
            </div>
            <div class="hero-stat">
                <div class="stat-number" data-count="1000">0</div>
                <div class="stat-label">Pengguna</div>
            </div>
            <div class="hero-stat">
                <div class="stat-number" data-count="99">0</div>
                <div class="stat-label">% Uptime</div>
            </div>
        </div>
    </div>
</div>

<!-- Loading State -->
<div id="loadingIndicator" class="loading-indicator" style="display: none;">
    <div class="loading-spinner"></div>
    <p>Memuat konten...</p>
</div>

<!-- Error Boundary -->
<div id="errorBoundary" class="error-boundary" style="display: none;">
    <div class="error-icon">⚠️</div>
    <h4>Terjadi Kesalahan</h4>
    <p>Silakan refresh halaman atau coba lagi nanti.</p>
    <button onclick="location.reload()" class="btn btn-primary">Coba Lagi</button>
</div>

<!-- Features Grid -->
<div class="features-section">
    <div class="section-header">
        <h2><i class="fas fa-star me-2"></i>Fitur Unggulan</h2>
        <p>Semua yang Anda butuhkan untuk manajemen ruangan yang efisien</p>
    </div>
    <div class="features-grid">
        <div class="feature-card">
            <div class="feature-icon">
                <i class="fas fa-search"></i>
            </div>
            <h4>Pencarian Real-time</h4>
            <p>Cari dan filter ruangan berdasarkan fasilitas, kapasitas, dan ketersediaan dengan cepat</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">
                <i class="fas fa-calendar-check"></i>
            </div>
            <h4>Reservasi Terintegrasi</h4>
            <p>Sistem booking yang mudah dengan persetujuan otomatis dan notifikasi</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">
                <i class="fas fa-chart-line"></i>
            </div>
            <h4>Analitik Cerdas</h4>
            <p>Laporan penggunaan ruangan dan statistik untuk perencanaan yang lebih baik</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">
                <i class="fas fa-mobile-alt"></i>
            </div>
            <h4>Responsive Design</h4>
            <p>Akses dari berbagai perangkat dengan tampilan yang optimal</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">
                <i class="fas fa-shield-alt"></i>
            </div>
            <h4>Keamanan Terjamin</h4>
            <p>Autentikasi multi-level dan proteksi data yang komprehensif</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">
                <i class="fas fa-sync"></i>
            </div>
            <h4>Update Real-time</h4>
            <p>Status ruangan diperbarui secara otomatis tanpa refresh halaman</p>
        </div>
    </div>
</div>

<!-- Benefits Section -->
<div class="benefits-section">
    <div class="container">
        <div class="section-header">
            <h2><i class="fas fa-users me-2"></i>Manfaat untuk Semua</h2>
            <p>Setiap peran mendapatkan manfaat yang berbeda</p>
        </div>
        <div class="benefits-tabs">
            <div class="tab-buttons">
                <button class="tab-btn active" data-tab="dosen">Dosen</button>
                <button class="tab-btn" data-tab="mahasiswa">Mahasiswa</button>
                <button class="tab-btn" data-tab="staff">Staff</button>
                <button class="tab-btn" data-tab="admin">Admin</button>
            </div>
            <div class="tab-content">
                <div class="tab-pane active" id="tab-dosen">
                    <div class="benefit-content">
                        <div class="benefit-icon">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>
                        <div class="benefit-text">
                            <h4>Efisiensi Waktu Mengajar</h4>
                            <ul>
                                <li>Cepat menemukan ruang pengganti saat jadwal berubah</li>
                                <li>Monitoring fasilitas ruangan sebelum mengajar</li>
                                <li>Reservasi ruang untuk kegiatan tambahan dengan mudah</li>
                                <li>Notifikasi otomatis untuk perubahan jadwal</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="tab-mahasiswa">
                    <div class="benefit-content">
                        <div class="benefit-icon">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <div class="benefit-text">
                            <h4>Kemudahan Akses Belajar</h4>
                            <ul>
                                <li>Cek ketersediaan ruang belajar kelompok</li>
                                <li>Informasi fasilitas ruangan yang lengkap</li>
                                <li>Booking ruang untuk diskusi dan presentasi</li>
                                <li>Akses informasi real-time via mobile</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="tab-staff">
                    <div class="benefit-content">
                        <div class="benefit-icon">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <div class="benefit-text">
                            <h4>Manajemen Fasilitas Optimal</h4>
                            <ul>
                                <li>Pemantauan penggunaan ruangan secara berkala</li>
                                <li>Perencanaan pemeliharaan yang terstruktur</li>
                                <li>Koordinasi antar departemen yang lebih baik</li>
                                <li>Laporan statistik untuk evaluasi</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="tab-admin">
                    <div class="benefit-content">
                        <div class="benefit-icon">
                            <i class="fas fa-cogs"></i>
                        </div>
                        <div class="benefit-text">
                            <h4>Kontrol Penuh Sistem</h4>
                            <ul>
                                <li>Dashboard analitik yang komprehensif</li>
                                <li>Manajemen pengguna dan permissions</li>
                                <li>Konfigurasi sistem yang fleksibel</li>
                                <li>Backup dan recovery data yang aman</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Technology Stack -->
<div class="tech-section">
    <div class="section-header">
        <h2><i class="fas fa-code me-2"></i>Teknologi Kami</h2>
        <p>Dibangun dengan teknologi modern untuk performa terbaik</p>
    </div>
    <div class="tech-grid">
        <div class="tech-card">
            <div class="tech-logo">
                <i class="fab fa-laravel"></i>
            </div>
            <h5>Laravel</h5>
            <p>PHP Framework</p>
        </div>
        <div class="tech-card">
            <div class="tech-logo">
                <i class="fas fa-database"></i>
            </div>
            <h5>MySQL</h5>
            <p>Database</p>
        </div>
        <div class="tech-card">
            <div class="tech-logo">
                <i class="fab fa-js-square"></i>
            </div>
            <h5>JavaScript</h5>
            <p>Frontend Interactivity</p>
        </div>
        <div class="tech-card">
            <div class="tech-logo">
                <i class="fab fa-bootstrap"></i>
            </div>
            <h5>Bootstrap</h5>
            <p>UI Framework</p>
        </div>
        <div class="tech-card">
            <div class="tech-logo">
                <i class="fas fa-server"></i>
            </div>
            <h5>REST API</h5>
            <p>Web Services</p>
        </div>
        <div class="tech-card">
            <div class="tech-logo">
                <i class="fas fa-mobile-alt"></i>
            </div>
            <h5>Responsive</h5>
            <p>Mobile First</p>
        </div>
    </div>
</div>

<!-- Team Section -->
<div class="team-section-modern">
    <div class="section-header">
        <h2><i class="fas fa-users me-2"></i>Tim Pengembang</h2>
        <p>Dibuat dengan ❤️ oleh tim profesional</p>
    </div>
    <div class="team-slider-container">
        <button class="slider-btn prev-btn">
            <i class="fas fa-chevron-left"></i>
        </button>
        <div class="team-slider-track" id="teamSlider">
            <!-- Team Member 1 -->
            <div class="team-member-card">
                <div class="member-header">
                    <div class="member-avatar">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <div class="member-badge">Lead</div>
                </div>
                <h4>Muhammad Fadhil Maulana Irawan</h4>
                <p class="member-role">Project Manager & Backend</p>
                <div class="member-skills">
                    <span class="skill-tag">Laravel</span>
                    <span class="skill-tag">MySQL</span>
                    <span class="skill-tag">API</span>
                </div>
                <div class="member-social">
                    <span class="social-item"><i class="fas fa-envelope"></i></span>
                    <span class="social-item"><i class="fab fa-github"></i></span>
                    <span class="social-item"><i class="fab fa-linkedin"></i></span>
                </div>
            </div>

            <!-- Team Member 2 -->
            <div class="team-member-card">
                <div class="member-header">
                    <div class="member-avatar">
                        <i class="fas fa-palette"></i>
                    </div>
                    <div class="member-badge">Design</div>
                </div>
                <h4>Rahmat Rayhan</h4>
                <p class="member-role">UI/UX Designer & Frontend</p>
                <div class="member-skills">
                    <span class="skill-tag">Figma</span>
                    <span class="skill-tag">CSS</span>
                    <span class="skill-tag">JavaScript</span>
                </div>
                <div class="member-social">
                    <span class="social-item"><i class="fas fa-envelope"></i></span>
                    <span class="social-item"><i class="fab fa-github"></i></span>
                    <span class="social-item"><i class="fab fa-linkedin"></i></span>
                </div>
            </div>

            <!-- Team Member 3 -->
            <div class="team-member-card">
                <div class="member-header">
                    <div class="member-avatar">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <div class="member-badge">Security</div>
                </div>
                <h4>Noer Azizah</h4>
                <p class="member-role">Cyber Security Specialist</p>
                <div class="member-skills">
                    <span class="skill-tag">Security</span>
                    <span class="skill-tag">Testing</span>
                    <span class="skill-tag">Audit</span>
                </div>
                <div class="member-social">
                    <span class="social-item"><i class="fas fa-envelope"></i></span>
                    <span class="social-item"><i class="fab fa-github"></i></span>
                    <span class="social-item"><i class="fab fa-linkedin"></i></span>
                </div>
            </div>

            <!-- Team Member 4 -->
            <div class="team-member-card">
                <div class="member-header">
                    <div class="member-avatar">
                        <i class="fas fa-paint-brush"></i>
                    </div>
                    <div class="member-badge">Design</div>
                </div>
                <h4>Muhammad Fadlan</h4>
                <p class="member-role">UI/UX Designer</p>
                <div class="member-skills">
                    <span class="skill-tag">Design</span>
                    <span class="skill-tag">Prototype</span>
                    <span class="skill-tag">Wireframe</span>
                </div>
                <div class="member-social">
                    <span class="social-item"><i class="fas fa-envelope"></i></span>
                    <span class="social-item"><i class="fab fa-github"></i></span>
                    <span class="social-item"><i class="fab fa-linkedin"></i></span>
                </div>
            </div>
        </div>
        <button class="slider-btn next-btn">
            <i class="fas fa-chevron-right"></i>
        </button>
    </div>
    <div class="slider-dots" id="sliderDots"></div>
</div>

<!-- CTA Section -->
<div class="cta-section">
    <div class="cta-content">
        <h3>Siap Mengoptimalkan Manajemen Ruangan Anda?</h3>
        <p>Bergabung dengan ratusan pengguna yang telah merasakan kemudahan JTIK ROOM'S</p>
        <div class="cta-buttons">
            <a href="/dashboard" class="btn btn-primary btn-lg">
                <i class="fas fa-rocket me-2"></i>Mulai Sekarang
            </a>
            <a href="/informasi" class="btn btn-outline-light btn-lg">
                <i class="fas fa-book me-2"></i>Pelajari Lebih Lanjut
            </a>
        </div>
    </div>
</div>

<script>
// Enhanced version dengan error handling
class AboutPageUX {
    constructor() {
        this.initializeComponents();
    }

    initializeComponents() {
        try {
            this.animateCounter();
            this.initTabs();
            this.initTeamSlider();
            this.setupIntersectionObserver();
        } catch (error) {
            console.error('Initialization error:', error);
            this.showErrorState();
        }
    }

    animateCounter() {
        const counters = document.querySelectorAll('.stat-number');
        const isReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        if (isReducedMotion) {
            counters.forEach(counter => {
                counter.textContent = counter.getAttribute('data-count');
            });
            return;
        }

        counters.forEach(counter => {
            const target = +counter.getAttribute('data-count');
            const duration = 2000;
            const startTime = performance.now();

            const updateCounter = (currentTime) => {
                const elapsed = currentTime - startTime;
                const progress = Math.min(elapsed / duration, 1);
                
                const easeOutQuart = 1 - Math.pow(1 - progress, 4);
                const current = Math.floor(target * easeOutQuart);

                counter.textContent = current;

                if (progress < 1) {
                    requestAnimationFrame(updateCounter);
                } else {
                    counter.textContent = target;
                }
            };

            requestAnimationFrame(updateCounter);
        });
    }

    initTabs() {
        const tabBtns = document.querySelectorAll('.tab-btn');
        const tabPanes = document.querySelectorAll('.tab-pane');
        
        tabBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                const targetTab = btn.getAttribute('data-tab');
                
                // Update buttons
                tabBtns.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                
                // Update panes
                tabPanes.forEach(pane => {
                    pane.classList.remove('active');
                    if (pane.id === `tab-${targetTab}`) {
                        pane.classList.add('active');
                    }
                });
            });
        });
    }

    initTeamSlider() {
        const track = document.getElementById('teamSlider');
        if (!track) return;
        
        const cards = track.querySelectorAll('.team-member-card');
        const cardWidth = 280 + 32; // width + gap
        
        // Hide navigation buttons and dots
        const prevBtn = document.querySelector('.prev-btn');
        const nextBtn = document.querySelector('.next-btn');
        const dotsContainer = document.getElementById('sliderDots');
        
        if (prevBtn) prevBtn.style.display = 'none';
        if (nextBtn) nextBtn.style.display = 'none';
        if (dotsContainer) dotsContainer.style.display = 'none';
        
        // Dynamic cards per view based on screen size
        const getCardsPerView = () => {
            if (window.innerWidth >= 1200) {
                return 4; // Desktop besar: 4 cards
            } else if (window.innerWidth >= 768) {
                return 3; // Tablet: 3 cards  
            } else {
                return 2; // Mobile: 2 cards
            }
        };
        
        let cardsPerView = getCardsPerView();
        let currentSlide = 0;
        const totalSlides = Math.ceil(cards.length / cardsPerView);
        
        // Swipe functionality
        let startX = 0;
        let currentX = 0;
        let isDragging = false;
        
        const handleTouchStart = (e) => {
            startX = e.touches[0].clientX;
            isDragging = true;
            track.style.cursor = 'grabbing';
            track.style.transition = 'none';
        };
        
        const handleTouchMove = (e) => {
            if (!isDragging) return;
            currentX = e.touches[0].clientX;
            const diff = currentX - startX;
            
            // Apply drag effect dengan resistance
            track.style.transform = `translateX(calc(${-currentSlide * cardsPerView * cardWidth}px + ${diff * 0.5}px))`;
        };
        
        const handleTouchEnd = () => {
            if (!isDragging) return;
            isDragging = false;
            track.style.cursor = 'grab';
            track.style.transition = 'transform 0.5s ease';
            
            const diff = currentX - startX;
            const swipeThreshold = 50;
            
            // Determine swipe direction
            if (Math.abs(diff) > swipeThreshold) {
                if (diff > 0) {
                    // Swipe right - previous
                    this.prevSlide();
                } else {
                    // Swipe left - next
                    this.nextSlide();
                }
            } else {
                // Return to current position
                this.updateSlider();
            }
        };
        
        // Mouse events untuk desktop
        const handleMouseDown = (e) => {
            startX = e.clientX;
            isDragging = true;
            track.style.cursor = 'grabbing';
            track.style.transition = 'none';
        };
        
        const handleMouseMove = (e) => {
            if (!isDragging) return;
            currentX = e.clientX;
            const diff = currentX - startX;
            track.style.transform = `translateX(calc(${-currentSlide * cardsPerView * cardWidth}px + ${diff}px))`;
        };
        
        const handleMouseUp = () => {
            if (!isDragging) return;
            isDragging = false;
            track.style.cursor = 'grab';
            track.style.transition = 'transform 0.5s ease';
            
            const diff = currentX - startX;
            const swipeThreshold = 100;
            
            if (Math.abs(diff) > swipeThreshold) {
                if (diff > 0) {
                    this.prevSlide();
                } else {
                    this.nextSlide();
                }
            } else {
                this.updateSlider();
            }
        };
        
        // Add event listeners untuk swipe
        track.addEventListener('touchstart', handleTouchStart);
        track.addEventListener('touchmove', handleTouchMove);
        track.addEventListener('touchend', handleTouchEnd);
        
        track.addEventListener('mousedown', handleMouseDown);
        track.addEventListener('mousemove', handleMouseMove);
        track.addEventListener('mouseup', handleMouseUp);
        track.addEventListener('mouseleave', handleMouseUp);
        
        // Update slider position
        this.updateSlider = () => {
            const translateX = -currentSlide * cardsPerView * cardWidth;
            track.style.transform = `translateX(${translateX}px)`;
        };
        
        // Navigation methods
        this.nextSlide = () => {
            if (currentSlide < totalSlides - 1) {
                currentSlide++;
                this.updateSlider();
            }
        };
        
        this.prevSlide = () => {
            if (currentSlide > 0) {
                currentSlide--;
                this.updateSlider();
            }
        };
        
        // Handle resize untuk responsive cards count
        let resizeTimeout;
        const handleResize = () => {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(() => {
                const newCardsPerView = getCardsPerView();
                if (newCardsPerView !== cardsPerView) {
                    cardsPerView = newCardsPerView;
                    currentSlide = 0; // Reset ke slide pertama
                    this.updateSlider();
                }
            }, 250);
        };
        
        window.addEventListener('resize', handleResize);
        
        // Auto-slide
        this.autoSlide = setInterval(this.nextSlide.bind(this), 5000);
        
        // Pause auto-slide on interaction
        track.addEventListener('touchstart', () => clearInterval(this.autoSlide));
        track.addEventListener('mousedown', () => clearInterval(this.autoSlide));
        
        // Initial setup
        track.style.cursor = 'grab';
        this.updateSlider();
    }

    setupIntersectionObserver() {
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);
        
        document.querySelectorAll('.feature-card, .team-member-card').forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';
            card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            observer.observe(card);
        });
    }

    showErrorState() {
        const errorBoundary = document.getElementById('errorBoundary');
        if (errorBoundary) {
            errorBoundary.style.display = 'block';
        }
    }
}

// Initialize
document.addEventListener('DOMContentLoaded', () => {
    try {
        new AboutPageUX();
    } catch (error) {
        console.error('Failed to initialize About Page:', error);
    }
});
</script>
@endsection