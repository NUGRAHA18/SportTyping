@extends('layouts.app')

@section('content')
<div class="profile-container">
    <div class="container">
        <!-- Profile Header -->
        <div class="profile-header">
            <div class="profile-avatar-section">
                <div class="avatar-container">
                    @if(Auth::user()->profile->avatar)
                        <img src="{{ Storage::url(Auth::user()->profile->avatar) }}" alt="{{ Auth::user()->username }}" class="profile-avatar">
                    @else
                        <div class="avatar-placeholder">
                            {{ substr(Auth::user()->username, 0, 2) }}
                        </div>
                    @endif
                    <div class="avatar-status online">
                        <i class="fas fa-circle"></i>
                    </div>
                </div>
                <div class="profile-basic-info">
                    <h1>{{ Auth::user()->username }}</h1>
                    <p>{{ Auth::user()->email }}</p>
                    <div class="profile-meta">
                        <span class="member-since">
                            <i class="fas fa-calendar"></i>
                            Member since {{ Auth::user()->created_at->format('M Y') }}
                        </span>
                        <span class="last-active">
                            <i class="fas fa-clock"></i>
                            Last active {{ Auth::user()->updated_at->diffForHumans() }}
                        </span>
                    </div>
                </div>
            </div>
            
            <div class="profile-actions">
                <a href="{{ route('profile.edit') }}" class="btn btn-primary">
                    <i class="fas fa-edit"></i>
                    Edit Profile
                </a>
                <button class="btn btn-outline-primary" onclick="shareProfile()">
                    <i class="fas fa-share"></i>
                    Share Profile
                </button>
            </div>
        </div>

        <!-- League & Experience -->
        <div class="league-section">
            <div class="current-league">
                <div class="league-badge">
                    <div class="league-icon">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <div class="league-info">
                        <h3>{{ Auth::user()->profile->league->name ?? 'Novice' }} League</h3>
                        <p>{{ Auth::user()->profile->league->description ?? 'Starting your typing journey' }}</p>
                    </div>
                </div>
                <div class="xp-progress">
                    <div class="xp-info">
                        <span class="current-xp">{{ number_format(Auth::user()->profile->total_experience ?? 0) }} XP</span>
                        <span class="next-league">Next: {{ Auth::user()->profile->league->next_league ?? 'Apprentice' }}</span>
                    </div>
                    <div class="xp-bar">
                        <div class="xp-fill" style="width: {{ min(100, (Auth::user()->profile->total_experience ?? 0) % 1000 / 10) }}%"></div>
                    </div>
                    <div class="xp-needed">
                        {{ 1000 - ((Auth::user()->profile->total_experience ?? 0) % 1000) }} XP to next league
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Overview -->
        <div class="stats-overview">
            <div class="stats-grid">
                <div class="stat-card primary">
                    <div class="stat-icon">
                        <i class="fas fa-tachometer-alt"></i>
                    </div>
                    <div class="stat-content">
                        <h3>{{ Auth::user()->profile->typing_speed_avg ?? 0 }}</h3>
                        <p>Average WPM</p>
                        <div class="stat-detail">
                            Best: {{ Auth::user()->profile->best_wpm ?? 0 }} WPM
                        </div>
                    </div>
                </div>

                <div class="stat-card secondary">
                    <div class="stat-icon">
                        <i class="fas fa-bullseye"></i>
                    </div>
                    <div class="stat-content">
                        <h3>{{ Auth::user()->profile->typing_accuracy_avg ?? 0 }}%</h3>
                        <p>Average Accuracy</p>
                        <div class="stat-detail">
                            Best: {{ Auth::user()->profile->best_accuracy ?? 0 }}%
                        </div>
                    </div>
                </div>

                <div class="stat-card success">
                    <div class="stat-icon">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <div class="stat-content">
                        <h3>{{ Auth::user()->competitions()->count() }}</h3>
                        <p>Competitions Joined</p>
                        <div class="stat-detail">
                            {{ Auth::user()->profile->total_wins ?? 0 }} wins
                        </div>
                    </div>
                </div>

                <div class="stat-card warning">
                    <div class="stat-icon">
                        <i class="fas fa-fire"></i>
                    </div>
                    <div class="stat-content">
                        <h3>{{ Auth::user()->profile->practice_streak ?? 0 }}</h3>
                        <p>Day Streak</p>
                        <div class="stat-detail">
                            {{ Auth::user()->practices()->count() }} total sessions
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Grid -->
        <div class="profile-content">
            <!-- Achievements & Badges -->
            <div class="achievements-section">
                <div class="section-header">
                    <h2><i class="fas fa-medal"></i> Achievements & Badges</h2>
                    <a href="{{ route('badges.index') }}" class="view-all-link">
                        View All <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                
                <div class="badges-showcase">
                    @forelse(Auth::user()->badges()->latest()->limit(6)->get() as $badge)
                    <div class="badge-item {{ $badge->rarity }}">
                        <div class="badge-icon">
                            <i class="fas fa-medal"></i>
                        </div>
                        <div class="badge-info">
                            <h4>{{ $badge->name }}</h4>
                            <p>{{ $badge->description }}</p>
                            <span class="badge-date">{{ $badge->pivot->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                    @empty
                    <div class="no-badges">
                        <i class="fas fa-trophy"></i>
                        <p>No badges earned yet. Start practicing to unlock achievements!</p>
                        <a href="{{ route('practice.index') }}" class="btn btn-primary">
                            <i class="fas fa-keyboard"></i>
                            Start Practicing
                        </a>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="activity-section">
                <div class="section-header">
                    <h2><i class="fas fa-activity"></i> Recent Activity</h2>
                    <div class="activity-filter">
                        <select id="activityFilter" onchange="filterActivity()">
                            <option value="all">All Activity</option>
                            <option value="practices">Practices</option>
                            <option value="competitions">Competitions</option>
                            <option value="achievements">Achievements</option>
                        </select>
                    </div>
                </div>
                
                <div class="activity-timeline">
                    @php
                        $activities = collect();
                        
                        // Add recent practices
                        Auth::user()->practices()->latest()->limit(3)->get()->each(function($practice) use ($activities) {
                            $activities->push((object)[
                                'type' => 'practice',
                                'title' => 'Completed Practice Session',
                                'description' => $practice->wpm . ' WPM • ' . $practice->accuracy . '% accuracy',
                                'time' => $practice->created_at,
                                'icon' => 'keyboard'
                            ]);
                        });
                        
                        // Add recent competitions
                        Auth::user()->competitions()->latest()->limit(2)->get()->each(function($competition) use ($activities) {
                            $activities->push((object)[
                                'type' => 'competition',
                                'title' => 'Joined Competition',
                                'description' => $competition->title,
                                'time' => $competition->pivot->created_at,
                                'icon' => 'racing-flag'
                            ]);
                        });
                        
                        // Add recent badges
                        Auth::user()->badges()->latest()->limit(2)->get()->each(function($badge) use ($activities) {
                            $activities->push((object)[
                                'type' => 'achievement',
                                'title' => 'Earned Achievement',
                                'description' => $badge->name,
                                'time' => $badge->pivot->created_at,
                                'icon' => 'medal'
                            ]);
                        });
                        
                        $activities = $activities->sortByDesc('time')->take(8);
                    @endphp
                    
                    @forelse($activities as $activity)
                    <div class="activity-item {{ $activity->type }}">
                        <div class="activity-icon">
                            <i class="fas fa-{{ $activity->icon }}"></i>
                        </div>
                        <div class="activity-content">
                            <h4>{{ $activity->title }}</h4>
                            <p>{{ $activity->description }}</p>
                            <span class="activity-time">{{ $activity->time->diffForHumans() }}</span>
                        </div>
                    </div>
                    @empty
                    <div class="no-activity">
                        <i class="fas fa-history"></i>
                        <p>No recent activity. Start typing to build your activity timeline!</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Performance Charts -->
        <div class="performance-section">
            <div class="section-header">
                <h2><i class="fas fa-chart-line"></i> Performance Analysis</h2>
                <div class="chart-controls">
                    <button class="chart-btn active" data-period="week">Week</button>
                    <button class="chart-btn" data-period="month">Month</button>
                    <button class="chart-btn" data-period="year">Year</button>
                </div>
            </div>
            
            <div class="charts-grid">
                <div class="chart-card">
                    <h3>Typing Speed Trend</h3>
                    <div class="chart-container">
                        <canvas id="speedChart" width="400" height="200"></canvas>
                    </div>
                </div>
                
                <div class="chart-card">
                    <h3>Accuracy Progress</h3>
                    <div class="chart-container">
                        <canvas id="accuracyChart" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Social & Comparison -->
        <div class="social-section">
            <div class="leaderboard-position">
                <div class="position-card">
                    <div class="position-icon">
                        <i class="fas fa-crown"></i>
                    </div>
                    <div class="position-content">
                        <h3>Global Ranking</h3>
                        <div class="rank-info">
                            <span class="rank-number">#{{ Auth::user()->profile->global_rank ?? 'Unranked' }}</span>
                            <span class="rank-detail">of {{ \App\Models\User::count() }} users</span>
                        </div>
                        <a href="{{ route('leaderboards.index') }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-list"></i>
                            View Leaderboard
                        </a>
                    </div>
                </div>
            </div>

            <div class="comparison-card">
                <h3>Compare Performance</h3>
                <div class="comparison-stats">
                    <div class="comparison-item">
                        <span class="comparison-label">vs Global Average</span>
                        <div class="comparison-bar">
                            <div class="comparison-fill user" style="width: {{ min(100, (Auth::user()->profile->typing_speed_avg ?? 0) / 50 * 100) }}%"></div>
                        </div>
                        <span class="comparison-value">{{ Auth::user()->profile->typing_speed_avg ?? 0 }} WPM</span>
                    </div>
                    <div class="comparison-item">
                        <span class="comparison-label">vs League Average</span>
                        <div class="comparison-bar">
                            <div class="comparison-fill league" style="width: {{ min(100, (Auth::user()->profile->typing_speed_avg ?? 0) / 40 * 100) }}%"></div>
                        </div>
                        <span class="comparison-value">{{ Auth::user()->profile->typing_speed_avg ?? 0 }} WPM</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.profile-container {
    padding: 2rem 0;
    min-height: calc(100vh - 80px);
}

/* Profile Header */
.profile-header {
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

.profile-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: var(--gradient-accent);
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
    object-fit: cover;
    border: 4px solid rgba(255, 255, 255, 0.1);
}

.avatar-placeholder {
    width: 120px;
    height: 120px;
    background: var(--gradient-button);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.5rem;
    font-weight: 700;
    color: white;
    border: 4px solid rgba(255, 255, 255, 0.1);
}

.avatar-status {
    position: absolute;
    bottom: 8px;
    right: 8px;
    width: 24px;
    height: 24px;
    background: var(--success);
    border-radius: 50%;
    border: 3px solid var(--bg-primary);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 0.8rem;
}

.profile-basic-info h1 {
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 0.5rem;
}

.profile-basic-info p {
    color: var(--text-secondary);
    font-size: 1.1rem;
    margin-bottom: 1rem;
}

.profile-meta {
    display: flex;
    gap: 2rem;
    flex-wrap: wrap;
}

.member-since, .last-active {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--text-secondary);
    font-size: 0.9rem;
}

.member-since i, .last-active i {
    color: var(--accent-pink);
}

.profile-actions {
    display: flex;
    gap: 1rem;
}

/* League Section */
.league-section {
    margin-bottom: 3rem;
}

.current-league {
    background: var(--gradient-card);
    backdrop-filter: blur(var(--blur-amount));
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius-lg);
    padding: 2.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 3rem;
}

