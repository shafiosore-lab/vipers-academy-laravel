<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Reset Password - Vipers Academy</title>
    <meta name="description" content="Set your new Vipers Academy password - Complete your password reset">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Figtree', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 20px;
        }

        .reset-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            overflow: hidden;
            max-width: 450px;
            width: 100%;
            position: relative;
        }

        .reset-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #ff6b35, #ff8c42, #ffb347);
        }

        .logo-section {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            padding: 2rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .logo-section::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 107, 53, 0.1) 0%, transparent 70%);
            animation: rotate 20s linear infinite;
        }

        .club-logo {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #ff6b35, #ff8c42);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 25px rgba(255, 107, 53, 0.4);
            position: relative;
            margin: 0 auto 1rem;
            animation: pulse 2s ease-in-out infinite;
        }

        .club-logo i {
            font-size: 2.5rem;
            color: white;
        }

        .logo-ring {
            position: absolute;
            top: -3px;
            left: -3px;
            right: -3px;
            bottom: -3px;
            border: 2px solid rgba(255, 107, 53, 0.3);
            border-radius: 50%;
            animation: rotate 6s linear infinite;
        }

        .club-name {
            font-size: 1.8rem;
            font-weight: bold;
            background: linear-gradient(45deg, #fff, #ff6b35);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.5rem;
        }

        .club-tagline {
            color: #ff6b35;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .form-section {
            padding: 2.5rem;
        }

        .form-title {
            text-align: center;
            margin-bottom: 2rem;
            color: #1a1a2e;
        }

        .form-title h2 {
            font-size: 2rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            background: linear-gradient(135deg, #1a1a2e, #ff6b35);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .form-title p {
            color: #666;
            font-size: 0.95rem;
            line-height: 1.6;
        }

        .input-group {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .input-group input {
            width: 100%;
            padding: 1rem 1rem 1rem 3rem;
            border: 2px solid #e1e5e9;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.9);
            box-sizing: border-box;
        }

        .input-group input:focus {
            border-color: #ff6b35;
            box-shadow: 0 0 0 3px rgba(255, 107, 53, 0.1);
            outline: none;
        }

        .input-group i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #ff6b35;
            font-size: 1.1rem;
            z-index: 1;
        }

        .input-group input::placeholder {
            color: #999;
        }

        .password-strength {
            margin-bottom: 1rem;
        }

        .password-strength .progress {
            height: 6px;
            border-radius: 3px;
            background: #e1e5e9;
            margin-bottom: 0.5rem;
        }

        .password-strength .progress-bar {
            border-radius: 3px;
            transition: width 0.3s ease;
        }

        .password-strength .strength-text {
            font-size: 0.8rem;
            color: #666;
        }

        .btn-reset {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, #ff6b35, #ff8c42);
            border: none;
            border-radius: 12px;
            color: white;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            margin-bottom: 1.5rem;
        }

        .btn-reset:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(255, 107, 53, 0.3);
        }

        .btn-reset::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .btn-reset:hover::before {
            left: 100%;
        }

        .back-to-login {
            text-align: center;
            padding-top: 1.5rem;
            border-top: 1px solid #e1e5e9;
        }

        .back-to-login p {
            color: #666;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }

        .back-to-login a {
            color: #ff6b35;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .back-to-login a:hover {
            color: #e55a2b;
            text-decoration: underline;
        }

        .error-message {
            background: linear-gradient(135deg, #f8d7da, #f5c6cb);
            border: 1px solid #f5c6cb;
            color: #721c24;
            padding: 1rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            font-size: 0.95rem;
        }

        @keyframes rotate {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        @media (max-width: 640px) {
            .reset-container {
                margin: 1rem;
                max-width: none;
            }

            .logo-section {
                padding: 1.5rem;
            }

            .form-section {
                padding: 2rem 1.5rem;
            }

            .club-logo {
                width: 60px;
                height: 60px;
            }

            .club-logo i {
                font-size: 2rem;
            }

            .club-name {
                font-size: 1.5rem;
            }

            .form-title h2 {
                font-size: 1.8rem;
            }
        }
    </style>
</head>
<body>
    <div class="reset-container">
        <!-- Logo Section -->
        <div class="logo-section">
            <a href="{{ route('home') }}" class="text-decoration-none">
                <div class="club-logo">
                    <i class="fas fa-futbol"></i>
                    <div class="logo-ring"></div>
                </div>
                <div class="club-name">Vipers Academy</div>
                <div class="club-tagline">Excellence in Football</div>
            </a>
        </div>

        <!-- Form Section -->
        <div class="form-section">
            <div class="form-title">
                <h2>Set New Password</h2>
                <p>Enter your new password below to complete the reset process.</p>
            </div>

            <form method="POST" action="{{ route('password.store') }}" novalidate>
                @csrf

                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Email Address -->
                <div class="input-group">
                    <i class="fas fa-envelope"></i>
                    <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}"
                           placeholder="Enter your email address" required autofocus autocomplete="username">
                    @error('email')
                        <div style="color: #e74c3c; font-size: 0.875rem; margin-top: 0.5rem; margin-left: 3rem;">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input id="password" type="password" name="password"
                           placeholder="Enter new password" required autocomplete="new-password">
                    @error('password')
                        <div style="color: #e74c3c; font-size: 0.875rem; margin-top: 0.5rem; margin-left: 3rem;">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input id="password_confirmation" type="password" name="password_confirmation"
                           placeholder="Confirm new password" required autocomplete="new-password">
                    @error('password_confirmation')
                        <div style="color: #e74c3c; font-size: 0.875rem; margin-top: 0.5rem; margin-left: 3rem;">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Reset Password Button -->
                <button type="submit" class="btn-reset">
                    <i class="fas fa-key me-2"></i>Reset Password
                </button>

                <!-- Back to Login -->
                <div class="back-to-login">
                    <p>Remember your password?</p>
                    <a href="{{ route('login') }}">Back to Sign In</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Password strength indicator
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            const strength = calculatePasswordStrength(password);
            updatePasswordStrengthIndicator(strength);
        });

        function calculatePasswordStrength(password) {
            let strength = 0;
            if (password.length >= 8) strength += 25;
            if (/[a-z]/.test(password)) strength += 25;
            if (/[A-Z]/.test(password)) strength += 25;
            if (/[0-9]/.test(password)) strength += 15;
            if (/[^A-Za-z0-9]/.test(password)) strength += 10;
            return Math.min(strength, 100);
        }

        function updatePasswordStrengthIndicator(strength) {
            const progressBar = document.querySelector('.progress-bar');
            const strengthText = document.querySelector('.strength-text');

            if (progressBar && strengthText) {
                progressBar.style.width = strength + '%';

                if (strength < 25) {
                    progressBar.className = 'progress-bar bg-danger';
                    strengthText.textContent = 'Very Weak';
                    strengthText.style.color = '#dc3545';
                } else if (strength < 50) {
                    progressBar.className = 'progress-bar bg-warning';
                    strengthText.textContent = 'Weak';
                    strengthText.style.color = '#ffc107';
                } else if (strength < 75) {
                    progressBar.className = 'progress-bar bg-info';
                    strengthText.textContent = 'Good';
                    strengthText.style.color = '#17a2b8';
                } else {
                    progressBar.className = 'progress-bar bg-success';
                    strengthText.textContent = 'Strong';
                    strengthText.style.color = '#28a745';
                }
            }
        }
    </script>
</body>
</html>
