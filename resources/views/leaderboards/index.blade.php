@extends('layouts.app')

@section('content')
<div class="leaderboards-container">
    <div class="container">
        <!-- Leaderboards Header -->
        <div class="leaderboards-header">
            <div class="header-content">
                <h1>Global Leaderboards</h1>
                <p>See where you rank among the world's fastest typists</p>
            </div>
            <div class="header-stats">
                <div class="stat-item">
                    <span class="stat-number">
                        {{ ($globalLeaderboards ? $globalLeaderboards->count() : 0) + 
                           ($leagueLeaderboards ? $leagueLeaderboards->count() : 0) + 
                           ($deviceLeaderboards ? $deviceLeaderboards->count() : 0) }}
                    </span>
                    <span class="stat-label">Total Boards</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">{{ Auth::user()->profile->current_league_id ?? 1 }}</span>
                    <span class="stat-label">Your League</span>
                </div>
            </div>
        </div>

        <!-- Leaderboard Categories -->
        <div class="categories-section">
            <div class="categories-nav">
                <button class="category-btn active" data-category="global">
                    <i class="fas fa-globe"></i>
                    Global Rankings
                </button>
                <button class="category-btn" data-category="league">
                    <i class="fas fa-trophy"></i>
                    League Rankings
                </button>
                <button class="category-btn" data-category="device">
                    <i class="fas fa-mobile-alt"></i>
                    Device Rankings
                </button>
            </div>
        </div>

        <!-- Global Leaderboards -->
        <div class="leaderboard-section active" id="global-section">
            <div class="section-header">
                <h2><i class="fas fa-globe"></i> Global Leaderboards</h2>
                <div class="section-info">
                    <span class="live-indicator">
                        <span class="live-dot"></span>
                        LIVE RANKINGS
                    </span>
                </div>
            </div>

            <div class="leaderboards-grid">
                @forelse($globalLeaderboards as $leaderboard)
                <div class="leaderboard-card global">
                    <div class="card-header">
                        <div class="board-info">
                            <h3>{{ $leaderboard->name }}</h3>
                            <p>Compete with typists worldwide across all skill levels</p>
                        </div>
                        <div class="board-badge global">
                            <i class="fas fa-globe"></i>
                            Global
                        </div>
                    </div>

                    <div class="top-performers">
                        <div class="podium">
                            <div class="podium-item silver">
                                <div class="performer-avatar">
                                    <div class="avatar-placeholder silver">2</div>
                                </div>
                                <div class="performer-info">
                                    <span class="performer-name">PlayerTwo</span>
                                    <span class="performer-score">142 WPM</span>
                                </div>
                            </div>
                            <div class="podium-item gold">
                                <div class="performer-avatar">
                                    <div class="avatar-placeholder gold">1</div>
                                </div>
                                <div class="performer-info">
                                    <span class="performer-name">SpeedMaster</span>
                                    <span class="performer-score">156 WPM</span>
                                </div>
                                <div class="crown">
                                    <i class="fas fa-crown"></i>
                                </div>
                            </div>
                            <div class="podium-item bronze">
                                <div class="performer-avatar">
                                    <div class="avatar-placeholder bronze">3</div>
                                </div>
                                <div class="performer-info">
                                    <span class="performer-name">FastTyper</span>
                                    <span class="performer-score">138 WPM</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-actions">
                        <a href="{{ route('leaderboards.show', $leaderboard) }}" class="btn btn-primary btn-full">
                            <i class="fas fa-list"></i>
                            View Full Rankings
                        </a>
                    </div>

                    <div class="leaderboard-overlay">
                        <div class="ranking-animation">
                            <div class="rank-line"></div>
                            <div class="rank-line"></div>
                            <div class="rank-line"></div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3>No Global Rankings Yet</h3>
                    <p>Global leaderboards will appear here once competitions begin.</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- League Leaderboards -->
        <div class="leaderboard-section" id="league-section">
            <div class="section-header">
                <h2><i class="fas fa-trophy"></i> League Leaderboards</h2>
                <div class="section-info">
                    <span class="league-info">
                        <i class="fas fa-medal"></i>
                        Your League: {{ Auth::user()->profile->league->name ?? 'Novice' }}
                    </span>
                </div>
            </div>

            <div class="leaderboards-grid">
                @forelse($leagueLeaderboards as $leaderboard)
                <div class="leaderboard-card league">
                    <div class="card-header">
                        <div class="board-info">
                            <h3>{{ $leaderboard->name }}</h3>
                            <p>{{ $leaderboard->league->description ?? 'Compete within your skill level' }}</p>
                        </div>
                        <div class="board-badge league">
                            <i class="fas fa-trophy"></i>
                            {{ $leaderboard->league->name ?? 'League' }}
                        </div>
                    </div>

                    <div class="league-progress">
                        <div class="progress-info">
                            <span class="progress-label">League Progress</span>
                            <span class="progress-value">
                                {{ Auth::user()->profile->total_experience ?? 0 }} / 
                                {{ $leaderboard->league->max_experience ?? '∞' }} XP
                            </span>
                        </div>
                        <div class="progress-bar">
                            @php
                                $currentXP = Auth::user()->profile->total_experience ?? 0;
                                $minXP = $leaderboard->league->min_experience ?? 0;
                                $maxXP = $leaderboard->league->max_experience ?? ($minXP + 1000);
                                if ($maxXP > $minXP) {
                                    $progress = (($currentXP - $minXP) / ($maxXP - $minXP)) * 100;
                                } else {
                                    $progress = 0;
                                }
                                $progress = min(100, max(0, $progress));
                            @endphp
                            <div class="progress-fill" style="width: {{ $progress }}%"></div>
                        </div>
                    </div>

                        <div class="league-stats">
                            <div class="stat-item">
                                <i class="fas fa-users"></i>
                                <span>{{ rand(50, 500) }} Players</span>
                            </div>
                            <div class="stat-item">
                                <i class="fas fa-chart-line"></i>
                                <span>Avg {{ rand(40, 80) }} WPM</span>
                            </div>
                        </div>

                    <div class="card-actions">
                        <a href="{{ route('leaderboards.show', $leaderboard) }}" class="btn btn-outline-primary btn-full">
                            <i class="fas fa-eye"></i>
                            View League Rankings
                        </a>
                    </div>
                </div>
                @empty
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <h3>No League Rankings Yet</h3>
                    <p>League-specific leaderboards will appear here as competitions progress.</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Device Leaderboards -->
        <div class="leaderboard-section" id="device-section">
            <div class="section-header">
                <h2><i class="fas fa-mobile-alt"></i> Device Leaderboards</h2>
                <div class="section-info">
                    <span class="device-info">
                        <i class="fas fa-desktop"></i>
                        Fair competition for each platform
                    </span>
                </div>
            </div>

            <div class="leaderboards-grid">
                @forelse($deviceLeaderboards as $leaderboard)
                <div class="leaderboard-card device">
                    <div class="card-header">
                        <div class="board-info">
                            <h3>{{ $leaderboard->name }}</h3>
                            <p>Rankings for {{ ucfirst($leaderboard->device_type) }} users only</p>
                        </div>
                        <div class="board-badge {{ $leaderboard->device_type }}">
                            <i class="fas fa-{{ $leaderboard->device_type == 'mobile' ? 'mobile-alt' : 'desktop' }}"></i>
                            {{ ucfirst($leaderboard->device_type) }}
                        </div>
                    </div>

                    <div class="device-stats">
                        <div class="stat-row">
                            <div class="stat-item">
                                <span class="stat-label">Top Speed</span>
                                <span class="stat-value">
                                    @if($leaderboard->device_type == 'mobile')
                                        {{ rand(80, 120) }} WPM
                                    @else
                                        {{ rand(120, 180) }} WPM
                                    @endif
                                </span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-label">Avg Accuracy</span>
                                <span class="stat-value">{{ rand(92, 98) }}%</span>
                            </div>
                        </div>
                        <div class="stat-row">
                            <div class="stat-item">
                                <span class="stat-label">Active Players</span>
                                <span class="stat-value">{{ rand(100, 1000) }}</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-label">Competitions</span>
                                <span class="stat-value">{{ rand(20, 100) }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="card-actions">
                        <a href="{{ route('leaderboards.show', $leaderboard) }}" class="btn btn-outline-primary btn-full">
                            <i class="fas fa-list"></i>
                            View {{ ucfirst($leaderboard->device_type) }} Rankings
                        </a>
                    </div>
                </div>
                @empty
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <h3>No Device Rankings Yet</h3>
                    <p>Device-specific leaderboards will appear here once competitions begin.</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Your Performance Summary -->
        <div class="performance-summary">
            <div class="summary-card">
                <div class="summary-header">
                    <i class="fas fa-user-circle"></i>
                    <h3>Your Performance Summary</h3>
                </div>
                <div class="summary-content">
                    <div class="performance-stats">
                        <div class="perf-stat">
                            <div class="stat-icon speed">
                                <i class="fas fa-tachometer-alt"></i>
                            </div>
                            <div class="stat-info">
                                <span class="stat-number">{{ number_format(Auth::user()->profile->typing_speed_avg ?? 0, 1) }}</span>
                                <span class="stat-label">Avg WPM</span>
                            </div>
                        </div>
                        <div class="perf-stat">
                            <div class="stat-icon accuracy">
                                <i class="fas fa-bullseye"></i>
                            </div>
                            <div class="stat-info">
                                <span class="stat-number">{{ number_format(Auth::user()->profile->typing_accuracy_avg ?? 0, 1) }}%</span>
                                <span class="stat-label">Accuracy</span>
                            </div>
                        </div>
                        <div class="perf-stat">
                            <div class="stat-icon competitions">
                                <i class="fas fa-medal"></i>
                            </div>
                            <div class="stat-info">
                                <span class="stat-number">{{ Auth::user()->profile->total_competitions ?? 0 }}</span>
                                <span class="stat-label">Competitions</span>
                            </div>
                        </div>
                        <div class="perf-stat">
                            <div class="stat-icon wins">
                                <i class="fas fa-crown"></i>
                            </div>
                            <div class="stat-info">
                                <span class="stat-number">{{ Auth::user()->profile->total_wins ?? 0 }}</span>
                                <span class="stat-label">Wins</span>
                            </div>
                        </div>
                    </div>
                    <div class="improvement-tips">
                        <h4>Tips to Improve Your Ranking</h4>
                        <div class="tips-list">
                            <div class="tip-item">
                                <i class="fas fa-play"></i>
                                <span>Join more competitions to gain experience</span>
                            </div>
                            <div class="tip-item">
                                <i class="fas fa-graduation-cap"></i>
                                <span>Complete typing lessons to improve technique</span>
                            </div>
                            <div class="tip-item">
                                <i class="fas fa-keyboard"></i>
                                <span>Practice daily to maintain consistency</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    
    
@endsection: 
var(--text-secondary){
    font-size: 1.1rem;
}

.header-stats {
    display: flex;
    gap: 2rem;
}

.stat-item {
    text-align: center;
}

.stat-number {
    display: block;
    font-size: 2rem;
    font-weight: 700;
    color: var(--text-primary);
}

.stat-label {
    font-size: 0.9rem;
    color: var(--text-secondary);
}

/* Categories Navigation */
.categories-section {
    margin-bottom: 3rem;
}

.categories-nav {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

.category-btn {
    padding: 1rem 2rem;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius);
    color: var(--text-secondary);
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-weight: 500;
    font-size: 1rem;
}

.category-btn:hover {
    background: rgba(255, 255, 255, 0.1);
    border-color: var(--accent-pink);
    color: var(--text-primary);
}

.category-btn.active {
    background: var(--gradient-button);
    border-color: var(--accent-pink);
    color: white;
    box-shadow: 0 4px 15px rgba(255, 107, 157, 0.3);
}

/* Leaderboard Sections */
.leaderboard-section {
    display: none;
    margin-bottom: 3rem;
}

.leaderboard-section.active {
    display: block;
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

.live-indicator {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    background: rgba(239, 68, 68, 0.1);
    color: var(--error);
    padding: 0.5rem 1rem;
    border-radius: var(--border-radius);
    font-size: 0.85rem;
    font-weight: 600;
    border: 1px solid rgba(239, 68, 68, 0.2);
}

.live-dot {
    width: 8px;
    height: 8px;
    background: var(--error);
    border-radius: 50%;
    animation: pulse 2s infinite;
}

.league-info, .device-info {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--text-secondary);
    font-size: 0.9rem;
}

/* Leaderboard Cards Grid */
.leaderboards-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
    gap: 2rem;
}

