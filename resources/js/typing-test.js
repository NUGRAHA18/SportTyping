/**
 * SportTyping - Real-time Typing Test Engine
 * Professional typing test with WPM calculation, highlighting, and error detection
 * Adopts styling from app.blade.php for consistent UI/UX
 */

class TypingTest {
    constructor(options = {}) {
        // Configuration
        this.config = {
            container: options.container || '.typing-area',
            textContainer: options.textContainer || '.typing-text',
            inputContainer: options.inputContainer || '.typing-input',
            statsContainer: options.statsContainer || '.typing-stats',
            originalText: options.originalText || '',
            mode: options.mode || 'practice', // 'practice', 'competition', 'lesson'
            competitionId: options.competitionId || null,
            apiEndpoint: options.apiEndpoint || '/api/practice/calculate-stats',
            guestMode: options.guestMode || !window.userData,
            updateInterval: options.updateInterval || 1000,
            highlightDelay: options.highlightDelay || 100,
            showKeyboard: options.showKeyboard || false,
            ...options
        };

        // State management
        this.state = {
            isActive: false,
            isPaused: false,
            isCompleted: false,
            startTime: null,
            endTime: null,
            currentPosition: 0,
            typedText: '',
            errors: new Set(),
            totalKeystrokes: 0,
            correctKeystrokes: 0,
            currentWPM: 0,
            currentAccuracy: 100,
            elapsedTime: 0,
            lastUpdateTime: 0
        };

        // DOM elements
        this.elements = {};
        
        // Intervals and timeouts
        this.updateTimer = null;
        this.highlightTimer = null;
        
        // Event handlers
        this.eventHandlers = {
            onProgress: options.onProgress || null,
            onComplete: options.onComplete || null,
            onError: options.onError || null,
            onStart: options.onStart || null,
            onPause: options.onPause || null,
            onResume: options.onResume || null
        };

        // Initialize
        this.init();
    }

    /**
     * Initialize the typing test
     */
    async init() {
        try {
            await this.setupDOM();
            this.setupEventListeners();
            this.setupKeyboardVisualization();
            this.renderText();
            this.updateStats();
            
            console.log('✅ TypingTest initialized successfully');
        } catch (error) {
            console.error('❌ Failed to initialize TypingTest:', error);
            this.showError('Failed to initialize typing test');
        }
    }

    /**
     * Setup DOM elements with SportTyping styling
     */
    async setupDOM() {
        const container = document.querySelector(this.config.container);
        if (!container) {
            throw new Error(`Container not found: ${this.config.container}`);
        }

        // Create main typing interface
        container.innerHTML = `
            <div class="typing-test-container">
                <!-- Header with stats -->
                <div class="typing-header">
                    <div class="typing-stats" id="typingStats">
                        <div class="stat-item wpm">
                            <div class="stat-icon">
                                <i class="fas fa-tachometer-alt"></i>
                            </div>
                            <div class="stat-content">
                                <span class="stat-value" id="wpmValue">0</span>
                                <span class="stat-label">WPM</span>
                            </div>
                        </div>
                        
                        <div class="stat-item accuracy">
                            <div class="stat-icon">
                                <i class="fas fa-bullseye"></i>
                            </div>
                            <div class="stat-content">
                                <span class="stat-value" id="accuracyValue">100</span>
                                <span class="stat-label">Accuracy</span>
                            </div>
                        </div>
                        
                        <div class="stat-item time">
                            <div class="stat-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="stat-content">
                                <span class="stat-value" id="timeValue">0:00</span>
                                <span class="stat-label">Time</span>
                            </div>
                        </div>
                        
                        <div class="stat-item progress">
                            <div class="stat-icon">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <div class="stat-content">
                                <span class="stat-value" id="progressValue">0</span>
                                <span class="stat-label">Progress</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Text display area -->
                <div class="typing-text-container">
                    <div class="typing-text" id="typingText">
                        <!-- Text will be rendered here -->
                    </div>
                    <div class="typing-cursor" id="typingCursor"></div>
                </div>

                <!-- Input area -->
                <div class="typing-input-container">
                    <textarea 
                        id="typingInput" 
                        class="typing-input" 
                        placeholder="Click here and start typing..."
                        autocomplete="off"
                        autocorrect="off"
                        autocapitalize="off"
                        spellcheck="false"
                    ></textarea>
                </div>

                <!-- Control buttons -->
                <div class="typing-controls">
                    <button class="control-btn restart" id="restartBtn">
                        <i class="fas fa-redo"></i>
                        <span>Restart</span>
                    </button>
                    
                    <button class="control-btn pause" id="pauseBtn" style="display: none;">
                        <i class="fas fa-pause"></i>
                        <span>Pause</span>
                    </button>
                    
                    <button class="control-btn resume" id="resumeBtn" style="display: none;">
                        <i class="fas fa-play"></i>
                        <span>Resume</span>
                    </button>
                </div>

                <!-- Keyboard visualization (optional) -->
                ${this.config.showKeyboard ? `
                    <div class="keyboard-container" id="keyboardContainer">
                        <div class="keyboard-visualization" id="keyboardVisualization">
                            <!-- Keyboard will be rendered here -->
                        </div>
                    </div>
                ` : ''}

                <!-- Progress indicator -->
                <div class="typing-progress-bar">
                    <div class="progress-fill" id="progressFill"></div>
                    <div class="progress-text" id="progressText">Ready to start</div>
                </div>
            </div>
        `;

        // Cache DOM elements
        this.elements = {
            container: container.querySelector('.typing-test-container'),
            textContainer: document.getElementById('typingText'),
            input: document.getElementById('typingInput'),
            cursor: document.getElementById('typingCursor'),
            stats: document.getElementById('typingStats'),
            wpmValue: document.getElementById('wpmValue'),
            accuracyValue: document.getElementById('accuracyValue'),
            timeValue: document.getElementById('timeValue'),
            progressValue: document.getElementById('progressValue'),
            progressFill: document.getElementById('progressFill'),
            progressText: document.getElementById('progressText'),
            restartBtn: document.getElementById('restartBtn'),
            pauseBtn: document.getElementById('pauseBtn'),
            resumeBtn: document.getElementById('resumeBtn'),
            keyboard: document.getElementById('keyboardVisualization')
        };

        // Apply SportTyping styling
        this.applyCustomStyling();
    }

