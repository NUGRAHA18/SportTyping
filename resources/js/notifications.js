/**
 * SportTyping Notification System
 * Modern notification management for competitive typing platform
 */

class SportNotificationSystem {
    constructor() {
        this.container = null;
        this.notifications = new Map();
        this.maxNotifications = 5;
        this.defaultDuration = 5000;
        this.soundEnabled = true;

        this.init();
    }

    init() {
        this.createContainer();
        this.bindEvents();
        this.loadSettings();
    }

    createContainer() {
        this.container = document.createElement("div");
        this.container.className = "sport-notifications-container";
        this.container.setAttribute("aria-live", "polite");
        this.container.setAttribute("aria-label", "Notifications");
        document.body.appendChild(this.container);
    }

    bindEvents() {
        // Listen for Laravel flash messages
        document.addEventListener("DOMContentLoaded", () => {
            this.checkFlashMessages();
        });

        // Listen for custom notification events
        document.addEventListener("sport:notification", (e) => {
            this.show(e.detail);
        });

        // Listen for badge earned events
        document.addEventListener("sport:badge-earned", (e) => {
            this.showBadgeEarned(e.detail);
        });

        // Listen for level up events
        document.addEventListener("sport:level-up", (e) => {
            this.showLevelUp(e.detail);
        });
    }

    checkFlashMessages() {
        // Check for Laravel flash messages in meta tags or data attributes
        const flashTypes = ["success", "error", "warning", "info"];

        flashTypes.forEach((type) => {
            const message = document.querySelector(
                `meta[name="flash-${type}"]`
            )?.content;
            if (message) {
                this.show({
                    type: type,
                    message: message,
                    duration: type === "error" ? 8000 : this.defaultDuration,
                });
            }
        });
    }

    show(options = {}) {
        const config = {
            type: "info",
            message: "",
            title: null,
            duration: this.defaultDuration,
            actions: null,
            icon: null,
            sound: true,
            persistent: false,
            ...options,
        };

        if (!config.message) return null;

        const notification = this.createNotification(config);
        const id = this.addNotification(notification, config);

        // Play sound if enabled
        if (this.soundEnabled && config.sound) {
            this.playNotificationSound(config.type);
        }

        // Auto dismiss if not persistent
        if (!config.persistent && config.duration > 0) {
            setTimeout(() => {
                this.remove(id);
            }, config.duration);
        }

        return id;
    }

    createNotification(config) {
        const notification = document.createElement("div");
        notification.className = `sport-notification notification-${config.type}`;
        notification.setAttribute(
            "role",
            config.type === "error" ? "alert" : "status"
        );

        const icon = config.icon || this.getDefaultIcon(config.type);

        notification.innerHTML = `
            <div class="notification-content">
                <i class="${icon}"></i>
                <div class="notification-message">
                    ${
                        config.title
                            ? `<strong>${config.title}</strong><br>`
                            : ""
                    }
                    ${config.message}
                </div>
                <button class="notification-close" aria-label="Close notification">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            ${config.actions ? this.createActions(config.actions) : ""}
            ${
                !config.persistent && config.duration > 0
                    ? this.createProgressBar(config.type)
                    : ""
            }
        `;

        // Bind close button
        const closeBtn = notification.querySelector(".notification-close");
        closeBtn.addEventListener("click", () => {
            const id = notification.dataset.notificationId;
            this.remove(id);
        });

        // Bind action buttons
        if (config.actions) {
            config.actions.forEach((action, index) => {
                const btn = notification.querySelector(
                    `[data-action-index="${index}"]`
                );
                if (btn && action.handler) {
                    btn.addEventListener("click", action.handler);
                }
            });
        }

        return notification;
    }

    createActions(actions) {
        if (!actions || !Array.isArray(actions)) return "";

        const actionsHtml = actions
            .map(
                (action, index) => `
            <button class="notification-action ${action.type || "secondary"}" 
                    data-action-index="${index}">
                ${action.icon ? `<i class="${action.icon}"></i>` : ""}
                ${action.text}
            </button>
        `
            )
            .join("");

        return `<div class="notification-actions">${actionsHtml}</div>`;
    }

    createProgressBar(type) {
        return `
            <div class="notification-progress">
                <div class="notification-progress-bar"></div>
            </div>
        `;
    }

    addNotification(notification, config) {
        const id =
            "notification_" +
            Date.now() +
            "_" +
            Math.random().toString(36).substr(2, 9);
        notification.dataset.notificationId = id;

        this.notifications.set(id, { element: notification, config });

        // Remove oldest if we exceed max
        if (this.notifications.size > this.maxNotifications) {
            const oldestId = this.notifications.keys().next().value;
            this.remove(oldestId);
        }

        this.container.appendChild(notification);

        // Trigger animation
        requestAnimationFrame(() => {
            notification.classList.add("show");
        });

        return id;
    }

