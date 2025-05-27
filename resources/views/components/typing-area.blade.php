<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SportTyping - Typing Area</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #1a1a2e 0%, #2d1b69 50%, #0f0f23 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            color: #fff;
            overflow-x: hidden;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 2rem;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1.5rem;
            font-weight: bold;
            color: #ff6b9d;
        }

        .nav-links {
            display: flex;
            gap: 2rem;
            list-style: none;
        }

        .nav-links a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .nav-links a:hover {
            color: #ff6b9d;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        .typing-interface {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .stats-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .timer {
            font-size: 2rem;
            font-weight: bold;
            color: #4facfe;
        }

        .stats-grid {
            display: flex;
            gap: 2rem;
            flex-wrap: wrap;
        }

        .stat-item {
            text-align: center;
        }

        .stat-value {
            font-size: 1.8rem;
            font-weight: bold;
            color: #ff6b9d;
            display: block;
        }

        .stat-label {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.7);
            margin-top: 0.25rem;
        }

        .text-display {
            background: rgba(0, 0, 0, 0.3);
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 1.5rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
            position: relative;
            overflow: hidden;
        }

        .text-content {
            font-size: 1.2rem;
            line-height: 1.8;
            font-family: 'Courier New', monospace;
            white-space: pre-wrap;
            position: relative;
            z-index: 2;
        }

        .char {
            position: relative;
            transition: all 0.15s ease;
        }

        .char.correct {
            background: rgba(76, 175, 80, 0.3);
            color: #4caf50;
            border-radius: 3px;
        }

        .char.incorrect {
            background: rgba(244, 67, 54, 0.3);
            color: #f44336;
            border-radius: 3px;
        }

        .char.current {
            background: rgba(255, 107, 157, 0.5);
            animation: pulse 1s infinite;
            border-radius: 3px;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        .typing-input {
            width: 100%;
            background: rgba(255, 255, 255, 0.05);
            border: 2px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 1rem;
            color: #fff;
            font-size: 1.1rem;
            font-family: 'Courier New', monospace;
            resize: none;
            outline: none;
            transition: all 0.3s ease;
        }

        .typing-input:focus {
            border-color: #ff6b9d;
            box-shadow: 0 0 20px rgba(255, 107, 157, 0.3);
        }

        .control-buttons {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-top: 1.5rem;
            flex-wrap: wrap;
        }

        .btn {
            background: linear-gradient(45deg, #ff6b9d, #4facfe);
            border: none;
            border-radius: 25px;
            padding: 0.75rem 2rem;
            color: white;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 1rem;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(255, 107, 157, 0.4);
        }

        .btn:active {
            transform: translateY(0);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 25px rgba(255, 255, 255, 0.1);
        }

        .progress-bar {
            width: 100%;
            height: 8px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 4px;
            margin: 1rem 0;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #ff6b9d, #4facfe);
            border-radius: 4px;
            width: 0%;
            transition: width 0.3s ease;
        }

        .accuracy-indicator {
            display: flex;
            gap: 0.5rem;
            margin-top: 1rem;
            flex-wrap: wrap;
        }

        .accuracy-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }

        .accuracy-dot.correct {
            background: #4caf50;
            box-shadow: 0 0 8px rgba(76, 175, 80, 0.5);
        }

        .accuracy-dot.incorrect {
            background: #f44336;
            box-shadow: 0 0 8px rgba(244, 67, 54, 0.5);
        }

        .results-panel {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 2rem;
            text-align: center;
            display: none;
        }

        .results-panel.show {
            display: block;
            animation: slideUp 0.5s ease;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .results-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
            margin: 2rem 0;
        }

        .result-card {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 15px;
            padding: 1.5rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .result-value {
            font-size: 2.5rem;
            font-weight: bold;
            color: #ff6b9d;
            margin-bottom: 0.5rem;
        }

        .result-label {
            color: rgba(255, 255, 255, 0.7);
            font-size: 1.1rem;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .navbar {
                padding: 1rem;
                flex-direction: column;
                gap: 1rem;
            }

            .nav-links {
                gap: 1rem;
            }

            .container {
                padding: 1rem;
            }

            .stats-grid {
                justify-content: center;
            }

            .text-content {
                font-size: 1rem;
            }

            .control-buttons {
                flex-direction: column;
                align-items: center;
            }

            .btn {
                width: 200px;
            }
        }

        /* Additional animations */
        .floating-particle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: rgba(255, 107, 157, 0.6);
            border-radius: 50%;
            animation: float 4s infinite linear;
        }

        @keyframes float {
            0% {
                transform: translateY(100vh) rotate(0deg);
                opacity: 0;
            }
            10% {
                opacity: 1;
            }
            90% {
                opacity: 1;
            }
            100% {
                transform: translateY(-100px) rotate(360deg);
                opacity: 0;
            }
        }
    </style>
</head>
<body>
    <!-- Background particles -->
    <div class="floating-particle" style="left: 10%; animation-delay: 0s;"></div>
    <div class="floating-particle" style="left: 20%; animation-delay: 1s;"></div>
    <div class="floating-particle" style="left: 30%; animation-delay: 2s;"></div>
    <div class="floating-particle" style="left: 40%; animation-delay: 3s;"></div>
    <div class="floating-particle" style="left: 50%; animation-delay: 4s;"></div>
    <div class="floating-particle" style="left: 60%; animation-delay: 5s;"></div>
    <div class="floating-particle" style="left: 70%; animation-delay: 6s;"></div>
    <div class="floating-particle" style="left: 80%; animation-delay: 7s;"></div>
    <div class="floating-particle" style="left: 90%; animation-delay: 8s;"></div>

    <!-- Navigation -->
    <nav class="navbar">
        <div class="logo">
            ⌨ SportTyping
        </div>
        <ul class="nav-links">
            <li><a href="#">Practice</a></li>
            <li><a href="#">Lessons</a></li>
            <li><a href="#">Competitions</a></li>
            <li><a href="#">Login</a></li>
            <li><a href="#">Register</a></li>
        </ul>
    </nav>

    <div class="container">
        <!-- Main Typing Interface -->
        <div class="typing-interface">
            <div class="stats-header">
                <div class="timer" id="timer">60</div>
                <div class="stats-grid">
                    <div class="stat-item">
                        <span class="stat-value" id="wpm">0</span>
                        <div class="stat-label">WPM</div>
                    </div>
                    <div class="stat-item">
                        <span class="stat-value" id="accuracy">100%</span>
                        <div class="stat-label">Accuracy</div>
                    </div>
                    <div class="stat-item">
                        <span class="stat-value" id="errors">0</span>
                        <div class="stat-label">Errors</div>
                    </div>
                </div>
            </div>

            <div class="progress-bar">
                <div class="progress-fill" id="progressFill"></div>
            </div>

            <div class="text-display">
                <div class="text-content" id="textContent">
                    The quick brown fox jumps over the lazy dog. This pangram contains every letter of the alphabet at least once, making it perfect for typing practice. Regular typing practice helps improve both speed and accuracy, which are essential skills in today's digital world.
                </div>
            </div>

            <textarea 
                class="typing-input" 
                id="typingInput" 
                placeholder="Start typing here..."
                rows="4"
                disabled
            ></textarea>

            <div class="control-buttons">
                <button class="btn" id="startBtn">Start Test</button>
                <button class="btn btn-secondary" id="resetBtn">Reset</button>
                <button class="btn btn-secondary" id="newTextBtn">New Text</button>
            </div>

            <div class="accuracy-indicator" id="accuracyDots"></div>
        </div>

        <!-- Results Panel -->
        <div class="results-panel" id="resultsPanel">
            <h2 style="color: #ff6b9d; margin-bottom: 2rem;">Test Results</h2>
            <div class="results-grid">
                <div class="result-card">
                    <div class="result-value" id="finalWpm">0</div>
                    <div class="result-label">Words Per Minute</div>
                </div>
                <div class="result-card">
                    <div class="result-value" id="finalAccuracy">0%</div>
                    <div class="result-label">Accuracy</div>
                </div>
                <div class="result-card">
                    <div class="result-value" id="finalTime">0s</div>
                    <div class="result-label">Time</div>
                </div>
                <div class="result-card">
                    <div class="result-value" id="finalErrors">0</div>
                    <div class="result-label">Total Errors</div>
                </div>
            </div>
            <button class="btn" onclick="resetTest()">Try Again</button>
        </div>
    </div>

    <script>
        class TypingTest {
            constructor() {
                this.testTexts = [
                    "The quick brown fox jumps over the lazy dog. This pangram contains every letter of the alphabet at least once, making it perfect for typing practice. Regular typing practice helps improve both speed and accuracy, which are essential skills in today's digital world.",
                    "JavaScript is a versatile programming language that powers the modern web. From simple website interactions to complex web applications, JavaScript enables developers to create dynamic and engaging user experiences across different platforms and devices.",
                    "Climate change represents one of the most significant challenges of our time. Rising global temperatures, melting ice caps, and extreme weather patterns are clear indicators that immediate action is required to protect our planet for future generations.",
                    "The advancement of artificial intelligence has revolutionized many industries. Machine learning algorithms can now process vast amounts of data, recognize patterns, and make predictions that were once thought impossible for computers to achieve."
                ];
                
                this.currentText = this.testTexts[0];
                this.startTime = null;
                this.testDuration = 60;
                this.isActive = false;
                this.timer = null;
                this.currentPosition = 0;
                this.errors = 0;
                this.totalTyped = 0;
                
                this.initializeElements();
                this.setupEventListeners();
                this.renderText();
            }

            initializeElements() {
                this.elements = {
                    timer: document.getElementById('timer'),
                    wpm: document.getElementById('wpm'),
                    accuracy: document.getElementById('accuracy'),
                    errors: document.getElementById('errors'),
                    textContent: document.getElementById('textContent'),
                    typingInput: document.getElementById('typingInput'),
                    startBtn: document.getElementById('startBtn'),
                    resetBtn: document.getElementById('resetBtn'),
                    newTextBtn: document.getElementById('newTextBtn'),
                    progressFill: document.getElementById('progressFill'),
                    accuracyDots: document.getElementById('accuracyDots'),
                    resultsPanel: document.getElementById('resultsPanel')
                };
            }

            setupEventListeners() {
                this.elements.startBtn.addEventListener('click', () => this.startTest());
                this.elements.resetBtn.addEventListener('click', () => this.resetTest());
                this.elements.newTextBtn.addEventListener('click', () => this.newText());
                this.elements.typingInput.addEventListener('input', (e) => this.handleInput(e));
                this.elements.typingInput.addEventListener('keydown', (e) => this.handleKeyDown(e));
            }

            renderText() {
                const textHtml = this.currentText.split('').map((char, index) => 
                    <span class="char" data-index="${index}">${char === ' ' ? '&nbsp;' : char}</span>
                ).join('');
                
                this.elements.textContent.innerHTML = textHtml;
                this.updateCurrentChar();
            }

            updateCurrentChar() {
                const chars = this.elements.textContent.querySelectorAll('.char');
                chars.forEach(char => char.classList.remove('current'));
                
                if (this.currentPosition < chars.length) {
                    chars[this.currentPosition].classList.add('current');
                }
            }

            startTest() {
                this.isActive = true;
                this.startTime = Date.now();
                this.elements.typingInput.disabled = false;
                this.elements.typingInput.focus();
                this.elements.startBtn.textContent = 'Testing...';
                this.elements.startBtn.disabled = true;
                
                this.timer = setInterval(() => {
                    const elapsed = Math.floor((Date.now() - this.startTime) / 1000);
                    const remaining = Math.max(0, this.testDuration - elapsed);
                    this.elements.timer.textContent = remaining;
                    
                    if (remaining === 0) {
                        this.endTest();
                    }
                }, 1000);

                this.updateAccuracyDots();
            }

            endTest() {
                this.isActive = false;
                clearInterval(this.timer);
                this.elements.typingInput.disabled = true;
                this.showResults();
            }

            resetTest() {
                this.isActive = false;
                clearInterval(this.timer);
                this.currentPosition = 0;
                this.errors = 0;
                this.totalTyped = 0;
                this.elements.timer.textContent = '60';
                this.elements.wpm.textContent = '0';
                this.elements.accuracy.textContent = '100%';
                this.elements.errors.textContent = '0';
                this.elements.typingInput.value = '';
                this.elements.typingInput.disabled = true;
                this.elements.startBtn.textContent = 'Start Test';
                this.elements.startBtn.disabled = false;
                this.elements.progressFill.style.width = '0%';
                this.elements.resultsPanel.classList.remove('show');
                this.elements.accuracyDots.innerHTML = '';
                
                this.renderText();
            }

            newText() {
                this.resetTest();
                const randomIndex = Math.floor(Math.random() * this.testTexts.length);
                this.currentText = this.testTexts[randomIndex];
                this.renderText();
            }

            handleInput(e) {
                if (!this.isActive) return;

                const inputValue = e.target.value;
                const chars = this.elements.textContent.querySelectorAll('.char');
                
                // Update character states
                for (let i = 0; i < chars.length; i++) {
                    const char = chars[i];
                    char.classList.remove('correct', 'incorrect', 'current');
                    
                    if (i < inputValue.length) {
                        const expectedChar = this.currentText[i];
                        const typedChar = inputValue[i];
                        
                        if (expectedChar === typedChar) {
                            char.classList.add('correct');
                        } else {
                            char.classList.add('incorrect');
                        }
                    }
                }

                this.currentPosition = inputValue.length;
                this.updateCurrentChar();
                this.updateStats();
                this.updateProgress();
                this.updateAccuracyDots();

                // Auto-complete test if user finishes typing
                if (inputValue.length >= this.currentText.length) {
                    this.endTest();
                }
            }

            handleKeyDown(e) {
                if (!this.isActive) return;
                this.totalTyped++;
            }

            updateStats() {
                const inputValue = this.elements.typingInput.value;
                const elapsed = (Date.now() - this.startTime) / 1000 / 60; // minutes
                
                // Calculate WPM (words per minute)
                const wordsTyped = inputValue.length / 5; // 5 characters = 1 word
                const wpm = Math.round(wordsTyped / elapsed) || 0;
                
                // Calculate accuracy
                let correctChars = 0;
                for (let i = 0; i < inputValue.length; i++) {
                    if (i < this.currentText.length && inputValue[i] === this.currentText[i]) {
                        correctChars++;
                    }
                }
                
                const accuracy = inputValue.length > 0 ? Math.round((correctChars / inputValue.length) * 100) : 100;
                this.errors = inputValue.length - correctChars;
                
                this.elements.wpm.textContent = wpm;
                this.elements.accuracy.textContent = accuracy + '%';
                this.elements.errors.textContent = this.errors;
            }

            updateProgress() {
                const progress = (this.elements.typingInput.value.length / this.currentText.length) * 100;
                this.elements.progressFill.style.width = Math.min(progress, 100) + '%';
            }

            updateAccuracyDots() {
                const inputValue = this.elements.typingInput.value;
                const dotsHtml = [];
                
                for (let i = 0; i < Math.min(inputValue.length, 50); i++) {
                    const isCorrect = i < this.currentText.length && inputValue[i] === this.currentText[i];
                    dotsHtml.push(<div class="accuracy-dot ${isCorrect ? 'correct' : 'incorrect'}"></div>);
                }
                
                this.elements.accuracyDots.innerHTML = dotsHtml.join('');
            }

            showResults() {
                const inputValue = this.elements.typingInput.value;
                const testTime = this.testDuration;
                
                // Calculate final stats
                const wordsTyped = inputValue.length / 5;
                const finalWpm = Math.round(wordsTyped / (testTime / 60));
                
                let correctChars = 0;
                for (let i = 0; i < inputValue.length; i++) {
                    if (i < this.currentText.length && inputValue[i] === this.currentText[i]) {
                        correctChars++;
                    }
                }
                
                const finalAccuracy = inputValue.length > 0 ? Math.round((correctChars / inputValue.length) * 100) : 100;
                const finalErrors = inputValue.length - correctChars;
                
                // Update results display
                document.getElementById('finalWpm').textContent = finalWpm;
                document.getElementById('finalAccuracy').textContent = finalAccuracy + '%';
                document.getElementById('finalTime').textContent = testTime + 's';
                document.getElementById('finalErrors').textContent = finalErrors;
                
                this.elements.resultsPanel.classList.add('show');
            }
        }

        // Global function for reset button in results
        function resetTest() {
            if (window.typingTest) {
                window.typingTest.resetTest();
            }
        }

        // Initialize typing test when page loads
        window.addEventListener('DOMContentLoaded', () => {
            window.typingTest = new TypingTest();
        });

        // Add some visual enhancements
        document.addEventListener('mousemove', (e) => {
            const cursor = document.querySelector('.cursor');
            if (!cursor) {
                const cursorElement = document.createElement('div');
                cursorElement.className = 'cursor';
                cursorElement.style.cssText = `
                    position: fixed;
                    width: 20px;
                    height: 20px;
                    background: radial-gradient(circle, rgba(255,107,157,0.3), transparent);
                    border-radius: 50%;
                    pointer-events: none;
                    z-index: 9999;
                    mix-blend-mode: difference;
                `;
                document.body.appendChild(cursorElement);
            }
            
            const cursorElement = document.querySelector('.cursor');
            cursorElement.style.left = e.clientX - 10 + 'px';
            cursorElement.style.top = e.clientY - 10 + 'px';
        });
    </script>
</body>
</html>