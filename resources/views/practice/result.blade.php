{{-- resources/views/practice/result.blade.php --}}
@extends('layouts.app')

@section('title', 'Practice Result - SportTyping')

@section('content')
<div class="practice-result-container">
    <div class="container">
        <!-- Header Section -->
        <div class="result-header">
            <div class="result-badge">
                @if($practice->accuracy >= 95)
                    <i class="fas fa-trophy text-warning"></i>
                @elseif($practice->accuracy >= 80)
                    <i class="fas fa-medal text-primary"></i>
                @else
                    <i class="fas fa-target text-secondary"></i>
                @endif
            </div>
            <h1 class="result-title">Practice Complete!</h1>
            <p class="result-subtitle">{{ $practice->text->title }}</p>
        </div>

        <!-- Main Stats Grid -->
        <div class="stats-grid">
            <x-stat-card 
                icon="fas fa-tachometer-alt"
                title="Words Per Minute"
                :value="$practice->wpm"
                unit="WPM"
                color="primary"
                size="large"
                :change="$wpmImprovement ?? null"
                :changeType="($wpmImprovement ?? 0) > 0 ? 'positive' : 'neutral'"
            />
            
            <x-stat-card 
                icon="fas fa-bullseye"
                title="Accuracy"
                :value="number_format($practice->accuracy, 1)"
                unit="%"
                color="success"
                size="large"
                :change="$accuracyImprovement ?? null"
                :changeType="($accuracyImprovement ?? 0) > 0 ? 'positive' : 'neutral'"
            />
            
            <x-stat-card 
                icon="fas fa-clock"
                title="Time Taken"
                :value="gmdate('i:s', $practice->completion_time)"
                color="info"
                size="large"
            />
            
            <x-stat-card 
                icon="fas fa-keyboard"
                title="Characters"
                :value="strlen($practice->text->content)"
                unit="chars"
                color="warning"
                size="large"
            />
        </div>

        <!-- Performance Analysis -->
        <div class="analysis-section">
            <h3 class="section-title">
                <i class="fas fa-chart-line"></i>
                Performance Analysis
            </h3>
            
            <div class="analysis-grid">
                <!-- Performance Breakdown -->
                <div class="analysis-card">
                    <h4>Performance Breakdown</h4>
                    <div class="breakdown-stats">
                        <div class="breakdown-item">
                            <div class="breakdown-label">Correct Characters</div>
                            <div class="breakdown-value correct">{{ $correctChars ?? 0 }}</div>
                        </div>
                        <div class="breakdown-item">
                            <div class="breakdown-label">Incorrect Characters</div>
                            <div class="breakdown-value incorrect">{{ $incorrectChars ?? 0 }}</div>
                        </div>
                        <div class="breakdown-item">
                            <div class="breakdown-label">Total Keystrokes</div>
                            <div class="breakdown-value total">{{ ($correctChars ?? 0) + ($incorrectChars ?? 0) }}</div>
                        </div>
                    </div>
                </div>

                <!-- Speed Rating -->
                <div class="analysis-card">
                    <h4>Speed Rating</h4>
                    <div class="rating-display">
                        @if($practice->wpm >= 80)
                            <div class="rating excellent">
                                <i class="fas fa-rocket"></i>
                                <span>Excellent</span>
                            </div>
                            <p>You're typing at professional speed!</p>
                        @elseif($practice->wpm >= 60)
                            <div class="rating good">
                                <i class="fas fa-thumbs-up"></i>
                                <span>Good</span>
                            </div>
                            <p>Great speed! Keep practicing to reach expert level.</p>
                        @elseif($practice->wpm >= 40)
                            <div class="rating average">
                                <i class="fas fa-chart-line"></i>
                                <span>Average</span>
                            </div>
                            <p>You're making progress. Focus on consistency.</p>
                        @else
                            <div class="rating beginner">
                                <i class="fas fa-seedling"></i>
                                <span>Beginner</span>
                            </div>
                            <p>Great start! Practice regularly to improve.</p>
                        @endif
                    </div>
                </div>

                <!-- Accuracy Rating -->
                <div class="analysis-card">
                    <h4>Accuracy Rating</h4>
                    <div class="rating-display">
                        @if($practice->accuracy >= 95)
                            <div class="rating excellent">
                                <i class="fas fa-bullseye"></i>
                                <span>Precise</span>
                            </div>
                            <p>Outstanding accuracy! You rarely make mistakes.</p>
                        @elseif($practice->accuracy >= 85)
                            <div class="rating good">
                                <i class="fas fa-target"></i>
                                <span>Accurate</span>
                            </div>
                            <p>Good accuracy. Minor improvements will perfect your typing.</p>
                        @elseif($practice->accuracy >= 70)
                            <div class="rating average">
                                <i class="fas fa-crosshairs"></i>
                                <span>Developing</span>
                            </div>
                            <p>Focus on accuracy over speed for better results.</p>
                        @else
                            <div class="rating beginner">
                                <i class="fas fa-search"></i>
                                <span>Learning</span>
                            </div>
                            <p>Take your time and focus on hitting the right keys.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Badges Earned -->
        @if(isset($newBadges) && count($newBadges) > 0)
        <div class="badges-section">
            <h3 class="section-title">
                <i class="fas fa-medal"></i>
                Badges Earned!
            </h3>
            <div class="badges-grid">
                @foreach($newBadges as $badge)
                    <x-badge-display 
                        :badge="$badge" 
                        :earned="true" 
                        size="large" 
                        :interactive="false"
                        class="new-badge" 
                    />
                @endforeach
            </div>
        </div>
        @endif

        <!-- Progress Comparison -->
        @if(isset($previousSessions) && count($previousSessions) > 0)
        <div class="progress-section">
            <h3 class="section-title">
                <i class="fas fa-chart-area"></i>
                Progress Comparison
            </h3>
            <div class="progress-chart">
                <canvas id="progressChart" width="400" height="200"></canvas>
            </div>
        </div>
        @endif

        <!-- Action Buttons -->
        <div class="action-section">
            <div class="action-buttons">
                <a href="{{ route('practice.show', $practice->text) }}" class="btn btn-primary">
                    <i class="fas fa-redo"></i>
                    Practice Again
                </a>
                <a href="{{ route('practice.index') }}" class="btn btn-outline-primary">
                    <i class="fas fa-list"></i>
                    More Texts
                </a>
                <a href="{{ route('competitions.index') }}" class="btn btn-outline-success">
                    <i class="fas fa-trophy"></i>
                    Join Competition
                </a>
                <a href="{{ route('profile.show') }}" class="btn btn-outline-info">
                    <i class="fas fa-user"></i>
                    View Profile
                </a>
            </div>
        </div>
    </div>