    /**
     * Apply custom styling that matches app.blade.php
     */
    applyCustomStyling() {
        const style = document.createElement('style');
        style.textContent = `
            .typing-test-container {
                background: var(--bg-card);
                border-radius: var(--border-radius-xl);
                border: 1px solid var(--border-light);
                padding: 2rem;
                box-shadow: var(--shadow-lg);
                position: relative;
                overflow: hidden;
            }

            .typing-test-container::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                height: 4px;
                background: var(--champion-gradient);
            }

            /* Header Stats */
            .typing-header {
                margin-bottom: 2rem;
            }

            .typing-stats {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
                gap: 1rem;
            }

            .stat-item {
                display: flex;
                align-items: center;
                gap: 1rem;
                padding: 1rem;
                background: var(--bg-secondary);
                border-radius: var(--border-radius);
                border: 1px solid var(--border-light);
                transition: all 0.3s ease;
            }

            .stat-item:hover {
                transform: translateY(-2px);
                box-shadow: var(--shadow-md);
            }

            .stat-icon {
                width: 45px;
                height: 45px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                font-size: 1.1rem;
                flex-shrink: 0;
            }

            .stat-item.wpm .stat-icon { background: var(--champion-gradient); }
            .stat-item.accuracy .stat-icon { background: var(--victory-gradient); }
            .stat-item.time .stat-icon { background: var(--medal-gradient); }
            .stat-item.progress .stat-icon { background: linear-gradient(135deg, #8b5cf6, #6366f1); }

            .stat-content {
                display: flex;
                flex-direction: column;
            }

            .stat-value {
                font-size: 1.5rem;
                font-weight: 700;
                color: var(--text-primary);
                line-height: 1.2;
                font-family: var(--font-display);
            }

            .stat-label {
                color: var(--text-secondary);
                font-size: 0.875rem;
                font-weight: 500;
            }

            /* Text Display */
            .typing-text-container {
                position: relative;
                background: var(--bg-primary);
                border: 2px solid var(--border-light);
                border-radius: var(--border-radius-lg);
                padding: 2rem;
                margin-bottom: 1.5rem;
                min-height: 200px;
                font-family: 'JetBrains Mono', 'Monaco', 'Consolas', monospace;
                font-size: 1.125rem;
                line-height: 1.8;
                overflow: hidden;
                position: relative;
            }

            .typing-text {
                position: relative;
                z-index: 2;
                word-wrap: break-word;
                user-select: none;
            }

            .typing-text .char {
                position: relative;
                transition: all 0.2s ease;
            }

            .typing-text .char.correct {
                color: var(--accent-success);
                background: rgba(16, 185, 129, 0.1);
            }

            .typing-text .char.incorrect {
                color: var(--accent-danger);
                background: rgba(239, 68, 68, 0.2);
                animation: errorShake 0.3s ease;
            }

            .typing-text .char.current {
                background: var(--accent-primary);
                color: white;
                animation: currentPulse 1.5s infinite;
            }

            .typing-text .char.pending {
                color: var(--text-muted);
            }

            @keyframes errorShake {
                0%, 100% { transform: translateX(0); }
                25% { transform: translateX(-2px); }
                75% { transform: translateX(2px); }
            }

            @keyframes currentPulse {
                0%, 100% { opacity: 1; }
                50% { opacity: 0.7; }
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

            @keyframes cursorBlink {
                0%, 50% { opacity: 1; }
                51%, 100% { opacity: 0; }
            }

            /* Input Area */
            .typing-input-container {
                margin-bottom: 1.5rem;
            }

            .typing-input {
                width: 100%;
                padding: 1rem;
                border: 2px solid var(--border-light);
                border-radius: var(--border-radius);
                font-family: 'JetBrains Mono', 'Monaco', 'Consolas', monospace;
                font-size: 1rem;
                background: var(--bg-primary);
                color: var(--text-primary);
                resize: none;
                height: 100px;
                transition: all 0.3s ease;
            }

            .typing-input:focus {
                outline: none;
                border-color: var(--accent-primary);
                box-shadow: var(--sport-glow);
            }

            .typing-input.error {
                border-color: var(--accent-danger);
                box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
            }

            /* Controls */
            .typing-controls {
                display: flex;
                gap: 1rem;
                justify-content: center;
                margin-bottom: 1.5rem;
            }

            .control-btn {
                display: flex;
                align-items: center;
                gap: 0.75rem;
                padding: 1rem 1.5rem;
                border: none;
                border-radius: var(--border-radius);
                font-weight: 600;
                cursor: pointer;
                transition: all 0.3s ease;
                position: relative;
                overflow: hidden;
            }

            .control-btn::before {
                content: '';
                position: absolute;
                top: 0;
                left: -100%;
                width: 100%;
                height: 100%;
                background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
                transition: left 0.5s ease;
            }

            .control-btn:hover::before {
                left: 100%;
            }

            .control-btn.restart {
                background: var(--champion-gradient);
                color: white;
            }

            .control-btn.pause {
                background: var(--medal-gradient);
                color: white;
            }

            .control-btn.resume {
                background: var(--victory-gradient);
                color: white;
            }

            .control-btn:hover {
                transform: translateY(-2px);
                box-shadow: var(--shadow-md);
            }

            /* Progress Bar */
            .typing-progress-bar {
                position: relative;
                width: 100%;
                height: 8px;
                background: var(--border-light);
                border-radius: 4px;
                overflow: hidden;
                margin-bottom: 1rem;
            }

            .progress-fill {
                height: 100%;
                background: var(--champion-gradient);
                border-radius: 4px;
                transition: width 0.3s ease;
                width: 0%;
            }

            .progress-text {
                text-align: center;
                color: var(--text-secondary);
                font-size: 0.875rem;
                font-weight: 500;
                margin-top: 0.5rem;
            }

            /* Keyboard Visualization */
            .keyboard-container {
                margin-top: 2rem;
                padding-top: 2rem;
                border-top: 1px solid var(--border-light);
            }

            .keyboard-visualization {
                display: grid;
                grid-template-columns: repeat(15, 1fr);
                gap: 4px;
                max-width: 800px;
                margin: 0 auto;
            }

            .key {
                aspect-ratio: 1;
                background: var(--bg-secondary);
                border: 1px solid var(--border-light);
                border-radius: var(--border-radius-sm);
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 0.875rem;
                font-weight: 500;
                color: var(--text-secondary);
                transition: all 0.2s ease;
            }

            .key.active {
                background: var(--accent-primary);
                color: white;
                transform: scale(1.1);
                box-shadow: var(--shadow-md);
            }

            .key.next {
                background: rgba(59, 130, 246, 0.1);
                border-color: var(--accent-primary);
                color: var(--accent-primary);
            }

            /* Responsive Design */
            @media (max-width: 768px) {
                .typing-test-container {
                    padding: 1rem;
                }

                .typing-stats {
                    grid-template-columns: repeat(2, 1fr);
                }

                .typing-text-container {
                    padding: 1rem;
                    font-size: 1rem;
                }

                .typing-controls {
                    flex-direction: column;
                }

                .control-btn {
                    width: 100%;
                    justify-content: center;
                }

                .keyboard-visualization {
                    grid-template-columns: repeat(10, 1fr);
                }
            }

            @media (max-width: 480px) {
                .typing-stats {
                    grid-template-columns: 1fr;
                }

                .stat-item {
                    padding: 0.75rem;
                }

                .stat-icon {
                    width: 40px;
                    height: 40px;
                }

                .typing-text-container {
                    font-size: 0.9rem;
                    min-height: 150px;
                }
            }

            /* Animation enhancements */
            .typing-test-container {
                animation: slideInUp 0.5s ease;
            }

            @keyframes slideInUp {
                from {
                    opacity: 0;
                    transform: translateY(30px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            /* Focus states */
            .typing-test-container.focused {
                border-color: var(--accent-primary);
                box-shadow: var(--sport-glow), var(--shadow-lg);
            }

            .typing-test-container.completed {
                border-color: var(--accent-success);
                background: linear-gradient(135deg, rgba(16, 185, 129, 0.02), var(--bg-card));
            }

            .typing-test-container.error {
                border-color: var(--accent-danger);
                animation: errorBorder 0.5s ease;
            }

            @keyframes errorBorder {
                0%, 100% { border-color: var(--accent-danger); }
                50% { border-color: rgba(239, 68, 68, 0.5); }
            }
        `;
        
        document.head.appendChild(style);
    }

