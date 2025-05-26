@extends('layouts.app')

@section('content')
<div class="race-container">
    <div class="container-fluid">
        <!-- Race Header -->
        <div class="race-header">
            <div class="competition-info">
                <h1>{{ $competition->title }}</h1>
                <div class="race-status">
                    <span class="live-indicator">
                        <span class="live-dot"></span>
                        LIVE RACE
                    </span>
                </div>
            </div>
            <div class="race-timer">
                <div class="timer-display">
                    <span id="raceTimer">00:00</span>
                </div>
                <div class="participants-count">
                    {{ $competition->participants->count() }} racers
                </div>
            </div>
        </div>

        <!-- Race Track -->
        <div class="race-track-section">
            <div class="track-header">
                <h2><i class="fas fa-racing-flag"></i> Race Track</h2>
                <div class="track-info">
                    <span id="currentWPM">0 WPM</span>
                    <span id="currentAccuracy">0% ACC</span>
                </div>
            </div>
            
            <div class="race-track">
                <!-- User Track -->
                <div class="racer-lane user-lane">
                    <div class="racer-info">
                        <div class="racer-avatar">
                            @if(Auth::user()->profile->avatar)
                                <img src="{{ Storage::url(Auth::user()->profile->avatar) }}" alt="You">
                            @else
                                <div class="avatar-placeholder user">
                                    {{ substr(Auth::user()->username, 0, 1) }}
                                </div>
                            @endif
                        </div>
                        <div class="racer-details">
                            <span class="racer-name">{{ Auth::user()->username }} (You)</span>
                            <span class="racer-stats">
                                <span id="userWPM">0 WPM</span>
                                <span id="userPosition">#1</span>
                            </span>
                        </div>
                    </div>
                    <div class="track-progress">
                        <div class="progress-bar">
                            <div class="progress-fill user-progress" id="userProgress" style="width: 0%"></div>
                        </div>
                        <div class="finish-line">
                            <i class="fas fa-flag-checkered"></i>
                        </div>
                    </div>
                </div>

                <!-- Bot Tracks -->
                @foreach($bots as $index => $bot)
                <div class="racer-lane bot-lane">
                    <div class="racer-info">
                        <div class="racer-avatar">
                            <div class="avatar-placeholder bot bot-{{ ($index % 4) + 1 }}">
                                <i class="fas fa-robot"></i>
                            </div>
                        </div>
                        <div class="racer-details">
                            <span class="racer-name">{{ $bot['name'] }}</span>
                            <span class="racer-stats">
                                <span class="bot-wpm" id="bot{{ $index }}WPM">0 WPM</span>
                                <span class="bot-position" id="bot{{ $index }}Position">#{{ $index + 2 }}</span>
                            </span>
                        </div>
                    </div>
                    <div class="track-progress">
                        <div class="progress-bar">
                            <div class="progress-fill bot-progress bot-{{ ($index % 4) + 1 }}" id="bot{{ $index }}Progress" style="width: 0%"></div>
                        </div>
                        <div class="finish-line">
                            <i class="fas fa-flag-checkered"></i>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Typing Interface -->
        <div class="typing-interface">
            <div class="text-display">
                <div class="text-content" id="textContent">
                    {{ $competition->text->content }}
                </div>
            </div>
            
            <div class="typing-area">
                <div class="typing-controls">
                    <button id="startButton" class="btn btn-primary btn-large">
                        <i class="fas fa-play"></i>
                        Start Racing
                    </button>
                    <button id="resetButton" class="btn btn-secondary" style="display: none;">
                        <i class="fas fa-redo"></i>
                        Reset
                    </button>
                </div>
                
                <div class="typing-input-container" style="display: none;" id="typingContainer">
                    <textarea id="typingInput" 
                              placeholder="Start typing when ready..." 
                              rows="6"
                              disabled></textarea>
                    <div class="typing-stats">
                        <div class="stat-item">
                            <span class="stat-label">Speed</span>
                            <span class="stat-value" id="liveWPM">0 WPM</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Accuracy</span>
                            <span class="stat-value" id="liveAccuracy">0%</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Progress</span>
                            <span class="stat-value" id="progressPercent">0%</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Errors</span>
                            <span class="stat-value error" id="errorCount">0</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Results Modal -->
        <div class="race-results-modal" id="resultsModal" style="display: none;">
            <div class="modal-content">
                <div class="results-header">
                    <i class="fas fa-trophy"></i>
                    <h2>Race Complete!</h2>
                </div>
                
                <div class="final-stats">
                    <div class="stat-card">
                        <div class="stat-icon speed">
                            <i class="fas fa-tachometer-alt"></i>
                        </div>
                        <div class="stat-info">
                            <span class="stat-number" id="finalWPM">0</span>
                            <span class="stat-label">WPM</span>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon accuracy">
                            <i class="fas fa-bullseye"></i>
                        </div>
                        <div class="stat-info">
                            <span class="stat-number" id="finalAccuracy">0%</span>
                            <span class="stat-label">Accuracy</span>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon time">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="stat-info">
                            <span class="stat-number" id="finalTime">00:00</span>
                            <span class="stat-label">Time</span>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon position">
                            <i class="fas fa-medal"></i>
                        </div>
                        <div class="stat-info">
                            <span class="stat-number" id="finalPosition">#1</span>
                            <span class="stat-label">Position</span>
                        </div>
                    </div>
                </div>

                <div class="leaderboard">
                    <h3>Final Rankings</h3>
                    <div class="ranking-list" id="finalRankings">
                        <!-- Rankings will be populated by JavaScript -->
                    </div>
                </div>

                <div class="results-actions">
                    <form action="{{ route('competitions.submit-result', $competition) }}" method="POST" id="submitResultForm">
                        @csrf
                        <input type="hidden" name="typed_text" id="typedTextInput">
                        <input type="hidden" name="completion_time" id="completionTimeInput">
                        <button type="submit" class="btn btn-primary btn-large">
                            <i class="fas fa-save"></i>
                            Save Results
                        </button>
                    </form>
                    <button class="btn btn-secondary" onclick="resetRace()">
                        <i class="fas fa-redo"></i>
                        Race Again
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.race-container {
    padding: 1rem 0;
    min-height: calc(100vh - 80px);
    background: var(--bg-primary);
}

