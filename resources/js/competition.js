/**
 * SportTyping Competition Real-time Controller
 * 
 * Handles real-time racing, bot simulation, WebSocket communication,
 * progress tracking, and all competition-related interactions.
 * 
 * @author Agung Nugraha (Frontend Developer)
 * @version 1.0.0
 */

class CompetitionArenaController {
    constructor(options = {}) {
        // Core competition data
        this.competitionId = options.competitionId || null;
        this.userId = options.userId || null;
        this.competitionStatus = options.status || 'upcoming';
        this.timeLimit = options.timeLimit || null;
        this.competitionStartTime = options.startTime || null;
        this.competitionEndTime = options.endTime || null;
        
        // Participants management
        this.participants = new Map();
        this.bots = options.bots || [];
        this.userParticipant = null;
        
        // Controllers and connections
        this.typingController = null;
        this.websocketConnection = null;
        this.soundController = new CompetitionSoundController();
        
        // Intervals and timers
        this.timerInterval = null;
        this.botUpdateInterval = null;
        this.leaderboardUpdateInterval = null;
        this.progressBroadcastThrottle = null;
        
        // UI state
        this.isFullscreen = false;
        this.soundEnabled = true;
        this.chatVisible = false;
        this.currentFilter = 'all';
        
        // Performance tracking
        this.lastUpdateTime = 0;
        this.frameRate = 60;
        this.updateQueue = [];
        
        // Race state
        this.raceStarted = false;
        this.raceFinished = false;
        this.winnerDeclared = false;
        
        this.init();
    }
    
    /**
     * Initialize competition arena
     */
    init() {
        try {
            console.log('🏁 Initializing Competition Arena Controller...');
            
            this.setupEventListeners();
            this.initializeParticipants();
            this.setupTypingAreaIntegration();
            this.initializeWebSocket();
            this.startCompetitionTimer();
            this.setupBotSimulation();
            this.initializeUI();
            this.setupPerformanceOptimization();
            
            console.log('✅ Competition Arena Controller initialized successfully');
        } catch (error) {
            console.error('❌ Failed to initialize Competition Arena Controller:', error);
        }
    }
    
    /**
     * Setup all event listeners
     */
    setupEventListeners() {
        // Typing area events
        document.addEventListener('typing:started', (e) => this.handleTypingStarted(e));
        document.addEventListener('typing:progress', (e) => this.handleTypingProgress(e));
        document.addEventListener('typing:completed', (e) => this.handleTypingCompleted(e));
        document.addEventListener('typing:paused', (e) => this.handleTypingPaused(e));
        document.addEventListener('typing:resumed', (e) => this.handleTypingResumed(e));
        
        // Competition control events
        this.setupControlEventListeners();
        
        // Keyboard shortcuts
        document.addEventListener('keydown', (e) => this.handleKeyboardShortcuts(e));
        
        // Window events
        window.addEventListener('resize', () => this.handleWindowResize());
        window.addEventListener('beforeunload', () => this.handleBeforeUnload());
        
        // Visibility API for tab switching
        document.addEventListener('visibilitychange', () => this.handleVisibilityChange());
    }
    
    /**
     * Setup UI control event listeners
     */
    setupControlEventListeners() {
        // Race controls
        const fullscreenBtn = document.getElementById('fullscreen-btn');
        const soundBtn = document.getElementById('sound-btn');
        const chatToggle = document.getElementById('chat-toggle');
        
        if (fullscreenBtn) {
            fullscreenBtn.addEventListener('click', () => this.toggleFullscreen());
        }
        
        if (soundBtn) {
            soundBtn.addEventListener('click', () => this.toggleSound());
        }
        
        if (chatToggle) {
            chatToggle.addEventListener('click', () => this.toggleChat());
        }
        
        // Filter buttons
        const filterBtns = document.querySelectorAll('.filter-btn');
        filterBtns.forEach(btn => {
            btn.addEventListener('click', (e) => {
                this.filterLeaderboard(e.target.dataset.filter);
            });
        });
        
        // Modal controls
        const closeResults = document.getElementById('close-results');
        const viewDetailedResult = document.getElementById('view-detailed-result');
        const joinAnother = document.getElementById('join-another');
        
        if (closeResults) {
            closeResults.addEventListener('click', () => this.closeResults());
        }
        
        if (viewDetailedResult) {
            viewDetailedResult.addEventListener('click', () => this.viewDetailedResults());
        }
        
        if (joinAnother) {
            joinAnother.addEventListener('click', () => this.joinAnotherCompetition());
        }
        
        // Chat functionality
        this.setupChatEventListeners();
    }
    