    /**
     * Setup event listeners
     */
    setupEventListeners() {
        // Input handling
        this.elements.input.addEventListener('input', (e) => this.handleInput(e));
        this.elements.input.addEventListener('keydown', (e) => this.handleKeyDown(e));
        this.elements.input.addEventListener('keyup', (e) => this.handleKeyUp(e));
        this.elements.input.addEventListener('focus', () => this.handleFocus());
        this.elements.input.addEventListener('blur', () => this.handleBlur());

        // Control buttons
        this.elements.restartBtn.addEventListener('click', () => this.restart());
        this.elements.pauseBtn.addEventListener('click', () => this.pause());
        this.elements.resumeBtn.addEventListener('click', () => this.resume());

        // Prevent context menu on right click
        this.elements.textContainer.addEventListener('contextmenu', (e) => e.preventDefault());

        // Handle window visibility changes
        document.addEventListener('visibilitychange', () => this.handleVisibilityChange());

        // Mobile-specific events
        if ('ontouchstart' in window) {
            this.elements.input.addEventListener('touchstart', () => this.handleMobileInput());
        }
    }

    /**
     * Setup keyboard visualization
     */
    setupKeyboardVisualization() {
        if (!this.config.showKeyboard || !this.elements.keyboard) return;

        const keyboard = [
            ['`', '1', '2', '3', '4', '5', '6', '7', '8', '9', '0', '-', '=', 'Backspace'],
            ['Tab', 'q', 'w', 'e', 'r', 't', 'y', 'u', 'i', 'o', 'p', '[', ']', '\\'],
            ['Caps', 'a', 's', 'd', 'f', 'g', 'h', 'j', 'k', 'l', ';', "'", 'Enter'],
            ['Shift', 'z', 'x', 'c', 'v', 'b', 'n', 'm', ',', '.', '/', 'Shift'],
            ['Ctrl', 'Alt', 'Space', 'Alt', 'Ctrl']
        ];

        let keyboardHTML = '';
        keyboard.forEach(row => {
            row.forEach(key => {
                const keyClass = key === 'Space' ? 'key space' : 'key';
                const keyData = key.toLowerCase();
                keyboardHTML += `<div class="${keyClass}" data-key="${keyData}">${key}</div>`;
            });
        });

        this.elements.keyboard.innerHTML = keyboardHTML;
    }

