@extends('layouts.auth')

@section('title', 'Sign In')

@section('content')
    <!-- Header -->
    <div class="auth-header">
        <div class="auth-logo" style="background: transparent; box-shadow: none;">
            <img src="{{ asset('assets/img/logo/GameSuite.png') }}" alt="Gamesuite Logo" style="width: 120px; height: 120px; object-fit: contain;">
        </div>
        <h1 class="auth-title">Welcome back</h1>
        <p class="auth-subtitle">Sign in to continue to Gamesuite</p>
    </div>

    <!-- Session Status -->
    @if (session('status'))
        <div class="alert alert-success visible">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('status') }}</span>
        </div>
    @endif

    <!-- Validation Errors -->
    @if ($errors->any())
        <div class="alert alert-danger visible">
            <i class="fas fa-exclamation-circle"></i>
            <div>
                @if ($errors->count() === 1)
                    {{ $errors->first() }}
                @else
                    <ul style="margin: 0; padding-left: 1rem;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    @endif

    <!-- Login Form -->
    <form method="POST" action="{{ route('login') }}" class="auth-form" id="loginForm" novalidate>
        @csrf

        <!-- Email -->
        <div class="form-group">
            <label for="email" class="form-label required">Email address</label>
            <div class="form-input-wrapper">
                <input
                    type="email"
                    id="email"
                    name="email"
                    class="form-input has-icon @error('email', 'login') error @enderror"
                    placeholder="you@example.com"
                    value="{{ old('email') }}"
                    required
                    autocomplete="username"
                    autofocus
                    aria-describedby="email-error"
                >
                <i class="form-input-icon fas fa-envelope"></i>
            </div>
            @error('email', 'login')
                <span class="form-error visible" id="email-error" role="alert">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>{{ $message }}</span>
                </span>
            @enderror
        </div>

        <!-- Password -->
        <div class="form-group">
            <label for="password" class="form-label required">Password</label>
            <div class="form-input-wrapper">
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="form-input has-icon @error('password', 'login') error @enderror"
                    placeholder="Enter your password"
                    required
                    autocomplete="current-password"
                    aria-describedby="password-error"
                >
                <i class="form-input-icon fas fa-lock"></i>
                <button type="button" class="password-toggle" aria-label="Toggle password visibility">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
            @error('password', 'login')
                <span class="form-error visible" id="password-error" role="alert">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>{{ $message }}</span>
                </span>
            @enderror
        </div>

        <!-- Remember & Forgot -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
            <div class="form-checkbox-group" style="gap: 0.5rem;">
                <input type="checkbox" id="remember" name="remember" class="form-checkbox" {{ old('remember') ? 'checked' : '' }}>
                <label for="remember" class="form-checkbox-label">Remember me</label>
            </div>
            <a href="{{ route('password.request') }}" style="font-size: 0.8125rem; color: var(--primary); text-decoration: none; font-weight: 500;">
                Forgot password?
            </a>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn-primary" id="loginBtn">
            <span>Sign in</span>
        </button>
    </form>

    <!-- Footer -->
    <div class="auth-footer">
        <p>Don't have an account? <a href="{{ route('signup') }}">Sign up</a></p>
    </div>
@endsection

@section('branding')
    <!-- Branding Section for Login -->
    <div class="branding-logo">
        <div class="branding-logo-icon" style="padding: 8px; background: white; border-radius: 8px;">
            <img src="{{ asset('assets/img/logo/GameSuite.png') }}" alt="Gamesuite Logo" style="width: 48px; height: 48px; object-fit: contain;">
        </div>
    </div>

    <div class="branding-content">
        <h2 class="branding-title">Level Up Your Gaming Experience</h2>
        <p class="branding-description">
            Access powerful tournament management tools and organize your gaming community.
        </p>
    </div>

    <div class="branding-footer">
        <p class="branding-footer-text">© 2025 Gamesuite. All rights reserved.</p>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('loginForm');
        const loginBtn = document.getElementById('loginBtn');
        const emailInput = document.getElementById('email');
        const passwordInput = document.getElementById('password');

        // Real-time validation
        const validateEmail = () => {
            const email = emailInput.value.trim();
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (email && !emailRegex.test(email)) {
                showError(emailInput, 'Please enter a valid email address');
                return false;
            }
            clearError(emailInput);
            return true;
        };

        const validatePassword = () => {
            const password = passwordInput.value;

            if (password && password.length < 1) {
                showError(passwordInput, 'Password is required');
                return false;
            }
            clearError(passwordInput);
            return true;
        };

        const showError = (input, message) => {
            input.classList.add('error');
            let errorEl = input.parentElement.parentElement.querySelector('.form-error');
            if (!errorEl) {
                errorEl = document.createElement('span');
                errorEl.className = 'form-error visible';
                errorEl.innerHTML = `<i class="fas fa-exclamation-circle"></i><span>${message}</span>`;
                input.parentElement.parentElement.appendChild(errorEl);
            } else {
                errorEl.classList.add('visible');
                errorEl.querySelector('span').textContent = message;
            }
        };

        const clearError = (input) => {
            input.classList.remove('error');
            const errorEl = input.parentElement.parentElement.querySelector('.form-error');
            if (errorEl) {
                errorEl.classList.remove('visible');
            }
        };

        // Input event listeners
        emailInput.addEventListener('blur', validateEmail);
        emailInput.addEventListener('input', () => {
            if (emailInput.classList.contains('error')) {
                validateEmail();
            }
        });

        passwordInput.addEventListener('blur', validatePassword);
        passwordInput.addEventListener('input', () => {
            if (passwordInput.classList.contains('error')) {
                validatePassword();
            }
        });

        // Form submission
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const isEmailValid = validateEmail();
            const isPasswordValid = validatePassword();

            if (!isEmailValid || !isPasswordValid) {
                return;
            }

            // Show loading state
            loginBtn.disabled = true;
            loginBtn.innerHTML = '<i class="fas fa-spinner spinner"></i><span>Signing in...</span>';

            // Submit form
            form.submit();
        });

        // Clear Laravel validation errors on input
        [emailInput, passwordInput].forEach(input => {
            input.addEventListener('input', function() {
                this.classList.remove('error');
                const errorEl = this.parentElement.parentElement.querySelector('.form-error');
                if (errorEl && errorEl.classList.contains('visible')) {
                    // Only clear if it's a JS-generated error (not Laravel)
                    if (!errorEl.querySelector('ul')) {
                        errorEl.classList.remove('visible');
                    }
                }
            });
        });
    });
</script>
@endpush
