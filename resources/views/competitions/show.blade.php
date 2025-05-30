@extends('layouts.app')

@section('content')
<div class="competition-detail-container">
    <div class="container-fluid">
        <!-- Competition Header -->
        <div class="competition-header">
            <div class="header-navigation">
                <a href="{{ route('competitions.index') }}" class="back-btn">
                    <i class="fas fa-arrow-left"></i>
                    <span>Back to Competitions</span>
                </a>
                
                <div class="competition-status-badge status-{{ $competition->status }}">
                    <i class="fas fa-{{ $competition->status === 'upcoming' ? 'clock' : ($competition->status === 'active' ? 'play' : 'check') }}"></i>
                    <span>{{ ucfirst($competition->status) }}</span>
                </div>
            </div>
            
            <div class="competition-info">
                <h1 class="competition-title">{{ $competition->title }}</h1>
                <p class="competition-description">{{ $competition->description }}</p>
                
                <div class="competition-meta">
                    <div class="meta-item">
                        <i class="fas fa-calendar"></i>
                        <div class="meta-content">
                            <span class="meta-label">Start Time</span>
                            <span class="meta-value">{{ $competition->start_time->format('M d, Y - H:i') }}</span>
                        </div>
                    </div>
                    
                    @if($competition->end_time)
                    <div class="meta-item">
                        <i class="fas fa-flag-checkered"></i>
                        <div class="meta-content">
                            <span class="meta-label">End Time</span>
                            <span class="meta-value">{{ $competition->end_time->format('M d, Y - H:i') }}</span>
                        </div>
                    </div>
                    @endif
                    
                    <div class="meta-item">
                        <i class="fas fa-{{ $competition->device_type === 'mobile' ? 'mobile-alt' : ($competition->device_type === 'pc' ? 'desktop' : 'devices') }}"></i>
                        <div class="meta-content">
                            <span class="meta-label">Device Type</span>
                            <span class="meta-value">{{ ucfirst($competition->device_type) }}</span>
                        </div>
                    </div>
                    
                    <div class="meta-item">
                        <i class="fas fa-users"></i>
                        <div class="meta-content">
                            <span class="meta-label">Participants</span>
                            <span class="meta-value" id="participantCount">{{ $competition->participants->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Live Countdown for Upcoming Competitions -->
            @if($competition->status === 'upcoming')
            <div class="live-countdown" id="competitionCountdown">
                <div class="countdown-title">
                    <i class="fas fa-rocket"></i>
                    Competition starts in
                </div>
                <div class="countdown-timer" id="countdownTimer">
                    <div class="time-unit">
                        <span class="time-value" id="days">00</span>
                        <span class="time-label">Days</span>
                    </div>
                    <div class="time-unit">
                        <span class="time-value" id="hours">00</span>
                        <span class="time-label">Hours</span>
                    </div>
                    <div class="time-unit">
                        <span class="time-value" id="minutes">00</span>
                        <span class="time-label">Minutes</span>
                    </div>
                    <div class="time-unit">
                        <span class="time-value" id="seconds">00</span>
                        <span class="time-label">Seconds</span>
                    </div>
                </div>
            </div>
            @endif
            
            <!-- Live Timer for Active Competitions -->
            @if($competition->status === 'active')
            <div class="live-timer" id="activeTimer">
                <div class="timer-title">
                    <i class="fas fa-play-circle"></i>
                    Competition in progress
                </div>
                <div class="timer-display" id="activeTimerDisplay">
                    <div class="timer-elapsed">
                        <span class="timer-label">Elapsed:</span>
                        <span class="timer-value" id="elapsedTime">--:--</span>
                    </div>
                    @if($competition->end_time)
                    <div class="timer-remaining">
                        <span class="timer-label">Remaining:</span>
                        <span class="timer-value" id="remainingTime">--:--</span>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <!-- Main Content Grid -->
        <div class="competition-content-grid">
            <!-- Text Preview Section -->
            <div class="text-preview-section">
                <div class="section-header">
                    <h2 class="section-title">
                        <i class="fas fa-file-text"></i>
                        Practice Text
                    </h2>
                    <div class="text-difficulty difficulty-{{ $competition->text->difficulty_level }}">
                        {{ ucfirst($competition->text->difficulty_level) }}
                    </div>
                </div>
                
                <div class="text-preview-card">
                    <div class="text-info-header">
                        <h3 class="text-title">{{ $competition->text->title }}</h3>
                        <div class="text-stats">
                            <span class="stat">
                                <i class="fas fa-tag"></i>
                                {{ $competition->text->category->name }}
                            </span>
                            <span class="stat">
                                <i class="fas fa-font"></i>
                                {{ $competition->text->word_count }} words
                            </span>
                            <span class="stat">
                                <i class="fas fa-clock"></i>
                                ~{{ ceil($competition->text->word_count / 40) }} min
                            </span>
                        </div>
                    </div>
                    
                    <div class="text-content-preview">
                        {{ Str::limit($competition->text->content, 300) }}
                        @if(strlen($competition->text->content) > 300)
                        <button class="expand-text-btn" onclick="expandTextPreview()">
                            <i class="fas fa-expand"></i>
                            Show Full Text
                        </button>
                        @endif
                    </div>
                    
                    <div class="text-full-content" id="fullTextContent" style="display: none;">
                        {{ $competition->text->content }}
                        <button class="collapse-text-btn" onclick="collapseTextPreview()">
                            <i class="fas fa-compress"></i>
                            Show Less
                        </button>
                    </div>
                </div>
            </div>

            <!-- Participants Section -->
            <div class="participants-section">
                <div class="section-header">
                    <h2 class="section-title">
                        <i class="fas fa-users"></i>
                        Participants
                        <span class="participant-count">({{ $competition->participants->count() }})</span>
                    </h2>
                    
                    @if($competition->status === 'active')
                    <div class="live-indicator">
                        <div class="pulse-dot"></div>
                        <span>Live</span>
                    </div>
                    @endif
                </div>
                
                <div class="participants-list" id="participantsList">
                    @forelse($competition->participants as $participant)
                    <div class="participant-card {{ $participant->user_id === Auth::id() ? 'current-user' : '' }}" 
                         data-user-id="{{ $participant->user_id }}">
                        <div class="participant-avatar">
                            @if($participant->user->profile?->avatar)
                                <img src="{{ asset('storage/' . $participant->user->profile->avatar) }}" 
                                     alt="{{ $participant->user->username }}">
                            @else
                                <div class="avatar-placeholder">
                                    {{ strtoupper(substr($participant->user->username, 0, 1)) }}
                                </div>
                            @endif
                            
                            @if($participant->user_id === Auth::id())
                            <div class="user-badge">You</div>
                            @endif
                        </div>
                        
                        <div class="participant-info">
                            <h4 class="participant-name">{{ $participant->user->username }}</h4>
                            <div class="participant-stats">
                                <span class="league-badge">
                                    <i class="fas fa-crown"></i>
                                    {{ $participant->user->profile?->league?->name ?? 'Novice' }}
                                </span>
                                <span class="avg-speed">
                                    <i class="fas fa-tachometer-alt"></i>
                                    {{ number_format($participant->user->profile?->typing_speed_avg ?? 0) }} WPM
                                </span>
                            </div>
                            <div class="join-time">
                                <i class="fas fa-clock"></i>
                                Joined {{ $participant->joined_at->diffForHumans() }}
                            </div>
                        </div>
                        
                        @if($competition->status === 'active')
                        <div class="live-progress" id="progress-{{ $participant->user_id }}">
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: 0%"></div>
                            </div>
                            <div class="live-wpm">-- WPM</div>
                        </div>
                        @endif
                        
                        <div class="participant-device">
                            <i class="fas fa-{{ $participant->device?->type === 'mobile' ? 'mobile-alt' : 'desktop' }}"></i>
                        </div>
                    </div>
                    @empty
                    <div class="empty-participants">
                        <div class="empty-icon">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <h3>No participants yet</h3>
                        <p>Be the first to join this competition!</p>
                    </div>
                    @endforelse
                </div>
                
                <!-- Bot Participants Preview for Active Competitions -->
                @if($competition->status === 'active' && $competition->participants->count() < 3)
                <div class="bot-participants-preview">
                    <h4 class="bot-header">
                        <i class="fas fa-robot"></i>
                        AI Competitors
                    </h4>
                    <div class="bot-list">
                        @for($i = 0; $i < (3 - $competition->participants->count()); $i++)
                        <div class="bot-card">
                            <div class="bot-avatar">
                                <i class="fas fa-robot"></i>
                            </div>
                            <div class="bot-info">
                                <span class="bot-name">{{ ['SpeedBot', 'KeyMaster', 'TypeFury'][$i] ?? 'Bot' . ($i+1) }}</span>
                                <span class="bot-level">{{ $competition->text->difficulty_level }} AI</span>
                            </div>
                        </div>
                        @endfor
                    </div>
                </div>
                @endif
            </div>

            <!-- Competition Actions -->
            <div class="competition-actions-section">
                @if(!$userParticipating && $competition->status !== 'completed')
                <div class="join-section">
                    <div class="join-card">
                        <div class="join-header">
                            <h3>
                                <i class="fas fa-user-plus"></i>
                                Join Competition
                            </h3>
                            <p>Ready to test your typing skills?</p>
                        </div>
                        
                        <div class="requirements-check" id="requirementsCheck">
                            <div class="requirement-item" id="deviceCheck">
                                <div class="check-icon">
                                    <i class="fas fa-check"></i>
                                </div>
                                <div class="check-content">
                                    <span class="check-label">Device Compatibility</span>
                                    <span class="check-description">Your device is compatible</span>
                                </div>
                            </div>
                            
                            @auth
                            <div class="requirement-item verified">
                                <div class="check-icon">
                                    <i class="fas fa-check"></i>
                                </div>
                                <div class="check-content">
                                    <span class="check-label">Account Status</span>
                                    <span class="check-description">Logged in as {{ Auth::user()->username }}</span>
                                </div>
                            </div>
                            @else
                            <div class="requirement-item warning">
                                <div class="check-icon">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                                <div class="check-content">
                                    <span class="check-label">Account Required</span>
                                    <span class="check-description">Please login to join competitions</span>
                                </div>
                            </div>
                            @endauth
                        </div>
                        
                        @auth
                        <form action="{{ route('competitions.join', $competition) }}" method="POST" id="joinCompetitionForm">
                            @csrf
                            <button type="submit" class="join-btn" id="joinBtn" 
                                    {{ $competition->status === 'completed' ? 'disabled' : '' }}>
                                <i class="fas fa-rocket"></i>
                                <span>Join Competition</span>
                            </button>
                        </form>
                        @else
                        <div class="auth-actions">
                            <a href="{{ route('login') }}" class="auth-btn login">
                                <i class="fas fa-sign-in-alt"></i>
                                Login
                            </a>
                            <a href="{{ route('register') }}" class="auth-btn register">
                                <i class="fas fa-user-plus"></i>
                                Register
                            </a>
                        </div>
                        @endauth
                    </div>
                </div>
                @endif

                @if($userParticipating)
                <div class="participant-actions">
                    @if($competition->status === 'upcoming')
                    <div class="waiting-card">
                        <div class="waiting-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <h3>You're Registered!</h3>
                        <p>Competition will start soon. Get ready!</p>
                        <div class="preparation-tips">
                            <h4>Preparation Tips:</h4>
                            <ul>
                                <li><i class="fas fa-check"></i> Ensure stable internet connection</li>
                                <li><i class="fas fa-check"></i> Find a comfortable typing position</li>
                                <li><i class="fas fa-check"></i> Minimize distractions</li>
                            </ul>
                        </div>
                    </div>
                    @elseif($competition->status === 'active')
                    <div class="compete-card">
                        <div class="compete-header">
                            <h3>
                                <i class="fas fa-play"></i>
                                Competition is Live!
                            </h3>
                            <p>Enter the typing arena now!</p>
                        </div>
                        
                        <a href="{{ route('competitions.compete', $competition) }}" class="compete-btn">
                            <i class="fas fa-racing-flag"></i>
                            <span>Start Typing</span>
                        </a>
                        
                        <div class="compete-info">
                            <div class="info-item">
                                <i class="fas fa-users"></i>
                                <span>{{ $competition->participants->count() }} typing now</span>
                            </div>
                            <div class="info-item">
                                <i class="fas fa-trophy"></i>
                                <span>Race for the top position!</span>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="completed-card">
                        <div class="completed-icon">
                            <i class="fas fa-flag-checkered"></i>
                        </div>
                        <h3>Competition Completed</h3>
                        <p>Check out the results and see how you performed!</p>
                        
                        @php
                            $userResult = $competition->results()->where('user_id', Auth::id())->first();
                        @endphp
                        
                        @if($userResult)
                        <div class="user-result-preview">
                            <div class="result-stat">
                                <span class="stat-label">Your Speed</span>
                                <span class="stat-value">{{ number_format($userResult->typing_speed) }} WPM</span>
                            </div>
                            <div class="result-stat">
                                <span class="stat-label">Your Accuracy</span>
                                <span class="stat-value">{{ number_format($userResult->typing_accuracy, 1) }}%</span>
                            </div>
                            <div class="result-stat">
                                <span class="stat-label">Your Position</span>
                                <span class="stat-value">#{{ $userResult->position ?? '--' }}</span>
                            </div>
                        </div>
                        
                        <a href="{{ route('competitions.result', ['competition' => $competition, 'result' => $userResult]) }}" 
                           class="view-result-btn">
                            <i class="fas fa-chart-bar"></i>
                            View Detailed Results
                        </a>
                        @endif
                    </div>
                    @endif
                </div>
                @endif
            </div>
        </div>

        <!-- Competition Statistics -->
        @if($competition->status === 'completed' || $competition->results->count() > 0)
        <div class="competition-statistics">
            <h2 class="section-title">
                <i class="fas fa-chart-bar"></i>
                Competition Statistics
            </h2>
            
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-tachometer-alt"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-value">{{ number_format($competition->results->avg('typing_speed') ?? 0) }}</span>
                        <span class="stat-label">Average WPM</span>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-crown"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-value">{{ number_format($competition->results->max('typing_speed') ?? 0) }}</span>
                        <span class="stat-label">Highest WPM</span>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-bullseye"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-value">{{ number_format($competition->results->avg('typing_accuracy') ?? 0, 1) }}%</span>
                        <span class="stat-label">Average Accuracy</span>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-medal"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-value">{{ $competition->results->count() }}</span>
                        <span class="stat-label">Completed</span>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<style>
.competition-detail-container {
    padding: 2rem 0;
    background: var(--bg-primary);
    min-height: calc(100vh - 76px);
}

/* Competition Header */
.competition-header {
    background: var(--bg-card);
    border-radius: var(--border-radius-xl);
    border: 1px solid var(--border-light);
    box-shadow: var(--shadow-md);
    padding: 2rem;
    margin-bottom: 2rem;
    position: relative;
    overflow: hidden;
}

.competition-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: var(--champion-gradient);
}

.header-navigation {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.back-btn {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    color: var(--text-secondary);
    text-decoration: none;
    padding: 0.75rem 1.25rem;
    border-radius: var(--border-radius);
    border: 1px solid var(--border-light);
    transition: all 0.3s ease;
}

.back-btn:hover {
    background: var(--accent-primary);
    border-color: var(--accent-primary);
    color: white;
    text-decoration: none;
    transform: translateX(-3px);
}

.competition-status-badge {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem 1.5rem;
    border-radius: var(--border-radius);
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
    font-size: 0.875rem;
}

.competition-status-badge.status-upcoming {
    background: rgba(245, 158, 11, 0.1);
    color: var(--accent-secondary);
    border: 1px solid rgba(245, 158, 11, 0.2);
}

.competition-status-badge.status-active {
    background: rgba(16, 185, 129, 0.1);
    color: var(--accent-success);
    border: 1px solid rgba(16, 185, 129, 0.2);
    animation: pulse 2s infinite;
}

.competition-status-badge.status-completed {
    background: rgba(59, 130, 246, 0.1);
    color: var(--accent-primary);
    border: 1px solid rgba(59, 130, 246, 0.2);
}

.competition-info {
    margin-bottom: 2rem;
}

.competition-title {
    font-family: var(--font-display);
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 1rem;
}

.competition-description {
    font-size: 1.1rem;
    color: var(--text-secondary);
    line-height: 1.6;
    margin-bottom: 2rem;
    max-width: 800px;
}

.competition-meta {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: var(--bg-secondary);
    border-radius: var(--border-radius);
    border: 1px solid var(--border-light);
}

.meta-item i {
    color: var(--accent-primary);
    font-size: 1.25rem;
    width: 24px;
    text-align: center;
}

.meta-content {
    display: flex;
    flex-direction: column;
}

.meta-label {
    color: var(--text-muted);
    font-size: 0.8rem;
    font-weight: 500;
    margin-bottom: 0.25rem;
}

.meta-value {
    color: var(--text-primary);
    font-weight: 600;
}

/* Live Countdown */
.live-countdown {
    background: var(--champion-gradient);
    color: white;
    border-radius: var(--border-radius-lg);
    padding: 2rem;
    text-align: center;
    margin-top: 1rem;
}

.countdown-title {
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
}

.countdown-timer {
    display: flex;
    justify-content: center;
    gap: 2rem;
}

.time-unit {
    display: flex;
    flex-direction: column;
    align-items: center;
    min-width: 80px;
}

.time-value {
    font-family: var(--font-display);
    font-size: 3rem;
    font-weight: 900;
    line-height: 1;
    margin-bottom: 0.5rem;
}

.time-label {
    font-size: 0.875rem;
    opacity: 0.9;
    text-transform: uppercase;
    letter-spacing: 1px;
}

/* Live Timer */
.live-timer {
    background: var(--victory-gradient);
    color: white;
    border-radius: var(--border-radius-lg);
    padding: 2rem;
    text-align: center;
    margin-top: 1rem;
}

.timer-title {
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
}

.timer-display {
    display: flex;
    justify-content: center;
    gap: 3rem;
}

.timer-elapsed,
.timer-remaining {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.timer-label {
    font-size: 0.875rem;
    opacity: 0.9;
    margin-bottom: 0.5rem;
}

.timer-value {
    font-family: var(--font-display);
    font-size: 2rem;
    font-weight: 700;
}

/* Content Grid */
.competition-content-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 2rem;
    margin-bottom: 3rem;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
}

.section-title {
    font-family: var(--font-display);
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--text-primary);
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.section-title i {
    color: var(--accent-primary);
}

.participant-count {
    color: var(--text-muted);
    font-weight: 400;
    font-size: 1rem;
}

/* Text Preview Section */
.text-preview-section {
    background: var(--bg-card);
    border-radius: var(--border-radius-lg);
    border: 1px solid var(--border-light);
    padding: 2rem;
    box-shadow: var(--shadow-sm);
}

.text-difficulty {
    padding: 0.5rem 1rem;
    border-radius: var(--border-radius);
    font-size: 0.875rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.text-difficulty.difficulty-beginner {
    background: rgba(16, 185, 129, 0.1);
    color: var(--accent-success);
}

.text-difficulty.difficulty-intermediate {
    background: rgba(59, 130, 246, 0.1);
    color: var(--accent-primary);
}

.text-difficulty.difficulty-advanced {
    background: rgba(245, 158, 11, 0.1);
    color: var(--accent-secondary);
}

.text-difficulty.difficulty-expert {
    background: rgba(239, 68, 68, 0.1);
    color: var(--accent-danger);
}

.text-preview-card {
    border: 1px solid var(--border-light);
    border-radius: var(--border-radius);
    overflow: hidden;
}

.text-info-header {
    padding: 1.5rem;
    background: var(--bg-secondary);
    border-bottom: 1px solid var(--border-light);
}

.text-title {
    font-family: var(--font-display);
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 1rem;
}

.text-stats {
    display: flex;
    gap: 1.5rem;
    flex-wrap: wrap;
}

.text-stats .stat {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--text-secondary);
    font-size: 0.875rem;
}

.text-content-preview,
.text-full-content {
    padding: 1.5rem;
    line-height: 1.7;
    color: var(--text-primary);
    background: var(--bg-primary);
    font-family: 'JetBrains Mono', monospace;
}

.expand-text-btn,
.collapse-text-btn {
    background: var(--accent-primary);
    color: white;
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: var(--border-radius);
    margin-top: 1rem;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 500;
}

.expand-text-btn:hover,
.collapse-text-btn:hover {
    background: var(--accent-primary);
    opacity: 0.9;
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

/* Participants Section */
.participants-section {
    background: var(--bg-card);
    border-radius: var(--border-radius-lg);
    border: 1px solid var(--border-light);
    padding: 2rem;
    box-shadow: var(--shadow-sm);
}

.live-indicator {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--accent-success);
    font-weight: 600;
    font-size: 0.875rem;
}

.pulse-dot {
    width: 8px;
    height: 8px;
    background: var(--accent-success);
    border-radius: 50%;
    animation: pulse 2s infinite;
}

.participants-list {
    max-height: 500px;
    overflow-y: auto;
}

.participant-card {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    border: 1px solid var(--border-light);
    border-radius: var(--border-radius);
    margin-bottom: 1rem;
    transition: all 0.3s ease;
    position: relative;
}

.participant-card:hover {
    background: var(--bg-secondary);
    transform: translateX(5px);
}

.participant-card.current-user {
    border-color: var(--accent-primary);
    background: rgba(59, 130, 246, 0.05);
}

.participant-avatar {
    position: relative;
    width: 50px;
    height: 50px;
    flex-shrink: 0;
}

.participant-avatar img,
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
    font-weight: 700;
    font-size: 1.25rem;
}

.user-badge {
    position: absolute;
    bottom: -5px;
    right: -5px;
    background: var(--accent-success);
    color: white;
    padding: 0.25rem 0.5rem;
    border-radius: var(--border-radius-sm);
    font-size: 0.7rem;
    font-weight: 600;
}

.participant-info {
    flex: 1;
}

.participant-name {
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 0.5rem;
}

.participant-stats {
    display: flex;
    gap: 1rem;
    margin-bottom: 0.25rem;
}

.league-badge,
.avg-speed {
    display: flex;
    align-items: center;
    gap: 0.25rem;
    font-size: 0.8rem;
    color: var(--text-secondary);
}

.league-badge {
    color: var(--accent-secondary);
    font-weight: 600;
}

.join-time {
    display: flex;
    align-items: center;
    gap: 0.25rem;
    font-size: 0.75rem;
    color: var(--text-muted);
}

.live-progress {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
    min-width: 100px;
}

.live-progress .progress-bar {
    width: 100%;
    height: 6px;
    background: var(--border-light);
    border-radius: 3px;
    overflow: hidden;
}

.live-progress .progress-fill {
    height: 100%;
    background: var(--victory-gradient);
    border-radius: 3px;
    transition: width 0.5s ease;
}

.live-wpm {
    font-size: 0.8rem;
    font-weight: 600;
    color: var(--accent-success);
}

.participant-device {
    color: var(--text-muted);
    font-size: 1.25rem;
}

.empty-participants {
    text-align: center;
    padding: 3rem 1rem;
    color: var(--text-muted);
}

.empty-participants .empty-icon {
    font-size: 3rem;
    margin-bottom: 1rem;
    color: var(--border-medium);
}

.empty-participants h3 {
    margin-bottom: 0.5rem;
    color: var(--text-secondary);
}

/* Bot Participants */
.bot-participants-preview {
    margin-top: 2rem;
    padding-top: 1.5rem;
    border-top: 1px solid var(--border-light);
}

.bot-header {
    color: var(--text-secondary);
    font-size: 1rem;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.bot-list {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.bot-card {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem;
    background: var(--bg-secondary);
    border-radius: var(--border-radius);
    border: 1px dashed var(--border-medium);
}

.bot-avatar {
    width: 32px;
    height: 32px;
    background: var(--text-muted);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 0.875rem;
}

.bot-info {
    display: flex;
    flex-direction: column;
}

.bot-name {
    font-weight: 600;
    color: var(--text-secondary);
    font-size: 0.875rem;
}

.bot-level {
    font-size: 0.75rem;
    color: var(--text-muted);
}

/* Competition Actions */
.competition-actions-section {
    grid-column: span 2;
    background: var(--bg-card);
    border-radius: var(--border-radius-lg);
    border: 1px solid var(--border-light);
    padding: 2rem;
    box-shadow: var(--shadow-sm);
}

.join-section,
.participant-actions {
    max-width: 600px;
    margin: 0 auto;
}

.join-card,
.waiting-card,
.compete-card,
.completed-card {
    text-align: center;
    padding: 2rem;
    border-radius: var(--border-radius-lg);
    border: 1px solid var(--border-light);
}

.join-header,
.compete-header {
    margin-bottom: 2rem;
}

.join-header h3,
.compete-header h3 {
    font-family: var(--font-display);
    font-size: 1.5rem;
    color: var(--text-primary);
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
}

.join-header p,
.compete-header p {
    color: var(--text-secondary);
    font-size: 1.1rem;
}

.requirements-check {
    margin: 2rem 0;
    text-align: left;
}

.requirement-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    border-radius: var(--border-radius);
    margin-bottom: 0.75rem;
    border: 1px solid var(--border-light);
}

.requirement-item.verified {
    background: rgba(16, 185, 129, 0.05);
    border-color: var(--accent-success);
}

.requirement-item.warning {
    background: rgba(245, 158, 11, 0.05);
    border-color: var(--accent-secondary);
}

.check-icon {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: var(--accent-success);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    flex-shrink: 0;
}

.requirement-item.warning .check-icon {
    background: var(--accent-secondary);
}

.check-content {
    display: flex;
    flex-direction: column;
}

.check-label {
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 0.25rem;
}

.check-description {
    color: var(--text-secondary);
    font-size: 0.875rem;
}

.join-btn,
.compete-btn {
    background: var(--champion-gradient);
    color: white;
    border: none;
    padding: 1.25rem 3rem;
    border-radius: var(--border-radius);
    font-size: 1.1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1rem;
    margin: 0 auto;
    text-decoration: none;
    min-width: 200px;
}

.join-btn:hover,
.compete-btn:hover {
    transform: translateY(-3px);
    box-shadow: var(--shadow-lg);
    text-decoration: none;
    color: white;
}

.join-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

.auth-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
    margin-top: 1.5rem;
}

.auth-btn {
    padding: 1rem 2rem;
    border-radius: var(--border-radius);
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.auth-btn.login {
    background: transparent;
    border: 2px solid var(--accent-primary);
    color: var(--accent-primary);
}

.auth-btn.register {
    background: var(--champion-gradient);
    color: white;
    border: 2px solid transparent;
}

.auth-btn:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
    text-decoration: none;
}

.auth-btn.login:hover {
    background: var(--accent-primary);
    color: white;
}

.waiting-card {
    background: var(--bg-secondary);
    border-color: var(--accent-secondary);
}

.waiting-icon,
.completed-icon {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: var(--medal-gradient);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 2rem;
    margin: 0 auto 1.5rem;
}

.completed-icon {
    background: var(--champion-gradient);
}

.waiting-card h3,
.completed-card h3 {
    font-family: var(--font-display);
    color: var(--text-primary);
    margin-bottom: 1rem;
}

.preparation-tips {
    text-align: left;
    margin-top: 2rem;
    padding: 1.5rem;
    background: var(--bg-primary);
    border-radius: var(--border-radius);
    border: 1px solid var(--border-light);
}

.preparation-tips h4 {
    color: var(--text-primary);
    margin-bottom: 1rem;
}

.preparation-tips ul {
    list-style: none;
    padding: 0;
}

.preparation-tips li {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 0.75rem;
    color: var(--text-secondary);
}

.preparation-tips i {
    color: var(--accent-success);
}

.compete-info {
    display: flex;
    justify-content: center;
    gap: 2rem;
    margin-top: 1.5rem;
}

.info-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--text-secondary);
    font-size: 0.9rem;
}

