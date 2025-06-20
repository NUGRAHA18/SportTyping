@extends('layouts.app')

@section('content')
<div class="leaderboards-container">
    <div class="container">
        <!-- Leaderboards Header -->
        <div class="leaderboards-header">
            <div class="header-content">
                <h1 class="page-title">
                    <i class="fas fa-trophy"></i>
                    Global Leaderboards
                </h1>
                <p class="page-subtitle">
                    See where you rank among the world's fastest typists
                </p>
            </div>
            <div class="header-stats">
                <div class="global-stats">
                    <div class="stat-item">
                        <span class="stat-value">{{ number_format($totalUsers ?? 12543) }}</span>
                        <span class="stat-label">Total Players</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-value">{{ number_format($activeUsers ?? 3247) }}</span>
                        <span class="stat-label">Active This Week</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-value">{{ $topWpm ?? 187 }}</span>
                        <span class="stat-label">Highest WPM</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Leaderboard Categories -->
        <div class="leaderboard-categories">
            <div class="category-tabs">
                <button class="category-tab active" data-category="overall">
                    <i class="fas fa-globe"></i>
                    <span>Overall</span>
                </button>
                <button class="category-tab" data-category="speed">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Speed (WPM)</span>
                </button>
                <button class="category-tab" data-category="accuracy">
                    <i class="fas fa-bullseye"></i>
                    <span>Accuracy</span>
                </button>
                <button class="category-tab" data-category="competitions">
                    <i class="fas fa-racing-flag"></i>
                    <span>Competitions</span>
                </button>
                <button class="category-tab" data-category="practice">
                    <i class="fas fa-keyboard"></i>
                    <span>Practice Hours</span>
                </button>
            </div>
            
            <div class="leaderboard-filters">
                <select class="filter-select" id="time-filter">
                    <option value="all-time">All Time</option>
                    <option value="this-month">This Month</option>
                    <option value="this-week">This Week</option>
                    <option value="today">Today</option>
                </select>
                
                <select class="filter-select" id="region-filter">
                    <option value="global">Global</option>
                    <option value="indonesia">Indonesia</option>
                    <option value="asia">Asia</option>
                    <option value="friends">Friends Only</option>
                </select>
            </div>
        </div>

        <!-- Current User Position -->
        @if(Auth::check())
        <div class="user-position-card">
            <div class="position-header">
                <h3>Your Current Ranking</h3>
            </div>
            <div class="position-content">
                <div class="user-rank-info">
                    <div class="rank-badge">
                        <span class="rank-number">#{{ $userRank ?? 1247 }}</span>
                        <span class="rank-label">Global Rank</span>
                    </div>
                    <div class="user-info">
                        <div class="user-avatar">
                            @if(Auth::user()->profile && Auth::user()->profile->avatar)
                                <img src="{{ asset('storage/' . Auth::user()->profile->avatar) }}" alt="{{ Auth::user()->username }}">
                            @else
                                <div class="avatar-placeholder">{{ strtoupper(substr(Auth::user()->username, 0, 1)) }}</div>
                            @endif
                        </div>
                        <div class="user-details">
                            <div class="user-name">{{ Auth::user()->username }}</div>
                            <div class="user-league">{{ Auth::user()->profile?->league?->name ?? 'Novice' }}</div>
                        </div>
                    </div>
                    <div class="user-stats">
                        <div class="stat">
                            <span class="value">{{ number_format(Auth::user()->profile?->typing_speed_avg ?? 0, 1) }}</span>
                            <span class="label">Avg WPM</span>
                        </div>
                        <div class="stat">
                            <span class="value">{{ number_format(Auth::user()->profile?->typing_accuracy_avg ?? 0, 1) }}%</span>
                            <span class="label">Accuracy</span>
                        </div>
                        <div class="stat">
                            <span class="value">{{ Auth::user()->profile?->total_experience ?? 0 }}</span>
                            <span class="label">EXP</span>
                        </div>
                    </div>
                </div>
                <div class="rank-progress">
                    <div class="progress-info">
                        <span>{{ $rankImprovements ?? 5 }} positions up this week</span>
                        <i class="fas fa-arrow-up text-success"></i>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Main Leaderboard -->
        <div class="main-leaderboard">
            <!-- Top 3 Podium -->
            <div class="podium-section">
                <div class="podium-container">
                    @foreach($topThree ?? [] as $index => $player)
                    <div class="podium-place {{ $index == 0 ? 'first' : ($index == 1 ? 'second' : 'third') }}">
                        <div class="podium-rank">{{ $index + 1 }}</div>
                        <div class="podium-avatar">
                            @if($player->profile && $player->profile->avatar)
                                <img src="{{ asset('storage/' . $player->profile->avatar) }}" alt="{{ $player->username }}">
                            @else
                                <div class="avatar-placeholder">{{ strtoupper(substr($player->username, 0, 1)) }}</div>
                            @endif
                            <div class="crown-icon">
                                @if($index == 0)
                                    <i class="fas fa-crown"></i>
                                @elseif($index == 1)
                                    <i class="fas fa-medal"></i>
                                @else
                                    <i class="fas fa-award"></i>
                                @endif
                            </div>
                        </div>
                        <div class="podium-info">
                            <div class="player-name">{{ $player->username }}</div>
                            <div class="player-league">
                                <img src="/image/leagues/{{ strtolower($player->profile?->league?->name ?? 'novice') }}.png" alt="League" class="league-icon">
                                {{ $player->profile?->league?->name ?? 'Novice' }}
                            </div>
                            <div class="player-score">
                                <span class="score-value">{{ number_format($player->profile?->typing_speed_avg ?? 0, 1) }}</span>
                                <span class="score-unit">WPM</span>
                            </div>
                        </div>
                        <div class="podium-base {{ $index == 0 ? 'gold' : ($index == 1 ? 'silver' : 'bronze') }}">
                            <div class="base-height"></div>
                            <div class="base-label">#{{ $index + 1 }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Leaderboard Table -->
            <div class="leaderboard-table-section">
                <div class="table-header">
                    <h3>
                        <i class="fas fa-list-ol"></i>
                        Rankings
                    </h3>
                    <div class="table-actions">
                        <button class="btn btn-outline-primary" onclick="refreshLeaderboard()">
                            <i class="fas fa-sync-alt"></i>
                            Refresh
                        </button>
                        <button class="btn btn-outline-secondary" onclick="exportLeaderboard()">
                            <i class="fas fa-download"></i>
                            Export
                        </button>
                    </div>
                </div>
                
                <div class="leaderboard-table-container">
                    <table class="leaderboard-table">
                        <thead>
                            <tr>
                                <th class="rank-col">Rank</th>
                                <th class="player-col">Player</th>
                                <th class="league-col">League</th>
                                <th class="wpm-col sortable" data-sort="wpm">
                                    <span>WPM</span>
                                    <i class="fas fa-sort"></i>
                                </th>
                                <th class="accuracy-col sortable" data-sort="accuracy">
                                    <span>Accuracy</span>
                                    <i class="fas fa-sort"></i>
                                </th>
                                <th class="competitions-col sortable" data-sort="competitions">
                                    <span>Competitions</span>
                                    <i class="fas fa-sort"></i>
                                </th>
                                <th class="exp-col sortable" data-sort="exp">
                                    <span>EXP</span>
                                    <i class="fas fa-sort"></i>
                                </th>
                                <th class="actions-col">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="leaderboard-tbody">
                            @foreach($leaderboardData ?? [] as $index => $player)
                            <tr class="player-row {{ Auth::check() && $player->id == Auth::id() ? 'current-user' : '' }}">
                                <td class="rank-col">
                                    <div class="rank-display">
                                        <span class="rank-number">#{{ $index + 4 }}</span>
                                        @if($player->rank_change ?? 0 > 0)
                                            <span class="rank-change up">
                                                <i class="fas fa-arrow-up"></i>
                                                {{ $player->rank_change }}
                                            </span>
                                        @elseif($player->rank_change ?? 0 < 0)
                                            <span class="rank-change down">
                                                <i class="fas fa-arrow-down"></i>
                                                {{ abs($player->rank_change) }}
                                            </span>
                                        @else
                                            <span class="rank-change neutral">
                                                <i class="fas fa-minus"></i>
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="player-col">
                                    <div class="player-info">
                                        <div class="player-avatar">
                                            @if($player->profile && $player->profile->avatar)
                                                <img src="{{ asset('storage/' . $player->profile->avatar) }}" alt="{{ $player->username }}">
                                            @else
                                                <div class="avatar-placeholder">{{ strtoupper(substr($player->username, 0, 1)) }}</div>
                                            @endif
                                            <div class="online-status {{ $player->is_online ?? false ? 'online' : 'offline' }}"></div>
                                        </div>
                                        <div class="player-details">
                                            <div class="player-name">
                                                {{ $player->username }}
                                                @if($player->is_verified ?? false)
                                                    <i class="fas fa-check-circle verified-badge"></i>
                                                @endif
                                            </div>
                                            <div class="player-country">
                                                @if($player->profile?->country)
                                                    <span class="country-flag">🇮🇩</span>
                                                    <span class="country-name">{{ $player->profile->country_name ?? 'Indonesia' }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="league-col">
                                    <div class="league-display">
                                        <img src="/image/leagues/{{ strtolower($player->profile?->league?->name ?? 'novice') }}.png" alt="League" class="league-icon">
                                        <span class="league-name">{{ $player->profile?->league?->name ?? 'Novice' }}</span>
                                    </div>
                                </td>
                                <td class="wpm-col">
                                    <div class="stat-display">
                                        <span class="stat-value">{{ number_format($player->profile?->typing_speed_avg ?? 0, 1) }}</span>
                                        <span class="stat-best">Best: {{ number_format($player->profile?->typing_speed_max ?? 0, 1) }}</span>
                                    </div>
                                </td>
                                <td class="accuracy-col">
                                    <div class="stat-display">
                                        <span class="stat-value">{{ number_format($player->profile?->typing_accuracy_avg ?? 0, 1) }}%</span>
                                        <div class="accuracy-bar">
                                            <div class="accuracy-fill" style="width: {{ $player->profile?->typing_accuracy_avg ?? 0 }}%"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="competitions-col">
                                    <div class="competitions-display">
                                        <span class="competitions-won">{{ $player->competitions_won ?? 0 }}W</span>
                                        <span class="competitions-total">/ {{ $player->competitions_total ?? 0 }}</span>
                                    </div>
                                </td>
                                <td class="exp-col">
                                    <div class="exp-display">
                                        <span class="exp-value">{{ number_format($player->profile?->total_experience ?? 0) }}</span>
                                        <div class="level-badge">Lv.{{ $player->profile?->level ?? 1 }}</div>
                                    </div>
                                </td>
                                <td class="actions-col">
                                    <div class="action-buttons">
                                        <a href="{{ route('profile.show', $player->username) }}" class="btn btn-sm btn-outline-primary" title="View Profile">
                                            <i class="fas fa-user"></i>
                                        </a>
                                        @if(Auth::check() && $player->id !== Auth::id())
                                            <button class="btn btn-sm btn-outline-secondary" onclick="challengePlayer({{ $player->id }})" title="Challenge">
                                                <i class="fas fa-racing-flag"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Load More -->
                <div class="load-more-section">
                    <button class="btn btn-outline-primary" id="load-more-btn" onclick="loadMorePlayers()">
                        <i class="fas fa-chevron-down"></i>
                        Load More Players
                    </button>
                </div>
            </div>
        </div>

        <!-- League Leaderboards -->
        <div class="league-leaderboards">
            <h3 class="section-title">
                <i class="fas fa-shield-alt"></i>
                League Rankings
            </h3>
            <div class="league-tabs">
                @foreach(['novice', 'apprentice', 'expert', 'journeyman', 'legend', 'master', 'grandmaster'] as $league)
                <button class="league-tab {{ $loop->first ? 'active' : '' }}" data-league="{{ $league }}">
                    <img src="/image/leagues/{{ $league }}.png" alt="{{ ucfirst($league) }}" class="league-icon">
                    <span class="league-name">{{ ucfirst($league) }}</span>
                    <span class="league-count">{{ $leagueCounts[$league] ?? 0 }}</span>
                </button>
                @endforeach
            </div>
            
            <div class="league-content" id="league-content">
                <!-- League content will be loaded dynamically -->
            </div>
        </div>
    </div>
</div>

<style>
.leaderboards-container {
    background: var(--bg-primary);
    min-height: calc(100vh - 120px);
    padding: 2rem 0;
}

/* Leaderboards Header */
.leaderboards-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: var(--border-radius-lg);
    padding: 2rem;
    margin-bottom: 2rem;
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

.global-stats {
    display: flex;
    gap: 2rem;
}

.global-stats .stat-item {
    text-align: right;
}

.global-stats .stat-value {
    display: block;
    font-family: var(--font-display);
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--accent-primary);
    margin-bottom: 0.25rem;
}

