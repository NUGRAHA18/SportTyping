@extends('layouts.app')

@section('content')
<div class="practice-dashboard-container">
    <div class="container">
        <!-- Header -->
        <div class="practice-header">
            <div class="header-content">
                <h1>Practice Center</h1>
                <p>Improve your typing skills with personalized practice sessions</p>
            </div>
            <div class="user-level">
                <div class="level-badge">
                    <i class="fas fa-star"></i>
                    <div class="level-info">
                        <span class="level-name">{{ Auth::user()->profile->league->name ?? 'Novice' }}</span>
                        <span class="level-subtitle">Current Level</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Practice Stats -->
        <div class="practice-stats">
            <div class="stat-card">
                <div class="stat-icon speed">
                    <i class="fas fa-tachometer-alt"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ number_format(Auth::user()->profile->typing_speed_avg ?? 0, 1) }}</h3>
                    <p>Average WPM</p>
                    <div class="stat-trend">
                        <i class="fas fa-arrow-up"></i>
                        <span>+3.2 this week</span>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon accuracy">
                    <i class="fas fa-bullseye"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ number_format(Auth::user()->profile->typing_accuracy_avg ?? 0, 1) }}%</h3>
                    <p>Accuracy Rate</p>
                    <div class="stat-trend">
                        <i class="fas fa-arrow-up"></i>
                        <span>+2.1% this week</span>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon sessions">
                    <i class="fas fa-keyboard"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ Auth::user()->practices()->count() }}</h3>
                    <p>Practice Sessions</p>
                    <div class="stat-trend">
                        <i class="fas fa-fire"></i>
                        <span>{{ Auth::user()->practices()->whereDate('created_at', today())->count() }} today</span>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon streak">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="stat-content">
                    <h3>7</h3>
                    <p>Day Streak</p>
                    <div class="stat-trend">
                        <i class="fas fa-fire"></i>
                        <span>Keep it up!</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Practice Options -->
        <div class="quick-practice">
            <div class="section-header">
                <h2><i class="fas fa-bolt"></i> Quick Practice</h2>
                <p>Jump into a practice session right away</p>
            </div>

            <div class="quick-options">
                <div class="quick-option recommended" onclick="startPractice('recommended')">
                    <div class="option-header">
                        <div class="option-icon">
                            <i class="fas fa-magic"></i>
                        </div>
                        <div class="recommended-badge">
                            <i class="fas fa-star"></i>
                            Recommended
                        </div>
                    </div>
                    <div class="option-content">
                        <h3>Smart Practice</h3>
                        <p>AI-powered practice based on your weaknesses</p>
                        <div class="option-details">
                            <span class="detail-item">
                                <i class="fas fa-target"></i>
                                Focus on accuracy
                            </span>
                            <span class="detail-item">
                                <i class="fas fa-clock"></i>
                                ~10 minutes
                            </span>
                        </div>
                    </div>
                </div>

                <div class="quick-option" onclick="startPractice('speed')">
                    <div class="option-header">
                        <div class="option-icon">
                            <i class="fas fa-rocket"></i>
                        </div>
                    </div>
                    <div class="option-content">
                        <h3>Speed Building</h3>
                        <p>Push your WPM to the next level</p>
                        <div class="option-details">
                            <span class="detail-item">
                                <i class="fas fa-tachometer-alt"></i>
                                Speed focused
                            </span>
                            <span class="detail-item">
                                <i class="fas fa-clock"></i>
                                ~5 minutes
                            </span>
                        </div>
                    </div>
                </div>

                <div class="quick-option" onclick="startPractice('accuracy')">
                    <div class="option-header">
                        <div class="option-icon">
                            <i class="fas fa-bullseye"></i>
                        </div>
                    </div>
                    <div class="option-content">
                        <h3>Accuracy Training</h3>
                        <p>Perfect your precision and reduce errors</p>
                        <div class="option-details">
                            <span class="detail-item">
                                <i class="fas fa-crosshairs"></i>
                                Precision mode
                            </span>
                            <span class="detail-item">
                                <i class="fas fa-clock"></i>
                                ~8 minutes
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Practice Categories -->
        <div class="practice-categories">
            <div class="section-header">
                <h2><i class="fas fa-th-large"></i> Practice Categories</h2>
                <p>Choose your preferred practice type</p>
            </div>

            <div class="categories-grid">
                <div class="category-card" onclick="showCategoryModal('programming')">
                    <div class="category-icon programming">
                        <i class="fas fa-code"></i>
                    </div>
                    <div class="category-content">
                        <h3>Programming</h3>
                        <p>Code snippets, syntax, and technical terms</p>
                        <div class="category-stats">
                            <span class="stat-item">
                                <i class="fas fa-chart-bar"></i>
                                Best: {{ Auth::user()->practices()->where('category', 'programming')->max('wpm') ?? 0 }} WPM
                            </span>
                            <span class="stat-item">
                                <i class="fas fa-history"></i>
                                {{ Auth::user()->practices()->where('category', 'programming')->count() }} sessions
                            </span>
                        </div>
                    </div>
                </div>

                <div class="category-card" onclick="showCategoryModal('literature')">
                    <div class="category-icon literature">
                        <i class="fas fa-book"></i>
                    </div>
                    <div class="category-content">
                        <h3>Literature</h3>
                        <p>Classic texts, poetry, and prose</p>
                        <div class="category-stats">
                            <span class="stat-item">
                                <i class="fas fa-chart-bar"></i>
                                Best: {{ Auth::user()->practices()->where('category', 'literature')->max('wpm') ?? 0 }} WPM
                            </span>
                            <span class="stat-item">
                                <i class="fas fa-history"></i>
                                {{ Auth::user()->practices()->where('category', 'literature')->count() }} sessions
                            </span>
                        </div>
                    </div>
                </div>

                <div class="category-card" onclick="showCategoryModal('science')">
                    <div class="category-icon science">
                        <i class="fas fa-flask"></i>
                    </div>
                    <div class="category-content">
                        <h3>Science</h3>
                        <p>Scientific articles and terminology</p>
                        <div class="category-stats">
                            <span class="stat-item">
                                <i class="fas fa-chart-bar"></i>
                                Best: {{ Auth::user()->practices()->where('category', 'science')->max('wpm') ?? 0 }} WPM
                            </span>
                            <span class="stat-item">
                                <i class="fas fa-history"></i>
                                {{ Auth::user()->practices()->where('category', 'science')->count() }} sessions
                            </span>
                        </div>
                    </div>
                </div>

                <div class="category-card" onclick="showCategoryModal('business')">
                    <div class="category-icon business">
                        <i class="fas fa-briefcase"></i>
                    </div>
                    <div class="category-content">
                        <h3>Business</h3>
                        <p>Corporate communication and reports</p>
                        <div class="category-stats">
                            <span class="stat-item">
                                <i class="fas fa-chart-bar"></i>
                                Best: {{ Auth::user()->practices()->where('category', 'business')->max('wpm') ?? 0 }} WPM
                            </span>
                            <span class="stat-item">
                                <i class="fas fa-history"></i>
                                {{ Auth::user()->practices()->where('category', 'business')->count() }} sessions
                            </span>
                        </div>
                    </div>
                </div>

                <div class="category-card" onclick="showCategoryModal('technology')">
                    <div class="category-icon technology">
                        <i class="fas fa-microchip"></i>
                    </div>
                    <div class="category-content">
                        <h3>Technology</h3>
                        <p>Tech news and innovation articles</p>
                        <div class="category-stats">
                            <span class="stat-item">
                                <i class="fas fa-chart-bar"></i>
                                Best: {{ Auth::user()->practices()->where('category', 'technology')->max('wpm') ?? 0 }} WPM
                            </span>
                            <span class="stat-item">
                                <i class="fas fa-history"></i>
                                {{ Auth::user()->practices()->where('category', 'technology')->count() }} sessions
                            </span>
                        </div>
                    </div>
                </div>

                <div class="category-card" onclick="showCustomTextModal()">
                    <div class="category-icon custom">
                        <i class="fas fa-edit"></i>
                    </div>
                    <div class="category-content">
                        <h3>Custom Text</h3>
                        <p>Practice with your own text content</p>
                        <div class="category-stats">
                            <span class="stat-item">
                                <i class="fas fa-upload"></i>
                                Paste your text
                            </span>
                            <span class="stat-item">
                                <i class="fas fa-infinity"></i>
                                Unlimited length
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Practice Sessions -->
        <div class="recent-sessions">
            <div class="section-header">
                <h2><i class="fas fa-history"></i> Recent Sessions</h2>
                <a href="#" class="view-all-link">
                    View All <i class="fas fa-arrow-right"></i>
                </a>
            </div>

            <div class="sessions-list">
                @forelse(Auth::user()->practices()->latest()->limit(5)->get() as $practice)
                <div class="session-item">
                    <div class="session-icon">
                        <i class="fas fa-{{ $practice->category == 'programming' ? 'code' : ($practice->category == 'literature' ? 'book' : 'keyboard') }}"></i>
                    </div>
                    <div class="session-content">
                        <h4>{{ ucfirst($practice->category) }} Practice</h4>
                        <p>{{ $practice->created_at->diffForHumans() }}</p>
                    </div>
                    <div class="session-stats">
                        <div class="session-stat">
                            <span class="stat-value">{{ $practice->wpm }}</span>
                            <span class="stat-label">WPM</span>
                        </div>
                        <div class="session-stat">
                            <span class="stat-value">{{ $practice->accuracy }}%</span>
                            <span class="stat-label">Accuracy</span>
                        </div>
                    </div>
                    <div class="session-actions">
                        <button class="btn btn-outline-primary btn-sm" onclick="retrySession('{{ $practice->id }}')">
                            <i class="fas fa-redo"></i>
                            Retry
                        </button>
                    </div>
                </div>
                @empty
                <div class="no-sessions">
                    <div class="no-sessions-icon">
                        <i class="fas fa-keyboard"></i>
                    </div>
                    <h3>No practice sessions yet</h3>
                    <p>Start your first practice session to track your progress!</p>
                    <button class="btn btn-primary" onclick="startPractice('recommended')">
                        <i class="fas fa-play"></i>
                        Start First Session
                    </button>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Progress Chart -->
        <div class="progress-chart-section">
            <div class="section-header">
                <h2><i class="fas fa-chart-line"></i> Progress Over Time</h2>
                <div class="chart-controls">
                    <button class="chart-btn active" data-period="week">7 Days</button>
                    <button class="chart-btn" data-period="month">30 Days</button>
                    <button class="chart-btn" data-period="year">1 Year</button>
                </div>
            </div>
            
            <div class="chart-container">
                <canvas id="progressChart" width="800" height="300"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Category Modal -->