    /**
     * Render text with character highlighting
     */
    renderText() {
        if (!this.config.originalText) {
            this.elements.textContainer.innerHTML = '<p style="color: var(--text-muted);">No text available for typing test.</p>';
            return;
        }

        const chars = this.config.originalText.split('');
        let html = '';

        chars.forEach((char, index) => {
            const charClass = this.getCharClass(index);
            const escaped = this.escapeHtml(char);
            html += `<span class="char ${charClass}" data-index="${index}">${escaped === ' ' ? '&nbsp;' : escaped}</span>`;
        });

        this.elements.textContainer.innerHTML = html;
        this.updateCursor();
    }

    /**
     * Get character class based on current state
     */
    getCharClass(index) {
        if (index < this.state.currentPosition) {
            return this.state.errors.has(index) ? 'incorrect' : 'correct';
        } else if (index === this.state.currentPosition) {
            return 'current';
        } else {
            return 'pending';
        }
    }

    /**
     * Escape HTML characters
     */
    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    /**
     * Handle input events
     */
    handleInput(e) {
        const typedText = e.target.value;
        
        // Start the test if not already started
        if (!this.state.isActive && typedText.length > 0) {
            this.start();
        }

        // Update state
        this.state.typedText = typedText;
        this.state.currentPosition = typedText.length;
        this.state.totalKeystrokes++;

        // Check for errors
        this.updateErrors();

        // Update display
        this.updateTextHighlighting();
        this.updateCursor();
        this.updateKeyboardHighlight();
        
        // Trigger progress callback
        if (this.eventHandlers.onProgress) {
            this.eventHandlers.onProgress(this.getProgressData());
        }

        // Check for completion
        if (this.state.currentPosition >= this.config.originalText.length) {
            this.complete();
        }

        // Limit input length
        if (typedText.length > this.config.originalText.length) {
            e.target.value = typedText.substring(0, this.config.originalText.length);
        }
    }