.global-stats .stat-label {
    color: var(--text-secondary);
    font-size: 0.9rem;
}

/* Leaderboard Categories */
.leaderboard-categories {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: var(--border-radius-lg);
    padding: 1.5rem 2rem;
    margin-bottom: 2rem;
}

.category-tabs {
    display: flex;
    gap: 1rem;
}

.category-tab {
    background: var(--bg-secondary);
    border: 1px solid var(--border-light);
    border-radius: var(--border-radius);
    padding: 0.75rem 1.5rem;
    color: var(--text-secondary);
    font-weight: 500;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    white-space: nowrap;
}

.category-tab:hover,
.category-tab.active {
    background: var(--accent-primary);
    color: white;
    border-color: var(--accent-primary);
    transform: translateY(-2px);
}

.leaderboard-filters {
    display: flex;
    gap: 1rem;
}

.filter-select {
    background: var(--bg-secondary);
    border: 1px solid var(--border-light);
    border-radius: var(--border-radius);
    padding: 0.75rem 1rem;
    color: var(--text-primary);
    font-size: 0.9rem;
    min-width: 120px;
}

.filter-select:focus {
    outline: none;
    border-color: var(--accent-primary);
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

/* User Position Card */
.user-position-card {
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(16, 185, 129, 0.1));
    border: 1px solid var(--accent-primary);
    border-radius: var(--border-radius-lg);
    padding: 2rem;
    margin-bottom: 2rem;
}

