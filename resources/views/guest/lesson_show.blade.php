@extends('layouts.app')

@section('content')
<div class="lesson-interface-container">
    <div class="container-fluid">
        <!-- Lesson Header -->
        <div class="lesson-header">
            <div class="lesson-info">
                <div class="breadcrumb">
                    <a href="{{ route('guest.lessons') }}">
                        <i class="fas fa-arrow-left"></i>
                        Back to Lessons
                    </a>
                </div>
                <h1 id="lessonTitle">Home Row Basics</h1>
                <div class="lesson-meta">
                    <span class="lesson-type">Beginner</span>
                    <span class="lesson-duration">10 minutes</span>
                    <span class="lesson-progress">Lesson 1 of 4</span>
                </div>
            </div>
            <div class="lesson-controls">
                <button class="btn btn-secondary" onclick="resetLesson()">
                    <i class="fas fa-redo"></i>
                    Reset
                </button>
            </div>
        </div>

        <!-- Progress Indicator -->
        <div class="lesson-progress-bar">
            <div class="progress-container">
                <div class="progress-bar">
                    <div class="progress-fill" id="lessonProgress" style="width: 0%"></div>
                </div>
                <div class="progress-info">
                    <span id="currentStep">Step 1</span>
                    <span id="totalSteps">of 5</span>
                </div>
            </div>
        </div>

        <!-- Lesson Content -->
        <div class="lesson-content">
            <!-- Step 1: Introduction -->
            <div class="lesson-step active" id="step1">
                <div class="step-content">
                    <div class="instruction-panel">
                        <div class="instruction-header">
                            <div class="step-icon">
                                <i class="fas fa-info-circle"></i>
                            </div>
                            <h2>Welcome to Home Row Basics!</h2>
                        </div>
                        <div class="instruction-text">
                            <p>The home row is the foundation of touch typing. Your fingers should rest on these keys when not typing:</p>
                            <ul class="key-list">
                                <li><strong>Left hand:</strong> A S D F (pinky to index finger)</li>
                                <li><strong>Right hand:</strong> J K L ; (index to pinky finger)</li>
                                <li><strong>Thumbs:</strong> Space bar</li>
                            </ul>
                            <p>The F and J keys have small bumps to help you find the home position without looking!</p>
                        </div>
                        <div class="instruction-actions">
                            <button class="btn btn-primary" onclick="nextStep()">
                                <i class="fas fa-arrow-right"></i>
                                Continue
                            </button>
                        </div>
                    </div>
                    <div class="visual-panel">
                        <div class="keyboard-visual">
                            <div class="keyboard">
                                <!-- Top Row -->
                                <div class="key-row">
                                    <div class="key">Q</div>
                                    <div class="key">W</div>
                                    <div class="key">E</div>
                                    <div class="key">R</div>
                                    <div class="key">T</div>
                                    <div class="key">Y</div>
                                    <div class="key">U</div>
                                    <div class="key">I</div>
                                    <div class="key">O</div>
                                    <div class="key">P</div>
                                </div>
                                <!-- Home Row -->
                                <div class="key-row home-row">
                                    <div class="key home-key left-pinky">A</div>
                                    <div class="key home-key left-ring">S</div>
                                    <div class="key home-key left-middle">D</div>
                                    <div class="key home-key left-index">F<span class="key-bump"></span></div>
                                    <div class="key">G</div>
                                    <div class="key">H</div>
                                    <div class="key home-key right-index">J<span class="key-bump"></span></div>
                                    <div class="key home-key right-middle">K</div>
                                    <div class="key home-key right-ring">L</div>
                                    <div class="key home-key right-pinky">;</div>
                                </div>
                                <!-- Bottom Row -->
                                <div class="key-row">
                                    <div class="key">Z</div>
                                    <div class="key">X</div>
                                    <div class="key">C</div>
                                    <div class="key">V</div>
                                    <div class="key">B</div>
                                    <div class="key">N</div>
                                    <div class="key">M</div>
                                    <div class="key">,</div>
                                    <div class="key">.</div>
                                    <div class="key">/</div>
                                </div>
                                <!-- Space Bar -->
                                <div class="key-row">
                                    <div class="key space-bar">Space</div>
                                </div>
                            </div>
                            <div class="hand-indicators">
                                <div class="hand left-hand">
                                    <span class="finger pinky">Pinky</span>
                                    <span class="finger ring">Ring</span>
                                    <span class="finger middle">Middle</span>
                                    <span class="finger index">Index</span>
                                </div>
                                <div class="hand right-hand">
                                    <span class="finger index">Index</span>
                                    <span class="finger middle">Middle</span>
                                    <span class="finger ring">Ring</span>
                                    <span class="finger pinky">Pinky</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 2: Finger Positioning -->
            <div class="lesson-step" id="step2">
                <div class="step-content">
                    <div class="instruction-panel">
                        <div class="instruction-header">
                            <div class="step-icon">
                                <i class="fas fa-hand-paper"></i>
                            </div>
                            <h2>Proper Hand Position</h2>
                        </div>
                        <div class="instruction-text">
                            <p>Now let's practice the correct hand position:</p>
                            <ol>
                                <li>Sit up straight with feet flat on the floor</li>
                                <li>Keep your wrists straight and floating above the keyboard</li>
                                <li>Curve your fingers slightly as if holding a small ball</li>
                                <li>Place your fingers on the home row keys</li>
                                <li>Rest your thumbs lightly on the space bar</li>
                            </ol>
                            <div class="tip-box">
                                <i class="fas fa-lightbulb"></i>
                                <p><strong>Tip:</strong> Your fingers should barely touch the keys - don't press down!</p>
                            </div>
                        </div>
                        <div class="instruction-actions">
                            <button class="btn btn-outline-primary" onclick="prevStep()">
                                <i class="fas fa-arrow-left"></i>
                                Previous
                            </button>
                            <button class="btn btn-primary" onclick="nextStep()">
                                <i class="fas fa-arrow-right"></i>
                                Continue
                            </button>
                        </div>
                    </div>
                    <div class="visual-panel">
                        <div class="posture-guide">
                            <div class="posture-image">
                                <div class="typing-posture">
                                    <div class="person-silhouette">
                                        <div class="head"></div>
                                        <div class="shoulders"></div>
                                        <div class="arms">
                                            <div class="arm left-arm"></div>
                                            <div class="arm right-arm"></div>
                                        </div>
                                        <div class="hands">
                                            <div class="hand left-hand-pos"></div>
                                            <div class="hand right-hand-pos"></div>
                                        </div>
                                        <div class="torso"></div>
                                    </div>
                                    <div class="keyboard-base"></div>
                                </div>
                            </div>
                            <div class="posture-checklist">
                                <h3>Posture Checklist</h3>
                                <div class="checklist-item">
                                    <i class="fas fa-check-circle"></i>
                                    <span>Back straight</span>
                                </div>
                                <div class="checklist-item">
                                    <i class="fas fa-check-circle"></i>
                                    <span>Feet flat on floor</span>
                                </div>
                                <div class="checklist-item">
                                    <i class="fas fa-check-circle"></i>
                                    <span>Wrists floating</span>
                                </div>
                                <div class="checklist-item">
                                    <i class="fas fa-check-circle"></i>
                                    <span>Fingers curved</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 3: Practice Exercise -->
            <div class="lesson-step" id="step3">
                <div class="step-content">
                    <div class="instruction-panel">
                        <div class="instruction-header">
                            <div class="step-icon">
                                <i class="fas fa-keyboard"></i>
                            </div>
                            <h2>Practice Time!</h2>
                        </div>
                        <div class="instruction-text">
                            <p>Now let's practice typing some home row keys. Type each character as it appears:</p>
                            <div class="practice-stats">
                                <div class="stat">
                                    <span class="stat-label">Accuracy:</span>
                                    <span class="stat-value" id="practiceAccuracy">100%</span>
                                </div>
                                <div class="stat">
                                    <span class="stat-label">Errors:</span>
                                    <span class="stat-value error" id="practiceErrors">0</span>
                                </div>
                            </div>
                        </div>
                        <div class="practice-area">
                            <div class="target-text" id="targetText">
                                a s d f j k l ;
                            </div>
                            <input type="text" id="practiceInput" class="practice-input" 
                                   placeholder="Start typing here..." 
                                   disabled>
                            <div class="practice-controls">
                                <button class="btn btn-primary" id="startPracticeBtn" onclick="startPractice()">
                                    <i class="fas fa-play"></i>
                                    Start Practice
                                </button>
                            </div>
                        </div>
                        <div class="instruction-actions">
                            <button class="btn btn-outline-primary" onclick="prevStep()">
                                <i class="fas fa-arrow-left"></i>
                                Previous
                            </button>
                            <button class="btn btn-primary" id="continueBtn" onclick="nextStep()" disabled>
                                <i class="fas fa-arrow-right"></i>
                                Continue
                            </button>
                        </div>
                    </div>
                    <div class="visual-panel">
                        <div class="keyboard-visual">
                            <div class="keyboard practice-keyboard">
                                <!-- Home Row -->
                                <div class="key-row home-row">
                                    <div class="key home-key" id="key-a">A</div>
                                    <div class="key home-key" id="key-s">S</div>
                                    <div class="key home-key" id="key-d">D</div>
                                    <div class="key home-key" id="key-f">F</div>
                                    <div class="key">G</div>
                                    <div class="key">H</div>
                                    <div class="key home-key" id="key-j">J</div>
                                    <div class="key home-key" id="key-k">K</div>
                                    <div class="key home-key" id="key-l">L</div>
                                    <div class="key home-key" id="key-semicolon">;</div>
                                </div>
                            </div>
                            <div class="finger-guide">
                                <p>Watch the highlighted keys and use the correct finger!</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 4: Completion -->
            <div class="lesson-step" id="step4">
                <div class="step-content completion-step">
                    <div class="completion-content">
                        <div class="completion-icon">
                            <i class="fas fa-trophy"></i>
                        </div>
                        <h2>Congratulations!</h2>
                        <p>You've completed the Home Row Basics lesson!</p>
                        
                        <div class="lesson-summary">
                            <h3>What you learned:</h3>
                            <ul>
                                <li>Home row key positions (ASDF JKL;)</li>
                                <li>Proper typing posture</li>
                                <li>Correct finger placement</li>
                                <li>Basic key practice</li>
                            </ul>
                        </div>

                        <div class="next-steps">
                            <h3>Next Steps:</h3>
                            <div class="next-lesson-card">
                                <div class="lesson-icon">
                                    <i class="fas fa-play"></i>
                                </div>
                                <div class="lesson-info">
                                    <h4>Home Row Words</h4>
                                    <p>Practice typing common words using only home row keys</p>
                                </div>
                                <button class="btn btn-primary" onclick="nextLesson()">
                                    <i class="fas fa-arrow-right"></i>
                                    Start
                                </button>
                            </div>
                        </div>

                        <div class="completion-actions">
                            <button class="btn btn-outline-primary" onclick="repeatLesson()">
                                <i class="fas fa-redo"></i>
                                Repeat Lesson
                            </button>
                            <a href="{{ route('guest.lessons') }}" class="btn btn-secondary">
                                <i class="fas fa-list"></i>
                                All Lessons
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.lesson-interface-container {
    padding: 1rem 0;
    min-height: calc(100vh - 80px);
}

