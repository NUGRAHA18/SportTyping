@extends('layouts.app')

@section('content')
<div class="competition-arena">
    <div class="container-fluid">
        
        {{-- Competition Header --}}
        <div class="competition-header">
            <div class="competition-info">
                <div class="competition-badge live">
                    <i class="fas fa-circle"></i>
                    <span>LIVE COMPETITION</span>
                </div>
                <h1 class="competition-title">{{ $competition->title }}</h1>
                <p class="competition-description">{{ $competition->description }}</p>
                
                <div class="competition-meta">
                    <div class="meta-item">
                        <i class="fas fa-{{ $competition->device_type === 'mobile' ? 'mobile-alt' : ($competition->device_type === 'pc' ? 'desktop' : 'devices') }}"></i>
                        <span>{{ ucfirst($competition->device_type) }} Only</span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-users"></i>
                        <span id="participant-count">{{ $competition->participants()->count() }}</span> Racers
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-signal"></i>
                        <span>{{ ucfirst($competition->text->difficulty_level) }}</span>
                    </div>
                </div>
            </div>
            
            <div class="competition-timer">
                <div class="timer-container">
                    <div class="timer-circle">
                        <div class="timer-value" id="competition-timer">
                            @if($competition->status === 'upcoming')
                                <span class="timer-label">Starting in</span>
                                <span class="timer-time">--:--</span>
                            @else
                                <span class="timer-label">Time Left</span>
                                <span class="timer-time">--:--</span>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="competition-status" id="competition-status">
                    @if($competition->status === 'upcoming')
                        <span class="status waiting">Waiting to Start</span>
                    @elseif($competition->status === 'active')
                        <span class="status racing">Racing Now!</span>
                    @else
                        <span class="status finished">Competition Ended</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Race Track Section --}}
        <div class="race-track-section">
            <div class="race-track-header">
                <h2>
                    <i class="fas fa-racing-flag"></i>
                    Live Race Track
                </h2>
                <div class="race-controls">
                    <button class="control-btn" id="fullscreen-btn" title="Fullscreen Mode">
                        <i class="fas fa-expand"></i>
                    </button>
                    <button class="control-btn" id="sound-btn" title="Toggle Sound">
                        <i class="fas fa-volume-up"></i>
                    </button>
                </div>
            </div>
            
            <div class="race-track-container" id="race-track">
                <div class="race-track-background">
                    <div class="track-lanes"></div>
                    <div class="finish-line">
                        <i class="fas fa-flag-checkered"></i>
                        <span>FINISH</span>
                    </div>
                </div>
                
                <div class="race-participants" id="race-participants">
                    {{-- Current User --}}
                    <div class="participant user-participant" data-user-id="{{ Auth::id() }}" data-is-bot="false">
                        <div class="participant-lane">
                            <div class="participant-avatar">
                                @if(Auth::user()->profile?->avatar)
                                    <img src="{{ asset('storage/' . Auth::user()->profile->avatar) }}" alt="{{ Auth::user()->username }}">
                                @else
                                    <div class="avatar-placeholder">
                                        {{ strtoupper(substr(Auth::user()->username, 0, 1)) }}
                                    </div>
                                @endif
                                <div class="participant-crown" style="display: none;">
                                    <i class="fas fa-crown"></i>
                                </div>
                            </div>
                            <div class="participant-info">
                                <div class="participant-name">{{ Auth::user()->username }} <span class="you-badge">YOU</span></div>
                                <div class="participant-league">{{ Auth::user()->profile?->league?->name ?? 'Novice' }}</div>
                            </div>
                            <div class="participant-track">
                                <div class="track-progress" data-progress="0">
                                    <div class="progress-fill"></div>
                                    <div class="progress-car">
                                        <i class="fas fa-car"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="participant-stats">
                                <div class="stat-item">
                                    <span class="stat-value wpm" data-wpm="0">0</span>
                                    <span class="stat-label">WPM</span>
                                </div>
                                <div class="stat-item">
                                    <span class="stat-value accuracy" data-accuracy="100">100%</span>
                                    <span class="stat-label">ACC</span>
                                </div>
                                <div class="stat-item position">
                                    <span class="position-value" data-position="1">#1</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Other Human Participants --}}
                    @foreach($competition->participants()->where('user_id', '!=', Auth::id())->where('is_bot', false)->with('user.profile.league')->get() as $participant)
                    <div class="participant human-participant" data-user-id="{{ $participant->user_id }}" data-is-bot="false">
                        <div class="participant-lane">
                            <div class="participant-avatar">
                                @if($participant->user->profile?->avatar)
                                    <img src="{{ asset('storage/' . $participant->user->profile->avatar) }}" alt="{{ $participant->user->username }}">
                                @else
                                    <div class="avatar-placeholder">
                                        {{ strtoupper(substr($participant->user->username, 0, 1)) }}
                                    </div>
                                @endif
                                <div class="participant-crown" style="display: none;">
                                    <i class="fas fa-crown"></i>
                                </div>
                            </div>
                            <div class="participant-info">
                                <div class="participant-name">{{ $participant->user->username }}</div>
                                <div class="participant-league">{{ $participant->user->profile?->league?->name ?? 'Novice' }}</div>
                            </div>
                            <div class="participant-track">
                                <div class="track-progress" data-progress="0">
                                    <div class="progress-fill"></div>
                                    <div class="progress-car">
                                        <i class="fas fa-car"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="participant-stats">
                                <div class="stat-item">
                                    <span class="stat-value wpm" data-wpm="0">0</span>
                                    <span class="stat-label">WPM</span>
                                </div>
                                <div class="stat-item">
                                    <span class="stat-value accuracy" data-accuracy="100">100%</span>
                                    <span class="stat-label">ACC</span>
                                </div>
                                <div class="stat-item position">
                                    <span class="position-value" data-position="1">#1</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    
                    {{-- Bot Participants --}}
                    @if(isset($bots) && count($bots) > 0)
                        @foreach($bots as $bot)
                        <div class="participant bot-participant" data-user-id="{{ $bot['id'] }}" data-is-bot="true">
                            <div class="participant-lane">
                                <div class="participant-avatar bot">
                                    <div class="avatar-placeholder bot">
                                        <i class="fas fa-robot"></i>
                                    </div>
                                    <div class="bot-indicator">
                                        <i class="fas fa-cpu"></i>
                                    </div>
                                </div>
                                <div class="participant-info">
                                    <div class="participant-name">{{ $bot['name'] }} <span class="bot-badge">BOT</span></div>
                                    <div class="participant-league">AI Level {{ rand(1, 5) }}</div>
                                </div>
                                <div class="participant-track">
                                    <div class="track-progress" data-progress="0">
                                        <div class="progress-fill bot"></div>
                                        <div class="progress-car bot">
                                            <i class="fas fa-robot"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="participant-stats">
                                    <div class="stat-item">
                                        <span class="stat-value wpm" data-wpm="0">0</span>
                                        <span class="stat-label">WPM</span>
                                    </div>
                                    <div class="stat-item">
                                        <span class="stat-value accuracy" data-accuracy="100">100%</span>
                                        <span class="stat-label">ACC</span>
                                    </div>
                                    <div class="stat-item position">
                                        <span class="position-value" data-position="1">#1</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @endif
                </div>
                
                {{-- Race Stats Overlay --}}
                <div class="race-stats-overlay">
                    <div class="leading-stats">
                        <div class="leading-user">
                            <div class="leading-avatar"></div>
                            <div class="leading-info">
                                <span class="leading-name">Waiting...</span>
                                <span class="leading-wpm">0 WPM</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Typing Area Section --}}
        <div class="typing-section" id="typing-section">
            @if($competition->status === 'active')
                <x-typing-area 
                    :textContent="$competition->text->content"
                    mode="competition"
                    :competitionId="$competition->id"
                    :showStats="true"
                    :autoFocus="true"
                    :allowBackspace="true"
                    :showProgress="false"
                />
            @else
                <div class="typing-area-placeholder">
                    <div class="placeholder-content">
                        @if($competition->status === 'upcoming')
                            <div class="placeholder-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <h3>Competition Starting Soon</h3>
                            <p>Get ready! The typing arena will open when the competition begins.</p>
                            <div class="text-preview">
                                <h4>Text Preview:</h4>
                                <div class="preview-content">
                                    "{{ Str::limit($competition->text->content, 100) }}"
                                </div>
                            </div>
                        @else
                            <div class="placeholder-icon finished">
                                <i class="fas fa-flag-checkered"></i>
                            </div>
                            <h3>Competition Finished</h3>
                            <p>This competition has ended. Check the final results below!</p>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        {{-- Live Leaderboard --}}
        <div class="live-leaderboard-section">
            <div class="leaderboard-header">
                <h2>
                    <i class="fas fa-trophy"></i>
                    Live Leaderboard
                </h2>
                <div class="leaderboard-filters">
                    <button class="filter-btn active" data-filter="all">All</button>
                    <button class="filter-btn" data-filter="human">Humans</button>
                    <button class="filter-btn" data-filter="bot">Bots</button>
                </div>
            </div>
            
            <div class="leaderboard-container" id="live-leaderboard">
                <div class="leaderboard-list">
                    {{-- Leaderboard entries will be populated by JavaScript --}}
                    <div class="leaderboard-loading">
                        <div class="loading-spinner"></div>
                        <span>Waiting for race to start...</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Competition Chat (Optional) --}}
        <div class="competition-chat-section" id="chat-section" style="display: none;">
            <div class="chat-header">
                <h3>
                    <i class="fas fa-comments"></i>
                    Race Chat
                </h3>
                <button class="chat-toggle" id="chat-toggle">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="chat-messages" id="chat-messages">
                <div class="chat-message system">
                    <span class="message-text">Welcome to the competition! Good luck everyone! 🏁</span>
                </div>
            </div>
            <div class="chat-input">
                <input type="text" id="chat-input" placeholder="Type a message..." maxlength="100">
                <button id="send-chat">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
        </div>

    </div>