.position-header h3 {
    font-family: var(--font-display);
    color: var(--text-primary);
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.user-rank-info {
    display: flex;
    align-items: center;
    gap: 2rem;
}

.rank-badge {
    text-align: center;
    background: var(--bg-card);
    border-radius: var(--border-radius-lg);
    padding: 1.5rem;
    min-width: 120px;
}

.rank-number {
    display: block;
    font-family: var(--font-display);
    font-size: 2rem;
    font-weight: 700;
    color: var(--accent-primary);
    margin-bottom: 0.25rem;
}

.rank-label {
    color: var(--text-secondary);
    font-size: 0.9rem;
}

.user-info {
    display: flex;
    align-items: center;
    gap: 1rem;
    flex: 1;
}

.user-avatar {
    width: 64px;
    height: 64px;
    border-radius: 50%;
    overflow: hidden;
    border: 3px solid var(--accent-primary);
}

.user-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.avatar-placeholder {
    width: 100%;
    height: 100%;
    background: var(--accent-primary);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 1.5rem;
}

.user-name {
    font-family: var(--font-display);
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 0.25rem;
}

.user-league {
    color: var(--accent-primary);
    font-weight: 500;
}

.user-stats {
    display: flex;
    gap: 2rem;
}

.user-stats .stat {
    text-align: center;
}

.user-stats .value {
    display: block;
    font-family: var(--font-display);
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 0.25rem;
}

.user-stats .label {
    color: var(--text-secondary);
    font-size: 0.8rem;
}

.rank-progress {
    text-align: center;
    padding: 1rem;
    background: rgba(34, 197, 94, 0.1);
    border-radius: var(--border-radius);
    margin-top: 1rem;
}

.progress-info {
    color: var(--accent-success);
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

/* Podium Section */
.podium-section {
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: var(--border-radius-lg);
    padding: 3rem 2rem;
    margin-bottom: 2rem;
    position: relative;
    overflow: hidden;
}

.podium-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('/image/ui/achievement_bg.png') center/cover;
    opacity: 0.05;
    pointer-events: none;
}

.podium-container {
    display: flex;
    justify-content: center;
    align-items: flex-end;
    gap: 2rem;
    position: relative;
    z-index: 2;
}

.podium-place {
    text-align: center;
    position: relative;
}

.podium-place.first {
    order: 2;
}

.podium-place.second {
    order: 1;
}

.podium-place.third {
    order: 3;
}

.podium-rank {
    position: absolute;
    top: -15px;
    right: -15px;
    width: 32px;
    height: 32px;
    background: var(--accent-primary);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    z-index: 3;
}

.podium-avatar {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    overflow: hidden;
    border: 4px solid;
    margin: 0 auto 1rem;
    position: relative;
}

.podium-place.first .podium-avatar {
    border-color: #FFD700;
    width: 100px;
    height: 100px;
}

.podium-place.second .podium-avatar {
    border-color: #C0C0C0;
}

.podium-place.third .podium-avatar {
    border-color: #CD7F32;
}

.podium-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.crown-icon {
    position: absolute;
    top: -15px;
    left: 50%;
    transform: translateX(-50%);
    font-size: 1.5rem;
}

.podium-place.first .crown-icon {
    color: #FFD700;
}

.podium-place.second .crown-icon {
    color: #C0C0C0;
}

.podium-place.third .crown-icon {
    color: #CD7F32;
}

.player-name {
    font-family: var(--font-display);
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 0.5rem;
}

.player-league {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    color: var(--text-secondary);
    font-size: 0.9rem;
    margin-bottom: 1rem;
}

.league-icon {
    width: 20px;
    height: 20px;
}

.player-score {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-bottom: 2rem;
}

.score-value {
    font-family: var(--font-display);
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--accent-primary);
}

