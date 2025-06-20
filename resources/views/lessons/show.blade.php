@extends('layouts.app')

@section('content')
<div class="lesson-container">
    <div class="container-fluid">
        <!-- Lesson Header -->
        <div class="lesson-header">
            <div class="header-left">
                <div class="back-navigation">
                    <a href="{{ route('lessons.index') }}" class="btn btn-link">
                        <i class="fas fa-arrow-left"></i>
                        Back to Lessons
                    </a>
                </div>
                <div class="lesson-info">
                    <div class="lesson-badge">
                        <span class="lesson-number">{{ $lesson->order_number }}</span>
                        <span class="lesson-category">{{ ucfirst($lesson->category ?? $lesson->difficulty_level) }}</span>
                    </div>
                    <h1 class="lesson-title">{{ $lesson->title }}</h1>
                    <p class="lesson-description">{{ $lesson->description }}</p>
                </div>
            </div>
            <div class="header-right">
                <div class="lesson-goals">
                    <div class="goal-item">
                        <i class="fas fa-target"></i>
                        <span>Target: {{ $lesson->target_wpm }} WPM</span>
                    </div>
                    <div class="goal-item">
                        <i class="fas fa-bullseye"></i>
                        <span>Accuracy: {{ $lesson->target_accuracy }}%</span>
                    </div>
                    <div class="goal-item">
                        <i class="fas fa-clock"></i>
                        <span>Duration: {{ $lesson->estimated_duration }} min</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lesson Progress -->
        <div class="lesson-progress-bar">
            <div class="progress-container">
                <div class="progress-track">
                    <div class="progress-fill" id="lesson-progress-fill"></div>
                </div>
                <div class="progress-info">
                    <span class="progress-text">Lesson Progress: <span id="progress-percentage">0%</span></span>
                    <span class="progress-step">Step <span id="current-step">1</span> of <span id="total-steps">{{ $lesson->steps->count() }}</span></span>
                </div>
            </div>
        </div>

        <!-- Lesson Content -->
        <div class="lesson-content">
            <!-- Lesson Steps Navigation -->
            <div class="lesson-steps-nav">
                <div class="steps-list">
                    @foreach($lesson->steps as $index => $step)
                    <div class="step-item {{ $index === 0 ? 'active' : '' }}" data-step="{{ $index + 1 }}">
                        <div class="step-number">{{ $index + 1 }}</div>
                        <div class="step-info">
                            <div class="step-title">{{ $step->title }}</div>
                            <div class="step-type">{{ ucfirst($step->type) }}</div>
                        </div>
                        <div class="step-status">
                            <i class="fas fa-circle"></i>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Current Step Content -->
            <div class="current-step-content">
                @foreach($lesson->steps as $index => $step)
                <div class="step-content {{ $index === 0 ? 'active' : '' }}" data-step="{{ $index + 1 }}">
                    <!-- Step Header -->
                    <div class="step-header">
                        <h2 class="step-title">{{ $step->title }}</h2>
                        <p class="step-description">{{ $step->description }}</p>
                        
                        @if($step->type === 'instruction')
                        <div class="instruction-content">
                            <div class="instruction-text">
                                {!! nl2br(e($step->content)) !!}
                            </div>
                            
                            @if($step->demonstration_keys)
                            <div class="key-demonstration">
                                <h4>Keys to Practice:</h4>
                                <div class="demo-keys">
                                    @foreach(json_decode($step->demonstration_keys) as $key)
                                    <div class="demo-key">{{ $key }}</div>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                            
                            <div class="instruction-actions">
                                <button class="btn btn-primary" onclick="nextStep()">
                                    <i class="fas fa-arrow-right"></i>
                                    Continue to Practice
                                </button>
                            </div>
                        </div>
                        @endif
                    </div>

                    @if($step->type === 'exercise')
                    <!-- Exercise Content -->
                    <div class="exercise-content">
                        <!-- Exercise Stats -->
                        <div class="exercise-stats">
                            <div class="stat-item">
                                <div class="stat-icon">
                                    <i class="fas fa-tachometer-alt"></i>
                                </div>
                                <div class="stat-info">
                                    <div class="stat-value" id="current-wpm-{{ $index + 1 }}">0</div>
                                    <div class="stat-label">WPM</div>
                                </div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-icon">
                                    <i class="fas fa-bullseye"></i>
                                </div>
                                <div class="stat-info">
                                    <div class="stat-value" id="current-accuracy-{{ $index + 1 }}">100%</div>
                                    <div class="stat-label">Accuracy</div>
                                </div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-icon">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                                <div class="stat-info">
                                    <div class="stat-value" id="exercise-progress-{{ $index + 1 }}">0%</div>
                                    <div class="stat-label">Progress</div>
                                </div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-icon">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                                <div class="stat-info">
                                    <div class="stat-value" id="current-errors-{{ $index + 1 }}">0</div>
                                    <div class="stat-label">Errors</div>
                                </div>
                            </div>
                        </div>

                        <!-- Text Display -->
                        <div class="text-display">
                            <div class="text-content" id="text-content-{{ $index + 1 }}">
                                @foreach(str_split($step->content) as $charIndex => $char)
                                    <span class="char" data-index="{{ $charIndex }}">{{ $char === ' ' ? '·' : $char }}</span>
                                @endforeach
                            </div>
                        </div>

                        <!-- Typing Input -->
                        <div class="typing-area">
                            <textarea 
                                class="typing-input" 
                                id="typing-input-{{ $index + 1 }}"
                                placeholder="Click here and start typing..."
                                rows="4"
                                data-step="{{ $index + 1 }}"
                                data-content="{{ $step->content }}"
                                disabled
                            ></textarea>
                            <div class="typing-controls">
                                <button class="btn btn-success" onclick="startExercise({{ $index + 1 }})">
                                    <i class="fas fa-play"></i>
                                    Start Exercise
                                </button>
                                <button class="btn btn-warning" onclick="restartExercise({{ $index + 1 }})" style="display: none;">
                                    <i class="fas fa-redo"></i>
                                    Restart
                                </button>
                                <button class="btn btn-primary" onclick="nextStep()" style="display: none;" id="next-btn-{{ $index + 1 }}">
                                    <i class="fas fa-arrow-right"></i>
                                    Next Step
                                </button>
                            </div>
                        </div>

                        <!-- Virtual Keyboard -->
                        <div class="virtual-keyboard" id="virtual-keyboard-{{ $index + 1 }}">
                            <div class="keyboard-container">
                                <div class="current-key-info">
                                    <div class="key-info-content">
                                        <span class="key-label">Next Key:</span>
                                        <span class="key-display" id="next-key-{{ $index + 1 }}">-</span>
                                        <span class="finger-label">Use: <span id="finger-guide-{{ $index + 1 }}">-</span></span>
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
                    @endif
                </div>
                @endforeach
            </div>
        </div>

        <!-- Lesson Navigation -->
        <div class="lesson-navigation">
            <button class="btn btn-outline-secondary" onclick="previousStep()" id="prev-btn" disabled>
                <i class="fas fa-arrow-left"></i>
                Previous Step
            </button>
            
            <div class="nav-info">
                <span class="current-step-info">Step <span id="nav-current-step">1</span> of {{ $lesson->steps->count() }}</span>
            </div>
            
            <button class="btn btn-primary" onclick="nextStep()" id="nav-next-btn">
                <i class="fas fa-arrow-right"></i>
                Next Step
            </button>
        </div>
    </div>