    remove(id) {
        const notificationData = this.notifications.get(id);
        if (!notificationData) return;

        const notification = notificationData.element;
        notification.classList.remove("show");

        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
            this.notifications.delete(id);
        }, 400);
    }

    removeAll() {
        this.notifications.forEach((_, id) => {
            this.remove(id);
        });
    }

    showBadgeEarned(badgeData) {
        return this.show({
            type: "badge-earned",
            title: "Badge Earned!",
            message: `You've earned the "${badgeData.name}" badge!`,
            icon: "fas fa-medal",
            duration: 8000,
            actions: [
                {
                    text: "View Badges",
                    type: "primary",
                    icon: "fas fa-trophy",
                    handler: () => (window.location.href = "/badges"),
                },
            ],
        });
    }

    showLevelUp(levelData) {
        return this.show({
            type: "level-up",
            title: "Level Up!",
            message: `Congratulations! You've reached ${levelData.league} league!`,
            icon: "fas fa-crown",
            duration: 10000,
            actions: [
                {
                    text: "View Profile",
                    type: "primary",
                    icon: "fas fa-user",
                    handler: () => (window.location.href = "/profile"),
                },
            ],
        });
    }

    showSuccess(message, options = {}) {
        return this.show({ type: "success", message, ...options });
    }

    showError(message, options = {}) {
        return this.show({
            type: "error",
            message,
            duration: 8000,
            ...options,
        });
    }

    showWarning(message, options = {}) {
        return this.show({ type: "warning", message, ...options });
    }

    showInfo(message, options = {}) {
        return this.show({ type: "info", message, ...options });
    }

    getDefaultIcon(type) {
        const icons = {
            success: "fas fa-check-circle",
            error: "fas fa-exclamation-circle",
            warning: "fas fa-exclamation-triangle",
            info: "fas fa-info-circle",
        };
        return icons[type] || icons.info;
    }

    playNotificationSound(type) {
        try {
            const audioContext = new (window.AudioContext ||
                window.webkitAudioContext)();
            const oscillator = audioContext.createOscillator();
            const gainNode = audioContext.createGain();

            oscillator.connect(gainNode);
            gainNode.connect(audioContext.destination);

            // Different frequencies for different types
            const frequencies = {
                success: 523.25, // C5
                error: 146.83, // D3
                warning: 293.66, // D4
                info: 440, // A4
            };

            oscillator.frequency.setValueAtTime(
                frequencies[type] || frequencies.info,
                audioContext.currentTime
            );
            oscillator.type = "sine";

            gainNode.gain.setValueAtTime(0.1, audioContext.currentTime);
            gainNode.gain.exponentialRampToValueAtTime(
                0.01,
                audioContext.currentTime + 0.3
            );

            oscillator.start(audioContext.currentTime);
            oscillator.stop(audioContext.currentTime + 0.3);
        } catch (e) {
            // Fallback or ignore if Web Audio API not supported
            console.warn("Could not play notification sound:", e);
        }
    }

    loadSettings() {
        const soundSetting = localStorage.getItem("sport-notifications-sound");
        if (soundSetting !== null) {
            this.soundEnabled = soundSetting === "true";
        }
    }

    toggleSound() {
        this.soundEnabled = !this.soundEnabled;
        localStorage.setItem(
            "sport-notifications-sound",
            this.soundEnabled.toString()
        );

        this.showInfo(
            this.soundEnabled
                ? "Notification sounds enabled"
                : "Notification sounds disabled",
            { duration: 2000 }
        );
    }
}

// Initialize the notification system
const sportNotifications = new SportNotificationSystem();

// Global convenience functions
window.showNotification = (message, type = "info", options = {}) => {
    return sportNotifications.show({ message, type, ...options });
};

window.showSuccess = (message, options = {}) =>
    sportNotifications.showSuccess(message, options);
window.showError = (message, options = {}) =>
    sportNotifications.showError(message, options);
window.showWarning = (message, options = {}) =>
    sportNotifications.showWarning(message, options);
window.showInfo = (message, options = {}) =>
    sportNotifications.showInfo(message, options);

// Laravel integration helpers
window.laravelNotification = {
    success: (message) => showSuccess(message),
    error: (message) => showError(message),
    warning: (message) => showWarning(message),
    info: (message) => showInfo(message),
};

// Export for module systems
if (typeof module !== "undefined" && module.exports) {
    module.exports = SportNotificationSystem;
}

// Make available globally
window.SportNotificationSystem = SportNotificationSystem;
window.sportNotifications = sportNotifications;
