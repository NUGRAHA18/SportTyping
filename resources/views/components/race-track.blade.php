{{-- resources/views/components/race-track.blade.php --}}
@props([
    'participants' => [],
    'userProgress' => 0,
    'showPositions' => true,
    'showAvatars' => true,
    'trackLength' => 100,
    'animationSpeed' => 'normal', // slow, normal, fast
    'competitionMode' => false
])

<div class="race-track-container">
    <!-- Race Header -->
    <div class="race-header">
        <div class="race-title">
            <i class="fas fa-racing-flag"></i>
            <h3>Live Race Track</h3>
        </div>
        @if($showPositions)
        <div class="race-stats">
            <div class="stat-item">
                <span class="stat-label">Racers</span>
                <span class="stat-value">{{ count($participants) }}</span>
            </div>
            <div class="stat-item">
                <span class="stat-label">Your Position</span>
                <span class="stat-value" id="userPosition">#1</span>
            </div>
        </div>
        @endif
    </div>

    <!-- Race Track -->
    <div class="race-track">
        <!-- Track Background -->
        <div class="track-background">
            <div class="track-lines">
                @for($i = 0; $i < 20; $i++)
                <div class="track-line" style="animation-delay: {{ $i * 0.1 }}s;"></div>
                @endfor
            </div>
        </div>

        <!-- User Lane -->
        <div class="race-lane user-lane" data-user-id="{{ Auth::id() ?? 'guest' }}">
            <div class="lane-info">
                @if($showAvatars)
                <div class="racer-avatar user-avatar">
                    @auth
                        @if(Auth::user()->profile && Auth::user()->profile->avatar)
                            <img src="{{ asset('storage/' . Auth::user()->profile->avatar) }}" alt="You">
                        @else
                            <div class="avatar-placeholder user">
                                {{ strtoupper(substr(Auth::user()->username ?? 'Y', 0, 1)) }}
                            </div>
                        @endif
                    @else
                        <div class="avatar-placeholder user">G</div>
                    @endauth
                </div>
                @endif
                <div class="racer-details">
                    <span class="racer-name">{{ Auth::user()->username ?? 'Guest' }} (You)</span>
                    <div class="racer-stats">
                        <span class="wpm-display" id="userWpm">0 WPM</span>
                        <span class="accuracy-display" id="userAccuracy">100%</span>
                    </div>
                </div>
            </div>
            <div class="track-lane">
                <div class="progress-track">
                    <div class="progress-bar user-progress" id="userProgressBar" style="width: {{ $userProgress }}%">
                        <div class="progress-glow"></div>
                        <div class="racer-car user-car">
                            <i class="fas fa-keyboard"></i>
                        </div>
                    </div>
                </div>
                <div class="finish-line">
                    <i class="fas fa-flag-checkered"></i>
                </div>
            </div>
        </div>

        <!-- Participant Lanes -->
        @foreach($participants as $index => $participant)
        <div class="race-lane participant-lane" data-participant-id="{{ $participant['id'] ?? $index }}">
            <div class="lane-info">
                @if($showAvatars)
                <div class="racer-avatar {{ $participant['is_bot'] ?? false ? 'bot-avatar' : 'user-avatar' }}">
                    @if($participant['is_bot'] ?? false)
                        <div class="avatar-placeholder bot bot-{{ ($index % 4) + 1 }}">
                            <i class="fas fa-robot"></i>
                        </div>
                    @else
                        @if(isset($participant['avatar']) && $participant['avatar'])
                            <img src="{{ asset('storage/' . $participant['avatar']) }}" alt="{{ $participant['name'] ?? 'Racer' }}">
                        @else
                            <div class="avatar-placeholder participant">
                                {{ strtoupper(substr($participant['name'] ?? 'R', 0, 1)) }}
                            </div>
                        @endif
                    @endif
                </div>
                @endif
                <div class="racer-details">
                    <span class="racer-name">
                        {{ $participant['name'] ?? 'Racer ' . ($index + 1) }}
                        @if($participant['is_bot'] ?? false)
                            <span class="bot-badge">BOT</span>
                        @endif
                    </span>
                    <div class="racer-stats">
                        <span class="wmp-display" id="participant{{ $index }}Wpm">
                            {{ $participant['wpm'] ?? 0 }} WPM
                        </span>
                        @if(isset($participant['accuracy']))
                        <span class="accuracy-display">{{ $participant['accuracy'] }}%</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="track-lane">
                <div class="progress-track">
                    <div class="progress-bar participant-progress progress-{{ ($index % 4) + 1 }}" 
                         id="participant{{ $index }}Progress" 
                         style="width: {{ $participant['progress'] ?? 0 }}%">
                        <div class="progress-glow"></div>
                        <div class="racer-car participant-car car-{{ ($index % 4) + 1 }}">
                            @if($participant['is_bot'] ?? false)
                                <i class="fas fa-robot"></i>
                            @else
                                <i class="fas fa-user"></i>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="finish-line">
                    <i class="fas fa-flag-checkered"></i>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Race Leaderboard (Optional) -->
    @if($showPositions && $competitionMode)
    <div class="race-leaderboard">
        <h4>Live Rankings</h4>
        <div class="leaderboard-list" id="liveLeaderboard">
            <!-- Rankings will be populated by JavaScript -->
        </div>
    </div>
    @endif