</div>

<!-- Lesson Complete Modal -->
<div class="modal fade" id="lessonCompleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-trophy"></i>
                    Lesson Complete!
                </h5>
            </div>
            <div class="modal-body">
                <div class="completion-content">
                    <div class="completion-celebration">
                        <div class="celebration-icon">
                            <i class="fas fa-star"></i>
                        </div>
                        <h3>Congratulations!</h3>
                        <p>You have successfully completed <strong>{{ $lesson->title }}</strong></p>
                    </div>
                    
                    <div class="lesson-results">
                        <div class="result-grid">
                            <div class="result-item">
                                <div class="result-icon">
                                    <i class="fas fa-tachometer-alt"></i>
                                </div>
                                <div class="result-content">
                                    <div class="result-value" id="final-lesson-wpm">0</div>
                                    <div class="result-label">Average WPM</div>
                                </div>
                            </div>
                            <div class="result-item">
                                <div class="result-icon">
                                    <i class="fas fa-bullseye"></i>
                                </div>
                                <div class="result-content">
                                    <div class="result-value" id="final-lesson-accuracy">0%</div>
                                    <div class="result-label">Average Accuracy</div>
                                </div>
                            </div>
                            <div class="result-item">
                                <div class="result-icon">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div class="result-content">
                                    <div class="result-value" id="final-lesson-time">00:00</div>
                                    <div class="result-label">Time Taken</div>
                                </div>
                            </div>
                            <div class="result-item">
                                <div class="result-icon">
                                    <i class="fas fa-star"></i>
                                </div>
                                <div class="result-content">
                                    <div class="result-value" id="lesson-score">0</div>
                                    <div class="result-label">Score</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="goals-achievement">
                            <h4>Goals Achievement</h4>
                            <div class="goals-list">
                                <div class="goal-check">
                                    <div class="goal-status" id="wpm-goal-status">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                    <div class="goal-text">
                                        <span>Target WPM: {{ $lesson->target_wpm }}</span>
                                        <span class="goal-result" id="wpm-goal-result">Achieved!</span>
                                    </div>
                                </div>
                                <div class="goal-check">
                                    <div class="goal-status" id="accuracy-goal-status">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                    <div class="goal-text">
                                        <span>Target Accuracy: {{ $lesson->target_accuracy }}%</span>
                                        <span class="goal-result" id="accuracy-goal-result">Achieved!</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="experience-gained">
                            <div class="exp-display">
                                <i class="fas fa-star"></i>
                                <span>+<span id="exp-gained">50</span> EXP Gained</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="next-lesson-info" id="next-lesson-info" style="display: none;">
                        <div class="next-lesson-card">
                            <div class="next-lesson-icon">
                                <i class="fas fa-graduation-cap"></i>
                            </div>
                            <div class="next-lesson-content">
                                <h4>Next Lesson Available</h4>
                                <p id="next-lesson-title">Advanced Finger Positioning</p>
                            </div>
                            <a href="#" class="btn btn-primary" id="next-lesson-btn">
                                <i class="fas fa-arrow-right"></i>
                                Continue Learning
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" onclick="retryLesson()">
                    <i class="fas fa-redo"></i>
                    Retry Lesson
                </button>
                <a href="{{ route('lessons.index') }}" class="btn btn-secondary">
                    <i class="fas fa-list"></i>
                    Back to Lessons
                </a>
                <button type="button" class="btn btn-primary" onclick="saveProgress()">
                    <i class="fas fa-save"></i>
                    Save Progress
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.lesson-container {
    background: var(--bg-primary);
    min-height: 100vh;
    padding: 1rem 0;
}

