@extends('layouts.app')

@section('content')
<div class="auth-container">
    <div class="container">
        <div class="auth-wrapper">
            <div class="auth-card">
                <div class="auth-header">
                    <div class="auth-icon">
                        <i class="fas fa-envelope-circle-check"></i>
                    </div>
                    <h1>Verify Your Email</h1>
                    <p>We've sent a verification link to your email address</p>
                </div>

                <div class="verification-content">
                    @if (session('status'))
                        <div class="success-message">
                            <i class="fas fa-check-circle"></i>
                            <p>{{ session('status') }}</p>
                        </div>
                    @endif

                    <div class="verification-info">
                        <div class="info-card">
                            <i class="fas fa-info-circle"></i>
                            <div>
                                <h3>Check Your Email</h3>
                                <p>We've sent a verification link to <strong>{{ Auth::user()->email }}</strong>. Please click the link in the email to verify your account.</p>
                            </div>
                        </div>

                        <div class="verification-steps">
                            <h4>What to do next:</h4>
                            <ol>
                                <li>
                                    <i class="fas fa-envelope"></i>
                                    <span>Check your email inbox (and spam folder)</span>
                                </li>
                                <li>
                                    <i class="fas fa-mouse-pointer"></i>
                                    <span>Click the verification link in the email</span>
                                </li>
                                <li>
                                    <i class="fas fa-rocket"></i>
                                    <span>Start your typing journey!</span>
                                </li>
                            </ol>
                        </div>
                    </div>

                    <div class="verification-actions">
                        <form method="POST" action="{{ route('verification.send') }}" class="resend-form">
                            @csrf
                            <button type="submit" class="btn btn-outline-primary btn-full">
                                <i class="fas fa-paper-plane"></i>
                                {{ __('Resend Verification Email') }}
                            </button>
                        </form>

                        <div class="action-links">
                            <a href="{{ route('profile.edit') }}" class="action-link">
                                <i class="fas fa-edit"></i>
                                Change Email Address
                            </a>
                            <a href="{{ route('logout') }}" class="action-link" 
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt"></i>
                                Use Different Account
                            </a>
                        </div>
                    </div>
                </div>

                <div class="demo-section">
                    <div class="divider">
                        <span>or continue as guest</span>
                    </div>
                    <a href="{{ route('guest.practice') }}" class="btn btn-secondary btn-full">
                        <i class="fas fa-play"></i>
                        Practice Without Verification
                    </a>
                </div>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.auth-container {
    min-height: calc(100vh - 80px);
    display: flex;
    align-items: center;
    padding: 2rem 0;
}

.auth-wrapper {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 600px;
}

.auth-card {
    background: var(--gradient-card);
    backdrop-filter: blur(var(--blur-amount));
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius-lg);
    padding: 3rem;
    width: 100%;
    max-width: 550px;
    box-shadow: var(--shadow-card);
    position: relative;
    overflow: hidden;
}

.auth-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: var(--gradient-accent);
}

.auth-header {
    text-align: center;
    margin-bottom: 2.5rem;
}

.auth-icon {
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
    box-shadow: 0 8px 25px rgba(255, 107, 157, 0.3);
}

.auth-header h1 {
    font-size: 2rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 0.5rem;
}

.auth-header p {
    color: var(--text-secondary);
    font-size: 1rem;
}

.verification-content {
    margin-bottom: 2rem;
}

.success-message {
    background: rgba(16, 185, 129, 0.1);
    border: 1px solid rgba(16, 185, 129, 0.3);
    border-radius: var(--border-radius);
    padding: 1rem 1.5rem;
    margin-bottom: 2rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    color: var(--success);
    font-weight: 500;
}

.success-message i {
    font-size: 1.25rem;
}

.verification-info {
    margin-bottom: 2rem;
}

.info-card {
    background: rgba(59, 130, 246, 0.1);
    border: 1px solid rgba(59, 130, 246, 0.2);
    border-radius: var(--border-radius);
    padding: 1.5rem;
    display: flex;
    gap: 1rem;
    margin-bottom: 2rem;
}

.info-card i {
    color: var(--info);
    font-size: 1.5rem;
    margin-top: 0.25rem;
    flex-shrink: 0;
}