/* Race Header */
.race-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    padding: 2rem;
    background: var(--gradient-card);
    backdrop-filter: blur(var(--blur-amount));
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius-lg);
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
    background: var(--gradient-accent);
}

.competition-info h1 {
    font-size: 1.8rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 0.5rem;
}

.live-indicator {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    background: rgba(239, 68, 68, 0.1);
    color: var(--error);
    padding: 0.5rem 1rem;
    border-radius: var(--border-radius);
    font-size: 0.85rem;
    font-weight: 600;
    border: 1px solid rgba(239, 68, 68, 0.2);
}

.live-dot {
    width: 8px;
    height: 8px;
    background: var(--error);
    border-radius: 50%;
    animation: pulse 2s infinite;
}

.timer-display {
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--text-primary);
    font-family: 'Courier New', monospace;
}

.participants-count {
    color: var(--text-secondary);
    font-size: 0.9rem;
    text-align: center;
}

/* Race Track */
.race-track-section {
    margin-bottom: 2rem;
}

.track-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
}

.track-header h2 {
    font-size: 1.3rem;
    font-weight: 600;
    color: var(--text-primary);
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.track-info {
    display: flex;
    gap: 1.5rem;
    font-weight: 600;
}

.track-info span {
    color: var(--accent-pink);
}

.race-track {
    background: var(--gradient-card);
    backdrop-filter: blur(var(--blur-amount));
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius-lg);
    padding: 2rem;
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.racer-lane {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: rgba(255, 255, 255, 0.03);
    border-radius: var(--border-radius);
    border: 1px solid rgba(255, 255, 255, 0.05);
    transition: all 0.3s ease;
}

.racer-lane.user-lane {
    border-color: var(--accent-pink);
    background: rgba(255, 107, 157, 0.05);
}

.racer-info {
    display: flex;
    align-items: center;
    gap: 1rem;
    min-width: 200px;
}

.racer-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    overflow: hidden;
    flex-shrink: 0;
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
    background: var(--gradient-button);
}

.avatar-placeholder.bot {
    font-size: 1rem;
}

