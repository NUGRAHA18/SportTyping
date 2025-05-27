{{-- resources/views/guest/practice.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Practice - SportTyping</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            /* SportTyping Color Palette */
            --bg-primary: #1a0d2e;
            --bg-secondary: #2c1b47;
            --bg-card: rgba(44, 27, 71, 0.4);
            --accent-pink: #ff6b9d;
            --accent-cyan: #00d4ff;
            --accent-purple: #8b5cf6;
            
            /* Gradients */
            --gradient-main: linear-gradient(135deg, #1a0d2e 0%, #2c1b47 100%);
            --gradient-card: linear-gradient(145deg, rgba(139, 92, 246, 0.1) 0%, rgba(255, 107, 157, 0.1) 100%);
            --gradient-accent: linear-gradient(90deg, #ff6b9d 0%, #00d4ff 100%);
            --gradient-button: linear-gradient(45deg, #ff6b9d, #8b5cf6);
            
            /* Text Colors */
            --text-primary: #ffffff;
            --text-secondary: #b4a7d1;
            --text-muted: #9ca3af;
            
            /* Layout */
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

        .nav-link:hover, .nav-link.active {
            color: var(--accent-pink);
            background: rgba(255, 107, 157, 0.1);
        }

        /* Page Header */
        .page-header {
            text-align: center;
            padding: 4rem 2rem 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .page-header h1 {
            font-size: clamp(2.5rem, 8vw, 4rem);
            font-weight: 700;
            margin-bottom: 1rem;
            background: var(--gradient-accent);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1.2;
        }

        .page-header .subtitle {
            font-size: clamp(1rem, 3vw, 1.2rem);
            color: var(--text-secondary);
            margin-bottom: 2rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        /* Practice Section */
        .practice-section {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        .practice-filters {
            background: var(--gradient-card);
            backdrop-filter: blur(var(--blur-amount));
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: var(--border-radius-lg);
            padding: 2rem;
            margin-bottom: 3rem;
            box-shadow: var(--shadow-card);
        }

        .filters-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .filters-header h2 {
            color: var(--text-primary);
            font-size: 1.5rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .filters-header i {
            color: var(--accent-pink);
        }

        .filter-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .filter-label {
            color: var(--text-primary);
            font-weight: 500;
            font-size: 0.95rem;
        }

        .filter-select, .filter-input {
            background: rgba(255, 255, 255, 0.05);
            border: 2px solid rgba(255, 255, 255, 0.1);
            border-radius: var(--border-radius);
            color: var(--text-primary);
            padding: 0.75rem 1rem;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .filter-select:focus, .filter-input:focus {
            outline: none;
            border-color: var(--accent-pink);
            background: rgba(255, 255, 255, 0.08);
            box-shadow: 0 0 0 3px rgba(255, 107, 157, 0.1);
        }

        .filter-select option {
            background: var(--bg-secondary);
            color: var(--text-primary);
        }

        /* Category Cards */
        .categories-section {
            margin-bottom: 3rem;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .section-header h2 {
            color: var(--text-primary);
            font-size: 1.5rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .categories-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .category-card {
            background: var(--gradient-card);
            backdrop-filter: blur(var(--blur-amount));
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: var(--border-radius-lg);
            padding: 1.5rem;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .category-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transition: left 0.5s ease;
        }

        .category-card:hover::before {
            left: 100%;
        }

        .category-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 40px rgba(139, 92, 246, 0.2);
            border-color: var(--accent-pink);
        }

        .category-card.active {
            border-color: var(--accent-pink);
            background: linear-gradient(145deg, rgba(255, 107, 157, 0.1), rgba(139, 92, 246, 0.1));
        }

        .category-icon {
            width: 60px;
            height: 60px;
            background: var(--gradient-button);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 1.5rem;
            color: white;
        }

        .category-card h3 {
            color: var(--text-primary);
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .category-count {
            color: var(--text-secondary);
            font-size: 0.9rem;
        }

        /* Text List */
        .texts-section {
            background: var(--gradient-card);
            backdrop-filter: blur(var(--blur-amount));
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: var(--border-radius-lg);
            padding: 2rem;
            box-shadow: var(--shadow-card);
        }

        .texts-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .texts-header h2 {
            color: var(--text-primary);
            font-size: 1.3rem;
            font-weight: 600;
        }

        .view-toggle {
            display: flex;
            background: rgba(255, 255, 255, 0.05);
            border-radius: var(--border-radius);
            padding: 0.25rem;
        }

        .view-toggle button {
            background: none;
            border: none;
            color: var(--text-secondary);
            padding: 0.5rem 1rem;
            border-radius: calc(var(--border-radius) - 2px);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .view-toggle button.active {
            background: var(--accent-pink);
            color: white;
        }

        .texts-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 1.5rem;
        }

        .texts-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .text-card {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: var(--border-radius);
            padding: 1.5rem;
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
        }

        .text-card:hover {
            background: rgba(255, 255, 255, 0.05);
            border-color: var(--accent-pink);
            transform: translateY(-2px);
        }

        .text-card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
        }

        .text-title {
            color: var(--text-primary);
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .difficulty-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .difficulty-beginner { 
            background: rgba(76, 175, 80, 0.2); 
            color: #4caf50; 
        }
        .difficulty-intermediate { 
            background: rgba(245, 158, 11, 0.2); 
            color: #f59e0b; 
        }
        .difficulty-advanced { 
            background: rgba(239, 68, 68, 0.2); 
            color: #ef4444; 
        }
        .difficulty-expert { 
            background: rgba(139, 92, 246, 0.2); 
            color: var(--accent-purple); 
        }

        .text-preview {
            color: var(--text-secondary);
            font-size: 0.9rem;
            line-height: 1.4;
            margin-bottom: 1rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .text-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.85rem;
            color: var(--text-muted);
        }

        .category-tag {
            background: rgba(0, 212, 255, 0.2);
            color: var(--accent-cyan);
            padding: 0.25rem 0.5rem;
            border-radius: 6px;
        }

        /* Pagination */
        .pagination-wrapper {
            display: flex;
            justify-content: center;
            margin-top: 2rem;
        }

        .pagination {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }

        .pagination a, .pagination span {
            padding: 0.5rem 1rem;
            border-radius: var(--border-radius);
            text-decoration: none;
            color: var(--text-secondary);
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }

        .pagination a:hover {
            background: rgba(255, 107, 157, 0.1);
            color: var(--accent-pink);
            border-color: var(--accent-pink);
        }

        .pagination .active {
            background: var(--accent-pink);
            color: white;
            border-color: var(--accent-pink);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: var(--text-secondary);
        }

        .empty-icon {
            width: 80px;
            height: 80px;
            background: var(--gradient-card);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 2rem;
            color: var(--text-muted);
        }

        .empty-state h3 {
            color: var(--text-primary);
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
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

        /* CTA Section */
        .cta-section {
            text-align: center;
            padding: 4rem 2rem;
            margin: 4rem 2rem 2rem;
            background: var(--gradient-card);
            backdrop-filter: blur(var(--blur-amount));
            border-radius: var(--border-radius-lg);
            border: 1px solid rgba(255, 255, 255, 0.1);
            max-width: 1200px;
            margin-left: auto;
            margin-right: auto;
        }

        .cta-section h2 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 1rem;
            background: var(--gradient-accent);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .cta-section p {
            color: var(--text-secondary);
            font-size: 1.1rem;
            margin-bottom: 2rem;
        }

        /* Mobile Toggle */
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

        /* Responsive */
        @media (max-width: 1024px) {
            .filter-grid {
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            }
            
            .categories-grid {
                grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            }
            
            .texts-grid {
                grid-template-columns: 1fr;
            }
        }

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

            .page-header {
                padding: 2rem 1rem 1rem;
            }

            .practice-section {
                padding: 1rem;
            }

            .filter-grid {
                grid-template-columns: 1fr;
            }

            .categories-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .texts-header {
                flex-direction: column;
                gap: 1rem;
                align-items: stretch;
            }

            .view-toggle {
                align-self: center;
            }
        }

        /* Loading Animation */
        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: var(--accent-pink);
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Scroll Animation */
        .scroll-animate {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.6s ease;
        }

        .scroll-animate.animate {
            opacity: 1;
            transform: translateY(0);
        }

        /* Stats Section */
        .stats-section {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 3rem;
        }

        .stat-card {
            background: var(--gradient-card);
            backdrop-filter: blur(var(--blur-amount));
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: var(--border-radius);
            padding: 1.5rem;
            text-align: center;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(139, 92, 246, 0.2);
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--accent-pink);
            display: block;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: var(--text-secondary);
            font-size: 0.9rem;
        }

        /* Footer */
        .footer {
            text-align: center;
            padding: 2rem;
            background: rgba(44, 27, 71, 0.4);
            backdrop-filter: blur(var(--blur-amount));
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            color: var(--text-muted);
            margin-top: 4rem;
        }
    </style>
</head>
<body>
    <div class="animated-bg"></div>
    
    <!-- Navigation -->
    <nav class="navbar">
        <div class="navbar-container">
            <a href="{{ url('/') }}" class="navbar-brand">
                <i class="fas fa-keyboard"></i>
                SportTyping
            </a>
            
            <button class="mobile-toggle" id="mobileToggle">
                <i class="fas fa-bars"></i>
            </button>

            <ul class="navbar-nav" id="navbarNav">
                <li><a class="nav-link active" href="{{ route('guest.practice') }}">Practice</a></li>
                <li><a class="nav-link" href="{{ route('guest.lessons') }}">Lessons</a></li>
                <li><a class="nav-link" href="{{ route('guest.competitions') }}">Competitions</a></li>
                <li><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                <li><a class="nav-link" href="{{ route('register') }}">Register</a></li>
            </ul>
        </div>
    </nav>

    <!-- Page Header -->
    <section class="page-header">
        <h1>Practice Typing</h1>
        <p class="subtitle">
            Improve your typing speed and accuracy with our diverse collection of practice texts. 
            Choose from different categories and difficulty levels to match your skill level.
        </p>
    </section>

    <!-- Practice Section -->
    <main class="practice-section">
        <!-- Stats -->
        <div class="stats-section scroll-animate">
            <div class="stat-card">
                <span class="stat-number">{{ $texts->total() ?? 0 }}</span>
                <span class="stat-label">Practice Texts</span>
            </div>
            <div class="stat-card">
                <span class="stat-number">{{ $categories->count() ?? 0 }}</span>
                <span class="stat-label">Categories</span>
            </div>
            <div class="stat-card">
                <span class="stat-number">4</span>
                <span class="stat-label">Difficulty Levels</span>
            </div>
            <div class="stat-card">
                <span class="stat-number">∞</span>
                <span class="stat-label">Practice Sessions</span>
            </div>
        </div>

        <!-- Filters -->
        <div class="practice-filters scroll-animate">
            <div class="filters-header">
                <h2>
                    <i class="fas fa-filter"></i>
                    Filter Practice Texts
                </h2>
                <button class="btn btn-outline" onclick="resetFilters()">
                    <i class="fas fa-undo"></i>
                    Reset
                </button>
            </div>
            
            <form class="filter-grid" method="GET" action="{{ route('guest.practice') }}">
                <div class="filter-group">
                    <label class="filter-label">Category</label>
                    <select class="filter-select" name="category" onchange="this.form.submit()">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }} ({{ $category->texts_count ?? 0 }})
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="filter-group">
                    <label class="filter-label">Difficulty</label>
                    <select class="filter-select" name="difficulty" onchange="this.form.submit()">
                        <option value="">All Levels</option>
                        <option value="beginner" {{ request('difficulty') == 'beginner' ? 'selected' : '' }}>Beginner</option>
                        <option value="intermediate" {{ request('difficulty') == 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                        <option value="advanced" {{ request('difficulty') == 'advanced' ? 'selected' : '' }}>Advanced</option>
                        <option value="expert" {{ request('difficulty') == 'expert' ? 'selected' : '' }}>Expert</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label class="filter-label">Search</label>
                    <input type="text" class="filter-input" name="search" placeholder="Search texts..." 
                           value="{{ request('search') }}" onkeyup="debounceSearch(this)">