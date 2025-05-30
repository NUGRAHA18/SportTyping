@extends('layouts.app')

@section('content')
<div class="practice-gallery-container">
    <div class="container-fluid">
        <!-- Practice Header -->
        <div class="practice-header">
            <div class="header-content">
                <div class="header-info">
                    <h1 class="page-title">
                        <i class="fas fa-keyboard"></i>
                        Practice Typing
                    </h1>
                    <p class="page-subtitle">
                        Master your typing skills with our collection of texts across different categories and difficulty levels.
                        Track your progress and compete with yourself to achieve new records!
                    </p>
                </div>
                
                <div class="header-stats">
                    @auth
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <div class="stat-content">
                                <span class="stat-value">{{ number_format(Auth::user()->profile?->typing_speed_avg ?? 0) }}</span>
                                <span class="stat-label">Avg WPM</span>
                            </div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-bullseye"></i>
                            </div>
                            <div class="stat-content">
                                <span class="stat-value">{{ number_format(Auth::user()->profile?->typing_accuracy_avg ?? 0, 1) }}%</span>
                                <span class="stat-label">Accuracy</span>
                            </div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-fire"></i>
                            </div>
                            <div class="stat-content">
                                <span class="stat-value">{{ Auth::user()->practices()->count() }}</span>
                                <span class="stat-label">Sessions</span>
                            </div>
                        </div>
                    @else
                        <div class="guest-cta">
                            <div class="cta-content">
                                <h3>Ready to improve?</h3>
                                <p>Sign up to track your progress!</p>
                                <a href="{{ route('register') }}" class="cta-btn">
                                    <i class="fas fa-user-plus"></i>
                                    Join Now
                                </a>
                            </div>
                        </div>
                    @endauth
                </div>
            </div>
        </div>

        <!-- Practice Filters -->
        <div class="practice-filters">
            <div class="filter-section">
                <h3 class="filter-title">
                    <i class="fas fa-filter"></i>
                    Filter Texts
                </h3>
                
                <div class="filter-controls">
                    <!-- Category Filter -->
                    <div class="filter-group">
                        <label class="filter-label">Category</label>
                        <div class="filter-options">
                            <button class="filter-btn {{ !request('category') ? 'active' : '' }}" 
                                    onclick="updateFilter('category', '')">
                                <span>All Categories</span>
                                <span class="count">{{ $texts->total() }}</span>
                            </button>
                            
                            @foreach($categories as $category)
                            <button class="filter-btn {{ request('category') == $category->id ? 'active' : '' }}" 
                                    onclick="updateFilter('category', '{{ $category->id }}')">
                                <span>{{ $category->name }}</span>
                                <span class="count">{{ $category->texts_count }}</span>
                            </button>
                            @endforeach
                        </div>
                    </div>
                    
                    <!-- Difficulty Filter -->
                    <div class="filter-group">
                        <label class="filter-label">Difficulty</label>
                        <div class="filter-options">
                            <button class="filter-btn {{ !request('difficulty') ? 'active' : '' }}" 
                                    onclick="updateFilter('difficulty', '')">
                                <span>All Levels</span>
                            </button>
                            
                            @foreach(['beginner', 'intermediate', 'advanced', 'expert'] as $level)
                            <button class="filter-btn {{ request('difficulty') == $level ? 'active' : '' }}" 
                                    onclick="updateFilter('difficulty', '{{ $level }}')">
                                <span>{{ ucfirst($level) }}</span>
                            </button>
                            @endforeach
                        </div>
                    </div>
                    
                    <!-- Quick Actions -->
                    <div class="filter-group">
                        <label class="filter-label">Quick Start</label>
                        <div class="quick-actions">
                            <button class="action-btn primary" onclick="randomPractice()">
                                <i class="fas fa-random"></i>
                                Random Text
                            </button>
                            
                            <button class="action-btn secondary" onclick="personalizedPractice()">
                                <i class="fas fa-user-cog"></i>
                                For You
                            </button>
                            
                            @auth
                            <button class="action-btn success" onclick="continuePractice()">
                                <i class="fas fa-play"></i>
                                Continue
                            </button>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Practice Texts Grid -->
        <div class="practice-texts-section">
            <div class="section-header">
                <h2 class="section-title">
                    Practice Texts
                    <span class="text-count">({{ $texts->total() }} available)</span>
                </h2>
                
                <div class="view-controls">
                    <button class="view-btn {{ session('view_mode', 'grid') == 'grid' ? 'active' : '' }}" 
                            onclick="setViewMode('grid')" title="Grid View">
                        <i class="fas fa-th"></i>
                    </button>
                    <button class="view-btn {{ session('view_mode', 'grid') == 'list' ? 'active' : '' }}" 
                            onclick="setViewMode('list')" title="List View">
                        <i class="fas fa-list"></i>
                    </button>
                </div>
            </div>
            
            <div class="texts-grid {{ session('view_mode', 'grid') == 'list' ? 'list-view' : 'grid-view' }}" id="textsGrid">
                @forelse($texts as $text)
                <div class="text-card" data-category="{{ $text->category_id }}" data-difficulty="{{ $text->difficulty_level }}">
                    <div class="card-header">
                        <div class="text-category">
                            <i class="fas fa-{{ $text->category->name == 'Programming' ? 'code' : ($text->category->name == 'Literature' ? 'book' : ($text->category->name == 'Science' ? 'flask' : ($text->category->name == 'Technology' ? 'microchip' : ($text->category->name == 'Business' ? 'briefcase' : 'file-text')))) }}"></i>
                            <span>{{ $text->category->name }}</span>
                        </div>
                        
                        <div class="difficulty-badge {{ $text->difficulty_level }}">
                            {{ ucfirst($text->difficulty_level) }}
                        </div>
                    </div>
                    
                    <div class="card-content">
                        <h3 class="text-title">{{ $text->title }}</h3>
                        
                        <div class="text-preview">
                            {{ Str::limit($text->content, 120) }}
                        </div>
                        
                        <div class="text-stats">
                            <div class="stat-item">
                                <i class="fas fa-font"></i>
                                <span>{{ $text->word_count }} words</span>
                            </div>
                            
                            <div class="stat-item">
                                <i class="fas fa-clock"></i>
                                <span>~{{ ceil($text->word_count / 40) }} min</span>
                            </div>
                            
                            @auth
                                @php
                                    $userBest = Auth::user()->practices()
                                        ->where('text_id', $text->id)
                                        ->orderBy('typing_speed', 'desc')
                                        ->first();
                                @endphp
                                
                                @if($userBest)
                                <div class="stat-item personal">
                                    <i class="fas fa-medal"></i>
                                    <span>{{ number_format($userBest->typing_speed) }} WPM</span>
                                </div>
                                @endif
                            @endauth
                        </div>
                    </div>
                    
                    <div class="card-actions">
                        <a href="{{ route('practice.show', $text) }}" class="practice-btn primary">
                            <i class="fas fa-play"></i>
                            <span>Start Practice</span>
                        </a>
                        
                        <button class="practice-btn secondary" onclick="previewText({{ $text->id }})">
                            <i class="fas fa-eye"></i>
                        </button>
                        
                        <button class="practice-btn secondary" onclick="addToFavorites({{ $text->id }})">
                            <i class="fas fa-heart"></i>
                        </button>
                    </div>
                    
                    <!-- Progress indicator for logged in users -->
                    @auth
                        @if($userBest)
                        <div class="progress-indicator">
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: {{ min(100, ($userBest->typing_speed / 100) * 100) }}%"></div>
                            </div>
                            <span class="progress-text">Best: {{ number_format($userBest->typing_speed) }} WPM</span>
                        </div>
                        @endif
                    @endauth
                </div>
                @empty
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <h3>No texts found</h3>
                    <p>Try adjusting your filters or browse all categories.</p>
                    <button class="empty-action" onclick="clearFilters()">
                        <i class="fas fa-undo"></i>
                        Clear Filters
                    </button>
                </div>
                @endforelse
            </div>
            
            <!-- Pagination -->
            @if($texts->hasPages())
            <div class="pagination-wrapper">
                {{ $texts->withQueryString()->links('pagination::bootstrap-4') }}
            </div>
            @endif
        </div>

        <!-- Featured Practice Challenges -->
        <div class="featured-challenges">
            <h2 class="section-title">
                <i class="fas fa-trophy"></i>
                Featured Challenges
            </h2>
            
            <div class="challenges-grid">
                <div class="challenge-card daily">
                    <div class="challenge-icon">
                        <i class="fas fa-calendar-day"></i>
                    </div>
                    <div class="challenge-content">
                        <h3>Daily Challenge</h3>
                        <p>Complete today's special text and earn bonus points!</p>
                        <div class="challenge-reward">+50 EXP</div>
                    </div>
                    <button class="challenge-btn">
                        <i class="fas fa-play"></i>
                        Start
                    </button>
                </div>
                
                <div class="challenge-card speed">
                    <div class="challenge-icon">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <div class="challenge-content">
                        <h3>Speed Demon</h3>
                        <p>Type 3 texts in under 5 minutes total!</p>
                        <div class="challenge-reward">+100 EXP</div>
                    </div>
                    <button class="challenge-btn">
                        <i class="fas fa-play"></i>
                        Start
                    </button>
                </div>
                
                <div class="challenge-card accuracy">
                    <div class="challenge-icon">
                        <i class="fas fa-bullseye"></i>
                    </div>
                    <div class="challenge-content">
                        <h3>Perfect Accuracy</h3>
                        <p>Achieve 100% accuracy on any text!</p>
                        <div class="challenge-reward">+75 EXP</div>
                    </div>
                    <button class="challenge-btn">
                        <i class="fas fa-play"></i>
                        Start
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Text Preview Modal -->
<div class="text-preview-modal" id="textPreviewModal" style="display: none;">
    <div class="modal-overlay" onclick="closePreviewModal()"></div>
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="previewTitle">Text Preview</h3>
            <button class="modal-close" onclick="closePreviewModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="modal-body">
            <div class="preview-content" id="previewContent">
                <!-- Text content will be loaded here -->
            </div>
            
            <div class="preview-stats" id="previewStats">
                <!-- Text statistics will be shown here -->
            </div>
        </div>
        
        <div class="modal-footer">
            <button class="modal-btn secondary" onclick="closePreviewModal()">
                <i class="fas fa-times"></i>
                Close
            </button>
            <button class="modal-btn primary" id="startPracticeBtn">
                <i class="fas fa-play"></i>
                Start Practice
            </button>
        </div>
    </div>