    /**
     * Handle keydown events
     */
    handleKeyDown(e) {
        // Handle special keys
        if (e.key === 'Tab') {
            e.preventDefault();
            return;
        }

        if (e.key === 'Escape') {
            this.pause();
            return;
        }

        if (e.ctrlKey && e.key === 'r') {
            e.preventDefault();
            this.restart();
            return;
        }

        // Visual feedback for keyboard
        this.highlightKey(e.key.toLowerCase());
        
        // Add error class to input on wrong key
        const currentChar = this.config.originalText[this.state.currentPosition];
        if (currentChar && e.key !== currentChar && e.key.length === 1) {
            this.elements.input.classList.add('error');
            setTimeout(() => {
                this.elements.input.classList.remove('error');
            }, 200);
        }
    }

    /**
     * Handle keyup events
     */
    handleKeyUp(e) {
        this.removeKeyHighlight(e.key.toLowerCase());
    }

    /**
     * Handle focus events
     */
    handleFocus() {
        this.elements.container.classList.add('focused');
        if (this.state.isPaused && this.state.isActive) {
            this.resume();
        }
    }

    /**
     * Handle blur events
     */
    handleBlur() {
        this.elements.container.classList.remove('focused');
        if (this.state.isActive && !this.state.isCompleted) {
            this.pause();
        }
    }

    /**
     * Handle visibility change
     */
    handleVisibilityChange() {
        if (document.hidden && this.state.isActive && !this.state.isCompleted) {
            this.pause();
        }
    }

    /**
     * Handle mobile input
     */
    handleMobileInput() {
        // Ensure input stays focused on mobile
        setTimeout(() => {
            this.elements.input.focus();
        }, 100);
    }

    /**
     * Start the typing test
     */
    start() {
        if (this.state.isActive) return;

        this.state.isActive = true;
        this.state.startTime = Date.now();
        this.state.isPaused = false;

        // Show/hide controls
        this.elements.pauseBtn.style.display = 'flex';
        this.elements.resumeBtn.style.display = 'none';

        // Start update timer
        this.startUpdateTimer();

        // Update progress text
        this.elements.progressText.textContent = 'Test in progress...';

        // Add focused class
        this.elements.container.classList.add('focused');

        // Trigger start callback
        if (this.eventHandlers.onStart) {
            this.eventHandlers.onStart();
        }

        console.log('⏱️ Typing test started');
    }

    /**
     * Pause the typing test
     */
    pause() {
        if (!this.state.isActive || this.state.isPaused || this.state.isCompleted) return;

        this.state.isPaused = true;
        
        // Show/hide controls
        this.elements.pauseBtn.style.display = 'none';
        this.elements.resumeBtn.style.display = 'flex';

        // Stop update timer
        this.stopUpdateTimer();

        // Disable input
        this.elements.input.disabled = true;

        // Update progress text
        this.elements.progressText.textContent = 'Test paused - Click resume to continue';

        // Trigger pause callback
        if (this.eventHandlers.onPause) {
            this.eventHandlers.onPause();
        }

        console.log('⏸️ Typing test paused');
    }

