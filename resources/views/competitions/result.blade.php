{{-- resources/views/competitions/result.blade.php --}}
@extends('layouts.app')

@section('title', 'Competition Result - SportTyping')

@section('content')
<div class="competition-result-container">
    <div class="container">
        <!-- Race Header -->
        <div class="race-header">
            <div class="race-info">
                <h1 class="race-title">{{ $competition->title }}</h1>
                <div class="race-meta">
                    <span class="race-device">
                        <i class="fas fa-{{ $competition->device_type === 'mobile' ? 'mobile-alt' : 'desktop' }}"></i>
                        {{ ucfirst($competition->device_type) }} Race
                    </span>
                    <span class="race-time">
                        <i class="fas fa-clock"></i>
                        {{ $competition->created_at->format('M j, Y H:i') }}
                    </span>
                </div>
            </div>
            
            <!-- Winner Podium -->
            <div class="winner-podium">
                @if($isWinner)
                    <div class="winner-crown">
                        <i class="fas fa-crown"></i>
                        <span>WINNER!</span>
                    </div>
                @endif
                
                <div class="position-badge position-{{ $userPosition ?? 'unranked' }}">
                    @if($userPosition)
                        @if($userPosition === 1)
                            <i class="fas fa-trophy"></i>
                            <span>1st Place</span>
                        @elseif($userPosition === 2)
                            <i class="fas fa-medal"></i>
                            <span>2nd Place</span>
                        @elseif($userPosition === 3)
                            <i class="fas fa-award"></i>
                            <span>3rd Place</span>
                        @else
                            <i class="fas fa-hashtag"></i>
                            <span>{{ $userPosition }}{{ $userPosition % 10 === 1 && $userPosition !== 11 ? 'st' : ($userPosition % 10 === 2 && $userPosition !== 12 ? 'nd' : ($userPosition % 10 === 3 && $userPosition !== 13 ? 'rd' : 'th')) }} Place</span>
                        @endif
                    @else
                        <i class="fas fa-user"></i>
                        <span>Participated</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Personal Performance -->
        <div class="personal-performance">
            <h3 class="section-title">
                <i class="fas fa-user-chart"></i>
                Your Performance
            </h3>
            
            <div class="performance-grid">
                <x-stat-card 
                    icon="fas fa-tachometer-alt"
                    title="Final Speed"
                    :value="$userResult->wpm"
                    unit="WPM"
                    color="primary"
                    size="large"
                    :change="$wpmImprovement ?? null"
                    :changeType="($wpmImprovement ?? 0) > 0 ? 'positive' : 'neutral'"
                />
                
                <x-stat-card 
                    icon="fas fa-bullseye"
                    title="Accuracy"
                    :value="number_format($userResult->accuracy, 1)"
                    unit="%"
                    color="success"
                    size="large"
                />
                
                <x-stat-card 
                    icon="fas fa-stopwatch"
                    title="Completion Time"
                    :value="gmdate('i:s', $userResult->completion_time)"
                    color="info"
                    size="large"
                />
                
                <x-stat-card 
                    icon="fas fa-users"
                    title="Participants"
                    :value="$totalParticipants"
                    color="warning"
                    size="large"
                />
            </div>
        </div>

        <!-- Race Leaderboard -->
        <div class="race-leaderboard">
            <h3 class="section-title">
                <i class="fas fa-list-ol"></i>
                Race Results
            </h3>
            
            <div class="leaderboard-table">
                <div class="table-header">
                    <div class="header-cell position">Rank</div>
                    <div class="header-cell participant">Participant</div>
                    <div class="header-cell wpm">WPM</div>
                    <div class="header-cell accuracy">Accuracy</div>
                    <div class="header-cell time">Time</div>
                    <div class="header-cell status">Status</div>
                </div>
                
                @foreach($results as $index => $result)
                    <div class="table-row {{ $result->user_id === auth()->id() ? 'user-row' : '' }} {{ $result->type === 'bot' ? 'bot-row' : '' }}">
                        <div class="table-cell position">
                            <div class="rank-badge rank-{{ $index + 1 }}">
                                @if($index + 1 === 1)
                                    <i class="fas fa-trophy"></i>
                                @elseif($index + 1 === 2)
                                    <i class="fas fa-medal"></i>
                                @elseif($index + 1 === 3)
                                    <i class="fas fa-award"></i>
                                @else
                                    {{ $index + 1 }}
                                @endif
                            </div>
                        </div>
                        
                        <div class="table-cell participant">
                            <div class="participant-info">
                                @if($result->type === 'bot')
                                    <div class="participant-avatar bot-avatar">
                                        <i class="fas fa-robot"></i>
                                    </div>
                                    <div class="participant-details">
                                        <div class="participant-name">{{ $result->bot_name }}</div>
                                        <div class="participant-type bot-type">
                                            <i class="fas fa-robot"></i>
                                            Bot ({{ ucfirst($result->difficulty_level) }})
                                        </div>
                                    </div>
                                @else
                                    <div class="participant-avatar">
                                        @if($result->user->avatar)
                                            <img src="{{ asset('storage/' . $result->user->avatar) }}" alt="{{ $result->user->name }}">
                                        @else
                                            <i class="fas fa-user"></i>
                                        @endif
                                    </div>
                                    <div class="participant-details">
                                        <div class="participant-name">{{ $result->user->name }}</div>
                                        <div class="participant-league">
                                            @if($result->user->league)
                                                <img src="{{ asset('image/leagues/' . $result->user->league->icon) }}" alt="{{ $result->user->league->name }}">
                                                {{ $result->user->league->name }}
                                            @else
                                                <i class="fas fa-user-plus"></i>
                                                New Player
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <div class="table-cell wpm">
                            <div class="stat-display">
                                <span class="stat-value">{{ $result->wpm }}</span>
                                <span class="stat-unit">WPM</span>
                            </div>
                        </div>
                        
                        <div class="table-cell accuracy">
                            <div class="accuracy-display">
                                <div class="accuracy-bar">
                                    <div class="accuracy-fill" style="width: {{ $result->accuracy }}%"></div>
                                </div>
                                <span class="accuracy-text">{{ number_format($result->accuracy, 1) }}%</span>
                            </div>
                        </div>
                        
                        <div class="table-cell time">
                            {{ gmdate('i:s', $result->completion_time) }}
                        </div>
                        
                        <div class="table-cell status">
                            @if($result->completion_time)
                                <span class="status-badge completed">
                                    <i class="fas fa-check"></i>
                                    Finished
                                </span>
                            @else
                                <span class="status-badge incomplete">
                                    <i class="fas fa-times"></i>
                                    DNF
                                </span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Experience Gained -->
        @if(isset($experienceGained) && $experienceGained > 0)
        <div class="experience-section">
            <h3 class="section-title">
                <i class="fas fa-star"></i>
                Experience Gained
            </h3>
            
            <div class="experience-card">
                <div class="exp-icon">
                    <i class="fas fa-plus"></i>
                </div>
                <div class="exp-details">
                    <div class="exp-amount">+{{ $experienceGained }} EXP</div>
                    <div class="exp-breakdown">
                        <div class="exp-item">
                            <span>Participation:</span>
                            <span>+{{ $baseExperience ?? 50 }} EXP</span>
                        </div>
                        @if($positionBonus ?? 0 > 0)
                        <div class="exp-item">
                            <span>Position Bonus:</span>
                            <span>+{{ $positionBonus }} EXP</span>
                        </div>
                        @endif
                        @if($speedBonus ?? 0 > 0)
                        <div class="exp-item">
                            <span>Speed Bonus:</span>
                            <span>+{{ $speedBonus }} EXP</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Badges Earned -->
        @if(isset($newBadges) && count($newBadges) > 0)
        <div class="badges-section">
            <h3 class="section-title">
                <i class="fas fa-medal"></i>
                New Badges Earned!
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

        <!-- Race Replay -->
        <div class="race-replay">
            <h3 class="section-title">
                <i class="fas fa-chart-line"></i>
                Race Progress
            </h3>
            
            <div class="replay-chart">
                <canvas id="raceChart" width="400" height="200"></canvas>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="action-section">
            <div class="action-buttons">
                <a href="{{ route('competitions.index') }}" class="btn btn-primary">
                    <i class="fas fa-trophy"></i>
                    Join Another Race
                </a>
                <a href="{{ route('practice.show', $competition->text) }}" class="btn btn-outline-primary">
                    <i class="fas fa-redo"></i>
                    Practice This Text
                </a>
                <a href="{{ route('leaderboards.index') }}" class="btn btn-outline-success">
                    <i class="fas fa-list-ol"></i>
                    View Leaderboards
                </a>
                <button onclick="shareResult()" class="btn btn-outline-info">
                    <i class="fas fa-share"></i>
                    Share Result
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.competition-result-container {
    min-height: calc(100vh - 80px);
    background: linear-gradient(135deg, var(--bg-secondary), var(--bg-tertiary));
    padding: 2rem 0;
}