.score-unit {
    color: var(--text-secondary);
    font-size: 0.9rem;
}

.podium-base {
    width: 120px;
    border-radius: var(--border-radius) var(--border-radius) 0 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
}

.podium-place.first .podium-base {
    width: 140px;
}

.base-height {
    width: 100%;
    background: linear-gradient(135deg, var(--medal-gradient));
}

.podium-place.first .base-height {
    height: 100px;
    background: linear-gradient(135deg, #FFD700, #FFA500);
}

.podium-place.second .base-height {
    height: 80px;
    background: linear-gradient(135deg, #C0C0C0, #A0A0A0);
}

.podium-place.third .base-height {
    height: 60px;
    background: linear-gradient(135deg, #CD7F32, #B8860B);
}

.base-label {
    background: var(--bg-card);
    color: var(--text-primary);
    font-weight: 700;
    padding: 0.5rem;
    border-radius: 0 0 var(--border-radius) var(--border-radius);
    width: 100%;
    border: 1px solid var(--border-light);
    border-top: none;
}

/* Leaderboard Table */
.leaderboard-table-section {
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: var(--border-radius-lg);
    overflow: hidden;
    margin-bottom: 2rem;
}

.table-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem 2rem;
    border-bottom: 1px solid var(--border-light);
    background: var(--bg-secondary);
}

.table-header h3 {
    font-family: var(--font-display);
    color: var(--text-primary);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.table-header i {
    color: var(--accent-primary);
}

.table-actions {
    display: flex;
    gap: 1rem;
}

.leaderboard-table-container {
    overflow-x: auto;
}

.leaderboard-table {
    width: 100%;
    border-collapse: collapse;
}

.leaderboard-table th {
    background: var(--bg-secondary);
    color: var(--text-primary);
    font-weight: 600;
    padding: 1rem;
    text-align: left;
    border-bottom: 1px solid var(--border-light);
    white-space: nowrap;
}

.leaderboard-table th.sortable {
    cursor: pointer;
    user-select: none;
    transition: all 0.3s ease;
}

.leaderboard-table th.sortable:hover {
    background: var(--border-light);
}

.leaderboard-table th.sortable span {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.leaderboard-table td {
    padding: 1rem;
    border-bottom: 1px solid var(--border-light);
    vertical-align: middle;
}

.player-row:hover {
    background: var(--bg-secondary);
}

.player-row.current-user {
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(16, 185, 129, 0.1));
    border: 1px solid var(--accent-primary);
}

.rank-display {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.rank-number {
    font-family: var(--font-display);
    font-weight: 700;
    color: var(--text-primary);
    min-width: 40px;
}

.rank-change {
    font-size: 0.8rem;
    padding: 0.25rem 0.5rem;
    border-radius: var(--border-radius);
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.rank-change.up {
    background: rgba(34, 197, 94, 0.1);
    color: var(--accent-success);
}

.rank-change.down {
    background: rgba(239, 68, 68, 0.1);
    color: var(--accent-danger);
}

.rank-change.neutral {
    background: var(--bg-secondary);
    color: var(--text-muted);
}

.player-info {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.player-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    overflow: hidden;
    position: relative;
}

.online-status {
    position: absolute;
    bottom: 0;
    right: 0;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid white;
}

.online-status.online {
    background: var(--accent-success);
}

.online-status.offline {
    background: var(--text-muted);
}

.player-name {
    font-weight: 600;
    color: var(--text-primary);
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.verified-badge {
    color: var(--accent-primary);
    font-size: 0.9rem;
}

.player-country {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--text-secondary);
    font-size: 0.8rem;
}

.league-display {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.league-name {
    color: var(--text-primary);
    font-weight: 500;
}

.stat-display {
    text-align: center;
}

.stat-value {
    display: block;
    font-family: var(--font-display);
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 0.25rem;
}

.stat-best {
    font-size: 0.8rem;
    color: var(--text-secondary);
}

.accuracy-bar {
    width: 60px;
    height: 4px;
    background: var(--border-light);
    border-radius: 2px;
    overflow: hidden;
    margin: 0 auto;
}

.accuracy-fill {
    height: 100%;
    background: var(--accent-success);
    transition: width 0.3s ease;
}

.competitions-display {
    text-align: center;
}

.competitions-won {
    font-weight: 700;
    color: var(--accent-success);
}

.competitions-total {
    color: var(--text-secondary);
}

.exp-display {
    text-align: center;
}

.exp-value {
    display: block;
    font-family: var(--font-display);
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 0.25rem;
}

.level-badge {
    background: var(--accent-primary);
    color: white;
    padding: 0.25rem 0.5rem;
    border-radius: var(--border-radius);
    font-size: 0.7rem;
    font-weight: 600;
}

.action-buttons {
    display: flex;
    gap: 0.5rem;
}

/* Load More */
.load-more-section {
    text-align: center;
    padding: 2rem;
    background: var(--bg-secondary);
}

/* League Leaderboards */
.league-leaderboards {
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: var(--border-radius-lg);
    padding: 2rem;
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

.league-tabs {
    display: flex;
    gap: 1rem;
    margin-bottom: 2rem;
    overflow-x: auto;
    padding-bottom: 0.5rem;
}

.league-tab {
    background: var(--bg-secondary);
    border: 1px solid var(--border-light);
    border-radius: var(--border-radius);
    padding: 1rem;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
    min-width: 100px;
    white-space: nowrap;
}

.league-tab:hover,
.league-tab.active {
    background: var(--accent-primary);
    color: white;
    border-color: var(--accent-primary);
    transform: translateY(-2px);
}

.league-tab .league-icon {
    width: 32px;
    height: 32px;
}

.league-tab .league-name {
    font-weight: 600;
    font-size: 0.9rem;
}

.league-tab .league-count {
    background: rgba(255,255,255,0.2);
    padding: 0.25rem 0.5rem;
    border-radius: var(--border-radius);
    font-size: 0.8rem;
    font-weight: 600;
}

.league-tab:not(.active) .league-count {
    background: var(--border-light);
    color: var(--text-muted);
}

/* Responsive Design */
@media (max-width: 1024px) {
    .leaderboards-header {
        flex-direction: column;
        gap: 1.5rem;
        text-align: center;
    }
    
    .leaderboard-categories {
        flex-direction: column;
        gap: 1.5rem;
    }
    
    .category-tabs {
        flex-wrap: wrap;
        justify-content: center;
    }
    
    .user-rank-info {
        flex-direction: column;
        text-align: center;
        gap: 1rem;
    }
    
    .podium-container {
        flex-direction: column;
        align-items: center;
        gap: 1rem;
    }
    
    .podium-place {
        order: initial !important;
    }
}

@media (max-width: 768px) {
    .leaderboards-container {
        padding: 1rem 0;
    }
    
    .leaderboards-header,
    .leaderboard-categories,
    .user-position-card,
    .podium-section,
    .leaderboard-table-section,
    .league-leaderboards {
        padding: 1rem;
    }
    
    .global-stats {
        justify-content: center;
        flex-wrap: wrap;
    }
    
    .user-stats {
        flex-wrap: wrap;
        justify-content: center;
    }
    
    .table-header {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
    
    .league-tabs {
        justify-content: center;
    }
    
    /* Hide some columns on mobile */
    .competitions-col,
    .exp-col {
        display: none;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Category tab switching
    document.querySelectorAll('.category-tab').forEach(tab => {
        tab.addEventListener('click', function() {
            document.querySelectorAll('.category-tab').forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            
            const category = this.dataset.category;
            loadLeaderboardData(category);
        });
    });
    
    // Filter changes
    document.getElementById('time-filter').addEventListener('change', function() {
        loadLeaderboardData();
    });
    
    document.getElementById('region-filter').addEventListener('change', function() {
        loadLeaderboardData();
    });
    
    // Sortable columns
    document.querySelectorAll('.sortable').forEach(header => {
        header.addEventListener('click', function() {
            const column = this.dataset.sort;
            sortLeaderboard(column);
        });
    });
    
    // League tab switching
    document.querySelectorAll('.league-tab').forEach(tab => {
        tab.addEventListener('click', function() {
            document.querySelectorAll('.league-tab').forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            
            const league = this.dataset.league;
            loadLeagueData(league);
        });
    });
});

function loadLeaderboardData(category = null) {
    const activeCategory = category || document.querySelector('.category-tab.active').dataset.category;
    const timeFilter = document.getElementById('time-filter').value;
    const regionFilter = document.getElementById('region-filter').value;
    
    // Show loading state
    const tbody = document.getElementById('leaderboard-tbody');
    tbody.innerHTML = '<tr><td colspan="8" class="text-center">Loading...</td></tr>';
    
    // In real app, this would be an AJAX call
    setTimeout(() => {
        // Simulate data loading
        console.log('Loading leaderboard data:', { category: activeCategory, time: timeFilter, region: regionFilter });
        
        // Restore original content (in real app, this would be replaced with new data)
        location.reload();
    }, 1000);
}

function sortLeaderboard(column) {
    const tbody = document.getElementById('leaderboard-tbody');
    const rows = Array.from(tbody.querySelectorAll('tr'));
    
    // Simple sorting example (in real app, this would sort actual data)
    rows.sort((a, b) => {
        // Sorting logic would go here
        return Math.random() - 0.5; // Random sort for demo
    });
    
    rows.forEach(row => tbody.appendChild(row));
}

function loadMorePlayers() {
    const loadMoreBtn = document.getElementById('load-more-btn');
    loadMoreBtn.disabled = true;
    loadMoreBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';
    
    // In real app, this would load more data via AJAX
    setTimeout(() => {
        loadMoreBtn.disabled = false;
        loadMoreBtn.innerHTML = '<i class="fas fa-chevron-down"></i> Load More Players';
        
        // Add new rows (simulated)
        console.log('Loading more players...');
    }, 1000);
}

function loadLeagueData(league) {
    const leagueContent = document.getElementById('league-content');
    leagueContent.innerHTML = '<div class="text-center p-4">Loading ' + league + ' league data...</div>';
    
    // In real app, load league-specific leaderboard
    setTimeout(() => {
        leagueContent.innerHTML = '<div class="text-center p-4">League leaderboard for ' + league + ' will be displayed here.</div>';
    }, 500);
}

function refreshLeaderboard() {
    // Refresh current leaderboard data
    loadLeaderboardData();
}

function exportLeaderboard() {
    // In real app, this would export leaderboard data
    alert('Export feature will be available soon!');
}

function challengePlayer(playerId) {
    // In real app, this would create a challenge
    alert('Challenge feature coming soon!');
}
</script>
@endsection