    /**
     * Resume the typing test
     */
    resume() {
        if (!this.state.isActive || !this.state.isPaused) return;

        this.state.isPaused = false;
        
        // Show/hide controls
        this.elements.pauseBtn.style.display = 'flex';
        this.elements.resumeBtn.style.display = 'none';

        // Enable input
        this.elements.input.disabled = false;
        this.elements.input.focus();

        // Restart update timer
        this.startUpdateTimer();

        // Update progress text
        this.elements.progressText.textContent = 'Test in progress...';

        // Trigger resume callback
        if (this.eventHandlers.onResume) {
            this.eventHandlers.onResume();
        }

        console.log('▶️ Typing test resumed');
    }

    /**
     * Complete the typing test
     */
    async complete() {
        if (this.state.isCompleted) return;

        this.state.isCompleted = true;
        this.state.isActive = false;
        this.state.endTime = Date.now();

        // Stop timers
        this.stopUpdateTimer();

        // Hide controls
        this.elements.pauseBtn.style.display = 'none';
        this.elements.resumeBtn.style.display = 'none';

        // Disable input
        this.elements.input.disabled = true;

        // Add completed class
        this.elements.container.classList.add('completed');

        // Update progress
        this.elements.progressFill.style.width = '100%';
        this.elements.progressText.textContent = 'Test completed! Great job!';

        // Final stats update
        await this.updateStats(true);

        // Trigger completion callback
        if (this.eventHandlers.onComplete) {
            this.eventHandlers.onComplete(this.getFinalResults());
        }

        // Show completion animation
        this.showCompletionAnimation();

        console.log('🎉 Typing test completed!', this.getFinalResults());
    }

    /**
     * Restart the typing test
     */
    restart() {
        // Reset state
        this.state = {
            isActive: false,
            isPaused: false,
            isCompleted: false,
            startTime: null,
            endTime: null,
            currentPosition: 0,
            typedText: '',
            errors: new Set(),
            totalKeystrokes: 0,
            correctKeystrokes: 0,
            currentWPM: 0,
            currentAccuracy: 100,
            elapsedTime: 0,
            lastUpdateTime: 0
        };

        // Reset UI
        this.elements.input.value = '';
        this.elements.input.disabled = false;
        this.elements.input.focus();

        // Reset controls
        this.elements.pauseBtn.style.display = 'none';
        this.elements.resumeBtn.style.display = 'none';
        this.elements.restartBtn.style.display = 'flex';

        // Remove classes
        this.elements.container.classList.remove('focused', 'completed', 'error');

        // Stop timers
        this.stopUpdateTimer();

        // Re-render text
        this.renderText();

        // Reset stats
        this.updateStats();

        // Reset progress
        this.elements.progressFill.style.width = '0%';
        this.elements.progressText.textContent = 'Ready to start';

        console.log('🔄 Typing test restarted');
    }

    /**
     * Update error tracking
     */
    updateErrors() {
        for (let i = 0; i < this.state.typedText.length; i++) {
            const typedChar = this.state.typedText[i];
            const originalChar = this.config.originalText[i];

            if (typedChar !== originalChar) {
                this.state.errors.add(i);
            } else {
                this.state.errors.delete(i);
                this.state.correctKeystrokes++;
            }
        }
    }

    /**
     * Update text highlighting
     */
    updateTextHighlighting() {
        const chars = this.elements.textContainer.querySelectorAll('.char');
        
        chars.forEach((char, index) => {
            const newClass = this.getCharClass(index);
            char.className = `char ${newClass}`;
        });
    }

    /**
     * Update cursor position
     */
    updateCursor() {
        const chars = this.elements.textContainer.querySelectorAll('.char');
        const currentChar = chars[this.state.currentPosition];
        
        if (currentChar) {
            const rect = currentChar.getBoundingClientRect();
            const containerRect = this.elements.textContainer.getBoundingClientRect();
            
            this.elements.cursor.style.left = `${rect.left - containerRect.left}px`;
            this.elements.cursor.style.top = `${rect.top - containerRect.top}px`;
            this.elements.cursor.style.display = 'block';
        } else {
            this.elements.cursor.style.display = 'none';
        }
    }

