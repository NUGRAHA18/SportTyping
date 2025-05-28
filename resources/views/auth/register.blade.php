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
                <h1>Join the Elite Athletes</h1>
                <p>Create your champion account and start your journey to typing mastery. Compete, improve, and dominate the leaderboards.</p>
                
                <div class="feature-highlights">
                    <div class="highlight-item">
                        <i class="fas fa-users"></i>
                        <span>Join 10K+ Typing Athletes</span>
                    </div>
                    <div class="highlight-item">
                        <i class="fas fa-graduation-cap"></i>
                        <span>Professional Training Courses</span>
                    </div>
                    <div class="highlight-item">
                        <i class="fas fa-trophy"></i>
                        <span>Compete in Live Tournaments</span>
                    </div>
                    <div class="highlight-item">
                        <i class="fas fa-chart-bar"></i>
                        <span>Track Your Progress</span>
                    </div>
                </div>
                
                <div class="membership-benefits">
                    <h3>Membership Benefits</h3>
                    <div class="benefits-grid">
                        <div class="benefit-item">
                            <i class="fas fa-medal"></i>
                            <span>Earn Prestigious Badges</span>
                        </div>
                        <div class="benefit-item">
                            <i class="fas fa-crown"></i>
                            <span>Climb League Rankings</span>
                        </div>
                        <div class="benefit-item">
                            <i class="fas fa-mobile-alt"></i>
                            <span>Cross-Platform Support</span>
                        </div>
                        <div class="benefit-item">
                            <i class="fas fa-infinity"></i>
                            <span>Unlimited Practice</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="floating-elements">
                <div class="floating-trophy"></div>
                <div class="floating-medal"></div>
                <div class="floating-crown"></div>
                <div class="floating-star"></div>
            </div>
        </div>

        <!-- Right Side - Register Form -->
        <div class="auth-form-section">
            <div class="form-container">
                <div class="form-header">
                    <h2>Create Account</h2>
                    <p>Start your typing championship journey</p>
                </div>

                <form method="POST" action="{{ route('register') }}" class="auth-form">
                    @csrf

                    <div class="form-group">
                        <label for="username" class="form-label">
                            <i class="fas fa-user"></i>
                            Champion Username
                        </label>
                        <input id="username" 
                               type="text" 
                               class="form-input @error('username') error @enderror" 
                               name="username" 
                               value="{{ old('username') }}" 
                               required 
                               autocomplete="username" 
                               autofocus
                               placeholder="TypingChampion2025">
                        @error('username')
                            <span class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </span>
                        @enderror
                        <div class="input-hint">
                            <i class="fas fa-info-circle"></i>
                            Choose a unique username that represents you in competitions
                        </div>
                    </div>

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
                               placeholder="champion@example.com">
                        @error('email')
                            <span class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <div class="form-row">
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
                                       autocomplete="new-password"
                                       placeholder="Create strong password">
                                <button type="button" class="password-toggle" onclick="togglePassword('password', 'passwordToggleIcon')">
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

                        <div class="form-group">
                            <label for="password-confirm" class="form-label">
                                <i class="fas fa-shield-alt"></i>
                                Confirm Password
                            </label>
                            <div class="password-input-wrapper">
                                <input id="password-confirm" 
                                       type="password" 
                                       class="form-input" 
                                       name="password_confirmation" 
                                       required 
                                       autocomplete="new-password"
                                       placeholder="Confirm your password">
                                <button type="button" class="password-toggle" onclick="togglePassword('password-confirm', 'confirmToggleIcon')">
                                    <i class="fas fa-eye" id="confirmToggleIcon"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Password Strength Indicator -->
                    <div class="password-strength" id="passwordStrength" style="display: none;">
                        <div class="strength-bar">
                            <div class="strength-fill" id="strengthFill"></div>
                        </div>
                        <div class="strength-text" id="strengthText">Password Strength</div>
                        <div class="strength-requirements">
                            <div class="requirement" id="req-length">
                                <i class="fas fa-times"></i> At least 8 characters
                            </div>
                            <div class="requirement" id="req-upper">
                                <i class="fas fa-times"></i> One uppercase letter
                            </div>
                            <div class="requirement" id="req-lower">
                                <i class="fas fa-times"></i> One lowercase letter
                            </div>
                            <div class="requirement" id="req-number">
                                <i class="fas fa-times"></i> One number
                            </div>
                            <div class="requirement" id="req-special">
                                <i class="fas fa-times"></i> One special character
                            </div>
                        </div>
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

                    <!-- Terms & Conditions -->
                    <div class="terms-container">
                        <label class="checkbox-container">
                            <input type="checkbox" name="terms" id="terms" required>
                            <span class="checkmark"></span>
                            <span class="checkbox-label">
                                I agree to the 
                                <a href="#" class="terms-link">Terms of Service</a> 
                                and 
                                <a href="#" class="terms-link">Privacy Policy</a>
                            </span>
                        </label>
                    </div>

                    <button type="submit" class="btn-primary btn-full" id="registerBtn">
                        <i class="fas fa-user-plus"></i>
                        <span>Join the Championship</span>
                        <div class="btn-shine"></div>
                    </button>

                    <div class="form-divider">
                        <span>or</span>
                    </div>

                    <a href="{{ route('guest.practice') }}" class="btn-secondary btn-full">
                        <i class="fas fa-play"></i>
                        <span>Try as Guest First</span>
                    </a>
                </form>

                <div class="form-footer">
                    <p>Already have an account? 
                        <a href="{{ route('login') }}" class="login-link">
                            Sign in to continue
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
    max-width: 1300px;
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
    margin-bottom: 2rem;
    line-height: 1.6;
}