</div>

<style>
.practice-gallery-container {
    padding: 2rem 0;
    background: var(--bg-primary);
    min-height: calc(100vh - 76px);
}

/* Practice Header */
.practice-header {
    margin-bottom: 3rem;
    background: var(--bg-card);
    border-radius: var(--border-radius-xl);
    border: 1px solid var(--border-light);
    box-shadow: var(--shadow-md);
    overflow: hidden;
    position: relative;
}

.practice-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: var(--champion-gradient);
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 2.5rem;
    gap: 2rem;
}

.header-info {
    flex: 1;
}

.page-title {
    font-family: var(--font-display);
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.page-title i {
    color: var(--accent-primary);
}

.page-subtitle {
    color: var(--text-secondary);
    font-size: 1.1rem;
    line-height: 1.6;
    max-width: 600px;
}

.header-stats {
    display: flex;
    gap: 1.5rem;
}

.stat-card {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.5rem;
    background: var(--bg-secondary);
    border-radius: var(--border-radius-lg);
    border: 1px solid var(--border-light);
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-3px);
    box-shadow: var(--shadow-lg);
}

.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: var(--champion-gradient);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.25rem;
}

.stat-content {
    display: flex;
    flex-direction: column;
}

.stat-value {
    font-size: 1.75rem;
    font-weight: 700;
    color: var(--text-primary);
    line-height: 1.2;
    font-family: var(--font-display);
}