    /**
     * Setup chat system event listeners
     */
    setupChatEventListeners() {
        const chatInput = document.getElementById('chat-input');
        const sendChat = document.getElementById('send-chat');
        
        if (chatInput && sendChat) {
            chatInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    this.sendChatMessage();
                }
            });
            
            sendChat.addEventListener('click', () => this.sendChatMessage());
        }
    }
    
    /**
     * Initialize participants from DOM
     */
    initializeParticipants() {
        const participantElements = document.querySelectorAll('.participant');
        
        participantElements.forEach(element => {
            const userId = element.dataset.userId;
            const isBot = element.dataset.isBot === 'true';
            
            const participant = {
                element: element,
                userId: userId,
                isBot: isBot,
                progress: 0,
                wpm: 0,
                accuracy: 100,
                position: 1,
                finished: false,
                startTime: null,
                finishTime: null,
                keystrokes: 0,
                errors: 0,
                consistency: 100,
                lastUpdateTime: Date.now(),
                // Bot-specific properties
                botConfig: isBot ? this.getBotConfig(userId) : null,
                // Performance tracking
                wpmHistory: [],
                progressHistory: []
            };
            
            this.participants.set(userId, participant);
            
            // Set user participant reference
            if (userId === this.userId.toString()) {
                this.userParticipant = participant;
            }
        });
        
        console.log(`👥 Initialized ${this.participants.size} participants`);
    }
    
    /**
     * Get bot configuration by ID
     */
    getBotConfig(botId) {
        const bot = this.bots.find(b => b.id === botId);
        if (!bot) return null;
        
        return {
            name: bot.name,
            baseWPM: bot.typing_speed || 60,
            baseAccuracy: bot.accuracy || 95,
            skillLevel: bot.skill_level || 'intermediate',
            personality: bot.personality || 'steady',
            aggressiveness: bot.aggressiveness || 0.5,
            consistency: bot.consistency || 0.8,
            // Behavioral patterns
            speedVariation: 0.2, // ±20% WPM variation
            accuracyVariation: 0.1, // ±10% accuracy variation
            burstProbability: 0.15, // 15% chance of speed burst
            slowdownProbability: 0.1, // 10% chance of slowdown
            // Learning simulation
            adaptationRate: 0.05,
            currentForm: 1.0
        };
    }
    
    /**
     * Setup typing area integration
     */
    setupTypingAreaIntegration() {
        const typingContainer = document.querySelector('.typing-area-container');
        if (!typingContainer) return;
        
        // Wait for TypingAreaController to initialize
        const checkTypingController = () => {
            if (window.TypingAreaController) {
                this.typingController = new window.TypingAreaController(typingContainer);
                this.bindTypingAreaEvents();
                console.log('⌨️ Typing area integrated successfully');
            } else {
                setTimeout(checkTypingController, 100);
            }
        };
        
        checkTypingController();
    }
    
    /**
     * Bind typing area specific events
     */
    bindTypingAreaEvents() {
        if (!this.typingController) return;
        
        // Override typing input handler for real-time updates
        const typingInput = document.querySelector('#typing-input');
        if (typingInput) {
            let lastBroadcast = 0;
            const broadcastThreshold = 100; // Broadcast every 100ms
            
            typingInput.addEventListener('input', () => {
                const now = Date.now();
                if (now - lastBroadcast > broadcastThreshold) {
                    const stats = this.typingController.getStats();
                    this.updateUserProgress(stats);
                    this.broadcastProgress(stats);
                    lastBroadcast = now;
                }
            });
        }
    }
    
    /**
     * Initialize WebSocket connection
     */
    initializeWebSocket() {
        if (!window.Echo || !this.competitionId) {
            console.warn('⚠️ WebSocket not available or competition ID missing');
            return;
        }
        
        try {
            this.websocketConnection = window.Echo.join(`competition.${this.competitionId}`)
                .here((users) => {
                    console.log('👥 Users currently in competition:', users.length);
                    this.handleUsersPresence(users);
                })
                .joining((user) => {
                    console.log('➕ User joined competition:', user.username);
                    this.handleUserJoined(user);
                    this.soundController.playJoinSound();
                })
                .leaving((user) => {
                    console.log('➖ User left competition:', user.username);
                    this.handleUserLeft(user);
                })
                .listen('CompetitionProgress', (e) => {
                    this.handleRemoteProgress(e);
                })
                .listen('CompetitionStarted', (e) => {
                    this.handleCompetitionStarted(e);
                })
                .listen('CompetitionEnded', (e) => {
                    this.handleCompetitionEnded(e);
                })
                .listen('CompetitionCountdown', (e) => {
                    this.handleCompetitionCountdown(e);
                })
                .listenForWhisper('progress.updated', (e) => {
                    this.handleWhisperedProgress(e);
                })
                .listenForWhisper('chat.message', (e) => {
                    this.handleChatMessage(e);
                });
                
            console.log('🌐 WebSocket connection established');
        } catch (error) {
            console.error('❌ WebSocket connection failed:', error);
        }
    }
    
    /**
     * Start competition timer
     */
    startCompetitionTimer() {
        if (this.timerInterval) {
            clearInterval(this.timerInterval);
        }
        
        this.timerInterval = setInterval(() => {
            this.updateCompetitionTimer();
        }, 1000);
        
        console.log('⏰ Competition timer started');
    }
    
    /**
     * Update competition timer display
     */
    updateCompetitionTimer() {
        const timerElement = document.getElementById('competition-timer');
        const statusElement = document.getElementById('competition-status');
        
        if (!timerElement || !statusElement) return;
        
        const now = new Date();
        let timeLeft = 0;
        let timerLabel = '';
        let statusText = '';
        let statusClass = '';
        
        if (this.competitionStatus === 'upcoming') {
            const startTime = new Date(this.competitionStartTime);
            timeLeft = Math.max(0, startTime - now);
            timerLabel = 'Starting in';
            statusText = 'Waiting to Start';
            statusClass = 'waiting';
            
            if (timeLeft === 0) {
                this.startCompetition();
            }
        } else if (this.competitionStatus === 'active') {
            const endTime = new Date(this.competitionEndTime);
            timeLeft = Math.max(0, endTime - now);
            timerLabel = 'Time Left';
            statusText = 'Racing Now!';
            statusClass = 'racing';
            
            if (timeLeft === 0) {
                this.endCompetition();
            }
        } else {
            timerLabel = 'Finished';
            statusText = 'Competition Ended';
            statusClass = 'finished';
        }
        
        // Update timer display
        const minutes = Math.floor(timeLeft / 60000);
        const seconds = Math.floor((timeLeft % 60000) / 1000);
        
        const timeLabelElement = timerElement.querySelector('.timer-label');
        const timeValueElement = timerElement.querySelector('.timer-time');
        
        if (timeLabelElement) timeLabelElement.textContent = timerLabel;
        if (timeValueElement) {
            timeValueElement.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;
        }
        
        // Update status
        const statusSpan = statusElement.querySelector('span');
        if (statusSpan) {
            statusSpan.textContent = statusText;
            statusSpan.className = `status ${statusClass}`;
        }
    }
    
    /**
     * Setup bot simulation
     */
    setupBotSimulation() {
        if (this.bots.length === 0) return;
        
        // Initialize bot simulation interval
        this.botUpdateInterval = setInterval(() => {
            this.updateBots();
        }, 100); // Update every 100ms for smooth animation
        
        console.log(`🤖 Bot simulation started for ${this.bots.length} bots`);
    }
    
    /**
     * Update bot participants
     */
    updateBots() {
        if (this.competitionStatus !== 'active' || this.raceFinished) return;
        
        this.bots.forEach(bot => {
            const participant = this.participants.get(bot.id);
            if (!participant || participant.finished) return;
            
            this.simulateBotProgress(participant);
        });
        
        this.updatePositions();
        this.scheduleLeaderboardUpdate();
    }
    
    /**
     * Simulate realistic bot progress
     */
    simulateBotProgress(participant) {
        const config = participant.botConfig;
        if (!config) return;
        
        const now = Date.now();
        const deltaTime = (now - participant.lastUpdateTime) / 1000; // seconds
        
        // Simulate typing behavior based on bot personality
        const progressIncrement = this.calculateBotProgressIncrement(config, deltaTime);
        const wpmUpdate = this.calculateBotWPM(config, participant);
        const accuracyUpdate = this.calculateBotAccuracy(config, participant);
        
        // Update participant data
        participant.progress = Math.min(100, participant.progress + progressIncrement);
        participant.wpm = Math.max(0, wmpUpdate);
        participant.accuracy = Math.max(0, Math.min(100, accuracyUpdate));
        participant.lastUpdateTime = now;
        
        // Track history for consistency calculation
        participant.wmpHistory.push(participant.wmp);
        participant.progressHistory.push(participant.progress);
        
        // Limit history size
        if (participant.wmpHistory.length > 30) {
            participant.wmpHistory.shift();
            participant.progressHistory.shift();
        }
        
        // Check if finished
        if (participant.progress >= 100 && !participant.finished) {
            participant.finished = true;
            participant.finishTime = now;
            this.handleBotFinished(participant);
        }
        
        // Update display
        this.updateParticipantDisplay(participant);
    }
    
    /**
     * Calculate bot progress increment
     */
    calculateBotProgressIncrement(config, deltaTime) {
        const baseSpeed = config.baseWPM / 60; // chars per second (assuming 5 chars per word)
        const speedVariation = 1 + (Math.random() - 0.5) * config.speedVariation;
        const burstMultiplier = Math.random() < config.burstProbability ? 1.5 : 1.0;
        const slowdownMultiplier = Math.random() < config.slowdownProbability ? 0.7 : 1.0;
        
        // Personality-based adjustments
        let personalityMultiplier = 1.0;
        switch (config.personality) {
            case 'aggressive':
                personalityMultiplier = 1.2;
                break;
            case 'steady':
                personalityMultiplier = 1.0;
                break;
            case 'cautious':
                personalityMultiplier = 0.9;
                break;
        }
        
        const effectiveSpeed = baseSpeed * speedVariation * burstMultiplier * 
                             slowdownMultiplier * personalityMultiplier * config.currentForm;
        
        // Convert to progress percentage (assuming text length)
        const estimatedTextLength = 500; // characters
        const progressPerSecond = (effectiveSpeed * 5) / estimatedTextLength * 100; // 5 chars per word
        
        return progressPerSecond * deltaTime;
    }
    
    /**
     * Calculate bot WPM
     */
    calculateBotWPM(config, participant) {
        const currentProgress = participant.progress;
        const timeElapsed = (Date.now() - (participant.startTime || Date.now())) / 1000 / 60; // minutes
        
        if (timeElapsed <= 0) return 0;
        
        // Base WPM calculation
        const baseWPM = config.baseWPM;
        const variation = (Math.random() - 0.5) * config.speedVariation * baseWPM;
        const progressMultiplier = Math.min(1.2, currentProgress / 100 + 0.2); // Slight speedup as progress increases
        
        return Math.round((baseWPM + variation) * progressMultiplier * config.currentForm);
    }
    
    /**
     * Calculate bot accuracy
     */
    calculateBotAccuracy(config, participant) {
        const baseAccuracy = config.baseAccuracy;
        const variation = (Math.random() - 0.5) * config.accuracyVariation * baseAccuracy;
        const consistencyFactor = config.consistency;
        
        // Accuracy tends to decrease slightly with speed increases
        const speedPenalty = Math.max(0, (participant.wmp - config.baseWPM) * 0.1);
        
        return Math.round((baseAccuracy + variation - speedPenalty) * consistencyFactor);
    }
    
    /**
     * Handle bot finishing
     */
    handleBotFinished(participant) {
        console.log(`🤖 Bot ${participant.botConfig.name} finished!`);
        
        // Play finish sound if enabled
        this.soundController.playFinishSound();
        
        // Broadcast bot finish event
        this.broadcastBotFinish(participant);
    }
    
    /**
     * Update user progress
     */
    updateUserProgress(stats) {
        if (!this.userParticipant) return;
        
        const participant = this.userParticipant;
        const now = Date.now();
        
        // Update participant data
        participant.progress = stats.progress || 0;
        participant.wmp = stats.wmp || 0;
        participant.accuracy = stats.accuracy || 100;
        participant.errors = stats.errors || 0;
        participant.keystrokes = stats.totalChars || 0;
        participant.lastUpdateTime = now;
        
        // Set start time if first progress
        if (!participant.startTime && participant.progress > 0) {
            participant.startTime = now;
            this.raceStarted = true;
        }
        
        // Check if finished
        if (participant.progress >= 100 && !participant.finished) {
            participant.finished = true;
            participant.finishTime = now;
            this.handleUserFinished();
        }
        
        // Update display
        this.updateParticipantDisplay(participant);
        this.updatePositions();
        this.scheduleLeaderboardUpdate();
    }
    
    /**
     * Handle user finishing
     */
    handleUserFinished() {
        console.log('🏁 User finished typing!');
        
        // Play appropriate finish sound
        if (this.userParticipant.position === 1) {
            this.soundController.playVictorySound();
            this.showVictoryEffects();
        } else {
            this.soundController.playFinishSound();
        }
        
        // Show completion effects
        this.showCompletionEffects();
        
        // Schedule results display
        setTimeout(() => {
            if (!this.raceFinished) {
                this.showIntermediateResults();
            }
        }, 3000);
    }
    
    /**
     * Update participant display
     */
    updateParticipantDisplay(participant) {
        const element = participant.element;
        if (!element) return;
        
        // Update progress bar
        const progressFill = element.querySelector('.progress-fill');
        if (progressFill) {
            progressFill.style.width = `${Math.min(100, participant.progress)}%`;
        }
        
        // Update progress car position
        const progressCar = element.querySelector('.progress-car');
        if (progressCar && progressFill) {
            const progressPercent = Math.min(100, participant.progress);
            progressCar.style.transform = `translateX(${progressPercent * 0.9}%) translateY(-50%)`;
        }
        
        // Update stats
        const wmpElement = element.querySelector('.stat-value.wmp');
        const accuracyElement = element.querySelector('.stat-value.accuracy');
        const positionElement = element.querySelector('.position-value');
        
        if (wmpElement) {
            wmpElement.textContent = Math.round(participant.wmp);
            wmpElement.dataset.wmp = participant.wmp;
        }
        
        if (accuracyElement) {
            accuracyElement.textContent = Math.round(participant.accuracy) + '%';
            accuracyElement.dataset.accuracy = participant.accuracy;
        }
        
        if (positionElement) {
            positionElement.textContent = `#${participant.position}`;
            positionElement.dataset.position = participant.position;
        }
        
        // Update crown for leader
        const crown = element.querySelector('.participant-crown');
        if (crown) {
            crown.style.display = participant.position === 1 ? 'block' : 'none';
        }
        
        // Add/remove finished state
        if (participant.finished) {
            element.classList.add('finished');
        }
        
        // Add position-based styling
        element.classList.remove('position-1', 'position-2', 'position-3');
        if (participant.position <= 3) {
            element.classList.add(`position-${participant.position}`);
        }
    }
    
    /**
     * Update all participant positions
     */
    updatePositions() {
        // Sort participants by progress (finished first, then by progress)
        const sortedParticipants = Array.from(this.participants.values())
            .sort((a, b) => {
                if (a.finished !== b.finished) {
                    return b.finished - a.finished; // Finished participants first
                }
                return b.progress - a.progress; // Then by progress
            });
        
        // Update positions
        sortedParticipants.forEach((participant, index) => {
            const oldPosition = participant.position;
            participant.position = index + 1;
            
            // Trigger position change animation if position improved
            if (oldPosition > participant.position) {
                this.animatePositionImprovement(participant);
            }
        });
        
        // Update leading stats overlay
        this.updateLeadingStatsOverlay(sortedParticipants[0]);
    }
    
    /**
     * Animate position improvement
     */
    animatePositionImprovement(participant) {
        const element = participant.element;
        if (!element) return;
        
        // Add improvement animation class
        element.classList.add('position-improved');
        
        // Remove class after animation
        setTimeout(() => {
            element.classList.remove('position-improved');
        }, 1000);
        
        // Play position improvement sound
        this.soundController.playPositionUpSound();
    }
    
    /**
     * Update leading stats overlay
     */
    updateLeadingStatsOverlay(leader) {
        if (!leader) return;
        
        const leadingName = document.querySelector('.leading-name');
        const leadingWMP = document.querySelector('.leading-wmp');
        const leadingAvatar = document.querySelector('.leading-avatar');
        
        if (!leadingName || !leadingWMP || !leadingAvatar) return;
        
        // Get participant info
        const participantInfo = this.getParticipantDisplayInfo(leader);
        
        // Update overlay
        leadingName.textContent = participantInfo.name;
        leadingWMP.textContent = `${Math.round(leader.wmp)} WPM`;
        
        // Update avatar
        if (participantInfo.avatarUrl) {
            leadingAvatar.style.backgroundImage = `url(${participantInfo.avatarUrl})`;
            leadingAvatar.style.backgroundSize = 'cover';
            leadingAvatar.textContent = '';
        } else {
            leadingAvatar.style.backgroundImage = '';
            leadingAvatar.style.background = participantInfo.avatarBg;
            leadingAvatar.textContent = participantInfo.avatarText;
        }
    }
    
    /**
     * Get participant display information
     */
    getParticipantDisplayInfo(participant) {
        const element = participant.element;
        const nameElement = element.querySelector('.participant-name');
        const avatarElement = element.querySelector('.participant-avatar img, .avatar-placeholder');
        
        let name = 'Unknown';
        let avatarUrl = null;
        let avatarBg = '';
        let avatarText = '';
        let badges = '';
        
        if (nameElement) {
            name = nameElement.textContent.trim();
            name = name.replace(' YOU', '').replace(' BOT', '');
        }
        
        if (avatarElement) {
            if (avatarElement.tagName === 'IMG') {
                avatarUrl = avatarElement.src;
            } else {
                avatarBg = window.getComputedStyle(avatarElement).background;
                avatarText = avatarElement.textContent || avatarElement.innerHTML;
            }
        }
        
        return { name, avatarUrl, avatarBg, avatarText, badges };
    }
    
    /**
     * Schedule leaderboard update (throttled)
     */
    scheduleLeaderboardUpdate() {
        if (this.leaderboardUpdateInterval) return;
        
        this.leaderboardUpdateInterval = setTimeout(() => {
            this.updateLeaderboard();
            this.leaderboardUpdateInterval = null;
        }, 200); // Update every 200ms
    }
    
    /**
     * Update live leaderboard
     */
    updateLeaderboard() {
        const leaderboardList = document.querySelector('.leaderboard-list');
        if (!leaderboardList) return;
        
        // Clear loading state
        const loading = leaderboardList.querySelector('.leaderboard-loading');
        if (loading) {
            loading.remove();
        }
        
        // Get sorted participants
        const sortedParticipants = Array.from(this.participants.values())
            .sort((a, b) => {
                if (a.finished !== b.finished) {
                    return b.finished - a.finished;
                }
                return b.progress - a.progress;
            });
        
        // Generate leaderboard HTML
        const leaderboardHTML = sortedParticipants.map((participant, index) => {
            const participantInfo = this.getParticipantDisplayInfo(participant);
            const medal = index < 3 ? ['🥇', '🥈', '🥉'][index] : '';
            const isCurrentUser = participant.userId === this.userId.toString();
            const isBot = participant.isBot;
            
            return `
                <div class="leaderboard-item ${isCurrentUser ? 'current-user' : ''} ${isBot ? 'bot' : 'human'}" 
                     data-user-id="${participant.userId}">
                    <div class="rank">${medal} #${index + 1}</div>
                    <div class="participant-info">
                        <div class="avatar">
                            ${participantInfo.avatarUrl ? 
                                `<img src="${participantInfo.avatarUrl}" alt="${participantInfo.name}">` :
                                `<div class="avatar-placeholder" style="background: ${participantInfo.avatarBg}">${participantInfo.avatarText}</div>`
                            }
                        </div>
                        <div class="info">
                            <div class="name">
                                ${participantInfo.name}
                                ${isCurrentUser ? '<span class="you-badge">YOU</span>' : ''}
                                ${isBot ? '<span class="bot-badge">BOT</span>' : ''}
                            </div>
                            <div class="status">
                                ${participant.finished ? '✅ Finished' : '⌨️ Typing...'}
                            </div>
                        </div>
                    </div>
                    <div class="stats">
                        <div class="wmp">${Math.round(participant.wmp)} WPM</div>
                        <div class="accuracy">${Math.round(participant.accuracy)}%</div>
                        <div class="progress">${Math.round(participant.progress)}%</div>
                    </div>
                </div>
            `;
        }).join('');
        
        leaderboardList.innerHTML = leaderboardHTML;
        
        // Apply current filter
        this.applyLeaderboardFilter();
    }
    
    /**
     * Filter leaderboard by type
     */
    filterLeaderboard(filter) {
        this.currentFilter = filter;
        
        // Update filter button states
        const filterBtns = document.querySelectorAll('.filter-btn');
        filterBtns.forEach(btn => {
            btn.classList.toggle('active', btn.dataset.filter === filter);
        });
        
        this.applyLeaderboardFilter();
    }
    
    /**
     * Apply current leaderboard filter
     */
    applyLeaderboardFilter() {
        const leaderboardItems = document.querySelectorAll('.leaderboard-item');
        
        leaderboardItems.forEach(item => {
            let show = true;
            
            if (this.currentFilter === 'human' && item.classList.contains('bot')) {
                show = false;
            } else if (this.currentFilter === 'bot' && !item.classList.contains('bot')) {
                show = false;
            }
            
            item.style.display = show ? 'flex' : 'none';
        });
    }
    
    /**
     * Broadcast progress to other participants
     */
    broadcastProgress(stats) {
        if (!this.websocketConnection) return;
        
        // Throttle broadcasts to prevent spam
        const now = Date.now();
        if (this.progressBroadcastThrottle && now - this.progressBroadcastThrottle < 250) {
            return;
        }
        this.progressBroadcastThrottle = now;
        
        const progressData = {
            user_id: this.userId,
            progress: stats.progress,
            wmp: stats.wmp,
            accuracy: stats.accuracy,
            timestamp: now
        };
        
        this.websocketConnection.whisper('progress.updated', progressData);
    }
    
    /**
     * Broadcast bot finish event
     */
    broadcastBotFinish(participant) {
        if (!this.websocketConnection) return;
        
        this.websocketConnection.whisper('bot.finished', {
            bot_id: participant.userId,
            bot_name: participant.botConfig.name,
            final_wpm: participant.wmp,
            final_accuracy: participant.accuracy,
            finish_time: participant.finishTime
        });
    }
    
    /**
     * Handle remote progress updates
     */
    handleRemoteProgress(event) {
        this.handleWhisperedProgress(event);
    }
    
    /**
     * Handle whispered progress updates
     */
    handleWhisperedProgress(event) {
        const { user_id, progress, wmp, accuracy, timestamp } = event;
        const participant = this.participants.get(user_id.toString());
        
        if (!participant || user_id === this.userId) return;
        
        // Update participant data
        participant.progress = progress;
        participant.wmp = wmp;
        participant.accuracy = accuracy;
        participant.lastUpdateTime = timestamp;
        
        // Check if finished
        if (progress >= 100 && !participant.finished) {
            participant.finished = true;
            participant.finishTime = timestamp;
            this.soundController.playRemoteFinishSound();
        }
        
        // Update display
        this.updateParticipantDisplay(participant);
        this.updatePositions();
        this.scheduleLeaderboardUpdate();
    }
    
    /**
     * Handle typing events
     */
    handleTypingStarted(e) {
        console.log('⌨️ User started typing');
        if (this.userParticipant && !this.userParticipant.startTime) {
            this.userParticipant.startTime = Date.now();
            this.raceStarted = true;
        }
    }
    
    handleTypingProgress(e) {
        const stats = e.detail.stats;
        this.updateUserProgress(stats);
    }
    
    handleTypingCompleted(e) {
        const stats = e.detail.stats;
        console.log('🏁 User completed typing:', stats);
        this.updateUserProgress(stats);
    }
    
    handleTypingPaused(e) {
        console.log('⏸️ User paused typing');
        this.broadcastEvent('typing_paused');
    }
    
    handleTypingResumed(e) {
        console.log('▶️ User resumed typing');
        this.broadcastEvent('typing_resumed');
    }
    
    /**
     * Show victory effects for winner
     */
    showVictoryEffects() {
        // Add victory class to user participant
        if (this.userParticipant && this.userParticipant.element) {
            this.userParticipant.element.classList.add('victory');
        }
        
        // Create confetti effect
        this.createConfettiEffect();
        
        // Show victory message
        this.showVictoryMessage();
    }
    
    /**
     * Create confetti animation
     */
    createConfettiEffect() {
        const colors = ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6'];
        const confettiCount = window.innerWidth < 768 ? 30 : 50;
        
        for (let i = 0; i < confettiCount; i++) {
            const confetti = document.createElement('div');
            confetti.className = 'confetti-piece';
            confetti.style.cssText = `
                position: fixed;
                top: -10px;
                left: ${Math.random() * 100}%;
                width: ${Math.random() * 10 + 5}px;
                height: ${Math.random() * 10 + 5}px;
                background: ${colors[Math.floor(Math.random() * colors.length)]};
                z-index: 9999;
                pointer-events: none;
                border-radius: ${Math.random() > 0.5 ? '50%' : '0'};
                animation: confettiFall ${Math.random() * 2 + 2}s linear forwards;
            `;
            
            document.body.appendChild(confetti);
            
            setTimeout(() => confetti.remove(), 4000);
        }
    }
    
    /**
     * Show victory message
     */
    showVictoryMessage() {
        const message = document.createElement('div');
        message.className = 'victory-message';
        message.innerHTML = `
            <div class="victory-content">
                <div class="victory-icon">🏆</div>
                <h2>VICTORY!</h2>
                <p>You won the race!</p>
            </div>
        `;
        message.style.cssText = `
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
            padding: 2rem;
            border-radius: 1rem;
            text-align: center;
            z-index: 10000;
            animation: victoryPulse 2s ease-in-out;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        `;
        
        document.body.appendChild(message);
        
        setTimeout(() => message.remove(), 3000);
    }
    
    /**
     * Show completion effects for non-winners
     */
    showCompletionEffects() {
        // Add completed class to user participant
        if (this.userParticipant && this.userParticipant.element) {
            this.userParticipant.element.classList.add('completed');
        }
    }
    
    /**
     * Start competition
     */
    startCompetition() {
        this.competitionStatus = 'active';
        this.raceStarted = false;
        this.raceFinished = false;
        
        console.log('🏁 Competition started!');
        
        // Start bot timers if needed
        this.participants.forEach(participant => {
            if (participant.isBot) {
                participant.startTime = Date.now();
            }
        });
        
        // Play start sound
        this.soundController.playStartSound();
        
        // Show start notification
        this.showStartNotification();
    }
    
    /**
     * End competition
     */
    endCompetition() {
        this.competitionStatus = 'completed';
        this.raceFinished = true;
        
        console.log('🏁 Competition ended!');
        
        // Clear intervals
        if (this.timerInterval) {
            clearInterval(this.timerInterval);
            this.timerInterval = null;
        }
        
        if (this.botUpdateInterval) {
            clearInterval(this.botUpdateInterval);
            this.botUpdateInterval = null;
        }
        
        // Play end sound
        this.soundController.playEndSound();
        
        // Show final results
        setTimeout(() => {
            this.showFinalResults();
        }, 2000);
    }
    
    /**
     * Show start notification
     */
    showStartNotification() {
        const notification = document.createElement('div');
        notification.className = 'start-notification';
        notification.innerHTML = `
            <div class="start-content">
                <div class="start-icon">🚀</div>
                <h2>Race Started!</h2>
                <p>Start typing now!</p>
            </div>
        `;
        notification.style.cssText = `
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            padding: 2rem;
            border-radius: 1rem;
            text-align: center;
            z-index: 10000;
            animation: startPulse 1.5s ease-in-out;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => notification.remove(), 2000);
    }
    
    /**
     * Show intermediate results (for early finishers)
     */
    showIntermediateResults() {
        console.log('📊 Showing intermediate results');
        // Implementation for intermediate results display
    }
    
    /**
     * Show final results
     */
    showFinalResults() {
        const resultsModal = document.getElementById('results-modal');
        if (!resultsModal) return;
        
        console.log('🏆 Showing final results');
        
        this.populateFinalResults();
        resultsModal.style.display = 'flex';
    }
    
    /**
     * Populate final results modal
     */
    populateFinalResults() {
        // Get final sorted results
        const sortedParticipants = Array.from(this.participants.values())
            .filter(p => p.finished)
            .sort((a, b) => {
                // Sort by finish time for finished participants
                return (a.finishTime || 0) - (b.finishTime || 0);
            });
        
        // Add unfinished participants at the end
        const unfinishedParticipants = Array.from(this.participants.values())
            .filter(p => !p.finished)
            .sort((a, b) => b.progress - a.progress);
            
        const allParticipants = [...sortedParticipants, ...unfinishedParticipants];
        
        // Populate podium
        this.populatePodium(allParticipants.slice(0, 3));
        
        // Populate full results table
        this.populateResultsTable(allParticipants);
    }
    
    /**
     * Populate results podium
     */
    populatePodium(topThree) {
        const podiumPlaces = ['first', 'second', 'third'];
        
        podiumPlaces.forEach((place, index) => {
            const placeElement = document.getElementById(`${place}-place`);
            const participant = topThree[index];
            
            if (placeElement) {
                if (participant) {
                    const participantInfo = this.getParticipantDisplayInfo(participant);
                    
                    const avatarElement = placeElement.querySelector('.podium-avatar');
                    const nameElement = placeElement.querySelector('.podium-name');
                    const statsElement = placeElement.querySelector('.podium-stats');
                    
                    if (avatarElement) {
                        if (participantInfo.avatarUrl) {
                            avatarElement.innerHTML = `<img src="${participantInfo.avatarUrl}" alt="${participantInfo.name}">`;
                        } else {
                            avatarElement.innerHTML = `<div class="avatar-placeholder" style="background: ${participantInfo.avatarBg}">${participantInfo.avatarText}</div>`;
                        }
                    }
                    
                    if (nameElement) {
                        nameElement.textContent = participantInfo.name;
                    }
                    
                    if (statsElement) {
                        const finishTime = participant.finishTime ? new Date(participant.finishTime) : null;
                        const raceTime = finishTime && participant.startTime ? 
                                       ((finishTime - participant.startTime) / 1000).toFixed(1) + 's' : 
                                       'DNF';
                                       
                        statsElement.innerHTML = `
                            ${Math.round(participant.wmp)} WPM • ${Math.round(participant.accuracy)}%<br>
                            <small>${raceTime}</small>
                        `;
                    }
                    
                    placeElement.style.display = 'block';
                } else {
                    placeElement.style.display = 'none';
                }
            }
        });
    }
    
    /**
     * Populate results table
     */
    populateResultsTable(allParticipants) {
        const resultsTable = document.getElementById('results-table');
        if (!resultsTable) return;
        
        const tableHTML = allParticipants.map((participant, index) => {
            const participantInfo = this.getParticipantDisplayInfo(participant);
            const isCurrentUser = participant.userId === this.userId.toString();
            const finishTime = participant.finishTime ? new Date(participant.finishTime) : null;
            const raceTime = finishTime && participant.startTime ? 
                           ((finishTime - participant.startTime) / 1000).toFixed(1) + 's' : 
                           'DNF';
            
            return `
                <div class="result-row ${isCurrentUser ? 'current-user' : ''}">
                    <div class="position">#${index + 1}</div>
                    <div class="participant">
                        ${participantInfo.name}
                        ${isCurrentUser ? '<span class="you-badge">YOU</span>' : ''}
                        ${participant.isBot ? '<span class="bot-badge">BOT</span>' : ''}
                    </div>
                    <div class="wmp">${Math.round(participant.wmp)} WPM</div>
                    <div class="accuracy">${Math.round(participant.accuracy)}%</div>
                    <div class="time">${raceTime}</div>
                </div>
            `;
        }).join('');
        
        resultsTable.innerHTML = tableHTML;
    }
    
    /**
     * UI Control Methods
     */
    toggleFullscreen() {
        const raceTrack = document.getElementById('race-track');
        if (!raceTrack) return;
        
        if (!document.fullscreenElement) {
            raceTrack.requestFullscreen().then(() => {
                this.isFullscreen = true;
                console.log('🖥️ Entered fullscreen mode');
            }).catch(err => {
                console.warn('Fullscreen request failed:', err);
            });
        } else {
            document.exitFullscreen().then(() => {
                this.isFullscreen = false;
                console.log('🖥️ Exited fullscreen mode');
            });
        }
    }
    
    toggleSound() {
        this.soundEnabled = !this.soundEnabled;
        this.soundController.setEnabled(this.soundEnabled);
        
        const soundBtn = document.getElementById('sound-btn');
        if (soundBtn) {
            const icon = soundBtn.querySelector('i');
            if (icon) {
                icon.className = this.soundEnabled ? 'fas fa-volume-up' : 'fas fa-volume-mute';
            }
        }
        
        console.log(`🔊 Sound ${this.soundEnabled ? 'enabled' : 'disabled'}`);
    }
    
    toggleChat() {
        this.chatVisible = !this.chatVisible;
        const chatSection = document.getElementById('chat-section');
        
        if (chatSection) {
            chatSection.style.display = this.chatVisible ? 'block' : 'none';
        }
        
        console.log(`💬 Chat ${this.chatVisible ? 'shown' : 'hidden'}`);
    }
    
    /**
     * Chat Methods
     */
    sendChatMessage() {
        const chatInput = document.getElementById('chat-input');
        if (!chatInput || !this.websocketConnection) return;
        
        const message = chatInput.value.trim();
        if (!message) return;
        
        const chatData = {
            user_id: this.userId,
            username: this.getUserDisplayName(),
            message: message,
            timestamp: Date.now()
        };
        
        this.websocketConnection.whisper('chat.message', chatData);
        this.addChatMessage(chatData, true);
        
        chatInput.value = '';
    }
    
    handleChatMessage(data) {
        if (data.user_id !== this.userId) {
            this.addChatMessage(data, false);
        }
    }
    
    addChatMessage(data, isOwn) {
        const chatMessages = document.getElementById('chat-messages');
        if (!chatMessages) return;
        
        const messageElement = document.createElement('div');
        messageElement.className = `chat-message ${isOwn ? 'own' : 'other'}`;
        messageElement.innerHTML = `
            <div class="message-header">
                <span class="username">${data.username}</span>
                <span class="timestamp">${new Date(data.timestamp).toLocaleTimeString()}</span>
            </div>
            <div class="message-text">${this.sanitizeMessage(data.message)}</div>
        `;
        
        chatMessages.appendChild(messageElement);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
    
    sanitizeMessage(message) {
        return message.replace(/[<>]/g, '');
    }
    
    getUserDisplayName() {
        if (this.userParticipant) {
            const nameElement = this.userParticipant.element.querySelector('.participant-name');
            if (nameElement) {
                return nameElement.textContent.replace(' YOU', '').trim();
            }
        }
        return 'Anonymous';
    }
    
    /**
     * Event Handlers
     */
    handleKeyboardShortcuts(e) {
        if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') {
            return; // Don't trigger shortcuts when typing
        }
        
        switch (e.key) {
            case 'F11':
                e.preventDefault();
                this.toggleFullscreen();
                break;
            case 'Escape':
                if (document.fullscreenElement) {
                    document.exitFullscreen();
                }
                break;
            case 'm':
            case 'M':
                if (e.ctrlKey) {
                    e.preventDefault();
                    this.toggleSound();
                }
                break;
            case 'c':
            case 'C':
                if (e.ctrlKey) {
                    e.preventDefault();
                    this.toggleChat();
                }
                break;
        }
    }
    
    handleWindowResize() {
        // Handle responsive changes
        if (window.innerWidth < 768 && this.chatVisible) {
            this.toggleChat(); // Auto-hide chat on mobile
        }
    }
    
    handleBeforeUnload() {
        // Cleanup WebSocket connection
        if (this.websocketConnection) {
            this.websocketConnection.disconnect();
        }
    }
    
    handleVisibilityChange() {
        if (document.visibilityState === 'visible') {
            // Resume updates when tab becomes visible
            if (this.competitionStatus === 'active' && !this.botUpdateInterval) {
                this.setupBotSimulation();
            }
        } else {
            // Pause bot simulation when tab is hidden to save resources
            if (this.botUpdateInterval) {
                clearInterval(this.botUpdateInterval);
                this.botUpdateInterval = null;
            }
        }
    }
    
    /**
     * WebSocket Event Handlers
     */
    handleUsersPresence(users) {
        const participantCount = document.getElementById('participant-count');
        if (participantCount) {
            participantCount.textContent = users.length;
        }
    }
    
    handleUserJoined(user) {
        // Add user to participants if not already present
        // This would require dynamic participant addition
        console.log('User joined:', user);
    }
    
    handleUserLeft(user) {
        // Handle user leaving
        console.log('User left:', user);
    }
    
    handleCompetitionStarted(e) {
        this.startCompetition();
    }
    
    handleCompetitionEnded(e) {
        this.endCompetition();
    }
    
    handleCompetitionCountdown(e) {
        // Handle countdown updates from server
        console.log('Countdown update:', e);
    }
    
    /**
     * Utility Methods
     */
    broadcastEvent(eventType, data = {}) {
        if (this.websocketConnection) {
            this.websocketConnection.whisper(eventType, {
                user_id: this.userId,
                timestamp: Date.now(),
                ...data
            });
        }
    }
    
    closeResults() {
        const resultsModal = document.getElementById('results-modal');
        if (resultsModal) {
            resultsModal.style.display = 'none';
        }
    }
    
    viewDetailedResults() {
        // Navigate to detailed results page
        window.location.href = `/competitions/${this.competitionId}/results`;
    }
    
    joinAnotherCompetition() {
        // Navigate to competitions list
        window.location.href = '/competitions';
    }
    
    /**
     * Performance Optimization
     */
    setupPerformanceOptimization() {
        // Use requestAnimationFrame for smooth animations
        this.animationFrameId = null;
        this.startAnimationLoop();
    }
    
    startAnimationLoop() {
        const animate = () => {
            this.processUpdateQueue();
            this.animationFrameId = requestAnimationFrame(animate);
        };
        animate();
    }
    
    processUpdateQueue() {
        while (this.updateQueue.length > 0) {
            const update = this.updateQueue.shift();
            update();
        }
    }
    
    queueUpdate(updateFunction) {
        this.updateQueue.push(updateFunction);
    }
    
    /**
     * Initialize UI components
     */
    initializeUI() {
        // Setup particle background
        this.setupParticleBackground();
        
        // Initialize progress animations
        this.initializeProgressAnimations();
        
        // Setup responsive handlers
        this.setupResponsiveHandlers();
    }
    
    setupParticleBackground() {
        // Add subtle particle animation to race track background
        // This could be implemented with Canvas or CSS animations
    }
    
    initializeProgressAnimations() {
        // Set initial progress bar states
        const progressBars = document.querySelectorAll('.progress-fill');
        progressBars.forEach(bar => {
            bar.style.width = '0%';
            bar.style.transition = 'width 0.3s ease';
        });
    }
    
    setupResponsiveHandlers() {
        // Setup responsive behavior for different screen sizes
        const mediaQuery = window.matchMedia('(max-width: 768px)');
        mediaQuery.addListener((e) => {
            if (e.matches) {
                // Mobile optimizations
                this.optimizeForMobile();
            } else {
                // Desktop optimizations
                this.optimizeForDesktop();
            }
        });
        
        // Initial setup
        if (mediaQuery.matches) {
            this.optimizeForMobile();
        }
    }
    
    optimizeForMobile() {
        // Mobile-specific optimizations
        if (this.chatVisible) {
            this.toggleChat();
        }
    }
    
    optimizeForDesktop() {
        // Desktop-specific optimizations
    }
    
    /**
     * Cleanup methods
     */
    destroy() {
        // Clear all intervals
        if (this.timerInterval) clearInterval(this.timerInterval);
        if (this.botUpdateInterval) clearInterval(this.botUpdateInterval);
        if (this.leaderboardUpdateInterval) clearTimeout(this.leaderboardUpdateInterval);
        if (this.animationFrameId) cancelAnimationFrame(this.animationFrameId);
        
        // Disconnect WebSocket
        if (this.websocketConnection) {
            this.websocketConnection.disconnect();
        }
        
        // Remove event listeners
        document.removeEventListener('typing:started', this.handleTypingStarted);
        document.removeEventListener('typing:progress', this.handleTypingProgress);
        document.removeEventListener('typing:completed', this.handleTypingCompleted);
        
        console.log('🧹 Competition Arena Controller destroyed');
    }
}

/**
 * Competition Sound Controller
 * Handles all sound effects for the competition
 */
class CompetitionSoundController {
    constructor() {
        this.enabled = true;
        this.volume = 0.5;
        this.sounds = {};
        
        this.initializeSounds();
    }
    
    initializeSounds() {
        // Define sound URLs (these would be actual sound files)
        const soundDefinitions = {
            start: '/sounds/race-start.mp3',
            finish: '/sounds/finish.mp3',
            victory: '/sounds/victory.mp3',
            join: '/sounds/join.mp3',
            positionUp: '/sounds/position-up.mp3',
            remoteFinish: '/sounds/remote-finish.mp3',
            end: '/sounds/race-end.mp3'
        };
        
        // Preload sounds
        Object.entries(soundDefinitions).forEach(([key, url]) => {
            this.sounds[key] = new Audio(url);
            this.sounds[key].volume = this.volume;
            this.sounds[key].preload = 'auto';
        });
    }
    
    setEnabled(enabled) {
        this.enabled = enabled;
    }
    
    setVolume(volume) {
        this.volume = Math.max(0, Math.min(1, volume));
        Object.values(this.sounds).forEach(sound => {
            sound.volume = this.volume;
        });
    }
    
    playSound(soundName) {
        if (!this.enabled || !this.sounds[soundName]) return;
        
        try {
            this.sounds[soundName].currentTime = 0;
            this.sounds[soundName].play().catch(err => {
                console.warn(`Could not play sound ${soundName}:`, err);
            });
        } catch (error) {
            console.warn(`Error playing sound ${soundName}:`, error);
        }
    }
    
    playStartSound() { this.playSound('start'); }
    playFinishSound() { this.playSound('finish'); }
    playVictorySound() { this.playSound('victory'); }
    playJoinSound() { this.playSound('join'); }
    playPositionUpSound() { this.playSound('positionUp'); }
    playRemoteFinishSound() { this.playSound('remoteFinish'); }
    playEndSound() { this.playSound('end'); }
}

// Export for use in other modules
if (typeof module !== 'undefined' && module.exports) {
    module.exports = { CompetitionArenaController, CompetitionSoundController };
}

// Global initialization function
window.initializeCompetitionArena = function(options) {
    return new CompetitionArenaController(options);
};

// Add CSS animations for competition effects
const competitionStyles = document.createElement('style');
competitionStyles.textContent = `
    @keyframes confettiFall {
        0% {
            transform: translateY(-100vh) rotate(0deg);
            opacity: 1;
        }
        100% {
            transform: translateY(100vh) rotate(720deg);
            opacity: 0;
        }
    }
    
    @keyframes victoryPulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.02); }
    }
    
    @keyframes startPulse {
        0%, 100% { transform: translate(-50%, -50%) scale(1); }
        50% { transform: translate(-50%, -50%) scale(1.05); }
    }
    
    @keyframes positionImproved {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); background: rgba(16, 185, 129, 0.1); }
        100% { transform: scale(1); }
    }
    
    .participant.victory .participant-lane {
        animation: victoryPulse 1s ease-in-out 3;
        border-color: var(--accent-secondary) !important;
        background: linear-gradient(135deg, rgba(245, 158, 11, 0.2), var(--bg-card)) !important;
    }
    
    .participant.position-improved .participant-lane {
        animation: positionImproved 1s ease-in-out;
    }
    
    .participant.completed .participant-lane {
        border-color: var(--accent-success) !important;
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), var(--bg-card)) !important;
    }
    
    .leaderboard-item {
        transition: all 0.3s ease;
    }
    
    .leaderboard-item:hover {
        transform: translateX(5px);
        background: var(--bg-tertiary);
    }
    
    .result-row {
        transition: all 0.3s ease;
    }
    
    .result-row:hover {
        background: var(--bg-tertiary);
    }
    
    .progress-car {
        transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .chat-message {
        animation: messageSlideIn 0.3s ease;
    }
    
    @keyframes messageSlideIn {
        from {
            transform: translateX(-20px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
`;

document.head.appendChild(competitionStyles);

console.log('🏁 Competition.js loaded successfully');
