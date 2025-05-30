@extends('layouts.app')

@section('content')
<div class="practice-interface-container">
    <div class="container-fluid">
        <!-- Practice Header -->
        <div class="practice-header">
            <div class="header-navigation">
                <a href="{{ route('practice.index') }}" class="back-btn">
                    <i class="fas fa-arrow-left"></i>
                    <span>Back to Practice</span>
                </a>
                
                <div class="text-info">
                    <h1 class="text-title">{{ $text->title }}</h1>
                    <div class="text-meta">
                        <span class="category">
                            <i class="fas fa-{{ $text->category->name == 'Programming' ? 'code' : ($text->category->name == 'Literature' ? 'book' : ($text->category->name == 'Science' ? 'flask' : ($text->category->name == 'Technology' ? 'microchip' : ($text->category->name == 'Business' ? 'briefcase' : 'file-text')))) }}"></i>
                            {{ $text->category->name }}
                        </span>
                        <span class="difficulty difficulty-{{ $text->difficulty_level }}">
                            {{ ucfirst($text->difficulty_level) }}
                        </span>
                        <span class="word-count">
                            <i class="fas fa-font"></i>
                            {{ $text->word_count }} words
                        </span>
                    </div>
                </div>
            </div>
            
            @auth
            <div class="header-stats">
                @php
                    $userBest = Auth::user()->practices()
                        ->where('text_id', $text->id)
                        ->orderBy('typing_speed', 'desc')
                        ->first();
                    $userStats = Auth::user()->practices()
                        ->where('text_id', $text->id)
                        ->selectRaw('AVG(typing_speed) as avg_speed, AVG(typing_accuracy) as avg_accuracy, COUNT(*) as attempts')
                        ->first();
                @endphp
                
                <div class="stat-item">
                    <div class="stat-label">Personal Best</div>
                    <div class="stat-value">{{ $userBest ? number_format($userBest->typing_speed) : 0 }} WPM</div>
                </div>
                
                <div class="stat-item">
                    <div class="stat-label">Your Average</div>
                    <div class="stat-value">{{ $userStats && $userStats->avg_speed ? number_format($userStats->avg_speed) : 0 }} WPM</div>
                </div>
                
                <div class="stat-item">
                    <div class="stat-label">Attempts</div>
                    <div class="stat-value">{{ $userStats ? $userStats->attempts : 0 }}</div>
                </div>
            </div>
            @endauth
        </div>

        <!-- Practice Interface -->
        <div class="practice-main">
            <!-- Live Statistics Dashboard -->
            <div class="live-stats-dashboard">
                <div class="stats-grid">
                    <div class="live-stat-card wpm">
                        <div class="stat-icon">
                            <i class="fas fa-tachometer-alt"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-value" id="liveWPM">0</div>
                            <div class="stat-label">Words/Min</div>
                            <div class="stat-change" id="wpmChange">--</div>
                        </div>
                        <div class="stat-chart" id="wpmChart">
                            <canvas width="100" height="40"></canvas>
                        </div>
                    </div>
                    
                    <div class="live-stat-card accuracy">
                        <div class="stat-icon">
                            <i class="fas fa-bullseye"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-value" id="liveAccuracy">100</div>
                            <div class="stat-label">Accuracy %</div>
                            <div class="stat-change" id="accuracyChange">--</div>
                        </div>
                        <div class="accuracy-ring" id="accuracyRing">
                            <svg viewBox="0 0 100 100">
                                <circle cx="50" cy="50" r="45" fill="none" stroke="var(--border-light)" stroke-width="6"/>
                                <circle cx="50" cy="50" r="45" fill="none" stroke="var(--accent-success)" stroke-width="6" 
                                        stroke-dasharray="283" stroke-dashoffset="283" id="accuracyCircle"/>
                            </svg>
                        </div>
                    </div>
                    
                    <div class="live-stat-card time">
                        <div class="stat-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-value" id="liveTime">0:00</div>
                            <div class="stat-label">Elapsed</div>
                            <div class="stat-change" id="estimatedFinish">--</div>
                        </div>
                        <div class="time-progress">
                            <div class="progress-ring">
                                <svg viewBox="0 0 100 100">
                                    <circle cx="50" cy="50" r="45" fill="none" stroke="var(--border-light)" stroke-width="6"/>
                                    <circle cx="50" cy="50" r="45" fill="none" stroke="var(--accent-primary)" stroke-width="6" 
                                            stroke-dasharray="283" stroke-dashoffset="283" id="timeCircle"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    <div class="live-stat-card progress">
                        <div class="stat-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-value" id="liveProgress">0</div>
                            <div class="stat-label">Progress %</div>
                            <div class="stat-change" id="remainingWords">{{ $text->word_count }} words left</div>
                        </div>
                        <div class="progress-visualization">
                            <div class="progress-bar">
                                <div class="progress-fill" id="progressFill"></div>
                            </div>
                            <div class="progress-markers">
                                <span class="marker" style="left: 25%">25%</span>
                                <span class="marker" style="left: 50%">50%</span>
                                <span class="marker" style="left: 75%">75%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Typing Interface -->
            <div class="typing-interface" 
                 data-typing-test="true"
                 data-original-text="{{ $text->content }}"
                 data-mode="practice"
                 data-text-id="{{ $text->id }}"
                 data-show-keyboard="true"
                 data-config='{"updateInterval": 1000, "highlightDelay": 50}'>
                
                <!-- Typing area will be rendered here by typing-test.js -->
                <div class="typing-loading">
                    <div class="loading-spinner"></div>
                    <p>Loading typing interface...</p>
                </div>
            </div>

            <!-- Performance Insights -->
            <div class="performance-insights" id="performanceInsights" style="display: none;">
                <h3 class="insights-title">
                    <i class="fas fa-chart-area"></i>
                    Real-time Performance Analysis
                </h3>
                
                <div class="insights-grid">
                    <div class="insight-card speed-trend">
                        <h4>Speed Trend</h4>
                        <div class="trend-chart" id="speedTrendChart">
                            <canvas width="300" height="100"></canvas>
                        </div>
                        <div class="trend-info">
                            <span class="trend-label">Current pace:</span>
                            <span class="trend-value" id="currentPace">Calculating...</span>
                        </div>
                    </div>
                    
                    <div class="insight-card accuracy-breakdown">
                        <h4>Accuracy Breakdown</h4>
                        <div class="accuracy-stats">
                            <div class="accuracy-item">
                                <span class="label">Correct:</span>
                                <span class="value correct" id="correctChars">0</span>
                            </div>
                            <div class="accuracy-item">
                                <span class="label">Errors:</span>
                                <span class="value errors" id="errorChars">0</span>
                            </div>
                            <div class="accuracy-item">
                                <span class="label">Fixes:</span>
                                <span class="value fixes" id="fixedChars">0</span>
                            </div>
                        </div>
                        <div class="error-heatmap" id="errorHeatmap">
                            <div class="heatmap-title">Error Patterns</div>
                            <div class="heatmap-grid" id="heatmapGrid">
                                <!-- Error pattern visualization -->
                            </div>
                        </div>
                    </div>
                    
                    <div class="insight-card keystroke-rhythm">
                        <h4>Keystroke Rhythm</h4>
                        <div class="rhythm-visualization" id="rhythmVisualization">
                            <canvas width="300" height="80"></canvas>
                        </div>
                        <div class="rhythm-stats">
                            <div class="rhythm-item">
                                <span class="label">Consistency:</span>
                                <span class="value" id="rhythmConsistency">--</span>
                            </div>
                            <div class="rhythm-item">
                                <span class="label">Peak Speed:</span>
                                <span class="value" id="peakSpeed">-- WPM</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Practice Controls -->
        <div class="practice-controls">
            <div class="control-section primary-controls">
                <button class="control-btn restart" id="restartPractice">
                    <i class="fas fa-redo"></i>
                    <span>Restart</span>
                </button>
                
                <button class="control-btn settings" id="practiceSettings">
                    <i class="fas fa-cog"></i>
                    <span>Settings</span>
                </button>
                
                <button class="control-btn insights" id="toggleInsights">
                    <i class="fas fa-chart-area"></i>
                    <span>Insights</span>
                </button>
            </div>
            
            <div class="control-section secondary-controls">
                <button class="control-btn share" id="sharePractice">
                    <i class="fas fa-share-alt"></i>
                    <span>Share</span>
                </button>
                
                <button class="control-btn favorite" id="favoritePractice">
                    <i class="fas fa-heart"></i>
                    <span>Favorite</span>
                </button>
                
                @auth
                <button class="control-btn history" id="practiceHistory">
                    <i class="fas fa-history"></i>
                    <span>History</span>
                </button>
                @endauth
            </div>
        </div>

        <!-- Mobile-Specific Features -->
        <div class="mobile-features" id="mobileFeatures">
            <!-- Touch typing guide -->
            <div class="touch-typing-guide" id="touchGuide" style="display: none;">
                <h4>Touch Typing Tips</h4>
                <div class="tips-carousel">
                    <div class="tip-item active">
                        <i class="fas fa-mobile-alt"></i>
                        <p>Hold your device horizontally for the best typing experience</p>
                    </div>
                    <div class="tip-item">
                        <i class="fas fa-hand-paper"></i>
                        <p>Use both thumbs for optimal speed and accuracy</p>
                    </div>
                    <div class="tip-item">
                        <i class="fas fa-eye"></i>
                        <p>Keep your eyes on the text, not the keyboard</p>
                    </div>
                </div>
            </div>
            
            <!-- Haptic feedback controls -->
            <div class="haptic-controls" id="hapticControls">
                <label class="haptic-toggle">
                    <input type="checkbox" id="hapticFeedback" checked>
                    <span class="toggle-slider"></span>
                    <span class="toggle-label">Haptic Feedback</span>
                </label>
            </div>
        </div>
    </div>
