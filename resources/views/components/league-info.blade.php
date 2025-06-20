{{-- resources/views/components/league-info.blade.php --}}
@props([
    'league' => null,
    'userExperience' => 0,
    'size' => 'default', // 'small', 'default', 'large'
    'showProgress' => true,
    'showDetails' => true,
    'interactive' => false
])

@php
    $sizeClasses = [
        'small' => 'league-info-sm',
        'default' => '',
        'large' => 'league-info-lg'
    ];
    
    // Calculate progress to next league
    $currentExp = $userExperience;
    $currentLeagueExp = $league ? $league->min_experience : 0;
    $nextLeague = null;
    $progressPercent = 0;
    
    if ($league) {
        // Find next league
        $allLeagues = [
            (object)['name' => 'Novice', 'min_experience' => 0, 'icon' => 'novice.png'],
            (object)['name' => 'Apprentice', 'min_experience' => 100, 'icon' => 'apprentice.png'],
            (object)['name' => 'Expert', 'min_experience' => 500, 'icon' => 'expert.png'],
            (object)['name' => 'Journeyman', 'min_experience' => 1500, 'icon' => 'journeyman.png'],
            (object)['name' => 'Legend', 'min_experience' => 4000, 'icon' => 'legend.png'],
            (object)['name' => 'Master', 'min_experience' => 8000, 'icon' => 'master.png'],
            (object)['name' => 'Grandmaster', 'min_experience' => 15000, 'icon' => 'grandmaster.png']
        ];
        
        foreach ($allLeagues as $index => $l) {
            if ($l->name === $league->name && isset($allLeagues[$index + 1])) {
                $nextLeague = $allLeagues[$index + 1];
                break;
            }
        }
        
        if ($nextLeague) {
            $expNeeded = $nextLeague->min_experience - $currentLeagueExp;
            $expProgress = $currentExp - $currentLeagueExp;
            $progressPercent = min(100, max(0, ($expProgress / $expNeeded) * 100));
        }
    }
@endphp

<div class="league-info {{ $sizeClasses[$size] ?? '' }} {{ $interactive ? 'league-interactive' : '' }} {{ $attributes->get('class') }}">
    <div class="league-content">
        @if($league)
            <div class="league-badge">
                <div class="league-image-wrapper">
                    <img src="{{ asset('image/leagues/' . $league->icon) }}" 
                         alt="{{ $league->name }}" 
                         class="league-image">
                    <div class="league-glow"></div>
                </div>
                
                @if($showProgress && $nextLeague)
                    <div class="league-progress-ring">
                        <svg width="100%" height="100%" viewBox="0 0 120 120">
                            <circle cx="60" cy="60" r="54" fill="none" stroke="var(--border-light)" stroke-width="4"/>
                            <circle cx="60" cy="60" r="54" fill="none" stroke="var(--accent-primary)" stroke-width="4"
                                    stroke-dasharray="{{ ($progressPercent * 339.292) / 100 }}, 339.292"
                                    stroke-linecap="round"
                                    class="progress-circle"/>
                        </svg>
                    </div>
                @endif
            </div>
            
            @if($showDetails)
                <div class="league-details">
                    <div class="league-current">
                        <div class="league-name">{{ $league->name }}</div>
                        <div class="league-experience">{{ number_format($userExperience) }} EXP</div>
                    </div>
                    
                    @if($showProgress && $nextLeague)
                        <div class="league-progression">
                            <div class="progression-info">
                                <span class="next-league">Next: {{ $nextLeague->name }}</span>
                                <span class="exp-needed">{{ number_format($nextLeague->min_experience - $userExperience) }} EXP needed</span>
                            </div>
                            
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: {{ $progressPercent }}%"></div>
                            </div>
                            
                            <div class="progress-text">{{ round($progressPercent) }}% to next league</div>
                        </div>
                    @elseif($league->name === 'Grandmaster')
                        <div class="league-max">
                            <i class="fas fa-crown"></i>
                            <span>Maximum League Achieved!</span>
                        </div>
                    @endif
                </div>
            @endif
            
        @else
            <div class="league-placeholder">
                <div class="placeholder-icon">
                    <i class="fas fa-medal"></i>
                </div>
                <div class="placeholder-text">
                    <div class="placeholder-title">Start Your Journey</div>
                    <div class="placeholder-subtitle">Complete activities to join a league</div>
                </div>
            </div>
        @endif
    </div>
    
    @if(isset($slot) && !$slot->isEmpty())
        <div class="league-footer">
            {{ $slot }}
        </div>
    @endif
</div>

<style>
.league-info {
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: var(--border-radius-lg);
    padding: 1.5rem;
    box-shadow: var(--shadow-sm);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.league-info::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: var(--medal-gradient);
    opacity: 0.8;
}