/* Lesson Header */
.lesson-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: var(--border-radius-lg);
    padding: 2rem;
    margin-bottom: 1.5rem;
}

.back-navigation .btn {
    color: var(--text-secondary);
    padding: 0.5rem 0;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 1rem;
}

.back-navigation .btn:hover {
    color: var(--accent-primary);
}

.lesson-badge {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1rem;
}

.lesson-number {
    width: 48px;
    height: 48px;
    background: var(--champion-gradient);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 1.2rem;
}

.lesson-category {
    background: var(--accent-primary);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: var(--border-radius);
    font-size: 0.9rem;
    font-weight: 600;
}

.lesson-title {
    font-family: var(--font-display);
    font-size: 1.75rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 0.75rem;
}

.lesson-description {
    color: var(--text-secondary);
    font-size: 1.1rem;
    margin: 0;
}

.lesson-goals {
    text-align: right;
}

.goal-item {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 0.5rem;
    margin-bottom: 0.5rem;
    color: var(--text-secondary);
    font-size: 0.9rem;
}

.goal-item i {
    color: var(--accent-primary);
}

/* Lesson Progress Bar */
.lesson-progress-bar {
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: var(--border-radius-lg);
    padding: 1.5rem 2rem;
    margin-bottom: 2rem;
}

.progress-container {
    display: flex;
    align-items: center;
    gap: 2rem;
}

.progress-track {
    flex: 1;
    height: 12px;
    background: var(--border-light);
    border-radius: 6px;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    background: var(--champion-gradient);
    transition: width 0.3s ease;
    width: 0%;
}

.progress-info {
    display: flex;
    gap: 2rem;
    white-space: nowrap;
}

.progress-text {
    color: var(--text-primary);
    font-weight: 600;
}

.progress-step {
    color: var(--text-secondary);
}

/* Lesson Content */
.lesson-content {
    display: grid;
    grid-template-columns: 300px 1fr;
    gap: 2rem;
    margin-bottom: 2rem;
}

/* Lesson Steps Navigation */
.lesson-steps-nav {
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: var(--border-radius-lg);
    padding: 1.5rem;
    height: fit-content;
    position: sticky;
    top: 2rem;
}

.steps-list {
    space-y: 1rem;
}

.step-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    border-radius: var(--border-radius);
    cursor: pointer;
    transition: all 0.3s ease;
    border: 1px solid transparent;
}

.step-item:hover {
    background: var(--bg-secondary);
}

.step-item.active {
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(16, 185, 129, 0.1));
    border-color: var(--accent-primary);
}

.step-item.completed {
    background: linear-gradient(135deg, rgba(34, 197, 94, 0.1), rgba(16, 185, 129, 0.1));
    border-color: var(--accent-success);
}

.step-number {
    width: 32px;
    height: 32px;
    background: var(--border-light);
    color: var(--text-secondary);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 0.9rem;
    transition: all 0.3s ease;
}

.step-item.active .step-number {
    background: var(--accent-primary);
    color: white;
}

.step-item.completed .step-number {
    background: var(--accent-success);
    color: white;
}

.step-info {
    flex: 1;
}

.step-title {
    font-weight: 600;
    color: var(--text-primary);
    font-size: 0.9rem;
    margin-bottom: 0.25rem;
}