.race-header {
    background: var(--bg-card);
    border-radius: var(--border-radius-lg);
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: var(--shadow-md);
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: relative;
    overflow: hidden;
}

.race-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: var(--champion-gradient);
}

.race-info {
    flex: 1;
}

.race-title {
    font-family: var(--font-display);
    font-size: 2rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 0.5rem;
}

.race-meta {
    display: flex;
    gap: 2rem;
    color: var(--text-secondary);
    font-size: 0.9rem;
}

.race-meta span {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.winner-podium {
    text-align: center;
}

.winner-crown {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 1rem;
    color: var(--accent-secondary);
    font-weight: 700;
    animation: crown-glow 2s ease-in-out infinite;
}

.winner-crown i {
    font-size: 3rem;
}

@keyframes crown-glow {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}

.position-badge {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
    padding: 1rem;
    border-radius: var(--border-radius-lg);
    font-weight: 700;
    min-width: 120px;
}

.position-badge i {
    font-size: 2rem;
}

.position-1 {
    background: var(--medal-gradient);
    color: white;
    box-shadow: 0 0 20px rgba(245, 158, 11, 0.3);
}

.position-2 {
    background: linear-gradient(135deg, #e5e7eb, #9ca3af);
    color: var(--text-primary);
}

.position-3 {
    background: linear-gradient(135deg, #f59e0b, #d97706);
    color: white;
}

.position-badge:not(.position-1):not(.position-2):not(.position-3) {
    background: var(--bg-secondary);
    color: var(--text-primary);
    border: 2px solid var(--border-light);
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

.personal-performance {
    background: var(--bg-card);
    border-radius: var(--border-radius-lg);
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: var(--shadow-sm);
}

.performance-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
}

.race-leaderboard {
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
    grid-template-columns: 80px 1fr 100px 120px 100px 120px;
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
    grid-template-columns: 80px 1fr 100px 120px 100px 120px;
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

.table-row.bot-row {
    background: rgba(107, 114, 128, 0.05);
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
.rank-badge:not(.rank-1):not(.rank-2):not(.rank-3) {
    background: var(--text-secondary);
    color: white;
}

.participant-info {
    display: flex;
    align-items: center;
    gap: 1rem;
    width: 100%;
}

.participant-avatar {
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

.participant-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.bot-avatar {
    background: var(--text-secondary);
    color: white;
}

.participant-details {
    flex: 1;
    text-align: left;
}

.participant-name {
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 0.25rem;
}

.participant-league,
.participant-type {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.8rem;
    color: var(--text-secondary);
}

.participant-league img {
    width: 16px;
    height: 16px;
}

.bot-type {
    color: var(--text-muted);
}

.stat-display {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.stat-value {
    font-family: var(--font-display);
    font-size: 1.2rem;
    font-weight: 700;
    color: var(--text-primary);
}

.stat-unit {
    font-size: 0.8rem;
    color: var(--text-secondary);
}

.accuracy-display {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
    width: 100%;
}

.accuracy-bar {
    width: 80px;
    height: 6px;
    background: var(--border-light);
    border-radius: var(--border-radius);
    overflow: hidden;
}

.accuracy-fill {
    height: 100%;
    background: var(--victory-gradient);
    transition: width 0.5s ease;
}

.accuracy-text {
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--text-primary);
}

.status-badge {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: var(--border-radius);
    font-size: 0.8rem;
    font-weight: 600;
}

.status-badge.completed {
    background: rgba(16, 185, 129, 0.1);
    color: var(--accent-success);
}

.status-badge.incomplete {
    background: rgba(239, 68, 68, 0.1);
    color: var(--accent-danger);
}

.experience-section,
.badges-section,
.race-replay {
    background: var(--bg-card);
    border-radius: var(--border-radius-lg);
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: var(--shadow-sm);
}

.experience-card {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    padding: 1.5rem;
    background: var(--victory-gradient);
    color: white;
    border-radius: var(--border-radius);
}

.exp-icon {
    width: 60px;
    height: 60px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}

.exp-amount {
    font-family: var(--font-display);
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.exp-breakdown {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
    font-size: 0.9rem;
    opacity: 0.9;
}

.exp-item {
    display: flex;
    justify-content: space-between;
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

.replay-chart {
    height: 300px;
    display: flex;
    align-items: center;
    justify-content: center;
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
    min-width: 180px;
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
    .race-header {
        flex-direction: column;
        gap: 2rem;
        text-align: center;
    }
    
    .table-header,
    .table-row {
        grid-template-columns: 60px 1fr 80px 100px 80px 100px;
        font-size: 0.8rem;
    }
    
    .performance-grid {
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    }
}

@media (max-width: 768px) {
    .competition-result-container {
        padding: 1rem 0;
    }
    
    .race-header,
    .personal-performance,
    .race-leaderboard,
    .experience-section,
    .badges-section,
    .race-replay {
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
    
    .race-title {
        font-size: 1.5rem;
    }
    
    .race-meta {
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .table-header,
    .table-row {
        grid-template-columns: 50px 1fr 60px 80px;
        font-size: 0.75rem;
    }
    
    .table-cell:nth-child(5),
    .table-cell:nth-child(6),
    .header-cell:nth-child(5),
    .header-cell:nth-child(6) {
        display: none;
    }
    
    .performance-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .experience-card {
        flex-direction: column;
        text-align: center;
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
    // Race progress chart
    const ctx = document.getElementById('raceChart').getContext('2d');
    const chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($raceTimeline ?? []),
            datasets: [
                {
                    label: 'Your Progress',
                    data: @json($userProgress ?? []),
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true,
                    borderWidth: 3
                },
                @if(isset($botProgress))
                    @foreach($botProgress as $botName => $progress)
                    {
                        label: '{{ $botName }}',
                        data: @json($progress),
                        borderColor: 'rgb(107, 114, 128)',
                        backgroundColor: 'rgba(107, 114, 128, 0.05)',
                        tension: 0.4,
                        borderDash: [5, 5]
                    },
                    @endforeach
                @endif
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    title: {
                        display: true,
                        text: 'Progress (%)'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Time (seconds)'
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Race Progress Over Time'
                }
            }
        }
    });
});

function shareResult() {
    const text = `I just finished a typing race on SportTyping! 🏁\n\n` +
                `📊 Results:\n` +
                `• Speed: {{ $userResult->wpm }} WPM\n` +
                `• Accuracy: {{ number_format($userResult->accuracy, 1) }}%\n` +
                `• Position: {{ $userPosition ? ($userPosition === 1 ? '🥇 1st Place!' : ($userPosition === 2 ? '🥈 2nd Place!' : ($userPosition === 3 ? '🥉 3rd Place!' : '#' . $userPosition))) : 'Participated' }}\n\n` +
                `Join the competition at SportTyping!`;
    
    if (navigator.share) {
        navigator.share({
            title: 'My SportTyping Race Result',
            text: text
        });
    } else {
        navigator.clipboard.writeText(text).then(() => {
            alert('Result copied to clipboard!');
        });
    }
}
</script>
@endsection