@extends('layouts.app')

@section('content')
<div class="competitions-container">
    <div class="container">
        <!-- Page Header -->
        <div class="page-header">
            <div class="header-content">
                <h1 class="page-title">
                    <i class="fas fa-racing-flag"></i>
                    Competitions
                </h1>
                <p class="page-subtitle">
                    Join real-time typing competitions and compete with players worldwide
                </p>
            </div>
            <div class="header-actions">
                <a href="{{ route('practice.index') }}" class="btn btn-outline-primary">
                    <i class="fas fa-keyboard"></i>
                    Practice First
                </a>
            </div>
        </div>

        <!-- Competition Filters -->
        <div class="competition-filters">
            <div class="filter-tabs">
                <button class="filter-tab active" data-filter="all">
                    <i class="fas fa-globe"></i>
                    All Competitions
                </button>
                <button class="filter-tab" data-filter="active">
                    <i class="fas fa-play-circle"></i>
                    Live Now
                </button>
                <button class="filter-tab" data-filter="upcoming">
                    <i class="fas fa-clock"></i>
                    Upcoming
                </button>
                <button class="filter-tab" data-filter="mobile">
                    <i class="fas fa-mobile-alt"></i>
                    Mobile Only
                </button>
                <button class="filter-tab" data-filter="pc">
                    <i class="fas fa-desktop"></i>
                    PC Only
                </button>
            </div>
        </div>

        <!-- Active Competitions -->
        @if($activeCompetitions->count() > 0)
        <div class="competitions-section active-section">
            <div class="section-header">
                <h2 class="section-title">
                    <span class="live-indicator">LIVE</span>
                    Active Competitions
                </h2>
                <div class="section-count">{{ $activeCompetitions->count() }} active</div>
            </div>
            
            <div class="competitions-grid">
                @foreach($activeCompetitions as $competition)
                <div class="competition-card active-card" data-device="{{ $competition->device_type }}">
                    <div class="card-header">
                        <div class="competition-status live">
                            <i class="fas fa-broadcast-tower"></i>
                            LIVE
                        </div>
                        <div class="device-badge {{ $competition->device_type }}">
                            <i class="fas fa-{{ $competition->device_type == 'mobile' ? 'mobile-alt' : ($competition->device_type == 'pc' ? 'desktop' : 'globe') }}"></i>
                            {{ ucfirst($competition->device_type) }}
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <h3 class="competition-title">{{ $competition->title }}</h3>
                        <p class="competition-description">{{ $competition->description }}</p>
                        
                        <div class="competition-stats">
                            <div class="stat-item">
                                <i class="fas fa-users"></i>
                                <span>{{ $competition->participants_count }}/{{ $competition->max_participants }}</span>
                            </div>
                            <div class="stat-item">
                                <i class="fas fa-clock"></i>
                                <span>{{ $competition->duration }}m</span>
                            </div>
                            <div class="stat-item">
                                <i class="fas fa-file-text"></i>
                                <span>{{ $competition->text->word_count }} words</span>
                            </div>
                        </div>
                        
                        <div class="race-preview">
                            <div class="race-track">
                                @for($i = 1; $i <= min(6, $competition->participants_count); $i++)
                                <div class="racer" style="--progress: {{ rand(20, 80) }}%">
                                    <img src="/image/ui/mobil{{ rand(1,4) }}.svg" alt="Racer {{ $i }}">
                                </div>
                                @endfor
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-footer">
                        <div class="prize-info">
                            <i class="fas fa-trophy"></i>
                            <span>{{ $competition->experience_reward }} EXP</span>
                        </div>
                        <a href="{{ route('competitions.show', $competition) }}" class="btn btn-primary btn-join">
                            <i class="fas fa-racing-flag"></i>
                            Join Race
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Upcoming Competitions -->
        @if($upcomingCompetitions->count() > 0)
        <div class="competitions-section upcoming-section">
            <div class="section-header">
                <h2 class="section-title">
                    <i class="fas fa-calendar-alt"></i>
                    Upcoming Competitions
                </h2>
                <div class="section-count">{{ $upcomingCompetitions->count() }} scheduled</div>
            </div>
            
            <div class="competitions-grid">
                @foreach($upcomingCompetitions as $competition)
                <div class="competition-card upcoming-card" data-device="{{ $competition->device_type }}">
                    <div class="card-header">
                        <div class="competition-status upcoming">
                            <i class="fas fa-clock"></i>
                            {{ $competition->start_time->diffForHumans() }}
                        </div>
                        <div class="device-badge {{ $competition->device_type }}">
                            <i class="fas fa-{{ $competition->device_type == 'mobile' ? 'mobile-alt' : ($competition->device_type == 'pc' ? 'desktop' : 'globe') }}"></i>
                            {{ ucfirst($competition->device_type) }}
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <h3 class="competition-title">{{ $competition->title }}</h3>
                        <p class="competition-description">{{ $competition->description }}</p>
                        
                        <div class="competition-info">
                            <div class="info-row">
                                <i class="fas fa-calendar"></i>
                                <span>{{ $competition->start_time->format('M j, Y') }}</span>
                            </div>
                            <div class="info-row">
                                <i class="fas fa-clock"></i>
                                <span>{{ $competition->start_time->format('H:i') }} - {{ $competition->duration }}m duration</span>
                            </div>
                            <div class="info-row">
                                <i class="fas fa-users"></i>
                                <span>{{ $competition->participants_count }}/{{ $competition->max_participants }} registered</span>
                            </div>
                        </div>
                        
                        <div class="difficulty-indicator">
                            <span class="difficulty-label">Difficulty:</span>
                            <div class="difficulty-bars">
                                @for($i = 1; $i <= 5; $i++)
                                <div class="difficulty-bar {{ $i <= (($competition->text->difficulty_level == 'beginner') ? 2 : (($competition->text->difficulty_level == 'intermediate') ? 3 : 5)) ? 'active' : '' }}"></div>
                                @endfor
                            </div>
                            <span class="difficulty-text">{{ ucfirst($competition->text->difficulty_level) }}</span>
                        </div>
                    </div>
                    
                    <div class="card-footer">
                        <div class="prize-info">
                            <i class="fas fa-star"></i>
                            <span>{{ $competition->experience_reward }} EXP</span>
                        </div>
                        <a href="{{ route('competitions.show', $competition) }}" class="btn btn-outline-primary btn-register">
                            <i class="fas fa-user-plus"></i>
                            Register
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Empty State -->
        @if($activeCompetitions->count() == 0 && $upcomingCompetitions->count() == 0)
        <div class="empty-state">
            <div class="empty-content">
                <div class="empty-icon">
                    <i class="fas fa-racing-flag"></i>
                </div>
                <h3>No competitions available</h3>
                <p>Check back later for new competitions or practice while you wait!</p>
                <a href="{{ route('practice.index') }}" class="btn btn-primary">
                    <i class="fas fa-keyboard"></i>
                    Start Practicing
                </a>
            </div>
        </div>
        @endif
    </div>