.league-badge {
    display: flex;
    align-items: center;
    gap: 1.5rem;
}

.league-icon {
    width: 80px;
    height: 80px;
    background: var(--gradient-button);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: white;
    box-shadow: 0 8px 25px rgba(255, 107, 157, 0.3);
}

.league-info h3 {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 0.5rem;
}

.league-info p {
    color: var(--text-secondary);
    font-size: 1rem;
}

.xp-progress {
    text-align: right;
    min-width: 250px;
}

.xp-info {
    display: flex;
    justify-content: space-between;
    margin-bottom: 0.5rem;
}

.current-xp {
    font-size: 1.2rem;
    font-weight: 700;
    color: var(--accent-pink);
}

.next-league {
    color: var(--text-secondary);
    font-size: 0.9rem;
}

.xp-bar {
    width: 100%;
    height: 8px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 4px;
    overflow: hidden;
    margin-bottom: 0.5rem;
}

.xp-fill {
    height: 100%;
    background: var(--gradient-button);
    border-radius: 4px;
    transition: width 0.5s ease;
}

.xp-needed {
    color: var(--text-secondary);
    font-size: 0.85rem;
}

/* Statistics Overview */
.stats-overview {
    margin-bottom: 3rem;
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
    transform: translateY(-3px);
    box-shadow: 0 8px 30px rgba(139, 92, 246, 0.15);
}

