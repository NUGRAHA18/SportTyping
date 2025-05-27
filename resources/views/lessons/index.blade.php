@extends('layouts.app')

@section('content')
<div class="lessons-container">
    <div class="container">
        <!-- Lessons Header -->
        <div class="lessons-header">
            <div class="header-content">
                <h1>Typing Lessons</h1>
                <p>Master 10-finger typing with structured lessons designed by professionals</p>
            </div>
            <div class="progress-overview">
                <div class="overall-progress">
                    <div class="progress-circle" data-progress="{{ round($completionPercentage) }}">
                        <svg class="progress-ring" width="80" height="80">
                            <circle class="progress-ring__circle" stroke="var(--accent-pink)" stroke-width="4" fill="transparent" r="36" cx="40" cy="40"/>
                            <circle class="progress-ring_circle progress-ring_circle--fill" stroke="var(--accent-pink)" stroke-width="4" fill="transparent" r="36" cx="40" cy="40"/>
                        </svg>
                        <div class="progress-text">
                            <span class="progress-percentage">{{ round($completionPercentage) }}%</span>
                            <span class="progress-label">Complete</span>
                        </div>
                    </div>
                </div>
                <div class="progress-stats">
                    <div class="stat-item">
                        <span class="stat-number">{{ $completedCount }}</span>
                        <span class="stat-label">Completed</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">{{ $totalCount - $completedCount }}</span>
                        <span class="stat-label">Remaining</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Learning Path -->
        <div class="learning-path-section">
            <div class="section-header">
                <h2><i class="fas fa-route"></i> Your Learning Journey</h2>
                <div class="path-info">
                    <span class="journey-indicator">
                        <i class="fas fa-map-marked-alt"></i>
                        Structured Path to Mastery
                    </span>
                </div>
            </div>

            <div class="learning-path">
                @foreach($lessons as $index => $lesson)
                    @php
                        $progress = $lesson->progress->first();
                        $isCompleted = $progress && $progress->completion_status === 'completed';
                        $isInProgress = $progress && $progress->completion_status === 'in_progress';
                        $isLocked = $index > 0 && !$lessons[$index - 1]->progress->first();
                        $isAvailable = !$isLocked;
                    @endphp

                    <div class="lesson-node {{ $isCompleted ? 'completed' : ($isInProgress ? 'in-progress' : ($isAvailable ? 'available' : 'locked')) }}"
                         data-lesson="{{ $lesson->order_number }}">
                        
                        <!-- Connection Line -->
                        @if($index < count($lessons) - 1)
                            <div class="connection-line {{ $isCompleted ? 'completed-line' : '' }}"></div>
                        @endif

                        <!-- Lesson Card -->
                        <div class="lesson-card">
                            <div class="lesson-status">
                                @if($isCompleted)
                                    <div class="status-icon completed">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                @elseif($isInProgress)
                                    <div class="status-icon in-progress">
                                        <i class="fas fa-play-circle"></i>
                                    </div>
                                @elseif($isAvailable)
                                    <div class="status-icon available">
                                        <i class="fas fa-circle"></i>
                                    </div>
                                @else
                                    <div class="status-icon locked">
                                        <i class="fas fa-lock"></i>
                                    </div>
                                @endif
                                <span class="lesson-number">{{ $lesson->order_number }}</span>
                            </div>

                            <div class="lesson-content">
                                <div class="lesson-header">
                                    <h3>{{ $lesson->title }}</h3>
                                    <div class="lesson-badges">
                                        <span class="difficulty-badge {{ $lesson->difficulty_level }}">
                                            @if($lesson->difficulty_level == 'beginner')
                                                <i class="fas fa-seedling"></i>
                                            @elseif($lesson->difficulty_level == 'intermediate')
                                                <i class="fas fa-chart-line"></i>
                                            @elseif($lesson->difficulty_level == 'advanced')
                                                <i class="fas fa-fire"></i>
                                            @else
                                                <i class="fas fa-star"></i>
                                            @endif
                                            {{ ucfirst($lesson->difficulty_level) }}
                                        </span>
                                    </div>
                                </div>

                                <p class="lesson-description">
                                    {{ $lesson->description ?? 'Master essential typing techniques and improve your speed and accuracy.' }}
                                </p>

                                <div class="lesson-stats">
                                    <div class="stat-item">
                                        <i class="fas fa-clock"></i>
                                        <span>{{ $lesson->estimated_completion_time ?? 10 }} min</span>
                                    </div>
                                    <div class="stat-item">
                                        <i class="fas fa-star"></i>
                                        <span>{{ $lesson->experience_reward }} XP</span>
                                    </div>
                                    @if($progress && $progress->highest_speed > 0)
                                        <div class="stat-item">
                                            <i class="fas fa-tachometer-alt"></i>
                                            <span>Best: {{ number_format($progress->highest_speed, 1) }} WPM</span>
                                        </div>
                                    @endif
                                </div>

                                @if($progress && $isCompleted)
                                    <div class="completion-stats">
                                        <div class="completion-badges">
                                            @if($progress->highest_speed >= 40)
                                                <span class="achievement-badge speed">
                                                    <i class="fas fa-bolt"></i>
                                                    Speed Master
                                                </span>
                                            @endif
                                            @if($progress->highest_accuracy >= 95)
                                                <span class="achievement-badge accuracy">
                                                    <i class="fas fa-bullseye"></i>
                                                    Accuracy Expert
                                                </span>
                                            @endif
                                        </div>
                                        <div class="completion-date">
                                            <i class="fas fa-calendar-check"></i>
                                            Completed {{ $progress->completed_at->diffForHumans() }}
                                        </div>
                                    </div>
                                @endif

                                <div class="lesson-actions">
                                    @if($isLocked)
                                        <button class="btn btn-secondary disabled" disabled>
                                            <i class="fas fa-lock"></i>
                                            Locked
                                        </button>
                                        <span class="unlock-hint">Complete previous lesson to unlock</span>
                                    @elseif($isCompleted)
                                        <a href="{{ route('lessons.show', $lesson) }}" class="btn btn-outline-primary">
                                            <i class="fas fa-redo"></i>
                                            Practice Again
                                        </a>
                                        <span class="completion-indicator">
                                            <i class="fas fa-trophy"></i>
                                            Mastered
                                        </span>
                                    @elseif($isInProgress)
                                        <a href="{{ route('lessons.show', $lesson) }}" class="btn btn-primary">
                                            <i class="fas fa-play"></i>
                                            Continue
                                        </a>
                                    @else
                                        <a href="{{ route('lessons.show', $lesson) }}" class="btn btn-primary">
                                            <i class="fas fa-play"></i>
                                            Start Lesson
                                        </a>
                                    @endif
                                </div>
                            </div>

                            <!-- Lesson Preview -->
                            <div class="lesson-preview">
                                @if($lesson->difficulty_level == 'beginner')
                                    <div class="keyboard-preview">
                                        <div class="key-highlight home-row">
                                            <span>A S D F</span>
                                            <span>J K L ;</span>
                                        </div>
                                    </div>
                                @elseif($lesson->difficulty_level == 'intermediate')
                                    <div class="speed-preview">
                                        <div class="speed-meter">
                                            <i class="fas fa-tachometer-alt"></i>
                                            <span>30-50 WPM</span>
                                        </div>
                                    </div>
                                @else
                                    <div class="mastery-preview">
                                        <div class="mastery-indicator">
                                            <i class="fas fa-crown"></i>
                                            <span>Expert Level</span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="quick-stats-section">
            <div class="stats-grid">
                <div class="stat-card speed">
                    <div class="stat-icon">
                        <i class="fas fa-tachometer-alt"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-number">
                            @php
                                $avgSpeed = $lessons->filter(function($lesson) {
                                    return $lesson->progress->first() && $lesson->progress->first()->highest_speed > 0;
                                })->avg(function($lesson) {
                                    return $lesson->progress->first()->highest_speed;
                                });
                            @endphp
                            {{ $avgSpeed ? number_format($avgSpeed, 1) : '0' }}
                        </span>
                        <span class="stat-label">Avg Speed (WPM)</span>
                        <div class="stat-trend">
                            <i class="fas fa-arrow-up"></i>
                            <span>+12% this week</span>
                        </div>
                    </div>
                </div>

                <div class="stat-card accuracy">
                    <div class="stat-icon">
                        <i class="fas fa-bullseye"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-number">
                            @php
                                $avgAccuracy = $lessons->filter(function($lesson) {
                                    return $lesson->progress->first() && $lesson->progress->first()->highest_accuracy > 0;
                                })->avg(function($lesson) {
                                    return $lesson->progress->first()->highest_accuracy;
                                });
                            @endphp
                            {{ $avgAccuracy ? number_format($avgAccuracy, 1) : '0' }}%
                        </span>
                        <span class="stat-label">Avg Accuracy</span>
                        <div class="stat-trend">
                            <i class="fas fa-arrow-up"></i>
                            <span>+5% this week</span>
                        </div>
                    </div>
                </div>

                <div class="stat-card experience">
                    <div class="stat-icon">
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-number">
                            {{ $lessons->sum(function($lesson) {
                                $progress = $lesson->progress->first();
                                return $progress ? $progress->experience_earned : 0;
                            }) }}
                        </span>
                        <span class="stat-label">XP Earned</span>
                        <div class="stat-trend">
                            <i class="fas fa-plus"></i>
                            <span>From lessons</span>
                        </div>
                    </div>
                </div>

                <div class="stat-card time">
                    <div class="stat-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-number">
                            {{ $lessons->sum('estimated_completion_time') }}
                        </span>
                        <span class="stat-label">Total Time (min)</span>
                        <div class="stat-trend">
                            <i class="fas fa-graduation-cap"></i>
                            <span>Learning time</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Learning Tips -->
        <div class="tips-section">
            <div class="tips-card">
                <div class="tips-header">
                    <i class="fas fa-lightbulb"></i>
                    <h3>Learning Tips for Success</h3>
                </div>
                <div class="tips-grid">
                    <div class="tip-item">
                        <i class="fas fa-hand-paper"></i>
                        <div>
                            <h4>Proper Finger Position</h4>
                            <p>Keep your fingers on the home row (ASDF JKL;) and use the correct finger for each key. This builds muscle memory faster.</p>
                        </div>
                    </div>
                    <div class="tip-item">
                        <i class="fas fa-eye"></i>
                        <div>
                            <h4>Don't Look at Keys</h4>
                            <p>Focus on the screen, not the keyboard. Trust your fingers to find the right keys through practice and repetition.</p>
                        </div>
                    </div>
                    <div class="tip-item">
                        <i class="fas fa-clock"></i>
                        <div>
                            <h4>Practice Regularly</h4>
                            <p>Short, consistent practice sessions are more effective than long, irregular ones. Aim for 15-30 minutes daily.</p>
                        </div>
                    </div>
                    <div class="tip-item">
                        <i class="fas fa-target"></i>
                        <div>
                            <h4>Accuracy First</h4>
                            <p>Focus on accuracy before speed. It's better to type slowly and correctly than fast with many errors.</p>
                        </div>
                    </div>
                    <div class="tip-item">
                        <i class="fas fa-chair"></i>
                        <div>
                            <h4>Proper Posture</h4>
                            <p>Sit straight with feet flat on floor, wrists level with keyboard, and screen at eye level for comfortable typing.</p>
                        </div>
                    </div>
                    <div class="tip-item">
                        <i class="fas fa-heart"></i>
                        <div>
                            <h4>Stay Patient</h4>
                            <p>Learning proper typing takes time. Be patient with yourself and celebrate small improvements along the way.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Achievement Showcase -->
        @if($completedCount > 0)
        <div class="achievements-section">
            <div class="achievements-card">
                <div class="achievements-header">
                    <i class="fas fa-trophy"></i>
                    <h3>Your Achievements</h3>
                </div>
                <div class="achievements-showcase">
                    @if($completedCount >= 1)
                        <div class="achievement-badge earned">
                            <i class="fas fa-play"></i>
                            <span>First Steps</span>
                        </div>
                    @endif
                    @if($completedCount >= 5)
                        <div class="achievement-badge earned">
                            <i class="fas fa-fire"></i>
                            <span>Getting Started</span>
                        </div>
                    @endif
                    @if($completedCount >= 10)
                        <div class="achievement-badge earned">
                            <i class="fas fa-medal"></i>
                            <span>Dedicated Learner</span>
                        </div>
                    @endif
                    @if($completionPercentage >= 50)
                        <div class="achievement-badge earned">
                            <i class="fas fa-star"></i>
                            <span>Halfway Hero</span>
                        </div>
                    @endif
                    @if($completionPercentage >= 100)
                        <div class="achievement-badge earned">
                            <i class="fas fa-crown"></i>
                            <span>Typing Master</span>
                        </div>
                    @endif
                    
                    <!-- Future achievements -->
                    @if($completedCount < 5)
                        <div class="achievement-badge locked">
                            <i class="fas fa-fire"></i>
                            <span>Getting Started</span>
                            <div class="unlock-requirement">Complete 5 lessons</div>
                        </div>
                    @endif
                    @if($completionPercentage < 100)
                        <div class="achievement-badge locked">
                            <i class="fas fa-crown"></i>
                            <span>Typing Master</span>
                            <div class="unlock-requirement">Complete all lessons</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<style>
