import "./bootstrap";

// Global SportTyping namespace
window.SportTyping = {
    // Configuration
    config: {
        apiUrl: "/api",
        pusherKey: window.pusherKey || null,
        pusherCluster: window.pusherCluster || "mt1",
        csrf: document.querySelector('meta[name="csrf-token"]')?.content,
        user: window.authUser || null,
        debug: window.appDebug || false,
    },

    // Utilities
    utils: {},

    // Components
    components: {},

    // Services
    services: {},

    // Event system
    events: new EventTarget(),

    // Initialized components
    instances: new Map(),
};

/**
 * Utility Functions
 */
SportTyping.utils = {
    /**
     * Debounce function execution
     */
    debounce(func, wait, immediate = false) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                timeout = null;
                if (!immediate) func(...args);
            };
            const callNow = immediate && !timeout;
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
            if (callNow) func(...args);
        };
    },

    /**
     * Throttle function execution
     */
    throttle(func, limit) {
        let inThrottle;
        return function (...args) {
            if (!inThrottle) {
                func.apply(this, args);
                inThrottle = true;
                setTimeout(() => (inThrottle = false), limit);
            }
        };
    },

    /**
     * Format number with appropriate suffix
     */
    formatNumber(num) {
        if (num >= 1000000) {
            return (num / 1000000).toFixed(1) + "M";
        }
        if (num >= 1000) {
            return (num / 1000).toFixed(1) + "K";
        }
        return num.toString();
    },

    /**
     * Format time duration
     */
    formatDuration(seconds) {
        const hours = Math.floor(seconds / 3600);
        const minutes = Math.floor((seconds % 3600) / 60);
        const secs = Math.floor(seconds % 60);

        if (hours > 0) {
            return `${hours}:${minutes.toString().padStart(2, "0")}:${secs
                .toString()
                .padStart(2, "0")}`;
        }
        return `${minutes}:${secs.toString().padStart(2, "0")}`;
    },

    /**
     * Calculate typing speed (WPM)
     */
    calculateWPM(characters, timeInSeconds) {
        const words = characters / 5; // Standard: 5 characters = 1 word
        const minutes = timeInSeconds / 60;
        return minutes > 0 ? Math.round(words / minutes) : 0;
    },

    /**
     * Calculate typing accuracy
     */
    calculateAccuracy(correct, total) {
        return total > 0 ? Math.round((correct / total) * 100) : 100;
    },

    /**
     * Show notification
     */
    showNotification(message, type = "info", duration = 5000) {
        const notification = document.createElement("div");
        notification.className = `sport-notification notification-${type}`;
        notification.innerHTML = `
            <div class="notification-content">
                <i class="fas fa-${this.getNotificationIcon(type)}"></i>
                <span class="notification-message">${message}</span>
                <button class="notification-close" onclick="this.parentElement.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;

        document.body.appendChild(notification);

        // Animate in
        setTimeout(() => notification.classList.add("show"), 100);

        // Auto remove
        setTimeout(() => {
            notification.classList.remove("show");
            setTimeout(() => notification.remove(), 300);
        }, duration);

        return notification;
    },

    /**
     * Get notification icon based on type
     */
    getNotificationIcon(type) {
        const icons = {
            success: "check-circle",
            error: "exclamation-circle",
            warning: "exclamation-triangle",
            info: "info-circle",
        };
        return icons[type] || "info-circle";
    },

    /**
     * API request helper
     */
    async api(endpoint, options = {}) {
        const defaultOptions = {
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": SportTyping.config.csrf,
                "X-Requested-With": "XMLHttpRequest",
            },
        };

        const finalOptions = {
            ...defaultOptions,
            ...options,
            headers: {
                ...defaultOptions.headers,
                ...options.headers,
            },
        };

        try {
            const response = await fetch(
                `${SportTyping.config.apiUrl}${endpoint}`,
                finalOptions
            );

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const contentType = response.headers.get("content-type");
            if (contentType && contentType.includes("application/json")) {
                return await response.json();
            }

            return await response.text();
        } catch (error) {
            console.error("API request failed:", error);
            throw error;
        }
    },

    /**
     * Local storage helper with expiration
     */
    storage: {
        set(key, value, expirationInMinutes = null) {
            const item = {
                value: value,
                timestamp: Date.now(),
                expiration: expirationInMinutes
                    ? Date.now() + expirationInMinutes * 60 * 1000
                    : null,
            };
            localStorage.setItem(`sporttyping_${key}`, JSON.stringify(item));
        },

        get(key) {
            const itemStr = localStorage.getItem(`sporttyping_${key}`);
            if (!itemStr) return null;

            try {
                const item = JSON.parse(itemStr);

                // Check expiration
                if (item.expiration && Date.now() > item.expiration) {
                    localStorage.removeItem(`sporttyping_${key}`);
                    return null;
                }

                return item.value;
            } catch (e) {
                localStorage.removeItem(`sporttyping_${key}`);
                return null;
            }
        },

        remove(key) {
            localStorage.removeItem(`sporttyping_${key}`);
        },

        clear() {
            Object.keys(localStorage).forEach((key) => {
                if (key.startsWith("sporttyping_")) {
                    localStorage.removeItem(key);
                }
            });
        },
    },

    /**
     * Device detection
     */
    device: {
        isMobile() {
            return (
                window.innerWidth <= 768 ||
                /Android|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(
                    navigator.userAgent
                )
            );
        },

        isTablet() {
            return window.innerWidth <= 1024 && window.innerWidth > 768;
        },

        isDesktop() {
            return window.innerWidth > 1024;
        },

        getType() {
            if (this.isMobile()) return "mobile";
            if (this.isTablet()) return "tablet";
            return "desktop";
        },
    },
};

/**
 * Component Management System
 */
SportTyping.components = {
    /**
     * Register a component
     */
    register(name, componentClass) {
        this[name] = componentClass;
    },

    /**
     * Initialize component on elements
     */
    init(name, selector, options = {}) {
        const elements = document.querySelectorAll(selector);
        const instances = [];

        elements.forEach((element) => {
            if (!element.dataset.sportTypingComponent) {
                const instance = new this[name](element, options);
                element.dataset.sportTypingComponent = name;
                instances.push(instance);
                SportTyping.instances.set(element, instance);
            }
        });

        return instances;
    },

    /**
     * Get component instance from element
     */
    getInstance(element) {
        return SportTyping.instances.get(element);
    },

    /**
     * Destroy component instance
     */
    destroy(element) {
        const instance = SportTyping.instances.get(element);
        if (instance && typeof instance.destroy === "function") {
            instance.destroy();
        }
        SportTyping.instances.delete(element);
        delete element.dataset.sportTypingComponent;
    },
};

/**
 * Services
 */
SportTyping.services = {
    /**
     * Analytics service
     */
    analytics: {
        track(event, data = {}) {
            if (SportTyping.config.debug) {
                console.log("Analytics event:", event, data);
            }

            // Send to analytics service
            SportTyping.utils
                .api("/analytics/track", {
                    method: "POST",
                    body: JSON.stringify({
                        event: event,
                        data: data,
                        timestamp: Date.now(),
                        url: window.location.href,
                        user_agent: navigator.userAgent,
                    }),
                })
                .catch((error) => {
                    console.warn("Analytics tracking failed:", error);
                });
        },

        trackTypingSession(data) {
            this.track("typing_session", {
                wpm: data.wpm,
                accuracy: data.accuracy,
                duration: data.duration,
                text_category: data.category,
                device_type: SportTyping.utils.device.getType(),
            });
        },

        trackCompetitionJoin(competitionId) {
            this.track("competition_join", {
                competition_id: competitionId,
                device_type: SportTyping.utils.device.getType(),
            });
        },

        trackBadgeEarned(badgeId) {
            this.track("badge_earned", {
                badge_id: badgeId,
            });
        },
    },

    /**
     * Real-time communication service
     */
    realtime: {
        connection: null,

        init() {
            if (window.Echo) {
                this.connection = window.Echo;
                return true;
            }
            return false;
        },

        joinChannel(channel, callbacks = {}) {
            if (!this.connection) return null;

            const ch = this.connection.channel(channel);

            Object.keys(callbacks).forEach((event) => {
                ch.listen(event, callbacks[event]);
            });

            return ch;
        },

        leaveChannel(channel) {
            if (this.connection) {
                this.connection.leaveChannel(channel);
            }
        },
    },

    /**
     * Performance monitoring
     */
    performance: {
        metrics: {},

        start(key) {
            this.metrics[key] = {
                start: performance.now(),
                end: null,
                duration: null,
            };
        },

        end(key) {
            if (this.metrics[key]) {
                this.metrics[key].end = performance.now();
                this.metrics[key].duration =
                    this.metrics[key].end - this.metrics[key].start;

                if (SportTyping.config.debug) {
                    console.log(
                        `Performance [${key}]:`,
                        this.metrics[key].duration.toFixed(2) + "ms"
                    );
                }

                return this.metrics[key].duration;
            }
            return null;
        },

        measure(key, fn) {
            this.start(key);
            const result = fn();
            this.end(key);
            return result;
        },
    },
};

/**
 * Auto-initialization system
 */
class AutoInit {
    constructor() {
        this.observers = new Map();
        this.initialized = new Set();
    }

    /**
     * Register auto-init component
     */
    register(selector, initFunction, options = {}) {
        const observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                mutation.addedNodes.forEach((node) => {
                    if (node.nodeType === Node.ELEMENT_NODE) {
                        this.initElement(node, selector, initFunction, options);
                    }
                });
            });
        });

        observer.observe(document.body, {
            childList: true,
            subtree: true,
        });

        this.observers.set(selector, observer);

        // Initialize existing elements
        this.initExisting(selector, initFunction, options);
    }

    initElement(element, selector, initFunction, options) {
        const targets =
            element.matches && element.matches(selector)
                ? [element]
                : element.querySelectorAll
                ? Array.from(element.querySelectorAll(selector))
                : [];

        targets.forEach((target) => {
            const id = this.getElementId(target);
            if (!this.initialized.has(id)) {
                try {
                    initFunction(target, options);
                    this.initialized.add(id);
                } catch (error) {
                    console.error("Auto-init failed for:", selector, error);
                }
            }
        });
    }

    initExisting(selector, initFunction, options) {
        document.querySelectorAll(selector).forEach((element) => {
            const id = this.getElementId(element);
            if (!this.initialized.has(id)) {
                try {
                    initFunction(element, options);
                    this.initialized.add(id);
                } catch (error) {
                    console.error("Auto-init failed for:", selector, error);
                }
            }
        });
    }

    getElementId(element) {
        if (!element.__sportTypingId) {
            element.__sportTypingId =
                "st_" + Math.random().toString(36).substr(2, 9);
        }
        return element.__sportTypingId;
    }

    destroy() {
        this.observers.forEach((observer) => observer.disconnect());
        this.observers.clear();
        this.initialized.clear();
    }
}

// Initialize auto-init system
SportTyping.autoInit = new AutoInit();

/**
 * Global Event Handlers
 */
document.addEventListener("DOMContentLoaded", function () {
    console.log("🏆 SportTyping initialized");

    // Initialize services
    SportTyping.services.realtime.init();

    // Global click handler for data attributes
    document.addEventListener("click", function (e) {
        const target = e.target.closest("[data-action]");
        if (target) {
            const action = target.dataset.action;
            const params = target.dataset.params
                ? JSON.parse(target.dataset.params)
                : {};

            // Handle common actions
            switch (action) {
                case "copy-text":
                    copyToClipboard(target.dataset.text || target.textContent);
                    break;
                case "toggle-class":
                    toggleClass(target.dataset.target, target.dataset.class);
                    break;
                case "scroll-to":
                    scrollToElement(target.dataset.target);
                    break;
            }

            // Emit custom event
            SportTyping.events.dispatchEvent(
                new CustomEvent("action", {
                    detail: { action, params, element: target },
                })
            );
        }
    });

    // Initialize tooltips
    initTooltips();

    // Initialize modals
    initModals();

    // Initialize auto-growing textareas
    initAutoGrowTextareas();

    // Initialize scroll animations
    initScrollAnimations();

    // Initialize theme system
    initThemeSystem();

    // Track page view
    SportTyping.services.analytics.track("page_view", {
        path: window.location.pathname,
        title: document.title,
    });
});

/**
 * Helper Functions
 */
function copyToClipboard(text) {
    navigator.clipboard
        .writeText(text)
        .then(() => {
            SportTyping.utils.showNotification(
                "Copied to clipboard!",
                "success",
                2000
            );
        })
        .catch(() => {
            SportTyping.utils.showNotification("Failed to copy", "error", 2000);
        });
}

function toggleClass(selector, className) {
    const elements = document.querySelectorAll(selector);
    elements.forEach((el) => el.classList.toggle(className));
}

function scrollToElement(selector) {
    const element = document.querySelector(selector);
    if (element) {
        element.scrollIntoView({ behavior: "smooth" });
    }
}

function initTooltips() {
    const tooltips = document.querySelectorAll("[data-tooltip]");
    tooltips.forEach((element) => {
        if (window.bootstrap && window.bootstrap.Tooltip) {
            new window.bootstrap.Tooltip(element, {
                title: element.dataset.tooltip,
                placement: element.dataset.tooltipPlacement || "top",
            });
        }
    });
}

function initModals() {
    // Auto-open modals with data-auto-show
    const autoModals = document.querySelectorAll("[data-auto-show]");
    autoModals.forEach((modal) => {
        setTimeout(() => {
            if (window.bootstrap && window.bootstrap.Modal) {
                const modalInstance = new window.bootstrap.Modal(modal);
                modalInstance.show();
            }
        }, parseInt(modal.dataset.autoShow) || 0);
    });
}

function initAutoGrowTextareas() {
    const textareas = document.querySelectorAll("textarea[data-auto-grow]");
    textareas.forEach((textarea) => {
        textarea.addEventListener("input", function () {
            this.style.height = "auto";
            this.style.height = this.scrollHeight + "px";
        });

        // Initial resize
        textarea.dispatchEvent(new Event("input"));
    });
}

function initScrollAnimations() {
    const animatedElements = document.querySelectorAll("[data-animate]");

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    const animation = entry.target.dataset.animate;
                    entry.target.classList.add(`animate-${animation}`);
                    observer.unobserve(entry.target);
                }
            });
        },
        { threshold: 0.1 }
    );

    animatedElements.forEach((el) => observer.observe(el));
}

function initThemeSystem() {
    const themeToggle = document.querySelector("[data-theme-toggle]");
    if (themeToggle) {
        themeToggle.addEventListener("click", toggleTheme);
    }

    // Apply saved theme
    const savedTheme = SportTyping.utils.storage.get("theme");
    if (savedTheme) {
        document.documentElement.setAttribute("data-theme", savedTheme);
    }
}

function toggleTheme() {
    const current = document.documentElement.getAttribute("data-theme");
    const newTheme = current === "dark" ? "light" : "dark";

    document.documentElement.setAttribute("data-theme", newTheme);
    SportTyping.utils.storage.set("theme", newTheme);

    SportTyping.services.analytics.track("theme_change", { theme: newTheme });
}

/**
 * Error Handling
 */
window.addEventListener("error", function (e) {
    console.error("Global error:", e.error);

    if (SportTyping.config.debug) {
        SportTyping.utils.showNotification(
            "An error occurred. Check console for details.",
            "error"
        );
    }
});

window.addEventListener("unhandledrejection", function (e) {
    console.error("Unhandled promise rejection:", e.reason);

    if (SportTyping.config.debug) {
        SportTyping.utils.showNotification(
            "A promise rejection occurred. Check console for details.",
            "error"
        );
    }
});

/**
 * Auto-initialize components based on data attributes
 */
SportTyping.autoInit.register("[data-typing-test]", (element) => {
    if (window.TypingTest) {
        new window.TypingTest({
            textElement: element.querySelector("[data-typing-text]"),
            inputElement: element.querySelector("[data-typing-input]"),
            statsElement: element.querySelector("[data-typing-stats]"),
            ...JSON.parse(element.dataset.typingTest || "{}"),
        });
    }
});

SportTyping.autoInit.register("[data-virtual-keyboard]", (element) => {
    if (window.VirtualKeyboard) {
        new window.VirtualKeyboard({
            container: element,
            ...JSON.parse(element.dataset.virtualKeyboard || "{}"),
        });
    }
});

SportTyping.autoInit.register("[data-competition-race]", (element) => {
    if (window.CompetitionRace) {
        new window.CompetitionRace({
            competitionId: element.dataset.competitionId,
            ...JSON.parse(element.dataset.competitionRace || "{}"),
        });
    }
});

SportTyping.autoInit.register("[data-dashboard-analytics]", (element) => {
    if (window.DashboardAnalytics) {
        new window.DashboardAnalytics({
            ...JSON.parse(element.dataset.dashboardAnalytics || "{}"),
        });
    }
});

/**
 * Performance monitoring for page load
 */
window.addEventListener("load", function () {
    SportTyping.services.performance.start("page_load");

    setTimeout(() => {
        const loadTime = SportTyping.services.performance.end("page_load");
        if (loadTime) {
            SportTyping.services.analytics.track("page_load_time", {
                duration: loadTime,
                path: window.location.pathname,
            });
        }
    }, 0);
});

// Export for global access
window.SportTyping = SportTyping;
