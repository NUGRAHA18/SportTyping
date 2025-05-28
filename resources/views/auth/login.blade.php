@extends('layouts.app')

@section('content')
<div class="auth-container">
    <div class="auth-wrapper">
        <!-- Left Side - Branding -->
        <div class="auth-branding">
            <div class="branding-content">
                <div class="brand-logo">
                    <i class="fas fa-trophy"></i>
                    <span>SportTyping</span>
                </div>
                <h1>Welcome Back, Champion!</h1>
                <p>Enter the arena and continue your typing mastery journey. Every keystroke counts towards your victory.</p>
                
                <div class="feature-highlights">
                    <div class="highlight-item">
                        <i class="fas fa-racing-flag"></i>
                        <span>Live Competitions</span>
                    </div>
                    <div class="highlight-item">
                        <i class="fas fa-medal"></i>
                        <span>Achievement System</span>
                    </div>
                    <div class="highlight-item">
                        <i class="fas fa-chart-line"></i>
                        <span>Performance Analytics</span>
                    </div>
                </div>
                
                <div class="stats-preview">
                    <div class="stat-item">
                        <span class="stat-number">10K+</span>
                        <span class="stat-label">Active Athletes</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">150+</span>
                        <span class="stat-label">Max WPM Record</span>
                    </div>
                </div>
            </div>
            
            <div class="floating-elements">
                <div class="floating-trophy"></div>
                <div class="floating-medal"></div>
                <div class="floating-crown"></div>
            </div>
        </div>

        <!-- Right Side - Login Form -->
        <div class="auth-form-section">
            <div class="form-container">
                <div class="form-header">
                    <h2>Sign In</h2>
                    <p>Access your training dashboard</p>
                </div>

                <form method="POST" action="{{ route('login') }}" class="auth-form">
                    @csrf

                    <div class="form-group">
                        <label for="email" class="form-label">
                            <i class="fas fa-envelope"></i>
                            Email Address
                        </label>
                        <input id="email" 
                               type="email" 
                               class="form-input @error('email') error @enderror" 
                               name="email" 
                               value="{{ old('email') }}" 
                               required 
                               autocomplete="email" 
                               autofocus
                               placeholder="champion@sporttyping.com">
                        @error('email')
                            <span class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">
                            <i class="fas fa-lock"></i>
                            Password
                        </label>
                        <div class="password-input-wrapper">
                            <input id="password" 
                                   type="password" 
                                   class="form-input @error('password') error @enderror" 
                                   name="password" 
                                   required 
                                   autocomplete="current-password"
                                   placeholder="Enter your password">
                            <button type="button" class="password-toggle" onclick="togglePassword()">
                                <i class="fas fa-eye" id="passwordToggleIcon"></i>
                            </button>
                        </div>
                        @error('password')
                            <span class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <div class="form-options">
                        <label class="checkbox-container">
                            <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <span class="checkmark"></span>
                            <span class="checkbox-label">Keep me signed in</span>
                        </label>
                        <a href="#" class="forgot-link">Forgot password?</a>
                    </div>

                    <!-- reCAPTCHA -->
                    <div class="captcha-container">
                        {!! NoCaptcha::renderJs() !!}
                        {!! NoCaptcha::display() !!}
                        @error('g-recaptcha-response')
                            <span class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <button type="submit" class="btn-primary btn-full">
                        <i class="fas fa-sign-in-alt"></i>
                        <span>Enter Arena</span>
                        <div class="btn-shine"></div>
                    </button>

                    <div class="form-divider">
                        <span>or</span>
                    </div>

                    <a href="{{ route('guest.practice') }}" class="btn-secondary btn-full">
                        <i class="fas fa-play"></i>
                        <span>Practice as Guest</span>
                    </a>
                </form>

                <div class="form-footer">
                    <p>New to SportTyping? 
                        <a href="{{ route('register') }}" class="register-link">
                            Create your champion account
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.auth-container {
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

.auth-container::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: 
        radial-gradient(circle at 20% 80%, rgba(59, 130, 246, 0.08) 0%, transparent 50%),
        radial-gradient(circle at 80% 20%, rgba(16, 185, 129, 0.08) 0%, transparent 50%);
    z-index: 1;
}

.auth-wrapper {
    max-width: 1200px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: 1fr 1fr;
    min-height: calc(100vh - 4rem);
    background: var(--bg-card);
    border-radius: var(--border-radius-xl);
    box-shadow: var(--shadow-xl);
    border: 1px solid var(--border-light);
    overflow: hidden;
    position: relative;
    z-index: 2;
}