.stat-label {
    color: var(--text-secondary);
    font-size: 0.875rem;
    font-weight: 500;
}

.guest-cta {
    background: var(--champion-gradient);
    color: white;
    padding: 2rem;
    border-radius: var(--border-radius-lg);
    text-align: center;
}

.cta-content h3 {
    margin-bottom: 0.5rem;
    font-family: var(--font-display);
}

.cta-content p {
    margin-bottom: 1.5rem;
    opacity: 0.9;
}

.cta-btn {
    background: white;
    color: var(--accent-primary);
    padding: 1rem 2rem;
    border-radius: var(--border-radius);
    text-decoration: none;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    transition: all 0.3s ease;
}

.cta-btn:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
    text-decoration: none;
    color: var(--accent-primary);
}

/* Practice Filters */
.practice-filters {
    margin-bottom: 3rem;
}

.filter-section {
    background: var(--bg-card);
    border-radius: var(--border-radius-lg);
    border: 1px solid var(--border-light);
    padding: 2rem;
    box-shadow: var(--shadow-sm);
}

.filter-title {
    font-family: var(--font-display);
    color: var(--text-primary);
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.filter-title i {
    color: var(--accent-primary);
}

.filter-controls {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
}

.filter-group {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.filter-label {
    font-weight: 600;
    color: var(--text-primary);
    font-size: 0.95rem;
}

.filter-options {
    display: flex;
    flex-wrap: wrap;
    gap: 0.75rem;
}

.filter-btn {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 0.75rem;
    padding: 0.75rem 1.25rem;
    background: var(--bg-secondary);
    border: 1px solid var(--border-light);
    border-radius: var(--border-radius);
    color: var(--text-secondary);
    cursor: pointer;
    transition: all 0.3s ease;
    font-weight: 500;
}

.filter-btn:hover {
    background: rgba(59, 130, 246, 0.05);
    border-color: var(--accent-primary);
    color: var(--accent-primary);
}

.filter-btn.active {
    background: var(--champion-gradient);
    border-color: var(--accent-primary);
    color: white;
}

.filter-btn .count {
    background: rgba(255, 255, 255, 0.2);
    padding: 0.25rem 0.5rem;
    border-radius: var(--border-radius-sm);
    font-size: 0.8rem;
    font-weight: 600;
}

.filter-btn.active .count {
    background: rgba(255, 255, 255, 0.3);
}

.quick-actions {
    display: flex;
    gap: 1rem;
}

.action-btn {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem 1.5rem;
    border: none;
    border-radius: var(--border-radius);
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.action-btn.primary {
    background: var(--champion-gradient);
    color: white;
}

.action-btn.secondary {
    background: transparent;
    border: 2px solid var(--accent-primary);
    color: var(--accent-primary);
}

.action-btn.success {
    background: var(--victory-gradient);
    color: white;
}

.action-btn:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.action-btn.secondary:hover {
    background: var(--accent-primary);
    color: white;
}

/* Practice Texts Section */
.practice-texts-section {
    margin-bottom: 3rem;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.section-title {
    font-family: var(--font-display);
    font-size: 1.75rem;
    color: var(--text-primary);
}

.text-count {
    font-size: 1rem;
    color: var(--text-muted);
    font-weight: 400;
}

.view-controls {
    display: flex;
    gap: 0.5rem;
}

.view-btn {
    width: 40px;
    height: 40px;
    border: 1px solid var(--border-light);
    background: var(--bg-secondary);
    border-radius: var(--border-radius);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--text-secondary);
    cursor: pointer;
    transition: all 0.3s ease;
}

.view-btn:hover,
.view-btn.active {
    background: var(--accent-primary);
    border-color: var(--accent-primary);
    color: white;
}

/* Texts Grid */
.texts-grid.grid-view {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 2rem;
}

.texts-grid.list-view {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.text-card {
    background: var(--bg-card);
    border-radius: var(--border-radius-lg);
    border: 1px solid var(--border-light);
    overflow: hidden;
    transition: all 0.3s ease;
    position: relative;
    box-shadow: var(--shadow-sm);
}

.text-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-lg);
}

.list-view .text-card {
    display: flex;
    align-items: center;
    padding: 1.5rem;
}

.list-view .text-card:hover {
    transform: translateX(10px);
}

.card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem 1.5rem 0;
}

.list-view .card-header {
    padding: 0;
    margin-right: 1.5rem;
}

.text-category {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--text-secondary);
    font-size: 0.875rem;
    font-weight: 500;
}

