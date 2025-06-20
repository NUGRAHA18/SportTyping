{{-- resources/views/auth/verify-email.blade.php --}}
@extends('layouts.app')

@section('title', 'Verify Email - SportTyping')

@section('content')
<div class="verify-email-container">
    <div class="container">
        <div class="verify-email-card">
            <div class="verify-header">
                <div class="verify-icon">
                    <i class="fas fa-envelope-circle-check"></i>
                </div>
                <h1 class="verify-title">Verify Your Email</h1>
                <p class="verify-subtitle">We've sent a verification link to your email address</p>
            </div>
            
            <div class="verify-content">
                <div class="email-info">
                    <div class="email-display">
                        <i class="fas fa-envelope"></i>
                        <span>{{ auth()->user()->email }}</span>
                    </div>
                    <p class="verify-message">
                        Thanks for signing up for SportTyping! Before getting started, please verify your email address by clicking the link we just sent to you.
                    </p>
                </div>

                @if (session('status') == 'verification-link-sent')
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        <div>
                            <strong>Email Sent!</strong>
                            <p>A new verification link has been sent to your email address.</p>
                        </div>
                    </div>
                @endif

                <div class="verify-actions">
                    <form method="POST" action="{{ route('verification.send') }}" class="resend-form">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-paper-plane"></i>
                            Resend Verification Email
                        </button>
                    </form>
                    
                    <div class="verify-divider">
                        <span>or</span>
                    </div>
                    
                    <form method="POST" action="{{ route('logout') }}" class="logout-form">
                        @csrf
                        <button type="submit" class="btn btn-outline-secondary">
                            <i class="fas fa-sign-out-alt"></i>
                            Sign Out
                        </button>
                    </form>
                </div>

                <div class="verify-help">
                    <h4>Didn't receive the email?</h4>
                    <ul class="help-list">
                        <li>
                            <i class="fas fa-search"></i>
                            Check your spam or junk folder
                        </li>
                        <li>
                            <i class="fas fa-clock"></i>
                            Wait a few minutes for the email to arrive
                        </li>
                        <li>
                            <i class="fas fa-shield-alt"></i>
                            Make sure {{ config('mail.from.address') }} is not blocked
                        </li>
                        <li>
                            <i class="fas fa-envelope"></i>
                            Click "Resend Verification Email" to try again
                        </li>
                    </ul>
                </div>
                
                <div class="verify-benefits">
                    <h4>Why verify your email?</h4>
                    <div class="benefits-grid">
                        <div class="benefit-item">
                            <div class="benefit-icon">
                                <i class="fas fa-shield-check"></i>
                            </div>
                            <div class="benefit-content">
                                <h5>Account Security</h5>
                                <p>Protect your account and typing progress</p>
                            </div>
                        </div>
                        <div class="benefit-item">
                            <div class="benefit-icon">
                                <i class="fas fa-bell"></i>
                            </div>
                            <div class="benefit-content">
                                <h5>Important Updates</h5>
                                <p>Get notified about competitions and achievements</p>
                            </div>
                        </div>
                        <div class="benefit-item">
                            <div class="benefit-icon">
                                <i class="fas fa-trophy"></i>
                            </div>
                            <div class="benefit-content">
                                <h5>Full Access</h5>
                                <p>Unlock all features and join competitions</p>
                            </div>
                        </div>
                        <div class="benefit-item">
                            <div class="benefit-icon">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <div class="benefit-content">
                                <h5>Progress Tracking</h5>
                                <p>Save your typing statistics and improvements</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.verify-email-container {
    min-height: 100vh;
    background: linear-gradient(135deg, var(--bg-secondary), var(--bg-tertiary));
    display: flex;
    align-items: center;
    padding: 2rem 0;
}

.verify-email-card {
    max-width: 600px;
    margin: 0 auto;
    background: var(--bg-card);
    border-radius: var(--border-radius-xl);
    box-shadow: var(--shadow-xl);
    overflow: hidden;
    animation: slideInUp 0.6s ease-out;
}

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

.verify-header {
    background: var(--champion-gradient);
    color: white;
    text-align: center;
    padding: 3rem 2rem 2rem;
    position: relative;
}

.verify-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.1'%3E%3Ccircle cx='30' cy='30' r='4'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat;
    opacity: 0.3;
}

.verify-icon {
    font-size: 4rem;
    margin-bottom: 1rem;
    position: relative;
    z-index: 1;
    animation: bounce 2s ease-in-out infinite;
}

@keyframes bounce {
    0%, 20%, 50%, 80%, 100% {
        transform: translateY(0);
    }
    40% {
        transform: translateY(-10px);
    }
    60% {
        transform: translateY(-5px);
    }
}