.step-type {
    color: var(--text-secondary);
    font-size: 0.8rem;
    text-transform: capitalize;
}

.step-status i {
    color: var(--border-light);
    transition: color 0.3s ease;
}

.step-item.active .step-status i {
    color: var(--accent-primary);
}

.step-item.completed .step-status i {
    color: var(--accent-success);
}

/* Current Step Content */
.current-step-content {
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: var(--border-radius-lg);
    overflow: hidden;
}

.step-content {
    display: none;
    padding: 2rem;
}

.step-content.active {
    display: block;
}

.step-header {
    margin-bottom: 2rem;
    padding-bottom: 2rem;
    border-bottom: 1px solid var(--border-light);
}

.step-header .step-title {
    font-family: var(--font-display);
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 0.75rem;
}

.step-header .step-description {
    color: var(--text-secondary);
    font-size: 1.1rem;
    margin-bottom: 1.5rem;
}

/* Instruction Content */
.instruction-content {
    text-align: center;
}

.instruction-text {
    background: var(--bg-secondary);
    border: 1px solid var(--border-light);
    border-radius: var(--border-radius);
    padding: 2rem;
    margin-bottom: 2rem;
    line-height: 1.8;
    color: var(--text-primary);
}

.key-demonstration {
    margin-bottom: 2rem;
}

.key-demonstration h4 {
    color: var(--text-primary);
    margin-bottom: 1rem;
}

.demo-keys {
    display: flex;
    justify-content: center;
    gap: 1rem;
    flex-wrap: wrap;
}

.demo-key {
    background: var(--accent-primary);
    color: white;
    padding: 1rem 1.5rem;
    border-radius: var(--border-radius);
    font-family: var(--font-mono);
    font-size: 1.2rem;
    font-weight: 600;
    min-width: 60px;
    text-align: center;
}

/* Exercise Content */
.exercise-stats {
    display: flex;
    justify-content: space-around;
    background: var(--bg-secondary);
    border: 1px solid var(--border-light);
    border-radius: var(--border-radius);
    padding: 1.5rem;
    margin-bottom: 2rem;
}