.stat-card.primary { border-color: rgba(255, 107, 157, 0.3); }
.stat-card.secondary { border-color: rgba(0, 212, 255, 0.3); }
.stat-card.success { border-color: rgba(16, 185, 129, 0.3); }
.stat-card.warning { border-color: rgba(245, 158, 11, 0.3); }

.stat-card .stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
}

.stat-card.primary .stat-icon { background: var(--gradient-button); }
.stat-card.secondary .stat-icon { background: linear-gradient(45deg, #00d4ff, #0ea5e9); }
.stat-card.success .stat-icon { background: linear-gradient(45deg, #10b981, #059669); }
.stat-card.warning .stat-icon { background: linear-gradient(45deg, #f59e0b, #eab308); }

.stat-card .stat-content h3 {
    font-size: 2rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 0.25rem;
    font-family: 'Courier New', monospace;
}

.stat-card .stat-content p {
    color: var(--text-secondary);
    font-size: 0.9rem;
    margin-bottom: 0.5rem;
}

.stat-detail {
    color: var(--text-muted);
    font-size: 0.85rem;
}

/* Section Headers */
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

.view-all-link {
    color: var(--accent-pink);
    text-decoration: none;
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
}

.view-all-link:hover {
    color: var(--accent-cyan);
    transform: translateX(3px);
}

/* Profile Content Grid */
.profile-content {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 3rem;
    margin-bottom: 3rem;
}

/* Achievements Section */
.achievements-section {
    background: var(--gradient-card);
    backdrop-filter: blur(var(--blur-amount));
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius-lg);
    padding: 2rem;
}

.badges-showcase {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.badge-item {
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius);
    padding: 1.5rem;
    text-align: center;
    transition: all 0.3s ease;
    position: relative;
}

.badge-item:hover {
    transform: translateY(-3px);
    border-color: var(--accent-pink);
}

.badge-item.rare { border-color: rgba(59, 130, 246, 0.3); }
.badge-item.epic { border-color: rgba(139, 92, 246, 0.3); }
.badge-item.legendary { border-color: rgba(245, 158, 11, 0.3); }

.badge-icon {
    width: 50px;
    height: 50px;
    background: var(--gradient-button);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    font-size: 1.2rem;
    color: white;
}

.badge-info h4 {
    color: var(--text-primary);
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.badge-info p {
    color: var(--text-secondary);
    font-size: 0.85rem;
    margin-bottom: 0.5rem;
}

.badge-date {
    color: var(--text-muted);
    font-size: 0.8rem;
}

.no-badges {
    grid-column: 1 / -1;
    text-align: center;
    padding: 3rem 2rem;
    color: var(--text-secondary);
}

.no-badges i {
    font-size: 3rem;
    color: var(--text-muted);
    margin-bottom: 1rem;
}

/* Activity Section */
.activity-section {
    background: var(--gradient-card);
    backdrop-filter: blur(var(--blur-amount));
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius-lg);
    padding: 2rem;
}

.activity-filter select {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius);
    color: var(--text-primary);
    padding: 0.5rem 1rem;
    font-size: 0.9rem;
}

.activity-timeline {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.activity-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: rgba(255, 255, 255, 0.03);
    border-radius: var(--border-radius);
    border: 1px solid rgba(255, 255, 255, 0.05);
    transition: all 0.3s ease;
}

.activity-item:hover {
    background: rgba(255, 255, 255, 0.06);
    border-color: var(--accent-pink);
}

.activity-item.practice { border-left: 3px solid var(--accent-pink); }
.activity-item.competition { border-left: 3px solid var(--info); }
.activity-item.achievement { border-left: 3px solid var(--success); }

.activity-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
}

.activity-item.practice .activity-icon { background: var(--gradient-button); }
.activity-item.competition .activity-icon { background: linear-gradient(45deg, #00d4ff, #0ea5e9); }
.activity-item.achievement .activity-icon { background: linear-gradient(45deg, #10b981, #059669); }

.activity-content h4 {
    color: var(--text-primary);
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.activity-content p {
    color: var(--text-secondary);
    font-size: 0.9rem;
    margin-bottom: 0.25rem;
}

.activity-time {
    color: var(--text-muted);
    font-size: 0.8rem;
}

.no-activity {
    text-align: center;
    padding: 3rem 2rem;
    color: var(--text-secondary);
}

.no-activity i {
    font-size: 2rem;
    color: var(--text-muted);
    margin-bottom: 1rem;
}

/* Performance Section */
.performance-section {
    margin-bottom: 3rem;
}

.chart-controls {
    display: flex;
    gap: 0.5rem;
}

.chart-btn {
    padding: 0.5rem 1rem;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius);
    color: var(--text-secondary);
    cursor: pointer;
    transition: all 0.3s ease;
}

.chart-btn.active, .chart-btn:hover {
    background: var(--gradient-button);
    border-color: var(--accent-pink);
    color: white;
}

.charts-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
}

.chart-card {
    background: var(--gradient-card);
    backdrop-filter: blur(var(--blur-amount));
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius-lg);
    padding: 2rem;
}

.chart-card h3 {
    color: var(--text-primary);
    font-weight: 600;
    margin-bottom: 1.5rem;
}

.chart-container {
    height: 200px;
}

/* Social Section */
.social-section {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
}

.leaderboard-position, .comparison-card {
    background: var(--gradient-card);
    backdrop-filter: blur(var(--blur-amount));
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius-lg);
    padding: 2rem;
}

.position-card {
    display: flex;
    align-items: center;
    gap: 1.5rem;
}

.position-icon {
    width: 60px;
    height: 60px;
    background: var(--gradient-button);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
}

.position-content h3 {
    color: var(--text-primary);
    font-weight: 600;
    margin-bottom: 1rem;
}

.rank-info {
    margin-bottom: 1.5rem;
}

.rank-number {
    font-size: 2rem;
    font-weight: 700;
    color: var(--accent-pink);
    font-family: 'Courier New', monospace;
}

.rank-detail {
    color: var(--text-secondary);
    font-size: 0.9rem;
    margin-left: 0.5rem;
}

.comparison-card h3 {
    color: var(--text-primary);
    font-weight: 600;
    margin-bottom: 2rem;
}

.comparison-stats {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.comparison-item {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.comparison-label {
    color: var(--text-secondary);
    font-size: 0.9rem;
    min-width: 120px;
}

.comparison-bar {
    flex: 1;
    height: 8px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 4px;
    overflow: hidden;
}

.comparison-fill {
    height: 100%;
    border-radius: 4px;
    transition: width 0.5s ease;
}

.comparison-fill.user { background: var(--gradient-button); }
.comparison-fill.league { background: linear-gradient(45deg, #00d4ff, #0ea5e9); }

.comparison-value {
    color: var(--text-primary);
    font-weight: 600;
    font-size: 0.9rem;
    min-width: 60px;
    text-align: right;
}

/* Responsive */
@media (max-width: 1024px) {
    .profile-header {
        flex-direction: column;
        text-align: center;
        gap: 2rem;
    }
    
    .current-league {
        flex-direction: column;
        text-align: center;
    }
    
    .xp-progress {
        text-align: center;
        width: 100%;
    }
    
    .profile-content {
        grid-template-columns: 1fr;
    }
    
    .charts-grid, .social-section {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .profile-avatar-section {
        flex-direction: column;
        text-align: center;
    }
    
    .profile-basic-info h1 {
        font-size: 2rem;
    }
    
    .profile-meta {
        justify-content: center;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .badges-showcase {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
function shareProfile() {
    if (navigator.share) {
        navigator.share({
            title: 'My SportTyping Profile',
            text: 'Check out my typing stats on SportTyping!',
            url: window.location.href
        });
    } else {
        // Fallback to copying URL
        navigator.clipboard.writeText(window.location.href).then(() => {
            alert('Profile URL copied to clipboard!');
        });
    }
}

function filterActivity() {
    const filter = document.getElementById('activityFilter').value;
    const items = document.querySelectorAll('.activity-item');
    
    items.forEach(item => {
        if (filter === 'all' || item.classList.contains(filter.slice(0, -1))) {
            item.style.display = 'flex';
        } else {
            item.style.display = 'none';
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    // Chart controls
    const chartBtns = document.querySelectorAll('.chart-btn');
    chartBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            chartBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            // Update charts based on period
            const period = this.dataset.period;
            updateCharts(period);
        });
    });
    
    function updateCharts(period) {
        // This would update the actual charts with new data
        console.log(Updating charts for ${period});
    }
    
    // Initialize charts
    updateCharts('week');
});
</script>
@endsection