@extends('layouts.app')

@section('content')
<div class="profile-container">
    <div class="container">
        <!-- Profile Header -->
        <div class="profile-header">
            <div class="profile-banner">
                <div class="banner-content">
                    <div class="profile-avatar-section">
                        <div class="avatar-container">
                            @if($user->profile && $user->profile->avatar)
                                <img src="{{ asset('storage/' . $user->profile->avatar) }}" alt="{{ $user->username }}" class="profile-avatar">
                            @else
                                <div class="avatar-placeholder">
                                    {{ strtoupper(substr($user->username, 0, 2)) }}
                                </div>
                            @endif
                            <div class="avatar-status online"></div>
                        </div>
                        
                        <div class="league-badge-container">
                            <div class="league-badge">
                                <img src="/image/leagues/{{ strtolower($user->profile?->league?->name ?? 'novice') }}.png" alt="{{ $user->profile?->league?->name ?? 'Novice' }}" class="league-icon">
                                <span class="league-name">{{ $user->profile?->league?->name ?? 'Novice' }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="profile-info">
                        <h1 class="profile-name">{{ $user->username }}</h1>
                        <p class="profile-title">{{ $user->profile?->title ?? 'Typing Enthusiast' }}</p>
                        <div class="profile-stats-quick">
                            <div class="quick-stat">
                                <span class="stat-value">{{ number_format($user->profile?->typing_speed_avg ?? 0, 1) }}</span>
                                <span class="stat-label">Avg WPM</span>
                            </div>
                            <div class="quick-stat">
                                <span class="stat-value">{{ number_format($user->profile?->typing_accuracy_avg ?? 0, 1) }}%</span>
                                <span class="stat-label">Accuracy</span>
                            </div>
                            <div class="quick-stat">
                                <span class="stat-value">{{ $user->profile?->total_experience ?? 0 }}</span>
                                <span class="stat-label">EXP</span>
                            </div>
                        </div>
                        <div class="profile-join-date">
                            <i class="fas fa-calendar"></i>
                            <span>Joined {{ $user->created_at->format('F Y') }}</span>
                        </div>
                    </div>
                    
                    <div class="profile-actions">
                        @if(Auth::id() === $user->id)
                            <a href="{{ route('profile.edit') }}" class="btn btn-primary">
                                <i class="fas fa-edit"></i>
                                Edit Profile
                            </a>
                            <button class="btn btn-outline-secondary" onclick="shareProfile()">
                                <i class="fas fa-share"></i>
                                Share
                            </button>
                        @else
                            <button class="btn btn-outline-primary" onclick="challengeUser()">
                                <i class="fas fa-racing-flag"></i>
                                Challenge
                            </button>
                            <button class="btn btn-outline-secondary" onclick="followUser()">
                                <i class="fas fa-user-plus"></i>
                                Follow
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Experience Progress -->
        <div class="experience-section">
            <div class="exp-container">
                <div class="exp-header">
                    <div class="exp-info">
                        <h3>Experience Progress</h3>
                        <p>Level {{ $user->profile?->level ?? 1 }} • {{ number_format($user->profile?->total_experience ?? 0) }} EXP</p>
                    </div>
                    <div class="next-level-info">
                        <span>Next Level: {{ number_format($nextLevelExp ?? 1000) }} EXP</span>
                    </div>
                </div>
                <div class="exp-progress">
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: {{ $expProgress ?? 0 }}%"></div>
                    </div>
                    <div class="exp-text">
                        {{ number_format($currentLevelExp ?? 0) }} / {{ number_format($nextLevelExp ?? 1000) }} EXP
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="profile-content">
            <div class="content-grid">
                <!-- Left Column -->
                <div class="left-column">
                    <!-- Statistics Cards -->
                    <div class="stats-section">
                        <h3 class="section-title">
                            <i class="fas fa-chart-bar"></i>
                            Statistics
                        </h3>
                        <div class="stats-grid">
                            <div class="stat-card speed-card">
                                <div class="stat-icon">
                                    <i class="fas fa-tachometer-alt"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="stat-main">
                                        <span class="stat-number">{{ number_format($user->profile?->typing_speed_avg ?? 0, 1) }}</span>
                                        <span class="stat-unit">WPM</span>
                                    </div>
                                    <div class="stat-label">Average Speed</div>
                                    <div class="stat-details">
                                        <span class="stat-detail">Best: {{ number_format($user->profile?->typing_speed_max ?? 0, 1) }} WPM</span>
                                    </div>
                                </div>
                                <div class="stat-trend positive">
                                    <i class="fas fa-arrow-up"></i>
                                    <span>+2.5%</span>
                                </div>
                            </div>

                            <div class="stat-card accuracy-card">
                                <div class="stat-icon">
                                    <i class="fas fa-bullseye"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="stat-main">
                                        <span class="stat-number">{{ number_format($user->profile?->typing_accuracy_avg ?? 0, 1) }}</span>
                                        <span class="stat-unit">%</span>
                                    </div>
                                    <div class="stat-label">Accuracy</div>
                                    <div class="stat-details">
                                        <span class="stat-detail">Best: {{ number_format($user->profile?->typing_accuracy_max ?? 0, 1) }}%</span>
                                    </div>
                                </div>
                                <div class="stat-trend positive">
                                    <i class="fas fa-arrow-up"></i>
                                    <span>+1.2%</span>
                                </div>
                            </div>

                            <div class="stat-card practice-card">
                                <div class="stat-icon">
                                    <i class="fas fa-keyboard"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="stat-main">
                                        <span class="stat-number">{{ $user->practices_count ?? 0 }}</span>
                                        <span class="stat-unit"></span>
                                    </div>
                                    <div class="stat-label">Practice Sessions</div>
                                    <div class="stat-details">
                                        <span class="stat-detail">This week: {{ $weeklyPractices ?? 0 }}</span>
                                    </div>
                                </div>
                                <div class="stat-trend">
                                    <i class="fas fa-calendar"></i>
                                    <span>{{ $practiceStreak ?? 0 }} day streak</span>
                                </div>
                            </div>

                            <div class="stat-card competition-card">
                                <div class="stat-icon">
                                    <i class="fas fa-trophy"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="stat-main">
                                        <span class="stat-number">{{ $user->competitions_won ?? 0 }}</span>
                                        <span class="stat-unit"></span>
                                    </div>
                                    <div class="stat-label">Competitions Won</div>
                                    <div class="stat-details">
                                        <span class="stat-detail">Participated: {{ $user->competitions_joined ?? 0 }}</span>
                                    </div>
                                </div>
                                <div class="stat-trend">
                                    <span>{{ $user->competitions_won > 0 ? number_format(($user->competitions_won / max($user->competitions_joined, 1)) * 100, 1) : 0 }}% win rate</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Performance Chart -->
                    <div class="chart-section">
                        <div class="chart-header">
                            <h3 class="section-title">
                                <i class="fas fa-chart-line"></i>
                                Performance Trends
                            </h3>
                            <div class="chart-controls">
                                <button class="chart-period active" data-period="7d">7 Days</button>
                                <button class="chart-period" data-period="30d">30 Days</button>
                                <button class="chart-period" data-period="90d">3 Months</button>
                            </div>
                        </div>
                        <div class="chart-container">
                            <canvas id="performance-chart" width="400" height="200"></canvas>
                        </div>
                    </div>

                    <!-- Recent Activity -->
                    <div class="activity-section">
                        <h3 class="section-title">
                            <i class="fas fa-history"></i>
                            Recent Activity
                        </h3>
                        <div class="activity-list">
                            @foreach($recentActivities ?? [] as $activity)
                            <div class="activity-item">
                                <div class="activity-icon {{ $activity->type }}">
                                    <i class="fas fa-{{ $activity->type == 'practice' ? 'keyboard' : ($activity->type == 'competition' ? 'racing-flag' : 'graduation-cap') }}"></i>
                                </div>
                                <div class="activity-content">
                                    <div class="activity-title">{{ $activity->title }}</div>
                                    <div class="activity-details">{{ $activity->description }}</div>
                                    <div class="activity-time">{{ $activity->created_at->diffForHumans() }}</div>
                                </div>
                                <div class="activity-result">
                                    @if($activity->type == 'practice')
                                        <span class="wpm-result">{{ $activity->wpm }} WPM</span>
                                    @elseif($activity->type == 'competition')
                                        <span class="position-result">#{{ $activity->position }}</span>
                                    @else
                                        <span class="exp-result">+{{ $activity->exp }} EXP</span>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="right-column">
                    <!-- Badge Collection -->
                    <div class="badges-section" id="achievements">
                        <div class="section-header">
                            <h3 class="section-title">
                                <i class="fas fa-medal"></i>
                                Badge Collection
                            </h3>
                            <div class="badges-count">
                                {{ $user->badges_count ?? 0 }} / {{ $totalBadges ?? 25 }} earned
                            </div>
                        </div>
                        
                        <div class="badges-progress">
                            <div class="badges-progress-bar">
                                <div class="badges-progress-fill" style="width: {{ $user->badges_count > 0 ? ($user->badges_count / max($totalBadges, 1)) * 100 : 0 }}%"></div>
                            </div>
                        </div>

                        <div class="badges-grid">
                            @foreach($badges ?? [] as $badge)
                            <div class="badge-item {{ $badge->earned ? 'earned' : 'locked' }}" data-toggle="tooltip" title="{{ $badge->description }}">
                                <div class="badge-container">
                                    <img src="/image/badges/{{ $badge->icon }}.png" alt="{{ $badge->name }}" class="badge-icon">
                                    @if($badge->earned)
                                        <div class="badge-shine"></div>
                                    @else
                                        <div class="badge-lock">
                                            <i class="fas fa-lock"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="badge-name">{{ $badge->name }}</div>
                                @if($badge->earned)
                                    <div class="badge-earned-date">{{ $badge->earned_at?->format('M j, Y') }}</div>
                                @else
                                    <div class="badge-requirement">{{ $badge->requirement }}</div>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- League Progression -->
                    <div class="league-section">
                        <h3 class="section-title">
                            <i class="fas fa-shield-alt"></i>
                            League Progression
                        </h3>
                        
                        <div class="league-progression">
                            @foreach(['novice', 'apprentice', 'expert', 'journeyman', 'legend', 'master', 'grandmaster'] as $index => $leagueName)
                            <div class="league-step {{ $index <= ($currentLeagueIndex ?? 0) ? 'achieved' : '' }} {{ $index == ($currentLeagueIndex ?? 0) ? 'current' : '' }}">
                                <div class="league-icon-container">
                                    <img src="/image/leagues/{{ $leagueName }}.png" alt="{{ ucfirst($leagueName) }}" class="league-icon">
                                    @if($index == ($currentLeagueIndex ?? 0))
                                        <div class="current-indicator">Current</div>
                                    @endif
                                </div>
                                <div class="league-info">
                                    <div class="league-name">{{ ucfirst($leagueName) }}</div>
                                    <div class="league-requirement">
                                        @if($index <= ($currentLeagueIndex ?? 0))
                                            <span class="achieved-text">Achieved!</span>
                                        @else
                                            <span class="requirement-text">{{ $leagueRequirements[$leagueName] ?? 'Reach next level' }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Personal Records -->
                    <div class="records-section">
                        <h3 class="section-title">
                            <i class="fas fa-crown"></i>
                            Personal Records
                        </h3>
                        
                        <div class="records-list">
                            <div class="record-item">
                                <div class="record-icon speed">
                                    <i class="fas fa-bolt"></i>
                                </div>
                                <div class="record-content">
                                    <div class="record-title">Fastest Speed</div>
                                    <div class="record-value">{{ number_format($user->profile?->typing_speed_max ?? 0, 1) }} WPM</div>
                                    <div class="record-context">{{ $fastestSpeedDate ?? 'No record yet' }}</div>
                                </div>
                            </div>

                            <div class="record-item">
                                <div class="record-icon accuracy">
                                    <i class="fas fa-crosshairs"></i>
                                </div>
                                <div class="record-content">
                                    <div class="record-title">Perfect Accuracy</div>
                                    <div class="record-value">{{ $perfectAccuracyCount ?? 0 }} times</div>
                                    <div class="record-context">100% accuracy achieved</div>
                                </div>
                            </div>

                            <div class="record-item">
                                <div class="record-icon streak">
                                    <i class="fas fa-fire"></i>
                                </div>
                                <div class="record-content">
                                    <div class="record-title">Longest Streak</div>
                                    <div class="record-value">{{ $longestStreak ?? 0 }} days</div>
                                    <div class="record-context">Daily practice streak</div>
                                </div>
                            </div>

                            <div class="record-item">
                                <div class="record-icon competition">
                                    <i class="fas fa-trophy"></i>
                                </div>
                                <div class="record-content">
                                    <div class="record-title">Competition Wins</div>
                                    <div class="record-value">{{ $user->competitions_won ?? 0 }} wins</div>
                                    <div class="record-context">Total victories</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="quick-actions-section">
                        <h3 class="section-title">
                            <i class="fas fa-lightning-bolt"></i>
                            Quick Actions
                        </h3>
                        
                        <div class="quick-actions-grid">
                            <a href="{{ route('practice.index') }}" class="quick-action-card">
                                <div class="action-icon">
                                    <i class="fas fa-keyboard"></i>
                                </div>
                                <div class="action-text">Start Practice</div>
                            </a>
                            
                            <a href="{{ route('competitions.index') }}" class="quick-action-card">
                                <div class="action-icon">
                                    <i class="fas fa-racing-flag"></i>
                                </div>
                                <div class="action-text">Join Competition</div>
                            </a>
                            
                            <a href="{{ route('lessons.index') }}" class="quick-action-card">
                                <div class="action-icon">
                                    <i class="fas fa-graduation-cap"></i>
                                </div>
                                <div class="action-text">Take Lessons</div>
                            </a>
                            
                            <a href="{{ route('leaderboards.index') }}" class="quick-action-card">
                                <div class="action-icon">
                                    <i class="fas fa-chart-bar"></i>
                                </div>
                                <div class="action-text">View Rankings</div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.profile-container {
    background: var(--bg-primary);
    min-height: calc(100vh - 120px);
    padding: 2rem 0;
}

/* Profile Header */
.profile-header {
    margin-bottom: 2rem;
}

.profile-banner {
    background: linear-gradient(135deg, var(--champion-gradient), var(--medal-gradient));
    border-radius: var(--border-radius-xl);
    padding: 3rem 2rem;
    position: relative;
    overflow: hidden;
}

.profile-banner::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('/image/ui/achievement_bg.png') center/cover;
    opacity: 0.1;
    pointer-events: none;
}

.banner-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: relative;
    z-index: 2;
}

