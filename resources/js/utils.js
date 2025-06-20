/**
 * SportTyping - Utility Helper Functions
 * Additional utilities for enhanced functionality
 */

// Enhanced notification system with better UX
class NotificationManager {
    constructor() {
        this.container = null;
        this.notifications = new Map();
        this.sounds = new Map();
        this.soundEnabled =
            localStorage.getItem("notification-sound") !== "false";

        this.init();
    }

    init() {
        this.createContainer();
        this.loadSounds();
        this.createSoundToggle();
    }

    createContainer() {
        if (document.querySelector(".sport-notifications-container")) return;

        this.container = document.createElement("div");
        this.container.className = "sport-notifications-container";
        document.body.appendChild(this.container);
    }

    loadSounds() {
        const soundFiles = {
            success:
                "data:audio/wav;base64,UklGRvIAAABXQVZFZm10IAAAAAAAAAEAAAAAAAAAAAAAAAAAAAAAAAASW5mb1AAAAAAAAAAAAAAAAAAAAA...",
            error: "data:audio/wav;base64,UklGRvIAAABXQVZFZm10IAAAAAAAAAEAAAAAAAAAAAAAAAAAAAAAAAASW5mb1AAAAAAAAAAAAAAAAAAAAA...",
            info: "data:audio/wav;base64,UklGRvIAAABXQVZFZm10IAAAAAAAAAEAAAAAAAAAAAAAAAAAAAAAAAASW5mb1AAAAAAAAAAAAAAAAAAAAA...",
            warning:
                "data:audio/wav;base64,UklGRvIAAABXQVZFZm10IAAAAAAAAAEAAAAAAAAAAAAAAAAAAAAAAAASW5mb1AAAAAAAAAAAAAAAAAAAAA...",
        };

        Object.entries(soundFiles).forEach(([type, data]) => {
            const audio = new Audio(data);
            audio.volume = 0.3;
            this.sounds.set(type, audio);
        });
    }

    createSoundToggle() {
        const toggle = document.createElement("button");
        toggle.className = `notification-sound-toggle ${
            this.soundEnabled ? "" : "muted"
        }`;
        toggle.innerHTML = `<i class="fas fa-${
            this.soundEnabled ? "volume-up" : "volume-mute"
        }"></i>`;
        toggle.title = `${
            this.soundEnabled ? "Disable" : "Enable"
        } notification sounds`;

        toggle.addEventListener("click", () => {
            this.soundEnabled = !this.soundEnabled;
            localStorage.setItem("notification-sound", this.soundEnabled);

            toggle.className = `notification-sound-toggle ${
                this.soundEnabled ? "" : "muted"
            }`;
            toggle.innerHTML = `<i class="fas fa-${
                this.soundEnabled ? "volume-up" : "volume-mute"
            }"></i>`;
            toggle.title = `${
                this.soundEnabled ? "Disable" : "Enable"
            } notification sounds`;
        });

        document.body.appendChild(toggle);
    }

    show(message, type = "info", options = {}) {
        const id =
            "notif_" +
            Date.now() +
            "_" +
            Math.random().toString(36).substr(2, 9);
        const duration = options.duration || 5000;
        const actions = options.actions || [];
        const persistent = options.persistent || false;

        const notification = this.createNotification(
            id,
            message,
            type,
            actions,
            persistent
        );
        this.container.appendChild(notification);
        this.notifications.set(id, notification);

        // Animate in
        requestAnimationFrame(() => {
            notification.classList.add("show");
        });

        // Play sound
        if (this.soundEnabled && this.sounds.has(type)) {
            this.sounds
                .get(type)
                .play()
                .catch(() => {});
        }

        // Auto remove if not persistent
        if (!persistent && duration > 0) {
            setTimeout(() => {
                this.remove(id);
            }, duration);
        }

        return id;
    }

