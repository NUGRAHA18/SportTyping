/**
 * SportTyping - Real-time Competition Engine
 * Real-time race tracking, bot simulation, and live competition features
 * Integrates with typing-test.js and adopts styling from app.blade.php
 */

class CompetitionRace {
    constructor(options = {}) {
        // Configuration
        this.config = {
            container: options.container || '.competition-container',
            raceTrackContainer: options.raceTrackContainer || '.race-track',
            leaderboardContainer: options.leaderboardContainer || '.live-leaderboard',
            competitionId: options.competitionId,
            originalText: options.originalText || '',
            participants: options.participants || [],
            bots: options.bots || [],
            userId: options.userId || (window.userData ? window.userData.id : null),
            maxParticipants: options.maxParticipants || 8,
            raceDistance: options.raceDistance || 1000, // pixels
            updateInterval: options.updateInterval || 1000,
            botUpdateInterval: options.botUpdateInterval || 800,
            pusherChannel: options.pusherChannel || null,
            apiEndpoint: options.apiEndpoint || `/api/v1/competitions/${options.competitionId}`,
            ...options
        };

        // State management
        this.state = {
            isActive: false,
            isCompleted: false,
            startTime: null,
            endTime: null,
            participants: new Map(),
            racePositions: new Map(),
            leaderboard: [],
            currentUserProgress: 0,
            winner: null,
            countdownActive: false,
            countdownTime: 0
        };

        // DOM elements
        this.elements = {};
        
        // Timers and intervals
        this.updateTimer = null;
        this.botTimer = null;
        this.countdownTimer = null;
        
        // Event handlers
        this.eventHandlers = {
            onRaceStart: options.onRaceStart || null,
            onRaceComplete: options.onRaceComplete || null,
            onPositionUpdate: options.onPositionUpdate || null,
            onParticipantJoin: options.onParticipantJoin || null,
            onParticipantLeave: options.onParticipantLeave || null,
            ...options.eventHandlers
        };

        // Pusher/WebSocket connection
        this.pusher = null;
        this.channel = null;

        // Integration with typing test
        this.typingTest = null;

        // Initialize
        this.init();
    }

    /**
     * Initialize the competition race
     */
    async init() {
        try {
            await this.setupDOM();
            this.setupEventListeners();
            this.initializeParticipants();
            this.setupPusherConnection();
            this.setupTypingTestIntegration();
            this.renderRaceTrack();
            this.startRaceLoop();
            
            console.log('✅ CompetitionRace initialized successfully');
        } catch (error) {
            console.error('❌ Failed to initialize CompetitionRace:', error);
            this.showError('Failed to initialize competition race');
        }
    }

    /**
     * Setup DOM with SportTyping styling
     */
    async setupDOM() {
        const container = document.querySelector(this.config.container);
        if (!container) {
            throw new Error(`Container not found: ${this.config.container}`);
        }

        // Create race interface
        container.innerHTML = `
            <div class="competition-race-container">
                <!-- Race Header -->
                <div class="race-header">
                    <div class="race-info">
                        <h2 class="race-title">
                            <i class="fas fa-flag-checkered"></i>
                            Live Competition Race
                        </h2>
                        <div class="race-status" id="raceStatus">
                            <span class="status-indicator preparing"></span>
                            <span class="status-text">Preparing race...</span>
                        </div>
                    </div>
                    
                    <div class="race-countdown" id="raceCountdown" style="display: none;">
                        <div class="countdown-circle">
                            <span class="countdown-number" id="countdownNumber">3</span>
                        </div>
                        <div class="countdown-text">Get Ready!</div>
                    </div>
                </div>

                <!-- Race Track -->
                <div class="race-track-section">
                    <div class="race-track" id="raceTrack">
                        <div class="track-background">
                            <div class="track-lines"></div>
                            <div class="finish-line">
                                <i class="fas fa-flag-checkered"></i>
                                <span>FINISH</span>
                            </div>
                        </div>
                        
                        <div class="race-participants" id="raceParticipants">
                            <!-- Participants will be rendered here -->
                        </div>
                        
                        <div class="race-progress-indicator">
                            <div class="progress-markers">
                                <div class="marker start">START</div>
                                <div class="marker quarter">25%</div>
                                <div class="marker half">50%</div>
                                <div class="marker three-quarter">75%</div>
                                <div class="marker finish">FINISH</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Live Leaderboard -->
                <div class="live-leaderboard-section">
                    <div class="leaderboard-header">
                        <h3>
                            <i class="fas fa-trophy"></i>
                            Live Rankings
                        </h3>
                        <div class="leaderboard-refresh">
                            <i class="fas fa-sync-alt" id="refreshIcon"></i>
                        </div>
                    </div>
                    
                    <div class="live-leaderboard" id="liveLeaderboard">
                        <!-- Leaderboard will be updated here -->
                    </div>
                </div>

                <!-- Race Controls -->
                <div class="race-controls" id="raceControls">
                    <button class="race-btn start-race" id="startRaceBtn" style="display: none;">
                        <i class="fas fa-play"></i>
                        <span>Start Race</span>
                    </button>
                    
                    <button class="race-btn leave-race" id="leaveRaceBtn">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Leave Race</span>
                    </button>
                </div>

                <!-- Race Results Modal -->
                <div class="race-results-modal" id="raceResultsModal" style="display: none;">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3>Race Complete!</h3>
                            <button class="modal-close" id="modalClose">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        
                        <div class="modal-body" id="modalBody">
                            <!-- Results will be displayed here -->
                        </div>
                        
                        <div class="modal-footer">
                            <button class="race-btn secondary" id="viewFullResults">
                                View Full Results
                            </button>
                            <button class="race-btn primary" id="raceAgain">
                                Race Again
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;

        // Cache DOM elements
        this.elements = {
            container: container.querySelector('.competition-race-container'),
            raceStatus: document.getElementById('raceStatus'),
            raceCountdown: document.getElementById('raceCountdown'),
            countdownNumber: document.getElementById('countdownNumber'),
            raceTrack: document.getElementById('raceTrack'),
            raceParticipants: document.getElementById('raceParticipants'),
            liveLeaderboard: document.getElementById('liveLeaderboard'),
            refreshIcon: document.getElementById('refreshIcon'),
            startRaceBtn: document.getElementById('startRaceBtn'),
            leaveRaceBtn: document.getElementById('leaveRaceBtn'),
            raceResultsModal: document.getElementById('raceResultsModal'),
            modalClose: document.getElementById('modalClose'),
            modalBody: document.getElementById('modalBody'),
            viewFullResults: document.getElementById('viewFullResults'),
            raceAgain: document.getElementById('raceAgain')
        };

        // Apply custom styling
        this.applyCustomStyling();
    }

    /**
     * Apply SportTyping styling
     */
    applyCustomStyling() {
        const style = document.createElement('style');
        style.textContent = `
            .competition-race-container {
                background: var(--bg-card);
                border-radius: var(--border-radius-xl);
                border: 1px solid var(--border-light);
                overflow: hidden;
                box-shadow: var(--shadow-lg);
                position: relative;
            }

            .competition-race-container::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                height: 4px;
                background: var(--champion-gradient);
            }

