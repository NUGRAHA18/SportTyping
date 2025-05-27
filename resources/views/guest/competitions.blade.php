@extends('layouts.app')

@section('content')
<div class="guest-competitions-container">
    <div class="container">
        <!-- Header -->
        <div class="competitions-header">
            <div class="header-content">
                <h1>Typing <span class="gradient-text">Competitions</span></h1>
                <p>Watch live competitions and see what awaits when you create your account!</p>
            </div>
            <div class="guest-badge">
                <i class="fas fa-eye"></i>
                <span>Spectator Mode</span>
            </div>
        </div>

        <!-- Live Competitions -->
        <div class="live-competitions-section">
            <div class="section-header">
                <h2><i class="fas fa-broadcast-tower"></i> Live Competitions</h2>
                <div class="live-indicator">
                    <span class="live-dot"></span>
                    3 LIVE NOW
                </div>
            </div>
            
            <div class="live-grid">
                <div class="live-competition-card">
                    <div class="card-header">
                        <div class="competition-info">
                            <h3>Speed Challenge - Intermediate</h3>
                            <div class="competition-meta">
                                <span class="device-type pc">
                                    <i class="fas fa-desktop"></i>
                                    PC Only
                                </span>
                                <span class="participants">
                                    <i class="fas fa-users"></i>
                                    12 racing
                                </span>
                            </div>
                        </div>
                        <div class="live-badge">
                            <span class="live-dot"></span>
                            LIVE
                        </div>
                    </div>
                    
                    <div class="race-preview">
                        <div class="preview-racers">
                            <div class="racer-item leader">
                                <div class="racer-avatar">
                                    <div class="avatar-placeholder">S</div>
                                </div>
                                <div class="racer-info">
                                    <span class="racer-name">SpeedDemon_92</span>
                                    <span class="racer-stats">78 WPM • 96%</span>
                                </div>
                                <div class="position-badge first">#1</div>
                            </div>
                            
                            <div class="racer-item">
                                <div class="racer-avatar">
                                    <div class="avatar-placeholder">T</div>
                                </div>
                                <div class="racer-info">
                                    <span class="racer-name">TypingMaster_V2</span>
                                    <span class="racer-stats">72 WPM • 98%</span>
                                </div>
                                <div class="position-badge second">#2</div>
                            </div>
                            
                            <div class="racer-item">
                                <div class="racer-avatar">
                                    <div class="avatar-placeholder">K</div>
                                </div>
                                <div class="racer-info">
                                    <span class="racer-name">KeyboardNinja</span>
                                    <span class="racer-stats">69 WPM • 94%</span>
                                </div>
                                <div class="position-badge third">#3</div>
                            </div>
                        </div>
                        
                        <div class="race-progress">
                            <div class="progress-bars">
                                <div class="progress-bar">
                                    <div class="progress-fill leader-progress" style="width: 85%"></div>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill second-progress" style="width: 78%"></div>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill third-progress" style="width: 71%"></div>
                                </div>
                            </div>
                            <div class="finish-line">
                                <i class="fas fa-flag-checkered"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-actions">
                        <button class="btn btn-outline-primary btn-full" onclick="showSignupModal()">
                            <i class="fas fa-sign-in-alt"></i>
                            Join Competition
                        </button>
                    </div>
                </div>

                <div class="live-competition-card">
                    <div class="card-header">
                        <div class="competition-info">
                            <h3>Mobile Typing Championship</h3>
                            <div class="competition-meta">
                                <span class="device-type mobile">
                                    <i class="fas fa-mobile-alt"></i>
                                    Mobile Only
                                </span>
                                <span class="participants">
                                    <i class="fas fa-users"></i>
                                    8 racing
                                </span>
                            </div>
                        </div>
                        <div class="live-badge">
                            <span class="live-dot"></span>
                            LIVE
                        </div>
                    </div>
                    
                    <div class="race-preview">
                        <div class="preview-racers">
                            <div class="racer-item leader">
                                <div class="racer-avatar">
                                    <div class="avatar-placeholder">M</div>
                                </div>
                                <div class="racer-info">
                                    <span class="racer-name">MobileGuru_2023</span>
                                    <span class="racer-stats">45 WPM • 92%</span>
                                </div>
                                <div class="position-badge first">#1</div>
                            </div>
                            
                            <div class="racer-item">
                                <div class="racer-avatar">
                                    <div class="avatar-placeholder">T</div>
                                </div>
                                <div class="racer-info">
                                    <span class="racer-name">ThumbTyper_Pro</span>
                                    <span class="racer-stats">42 WPM • 95%</span>
                                </div>
                                <div class="position-badge second">#2</div>
                            </div>
                            
                            <div class="racer-item">
                                <div class="racer-avatar">
                                    <div class="avatar-placeholder">S</div>
                                </div>
                                <div class="racer-info">
                                    <span class="racer-name">SwiftFingers_M</span>
                                    <span class="racer-stats">39 WPM • 88%</span>
                                </div>
                                <div class="position-badge third">#3</div>
                            </div>
                        </div>
                        
                        <div class="race-progress">
                            <div class="progress-bars">
                                <div class="progress-bar">
                                    <div class="progress-fill leader-progress" style="width: 67%"></div>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill second-progress" style="width: 61%"></div>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill third-progress" style="width: 54%"></div>
                                </div>
                            </div>
                            <div class="finish-line">
                                <i class="fas fa-flag-checkered"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-actions">
                        <button class="btn btn-outline-primary btn-full" onclick="showSignupModal()">
                            <i class="fas fa-sign-in-alt"></i>
                            Join Competition
                        </button>
                    </div>
                </div>

                <div class="live-competition-card">
                    <div class="card-header">
                        <div class="competition-info">
                            <h3>Programming Text Challenge</h3>
                            <div class="competition-meta">
                                <span class="device-type both">
                                    <i class="fas fa-laptop"></i>
                                    All Devices
                                </span>
                                <span class="participants">
                                    <i class="fas fa-users"></i>
                                    15 racing
                                </span>
                            </div>
                        </div>
                        <div class="live-badge">
                            <span class="live-dot"></span>
                            LIVE
                        </div>
                    </div>
                    
                    <div class="race-preview">
                        <div class="preview-racers">
                            <div class="racer-item leader">
                                <div class="racer-avatar">
                                    <div class="avatar-placeholder">C</div>
                                </div>
                                <div class="racer-info">
                                    <span class="racer-name">CodeTyper_Elite</span>
                                    <span class="racer-stats">65 WPM • 97%</span>
                                </div>
                                <div class="position-badge first">#1</div>
                            </div>
                            
                            <div class="racer-item">
                                <div class="racer-avatar">
                                    <div class="avatar-placeholder">D</div>
                                </div>
                                <div class="racer-info">
                                    <span class="racer-name">DevSpeed_Master</span>
                                    <span class="racer-stats">61 WPM • 93%</span>
                                </div>
                                <div class="position-badge second">#2</div>
                            </div>
                            
                            <div class="racer-item">
                                <div class="racer-avatar">
                                    <div class="avatar-placeholder">P</div>
                                </div>
                                <div class="racer-info">
                                    <span class="racer-name">ProgrammerFast</span>
                                    <span class="racer-stats">58 WPM • 89%</span>
                                </div>
                                <div class="position-badge third">#3</div>
                            </div>
                        </div>
                        
                        <div class="race-progress">
                            <div class="progress-bars">
                                <div class="progress-bar">
                                    <div class="progress-fill leader-progress" style="width: 73%"></div>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill second-progress" style="width: 68%"></div>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill third-progress" style="width: 62%"></div>
                                </div>
                            </div>
                            <div class="finish-line">
                                <i class="fas fa-flag-checkered"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-actions">
                        <button class="btn btn-outline-primary btn-full" onclick="showSignupModal()">
                            <i class="fas fa-sign-in-alt"></i>
                            Join Competition
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Upcoming Competitions -->
        <div class="upcoming-section">
            <div class="section-header">
                <h2><i class="fas fa-calendar-alt"></i> Upcoming Competitions</h2>
                <div class="next-info">
                    <i class="fas fa-clock"></i>
                    Next in 15 minutes
                </div>
            </div>
            
            <div class="upcoming-grid">
                <div class="upcoming-card">
                    <div class="card-header">
                        <div class="competition-info">
                            <h3>Daily Speed Challenge</h3>
                            <p>Test your speed with randomly selected texts</p>
                        </div>
                        <div class="device-badge pc">
                            <i class="fas fa-desktop"></i>
                            PC
                        </div>
                    </div>
                    
                    <div class="competition-details">
                        <div class="detail-row">
                            <span class="detail-label">Start Time:</span>
                            <span class="detail-value">{{ now()->addMinutes(15)->format('g:i A') }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Duration:</span>
                            <span class="detail-value">~5 minutes</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Difficulty:</span>
                            <span class="detail-value difficulty-intermediate">Intermediate</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Category:</span>
                            <span class="detail-value">Mixed Content</span>
                        </div>
                    </div>
                    
                    <div class="countdown-timer" data-target="{{ now()->addMinutes(15)->toISOString() }}">
                        <div class="timer-segment">
                            <span class="timer-number" data-minutes>15</span>
                            <span class="timer-label">Min</span>
                        </div>
                        <div class="timer-segment">
                            <span class="timer-number" data-seconds>00</span>
                            <span class="timer-label">Sec</span>
                        </div>
                    </div>
                    
                    <div class="card-actions">
                        <button class="btn btn-outline-primary btn-full" onclick="showSignupModal()">
                            <i class="fas fa-bell"></i>
                            Set Reminder
                        </button>
                    </div>
                </div>

                <div class="upcoming-card">
                    <div class="card-header">
                        <div class="competition-info">
                            <h3>Mobile Masters Tournament</h3>
                            <p>Championship for mobile typing enthusiasts</p>
                        </div>
                        <div class="device-badge mobile">
                            <i class="fas fa-mobile-alt"></i>
                            Mobile
                        </div>
                    </div>
                    
                    <div class="competition-details">
                        <div class="detail-row">
                            <span class="detail-label">Start Time:</span>
                            <span class="detail-value">{{ now()->addHour()->format('g:i A') }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Duration:</span>
                            <span class="detail-value">~8 minutes</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Difficulty:</span>
                            <span class="detail-value difficulty-advanced">Advanced</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Category:</span>
                            <span class="detail-value">Technology</span>
                        </div>
                    </div>
                    
                    <div class="countdown-timer" data-target="{{ now()->addHour()->toISOString() }}">
                        <div class="timer-segment">
                            <span class="timer-number" data-hours>1</span>
                            <span class="timer-label">Hour</span>
                        </div>
                        <div class="timer-segment">
                            <span class="timer-number" data-minutes>00</span>
                            <span class="timer-label">Min</span>
                        </div>
                    </div>
                    
                    <div class="card-actions">
                        <button class="btn btn-outline-primary btn-full" onclick="showSignupModal()">
                            <i class="fas fa-bell"></i>
                            Set Reminder
                        </button>
                    </div>
                </div>

                <div class="upcoming-card">
                    <div class="card-header">
                        <div class="competition-info">
                            <h3>Weekend Word Warriors</h3>
                            <p>Literature-focused weekend competition</p>
                        </div>
                        <div class="device-badge both">
                            <i class="fas fa-laptop"></i>
                            All
                        </div>
                    </div>
                    
                    <div class="competition-details">
                        <div class="detail-row">
                            <span class="detail-label">Start Time:</span>
                            <span class="detail-value">{{ now()->addHours(3)->format('g:i A') }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Duration:</span>
                            <span class="detail-value">~10 minutes</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Difficulty:</span>
                            <span class="detail-value difficulty-beginner">Beginner</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Category:</span>
                            <span class="detail-value">Literature</span>
                        </div>
                    </div>
                    
                    <div class="countdown-timer" data-target="{{ now()->addHours(3)->toISOString() }}">
                        <div class="timer-segment">
                            <span class="timer-number" data-hours>3</span>
                            <span class="timer-label">Hours</span>
                        </div>
                        <div class="timer-segment">
                            <span class="timer-number" data-minutes>00</span>
                            <span class="timer-label">Min</span>
                        </div>
                    </div>
                    
                    <div class="card-actions">
                        <button class="btn btn-outline-primary btn-full" onclick="showSignupModal()">
                            <i class="fas fa-bell"></i>
                            Set Reminder
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Competition Benefits -->
        <div class="benefits-section">
            <div class="section-header">
                <h2><i class="fas fa-trophy"></i> Why Join Competitions?</h2>
                <p>Discover the benefits of competitive typing</p>
            </div>
            
            <div class="benefits-grid">
                <div class="benefit-card">
                    <div class="benefit-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="benefit-content">
                        <h3>Improve Your Speed</h3>
                        <p>Competition pressure naturally increases your typing speed and builds stamina for longer typing sessions.</p>
                    </div>
                </div>
                
                <div class="benefit-card">
                    <div class="benefit-icon">
                        <i class="fas fa-bullseye"></i>
                    </div>
                    <div class="benefit-content">
                        <h3>Enhance Accuracy</h3>
                        <p>Real-time competition teaches you to balance speed with precision for optimal performance.</p>
                    </div>
                </div>
                
                <div class="benefit-card">
                    <div class="benefit-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="benefit-content">
                        <h3>Join Community</h3>
                        <p>Connect with fellow typing enthusiasts and learn from the best typists worldwide.</p>
                    </div>
                </div>
                
                <div class="benefit-card">
                    <div class="benefit-icon">
                        <i class="fas fa-medal"></i>
                    </div>
                    <div class="benefit-content">
                        <h3>Earn Recognition</h3>
                        <p>Climb leaderboards, unlock achievements, and showcase your typing prowess to the world.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Call to Action -->
        <div class="cta-section">
            <div class="cta-content">
                <h3>Ready to Compete?</h3>
                <p>Create your free account now and join the next competition!</p>
            </div>
            <div class="cta-actions">
                <a href="{{ route('register') }}" class="btn btn-primary btn-large">
                    <i class="fas fa-user-plus"></i>
                    Create Free Account
                </a>
                <a href="{{ route('guest.practice') }}" class="btn btn-outline-primary">
                    <i class="fas fa-keyboard"></i>
                    Practice First
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Signup Modal -->
<div class="modal" id="signupModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3><i class="fas fa-sign-in-alt"></i> Join Competition</h3>
            <button class="close-btn" onclick="closeSignupModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <div class="signup-message">
                <div class="signup-icon">
                    <i class="fas fa-racing-flag"></i>
                </div>
                <h4>Ready to Race?</h4>
                <p>Create a free account to join competitions and track your progress!</p>
                
                <div class="signup-benefits">
                    <div class="benefit-item">
                        <i class="fas fa-check"></i>
                        <span>Join live competitions</span>
                    </div>
                    <div class="benefit-item">
                        <i class="fas fa-check"></i>
                        <span>Track your statistics</span>
                    </div>
                    <div class="benefit-item">
                        <i class="fas fa-check"></i>
                        <span>Earn badges & rankings</span>
                    </div>
                    <div class="benefit-item">
                        <i class="fas fa-check"></i>
                        <span>Compete with friends</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <a href="{{ route('register') }}" class="btn btn-primary">
                <i class="fas fa-user-plus"></i>
                Create Account
            </a>
            <a href="{{ route('login') }}" class="btn btn-outline-primary">
                <i class="fas fa-sign-in-alt"></i>
                Sign In
            </a>
        </div>
    </div>
</div>

<style>
.guest-competitions-container {
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
    color: var(--text-primary);
    margin-bottom: 0.5rem;
}

.gradient-text {
    background: var(--gradient-accent);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.header-content p {
    color: var(--text-secondary);
    font-size: 1.1rem;
}

.guest-badge {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    background: rgba(59, 130, 246, 0.1);
    color: var(--info);
    padding: 1rem 1.5rem;
    border-radius: var(--border-radius);
    border: 1px solid rgba(59, 130, 246, 0.2);
    font-weight: 600;
}

/* Section Headers */
.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.section-header h2 {
    font-size: 1.8rem;
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

.next-info {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--text-secondary);
    font-size: 0.9rem;
}

/* Live Competitions */
.live-competitions-section {
    margin-bottom: 4rem;
}

.live-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 2rem;
}

.live-competition-card {
    background: var(--gradient-card);
    backdrop-filter: blur(var(--blur-amount));
    border: 1px solid rgba(239, 68, 68, 0.3);
    border-radius: var(--border-radius-lg);
    padding: 2rem;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.live-competition-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, var(--error), var(--accent-pink));
}

.live-competition-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 40px rgba(239, 68, 68, 0.2);
}

.card-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 2rem;
}