</div>

<!-- Practice Settings Modal -->
<div class="practice-settings-modal" id="practiceSettingsModal" style="display: none;">
    <div class="modal-overlay" onclick="closePracticeSettings()"></div>
    <div class="modal-content">
        <div class="modal-header">
            <h3>Practice Settings</h3>
            <button class="modal-close" onclick="closePracticeSettings()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="modal-body">
            <div class="settings-group">
                <h4>Display Options</h4>
                <div class="setting-item">
                    <label class="setting-label">
                        <input type="checkbox" id="showKeyboard" checked>
                        <span>Show Virtual Keyboard</span>
                    </label>
                </div>
                <div class="setting-item">
                    <label class="setting-label">
                        <input type="checkbox" id="showLiveStats" checked>
                        <span>Show Live Statistics</span>
                    </label>
                </div>
                <div class="setting-item">
                    <label class="setting-label">
                        <input type="checkbox" id="highlightErrors" checked>
                        <span>Highlight Errors</span>
                    </label>
                </div>
            </div>
            
            <div class="settings-group">
                <h4>Audio & Feedback</h4>
                <div class="setting-item">
                    <label class="setting-label">
                        <input type="checkbox" id="keystrokeSounds">
                        <span>Keystroke Sounds</span>
                    </label>
                </div>
                <div class="setting-item">
                    <label class="setting-label">
                        <input type="checkbox" id="errorSounds" checked>
                        <span>Error Alert Sounds</span>
                    </label>
                </div>
                <div class="setting-item">
                    <label class="setting-label">
                        <input type="range" id="soundVolume" min="0" max="100" value="50">
                        <span>Sound Volume</span>
                    </label>
                </div>
            </div>
            
            <div class="settings-group">
                <h4>Performance</h4>
                <div class="setting-item">
                    <label class="setting-label">
                        <select id="updateInterval">
                            <option value="500">High (0.5s)</option>
                            <option value="1000" selected>Normal (1s)</option>
                            <option value="2000">Low (2s)</option>
                        </select>
                        <span>Stats Update Rate</span>
                    </label>
                </div>
                <div class="setting-item">
                    <label class="setting-label">
                        <input type="checkbox" id="smoothAnimations" checked>
                        <span>Smooth Animations</span>
                    </label>
                </div>
            </div>
        </div>
        
        <div class="modal-footer">
            <button class="modal-btn secondary" onclick="resetSettings()">
                <i class="fas fa-undo"></i>
                Reset to Default
            </button>
            <button class="modal-btn primary" onclick="savePracticeSettings()">
                <i class="fas fa-save"></i>
                Save Settings
            </button>
        </div>
    </div>
</div>

<!-- Practice Complete Modal -->
<div class="practice-complete-modal" id="practiceCompleteModal" style="display: none;">
    <div class="modal-overlay"></div>
    <div class="modal-content">
        <div class="modal-header celebration">
            <div class="celebration-icon">
                <i class="fas fa-trophy"></i>
            </div>
            <h3>Practice Complete!</h3>
            <div class="completion-badge" id="completionBadge">
                <!-- Dynamic badge based on performance -->
            </div>
        </div>
        
        <div class="modal-body">
            <div class="results-summary" id="resultsSummary">
                <!-- Results will be populated by JavaScript -->
            </div>
            
            <div class="achievement-notifications" id="achievementNotifications">
                <!-- Achievement notifications will appear here -->
            </div>
            
            <div class="social-sharing" id="socialSharing">
                <h4>Share Your Achievement</h4>
                <div class="share-buttons">
                    <button class="share-btn twitter">
                        <i class="fab fa-twitter"></i>
                        <span>Tweet</span>
                    </button>
                    <button class="share-btn facebook">
                        <i class="fab fa-facebook"></i>
                        <span>Share</span>
                    </button>
                    <button class="share-btn copy">
                        <i class="fas fa-copy"></i>
                        <span>Copy Link</span>
                    </button>
                </div>
            </div>
        </div>
        
        <div class="modal-footer">
            <button class="modal-btn secondary" onclick="closePracticeComplete()">
                <i class="fas fa-times"></i>
                Close
            </button>
            <button class="modal-btn primary" onclick="restartPractice()">
                <i class="fas fa-redo"></i>
                Practice Again
            </button>
            <button class="modal-btn success" onclick="goToResults()">
                <i class="fas fa-chart-bar"></i>
                View Detailed Results
            </button>
        </div>
    </div>
</div>

<style>
.practice-interface-container {
    padding: 2rem 0;
    background: var(--bg-primary);
    min-height: calc(100vh - 76px);
}

/* Practice Header */
.practice-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    padding: 2rem;
    background: var(--bg-card);
    border-radius: var(--border-radius-xl);
    border: 1px solid var(--border-light);
    box-shadow: var(--shadow-md);
    position: relative;
}

.practice-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: var(--champion-gradient);
}

.header-navigation {
    display: flex;
    align-items: center;
    gap: 2rem;
}

.back-btn {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    color: var(--text-secondary);
    text-decoration: none;
    padding: 0.75rem 1.25rem;
    border-radius: var(--border-radius);
    border: 1px solid var(--border-light);
    transition: all 0.3s ease;
}

.back-btn:hover {
    background: var(--accent-primary);
    border-color: var(--accent-primary);
    color: white;
    text-decoration: none;
    transform: translateX(-3px);
}

.text-info {
    flex: 1;
}

.text-title {
    font-family: var(--font-display);
    font-size: 1.75rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 0.5rem;
}

.text-meta {
    display: flex;
    gap: 1.5rem;
    align-items: center;
    flex-wrap: wrap;
}

.text-meta > span {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--text-secondary);
    font-size: 0.9rem;
    font-weight: 500;
}

