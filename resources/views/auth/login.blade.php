<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="csrf_token_here">
    <title>Login - Vipers Academy</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Figtree', sans-serif;
            height: 100vh;
            width: 100vw;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            background: #ffffff;
        }

        /* Header */
        .header {
            background: white;
            padding: 0.75rem 2rem;
            border-bottom: 1px solid #e5e7eb;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .header h1 {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1f2937;
        }

        /* Main Container */
        .login-container {
            display: flex;
            flex: 1;
            width: 100vw;
            overflow: hidden;
        }

        /* Left Section - Image (50% width) */
        .left-section {
            width: 50vw;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem 1.5rem 1.5rem 2rem;
        }

        .image-wrapper {
            width: 100%;
            height: 100%;
            position: relative;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
        }

        .player-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
        }

        .image-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg,
                rgba(40, 167, 69, 0.25) 0%,
                rgba(32, 201, 151, 0.15) 50%,
                rgba(40, 167, 69, 0.25) 100%);
        }

        /* Right Section - Content (50% width) */
        .right-section {
            width: 50vw;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem 2rem 1.5rem 1.5rem;
        }

        .form-wrapper {
            width: 100%;
            max-width: 420px;
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.08);
            padding: 1.5rem;
        }

        .form-header {
            margin-bottom: 1rem;
            text-align: center;
        }

        .form-header h2 {
            color: #1f2937;
            font-size: 1.35rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }

        .form-header p {
            color: #6b7280;
            font-size: 0.8rem;
        }

        .alert {
            padding: 0.6rem;
            border-radius: 8px;
            margin-bottom: 0.75rem;
            font-size: 0.775rem;
        }

        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .alert-danger {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .form-group {
            margin-bottom: 0.75rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.3rem;
            font-weight: 600;
            color: #374151;
            font-size: 0.775rem;
        }

        .form-control {
            width: 100%;
            padding: 0.6rem 0.8rem;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 0.85rem;
            transition: all 0.2s;
            background: #f9fafb;
        }

        .form-control:focus {
            border-color: #28a745;
            box-shadow: 0 0 0 3px rgba(40, 167, 69, 0.1);
            outline: none;
            background: white;
        }

        .form-control.is-invalid {
            border-color: #dc3545;
        }

        .invalid-feedback {
            display: block;
            color: #dc3545;
            font-size: 0.725rem;
            margin-top: 0.2rem;
        }

        .checkbox-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.9rem;
            font-size: 0.75rem;
        }

        .checkbox-row label {
            display: flex;
            align-items: center;
            color: #6b7280;
            cursor: pointer;
            font-weight: 500;
        }

        .checkbox-row input[type="checkbox"] {
            margin-right: 0.35rem;
            accent-color: #28a745;
            width: 13px;
            height: 13px;
        }

        .forgot-password {
            color: #28a745;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s;
        }

        .forgot-password:hover {
            color: #218838;
            text-decoration: underline;
        }

        .btn-login {
            width: 100%;
            padding: 0.65rem;
            background: linear-gradient(135deg, #28a745, #20c997);
            border: none;
            border-radius: 8px;
            color: white;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            margin-bottom: 0.85rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.4rem;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(40, 167, 69, 0.3);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .btn-login:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }

        .divider {
            text-align: center;
            position: relative;
            margin: 0.85rem 0;
        }

        .divider span {
            background: white;
            padding: 0 0.6rem;
            color: #9ca3af;
            font-size: 0.75rem;
            position: relative;
            z-index: 1;
            font-weight: 500;
        }

        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #e5e7eb;
        }

        .social-buttons {
            display: flex;
            gap: 0.6rem;
            margin-bottom: 0.85rem;
        }

        .social-btn {
            flex: 1;
            padding: 0.5rem;
            border: 2px solid #e5e7eb;
            background: white;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.35rem;
            color: #6b7280;
            text-decoration: none;
            font-size: 0.775rem;
            font-weight: 500;
        }

        .social-btn:hover {
            border-color: #28a745;
            color: #28a745;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .register-link {
            text-align: center;
            padding-top: 0.85rem;
            border-top: 1px solid #e5e7eb;
            font-size: 0.775rem;
        }

        .register-link span {
            color: #6b7280;
        }

        .register-link a {
            color: #28a745;
            text-decoration: none;
            font-weight: 600;
            margin-left: 0.25rem;
        }

        .register-link a:hover {
            text-decoration: underline;
        }

        /* Footer */
        .footer {
            background: white;
            padding: 0.65rem 2rem;
            border-top: 1px solid #e5e7eb;
            text-align: center;
        }

        .footer p {
            font-size: 0.8rem;
            color: #6b7280;
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .login-container {
                flex-direction: column;
                overflow-y: auto;
            }

            .left-section,
            .right-section {
                width: 100%;
            }

            .left-section {
                padding: 1.5rem;
                min-height: 300px;
            }

            .right-section {
                padding: 1.5rem;
            }

            .form-wrapper {
                max-width: 100%;
            }
        }

        @media (max-width: 768px) {
            .header {
                padding: 0.65rem 1.5rem;
            }

            .header h1 {
                font-size: 1.15rem;
            }

            .left-section {
                padding: 1.25rem;
                min-height: 250px;
            }

            .right-section {
                padding: 1.25rem;
            }

            .form-wrapper {
                padding: 1.25rem;
            }

            .form-header h2 {
                font-size: 1.25rem;
            }

            .social-buttons {
                flex-direction: column;
            }
        }

        @media (max-width: 576px) {
            .header {
                padding: 0.6rem 1rem;
            }

            .header h1 {
                font-size: 1.05rem;
            }

            .footer {
                padding: 0.6rem 1rem;
            }

            .footer p {
                font-size: 0.75rem;
            }

            .left-section {
                padding: 1rem;
                min-height: 200px;
            }

            .right-section {
                padding: 1rem;
            }

            .form-wrapper {
                padding: 1.25rem;
                border-radius: 14px;
            }

            .form-header h2 {
                font-size: 1.2rem;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>Vipers Academy</h1>
    </div>

    <!-- Main Content -->
    <div class="login-container">
        <!-- Left Section: Image (50% width) -->
        <div class="left-section">
            <div class="image-wrapper">
                <img src="{{ asset('assets/img/gallery/sen.jpeg') }}"
                     alt="Football Training"
                     class="player-image">
                <div class=""></div>

            </div>
        </div>

        <!-- Right Section: Login Form (50% width) -->
        <div class="right-section">
            <div class="form-wrapper">
                <div class="form-header">
                    <h2>Welcome Back</h2>
                    <p>Sign in to your account</p>
                </div>

                <!-- Session Status -->
                <div class="alert alert-success" style="display: none;" id="statusAlert"></div>

                <!-- Validation Errors -->
                <div class="alert alert-danger" style="display: none;" id="errorAlert"></div>

                <form method="POST" action="/login" id="loginForm">
                    @csrf

                    <!-- Email -->
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input id="email" type="email" name="email"
                               placeholder="your@email.com" required autofocus autocomplete="username"
                               class="form-control">
                    </div>

                    <!-- Password -->
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input id="password" type="password" name="password"
                               placeholder="Enter your password" required autocomplete="current-password"
                               class="form-control">
                    </div>

                    <!-- Remember & Forgot -->
                    <div class="checkbox-row">
                        <label for="remember_me">
                            <input id="remember_me" type="checkbox" name="remember">
                            Remember me
                        </label>

                        <a href="/password/reset" class="forgot-password">
                            Forgot Password?
                        </a>
                    </div>

                    <!-- Login Button -->
                    <button type="submit" class="btn-login" id="loginBtn">
                        <i class="fas fa-sign-in-alt"></i> Sign In
                    </button>

                    <!-- Divider -->
                    <div class="divider">
                        <span>or continue with</span>
                    </div>

                    <!-- Social Login -->
                    <div class="social-buttons">
                        <a href="{{ route('auth.google') }}" class="social-btn">
                            <i class="fab fa-google"></i> Google
                        </a>
                        <a href="{{ route('auth.facebook') }}" class="social-btn">
                            <i class="fab fa-facebook-f"></i> Facebook
                        </a>
                    </div>

                    <!-- Register Link -->
                    <div class="register-link">
                        <span>Don't have an account?</span>
                        <a href="/register">Sign Up</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>© Vipers 2025</p>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('loginForm');
        const loginBtn = document.getElementById('loginBtn');

        form.addEventListener('submit', function(e) {
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            if (!email || !password) {
                alert('Please fill in all fields');
                e.preventDefault();
                return;
            }

            // Show loading state
            loginBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Signing In...';
            loginBtn.disabled = true;
        });
    });
    </script>
</body>
</html>