/* Left Side - Branding */
.auth-branding {
    background: var(--champion-gradient);
    color: white;
    padding: 3rem;
    display: flex;
    flex-direction: column;
    justify-content: center;
    position: relative;
    overflow: hidden;
}

.auth-branding::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: 
        radial-gradient(circle at 30% 70%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
        radial-gradient(circle at 70% 30%, rgba(255, 255, 255, 0.1) 0%, transparent 50%);
}

.branding-content {
    position: relative;
    z-index: 2;
}

.brand-logo {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 2rem;
    font-family: var(--font-display);
    font-size: 2rem;
    font-weight: 700;
}

.brand-logo i {
    font-size: 2.5rem;
    color: #fbbf24;
}

.auth-branding h1 {
    font-family: var(--font-display);
    font-size: 2.5rem;
    font-weight: 800;
    margin-bottom: 1rem;
    line-height: 1.2;
}

.auth-branding p {
    font-size: 1.1rem;
    opacity: 0.9;
    margin-bottom: 2.5rem;
    line-height: 1.6;
}

.feature-highlights {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    margin-bottom: 2.5rem;
}

.highlight-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 0.75rem 1rem;
    background: rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    font-weight: 500;
    transition: all 0.3s ease;
}

.highlight-item:hover {
    background: rgba(255, 255, 255, 0.15);
    transform: translateX(5px);
}

.highlight-item i {
    color: #fbbf24;
    font-size: 1.2rem;
}

.stats-preview {
    display: flex;
    gap: 2rem;
}

.stat-item {
    text-align: left;
}

.stat-number {
    display: block;
    font-family: var(--font-display);
    font-size: 2rem;
    font-weight: 700;
    color: #fbbf24;
    line-height: 1.2;
}

.stat-label {
    font-size: 0.875rem;
    opacity: 0.8;
    font-weight: 500;
}

/* Floating Elements */
.floating-elements {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    overflow: hidden;
    pointer-events: none;
}

.floating-trophy,
.floating-medal,
.floating-crown {
    position: absolute;
    width: 60px;
    height: 60px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: #fbbf24;
    animation: float 8s ease-in-out infinite;
}

.floating-trophy {
    top: 10%;
    right: 10%;
    animation-delay: 0s;
}

.floating-medal {
    top: 60%;
    right: 20%;
    animation-delay: 2s;
}

.floating-crown {
    bottom: 20%;
    right: 5%;
    animation-delay: 4s;
}

.floating-trophy::before { content: '🏆'; }
.floating-medal::before { content: '🥇'; }
.floating-crown::before { content: '👑'; }

@keyframes float {
    0%, 100% { 
        transform: translateY(0) rotate(0deg); 
        opacity: 0.6;
    }
    50% { 
        transform: translateY(-20px) rotate(10deg); 
        opacity: 0.8;
    }
}

/* Right Side - Form */
.auth-form-section {
    padding: 3rem;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--bg-card);
}

.form-container {
    width: 100%;
    max-width: 400px;
}

.form-header {
    text-align: center;
    margin-bottom: 2.5rem;
}

.form-header h2 {
    font-family: var(--font-display);
    font-size: 2rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 0.5rem;
}

.form-header p {
    color: var(--text-secondary);
    font-size: 1rem;
}