.difficulty-badge {
    padding: 0.375rem 0.75rem;
    border-radius: var(--border-radius-sm);
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.difficulty-badge.beginner {
    background: rgba(16, 185, 129, 0.1);
    color: var(--accent-success);
}

.difficulty-badge.intermediate {
    background: rgba(59, 130, 246, 0.1);
    color: var(--accent-primary);
}

.difficulty-badge.advanced {
    background: rgba(245, 158, 11, 0.1);
    color: var(--accent-secondary);
}

.difficulty-badge.expert {
    background: rgba(239, 68, 68, 0.1);
    color: var(--accent-danger);
}

.card-content {
    padding: 1.5rem;
}

.list-view .card-content {
    flex: 1;
    padding: 0 1.5rem;
}

.text-title {
    font-family: var(--font-display);
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 1rem;
}

.text-preview {
    color: var(--text-secondary);
    line-height: 1.6;
    margin-bottom: 1.5rem;
    font-size: 0.95rem;
}

.text-stats {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--text-muted);
    font-size: 0.875rem;
}

.stat-item.personal {
    color: var(--accent-secondary);
    font-weight: 600;
}

.card-actions {
    display: flex;
    gap: 0.75rem;
    padding: 1.5rem;
    border-top: 1px solid var(--border-light);
}

