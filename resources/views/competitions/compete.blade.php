@extends('layouts.app')

@section('content')
<div class="compete-container">
    <div class="container-fluid">
        <!-- Competition Header -->
        <div class="compete-header">
            <div class="header-left">
                <h1 class="competition-title">{{ $competition->title }}</h1>
                <div class="competition-meta">
                    <span class="meta-item">
                        <i class="fas fa-users"></i>
                        {{ $competition->participants_count }} racers
                    </span>
                    <span class="meta-item">
                        <i class="fas fa-clock"></i>
                        {{ $competition->duration }} minutes
                    </span>
                </div>
            </div>
            <div class="header-right">
                <div class="timer-display">
                    <div class="timer-label">Time Remaining</div>
                    <div class="timer-value" id="competition-timer">{{ $competition->duration }}:00</div>
                </div>
            </div>
        </div>

        <!-- Race Track Section -->
        <div class="race-section">
            <div class="race-track-wrapper">
                <div class="race-track">
                    <div class="track-lanes">
                        <div class="finish-line">
                            <img src="/image/ui/race_finish.svg" alt="Finish Line">
                            <span class="finish-text">FINISH</span>
                        </div>
                        
                        <!-- Current User Lane -->
                        <div class="race-lane user-lane">
                            <div class="lane-info">
                                <div class="racer-avatar">
                                    @if(Auth::user()->profile && Auth::user()->profile->avatar)
                                        <img src="{{ asset('storage/' . Auth::user()->profile->avatar) }}" alt="{{ Auth::user()->username }}">
                                    @else
                                        <div class="avatar-placeholder">{{ strtoupper(substr(Auth::user()->username, 0, 1)) }}</div>
                                    @endif
                                </div>
                                <div class="racer-name">{{ Auth::user()->username }} (You)</div>
                            </div>
                            <div class="racer-car user-car" style="--progress: 0%">
                                <img src="/image/ui/mobil1.svg" alt="Your car">
                            </div>
                            <div class="lane-stats">
                                <span class="wpm-display">0 WPM</span>
                                <span class="accuracy-display">100%</span>
                            </div>
                        </div>

                        <!-- Bot/Other Participants Lanes -->
                        @foreach($bots as $index => $bot)
                        <div class="race-lane bot-lane" data-bot-id="{{ $bot['id'] }}">
                            <div class="lane-info">
                                <div class="racer-avatar bot-avatar">
                                    <i class="fas fa-robot"></i>
                                </div>
                                <div class="racer-name">{{ $bot['name'] }}</div>
                            </div>
                            <div class="racer-car bot-car" style="--progress: 0%">
                                <img src="/image/ui/mobil{{ ($index % 4) + 2 }}.svg" alt="{{ $bot['name'] }} car">
                            </div>
                            <div class="lane-stats">
                                <span class="wpm-display">0 WPM</span>
                                <span class="accuracy-display">100%</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Typing Area Section -->
        <div class="typing-section">
            <div class="typing-container">
                <!-- Competition Status -->
                <div class="competition-status" id="competition-status">
                    <div class="status-waiting">
                        <div class="status-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <h3>Get Ready!</h3>
                        <p>Competition starts in <span id="start-countdown">3</span> seconds</p>
                    </div>
                </div>

                <!-- Text Display -->
                <div class="text-display" id="text-display" style="display: none;">
                    <div class="text-content">
                        <div class="text-wrapper">
                            @foreach(str_split($competition->text->content) as $index => $char)
                                <span class="char" data-index="{{ $index }}">{{ $char === ' ' ? '·' : $char }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Typing Input -->
                <div class="typing-input-wrapper" style="display: none;" id="typing-wrapper">
                    <textarea 
                        id="typing-input" 
                        class="typing-input" 
                        placeholder="Start typing when the competition begins..."
                        rows="4"
                        disabled
                    ></textarea>
                    <div class="input-stats">
                        <div class="stat-item">
                            <span class="stat-label">WPM</span>
                            <span class="stat-value" id="current-wpm">0</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Accuracy</span>
                            <span class="stat-value" id="current-accuracy">100%</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Progress</span>
                            <span class="stat-value" id="current-progress">0%</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Errors</span>
                            <span class="stat-value" id="current-errors">0</span>
                        </div>
                    </div>
                </div>

                <!-- Virtual Keyboard -->
                <div class="virtual-keyboard" id="virtual-keyboard" style="display: none;">
                    <div class="keyboard-row">
                        <div class="key" data-key="`">~<br>`</div>
                        <div class="key" data-key="1">!<br>1</div>
                        <div class="key" data-key="2">@<br>2</div>
                        <div class="key" data-key="3">#<br>3</div>
                        <div class="key" data-key="4">$<br>4</div>
                        <div class="key" data-key="5">%<br>5</div>
                        <div class="key" data-key="6">^<br>6</div>
                        <div class="key" data-key="7">&<br>7</div>
                        <div class="key" data-key="8">*<br>8</div>
                        <div class="key" data-key="9">(<br>9</div>
                        <div class="key" data-key="0">)<br>0</div>
                        <div class="key" data-key="-">_<br>-</div>
                        <div class="key" data-key="=">=<br>=</div>
                        <div class="key wide" data-key="Backspace">Backspace</div>
                    </div>
                    <div class="keyboard-row">
                        <div class="key wide" data-key="Tab">Tab</div>
                        <div class="key" data-key="q">Q</div>
                        <div class="key" data-key="w">W</div>
                        <div class="key" data-key="e">E</div>
                        <div class="key" data-key="r">R</div>
                        <div class="key" data-key="t">T</div>
                        <div class="key" data-key="y">Y</div>
                        <div class="key" data-key="u">U</div>
                        <div class="key" data-key="i">I</div>
                        <div class="key" data-key="o">O</div>
                        <div class="key" data-key="p">P</div>
                        <div class="key" data-key="[">{<br>[</div>
                        <div class="key" data-key="]">}<br>]</div>
                        <div class="key wide" data-key="\\">|<br>\</div>
                    </div>
                    <div class="keyboard-row">
                        <div class="key extra-wide" data-key="CapsLock">Caps Lock</div>
                        <div class="key" data-key="a">A</div>
                        <div class="key" data-key="s">S</div>
                        <div class="key" data-key="d">D</div>
                        <div class="key" data-key="f">F</div>
                        <div class="key" data-key="g">G</div>
                        <div class="key" data-key="h">H</div>
                        <div class="key" data-key="j">J</div>
                        <div class="key" data-key="k">K</div>
                        <div class="key" data-key="l">L</div>
                        <div class="key" data-key=";">:<br>;</div>
                        <div class="key" data-key="'">"<br>'</div>
                        <div class="key extra-wide" data-key="Enter">Enter</div>
                    </div>
                    <div class="keyboard-row">
                        <div class="key extra-wide" data-key="Shift">Shift</div>
                        <div class="key" data-key="z">Z</div>
                        <div class="key" data-key="x">X</div>
                        <div class="key" data-key="c">C</div>
                        <div class="key" data-key="v">V</div>
                        <div class="key" data-key="b">B</div>
                        <div class="key" data-key="n">N</div>
                        <div class="key" data-key="m">M</div>
                        <div class="key" data-key=",">&lt;<br>,</div>
                        <div class="key" data-key=".">&gt;<br>.</div>
                        <div class="key" data-key="/">?<br>/</div>
                        <div class="key extra-wide" data-key="Shift">Shift</div>
                    </div>
                    <div class="keyboard-row">
                        <div class="key" data-key="Ctrl">Ctrl</div>
                        <div class="key" data-key="Alt">Alt</div>
                        <div class="key space-bar" data-key=" ">Space</div>
                        <div class="key" data-key="Alt">Alt</div>
                        <div class="key" data-key="Ctrl">Ctrl</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Competition Results Modal -->
        <div class="modal fade" id="resultsModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Competition Results</h5>
                    </div>
                    <div class="modal-body">
                        <div class="results-content">
                            <div class="final-stats">
                                <div class="stat-card">
                                    <div class="stat-icon">
                                        <i class="fas fa-tachometer-alt"></i>
                                    </div>
                                    <div class="stat-info">
                                        <div class="stat-number" id="final-wpm">0</div>
                                        <div class="stat-label">Words Per Minute</div>
                                    </div>
                                </div>
                                <div class="stat-card">
                                    <div class="stat-icon">
                                        <i class="fas fa-bullseye"></i>
                                    </div>
                                    <div class="stat-info">
                                        <div class="stat-number" id="final-accuracy">0%</div>
                                        <div class="stat-label">Accuracy</div>
                                    </div>
                                </div>
                                <div class="stat-card">
                                    <div class="stat-icon">
                                        <i class="fas fa-trophy"></i>
                                    </div>
                                    <div class="stat-info">
                                        <div class="stat-number" id="final-position">#0</div>
                                        <div class="stat-label">Final Position</div>
                                    </div>
                                </div>
                                <div class="stat-card">
                                    <div class="stat-icon">
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <div class="stat-info">
                                        <div class="stat-number" id="exp-earned">0</div>
                                        <div class="stat-label">EXP Earned</div>
                                    </div>
                                </div>
                            </div>
                            <div class="achievement-notifications" id="achievement-notifications">
                                <!-- Achievement badges will be populated here -->
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a href="{{ route('competitions.index') }}" class="btn btn-secondary">Back to Competitions</a>
                        <a href="{{ route('practice.index') }}" class="btn btn-primary">Practice More</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.compete-container {
    background: linear-gradient(135deg, var(--bg-primary) 0%, var(--bg-secondary) 100%);
    min-height: 100vh;
    padding: 1rem 0;
    overflow-x: hidden;
}

/* Competition Header */
.compete-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: var(--border-radius-lg);
    padding: 1.5rem 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
}

.competition-title {
    font-family: var(--font-display);
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 0.5rem;
}

.competition-meta {
    display: flex;
    gap: 1.5rem;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--text-secondary);
    font-size: 0.9rem;
}

.meta-item i {
    color: var(--accent-primary);
}

.timer-display {
    text-align: right;
}

.timer-label {
    font-size: 0.9rem;
    color: var(--text-secondary);
    margin-bottom: 0.25rem;
}

.timer-value {
    font-family: var(--font-mono);
    font-size: 2rem;
    font-weight: 700;
    color: var(--accent-danger);
}

/* Race Section */
.race-section {
    margin-bottom: 2rem;
}

.race-track-wrapper {
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: var(--border-radius-lg);
    padding: 2rem;
    overflow-x: auto;
}

.race-track {
    min-width: 800px;
    position: relative;
}

.track-lanes {
    background: linear-gradient(90deg, #f8fafc 0%, #e2e8f0 50%, #f1f5f9 100%);
    border-radius: var(--border-radius);
    padding: 1rem;
    position: relative;
}

.finish-line {
    position: absolute;
    right: 20px;
    top: 0;
    bottom: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    z-index: 10;
}

.finish-line img {
    width: 32px;
    height: 40px;
    margin-bottom: 0.5rem;
}

.finish-text {
    font-weight: 700;
    color: var(--accent-danger);
    font-size: 0.8rem;
    transform: rotate(-90deg);
    white-space: nowrap;
}

/* Race Lanes */
.race-lane {
    display: flex;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px dashed rgba(0,0,0,0.1);
    position: relative;
    min-height: 60px;
}

.race-lane:last-child {
    border-bottom: none;
}

.user-lane {
    background: linear-gradient(90deg, rgba(59, 130, 246, 0.05), rgba(59, 130, 246, 0.02));
    border: 1px solid rgba(59, 130, 246, 0.2);
    border-radius: var(--border-radius);
    margin-bottom: 0.5rem;
}

.lane-info {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    min-width: 150px;
    flex-shrink: 0;
}

.racer-avatar {
    width: 32px;
    height: 32px;
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
    background: var(--accent-primary);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 0.9rem;
}

.bot-avatar {
    background: var(--accent-secondary);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
}

.racer-name {
    font-weight: 600;
    color: var(--text-primary);
    font-size: 0.9rem;
}

.racer-car {
    position: absolute;
    left: calc(160px + var(--progress));
    transition: left 0.3s ease;
    z-index: 5;
}

.racer-car img {
    width: 28px;
    height: 28px;
    filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2));
}

.user-car img {
    filter: drop-shadow(0 2px 4px rgba(59, 130, 246, 0.3));
}

.lane-stats {
    position: absolute;
    right: 60px;
    display: flex;
    gap: 1rem;
    font-size: 0.8rem;
}

.wpm-display {
    color: var(--accent-primary);
    font-weight: 600;
}

.accuracy-display {
    color: var(--accent-success);
    font-weight: 600;
}

/* Typing Section */
.typing-section {
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: var(--border-radius-lg);
    overflow: hidden;
}

.typing-container {
    padding: 2rem;
}

/* Competition Status */
.competition-status {
    text-align: center;
    padding: 3rem 2rem;
}

.status-icon {
    font-size: 3rem;
    color: var(--accent-primary);
    margin-bottom: 1rem;
}

.competition-status h3 {
    font-family: var(--font-display);
    font-size: 1.5rem;
    color: var(--text-primary);
    margin-bottom: 1rem;
}

.competition-status p {
    color: var(--text-secondary);
    font-size: 1.1rem;
}

#start-countdown {
    font-weight: 700;
    color: var(--accent-danger);
    font-size: 1.2rem;
}

/* Text Display */
.text-display {
    margin-bottom: 2rem;
}

.text-content {
    background: var(--bg-secondary);
    border: 2px solid var(--border-light);
    border-radius: var(--border-radius);
    padding: 2rem;
    font-family: var(--font-mono);
    font-size: 1.2rem;
    line-height: 1.8;
    max-height: 200px;
    overflow-y: auto;
}

.text-wrapper {
    position: relative;
}

.char {
    position: relative;
    transition: all 0.2s ease;
}

.char.correct {
    background: rgba(34, 197, 94, 0.2);
    color: var(--accent-success);
}

.char.incorrect {
    background: rgba(239, 68, 68, 0.2);
    color: var(--accent-danger);
}

.char.current {
    background: var(--accent-primary);
    color: white;
    animation: blink 1s infinite;
}

@keyframes blink {
    0%, 50% { opacity: 1; }
    51%, 100% { opacity: 0.5; }
}

/* Typing Input */
.typing-input-wrapper {
    margin-bottom: 2rem;
}

.typing-input {
    width: 100%;
    background: var(--bg-primary);
    border: 2px solid var(--border-light);
    border-radius: var(--border-radius);
    padding: 1rem;
    font-family: var(--font-mono);
    font-size: 1.1rem;
    color: var(--text-primary);
    resize: none;
    transition: border-color 0.3s ease;
}

.typing-input:focus {
    outline: none;
    border-color: var(--accent-primary);
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.input-stats {
    display: flex;
    justify-content: space-around;
    background: var(--bg-secondary);
    border: 1px solid var(--border-light);
    border-top: none;
    border-radius: 0 0 var(--border-radius) var(--border-radius);
    padding: 1rem;
}

.stat-item {
    text-align: center;
}

.stat-label {
    display: block;
    font-size: 0.8rem;
    color: var(--text-secondary);
    margin-bottom: 0.25rem;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.stat-value {
    display: block;
    font-family: var(--font-display);
    font-size: 1.2rem;
    font-weight: 700;
    color: var(--text-primary);
}

/* Virtual Keyboard */
.virtual-keyboard {
    background: var(--bg-secondary);
    border: 1px solid var(--border-light);
    border-radius: var(--border-radius);
    padding: 1rem;
}

.keyboard-row {
    display: flex;
    justify-content: center;
    margin-bottom: 0.5rem;
}

.keyboard-row:last-child {
    margin-bottom: 0;
}

.key {
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: 4px;
    padding: 0.5rem;
    margin: 0 2px;
    min-width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.8rem;
    font-weight: 500;
    color: var(--text-primary);
    transition: all 0.2s ease;
    cursor: pointer;
    user-select: none;
}

.key:hover {
    background: var(--border-light);
}

.key.active {
    background: var(--accent-primary);
    color: white;
    transform: scale(0.95);
}

.key.next {
    background: var(--accent-warning);
    color: white;
    animation: pulse 1s infinite;
}

.key.wide {
    min-width: 60px;
}

.key.extra-wide {
    min-width: 80px;
}

.key.space-bar {
    min-width: 200px;
}

/* Results Modal */
.modal-content {
    border: none;
    border-radius: var(--border-radius-lg);
}

.modal-header {
    background: var(--champion-gradient);
    color: white;
    border-radius: var(--border-radius-lg) var(--border-radius-lg) 0 0;
}

.modal-title {
    font-family: var(--font-display);
    font-weight: 700;
}

.results-content {
    text-align: center;
}

.final-stats {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: var(--bg-secondary);
    border: 1px solid var(--border-light);
    border-radius: var(--border-radius);
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.stat-card .stat-icon {
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

.stat-info {
    text-align: left;
}

.stat-card .stat-number {
    font-family: var(--font-display);
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text-primary);
}

.stat-card .stat-label {
    color: var(--text-secondary);
    font-size: 0.9rem;
}

/* Achievement Notifications */
.achievement-notifications {
    margin-top: 2rem;
}

.achievement-item {
    background: linear-gradient(135deg, var(--medal-gradient));
    color: white;
    padding: 1rem;
    border-radius: var(--border-radius);
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    animation: slideInUp 0.5s ease;
}

.achievement-icon {
    width: 48px;
    height: 48px;
    background: rgba(255,255,255,0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
}

.achievement-content h4 {
    margin: 0 0 0.25rem 0;
    font-weight: 600;
}

.achievement-content p {
    margin: 0;
    opacity: 0.9;
    font-size: 0.9rem;
}

@keyframes slideInUp {
    from {
        transform: translateY(20px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

/* Responsive Design */
@media (max-width: 1024px) {
    .compete-header {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
    
    .final-stats {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .compete-container {
        padding: 0.5rem 0;
    }
    
    .compete-header,
    .race-track-wrapper,
    .typing-container {
        padding: 1rem;
    }
    
    .race-track {
        min-width: 600px;
    }
    
    .lane-info {
        min-width: 120px;
    }
    
    .racer-car {
        left: calc(130px + var(--progress));
    }
    
    .virtual-keyboard {
        display: none; /* Hide on mobile for space */
    }
    
    .input-stats {
        flex-wrap: wrap;
        gap: 1rem;
    }
    
    .text-content {
        font-size: 1rem;
        padding: 1rem;
    }
}
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

<script>
class CompetitionTypingTest {
    constructor() {
        this.competitionText = @json($competition->text->content);
        this.competitionDuration = {{ $competition->duration }};
        this.bots = @json($bots);
        this.startTime = null;
        this.endTime = null;
        this.currentIndex = 0;
        this.errors = 0;
        this.isStarted = false;
        this.isFinished = false;
        this.botIntervals = [];
        
        this.initializeElements();
        this.startCountdown();
    }
    
    initializeElements() {
        this.statusElement = document.getElementById('competition-status');
        this.textDisplay = document.getElementById('text-display');
        this.typingWrapper = document.getElementById('typing-wrapper');
        this.typingInput = document.getElementById('typing-input');
        this.virtualKeyboard = document.getElementById('virtual-keyboard');
        this.timerElement = document.getElementById('competition-timer');
        
        // Stat elements
        this.wpmElement = document.getElementById('current-wpm');
        this.accuracyElement = document.getElementById('current-accuracy');
        this.progressElement = document.getElementById('current-progress');
        this.errorsElement = document.getElementById('current-errors');
        
        // Race elements
        this.userCar = document.querySelector('.user-car');
        this.userWpmDisplay = document.querySelector('.user-lane .wpm-display');
        this.userAccuracyDisplay = document.querySelector('.user-lane .accuracy-display');
        
        // Bind events
        this.typingInput.addEventListener('input', (e) => this.handleInput(e));
        this.typingInput.addEventListener('keydown', (e) => this.handleKeyDown(e));
        
        // Prevent leaving during competition
        window.addEventListener('beforeunload', (e) => {
            if (this.isStarted && !this.isFinished) {
                e.preventDefault();
                e.returnValue = 'Are you sure you want to leave? This will disqualify you from the competition.';
            }
        });
    }
    
    startCountdown() {
        let countdown = 3;
        const countdownElement = document.getElementById('start-countdown');
        
        const interval = setInterval(() => {
            countdownElement.textContent = countdown;
            countdown--;
            
            if (countdown < 0) {
                clearInterval(interval);
                this.startCompetition();
            }
        }, 1000);
    }
    
    startCompetition() {
        this.isStarted = true;
        this.startTime = Date.now();
        
        // Hide status, show typing area
        this.statusElement.style.display = 'none';
        this.textDisplay.style.display = 'block';
        this.typingWrapper.style.display = 'block';
        this.virtualKeyboard.style.display = 'block';
        
        // Enable input
        this.typingInput.disabled = false;
        this.typingInput.focus();
        
        // Start timer
        this.startTimer();
        
        // Start bots
        this.startBots();
        
        // Highlight first character
        this.updateCharacterHighlight();
    }
    
    startTimer() {
        const startTime = Date.now();
        const duration = this.competitionDuration * 60 * 1000; // Convert to milliseconds
        
        this.timerInterval = setInterval(() => {
            const elapsed = Date.now() - startTime;
            const remaining = Math.max(0, duration - elapsed);
            
            const minutes = Math.floor(remaining / 60000);
            const seconds = Math.floor((remaining % 60000) / 1000);
            
            this.timerElement.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;
            
            if (remaining <= 0) {
                this.endCompetition('timeout');
            }
        }, 1000);
    }
    
    startBots() {
        this.bots.forEach((bot, index) => {
            const botCar = document.querySelector(`[data-bot-id="${bot.id}"] .bot-car`);
            const botWpmDisplay = document.querySelector(`[data-bot-id="${bot.id}"] .wpm-display`);
            const botAccuracyDisplay = document.querySelector(`[data-bot-id="${bot.id}"] .accuracy-display`);
            
            let botProgress = 0;
            const botSpeed = bot.typing_speed / 60; // chars per second
            const textLength = this.competitionText.length;
            
            const interval = setInterval(() => {
                if (this.isFinished) {
                    clearInterval(interval);
                    return;
                }
                
                // Add some randomness to bot speed
                const variance = (Math.random() - 0.5) * 0.2;
                const currentSpeed = botSpeed * (1 + variance);
                
                botProgress += currentSpeed;
                const progressPercent = Math.min(100, (botProgress / textLength) * 100);
                
                // Update bot position
                botCar.style.setProperty('--progress', `${progressPercent * 0.8}%`);
                
                // Update bot stats
                const currentWpm = Math.round(bot.typing_speed + (Math.random() - 0.5) * 10);
                const currentAccuracy = Math.round(bot.accuracy + (Math.random() - 0.5) * 5);
                
                botWpmDisplay.textContent = `${currentWpm} WPM`;
                botAccuracyDisplay.textContent = `${Math.max(85, currentAccuracy)}%`;
                
                // Check if bot finished
                if (progressPercent >= 100) {
                    clearInterval(interval);
                }
            }, 1000);
            
            this.botIntervals.push(interval);
        });
    }
    
    handleInput(e) {
        if (!this.isStarted || this.isFinished) return;
        
        const inputText = e.target.value;
        this.currentIndex = inputText.length;
        
        // Update character highlighting
        this.updateCharacterHighlight();
        
        // Update statistics
        this.updateStats();
        
        // Update race position
        this.updateRacePosition();
        
        // Check if completed
        if (this.currentIndex >= this.competitionText.length) {
            this.endCompetition('completed');
        }
    }
    
    handleKeyDown(e) {
        if (!this.isStarted || this.isFinished) return;
        
        // Highlight keyboard key
        const key = e.key.toLowerCase();
        this.highlightKey(key);
        
        // Handle special keys
        if (e.key === 'Tab') {
            e.preventDefault();
        }
    }
    
    updateCharacterHighlight() {
        const chars = document.querySelectorAll('.char');
        const inputText = this.typingInput.value;
        
        chars.forEach((char, index) => {
            char.classList.remove('correct', 'incorrect', 'current');
            
            if (index < inputText.length) {
                if (inputText[index] === this.competitionText[index]) {
                    char.classList.add('correct');
                } else {
                    char.classList.add('incorrect');
                }
            } else if (index === inputText.length) {
                char.classList.add('current');
                
                // Highlight next key on virtual keyboard
                const nextChar = this.competitionText[index];
                this.highlightNextKey(nextChar);
            }
        });
    }
    
    highlightKey(key) {
        // Remove previous highlights
        document.querySelectorAll('.key').forEach(k => k.classList.remove('active'));
        
        // Highlight current key
        const keyElement = document.querySelector(`[data-key="${key}"]`);
        if (keyElement) {
            keyElement.classList.add('active');
            setTimeout(() => keyElement.classList.remove('active'), 200);
        }
    }
    
    highlightNextKey(char) {
        // Remove previous next highlights
        document.querySelectorAll('.key').forEach(k => k.classList.remove('next'));
        
        // Highlight next key
        const keyElement = document.querySelector(`[data-key="${char.toLowerCase()}"]`);
        if (keyElement) {
            keyElement.classList.add('next');
        }
    }
    
    updateStats() {
        const inputText = this.typingInput.value;
        const timeElapsed = (Date.now() - this.startTime) / 1000 / 60; // minutes
        
        // Calculate WPM
        const wordsTyped = inputText.trim().split(' ').length;
        const wpm = timeElapsed > 0 ? Math.round(wordsTyped / timeElapsed) : 0;
        
        // Calculate accuracy
        let correctChars = 0;
        for (let i = 0; i < inputText.length; i++) {
            if (inputText[i] === this.competitionText[i]) {
                correctChars++;
            }
        }
        const accuracy = inputText.length > 0 ? Math.round((correctChars / inputText.length) * 100) : 100;
        
        // Calculate progress
        const progress = Math.round((inputText.length / this.competitionText.length) * 100);
        
        // Calculate errors
        this.errors = inputText.length - correctChars;
        
        // Update displays
        this.wpmElement.textContent = wpm;
        this.accuracyElement.textContent = accuracy + '%';
        this.progressElement.textContent = progress + '%';
        this.errorsElement.textContent = this.errors;
        
        // Update race lane stats
        this.userWpmDisplay.textContent = `${wpm} WPM`;
        this.userAccuracyDisplay.textContent = `${accuracy}%`;
    }
    
    updateRacePosition() {
        const progress = (this.currentIndex / this.competitionText.length) * 80; // 80% of track width
        this.userCar.style.setProperty('--progress', `${progress}%`);
    }
    
    endCompetition(reason) {
        this.isFinished = true;
        this.endTime = Date.now();
        
        // Clear intervals
        clearInterval(this.timerInterval);
        this.botIntervals.forEach(interval => clearInterval(interval));
        
        // Disable input
        this.typingInput.disabled = true;
        
        // Calculate final stats
        this.calculateFinalResults(reason);
    }
    
    calculateFinalResults(reason) {
        const inputText = this.typingInput.value;
        const timeElapsed = (this.endTime - this.startTime) / 1000 / 60; // minutes
        
        // Final calculations
        const wordsTyped = inputText.trim().split(' ').length;
        const finalWpm = timeElapsed > 0 ? Math.round(wordsTyped / timeElapsed) : 0;
        
        let correctChars = 0;
        for (let i = 0; i < inputText.length; i++) {
            if (inputText[i] === this.competitionText[i]) {
                correctChars++;
            }
        }
        const finalAccuracy = inputText.length > 0 ? Math.round((correctChars / inputText.length) * 100) : 0;
        
        // Calculate position (simplified - in real app this would come from server)
        const finalPosition = Math.floor(Math.random() * 5) + 1;
        
        // Calculate EXP earned
        const baseExp = {{ $competition->experience_reward }};
        const positionMultiplier = Math.max(0.5, 1.5 - (finalPosition - 1) * 0.2);
        const expEarned = Math.round(baseExp * positionMultiplier);
        
        // Show results modal
        this.showResults(finalWpm, finalAccuracy, finalPosition, expEarned);
        
        // Submit results to server
        this.submitResults(finalWpm, finalAccuracy, reason, inputText);
    }
    
    showResults(wpm, accuracy, position, exp) {
        document.getElementById('final-wpm').textContent = wpm;
        document.getElementById('final-accuracy').textContent = accuracy + '%';
        document.getElementById('final-position').textContent = '#' + position;
        document.getElementById('exp-earned').textContent = exp;
        
        // Show achievement notifications
        this.showAchievements(wpm, accuracy);
        
        // Show modal
        const modal = new bootstrap.Modal(document.getElementById('resultsModal'));
        modal.show();
    }
    
    showAchievements(wpm, accuracy) {
        const achievementsContainer = document.getElementById('achievement-notifications');
        const achievements = [];
        
        // Check for achievements
        if (wpm >= 60) achievements.push({
            icon: 'fas fa-tachometer-alt',
            title: 'Speed Demon!',
            description: 'Achieved 60+ WPM'
        });
        
        if (accuracy >= 95) achievements.push({
            icon: 'fas fa-bullseye',
            title: 'Precision Master!',
            description: 'Achieved 95%+ accuracy'
        });
        
        if (this.errors === 0) achievements.push({
            icon: 'fas fa-crown',
            title: 'Flawless Victory!',
            description: 'Completed without errors'
        });
        
        // Display achievements
        achievements.forEach((achievement, index) => {
            setTimeout(() => {
                const achievementElement = document.createElement('div');
                achievementElement.className = 'achievement-item';
                achievementElement.innerHTML = `
                    <div class="achievement-icon">
                        <i class="${achievement.icon}"></i>
                    </div>
                    <div class="achievement-content">
                        <h4>${achievement.title}</h4>
                        <p>${achievement.description}</p>
                    </div>
                `;
                achievementsContainer.appendChild(achievementElement);
            }, index * 500);
        });
    }
    
    submitResults(wpm, accuracy, reason, typedText) {
        // In a real application, submit to server via AJAX
        fetch('{{ route("competitions.submit-result", $competition) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                typing_speed: wpm,
                accuracy: accuracy,
                completion_status: reason,
                typed_text: typedText,
                time_taken: (this.endTime - this.startTime) / 1000
            })
        }).catch(error => {
            console.error('Error submitting results:', error);
        });
    }
}

// Initialize competition when page loads
document.addEventListener('DOMContentLoaded', function() {
    new CompetitionTypingTest();
});
</script>
@endsection