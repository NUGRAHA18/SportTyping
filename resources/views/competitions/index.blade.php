@extends('layouts.app')

@section('content')
<div class="competitions-container">
    <div class="container">
        <!-- Header Section -->
        <div class="competitions-header">
            <div class="header-content">
                <h1>Typing Competitions</h1>
                <p>Join real-time competitions and race against other typists worldwide</p>
            </div>
            <div class="header-stats">
                <div class="stat-item">
                    <span class="stat-number">{{ $activeCompetitions->count() }}</span>
                    <span class="stat-label">Active Now</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">{{ $upcomingCompetitions->count() }}</span>
                    <span class="stat-label">Upcoming</span>
                </div>
            </div>
        </div>

        <!-- Active Competitions -->
        @if($activeCompetitions->count() > 0)
        <div class="competitions-section">
            <div class="section-header">
                <h2><i class="fas fa-racing-flag"></i> Active Competitions</h2>
                <div class="live-indicator">
                    <span class="live-dot"></span>
                    LIVE
                </div>
            </div>

            <div class="competitions-grid">
                @foreach($activeCompetitions as $competition)
                <div class="competition-card active">
                    <div class="card-header">
                        <div class="competition-info">
                            <h3>{{ $competition->title }}</h3>
                            <p>{{ $competition->description ?? 'Test your typing skills in this exciting competition!' }}</p>
                        </div>
                        <div class="device-badge {{ $competition->device_type }}">
                            <i class="fas fa-{{ $competition->device_type == 'mobile' ? 'mobile-alt' : ($competition->device_type == 'pc' ? 'desktop' : 'laptop') }}"></i>
                            {{ ucfirst($competition->device_type) }}
                        </div>
                    </div>

                    <div class="competition-details">
                        <div class="detail-item">
                            <i class="fas fa-users"></i>
                            <span>{{ $competition->participants->count() }} participants</span>
                        </div>
                        <div class="detail-item">
                            <i class="fas fa-clock"></i>
                            <span>Started {{ $competition->start_time->diffForHumans() }}</span>
                        </div>
                        <div class="detail-item">
                            <i class="fas fa-file-text"></i>
                            <span>{{ $competition->text->category->name ?? 'General' }}</span>
                        </div>
                        <div class="detail-item difficulty">
                            <i class="fas fa-chart-line"></i>
                            <span class="difficulty-{{ $competition->text->difficulty_level }}">
                                {{ ucfirst($competition->text->difficulty_level) }}
                            </span>
                        </div>
                    </div>

                    <div class="card-actions">
                        @if($competition->participants()->where('user_id', Auth::id())->exists())
                            <a href="{{ route('competitions.compete', $competition) }}" class="btn btn-primary btn-full">
                                <i class="fas fa-play"></i>
                                Continue Race
                            </a>
                        @else
                            <a href="{{ route('competitions.show', $competition) }}" class="btn btn-outline-primary btn-full">
                                <i class="fas fa-info-circle"></i>
                                View Details
                            </a>
                        @endif
                    </div>

                    <div class="competition-overlay">
                        <div class="racing-animation">
                            <div class="race-line"></div>
                            <div class="race-line"></div>
                            <div class="race-line"></div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Upcoming Competitions -->
        @if($upcomingCompetitions->count() > 0)
        <div class="competitions-section">
            <div class="section-header">
                <h2><i class="fas fa-calendar-alt"></i> Upcoming Competitions</h2>
                <div class="countdown-info">
                    <i class="fas fa-clock"></i>
                    Next in {{ $upcomingCompetitions->first()->start_time->diffForHumans() }}
                </div>
            </div>

            <div class="competitions-grid">
                @foreach($upcomingCompetitions as $competition)
                <div class="competition-card upcoming">
                    <div class="card-header">
                        <div class="competition-info">
                            <h3>{{ $competition->title }}</h3>
                            <p>{{ $competition->description ?? 'Test your typing skills in this exciting competition!' }}</p>
                        </div>
                        <div class="device-badge {{ $competition->device_type }}">
                            <i class="fas fa-{{ $competition->device_type == 'mobile' ? 'mobile-alt' : ($competition->device_type == 'pc' ? 'desktop' : 'laptop') }}"></i>
                            {{ ucfirst($competition->device_type) }}
                        </div>
                    </div>

                    <div class="competition-details">
                        <div class="detail-item">
                            <i class="fas fa-calendar"></i>
                            <span>{{ $competition->start_time->format('M j, Y') }}</span>
                        </div>
                        <div class="detail-item">
                            <i class="fas fa-clock"></i>
                            <span>{{ $competition->start_time->format('g:i A') }}</span>
                        </div>
                        <div class="detail-item">
                            <i class="fas fa-file-text"></i>
                            <span>{{ $competition->text->category->name ?? 'General' }}</span>
                        </div>
                        <div class="detail-item difficulty">
                            <i class="fas fa-chart-line"></i>
                            <span class="difficulty-{{ $competition->text->difficulty_level }}">
                                {{ ucfirst($competition->text->difficulty_level) }}
                            </span>
                        </div>
                    </div>

                    <div class="countdown-timer" data-start-time="{{ $competition->start_time->toISOString() }}">
                        <div class="timer-segment">
                            <span class="timer-number" data-days>0</span>
                            <span class="timer-label">Days</span>
                        </div>
                        <div class="timer-segment">
                            <span class="timer-number" data-hours>0</span>
                            <span class="timer-label">Hours</span>
                        </div>
                        <div class="timer-segment">
                            <span class="timer-number" data-minutes>0</span>
                            <span class="timer-label">Minutes</span>
                        </div>
                        <div class="timer-segment">
                            <span class="timer-number" data-seconds>0</span>
                            <span class="timer-label">Seconds</span>
                        </div>
                    </div>

                    <div class="card-actions">
                        <a href="{{ route('competitions.show', $competition) }}" class="btn btn-outline-primary btn-full">
                            <i class="fas fa-bell"></i>
                            Set Reminder
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
            <div class="empty-icon">
                <i class="fas fa-trophy"></i>
            </div>
            <h3>No Competitions Available</h3>
            <p>There are no competitions scheduled at the moment. Check back later or practice your typing skills!</p>
            <div class="empty-actions">
                <a href="{{ route('practice.index') }}" class="btn btn-primary">
                    <i class="fas fa-keyboard"></i>
                    Practice Typing
                </a>
                <a href="{{ route('lessons.index') }}" class="btn btn-outline-primary">
                    <i class="fas fa-graduation-cap"></i>
                    Take Lessons
                </a>
            </div>
        </div>
        @endif

        <!-- Competition Tips -->
        <div class="tips-section">
            <div class="tips-card">
                <div class="tips-header">
                    <i class="fas fa-lightbulb"></i>
                    <h3>Competition Tips</h3>
                </div>
                <div class="tips-grid">
                    <div class="tip-item">
                        <i class="fas fa-target"></i>
                        <div>
                            <h4>Focus on Accuracy</h4>
                            <p>High accuracy is more important than speed. Maintain 95%+ accuracy for better rankings.</p>
                        </div>
                    </div>
                    <div class="tip-item">
                        <i class="fas fa-hand-paper"></i>
                        <div>
                            <h4>Proper Posture</h4>
                            <p>Sit straight, feet flat on floor, wrists straight, and use all 10 fingers for optimal performance.</p>
                        </div>
                    </div>
                    <div class="tip-item">
                        <i class="fas fa-eye"></i>
                        <div>
                            <h4>Don't Look Down</h4>
                            <p>Keep your eyes on the screen, not the keyboard. Trust your muscle memory.</p>
                        </div>
                    </div>
                    <div class="tip-item">
                        <i class="fas fa-heartbeat"></i>
                        <div>
                            <h4>Stay Calm</h4>
                            <p>Take deep breaths and maintain a steady rhythm. Rushing leads to more errors.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.competitions-container {
    padding: 2rem 0;
    min-height: calc(100vh - 80px);
}