.info-card h3 {
    color: var(--text-primary);
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.info-card p {
    color: var(--text-secondary);
    line-height: 1.5;
    margin: 0;
}

.info-card strong {
    color: var(--accent-pink);
}

.verification-steps h4 {
    color: var(--text-primary);
    font-size: 1rem;
    font-weight: 600;
    margin-bottom: 1rem;
}

.verification-steps ol {
    list-style: none;
    padding: 0;
    counter-reset: step-counter;
}

.verification-steps li {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 0.75rem 0;
    position: relative;
    counter-increment: step-counter;
}

.verification-steps li::before {
    content: counter(step-counter);
    background: var(--gradient-button);
    color: white;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.8rem;
    font-weight: 600;
    flex-shrink: 0;
}

.verification-steps li i {
    color: var(--accent-pink);
    width: 16px;
    margin-left: 0.5rem;
    flex-shrink: 0;
}

.verification-steps li span {
    color: var(--text-secondary);
    font-size: 0.95rem;
}

.verification-actions {
    text-align: center;
}

.resend-form {
    margin-bottom: 2rem;
}

.btn-full {
    width: 100%;
    padding: 1rem;
    font-size: 1rem;
    font-weight: 600;
}

.action-links {
    display: flex;
    justify-content: center;
    gap: 2rem;
    flex-wrap: wrap;
}

.action-link {
    color: var(--text-secondary);
    text-decoration: none;
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
    padding: 0.5rem;
    border-radius: var(--border-radius);
}

.action-link:hover {
    color: var(--accent-pink);
    background: rgba(255, 107, 157, 0.1);
    transform: translateY(-1px);
}

.demo-section {
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
}

.divider {
    text-align: center;
    margin: 1.5rem 0;
    position: relative;
}

.divider::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    height: 1px;
    background: rgba(255, 255, 255, 0.1);
}

.divider span {
    background: var(--bg-card);
    color: var(--text-muted);
    padding: 0 1rem;
    font-size: 0.9rem;
}

/* Animation for success message */
.success-message {
    animation: slideInDown 0.5s ease-out;
}

@keyframes slideInDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Loading state for resend button */
.btn-loading {
    position: relative;
    color: transparent !important;
}

.btn-loading::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 20px;
    height: 20px;
    border: 2px solid transparent;
    border-top-color: currentColor;
    border-radius: 50%;
    transform: translate(-50%, -50%);
    animation: spin 1s linear infinite;
}

@keyframes spin {
    to {
        transform: translate(-50%, -50%) rotate(360deg);
    }
}

@media (max-width: 768px) {
    .auth-card {
        padding: 2rem 1.5rem;
        margin: 1rem;
    }
    
    .auth-header h1 {
        font-size: 1.75rem;
    }
    
    .auth-icon {
        width: 60px;
        height: 60px;
        font-size: 1.5rem;
    }
    
    .info-card {
        padding: 1rem;
        flex-direction: column;
        text-align: center;
    }
    
    .action-links {
        flex-direction: column;
        gap: 1rem;
    }
    
    .verification-steps li {
        padding: 1rem 0;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const resendForm = document.querySelector('.resend-form');
    const resendButton = resendForm.querySelector('button');
    
    // Handle resend form submission
    resendForm.addEventListener('submit', function(e) {
        // Add loading state
        resendButton.classList.add('btn-loading');
        resendButton.disabled = true;
        
        // Reset after 3 seconds (form will submit normally)
        setTimeout(() => {
            resendButton.classList.remove('btn-loading');
            resendButton.disabled = false;
        }, 3000);
    });
    
    // Auto-hide success message after 10 seconds
    const successMessage = document.querySelector('.success-message');
    if (successMessage) {
        setTimeout(() => {
            successMessage.style.opacity = '0';
            successMessage.style.transform = 'translateY(-20px)';
            setTimeout(() => {
                successMessage.remove();
            }, 300);
        }, 10000);
    }
    
    // Countdown timer for resend button (optional)
    let countdown = 60;
    const originalText = resendButton.innerHTML;
    
    function updateCountdown() {
        if (countdown > 0) {
            resendButton.innerHTML = <i class="fas fa-clock"></i> Resend in ${countdown}s;
            resendButton.disabled = true;
            countdown--;
            setTimeout(updateCountdown, 1000);
        } else {
            resendButton.innerHTML = originalText;
            resendButton.disabled = false;
        }
    }
    
    // Start countdown only if there's a success message (email was just sent)
    if (successMessage) {
        updateCountdown();
    }
});
</script>
@endsection