@extends('layouts.app')

@section('content')
<div class="lessons-container">
    <div class="container">
        <!-- Lessons Header -->
        <div class="lessons-header">
            <div class="header-content">
                <h1 class="page-title">
                    <i class="fas fa-graduation-cap"></i>
                    Typing Lessons
                </h1>
                <p class="page-subtitle">
                    Master the art of touch typing with our structured lessons from beginner to advanced
                </p>
            </div>
            <div class="header-progress">
                @if(Auth::check())
                <div class="overall-progress">
                    <div class="progress-info">
                        <span class="progress-label">Overall Progress</span>
                        <span class="progress-percentage">{{ $overallProgress ?? 0 }}%</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: {{ $overallProgress ?? 0 }}%"></div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Learning Path -->
        <div class="learning-path-section">
            <div class="section-header">
                <h2 class="section-title">
                    <i class="fas fa-route"></i>
                    Your Learning Path
                </h2>
                <p class="section-subtitle">Follow our structured curriculum to become a typing master</p>
            </div>
            
            <div class="learning-path">
                <div class="path-step {{ ($currentLevel ?? 'beginner') == 'beginner' ? 'active' : (($completedLevels ?? []) ? 'completed' : '') }}">
                    <div class="step-icon">
                        <i class="fas fa-seedling"></i>
                    </div>
                    <div class="step-content">
                        <h3>Beginner</h3>
                        <p>Learn basic finger positioning and home row</p>
                        <div class="step-stats">
                            <span>{{ $beginnerLessons ?? 8 }} lessons</span>
                            <span>{{ $beginnerProgress ?? 0 }}% complete</span>
                        </div>
                    </div>
                </div>
                
                <div class="path-connector">
                    <div class="connector-line {{ in_array('beginner', $completedLevels ?? []) ? 'completed' : '' }}"></div>
                </div>
                
                <div class="path-step {{ ($currentLevel ?? '') == 'intermediate' ? 'active' : (in_array('intermediate', $completedLevels ?? []) ? 'completed' : '') }}">
                    <div class="step-icon">
                        <i class="fas fa-mountain"></i>
                    </div>
                    <div class="step-content">
                        <h3>Intermediate</h3>
                        <p>Expand to all letters and numbers</p>
                        <div class="step-stats">
                            <span>{{ $intermediateLessons ?? 12 }} lessons</span>
                            <span>{{ $intermediateProgress ?? 0 }}% complete</span>
                        </div>
                    </div>
                </div>
                
                <div class="path-connector">
                    <div class="connector-line {{ in_array('intermediate', $completedLevels ?? []) ? 'completed' : '' }}"></div>
                </div>
                
                <div class="path-step {{ ($currentLevel ?? '') == 'advanced' ? 'active' : (in_array('advanced', $completedLevels ?? []) ? 'completed' : '') }}">
                    <div class="step-icon">
                        <i class="fas fa-fire"></i>
                    </div>
                    <div class="step-content">
                        <h3>Advanced</h3>
                        <p>Master special characters and speed</p>
                        <div class="step-stats">
                            <span>{{ $advancedLessons ?? 10 }} lessons</span>
                            <span>{{ $advancedProgress ?? 0 }}% complete</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lesson Categories -->
        <div class="lesson-categories">
            <!-- Finger Position Lessons -->
            <div class="category-section">
                <div class="category-header">
                    <div class="category-title">
                        <i class="fas fa-hand-paper"></i>
                        <h3>Finger Positioning</h3>
                        <span class="lesson-count">8 lessons</span>
                    </div>
                    <div class="category-description">
                        Learn proper finger placement and home row fundamentals
                    </div>
                </div>
                
                <div class="lessons-grid">
                    @foreach($lessons->where('category', 'finger_position')->take(4) as $lesson)
                    <div class="lesson-card {{ $lesson->isCompletedBy(Auth::id()) ? 'completed' : ($lesson->isUnlocked(Auth::id()) ? 'unlocked' : 'locked') }}">
                        <div class="lesson-number">{{ $lesson->order_number }}</div>
                        <div class="lesson-content">
                            <h4 class="lesson-title">{{ $lesson->title }}</h4>
                            <p class="lesson-description">{{ $lesson->description }}</p>
                            <div class="lesson-meta">
                                <span class="lesson-duration">
                                    <i class="fas fa-clock"></i>
                                    {{ $lesson->estimated_duration }} min
                                </span>
                                <span class="lesson-difficulty difficulty-{{ $lesson->difficulty_level }}">
                                    <i class="fas fa-signal"></i>
                                    {{ ucfirst($lesson->difficulty_level) }}
                                </span>
                            </div>
                        </div>
                        <div class="lesson-status">
                            @if($lesson->isCompletedBy(Auth::id()))
                                <div class="status-completed">
                                    <i class="fas fa-check-circle"></i>
                                    <span>Completed</span>
                                </div>
                                <div class="lesson-score">
                                    Score: {{ $lesson->getUserScore(Auth::id()) }}%
                                </div>
                            @elseif($lesson->isUnlocked(Auth::id()))
                                <a href="{{ route('lessons.show', $lesson) }}" class="btn btn-primary btn-start">
                                    <i class="fas fa-play"></i>
                                    Start Lesson
                                </a>
                            @else
                                <div class="status-locked">
                                    <i class="fas fa-lock"></i>
                                    <span>Locked</span>
                                </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
                
                @if($lessons->where('category', 'finger_position')->count() > 4)
                <div class="show-more">
                    <button class="btn btn-outline-primary" onclick="toggleCategory('finger_position')">
                        <span class="show-text">Show {{ $lessons->where('category', 'finger_position')->count() - 4 }} More</span>
                        <span class="hide-text" style="display: none;">Show Less</span>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                </div>
                @endif
            </div>

            <!-- Beginner Lessons -->
            <div class="category-section">
                <div class="category-header">
                    <div class="category-title">
                        <i class="fas fa-seedling"></i>
                        <h3>Beginner Lessons</h3>
                        <span class="lesson-count">{{ $lessons->where('difficulty_level', 'beginner')->count() }} lessons</span>
                    </div>
                    <div class="category-description">
                        Master the basics with simple words and common letter combinations
                    </div>
                </div>
                
                <div class="lessons-grid">
                    @foreach($lessons->where('difficulty_level', 'beginner')->take(4) as $lesson)
                    <div class="lesson-card {{ $lesson->isCompletedBy(Auth::id()) ? 'completed' : ($lesson->isUnlocked(Auth::id()) ? 'unlocked' : 'locked') }}">
                        <div class="lesson-number">{{ $lesson->order_number }}</div>
                        <div class="lesson-content">
                            <h4 class="lesson-title">{{ $lesson->title }}</h4>
                            <p class="lesson-description">{{ $lesson->description }}</p>
                            <div class="lesson-preview">
                                <span class="preview-text">{{ Str::limit($lesson->content, 40) }}</span>
                            </div>
                            <div class="lesson-meta">
                                <span class="lesson-duration">
                                    <i class="fas fa-clock"></i>
                                    {{ $lesson->estimated_duration }} min
                                </span>
                                <span class="lesson-target">
                                    <i class="fas fa-target"></i>
                                    {{ $lesson->target_wpm }} WPM
                                </span>
                            </div>
                        </div>
                        <div class="lesson-status">
                            @if($lesson->isCompletedBy(Auth::id()))
                                <div class="status-completed">
                                    <i class="fas fa-check-circle"></i>
                                    <span>Completed</span>
                                </div>
                                <div class="lesson-score">
                                    Score: {{ $lesson->getUserScore(Auth::id()) }}%
                                </div>
                                <a href="{{ route('lessons.show', $lesson) }}" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-redo"></i>
                                    Practice Again
                                </a>
                            @elseif($lesson->isUnlocked(Auth::id()))
                                <a href="{{ route('lessons.show', $lesson) }}" class="btn btn-primary btn-start">
                                    <i class="fas fa-play"></i>
                                    Start Lesson
                                </a>
                            @else
                                <div class="status-locked">
                                    <i class="fas fa-lock"></i>
                                    <span>Complete previous lessons to unlock</span>
                                </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Intermediate Lessons -->
            <div class="category-section">
                <div class="category-header">
                    <div class="category-title">
                        <i class="fas fa-mountain"></i>
                        <h3>Intermediate Lessons</h3>
                        <span class="lesson-count">{{ $lessons->where('difficulty_level', 'intermediate')->count() }} lessons</span>
                    </div>
                    <div class="category-description">
                        Increase speed and accuracy with numbers and punctuation
                    </div>
                </div>
                
                <div class="lessons-grid">
                    @foreach($lessons->where('difficulty_level', 'intermediate')->take(4) as $lesson)
                    <div class="lesson-card {{ $lesson->isCompletedBy(Auth::id()) ? 'completed' : ($lesson->isUnlocked(Auth::id()) ? 'unlocked' : 'locked') }}">
                        <div class="lesson-number">{{ $lesson->order_number }}</div>
                        <div class="lesson-content">
                            <h4 class="lesson-title">{{ $lesson->title }}</h4>
                            <p class="lesson-description">{{ $lesson->description }}</p>
                            <div class="lesson-preview">
                                <span class="preview-text">{{ Str::limit($lesson->content, 40) }}</span>
                            </div>
                            <div class="lesson-meta">
                                <span class="lesson-duration">
                                    <i class="fas fa-clock"></i>
                                    {{ $lesson->estimated_duration }} min
                                </span>
                                <span class="lesson-target">
                                    <i class="fas fa-target"></i>
                                    {{ $lesson->target_wpm }} WPM
                                </span>
                            </div>
                        </div>
                        <div class="lesson-status">
                            @if($lesson->isCompletedBy(Auth::id()))
                                <div class="status-completed">
                                    <i class="fas fa-check-circle"></i>
                                    <span>Completed</span>
                                </div>
                                <div class="lesson-score">
                                    Score: {{ $lesson->getUserScore(Auth::id()) }}%
                                </div>
                                <a href="{{ route('lessons.show', $lesson) }}" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-redo"></i>
                                    Practice Again
                                </a>
                            @elseif($lesson->isUnlocked(Auth::id()))
                                <a href="{{ route('lessons.show', $lesson) }}" class="btn btn-warning btn-start">
                                    <i class="fas fa-play"></i>
                                    Start Lesson
                                </a>
                            @else
                                <div class="status-locked">
                                    <i class="fas fa-lock"></i>
                                    <span>Complete previous lessons to unlock</span>
                                </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Advanced Lessons -->
            <div class="category-section">
                <div class="category-header">
                    <div class="category-title">
                        <i class="fas fa-fire"></i>
                        <h3>Advanced Lessons</h3>
                        <span class="lesson-count">{{ $lessons->where('difficulty_level', 'advanced')->count() }} lessons</span>
                    </div>
                    <div class="category-description">
                        Master complex texts and achieve professional typing speeds
                    </div>
                </div>
                
                <div class="lessons-grid">
                    @foreach($lessons->where('difficulty_level', 'advanced')->take(4) as $lesson)
                    <div class="lesson-card {{ $lesson->isCompletedBy(Auth::id()) ? 'completed' : ($lesson->isUnlocked(Auth::id()) ? 'unlocked' : 'locked') }}">
                        <div class="lesson-number">{{ $lesson->order_number }}</div>
                        <div class="lesson-content">
                            <h4 class="lesson-title">{{ $lesson->title }}</h4>
                            <p class="lesson-description">{{ $lesson->description }}</p>
                            <div class="lesson-preview">
                                <span class="preview-text">{{ Str::limit($lesson->content, 40) }}</span>
                            </div>
                            <div class="lesson-meta">
                                <span class="lesson-duration">
                                    <i class="fas fa-clock"></i>
                                    {{ $lesson->estimated_duration }} min
                                </span>
                                <span class="lesson-target">
                                    <i class="fas fa-target"></i>
                                    {{ $lesson->target_wpm }} WPM
                                </span>
                            </div>
                        </div>
                        <div class="lesson-status">
                            @if($lesson->isCompletedBy(Auth::id()))
                                <div class="status-completed">
                                    <i class="fas fa-check-circle"></i>
                                    <span>Completed</span>
                                </div>
                                <div class="lesson-score">
                                    Score: {{ $lesson->getUserScore(Auth::id()) }}%
                                </div>
                                <a href="{{ route('lessons.show', $lesson) }}" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-redo"></i>
                                    Practice Again
                                </a>
                            @elseif($lesson->isUnlocked(Auth::id()))
                                <a href="{{ route('lessons.show', $lesson) }}" class="btn btn-danger btn-start">
                                    <i class="fas fa-play"></i>
                                    Start Lesson
                                </a>
                            @else
                                <div class="status-locked">
                                    <i class="fas fa-lock"></i>
                                    <span>Complete previous lessons to unlock</span>
                                </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Quick Access -->
        <div class="quick-access-section">
            <div class="section-header">
                <h2 class="section-title">
                    <i class="fas fa-bolt"></i>
                    Quick Access
                </h2>
            </div>
            
            <div class="quick-access-grid">
                <div class="quick-access-card">
                    <div class="card-icon">
                        <i class="fas fa-keyboard"></i>
                    </div>
                    <div class="card-content">
                        <h3>Keyboard Layout Guide</h3>
                        <p>Interactive keyboard layout with finger positioning</p>
                    </div>
                    <button class="btn btn-outline-primary" onclick="showKeyboardGuide()">
                        <i class="fas fa-eye"></i>
                        View Guide
                    </button>
                </div>
                
                <div class="quick-access-card">
                    <div class="card-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="card-content">
                        <h3>Progress Report</h3>
                        <p>Detailed analysis of your learning journey</p>
                    </div>
                    <a href="{{ route('profile.show') }}" class="btn btn-outline-primary">
                        <i class="fas fa-chart-bar"></i>
                        View Report
                    </a>
                </div>
                
                <div class="quick-access-card">
                    <div class="card-icon">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <div class="card-content">
                        <h3>Achievements</h3>
                        <p>Track your milestones and earned badges</p>
                    </div>
                    <a href="{{ route('profile.show') }}#achievements" class="btn btn-outline-primary">
                        <i class="fas fa-medal"></i>
                        View Badges
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Keyboard Guide Modal -->
<div class="modal fade" id="keyboardGuideModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-keyboard"></i>
                    Keyboard Layout Guide
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="keyboard-guide">
                    <div class="finger-colors-legend">
                        <h6>Finger Color Guide:</h6>
                        <div class="color-legend">
                            <div class="legend-item">
                                <div class="color-box left-pinky"></div>
                                <span>Left Pinky</span>
                            </div>
                            <div class="legend-item">
                                <div class="color-box left-ring"></div>
                                <span>Left Ring</span>
                            </div>
                            <div class="legend-item">
                                <div class="color-box left-middle"></div>
                                <span>Left Middle</span>
                            </div>
                            <div class="legend-item">
                                <div class="color-box left-index"></div>
                                <span>Left Index</span>
                            </div>
                            <div class="legend-item">
                                <div class="color-box thumbs"></div>
                                <span>Thumbs</span>
                            </div>
                            <div class="legend-item">
                                <div class="color-box right-index"></div>
                                <span>Right Index</span>
                            </div>
                            <div class="legend-item">
                                <div class="color-box right-middle"></div>
                                <span>Right Middle</span>
                            </div>
                            <div class="legend-item">
                                <div class="color-box right-ring"></div>
                                <span>Right Ring</span>
                            </div>
                            <div class="legend-item">
                                <div class="color-box right-pinky"></div>
                                <span>Right Pinky</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="keyboard-layout">
                        <div class="keyboard-row">
                            <div class="key left-pinky">`</div>
                            <div class="key left-pinky">1</div>
                            <div class="key left-ring">2</div>
                            <div class="key left-middle">3</div>
                            <div class="key left-index">4</div>
                            <div class="key left-index">5</div>
                            <div class="key right-index">6</div>
                            <div class="key right-index">7</div>
                            <div class="key right-middle">8</div>
                            <div class="key right-ring">9</div>
                            <div class="key right-pinky">0</div>
                            <div class="key right-pinky">-</div>
                            <div class="key right-pinky">=</div>
                            <div class="key backspace">Backspace</div>
                        </div>
                        <div class="keyboard-row">
                            <div class="key tab">Tab</div>
                            <div class="key left-pinky">Q</div>
                            <div class="key left-ring">W</div>
                            <div class="key left-middle">E</div>
                            <div class="key left-index">R</div>
                            <div class="key left-index">T</div>
                            <div class="key right-index">Y</div>
                            <div class="key right-index">U</div>
                            <div class="key right-middle">I</div>
                            <div class="key right-ring">O</div>
                            <div class="key right-pinky">P</div>
                            <div class="key right-pinky">[</div>
                            <div class="key right-pinky">]</div>
                            <div class="key right-pinky">\</div>
                        </div>
                        <div class="keyboard-row">
                            <div class="key caps">Caps</div>
                            <div class="key left-pinky home">A</div>
                            <div class="key left-ring home">S</div>
                            <div class="key left-middle home">D</div>
                            <div class="key left-index home">F</div>
                            <div class="key left-index">G</div>
                            <div class="key right-index">H</div>
                            <div class="key right-index home">J</div>
                            <div class="key right-middle home">K</div>
                            <div class="key right-ring home">L</div>
                            <div class="key right-pinky home">;</div>
                            <div class="key right-pinky">'</div>
                            <div class="key enter">Enter</div>
                        </div>
                        <div class="keyboard-row">
                            <div class="key shift">Shift</div>
                            <div class="key left-pinky">Z</div>
                            <div class="key left-ring">X</div>
                            <div class="key left-middle">C</div>
                            <div class="key left-index">V</div>
                            <div class="key left-index">B</div>
                            <div class="key right-index">N</div>
                            <div class="key right-index">M</div>
                            <div class="key right-middle">,</div>
                            <div class="key right-ring">.</div>
                            <div class="key right-pinky">/</div>
                            <div class="key shift">Shift</div>
                        </div>
                        <div class="keyboard-row">
                            <div class="key ctrl">Ctrl</div>
                            <div class="key alt">Alt</div>
                            <div class="key thumbs space">Space</div>
                            <div class="key alt">Alt</div>
                            <div class="key ctrl">Ctrl</div>
                        </div>
                    </div>
                    
                    <div class="home-row-tip">
                        <div class="tip-icon">
                            <i class="fas fa-lightbulb"></i>
                        </div>
                        <div class="tip-content">
                            <h6>Home Row Position</h6>
                            <p>Place your fingers on the highlighted home row keys (ASDF for left hand, JKL; for right hand). Your index fingers should rest on F and J keys which usually have small bumps.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <a href="{{ route('lessons.index') }}" class="btn btn-primary">
                    <i class="fas fa-graduation-cap"></i>
                    Start Learning
                </a>
            </div>
        </div>
    </div>
</div>

<style>
.lessons-container {
    background: var(--bg-primary);
    min-height: calc(100vh - 120px);
    padding: 2rem 0;
}

/* Lessons Header */
.lessons-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: var(--border-radius-lg);
    padding: 2rem;
    margin-bottom: 3rem;
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
    max-width: 600px;
}

.overall-progress {
    text-align: right;
    min-width: 200px;
}

.progress-info {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.5rem;
}

.progress-label {
    color: var(--text-secondary);
    font-size: 0.9rem;
}

.progress-percentage {
    font-family: var(--font-display);
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--accent-primary);
}

.progress-bar {
    width: 100%;
    height: 8px;
    background: var(--border-light);
    border-radius: 4px;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    background: var(--champion-gradient);
    transition: width 0.3s ease;
}

/* Learning Path */
.learning-path-section {
    margin-bottom: 3rem;
}

.section-header {
    text-align: center;
    margin-bottom: 2rem;
}

.section-title {
    font-family: var(--font-display);
    font-size: 1.75rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
}

.section-title i {
    color: var(--accent-primary);
}

.section-subtitle {
    color: var(--text-secondary);
    font-size: 1.1rem;
}

.learning-path {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 2rem;
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: var(--border-radius-lg);
    padding: 3rem 2rem;
    margin: 0 auto;
    max-width: 900px;
}

.path-step {
    text-align: center;
    padding: 1.5rem;
    border-radius: var(--border-radius-lg);
    background: var(--bg-secondary);
    border: 2px solid var(--border-light);
    transition: all 0.3s ease;
    min-width: 200px;
}

.path-step.active {
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(16, 185, 129, 0.1));
    border-color: var(--accent-primary);
}

.path-step.completed {
    background: linear-gradient(135deg, rgba(34, 197, 94, 0.1), rgba(16, 185, 129, 0.1));
    border-color: var(--accent-success);
}

.step-icon {
    width: 60px;
    height: 60px;
    background: var(--border-light);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    font-size: 1.5rem;
    color: var(--text-secondary);
    transition: all 0.3s ease;
}

.path-step.active .step-icon {
    background: var(--accent-primary);
    color: white;
}

.path-step.completed .step-icon {
    background: var(--accent-success);
    color: white;
}

.step-content h3 {
    font-family: var(--font-display);
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 0.5rem;
}

.step-content p {
    color: var(--text-secondary);
    font-size: 0.9rem;
    margin-bottom: 1rem;
}

.step-stats {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
    font-size: 0.8rem;
    color: var(--text-muted);
}

.path-connector {
    display: flex;
    align-items: center;
    height: 60px;
}

.connector-line {
    width: 60px;
    height: 3px;
    background: var(--border-light);
    transition: background 0.3s ease;
}

.connector-line.completed {
    background: var(--accent-success);
}

/* Lesson Categories */
.lesson-categories {
    margin-bottom: 3rem;
}

.category-section {
    margin-bottom: 3rem;
}

.category-header {
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: var(--border-radius-lg) var(--border-radius-lg) 0 0;
    padding: 2rem;
    border-bottom: none;
}

.category-title {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1rem;
}

.category-title i {
    color: var(--accent-primary);
    font-size: 1.5rem;
}

.category-title h3 {
    font-family: var(--font-display);
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--text-primary);
    margin: 0;
}

.lesson-count {
    background: var(--accent-primary);
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: var(--border-radius);
    font-size: 0.8rem;
    font-weight: 600;
}

.category-description {
    color: var(--text-secondary);
    font-size: 1rem;
}

.lessons-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: 0 0 var(--border-radius-lg) var(--border-radius-lg);
    padding: 2rem;
}

/* Lesson Cards */
.lesson-card {
    background: var(--bg-secondary);
    border: 1px solid var(--border-light);
    border-radius: var(--border-radius-lg);
    overflow: hidden;
    transition: all 0.3s ease;
    position: relative;
}

.lesson-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}

.lesson-card.completed {
    border-color: var(--accent-success);
    background: linear-gradient(135deg, rgba(34, 197, 94, 0.05), rgba(16, 185, 129, 0.05));
}

.lesson-card.unlocked {
    border-color: var(--accent-primary);
}

.lesson-card.locked {
    opacity: 0.6;
    background: var(--bg-muted);
}

.lesson-number {
    position: absolute;
    top: 1rem;
    left: 1rem;
    width: 32px;
    height: 32px;
    background: var(--accent-primary);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 0.9rem;
}

.lesson-card.completed .lesson-number {
    background: var(--accent-success);
}

.lesson-card.locked .lesson-number {
    background: var(--text-muted);
}

.lesson-content {
    padding: 3rem 1.5rem 1.5rem;
}

.lesson-title {
    font-family: var(--font-display);
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 0.75rem;
}

.lesson-description {
    color: var(--text-secondary);
    font-size: 0.9rem;
    line-height: 1.5;
    margin-bottom: 1rem;
}

.lesson-preview {
    background: var(--bg-primary);
    border: 1px solid var(--border-light);
    border-radius: var(--border-radius);
    padding: 0.75rem;
    margin-bottom: 1rem;
}

.preview-text {
    font-family: var(--font-mono);
    font-size: 0.8rem;
    color: var(--text-secondary);
    font-style: italic;
}

.lesson-meta {
    display: flex;
    gap: 1rem;
    margin-bottom: 1rem;
    flex-wrap: wrap;
}

.lesson-duration,
.lesson-difficulty,
.lesson-target {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.8rem;
    color: var(--text-secondary);
}

.lesson-difficulty i,
.lesson-duration i,
.lesson-target i {
    color: var(--accent-primary);
}

.difficulty-beginner {
    color: var(--accent-success) !important;
}

.difficulty-intermediate {
    color: var(--accent-warning) !important;
}

.difficulty-advanced {
    color: var(--accent-danger) !important;
}

.lesson-status {
    padding: 1.5rem;
    background: var(--bg-primary);
    text-align: center;
}

.status-completed {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    color: var(--accent-success);
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.status-locked {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    color: var(--text-muted);
    font-size: 0.9rem;
}

.lesson-score {
    font-size: 0.9rem;
    color: var(--text-secondary);
    margin-bottom: 1rem;
}

.btn-start {
    padding: 0.75rem 1.5rem;
    font-weight: 600;
    border-radius: var(--border-radius);
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    text-decoration: none;
}

.show-more {
    text-align: center;
    padding: 1rem;
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-top: none;
    border-radius: 0 0 var(--border-radius-lg) var(--border-radius-lg);
}

/* Quick Access */
.quick-access-section {
    margin-bottom: 3rem;
}

.quick-access-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
}

.quick-access-card {
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: var(--border-radius-lg);
    padding: 2rem;
    text-align: center;
    transition: all 0.3s ease;
}

.quick-access-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 40px rgba(0,0,0,0.1);
    border-color: var(--accent-primary);
}

.quick-access-card .card-icon {
    width: 64px;
    height: 64px;
    background: var(--bg-secondary);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: var(--accent-primary);
    margin: 0 auto 1.5rem;
}

.quick-access-card h3 {
    font-family: var(--font-display);
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 0.75rem;
}

.quick-access-card p {
    color: var(--text-secondary);
    margin-bottom: 1.5rem;
}

/* Keyboard Guide Modal */
.modal-xl {
    max-width: 1200px;
}

.keyboard-guide {
    text-align: center;
}

.finger-colors-legend {
    margin-bottom: 2rem;
}

.finger-colors-legend h6 {
    color: var(--text-primary);
    margin-bottom: 1rem;
}

.color-legend {
    display: flex;
    justify-content: center;
    gap: 1rem;
    flex-wrap: wrap;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.9rem;
    color: var(--text-secondary);
}

.color-box {
    width: 16px;
    height: 16px;
    border-radius: 2px;
}

.color-box.left-pinky { background: #ef4444; }
.color-box.left-ring { background: #f97316; }
.color-box.left-middle { background: #eab308; }
.color-box.left-index { background: #22c55e; }
.color-box.thumbs { background: #6366f1; }
.color-box.right-index { background: #22c55e; }
.color-box.right-middle { background: #eab308; }
.color-box.right-ring { background: #f97316; }
.color-box.right-pinky { background: #ef4444; }

.keyboard-layout {
    background: var(--bg-secondary);
    border: 1px solid var(--border-light);
    border-radius: var(--border-radius);
    padding: 1.5rem;
    margin-bottom: 2rem;
}

.keyboard-row {
    display: flex;
    justify-content: center;
    gap: 4px;
    margin-bottom: 4px;
}

.keyboard-row:last-child {
    margin-bottom: 0;
}

.key {
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: 4px;
    padding: 0.5rem;
    min-width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.9rem;
    font-weight: 500;
    color: var(--text-primary);
    transition: all 0.2s ease;
}

.key.home {
    border: 2px solid var(--accent-primary);
    font-weight: 700;
}

.key.left-pinky { background: rgba(239, 68, 68, 0.1); border-color: #ef4444; }
.key.left-ring { background: rgba(249, 115, 22, 0.1); border-color: #f97316; }
.key.left-middle { background: rgba(234, 179, 8, 0.1); border-color: #eab308; }
.key.left-index { background: rgba(34, 197, 94, 0.1); border-color: #22c55e; }
.key.thumbs { background: rgba(99, 102, 241, 0.1); border-color: #6366f1; }
.key.right-index { background: rgba(34, 197, 94, 0.1); border-color: #22c55e; }
.key.right-middle { background: rgba(234, 179, 8, 0.1); border-color: #eab308; }
.key.right-ring { background: rgba(249, 115, 22, 0.1); border-color: #f97316; }
.key.right-pinky { background: rgba(239, 68, 68, 0.1); border-color: #ef4444; }

.key.backspace,
.key.tab,
.key.caps,
.key.enter,
.key.shift,
.key.ctrl,
.key.alt {
    min-width: 60px;
    font-size: 0.8rem;
}

.key.space {
    min-width: 200px;
}

.home-row-tip {
    display: flex;
    align-items: center;
    gap: 1rem;
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(16, 185, 129, 0.1));
    border: 1px solid var(--accent-primary);
    border-radius: var(--border-radius);
    padding: 1.5rem;
    text-align: left;
}

.tip-icon {
    width: 48px;
    height: 48px;
    background: var(--accent-primary);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2rem;
    flex-shrink: 0;
}

.tip-content h6 {
    color: var(--text-primary);
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.tip-content p {
    color: var(--text-secondary);
    margin: 0;
    line-height: 1.6;
}

/* Responsive Design */
@media (max-width: 1024px) {
    .lessons-header {
        flex-direction: column;
        gap: 2rem;
        text-align: center;
    }
    
    .learning-path {
        flex-direction: column;
        gap: 1rem;
    }
    
    .path-connector {
        height: 30px;
        transform: rotate(90deg);
    }
    
    .connector-line {
        width: 3px;
        height: 30px;
    }
}

@media (max-width: 768px) {
    .lessons-container {
        padding: 1rem 0;
    }
    
    .lessons-header,
    .category-header {
        padding: 1.5rem;
    }
    
    .lessons-grid {
        grid-template-columns: 1fr;
        padding: 1.5rem;
    }
    
    .path-step {
        min-width: auto;
        padding: 1rem;
    }
    
    .keyboard-layout {
        transform: scale(0.8);
        margin: 0 -10%;
    }
    
    .color-legend {
        justify-content: flex-start;
    }
    
    .home-row-tip {
        flex-direction: column;
        text-align: center;
    }
}
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Show more/less functionality
    window.toggleCategory = function(category) {
        // This would typically show/hide additional lessons in the category
        console.log('Toggle category:', category);
    };
    
    // Show keyboard guide modal
    window.showKeyboardGuide = function() {
        const modal = new bootstrap.Modal(document.getElementById('keyboardGuideModal'));
        modal.show();
    };
    
    // Add hover effects to lesson cards
    const lessonCards = document.querySelectorAll('.lesson-card');
    lessonCards.forEach(card => {
        if (!card.classList.contains('locked')) {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-4px)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(-2px)';
            });
        }
    });
    
    // Progress animation on scroll
    const observerOptions = {
        threshold: 0.5,
        rootMargin: '0px 0px -100px 0px'
    };
    
    const progressObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const progressBars = entry.target.querySelectorAll('.progress-fill');
                progressBars.forEach(bar => {
                    const width = bar.style.width;
                    bar.style.width = '0%';
                    setTimeout(() => {
                        bar.style.width = width;
                    }, 100);
                });
            }
        });
    }, observerOptions);
    
    document.querySelectorAll('.learning-path, .overall-progress').forEach(el => {
        progressObserver.observe(el);
    });
});
</script>
@endsection