.list-view .card-actions {
    border-top: none;
    padding: 0;
}

.practice-btn {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.25rem;
    border: none;
    border-radius: var(--border-radius);
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
}

.practice-btn.primary {
    background: var(--champion-gradient);
    color: white;
    flex: 1;
    justify-content: center;
}

.practice-btn.secondary {
    background: var(--bg-secondary);
    border: 1px solid var(--border-light);
    color: var(--text-secondary);
    padding: 0.75rem;
}

.practice-btn:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
    text-decoration: none;
    color: white;
}

.practice-btn.secondary:hover {
    background: var(--accent-primary);
    border-color: var(--accent-primary);
    color: white;
}

.progress-indicator {
    padding: 1rem 1.5rem;
    border-top: 1px solid var(--border-light);
}

.progress-bar {
    width: 100%;
    height: 6px;
    background: var(--border-light);
    border-radius: 3px;
    overflow: hidden;
    margin-bottom: 0.5rem;
}

.progress-fill {
    height: 100%;
    background: var(--victory-gradient);
    border-radius: 3px;
    transition: width 0.8s ease;
}

.progress-text {
    color: var(--text-muted);
    font-size: 0.8rem;
    font-weight: 500;
}

/* Empty State */
.empty-state {
    grid-column: 1 / -1;
    text-align: center;
    padding: 4rem 2rem;
}

.empty-icon {
    font-size: 4rem;
    color: var(--text-muted);
    margin-bottom: 1.5rem;
}

.empty-state h3 {
    color: var(--text-primary);
    margin-bottom: 1rem;
}

.empty-state p {
    color: var(--text-secondary);
    margin-bottom: 2rem;
}

.empty-action {
    background: var(--champion-gradient);
    color: white;
    border: none;
    padding: 1rem 2rem;
    border-radius: var(--border-radius);
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
}

.empty-action:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

/* Featured Challenges */
.featured-challenges {
    margin-bottom: 3rem;
}

.challenges-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
    margin-top: 2rem;
}

.challenge-card {
    background: var(--bg-card);
    border-radius: var(--border-radius-lg);
    border: 1px solid var(--border-light);
    padding: 2rem;
    display: flex;
    align-items: center;
    gap: 1.5rem;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.challenge-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
}

.challenge-card.daily::before {
    background: var(--victory-gradient);
}

.challenge-card.speed::before {
    background: var(--accent-secondary);
}

.challenge-card.accuracy::before {
    background: var(--accent-primary);
}

.challenge-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-lg);
}

.challenge-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    flex-shrink: 0;
}

.challenge-card.daily .challenge-icon {
    background: var(--victory-gradient);
}

.challenge-card.speed .challenge-icon {
    background: var(--medal-gradient);
}

