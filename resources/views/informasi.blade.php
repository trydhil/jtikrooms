@extends('layouts.app')

@section('title', 'Informasi - JTIK ROOMS')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/informasi.css') }}" />

@section('content')
<div class="header">
    <h2><i class="fas fa-info-circle me-2"></i>Informasi JTIK</h2>
    <div class="user-info-main">
        
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert" data-animate>
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(auth()->check() && auth()->user()->role === 'admin')
<div class="admin-controls mb-4" data-animate>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editAboutModal">
        <i class="fas fa-edit me-2"></i>Edit Informasi
    </button>
</div>
@endif

<div class="info-hero" data-animate>
    <div class="hero-content">
        <h1>{{ $about->hero_stats['title'] ?? 'Jurusan Teknik Informatika dan Komputer' }}</h1>
        <p>{{ $about->hero_stats['subtitle'] ?? 'Menciptakan generasi unggul di bidang teknologi informasi dan komputer yang siap bersaing di era digital' }}</p>
        <div class="hero-stats">
            <div class="stat-item">
                <div class="stat-number">{{ $about->hero_stats['students'] ?? '500+' }}</div>
                <div class="stat-label">Mahasiswa Aktif</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">{{ $about->hero_stats['lecturers'] ?? '25+' }}</div>
                <div class="stat-label">Dosen Berpengalaman</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">{{ $about->hero_stats['accreditation_badge'] ?? 'A' }}</div>
                <div class="stat-label">Akreditasi Unggul</div>
            </div>
        </div>
    </div>
</div>