.lessons-container {
    padding: 2rem 0;
    min-height: calc(100vh - 80px);
}

/* Header */
.lessons-header {
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

.lessons-header::before {
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
    background: var(--gradient-accent);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: 0.5rem;
}

.header-content p {
    color: var(--text-secondary);
    font-size: 1.1rem;
}

/* Progress Overview */
.progress-overview {
    display: flex;
    align-items: center;
    gap: 2rem;
}

.overall-progress {
    position: relative;
}

.progress-circle {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
}

.progress-ring {
    transform: rotate(-90deg);
}

.progress-ring__circle {
    stroke-dasharray: 226;
    stroke-dashoffset: 226;
    transition: stroke-dashoffset 2s ease-in-out;
}

.progress-ring__circle--fill {
    stroke-dashoffset: calc(226 - (226 * var(--progress)) / 100);
}

.progress-text {
    position: absolute;
    text-align: center;
}

.progress-percentage {
    display: block;
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text-primary);
}

.progress-label {
    font-size: 0.8rem;
    color: var(--text-secondary);
}

.progress-stats {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.stat-item {
    text-align: center;
}

.stat-number {
    display: block;
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text-primary);
}

.stat-label {
    font-size: 0.8rem;
    color: var(--text-secondary);
}

/* Learning Path Section */
.learning-path-section {
    margin-bottom: 4rem;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 3rem;
}