</div>

<style>
.practice-result-container {
    min-height: calc(100vh - 80px);
    background: linear-gradient(135deg, var(--bg-secondary), var(--bg-tertiary));
    padding: 2rem 0;
}

.result-header {
    text-align: center;
    margin-bottom: 3rem;
    position: relative;
}

.result-badge {
    width: 80px;
    height: 80px;
    background: var(--champion-gradient);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: white;
    margin: 0 auto 1.5rem;
    box-shadow: var(--shadow-lg);
    animation: result-bounce 0.6s ease-out;
}

@keyframes result-bounce {
    0% { transform: scale(0) rotate(-180deg); }
    50% { transform: scale(1.2) rotate(-90deg); }
    100% { transform: scale(1) rotate(0deg); }
}

.result-title {
    font-family: var(--font-display);
    font-size: 3rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 0.5rem;
    animation: fade-in-up 0.8s ease-out 0.2s both;
}

.result-subtitle {
    color: var(--text-secondary);
    font-size: 1.2rem;
    font-weight: 500;
    animation: fade-in-up 0.8s ease-out 0.4s both;
}

@keyframes fade-in-up {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
    margin-bottom: 3rem;
    animation: fade-in-up 0.8s ease-out 0.6s both;
}

.section-title {
    font-family: var(--font-display);
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.analysis-section {
    background: var(--bg-card);
    border-radius: var(--border-radius-lg);
    padding: 2rem;
    margin-bottom: 3rem;
    box-shadow: var(--shadow-sm);
    animation: fade-in-up 0.8s ease-out 0.8s both;
}

.analysis-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
}

