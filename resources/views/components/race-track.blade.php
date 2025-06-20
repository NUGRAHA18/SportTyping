{{-- resources/views/components/race-track.blade.php --}}
@props([
    'participants' => [],
    'currentUser' => null,
    'mode' => 'competition', // competition, practice, demo
    'showLeaderboard' => true,
    'animated' => true,
    'textLength' => 100
])

<div class="race-track-component" data-mode="{{ $mode }}" data-animated="{{ $animated ? 'true' : 'false' }}">
    <!-- Race Track Header -->
    <div class="race-header">
        <div class="race-info">
            <h3 class="race-title">
                @if($mode === 'competition')
                    <i class="fas fa-racing-flag"></i>
                    Live Competition
                @elseif($mode === 'practice')
                    <i class="fas fa-keyboard"></i>
                    Practice Session
                @else
                    <i class="fas fa-trophy"></i>
                    Typing Race
                @endif
            </h3>
            <div class="race-stats">
                <span class="participant-count">{{ count($participants) }} racers</span>
                @if($mode === 'competition')
                    <span class="race-status live">
                        <i class="fas fa-broadcast-tower"></i>
                        LIVE
                    </span>
                @endif
            </div>
        </div>
        
        @if($mode === 'competition')
        <div class="race-timer">
            <div class="timer-label">Race Time</div>
            <div class="timer-display" id="race-timer">00:00</div>
        </div>
        @endif
    </div>

    <!-- Race Track Area -->
    <div class="race-track-area">
        <div class="track-container">
            <!-- Track Background -->
            <div class="track-background">
                <div class="track-lines">
                    @for($i = 0; $i < count($participants); $i++)
                    <div class="track-line"></div>
                    @endfor
                </div>
                <div class="distance-markers">
                    <div class="marker start">
                        <div class="marker-line"></div>
                        <div class="marker-label">START</div>
                    </div>
                    <div class="marker quarter">
                        <div class="marker-line"></div>
                        <div class="marker-label">25%</div>
                    </div>
                    <div class="marker half">
                        <div class="marker-line"></div>
                        <div class="marker-label">50%</div>
                    </div>
                    <div class="marker three-quarter">
                        <div class="marker-line"></div>
                        <div class="marker-label">75%</div>
                    </div>
                    <div class="marker finish">
                        <div class="marker-line"></div>
                        <div class="marker-label">FINISH</div>
                        <img src="/image/ui/race_finish.svg" alt="Finish Line" class="finish-flag">
                    </div>
                </div>
            </div>

            <!-- Race Lanes -->
            <div class="race-lanes">
                @foreach($participants as $index => $participant)
                <div class="race-lane {{ $currentUser && $participant['id'] == $currentUser['id'] ? 'current-user' : '' }}" 
                     data-participant="{{ $participant['id'] }}" 
                     data-lane="{{ $index }}">
                    
                    <div class="lane-info">
                        <div class="racer-position">
                            <span class="position-number">#{{ $index + 1 }}</span>
                        </div>
                        <div class="racer-avatar">
                            @if(isset($participant['avatar']) && $participant['avatar'])
                                <img src="{{ $participant['avatar'] }}" alt="{{ $participant['name'] }}">
                            @else
                                <div class="avatar-placeholder">
                                    {{ strtoupper(substr($participant['name'], 0, 1)) }}
                                </div>
                            @endif
                            @if($participant['type'] ?? 'user' === 'bot')
                                <div class="bot-indicator">
                                    <i class="fas fa-robot"></i>
                                </div>
                            @endif
                        </div>
                        <div class="racer-details">
                            <div class="racer-name">{{ $participant['name'] }}</div>
                            <div class="racer-league">
                                @if(isset($participant['league']))
                                    <img src="/image/leagues/{{ strtolower($participant['league']) }}.png" alt="League" class="league-icon">
                                    {{ $participant['league'] }}
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Racer Car -->
                    <div class="racer-car" data-progress="0">
                        <div class="car-container">
                            <img src="/image/ui/mobil{{ ($index % 4) + 1 }}.svg" alt="{{ $participant['name'] }} car" class="car-image">
                            <div class="car-effects">
                                <div class="speed-lines"></div>
                                <div class="exhaust-smoke"></div>
                            </div>
                        </div>
                        
                        <!-- Progress Indicator -->
                        <div class="progress-bubble">
                            <span class="progress-text">0%</span>
                        </div>
                    </div>

                    <!-- Lane Stats -->
                    <div class="lane-stats">
                        <div class="stat-item">
                            <span class="stat-label">WPM</span>
                            <span class="stat-value wpm-value">0</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Accuracy</span>
                            <span class="stat-value accuracy-value">100%</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Leaderboard Sidebar -->
    @if($showLeaderboard)
    <div class="race-leaderboard">
        <div class="leaderboard-header">
            <h4>
                <i class="fas fa-list-ol"></i>
                Live Rankings
            </h4>
            <div class="auto-update">
                <i class="fas fa-sync-alt"></i>
                Auto-updating
            </div>
        </div>
        
        <div class="leaderboard-list" id="race-leaderboard">
            @foreach($participants as $index => $participant)
            <div class="leaderboard-item" data-participant="{{ $participant['id'] }}">
                <div class="item-position">{{ $index + 1 }}</div>
                <div class="item-avatar">
                    @if(isset($participant['avatar']) && $participant['avatar'])
                        <img src="{{ $participant['avatar'] }}" alt="{{ $participant['name'] }}">
                    @else
                        <div class="avatar-placeholder">
                            {{ strtoupper(substr($participant['name'], 0, 1)) }}
                        </div>
                    @endif
                </div>
                <div class="item-info">
                    <div class="item-name">{{ $participant['name'] }}</div>
                    <div class="item-stats">
                        <span class="item-wpm">0 WPM</span>
                        <span class="item-progress">0%</span>
                    </div>
                </div>
                <div class="item-status">
                    @if($participant['type'] ?? 'user' === 'bot')
                        <i class="fas fa-robot"></i>
                    @else
                        <div class="online-indicator"></div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