.leaderboard-card {
    background: var(--gradient-card);
    backdrop-filter: blur(var(--blur-amount));
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius-lg);
    padding: 2rem;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.leaderboard-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 40px rgba(139, 92, 246, 0.2);
    border-color: var(--accent-pink);
}

.leaderboard-card.global {
    background: linear-gradient(145deg, rgba(139, 92, 246, 0.05), rgba(255, 107, 157, 0.05));
    border-color: rgba(139, 92, 246, 0.3);
}

.leaderboard-card.league {
    background: linear-gradient(145deg, rgba(245, 158, 11, 0.05), rgba(255, 107, 157, 0.05));
    border-color: rgba(245, 158, 11, 0.3);
}

.leaderboard-card.device {
    background: linear-gradient(145deg, rgba(59, 130, 246, 0.05), rgba(16, 185, 129, 0.05));
    border-color: rgba(59, 130, 246, 0.3);
}

.card-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 2rem;
}

.board-info h3 {
    font-size: 1.3rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 0.5rem;
}

.board-info p {
    color: var(--text-secondary);
    font-size: 0.95rem;
    line-height: 1.4;
}

.board-badge {
    padding: 0.5rem 1rem;
    border-radius: var(--border-radius);
    font-size: 0.85rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    white-space: nowrap;
}