.difficulty {
    padding: 0.375rem 0.75rem;
    border-radius: var(--border-radius-sm);
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.difficulty-beginner { background: rgba(16, 185, 129, 0.1); color: var(--accent-success); }
.difficulty-intermediate { background: rgba(59, 130, 246, 0.1); color: var(--accent-primary); }
.difficulty-advanced { background: rgba(245, 158, 11, 0.1); color: var(--accent-secondary); }
.difficulty-expert { background: rgba(239, 68, 68, 0.1); color: var(--accent-danger); }

.header-stats {
    display: flex;
    gap: 2rem;
}

.header-stats .stat-item {
    text-align: center;
    padding: 1rem;
    background: var(--bg-secondary);
    border-radius: var(--border-radius);
    border: 1px solid var(--border-light);
    min-width: 100px;
}

.header-stats .stat-label {
    display: block;
    color: var(--text-muted);
    font-size: 0.8rem;
    margin-bottom: 0.5rem;
    font-weight: 500;
}

.header-stats .stat-value {
    display: block;
    color: var(--text-primary);
    font-size: 1.5rem;
    font-weight: 700;
    font-family: var(--font-display);
}

/* Live Statistics Dashboard */
.live-stats-dashboard {
    margin-bottom: 2rem;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1.5rem;
}

.live-stat-card {
    background: var(--bg-card);
    border-radius: var(--border-radius-lg);
    border: 1px solid var(--border-light);
    padding: 2rem;
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
    box-shadow: var(--shadow-sm);
}

.live-stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
}

.live-stat-card.wpm::before { background: var(--champion-gradient); }
.live-stat-card.accuracy::before { background: var(--victory-gradient); }
.live-stat-card.time::before { background: var(--medal-gradient); }
.live-stat-card.progress::before { background: linear-gradient(135deg, #8b5cf6, #6366f1); }

.live-stat-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-lg);
}

.live-stat-card .stat-icon {
    position: absolute;
    top: 1.5rem;
    right: 1.5rem;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.25rem;
    opacity: 0.9;
}