</div>

{{-- Competition Results Modal --}}
<div class="competition-results-modal" id="results-modal" style="display: none;">
    <div class="modal-overlay"></div>
    <div class="modal-content">
        <div class="results-header">
            <div class="results-icon">
                <i class="fas fa-trophy"></i>
            </div>
            <h2>Competition Results</h2>
            <button class="close-modal" id="close-results">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="results-content">
            <div class="podium">
                <div class="podium-place second" id="second-place" style="display: none;">
                    <div class="podium-avatar"></div>
                    <div class="podium-name"></div>
                    <div class="podium-stats"></div>
                    <div class="podium-medal silver">
                        <i class="fas fa-medal"></i>
                    </div>
                </div>
                <div class="podium-place first" id="first-place" style="display: none;">
                    <div class="podium-avatar"></div>
                    <div class="podium-name"></div>
                    <div class="podium-stats"></div>
                    <div class="podium-medal gold">
                        <i class="fas fa-crown"></i>
                    </div>
                </div>
                <div class="podium-place third" id="third-place" style="display: none;">
                    <div class="podium-avatar"></div>
                    <div class="podium-name"></div>
                    <div class="podium-stats"></div>
                    <div class="podium-medal bronze">
                        <i class="fas fa-award"></i>
                    </div>
                </div>
            </div>
            
            <div class="full-results">
                <h3>Complete Results</h3>
                <div class="results-table" id="results-table">
                    {{-- Results will be populated by JavaScript --}}
                </div>
            </div>
        </div>
        
        <div class="results-actions">
            <button class="btn-primary" id="view-detailed-result">
                <i class="fas fa-chart-bar"></i>
                View My Stats
            </button>
            <button class="btn-secondary" id="join-another">
                <i class="fas fa-redo"></i>
                Join Another Race
            </button>
        </div>
    </div>
</div>

<style>
/* Competition Arena Styles */
.competition-arena {
    background: var(--bg-primary);
    min-height: calc(100vh - 76px);
    padding: 2rem 0;
}

/* Competition Header */
.competition-header {
    background: var(--bg-card);
    border-radius: var(--border-radius-lg);
    border: 1px solid var(--border-light);
    padding: 2.5rem;
    margin-bottom: 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
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
    background: linear-gradient(90deg, var(--accent-danger), var(--accent-secondary), var(--accent-success));
}

.competition-badge.live {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    background: rgba(239, 68, 68, 0.1);
    border: 1px solid rgba(239, 68, 68, 0.2);
    border-radius: var(--border-radius);
    color: var(--accent-danger);
    font-weight: 700;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 1rem;
}

