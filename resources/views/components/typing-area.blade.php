{{-- 
    Typing Area Component - SportTyping
    
    Props:
    - $textContent: String - Text yang harus diketik
    - $mode: String - 'practice'|'competition'|'lesson' (default: practice)
    - $timeLimit: Integer - Batas waktu dalam detik (optional)
    - $showStats: Boolean - Tampilkan statistik (default: true)
    - $autoFocus: Boolean - Auto focus input (default: true)
    - $allowBackspace: Boolean - Izinkan backspace (default: true)
    - $showProgress: Boolean - Tampilkan progress bar (default: true)
--}}

@props([
    'textContent' => 'The quick brown fox jumps over the lazy dog. This is a sample text for typing practice.',
    'mode' => 'practice',
    'timeLimit' => null,
    'showStats' => true,
    'autoFocus' => true,
    'allowBackspace' => true,
    'showProgress' => true,
    'textId' => null,
    'competitionId' => null
])

<div class="typing-area-container" data-mode="{{ $mode }}" data-time-limit="{{ $timeLimit }}" data-text-id="{{ $textId }}" data-competition-id="{{ $competitionId }}">
    
    {{-- Typing Header --}}
    <div class="typing-header">
        <div class="typing-mode-info">
            <div class="mode-badge {{ $mode }}">
                <i class="fas fa-{{ $mode === 'competition' ? 'racing-flag' : ($mode === 'lesson' ? 'graduation-cap' : 'keyboard') }}"></i>
                <span>{{ ucfirst($mode) }} Mode</span>
            </div>
            @if($timeLimit)
                <div class="time-limit-badge">
                    <i class="fas fa-clock"></i>
                    <span>{{ $timeLimit }}s Limit</span>
                </div>
            @endif
        </div>
        
        @if($showStats)
        <div class="typing-stats-mini">
            <div class="stat-mini">
                <span class="stat-value" id="current-wpm">0</span>
                <span class="stat-label">WPM</span>
            </div>
            <div class="stat-mini">
                <span class="stat-value" id="current-accuracy">100</span>
                <span class="stat-label">ACC%</span>
            </div>
            <div class="stat-mini">
                <span class="stat-value" id="current-time">0:00</span>
                <span class="stat-label">TIME</span>
            </div>
        </div>
        @endif
    </div>

    {{-- Progress Bar --}}
    @if($showProgress)
    <div class="typing-progress-container">
        <div class="typing-progress-bar">
            <div class="progress-fill" id="typing-progress"></div>
            <div class="progress-text">
                <span id="progress-chars">0</span> / <span id="total-chars">{{ strlen($textContent) }}</span> characters
            </div>
        </div>
    </div>
    @endif

    {{-- Main Typing Area --}}
    <div class="typing-main-area">
        
        {{-- Text Display with Highlighting --}}
        <div class="typing-text-display" id="text-display">
            <div class="typing-text-content" id="text-content" data-text="{{ $textContent }}">
                {!! $textContent !!}
            </div>
            <div class="typing-cursor" id="typing-cursor"></div>
        </div>

        {{-- Typing Input --}}
        <div class="typing-input-area">
            <textarea 
                id="typing-input" 
                class="typing-input"
                placeholder="Click here and start typing..."
                autocomplete="off"
                autocorrect="off"
                autocapitalize="off"
                spellcheck="false"
                @if($autoFocus) autofocus @endif
                @if(!$allowBackspace) data-no-backspace="true" @endif
            ></textarea>
            
            {{-- Input Overlay for Visual Effects --}}
            <div class="input-overlay">
                <div class="input-focus-ring"></div>
            </div>
        </div>

        {{-- Typing Status Messages --}}
        <div class="typing-status" id="typing-status">
            <div class="status-message" id="status-message">
                <i class="fas fa-play"></i>
                <span>Press any key to start typing...</span>
            </div>
        </div>

    </div>

    {{-- Detailed Statistics Panel --}}
    @if($showStats)
    <div class="typing-stats-panel" id="stats-panel">
        <div class="stats-grid">
            <div class="stat-card speed">
                <div class="stat-icon">
                    <i class="fas fa-tachometer-alt"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value" id="detailed-wpm">0</div>
                    <div class="stat-label">Words Per Minute</div>
                    <div class="stat-trend" id="wmp-trend">
                        <i class="fas fa-minus"></i>
                        <span>--</span>
                    </div>
                </div>
            </div>
            
            <div class="stat-card accuracy">
                <div class="stat-icon">
                    <i class="fas fa-bullseye"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value" id="detailed-accuracy">100%</div>
                    <div class="stat-label">Accuracy Rate</div>
                    <div class="stat-trend" id="accuracy-trend">
                        <i class="fas fa-minus"></i>
                        <span>--</span>
                    </div>
                </div>
            </div>
            
            <div class="stat-card errors">
                <div class="stat-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value" id="error-count">0</div>
                    <div class="stat-label">Errors Made</div>
                    <div class="stat-detail" id="error-rate">0% error rate</div>
                </div>
            </div>
            
            <div class="stat-card consistency">
                <div class="stat-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value" id="consistency-score">--</div>
                    <div class="stat-label">Consistency</div>
                    <div class="stat-detail" id="consistency-detail">Starting...</div>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Action Buttons --}}
    <div class="typing-actions">
        <button type="button" class="btn-action restart" id="restart-btn" title="Restart Test">
            <i class="fas fa-redo"></i>
            <span>Restart</span>
        </button>
        
        @if($mode === 'practice')
        <button type="button" class="btn-action settings" id="settings-btn" title="Test Settings">
            <i class="fas fa-cog"></i>
            <span>Settings</span>
        </button>
        @endif
        
        <button type="button" class="btn-action pause" id="pause-btn" title="Pause Test" style="display: none;">
            <i class="fas fa-pause"></i>
            <span>Pause</span>
        </button>
        
        <button type="button" class="btn-action finish" id="finish-btn" title="Finish Test" style="display: none;">
            <i class="fas fa-flag"></i>
            <span>Finish</span>
        </button>
    </div>

    {{-- Completion Modal/Panel --}}
    <div class="typing-completion" id="completion-panel" style="display: none;">
        <div class="completion-content">
            <div class="completion-header">
                <div class="completion-icon">
                    <i class="fas fa-trophy"></i>
                </div>
                <h2>Test Completed!</h2>
                <p class="completion-message">Great job! Here are your results:</p>
            </div>
            
            <div class="completion-stats">
                <div class="final-stat primary">
                    <div class="final-stat-value" id="final-wpm">0</div>
                    <div class="final-stat-label">Words Per Minute</div>
                </div>
                <div class="final-stat success">
                    <div class="final-stat-value" id="final-accuracy">0%</div>
                    <div class="final-stat-label">Accuracy</div>
                </div>
                <div class="final-stat info">
                    <div class="final-stat-value" id="final-time">0:00</div>
                    <div class="final-stat-label">Time Taken</div>
                </div>
            </div>
            
            <div class="completion-actions">
                <button type="button" class="btn-primary" id="save-result-btn">
                    <i class="fas fa-save"></i>
                    Save Result
                </button>
                <button type="button" class="btn-secondary" id="try-again-btn">
                    <i class="fas fa-redo"></i>
                    Try Again
                </button>
                @if($mode === 'practice')
                <button type="button" class="btn-ghost" id="new-text-btn">
                    <i class="fas fa-random"></i>
                    New Text
                </button>
                @endif
            </div>
        </div>
    </div>

