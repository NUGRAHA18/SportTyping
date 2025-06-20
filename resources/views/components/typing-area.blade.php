{{-- resources/views/components/typing-area.blade.php --}}
@props([
    'text' => '',
    'placeholder' => 'Start typing here...',
    'disabled' => true,
    'showStats' => true,
    'showKeyboard' => true,
    'mode' => 'practice', // practice, competition, lesson
    'timer' => null
])

<div class="typing-area-component" data-mode="{{ $mode }}">
    <!-- Typing Stats Bar -->
    @if($showStats)
    <div class="typing-stats-bar">
        <div class="stat-item">
            <div class="stat-icon">
                <i class="fas fa-tachometer-alt"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value" data-stat="wpm">0</div>
                <div class="stat-label">WPM</div>
            </div>
        </div>
        
        <div class="stat-item">
            <div class="stat-icon">
                <i class="fas fa-bullseye"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value" data-stat="accuracy">100%</div>
                <div class="stat-label">Accuracy</div>
            </div>
        </div>
        
        @if($timer)
        <div class="stat-item">
            <div class="stat-icon">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value" data-stat="timer">{{ $timer }}</div>
                <div class="stat-label">Time</div>
            </div>
        </div>
        @endif
        
        <div class="stat-item">
            <div class="stat-icon">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value" data-stat="progress">0%</div>
                <div class="stat-label">Progress</div>
            </div>
        </div>
        
        <div class="stat-item">
            <div class="stat-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value" data-stat="errors">0</div>
                <div class="stat-label">Errors</div>
            </div>
        </div>
    </div>
    @endif

    <!-- Text Display Area -->
    <div class="text-display-container">
        <div class="text-content" data-text="{{ $text }}">
            @if($text)
                @foreach(str_split($text) as $index => $char)
                    <span class="char" data-index="{{ $index }}">{{ $char === ' ' ? '·' : $char }}</span>
                @endforeach
            @else
                <span class="placeholder-text">No text provided</span>
            @endif
        </div>
        
        <!-- Progress Indicator -->
        <div class="progress-indicator">
            <div class="progress-bar">
                <div class="progress-fill" data-progress="0"></div>
            </div>
            <div class="progress-text">
                <span data-chars-typed>0</span> / <span data-total-chars>{{ strlen($text) }}</span> characters
            </div>
        </div>
    </div>

    <!-- Typing Input -->
    <div class="typing-input-container">
        <textarea 
            class="typing-input" 
            placeholder="{{ $placeholder }}"
            rows="4"
            {{ $disabled ? 'disabled' : '' }}
            data-typing-area
        ></textarea>
        
        <div class="input-controls">
            <div class="typing-mode-controls">
                <button class="mode-btn active" data-mode="normal">
                    <i class="fas fa-keyboard"></i>
                    Normal
                </button>
                <button class="mode-btn" data-mode="zen">
                    <i class="fas fa-eye-slash"></i>
                    Zen Mode
                </button>
                <button class="mode-btn" data-mode="focus">
                    <i class="fas fa-crosshairs"></i>
                    Focus Mode
                </button>
            </div>
            
            <div class="action-controls">
                <button class="control-btn" data-action="restart" title="Restart">
                    <i class="fas fa-redo"></i>
                </button>
                <button class="control-btn" data-action="pause" title="Pause">
                    <i class="fas fa-pause"></i>
                </button>
                <button class="control-btn" data-action="settings" title="Settings">
                    <i class="fas fa-cog"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Virtual Keyboard -->
    @if($showKeyboard)
    <div class="virtual-keyboard-container">
        <div class="keyboard-info">
            <div class="next-key-display">
                <span class="key-label">Next Key:</span>
                <span class="key-highlight" data-next-key>-</span>
                <span class="finger-guide">Use: <span data-finger-guide>-</span></span>
            </div>
        </div>
        
        <div class="virtual-keyboard">
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
    @endif
</div>

<style>
.typing-area-component {
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: var(--border-radius-lg);
    overflow: hidden;
}

/* Typing Stats Bar */
.typing-stats-bar {
    display: flex;
    justify-content: space-around;
    background: var(--bg-secondary);
    border-bottom: 1px solid var(--border-light);
    padding: 1.5rem;
}

.typing-stats-bar .stat-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    min-width: 100px;
}