.competition-info h3 {
    font-size: 1.2rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 1rem;
}

.competition-meta {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.device-type, .participants {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 0.75rem;
    border-radius: var(--border-radius);
    font-size: 0.85rem;
    font-weight: 600;
}

.device-type.pc {
    background: rgba(59, 130, 246, 0.1);
    color: var(--info);
    border: 1px solid rgba(59, 130, 246, 0.2);
}

.device-type.mobile {
    background: rgba(245, 158, 11, 0.1);
    color: var(--warning);
    border: 1px solid rgba(245, 158, 11, 0.2);
}

.device-type.both {
    background: rgba(16, 185, 129, 0.1);
    color: var(--success);
    border: 1px solid rgba(16, 185, 129, 0.2);
}

.participants {
    background: rgba(255, 255, 255, 0.05);
    color: var(--text-secondary);
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.live-badge {
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

/* Race Preview */
.race-preview {
    margin-bottom: 2rem;
}

.preview-racers {
    margin-bottom: 1.5rem;
}

.racer-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: rgba(255, 255, 255, 0.03);
    border-radius: var(--border-radius);
    margin-bottom: 0.75rem;
    border: 1px solid rgba(255, 255, 255, 0.05);
}

.racer-item.leader {
    border-color: var(--accent-pink);
    background: rgba(255, 107, 157, 0.05);
}

.racer-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    overflow: hidden;
    flex-shrink: 0;
}

.avatar-placeholder {
    width: 100%;
    height: 100%;
    background: var(--gradient-button);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
}

.racer-info {
    flex: 1;
}

.racer-name {
    display: block;
    color: var(--text-primary);
    font-weight: 600;
    font-size: 0.95rem;
    margin-bottom: 0.25rem;
}

.racer-stats {
    color: var(--text-secondary);
    font-size: 0.85rem;
}

.position-badge {
    padding: 0.25rem 0.75rem;
    border-radius: var(--border-radius);
    font-size: 0.8rem;
    font-weight: 700;
    color: white;
}

.position-badge.first { background: linear-gradient(45deg, #ffd700, #ffed4a); color: #000; }
.position-badge.second { background: linear-gradient(45deg, #c0c0c0, #e5e7eb); color: #000; }
.position-badge.third { background: linear-gradient(45deg, #cd7f32, #d97706); }

.race-progress {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.progress-bars {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.progress-bar {
    height: 8px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 4px;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    border-radius: 4px;
    transition: width 0.3s ease;
    position: relative;
}

.leader-progress { background: var(--gradient-button); }
.second-progress { background: linear-gradient(45deg, #00d4ff, #0ea5e9); }
.third-progress { background: linear-gradient(45deg, #f59e0b, #eab308); }

.progress-fill::after {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 15px;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3));
    animation: shimmer 2s infinite;
}

.finish-line {
    color: var(--accent-cyan);
    font-size: 1.2rem;
    animation: wave 2s ease-in-out infinite;
}

/* Upcoming Competitions */
.upcoming-section {
    margin-bottom: 4rem;
}

.upcoming-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 2rem;
}

.upcoming-card {
    background: var(--gradient-card);
    backdrop-filter: blur(var(--blur-amount));
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius-lg);
    padding: 2rem;
    transition: all 0.3s ease;
}

.upcoming-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 30px rgba(139, 92, 246, 0.15);
}

.upcoming-card .card-header {
    margin-bottom: 1.5rem;
}

.device-badge {
    padding: 0.5rem 1rem;
    border-radius: var(--border-radius);
    font-size: 0.85rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.device-badge.pc {
    background: rgba(59, 130, 246, 0.1);
    color: var(--info);
    border: 1px solid rgba(59, 130, 246, 0.2);
}

.device-badge.mobile {
    background: rgba(245, 158, 11, 0.1);
    color: var(--warning);
    border: 1px solid rgba(245, 158, 11, 0.2);
}

.device-badge.both {
    background: rgba(16, 185, 129, 0.1);
    color: var(--success);
    border: 1px solid rgba(16, 185, 129, 0.2);
}

.upcoming-card .competition-info h3 {
    margin-bottom: 0.5rem;
}

.upcoming-card .competition-info p {
    color: var(--text-secondary);
    font-size: 0.9rem;
}

.competition-details {
    margin-bottom: 2rem;
}

.detail-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 0.75rem;
    padding: 0.5rem 0;
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
}

.detail-label {
    color: var(--text-secondary);
    font-size: 0.9rem;
}

.detail-value {
    color: var(--text-primary);
    font-weight: 500;
    font-size: 0.9rem;
}

.difficulty-beginner { color: var(--success); }
.difficulty-intermediate { color: var(--warning); }
.difficulty-advanced { color: var(--error); }

.countdown-timer {
    display: flex;
    justify-content: center;
    gap: 1rem;
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
    font-size: 1.8rem;
    font-weight: 700;
    color: var(--text-primary);
    font-family: 'Courier New', monospace;
}

.timer-label {
    font-size: 0.8rem;
    color: var(--text-secondary);
}

/* Benefits Section */
.benefits-section {
    margin-bottom: 4rem;
}

.benefits-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
}

.benefit-card {
    background: var(--gradient-card);
    backdrop-filter: blur(var(--blur-amount));
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius-lg);
    padding: 2rem;
    text-align: center;
    transition: all 0.3s ease;
}

.benefit-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 30px rgba(139, 92, 246, 0.15);
}

.benefit-icon {
    width: 60px;
    height: 60px;
    background: var(--gradient-button);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
    font-size: 1.5rem;
    color: white;
}

.benefit-content h3 {
    font-size: 1.2rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 1rem;
}

.benefit-content p {
    color: var(--text-secondary);
    line-height: 1.6;
}

/* CTA Section */
.cta-section {
    background: var(--gradient-card);
    backdrop-filter: blur(var(--blur-amount));
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius-lg);
    padding: 3rem;
    text-align: center;
    position: relative;
    overflow: hidden;
}

.cta-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: var(--gradient-accent);
}

.cta-content h3 {
    font-size: 1.8rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 1rem;
}

.cta-content p {
    color: var(--text-secondary);
    font-size: 1.1rem;
    margin-bottom: 2rem;
}

.cta-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

.btn-large {
    padding: 1.25rem 2.5rem;
    font-size: 1.1rem;
    font-weight: 700;
}

.btn-full {
    width: 100%;
    padding: 1rem;
    font-weight: 600;
}

/* Modal */
.modal {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.8);
    backdrop-filter: blur(10px);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 1000;
    padding: 2rem;
}

.modal.show {
    display: flex;
}

.modal-content {
    background: var(--gradient-card);
    backdrop-filter: blur(var(--blur-amount));
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius-lg);
    width: 100%;
    max-width: 500px;
    position: relative;
}

.modal-content::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: var(--gradient-accent);
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 2rem 2rem 0;
}