.board-badge.global {
    background: rgba(139, 92, 246, 0.1);
    color: var(--accent-purple);
    border: 1px solid rgba(139, 92, 246, 0.2);
}

.board-badge.league {
    background: rgba(245, 158, 11, 0.1);
    color: var(--warning);
    border: 1px solid rgba(245, 158, 11, 0.2);
}

.board-badge.mobile {
    background: rgba(245, 158, 11, 0.1);
    color: var(--warning);
    border: 1px solid rgba(245, 158, 11, 0.2);
}

.board-badge.pc {
    background: rgba(59, 130, 246, 0.1);
    color: var(--info);
    border: 1px solid rgba(59, 130, 246, 0.2);
}

/* Podium for Global Rankings */
.top-performers {
    margin-bottom: 2rem;
}

.podium {
    display: flex;
    justify-content: center;
    align-items: end;
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.podium-item {
    text-align: center;
    position: relative;
}

.podium-item.gold {
    order: 2;
}

.podium-item.silver {
    order: 1;
}

.podium-item.bronze {
    order: 3;
}

.performer-avatar {
    width: 60px;
    height: 60px;
    margin: 0 auto 0.75rem;
    position: relative;
}

.avatar-placeholder {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 700;
    font-size: 1.5rem;
}

.avatar-placeholder.gold {
    background: linear-gradient(45deg, #f59e0b, #eab308);
    box-shadow: 0 4px 15px rgba(245, 158, 11, 0.4);
}

.avatar-placeholder.silver {
    background: linear-gradient(45deg, #6b7280, #9ca3af);
    box-shadow: 0 4px 15px rgba(107, 114, 128, 0.4);
}

.avatar-placeholder.bronze {
    background: linear-gradient(45deg, #92400e, #b45309);
    box-shadow: 0 4px 15px rgba(146, 64, 14, 0.4);
}

.crown {
    position: absolute;
    top: -10px;
    left: 50%;
    transform: translateX(-50%);
    color: #f59e0b;
    font-size: 1.5rem;
    animation: bounce 2s infinite;
}

@keyframes bounce {
    0%, 20%, 50%, 80%, 100% { transform: translateX(-50%) translateY(0); }
    40% { transform: translateX(-50%) translateY(-5px); }
    60% { transform: translateX(-50%) translateY(-3px); }
}

.performer-info {
    display: flex;
    flex-direction: column;
}

.performer-name {
    color: var(--text-primary);
    font-weight: 600;
    font-size: 0.9rem;
    margin-bottom: 0.25rem;
}

.performer-score {
    color: var(--accent-pink);
    font-weight: 700;
    font-size: 0.85rem;
}

/* League Progress */
.league-progress {
    margin-bottom: 1.5rem;
}

.progress-info {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.75rem;
}

.progress-label {
    color: var(--text-secondary);
    font-size: 0.9rem;
}

.progress-value {
    color: var(--text-primary);
    font-weight: 600;
    font-size: 0.9rem;
}

.progress-bar {
    width: 100%;
    height: 8px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 4px;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    background: var(--gradient-button);
    border-radius: 4px;
    transition: width 0.5s ease;
}

/* League Stats */
.league-stats {
    display: flex;
    justify-content: space-between;
    margin-bottom: 2rem;
}

.league-stats .stat-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--text-secondary);
    font-size: 0.9rem;
}

.league-stats .stat-item i {
    color: var(--accent-pink);
}

/* Device Stats */
.device-stats {
    margin-bottom: 2rem;
}

.stat-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 1rem;
}

.device-stats .stat-item {
    text-align: center;
    flex: 1;
}

.device-stats .stat-label {
    display: block;
    color: var(--text-secondary);
    font-size: 0.85rem;
    margin-bottom: 0.25rem;
}

.device-stats .stat-value {
    display: block;
    color: var(--text-primary);
    font-weight: 700;
    font-size: 1.1rem;
}

/* Card Actions */
.card-actions {
    margin-top: auto;
}

.btn-full {
    width: 100%;
    padding: 1rem;
    font-weight: 600;
}

/* Ranking Animation */
.leaderboard-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    pointer-events: none;
    opacity: 0.1;
}