</div>

<style>
:root {
    /* SportTyping Color Palette */
    --primary-dark: #1a0d2e;
    --primary-purple: #2c1b47;
    --accent-pink: #ff6b9d;
    --accent-cyan: #00d4ff;
    --accent-purple: #8b5cf6;
    
    /* Gradients */
    --gradient-main: linear-gradient(135deg, #1a0d2e 0%, #2c1b47 100%);
    --gradient-card: linear-gradient(145deg, rgba(139, 92, 246, 0.1) 0%, rgba(255, 107, 157, 0.1) 100%);
    --gradient-accent: linear-gradient(90deg, #ff6b9d 0%, #00d4ff 100%);
    
    /* Text Colors */
    --text-primary: #ffffff;
    --text-secondary: #b4a7d1;
    --text-muted: #6b7280;
    
    /* Layout */
    --border-radius: 12px;
    --blur-amount: 20px;
    --font-primary: 'Poppins', 'Inter', sans-serif;
}

.race-track-container {
    background: var(--gradient-card);
    backdrop-filter: blur(var(--blur-amount));
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 16px;
    padding: 2rem;
    box-shadow: 0 8px 32px rgba(139, 92, 246, 0.1);
    font-family: var(--font-primary);
}

/* Race Header */
.race-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.race-title {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    color: var(--text-primary);
}

.race-title i {
    color: var(--accent-pink);
    font-size: 1.5rem;
}

.race-title h3 {
    font-size: 1.3rem;
    font-weight: 600;
    margin: 0;
}

.race-stats {
    display: flex;
    gap: 2rem;
}

.stat-item {
    text-align: center;
}

.stat-label {
    display: block;
    color: var(--text-secondary);
    font-size: 0.85rem;
    margin-bottom: 0.25rem;
}

.stat-value {
    display: block;
    color: var(--accent-pink);
    font-size: 1.2rem;
    font-weight: 600;
}

/* Race Track */
.race-track {
    position: relative;
    background: rgba(0, 0, 0, 0.2);
    border-radius: var(--border-radius);
    padding: 1.5rem;
    min-height: 400px;
    overflow: hidden;
}

.track-background {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    opacity: 0.1;
    z-index: 1;
}

.track-lines {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    display: flex;
    flex-direction: column;
    justify-content: space-around;
}

.track-line {
    height: 2px;
    background: repeating-linear-gradient(
        90deg,
        var(--accent-cyan) 0px,
        var(--accent-cyan) 20px,
        transparent 20px,
        transparent 40px
    );
    animation: moveTrackLines 2s linear infinite;
}

@keyframes moveTrackLines {
    0% { transform: translateX(-40px); }
    100% { transform: translateX(0); }
}

/* Race Lanes */
.race-lane {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1.5rem;
    position: relative;
    z-index: 2;
    padding: 1rem;
    background: rgba(255, 255, 255, 0.03);
    border-radius: var(--border-radius);
    border: 1px solid rgba(255, 255, 255, 0.05);
    transition: all 0.3s ease;
}

.race-lane.user-lane {
    background: rgba(255, 107, 157, 0.05);
    border-color: rgba(255, 107, 157, 0.2);
    box-shadow: 0 4px 20px rgba(255, 107, 157, 0.1);
}

.race-lane:hover {
    background: rgba(255, 255, 255, 0.05);
    border-color: rgba(255, 255, 255, 0.1);
}

.lane-info {
    display: flex;
    align-items: center;
    gap: 1rem;
    min-width: 200px;
    flex-shrink: 0;
}

.racer-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    overflow: hidden;
    flex-shrink: 0;
    position: relative;
}

.racer-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.avatar-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    font-size: 1.2rem;
}

