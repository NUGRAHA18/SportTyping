@extends('layouts.app')

@section('content')
<div class="dashboard-container">
    <div class="container">
        <!-- Welcome Header -->
        <div class="welcome-section">
            <div class="welcome-content">
                <div class="welcome-text">
                    <h1 class="welcome-title">
                        Welcome back, <span class="user-highlight">{{ Auth::user()->username }}</span>! 🏆
                    </h1>
                    <p class="welcome-subtitle">
                        Ready to break your typing records today? Your current league: 
                        <span class="league-badge">{{ Auth::user()->profile?->league?->name ?? 'Novice' }}</span>
                    </p>
                </div>
                <div class="welcome-actions">
                    <a href="{{ route('competitions.index') }}" class="btn btn-primary btn-action">
                        <i class="fas fa-racing-flag"></i>
                        Join Competition
                    </a>
                    <a href="{{ route('practice.index') }}" class="btn btn-secondary btn-action">
                        <i class="fas fa-keyboard"></i>
                        Quick Practice
                    </a>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="stats-section">
            <div class="stats-grid">
                <!-- WPM Card -->
                <div class="stat-card speed-card">
                    <div class="stat-icon">
                        <i class="fas fa-tachometer-alt"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">{{ number_format(Auth::user()->profile?->typing_speed_avg ?? 0, 1) }}</div>
                        <div class="stat-label">Average WPM</div>
                        <div class="stat-trend positive">
                            <i class="fas fa-arrow-up"></i>
                            +5.2% this week
                        </div>
                    </div>
                    <div class="stat-progress">
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: {{ min(100, (Auth::user()->profile?->typing_speed_avg ?? 0) / 100 * 100) }}%"></div>
                        </div>
                    </div>
                </div>

                <!-- Accuracy Card -->
                <div class="stat-card accuracy-card">
                    <div class="stat-icon">
                        <i class="fas fa-bullseye"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">{{ number_format(Auth::user()->profile?->typing_accuracy_avg ?? 0, 1) }}%</div>
                        <div class="stat-label">Accuracy</div>
                        <div class="stat-trend positive">
                            <i class="fas fa-arrow-up"></i>
                            +2.1% this week
                        </div>
                    </div>
                    <div class="stat-progress">
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: {{ Auth::user()->profile?->typing_accuracy_avg ?? 0 }}%"></div>
                        </div>
                    </div>
                </div>

                <!-- Experience Card -->
                <div class="stat-card experience-card">
                    <div class="stat-icon">
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">{{ number_format(Auth::user()->profile?->total_experience ?? 0) }}</div>
                        <div class="stat-label">Total Experience</div>
                        <div class="stat-trend positive">
                            <i class="fas fa-arrow-up"></i>
                            +125 XP today
                        </div>
                    </div>
                    <div class="stat-progress">
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 65%"></div>
                        </div>
                    </div>
                </div>

                <!-- Competitions Card -->
                <div class="stat-card competitions-card">
                    <div class="stat-icon">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">{{ Auth::user()->profile?->total_competitions ?? 0 }}</div>
                        <div class="stat-label">Competitions</div>
                        <div class="stat-trend neutral">
                            <i class="fas fa-medal"></i>
                            {{ Auth::user()->profile?->total_wins ?? 0 }} wins
                        </div>
                    </div>
                    <div class="stat-progress">
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: {{ Auth::user()->profile?->total_competitions ? min(100, (Auth::user()->profile->total_competitions / 50) * 100) : 0 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="dashboard-grid">
            <!-- Recent Activity -->
            <div class="activity-section">
                <div class="section-header">
                    <h3><i class="fas fa-clock"></i> Recent Activity</h3>
                    <a href="#" class="view-all-link">View All</a>
                </div>
                
                <div class="activity-feed">
                    @if($recentCompetitions && $recentCompetitions->count() > 0)
                        @foreach($recentCompetitions->take(3) as $result)
                        <div class="activity-item competition-activity">
                            <div class="activity-icon">
                                <i class="fas fa-racing-flag"></i>
                            </div>
                            <div class="activity-content">
                                <div class="activity-title">{{ $result->competition->title }}</div>
                                <div class="activity-stats">
                                    {{ $result->typing_speed }} WPM • {{ $result->typing_accuracy }}% accuracy
                                </div>
                                <div class="activity-time">{{ $result->created_at->diffForHumans() }}</div>
                            </div>
                            <div class="activity-badge">
                                <span class="position-badge">#{{ $result->position ?? 'N/A' }}</span>
                            </div>
                        </div>
                        @endforeach
                    @endif

                    @if($recentPractices && $recentPractices->count() > 0)
                        @foreach($recentPractices->take(2) as $practice)
                        <div class="activity-item practice-activity">
                            <div class="activity-icon">
                                <i class="fas fa-keyboard"></i>
                            </div>
                            <div class="activity-content">
                                <div class="activity-title">{{ $practice->text->title }}</div>
                                <div class="activity-stats">
                                    {{ $practice->typing_speed }} WPM • {{ $practice->typing_accuracy }}% accuracy
                                </div>
                                <div class="activity-time">{{ $practice->created_at->diffForHumans() }}</div>
                            </div>
                            <div class="activity-badge">
                                <span class="xp-badge">+{{ $practice->experience_earned }} XP</span>
                            </div>
                        </div>
                        @endforeach
                    @endif

                    @if((!$recentCompetitions || $recentCompetitions->count() == 0) && (!$recentPractices || $recentPractices->count() == 0))
                        <div class="empty-activity">
                            <div class="empty-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="empty-text">
                                <h4>No recent activity</h4>
                                <p>Start typing to see your activity here!</p>
                            </div>
                            <div class="empty-actions">
                                <a href="{{ route('practice.index') }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-play"></i>
                                    Start Practice
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Performance Chart -->
            <div class="chart-section">
                <div class="section-header">
                    <h3><i class="fas fa-chart-line"></i> Performance Trends</h3>
                    <div class="chart-controls">
                        <button class="chart-btn active" data-period="week">Week</button>
                        <button class="chart-btn" data-period="month">Month</button>
                        <button class="chart-btn" data-period="year">Year</button>
                    </div>
                </div>
                
                <div class="chart-container">
                    <canvas id="performanceChart" width="400" height="200"></canvas>
                </div>
                
                <div class="chart-stats">
                    <div class="chart-stat">
                        <div class="chart-stat-label">Best WPM</div>
                        <div class="chart-stat-value">{{ number_format(Auth::user()->profile?->typing_speed_avg ?? 0 + 15, 0) }}</div>
                    </div>
                    <div class="chart-stat">
                        <div class="chart-stat-label">Best Accuracy</div>
                        <div class="chart-stat-value">{{ number_format(min(100, (Auth::user()->profile?->typing_accuracy_avg ?? 0) + 5), 1) }}%</div>
                    </div>
                    <div class="chart-stat">
                        <div class="chart-stat-label">Consistency</div>
                        <div class="chart-stat-value">85%</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="quick-actions-section">
            <div class="section-header">
                <h3><i class="fas fa-bolt"></i> Quick Actions</h3>
            </div>
            
            <div class="actions-grid">
                <a href="{{ route('competitions.index') }}" class="action-card competition-action">
                    <div class="action-icon">
                        <i class="fas fa-racing-flag"></i>
                    </div>
                    <div class="action-content">
                        <h4>Join Competition</h4>
                        <p>Compete with others in real-time</p>
                    </div>
                    <div class="action-badge">
                        <span class="live-indicator">LIVE</span>
                    </div>
                </a>

                <a href="{{ route('practice.index') }}" class="action-card practice-action">
                    <div class="action-icon">
                        <i class="fas fa-keyboard"></i>
                    </div>
                    <div class="action-content">
                        <h4>Practice Typing</h4>
                        <p>Improve your speed and accuracy</p>
                    </div>
                    <div class="action-arrow">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </a>

                <a href="{{ route('lessons.index') }}" class="action-card lessons-action">
                    <div class="action-icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <div class="action-content">
                        <h4>Take Lessons</h4>
                        <p>Learn proper typing technique</p>
                    </div>
                    <div class="action-progress">
                        <div class="progress-ring">
                            <span>{{ $completedLessons }}/{{ $totalLessons }}</span>
                        </div>
                    </div>
                </a>

                <a href="{{ route('leaderboards.index') }}" class="action-card leaderboard-action">
                    <div class="action-icon">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <div class="action-content">
                        <h4>Leaderboards</h4>
                        <p>See how you rank globally</p>
                    </div>
                    <div class="action-rank">
                        <span>#{{ rand(50, 500) }}</span>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

<style>
.dashboard-container {
    background: var(--bg-primary);
    min-height: calc(100vh - 120px);
    padding: 2rem 0;
}

/* Welcome Section */
.welcome-section {
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.02), rgba(16, 185, 129, 0.02));
    border-radius: var(--border-radius-xl);
    padding: 3rem;
    margin-bottom: 3rem;
    border: 1px solid var(--border-light);
    position: relative;
    overflow: hidden;
}

