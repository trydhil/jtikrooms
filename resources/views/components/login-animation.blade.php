{{-- Toast Notification Animation --}}
<div class="login-toast" id="loginToast">
    <div class="toast-header">
        <div class="toast-icon">
            <i class="fas fa-check"></i>
        </div>
        <div class="toast-content">
            <h6 class="toast-title">
                @if(session('user_role') === 'admin')
                    <i class="fas fa-user-shield me-1"></i>Login Admin Berhasil
                @else
                    <i class="fas fa-chalkboard me-1"></i>Login Kelas Berhasil
                @endif
            </h6>
            <p class="toast-message">
                Selamat datang, <strong>{{ session('user_name') }}</strong>!
                <br>
                <small class="text-muted">
                    <i class="fas fa-clock me-1"></i>{{ session('login_time') }} WIB
                    <br>
                    <i class="fas fa-calendar me-1"></i>{{ session('login_date') }}
                </small>
            </p>
        </div>
    </div>
    <div class="toast-progress">
        <div class="toast-progress-bar"></div>
    </div>
</div>

{{-- Confetti Effect --}}
<div class="confetti-container" id="confettiContainer"></div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const toast = document.getElementById('loginToast');
    
    // Create confetti effect
    createConfetti();
    
    // Hapus toast setelah progress bar selesai
    setTimeout(() => {
        if (toast) {
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(-100px)';
            toast.style.transition = 'all 0.5s ease';
            
            setTimeout(() => {
                toast.remove();
            }, 500);
        }
    }, 5000); // Toast muncul selama 5 detik (lebih lama untuk baca tanggal)
    
    // Add special effects based on role
    @if(session('user_role') === 'admin')
        addAdminEffects();
    @else
        addKelasEffects();
    @endif
});

function createConfetti() {
    const container = document.getElementById('confettiContainer');
    if (!container) return;
    
    const colors = ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6'];
    
    for (let i = 0; i < 20; i++) {
        const confetti = document.createElement('div');
        confetti.className = `confetti confetti-${(i % 5) + 1}`;
        confetti.style.left = Math.random() * 100 + 'vw';
        confetti.style.animationDelay = (Math.random() * 0.5) + 's';
        
        container.appendChild(confetti);
        
        // Remove confetti after animation
        setTimeout(() => {
            confetti.remove();
        }, 3000);
    }
    
    // Remove container after all confetti is gone
    setTimeout(() => {
        if (container) container.remove();
    }, 3500);
}

function addAdminEffects() {
    // Add special styling for admin
    document.body.classList.add('admin-login');
    
    // Pulse effect on dashboard elements after a delay
    setTimeout(() => {
        const statsCards = document.querySelectorAll('.stat-card');
        if (statsCards.length > 0) {
            statsCards.forEach((card, index) => {
                setTimeout(() => {
                    card.classList.add('dashboard-pulse');
                    setTimeout(() => card.classList.remove('dashboard-pulse'), 2000);
                }, index * 300);
            });
        }
    }, 1000);
}

function addKelasEffects() {
    // Add special styling for kelas
    document.body.classList.add('kelas-login');
    
    // Highlight booking section
    setTimeout(() => {
        const bookingSection = document.querySelector('.active-booking-section');
        const welcomeCard = document.querySelector('.welcome-card');
        
        if (bookingSection) {
            bookingSection.classList.add('dashboard-pulse');
            setTimeout(() => bookingSection.classList.remove('dashboard-pulse'), 2000);
        }
        
        if (welcomeCard) {
            welcomeCard.classList.add('dashboard-pulse');
            setTimeout(() => welcomeCard.classList.remove('dashboard-pulse'), 2000);
        }
    }, 1000);
}
</script>