/* Lesson Header */
.lesson-header {
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

.lesson-header::before {
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

.lesson-info h1 {
    font-size: 2rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 1rem;
}

.lesson-meta {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.lesson-type, .lesson-duration, .lesson-progress {
    padding: 0.5rem 1rem;
    border-radius: var(--border-radius);
    font-size: 0.85rem;
    font-weight: 600;
}

.lesson-type {
    background: rgba(16, 185, 129, 0.1);
    color: var(--success);
    border: 1px solid rgba(16, 185, 129, 0.2);
}

.lesson-duration {
    background: rgba(59, 130, 246, 0.1);
    color: var(--info);
    border: 1px solid rgba(59, 130, 246, 0.2);
}

.lesson-progress {
    background: rgba(245, 158, 11, 0.1);
    color: var(--warning);
    border: 1px solid rgba(245, 158, 11, 0.2);
}

/* Progress Bar */
.lesson-progress-bar {
    margin-bottom: 2rem;
    padding: 1.5rem;
    background: var(--gradient-card);
    backdrop-filter: blur(var(--blur-amount));
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius-lg);
}

.progress-container {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.progress-bar {
    flex: 1;
    height: 10px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 5px;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    background: var(--gradient-button);
    border-radius: 5px;
    transition: width 0.5s ease;
}

.progress-info {
    color: var(--text-secondary);
    font-size: 0.9rem;
    white-space: nowrap;
}

/* Lesson Steps */
.lesson-content {
    background: var(--gradient-card);
    backdrop-filter: blur(var(--blur-amount));
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius-lg);
    padding: 2rem;
    min-height: 600px;
}

.lesson-step {
    display: none;
    animation: fadeIn 0.5s ease-in-out;
}

.lesson-step.active {
    display: block;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.step-content {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 3rem;
    align-items: start;
}

.completion-step .step-content {
    grid-template-columns: 1fr;
    text-align: center;
}

.instruction-panel {
    padding: 2rem;
    background: rgba(255, 255, 255, 0.03);
    border-radius: var(--border-radius-lg);
    border: 1px solid rgba(255, 255, 255, 0.05);
}

.instruction-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 2rem;
}

.step-icon {
    width: 50px;
    height: 50px;
    background: var(--gradient-button);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    color: white;
}

.instruction-header h2 {
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--text-primary);
}

.instruction-text {
    margin-bottom: 2rem;
    color: var(--text-secondary);
    line-height: 1.6;
}

.instruction-text p {
    margin-bottom: 1rem;
}

.key-list {
    background: rgba(255, 255, 255, 0.05);
    padding: 1rem;
    border-radius: var(--border-radius);
    border: 1px solid rgba(255, 255, 255, 0.1);
    margin: 1rem 0;
}

.key-list li {
    color: var(--text-primary);
    margin-bottom: 0.5rem;
}

.tip-box {
    background: rgba(245, 158, 11, 0.1);
    border: 1px solid rgba(245, 158, 11, 0.2);
    padding: 1rem;
    border-radius: var(--border-radius);
    margin: 1rem 0;
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
}

.tip-box i {
    color: var(--warning);
    margin-top: 0.25rem;
}

.instruction-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
}

