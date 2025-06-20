@extends('layouts.app')

@section('content')
<div class="practice-container">
    <div class="container">
        <!-- Practice Header -->
        <div class="practice-header">
            <div class="header-content">
                <h1 class="page-title">
                    <i class="fas fa-keyboard"></i>
                    Typing Practice
                </h1>
                <p class="page-subtitle">
                    Improve your typing skills with our diverse collection of practice texts
                </p>
            </div>
            <div class="header-stats">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-tachometer-alt"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">{{ number_format(Auth::user()->profile?->typing_speed_avg ?? 0, 1) }}</div>
                        <div class="stat-label">Avg WPM</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-bullseye"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">{{ number_format(Auth::user()->profile?->typing_accuracy_avg ?? 0, 1) }}%</div>
                        <div class="stat-label">Accuracy</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Practice Filters -->
        <div class="practice-filters">
            <div class="filter-section">
                <h3 class="filter-title">
                    <i class="fas fa-filter"></i>
                    Filter by Category
                </h3>
                <div class="category-filters">
                    <button class="category-filter active" data-category="all">
                        <i class="fas fa-globe"></i>
                        All Categories
                        <span class="count">{{ $texts->total() }}</span>
                    </button>
                    @foreach($categories as $category)
                    <button class="category-filter" data-category="{{ $category->id }}">
                        <i class="fas fa-{{ $category->name == 'Programming' ? 'code' : ($category->name == 'Literature' ? 'book' : ($category->name == 'Science' ? 'flask' : ($category->name == 'Business' ? 'briefcase' : 'file-text'))) }}"></i>
                        {{ $category->name }}
                        <span class="count">{{ $category->texts_count }}</span>
                    </button>
                    @endforeach
                </div>
            </div>
            
            <div class="filter-section">
                <h3 class="filter-title">
                    <i class="fas fa-signal"></i>
                    Difficulty Level
                </h3>
                <div class="difficulty-filters">
                    <button class="difficulty-filter active" data-difficulty="all">
                        All Levels
                    </button>
                    <button class="difficulty-filter beginner" data-difficulty="beginner">
                        <i class="fas fa-seedling"></i>
                        Beginner
                    </button>
                    <button class="difficulty-filter intermediate" data-difficulty="intermediate">
                        <i class="fas fa-mountain"></i>
                        Intermediate
                    </button>
                    <button class="difficulty-filter advanced" data-difficulty="advanced">
                        <i class="fas fa-fire"></i>
                        Advanced
                    </button>
                </div>
            </div>
            
            <div class="filter-section">
                <h3 class="filter-title">
                    <i class="fas fa-sort"></i>
                    Sort By
                </h3>
                <div class="sort-filters">
                    <select class="sort-select" id="sort-select">
                        <option value="newest">Newest First</option>
                        <option value="popular">Most Popular</option>
                        <option value="difficulty">Difficulty</option>
                        <option value="length">Text Length</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Quick Practice Section -->
        <div class="quick-practice-section">
            <div class="section-header">
                <h2 class="section-title">
                    <i class="fas fa-bolt"></i>
                    Quick Practice
                </h2>
                <p class="section-subtitle">Jump right into practice with popular texts</p>
            </div>
            
            <div class="quick-practice-grid">
                <div class="quick-practice-card random-text">
                    <div class="card-icon">
                        <i class="fas fa-random"></i>
                    </div>
                    <div class="card-content">
                        <h3>Random Text</h3>
                        <p>Practice with a randomly selected text</p>
                        <div class="card-meta">
                            <span class="difficulty mixed">Mixed Difficulty</span>
                            <span class="length">Various Lengths</span>
                        </div>
                    </div>
                    <button class="btn btn-primary quick-btn" onclick="startRandomPractice()">
                        <i class="fas fa-play"></i>
                        Start
                    </button>
                </div>
                
                <div class="quick-practice-card speed-test">
                    <div class="card-icon">
                        <i class="fas fa-stopwatch"></i>
                    </div>
                    <div class="card-content">
                        <h3>1-Minute Speed Test</h3>
                        <p>Quick typing speed assessment</p>
                        <div class="card-meta">
                            <span class="difficulty intermediate">Intermediate</span>
                            <span class="length">Timed Test</span>
                        </div>
                    </div>
                    <button class="btn btn-warning quick-btn" onclick="startSpeedTest()">
                        <i class="fas fa-clock"></i>
                        Test Speed
                    </button>
                </div>
                
                <div class="quick-practice-card accuracy-test">
                    <div class="card-icon">
                        <i class="fas fa-target"></i>
                    </div>
                    <div class="card-content">
                        <h3>Accuracy Challenge</h3>
                        <p>Focus on precision over speed</p>
                        <div class="card-meta">
                            <span class="difficulty beginner">Beginner</span>
                            <span class="length">Short Text</span>
                        </div>
                    </div>
                    <button class="btn btn-success quick-btn" onclick="startAccuracyTest()">
                        <i class="fas fa-bullseye"></i>
                        Challenge
                    </button>
                </div>
            </div>
        </div>

        <!-- Practice Texts Grid -->
        <div class="practice-texts-section">
            <div class="section-header">
                <h2 class="section-title">
                    <i class="fas fa-file-text"></i>
                    Practice Texts
                </h2>
                <div class="section-actions">
                    <div class="view-toggle">
                        <button class="view-btn active" data-view="grid">
                            <i class="fas fa-th"></i>
                        </button>
                        <button class="view-btn" data-view="list">
                            <i class="fas fa-list"></i>
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="texts-grid" id="texts-container">
                @foreach($texts as $text)
                <div class="text-card" 
                     data-category="{{ $text->category_id }}" 
                     data-difficulty="{{ $text->difficulty_level }}"
                     data-length="{{ $text->word_count }}"
                     data-popularity="{{ rand(1, 100) }}">
                    
                    <div class="card-header">
                        <div class="text-category">
                            <i class="fas fa-{{ $text->category->name == 'Programming' ? 'code' : ($text->category->name == 'Literature' ? 'book' : ($text->category->name == 'Science' ? 'flask' : ($text->category->name == 'Business' ? 'briefcase' : 'file-text'))) }}"></i>
                            {{ $text->category->name }}
                        </div>
                        <div class="difficulty-badge difficulty-{{ $text->difficulty_level }}">
                            {{ ucfirst($text->difficulty_level) }}
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <h3 class="text-title">{{ $text->title }}</h3>
                        <p class="text-preview">{{ Str::limit($text->content, 120) }}</p>
                        
                        <div class="text-stats">
                            <div class="stat-item">
                                <i class="fas fa-font"></i>
                                <span>{{ $text->word_count }} words</span>
                            </div>
                            <div class="stat-item">
                                <i class="fas fa-clock"></i>
                                <span>~{{ ceil($text->word_count / 40) }} min</span>
                            </div>
                            <div class="stat-item">
                                <i class="fas fa-users"></i>
                                <span>{{ rand(50, 500) }} attempts</span>
                            </div>
                        </div>
                        
                        <div class="difficulty-progress">
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: {{ ($text->difficulty_level == 'beginner') ? '33%' : (($text->difficulty_level == 'intermediate') ? '66%' : '100%') }}"></div>
                            </div>
                            <span class="progress-label">{{ ucfirst($text->difficulty_level) }}</span>
                        </div>
                    </div>
                    
                    <div class="card-footer">
                        <div class="card-actions">
                            <button class="btn btn-outline-primary preview-btn" onclick="previewText({{ $text->id }})">
                                <i class="fas fa-eye"></i>
                                Preview
                            </button>
                            <a href="{{ route('practice.show', $text) }}" class="btn btn-primary practice-btn">
                                <i class="fas fa-keyboard"></i>
                                Practice
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            <div class="pagination-wrapper">
                {{ $texts->links() }}
            </div>
            
            <!-- Empty State -->
            <div class="empty-state" id="empty-state" style="display: none;">
                <div class="empty-icon">
                    <i class="fas fa-search"></i>
                </div>
                <h3>No texts found</h3>
                <p>Try adjusting your filters to find more practice texts</p>
                <button class="btn btn-primary" onclick="clearFilters()">
                    <i class="fas fa-refresh"></i>
                    Clear Filters
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Text Preview Modal -->
<div class="modal fade" id="textPreviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Text Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="preview-content">
                    <div class="preview-meta">
                        <span class="preview-category"></span>
                        <span class="preview-difficulty"></span>
                        <span class="preview-length"></span>
                    </div>
                    <h4 class="preview-title"></h4>
                    <div class="preview-text"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <a href="#" class="btn btn-primary" id="practice-from-preview">
                    <i class="fas fa-keyboard"></i>
                    Start Practice
                </a>
            </div>
        </div>
    </div>