.modal-header h3 {
    color: var(--text-primary);
    font-size: 1.3rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.close-btn {
    background: none;
    border: none;
    color: var(--text-secondary);
    font-size: 1.2rem;
    cursor: pointer;
    padding: 0.5rem;
    border-radius: 50%;
    transition: all 0.3s ease;
}

.close-btn:hover {
    color: var(--accent-pink);
    background: rgba(255, 107, 157, 0.1);
}

.modal-body {
    padding: 2rem;
}

.signup-message {
    text-align: center;
}

.signup-icon {
    width: 80px;
    height: 80px;
    background: var(--gradient-button);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
    font-size: 2rem;
    color: white;
}

.signup-message h4 {
    color: var(--text-primary);
    font-size: 1.3rem;
    font-weight: 600;
    margin-bottom: 1rem;
}

.signup-message p {
    color: var(--text-secondary);
    margin-bottom: 2rem;
}

.signup-benefits {
    text-align: left;
}

.benefit-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 1rem;
    color: var(--text-secondary);
}

.benefit-item i {
    color: var(--success);
}

.modal-footer {
    padding: 0 2rem 2rem;
    display: flex;
    gap: 1rem;
    justify-content: center;
}

/* Animations */
@keyframes pulse {
    0% { opacity: 1; }
    50% { opacity: 0.5; }
    100% { opacity: 1; }
}

