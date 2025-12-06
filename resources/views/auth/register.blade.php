<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Register - Vipers Academy</title>

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

        .register-container {
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
            background: url('https://images.unsplash.com/photo-1579952363873-27f3bade9f55?auto=format&fit=crop&w=1920&q=80') center/cover;
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
            overflow-y: auto;
        }

        .form-wrapper {
            width: 100%;
            max-width: 450px;
        }

        .form-header {
            margin-bottom: 1.5rem;
            text-align: center;
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

        /* Role Selection Cards */
        .role-selection {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .role-card {
            border: 2px solid #dee2e6;
            border-radius: 8px;
            padding: 1.25rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: white;
        }

        .role-card:hover {
            border-color: #28a745;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.2);
        }

        .role-card.active {
            border-color: #28a745;
            background: linear-gradient(135deg, rgba(40, 167, 69, 0.05), rgba(32, 201, 151, 0.05));
        }

        .role-card input[type="radio"] {
            display: none;
        }

        .role-icon {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
            color: #28a745;
        }

        .role-card.active .role-icon {
            animation: bounce 0.5s ease;
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        .role-title {
            font-size: 1rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 0.25rem;
        }

        .role-desc {
            font-size: 0.8rem;
            color: #6c757d;
            margin: 0;
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

        .checkbox-group {
            margin-bottom: 1rem;
        }

        .checkbox-group label {
            display: flex;
            align-items: flex-start;
            font-size: 0.85rem;
            color: #6c757d;
            cursor: pointer;
            font-weight: 400;
        }

        .checkbox-group input[type="checkbox"] {
            margin-right: 0.5rem;
            margin-top: 0.2rem;
            accent-color: #28a745;
            flex-shrink: 0;
        }

        .checkbox-group a {
            color: #28a745;
            text-decoration: none;
            font-weight: 500;
        }

        .checkbox-group a:hover {
            text-decoration: underline;
        }

        .btn-register {
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

        .btn-register:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(40, 167, 69, 0.4);
        }

        .btn-register:disabled {
            opacity: 0.6;
            cursor: not-allowed;
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

        .login-link {
            text-align: center;
            padding-top: 1rem;
            border-top: 1px solid #dee2e6;
            font-size: 0.9rem;
        }

        .login-link span {
            color: #6c757d;
        }

        .login-link a {
            color: #28a745;
            text-decoration: none;
            font-weight: 600;
            margin-left: 0.25rem;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .register-container {
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

            .role-selection {
                grid-template-columns: 1fr;
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

            .role-selection {
                margin-bottom: 1rem;
            }

            .role-card {
                padding: 1rem;
            }

            .role-icon {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <!-- Left Side: Brand -->
        <div class="image-section">
            <div class="logo-content">
                <i class="fas fa-users"></i>
                <h1>Join Vipers Academy</h1>
                <p>Build Your Football Future</p>
            </div>
        </div>

        <!-- Right Side: Registration Form -->
        <div class="form-section">
            <div class="form-wrapper">
                <div class="form-header">
                    <h2>Create Your Account</h2>
                    <p>Choose your path and get started</p>
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

                <form method="POST" action="{{ route('register') }}" id="registerForm">
                    @csrf

                    <!-- Role Selection Cards -->
                    <div class="role-selection">
                        <label class="role-card active" for="role_player">
                            <input type="radio" name="role" id="role_player" value="player" checked>
                            <div class="role-icon">
                                <i class="fas fa-running"></i>
                            </div>
                            <div class="role-title">Join as Player</div>
                            <p class="role-desc">Access training programs</p>
                        </label>

                        <label class="role-card" for="role_partner">
                            <input type="radio" name="role" id="role_partner" value="partner">
                            <div class="role-icon">
                                <i class="fas fa-handshake"></i>
                            </div>
                            <div class="role-title">Join as Partner</div>
                            <p class="role-desc">Collaborate with us</p>
                        </label>
                    </div>

                    <!-- Name -->
                    <div class="form-group">
                        <input id="name" type="text" name="name" value="{{ old('name') }}"
                               placeholder="Full Name" required autofocus autocomplete="name"
                               class="form-control @error('name') is-invalid @enderror">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="form-group">
                        <input id="email" type="email" name="email" value="{{ old('email') }}"
                               placeholder="Email Address" required autocomplete="username"
                               class="form-control @error('email') is-invalid @enderror">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="form-group">
                        <input id="password" type="password" name="password"
                               placeholder="Password (min. 8 characters)" required autocomplete="new-password"
                               class="form-control @error('password') is-invalid @enderror">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="form-group">
                        <input id="password_confirmation" type="password" name="password_confirmation"
                               placeholder="Confirm Password" required autocomplete="new-password"
                               class="form-control">
                    </div>

                    <!-- Terms -->
                    <div class="checkbox-group">
                        <label for="terms">
                            <input id="terms" type="checkbox" name="terms" required>
                            <span>I agree to the <a href="#" target="_blank">Terms & Conditions</a> and <a href="#" target="_blank">Privacy Policy</a></span>
                        </label>
                    </div>

                    <!-- Register Button -->
                    <button type="submit" class="btn-register" id="submitBtn">
                        <i class="fas fa-user-plus"></i> Create Account
                    </button>

                    <!-- Divider -->
                    <div class="divider">
                        <span>or register with</span>
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

                    <!-- Login Link -->
                    <div class="login-link">
                        <span>Already have an account?</span>
                        <a href="{{ route('login') }}">Sign In</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Role card selection
            const roleCards = document.querySelectorAll('.role-card');
            const roleInputs = document.querySelectorAll('input[name="role"]');

            roleCards.forEach((card, index) => {
                card.addEventListener('click', function() {
                    // Remove active class from all cards
                    roleCards.forEach(c => c.classList.remove('active'));
                    // Add active class to clicked card
                    this.classList.add('active');
                    // Check the corresponding radio button
                    roleInputs[index].checked = true;
                });
            });

            // Form submission handling
            const form = document.getElementById('registerForm');
            const submitBtn = document.getElementById('submitBtn');

            form.addEventListener('submit', function(e) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creating Account...';
            });
        });
    </script>
</body>
</html>