.welcome-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: var(--champion-gradient);
}

.welcome-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 2rem;
}

.welcome-title {
    font-family: var(--font-display);
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 1rem;
}

.user-highlight {
    background: var(--champion-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.welcome-subtitle {
    font-size: 1.1rem;
    color: var(--text-secondary);
    line-height: 1.6;
}

.league-badge {
    background: var(--medal-gradient);
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: var(--border-radius);
    font-weight: 600;
    font-size: 0.9rem;
}

.welcome-actions {
    display: flex;
    gap: 1rem;
    flex-shrink: 0;
}

.btn-action {
    padding: 1rem 2rem;
    font-weight: 600;
    border-radius: var(--border-radius);
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

/* Statistics Section */
.stats-section {
    margin-bottom: 3rem;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 2rem;
}

.stat-card {
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: var(--border-radius-lg);
    padding: 2rem;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    box-shadow: var(--shadow-sm);
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-lg);
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    transition: all 0.3s ease;
}

.speed-card::before { background: var(--champion-gradient); }
.accuracy-card::before { background: var(--victory-gradient); }
.experience-card::before { background: var(--medal-gradient); }
.competitions-card::before { background: linear-gradient(135deg, var(--accent-purple), var(--accent-danger)); }

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: var(--border-radius-lg);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
    margin-bottom: 1.5rem;
    transition: all 0.3s ease;
}

.speed-card .stat-icon { background: var(--champion-gradient); }
.accuracy-card .stat-icon { background: var(--victory-gradient); }
.experience-card .stat-icon { background: var(--medal-gradient); }
.competitions-card .stat-icon { background: linear-gradient(135deg, var(--accent-purple), var(--accent-danger)); }

.stat-card:hover .stat-icon {
    transform: scale(1.1) rotate(5deg);
}

.stat-number {
    font-family: var(--font-display);
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--text-primary);
    line-height: 1.2;
    margin-bottom: 0.5rem;
}