/* Header */
.competitions-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 3rem;
    padding: 2.5rem;
    background: var(--gradient-card);
    backdrop-filter: blur(var(--blur-amount));
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius-lg);
    position: relative;
    overflow: hidden;
}

.competitions-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: var(--gradient-accent);
}

.header-content h1 {
    font-size: 2.5rem;
    font-weight: 700;
    background: var(--gradient-accent);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: 0.5rem;
}

.header-content p {
    color: var(--text-secondary);
    font-size: 1.1rem;
}

.header-stats {
    display: flex;
    gap: 2rem;
}

.stat-item {
    text-align: center;
}

.stat-number {
    display: block;
    font-size: 2rem;
    font-weight: 700;
    color: var(--text-primary);
}

.stat-label {
    font-size: 0.9rem;
    color: var(--text-secondary);
}

/* Section Headers */
.competitions-section {
    margin-bottom: 3rem;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.section-header h2 {
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--text-primary);
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.live-indicator {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    background: rgba(239, 68, 68, 0.1);
    color: var(--error);
    padding: 0.5rem 1rem;
    border-radius: var(--border-radius);
    font-size: 0.85rem;
    font-weight: 600;
    border: 1px solid rgba(239, 68, 68, 0.2);
}

.live-dot {
    width: 8px;
    height: 8px;
    background: var(--error);
    border-radius: 50%;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { opacity: 1; }
    50% { opacity: 0.5; }
    100% { opacity: 1; }
}

.countdown-info {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--text-secondary);
    font-size: 0.9rem;
}