.competition-badge.live i {
    animation: livePulse 2s infinite;
}

.competition-title {
    font-family: var(--font-display);
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 0.5rem;
    line-height: 1.2;
}

.competition-description {
    color: var(--text-secondary);
    font-size: 1.1rem;
    margin-bottom: 1.5rem;
    line-height: 1.6;
}

.competition-meta {
    display: flex;
    gap: 2rem;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--text-muted);
    font-weight: 500;
}

.meta-item i {
    color: var(--accent-primary);
}

/* Competition Timer */
.competition-timer {
    text-align: center;
}

.timer-container {
    margin-bottom: 1rem;
}

.timer-circle {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    background: var(--champion-gradient);
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: var(--shadow-lg);
    position: relative;
    margin: 0 auto;
}

.timer-circle::before {
    content: '';
    position: absolute;
    top: -5px;
    left: -5px;
    right: -5px;
    bottom: -5px;
    border-radius: 50%;
    background: linear-gradient(45deg, var(--accent-danger), var(--accent-secondary), var(--accent-success));
    z-index: -1;
    animation: timerRotate 20s linear infinite;
}

.timer-value {
    text-align: center;
    color: white;
}

.timer-label {
    display: block;
    font-size: 0.875rem;
    font-weight: 600;
    margin-bottom: 0.25rem;
    opacity: 0.9;
}

.timer-time {
    display: block;
    font-size: 1.5rem;
    font-weight: 700;
    font-family: var(--font-display);
}

.competition-status {
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.status.waiting {
    color: var(--accent-secondary);
}

.status.racing {
    color: var(--accent-success);
}

.status.finished {
    color: var(--text-muted);
}

/* Race Track Section */
.race-track-section {
    background: var(--bg-card);
    border-radius: var(--border-radius-lg);
    border: 1px solid var(--border-light);
    margin-bottom: 2rem;
    overflow: hidden;
}

.race-track-header {
    background: var(--bg-secondary);
    padding: 1.5rem 2rem;
    border-bottom: 1px solid var(--border-light);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.race-track-header h2 {
    font-family: var(--font-display);
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--text-primary);
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.race-track-header h2 i {
    color: var(--accent-danger);
}

.race-controls {
    display: flex;
    gap: 0.5rem;
}

.control-btn {
    width: 40px;
    height: 40px;
    border: 1px solid var(--border-light);
    background: var(--bg-card);
    border-radius: var(--border-radius);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--text-secondary);
    cursor: pointer;
    transition: all 0.3s ease;
}

.control-btn:hover {
    border-color: var(--accent-primary);
    color: var(--accent-primary);
    background: rgba(59, 130, 246, 0.05);
}

/* Race Track Container */
.race-track-container {
    position: relative;
    padding: 2rem;
    background: linear-gradient(135deg, var(--bg-primary) 0%, var(--bg-secondary) 100%);
}

.race-track-background {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    pointer-events: none;
}

.track-lanes {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: repeating-linear-gradient(
        to bottom,
        transparent 0px,
        transparent 70px,
        rgba(59, 130, 246, 0.1) 70px,
        rgba(59, 130, 246, 0.1) 71px
    );
}

.finish-line {
    position: absolute;
    top: 0;
    right: 2rem;
    bottom: 0;
    width: 4px;
    background: repeating-linear-gradient(
        to bottom,
        var(--accent-danger) 0px,
        var(--accent-danger) 10px,
        white 10px,
        white 20px
    );
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    gap: 0.5rem;
    color: var(--accent-danger);
    font-weight: 700;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.finish-line i {
    font-size: 1.5rem;
}

/* Race Participants */
.race-participants {
    position: relative;
    z-index: 2;
}

.participant {
    margin-bottom: 1.5rem;
    transition: all 0.3s ease;
}

.participant-lane {
    display: grid;
    grid-template-columns: 60px 200px 1fr 150px;
    gap: 1rem;
    align-items: center;
    background: var(--bg-card);
    border: 2px solid var(--border-light);
    border-radius: var(--border-radius);
    padding: 1rem;
    position: relative;
    overflow: hidden;
}

.participant.user-participant .participant-lane {
    border-color: var(--accent-primary);
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.05), var(--bg-card));
}

.participant.bot-participant .participant-lane {
    border-color: var(--accent-secondary);
    background: linear-gradient(135deg, rgba(245, 158, 11, 0.05), var(--bg-card));
}

/* Participant Avatar */
.participant-avatar {
    position: relative;
}