.profile-avatar-section {
    display: flex;
    align-items: center;
    gap: 2rem;
}

.avatar-container {
    position: relative;
}

.profile-avatar {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    border: 4px solid white;
    object-fit: cover;
}

.avatar-placeholder {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    border: 4px solid white;
    background: var(--accent-primary);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    font-weight: 700;
}

.avatar-status {
    position: absolute;
    bottom: 8px;
    right: 8px;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    border: 3px solid white;
}

.avatar-status.online {
    background: var(--accent-success);
}

.league-badge-container {
    text-align: center;
}

.league-badge {
    background: rgba(255,255,255,0.2);
    backdrop-filter: blur(10px);
    border-radius: var(--border-radius-lg);
    padding: 1rem;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
}

.league-icon {
    width: 64px;
    height: 64px;
}

.league-name {
    color: white;
    font-weight: 600;
    font-size: 0.9rem;
}

.profile-info {
    flex: 1;
    text-align: center;
    color: white;
}

.profile-name {
    font-family: var(--font-display);
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.profile-title {
    font-size: 1.1rem;
    opacity: 0.9;
    margin-bottom: 1.5rem;
}

.profile-stats-quick {
    display: flex;
    justify-content: center;
    gap: 3rem;
    margin-bottom: 1rem;
}

.quick-stat {
    text-align: center;
}

.quick-stat .stat-value {
    display: block;
    font-family: var(--font-display);
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 0.25rem;
}

.quick-stat .stat-label {
    font-size: 0.9rem;
    opacity: 0.8;
}

.profile-join-date {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    opacity: 0.8;
    font-size: 0.9rem;
}

.profile-actions {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

/* Experience Section */
.experience-section {
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: var(--border-radius-lg);
    padding: 2rem;
    margin-bottom: 2rem;
}

.exp-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.exp-info h3 {
    font-family: var(--font-display);
    color: var(--text-primary);
    margin-bottom: 0.25rem;
}

.exp-info p {
    color: var(--text-secondary);
    margin: 0;
}

.next-level-info {
    color: var(--accent-primary);
    font-weight: 600;
    font-size: 0.9rem;
}

.exp-progress {
    position: relative;
}

.progress-bar {
    width: 100%;
    height: 12px;
    background: var(--border-light);
    border-radius: 6px;
    overflow: hidden;
    margin-bottom: 0.5rem;
}

.progress-fill {
    height: 100%;
    background: var(--champion-gradient);
    transition: width 0.3s ease;
}

.exp-text {
    text-align: center;
    color: var(--text-secondary);
    font-size: 0.9rem;
}

/* Content Grid */
.content-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 2rem;
}

/* Section Titles */
.section-title {
    font-family: var(--font-display);
    font-size: 1.25rem;
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
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: var(--border-radius-lg);
    padding: 2rem;
    margin-bottom: 2rem;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
}

.stat-card {
    background: var(--bg-secondary);
    border: 1px solid var(--border-light);
    border-radius: var(--border-radius);
    padding: 1.5rem;
    position: relative;
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    border-radius: var(--border-radius) var(--border-radius) 0 0;
}

.speed-card::before { background: var(--accent-primary); }
.accuracy-card::before { background: var(--accent-success); }
.practice-card::before { background: var(--accent-warning); }
.competition-card::before { background: var(--accent-danger); }

.stat-icon {
    width: 48px;
    height: 48px;
    background: var(--champion-gradient);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2rem;
    margin-bottom: 1rem;
}

.stat-main {
    display: flex;
    align-items: baseline;
    gap: 0.5rem;
    margin-bottom: 0.5rem;
}

.stat-number {
    font-family: var(--font-display);
    font-size: 2rem;
    font-weight: 700;
    color: var(--text-primary);
}

.stat-unit {
    font-size: 1rem;
    color: var(--text-secondary);
}

.stat-label {
    color: var(--text-secondary);
    font-size: 0.9rem;
    margin-bottom: 0.5rem;
}

.stat-details {
    font-size: 0.8rem;
    color: var(--text-muted);
}

.stat-trend {
    position: absolute;
    top: 1rem;
    right: 1rem;
    display: flex;
    align-items: center;
    gap: 0.25rem;
    font-size: 0.8rem;
    font-weight: 600;
}

.stat-trend.positive {
    color: var(--accent-success);
}

/* Chart Section */
.chart-section {
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: var(--border-radius-lg);
    padding: 2rem;
    margin-bottom: 2rem;
}

.chart-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
}