</div>

<style>
.competitions-container {
    background: var(--bg-primary);
    min-height: calc(100vh - 120px);
    padding: 2rem 0;
}

/* Page Header */
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    padding-bottom: 2rem;
    border-bottom: 2px solid var(--border-light);
}

.page-title {
    font-family: var(--font-display);
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.page-title i {
    background: var(--champion-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.page-subtitle {
    color: var(--text-secondary);
    font-size: 1.1rem;
    margin: 0;
}

/* Competition Filters */
.competition-filters {
    margin-bottom: 3rem;
}

.filter-tabs {
    display: flex;
    gap: 1rem;
    overflow-x: auto;
    padding-bottom: 0.5rem;
}

.filter-tab {
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: var(--border-radius);
    padding: 0.75rem 1.5rem;
    color: var(--text-secondary);
    font-weight: 500;
    transition: all 0.3s ease;
    white-space: nowrap;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.filter-tab:hover,
.filter-tab.active {
    background: var(--accent-primary);
    color: white;
    border-color: var(--accent-primary);
    transform: translateY(-2px);
}

/* Competitions Section */
.competitions-section {
    margin-bottom: 3rem;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.section-title {
    font-family: var(--font-display);
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--text-primary);
    display: flex;
    align-items: center;
    gap: 1rem;
}

.live-indicator {
    background: var(--accent-danger);
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: var(--border-radius);
    font-size: 0.8rem;
    font-weight: 600;
    animation: pulse 2s infinite;
}

.section-count {
    background: var(--bg-card);
    color: var(--text-secondary);
    padding: 0.5rem 1rem;
    border-radius: var(--border-radius);
    font-size: 0.9rem;
    border: 1px solid var(--border-light);
}

/* Competition Cards */
.competitions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 2rem;
}

.competition-card {
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: var(--border-radius-lg);
    overflow: hidden;
    transition: all 0.3s ease;
    position: relative;
}

.competition-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 40px rgba(0,0,0,0.1);
    border-color: var(--accent-primary);
}

.active-card {
    border-color: var(--accent-danger);
    box-shadow: 0 0 20px rgba(220, 38, 38, 0.1);
}

.active-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: var(--champion-gradient);
}

.card-header {
    padding: 1.5rem;
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.05), rgba(16, 185, 129, 0.05));
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.competition-status {
    padding: 0.5rem 1rem;
    border-radius: var(--border-radius);
    font-weight: 600;
    font-size: 0.85rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.competition-status.live {
    background: var(--accent-danger);
    color: white;
    animation: pulse 2s infinite;
}

.competition-status.upcoming {
    background: var(--accent-warning);
    color: white;
}

.device-badge {
    padding: 0.5rem 1rem;
    border-radius: var(--border-radius);
    font-size: 0.8rem;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.device-badge.mobile {
    background: linear-gradient(135deg, var(--accent-purple), var(--accent-primary));
    color: white;
}

.device-badge.pc {
    background: linear-gradient(135deg, var(--accent-success), var(--accent-info));
    color: white;
}

.device-badge.both {
    background: var(--medal-gradient);
    color: white;
}

.card-body {
    padding: 1.5rem;
}

.competition-title {
    font-family: var(--font-display);
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 0.75rem;
}

.competition-description {
    color: var(--text-secondary);
    line-height: 1.6;
    margin-bottom: 1.5rem;
}

.competition-stats {
    display: flex;
    gap: 1.5rem;
    margin-bottom: 1.5rem;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--text-secondary);
    font-size: 0.9rem;
}

.stat-item i {
    color: var(--accent-primary);
}

/* Race Preview */
.race-preview {
    margin-bottom: 1rem;
}

.race-track {
    background: linear-gradient(90deg, #f3f4f6, #e5e7eb);
    border-radius: var(--border-radius);
    padding: 1rem;
    position: relative;
    height: 60px;
    overflow: hidden;
}

.racer {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    left: var(--progress);
    transition: left 0.3s ease;
}

.racer img {
    width: 24px;
    height: 24px;
    filter: hue-rotate(calc(var(--progress) * 3.6deg));
}

/* Competition Info */
.competition-info {
    margin-bottom: 1.5rem;
}

.info-row {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 0.5rem;
    color: var(--text-secondary);
    font-size: 0.9rem;
}

.info-row i {
    color: var(--accent-primary);
    width: 16px;
}

/* Difficulty Indicator */
.difficulty-indicator {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.difficulty-label {
    font-size: 0.9rem;
    color: var(--text-secondary);
}

.difficulty-bars {
    display: flex;
    gap: 2px;
}

.difficulty-bar {
    width: 8px;
    height: 16px;
    background: var(--border-light);
    border-radius: 2px;
}

.difficulty-bar.active {
    background: var(--medal-gradient);
}

.difficulty-text {
    font-size: 0.9rem;
    font-weight: 500;
    color: var(--text-primary);
}

.card-footer {
    padding: 1.5rem;
    background: var(--bg-secondary);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.prize-info {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--accent-warning);
    font-weight: 600;
}

.btn-join,
.btn-register {
    padding: 0.75rem 1.5rem;
    font-weight: 600;
    border-radius: var(--border-radius);
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 4rem 2rem;
}

.empty-icon {
    font-size: 4rem;
    color: var(--text-muted);
    margin-bottom: 1.5rem;
}

.empty-content h3 {
    font-family: var(--font-display);
    font-size: 1.5rem;
    color: var(--text-primary);
    margin-bottom: 1rem;
}

.empty-content p {
    color: var(--text-secondary);
    font-size: 1.1rem;
    margin-bottom: 2rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .page-header {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
    
    .competitions-grid {
        grid-template-columns: 1fr;
    }
    
    .competition-stats {
        flex-wrap: wrap;
        gap: 1rem;
    }
    
    .filter-tabs {
        justify-content: center;
    }
    
    .section-header {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.7; }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filter functionality
    const filterTabs = document.querySelectorAll('.filter-tab');
    const competitionCards = document.querySelectorAll('.competition-card');
    
    filterTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            // Remove active class from all tabs
            filterTabs.forEach(t => t.classList.remove('active'));
            // Add active class to clicked tab
            this.classList.add('active');
            
            const filter = this.dataset.filter;
            
            competitionCards.forEach(card => {
                if (filter === 'all') {
                    card.style.display = 'block';
                } else if (filter === 'active') {
                    card.style.display = card.classList.contains('active-card') ? 'block' : 'none';
                } else if (filter === 'upcoming') {
                    card.style.display = card.classList.contains('upcoming-card') ? 'block' : 'none';
                } else {
                    card.style.display = card.dataset.device === filter || card.dataset.device === 'both' ? 'block' : 'none';
                }
            });
        });
    });
    
    // Real-time updates for live competitions
    setInterval(function() {
        const raceTracks = document.querySelectorAll('.race-track');
        raceTracks.forEach(track => {
            const racers = track.querySelectorAll('.racer');
            racers.forEach(racer => {
                const currentProgress = parseFloat(racer.style.getPropertyValue('--progress')) || 0;
                const newProgress = Math.min(95, currentProgress + Math.random() * 2);
                racer.style.setProperty('--progress', newProgress + '%');
            });
        });
    }, 2000);
});
</script>
@endsection