.feature-highlights {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    margin-bottom: 2rem;
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
    width: 20px;
}

.membership-benefits {
    margin-top: 2rem;
}

.membership-benefits h3 {
    font-family: var(--font-display);
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 1rem;
    color: #fbbf24;
}

.benefits-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0.75rem;
}

.benefit-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-size: 0.9rem;
    opacity: 0.9;
}

.benefit-item i {
    color: #fbbf24;
    width: 16px;
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
.floating-crown,
.floating-star {
    position: absolute;
    width: 50px;
    height: 50px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    color: #fbbf24;
    animation: float 8s ease-in-out infinite;
}

.floating-trophy {
    top: 15%;
    right: 15%;
    animation-delay: 0s;
}

.floating-medal {
    top: 45%;
    right: 5%;
    animation-delay: 2s;
}

.floating-crown {
    bottom: 30%;
    right: 20%;
    animation-delay: 4s;
}

.floating-star {
    top: 70%;
    right: 10%;
    animation-delay: 6s;
}

.floating-trophy::before { content: '🏆'; }
.floating-medal::before { content: '🥇'; }
.floating-crown::before { content: '👑'; }
.floating-star::before { content: '⭐'; }

@keyframes float {
    0%, 100% { 
        transform: translateY(0) rotate(0deg); 
        opacity: 0.6;
    }
    50% { 
        transform: translateY(-15px) rotate(5deg); 
        opacity: 0.8;
    }
}

/* Right Side - Form */
.auth-form-section {
    padding: 2.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--bg-card);
    overflow-y: auto;
}

.form-container {
    width: 100%;
    max-width: 450px;
}