.live-stat-card.wpm .stat-icon { background: var(--champion-gradient); }
.live-stat-card.accuracy .stat-icon { background: var(--victory-gradient); }
.live-stat-card.time .stat-icon { background: var(--medal-gradient); }
.live-stat-card.progress .stat-icon { background: linear-gradient(135deg, #8b5cf6, #6366f1); }

.live-stat-card .stat-content {
    margin-bottom: 1.5rem;
}

.live-stat-card .stat-value {
    font-size: 2.5rem;
    font-weight: 900;
    color: var(--text-primary);
    line-height: 1.2;
    font-family: var(--font-display);
    margin-bottom: 0.5rem;
}

.live-stat-card .stat-label {
    color: var(--text-secondary);
    font-weight: 600;
    font-size: 0.95rem;
    margin-bottom: 0.75rem;
}

.live-stat-card .stat-change {
    color: var(--text-muted);
    font-size: 0.875rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.stat-change.positive {
    color: var(--accent-success);
}

.stat-change.negative {
    color: var(--accent-danger);
}

.stat-change::before {
    content: '';
    display: inline-block;
    width: 0;
    height: 0;
    border-left: 4px solid transparent;
    border-right: 4px solid transparent;
}

.stat-change.positive::before {
    border-bottom: 6px solid var(--accent-success);
}

.stat-change.negative::before {
    border-top: 6px solid var(--accent-danger);
}

/* Stat Visualizations */
.stat-chart {
    height: 40px;
    margin-top: 1rem;
}

.accuracy-ring, .progress-ring {
    width: 60px;
    height: 60px;
    position: absolute;
    bottom: 1.5rem;
    right: 1.5rem;
}

.accuracy-ring svg, .progress-ring svg {
    width: 100%;
    height: 100%;
    transform: rotate(-90deg);
}

.accuracy-ring circle:last-child, .progress-ring circle:last-child {
    transition: stroke-dashoffset 0.5s ease;
}

.progress-visualization {
    margin-top: 1rem;
}

.progress-bar {
    width: 100%;
    height: 8px;
    background: var(--border-light);
    border-radius: 4px;
    overflow: hidden;
    margin-bottom: 0.75rem;
    position: relative;
}

.progress-fill {
    height: 100%;
    background: linear-gradient(90deg, var(--accent-primary), var(--accent-success));
    border-radius: 4px;
    transition: width 0.5s ease;
    position: relative;
}

.progress-fill::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
    animation: progressShimmer 2s ease-in-out infinite;
}

@keyframes progressShimmer {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(100%); }
}

.progress-markers {
    position: relative;
    height: 20px;
}

.progress-markers .marker {
    position: absolute;
    top: 0;
    transform: translateX(-50%);
    font-size: 0.75rem;
    color: var(--text-muted);
    font-weight: 500;
}

/* Typing Interface */
.typing-interface {
    margin-bottom: 2rem;
    background: var(--bg-card);
    border-radius: var(--border-radius-xl);
    border: 1px solid var(--border-light);
    padding: 2rem;
    box-shadow: var(--shadow-md);
    position: relative;
    min-height: 400px;
}

.typing-loading {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 300px;
    color: var(--text-muted);
}

.loading-spinner {
    width: 40px;
    height: 40px;
    border: 4px solid var(--border-light);
    border-top: 4px solid var(--accent-primary);
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin-bottom: 1rem;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Performance Insights */
.performance-insights {
    background: var(--bg-card);
    border-radius: var(--border-radius-lg);
    border: 1px solid var(--border-light);
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: var(--shadow-sm);
}

.insights-title {
    font-family: var(--font-display);
    color: var(--text-primary);
    margin-bottom: 2rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.insights-title i {
    color: var(--accent-primary);
}

.insights-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
}

.insight-card {
    background: var(--bg-secondary);
    border-radius: var(--border-radius);
    padding: 1.5rem;
    border: 1px solid var(--border-light);
}

.insight-card h4 {
    color: var(--text-primary);
    margin-bottom: 1rem;
    font-weight: 600;
}

.trend-chart, .rhythm-visualization {
    margin-bottom: 1rem;
    height: 100px;
    background: var(--bg-primary);
    border-radius: var(--border-radius-sm);
    border: 1px solid var(--border-light);
    display: flex;
    align-items: center;
    justify-content: center;
}

.trend-info, .rhythm-stats {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.accuracy-stats {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.accuracy-item {
    text-align: center;
    padding: 1rem;
    background: var(--bg-primary);
    border-radius: var(--border-radius-sm);
}

.accuracy-item .label {
    display: block;
    color: var(--text-muted);
    font-size: 0.8rem;
    margin-bottom: 0.5rem;
}

.accuracy-item .value {
    display: block;
    font-size: 1.5rem;
    font-weight: 700;
    font-family: var(--font-display);
}

.accuracy-item .value.correct { color: var(--accent-success); }
.accuracy-item .value.errors { color: var(--accent-danger); }
.accuracy-item .value.fixes { color: var(--accent-secondary); }

.error-heatmap {
    background: var(--bg-primary);
    border-radius: var(--border-radius-sm);
    padding: 1rem;
}

.heatmap-title {
    font-size: 0.875rem;
    color: var(--text-secondary);
    margin-bottom: 0.75rem;
    font-weight: 600;
}

.heatmap-grid {
    display: grid;
    grid-template-columns: repeat(10, 1fr);
    gap: 2px;
    height: 60px;
}

.heatmap-cell {
    background: var(--border-light);
    border-radius: 2px;
    transition: background 0.3s ease;
}

.heatmap-cell.error-1 { background: rgba(239, 68, 68, 0.2); }
.heatmap-cell.error-2 { background: rgba(239, 68, 68, 0.4); }
.heatmap-cell.error-3 { background: rgba(239, 68, 68, 0.6); }
.heatmap-cell.error-4 { background: rgba(239, 68, 68, 0.8); }
.heatmap-cell.error-5 { background: rgba(239, 68, 68, 1); }

/* Practice Controls */
.practice-controls {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 2rem;
    background: var(--bg-card);
    border-radius: var(--border-radius-lg);
    border: 1px solid var(--border-light);
    box-shadow: var(--shadow-sm);
    margin-bottom: 2rem;
}

.control-section {
    display: flex;
    gap: 1rem;
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

.control-btn.settings {
    background: var(--bg-secondary);
    border: 1px solid var(--border-light);
    color: var(--text-secondary);
}

.control-btn.insights {
    background: var(--medal-gradient);
    color: white;
}

.control-btn.share,
.control-btn.favorite,
.control-btn.history {
    background: transparent;
    border: 1px solid var(--border-light);
    color: var(--text-secondary);
}

.control-btn:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.control-btn.settings:hover,
.control-btn.share:hover,
.control-btn.favorite:hover,
.control-btn.history:hover {
    background: var(--accent-primary);
    border-color: var(--accent-primary);
    color: white;
}

/* Mobile Features */
.mobile-features {
    display: none;
}

@media (max-width: 768px) {
    .mobile-features {
        display: block;
        background: var(--bg-card);
        border-radius: var(--border-radius-lg);
        border: 1px solid var(--border-light);
        padding: 1.5rem;
        margin-bottom: 2rem;
    }
}

.touch-typing-guide {
    margin-bottom: 1.5rem;
}

.touch-typing-guide h4 {
    color: var(--text-primary);
    margin-bottom: 1rem;
    font-weight: 600;
}

.tips-carousel {
    position: relative;
    height: 80px;
    overflow: hidden;
}

.tip-item {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: var(--bg-secondary);
    border-radius: var(--border-radius);
    border: 1px solid var(--border-light);
    opacity: 0;
    transform: translateX(100%);
    transition: all 0.5s ease;
}

.tip-item.active {
    opacity: 1;
    transform: translateX(0);
}

.tip-item i {
    color: var(--accent-primary);
    font-size: 1.5rem;
    flex-shrink: 0;
}

.tip-item p {
    color: var(--text-secondary);
    margin: 0;
    font-size: 0.9rem;
}

.haptic-controls {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.haptic-toggle {
    display: flex;
    align-items: center;
    gap: 1rem;
    cursor: pointer;
}

.toggle-slider {
    position: relative;
    width: 50px;
    height: 24px;
    background: var(--border-light);
    border-radius: 12px;
    transition: background 0.3s ease;
}

.toggle-slider::before {
    content: '';
    position: absolute;
    top: 2px;
    left: 2px;
    width: 20px;
    height: 20px;
    background: white;
    border-radius: 50%;
    transition: transform 0.3s ease;
    box-shadow: var(--shadow-sm);
}

.haptic-toggle input:checked + .toggle-slider {
    background: var(--accent-primary);
}

.haptic-toggle input:checked + .toggle-slider::before {
    transform: translateX(26px);
}

.haptic-toggle input {
    display: none;
}

.toggle-label {
    color: var(--text-secondary);
    font-weight: 500;
}

/* Modal Styles */
.practice-settings-modal,
.practice-complete-modal {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 1000;
    display: flex;
    align-items: center;
    justify-content: center;
    animation: modalFadeIn 0.3s ease;
}

.modal-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.8);
    backdrop-filter: blur(8px);
}

.modal-content {
    background: var(--bg-card);
    border-radius: var(--border-radius-xl);
    border: 1px solid var(--border-light);
    box-shadow: var(--shadow-xl);
    max-width: 600px;
    width: 90%;
    max-height: 90vh;
    overflow: hidden;
    position: relative;
    z-index: 1001;
    animation: modalSlideIn 0.3s ease;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 2rem;
    border-bottom: 1px solid var(--border-light);
}

.modal-header.celebration {
    background: var(--champion-gradient);
    color: white;
    flex-direction: column;
    text-align: center;
    gap: 1rem;
}

.celebration-icon {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    animation: celebrationPulse 2s ease-in-out infinite;
}

@keyframes celebrationPulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.1); }
}

.completion-badge {
    background: rgba(255, 255, 255, 0.2);
    padding: 0.5rem 1rem;
    border-radius: var(--border-radius);
    font-size: 0.875rem;
    font-weight: 600;
}

.modal-header h3 {
    font-family: var(--font-display);
    margin: 0;
}

.modal-close {
    background: none;
    border: none;
    color: currentColor;
    font-size: 1.5rem;
    cursor: pointer;
    padding: 0.5rem;
    border-radius: 50%;
    transition: background 0.3s ease;
}

.modal-close:hover {
    background: rgba(255, 255, 255, 0.1);
}

.modal-body {
    padding: 2rem;
    max-height: 400px;
    overflow-y: auto;
}

.settings-group {
    margin-bottom: 2rem;
}

.settings-group h4 {
    color: var(--text-primary);
    margin-bottom: 1rem;
    font-weight: 600;
    border-bottom: 1px solid var(--border-light);
    padding-bottom: 0.5rem;
}

.setting-item {
    margin-bottom: 1rem;
}

.setting-label {
    display: flex;
    align-items: center;
    gap: 1rem;
    cursor: pointer;
    padding: 0.75rem;
    border-radius: var(--border-radius);
    transition: background 0.3s ease;
}

.setting-label:hover {
    background: var(--bg-secondary);
}

.setting-label input[type="checkbox"] {
    width: 18px;
    height: 18px;
    accent-color: var(--accent-primary);
}

.setting-label input[type="range"] {
    flex: 1;
    accent-color: var(--accent-primary);
}

.setting-label select {
    padding: 0.5rem;
    border: 1px solid var(--border-light);
    border-radius: var(--border-radius-sm);
    background: var(--bg-primary);
    color: var(--text-primary);
}

.results-summary {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 1rem;
    margin-bottom: 2rem;
}

.result-stat {
    text-align: center;
    padding: 1rem;
    background: var(--bg-secondary);
    border-radius: var(--border-radius);
    border: 1px solid var(--border-light);
}

.result-stat .label {
    display: block;
    color: var(--text-muted);
    font-size: 0.8rem;
    margin-bottom: 0.5rem;
}

.result-stat .value {
    display: block;
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text-primary);
    font-family: var(--font-display);
}

.achievement-notifications {
    margin-bottom: 2rem;
}

.achievement-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: var(--victory-gradient);
    color: white;
    border-radius: var(--border-radius);
    margin-bottom: 0.75rem;
    animation: achievementSlideIn 0.5s ease;
}