<div class="modal" id="categoryModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="categoryModalTitle">Programming Practice</h3>
            <button class="close-btn" onclick="closeCategoryModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <div class="difficulty-selection">
                <h4>Choose Difficulty</h4>
                <div class="difficulty-options">
                    <div class="difficulty-option active" data-difficulty="beginner">
                        <div class="difficulty-icon">
                            <i class="fas fa-seedling"></i>
                        </div>
                        <div class="difficulty-info">
                            <h5>Beginner</h5>
                            <p>Simple words and short sentences</p>
                        </div>
                    </div>
                    <div class="difficulty-option" data-difficulty="intermediate">
                        <div class="difficulty-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div class="difficulty-info">
                            <h5>Intermediate</h5>
                            <p>Complex sentences and technical terms</p>
                        </div>
                    </div>
                    <div class="difficulty-option" data-difficulty="advanced">
                        <div class="difficulty-icon">
                            <i class="fas fa-rocket"></i>
                        </div>
                        <div class="difficulty-info">
                            <h5>Advanced</h5>
                            <p>Complex paragraphs and symbols</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="duration-selection">
                <h4>Practice Duration</h4>
                <div class="duration-options">
                    <button class="duration-btn active" data-duration="60">1 min</button>
                    <button class="duration-btn" data-duration="180">3 min</button>
                    <button class="duration-btn" data-duration="300">5 min</button>
                    <button class="duration-btn" data-duration="0">Unlimited</button>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-primary" onclick="startCategoryPractice()">
                <i class="fas fa-play"></i>
                Start Practice
            </button>
        </div>
    </div>
