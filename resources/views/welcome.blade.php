<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SportTyping - Competitive Typing Platform</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
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

        /* Navigation */
        .navbar {
            background: rgba(44, 27, 71, 0.4);
            backdrop-filter: blur(var(--blur-amount));
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding: 1rem 0;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .navbar.scrolled {
            background: rgba(44, 27, 71, 0.8);
            box-shadow: var(--shadow-glow);
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
            font-size: 1.5rem;
            font-weight: 700;
            background: var(--gradient-accent);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-decoration: none;
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
            transition: color 0.3s ease;
        }

        .navbar .nav-links a:hover {
            color: var(--accent-pink);
        }

        /* Hero Section */
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
            padding: 0 2rem;
        }

        .hero-content {
            max-width: 1200px;
            margin: 0 auto;
            text-align: center;
            z-index: 2;
        }

        .hero h1 {
            font-size: clamp(2.5rem, 8vw, 4.5rem);
            font-weight: 700;
            margin-bottom: 1rem;
            background: var(--gradient-accent);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1.2;
        }

        .hero .subtitle {
            font-size: clamp(1rem, 3vw, 1.2rem);
            color: var(--text-secondary);
            margin-bottom: 2rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .hero-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 2rem;
        }

        /* Buttons */
        .btn {
            padding: 0.75rem 2rem;
            border-radius: var(--border-radius);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1rem;
        }

        .btn-primary {
            background: var(--gradient-button);
            color: white;
            box-shadow: 0 4px 15px rgba(255, 107, 157, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(255, 107, 157, 0.4);
        }

        .btn-outline {
            background: transparent;
            color: var(--text-primary);
            border: 2px solid rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
        }

        .btn-outline:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: var(--accent-pink);
            color: var(--accent-pink);
            transform: translateY(-2px);
        }

        /* Features Section */
        .features {
            padding: 5rem 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .features-header {
            text-align: center;
            margin-bottom: 4rem;
        }

        .features-header h2 {
            font-size: clamp(2rem, 5vw, 3rem);
            font-weight: 700;
            margin-bottom: 1rem;
            background: var(--gradient-accent);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .features-header p {
            color: var(--text-secondary);
            font-size: 1.1rem;
            max-width: 600px;
            margin: 0 auto;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }

        .feature-card {
            background: var(--gradient-card);
            backdrop-filter: blur(var(--blur-amount));
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: var(--border-radius-lg);
            padding: 2rem;
            text-align: center;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transition: left 0.5s ease;
        }

        .feature-card:hover::before {
            left: 100%;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 40px rgba(139, 92, 246, 0.2);
            border-color: var(--accent-pink);
        }

        .feature-icon {
            width: 60px;
            height: 60px;
            background: var(--gradient-button);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 1.5rem;
            color: white;
        }

        .feature-card h3 {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: var(--text-primary);
        }

        .feature-card p {
            color: var(--text-secondary);
            line-height: 1.6;
        }

        /* CTA Section */
        .cta {
            text-align: center;
            padding: 4rem 2rem;
            background: var(--gradient-card);
            backdrop-filter: blur(var(--blur-amount));
            margin: 4rem 2rem;
            border-radius: var(--border-radius-lg);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .cta h2 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 1rem;
            background: var(--gradient-accent);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .cta p {
            color: var(--text-secondary);
            font-size: 1.1rem;
            margin-bottom: 2rem;
        }

        /* Footer */
        .footer {
            text-align: center;
            padding: 2rem;
            background: rgba(44, 27, 71, 0.4);
            backdrop-filter: blur(var(--blur-amount));
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            color: var(--text-muted);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .navbar .nav-links {
                display: none;
            }
            
            .hero-buttons {
                flex-direction: column;
                align-items: center;
            }
            
            .btn {
                width: 100%;
                max-width: 300px;
                justify-content: center;
            }
            
            .features-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Scroll Animation */
        .scroll-animate {
            opacity: 0;
            transform: translateY(50px);
            transition: all 0.6s ease;
        }

        .scroll-animate.animate {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>
<body>
    <div class="animated-bg"></div>
    
    <!-- Navigation -->
    <nav class="navbar" id="navbar">
        <div class="container">
            <a href="/" class="brand">
                <i class="fas fa-keyboard"></i> SportTyping
            </a>
            <ul class="nav-links">
                <li><a href="#features">Features</a></li>
                <li><a href="#about">About</a></li>
                <li><a href="{{ route('guest.practice') }}">Practice</a></li>
            </ul>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1>Transform Typing Into Sport</h1>
            <p class="subtitle">
                Experience the thrill of competitive typing with real-time races, 
                device-specific arenas, and a global ranking system since 2025
            </p>
            
            <div class="hero-buttons">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn btn-primary">
                            <i class="fas fa-tachometer-alt"></i>
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary">
                            <i class="fas fa-sign-in-alt"></i>
                            Get Started
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-outline">
                            <i class="fas fa-user-plus"></i>
                            Create Account
                        </a>
                    @endauth
                @endif
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features" id="features">
        <div class="features-header scroll-animate">
            <h2>Game-Changing Features</h2>
            <p>Discover what makes SportTyping the ultimate competitive typing platform</p>
        </div>

        <div class="features-grid">
            <div class="feature-card scroll-animate">
                <div class="feature-icon">
                    <i class="fas fa-mobile-alt"></i>
                </div>
                <h3>Device-Specific Arenas</h3>
                <p>Fair competition with separate arenas for mobile and PC users, ensuring level playing field for everyone</p>
            </div>

            <div class="feature-card scroll-animate">
                <div class="feature-icon">
                    <i class="fas fa-trophy"></i>
                </div>
                <h3>League System</h3>
                <p>Climb through rankings from Novice to Legend as your typing skills improve and earn exclusive rewards</p>
            </div>

            <div class="feature-card scroll-animate">
                <div class="feature-icon">
                    <i class="fas fa-bolt"></i>
                </div>
                <h3>Real-time Competitions</h3>
                <p>Race against other players in exciting live typing competitions with instant results and leaderboards</p>
            </div>

            <div class="feature-card scroll-animate">
                <div class="feature-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h3>Advanced Analytics</h3>
                <p>Track your performance with detailed statistics, progress charts, and personalized improvement insights</p>
            </div>

            <div class="feature-card scroll-animate">
                <div class="feature-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <h3>Structured Lessons</h3>
                <p>Master 10-finger typing with professional courses designed for beginners to advanced typists</p>
            </div>

            <div class="feature-card scroll-animate">
                <div class="feature-icon">
                    <i class="fas fa-medal"></i>
                </div>
                <h3>Achievement System</h3>
                <p>Unlock badges and achievements for speed, accuracy, consistency, and various typing milestones</p>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta scroll-animate">
        <h2>Ready to Compete?</h2>
        <p>Join thousands of typists worldwide and start your journey to typing mastery</p>
        <a href="{{ route('guest.practice') }}" class="btn btn-primary">
            <i class="fas fa-play"></i>
            Try Without Account
        </a>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <p>&copy; 2025 SportTyping. All rights reserved. | Transforming typing into competitive sport.</p>
    </footer>

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

        // Scroll animations
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

        
        // Smooth scrolling for anchor links
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
    </script>
</body>
</html>