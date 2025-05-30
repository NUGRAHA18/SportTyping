@extends('layouts.app')

@section('content')
<div class="dashboard-container">
    <div class="container-fluid">
        <!-- Welcome Header -->
        <div class="dashboard-header">
            <div class="welcome-section">
                <div class="user-info">
                    <div class="user-avatar">
                        @if(Auth::user()->profile?->avatar)
                            <img src="{{ asset('storage/' . Auth::user()->profile->avatar) }}" alt="{{ Auth::user()->username }}">
                        @else
                            <div class="avatar-placeholder">
                                {{ strtoupper(substr(Auth::user()->username, 0, 1)) }}
                            </div>
                        @endif
                        <div class="online-indicator"></div>
                    </div>
                    <div class="user-details">
                        <h1>Welcome back, <span class="username">{{ Auth::user()->username }}</span>!</h1>
                        <p class="subtitle">Ready to dominate the typing arena today?</p>
                        <div class="user-badges">
                            <div class="league-badge">
                                <i class="fas fa-crown"></i>
                                <span>{{ Auth::user()->profile?->league?->name ?? 'Novice' }} League</span>
                            </div>
                            <div class="experience-badge">
                                <i class="fas fa-star"></i>
                                <span>{{ number_format(Auth::user()->profile?->total_experience ?? 0) }} EXP</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="quick-stats">
                    <div class="stat-item">
                        <div class="stat-icon speed">
                            <i class="fas fa-tachometer-alt"></i>
                        </div>
                        <div class="stat-details">
                            <span class="stat-value">{{ number_format(Auth::user()->profile?->typing_speed_avg ?? 0) }}</span>
                            <span class="stat-label">Avg WPM</span>
                        </div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-icon accuracy">
                            <i class="fas fa-bullseye"></i>
                        </div>
                        <div class="stat-details">
                            <span class="stat-value">{{ number_format(Auth::user()->profile?->typing_accuracy_avg ?? 0, 1) }}%</span>
                            <span class="stat-label">Accuracy</span>
                        </div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-icon competitions">
                            <i class="fas fa-trophy"></i>
                        </div>
                        <div class="stat-details">
                            <span class="stat-value">{{ Auth::user()->profile?->total_competitions ?? 0 }}</span>
                            <span class="stat-label">Competitions</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Dashboard Grid -->
        <div class="dashboard-grid">
            <!-- Performance Stats Cards -->
            <div class="stats-section">
                <h2 class="section-title">
                    <i class="fas fa-chart-line"></i>
                    Performance Overview
                </h2>
                
                <div class="stats-grid">
                    <div class="stat-card primary">
                        <div class="card-header">
                            <div class="card-icon">
                                <i class="fas fa-keyboard"></i>
                            </div>
                            <div class="card-trend up">
                                <i class="fas fa-arrow-up"></i>
                                <span>+12%</span>
                            </div>
                        </div>
                        <div class="card-content">
                            <h3>{{ number_format(Auth::user()->profile?->typing_speed_avg ?? 0) }}</h3>
                            <p>Words Per Minute</p>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: {{ min(100, (Auth::user()->profile?->typing_speed_avg ?? 0) / 1.5) }}%"></div>
                            </div>
                            <span class="progress-text">{{ Auth::user()->profile?->typing_speed_avg >= 100 ? 'Expert Level' : (Auth::user()->profile?->typing_speed_avg >= 70 ? 'Advanced' : (Auth::user()->profile?->typing_speed_avg >= 40 ? 'Intermediate' : 'Beginner')) }}</span>
                        </div>
                    </div>
                    
                    <div class="stat-card success">
                        <div class="card-header">
                            <div class="card-icon">
                                <i class="fas fa-bullseye"></i>
                            </div>
                            <div class="card-trend up">
                                <i class="fas fa-arrow-up"></i>
                                <span>+8%</span>
                            </div>
                        </div>
                        <div class="card-content">
                            <h3>{{ number_format(Auth::user()->profile?->typing_accuracy_avg ?? 0, 1) }}%</h3>
                            <p>Typing Accuracy</p>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: {{ Auth::user()->profile?->typing_accuracy_avg ?? 0 }}%"></div>
                            </div>
                            <span class="progress-text">{{ Auth::user()->profile?->typing_accuracy_avg >= 95 ? 'Excellent' : (Auth::user()->profile?->typing_accuracy_avg >= 90 ? 'Good' : 'Needs Work') }}</span>
                        </div>
                    </div>
                    
                    <div class="stat-card warning">
                        <div class="card-header">
                            <div class="card-icon">
                                <i class="fas fa-medal"></i>
                            </div>
                            <div class="card-trend up">
                                <i class="fas fa-arrow-up"></i>
                                <span>+3</span>
                            </div>
                        </div>
                        <div class="card-content">
                            <h3>{{ Auth::user()->profile?->total_competitions ?? 0 }}</h3>
                            <p>Total Competitions</p>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: {{ min(100, (Auth::user()->profile?->total_competitions ?? 0) * 2) }}%"></div>
                            </div>
                            <span class="progress-text">{{ Auth::user()->profile?->total_competitions >= 50 ? 'Veteran' : (Auth::user()->profile?->total_competitions >= 10 ? 'Regular' : 'Rookie') }}</span>
                        </div>
                    </div>
                    
                    <div class="stat-card info">
                        <div class="card-header">
                            <div class="card-icon">
                                <i class="fas fa-star"></i>
                            </div>
                            <div class="card-trend up">
                                <i class="fas fa-arrow-up"></i>
                                <span>+250</span>
                            </div>
                        </div>
                        <div class="card-content">
                            <h3>{{ number_format(Auth::user()->profile?->total_experience ?? 0) }}</h3>
                            <p>Experience Points</p>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: {{ min(100, (Auth::user()->profile?->total_experience ?? 0) / 500) }}%"></div>
                            </div>
                            <span class="progress-text">{{ Auth::user()->profile?->league?->name ?? 'Novice' }} League</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="actions-section">
                <h2 class="section-title">
                    <i class="fas fa-rocket"></i>
                    Quick Actions
                </h2>
                
                <div class="action-cards">
                    <a href="{{ route('competitions.index') }}" class="action-card primary">
                        <div class="action-icon">
                            <i class="fas fa-racing-flag"></i>
                        </div>
                        <div class="action-content">
                            <h3>Join Competition</h3>
                            <p>Compete with other typists</p>
                            <div class="action-badge live">LIVE</div>
                        </div>
                        <div class="action-arrow">
                            <i class="fas fa-arrow-right"></i>
                        </div>
                    </a>
                    
                    <a href="{{ route('practice.index') }}" class="action-card success">
                        <div class="action-icon">
                            <i class="fas fa-keyboard"></i>
                        </div>
                        <div class="action-content">
                            <h3>Practice Typing</h3>
                            <p>Improve your skills</p>
                            <div class="action-badge">START</div>
                        </div>
                        <div class="action-arrow">
                            <i class="fas fa-arrow-right"></i>
                        </div>
                    </a>
                    
                    <a href="{{ route('lessons.index') }}" class="action-card warning">
                        <div class="action-icon">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <div class="action-content">
                            <h3>Take Lessons</h3>
                            <p>Learn proper technique</p>
                            <div class="action-badge">LEARN</div>
                        </div>
                        <div class="action-arrow">
                            <i class="fas fa-arrow-right"></i>
                        </div>
                    </a>
                    
                    <a href="{{ route('leaderboards.index') }}" class="action-card info">
                        <div class="action-icon">
                            <i class="fas fa-trophy"></i>
                        </div>
                        <div class="action-content">
                            <h3>Leaderboards</h3>
                            <p>Check your ranking</p>
                            <div class="action-badge">RANK</div>
                        </div>
                        <div class="action-arrow">
                            <i class="fas fa-arrow-right"></i>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="activity-section">
                <h2 class="section-title">
                    <i class="fas fa-history"></i>
                    Recent Activity
                </h2>
                
                <div class="activity-feed">
                    @if(isset($recentCompetitions) && $recentCompetitions->count() > 0)
                        @foreach($recentCompetitions->take(3) as $result)
                        <div class="activity-item competition">
                            <div class="activity-icon">
                                <i class="fas fa-trophy"></i>
                            </div>
                            <div class="activity-content">
                                <h4>{{ $result->competition->title }}</h4>
                                <p>{{ number_format($result->typing_speed) }} WPM • {{ number_format($result->typing_accuracy, 1) }}% accuracy</p>
                                <span class="activity-time">{{ $result->created_at->diffForHumans() }}</span>
                            </div>
                            <div class="activity-badge success">
                                +{{ $result->experience_earned }} EXP
                            </div>
                        </div>
                        @endforeach
                    @endif
                    
                    @if(isset($recentPractices) && $recentPractices->count() > 0)
                        @foreach($recentPractices->take(2) as $practice)
                        <div class="activity-item practice">
                            <div class="activity-icon">
                                <i class="fas fa-keyboard"></i>
                            </div>
                            <div class="activity-content">
                                <h4>{{ $practice->text->title }}</h4>
                                <p>{{ number_format($practice->typing_speed) }} WPM • {{ number_format($practice->typing_accuracy, 1) }}% accuracy</p>
                                <span class="activity-time">{{ $practice->created_at->diffForHumans() }}</span>
                            </div>
                            <div class="activity-badge info">
                                +{{ $practice->experience_earned }} EXP
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="empty-activity">
                            <i class="fas fa-clipboard-list"></i>
                            <h3>No Recent Activity</h3>
                            <p>Start practicing or join competitions to see your activity here!</p>
                            <a href="{{ route('practice.index') }}" class="btn-primary">
                                <i class="fas fa-play"></i>
                                Start Practicing
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Progress Chart -->
            <div class="chart-section">
                <h2 class="section-title">
                    <i class="fas fa-chart-area"></i>
                    Progress Chart
                </h2>
                
                <div class="chart-container">
                    <div class="chart-header">
                        <div class="chart-controls">
                            <button class="chart-btn active" data-period="7">7 Days</button>
                            <button class="chart-btn" data-period="30">30 Days</button>
                            <button class="chart-btn" data-period="90">90 Days</button>
                        </div>
                        <div class="chart-legend">
                            <div class="legend-item">
                                <div class="legend-color speed"></div>
                                <span>WPM</span>
                            </div>
                            <div class="legend-item">
                                <div class="legend-color accuracy"></div>
                                <span>Accuracy</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="chart-area">
                        <canvas id="progressChart" width="400" height="200"></canvas>
                    </div>
                    
                    <div class="chart-summary">
                        <div class="summary-item">
                            <span class="summary-label">Best WPM</span>
                            <span class="summary-value">{{ number_format(Auth::user()->profile?->typing_speed_avg * 1.2 ?? 0) }}</span>
                        </div>
                        <div class="summary-item">
                            <span class="summary-label">Best Accuracy</span>
                            <span class="summary-value">{{ number_format(min(100, (Auth::user()->profile?->typing_accuracy_avg ?? 0) + 5), 1) }}%</span>
                        </div>
                        <div class="summary-item">
                            <span class="summary-label">Sessions</span>
                            <span class="summary-value">{{ (Auth::user()->profile?->total_competitions ?? 0) + 15 }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Achievement Highlights -->
            <div class="achievements-section">
                <h2 class="section-title">
                    <i class="fas fa-medal"></i>
                    Recent Achievements
                </h2>
                
                <div class="achievements-grid">
                    <div class="achievement-card earned">
                        <div class="achievement-icon">
                            <i class="fas fa-trophy"></i>
                        </div>
                        <div class="achievement-content">
                            <h3>Speed Demon</h3>
                            <p>Achieved 70+ WPM</p>
                        </div>
                        <div class="achievement-status">
                            <i class="fas fa-check"></i>
                        </div>
                    </div>
                    
                    <div class="achievement-card earned">
                        <div class="achievement-icon">
                            <i class="fas fa-bullseye"></i>
                        </div>
                        <div class="achievement-content">
                            <h3>Precise</h3>
                            <p>90% accuracy achieved</p>
                        </div>
                        <div class="achievement-status">
                            <i class="fas fa-check"></i>
                        </div>
                    </div>
                    
                    <div class="achievement-card progress">
                        <div class="achievement-icon">
                            <i class="fas fa-medal"></i>
                        </div>
                        <div class="achievement-content">
                            <h3>Competitor</h3>
                            <p>Join 10 competitions</p>
                            <div class="achievement-progress">
                                <div class="progress-bar mini">
                                    <div class="progress-fill" style="width: {{ min(100, (Auth::user()->profile?->total_competitions ?? 0) * 10) }}%"></div>
                                </div>
                                <span>{{ Auth::user()->profile?->total_competitions ?? 0 }}/10</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="achievement-card locked">
                        <div class="achievement-icon">
                            <i class="fas fa-crown"></i>
                        </div>
                        <div class="achievement-content">
                            <h3>Lightning Hands</h3>
                            <p>Achieve 100+ WPM</p>
                        </div>
                        <div class="achievement-status">
                            <i class="fas fa-lock"></i>
                        </div>
                    </div>
                </div>
                
                <div class="achievements-footer">
                    <a href="{{ route('badges.index') }}" class="btn-secondary">
                        <i class="fas fa-medal"></i>
                        View All Badges
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.dashboard-container {
    padding: 2rem 0;
    background: var(--bg-primary);
    min-height: calc(100vh - 76px);
}

/* Header Section */
.dashboard-header {
    margin-bottom: 3rem;
}

.welcome-section {
    background: var(--gradient-card);
    backdrop-filter: blur(var(--blur-amount));
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius-xl);
    padding: 2.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
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

.user-info {
    display: flex;
    align-items: center;
    gap: 2rem;
}

.user-avatar {
    position: relative;
    width: 80px;
    height: 80px;
}

.user-avatar img,
.avatar-placeholder {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    object-fit: cover;
}

.avatar-placeholder {
    background: var(--champion-gradient);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 2rem;
    font-weight: 700;
}

.online-indicator {
    position: absolute;
    bottom: 5px;
    right: 5px;
    width: 20px;
    height: 20px;
    background: var(--accent-success);
    border: 3px solid white;
    border-radius: 50%;
    animation: pulse 2s infinite;
}

.user-details h1 {
    font-family: var(--font-display);
    font-size: 2rem;
    color: var(--text-primary);
    margin-bottom: 0.5rem;
}

.username {
    background: var(--champion-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.subtitle {
    color: var(--text-secondary);
    font-size: 1.1rem;
    margin-bottom: 1rem;
}

.user-badges {
    display: flex;
    gap: 1rem;
}

.league-badge,
.experience-badge {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: var(--border-radius);
    font-weight: 600;
    font-size: 0.875rem;
}

.league-badge {
    background: linear-gradient(135deg, rgba(245, 158, 11, 0.1), rgba(245, 158, 11, 0.05));
    color: var(--accent-secondary);
    border: 1px solid rgba(245, 158, 11, 0.2);
}

.experience-badge {
    background: linear-gradient(135deg, rgba(139, 92, 246, 0.1), rgba(139, 92, 246, 0.05));
    color: var(--accent-purple);
    border: 1px solid rgba(139, 92, 246, 0.2);
}

.quick-stats {
    display: flex;
    gap: 2rem;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: rgba(255, 255, 255, 0.05);
    border-radius: var(--border-radius);
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.25rem;
}

.stat-icon.speed { background: var(--champion-gradient); }
.stat-icon.accuracy { background: var(--victory-gradient); }
.stat-icon.competitions { background: var(--medal-gradient); }

.stat-details {
    display: flex;
    flex-direction: column;
}

.stat-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text-primary);
    line-height: 1.2;
}

.stat-label {
    color: var(--text-secondary);
    font-size: 0.875rem;
}

/* Dashboard Grid */
.dashboard-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
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

.section-title i {
    color: var(--accent-primary);
}

/* Stats Section */
.stats-section {
    grid-column: span 2;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1.5rem;
}

.stat-card {
    background: var(--bg-card);
    border-radius: var(--border-radius-lg);
    padding: 2rem;
    border: 1px solid var(--border-light);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
}

.stat-card.primary::before { background: var(--champion-gradient); }
.stat-card.success::before { background: var(--victory-gradient); }
.stat-card.warning::before { background: var(--medal-gradient); }
.stat-card.info::before { background: linear-gradient(135deg, #8b5cf6, #6366f1); }

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-lg);
}

.card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
}

