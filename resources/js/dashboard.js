class DashboardAnalytics {
    constructor(options = {}) {
        // Configuration
        this.config = {
            chartElements: {
                wpmTrend: options.wpmTrendChart || "#wpm-trend-chart",
                accuracyTrend:
                    options.accuracyTrendChart || "#accuracy-trend-chart",
                activityHeatmap: options.activityHeatmap || "#activity-heatmap",
                performanceRadar:
                    options.performanceRadar || "#performance-radar",
                progressChart: options.progressChart || "#progress-chart",
                categoryBreakdown:
                    options.categoryBreakdown || "#category-breakdown",
            },
            apiEndpoint: options.apiEndpoint || "/api/user",
            refreshInterval: options.refreshInterval || 300000, // 5 minutes
            animationDuration: options.animationDuration || 1000,
        };

        // Chart instances
        this.charts = {};

        // Data cache
        this.dataCache = {
            wpmHistory: [],
            accuracyHistory: [],
            activityData: [],
            performanceMetrics: {},
            categoryStats: [],
        };

        this.init();
    }

    async init() {
        await this.loadDashboardData();
        this.initializeCharts();
        this.setupEventListeners();
        this.startAutoRefresh();
    }

    async loadDashboardData() {
        try {
            const response = await fetch(
                `${this.config.apiEndpoint}/dashboard`,
                {
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector(
                            'meta[name="csrf-token"]'
                        ).content,
                    },
                }
            );

            if (response.ok) {
                const data = await response.json();
                this.updateDataCache(data);
            }
        } catch (error) {
            console.error("Failed to load dashboard data:", error);
        }
    }

    updateDataCache(data) {
        this.dataCache = {
            wmpHistory: data.wpm_history || [],
            accuracyHistory: data.accuracy_history || [],
            activityData: data.activity_data || [],
            performanceMetrics: data.performance_metrics || {},
            categoryStats: data.category_stats || [],
            recentSessions: data.recent_sessions || [],
            goalProgress: data.goal_progress || {},
        };
    }

    initializeCharts() {
        this.createWPMTrendChart();
        this.createAccuracyTrendChart();
        this.createActivityHeatmap();
        this.createPerformanceRadar();
        this.createProgressChart();
        this.createCategoryBreakdown();
    }

    createWPMTrendChart() {
        const canvas = document.querySelector(
            this.config.chartElements.wmpTrend
        );
        if (!canvas) return;

        const ctx = canvas.getContext("2d");

        this.charts.wmpTrend = new Chart(ctx, {
            type: "line",
            data: {
                labels: this.dataCache.wmpHistory.map((item) =>
                    new Date(item.date).toLocaleDateString("id-ID", {
                        month: "short",
                        day: "numeric",
                    })
                ),
                datasets: [
                    {
                        label: "WPM",
                        data: this.dataCache.wmpHistory.map((item) => item.wpm),
                        borderColor: "rgb(59, 130, 246)",
                        backgroundColor: "rgba(59, 130, 246, 0.1)",
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: "rgb(59, 130, 246)",
                        pointBorderColor: "#ffffff",
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 8,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false,
                    },
                    tooltip: {
                        mode: "index",
                        intersect: false,
                        backgroundColor: "rgba(0, 0, 0, 0.8)",
                        titleColor: "#ffffff",
                        bodyColor: "#ffffff",
                        borderColor: "rgb(59, 130, 246)",
                        borderWidth: 1,
                        cornerRadius: 8,
                        displayColors: false,
                        callbacks: {
                            title: function (tooltipItems) {
                                const item = tooltipItems[0];
                                const date = new Date(
                                    this.dataCache.wmpHistory[
                                        item.dataIndex
                                    ].date
                                );
                                return date.toLocaleDateString("id-ID", {
                                    weekday: "long",
                                    year: "numeric",
                                    month: "long",
                                    day: "numeric",
                                });
                            }.bind(this),
                            label: function (context) {
                                return `Speed: ${context.parsed.y} WPM`;
                            },
                        },
                    },
                },
                scales: {
                    x: {
                        grid: {
                            color: "rgba(0, 0, 0, 0.1)",
                            drawBorder: false,
                        },
                        ticks: {
                            color: "#6b7280",
                        },
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: "rgba(0, 0, 0, 0.1)",
                            drawBorder: false,
                        },
                        ticks: {
                            color: "#6b7280",
                            callback: function (value) {
                                return value + " WPM";
                            },
                        },
                    },
                },
                interaction: {
                    intersect: false,
                    mode: "index",
                },
                animation: {
                    duration: this.config.animationDuration,
                    easing: "easeInOutQuart",
                },
            },
        });
    }

    createAccuracyTrendChart() {
        const canvas = document.querySelector(
            this.config.chartElements.accuracyTrend
        );
        if (!canvas) return;

        const ctx = canvas.getContext("2d");

        this.charts.accuracyTrend = new Chart(ctx, {
            type: "line",
            data: {
                labels: this.dataCache.accuracyHistory.map((item) =>
                    new Date(item.date).toLocaleDateString("id-ID", {
                        month: "short",
                        day: "numeric",
                    })
                ),
                datasets: [
                    {
                        label: "Accuracy",
                        data: this.dataCache.accuracyHistory.map(
                            (item) => item.accuracy
                        ),
                        borderColor: "rgb(16, 185, 129)",
                        backgroundColor: "rgba(16, 185, 129, 0.1)",
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: "rgb(16, 185, 129)",
                        pointBorderColor: "#ffffff",
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 8,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false,
                    },
                    tooltip: {
                        mode: "index",
                        intersect: false,
                        backgroundColor: "rgba(0, 0, 0, 0.8)",
                        titleColor: "#ffffff",
                        bodyColor: "#ffffff",
                        borderColor: "rgb(16, 185, 129)",
                        borderWidth: 1,
                        cornerRadius: 8,
                        displayColors: false,
                        callbacks: {
                            label: function (context) {
                                return `Accuracy: ${context.parsed.y.toFixed(
                                    1
                                )}%`;
                            },
                        },
                    },
                },
                scales: {
                    x: {
                        grid: {
                            color: "rgba(0, 0, 0, 0.1)",
                            drawBorder: false,
                        },
                        ticks: {
                            color: "#6b7280",
                        },
                    },
                    y: {
                        min: 0,
                        max: 100,
                        grid: {
                            color: "rgba(0, 0, 0, 0.1)",
                            drawBorder: false,
                        },
                        ticks: {
                            color: "#6b7280",
                            callback: function (value) {
                                return value + "%";
                            },
                        },
                    },
                },
                animation: {
                    duration: this.config.animationDuration,
                    easing: "easeInOutQuart",
                },
            },
        });
    }

    createActivityHeatmap() {
        const container = document.querySelector(
            this.config.chartElements.activityHeatmap
        );
        if (!container) return;

        // Create heatmap using D3.js style approach with vanilla JS
        const heatmapData = this.generateHeatmapData();
        const heatmapHTML = this.renderHeatmap(heatmapData);
        container.innerHTML = heatmapHTML;
    }

    generateHeatmapData() {
        const days = ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"];
        const hours = Array.from({ length: 24 }, (_, i) => i);

        // Generate activity intensity data (0-4 scale)
        const data = [];
        days.forEach((day, dayIndex) => {
            hours.forEach((hour) => {
                const activity = this.getActivityIntensity(dayIndex, hour);
                data.push({
                    day: dayIndex,
                    hour: hour,
                    intensity: activity,
                    dayName: day,
                });
            });
        });

        return data;
    }

    getActivityIntensity(day, hour) {
        // Simulate activity data based on typical patterns
        // Peak hours: 9-11 AM, 2-4 PM, 7-9 PM
        // Weekend patterns different from weekdays

        const isWeekend = day >= 5;
        let baseIntensity = 0;

        if (isWeekend) {
            if (hour >= 10 && hour <= 12) baseIntensity = 2;
            else if (hour >= 14 && hour <= 16) baseIntensity = 3;
            else if (hour >= 19 && hour <= 21) baseIntensity = 2;
        } else {
            if (hour >= 9 && hour <= 11) baseIntensity = 3;
            else if (hour >= 14 && hour <= 16) baseIntensity = 4;
            else if (hour >= 19 && hour <= 21) baseIntensity = 3;
            else if (hour >= 7 && hour <= 8) baseIntensity = 1;
        }

        // Add some randomness
        return Math.min(4, baseIntensity + Math.floor(Math.random() * 2));
    }

    renderHeatmap(data) {
        const cellSize = 20;
        const days = ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"];
        const intensityColors = [
            "#edf2f7",
            "#bee3f8",
            "#63b3ed",
            "#4299e1",
            "#3182ce",
        ];

        let html = '<div class="heatmap-container">';
        html += '<div class="heatmap-title">Activity Heatmap</div>';
        html += '<div class="heatmap-grid">';

        // Hour labels
        html += '<div class="heatmap-hours">';
        for (let hour = 0; hour < 24; hour += 2) {
            html += `<div class="hour-label">${hour
                .toString()
                .padStart(2, "0")}</div>`;
        }
        html += "</div>";

        // Day rows
        days.forEach((day, dayIndex) => {
            html += `<div class="heatmap-row">`;
            html += `<div class="day-label">${day}</div>`;
            html += `<div class="day-cells">`;

            for (let hour = 0; hour < 24; hour++) {
                const cellData = data.find(
                    (d) => d.day === dayIndex && d.hour === hour
                );
                const intensity = cellData ? cellData.intensity : 0;
                const color = intensityColors[intensity];

                html += `<div class="heatmap-cell" 
                              style="background-color: ${color}" 
                              data-day="${day}" 
                              data-hour="${hour}" 
                              data-intensity="${intensity}"
                              title="${day} ${hour}:00 - Activity level: ${intensity}/4">
                         </div>`;
            }

            html += "</div></div>";
        });

        html += "</div>";

        // Legend
        html += '<div class="heatmap-legend">';
        html += "<span>Less</span>";
        intensityColors.forEach((color, index) => {
            html += `<div class="legend-cell" style="background-color: ${color}"></div>`;
        });
        html += "<span>More</span>";
        html += "</div>";

        html += "</div>";

        return html;
    }

    createPerformanceRadar() {
        const canvas = document.querySelector(
            this.config.chartElements.performanceRadar
        );
        if (!canvas) return;

        const ctx = canvas.getContext("2d");

        const metrics = this.dataCache.performanceMetrics;

        this.charts.performanceRadar = new Chart(ctx, {
            type: "radar",
            data: {
                labels: [
                    "Speed",
                    "Accuracy",
                    "Consistency",
                    "Endurance",
                    "Learning",
                    "Focus",
                ],
                datasets: [
                    {
                        label: "Your Performance",
                        data: [
                            metrics.speed || 60,
                            metrics.accuracy || 85,
                            metrics.consistency || 70,
                            metrics.endurance || 65,
                            metrics.learning || 75,
                            metrics.focus || 80,
                        ],
                        backgroundColor: "rgba(59, 130, 246, 0.2)",
                        borderColor: "rgb(59, 130, 246)",
                        borderWidth: 2,
                        pointBackgroundColor: "rgb(59, 130, 246)",
                        pointBorderColor: "#ffffff",
                        pointBorderWidth: 2,
                        pointRadius: 5,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false,
                    },
                    tooltip: {
                        backgroundColor: "rgba(0, 0, 0, 0.8)",
                        titleColor: "#ffffff",
                        bodyColor: "#ffffff",
                        borderColor: "rgb(59, 130, 246)",
                        borderWidth: 1,
                        cornerRadius: 8,
                        callbacks: {
                            label: function (context) {
                                return `${context.label}: ${context.parsed.r}/100`;
                            },
                        },
                    },
                },
                scales: {
                    r: {
                        min: 0,
                        max: 100,
                        ticks: {
                            stepSize: 20,
                            color: "#6b7280",
                            backdropColor: "transparent",
                        },
                        grid: {
                            color: "rgba(0, 0, 0, 0.1)",
                        },
                        angleLines: {
                            color: "rgba(0, 0, 0, 0.1)",
                        },
                        pointLabels: {
                            color: "#374151",
                            font: {
                                size: 12,
                                weight: "600",
                            },
                        },
                    },
                },
                animation: {
                    duration: this.config.animationDuration,
                    easing: "easeInOutQuart",
                },
            },
        });
    }

    createProgressChart() {
        const canvas = document.querySelector(
            this.config.chartElements.progressChart
        );
        if (!canvas) return;

        const ctx = canvas.getContext("2d");
        const goalData = this.dataCache.goalProgress;

        this.charts.progressChart = new Chart(ctx, {
            type: "doughnut",
            data: {
                labels: ["Completed", "Remaining"],
                datasets: [
                    {
                        data: [
                            goalData.completed || 65,
                            100 - (goalData.completed || 65),
                        ],
                        backgroundColor: [
                            "rgb(16, 185, 129)",
                            "rgba(0, 0, 0, 0.1)",
                        ],
                        borderWidth: 0,
                        cutout: "70%",
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false,
                    },
                    tooltip: {
                        enabled: false,
                    },
                },
                animation: {
                    duration: this.config.animationDuration,
                    easing: "easeInOutQuart",
                },
            },
            plugins: [
                {
                    beforeDraw: function (chart) {
                        const width = chart.width;
                        const height = chart.height;
                        const ctx = chart.ctx;

                        ctx.restore();
                        const fontSize = (height / 150).toFixed(2);
                        ctx.font = `bold ${fontSize}em sans-serif`;
                        ctx.textAlign = "center";
                        ctx.textBaseline = "middle";
                        ctx.fillStyle = "#374151";

                        const text = `${goalData.completed || 65}%`;
                        const textX = width / 2;
                        const textY = height / 2;

                        ctx.fillText(text, textX, textY);
                        ctx.save();
                    },
                },
            ],
        });
    }

    createCategoryBreakdown() {
        const canvas = document.querySelector(
            this.config.chartElements.categoryBreakdown
        );
        if (!canvas) return;

        const ctx = canvas.getContext("2d");

        this.charts.categoryBreakdown = new Chart(ctx, {
            type: "bar",
            data: {
                labels: this.dataCache.categoryStats.map((item) => item.name),
                datasets: [
                    {
                        label: "Average WPM",
                        data: this.dataCache.categoryStats.map(
                            (item) => item.avgWpm
                        ),
                        backgroundColor: [
                            "rgba(59, 130, 246, 0.8)",
                            "rgba(16, 185, 129, 0.8)",
                            "rgba(245, 158, 11, 0.8)",
                            "rgba(239, 68, 68, 0.8)",
                            "rgba(139, 92, 246, 0.8)",
                        ],
                        borderColor: [
                            "rgb(59, 130, 246)",
                            "rgb(16, 185, 129)",
                            "rgb(245, 158, 11)",
                            "rgb(239, 68, 68)",
                            "rgb(139, 92, 246)",
                        ],
                        borderWidth: 1,
                        borderRadius: 4,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false,
                    },
                    tooltip: {
                        backgroundColor: "rgba(0, 0, 0, 0.8)",
                        titleColor: "#ffffff",
                        bodyColor: "#ffffff",
                        borderColor: "rgb(59, 130, 246)",
                        borderWidth: 1,
                        cornerRadius: 8,
                        callbacks: {
                            label: function (context) {
                                return `Average: ${context.parsed.y} WPM`;
                            },
                        },
                    },
                },
                scales: {
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false,
                        },
                        ticks: {
                            color: "#6b7280",
                        },
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: "rgba(0, 0, 0, 0.1)",
                            drawBorder: false,
                        },
                        ticks: {
                            color: "#6b7280",
                            callback: function (value) {
                                return value + " WPM";
                            },
                        },
                    },
                },
                animation: {
                    duration: this.config.animationDuration,
                    easing: "easeInOutQuart",
                },
            },
        });
    }

    setupEventListeners() {
        // Chart interaction handlers
        document.addEventListener("click", (e) => {
            if (e.target.closest(".chart-period-selector")) {
                this.handlePeriodChange(
                    e.target.closest(".chart-period-selector")
                );
            }

            if (e.target.closest(".chart-export-btn")) {
                this.exportChart(
                    e.target.closest(".chart-export-btn").dataset.chart
                );
            }
        });

        // Heatmap cell interactions
        document.addEventListener("mouseover", (e) => {
            if (e.target.classList.contains("heatmap-cell")) {
                this.showHeatmapTooltip(e);
            }
        });

        document.addEventListener("mouseout", (e) => {
            if (e.target.classList.contains("heatmap-cell")) {
                this.hideHeatmapTooltip();
            }
        });

        // Window resize handler
        window.addEventListener("resize", () => {
            Object.values(this.charts).forEach((chart) => {
                if (chart && chart.resize) {
                    chart.resize();
                }
            });
        });
    }

    handlePeriodChange(selector) {
        const period = selector.dataset.period;
        const chartType = selector.dataset.chart;

        // Update active state
        selector.parentElement
            .querySelectorAll(".chart-period-selector")
            .forEach((btn) => {
                btn.classList.remove("active");
            });
        selector.classList.add("active");

        // Refresh chart data for the selected period
        this.refreshChartData(chartType, period);
    }

    async refreshChartData(chartType, period) {
        try {
            const response = await fetch(
                `${this.config.apiEndpoint}/statistics?period=${period}&chart=${chartType}`,
                {
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector(
                            'meta[name="csrf-token"]'
                        ).content,
                    },
                }
            );

            if (response.ok) {
                const data = await response.json();
                this.updateChart(chartType, data);
            }
        } catch (error) {
            console.error("Failed to refresh chart data:", error);
        }
    }

    updateChart(chartType, newData) {
        const chart = this.charts[chartType];
        if (!chart) return;

        switch (chartType) {
            case "wmpTrend":
                chart.data.labels = newData.labels;
                chart.data.datasets[0].data = newData.data;
                break;
            case "accuracyTrend":
                chart.data.labels = newData.labels;
                chart.data.datasets[0].data = newData.data;
                break;
            // Add other chart types as needed
        }

        chart.update("active");
    }

    exportChart(chartType) {
        const chart = this.charts[chartType];
        if (!chart) return;

        const url = chart.toBase64Image();
        const link = document.createElement("a");
        link.download = `${chartType}-chart.png`;
        link.href = url;
        link.click();
    }

    showHeatmapTooltip(e) {
        const cell = e.target;
        const day = cell.dataset.day;
        const hour = cell.dataset.hour;
        const intensity = cell.dataset.intensity;

        // Create or update tooltip
        let tooltip = document.querySelector(".heatmap-tooltip");
        if (!tooltip) {
            tooltip = document.createElement("div");
            tooltip.className = "heatmap-tooltip";
            document.body.appendChild(tooltip);
        }

        tooltip.innerHTML = `
            <div class="tooltip-header">${day} ${hour}:00</div>
            <div class="tooltip-content">Activity: ${intensity}/4</div>
        `;

        // Position tooltip
        const rect = cell.getBoundingClientRect();
        tooltip.style.left = `${rect.left + rect.width / 2}px`;
        tooltip.style.top = `${rect.top - tooltip.offsetHeight - 10}px`;
        tooltip.style.display = "block";
    }

    hideHeatmapTooltip() {
        const tooltip = document.querySelector(".heatmap-tooltip");
        if (tooltip) {
            tooltip.style.display = "none";
        }
    }

    startAutoRefresh() {
        setInterval(() => {
            this.loadDashboardData().then(() => {
                this.refreshAllCharts();
            });
        }, this.config.refreshInterval);
    }

    refreshAllCharts() {
        Object.keys(this.charts).forEach((chartType) => {
            if (this.charts[chartType]) {
                this.charts[chartType].update("none");
            }
        });

        // Refresh heatmap
        this.createActivityHeatmap();
    }

    // Public methods
    destroy() {
        Object.values(this.charts).forEach((chart) => {
            if (chart && chart.destroy) {
                chart.destroy();
            }
        });

        const tooltip = document.querySelector(".heatmap-tooltip");
        if (tooltip) {
            tooltip.remove();
        }
    }

    updateGoalProgress(percentage) {
        if (this.charts.progressChart) {
            this.charts.progressChart.data.datasets[0].data = [
                percentage,
                100 - percentage,
            ];
            this.charts.progressChart.update();
        }
    }
}