</div>

<!-- Custom Text Modal -->
<div class="modal" id="customTextModal">
    <div class="modal-content large">
        <div class="modal-header">
            <h3>Custom Text Practice</h3>
            <button class="close-btn" onclick="closeCustomTextModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <div class="custom-text-area">
                <label for="customText">Enter your custom text:</label>
                <textarea id="customText" rows="8" placeholder="Paste or type your text here. Minimum 50 words recommended for accurate results."></textarea>
                <div class="text-info">
                    <span id="wordCount">0 words</span>
                    <span id="charCount">0 characters</span>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-outline-primary" onclick="loadSampleText()">
                <i class="fas fa-file-text"></i>
                Load Sample
            </button>
            <button class="btn btn-primary" onclick="startCustomPractice()">
                <i class="fas fa-play"></i>
                Start Practice
            </button>
        </div>
    </div>
</div>

<style>
.practice-dashboard-container {
    padding: 2rem 0;
    min-height: calc(100vh - 80px);
}

/* Header */
.practice-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 3rem;
    padding: 2.5rem;
    background: var(--gradient-card);
    backdrop-filter: blur(var(--blur-amount));
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius-lg);
    position: relative;
    overflow: hidden;
}

.practice-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: var(--gradient-accent);
}

.header-content h1 {
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 0.5rem;
}

