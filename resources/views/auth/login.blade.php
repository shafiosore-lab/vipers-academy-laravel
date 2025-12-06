<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Vipers Academy</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

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
            background: #1a1a1a;
        }

        .login-container {
            height: 100vh;
            width: 100vw;
            display: grid;
            grid-template-columns: 1fr 1fr;
        }

        /* Left Side - Image */
        .image-section {
            position: relative;
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .image-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('https://images.unsplash.com/photo-1574629810360-7efbbe195018?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80') center/cover;
            opacity: 0.3;
        }

        .logo-content {
            position: relative;
            z-index: 2;
            text-align: center;
            color: white;
            padding: 2rem;
        }

        .logo-content i {
            font-size: 80px;
            margin-bottom: 1rem;
            text-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }

        .logo-content h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .logo-content p {
            font-size: 1.2rem;
            opacity: 0.95;
        }

        /* Right Side - Form */
        .form-section {
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .form-wrapper {
            width: 100%;
            max-width: 400px;
        }

        .form-header {
            margin-bottom: 1.5rem;
        }

        .form-header h2 {
            color: #28a745;
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }

        .form-header p {
            color: #6c757d;
            font-size: 0.95rem;
        }

        .alert {
            padding: 0.75rem;
            border-radius: 6px;
            margin-bottom: 1rem;
            font-size: 0.9rem;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.4rem;
            font-weight: 600;
            color: #333;
            font-size: 0.9rem;
        }

        .form-control {
            width: 100%;
            padding: 0.65rem 0.75rem;
            border: 2px solid #dee2e6;
            border-radius: 6px;
            font-size: 0.95rem;
            transition: all 0.2s;
        }

        .form-control:focus {
            border-color: #28a745;
            box-shadow: 0 0 0 3px rgba(40, 167, 69, 0.1);
            outline: none;
        }

        .form-text {
            display: none;
        }

        .checkbox-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            font-size: 0.85rem;
        }

        .checkbox-row label {
            display: flex;
            align-items: center;
            color: #6c757d;
            cursor: pointer;
            font-weight: 400;
        }

        .checkbox-row input[type="checkbox"] {
            margin-right: 0.4rem;
            accent-color: #28a745;
        }

        .forgot-password {
            color: #28a745;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s;
        }

        .forgot-password:hover {
            color: #218838;
        }

        .btn-login {
            width: 100%;
            padding: 0.7rem;
            background: linear-gradient(135deg, #28a745, #20c997);
            border: none;
            border-radius: 6px;
            color: white;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
            margin-bottom: 1rem;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(40, 167, 69, 0.4);
        }

        .divider {
            text-align: center;
            position: relative;
            margin: 1rem 0;
        }

        .divider span {
            background: white;
            padding: 0 0.75rem;
            color: #6c757d;
            font-size: 0.85rem;
            position: relative;
            z-index: 1;
        }

        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #dee2e6;
        }

        .social-buttons {
            display: flex;
            gap: 0.75rem;
            margin-bottom: 1rem;
        }

        .social-btn {
            flex: 1;
            padding: 0.6rem;
            border: 2px solid #dee2e6;
            background: white;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            color: #6c757d;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .social-btn:hover {
            border-color: #28a745;
            color: #28a745;
            transform: translateY(-2px);
        }

        .register-link {
            text-align: center;
            padding-top: 1rem;
            border-top: 1px solid #dee2e6;
            font-size: 0.9rem;
        }

        .register-link span {
            color: #6c757d;
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

        /* Responsive */
        @media (max-width: 768px) {
            .login-container {
                grid-template-columns: 1fr;
                grid-template-rows: 35vh 65vh;
            }

            .logo-content h1 {
                font-size: 2rem;
            }

            .logo-content i {
                font-size: 60px;
            }

            .form-section {
                padding: 1.5rem;
            }
        }

        @media (max-height: 700px) {
            .form-group {
                margin-bottom: 0.75rem;
            }

            .form-header {
                margin-bottom: 1rem;
            }

            .logo-content i {
                font-size: 60px;
            }

            .logo-content h1 {
                font-size: 2rem;
            }

            .btn-login {
                padding: 0.6rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Left Side: Brand -->
        <div class="image-section">
            <div class="logo-content">
                <i class="fas fa-shield-alt"></i>
                <h1>Vipers Academy</h1>
                <p>Excellence in Football</p>
            </div>
        </div>

        <!-- Right Side: Login Form -->
        <div class="form-section">
            <div class="form-wrapper">
                <div class="form-header">
                    <h2>Welcome Back</h2>
                    <p>Sign in to your account</p>
                </div>

                <!-- Session Status -->
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif

                <!-- Validation Errors -->
                @if ($errors->any())
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('login.post') }}">
                    @csrf

                    <!-- Email -->
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}"
                               placeholder="your@email.com" required autofocus autocomplete="username"
                               class="form-control @error('email') is-invalid @enderror">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input id="password" type="password" name="password"
                               placeholder="Enter your password" required autocomplete="current-password"
                               class="form-control @error('password') is-invalid @enderror">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Remember & Forgot -->
                    <div class="checkbox-row">
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
                        <i class="fas fa-sign-in-alt"></i> Sign In
                    </button>

                    <!-- Divider -->
                    <div class="divider">
                        <span>or continue with</span>
                    </div>

                    <!-- Social Login -->
                    <div class="social-buttons">
                        <a href="#" class="social-btn">
                            <i class="fab fa-google"></i> Google
                        </a>
                        <a href="#" class="social-btn">
                            <i class="fab fa-facebook-f"></i> Facebook
                        </a>
                    </div>

                    <!-- Register Link -->
                    <div class="register-link">
                        <span>Don't have an account?</span>
                        <a href="{{ route('register') }}">Sign Up</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