.analysis-card {
    background: var(--bg-secondary);
    border-radius: var(--border-radius);
    padding: 1.5rem;
    border: 1px solid var(--border-light);
}

.analysis-card h4 {
    font-family: var(--font-display);
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 1rem;
}

.breakdown-stats {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.breakdown-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem;
    background: var(--bg-card);
    border-radius: var(--border-radius);
}

.breakdown-label {
    color: var(--text-secondary);
    font-weight: 500;
}

.breakdown-value {
    font-family: var(--font-display);
    font-size: 1.25rem;
    font-weight: 700;
}

.breakdown-value.correct { color: var(--accent-success); }
.breakdown-value.incorrect { color: var(--accent-danger); }
.breakdown-value.total { color: var(--text-primary); }

.rating-display {
    text-align: center;
}

.rating {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 1rem;
    padding: 1rem;
    border-radius: var(--border-radius);
}

.rating i {
    font-size: 2rem;
    margin-bottom: 0.5rem;
}

.rating span {
    font-family: var(--font-display);
    font-size: 1.25rem;
    font-weight: 700;
}

.rating.excellent {
    background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(5, 150, 105, 0.1));
    color: var(--accent-success);
}

.rating.good {
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(29, 78, 216, 0.1));
    color: var(--accent-primary);
}

.rating.average {
    background: linear-gradient(135deg, rgba(245, 158, 11, 0.1), rgba(217, 119, 6, 0.1));
    color: var(--accent-secondary);
}

.rating.beginner {
    background: linear-gradient(135deg, rgba(107, 114, 128, 0.1), rgba(75, 85, 99, 0.1));
    color: var(--text-secondary);
}

.badges-section {
    background: var(--bg-card);
    border-radius: var(--border-radius-lg);
    padding: 2rem;
    margin-bottom: 3rem;
    box-shadow: var(--shadow-sm);
    text-align: center;
    animation: fade-in-up 0.8s ease-out 1s both;
}

.badges-grid {
    display: flex;
    justify-content: center;
    gap: 2rem;
    flex-wrap: wrap;
}

.new-badge {
    animation: badge-celebration 1s ease-out;
}

@keyframes badge-celebration {
    0% { transform: scale(0) rotate(-180deg); opacity: 0; }
    50% { transform: scale(1.2) rotate(10deg); opacity: 1; }
    100% { transform: scale(1) rotate(0deg); opacity: 1; }
}

.progress-section {
    background: var(--bg-card);
    border-radius: var(--border-radius-lg);
    padding: 2rem;
    margin-bottom: 3rem;
    box-shadow: var(--shadow-sm);
    animation: fade-in-up 0.8s ease-out 1.2s both;
}

.progress-chart {
    height: 300px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.action-section {
    text-align: center;
    animation: fade-in-up 0.8s ease-out 1.4s both;
}

.action-buttons {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

.action-buttons .btn {
    min-width: 150px;
    padding: 0.75rem 1.5rem;
    font-weight: 600;
    border-radius: var(--border-radius);
    transition: all 0.3s ease;
}

.action-buttons .btn:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

/* Responsive Design */
@media (max-width: 1024px) {
    .stats-grid {
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    }
    
    .analysis-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .practice-result-container {
        padding: 1rem 0;
    }
    
    .result-title {
        font-size: 2rem;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    
    .analysis-section,
    .badges-section,
    .progress-section {
        padding: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .action-buttons {
        flex-direction: column;
        align-items: center;
    }
    
    .action-buttons .btn {
        width: 100%;
        max-width: 300px;
    }
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    @if(isset($previousSessions) && count($previousSessions) > 0)
    // Progress Chart
    const ctx = document.getElementById('progressChart').getContext('2d');
    const chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($previousSessions->pluck('created_at')->map(fn($date) => $date->format('M j'))) !!},
            datasets: [{
                label: 'WPM',
                data: {!! json_encode($previousSessions->pluck('wpm')) !!},
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.4,
                fill: true
            }, {
                label: 'Accuracy %',
                data: {!! json_encode($previousSessions->pluck('accuracy')) !!},
                borderColor: 'rgb(16, 185, 129)',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)'
                    }
                },
                x: {
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)'
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                }
            }
        }
    });
    @endif
});
</script>
@endsection