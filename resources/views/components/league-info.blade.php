{{-- resources/views/components/league-info.blade.php --}}
@props([
    'user' => null,
    'showDetails' => false,
    'showProgress' => true,
    'size' => 'default', // 'small', 'default', 'large'
    'layout' => 'horizontal' // 'horizontal', 'vertical'
])

@php
    $currentLeague = $user ? $user->profile->league : null;
    $totalExperience = $user ? $user->profile->total_experience : 0;
    $nextLeague = null;
    $progressPercentage = 0;
    $experienceToNext = 0;
    
    if ($currentLeague) {
        // Find next league
        $nextLeague = \App\Models\League::where('min_experience', '>', $currentLeague->min_experience)
            ->orderBy('min_experience', 'asc')
            ->first();
            
        if ($nextLeague) {
            $experienceToNext = $nextLeague->min_experience - $totalExperience;
            $leagueRange = $nextLeague->min_experience - $currentLeague->min_experience;
            $currentProgress = $totalExperience - $currentLeague->min_experience;
            $progressPercentage = min(100, max(0, ($currentProgress / $leagueRange) * 100));
        } else {
            // Highest league
            $progressPercentage = 100;
        }
    }
    
    $sizeClasses = match($size) {
        'small' => 'league-info-small',
        'large' => 'league-info-large',
        default => 'league-info-default'
    };
    
    $layoutClasses = match($layout) {
        'vertical' => 'league-info-vertical',
        default => 'league-info-horizontal'
    };
@endphp