/* Visual Panel */
.visual-panel {
    padding: 2rem;
    background: rgba(255, 255, 255, 0.03);
    border-radius: var(--border-radius-lg);
    border: 1px solid rgba(255, 255, 255, 0.05);
}

/* Keyboard Visual */
.keyboard {
    background: rgba(255, 255, 255, 0.05);
    padding: 1rem;
    border-radius: var(--border-radius);
    border: 1px solid rgba(255, 255, 255, 0.1);
    margin-bottom: 1rem;
}

.key-row {
    display: flex;
    justify-content: center;
    gap: 0.25rem;
    margin-bottom: 0.25rem;
}

.key {
    width: 40px;
    height: 40px;
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--text-primary);
    font-weight: 600;
    font-size: 0.9rem;
    position: relative;
    transition: all 0.3s ease;
}

.key.home-key {
    background: var(--gradient-button);
    color: white;
    box-shadow: 0 2px 8px rgba(255, 107, 157, 0.3);
}

.key.active {
    background: var(--accent-cyan);
    transform: scale(1.1);
    box-shadow: 0 0 15px var(--accent-cyan);
}

.key-bump {
    position: absolute;
    bottom: 2px;
    width: 8px;
    height: 2px;
    background: rgba(255, 255, 255, 0.5);
    border-radius: 1px;
}