.chart-controls {
    display: flex;
    gap: 0.5rem;
}

.chart-period {
    background: var(--bg-secondary);
    border: 1px solid var(--border-light);
    border-radius: var(--border-radius);
    padding: 0.5rem 1rem;
    color: var(--text-secondary);
    font-size: 0.9rem;
    transition: all 0.3s ease;
}

.chart-period.active,
.chart-period:hover {
    background: var(--accent-primary);
    color: white;
    border-color: var(--accent-primary);
}

.chart-container {
    position: relative;
    height: 200px;
}

/* Activity Section */
.activity-section {
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: var(--border-radius-lg);
    padding: 2rem;
}

.activity-list {
    max-height: 400px;
    overflow-y: auto;
}

.activity-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    border-radius: var(--border-radius);
    margin-bottom: 0.5rem;
    transition: background 0.3s ease;
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
}

.activity-icon.practice { background: var(--accent-primary); }
.activity-icon.competition { background: var(--accent-danger); }
.activity-icon.lesson { background: var(--accent-success); }

.activity-content {
    flex: 1;
}

.activity-title {
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 0.25rem;
}

.activity-details {
    color: var(--text-secondary);
    font-size: 0.9rem;
    margin-bottom: 0.25rem;
}

.activity-time {
    color: var(--text-muted);
    font-size: 0.8rem;
}