.ranking-animation {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 100px;
}

.rank-line {
    position: absolute;
    left: -100%;
    width: 100px;
    height: 2px;
    background: var(--gradient-accent);
    animation: rank 4s linear infinite;
}

.rank-line:nth-child(2) {
    top: 33%;
    animation-delay: 1.3s;
}

.rank-line:nth-child(3) {
    top: 66%;
    animation-delay: 2.6s;
}

@keyframes rank {
    0% { left: -100px; }
    100% { left: 100%; }
}

/* Performance Summary */
.performance-summary {
    margin-top: 4rem;
}

.summary-card {
    background: var(--gradient-card);
    backdrop-filter: blur(var(--blur-amount));
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius-lg);
    padding: 2.5rem;
}

.summary-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 2rem;
}

.summary-header i {
    font-size: 1.5rem;
    color: var(--accent-pink);
}

.summary-header h3 {
    font-size: 1.3rem;
    color: var(--text-primary);
    font-weight: 600;
}

.summary-content {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 3rem;
    align-items: start;
}

.performance-stats {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
}

.perf-stat {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.5rem;
    background: rgba(255, 255, 255, 0.03);
    border-radius: var(--border-radius);
    border: 1px solid rgba(255, 255, 255, 0.05);
}

.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2rem;
    flex-shrink: 0;
}
.leaderboards-container {
    padding: 2rem 0;
    min-height: calc(100vh - 80px);
}