.auth-form {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.form-label {
    color: var(--text-primary);
    font-weight: 600;
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.form-label i {
    color: var(--accent-primary);
    width: 16px;
}

.form-input {
    padding: 1rem 1.25rem;
    border: 2px solid var(--border-light);
    border-radius: var(--border-radius);
    font-size: 1rem;
    font-weight: 500;
    background: var(--bg-card);
    color: var(--text-primary);
    transition: all 0.3s ease;
}

.form-input:focus {
    outline: none;
    border-color: var(--accent-primary);
    box-shadow: var(--sport-glow);
    background: var(--bg-card);
}

.form-input.error {
    border-color: var(--accent-danger);
    box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
}

.form-input::placeholder {
    color: var(--text-muted);
}

.password-input-wrapper {
    position: relative;
}

.password-toggle {
    position: absolute;
    right: 1rem;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: var(--text-muted);
    cursor: pointer;
    padding: 0.5rem;
    transition: color 0.3s ease;
}

.password-toggle:hover {
    color: var(--accent-primary);
}

.error-message {
    color: var(--accent-danger);
    font-size: 0.875rem;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.form-options {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin: 0.5rem 0;
}

.checkbox-container {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    cursor: pointer;
    font-size: 0.9rem;
    color: var(--text-secondary);
}

.checkbox-container input[type="checkbox"] {
    display: none;
}

.checkmark {
    width: 20px;
    height: 20px;
    border: 2px solid var(--border-medium);
    border-radius: var(--border-radius-sm);
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.checkbox-container input[type="checkbox"]:checked + .checkmark {
    background: var(--champion-gradient);
    border-color: var(--accent-primary);
}

.checkbox-container input[type="checkbox"]:checked + .checkmark::after {
    content: '✓';
    color: white;
    font-weight: 700;
    font-size: 0.75rem;
}

.forgot-link {
    color: var(--accent-primary);
    text-decoration: none;
    font-size: 0.9rem;
    font-weight: 500;
    transition: all 0.3s ease;
}

.forgot-link:hover {
    color: var(--accent-primary);
    text-decoration: underline;
}

.captcha-container {
    display: flex;
    justify-content: center;
    margin: 1rem 0;
}

.btn-primary {
    background: var(--champion-gradient);
    border: none;
    color: white;
    padding: 1.25rem 2rem;
    border-radius: var(--border-radius);
    font-weight: 700;
    font-size: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    cursor: pointer;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
}

.btn-secondary {
    background: transparent;
    border: 2px solid var(--accent-primary);
    color: var(--accent-primary);
    padding: 1rem 2rem;
    border-radius: var(--border-radius);
    font-weight: 600;
    font-size: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
    transition: all 0.3s ease;
    text-decoration: none;
    cursor: pointer;
}

.btn-secondary:hover {
    background: var(--accent-primary);
    color: white;
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
    text-decoration: none;
}

.btn-full {
    width: 100%;
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

.form-divider {
    text-align: center;
    position: relative;
    margin: 1.5rem 0;
    color: var(--text-muted);
    font-size: 0.9rem;
}

.form-divider::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    height: 1px;
    background: var(--border-light);
    z-index: 1;
}

.form-divider span {
    background: var(--bg-card);
    padding: 0 1rem;
    position: relative;
    z-index: 2;
}

.form-footer {
    text-align: center;
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 1px solid var(--border-light);
}

.form-footer p {
    color: var(--text-secondary);
    font-size: 0.95rem;
}

.register-link {
    color: var(--accent-primary);
    text-decoration: none;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
}

.register-link:hover {
    color: var(--accent-primary);
    transform: translateX(3px);
}

/* Responsive */
@media (max-width: 1024px) {
    .auth-wrapper {
        grid-template-columns: 1fr;
        max-width: 500px;
    }
    
    .auth-branding {
        display: none;
    }
    
    .auth-form-section {
        padding: 2rem;
    }
}

@media (max-width: 768px) {
    .auth-container {
        padding: 1rem;
    }
    
    .auth-wrapper {
        margin: 0;
        border-radius: var(--border-radius-lg);
    }
    
    .auth-form-section {
        padding: 1.5rem;
    }
    
    .form-container {
        max-width: none;
    }
    
    .form-options {
        flex-direction: column;
        gap: 1rem;
        align-items: flex-start;
    }
}
</style>

<script>
function togglePassword() {
    const passwordInput = document.getElementById('password');
    const toggleIcon = document.getElementById('passwordToggleIcon');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.classList.remove('fa-eye');
        toggleIcon.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        toggleIcon.classList.remove('fa-eye-slash');
        toggleIcon.classList.add('fa-eye');
    }
}

// Form validation enhancement
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('.auth-form');
    const inputs = form.querySelectorAll('.form-input');
    
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            if (this.value && !this.classList.contains('error')) {
                this.style.borderColor = 'var(--accent-success)';
            }
        });
        
        input.addEventListener('focus', function() {
            this.style.borderColor = 'var(--accent-primary)';
        });
    });
    
    // Smooth form animation on load
    setTimeout(() => {
        form.style.opacity = '1';
        form.style.transform = 'translateY(0)';
    }, 100);
});

// Add initial form styles
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('.auth-form');
    form.style.opacity = '0';
    form.style.transform = 'translateY(20px)';
    form.style.transition = 'all 0.6s ease';
});
</script>
@endsection
