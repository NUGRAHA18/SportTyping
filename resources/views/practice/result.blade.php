{{-- resources/views/practice/result.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="result-container">
    <div class="container">
        <!-- Result Header -->
        <div class="result-header">
            <div class="header-content">
                <div class="completion-icon">
                    <i class="fas fa-trophy"></i>
                </div>
                <h1>Practice Complete!</h1>
                <p>Great job! Here's how you performed in this session</p>
            </div>
            <div class="session-info">
                <div class="info-item">
                    <span class="info-label">Text:</span>
                    <span class="info-value">{{ $practice->text->title }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Category:</span>
                    <span class="info-value">{{ $practice->text->category->name ?? 'General' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Difficulty:</span>
                    <span class="difficulty-badge difficulty-{{ $practice->text->difficulty_level }}">
                        {{ ucfirst($practice->text->difficulty_level) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Main Results -->
        <div class="main-results scroll-animate">
            <div class="results-grid">
                <div class="result-card primary speed">
                    <div class="card-icon">
                        <i class="fas fa-tachometer-alt"></i>
                    </div>
                    <div class="card-content">
                        <div class="result-value">{{ number_format($practice->typing_speed, 1) }}</div>
                        <div class="result-label">Words Per Minute</div>
                        <div class="result-improvement">
                            @if($bestPractice && $bestPractice->typing_speed > 0)
                                @php
                                    $speedImprovement = $practice->typing_speed - $bestPractice->typing_speed;
                                    $improvementPercent = ($speedImprovement / $bestPractice->typing_speed) * 100;
                                @endphp
                                @if($speedImprovement > 0)
                                    <span class="improvement positive">
                                        <i class="fas fa-arrow-up"></i>
                                        +{{ number_format($speedImprovement, 1) }} WPM ({{ number_format($improvementPercent, 1) }}%)
                                    </span>
                                @elseif($speedImprovement < 0)
                                    <span class="improvement negative">
                                        <i class="fas fa-arrow-down"></i>
                                        {{ number_format($speedImprovement, 1) }} WPM ({{ number_format($improvementPercent, 1) }}%)
                                    </span>
                                @else
                                    <span class="improvement neutral">
                                        <i class="fas fa-minus"></i>
                                        Same as your best
                                    </span>
                                @endif
                            @else
                                <span class="improvement new">
                                    <i class="fas fa-star"></i>
                                    New personal best!
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="result-card primary accuracy">
                    <div class="card-icon">
                        <i class="fas fa-bullseye"></i>
                    </div>
                    <div class="card-content">
                        <div class="result-value">{{ number_format($practice->typing_accuracy, 1) }}%</div>
                        <div class="result-label">Accuracy</div>
                        <div class="result-improvement">
                            @if($bestPractice && $bestPractice->typing_accuracy > 0)
                                @php
                                    $accuracyImprovement = $practice->typing_accuracy - $bestPractice->typing_accuracy;
                                @endphp
                                @if($accuracyImprovement > 0)
                                    <span class="improvement positive">
                                        <i class="fas fa-arrow-up"></i>
                                        +{{ number_format($accuracyImprovement, 1) }}%
                                    </span>
                                @elseif($accuracyImprovement < 0)
                                    <span class="improvement negative">
                                        <i class="fas fa-arrow-down"></i>
                                        {{ number_format($accuracyImprovement, 1) }}%
                                    </span>
                                @else
                                    <span class="improvement neutral">
                                        <i class="fas fa-minus"></i>
                                        Same as your best
                                    </span>
                                @endif
                            @else
                                <span class="improvement new">
                                    <i class="fas fa-star"></i>
                                    New personal best!
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="result-card secondary time">
                    <div class="card-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="card-content">
                        <div class="result-value">
                            {{ gmdate('i:s', $practice->completion_time ?? 0) }}
                        </div>
                        <div class="result-label">Time Taken</div>
                    </div>
                </div>

                <div class="result-card secondary experience">
                    <div class="card-icon">
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="card-content">
                        <div class="result-value">+{{ $practice->experience_earned ?? 0 }}</div>
                        <div class="result-label">Experience Points</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Performance Analysis -->
        <div class="performance-analysis scroll-animate">
            <div class="section-header">
                <h2>
                    <i class="fas fa-chart-line"></i>
                    Performance Analysis
                </h2>
            </div>

            <div class="analysis-grid">
                <!-- Performance Rating -->
                <div class="analysis-card rating">
                    <div class="card-header">
                        <h3>Overall Performance</h3>
                    </div>
                    <div class="rating-display">
                        @php
                            $overallScore = ($practice->typing_speed * 0.6) + ($practice->typing_accuracy * 0.4);
                            $rating = 'Excellent';
                            $ratingClass = 'excellent';
                            
                            if ($overallScore < 30) {
                                $rating = 'Beginner';
                                $ratingClass = 'beginner';
                            } elseif ($overallScore < 50) {
                                $rating = 'Good';
                                $ratingClass = 'good';
                            } elseif ($overallScore < 70) {
                                $rating = 'Great';
                                $ratingClass = 'great';
                            }
                        @endphp
                        <div class="rating-circle {{ $ratingClass }}">
                            <span class="rating-score">{{ number_format($overallScore, 0) }}</span>
                            <span class="rating-max">/100</span>
                        </div>
                        <div class="rating-label">{{ $rating }}</div>
                    </div>
                </div>

                <!-- Speed Analysis -->
                <div class="analysis-card speed-analysis">
                    <div class="card-header">
                        <h3>Speed Analysis</h3>
                    </div>
                    <div class="speed-breakdown">
                        <div class="speed-item">
                            <span class="speed-label">Your Speed:</span>
                            <span class="speed-value">{{ number_format($practice->typing_speed, 1) }} WPM</span>
                        </div>
                        @if($globalAverage)
                        <div class="speed-item">
                            <span class="speed-label">Global Average:</span>
                            <span class="speed-value">{{ number_format($globalAverage, 1) }} WPM</span>
                        </div>
                        @endif
                        @if($bestPractice)
                        <div class="speed-item">
                            <span class="speed-label">Your Best:</span>
                            <span class="speed-value">{{ number_format($bestPractice->typing_speed, 1) }} WPM</span>
                        </div>
                        @endif
                        <div class="speed-comparison">
                            @if($globalAverage)
                                @php
                                    $comparison = $practice->typing_speed - $globalAverage;
                                @endphp
                                @if($comparison > 0)
                                    <span class="comparison positive">
                                        <i class="fas fa-arrow-up"></i>
                                        {{ number_format($comparison, 1) }} WPM above average
                                    </span>
                                @else
                                    <span class="comparison negative">
                                        <i class="fas fa-arrow-down"></i>
                                        {{ number_format(abs($comparison), 1) }} WPM below average
                                    </span>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Accuracy Insights -->
                <div class="analysis-card accuracy-analysis">
                    <div class="card-header">
                        <h3>Accuracy Insights</h3>
                    </div>
                    <div class="accuracy-breakdown">
                        <div class="accuracy-meter">
                            <div class="meter-background">
                                <div class="meter-fill" style="width: {{ $practice->typing_accuracy }}%"></div>
                            </div>
                            <div class="meter-labels">
                                <span class="meter-start">0%</span>
                                <span class="meter-end">100%</span>
                            </div>
                        </div>
                        <div class="accuracy-feedback">
                            @if($practice->typing_accuracy >= 95)
                                <div class="feedback excellent">
                                    <i class="fas fa-medal"></i>
                                    <span>Outstanding accuracy! You're typing like a pro.</span>
                                </div>
                            @elseif($practice->typing_accuracy >= 90)
                                <div class="feedback good">
                                    <i class="fas fa-thumbs-up"></i>
                                    <span>Great accuracy! Keep up the excellent work.</span>
                                </div>
                            @elseif($practice->typing_accuracy >= 80)
                                <div class="feedback average">
                                    <i class="fas fa-target"></i>
                                    <span>Good accuracy. Focus on reducing errors for better performance.</span>
                                </div>
                            @else
                                <div class="feedback needs-improvement">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    <span>Focus on accuracy before speed. Slow down and type carefully.</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Achievements & Badges -->
        @if(session('newBadges') && count(session('newBadges')) > 0)
        <div class="achievements-section scroll-animate">
            <div class="section-header">
                <h2>
                    <i class="fas fa-trophy"></i>
                    New Achievements Unlocked!
                </h2>
            </div>

            <div class="badges-grid">
                @foreach(session('newBadges') as $badge)
                <div class="badge-card new">
                    <div class="badge-icon">
                        @if($badge->icon)
                            <img src="{{ asset('images/badges/' . $badge->icon) }}" alt="{{ $badge->name }}">
                        @else
                            <i class="fas fa-medal"></i>
                        @endif
                    </div>
                    <div class="badge-content">
                        <h3>{{ $badge->name }}</h3>
                        <p>{{ $badge->description }}</p>
                        <div class="badge-requirement">
                            {{ ucfirst($badge->requirement_type) }}: {{ $badge->requirement_value }}
                        </div>
                    </div>
                    <div class="badge-new">
                        <i class="fas fa-star"></i>
                        NEW!
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Progress Tracking -->
        <div class="progress-section scroll-animate">
            <div class="section-header">
                <h2>
                    <i class="fas fa-chart-bar"></i>
                    Your Progress
                </h2>
            </div>

            <div class="progress-grid">
                <!-- League Progress -->
                <div class="progress-card league-progress">
                    <x-league-info 
                        :user="Auth::user()" 
                        :show-details="false" 
                        :show-progress="true" 
                        size="default" 
                        layout="vertical"
                    />
                </div>

                <!-- Statistics -->
                <div class="progress-card stats">
                    <div class="card-header">
                        <h3>Session Statistics</h3>
                    </div>
                    <div class="stats-list">
                        <div class="stat-row">
                            <span class="stat-label">Words Typed:</span>
                            <span class="stat-value">{{ str_word_count($practice->text->content) }}</span>
                        </div>
                        <div class="stat-row">
                            <span class="stat-label">Characters:</span>
                            <span class="stat-value">{{ strlen($practice->text->content) }}</span>
                        </div>
                        <div class="stat-row">
                            <span class="stat-label">Avg. Word Length:</span>
                            <span class="stat-value">{{ number_format(strlen($practice->text->content) / str_word_count($practice->text->content), 1) }}</span>
                        </div>
                        <div class="stat-row">
                            <span class="stat-label">Practice Sessions:</span>
                            <span class="stat-value">{{ Auth::user()->practices()->count() }}</span>
                        </div>
                    </div>
                </div>

                <!-- Next Goals -->
                <div class="progress-card goals">
                    <div class="card-header">
                        <h3>Next Goals</h3>
                    </div>
                    <div class="goals-list">
                        @if($practice->typing_speed < 60)
                        <div class="goal-item">
                            <div class="goal-icon speed">
                                <i class="fas fa-tachometer-alt"></i>
                            </div>
                            <div class="goal-content">
                                <span class="goal-title">Reach 60 WPM</span>
                                <div class="goal-progress">
                                    <div class="progress-bar">
                                        <div class="progress-fill" style="width: {{ ($practice->typing_speed / 60) * 100 }}%"></div>
                                    </div>
                                    <span class="progress-text">{{ number_format(60 - $practice->typing_speed, 1) }} WPM to go</span>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if($practice->typing_accuracy < 95)
                        <div class="goal-item">
                            <div class="goal-icon accuracy">
                                <i class="fas fa-bullseye"></i>
                            </div>
                            <div class="goal-content">
                                <span class="goal-title">Achieve 95% Accuracy</span>
                                <div class="goal-progress">
                                    <div class="progress-bar">
                                        <div class="progress-fill" style="width: {{ ($practice->typing_accuracy / 95) * 100 }}%"></div>
                                    </div>
                                    <span class="progress-text">{{ number_format(95 - $practice->typing_accuracy, 1) }}% to go</span>
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="goal-item">
                            <div class="goal-icon practice">
                                <i class="fas fa-fire"></i>
                            </div>
                            <div class="goal-content">
                                <span class="goal-title">Maintain Practice Streak</span>
                                <div class="streak-display">
                                    <span class="streak-number">{{ $practiceStreak ?? 1 }}</span>
                                    <span class="streak-label">day{{ ($practiceStreak ?? 1) > 1 ? 's' : '' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="action-section scroll-animate">
            <div class="action-buttons">
                <button class="btn btn-primary" onclick="retryPractice()">
                    <i class="fas fa-redo"></i>
                    Practice Again
                </button>
                <button class="btn btn-outline" onclick="sharePractice()">
                    <i class="fas fa-share"></i>
                    Share Result
                </button>
                <a href="{{ route('practice.index') }}" class="btn btn-secondary">
                    <i class="fas fa-list"></i>
                    Browse More Texts
                </a>
                <a href="{{ route('competitions.index') }}" class="btn btn-outline">
                    <i class="fas fa-trophy"></i>
                    Join Competition
                </a>
            </div>
        </div>

        <!-- Recommendations -->
        <div class="recommendations-section scroll-animate">
            <div class="section-header">
                <h2>
                    <i class="fas fa-lightbulb"></i>
                    Recommended Next Steps
                </h2>
            </div>

            <div class="recommendations-grid">
                @if($practice->typing_speed < 40)
                <div class="recommendation-card">
                    <div class="rec-icon speed">
                        <i class="fas fa-tachometer-alt"></i>
                    </div>
                    <div class="rec-content">
                        <h3>Focus on Speed</h3>
                        <p>Practice with beginner-level texts to build your typing speed. Aim for consistency before complexity.</p>
                        <a href="{{ route('practice.index', ['difficulty' => 'beginner']) }}" class="rec-action">
                            Practice Beginner Texts <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
                @endif

                @if($practice->typing_accuracy < 90)
                <div class="recommendation-card">
                    <div class="rec-icon accuracy">
                        <i class="fas fa-bullseye"></i>
                    </div>
                    <div class="rec-content">
                        <h3>Improve Accuracy</h3>
                        <p>Take typing lessons to learn proper finger placement and reduce errors.</p>
                        <a href="{{ route('lessons.index') }}" class="rec-action">
                            Start Lessons <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
                @endif

                <div class="recommendation-card">
                    <div class="rec-icon challenge">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <div class="rec-content">
                        <h3>Challenge Yourself</h3>
                        <p>Ready for competition? Test your skills against other typists in real-time races.</p>
                        <a href="{{ route('competitions.index') }}" class="rec-action">
                            Join Competition <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.result-container {
    padding: 2rem 0;
    min-height: calc(100vh - 80px);
}

/* Result Header */
.result-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 3rem;
    padding: 3rem;
    background: var(--gradient-card);
    backdrop-filter: blur(var(--blur-amount));
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius-lg);
    position: relative;
    overflow: hidden;
}

.result-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: var(--gradient-accent);
}

.header-content {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
}

.completion-icon {
    width: 80px;
    height: 80px;
    background: var(--gradient-button);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: white;
    margin-bottom: 1.5rem;
    box-shadow: 0 8px 25px rgba(255, 107, 157, 0.3);
    animation: celebrateIcon 2s ease-in-out;
}

@keyframes celebrateIcon {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.1) rotate(5deg); }
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

.session-info {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.info-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 1rem;
    background: rgba(255, 255, 255, 0.05);
    border-radius: var(--border-radius);
    border: 1px solid rgba(255, 255, 255, 0.1);
    min-width: 250px;
}

.info-label {
    color: var(--text-secondary);
    font-size: 0.9rem;
}

.info-value {
    color: var(--text-primary);
    font-weight: 600;
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

/* Main Results */
.main-results {
    margin-bottom: 3rem;
}

.results-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
}

.result-card {
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

.result-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    opacity: 0.8;
}

.result-card.primary::before {
    background: var(--gradient-accent);
}

.result-card.secondary::before {
    background: linear-gradient(90deg, rgba(255, 255, 255, 0.3), rgba(255, 255, 255, 0.1));
}

.result-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 40px rgba(139, 92, 246, 0.2);
    border-color: var(--accent-pink);
}

.card-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
    font-size: 1.5rem;
    color: white;
}

.result-card.speed .card-icon {
    background: linear-gradient(45deg, #ff6b9d, #c084fc);
}

.result-card.accuracy .card-icon {
    background: linear-gradient(45deg, #00d4ff, #0ea5e9);
}

.result-card.time .card-icon {
    background: linear-gradient(45deg, #f59e0b, #eab308);
}

.result-card.experience .card-icon {
    background: linear-gradient(45deg, #10b981, #059669);
}

.result-value {
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 0.5rem;
    line-height: 1;
}

.result-label {
    color: var(--text-secondary);
    font-size: 0.9rem;
    margin-bottom: 1rem;
}

.result-improvement {
    font-size: 0.85rem;
    font-weight: 600;
}

.improvement {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.25rem;
}

.improvement.positive {
    color: var(--success);
}

.improvement.negative {
    color: var(--error);
}

.improvement.neutral {
    color: var(--text-muted);
}

.improvement.new {
    color: var(--accent-pink);
}

/* Performance Analysis */
.performance-analysis {
    margin-bottom: 3rem;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.section-header h2 {
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--text-primary);
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.analysis-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
}

.analysis-card {
    background: var(--gradient-card);
    backdrop-filter: blur(var(--blur-amount));
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius-lg);
    padding: 2rem;
    transition: all 0.3s ease;
}

.analysis-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(139, 92, 246, 0.2);
}

.card-header {
    margin-bottom: 1.5rem;
}

.card-header h3 {
    color: var(--text-primary);
    font-size: 1.2rem;
    font-weight: 600;
}

/* Rating Display */
.rating-display {
    text-align: center;
}

.rating-circle {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    position: relative;
    border: 4px solid;
}

.rating-circle.beginner {
    border-color: #6b7280;
    background: radial-gradient(circle, rgba(107, 114, 128, 0.1), transparent);
}

.rating-circle.good {
    border-color: #10b981;
    background: radial-gradient(circle, rgba(16, 185, 129, 0.1), transparent);
}

.rating-circle.great {
    border-color: #0ea5e9;
    background: radial-gradient(circle, rgba(14, 165, 233, 0.1), transparent);
}

.rating-circle.excellent {
    border-color: var(--accent-pink);
    background: radial-gradient(circle, rgba(255, 107, 157, 0.1), transparent);
}

.rating-score {
    font-size: 2rem;
    font-weight: 700;
    color: var(--text-primary);
}

.rating-max {
    font-size: 1rem;
    color: var(--text-secondary);
}

.rating-label {
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--text-primary);
}

/* Speed Analysis */
.speed-breakdown {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.speed-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 1rem;
    background: rgba(255, 255, 255, 0.03);
    border-radius: var(--border-radius);
    border: 1px solid rgba(255, 255, 255, 0.05);
}

.speed-label {
    color: var(--text
}
/* ini belum selesai */