.form-header {
    text-align: center;
    margin-bottom: 2rem;
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
    gap: 1.25rem;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
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

.input-hint {
    color: var(--text-muted);
    font-size: 0.8rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-top: 0.25rem;
}

.error-message {
    color: var(--accent-danger);
    font-size: 0.875rem;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

/* Password Strength */
.password-strength {
    padding: 1rem;
    background: var(--bg-secondary);
    border-radius: var(--border-radius);
    border: 1px solid var(--border-light);
}

.strength-bar {
    height: 8px;
    background: var(--border-light);
    border-radius: 4px;
    overflow: hidden;
    margin-bottom: 0.75rem;
}

.strength-fill {
    height: 100%;
    transition: all 0.3s ease;
    border-radius: 4px;
}

.strength-text {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 0.75rem;
}

.strength-requirements {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0.5rem;
}

.requirement {
    font-size: 0.8rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--text-muted);
}

.requirement.met {
    color: var(--accent-success);
}

.requirement.met i {
    color: var(--accent-success);
}

.requirement i {
    width: 12px;
    color: var(--accent-danger);
}

.captcha-container {
    display: flex;
    justify-content: center;
    margin: 1rem 0;
}

.terms-container {
    margin: 1rem 0;
}

.checkbox-container {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    cursor: pointer;
    font-size: 0.9rem;
    color: var(--text-secondary);
    line-height: 1.5;
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
    flex-shrink: 0;
    margin-top: 2px;
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

.terms-link {
    color: var(--accent-primary);
    text-decoration: none;
    font-weight: 600;
}

.terms-link:hover {
    text-decoration: underline;
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

.btn-primary:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
}

.btn-primary:disabled {
    opacity: 0.6;
    cursor: not-allowed;
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

.login-link {
    color: var(--accent-primary);
    text-decoration: none;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
}

.login-link:hover {
    color: var(--accent-primary);
    transform: translateX(3px);
}

/* Responsive */
@media (max-width: 1024px) {
    .auth-wrapper {
        grid-template-columns: 1fr;
        max-width: 550px;
    }
    
    .auth-branding {
        display: none;
    }
    
    .auth-form-section {
        padding: 2rem;
    }
    
    .form-row {
        grid-template-columns: 1fr;
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
    
    .benefits-grid {
        grid-template-columns: 1fr;
    }
    
    .strength-requirements {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
function togglePassword(inputId, iconId) {
    const passwordInput = document.getElementById(inputId);
    const toggleIcon = document.getElementById(iconId);
    
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
    const requirements = {
        length: password.length >= 8,
        upper: /[A-Z]/.test(password),
        lower: /[a-z]/.test(password),
        number: /\d/.test(password),
        special: /[@$!%*?&]/.test(password)
    };
    
    const metCount = Object.values(requirements).filter(Boolean).length;
    let strength = 0;
    let strengthText = '';
    let strengthColor = '';
    
    if (metCount === 0) {
        strength = 0;
        strengthText = 'Very Weak';
        strengthColor = '#ef4444';
    } else if (metCount <= 2) {
        strength = 25;
        strengthText = 'Weak';
        strengthColor = '#ef4444';
    } else if (metCount <= 3) {
        strength = 50;
        strengthText = 'Fair';
        strengthColor = '#f59e0b';
    } else if (metCount <= 4) {
        strength = 75;
        strengthText = 'Good';
        strengthColor = '#10b981';
    } else {
        strength = 100;
        strengthText = 'Excellent';
        strengthColor = '#10b981';
    }
    
    return { requirements, strength, strengthText, strengthColor };
}

function updatePasswordStrength() {
    const password = document.getElementById('password').value;
    const strengthContainer = document.getElementById('passwordStrength');
    const strengthFill = document.getElementById('strengthFill');
    const strengthText = document.getElementById('strengthText');
    
    if (password.length === 0) {
        strengthContainer.style.display = 'none';
        return;
    }
    
    strengthContainer.style.display = 'block';
    
    const { requirements, strength, strengthText: text, strengthColor } = checkPasswordStrength(password);
    
    strengthFill.style.width = strength + '%';
    strengthFill.style.background = strengthColor;
    strengthText.textContent = text;
    strengthText.style.color = strengthColor;
    
    // Update requirement indicators
    Object.keys(requirements).forEach(req => {
        const element = document.getElementById(`req-${req}`);
        const icon = element.querySelector('i');
        
        if (requirements[req]) {
            element.classList.add('met');
            icon.classList.remove('fa-times');
            icon.classList.add('fa-check');
        } else {
            element.classList.remove('met');
            icon.classList.remove('fa-check');
            icon.classList.add('fa-times');
        }
    });
}

// Form validation and enhancements
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('.auth-form');
    const inputs = form.querySelectorAll('.form-input');
    const passwordInput = document.getElementById('password');
    const confirmInput = document.getElementById('password-confirm');
    const termsCheckbox = document.getElementById('terms');
    const registerBtn = document.getElementById('registerBtn');
    
    // Password strength monitoring
    passwordInput.addEventListener('input', updatePasswordStrength);
    
    // Password confirmation validation
    function validatePasswordMatch() {
        if (confirmInput.value && passwordInput.value !== confirmInput.value) {
            confirmInput.style.borderColor = 'var(--accent-danger)';
            confirmInput.style.boxShadow = '0 0 0 3px rgba(239, 68, 68, 0.1)';
        } else if (confirmInput.value) {
            confirmInput.style.borderColor = 'var(--accent-success)';
            confirmInput.style.boxShadow = '0 0 0 3px rgba(16, 185, 129, 0.1)';
        }
    }
    
    confirmInput.addEventListener('input', validatePasswordMatch);
    passwordInput.addEventListener('input', validatePasswordMatch);
    
    // Input validation enhancement
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            if (this.value && !this.classList.contains('error')) {
                this.style.borderColor = 'var(--accent-success)';
            }
        });
        
        input.addEventListener('focus', function() {
            this.style.borderColor = 'var(--accent-primary)';
            this.style.boxShadow = 'var(--sport-glow)';
        });
    });
    
    // Terms checkbox validation
    function updateSubmitButton() {
        if (termsCheckbox.checked) {
            registerBtn.disabled = false;
        } else {
            registerBtn.disabled = true;
        }
    }
    
    termsCheckbox.addEventListener('change', updateSubmitButton);
    updateSubmitButton(); // Initial check
    
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

// Username availability checker (mock)
let usernameTimeout;
document.getElementById('username').addEventListener('input', function() {
    clearTimeout(usernameTimeout);
    const username = this.value;
    
    if (username.length >= 3) {
        usernameTimeout = setTimeout(() => {
            // Mock availability check
            const isAvailable = !['admin', 'test', 'user', 'champion'].includes(username.toLowerCase());
            const input = this;
            
            if (isAvailable) {
                input.style.borderColor = 'var(--accent-success)';
                input.style.boxShadow = '0 0 0 3px rgba(16, 185, 129, 0.1)';
            } else {
                input.style.borderColor = 'var(--accent-danger)';
                input.style.boxShadow = '0 0 0 3px rgba(239, 68, 68, 0.1)';
            }
        }, 500);
    }
});
</script>
@endsection
