<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Vipers Academy</title>
    <meta name="description" content="Login to Vipers Academy - Access your football training account">

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

        .login-container {
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

        .login-container::before {
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

        .checkbox-group {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
        }

        .checkbox-group label {
            display: flex;
            align-items: center;
            font-size: 0.9rem;
            color: #666;
            cursor: pointer;
        }

        .checkbox-group input[type="checkbox"] {
            margin-right: 0.5rem;
            accent-color: #ff6b35;
        }

        .forgot-password {
            color: #ff6b35;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .forgot-password:hover {
            color: #e55a2b;
            text-decoration: underline;
        }

        .btn-login {
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

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(255, 107, 53, 0.3);
        }

        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .btn-login:hover::before {
            left: 100%;
        }

        .social-login {
            text-align: center;
            margin-bottom: 2rem;
        }

        .social-login p {
            color: #666;
            margin-bottom: 1rem;
            position: relative;
            font-size: 0.9rem;
        }

        .social-login p::before,
        .social-login p::after {
            content: '';
            position: absolute;
            top: 50%;
            width: 30%;
            height: 1px;
            background: #e1e5e9;
        }

        .social-login p::before {
            left: 0;
        }

        .social-login p::after {
            right: 0;
        }

        .social-btn {
            display: inline-block;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin: 0 0.5rem;
            text-decoration: none;
            transition: all 0.3s ease;
            border: 2px solid #e1e5e9;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #666;
        }

        .social-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .social-btn.google:hover {
            border-color: #db4437;
            color: #db4437;
        }

        .social-btn.facebook:hover {
            border-color: #4267B2;
            color: #4267B2;
        }

        .social-btn.twitter:hover {
            border-color: #1DA1F2;
            color: #1DA1F2;
        }

        .register-link {
            text-align: center;
            padding-top: 1.5rem;
            border-top: 1px solid #e1e5e9;
        }

        .register-link p {
            color: #666;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }

        .register-link a {
            color: #ff6b35;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .register-link a:hover {
            color: #e55a2b;
            text-decoration: underline;
        }

        .error-message {
            background: #fee;
            border: 1px solid #fcc;
            color: #c33;
            padding: 0.75rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            font-size: 0.9rem;
        }

        .success-message {
            background: #efe;
            border: 1px solid #cfc;
            color: #363;
            padding: 0.75rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            font-size: 0.9rem;
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
            .login-container {
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
    <div class="login-container">
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
                <h2>Welcome Back</h2>
                <p>Sign in to your Vipers Academy account</p>
            </div>

            <!-- Session Status -->
            @if (session('status'))
                <div class="success-message">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" novalidate>
                @csrf

                <!-- Email Address -->
                <div class="input-group">
                    <i class="fas fa-envelope"></i>
                    <input id="email" type="email" name="email" value="{{ old('email') }}"
                           placeholder="Enter your email address" required autofocus autocomplete="username"
                           class="form-control @error('email') is-invalid @enderror">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input id="password" type="password" name="password"
                           placeholder="Enter your password" required autocomplete="current-password"
                           class="form-control @error('password') is-invalid @enderror">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="checkbox-group">
                    <label for="remember_me">
                        <input id="remember_me" type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        Remember me
                    </label>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="forgot-password">
                            Forgot Password?
                        </a>
                    @endif
                </div>

                <!-- Login Button -->
                <button type="submit" class="btn-login">
                    <i class="fas fa-sign-in-alt me-2"></i>Sign In
                </button>

                <!-- Social Login -->
                <div class="social-login">
                    <p>Or continue with</p>
                    <a href="#" class="social-btn google" title="Continue with Google">
                        <i class="fab fa-google"></i>
                    </a>
                    <a href="#" class="social-btn facebook" title="Continue with Facebook">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="social-btn twitter" title="Continue with Twitter">
                        <i class="fab fa-twitter"></i>
                    </a>
                </div>

                <!-- Register Link -->
                <div class="register-link">
                    <p>Don't have an account?</p>
                    <a href="{{ route('register') }}">Join Vipers Academy Today</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
