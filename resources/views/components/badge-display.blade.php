{{-- resources/views/components/badge-display.blade.php --}}
@props([
    'badge' => null,
    'size' => 'default', // 'small', 'default', 'large'
    'showProgress' => false,
    'progress' => 0,
    'earned' => false,
    'interactive' => true,
    'showDetails' => true
])

@php
    $sizeClasses = [
        'small' => 'badge-display-sm',
        'default' => '',
        'large' => 'badge-display-lg'
    ];
@endphp

<div class="badge-display {{ $sizeClasses[$size] ?? '' }} {{ $earned ? 'badge-earned' : 'badge-locked' }} {{ $interactive ? 'badge-interactive' : '' }} {{ $attributes->get('class') }}" 
     @if($interactive) data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $badge?->description ?? 'Badge information' }}" @endif>
    
    <div class="badge-container">
        <div class="badge-image-wrapper">
            @if($badge && $earned)
                <img src="{{ asset('image/badges/' . $badge->icon) }}" 
                     alt="{{ $badge->name }}" 
                     class="badge-image">
            @elseif($badge)
                <img src="{{ asset('image/badges/' . $badge->icon) }}" 
                     alt="{{ $badge->name }}" 
                     class="badge-image badge-grayscale">
            @else
                <div class="badge-placeholder">
                    <i class="fas fa-medal"></i>
                </div>
            @endif
            
            @if($earned)
                <div class="badge-glow"></div>
                <div class="badge-sparkle">
                    <i class="fas fa-sparkles"></i>
                </div>
            @endif
            
            @if(!$earned && $badge)
                <div class="badge-lock">
                    <i class="fas fa-lock"></i>
                </div>
            @endif
        </div>
        
        @if($showProgress && !$earned && $progress > 0)
            <div class="badge-progress">
                <div class="progress-ring">
                    <svg width="100%" height="100%" viewBox="0 0 36 36">
                        <path class="progress-bg"
                              d="M18 2.0845
                                a 15.9155 15.9155 0 0 1 0 31.831
                                a 15.9155 15.9155 0 0 1 0 -31.831"
                              fill="none"
                              stroke="var(--border-light)"
                              stroke-width="2"/>
                        <path class="progress-fill"
                              d="M18 2.0845
                                a 15.9155 15.9155 0 0 1 0 31.831
                                a 15.9155 15.9155 0 0 1 0 -31.831"
                              fill="none"
                              stroke="var(--accent-primary)"
                              stroke-width="2"
                              stroke-dasharray="{{ $progress }}, 100"
                              stroke-linecap="round"/>
                    </svg>
                    <div class="progress-text">{{ round($progress) }}%</div>
                </div>
            </div>
        @endif
    </div>
    
    @if($showDetails && $badge)
        <div class="badge-details">
            <div class="badge-name">{{ $badge->name }}</div>
            @if($size !== 'small')
                <div class="badge-description">{{ $badge->description }}</div>
                @if($badge->requirement_value)
                    <div class="badge-requirement">
                        {{ $badge->requirement_type === 'wpm' ? 'WPM: ' : '' }}
                        {{ $badge->requirement_value }}
                        {{ $badge->requirement_type === 'accuracy' ? '%' : '' }}
                    </div>
                @endif
            @endif
        </div>
    @endif
</div>

<style>
.badge-display {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    transition: all 0.3s ease;
    position: relative;
}

.badge-interactive {
    cursor: pointer;
}

.badge-interactive:hover {
    transform: translateY(-3px);
}

.badge-container {
    position: relative;
    margin-bottom: 1rem;
}

.badge-image-wrapper {
    position: relative;
    width: 80px;
    height: 80px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--bg-card);
    border: 3px solid var(--border-light);
    transition: all 0.3s ease;
}

.badge-earned .badge-image-wrapper {
    border-color: var(--accent-secondary);
    box-shadow: 0 0 20px rgba(245, 158, 11, 0.3);
}

.badge-image {
    width: 60px;
    height: 60px;
    object-fit: contain;
    transition: all 0.3s ease;
}

