{{-- resources/views/badges/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Badges Collection - SportTyping')

@section('content')
<div class="badges-container">
    <div class="container">
        <!-- Header Section -->
        <div class="badges-header">
            <div class="header-content">
                <h1 class="page-title">
                    <i class="fas fa-medal"></i>
                    Badge Collection
                </h1>
                <p class="page-subtitle">
                    Earn badges by completing typing challenges and achieving milestones. 
                    Show off your skills and track your progress!
                </p>
            </div>
            
            <div class="collection-stats">
                <div class="stat-item">
                    <div class="stat-value">{{ $userBadges->count() }}</div>
                    <div class="stat-label">Earned</div>
                </div>
                <div class="stat-divider"></div>
                <div class="stat-item">
                    <div class="stat-value">{{ $allBadges->count() }}</div>
                    <div class="stat-label">Total</div>
                </div>
                <div class="stat-divider"></div>
                <div class="stat-item">
                    <div class="stat-value">{{ round(($userBadges->count() / $allBadges->count()) * 100) }}%</div>
                    <div class="stat-label">Complete</div>
                </div>
            </div>
        </div>

        <!-- Filter Tabs -->
        <div class="filter-tabs">
            <button class="filter-tab active" data-filter="all">
                <i class="fas fa-th"></i>
                All Badges
            </button>
            <button class="filter-tab" data-filter="earned">
                <i class="fas fa-trophy"></i>
                Earned ({{ $userBadges->count() }})
            </button>
            <button class="filter-tab" data-filter="locked">
                <i class="fas fa-lock"></i>
                Locked ({{ $allBadges->count() - $userBadges->count() }})
            </button>
            <button class="filter-tab" data-filter="speed">
                <i class="fas fa-tachometer-alt"></i>
                Speed
            </button>
            <button class="filter-tab" data-filter="accuracy">
                <i class="fas fa-bullseye"></i>
                Accuracy
            </button>
            <button class="filter-tab" data-filter="achievement">
                <i class="fas fa-star"></i>
                Achievement
            </button>
        </div>

        <!-- Badge Categories -->
        <div class="badge-categories">
            <!-- Speed Badges -->
            <div class="badge-category" data-category="speed">
                <h3 class="category-title">
                    <i class="fas fa-tachometer-alt"></i>
                    Speed Badges
                </h3>
                <p class="category-description">Achieve different typing speeds to unlock these badges</p>
                
                <div class="badge-grid">
                    @foreach($allBadges->where('category', 'speed') as $badge)
                        @php
                            $userBadge = $userBadges->firstWhere('badge_id', $badge->id);
                            $isEarned = $userBadge !== null;
                            $progress = 0;
                            
                            if (!$isEarned && isset($userStats)) {
                                if ($badge->requirement_type === 'wpm') {
                                    $progress = min(100, ($userStats['best_wpm'] / $badge->requirement_value) * 100);
                                }
                            }
                        @endphp
                        
                        <x-badge-display 
                            :badge="$badge" 
                            :earned="$isEarned"
                            :showProgress="!$isEarned && $progress > 0"
                            :progress="$progress"
                            :interactive="true"
                            size="default"
                            class="badge-item"
                            data-earned="{{ $isEarned ? 'true' : 'false' }}"
                            data-category="speed"
                        >
                            @if($isEarned && $userBadge)
                                <div class="earned-date">
                                    Earned {{ $userBadge->created_at->diffForHumans() }}
                                </div>
                            @endif
                        </x-badge-display>
                    @endforeach
                </div>
            </div>

            <!-- Accuracy Badges -->
            <div class="badge-category" data-category="accuracy">
                <h3 class="category-title">
                    <i class="fas fa-bullseye"></i>
                    Accuracy Badges
                </h3>
                <p class="category-description">Perfect your typing accuracy to earn these prestigious badges</p>
                
                <div class="badge-grid">
                    @foreach($allBadges->where('category', 'accuracy') as $badge)
                        @php
                            $userBadge = $userBadges->firstWhere('badge_id', $badge->id);
                            $isEarned = $userBadge !== null;
                            $progress = 0;
                            
                            if (!$isEarned && isset($userStats)) {
                                if ($badge->requirement_type === 'accuracy') {
                                    $progress = min(100, ($userStats['best_accuracy'] / $badge->requirement_value) * 100);
                                }
                            }
                        @endphp
                        
                        <x-badge-display 
                            :badge="$badge" 
                            :earned="$isEarned"
                            :showProgress="!$isEarned && $progress > 0"
                            :progress="$progress"
                            :interactive="true"
                            size="default"
                            class="badge-item"
                            data-earned="{{ $isEarned ? 'true' : 'false' }}"
                            data-category="accuracy"
                        >
                            @if($isEarned && $userBadge)
                                <div class="earned-date">
                                    Earned {{ $userBadge->created_at->diffForHumans() }}
                                </div>
                            @endif
                        </x-badge-display>
                    @endforeach
                </div>
            </div>

            <!-- Achievement Badges -->
            <div class="badge-category" data-category="achievement">
                <h3 class="category-title">
                    <i class="fas fa-star"></i>
                    Achievement Badges
                </h3>
                <p class="category-description">Special badges for milestones and accomplishments</p>
                
                <div class="badge-grid">
                    @foreach($allBadges->where('category', 'achievement') as $badge)
                        @php
                            $userBadge = $userBadges->firstWhere('badge_id', $badge->id);
                            $isEarned = $userBadge !== null;
                            $progress = 0;
                            
                            // Calculate progress based on different requirement types
                            if (!$isEarned && isset($userStats)) {
                                switch ($badge->requirement_type) {
                                    case 'practices_completed':
                                        $progress = min(100, ($userStats['total_practices'] / $badge->requirement_value) * 100);
                                        break;
                                    case 'competitions_won':
                                        $progress = min(100, ($userStats['competitions_won'] / $badge->requirement_value) * 100);
                                        break;
                                    case 'consecutive_days':
                                        $progress = min(100, ($userStats['consecutive_days'] / $badge->requirement_value) * 100);
                                        break;
                                }
                            }
                        @endphp
                        
                        <x-badge-display 
                            :badge="$badge" 
                            :earned="$isEarned"
                            :showProgress="!$isEarned && $progress > 0"
                            :progress="$progress"
                            :interactive="true"
                            size="default"
                            class="badge-item"
                            data-earned="{{ $isEarned ? 'true' : 'false' }}"
                            data-category="achievement"
                        >
                            @if($isEarned && $userBadge)
                                <div class="earned-date">
                                    Earned {{ $userBadge->created_at->diffForHumans() }}
                                </div>
                            @endif
                        </x-badge-display>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Recent Badges -->
        @if($recentBadges && $recentBadges->count() > 0)
        <div class="recent-badges-section">
            <h3 class="section-title">
                <i class="fas fa-clock"></i>
                Recently Earned
            </h3>
            
            <div class="recent-badges-grid">
                @foreach($recentBadges as $userBadge)
                    <div class="recent-badge-item">
                        <x-badge-display 
                            :badge="$userBadge->badge" 
                            :earned="true"
                            :interactive="true"
                            size="small"
                            :showDetails="false"
                        />
                        <div class="recent-badge-info">
                            <div class="badge-name">{{ $userBadge->badge->name }}</div>
                            <div class="earned-time">{{ $userBadge->created_at->diffForHumans() }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Progress Motivation -->
        @if($nextBadges && $nextBadges->count() > 0)
        <div class="motivation-section">
            <h3 class="section-title">
                <i class="fas fa-target"></i>
                Almost There!
            </h3>
            <p class="section-description">You're close to earning these badges. Keep practicing!</p>
            
            <div class="next-badges-grid">
                @foreach($nextBadges as $badge)
                    @php
                        $progress = 0;
                        $requirement = '';
                        
                        if (isset($userStats)) {
                            switch ($badge->requirement_type) {
                                case 'wpm':
                                    $progress = min(100, ($userStats['best_wpm'] / $badge->requirement_value) * 100);
                                    $requirement = ($badge->requirement_value - $userStats['best_wpm']) . ' more WPM needed';
                                    break;
                                case 'accuracy':
                                    $progress = min(100, ($userStats['best_accuracy'] / $badge->requirement_value) * 100);
                                    $requirement = number_format($badge->requirement_value - $userStats['best_accuracy'], 1) . '% more accuracy needed';
                                    break;
                                case 'practices_completed':
                                    $progress = min(100, ($userStats['total_practices'] / $badge->requirement_value) * 100);
                                    $requirement = ($badge->requirement_value - $userStats['total_practices']) . ' more practices needed';
                                    break;
                            }
                        }
                    @endphp
                    
                    <div class="next-badge-card">
                        <x-badge-display 
                            :badge="$badge" 
                            :earned="false"
                            :showProgress="true"
                            :progress="$progress"
                            :interactive="true"
                            size="default"
                            :showDetails="false"
                        />
                        <div class="next-badge-info">
                            <div class="badge-name">{{ $badge->name }}</div>
                            <div class="requirement-text">{{ $requirement }}</div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: {{ $progress }}%"></div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

<style>
.badges-container {
    min-height: calc(100vh - 80px);
    background: var(--bg-secondary);
    padding: 2rem 0;
}

.badges-header {
    text-align: center;
    margin-bottom: 3rem;
    padding: 2rem;
    background: var(--bg-card);
    border-radius: var(--border-radius-lg);
    box-shadow: var(--shadow-sm);
}

.header-content {
    margin-bottom: 2rem;
}

.page-title {
    font-family: var(--font-display);
    font-size: 3rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1rem;
}

.page-title i {
    color: var(--accent-secondary);
}

.page-subtitle {
    color: var(--text-secondary);
    font-size: 1.1rem;
    max-width: 600px;
    margin: 0 auto;
    line-height: 1.6;
}

.collection-stats {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 2rem;
    padding: 1.5rem;
    background: var(--bg-secondary);
    border-radius: var(--border-radius);
    border: 1px solid var(--border-light);
}

.stat-item {
    text-align: center;
}

.stat-value {
    font-family: var(--font-display);
    font-size: 2rem;
    font-weight: 700;
    color: var(--accent-primary);
}

.stat-label {
    color: var(--text-secondary);
    font-size: 0.9rem;
    font-weight: 500;
    margin-top: 0.25rem;
}

.stat-divider {
    width: 1px;
    height: 40px;
    background: var(--border-light);
}

.filter-tabs {
    display: flex;
    justify-content: center;
    gap: 0.5rem;
    margin-bottom: 3rem;
    flex-wrap: wrap;
}

.filter-tab {
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    color: var(--text-secondary);
    padding: 0.75rem 1.5rem;
    border-radius: var(--border-radius);
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 500;
}

.filter-tab:hover {
    background: var(--bg-secondary);
    color: var(--text-primary);
}

.filter-tab.active {
    background: var(--champion-gradient);
    color: white;
    border-color: transparent;
}

.badge-categories {
    display: flex;
    flex-direction: column;
    gap: 3rem;
}

.badge-category {
    background: var(--bg-card);
    border-radius: var(--border-radius-lg);
    padding: 2rem;
    box-shadow: var(--shadow-sm);
}

.category-title {
    font-family: var(--font-display);
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.category-description {
    color: var(--text-secondary);
    margin-bottom: 2rem;
}

.badge-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
    gap: 2rem;
}

.badge-item {
    transition: all 0.3s ease;
}

.badge-item[data-earned="false"] {
    opacity: 0.6;
}

.earned-date {
    color: var(--text-muted);
    font-size: 0.8rem;
    margin-top: 0.5rem;
}

.section-title {
    font-family: var(--font-display);
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.section-description {
    color: var(--text-secondary);
    margin-bottom: 2rem;
}

.recent-badges-section {
    background: var(--bg-card);
    border-radius: var(--border-radius-lg);
    padding: 2rem;
    margin: 3rem 0;
    box-shadow: var(--shadow-sm);
}

.recent-badges-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 1.5rem;
}

.recent-badge-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: var(--bg-secondary);
    border-radius: var(--border-radius);
    transition: all 0.3s ease;
}

.recent-badge-item:hover {
    background: var(--bg-tertiary);
    transform: translateY(-2px);
}

.recent-badge-info {
    flex: 1;
}

.recent-badge-info .badge-name {
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 0.25rem;
}

.earned-time {
    color: var(--text-secondary);
    font-size: 0.9rem;
}

.motivation-section {
    background: var(--bg-card);
    border-radius: var(--border-radius-lg);
    padding: 2rem;
    margin: 3rem 0;
    box-shadow: var(--shadow-sm);
}

.next-badges-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 2rem;
}

.next-badge-card {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    padding: 1.5rem;
    background: var(--bg-secondary);
    border-radius: var(--border-radius);
    border: 2px solid transparent;
    transition: all 0.3s ease;
}

.next-badge-card:hover {
    border-color: var(--accent-primary);
    transform: translateY(-2px);
}

.next-badge-info {
    flex: 1;
}

.next-badge-info .badge-name {
    font-family: var(--font-display);
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 0.5rem;
}

.requirement-text {
    color: var(--text-secondary);
    font-size: 0.9rem;
    margin-bottom: 1rem;
}

.progress-bar {
    width: 100%;
    height: 6px;
    background: var(--border-light);
    border-radius: var(--border-radius);
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    background: var(--champion-gradient);
    border-radius: var(--border-radius);
    transition: width 0.5s ease;
}

/* Responsive Design */
@media (max-width: 1024px) {
    .collection-stats {
        gap: 1rem;
    }
    
    .badge-grid {
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 1.5rem;
    }
}

@media (max-width: 768px) {
    .badges-container {
        padding: 1rem 0;
    }
    
    .badges-header {
        padding: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .page-title {
        font-size: 2rem;
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .collection-stats {
        flex-direction: column;
        gap: 1rem;
    }
    
    .stat-divider {
        width: 40px;
        height: 1px;
    }
    
    .filter-tabs {
        gap: 0.25rem;
    }
    
    .filter-tab {
        padding: 0.5rem 1rem;
        font-size: 0.9rem;
    }
    
    .badge-category {
        padding: 1.5rem;
    }
    
    .badge-grid {
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
    }
    
    .recent-badges-grid {
        grid-template-columns: 1fr;
    }
    
    .recent-badge-item {
        flex-direction: column;
        text-align: center;
    }
    
    .next-badges-grid {
        grid-template-columns: 1fr;
    }
    
    .next-badge-card {
        flex-direction: column;
        text-align: center;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filter functionality
    const filterTabs = document.querySelectorAll('.filter-tab');
    const badgeItems = document.querySelectorAll('.badge-item');
    const badgeCategories = document.querySelectorAll('.badge-category');
    
    filterTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            // Update active tab
            filterTabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            
            const filter = this.dataset.filter;
            
            // Show/hide badge items and categories
            badgeCategories.forEach(category => {
                const categoryType = category.dataset.category;
                
                if (filter === 'all') {
                    category.style.display = 'block';
                    category.querySelectorAll('.badge-item').forEach(item => {
                        item.style.display = 'flex';
                    });
                } else if (filter === 'earned') {
                    category.style.display = 'block';
                    category.querySelectorAll('.badge-item').forEach(item => {
                        item.style.display = item.dataset.earned === 'true' ? 'flex' : 'none';
                    });
                } else if (filter === 'locked') {
                    category.style.display = 'block';
                    category.querySelectorAll('.badge-item').forEach(item => {
                        item.style.display = item.dataset.earned === 'false' ? 'flex' : 'none';
                    });
                } else if (filter === categoryType) {
                    category.style.display = 'block';
                    category.querySelectorAll('.badge-item').forEach(item => {
                        item.style.display = 'flex';
                    });
                } else {
                    category.style.display = 'none';
                }
            });
        });
    });
    
    // Add animation on scroll
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.animation = 'fade-in-up 0.6s ease-out forwards';
            }
        });
    });
    
    badgeCategories.forEach(category => {
        observer.observe(category);
    });
});
</script>
@endsection