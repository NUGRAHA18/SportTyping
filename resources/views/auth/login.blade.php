@extends('layouts.app')

@section('content')
<div class="auth-container">
    <div class="container">
        <div class="auth-wrapper">
            <div class="auth-card">
                <div class="auth-header">
                    <div class="auth-icon">
                        <i class="fas fa-keyboard"></i>
                    </div>
                    <h1>Welcome Back</h1>
                    <p>Sign in to continue your typing journey</p>
                </div>

                <form method="POST" action="{{ route('login') }}" class="auth-form">
                    @csrf

                    <div class="form-group">
                        <label for="email" class="form-label">
                            <i class="fas fa-envelope"></i>
                            {{ __('Email Address') }}
                        </label>
                        <input id="email" type="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               name="email" value="{{ old('email') }}" 
                               required autocomplete="email" autofocus
                               placeholder="Enter your email">
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
                                   name="password" required autocomplete="current-password"
                                   placeholder="Enter your password">
                            <button type="button" class="password-toggle" onclick="togglePassword()">
                                <i class="fas fa-eye" id="passwordToggleIcon"></i>
                            </button>
                        </div>
                        @error('password')
                            <div class="invalid-feedback">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">
                                {{ __('Remember Me') }}
                            </label>
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

                    <button type="submit" class="btn btn-primary btn-full">
                        <i class="fas fa-sign-in-alt"></i>
                        {{ __('Sign In') }}
                    </button>
                </form>

                <div class="auth-footer">
                    <p>Don't have an account?</p>
                    <a href="{{ route('register') }}" class="auth-link">
                        {{ __('Create Account') }}
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
    min-height: 600px;
}

.auth-card {
    background: var(--gradient-card);
    backdrop-filter: blur(var(--blur-amount));
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius-lg);
    padding: 3rem;
    width: 100%;
    max-width: 450px;
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

.form-check {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.form-check-input {
    width: 18px;
    height: 18px;
    background: rgba(255, 255, 255, 0.05);
    border: 2px solid rgba(255, 255, 255, 0.2);
    border-radius: 4px;
    cursor: pointer;
}

.form-check-input:checked {
    background: var(--accent-pink);
    border-color: var(--accent-pink);
}

.form-check-label {
    color: var(--text-secondary);
    cursor: pointer;
    font-size: 0.95rem;
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

// Form validation feedback
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('.auth-form');
    const inputs = form.querySelectorAll('.form-control');
    
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