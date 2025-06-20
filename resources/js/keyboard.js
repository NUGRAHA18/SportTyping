class VirtualKeyboard {
    constructor(options = {}) {
        // Configuration
        this.config = {
            container: options.container || "#virtual-keyboard",
            handContainer: options.handContainer || "#hand-position",
            showHands: options.showHands !== false,
            showFingerColors: options.showFingerColors !== false,
            keyLayout: options.keyLayout || "qwerty",
            highlightNextKey: options.highlightNextKey !== false,
            playKeySound: options.playKeySound || false,
            showKeyTips: options.showKeyTips !== false,
            language: options.language || "en",
        };

        // State
        this.currentKey = null;
        this.pressedKeys = new Set();
        this.fingerPositions = new Map();

        // Finger mapping for proper hand positioning
        this.fingerMapping = {
            // Left hand
            q: "left-pinky",
            w: "left-ring",
            e: "left-middle",
            r: "left-index",
            t: "left-index",
            a: "left-pinky",
            s: "left-ring",
            d: "left-middle",
            f: "left-index",
            g: "left-index",
            z: "left-pinky",
            x: "left-ring",
            c: "left-middle",
            v: "left-index",
            b: "left-index",
            1: "left-pinky",
            2: "left-ring",
            3: "left-middle",
            4: "left-index",
            5: "left-index",
            tab: "left-pinky",
            capslock: "left-pinky",
            "shift-left": "left-pinky",

            // Right hand
            y: "right-index",
            u: "right-index",
            i: "right-middle",
            o: "right-ring",
            p: "right-pinky",
            h: "right-index",
            j: "right-index",
            k: "right-middle",
            l: "right-ring",
            ";": "right-pinky",
            n: "right-index",
            m: "right-index",
            ",": "right-middle",
            ".": "right-ring",
            "/": "right-pinky",
            6: "right-index",
            7: "right-index",
            8: "right-middle",
            9: "right-ring",
            0: "right-pinky",
            "shift-right": "right-pinky",
            enter: "right-pinky",

            // Thumbs
            " ": "thumb",
        };

        // Finger colors for visual learning
        this.fingerColors = {
            "left-pinky": "#ef4444", // Red
            "left-ring": "#f97316", // Orange
            "left-middle": "#eab308", // Yellow
            "left-index": "#22c55e", // Green
            "right-index": "#22c55e", // Green
            "right-middle": "#3b82f6", // Blue
            "right-ring": "#8b5cf6", // Purple
            "right-pinky": "#ec4899", // Pink
            thumb: "#6b7280", // Gray
        };

        // Callbacks
        this.callbacks = {
            onKeyPress: options.onKeyPress || (() => {}),
            onKeyRelease: options.onKeyRelease || (() => {}),
            onFingerMove: options.onFingerMove || (() => {}),
        };

        this.init();
    }

    init() {
        this.container = document.querySelector(this.config.container);
        this.handContainer = document.querySelector(this.config.handContainer);

        if (!this.container) {
            console.error("VirtualKeyboard: Container not found");
            return;
        }

        this.createKeyboard();

        if (this.config.showHands && this.handContainer) {
            this.createHandPosition();
        }

        this.setupEventListeners();
    }

    createKeyboard() {
        const keyboardHTML = `
            <div class="virtual-keyboard">
                ${this.createKeyboardRow(
                    [
                        { key: "1", shift: "!", finger: "left-pinky" },
                        { key: "2", shift: "@", finger: "left-ring" },
                        { key: "3", shift: "#", finger: "left-middle" },
                        { key: "4", shift: "$", finger: "left-index" },
                        { key: "5", shift: "%", finger: "left-index" },
                        { key: "6", shift: "^", finger: "right-index" },
                        { key: "7", shift: "&", finger: "right-index" },
                        { key: "8", shift: "*", finger: "right-middle" },
                        { key: "9", shift: "(", finger: "right-ring" },
                        { key: "0", shift: ")", finger: "right-pinky" },
                    ],
                    "number-row"
                )}
                
                ${this.createKeyboardRow(
                    [
                        { key: "q", finger: "left-pinky" },
                        { key: "w", finger: "left-ring" },
                        { key: "e", finger: "left-middle" },
                        { key: "r", finger: "left-index" },
                        { key: "t", finger: "left-index" },
                        { key: "y", finger: "right-index" },
                        { key: "u", finger: "right-index" },
                        { key: "i", finger: "right-middle" },
                        { key: "o", finger: "right-ring" },
                        { key: "p", finger: "right-pinky" },
                    ],
                    "top-row"
                )}
                
                ${this.createKeyboardRow(
                    [
                        { key: "a", finger: "left-pinky", isHome: true },
                        { key: "s", finger: "left-ring", isHome: true },
                        { key: "d", finger: "left-middle", isHome: true },
                        {
                            key: "f",
                            finger: "left-index",
                            isHome: true,
                            hasBump: true,
                        },
                        { key: "g", finger: "left-index" },
                        { key: "h", finger: "right-index" },
                        {
                            key: "j",
                            finger: "right-index",
                            isHome: true,
                            hasBump: true,
                        },
                        { key: "k", finger: "right-middle", isHome: true },
                        { key: "l", finger: "right-ring", isHome: true },
                        {
                            key: ";",
                            shift: ":",
                            finger: "right-pinky",
                            isHome: true,
                        },
                    ],
                    "home-row"
                )}
                
                ${this.createKeyboardRow(
                    [
                        { key: "z", finger: "left-pinky" },
                        { key: "x", finger: "left-ring" },
                        { key: "c", finger: "left-middle" },
                        { key: "v", finger: "left-index" },
                        { key: "b", finger: "left-index" },
                        { key: "n", finger: "right-index" },
                        { key: "m", finger: "right-index" },
                        { key: ",", shift: "<", finger: "right-middle" },
                        { key: ".", shift: ">", finger: "right-ring" },
                        { key: "/", shift: "?", finger: "right-pinky" },
                    ],
                    "bottom-row"
                )}
                
                <div class="keyboard-row space-row">
                    <div class="key-space" data-key=" " data-finger="thumb">
                        <div class="key-content">Space</div>
                    </div>
                </div>
            </div>
        `;

        this.container.innerHTML = keyboardHTML;
    }

    createKeyboardRow(keys, className) {
        const keysHTML = keys
            .map((keyData) => {
                const fingerClass = this.config.showFingerColors
                    ? `finger-${keyData.finger}`
                    : "";
                const homeClass = keyData.isHome ? "home-key" : "";
                const bumpClass = keyData.hasBump ? "bump-key" : "";

                return `
                <div class="key ${fingerClass} ${homeClass} ${bumpClass}" 
                     data-key="${keyData.key}" 
                     data-finger="${keyData.finger}"
                     ${keyData.shift ? `data-shift="${keyData.shift}"` : ""}>
                    <div class="key-content">
                        ${
                            keyData.shift
                                ? `<span class="key-shift">${keyData.shift}</span>`
                                : ""
                        }
                        <span class="key-main">${keyData.key.toUpperCase()}</span>
                    </div>
                    ${keyData.hasBump ? '<div class="key-bump"></div>' : ""}
                </div>
            `;
            })
            .join("");

        return `<div class="keyboard-row ${className}">${keysHTML}</div>`;
    }

    createHandPosition() {
        const handHTML = `
            <div class="hand-position">
                <div class="hand-instruction">
                    <h4>Proper Hand Position</h4>
                    <p>Place your fingers on the home row keys (ASDF JKL;) and keep them curved</p>
                </div>
                
                <div class="hands-container">
                    <div class="hand left-hand">
                        <div class="hand-label">Left Hand</div>
                        <div class="fingers">
                            <div class="finger left-pinky" data-finger="left-pinky">
                                <div class="finger-tip"></div>
                                <div class="finger-label">Pinky</div>
                                <div class="finger-keys">Q A Z</div>
                            </div>
                            <div class="finger left-ring" data-finger="left-ring">
                                <div class="finger-tip"></div>
                                <div class="finger-label">Ring</div>
                                <div class="finger-keys">W S X</div>
                            </div>
                            <div class="finger left-middle" data-finger="left-middle">
                                <div class="finger-tip"></div>
                                <div class="finger-label">Middle</div>
                                <div class="finger-keys">E D C</div>
                            </div>
                            <div class="finger left-index" data-finger="left-index">
                                <div class="finger-tip"></div>
                                <div class="finger-label">Index</div>
                                <div class="finger-keys">R T F G V B</div>
                            </div>
                        </div>
                        <div class="thumb left-thumb" data-finger="thumb">
                            <div class="finger-tip"></div>
                            <div class="finger-label">Thumb</div>
                            <div class="finger-keys">Space</div>
                        </div>
                    </div>
                    
                    <div class="hand right-hand">
                        <div class="hand-label">Right Hand</div>
                        <div class="fingers">
                            <div class="finger right-index" data-finger="right-index">
                                <div class="finger-tip"></div>
                                <div class="finger-label">Index</div>
                                <div class="finger-keys">Y U H J N M</div>
                            </div>
                            <div class="finger right-middle" data-finger="right-middle">
                                <div class="finger-tip"></div>
                                <div class="finger-label">Middle</div>
                                <div class="finger-keys">I K ,</div>
                            </div>
                            <div class="finger right-ring" data-finger="right-ring">
                                <div class="finger-tip"></div>
                                <div class="finger-label">Ring</div>
                                <div class="finger-keys">O L .</div>
                            </div>
                            <div class="finger right-pinky" data-finger="right-pinky">
                                <div class="finger-tip"></div>
                                <div class="finger-label">Pinky</div>
                                <div class="finger-keys">P ; /</div>
                            </div>
                        </div>
                        <div class="thumb right-thumb" data-finger="thumb">
                            <div class="finger-tip"></div>
                            <div class="finger-label">Thumb</div>
                            <div class="finger-keys">Space</div>
                        </div>
                    </div>
                </div>
                
                <div class="hand-tips">
                    <div class="tip">
                        <i class="fas fa-lightbulb"></i>
                        Keep your fingers curved and relaxed
                    </div>
                    <div class="tip">
                        <i class="fas fa-eye"></i>
                        Try not to look at the keyboard
                    </div>
                    <div class="tip">
                        <i class="fas fa-clock"></i>
                        Practice regularly for muscle memory
                    </div>
                </div>
            </div>
        `;

        this.handContainer.innerHTML = handHTML;
    }

    setupEventListeners() {
        // Physical keyboard events
        document.addEventListener("keydown", (e) => this.handleKeyDown(e));
        document.addEventListener("keyup", (e) => this.handleKeyUp(e));

        // Virtual keyboard clicks
        this.container.addEventListener("click", (e) => {
            const key = e.target.closest(".key");
            if (key) {
                this.simulateKeyPress(key.dataset.key);
            }
        });

        // Hover effects for learning
        this.container.addEventListener("mouseover", (e) => {
            const key = e.target.closest(".key");
            if (key) {
                this.highlightFinger(key.dataset.finger);
            }
        });

        this.container.addEventListener("mouseout", (e) => {
            this.clearFingerHighlight();
        });
    }

    handleKeyDown(e) {
        const key = e.key.toLowerCase();
        this.pressKey(key);
        this.callbacks.onKeyPress(key, this.fingerMapping[key]);
    }

    handleKeyUp(e) {
        const key = e.key.toLowerCase();
        this.releaseKey(key);
        this.callbacks.onKeyRelease(key, this.fingerMapping[key]);
    }

    pressKey(key) {
        if (this.pressedKeys.has(key)) return;

        this.pressedKeys.add(key);

        // Find and highlight the key
        const keyElement = this.container.querySelector(`[data-key="${key}"]`);
        if (keyElement) {
            keyElement.classList.add("key-pressed");

            // Highlight corresponding finger
            this.highlightFinger(keyElement.dataset.finger, true);

            // Play sound if enabled
            if (this.config.playKeySound) {
                this.playKeySound();
            }
        }
    }

    releaseKey(key) {
        this.pressedKeys.delete(key);

        const keyElement = this.container.querySelector(`[data-key="${key}"]`);
        if (keyElement) {
            keyElement.classList.remove("key-pressed");
            this.highlightFinger(keyElement.dataset.finger, false);
        }
    }

    highlightNextKey(key) {
        if (!this.config.highlightNextKey) return;

        // Clear previous highlights
        this.clearKeyHighlights();

        // Highlight the next key to press
        const keyElement = this.container.querySelector(
            `[data-key="${key.toLowerCase()}"]`
        );
        if (keyElement) {
            keyElement.classList.add("key-next");
            this.currentKey = key.toLowerCase();

            // Highlight the corresponding finger
            this.highlightFinger(keyElement.dataset.finger);

            // Show finger movement guidance
            this.showFingerGuidance(keyElement.dataset.finger);
        }
    }

    highlightFinger(finger, pressed = false) {
        if (!this.handContainer) return;

        // Clear previous finger highlights
        this.clearFingerHighlight();

        // Highlight the finger
        const fingerElement = this.handContainer.querySelector(
            `[data-finger="${finger}"]`
        );
        if (fingerElement) {
            fingerElement.classList.add(
                pressed ? "finger-pressed" : "finger-active"
            );

            // Add movement animation for guidance
            if (!pressed) {
                fingerElement.classList.add("finger-guide");
            }
        }

        this.callbacks.onFingerMove(finger, pressed);
    }

    clearFingerHighlight() {
        if (!this.handContainer) return;

        this.handContainer.querySelectorAll(".finger").forEach((finger) => {
            finger.classList.remove(
                "finger-active",
                "finger-pressed",
                "finger-guide"
            );
        });
    }

    clearKeyHighlights() {
        this.container.querySelectorAll(".key").forEach((key) => {
            key.classList.remove("key-next", "key-correct", "key-incorrect");
        });
    }

    showCorrectKey(key) {
        const keyElement = this.container.querySelector(
            `[data-key="${key.toLowerCase()}"]`
        );
        if (keyElement) {
            keyElement.classList.add("key-correct");
            setTimeout(() => keyElement.classList.remove("key-correct"), 500);
        }
    }

    showIncorrectKey(key) {
        const keyElement = this.container.querySelector(
            `[data-key="${key.toLowerCase()}"]`
        );
        if (keyElement) {
            keyElement.classList.add("key-incorrect");
            setTimeout(() => keyElement.classList.remove("key-incorrect"), 500);
        }
    }

    showFingerGuidance(finger) {
        // Show visual guidance for finger movement
        if (!this.handContainer) return;

        const instruction = this.getFingerInstruction(finger);
        this.showInstruction(instruction);
    }

    getFingerInstruction(finger) {
        const instructions = {
            "left-pinky": "Use your left pinky finger",
            "left-ring": "Use your left ring finger",
            "left-middle": "Use your left middle finger",
            "left-index": "Use your left index finger",
            "right-index": "Use your right index finger",
            "right-middle": "Use your right middle finger",
            "right-ring": "Use your right ring finger",
            "right-pinky": "Use your right pinky finger",
            thumb: "Use your thumb for space",
        };

        return instructions[finger] || "Use the correct finger";
    }

    showInstruction(text) {
        const existing = document.querySelector(".keyboard-instruction");
        if (existing) existing.remove();

        const instruction = document.createElement("div");
        instruction.className = "keyboard-instruction";
        instruction.textContent = text;

        this.container.appendChild(instruction);

        setTimeout(() => instruction.remove(), 3000);
    }

    simulateKeyPress(key) {
        this.pressKey(key);
        setTimeout(() => this.releaseKey(key), 150);
    }

    playKeySound() {
        // Create a simple click sound
        const audioContext = new (window.AudioContext ||
            window.webkitAudioContext)();
        const oscillator = audioContext.createOscillator();
        const gainNode = audioContext.createGain();

        oscillator.connect(gainNode);
        gainNode.connect(audioContext.destination);

        oscillator.frequency.setValueAtTime(800, audioContext.currentTime);
        gainNode.gain.setValueAtTime(0.1, audioContext.currentTime);
        gainNode.gain.exponentialRampToValueAtTime(
            0.01,
            audioContext.currentTime + 0.1
        );

        oscillator.start(audioContext.currentTime);
        oscillator.stop(audioContext.currentTime + 0.1);
    }

    // Public methods
    setNextKey(key) {
        this.highlightNextKey(key);
    }

    markKeyCorrect(key) {
        this.showCorrectKey(key);
    }

    markKeyIncorrect(key) {
        this.showIncorrectKey(key);
    }

    resetHighlights() {
        this.clearKeyHighlights();
        this.clearFingerHighlight();
    }

    enableFingerColors(enable = true) {
        this.config.showFingerColors = enable;
        this.container.classList.toggle("finger-colors", enable);
    }

    setKeyLayout(layout) {
        this.config.keyLayout = layout;
        this.createKeyboard(); // Recreate keyboard with new layout
    }
}