.challenge-card.accuracy .challenge-icon {
    background: var(--champion-gradient);
}

.challenge-content {
    flex: 1;
}

.challenge-content h3 {
    color: var(--text-primary);
    margin-bottom: 0.5rem;
    font-family: var(--font-display);
}

.challenge-content p {
    color: var(--text-secondary);
    font-size: 0.9rem;
    margin-bottom: 1rem;
}

.challenge-reward {
    background: rgba(245, 158, 11, 0.1);
    color: var(--accent-secondary);
    padding: 0.375rem 0.75rem;
    border-radius: var(--border-radius-sm);
    font-size: 0.875rem;
    font-weight: 600;
    display: inline-block;
}

.challenge-btn {
    background: var(--champion-gradient);
    color: white;
    border: none;
    padding: 1rem;
    border-radius: var(--border-radius);
    font-size: 1rem;
    cursor: pointer;
    transition: all 0.3s ease;
    flex-shrink: 0;
}

.challenge-btn:hover {
    transform: scale(1.05);
    box-shadow: var(--shadow-md);
}

/* Text Preview Modal */
.text-preview-modal {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 1000;
    display: flex;
    align-items: center;
    justify-content: center;
    animation: modalFadeIn 0.3s ease;
}

.modal-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.8);
    backdrop-filter: blur(8px);
}

.modal-content {
    background: var(--bg-card);
    border-radius: var(--border-radius-xl);
    border: 1px solid var(--border-light);
    box-shadow: var(--shadow-xl);
    max-width: 800px;
    width: 90%;
    max-height: 90vh;
    overflow: hidden;
    position: relative;
    z-index: 1001;
    animation: modalSlideIn 0.3s ease;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 2rem;
    border-bottom: 1px solid var(--border-light);
    background: var(--champion-gradient);
    color: white;
}

.modal-header h3 {
    font-family: var(--font-display);
    margin: 0;
}

.modal-close {
    background: none;
    border: none;
    color: white;
    font-size: 1.5rem;
    cursor: pointer;
    padding: 0.5rem;
    border-radius: 50%;
    transition: background 0.3s ease;
}

.modal-close:hover {
    background: rgba(255, 255, 255, 0.2);
}

.modal-body {
    padding: 2rem;
    max-height: 400px;
    overflow-y: auto;
}

.preview-content {
    background: var(--bg-secondary);
    padding: 1.5rem;
    border-radius: var(--border-radius);
    font-family: 'JetBrains Mono', monospace;
    line-height: 1.6;
    margin-bottom: 1.5rem;
    border: 1px solid var(--border-light);
}

.preview-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 1rem;
}

.modal-footer {
    display: flex;
    gap: 1rem;
    padding: 2rem;
    border-top: 1px solid var(--border-light);
    background: var(--bg-secondary);
}