/* Competition Cards Grid */
.competitions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
    gap: 2rem;
}

.competition-card {
    background: var(--gradient-card);
    backdrop-filter: blur(var(--blur-amount));
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius-lg);
    padding: 2rem;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.competition-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 40px rgba(139, 92, 246, 0.2);
    border-color: var(--accent-pink);
}

.competition-card.active {
    background: linear-gradient(145deg, rgba(239, 68, 68, 0.05), rgba(255, 107, 157, 0.05));
    border-color: rgba(239, 68, 68, 0.3);
}

.competition-card.upcoming {
    background: linear-gradient(145deg, rgba(59, 130, 246, 0.05), rgba(139, 92, 246, 0.05));
    border-color: rgba(59, 130, 246, 0.3);
}

.card-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1.5rem;
}

.competition-info h3 {
    font-size: 1.3rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 0.5rem;
}

.competition-info p {
    color: var(--text-secondary);
    font-size: 0.95rem;
    line-height: 1.4;
}

.device-badge {
    padding: 0.5rem 1rem;
    border-radius: var(--border-radius);
    font-size: 0.85rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    white-space: nowrap;
}

.device-badge.mobile {
    background: rgba(245, 158, 11, 0.1);
    color: var(--warning);
    border: 1px solid rgba(245, 158, 11, 0.2);
}

.device-badge.pc {
    background: rgba(59, 130, 246, 0.1);
    color: var(--info);
    border: 1px solid rgba(59, 130, 246, 0.2);
}

.device-badge.both {
    background: rgba(16, 185, 129, 0.1);
    color: var(--success);
    border: 1px solid rgba(16, 185, 129, 0.2);
}

/* Competition Details */
.competition-details {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
    margin-bottom: 2rem;
}

.detail-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--text-secondary);
    font-size: 0.9rem;
}

.detail-item i {
    width: 16px;
    color: var(--accent-pink);
}

.difficulty-beginner { color: var(--success); }
.difficulty-intermediate { color: var(--warning); }
.difficulty-advanced { color: var(--error); }
.difficulty-expert { color: var(--accent-purple); }

/* Countdown Timer */
.countdown-timer {
    display: flex;
    justify-content: space-between;
    margin-bottom: 2rem;
    padding: 1.5rem;
    background: rgba(255, 255, 255, 0.03);
    border-radius: var(--border-radius);
    border: 1px solid rgba(255, 255, 255, 0.05);
}

.timer-segment {
    text-align: center;
}

.timer-number {
    display: block;
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text-primary);
}

.timer-label {
    font-size: 0.8rem;
    color: var(--text-secondary);
}

/* Card Actions */
.card-actions {
    margin-top: auto;
}

