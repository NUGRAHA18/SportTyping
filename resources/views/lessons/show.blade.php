@extends('layouts.app')

@section('content')
<div class="lesson-container">
    <div class="container">
        <!-- Lesson Header -->
        <div class="lesson-header">
            <div class="header-navigation">
                <a href="{{ route('lessons.index') }}" class="back-button">
                    <i class="fas fa-arrow-left"></i>
                    Back to Lessons
                </a>
                @if($nextLesson)
                    <a href="{{ route('lessons.show', $nextLesson) }}" class="next-lesson-preview">
                        <span>Next:</span>
                        <strong>{{ $nextLesson->title }}</strong>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                @endif
            </div>

            <div class="lesson-info-card">
                <div class="lesson-main-info">
                    <div class="lesson-meta">
                        <span class="lesson-number">Lesson {{ $lesson->order_number }}</span>
                        <span class="difficulty-badge {{ $lesson->difficulty_level }}">
                            @if($lesson->difficulty_level == 'beginner')
                                <i class="fas fa-seedling"></i>
                            @elseif($lesson->difficulty_level == 'intermediate')
                                <i class="fas fa-chart-line"></i>
                            @elseif($lesson->difficulty_level == 'advanced')
                                <i class="fas fa-fire"></i>
                            @else
                                <i class="fas fa-star"></i>
                            @endif
                            {{ ucfirst($lesson->difficulty_level) }}
                        </span>
                    </div>
                    
                    <h1>{{ $lesson->title }}</h1>
                    <p class="lesson-description">
                        {{ $lesson->description ?? 'Master essential typing techniques and improve your speed and accuracy through focused practice.' }}
                    </p>

                    <div class="lesson-stats-bar">
                        <div class="stat-item">
                            <i class="fas fa-clock"></i>
                            <span>{{ $lesson->estimated_completion_time ?? 15 }} minutes</span>
                        </div>
                        <div class="stat-item">
                            <i class="fas fa-star"></i>
                            <span>{{ $lesson->experience_reward }} XP reward</span>
                        </div>
                        @if($progress && $progress->highest_speed > 0)
                            <div class="stat-item">
                                <i class="fas fa-trophy"></i>
                                <span>Personal best: {{ number_format($progress->highest_speed, 1) }} WPM</span>
                            </div>
                        @endif
                    </div>
                </div>

                @if($progress && $progress->completion_status === 'completed')
                    <div class="completion-badge">
                        <div class="completion-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="completion-info">
                            <span class="completion-status">Completed</span>
                            <span class="completion-date">{{ $progress->completed_at->diffForHumans() }}</span>
                        </div>
                    </div>
                @elseif($progress && $progress->completion_status === 'in_progress')
                    <div class="progress-badge">
                        <div class="progress-icon">
                            <i class="fas fa-play-circle"></i>
                        </div>
                        <div class="progress-info">
                            <span class="progress-status">In Progress</span>
                            <span class="progress-hint">Continue your learning journey</span>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Lesson Content -->
        <div class="lesson-content-area">
            <div class="content-section">
                <div class="section-header">
                    <h2><i class="fas fa-book-open"></i> Lesson Content</h2>
                </div>
                
                <div class="lesson-content-card">
                    <div class="content-text">
                        {!! nl2br(e($lesson->content)) !!}
                    </div>
                    
                    @if($lesson->difficulty_level == 'beginner')
                        <div class="visual-guide">
                            <div class="keyboard-guide">
                                <h4><i class="fas fa-keyboard"></i> Finger Position Guide</h4>
                                <div class="keyboard-visual">
                                    <div class="key-row">
                                        <div class="key inactive">Q</div>
                                        <div class="key inactive">W</div>
                                        <div class="key inactive">E</div>
                                        <div class="key inactive">R</div>
                                        <div class="key inactive">T</div>
                                        <div class="key inactive">Y</div>
                                        <div class="key inactive">U</div>
                                        <div class="key inactive">I</div>
                                        <div class="key inactive">O</div>
                                        <div class="key inactive">P</div>
                                    </div>
                                    <div class="key-row home-row">
                                        <div class="key active left-pinky">A</div>
                                        <div class="key active left-ring">S</div>
                                        <div class="key active left-middle">D</div>
                                        <div class="key active left-index">F</div>
                                        <div class="key spacing"></div>
                                        <div class="key active right-index">J</div>
                                        <div class="key active right-middle">K</div>
                                        <div class="key active right-ring">L</div>
                                        <div class="key active right-pinky">;</div>
                                    </div>
                                    <div class="key-row">
                                        <div class="key inactive">Z</div>
                                        <div class="key inactive">X</div>
                                        <div class="key inactive">C</div>
                                        <div class="key inactive">V</div>
                                        <div class="key inactive">B</div>
                                        <div class="key inactive">N</div>
                                        <div class="key inactive">M</div>
                                    </div>
                                </div>
                                <div class="finger-legend">
                                    <div class="legend-item">
                                        <div class="color-indicator left-pinky"></div>
                                        <span>Left Pinky</span>
                                    </div>
                                    <div class="legend-item">
                                        <div class="color-indicator left-ring"></div>
                                        <span>Left Ring</span>
                                    </div>
                                    <div class="legend-item">
                                        <div class="color-indicator left-middle"></div>
                                        <span>Left Middle</span>
                                    </div>
                                    <div class="legend-item">
                                        <div class="color-indicator left-index"></div>
                                        <span>Left Index</span>
                                    </div>
                                    <div class="legend-item">
                                        <div class="color-indicator right-index"></div>
                                        <span>Right Index</span>
                                    </div>
                                    <div class="legend-item">
                                        <div class="color-indicator right-middle"></div>
                                        <span>Right Middle</span>
                                    </div>
                                    <div class="legend-item">
                                        <div class="color-indicator right-ring"></div>
                                        <span>Right Ring</span>
                                    </div>
                                    <div class="legend-item">
                                        <div class="color-indicator right-pinky"></div>
                                        <span>Right Pinky</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Practice Area -->
            <div class="practice-section">
                <div class="section-header">
                    <h2><i class="fas fa-play"></i> Practice Exercise</h2>
                    <div class="practice-controls">
                        <button id="resetButton" class="btn btn-secondary" style="display: none;">
                            <i class="fas fa-redo"></i>
                            Reset
                        </button>
                        <button id="startButton" class="btn btn-primary">
                            <i class="fas fa-play"></i>
                            Start Practice
                        </button>
                    </div>
                </div>

                <div class="practice-interface">
                    <!-- Text Display -->
                    <div class="text-display-area">
                        <div class="text-content" id="textContent">
                            {{ $lesson->content }}
                        </div>
                    </div>

                    <!-- Typing Input Area -->
                    <div class="typing-area" id="typingArea" style="display: none;">
                        <div class="typing-input-wrapper">
                            <label for="typingInput" class="input-label">
                                <i class="fas fa-keyboard"></i>
                                Type the text above:
                            </label>
                            <textarea id="typingInput" 
                                      class="typing-input" 
                                      rows="6" 
                                      placeholder="Click 'Start Practice' and begin typing here..."
                                      disabled></textarea>
                        </div>

                        <!-- Real-time Stats -->
                        <div class="realtime-stats">
                            <div class="stats-grid">
                                <div class="stat-card live-wpm">
                                    <div class="stat-icon">
                                        <i class="fas fa-tachometer-alt"></i>
                                    </div>
                                    <div class="stat-info">
                                        <span class="stat-value" id="currentWPM">0</span>
                                        <span class="stat-label">WPM</span>
                                    </div>
                                </div>
                                
                                <div class="stat-card live-accuracy">
                                    <div class="stat-icon">
                                        <i class="fas fa-bullseye"></i>
                                    </div>
                                    <div class="stat-info">
                                        <span class="stat-value" id="currentAccuracy">0%</span>
                                        <span class="stat-label">Accuracy</span>
                                    </div>
                                </div>
                                
                                <div class="stat-card live-progress">
                                    <div class="stat-icon">
                                        <i class="fas fa-chart-line"></i>
                                    </div>
                                    <div class="stat-info">
                                        <span class="stat-value" id="currentProgress">0%</span>
                                        <span class="stat-label">Progress</span>
                                    </div>
                                </div>
                                
                                <div class="stat-card live-errors">
                                    <div class="stat-icon">
                                        <i class="fas fa-exclamation-triangle"></i>
                                    </div>
                                    <div class="stat-info">
                                        <span class="stat-value error" id="currentErrors">0</span>
                                        <span class="stat-label">Errors</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Timer Display -->
                    <div class="timer-display" id="timerDisplay" style="display: none;">
                        <div class="timer-card">
                            <i class="fas fa-stopwatch"></i>
                            <span id="practiceTimer">00:00</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Results Modal -->
        <div class="results-modal" id="resultsModal" style="display: none;">
            <div class="modal-backdrop"></div>
            <div class="modal-content">
                <div class="results-header">
                    <div class="results-icon">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <h2>Practice Complete!</h2>
                    <p>Great job on completing this lesson</p>
                </div>

                <div class="results-stats">
                    <div class="stat-row">
                        <div class="final-stat speed">
                            <div class="stat-icon">
                                <i class="fas fa-tachometer-alt"></i>
                            </div>
                            <div class="stat-data">
                                <span class="stat-number" id="finalWPM">0</span>
                                <span class="stat-unit">WPM</span>
                            </div>
                        </div>
                        
                        <div class="final-stat accuracy">
                            <div class="stat-icon">
                                <i class="fas fa-bullseye"></i>
                            </div>
                            <div class="stat-data">
                                <span class="stat-number" id="finalAccuracy">0</span>
                                <span class="stat-unit">% Accuracy</span>
                            </div>
                        </div>
                        
                        <div class="final-stat time">
                            <div class="stat-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="stat-data">
                                <span class="stat-number" id="finalTime">0</span>
                                <span class="stat-unit">seconds</span>
                            </div>
                        </div>
                    </div>

                    <div class="progress-comparison" id="progressComparison">
                        <!-- Progress comparison will be inserted here -->
                    </div>
                </div>

                <div class="results-actions">
                    <form action="{{ route('lessons.progress', $lesson) }}" method="POST" id="progressForm">
                        @csrf
                        <input type="hidden" name="typing_speed" id="submitWPM">
                        <input type="hidden" name="typing_accuracy" id="submitAccuracy">
                        <input type="hidden" name="completion_status" id="submitStatus" value="completed">
                        
                        <button type="submit" class="btn btn-primary btn-large">
                            <i class="fas fa-save"></i>
                            Save Progress
                        </button>
                    </form>
                    
                    <button class="btn btn-secondary" onclick="resetPractice()">
                        <i class="fas fa-redo"></i>
                        Try Again
                    </button>
                    
                    @if($nextLesson)
                        <a href="{{ route('lessons.show', $nextLesson) }}" class="btn btn-outline-primary">
                            <i class="fas fa-arrow-right"></i>
                            Next Lesson
                        </a>
                    @else
                        <a href="{{ route('lessons.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-list"></i>
                            All Lessons
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.lesson-container {
    padding: 2rem 0;
    min-height: calc(100vh - 80px);
}