.avatar-placeholder.bot-1 { background: linear-gradient(45deg, #ff6b9d, #c084fc); }
.avatar-placeholder.bot-2 { background: linear-gradient(45deg, #00d4ff, #0ea5e9); }
.avatar-placeholder.bot-3 { background: linear-gradient(45deg, #f59e0b, #eab308); }
.avatar-placeholder.bot-4 { background: linear-gradient(45deg, #10b981, #059669); }

.racer-details {
    display: flex;
    flex-direction: column;
}

.racer-name {
    color: var(--text-primary);
    font-weight: 600;
    font-size: 0.95rem;
}

.racer-stats {
    color: var(--text-secondary);
    font-size: 0.85rem;
    display: flex;
    gap: 1rem;
}

.track-progress {
    flex: 1;
    display: flex;
    align-items: center;
    gap: 1rem;
    position: relative;
}

.progress-bar {
    flex: 1;
    height: 12px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 6px;
    overflow: hidden;
    position: relative;
}

.progress-fill {
    height: 100%;
    border-radius: 6px;
    transition: width 0.3s ease;
    position: relative;
}

.progress-fill::after {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 20px;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3));
    animation: shimmer 2s infinite;
}

@keyframes shimmer {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(100%); }
}

.user-progress {
    background: var(--gradient-button);
}

.bot-progress.bot-1 { background: linear-gradient(45deg, #ff6b9d, #c084fc); }
.bot-progress.bot-2 { background: linear-gradient(45deg, #00d4ff, #0ea5e9); }
.bot-progress.bot-3 { background: linear-gradient(45deg, #f59e0b, #eab308); }
.bot-progress.bot-4 { background: linear-gradient(45deg, #10b981, #059669); }

.finish-line {
    color: var(--accent-cyan);
    font-size: 1.5rem;
    animation: wave 2s ease-in-out infinite;
}

@keyframes wave {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-3px); }
}

/* Typing Interface */
.typing-interface {
    background: var(--gradient-card);
    backdrop-filter: blur(var(--blur-amount));
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius-lg);
    padding: 2rem;
    margin-bottom: 2rem;
}

.text-display {
    margin-bottom: 2rem;
    padding: 2rem;
    background: rgba(255, 255, 255, 0.03);
    border-radius: var(--border-radius);
    border: 1px solid rgba(255, 255, 255, 0.05);
}

.text-content {
    font-family: 'Courier New', monospace;
    font-size: 1.1rem;
    line-height: 1.8;
    color: var(--text-primary);
    word-spacing: 0.2em;
}

.char-correct { background: rgba(16, 185, 129, 0.2); color: var(--success); }
.char-incorrect { background: rgba(239, 68, 68, 0.2); color: var(--error); }
.char-current { background: var(--accent-pink); color: white; }

.typing-controls {
    text-align: center;
    margin-bottom: 2rem;
}

.btn-large {
    padding: 1.25rem 3rem;
    font-size: 1.2rem;
    font-weight: 700;
}

.typing-input-container {
    position: relative;
}

#typingInput {
    width: 100%;
    background: rgba(255, 255, 255, 0.05);
    border: 2px solid rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius);
    color: var(--text-primary);
    padding: 1.5rem;
    font-family: 'Courier New', monospace;
    font-size: 1.1rem;
    line-height: 1.6;
    resize: none;
    transition: all 0.3s ease;
}

#typingInput:focus {
    outline: none;
    border-color: var(--accent-pink);
    background: rgba(255, 255, 255, 0.08);
    box-shadow: 0 0 0 3px rgba(255, 107, 157, 0.1);
}

#typingInput::placeholder {
    color: var(--text-muted);
}

.typing-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    gap: 1rem;
    margin-top: 1.5rem;
}

.stat-item {
    text-align: center;
    padding: 1rem;
    background: rgba(255, 255, 255, 0.03);
    border-radius: var(--border-radius);
    border: 1px solid rgba(255, 255, 255, 0.05);
}

.stat-label {
    display: block;
    color: var(--text-secondary);
    font-size: 0.85rem;
    margin-bottom: 0.5rem;
}

.stat-value {
    display: block;
    color: var(--text-primary);
    font-size: 1.2rem;
    font-weight: 700;
}

.stat-value.error {
    color: var(--error);
}

/* Results Modal */
.race-results-modal {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.8);
    backdrop-filter: blur(10px);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
    padding: 2rem;
}

.modal-content {
    background: var(--gradient-card);
    backdrop-filter: blur(var(--blur-amount));
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius-lg);
    padding: 3rem;
    max-width: 600px;
    width: 100%;
    max-height: 80vh;
    overflow-y: auto;
    position: relative;
}

.modal-content::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: var(--gradient-accent);
}

.results-header {
    text-align: center;
    margin-bottom: 2rem;
}

.results-header i {
    font-size: 3rem;
    color: var(--accent-pink);
    margin-bottom: 1rem;
}

.results-header h2 {
    color: var(--text-primary);
    font-size: 2rem;
    font-weight: 700;
}

.final-stats {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.5rem;
    background: rgba(255, 255, 255, 0.03);
    border-radius: var(--border-radius);
    border: 1px solid rgba(255, 255, 255, 0.05);
}

.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2rem;
}

.stat-icon.speed { background: var(--gradient-button); }
.stat-icon.accuracy { background: linear-gradient(45deg, #00d4ff, #0ea5e9); }
.stat-icon.time { background: linear-gradient(45deg, #f59e0b, #eab308); }
.stat-icon.position { background: linear-gradient(45deg, #10b981, #059669); }

.stat-info {
    display: flex;
    flex-direction: column;
}

.stat-number {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text-primary);
}

.leaderboard {
    margin-bottom: 2rem;
}

.leaderboard h3 {
    color: var(--text-primary);
    margin-bottom: 1rem;
    text-align: center;
}

.ranking-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: rgba(255, 255, 255, 0.03);
    border-radius: var(--border-radius);
    margin-bottom: 0.5rem;
    border: 1px solid rgba(255, 255, 255, 0.05);
}

.ranking-item.winner {
    border-color: var(--accent-pink);
    background: rgba(255, 107, 157, 0.05);
}

.rank-position {
    font-size: 1.2rem;
    font-weight: 700;
    color: var(--accent-pink);
    min-width: 30px;
}

.rank-name {
    flex: 1;
    color: var(--text-primary);
    font-weight: 600;
}

.rank-stats {
    color: var(--text-secondary);
    font-size: 0.9rem;
}

.results-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
}

/* Responsive */
@media (max-width: 1024px) {
    .race-header {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
    
    .track-header {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
    
    .racer-info {
        min-width: 150px;
    }
}

@media (max-width: 768px) {
    .final-stats {
        grid-template-columns: 1fr;
    }
    
    .results-actions {
        flex-direction: column;
    }
    
    .racer-lane {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
    
    .track-progress {
        width: 100%;
    }
    
    .typing-stats {
        grid-template-columns: repeat(2, 1fr);
    }
}
</style>

<script>
// Competition data
const competition = @json($competition);
const bots = @json($bots);
const originalText = competition.text.content;
const totalWords = originalText.split(/\s+/).length;

// Race state
let raceStarted = false;
let raceFinished = false;
let startTime, endTime;
let raceTimer;
let botIntervals = [];
let userTypedText = '';

// DOM elements
const startButton = document.getElementById('startButton');
const resetButton = document.getElementById('resetButton');
const typingContainer = document.getElementById('typingContainer');
const typingInput = document.getElementById('typingInput');
const textContent = document.getElementById('textContent');
const resultsModal = document.getElementById('resultsModal');

// Statistics elements
const raceTimerEl = document.getElementById('raceTimer');
const currentWPM = document.getElementById('currentWPM');
const currentAccuracy = document.getElementById('currentAccuracy');
const liveWPM = document.getElementById('liveWPM');
const liveAccuracy = document.getElementById('liveAccuracy');
const progressPercent = document.getElementById('progressPercent');
const errorCount = document.getElementById('errorCount');

// Event listeners
startButton.addEventListener('click', startRace);
resetButton.addEventListener('click', resetRace);
typingInput.addEventListener('input', handleTyping);
typingInput.addEventListener('paste', e => e.preventDefault());

function startRace() {
    raceStarted = true;
    startTime = new Date();
    
    // Update UI
    startButton.style.display = 'none';
    resetButton.style.display = 'inline-block';
    typingContainer.style.display = 'block';
    typingInput.disabled = false;
    typingInput.focus();
    
    // Start race timer
    raceTimer = setInterval(updateRaceTimer, 100);
    
    // Start bots
    startBots();
    
    // Highlight first character
    highlightCurrentChar(0);
}

function resetRace() {
    raceStarted = false;
    raceFinished = false;
    userTypedText = '';
    
    // Clear timers
    clearInterval(raceTimer);
    botIntervals.forEach(interval => clearInterval(interval));
    botIntervals = [];
    
    // Reset UI
    startButton.style.display = 'inline-block';
    resetButton.style.display = 'none';
    typingContainer.style.display = 'none';
    typingInput.disabled = true;
    typingInput.value = '';
    resultsModal.style.display = 'none';
    
    // Reset progress bars
    document.getElementById('userProgress').style.width = '0%';
    bots.forEach((bot, index) => {
        document.getElementById(bot${index}Progress).style.width = '0%';
    });
    
    // Reset text highlighting
    textContent.innerHTML = originalText;
    
    // Reset stats
    resetStats();
}

function handleTyping(e) {
    if (!raceStarted || raceFinished) return;
    
    userTypedText = e.target.value;
    const progress = Math.min(100, (userTypedText.length / originalText.length) * 100);
    
    // Update progress bar
    document.getElementById('userProgress').style.width = progress + '%';
    
    // Update text highlighting
    highlightText(userTypedText);
    
    // Calculate and update stats
    updateStats();
    
    // Check if race finished
    if (userTypedText.length >= originalText.length) {
        finishRace();
    }
    
    // Update user position
    updatePositions();
}

function highlightText(typedText) {
    let highlighted = '';
    let errors = 0;
    
    for (let i = 0; i < originalText.length; i++) {
        const originalChar = originalText[i];
        const typedChar = typedText[i];
        
        if (i < typedText.length) {
            if (typedChar === originalChar) {
                highlighted += <span class="char-correct">${originalChar}</span>;
            } else {
                highlighted += <span class="char-incorrect">${originalChar}</span>;
                errors++;
            }
        } else if (i === typedText.length) {
            highlighted += <span class="char-current">${originalChar}</span>;
        } else {
            highlighted += originalChar;
        }
    }
    
    textContent.innerHTML = highlighted;
    errorCount.textContent = errors;
}

function highlightCurrentChar(position) {
    let highlighted = '';
    for (let i = 0; i < originalText.length; i++) {
        if (i === position) {
            highlighted += <span class="char-current">${originalText[i]}</span>;
        } else {
            highlighted += originalText[i];
        }
    }
    textContent.innerHTML = highlighted;
}

function updateStats() {
    const elapsedTime = (new Date() - startTime) / 1000; // seconds
    const typedWords = userTypedText.split(/\s+/).length;
    const wpm = Math.round((typedWords / elapsedTime) * 60);
    
    // Calculate accuracy
    let correctChars = 0;
    const minLength = Math.min(originalText.length, userTypedText.length);
    
    for (let i = 0; i < minLength; i++) {
        if (originalText[i] === userTypedText[i]) {
            correctChars++;
        }
    }
    
    const accuracy = userTypedText.length > 0 ? Math.round((correctChars / userTypedText.length) * 100) : 0;
    const progress = Math.round((userTypedText.length / originalText.length) * 100);
    
    // Update displays
    liveWPM.textContent = wpm + ' WPM';
    liveAccuracy.textContent = accuracy + '%';
    progressPercent.textContent = progress + '%';
    currentWPM.textContent = wmp + ' WPM';
    currentAccuracy.textContent = accuracy + '% ACC';
    
    // Update user stats in race track
    document.getElementById('userWPM').textContent = wmp + ' WPM';
}

function updateRaceTimer() {
    if (!raceStarted) return;
    
    const elapsed = (new Date() - startTime) / 1000;
    const minutes = Math.floor(elapsed / 60);
    const seconds = Math.floor(elapsed % 60);
    
    raceTimerEl.textContent = ${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')};
}

function startBots() {
    bots.forEach((bot, index) => {
        const totalTime = (totalWords / bot.typing_speed) * 60 * 1000; // milliseconds
        const progressInterval = 200; // update every 200ms
        const progressStep = 100 / (totalTime / progressInterval);
        
        let progress = 0;
        
        botIntervals[index] = setInterval(() => {
            progress += progressStep * (0.8 + Math.random() * 0.4); // Add some randomness
            progress = Math.min(100, progress);
            
            document.getElementById(bot${index}Progress).style.width = progress + '%';
            
            // Update bot WPM display
            const currentWPM = Math.round(bot.typing_speed * (progress / 100));
            document.getElementById(bot${index}WPM).textContent = currentWPM + ' WPM';
            
            if (progress >= 100) {
                clearInterval(botIntervals[index]);
                // Check if all bots finished and user hasn't
                checkRaceEnd();
            }
        }, progressInterval);
    });
}

function updatePositions() {
    // Get all racer progress
    const racers = [
        { name: '{{ Auth::user()->username }} (You)', progress: parseFloat(document.getElementById('userProgress').style.width) || 0, isUser: true }
    ];
    
    bots.forEach((bot, index) => {
        racers.push({
            name: bot.name,
            progress: parseFloat(document.getElementById(bot${index}Progress).style.width) || 0,
            isUser: false,
            index: index
        });
    });
    
    // Sort by progress
    racers.sort((a, b) => b.progress - a.progress);
    
    // Update positions
    racers.forEach((racer, position) => {
        const rank = position + 1;
        if (racer.isUser) {
            document.getElementById('userPosition').textContent = #${rank};
        } else {
            document.getElementById(bot${racer.index}Position).textContent = #${rank};
        }
    });
}

function checkRaceEnd() {
    const userProgress = parseFloat(document.getElementById('userProgress').style.width) || 0;
    const allBotsFinished = bots.every((bot, index) => {
        const botProgress = parseFloat(document.getElementById(bot${index}Progress).style.width) || 0;
        return botProgress >= 100;
    });
    
    if (allBotsFinished && userProgress < 100 && raceStarted && !raceFinished) {
        // User lost, but let them finish
        setTimeout(() => {
            if (!raceFinished) {
                finishRace();
            }
        }, 5000); // Give user 5 more seconds
    }
}

function finishRace() {
    if (raceFinished) return;
    
    raceFinished = true;
    endTime = new Date();
    const totalTime = (endTime - startTime) / 1000;
    
    // Stop timers
    clearInterval(raceTimer);
    botIntervals.forEach(interval => clearInterval(interval));
    
    // Disable input
    typingInput.disabled = true;
    
    // Show results modal
    showResults(totalTime);
}

function showResults(totalTime) {
    // Calculate final stats
    const typedWords = userTypedText.split(/\s+/).length;
    const finalWPM = Math.round((typedWords / totalTime) * 60);
    
    let correctChars = 0;
    const minLength = Math.min(originalText.length, userTypedText.length);
    
    for (let i = 0; i < minLength; i++) {
        if (originalText[i] === userTypedText[i]) {
            correctChars++;
        }
    }
    
    const finalAccuracy = userTypedText.length > 0 ? Math.round((correctChars / userTypedText.length) * 100) : 0;
    
    // Update final stats display
    document.getElementById('finalWPM').textContent = finalWPM;
    document.getElementById('finalAccuracy').textContent = finalAccuracy + '%';
    document.getElementById('finalTime').textContent = ${Math.floor(totalTime / 60)}:${Math.floor(totalTime % 60).toString().padStart(2, '0')};
    
    // Generate final rankings
    const racers = [
        { name: '{{ Auth::user()->username }} (You)', wpm: finalWPM, accuracy: finalAccuracy, isUser: true }
    ];
    
    bots.forEach(bot => {
        racers.push({
            name: bot.name,
            wmp: bot.typing_speed,
            accuracy: bot.accuracy,
            isUser: false
        });
    });
    
    // Sort by WPM
    racers.sort((a, b) => b.wmp - a.wmp);
    
    // Find user position
    const userPosition = racers.findIndex(r => r.isUser) + 1;
    document.getElementById('finalPosition').textContent = #${userPosition};
    
    // Generate rankings HTML
    const rankingsHTML = racers.map((racer, index) => `
        <div class="ranking-item ${racer.isUser ? 'winner' : ''}">
            <span class="rank-position">#${index + 1}</span>
            <span class="rank-name">${racer.name}</span>
            <span class="rank-stats">${racer.wpm} WPM • ${racer.accuracy}%</span>
        </div>
    `).join('');
    
    document.getElementById('finalRankings').innerHTML = rankingsHTML;
    
    // Set form data
    document.getElementById('typedTextInput').value = userTypedText;
    document.getElementById('completionTimeInput').value = Math.round(totalTime);
    
    // Show modal
    resultsModal.style.display = 'flex';
}

function resetStats() {
    raceTimerEl.textContent = '00:00';
    currentWPM.textContent = '0 WPM';
    currentAccuracy.textContent = '0% ACC';
    liveWPM.textContent = '0 WPM';
    liveAccuracy.textContent = '0%';
    progressPercent.textContent = '0%';
    errorCount.textContent = '0';
    document.getElementById('userWPM').textContent = '0 WPM';
    document.getElementById('userPosition').textContent = '#1';
    
    bots.forEach((bot, index) => {
        document.getElementById(bot${index}WPM).textContent = '0 WPM';
        document.getElementById(bot${index}Position).textContent = #${index + 2};
    });
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    resetStats();
});
</script>
@endsection