.modal-btn {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem 2rem;
    border: none;
    border-radius: var(--border-radius);
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.modal-btn.primary {
    background: var(--champion-gradient);
    color: white;
    flex: 1;
}

.modal-btn.secondary {
    background: transparent;
    border: 2px solid var(--accent-primary);
    color: var(--accent-primary);
}

.modal-btn:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.modal-btn.secondary:hover {
    background: var(--accent-primary);
    color: white;
}

/* Animations */
@keyframes modalFadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes modalSlideIn {
    from { 
        opacity: 0; 
        transform: translateY(-30px) scale(0.95); 
    }
    to { 
        opacity: 1; 
        transform: translateY(0) scale(1); 
    }
}

/* Responsive Design */
@media (max-width: 1200px) {
    .texts-grid.grid-view {
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    }
    
    .filter-controls {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .header-content {
        flex-direction: column;
        gap: 2rem;
    }
    
    .header-stats {
        width: 100%;
        justify-content: center;
        flex-wrap: wrap;
    }
    
    .texts-grid.grid-view {
        grid-template-columns: 1fr;
    }
    
    .filter-options {
        flex-direction: column;
    }
    
    .quick-actions {
        flex-direction: column;
    }
    
    .section-header {
        flex-direction: column;
        gap: 1rem;
        align-items: flex-start;
    }
    
    .challenges-grid {
        grid-template-columns: 1fr;
    }
    
    .challenge-card {
        flex-direction: column;
        text-align: center;
    }
}

@media (max-width: 480px) {
    .practice-gallery-container {
        padding: 1rem 0;
    }
    
    .header-content,
    .filter-section,
    .challenge-card {
        padding: 1.5rem;
    }
    
    .text-card {
        margin: 0 -0.5rem;
    }
    
    .modal-content {
        width: 95%;
        margin: 1rem;
    }
    
    .modal-header,
    .modal-body,
    .modal-footer {
        padding: 1.5rem;
    }
    
    .modal-footer {
        flex-direction: column;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize filters
    initializeFilters();
    
    // Initialize view modes
    initializeViewModes();
    
    // Setup AJAX loading
    setupAjaxFilters();
    
    // Initialize animations
    initializeAnimations();
});

function initializeFilters() {
    const urlParams = new URLSearchParams(window.location.search);
    const activeCategory = urlParams.get('category');
    const activeDifficulty = urlParams.get('difficulty');
    
    // Update active filter buttons
    updateActiveFilters();
}

function updateFilter(type, value) {
    const url = new URL(window.location);
    
    if (value) {
        url.searchParams.set(type, value);
    } else {
        url.searchParams.delete(type);
    }
    
    // Add loading state
    showLoadingState();
    
    // Navigate to new URL
    window.location.href = url.toString();
}

function clearFilters() {
    const url = new URL(window.location);
    url.searchParams.delete('category');
    url.searchParams.delete('difficulty');
    window.location.href = url.toString();
}

function showLoadingState() {
    const grid = document.getElementById('textsGrid');
    if (grid) {
        grid.style.opacity = '0.6';
        grid.style.pointerEvents = 'none';
    }
}

function initializeViewModes() {
    const viewMode = localStorage.getItem('practice_view_mode') || 'grid';
    setViewMode(viewMode, false);
}

function setViewMode(mode, save = true) {
    const grid = document.getElementById('textsGrid');
    const buttons = document.querySelectorAll('.view-btn');
    
    if (grid) {
        grid.className = `texts-grid ${mode}-view`;
    }
    
    buttons.forEach(btn => {
        btn.classList.toggle('active', btn.textContent.toLowerCase().includes(mode));
    });
    
    if (save) {
        localStorage.setItem('practice_view_mode', mode);
        
        // Send to server to save preference
        fetch('/api/user/preferences', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.csrfToken
            },
            body: JSON.stringify({
                practice_view_mode: mode
            })
        }).catch(console.error);
    }
}

function setupAjaxFilters() {
    // Enhanced filtering with AJAX for smoother UX
    const filterButtons = document.querySelectorAll('.filter-btn');
    
    filterButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const type = this.closest('.filter-group').querySelector('.filter-label').textContent.toLowerCase();
            const value = this.onclick.toString().match(/'([^']+)'/)?.[1] || '';
            
            loadFilteredResults(type, value);
        });
    });
}

async function loadFilteredResults(type, value) {
    try {
        showLoadingState();
        
        const url = new URL('/practice', window.location.origin);
        if (value) {
            url.searchParams.set(type, value);
        }
        
        const response = await fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        if (response.ok) {
            const html = await response.text();
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newGrid = doc.getElementById('textsGrid');
            
            if (newGrid) {
                document.getElementById('textsGrid').innerHTML = newGrid.innerHTML;
                updateActiveFilters();
                initializeAnimations();
            }
        }
        
    } catch (error) {
        console.error('Filter loading failed:', error);
        window.showNotification('Failed to load filtered results', 'danger');
    } finally {
        const grid = document.getElementById('textsGrid');
        if (grid) {
            grid.style.opacity = '1';
            grid.style.pointerEvents = 'auto';
        }
    }
}