.exercise-stats .stat-item {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.exercise-stats .stat-icon {
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

.exercise-stats .stat-value {
    font-family: var(--font-display);
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--text-primary);
}

.exercise-stats .stat-label {
    font-size: 0.8rem;
    color: var(--text-secondary);
}

/* Text Display */
.text-display {
    background: var(--bg-secondary);
    border: 2px solid var(--border-light);
    border-radius: var(--border-radius);
    padding: 2rem;
    margin-bottom: 2rem;
}

.text-content {
    font-family: var(--font-mono);
    font-size: 1.2rem;
    line-height: 1.8;
    letter-spacing: 0.5px;
    color: var(--text-primary);
    user-select: none;
    text-align: center;
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

/* Typing Area */
.typing-area {
    margin-bottom: 2rem;
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

.typing-controls {
    display: flex;
    justify-content: center;
    gap: 1rem;
}

/* Virtual Keyboard */
.virtual-keyboard {
    background: var(--bg-secondary);
    border: 1px solid var(--border-light);
    border-radius: var(--border-radius);
    padding: 1.5rem;
}

.current-key-info {
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: var(--border-radius);
    padding: 1rem;
    margin-bottom: 1rem;
    text-align: center;
}

.key-info-content {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 1rem;
}

.key-label,
.finger-label {
    color: var(--text-secondary);
    font-size: 0.9rem;
}

.key-display {
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

.keyboard-layout {
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

/* Lesson Navigation */
.lesson-navigation {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: var(--border-radius-lg);
    padding: 1.5rem 2rem;
}

.nav-info {
    color: var(--text-secondary);
    font-weight: 500;
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

.completion-content {
    text-align: center;
}

.completion-celebration {
    margin-bottom: 2rem;
}

.celebration-icon {
    width: 80px;
    height: 80px;
    background: var(--medal-gradient);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: white;
    margin: 0 auto 1rem;
    animation: bounce 1s ease-in-out infinite alternate;
}

@keyframes bounce {
    from { transform: translateY(0px); }
    to { transform: translateY(-10px); }
}

.completion-celebration h3 {
    font-family: var(--font-display);
    color: var(--text-primary);
    margin-bottom: 0.5rem;
}

.completion-celebration p {
    color: var(--text-secondary);
    font-size: 1.1rem;
}

/* Lesson Results */
.result-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.result-item {
    background: var(--bg-secondary);
    border: 1px solid var(--border-light);
    border-radius: var(--border-radius);
    padding: 1.5rem;
    text-align: center;
}

.result-item .result-icon {
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
}

/* Goals Achievement */
.goals-achievement {
    margin-bottom: 2rem;
}

.goals-achievement h4 {
    color: var(--text-primary);
    margin-bottom: 1rem;
}

.goals-list {
    background: var(--bg-secondary);
    border-radius: var(--border-radius);
    padding: 1rem;
}

.goal-check {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 0.75rem 0;
    border-bottom: 1px solid var(--border-light);
}

.goal-check:last-child {
    border-bottom: none;
}

.goal-status {
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.goal-status.success i {
    color: var(--accent-success);
}

.goal-status.failed i {
    color: var(--accent-danger);
}

.goal-text {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.goal-text span:first-child {
    color: var(--text-primary);
    font-weight: 500;
}

.goal-result {
    font-size: 0.9rem;
    font-weight: 600;
}

.goal-result.success {
    color: var(--accent-success);
}

.goal-result.failed {
    color: var(--accent-danger);
}

/* Experience Gained */
.experience-gained {
    background: linear-gradient(135deg, rgba(34, 197, 94, 0.1), rgba(16, 185, 129, 0.1));
    border: 1px solid var(--accent-success);
    border-radius: var(--border-radius);
    padding: 1.5rem;
    margin-bottom: 2rem;
}

.exp-display {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
    font-size: 1.2rem;
    font-weight: 600;
    color: var(--accent-success);
}

.exp-display i {
    font-size: 1.5rem;
}

/* Next Lesson Info */
.next-lesson-card {
    background: var(--bg-secondary);
    border: 1px solid var(--border-light);
    border-radius: var(--border-radius);
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1.5rem;
}

.next-lesson-icon {
    width: 48px;
    height: 48px;
    background: var(--accent-primary);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2rem;
}

.next-lesson-content {
    flex: 1;
    text-align: left;
}

.next-lesson-content h4 {
    color: var(--text-primary);
    margin-bottom: 0.5rem;
}

.next-lesson-content p {
    color: var(--text-secondary);
    margin: 0;
}

/* Responsive Design */
@media (max-width: 1024px) {
    .lesson-content {
        grid-template-columns: 1fr;
    }
    
    .lesson-steps-nav {
        position: static;
        margin-bottom: 1rem;
    }
    
    .lesson-header {
        flex-direction: column;
        gap: 1.5rem;
        text-align: center;
    }
    
    .goal-item {
        justify-content: center;
    }
    
    .result-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .lesson-container {
        padding: 0.5rem 0;
    }
    
    .lesson-header,
    .lesson-progress-bar,
    .current-step-content {
        padding: 1rem;
    }
    
    .step-content {
        padding: 1rem;
    }
    
    .keyboard-layout {
        transform: scale(0.8);
        margin: 0 -10%;
    }
    
    .progress-container {
        flex-direction: column;
        gap: 1rem;
    }
    
    .progress-info {
        justify-content: center;
    }
    
    .lesson-navigation {
        flex-direction: column;
        gap: 1rem;
    }
    
    .next-lesson-card {
        flex-direction: column;
        text-align: center;
    }
}
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

<script>
class LessonManager {
    constructor() {
        this.totalSteps = {{ $lesson->steps->count() }};
        this.currentStep = 1;
        this.stepData = {};
        this.lessonStartTime = Date.now();
        this.stepResults = [];
        
        this.initializeLesson();
    }
    
    initializeLesson() {
        this.updateProgressDisplay();
        this.updateNavigationButtons();
        
        // Initialize first step if it's an exercise
        const firstStepContent = document.querySelector('.step-content.active');
        if (firstStepContent && firstStepContent.querySelector('.exercise-content')) {
            this.initializeExercise(1);
        }
    }
    
    updateProgressDisplay() {
        const progress = ((this.currentStep - 1) / this.totalSteps) * 100;
        document.getElementById('lesson-progress-fill').style.width = progress + '%';
        document.getElementById('progress-percentage').textContent = Math.round(progress) + '%';
        document.getElementById('current-step').textContent = this.currentStep;
        document.getElementById('nav-current-step').textContent = this.currentStep;
    }
    
    updateNavigationButtons() {
        const prevBtn = document.getElementById('prev-btn');
        const nextBtn = document.getElementById('nav-next-btn');
        
        prevBtn.disabled = this.currentStep === 1;
        nextBtn.textContent = this.currentStep === this.totalSteps ? 'Complete Lesson' : 'Next Step';
    }
    
    goToStep(stepNumber) {
        if (stepNumber < 1 || stepNumber > this.totalSteps) return;
        
        // Hide current step
        document.querySelector('.step-content.active').classList.remove('active');
        document.querySelector('.step-item.active').classList.remove('active');
        
        // Show new step
        document.querySelector(`[data-step="${stepNumber}"]`).classList.add('active');
        document.querySelectorAll('.step-item')[stepNumber - 1].classList.add('active');
        
        this.currentStep = stepNumber;
        this.updateProgressDisplay();
        this.updateNavigationButtons();
        
        // Initialize exercise if needed
        const currentStepContent = document.querySelector('.step-content.active');
        if (currentStepContent && currentStepContent.querySelector('.exercise-content')) {
            this.initializeExercise(stepNumber);
        }
    }
    
    initializeExercise(stepNumber) {
        const typingInput = document.getElementById(`typing-input-${stepNumber}`);
        const textContent = document.getElementById(`text-content-${stepNumber}`);
        
        if (!typingInput || !textContent) return;
        
        // Initialize step data
        this.stepData[stepNumber] = {
            startTime: null,
            endTime: null,
            currentIndex: 0,
            errors: 0,
            isStarted: false,
            isCompleted: false,
            exerciseText: typingInput.dataset.content
        };
        
        // Add event listeners
        typingInput.addEventListener('input', (e) => this.handleExerciseInput(e, stepNumber));
        typingInput.addEventListener('keydown', (e) => this.handleExerciseKeyDown(e, stepNumber));
        
        // Update virtual keyboard
        this.updateVirtualKeyboard(stepNumber);
    }
    
    startExercise(stepNumber) {
        const data = this.stepData[stepNumber];
        const typingInput = document.getElementById(`typing-input-${stepNumber}`);
        
        if (!data || data.isStarted) return;
        
        data.isStarted = true;
        data.startTime = Date.now();
        
        // Enable input and focus
        typingInput.disabled = false;
        typingInput.focus();
        
        // Update buttons
        const startBtn = document.querySelector(`#typing-input-${stepNumber}`).parentNode.querySelector('.btn-success');
        const restartBtn = document.querySelector(`#typing-input-${stepNumber}`).parentNode.querySelector('.btn-warning');
        
        startBtn.style.display = 'none';
        restartBtn.style.display = 'inline-flex';
        
        // Start updating stats
        this.startStatsUpdate(stepNumber);
    }
    
    handleExerciseInput(e, stepNumber) {
        const data = this.stepData[stepNumber];
        if (!data || !data.isStarted || data.isCompleted) return;
        
        const inputText = e.target.value;
        data.currentIndex = inputText.length;
        
        // Update character highlighting
        this.updateCharacterHighlight(stepNumber, inputText);
        
        // Update statistics
        this.updateExerciseStats(stepNumber, inputText);
        
        // Update virtual keyboard
        this.updateVirtualKeyboard(stepNumber, inputText);
        
        // Check if exercise completed
        if (data.currentIndex >= data.exerciseText.length) {
            this.completeExercise(stepNumber);
        }
    }
    
    handleExerciseKeyDown(e, stepNumber) {
        const data = this.stepData[stepNumber];
        if (!data || !data.isStarted || data.isCompleted) return;
        
        // Highlight keyboard key
        this.highlightKey(stepNumber, e.key);
        
        // Handle special keys
        if (e.key === 'Tab') {
            e.preventDefault();
        }
    }
    
    updateCharacterHighlight(stepNumber, inputText) {
        const chars = document.querySelectorAll(`#text-content-${stepNumber} .char`);
        const data = this.stepData[stepNumber];
        
        chars.forEach((char, index) => {
            char.classList.remove('correct', 'incorrect', 'current');
            
            if (index < inputText.length) {
                if (inputText[index] === data.exerciseText[index]) {
                    char.classList.add('correct');
                } else {
                    char.classList.add('incorrect');
                }
            } else if (index === inputText.length) {
                char.classList.add('current');
            }
        });
    }
    
    updateExerciseStats(stepNumber, inputText) {
        const data = this.stepData[stepNumber];
        const timeElapsed = (Date.now() - data.startTime) / 1000 / 60; // minutes
        
        // Calculate WPM
        const wordsTyped = inputText.trim().split(/\s+/).length;
        const wpm = timeElapsed > 0 ? Math.round(wordsTyped / timeElapsed) : 0;
        
        // Calculate accuracy
        let correctChars = 0;
        for (let i = 0; i < inputText.length; i++) {
            if (inputText[i] === data.exerciseText[i]) {
                correctChars++;
            }
        }
        const accuracy = inputText.length > 0 ? Math.round((correctChars / inputText.length) * 100) : 100;
        
        // Calculate progress
        const progress = Math.round((inputText.length / data.exerciseText.length) * 100);
        
        // Calculate errors
        data.errors = inputText.length - correctChars;
        
        // Update displays
        document.getElementById(`current-wpm-${stepNumber}`).textContent = wpm;
        document.getElementById(`current-accuracy-${stepNumber}`).textContent = accuracy + '%';
        document.getElementById(`exercise-progress-${stepNumber}`).textContent = progress + '%';
        document.getElementById(`current-errors-${stepNumber}`).textContent = data.errors;
    }
    
    updateVirtualKeyboard(stepNumber, inputText = '') {
        const data = this.stepData[stepNumber];
        const nextChar = data.exerciseText[inputText.length];
        
        if (!nextChar) return;
        
        // Update next key display
        document.getElementById(`next-key-${stepNumber}`).textContent = nextChar === ' ' ? 'Space' : nextChar.toUpperCase();
        
        // Update finger guide
        const fingerGuide = this.getFingerForKey(nextChar);
        document.getElementById(`finger-guide-${stepNumber}`).textContent = fingerGuide;
        
        // Highlight next key
        const keyboard = document.getElementById(`virtual-keyboard-${stepNumber}`);
        keyboard.querySelectorAll('.key').forEach(key => key.classList.remove('next'));
        
        const nextKeyElement = keyboard.querySelector(`[data-key="${nextChar.toLowerCase()}"]`);
        if (nextKeyElement) {
            nextKeyElement.classList.add('next');
        }
    }
    
    highlightKey(stepNumber, key) {
        const keyboard = document.getElementById(`virtual-keyboard-${stepNumber}`);
        keyboard.querySelectorAll('.key').forEach(k => k.classList.remove('active'));
        
        const keyElement = keyboard.querySelector(`[data-key="${key.toLowerCase()}"]`);
        if (keyElement) {
            keyElement.classList.add('active');
            setTimeout(() => keyElement.classList.remove('active'), 200);
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
    
    startStatsUpdate(stepNumber) {
        const data = this.stepData[stepNumber];
        
        data.statsInterval = setInterval(() => {
            if (data.isCompleted) {
                clearInterval(data.statsInterval);
                return;
            }
            
            const inputText = document.getElementById(`typing-input-${stepNumber}`).value;
            this.updateExerciseStats(stepNumber, inputText);
        }, 1000);
    }
    
    completeExercise(stepNumber) {
        const data = this.stepData[stepNumber];
        data.isCompleted = true;
        data.endTime = Date.now();
        
        // Clear stats interval
        if (data.statsInterval) {
            clearInterval(data.statsInterval);
        }
        
        // Disable input
        document.getElementById(`typing-input-${stepNumber}`).disabled = true;
        
        // Show next button
        const nextBtn = document.getElementById(`next-btn-${stepNumber}`);
        if (nextBtn) {
            nextBtn.style.display = 'inline-flex';
        }
        
        // Mark step as completed
        document.querySelectorAll('.step-item')[stepNumber - 1].classList.add('completed');
        
        // Calculate final results for this step
        this.calculateStepResults(stepNumber);
    }
    
    calculateStepResults(stepNumber) {
        const data = this.stepData[stepNumber];
        const inputText = document.getElementById(`typing-input-${stepNumber}`).value;
        const timeElapsed = (data.endTime - data.startTime) / 1000 / 60; // minutes
        
        const wordsTyped = inputText.trim().split(/\s+/).length;
        const wpm = timeElapsed > 0 ? Math.round(wordsTyped / timeElapsed) : 0;
        
        let correctChars = 0;
        for (let i = 0; i < inputText.length; i++) {
            if (inputText[i] === data.exerciseText[i]) {
                correctChars++;
            }
        }
        const accuracy = inputText.length > 0 ? Math.round((correctChars / inputText.length) * 100) : 0;
        
        // Store step results
        this.stepResults.push({
            step: stepNumber,
            wpm: wpm,
            accuracy: accuracy,
            timeElapsed: timeElapsed,
            errors: data.errors
        });
    }
    
    restartExercise(stepNumber) {
        const data = this.stepData[stepNumber];
        const typingInput = document.getElementById(`typing-input-${stepNumber}`);
        
        // Reset data
        data.startTime = null;
        data.endTime = null;
        data.currentIndex = 0;
        data.errors = 0;
        data.isStarted = false;
        data.isCompleted = false;
        
        // Clear input
        typingInput.value = '';
        typingInput.disabled = true;
        
        // Reset character highlighting
        const chars = document.querySelectorAll(`#text-content-${stepNumber} .char`);
        chars.forEach(char => {
            char.classList.remove('correct', 'incorrect', 'current');
        });
        
        // Reset stats display
        document.getElementById(`current-wpm-${stepNumber}`).textContent = '0';
        document.getElementById(`current-accuracy-${stepNumber}`).textContent = '100%';
        document.getElementById(`exercise-progress-${stepNumber}`).textContent = '0%';
        document.getElementById(`current-errors-${stepNumber}`).textContent = '0';
        
        // Reset buttons
        const startBtn = typingInput.parentNode.querySelector('.btn-success');
        const restartBtn = typingInput.parentNode.querySelector('.btn-warning');
        const nextBtn = document.getElementById(`next-btn-${stepNumber}`);
        
        startBtn.style.display = 'inline-flex';
        restartBtn.style.display = 'none';
        if (nextBtn) nextBtn.style.display = 'none';
        
        // Remove completed status
        document.querySelectorAll('.step-item')[stepNumber - 1].classList.remove('completed');
        
        // Clear stats interval
        if (data.statsInterval) {
            clearInterval(data.statsInterval);
        }
    }
    
    completeLesson() {
        // Calculate overall lesson results
        const totalTime = (Date.now() - this.lessonStartTime) / 1000 / 60; // minutes
        
        let totalWpm = 0;
        let totalAccuracy = 0;
        let exerciseCount = 0;
        
        this.stepResults.forEach(result => {
            if (result.wpm > 0) {
                totalWpm += result.wpm;
                totalAccuracy += result.accuracy;
                exerciseCount++;
            }
        });
        
        const avgWpm = exerciseCount > 0 ? Math.round(totalWpm / exerciseCount) : 0;
        const avgAccuracy = exerciseCount > 0 ? Math.round(totalAccuracy / exerciseCount) : 0;
        
        // Calculate score (0-100)
        const targetWpm = {{ $lesson->target_wpm }};
        const targetAccuracy = {{ $lesson->target_accuracy }};
        
        const wpmScore = Math.min(100, (avgWpm / targetWpm) * 50);
        const accuracyScore = Math.min(50, (avgAccuracy / 100) * 50);
        const finalScore = Math.round(wpmScore + accuracyScore);
        
        // Show completion modal
        this.showCompletionModal(avgWpm, avgAccuracy, totalTime, finalScore);
    }
    
    showCompletionModal(avgWpm, avgAccuracy, totalTime, score) {
        // Update modal content
        document.getElementById('final-lesson-wpm').textContent = avgWpm;
        document.getElementById('final-lesson-accuracy').textContent = avgAccuracy + '%';
        
        const minutes = Math.floor(totalTime);
        const seconds = Math.floor((totalTime % 1) * 60);
        document.getElementById('final-lesson-time').textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;
        document.getElementById('lesson-score').textContent = score;
        
        // Update goals achievement
        const targetWpm = {{ $lesson->target_wpm }};
        const targetAccuracy = {{ $lesson->target_accuracy }};
        
        const wpmGoalStatus = document.getElementById('wpm-goal-status');
        const wpmGoalResult = document.getElementById('wpm-goal-result');
        const accuracyGoalStatus = document.getElementById('accuracy-goal-status');
        const accuracyGoalResult = document.getElementById('accuracy-goal-result');
        
        if (avgWpm >= targetWpm) {
            wmpGoalStatus.classList.add('success');
            wmpGoalResult.textContent = 'Achieved!';
            wmpGoalResult.classList.add('success');
        } else {
            wmpGoalStatus.classList.add('failed');
            wmpGoalResult.textContent = `${avgWpm}/${targetWpm} WPM`;
            wmpGoalResult.classList.add('failed');
        }
        
        if (avgAccuracy >= targetAccuracy) {
            accuracyGoalStatus.classList.add('success');
            accuracyGoalResult.textContent = 'Achieved!';
            accuracyGoalResult.classList.add('success');
        } else {
            accuracyGoalStatus.classList.add('failed');
            accuracyGoalResult.textContent = `${avgAccuracy}%/${targetAccuracy}%`;
            accuracyGoalResult.classList.add('failed');
        }
        
        // Calculate EXP
        const baseExp = 50;
        const scoreBonus = Math.floor(score / 10) * 5;
        const totalExp = baseExp + scoreBonus;
        document.getElementById('exp-gained').textContent = totalExp;
        
        // Show next lesson info if available
        @if(isset($nextLesson))
        document.getElementById('next-lesson-info').style.display = 'block';
        document.getElementById('next-lesson-title').textContent = '{{ $nextLesson->title }}';
        document.getElementById('next-lesson-btn').href = '{{ route("lessons.show", $nextLesson) }}';
        @endif
        
        // Show modal
        const modal = new bootstrap.Modal(document.getElementById('lessonCompleteModal'));
        modal.show();
    }
}

// Global functions
let lessonManager;

document.addEventListener('DOMContentLoaded', function() {
    lessonManager = new LessonManager();
});

function nextStep() {
    if (lessonManager.currentStep === lessonManager.totalSteps) {
        lessonManager.completeLesson();
    } else {
        lessonManager.goToStep(lessonManager.currentStep + 1);
    }
}

function previousStep() {
    lessonManager.goToStep(lessonManager.currentStep - 1);
}

function startExercise(stepNumber) {
    lessonManager.startExercise(stepNumber);
}

function restartExercise(stepNumber) {
    lessonManager.restartExercise(stepNumber);
}

function retryLesson() {
    location.reload();
}

function saveProgress() {
    // In real application, save progress to server
    alert('Progress saved successfully!');
    
    // Close modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('lessonCompleteModal'));
    if (modal) {
        modal.hide();
    }
}
</script>
@endsection