.header-content p {
    color: var(--text-secondary);
    font-size: 1.1rem;
}

.user-level {
    text-align: right;
}

.level-badge {
    display: flex;
    align-items: center;
    gap: 1rem;
    background: rgba(255, 107, 157, 0.1);
    padding: 1rem 1.5rem;
    border-radius: var(--border-radius);
    border: 1px solid rgba(255, 107, 157, 0.2);
}

.level-badge i {
    font-size: 2rem;
    color: var(--accent-pink);
}

.level-info {
    display: flex;
    flex-direction: column;
}

.level-name {
    font-size: 1.2rem;
    font-weight: 600;
    color: var(--text-primary);
}

.level-subtitle {
    font-size: 0.9rem;
    color: var(--text-secondary);
}

/* Practice Stats */
.practice-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-bottom: 3rem;
}

.stat-card {
    background: var(--gradient-card);
    backdrop-filter: blur(var(--blur-amount));
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius-lg);
    padding: 2rem;
    display: flex;
    align-items: center;
    gap: 1.5rem;
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 30px rgba(139, 92, 246, 0.15);
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
}

.stat-icon.speed { background: var(--gradient-button); }
.stat-icon.accuracy { background: linear-gradient(45deg, #00d4ff, #0ea5e9); }
.stat-icon.sessions { background: linear-gradient(45deg, #f59e0b, #eab308); }
.stat-icon.streak { background: linear-gradient(45deg, #10b981, #059669); }

.stat-content h3 {
    font-size: 1.8rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 0.25rem;
}

.stat-content p {
    color: var(--text-secondary);
    font-size: 0.95rem;
    margin-bottom: 0.5rem;
}

.stat-trend {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.85rem;
    color: var(--success);
}

/* Section Headers */
.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.section-header h2 {
    font-size: 1.8rem;
    font-weight: 600;
    color: var(--text-primary);
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.section-header p {
    color: var(--text-secondary);
    font-size: 1rem;
}

.view-all-link {
    color: var(--accent-pink);
    text-decoration: none;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
}

.view-all-link:hover {
    color: var(--accent-cyan);
    transform: translateX(3px);
}

/* Quick Practice */
.quick-practice {
    margin-bottom: 4rem;
}

.quick-options {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
}

.quick-option {
    background: var(--gradient-card);
    backdrop-filter: blur(var(--blur-amount));
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius-lg);
    padding: 2rem;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.quick-option:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 40px rgba(139, 92, 246, 0.2);
    border-color: var(--accent-pink);
}

.quick-option.recommended {
    border-color: var(--accent-pink);
    background: linear-gradient(145deg, rgba(255, 107, 157, 0.05), rgba(139, 92, 246, 0.05));
}

.quick-option.recommended::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: var(--gradient-accent);
}

.option-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
}

.option-icon {
    width: 60px;
    height: 60px;
    background: var(--gradient-button);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
}

.recommended-badge {
    background: var(--gradient-button);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: var(--border-radius);
    font-size: 0.8rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.option-content h3 {
    font-size: 1.3rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 0.75rem;
}

.option-content p {
    color: var(--text-secondary);
    margin-bottom: 1.5rem;
    line-height: 1.5;
}

.option-details {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.detail-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--text-secondary);
    font-size: 0.85rem;
    padding: 0.5rem 0.75rem;
    background: rgba(255, 255, 255, 0.05);
    border-radius: var(--border-radius);
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.detail-item i {
    color: var(--accent-pink);
}

/* Practice Categories */
.practice-categories {
    margin-bottom: 4rem;
}

.categories-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
}

.category-card {
    background: var(--gradient-card);
    backdrop-filter: blur(var(--blur-amount));
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius-lg);
    padding: 2rem;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 1.5rem;
}

.category-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 30px rgba(139, 92, 246, 0.15);
    border-color: var(--accent-pink);
}

.category-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
    flex-shrink: 0;
}

.category-icon.programming { background: linear-gradient(45deg, #ff6b9d, #c084fc); }
.category-icon.literature { background: linear-gradient(45deg, #00d4ff, #0ea5e9); }
.category-icon.science { background: linear-gradient(45deg, #10b981, #059669); }
.category-icon.business { background: linear-gradient(45deg, #f59e0b, #eab308); }
.category-icon.technology { background: linear-gradient(45deg, #8b5cf6, #a855f7); }
.category-icon.custom { background: linear-gradient(45deg, #ef4444, #dc2626); }

.category-content {
    flex: 1;
}

.category-content h3 {
    font-size: 1.2rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 0.5rem;
}

.category-content p {
    color: var(--text-secondary);
    font-size: 0.9rem;
    margin-bottom: 1rem;
}

.category-stats {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--text-secondary);
    font-size: 0.85rem;
}

.stat-item i {
    color: var(--accent-pink);
    width: 14px;
}

/* Recent Sessions */
.recent-sessions {
    margin-bottom: 4rem;
}

.sessions-list {
    background: var(--gradient-card);
    backdrop-filter: blur(var(--blur-amount));
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius-lg);
    padding: 2rem;
}

.session-item {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    padding: 1.5rem;
    background: rgba(255, 255, 255, 0.03);
    border-radius: var(--border-radius);
    margin-bottom: 1rem;
    border: 1px solid rgba(255, 255, 255, 0.05);
    transition: all 0.3s ease;
}

.session-item:hover {
    background: rgba(255, 255, 255, 0.06);
    border-color: var(--accent-pink);
}

.session-item:last-child {
    margin-bottom: 0;
}

.session-icon {
    width: 50px;
    height: 50px;
    background: var(--gradient-button);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    color: white;
    flex-shrink: 0;
}

.session-content {
    flex: 1;
}

.session-content h4 {
    color: var(--text-primary);
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.session-content p {
    color: var(--text-secondary);
    font-size: 0.9rem;
}

.session-stats {
    display: flex;
    gap: 2rem;
}

.session-stat {
    text-align: center;
}

.stat-value {
    display: block;
    font-size: 1.2rem;
    font-weight: 700;
    color: var(--text-primary);
}

.stat-label {
    font-size: 0.8rem;
    color: var(--text-secondary);
}

.no-sessions {
    text-align: center;
    padding: 4rem 2rem;
}

.no-sessions-icon {
    width: 100px;
    height: 100px;
    background: var(--gradient-button);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 2rem;
    font-size: 3rem;
    color: white;
}

.no-sessions h3 {
    color: var(--text-primary);
    margin-bottom: 1rem;
}

.no-sessions p {
    color: var(--text-secondary);
    margin-bottom: 2rem;
}

/* Progress Chart */
.progress-chart-section {
    margin-bottom: 2rem;
}

.chart-controls {
    display: flex;
    gap: 0.5rem;
}

.chart-btn {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    color: var(--text-secondary);
    padding: 0.5rem 1rem;
    border-radius: var(--border-radius);
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.9rem;
}

.chart-btn:hover {
    border-color: var(--accent-pink);
    color: var(--text-primary);
}

.chart-btn.active {
    background: var(--gradient-button);
    border-color: var(--accent-pink);
    color: white;
}

.chart-container {
    background: var(--gradient-card);
    backdrop-filter: blur(var(--blur-amount));
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius-lg);
    padding: 2rem;
    margin-top: 2rem;
}

/* Modals */
.modal {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.8);
    backdrop-filter: blur(10px);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 1000;
    padding: 2rem;
}

.modal.show {
    display: flex;
}

.modal-content {
    background: var(--gradient-card);
    backdrop-filter: blur(var(--blur-amount));
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius-lg);
    width: 100%;
    max-width: 500px;
    max-height: 80vh;
    overflow-y: auto;
    position: relative;
}

.modal-content.large {
    max-width: 700px;
}

.modal-content::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: var(--gradient-accent);
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 2rem 2rem 0;
}

.modal-header h3 {
    color: var(--text-primary);
    font-size: 1.3rem;
    font-weight: 600;
}

.close-btn {
    background: none;
    border: none;
    color: var(--text-secondary);
    font-size: 1.2rem;
    cursor: pointer;
    padding: 0.5rem;
    border-radius: 50%;
    transition: all 0.3s ease;
}

.close-btn:hover {
    color: var(--accent-pink);
    background: rgba(255, 107, 157, 0.1);
}

.modal-body {
    padding: 2rem;
}

.modal-footer {
    padding: 0 2rem 2rem;
    text-align: center;
}

.difficulty-selection, .duration-selection {
    margin-bottom: 2rem;
}

.difficulty-selection h4, .duration-selection h4 {
    color: var(--text-primary);
    margin-bottom: 1rem;
}

.difficulty-options {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.difficulty-option {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius);
    cursor: pointer;
    transition: all 0.3s ease;
}

.difficulty-option:hover {
    border-color: var(--accent-pink);
}

.difficulty-option.active {
    border-color: var(--accent-pink);
    background: rgba(255, 107, 157, 0.05);
}

.difficulty-icon {
    width: 40px;
    height: 40px;
    background: var(--gradient-button);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    flex-shrink: 0;
}

.difficulty-info h5 {
    color: var(--text-primary);
    margin-bottom: 0.25rem;
}

.difficulty-info p {
    color: var(--text-secondary);
    font-size: 0.9rem;
}

.duration-options {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.duration-btn {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    color: var(--text-secondary);
    padding: 0.75rem 1.5rem;
    border-radius: var(--border-radius);
    cursor: pointer;
    transition: all 0.3s ease;
}

.duration-btn:hover {
    border-color: var(--accent-pink);
    color: var(--text-primary);
}

.duration-btn.active {
    background: var(--gradient-button);
    border-color: var(--accent-pink);
    color: white;
}

.custom-text-area {
    margin-bottom: 1rem;
}

.custom-text-area label {
    display: block;
    color: var(--text-primary);
    font-weight: 600;
    margin-bottom: 1rem;
}

#customText {
    width: 100%;
    background: rgba(255, 255, 255, 0.05);
    border: 2px solid rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius);
    color: var(--text-primary);
    padding: 1rem;
    font-family: 'Courier New', monospace;
    font-size: 1rem;
    line-height: 1.6;
    resize: vertical;
    transition: all 0.3s ease;
}

#customText:focus {
    outline: none;
    border-color: var(--accent-pink);
    background: rgba(255, 255, 255, 0.08);
    box-shadow: 0 0 0 3px rgba(255, 107, 157, 0.1);
}

.text-info {
    display: flex;
    justify-content: space-between;
    color: var(--text-secondary);
    font-size: 0.9rem;
    margin-top: 1rem;
}

/* Responsive */
@media (max-width: 1024px) {
    .practice-header {
        flex-direction: column;
        text-align: center;
        gap: 2rem;
    }
    
    .quick-options {
        grid-template-columns: 1fr;
    }
    
    .categories-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .header-content h1 {
        font-size: 2rem;
    }
    
    .practice-stats {
        grid-template-columns: 1fr;
    }
    
    .section-header {
        flex-direction: column;
        gap: 0.5rem;
        text-align: center;
    }
    
    .session-item {
        flex-direction: column;
        text-align: center;
        gap: 1rem;
    }
    
    .session-stats {
        justify-content: center;
    }
    
    .difficulty-options {
        gap: 0.5rem;
    }
    
    .duration-options {
        flex-direction: column;
    }
}
</style>

<script>
// Global variables
let selectedCategory = '';
let selectedDifficulty = 'beginner';
let selectedDuration = 60;

document.addEventListener('DOMContentLoaded', function() {
    setupEventListeners();
    initializeChart();
});

function setupEventListeners() {
    // Custom text word count
    const customText = document.getElementById('customText');
    if (customText) {
        customText.addEventListener('input', updateTextCount);
    }
    
    // Difficulty selection
    document.querySelectorAll('.difficulty-option').forEach(option => {
        option.addEventListener('click', function() {
            document.querySelectorAll('.difficulty-option').forEach(o => o.classList.remove('active'));
            this.classList.add('active');
            selectedDifficulty = this.dataset.difficulty;
        });
    });
    
    // Duration selection
    document.querySelectorAll('.duration-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.duration-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            selectedDuration = parseInt(this.dataset.duration);
        });
    });
    
    // Chart controls
    document.querySelectorAll('.chart-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.chart-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            updateChart(this.dataset.period);
        });
    });
}