<style>
.race-track-component {
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: var(--border-radius-lg);
    overflow: hidden;
}

/* Race Header */
.race-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(16, 185, 129, 0.1));
    border-bottom: 1px solid var(--border-light);
    padding: 1.5rem 2rem;
}

.race-title {
    font-family: var(--font-display);
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.race-title i {
    color: var(--accent-primary);
}

.race-stats {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.participant-count {
    color: var(--text-secondary);
    font-size: 0.9rem;
}

.race-status.live {
    background: var(--accent-danger);
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: var(--border-radius);
    font-size: 0.8rem;
    font-weight: 600;
    animation: pulse-live 2s infinite;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

@keyframes pulse-live {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.7; }
}

.race-timer {
    text-align: right;
}

.timer-label {
    color: var(--text-secondary);
    font-size: 0.9rem;
    margin-bottom: 0.25rem;
}

.timer-display {
    font-family: var(--font-mono);
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--accent-primary);
}

/* Race Track Area */
.race-track-area {
    display: grid;
    grid-template-columns: 1fr auto;
    gap: 2rem;
    padding: 2rem;
}

.track-container {
    position: relative;
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 50%, #f1f5f9 100%);
    border: 1px solid var(--border-light);
    border-radius: var(--border-radius);
    padding: 2rem;
    min-height: 400px;
    overflow: hidden;
}

/* Track Background */
.track-background {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    pointer-events: none;
}

.track-lines {
    position: absolute;
    top: 2rem;
    left: 2rem;
    right: 2rem;
    bottom: 2rem;
    display: flex;
    flex-direction: column;
    justify-content: space-around;
}

.track-line {
    height: 1px;
    background: rgba(0,0,0,0.1);
    border-top: 1px dashed rgba(0,0,0,0.2);
}

.distance-markers {
    position: absolute;
    top: 2rem;
    left: 2rem;
    right: 2rem;
    bottom: 2rem;
    display: flex;
    justify-content: space-between;
}

.marker {
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.marker-line {
    width: 2px;
    height: 100%;
    background: rgba(0,0,0,0.2);
}

.marker.start .marker-line {
    background: var(--accent-success);
}

.marker.finish .marker-line {
    background: var(--accent-danger);
}

.marker-label {
    position: absolute;
    bottom: -1.5rem;
    font-size: 0.8rem;
    font-weight: 600;
    color: var(--text-secondary);
    white-space: nowrap;
}

.finish-flag {
    position: absolute;
    top: -1rem;
    right: -1rem;
    width: 32px;
    height: 40px;
}

/* Race Lanes */
.race-lanes {
    position: relative;
    z-index: 2;
    display: flex;
    flex-direction: column;
    gap: 1rem;
    height: 100%;
    justify-content: space-around;
}

.race-lane {
    display: grid;
    grid-template-columns: 200px 1fr 120px;
    align-items: center;
    gap: 1rem;
    padding: 0.75rem;
    background: rgba(255,255,255,0.8);
    border-radius: var(--border-radius);
    border: 1px solid rgba(0,0,0,0.1);
    position: relative;
    min-height: 60px;
}

.race-lane.current-user {
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.2), rgba(16, 185, 129, 0.2));
    border-color: var(--accent-primary);
    box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.3);
}