.stat-label {
    color: var(--text-secondary);
    font-weight: 500;
    margin-bottom: 0.75rem;
}

.stat-trend {
    font-size: 0.875rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.stat-trend.positive { color: var(--accent-success); }
.stat-trend.negative { color: var(--accent-danger); }
.stat-trend.neutral { color: var(--text-muted); }

.stat-progress {
    margin-top: 1.5rem;
}

.progress-bar {
    height: 8px;
    background: rgba(0, 0, 0, 0.05);
    border-radius: 4px;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    background: var(--champion-gradient);
    border-radius: 4px;
    transition: width 0.8s ease;
}

.accuracy-card .progress-fill { background: var(--victory-gradient); }
.experience-card .progress-fill { background: var(--medal-gradient); }
.competitions-card .progress-fill { background: linear-gradient(135deg, var(--accent-purple), var(--accent-danger)); }

/* Dashboard Grid */
.dashboard-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
    margin-bottom: 3rem;
}

/* Activity Section */
.activity-section, .chart-section {
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: var(--border-radius-lg);
    padding: 2rem;
    box-shadow: var(--shadow-sm);
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.section-header h3 {
    font-family: var(--font-display);
    font-size: 1.3rem;
    font-weight: 600;
    color: var(--text-primary);
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.view-all-link {
    color: var(--accent-primary);
    text-decoration: none;
    font-weight: 500;
    font-size: 0.9rem;
    transition: all 0.3s ease;
}

.view-all-link:hover {
    color: var(--accent-primary);
}

.activity-feed {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.activity-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.5rem;
    background: var(--bg-secondary);
    border-radius: var(--border-radius);
    transition: all 0.3s ease;
    border: 1px solid transparent;
}

.activity-item:hover {
    background: rgba(59, 130, 246, 0.02);
    border-color: var(--accent-primary);
    transform: translateX(5px);
}

.activity-icon {
    width: 48px;
    height: 48px;
    border-radius: var(--border-radius);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    color: white;
    flex-shrink: 0;
}

.competition-activity .activity-icon { background: var(--champion-gradient); }
.practice-activity .activity-icon { background: var(--victory-gradient); }

.activity-content {
    flex: 1;
}

.activity-title {
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 0.25rem;
}

.activity-stats {
    color: var(--text-secondary);
    font-size: 0.9rem;
    margin-bottom: 0.25rem;
}

.activity-time {
    color: var(--text-muted);
    font-size: 0.8rem;
}

.activity-badge {
    flex-shrink: 0;
}

.position-badge, .xp-badge {
    padding: 0.25rem 0.75rem;
    border-radius: var(--border-radius);
    font-size: 0.8rem;
    font-weight: 600;
}

.position-badge {
    background: var(--medal-gradient);
    color: white;
}

.xp-badge {
    background: var(--victory-gradient);
    color: white;
}

/* Empty State */
.empty-activity {
    text-align: center;
    padding: 3rem 2rem;
}

.empty-icon {
    width: 80px;
    height: 80px;
    background: var(--bg-secondary);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: var(--text-muted);
    margin: 0 auto 1.5rem;
}

.empty-text h4 {
    color: var(--text-primary);
    margin-bottom: 0.5rem;
}

.empty-text p {
    color: var(--text-secondary);
    margin-bottom: 2rem;
}

/* Chart Section */
.chart-controls {
    display: flex;
    gap: 0.5rem;
}

.chart-btn {
    padding: 0.5rem 1rem;
    background: transparent;
    border: 1px solid var(--border-light);
    border-radius: var(--border-radius);
    color: var(--text-secondary);
    font-weight: 500;
    transition: all 0.3s ease;
    cursor: pointer;
}

.chart-btn.active,
.chart-btn:hover {
    background: var(--accent-primary);
    color: white;
    border-color: var(--accent-primary);
}

.chart-container {
    margin: 2rem 0;
    height: 250px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--bg-secondary);
    border-radius: var(--border-radius);
}

.chart-stats {
    display: flex;
    justify-content: space-around;
    padding-top: 1rem;
    border-top: 1px solid var(--border-light);
}

.chart-stat {
    text-align: center;
}

.chart-stat-label {
    color: var(--text-muted);
    font-size: 0.8rem;
    margin-bottom: 0.25rem;
}

.chart-stat-value {
    font-family: var(--font-display);
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text-primary);
}