</div>

<style>
/* Typing Area Container */
.typing-area-container {
    background: var(--bg-card);
    border-radius: var(--border-radius-lg);
    border: 1px solid var(--border-light);
    overflow: hidden;
    box-shadow: var(--shadow-sm);
    transition: all 0.3s ease;
}

.typing-area-container:focus-within {
    border-color: var(--accent-primary);
    box-shadow: var(--sport-glow);
}

/* Typing Header */
.typing-header {
    background: var(--bg-secondary);
    padding: 1.5rem 2rem;
    border-bottom: 1px solid var(--border-light);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.typing-mode-info {
    display: flex;
    gap: 1rem;
    align-items: center;
}

.mode-badge {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: var(--border-radius);
    font-weight: 600;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.mode-badge.practice {
    background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(16, 185, 129, 0.05));
    color: var(--accent-success);
    border: 1px solid rgba(16, 185, 129, 0.2);
}

.mode-badge.competition {
    background: linear-gradient(135deg, rgba(239, 68, 68, 0.1), rgba(239, 68, 68, 0.05));
    color: var(--accent-danger);
    border: 1px solid rgba(239, 68, 68, 0.2);
}

.mode-badge.lesson {
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(59, 130, 246, 0.05));
    color: var(--accent-primary);
    border: 1px solid rgba(59, 130, 246, 0.2);
}

