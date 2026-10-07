<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Verify Email - Vipers Academy</title>
    <meta name="description" content="Verify your email address for Vipers Academy - Complete your registration">

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

        .verify-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            overflow: hidden;
            max-width: 500px;
            width: 100%;
            position: relative;
        }

        .verify-container::before {
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

        .email-verification-icon {
            text-align: center;
            margin-bottom: 2rem;
        }

        .email-verification-icon .fa-envelope {
            font-size: 4rem;
            color: #ff6b35;
            background: linear-gradient(135deg, rgba(255, 107, 53, 0.1), rgba(255, 140, 66, 0.1));
            border-radius: 50%;
            padding: 2rem;
            display: inline-block;
        }

        .verification-message {
            background: linear-gradient(135deg, #e3f2fd, #bbdefb);
            border: 1px solid #90caf9;
            color: #1565c0;
            padding: 1.5rem;
            border-radius: 12px;
            margin-bottom: 2rem;
            text-align: center;
            font-size: 0.95rem;
            line-height: 1.6;
        }

        .success-message {
            background: linear-gradient(135deg, #d4edda, #c3e6cb);
            border: 1px solid #c3e6cb;
            color: #155724;
            padding: 1rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            font-size: 0.95rem;
            text-align: center;
        }

        .btn-resend {
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
            margin-bottom: 1rem;
        }

        .btn-resend:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(255, 107, 53, 0.3);
        }

        .btn-resend::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .btn-resend:hover::before {
            left: 100%;
        }

        .btn-logout {
            width: 100%;
            padding: 0.75rem;
            background: transparent;
            border: 2px solid #6c757d;
            border-radius: 12px;
            color: #6c757d;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-logout:hover {
            background: #6c757d;
            color: white;
            transform: translateY(-1px);
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

        @keyframes rotate {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        @media (max-width: 640px) {
            .verify-container {
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

            .email-verification-icon .fa-envelope {
                font-size: 3rem;
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="verify-container">
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
                <h2>Verify Your Email</h2>
                <p>Thanks for signing up! Before getting started, please verify your email address.</p>
            </div>

            <!-- Email Verification Icon -->
            <div class="email-verification-icon">
                <div class="fa-envelope">
                    <i class="fas fa-envelope"></i>
                </div>
            </div>

            <!-- Verification Message -->
            <div class="verification-message">
                <strong>Check your email!</strong><br>
                We've sent a verification link to your email address. Click the link in the email to activate your account and start your football journey.
            </div>

            <!-- Success Message -->
            @if (session('status') == 'verification-link-sent')
                <div class="success-message">
                    <i class="fas fa-check-circle me-2"></i>
                    A new verification link has been sent to your email address.
                </div>
            @endif

            <!-- Action Buttons -->
            <form method="POST" action="{{ route('verification.send') }}" style="margin-bottom: 1rem;">
                @csrf
                <button type="submit" class="btn-resend">
                    <i class="fas fa-envelope me-2"></i>Resend Verification Email
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-logout">
                    <i class="fas fa-sign-out-alt me-2"></i>Log Out
                </button>
            </form>

            <!-- Back to Login -->
            <div class="back-to-login">
                <p>Already verified?</p>
                <a href="{{ route('login') }}">Back to Sign In</a>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
