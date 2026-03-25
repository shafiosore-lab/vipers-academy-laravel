@extends('layouts.auth')

@section('title', 'Reset Password')

@section('content')
    <!-- Header -->
    <div class="auth-header">
        <div class="auth-logo">
            <i class="fas fa-key"></i>
        </div>
        <h1 class="auth-title">Set new password</h1>
        <p class="auth-subtitle">Enter your new password below.</p>
    </div>

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

    <!-- Reset Password Form -->
    <form method="POST" action="{{ route('password.store') }}" class="auth-form" id="resetPasswordForm" novalidate>
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

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
                    value="{{ old('email', $request->email) }}"
                    required
                    autocomplete="username"
                    readonly
                    style="background-color: var(--gray-100);"
                >
                <i class="form-input-icon fas fa-envelope"></i>
            </div>
        </div>

        <!-- Password -->
        <div class="form-group">
            <label for="password" class="form-label required">New Password</label>
            <div class="form-input-wrapper">
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="form-input has-icon"
                    placeholder="Create new password"
                    required
                    autocomplete="new-password"
                >
                <i class="form-input-icon fas fa-lock"></i>
                <button type="button" class="password-toggle" aria-label="Toggle password visibility">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
            <!-- Password Strength -->
            <div class="password-strength" id="passwordStrength">
                <div class="password-strength-bar">
                    <div class="password-strength-fill" id="passwordStrengthFill"></div>
                </div>
                <span class="password-strength-text" id="passwordStrengthText"></span>
            </div>
        </div>

        <!-- Confirm Password -->
        <div class="form-group">
            <label for="password_confirmation" class="form-label required">Confirm Password</label>
            <div class="form-input-wrapper">
                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    class="form-input has-icon"
                    placeholder="Confirm new password"
                    required
                    autocomplete="new-password"
                >
                <i class="form-input-icon fas fa-lock"></i>
                <button type="button" class="password-toggle" aria-label="Toggle password visibility">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
            <span class="form-error" id="passwordMatchError" style="display: none;">
                <i class="fas fa-exclamation-circle"></i>
                <span>Passwords do not match</span>
            </span>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn-primary" id="submitBtn">
            <span>Reset Password</span>
        </button>
    </form>

    <!-- Footer -->
    <div class="auth-footer">
        <p>Remember your password? <a href="{{ route('login') }}">Sign in</a></p>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('resetPasswordForm');
        const submitBtn = document.getElementById('submitBtn');
        const passwordInput = document.getElementById('password');
        const confirmPasswordInput = document.getElementById('password_confirmation');
        const passwordStrength = document.getElementById('passwordStrength');
        const passwordStrengthFill = document.getElementById('passwordStrengthFill');
        const passwordStrengthText = document.getElementById('passwordStrengthText');
        const passwordMatchError = document.getElementById('passwordMatchError');

        // Password strength calculation
        const calculatePasswordStrength = (password) => {
            let strength = 0;

            if (password.length >= 8) strength += 1;
            if (password.length >= 12) strength += 1;
            if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength += 1;
            if (/\d/.test(password)) strength += 1;
            if (/[^a-zA-Z0-9]/.test(password)) strength += 1;

            return strength;
        };

        // Update password strength display
        const updatePasswordStrength = () => {
            const password = passwordInput.value;

            if (password.length === 0) {
                passwordStrength.classList.remove('visible');
                passwordStrengthFill.className = 'password-strength-fill';
                return;
            }

            passwordStrength.classList.add('visible');
            const strength = calculatePasswordStrength(password);

            passwordStrengthFill.className = 'password-strength-fill';

            if (strength <= 2) {
                passwordStrengthFill.classList.add('weak');
                passwordStrengthText.textContent = 'Weak - Use at least 8 characters with letters and numbers';
            } else if (strength <= 3) {
                passwordStrengthFill.classList.add('medium');
                passwordStrengthText.textContent = 'Medium - Add uppercase letters for stronger security';
            } else {
                passwordStrengthFill.classList.add('strong');
                passwordStrengthText.textContent = 'Strong - Great password!';
            }
        };

        // Check password match
        const checkPasswordMatch = () => {
            const password = passwordInput.value;
            const confirmPassword = confirmPasswordInput.value;

            if (confirmPassword.length === 0) {
                passwordMatchError.style.display = 'none';
                confirmPasswordInput.classList.remove('error');
                return true;
            }

            if (password !== confirmPassword) {
                passwordMatchError.style.display = 'flex';
                confirmPasswordInput.classList.add('error');
                return false;
            } else {
                passwordMatchError.style.display = 'none';
                confirmPasswordInput.classList.remove('error');
                return true;
            }
        };

        // Event listeners
        passwordInput.addEventListener('input', () => {
            updatePasswordStrength();
            if (confirmPasswordInput.value) {
                checkPasswordMatch();
            }
        });

        confirmPasswordInput.addEventListener('input', checkPasswordMatch);

        // Form submission
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            let isValid = true;

            // Validate password
            if (!passwordInput.value) {
                passwordInput.classList.add('error');
                const group = passwordInput.closest('.form-group');
                let errorEl = group.querySelector('.form-error');
                if (!errorEl) {
                    errorEl = document.createElement('span');
                    errorEl.className = 'form-error visible';
                    errorEl.innerHTML = '<i class="fas fa-exclamation-circle"></i><span>Password is required</span>';
                    group.appendChild(errorEl);
                }
                isValid = false;
            } else if (passwordInput.value.length < 8) {
                passwordInput.classList.add('error');
                const group = passwordInput.closest('.form-group');
                let errorEl = group.querySelector('.form-error');
                if (!errorEl) {
                    errorEl = document.createElement('span');
                    errorEl.className = 'form-error visible';
                    errorEl.innerHTML = '<i class="fas fa-exclamation-circle"></i><span>Password must be at least 8 characters</span>';
                    group.appendChild(errorEl);
                }
                isValid = false;
            }

            // Validate password match
            if (!checkPasswordMatch()) {
                isValid = false;
            }

            if (!isValid) {
                return;
            }

            // Show loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner spinner"></i><span>Resetting...</span>';

            // Submit form
            form.submit();
        });

        // Clear errors on input
        [passwordInput, confirmPasswordInput].forEach(input => {
            input.addEventListener('input', function() {
                this.classList.remove('error');
                const group = this.closest('.form-group');
                const errorEl = group.querySelector('.form-error:not(#passwordMatchError)');
                if (errorEl) {
                    errorEl.classList.remove('visible');
                }
            });
        });
    });
</script>
@endpush
