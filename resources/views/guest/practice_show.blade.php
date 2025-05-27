@extends('layouts.app')

@section('content')
<div class="typing-test-container">
    <div class="container-fluid">
        <!-- Test Header -->
        <div class="test-header">
            <div class="test-info">
                <h1 id="testTitle">Typing Test</h1>
                <div class="test-meta">
                    <span class="test-mode" id="testMode">Quick Test</span>
                    <span class="test-difficulty" id="testDifficulty">Beginner</span>
                </div>
            </div>
            <div class="test-controls">
                <button class="btn btn-secondary" onclick="resetTest()">
                    <i class="fas fa-redo"></i>
                    Reset
                </button>
                <a href="{{ route('guest.practice') }}" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left"></i>
                    Back
                </a>
            </div>
        </div>

        <!-- Live Statistics -->
        <div class="live-stats">
            <div class="stat-item">
                <div class="stat-icon speed">
                    <i class="fas fa-tachometer-alt"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-value" id="currentWPM">0</span>
                    <span class="stat-label">WPM</span>
                </div>
            </div>
            <div class="stat-item">
                <div class="stat-icon accuracy">
                    <i class="fas fa-bullseye"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-value" id="currentAccuracy">0%</span>
                    <span class="stat-label">Accuracy</span>
                </div>
            </div>
            <div class="stat-item">
                <div class="stat-icon timer">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-value" id="currentTime">00:00</span>
                    <span class="stat-label" id="timeLabel">Time</span>
                </div>
            </div>
            <div class="stat-item">
                <div class="stat-icon progress">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-value" id="currentProgress">0%</span>
                    <span class="stat-label">Progress</span>
                </div>
            </div>
        </div>

        <!-- Progress Bar -->
        <div class="progress-section">
            <div class="progress-bar-container">
                <div class="progress-bar">
                    <div class="progress-fill" id="progressBar" style="width: 0%"></div>
                </div>
                <div class="progress-info">
                    <span id="progressText">0 / 0 characters</span>
                </div>
            </div>
        </div>

        <!-- Text Display -->
        <div class="text-display-section">
            <div class="text-container">
                <div class="text-content" id="textContent">
                    <!-- Text will be loaded here -->
                </div>
            </div>
        </div>

        <!-- Typing Interface -->
        <div class="typing-interface">
            <div class="typing-controls" id="typingControls">
                <button class="btn btn-primary btn-large" id="startButton" onclick="startTest()">
                    <i class="fas fa-play"></i>
                    Start Test
                </button>
                <div class="test-instructions">
                    <p><i class="fas fa-info-circle"></i> Click "Start Test" and begin typing the text above. Focus on accuracy first, then speed!</p>
                </div>
            </div>
            
            <div class="typing-area" id="typingArea" style="display: none;">
                <textarea id="typingInput" 
                          placeholder="Start typing here when ready..." 
                          rows="6"
                          disabled
                          spellcheck="false"
                          autocomplete="off"
                          autocorrect="off"
                          autocapitalize="off"></textarea>
                
                <div class="typing-feedback">
                    <div class="feedback-item">
                        <span class="feedback-label">Characters:</span>
                        <span class="feedback-value">
                            <span class="correct-chars" id="correctChars">0</span> / 
                            <span class="total-chars" id="totalChars">0</span>
                        </span>
                    </div>
                    <div class="feedback-item">
                        <span class="feedback-label">Errors:</span>
                        <span class="feedback-value error" id="errorCount">0</span>
                    </div>
                    <div class="feedback-item">
                        <span class="feedback-label">Words:</span>
                        <span class="feedback-value" id="wordCount">0</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Results Modal -->
        <div class="results-modal" id="resultsModal">
            <div class="modal-content">
                <div class="results-header">
                    <div class="completion-badge">
                        <i class="fas fa-trophy" id="completionIcon"></i>
                    </div>
                    <h2 id="resultsTitle">Test Complete!</h2>
                    <p id="resultsSubtitle">Great job! Here are your results:</p>
                </div>

                <div class="final-stats">
                    <div class="final-stat-card main-stat">
                        <div class="stat-icon">
                            <i class="fas fa-tachometer-alt"></i>
                        </div>
                        <div class="stat-info">
                            <span class="stat-number" id="finalWPM">0</span>
                            <span class="stat-unit">WPM</span>
                        </div>
                        <div class="stat-description">Words Per Minute</div>
                    </div>
                    
                    <div class="secondary-stats">
                        <div class="final-stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-bullseye"></i>
                            </div>
                            <div class="stat-info">
                                <span class="stat-number" id="finalAccuracy">0</span>
                                <span class="stat-unit">%</span>
                            </div>
                            <div class="stat-description">Accuracy</div>
                        </div>
                        
                        <div class="final-stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="stat-info">
                                <span class="stat-number" id="finalTime">0</span>
                                <span class="stat-unit">s</span>
                            </div>
                            <div class="stat-description">Total Time</div>
                        </div>
                        
                        <div class="final-stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <div class="stat-info">
                                <span class="stat-number" id="finalErrors">0</span>
                                <span class="stat-unit"></span>
                            </div>
                            <div class="stat-description">Errors</div>
                        </div>
                    </div>
                </div>

                <div class="performance-analysis">
                    <h3>Performance Analysis</h3>
                    <div class="analysis-grid">
                        <div class="analysis-item">
                            <div class="analysis-icon speed-rating" id="speedRating">
                                <i class="fas fa-star"></i>
                            </div>
                            <div class="analysis-content">
                                <h4>Speed Rating</h4>
                                <p id="speedAnalysis">Good typing speed!</p>
                            </div>
                        </div>
                        <div class="analysis-item">
                            <div class="analysis-icon accuracy-rating" id="accuracyRating">
                                <i class="fas fa-target"></i>
                            </div>
                            <div class="analysis-content">
                                <h4>Accuracy Rating</h4>
                                <p id="accuracyAnalysis">Excellent accuracy!</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="results-actions">
                    <button class="btn btn-primary" onclick="tryAgain()">
                        <i class="fas fa-redo"></i>
                        Try Again
                    </button>
                    <button class="btn btn-outline-primary" onclick="changeTest()">
                        <i class="fas fa-exchange-alt"></i>
                        Different Test
                    </button>
                    <a href="{{ route('register') }}" class="btn btn-outline-success">
                        <i class="fas fa-user-plus"></i>
                        Save Progress
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.typing-test-container {
    padding: 1rem 0;
    min-height: calc(100vh - 80px);
    background: var(--bg-primary);
}