.avatar-placeholder.user {
    background: var(--gradient-accent);
}

.avatar-placeholder.participant {
    background: linear-gradient(45deg, var(--accent-purple), var(--accent-cyan));
}

.avatar-placeholder.bot {
    font-size: 1rem;
}

.avatar-placeholder.bot.bot-1 { background: linear-gradient(45deg, #ff6b9d, #c084fc); }
.avatar-placeholder.bot.bot-2 { background: linear-gradient(45deg, #00d4ff, #0ea5e9); }
.avatar-placeholder.bot.bot-3 { background: linear-gradient(45deg, #f59e0b, #eab308); }
.avatar-placeholder.bot.bot-4 { background: linear-gradient(45deg, #10b981, #059669); }

.racer-details {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.racer-name {
    color: var(--text-primary);
    font-weight: 600;
    font-size: 0.95rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.bot-badge {
    background: rgba(139, 92, 246, 0.2);
    color: var(--accent-purple);
    padding: 0.1rem 0.4rem;
    border-radius: 4px;
    font-size: 0.7rem;
    font-weight: 500;
}

.racer-stats {
    display: flex;
    gap: 1rem;
    font-size: 0.85rem;
    color: var(--text-secondary);
}

.wmp-display {
    color: var(--accent-pink);
    font-weight: 500;
}

.accuracy-display {
    color: var(--accent-cyan);
    font-weight: 500;
}

/* Track Lane */
.track-lane {
    flex: 1;
    display: flex;
    align-items: center;
    gap: 1rem;
    position: relative;
}

.progress-track {
    flex: 1;
    height: 16px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 8px;
    overflow: hidden;
    position: relative;
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.progress-bar {
    height: 100%;
    border-radius: 8px;
    position: relative;
    transition: width 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    overflow: hidden;
}

.progress-bar.user-progress {
    background: var(--gradient-accent);
    box-shadow: 0 0 20px rgba(255, 107, 157, 0.3);
}

.progress-bar.progress-1 { 
    background: linear-gradient(45deg, #ff6b9d, #c084fc);
    box-shadow: 0 0 15px rgba(255, 107, 157, 0.2);
}
.progress-bar.progress-2 { 
    background: linear-gradient(45deg, #00d4ff, #0ea5e9);
    box-shadow: 0 0 15px rgba(0, 212, 255, 0.2);
}
.progress-bar.progress-3 { 
    background: linear-gradient(45deg, #f59e0b, #eab308);
    box-shadow: 0 0 15px rgba(245, 158, 11, 0.2);
}
.progress-bar.progress-4 { 
    background: linear-gradient(45deg, #10b981, #059669);
    box-shadow: 0 0 15px rgba(16, 185, 129, 0.2);
}

.progress-glow {
    position: absolute;
    top: 0;
    left: -50px;
    width: 50px;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
    animation: shimmer 2s infinite;
}

@keyframes shimmer {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(300px); }
}

.racer-car {
    position: absolute;
    right: -15px;
    top: 50%;
    transform: translateY(-50%);
    width: 30px;
    height: 30px;
    background: rgba(255, 255, 255, 0.9);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.9rem;
    color: var(--primary-dark);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
    animation: bounce 2s ease-in-out infinite;
}

.racer-car.user-car {
    background: var(--accent-pink);
    color: white;
}

.racer-car.car-1 { background: #ff6b9d; color: white; }
.racer-car.car-2 { background: #00d4ff; color: white; }
.racer-car.car-3 { background: #f59e0b; color: white; }
.racer-car.car-4 { background: #10b981; color: white; }

@keyframes bounce {
    0%, 100% { transform: translateY(-50%) translateX(0); }
    50% { transform: translateY(-50%) translateX(2px); }
}

.finish-line {
    color: var(--accent-cyan);
    font-size: 1.5rem;
    animation: wave 2s ease-in-out infinite;
    margin-left: 0.5rem;
}

@keyframes wave {
    0%, 100% { transform: translateY(0) rotate(0deg); }
    25% { transform: translateY(-3px) rotate(5deg); }
    75% { transform: translateY(3px) rotate(-5deg); }
}

/* Race Leaderboard */
.race-leaderboard {
    margin-top: 2rem;
    padding-top: 1.5rem;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
}

.race-leaderboard h4 {
    color: var(--text-primary);
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.leaderboard-list {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.leaderboard-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0.75rem 1rem;
    background: rgba(255, 255, 255, 0.03);
    border-radius: 8px;
    border: 1px solid rgba(255, 255, 255, 0.05);
    transition: all 0.3s ease;
}

.leaderboard-item:hover {
    background: rgba(255, 255, 255, 0.05);
}

.leaderboard-item.user-item {
    background: rgba(255, 107, 157, 0.05);
    border-color: rgba(255, 107, 157, 0.2);
}

.position-badge {
    background: var(--gradient-accent);
    color: white;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.8rem;
    font-weight: 600;
}

/* Responsive Design */
@media (max-width: 1024px) {
    .race-header {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
    
    .race-stats {
        justify-content: center;
    }
    
    .lane-info {
        min-width: 150px;
    }
    
    .racer-stats {
        flex-direction: column;
        gap: 0.25rem;
    }
}

@media (max-width: 768px) {
    .race-track-container {
        padding: 1rem;
    }
    
    .race-lane {
        flex-direction: column;
        text-align: center;
        gap: 1rem;
    }
    
    .track-lane {
        width: 100%;
        order: 2;
    }
    
    .lane-info {
        order: 1;
        min-width: unset;
        justify-content: center;
    }
    
    .racer-avatar {
        width: 40px;
        height: 40px;
    }
    
    .finish-line {
        font-size: 1.2rem;
    }
}

/* Animation Speed Modifiers */
.race-track.slow .progress-bar {
    transition-duration: 1s;
}

.race-track.fast .progress-bar {
    transition-duration: 0.2s;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Race Track Animation Controller
    class RaceTrack {
        constructor(containerId = 'raceTrack') {
            this.container = document.querySelector(#${containerId}) || document.querySelector('.race-track-container');
            this.participants = @json($participants);
            this.competitionMode = {{ $competitionMode ? 'true' : 'false' }};
            this.animationSpeed = '{{ $animationSpeed }}';
            
            this.init();
        }
        
        init() {
            this.updatePositions();
            
            // Set animation speed class
            if (this.container) {
                this.container.classList.add(this.animationSpeed);
            }
        }
        
        updateProgress(participantId, progress, wpm = null, accuracy = null) {
            const progressBar = document.getElementById(participant${participantId}Progress) || 
                              document.querySelector([data-participant-id="${participantId}"] .progress-bar);
            
            if (progressBar) {
                progressBar.style.width = Math.min(progress, 100) + '%';
                
                // Update WPM display
                if (wpm !== null) {
                    const wmpDisplay = document.getElementById(participant${participantId}Wpm);
                    if (wmpDisplay) {
                        wmpDisplay.textContent = wmp + ' WPM';
                    }
                }
                
                // Add completion animation
                if (progress >= 100) {
                    progressBar.classList.add('completed');
                    this.onRacerFinish(participantId);
                }
            }
            
            this.updatePositions();
        }
        
        updateUserProgress(progress, wpm = null, accuracy = null) {
            const userProgressBar = document.getElementById('userProgressBar');
            
            if (userProgressBar) {
                userProgressBar.style.width = Math.min(progress, 100) + '%';
            }
            
            // Update user stats
            if (wmp !== null) {
                const userWmpEl = document.getElementById('userWpm');
                if (userWmpEl) userWmpEl.textContent = wmp + ' WPM';
            }
            
            if (accuracy !== null) {
                const userAccuracyEl = document.getElementById('userAccuracy');
                if (userAccuracyEl) userAccuracyEl.textContent = accuracy + '%';
            }
            
            // Add completion animation
            if (progress >= 100) {
                userProgressBar.classList.add('completed');
                this.onRacerFinish('user');
            }
            
            this.updatePositions();
        }
        
        updatePositions() {
            const lanes = Array.from(document.querySelectorAll('.race-lane'));
            const positions = [];
            
            lanes.forEach((lane, index) => {
                const progressBar = lane.querySelector('.progress-bar');
                const racerName = lane.querySelector('.racer-name');
                const isUser = lane.classList.contains('user-lane');
                
                if (progressBar && racerName) {
                    const progress = parseFloat(progressBar.style.width) || 0;
                    positions.push({
                        element: lane,
                        progress: progress,
                        name: racerName.textContent.trim(),
                        isUser: isUser,
                        index: index
                    });
                }
            });
            
            // Sort by progress
            positions.sort((a, b) => b.progress - a.progress);
            
            // Update position displays and leaderboard
            positions.forEach((pos, rank) => {
                const position = rank + 1;
                
                if (pos.isUser) {
                    const userPosEl = document.getElementById('userPosition');
                    if (userPosEl) userPosEl.textContent = '#' + position;
                }
            });
            
            // Update live leaderboard if in competition mode
            if (this.competitionMode) {
                this.updateLeaderboard(positions);
            }
        }
        
        updateLeaderboard(positions) {
            const leaderboard = document.getElementById('liveLeaderboard');
            if (!leaderboard) return;
            
            const leaderboardHtml = positions.map((pos, rank) => `
                <div class="leaderboard-item ${pos.isUser ? 'user-item' : ''}">
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                        <div class="position-badge">${rank + 1}</div>
                        <span style="color: var(--text-primary); font-weight: 500;">${pos.name}</span>
                    </div>
                    <div style="color: var(--text-secondary); font-size: 0.9rem;">
                        ${pos.progress.toFixed(1)}%
                    </div>
                </div>
            `).join('');
            
            leaderboard.innerHTML = leaderboardHtml;
        }
        
        onRacerFinish(participantId) {
            // Add finish animation
            const lane = participantId === 'user' ? 
                document.querySelector('.user-lane') : 
                document.querySelector([data-participant-id="${participantId}"]);
                
            if (lane) {
                lane.classList.add('finished');
                
                // Create finish effect
                const finishEffect = document.createElement('div');
                finishEffect.style.cssText = `
                    position: absolute;
                    top: 50%;
                    right: 0;
                    transform: translateY(-50%);
                    font-size: 2rem;
                    animation: finishPulse 1s ease-out;
                    z-index: 10;
                `;
                finishEffect.innerHTML = '🏁';
                lane.style.position = 'relative';
                lane.appendChild(finishEffect);
                
                setTimeout(() => finishEffect.remove(), 1000);
            }
        }
        
        reset() {
            const progressBars = document.querySelectorAll('.progress-bar');
            progressBars.forEach(bar => {
                bar.style.width = '0%';
                bar.classList.remove('completed');
            });
            
            const lanes = document.querySelectorAll('.race-lane');
            lanes.forEach(lane => lane.classList.remove('finished'));
            
            // Reset stats
            const userPosEl = document.getElementById('userPosition');
            if (userPosEl) userPosEl.textContent = '#1';
            
            const leaderboard = document.getElementById('liveLeaderboard');
            if (leaderboard) leaderboard.innerHTML = '';
        }
    }
    
    // Initialize race track
    window.raceTrack = new RaceTrack();
    
    // Add finish animation keyframes
    const style = document.createElement('style');
    style.textContent = `
        @keyframes finishPulse {
            0% { opacity: 0; transform: translateY(-50%) scale(0.5); }
            50% { opacity: 1; transform: translateY(-50%) scale(1.2); }
            100% { opacity: 0; transform: translateY(-50%) scale(1); }
        }
        
        .race-lane.finished {
            animation: celebrate 0.5s ease-out;
        }
        
        @keyframes celebrate {
            0% { transform: scale(1); }
            50% { transform: scale(1.02); }
            100% { transform: scale(1); }
        }
        
        .progress-bar.completed .progress-glow {
            animation: completedShimmer 1s ease-out;
        }
        
        @keyframes completedShimmer {
            0% { opacity: 0; transform: translateX(-100%); }
            50% { opacity: 1; transform: translateX(0); }
            100% { opacity: 0; transform: translateX(100%); }
        }
    `;
    document.head.appendChild(style);
});

// Global functions for integration
window.updateRaceProgress = function(participantId, progress, wpm, accuracy) {
    if (window.raceTrack) {
        window.raceTrack.updateProgress(participantId, progress, wpm, accuracy);
    }
};

window.updateUserRaceProgress = function(progress, wpm, accuracy) {
    if (window.raceTrack) {
        window.raceTrack.updateUserProgress(progress, wpm, accuracy);
    }
};

window.resetRaceTrack = function() {
    if (window.raceTrack) {
        window.raceTrack.reset();
    }
};
</script>