.lane-info {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.racer-position {
    background: var(--accent-primary);
    color: white;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 0.9rem;
}

.race-lane.current-user .racer-position {
    background: var(--champion-gradient);
}

.racer-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    overflow: hidden;
    position: relative;
    border: 2px solid var(--border-light);
}

.racer-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.avatar-placeholder {
    width: 100%;
    height: 100%;
    background: var(--accent-secondary);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 1rem;
}

.bot-indicator {
    position: absolute;
    bottom: -4px;
    right: -4px;
    background: var(--accent-warning);
    color: white;
    width: 18px;
    height: 18px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.7rem;
    border: 2px solid white;
}

.racer-name {
    font-weight: 600;
    color: var(--text-primary);
    font-size: 0.9rem;
}

.racer-league {
    display: flex;
    align-items: center;
    gap: 0.25rem;
    color: var(--text-secondary);
    font-size: 0.8rem;
}

.league-icon {
    width: 16px;
    height: 16px;
}

/* Racer Car */
.racer-car {
    position: relative;
    width: 100%;
    height: 40px;
    display: flex;
    align-items: center;
}

.car-container {
    position: absolute;
    left: 0;
    top: 50%;
    transform: translateY(-50%);
    transition: left 0.5s ease;
    z-index: 3;
}

.car-image {
    width: 32px;
    height: 32px;
    filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2));
}

.race-lane.current-user .car-image {
    filter: drop-shadow(0 2px 8px rgba(59, 130, 246, 0.4));
    transform: scale(1.1);
}

.car-effects {
    position: absolute;
    top: 50%;
    left: -20px;
    transform: translateY(-50%);
    pointer-events: none;
}

.speed-lines {
    width: 20px;
    height: 2px;
    background: repeating-linear-gradient(
        90deg,
        transparent,
        transparent 2px,
        rgba(59, 130, 246, 0.4) 2px,
        rgba(59, 130, 246, 0.4) 4px
    );
    opacity: 0;
    transition: opacity 0.3s ease;
}

.racer-car[data-moving="true"] .speed-lines {
    opacity: 1;
    animation: speed-lines 0.5s linear infinite;
}

@keyframes speed-lines {
    0% { transform: translateX(0); }
    100% { transform: translateX(-10px); }
}

.progress-bubble {
    position: absolute;
    top: -30px;
    left: 50%;
    transform: translateX(-50%);
    background: var(--accent-primary);
    color: white;
    padding: 0.25rem 0.5rem;
    border-radius: var(--border-radius);
    font-size: 0.8rem;
    font-weight: 600;
    white-space: nowrap;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.racer-car:hover .progress-bubble {
    opacity: 1;
}

/* Lane Stats */
.lane-stats {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    text-align: right;
}

.stat-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.stat-label {
    font-size: 0.8rem;
    color: var(--text-secondary);
}

.stat-value {
    font-weight: 600;
    font-size: 0.9rem;
}

.wpm-value {
    color: var(--accent-primary);
}

.accuracy-value {
    color: var(--accent-success);
}

/* Leaderboard */
.race-leaderboard {
    background: var(--bg-secondary);
    border: 1px solid var(--border-light);
    border-radius: var(--border-radius);
    padding: 1.5rem;
    min-width: 280px;
    max-height: 400px;
    overflow-y: auto;
}

.leaderboard-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid var(--border-light);
}