.user-result-preview {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1rem;
    margin: 1.5rem 0;
}

.result-stat {
    padding: 1rem;
    background: var(--bg-secondary);
    border-radius: var(--border-radius);
    border: 1px solid var(--border-light);
    text-align: center;
}

.stat-label {
    display: block;
    color: var(--text-muted);
    font-size: 0.8rem;
    margin-bottom: 0.5rem;
}

.stat-value {
    display: block;
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text-primary);
    font-family: var(--font-display);
}

.view-result-btn {
    background: var(--victory-gradient);
    color: white;
    padding: 1rem 2rem;
    border-radius: var(--border-radius);
    text-decoration: none;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    transition: all 0.3s ease;
}

.view-result-btn:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
    text-decoration: none;
    color: white;
}

/* Competition Statistics */
.competition-statistics {
    background: var(--bg-card);
    border-radius: var(--border-radius-lg);
    border: 1px solid var(--border-light);
    padding: 2rem;
    box-shadow: var(--shadow-sm);
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
    margin-top: 1.5rem;
}

.stat-card {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.5rem;
    background: var(--bg-secondary);
    border-radius: var(--border-radius);
    border: 1px solid var(--border-light);
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-3px);
    box-shadow: var(--shadow-md);
}

.stat-card .stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: var(--champion-gradient);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.25rem;
}