            /* Race Header */
            .race-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 2rem;
                border-bottom: 1px solid var(--border-light);
                background: var(--bg-secondary);
            }

            .race-info h2 {
                font-family: var(--font-display);
                color: var(--text-primary);
                margin-bottom: 0.5rem;
                display: flex;
                align-items: center;
                gap: 0.75rem;
            }

            .race-info h2 i {
                color: var(--accent-primary);
            }

            .race-status {
                display: flex;
                align-items: center;
                gap: 0.75rem;
                font-weight: 500;
            }

            .status-indicator {
                width: 12px;
                height: 12px;
                border-radius: 50%;
                animation: pulse 2s infinite;
            }

            .status-indicator.preparing { background: var(--accent-secondary); }
            .status-indicator.racing { background: var(--accent-success); }
            .status-indicator.completed { background: var(--accent-primary); }

            .race-countdown {
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 1rem;
            }

            .countdown-circle {
                width: 80px;
                height: 80px;
                border-radius: 50%;
                background: var(--champion-gradient);
                display: flex;
                align-items: center;
                justify-content: center;
                animation: countdownPulse 1s infinite;
            }

            .countdown-number {
                font-size: 2rem;
                font-weight: 900;
                color: white;
                font-family: var(--font-display);
            }

            .countdown-text {
                font-weight: 600;
                color: var(--accent-primary);
                font-size: 1.1rem;
            }

            @keyframes countdownPulse {
                0%, 100% { transform: scale(1); }
                50% { transform: scale(1.1); }
            }

            /* Race Track */
            .race-track-section {
                padding: 2rem;
                background: linear-gradient(135deg, var(--bg-primary), var(--bg-secondary));
            }

            .race-track {
                position: relative;
                width: 100%;
                height: 400px;
                background: linear-gradient(90deg, #e8f4fd 0%, #c3e4fc 100%);
                border-radius: var(--border-radius-lg);
                border: 2px solid var(--border-medium);
                overflow: hidden;
            }

            .track-background {
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
            }

            .track-lines {
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-image: repeating-linear-gradient(
                    90deg,
                    transparent,
                    transparent 98px,
                    rgba(59, 130, 246, 0.1) 98px,
                    rgba(59, 130, 246, 0.1) 100px
                );
            }

            .finish-line {
                position: absolute;
                right: 0;
                top: 0;
                bottom: 0;
                width: 60px;
                background: repeating-linear-gradient(
                    45deg,
                    #000000,
                    #000000 8px,
                    #ffffff 8px,
                    #ffffff 16px
                );
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                color: var(--accent-primary);
                font-weight: 700;
                gap: 0.5rem;
                font-size: 0.875rem;
            }

            .race-participants {
                position: relative;
                height: 100%;
                padding: 1rem 0;
            }

            .participant-runner {
                position: absolute;
                left: 20px;
                width: 60px;
                height: 60px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                font-weight: 700;
                font-size: 0.9rem;
                transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
                box-shadow: var(--shadow-md);
                border: 3px solid white;
                cursor: pointer;
                z-index: 10;
            }

            .participant-runner::before {
                content: '';
                position: absolute;
                top: -5px;
                left: -5px;
                right: -5px;
                bottom: -5px;
                border-radius: 50%;
                background: var(--champion-gradient);
                z-index: -1;
                opacity: 0;
                transition: opacity 0.3s ease;
            }

            .participant-runner:hover::before {
                opacity: 1;
            }

            .participant-runner.current-user {
                border-color: var(--accent-secondary);
                box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.3), var(--shadow-lg);
                animation: currentUserGlow 2s infinite;
            }

            .participant-runner.bot {
                opacity: 0.9;
                border-color: var(--text-muted);
            }

            .participant-runner.winner {
                animation: winnerCelebration 2s infinite;
                border-color: var(--accent-secondary);
                box-shadow: 0 0 0 5px rgba(245, 158, 11, 0.5), var(--shadow-xl);
            }

            @keyframes currentUserGlow {
                0%, 100% { box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.3), var(--shadow-lg); }
                50% { box-shadow: 0 0 0 6px rgba(245, 158, 11, 0.6), var(--shadow-xl); }
            }

            @keyframes winnerCelebration {
                0%, 100% { transform: scale(1) rotate(0deg); }
                25% { transform: scale(1.1) rotate(-5deg); }
                75% { transform: scale(1.1) rotate(5deg); }
            }

            .participant-info {
                position: absolute;
                top: -30px;
                left: 50%;
                transform: translateX(-50%);
                background: rgba(0, 0, 0, 0.8);
                color: white;
                padding: 0.25rem 0.75rem;
                border-radius: var(--border-radius-sm);
                font-size: 0.75rem;
                font-weight: 600;
                white-space: nowrap;
                opacity: 0;
                transition: opacity 0.3s ease;
                z-index: 20;
            }

            .participant-runner:hover .participant-info {
                opacity: 1;
            }

            .participant-progress {
                position: absolute;
                bottom: -8px;
                left: 0;
                right: 0;
                height: 4px;
                background: rgba(255, 255, 255, 0.3);
                border-radius: 2px;
            }

            .progress-fill {
                height: 100%;
                background: var(--victory-gradient);
                border-radius: 2px;
                transition: width 0.5s ease;
            }

            .race-progress-indicator {
                position: absolute;
                bottom: 10px;
                left: 0;
                right: 0;
            }

            .progress-markers {
                display: flex;
                justify-content: space-between;
                padding: 0 20px 0 80px;
            }

            .marker {
                font-size: 0.75rem;
                font-weight: 600;
                color: var(--text-muted);
                text-align: center;
            }

            /* Live Leaderboard */
            .live-leaderboard-section {
                padding: 2rem;
                border-top: 1px solid var(--border-light);
                background: var(--bg-primary);
            }

            .leaderboard-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 1.5rem;
            }

            .leaderboard-header h3 {
                font-family: var(--font-display);
                color: var(--text-primary);
                display: flex;
                align-items: center;
                gap: 0.75rem;
            }

            .leaderboard-header h3 i {
                color: var(--accent-secondary);
            }

            .leaderboard-refresh {
                cursor: pointer;
                color: var(--accent-primary);
                padding: 0.5rem;
                border-radius: 50%;
                transition: all 0.3s ease;
            }

            .leaderboard-refresh:hover {
                background: rgba(59, 130, 246, 0.1);
                transform: rotate(180deg);
            }

            .live-leaderboard {
                display: flex;
                flex-direction: column;
                gap: 0.75rem;
            }

            .leaderboard-item {
                display: flex;
                align-items: center;
                gap: 1rem;
                padding: 1rem;
                background: var(--bg-secondary);
                border-radius: var(--border-radius);
                border: 1px solid var(--border-light);
                transition: all 0.3s ease;
            }

            .leaderboard-item:hover {
                transform: translateX(5px);
                box-shadow: var(--shadow-md);
            }

            .leaderboard-item.current-user {
                border-color: var(--accent-primary);
                background: linear-gradient(135deg, rgba(59, 130, 246, 0.05), var(--bg-secondary));
            }

            .position-badge {
                width: 40px;
                height: 40px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-weight: 700;
                color: white;
                flex-shrink: 0;
            }

            .position-badge.first { background: var(--medal-gradient); }
            .position-badge.second { background: linear-gradient(135deg, #94a3b8, #64748b); }
            .position-badge.third { background: linear-gradient(135deg, #cd7c32, #a0522d); }
            .position-badge.other { background: var(--text-muted); }

            .participant-details {
                flex: 1;
                display: flex;
                flex-direction: column;
                gap: 0.25rem;
            }

            .participant-name {
                font-weight: 600;
                color: var(--text-primary);
                display: flex;
                align-items: center;
                gap: 0.5rem;
            }

            .participant-name .bot-indicator {
                background: var(--text-muted);
                color: white;
                padding: 0.125rem 0.5rem;
                border-radius: var(--border-radius-sm);
                font-size: 0.7rem;
                font-weight: 500;
            }

            .participant-stats {
                display: flex;
                gap: 1rem;
                font-size: 0.875rem;
                color: var(--text-secondary);
            }

            .stat-item {
                display: flex;
                align-items: center;
                gap: 0.25rem;
            }

            /* Race Controls */
            .race-controls {
                display: flex;
                justify-content: center;
                gap: 1rem;
                padding: 2rem;
                border-top: 1px solid var(--border-light);
                background: var(--bg-secondary);
            }

            .race-btn {
                display: flex;
                align-items: center;
                gap: 0.75rem;
                padding: 1rem 2rem;
                border: none;
                border-radius: var(--border-radius);
                font-weight: 600;
                cursor: pointer;
                transition: all 0.3s ease;
                position: relative;
                overflow: hidden;
            }

            .race-btn::before {
                content: '';
                position: absolute;
                top: 0;
                left: -100%;
                width: 100%;
                height: 100%;
                background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
                transition: left 0.5s ease;
            }

            .race-btn:hover::before {
                left: 100%;
            }

            .race-btn.start-race {
                background: var(--victory-gradient);
                color: white;
            }

            .race-btn.leave-race {
                background: var(--accent-danger);
                color: white;
            }

            .race-btn.primary {
                background: var(--champion-gradient);
                color: white;
            }

            .race-btn.secondary {
                background: transparent;
                border: 2px solid var(--accent-primary);
                color: var(--accent-primary);
            }

            .race-btn:hover {
                transform: translateY(-2px);
                box-shadow: var(--shadow-md);
            }

            .race-btn.secondary:hover {
                background: var(--accent-primary);
                color: white;
            }

            /* Race Results Modal */
            .race-results-modal {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.8);
                backdrop-filter: blur(8px);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 1000;
                animation: modalFadeIn 0.3s ease;
            }

            @keyframes modalFadeIn {
                from { opacity: 0; }
                to { opacity: 1; }
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
                animation: modalSlideIn 0.3s ease;
            }

            @keyframes modalSlideIn {
                from { opacity: 0; transform: translateY(-30px) scale(0.95); }
                to { opacity: 1; transform: translateY(0) scale(1); }
            }

            .modal-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 2rem;
                border-bottom: 1px solid var(--border-light);
                background: var(--champion-gradient);
                color: white;
            }

            .modal-header h3 {
                font-family: var(--font-display);
                font-size: 1.5rem;
                margin: 0;
            }

            .modal-close {
                background: none;
                border: none;
                color: white;
                font-size: 1.25rem;
                cursor: pointer;
                padding: 0.5rem;
                border-radius: 50%;
                transition: background 0.3s ease;
            }

            .modal-close:hover {
                background: rgba(255, 255, 255, 0.2);
            }

            .modal-body {
                padding: 2rem;
                max-height: 400px;
                overflow-y: auto;
            }

            .modal-footer {
                display: flex;
                gap: 1rem;
                padding: 2rem;
                border-top: 1px solid var(--border-light);
                background: var(--bg-secondary);
            }

            /* Responsive Design */
            @media (max-width: 768px) {
                .race-header {
                    flex-direction: column;
                    gap: 1rem;
                    text-align: center;
                }

                .race-track {
                    height: 300px;
                }

                .participant-runner {
                    width: 45px;
                    height: 45px;
                    font-size: 0.8rem;
                }

                .progress-markers {
                    padding: 0 10px 0 55px;
                }

                .race-controls {
                    flex-direction: column;
                }

                .race-btn {
                    width: 100%;
                    justify-content: center;
                }

                .modal-content {
                    width: 95%;
                }

                .modal-footer {
                    flex-direction: column;
                }
            }

            @media (max-width: 480px) {
                .live-leaderboard-section {
                    padding: 1rem;
                }

                .leaderboard-item {
                    padding: 0.75rem;
                }

                .participant-stats {
                    flex-direction: column;
                    gap: 0.25rem;
                }

                .race-track-section {
                    padding: 1rem;
                }
            }

            /* Animation enhancements */
            .competition-race-container {
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

            /* Racing animations */
            .participant-runner.racing {
                animation: racing 0.5s ease-in-out infinite alternate;
            }

            @keyframes racing {
                0% { transform: translateY(0) rotate(-2deg); }
                100% { transform: translateY(-3px) rotate(2deg); }
            }

            /* Finish line crossing animation */
            .participant-runner.finished {
                animation: finishLineCross 1s ease;
            }

            @keyframes finishLineCross {
                0% { transform: scale(1); }
                50% { transform: scale(1.2); }
                100% { transform: scale(1); }
            }
        `;
        
        document.head.appendChild(style);
    }

    /**
     * Setup event listeners
     */
    setupEventListeners() {
        // Race controls
        this.elements.startRaceBtn.addEventListener('click', () => this.startRace());
        this.elements.leaveRaceBtn.addEventListener('click', () => this.leaveRace());
        
        // Modal controls
        this.elements.modalClose.addEventListener('click', () => this.hideResultsModal());
        this.elements.viewFullResults.addEventListener('click', () => this.viewFullResults());
        this.elements.raceAgain.addEventListener('click', () => this.raceAgain());
        
        // Leaderboard refresh
        this.elements.refreshIcon.addEventListener('click', () => this.refreshLeaderboard());
        
        // Keyboard shortcuts
        document.addEventListener('keydown', (e) => this.handleKeyboardShortcuts(e));
        
        // Window events
        window.addEventListener('beforeunload', () => this.cleanup());
        document.addEventListener('visibilitychange', () => this.handleVisibilityChange());
    }

    /**
     * Initialize participants data
     */
    initializeParticipants() {
        // Clear existing data
        this.state.participants.clear();
        this.state.racePositions.clear();
        
        // Add real participants
        this.config.participants.forEach((participant, index) => {
            this.state.participants.set(participant.id, {
                ...participant,
                progress: 0,
                position: index + 1,
                isBot: false,
                isCurrentUser: participant.id === this.config.userId,
                color: this.generateParticipantColor(participant.id),
                lane: index + 1
            });
        });
        
        // Add bots
        this.config.bots.forEach((bot, index) => {
            const botId = `bot_${index + 1}`;
            this.state.participants.set(botId, {
                id: botId,
                username: bot.name,
                avatar: bot.avatar,
                progress: 0,
                position: this.config.participants.length + index + 1,
                isBot: true,
                isCurrentUser: false,
                color: this.generateParticipantColor(botId),
                lane: this.config.participants.length + index + 1,
                botSpeed: bot.typing_speed,
                botAccuracy: bot.accuracy
            });
        });
        
        console.log(`🏃‍♂️ Initialized ${this.state.participants.size} participants`);
    }

    /**
     * Setup Pusher real-time connection
     */
    setupPusherConnection() {
        if (!window.Pusher || !this.config.pusherChannel) {
            console.warn('⚠️ Pusher not available or channel not specified');
            return;
        }

        try {
            this.pusher = new Pusher(window.pusherConfig.key, {
                cluster: window.pusherConfig.cluster,
                encrypted: true
            });

            this.channel = this.pusher.subscribe(`competition.${this.config.competitionId}`);
            
            // Listen for real-time events
            this.channel.bind('progress.updated', (data) => this.handleProgressUpdate(data));
            this.channel.bind('countdown.tick', (data) => this.handleCountdownTick(data));
            this.channel.bind('competition.started', (data) => this.handleRaceStart(data));
            this.channel.bind('competition.ended', (data) => this.handleRaceComplete(data));
            this.channel.bind('participant.joined', (data) => this.handleParticipantJoin(data));
            this.channel.bind('participant.left', (data) => this.handleParticipantLeave(data));
            
            console.log('✅ Pusher connection established');
        } catch (error) {
            console.error('❌ Failed to setup Pusher:', error);
        }
    }

    /**
     * Setup integration with typing test
     */
    setupTypingTestIntegration() {
        // Look for existing typing test instance
        const typingArea = document.querySelector('[data-typing-test]');
        if (typingArea && window.TypingTest) {
            this.typingTest = new window.TypingTest({
                container: typingArea,
                originalText: this.config.originalText,
                mode: 'competition',
                competitionId: this.config.competitionId,
                onProgress: (data) => this.handleTypingProgress(data),
                onComplete: (results) => this.handleTypingComplete(results),
                onStart: () => this.handleTypingStart(),
                showKeyboard: false
            });
            
            console.log('✅ Typing test integration setup');
        }
    }

    /**
     * Render race track with participants
     */
    renderRaceTrack() {
        let html = '';
        const totalLanes = this.state.participants.size;
        const laneHeight = 100 / totalLanes;
        
        this.state.participants.forEach((participant, id) => {
            const topPosition = (participant.lane - 1) * laneHeight + (laneHeight / 2) - 30;
            
            html += `
                <div class="participant-runner ${participant.isCurrentUser ? 'current-user' : ''} ${participant.isBot ? 'bot' : ''}"
                     data-participant-id="${id}"
                     style="top: ${topPosition}px; background: ${participant.color};">
                    
                    <div class="participant-info">
                        ${participant.username}
                        ${participant.isBot ? '<span class="bot-indicator">BOT</span>' : ''}
                        <br>
                        <small>${participant.progress}% • Pos: ${participant.position}</small>
                    </div>
                    
                    ${participant.avatar ? 
                        `<img src="${participant.avatar}" alt="${participant.username}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">` :
                        `<span>${participant.username.charAt(0).toUpperCase()}</span>`
                    }
                    
                    <div class="participant-progress">
                        <div class="progress-fill" style="width: ${participant.progress}%"></div>
                    </div>
                </div>
            `;
        });
        
        this.elements.raceParticipants.innerHTML = html;
        this.updateLeaderboard();
    }

    /**
     * Start the race countdown and race
     */
    async startRace() {
        if (this.state.isActive) return;

        try {
            // Show countdown
            await this.showCountdown();
            
            // Start race
            this.state.isActive = true;
            this.state.startTime = Date.now();
            
            // Update UI
            this.updateRaceStatus('racing', 'Race in progress!');
            
            // Start race loop
            this.startRaceLoop();
            
            // Start bot simulation
            this.startBotSimulation();
            
            // Trigger start event
            if (this.eventHandlers.onRaceStart) {
                this.eventHandlers.onRaceStart();
            }
            
            console.log('🏁 Race started!');
            
        } catch (error) {
            console.error('❌ Failed to start race:', error);
            this.showError('Failed to start race');
        }
    }

    /**
     * Show countdown before race starts
     */
    async showCountdown() {
        return new Promise((resolve) => {
            this.state.countdownActive = true;
            this.state.countdownTime = 3;
            
            this.elements.raceCountdown.style.display = 'flex';
            this.elements.countdownNumber.textContent = this.state.countdownTime;
            
            this.countdownTimer = setInterval(() => {
                this.state.countdownTime--;
                
                if (this.state.countdownTime > 0) {
                    this.elements.countdownNumber.textContent = this.state.countdownTime;
                    
                    // Play countdown sound (if available)
                    this.playSound('countdown');
                    
                } else {
                    // Show "GO!"
                    this.elements.countdownNumber.textContent = 'GO!';
                    this.elements.countdownNumber.style.color = 'var(--accent-success)';
                    
                    // Play start sound
                    this.playSound('start');
                    
                    setTimeout(() => {
                        this.elements.raceCountdown.style.display = 'none';
                        this.state.countdownActive = false;
                        clearInterval(this.countdownTimer);
                        resolve();
                    }, 1000);
                }
            }, 1000);
        });
    }

    /**
     * Start the main race loop
     */
    startRaceLoop() {
        if (this.updateTimer) {
            clearInterval(this.updateTimer);
        }
        
        this.updateTimer = setInterval(() => {
            this.updateRacePositions();
            this.updateLeaderboard();
            this.checkRaceCompletion();
            
            // Refresh leaderboard animation
            this.elements.refreshIcon.style.animation = 'none';
            setTimeout(() => {
                this.elements.refreshIcon.style.animation = 'rotation 1s linear';
            }, 10);
            
        }, this.config.updateInterval);
    }

    /**
     * Start bot simulation
     */
    startBotSimulation() {
        if (this.botTimer) {
            clearInterval(this.botTimer);
        }
        
        this.botTimer = setInterval(() => {
            this.updateBotProgress();
        }, this.config.botUpdateInterval);
    }

    /**
     * Update bot progress with realistic simulation
     */
    updateBotProgress() {
        if (!this.state.isActive || this.state.isCompleted) return;
        
        this.state.participants.forEach((participant, id) => {
            if (!participant.isBot) return;
            
            // Calculate bot progress based on their speed and some randomness
            const baseSpeed = participant.botSpeed || 50;
            const accuracy = participant.botAccuracy || 90;
            
            // Add some realistic variation
            const speedVariation = (Math.random() - 0.5) * 10; // ±5 WPM variation
            const currentSpeed = Math.max(10, baseSpeed + speedVariation);
            
            // Calculate progress increment
            const timeElapsed = (Date.now() - this.state.startTime) / 1000;
            const expectedProgress = (currentSpeed / 60) * (timeElapsed / (this.config.originalText.length / 5)) * 100;
            
            // Add accuracy impact
            const accuracyFactor = accuracy / 100;
            const adjustedProgress = expectedProgress * accuracyFactor;
            
            // Update bot progress with some smoothing
            const currentProgress = participant.progress;
            const targetProgress = Math.min(100, adjustedProgress);
            const progressDiff = targetProgress - currentProgress;
            
            if (progressDiff > 0) {
                participant.progress = Math.min(100, currentProgress + (progressDiff * 0.1));
                this.updateParticipantPosition(id, participant.progress);
            }
        });
    }

    /**
     * Handle typing progress from typing test
     */
    handleTypingProgress(data) {
        if (!this.state.isActive || !this.config.userId) return;
        
        const participant = this.state.participants.get(this.config.userId);
        if (participant) {
            participant.progress = data.progress;
            participant.wpm = data.wpm;
            participant.accuracy = data.accuracy;
            
            this.updateParticipantPosition(this.config.userId, data.progress);
            
            // Send progress to server
            this.sendProgressUpdate(data);
            
            // Trigger progress callback
            if (this.eventHandlers.onPositionUpdate) {
                this.eventHandlers.onPositionUpdate(data);
            }
        }
    }

    /**
     * Handle typing completion
     */
    handleTypingComplete(results) {
        if (!this.state.isActive) return;
        
        const participant = this.state.participants.get(this.config.userId);
        if (participant) {
            participant.progress = 100;
            participant.wmp = results.wmp;
            participant.accuracy = results.accuracy;
            participant.completionTime = results.completionTime;
            
            this.updateParticipantPosition(this.config.userId, 100);
            
            // Mark as finished
            const runnerElement = document.querySelector(`[data-participant-id="${this.config.userId}"]`);
            if (runnerElement) {
                runnerElement.classList.add('finished');
            }
            
            // Send final results
            this.sendProgressUpdate({
                progress: 100,
                wmp: results.wmp,
                accuracy: results.accuracy,
                completionTime: results.completionTime,
                completed: true
            });
            
            console.log('🏁 User completed the race!', results);
        }
    }

    /**
     * Update participant position on track
     */
    updateParticipantPosition(participantId, progress) {
        const runnerElement = document.querySelector(`[data-participant-id="${participantId}"]`);
        if (!runnerElement) return;
        
        const trackWidth = this.elements.raceTrack.offsetWidth - 140; // Account for runner width and finish line
        const newPosition = 20 + (trackWidth * (progress / 100));
        
        runnerElement.style.left = `${newPosition}px`;
        
        // Update progress bar
        const progressBar = runnerElement.querySelector('.progress-fill');
        if (progressBar) {
            progressBar.style.width = `${progress}%`;
        }
        
        // Update info tooltip
        const participantInfo = runnerElement.querySelector('.participant-info');
        const participant = this.state.participants.get(participantId);
        if (participantInfo && participant) {
            participantInfo.innerHTML = `
                ${participant.username}
                ${participant.isBot ? '<span class="bot-indicator">BOT</span>' : ''}
                <br>
                <small>${Math.round(progress)}% • ${participant.wmp || 0} WPM</small>
            `;
        }
        
        // Add racing animation
        if (progress > 0 && progress < 100) {
            runnerElement.classList.add('racing');
        } else {
            runnerElement.classList.remove('racing');
        }
        
        // Handle finish line crossing
        if (progress >= 100 && !runnerElement.classList.contains('finished')) {
            runnerElement.classList.add('finished');
            this.playSound('finish');
            
            // Check if this is the winner
            if (!this.state.winner) {
                this.state.winner = participantId;
                runnerElement.classList.add('winner');
                this.playSound('victory');
            }
        }
    }

    /**
     * Update race positions based on progress
     */
    updateRacePositions() {
        // Sort participants by progress (descending)
        const sortedParticipants = Array.from(this.state.participants.entries())
            .sort((a, b) => b[1].progress - a[1].progress);
        
        // Update positions
        sortedParticipants.forEach(([id, participant], index) => {
            participant.position = index + 1;
        });
        
        this.state.leaderboard = sortedParticipants;
    }

    /**
     * Update live leaderboard display
     */
    updateLeaderboard() {
        let html = '';
        
        this.state.leaderboard.forEach(([id, participant], index) => {
            const positionClass = index === 0 ? 'first' : index === 1 ? 'second' : index === 2 ? 'third' : 'other';
            
            html += `
                <div class="leaderboard-item ${participant.isCurrentUser ? 'current-user' : ''}">
                    <div class="position-badge ${positionClass}">
                        ${index + 1}
                    </div>
                    
                    <div class="participant-details">
                        <div class="participant-name">
                            ${participant.username}
                            ${participant.isBot ? '<span class="bot-indicator">BOT</span>' : ''}
                        </div>
                        
                        <div class="participant-stats">
                            <div class="stat-item">
                                <i class="fas fa-percentage"></i>
                                <span>${Math.round(participant.progress)}%</span>
                            </div>
                            
                            <div class="stat-item">
                                <i class="fas fa-tachometer-alt"></i>
                                <span>${participant.wmp || 0} WPM</span>
                            </div>
                            
                            <div class="stat-item">
                                <i class="fas fa-bullseye"></i>
                                <span>${participant.accuracy || 0}%</span>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });
        
        this.elements.liveLeaderboard.innerHTML = html;
    }

    /**
     * Check if race is completed
     */
    checkRaceCompletion() {
        if (this.state.isCompleted) return;
        
        // Check if all human participants have finished
        const humanParticipants = Array.from(this.state.participants.values())
            .filter(p => !p.isBot);
        
        const finishedHumans = humanParticipants.filter(p => p.progress >= 100).length;
        
        // Or check if enough time has passed or winner exists
        const timeElapsed = (Date.now() - this.state.startTime) / 1000;
        const shouldComplete = finishedHumans === humanParticipants.length || 
                              (this.state.winner && timeElapsed > 10) ||
                              timeElapsed > 300; // 5 minute max
        
        if (shouldComplete) {
            this.completeRace();
        }
    }

    /**
     * Complete the race
     */
    async completeRace() {
        if (this.state.isCompleted) return;
        
        this.state.isCompleted = true;
        this.state.endTime = Date.now();
        
        // Stop timers
        this.stopRaceLoop();
        
        // Update status
        this.updateRaceStatus('completed', 'Race completed!');
        
        // Show results
        setTimeout(() => {
            this.showResultsModal();
        }, 2000);
        
        // Trigger completion callback
        if (this.eventHandlers.onRaceComplete) {
            this.eventHandlers.onRaceComplete(this.getRaceResults());
        }
        
        console.log('🏆 Race completed!', this.getRaceResults());
    }

    /**
     * Show race results modal
     */
    showResultsModal() {
        const results = this.getRaceResults();
        const userResult = results.participants.find(p => p.isCurrentUser);
        
        let html = `
            <div class="race-summary">
                <div class="winner-announcement">
                    <h4>🏆 Winner: ${results.winner.username}</h4>
                    <p>Completed in ${this.formatTime(results.duration / 1000)} with ${results.winner.wmp} WPM</p>
                </div>
                
                ${userResult ? `
                    <div class="user-result">
                        <h5>Your Performance</h5>
                        <div class="result-stats">
                            <div class="stat">
                                <span class="label">Position:</span>
                                <span class="value">${userResult.position}/${results.participants.length}</span>
                            </div>
                            <div class="stat">
                                <span class="label">Speed:</span>
                                <span class="value">${userResult.wmp} WPM</span>
                            </div>
                            <div class="stat">
                                <span class="label">Accuracy:</span>
                                <span class="value">${userResult.accuracy}%</span>
                            </div>
                        </div>
                    </div>
                ` : ''}
                
                <div class="final-leaderboard">
                    <h5>Final Results</h5>
                    ${this.renderFinalLeaderboard(results.participants)}
                </div>
            </div>
        `;
        
        this.elements.modalBody.innerHTML = html;
        this.elements.raceResultsModal.style.display = 'flex';
    }

    /**
     * Render final leaderboard for modal
     */
    renderFinalLeaderboard(participants) {
        return participants.map((participant, index) => {
            const medal = index === 0 ? '🥇' : index === 1 ? '🥈' : index === 2 ? '🥉' : '';
            
            return `
                <div class="final-result-item ${participant.isCurrentUser ? 'current-user' : ''}">
                    <span class="position">${medal} ${index + 1}</span>
                    <span class="name">${participant.username}${participant.isBot ? ' (BOT)' : ''}</span>
                    <span class="stats">${participant.wpm} WPM • ${participant.accuracy}%</span>
                </div>
            `;
        }).join('');
    }

    /**
     * Hide results modal
     */
    hideResultsModal() {
        this.elements.raceResultsModal.style.display = 'none';
    }

    /**
     * Send progress update to server
     */
    async sendProgressUpdate(data) {
        try {
            const response = await fetch(`${this.config.apiEndpoint}/progress`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.csrfToken,
                    ...(window.userData ? { 'Authorization': `Bearer ${window.userData.token}` } : {})
                },
                body: JSON.stringify(data)
            });
            
            if (!response.ok) {
                throw new Error(`API error: ${response.status}`);
            }
            
        } catch (error) {
            console.warn('⚠️ Failed to send progress update:', error);
        }
    }

    /**
     * Handle real-time progress updates from other participants
     */
    handleProgressUpdate(data) {
        const participant = this.state.participants.get(data.user_id);
        if (participant && data.user_id !== this.config.userId) {
            participant.progress = data.progress;
            participant.wpm = data.wpm;
            participant.accuracy = data.accuracy;
            
            this.updateParticipantPosition(data.user_id, data.progress);
        }
    }

    /**
     * Handle other event handlers
     */
    handleCountdownTick(data) {
        // Handle countdown from server
        console.log('⏰ Countdown tick:', data);
    }

    handleRaceStart(data) {
        if (!this.state.isActive) {
            this.startRace();
        }
    }

    handleRaceComplete(data) {
        if (!this.state.isCompleted) {
            this.completeRace();
        }
    }

    handleParticipantJoin(data) {
        // Add new participant
        console.log('👋 Participant joined:', data);
    }

    handleParticipantLeave(data) {
        // Remove participant
        console.log('👋 Participant left:', data);
    }

    /**
     * Utility methods
     */
    generateParticipantColor(id) {
        const colors = [
            'var(--champion-gradient)',
            'var(--victory-gradient)',
            'var(--medal-gradient)',
            'linear-gradient(135deg, #8b5cf6, #6366f1)',
            'linear-gradient(135deg, #ef4444, #dc2626)',
            'linear-gradient(135deg, #06b6d4, #0891b2)',
            'linear-gradient(135deg, #84cc16, #65a30d)',
            'linear-gradient(135deg, #f97316, #ea580c)'
        ];
        
        const hash = id.split('').reduce((a, b) => {
            a = ((a << 5) - a) + b.charCodeAt(0);
            return a & a;
        }, 0);
        
        return colors[Math.abs(hash) % colors.length];
    }

    updateRaceStatus(status, text) {
        const statusIndicator = this.elements.raceStatus.querySelector('.status-indicator');
        const statusText = this.elements.raceStatus.querySelector('.status-text');
        
        statusIndicator.className = `status-indicator ${status}`;
        statusText.textContent = text;
    }

    formatTime(seconds) {
        const mins = Math.floor(seconds / 60);
        const secs = Math.floor(seconds % 60);
        return `${mins}:${secs.toString().padStart(2, '0')}`;
    }

    playSound(type) {
        // Implement sound playing if needed
        console.log(`🔊 Playing sound: ${type}`);
    }

    showError(message) {
        if (window.showNotification) {
            window.showNotification(message, 'danger');
        } else {
            console.error('❌ Competition Error:', message);
        }
    }

    /**
     * Event handlers for controls
     */
    leaveRace() {
        if (confirm('Are you sure you want to leave the race?')) {
            window.location.href = '/competitions';
        }
    }

    refreshLeaderboard() {
        this.updateLeaderboard();
        this.elements.refreshIcon.style.animation = 'rotation 1s linear';
    }

    viewFullResults() {
        this.hideResultsModal();
        window.location.href = `/competitions/${this.config.competitionId}/results`;
    }

    raceAgain() {
        this.hideResultsModal();
        window.location.reload();
    }

    handleKeyboardShortcuts(e) {
        if (e.key === 'Escape') {
            this.hideResultsModal();
        }
    }

    handleVisibilityChange() {
        if (document.hidden && this.state.isActive) {
            // Pause or handle visibility change
            console.log('👁️ Page hidden during race');
        }
    }

    /**
     * Get race results data
     */
    getRaceResults() {
        const sortedParticipants = Array.from(this.state.participants.values())
            .sort((a, b) => {
                if (b.progress === a.progress) {
                    return (a.completionTime || Infinity) - (b.completionTime || Infinity);
                }
                return b.progress - a.progress;
            });

        return {
            winner: sortedParticipants[0],
            participants: sortedParticipants,
            duration: this.state.endTime - this.state.startTime,
            totalParticipants: this.state.participants.size
        };
    }

    /**
     * Cleanup and stop race loop
     */
    stopRaceLoop() {
        if (this.updateTimer) {
            clearInterval(this.updateTimer);
            this.updateTimer = null;
        }
        
        if (this.botTimer) {
            clearInterval(this.botTimer);
            this.botTimer = null;
        }
        
        if (this.countdownTimer) {
            clearInterval(this.countdownTimer);
            this.countdownTimer = null;
        }
    }

    /**
     * Cleanup resources
     */
    cleanup() {
        this.stopRaceLoop();
        
        if (this.channel) {
            this.pusher.unsubscribe(`competition.${this.config.competitionId}`);
        }
        
        if (this.typingTest) {
            this.typingTest.destroy();
        }
    }

    /**
     * Destroy the competition race instance
     */
    destroy() {
        this.cleanup();
        
        if (this.elements.container) {
            this.elements.container.innerHTML = '';
        }
        
        console.log('🗑️ CompetitionRace instance destroyed');
    }
}

// Export for use
window.CompetitionRace = CompetitionRace;

// Usage helper
window.initCompetitionRace = function(config) {
    return new CompetitionRace(config);
};

// Auto-initialize competition races on page load
document.addEventListener('DOMContentLoaded', function() {
    const competitionAreas = document.querySelectorAll('[data-competition-race]');
    
    competitionAreas.forEach(area => {
        const config = {
            container: area,
            competitionId: area.dataset.competitionId,
            originalText: area.dataset.originalText || '',
            participants: JSON.parse(area.dataset.participants || '[]'),
            bots: JSON.parse(area.dataset.bots || '[]'),
            pusherChannel: area.dataset.pusherChannel,
            ...JSON.parse(area.dataset.config || '{}')
        };
        
        new CompetitionRace(config);
    });
});

console.log('✅ SportTyping CompetitionRace engine loaded successfully!');