function startPractice(type) {
    let url = '{{ route("practice.show") }}';
    
    switch(type) {
        case 'recommended':
            url += '?mode=smart';
            break;
        case 'speed':
            url += '?mode=speed';
            break;
        case 'accuracy':
            url += '?mode=accuracy';
            break;
    }
    
    window.location.href = url;
}

function showCategoryModal(category) {
    selectedCategory = category;
    document.getElementById('categoryModalTitle').textContent = category.charAt(0).toUpperCase() + category.slice(1) + ' Practice';
    document.getElementById('categoryModal').classList.add('show');
}

function closeCategoryModal() {
    document.getElementById('categoryModal').classList.remove('show');
}

function startCategoryPractice() {
    const url = {{ route("practice.show") }}?mode=category&category=${selectedCategory}&difficulty=${selectedDifficulty}&duration=${selectedDuration};
    window.location.href = url;
}

function showCustomTextModal() {
    document.getElementById('customTextModal').classList.add('show');
}

function closeCustomTextModal() {
    document.getElementById('customTextModal').classList.remove('show');
}

function startCustomPractice() {
    const text = document.getElementById('customText').value.trim();
    if (text.length < 50) {
        alert('Please enter at least 50 characters for accurate results.');
        return;
    }
    
    // Save custom text to session
    sessionStorage.setItem('custom_practice_text', text);
    
    const url = {{ route("practice.show") }}?mode=custom;
    window.location.href = url;
}

