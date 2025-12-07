@extends('layouts.academy')

@section('title', 'Player Registration - Vipers Academy')

@section('content')
<div class="registration-compact">
    <!-- Hero Section -->
    <div class="hero-compact">
        <div class="hero-overlay"></div>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="hero-content text-center">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Registration Form -->
    <div class="form-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="form-card">
                        <div class="form-header">
                            <div class="status-badge">
                                <span class="status-dot"></span>
                                <span>Registration Active</span>
                            </div>
                            <h2 class="form-title">Create Your Account</h2>
                            <p class="form-subtitle">Start your journey with thousands of aspiring footballers</p>
                        </div>

                        <!-- Progress Steps -->
                        <div class="progress-steps">
                            <div class="step active">
                                <span class="step-number">1</span>
                                <span class="step-label">Account</span>
                            </div>
                            <div class="step-line"></div>
                            <div class="step">
                                <span class="step-number">2</span>
                                <span class="step-label">Profile</span>
                            </div>
                            <div class="step-line"></div>
                            <div class="step">
                                <span class="step-number">3</span>
                                <span class="step-label">Complete</span>
                            </div>
                        </div>

                        <form action="{{ route('register.player.store') }}" method="POST" class="registration-form">
                            @csrf

                            <!-- Full Name -->
                            <div class="form-group">
                                <label class="form-label">
                                    Full Name <span class="required">*</span>
                                </label>
                                <div class="input-wrapper">
                                    <input type="text"
                                           class="form-control @error('name') is-invalid @enderror"
                                           id="name"
                                           name="name"
                                           value="{{ old('name') }}"
                                           placeholder="Enter your full name"
                                           required>
                                </div>
                                @error('name')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Email Address -->
                            <div class="form-group">
                                <label class="form-label">
                                    Email Address <span class="required">*</span>
                                </label>
                                <div class="input-wrapper">
                                    <input type="email"
                                           class="form-control @error('email') is-invalid @enderror"
                                           id="email"
                                           name="email"
                                           value="{{ old('email') }}"
                                           placeholder="your.email@example.com"
                                           required>
                                </div>
                                @error('email')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div class="form-group">
                                <label class="form-label">
                                    Password <span class="required">*</span>
                                </label>
                                <div class="input-wrapper">
                                    <input type="password"
                                           class="form-control @error('password') is-invalid @enderror"
                                           id="password"
                                           name="password"
                                           placeholder="Create a strong password"
                                           required
                                           minlength="8">
                                    <button type="button" class="password-toggle" onclick="togglePassword()">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <div class="password-strength">
                                    <div class="strength-meter">
                                        <div class="strength-fill" id="strengthFill"></div>
                                    </div>
                                    <span class="strength-text" id="strengthText">Password strength</span>
                                </div>
                                @error('password')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Confirm Password -->
                            <div class="form-group">
                                <label class="form-label">
                                    Confirm Password <span class="required">*</span>
                                </label>
                                <div class="input-wrapper">
                                    <input type="password"
                                           class="form-control"
                                           id="password_confirmation"
                                           name="password_confirmation"
                                           placeholder="Re-enter your password"
                                           required>
                                </div>
                            </div>

                            <!-- Terms & Conditions -->
                            <div class="form-group">
                                <label class="checkbox-container">
                                    <input class="checkbox-input @error('terms_accepted') checkbox-error @enderror"
                                           type="checkbox"
                                           id="terms_accepted"
                                           name="terms_accepted"
                                           value="1"
                                           required>
                                    <span class="checkmark"></span>
                                    <span class="checkbox-label">
                                        I agree to the <a href="#" class="link-primary">Terms & Conditions</a> and <a href="#" class="link-primary">Privacy Policy</a>
                                    </span>
                                </label>
                                @error('terms_accepted')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="btn-submit">
                                <span class="btn-text">Create My Account</span>
                                <i class="fas fa-arrow-right btn-icon"></i>
                            </button>
                        </form>

                        <!-- Social Login -->
                        <div class="social-divider">
                            <span>or continue with</span>
                        </div>

                        <div class="social-buttons">
                            <button class="btn-social google-btn">
                                <i class="fab fa-google"></i>
                                <span>Google</span>
                            </button>
                            <button class="btn-social facebook-btn">
                                <i class="fab fa-facebook"></i>
                                <span>Facebook</span>
                            </button>
                        </div>

                        <!-- Sign In Link -->
                        <div class="signin-link">
                            <span>Already have an account?</span>
                            <a href="{{ route('login') }}" class="link-secondary">Sign In</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Compact Registration Page */
