@extends('layouts.app')

@section('content')
<div class="verify-container">
    <div class="verify-wrapper">
        <div class="verify-content">
            <!-- Success Animation -->
            <div class="verification-icon">
                <div class="icon-circle">
                    <i class="fas fa-envelope"></i>
                </div>
                <div class="pulse-ring"></div>
                <div class="pulse-ring-2"></div>
            </div>
            
            <!-- Main Content -->
            <div class="verify-header">
                <h1>Verify Your Email</h1>
                <p class="subtitle">We've sent a verification link to your email address. Click the link to activate your champion account and start your typing journey.</p>
            </div>
            
            <!-- Status Messages -->
            @if (session('status'))
                <div class="success-message">
                    <div class="message-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="message-content">
                        <h3>Email Sent Successfully!</h3>
                        <p>{{ session('status') }}</p>
                    </div>
                </div>
            @endif
            
            <!-- Instructions -->
            <div class="verification-steps">
                <div class="step-item">
                    <div class="step-number">1</div>
                    <div class="step-content">
                        <h4>Check Your Inbox</h4>
                        <p>Look for an email from SportTyping in your inbox</p>
                    </div>
                </div>
                
                <div class="step-item">
                    <div class="step-number">2</div>
                    <div class="step-content">
                        <h4>Click Verification Link</h4>
                        <p>Click the "Verify Email Address" button in the email</p>
                    </div>
                </div>
                
                <div class="step-item">
                    <div class="step-number">3</div>
                    <div class="step-content">
                        <h4>Start Competing</h4>
                        <p>Access your dashboard and begin your typing championship</p>
                    </div>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="action-buttons">
                <form class="resend-form" method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-paper-plane"></i>
                        <span>Resend Verification Email</span>
                        <div class="btn-shine"></div>
                    </button>
                </form>
                
                <div class="alternative-actions">
                    <a href="{{ route('guest.practice') }}" class="btn-secondary">
                        <i class="fas fa-play"></i>
                        Practice While Waiting
                    </a>
                    
                    <a href="{{ route('logout') }}" class="btn-outline" 
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i>
                        Sign Out
                    </a>
                    
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </div>
            
            <!-- Help Section -->
            <div class="help-section">
                <div class="help-header">
                    <i class="fas fa-question-circle"></i>
                    <h3>Need Help?</h3>
                </div>
                
                <div class="help-items">
                    <div class="help-item">
                        <i class="fas fa-search"></i>
                        <div>
                            <h4>Check Spam Folder</h4>
                            <p>Sometimes verification emails end up in spam or promotions folder</p>
                        </div>
                    </div>
                    
                    <div class="help-item">
                        <i class="fas fa-clock"></i>
                        <div>
                            <h4>Wait a Few Minutes</h4>
                            <p>Email delivery can take up to 5 minutes during peak times</p>
                        </div>
                    </div>
                    
                    <div class="help-item">
                        <i class="fas fa-at"></i>
                        <div>
                            <h4>Check Email Address</h4>
                            <p>Make sure you entered the correct email address during registration</p>
                        </div>
                    </div>
                    
                    <div class="help-item">
                        <i class="fas fa-life-ring"></i>
                        <div>
                            <h4>Still Need Help?</h4>
                            <p>Contact our support team for assistance</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Email Preview -->
            <div class="email-preview">
                <div class="preview-header">
                    <i class="fas fa-eye"></i>
                    <span>What to expect in your email:</span>
                </div>
                
                <div class="email-mockup">
                    <div class="email-header">
                        <div class="sender-info">
                            <div class="sender-avatar">
                                <i class="fas fa-trophy"></i>
                            </div>
                            <div class="sender-details">
                                <strong>SportTyping</strong>
                                <span>noreply@sporttyping.com</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="email-body">
                        <h4>Welcome to SportTyping!</h4>
                        <p>Click the button below to verify your email address and activate your champion account.</p>
                        
                        <div class="email-button">
                            <i class="fas fa-check-circle"></i>
                            Verify Email Address
                        </div>
                        
                        <p class="email-footer">This link will expire in 60 minutes for security reasons.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.verify-container {
    min-height: 100vh;
    background: linear-gradient(135deg, 
        rgba(59, 130, 246, 0.02) 0%,
        rgba(248, 250, 252, 1) 50%,
        rgba(16, 185, 129, 0.02) 100%
    );
    padding: 2rem 0;
    position: relative;
    overflow: hidden;
}

.verify-container::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: 
        radial-gradient(circle at 20% 80%, rgba(59, 130, 246, 0.05) 0%, transparent 50%),
        radial-gradient(circle at 80% 20%, rgba(16, 185, 129, 0.05) 0%, transparent 50%);
    z-index: 1;
}

.verify-wrapper {
    max-width: 800px;
    margin: 0 auto;
    position: relative;
    z-index: 2;
    padding: 0 2rem;
}

.verify-content {
    background: var(--bg-card);
    border-radius: var(--border-radius-xl);
    box-shadow: var(--shadow-xl);
    border: 1px solid var(--border-light);
    padding: 3rem;
    text-align: center;
    position: relative;
    overflow: hidden;
}