/* Test Header */
.test-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    padding: 1.5rem 2rem;
    background: var(--gradient-card);
    backdrop-filter: blur(var(--blur-amount));
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius-lg);
    position: relative;
    overflow: hidden;
}

.test-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: var(--gradient-accent);
}

.test-info h1 {
    font-size: 1.8rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 0.5rem;
}

.test-meta {
    display: flex;
    gap: 1rem;
}

.test-mode, .test-difficulty {
    padding: 0.5rem 1rem;
    border-radius: var(--border-radius);
    font-size: 0.85rem;
    font-weight: 600;
}

.test-mode {
    background: rgba(255, 107, 157, 0.1);
    color: var(--accent-pink);
    border: 1px solid rgba(255, 107, 157, 0.2);
}

.test-difficulty {
    background: rgba(59, 130, 246, 0.1);
    color: var(--info);
    border: 1px solid rgba(59, 130, 246, 0.2);
}

.test-controls {
    display: flex;
    gap: 1rem;
}

/* Live Statistics */
.live-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-item {
    background: var(--gradient-card);
    backdrop-filter: blur(var(--blur-amount));
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius-lg);
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: all 0.3s ease;
}

.stat-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(139, 92, 246, 0.15);
}

.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    color: white;
}

.stat-icon.speed { background: var(--gradient-button); }
.stat-icon.accuracy { background: linear-gradient(45deg, #00d4ff, #0ea5e9); }
.stat-icon.timer { background: linear-gradient(45deg, #f59e0b, #eab308); }
.stat-icon.progress { background: linear-gradient(45deg, #10b981, #059669); }

.stat-content {
    display: flex;
    flex-direction: column;
}

.stat-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text-primary);
    font-family: 'Courier New', monospace;
}

.stat-label {
    font-size: 0.85rem;
    color: var(--text-secondary);
}

/* Progress Section */
.progress-section {
    margin-bottom: 2rem;
}

.progress-bar-container {
    background: var(--gradient-card);
    backdrop-filter: blur(var(--blur-amount));
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius-lg);
    padding: 1.5rem;
}

.progress-bar {
    width: 100%;
    height: 12px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 6px;
    overflow: hidden;
    margin-bottom: 1rem;
    position: relative;
}

.progress-fill {
    height: 100%;
    background: var(--gradient-button);
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

.progress-info {
    text-align: center;
    color: var(--text-secondary);
    font-size: 0.9rem;
}

/* Text Display */
.text-display-section {
    margin-bottom: 2rem;
}

.text-container {
    background: var(--gradient-card);
    backdrop-filter: blur(var(--blur-amount));
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius-lg);
    padding: 2.5rem;
    max-height: 400px;
    overflow-y: auto;
}

.text-content {
    font-family: 'Courier New', monospace;
    font-size: 1.2rem;
    line-height: 1.8;
    color: var(--text-primary);
    word-spacing: 0.2em;
    user-select: none;
}

.char-correct { 
    background: rgba(16, 185, 129, 0.2); 
    color: var(--success); 
}

.char-incorrect { 
    background: rgba(239, 68, 68, 0.3); 
    color: var(--error); 
    text-decoration: underline;
}

.char-current { 
    background: var(--accent-pink); 
    color: white;
    animation: blink 1s infinite;
}

@keyframes blink {
    0%, 50% { opacity: 1; }
    51%, 100% { opacity: 0.3; }
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

.typing-controls {
    text-align: center;
}

.btn-large {
    padding: 1.25rem 3rem;
    font-size: 1.2rem;
    font-weight: 700;
}

.test-instructions {
    margin-top: 1.5rem;
    color: var(--text-secondary);
    font-size: 0.95rem;
}

.test-instructions i {
    color: var(--accent-pink);
    margin-right: 0.5rem;
}

.typing-area {
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

#typingInput:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.typing-feedback {
    display: flex;
    justify-content: space-between;
    margin-top: 1.5rem;
    padding: 1rem;
    background: rgba(255, 255, 255, 0.03);
    border-radius: var(--border-radius);
    border: 1px solid rgba(255, 255, 255, 0.05);
}

.feedback-item {
    text-align: center;
}

.feedback-label {
    display: block;
    color: var(--text-secondary);
    font-size: 0.85rem;
    margin-bottom: 0.25rem;
}

.feedback-value {
    display: block;
    color: var(--text-primary);
    font-weight: 600;
    font-size: 1rem;
}

.feedback-value.error {
    color: var(--error);
}

.correct-chars {
    color: var(--success);
}

/* Results Modal */
.results-modal {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.9);
    backdrop-filter: blur(15px);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 1000;
    padding: 2rem;
}

.results-modal.show {
    display: flex;
    animation: fadeIn 0.3s ease-out;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.results-modal .modal-content {
    background: var(--gradient-card);
    backdrop-filter: blur(var(--blur-amount));
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius-lg);
    padding: 3rem;
    max-width: 600px;
    width: 100%;
    max-height: 90vh;
    overflow-y: auto;
    position: relative;
    animation: slideUp 0.4s ease-out;
}

@keyframes slideUp {
    from { transform: translateY(50px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}

.results-modal .modal-content::before {
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
    margin-bottom: 3rem;
}

.completion-badge {
    width: 100px;
    height: 100px;
    background: var(--gradient-button);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 2rem;
    font-size: 3rem;
    color: white;
    box-shadow: 0 10px 30px rgba(255, 107, 157, 0.4);
    animation: bounce 2s infinite;
}

@keyframes bounce {
    0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
    40% { transform: translateY(-10px); }
    60% { transform: translateY(-5px); }
}

.results-header h2 {
    font-size: 2rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 0.5rem;
}

.results-header p {
    color: var(--text-secondary);
    font-size: 1.1rem;
}

.final-stats {
    margin-bottom: 3rem;
}

.main-stat {
    margin-bottom: 2rem;
}

.final-stat-card {
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius);
    padding: 2rem;
    text-align: center;
    transition: all 0.3s ease;
}

.final-stat-card:hover {
    transform: translateY(-2px);
    border-color: var(--accent-pink);
}

.final-stat-card.main-stat {
    padding: 3rem;
    border-color: var(--accent-pink);
    background: rgba(255, 107, 157, 0.05);
}

.final-stat-card .stat-icon {
    width: 60px;
    height: 60px;
    background: var(--gradient-button);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    font-size: 1.5rem;
    color: white;
}

.final-stat-card.main-stat .stat-icon {
    width: 80px;
    height: 80px;
    font-size: 2rem;
}

.stat-info {
    display: flex;
    align-items: baseline;
    justify-content: center;
    gap: 0.5rem;
    margin-bottom: 0.5rem;
}

.stat-number {
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--text-primary);
    font-family: 'Courier New', monospace;
}

.final-stat-card.main-stat .stat-number {
    font-size: 4rem;
}

.stat-unit {
    font-size: 1.5rem;
    color: var(--accent-pink);
    font-weight: 600;
}

.stat-description {
    color: var(--text-secondary);
    font-size: 0.9rem;
}

.secondary-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 1rem;
}

.performance-analysis {
    margin-bottom: 3rem;
}

.performance-analysis h3 {
    color: var(--text-primary);
    font-size: 1.3rem;
    font-weight: 600;
    margin-bottom: 1.5rem;
    text-align: center;
}

.analysis-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
}

.analysis-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.5rem;
    background: rgba(255, 255, 255, 0.03);
    border-radius: var(--border-radius);
    border: 1px solid rgba(255, 255, 255, 0.05);
}

