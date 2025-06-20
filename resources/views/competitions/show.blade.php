@extends('layouts.app')

@section('content')
<div class="competition-detail-container">
    <div class="container">
        <!-- Competition Header -->
        <div class="competition-header">
            <div class="header-main">
                <div class="back-button">
                    <a href="{{ route('competitions.index') }}" class="btn btn-link">
                        <i class="fas fa-arrow-left"></i>
                        Back to Competitions
                    </a>
                </div>
                
                <div class="competition-info">
                    <div class="status-badges">
                        <div class="status-badge {{ $competition->status }}">
                            <i class="fas fa-{{ $competition->status == 'active' ? 'broadcast-tower' : ($competition->status == 'upcoming' ? 'clock' : 'flag-checkered') }}"></i>
                            {{ ucfirst($competition->status) }}
                        </div>
                        <div class="device-badge {{ $competition->device_type }}">
                            <i class="fas fa-{{ $competition->device_type == 'mobile' ? 'mobile-alt' : ($competition->device_type == 'pc' ? 'desktop' : 'globe') }}"></i>
                            {{ ucfirst($competition->device_type) }} Only
                        </div>
                    </div>
                    
                    <h1 class="competition-title">{{ $competition->title }}</h1>
                    <p class="competition-description">{{ $competition->description }}</p>
                </div>
            </div>
            
            <div class="header-actions">
                @if($competition->status == 'active')
                    @if($userParticipating)
                        <a href="{{ route('competitions.compete', $competition) }}" class="btn btn-danger btn-lg">
                            <i class="fas fa-racing-flag"></i>
                            Enter Race
                        </a>
                    @else
                        <form action="{{ route('competitions.join', $competition) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-user-plus"></i>
                                Join Now
                            </button>
                        </form>
                    @endif
                @elseif($competition->status == 'upcoming')
                    @if($userParticipating)
                        <button class="btn btn-success btn-lg" disabled>
                            <i class="fas fa-check"></i>
                            Registered
                        </button>
                    @else
                        <form action="{{ route('competitions.join', $competition) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-user-plus"></i>
                                Register
                            </button>
                        </form>
                    @endif
                @else
                    <button class="btn btn-secondary btn-lg" disabled>
                        <i class="fas fa-flag-checkered"></i>
                        Competition Ended
                    </button>
                @endif
            </div>
        </div>

        <div class="competition-content">
            <div class="content-grid">
                <!-- Main Content -->
                <div class="main-content">
                    <!-- Competition Stats -->
                    <div class="stats-card">
                        <h3 class="card-title">
                            <i class="fas fa-chart-bar"></i>
                            Competition Details
                        </h3>
                        <div class="stats-grid">
                            <div class="stat-item">
                                <div class="stat-icon">
                                    <i class="fas fa-users"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="stat-number">{{ $competition->participants_count }}/{{ $competition->max_participants }}</div>
                                    <div class="stat-label">Participants</div>
                                </div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-icon">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="stat-number">{{ $competition->duration }}</div>
                                    <div class="stat-label">Minutes</div>
                                </div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-icon">
                                    <i class="fas fa-file-text"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="stat-number">{{ $competition->text->word_count }}</div>
                                    <div class="stat-label">Words</div>
                                </div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-icon">
                                    <i class="fas fa-star"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="stat-number">{{ $competition->experience_reward }}</div>
                                    <div class="stat-label">EXP Reward</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Text Preview -->
                    <div class="text-preview-card">
                        <h3 class="card-title">
                            <i class="fas fa-eye"></i>
                            Text Preview
                        </h3>
                        <div class="text-preview">
                            <div class="text-meta">
                                <span class="text-category">
                                    <i class="fas fa-tag"></i>
                                    {{ $competition->text->category->name }}
                                </span>
                                <span class="text-difficulty difficulty-{{ $competition->text->difficulty_level }}">
                                    <i class="fas fa-signal"></i>
                                    {{ ucfirst($competition->text->difficulty_level) }}
                                </span>
                            </div>
                            <div class="text-content">
                                <p>{{ Str::limit($competition->text->content, 300) }}</p>
                                @if(strlen($competition->text->content) > 300)
                                    <button class="show-more-btn" onclick="toggleTextPreview()">
                                        <span class="show-text">Show more</span>
                                        <span class="hide-text" style="display: none;">Show less</span>
                                    </button>
                                @endif
                            </div>
                            <div class="full-text" style="display: none;">
                                <p>{{ $competition->text->content }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Competition Timeline -->
                    @if($competition->status == 'upcoming')
                    <div class="timeline-card">
                        <h3 class="card-title">
                            <i class="fas fa-calendar-alt"></i>
                            Schedule
                        </h3>
                        <div class="timeline">
                            <div class="timeline-item current">
                                <div class="timeline-marker"></div>
                                <div class="timeline-content">
                                    <h4>Registration Open</h4>
                                    <p>Players can register for the competition</p>
                                    <span class="timeline-time">Now</span>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-marker"></div>
                                <div class="timeline-content">
                                    <h4>Competition Starts</h4>
                                    <p>Typing competition begins</p>
                                    <span class="timeline-time">{{ $competition->start_time->format('M j, Y - H:i') }}</span>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-marker"></div>
                                <div class="timeline-content">
                                    <h4>Competition Ends</h4>
                                    <p>Results will be calculated and announced</p>
                                    <span class="timeline-time">{{ $competition->start_time->addMinutes($competition->duration)->format('M j, Y - H:i') }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Countdown Timer -->
                        <div class="countdown-timer" data-start="{{ $competition->start_time->timestamp }}">
                            <h4>Starts in:</h4>
                            <div class="countdown-display">
                                <div class="countdown-item">
                                    <span class="countdown-value" id="days">00</span>
                                    <span class="countdown-label">Days</span>
                                </div>
                                <div class="countdown-item">
                                    <span class="countdown-value" id="hours">00</span>
                                    <span class="countdown-label">Hours</span>
                                </div>
                                <div class="countdown-item">
                                    <span class="countdown-value" id="minutes">00</span>
                                    <span class="countdown-label">Minutes</span>
                                </div>
                                <div class="countdown-item">
                                    <span class="countdown-value" id="seconds">00</span>
                                    <span class="countdown-label">Seconds</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="sidebar">
                    <!-- Live Race Track (for active competitions) -->
                    @if($competition->status == 'active')
                    <div class="race-track-card">
                        <h3 class="card-title">
                            <span class="live-indicator">LIVE</span>
                            Race Progress
                        </h3>
                        <div class="race-track-container">
                            <div class="race-track">
                                <div class="track-background"></div>
                                <div class="finish-line">
                                    <img src="/image/ui/race_finish.svg" alt="Finish Line">
                                </div>
                                @foreach($competition->participants->take(8) as $index => $participant)
                                <div class="racer" data-participant="{{ $participant->id }}" style="--progress: {{ rand(10, 85) }}%">
                                    <div class="racer-car">
                                        <img src="/image/ui/mobil{{ ($index % 4) + 1 }}.svg" alt="{{ $participant->user->username }}">
                                    </div>
                                    <div class="racer-info">
                                        <span class="racer-name">{{ $participant->user->username }}</span>
                                        <span class="racer-wpm">{{ rand(40, 80) }} WPM</span>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <div class="race-leaderboard">
                                <h4>Current Standings</h4>
                                @foreach($competition->participants->take(5) as $index => $participant)
                                <div class="leaderboard-item">
                                    <span class="position">#{{ $index + 1 }}</span>
                                    <span class="participant-name">{{ $participant->user->username }}</span>
                                    <span class="participant-progress">{{ rand(60, 95) }}%</span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Participants List -->
                    <div class="participants-card">
                        <h3 class="card-title">
                            <i class="fas fa-users"></i>
                            Participants ({{ $competition->participants_count }})
                        </h3>
                        <div class="participants-list">
                            @forelse($competition->participants as $participant)
                            <div class="participant-item">
                                <div class="participant-avatar">
                                    @if($participant->user->profile && $participant->user->profile->avatar)
                                        <img src="{{ asset('storage/' . $participant->user->profile->avatar) }}" alt="{{ $participant->user->username }}">
                                    @else
                                        <div class="avatar-placeholder">
                                            {{ strtoupper(substr($participant->user->username, 0, 1)) }}
                                        </div>
                                    @endif
                                </div>
                                <div class="participant-info">
                                    <div class="participant-name">{{ $participant->user->username }}</div>
                                    <div class="participant-league">{{ $participant->user->profile?->league?->name ?? 'Novice' }}</div>
                                </div>
                                <div class="participant-stats">
                                    <div class="avg-wpm">{{ number_format($participant->user->profile?->typing_speed_avg ?? 0, 1) }} WPM</div>
                                </div>
                            </div>
                            @empty
                            <div class="empty-participants">
                                <i class="fas fa-user-plus"></i>
                                <p>No participants yet. Be the first to join!</p>
                            </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Competition Rules -->
                    <div class="rules-card">
                        <h3 class="card-title">
                            <i class="fas fa-gavel"></i>
                            Rules & Guidelines
                        </h3>
                        <div class="rules-list">
                            <div class="rule-item">
                                <i class="fas fa-check-circle"></i>
                                <span>Type as fast and accurately as possible</span>
                            </div>
                            <div class="rule-item">
                                <i class="fas fa-check-circle"></i>
                                <span>Backspace is allowed for corrections</span>
                            </div>
                            <div class="rule-item">
                                <i class="fas fa-check-circle"></i>
                                <span>Must complete entire text to finish</span>
                            </div>
                            <div class="rule-item">
                                <i class="fas fa-check-circle"></i>
                                <span>Rankings based on completion time</span>
                            </div>
                            <div class="rule-item">
                                <i class="fas fa-exclamation-triangle"></i>
                                <span>Refreshing page will disqualify you</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.competition-detail-container {
    background: var(--bg-primary);
    min-height: calc(100vh - 120px);
    padding: 2rem 0;
}

/* Competition Header */
.competition-header {
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: var(--border-radius-lg);
    padding: 2rem;
    margin-bottom: 2rem;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
}

.header-main {
    flex: 1;
}

.back-button {
    margin-bottom: 1rem;
}

.back-button .btn {
    color: var(--text-secondary);
    padding: 0.5rem 0;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    transition: color 0.3s ease;
}

.back-button .btn:hover {
    color: var(--accent-primary);
}

.status-badges {
    display: flex;
    gap: 1rem;
    margin-bottom: 1rem;
}

.status-badge {
    padding: 0.5rem 1rem;
    border-radius: var(--border-radius);
    font-weight: 600;
    font-size: 0.85rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.status-badge.active {
    background: var(--accent-danger);
    color: white;
    animation: pulse 2s infinite;
}

.status-badge.upcoming {
    background: var(--accent-warning);
    color: white;
}

.status-badge.completed {
    background: var(--accent-success);
    color: white;
}

.device-badge {
    padding: 0.5rem 1rem;
    border-radius: var(--border-radius);
    font-size: 0.8rem;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.device-badge.mobile {
    background: linear-gradient(135deg, var(--accent-purple), var(--accent-primary));
    color: white;
}

.device-badge.pc {
    background: linear-gradient(135deg, var(--accent-success), var(--accent-info));
    color: white;
}

.device-badge.both {
    background: var(--medal-gradient);
    color: white;
}

.competition-title {
    font-family: var(--font-display);
    font-size: 2rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 1rem;
}

.competition-description {
    color: var(--text-secondary);
    font-size: 1.1rem;
    line-height: 1.6;
    margin: 0;
}

.header-actions {
    flex-shrink: 0;
    margin-left: 2rem;
}

/* Content Grid */
.content-grid {
    display: grid;
    grid-template-columns: 1fr 400px;
    gap: 2rem;
}

.main-content > * {
    margin-bottom: 2rem;
}

/* Card Styles */
.stats-card,
.text-preview-card,
.timeline-card,
.race-track-card,
.participants-card,
.rules-card {
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: var(--border-radius-lg);
    padding: 2rem;
}

.card-title {
    font-family: var(--font-display);
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.card-title i {
    color: var(--accent-primary);
}

/* Stats Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: var(--bg-secondary);
    border-radius: var(--border-radius);
}

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
}

.stat-number {
    font-family: var(--font-display);
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text-primary);
}

.stat-label {
    color: var(--text-secondary);
    font-size: 0.9rem;
}

/* Text Preview */
.text-meta {
    display: flex;
    gap: 1rem;
    margin-bottom: 1rem;
}

.text-category,
.text-difficulty {
    padding: 0.5rem 1rem;
    border-radius: var(--border-radius);
    font-size: 0.8rem;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.text-category {
    background: var(--bg-secondary);
    color: var(--text-primary);
}

.text-difficulty.difficulty-beginner {
    background: var(--accent-success);
    color: white;
}

.text-difficulty.difficulty-intermediate {
    background: var(--accent-warning);
    color: white;
}

.text-difficulty.difficulty-advanced {
    background: var(--accent-danger);
    color: white;
}

.text-content {
    background: var(--bg-secondary);
    padding: 1.5rem;
    border-radius: var(--border-radius);
    font-family: 'Courier New', monospace;
    line-height: 1.8;
    color: var(--text-primary);
}

.show-more-btn {
    background: none;
    border: none;
    color: var(--accent-primary);
    font-weight: 500;
    cursor: pointer;
    margin-top: 1rem;
    padding: 0;
}

/* Timeline */
.timeline {
    position: relative;
    margin-bottom: 2rem;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 24px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: var(--border-light);
}

.timeline-item {
    position: relative;
    padding-left: 4rem;
    margin-bottom: 2rem;
}

.timeline-item.current .timeline-marker {
    background: var(--accent-primary);
    border-color: var(--accent-primary);
}

.timeline-marker {
    position: absolute;
    left: 16px;
    top: 0;
    width: 16px;
    height: 16px;
    background: var(--bg-secondary);
    border: 3px solid var(--border-light);
    border-radius: 50%;
}

.timeline-content h4 {
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 0.5rem;
}

.timeline-content p {
    color: var(--text-secondary);
    margin-bottom: 0.5rem;
}

.timeline-time {
    font-size: 0.9rem;
    color: var(--accent-primary);
    font-weight: 500;
}

/* Countdown Timer */
.countdown-timer {
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(16, 185, 129, 0.1));
    padding: 1.5rem;
    border-radius: var(--border-radius);
    text-align: center;
}

.countdown-timer h4 {
    color: var(--text-primary);
    margin-bottom: 1rem;
}

.countdown-display {
    display: flex;
    justify-content: center;
    gap: 1rem;
}

.countdown-item {
    text-align: center;
}

.countdown-value {
    display: block;
    font-family: var(--font-display);
    font-size: 2rem;
    font-weight: 700;
    color: var(--accent-primary);
}

.countdown-label {
    font-size: 0.8rem;
    color: var(--text-secondary);
    text-transform: uppercase;
    letter-spacing: 1px;
}

/* Race Track */
.race-track-container {
    background: var(--bg-secondary);
    border-radius: var(--border-radius);
    padding: 1rem;
}

.race-track {
    background: linear-gradient(90deg, #f3f4f6, #e5e7eb);
    border-radius: var(--border-radius);
    padding: 1rem;
    position: relative;
    min-height: 200px;
    margin-bottom: 1rem;
}

.track-background {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-image: repeating-linear-gradient(
        90deg,
        transparent,
        transparent 20px,
        rgba(255,255,255,0.3) 20px,
        rgba(255,255,255,0.3) 22px
    );
}

.finish-line {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
}

.finish-line img {
    width: 32px;
    height: 40px;
}

.racer {
    position: absolute;
    left: var(--progress);
    top: calc(20px + var(--index, 0) * 25px);
    display: flex;
    align-items: center;
    gap: 0.5rem;
    transition: left 0.5s ease;
}

.racer-car img {
    width: 24px;
    height: 24px;
}

.racer-info {
    background: rgba(255,255,255,0.9);
    padding: 0.25rem 0.5rem;
    border-radius: var(--border-radius);
    font-size: 0.8rem;
    white-space: nowrap;
}

.racer-name {
    font-weight: 600;
    color: var(--text-primary);
}

.racer-wpm {
    color: var(--accent-primary);
    margin-left: 0.5rem;
}

/* Race Leaderboard */
.race-leaderboard h4 {
    font-size: 1rem;
    color: var(--text-primary);
    margin-bottom: 1rem;
}

.leaderboard-item {
    display: flex;
    align-items: center;
    padding: 0.5rem;
    margin-bottom: 0.5rem;
    background: var(--bg-card);
    border-radius: var(--border-radius);
}

.position {
    font-weight: 700;
    color: var(--accent-primary);
    margin-right: 1rem;
    min-width: 30px;
}

.participant-name {
    flex: 1;
    color: var(--text-primary);
}

.participant-progress {
    color: var(--accent-success);
    font-weight: 600;
}

/* Participants List */
.participants-list {
    max-height: 400px;
    overflow-y: auto;
}

.participant-item {
    display: flex;
    align-items: center;
    padding: 1rem;
    margin-bottom: 0.5rem;
    background: var(--bg-secondary);
    border-radius: var(--border-radius);
    transition: all 0.3s ease;
}

.participant-item:hover {
    background: var(--border-light);
}

.participant-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    overflow: hidden;
    margin-right: 1rem;
}

.participant-avatar img {
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
    font-weight: 600;
}

.participant-info {
    flex: 1;
}

.participant-name {
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 0.25rem;
}

.participant-league {
    font-size: 0.8rem;
    color: var(--text-secondary);
}

.avg-wpm {
    font-weight: 600;
    color: var(--accent-primary);
    font-size: 0.9rem;
}

.empty-participants {
    text-align: center;
    padding: 2rem;
    color: var(--text-secondary);
}

.empty-participants i {
    font-size: 2rem;
    margin-bottom: 1rem;
    display: block;
}

/* Rules List */
.rules-list {
    space-y: 1rem;
}

.rule-item {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    padding: 0.75rem;
    background: var(--bg-secondary);
    border-radius: var(--border-radius);
    margin-bottom: 0.5rem;
}

.rule-item i {
    color: var(--accent-success);
    margin-top: 0.125rem;
    flex-shrink: 0;
}

.rule-item i.fa-exclamation-triangle {
    color: var(--accent-warning);
}

.rule-item span {
    color: var(--text-primary);
    line-height: 1.5;
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

/* Responsive Design */
@media (max-width: 1024px) {
    .content-grid {
        grid-template-columns: 1fr;
    }
    
    .competition-header {
        flex-direction: column;
        gap: 1.5rem;
    }
    
    .header-actions {
        margin-left: 0;
        align-self: stretch;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .competition-detail-container {
        padding: 1rem 0;
    }
    
    .competition-header {
        padding: 1.5rem;
    }
    
    .stats-card,
    .text-preview-card,
    .timeline-card,
    .race-track-card,
    .participants-card,
    .rules-card {
        padding: 1.5rem;
    }
    
    .countdown-display {
        flex-wrap: wrap;
        gap: 0.5rem;
    }
    
    .countdown-value {
        font-size: 1.5rem;
    }
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.7; }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle text preview
    window.toggleTextPreview = function() {
        const textContent = document.querySelector('.text-content');
        const fullText = document.querySelector('.full-text');
        const showText = document.querySelector('.show-text');
        const hideText = document.querySelector('.hide-text');
        
        if (fullText.style.display === 'none') {
            textContent.style.display = 'none';
            fullText.style.display = 'block';
            showText.style.display = 'none';
            hideText.style.display = 'inline';
        } else {
            textContent.style.display = 'block';
            fullText.style.display = 'none';
            showText.style.display = 'inline';
            hideText.style.display = 'none';
        }
    }
    
    // Countdown timer
    const countdownTimer = document.querySelector('.countdown-timer');
    if (countdownTimer) {
        const startTime = parseInt(countdownTimer.dataset.start) * 1000;
        
        function updateCountdown() {
            const now = new Date().getTime();
            const distance = startTime - now;
            
            if (distance > 0) {
                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                
                document.getElementById('days').textContent = days.toString().padStart(2, '0');
                document.getElementById('hours').textContent = hours.toString().padStart(2, '0');
                document.getElementById('minutes').textContent = minutes.toString().padStart(2, '0');
                document.getElementById('seconds').textContent = seconds.toString().padStart(2, '0');
            } else {
                // Competition started
                countdownTimer.innerHTML = '<h4>Competition has started!</h4>';
                setTimeout(() => {
                    location.reload();
                }, 2000);
            }
        }
        
        updateCountdown();
        setInterval(updateCountdown, 1000);
    }
    
    // Real-time race updates for active competitions
    if (document.querySelector('.race-track')) {
        function updateRaceProgress() {
            const racers = document.querySelectorAll('.racer');
            racers.forEach((racer, index) => {
                const currentProgress = parseFloat(racer.style.getPropertyValue('--progress')) || 10;
                const newProgress = Math.min(95, currentProgress + Math.random() * 3);
                racer.style.setProperty('--progress', newProgress + '%');
                racer.style.setProperty('--index', index);
                
                // Update WPM display
                const wpmSpan = racer.querySelector('.racer-wpm');
                if (wpmSpan) {
                    const currentWpm = parseInt(wpmSpan.textContent);
                    const newWpm = Math.max(30, currentWpm + Math.floor(Math.random() * 6 - 3));
                    wpmSpan.textContent = newWpm + ' WPM';
                }
            });
        }
        
        setInterval(updateRaceProgress, 2000);
    }
});
</script>
@endsection