</div>

<style>
.practice-container {
    background: var(--bg-primary);
    min-height: calc(100vh - 120px);
    padding: 2rem 0;
}

/* Practice Header */
.practice-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 3rem;
    padding: 2rem;
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: var(--border-radius-lg);
}

.page-title {
    font-family: var(--font-display);
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.page-title i {
    background: var(--champion-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.page-subtitle {
    color: var(--text-secondary);
    font-size: 1.1rem;
    margin: 0;
}

.header-stats {
    display: flex;
    gap: 1.5rem;
}

.stat-card {
    background: var(--bg-secondary);
    border: 1px solid var(--border-light);
    border-radius: var(--border-radius);
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    min-width: 140px;
}

.stat-icon {
    width: 48px;
    height: 48px;
    background: var(--champion-gradient);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2rem;
}

.stat-number {
    font-family: var(--font-display);
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text-primary);
}

.stat-label {
    color: var(--text-secondary);
    font-size: 0.9rem;
}

/* Practice Filters */
.practice-filters {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr;
    gap: 2rem;
    margin-bottom: 3rem;
    padding: 2rem;
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: var(--border-radius-lg);
}

.filter-title {
    font-size: 1rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.filter-title i {
    color: var(--accent-primary);
}

/* Category Filters */
.category-filters {
    display: flex;
    flex-wrap: wrap;
    gap: 0.75rem;
}

.category-filter {
    background: var(--bg-secondary);
    border: 1px solid var(--border-light);
    border-radius: var(--border-radius);
    padding: 0.75rem 1rem;
    color: var(--text-secondary);
    font-size: 0.9rem;
    font-weight: 500;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    white-space: nowrap;
}

.category-filter:hover,
.category-filter.active {
    background: var(--accent-primary);
    color: white;
    border-color: var(--accent-primary);
    transform: translateY(-2px);
}

.category-filter .count {
    background: rgba(255,255,255,0.2);
    padding: 0.2rem 0.5rem;
    border-radius: 12px;
    font-size: 0.8rem;
    font-weight: 600;
}

.category-filter:not(.active) .count {
    background: var(--border-light);
    color: var(--text-muted);
}

/* Difficulty Filters */
.difficulty-filters {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.difficulty-filter {
    background: var(--bg-secondary);
    border: 1px solid var(--border-light);
    border-radius: var(--border-radius);
    padding: 0.75rem 1rem;
    color: var(--text-secondary);
    font-size: 0.9rem;
    font-weight: 500;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.difficulty-filter:hover,
.difficulty-filter.active {
    transform: translateY(-2px);
}

.difficulty-filter.beginner:hover,
.difficulty-filter.beginner.active {
    background: var(--accent-success);
    color: white;
    border-color: var(--accent-success);
}

.difficulty-filter.intermediate:hover,
.difficulty-filter.intermediate.active {
    background: var(--accent-warning);
    color: white;
    border-color: var(--accent-warning);
}

.difficulty-filter.advanced:hover,
.difficulty-filter.advanced.active {
    background: var(--accent-danger);
    color: white;
    border-color: var(--accent-danger);
}

/* Sort Filters */
.sort-select {
    width: 100%;
    background: var(--bg-secondary);
    border: 1px solid var(--border-light);
    border-radius: var(--border-radius);
    padding: 0.75rem 1rem;
    color: var(--text-primary);
    font-size: 0.9rem;
}

.sort-select:focus {
    outline: none;
    border-color: var(--accent-primary);
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

/* Quick Practice Section */
.quick-practice-section {
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
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--text-primary);
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.section-title i {
    color: var(--accent-primary);
}

.section-subtitle {
    color: var(--text-secondary);
    margin: 0.5rem 0 0 0;
}

.quick-practice-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
}

.quick-practice-card {
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: var(--border-radius-lg);
    padding: 2rem;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.quick-practice-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 40px rgba(0,0,0,0.1);
}

.quick-practice-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
}

.random-text::before {
    background: var(--champion-gradient);
}

.speed-test::before {
    background: linear-gradient(135deg, var(--accent-warning), var(--accent-danger));
}

.accuracy-test::before {
    background: linear-gradient(135deg, var(--accent-success), var(--accent-info));
}

.card-icon {
    width: 64px;
    height: 64px;
    background: var(--bg-secondary);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: var(--accent-primary);
    margin-bottom: 1.5rem;
}

.card-content h3 {
    font-family: var(--font-display);
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 0.75rem;
}

.card-content p {
    color: var(--text-secondary);
    line-height: 1.6;
    margin-bottom: 1.5rem;
}

.card-meta {
    display: flex;
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.card-meta span {
    padding: 0.25rem 0.75rem;
    border-radius: var(--border-radius);
    font-size: 0.8rem;
    font-weight: 500;
}

.difficulty {
    color: white;
}

.difficulty.beginner {
    background: var(--accent-success);
}

.difficulty.intermediate {
    background: var(--accent-warning);
}

.difficulty.advanced {
    background: var(--accent-danger);
}

.difficulty.mixed {
    background: var(--medal-gradient);
}

.length {
    background: var(--bg-secondary);
    color: var(--text-primary);
}

.quick-btn {
    width: 100%;
    padding: 0.75rem;
    font-weight: 600;
    border-radius: var(--border-radius);
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

/* Practice Texts Section */
.practice-texts-section {
    margin-bottom: 3rem;
}

.section-actions {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.view-toggle {
    display: flex;
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: var(--border-radius);
    overflow: hidden;
}

.view-btn {
    background: none;
    border: none;
    padding: 0.5rem 1rem;
    color: var(--text-secondary);
    transition: all 0.3s ease;
}

.view-btn.active,
.view-btn:hover {
    background: var(--accent-primary);
    color: white;
}

/* Text Cards */
.texts-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 2rem;
}

.text-card {
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: var(--border-radius-lg);
    overflow: hidden;
    transition: all 0.3s ease;
}

.text-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 40px rgba(0,0,0,0.1);
    border-color: var(--accent-primary);
}

.card-header {
    padding: 1.5rem;
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.05), rgba(16, 185, 129, 0.05));
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.text-category {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--text-secondary);
    font-size: 0.9rem;
    font-weight: 500;
}

.text-category i {
    color: var(--accent-primary);
}

.difficulty-badge {
    padding: 0.25rem 0.75rem;
    border-radius: var(--border-radius);
    font-size: 0.8rem;
    font-weight: 600;
    color: white;
}

.difficulty-badge.difficulty-beginner {
    background: var(--accent-success);
}

.difficulty-badge.difficulty-intermediate {
    background: var(--accent-warning);
}

.difficulty-badge.difficulty-advanced {
    background: var(--accent-danger);
}

.card-body {
    padding: 1.5rem;
}

.text-title {
    font-family: var(--font-display);
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 0.75rem;
}

.text-preview {
    color: var(--text-secondary);
    line-height: 1.6;
    margin-bottom: 1.5rem;
    font-size: 0.9rem;
}

.text-stats {
    display: flex;
    gap: 1rem;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--text-secondary);
    font-size: 0.8rem;
}

.stat-item i {
    color: var(--accent-primary);
}

.difficulty-progress {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.progress-bar {
    flex: 1;
    height: 6px;
    background: var(--border-light);
    border-radius: 3px;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    background: var(--medal-gradient);
    transition: width 0.3s ease;
}

.progress-label {
    font-size: 0.8rem;
    color: var(--text-secondary);
    font-weight: 500;
}

.card-footer {
    padding: 1.5rem;
    background: var(--bg-secondary);
}

.card-actions {
    display: flex;
    gap: 1rem;
}

.preview-btn,
.practice-btn {
    flex: 1;
    padding: 0.75rem;
    font-weight: 500;
    border-radius: var(--border-radius);
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    font-size: 0.9rem;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: var(--border-radius-lg);
}

.empty-icon {
    font-size: 4rem;
    color: var(--text-muted);
    margin-bottom: 1.5rem;
}

.empty-state h3 {
    font-family: var(--font-display);
    font-size: 1.5rem;
    color: var(--text-primary);
    margin-bottom: 1rem;
}

.empty-state p {
    color: var(--text-secondary);
    font-size: 1.1rem;
    margin-bottom: 2rem;
}

/* Modal Styles */
.modal-content {
    border: none;
    border-radius: var(--border-radius-lg);
}

.modal-header {
    background: var(--champion-gradient);
    color: white;
    border-radius: var(--border-radius-lg) var(--border-radius-lg) 0 0;
}

.preview-content {
    text-align: center;
}

.preview-meta {
    display: flex;
    justify-content: center;
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.preview-meta span {
    padding: 0.5rem 1rem;
    border-radius: var(--border-radius);
    font-size: 0.9rem;
    font-weight: 500;
}

.preview-category {
    background: var(--bg-secondary);
    color: var(--text-primary);
}

.preview-difficulty {
    color: white;
}

.preview-length {
    background: var(--border-light);
    color: var(--text-secondary);
}

.preview-title {
    font-family: var(--font-display);
    color: var(--text-primary);
    margin-bottom: 1.5rem;
}

.preview-text {
    background: var(--bg-secondary);
    padding: 2rem;
    border-radius: var(--border-radius);
    font-family: var(--font-mono);
    line-height: 1.8;
    text-align: left;
    max-height: 300px;
    overflow-y: auto;
}

/* Responsive Design */
@media (max-width: 1024px) {
    .practice-filters {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    
    .practice-header {
        flex-direction: column;
        gap: 1.5rem;
        text-align: center;
    }
    
    .header-stats {
        justify-content: center;
    }
}

@media (max-width: 768px) {
    .practice-container {
        padding: 1rem 0;
    }
    
    .practice-header,
    .practice-filters {
        padding: 1.5rem;
    }
    
    .quick-practice-grid {
        grid-template-columns: 1fr;
    }
    
    .texts-grid {
        grid-template-columns: 1fr;
    }
    
    .category-filters {
        justify-content: center;
    }
    
    .text-stats {
        justify-content: center;
    }
    
    .card-actions {
        flex-direction: column;
    }
}
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filter functionality
    const categoryFilters = document.querySelectorAll('.category-filter');
    const difficultyFilters = document.querySelectorAll('.difficulty-filter');
    const sortSelect = document.getElementById('sort-select');
    const textsContainer = document.getElementById('texts-container');
    const emptyState = document.getElementById('empty-state');
    const textCards = document.querySelectorAll('.text-card');
    
    let currentFilters = {
        category: 'all',
        difficulty: 'all',
        sort: 'newest'
    };
    
    // Category filter handlers
    categoryFilters.forEach(filter => {
        filter.addEventListener('click', function() {
            categoryFilters.forEach(f => f.classList.remove('active'));
            this.classList.add('active');
            currentFilters.category = this.dataset.category;
            applyFilters();
        });
    });
    
    // Difficulty filter handlers
    difficultyFilters.forEach(filter => {
        filter.addEventListener('click', function() {
            difficultyFilters.forEach(f => f.classList.remove('active'));
            this.classList.add('active');
            currentFilters.difficulty = this.dataset.difficulty;
            applyFilters();
        });
    });
    
    // Sort handler
    sortSelect.addEventListener('change', function() {
        currentFilters.sort = this.value;
        applyFilters();
    });
    
    function applyFilters() {
        let visibleCards = [];
        
        textCards.forEach(card => {
            let visible = true;
            
            // Category filter
            if (currentFilters.category !== 'all') {
                if (card.dataset.category !== currentFilters.category) {
                    visible = false;
                }
            }
            
            // Difficulty filter
            if (currentFilters.difficulty !== 'all') {
                if (card.dataset.difficulty !== currentFilters.difficulty) {
                    visible = false;
                }
            }
            
            if (visible) {
                visibleCards.push(card);
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
        
        // Sort visible cards
        sortCards(visibleCards);
        
        // Show/hide empty state
        if (visibleCards.length === 0) {
            textsContainer.style.display = 'none';
            emptyState.style.display = 'block';
        } else {
            textsContainer.style.display = 'grid';
            emptyState.style.display = 'none';
        }
    }
    
    function sortCards(cards) {
        const container = cards[0]?.parentNode;
        if (!container) return;
        
        cards.sort((a, b) => {
            switch (currentFilters.sort) {
                case 'popular':
                    return parseInt(b.dataset.popularity) - parseInt(a.dataset.popularity);
                case 'difficulty':
                    const difficultyOrder = { 'beginner': 1, 'intermediate': 2, 'advanced': 3 };
                    return difficultyOrder[a.dataset.difficulty] - difficultyOrder[b.dataset.difficulty];
                case 'length':
                    return parseInt(a.dataset.length) - parseInt(b.dataset.length);
                default: // newest
                    return 0; // Keep original order
            }
        });
        
        cards.forEach(card => container.appendChild(card));
    }
    
    // View toggle
    const viewBtns = document.querySelectorAll('.view-btn');
    viewBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            viewBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            const view = this.dataset.view;
            if (view === 'list') {
                textsContainer.classList.add('list-view');
            } else {
                textsContainer.classList.remove('list-view');
            }
        });
    });
});

// Quick practice functions
function startRandomPractice() {
    // In real app, this would make an AJAX call to get a random text
    window.location.href = '{{ route("practice.show", "random") }}';
}

function startSpeedTest() {
    // In real app, this would redirect to a special speed test mode
    window.location.href = '{{ route("practice.show", "speed-test") }}';
}

function startAccuracyTest() {
    // In real app, this would redirect to a special accuracy test mode
    window.location.href = '{{ route("practice.show", "accuracy-test") }}';
}

// Text preview function
function previewText(textId) {
    // In real app, this would make an AJAX call to get text details
    fetch(`/practice/preview/${textId}`)
        .then(response => response.json())
        .then(data => {
            document.querySelector('.preview-category').textContent = data.category;
            document.querySelector('.preview-difficulty').textContent = data.difficulty;
            document.querySelector('.preview-difficulty').className = `preview-difficulty difficulty-${data.difficulty}`;
            document.querySelector('.preview-length').textContent = `${data.word_count} words`;
            document.querySelector('.preview-title').textContent = data.title;
            document.querySelector('.preview-text').textContent = data.content;
            document.getElementById('practice-from-preview').href = `/practice/${textId}`;
            
            const modal = new bootstrap.Modal(document.getElementById('textPreviewModal'));
            modal.show();
        })
        .catch(error => {
            console.error('Error fetching text preview:', error);
        });
}

// Clear filters function
function clearFilters() {
    document.querySelector('.category-filter[data-category="all"]').click();
    document.querySelector('.difficulty-filter[data-difficulty="all"]').click();
    document.getElementById('sort-select').value = 'newest';
    document.getElementById('sort-select').dispatchEvent(new Event('change'));
}
</script>
@endsection