.card-icon {
    width: 50px;
    height: 50px;
    border-radius: var(--border-radius);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.25rem;
}

.stat-card.primary .card-icon { background: var(--champion-gradient); }
.stat-card.success .card-icon { background: var(--victory-gradient); }
.stat-card.warning .card-icon { background: var(--medal-gradient); }
.stat-card.info .card-icon { background: linear-gradient(135deg, #8b5cf6, #6366f1); }

.card-trend {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: var(--border-radius);
    font-size: 0.875rem;
    font-weight: 600;
}

.card-trend.up {
    background: rgba(16, 185, 129, 0.1);
    color: var(--accent-success);
}

.card-content h3 {
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 0.5rem;
    line-height: 1.2;
}

.card-content p {
    color: var(--text-secondary);
    margin-bottom: 1rem;
    font-weight: 500;
}

.progress-bar {
    width: 100%;
    height: 8px;
    background: var(--border-light);
    border-radius: 4px;
    overflow: hidden;
    margin-bottom: 0.75rem;
}

.progress-fill {
    height: 100%;
    border-radius: 4px;
    transition: width 0.8s ease;
}

.stat-card.primary .progress-fill { background: var(--champion-gradient); }
.stat-card.success .progress-fill { background: var(--victory-gradient); }
.stat-card.warning .progress-fill { background: var(--medal-gradient); }
.stat-card.info .progress-fill { background: linear-gradient(135deg, #8b5cf6, #6366f1); }

.progress-text {
    color: var(--text-muted);
    font-size: 0.875rem;
    font-weight: 500;
}

/* Actions Section */
.action-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1rem;
}

.action-card {
    background: var(--bg-card);
    border-radius: var(--border-radius-lg);
    padding: 1.5rem;
    border: 1px solid var(--border-light);
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.action-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
}

.action-card.primary::before { background: var(--champion-gradient); }
.action-card.success::before { background: var(--victory-gradient); }
.action-card.warning::before { background: var(--medal-gradient); }
.action-card.info::before { background: linear-gradient(135deg, #8b5cf6, #6366f1); }

.action-card:hover {
    transform: translateY(-3px);
    box-shadow: var(--shadow-lg);
    text-decoration: none;
}

.action-icon {
    width: 50px;
    height: 50px;
    border-radius: var(--border-radius);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.25rem;
    flex-shrink: 0;
}

.action-card.primary .action-icon { background: var(--champion-gradient); }
.action-card.success .action-icon { background: var(--victory-gradient); }
.action-card.warning .action-icon { background: var(--medal-gradient); }
.action-card.info .action-icon { background: linear-gradient(135deg, #8b5cf6, #6366f1); }

.action-content {
    flex: 1;
}

.action-content h3 {
    color: var(--text-primary);
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.action-content p {
    color: var(--text-secondary);
    font-size: 0.875rem;
    margin: 0;
}

.action-badge {
    position: absolute;
    top: 0.75rem;
    right: 0.75rem;
    padding: 0.25rem 0.75rem;
    background: var(--bg-secondary);
    border: 1px solid var(--border-light);
    border-radius: var(--border-radius-sm);
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--text-secondary);
}

.action-badge.live {
    background: rgba(239, 68, 68, 0.1);
    color: var(--accent-danger);
    border-color: rgba(239, 68, 68, 0.2);
    animation: pulse 2s infinite;
}

.action-arrow {
    color: var(--text-muted);
    transition: all 0.3s ease;
}

.action-card:hover .action-arrow {
    color: var(--accent-primary);
    transform: translateX(5px);
}

/* Activity Section */
.activity-feed {
    background: var(--bg-card);
    border-radius: var(--border-radius-lg);
    border: 1px solid var(--border-light);
    overflow: hidden;
}

.activity-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.5rem;
    border-bottom: 1px solid var(--border-light);
    transition: all 0.3s ease;
}

.activity-item:last-child {
    border-bottom: none;
}

.activity-item:hover {
    background: var(--bg-secondary);
}

.activity-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1rem;
    flex-shrink: 0;
}

.activity-item.competition .activity-icon { background: var(--champion-gradient); }
.activity-item.practice .activity-icon { background: var(--victory-gradient); }

.activity-content {
    flex: 1;
}

.activity-content h4 {
    color: var(--text-primary);
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.activity-content p {
    color: var(--text-secondary);
    font-size: 0.875rem;
    margin-bottom: 0.25rem;
}

.activity-time {
    color: var(--text-muted);
    font-size: 0.8rem;
}

.activity-badge {
    padding: 0.5rem 1rem;
    border-radius: var(--border-radius);
    font-size: 0.875rem;
    font-weight: 600;
}

.activity-badge.success {
    background: rgba(16, 185, 129, 0.1);
    color: var(--accent-success);
}

.activity-badge.info {
    background: rgba(59, 130, 246, 0.1);
    color: var(--accent-primary);
}

.empty-activity {
    text-align: center;
    padding: 3rem;
}

.empty-activity i {
    font-size: 3rem;
    color: var(--text-muted);
    margin-bottom: 1rem;
}

.empty-activity h3 {
    color: var(--text-primary);
    margin-bottom: 0.5rem;
}

.empty-activity p {
    color: var(--text-secondary);
    margin-bottom: 2rem;
}

/* Chart Section */
.chart-container {
    background: var(--bg-card);
    border-radius: var(--border-radius-lg);
    border: 1px solid var(--border-light);
    padding: 2rem;
}

.chart-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.chart-controls {
    display: flex;
    gap: 0.5rem;
}

.chart-btn {
    padding: 0.5rem 1rem;
    border: 1px solid var(--border-light);
    background: var(--bg-secondary);
    color: var(--text-secondary);
    border-radius: var(--border-radius);
    cursor: pointer;
    transition: all 0.3s ease;
    font-weight: 500;
}

.chart-btn.active {
    background: var(--champion-gradient);
    color: white;
    border-color: var(--accent-primary);
}

.chart-legend {
    display: flex;
    gap: 1rem;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
    color: var(--text-secondary);
}

.legend-color {
    width: 12px;
    height: 12px;
    border-radius: 2px;
}

.legend-color.speed { background: var(--champion-gradient); }
.legend-color.accuracy { background: var(--victory-gradient); }

.chart-area {
    height: 200px;
    margin-bottom: 1.5rem;
    position: relative;
}

.chart-summary {
    display: flex;
    justify-content: space-around;
    padding-top: 1.5rem;
    border-top: 1px solid var(--border-light);
}

.summary-item {
    text-align: center;
}

.summary-label {
    display: block;
    color: var(--text-muted);
    font-size: 0.875rem;
    margin-bottom: 0.25rem;
}

.summary-value {
    display: block;
    color: var(--text-primary);
    font-size: 1.25rem;
    font-weight: 700;
}

/* Achievements Section */
.achievements-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1rem;
    margin-bottom: 2rem;
}

.achievement-card {
    background: var(--bg-card);
    border-radius: var(--border-radius-lg);
    padding: 1.5rem;
    border: 1px solid var(--border-light);
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: all 0.3s ease;
    position: relative;
}

.achievement-card.earned {
    border-color: var(--accent-success);
    background: linear-gradient(135deg, rgba(16, 185, 129, 0.05), var(--bg-card));
}

.achievement-card.progress {
    border-color: var(--accent-primary);
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.05), var(--bg-card));
}

.achievement-card.locked {
    opacity: 0.6;
}

.achievement-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.achievement-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.25rem;
    flex-shrink: 0;
}

.achievement-card.earned .achievement-icon { background: var(--victory-gradient); }
.achievement-card.progress .achievement-icon { background: var(--champion-gradient); }
.achievement-card.locked .achievement-icon { background: var(--text-muted); }

.achievement-content {
    flex: 1;
}

.achievement-content h3 {
    color: var(--text-primary);
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.achievement-content p {
    color: var(--text-secondary);
    font-size: 0.875rem;
    margin: 0;
}

.achievement-progress {
    margin-top: 0.75rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.progress-bar.mini {
    height: 6px;
    flex: 1;
}

.achievement-progress span {
    font-size: 0.8rem;
    color: var(--text-muted);
    font-weight: 600;
}

.achievement-status {
    color: white;
    font-size: 1.25rem;
}

.achievement-card.earned .achievement-status { color: var(--accent-success); }
.achievement-card.locked .achievement-status { color: var(--text-muted); }

.achievements-footer {
    text-align: center;
}

.btn-secondary {
    background: transparent;
    border: 2px solid var(--accent-primary);
    color: var(--accent-primary);
    padding: 1rem 2rem;
    border-radius: var(--border-radius);
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    transition: all 0.3s ease;
}

.btn-secondary:hover {
    background: var(--accent-primary);
    color: white;
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
    text-decoration: none;
}

.btn-primary {
    background: var(--champion-gradient);
    border: none;
    color: white;
    padding: 1rem 2rem;
    border-radius: var(--border-radius);
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
    text-decoration: none;
    color: white;
}

/* Responsive */
@media (max-width: 1200px) {
    .dashboard-grid {
        grid-template-columns: 1fr;
    }
    
    .stats-grid {
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    }
}

@media (max-width: 768px) {
    .welcome-section {
        flex-direction: column;
        gap: 2rem;
        text-align: center;
    }
    
    .user-info {
        flex-direction: column;
        gap: 1rem;
    }
    
    .quick-stats {
        flex-direction: column;
        gap: 1rem;
        width: 100%;
    }
    
    .chart-header {
        flex-direction: column;
        gap: 1rem;
    }
    
    .chart-summary {
        flex-direction: column;
        gap: 1rem;
    }
}

@media (max-width: 480px) {
    .dashboard-container {
        padding: 1rem 0;
    }
    
    .welcome-section {
        padding: 1.5rem;
    }
    
    .user-avatar {
        width: 60px;
        height: 60px;
    }
    
    .user-details h1 {
        font-size: 1.5rem;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .action-cards {
        grid-template-columns: 1fr;
    }
    
    .achievements-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize progress animations
    initializeProgressBars();
    
    // Initialize chart
    initializeChart();
    
    // Initialize entrance animations
    initializeAnimations();
    
    // Chart controls
    setupChartControls();
});

function initializeProgressBars() {
    const progressBars = document.querySelectorAll('.progress-fill');
    
    progressBars.forEach((bar, index) => {
        const width = bar.style.width;
        bar.style.width = '0%';
        
        setTimeout(() => {
            bar.style.width = width;
        }, 500 + (index * 100));
    });
}

function initializeChart() {
    const canvas = document.getElementById('progressChart');
    if (!canvas) return;
    
    const ctx = canvas.getContext('2d');
    const width = canvas.width;
    const height = canvas.height;
    
    // Clear canvas
    ctx.clearRect(0, 0, width, height);
    
    // Mock data for demo
    const data = {
        wpm: [45, 48, 52, 49, 55, 58, 62],
        accuracy: [88, 90, 92, 89, 94, 96, 95]
    };
    
    // Drawing the chart
    drawChart(ctx, data, width, height);
}

function drawChart(ctx, data, width, height) {
    const padding = 40;
    const chartWidth = width - (padding * 2);
    const chartHeight = height - (padding * 2);
    
    // Draw grid
    ctx.strokeStyle = '#e2e8f0';
    ctx.lineWidth = 1;
    
    for (let i = 0; i <= 5; i++) {
        const y = padding + (chartHeight / 5) * i;
        ctx.beginPath();
        ctx.moveTo(padding, y);
        ctx.lineTo(width - padding, y);
        ctx.stroke();
    }
    
    // Draw WPM line
    ctx.strokeStyle = '#3b82f6';
    ctx.lineWidth = 3;
    ctx.beginPath();
    
    data.wmp.forEach((value, index) => {
        const x = padding + (chartWidth / (data.wmp.length - 1)) * index;
        const y = padding + chartHeight - ((value / 100) * chartHeight);
        
        if (index === 0) {
            ctx.moveTo(x, y);
        } else {
            ctx.lineTo(x, y);
        }
    });
    ctx.stroke();
    
    // Draw accuracy line
    ctx.strokeStyle = '#10b981';
    ctx.lineWidth = 3;
    ctx.beginPath();
    
    data.accuracy.forEach((value, index) => {
        const x = padding + (chartWidth / (data.accuracy.length - 1)) * index;
        const y = padding + chartHeight - ((value / 100) * chartHeight);
        
        if (index === 0) {
            ctx.moveTo(x, y);
        } else {
            ctx.lineTo(x, y);
        }
    });
    ctx.stroke();
}

function initializeAnimations() {
    // Entrance animations
    const sections = document.querySelectorAll('.stats-section, .actions-section, .activity-section, .chart-section, .achievements-section');
    
    sections.forEach((section, index) => {
        section.style.opacity = '0';
        section.style.transform = 'translateY(30px)';
        section.style.transition = 'all 0.8s ease';
        
        setTimeout(() => {
            section.style.opacity = '1';
            section.style.transform = 'translateY(0)';
        }, 200 + (index * 150));
    });
    
    // Stagger card animations
    const cards = document.querySelectorAll('.stat-card, .action-card, .achievement-card');
    
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'all 0.6s ease';
        
        setTimeout(() => {
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, 800 + (index * 100));
    });
}

function setupChartControls() {
    const chartBtns = document.querySelectorAll('.chart-btn');
    
    chartBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            // Remove active class from all buttons
            chartBtns.forEach(b => b.classList.remove('active'));
            
            // Add active class to clicked button
            this.classList.add('active');
            
            // Update chart based on period
            const period = this.dataset.period;
            updateChart(period);
        });
    });
}