/* Quick Actions Section */
.quick-actions-section {
    margin-bottom: 2rem;
}

.actions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1.5rem;
}

.action-card {
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: var(--border-radius-lg);
    padding: 2rem;
    text-decoration: none;
    color: inherit;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 1.5rem;
    position: relative;
    overflow: hidden;
    box-shadow: var(--shadow-sm);
}

.action-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-lg);
    color: inherit;
    text-decoration: none;
}

.action-card::before {
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

.action-card:hover::before {
    transform: translateX(0);
}

.action-icon {
    width: 60px;
    height: 60px;
    border-radius: var(--border-radius-lg);
    background: var(--champion-gradient);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
    flex-shrink: 0;
    transition: all 0.3s ease;
}

.practice-action .action-icon { background: var(--victory-gradient); }
.lessons-action .action-icon { background: var(--medal-gradient); }
.leaderboard-action .action-icon { background: linear-gradient(135deg, var(--accent-purple), var(--accent-danger)); }

.action-card:hover .action-icon {
    transform: scale(1.1) rotate(5deg);
}

.action-content {
    flex: 1;
}

.action-content h4 {
    font-family: var(--font-display);
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 0.5rem;
}

.action-content p {
    color: var(--text-secondary);
    font-size: 0.9rem;
}

.action-badge, .action-arrow, .action-progress, .action-rank {
    flex-shrink: 0;
}

.live-indicator {
    background: var(--accent-danger);
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: var(--border-radius);
    font-size: 0.8rem;
    font-weight: 600;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.7; }
}