    createNotification(id, message, type, actions, persistent) {
        const notification = document.createElement("div");
        notification.className = `sport-notification notification-${type}`;
        notification.setAttribute("data-id", id);
        notification.setAttribute("role", "alert");
        notification.setAttribute(
            "aria-live",
            type === "error" ? "assertive" : "polite"
        );

        const icon = this.getIcon(type);

        let actionsHtml = "";
        if (actions.length > 0) {
            actionsHtml = `
                <div class="notification-actions">
                    ${actions
                        .map(
                            (action) => `
                        <button class="notification-action ${
                            action.style || "secondary"
                        }" 
                                onclick="${action.handler}">
                            ${action.text}
                        </button>
                    `
                        )
                        .join("")}
                </div>
            `;
        }

        notification.innerHTML = `
            <div class="notification-content">
                <i class="fas fa-${icon}"></i>
                <span class="notification-message">${message}</span>
                <button class="notification-close" onclick="SportTyping.notifications.remove('${id}')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            ${actionsHtml}
            ${
                !persistent
                    ? '<div class="notification-progress"><div class="notification-progress-bar"></div></div>'
                    : ""
            }
        `;

        return notification;
    }

    getIcon(type) {
        const icons = {
            success: "check-circle",
            error: "exclamation-circle",
            warning: "exclamation-triangle",
            info: "info-circle",
            badge: "medal",
            level: "trophy",
        };
        return icons[type] || "info-circle";
    }

    remove(id) {
        const notification = this.notifications.get(id);
        if (!notification) return;

        notification.classList.remove("show");

        setTimeout(() => {
            if (notification.parentElement) {
                notification.parentElement.removeChild(notification);
            }
            this.notifications.delete(id);
        }, 400);
    }

    removeAll() {
        this.notifications.forEach((notification, id) => {
            this.remove(id);
        });
    }

    // Special notification types
    showBadgeEarned(badgeName, badgeIcon) {
        return this.show(
            `Congratulations! You earned the "${badgeName}" badge!`,
            "badge",
            {
                duration: 8000,
                actions: [
                    {
                        text: "View Badge",
                        style: "primary",
                        handler: 'window.location.href = "/badges"',
                    },
                ],
            }
        );
    }

    showLevelUp(newLevel, newLeague) {
        return this.show(
            `Level Up! You've reached ${newLeague} league!`,
            "level",
            {
                duration: 10000,
                actions: [
                    {
                        text: "View Profile",
                        style: "primary",
                        handler: 'window.location.href = "/profile"',
                    },
                ],
            }
        );
    }

    showCompetitionInvite(competitionName, inviterName) {
        return this.show(
            `${inviterName} invited you to join "${competitionName}"`,
            "info",
            {
                persistent: true,
                actions: [
                    {
                        text: "Accept",
                        style: "primary",
                        handler: "SportTyping.competitions.acceptInvite()",
                    },
                    {
                        text: "Decline",
                        style: "secondary",
                        handler: "SportTyping.competitions.declineInvite()",
                    },
                ],
            }
        );
    }
}

// Performance monitoring utilities
class PerformanceMonitor {
    constructor() {
        this.metrics = new Map();
        this.observers = new Map();
        this.enabled = SportTyping.config.debug;
    }

    startMeasure(name, description = "") {
        if (!this.enabled) return;

        this.metrics.set(name, {
            start: performance.now(),
            description,
        });

        if (performance.mark) {
            performance.mark(`${name}-start`);
        }
    }

    endMeasure(name) {
        if (!this.enabled) return;

        const metric = this.metrics.get(name);
        if (!metric) return;

        const duration = performance.now() - metric.start;

        if (performance.mark && performance.measure) {
            performance.mark(`${name}-end`);
            performance.measure(name, `${name}-start`, `${name}-end`);
        }

        console.log(
            `⚡ ${name}: ${duration.toFixed(2)}ms ${
                metric.description ? `(${metric.description})` : ""
            }`
        );

        // Send to analytics if duration is significant
        if (duration > 100) {
            SportTyping.services.analytics.track("performance_metric", {
                name,
                duration,
                description: metric.description,
            });
        }

        this.metrics.delete(name);
        return duration;
    }