.activity-result {
    text-align: right;
    font-weight: 600;
}

.wpm-result { color: var(--accent-primary); }
.position-result { color: var(--accent-warning); }
.exp-result { color: var(--accent-success); }

/* Right Column */
.right-column > * {
    margin-bottom: 2rem;
}

/* Badges Section */
.badges-section {
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: var(--border-radius-lg);
    padding: 2rem;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.badges-count {
    background: var(--accent-primary);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: var(--border-radius);
    font-size: 0.9rem;
    font-weight: 600;
}

.badges-progress {
    margin-bottom: 2rem;
}

.badges-progress-bar {
    width: 100%;
    height: 8px;
    background: var(--border-light);
    border-radius: 4px;
    overflow: hidden;
}

.badges-progress-fill {
    height: 100%;
    background: var(--medal-gradient);
    transition: width 0.3s ease;
}

.badges-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(80px, 1fr));
    gap: 1rem;
}

.badge-item {
    text-align: center;
    padding: 1rem;
    border-radius: var(--border-radius);
    transition: all 0.3s ease;
    position: relative;
}

.badge-item.earned {
    background: linear-gradient(135deg, rgba(255, 215, 0, 0.1), rgba(255, 165, 0, 0.1));
    border: 1px solid rgba(255, 215, 0, 0.3);
}