/* Header Navigation */
.header-navigation {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.back-button {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--text-secondary);
    text-decoration: none;
    font-weight: 500;
    padding: 0.5rem 1rem;
    border-radius: var(--border-radius);
    transition: all 0.3s ease;
    border: 1px solid rgba(255, 255, 255, 0.1);
    background: rgba(255, 255, 255, 0.03);
    backdrop-filter: blur(10px);
}

.back-button:hover {
    color: var(--accent-pink);
    border-color: var(--accent-pink);
    background: rgba(255, 107, 157, 0.1);
    transform: translateX(-3px);
}

.next-lesson-preview {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    color: var(--text-secondary);
    text-decoration: none;
    font-size: 0.9rem;
    padding: 0.75rem 1rem;
    border-radius: var(--border-radius);
    transition: all 0.3s ease;
    border: 1px solid rgba(59, 130, 246, 0.2);
    background: rgba(59, 130, 246, 0.05);
}

.next-lesson-preview:hover {
    color: var(--info);
    border-color: var(--info);
    transform: translateX(3px);
}

.next-lesson-preview strong {
    color: var(--text-primary);
}

/* Lesson Info Card */
.lesson-info-card {
    background: var(--gradient-card);
    backdrop-filter: blur(var(--blur-amount));
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius-lg);
    padding: 3rem;
    margin-bottom: 3rem;
    position: relative;
    overflow: hidden;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
}