<div class="about-content">
    <div class="info-grid-main">
        <div class="info-card-main" data-animate style="--item-index: 0;">
            <div class="info-card-header-main">
                <div class="info-icon">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <h4>Lokasi & Kontak</h4>
            </div>
            <div class="info-card-body-main">
                <div class="contact-list">
                    <div class="contact-item-main">
                        <i class="fas fa-map-pin mt-1"></i>
                        <div class="contact-details">
                            <strong>Alamat Kampus</strong>
                            <p>{{ $about['info']['address'] ?? 'Jl. Pendidikan No. 123, Jakarta Selatan' }}</p>
                        </div>
                    </div>
                    <div class="contact-item-main">
                        <i class="fas fa-phone mt-1"></i>
                        <div class="contact-details">
                            <strong>Telepon</strong>
                            <p>{{ $about['info']['phone'] ?? '(021) 1234-5678' }}</p>
                        </div>
                    </div>
                    <div class="contact-item-main">
                        <i class="fas fa-envelope mt-1"></i>
                        <div class="contact-details">
                            <strong>Email</strong>
                            <p>{{ $about['info']['email'] ?? 'jtik@universitas.ac.id' }}</p>
                        </div>
                    </div>
                </div>
                <div class="contact-actions-main mt-3">
                    <a href="{{ $about['info']['maps_url'] ?? '#' }}" 
                       target="_blank" class="btn btn-primary btn-action-main">
                        <i class="fas fa-directions me-2"></i>Lihat di Maps
                    </a>
                </div>
            </div>
        </div>

        <div class="info-card-main" data-animate style="--item-index: 1;">
            <div class="info-card-header-main">
                <div class="info-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <h4>Jam Operasional</h4>
            </div>
            <div class="info-card-body-main">
                <div class="schedule-list">
                    @foreach(($about['info']['operational_hours'] ?? [
                        ['day' => 'Senin - Kamis', 'hours' => '08:00 - 16:00'],
                        ['day' => 'Jumat', 'hours' => '08:00 - 16:30'],
                        ['day' => 'Sabtu', 'hours' => '08:00 - 14:00'],
                        ['day' => 'Minggu', 'hours' => 'Libur']
                    ]) as $schedule)
                    <div class="schedule-item-main d-flex justify-content-between">
                        <span class="schedule-day fw-bold">{{ $schedule['day'] }}</span>
                        <span class="schedule-time">{{ $schedule['hours'] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="info-card-main" data-animate style="--item-index: 2;">
            <div class="info-card-header-main">
                <div class="info-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <h4>Program Studi</h4>
            </div>
            <div class="info-card-body-main">
                <div class="program-list">
                    @foreach(($about['info']['study_programs'] ?? [
                        'Teknik Informatika (S1)',
                        'Sistem Informasi (S1)', 
                        'Teknik Komputer (S1)',
                        'Manajemen Informatika (D3)'
                    ]) as $program)
                    <div class="program-item d-flex align-items-center">
                        <i class="fas fa-book me-2 text-primary"></i>
                        <span>{{ $program }}</span>
                    </div>
                    @endforeach
                </div>
                <div class="accreditation-badge d-inline-flex align-items-center rounded-pill px-3 py-2 text-white fw-bold mt-3">
                    <i class="fas fa-award me-2"></i>
                    {{ $about['info']['accreditation'] ?? 'TERAKREDITASI A' }}
                </div>
            </div>
        </div>
    </div>

    <div class="history-section">
        <div class="section-header-main" data-animate>
            <h3><i class="fas fa-book-open me-2"></i>Sejarah JTIK</h3>
        </div>
        <div class="history-card" data-animate>
            <div class="history-icon">
                <i class="fas fa-landmark"></i>
            </div>
            <p>{{ $about['detail']['history'] ?? 'Jurusan Teknik Informatika dan Komputer (JTIK) didirikan pada tahun 1995 dengan visi untuk menjadi pusat unggulan dalam pendidikan teknologi informasi dan komputer. Selama lebih dari 25 tahun, JTIK telah menghasilkan lulusan yang kompeten dan berdaya saing tinggi di industri teknologi baik nasional maupun internasional. Kami terus berkomitmen untuk memberikan pendidikan berkualitas dengan kurikulum yang selalu diperbarui sesuai perkembangan teknologi terkini.' }}</p>
        </div>
    </div>

    <div class="vision-mission-section">
        <div class="section-header-main" data-animate>
            <h3><i class="fas fa-bullseye me-2"></i>Visi & Misi JTIK</h3>
        </div>
        <div class="vision-mission-grid">
            <div class="vision-card" data-animate style="--item-index: 0;">
                <div class="vm-icon">
                    <i class="fas fa-eye"></i>
                </div>
                <h5>Visi</h5>
                <p>{{ $about['detail']['vision'] ?? 'Menjadi program studi unggulan dalam bidang teknik informatika dan komputer yang menghasilkan lulusan berdaya saing global, berkarakter, dan mampu berkontribusi dalam pengembangan ilmu pengetahuan dan teknologi untuk kemaslahatan umat manusia.' }}</p>
            </div>
            <div class="mission-card" data-animate style="--item-index: 1;">
                <div class="vm-icon">
                    <i class="fas fa-list-check"></i>
                </div>
                <h5>Misi</h5>
                <div class="mission-list text-start">
                    @foreach(($about['detail']['missions'] ?? [
                        'Menyelenggarakan pendidikan tinggi yang berkualitas dalam bidang teknik informatika dan komputer.',
                        'Melaksanakan penelitian inovatif yang bermanfaat bagi pengembangan ilmu pengetahuan dan teknologi.',
                        'Menerapkan hasil pendidikan dan penelitian untuk pengabdian kepada masyarakat.',
                        'Mengembangkan kerjasama dengan institusi dalam dan luar negeri.',
                        'Menghasilkan lulusan yang berkarakter, profesional, dan memiliki jiwa entrepreneurship.'
                    ]) as $mission)
                    <div class="mission-item d-flex">
                        <i class="fas fa-check-circle me-2 text-success mt-1"></i>
                        <span>{{ $mission }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="achievements-section">
        <div class="section-header-main" data-animate>
            <h3><i class="fas fa-trophy me-2"></i>Prestasi & Penghargaan</h3>
        </div>
        
        <div class="slider-container" data-animate>
            <div class="slider-track">
                @foreach(($about['detail']['achievements'] ?? [
                    ['year' => '2023', 'title' => 'Juara 1 National Programming Contest 2023'],
                    ['year' => '2022', 'title' => 'Akreditasi A BAN-PT untuk semua program studi'],
                    ['year' => '2021', 'title' => 'Best Paper Award di International Conference on IT'],
                    ['year' => '2020', 'title' => 'Juara 2 Gemastik Nasional bidang Data Mining'],
                    ['year' => '2019', 'title' => 'Inovasi Terbaik Kemdikbud untuk Sistem E-Learning']
                ]) as $index => $achievement)
                <div class="achievement-card" style="--item-index: {{ $index }};">
                    <div class="achievement-icon">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <div class="achievement-content text-center">
                        <span class="achievement-year">{{ $achievement['year'] }}</span>
                        <h6>{{ $achievement['title'] }}</h6>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="staff-section">
        <div class="section-header-main" data-animate>
            <h3><i class="fas fa-users me-2"></i>Dosen & Staf</h3>
        </div>
        
        <div class="staff-slider-container" data-animate>
            <div class="staff-slider-track">
                <div class="staff-category-card" style="--item-index: 0;">
                    <div class="staff-category-header d-flex align-items-center">
                        <i class="fas fa-chalkboard-teacher me-3 fs-4 text-primary"></i>
                        <div>
                            <h5 class="m-0">Dosen Tetap</h5>
                            <small class="text-muted">{{ count($about['detail']['lecturers'] ?? []) }} orang</small>
                        </div>
                    </div>
                    <div class="staff-list" id="lecturers-list">
                        @foreach(($about['detail']['lecturers'] ?? [
                            'Prof. Dr. Ahmad Santoso, M.Kom.',
                            'Dr. Siti Aminah, M.T.',
                            'Dr. Budi Raharjo, M.Sc.',
                            'Dian Pratiwi, M.Kom.',
                            'Rizki Pratama, M.T.I.',
                            'Dr. Maya Sari, M.Kom.',
                            'Ahmad Fauzi, M.T.I.',
                            'Sri Wahyuni, M.Sc.'
                        ]) as $lecturer)
                        <div class="staff-item">
                            <i class="fas fa-user-graduate"></i>
                            <span>{{ $lecturer }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="staff-category-card" style="--item-index: 1;">
                    <div class="staff-category-header d-flex align-items-center">
                        <i class="fas fa-user-tie me-3 fs-4 text-primary"></i>
                        <div>
                            <h5 class="m-0">Staf Administrasi</h5>
                            <small class="text-muted">{{ count($about['detail']['staff'] ?? []) }} orang</small>
                        </div>
                    </div>
                    <div class="staff-list" id="staff-list">
                        @foreach(($about['detail']['staff'] ?? [
                            'Maya Sari, S.Adm. - Kepala Tata Usaha',
                            'Rudi Hermawan - Staf Administrasi',
                            'Sari Indah, A.Md. - Staf Akademik',
                            'Budi Santoso - Staf Keuangan',
                            'Anita Wijaya - Staf Perpustakaan'
                        ]) as $staff)
                        <div class="staff-item">
                            <i class="fas fa-user"></i>
                            <span>{{ $staff }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if(auth()->check() && auth()->user()->role === 'admin')
    <!-- Modal Edit Informasi -->
    <div class="modal fade" id="editAboutModal" tabindex="-1" aria-labelledby="editAboutModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editAboutModalLabel">Edit Informasi JTIK</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Form edit akan ditambahkan di sini -->
                </div>
            </div>
        </div>
    </div>
@endif

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animasi On-Scroll
    const observerOptions = {
        root: null,
        rootMargin: '0px',
        threshold: 0.1
    };

    const observer = new IntersectionObserver((entries, observer) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                const delay = (entry.target.style.getPropertyValue('--item-index') || 0) * 100;
                setTimeout(() => {
                    entry.target.classList.add('is-visible');
                }, delay);
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    document.querySelectorAll('[data-animate]').forEach(el => {
        observer.observe(el);
    });

    // Improved Slider Functionality dengan inertia
    function initSliderTouch(sliderContainer) {
        let isDown = false;
        let startX;
        let scrollLeft;
        let velocity = 0;
        let animationFrame;

        function smoothScroll() {
            sliderContainer.scrollLeft += velocity;
            velocity *= 0.95; // friction
            
            if (Math.abs(velocity) > 0.5) {
                animationFrame = requestAnimationFrame(smoothScroll);
            } else {
                cancelAnimationFrame(animationFrame);
            }
        }

        sliderContainer.addEventListener('mousedown', (e) => {
            isDown = true;
            sliderContainer.classList.add('grabbing');
            startX = e.pageX - sliderContainer.offsetLeft;
            scrollLeft = sliderContainer.scrollLeft;
            cancelAnimationFrame(animationFrame);
        });

        sliderContainer.addEventListener('mouseleave', () => {
            isDown = false;
            sliderContainer.classList.remove('grabbing');
            if (Math.abs(velocity) > 1) {
                animationFrame = requestAnimationFrame(smoothScroll);
            }
        });

        sliderContainer.addEventListener('mouseup', () => {
            isDown = false;
            sliderContainer.classList.remove('grabbing');
            if (Math.abs(velocity) > 1) {
                animationFrame = requestAnimationFrame(smoothScroll);
            }
        });

        sliderContainer.addEventListener('mousemove', (e) => {
            if (!isDown) return;
            e.preventDefault();
            const x = e.pageX - sliderContainer.offsetLeft;
            const walk = (x - startX) * 2;
            const prevScrollLeft = sliderContainer.scrollLeft;
            sliderContainer.scrollLeft = scrollLeft - walk;
            velocity = sliderContainer.scrollLeft - prevScrollLeft;
        });

        // Touch events
        sliderContainer.addEventListener('touchstart', (e) => {
            isDown = true;
            startX = e.touches[0].pageX - sliderContainer.offsetLeft;
            scrollLeft = sliderContainer.scrollLeft;
            cancelAnimationFrame(animationFrame);
        });

        sliderContainer.addEventListener('touchend', () => {
            isDown = false;
            if (Math.abs(velocity) > 1) {
                animationFrame = requestAnimationFrame(smoothScroll);
            }
        });

        sliderContainer.addEventListener('touchmove', (e) => {
            if (!isDown) return;
            const x = e.touches[0].pageX - sliderContainer.offsetLeft;
            const walk = (x - startX) * 2;
            const prevScrollLeft = sliderContainer.scrollLeft;
            sliderContainer.scrollLeft = scrollLeft - walk;
            velocity = sliderContainer.scrollLeft - prevScrollLeft;
        });
    }

    // Inisialisasi slider
    document.querySelectorAll('.slider-container, .staff-slider-container').forEach(slider => {
        initSliderTouch(slider);
    });

    // Auto-scroll indicators
    function toggleScrollIndicators() {
        const staffLists = document.querySelectorAll('.staff-list');
        staffLists.forEach(list => {
            const hasScroll = list.scrollHeight > list.clientHeight;
            list.parentElement.classList.toggle('has-scroll', hasScroll);
        });
    }

    toggleScrollIndicators();
    window.addEventListener('resize', toggleScrollIndicators);
});
</script>
@endpush