.registration-compact {
    min-height: 100vh;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    position: relative;
    overflow: hidden;
}

.registration-compact::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('https://images.unsplash.com/photo-1574629810360-7efbbe195018?auto=format&fit=crop&w=1920&q=80') center/cover no-repeat;
    opacity: 0.1;
}

/* Hero Section */
.hero-compact {
    padding: 60px 0 40px;
    position: relative;
    z-index: 2;
}

.hero-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(45deg,
        rgba(102, 126, 234, 0.1) 0%,
        rgba(118, 75, 162, 0.1) 50%,
        rgba(139, 92, 246, 0.1) 100%);
}

.hero-content {
    position: relative;
    z-index: 2;
    color: white;
}

.hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(10px);
    padding: 0.5rem 1rem;
    border-radius: 50px;
    margin-bottom: 1.5rem;
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.hero-badge i {
    color: #fbbf24;
    font-size: 1rem;
}

.hero-badge span {
    font-weight: 600;
    font-size: 0.9rem;
    letter-spacing: 0.5px;
}

.hero-title {
    font-size: 2.5rem;
    font-weight: 800;
    margin-bottom: 1rem;
    line-height: 1.2;
}

.hero-title .highlight {
    background: linear-gradient(135deg, #fbbf24, #f59e0b);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.hero-subtitle {
    font-size: 1rem;
    margin-bottom: 2rem;
    opacity: 0.9;
    line-height: 1.6;
    max-width: 600px;
    margin-left: auto;
    margin-right: auto;
}

/* Features Grid */
.features-compact {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-bottom: 2rem;
}

.feature-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem;
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border-radius: 12px;
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.feature-item i {
    color: #fbbf24;
    font-size: 1.2rem;
    width: 20px;
}

.feature-item span {
    font-weight: 500;
    font-size: 0.9rem;
}

/* Stats */
.stats-compact {
    display: flex;
    justify-content: center;
    gap: 2rem;
}

.stat-item {
    text-align: center;
}

.stat-number {
    display: block;
    font-size: 2rem;
    font-weight: 800;
    color: #fbbf24;
    line-height: 1;
}

.stat-label {
    display: block;
    font-size: 0.8rem;
    opacity: 0.9;
    font-weight: 500;
    margin-top: 0.25rem;
}

/* Form Section */
.form-section {
    padding: 40px 0 60px;
    position: relative;
    z-index: 2;
}

.form-card {
    background: white;
    border-radius: 20px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    padding: 2.5rem;
    position: relative;
    margin-top: -30px;
}

/* Form Header */
.form-header {
    text-align: center;
    margin-bottom: 2rem;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: linear-gradient(135deg, #d1fae5, #a7f3d0);
    padding: 0.5rem 1rem;
    border-radius: 50px;
    margin-bottom: 1rem;
    font-size: 0.8rem;
    font-weight: 600;
    color: #065f46;
}

.status-dot {
    width: 6px;
    height: 6px;
    background: #10b981;
    border-radius: 50%;
    animation: pulse 2s ease-in-out infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}

.form-title {
    font-size: 1.75rem;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 0.5rem;
}

.form-subtitle {
    color: #6b7280;
    font-size: 0.9rem;
}

/* Progress Steps */
.progress-steps {
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 2rem;
    padding: 1rem;
    background: #f8fafc;
    border-radius: 12px;
}

.step {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
}

.step.active .step-number {
    background: #ea1c4d;
    color: white;
}

.step .step-number {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: #e5e7eb;
    color: #6b7280;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 0.8rem;
    transition: all 0.3s ease;
}

.step .step-label {
    font-size: 0.7rem;
    color: #6b7280;
    font-weight: 500;
}

.step-line {
    width: 50px;
    height: 2px;
    background: #e5e7eb;
    margin: 0 0.75rem;
}

/* Form Styles */
.registration-form {
    margin-bottom: 2rem;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-label {
    display: block;
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
}

.required {
    color: #ef4444;
}

.input-wrapper {
    position: relative;
}

.form-control {
    width: 100%;
    padding: 0.875rem 1rem;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: white;
}

.form-control:focus {
    outline: none;
    border-color: #ea1c4d;
    box-shadow: 0 0 0 3px rgba(234, 28, 77, 0.1);
}

.form-control.is-invalid {
    border-color: #ef4444;
}

.password-toggle {
    position: absolute;
    right: 1rem;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: #9ca3af;
    cursor: pointer;
    padding: 0.25rem;
    transition: color 0.2s;
}

.password-toggle:hover {
    color: #ea1c4d;
}

.password-strength {
    margin-top: 0.5rem;
}

.strength-meter {
    height: 3px;
    background: #e5e7eb;
    border-radius: 2px;
    overflow: hidden;
    margin-bottom: 0.25rem;
}

.strength-fill {
    height: 100%;
    width: 0%;
    transition: all 0.3s ease;
    border-radius: 2px;
}

.strength-text {
    font-size: 0.75rem;
    color: #6b7280;
}

/* Checkbox */
.checkbox-container {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    cursor: pointer;
    position: relative;
}

.checkbox-input {
    position: absolute;
    opacity: 0;
    cursor: pointer;
}

.checkmark {
    width: 18px;
    height: 18px;
    background: white;
    border: 2px solid #d1d5db;
    border-radius: 4px;
    position: relative;
    flex-shrink: 0;
    transition: all 0.2s ease;
}

.checkbox-container:hover .checkmark {
    border-color: #ea1c4d;
}

.checkbox-input:checked ~ .checkmark {
    background: #ea1c4d;
    border-color: #ea1c4d;
}

.checkmark::after {
    content: '';
    position: absolute;
    left: 5px;
    top: 2px;
    width: 5px;
    height: 9px;
    border: solid white;
    border-width: 0 2px 2px 0;
    transform: rotate(45deg);
    opacity: 0;
    transition: opacity 0.2s;
}

.checkbox-input:checked ~ .checkmark::after {
    opacity: 1;
}

.checkbox-label {
    font-size: 0.85rem;
    color: #6b7280;
    line-height: 1.5;
    margin-top: 1px;
}

.link-primary {
    color: #ea1c4d;
    text-decoration: none;
    font-weight: 500;
}

.link-primary:hover {
    text-decoration: underline;
}

/* Submit Button */
.btn-submit {
    width: 100%;
    background: linear-gradient(135deg, #ea1c4d 0%, #c0173f 100%);
    border: none;
    border-radius: 12px;
    padding: 1rem 2rem;
    color: white;
    font-weight: 600;
    font-size: 1rem;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    margin-top: 1rem;
}

.btn-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(234, 28, 77, 0.3);
}

.btn-icon {
    transition: transform 0.3s ease;
}

.btn-submit:hover .btn-icon {
    transform: translateX(4px);
}

/* Social Login */
.social-divider {
    text-align: center;
    margin: 2rem 0 1.5rem;
    position: relative;
}

.social-divider::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    height: 1px;
    background: #e5e7eb;
}

.social-divider span {
    background: white;
    padding: 0 1rem;
    color: #6b7280;
    font-size: 0.85rem;
    font-weight: 500;
}

.social-buttons {
    display: flex;
    gap: 0.75rem;
    margin-bottom: 2rem;
}

.btn-social {
    flex: 1;
    padding: 0.875rem 1rem;
    border: 2px solid #e5e7eb;
    border-radius: 10px;
    background: white;
    color: #374151;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.btn-social:hover {
    border-color: #ea1c4d;
    color: #ea1c4d;
    transform: translateY(-1px);
}

.google-btn:hover {
    border-color: #ea4335;
    color: #ea4335;
}

.facebook-btn:hover {
    border-color: #1877f2;
    color: #1877f2;
}

/* Sign In Link */
.signin-link {
    text-align: center;
    padding-top: 1.5rem;
    border-top: 1px solid #e5e7eb;
}

.signin-link span {
    color: #6b7280;
    font-size: 0.9rem;
}

.link-secondary {
    color: #ea1c4d;
    text-decoration: none;
    font-weight: 600;
    margin-left: 0.5rem;
}

.link-secondary:hover {
    text-decoration: underline;
}

/* Error Messages */
.error-message {
    color: #ef4444;
    font-size: 0.8rem;
    margin-top: 0.25rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .hero-compact {
        padding: 40px 0 30px;
    }

    .hero-title {
        font-size: 2rem;
    }

    .features-compact {
        grid-template-columns: repeat(2, 1fr);
        gap: 0.75rem;
    }

    .feature-item {
        padding: 0.5rem;
        font-size: 0.8rem;
    }

    .stats-compact {
        gap: 1.5rem;
    }

    .stat-number {
        font-size: 1.5rem;
    }

    .form-card {
        padding: 2rem;
        margin-top: -20px;
    }

    .form-title {
        font-size: 1.5rem;
    }

    .progress-steps {
        padding: 0.75rem;
    }

    .social-buttons {
        flex-direction: column;
    }
}

@media (max-width: 576px) {
    .features-compact {
        grid-template-columns: 1fr;
    }

    .stats-compact {
        flex-direction: column;
        gap: 1rem;
    }

    .form-card {
        padding: 1.5rem;
        border-radius: 16px;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Password strength checker
    const passwordInput = document.getElementById('password');
    const strengthFill = document.getElementById('strengthFill');
    const strengthText = document.getElementById('strengthText');

    passwordInput.addEventListener('input', function() {
        const password = this.value;
        let strength = 0;

        if (password.length >= 8) strength++;
        if (/[a-z]/.test(password)) strength++;
        if (/[A-Z]/.test(password)) strength++;
        if (/[0-9]/.test(password)) strength++;
        if (/[^A-Za-z0-9]/.test(password)) strength++;

        const percentage = (strength / 5) * 100;
        strengthFill.style.width = percentage + '%';

        if (strength <= 2) {
            strengthFill.style.background = '#ef4444';
            strengthText.textContent = 'Weak password';
            strengthText.style.color = '#ef4444';
        } else if (strength <= 3) {
            strengthFill.style.background = '#f59e0b';
            strengthText.textContent = 'Fair password';
            strengthText.style.color = '#f59e0b';
        } else if (strength <= 4) {
            strengthFill.style.background = '#3b82f6';
            strengthText.textContent = 'Good password';
            strengthText.style.color = '#3b82f6';
        } else {
            strengthFill.style.background = '#10b981';
            strengthText.textContent = 'Strong password';
            strengthText.style.color = '#10b981';
        }
    });

    // Password toggle visibility
    window.togglePassword = function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        const icon = document.querySelector('.password-toggle i');
        icon.className = type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
    };

    // Form validation
    const form = document.querySelector('.registration-form');
    form.addEventListener('submit', function(e) {
        const password = passwordInput.value;
        const confirm = document.getElementById('password_confirmation').value;

        if (password !== confirm) {
            e.preventDefault();
            alert('Passwords do not match!');
            return false;
        }

        if (password.length < 8) {
            e.preventDefault();
            alert('Password must be at least 8 characters long!');
            return false;
        }
    });
});
</script>
@endpush