.verify-title {
    font-family: var(--font-display);
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    position: relative;
    z-index: 1;
}

.verify-subtitle {
    font-size: 1.1rem;
    opacity: 0.9;
    position: relative;
    z-index: 1;
}

.verify-content {
    padding: 2rem;
}

.email-info {
    text-align: center;
    margin-bottom: 2rem;
}

.email-display {
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem 1.5rem;
    background: var(--bg-secondary);
    border-radius: var(--border-radius-lg);
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 1rem;
    border: 2px solid var(--border-light);
}

.email-display i {
    color: var(--accent-primary);
    font-size: 1.2rem;
}

.verify-message {
    color: var(--text-secondary);
    line-height: 1.6;
    font-size: 1rem;
}

.alert {
    padding: 1rem 1.5rem;
    border-radius: var(--border-radius);
    margin-bottom: 2rem;
    display: flex;
    align-items: flex-start;
    gap: 1rem;
}

.alert-success {
    background: rgba(16, 185, 129, 0.1);
    border: 1px solid rgba(16, 185, 129, 0.3);
    color: var(--accent-success);
}

.alert i {
    font-size: 1.2rem;
    margin-top: 0.1rem;
}

.verify-actions {
    text-align: center;
    margin-bottom: 3rem;
}

.resend-form {
    margin-bottom: 1.5rem;
}

.verify-divider {
    position: relative;
    margin: 1.5rem 0;
    text-align: center;
}

.verify-divider::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    height: 1px;
    background: var(--border-light);
}

.verify-divider span {
    background: var(--bg-card);
    padding: 0 1rem;
    color: var(--text-muted);
    font-size: 0.9rem;
    position: relative;
}

.verify-help {
    background: var(--bg-secondary);
    padding: 1.5rem;
    border-radius: var(--border-radius);
    margin-bottom: 2rem;
}

.verify-help h4 {
    font-family: var(--font-display);
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.verify-help h4::before {
    content: '?';
    width: 24px;
    height: 24px;
    background: var(--accent-info);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.8rem;
    font-weight: 700;
}

.help-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.help-list li {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.5rem 0;
    color: var(--text-secondary);
}

.help-list i {
    color: var(--accent-primary);
    width: 16px;
    text-align: center;
}

.verify-benefits h4 {
    font-family: var(--font-display);
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 1.5rem;
    text-align: center;
}

.benefits-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
}

.benefit-item {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    padding: 1rem;
    background: var(--bg-secondary);
    border-radius: var(--border-radius);
    border: 1px solid var(--border-light);
    transition: var(--transition-normal);
}

.benefit-item:hover {
    background: var(--bg-tertiary);
    transform: translateY(-2px);
    box-shadow: var(--shadow-sm);
}

.benefit-icon {
    width: 48px;
    height: 48px;
    border-radius: var(--border-radius);
    background: var(--champion-gradient);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    flex-shrink: 0;
}

.benefit-content h5 {
    font-family: var(--font-display);
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 0.25rem;
    font-size: 1rem;
}

.benefit-content p {
    color: var(--text-secondary);
    font-size: 0.9rem;
    line-height: 1.4;
    margin: 0;
}

/* Button Loading States */
.btn:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}

.btn.loading {
    position: relative;
    color: transparent;
}

.btn.loading::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 20px;
    height: 20px;
    margin: -10px 0 0 -10px;
    border: 2px solid currentColor;
    border-radius: 50%;
    border-right-color: transparent;
    animation: btn-spin 0.6s linear infinite;
}

@keyframes btn-spin {
    to { transform: rotate(360deg); }
}

/* Responsive Design */
@media (max-width: 768px) {
    .verify-email-container {
        padding: 1rem 0;
    }
    
    .verify-email-card {
        margin: 0 1rem;
    }
    
    .verify-header {
        padding: 2rem 1.5rem 1.5rem;
    }
    
    .verify-title {
        font-size: 2rem;
    }
    
    .verify-content {
        padding: 1.5rem;
    }
    
    .benefits-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .benefit-item {
        flex-direction: column;
        text-align: center;
    }
    
    .benefit-icon {
        margin: 0 auto;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle form submissions with loading states
    const forms = document.querySelectorAll('form');
    
    forms.forEach(form => {
        form.addEventListener('submit', function() {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.classList.add('loading');
                submitBtn.disabled = true;
            }
        });
    });
    
    // Auto-refresh page after successful email send
    @if(session('status') == 'verification-link-sent')
        setTimeout(() => {
            const successAlert = document.querySelector('.alert-success');
            if (successAlert) {
                successAlert.style.animation = 'pulse 1s ease-in-out infinite';
            }
        }, 1000);
    @endif
});
</script>
@endsection