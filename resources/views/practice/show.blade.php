@extends('layouts.app')

@section('content')
<div class="practice-test-container">
    <div class="container-fluid">
        <!-- Test Header -->
        <div class="test-header">
            <div class="test-info">
                <div class="breadcrumb">
                    <a href="{{ route('practice.index') }}">
                        <i class="fas fa-arrow-left"></i>
                        Back to Practice
                    </a>
                </div>
                <h1 id="testTitle">Smart Practice</h1>
                <div class="test-meta">
                    <span class="test-mode" id="testMode">AI-Powered</span>
                    <span class="test-difficulty" id="testDifficulty">Adaptive</span>
                    <span class="user-level">{{ Auth::user()->profile->league->name ?? 'Novice' }}</span>
                </div>
            </div>
            <div class="test-controls">
                <button class="btn btn-secondary" onclick="resetTest()">
                    <i class="fas fa-redo"></i>
                    Reset
                </button>
                <button class="btn btn-outline-primary" onclick="saveAndExit()">
                    <i class="fas fa-save"></i>
                    Save & Exit
                </button>
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
                    <div class="stat-comparison">
                        <span class="comparison-text" id="wpmComparison">Your avg: {{ number_format(Auth::user()->profile->typing_speed_avg ?? 0, 0) }}</span>
                    </div>
                </div>
            </div>
            <div class="stat-item">
                <div class="stat-icon accuracy">
                    <i class="fas fa-bullseye"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-value" id="currentAccuracy">100%</span>
                    <span class="stat-label">Accuracy</span>
                    <div class="stat-comparison">
                        <span class="comparison-text" id="accuracyComparison">Your avg: {{ number_format(Auth::user()->profile->typing_accuracy_avg ?? 0, 1) }}%</span>
                    </div>
                </div>
            </div>
            <div class="stat-item">
                <div class="stat-icon timer">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-value" id="currentTime">00:00</span>
                    <span class="stat-label" id="timeLabel">Time</span>
                    <div class="stat-comparison">
                        <span class="comparison-text" id="timeComparison">Target: 5:00</span>
                    </div>
                </div>
            </div>
            <div class="stat-item">
                <div class="stat-icon experience">
                    <i class="fas fa-star"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-value" id="currentXP">0</span>
                    <span class="stat-label">XP Earned</span>
                    <div class="stat-comparison">
                        <span class="comparison-text">Bonus available!</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Progress Section -->
        <div class="progress-section">
            <div class="progress-container">
                <div class="progress-header">
                    <span class="progress-label">Progress</span>
                    <span class="progress-percentage" id="progressPercentage">0%</span>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill" id="progressBar" style="width: 0%"></div>
                    <div class="progress-milestones">
                        <div class="milestone" style="left: 25%">
                            <i class="fas fa-flag"></i>
                            <span>25%</span>
                        </div>
                        <div class="milestone" style="left: 50%">
                            <i class="fas fa-flag"></i>
                            <span>50%</span>
                        </div>
                        <div class="milestone" style="left: 75%">
                            <i class="fas fa-flag"></i>
                            <span>75%</span>
                        </div>
                    </div>
                </div>
                <div class="progress-info">
                    <span id="progressText">0 / 0 characters completed</span>
                </div>
            </div>
        </div>

        <!-- Text Display -->
        <div class="text-display-section">
            <div class="text-container">
                <div class="text-header">
                    <div class="text-info">
                        <span class="text-category" id="textCategory">Programming</span>
                        <span class="text-difficulty" id="textDifficultyBadge">Intermediate</span>
                    </div>
                    <div class="text-actions">
                        <button class="text-action-btn" onclick="toggleFocus()" id="focusBtn">
                            <i class="fas fa-eye"></i>
                            Focus Mode
                        </button>
                        <button class="text-action-btn" onclick="adjustFontSize()">
                            <i class="fas fa-font"></i>
                            Font Size
                        </button>
                    </div>
                </div>
                <div class="text-content" id="textContent">
                    <!-- Text will be loaded here -->
                </div>
            </div>
        </div>

        <!-- Typing Interface -->
        <div class="typing-interface">
            <div class="typing-controls" id="typingControls">
                <div class="control-group">
                    <button class="btn btn-primary btn-large" id="startButton" onclick="startTest()">
                        <i class="fas fa-play"></i>
                        Start Practice
                    </button>
                    <div class="control-options">
                        <label class="control-option">
                            <input type="checkbox" id="soundEnabled" checked>
                            <i class="fas fa-volume-up"></i>
                            Sound Effects
                        </label>
                        <label class="control-option">
                            <input type="checkbox" id="mistakeHighlight" checked>
                            <i class="fas fa-exclamation-triangle"></i>
                            Highlight Mistakes
                        </label>
                    </div>
                </div>
                <div class="test-instructions">
                    <p><i class="fas fa-info-circle"></i> Focus on accuracy first, then build speed. Good luck!</p>
                </div>
            </div>
            
            <div class="typing-area" id="typingArea" style="display: none;">
                <div class="typing-input-container">
                    <textarea id="typingInput" 
                              class="typing-input"
                              placeholder="Start typing here when ready..." 
                              rows="6"
                              disabled
                              spellcheck="false"
                              autocomplete="off"
                              autocorrect="off"
                              autocapitalize="off"></textarea>
                    
                    <div class="input-overlay">
                        <div class="cursor-line" id="cursorLine"></div>
                        <div class="mistake-indicators" id="mistakeIndicators"></div>
                    </div>
                </div>
                
                <div class="typing-feedback">
                    <div class="feedback-row">
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
                        <div class="feedback-item">
                            <span class="feedback-label">Streak:</span>
                            <span class="feedback-value success" id="streakCount">0</span>
                        </div>
                    </div>
                    
                    <div class="performance-indicators">
                        <div class="performance-item">
                            <div class="performance-icon" id="speedIndicator">
                                <i class="fas fa-tachometer-alt"></i>
                            </div>
                            <span class="performance-label">Speed</span>
                        </div>
                        <div class="performance-item">
                            <div class="performance-icon" id="accuracyIndicator">
                                <i class="fas fa-bullseye"></i>
                            </div>
                            <span class="performance-label">Accuracy</span>
                        </div>
                        <div class="performance-item">
                            <div class="performance-icon" id="consistencyIndicator">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <span class="performance-label">Consistency</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Results Modal -->
        <div class="results-modal" id="resultsModal">
            <div class="modal-content">
                <div class="results-header">
                    <div class="completion-badge" id="completionBadge">
                        <i class="fas fa-trophy" id="completionIcon"></i>
                    </div>
                    <h2 id="resultsTitle">Practice Complete!</h2>
                    <p id="resultsSubtitle">Great job! Here are your results:</p>
                </div>

                <div class="final-stats">
                    <div class="primary-stats">
                        <div class="final-stat-card main-stat">
                            <div class="stat-icon">
                                <i class="fas fa-tachometer-alt"></i>
                            </div>
                            <div class="stat-info">
                                <span class="stat-number" id="finalWPM">0</span>
                                <span class="stat-unit">WPM</span>
                            </div>
                            <div class="stat-comparison">
                                <span id="wpmImprovement">+5 from your average</span>
                            </div>
                        </div>
                        
                        <div class="final-stat-card main-stat">
                            <div class="stat-icon">
                                <i class="fas fa-bullseye"></i>
                            </div>
                            <div class="stat-info">
                                <span class="stat-number" id="finalAccuracy">0</span>
                                <span class="stat-unit">%</span>
                            </div>
                            <div class="stat-comparison">
                                <span id="accuracyImprovement">Perfect accuracy!</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="secondary-stats">
                        <div class="final-stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="stat-info">
                                <span class="stat-number" id="finalTime">0</span>
                                <span class="stat-unit">min</span>
                            </div>
                            <div class="stat-description">Total Time</div>
                        </div>
                        
                        <div class="final-stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-star"></i>
                            </div>
                            <div class="stat-info">
                                <span class="stat-number" id="finalXP">0</span>
                                <span class="stat-unit">XP</span>
                            </div>
                            <div class="stat-description">Experience Gained</div>
                        </div>
                        
                        <div class="final-stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-fire"></i>
                            </div>
                            <div class="stat-info">
                                <span class="stat-number" id="finalStreak">0</span>
                                <span class="stat-unit">day</span>
                            </div>
                            <div class="stat-description">Daily Streak</div>
                        </div>
                    </div>
                </div>

                <div class="achievement-section" id="achievementSection" style="display: none;">
                    <h3>🎉 New Achievement Unlocked!</h3>
                    <div class="achievement-card">
                        <div class="achievement-icon">
                            <i class="fas fa-medal" id="achievementIcon"></i>
                        </div>
                        <div class="achievement-info">
                            <h4 id="achievementName">Speed Demon</h4>
                            <p id="achievementDescription">Reached 50 WPM for the first time!</p>
                        </div>
                    </div>
                </div>

                <div class="improvement-tips">
                    <h3>💡 Tips for Improvement</h3>
                    <div class="tips-grid" id="improvementTips">
                        <!-- Tips will be populated by JavaScript -->
                    </div>
                </div>

                <div class="results-actions">
                    <button class="btn btn-primary" onclick="saveAndContinue()">
                        <i class="fas fa-save"></i>
                        Save Results
                    </button>
                    <button class="btn btn-outline-primary" onclick="tryAgain()">
                        <i class="fas fa-redo"></i>
                        Try Again
                    </button>
                    <button class="btn btn-outline-success" onclick="nextPractice()">
                        <i class="fas fa-arrow-right"></i>
                        Next Practice
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.practice-test-container {
    padding: 1rem 0;
    min-height: calc(100vh - 80px);
    background: var(--bg-primary);
}