<div class="league-info {{ $sizeClasses }} {{ $layoutClasses }}" {{ $attributes }}>
    <style>
        :root {
            /* SportTyping Color Palette */
            --bg-primary: #1a0d2e;
            --bg-secondary: #2c1b47;
            --bg-card: rgba(44, 27, 71, 0.4);
            --accent-pink: #ff6b9d;
            --accent-cyan: #00d4ff;
            --accent-purple: #8b5cf6;
            
            /* Gradients */
            --gradient-card: linear-gradient(145deg, rgba(139, 92, 246, 0.1) 0%, rgba(255, 107, 157, 0.1) 100%);
            --gradient-accent: linear-gradient(90deg, #ff6b9d 0%, #00d4ff 100%);
            --gradient-button: linear-gradient(45deg, #ff6b9d, #8b5cf6);
            
            /* Text Colors */
            --text-primary: #ffffff;
            --text-secondary: #b4a7d1;
            --text-muted: #9ca3af;
            
            /* Layout */
            --border-radius: 12px;
            --border-radius-lg: 20px;
            --blur-amount: 20px;
            --shadow-card: 0 8px 32px rgba(0, 0, 0, 0.3);
            
            /* League Colors */
            --league-novice: linear-gradient(45deg, #6b7280, #9ca3af);
            --league-apprentice: linear-gradient(45deg, #059669, #10b981);
            --league-journeyman: linear-gradient(45deg, #0ea5e9, #00d4ff);
            --league-expert: linear-gradient(45deg, #8b5cf6, #a855f7);
            --league-master: linear-gradient(45deg, #f59e0b, #fbbf24);
            --league-grandmaster: linear-gradient(45deg, #ef4444, #f87171);
            --league-legend: linear-gradient(45deg, #ff6b9d, #00d4ff);
        }

        .league-info {
            background: var(--gradient-card);
            backdrop-filter: blur(var(--blur-amount));
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: var(--border-radius-lg);
            padding: 1.5rem;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            font-family: 'Poppins', sans-serif;
        }

        .league-info::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--gradient-accent);
            opacity: 0.8;
        }

        .league-info:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 40px rgba(139, 92, 246, 0.2);
            border-color: var(--accent-pink);
        }

        /* Layout Variations */
        .league-info-horizontal {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .league-info-vertical {
            display: flex;
            flex-direction: column;
            text-align: center;
            gap: 1rem;
        }

        /* Size Variations */
        .league-info-small {
            padding: 1rem;
            gap: 1rem;
        }

        .league-info-small .league-icon {
            width: 40px;
            height: 40px;
            font-size: 1rem;
        }

        .league-info-small .league-name {
            font-size: 1rem;
        }

        .league-info-default .league-icon {
            width: 60px;
            height: 60px;
            font-size: 1.5rem;
        }

        .league-info-default .league-name {
            font-size: 1.2rem;
        }

        .league-info-large {
            padding: 2rem;
        }

        .league-info-large .league-icon {
            width: 80px;
            height: 80px;
            font-size: 2rem;
        }

        .league-info-large .league-name {
            font-size: 1.5rem;
        }

        /* League Icon */
        .league-icon {
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            position: relative;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
            flex-shrink: 0;
        }

        .league-icon::after {
            content: '';
            position: absolute;
            inset: -2px;
            border-radius: 50%;
            background: var(--gradient-accent);
            z-index: -1;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .league-info:hover .league-icon::after {
            opacity: 1;
        }

        .league-icon.novice { background: var(--league-novice); }
        .league-icon.apprentice { background: var(--league-apprentice); }
        .league-icon.journeyman { background: var(--league-journeyman); }
        .league-icon.expert { background: var(--league-expert); }
        .league-icon.master { background: var(--league-master); }
        .league-icon.grandmaster { background: var(--league-grandmaster); }
        .league-icon.legend { background: var(--league-legend); }

        /* League Content */
        .league-content {
            flex: 1;
            min-width: 0;
        }

        .league-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 0.5rem;
        }

        .league-info-vertical .league-header {
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
        }

        .league-name {
            color: var(--text-primary);
            font-weight: 700;
            margin: 0;
            background: var(--gradient-accent);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .league-rank {
            background: rgba(255, 107, 157, 0.1);
            color: var(--accent-pink);
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 600;
            border: 1px solid rgba(255, 107, 157, 0.2);
        }

        .league-description {
            color: var(--text-secondary);
            font-size: 0.9rem;
            margin-bottom: 1rem;
            line-height: 1.4;
        }

        .league-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(80px, 1fr));
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .league-info-small .league-stats {
            grid-template-columns: repeat(2, 1fr);
            gap: 0.5rem;
        }

        .stat-item {
            text-align: center;
            padding: 0.75rem;
            background: rgba(255, 255, 255, 0.03);
            border-radius: var(--border-radius);
            border: 1px solid rgba(255, 255, 255, 0.05);
            transition: all 0.3s ease;
        }

        .stat-item:hover {
            background: rgba(255, 255, 255, 0.05);
            border-color: var(--accent-pink);
        }

        .stat-value {
            display: block;
            color: var(--text-primary);
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }

        .league-info-small .stat-value {
            font-size: 1rem;
        }

        .stat-label {
            color: var(--text-secondary);
            font-size: 0.8rem;
            font-weight: 500;
        }

        /* Progress Section */
        .league-progress {
            margin-top: 1rem;
        }

        .progress-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.75rem;
        }

        .progress-title {
            color: var(--text-primary);
            font-size: 0.9rem;
            font-weight: 600;
        }

        .progress-percentage {
            color: var(--accent-pink);
            font-size: 0.8rem;
            font-weight: 600;
        }

        .progress-bar {
            width: 100%;
            height: 8px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 4px;
            overflow: hidden;
            position: relative;
        }

        .progress-fill {
            height: 100%;
            background: var(--gradient-accent);
            border-radius: 4px;
            transition: width 0.8s ease;
            position: relative;
        }

        .progress-fill::after {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 20px;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3));
            animation: shimmer 2s infinite;
        }

        @keyframes shimmer {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        .progress-info {
            display: flex;
            justify-content: space-between;
            margin-top: 0.5rem;
            font-size: 0.8rem;
        }

        .current-xp {
            color: var(--text-secondary);
        }

        .next-league {
            color: var(--accent-cyan);
        }

        /* Achievements Section */
        .league-achievements {
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .achievements-header {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.75rem;
        }

        .achievements-title {
            color: var(--text-primary);
            font-size: 0.9rem;
            font-weight: 600;
        }

        .achievements-count {
            background: rgba(0, 212, 255, 0.1);
            color: var(--accent-cyan);
            padding: 0.15rem 0.5rem;
            border-radius: 8px;
            font-size: 0.7rem;
            font-weight: 600;
        }

        .achievements-list {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .achievement-badge {
            width: 24px;
            height: 24px;
            background: var(--gradient-button);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 0.7rem;
            transition: all 0.3s ease;
        }

        .achievement-badge:hover {
            transform: scale(1.1);
            box-shadow: 0 4px 15px rgba(255, 107, 157, 0.3);
        }

        /* No League State */
        .no-league {
            text-align: center;
            padding: 2rem;
            color: var(--text-secondary);
        }

        .no-league-icon {
            width: 60px;
            height: 60px;
            background: var(--gradient-card);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 1.5rem;
            color: var(--text-muted);
        }

        .no-league h3 {
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .league-info-horizontal {
                flex-direction: column;
                text-align: center;
                gap: 1rem;
            }

            .league-header {
                flex-direction: column;
                align-items: center;
                gap: 0.5rem;
            }

            .league-stats {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 480px) {
            .league-info {
                padding: 1rem;
            }

            .league-stats {
                grid-template-columns: 1fr;
            }
        }
    </style>

    @if($currentLeague)
        <!-- League Icon -->
        <div class="league-icon {{ strtolower($currentLeague->name) }}">
            @if($currentLeague->icon)
                <img src="{{ asset('images/leagues/' . $currentLeague->icon) }}" alt="{{ $currentLeague->name }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
            @else
                <i class="fas fa-trophy"></i>
            @endif
        </div>

        <!-- League Content -->
        <div class="league-content">
            <!-- League Header -->
            <div class="league-header">
                <h3 class="league-name">{{ $currentLeague->name }}</h3>
                <span class="league-rank">
                    @php
                        $leagueRank = \App\Models\League::where('min_experience', '<=', $currentLeague->min_experience)->count();
                    @endphp
                    League {{ $leagueRank }}
                </span>
            </div>

            @if($showDetails && $currentLeague->description)
                <p class="league-description">{{ $currentLeague->description }}</p>
            @endif

            @if($showDetails)
                <!-- League Stats -->
                <div class="league-stats">
                    <div class="stat-item">
                        <span class="stat-value">{{ number_format($totalExperience) }}</span>
                        <span class="stat-label">Total XP</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-value">{{ number_format($currentLeague->min_experience) }}</span>
                        <span class="stat-label">Min XP</span>
                    </div>
                    @if($currentLeague->max_experience)
                        <div class="stat-item">
                            <span class="stat-value">{{ number_format($currentLeague->max_experience) }}</span>
                            <span class="stat-label">Max XP</span>
                        </div>
                    @endif
                    @if($user)
                        <div class="stat-item">
                            <span class="stat-value">{{ $user->profile->total_competitions ?? 0 }}</span>
                            <span class="stat-label">Competitions</span>
                        </div>
                    @endif
                </div>
            @endif

            @if($showProgress)
                <!-- Progress to Next League -->
                <div class="league-progress">
                    @if($nextLeague)
                        <div class="progress-header">
                            <span class="progress-title">Progress to {{ $nextLeague->name }}</span>
                            <span class="progress-percentage">{{ round($progressPercentage) }}%</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: {{ $progressPercentage }}%"></div>
                        </div>
                        <div class="progress-info">
                            <span class="current-xp">{{ number_format($totalExperience) }} XP</span>
                            <span class="next-league">{{ number_format($experienceToNext) }} XP to {{ $nextLeague->name }}</span>
                        </div>
                    @else
                        <div class="progress-header">
                            <span class="progress-title">🏆 Highest League Achieved!</span>
                            <span class="progress-percentage">MAX</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 100%"></div>
                        </div>
                        <div class="progress-info">
                            <span class="current-xp">{{ number_format($totalExperience) }} XP</span>
                            <span class="next-league">Legend Status</span>
                        </div>
                    @endif
                </div>
            @endif

            @if($showDetails && $user && $user->badges->count() > 0)
                <!-- Achievements -->
                <div class="league-achievements">
                    <div class="achievements-header">
                        <span class="achievements-title">Recent Badges</span>
                        <span class="achievements-count">{{ $user->badges->count() }}</span>
                    </div>
                    <div class="achievements-list">
                        @foreach($user->badges->take(6) as $badge)
                            <div class="achievement-badge" title="{{ $badge->name }}">
                                @if($badge->icon)
                                    <img src="{{ asset('images/badges/' . $badge->icon) }}" alt="{{ $badge->name }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                                @else
                                    <i class="fas fa-medal"></i>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    @else
        <!-- No League State -->
        <div class="no-league">
            <div class="no-league-icon">
                <i class="fas fa-trophy"></i>
            </div>
            <h3>No League Yet</h3>
            <p>Start practicing to earn experience and join your first league!</p>
        </div>
    @endif
</div>