.section-header h2 {
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--text-primary);
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.journey-indicator {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    background: rgba(59, 130, 246, 0.1);
    color: var(--info);
    padding: 0.5rem 1rem;
    border-radius: var(--border-radius);
    font-size: 0.85rem;
    font-weight: 600;
    border: 1px solid rgba(59, 130, 246, 0.2);
}

/* Learning Path */
.learning-path {
    display: flex;
    flex-direction: column;
    gap: 2rem;
    position: relative;
}

.lesson-node {
    position: relative;
    display: flex;
    align-items: center;
    gap: 2rem;
    opacity: 0;
    animation: slideInRight 0.6s ease-out forwards;
}

.lesson-node:nth-child(odd) {
    animation-delay: 0.1s;
}

.lesson-node:nth-child(even) {
    animation-delay: 0.2s;
}

.connection-line {
    position: absolute;
    left: 40px;
    top: 100%;
    width: 3px;
    height: 2rem;
    background: rgba(255, 255, 255, 0.1);
    z-index: 1;
    transition: all 0.5s ease;
}

.connection-line.completed-line {
    background: var(--accent-pink);
    box-shadow: 0 0 10px rgba(255, 107, 157, 0.3);
}

.lesson-card {
    flex: 1;
    background: var(--gradient-card);
    backdrop-filter: blur(var(--blur-amount));
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius-lg);
    padding: 2rem;
    transition: all 0.3s ease;
    position: relative;
    display: flex;
    gap: 2rem;
    z-index: 2;
    overflow: hidden;
}