.verify-content::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: var(--champion-gradient);
}

/* Verification Icon */
.verification-icon {
    position: relative;
    margin: 0 auto 3rem;
    width: 120px;
    height: 120px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.icon-circle {
    width: 120px;
    height: 120px;
    background: var(--champion-gradient);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3rem;
    color: white;
    position: relative;
    z-index: 3;
    box-shadow: var(--shadow-lg);
}

.pulse-ring {
    position: absolute;
    width: 140px;
    height: 140px;
    border: 3px solid rgba(59, 130, 246, 0.3);
    border-radius: 50%;
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

.pulse-ring-2 {
    position: absolute;
    width: 160px;
    height: 160px;
    border: 2px solid rgba(59, 130, 246, 0.2);
    border-radius: 50%;
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    animation-delay: 1s;
}

@keyframes pulse {
    0%, 100% {
        transform: scale(1);
        opacity: 1;
    }
    50% {
        transform: scale(1.1);
        opacity: 0.7;
    }
}

/* Header */
.verify-header {
    margin-bottom: 3rem;
}

.verify-header h1 {
    font-family: var(--font-display);
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 1rem;
}

.subtitle {
    font-size: 1.1rem;
    color: var(--text-secondary);
    line-height: 1.6;
    max-width: 600px;
    margin: 0 auto;
}

/* Success Message */
.success-message {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.5rem;
    background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(16, 185, 129, 0.05));
    border: 1px solid rgba(16, 185, 129, 0.2);
    border-radius: var(--border-radius-lg);
    margin-bottom: 2rem;
    text-align: left;
}

.message-icon {
    width: 50px;
    height: 50px;
    background: var(--victory-gradient);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    flex-shrink: 0;
}

.message-content h3 {
    color: var(--accent-success);
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.message-content p {
    color: var(--text-secondary);
    margin: 0;
}

/* Verification Steps */
.verification-steps {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
    margin: 3rem 0;
    text-align: left;
}

.step-item {
    display: flex;
    align-items: flex-start;
    gap: 1.5rem;
    padding: 1.5rem;
    background: var(--bg-secondary);
    border-radius: var(--border-radius-lg);
    border: 1px solid var(--border-light);
    transition: all 0.3s ease;
}

.step-item:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.step-number {
    width: 40px;
    height: 40px;
    background: var(--champion-gradient);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 1.1rem;
    flex-shrink: 0;
}

.step-content h4 {
    color: var(--text-primary);
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.step-content p {
    color: var(--text-secondary);
    margin: 0;
    line-height: 1.5;
}

/* Action Buttons */
.action-buttons {
    margin: 3rem 0;
}

.resend-form {
    margin-bottom: 2rem;
}

.btn-primary {
    background: var(--champion-gradient);
    border: none;
    color: white;
    padding: 1.25rem 2rem;
    border-radius: var(--border-radius);
    font-weight: 700;
    font-size: 1rem;
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    cursor: pointer;
    box-shadow: var(--shadow-md);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
}

.btn-shine {
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.5s ease;
}

.btn-primary:hover .btn-shine {
    left: 100%;
}

.alternative-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    justify-content: center;
}

.btn-secondary {
    background: transparent;
    border: 2px solid var(--accent-primary);
    color: var(--accent-primary);
    padding: 1rem 1.5rem;
    border-radius: var(--border-radius);
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
}

.btn-secondary:hover {
    background: var(--accent-primary);
    color: white;
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.btn-outline {
    background: transparent;
    border: 1px solid var(--border-medium);
    color: var(--text-secondary);
    padding: 1rem 1.5rem;
    border-radius: var(--border-radius);
    font-weight: 500;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
}

.btn-outline:hover {
    border-color: var(--text-primary);
    color: var(--text-primary);
    text-decoration: none;
}

/* Help Section */
.help-section {
    margin: 3rem 0;
    text-align: left;
}

.help-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 2rem;
    justify-content: center;
}

.help-header i {
    color: var(--accent-primary);
    font-size: 1.5rem;
}

.help-header h3 {
    color: var(--text-primary);
    font-weight: 600;
    font-size: 1.25rem;
}

.help-items {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
}

.help-item {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    padding: 1.5rem;
    background: var(--bg-secondary);
    border-radius: var(--border-radius);
    border: 1px solid var(--border-light);
    transition: all 0.3s ease;
}

.help-item:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.help-item i {
    color: var(--accent-primary);
    font-size: 1.25rem;
    margin-top: 0.25rem;
    flex-shrink: 0;
}

.help-item h4 {
    color: var(--text-primary);
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.help-item p {
    color: var(--text-secondary);
    margin: 0;
    line-height: 1.5;
    font-size: 0.9rem;
}

/* Email Preview */
.email-preview {
    margin: 3rem 0;
    text-align: left;
}

.preview-header {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 1.5rem;
    justify-content: center;
    color: var(--text-secondary);
    font-weight: 500;
}

.preview-header i {
    color: var(--accent-primary);
}

.email-mockup {
    background: var(--bg-secondary);
    border-radius: var(--border-radius-lg);
    border: 1px solid var(--border-light);
    overflow: hidden;
    box-shadow: var(--shadow-sm);
}

.email-header {
    padding: 1.5rem;
    background: white;
    border-bottom: 1px solid var(--border-light);
}

.sender-info {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.sender-avatar {
    width: 40px;
    height: 40px;
    background: var(--champion-gradient);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1rem;
}

.sender-details {
    display: flex;
    flex-direction: column;
}

.sender-details strong {
    color: var(--text-primary);
    font-weight: 600;
}

.sender-details span {
    color: var(--text-secondary);
    font-size: 0.9rem;
}

.email-body {
    padding: 2rem 1.5rem;
}

.email-body h4 {
    color: var(--text-primary);
    font-weight: 600;
    margin-bottom: 1rem;
}

.email-body p {
    color: var(--text-secondary);
    line-height: 1.6;
    margin-bottom: 1.5rem;
}

.email-button {
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    background: var(--champion-gradient);
    color: white;
    padding: 1rem 2rem;
    border-radius: var(--border-radius);
    font-weight: 600;
    margin: 1rem 0;
    text-decoration: none;
}

.email-footer {
    font-size: 0.875rem;
    color: var(--text-muted);
    margin-top: 1.5rem;
    margin-bottom: 0;
}

/* Responsive */
@media (max-width: 768px) {
    .verify-container {
        padding: 1rem 0;
    }
    
    .verify-content {
        padding: 2rem 1.5rem;
    }
    
    .verify-header h1 {
        font-size: 2rem;
    }
    
    .verification-steps {
        gap: 1rem;
    }
    
    .step-item {
        padding: 1rem;
    }
    
    .help-items {
        grid-template-columns: 1fr;
    }
    
    .alternative-actions {
        flex-direction: column;
        align-items: center;
    }
    
    .btn-secondary,
    .btn-outline {
        width: 100%;
        justify-content: center;
    }
}

@media (max-width: 480px) {
    .icon-circle {
        width: 100px;
        height: 100px;
        font-size: 2.5rem;
    }
    
    .pulse-ring {
        width: 120px;
        height: 120px;
    }
    
    .pulse-ring-2 {
        width: 140px;
        height: 140px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add entrance animation
    const content = document.querySelector('.verify-content');
    content.style.opacity = '0';
    content.style.transform = 'translateY(30px)';
    content.style.transition = 'all 0.8s ease';
    
    setTimeout(() => {
        content.style.opacity = '1';
        content.style.transform = 'translateY(0)';
    }, 100);
    
    // Animate steps on scroll
    const steps = document.querySelectorAll('.step-item');
    const observer = new IntersectionObserver(entries => {
        entries.forEach((entry, index) => {
            if (entry.isIntersecting) {
                setTimeout(() => {
                    entry.target.style.animation = 'slideInLeft 0.6s ease forwards';
                }, index * 150);
            }
        });
    });
    
    steps.forEach(step => {
        step.style.opacity = '0';
        step.style.transform = 'translateX(-30px)';
        observer.observe(step);
    });
    
    // Help items animation
    const helpItems = document.querySelectorAll('.help-item');
    const helpObserver = new IntersectionObserver(entries => {
        entries.forEach((entry, index) => {
            if (entry.isIntersecting) {
                setTimeout(() => {
                    entry.target.style.animation = 'slideInUp 0.6s ease forwards';
                }, index * 100);
            }
        });
    });
    
    helpItems.forEach(item => {
        item.style.opacity = '0';
        item.style.transform = 'translateY(20px)';
        helpObserver.observe(item);
    });
    
    // Resend button enhancement
    const resendForm = document.querySelector('.resend-form');
    const resendBtn = resendForm.querySelector('.btn-primary');
    
    resendForm.addEventListener('submit', function() {
        resendBtn.disabled = true;
        resendBtn.innerHTML = `
            <div class="loading" style="width: 20px; height: 20px; border: 3px solid rgba(255,255,255,0.3); border-radius: 50%; border-top-color: white; animation: spin 1s ease-in-out infinite;"></div>
            <span>Sending...</span>
        `;
        
        // Re-enable after 3 seconds
        setTimeout(() => {
            resendBtn.disabled = false;
            resendBtn.innerHTML = `
                <i class="fas fa-paper-plane"></i>
                <span>Resend Verification Email</span>
                <div class="btn-shine"></div>
            `;
        }, 3000);
    });
    
    // Auto-hide success message after 5 seconds
    const successMessage = document.querySelector('.success-message');
    if (successMessage) {
        setTimeout(() => {
            successMessage.style.animation = 'slideOutUp 0.5s ease forwards';
            setTimeout(() => {
                successMessage.style.display = 'none';
            }, 500);
        }, 5000);
    }
});

// Add keyframes
const style = document.createElement('style');
style.textContent = `
    @keyframes slideInLeft {
        from {
            opacity: 0;
            transform: translateX(-30px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes slideOutUp {
        from {
            opacity: 1;
            transform: translateY(0);
        }
        to {
            opacity: 0;
            transform: translateY(-20px);
        }
    }
    
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
`;
document.head.appendChild(style);
</script>
@endsection
