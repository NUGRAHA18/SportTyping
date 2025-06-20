class TypingTest {
    constructor(options = {}) {
        // Configuration
        this.config = {
            textElement: options.textElement || "#typing-text",
            inputElement: options.inputElement || "#typing-input",
            statsElement: options.statsElement || "#typing-stats",
            highlightCorrect: options.highlightCorrect || "correct",
            highlightIncorrect: options.highlightIncorrect || "incorrect",
            highlightCurrent: options.highlightCurrent || "current",
            updateInterval: options.updateInterval || 100,
            enableSound: options.enableSound || false,
            enableKeyboard: options.enableKeyboard || false,
            keyboardElement: options.keyboardElement || "#virtual-keyboard",
        };

        // State
        this.originalText = "";
        this.typedText = "";
        this.startTime = null;
        this.endTime = null;
        this.isActive = false;
        this.isPaused = false;
        this.currentPosition = 0;
        this.errors = [];
        this.keystrokes = [];
        this.updateTimer = null;

        // Statistics
        this.stats = {
            wpm: 0,
            accuracy: 100,
            charactersTyped: 0,
            correctCharacters: 0,
            incorrectCharacters: 0,
            totalKeystrokes: 0,
            elapsedTime: 0,
            progress: 0,
        };

        // Event callbacks
        this.callbacks = {
            onStart: options.onStart || (() => {}),
            onUpdate: options.onUpdate || (() => {}),
            onComplete: options.onComplete || (() => {}),
            onError: options.onError || (() => {}),
            onKeyPress: options.onKeyPress || (() => {}),
        };

        this.init();
    }

    init() {
        this.textElement = document.querySelector(this.config.textElement);
        this.inputElement = document.querySelector(this.config.inputElement);
        this.statsElement = document.querySelector(this.config.statsElement);

        if (!this.textElement || !this.inputElement) {
            console.error("TypingTest: Required elements not found");
            return;
        }

        this.originalText = this.textElement.textContent.trim();
        this.setupEventListeners();
        this.renderText();
        this.updateStats();

        // Initialize virtual keyboard if enabled
        if (this.config.enableKeyboard) {
            this.initVirtualKeyboard();
        }
    }

    setupEventListeners() {
        // Input event listeners
        this.inputElement.addEventListener("input", (e) => this.handleInput(e));
        this.inputElement.addEventListener("keydown", (e) =>
            this.handleKeyDown(e)
        );
        this.inputElement.addEventListener("keyup", (e) => this.handleKeyUp(e));
        this.inputElement.addEventListener("paste", (e) => this.handlePaste(e));

        // Focus management
        this.inputElement.addEventListener("focus", () => this.handleFocus());
        this.inputElement.addEventListener("blur", () => this.handleBlur());

        // Visibility change (pause when tab is not active)
        document.addEventListener("visibilitychange", () => {
            if (document.hidden && this.isActive) {
                this.pause();
            }
        });
    }

    handleInput(e) {
        this.typedText = e.target.value;

        if (!this.isActive && this.typedText.length > 0) {
            this.start();
        }

        if (this.isActive) {
            this.currentPosition = this.typedText.length;
            this.updateHighlighting();
            this.calculateStats();

            // Check completion
            if (this.typedText.length >= this.originalText.length) {
                this.complete();
            }
        }
    }

    handleKeyDown(e) {
        if (!this.isActive) return;

        this.keystrokes.push({
            key: e.key,
            timestamp: Date.now(),
            correct: this.isCorrectKey(e.key),
        });

        this.stats.totalKeystrokes++;

        // Update virtual keyboard
        if (this.config.enableKeyboard) {
            this.highlightKey(e.key, true);
        }

        this.callbacks.onKeyPress(e, this.stats);

        // Prevent certain keys
        if (
            e.key === "Tab" ||
            (e.ctrlKey && ["a", "v", "c", "x"].includes(e.key))
        ) {
            e.preventDefault();
        }
    }

    handleKeyUp(e) {
        if (this.config.enableKeyboard) {
            this.highlightKey(e.key, false);
        }
    }

    handlePaste(e) {
        e.preventDefault();
        // Prevent pasting
    }

    handleFocus() {
        if (this.isPaused) {
            this.resume();
        }
    }

    handleBlur() {
        if (this.isActive && !this.isPaused) {
            this.pause();
        }
    }

    isCorrectKey(key) {
        if (this.currentPosition >= this.originalText.length) return false;
        return this.originalText[this.currentPosition] === key;
    }

    start() {
        this.isActive = true;
        this.startTime = Date.now();
        this.updateTimer = setInterval(
            () => this.updateStats(),
            this.config.updateInterval
        );
        this.callbacks.onStart();
    }

    pause() {
        this.isPaused = true;
        if (this.updateTimer) {
            clearInterval(this.updateTimer);
        }
    }

    resume() {
        this.isPaused = false;
        this.updateTimer = setInterval(
            () => this.updateStats(),
            this.config.updateInterval
        );
    }

    complete() {
        this.isActive = false;
        this.endTime = Date.now();

        if (this.updateTimer) {
            clearInterval(this.updateTimer);
        }

        this.calculateFinalStats();
        this.callbacks.onComplete(this.stats);
    }

    reset() {
        this.isActive = false;
        this.isPaused = false;
        this.typedText = "";
        this.currentPosition = 0;
        this.startTime = null;
        this.endTime = null;
        this.errors = [];
        this.keystrokes = [];

        if (this.updateTimer) {
            clearInterval(this.updateTimer);
        }

        this.inputElement.value = "";
        this.renderText();
        this.updateStats();
    }

    setText(newText) {
        this.originalText = newText.trim();
        this.reset();
        this.renderText();
    }

    renderText() {
        if (!this.textElement) return;

        let html = "";

        for (let i = 0; i < this.originalText.length; i++) {
            const char = this.originalText[i];
            let className = "";

            if (i < this.typedText.length) {
                // Character has been typed
                if (this.typedText[i] === char) {
                    className = this.config.highlightCorrect;
                } else {
                    className = this.config.highlightIncorrect;
                }
            } else if (i === this.currentPosition) {
                // Current character to type
                className = this.config.highlightCurrent;
            }

            if (char === " ") {
                html += `<span class="${className}">&nbsp;</span>`;
            } else if (char === "\n") {
                html += `<span class="${className}">↵</span><br>`;
            } else {
                html += `<span class="${className}">${this.escapeHtml(
                    char
                )}</span>`;
            }
        }

        this.textElement.innerHTML = html;
    }

    updateHighlighting() {
        this.renderText();
    }

    calculateStats() {
        const currentTime = Date.now();
        const elapsedSeconds = (currentTime - this.startTime) / 1000;

        // Characters
        this.stats.charactersTyped = this.typedText.length;
        this.stats.correctCharacters = 0;
        this.stats.incorrectCharacters = 0;

        for (let i = 0; i < this.typedText.length; i++) {
            if (
                i < this.originalText.length &&
                this.typedText[i] === this.originalText[i]
            ) {
                this.stats.correctCharacters++;
            } else {
                this.stats.incorrectCharacters++;
            }
        }

        // WPM Calculation (standard: 5 characters = 1 word)
        const wordsTyped = this.stats.correctCharacters / 5;
        const minutesElapsed = elapsedSeconds / 60;
        this.stats.wpm =
            minutesElapsed > 0 ? Math.round(wordsTyped / minutesElapsed) : 0;

        // Accuracy
        this.stats.accuracy =
            this.stats.charactersTyped > 0
                ? Math.round(
                      (this.stats.correctCharacters /
                          this.stats.charactersTyped) *
                          100
                  )
                : 100;

        // Progress
        this.stats.progress = Math.round(
            (this.stats.charactersTyped / this.originalText.length) * 100
        );

        // Time
        this.stats.elapsedTime = elapsedSeconds;

        this.updateStatsDisplay();
        this.callbacks.onUpdate(this.stats);
    }

    calculateFinalStats() {
        const totalSeconds = (this.endTime - this.startTime) / 1000;

        // Final WPM calculation
        const correctWords = this.stats.correctCharacters / 5;
        const minutes = totalSeconds / 60;
        this.stats.wpm = minutes > 0 ? Math.round(correctWords / minutes) : 0;

        // Error analysis
        this.stats.errorRate =
            this.stats.charactersTyped > 0
                ? Math.round(
                      (this.stats.incorrectCharacters /
                          this.stats.charactersTyped) *
                          100
                  )
                : 0;

        // Keystroke efficiency
        this.stats.efficiency =
            this.stats.totalKeystrokes > 0
                ? Math.round(
                      (this.stats.correctCharacters /
                          this.stats.totalKeystrokes) *
                          100
                  )
                : 0;

        // Speed consistency (variation in keystroke timing)
        this.calculateSpeedConsistency();
    }

    calculateSpeedConsistency() {
        if (this.keystrokes.length < 10) return;

        const intervals = [];
        for (let i = 1; i < this.keystrokes.length; i++) {
            intervals.push(
                this.keystrokes[i].timestamp - this.keystrokes[i - 1].timestamp
            );
        }

        const avgInterval =
            intervals.reduce((a, b) => a + b, 0) / intervals.length;
        const variance =
            intervals.reduce((acc, interval) => {
                return acc + Math.pow(interval - avgInterval, 2);
            }, 0) / intervals.length;

        this.stats.consistency = Math.round(
            100 - (Math.sqrt(variance) / avgInterval) * 100
        );
        this.stats.consistency = Math.max(
            0,
            Math.min(100, this.stats.consistency)
        );
    }

    updateStatsDisplay() {
        if (!this.statsElement) return;

        const statsHtml = `
            <div class="stat-item">
                <div class="stat-value">${this.stats.wpm}</div>
                <div class="stat-label">WPM</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">${this.stats.accuracy}%</div>
                <div class="stat-label">Accuracy</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">${Math.round(
                    this.stats.elapsedTime
                )}</div>
                <div class="stat-label">Time (s)</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">${this.stats.progress}%</div>
                <div class="stat-label">Progress</div>
            </div>
        `;

        this.statsElement.innerHTML = statsHtml;
    }

    initVirtualKeyboard() {
        const keyboardElement = document.querySelector(
            this.config.keyboardElement
        );
        if (!keyboardElement) return;

        // Highlight the next key to press
        if (this.currentPosition < this.originalText.length) {
            const nextChar =
                this.originalText[this.currentPosition].toLowerCase();
            this.highlightNextKey(nextChar);
        }
    }

    highlightKey(key, isPressed) {
        const keyboardElement = document.querySelector(
            this.config.keyboardElement
        );
        if (!keyboardElement) return;

        const keyElement = keyboardElement.querySelector(
            `[data-key="${key.toLowerCase()}"]`
        );
        if (keyElement) {
            if (isPressed) {
                keyElement.classList.add("key-pressed");
            } else {
                keyElement.classList.remove("key-pressed");
            }
        }
    }

    highlightNextKey(key) {
        const keyboardElement = document.querySelector(
            this.config.keyboardElement
        );
        if (!keyboardElement) return;

        // Remove previous highlights
        keyboardElement.querySelectorAll(".key-next").forEach((el) => {
            el.classList.remove("key-next");
        });

        // Highlight next key
        const keyElement = keyboardElement.querySelector(`[data-key="${key}"]`);
        if (keyElement) {
            keyElement.classList.add("key-next");
        }
    }

    escapeHtml(text) {
        const div = document.createElement("div");
        div.textContent = text;
        return div.innerHTML;
    }

    // Public methods for external access
    getStats() {
        return { ...this.stats };
    }

    getProgress() {
        return {
            typed: this.typedText,
            remaining: this.originalText.slice(this.typedText.length),
            position: this.currentPosition,
            total: this.originalText.length,
        };
    }

    isRunning() {
        return this.isActive && !this.isPaused;
    }

    getKeystrokes() {
        return [...this.keystrokes];
    }

    // Static utility methods
    static calculateWPM(text, timeInSeconds) {
        const words = text.length / 5; // Standard: 5 characters = 1 word
        const minutes = timeInSeconds / 60;
        return minutes > 0 ? Math.round(words / minutes) : 0;
    }

    static calculateAccuracy(originalText, typedText) {
        let correct = 0;
        const maxLength = Math.min(originalText.length, typedText.length);

        for (let i = 0; i < maxLength; i++) {
            if (originalText[i] === typedText[i]) {
                correct++;
            }
        }

        return typedText.length > 0
            ? Math.round((correct / typedText.length) * 100)
            : 100;
    }
}