.badge-item.locked {
    opacity: 0.5;
    background: var(--bg-secondary);
    border: 1px solid var(--border-light);
}

.badge-container {
    position: relative;
    display: inline-block;
    margin-bottom: 0.5rem;
}

.badge-icon {
    width: 48px;
    height: 48px;
    transition: transform 0.3s ease;
}

.badge-item.earned:hover .badge-icon {
    transform: scale(1.1);
}

.badge-shine {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(45deg, transparent, rgba(255,255,255,0.3), transparent);
    border-radius: 50%;
    animation: shine 2s infinite;
}

@keyframes shine {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(100%); }
}

.badge-lock {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: rgba(0,0,0,0.7);
    color: white;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.8rem;
}

.badge-name {
    font-size: 0.8rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 0.25rem;
}

.badge-earned-date {
    font-size: 0.7rem;
    color: var(--accent-success);
}

.badge-requirement {
    font-size: 0.7rem;
    color: var(--text-muted);
}

/* League Section */
.league-section {
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: var(--border-radius-lg);
    padding: 2rem;
}

.league-progression {
    space-y: 1rem;
}

.league-step {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    border-radius: var(--border-radius);
    transition: all 0.3s ease;
}

.league-step.achieved {
    background: linear-gradient(135deg, rgba(34, 197, 94, 0.1), rgba(16, 185, 129, 0.1));
    border: 1px solid rgba(34, 197, 94, 0.3);
}

