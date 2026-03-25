@extends('layouts.auth')

@section('title', 'Create Account')

@section('content')
    <!-- Header -->
    <div class="auth-header">
        <div class="auth-logo" style="background: transparent; box-shadow: none;">
            <img src="{{ asset('assets/img/logo/GameSuite.png') }}" alt="Gamesuite Logo" style="width: 120px; height: 120px; object-fit: contain;">
        </div>
        <h1 class="auth-title" style="font-size: 1.25rem;">Create Account</h1>

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

    <!-- Signup Form -->
    <form method="POST" action="{{ route('signup.post') }}" class="auth-form" id="signupForm" novalidate>
        @csrf

        <!-- Account Type -->
        <div class="form-group" style="margin-bottom: 0;">
            <label for="account_type" class="form-label required" style="font-size: 0.8125rem;">Account Type</label>
            <select
                name="account_type"
                id="account_type"
                class="form-select @error('account_type') error @enderror"
                required
                style="padding: 0.5rem 2rem 0.5rem 0.5rem; font-size: 0.8125rem;"
            >
                <option value="" disabled {{ old('account_type') ? '' : 'selected' }}>Select type</option>
                <option value="player" {{ old('account_type') == 'player' ? 'selected' : '' }}>Player</option>
                <option value="coach" {{ old('account_type') == 'coach' ? 'selected' : '' }}>Coach</option>
                <option value="partner" {{ old('account_type') == 'partner' ? 'selected' : '' }}>Partner</option>
                <option value="team_manager" {{ old('account_type') == 'team_manager' ? 'selected' : 'selected' }}>Team Manager</option>
                <option value="organization" {{ old('account_type') == 'organization' ? 'selected' : '' }}>Organization</option>
            </select>
            @error('account_type')
                <span class="form-error visible" role="alert" style="font-size: 0.75rem;">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>{{ $message }}</span>
                </span>
            @enderror
        </div>

        <!-- Organization Name (Conditional) -->
        <div class="form-group organization-field" id="organizationNameGroup" style="display: none; margin-bottom: 0;">
            <label for="organization_name" class="form-label required" style="font-size: 0.8125rem;">Organization Name</label>
            <div class="form-input-wrapper">
                <input
                    type="text"
                    id="organization_name"
                    name="organization_name"
                    class="form-input has-icon"
                    placeholder="Your organization name"
                    value="{{ old('organization_name') }}"
                    style="padding: 0.5rem 0.75rem 0.5rem 2.25rem; font-size: 0.875rem;"
                >
                <i class="form-input-icon fas fa-building" style="left: 0.625rem; font-size: 0.8125rem;"></i>
            </div>
        </div>

        <!-- Name Row -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem;">
            <!-- First Name -->
            <div class="form-group" style="margin-bottom: 0;">
                <label for="first_name" class="form-label required" style="font-size: 0.8125rem;">First Name</label>
                <div class="form-input-wrapper">
                    <input
                        type="text"
                        id="first_name"
                        name="first_name"
                        class="form-input has-icon @error('first_name') error @enderror"
                        placeholder="John"
                        value="{{ old('first_name') }}"
                        required
                        style="padding: 0.5rem 0.75rem 0.5rem 2.25rem; font-size: 0.875rem;"
                    >
                    <i class="form-input-icon fas fa-user" style="left: 0.625rem; font-size: 0.8125rem;"></i>
                </div>
                @error('first_name')
                    <span class="form-error visible" role="alert" style="font-size: 0.75rem;">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>{{ $message }}</span>
                    </span>
                @enderror
            </div>

            <!-- Last Name -->
            <div class="form-group" style="margin-bottom: 0;">
                <label for="last_name" class="form-label required" style="font-size: 0.8125rem;">Last Name</label>
                <div class="form-input-wrapper">
                    <input
                        type="text"
                        id="last_name"
                        name="last_name"
                        class="form-input has-icon @error('last_name') error @enderror"
                        placeholder="Doe"
                        value="{{ old('last_name') }}"
                        required
                        style="padding: 0.5rem 0.75rem 0.5rem 2.25rem; font-size: 0.875rem;"
                    >
                    <i class="form-input-icon fas fa-user" style="left: 0.625rem; font-size: 0.8125rem;"></i>
                </div>
                @error('last_name')
                    <span class="form-error visible" role="alert" style="font-size: 0.75rem;">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>{{ $message }}</span>
                    </span>
                @enderror
            </div>
        </div>

        <!-- Email -->
        <div class="form-group" style="margin-bottom: 0;">
            <label for="email" class="form-label required" style="font-size: 0.8125rem;">Email Address</label>
            <div class="form-input-wrapper">
                <input
                    type="email"
                    id="email"
                    name="email"
                    class="form-input has-icon @error('email') error @enderror"
                    placeholder="you@example.com"
                    value="{{ old('email') }}"
                    required
                    style="padding: 0.5rem 0.75rem 0.5rem 2.25rem; font-size: 0.875rem;"
                >
                <i class="form-input-icon fas fa-envelope" style="left: 0.625rem; font-size: 0.8125rem;"></i>
            </div>
            @error('email')
                <span class="form-error visible" role="alert" style="font-size: 0.75rem;">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>{{ $message }}</span>
                </span>
            @enderror
        </div>

        <!-- Phone -->
        <div class="form-group" style="margin-bottom: 0;">
            <label for="phone" class="form-label" style="font-size: 0.8125rem;">Phone Number <span style="color: var(--gray-400); font-weight: 400;">(+254)</span></label>
            <div class="form-input-wrapper">
                <input
                    type="tel"
                    id="phone"
                    name="phone"
                    class="form-input has-icon"
                    placeholder="712 345 678"
                    value="{{ old('phone') }}"
                    pattern="[0-9]{3}[0-9]{3}[0-9]{3}"
                    style="padding: 0.5rem 0.75rem 0.5rem 2.25rem; font-size: 0.875rem;"
                >
                <i class="form-input-icon fas fa-phone" style="left: 0.625rem; font-size: 0.8125rem;"></i>
            </div>
            <small style="font-size: 0.6875rem; color: var(--gray-400);">Format: 712345678</small>
        </div>

        <!-- Password Row -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem;">
            <!-- Password -->
            <div class="form-group" style="margin-bottom: 0;">
                <label for="password" class="form-label required" style="font-size: 0.8125rem;">Password</label>
                <div class="form-input-wrapper">
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-input has-icon @error('password') error @enderror"
                        placeholder="Min 8 characters"
                        required
                        autocomplete="new-password"
                        style="padding: 0.5rem 2rem 0.5rem 2.25rem; font-size: 0.875rem;"
                    >
                    <i class="form-input-icon fas fa-lock" style="left: 0.625rem; font-size: 0.8125rem;"></i>
                    <button type="button" class="password-toggle" aria-label="Toggle password visibility" style="right: 0.5rem;">
                        <i class="fas fa-eye" style="font-size: 0.8125rem;"></i>
                    </button>
                </div>
                @error('password')
                    <span class="form-error visible" role="alert" style="font-size: 0.75rem;">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>{{ $message }}</span>
                    </span>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div class="form-group" style="margin-bottom: 0;">
                <label for="password_confirmation" class="form-label required" style="font-size: 0.8125rem;">Confirm</label>
                <div class="form-input-wrapper">
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        class="form-input has-icon @error('password_confirmation') error @enderror"
                        placeholder="Repeat password"
                        required
                        autocomplete="new-password"
                        style="padding: 0.5rem 2rem 0.5rem 2.25rem; font-size: 0.875rem;"
                    >
                    <i class="form-input-icon fas fa-lock" style="left: 0.625rem; font-size: 0.8125rem;"></i>
                    <button type="button" class="password-toggle" aria-label="Toggle password visibility" style="right: 0.5rem;">
                        <i class="fas fa-eye" style="font-size: 0.8125rem;"></i>
                    </button>
                </div>
                <span class="form-error" id="passwordMatchError" style="display: none; font-size: 0.75rem;">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>Passwords do not match</span>
                </span>
            </div>
        </div>

        <!-- Terms -->
        <div class="form-checkbox-group" style="margin-bottom: 0.5rem;">
            <input
                type="checkbox"
                id="terms"
                name="terms"
                class="form-checkbox @error('terms') error @enderror"
                required
            >
            <label for="terms" class="form-checkbox-label">
                I agree to the <a href="{{ route('terms') }}" target="_blank">Terms of Service</a> and <a href="{{ route('privacy') }}" target="_blank">Privacy Policy</a>
            </label>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn-primary" id="signupBtn">
            <span>Create account</span>
        </button>
    </form>

    <!-- Footer -->
    <div class="auth-footer">
        <p>Already have an account? <a href="{{ route('login') }}">Sign in</a></p>
    </div>