.space-bar {
    width: 300px;
    margin: 0.5rem 0;
}

.hand-indicators {
    display: flex;
    justify-content: space-between;
    margin-top: 1rem;
}

.hand {
    display: flex;
    gap: 0.5rem;
}

.finger {
    padding: 0.25rem 0.5rem;
    background: rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius);
    font-size: 0.8rem;
    color: var(--text-secondary);
}

/* Posture Guide */
.posture-guide {
    text-align: center;
}

.typing-posture {
    position: relative;
    width: 200px;
    height: 250px;
    margin: 0 auto 2rem;
    background: rgba(255, 255, 255, 0.05);
    border-radius: var(--border-radius);
    border: 1px solid rgba(255, 255, 255, 0.1);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

.person-silhouette {
    position: relative;
}

.head {
    width: 30px;
    height: 30px;
    background: var(--gradient-button);
    border-radius: 50%;
    margin: 0 auto 10px;
}

.shoulders {
    width: 60px;
    height: 8px;
    background: var(--gradient-button);
    border-radius: 4px;
    margin: 0 auto 5px;
}

.arms {
    display: flex;
    justify-content: space-between;
    width: 80px;
    margin: 0 auto 10px;
}

.arm {
    width: 6px;
    height: 40px;
    background: var(--gradient-button);
    border-radius: 3px;
}

.hands {
    display: flex;
    justify-content: space-between;
    width: 100px;
    margin: 0 auto 10px;
}

.hand {
    width: 12px;
    height: 12px;
    background: var(--gradient-button);
    border-radius: 50%;
}

.torso {
    width: 40px;
    height: 60px;
    background: var(--gradient-button);
    border-radius: 8px;
    margin: 0 auto;
}

.keyboard-base {
    width: 120px;
    height: 8px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 4px;
    margin-top: 10px;
}

.posture-checklist {
    text-align: left;
}

.posture-checklist h3 {
    color: var(--text-primary);
    margin-bottom: 1rem;
}

.checklist-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 0.75rem;
    color: var(--text-secondary);
}

