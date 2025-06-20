{{-- resources/views/leaderboards/show.blade.php --}}
@extends('layouts.app')

@section('title', $leaderboard->title . ' Leaderboard - SportTyping')

@section('content')
<div class="leaderboard-show-container">
    <div class="container">
        <!-- Leaderboard Header -->
        <div class="leaderboard-header">
            <div class="header-content">
                <div class="leaderboard-info">
                    <h1 class="leaderboard-title">
                        <i class="fas fa-trophy"></i>
                        {{ $leaderboard->title }}
                    </h1>
                    <p class="leaderboard-description">{{ $leaderboard->description }}</p>
                    <div class="leaderboard-meta">
                        <span class="meta-item">
                            <i class="fas fa-users"></i>
                            {{ $totalParticipants }} participants
                        </span>
                        <span class="meta-item">
                            <i class="fas fa-calendar"></i>
                            {{ $leaderboard->period_start->format('M j') }} - {{ $leaderboard->period_end->format('M j, Y') }}
                        </span>
                        <span class="meta-item">
                            <i class="fas fa-{{ $leaderboard->metric_type === 'wpm' ? 'tachometer-alt' : 'bullseye' }}"></i>
                            Based on {{ ucfirst($leaderboard->metric_type) }}
                        </span>
                    </div>
                </div>
                
                <!-- Current User Position -->
                @if($userPosition)
                <div class="user-position-card">
                    <div class="position-badge">
                        <div class="position-number">#{{ $userPosition->rank }}</div>
                        <div class="position-label">Your Rank</div>
                    </div>
                    <div class="position-stats">
                        <div class="stat">
                            <div class="stat-value">{{ $userPosition->score }}</div>
                            <div class="stat-label">{{ ucfirst($leaderboard->metric_type) }}</div>
                        </div>
                        <div class="stat">
                            <div class="stat-value">{{ $userPosition->total_sessions }}</div>
                            <div class="stat-label">Sessions</div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Podium Section -->
        <div class="podium-section">
            <h3 class="section-title">
                <i class="fas fa-award"></i>
                Top Performers
            </h3>
            
            <div class="podium-container">
                @if(isset($topThree[1]))
                    <!-- 2nd Place -->
                    <div class="podium-position second-place">
                        <div class="position-number">2</div>
                        <div class="participant-avatar">
                            @if($topThree[1]->user->avatar)
                                <img src="{{ asset('storage/' . $topThree[1]->user->avatar) }}" alt="{{ $topThree[1]->user->name }}">
                            @else
                                <i class="fas fa-user"></i>
                            @endif
                        </div>
                        <div class="participant-name">{{ $topThree[1]->user->name }}</div>
                        <div class="participant-score">{{ $topThree[1]->score }} {{ strtoupper($leaderboard->metric_type) }}</div>
                        <div class="podium-base silver"></div>
                    </div>
                @endif
                
                @if(isset($topThree[0]))
                    <!-- 1st Place -->
                    <div class="podium-position first-place">
                        <div class="crown-icon">
                            <i class="fas fa-crown"></i>
                        </div>
                        <div class="position-number">1</div>
                        <div class="participant-avatar champion">
                            @if($topThree[0]->user->avatar)
                                <img src="{{ asset('storage/' . $topThree[0]->user->avatar) }}" alt="{{ $topThree[0]->user->name }}">
                            @else
                                <i class="fas fa-user"></i>
                            @endif
                        </div>
                        <div class="participant-name">{{ $topThree[0]->user->name }}</div>
                        <div class="participant-score">{{ $topThree[0]->score }} {{ strtoupper($leaderboard->metric_type) }}</div>
                        <div class="podium-base gold"></div>
                    </div>
                @endif
                
                @if(isset($topThree[2]))
                    <!-- 3rd Place -->
                    <div class="podium-position third-place">
                        <div class="position-number">3</div>
                        <div class="participant-avatar">
                            @if($topThree[2]->user->avatar)
                                <img src="{{ asset('storage/' . $topThree[2]->user->avatar) }}" alt="{{ $topThree[2]->user->name }}">
                            @else
                                <i class="fas fa-user"></i>
                            @endif
                        </div>
                        <div class="participant-name">{{ $topThree[2]->user->name }}</div>
                        <div class="participant-score">{{ $topThree[2]->score }} {{ strtoupper($leaderboard->metric_type) }}</div>
                        <div class="podium-base bronze"></div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Filters and Search -->
        <div class="leaderboard-controls">
            <div class="search-section">
                <div class="search-input-group">
                    <i class="fas fa-search"></i>
                    <input type="text" id="player-search" placeholder="Search players..." class="search-input">
                </div>
            </div>
            
            <div class="filter-section">
                <div class="filter-group">
                    <label>League:</label>
                    <select id="league-filter" class="filter-select">
                        <option value="">All Leagues</option>
                        <option value="novice">Novice</option>
                        <option value="apprentice">Apprentice</option>
                        <option value="expert">Expert</option>
                        <option value="journeyman">Journeyman</option>
                        <option value="legend">Legend</option>
                        <option value="master">Master</option>
                        <option value="grandmaster">Grandmaster</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label>Device:</label>
                    <select id="device-filter" class="filter-select">
                        <option value="">All Devices</option>
                        <option value="desktop">Desktop</option>
                        <option value="mobile">Mobile</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Full Leaderboard Table -->
        <div class="leaderboard-table-section">
            <h3 class="section-title">
                <i class="fas fa-list-ol"></i>
                Complete Rankings
            </h3>
            
            <div class="leaderboard-table">
                <div class="table-header">
                    <div class="header-cell rank">Rank</div>
                    <div class="header-cell player">Player</div>
                    <div class="header-cell league">League</div>
                    <div class="header-cell score">{{ ucfirst($leaderboard->metric_type) }}</div>
                    <div class="header-cell sessions">Sessions</div>
                    <div class="header-cell trend">Trend</div>
                    <div class="header-cell device">Device</div>
                </div>
                
                <div class="table-body" id="leaderboard-entries">
                    @foreach($entries as $entry)
                        <div class="table-row {{ $entry->user_id === auth()->id() ? 'user-row' : '' }}" 
                             data-league="{{ strtolower($entry->user->league?->name ?? 'none') }}"
                             data-device="{{ $entry->primary_device ?? 'unknown' }}">
                            
                            <!-- Rank -->
                            <div class="table-cell rank">
                                <div class="rank-badge rank-{{ $entry->rank <= 3 ? $entry->rank : 'other' }}">
                                    @if($entry->rank === 1)
                                        <i class="fas fa-trophy"></i>
                                    @elseif($entry->rank === 2)
                                        <i class="fas fa-medal"></i>
                                    @elseif($entry->rank === 3)
                                        <i class="fas fa-award"></i>
                                    @else
                                        {{ $entry->rank }}
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Player -->
                            <div class="table-cell player">
                                <div class="player-info">
                                    <div class="player-avatar">
                                        @if($entry->user->avatar)
                                            <img src="{{ asset('storage/' . $entry->user->avatar) }}" alt="{{ $entry->user->name }}">
                                        @else
                                            <i class="fas fa-user"></i>
                                        @endif
                                    </div>
                                    <div class="player-details">
                                        <div class="player-name">{{ $entry->user->name }}</div>
                                        <div class="player-join-date">
                                            Joined {{ $entry->user->created_at->diffForHumans() }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- League -->
                            <div class="table-cell league">
                                @if($entry->user->league)
                                    <div class="league-badge">
                                        <img src="{{ asset('image/leagues/' . $entry->user->league->icon) }}" alt="{{ $entry->user->league->name }}">
                                        <span>{{ $entry->user->league->name }}</span>
                                    </div>
                                @else
                                    <div class="league-badge no-league">
                                        <i class="fas fa-user-plus"></i>
                                        <span>New Player</span>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Score -->
                            <div class="table-cell score">
                                <div class="score-display">
                                    <div class="score-value">{{ $entry->score }}</div>
                                    <div class="score-unit">{{ strtoupper($leaderboard->metric_type) }}</div>
                                </div>
                            </div>
                            
                            <!-- Sessions -->
                            <div class="table-cell sessions">
                                <div class="sessions-count">{{ $entry->total_sessions }}</div>
                            </div>
                            
                            <!-- Trend -->
                            <div class="table-cell trend">
                                @if(isset($entry->trend))
                                    <div class="trend-indicator {{ $entry->trend > 0 ? 'trending-up' : ($entry->trend < 0 ? 'trending-down' : 'trending-stable') }}">
                                        <i class="fas fa-{{ $entry->trend > 0 ? 'arrow-up' : ($entry->trend < 0 ? 'arrow-down' : 'minus') }}"></i>
                                        <span>{{ abs($entry->trend) }}</span>
                                    </div>
                                @else
                                    <div class="trend-indicator trending-stable">
                                        <i class="fas fa-minus"></i>
                                        <span>--</span>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Device -->
                            <div class="table-cell device">
                                <div class="device-badge">
                                    <i class="fas fa-{{ ($entry->primary_device ?? 'desktop') === 'mobile' ? 'mobile-alt' : 'desktop' }}"></i>
                                    <span>{{ ucfirst($entry->primary_device ?? 'Desktop') }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            
            <!-- Load More Button -->
            @if($entries->hasMorePages())
            <div class="load-more-section">
                <button class="load-more-btn" data-page="{{ $entries->currentPage() + 1 }}">
                    <i class="fas fa-chevron-down"></i>
                    Load More Players
                </button>
            </div>
            @endif
        </div>

        <!-- Statistics Section -->
        <div class="leaderboard-stats">
            <h3 class="section-title">
                <i class="fas fa-chart-bar"></i>
                Leaderboard Statistics
            </h3>
            
            <div class="stats-grid">
                <x-stat-card 
                    icon="fas fa-trophy"
                    title="Average Score"
                    :value="number_format($averageScore, 1)"
                    :unit="strtoupper($leaderboard->metric_type)"
                    color="primary"
                />
                
                <x-stat-card 
                    icon="fas fa-users"
                    title="Total Players"
                    :value="$totalParticipants"
                    color="success"
                />
                
                <x-stat-card 
                    icon="fas fa-chart-line"
                    title="Highest Score"
                    :value="$highestScore"
                    :unit="strtoupper($leaderboard->metric_type)"
                    color="warning"
                />
                
                <x-stat-card 
                    icon="fas fa-keyboard"
                    title="Total Sessions"
                    :value="number_format($totalSessions)"
                    color="info"
                />
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="action-section">
            <div class="action-buttons">
                <a href="{{ route('practice.index') }}" class="btn btn-primary">
                    <i class="fas fa-keyboard"></i>
                    Start Practicing
                </a>
                <a href="{{ route('competitions.index') }}" class="btn btn-outline-primary">
                    <i class="fas fa-trophy"></i>
                    Join Competition
                </a>
                <a href="{{ route('leaderboards.index') }}" class="btn btn-outline-success">
                    <i class="fas fa-list"></i>
                    All Leaderboards
                </a>
                <button onclick="shareLeaderboard()" class="btn btn-outline-info">
                    <i class="fas fa-share"></i>
                    Share
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.leaderboard-show-container {
    min-height: calc(100vh - 80px);
    background: var(--bg-secondary);
    padding: 2rem 0;
}

.leaderboard-header {
    background: var(--bg-card);
    border-radius: var(--border-radius-lg);
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: var(--shadow-md);
    position: relative;
    overflow: hidden;
}

.leaderboard-header::before {
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
    gap: 2rem;
}

.leaderboard-title {
    font-family: var(--font-display);
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.leaderboard-title i {
    color: var(--accent-secondary);
}

.leaderboard-description {
    color: var(--text-secondary);
    font-size: 1.1rem;
    margin-bottom: 1rem;
    line-height: 1.6;
}

.leaderboard-meta {
    display: flex;
    gap: 2rem;
    flex-wrap: wrap;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--text-secondary);
    font-size: 0.9rem;
}

.meta-item i {
    color: var(--accent-primary);
}

.user-position-card {
    background: var(--champion-gradient);
    color: white;
    padding: 1.5rem;
    border-radius: var(--border-radius-lg);
    display: flex;
    align-items: center;
    gap: 1.5rem;
    min-width: 250px;
    box-shadow: var(--shadow-lg);
}

.position-badge {
    text-align: center;
}

.position-number {
    font-family: var(--font-display);
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.25rem;
}

.position-label {
    font-size: 0.9rem;
    opacity: 0.9;
}

.position-stats {
    display: flex;
    gap: 1.5rem;
}

.position-stats .stat {
    text-align: center;
}

.position-stats .stat-value {
    font-family: var(--font-display);
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 0.25rem;
}

.position-stats .stat-label {
    font-size: 0.8rem;
    opacity: 0.9;
}

.podium-section {
    background: var(--bg-card);
    border-radius: var(--border-radius-lg);
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: var(--shadow-sm);
}

.section-title {
    font-family: var(--font-display);
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 2rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.podium-container {
    display: flex;
    justify-content: center;
    align-items: end;
    gap: 2rem;
    perspective: 1000px;
}

.podium-position {
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
    text-align: center;
    min-width: 150px;
}

.crown-icon {
    position: absolute;
    top: -30px;
    left: 50%;
    transform: translateX(-50%);
    font-size: 2rem;
    color: var(--accent-secondary);
    animation: crown-float 2s ease-in-out infinite;
}

@keyframes crown-float {
    0%, 100% { transform: translateX(-50%) translateY(0); }
    50% { transform: translateX(-50%) translateY(-5px); }
}

.position-number {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: var(--font-display);
    font-weight: 700;
    font-size: 1.2rem;
    color: white;
    margin-bottom: 1rem;
    z-index: 2;
    position: relative;
}

.first-place .position-number {
    background: var(--medal-gradient);
    box-shadow: 0 0 20px rgba(245, 158, 11, 0.3);
}

.second-place .position-number {
    background: linear-gradient(135deg, #e5e7eb, #9ca3af);
}

.third-place .position-number {
    background: linear-gradient(135deg, #f59e0b, #d97706);
}

.participant-avatar {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: var(--bg-secondary);
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    margin-bottom: 1rem;
    border: 3px solid var(--border-light);
    transition: all 0.3s ease;
}

.participant-avatar.champion {
    border-color: var(--accent-secondary);
    box-shadow: 0 0 20px rgba(245, 158, 11, 0.3);
    animation: champion-glow 2s ease-in-out infinite;
}

@keyframes champion-glow {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}

.participant-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.participant-avatar i {
    font-size: 2rem;
    color: var(--text-muted);
}

.participant-name {
    font-family: var(--font-display);
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 0.5rem;
    font-size: 1.1rem;
}

.participant-score {
    color: var(--text-secondary);
    font-weight: 600;
    margin-bottom: 1.5rem;
}

.podium-base {
    width: 120px;
    border-radius: var(--border-radius) var(--border-radius) 0 0;
    position: relative;
}

.podium-base.gold {
    height: 100px;
    background: var(--medal-gradient);
    box-shadow: 0 0 20px rgba(245, 158, 11, 0.3);
}

.podium-base.silver {
    height: 80px;
    background: linear-gradient(135deg, #e5e7eb, #9ca3af);
}

.podium-base.bronze {
    height: 60px;
    background: linear-gradient(135deg, #f59e0b, #d97706);
}

.leaderboard-controls {
    background: var(--bg-card);
    border-radius: var(--border-radius-lg);
    padding: 1.5rem;
    margin-bottom: 2rem;
    box-shadow: var(--shadow-sm);
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 2rem;
}

.search-section {
    flex: 1;
    max-width: 400px;
}

.search-input-group {
    position: relative;
}

.search-input-group i {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-muted);
}

.search-input {
    width: 100%;
    padding: 0.75rem 0.75rem 0.75rem 2.5rem;
    border: 2px solid var(--border-light);
    border-radius: var(--border-radius);
    background: var(--bg-secondary);
    color: var(--text-primary);
    font-size: 0.9rem;
    transition: all 0.3s ease;
}

.search-input:focus {
    outline: none;
    border-color: var(--accent-primary);
    background: var(--bg-card);
}

.filter-section {
    display: flex;
    gap: 1.5rem;
}

.filter-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.filter-group label {
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--text-secondary);
}

.filter-select {
    padding: 0.5rem 0.75rem;
    border: 2px solid var(--border-light);
    border-radius: var(--border-radius);
    background: var(--bg-secondary);
    color: var(--text-primary);
    font-size: 0.9rem;
    min-width: 120px;
    transition: all 0.3s ease;
}

.filter-select:focus {
    outline: none;
    border-color: var(--accent-primary);
    background: var(--bg-card);
}

.leaderboard-table-section {
    background: var(--bg-card);
    border-radius: var(--border-radius-lg);
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: var(--shadow-sm);
}

.leaderboard-table {
    background: var(--bg-secondary);
    border-radius: var(--border-radius);
    overflow: hidden;
    border: 1px solid var(--border-light);
}

.table-header {
    display: grid;
    grid-template-columns: 80px 1fr 150px 100px 100px 100px 120px;
    background: var(--text-primary);
    color: white;
    font-weight: 600;
    font-size: 0.9rem;
}

.header-cell {
    padding: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
}

.table-row {
    display: grid;
    grid-template-columns: 80px 1fr 150px 100px 100px 100px 120px;
    border-bottom: 1px solid var(--border-light);
    transition: all 0.3s ease;
}

.table-row:hover {
    background: var(--bg-tertiary);
}

.table-row.user-row {
    background: rgba(59, 130, 246, 0.1);
    border-left: 4px solid var(--accent-primary);
}

.table-cell {
    padding: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
}

.rank-badge {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    color: white;
}

.rank-1 { background: var(--medal-gradient); }
.rank-2 { background: linear-gradient(135deg, #e5e7eb, #9ca3af); }
.rank-3 { background: linear-gradient(135deg, #f59e0b, #d97706); }
.rank-other { 
    background: var(--text-secondary); 
    color: white;
    font-size: 0.9rem;
}

.player-info {
    display: flex;
    align-items: center;
    gap: 1rem;
    width: 100%;
}

.player-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: var(--bg-primary);
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    flex-shrink: 0;
}

.player-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.player-details {
    flex: 1;
    text-align: left;
    min-width: 0;
}

.player-name {
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 0.25rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.player-join-date {
    font-size: 0.8rem;
    color: var(--text-secondary);
}

.league-badge {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem;
    background: var(--bg-tertiary);
    border-radius: var(--border-radius);
    font-size: 0.8rem;
    font-weight: 600;
}

.league-badge img {
    width: 20px;
    height: 20px;
}

.league-badge.no-league {
    color: var(--text-muted);
}

.score-display {
    text-align: center;
}

.score-value {
    font-family: var(--font-display);
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--text-primary);
}

.score-unit {
    font-size: 0.8rem;
    color: var(--text-secondary);
}

.sessions-count {
    font-weight: 600;
    color: var(--text-primary);
}

.trend-indicator {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem;
    border-radius: var(--border-radius);
    font-size: 0.8rem;
    font-weight: 600;
}

.trending-up {
    background: rgba(16, 185, 129, 0.1);
    color: var(--accent-success);
}

.trending-down {
    background: rgba(239, 68, 68, 0.1);
    color: var(--accent-danger);
}

.trending-stable {
    background: rgba(107, 114, 128, 0.1);
    color: var(--text-secondary);
}

.device-badge {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.8rem;
    color: var(--text-secondary);
}

.load-more-section {
    text-align: center;
    margin-top: 2rem;
}

.load-more-btn {
    background: var(--bg-secondary);
    border: 2px solid var(--border-light);
    color: var(--text-primary);
    padding: 0.75rem 2rem;
    border-radius: var(--border-radius);
    cursor: pointer;
    transition: all 0.3s ease;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.load-more-btn:hover {
    background: var(--bg-tertiary);
    border-color: var(--accent-primary);
}

.leaderboard-stats {
    background: var(--bg-card);
    border-radius: var(--border-radius-lg);
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: var(--shadow-sm);
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
}

.action-section {
    text-align: center;
}

.action-buttons {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

.action-buttons .btn {
    min-width: 160px;
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
    .header-content {
        flex-direction: column;
        gap: 2rem;
        text-align: center;
    }
    
    .leaderboard-meta {
        justify-content: center;
    }
    
    .table-header,
    .table-row {
        grid-template-columns: 60px 1fr 120px 80px 80px 80px;
        font-size: 0.8rem;
    }
    
    .table-cell:nth-child(7) {
        display: none;
    }
    
    .podium-container {
        gap: 1rem;
    }
    
    .podium-position {
        min-width: 120px;
    }
}

@media (max-width: 768px) {
    .leaderboard-show-container {
        padding: 1rem 0;
    }
    
    .leaderboard-header,
    .podium-section,
    .leaderboard-controls,
    .leaderboard-table-section,
    .leaderboard-stats {
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
    
    .leaderboard-title {
        font-size: 1.8rem;
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .leaderboard-controls {
        flex-direction: column;
        gap: 1.5rem;
    }
    
    .filter-section {
        flex-direction: column;
        gap: 1rem;
        width: 100%;
    }
    
    .table-header,
    .table-row {
        grid-template-columns: 50px 1fr 80px 70px;
        font-size: 0.75rem;
    }
    
    .table-cell:nth-child(3),
    .table-cell:nth-child(5),
    .table-cell:nth-child(6),
    .table-cell:nth-child(7) {
        display: none;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .action-buttons {
        flex-direction: column;
        align-items: center;
    }
    
    .action-buttons .btn {
        width: 100%;
        max-width: 300px;
    }
    
    .podium-container {
        flex-direction: column;
        align-items: center;
        gap: 2rem;
    }
    
    .podium-position {
        flex-direction: row;
        min-width: 100%;
        text-align: left;
        padding: 1rem;
        background: var(--bg-secondary);
        border-radius: var(--border-radius);
    }
    
    .podium-base {
        display: none;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Search functionality
    const searchInput = document.getElementById('player-search');
    const leagueFilter = document.getElementById('league-filter');
    const deviceFilter = document.getElementById('device-filter');
    const tableRows = document.querySelectorAll('.table-row');
    
    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedLeague = leagueFilter.value.toLowerCase();
        const selectedDevice = deviceFilter.value.toLowerCase();
        
        tableRows.forEach(row => {
            const playerName = row.querySelector('.player-name').textContent.toLowerCase();
            const league = row.dataset.league;
            const device = row.dataset.device;
            
            const matchesSearch = playerName.includes(searchTerm);
            const matchesLeague = !selectedLeague || league === selectedLeague;
            const matchesDevice = !selectedDevice || device === selectedDevice;
            
            if (matchesSearch && matchesLeague && matchesDevice) {
                row.style.display = 'grid';
            } else {
                row.style.display = 'none';
            }
        });
    }
    
    searchInput.addEventListener('input', filterTable);
    leagueFilter.addEventListener('change', filterTable);
    deviceFilter.addEventListener('change', filterTable);
    
    // Load more functionality
    const loadMoreBtn = document.querySelector('.load-more-btn');
    if (loadMoreBtn) {
        loadMoreBtn.addEventListener('click', async function() {
            const page = this.dataset.page;
            
            try {
                this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';
                this.disabled = true;
                
                const response = await fetch(`{{ request()->url() }}?page=${page}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                if (response.ok) {
                    const html = await response.text();
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newRows = doc.querySelectorAll('.table-row');
                    
                    const tableBody = document.getElementById('leaderboard-entries');
                    newRows.forEach(row => {
                        tableBody.appendChild(row);
                    });
                    
                    // Update page number or remove button if no more pages
                    const nextPage = parseInt(page) + 1;
                    const hasMore = doc.querySelector('.load-more-btn');
                    
                    if (hasMore) {
                        this.dataset.page = nextPage;
                        this.innerHTML = '<i class="fas fa-chevron-down"></i> Load More Players';
                        this.disabled = false;
                    } else {
                        this.remove();
                    }
                }
            } catch (error) {
                console.error('Failed to load more entries:', error);
                this.innerHTML = '<i class="fas fa-chevron-down"></i> Load More Players';
                this.disabled = false;
            }
        });
    }
});

function shareLeaderboard() {
    const title = document.querySelector('.leaderboard-title').textContent.trim();
    const url = window.location.href;
    const text = `Check out the ${title} on SportTyping! 🏆\n\nSee how I rank against other players and join the competition!\n\n${url}`;
    
    if (navigator.share) {
        navigator.share({
            title: title,
            text: text,
            url: url
        });
    } else {
        navigator.clipboard.writeText(text).then(() => {
            alert('Leaderboard link copied to clipboard!');
        });
    }
}
</script>
@endsection