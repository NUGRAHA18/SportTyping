<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SportTyping') }}</title>

    <!-- Modern Sport Fonts - Same as Welcome -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" defer></script>
    <script src="{{ asset('js/app.js') }}" defer></script>
    
    <style>
        :root {
            /* Modern Sport Color Palette - From Welcome.blade.php */
            --bg-primary: #ffffff;
            --bg-secondary: #f8fafc;
            --bg-tertiary: #f1f5f9;
            --bg-card: #ffffff;
            --accent-primary: #3b82f6;     /* Champion blue */
            --accent-secondary: #f59e0b;   /* Gold medal */
            --accent-success: #10b981;     /* Victory green */
            --accent-danger: #ef4444;      /* Error red */
            --accent-purple: #8b5cf6;      /* Premium purple */
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --text-muted: #94a3b8;
            --border-light: #e2e8f0;
            --border-medium: #cbd5e1;
            
            /* Modern Sport Effects */
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            --champion-gradient: linear-gradient(135deg, #3b82f6, #1d4ed8);
            --medal-gradient: linear-gradient(135deg, #fbbf24, #f59e0b, #d97706);
            --victory-gradient: linear-gradient(135deg, #10b981, #059669);
            --sport-glow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            
            /* Modern Sport Typography */
            --font-primary: 'Inter', system-ui, -apple-system, sans-serif;
            --font-display: 'Space Grotesk', system-ui, -apple-system, sans-serif;
            
            /* Borders & Radius */
            --border-radius-sm: 0.375rem;
            --border-radius: 0.5rem;
            --border-radius-lg: 0.75rem;
            --border-radius-xl: 1rem;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: var(--font-primary) !important;
            background: var(--bg-primary);
            color: var(--text-primary);
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* Enhanced Navbar Styling */
        .navbar {
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border-light);
            box-shadow: var(--shadow-sm);
            transition: all 0.3s ease;
        }

        .navbar.scrolled {
            background: rgba(255, 255, 255, 0.98) !important;
            box-shadow: var(--shadow-md);
            border-bottom-color: var(--accent-primary);
        }

        .navbar-brand {
            font-family: var(--font-display) !important;
            font-size: 1.5rem !important;
            font-weight: 700 !important;
            color: var(--accent-primary) !important;
            transition: all 0.3s ease;
        }

        .navbar-brand:hover {
            color: var(--accent-primary) !important;
            transform: scale(1.02);
        }

        .navbar-brand::before {
            content: '🏆 ';
            margin-right: 0.5rem;
        }

        .nav-link {
            color: var(--text-secondary) !important;
            font-weight: 500 !important;
            padding: 0.75rem 1rem !important;
            border-radius: var(--border-radius);
            transition: all 0.3s ease;
            position: relative;
        }

        .nav-link::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background: var(--champion-gradient);
            transition: all 0.3s ease;
            transform: translateX(-50%);
            border-radius: 1px;
        }

        .nav-link:hover {
            color: var(--accent-primary) !important;
            background: rgba(59, 130, 246, 0.05);
        }

        .nav-link:hover::before {
            width: 80%;
        }

        .nav-link.active {
            color: var(--accent-primary) !important;
            background: rgba(59, 130, 246, 0.1);
        }

        .nav-link.active::before {
            width: 80%;
        }

        /* Enhanced Dropdown */
        .dropdown-toggle::after {
            color: var(--accent-primary);
        }

        .dropdown-menu {
            background: var(--bg-card);
            border: 1px solid var(--border-light);
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow-xl);
            margin-top: 0.5rem;
        }

        .dropdown-item {
            color: var(--text-secondary);
            padding: 0.75rem 1.25rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .dropdown-item:hover {
            background: rgba(59, 130, 246, 0.05);
            color: var(--accent-primary);
        }

        .dropdown-item:focus {
            background: rgba(59, 130, 246, 0.05);
            color: var(--accent-primary);
        }

        /* Main Content Area */
        main {
            background: var(--bg-primary);
            min-height: calc(100vh - 76px);
        }

        /* Container Enhancement */
        .container {
            max-width: 1400px;
        }

        /* Enhanced Buttons */
        .btn-primary {
            background: var(--champion-gradient);
            border: none;
            color: white;
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            border-radius: var(--border-radius);
            transition: all 0.3s ease;
            box-shadow: var(--shadow-sm);
        }

        .btn-primary:hover {
            background: var(--champion-gradient);
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
            color: white;
        }

        .btn-secondary {
            background: transparent;
            border: 2px solid var(--accent-primary);
            color: var(--accent-primary);
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            border-radius: var(--border-radius);
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            background: var(--accent-primary);
            color: white;
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .btn-success {
            background: var(--victory-gradient);
            border: none;
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-success:hover {
            background: var(--victory-gradient);
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
            color: white;
        }

        .btn-warning {
            background: var(--medal-gradient);
            border: none;
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-warning:hover {
            background: var(--medal-gradient);
            transform: translateY(-2px); 
            box-shadow: var(--shadow-md);
            color: white;
        }

        /* Enhanced Cards */
        .card {
            background: var(--bg-card);
            border: 1px solid var(--border-light);
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow-sm);
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .card-header {
            background: var(--bg-secondary);
            border-bottom: 1px solid var(--border-light);
            color: var(--text-primary);
            font-weight: 600;
        }

        /* Alert Enhancements */
        .alert {
            border-radius: var(--border-radius);
            border: none;
            font-weight: 500;
            padding: 1rem 1.5rem;
        }

        .alert-success {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(16, 185, 129, 0.05));
            color: var(--accent-success);
            border: 1px solid rgba(16, 185, 129, 0.2);
        }

        .alert-danger {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.1), rgba(239, 68, 68, 0.05));
            color: var(--accent-danger);
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        .alert-warning {
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.1), rgba(245, 158, 11, 0.05));
            color: var(--accent-secondary);
            border: 1px solid rgba(245, 158, 11, 0.2);
        }

        .alert-info {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(59, 130, 246, 0.05));
            color: var(--accent-primary);
            border: 1px solid rgba(59, 130, 246, 0.2);
        }

        /* Form Enhancements */
        .form-control {
            border: 2px solid var(--border-light);
            border-radius: var(--border-radius);
            padding: 0.75rem 1rem;
            font-weight: 500;
            transition: all 0.3s ease;
            background: var(--bg-card);
        }

        .form-control:focus {
            border-color: var(--accent-primary);
            box-shadow: var(--sport-glow);
            background: var(--bg-card);
        }

        .form-label {
            color: var(--text-primary);
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        /* Table Enhancements */
        .table {
            background: var(--bg-card);
            border-radius: var(--border-radius-lg);
            overflow: hidden;
            box-shadow: var(--shadow-sm);
        }

        .table th {
            background: var(--bg-secondary);
            color: var(--text-primary);
            font-weight: 600;
            border: none;
            padding: 1rem;
        }

        .table td {
            color: var(--text-secondary);
            border-color: var(--border-light);
            padding: 1rem;
        }

        /* Badge Enhancements */
        .badge {
            font-weight: 600;
            padding: 0.5rem 0.75rem;
            border-radius: var(--border-radius);
        }

        .badge.bg-primary {
            background: var(--champion-gradient) !important;
        }

        .badge.bg-success {
            background: var(--victory-gradient) !important;
        }

        .badge.bg-warning {
            background: var(--medal-gradient) !important;
        }

        /* Loading Animation */
        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(59, 130, 246, 0.3);
            border-radius: 50%;
            border-top-color: var(--accent-primary);
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Scroll Animation */
        .fade-in {
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.6s ease;
        }

        .fade-in.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* Responsive Enhancements */
        @media (max-width: 768px) {
            .container {
                padding: 0 1rem;
            }
            
            .navbar-brand {
                font-size: 1.25rem !important;
            }
            
            .nav-link {
                padding: 0.5rem 0.75rem !important;
            }
        }

        /* Custom Scroll Bar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--bg-secondary);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--champion-gradient);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--accent-primary);
        }
    </style>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light shadow-sm" id="navbar">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'SportTyping') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        @auth
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                                    <i class="fas fa-chart-line me-2"></i>{{ __('Dashboard') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('competitions.*') ? 'active' : '' }}" href="{{ route('competitions.index') }}">
                                    <i class="fas fa-racing-flag me-2"></i>{{ __('Compete') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('practice.*') ? 'active' : '' }}" href="{{ route('practice.index') }}">
                                    <i class="fas fa-keyboard me-2"></i>{{ __('Practice') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('lessons.*') ? 'active' : '' }}" href="{{ route('lessons.index') }}">
                                    <i class="fas fa-graduation-cap me-2"></i>{{ __('Lessons') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('leaderboards.*') ? 'active' : '' }}" href="{{ route('leaderboards.index') }}">
                                    <i class="fas fa-trophy me-2"></i>{{ __('Rankings') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('badges.*') ? 'active' : '' }}" href="{{ route('badges.index') }}">
                                    <i class="fas fa-medal me-2"></i>{{ __('Badges') }}
                                </a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('guest.practice') }}">
                                    <i class="fas fa-keyboard me-2"></i>{{ __('Practice') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('guest.lessons') }}">
                                    <i class="fas fa-graduation-cap me-2"></i>{{ __('Lessons') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('guest.competitions') }}">
                                    <i class="fas fa-racing-flag me-2"></i>{{ __('Competitions') }}
                                </a>
                            </li>
                        @endauth
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">
                                    <i class="fas fa-sign-in-alt me-2"></i>{{ __('Login') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">
                                    <i class="fas fa-user-plus me-2"></i>{{ __('Register') }}
                                </a>
                            </li>
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    <div class="d-flex align-items-center">
                                        <div class="me-2" style="width: 32px; height: 32px; background: var(--champion-gradient); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 0.875rem;">
                                            @if(Auth::user()->profile?->avatar)
                                                <img src="{{ asset('storage/' . Auth::user()->profile->avatar) }}" alt="{{ Auth::user()->username }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                                            @else
                                                {{ strtoupper(substr(Auth::user()->username, 0, 1)) }}
                                            @endif
                                        </div>
                                        <div class="d-none d-md-block">
                                            <div style="font-size: 0.9rem; font-weight: 600; color: var(--text-primary);">{{ Auth::user()->username }}</div>
                                            <div style="font-size: 0.75rem; color: var(--accent-secondary);">{{ Auth::user()->profile?->league?->name ?? 'Novice' }}</div>
                                        </div>
                                    </div>
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('dashboard') }}">
                                        <i class="fas fa-chart-line me-2"></i>{{ __('Dashboard') }}
                                    </a>
                                    <a class="dropdown-item" href="{{ route('profile.show') }}">
                                        <i class="fas fa-user me-2"></i>{{ __('Profile') }}
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <div class="dropdown-item-text">
                                        <small class="text-muted">
                                            <i class="fas fa-star me-2"></i>EXP: {{ number_format(Auth::user()->profile?->total_experience ?? 0) }}
                                        </small>
                                    </div>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt me-2"></i>{{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4 fade-in">
            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="container">
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="container">
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            @endif

            @if(session('warning'))
                <div class="container">
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        {{ session('warning') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            @endif

            @if(session('info'))
                <div class="container">
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <i class="fas fa-info-circle me-2"></i>
                        {{ session('info') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <script>
        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Page enter animation
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => {
                document.querySelector('.fade-in').classList.add('visible');
            }, 100);
        });

        // Auto-hide alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 5000);
            });
        });

        // Global CSRF token
        window.csrfToken = '{{ csrf_token() }}';
        
        // Global user data
        @auth
        window.userData = {
            id: {{ Auth::id() }},
            username: '{{ Auth::user()->username }}',
            avatar: {{ Auth::user()->profile?->avatar ? "'" . asset('storage/' . Auth::user()->profile->avatar) . "'" : 'null' }},
            league: '{{ Auth::user()->profile?->league?->name ?? 'Novice' }}',
            experience: {{ Auth::user()->profile?->total_experience ?? 0 }}
        };
        @else
        window.userData = null;
        @endauth

        // Global helper functions
        window.showNotification = function(message, type = 'info') {
            // Create Bootstrap alert
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
            alertDiv.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check' : type === 'danger' ? 'exclamation' : 'info'}-circle me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            // Insert at top of main content
            const main = document.querySelector('main');
            const container = document.createElement('div');
            container.className = 'container';
            container.appendChild(alertDiv);
            main.insertBefore(container, main.firstChild);
            
            // Auto-hide after 5 seconds
            setTimeout(() => {
                const bsAlert = new bootstrap.Alert(alertDiv);
                bsAlert.close();
            }, 5000);
        };

        window.formatNumber = function(num) {
            return new Intl.NumberFormat().format(num);
        };

        window.formatTime = function(seconds) {
            const mins = Math.floor(seconds / 60);
            const secs = seconds % 60;
            return `${mins}:${secs.toString().padStart(2, '0')}`;
        };
    </script>
</body>
</html>