@endsection

@section('branding')
    <!-- Branding Section for Signup -->
    <div class="branding-logo">
        <div class="branding-logo-icon" style="padding: 8px; background: white; border-radius: 8px;">
            <img src="{{ asset('assets/img/logo/GameSuite.png') }}" alt="Gamesuite Logo" style="width: 48px; height: 48px; object-fit: contain;">
        </div>
    </div>

    <div class="branding-content">
        <h2 class="branding-title">Join Our Gaming Community</h2>
        <p class="branding-description">
            Create an account to access powerful tournament tools and connect with gamers.
        </p>
    </div>

    <div class="branding-footer">
        <p class="branding-footer-text">© 2025 Gamesuite. All rights reserved.</p>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('signupForm');
        const signupBtn = document.getElementById('signupBtn');
        const accountTypeSelect = document.getElementById('account_type');
        const organizationNameGroup = document.getElementById('organizationNameGroup');

        // Password elements
        const passwordInput = document.getElementById('password');
        const confirmPasswordInput = document.getElementById('password_confirmation');
        const passwordStrength = document.getElementById('passwordStrength');
        const passwordStrengthFill = document.getElementById('passwordStrengthFill');
        const passwordStrengthText = document.getElementById('passwordStrengthText');
        const passwordMatchError = document.getElementById('passwordMatchError');

        // Account type change handler
        accountTypeSelect.addEventListener('change', function() {
            if (this.value === 'organization') {
                organizationNameGroup.style.display = 'block';
                document.getElementById('organization_name').setAttribute('required', 'required');
            } else {
                organizationNameGroup.style.display = 'none';
                document.getElementById('organization_name').removeAttribute('required');
            }
        });

        // Trigger on page load if there's old input
        if (accountTypeSelect.value === 'organization') {
            organizationNameGroup.style.display = 'block';
        }

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

        // Validation helpers
        const validateEmail = (email) => {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        };

        const showError = (input, message) => {
            input.classList.add('error');
            const group = input.closest('.form-group');
            let errorEl = group.querySelector('.form-error:not(#passwordMatchError)');

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
            const errorEl = group.querySelector('.form-error:not(#passwordMatchError)');
            if (errorEl) {
                errorEl.classList.remove('visible');
            }
        };

        // Real-time validation
        confirmPasswordInput.addEventListener('input', checkPasswordMatch);

        // Form submission
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            let isValid = true;

            // Validate account type
            if (!accountTypeSelect.value) {
                showError(accountTypeSelect, 'Please select an account type');
                isValid = false;
            }

            // Validate first name
            const firstName = document.getElementById('first_name');
            if (!firstName.value.trim()) {
                showError(firstName, 'First name is required');
                isValid = false;
            }

            // Validate last name
            const lastName = document.getElementById('last_name');
            if (!lastName.value.trim()) {
                showError(lastName, 'Last name is required');
                isValid = false;
            }

            // Validate email
            const email = document.getElementById('email');
            if (!email.value.trim()) {
                showError(email, 'Email is required');
                isValid = false;
            } else if (!validateEmail(email.value)) {
                showError(email, 'Please enter a valid email address');
                isValid = false;
            }

            // Validate password
            if (!passwordInput.value) {
                showError(passwordInput, 'Password is required');
                isValid = false;
            } else if (passwordInput.value.length < 8) {
                showError(passwordInput, 'Password must be at least 8 characters');
                isValid = false;
            }

            // Validate password match
            if (!checkPasswordMatch()) {
                isValid = false;
            }

            // Validate terms
            const terms = document.getElementById('terms');
            if (!terms.checked) {
                showError(terms, 'You must accept the terms and conditions');
                isValid = false;
            }

            if (!isValid) {
                return;
            }

            // Show loading state
            signupBtn.disabled = true;
            signupBtn.innerHTML = '<i class="fas fa-spinner spinner"></i><span>Creating account...</span>';

            // Submit form
            form.submit();
        });

        // Clear errors on input
        const inputs = form.querySelectorAll('input, select');
        inputs.forEach(input => {
            input.addEventListener('input', function() {
                clearError(this);
            });
            input.addEventListener('change', function() {
                clearError(this);
            });
        });
    });
</script>
@endpush
