@extends('layouts.app')

@section('content')
<div class="dashboard-container">
    <div class="container">
        <!-- Welcome Header -->
        <div class="welcome-header">
            <div class="welcome-content">
                <h1>Welcome back, <span class="gradient-text">{{ Auth::user()->username }}</span>!</h1>
                <p>Ready to improve your typing skills? Let's see your progress.</p>
            </div>
            <div class="current-league">
                <div class="league-badge">
                    <i class="fas fa-trophy"></i>
                    <div class="league-info">
                        <span class="league-name">{{ Auth::user()->profile->league->name ?? 'Novice' }}</span>
                        <span class="league-level">Current League</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Overview -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon typing-speed">
                    <i class="fas fa-tachometer-alt"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ number_format(Auth::user()->profile->typing_speed_avg ?? 0, 1) }}</h3>
                    <p>Average WPM</p>
                    <div class="stat-trend">
                        <i class="fas fa-arrow-up"></i>
                        <span>+2.3 from last week</span>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon accuracy">
                    <i class="fas fa-bullseye"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ number_format(Auth::user()->profile->typing_accuracy_avg ?? 0, 1) }}%</h3>
                    <p>Accuracy Rate</p>
                    <div class="stat-trend">
                        <i class="fas fa-arrow-up"></i>
                        <span>+1.2% this month</span>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon experience">
                    <i class="fas fa-star"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ number_format(Auth::user()->profile->total_experience ?? 0) }}</h3>
                    <p>Total Experience</p>
                    <div class="stat-trend">
                        <i class="fas fa-arrow-up"></i>
                        <span>+150 XP today</span>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon competitions">
                    <i class="fas fa-medal"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ Auth::user()->profile->total_competitions ?? 0 }}</h3>
                    <p>Competitions</p>
                    <div class="stat-trend">
                        <i class="fas fa-trophy"></i>
                        <span>{{ Auth::user()->profile->total_wins ?? 0 }} wins</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="main-grid">
            <!-- Quick Actions -->
            <div class="quick-actions-card">
                <div class="card-header">
                    <h3>Quick Actions</h3>
                    <p>Jump into action</p>
                </div>
                <div class="actions-grid">
                    <a href="{{ route('competitions.index') }}" class="action-item competition">
                        <div class="action-icon">
                            <i class="fas fa-racing-flag"></i>
                        </div>
                        <div class="action-content">
                            <h4>Join Competition</h4>
                            <p>Race against others</p>
                        </div>
                        <i class="fas fa-arrow-right action-arrow"></i>
                    </a>

                    <a href="{{ route('practice.index') }}" class="action-item practice">
                        <div class="action-icon">
                            <i class="fas fa-keyboard"></i>
                        </div>
                        <div class="action-content">
                            <h4>Practice Typing</h4>
                            <p>Improve your skills</p>
                        </div>
                        <i class="fas fa-arrow-right action-arrow"></i>
                    </a>

                    <a href="{{ route('lessons.index') }}" class="action-item lessons">
                        <div class="action-icon">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <div class="action-content">
                            <h4>Take Lessons</h4>
                            <p>Learn 10-finger typing</p>
                        </div>
                        <i class="fas fa-arrow-right action-arrow"></i>
                    </a>

                    <a href="{{ route('leaderboards.index') }}" class="action-item leaderboard">
                        <div class="action-icon">
                            <i class="fas fa-crown"></i>
                        </div>
                        <div class="action-content">
                            <h4>Leaderboards</h4>
                            <p>See your ranking</p>
                        </div>
                        <i class="fas fa-arrow-right action-arrow"></i>
                    </a>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="activity-card">
                <div class="card-header">
                    <h3>Recent Activity</h3>
                    <button class="refresh-btn" onclick="refreshActivity()">
                        <i class="fas fa-refresh"></i>
                    </button>
                </div>
                <div class="activity-list" id="activityList">
                    <div class="activity-loading">
                        <i class="fas fa-spinner fa-spin"></i>
                        <span>Loading recent activity...</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Progress Section -->
        <div class="progress-section">
            <div class="progress-card">
                <div class="card-header">
                    <h3>Learning Progress</h3>
                    <p>{{ $completedLessons ?? 0 }}/{{ $totalLessons ?? 0 }} lessons completed</p>
                </div>
                <div class="progress-content">
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: {{ $totalLessons > 0 ? ($completedLessons / $totalLessons) * 100 : 0 }}%"></div>
                    </div>
                    <div class="progress-stats">
                        <div class="progress-stat">
                            <span class="stat-value">{{ round($totalLessons > 0 ? ($completedLessons / $totalLessons) * 100 : 0) }}%</span>
                            <span class="stat-label">Complete</span>
                        </div>
                        <div class="progress-stat">
                            <span class="stat-value">{{ $totalLessons - $completedLessons }}</span>
                            <span class="stat-label">Remaining</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Achievements Preview -->
            <div class="achievements-card">
                <div class="card-header">
                    <h3>Latest Achievements</h3>
                    <a href="{{ route('badges.index') }}" class="view-all-link">
                        View All <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                <div class="achievements-grid">
                    @forelse(Auth::user()->badges()->latest()->limit(4)->get() as $badge)
                        <div class="achievement-item">
                            <div class="achievement-icon">
                                <i class="fas fa-medal"></i>
                            </div>
                            <div class="achievement-info">
                                <h4>{{ $badge->name }}</h4>
                                <p>{{ $badge->description }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="no-achievements">
                            <i class="fas fa-trophy"></i>
                            <p>No achievements yet. Start typing to earn your first badge!</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.dashboard-container {
    padding: 2rem 0;
    min-height: calc(100vh - 80px);
}

/* Welcome Header */
.welcome-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 3rem;
    padding: 2rem;
    background: var(--gradient-card);
    backdrop-filter: blur(var(--blur-amount));
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius-lg);
    position: relative;
    overflow: hidden;
}