/* Test Header */
.test-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 2rem;
    padding: 2rem;
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

.breadcrumb a {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--text-secondary);
    text-decoration: none;
    font-size: 0.9rem;
    margin-bottom: 1rem;
    transition: color 0.3s ease;
}

.breadcrumb a:hover {
    color: var(--accent-pink);
}

.test-info h1 {
    font-size: 2rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 1rem;
}

.test-meta {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.test-mode, .test-difficulty, .user-level {
    padding: 0.5rem 1rem;
    border-radius: var(--border-radius);
    font-size: 0.85rem;
    font-weight: 600;
}

.test-mode {
    background: rgba(139, 92, 246, 0.1);
    color: var(--accent-purple);
    border: 1px solid rgba(139, 92, 246, 0.2);
}

.test-difficulty {
    background: rgba(245, 158, 11, 0.1);
    color: var(--warning);
    border: 1px solid rgba(245, 158, 11, 0.2);
}

.user-level {
    background: rgba(255, 107, 157, 0.1);
    color: var(--accent-pink);
    border: 1px solid rgba(255, 107, 157, 0.2);
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
.stat-icon.experience { background: linear-gradient(45deg, #10b981, #059669); }

.stat-content {
    flex: 1;
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
    margin-bottom: 0.25rem;
}

.stat-comparison {
    margin-top: 0.25rem;
}

.comparison-text {
    font-size: 0.8rem;
    color: var(--text-muted);
}

/* Progress Section */
.progress-section {
    margin-bottom: 2rem;
}

.progress-container {
    background: var(--gradient-card);
    backdrop-filter: blur(var(--blur-amount));
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius-lg);
    padding: 2rem;
}

.progress-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.progress-label {
    color: var(--text-primary);
    font-weight: 600;
}

.progress-percentage {
    color: var(--accent-pink);
    font-weight: 700;
    font-family: 'Courier New', monospace;
}

.progress-bar {
    position: relative;
    width: 100%;
    height: 12px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 6px;
    overflow: hidden;
    margin-bottom: 1rem;
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

.progress-milestones {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
}

.milestone {
    position: absolute;
    top: -8px;
    transform: translateX(-50%);
    display: flex;
    flex-direction: column;
    align-items: center;
}

.milestone i {
    color: var(--accent-pink);
    font-size: 0.8rem;
    margin-bottom: 0.25rem;
}

.milestone span {
    font-size: 0.7rem;
    color: var(--text-secondary);
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
    overflow: hidden;
}

.text-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem 2rem;
    background: rgba(255, 255, 255, 0.05);
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.text-info {
    display: flex;
    gap: 1rem;
}

.text-category, .text-difficulty {
    padding: 0.5rem 1rem;
    border-radius: var(--border-radius);
    font-size: 0.85rem;
    font-weight: 600;
}

.text-category {
    background: rgba(59, 130, 246, 0.1);
    color: var(--info);
    border: 1px solid rgba(59, 130, 246, 0.2);
}

.text-difficulty {
    background: rgba(245, 158, 11, 0.1);
    color: var(--warning);
    border: 1px solid rgba(245, 158, 11, 0.2);
}

.text-actions {
    display: flex;
    gap: 0.5rem;
}

.text-action-btn {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    color: var(--text-secondary);
    padding: 0.5rem 1rem;
    border-radius: var(--border-radius);
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.85rem;
}

.text-action-btn:hover {
    border-color: var(--accent-pink);
    color: var(--text-primary);
}

.text-content {
    padding: 2.5rem;
    font-family: 'Courier New', monospace;
    font-size: 1.2rem;
    line-height: 1.8;
    color: var(--text-primary);
    word-spacing: 0.2em;
    user-select: none;
    max-height: 400px;
    overflow-y: auto;
}

.text-content.focus-mode {
    background: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(5px);
}

.char-correct { 
    background: rgba(16, 185, 129, 0.2); 
    color: var(--success); 
}

.char-incorrect { 
    background: rgba(239, 68, 68, 0.3); 
    color: var(--error); 
    text-decoration: underline;
    animation: shake 0.3s ease-in-out;
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

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-2px); }
    75% { transform: translateX(2px); }
}

@keyframes shimmer {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(100%); }
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

.control-group {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 2rem;
}

.btn-large {
    padding: 1.25rem 3rem;
    font-size: 1.2rem;
    font-weight: 700;
}

.control-options {
    display: flex;
    gap: 2rem;
    flex-wrap: wrap;
    justify-content: center;
}

.control-option {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    color: var(--text-secondary);
    cursor: pointer;
    font-size: 0.9rem;
    transition: color 0.3s ease;
}

.control-option:hover {
    color: var(--text-primary);
}

.control-option input[type="checkbox"] {
    width: 16px;
    height: 16px;
    accent-color: var(--accent-pink);
}

.test-instructions {
    margin-top: 2rem;
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

.typing-input-container {
    position: relative;
    margin-bottom: 2rem;
}

.typing-input {
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

.typing-input:focus {
    outline: none;
    border-color: var(--accent-pink);
    background: rgba(255, 255, 255, 0.08);
    box-shadow: 0 0 0 3px rgba(255, 107, 157, 0.1);
}

.typing-input:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.input-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    pointer-events: none;
}

.cursor-line {
    position: absolute;
    width: 2px;
    height: 20px;
    background: var(--accent-pink);
    animation: blink 1s infinite;
}

.mistake-indicators {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
}

.typing-feedback {
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

.feedback-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 1rem;
}

.feedback-item {
    text-align: center;
    padding: 1rem;
    background: rgba(255, 255, 255, 0.03);
    border-radius: var(--border-radius);
    border: 1px solid rgba(255, 255, 255, 0.05);
}

.feedback-label {
    display: block;
    color: var(--text-secondary);
    font-size: 0.85rem;
    margin-bottom: 0.5rem;
}

.feedback-value {
    display: block;
    color: var(--text-primary);
    font-weight: 600;
    font-size: 1.1rem;
}

.feedback-value.error {
    color: var(--error);
}

.feedback-value.success {
    color: var(--success);
}

.correct-chars {
    color: var(--success);
}

.performance-indicators {
    display: flex;
    justify-content: center;
    gap: 3rem;
}

.performance-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
}

.performance-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    color: white;
    transition: all 0.3s ease;
    background: rgba(255, 255, 255, 0.1);
}

.performance-icon.good {
    background: var(--gradient-button);
    animation: pulse 2s infinite;
}

.performance-icon.excellent {
    background: linear-gradient(45deg, #10b981, #059669);
    animation: pulse 2s infinite;
}

.performance-label {
    color: var(--text-secondary);
    font-size: 0.8rem;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
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

.results-modal .modal-content {
    background: var(--gradient-card);
    backdrop-filter: blur(var(--blur-amount));
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius-lg);
    padding: 3rem;
    max-width: 700px;
    width: 100%;
    max-height: 90vh;
    overflow-y: auto;
    position: relative;
    animation: slideUp 0.4s ease-out;
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

.completion-badge.excellent {
    background: var(--gradient-button);
}

.completion-badge.good {
    background: linear-gradient(45deg, #00d4ff, #0ea5e9);
}

.completion-badge.average {
    background: linear-gradient(45deg, #f59e0b, #eab308);
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

.primary-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 2rem;
    margin-bottom: 2rem;
}

.secondary-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 1.5rem;
}

.final-stat-card {
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius);
    padding: 2rem;
    text-align: center;
    transition: all 0.3s ease;
}

.final-stat-card.main-stat {
    border-color: var(--accent-pink);
    background: rgba(255, 107, 157, 0.05);
}

.final-stat-card:hover {
    transform: translateY(-2px);
    border-color: var(--accent-pink);
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

.stat-info {
    display: flex;
    align-items: baseline;
    justify-content: center;
    gap: 0.5rem;
    margin-bottom: 0.5rem;
}

.stat-number {
    font-size: 2rem;
    font-weight: 700;
    color: var(--text-primary);
    font-family: 'Courier New', monospace;
}

.stat-unit {
    font-size: 1.2rem;
    color: var(--accent-pink);
    font-weight: 600;
}

.stat-comparison, .stat-description {
    color: var(--text-secondary);
    font-size: 0.9rem;
}

.achievement-section {
    background: rgba(255, 107, 157, 0.05);
    border: 1px solid rgba(255, 107, 157, 0.2);
    border-radius: var(--border-radius);
    padding: 2rem;
    margin-bottom: 2rem;
    text-align: center;
}

.achievement-section h3 {
    color: var(--text-primary);
    margin-bottom: 1.5rem;
}

.achievement-card {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    justify-content: center;
}

.achievement-icon {
    width: 60px;
    height: 60px;
    background: var(--gradient-button);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
}

.achievement-info h4 {
    color: var(--text-primary);
    margin-bottom: 0.5rem;
}

.achievement-info p {
    color: var(--text-secondary);
}

.improvement-tips {
    margin-bottom: 3rem;
}

.improvement-tips h3 {
    color: var(--text-primary);
    margin-bottom: 1.5rem;
    text-align: center;
}

.tips-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1rem;
}

.tip-item {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    padding: 1rem;
    background: rgba(255, 255, 255, 0.03);
    border-radius: var(--border-radius);
    border: 1px solid rgba(255, 255, 255, 0.05);
}

.tip-icon {
    color: var(--accent-pink);
    font-size: 1.2rem;
    margin-top: 0.25rem;
    flex-shrink: 0;
}

.tip-content h4 {
    color: var(--text-primary);
    font-size: 0.95rem;
    margin-bottom: 0.5rem;
}

.tip-content p {
    color: var(--text-secondary);
    font-size: 0.85rem;
    line-height: 1.4;
}

.results-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

/* Animations */
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slideUp {
    from { transform: translateY(50px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}

@keyframes bounce {
    0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
    40% { transform: translateY(-10px); }
    60% { transform: translateY(-5px); }
}

/* Responsive */
@media (max-width: 1024px) {
    .test-header {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
    
    .text-header {
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
    
    .feedback-row {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .performance-indicators {
        gap: 1.5rem;
    }
    
    .primary-stats, .secondary-stats {
        grid-template-columns: 1fr;
    }
    
    .results-actions {
        flex-direction: column;
    }
    
    .control-options {
        flex-direction: column;
        gap: 1rem;
    }
    
    .text-content {
        font-size: 1.1rem;
    }
    
    .typing-input {
        font-size: 1rem;
    }
}
</style>

<script>
// Test configuration and state
let testConfig = {
    mode: 'smart',
    category: 'programming',
    difficulty: 'intermediate',
    duration: 0,
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
    correctChars: 0,
    streak: 0,
    maxStreak: 0
};

// User stats for comparison
const userStats = {
    avgWPM: {{ Auth::user()->profile->typing_speed_avg ?? 0 }},
    avgAccuracy: {{ Auth::user()->profile->typing_accuracy_avg ?? 0 }},
    totalXP: {{ Auth::user()->profile->total_experience ?? 0 }}
};

// Sample texts for different modes
const practiceTexts = {
    smart: [
        "Artificial intelligence and machine learning are transforming how we approach problem-solving in software development. These technologies enable us to create more efficient algorithms and automate complex tasks that were previously time-consuming.",
        "The principles of object-oriented programming include encapsulation, inheritance, and polymorphism. These concepts help developers create maintainable and scalable software applications."
    ],
    speed: [
        "Quick brown fox jumps over lazy dog. The five boxing wizards jump quickly. Pack my box with five dozen liquor jugs. How vexingly quick daft zebras jump!",
        "Speed typing requires consistent practice and proper finger placement. Focus on accuracy first, then gradually increase your typing speed while maintaining precision."
    ],
    accuracy: [
        "Precision in typing requires careful attention to detail and consistent finger placement. Each keystroke should be deliberate and accurate, avoiding the temptation to rush through the text.",
        "Accuracy training focuses on reducing errors and building muscle memory. Take your time to ensure each character is typed correctly before moving to the next."
    ]
};

// DOM elements
const startButton = document.getElementById('startButton');
const typingArea = document.getElementById('typingArea');
const typingControls = document.getElementById('typingControls');
const typingInput = document.getElementById('typingInput');
const textContent = document.getElementById('textContent');
const resultsModal = document.getElementById('resultsModal');

// Initialize test on page load
document.addEventListener('DOMContentLoaded', function() {
    initializeTest();
    setupEventListeners();
});

function initializeTest() {
    // Get URL parameters
    const urlParams = new URLSearchParams(window.location.search);
    testConfig.mode = urlParams.get('mode') || 'smart';
    testConfig.category = urlParams.get('category') || 'programming';
    testConfig.difficulty = urlParams.get('difficulty') || 'intermediate';
    testConfig.duration = parseInt(urlParams.get('duration')) || 0;
    
    // Load custom text if available
    if (testConfig.mode === 'custom') {
        testConfig.customText = sessionStorage.getItem('custom_practice_text') || '';
    }
    
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
            text = testConfig.customText || practiceTexts.smart[0];
            break;
        case 'smart':
        case 'speed':
        case 'accuracy':
            const modeTexts = practiceTexts[testConfig.mode] || practiceTexts.smart;
            text = modeTexts[Math.floor(Math.random() * modeTexts.length)];
            break;
        default:
            text = practiceTexts.smart[0];
    }
    
    testState.currentText = text;
    document.getElementById('textContent').textContent = text;
    document.getElementById('totalChars').textContent = text.length;
    document.getElementById('progressText').textContent = 0 / ${text.length} characters completed;
}

function updateTestHeader() {
    const titles = {
        smart: 'Smart Practice',
        speed: 'Speed Building',
        accuracy: 'Accuracy Training',
        custom: 'Custom Practice'
    };
    
    const modes = {
        smart: 'AI-Powered',
        speed: 'Speed Focus',
        accuracy: 'Precision Mode',
        custom: 'Custom Text'
    };
    
    document.getElementById('testTitle').textContent = titles[testConfig.mode];
    document.getElementById('testMode').textContent = modes[testConfig.mode];
    document.getElementById('textCategory').textContent = testConfig.category.charAt(0).toUpperCase() + testConfig.category.slice(1);
    document.getElementById('textDifficultyBadge').textContent = testConfig.difficulty.charAt(0).toUpperCase() + testConfig.difficulty.slice(1);
    
    // Update time label for timed tests
    if (testConfig.duration > 0) {
        document.getElementById('timeLabel').textContent = 'Remaining';
        document.getElementById('currentTime').textContent = formatTime(testConfig.duration);
        document.getElementById('timeComparison').textContent = Duration: ${Math.floor(testConfig.duration / 60)}:${(testConfig.duration % 60).toString().padStart(2, '0')};
    }
}

function setupEventListeners() {
    typingInput.addEventListener('input', handleTyping);
    typingInput.addEventListener('paste', e => e.preventDefault());
    
    // Prevent common shortcuts that might interfere
    typingInput.addEventListener('keydown', function(e) {
        if (e.ctrlKey && (e.key === 'a' || e.key === 'c' || e.key === 'v')) {
            if (e.key !== 'a') { // Allow select all
                e.preventDefault();
            }
        }
        
        // Play sound effects if enabled
        if (document.getElementById('soundEnabled').checked && testState.started) {
            playTypingSound(e.key);
        }
    });
}

function startTest() {
    testState.started = true;
    testState.startTime = new Date();
    
    // Update UI
    typingControls.style.display = 'none';
    typingArea.style.display = 'block';
    typingInput.disabled = false;
    typingInput.focus();
    
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
        
        if (testConfig.duration > 0) {
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
    document.getElementById('progressPercentage').textContent = Math.round(progress) + '%';
    document.getElementById('progressText').textContent = ${testState.typedText.length} / ${testState.currentText.length} characters completed;
    
    // Update text highlighting
    highlightText();
    
    // Update live stats
    updateStats();
    
    // Update performance indicators
    updatePerformanceIndicators();
    
    // Check if test is complete
    if (testState.typedText.length >= testState.currentText.length) {
        finishTest();
    }
}

function highlightText() {
    let highlighted = '';
    let errors = 0;
    let correctChars = 0;
    let currentStreak = 0;
    
    for (let i = 0; i < testState.currentText.length; i++) {
        const originalChar = testState.currentText[i];
        const typedChar = testState.typedText[i];
        
        if (i < testState.typedText.length) {
            if (typedChar === originalChar) {
                highlighted += <span class="char-correct">${originalChar}</span>;
                correctChars++;
                currentStreak++;
            } else {
                highlighted += <span class="char-incorrect">${originalChar}</span>;
                errors++;
                currentStreak = 0;
            }
        } else if (i === testState.typedText.length) {
            highlighted += <span class="char-current">${originalChar}</span>;
        } else {
            highlighted += originalChar;
        }
    }
    
    document.getElementById('textContent').innerHTML = highlighted;
    
    // Update state
    testState.errors = errors;
    testState.correctChars = correctChars;
    testState.streak = currentStreak;
    testState.maxStreak = Math.max(testState.maxStreak, currentStreak);
    
    // Update feedback
    document.getElementById('correctChars').textContent = correctChars;
    document.getElementById('errorCount').textContent = errors;
    document.getElementById('wordCount').textContent = Math.max(1, testState.typedText.split(/\s+/).length);
    document.getElementById('streakCount').textContent = testState.maxStreak;
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
    
    // Calculate XP based on performance
    const baseXP = Math.floor(testState.typedText.length / 10);
    const speedBonus = Math.max(0, wpm - userStats.avgWPM) * 2;
    const accuracyBonus = accuracy >= 95 ? 50 : 0;
    const streakBonus = testState.maxStreak >= 50 ? 25 : 0;
    const totalXP = baseXP + speedBonus + accuracyBonus + streakBonus;
    
    // Update live stats
    document.getElementById('currentWPM').textContent = wpm;
    document.getElementById('currentAccuracy').textContent = accuracy + '%';
    document.getElementById('currentXP').textContent = Math.round(totalXP);
    
    // Update comparisons
    const wpmDiff = wmp - userStats.avgWPM;
    const accuracyDiff = accuracy - userStats.avgAccuracy;
    
    document.getElementById('wpmComparison').textContent = wmpDiff >= 0 ? 
        +${wmpDiff} from avg : ${wmpDiff} from avg;
    document.getElementById('accuracyComparison').textContent = accuracyDiff >= 0 ? 
        +${accuracyDiff.toFixed(1)}% from avg : ${accuracyDiff.toFixed(1)}% from avg;
}

function updatePerformanceIndicators() {
    const elapsedTime = (new Date() - testState.startTime) / 1000;
    const typedWords = Math.max(1, testState.typedText.split(/\s+/).length);
    const wpm = Math.round((typedWords / elapsedTime) * 60) || 0;
    const accuracy = testState.typedText.length > 0 ? 
        Math.round((testState.correctChars / testState.typedText.length) * 100) : 100;
    
    // Speed indicator
    const speedIndicator = document.getElementById('speedIndicator');
    if (wpm >= userStats.avgWPM + 10) {
        speedIndicator.className = 'performance-icon excellent';
    } else if (wpm >= userStats.avgWPM) {
        speedIndicator.className = 'performance-icon good';
    } else {
        speedIndicator.className = 'performance-icon';
    }
    
    // Accuracy indicator
    const accuracyIndicator = document.getElementById('accuracyIndicator');
    if (accuracy >= 98) {
        accuracyIndicator.className = 'performance-icon excellent';
    } else if (accuracy >= 95) {
        accuracyIndicator.className = 'performance-icon good';
    } else {
        accuracyIndicator.className = 'performance-icon';
    }
    
    // Consistency indicator (based on streak)
    const consistencyIndicator = document.getElementById('consistencyIndicator');
    if (testState.maxStreak >= 50) {
        consistencyIndicator.className = 'performance-icon excellent';
    } else if (testState.maxStreak >= 25) {
        consistencyIndicator.className = 'performance-icon good';
    } else {
        consistencyIndicator.className = 'performance-icon';
    }
}

function formatTime(seconds) {
    const mins = Math.floor(seconds / 60);
    const secs = Math.floor(seconds % 60);
    return ${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')};
}

function playTypingSound(key) {
    // Simple typing sound effect
    const audioContext = new (window.AudioContext || window.webkitAudioContext)();
    const oscillator = audioContext.createOscillator();
    const gainNode = audioContext.createGain();
    
    oscillator.connect(gainNode);
    gainNode.connect(audioContext.destination);
    
    oscillator.frequency.setValueAtTime(800, audioContext.currentTime);
    gainNode.gain.setValueAtTime(0.1, audioContext.currentTime);
    gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.1);
    
    oscillator.start();
    oscillator.stop(audioContext.currentTime + 0.1);
}

function toggleFocus() {
    const textContent = document.getElementById('textContent');
    const focusBtn = document.getElementById('focusBtn');
    
    textContent.classList.toggle('focus-mode');
    
    if (textContent.classList.contains('focus-mode')) {
        focusBtn.innerHTML = '<i class="fas fa-eye-slash"></i> Exit Focus';
    } else {
        focusBtn.innerHTML = '<i class="fas fa-eye"></i> Focus Mode';
    }
}

function adjustFontSize() {
    const textContent = document.getElementById('textContent');
    const currentSize = parseFloat(window.getComputedStyle(textContent).fontSize);
    const newSize = currentSize >= 20 ? 16 : currentSize + 2;
    textContent.style.fontSize = newSize + 'px';
}

function finishTest() {
    if (testState.finished) return;
    
    testState.finished = true;
    testState.endTime = new Date();
    
    // Stop timer
    clearInterval(testState.timer);
    
    // Disable input
    typingInput.disabled = true;
    
    // Calculate final stats
    const totalTime = (testState.endTime - testState.startTime) / 1000;
    const typedWords = Math.max(1, testState.typedText.split(/\s+/).length);
    const finalWPM = Math.round((typedWords / totalTime) * 60);
    const finalAccuracy = testState.typedText.length > 0 ? 
        Math.round((testState.correctChars / testState.typedText.length) * 100) : 0;
    
    // Calculate XP
    const baseXP = Math.floor(testState.typedText.length / 10);
    const speedBonus = Math.max(0, finalWPM - userStats.avgWPM) * 2;
    const accuracyBonus = finalAccuracy >= 95 ? 50 : 0;
    const streakBonus = testState.maxStreak >= 50 ? 25 : 0;
    const finalXP = baseXP + speedBonus + accuracyBonus + streakBonus;
    
    // Show results
    showResults({
        wpm: finalWPM,
        accuracy: finalAccuracy,
        time: Math.round(totalTime),
        errors: testState.errors,
        xp: Math.round(finalXP),
        streak: testState.maxStreak,
        mode: testConfig.mode,
        category: testConfig.category
    });
}

function showResults(results) {
    // Update result displays
    document.getElementById('finalWPM').textContent = results.wpm;
    document.getElementById('finalAccuracy').textContent = results.accuracy;
    document.getElementById('finalTime').textContent = Math.floor(results.time / 60) + ':' + (results.time % 60).toString().padStart(2, '0');
    document.getElementById('finalXP').textContent = results.xp;
    document.getElementById('finalStreak').textContent = '8'; // Daily streak from user data
    
    // Performance analysis
    const wmpImprovement = results.wpm - userStats.avgWPM;
    const accuracyImprovement = results.accuracy - userStats.avgAccuracy;
    
    document.getElementById('wpmImprovement').textContent = wmpImprovement >= 0 ? 
        +${wmpImprovement} from your average : ${wmpImprovement} from your average;
    document.getElementById('accuracyImprovement').textContent = accuracyImprovement >= 0 ? 
        +${accuracyImprovement.toFixed(1)}% improvement : ${accuracyImprovement.toFixed(1)}% from average;
    
    // Set completion badge and title
    const completionBadge = document.getElementById('completionBadge');
    const completionIcon = document.getElementById('completionIcon');
    const resultsTitle = document.getElementById('resultsTitle');
    
    if (results.accuracy >= 95 && results.wpm >= userStats.avgWPM + 5) {
        completionBadge.className = 'completion-badge excellent';
        completionIcon.className = 'fas fa-crown';
        resultsTitle.textContent = 'Excellent Performance!';
    } else if (results.accuracy >= 90 && results.wpm >= userStats.avgWPM) {
        completionBadge.className = 'completion-badge good';
        completionIcon.className = 'fas fa-trophy';
        resultsTitle.textContent = 'Great Job!';
    } else {
        completionBadge.className = 'completion-badge average';
        completionIcon.className = 'fas fa-medal';
        resultsTitle.textContent = 'Practice Complete!';
    }
    
    // Check for achievements
    checkAchievements(results);
    
    // Generate improvement tips
    generateImprovementTips(results);
    
    // Show modal
    resultsModal.classList.add('show');
}

function checkAchievements(results) {
    const achievementSection = document.getElementById('achievementSection');
    
    // Example achievement check
    if (results.wpm >= 50 && userStats.avgWPM < 50) {
        achievementSection.style.display = 'block';
        document.getElementById('achievementName').textContent = 'Speed Demon';
        document.getElementById('achievementDescription').textContent = 'Reached 50 WPM for the first time!';
        document.getElementById('achievementIcon').className = 'fas fa-rocket';
    } else if (results.accuracy === 100) {
        achievementSection.style.display = 'block';
        document.getElementById('achievementName').textContent = 'Perfect Precision';
        document.getElementById('achievementDescription').textContent = 'Achieved 100% accuracy!';
        document.getElementById('achievementIcon').className = 'fas fa-bullseye';
    }
}

function generateImprovementTips(results) {
    const tipsContainer = document.getElementById('improvementTips');
    const tips = [];
    
    if (results.accuracy < 95) {
        tips.push({
            icon: 'fa-target',
            title: 'Focus on Accuracy',
            content: 'Slow down and focus on typing each character correctly. Speed will naturally improve with practice.'
        });
    }
    
    if (results.wpm < userStats.avgWPM) {
        tips.push({
            icon: 'fa-tachometer-alt',
            title: 'Build Speed Gradually',
            content: 'Practice regularly with shorter sessions. Maintain good posture and use all 10 fingers.'
        });
    }
    
    if (testState.maxStreak < 25) {
        tips.push({
            icon: 'fa-chart-line',
            title: 'Improve Consistency',
            content: 'Work on maintaining steady rhythm. Avoid rushing through difficult words or sections.'
        });
    }
    
    // Always add at least one tip
    if (tips.length === 0) {
        tips.push({
            icon: 'fa-star',
            title: 'Keep Practicing',
            content: 'Excellent work! Continue practicing regularly to maintain and improve your skills.'
        });
    }
    
    tipsContainer.innerHTML = tips.map(tip => `
        <div class="tip-item">
            <div class="tip-icon">
                <i class="fas ${tip.icon}"></i>
            </div>
            <div class="tip-content">
                <h4>${tip.title}</h4>
                <p>${tip.content}</p>
            </div>
        </div>
    `).join('');
}

function resetTest() {
    testState = {
        started: false,
        finished: false,
        startTime: null,
        endTime: null,
        timer: null,
        currentText: testState.currentText,
        typedText: '',
        errors: 0,
        correctChars: 0,
        streak: 0,
        maxStreak: 0
    };
    
    // Clear timer
    clearInterval(testState.timer);
    
    // Reset UI
    typingControls.style.display = 'block';
    typingArea.style.display = 'none';
    typingInput.value = '';
    typingInput.disabled = true;
    resultsModal.classList.remove('show');
    
    // Reset progress
    document.getElementById('progressBar').style.width = '0%';
    document.getElementById('progressPercentage').textContent = '0%';
    document.getElementById('progressText').textContent = 0 / ${testState.currentText.length} characters completed;
    
    // Reset stats
    resetStats();
    
    // Reset text highlighting
    document.getElementById('textContent').textContent = testState.currentText;
}

function resetStats() {
    document.getElementById('currentWPM').textContent = '0';
    document.getElementById('currentAccuracy').textContent = '100%';
    document.getElementById('currentTime').textContent = testConfig.duration > 0 ? 
        formatTime(testConfig.duration) : '00:00';
    document.getElementById('currentXP').textContent = '0';
    
    document.getElementById('correctChars').textContent = '0';
    document.getElementById('errorCount').textContent = '0';
    document.getElementById('wordCount').textContent = '0';
    document.getElementById('streakCount').textContent = '0';
    
    // Reset performance indicators
    ['speedIndicator', 'accuracyIndicator', 'consistencyIndicator'].forEach(id => {
        document.getElementById(id).className = 'performance-icon';
    });
}

function saveAndExit() {
    if (testState.started && !testState.finished) {
        if (confirm('Are you sure you want to exit? Your progress will be lost.')) {
            window.location.href = '{{ route("practice.index") }}';
        }
    } else {
        window.location.href = '{{ route("practice.index") }}';
    }
}

function saveAndContinue() {
    // In a real application, this would save the results to the database
    const results = {
        wpm: parseInt(document.getElementById('finalWPM').textContent),
        accuracy: parseInt(document.getElementById('finalAccuracy').textContent),
        time: document.getElementById('finalTime').textContent,
        xp: parseInt(document.getElementById('finalXP').textContent),
        mode: testConfig.mode,
        category: testConfig.category,
        text_length: testState.currentText.length,
        errors: testState.errors
    };
    
    console.log('Saving results:', results);
    
    // Close modal and redirect
    resultsModal.classList.remove('show');
    window.location.href = '{{ route("practice.index") }}';
}

function tryAgain() {
    resultsModal.classList.remove('show');
    resetTest();
}

function nextPractice() {
    // Generate next practice session
    window.location.href = '{{ route("practice.show") }}?mode=smart';
}
</script>
@endsection