.participant-avatar img,
.avatar-placeholder {
    width: 50px;
    height: 50px;
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

.avatar-placeholder.bot {
    background: var(--medal-gradient);
}

.participant-crown {
    position: absolute;
    top: -8px;
    left: 50%;
    transform: translateX(-50%);
    color: var(--accent-secondary);
    font-size: 1.25rem;
    z-index: 3;
}

.bot-indicator {
    position: absolute;
    bottom: -2px;
    right: -2px;
    width: 18px;
    height: 18px;
    background: var(--accent-secondary);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 0.75rem;
    border: 2px solid white;
}

/* Participant Info */
.participant-info {
    min-width: 0;
}

.participant-name {
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 0.25rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.you-badge {
    background: var(--champion-gradient);
    color: white;
    padding: 0.125rem 0.5rem;
    border-radius: var(--border-radius-sm);
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.bot-badge {
    background: var(--medal-gradient);
    color: white;
    padding: 0.125rem 0.5rem;
    border-radius: var(--border-radius-sm);
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.participant-league {
    color: var(--text-muted);
    font-size: 0.875rem;
}

/* Participant Track */
.participant-track {
    position: relative;
    height: 40px;
    background: var(--bg-secondary);
    border-radius: var(--border-radius);
    border: 1px solid var(--border-light);
    overflow: hidden;
}

.track-progress {
    position: relative;
    height: 100%;
    transition: all 0.3s ease;
}

.progress-fill {
    height: 100%;
    background: var(--champion-gradient);
    border-radius: var(--border-radius);
    transition: width 0.5s ease;
    width: 0%;
}

.progress-fill.bot {
    background: var(--medal-gradient);
}

.progress-car {
    position: absolute;
    top: 50%;
    right: 5px;
    transform: translateY(-50%);
    color: white;
    font-size: 1.25rem;
    z-index: 2;
    transition: all 0.5s ease;
}

.progress-car.bot {
    color: var(--accent-secondary);
}

/* Participant Stats */
.participant-stats {
    display: flex;
    gap: 1rem;
    align-items: center;
}

.stat-item {
    text-align: center;
}

.stat-value {
    display: block;
    font-size: 1.125rem;
    font-weight: 700;
    color: var(--accent-primary);
    line-height: 1.2;
}

.stat-label {
    display: block;
    font-size: 0.75rem;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 1px;
}

.position-value {
    font-size: 1.25rem;
    font-weight: 700;
    padding: 0.5rem;
    background: var(--bg-secondary);
    border: 1px solid var(--border-light);
    border-radius: var(--border-radius);
    min-width: 50px;
    display: block;
    text-align: center;
}

/* Race Stats Overlay */
.race-stats-overlay {
    position: absolute;
    top: 1rem;
    left: 1rem;
    background: rgba(0, 0, 0, 0.8);
    backdrop-filter: blur(10px);
    border-radius: var(--border-radius);
    padding: 1rem;
    color: white;
    z-index: 10;
}

.leading-user {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.leading-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: var(--champion-gradient);
}

.leading-info {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.leading-name {
    font-weight: 600;
    font-size: 0.875rem;
}

.leading-wpm {
    font-size: 0.8rem;
    opacity: 0.9;
}

/* Typing Section */
.typing-section {
    margin-bottom: 2rem;
}

.typing-area-placeholder {
    background: var(--bg-card);
    border-radius: var(--border-radius-lg);
    border: 1px solid var(--border-light);
    padding: 4rem 2rem;
    text-align: center;
}

.placeholder-content {
    max-width: 500px;
    margin: 0 auto;
}

.placeholder-icon {
    width: 80px;
    height: 80px;
    background: var(--champion-gradient);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 2rem;
    font-size: 2rem;
    color: white;
}

.placeholder-icon.finished {
    background: var(--medal-gradient);
}

.placeholder-content h3 {
    font-family: var(--font-display);
    font-size: 1.75rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 1rem;
}

.placeholder-content p {
    color: var(--text-secondary);
    font-size: 1.1rem;
    margin-bottom: 2rem;
    line-height: 1.6;
}

.text-preview {
    background: var(--bg-secondary);
    border-radius: var(--border-radius);
    padding: 1.5rem;
    border: 1px solid var(--border-light);
}

.text-preview h4 {
    color: var(--text-primary);
    font-weight: 600;
    margin-bottom: 1rem;
}

.preview-content {
    font-family: 'Courier New', monospace;
    color: var(--text-secondary);
    font-size: 1rem;
    line-height: 1.6;
    font-style: italic;
}

/* Live Leaderboard */
.live-leaderboard-section {
    background: var(--bg-card);
    border-radius: var(--border-radius-lg);
    border: 1px solid var(--border-light);
    margin-bottom: 2rem;
}

.leaderboard-header {
    background: var(--bg-secondary);
    padding: 1.5rem 2rem;
    border-bottom: 1px solid var(--border-light);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.leaderboard-header h2 {
    font-family: var(--font-display);
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--text-primary);
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.leaderboard-header h2 i {
    color: var(--accent-secondary);
}

.leaderboard-filters {
    display: flex;
    gap: 0.5rem;
}

.filter-btn {
    padding: 0.5rem 1rem;
    border: 1px solid var(--border-light);
    background: var(--bg-card);
    color: var(--text-secondary);
    border-radius: var(--border-radius);
    cursor: pointer;
    font-weight: 500;
    transition: all 0.3s ease;
}

.filter-btn.active {
    background: var(--champion-gradient);
    color: white;
    border-color: var(--accent-primary);
}

.leaderboard-container {
    padding: 2rem;
}

.leaderboard-loading {
    text-align: center;
    padding: 3rem;
    color: var(--text-muted);
}

.loading-spinner {
    width: 40px;
    height: 40px;
    border: 4px solid var(--border-light);
    border-top: 4px solid var(--accent-primary);
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin: 0 auto 1rem;
}

/* Competition Chat */
.competition-chat-section {
    position: fixed;
    bottom: 2rem;
    right: 2rem;
    width: 350px;
    background: var(--bg-card);
    border-radius: var(--border-radius-lg);
    border: 1px solid var(--border-light);
    box-shadow: var(--shadow-xl);
    z-index: 1000;
}

.chat-header {
    background: var(--bg-secondary);
    padding: 1rem;
    border-bottom: 1px solid var(--border-light);
    border-radius: var(--border-radius-lg) var(--border-radius-lg) 0 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.chat-header h3 {
    font-size: 1rem;
    font-weight: 600;
    color: var(--text-primary);
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.chat-toggle {
    background: none;
    border: none;
    color: var(--text-muted);
    cursor: pointer;
    padding: 0.25rem;
}

.chat-messages {
    height: 200px;
    overflow-y: auto;
    padding: 1rem;
}

.chat-message {
    margin-bottom: 0.75rem;
}

.chat-message.system .message-text {
    color: var(--text-muted);
    font-style: italic;
    font-size: 0.875rem;
}

.chat-input {
    padding: 1rem;
    border-top: 1px solid var(--border-light);
    display: flex;
    gap: 0.5rem;
}

.chat-input input {
    flex: 1;
    padding: 0.5rem;
    border: 1px solid var(--border-light);
    border-radius: var(--border-radius);
    background: var(--bg-secondary);
}

.chat-input button {
    padding: 0.5rem;
    background: var(--champion-gradient);
    color: white;
    border: none;
    border-radius: var(--border-radius);
    cursor: pointer;
}

/* Competition Results Modal */
.competition-results-modal {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 2000;
    display: flex;
    align-items: center;
    justify-content: center;
}

.modal-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.8);
    backdrop-filter: blur(10px);
}

.modal-content {
    background: var(--bg-card);
    border-radius: var(--border-radius-lg);
    border: 1px solid var(--border-light);
    max-width: 800px;
    width: 90%;
    max-height: 90vh;
    overflow-y: auto;
    position: relative;
    z-index: 1;
}

.results-header {
    background: var(--bg-secondary);
    padding: 2rem;
    border-bottom: 1px solid var(--border-light);
    text-align: center;
    position: relative;
}

.results-icon {
    width: 80px;
    height: 80px;
    background: var(--medal-gradient);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    font-size: 2rem;
    color: white;
}

.results-header h2 {
    font-family: var(--font-display);
    font-size: 2rem;
    font-weight: 700;
    color: var(--text-primary);
}

.close-modal {
    position: absolute;
    top: 1rem;
    right: 1rem;
    background: none;
    border: none;
    font-size: 1.5rem;
    color: var(--text-muted);
    cursor: pointer;
}

.results-content {
    padding: 2rem;
}

.podium {
    display: flex;
    justify-content: center;
    align-items: end;
    gap: 1rem;
    margin-bottom: 3rem;
    height: 200px;
}

.podium-place {
    text-align: center;
    padding: 1rem;
    border-radius: var(--border-radius);
    position: relative;
    min-width: 120px;
}

.podium-place.first {
    background: linear-gradient(135deg, rgba(245, 158, 11, 0.1), var(--bg-card));
    border: 2px solid var(--accent-secondary);
    height: 180px;
    order: 2;
}

.podium-place.second {
    background: linear-gradient(135deg, rgba(148, 163, 184, 0.1), var(--bg-card));
    border: 2px solid var(--text-muted);
    height: 140px;
    order: 1;
}

.podium-place.third {
    background: linear-gradient(135deg, rgba(205, 124, 50, 0.1), var(--bg-card));
    border: 2px solid #cd7c32;
    height: 100px;
    order: 3;
}

.podium-medal {
    position: absolute;
    top: -15px;
    left: 50%;
    transform: translateX(-50%);
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1rem;
}

.podium-medal.gold { background: var(--medal-gradient); }
.podium-medal.silver { background: linear-gradient(135deg, #94a3b8, #64748b); }
.podium-medal.bronze { background: linear-gradient(135deg, #cd7c32, #a0522d); }

.results-actions {
    padding: 2rem;
    border-top: 1px solid var(--border-light);
    display: flex;
    gap: 1rem;
    justify-content: center;
}

/* Animations */
@keyframes livePulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}

@keyframes timerRotate {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

/* Responsive Design */
@media (max-width: 1200px) {
    .participant-lane {
        grid-template-columns: 50px 150px 1fr 120px;
        gap: 0.75rem;
    }
    
    .competition-header {
        flex-direction: column;
        gap: 2rem;
        text-align: center;
    }
    
    .competition-meta {
        justify-content: center;
    }
}

@media (max-width: 768px) {
    .competition-arena {
        padding: 1rem 0;
    }
    
    .competition-header {
        padding: 2rem 1rem;
    }
    
    .competition-title {
        font-size: 2rem;
    }
    
    .competition-meta {
        flex-direction: column;
        gap: 1rem;
    }
    
    .race-track-container {
        padding: 1rem;
    }
    
    .race-track-header {
        padding: 1rem;
        flex-direction: column;
        gap: 1rem;
    }
    
    .participant-lane {
        grid-template-columns: 1fr;
        gap: 1rem;
        text-align: center;
    }
    
    .participant-track {
        order: -1;
    }
    
    .participant-stats {
        justify-content: center;
    }
    
    .leaderboard-header {
        padding: 1rem;
        flex-direction: column;
        gap: 1rem;
    }
    
    .competition-chat-section {
        bottom: 0;
        right: 0;
        left: 0;
        width: auto;
        border-radius: 0;
    }
    
    .modal-content {
        margin: 1rem;
        width: auto;
    }
    
    .podium {
        flex-direction: column;
        height: auto;
        gap: 1rem;
    }
    
    .podium-place {
        width: 100%;
        height: auto !important;
        order: unset !important;
    }
    
    .results-actions {
        flex-direction: column;
    }
}

@media (max-width: 480px) {
    .timer-circle {
        width: 100px;
        height: 100px;
    }
    
    .timer-time {
        font-size: 1.25rem;
    }
    
    .race-participants {
        margin: 0 -0.5rem;
    }
    
    .participant-lane {
        padding: 0.75rem;
    }
    
    .avatar-placeholder,
    .participant-avatar img {
        width: 40px;
        height: 40px;
    }
    
    .participant-track {
        height: 30px;
    }
}
</style>

<script>
// Competition Arena Controller
class CompetitionArenaController {
    constructor() {
        this.competitionId = {{ $competition->id }};
        this.userId = {{ Auth::id() }};
        this.competitionStatus = '{{ $competition->status }}';
        this.participants = new Map();
        this.bots = @json($bots ?? []);
        this.typingController = null;
        this.websocketConnection = null;
        this.raceUpdateInterval = null;
        this.timerInterval = null;
        
        this.init();
    }
    
    init() {
        this.setupEventListeners();
        this.initializeParticipants();
        this.setupTypingArea();
        this.startTimer();
        this.initializeWebSocket();
        this.setupRaceSimulation();
        
        // Initialize UI components
        this.initializeLeaderboard();
        this.setupResponsiveFeatures();
    }
    
    setupEventListeners() {
        // Typing area events
        document.addEventListener('typing:started', (e) => this.handleTypingStarted(e));
        document.addEventListener('typing:progress', (e) => this.handleTypingProgress(e));
        document.addEventListener('typing:completed', (e) => this.handleTypingCompleted(e));
        
        // UI controls
        const fullscreenBtn = document.getElementById('fullscreen-btn');
        const soundBtn = document.getElementById('sound-btn');
        const chatToggle = document.getElementById('chat-toggle');
        
        if (fullscreenBtn) {
            fullscreenBtn.addEventListener('click', () => this.toggleFullscreen());
        }
        
        if (soundBtn) {
            soundBtn.addEventListener('click', () => this.toggleSound());
        }
        
        if (chatToggle) {
            chatToggle.addEventListener('click', () => this.toggleChat());
        }
        
        // Filter buttons
        const filterBtns = document.querySelectorAll('.filter-btn');
        filterBtns.forEach(btn => {
            btn.addEventListener('click', (e) => this.filterLeaderboard(e.target.dataset.filter));
        });
        
        // Modal controls
        const closeResults = document.getElementById('close-results');
        if (closeResults) {
            closeResults.addEventListener('click', () => this.closeResults());
        }
        
        // Keyboard shortcuts
        document.addEventListener('keydown', (e) => this.handleKeyboard(e));
    }
    
    initializeParticipants() {
        const participantElements = document.querySelectorAll('.participant');
        
        participantElements.forEach(element => {
            const userId = element.dataset.userId;
            const isBot = element.dataset.isBot === 'true';
            
            this.participants.set(userId, {
                element: element,
                userId: userId,
                isBot: isBot,
                progress: 0,
                wpm: 0,
                accuracy: 100,
                position: 1,
                finished: false
            });
        });
    }
    
    setupTypingArea() {
        // Listen for typing area initialization
        const typingContainer = document.querySelector('.typing-area-container');
        if (typingContainer) {
            // Wait for TypingAreaController to initialize
            setTimeout(() => {
                this.typingController = typingContainer.typingController;
                if (this.typingController) {
                    this.bindTypingEvents();
                }
            }, 100);
        }
    }
    
    bindTypingEvents() {
        // Override typing area events for competition
        const typingInput = document.querySelector('#typing-input');
        if (typingInput) {
            typingInput.addEventListener('input', () => {
                if (this.typingController) {
                    const stats = this.typingController.getStats();
                    this.updateUserProgress(stats);
                    this.broadcastProgress(stats);
                }
            });
        }
    }
    
    startTimer() {
        const timerElement = document.getElementById('competition-timer');
        if (!timerElement) return;
        
        this.timerInterval = setInterval(() => {
            this.updateTimer();
        }, 1000);
    }
    
    updateTimer() {
        const timerElement = document.getElementById('competition-timer');
        const statusElement = document.getElementById('competition-status');
        
        if (!timerElement || !statusElement) return;
        
        // Mock timer logic - replace with actual competition timing
        const now = new Date();
        const competitionEnd = new Date(now.getTime() + 5 * 60 * 1000); // 5 minutes from now
        const timeLeft = Math.max(0, competitionEnd - now);
        
        const minutes = Math.floor(timeLeft / 60000);
        const seconds = Math.floor((timeLeft % 60000) / 1000);
        
        const timeElement = timerElement.querySelector('.timer-time');
        if (timeElement) {
            timeElement.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;
        }
        
        if (timeLeft === 0) {
            this.endCompetition();
        }
    }
    
    initializeWebSocket() {
        // Initialize WebSocket connection for real-time updates
        // This would connect to your broadcasting service (Pusher, etc.)
        
        if (window.Echo) {
            this.websocketConnection = window.Echo.join(`competition.${this.competitionId}`)
                .here((users) => {
                    console.log('Users in competition:', users);
                })
                .joining((user) => {
                    console.log('User joined:', user);
                    this.addParticipant(user);
                })
                .leaving((user) => {
                    console.log('User left:', user);
                    this.removeParticipant(user);
                })
                .listen('CompetitionProgress', (e) => {
                    this.handleRemoteProgress(e);
                })
                .listen('CompetitionEnded', (e) => {
                    this.handleCompetitionEnd(e);
                });
        }
    }
    
    setupRaceSimulation() {
        // Simulate bot progress for demo
        this.raceUpdateInterval = setInterval(() => {
            this.updateBots();
        }, 1000);
    }
    
    updateBots() {
        this.bots.forEach(bot => {
            const participant = this.participants.get(bot.id);
            if (!participant || participant.finished) return;
            
            // Simulate bot progress
            const progressIncrement = (Math.random() * 2) + 0.5; // 0.5-2.5% per second
            participant.progress = Math.min(100, participant.progress + progressIncrement);
            
            // Simulate WPM fluctuation
            const baseWPM = bot.typing_speed || 60;
            participant.wmp = Math.max(0, baseWPM + (Math.random() * 20) - 10);
            
            // Simulate accuracy
            participant.accuracy = Math.max(85, bot.accuracy + (Math.random() * 10) - 5);
            
            if (participant.progress >= 100) {
                participant.finished = true;
            }
            
            this.updateParticipantDisplay(participant);
        });
        
        this.updatePositions();
        this.updateLeaderboard();
    }
    
    updateUserProgress(stats) {
        const userParticipant = this.participants.get(this.userId.toString());
        if (!userParticipant) return;
        
        userParticipant.progress = stats.progress || 0;
        userParticipant.wmp = stats.wmp || 0;
        userParticipant.accuracy = stats.accuracy || 100;
        
        if (userParticipant.progress >= 100) {
            userParticipant.finished = true;
        }
        
        this.updateParticipantDisplay(userParticipant);
        this.updatePositions();
        this.updateLeaderboard();
    }
    
    updateParticipantDisplay(participant) {
        const element = participant.element;
        
        // Update progress bar
        const progressFill = element.querySelector('.progress-fill');
        if (progressFill) {
            progressFill.style.width = `${participant.progress}%`;
        }
        
        // Update stats
        const wmpElement = element.querySelector('.stat-value.wmp');
        const accuracyElement = element.querySelector('.stat-value.accuracy');
        const positionElement = element.querySelector('.position-value');
        
        if (wmpElement) wmpElement.textContent = Math.round(participant.wmp);
        if (accuracyElement) accuracyElement.textContent = Math.round(participant.accuracy) + '%';
        if (positionElement) positionElement.textContent = `#${participant.position}`;
        
        // Update crown for leader
        const crown = element.querySelector('.participant-crown');
        if (crown) {
            crown.style.display = participant.position === 1 ? 'block' : 'none';
        }
        
        // Add finished state
        if (participant.finished) {
            element.classList.add('finished');
        }
    }
    
    updatePositions() {
        // Sort participants by progress
        const sortedParticipants = Array.from(this.participants.values())
            .sort((a, b) => b.progress - a.progress);
        
        // Update positions
        sortedParticipants.forEach((participant, index) => {
            participant.position = index + 1;
        });
        
        // Update leading stats overlay
        const leader = sortedParticipants[0];
        if (leader) {
            this.updateLeadingStats(leader);
        }
    }
    
    updateLeadingStats(leader) {
        const leadingName = document.querySelector('.leading-name');
        const leadingWMP = document.querySelector('.leading-wmp');
        const leadingAvatar = document.querySelector('.leading-avatar');
        
        if (leadingName && leadingWMP && leadingAvatar) {
            const participantName = leader.element.querySelector('.participant-name');
            const participantAvatar = leader.element.querySelector('.participant-avatar img, .avatar-placeholder');
            
            if (participantName) {
                leadingName.textContent = participantName.textContent.replace(' YOU', '').replace(' BOT', '');
            }
            
            leadingWMP.textContent = `${Math.round(leader.wmp)} WPM`;
            
            if (participantAvatar) {
                if (participantAvatar.tagName === 'IMG') {
                    leadingAvatar.style.backgroundImage = `url(${participantAvatar.src})`;
                    leadingAvatar.style.backgroundSize = 'cover';
                } else {
                    leadingAvatar.style.background = window.getComputedStyle(participantAvatar).background;
                    leadingAvatar.textContent = participantAvatar.textContent;
                }
            }
        }
    }
    
    updateLeaderboard() {
        const leaderboardContainer = document.getElementById('live-leaderboard');
        const leaderboardList = leaderboardContainer.querySelector('.leaderboard-list');
        
        if (!leaderboardList) return;
        
        // Clear loading state
        const loading = leaderboardList.querySelector('.leaderboard-loading');
        if (loading) {
            loading.remove();
        }
        
        // Get sorted participants
        const sortedParticipants = Array.from(this.participants.values())
            .sort((a, b) => b.progress - a.progress);
        
        // Generate leaderboard HTML
        leaderboardList.innerHTML = sortedParticipants.map((participant, index) => {
            const participantInfo = this.getParticipantInfo(participant);
            const medal = index < 3 ? ['🥇', '🥈', '🥉'][index] : '';
            
            return `
                <div class="leaderboard-item ${participant.userId === this.userId.toString() ? 'current-user' : ''} ${participant.isBot ? 'bot' : 'human'}">
                    <div class="rank">${medal} #${index + 1}</div>
                    <div class="participant-info">
                        <div class="avatar">${participantInfo.avatar}</div>
                        <div class="info">
                            <div class="name">${participantInfo.name}</div>
                            <div class="badges">${participantInfo.badges}</div>
                        </div>
                    </div>
                    <div class="stats">
                        <div class="wmp">${Math.round(participant.wmp)} WPM</div>
                        <div class="accuracy">${Math.round(participant.accuracy)}%</div>
                        <div class="progress">${Math.round(participant.progress)}%</div>
                    </div>
                </div>
            `;
        }).join('');
    }
    
    getParticipantInfo(participant) {
        const nameElement = participant.element.querySelector('.participant-name');
        const avatarElement = participant.element.querySelector('.participant-avatar img, .avatar-placeholder');
        
        let name = 'Unknown';
        let avatar = 'U';
        let badges = '';
        
        if (nameElement) {
            name = nameElement.textContent.trim();
            
            if (name.includes('YOU')) {
                badges += '<span class="you-badge">YOU</span>';
                name = name.replace(' YOU', '');
            }
            
            if (name.includes('BOT')) {
                badges += '<span class="bot-badge">BOT</span>';
                name = name.replace(' BOT', '');
            }
        }
        
        if (avatarElement) {
            if (avatarElement.tagName === 'IMG') {
                avatar = `<img src="${avatarElement.src}" alt="${name}">`;
            } else {
                avatar = avatarElement.textContent || avatarElement.innerHTML;
            }
        }
        
        return { name, avatar, badges };
    }
    
    filterLeaderboard(filter) {
        const filterBtns = document.querySelectorAll('.filter-btn');
        const leaderboardItems = document.querySelectorAll('.leaderboard-item');
        
        // Update active filter button
        filterBtns.forEach(btn => {
            btn.classList.toggle('active', btn.dataset.filter === filter);
        });
        
        // Filter leaderboard items
        leaderboardItems.forEach(item => {
            let show = true;
            
            if (filter === 'human' && item.classList.contains('bot')) {
                show = false;
            } else if (filter === 'bot' && !item.classList.contains('bot')) {
                show = false;
            }
            
            item.style.display = show ? 'flex' : 'none';
        });
    }
    
    handleTypingStarted(e) {
        console.log('User started typing');
        this.broadcastEvent('typing_started');
    }
    
    handleTypingProgress(e) {
        const stats = e.detail.stats;
        this.updateUserProgress(stats);
    }
    
    handleTypingCompleted(e) {
        const stats = e.detail.stats;
        console.log('User completed typing:', stats);
        
        this.broadcastEvent('typing_completed', stats);
        
        // Check if user won
        const userParticipant = this.participants.get(this.userId.toString());
        if (userParticipant && userParticipant.position === 1) {
            this.showVictoryEffect();
        }
        
        // Auto-show results after delay
        setTimeout(() => {
            this.showResults();
        }, 3000);
    }
    
    showVictoryEffect() {
        // Add victory animation for winning user
        const userParticipant = document.querySelector('.user-participant');
        if (userParticipant) {
            userParticipant.classList.add('victory');
            
            // Create confetti effect
            this.createConfetti();
        }
    }
    
    createConfetti() {
        // Simple confetti animation
        const colors = ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6'];
        
        for (let i = 0; i < 50; i++) {
            const confetti = document.createElement('div');
            confetti.style.cssText = `
                position: fixed;
                top: -10px;
                left: ${Math.random() * 100}%;
                width: 10px;
                height: 10px;
                background: ${colors[Math.floor(Math.random() * colors.length)]};
                z-index: 9999;
                pointer-events: none;
                animation: confettiFall 3s linear forwards;
            `;
            
            document.body.appendChild(confetti);
            
            setTimeout(() => confetti.remove(), 3000);
        }
    }
    
    broadcastProgress(stats) {
        if (this.websocketConnection) {
            this.websocketConnection.whisper('progress.updated', {
                user_id: this.userId,
                progress: stats.progress,
                wmp: stats.wmp,
                accuracy: stats.accuracy
            });
        }
    }
    
    broadcastEvent(eventType, data = {}) {
        if (this.websocketConnection) {
            this.websocketConnection.whisper(eventType, {
                user_id: this.userId,
                timestamp: Date.now(),
                ...data
            });
        }
    }
    
    handleRemoteProgress(event) {
        const { user_id, progress, wmp, accuracy } = event;
        const participant = this.participants.get(user_id.toString());
        
        if (participant && user_id !== this.userId) {
            participant.progress = progress;
            participant.wmp = wmp;
            participant.accuracy = accuracy;
            
            this.updateParticipantDisplay(participant);
            this.updatePositions();
            this.updateLeaderboard();
        }
    }
    
    endCompetition() {
        clearInterval(this.timerInterval);
        clearInterval(this.raceUpdateInterval);
        
        this.competitionStatus = 'completed';
        
        // Update status
        const statusElement = document.getElementById('competition-status');
        if (statusElement) {
            statusElement.innerHTML = '<span class="status finished">Competition Ended</span>';
        }
        
        // Show results
        setTimeout(() => {
            this.showResults();
        }, 2000);
    }
    
    showResults() {
        const resultsModal = document.getElementById('results-modal');
        if (resultsModal) {
            resultsModal.style.display = 'flex';
            this.populateResults();
        }
    }
    
    populateResults() {
        // Get final sorted results
        const sortedParticipants = Array.from(this.participants.values())
            .sort((a, b) => {
                if (b.finished !== a.finished) return b.finished - a.finished;
                return b.progress - a.progress;
            });
        
        // Populate podium
        const podiumPlaces = ['first', 'second', 'third'];
        podiumPlaces.forEach((place, index) => {
            const placeElement = document.getElementById(`${place}-place`);
            const participant = sortedParticipants[index];
            
            if (placeElement && participant) {
                const participantInfo = this.getParticipantInfo(participant);
                
                placeElement.querySelector('.podium-avatar').innerHTML = participantInfo.avatar;
                placeElement.querySelector('.podium-name').textContent = participantInfo.name;
                placeElement.querySelector('.podium-stats').innerHTML = `
                    ${Math.round(participant.wmp)} WPM • ${Math.round(participant.accuracy)}%
                `;
                
                placeElement.style.display = 'block';
            }
        });
        
        // Populate full results table
        const resultsTable = document.getElementById('results-table');
        if (resultsTable) {
            resultsTable.innerHTML = sortedParticipants.map((participant, index) => {
                const participantInfo = this.getParticipantInfo(participant);
                return `
                    <div class="result-row ${participant.userId === this.userId.toString() ? 'current-user' : ''}">
                        <div class="position">#${index + 1}</div>
                        <div class="participant">${participantInfo.name} ${participantInfo.badges}</div>
                        <div class="wmp">${Math.round(participant.wmp)} WPM</div>
                        <div class="accuracy">${Math.round(participant.accuracy)}%</div>
                        <div class="progress">${Math.round(participant.progress)}%</div>
                    </div>
                `;
            }).join('');
        }
    }
    
    closeResults() {
        const resultsModal = document.getElementById('results-modal');
        if (resultsModal) {
            resultsModal.style.display = 'none';
        }
    }
    
    toggleFullscreen() {
        const raceTrack = document.getElementById('race-track');
        if (!document.fullscreenElement) {
            raceTrack.requestFullscreen().catch(err => {
                console.log('Fullscreen error:', err);
            });
        } else {
            document.exitFullscreen();
        }
    }
    
    toggleSound() {
        const soundBtn = document.getElementById('sound-btn');
        const icon = soundBtn.querySelector('i');
        
        if (icon.classList.contains('fa-volume-up')) {
            icon.className = 'fas fa-volume-mute';
            // Mute sounds
        } else {
            icon.className = 'fas fa-volume-up';
            // Unmute sounds
        }
    }
    
    toggleChat() {
        const chatSection = document.getElementById('chat-section');
        if (chatSection) {
            const isVisible = chatSection.style.display !== 'none';
            chatSection.style.display = isVisible ? 'none' : 'block';
        }
    }
    
    handleKeyboard(e) {
        if (e.key === 'F11') {
            e.preventDefault();
            this.toggleFullscreen();
        } else if (e.key === 'Escape' && document.fullscreenElement) {
            document.exitFullscreen();
        }
    }
    
    initializeLeaderboard() {
        // Initial leaderboard setup
        this.updateLeaderboard();
    }
    
    setupResponsiveFeatures() {
        // Add responsive event listeners
        window.addEventListener('resize', () => {
            this.handleResize();
        });
    }
    
    handleResize() {
        // Handle responsive changes
        if (window.innerWidth < 768) {
            // Mobile optimizations
            const chatSection = document.getElementById('chat-section');
            if (chatSection && chatSection.style.display !== 'none') {
                chatSection.style.display = 'none';
            }
        }
    }
}

// Initialize competition arena when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    @if($competition->status === 'active' || $competition->status === 'upcoming')
        new CompetitionArenaController();
    @endif
});

// Add confetti animation CSS
const style = document.createElement('style');
style.textContent = `
    @keyframes confettiFall {
        0% {
            transform: translateY(-100vh) rotate(0deg);
            opacity: 1;
        }
        100% {
            transform: translateY(100vh) rotate(720deg);
            opacity: 0;
        }
    }
    
    .participant.victory .participant-lane {
        animation: victoryPulse 1s ease-in-out 3;
        border-color: var(--accent-secondary) !important;
        background: linear-gradient(135deg, rgba(245, 158, 11, 0.2), var(--bg-card)) !important;
    }
    
    @keyframes victoryPulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.02); }
    }
    
    .leaderboard-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        border-radius: var(--border-radius);
        margin-bottom: 0.5rem;
        background: var(--bg-secondary);
        border: 1px solid var(--border-light);
        transition: all 0.3s ease;
    }
    
    .leaderboard-item.current-user {
        border-color: var(--accent-primary);
        background: rgba(59, 130, 246, 0.05);
    }
    
    .leaderboard-item .rank {
        font-weight: 700;
        min-width: 60px;
    }
    
    .leaderboard-item .participant-info {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        flex: 1;
    }
    
    .leaderboard-item .avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: var(--champion-gradient);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
    }
    
    .leaderboard-item .avatar img {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
    }
    
    .leaderboard-item .stats {
        display: flex;
        gap: 1rem;
        text-align: right;
    }
    
    .result-row {
        display: grid;
        grid-template-columns: 60px 1fr 80px 80px 80px;
        gap: 1rem;
        padding: 1rem;
        border-bottom: 1px solid var(--border-light);
        align-items: center;
    }
    
    .result-row.current-user {
        background: rgba(59, 130, 246, 0.05);
        border-color: var(--accent-primary);
    }
`;
document.head.appendChild(style);
</script>
@endsection