.league-interactive {
    cursor: pointer;
}

.league-interactive:hover {
    box-shadow: var(--shadow-md);
    transform: translateY(-2px);
}

.league-content {
    display: flex;
    align-items: center;
    gap: 1.5rem;
}

.league-badge {
    position: relative;
    flex-shrink: 0;
}

.league-image-wrapper {
    position: relative;
    width: 80px;
    height: 80px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--bg-secondary);
    border: 3px solid var(--accent-secondary);
    box-shadow: 0 0 20px rgba(245, 158, 11, 0.3);
}

.league-image {
    width: 60px;
    height: 60px;
    object-fit: contain;
    filter: drop-shadow(0 2px 8px rgba(0, 0, 0, 0.1));
}

.league-glow {
    position: absolute;
    top: -10px;
    left: -10px;
    right: -10px;
    bottom: -10px;
    border-radius: 50%;
    background: var(--medal-gradient);
    opacity: 0.2;
    animation: league-pulse 3s ease-in-out infinite;
}

@keyframes league-pulse {
    0%, 100% { transform: scale(1); opacity: 0.2; }
    50% { transform: scale(1.1); opacity: 0.4; }
}

.league-progress-ring {
    position: absolute;
    top: -6px;
    left: -6px;
    right: -6px;
    bottom: -6px;
}

.league-progress-ring svg {
    transform: rotate(-90deg);
}

.progress-circle {
    transition: stroke-dasharray 0.5s ease;
}

.league-details {
    flex: 1;
    min-width: 0;
}

.league-current {
    margin-bottom: 1rem;
}

.league-name {
    font-family: var(--font-display);
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 0.25rem;
}

.league-experience {
    color: var(--text-secondary);
    font-weight: 600;
}

.league-progression {
    margin-top: 1rem;
}

.progression-info {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
}

.next-league {
    color: var(--text-primary);
    font-weight: 600;
}

.exp-needed {
    color: var(--text-secondary);
}

.progress-bar {
    width: 100%;
    height: 8px;
    background: var(--border-light);
    border-radius: var(--border-radius);
    overflow: hidden;
    margin-bottom: 0.5rem;
}

.progress-fill {
    height: 100%;
    background: var(--medal-gradient);
    transition: width 0.5s ease;
    position: relative;
}

.progress-fill::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
    animation: shimmer 2s ease-in-out infinite;
}

@keyframes shimmer {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(100%); }
}

.progress-text {
    text-align: center;
    font-size: 0.8rem;
    color: var(--text-secondary);
    font-weight: 600;
}

.league-max {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 1rem;
    background: var(--medal-gradient);
    border-radius: var(--border-radius);
    color: white;
    font-weight: 600;
    text-align: center;
}

.league-placeholder {
    display: flex;
    align-items: center;
    gap: 1rem;
    width: 100%;
}

.placeholder-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: var(--bg-secondary);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: var(--text-muted);
    flex-shrink: 0;
}

.placeholder-text {
    flex: 1;
}

.placeholder-title {
    font-family: var(--font-display);
    font-size: 1.2rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 0.25rem;
}

.placeholder-subtitle {
    color: var(--text-secondary);
    font-size: 0.9rem;
}

.league-footer {
    margin-top: 1.5rem;
    padding-top: 1rem;
    border-top: 1px solid var(--border-light);
}

/* Size variations */
.league-info-sm {
    padding: 1rem;
}

.league-info-sm .league-content {
    gap: 1rem;
}

.league-info-sm .league-image-wrapper {
    width: 60px;
    height: 60px;
}

.league-info-sm .league-image {
    width: 45px;
    height: 45px;
}

.league-info-sm .league-name {
    font-size: 1.2rem;
}

.league-info-lg {
    padding: 2rem;
}

.league-info-lg .league-content {
    gap: 2rem;
}

.league-info-lg .league-image-wrapper {
    width: 100px;
    height: 100px;
}

.league-info-lg .league-image {
    width: 75px;
    height: 75px;
}

.league-info-lg .league-name {
    font-size: 2rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .league-info {
        padding: 1.25rem;
    }
    
    .league-content {
        flex-direction: column;
        text-align: center;
        gap: 1.5rem;
    }
    
    .progression-info {
        flex-direction: column;
        gap: 0.25rem;
        text-align: center;
    }
    
    .league-placeholder {
        flex-direction: column;
        text-align: center;
        gap: 1rem;
    }
}

@media (max-width: 480px) {
    .league-info-lg .league-content {
        flex-direction: column;
        gap: 1.5rem;
    }
    
    .league-info-lg .league-image-wrapper {
        width: 80px;
        height: 80px;
    }
    
    .league-info-lg .league-image {
        width: 60px;
        height: 60px;
    }
}
</style>