.stat-card .stat-content {
    display: flex;
    flex-direction: column;
}

.stat-card .stat-value {
    font-size: 1.75rem;
    font-weight: 700;
    color: var(--text-primary);
    line-height: 1.2;
    font-family: var(--font-display);
}

.stat-card .stat-label {
    color: var(--text-secondary);
    font-size: 0.875rem;
    font-weight: 500;
}

/* Responsive Design */
@media (max-width: 1024px) {
    .competition-content-grid {
        grid-template-columns: 1fr;
    }
    
    .competition-actions-section {
        grid-column: span 1;
    }
    
    .countdown-timer {
        gap: 1rem;
    }
    
    .time-value {
        font-size: 2rem;
    }
}

@media (max-width: 768px) {
    .competition-detail-container {
        padding: 1rem 0;
    }
    
    .competition-header {
        padding: 1.5rem;
    }
    
    .header-navigation {
        flex-direction: column;
        gap: 1rem;
    }
    
    .competition-title {
        font-size: 2rem;
    }
    
    .competition-meta {
        grid-template-columns: 1fr;
    }
    
    .countdown-timer {
        flex-wrap: wrap;
        gap: 1rem;
    }
    
    .timer-display {
        flex-direction: column;
        gap: 1rem;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .auth-actions {
        flex-direction: column;
    }
    
    .user-result-preview {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 480px) {
    .time-unit {
        min-width: 60px;
    }
    
    .time-value {
        font-size: 1.5rem;
    }
    
    .participant-card {
        flex-direction: column;
        text-align: center;
        gap: 1rem;
    }
    
    .participant-stats {
        justify-content: center;
    }
    
    .join-btn,
    .compete-btn {
        width: 100%;
        padding: 1rem 2rem;
    }
}

/* Animations */
@keyframes pulse {
    0%, 100% {
        opacity: 1;
        transform: scale(1);
    }
    50% {
        opacity: 0.7;
        transform: scale(1.05);
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize competition page functionality
    initializeCompetitionCountdown();
    initializeActiveTimer();
    initializeDeviceCheck();
    initializeRealtimeUpdates();
    setupFormHandling();
});

function initializeCompetitionCountdown() {
    const countdownElement = document.getElementById('competitionCountdown');
    if (!countdownElement) return;
    
    const startTime = new Date('{{ $competition->start_time->toISOString() }}');
    
    function updateCountdown() {
        const now = new Date();
        const timeDiff = startTime - now;
        
        if (timeDiff <= 0) {
            // Competition has started, reload page
            window.location.reload();
            return;
        }
        
        const days = Math.floor(timeDiff / (1000 * 60 * 60 * 24));
        const hours = Math.floor((timeDiff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((timeDiff % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((timeDiff % (1000 * 60)) / 1000);
        
        document.getElementById('days').textContent = days.toString().padStart(2, '0');
        document.getElementById('hours').textContent = hours.toString().padStart(2, '0');
        document.getElementById('minutes').textContent = minutes.toString().padStart(2, '0');
        document.getElementById('seconds').textContent = seconds.toString().padStart(2, '0');
    }
    
    // Update immediately and then every second
    updateCountdown();
    setInterval(updateCountdown, 1000);
}

function initializeActiveTimer() {
    const timerElement = document.getElementById('activeTimer');
    if (!timerElement) return;
    
    const startTime = new Date('{{ $competition->start_time->toISOString() }}');
    @if($competition->end_time)
    const endTime = new Date('{{ $competition->end_time->toISOString() }}');
    @endif
    
    function updateActiveTimer() {
        const now = new Date();
        const elapsedMs = now - startTime;
        
        // Update elapsed time
        const elapsedMinutes = Math.floor(elapsedMs / (1000 * 60));
        const elapsedSeconds = Math.floor((elapsedMs % (1000 * 60)) / 1000);
        document.getElementById('elapsedTime').textContent = 
            `${elapsedMinutes}:${elapsedSeconds.toString().padStart(2, '0')}`;
        
        @if($competition->end_time)
        // Update remaining time
        const remainingMs = endTime - now;
        if (remainingMs > 0) {
            const remainingMinutes = Math.floor(remainingMs / (1000 * 60));
            const remainingSeconds = Math.floor((remainingMs % (1000 * 60)) / 1000);
            document.getElementById('remainingTime').textContent = 
                `${remainingMinutes}:${remainingSeconds.toString().padStart(2, '0')}`;
        } else {
            document.getElementById('remainingTime').textContent = '00:00';
            // Competition ended, reload page
            setTimeout(() => window.location.reload(), 2000);
        }
        @endif
    }
    
    // Update immediately and then every second
    updateActiveTimer();
    setInterval(updateActiveTimer, 1000);
}

function initializeDeviceCheck() {
    const deviceCheck = document.getElementById('deviceCheck');
    if (!deviceCheck) return;
    
    const requiredDevice = '{{ $competition->device_type }}';
    const currentDevice = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ? 'mobile' : 'pc';
    
    const checkIcon = deviceCheck.querySelector('.check-icon i');
    const checkDescription = deviceCheck.querySelector('.check-description');
    
    if (requiredDevice === 'both' || requiredDevice === currentDevice) {
        deviceCheck.classList.add('verified');
        checkIcon.className = 'fas fa-check';
        checkDescription.textContent = `Your ${currentDevice} device is compatible`;
    } else {
        deviceCheck.classList.add('warning');
        checkIcon.className = 'fas fa-exclamation-triangle';
        checkDescription.textContent = `This competition is for ${requiredDevice} devices only`;
        
        // Disable join button
        const joinBtn = document.getElementById('joinBtn');
        if (joinBtn) {
            joinBtn.disabled = true;
            joinBtn.innerHTML = '<i class="fas fa-ban"></i><span>Device Not Compatible</span>';
        }
    }
}

function initializeRealtimeUpdates() {
    @if($competition->status === 'active')
    // Setup real-time updates for active competitions
    if (window.Echo) {
        window.Echo.join(`competition.{{ $competition->id }}`)
            .here((users) => {
                console.log('Users currently in competition:', users);
                updateParticipantCount(users.length);
            })
            .joining((user) => {
                console.log('User joined:', user);
                addParticipantToList(user);
                updateParticipantCount();
            })
            .leaving((user) => {
                console.log('User left:', user);
                removeParticipantFromList(user);
                updateParticipantCount();
            })
            .listen('CompetitionProgress', (e) => {
                updateParticipantProgress(e.user_id, e.progress, e.wpm);
            });
    }
    @endif
}

function updateParticipantCount(count = null) {
    const countElement = document.getElementById('participantCount');
    if (countElement) {
        if (count !== null) {
            countElement.textContent = count;
        } else {
            const currentCount = parseInt(countElement.textContent);
            countElement.textContent = currentCount;
        }
    }
}

function updateParticipantProgress(userId, progress, wpm) {
    const progressElement = document.getElementById(`progress-${userId}`);
    if (progressElement) {
        const progressBar = progressElement.querySelector('.progress-fill');
        const wpmDisplay = progressElement.querySelector('.live-wpm');
        
        if (progressBar) {
            progressBar.style.width = `${progress}%`;
        }
        
        if (wmpDisplay) {
            wmpDisplay.textContent = `${Math.round(wmp)} WPM`;
        }
    }
}

function setupFormHandling() {
    const joinForm = document.getElementById('joinCompetitionForm');
    if (joinForm) {
        joinForm.addEventListener('submit', function(e) {
            const joinBtn = document.getElementById('joinBtn');
            if (joinBtn && !joinBtn.disabled) {
                joinBtn.disabled = true;
                joinBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>Joining...</span>';
            }
        });
    }
}

function expandTextPreview() {
    const preview = document.querySelector('.text-content-preview');
    const fullContent = document.getElementById('fullTextContent');
    
    if (preview && fullContent) {
        preview.style.display = 'none';
        fullContent.style.display = 'block';
    }
}

function collapseTextPreview() {
    const preview = document.querySelector('.text-content-preview');
    const fullContent = document.getElementById('fullTextContent');
    
    if (preview && fullContent) {
        preview.style.display = 'block';
        fullContent.style.display = 'none';
    }
}

// Auto-refresh page every 30 seconds for upcoming competitions
@if($competition->status === 'upcoming')
setInterval(() => {
    // Only refresh if the start time is within 1 minute
    const startTime = new Date('{{ $competition->start_time->toISOString() }}');
    const now = new Date();
    const timeDiff = startTime - now;
    
    if (timeDiff <= 60000 && timeDiff > 0) { // Within 1 minute
        window.location.reload();
    }
}, 30000);
@endif

// Auto-refresh for completed competitions
@if($competition->status === 'active' && $competition->end_time)
const endTime = new Date('{{ $competition->end_time->toISOString() }}');
const checkCompletion = setInterval(() => {
    if (new Date() >= endTime) {
        clearInterval(checkCompletion);
        setTimeout(() => window.location.reload(), 3000);
    }
}, 1000);
@endif

console.log('✅ Competition detail page loaded successfully!');
</script>
@endsection