    /**
     * Highlight keyboard key
     */
    highlightKey(key) {
        if (!this.elements.keyboard) return;

        const keyElement = this.elements.keyboard.querySelector(`[data-key="${key}"]`);
        if (keyElement) {
            keyElement.classList.add('active');
        }
    }

    /**
     * Remove keyboard key highlight
     */
    removeKeyHighlight(key) {
        if (!this.elements.keyboard) return;

        const keyElement = this.elements.keyboard.querySelector(`[data-key="${key}"]`);
        if (keyElement) {
            keyElement.classList.remove('active');
        }
    }

    /**
     * Update keyboard highlighting for next character
     */
    updateKeyboardHighlight() {
        if (!this.elements.keyboard) return;

        // Remove all highlights
        this.elements.keyboard.querySelectorAll('.key').forEach(key => {
            key.classList.remove('next');
        });

        // Highlight next character
        const nextChar = this.config.originalText[this.state.currentPosition];
        if (nextChar) {
            const keyElement = this.elements.keyboard.querySelector(`[data-key="${nextChar.toLowerCase()}"]`);
            if (keyElement) {
                keyElement.classList.add('next');
            }
        }
    }

    /**
     * Start update timer
     */
    startUpdateTimer() {
        this.updateTimer = setInterval(() => {
            this.updateStats();
        }, this.config.updateInterval);
    }

    /**
     * Stop update timer
     */
    stopUpdateTimer() {
        if (this.updateTimer) {
            clearInterval(this.updateTimer);
            this.updateTimer = null;
        }
    }

    /**
     * Update statistics display
     */
    async updateStats(final = false) {
        if (!this.state.startTime) return;

        // Calculate elapsed time
        const now = this.state.endTime || Date.now();
        this.state.elapsedTime = Math.floor((now - this.state.startTime) / 1000);

        // Get real-time stats from API or calculate locally
        let stats;
        if (final || this.state.elapsedTime % 3 === 0) { // API call every 3 seconds or on completion
            stats = await this.getStatsFromAPI();
        } else {
            stats = this.calculateLocalStats();
        }

        // Update state
        this.state.currentWPM = stats.wpm;
        this.state.currentAccuracy = stats.accuracy;

        // Update UI
        this.elements.wpmValue.textContent = Math.round(stats.wpm);
        this.elements.accuracyValue.textContent = Math.round(stats.accuracy * 10) / 10;
        this.elements.timeValue.textContent = this.formatTime(this.state.elapsedTime);
        
        // Update progress
        const progress = (this.state.currentPosition / this.config.originalText.length) * 100;
        this.elements.progressValue.textContent = `${Math.round(progress)}%`;
        this.elements.progressFill.style.width = `${progress}%`;

        // Animate stats changes
        this.animateStatChanges();
    }

    /**
     * Get stats from API
     */
    async getStatsFromAPI() {
        try {
            const endpoint = this.config.mode === 'competition' 
                ? `/api/competitions/${this.config.competitionId}/real-time-stats`
                : this.config.apiEndpoint;

            const response = await fetch(endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.csrfToken,
                    ...(window.userData ? { 'Authorization': `Bearer ${window.userData.token}` } : {})
                },
                body: JSON.stringify({
                    original_text: this.config.originalText,
                    typed_text: this.state.typedText,
                    elapsed_seconds: this.state.elapsedTime
                })
            });

            if (!response.ok) {
                throw new Error(`API error: ${response.status}`);
            }