    observeElement(element, callback) {
        if (!window.IntersectionObserver) return;

        const observer = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        callback(entry.target);
                    }
                });
            },
            { threshold: 0.1 }
        );

        observer.observe(element);
        this.observers.set(element, observer);
    }

    measureTypingPerformance(startTime, endTime, charactersTyped) {
        const duration = endTime - startTime;
        const cps = charactersTyped / (duration / 1000); // Characters per second

        console.log(
            `⌨️ Typing Performance: ${cps.toFixed(2)} CPS, ${duration.toFixed(
                2
            )}ms total`
        );

        return {
            duration,
            charactersTyped,
            cps,
            wpm: (cps * 60) / 5, // Approximate WPM
        };
    }
}

// Enhanced error handling and reporting
class ErrorHandler {
    constructor() {
        this.errorQueue = [];
        this.maxErrors = 50;
        this.setupGlobalHandlers();
    }

    setupGlobalHandlers() {
        window.addEventListener("error", (e) => {
            this.handleError({
                type: "javascript",
                message: e.message,
                filename: e.filename,
                lineno: e.lineno,
                colno: e.colno,
                stack: e.error?.stack,
                timestamp: Date.now(),
            });
        });

        window.addEventListener("unhandledrejection", (e) => {
            this.handleError({
                type: "promise",
                message: e.reason?.message || e.reason,
                stack: e.reason?.stack,
                timestamp: Date.now(),
            });
        });
    }

    handleError(error) {
        // Add to queue
        this.errorQueue.unshift(error);
        if (this.errorQueue.length > this.maxErrors) {
            this.errorQueue.pop();
        }

        // Log to console in development
        if (SportTyping.config.debug) {
            console.error("🚨 SportTyping Error:", error);
        }

        // Report to analytics service
        SportTyping.services.analytics.track("error", {
            type: error.type,
            message: error.message,
            filename: error.filename,
            stack: error.stack?.substring(0, 1000), // Limit stack trace size
        });

        // Show user-friendly notification for critical errors
        if (this.isCriticalError(error)) {
            SportTyping.notifications.show(
                "Something went wrong. Please refresh the page if the problem persists.",
                "error",
                { duration: 8000 }
            );
        }
    }

    isCriticalError(error) {
        const criticalKeywords = [
            "network",
            "failed to fetch",
            "authentication",
            "permission",
        ];
        const message = error.message?.toLowerCase() || "";
        return criticalKeywords.some((keyword) => message.includes(keyword));
    }

    getErrorReport() {
        return {
            errors: this.errorQueue.slice(0, 10), // Last 10 errors
            userAgent: navigator.userAgent,
            url: window.location.href,
            timestamp: Date.now(),
        };
    }

    clearErrors() {
        this.errorQueue = [];
    }
}

// Accessibility utilities
class AccessibilityManager {
    constructor() {
        this.announcements = document.createElement("div");
        this.announcements.setAttribute("aria-live", "polite");
        this.announcements.setAttribute("aria-atomic", "true");
        this.announcements.className = "sr-only";
        document.body.appendChild(this.announcements);

        this.setupKeyboardNavigation();
        this.setupFocusManagement();
    }

    announce(message, priority = "polite") {
        this.announcements.setAttribute("aria-live", priority);
        this.announcements.textContent = message;

        // Clear after announcement
        setTimeout(() => {
            this.announcements.textContent = "";
        }, 1000);
    }

    setupKeyboardNavigation() {
        // Enhanced keyboard navigation for typing interface
        document.addEventListener("keydown", (e) => {
            // Escape key to focus main input
            if (e.key === "Escape") {
                const mainInput = document.querySelector(
                    "#typing-input, .typing-input"
                );
                if (mainInput) {
                    mainInput.focus();
                    e.preventDefault();
                }
            }

            // Ctrl+/ for keyboard shortcuts help
            if (e.ctrlKey && e.key === "/") {
                this.showKeyboardShortcuts();
                e.preventDefault();
            }
        });
    }