.lesson-info-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: var(--gradient-accent);
}

.lesson-main-info {
    flex: 1;
}

.lesson-meta {
    display: flex;
    gap: 1rem;
    margin-bottom: 1rem;
}

.lesson-number {
    background: rgba(255, 255, 255, 0.1);
    color: var(--text-primary);
    padding: 0.5rem 1rem;
    border-radius: var(--border-radius);
    font-weight: 600;
    font-size: 0.9rem;
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.difficulty-badge {
    padding: 0.5rem 1rem;
    border-radius: var(--border-radius);
    font-size: 0.9rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.difficulty-badge.beginner {
    background: rgba(16, 185, 129, 0.1);
    color: var(--success);
    border: 1px solid rgba(16, 185, 129, 0.2);
}

.difficulty-badge.intermediate {
    background: rgba(245, 158, 11, 0.1);
    color: var(--warning);
    border: 1px solid rgba(245, 158, 11, 0.2);
}

.difficulty-badge.advanced {
    background: rgba(239, 68, 68, 0.1);
    color: var(--error);
    border: 1px solid rgba(239, 68, 68, 0.2);
}

.difficulty-badge.expert {
    background: rgba(139, 92, 246, 0.1);
    color: var(--accent-purple);
    border: 1px solid rgba(139, 92, 246, 0.2);
}

.lesson-info-card h1 {
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 1rem;
    background: var(--gradient-accent);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.lesson-description {
    color: var(--text-secondary);
    font-size: 1.1rem;
    line-height: 1.6;
    margin-bottom: 2rem;
    max-width: 600px;
}

.lesson-stats-bar {
    display: flex;
    gap: 2rem;
    flex-wrap: wrap;
}

.lesson-stats-bar .stat-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--text-secondary);
    font-size: 0.95rem;
}

.lesson-stats-bar .stat-item i {
    color: var(--accent-pink);
    width: 16px;
}

/* Completion/Progress Badges */
.completion-badge,
.progress-badge {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.5rem;
    border-radius: var(--border-radius-lg);
    min-width: 200px;
}

.completion-badge {
    background: rgba(16, 185, 129, 0.1);
    border: 1px solid rgba(16, 185, 129, 0.2);
}

.progress-badge {
    background: rgba(245, 158, 11, 0.1);
    border: 1px solid rgba(245, 158, 11, 0.2);
}

.completion-icon,
.progress-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
}