.league-step.current {
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(16, 185, 129, 0.1));
    border: 1px solid var(--accent-primary);
}

.league-icon-container {
    position: relative;
}

.league-step .league-icon {
    width: 48px;
    height: 48px;
}

.current-indicator {
    position: absolute;
    top: -8px;
    right: -8px;
    background: var(--accent-primary);
    color: white;
    font-size: 0.7rem;
    padding: 0.25rem 0.5rem;
    border-radius: var(--border-radius);
    font-weight: 600;
}

.league-step .league-name {
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 0.25rem;
}

.achieved-text {
    color: var(--accent-success);
    font-weight: 600;
    font-size: 0.9rem;
}

.requirement-text {
    color: var(--text-secondary);
    font-size: 0.9rem;
}

/* Records Section */
.records-section {
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: var(--border-radius-lg);
    padding: 2rem;
}

.records-list {
    space-y: 1rem;
}

.record-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: var(--bg-secondary);
    border-radius: var(--border-radius);
    transition: all 0.3s ease;
}

.record-item:hover {
    transform: translateX(4px);
    background: var(--border-light);
}

.record-icon {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2rem;
}

.record-icon.speed { background: var(--accent-primary); }
.record-icon.accuracy { background: var(--accent-success); }
.record-icon.streak { background: var(--accent-warning); }
.record-icon.competition { background: var(--accent-danger); }

.record-title {
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 0.25rem;
}

.record-value {
    font-family: var(--font-display);
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--accent-primary);
    margin-bottom: 0.25rem;
}

.record-context {
    color: var(--text-secondary);
    font-size: 0.8rem;
}

/* Quick Actions */
.quick-actions-section {
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: var(--border-radius-lg);
    padding: 2rem;
}

.quick-actions-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
}