    setupFocusManagement() {
        // Track focus for better accessibility
        let lastFocusedElement = null;

        document.addEventListener("focusin", (e) => {
            lastFocusedElement = e.target;
        });

        // Restore focus when modals close
        document.addEventListener("hidden.bs.modal", () => {
            if (lastFocusedElement && lastFocusedElement !== document.body) {
                setTimeout(() => {
                    lastFocusedElement.focus();
                }, 100);
            }
        });
    }

    showKeyboardShortcuts() {
        const shortcuts = [
            { key: "Esc", description: "Focus typing input" },
            { key: "Ctrl + /", description: "Show keyboard shortcuts" },
            { key: "Ctrl + Enter", description: "Submit typing test" },
            { key: "Ctrl + R", description: "Reset typing test" },
        ];

        const message = shortcuts
            .map((s) => `${s.key}: ${s.description}`)
            .join("\n");
        alert(`Keyboard Shortcuts:\n\n${message}`);
    }

    setPageTitle(title) {
        document.title = title;
        this.announce(`Page changed to ${title}`);
    }

    announceTypingStats(wpm, accuracy) {
        this.announce(
            `Current speed: ${wmp} words per minute, accuracy: ${accuracy} percent`
        );
    }
}

// Offline support utilities
class OfflineManager {
    constructor() {
        this.isOnline = navigator.onLine;
        this.offlineQueue = [];

        this.setupEventListeners();
        this.setupServiceWorker();
    }

    setupEventListeners() {
        window.addEventListener("online", () => {
            this.isOnline = true;
            this.handleOnline();
        });

        window.addEventListener("offline", () => {
            this.isOnline = false;
            this.handleOffline();
        });
    }

    setupServiceWorker() {
        if ("serviceWorker" in navigator) {
            navigator.serviceWorker
                .register("/sw.js")
                .then((registration) => {
                    console.log("Service Worker registered:", registration);
                })
                .catch((error) => {
                    console.log("Service Worker registration failed:", error);
                });
        }
    }

    handleOnline() {
        SportTyping.notifications.show("Connection restored", "success", {
            duration: 3000,
        });
        this.processOfflineQueue();
    }

    handleOffline() {
        SportTyping.notifications.show(
            "You are now offline. Some features may be limited.",
            "warning",
            { duration: 5000 }
        );
    }

    queueRequest(request) {
        this.offlineQueue.push(request);
    }

    async processOfflineQueue() {
        while (this.offlineQueue.length > 0) {
            const request = this.offlineQueue.shift();
            try {
                await fetch(request.url, request.options);
            } catch (error) {
                console.warn("Failed to process offline request:", error);
                // Re-queue if still failing
                this.offlineQueue.unshift(request);
                break;
            }
        }
    }
}

// Initialize utility managers
document.addEventListener("DOMContentLoaded", () => {
    // Initialize all utility managers
    SportTyping.notifications = new NotificationManager();
    SportTyping.performance = new PerformanceMonitor();
    SportTyping.errorHandler = new ErrorHandler();
    SportTyping.accessibility = new AccessibilityManager();
    SportTyping.offline = new OfflineManager();

    // Override the default utils.showNotification to use the enhanced version
    SportTyping.utils.showNotification = (message, type, duration) => {
        return SportTyping.notifications.show(message, type, { duration });
    };

    console.log("🚀 SportTyping utilities initialized");
});

// Export utilities for global access
window.NotificationManager = NotificationManager;
window.PerformanceMonitor = PerformanceMonitor;
window.ErrorHandler = ErrorHandler;
window.AccessibilityManager = AccessibilityManager;
window.OfflineManager = OfflineManager;
