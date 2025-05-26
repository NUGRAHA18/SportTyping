@extends('layouts.app')

@section('content')
<div class="auth-container">
    <div class="container">
        <div class="auth-wrapper">
            <div class="auth-card">
                <div class="auth-header">
                    <div class="auth-icon">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <h1>Join SportTyping</h1>
                    <p>Create your account and start competing</p>
                </div>

                <form method="POST" action="{{ route('register') }}" class="auth-form">
                    @csrf

                    <div class="form-group">
                        <label for="username" class="form-label">
                            <i class="fas fa-user"></i>
                            {{ __('Username') }}
                        </label>
                        <input id="username" type="text" 
                               class="form-control @error('username') is-invalid @enderror" 
                               name="username" value="{{ old('username') }}" 
                               required autocomplete="username" autofocus
                               placeholder="Choose a unique username">
                        @error('username')
                            <div class="invalid-feedback">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">
                            <i class="fas fa-envelope"></i>
                            {{ __('Email Address') }}
                        </label>
                        <input id="email" type="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               name="email" value="{{ old('email') }}" 
                               required autocomplete="email"
                               placeholder="Enter your email address">
                        @error('email')
                            <div class="invalid-feedback">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">
                            <i class="fas fa-lock"></i>
                            {{ __('Password') }}
                        </label>
                        <div class="password-input">
                            <input id="password" type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   name="password" required autocomplete="new-password"
                                   placeholder="Create a strong password">
                            <button type="button" class="password-toggle" onclick="togglePassword('password')">
                                <i class="fas fa-eye" id="passwordToggleIcon"></i>
                            </button>
                        </div>
                        <div class="password-requirements">
                            <small class="text-muted">
                                <i class="fas fa-info-circle"></i>
                                Password must contain uppercase, lowercase, number, and special character
                            </small>
                        </div>
                        @error('password')
                            <div class="invalid-feedback">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password-confirm" class="form-label">
                            <i class="fas fa-lock"></i>
                            {{ __('Confirm Password') }}
                        </label>
                        <div class="password-input">
                            <input id="password-confirm" type="password" 
                                   class="form-control" name="password_confirmation" 
                                   required autocomplete="new-password"
                                   placeholder="Confirm your password">
                            <button type="button" class="password-toggle" onclick="togglePassword('password-confirm')">
                                <i class="fas fa-eye" id="passwordConfirmToggleIcon"></i>
                            </button>
                        </div>
                    </div>

                    <!-- reCAPTCHA -->
                    <div class="form-group">
                        {!! NoCaptcha::renderJs() !!}
                        <div class="recaptcha-wrapper">
                            {!! NoCaptcha::display() !!}
                        </div>
                        @error('g-recaptcha-response')
                            <div class="invalid-feedback d-block">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <div class="terms-check">
                            <input class="form-check-input" type="checkbox" id="terms" required>
                            <label class="form-check-label" for="terms">
                                I agree to the <a href="#" class="terms-link">Terms of Service</a> and <a href="#" class="terms-link">Privacy Policy</a>
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-full">
                        <i class="fas fa-rocket"></i>
                        {{ __('Create Account') }}
                    </button>
                </form>

                <div class="auth-footer">
                    <p>Already have an account?</p>
                    <a href="{{ route('login') }}" class="auth-link">
                        {{ __('Sign In') }}
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>

                <div class="demo-section">
                    <div class="divider">
                        <span>or</span>
                    </div>
                    <a href="{{ route('guest.practice') }}" class="btn btn-outline-primary btn-full">
                        <i class="fas fa-play"></i>
                        Try Without Account
                    </a>
                </div>
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
    min-height: 700px;
}

.auth-card {
    background: var(--gradient-card);
    backdrop-filter: blur(var(--blur-amount));
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius-lg);
    padding: 3rem;
    width: 100%;
    max-width: 500px;
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

.auth-form {
    margin-bottom: 2rem;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--text-primary);
    font-weight: 500;
    margin-bottom: 0.75rem;
    font-size: 0.95rem;
}

.form-control {
    background: rgba(255, 255, 255, 0.05);
    border: 2px solid rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius);
    color: var(--text-primary);
    padding: 1rem 1.25rem;
    width: 100%;
    transition: all 0.3s ease;
    font-size: 1rem;
}

.form-control:focus {
    outline: none;
    border-color: var(--accent-pink);
    background: rgba(255, 255, 255, 0.08);
    box-shadow: 0 0 0 3px rgba(255, 107, 157, 0.1);
}

.form-control::placeholder {
    color: var(--text-muted);
}

.form-control.is-invalid {
    border-color: var(--error);
}