.welcome-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: var(--gradient-accent);
}

.welcome-content h1 {
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 0.5rem;
}

.gradient-text {
    background: var(--gradient-accent);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.welcome-content p {
    color: var(--text-secondary);
    font-size: 1.1rem;
}

.current-league {
    text-align: right;
}

.league-badge {
    display: flex;
    align-items: center;
    gap: 1rem;
    background: rgba(255, 107, 157, 0.1);
    padding: 1rem 1.5rem;
    border-radius: var(--border-radius);
    border: 1px solid rgba(255, 107, 157, 0.2);
}

.league-badge i {
    font-size: 2rem;
    color: var(--accent-pink);
}

.league-info {
    display: flex;
    flex-direction: column;
}

.league-name {
    font-size: 1.2rem;
    font-weight: 600;
    color: var(--text-primary);
}

.league-level {
    font-size: 0.9rem;
    color: var(--text-secondary);
}

/* Stats Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-bottom: 3rem;
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
    transform: translateY(-5px);
    box-shadow: 0 12px 40px rgba(139, 92, 246, 0.2);
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
}

.stat-icon.typing-speed { background: linear-gradient(45deg, #ff6b9d, #c084fc); }
.stat-icon.accuracy { background: linear-gradient(45deg, #00d4ff, #0ea5e9); }
.stat-icon.experience { background: linear-gradient(45deg, #f59e0b, #eab308); }
.stat-icon.competitions { background: linear-gradient(45deg, #10b981, #059669); }

.stat-content h3 {
    font-size: 2rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 0.25rem;
}

.stat-content p {
    color: var(--text-secondary);
    font-size: 0.95rem;
    margin-bottom: 0.5rem;
}

.stat-trend {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.85rem;
    color: var(--success);
}

/* Main Grid */
.main-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
    margin-bottom: 3rem;
}

/* Quick Actions Card */
.quick-actions-card {
    background: var(--gradient-card);
    backdrop-filter: blur(var(--blur-amount));
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius-lg);
    padding: 2rem;
}

.card-header {
    margin-bottom: 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.card-header h3 {
    font-size: 1.3rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 0.25rem;
}

.card-header p {
    color: var(--text-secondary);
    font-size: 0.9rem;
}

.actions-grid {
    display: grid;
    gap: 1rem;
}

.action-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.5rem;
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: var(--border-radius);
    text-decoration: none;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.action-item:hover {
    background: rgba(255, 255, 255, 0.06);
    border-color: var(--accent-pink);
    transform: translateX(5px);
}

.action-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    color: white;
}

