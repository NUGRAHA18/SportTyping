<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SportTyping') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <style>
        :root {
            /* Colors */
            --bg-primary: #1a0d2e;
            --bg-secondary: #2c1b47;
            --bg-card: rgba(44, 27, 71, 0.4);
            --accent-pink: #ff6b9d;
            --accent-cyan: #00d4ff;
            --accent-purple: #8b5cf6;
            --text-primary: #ffffff;
            --text-secondary: #b4a7d1;
            --text-muted: #9ca3af;
            --success: #10b981;
            --warning: #f59e0b;
            --error: #ef4444;
            --info: #3b82f6;
            
            /* Gradients */
            --gradient-hero: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --gradient-card: linear-gradient(145deg, rgba(139, 92, 246, 0.1), rgba(255, 107, 157, 0.1));
            --gradient-accent: linear-gradient(90deg, #ff6b9d 0%, #00d4ff 100%);
            --gradient-button: linear-gradient(45deg, #ff6b9d, #8b5cf6);
            
            /* Spacing & Effects */
            --border-radius: 12px;
            --border-radius-lg: 20px;
            --blur-amount: 20px;
            --shadow-glow: 0 0 30px rgba(139, 92, 246, 0.3);
            --shadow-card: 0 8px 32px rgba(0, 0, 0, 0.3);
            
            /* Typography */
            --font-primary: 'Poppins', sans-serif;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: var(--font-primary);
            background: var(--bg-primary);
            color: var(--text-primary);
            overflow-x: hidden;
            line-height: 1.6;
            min-height: 100vh;
        }

        /* Animated Background */
        .animated-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            background: var(--bg-primary);
        }

        .animated-bg::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at 20% 20%, rgba(139, 92, 246, 0.1) 0%, transparent 50%),
                        radial-gradient(circle at 80% 80%, rgba(255, 107, 157, 0.1) 0%, transparent 50%),
                        radial-gradient(circle at 40% 40%, rgba(0, 212, 255, 0.05) 0%, transparent 50%);
            animation: float 20s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            33% { transform: translateY(-20px) rotate(1deg); }
            66% { transform: translateY(10px) rotate(-1deg); }
        }

        /* Main App Container */
        #app {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Navigation */
        .navbar {
            background: rgba(44, 27, 71, 0.4);
            backdrop-filter: blur(var(--blur-amount));
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 1000;
            transition: all 0.3s ease;
            box-shadow: var(--shadow-card);
        }

        .navbar-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar-brand {
            font-size: 1.5rem;
            font-weight: 700;
            background: var(--gradient-accent);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .navbar-nav {
            display: flex;
            align-items: center;
            gap: 2rem;
            list-style: none;
        }

        .nav-link {
            color: var(--text-secondary);
            text-decoration: none;
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: var(--border-radius);
            transition: all 0.3s ease;
            position: relative;
        }

        .nav-link:hover {
            color: var(--accent-pink);
            background: rgba(255, 107, 157, 0.1);
        }

        .nav-link.active {
            color: var(--accent-pink);
            background: rgba(255, 107, 157, 0.15);
        }

        /* Mobile Menu Toggle */
        .mobile-toggle {
            display: none;
            background: none;
            border: none;
            color: var(--text-primary);
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: var(--border-radius);
            transition: all 0.3s ease;
        }

        .mobile-toggle:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        /* Dropdown */
        .dropdown {
            position: relative;
        }

        .dropdown-toggle {
            background: none;
            border: none;
            color: var(--text-secondary);
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: var(--border-radius);
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }

        .dropdown-toggle:hover {
            color: var(--accent-pink);
            background: rgba(255, 107, 157, 0.1);
        }

        .dropdown-menu {
            position: absolute;
            top: calc(100% + 0.5rem);
            right: 0;
            background: rgba(44, 27, 71, 0.9);
            backdrop-filter: blur(var(--blur-amount));
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: var(--border-radius);
            padding: 0.5rem;
            min-width: 200px;
            box-shadow: var(--shadow-card);
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s ease;
            z-index: 1000;
        }

        .dropdown.show .dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .dropdown-item {
            display: block;
            padding: 0.75rem 1rem;
            color: var(--text-secondary);
            text-decoration: none;
            border-radius: calc(var(--border-radius) - 2px);
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .dropdown-item:hover {
            background: rgba(255, 107, 157, 0.1);
            color: var(--accent-pink);
        }

        .dropdown-divider {
            height: 1px;
            background: rgba(255, 255, 255, 0.1);
            margin: 0.5rem 0;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            padding-top: 2rem;
        }

        /* Alerts */
        .alert {
            padding: 1rem 1.5rem;
            border-radius: var(--border-radius);
            margin-bottom: 1rem;
            border: 1px solid transparent;
            backdrop-filter: blur(10px);
            font-weight: 500;
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.1);
            border-color: rgba(16, 185, 129, 0.3);
            color: var(--success);
        }

        .alert-info {
            background: rgba(59, 130, 246, 0.1);
            border-color: rgba(59, 130, 246, 0.3);
            color: var(--info);
        }

        .alert-warning {
            background: rgba(245, 158, 11, 0.1);
            border-color: rgba(245, 158, 11, 0.3);
            color: var(--warning);
        }

        .alert-error, .alert-danger {
            background: rgba(239, 68, 68, 0.1);
            border-color: rgba(239, 68, 68, 0.3);
            color: var(--error);
        }

        /* Container */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        /* Cards */
        .card {
            background: var(--gradient-card);
            backdrop-filter: blur(var(--blur-amount));
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow-card);
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 40px rgba(139, 92, 246, 0.2);
        }

        .card-header {
            background: rgba(255, 255, 255, 0.05);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding: 1.5rem 2rem;
            font-weight: 600;
            font-size: 1.1rem;
        }

        .card-body {
            padding: 2rem;
        }

        /* Buttons */
        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: var(--border-radius);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.95rem;
            font-family: var(--font-primary);
        }

        .btn-primary {
            background: var(--gradient-button);
            color: white;
            box-shadow: 0 4px 15px rgba(255, 107, 157, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(255, 107, 157, 0.4);
            color: white;
            text-decoration: none;
        }

        .btn-outline-primary {
            background: transparent;
            color: var(--accent-pink);
            border: 2px solid var(--accent-pink);
        }

        .btn-outline-primary:hover {
            background: var(--accent-pink);
            color: white;
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.1);
            color: var(--text-primary);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
        }

        /* Forms */
        .form-label {
            color: var(--text-primary);
            font-weight: 500;
            margin-bottom: 0.5rem;
            display: block;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: var(--border-radius);
            color: var(--text-primary);
            padding: 0.75rem 1rem;
            width: 100%;
            transition: all 0.3s ease;
            font-family: var(--font-primary);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--accent-pink);
            background: rgba(255, 255, 255, 0.1);
            box-shadow: 0 0 0 3px rgba(255, 107, 157, 0.1);
        }

        .form-control::placeholder {
            color: var(--text-muted);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .navbar-container {
                padding: 0 1rem;
            }

            .mobile-toggle {
                display: block;
            }

            .navbar-nav {
                position: absolute;
                top: calc(100% + 1rem);
                left: 1rem;
                right: 1rem;
                background: rgba(44, 27, 71, 0.95);
                backdrop-filter: blur(var(--blur-amount));
                border: 1px solid rgba(255, 255, 255, 0.1);
                border-radius: var(--border-radius);
                flex-direction: column;
                gap: 0;
                padding: 1rem;
                box-shadow: var(--shadow-card);
                opacity: 0;
                visibility: hidden;
                transform: translateY(-10px);
                transition: all 0.3s ease;
            }

            .navbar-nav.show {
                opacity: 1;
                visibility: visible;
                transform: translateY(0);
            }

            .nav-link {
                padding: 1rem;
                width: 100%;
                text-align: center;
                border-radius: calc(var(--border-radius) - 2px);
            }

            .dropdown-menu {
                position: static;
                opacity: 1;
                visibility: visible;
                transform: none;
                box-shadow: none;
                background: rgba(255, 255, 255, 0.05);
                margin-top: 0.5rem;
            }

            .container {
                padding: 0 1rem;
            }

            .card-body {
                padding: 1.5rem;
            }
        }

        /* Utility Classes */
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .mb-3 { margin-bottom: 1rem; }
        .mb-4 { margin-bottom: 1.5rem; }
        .mb-5 { margin-bottom: 2rem; }
        .mt-3 { margin-top: 1rem; }
        .mt-4 { margin-top: 1.5rem; }
        .mt-5 { margin-top: 2rem; }
        .d-flex { display: flex; }
        .justify-content-between { justify-content: space-between; }
        .align-items-center { align-items: center; }
        .gap-2 { gap: 0.5rem; }
        .gap-3 { gap: 1rem; }
    </style>
