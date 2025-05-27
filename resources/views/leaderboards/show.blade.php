@extends('layouts.app')

@section('content')
<div class="leaderboard-detail-container">
    <div class="container">
        <!-- Back Navigation -->
        <div class="back-navigation">
            <a href="{{ route('leaderboards.index') }}" class="back-btn">
                <i class="fas fa-arrow-left"></i>
                Back to Leaderboards
            </a>
        </div>

        <!-- Leaderboard Header -->
        <div class="leaderboard-header">
            <div class="header-content">
                <div class="board-title">
                    <h1>{{ $leaderboard->name }}</h1>
                    <div class="board-badges">
                        <span class="board-type {{ $leaderboard->type }}">
                            @if($leaderboard->type == 'global')
                                <i class="fas fa-globe"></i>
                                Global Rankings
                            @elseif($leaderboard->type == 'league')
                                <i class="fas fa-trophy"></i>
                                {{ $leagueInfo->name ?? 'League' }} Rankings
                            @elseif($leaderboard->type == 'device_type')
                                <i class="fas fa-{{ $deviceInfo == 'mobile' ? 'mobile-alt' : 'desktop' }}"></i>
                                {{ ucfirst($deviceInfo ?? 'Device') }} Rankings
                            @else
                                <i class="fas fa-chart-line"></i>
                                Rankings
                            @endif
                        </span>
                        @if($leaderboard->device_type && $leaderboard->device_type != 'both')
                            <span class="device-badge {{ $leaderboard->device_type }}">
                                <i class="fas fa-{{ $leaderboard->device_type == 'mobile' ? 'mobile-alt' : 'desktop' }}"></i>
                                {{ ucfirst($leaderboard->device_type) }} Only
                            </span>
                        @endif
                    </div>
                </div>
                @if($leagueInfo)
                    <p class="board-description">{{ $leagueInfo->description }}</p>
                @elseif($deviceInfo)
                    <p class="board-description">Fair competition for {{ $deviceInfo }} users worldwide</p>
                @else
                    <p class="board-description">Global rankings across all skill levels and devices</p>
                @endif
            </div>
            
            <div class="live-status">
                <div class="live-indicator">
                    <span class="live-dot"></span>
                    LIVE RANKINGS
                </div>
                <div class="last-updated">
                    Last updated: {{ now()->format('M j, g:i A') }}
                </div>
            </div>
        </div>

        <!-- Podium Section (Top 3) -->
        @if($entries->count() >= 3)
        <div class="podium-section">
            <div class="section-title">
                <h2><i class="fas fa-crown"></i> Champions</h2>
            </div>
            
            <div class="podium-container">
                @php
                    $topThree = $entries->take(3);
                @endphp
                
                <!-- Silver (2nd Place) -->
                @if($topThree->count() >= 2)
                <div class="podium-position silver">
                    <div class="position-number">2</div>
                    <div class="podium-platform silver-platform">
                        <div class="platform-height"></div>
                    </div>
                    <div class="champion-info">
                        <div class="champion-avatar">
                            @if($topThree[1]->user->profile->avatar)
                                <img src="{{ Storage::url($topThree[1]->user->profile->avatar) }}" alt="{{ $topThree[1]->user->username }}">
                            @else
                                <div class="avatar-placeholder silver">
                                    {{ substr($topThree[1]->user->username, 0, 1) }}
                                </div>
                            @endif
                            <div class="medal silver-medal">
                                <i class="fas fa-medal"></i>
                            </div>
                        </div>
                        <div class="champion-details">
                            <span class="champion-name">{{ $topThree[1]->user->username }}</span>
                            <span class="champion-score">{{ number_format($topThree[1]->score, 1) }} 
                                @if($leaderboard->type == 'global' || $leaderboard->type == 'device_type')
                                    WPM
                                @else
                                    XP
                                @endif
                            </span>
                            @if($topThree[1]->user->profile->league)
                                <span class="champion-league">{{ $topThree[1]->user->profile->league->name }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                <!-- Gold (1st Place) -->
                <div class="podium-position gold">
                    <div class="position-number">1</div>
                    <div class="crown-animation">
                        <i class="fas fa-crown"></i>
                    </div>
                    <div class="podium-platform gold-platform">
                        <div class="platform-height"></div>
                    </div>
                    <div class="champion-info">
                        <div class="champion-avatar">
                            @if($topThree[0]->user->profile->avatar)
                                <img src="{{ Storage::url($topThree[0]->user->profile->avatar) }}" alt="{{ $topThree[0]->user->username }}">
                            @else
                                <div class="avatar-placeholder gold">
                                    {{ substr($topThree[0]->user->username, 0, 1) }}
                                </div>
                            @endif
                            <div class="medal gold-medal">
                                <i class="fas fa-medal"></i>
                            </div>
                        </div>
                        <div class="champion-details">
                            <span class="champion-name">{{ $topThree[0]->user->username }}</span>
                            <span class="champion-score">{{ number_format($topThree[0]->score, 1) }} 
                                @if($leaderboard->type == 'global' || $leaderboard->type == 'device_type')
                                    WPM
                                @else
                                    XP
                                @endif
                            </span>
                            @if($topThree[0]->user->profile->league)
                                <span class="champion-league">{{ $topThree[0]->user->profile->league->name }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Bronze (3rd Place) -->
                @if($topThree->count() >= 3)
                <div class="podium-position bronze">
                    <div class="position-number">3</div>
                    <div class="podium-platform bronze-platform">
                        <div class="platform-height"></div>
                    </div>
                    <div class="champion-info">
                        <div class="champion-avatar">
                            @if($topThree[2]->user->profile->avatar)
                                <img src="{{ Storage::url($topThree[2]->user->profile->avatar) }}" alt="{{ $topThree[2]->user->username }}">
                            @else
                                <div class="avatar-placeholder bronze">
                                    {{ substr($topThree[2]->user->username, 0, 1) }}
                                </div>
                            @endif
                            <div class="medal bronze-medal">
                                <i class="fas fa-medal"></i>
                            </div>
                        </div>
                        <div class="champion-details">
                            <span class="champion-name">{{ $topThree[2]->user->username }}</span>
                            <span class="champion-score">{{ number_format($topThree[2]->score, 1) }} 
                                @if($leaderboard->type == 'global' || $leaderboard->type == 'device_type')
                                    WPM
                                @else
                                    XP
                                @endif
                            </span>
                            @if($topThree[2]->user->profile->league)
                                <span class="champion-league">{{ $topThree[2]->user->profile->league->name }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- User Position Highlight (if not in top 3) -->
        @if($userEntry && $userEntry->rank > 3)
        <div class="user-position-highlight">
            <div class="highlight-header">
                <h3><i class="fas fa-user"></i> Your Position</h3>
            </div>
            <div class="user-rank-card">
                <div class="rank-badge">
                    #{{ $userEntry->rank }}
                </div>
                <div class="user-info">
                    <div class="user-avatar">
                        @if($userEntry->user->profile->avatar)
                            <img src="{{ Storage::url($userEntry->user->profile->avatar) }}" alt="{{ $userEntry->user->username }}">
                        @else
                            <div class="avatar-placeholder user">
                                {{ substr($userEntry->user->username, 0, 1) }}
                            </div>
                        @endif
                    </div>
                    <div class="user-details">
                        <span class="user-name">{{ $userEntry->user->username }} (You)</span>
                        <span class="user-score">{{ number_format($userEntry->score, 1) }} 
                            @if($leaderboard->type == 'global' || $leaderboard->type == 'device_type')
                                WPM
                            @else
                                XP
                            @endif
                        </span>
                        @if($userEntry->user->profile->league)
                            <span class="user-league">{{ $userEntry->user->profile->league->name }}</span>
                        @endif
                    </div>
                </div>
                <div class="improvement-tips">
                    <span class="tip-label">To improve:</span>
                    @if($userEntry->rank > 1)
                        @php
                            $nextRank = $entries->where('rank', $userEntry->rank - 1)->first();
                            $scoreGap = $nextRank ? $nextRank->score - $userEntry->score : 0;
                        @endphp
                        @if($scoreGap > 0)
                            <span class="tip-text">+{{ number_format($scoreGap, 1) }} to rank #{{ $userEntry->rank - 1 }}</span>
                        @endif
                    @endif
                </div>
            </div>
        </div>
        @endif

        <!-- Full Rankings Table -->
        <div class="rankings-section">
            <div class="section-header">
                <h2><i class="fas fa-list"></i> Full Rankings</h2>
                <div class="rankings-stats">
                    <span class="total-participants">{{ $entries->total() }} participants</span>
                </div>
            </div>

            <div class="rankings-table">
                <div class="table-header">
                    <div class="rank-col">Rank</div>
                    <div class="player-col">Player</div>
                    <div class="league-col">League</div>
                    <div class="score-col">
                        @if($leaderboard->type == 'global' || $leaderboard->type == 'device_type')
                            Speed (WPM)
                        @else
                            Experience
                        @endif
                    </div>
                    <div class="trend-col">Trend</div>
                </div>

                <div class="table-body">
                    @forelse($entries as $entry)
                    <div class="table-row {{ $userEntry && $userEntry->id == $entry->id ? 'user-row' : '' }} {{ $entry->rank <= 3 ? 'top-three' : '' }}">
                        <div class="rank-col">
                            <div class="rank-display">
                                @if($entry->rank == 1)
                                    <div class="rank-badge gold">
                                        <i class="fas fa-crown"></i>
                                        <span>{{ $entry->rank }}</span>
                                    </div>
                                @elseif($entry->rank == 2)
                                    <div class="rank-badge silver">
                                        <i class="fas fa-medal"></i>
                                        <span>{{ $entry->rank }}</span>
                                    </div>
                                @elseif($entry->rank == 3)
                                    <div class="rank-badge bronze">
                                        <i class="fas fa-medal"></i>
                                        <span>{{ $entry->rank }}</span>
                                    </div>
                                @else
                                    <div class="rank-number">#{{ $entry->rank }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="player-col">
                            <div class="player-info">
                                <div class="player-avatar">
                                    @if($entry->user->profile->avatar)
                                        <img src="{{ Storage::url($entry->user->profile->avatar) }}" alt="{{ $entry->user->username }}">
                                    @else
                                        <div class="avatar-placeholder">
                                            {{ substr($entry->user->username, 0, 1) }}
                                        </div>
                                    @endif
                                    @if($userEntry && $userEntry->id == $entry->id)
                                        <div class="you-badge">YOU</div>
                                    @endif
                                </div>
                                <div class="player-details">
                                    <span class="player-name">{{ $entry->user->username }}</span>
                                    <span class="player-stats">
                                        @if($entry->user->profile->typing_speed_avg)
                                            {{ number_format($entry->user->profile->typing_speed_avg, 1) }} WPM avg
                                        @endif
                                        @if($entry->user->profile->typing_accuracy_avg)
                                            • {{ number_format($entry->user->profile->typing_accuracy_avg, 1) }}% acc
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="league-col">
                            @if($entry->user->profile->league)
                                <div class="league-badge {{ strtolower($entry->user->profile->league->name) }}">
                                    <i class="fas fa-trophy"></i>
                                    {{ $entry->user->profile->league->name }}
                                </div>
                            @else
                                <span class="no-league">Unranked</span>
                            @endif
                        </div>

                        <div class="score-col">
                            <div class="score-display">
                                <span class="score-value">{{ number_format($entry->score, 1) }}</span>
                                <span class="score-unit">
                                    @if($leaderboard->type == 'global' || $leaderboard->type == 'device_type')
                                        WPM
                                    @else
                                        XP
                                    @endif
                                </span>
                            </div>
                        </div>

                        <div class="trend-col">
                            @php
                                $trend = rand(-5, 10); // Simulated trend data
                            @endphp
                            <div class="trend-indicator {{ $trend > 0 ? 'positive' : ($trend < 0 ? 'negative' : 'neutral') }}">
                                @if($trend > 0)
                                    <i class="fas fa-arrow-up"></i>
                                    <span>+{{ $trend }}</span>
                                @elseif($trend < 0)
                                    <i class="fas fa-arrow-down"></i>
                                    <span>{{ $trend }}</span>
                                @else
                                    <i class="fas fa-minus"></i>
                                    <span>0</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="empty-rankings">
                        <div class="empty-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <h3>No Rankings Yet</h3>
                        <p>Be the first to compete and claim the top spot!</p>
                        <a href="{{ route('competitions.index') }}" class="btn btn-primary">
                            <i class="fas fa-play"></i>
                            Join Competition
                        </a>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Pagination -->
            @if($entries->hasPages())
            <div class="pagination-wrapper">
                {{ $entries->links() }}
            </div>
            @endif
        </div>

        <!-- Competition Info -->
        <div class="competition-info">
            <div class="info-card">
                <div class="info-header">
                    <i class="fas fa-info-circle"></i>
                    <h3>How Rankings Work</h3>
                </div>
                <div class="info-content">
                    <div class="info-grid">
                        <div class="info-item">
                            <i class="fas fa-calculator"></i>
                            <div>
                                <h4>Calculation Method</h4>
                                <p>
                                    @if($leaderboard->type == 'global' || $leaderboard->type == 'device_type')
                                        Rankings based on average typing speed (WPM) from competition results
                                    @else
                                        Rankings based on total experience points earned in competitions and lessons
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-sync-alt"></i>
                            <div>
                                <h4>Update Frequency</h4>
                                <p>Rankings are updated in real-time after each competition and practice session</p>
                            </div>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-trophy"></i>
                            <div>
                                <h4>Fair Play</h4>
                                <p>All results are verified and monitored to ensure fair competition for everyone</p>
                            </div>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-chart-line"></i>
                            <div>
                                <h4>Improvement Tips</h4>
                                <p>Practice daily, maintain high accuracy, and participate in competitions to climb the rankings</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/*} kode error 
     gap: 0.75rem;
}*/

.podium-container {
    display: flex;
    justify-content: center;
    align-items: end;
    gap: 3rem;
    perspective: 1000px;
}

.podium-position {
    text-align: center;
    position: relative;
    transform-style: preserve-3d;
    transition: transform 0.5s ease;
}

.podium-position:hover {
    transform: translateY(-10px) rotateY(5deg);
}

.podium-position.gold {
    order: 2;
    z-index: 3;
}

.podium-position.silver {
    order: 1;
    z-index: 2;
}

.podium-position.bronze {
    order: 3;
    z-index: 1;
}

.position-number {
    position: absolute;
    top: -30px;
    left: 50%;
    transform: translateX(-50%);
    width: 40px;
    height: 40px;
    background: var(--gradient-button);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 700;
    font-size: 1.2rem;
    z-index: 10;
    box-shadow: 0 4px 15px rgba(255, 107, 157, 0.3);
}

.crown-animation {
    position: absolute;
    top: -50px;
    left: 50%;
    transform: translateX(-50%);
    color: #f59e0b;
    font-size: 2rem;
    z-index: 11;
    animation: bounce 2s infinite;
}

.podium-platform {
    width: 120px;
    background: var(--gradient-card);
    backdrop-filter: blur(var(--blur-amount));
    border: 2px solid;
    border-radius: var(--border-radius) var(--border-radius) 0 0;
    position: relative;
    margin-bottom: 1rem;
}

.gold-platform {
    border-color: #f59e0b;
    box-shadow: 0 0 20px rgba(245, 158, 11, 0.3);
}

.silver-platform {
    border-color: #6b7280;
    box-shadow: 0 0 20px rgba(107, 114, 128, 0.3);
}

.bronze-platform {
    border-color: #92400e;
    box-shadow: 0 0 20px rgba(146, 64, 14, 0.3);
}

.platform-height {
    height: 100px;
}

.gold-platform .platform-height {
    height: 120px;
}

.silver-platform .platform-height {
    height: 90px;
}

.bronze-platform .platform-height {
    height: 70px;
}

.champion-info {
    position: relative;
    z-index: 5;
}

.champion-avatar {
    width: 80px;
    height: 80px;
    margin: 0 auto 1rem;
    position: relative;
}

.champion-avatar img {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid;
}

.gold .champion-avatar img { border-color: #f59e0b; }
.silver .champion-avatar img { border-color: #6b7280; }
.bronze .champion-avatar img { border-color: #92400e; }

.avatar-placeholder {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 700;
    font-size: 1.8rem;
    border: 3px solid;
}

.avatar-placeholder.gold {
    background: linear-gradient(45deg, #f59e0b, #eab308);
    border-color: #f59e0b;
}

.avatar-placeholder.silver {
    background: linear-gradient(45deg, #6b7280, #9ca3af);
    border-color: #6b7280;
}

.avatar-placeholder.bronze {
    background: linear-gradient(45deg, #92400e, #b45309);
    border-color: #92400e;
}

.medal {
    position: absolute;
    bottom: -5px;
    right: -5px;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.9rem;
    border: 2px solid white;
}

.gold-medal {
    background: linear-gradient(45deg, #f59e0b, #eab308);
    color: white;
}

.silver-medal {
    background: linear-gradient(45deg, #6b7280, #9ca3af);
    color: white;
}

.bronze-medal {
    background: linear-gradient(45deg, #92400e, #b45309);
    color: white;
}

.champion-details {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.champion-name {
    color: var(--text-primary);
    font-weight: 700;
    font-size: 1rem;
}

.champion-score {
    color: var(--accent-pink);
    font-weight: 700;
    font-size: 1.1rem;
}

.champion-league {
    color: var(--text-secondary);
    font-size: 0.8rem;
}

/* User Position Highlight */
.user-position-highlight {
    margin-bottom: 3rem;
}

.highlight-header {
    text-align: center;
    margin-bottom: 2rem;
}

.highlight-header h3 {
    font-size: 1.3rem;
    color: var(--text-primary);
    display: flex;
    align-items: center;
    justify-content: center;
}
.leaderboard-detail-container {
    padding: 2rem 0;
    min-height: calc(100vh - 80px);
}

/* Back Navigation */
.back-navigation {
    margin-bottom: 2rem;
}

.back-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    color: var(--text-secondary);
    text-decoration: none;
    font-weight: 500;
    padding: 0.75rem 1.5rem;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius);
    transition: all 0.3s ease;
}

.back-btn:hover {
    color: var(--accent-pink);
    background: rgba(255, 107, 157, 0.1);
    border-color: var(--accent-pink);
    transform: translateX(-3px);
}

/* Header */
.leaderboard-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 3rem;
    padding: 2.5rem;
    background: var(--gradient-card);
    backdrop-filter: blur(var(--blur-amount));
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius-lg);
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
    background: var(--gradient-accent);
}

.board-title h1 {
    font-size: 2.2rem;
    font-weight: 700;
    background: var(--gradient-accent);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: 1rem;
}

.board-badges {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.board-type {
    padding: 0.5rem 1rem;
    border-radius: var(--border-radius);
    font-size: 0.9rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.board-type.global {
    background: rgba(139, 92, 246, 0.1);
    color: var(--accent-purple);
    border: 1px solid rgba(139, 92, 246, 0.2);
}

.board-type.league {
    background: rgba(245, 158, 11, 0.1);
    color: var(--warning);
    border: 1px solid rgba(245, 158, 11, 0.2);
}

.board-type.device_type {
    background: rgba(59, 130, 246, 0.1);
    color: var(--info);
    border: 1px solid rgba(59, 130, 246, 0.2);
}

.device-badge {
    padding: 0.5rem 1rem;
    border-radius: var(--border-radius);
    font-size: 0.85rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.device-badge.mobile {
    background: rgba(245, 158, 11, 0.1);
    color: var(--warning);
    border: 1px solid rgba(245, 158, 11, 0.2);
}

.device-badge.pc {
    background: rgba(59, 130, 246, 0.1);
    color: var(--info);
    border: 1px solid rgba(59, 130, 246, 0.2);
}

.board-description {
    color: var(--text-secondary);
    font-size: 1rem;
    margin-top: 0.5rem;
    max-width: 600px;
}

.live-status {
    text-align: right;
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
    margin-bottom: 0.5rem;
}

.live-dot {
    width: 8px;
    height: 8px;
    background: var(--error);
    border-radius: 50%;
    animation: pulse 2s infinite;
}

.last-updated {
    color: var(--text-muted);
    font-size: 0.8rem;
}

/* Podium Section */
.podium-section {
    margin-bottom: 4rem;
}

.section-title {
    text-align: center;
    margin-bottom: 3rem;
}

.section-title h2 {
    font-size: 1.8rem;
    font-weight: 600;
    color: var(--text-primary);
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.user-rank-card {
    background: var(--gradient-card);
    backdrop-filter: blur(var(--blur-amount));
    border: 2px solid var(--accent-pink);
    border-radius: var(--border-radius-lg);
    padding: 2rem;
    display: flex;
    align-items: center;
    gap: 2rem;
    position: relative;
    overflow: hidden;
}

.user-rank-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: var(--gradient-accent);
}

.rank-badge {
    background: var(--gradient-button);
    color: white;
    padding: 1rem 1.5rem;
    border-radius: var(--border-radius);
    font-size: 2rem;
    font-weight: 700;
    text-align: center;
    min-width: 100px;
    box-shadow: 0 4px 15px rgba(255, 107, 157, 0.3);
}

.user-info {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    flex: 1;
}

.user-avatar {
    width: 60px;
    height: 60px;
    position: relative;
}

.user-avatar img {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid var(--accent-pink);
}

.avatar-placeholder.user {
    background: var(--gradient-button);
    border: 2px solid var(--accent-pink);
}

.user-details {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.user-name {
    color: var(--text-primary);
    font-weight: 700;
    font-size: 1.2rem;
}

.user-score {
    color: var(--accent-pink);
    font-weight: 700;
    font-size: 1.1rem;
}

.user-league {
    color: var(--text-secondary);
    font-size: 0.9rem;
}

.improvement-tips {
    text-align: right;
}

.tip-label {
    display: block;
    color: var(--text-secondary);
    font-size: 0.8rem;
    margin-bottom: 0.25rem;
}

.tip-text {
    color: var(--accent-cyan);
    font-weight: 600;
    font-size: 0.9rem;
}

/* Rankings Section */
.rankings-section {
    margin-bottom: 4rem;
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

.rankings-stats {
    color: var(--text-secondary);
    font-size: 0.9rem;
}

.total-participants {
    background: rgba(255, 255, 255, 0.05);
    padding: 0.5rem 1rem;
    border-radius: var(--border-radius);
    border: 1px solid rgba(255, 255, 255, 0.1);
}

/* Rankings Table */
.rankings-table {
    background: var(--gradient-card);
    backdrop-filter: blur(var(--blur-amount));
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius-lg);
    overflow: hidden;
}

.table-header {
    display: grid;
    grid-template-columns: 80px 1fr 150px 120px 100px;
    gap: 1rem;
    padding: 1.5rem 2rem;
    background: rgba(255, 255, 255, 0.05);
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    font-weight: 600;
    color: var(--text-primary);
    font-size: 0.9rem;
}

.table-body {
    max-height: 600px;
    overflow-y: auto;
}

.table-row {
    display: grid;
    grid-template-columns: 80px 1fr 150px 120px 100px;
    gap: 1rem;
    padding: 1.5rem 2rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    transition: all 0.3s ease;
    align-items: center;
}

.table-row:hover {
    background: rgba(255, 255, 255, 0.03);
}

.table-row.user-row {
    background: rgba(255, 107, 157, 0.05);
    border-color: rgba(255, 107, 157, 0.2);
}

.table-row.top-three {
    background: rgba(245, 158, 11, 0.03);
}

.rank-display {
    display: flex;
    align-items: center;
    justify-content: center;
}

.rank-badge {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: var(--border-radius);
    font-weight: 700;
    font-size: 0.9rem;
}

.rank-badge.gold {
    background: linear-gradient(45deg, #f59e0b, #eab308);
    color: white;
}

.rank-badge.silver {
    background: linear-gradient(45deg, #6b7280, #9ca3af);
    color: white;
}

.rank-badge.bronze {
    background: linear-gradient(45deg, #92400e, #b45309);
    color: white;
}

.rank-number {
    font-size: 1.2rem;
    font-weight: 700;
    color: var(--text-primary);
}

.player-info {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.player-avatar {
    width: 40px;
    height: 40px;
    position: relative;
}

.player-avatar img {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    object-fit: cover;
}

.player-avatar .avatar-placeholder {
    width: 100%;
    height: 100%;
    background: var(--gradient-button);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    font-size: 1rem;
}

.you-badge {
    position: absolute;
    bottom: -5px;
    right: -5px;
    background: var(--accent-pink);
    color: white;
    font-size: 0.6rem;
    padding: 0.2rem 0.4rem;
    border-radius: 4px;
    font-weight: 700;
}

.player-details {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.player-name {
    color: var(--text-primary);
    font-weight: 600;
    font-size: 1rem;
}

.player-stats {
    color: var(--text-secondary);
    font-size: 0.8rem;
}

.league-badge {
    padding: 0.25rem 0.75rem;
    border-radius: var(--border-radius);
    font-size: 0.8rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    white-space: nowrap;
}

.league-badge.novice {
    background: rgba(107, 114, 128, 0.1);
    color: #6b7280;
    border: 1px solid rgba(107, 114, 128, 0.2);
}

.league-badge.apprentice {
    background: rgba(34, 197, 94, 0.1);
    color: #22c55e;
    border: 1px solid rgba(34, 197, 94, 0.2);
}

.league-badge.journeyman {
    background: rgba(59, 130, 246, 0.1);
    color: #3b82f6;
    border: 1px solid rgba(59, 130, 246, 0.2);
}

.league-badge.expert {
    background: rgba(139, 92, 246, 0.1);
    color: #8b5cf6;
    border: 1px solid rgba(139, 92, 246, 0.2);
}

.league-badge.master {
    background: rgba(245, 158, 11, 0.1);
    color: #f59e0b;
    border: 1px solid rgba(245, 158, 11, 0.2);
}

.league-badge.grandmaster {
    background: rgba(239, 68, 68, 0.1);
    color: #ef4444;
    border: 1px solid rgba(239, 68, 68, 0.2);
}

.league-badge.legend {
    background: var(--gradient-button);
    color: white;
    border: 1px solid var(--accent-pink);
}

.no-league {
    color: var(--text-muted);
    font-size: 0.8rem;
    font-style: italic;
}

.score-display {
    text-align: center;
}

.score-value {
    display: block;
    color: var(--text-primary);
    font-weight: 700;
    font-size: 1.1rem;
}

.score-unit {
    color: var(--text-secondary);
    font-size: 0.8rem;
}

.trend-indicator {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.25rem;
    font-size: 0.8rem;
    font-weight: 600;
}

.trend-indicator.positive {
    color: var(--success);
}

.trend-indicator.negative {
    color: var(--error);
}

.trend-indicator.neutral {
    color: var(--text-muted);
}

/* Empty Rankings */
.empty-rankings {
    text-align: center;
    padding: 4rem 2rem;
    grid-column: 1 / -1;
}

.empty-icon {
    width: 80px;
    height: 80px;
    background: var(--gradient-button);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
    font-size: 2rem;
    color: white;
}

.empty-rankings h3 {
    color: var(--text-primary);
    font-size: 1.3rem;
    margin-bottom: 1rem;
}

.empty-rankings p {
    color: var(--text-secondary);
    margin-bottom: 2rem;
}

/* Pagination */
.pagination-wrapper {
    margin-top: 2rem;
    display: flex;
    justify-content: center;
}

/* Competition Info */
.competition-info {
    margin-top: 4rem;
}

.info-card {
    background: var(--gradient-card);
    backdrop-filter: blur(var(--blur-amount));
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius-lg);
    padding: 2.5rem;
}

.info-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 2rem;
    text-align: center;
    justify-content: center;
}

.info-header i {
    font-size: 1.5rem;
    color: var(--accent-pink);
}

.info-header h3 {
    font-size: 1.3rem;
    color: var(--text-primary);
    font-weight: 600;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
}

.info-item {
    display: flex;
    gap: 1rem;
    padding: 1.5rem;
    background: rgba(255, 255, 255, 0.03);
    border-radius: var(--border-radius);
    border: 1px solid rgba(255, 255, 255, 0.05);
    transition: all 0.3s ease;
}

.info-item:hover {
    background: rgba(255, 255, 255, 0.05);
    border-color: var(--accent-pink);
    transform: translateY(-2px);
}

.info-item i {
    color: var(--accent-pink);
    font-size: 1.2rem;
    margin-top: 0.25rem;
    flex-shrink: 0;
}

.info-item h4 {
    color: var(--text-primary);
    font-size: 1rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.info-item p {
    color: var(--text-secondary);
    font-size: 0.9rem;
    line-height: 1.4;
}

/* Responsive Design */
@media (max-width: 1024px) {
    .leaderboard-header {
        flex-direction: column;
        gap: 2rem;
        text-align: center;
    }
    
    .podium-container {
        gap: 2rem;
    }
    
    .user-rank-card {
        flex-direction: column;
        text-align: center;
        gap: 1.5rem;
    }
    
    .improvement-tips {
        text-align: center;
    }
    
    .table-header,
    .table-row {
        grid-template-columns: 60px 1fr 100px 80px;
        gap: 0.5rem;
        padding: 1rem;
    }
    
    .league-col {
        display: none;
    }
    
    .info-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .section-header {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
    
    .podium-container {
        flex-direction: column;
        gap: 2rem;
    }
    
    .podium-position {
        order: unset !important;
    }
    
    .board-badges {
        justify-content: center;
    }
    
    .table-header,
    .table-row {
        grid-template-columns: 50px 1fr 70px;
        font-size: 0.8rem;
    }
    
    .league-col,
    .trend-col {
        display: none;
    }
    
    .player-info {
        gap: 0.75rem;
    }
    
    .player-avatar {
        width: 35px;
        height: 35px;
    }
    
    .info-item {
        flex-direction: column;
        text-align: center;
    }
}

/* Animations */
@keyframes pulse {
    0% { opacity: 1; }
    50% { opacity: 0.5; }
    100% { opacity: 1; }
}

@keyframes bounce {
    0%, 20%, 50%, 80%, 100% { transform: translateX(-50%) translateY(0); }
    40% { transform: translateX(-50%) translateY(-8px); }
    60% { transform: translateX(-50%) translateY(-4px); }
}

/* Smooth scrolling for table */
.table-body {
    scrollbar-width: thin;
    scrollbar-color: var(--accent-pink) transparent;
}

.table-body::-webkit-scrollbar {
    width: 6px;
}

.table-body::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.05);
    border-radius: 3px;
}

.table-body::-webkit-scrollbar-thumb {
    background: var(--accent-pink);
    border-radius: 3px;
}

.table-body::-webkit-scrollbar-thumb:hover {
    background: var(--accent-cyan);
}

/* Loading animations */
.table-row {
    animation: slideInUp 0.6s ease forwards;
}

@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add stagger animation to table rows
    const tableRows = document.querySelectorAll('.table-row');
    const observer = new IntersectionObserver(entries => {
        entries.forEach((entry, index) => {
            if (entry.isIntersecting) {
                setTimeout(() => {
                    entry.target.style.animation = slideInUp 0.6s ease forwards;
                }, index * 50);
            }
        });
    });
    
    tableRows.forEach(row => {
        observer.observe(row);
    });
    
    // Smooth scroll to user position if highlighted
    const userRow = document.querySelector('.user-row');
    if (userRow && window.location.hash === '#your-position') {
        setTimeout(() => {
            userRow.scrollIntoView({ 
                behavior: 'smooth', 
                block: 'center' 
            });
        }, 1000);
    }
    
    // Add hover effects to podium positions
    const podiumPositions = document.querySelectorAll('.podium-position');
    podiumPositions.forEach(position => {
        position.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-10px) rotateY(5deg) scale(1.05)';
        });
        
        position.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) rotateY(0) scale(1)';
        });
    });
    
    // Real-time data simulation
    function simulateRealTimeUpdates() {
        const liveIndicators = document.querySelectorAll('.live-dot');
        liveIndicators.forEach(dot => {
            setInterval(() => {
                dot.style.animation = 'none';
                setTimeout(() => {
                    dot.style.animation = 'pulse 2s infinite';
                }, 10);
            }, 30000);
        });
    }
    
    simulateRealTimeUpdates();
    
    // Add click-to-copy functionality for usernames
    const playerNames = document.querySelectorAll('.player-name');
    playerNames.forEach(name => {
        name.addEventListener('click', function() {
            navigator.clipboard.writeText(this.textContent).then(() => {
                // Show tooltip
                const tooltip = document.createElement('div');
                tooltip.textContent = 'Username copied!';
                tooltip.style.cssText = `
                    position: absolute;
                    background: var(--accent-pink);
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
                
                setTimeout(() => {
                    tooltip.remove();
                }, 2000);
            });
        });
        
        name.style.cursor = 'pointer';
        name.title = 'Click to copy username';
    });
    
    // Highlight current user row
    const currentUserRow = document.querySelector('.user-row');
    if (currentUserRow) {
        currentUserRow.style.boxShadow = '0 0 20px rgba(255, 107, 157, 0.3)';
    }
    
    // Auto-refresh rankings (simulation)
    let autoRefreshInterval;
    const startAutoRefresh = () => {
        autoRefreshInterval = setInterval(() => {
            // In a real app, this would fetch new data
            console.log('Auto-refreshing rankings...');
            
            // Update last updated time
            const lastUpdated = document.querySelector('.last-updated');
            if (lastUpdated) {
                lastUpdated.textContent = 'Last updated: ' + new Date().toLocaleString('en-US', {
                    month: 'short',
                    day: 'numeric',
                    hour: 'numeric',
                    minute: '2-digit',
                    hour12: true
                });
            }
        }, 60000); // Update every minute
    };
    
    startAutoRefresh();
    
    // Add keyboard navigation
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            // Go back to leaderboards index
            window.location.href = '/leaderboards';
        }
    });
    
    // Add performance optimization for large tables
    const tableBody = document.querySelector('.table-body');
    if (tableBody && tableRows.length > 50) {
        // Virtual scrolling simulation for better performance
        let isScrolling = false;
        tableBody.addEventListener('scroll', function() {
            if (!isScrolling) {
                window.requestAnimationFrame(function() {
                    // Handle scroll optimization here
                    isScrolling = false;
                });
            }
            isScrolling = true;
        });
    }
});
</script>
@endsection