function updateChart(period) {
    // Mock data update based on period
    const canvas = document.getElementById('progressChart');
    if (!canvas) return;
    
    const ctx = canvas.getContext('2d');
    const width = canvas.width;
    const height = canvas.height;
    
    let data;
    
    switch(period) {
        case '7':
            data = {
                wmp: [45, 48, 52, 49, 55, 58, 62],
                accuracy: [88, 90, 92, 89, 94, 96, 95]
            };
            break;
        case '30':
            data = {
                wmp: [42, 45, 48, 52, 55, 58, 62, 65],
                accuracy: [85, 88, 90, 92, 94, 96, 95, 97]
            };
            break;
        case '90':
            data = {
                wmp: [35, 38, 42, 45, 48, 52, 55, 58, 62, 65],
                accuracy: [80, 83, 85, 88, 90, 92, 94, 96, 95, 97]
            };
            break;
    }
    
    // Clear and redraw
    ctx.clearRect(0, 0, width, height);
    drawChart(ctx, data, width, height);
}

// Real-time updates simulation
function simulateRealTimeUpdates() {
    setInterval(() => {
        // Update online indicator
        const indicator = document.querySelector('.online-indicator');
        if (indicator) {
            indicator.style.animation = 'none';
            setTimeout(() => {
                indicator.style.animation = 'pulse 2s infinite';
            }, 10);
        }
        
        // Update experience badge occasionally
        if (Math.random() > 0.95) {
            const expBadge = document.querySelector('.experience-badge span');
            if (expBadge) {
                const currentExp = parseInt(expBadge.textContent.replace(/,/g, ''));
                expBadge.textContent = (currentExp + Math.floor(Math.random() * 50)).toLocaleString() + ' EXP';
            }
        }
    }, 3000);
}

// Start real-time simulation
simulateRealTimeUpdates();
</script>
@endsection