@keyframes shimmer {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(100%); }
}

@keyframes wave {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-3px); }
}

/* Responsive */
@media (max-width: 1024px) {
    .competitions-header {
        flex-direction: column;
        text-align: center;
        gap: 2rem;
    }
    
    .section-header {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
    
    .live-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .header-content h1 {
        font-size: 2rem;
    }
    
    .upcoming-grid {
        grid-template-columns: 1fr;
    }
    
    .benefits-grid {
        grid-template-columns: 1fr;
    }
    
    .cta-actions {
        flex-direction: column;
    }
    
    .modal-footer {
        flex-direction: column;
    }
    
    .countdown-timer {
        gap: 0.5rem;
    }
    
    .timer-number {
        font-size: 1.5rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize countdown timers
    const countdowns = document.querySelectorAll('.countdown-timer');
    
    countdowns.forEach(countdown => {
        const target = new Date(countdown.dataset.target).getTime();
        
        function updateCountdown() {
            const now = new Date().getTime();
            const distance = target - now;
            
            if (distance > 0) {
                const hours = Math.floor(distance / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                
                const hoursEl = countdown.querySelector('[data-hours]');
                const minutesEl = countdown.querySelector('[data-minutes]');
                const secondsEl = countdown.querySelector('[data-seconds]');
                
                if (hoursEl) hoursEl.textContent = hours.toString().padStart(2, '0');
                if (minutesEl) minutesEl.textContent = minutes.toString().padStart(2, '0');
                if (secondsEl) secondsEl.textContent = seconds.toString().padStart(2, '0');
            } else {
                // Competition has started
                countdown.innerHTML = '<div class="timer-segment"><span class="timer-number">LIVE</span><span class="timer-label">Now</span></div>';
            }
        }
        
        updateCountdown();
        setInterval(updateCountdown, 1000);
    });
    
    // Animate progress bars
    const progressBars = document.querySelectorAll('.progress-fill');
    const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const progress = entry.target;
                const width = progress.style.width;
                progress.style.width = '0%';
                setTimeout(() => {
                    progress.style.width = width;
                }, 500);
            }
        });
    });
    
    progressBars.forEach(bar => {
        observer.observe(bar);
    });
    
    // Simulate live updates for demo
    setInterval(() => {
        const progressBars = document.querySelectorAll('.progress-fill');
        progressBars.forEach(bar => {
            const currentWidth = parseFloat(bar.style.width) || 0;
            if (currentWidth < 95) {
                const newWidth = Math.min(95, currentWidth + Math.random() * 2);
                bar.style.width = newWidth + '%';
            }
        });
    }, 3000);
});

function showSignupModal() {
    document.getElementById('signupModal').classList.add('show');
}

function closeSignupModal() {
    document.getElementById('signupModal').classList.remove('show');
}

// Close modal when clicking outside
document.addEventListener('click', function(e) {
    if (e.target.id === 'signupModal') {
        closeSignupModal();
    }
});

// ESC key to close modal
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeSignupModal();
    }
});
</script>
@endsection