// CSS for virtual keyboard
const keyboardCSS = `
.virtual-keyboard {
    background: var(--bg-card);
    border: 2px solid var(--border-light);
    border-radius: var(--border-radius-lg);
    padding: 1.5rem;
    margin: 2rem 0;
    user-select: none;
}

.keyboard-row {
    display: flex;
    justify-content: center;
    gap: 0.25rem;
    margin-bottom: 0.5rem;
}

.key {
    position: relative;
    min-width: 48px;
    height: 48px;
    background: var(--bg-secondary);
    border: 2px solid var(--border-light);
    border-radius: var(--border-radius-sm);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
    font-family: var(--font-display);
    font-weight: 600;
}

.key:hover {
    background: var(--bg-tertiary);
    border-color: var(--border-medium);
    transform: translateY(-1px);
}

.key-content {
    display: flex;
    flex-direction: column;
    align-items: center;
    line-height: 1;
}

.key-shift {
    font-size: 0.7rem;
    color: var(--text-muted);
}

.key-main {
    font-size: 0.9rem;
    color: var(--text-primary);
}

.key-space {
    min-width: 300px;
    height: 48px;
    background: var(--bg-secondary);
    border: 2px solid var(--border-light);
    border-radius: var(--border-radius-sm);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
    font-family: var(--font-display);
    font-weight: 600;
    color: var(--text-primary);
}

.key-space:hover {
    background: var(--bg-tertiary);
    border-color: var(--border-medium);
    transform: translateY(-1px);
}

/* Home row keys */
.home-key {
    border-color: var(--accent-primary);
    background: rgba(59, 130, 246, 0.1);
}

.bump-key::after {
    content: '';
    position: absolute;
    bottom: 8px;
    left: 50%;
    transform: translateX(-50%);
    width: 4px;
    height: 2px;
    background: var(--accent-primary);
    border-radius: 1px;
}

/* Key states */
.key-pressed {
    background: var(--accent-primary);
    color: white;
    transform: translateY(1px);
    box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.2);
}

.key-next {
    background: var(--accent-secondary);
    color: white;
    border-color: var(--accent-secondary);
    animation: key-pulse 1s ease-in-out infinite;
}

.key-correct {
    background: var(--accent-success);
    color: white;
    border-color: var(--accent-success);
    animation: key-success 0.5s ease-out;
}

.key-incorrect {
    background: var(--accent-danger);
    color: white;
    border-color: var(--accent-danger);
    animation: key-error 0.5s ease-out;
}

@keyframes key-pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}

@keyframes key-success {
    0% { transform: scale(1); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
}

@keyframes key-error {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-3px); }
    75% { transform: translateX(3px); }
}

/* Finger colors */
.finger-colors .finger-left-pinky { border-color: #ef4444; }
.finger-colors .finger-left-ring { border-color: #f97316; }
.finger-colors .finger-left-middle { border-color: #eab308; }
.finger-colors .finger-left-index { border-color: #22c55e; }
.finger-colors .finger-right-index { border-color: #22c55e; }
.finger-colors .finger-right-middle { border-color: #3b82f6; }
.finger-colors .finger-right-ring { border-color: #8b5cf6; }
.finger-colors .finger-right-pinky { border-color: #ec4899; }
.finger-colors .finger-thumb { border-color: #6b7280; }

/* Hand position visualization */
.hand-position {
    background: var(--bg-card);
    border: 2px solid var(--border-light);
    border-radius: var(--border-radius-lg);
    padding: 2rem;
    margin: 2rem 0;
}

.hand-instruction {
    text-align: center;
    margin-bottom: 2rem;
}

.hand-instruction h4 {
    font-family: var(--font-display);
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 0.5rem;
}

.hand-instruction p {
    color: var(--text-secondary);
}

.hands-container {
    display: flex;
    justify-content: space-around;
    gap: 3rem;
    margin-bottom: 2rem;
}

.hand {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1rem;
}

.hand-label {
    font-family: var(--font-display);
    font-weight: 600;
    color: var(--text-primary);
    font-size: 1.1rem;
}

.fingers {
    display: flex;
    gap: 0.5rem;
}

.finger {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
    padding: 1rem 0.5rem;
    background: var(--bg-secondary);
    border: 2px solid var(--border-light);
    border-radius: var(--border-radius);
    transition: all 0.3s ease;
    min-width: 60px;
}

.finger-tip {
    width: 20px;
    height: 30px;
    background: var(--text-muted);
    border-radius: 10px 10px 5px 5px;
    transition: all 0.3s ease;
}

.finger-label {
    font-size: 0.8rem;
    font-weight: 600;
    color: var(--text-primary);
}

.finger-keys {
    font-size: 0.7rem;
    color: var(--text-secondary);
    text-align: center;
    line-height: 1.2;
}

.thumb {
    margin-top: 1rem;
    transform: rotate(45deg);
}

.thumb .finger-tip {
    width: 25px;
    height: 20px;
    border-radius: 12px 12px 8px 8px;
}

/* Finger states */
.finger-active .finger-tip {
    background: var(--accent-primary);
    transform: translateY(-2px);
}

.finger-pressed .finger-tip {
    background: var(--accent-secondary);
    transform: translateY(2px);
}

.finger-guide {
    animation: finger-bounce 1s ease-in-out infinite;
}

@keyframes finger-bounce {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-5px); }
}

.hand-tips {
    display: flex;
    justify-content: center;
    gap: 2rem;
    flex-wrap: wrap;
}

.tip {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--text-secondary);
    font-size: 0.9rem;
}

.tip i {
    color: var(--accent-primary);
}

.keyboard-instruction {
    position: absolute;
    top: -40px;
    left: 50%;
    transform: translateX(-50%);
    background: var(--accent-primary);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: var(--border-radius);
    font-size: 0.9rem;
    font-weight: 600;
    white-space: nowrap;
    animation: instruction-slide 3s ease-out;
}

@keyframes instruction-slide {
    0% { opacity: 0; transform: translateX(-50%) translateY(-10px); }
    10%, 90% { opacity: 1; transform: translateX(-50%) translateY(0); }
    100% { opacity: 0; transform: translateX(-50%) translateY(-10px); }
}

/* Responsive Design */
@media (max-width: 768px) {
    .virtual-keyboard {
        padding: 1rem;
    }
    
    .key, .key-space {
        min-width: 36px;
        height: 36px;
        font-size: 0.8rem;
    }
    
    .key-space {
        min-width: 200px;
    }
    
    .hands-container {
        flex-direction: column;
        gap: 2rem;
    }
    
    .finger {
        min-width: 50px;
        padding: 0.75rem 0.25rem;
    }
    
    .finger-tip {
        width: 16px;
        height: 24px;
    }
    
    .hand-tips {
        flex-direction: column;
        gap: 1rem;
    }
}
`;

// Inject CSS
if (!document.querySelector("#keyboard-styles")) {
    const style = document.createElement("style");
    style.id = "keyboard-styles";
    style.textContent = keyboardCSS;
    document.head.appendChild(style);
}

// Export for module systems
if (typeof module !== "undefined" && module.exports) {
    module.exports = VirtualKeyboard;
}

// Global namespace
window.VirtualKeyboard = VirtualKeyboard;