@keyframes achievementSlideIn {
    from {
        opacity: 0;
        transform: translateX(-20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.achievement-item .icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
}

.social-sharing h4 {
    color: var(--text-primary);
    margin-bottom: 1rem;
}

.share-buttons {
    display: flex;
    gap: 1rem;
}

.share-btn {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.25rem;
    border: none;
    border-radius: var(--border-radius);
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.share-btn.twitter {
    background: #1da1f2;
    color: white;
}

.share-btn.facebook {
    background: #4267b2;
    color: white;
}

.share-btn.copy {
    background: var(--bg-secondary);
    border: 1px solid var(--border-light);
    color: var(--text-secondary);
}

.share-btn:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.modal-footer {
    display: flex;
    gap: 1rem;
    padding: 2rem;
    border-top: 1px solid var(--border-light);
    background: var(--bg-secondary);
}

.modal-btn {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem 2rem;
    border: none;
    border-radius: var(--border-radius);
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    flex: 1;
    justify-content: center;
}

.modal-btn.primary {
    background: var(--champion-gradient);
    color: white;
}

.modal-btn.secondary {
    background: transparent;
    border: 2px solid var(--accent-primary);
    color: var(--accent-primary);
}

.modal-btn.success {
    background: var(--victory-gradient);
    color: white;
}

.modal-btn:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.modal-btn.secondary:hover {
    background: var(--accent-primary);
    color: white;
}

/* Animations */
@keyframes modalFadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes modalSlideIn {
    from { 
        opacity: 0; 
        transform: translateY(-30px) scale(0.95); 
    }
    to { 
        opacity: 1; 
        transform: translateY(0) scale(1); 
    }
}

/* Responsive Design */
@media (max-width: 1200px) {
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .insights-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .practice-interface-container {
        padding: 1rem 0;
    }
    
    .practice-header {
        flex-direction: column;
        gap: 1.5rem;
        text-align: center;
    }
    
    .header-navigation {
        flex-direction: column;
        gap: 1rem;
    }
    
    .header-stats {
        flex-direction: column;
        gap: 1rem;
        width: 100%;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .live-stat-card {
        padding: 1.5rem;
    }
    
    .live-stat-card .stat-value {
        font-size: 2rem;
    }
    
    .practice-controls {
        flex-direction: column;
        gap: 1.5rem;
    }
    
    .control-section {
        justify-content: center;
        flex-wrap: wrap;
    }
    
    .modal-content {
        width: 95%;
        margin: 1rem;
    }
    
    .modal-header,
    .modal-body,
    .modal-footer {
        padding: 1.5rem;
    }
    
    .modal-footer {
        flex-direction: column;
    }
    
    .share-buttons {
        flex-direction: column;
    }
}

@media (max-width: 480px) {
    .text-meta {
        flex-direction: column;
        gap: 0.75rem;
        align-items: flex-start;
    }
    
    .live-stat-card .stat-value {
        font-size: 1.75rem;
    }
    
    .accuracy-stats {
        grid-template-columns: 1fr;
    }
    
    .results-summary {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
// Global variables
let typingTestInstance = null;
let performanceData = {
    wpmHistory: [],
    accuracyHistory: [],
    keystrokeTimings: [],
    errorPositions: []
};

let liveCharts = {
    wpmChart: null,
    speedTrendChart: null,
    rhythmChart: null
};

document.addEventListener('DOMContentLoaded', function() {
    // Initialize practice interface
    initializePracticeInterface();
    
    // Setup mobile-specific features
    setupMobileFeatures();
    
    // Load user preferences
    loadPracticeSettings();
    
    // Initialize live statistics
    initializeLiveStats();
    
    // Setup control handlers
    setupControlHandlers();
    
    // Auto-save practice session for guests
    setupGuestModeFeatures();
});

function initializePracticeInterface() {
    const typingInterface = document.querySelector('[data-typing-test]');
    
    if (typingInterface && window.TypingTest) {
        const config = {
            container: typingInterface,
            originalText: typingInterface.dataset.originalText,
            mode: 'practice',
            textId: typingInterface.dataset.textId,
            showKeyboard: typingInterface.dataset.showKeyboard === 'true',
            updateInterval: 1000,
            onProgress: handleTypingProgress,
            onComplete: handleTypingComplete,
            onStart: handleTypingStart,
            onError: handleTypingError,
            ...JSON.parse(typingInterface.dataset.config || '{}')
        };
        
        typingTestInstance = new window.TypingTest(config);
        
        console.log('✅ Practice interface initialized');
    } else {
        console.error('❌ TypingTest not available or container not found');
    }
}

function handleTypingStart() {
    // Start live statistics updates
    startLiveStatsTracking();
    
    // Hide loading state
    const loadingElement = document.querySelector('.typing-loading');
    if (loadingElement) {
        loadingElement.style.display = 'none';
    }
    
    // Update UI state
    updateControlsState('active');
    
    console.log('⏱️ Practice session started');
}

function handleTypingProgress(data) {
    // Update live statistics
    updateLiveStatistics(data);
    
    // Track performance data
    trackPerformanceData(data);
    
    // Update live charts
    updateLiveCharts(data);
    
    // Save progress for guest users
    if (!window.userData) {
        saveGuestProgress(data);
    }
}

function handleTypingComplete(results) {
    // Stop live tracking
    stopLiveStatsTracking();
    
    // Update final statistics
    updateFinalStatistics(results);
    
    // Save results
    saveTypingResults(results);
    
    // Show completion modal
    showPracticeCompleteModal(results);
    
    // Update controls state
    updateControlsState('completed');
    
    console.log('🎉 Practice session completed', results);
}

function handleTypingError(errorData) {
    // Track error for analysis
    performanceData.errorPositions.push({
        position: errorData.position,
        character: errorData.character,
        timestamp: Date.now()
    });
    
    // Trigger haptic feedback on mobile
    if ('navigator' in window && 'vibrate' in navigator) {
        const hapticEnabled = document.getElementById('hapticFeedback')?.checked;
        if (hapticEnabled) {
            navigator.vibrate(50); // Short vibration for errors
        }
    }
}

function updateLiveStatistics(data) {
    // Update WPM
    const wpmElement = document.getElementById('liveWPM');
    if (wmpElement) {
        const currentWPM = Math.round(data.wmp || 0);
        wmpElement.textContent = currentWPM;
        
        // Update change indicator
        const lastWPM = performanceData.wmpHistory[performanceData.wmpHistory.length - 1] || 0;
        const wmpChange = document.getElementById('wmpChange');
        if (wmpChange && performanceData.wmpHistory.length > 0) {
            const change = currentWPM - lastWPM;
            wmpChange.textContent = `${change > 0 ? '+' : ''}${change} WPM`;
            wmpChange.className = `stat-change ${change > 0 ? 'positive' : change < 0 ? 'negative' : ''}`;
        }
    }
    
    // Update Accuracy
    const accuracyElement = document.getElementById('liveAccuracy');
    if (accuracyElement) {
        const currentAccuracy = Math.round(data.accuracy || 100);
        accuracyElement.textContent = currentAccuracy;
        
        // Update accuracy ring
        const accuracyCircle = document.getElementById('accuracyCircle');
        if (accuracyCircle) {
            const circumference = 283; // 2 * PI * 45
            const offset = circumference - (currentAccuracy / 100) * circumference;
            accuracyCircle.style.strokeDashoffset = offset;
        }
    }
    
    // Update Time
    const timeElement = document.getElementById('liveTime');
    if (timeElement) {
        const minutes = Math.floor(data.elapsedTime / 60);
        const seconds = data.elapsedTime % 60;
        timeElement.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;
        
        // Estimate finish time
        const estimateElement = document.getElementById('estimatedFinish');
        if (estimateElement && data.progress > 10) {
            const estimatedTotal = (data.elapsedTime / data.progress) * 100;
            const remaining = estimatedTotal - data.elapsedTime;
            const remMinutes = Math.floor(remaining / 60);
            const remSeconds = Math.floor(remaining % 60);
            estimateElement.textContent = `~${remMinutes}:${remSeconds.toString().padStart(2, '0')} left`;
        }
    }
    
    // Update Progress
    const progressElement = document.getElementById('liveProgress');
    const progressFill = document.getElementById('progressFill');
    const remainingWords = document.getElementById('remainingWords');
    
    if (progressElement) {
        progressElement.textContent = Math.round(data.progress || 0);
    }
    
    if (progressFill) {
        progressFill.style.width = `${data.progress || 0}%`;
    }
    
    if (remainingWords) {
        const totalWords = {{ $text->word_count }};
        const completedWords = Math.floor((data.progress / 100) * totalWords);
        const remaining = totalWords - completedWords;
        remainingWords.textContent = `${remaining} words left`;
    }
}

function trackPerformanceData(data) {
    const now = Date.now();
    
    // Track WPM history
    performanceData.wmpHistory.push({
        timestamp: now,
        wmp: data.wmp || 0
    });
    
    // Track accuracy history
    performanceData.accuracyHistory.push({
        timestamp: now,
        accuracy: data.accuracy || 100
    });
    
    // Keep only last 60 data points (1 minute of data)
    if (performanceData.wmpHistory.length > 60) {
        performanceData.wmpHistory.shift();
        performanceData.accuracyHistory.shift();
    }
    
    // Track keystroke timing if available
    if (data.keystrokeInterval) {
        performanceData.keystrokeTimings.push({
            timestamp: now,
            interval: data.keystrokeInterval
        });
        
        if (performanceData.keystrokeTimings.length > 100) {
            performanceData.keystrokeTimings.shift();
        }
    }
}

function updateLiveCharts(data) {
    // Update WPM chart (mini chart in stat card)
    updateMiniChart('wmpChart', performanceData.wmpHistory.map(d => d.wmp));
    
    // Update speed trend chart in insights
    if (document.getElementById('performanceInsights').style.display !== 'none') {
        updateSpeedTrendChart();
        updateRhythmVisualization();
        updateErrorHeatmap();
    }
}

function updateMiniChart(chartId, data) {
    const canvas = document.querySelector(`#${chartId} canvas`);
    if (!canvas || data.length < 2) return;
    
    const ctx = canvas.getContext('2d');
    const width = canvas.width;
    const height = canvas.height;
    
    // Clear canvas
    ctx.clearRect(0, 0, width, height);
    
    // Calculate min/max for scaling
    const maxValue = Math.max(...data);
    const minValue = Math.min(...data);
    const range = maxValue - minValue || 1;
    
    // Draw line
    ctx.beginPath();
    ctx.strokeStyle = 'var(--accent-primary)';
    ctx.lineWidth = 2;
    
    data.forEach((value, index) => {
        const x = (index / (data.length - 1)) * width;
        const y = height - ((value - minValue) / range) * height;
        
        if (index === 0) {
            ctx.moveTo(x, y);
        } else {
            ctx.lineTo(x, y);
        }
    });
    
    ctx.stroke();
    
    // Draw fill
    ctx.lineTo(width, height);
    ctx.lineTo(0, height);
    ctx.closePath();
    ctx.fillStyle = 'rgba(59, 130, 246, 0.1)';
    ctx.fill();
}

function startLiveStatsTracking() {
    // Initialize charts and tracking
    if (liveCharts.statsInterval) {
        clearInterval(liveCharts.statsInterval);
    }
    
    liveCharts.statsInterval = setInterval(() => {
        // Update any additional real-time features
        updateInsightsPanelData();
    }, 2000);
}

function stopLiveStatsTracking() {
    if (liveCharts.statsInterval) {
        clearInterval(liveCharts.statsInterval);
        liveCharts.statsInterval = null;
    }
}

function setupMobileFeatures() {
    if ('ontouchstart' in window) {
        // Show mobile features
        const mobileFeatures = document.getElementById('mobileFeatures');
        if (mobileFeatures) {
            mobileFeatures.style.display = 'block';
        }
        
        // Initialize touch typing guide carousel
        initializeTipsCarousel();
        
        // Setup haptic feedback
        setupHapticFeedback();
        
        // Detect device orientation
        handleOrientationChange();
        window.addEventListener('orientationchange', handleOrientationChange);
    }
}

function initializeTipsCarousel() {
    const tips = document.querySelectorAll('.tip-item');
    let currentTip = 0;
    
    if (tips.length > 1) {
        setInterval(() => {
            tips[currentTip].classList.remove('active');
            currentTip = (currentTip + 1) % tips.length;
            tips[currentTip].classList.add('active');
        }, 4000);
    }
}

function setupHapticFeedback() {
    const hapticToggle = document.getElementById('hapticFeedback');
    if (hapticToggle && 'navigator' in window && 'vibrate' in navigator) {
        hapticToggle.addEventListener('change', function() {
            if (this.checked) {
                navigator.vibrate(100); // Test vibration
            }
            localStorage.setItem('haptic_feedback', this.checked);
        });
        
        // Load saved preference
        const savedPreference = localStorage.getItem('haptic_feedback');
        if (savedPreference !== null) {
            hapticToggle.checked = savedPreference === 'true';
        }
    }
}

function handleOrientationChange() {
    setTimeout(() => {
        const isLandscape = window.innerWidth > window.innerHeight;
        const orientationWarning = document.getElementById('orientationWarning');
        
        if (!isLandscape && window.innerWidth < 768) {
            if (!orientationWarning) {
                showOrientationGuide();
            }
        } else {
            hideOrientationGuide();
        }
    }, 500);
}

function showOrientationGuide() {
    const guide = document.createElement('div');
    guide.id = 'orientationWarning';
    guide.className = 'orientation-guide';
    guide.innerHTML = `
        <div class="guide-content">
            <i class="fas fa-mobile-alt"></i>
            <h4>Better Experience</h4>
            <p>Rotate your device to landscape mode for optimal typing experience</p>
            <button onclick="hideOrientationGuide()">Got it</button>
        </div>
    `;
    
    document.body.appendChild(guide);
    
    // Auto-hide after 5 seconds
    setTimeout(hideOrientationGuide, 5000);
}

function hideOrientationGuide() {
    const guide = document.getElementById('orientationWarning');
    if (guide) {
        guide.remove();
    }
}

function setupControlHandlers() {
    // Restart button
    document.getElementById('restartPractice')?.addEventListener('click', () => {
        if (typingTestInstance) {
            typingTestInstance.restart();
            resetLiveStatistics();
        }
    });
    
    // Settings button
    document.getElementById('practiceSettings')?.addEventListener('click', showPracticeSettings);
    
    // Insights toggle
    document.getElementById('toggleInsights')?.addEventListener('click', toggleInsights);
    
    // Share button
    document.getElementById('sharePractice')?.addEventListener('click', sharePractice);
    
    // Favorite button
    document.getElementById('favoritePractice')?.addEventListener('click', () => {
        toggleFavorite({{ $text->id }});
    });
    
    // History button
    document.getElementById('practiceHistory')?.addEventListener('click', showPracticeHistory);
}

function resetLiveStatistics() {
    // Reset all displays
    document.getElementById('liveWPM').textContent = '0';
    document.getElementById('liveAccuracy').textContent = '100';
    document.getElementById('liveTime').textContent = '0:00';
    document.getElementById('liveProgress').textContent = '0';
    
    // Reset progress indicators
    document.getElementById('progressFill').style.width = '0%';
    document.getElementById('accuracyCircle').style.strokeDashoffset = '283';
    
    // Clear performance data
    performanceData = {
        wmpHistory: [],
        accuracyHistory: [],
        keystrokeTimings: [],
        errorPositions: []
    };
    
    // Clear charts
    Object.values(liveCharts).forEach(chart => {
        if (chart && chart.canvas) {
            const ctx = chart.canvas.getContext('2d');
            ctx.clearRect(0, 0, chart.canvas.width, chart.canvas.height);
        }
    });
}

function updateControlsState(state) {
    const controls = document.querySelectorAll('.control-btn');
    
    switch(state) {
        case 'active':
            controls.forEach(btn => btn.disabled = false);
            break;
        case 'completed':
            controls.forEach(btn => btn.disabled = false);
            break;
        default:
            break;
    }
}

function toggleInsights() {
    const insights = document.getElementById('performanceInsights');
    const button = document.getElementById('toggleInsights');
    
    if (insights.style.display === 'none' || !insights.style.display) {
        insights.style.display = 'block';
        button.classList.add('active');
        
        // Initialize insights charts
        initializeInsightsCharts();
    } else {
        insights.style.display = 'none';
        button.classList.remove('active');
    }
}

function initializeInsightsCharts() {
    // Initialize speed trend chart
    const speedTrendCanvas = document.querySelector('#speedTrendChart canvas');
    if (speedTrendCanvas) {
        liveCharts.speedTrendChart = speedTrendCanvas;
        updateSpeedTrendChart();
    }
    
    // Initialize rhythm visualization
    const rhythmCanvas = document.querySelector('#rhythmVisualization canvas');
    if (rhythmCanvas) {
        liveCharts.rhythmChart = rhythmCanvas;
        updateRhythmVisualization();
    }
    
    // Initialize error heatmap
    updateErrorHeatmap();
}

function updateSpeedTrendChart() {
    if (!liveCharts.speedTrendChart || performanceData.wmpHistory.length < 2) return;
    
    const canvas = liveCharts.speedTrendChart;
    const ctx = canvas.getContext('2d');
    const width = canvas.width;
    const height = canvas.height;
    
    ctx.clearRect(0, 0, width, height);
    
    const data = performanceData.wmpHistory.map(d => d.wmp);
    const maxValue = Math.max(...data);
    const minValue = Math.min(...data);
    const range = maxValue - minValue || 1;
    
    // Draw grid
    ctx.strokeStyle = 'var(--border-light)';
    ctx.lineWidth = 1;
    for (let i = 1; i < 5; i++) {
        const y = (height / 5) * i;
        ctx.beginPath();
        ctx.moveTo(0, y);
        ctx.lineTo(width, y);
        ctx.stroke();
    }
    
    // Draw speed line
    ctx.beginPath();
    ctx.strokeStyle = 'var(--accent-primary)';
    ctx.lineWidth = 3;
    
    data.forEach((value, index) => {
        const x = (index / (data.length - 1)) * width;
        const y = height - ((value - minValue) / range) * height;
        
        if (index === 0) {
            ctx.moveTo(x, y);
        } else {
            ctx.lineTo(x, y);
        }
    });
    
    ctx.stroke();
    
    // Update current pace
    const currentPaceElement = document.getElementById('currentPace');
    if (currentPaceElement && data.length > 5) {
        const recent = data.slice(-5);
        const trend = recent[recent.length - 1] - recent[0];
        const pace = trend > 2 ? 'Accelerating' : trend < -2 ? 'Slowing' : 'Steady';
        currentPaceElement.textContent = pace;
    }
}

function updateRhythmVisualization() {
    if (!liveCharts.rhythmChart || performanceData.keystrokeTimings.length < 5) return;
    
    const canvas = liveCharts.rhythmChart;
    const ctx = canvas.getContext('2d');
    const width = canvas.width;
    const height = canvas.height;
    
    ctx.clearRect(0, 0, width, height);
    
    const intervals = performanceData.keystrokeTimings.map(d => d.interval);
    const avgInterval = intervals.reduce((a, b) => a + b, 0) / intervals.length;
    const maxInterval = Math.max(...intervals);
    
    // Draw rhythm bars
    intervals.forEach((interval, index) => {
        const x = (index / intervals.length) * width;
        const barHeight = (interval / maxInterval) * height * 0.8;
        const y = height - barHeight;
        
        ctx.fillStyle = interval > avgInterval * 1.5 ? 'var(--accent-danger)' : 'var(--accent-success)';
        ctx.fillRect(x, y, Math.max(2, width / intervals.length - 1), barHeight);
    });
    
    // Update consistency score
    const consistencyElement = document.getElementById('rhythmConsistency');
    if (consistencyElement) {
        const variance = intervals.reduce((sum, interval) => sum + Math.pow(interval - avgInterval, 2), 0) / intervals.length;
        const consistency = Math.max(0, 100 - (Math.sqrt(variance) / avgInterval) * 100);
        consistencyElement.textContent = `${Math.round(consistency)}%`;
    }
    
    // Update peak speed
    const peakSpeedElement = document.getElementById('peakSpeed');
    if (peakSpeedElement && performanceData.wmpHistory.length > 0) {
        const peakWPM = Math.max(...performanceData.wmpHistory.map(d => d.wmp));
        peakSpeedElement.textContent = `${Math.round(peakWPM)} WPM`;
    }
}

function updateErrorHeatmap() {
    const heatmapGrid = document.getElementById('heatmapGrid');
    if (!heatmapGrid || performanceData.errorPositions.length === 0) return;
    
    // Create heatmap cells
    heatmapGrid.innerHTML = '';
    const cellCount = 100;
    const textLength = typingTestInstance?.config.originalText.length || 1000;
    
    for (let i = 0; i < cellCount; i++) {
        const cell = document.createElement('div');
        cell.className = 'heatmap-cell';
        
        const sectionStart = (i / cellCount) * textLength;
        const sectionEnd = ((i + 1) / cellCount) * textLength;
        
        const errorsInSection = performanceData.errorPositions.filter(
            error => error.position >= sectionStart && error.position < sectionEnd
        ).length;
        
        if (errorsInSection > 0) {
            const intensity = Math.min(5, errorsInSection);
            cell.classList.add(`error-${intensity}`);
        }
        
        heatmapGrid.appendChild(cell);
    }
    
    // Update accuracy breakdown
    document.getElementById('correctChars').textContent = performanceData.wmpHistory.length > 0 ? 
        (typingTestInstance?.state.currentPosition - performanceData.errorPositions.length) : 0;
    document.getElementById('errorChars').textContent = performanceData.errorPositions.length;
    document.getElementById('fixedChars').textContent = Math.floor(performanceData.errorPositions.length * 0.7); // Estimate
}

function updateInsightsPanelData() {
    if (document.getElementById('performanceInsights').style.display === 'none') return;
    
    updateSpeedTrendChart();
    updateRhythmVisualization();
    updateErrorHeatmap();
}

// Practice Settings Functions
function showPracticeSettings() {
    document.getElementById('practiceSettingsModal').style.display = 'flex';
}

function closePracticeSettings() {
    document.getElementById('practiceSettingsModal').style.display = 'none';
}

function savePracticeSettings() {
    const settings = {
        showKeyboard: document.getElementById('showKeyboard').checked,
        showLiveStats: document.getElementById('showLiveStats').checked,
        highlightErrors: document.getElementById('highlightErrors').checked,
        keystrokeSounds: document.getElementById('keystrokeSounds').checked,
        errorSounds: document.getElementById('errorSounds').checked,
        soundVolume: document.getElementById('soundVolume').value,
        updateInterval: document.getElementById('updateInterval').value,
        smoothAnimations: document.getElementById('smoothAnimations').checked
    };
    
    // Save to localStorage
    localStorage.setItem('practice_settings', JSON.stringify(settings));
    
    // Apply settings to current session
    applyPracticeSettings(settings);
    
    // Close modal
    closePracticeSettings();
    
    window.showNotification('Settings saved successfully!', 'success');
}

function loadPracticeSettings() {
    const savedSettings = localStorage.getItem('practice_settings');
    if (savedSettings) {
        const settings = JSON.parse(savedSettings);
        applyPracticeSettings(settings);
        updateSettingsUI(settings);
    }
}

function applyPracticeSettings(settings) {
    // Apply settings to typing test instance
    if (typingTestInstance) {
        typingTestInstance.config.showKeyboard = settings.showKeyboard;
        typingTestInstance.config.highlightErrors = settings.highlightErrors;
        typingTestInstance.config.updateInterval = parseInt(settings.updateInterval);
        
        // Re-render if necessary
        if (typingTestInstance.elements.keyboard) {
            typingTestInstance.elements.keyboard.style.display = settings.showKeyboard ? 'block' : 'none';
        }
    }
    
    // Apply to live stats
    const statsSection = document.querySelector('.live-stats-dashboard');
    if (statsSection) {
        statsSection.style.display = settings.showLiveStats ? 'block' : 'none';
    }
    
    // Apply animation settings
    if (!settings.smoothAnimations) {
        document.documentElement.style.setProperty('--speed-normal', '0s');
        document.documentElement.style.setProperty('--speed-fast', '0s');
    }
}

function updateSettingsUI(settings) {
    document.getElementById('showKeyboard').checked = settings.showKeyboard;
    document.getElementById('showLiveStats').checked = settings.showLiveStats;
    document.getElementById('highlightErrors').checked = settings.highlightErrors;
    document.getElementById('keystrokeSounds').checked = settings.keystrokeSounds;
    document.getElementById('errorSounds').checked = settings.errorSounds;
    document.getElementById('soundVolume').value = settings.soundVolume;
    document.getElementById('updateInterval').value = settings.updateInterval;
    document.getElementById('smoothAnimations').checked = settings.smoothAnimations;
}

function resetSettings() {
    const defaultSettings = {
        showKeyboard: true,
        showLiveStats: true,
        highlightErrors: true,
        keystrokeSounds: false,
        errorSounds: true,
        soundVolume: 50,
        updateInterval: 1000,
        smoothAnimations: true
    };
    
    updateSettingsUI(defaultSettings);
    applyPracticeSettings(defaultSettings);
    localStorage.removeItem('practice_settings');
    
    window.showNotification('Settings reset to default', 'info');
}

// Guest Mode Features
function setupGuestModeFeatures() {
    if (!window.userData) {
        // Load guest session data
        loadGuestSession();
        
        // Setup periodic saving
        setInterval(saveGuestSession, 30000); // Save every 30 seconds
        
        // Save on page unload
        window.addEventListener('beforeunload', saveGuestSession);
    }
}

function loadGuestSession() {
    const guestData = localStorage.getItem('guest_typing_session');
    if (guestData) {
        const data = JSON.parse(guestData);
        
        // Show guest stats if available
        if (data.textHistory && data.textHistory[{{ $text->id }}]) {
            showGuestStats(data.textHistory[{{ $text->id }}]);
        }
    }
}

function saveGuestSession() {
    if (window.userData) return; // Only for guests
    
    const guestData = JSON.parse(localStorage.getItem('guest_typing_session') || '{}');
    
    guestData.lastSession = Date.now();
    guestData.textHistory = guestData.textHistory || {};
    
    // Save current session data
    if (typingTestInstance && typingTestInstance.state.isActive) {
        guestData.currentSession = {
            textId: {{ $text->id }},
            progress: typingTestInstance.getProgressData(),
            startTime: typingTestInstance.state.startTime
        };
    }
    
    localStorage.setItem('guest_typing_session', JSON.stringify(guestData));
}

function saveGuestProgress(data) {
    if (window.userData) return; // Only for guests
    
    const guestData = JSON.parse(localStorage.getItem('guest_typing_session') || '{}');
    guestData.textHistory = guestData.textHistory || {};
    guestData.textHistory[{{ $text->id }}] = {
        lastWPM: data.wmp,
        lastAccuracy: data.accuracy,
        bestWPM: Math.max(guestData.textHistory[{{ $text->id }}]?.bestWPM || 0, data.wmp),
        attempts: (guestData.textHistory[{{ $text->id }}]?.attempts || 0) + (data.isCompleted ? 1 : 0),
        lastPlayed: Date.now()
    };
    
    localStorage.setItem('guest_typing_session', JSON.stringify(guestData));
}

function showGuestStats(stats) {
    const headerStats = document.querySelector('.header-stats');
    if (headerStats && stats.bestWPM > 0) {
        headerStats.innerHTML = `
            <div class="stat-item">
                <div class="stat-label">Your Best</div>
                <div class="stat-value">${Math.round(stats.bestWPM)} WPM</div>
            </div>
            <div class="stat-item">
                <div class="stat-label">Attempts</div>
                <div class="stat-value">${stats.attempts}</div>
            </div>
            <div class="stat-item guest-cta-small">
                <a href="{{ route('register') }}" class="stat-cta">
                    <i class="fas fa-user-plus"></i>
                    <span>Sign up to save progress</span>
                </a>
            </div>
        `;
    }
}

// Results and Completion
async function saveTypingResults(results) {
    try {
        @auth
        const response = await fetch(`{{ route('practice.submit-result', $text) }}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.csrfToken
            },
            body: JSON.stringify({
                typed_text: results.typedText,
                completion_time: results.completionTime
            })
        });
        
        if (response.ok) {
            const data = await response.json();
            console.log('✅ Results saved to server');
            
            // Store practice URL for "continue" feature
            localStorage.setItem('last_practice_url', window.location.href);
            
            return data;
        }
        @else
        // For guests, save to localStorage
        saveGuestProgress({ ...results, isCompleted: true });
        console.log('💾 Results saved locally for guest user');
        @endauth
        
    } catch (error) {
        console.error('❌ Failed to save results:', error);
        window.showNotification('Failed to save results', 'danger');
    }
}

function showPracticeCompleteModal(results) {
    const modal = document.getElementById('practiceCompleteModal');
    const resultsContainer = document.getElementById('resultsSummary');
    const achievementsContainer = document.getElementById('achievementNotifications');
    const badgeContainer = document.getElementById('completionBadge');
    
    // Create results summary
    resultsContainer.innerHTML = `
        <div class="result-stat">
            <span class="label">Speed</span>
            <span class="value">${Math.round(results.wmp)} WPM</span>
        </div>
        <div class="result-stat">
            <span class="label">Accuracy</span>
            <span class="value">${Math.round(results.accuracy)}%</span>
        </div>
        <div class="result-stat">
            <span class="label">Time</span>
            <span class="value">${Math.floor(results.completionTime / 60)}:${(results.completionTime % 60).toString().padStart(2, '0')}</span>
        </div>
        <div class="result-stat">
            <span class="label">Errors</span>
            <span class="value">${results.errorCount}</span>
        </div>
    `;
    
    // Determine performance badge
    let badgeText = 'Good Job!';
    if (results.wmp >= 100 && results.accuracy >= 95) {
        badgeText = '🏆 Expert Level!';
    } else if (results.wmp >= 70 && results.accuracy >= 90) {
        badgeText = '⭐ Great Performance!';
    } else if (results.wmp >= 40 && results.accuracy >= 85) {
        badgeText = '👍 Well Done!';
    }
    
    badgeContainer.textContent = badgeText;
    
    // Show achievements (mock for now)
    if (results.wmp >= 70) {
        achievementsContainer.innerHTML = `
            <div class="achievement-item">
                <div class="icon"><i class="fas fa-bolt"></i></div>
                <div class="content">
                    <strong>Speed Demon!</strong><br>
                    <small>Achieved 70+ WPM</small>
                </div>
            </div>
        `;
    }
    
    modal.style.display = 'flex';
}

function closePracticeComplete() {
    document.getElementById('practiceCompleteModal').style.display = 'none';
}

function goToResults() {
    @auth
    window.location.href = '{{ route('practice.index') }}?completed=1';
    @else
    window.showNotification('Sign up to view detailed practice history!', 'info');
    setTimeout(() => {
        window.location.href = '{{ route('register') }}';
    }, 2000);
    @endauth
}

// Social and Sharing
function sharePractice() {
    const shareData = {
        title: 'SportTyping Practice',
        text: `I'm practicing typing with "${document.querySelector('.text-title').textContent}" on SportTyping!`,
        url: window.location.href
    };
    
    if (navigator.share) {
        navigator.share(shareData);
    } else {
        // Fallback: copy to clipboard
        navigator.clipboard.writeText(`${shareData.text} ${shareData.url}`).then(() => {
            window.showNotification('Link copied to clipboard!', 'success');
        });
    }
}

function toggleFavorite(textId) {
    @auth
    fetch(`/api/texts/${textId}/favorite`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.csrfToken
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const btn = document.getElementById('favoritePractice');
            const icon = btn.querySelector('i');
            icon.style.color = data.favorited ? 'var(--accent-danger)' : '';
            window.showNotification(data.favorited ? 'Added to favorites!' : 'Removed from favorites!', 'success');
        }
    })
    .catch(error => {
        window.showNotification('Failed to update favorites', 'danger');
    });
    @else
    window.showNotification('Please log in to save favorites', 'info');
    @endauth
}

function showPracticeHistory() {
    @auth
    window.location.href = '{{ route('practice.index') }}?history=1';
    @else
    window.showNotification('Sign up to view your practice history!', 'info');
    @endauth
}

// Initialize everything when DOM is ready
console.log('✅ Practice interface script loaded successfully!');
</script>
@endsection