            const data = await response.json();
            return data.data || data;

        } catch (error) {
            console.warn('⚠️ API stats failed, using local calculation:', error);
            return this.calculateLocalStats();
        }
    }

    /**
     * Calculate stats locally
     */
    calculateLocalStats() {
        const correctChars = this.state.currentPosition - this.state.errors.size;
        const timeInMinutes = Math.max(this.state.elapsedTime / 60, 0.1);
        
        const wpm = Math.round((correctChars / 5) / timeInMinutes);
        const accuracy = this.state.currentPosition > 0 
            ? (correctChars / this.state.currentPosition) * 100 
            : 100;

        return {
            wpm: Math.max(0, wmp),
            accuracy: Math.max(0, Math.min(100, accuracy))
        };
    }

    /**
     * Animate stat changes
     */
    animateStatChanges() {
        // Add animation classes
        [this.elements.wmpValue, this.elements.accuracyValue].forEach(element => {
            element.style.transform = 'scale(1.1)';
            element.style.transition = 'transform 0.2s ease';
            
            setTimeout(() => {
                element.style.transform = 'scale(1)';
            }, 200);
        });
    }

    /**
     * Show completion animation
     */
    showCompletionAnimation() {
        // Confetti-like effect
        const container = this.elements.container;
        
        for (let i = 0; i < 50; i++) {
            const particle = document.createElement('div');
            particle.style.cssText = `
                position: absolute;
                width: 6px;
                height: 6px;
                background: ${['var(--accent-primary)', 'var(--accent-success)', 'var(--accent-secondary)'][i % 3]};
                border-radius: 50%;
                pointer-events: none;
                animation: celebrate 2s ease-out forwards;
                left: ${Math.random() * 100}%;
                top: ${Math.random() * 100}%;
                z-index: 1000;
            `;
            
            container.appendChild(particle);
            
            setTimeout(() => {
                particle.remove();
            }, 2000);
        }

        // Add celebrate animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes celebrate {
                0% {
                    transform: translateY(0) rotate(0deg) scale(0);
                    opacity: 1;
                }
                50% {
                    transform: translateY(-100px) rotate(180deg) scale(1);
                    opacity: 1;
                }
                100% {
                    transform: translateY(-200px) rotate(360deg) scale(0);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
        
        setTimeout(() => style.remove(), 3000);
    }

    /**
     * Show error message
     */
    showError(message) {
        if (window.showNotification) {
            window.showNotification(message, 'danger');
        } else {
            console.error('❌ Typing Test Error:', message);
        }
    }

    /**
     * Format time display
     */
    formatTime(seconds) {
        const mins = Math.floor(seconds / 60);
        const secs = seconds % 60;
        return `${mins}:${secs.toString().padStart(2, '0')}`;
    }

    /**
     * Get current progress data
     */
    getProgressData() {
        return {
            wpm: this.state.currentWPM,
            accuracy: this.state.currentAccuracy,
            progress: Math.round((this.state.currentPosition / this.config.originalText.length) * 100),
            position: this.state.currentPosition,
            elapsedTime: this.state.elapsedTime,
            errors: this.state.errors.size,
            isCompleted: this.state.isCompleted
        };
    }

    /**
     * Get final results
     */
    getFinalResults() {
        const totalTime = this.state.endTime - this.state.startTime;
        const timeInMinutes = totalTime / (1000 * 60);
        const correctChars = this.state.currentPosition - this.state.errors.size;
        
        return {
            wmp: this.state.currentWPM,
            accuracy: this.state.currentAccuracy,
            completionTime: Math.floor(totalTime / 1000),
            totalCharacters: this.config.originalText.length,
            correctCharacters: correctChars,
            errorCount: this.state.errors.size,
            typedText: this.state.typedText,
            rawWPM: Math.round((this.state.currentPosition / 5) / timeInMinutes),
            netWPM: Math.round((correctChars / 5) / timeInMinutes)
        };
    }

    /**
     * Destroy the typing test instance
     */
    destroy() {
        // Stop timers
        this.stopUpdateTimer();
        
        if (this.highlightTimer) {
            clearTimeout(this.highlightTimer);
        }

        // Remove event listeners
        if (this.elements.input) {
            this.elements.input.removeEventListener('input', this.handleInput);
            this.elements.input.removeEventListener('keydown', this.handleKeyDown);
            this.elements.input.removeEventListener('keyup', this.handleKeyUp);
        }

        // Clear DOM
        if (this.elements.container) {
            this.elements.container.innerHTML = '';
        }

        console.log('🗑️ TypingTest instance destroyed');
    }
}

// Export for use
window.TypingTest = TypingTest;

// Usage examples and initialization helpers
window.initTypingTest = function(config) {
    return new TypingTest(config);
};

// Auto-initialize typing tests on page load
document.addEventListener('DOMContentLoaded', function() {
    // Auto-initialize any typing areas found on the page
    const typingAreas = document.querySelectorAll('[data-typing-test]');
    
    typingAreas.forEach(area => {
        const config = {
            container: area,
            originalText: area.dataset.originalText || '',
            mode: area.dataset.mode || 'practice',
            competitionId: area.dataset.competitionId || null,
            showKeyboard: area.dataset.showKeyboard === 'true',
            ...JSON.parse(area.dataset.config || '{}')
        };
        
        new TypingTest(config);
    });
});

console.log('✅ SportTyping TypingTest engine loaded successfully!');