.time-limit-badge {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: var(--border-radius);
    background: rgba(245, 158, 11, 0.1);
    color: var(--accent-secondary);
    border: 1px solid rgba(245, 158, 11, 0.2);
    font-weight: 600;
    font-size: 0.875rem;
}

.typing-stats-mini {
    display: flex;
    gap: 2rem;
}

.stat-mini {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.25rem;
}

.stat-mini .stat-value {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--accent-primary);
    line-height: 1.2;
}

.stat-mini .stat-label {
    font-size: 0.75rem;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 1px;
}

/* Progress Bar */
.typing-progress-container {
    padding: 1rem 2rem;
    background: var(--bg-primary);
    border-bottom: 1px solid var(--border-light);
}

.typing-progress-bar {
    position: relative;
    height: 8px;
    background: var(--border-light);
    border-radius: 4px;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    background: var(--champion-gradient);
    border-radius: 4px;
    transition: width 0.3s ease;
    width: 0%;
}

.progress-text {
    position: absolute;
    top: 1rem;
    right: 0;
    font-size: 0.875rem;
    color: var(--text-muted);
    font-weight: 500;
}

/* Main Typing Area */
.typing-main-area {
    padding: 2rem;
    position: relative;
}

/* Text Display */
.typing-text-display {
    background: var(--bg-secondary);
    border-radius: var(--border-radius);
    padding: 2rem;
    margin-bottom: 2rem;
    min-height: 120px;
    position: relative;
    border: 2px solid var(--border-light);
    transition: all 0.3s ease;
}

.typing-text-display:focus-within {
    border-color: var(--accent-primary);
    background: rgba(59, 130, 246, 0.02);
}

.typing-text-content {
    font-family: 'Courier New', 'Monaco', monospace;
    font-size: 1.25rem;
    line-height: 1.8;
    color: var(--text-primary);
    letter-spacing: 0.5px;
    word-spacing: 2px;
    position: relative;
    z-index: 2;
}

/* Text Highlighting Classes */
.typing-text-content .char {
    position: relative;
    transition: all 0.2s ease;
}

.typing-text-content .char.correct {
    background: rgba(16, 185, 129, 0.2);
    color: var(--accent-success);
}

.typing-text-content .char.incorrect {
    background: rgba(239, 68, 68, 0.2);
    color: var(--accent-danger);
}

.typing-text-content .char.current {
    background: var(--accent-primary);
    color: white;
    animation: currentBlink 1s infinite;
}

.typing-text-content .char.pending {
    color: var(--text-muted);
}

.typing-cursor {
    position: absolute;
    width: 2px;
    height: 1.8rem;
    background: var(--accent-primary);
    animation: cursorBlink 1s infinite;
    z-index: 3;
    transition: all 0.1s ease;
}

/* Input Area */
.typing-input-area {
    position: relative;
    margin-bottom: 2rem;
}

.typing-input {
    width: 100%;
    min-height: 120px;
    padding: 1.5rem;
    border: 2px solid var(--border-light);
    border-radius: var(--border-radius);
    background: var(--bg-card);
    color: var(--text-primary);
    font-family: 'Courier New', 'Monaco', monospace;
    font-size: 1.125rem;
    line-height: 1.6;
    resize: vertical;
    transition: all 0.3s ease;
}

.typing-input:focus {
    outline: none;
    border-color: var(--accent-primary);
    box-shadow: var(--sport-glow);
    background: rgba(59, 130, 246, 0.02);
}

.typing-input::placeholder {
    color: var(--text-muted);
    font-style: italic;
}

.input-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    pointer-events: none;
    border-radius: var(--border-radius);
    transition: all 0.3s ease;
}

.typing-input:focus + .input-overlay .input-focus-ring {
    border: 2px solid transparent;
    background: linear-gradient(var(--bg-card), var(--bg-card)) padding-box,
                var(--champion-gradient) border-box;
    border-radius: var(--border-radius);
}