function loadSampleText() {
    const sampleTexts = [
        "The evolution of artificial intelligence has transformed how we interact with technology. Machine learning algorithms now power everything from recommendation systems to autonomous vehicles, making our daily lives more efficient and connected than ever before.",
        "Sustainable development requires a balance between economic growth, environmental protection, and social equity. As global challenges like climate change intensify, innovative solutions and collaborative efforts become increasingly crucial for our planet's future.",
        "The art of effective communication extends beyond mere words. It encompasses active listening, empathy, and the ability to adapt your message to your audience. In our digital age, these skills remain fundamental to building meaningful relationships."
    ];
    
    const randomText = sampleTexts[Math.floor(Math.random() * sampleTexts.length)];
    document.getElementById('customText').value = randomText;
    updateTextCount();
}

function updateTextCount() {
    const text = document.getElementById('customText').value;
    const words = text.trim() === '' ? 0 : text.trim().split(/\s+/).length;
    const chars = text.length;
    
    document.getElementById('wordCount').textContent = words + ' words';
    document.getElementById('charCount').textContent = chars + ' characters';
}

function retrySession(sessionId) {
    // In a real application, this would load the specific session data
    window.location.href = {{ route("practice.show") }}?retry=${sessionId};
}

function initializeChart() {
    const canvas = document.getElementById('progressChart');
    if (!canvas) return;
    
    const ctx = canvas.getContext('2d');
    
    // Sample data - in real app, this would come from the backend
    const sampleData = {
        labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
        wpmData: [45, 48, 52, 47, 55, 58, 62],
        accuracyData: [92, 94, 91, 96, 93, 95, 97]
    };
    
    drawChart(ctx, sampleData);
}