.password-input {
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
    border-radius: 50%;
    transition: all 0.3s ease;
}

.password-toggle:hover {
    color: var(--accent-pink);
    background: rgba(255, 107, 157, 0.1);
}

.password-requirements {
    margin-top: 0.5rem;
}

.password-requirements small {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--text-muted);
    font-size: 0.85rem;
}

.terms-check {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
}

.form-check-input {
    width: 18px;
    height: 18px;
    min-width: 18px;
    background: rgba(255, 255, 255, 0.05);
    border: 2px solid rgba(255, 255, 255, 0.2);
    border-radius: 4px;
    cursor: pointer;
    margin-top: 2px;
}

.form-check-input:checked {
    background: var(--accent-pink);
    border-color: var(--accent-pink);
}

.form-check-label {
    color: var(--text-secondary);
    cursor: pointer;
    font-size: 0.9rem;
    line-height: 1.4;
}

.terms-link {
    color: var(--accent-pink);
    text-decoration: none;
    transition: color 0.3s ease;
}

.terms-link:hover {
    color: var(--accent-cyan);
}

.recaptcha-wrapper {
    display: flex;
    justify-content: center;
    margin: 1rem 0;
}

.invalid-feedback {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--error);
    font-size: 0.9rem;
    margin-top: 0.5rem;
}

.btn-full {
    width: 100%;
    padding: 1rem;
    font-size: 1.1rem;
    font-weight: 600;
}

.auth-footer {
    text-align: center;
    padding-top: 1.5rem;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
}

.auth-footer p {
    color: var(--text-secondary);
    margin-bottom: 1rem;
}

.auth-link {
    color: var(--accent-pink);
    text-decoration: none;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
}

.auth-link:hover {
    color: var(--accent-cyan);
    transform: translateX(3px);
}

.demo-section {
    margin-top: 2rem;
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

/* Password strength indicator */
.password-strength {
    margin-top: 0.5rem;
    height: 4px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 2px;
    overflow: hidden;
}

.password-strength-bar {
    height: 100%;
    width: 0%;
    transition: width 0.3s ease, background-color 0.3s ease;
}

.strength-weak { background: var(--error); width: 25%; }
.strength-fair { background: var(--warning); width: 50%; }
.strength-good { background: var(--info); width: 75%; }
.strength-strong { background: var(--success); width: 100%; }

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
    
    .form-check-label {
        font-size: 0.85rem;
    }
}
</style>

<script>
function togglePassword(inputId) {
    const passwordInput = document.getElementById(inputId);
    const toggleIcon = document.getElementById(inputId + 'ToggleIcon');
    
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

// Password strength checker
function checkPasswordStrength(password) {
    let strength = 0;
    
    if (password.length >= 8) strength++;
    if (/[a-z]/.test(password)) strength++;
    if (/[A-Z]/.test(password)) strength++;
    if (/[0-9]/.test(password)) strength++;
    if (/[^A-Za-z0-9]/.test(password)) strength++;
    
    return strength;
}

document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('.auth-form');
    const inputs = form.querySelectorAll('.form-control');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('password-confirm');
    
    // Add password strength indicator
    if (passwordInput) {
        const strengthIndicator = document.createElement('div');
        strengthIndicator.className = 'password-strength';
        strengthIndicator.innerHTML = '<div class="password-strength-bar"></div>';
        passwordInput.parentElement.appendChild(strengthIndicator);
        
        const strengthBar = strengthIndicator.querySelector('.password-strength-bar');
        
        passwordInput.addEventListener('input', function() {
            const strength = checkPasswordStrength(this.value);
            strengthBar.className = 'password-strength-bar';
            
            if (strength <= 2) strengthBar.classList.add('strength-weak');
            else if (strength === 3) strengthBar.classList.add('strength-fair');
            else if (strength === 4) strengthBar.classList.add('strength-good');
            else if (strength === 5) strengthBar.classList.add('strength-strong');
        });
    }
    
    // Password confirmation validation
    if (confirmPasswordInput) {
        confirmPasswordInput.addEventListener('input', function() {
            if (this.value !== passwordInput.value && this.value.length > 0) {
                this.style.borderColor = 'var(--error)';
            } else {
                this.style.borderColor = 'rgba(255, 255, 255, 0.1)';
            }
        });
    }
    
    // Form validation feedback
    inputs.forEach(input => {
        input.addEventListener('input', function() {
            if (this.classList.contains('is-invalid')) {
                this.classList.remove('is-invalid');
                const feedback = this.parentElement.querySelector('.invalid-feedback');
                if (feedback) {
                    feedback.style.display = 'none';
                }
            }
        });
    });
});
</script>
@endsection