// CSS for typing test highlighting
const typingTestCSS = `
.typing-text {
    font-family: 'Courier New', monospace;
    font-size: 1.2rem;
    line-height: 1.8;
    padding: 2rem;
    background: var(--bg-card);
    border-radius: var(--border-radius);
    border: 2px solid var(--border-light);
    margin-bottom: 1rem;
    white-space: pre-wrap;
    word-wrap: break-word;
}

.typing-text .correct {
    background-color: rgba(16, 185, 129, 0.2);
    color: var(--accent-success);
}

.typing-text .incorrect {
    background-color: rgba(239, 68, 68, 0.2);
    color: var(--accent-danger);
}

.typing-text .current {
    background-color: var(--accent-primary);
    color: white;
    animation: blink 1s infinite;
}

@keyframes blink {
    0%, 50% { opacity: 1; }
    51%, 100% { opacity: 0.3; }
}

.typing-input {
    width: 100%;
    padding: 1rem;
    font-size: 1.1rem;
    border: 2px solid var(--border-light);
    border-radius: var(--border-radius);
    background: var(--bg-card);
    color: var(--text-primary);
    resize: none;
    outline: none;
    transition: border-color 0.3s ease;
}

.typing-input:focus {
    border-color: var(--accent-primary);
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.typing-stats {
    display: flex;
    gap: 2rem;
    padding: 1rem;
    background: var(--bg-secondary);
    border-radius: var(--border-radius);
    margin-top: 1rem;
}

.typing-stats .stat-item {
    text-align: center;
    flex: 1;
}

.typing-stats .stat-value {
    font-family: var(--font-display);
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--accent-primary);
}

.typing-stats .stat-label {
    color: var(--text-secondary);
    font-size: 0.9rem;
    font-weight: 500;
    margin-top: 0.25rem;
}

.virtual-keyboard {
    margin-top: 2rem;
    padding: 1rem;
    background: var(--bg-card);
    border-radius: var(--border-radius);
    border: 1px solid var(--border-light);
}

.virtual-keyboard .key {
    display: inline-block;
    padding: 0.5rem 0.75rem;
    margin: 0.125rem;
    background: var(--bg-secondary);
    border: 1px solid var(--border-light);
    border-radius: var(--border-radius-sm);
    font-size: 0.9rem;
    transition: all 0.2s ease;
}

.virtual-keyboard .key-pressed {
    background: var(--accent-primary);
    color: white;
    transform: scale(0.95);
}

.virtual-keyboard .key-next {
    background: var(--accent-secondary);
    color: white;
    animation: pulse 1s ease-in-out infinite;
}

@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}
`;

// Inject CSS if not already present
if (!document.querySelector("#typing-test-styles")) {
    const style = document.createElement("style");
    style.id = "typing-test-styles";
    style.textContent = typingTestCSS;
    document.head.appendChild(style);
}

// Export for module systems
if (typeof module !== "undefined" && module.exports) {
    module.exports = TypingTest;
}

// Global namespace
window.TypingTest = TypingTest;