function drawChart(ctx, data) {
    const canvas = ctx.canvas;
    const width = canvas.width;
    const height = canvas.height;
    
    // Clear canvas
    ctx.clearRect(0, 0, width, height);
    
    // Set styles
    ctx.fillStyle = 'rgba(255, 255, 255, 0.1)';
    ctx.strokeStyle = '#ff6b9d';
    ctx.lineWidth = 3;
    
    // Draw WPM line
    ctx.beginPath();
    const maxWPM = Math.max(...data.wpmData);
    const minWPM = Math.min(...data.wpmData);
    
    data.wpmData.forEach((wpm, index) => {
        const x = (index / (data.wpmData.length - 1)) * (width - 100) + 50;
        const y = height - 50 - ((wpm - minWPM) / (maxWPM - minWPM)) * (height - 100);
        
        if (index === 0) {
            ctx.moveTo(x, y);
        } else {
            ctx.lineTo(x, y);
        }
        
        // Draw point
        ctx.fillRect(x - 3, y - 3, 6, 6);
    });
    
    ctx.stroke();
    
    // Draw labels
    ctx.fillStyle = '#b4a7d1';
    ctx.font = '12px Arial';
    ctx.textAlign = 'center';
    
    data.labels.forEach((label, index) => {
        const x = (index / (data.labels.length - 1)) * (width - 100) + 50;
        ctx.fillText(label, x, height - 20);
    });
}

function updateChart(period) {
    // In a real application, this would fetch new data based on the period
    console.log('Updating chart for period:', period);
}

// Close modals when clicking outside
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('modal')) {
        e.target.classList.remove('show');
    }
});

// ESC key to close modals
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const modals = document.querySelectorAll('.modal.show');
        modals.forEach(modal => modal.classList.remove('show'));
    }
});
</script>
@endsection