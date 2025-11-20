<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login - Dasher</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #0f172a;
            color: #ffffff;
            min-height: 100vh;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            overflow-x: hidden;
        }

        .login-container {
            display: grid;
            grid-template-columns: 1fr 1.2fr;
            min-height: 100vh;
        }

        /* Left Panel - Brand Section */
        .brand-panel {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
        }

        .brand-panel::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 20% 80%, rgba(56, 189, 248, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(139, 92, 246, 0.1) 0%, transparent 50%);
        }

        .brand-header {
            position: relative;
            z-index: 2;
        }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 3rem;
        }

        .logo-img {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: linear-gradient(135deg, #3b82f6, #8b5cf6);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 1.2rem;
        }

        .brand-name {
            font-size: 1.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, #f8fafc, #cbd5e1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            max-width: 400px;
        }

        .hero-title {
            font-size: 3rem;
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 1.5rem;
            background: linear-gradient(135deg, #f8fafc, #94a3b8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-subtitle {
            font-size: 1.1rem;
            color: #94a3b8;
            line-height: 1.6;
            margin-bottom: 2rem;
        }

        .features-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            margin-bottom: 3rem;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            color: #cbd5e1;
            font-size: 0.95rem;
        }

        .feature-icon {
            width: 24px;
            height: 24px;
            background: rgba(59, 130, 246, 0.1);
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #3b82f6;
            font-size: 0.8rem;
        }

        .floating-elements {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
        }

        .floating-element {
            position: absolute;
            border-radius: 12px;
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(139, 92, 246, 0.1));
            border: 1px solid rgba(255, 255, 255, 0.05);
            animation: float 6s ease-in-out infinite;
        }

        .element-1 {
            width: 80px;
            height: 80px;
            top: 20%;
            left: 10%;
            animation-delay: 0s;
        }

        .element-2 {
            width: 60px;
            height: 60px;
            top: 60%;
            left: 80%;
            animation-delay: -2s;
        }

        .element-3 {
            width: 100px;
            height: 100px;
            top: 70%;
            left: 15%;
            animation-delay: -4s;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0) rotate(0deg);
            }
            50% {
                transform: translateY(-20px) rotate(180deg);
            }
        }

        /* Right Panel - Login Form */
        .login-panel {
            background: #ffffff;
            padding: 3rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            width: 100%;
            max-width: 400px;
        }

        .login-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .login-title {
            font-size: 2rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 0.5rem;
        }

        .login-subtitle {
            color: #64748b;
            font-size: 1rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .input-group {
            position: relative;
        }

        .form-control {
            width: 100%;
            padding: 1rem 1rem 1rem 3rem;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            background: #ffffff;
            font-size: 1rem;
            color: #0f172a;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .form-control:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            transform: translateY(-1px);
        }

        .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #64748b;
            transition: all 0.3s ease;
            z-index: 2;
        }

        .form-control:focus + .input-icon {
            color: #3b82f6;
        }

        .password-toggle {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #64748b;
            cursor: pointer;
            transition: all 0.3s ease;
            z-index: 2;
        }

        .password-toggle:hover {
            color: #3b82f6;
        }

        .btn-login {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1rem;
            width: 100%;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(59, 130, 246, 0.3);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .btn-back {
            background: transparent;
            color: #64748b;
            border: 2px solid #e2e8f0;
            padding: 1rem 2rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1rem;
            width: 100%;
            transition: all 0.3s ease;
            margin-top: 1rem;
        }

        .btn-back:hover {
            border-color: #3b82f6;
            color: #3b82f6;
            transform: translateY(-1px);
        }

        .alert {
            border: none;
            border-radius: 12px;
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
            font-weight: 500;
            animation: slideIn 0.5s ease;
        }

        .alert-danger {
            background: linear-gradient(135deg, #fee2e2, #fecaca);
            color: #dc2626;
            border-left: 4px solid #ef4444;
        }

        .alert-success {
            background: linear-gradient(135deg, #dcfce7, #bbf7d0);
            color: #16a34a;
            border-left: 4px solid #22c55e;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* Loading animation */
        .btn-loading {
            position: relative;
            color: transparent;
        }

        .btn-loading::after {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            top: 50%;
            left: 50%;
            margin-left: -10px;
            margin-top: -10px;
            border: 2px solid #ffffff;
            border-radius: 50%;
            border-top-color: transparent;
            animation: spin 1s ease infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .login-container {
                grid-template-columns: 1fr;
            }
            
            .brand-panel {
                display: none;
            }
            
            .login-panel {
                padding: 2rem;
            }
        }

        @media (max-width: 480px) {
            .login-panel {
                padding: 1.5rem;
            }
            
            .login-title {
                font-size: 1.75rem;
            }
            
            .hero-title {
                font-size: 2.5rem;
            }
        }

        /* Additional Animations */
        .slide-in-left {
            animation: slideInLeft 0.8s ease-out;
        }

        .slide-in-right {
            animation: slideInRight 0.8s ease-out;
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* Status Indicator */
        .status-indicator {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #64748b;
            font-size: 0.9rem;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid #e2e8f0;
        }

        .status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #10b981;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.5;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Left Panel - Brand & Information -->
        <div class="brand-panel">
            <div class="floating-elements">
                <div class="floating-element element-1"></div>
                <div class="floating-element element-2"></div>
                <div class="floating-element element-3"></div>
            </div>
            
            <div class="brand-header">
                <div class="brand-logo">
                    <div class="logo-img">                            <img src="{{ asset('img/logo1.png') }}" alt="Logo" class="brand-img img-fluid mx-auto d-block" />
</div>
                    <div class="brand-name">Dasher</div>
                </div>
            </div>

            <div class="hero-content slide-in-left">
                <h1 class="hero-title">Sistem Manajemen Ruangan Modern</h1>
                <p class="hero-subtitle">
                    Kelola dan pantau penggunaan ruangan dengan mudah. Akses real-time, 
                    booking otomatis, dan kontrol penuh dalam satu platform.
                </p>

                <div class="features-list">
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-bolt"></i>
                        </div>
                        <span>Real-time Monitoring</span>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <span>Keamanan Terjamin</span>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <span>Responsive Design</span>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <span>Analitik Cerdas</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Panel - Login Form -->
        <div class="login-panel">
            <div class="login-card slide-in-right">
                <div class="login-header">
                    <h1 class="login-title">Masuk ke Dasher</h1>
                    <p class="login-subtitle">Silakan masuk untuk mengakses dashboard</p>
                </div>

                @if($errors->any())
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle me-2"></i>{{ $errors->first() }}
                    </div>
                @endif

                @if(session('success'))
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" id="loginForm">
                    @csrf
                    
                    <div class="form-group">
                        <label for="username" class="form-label" style="color: #374151; font-weight: 600; margin-bottom: 0.5rem; display: block;">
                            Username
                        </label>
                        <div class="input-group">
                            <input id="username" type="text" name="username" class="form-control" 
                                   placeholder="Masukkan username" required />
                            <i class="fas fa-user input-icon"></i>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label" style="color: #374151; font-weight: 600; margin-bottom: 0.5rem; display: block;">
                            Password
                        </label>
                        <div class="input-group">
                            <input id="password" type="password" name="password" class="form-control" 
                                   placeholder="Masukkan password" required />
                            <i class="fas fa-lock input-icon"></i>
                            <button type="button" class="password-toggle" id="passwordToggle">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-login" id="loginButton">
                            <i class="fas fa-sign-in-alt me-2"></i> Masuk ke Dashboard
                        </button>
                        
                        <a href="{{ route('home') }}" class="btn btn-back">
                            <i class="fas fa-arrow-left me-2"></i> Kembali ke Beranda
                        </a>
                    </div>
                </form>

                <div class="status-indicator">
                    <div class="status-dot"></div>
                    <span>Sistem berjalan normal</span>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Password toggle functionality
            const passwordToggle = document.getElementById('passwordToggle');
            const passwordInput = document.getElementById('password');
            
            passwordToggle.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
            });

            // Form submission animation
            const loginForm = document.getElementById('loginForm');
            const loginButton = document.getElementById('loginButton');
            
            loginForm.addEventListener('submit', function(e) {
                const username = document.getElementById('username').value;
                const password = document.getElementById('password').value;
                
                if (username && password) {
                    loginButton.classList.add('btn-loading');
                    loginButton.disabled = true;
                    loginButton.innerHTML = 'Memproses...';
                    
                    // Allow form to submit naturally
                }
            });

            // Input animations
            const inputs = document.querySelectorAll('.form-control');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.style.transform = 'translateY(-2px)';
                });
                
                input.addEventListener('blur', function() {
                    this.parentElement.style.transform = 'translateY(0)';
                });
            });

            // Add floating animation to brand panel elements
            const floatingElements = document.querySelectorAll('.floating-element');
            floatingElements.forEach((element, index) => {
                element.style.animationDelay = `${index * 2}s`;
            });

            // Add typing effect to placeholder
            let placeholderIndex = 0;
            const placeholders = [
                "Masukkan username Anda...",
                "Username...",
                "Nama pengguna..."
            ];
            
            setInterval(() => {
                const usernameInput = document.getElementById('username');
                if (usernameInput && !usernameInput.matches(':focus')) {
                    usernameInput.placeholder = placeholders[placeholderIndex];
                    placeholderIndex = (placeholderIndex + 1) % placeholders.length;
                }
            }, 3000);
        });

        // Add ripple effect to buttons
        document.addEventListener('click', function(e) {
            if (e.target.matches('.btn-login, .btn-back')) {
                createRipple(e);
            }
        });

        function createRipple(event) {
            const button = event.currentTarget;
            const circle = document.createElement('span');
            const diameter = Math.max(button.clientWidth, button.clientHeight);
            const radius = diameter / 2;
            
            circle.style.width = circle.style.height = diameter + 'px';
            circle.style.left = (event.clientX - button.getBoundingClientRect().left - radius) + 'px';
            circle.style.top = (event.clientY - button.getBoundingClientRect().top - radius) + 'px';
            circle.classList.add('ripple');
            
            const ripple = button.getElementsByClassName('ripple')[0];
            if (ripple) {
                ripple.remove();
            }
            
            button.appendChild(circle);
            
            setTimeout(() => {
                circle.remove();
            }, 600);
        }

        // Add CSS for ripple effect
        const style = document.createElement('style');
        style.textContent = `
            .ripple {
                position: absolute;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.6);
                transform: scale(0);
                animation: ripple-animation 0.6s linear;
                pointer-events: none;
            }
            
            @keyframes ripple-animation {
                to {
                    transform: scale(4);
                    opacity: 0;
                }
            }
            
            .btn-login, .btn-back {
                position: relative;
                overflow: hidden;
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>