.lesson-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
    transition: left 0.5s ease;
}

.lesson-card:hover::before {
    left: 100%;
}

.lesson-node.completed .lesson-card {
    border-color: rgba(16, 185, 129, 0.3);
    background: linear-gradient(145deg, rgba(16, 185, 129, 0.05), rgba(255, 107, 157, 0.05));
}

.lesson-node.in-progress .lesson-card {
    border-color: rgba(245, 158, 11, 0.3);
    background: linear-gradient(145deg, rgba(245, 158, 11, 0.05), rgba(255, 107, 157, 0.05));
}

.lesson-node.locked .lesson-card {
    opacity: 0.6;
    border-color: rgba(107, 114, 128, 0.3);
}

.lesson-card:hover {
    transform: translateX(10px);
    box-shadow: 0 8px 25px rgba(139, 92, 246, 0.15);
}

.lesson-status {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
    min-width: 80px;
}

.status-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    position: relative;
    transition: all 0.3s ease;
}

.status-icon.completed {
    background: linear-gradient(45deg, #10b981, #059669);
    color: white;
    box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
}

.status-icon.in-progress {
    background: linear-gradient(45deg, #f59e0b, #eab308);
    color: white;
    box-shadow: 0 4px 15px rgba(245, 158, 11, 0.3);
    animation: pulseGlow 2s infinite;
}

.status-icon.available {
    background: var(--gradient-button);
    color: white;
    box-shadow: 0 4px 15px rgba(255, 107, 157, 0.3);
}

.status-icon.locked {
    background: rgba(107, 114, 128, 0.2);
    color: var(--text-muted);
    border: 2px solid rgba(107, 114, 128, 0.3);
}

.lesson-number {
    background: rgba(255, 255, 255, 0.1);
    color: var(--text-primary);
    padding: 0.25rem 0.75rem;
    border-radius: var(--border-radius);
    font-weight: 600;
    font-size: 0.8rem;
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.lesson-content {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.lesson-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
}

.lesson-header h3 {
    font-size: 1.3rem;
    font-weight: 600;
    color: var(--text-primary);
}

.difficulty-badge {
    padding: 0.25rem 0.75rem;
    border-radius: var(--border-radius);
    font-size: 0.8rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.difficulty-badge.beginner {
    background: rgba(16, 185, 129, 0.1);
    color: var(--success);
    border: 1px solid rgba(16, 185, 129, 0.2);
}

.difficulty-badge.intermediate {
    background: rgba(245, 158, 11, 0.1);
    color: var(--warning);
    border: 1px solid rgba(245, 158, 11, 0.2);
}

.difficulty-badge.advanced {
    background: rgba(239, 68, 68, 0.1);
    color: var(--error);
    border: 1px solid rgba(239, 68, 68, 0.2);
}

.difficulty-badge.expert {
    background: rgba(139, 92, 246, 0.1);
    color: var(--accent-purple);
    border: 1px solid rgba(139, 92, 246, 0.2);
}

.lesson-description {
    color: var(--text-secondary);
    line-height: 1.5;
    margin-bottom: 0.5rem;
}

.lesson-stats {
    display: flex;
    gap: 1.5rem;
    margin-bottom: 1rem;
}

.lesson-stats .stat-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--text-secondary);
    font-size: 0.9rem;
}

.lesson-stats .stat-item i {
    color: var(--accent-pink);
}

.completion-stats {
    background: rgba(16, 185, 129, 0.05);
    border: 1px solid rgba(16, 185, 129, 0.2);
    border-radius: var(--border-radius);
    padding: 1rem;
}

.completion-badges {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 0.5rem;
}

.achievement-badge {
    padding: 0.25rem 0.5rem;
    border-radius: calc(var(--border-radius) - 2px);
    font-size: 0.7rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.25rem;
    transition: all 0.3s ease;
    position: relative;
}

.achievement-badge.earned {
    animation: earnedBadge 0.6s ease-out;
}

.achievement-badge.locked {
    opacity: 0.5;
    position: relative;
}

.achievement-badge.speed {
    background: rgba(245, 158, 11, 0.1);
    color: var(--warning);
    border: 1px solid rgba(245, 158, 11, 0.2);
}

.achievement-badge.accuracy {
    background: rgba(59, 130, 246, 0.1);
    color: var(--info);
    border: 1px solid rgba(59, 130, 246, 0.2);
}

.unlock-requirement {
    position: absolute;
    bottom: -25px;
    left: 50%;
    transform: translateX(-50%);
    background: rgba(0, 0, 0, 0.8);
    color: var(--text-secondary);
    padding: 0.25rem 0.5rem;
    border-radius: calc(var(--border-radius) - 4px);
    font-size: 0.6rem;
    white-space: nowrap;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.achievement-badge.locked:hover .unlock-requirement {
    opacity: 1;
}

.completion-date {
    color: var(--success);
    font-size: 0.8rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.lesson-actions {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-top: auto;
}

.unlock-hint {
    color: var(--text-muted);
    font-size: 0.8rem;
    font-style: italic;
}

.completion-indicator {
    color: var(--success);
    font-size: 0.9rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.lesson-preview {
    min-width: 120px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255, 255, 255, 0.03);
    border-radius: var(--border-radius);
    padding: 1rem;
    border: 1px solid rgba(255, 255, 255, 0.05);
}

.keyboard-preview {
    text-align: center;
}

.key-highlight {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    font-family: 'Courier New', monospace;
    font-weight: 600;
    color: var(--accent-pink);
}

.speed-preview,
.mastery-preview {
    text-align: center;
}

.speed-meter,
.mastery-indicator {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
    color: var(--accent-pink);
    font-weight: 600;
}

.speed-meter i,
.mastery-indicator i {
    font-size: 1.5rem;
}

/* Quick Stats Section */
.quick-stats-section {
    margin-bottom: 4rem;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
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
    position: relative;
    overflow: hidden;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 40px rgba(139, 92, 246, 0.2);
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

.stat-card.speed .stat-icon { 
    background: linear-gradient(45deg, #ff6b9d, #c084fc); 
}
.stat-card.accuracy .stat-icon { 
    background: linear-gradient(45deg, #00d4ff, #0ea5e9); 
}
.stat-card.experience .stat-icon { 
    background: linear-gradient(45deg, #f59e0b, #eab308); 
}
.stat-card.time .stat-icon { 
    background: linear-gradient(45deg, #10b981, #059669); 
}

.stat-content {
    flex: 1;
}

.stat-content .stat-number {
    font-size: 2rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 0.25rem;
}

.stat-content .stat-label {
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

/* Tips Section */
.tips-section {
    margin-bottom: 4rem;
}

.tips-card {
    background: var(--gradient-card);
    backdrop-filter: blur(var(--blur-amount));
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius-lg);
    padding: 2.5rem;
}

.tips-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 2rem;
}

.tips-header i {
    font-size: 1.5rem;
    color: var(--accent-pink);
}

.tips-header h3 {
    font-size: 1.3rem;
    color: var(--text-primary);
    font-weight: 600;
}

.tips-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
}

.tip-item {
    display: flex;
    gap: 1rem;
    padding: 1.5rem;
    background: rgba(255, 255, 255, 0.03);
    border-radius: var(--border-radius);
    border: 1px solid rgba(255, 255, 255, 0.05);
    transition: all 0.3s ease;
}

.tip-item:hover {
    background: rgba(255, 255, 255, 0.06);
    border-color: var(--accent-pink);
}

.tip-item i {
    color: var(--accent-pink);
    font-size: 1.2rem;
    margin-top: 0.25rem;
    flex-shrink: 0;
}

.tip-item h4 {
    color: var(--text-primary);
    font-size: 1rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.tip-item p {
    color: var(--text-secondary);
    font-size: 0.9rem;
    line-height: 1.4;
}

/* Achievements Section */
.achievements-section {
    margin-bottom: 2rem;
}

.achievements-card {
    background: var(--gradient-card);
    backdrop-filter: blur(var(--blur-amount));
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius-lg);
    padding: 2.5rem;
}

.achievements-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 2rem;
}

.achievements-header i {
    font-size: 1.5rem;
    color: var(--accent-pink);
}

.achievements-header h3 {
    font-size: 1.3rem;
    color: var(--text-primary);
    font-weight: 600;
}

.achievements-showcase {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
}

.achievements-showcase .achievement-badge {
    padding: 0.75rem 1rem;
    border-radius: var(--border-radius);
    font-size: 0.9rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    position: relative;
}

.achievements-showcase .achievement-badge.earned {
    background: var(--gradient-button);
    color: white;
    box-shadow: 0 4px 15px rgba(255, 107, 157, 0.3);
}

.achievements-showcase .achievement-badge.locked {
    background: rgba(107, 114, 128, 0.2);
    color: var(--text-muted);
    border: 1px solid rgba(107, 114, 128, 0.3);
}

/* Animations */
@keyframes slideInRight {
    from {
        opacity: 0;
        transform: translateX(-30px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes pulseGlow {
    0%, 100% {
        box-shadow: 0 4px 15px rgba(245, 158, 11, 0.3);
    }
    50% {
        box-shadow: 0 4px 25px rgba(245, 158, 11, 0.6);
    }
}

@keyframes earnedBadge {
    0% {
        transform: scale(0.8);
        opacity: 0;
    }
    50% {
        transform: scale(1.1);
    }
    100% {
        transform: scale(1);
        opacity: 1;
    }
}

/* Responsive */
@media (max-width: 1024px) {
    .lessons-header {
        flex-direction: column;
        text-align: center;
        gap: 2rem;
    }
    
    .lesson-card {
        flex-direction: column;
        text-align: center;
    }
    
    .lesson-content {
        align-items: center;
    }
    
    .lesson-header {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
}

@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .tips-grid {
        grid-template-columns: 1fr;
    }
    
    .lesson-card {
        padding: 1.5rem;
    }
    
    .header-content h1 {
        font-size: 2rem;
    }
    
    .progress-overview {
        flex-direction: column;
        gap: 1rem;
    }
    
    .lesson-actions {
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .achievements-showcase {
        justify-content: center;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize progress ring animations
    const progressCircles = document.querySelectorAll('.progress-circle');
    progressCircles.forEach(circle => {
        const progress = circle.dataset.progress;
        const progressRing = circle.querySelector('.progress-ring__circle--fill');
        
        // Set CSS custom property for animation
        circle.style.setProperty('--progress', progress);
        
        // Animate after a delay
        setTimeout(() => {
            progressRing.style.strokeDashoffset = calc(226 - (226 * ${progress}) / 100);
        }, 500);
    });

    // Intersection Observer for lesson node animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.animationPlayState = 'running';
            }
        });
    }, observerOptions);

    // Observe all lesson nodes
    document.querySelectorAll('.lesson-node').forEach(node => {
        observer.observe(node);
    });

    // Animate connection lines when lesson is completed
    const completedNodes = document.querySelectorAll('.lesson-node.completed');
    completedNodes.forEach((node, index) => {
        const connectionLine = node.querySelector('.connection-line');
        if (connectionLine) {
            setTimeout(() => {
                connectionLine.classList.add('completed-line');
            }, (index + 1) * 300);
        }
    });

    // Add hover effect to stat cards
    const statCards = document.querySelectorAll('.stat-card');
    statCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });

    // Animate achievement badges on load
    const earnedBadges = document.querySelectorAll('.achievement-badge.earned');
    earnedBadges.forEach((badge, index) => {
        badge.style.animationDelay = ${index * 0.2}s;
    });

    // Smooth scrolling for internal links
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

    // Add loading state to lesson buttons
    const lessonButtons = document.querySelectorAll('.lesson-actions .btn');
    lessonButtons.forEach(button => {
        if (!button.disabled) {
            button.addEventListener('click', function() {
                const originalContent = this.innerHTML;
                this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';
                this.disabled = true;
                
                // Re-enable after navigation (will be overridden by page load)
                setTimeout(() => {
                    this.innerHTML = originalContent;
                    this.disabled = false;
                }, 2000);
            });
        }
    });
});
</script>
@endsection