.analysis-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    color: white;
}

.analysis-icon.speed-rating { background: var(--gradient-button); }
.analysis-icon.accuracy-rating { background: linear-gradient(45deg, #00d4ff, #0ea5e9); }

.analysis-content h4 {
    color: var(--text-primary);
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.analysis-content p {
    color: var(--text-secondary);
    font-size: 0.9rem;
}

.results-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

/* Responsive */
@media (max-width: 1024px) {
    .test-header {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
    
    .live-stats {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .live-stats {
        grid-template-columns: 1fr;
    }
    
    .typing-feedback {
        flex-direction: column;
        gap: 1rem;
    }
    
    .secondary-stats {
        grid-template-columns: 1fr;
    }
    
    .analysis-grid {
        grid-template-columns: 1fr;
    }
    
    .results-actions {
        flex-direction: column;
    }
    
    .text-content {
        font-size: 1.1rem;
    }
    
    #typingInput {
        font-size: 1rem;
    }
}
</style>

<script>
// Test configuration and state
let testConfig = {
    mode: 'quick',
    duration: 0,
    difficulty: 'beginner',
    category: 'random',
    customText: ''
};

let testState = {
    started: false,
    finished: false,
    startTime: null,
    endTime: null,
    timer: null,
    currentText: '',
    typedText: '',
    errors: 0,
    correctChars: 0
};

// Sample texts for different categories
const sampleTexts = {
    programming: [
        "JavaScript is a versatile programming language used for web development. It allows developers to create interactive websites and web applications. With ES6 features like arrow functions, promises, and async/await, modern JavaScript has become more powerful and easier to use.",
        "Python is known for its simple syntax and readability. It supports multiple programming paradigms including object-oriented, functional, and procedural programming. Popular frameworks like Django and Flask make web development efficient and scalable.",
    ],
    literature: [
        "In the quiet moments of dawn, when the world holds its breath between night and day, there exists a profound beauty that speaks to the soul. The gentle whisper of wind through ancient trees carries stories of countless generations who have witnessed this same sacred transition.",
        "The art of storytelling has been humanity's companion since the dawn of civilization. Through myths, legends, and tales passed down through generations, we preserve not just information, but the very essence of human experience and wisdom."
    ],
    science: [
        "The theory of evolution by natural selection explains how species change over time through the process of genetic variation and environmental pressure. Charles Darwin's groundbreaking work fundamentally changed our understanding of life on Earth.",
        "Photosynthesis is the process by which plants convert light energy into chemical energy. This remarkable biological process not only sustains plant life but also produces the oxygen we breathe and forms the foundation of almost all food chains."
    ],
    business: [
        "Effective leadership requires a combination of vision, communication skills, and emotional intelligence. Modern leaders must adapt to rapidly changing market conditions while maintaining team motivation and organizational culture.",
        "Digital transformation has revolutionized how businesses operate and compete. Companies that embrace technology and data-driven decision making are better positioned to succeed in today's dynamic marketplace."
    ],
    technology: [
        "Artificial intelligence and machine learning are transforming industries across the globe. From healthcare diagnostics to autonomous vehicles, AI applications are becoming increasingly sophisticated and integrated into our daily lives.",
        "Cloud computing has democratized access to powerful computing resources. Small startups can now leverage the same infrastructure as large corporations, enabling innovation and scalability at unprecedented levels."
    ],
    random: [
        "The quick brown fox jumps over the lazy dog. This pangram contains every letter of the English alphabet and has been used for typing practice for decades. It helps develop muscle memory and finger coordination.",
        "Did you know that honey never spoils? Archaeologists have found pots of honey in ancient Egyptian tombs that are over 3000 years old and still perfectly edible. This is due to honey's low moisture content and acidic pH."
    ]
};

// Initialize test on page load
document.addEventListener('DOMContentLoaded', function() {
    initializeTest();
    setupEventListeners();
});

function initializeTest() {
    // Get URL parameters
    const urlParams = new URLSearchParams(window.location.search);
    testConfig.mode = urlParams.get('mode') || 'quick';
    testConfig.duration = parseInt(urlParams.get('duration')) || 0;
    testConfig.difficulty = urlParams.get('difficulty') || 'beginner';
    testConfig.category = urlParams.get('category') || 'random';
    
    // Load test text
    loadTestText();
    
    // Update UI
    updateTestHeader();
    updateStats();
}

function loadTestText() {
    let text = '';
    
    switch (testConfig.mode) {
        case 'custom':
            text = localStorage.getItem('sporttyping_custom_text') || sampleTexts.random[0];
            break;
        case 'category':
            const categoryTexts = sampleTexts[testConfig.category] || sampleTexts.random;
            text = categoryTexts[Math.floor(Math.random() * categoryTexts.length)];
            break;
        case 'timed':
            const timedTexts = sampleTexts.random;
            text = timedTexts[Math.floor(Math.random() * timedTexts.length)];
            break;
        default: // quick
            text = sampleTexts.random[Math.floor(Math.random() * sampleTexts.random.length)];
    }
    
    testState.currentText = text;
    document.getElementById('textContent').textContent = text;
    document.getElementById('totalChars').textContent = text.length;
    document.getElementById('progressText').textContent = 0 / ${text.length} characters;
}

function updateTestHeader() {
    const titles = {
        quick: 'Quick Typing Test',
        timed: 'Timed Typing Test',
        category: ${testConfig.category.charAt(0).toUpperCase() + testConfig.category.slice(1)} Practice,
        custom: 'Custom Text Practice'
    };
    
    document.getElementById('testTitle').textContent = titles[testConfig.mode];
    document.getElementById('testMode').textContent = titles[testConfig.mode];
    document.getElementById('testDifficulty').textContent = testConfig.difficulty.charAt(0).toUpperCase() + testConfig.difficulty.slice(1);
    
    // Update time label for timed tests
    if (testConfig.mode === 'timed' && testConfig.duration > 0) {
        document.getElementById('timeLabel').textContent = 'Remaining';
        document.getElementById('currentTime').textContent = formatTime(testConfig.duration);
    }
}

function setupEventListeners() {
    const typingInput = document.getElementById('typingInput');
    
    typingInput.addEventListener('input', handleTyping);
    typingInput.addEventListener('paste', e => e.preventDefault());
    
    // Prevent common shortcuts that might interfere
    typingInput.addEventListener('keydown', function(e) {
        if (e.ctrlKey && (e.key === 'a' || e.key === 'c' || e.key === 'v')) {
            if (e.key !== 'a') { // Allow select all
                e.preventDefault();
            }
        }
    });
}

function startTest() {
    testState.started = true;
    testState.startTime = new Date();
    
    // Update UI
    document.getElementById('typingControls').style.display = 'none';
    document.getElementById('typingArea').style.display = 'block';
    document.getElementById('typingInput').disabled = false;
    document.getElementById('typingInput').focus();
    
    // Start timer
    startTimer();
    
    // Highlight first character
    highlightCurrentChar(0);
}

function startTimer() {
    testState.timer = setInterval(() => {
        if (!testState.started || testState.finished) {
            clearInterval(testState.timer);
            return;
        }
        
        const elapsed = (new Date() - testState.startTime) / 1000;
        
        if (testConfig.mode === 'timed' && testConfig.duration > 0) {
            const remaining = Math.max(0, testConfig.duration - elapsed);
            document.getElementById('currentTime').textContent = formatTime(remaining);
            
            if (remaining <= 0) {
                finishTest();
            }
        } else {
            document.getElementById('currentTime').textContent = formatTime(elapsed);
        }
        
        updateStats();
    }, 100);
}

function handleTyping(e) {
    if (!testState.started || testState.finished) return;
    
    testState.typedText = e.target.value;
    const progress = Math.min(100, (testState.typedText.length / testState.currentText.length) * 100);
    
    // Update progress
    document.getElementById('progressBar').style.width = progress + '%';
    document.getElementById('currentProgress').textContent = Math.round(progress) + '%';
    document.getElementById('progressText').textContent = ${testState.typedText.length} / ${testState.currentText.length} characters;
    
    // Update text highlighting
    highlightText();
    
    // Update live stats
    updateStats();
    
    // Check if test is complete
    if (testState.typedText.length >= testState.currentText.length) {
        finishTest();
    }
}

function highlightText() {
    let highlighted = '';
    let errors = 0;
    let correctChars = 0;
    
    for (let i = 0; i < testState.currentText.length; i++) {
        const originalChar = testState.currentText[i];
        const typedChar = testState.typedText[i];
        
        if (i < testState.typedText.length) {
            if (typedChar === originalChar) {
                highlighted += <span class="char-correct">${originalChar}</span>;
                correctChars++;
            } else {
                highlighted += <span class="char-incorrect">${originalChar}</span>;
                errors++;
            }
        } else if (i === testState.typedText.length) {
            highlighted += <span class="char-current">${originalChar}</span>;
        } else {
            highlighted += originalChar;
        }
    }
    
    document.getElementById('textContent').innerHTML = highlighted;
    
    // Update feedback
    testState.errors = errors;
    testState.correctChars = correctChars;
    
    document.getElementById('correctChars').textContent = correctChars;
    document.getElementById('errorCount').textContent = errors;
    document.getElementById('wordCount').textContent = Math.max(1, testState.typedText.split(/\s+/).length);
}

function highlightCurrentChar(position) {
    let highlighted = '';
    for (let i = 0; i < testState.currentText.length; i++) {
        if (i === position) {
            highlighted += <span class="char-current">${testState.currentText[i]}</span>;
        } else {
            highlighted += testState.currentText[i];
        }
    }
    document.getElementById('textContent').innerHTML = highlighted;
}

function updateStats() {
    if (!testState.started) return;
    
    const elapsedTime = (new Date() - testState.startTime) / 1000;
    const typedWords = Math.max(1, testState.typedText.split(/\s+/).length);
    const wpm = Math.round((typedWords / elapsedTime) * 60) || 0;
    
    // Calculate accuracy
    const accuracy = testState.typedText.length > 0 ? 
        Math.round((testState.correctChars / testState.typedText.length) * 100) : 100;
    
    // Update live stats
    document.getElementById('currentWPM').textContent = wpm;
    document.getElementById('currentAccuracy').textContent = accuracy + '%';
}

function formatTime(seconds) {
    const mins = Math.floor(seconds / 60);
    const secs = Math.floor(seconds % 60);
    return ${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')};
}

function finishTest() {
    if (testState.finished) return;
    
    testState.finished = true;
    testState.endTime = new Date();
    
    // Stop timer
    clearInterval(testState.timer);
    
    // Disable input
    document.getElementById('typingInput').disabled = true;
    
    // Calculate final stats
    const totalTime = (testState.endTime - testState.startTime) / 1000;
    const typedWords = Math.max(1, testState.typedText.split(/\s+/).length);
    const finalWPM = Math.round((typedWords / totalTime) * 60);
    const finalAccuracy = testState.typedText.length > 0 ? 
        Math.round((testState.correctChars / testState.typedText.length) * 100) : 0;
    
    // Show results
    showResults({
        wpm: finalWPM,
        accuracy: finalAccuracy,
        time: Math.round(totalTime),
        errors: testState.errors,
        mode: testConfig.mode
    });
}

function showResults(results) {
    // Update result displays
    document.getElementById('finalWPM').textContent = results.wpm;
    document.getElementById('finalAccuracy').textContent = results.accuracy;
    document.getElementById('finalTime').textContent = results.time;
    document.getElementById('finalErrors').textContent = results.errors;
    
    // Performance analysis
    let speedAnalysis = '';
    let accuracyAnalysis = '';
    let completionIcon = 'fa-trophy';
    
    if (results.wpm >= 70) {
        speedAnalysis = 'Excellent speed! You\'re a fast typist.';
    } else if (results.wpm >= 50) {
        speedAnalysis = 'Good speed! Above average typing skills.';
    } else if (results.wpm >= 30) {
        speedAnalysis = 'Decent speed. Keep practicing to improve!';
    } else {
        speedAnalysis = 'Focus on building speed gradually.';
    }
    
    if (results.accuracy >= 95) {
        accuracyAnalysis = 'Outstanding accuracy! Very precise typing.';
    } else if (results.accuracy >= 90) {
        accuracyAnalysis = 'Good accuracy! Well done.';
    } else if (results.accuracy >= 80) {
        accuracyAnalysis = 'Decent accuracy. Focus on precision.';
    } else {
        accuracyAnalysis = 'Work on accuracy before building speed.';
        completionIcon = 'fa-exclamation-triangle';
    }
    
    document.getElementById('speedAnalysis').textContent = speedAnalysis;
    document.getElementById('accuracyAnalysis').textContent = accuracyAnalysis;
    document.getElementById('completionIcon').className = fas ${completionIcon};
    
    // Save to guest session if available
    if (typeof window.addGuestResult === 'function') {
        window.addGuestResult(results);
    }
    
    // Show modal
    document.getElementById('resultsModal').classList.add('show');
}

function resetTest() {
    // Reset state
    testState = {
        started: false,
        finished: false,
        startTime: null,
        endTime: null,
        timer: null,
        currentText: testState.currentText,
        typedText: '',
        errors: 0,
        correctChars: 0
    };
    
    // Clear timer
    clearInterval(testState.timer);
    
    // Reset UI
    document.getElementById('typingControls').style.display = 'block';
    document.getElementById('typingArea').style.display = 'none';
    document.getElementById('typingInput').value = '';
    document.getElementById('typingInput').disabled = true;
    document.getElementById('resultsModal').classList.remove('show');
    
    // Reset progress
    document.getElementById('progressBar').style.width = '0%';
    document.getElementById('currentProgress').textContent = '0%';
    document.getElementById('progressText').textContent = 0 / ${testState.currentText.length} characters;
    
    // Reset stats
    document.getElementById('currentWPM').textContent = '0';
    document.getElementById('currentAccuracy').textContent = '0%';
    document.getElementById('currentTime').textContent = testConfig.mode === 'timed' && testConfig.duration > 0 ? 
        formatTime(testConfig.duration) : '00:00';
    
    // Reset text highlighting
    document.getElementById('textContent').textContent = testState.currentText;
    
    // Reset feedback
    document.getElementById('correctChars').textContent = '0';
    document.getElementById('errorCount').textContent = '0';
    document.getElementById('wordCount').textContent = '0';
}

function tryAgain() {
    document.getElementById('resultsModal').classList.remove('show');
    resetTest();
}

function changeTest() {
    window.location.href = '{{ route("guest.practice") }}';
}
</script>
@endsection