.completion-icon {
    background: linear-gradient(45deg, #10b981, #059669);
}

.progress-icon {
    background: linear-gradient(45deg, #f59e0b, #eab308);
}

.completion-info,
.progress-info {
    display: flex;
    flex-direction: column;
}

.completion-status,
.progress-status {
    font-weight: 600;
    color: var(--text-primary);
    font-size: 1.1rem;
}

.completion-date,
.progress-hint {
    color: var(--text-secondary);
    font-size: 0.9rem;
}

/* Content Area */
.lesson-content-area {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 3rem;
    margin-bottom: 3rem;
}

.content-section,
.practice-section {
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.section-header h2 {
    font-size: 1.3rem;
    font-weight: 600;
    color: var(--text-primary);
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.practice-controls {
    display: flex;
    gap: 1rem;
}

/* Content Card */
.lesson-content-card {
    background: var(--gradient-card);
    backdrop-filter: blur(var(--blur-amount));
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius-lg);
    padding: 2rem;
    flex: 1;
}

.content-text {
    color: var(--text-primary);
    line-height: 1.8;
    font-size: 1rem;
    margin-bottom: 2rem;
}

/* Visual Guide */
.visual-guide {
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
}

.keyboard-guide h4 {
    color: var(--text-primary);
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.keyboard-visual {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    margin-bottom: 1.5rem;
}

.key-row {
    display: flex;
    justify-content: center;
    gap: 0.25rem;
}

.key {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 6px;
    font-weight: 600;
    font-size: 0.9rem;
    border: 2px solid transparent;
    transition: all 0.3s ease;
}

.key.inactive {
    background: rgba(255, 255, 255, 0.05);
    color: var(--text-muted);
}

.key.active {
    border: 2px solid;
    color: white;
    font-weight: 700;
}

.key.spacing {
    width: 20px;
    background: transparent;
}

.key.left-pinky { background: #ff6b9d; border-color: #ff6b9d; }
.key.left-ring { background: #c084fc; border-color: #c084fc; }
.key.left-middle { background: #00d4ff; border-color: #00d4ff; }
.key.left-index { background: #f59e0b; border-color: #f59e0b; }
.key.right-index { background: #10b981; border-color: #10b981; }
.key.right-middle { background: #6366f1; border-color: #6366f1; }
.key.right-ring { background: #ef4444; border-color: #ef4444; }
.key.right-pinky { background: #8b5cf6; border-color: #8b5cf6; }

.finger-legend {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    justify-content: center;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.8rem;
    color: var(--text-secondary);
}

.color-indicator {
    width: 16px;
    height: 16px;
    border-radius: 3px;
}

.color-indicator.left-pinky { background: #ff6b9d; }
.color-indicator.left-ring { background: #c084fc; }
.color-indicator.left-middle { background: #00d4ff; }
.color-indicator.left-index { background: #f59e0b; }
.color-indicator.right-index { background: #10b981; }
.color-indicator.right-middle { background: #6366f1; }
.color-indicator.right-ring { background: #ef4444; }
.color-indicator.right-pinky { background: #8b5cf6; }

/* Practice Interface */
.practice-interface {
    background: var(--gradient-card);
    backdrop-filter: blur(var(--blur-amount));
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius-lg);
    padding: 2rem;
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

.text-display-area {
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(255, 255, 255, 0.05);
    border-radius: var(--border-radius);
    padding: 2rem;
}

.text-content {
    font-family: 'Courier New', monospace;
    font-size: 1.1rem;
    line-height: 1.8;
    color: var(--text-primary);
    word-spacing: 0.2em;
    user-select: none;
}

.char-correct { 
    background: rgba(16, 185, 129, 0.2); 
    color: var(--success); 
    border-radius: 2px;
}
.char-incorrect { 
    background: rgba(239, 68, 68, 0.2); 
    color: var(--error); 
    border-radius: 2px;
}
.char-current { 
    background: var(--accent-pink); 
    color: white; 
    border-radius: 2px;
    animation: blink 1s infinite;
}

@keyframes blink {
    0%, 50% { opacity: 1; }
    51%, 100% { opacity: 0.3; }
}

.typing-input-wrapper {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.input-label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--text-primary);
    font-weight: 500;
}

.typing-input {
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
    width: 100%;
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

.typing-input::placeholder {
    color: var(--text-muted);
}

/* Real-time Stats */
.realtime-stats {
    margin-top: 1rem;
}

.realtime-stats .stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    gap: 1rem;
}

.realtime-stats .stat-card {
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(255, 255, 255, 0.05);
    border-radius: var(--border-radius);
    padding: 1rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    transition: all 0.3s ease;
}

.realtime-stats .stat-card:hover {
    background: rgba(255, 255, 255, 0.06);
    border-color: var(--accent-pink);
}

.realtime-stats .stat-icon {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 0.9rem;
}

.stat-card.live-wpm .stat-icon { background: var(--gradient-button); }
.stat-card.live-accuracy .stat-icon { background: linear-gradient(45deg, #00d4ff, #0ea5e9); }
.stat-card.live-progress .stat-icon { background: linear-gradient(45deg, #f59e0b, #eab308); }
.stat-card.live-errors .stat-icon { background: linear-gradient(45deg, #ef4444, #dc2626); }

.realtime-stats .stat-info {
    display: flex;
    flex-direction: column;
}

.realtime-stats .stat-value {
    font-size: 1.2rem;
    font-weight: 700;
    color: var(--text-primary);
}

.realtime-stats .stat-value.error {
    color: var(--error);
}

.realtime-stats .stat-label {
    font-size: 0.8rem;
    color: var(--text-secondary);
}

/* Timer Display */
.timer-display {
    position: fixed;
    top: 100px;
    right: 2rem;
    z-index: 100;
}

.timer-card {
    background: var(--gradient-card);
    backdrop-filter: blur(var(--blur-amount));
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius-lg);
    padding: 1rem 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    color: var(--text-primary);
    font-weight: 600;
    font-size: 1.1rem;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
}

.timer-card i {
    color: var(--accent-pink);
}

/* Results Modal */
.results-modal {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 1000;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2rem;
}

.modal-backdrop {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.8);
    backdrop-filter: blur(10px);
}

.modal-content {
    background: var(--gradient-card);
    backdrop-filter: blur(var(--blur-amount));
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius-lg);
    padding: 3rem;
    max-width: 600px;
    width: 100%;
    position: relative;
    max-height: 80vh;
    overflow-y: auto;
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

.results-icon {
    width: 80px;
    height: 80px;
    background: var(--gradient-button);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    font-size: 2rem;
    color: white;
    box-shadow: 0 8px 25px rgba(255, 107, 157, 0.3);
}

.results-header h2 {
    color: var(--text-primary);
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.results-header p {
    color: var(--text-secondary);
    font-size: 1.1rem;
}

/* Results Stats */
.results-stats {
    margin-bottom: 2rem;
}

.stat-row {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.final-stat {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 1.5rem;
    background: rgba(255, 255, 255, 0.03);
    border-radius: var(--border-radius);
    border: 1px solid rgba(255, 255, 255, 0.05);
}

.final-stat .stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2rem;
    margin-bottom: 1rem;
}

.final-stat.speed .stat-icon { background: var(--gradient-button); }
.final-stat.accuracy .stat-icon { background: linear-gradient(45deg, #00d4ff, #0ea5e9); }
.final-stat.time .stat-icon { background: linear-gradient(45deg, #f59e0b, #eab308); }

.stat-data {
    text-align: center;
}

.stat-number {
    display: block;
    font-size: 2rem;
    font-weight: 700;
    color: var(--text-primary);
}

.stat-unit {
    color: var(--text-secondary);
    font-size: 0.9rem;
}

.progress-comparison {
    background: rgba(59, 130, 246, 0.05);
    border: 1px solid rgba(59, 130, 246, 0.2);
    border-radius: var(--border-radius);
    padding: 1rem;
}

/* Results Actions */
.results-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

.btn-large {
    padding: 1rem 2rem;
    font-size: 1.1rem;
    font-weight: 600;
}

/* Responsive */
@media (max-width: 1024px) {
    .lesson-content-area {
        grid-template-columns: 1fr;
        gap: 2rem;
    }
    
    .lesson-info-card {
        flex-direction: column;
        gap: 2rem;
        text-align: center;
    }
    
    .header-navigation {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
}

@media (max-width: 768px) {
    .lesson-info-card h1 {
        font-size: 2rem;
    }
    
    .lesson-stats-bar {
        justify-content: center;
    }
    
    .stat-row {
        grid-template-columns: 1fr;
    }
    
    .results-actions {
        flex-direction: column;
    }
    
    .timer-display {
        position: relative;
        top: auto;
        right: auto;
        margin-bottom: 1rem;
    }
    
    .realtime-stats .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .keyboard-visual {
        transform: scale(0.8);
    }
    
    .finger-legend {
        transform: scale(0.9);
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Elements
    const startButton = document.getElementById('startButton');
    const resetButton = document.getElementById('resetButton');
    const typingArea = document.getElementById('typingArea');
    const typingInput = document.getElementById('typingInput');
    const textContent = document.getElementById('textContent');
    const timerDisplay = document.getElementById('timerDisplay');
    const practiceTimer = document.getElementById('practiceTimer');
    const resultsModal = document.getElementById('resultsModal');
    
    // Stats elements
    const currentWPM = document.getElementById('currentWPM');
    const currentAccuracy = document.getElementById('currentAccuracy');
    const currentProgress = document.getElementById('currentProgress');
    const currentErrors = document.getElementById('currentErrors');
    
    // Results elements
    const finalWPM = document.getElementById('finalWPM');
    const finalAccuracy = document.getElementById('finalAccuracy');
    const finalTime = document.getElementById('finalTime');
    const submitWPM = document.getElementById('submitWPM');
    const submitAccuracy = document.getElementById('submitAccuracy');
    
    // Practice state
    let practiceActive = false;
    let startTime;
    let timerInterval;
    const originalText = textContent.textContent.trim();
    let userTypedText = '';
    
    // Event listeners
    startButton.addEventListener('click', startPractice);
    resetButton.addEventListener('click', resetPractice);
    typingInput.addEventListener('input', handleTyping);
    typingInput.addEventListener('paste', e => e.preventDefault());
    
    function startPractice() {
        practiceActive = true;
        startTime = new Date();
        
        // Update UI
        startButton.style.display = 'none';
        resetButton.style.display = 'inline-flex';
        typingArea.style.display = 'block';
        timerDisplay.style.display = 'block';
        
        // Enable and focus input
        typingInput.disabled = false;
        typingInput.value = '';
        typingInput.focus();
        
        // Start timer
        timerInterval = setInterval(updateTimer, 100);
        
        // Highlight first character
        highlightCurrentChar(0);
    }
    
    function resetPractice() {
        practiceActive = false;
        userTypedText = '';
        
        // Clear timer
        clearInterval(timerInterval);
        practiceTimer.textContent = '00:00';
        
        // Reset UI
        startButton.style.display = 'inline-flex';
        resetButton.style.display = 'none';
        typingArea.style.display = 'none';
        timerDisplay.style.display = 'none';
        resultsModal.style.display = 'none';
        
        // Reset input
        typingInput.disabled = true;
        typingInput.value = '';
        
        // Reset text highlighting
        textContent.innerHTML = originalText;
        
        // Reset stats
        updateStatsDisplay(0, 0, 0, 0);
    }
    
    function handleTyping(e) {
        if (!practiceActive) return;
        
        userTypedText = e.target.value;
        
        // Update text highlighting
        highlightText(userTypedText);
        
        // Calculate stats
        const elapsedTime = (new Date() - startTime) / 1000;
        const stats = calculateStats(userTypedText, elapsedTime);
        updateStatsDisplay(stats.wpm, stats.accuracy, stats.progress, stats.errors);
        
        // Check if complete
        if (userTypedText.length >= originalText.length) {
            completePractice();
        }
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
        return errors;
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
    
    function calculateStats(typedText, elapsedTime) {
        const typedWords = typedText.split(/\s+/).length;
        const wmp = elapsedTime > 0 ? Math.round((typedWords / elapsedTime) * 60) : 0;
        
        let correctChars = 0;
        let errors = 0;
        const minLength = Math.min(originalText.length, typedText.length);
        
        for (let i = 0; i < minLength; i++) {
            if (originalText[i] === typedText[i]) {
                correctChars++;
            } else {
                errors++;
            }
        }
        
        const accuracy = typedText.length > 0 ? Math.round((correctChars / typedText.length) * 100) : 0;
        const progress = Math.round((typedText.length / originalText.length) * 100);
        
        return { wpm: wmp, accuracy, progress, errors };
    }
    
    function updateStatsDisplay(wmp, accuracy, progress, errors) {
        if (currentWPM) currentWPM.textContent = wmp;
        if (currentAccuracy) currentAccuracy.textContent = accuracy + '%';
        if (currentProgress) currentProgress.textContent = progress + '%';
        if (currentErrors) currentErrors.textContent = errors;
    }
    
    function updateTimer() {
        if (!practiceActive) return;
        
        const elapsed = (new Date() - startTime) / 1000;
        const minutes = Math.floor(elapsed / 60);
        const seconds = Math.floor(elapsed % 60);
        
        practiceTimer.textContent = ${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')};
    }
    
    function completePractice() {
        practiceActive = false;
        clearInterval(timerInterval);
        
        const totalTime = (new Date() - startTime) / 1000;
        const finalStats = calculateStats(userTypedText, totalTime);
        
        // Update final results
        finalWPM.textContent = finalStats.wpm;
        finalAccuracy.textContent = finalStats.accuracy + '%';
        finalTime.textContent = Math.round(totalTime);
        
        // Set form values
        submitWPM.value = finalStats.wmp;
        submitAccuracy.value = finalStats.accuracy;
        
        // Show progress comparison if available
        showProgressComparison(finalStats);
        
        // Disable input
        typingInput.disabled = true;
        
        // Show results modal
        resultsModal.style.display = 'flex';
    }
    
    function showProgressComparison(currentStats) {
        const progressComparison = document.getElementById('progressComparison');
        @if($progress && $progress->highest_speed > 0)
            const previousBest = {{ $progress->highest_speed }};
            const improvement = currentStats.wmp - previousBest;
            
            let comparisonHTML = '<h4><i class="fas fa-chart-line"></i> Progress Comparison</h4>';
            
            if (improvement > 0) {
                comparisonHTML += `
                    <div class="comparison-item positive">
                        <i class="fas fa-arrow-up"></i>
                        <span>+${improvement.toFixed(1)} WPM improvement from your best!</span>
                    </div>
                `;
            } else if (improvement < 0) {
                comparisonHTML += `
                    <div class="comparison-item neutral">
                        <i class="fas fa-info-circle"></i>
                        <span>Your best is still ${previousBest.toFixed(1)} WPM. Keep practicing!</span>
                    </div>
                `;
            } else {
                comparisonHTML += `
                    <div class="comparison-item positive">
                        <i class="fas fa-equals"></i>
                        <span>You matched your personal best!</span>
                    </div>
                `;
            }
            
            progressComparison.innerHTML = comparisonHTML;
        @else
            progressComparison.innerHTML = `
                <h4><i class="fas fa-star"></i> First Attempt</h4>
                <div class="comparison-item positive">
                    <i class="fas fa-trophy"></i>
                    <span>Great job on your first attempt! This is your new personal best.</span>
                </div>
            `;
        @endif
    }
    
    // Add CSS for progress comparison
    const style = document.createElement('style');
    style.textContent = `
        .comparison-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem;
            border-radius: calc(var(--border-radius) - 2px);
            margin-top: 0.5rem;
        }
        
        .comparison-item.positive {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success);
        }
        
        .comparison-item.neutral {
            background: rgba(59, 130, 246, 0.1);
            color: var(--info);
        }
        
        .comparison-item i {
            font-size: 1rem;
        }
    `;
    document.head.appendChild(style);
});

// Global function for reset button in results
function resetPractice() {
    const event = new Event('click');
    document.getElementById('resetButton').dispatchEvent(event);
}
</script>
@endsection