<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SportTyping - Competitive Typing Platform</title>
    
    <!-- Modern Sport Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            /* Modern Sport Color Palette */
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
            font-family: var(--font-primary);
            background: var(--bg-primary);
            color: var(--text-primary);
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* Modern Sport Navigation */
        .navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border-light);
            padding: 1rem 0;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .navbar.scrolled {
            background: rgba(255, 255, 255, 0.98);
            box-shadow: var(--shadow-md);
            border-bottom-color: var(--accent-primary);
        }

        .navbar .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar .brand {
            font-family: var(--font-display);
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--accent-primary);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.3s ease;
        }

        .navbar .brand:hover {
            color: var(--accent-primary);
            transform: scale(1.02);
        }

        .navbar .brand i {
            font-size: 1.5rem;
            background: var(--champion-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .navbar .nav-links {
            display: flex;
            gap: 2rem;
            list-style: none;
        }

        .navbar .nav-links a {
            color: var(--text-secondary);
            text-decoration: none;
            font-weight: 500;
            font-size: 1rem;
            padding: 0.5rem 1rem;
            border-radius: var(--border-radius);
            transition: all 0.3s ease;
            position: relative;
        }

        .navbar .nav-links a::before {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 50%;
            width: 0;
            height: 2px;
            background: var(--champion-gradient);
            transition: all 0.3s ease;
            transform: translateX(-50%);
            border-radius: 1px;
        }

        .navbar .nav-links a:hover {
            color: var(--accent-primary);
            background: rgba(59, 130, 246, 0.05);
        }

        .navbar .nav-links a:hover::before {
            width: 80%;
        }

        /* Modern Sport Hero Section */
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
            padding: 0 2rem;
            background: 
                linear-gradient(135deg, 
                    rgba(59, 130, 246, 0.02) 0%,
                    rgba(248, 250, 252, 1) 50%,
                    rgba(16, 185, 129, 0.02) 100%
                );
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 20% 80%, rgba(59, 130, 246, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(16, 185, 129, 0.1) 0%, transparent 50%);
            z-index: 1;
        }

        .hero-content {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: center;
            z-index: 2;
            position: relative;
        }

        .hero-text {
            max-width: 600px;
        }

        .hero-badge {
            display: inline-block;
            background: var(--bg-card);
            border: 1px solid var(--border-medium);
            color: var(--accent-primary);
            padding: 0.5rem 1.25rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 2rem;
            box-shadow: var(--shadow-sm);
            position: relative;
            overflow: hidden;
        }

        .hero-badge::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(59, 130, 246, 0.1), transparent);
            transition: left 0.8s ease;
        }

        .hero-badge:hover::before {
            left: 100%;
        }

        .hero h1 {
            font-family: var(--font-display);
            font-size: clamp(2.5rem, 6vw, 4.5rem);
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 1.5rem;
            background: linear-gradient(135deg, var(--text-primary), var(--accent-primary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero .subtitle {
            font-size: 1.25rem;
            color: var(--text-secondary);
            margin-bottom: 2.5rem;
            font-weight: 400;
            line-height: 1.6;
        }

        .hero-stats {
            display: flex;
            gap: 2rem;
            margin: 2.5rem 0;
        }

        .hero-stat {
            text-align: left;
        }

        .hero-stat-number {
            display: block;
            font-family: var(--font-display);
            font-size: 2.25rem;
            font-weight: 700;
            background: var(--champion-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1.2;
        }

        .hero-stat-label {
            font-size: 0.875rem;
            color: var(--text-muted);
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .hero-buttons {
            display: flex;
            gap: 1rem;
            align-items: center;
            flex-wrap: wrap;
        }

        .hero-visual {
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
        }

        .trophy-display {
            width: 400px;
            height: 400px;
            background: var(--champion-gradient);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 8rem;
            color: white;
            box-shadow: var(--shadow-xl);
            position: relative;
            animation: trophyFloat 6s ease-in-out infinite;
        }

        .trophy-display::before {
            content: '';
            position: absolute;
            top: -20px;
            left: -20px;
            right: -20px;
            bottom: -20px;
            border-radius: 50%;
            background: linear-gradient(45deg, var(--accent-primary), var(--accent-success), var(--accent-secondary));
            z-index: -1;
            animation: trophyRotate 20s linear infinite;
        }

        @keyframes trophyFloat {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }

        @keyframes trophyRotate {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .floating-elements {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            overflow: hidden;
            pointer-events: none;
        }

        .floating-medal {
            position: absolute;
            width: 60px;
            height: 60px;
            background: var(--medal-gradient);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            animation: medalFloat 8s ease-in-out infinite;
            box-shadow: var(--shadow-lg);
        }

        .floating-medal:nth-child(1) {
            top: 10%;
            left: 10%;
            animation-delay: 0s;
        }

        .floating-medal:nth-child(2) {
            top: 20%;
            right: 15%;
            animation-delay: 2s;
        }

        .floating-medal:nth-child(3) {
            bottom: 30%;
            left: 5%;
            animation-delay: 4s;
        }

        @keyframes medalFloat {
            0%, 100% { 
                transform: translateY(0) rotate(0deg); 
                opacity: 0.7;
            }
            50% { 
                transform: translateY(-30px) rotate(180deg); 
                opacity: 1;
            }
        }

        /* Modern Sport Buttons */
        .btn {
            padding: 1rem 2rem;
            border-radius: var(--border-radius);
            text-decoration: none;
            font-family: var(--font-primary);
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
            border: 2px solid transparent;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            position: relative;
            overflow: hidden;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .btn:hover::before {
            left: 100%;
        }

        .btn-primary {
            background: var(--champion-gradient);
            color: white;
            box-shadow: var(--shadow-md);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .btn-secondary {
            background: transparent;
            color: var(--accent-primary);
            border-color: var(--accent-primary);
        }

        .btn-secondary:hover {
            background: var(--accent-primary);
            color: white;
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .btn-ghost {
            background: transparent;
            color: var(--text-secondary);
            border: none;
            text-decoration: underline;
            text-underline-offset: 4px;
            text-decoration-thickness: 2px;
            text-decoration-color: transparent;
            transition: all 0.3s ease;
        }

        .btn-ghost:hover {
            color: var(--accent-primary);
            text-decoration-color: var(--accent-primary);
        }

        /* Modern Sport Features Section */
        .features {
            padding: 6rem 2rem;
            background: var(--bg-secondary);
            position: relative;
        }

        .features-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .features-header {
            text-align: center;
            margin-bottom: 4rem;
        }

        .features-header h2 {
            font-family: var(--font-display);
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 1rem;
            background: linear-gradient(135deg, var(--text-primary), var(--accent-primary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .features-header p {
            color: var(--text-secondary);
            font-size: 1.25rem;
            max-width: 600px;
            margin: 0 auto;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
        }

        .feature-card {
            background: var(--bg-card);
            border-radius: var(--border-radius-lg);
            padding: 2.5rem;
            text-align: center;
            transition: all 0.3s ease;
            border: 1px solid var(--border-light);
            box-shadow: var(--shadow-sm);
            position: relative;
            overflow: hidden;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--champion-gradient);
            transform: translateX(-100%);
            transition: transform 0.5s ease;
        }

        .feature-card:hover::before {
            transform: translateX(0);
        }

        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-xl);
            border-color: var(--accent-primary);
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            background: var(--champion-gradient);
            border-radius: var(--border-radius-lg);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 2rem;
            color: white;
            box-shadow: var(--shadow-md);
            transition: all 0.3s ease;
        }

        .feature-card:hover .feature-icon {
            transform: scale(1.1) rotate(5deg);
        }

        .feature-card h3 {
            font-family: var(--font-display);
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: var(--text-primary);
        }

        .feature-card p {
            color: var(--text-secondary);
            line-height: 1.7;
        }

        /* Sport Performance Cards */
        .performance-section {
            padding: 6rem 2rem;
            background: var(--bg-primary);
        }

        .performance-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .performance-header {
            text-align: center;
            margin-bottom: 4rem;
        }

        .performance-header h2 {
            font-family: var(--font-display);
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--text-primary);
        }

        .performance-header p {
            color: var(--text-secondary);
            font-size: 1.1rem;
            max-width: 600px;
            margin: 0 auto;
        }

        .performance-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
        }

        .performance-card {
            background: var(--bg-card);
            border-radius: var(--border-radius-lg);
            padding: 2.5rem;
            text-align: center;
            border: 1px solid var(--border-light);
            transition: all 0.3s ease;
            position: relative;
            box-shadow: var(--shadow-sm);
        }

        .performance-card.gold {
            border-color: var(--accent-secondary);
            background: linear-gradient(135deg, rgba(251, 191, 36, 0.05), var(--bg-card));
        }

        .performance-card.silver {
            border-color: var(--text-muted);
            background: linear-gradient(135deg, rgba(148, 163, 184, 0.05), var(--bg-card));
        }

        .performance-card.bronze {
            border-color: #cd7c32;
            background: linear-gradient(135deg, rgba(205, 124, 50, 0.05), var(--bg-card));
        }

        .performance-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
        }

        .performance-medal {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 1.75rem;
            color: white;
            box-shadow: var(--shadow-md);
        }

        .performance-medal.gold { background: var(--medal-gradient); }
        .performance-medal.silver { background: linear-gradient(135deg, #94a3b8, #64748b); }
        .performance-medal.bronze { background: linear-gradient(135deg, #cd7c32, #a0522d); }

        .performance-title {
            font-family: var(--font-display);
            font-weight: 600;
            font-size: 1.25rem;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        .performance-desc {
            color: var(--text-secondary);
            font-size: 0.95rem;
        }

        /* Modern Sport CTA Section */
        .cta {
            text-align: center;
            padding: 6rem 2rem;
            background: 
                linear-gradient(135deg, 
                    var(--accent-primary) 0%,
                    var(--accent-success) 100%
                );
            color: white;
            position: relative;
            overflow: hidden;
        }

        .cta::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 30% 70%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 70% 30%, rgba(255, 255, 255, 0.1) 0%, transparent 50%);
        }

        .cta-content {
            max-width: 800px;
            margin: 0 auto;
            position: relative;
            z-index: 2;
        }

        .cta h2 {
            font-family: var(--font-display);
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .cta p {
            font-size: 1.25rem;
            margin-bottom: 2rem;
            opacity: 0.9;
        }

        .cta .btn {
            background: white;
            color: var(--accent-primary);
            border: none;
            font-weight: 700;
        }

        .cta .btn:hover {
            background: var(--bg-secondary);
            transform: translateY(-3px);
        }

        /* Modern Sport Footer */
        .footer {
            text-align: center;
            padding: 3rem 2rem;
            background: var(--text-primary);
            color: white;
        }

        .footer p {
            opacity: 0.8;
        }

        /* Responsive Modern Sport Design */
        @media (max-width: 1024px) {
            .hero-content {
                grid-template-columns: 1fr;
                gap: 2rem;
                text-align: center;
            }
            
            .trophy-display {
                width: 300px;
                height: 300px;
                font-size: 6rem;
            }
        }

        @media (max-width: 768px) {
            .navbar .nav-links {
                display: none;
            }
            
            .hero-buttons {
                flex-direction: column;
                width: 100%;
            }
            
            .btn {
                width: 100%;
                justify-content: center;
            }
            
            .hero-stats {
                justify-content: center;
                gap: 1rem;
                flex-wrap: wrap;
            }

            .features-grid,
            .performance-grid {
                grid-template-columns: 1fr;
            }

            .trophy-display {
                width: 250px;
                height: 250px;
                font-size: 4rem;
            }

            .floating-medal {
                display: none;
            }

            .features-header h2,
            .performance-header h2,
            .cta h2 {
                font-size: 2rem;
            }
        }

        /* Sport Scroll Animation */
        .scroll-animate {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .scroll-animate.animate {
            opacity: 1;
            transform: translateY(0);
        }

        /* Sport Loading State */
        .loading-shimmer {
            background: linear-gradient(90deg, transparent, rgba(59, 130, 246, 0.1), transparent);
            background-size: 200% 100%;
            animation: shimmer 2s ease-in-out infinite;
        }

        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }
    </style>
</head>
<body>
    <!-- Modern Sport Navigation -->
    <nav class="navbar" id="navbar">
        <div class="container">
            <a href="/" class="brand">
                <i class="fas fa-trophy"></i> SportTyping
            </a>
            <ul class="nav-links">
                <li><a href="#features">Features</a></li>
                <li><a href="#performance">Performance</a></li>
                <li><a href="{{ route('guest.practice') }}">Practice</a></li>
            </ul>
        </div>
    </nav>

    <!-- Modern Sport Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <div class="hero-text">
                <div class="hero-badge">🏆 Professional Typing Platform</div>
                <h1>Master Your Typing Performance</h1>
                <p class="subtitle">
                    Join the premier competitive typing platform where speed meets precision. 
                    Train like an athlete, compete like a champion, and achieve typing excellence.
                </p>
                
                <div class="hero-stats">
                    <div class="hero-stat">
                        <span class="hero-stat-number">150+</span>
                        <span class="hero-stat-label">WPM Record</span>
                    </div>
                    <div class="hero-stat">
                        <span class="hero-stat-number">10K+</span>
                        <span class="hero-stat-label">Athletes</span>
                    </div>
                    <div class="hero-stat">
                        <span class="hero-stat-number">99.9%</span>
                        <span class="hero-stat-label">Accuracy</span>
                    </div>
                </div>
                
                <div class="hero-buttons">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="btn btn-primary">
                                <i class="fas fa-chart-line"></i>
                                View Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-primary">
                                <i class="fas fa-user-plus"></i>
                                Join Platform
                            </a>
                            <a href="{{ route('guest.practice') }}" class="btn btn-secondary">
                                <i class="fas fa-play"></i>
                                Start Training
                            </a>
                        @endauth
                    @endif
                </div>
            </div>
            
            <div class="hero-visual">
                <div class="trophy-display">
                    <i class="fas fa-trophy"></i>
                </div>
                <div class="floating-elements">
                    <div class="floating-medal">
                        <i class="fas fa-medal"></i>
                    </div>
                    <div class="floating-medal">
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="floating-medal">
                        <i class="fas fa-crown"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modern Sport Features Section -->
    <section class="features" id="features">
        <div class="features-container">
            <div class="features-header scroll-animate">
                <h2>Professional Training Features</h2>
                <p>Everything you need to achieve peak typing performance and competitive excellence</p>
            </div>

            <div class="features-grid">
                <div class="feature-card scroll-animate">
                    <div class="feature-icon">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <h3>Device-Specific Leagues</h3>
                    <p>Separate competitions for mobile and desktop users, ensuring fair play and optimal performance tracking across all platforms.</p>
                </div>

                <div class="feature-card scroll-animate">
                    <div class="feature-icon">
                        <i class="fas fa-medal"></i>
                    </div>
                    <h3>Championship System</h3>
                    <p>Progress through professional leagues from Novice to Legend, earning prestigious badges and recognition for your achievements.</p>
                </div>

                <div class="feature-card scroll-animate">
                    <div class="feature-icon">
                        <i class="fas fa-stopwatch"></i>
                    </div>
                    <h3>Real-Time Competitions</h3>
                    <p>Compete against players worldwide in live typing races with real-time performance tracking and instant results.</p>
                </div>

                <div class="feature-card scroll-animate">
                    <div class="feature-icon">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <h3>Performance Analytics</h3>
                    <p>Detailed statistics and performance insights to track your progress, identify areas for improvement, and optimize your training.</p>
                </div>

                <div class="feature-card scroll-animate">
                    <div class="feature-icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <h3>Professional Training</h3>
                    <p>Structured 10-finger typing courses designed by professionals to build proper technique and muscle memory.</p>
                </div>

                <div class="feature-card scroll-animate">
                    <div class="feature-icon">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <h3>Achievement System</h3>
                    <p>Earn recognition for speed milestones, accuracy achievements, consistency records, and competitive victories.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Sport Performance Section -->
    <section class="performance-section" id="performance">
        <div class="performance-container">
            <div class="performance-header scroll-animate">
                <h2>Performance Levels</h2>
                <p>Track your progress through different skill tiers and achieve championship status</p>
            </div>

            <div class="performance-grid">
                <div class="performance-card gold scroll-animate">
                    <div class="performance-medal gold">
                        <i class="fas fa-crown"></i>
                    </div>
                    <div class="performance-title">Elite Performance</div>
                    <div class="performance-desc">120+ WPM with 98%+ accuracy</div>
                </div>
                
                <div class="performance-card silver scroll-animate">
                    <div class="performance-medal silver">
                        <i class="fas fa-medal"></i>
                    </div>
                    <div class="performance-title">Advanced Level</div>
                    <div class="performance-desc">80+ WPM with 95%+ accuracy</div>
                </div>
                
                <div class="performance-card bronze scroll-animate">
                    <div class="performance-medal bronze">
                        <i class="fas fa-award"></i>
                    </div>
                    <div class="performance-title">Intermediate</div>
                    <div class="performance-desc">50+ WPM with 90%+ accuracy</div>
                </div>
                
                <div class="performance-card scroll-animate">
                    <div class="performance-medal" style="background: var(--victory-gradient);">
                        <i class="fas fa-flag"></i>
                    </div>
                    <div class="performance-title">Beginner Friendly</div>
                    <div class="performance-desc">Start your journey to excellence</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modern Sport CTA Section -->
    <section class="cta scroll-animate">
        <div class="cta-content">
            <h2>Ready to Compete?</h2>
            <p>Join thousands of typing athletes and elevate your performance to championship level</p>
            <a href="{{ route('guest.practice') }}" class="btn">
                <i class="fas fa-rocket"></i>
                Start Your Journey
            </a>
        </div>
    </section>

    <!-- Modern Sport Footer -->
    <footer class="footer">
        <p>&copy; 2025 SportTyping | Professional Typing Training Platform</p>
    </footer>

    <script>
        // Modern navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Sport scroll animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate');
                }
            });
        }, observerOptions);

        // Observe all scroll-animate elements
        document.querySelectorAll('.scroll-animate').forEach(el => {
            observer.observe(el);
        });

        // Modern smooth scrolling
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Sport performance tracking animation
        window.addEventListener('load', function() {
            document.body.classList.add('loaded');
            
            // Animate performance cards on load
            setTimeout(() => {
                document.querySelectorAll('.performance-card').forEach((card, index) => {
                    setTimeout(() => {
                        card.style.transform = 'translateY(0)';
                        card.style.opacity = '1';
                    }, index * 150);
                });
            }, 1000);
        });

        // Professional typing test preview
        let typingDemo = false;
        document.addEventListener('keydown', function(e) {
            if (!typingDemo && (e.key.length === 1 || e.key === 'Backspace')) {
                typingDemo = true;
                const demoIndicator = document.createElement('div');
                demoIndicator.innerHTML = `
                    <div style="
                        position: fixed;
                        top: 20px;
                        right: 20px;
                        background: var(--champion-gradient);
                        color: white;
                        padding: 1rem 1.5rem;
                        border-radius: var(--border-radius);
                        font-weight: 600;
                        z-index: 9999;
                        box-shadow: var(--shadow-lg);
                        animation: slideInRight 0.5s ease;
                    ">
                        <i class="fas fa-keyboard"></i> Typing Detected! 
                        <small style="display: block; opacity: 0.9; font-size: 0.85rem; margin-top: 0.25rem;">
                            Ready to start your training?
                        </small>
                    </div>
                `;
                document.body.appendChild(demoIndicator);
                
                setTimeout(() => {
                    demoIndicator.remove();
                    typingDemo = false;
                }, 4000);
            }
        });

        // Trophy animation enhancement
        const trophyDisplay = document.querySelector('.trophy-display');
        if (trophyDisplay) {
            trophyDisplay.addEventListener('mouseenter', function() {
                this.style.animation = 'trophyFloat 1s ease-in-out infinite';
            });
            
            trophyDisplay.addEventListener('mouseleave', function() {
                this.style.animation = 'trophyFloat 6s ease-in-out infinite';
            });
        }

        // Add slide-in animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideInRight {
                from {
                    transform: translateX(100%);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
        `;
        document.head.appendChild(style);

        // Performance improvement - preload images
        const preloadImages = [
            // Add any images that need preloading
        ];
        
        preloadImages.forEach(src => {
            const img = new Image();
            img.src = src;
        });
    </script>
</body>
</html>