/* Typing Status */
.typing-status {
    text-align: center;
    margin-bottom: 2rem;
}

.status-message {
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem 2rem;
    background: var(--bg-secondary);
    border: 1px solid var(--border-light);
    border-radius: var(--border-radius);
    color: var(--text-secondary);
    font-weight: 500;
    transition: all 0.3s ease;
}

.status-message.active {
    background: rgba(16, 185, 129, 0.1);
    border-color: var(--accent-success);
    color: var(--accent-success);
}

.status-message.paused {
    background: rgba(245, 158, 11, 0.1);
    border-color: var(--accent-secondary);
    color: var(--accent-secondary);
}

.status-message.error {
    background: rgba(239, 68, 68, 0.1);
    border-color: var(--accent-danger);
    color: var(--accent-danger);
}

/* Statistics Panel */
.typing-stats-panel {
    background: var(--bg-secondary);
    border-top: 1px solid var(--border-light);
    padding: 2rem;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
}

.stat-card {
    background: var(--bg-card);
    border-radius: var(--border-radius);
    padding: 1.5rem;
    border: 1px solid var(--border-light);
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.25rem;
    flex-shrink: 0;
}

.stat-card.speed .stat-icon { background: var(--champion-gradient); }
.stat-card.accuracy .stat-icon { background: var(--victory-gradient); }
.stat-card.errors .stat-icon { background: linear-gradient(135deg, #ef4444, #dc2626); }
.stat-card.consistency .stat-icon { background: linear-gradient(135deg, #8b5cf6, #7c3aed); }

.stat-content {
    flex: 1;
}

.stat-content .stat-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text-primary);
    line-height: 1.2;
    margin-bottom: 0.25rem;
}

.stat-content .stat-label {
    color: var(--text-secondary);
    font-size: 0.875rem;
    font-weight: 500;
    margin-bottom: 0.5rem;
}

.stat-trend {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.8rem;
    font-weight: 600;
}

.stat-trend.up {
    color: var(--accent-success);
}

.stat-trend.down {
    color: var(--accent-danger);
}

.stat-trend.neutral {
    color: var(--text-muted);
}

.stat-detail {
    color: var(--text-muted);
    font-size: 0.8rem;
}

/* Action Buttons */
.typing-actions {
    padding: 1.5rem 2rem;
    background: var(--bg-primary);
    border-top: 1px solid var(--border-light);
    display: flex;
    gap: 1rem;
    justify-content: center;
}

.btn-action {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem 1.5rem;
    border: 2px solid var(--border-light);
    background: var(--bg-card);
    color: var(--text-secondary);
    border-radius: var(--border-radius);
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-action:hover {
    border-color: var(--accent-primary);
    color: var(--accent-primary);
    background: rgba(59, 130, 246, 0.05);
    transform: translateY(-2px);
}

.btn-action.restart:hover {
    border-color: var(--accent-success);
    color: var(--accent-success);
    background: rgba(16, 185, 129, 0.05);
}

.btn-action.pause:hover {
    border-color: var(--accent-secondary);
    color: var(--accent-secondary);
    background: rgba(245, 158, 11, 0.05);
}

.btn-action.finish:hover {
    border-color: var(--accent-danger);
    color: var(--accent-danger);
    background: rgba(239, 68, 68, 0.05);
}

/* Completion Panel */
.typing-completion {
    position: absolute;
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
}

.completion-content {
    background: var(--bg-card);
    border-radius: var(--border-radius-lg);
    padding: 3rem;
    max-width: 500px;
    width: 90%;
    text-align: center;
    border: 1px solid var(--border-light);
    box-shadow: var(--shadow-xl);
}

.completion-header {
    margin-bottom: 2rem;
}

.completion-icon {
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
    animation: completionPulse 2s infinite;
}

.completion-header h2 {
    font-family: var(--font-display);
    font-size: 2rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 0.5rem;
}

.completion-message {
    color: var(--text-secondary);
    font-size: 1.1rem;
}

.completion-stats {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1rem;
    margin-bottom: 2rem;
}

.final-stat {
    background: var(--bg-secondary);
    border-radius: var(--border-radius);
    padding: 1.5rem 1rem;
    border: 1px solid var(--border-light);
}

.final-stat.primary {
    border-color: var(--accent-primary);
    background: rgba(59, 130, 246, 0.05);
}

.final-stat.success {
    border-color: var(--accent-success);
    background: rgba(16, 185, 129, 0.05);
}

.final-stat.info {
    border-color: var(--accent-purple);
    background: rgba(139, 92, 246, 0.05);
}

.final-stat-value {
    font-size: 1.75rem;
    font-weight: 700;
    color: var(--text-primary);
    line-height: 1.2;
    margin-bottom: 0.5rem;
}

.final-stat.primary .final-stat-value { color: var(--accent-primary); }
.final-stat.success .final-stat-value { color: var(--accent-success); }
.final-stat.info .final-stat-value { color: var(--accent-purple); }

.final-stat-label {
    color: var(--text-secondary);
    font-size: 0.875rem;
    font-weight: 500;
}

.completion-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

/* Animations */
@keyframes currentBlink {
    0%, 50% { background: var(--accent-primary); }
    51%, 100% { background: rgba(59, 130, 246, 0.3); }
}

@keyframes cursorBlink {
    0%, 50% { opacity: 1; }
    51%, 100% { opacity: 0; }
}

@keyframes completionPulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}

/* Responsive Design */
@media (max-width: 1024px) {
    .typing-header {
        flex-direction: column;
        gap: 1rem;
    }
    
    .typing-stats-mini {
        gap: 1rem;
    }
    
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .typing-main-area {
        padding: 1rem;
    }
    
    .typing-header {
        padding: 1rem;
    }
    
    .typing-progress-container {
        padding: 1rem;
    }
    
    .typing-text-display {
        padding: 1.5rem;
    }
    
    .typing-text-content {
        font-size: 1.1rem;
        line-height: 1.6;
    }
    
    .typing-input {
        font-size: 1rem;
        min-height: 100px;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .completion-stats {
        grid-template-columns: 1fr;
    }
    
    .completion-actions {
        flex-direction: column;
    }
    
    .typing-actions {
        flex-wrap: wrap;
        gap: 0.5rem;
    }
    
    .btn-action {
        flex: 1;
        min-width: 120px;
    }
}

@media (max-width: 480px) {
    .typing-text-content {
        font-size: 1rem;
        line-height: 1.5;
    }
    
    .typing-input {
        font-size: 0.95rem;
    }
    
    .completion-content {
        padding: 2rem;
        margin: 1rem;
    }
    
    .completion-header h2 {
        font-size: 1.5rem;
    }
    
    .final-stat-value {
        font-size: 1.5rem;
    }
}
</style>

<script>
// Typing Area Controller Class
class TypingAreaController {
    constructor(container) {
        this.container = container;
        this.textContent = container.querySelector('#text-content').dataset.text;
        this.textDisplay = container.querySelector('#text-content');
        this.typingInput = container.querySelector('#typing-input');
        this.cursor = container.querySelector('#typing-cursor');
        this.statusMessage = container.querySelector('#status-message');
        this.progressBar = container.querySelector('#typing-progress');
        
        // State
        this.currentPosition = 0;
        this.startTime = null;
        this.isActive = false;
        this.isPaused = false;
        this.errors = 0;
        this.correctChars = 0;
        this.typedChars = 0;
        this.lastWPM = 0;
        this.wmpHistory = [];
        
        // Settings
        this.mode = container.dataset.mode || 'practice';
        this.timeLimit = parseInt(container.dataset.timeLimit) || null;
        this.allowBackspace = !this.typingInput.dataset.noBackspace;
        
        this.init();
    }
    
    init() {
        this.setupText();
        this.bindEvents();
        this.updateDisplay();
        this.updateCursor();
        
        // Auto focus if enabled
        if (this.typingInput.hasAttribute('autofocus')) {
            setTimeout(() => this.typingInput.focus(), 100);
        }
    }
    
    setupText() {
        // Convert text to character spans for highlighting
        const chars = this.textContent.split('');
        this.textDisplay.innerHTML = chars.map((char, index) => 
            `<span class="char pending" data-index="${index}">${char === ' ' ? '&nbsp;' : char}</span>`
        ).join('');
        
        this.chars = this.textDisplay.querySelectorAll('.char');
        
        // Update total characters
        const totalCharsElement = this.container.querySelector('#total-chars');
        if (totalCharsElement) {
            totalCharsElement.textContent = chars.length;
        }
    }
    
    bindEvents() {
        // Input events
        this.typingInput.addEventListener('input', (e) => this.handleInput(e));
        this.typingInput.addEventListener('keydown', (e) => this.handleKeyDown(e));
        this.typingInput.addEventListener('focus', () => this.handleFocus());
        this.typingInput.addEventListener('blur', () => this.handleBlur());
        
        // Button events
        const restartBtn = this.container.querySelector('#restart-btn');
        const pauseBtn = this.container.querySelector('#pause-btn');
        const finishBtn = this.container.querySelector('#finish-btn');
        const saveResultBtn = this.container.querySelector('#save-result-btn');
        const tryAgainBtn = this.container.querySelector('#try-again-btn');
        
        if (restartBtn) restartBtn.addEventListener('click', () => this.restart());
        if (pauseBtn) pauseBtn.addEventListener('click', () => this.togglePause());
        if (finishBtn) finishBtn.addEventListener('click', () => this.finish());
        if (saveResultBtn) saveResultBtn.addEventListener('click', () => this.saveResult());
        if (tryAgainBtn) tryAgainBtn.addEventListener('click', () => this.restart());
        
        // Prevent context menu on text display
        this.textDisplay.addEventListener('contextmenu', (e) => e.preventDefault());
    }
    
    handleInput(e) {
        if (!this.isActive && !this.startTime) {
            this.start();
        }
        
        if (this.isPaused) return;
        
        const inputValue = this.typingInput.value;
        const inputLength = inputValue.length;
        
        // Handle backspace restriction
        if (!this.allowBackspace && inputLength < this.typedChars) {
            this.typingInput.value = inputValue.slice(0, this.typedChars);
            return;
        }
        
        this.typedChars = inputLength;
        this.updateHighlighting(inputValue);
        this.updateStats();
        this.updateProgress();
        this.updateCursor();
        
        // Check completion
        if (inputLength >= this.textContent.length) {
            this.complete();
        }
    }
    
    handleKeyDown(e) {
        // Handle special keys
        if (e.key === 'Escape') {
            this.togglePause();
        } else if (e.ctrlKey && e.key === 'r') {
            e.preventDefault();
            this.restart();
        }
    }
    
    handleFocus() {
        this.container.classList.add('focused');
        if (this.isPaused && this.startTime) {
            this.resume();
        }
    }
    
    handleBlur() {
        this.container.classList.remove('focused');
        if (this.isActive) {
            this.pause();
        }
    }
    
    start() {
        this.startTime = Date.now();
        this.isActive = true;
        this.isPaused = false;
        
        this.updateStatus('active', 'Typing in progress...', 'play');
        
        // Show pause and finish buttons
        const pauseBtn = this.container.querySelector('#pause-btn');
        const finishBtn = this.container.querySelector('#finish-btn');
        if (pauseBtn) pauseBtn.style.display = 'flex';
        if (finishBtn) finishBtn.style.display = 'flex';
        
        // Start timer if time limit exists
        if (this.timeLimit) {
            this.startTimer();
        }
        
        // Dispatch start event
        this.dispatchEvent('typing:started', {
            mode: this.mode,
            textLength: this.textContent.length,
            timeLimit: this.timeLimit
        });
    }
    
    pause() {
        if (!this.isActive) return;
        
        this.isPaused = true;
        this.updateStatus('paused', 'Test paused. Click to resume...', 'pause');
        
        // Dispatch pause event
        this.dispatchEvent('typing:paused', this.getStats());
    }
    
    resume() {
        if (!this.isPaused) return;
        
        this.isPaused = false;
        this.updateStatus('active', 'Typing in progress...', 'play');
        
        // Dispatch resume event
        this.dispatchEvent('typing:resumed', this.getStats());
    }
    
    togglePause() {
        if (this.isPaused) {
            this.resume();
        } else {
            this.pause();
        }
    }
    
    finish() {
        if (!this.isActive) return;
        
        this.complete();
    }
    
    complete() {
        this.isActive = false;
        this.isPaused = false;
        
        const finalStats = this.getStats();
        this.showCompletion(finalStats);
        
        // Hide action buttons
        const pauseBtn = this.container.querySelector('#pause-btn');
        const finishBtn = this.container.querySelector('#finish-btn');
        if (pauseBtn) pauseBtn.style.display = 'none';
        if (finishBtn) finishBtn.style.display = 'none';
        
        // Dispatch complete event
        this.dispatchEvent('typing:completed', finalStats);
    }
    
    restart() {
        // Reset state
        this.currentPosition = 0;
        this.startTime = null;
        this.isActive = false;
        this.isPaused = false;
        this.errors = 0;
        this.correctChars = 0;
        this.typedChars = 0;
        this.lastWPM = 0;
        this.wmpHistory = [];
        
        // Reset UI
        this.typingInput.value = '';
        this.setupText();
        this.updateStats();
        this.updateProgress();
        this.updateCursor();
        this.updateStatus('ready', 'Press any key to start typing...', 'play');
        
        // Hide completion panel
        const completionPanel = this.container.querySelector('#completion-panel');
        if (completionPanel) {
            completionPanel.style.display = 'none';
        }
        
        // Reset buttons
        const pauseBtn = this.container.querySelector('#pause-btn');
        const finishBtn = this.container.querySelector('#finish-btn');
        if (pauseBtn) pauseBtn.style.display = 'none';
        if (finishBtn) finishBtn.style.display = 'none';
        
        // Focus input
        this.typingInput.focus();
        
        // Dispatch restart event
        this.dispatchEvent('typing:restarted');
    }
    
    updateHighlighting(inputValue) {
        this.chars.forEach((char, index) => {
            const inputChar = inputValue[index];
            const originalChar = this.textContent[index];
            
            // Reset classes
            char.className = 'char';
            
            if (index < inputValue.length) {
                if (inputChar === originalChar) {
                    char.classList.add('correct');
                    if (index === this.correctChars) {
                        this.correctChars = index + 1;
                    }
                } else {
                    char.classList.add('incorrect');
                    if (index >= this.correctChars) {
                        this.errors++;
                    }
                }
            } else if (index === inputValue.length) {
                char.classList.add('current');
            } else {
                char.classList.add('pending');
            }
        });
        
        this.currentPosition = inputValue.length;
    }
    
    updateCursor() {
        if (this.currentPosition < this.chars.length) {
            const currentChar = this.chars[this.currentPosition];
            const rect = currentChar.getBoundingClientRect();
            const containerRect = this.textDisplay.getBoundingClientRect();
            
            this.cursor.style.left = (rect.left - containerRect.left) + 'px';
            this.cursor.style.top = (rect.top - containerRect.top) + 'px';
            this.cursor.style.display = 'block';
        } else {
            this.cursor.style.display = 'none';
        }
    }
    
    updateStats() {
        const stats = this.getStats();
        
        // Update mini stats
        this.updateElement('#current-wpm', Math.round(stats.wmp));
        this.updateElement('#current-accuracy', Math.round(stats.accuracy));
        this.updateElement('#current-time', this.formatTime(stats.timeElapsed));
        
        // Update detailed stats
        this.updateElement('#detailed-wmp', Math.round(stats.wmp));
        this.updateElement('#detailed-accuracy', Math.round(stats.accuracy) + '%');
        this.updateElement('#error-count', stats.errors);
        this.updateElement('#error-rate', Math.round(stats.errorRate) + '% error rate');
        
        // Update trends
        this.updateTrend('#wmp-trend', stats.wmp, this.lastWPM);
        this.lastWPM = stats.wmp;
        
        // Update consistency
        this.wmpHistory.push(stats.wmp);
        if (this.wmpHistory.length > 10) {
            this.wmpHistory.shift();
        }
        
        const consistency = this.calculateConsistency();
        this.updateElement('#consistency-score', consistency.score + '%');
        this.updateElement('#consistency-detail', consistency.description);
    }
    
    updateProgress() {
        const progress = this.typedChars / this.textContent.length * 100;
        if (this.progressBar) {
            this.progressBar.style.width = Math.min(100, progress) + '%';
        }
        
        this.updateElement('#progress-chars', this.typedChars);
    }
    
    updateStatus(type, message, icon) {
        if (this.statusMessage) {
            this.statusMessage.className = `status-message ${type}`;
            this.statusMessage.innerHTML = `<i class="fas fa-${icon}"></i><span>${message}</span>`;
        }
    }
    
    getStats() {
        const timeElapsed = this.startTime ? (Date.now() - this.startTime) / 1000 : 0;
        const minutes = timeElapsed / 60;
        const wmp = minutes > 0 ? (this.correctChars / 5) / minutes : 0;
        const accuracy = this.typedChars > 0 ? (this.correctChars / this.typedChars) * 100 : 100;
        const errorRate = this.typedChars > 0 ? (this.errors / this.typedChars) * 100 : 0;
        
        return {
            wmp: Math.max(0, wmp),
            accuracy: Math.max(0, Math.min(100, accuracy)),
            timeElapsed: timeElapsed,
            errors: this.errors,
            errorRate: errorRate,
            correctChars: this.correctChars,
            totalChars: this.typedChars,
            textLength: this.textContent.length,
            progress: (this.typedChars / this.textContent.length) * 100
        };
    }
    
    calculateConsistency() {
        if (this.wmpHistory.length < 3) {
            return { score: 0, description: 'Calculating...' };
        }
        
        const avg = this.wmpHistory.reduce((a, b) => a + b, 0) / this.wmpHistory.length;
        const variance = this.wmpHistory.reduce((acc, val) => acc + Math.pow(val - avg, 2), 0) / this.wmpHistory.length;
        const standardDeviation = Math.sqrt(variance);
        
        const consistency = Math.max(0, 100 - (standardDeviation * 2));
        
        let description = 'Excellent';
        if (consistency < 60) description = 'Needs work';
        else if (consistency < 80) description = 'Good';
        else if (consistency < 90) description = 'Very good';
        
        return {
            score: Math.round(consistency),
            description: description
        };
    }
    
    updateElement(selector, value) {
        const element = this.container.querySelector(selector);
        if (element) {
            element.textContent = value;
        }
    }
    
    updateTrend(selector, current, previous) {
        const element = this.container.querySelector(selector);
        if (!element) return;
        
        const diff = current - previous;
        const icon = element.querySelector('i');
        const span = element.querySelector('span');
        
        if (diff > 0) {
            element.className = 'stat-trend up';
            icon.className = 'fas fa-arrow-up';
            span.textContent = '+' + Math.round(diff);
        } else if (diff < 0) {
            element.className = 'stat-trend down';
            icon.className = 'fas fa-arrow-down';
            span.textContent = Math.round(diff);
        } else {
            element.className = 'stat-trend neutral';
            icon.className = 'fas fa-minus';
            span.textContent = '0';
        }
    }
    
    formatTime(seconds) {
        const mins = Math.floor(seconds / 60);
        const secs = Math.floor(seconds % 60);
        return `${mins}:${secs.toString().padStart(2, '0')}`;
    }
    
    showCompletion(stats) {
        const completionPanel = this.container.querySelector('#completion-panel');
        if (!completionPanel) return;
        
        // Update final stats
        this.updateElement('#final-wmp', Math.round(stats.wmp));
        this.updateElement('#final-accuracy', Math.round(stats.accuracy) + '%');
        this.updateElement('#final-time', this.formatTime(stats.timeElapsed));
        
        // Show panel
        completionPanel.style.display = 'flex';
    }
    
    saveResult() {
        const stats = this.getStats();
        
        // Dispatch save event for parent components to handle
        this.dispatchEvent('typing:saveResult', {
            stats: stats,
            mode: this.mode,
            textId: this.container.dataset.textId,
            competitionId: this.container.dataset.competitionId
        });
    }
    
    dispatchEvent(eventName, data = {}) {
        const event = new CustomEvent(eventName, {
            detail: {
                container: this.container,
                controller: this,
                ...data
            },
            bubbles: true
        });
        
        this.container.dispatchEvent(event);
    }
}

// Initialize all typing areas on page load
document.addEventListener('DOMContentLoaded', function() {
    const typingAreas = document.querySelectorAll('.typing-area-container');
    
    typingAreas.forEach(container => {
        new TypingAreaController(container);
    });
});

// Global helper functions
window.TypingAreaController = TypingAreaController;

// Export for module usage
if (typeof module !== 'undefined' && module.exports) {
    module.exports = TypingAreaController;
}
</script>