/* Header */
.leaderboards-header {
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

.leaderboards-header::before {
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
    color: white;
    font-size: 1.2rem;
}

.stat-icon.speed { background: linear-gradient(45deg, #ff6b9d, #c084fc); }
.stat-icon.accuracy { background: linear-gradient(45deg, #00d4ff, #0ea5e9); }
.stat-icon.competitions { background: linear-gradient(45deg, #f59e0b, #eab308); }
.stat-icon.wins { background: linear-gradient(45deg, #10b981, #059669); }

.stat-info {
    display: flex;
    flex-direction: column;
}

.perf-stat .stat-number {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text-primary);
}

.perf-stat .stat-label {
    color: var(--text-secondary);
    font-size: 0.9rem;
}

.improvement-tips {
    background: rgba(255, 255, 255, 0.03);
    border-radius: var(--border-radius);
    padding: 2rem;
    border: 1px solid rgba(255, 255, 255, 0.05);
}

.improvement-tips h4 {
    color: var(--text-primary);
    font-size: 1.1rem;
    margin-bottom: 1.5rem;
    text-align: center;
}

.tips-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.tip-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: rgba(255, 255, 255, 0.03);
    border-radius: calc(var(--border-radius) - 2px);
    transition: all 0.3s ease;
}

.tip-item:hover {
    background: rgba(255, 107, 157, 0.05);
    border-left: 3px solid var(--accent-pink);
}

.tip-item i {
    color: var(--accent-pink);
    font-size: 1rem;
    width: 16px;
    flex-shrink: 0;
}

.tip-item span {
    color: var(--text-secondary);
    font-size: 0.9rem;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    background: var(--gradient-card);
    backdrop-filter: blur(var(--blur-amount));
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius-lg);
    grid-column: 1 / -1;
}

.empty-icon {
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

.empty-state h3 {
    font-size: 1.5rem;
    color: var(--text-primary);
    margin-bottom: 1rem;
}

.empty-state p {
    color: var(--text-secondary);
    max-width: 500px;
    margin: 0 auto;
}

/* Responsive */
@media (max-width: 1024px) {
    .leaderboards-grid {
        grid-template-columns: 1fr;
    }
    
    .leaderboards-header {
        flex-direction: column;
        text-align: center;
        gap: 2rem;
    }
    
    .summary-content {
        grid-template-columns: 1fr;
        gap: 2rem;
    }
    
    .performance-stats {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .section-header {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
    
    .categories-nav {
        flex-direction: column;
    }
    
    .category-btn {
        text-align: center;
    }
    
    .podium {
        flex-direction: column;
        gap: 1.5rem;
    }
    
    .podium-item {
        order: unset !important;
    }
    
    .stat-row {
        flex-direction: column;
        gap: 1rem;
    }
    
    .league-stats {
        flex-direction: column;
        gap: 1rem;
    }
    
    .header-stats {
        justify-content: center;
    }
    
    .performance-stats {
        grid-template-columns: repeat(2, 1fr);
    }
}

/* Pulse Animation */
@keyframes pulse {
    0% { opacity: 1; }
    50% { opacity: 0.5; }
    100% { opacity: 1; }
}

/* Card entrance animation */
.leaderboard-card {
    animation: slideInUp 0.6s ease forwards;
}

@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Category switching functionality
    const categoryBtns = document.querySelectorAll('.category-btn');
    const leaderboardSections = document.querySelectorAll('.leaderboard-section');
    
    categoryBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const category = this.dataset.category;
            
            // Update active button
            categoryBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            // Show corresponding section
            leaderboardSections.forEach(section => {
                section.classList.remove('active');
                if (section.id === category + '-section') {
                    section.classList.add('active');
                }
            });
        });
    });
    
    // Add stagger animation to cards
    const cards = document.querySelectorAll('.leaderboard-card');
    const observer = new IntersectionObserver(entries => {
        entries.forEach((entry, index) => {
            if (entry.isIntersecting) {
                setTimeout(() => {
                    entry.target.style.animation = slideInUp 0.6s ease forwards;
                }, index * 100);
            }
        });
    });
    
    cards.forEach(card => {
        observer.observe(card);
    });
    
    // Smooth scrolling for better UX
    const scrollToSection = (sectionId) => {
        const section = document.getElementById(sectionId + '-section');
        if (section) {
            section.scrollIntoView({ 
                behavior: 'smooth', 
                block: 'start' 
            });
        }
    };
    
    // Add click handlers for quick navigation
    categoryBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            setTimeout(() => {
                scrollToSection(this.dataset.category);
            }, 100);
        });
    });
    
    // Real-time data simulation (for demo purposes)
    function simulateRealTimeUpdates() {
        const liveIndicators = document.querySelectorAll('.live-dot');
        liveIndicators.forEach(dot => {
            // Keep the pulse animation running
            setInterval(() => {
                dot.style.animation = 'none';
                setTimeout(() => {
                    dot.style.animation = 'pulse 2s infinite';
                }, 10);
            }, 30000); // Reset animation every 30 seconds
        });
    }
    
    // Initialize real-time updates
    simulateRealTimeUpdates();
    
    // Add hover effects to performance stats
    const perfStats = document.querySelectorAll('.perf-stat');
    perfStats.forEach(stat => {
        stat.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-3px)';
            this.style.boxShadow = '0 8px 25px rgba(139, 92, 246, 0.15)';
        });
        
        stat.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = 'none';
        });
    });
    
    // Animate progress bars on page load
    setTimeout(() => {
        const progressBars = document.querySelectorAll('.progress-fill');
        progressBars.forEach(bar => {
            const width = bar.style.width;
            bar.style.width = '0%';
            setTimeout(() => {
                bar.style.width = width;
            }, 500);
        });
    }, 1000);
    
    // Add tooltip functionality for badges
    const badges = document.querySelectorAll('.board-badge');
    badges.forEach(badge => {
        badge.addEventListener('mouseenter', function() {
            const tooltip = document.createElement('div');
            tooltip.className = 'badge-tooltip';
            tooltip.textContent = this.textContent + ' Competition';
            tooltip.style.cssText = `
                position: absolute;
                background: rgba(0, 0, 0, 0.8);
                color: white;
                padding: 0.5rem 1rem;
                border-radius: 6px;
                font-size: 0.8rem;
                top: -2.5rem;
                left: 50%;
                transform: translateX(-50%);
                white-space: nowrap;
                z-index: 1000;
                pointer-events: none;
            `;
            this.style.position = 'relative';
            this.appendChild(tooltip);
        });
        
        badge.addEventListener('mouseleave', function() {
            const tooltip = this.querySelector('.badge-tooltip');
            if (tooltip) {
                tooltip.remove();
            }
        });
    });
});
</script>