.quick-action-card {
    background: var(--bg-secondary);
    border: 1px solid var(--border-light);
    border-radius: var(--border-radius);
    padding: 1.5rem;
    text-align: center;
    text-decoration: none;
    transition: all 0.3s ease;
    color: var(--text-primary);
}

.quick-action-card:hover {
    background: var(--accent-primary);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}

.action-icon {
    font-size: 1.5rem;
    margin-bottom: 0.75rem;
}

.action-text {
    font-weight: 600;
    font-size: 0.9rem;
}

/* Responsive Design */
@media (max-width: 1024px) {
    .content-grid {
        grid-template-columns: 1fr;
    }
    
    .banner-content {
        flex-direction: column;
        gap: 2rem;
        text-align: center;
    }
    
    .profile-avatar-section {
        flex-direction: column;
        gap: 1rem;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .profile-container {
        padding: 1rem 0;
    }
    
    .profile-banner {
        padding: 2rem 1rem;
    }
    
    .profile-stats-quick {
        gap: 1.5rem;
    }
    
    .badges-grid {
        grid-template-columns: repeat(4, 1fr);
    }
    
    .quick-actions-grid {
        grid-template-columns: 1fr;
    }
    
    .chart-controls {
        flex-wrap: wrap;
    }
}
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize performance chart
    initializePerformanceChart();
    
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Chart period switching
    document.querySelectorAll('.chart-period').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.chart-period').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            // Update chart data based on period
            updateChartData(this.dataset.period);
        });
    });
});

function initializePerformanceChart() {
    const ctx = document.getElementById('performance-chart');
    if (!ctx) return;
    
    // Sample data - in real app this would come from backend
    const chartData = {
        labels: ['Day 1', 'Day 2', 'Day 3', 'Day 4', 'Day 5', 'Day 6', 'Day 7'],
        datasets: [{
            label: 'WPM',
            data: [45, 48, 52, 49, 55, 58, 62],
            borderColor: 'rgb(59, 130, 246)',
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
            tension: 0.4,
            fill: true
        }, {
            label: 'Accuracy (%)',
            data: [92, 94, 89, 96, 93, 97, 95],
            borderColor: 'rgb(34, 197, 94)',
            backgroundColor: 'rgba(34, 197, 94, 0.1)',
            tension: 0.4,
            fill: true
        }]
    };
    
    window.performanceChart = new Chart(ctx, {
        type: 'line',
        data: chartData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                intersect: false,
                mode: 'index'
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0,0,0,0.1)'
                    }
                },
                x: {
                    grid: {
                        color: 'rgba(0,0,0,0.1)'
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                    align: 'end'
                }
            }
        }
    });
}

function updateChartData(period) {
    if (!window.performanceChart) return;
    
    // Sample data for different periods
    const periodData = {
        '7d': {
            labels: ['Day 1', 'Day 2', 'Day 3', 'Day 4', 'Day 5', 'Day 6', 'Day 7'],
            wpm: [45, 48, 52, 49, 55, 58, 62],
            accuracy: [92, 94, 89, 96, 93, 97, 95]
        },
        '30d': {
            labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
            wpm: [48, 52, 58, 65],
            accuracy: [91, 93, 95, 96]
        },
        '90d': {
            labels: ['Month 1', 'Month 2', 'Month 3'],
            wpm: [45, 55, 68],
            accuracy: [88, 93, 97]
        }
    };
    
    const data = periodData[period];
    window.performanceChart.data.labels = data.labels;
    window.performanceChart.data.datasets[0].data = data.wpm;
    window.performanceChart.data.datasets[1].data = data.accuracy;
    window.performanceChart.update();
}

// Global functions
function shareProfile() {
    if (navigator.share) {
        navigator.share({
            title: 'Check out my SportTyping profile!',
            text: 'See my typing progress and achievements',
            url: window.location.href
        });
    } else {
        // Fallback - copy to clipboard
        navigator.clipboard.writeText(window.location.href).then(() => {
            alert('Profile link copied to clipboard!');
        });
    }
}

function challengeUser() {
    // In real app, this would create a challenge
    alert('Challenge feature coming soon!');
}

function followUser() {
    // In real app, this would follow/unfollow user
    alert('Follow feature coming soon!');
}
</script>
@endsection