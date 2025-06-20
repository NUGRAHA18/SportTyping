@extends('layouts.app')

@section('content')
<div class="practice-test-container">
    <div class="container-fluid">
        <!-- Practice Header -->
        <div class="practice-header">
            <div class="header-left">
                <div class="back-navigation">
                    <a href="{{ route('practice.index') }}" class="btn btn-link">
                        <i class="fas fa-arrow-left"></i>
                        Back to Practice
                    </a>
                </div>
                <div class="text-info">
                    <h1 class="text-title">{{ $text->title }}</h1>
                    <div class="text-meta">
                        <span class="meta-item">
                            <i class="fas fa-tag"></i>
                            {{ $text->category->name }}
                        </span>
                        <span class="meta-item difficulty-{{ $text->difficulty_level }}">
                            <i class="fas fa-signal"></i>
                            {{ ucfirst($text->difficulty_level) }}
                        </span>
                        <span class="meta-item">
                            <i class="fas fa-font"></i>
                            {{ $text->word_count }} words
                        </span>
                    </div>
                </div>
            </div>
            <div class="header-right">
                <div class="practice-settings">
                    <button class="btn btn-outline-secondary" id="settings-btn" data-bs-toggle="modal" data-bs-target="#settingsModal">
                        <i class="fas fa-cog"></i>
                        Settings
                    </button>
                    <button class="btn btn-success" id="start-practice-btn">
                        <i class="fas fa-play"></i>
                        Start Practice
                    </button>
                </div>
            </div>
        </div>

        <!-- Practice Stats Bar -->
        <div class="stats-bar">
            <div class="stat-item">
                <div class="stat-icon">
                    <i class="fas fa-tachometer-alt"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-label">WPM</div>
                    <div class="stat-value" id="current-wpm">0</div>
                </div>
            </div>
            <div class="stat-item">
                <div class="stat-icon">
                    <i class="fas fa-bullseye"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-label">Accuracy</div>
                    <div class="stat-value" id="current-accuracy">100%</div>
                </div>
            </div>
            <div class="stat-item">
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-label">Time</div>
                    <div class="stat-value" id="current-time">00:00</div>
                </div>
            </div>
            <div class="stat-item">
                <div class="stat-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-label">Progress</div>
                    <div class="stat-value" id="current-progress">0%</div>
                </div>
            </div>
            <div class="stat-item">
                <div class="stat-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-label">Errors</div>
                    <div class="stat-value" id="current-errors">0</div>
                </div>
            </div>
        </div>

        <!-- Main Practice Area -->
        <div class="practice-area">
            <!-- Practice Instructions -->
            <div class="practice-instructions" id="practice-instructions">
                <div class="instructions-content">
                    <div class="instruction-icon">
                        <i class="fas fa-keyboard"></i>
                    </div>
                    <h3>Ready to Practice?</h3>
                    <p>Type the text below as accurately and quickly as possible. The timer will start when you begin typing.</p>
                    <div class="instruction-tips">
                        <div class="tip-item">
                            <i class="fas fa-lightbulb"></i>
                            <span>Focus on accuracy first, speed will come naturally</span>
                        </div>
                        <div class="tip-item">
                            <i class="fas fa-eye"></i>
                            <span>Look at the screen, not the keyboard</span>
                        </div>
                        <div class="tip-item">
                            <i class="fas fa-hand-point-up"></i>
                            <span>Use proper finger positioning</span>
                        </div>
                    </div>
                    <button class="btn btn-primary btn-lg" onclick="startPractice()">
                        <i class="fas fa-play"></i>
                        Start Typing
                    </button>
                </div>
            </div>

            <!-- Text Display Area -->
            <div class="text-display-area" id="text-display-area" style="display: none;">
                <div class="text-container">
                    <div class="text-content" id="text-content">
                        @foreach(str_split($text->content) as $index => $char)
                            <span class="char" data-index="{{ $index }}">{{ $char === ' ' ? '·' : $char }}</span>
                        @endforeach
                    </div>
                </div>
                
                <!-- Progress Bar -->
                <div class="progress-container">
                    <div class="progress-bar">
                        <div class="progress-fill" id="progress-fill"></div>
                    </div>
                    <div class="progress-text">
                        <span id="chars-typed">0</span> / <span id="total-chars">{{ strlen($text->content) }}</span> characters
                    </div>
                </div>
            </div>

            <!-- Typing Input Area -->
            <div class="typing-input-area" id="typing-input-area" style="display: none;">
                <div class="input-wrapper">
                    <textarea 
                        id="typing-input" 
                        class="typing-input" 
                        placeholder="Start typing here..."
                        rows="6"
                        disabled
                    ></textarea>
                    <div class="input-footer">
                        <div class="typing-mode">
                            <button class="mode-btn active" data-mode="normal">
                                <i class="fas fa-keyboard"></i>
                                Normal
                            </button>
                            <button class="mode-btn" data-mode="zen">
                                <i class="fas fa-eye-slash"></i>
                                Zen Mode
                            </button>
                        </div>
                        <div class="action-buttons">
                            <button class="btn btn-outline-warning" id="restart-btn" onclick="restartPractice()">
                                <i class="fas fa-redo"></i>
                                Restart
                            </button>
                            <button class="btn btn-outline-danger" id="stop-btn" onclick="stopPractice()">
                                <i class="fas fa-stop"></i>
                                Stop
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Virtual Keyboard -->
            <div class="virtual-keyboard" id="virtual-keyboard" style="display: none;">
                <div class="keyboard-container">
                    <div class="finger-guide" id="finger-guide">
                        <div class="finger-info">
                            <span class="finger-name">Left Pinky</span>
                            <span class="finger-keys">Q, A, Z</span>
                        </div>
                    </div>
                    
                    <div class="keyboard-layout">
                        <div class="keyboard-row">
                            <div class="key" data-key="`" data-finger="left-pinky">~<br>`</div>
                            <div class="key" data-key="1" data-finger="left-pinky">!<br>1</div>
                            <div class="key" data-key="2" data-finger="left-ring">@<br>2</div>
                            <div class="key" data-key="3" data-finger="left-middle">#<br>3</div>
                            <div class="key" data-key="4" data-finger="left-index">$<br>4</div>
                            <div class="key" data-key="5" data-finger="left-index">%<br>5</div>
                            <div class="key" data-key="6" data-finger="right-index">^<br>6</div>
                            <div class="key" data-key="7" data-finger="right-index">&<br>7</div>
                            <div class="key" data-key="8" data-finger="right-middle">*<br>8</div>
                            <div class="key" data-key="9" data-finger="right-ring">(<br>9</div>
                            <div class="key" data-key="0" data-finger="right-pinky">)<br>0</div>
                            <div class="key" data-key="-" data-finger="right-pinky">_<br>-</div>
                            <div class="key" data-key="=" data-finger="right-pinky">+<br>=</div>
                            <div class="key wide" data-key="Backspace">Backspace</div>
                        </div>
                        <div class="keyboard-row">
                            <div class="key wide" data-key="Tab">Tab</div>
                            <div class="key" data-key="q" data-finger="left-pinky">Q</div>
                            <div class="key" data-key="w" data-finger="left-ring">W</div>
                            <div class="key" data-key="e" data-finger="left-middle">E</div>
                            <div class="key" data-key="r" data-finger="left-index">R</div>
                            <div class="key" data-key="t" data-finger="left-index">T</div>
                            <div class="key" data-key="y" data-finger="right-index">Y</div>
                            <div class="key" data-key="u" data-finger="right-index">U</div>
                            <div class="key" data-key="i" data-finger="right-middle">I</div>
                            <div class="key" data-key="o" data-finger="right-ring">O</div>
                            <div class="key" data-key="p" data-finger="right-pinky">P</div>
                            <div class="key" data-key="[" data-finger="right-pinky">{<br>[</div>
                            <div class="key" data-key="]" data-finger="right-pinky">}<br>]</div>
                            <div class="key wide" data-key="\\" data-finger="right-pinky">|<br>\</div>
                        </div>
                        <div class="keyboard-row">
                            <div class="key extra-wide" data-key="CapsLock">Caps Lock</div>
                            <div class="key home-key" data-key="a" data-finger="left-pinky">A</div>
                            <div class="key home-key" data-key="s" data-finger="left-ring">S</div>
                            <div class="key home-key" data-key="d" data-finger="left-middle">D</div>
                            <div class="key home-key" data-key="f" data-finger="left-index">F</div>
                            <div class="key" data-key="g" data-finger="left-index">G</div>
                            <div class="key" data-key="h" data-finger="right-index">H</div>
                            <div class="key home-key" data-key="j" data-finger="right-index">J</div>
                            <div class="key home-key" data-key="k" data-finger="right-middle">K</div>
                            <div class="key home-key" data-key="l" data-finger="right-ring">L</div>
                            <div class="key home-key" data-key=";" data-finger="right-pinky">:<br>;</div>
                            <div class="key" data-key="'" data-finger="right-pinky">"<br>'</div>
                            <div class="key extra-wide" data-key="Enter">Enter</div>
                        </div>
                        <div class="keyboard-row">
                            <div class="key extra-wide" data-key="Shift">Shift</div>
                            <div class="key" data-key="z" data-finger="left-pinky">Z</div>
                            <div class="key" data-key="x" data-finger="left-ring">X</div>
                            <div class="key" data-key="c" data-finger="left-middle">C</div>
                            <div class="key" data-key="v" data-finger="left-index">V</div>
                            <div class="key" data-key="b" data-finger="left-index">B</div>
                            <div class="key" data-key="n" data-finger="right-index">N</div>
                            <div class="key" data-key="m" data-finger="right-index">M</div>
                            <div class="key" data-key="," data-finger="right-middle">&lt;<br>,</div>
                            <div class="key" data-key="." data-finger="right-ring">&gt;<br>.</div>
                            <div class="key" data-key="/" data-finger="right-pinky">?<br>/</div>
                            <div class="key extra-wide" data-key="Shift">Shift</div>
                        </div>
                        <div class="keyboard-row">
                            <div class="key" data-key="Ctrl">Ctrl</div>
                            <div class="key" data-key="Alt">Alt</div>
                            <div class="key space-bar" data-key=" " data-finger="thumb">Space</div>
                            <div class="key" data-key="Alt">Alt</div>
                            <div class="key" data-key="Ctrl">Ctrl</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- WPM Chart -->
        <div class="wpm-chart-container" id="wpm-chart-container" style="display: none;">
            <div class="chart-header">
                <h3>
                    <i class="fas fa-chart-line"></i>
                    Real-time WPM
                </h3>
            </div>
            <div class="chart-area">
                <canvas id="wpm-chart" width="800" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Settings Modal -->
<div class="modal fade" id="settingsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-cog"></i>
                    Practice Settings
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="settings-group">
                    <label class="setting-label">
                        <i class="fas fa-keyboard"></i>
                        Virtual Keyboard
                    </label>
                    <div class="setting-control">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="show-keyboard" checked>
                            <label class="form-check-label" for="show-keyboard">
                                Show virtual keyboard
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="settings-group">
                    <label class="setting-label">
                        <i class="fas fa-chart-line"></i>
                        Real-time Chart
                    </label>
                    <div class="setting-control">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="show-chart">
                            <label class="form-check-label" for="show-chart">
                                Show WPM chart
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="settings-group">
                    <label class="setting-label">
                        <i class="fas fa-volume-up"></i>
                        Sound Effects
                    </label>
                    <div class="setting-control">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="enable-sounds">
                            <label class="form-check-label" for="enable-sounds">
                                Enable typing sounds
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="settings-group">
                    <label class="setting-label">
                        <i class="fas fa-eye"></i>
                        Focus Mode
                    </label>
                    <div class="setting-control">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="focus-mode">
                            <label class="form-check-label" for="focus-mode">
                                Hide unnecessary elements
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="settings-group">
                    <label class="setting-label">
                        <i class="fas fa-font"></i>
                        Font Size
                    </label>
                    <div class="setting-control">
                        <input type="range" class="form-range" id="font-size" min="14" max="24" value="18">
                        <div class="range-labels">
                            <span>Small</span>
                            <span>Large</span>
                        </div>
                    </div>
                </div>
                
                <div class="settings-group">
                    <label class="setting-label">
                        <i class="fas fa-palette"></i>
                        Color Theme
                    </label>
                    <div class="setting-control">
                        <select class="form-select" id="color-theme">
                            <option value="default">Default</option>
                            <option value="dark">Dark Mode</option>
                            <option value="high-contrast">High Contrast</option>
                            <option value="colorful">Colorful</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="applySettings()">Apply Settings</button>
            </div>
        </div>
    </div>
</div>

<!-- Results Modal -->
<div class="modal fade" id="resultsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-trophy"></i>
                    Practice Results
                </h5>
            </div>
            <div class="modal-body">
                <div class="results-summary">
                    <div class="result-card">
                        <div class="result-icon">
                            <i class="fas fa-tachometer-alt"></i>
                        </div>
                        <div class="result-content">
                            <div class="result-value" id="final-wpm">0</div>
                            <div class="result-label">Words Per Minute</div>
                            <div class="result-change" id="wpm-change">No previous data</div>
                        </div>
                    </div>
                    
                    <div class="result-card">
                        <div class="result-icon">
                            <i class="fas fa-bullseye"></i>
                        </div>
                        <div class="result-content">
                            <div class="result-value" id="final-accuracy">0%</div>
                            <div class="result-label">Accuracy</div>
                            <div class="result-change" id="accuracy-change">No previous data</div>
                        </div>
                    </div>
                    
                    <div class="result-card">
                        <div class="result-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="result-content">
                            <div class="result-value" id="final-time">00:00</div>
                            <div class="result-label">Time Taken</div>
                            <div class="result-change" id="time-info">Practice session</div>
                        </div>
                    </div>
                    
                    <div class="result-card">
                        <div class="result-icon">
                            <i class="fas fa-star"></i>
                        </div>
                        <div class="result-content">
                            <div class="result-value" id="exp-gained">0</div>
                            <div class="result-label">EXP Gained</div>
                            <div class="result-change">Practice reward</div>
                        </div>
                    </div>
                </div>
                
                <div class="detailed-stats">
                    <h4>Detailed Statistics</h4>
                    <div class="stats-table">
                        <div class="stat-row">
                            <span class="stat-name">Characters Typed:</span>
                            <span class="stat-value" id="chars-typed-final">0</span>
                        </div>
                        <div class="stat-row">
                            <span class="stat-name">Correct Characters:</span>
                            <span class="stat-value" id="correct-chars">0</span>
                        </div>
                        <div class="stat-row">
                            <span class="stat-name">Incorrect Characters:</span>
                            <span class="stat-value" id="incorrect-chars">0</span>
                        </div>
                        <div class="stat-row">
                            <span class="stat-name">Words Typed:</span>
                            <span class="stat-value" id="words-typed">0</span>
                        </div>
                        <div class="stat-row">
                            <span class="stat-name">Backspace Used:</span>
                            <span class="stat-value" id="backspace-count">0</span>
                        </div>
                    </div>
                </div>
                
                <div class="achievement-section" id="achievement-section" style="display: none;">
                    <h4>Achievements Unlocked!</h4>
                    <div class="achievement-list" id="achievement-list">
                        <!-- Achievements will be populated here -->
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="restartPractice()">
                    <i class="fas fa-redo"></i>
                    Practice Again
                </button>
                <a href="{{ route('practice.index') }}" class="btn btn-outline-primary">
                    <i class="fas fa-list"></i>
                    Choose Another Text
                </a>
                <button type="button" class="btn btn-primary" onclick="savePracticeResult()">
                    <i class="fas fa-save"></i>
                    Save & Continue
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.practice-test-container {
    background: var(--bg-primary);
    min-height: 100vh;
    padding: 1rem 0;
}

/* Practice Header */
.practice-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: var(--border-radius-lg);
    padding: 1.5rem 2rem;
    margin-bottom: 1.5rem;
}

.back-navigation .btn {
    color: var(--text-secondary);
    padding: 0.5rem 0;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.back-navigation .btn:hover {
    color: var(--accent-primary);
}

.text-title {
    font-family: var(--font-display);
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 0.5rem;
}

.text-meta {
    display: flex;
    gap: 1.5rem;
    flex-wrap: wrap;
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

.meta-item.difficulty-beginner { color: var(--accent-success); }
.meta-item.difficulty-intermediate { color: var(--accent-warning); }
.meta-item.difficulty-advanced { color: var(--accent-danger); }

.practice-settings {
    display: flex;
    gap: 1rem;
}

/* Stats Bar */
.stats-bar {
    display: flex;
    justify-content: space-around;
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: var(--border-radius-lg);
    padding: 1.5rem;
    margin-bottom: 2rem;
}

.stats-bar .stat-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: var(--bg-secondary);
    border-radius: var(--border-radius);
    min-width: 120px;
}

.stats-bar .stat-icon {
    width: 40px;
    height: 40px;
    background: var(--champion-gradient);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1rem;
}

.stats-bar .stat-label {
    font-size: 0.8rem;
    color: var(--text-secondary);
    margin-bottom: 0.25rem;
}

.stats-bar .stat-value {
    font-family: var(--font-display);
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--text-primary);
}

/* Practice Area */
.practice-area {
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: var(--border-radius-lg);
    padding: 2rem;
    margin-bottom: 2rem;
}

/* Practice Instructions */
.practice-instructions {
    text-align: center;
    padding: 3rem 2rem;
}

.instruction-icon {
    font-size: 4rem;
    color: var(--accent-primary);
    margin-bottom: 1.5rem;
}

.practice-instructions h3 {
    font-family: var(--font-display);
    font-size: 1.75rem;
    color: var(--text-primary);
    margin-bottom: 1rem;
}

.practice-instructions p {
    color: var(--text-secondary);
    font-size: 1.1rem;
    margin-bottom: 2rem;
}

.instruction-tips {
    display: flex;
    justify-content: center;
    gap: 2rem;
    margin-bottom: 2rem;
    flex-wrap: wrap;
}

.tip-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--text-secondary);
    font-size: 0.9rem;
    max-width: 200px;
}

.tip-item i {
    color: var(--accent-warning);
}

/* Text Display Area */
.text-display-area {
    margin-bottom: 2rem;
}

.text-container {
    background: var(--bg-secondary);
    border: 2px solid var(--border-light);
    border-radius: var(--border-radius);
    padding: 2rem;
    margin-bottom: 1rem;
}

.text-content {
    font-family: var(--font-mono);
    font-size: 1.2rem;
    line-height: 1.8;
    letter-spacing: 0.5px;
    color: var(--text-primary);
    user-select: none;
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
    background: rgba(239, 68, 68, 0.3);
    color: var(--accent-danger);
}

.char.current {
    background: var(--accent-primary);
    color: white;
    animation: pulse-cursor 1s infinite;
}

@keyframes pulse-cursor {
    0%, 50% { opacity: 1; }
    51%, 100% { opacity: 0.7; }
}

/* Progress Container */
.progress-container {
    margin-bottom: 1rem;
}

.progress-bar {
    width: 100%;
    height: 8px;
    background: var(--border-light);
    border-radius: 4px;
    overflow: hidden;
    margin-bottom: 0.5rem;
}

.progress-fill {
    height: 100%;
    background: var(--champion-gradient);
    transition: width 0.3s ease;
    width: 0%;
}

.progress-text {
    text-align: center;
    color: var(--text-secondary);
    font-size: 0.9rem;
}

/* Typing Input Area */
.typing-input-area {
    margin-bottom: 2rem;
}

.input-wrapper {
    position: relative;
}

.typing-input {
    width: 100%;
    background: var(--bg-primary);
    border: 2px solid var(--border-light);
    border-radius: var(--border-radius);
    padding: 1.5rem;
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

.input-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
    background: var(--bg-secondary);
    border: 1px solid var(--border-light);
    border-top: none;
    border-radius: 0 0 var(--border-radius) var(--border-radius);
}

.typing-mode {
    display: flex;
    gap: 0.5rem;
}

.mode-btn {
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: var(--border-radius);
    padding: 0.5rem 1rem;
    color: var(--text-secondary);
    font-size: 0.9rem;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.mode-btn.active,
.mode-btn:hover {
    background: var(--accent-primary);
    color: white;
    border-color: var(--accent-primary);
}

.action-buttons {
    display: flex;
    gap: 0.5rem;
}

/* Virtual Keyboard */
.virtual-keyboard {
    margin-bottom: 2rem;
}

.keyboard-container {
    background: var(--bg-secondary);
    border: 1px solid var(--border-light);
    border-radius: var(--border-radius);
    padding: 1.5rem;
}

.finger-guide {
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: var(--border-radius);
    padding: 1rem;
    margin-bottom: 1rem;
    text-align: center;
}

.finger-info {
    display: flex;
    justify-content: center;
    gap: 2rem;
    align-items: center;
}

.finger-name {
    font-weight: 600;
    color: var(--text-primary);
}

.finger-keys {
    color: var(--text-secondary);
    font-family: var(--font-mono);
}

.keyboard-layout {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    align-items: center;
}

.keyboard-row {
    display: flex;
    justify-content: center;
    gap: 4px;
}

.key {
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: 4px;
    padding: 0.5rem;
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
    position: relative;
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
    animation: pulse-key 1s infinite;
}

.key.home-key {
    background: rgba(59, 130, 246, 0.1);
    border-color: var(--accent-primary);
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

@keyframes pulse-key {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.7; }
}

/* WPM Chart */
.wpm-chart-container {
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: var(--border-radius-lg);
    padding: 2rem;
    margin-bottom: 2rem;
}

.chart-header {
    margin-bottom: 1rem;
}

.chart-header h3 {
    font-family: var(--font-display);
    color: var(--text-primary);
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.chart-header i {
    color: var(--accent-primary);
}

.chart-area {
    position: relative;
    height: 200px;
}

/* Modal Styles */
.modal-content {
    border: none;
    border-radius: var(--border-radius-lg);
}

.modal-header {
    background: var(--champion-gradient);
    color: white;
    border-radius: var(--border-radius-lg) var(--border-radius-lg) 0 0;
}

.settings-group {
    margin-bottom: 1.5rem;
    padding-bottom: 1.5rem;
    border-bottom: 1px solid var(--border-light);
}

.settings-group:last-child {
    border-bottom: none;
    margin-bottom: 0;
}

.setting-label {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 0.75rem;
}

.setting-label i {
    color: var(--accent-primary);
}

.range-labels {
    display: flex;
    justify-content: space-between;
    font-size: 0.8rem;
    color: var(--text-secondary);
    margin-top: 0.5rem;
}

/* Results Modal */
.results-summary {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.result-card {
    background: var(--bg-secondary);
    border: 1px solid var(--border-light);
    border-radius: var(--border-radius);
    padding: 1.5rem;
    text-align: center;
}

.result-icon {
    width: 48px;
    height: 48px;
    background: var(--champion-gradient);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2rem;
    margin: 0 auto 1rem;
}

.result-value {
    font-family: var(--font-display);
    font-size: 2rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 0.5rem;
}

.result-label {
    color: var(--text-secondary);
    font-size: 0.9rem;
    margin-bottom: 0.5rem;
}

.result-change {
    font-size: 0.8rem;
    padding: 0.25rem 0.5rem;
    border-radius: var(--border-radius);
}

.result-change.positive {
    background: rgba(34, 197, 94, 0.1);
    color: var(--accent-success);
}

.result-change.negative {
    background: rgba(239, 68, 68, 0.1);
    color: var(--accent-danger);
}

.detailed-stats h4 {
    color: var(--text-primary);
    margin-bottom: 1rem;
}

.stats-table {
    background: var(--bg-secondary);
    border-radius: var(--border-radius);
    padding: 1rem;
}

.stat-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem 0;
    border-bottom: 1px solid var(--border-light);
}

.stat-row:last-child {
    border-bottom: none;
}

.stat-name {
    color: var(--text-secondary);
}

.stat-value {
    font-weight: 600;
    color: var(--text-primary);
}

.achievement-section {
    margin-top: 2rem;
}

.achievement-section h4 {
    color: var(--text-primary);
    margin-bottom: 1rem;
}

.achievement-list {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
}

.achievement-badge {
    background: var(--medal-gradient);
    color: white;
    padding: 0.75rem 1rem;
    border-radius: var(--border-radius);
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.9rem;
    font-weight: 500;
    animation: slideInUp 0.5s ease;
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
    .practice-header {
        flex-direction: column;
        gap: 1rem;
    }
    
    .stats-bar {
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
    }
    
    .results-summary {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .practice-test-container {
        padding: 0.5rem 0;
    }
    
    .practice-header,
    .practice-area {
        padding: 1rem;
    }
    
    .stats-bar {
        flex-direction: column;
        gap: 1rem;
    }
    
    .instruction-tips {
        flex-direction: column;
        gap: 1rem;
    }
    
    .text-content {
        font-size: 1rem;
    }
    
    .keyboard-layout {
        transform: scale(0.8);
    }
    
    .input-footer {
        flex-direction: column;
        gap: 1rem;
    }
}

/* Zen Mode Styles */
.zen-mode .stats-bar,
.zen-mode .virtual-keyboard,
.zen-mode .wpm-chart-container {
    display: none !important;
}

.zen-mode .text-content {
    font-size: 1.5rem;
    line-height: 2;
    text-align: center;
}

/* Focus Mode Styles */
.focus-mode .practice-header {
    display: none;
}

.focus-mode .stats-bar .stat-item:not(:nth-child(1)):not(:nth-child(2)) {
    display: none;
}
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>

<script>
class TypingPractice {
    constructor() {
        this.practiceText = @json($text->content);
        this.currentIndex = 0;
        this.startTime = null;
        this.endTime = null;
        this.isStarted = false;
        this.isFinished = false;
        this.errors = 0;
        this.backspaceCount = 0;
        this.wpmData = [];
        this.chart = null;
        this.settings = {
            showKeyboard: true,
            showChart: false,
            enableSounds: false,
            focusMode: false,
            fontSize: 18,
            colorTheme: 'default'
        };
        
        this.initializeElements();
        this.loadSettings();
    }
    
    initializeElements() {
        this.typingInput = document.getElementById('typing-input');
        this.textContent = document.getElementById('text-content');
        this.progressFill = document.getElementById('progress-fill');
        this.charsTyped = document.getElementById('chars-typed');
        this.totalChars = document.getElementById('total-chars');
        
        // Stat elements
        this.wpmElement = document.getElementById('current-wpm');
        this.accuracyElement = document.getElementById('current-accuracy');
        this.timeElement = document.getElementById('current-time');
        this.progressElement = document.getElementById('current-progress');
        this.errorsElement = document.getElementById('current-errors');
        
        // Container elements
        this.instructionsElement = document.getElementById('practice-instructions');
        this.textDisplayArea = document.getElementById('text-display-area');
        this.typingInputArea = document.getElementById('typing-input-area');
        this.virtualKeyboard = document.getElementById('virtual-keyboard');
        this.wpmChartContainer = document.getElementById('wpm-chart-container');
        
        // Bind events
        this.typingInput.addEventListener('input', (e) => this.handleInput(e));
        this.typingInput.addEventListener('keydown', (e) => this.handleKeyDown(e));
        
        // Mode buttons
        document.querySelectorAll('.mode-btn').forEach(btn => {
            btn.addEventListener('click', (e) => this.switchMode(e.target.dataset.mode));
        });
        
        // Prevent leaving during practice
        window.addEventListener('beforeunload', (e) => {
            if (this.isStarted && !this.isFinished) {
                e.preventDefault();
                e.returnValue = 'Are you sure you want to leave? Your progress will be lost.';
            }
        });
    }
    
    loadSettings() {
        const saved = localStorage.getItem('typing-practice-settings');
        if (saved) {
            this.settings = { ...this.settings, ...JSON.parse(saved) };
            this.applySettings();
        }
    }
    
    saveSettings() {
        localStorage.setItem('typing-practice-settings', JSON.stringify(this.settings));
    }
    
    applySettings() {
        // Virtual keyboard
        this.virtualKeyboard.style.display = this.settings.showKeyboard ? 'block' : 'none';
        
        // Chart
        this.wpmChartContainer.style.display = this.settings.showChart ? 'block' : 'none';
        
        // Focus mode
        document.body.classList.toggle('focus-mode', this.settings.focusMode);
        
        // Font size
        this.textContent.style.fontSize = this.settings.fontSize + 'px';
        this.typingInput.style.fontSize = this.settings.fontSize + 'px';
        
        // Update form controls
        document.getElementById('show-keyboard').checked = this.settings.showKeyboard;
        document.getElementById('show-chart').checked = this.settings.showChart;
        document.getElementById('enable-sounds').checked = this.settings.enableSounds;
        document.getElementById('focus-mode').checked = this.settings.focusMode;
        document.getElementById('font-size').value = this.settings.fontSize;
        document.getElementById('color-theme').value = this.settings.colorTheme;
    }
    
    startPractice() {
        this.isStarted = true;
        this.startTime = Date.now();
        
        // Hide instructions, show practice area
        this.instructionsElement.style.display = 'none';
        this.textDisplayArea.style.display = 'block';
        this.typingInputArea.style.display = 'block';
        
        if (this.settings.showKeyboard) {
            this.virtualKeyboard.style.display = 'block';
        }
        
        if (this.settings.showChart) {
            this.wpmChartContainer.style.display = 'block';
            this.initializeChart();
        }
        
        // Enable input and focus
        this.typingInput.disabled = false;
        this.typingInput.focus();
        
        // Start timer
        this.startTimer();
        
        // Highlight first character
        this.updateCharacterHighlight();
    }
    
    startTimer() {
        this.timerInterval = setInterval(() => {
            if (!this.isStarted || this.isFinished) return;
            
            const elapsed = (Date.now() - this.startTime) / 1000;
            const minutes = Math.floor(elapsed / 60);
            const seconds = Math.floor(elapsed % 60);
            
            this.timeElement.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            
            // Update WPM chart every 2 seconds
            if (elapsed > 0 && elapsed % 2 === 0) {
                this.updateWPMChart();
            }
        }, 1000);
    }
    
    handleInput(e) {
        if (!this.isStarted || this.isFinished) return;
        
        const inputText = e.target.value;
        this.currentIndex = inputText.length;
        
        // Update character highlighting
        this.updateCharacterHighlight();
        
        // Update statistics
        this.updateStats();
        
        // Check if completed
        if (this.currentIndex >= this.practiceText.length) {
            this.endPractice('completed');
        }
    }
    
    handleKeyDown(e) {
        if (!this.isStarted || this.isFinished) return;
        
        // Track backspace usage
        if (e.key === 'Backspace') {
            this.backspaceCount++;
        }
        
        // Highlight keyboard key
        this.highlightKey(e.key);
        
        // Handle special keys
        if (e.key === 'Tab') {
            e.preventDefault();
        }
        
        // Play sound if enabled
        if (this.settings.enableSounds) {
            this.playTypingSound();
        }
    }
    
    updateCharacterHighlight() {
        const chars = document.querySelectorAll('.char');
        const inputText = this.typingInput.value;
        
        chars.forEach((char, index) => {
            char.classList.remove('correct', 'incorrect', 'current');
            
            if (index < inputText.length) {
                if (inputText[index] === this.practiceText[index]) {
                    char.classList.add('correct');
                } else {
                    char.classList.add('incorrect');
                }
            } else if (index === inputText.length) {
                char.classList.add('current');
                
                // Scroll into view if needed
                char.scrollIntoView({ behavior: 'smooth', block: 'center' });
                
                // Highlight next key on virtual keyboard
                const nextChar = this.practiceText[index];
                this.highlightNextKey(nextChar);
                
                // Update finger guide
                this.updateFingerGuide(nextChar);
            }
        });
    }
    
    highlightKey(key) {
        // Remove previous highlights
        document.querySelectorAll('.key').forEach(k => k.classList.remove('active'));
        
        // Highlight current key
        const keyElement = document.querySelector(`[data-key="${key.toLowerCase()}"]`);
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
    
    updateFingerGuide(char) {
        const fingerGuide = document.getElementById('finger-guide');
        const keyElement = document.querySelector(`[data-key="${char.toLowerCase()}"]`);
        
        if (keyElement && fingerGuide) {
            const finger = keyElement.dataset.finger;
            const fingerNames = {
                'left-pinky': 'Left Pinky',
                'left-ring': 'Left Ring',
                'left-middle': 'Left Middle',
                'left-index': 'Left Index',
                'right-index': 'Right Index',
                'right-middle': 'Right Middle',
                'right-ring': 'Right Ring',
                'right-pinky': 'Right Pinky',
                'thumb': 'Thumb'
            };
            
            fingerGuide.querySelector('.finger-name').textContent = fingerNames[finger] || 'Unknown';
            fingerGuide.querySelector('.finger-keys').textContent = `Next: ${char}`;
        }
    }
    
    updateStats() {
        const inputText = this.typingInput.value;
        const timeElapsed = (Date.now() - this.startTime) / 1000 / 60; // minutes
        
        // Calculate WPM
        const wordsTyped = inputText.trim().split(/\s+/).length;
        const wpm = timeElapsed > 0 ? Math.round(wordsTyped / timeElapsed) : 0;
        
        // Calculate accuracy
        let correctChars = 0;
        for (let i = 0; i < inputText.length; i++) {
            if (inputText[i] === this.practiceText[i]) {
                correctChars++;
            }
        }
        const accuracy = inputText.length > 0 ? Math.round((correctChars / inputText.length) * 100) : 100;
        
        // Calculate progress
        const progress = Math.round((inputText.length / this.practiceText.length) * 100);
        
        // Calculate errors
        this.errors = inputText.length - correctChars;
        
        // Update displays
        this.wpmElement.textContent = wpm;
        this.accuracyElement.textContent = accuracy + '%';
        this.progressElement.textContent = progress + '%';
        this.errorsElement.textContent = this.errors;
        this.charsTyped.textContent = inputText.length;
        
        // Update progress bar
        this.progressFill.style.width = progress + '%';
    }
    
    initializeChart() {
        const ctx = document.getElementById('wpm-chart');
        if (!ctx) return;
        
        this.chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: 'WPM',
                    data: [],
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.1,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Words Per Minute'
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
                        display: false
                    }
                }
            }
        });
    }
    
    updateWPMChart() {
        if (!this.chart) return;
        
        const timeElapsed = (Date.now() - this.startTime) / 1000;
        const inputText = this.typingInput.value;
        const wordsTyped = inputText.trim().split(/\s+/).length;
        const wpm = timeElapsed > 0 ? Math.round(wordsTyped / (timeElapsed / 60)) : 0;
        
        this.chart.data.labels.push(Math.round(timeElapsed));
        this.chart.data.datasets[0].data.push(wpm);
        
        // Keep only last 30 data points
        if (this.chart.data.labels.length > 30) {
            this.chart.data.labels.shift();
            this.chart.data.datasets[0].data.shift();
        }
        
        this.chart.update('none');
    }
    
    switchMode(mode) {
        // Update active mode button
        document.querySelectorAll('.mode-btn').forEach(btn => btn.classList.remove('active'));
        document.querySelector(`[data-mode="${mode}"]`).classList.add('active');
        
        // Apply mode
        if (mode === 'zen') {
            document.body.classList.add('zen-mode');
        } else {
            document.body.classList.remove('zen-mode');
        }
    }
    
    playTypingSound() {
        // Simple beep sound using Web Audio API
        const audioContext = new (window.AudioContext || window.webkitAudioContext)();
        const oscillator = audioContext.createOscillator();
        const gainNode = audioContext.createGain();
        
        oscillator.connect(gainNode);
        gainNode.connect(audioContext.destination);
        
        oscillator.frequency.value = 800;
        gainNode.gain.setValueAtTime(0.1, audioContext.currentTime);
        gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.1);
        
        oscillator.start(audioContext.currentTime);
        oscillator.stop(audioContext.currentTime + 0.1);
    }
    
    endPractice(reason) {
        this.isFinished = true;
        this.endTime = Date.now();
        
        // Stop timer
        clearInterval(this.timerInterval);
        
        // Disable input
        this.typingInput.disabled = true;
        
        // Calculate final results
        this.calculateFinalResults(reason);
    }
    
    calculateFinalResults(reason) {
        const inputText = this.typingInput.value;
        const timeElapsed = (this.endTime - this.startTime) / 1000 / 60; // minutes
        
        // Final calculations
        const wordsTyped = inputText.trim().split(/\s+/).length;
        const finalWpm = timeElapsed > 0 ? Math.round(wordsTyped / timeElapsed) : 0;
        
        let correctChars = 0;
        for (let i = 0; i < inputText.length; i++) {
            if (inputText[i] === this.practiceText[i]) {
                correctChars++;
            }
        }
        const finalAccuracy = inputText.length > 0 ? Math.round((correctChars / inputText.length) * 100) : 0;
        
        const timeMinutes = Math.floor(timeElapsed);
        const timeSeconds = Math.floor((timeElapsed % 1) * 60);
        const finalTime = `${timeMinutes.toString().padStart(2, '0')}:${timeSeconds.toString().padStart(2, '0')}`;
        
        // Calculate EXP gained (simplified)
        const baseExp = 10;
        const wpmBonus = Math.floor(finalWpm / 10) * 5;
        const accuracyBonus = Math.floor(finalAccuracy / 10) * 2;
        const expGained = baseExp + wpmBonus + accuracyBonus;
        
        // Show results modal
        this.showResults(finalWpm, finalAccuracy, finalTime, expGained, inputText.length, correctChars);
    }
    
    showResults(wpm, accuracy, time, exp, charsTyped, correctChars) {
        // Update result values
        document.getElementById('final-wpm').textContent = wpm;
        document.getElementById('final-accuracy').textContent = accuracy + '%';
        document.getElementById('final-time').textContent = time;
        document.getElementById('exp-gained').textContent = exp;
        
        // Update detailed stats
        document.getElementById('chars-typed-final').textContent = charsTyped;
        document.getElementById('correct-chars').textContent = correctChars;
        document.getElementById('incorrect-chars').textContent = this.errors;
        document.getElementById('words-typed').textContent = Math.floor(charsTyped / 5);
        document.getElementById('backspace-count').textContent = this.backspaceCount;
        
        // Check for achievements
        this.checkAchievements(wpm, accuracy);
        
        // Show modal
        const modal = new bootstrap.Modal(document.getElementById('resultsModal'));
        modal.show();
    }
    
    checkAchievements(wpm, accuracy) {
        const achievements = [];
        
        if (wpm >= 40) achievements.push({ icon: 'fas fa-tachometer-alt', text: 'Speed Racer: 40+ WPM' });
        if (wpm >= 60) achievements.push({ icon: 'fas fa-rocket', text: 'Speed Demon: 60+ WPM' });
        if (wpm >= 80) achievements.push({ icon: 'fas fa-fire', text: 'Lightning Fingers: 80+ WPM' });
        
        if (accuracy >= 95) achievements.push({ icon: 'fas fa-bullseye', text: 'Precision Master: 95%+ Accuracy' });
        if (accuracy === 100) achievements.push({ icon: 'fas fa-crown', text: 'Perfect Score: 100% Accuracy' });
        
        if (this.errors === 0) achievements.push({ icon: 'fas fa-medal', text: 'Flawless: No Errors' });
        if (this.backspaceCount === 0) achievements.push({ icon: 'fas fa-star', text: 'No Takebacks: No Backspace Used' });
        
        // Display achievements
        const achievementSection = document.getElementById('achievement-section');
        const achievementList = document.getElementById('achievement-list');
        
        if (achievements.length > 0) {
            achievementSection.style.display = 'block';
            achievementList.innerHTML = '';
            
            achievements.forEach((achievement, index) => {
                setTimeout(() => {
                    const badge = document.createElement('div');
                    badge.className = 'achievement-badge';
                    badge.innerHTML = `
                        <i class="${achievement.icon}"></i>
                        <span>${achievement.text}</span>
                    `;
                    achievementList.appendChild(badge);
                }, index * 300);
            });
        }
    }
    
    restartPractice() {
        // Reset all variables
        this.currentIndex = 0;
        this.startTime = null;
        this.endTime = null;
        this.isStarted = false;
        this.isFinished = false;
        this.errors = 0;
        this.backspaceCount = 0;
        this.wpmData = [];
        
        // Clear timer
        if (this.timerInterval) {
            clearInterval(this.timerInterval);
        }
        
        // Reset UI
        this.typingInput.value = '';
        this.typingInput.disabled = true;
        this.progressFill.style.width = '0%';
        this.charsTyped.textContent = '0';
        
        // Reset stats
        this.wpmElement.textContent = '0';
        this.accuracyElement.textContent = '100%';
        this.timeElement.textContent = '00:00';
        this.progressElement.textContent = '0%';
        this.errorsElement.textContent = '0';
        
        // Reset character highlighting
        document.querySelectorAll('.char').forEach(char => {
            char.classList.remove('correct', 'incorrect', 'current');
        });
        
        // Show instructions
        this.instructionsElement.style.display = 'block';
        this.textDisplayArea.style.display = 'none';
        this.typingInputArea.style.display = 'none';
        this.virtualKeyboard.style.display = 'none';
        this.wpmChartContainer.style.display = 'none';
        
        // Clear chart
        if (this.chart) {
            this.chart.destroy();
            this.chart = null;
        }
        
        // Close modal if open
        const modal = bootstrap.Modal.getInstance(document.getElementById('resultsModal'));
        if (modal) {
            modal.hide();
        }
    }
    
    stopPractice() {
        if (this.isStarted && !this.isFinished) {
            this.endPractice('stopped');
        }
    }
}

// Global functions
function startPractice() {
    if (window.typingPractice) {
        window.typingPractice.startPractice();
    }
}

function restartPractice() {
    if (window.typingPractice) {
        window.typingPractice.restartPractice();
    }
}

function stopPractice() {
    if (window.typingPractice) {
        window.typingPractice.stopPractice();
    }
}

function applySettings() {
    if (!window.typingPractice) return;
    
    // Get settings from form
    const settings = {
        showKeyboard: document.getElementById('show-keyboard').checked,
        showChart: document.getElementById('show-chart').checked,
        enableSounds: document.getElementById('enable-sounds').checked,
        focusMode: document.getElementById('focus-mode').checked,
        fontSize: parseInt(document.getElementById('font-size').value),
        colorTheme: document.getElementById('color-theme').value
    };
    
    // Apply and save settings
    window.typingPractice.settings = { ...window.typingPractice.settings, ...settings };
    window.typingPractice.applySettings();
    window.typingPractice.saveSettings();
    
    // Close modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('settingsModal'));
    if (modal) {
        modal.hide();
    }
}

function savePracticeResult() {
    // In a real application, this would save the result to the server
    alert('Practice result saved!');
    
    // Close modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('resultsModal'));
    if (modal) {
        modal.hide();
    }
}

// Initialize when page loads
document.addEventListener('DOMContentLoaded', function() {
    window.typingPractice = new TypingPractice();
});
</script>
@endsection