.leaderboard-header h4 {
    font-family: var(--font-display);
    color: var(--text-primary);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.leaderboard-header i {
    color: var(--accent-primary);
}

.auto-update {
    font-size: 0.8rem;
    color: var(--accent-success);
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.auto-update i {
    animation: spin 2s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.leaderboard-list {
    space-y: 0.5rem;
}

.leaderboard-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 0.75rem;
    background: var(--bg-card);
    border-radius: var(--border-radius);
    transition: all 0.3s ease;
    margin-bottom: 0.5rem;
}

.leaderboard-item:hover {
    background: var(--border-light);
}

.item-position {
    background: var(--accent-primary);
    color: white;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 0.8rem;
    flex-shrink: 0;
}

.item-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    overflow: hidden;
    flex-shrink: 0;
}

.item-info {
    flex: 1;
}

.item-name {
    font-weight: 600;
    color: var(--text-primary);
    font-size: 0.9rem;
    margin-bottom: 0.25rem;
}

.item-stats {
    display: flex;
    gap: 1rem;
    font-size: 0.8rem;
}

.item-wpm {
    color: var(--accent-primary);
    font-weight: 600;
}

.item-progress {
    color: var(--text-secondary);
}

.item-status {
    flex-shrink: 0;
}

.online-indicator {
    width: 12px;
    height: 12px;
    background: var(--accent-success);
    border-radius: 50%;
    border: 2px solid white;
}

/* Responsive Design */
@media (max-width: 1024px) {
    .race-track-area {
        grid-template-columns: 1fr;
    }
    
    .race-leaderboard {
        max-height: none;
        order: -1;
    }
    
    .race-lane {
        grid-template-columns: 150px 1fr 100px;
    }
}

@media (max-width: 768px) {
    .race-header {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
    
    .race-track-area {
        padding: 1rem;
    }
    
    .track-container {
        padding: 1rem;
        min-height: 300px;
    }
    
    .race-lane {
        grid-template-columns: 1fr;
        gap: 0.5rem;
        text-align: center;
    }
    
    .lane-info {
        justify-content: center;
    }
    
    .lane-stats {
        flex-direction: row;
        justify-content: center;
        gap: 1rem;
    }
    
    .leaderboard-item {
        gap: 0.5rem;
    }
}

/* Animation Styles */
.race-track-component[data-animated="true"] .racer-car {
    transition: left 1s cubic-bezier(0.25, 0.46, 0.45, 0.94);
}

.race-track-component[data-animated="false"] .racer-car {
    transition: left 0.1s linear;
}
</style>

<script>
// Race Track Component JavaScript
class RaceTrackComponent {
    constructor(element) {
        this.element = element;
        this.participants = new Map();
        this.isAnimated = element.dataset.animated === 'true';
        this.raceStartTime = null;
        this.timerInterval = null;
        
        this.initializeComponent();
    }
    
    initializeComponent() {
        // Initialize participant data
        this.element.querySelectorAll('.race-lane').forEach(lane => {
            const participantId = lane.dataset.participant;
            this.participants.set(participantId, {
                id: participantId,
                lane: lane,
                car: lane.querySelector('.racer-car'),
                progress: 0,
                wpm: 0,
                accuracy: 100,
                position: 0
            });
        });
        
        // Start race timer if in competition mode
        if (this.element.dataset.mode === 'competition') {
            this.startRaceTimer();
        }
        
        // Auto-update leaderboard
        this.startLeaderboardUpdate();
    }
    
    updateParticipantProgress(participantId, data) {
        const participant = this.participants.get(participantId);
        if (!participant) return;
        
        // Update progress
        participant.progress = Math.min(100, Math.max(0, data.progress || 0));
        participant.wpm = data.wpm || 0;
        participant.accuracy = data.accuracy || 100;
        
        // Update car position
        this.updateCarPosition(participant);
        
        // Update lane stats
        this.updateLaneStats(participant);
        
        // Update leaderboard
        this.updateLeaderboard();
    }
    
    updateCarPosition(participant) {
        const trackWidth = participant.lane.querySelector('.racer-car').parentElement.offsetWidth - 40; // Account for car width
        const newPosition = (participant.progress / 100) * trackWidth;
        
        participant.car.style.left = newPosition + 'px';
        participant.car.dataset.progress = participant.progress;
        
        // Update progress bubble
        const progressBubble = participant.car.querySelector('.progress-text');
        if (progressBubble) {
            progressBubble.textContent = Math.round(participant.progress) + '%';
        }
        
        // Add speed effects if moving
        const isMoving = participant.wpm > 0;
        participant.car.dataset.moving = isMoving;
    }
    
    updateLaneStats(participant) {
        const wpmElement = participant.lane.querySelector('.wpm-value');
        const accuracyElement = participant.lane.querySelector('.accuracy-value');
        
        if (wpmElement) {
            wpmElement.textContent = Math.round(participant.wpm);
        }
        
        if (accuracyElement) {
            accuracyElement.textContent = Math.round(participant.accuracy) + '%';
        }
    }
    
    updateLeaderboard() {
        // Sort participants by progress and WPM
        const sortedParticipants = Array.from(this.participants.values())
            .sort((a, b) => {
                if (b.progress !== a.progress) {
                    return b.progress - a.progress;
                }
                return b.wpm - a.wpm;
            });
        
        // Update positions
        sortedParticipants.forEach((participant, index) => {
            participant.position = index + 1;
            
            // Update position indicator in lane
            const positionElement = participant.lane.querySelector('.position-number');
            if (positionElement) {
                positionElement.textContent = '#' + participant.position;
            }
            
            // Update leaderboard item
            const leaderboardItem = this.element.querySelector(`[data-participant="${participant.id}"]`);
            if (leaderboardItem) {
                this.updateLeaderboardItem(leaderboardItem, participant, index);
            }
        });
        
        // Reorder leaderboard items
        this.reorderLeaderboard(sortedParticipants);
    }
    
    updateLeaderboardItem(item, participant, position) {
        const positionElement = item.querySelector('.item-position');
        const wpmElement = item.querySelector('.item-wpm');
        const progressElement = item.querySelector('.item-progress');
        
        if (positionElement) {
            positionElement.textContent = position + 1;
        }
        
        if (wpmElement) {
            wmpElement.textContent = Math.round(participant.wpm) + ' WPM';
        }
        
        if (progressElement) {
            progressElement.textContent = Math.round(participant.progress) + '%';
        }
        
        // Add winner highlight
        if (position === 0 && participant.progress >= 100) {
            item.classList.add('winner');
        }
    }
    
    reorderLeaderboard(sortedParticipants) {
        const leaderboardList = this.element.querySelector('.leaderboard-list');
        if (!leaderboardList) return;
        
        sortedParticipants.forEach(participant => {
            const item = leaderboardList.querySelector(`[data-participant="${participant.id}"]`);
            if (item) {
                leaderboardList.appendChild(item);
            }
        });
    }
    
    startRaceTimer() {
        this.raceStartTime = Date.now();
        const timerElement = this.element.querySelector('#race-timer');
        
        if (!timerElement) return;
        
        this.timerInterval = setInterval(() => {
            const elapsed = Date.now() - this.raceStartTime;
            const minutes = Math.floor(elapsed / 60000);
            const seconds = Math.floor((elapsed % 60000) / 1000);
            
            timerElement.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        }, 1000);
    }
    
    startLeaderboardUpdate() {
        // Auto-update leaderboard every 2 seconds
        setInterval(() => {
            this.updateLeaderboard();
        }, 2000);
    }
    
    simulateBotMovement(participantId, targetWpm = 50) {
        const participant = this.participants.get(participantId);
        if (!participant) return;
        
        const simulationInterval = setInterval(() => {
            // Simulate typing progress
            const currentProgress = participant.progress;
            const progressIncrement = (targetWpm / 60) * 2; // Approximate progress per 2 seconds
            const newProgress = Math.min(100, currentProgress + progressIncrement + (Math.random() - 0.5) * 5);
            
            // Simulate WPM variation
            const wpmVariation = (Math.random() - 0.5) * 10;
            const newWpm = Math.max(20, targetWpm + wmpVariation);
            
            // Simulate accuracy
            const accuracyVariation = (Math.random() - 0.5) * 5;
            const newAccuracy = Math.max(85, Math.min(100, 95 + accuracyVariation));
            
            this.updateParticipantProgress(participantId, {
                progress: newProgress,
                wpm: newWpm,
                accuracy: newAccuracy
            });
            
            // Stop simulation when finished
            if (newProgress >= 100) {
                clearInterval(simulationInterval);
            }
        }, 2000);
    }
    
    finishRace() {
        if (this.timerInterval) {
            clearInterval(this.timerInterval);
        }
        
        // Trigger race finish event
        this.element.dispatchEvent(new CustomEvent('race:finish', {
            detail: {
                results: Array.from(this.participants.values())
                    .sort((a, b) => a.position - b.position)
                    .map(p => ({
                        id: p.id,
                        position: p.position,
                        wpm: p.wpm,
                        accuracy: p.accuracy,
                        progress: p.progress
                    }))
            }
        }));
    }
    
    resetRace() {
        // Reset all participants
        this.participants.forEach(participant => {
            participant.progress = 0;
            participant.wpm = 0;
            participant.accuracy = 100;
            participant.position = 0;
            
            this.updateCarPosition(participant);
            this.updateLaneStats(participant);
        });
        
        // Reset timer
        if (this.timerInterval) {
            clearInterval(this.timerInterval);
        }
        
        const timerElement = this.element.querySelector('#race-timer');
        if (timerElement) {
            timerElement.textContent = '00:00';
        }
        
        this.updateLeaderboard();
    }
}

// Auto-initialize race tracks
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.race-track-component').forEach(element => {
        new RaceTrackComponent(element);
    });
});

// Global function to update race progress (can be called from outside)
window.updateRaceProgress = function(participantId, data) {
    document.querySelectorAll('.race-track-component').forEach(element => {
        if (element.raceTrack) {
            element.raceTrack.updateParticipantProgress(participantId, data);
        }
    });
};
</script>