.action-item.competition .action-icon { background: var(--gradient-button); }
.action-item.practice .action-icon { background: linear-gradient(45deg, #00d4ff, #0ea5e9); }
.action-item.lessons .action-icon { background: linear-gradient(45deg, #f59e0b, #eab308); }
.action-item.leaderboard .action-icon { background: linear-gradient(45deg, #10b981, #059669); }

.action-content {
    flex: 1;
}

.action-content h4 {
    color: var(--text-primary);
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.action-content p {
    color: var(--text-secondary);
    font-size: 0.9rem;
}

.action-arrow {
    color: var(--text-muted);
    transition: all 0.3s ease;
}

.action-item:hover .action-arrow {
    color: var(--accent-pink);
    transform: translateX(5px);
}

/* Activity Card */
.activity-card {
    background: var(--gradient-card);
    backdrop-filter: blur(var(--blur-amount));
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius-lg);
    padding: 2rem;
}

.refresh-btn {
    background: none;
    border: none;
    color: var(--text-secondary);
    cursor: pointer;
    padding: 0.5rem;
    border-radius: 50%;
    transition: all 0.3s ease;
}

.refresh-btn:hover {
    color: var(--accent-pink);
    background: rgba(255, 107, 157, 0.1);
}

.activity-loading {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1rem;
    padding: 3rem;
    color: var(--text-secondary);
}

.activity-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem 0;
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
}

.activity-item:last-child {
    border-bottom: none;
}

.activity-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    color: white;
}

.activity-icon.competition { background: var(--gradient-button); }
.activity-icon.practice { background: linear-gradient(45deg, #00d4ff, #0ea5e9); }

.activity-content {
    flex: 1;
}

.activity-content h4 {
    color: var(--text-primary);
    font-size: 0.95rem;
    margin-bottom: 0.25rem;
}

.activity-content p {
    color: var(--text-secondary);
    font-size: 0.85rem;
}

.activity-stats {
    text-align: right;
    font-size: 0.85rem;
}

.activity-wpm {
    color: var(--accent-pink);
    font-weight: 600;
}

.activity-accuracy {
    color: var(--text-secondary);
}

/* Progress Section */
.progress-section {
    display: grid;
    grid-template-columns: 1.5fr 1fr;
    gap: 2rem;
}

.progress-card, .achievements-card {
    background: var(--gradient-card);
    backdrop-filter: blur(var(--blur-amount));
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius-lg);
    padding: 2rem;
}

.progress-bar {
    width: 100%;
    height: 8px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 4px;
    overflow: hidden;
    margin-bottom: 1.5rem;
}

.progress-fill {
    height: 100%;
    background: var(--gradient-button);
    border-radius: 4px;
    transition: width 0.5s ease;
}

.progress-stats {
    display: flex;
    justify-content: space-between;
}

.progress-stat {
    text-align: center;
}

.stat-value {
    display: block;
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text-primary);
}

.stat-label {
    font-size: 0.85rem;
    color: var(--text-secondary);
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

.achievements-grid {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.achievement-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: rgba(255, 255, 255, 0.03);
    border-radius: var(--border-radius);
    border: 1px solid rgba(255, 255, 255, 0.05);
}

.achievement-icon {
    width: 40px;
    height: 40px;
    background: var(--gradient-button);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
}

.achievement-info h4 {
    color: var(--text-primary);
    font-size: 0.95rem;
    margin-bottom: 0.25rem;
}

.achievement-info p {
    color: var(--text-secondary);
    font-size: 0.85rem;
}

.no-achievements {
    text-align: center;
    padding: 2rem;
    color: var(--text-secondary);
}

.no-achievements i {
    font-size: 2rem;
    color: var(--text-muted);
    margin-bottom: 1rem;
}

/* Responsive */
@media (max-width: 1024px) {
    .main-grid, .progress-section {
        grid-template-columns: 1fr;
    }
    
    .welcome-header {
        flex-direction: column;
        text-align: center;
        gap: 2rem;
    }
}

@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .welcome-content h1 {
        font-size: 2rem;
    }
    
    .dashboard-container {
        padding: 1rem 0;
    }
    
    .quick-actions-card, .activity-card, .progress-card, .achievements-card {
        padding: 1.5rem;
    }
}
</style>

<script>
async function refreshActivity() {
    const activityList = document.getElementById('activityList');
    const refreshBtn = document.querySelector('.refresh-btn i');
    
    // Add loading state
    refreshBtn.classList.add('fa-spin');
    activityList.innerHTML = `
        <div class="activity-loading">
            <i class="fas fa-spinner fa-spin"></i>
            <span>Loading recent activity...</span>
        </div>
    `;
    
    try {
        const response = await fetch('/dashboard/recent-activity');
        const data = await response.json();
        
        if (data.success && data.data.length > 0) {
            let html = '';
            data.data.forEach(activity => {
                const iconClass = activity.type === 'competition' ? 'competition' : 'practice';
                const iconName = activity.type === 'competition' ? 'fa-trophy' : 'fa-keyboard';
                
                html += `
                    <div class="activity-item">
                        <div class="activity-icon ${iconClass}">
                            <i class="fas ${iconName}"></i>
                        </div>
                        <div class="activity-content">
                            <h4>${activity.title}</h4>
                            <p>${new Date(activity.date).toLocaleDateString()}</p>
                        </div>
                        <div class="activity-stats">
                            <div class="activity-wmp">${activity.wpm} WPM</div>
                            <div class="activity-accuracy">${activity.accuracy}% accuracy</div>
                        </div>
                    </div>
                `;
            });
            activityList.innerHTML = html;
        } else {
            activityList.innerHTML = `
                <div class="no-activity">
                    <i class="fas fa-history"></i>
                    <p>No recent activity. Start typing to see your progress here!</p>
                </div>
            `;
        }
    } catch (error) {
        activityList.innerHTML = `
            <div class="activity-error">
                <i class="fas fa-exclamation-triangle"></i>
                <p>Failed to load activity. Please try again.</p>
            </div>
        `;
    }
    
    // Remove loading state
    refreshBtn.classList.remove('fa-spin');
}

document.addEventListener('DOMContentLoaded', function() {
    // Load activity on page load
    refreshActivity();
    
    // Add loading animations to stats cards
    const statCards = document.querySelectorAll('.stat-card');
    statCards.forEach((card, index) => {
        card.style.animationDelay = ${index * 0.1}s;
        card.style.animation = 'slideInUp 0.6s ease forwards';
    });
});

// CSS Animation keyframes
const style = document.createElement('style');
style.textContent = `
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
    
    .no-activity, .activity-error {
        text-align: center;
        padding: 2rem;
        color: var(--text-secondary);
    }
    
    .no-activity i, .activity-error i {
        font-size: 2rem;
        color: var(--text-muted);
        margin-bottom: 1rem;
    }
`;
document.head.appendChild(style);
</script>
@endsection