.typing-stats-bar .stat-icon {
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

.typing-stats-bar .stat-content {
    text-align: left;
}

.typing-stats-bar .stat-value {
    font-family: var(--font-display);
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 0.25rem;
}

.typing-stats-bar .stat-label {
    font-size: 0.8rem;
    color: var(--text-secondary);
    text-transform: uppercase;
    letter-spacing: 1px;
}

/* Text Display */
.text-display-container {
    padding: 2rem;
    border-bottom: 1px solid var(--border-light);
}

.text-content {
    background: var(--bg-secondary);
    border: 2px solid var(--border-light);
    border-radius: var(--border-radius);
    padding: 2rem;
    font-family: var(--font-mono);
    font-size: 1.2rem;
    line-height: 1.8;
    letter-spacing: 0.5px;
    color: var(--text-primary);
    user-select: none;
    margin-bottom: 1rem;
    min-height: 120px;
    position: relative;
}

.char {
    position: relative;
    transition: all 0.2s ease;
    border-radius: 2px;
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

.placeholder-text {
    color: var(--text-muted);
    font-style: italic;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}

@keyframes pulse-cursor {
    0%, 50% { opacity: 1; }
    51%, 100% { opacity: 0.7; }
}

/* Progress Indicator */
.progress-indicator {
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

/* Typing Input */
.typing-input-container {
    padding: 2rem;
    border-bottom: 1px solid var(--border-light);
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
    margin-bottom: 1rem;
}

.typing-input:focus {
    outline: none;
    border-color: var(--accent-primary);
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.typing-input:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.input-controls {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.typing-mode-controls {
    display: flex;
    gap: 0.5rem;
}

.mode-btn {
    background: var(--bg-secondary);
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

.action-controls {
    display: flex;
    gap: 0.5rem;
}

.control-btn {
    background: var(--bg-secondary);
    border: 1px solid var(--border-light);
    border-radius: var(--border-radius);
    padding: 0.5rem;
    color: var(--text-secondary);
    transition: all 0.3s ease;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.control-btn:hover {
    background: var(--border-light);
    color: var(--text-primary);
}

/* Virtual Keyboard */
.virtual-keyboard-container {
    padding: 2rem;
    background: var(--bg-secondary);
}

.keyboard-info {
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: var(--border-radius);
    padding: 1rem;
    margin-bottom: 1rem;
    text-align: center;
}

.next-key-display {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 1rem;
}

.key-label,
.finger-guide {
    color: var(--text-secondary);
    font-size: 0.9rem;
}

.key-highlight {
    background: var(--accent-primary);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: var(--border-radius);
    font-family: var(--font-mono);
    font-size: 1.2rem;
    font-weight: 600;
    min-width: 50px;
    display: inline-block;
    text-align: center;
}

.virtual-keyboard {
    display: flex;
    flex-direction: column;
    gap: 4px;
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

/* Mode Variations */
.typing-area-component[data-mode="zen"] .typing-stats-bar,
.typing-area-component[data-mode="zen"] .virtual-keyboard-container {
    display: none;
}

.typing-area-component[data-mode="zen"] .text-content {
    font-size: 1.5rem;
    line-height: 2;
    text-align: center;
    min-height: 200px;
}

.typing-area-component[data-mode="focus"] .input-controls,
.typing-area-component[data-mode="focus"] .keyboard-info {
    display: none;
}

/* Responsive Design */
@media (max-width: 1024px) {
    .typing-stats-bar {
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
    }
    
    .input-controls {
        flex-direction: column;
        gap: 1rem;
    }
}

@media (max-width: 768px) {
    .typing-area-component {
        margin: 0 -1rem;
        border-radius: 0;
    }
    
    .text-display-container,
    .typing-input-container,
    .virtual-keyboard-container {
        padding: 1rem;
    }
    
    .typing-stats-bar {
        flex-direction: column;
        gap: 1rem;
    }
    
    .typing-stats-bar .stat-item {
        justify-content: center;
    }
    
    .text-content {
        font-size: 1rem;
        padding: 1rem;
    }
    
    .virtual-keyboard {
        transform: scale(0.8);
        margin: 0 -10%;
    }
    
    .next-key-display {
        flex-wrap: wrap;
    }
}
</style>

<script>
// Typing Area Component JavaScript
class TypingAreaComponent {
    constructor(element) {
        this.element = element;
        this.textContent = element.querySelector('.text-content');
        this.typingInput = element.querySelector('[data-typing-area]');
        this.originalText = this.textContent.dataset.text || '';
        this.currentIndex = 0;
        this.errors = 0;
        this.startTime = null;
        this.isStarted = false;
        
        this.initializeComponent();
    }
    
    initializeComponent() {
        if (!this.typingInput) return;
        
        // Add event listeners
        this.typingInput.addEventListener('input', (e) => this.handleInput(e));
        this.typingInput.addEventListener('keydown', (e) => this.handleKeyDown(e));
        this.typingInput.addEventListener('focus', () => this.startTyping());
        
        // Mode controls
        this.element.querySelectorAll('.mode-btn').forEach(btn => {
            btn.addEventListener('click', (e) => this.switchMode(e.target.dataset.mode));
        });
        
        // Action controls
        this.element.querySelectorAll('.control-btn').forEach(btn => {
            btn.addEventListener('click', (e) => this.handleAction(e.target.closest('.control-btn').dataset.action));
        });
        
        // Initialize display
        this.updateCharacterHighlight();
        this.updateNextKey();
    }
    
    startTyping() {
        if (!this.isStarted && this.originalText) {
            this.isStarted = true;
            this.startTime = Date.now();
            this.startStatsUpdate();
        }
    }
    
    handleInput(e) {
        const inputText = e.target.value;
        this.currentIndex = inputText.length;
        
        // Update character highlighting
        this.updateCharacterHighlight();
        
        // Update statistics
        this.updateStats();
        
        // Update next key
        this.updateNextKey();
        
        // Check completion
        if (this.currentIndex >= this.originalText.length) {
            this.completeTyping();
        }
    }
    
    handleKeyDown(e) {
        // Highlight keyboard key
        this.highlightKey(e.key);
        
        // Handle special keys
        if (e.key === 'Tab') {
            e.preventDefault();
        }
    }
    
    updateCharacterHighlight() {
        const chars = this.textContent.querySelectorAll('.char');
        const inputText = this.typingInput.value;
        
        chars.forEach((char, index) => {
            char.classList.remove('correct', 'incorrect', 'current');
            
            if (index < inputText.length) {
                if (inputText[index] === this.originalText[index]) {
                    char.classList.add('correct');
                } else {
                    char.classList.add('incorrect');
                }
            } else if (index === inputText.length) {
                char.classList.add('current');
            }
        });
    }
    
    updateStats() {
        const inputText = this.typingInput.value;
        const timeElapsed = this.startTime ? (Date.now() - this.startTime) / 1000 / 60 : 0; // minutes
        
        // Calculate WPM
        const wordsTyped = inputText.trim().split(/\s+/).length;
        const wpm = timeElapsed > 0 ? Math.round(wordsTyped / timeElapsed) : 0;
        
        // Calculate accuracy
        let correctChars = 0;
        for (let i = 0; i < inputText.length; i++) {
            if (inputText[i] === this.originalText[i]) {
                correctChars++;
            }
        }
        const accuracy = inputText.length > 0 ? Math.round((correctChars / inputText.length) * 100) : 100;
        
        // Calculate progress
        const progress = Math.round((inputText.length / this.originalText.length) * 100);
        
        // Calculate errors
        this.errors = inputText.length - correctChars;
        
        // Update displays
        this.updateStatDisplay('wpm', wpm);
        this.updateStatDisplay('accuracy', accuracy + '%');
        this.updateStatDisplay('progress', progress + '%');
        this.updateStatDisplay('errors', this.errors);
        
        // Update progress bar
        const progressFill = this.element.querySelector('[data-progress]');
        if (progressFill) {
            progressFill.style.width = progress + '%';
        }
        
        // Update character count
        const charsTypedEl = this.element.querySelector('[data-chars-typed]');
        if (charsTypedEl) {
            charsTypedEl.textContent = inputText.length;
        }
    }
    
    updateStatDisplay(stat, value) {
        const statElement = this.element.querySelector(`[data-stat="${stat}"]`);
        if (statElement) {
            statElement.textContent = value;
        }
    }
    
    updateNextKey() {
        const nextChar = this.originalText[this.currentIndex];
        if (!nextChar) return;
        
        // Update next key display
        const nextKeyEl = this.element.querySelector('[data-next-key]');
        if (nextKeyEl) {
            nextKeyEl.textContent = nextChar === ' ' ? 'Space' : nextChar.toUpperCase();
        }
        
        // Update finger guide
        const fingerGuideEl = this.element.querySelector('[data-finger-guide]');
        if (fingerGuideEl) {
            fingerGuideEl.textContent = this.getFingerForKey(nextChar);
        }
        
        // Highlight next key on keyboard
        this.highlightNextKey(nextChar);
    }
    
    highlightKey(key) {
        // Remove previous highlights
        this.element.querySelectorAll('.key').forEach(k => k.classList.remove('active'));
        
        // Highlight current key
        const keyElement = this.element.querySelector(`[data-key="${key.toLowerCase()}"]`);
        if (keyElement) {
            keyElement.classList.add('active');
            setTimeout(() => keyElement.classList.remove('active'), 200);
        }
    }
    
    highlightNextKey(char) {
        // Remove previous next highlights
        this.element.querySelectorAll('.key').forEach(k => k.classList.remove('next'));
        
        // Highlight next key
        const keyElement = this.element.querySelector(`[data-key="${char.toLowerCase()}"]`);
        if (keyElement) {
            keyElement.classList.add('next');
        }
    }
    
    getFingerForKey(key) {
        const fingerMap = {
            'q': 'Left Pinky', 'w': 'Left Ring', 'e': 'Left Middle', 'r': 'Left Index', 't': 'Left Index',
            'a': 'Left Pinky', 's': 'Left Ring', 'd': 'Left Middle', 'f': 'Left Index', 'g': 'Left Index',
            'z': 'Left Pinky', 'x': 'Left Ring', 'c': 'Left Middle', 'v': 'Left Index', 'b': 'Left Index',
            'y': 'Right Index', 'u': 'Right Index', 'i': 'Right Middle', 'o': 'Right Ring', 'p': 'Right Pinky',
            'h': 'Right Index', 'j': 'Right Index', 'k': 'Right Middle', 'l': 'Right Ring', ';': 'Right Pinky',
            'n': 'Right Index', 'm': 'Right Index', ',': 'Right Middle', '.': 'Right Ring', '/': 'Right Pinky',
            ' ': 'Thumb'
        };
        return fingerMap[key.toLowerCase()] || 'Unknown';
    }
    
    switchMode(mode) {
        // Update active mode button
        this.element.querySelectorAll('.mode-btn').forEach(btn => btn.classList.remove('active'));
        this.element.querySelector(`[data-mode="${mode}"]`).classList.add('active');
        
        // Apply mode to component
        this.element.dataset.mode = mode;
    }
    
    handleAction(action) {
        switch (action) {
            case 'restart':
                this.restartTyping();
                break;
            case 'pause':
                this.pauseTyping();
                break;
            case 'settings':
                this.showSettings();
                break;
        }
    }
    
    restartTyping() {
        this.typingInput.value = '';
        this.currentIndex = 0;
        this.errors = 0;
        this.startTime = null;
        this.isStarted = false;
        
        // Reset displays
        this.updateCharacterHighlight();
        this.updateStats();
        this.updateNextKey();
        
        // Focus input
        this.typingInput.focus();
    }
    
    pauseTyping() {
        this.typingInput.disabled = !this.typingInput.disabled;
        
        const pauseBtn = this.element.querySelector('[data-action="pause"] i');
        if (pauseBtn) {
            pauseBtn.className = this.typingInput.disabled ? 'fas fa-play' : 'fas fa-pause';
        }
    }
    
    showSettings() {
        // Trigger settings modal or panel
        console.log('Show typing settings');
    }
    
    startStatsUpdate() {
        if (this.statsInterval) {
            clearInterval(this.statsInterval);
        }
        
        this.statsInterval = setInterval(() => {
            this.updateStats();
        }, 1000);
    }
    
    completeTyping() {
        this.isStarted = false;
        
        if (this.statsInterval) {
            clearInterval(this.statsInterval);
        }
        
        // Trigger completion event
        this.element.dispatchEvent(new CustomEvent('typing:complete', {
            detail: {
                wpm: this.element.querySelector('[data-stat="wpm"]')?.textContent,
                accuracy: this.element.querySelector('[data-stat="accuracy"]')?.textContent,
                errors: this.errors,
                timeElapsed: this.startTime ? (Date.now() - this.startTime) / 1000 : 0
            }
        }));
    }
}

// Auto-initialize typing areas
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.typing-area-component').forEach(element => {
        new TypingAreaComponent(element);
    });
});
</script>