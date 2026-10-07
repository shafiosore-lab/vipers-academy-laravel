@extends('layouts.auth')

@section('title', 'Forgot Password')

@section('content')
    <!-- Header -->
    <div class="auth-header">
        <div class="auth-logo" style="background: transparent; box-shadow: none;">
            <img src="{{ asset('assets/img/logo/GameSuite.png') }}" alt="Gamesuite Logo" style="width: 120px; height: 120px; object-fit: contain;">
        </div>
        <h1 class="auth-title">Forgot password?</h1>
        <p class="auth-subtitle">No problem. Enter your email and we'll send you a reset link.</p>
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

    <!-- Forgot Password Form -->
    <form method="POST" action="{{ route('password.email') }}" class="auth-form" id="forgotPasswordForm" novalidate>
        @csrf

        <!-- Email -->
        <div class="form-group">
            <label for="email" class="form-label required">Email address</label>
            <div class="form-input-wrapper">
                <input
                    type="email"
                    id="email"
                    name="email"
                    class="form-input has-icon"
                    placeholder="you@example.com"
                    value="{{ old('email') }}"
                    required
                    autocomplete="username"
                    autofocus
                >
                <i class="form-input-icon fas fa-envelope"></i>
            </div>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn-primary" id="submitBtn">
            <span>Send Reset Link</span>
        </button>
    </form>

    <!-- Footer -->
    <div class="auth-footer">
        <p>Remember your password? <a href="{{ route('login') }}">Sign in</a></p>
    </div>
@endsection

@section('branding')
    <!-- Branding Section for Forgot Password -->
    <div class="branding-logo">
        <div class="branding-logo-icon" style="padding: 8px; background: white; border-radius: 8px;">
            <img src="{{ asset('assets/img/logo/GameSuite.png') }}" alt="Gamesuite Logo" style="width: 48px; height: 48px; object-fit: contain;">
        </div>
    </div>

    <div class="branding-content">
        <h2 class="branding-title">Recover Your Account</h2>
        <p class="branding-description">
            No worries! Enter your email address and we'll send you a reset link.
        </p>
    </div>

    <div class="branding-footer">
        <p class="branding-footer-text">© 2025 Gamesuite. All rights reserved.</p>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('forgotPasswordForm');
        const submitBtn = document.getElementById('submitBtn');
        const emailInput = document.getElementById('email');

        // Validate email
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

        const showError = (input, message) => {
            input.classList.add('error');
            const group = input.closest('.form-group');
            let errorEl = group.querySelector('.form-error');

            if (!errorEl) {
                errorEl = document.createElement('span');
                errorEl.className = 'form-error visible';
                errorEl.innerHTML = `<i class="fas fa-exclamation-circle"></i><span>${message}</span>`;
                group.appendChild(errorEl);
            } else {
                errorEl.classList.add('visible');
                errorEl.querySelector('span').textContent = message;
            }
        };

        const clearError = (input) => {
            input.classList.remove('error');
            const group = input.closest('.form-group');
            const errorEl = group.querySelector('.form-error');
            if (errorEl) {
                errorEl.classList.remove('visible');
            }
        };

        emailInput.addEventListener('blur', validateEmail);
        emailInput.addEventListener('input', () => {
            if (emailInput.classList.contains('error')) {
                validateEmail();
            }
        });

        // Form submission
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const isEmailValid = validateEmail();

            if (!isEmailValid) {
                return;
            }

            // Show loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner spinner"></i><span>Sending...</span>';

            // Submit form
            form.submit();
        });
    });
</script>
@endpush