.btn-full {
    width: 100%;
    padding: 1rem;
    font-weight: 600;
}

/* Racing Animation */
.competition-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    pointer-events: none;
    opacity: 0.1;
}

.racing-animation {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 100px;
}

.race-line {
    position: absolute;
    left: -100%;
    width: 100px;
    height: 2px;
    background: var(--gradient-accent);
    animation: race 3s linear infinite;
}

.race-line:nth-child(2) {
    top: 33%;
    animation-delay: 1s;
}

.race-line:nth-child(3) {
    top: 66%;
    animation-delay: 2s;
}

@keyframes race {
    0% { left: -100px; }
    100% { left: 100%; }
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    background: var(--gradient-card);
    backdrop-filter: blur(var(--blur-amount));
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius-lg);
}

.empty-icon {
    width: 100px;
    height: 100px;
    background: var(--gradient-button);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 2rem;
    font-size: 3rem;
    color: white;
}

.empty-state h3 {
    font-size: 1.5rem;
    color: var(--text-primary);
    margin-bottom: 1rem;
}

.empty-state p {
    color: var(--text-secondary);
    margin-bottom: 2rem;
    max-width: 500px;
    margin-left: auto;
    margin-right: auto;
}

.empty-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

/* Tips Section */
.tips-section {
    margin-top: 4rem;
}

.tips-card {
    background: var(--gradient-card);
    backdrop-filter: blur(var(--blur-amount));
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius-lg);
    padding: 2.5rem;
}

.tips-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 2rem;
}

.tips-header i {
    font-size: 1.5rem;
    color: var(--accent-pink);
}

.tips-header h3 {
    font-size: 1.3rem;
    color: var(--text-primary);
    font-weight: 600;
}

.tips-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
}

.tip-item {
    display: flex;
    gap: 1rem;
    padding: 1.5rem;
    background: rgba(255, 255, 255, 0.03);
    border-radius: var(--border-radius);
    border: 1px solid rgba(255, 255, 255, 0.05);
}

.tip-item i {
    color: var(--accent-pink);
    font-size: 1.2rem;
    margin-top: 0.25rem;
    flex-shrink: 0;
}

.tip-item h4 {
    color: var(--text-primary);
    font-size: 1rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.tip-item p {
    color: var(--text-secondary);
    font-size: 0.9rem;
    line-height: 1.4;
}

/* Responsive */
@media (max-width: 1024px) {
    .competitions-grid {
        grid-template-columns: 1fr;
    }
    
    .competitions-header {
        flex-direction: column;
        text-align: center;
        gap: 2rem;
    }
}

@media (max-width: 768px) {
    .section-header {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
    
    .competition-details {
        grid-template-columns: 1fr;
    }
    
    .countdown-timer {
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }
    
    .tips-grid {
        grid-template-columns: 1fr;
    }
    
    .header-stats {
        justify-content: center;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize countdown timers
    const countdowns = document.querySelectorAll('.countdown-timer');
    
    countdowns.forEach(countdown => {
        const startTime = new Date(countdown.dataset.startTime).getTime();
        
        function updateCountdown() {
            const now = new Date().getTime();
            const distance = startTime - now;
            
            if (distance > 0) {
                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                
                countdown.querySelector('[data-days]').textContent = days.toString().padStart(2, '0');
                countdown.querySelector('[data-hours]').textContent = hours.toString().padStart(2, '0');
                countdown.querySelector('[data-minutes]').textContent = minutes.toString().padStart(2, '0');
                countdown.querySelector('[data-seconds]').textContent = seconds.toString().padStart(2, '0');
            } else {
                // Competition has started, refresh the page
                location.reload();
            }
        }
        
        updateCountdown();
        setInterval(updateCountdown, 1000);
    });
    
    // Add animation to cards
    const cards = document.querySelectorAll('.competition-card');
    const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.animation = 'slideInUp 0.6s ease forwards';
            }
        });
    });
    
    cards.forEach(card => {
        observer.observe(card);
    });
});


// Add CSS animation
const style = document.createElement('style');
style.textContent = `
    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
`;
document.head.appendChild(style);
</script>
@endsection