.action-arrow {
    color: var(--text-muted);
    font-size: 1.2rem;
}

.progress-ring {
    width: 48px;
    height: 48px;
    border: 3px solid var(--border-light);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.8rem;
    font-weight: 600;
    color: var(--text-primary);
    background: var(--bg-secondary);
}

.action-rank {
    font-family: var(--font-display);
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--accent-primary);
}

/* Responsive Design */
@media (max-width: 1024px) {
    .dashboard-grid {
        grid-template-columns: 1fr;
    }
    
    .welcome-content {
        flex-direction: column;
        text-align: center;
        gap: 2rem;
    }
    
    .welcome-actions {
        flex-wrap: wrap;
        justify-content: center;
    }
}

@media (max-width: 768px) {
    .dashboard-container {
        padding: 1rem 0;
    }
    
    .welcome-section {
        padding: 2rem;
        margin-bottom: 2rem;
    }
    
    .welcome-title {
        font-size: 2rem;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    
    .actions-grid {
        grid-template-columns: 1fr;
    }
    
    .action-card {
        padding: 1.5rem;
    }
    
    .chart-controls {
        flex-wrap: wrap;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animate statistics on load
    setTimeout(() => {
        const progressBars = document.querySelectorAll('.progress-fill');
        progressBars.forEach(bar => {
            const width = bar.style.width;
            bar.style.width = '0%';
            setTimeout(() => {
                bar.style.width = width;
            }, 100);
        });
    }, 500);

    // Chart functionality (mock implementation)
    const chartBtns = document.querySelectorAll('.chart-btn');
    chartBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            chartBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            // Here you would implement actual chart update logic
            // For now, just show loading state
            const chartContainer = document.querySelector('.chart-container');
            chartContainer.innerHTML = `
                <div style="text-align: center; color: var(--text-muted);">
                    <div class="loading" style="margin-bottom: 1rem;"></div>
                    <p>Loading ${this.dataset.period} data...</p>
                </div>
            `;
            
            // Simulate loading
            setTimeout(() => {
                chartContainer.innerHTML = `
                    <canvas id="performanceChart" width="400" height="200"></canvas>
                `;
                // Here you would draw the actual chart
            }, 1000);
        });
    });

    // Add floating animation to stat cards
    const statCards = document.querySelectorAll('.stat-card');
    statCards.forEach((card, index) => {
        setTimeout(() => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';
            card.style.transition = 'all 0.6s ease';
            
            setTimeout(() => {
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 100);
        }, index * 100);
    });

    // Activity items hover effect
    const activityItems = document.querySelectorAll('.activity-item');
    activityItems.forEach(item => {
        item.addEventListener('mouseenter', function() {
            this.style.transform = 'translateX(10px)';
        });
        
        item.addEventListener('mouseleave', function() {
            this.style.transform = 'translateX(0)';
        });
    });
});
</script>
@endsection