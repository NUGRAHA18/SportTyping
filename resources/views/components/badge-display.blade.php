{{-- resources/views/components/badge-display.blade.php --}}
@props([
    'badges' => [],
    'showProgress' => false,
    'layout' => 'grid', // 'grid', 'list', 'carousel'
    'size' => 'normal', // 'small', 'normal', 'large'
    'showEarned' => true,
    'showLocked' => true,
    'groupByType' => false,
    'maxDisplay' => null
])

<div class="badge-display badge-layout-{{ $layout }} badge-size-{{ $size }}">
    @if($groupByType)
        @php
            $groupedBadges = collect($badges)->groupBy('requirement_type');
        @endphp
        
        @foreach($groupedBadges as $type => $typeBadges)
        <div class="badge-group">
            <h3 class="badge-group-title">
                <i class="fas fa-{{ $type === 'speed' ? 'tachometer-alt' : ($type === 'accuracy' ? 'bullseye' : ($type === 'experience' ? 'star' : ($type === 'competitions' ? 'trophy' : 'graduation-cap'))) }}"></i>
                {{ ucfirst($type) }} Badges
            </h3>
            
            <div class="badges-container">
                @foreach($typeBadges->take($maxDisplay ?? PHP_INT_MAX) as $badge)
                    <x-badge-item 
                        :badge="$badge" 
                        :size="$size"
                        :show-progress="$showProgress"
                    />
                @endforeach
            </div>
        </div>
        @endforeach
    @else
        <div class="badges-container">
            @forelse($badges->take($maxDisplay ?? PHP_INT_MAX) as $badge)
                <x-badge-item 
                    :badge="$badge"
                    :size="$size" 
                    :show-progress="$showProgress"
                />
            @empty
                <div class="no-badges">
                    <div class="no-badges-icon">
                        <i class="fas fa-medal"></i>
                    </div>
                    <h3>No Badges Yet</h3>
                    <p>Start typing to earn your first achievement!</p>
                </div>
            @endforelse
        </div>
    @endif
</div>

{{-- Badge Item Component --}}
@php
$badgeItem = function($badge, $size = 'normal', $showProgress = false) {
    $isEarned = isset($badge['earned_at']) || (isset($badge['pivot']) && $badge['pivot']['earned_at']);
    $progress = $badge['progress'] ?? null;
    
    return view('components.badge-item', compact('badge', 'size', 'showProgress', 'isEarned', 'progress'));
};
@endphp

<style>
.badge-display {
    font-family: var(--font-primary);
}

/* Layout Styles */
.badge-layout-grid .badges-container {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 1.5rem;
}

.badge-layout-list .badges-container {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.badge-layout-carousel .badges-container {
    display: flex;
    gap: 1rem;
    overflow-x: auto;
    padding-bottom: 1rem;
    scroll-behavior: smooth;
}

.badge-layout-carousel .badges-container::-webkit-scrollbar {
    height: 6px;
}

.badge-layout-carousel .badges-container::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 3px;
}

.badge-layout-carousel .badges-container::-webkit-scrollbar-thumb {
    background: var(--accent-pink);
    border-radius: 3px;
}

/* Size Adjustments */
.badge-size-small .badges-container {
    gap: 1rem;
}

.badge-size-small.badge-layout-grid .badges-container {
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
}

.badge-size-large .badges-container {
    gap: 2rem;
}

.badge-size-large.badge-layout-grid .badges-container {
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
}

/* Badge Groups */
.badge-group {
    margin-bottom: 3rem;
}

.badge-group-title {
    color: var(--text-primary);
    font-size: 1.3rem;
    font-weight: 600;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding-bottom: 0.75rem;
    border-bottom: 2px solid rgba(255, 255, 255, 0.1);
}

.badge-group-title i {
    color: var(--accent-pink);
}

/* No Badges State */
.no-badges {
    text-align: center;
    padding: 4rem 2rem;
    color: var(--text-secondary);
}

.no-badges-icon {
    width: 80px;
    height: 80px;
    background: var(--gradient-card);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
    font-size: 2rem;
    color: var(--text-muted);
}

.no-badges h3 {
    color: var(--text-primary);
    font-size: 1.3rem;
    margin-bottom: 0.5rem;
}

.no-badges p {
    font-size: 1rem;
    line-height: 1.5;
}

/* Responsive */
@media (max-width: 768px) {
    .badge-layout-grid .badges-container {
        grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
        gap: 1rem;
    }
    
    .badge-layout-carousel .badges-container {
        gap: 0.75rem;
    }
    
    .badge-group-title {
        font-size: 1.1rem;
    }
}
</style>

