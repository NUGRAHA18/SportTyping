{{-- resources/views/components/stat-card.blade.php --}}
@props([
    'title' => '',
    'value' => '',
    'icon' => 'fas fa-chart-line',
    'color' => 'primary',
    'trend' => null, // 'up', 'down', 'stable'
    'trendValue' => null,
    'subtitle' => null,
    'animate' => true,
    'size' => 'normal' // 'small', 'normal', 'large'
])

<div class="stat-card stat-card-{{ $size }} stat-card-{{ $color }} {{ $animate ? 'animate-on-scroll' : '' }}">
    <div class="stat-card-content">
        <div class="stat-icon">
            <i class="{{ $icon }}"></i>
        </div>
        
        <div class="stat-details">
            <div class="stat-value" data-value="{{ is_numeric($value) ? $value : 0 }}">
                {{ $value }}
            </div>
            
            <div class="stat-title">{{ $title }}</div>
            
            @if($subtitle)
            <div class="stat-subtitle">{{ $subtitle }}</div>
            @endif
            
            @if($trend && $trendValue)
            <div class="stat-trend trend-{{ $trend }}">
                <i class="fas fa-arrow-{{ $trend === 'up' ? 'up' : ($trend === 'down' ? 'down' : 'right') }}"></i>
                <span>{{ $trendValue }}</span>
                <small>{{ $trend === 'up' ? 'increase' : ($trend === 'down' ? 'decrease' : 'stable') }}</small>
            </div>
            @endif
        </div>
    </div>
    
    <div class="stat-card-glow"></div>
</div>

<style>
.stat-card {
    background: var(--gradient-card);
    backdrop-filter: blur(var(--blur-amount));
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 16px;
    padding: 1.5rem;
    position: relative;
    overflow: hidden;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    cursor: pointer;
}

.stat-card:hover {
    transform: translateY(-4px);
    border-color: rgba(255, 255, 255, 0.2);
}

.stat-card-content {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    position: relative;
    z-index: 2;
}

/* Size Variants */
.stat-card-small {
    padding: 1rem;
}

.stat-card-small .stat-card-content {
    gap: 1rem;
}

.stat-card-small .stat-value {
    font-size: 1.5rem;
}

.stat-card-small .stat-icon {
    width: 40px;
    height: 40px;
    font-size: 1rem;
}

.stat-card-large {
    padding: 2.5rem;
}

.stat-card-large .stat-value {
    font-size: 3rem;
}

.stat-card-large .stat-icon {
    width: 80px;
    height: 80px;
    font-size: 2rem;
}

/* Icon Styles */
.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
    flex-shrink: 0;
    position: relative;
}

.stat-icon::after {
    content: '';
    position: absolute;
    inset: -2px;
    border-radius: 50%;
    background: inherit;
    opacity: 0.3;
    z-index: -1;
    animation: pulse-glow 2s ease-in-out infinite;
}

@keyframes pulse-glow {
    0%, 100% { opacity: 0.3; transform: scale(1); }
    50% { opacity: 0.6; transform: scale(1.1); }
}

/* Color Variants */
.stat-card-primary .stat-icon {
    background: var(--gradient-accent);
}

.stat-card-primary:hover {
    box-shadow: 0 12px 40px rgba(255, 107, 157, 0.2);
}

.stat-card-secondary .stat-icon {
    background: linear-gradient(45deg, var(--accent-purple), var(--accent-cyan));
}

.stat-card-secondary:hover {
    box-shadow: 0 12px 40px rgba(139, 92, 246, 0.2);
}

.stat-card-success .stat-icon {
    background: linear-gradient(45deg, #10b981, #059669);
}

.stat-card-success:hover {
    box-shadow: 0 12px 40px rgba(16, 185, 129, 0.2);
}

.stat-card-warning .stat-icon {
    background: linear-gradient(45deg, #f59e0b, #eab308);
}

.stat-card-warning:hover {
    box-shadow: 0 12px 40px rgba(245, 158, 11, 0.2);
}

.stat-card-danger .stat-icon {
    background: linear-gradient(45deg, #ef4444, #dc2626);
}

.stat-card-danger:hover {
    box-shadow: 0 12px 40px rgba(239, 68, 68, 0.2);
}

.stat-card-info .stat-icon {
    background: linear-gradient(45deg, #3b82f6, #1d4ed8);
}

.stat-card-info:hover {
    box-shadow: 0 12px 40px rgba(59, 130, 246, 0.2);
}

/* Stat Details */
.stat-details {
    flex: 1;
}

.stat-value {
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--text-primary);
    line-height: 1;
    margin-bottom: 0.5rem;
    font-family: 'Courier New', monospace;
}

.stat-title {
    color: var(--text-primary);
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.stat-subtitle {
    color: var(--text-secondary);
    font-size: 0.9rem;
    margin-bottom: 0.5rem;
}

/* Trend Indicator */
.stat-trend {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.85rem;
    font-weight: 500;
}

.trend-up {
    color: #10b981;
}

.trend-down {
    color: #ef4444;
}

.trend-stable {
    color: var(--text-secondary);
}

/* Glow Effect */
.stat-card-glow {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: radial-gradient(circle at 50% 50%, rgba(255, 255, 255, 0.1), transparent 70%);
    opacity: 0;
    transition: opacity 0.3s ease;
    pointer-events: none;
}

.stat-card:hover .stat-card-glow {
    opacity: 1;
}

/* Animation */
.animate-on-scroll {
    opacity: 0;
    transform: translateY(30px);
    transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
}

.animate-on-scroll.in-view {
    opacity: 1;
    transform: translateY(0);
}

/* Responsive */
@media (max-width: 768px) {
    .stat-card {
        padding: 1rem;
    }
    
    .stat-card-content {
        gap: 1rem;
    }
    
    .stat-value {
        font-size: 2rem;
    }
    
    .stat-icon {
        width: 50px;
        height: 50px;
        font-size: 1.2rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animate stat values on load
    const animateValue = (element, start, end, duration) => {
        const startTime = performance.now();
        const startValue = parseFloat(start) || 0;
        const endValue = parseFloat(end) || 0;
        const difference = endValue - startValue;
        
        const animate = (currentTime) => {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
            
            // Easing function
            const easeOutCubic = 1 - Math.pow(1 - progress, 3);
            const currentValue = startValue + (difference * easeOutCubic);
            
            // Format value based on type
            let displayValue;
            if (element.textContent.includes('%')) {
                displayValue = Math.round(currentValue) + '%';
            } else if (element.textContent.includes('WPM')) {
                displayValue = Math.round(currentValue) + ' WPM';
            } else if (Number.isInteger(endValue)) {
                displayValue = Math.round(currentValue);
            } else {
                displayValue = currentValue.toFixed(1);
            }
            
            element.textContent = displayValue;
            
            if (progress < 1) {
                requestAnimationFrame(animate);
            }
        };
        
        requestAnimationFrame(animate);
    };
    
    // Intersection Observer for scroll animations
    const observerOptions = {
        threshold: 0.2,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('in-view');
                
                // Animate stat value
                const statValue = entry.target.querySelector('.stat-value');
                if (statValue && statValue.dataset.value) {
                    const currentText = statValue.textContent;
                    const targetValue = statValue.dataset.value;
                    
                    // Extract numeric value from current text
                    const currentNumeric = parseFloat(currentText.replace(/[^\d.-]/g, '')) || 0;
                    
                    animateValue(statValue, 0, targetValue, 1500);
                }
                
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);
    
    // Observe all animated stat cards
    document.querySelectorAll('.animate-on-scroll').forEach(card => {
        observer.observe(card);
    });
});
</script>