</head>
<body>
    <div class="animated-bg"></div>
    
    <div id="app">
        <nav class="navbar">
            <div class="navbar-container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <i class="fas fa-keyboard"></i>
                    {{ config('app.name', 'SportTyping') }}
                </a>
                
                <button class="mobile-toggle" id="mobileToggle">
                    <i class="fas fa-bars"></i>
                </button>

                <ul class="navbar-nav" id="navbarNav">
                    <!-- Left Side Of Navbar -->
                    <li><a class="nav-link" href="{{ route('guest.practice') }}">{{ __('Practice') }}</a></li>
                    <li><a class="nav-link" href="{{ route('guest.lessons') }}">{{ __('Lessons') }}</a></li>
                    <li><a class="nav-link" href="{{ route('guest.competitions') }}">{{ __('Competitions') }}</a></li>

                    <!-- Right Side Of Navbar -->
                    @guest
                        <li><a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a></li>
                        <li><a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a></li>
                    @else
                        <li class="dropdown">
                            <button class="dropdown-toggle" id="navbarDropdown">
                                <i class="fas fa-user-circle"></i>
                                {{ Auth::user()->username }}
                                <i class="fas fa-chevron-down"></i>
                            </button>

                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="{{ route('dashboard') }}">
                                    <i class="fas fa-tachometer-alt"></i>
                                    {{ __('Dashboard') }}
                                </a>
                                <a class="dropdown-item" href="{{ route('profile.show') }}">
                                    <i class="fas fa-user"></i>
                                    {{ __('Profile') }}
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fas fa-sign-out-alt"></i>
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endguest
                </ul>
            </div>
        </nav>

        <main class="main-content">
            @if(session('success'))
                <div class="container">
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="container">
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ session('error') }}
                    </div>
                </div>
            @endif

            @if(session('info'))
                <div class="container">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        {{ session('info') }}
                    </div>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <script>
        // Mobile menu toggle
        document.addEventListener('DOMContentLoaded', function() {
            const mobileToggle = document.getElementById('mobileToggle');
            const navbarNav = document.getElementById('navbarNav');
            const dropdown = document.querySelector('.dropdown');
            const dropdownToggle = document.getElementById('navbarDropdown');

            // Mobile menu
            if (mobileToggle && navbarNav) {
                mobileToggle.addEventListener('click', function() {
                    navbarNav.classList.toggle('show');
                    const icon = mobileToggle.querySelector('i');
                    icon.classList.toggle('fa-bars');
                    icon.classList.toggle('fa-times');
                });
            }

            // Dropdown menu
            if (dropdown && dropdownToggle) {
                dropdownToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    dropdown.classList.toggle('show');
                });

                // Close dropdown when clicking outside
                document.addEventListener('click', function(e) {
                    if (!dropdown.contains(e.target)) {
                        dropdown.classList.remove('show');
                    }
                });
            }

            // Close mobile menu when clicking outside
            document.addEventListener('click', function(e) {
                if (navbarNav && !navbarNav.contains(e.target) && !mobileToggle.contains(e.target)) {
                    navbarNav.classList.remove('show');
                    const icon = mobileToggle.querySelector('i');
                    icon.classList.add('fa-bars');
                    icon.classList.remove('fa-times');
                }
            });

            // Auto-hide alerts after 5 seconds
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.opacity = '0';
                    alert.style.transform = 'translateY(-20px)';
                    setTimeout(() => {
                        alert.remove();
                    }, 300);
                }, 5000);
            });

            // Active nav link highlighting
            const currentPath = window.location.pathname;
            const navLinks = document.querySelectorAll('.nav-link');
            
            navLinks.forEach(link => {
                if (link.getAttribute('href') === currentPath) {
                    link.classList.add('active');
                }
            });
        });
    </script>
</body>
</html>