{{-- Individual Badge Item Component Template --}}
<script type="text/template" id="badge-item-template">
    <div class="badge-item {{ isEarned ? 'earned' : 'locked' }} badge-{{ size }}">
        <div class="badge-container">
            <div class="badge-icon">
                <img src="{{ asset('images/badges/' . (badge.icon ?? 'default.png')) }}" 
                     alt="{{ badge.name }}" 
                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                <div class="badge-icon-fallback" style="display: none;">
                    <i class="fas fa-medal"></i>
                </div>
            </div>
            
            @if(!$isEarned)
            <div class="badge-lock-overlay">
                <i class="fas fa-lock"></i>
            </div>
            @endif
            
            @if($isEarned)
            <div class="badge-shine"></div>
            @endif
        </div>
        
        <div class="badge-details">
            <h4 class="badge-name">{{ badge.name }}</h4>
            <p class="badge-description">{{ badge.description }}</p>
            
            <div class="badge-requirement">
                @if($isEarned)
                    <span class="earned-date">
                        <i class="fas fa-check-circle"></i>
                        Earned {{ isset($badge['earned_at']) ? $badge['earned_at']->diffForHumans() : 'recently' }}
                    </span>
                @else
                    <span class="requirement-text">
                        {{ $badge['requirement_value'] }} {{ ucfirst($badge['requirement_type']) }}
                        @if($showProgress && $progress)
                            <div class="badge-progress">
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: {{ min(100, ($progress / $badge['requirement_value']) * 100) }}%"></div>
                                </div>
                                <span class="progress-text">{{ $progress }}/{{ $badge['requirement_value'] }}</span>
                            </div>
                        @endif
                    </span>
                @endif
            </div>
        </div>
        
        @if($isEarned)
        <div class="badge-glow"></div>
        @endif
    </div>
</script>

<style>
/* Badge Item Styles */
.badge-item {
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

.badge-item:hover {
    transform: translateY(-2px);
    border-color: rgba(255, 255, 255, 0.2);
}

.badge-item.earned {
    border-color: rgba(255, 107, 157, 0.3);
    background: linear-gradient(145deg, rgba(255, 107, 157, 0.1), rgba(139, 92, 246, 0.1));
}

.badge-item.earned:hover {
    box-shadow: 0 12px 40px rgba(255, 107, 157, 0.2);
}

.badge-item.locked {
    opacity: 0.6;
}

.badge-item.locked:hover {
    opacity: 0.8;
}

/* Badge Container */
.badge-container {
    position: relative;
    width: 80px;
    height: 80px;
    margin: 0 auto 1.5rem;
}

.badge-icon {
    width: 100%;
    height: 100%;
    position: relative;
    border-radius: 50%;
    overflow: hidden;
}

.badge-icon img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.badge-icon-fallback {
    width: 100%;
    height: 100%;
    background: var(--gradient-accent);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: white;
    border-radius: 50%;
}

.badge-lock-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    color: var(--text-muted);
    font-size: 1.5rem;
}

.badge-shine {
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.3), transparent);
    border-radius: 50%;
    animation: shine 3s ease-in-out infinite;
}

@keyframes shine {
    0%, 100% { transform: translateX(-100%) translateY(-100%); }
    50% { transform: translateX(0) translateY(0); }
}

/* Badge Details */
.badge-details {
    text-align: center;
}

.badge-name {
    color: var(--text-primary);
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.badge-description {
    color: var(--text-secondary);
    font-size: 0.9rem;
    line-height: 1.4;
    margin-bottom: 1rem;
}

.badge-requirement {
    font-size: 0.85rem;
}

.earned-date {
    color: var(--accent-pink);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    font-weight: 500;
}

.requirement-text {
    color: var(--text-secondary);
}

/* Badge Progress */
.badge-progress {
    margin-top: 0.75rem;
}

.progress-bar {
    width: 100%;
    height: 6px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 3px;
    overflow: hidden;
    margin-bottom: 0.5rem;
}

.progress-fill {
    height: 100%;
    background: var(--gradient-accent);
    border-radius: 3px;
    transition: width 0.5s ease;
}

.progress-text {
    color: var(--text-secondary);
    font-size: 0.8rem;
}

/* Badge Glow */
.badge-glow {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: radial-gradient(circle at center, rgba(255, 107, 157, 0.1), transparent 70%);
    opacity: 0;
    transition: opacity 0.3s ease;
    pointer-events: none;
}

.badge-item.earned:hover .badge-glow {
    opacity: 1;
}

/* Size Variants */
.badge-small {
    padding: 1rem;
}

.badge-small .badge-container {
    width: 60px;
    height: 60px;
    margin-bottom: 1rem;
}

.badge-small .badge-name {
    font-size: 1rem;
}

.badge-small .badge-description {
    font-size: 0.8rem;
}

.badge-large {
    padding: 2rem;
}

.badge-large .badge-container {
    width: 100px;
    height: 100px;
    margin-bottom: 2rem;
}

.badge-large .badge-name {
    font-size: 1.3rem;
}

.badge-large .badge-description {
    font-size: 1rem;
}

/* List Layout Specific */
.badge-layout-list .badge-item {
    display: flex;
    align-items: center;
    text-align: left;
    padding: 1rem;
}

.badge-layout-list .badge-container {
    margin: 0 1rem 0 0;
    width: 60px;
    height: 60px;
}

.badge-layout-list .badge-details {
    text-align: left;
    flex: 1;
}

/* Carousel Layout Specific */
.badge-layout-carousel .badge-item {
    min-width: 200px;
    flex-shrink: 0;
}

/* Responsive */
@media (max-width: 768px) {
    .badge-item {
        padding: 1rem;
    }
    
    .badge-container {
        width: 60px;
        height: 60px;
        margin-bottom: 1rem;
    }
    
    .badge-name {
        font-size: 1rem;
    }
    
    .badge-description {
        font-size: 0.85rem;
    }
    
    .badge-layout-list .badge-item {
        flex-direction: column;
        text-align: center;
    }
    
    .badge-layout-list .badge-container {
        margin: 0 0 1rem 0;
    }
    
    .badge-layout-list .badge-details {
        text-align: center;
    }
}