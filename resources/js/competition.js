class CompetitionRace {
    constructor(options = {}) {
        // Configuration
        this.config = {
            competitionId: options.competitionId,
            textElement: options.textElement || "#competition-text",
            inputElement: options.inputElement || "#competition-input",
            raceTrackElement: options.raceTrackElement || "#race-track",
            participantsElement:
                options.participantsElement || "#participants-list",
            timerElement: options.timerElement || "#race-timer",
            countdownElement: options.countdownElement || "#countdown",
            statsElement: options.statsElement || "#race-stats",
            enableSound: options.enableSound || true,
            updateInterval: options.updateInterval || 200,
            apiEndpoint: options.apiEndpoint || "/api/competitions",
        };

        // Race state
        this.raceState = "waiting"; // waiting, countdown, racing, finished
        this.startTime = null;
        this.countdownDuration = 5;
        this.participants = new Map();
        this.bots = new Map();
        this.userProgress = 0;
        this.raceText = "";

        // Real-time updates
        this.updateTimer = null;
        this.botUpdateTimer = null;
        this.websocket = null;

        // Event callbacks
        this.callbacks = {
            onRaceStart: options.onRaceStart || (() => {}),
            onRaceFinish: options.onRaceFinish || (() => {}),
            onPositionChange: options.onPositionChange || (() => {}),
            onUpdate: options.onUpdate || (() => {}),
        };

        this.init();
    }

    init() {
        this.setupElements();
        this.setupEventListeners();
        this.initializeRaceTrack();
        this.loadParticipants();

        // Initialize WebSocket for real-time updates
        this.initWebSocket();
    }

    setupElements() {
        this.textElement = document.querySelector(this.config.textElement);
        this.inputElement = document.querySelector(this.config.inputElement);
        this.raceTrackElement = document.querySelector(
            this.config.raceTrackElement
        );
        this.participantsElement = document.querySelector(
            this.config.participantsElement
        );
        this.timerElement = document.querySelector(this.config.timerElement);
        this.countdownElement = document.querySelector(
            this.config.countdownElement
        );
        this.statsElement = document.querySelector(this.config.statsElement);

        if (this.textElement) {
            this.raceText = this.textElement.textContent.trim();
        }
    }

    setupEventListeners() {
        if (this.inputElement) {
            this.inputElement.addEventListener("input", (e) =>
                this.handleTyping(e)
            );
            this.inputElement.addEventListener("keydown", (e) =>
                this.handleKeyDown(e)
            );
        }

        // Join race button
        const joinButton = document.querySelector("#join-race-btn");
        if (joinButton) {
            joinButton.addEventListener("click", () => this.joinRace());
        }

        // Start race button (for admin/host)
        const startButton = document.querySelector("#start-race-btn");
        if (startButton) {
            startButton.addEventListener("click", () => this.startRace());
        }
    }

    initWebSocket() {
        // Initialize WebSocket connection for real-time updates
        // This would connect to Laravel Echo/Pusher for live updates
        if (window.Echo) {
            window.Echo.channel(`competition.${this.config.competitionId}`)
                .listen("RaceStarted", (e) => this.onRaceStarted(e))
                .listen("ParticipantJoined", (e) => this.onParticipantJoined(e))
                .listen("ProgressUpdate", (e) => this.onProgressUpdate(e))
                .listen("RaceFinished", (e) => this.onRaceFinished(e));
        }
    }

    async joinRace() {
        try {
            const response = await fetch(
                `${this.config.apiEndpoint}/${this.config.competitionId}/join`,
                {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector(
                            'meta[name="csrf-token"]'
                        ).content,
                    },
                }
            );

            if (response.ok) {
                this.raceState = "joined";
                this.updateUI();
                this.showNotification(
                    "Successfully joined the race!",
                    "success"
                );
            }
        } catch (error) {
            console.error("Failed to join race:", error);
            this.showNotification("Failed to join race", "error");
        }
    }

    async startRace() {
        if (this.raceState !== "waiting") return;

        this.raceState = "countdown";
        this.startCountdown();
    }

    startCountdown() {
        let countdown = this.countdownDuration;

        this.updateCountdownDisplay(countdown);

        const countdownInterval = setInterval(() => {
            countdown--;
            this.updateCountdownDisplay(countdown);

            if (this.config.enableSound) {
                this.playSound(
                    countdown === 0 ? "race-start" : "countdown-tick"
                );
            }

            if (countdown <= 0) {
                clearInterval(countdownInterval);
                this.beginRace();
            }
        }, 1000);
    }

    beginRace() {
        this.raceState = "racing";
        this.startTime = Date.now();

        // Enable input
        if (this.inputElement) {
            this.inputElement.disabled = false;
            this.inputElement.focus();
        }

        // Start update timers
        this.updateTimer = setInterval(
            () => this.updateRaceProgress(),
            this.config.updateInterval
        );
        this.botUpdateTimer = setInterval(() => this.updateBots(), 100);

        // Hide countdown, show race UI
        this.updateUI();
        this.callbacks.onRaceStart();
    }

    handleTyping(e) {
        if (this.raceState !== "racing") return;

        const typedText = e.target.value;
        this.userProgress = Math.min(
            100,
            (typedText.length / this.raceText.length) * 100
        );

        // Update visual progress
        this.updateRaceTrack();

        // Send progress to server
        this.sendProgressUpdate();

        // Check for race completion
        if (typedText.length >= this.raceText.length) {
            this.finishRace();
        }
    }

    handleKeyDown(e) {
        if (this.raceState !== "racing") return;

        // Prevent certain keys during race
        if (
            e.key === "Tab" ||
            (e.ctrlKey && ["a", "v", "c", "x"].includes(e.key))
        ) {
            e.preventDefault();
        }
    }

    async sendProgressUpdate() {
        if (!this.config.competitionId) return;

        try {
            await fetch(
                `${this.config.apiEndpoint}/${this.config.competitionId}/progress`,
                {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector(
                            'meta[name="csrf-token"]'
                        ).content,
                    },
                    body: JSON.stringify({
                        progress: this.userProgress,
                        typed_text: this.inputElement.value,
                        timestamp: Date.now(),
                    }),
                }
            );
        } catch (error) {
            console.error("Failed to send progress update:", error);
        }
    }

    updateRaceProgress() {
        this.updateTimer();
        this.updateRaceTrack();
        this.updateParticipantsList();
        this.callbacks.onUpdate(this.getRaceStats());
    }

    updateTimer() {
        if (!this.timerElement || !this.startTime) return;

        const elapsed = Math.floor((Date.now() - this.startTime) / 1000);
        const minutes = Math.floor(elapsed / 60);
        const seconds = elapsed % 60;

        this.timerElement.textContent = `${minutes
            .toString()
            .padStart(2, "0")}:${seconds.toString().padStart(2, "0")}`;
    }

    initializeRaceTrack() {
        if (!this.raceTrackElement) return;

        const trackHTML = `
            <div class="race-track">
                <div class="track-header">
                    <div class="track-title">Race Progress</div>
                    <div class="track-legend">
                        <span class="legend-item user"><i class="fas fa-user"></i> You</span>
                        <span class="legend-item bot"><i class="fas fa-robot"></i> Bots</span>
                    </div>
                </div>
                <div class="track-lanes">
                    <div class="track-lane user-lane" id="user-lane">
                        <div class="lane-info">
                            <div class="participant-name">You</div>
                            <div class="participant-progress">0%</div>
                        </div>
                        <div class="lane-track">
                            <div class="track-background"></div>
                            <div class="track-progress" style="width: 0%"></div>
                            <div class="participant-car user-car">
                                <i class="fas fa-car"></i>
                            </div>
                        </div>
                        <div class="lane-stats">
                            <span class="lane-wpm">0 WPM</span>
                        </div>
                    </div>
                </div>
                <div class="finish-line">
                    <i class="fas fa-flag-checkered"></i>
                    <span>FINISH</span>
                </div>
            </div>
        `;

        this.raceTrackElement.innerHTML = trackHTML;
    }

    updateRaceTrack() {
        // Update user lane
        const userLane = document.querySelector("#user-lane");
        if (userLane) {
            const progressBar = userLane.querySelector(".track-progress");
            const participantCar = userLane.querySelector(".participant-car");
            const progressText = userLane.querySelector(
                ".participant-progress"
            );
            const wpmText = userLane.querySelector(".lane-wpm");

            if (progressBar) {
                progressBar.style.width = `${this.userProgress}%`;
            }

            if (participantCar) {
                participantCar.style.left = `${Math.min(
                    95,
                    this.userProgress
                )}%`;
            }

            if (progressText) {
                progressText.textContent = `${Math.round(this.userProgress)}%`;
            }

            if (wpmText && this.inputElement) {
                const wpm = this.calculateCurrentWPM();
                wpmText.textContent = `${wpm} WPM`;
            }
        }

        // Update bot lanes
        this.bots.forEach((bot, id) => {
            const botLane = document.querySelector(`#bot-lane-${id}`);
            if (botLane) {
                const progressBar = botLane.querySelector(".track-progress");
                const participantCar =
                    botLane.querySelector(".participant-car");
                const progressText = botLane.querySelector(
                    ".participant-progress"
                );
                const wpmText = botLane.querySelector(".lane-wpm");

                if (progressBar) {
                    progressBar.style.width = `${bot.progress}%`;
                }

                if (participantCar) {
                    participantCar.style.left = `${Math.min(
                        95,
                        bot.progress
                    )}%`;
                }

                if (progressText) {
                    progressText.textContent = `${Math.round(bot.progress)}%`;
                }

                if (wpmText) {
                    wpmText.textContent = `${bot.wpm} WPM`;
                }
            }
        });
    }

    addBotToTrack(bot) {
        const trackLanes = document.querySelector(".track-lanes");
        if (!trackLanes) return;

        const botLaneHTML = `
            <div class="track-lane bot-lane" id="bot-lane-${bot.id}">
                <div class="lane-info">
                    <div class="participant-name">${bot.name}</div>
                    <div class="participant-progress">0%</div>
                </div>
                <div class="lane-track">
                    <div class="track-background"></div>
                    <div class="track-progress" style="width: 0%"></div>
                    <div class="participant-car bot-car">
                        <i class="fas fa-robot"></i>
                    </div>
                </div>
                <div class="lane-stats">
                    <span class="lane-wpm">0 WPM</span>
                </div>
            </div>
        `;

        trackLanes.insertAdjacentHTML("beforeend", botLaneHTML);
    }

    updateBots() {
        if (this.raceState !== "racing") return;

        const elapsedSeconds = (Date.now() - this.startTime) / 1000;

        this.bots.forEach((bot, id) => {
            // Simulate bot typing with some randomness
            const baseSpeed = bot.wpm / 60; // characters per second
            const variance = 0.2; // 20% variance
            const actualSpeed =
                baseSpeed * (1 + (Math.random() - 0.5) * variance);

            // Update bot progress
            const expectedProgress = Math.min(
                100,
                ((actualSpeed * 5 * elapsedSeconds) / this.raceText.length) *
                    100
            );
            bot.progress = Math.min(
                bot.progress + (expectedProgress - bot.progress) * 0.1,
                100
            );

            // Add some typing mistakes for realism
            if (Math.random() < 0.02) {
                // 2% chance of a small delay (mistake)
                bot.progress = Math.max(0, bot.progress - Math.random() * 2);
            }

            // Check if bot finished
            if (bot.progress >= 100 && !bot.finished) {
                bot.finished = true;
                bot.finishTime = Date.now();
                this.showNotification(`${bot.name} finished the race!`, "info");

                if (this.config.enableSound) {
                    this.playSound("bot-finish");
                }
            }
        });
    }

    calculateCurrentWPM() {
        if (!this.startTime || !this.inputElement) return 0;

        const elapsedMinutes = (Date.now() - this.startTime) / 60000;
        const typedCharacters = this.inputElement.value.length;
        const words = typedCharacters / 5; // Standard: 5 characters = 1 word

        return elapsedMinutes > 0 ? Math.round(words / elapsedMinutes) : 0;
    }

    finishRace() {
        if (this.raceState !== "racing") return;

        this.raceState = "finished";

        // Clear timers
        if (this.updateTimer) clearInterval(this.updateTimer);
        if (this.botUpdateTimer) clearInterval(this.botUpdateTimer);

        // Disable input
        if (this.inputElement) {
            this.inputElement.disabled = true;
        }

        // Calculate final stats
        const finalStats = this.getRaceStats();

        // Show completion message
        this.showNotification("Race completed! 🏁", "success");

        if (this.config.enableSound) {
            this.playSound("race-finish");
        }

        // Submit final result
        this.submitRaceResult(finalStats);

        this.callbacks.onRaceFinish(finalStats);
    }

    async submitRaceResult(stats) {
        try {
            const response = await fetch(
                `${this.config.apiEndpoint}/${this.config.competitionId}/result`,
                {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector(
                            'meta[name="csrf-token"]'
                        ).content,
                    },
                    body: JSON.stringify({
                        typed_text: this.inputElement.value,
                        completion_time: stats.completionTime,
                        wpm: stats.wpm,
                        accuracy: stats.accuracy,
                        keystrokes: stats.totalKeystrokes,
                    }),
                }
            );

            if (response.ok) {
                const result = await response.json();
                // Redirect to results page
                setTimeout(() => {
                    window.location.href = result.redirect_url;
                }, 2000);
            }
        } catch (error) {
            console.error("Failed to submit race result:", error);
        }
    }

    getRaceStats() {
        const typedText = this.inputElement ? this.inputElement.value : "";
        const elapsedTime = this.startTime
            ? (Date.now() - this.startTime) / 1000
            : 0;

        // Calculate accuracy
        let correctChars = 0;
        for (
            let i = 0;
            i < Math.min(typedText.length, this.raceText.length);
            i++
        ) {
            if (typedText[i] === this.raceText[i]) {
                correctChars++;
            }
        }

        const accuracy =
            typedText.length > 0
                ? (correctChars / typedText.length) * 100
                : 100;
        const wpm = this.calculateCurrentWPM();

        return {
            progress: this.userProgress,
            wpm: wpm,
            accuracy: Math.round(accuracy * 10) / 10,
            elapsedTime: elapsedTime,
            completionTime: Math.round(elapsedTime),
            typedCharacters: typedText.length,
            correctCharacters: correctChars,
            totalKeystrokes: typedText.length, // This would be more accurate with actual keystroke counting
        };
    }

    loadParticipants() {
        // Load initial participants and bots
        // This would typically fetch from the server
        const mockBots = [
            { id: 1, name: "SpeedBot", wpm: 75, progress: 0, finished: false },
            {
                id: 2,
                name: "AccuracyBot",
                wpm: 60,
                progress: 0,
                finished: false,
            },
            {
                id: 3,
                name: "ConsistentBot",
                wpm: 85,
                progress: 0,
                finished: false,
            },
        ];

        mockBots.forEach((bot) => {
            this.bots.set(bot.id, bot);
            this.addBotToTrack(bot);
        });
    }

    updateCountdownDisplay(count) {
        if (this.countdownElement) {
            if (count > 0) {
                this.countdownElement.innerHTML = `
                    <div class="countdown-number">${count}</div>
                    <div class="countdown-text">Get Ready...</div>
                `;
            } else {
                this.countdownElement.innerHTML = `
                    <div class="countdown-number">GO!</div>
                    <div class="countdown-text">Start Typing!</div>
                `;
                setTimeout(() => {
                    this.countdownElement.style.display = "none";
                }, 1000);
            }
        }
    }

    updateUI() {
        // Update UI based on race state
        const joinButton = document.querySelector("#join-race-btn");
        const startButton = document.querySelector("#start-race-btn");
        const raceArea = document.querySelector("#race-area");

        switch (this.raceState) {
            case "waiting":
                if (joinButton) joinButton.style.display = "block";
                if (startButton) startButton.style.display = "block";
                if (raceArea) raceArea.style.display = "none";
                break;

            case "joined":
                if (joinButton) joinButton.style.display = "none";
                if (raceArea) raceArea.style.display = "block";
                break;

            case "countdown":
                if (this.countdownElement)
                    this.countdownElement.style.display = "flex";
                break;

            case "racing":
                if (this.countdownElement)
                    this.countdownElement.style.display = "none";
                break;
        }
    }

    playSound(type) {
        // Play sound effects for different events
        const sounds = {
            "countdown-tick": "/sounds/tick.mp3",
            "race-start": "/sounds/race-start.mp3",
            "race-finish": "/sounds/finish.mp3",
            "bot-finish": "/sounds/notification.mp3",
        };

        if (sounds[type]) {
            const audio = new Audio(sounds[type]);
            audio.volume = 0.3;
            audio.play().catch(() => {}); // Ignore errors if sound can't play
        }
    }

    showNotification(message, type = "info") {
        // Create and show notification
        const notification = document.createElement("div");
        notification.className = `race-notification notification-${type}`;
        notification.innerHTML = `
            <div class="notification-content">
                <i class="fas fa-${
                    type === "success"
                        ? "check-circle"
                        : type === "error"
                        ? "exclamation-circle"
                        : "info-circle"
                }"></i>
                <span>${message}</span>
            </div>
        `;

        document.body.appendChild(notification);

        // Animate in
        setTimeout(() => notification.classList.add("show"), 100);

        // Remove after delay
        setTimeout(() => {
            notification.classList.remove("show");
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }

    // Event handlers for WebSocket events
    onRaceStarted(event) {
        this.startCountdown();
    }

    onParticipantJoined(event) {
        // Add new participant to the race
        console.log("Participant joined:", event.participant);
    }

    onProgressUpdate(event) {
        // Update other participants' progress
        if (this.participants.has(event.participant.id)) {
            this.participants.get(event.participant.id).progress =
                event.progress;
        }
    }

    onRaceFinished(event) {
        // Handle race finish event
        console.log("Race finished:", event);
    }

    // Public methods
    destroy() {
        if (this.updateTimer) clearInterval(this.updateTimer);
        if (this.botUpdateTimer) clearInterval(this.botUpdateTimer);

        if (this.websocket) {
            this.websocket.disconnect();
        }
    }
}

// CSS for competition race
const competitionCSS = `
.race-track {
    background: var(--bg-card);
    border: 2px solid var(--border-light);
    border-radius: var(--border-radius-lg);
    padding: 1.5rem;
    margin: 2rem 0;
}

.track-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
}

.track-title {
    font-family: var(--font-display);
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--text-primary);
}

.track-legend {
    display: flex;
    gap: 1rem;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.9rem;
    color: var(--text-secondary);
}

.legend-item.user { color: var(--accent-primary); }
.legend-item.bot { color: var(--text-secondary); }

.track-lanes {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    margin-bottom: 1rem;
}

.track-lane {
    display: grid;
    grid-template-columns: 150px 1fr 100px;
    gap: 1rem;
    align-items: center;
    padding: 1rem;
    background: var(--bg-secondary);
    border-radius: var(--border-radius);
    transition: all 0.3s ease;
}

.track-lane:hover {
    background: var(--bg-tertiary);
}

.user-lane {
    border-left: 4px solid var(--accent-primary);
}

.bot-lane {
    border-left: 4px solid var(--text-secondary);
}

.lane-info {
    text-align: center;
}

.participant-name {
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 0.25rem;
}

.participant-progress {
    font-size: 0.9rem;
    color: var(--text-secondary);
}

.lane-track {
    position: relative;
    height: 40px;
    border-radius: var(--border-radius);
    overflow: hidden;
}

.track-background {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: repeating-linear-gradient(
        90deg,
        var(--border-light) 0px,
        var(--border-light) 10px,
        transparent 10px,
        transparent 20px
    );
    border: 2px solid var(--border-medium);
    border-radius: var(--border-radius);
}

.track-progress {
    position: absolute;
    top: 2px;
    left: 2px;
    bottom: 2px;
    background: var(--champion-gradient);
    border-radius: var(--border-radius-sm);
    transition: width 0.3s ease;
    opacity: 0.3;
}

.participant-car {
    position: absolute;
    top: 50%;
    left: 0%;
    transform: translateY(-50%);
    width: 36px;
    height: 36px;
    background: var(--bg-card);
    border: 2px solid var(--accent-primary);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    transition: left 0.3s ease;
    z-index: 2;
}

.user-car {
    color: var(--accent-primary);
    border-color: var(--accent-primary);
    box-shadow: 0 0 10px rgba(59, 130, 246, 0.3);
}

.bot-car {
    color: var(--text-secondary);
    border-color: var(--text-secondary);
}

.lane-stats {
    text-align: center;
}

.lane-wpm {
    font-family: var(--font-display);
    font-weight: 600;
    color: var(--accent-primary);
}

.finish-line {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 1rem;
    background: var(--medal-gradient);
    color: white;
    border-radius: var(--border-radius);
    font-weight: 700;
    animation: finish-pulse 2s ease-in-out infinite;
}

@keyframes finish-pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.02); }
}

.countdown-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.8);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    z-index: 1000;
}

.countdown-number {
    font-family: var(--font-display);
    font-size: 8rem;
    font-weight: 900;
    color: var(--accent-primary);
    text-shadow: 0 0 20px rgba(59, 130, 246, 0.5);
    animation: countdown-bounce 1s ease-out;
}

.countdown-text {
    font-size: 1.5rem;
    color: white;
    margin-top: 1rem;
}

@keyframes countdown-bounce {
    0% { transform: scale(0.3); opacity: 0; }
    50% { transform: scale(1.2); }
    100% { transform: scale(1); opacity: 1; }
}

.race-notification {
    position: fixed;
    top: 2rem;
    right: 2rem;
    padding: 1rem 1.5rem;
    border-radius: var(--border-radius);
    color: white;
    font-weight: 600;
    z-index: 1001;
    transform: translateX(100%);
    transition: transform 0.3s ease;
}

.race-notification.show {
    transform: translateX(0);
}

.notification-success {
    background: var(--victory-gradient);
}

.notification-error {
    background: linear-gradient(135deg, var(--accent-danger), #dc2626);
}

.notification-info {
    background: var(--champion-gradient);
}

.notification-content {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .track-lane {
        grid-template-columns: 100px 1fr 80px;
        gap: 0.5rem;
        padding: 0.75rem;
    }
    
    .participant-car {
        width: 28px;
        height: 28px;
        font-size: 0.8rem;
    }
    
    .countdown-number {
        font-size: 4rem;
    }
    
    .race-notification {
        top: 1rem;
        right: 1rem;
        left: 1rem;
        transform: translateY(-100%);
    }
    
    .race-notification.show {
        transform: translateY(0);
    }
}
`;

// Inject CSS
if (!document.querySelector("#competition-styles")) {
    const style = document.createElement("style");
    style.id = "competition-styles";
    style.textContent = competitionCSS;
    document.head.appendChild(style);
}

// Export for module systems
if (typeof module !== "undefined" && module.exports) {
    module.exports = CompetitionRace;
}

// Global namespace
window.CompetitionRace = CompetitionRace;