.checklist-item i {
    color: var(--success);
}

/* Practice Area */
.practice-area {
    background: rgba(255, 255, 255, 0.05);
    padding: 2rem;
    border-radius: var(--border-radius);
    border: 1px solid rgba(255, 255, 255, 0.1);
    margin: 1rem 0;
}

.practice-stats {
    display: flex;
    gap: 2rem;
    margin-bottom: 1.5rem;
}

.stat {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.stat-label {
    color: var(--text-secondary);
    font-size: 0.9rem;
}

.stat-value {
    color: var(--text-primary);
    font-weight: 600;
}

.stat-value.error {
    color: var(--error);
}

.target-text {
    font-family: 'Courier New', monospace;
    font-size: 1.5rem;
    color: var(--text-primary);
    text-align: center;
    padding: 1rem;
    background: rgba(255, 255, 255, 0.05);
    border-radius: var(--border-radius);
    border: 1px solid rgba(255, 255, 255, 0.1);
    margin-bottom: 1rem;
    letter-spacing: 0.2em;
}

.practice-input {
    width: 100%;
    background: rgba(255, 255, 255, 0.05);
    border: 2px solid rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius);
    color: var(--text-primary);
    padding: 1rem;
    font-family: 'Courier New', monospace;
    font-size: 1.2rem;
    text-align: center;
    letter-spacing: 0.2em;
    transition: all 0.3s ease;
}

.practice-input:focus {
    outline: none;
    border-color: var(--accent-pink);
    background: rgba(255, 255, 255, 0.08);
    box-shadow: 0 0 0 3px rgba(255, 107, 157, 0.1);
}

.practice-controls {
    text-align: center;
    margin-top: 1rem;
}

.finger-guide {
    text-align: center;
    color: var(--text-secondary);
    font-size: 0.9rem;
    margin-top: 1rem;
}

/* Completion Step */
.completion-content {
    max-width: 600px;
    margin: 0 auto;
}