.badge-grayscale {
    filter: grayscale(100%) opacity(0.5);
}

.badge-placeholder {
    font-size: 2rem;
    color: var(--text-muted);
}

.badge-glow {
    position: absolute;
    top: -5px;
    left: -5px;
    right: -5px;
    bottom: -5px;
    border-radius: 50%;
    background: var(--medal-gradient);
    opacity: 0.2;
    animation: pulse-glow 2s ease-in-out infinite;
}

@keyframes pulse-glow {
    0%, 100% { transform: scale(1); opacity: 0.2; }
    50% { transform: scale(1.1); opacity: 0.4; }
}

.badge-sparkle {
    position: absolute;
    top: -8px;
    right: -8px;
    width: 24px;
    height: 24px;
    background: var(--medal-gradient);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.8rem;
    color: white;
    animation: sparkle 1.5s ease-in-out infinite;
}

@keyframes sparkle {
    0%, 100% { transform: rotate(0deg) scale(1); }
    50% { transform: rotate(180deg) scale(1.2); }
}

.badge-lock {
    position: absolute;
    bottom: -8px;
    right: -8px;
    width: 24px;
    height: 24px;
    background: var(--text-muted);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.7rem;
    color: white;
}

.badge-progress {
    position: absolute;
    top: -3px;
    left: -3px;
    right: -3px;
    bottom: -3px;
}

.progress-ring {
    position: relative;
    width: 100%;
    height: 100%;
}

.progress-ring svg {
    transform: rotate(-90deg);
}

.progress-text {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-size: 0.7rem;
    font-weight: 600;
    color: var(--accent-primary);
}

.badge-details {
    max-width: 150px;
}

.badge-name {
    font-family: var(--font-display);
    font-weight: 600;
    color: var(--text-primary);
    font-size: 0.9rem;
    margin-bottom: 0.25rem;
}

.badge-description {
    color: var(--text-secondary);
    font-size: 0.8rem;
    line-height: 1.4;
    margin-bottom: 0.25rem;
}

.badge-requirement {
    color: var(--text-muted);
    font-size: 0.75rem;
    font-weight: 600;
}

.badge-earned .badge-name {
    color: var(--accent-secondary);
}

/* Size variations */
.badge-display-sm .badge-image-wrapper {
    width: 60px;
    height: 60px;
}

.badge-display-sm .badge-image {
    width: 45px;
    height: 45px;
}

.badge-display-sm .badge-sparkle,
.badge-display-sm .badge-lock {
    width: 18px;
    height: 18px;
    font-size: 0.6rem;
}

.badge-display-sm .badge-name {
    font-size: 0.8rem;
}

.badge-display-lg .badge-image-wrapper {
    width: 100px;
    height: 100px;
}

.badge-display-lg .badge-image {
    width: 75px;
    height: 75px;
}

.badge-display-lg .badge-sparkle,
.badge-display-lg .badge-lock {
    width: 30px;
    height: 30px;
    font-size: 0.9rem;
}

.badge-display-lg .badge-name {
    font-size: 1.1rem;
}

.badge-display-lg .badge-description {
    font-size: 0.9rem;
}

/* Grid layouts */
.badge-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 2rem;
    padding: 2rem 0;
}

.badge-grid-sm {
    grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
    gap: 1.5rem;
}

.badge-grid-lg {
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 2.5rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .badge-grid {
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        gap: 1.5rem;
        padding: 1.5rem 0;
    }
    
    .badge-display-lg .badge-image-wrapper {
        width: 80px;
        height: 80px;
    }
    
    .badge-display-lg .badge-image {
        width: 60px;
        height: 60px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips for badges
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Badge click animation
    document.querySelectorAll('.badge-interactive').forEach(badge => {
        badge.addEventListener('click', function() {
            if (this.classList.contains('badge-earned')) {
                this.style.animation = 'none';
                setTimeout(() => {
                    this.style.animation = 'sparkle 0.6s ease-in-out';
                }, 10);
            }
        });
    });
});
</script>