function updateActiveFilters() {
    const urlParams = new URLSearchParams(window.location.search);
    const filterButtons = document.querySelectorAll('.filter-btn');
    
    filterButtons.forEach(button => {
        const onclick = button.getAttribute('onclick');
        if (onclick) {
            const matches = onclick.match(/updateFilter\('(\w+)', '([^']*)'\)/);
            if (matches) {
                const [, type, value] = matches;
                const isActive = urlParams.get(type) === value || (!urlParams.get(type) && !value);
                button.classList.toggle('active', isActive);
            }
        }
    });
}

function initializeAnimations() {
    // Staggered animation for text cards
    const cards = document.querySelectorAll('.text-card');
    
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        card.style.transition = 'all 0.6s ease';
        
        setTimeout(() => {
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
}

// Quick Actions
function randomPractice() {
    const cards = document.querySelectorAll('.text-card');
    if (cards.length > 0) {
        const randomCard = cards[Math.floor(Math.random() * cards.length)];
        const practiceLink = randomCard.querySelector('.practice-btn.primary');
        if (practiceLink) {
            window.location.href = practiceLink.href;
        }
    }
}

function personalizedPractice() {
    // Redirect to personalized practice based on user stats
    const userAvgWPM = {{ Auth::check() ? Auth::user()->profile?->typing_speed_avg ?? 30 : 30 }};
    let difficulty = 'beginner';
    
    if (userAvgWPM >= 70) difficulty = 'expert';
    else if (userAvgWPM >= 50) difficulty = 'advanced';
    else if (userAvgWPM >= 30) difficulty = 'intermediate';
    
    updateFilter('difficulty', difficulty);
}

function continuePractice() {
    // Continue with last practiced text or suggest next
    @auth
    const lastPracticeUrl = localStorage.getItem('last_practice_url');
    if (lastPracticeUrl) {
        window.location.href = lastPracticeUrl;
    } else {
        personalizedPractice();
    }
    @else
    window.showNotification('Please log in to continue your practice session', 'info');
    @endauth
}

// Text Preview Modal
function previewText(textId) {
    fetch(`/api/texts/${textId}/preview`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showPreviewModal(data.data);
            }
        })
        .catch(error => {
            console.error('Preview failed:', error);
            window.showNotification('Failed to load text preview', 'danger');
        });
}

function showPreviewModal(textData) {
    const modal = document.getElementById('textPreviewModal');
    const title = document.getElementById('previewTitle');
    const content = document.getElementById('previewContent');
    const stats = document.getElementById('previewStats');
    const startBtn = document.getElementById('startPracticeBtn');
    
    title.textContent = textData.title;
    content.textContent = textData.content;
    
    stats.innerHTML = `
        <div class="stat-item">
            <i class="fas fa-font"></i>
            <span>${textData.word_count} words</span>
        </div>
        <div class="stat-item">
            <i class="fas fa-clock"></i>
            <span>~${Math.ceil(textData.word_count / 40)} min</span>
        </div>
        <div class="stat-item">
            <i class="fas fa-layer-group"></i>
            <span>${textData.difficulty_level}</span>
        </div>
        <div class="stat-item">
            <i class="fas fa-tag"></i>
            <span>${textData.category}</span>
        </div>
    `;
    
    startBtn.onclick = () => {
        window.location.href = `/practice/${textData.id}`;
    };
    
    modal.style.display = 'flex';
}

function closePreviewModal() {
    const modal = document.getElementById('textPreviewModal');
    modal.style.display = 'none';
}

function addToFavorites(textId) {
    @auth
    fetch(`/api/texts/${textId}/favorite`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.csrfToken
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.showNotification('Added to favorites!', 'success');
            
            // Update heart icon
            const heartBtn = event.target.closest('.practice-btn');
            if (heartBtn) {
                heartBtn.innerHTML = '<i class="fas fa-heart" style="color: var(--accent-danger);"></i>';
            }
        }
    })
    .catch(error => {
        console.error('Favorite failed:', error);
        window.showNotification('Failed to add to favorites', 'danger');
    });
    @else
    window.showNotification('Please log in to save favorites', 'info');
    @endauth
}

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closePreviewModal();
    }
    
    if (e.key === 'r' && e.ctrlKey) {
        e.preventDefault();
        randomPractice();
    }
});

console.log('✅ Practice gallery loaded successfully!');
</script>
@endsection