.completion-icon {
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

.completion-content h2 {
    font-size: 2rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 1rem;
}

.completion-content > p {
    color: var(--text-secondary);
    font-size: 1.1rem;
    margin-bottom: 2rem;
}

.lesson-summary {
    background: rgba(255, 255, 255, 0.05);
    padding: 2rem;
    border-radius: var(--border-radius);
    border: 1px solid rgba(255, 255, 255, 0.1);
    margin-bottom: 2rem;
    text-align: left;
}

.lesson-summary h3 {
    color: var(--text-primary);
    margin-bottom: 1rem;
}

.lesson-summary ul {
    color: var(--text-secondary);
}

.lesson-summary li {
    margin-bottom: 0.5rem;
}

.next-steps {
    margin-bottom: 2rem;
}

.next-steps h3 {
    color: var(--text-primary);
    margin-bottom: 1rem;
}

.next-lesson-card {
    display: flex;
    align-items: center;
    gap: 1rem;
    background: rgba(255, 255, 255, 0.05);
    padding: 1.5rem;
    border-radius: var(--border-radius);
    border: 1px solid rgba(255, 255, 255, 0.1);
    text-align: left;
}

.lesson-icon {
    width: 50px;
    height: 50px;
    background: var(--gradient-button);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    flex-shrink: 0;
}

.lesson-info {
    flex: 1;
}

.lesson-info h4 {
    color: var(--text-primary);
    margin-bottom: 0.5rem;
}

.lesson-info p {
    color: var(--text-secondary);
    font-size: 0.9rem;
}

.completion-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

/* Responsive */
@media (max-width: 1024px) {
    .lesson-header {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
    
    .step-content {
        grid-template-columns: 1fr;
        gap: 2rem;
    }
    
    .progress-container {
        flex-direction: column;
        gap: 0.5rem;
    }
}

@media (max-width: 768px) {
    .lesson-info h1 {
        font-size: 1.5rem;
    }
    
    .lesson-meta {
        justify-content: center;
    }
    
    .instruction-actions {
        flex-direction: column;
    }
    
    .completion-actions {
        flex-direction: column;
    }
    
    .key {
        width: 35px;
        height: 35px;
        font-size: 0.8rem;
    }
    
    .space-bar {
        width: 250px;
    }
    
    .practice-stats {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
}
</style>

<script>
// Lesson state management
let currentStep = 1;
const totalSteps = 4;
let practiceStarted = false;
let practiceCompleted = false;
let practiceText = "a s d f j k l ;";
let practiceInput = "";
let practiceErrors = 0;

// Key mappings for visual feedback
const keyMappings = {
    'a': 'key-a',
    's': 'key-s', 
    'd': 'key-d',
    'f': 'key-f',
    'j': 'key-j',
    'k': 'key-k',
    'l': 'key-l',
    ';': 'key-semicolon'
};

document.addEventListener('DOMContentLoaded', function() {
    updateProgress();
    setupPracticeInput();
});

function updateProgress() {
    const progressPercentage = (currentStep / totalSteps) * 100;
    document.getElementById('lessonProgress').style.width = progressPercentage + '%';
    document.getElementById('currentStep').textContent = Step ${currentStep};
    document.getElementById('totalSteps').textContent = of ${totalSteps};
}

function nextStep() {
    if (currentStep === 3 && !practiceCompleted) {
        alert('Please complete the practice exercise first!');
        return;
    }
    
    if (currentStep < totalSteps) {
        document.getElementById(step${currentStep}).classList.remove('active');
        currentStep++;
        document.getElementById(step${currentStep}).classList.add('active');
        updateProgress();
        
        // Special handling for completion step
        if (currentStep === totalSteps) {
            document.getElementById('lessonProgress').style.width = '100%';
        }
    }
}

function prevStep() {
    if (currentStep > 1) {
        document.getElementById(step${currentStep}).classList.remove('active');
        currentStep--;
        document.getElementById(step${currentStep}).classList.add('active');
        updateProgress();
    }
}

function setupPracticeInput() {
    const input = document.getElementById('practiceInput');
    
    input.addEventListener('input', function(e) {
        if (!practiceStarted) return;
        
        practiceInput = e.target.value;
        checkPracticeInput();
        highlightCurrentKey();
        updatePracticeStats();
        
        // Check if practice is complete
        if (practiceInput.length >= practiceText.length) {
            completePractice();
        }
    });
    
    input.addEventListener('keydown', function(e) {
        if (!practiceStarted) return;
        
        // Prevent certain keys
        if (e.key === 'Backspace' && practiceInput.length === 0) {
            e.preventDefault();
        }
    });
}

function startPractice() {
    practiceStarted = true;
    practiceInput = "";
    practiceErrors = 0;
    
    const input = document.getElementById('practiceInput');
    const startBtn = document.getElementById('startPracticeBtn');
    
    input.disabled = false;
    input.focus();
    input.value = "";
    
    startBtn.style.display = 'none';
    
    // Highlight first key
    highlightCurrentKey();
    updatePracticeStats();
}

function checkPracticeInput() {
    const targetDiv = document.getElementById('targetText');
    let highlighted = '';
    let errors = 0;
    
    for (let i = 0; i < practiceText.length; i++) {
        const targetChar = practiceText[i];
        const inputChar = practiceInput[i];
        
        if (i < practiceInput.length) {
            if (inputChar === targetChar) {
                highlighted += <span style="background: rgba(16, 185, 129, 0.3); color: var(--success);">${targetChar}</span>;
            } else {
                highlighted += <span style="background: rgba(239, 68, 68, 0.3); color: var(--error);">${targetChar}</span>;
                errors++;
            }
        } else if (i === practiceInput.length) {
            highlighted += <span style="background: var(--accent-pink); color: white;">${targetChar}</span>;
        } else {
            highlighted += targetChar;
        }
    }
    
    targetDiv.innerHTML = highlighted;
    practiceErrors = errors;
}

function highlightCurrentKey() {
    // Clear all key highlights
    document.querySelectorAll('.key').forEach(key => {
        key.classList.remove('active');
    });
    
    // Highlight current key
    if (practiceInput.length < practiceText.length) {
        const currentChar = practiceText[practiceInput.length];
        const keyId = keyMappings[currentChar];
        
        if (keyId) {
            const keyElement = document.getElementById(keyId);
            if (keyElement) {
                keyElement.classList.add('active');
            }
        }
    }
}

function updatePracticeStats() {
    const accuracy = practiceInput.length > 0 ? 
        Math.round(((practiceInput.length - practiceErrors) / practiceInput.length) * 100) : 100;
    
    document.getElementById('practiceAccuracy').textContent = accuracy + '%';
    document.getElementById('practiceErrors').textContent = practiceErrors;
}

function completePractice() {
    practiceCompleted = true;
    practiceStarted = false;
    
    const input = document.getElementById('practiceInput');
    const continueBtn = document.getElementById('continueBtn');
    
    input.disabled = true;
    continueBtn.disabled = false;
    
    // Clear key highlights
    document.querySelectorAll('.key').forEach(key => {
        key.classList.remove('active');
    });
    
    // Show completion feedback
    const accuracy = Math.round(((practiceInput.length - practiceErrors) / practiceInput.length) * 100);
    
    if (accuracy >= 90) {
        showNotification('Excellent work! 🎉', 'success');
    } else if (accuracy >= 80) {
        showNotification('Good job! Keep practicing! 👍', 'info');
    } else {
        showNotification('Keep practicing to improve accuracy! 📚', 'warning');
    }
}

function showNotification(message, type) {
    // Create notification element
    const notification = document.createElement('div');
    notification.style.cssText = `
        position: fixed;
        top: 100px;
        right: 20px;
        background: var(--gradient-card);
        backdrop-filter: blur(var(--blur-amount));
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: var(--border-radius);
        padding: 1rem 1.5rem;
        color: var(--text-primary);
        z-index: 1000;
        animation: slideInRight 0.3s ease-out;
    `;
    
    notification.textContent = message;
    document.body.appendChild(notification);
    
    // Remove after 3 seconds
    setTimeout(() => {
        notification.style.animation = 'slideOutRight 0.3s ease-out';
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 3000);
    
    // Add keyframes
    if (!document.getElementById('notification-styles')) {
        const style = document.createElement('style');
        style.id = 'notification-styles';
        style.textContent = `
            @keyframes slideInRight {
                from { transform: translateX(100%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
            @keyframes slideOutRight {
                from { transform: translateX(0); opacity: 1; }
                to { transform: translateX(100%); opacity: 0; }
            }
        `;
        document.head.appendChild(style);
    }
}

function resetLesson() {
    currentStep = 1;
    practiceStarted = false;
    practiceCompleted = false;
    practiceInput = "";
    practiceErrors = 0;
    
    // Reset UI
    document.querySelectorAll('.lesson-step').forEach(step => {
        step.classList.remove('active');
    });
    document.getElementById('step1').classList.add('active');
    
    // Reset practice
    const input = document.getElementById('practiceInput');
    const startBtn = document.getElementById('startPracticeBtn');
    const continueBtn = document.getElementById('continueBtn');
    
    input.disabled = true;
    input.value = '';
    startBtn.style.display = 'inline-flex';
    continueBtn.disabled = true;
    
    document.getElementById('targetText').textContent = practiceText;
    document.getElementById('practiceAccuracy').textContent = '100%';
    document.getElementById('practiceErrors').textContent = '0';
    
    // Clear key highlights
    document.querySelectorAll('.key').forEach(key => {
        key.classList.remove('active');
    });
    
    updateProgress();
}

function repeatLesson() {
    resetLesson();
}

function nextLesson() {
    // Navigate to next lesson
    window.location.href = {{ route('guest.lessons.show') }}?lesson=home-row-words;
}

// Add keyboard shortcuts
document.addEventListener('keydown', function(e) {
    if (e.target.id === 'practiceInput') return;
    
    if (e.key === 'ArrowRight' && currentStep < totalSteps) {
        nextStep();
    } else if (e.key === 'ArrowLeft' && currentStep > 1) {
        prevStep();
    }
});
</script>
@endsection