// CSS for dashboard analytics
const dashboardCSS = `
.chart-container {
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: var(--border-radius-lg);
    padding: 1.5rem;
    margin-bottom: 2rem;
    box-shadow: var(--shadow-sm);
}

.chart-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
}

.chart-title {
    font-family: var(--font-display);
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--text-primary);
}

.chart-controls {
    display: flex;
    gap: 0.5rem;
}

.chart-period-selector {
    padding: 0.5rem 1rem;
    background: var(--bg-secondary);
    border: 1px solid var(--border-light);
    border-radius: var(--border-radius);
    color: var(--text-secondary);
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.9rem;
    font-weight: 500;
}

.chart-period-selector:hover {
    background: var(--bg-tertiary);
    color: var(--text-primary);
}

.chart-period-selector.active {
    background: var(--accent-primary);
    color: white;
    border-color: var(--accent-primary);
}

.chart-export-btn {
    padding: 0.5rem;
    background: var(--bg-secondary);
    border: 1px solid var(--border-light);
    border-radius: var(--border-radius);
    color: var(--text-secondary);
    cursor: pointer;
    transition: all 0.3s ease;
}

.chart-export-btn:hover {
    background: var(--bg-tertiary);
    color: var(--text-primary);
}

.chart-canvas {
    position: relative;
    height: 300px;
    width: 100%;
}

/* Heatmap Styles */
.heatmap-container {
    padding: 1rem;
}

.heatmap-title {
    font-family: var(--font-display);
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 1rem;
    text-align: center;
}

.heatmap-grid {
    display: flex;
    flex-direction: column;
    gap: 2px;
    margin-bottom: 1rem;
}

.heatmap-hours {
    display: flex;
    gap: 2px;
    margin-left: 60px;
    margin-bottom: 5px;
}

.hour-label {
    width: 20px;
    text-align: center;
    font-size: 0.7rem;
    color: var(--text-muted);
}

.heatmap-row {
    display: flex;
    align-items: center;
    gap: 2px;
}

.day-label {
    width: 50px;
    font-size: 0.8rem;
    color: var(--text-secondary);
    font-weight: 500;
}

.day-cells {
    display: flex;
    gap: 2px;
}

.heatmap-cell {
    width: 20px;
    height: 20px;
    border-radius: 2px;
    cursor: pointer;
    transition: all 0.2s ease;
}

.heatmap-cell:hover {
    transform: scale(1.1);
    border: 1px solid var(--accent-primary);
}

.heatmap-legend {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    font-size: 0.8rem;
    color: var(--text-secondary);
}

.legend-cell {
    width: 12px;
    height: 12px;
    border-radius: 2px;
}

.heatmap-tooltip {
    position: absolute;
    background: rgba(0, 0, 0, 0.8);
    color: white;
    padding: 0.5rem;
    border-radius: var(--border-radius);
    font-size: 0.8rem;
    pointer-events: none;
    z-index: 1000;
    display: none;
}

.tooltip-header {
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.tooltip-content {
    opacity: 0.9;
}

/* Progress Chart Center Text */
.progress-chart-center {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    text-align: center;
    pointer-events: none;
}

.progress-percentage {
    font-family: var(--font-display);
    font-size: 2rem;
    font-weight: 700;
    color: var(--text-primary);
}

.progress-label {
    font-size: 0.9rem;
    color: var(--text-secondary);
    margin-top: 0.25rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .chart-container {
        padding: 1rem;
    }
    
    .chart-header {
        flex-direction: column;
        gap: 1rem;
        align-items: flex-start;
    }
    
    .chart-controls {
        width: 100%;
        justify-content: space-between;
    }
    
    .chart-period-selector {
        padding: 0.5rem 0.75rem;
        font-size: 0.8rem;
    }
    
    .chart-canvas {
        height: 250px;
    }
    
    .heatmap-hours {
        margin-left: 40px;
    }
    
    .day-label {
        width: 30px;
        font-size: 0.7rem;
    }
    
    .heatmap-cell {
        width: 15px;
        height: 15px;
    }
    
    .hour-label {
        width: 15px;
        font-size: 0.6rem;
    }
}
`;

// Inject CSS
if (!document.querySelector("#dashboard-styles")) {
    const style = document.createElement("style");
    style.id = "dashboard-styles";
    style.textContent = dashboardCSS;
    document.head.appendChild(style);
}

// Export for module systems
if (typeof module !== "undefined" && module.exports) {
    module.exports = DashboardAnalytics;
}

// Global namespace
window.DashboardAnalytics = DashboardAnalytics;
