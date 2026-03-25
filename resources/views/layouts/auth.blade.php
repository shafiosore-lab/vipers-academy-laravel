<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Authentication') - Gamesuite</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* ========================================
           CSS CUSTOM PROPERTIES
           ======================================== */
        :root {
            /* Brand Colors - Gaming Theme */
            --primary: #7c3aed;
            --primary-hover: #6d28d9;
            --primary-light: #8b5cf6;
            --primary-dark: #5b21b6;

            /* Neutral Colors */
            --white: #ffffff;
            --gray-50: #f8fafc;
            --gray-100: #f1f5f9;
            --gray-200: #e2e8f0;
            --gray-300: #cbd5e1;
            --gray-400: #94a3b8;
            --gray-500: #64748b;
            --gray-600: #475569;
            --gray-700: #334155;
            --gray-800: #1e293b;
            --gray-900: #0f172a;

            /* Semantic Colors */
            --success: #10b981;
            --success-light: #d1fae5;
            --danger: #ef4444;
            --danger-light: #fee2e2;
            --warning: #f59e0b;
            --warning-light: #fef3c7;

            /* Effects */
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);

            /* Border Radius */
            --radius-sm: 6px;
            --radius-md: 8px;
            --radius-lg: 12px;
            --radius-xl: 16px;

            /* Transitions */
            --transition-fast: 150ms ease;
            --transition-normal: 250ms ease;
            --transition-slow: 350ms ease;
        }

        /* ========================================
           RESET & BASE STYLES
           ======================================== */
        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html {
            font-size: 16px;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            font-size: 0.9375rem;
            line-height: 1.6;
            color: var(--gray-700);
            background: var(--gray-50);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ========================================
           AUTH CONTAINER
           ======================================== */
        .auth-container {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 1rem 0.5rem;
            position: relative;
            overflow: hidden;
        }

        /* Background decoration */
        .auth-container::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background:
                radial-gradient(circle at 20% 80%, rgba(124, 58, 237, 0.08) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(124, 58, 237, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(124, 58, 237, 0.05) 0%, transparent 30%);
            pointer-events: none;
        }

        /* ========================================
           AUTH CARD
           ======================================== */
        .auth-card {
            width: 100%;
            max-width: 900px;
            background: var(--white);
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-lg);
            display: flex;
            overflow: hidden;
            position: relative;
            z-index: 1;
            animation: slideUp 0.4s ease-out;
        }

        /* Left side - Form */
        .auth-form-section {
            flex: 1;
            padding: 1rem;
            min-width: 0;
        }

        /* Right side - Branding */
        .auth-branding-section {
            width: 280px;
            background: linear-gradient(135deg, #1e1b4b 0%, #312e81 50%, #4c1d95 100%);
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            color: var(--white);
            position: relative;
            overflow: hidden;
        }

        .auth-branding-section::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            pointer-events: none;
        }

        .auth-branding-section::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -30%;
            width: 60%;
            height: 60%;
            background: radial-gradient(circle, rgba(255,255,255,0.05) 0%, transparent 70%);
            pointer-events: none;
        }

        .branding-logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 2rem;
            position: relative;
            z-index: 1;
        }

        .branding-logo-icon {
            width: 48px;
            height: 48px;
            background: rgba(255,255,255,0.2);
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .branding-logo-text {
            font-size: 1.25rem;
            font-weight: 700;
        }

        .branding-content {
            position: relative;
            z-index: 1;
        }

        .branding-title {
            font-size: 1.125rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            line-height: 1.4;
        }

        .branding-description {
            font-size: 0.8125rem;
            opacity: 0.8;
            line-height: 1.5;
            margin-bottom: 1.5rem;
        }

        .branding-footer {
            margin-top: auto;
            padding-top: 1rem;
            border-top: 1px solid rgba(255,255,255,0.2);
            position: relative;
            z-index: 1;
        }

        .branding-footer-text {
            font-size: 0.75rem;
            opacity: 0.7;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ========================================
           AUTH HEADER
           ======================================== */
        .auth-header {
            text-align: center;
            margin-bottom: 0.75rem;
        }

        .auth-logo {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            border-radius: var(--radius-lg);
            margin-bottom: 0.25rem;
            box-shadow: 0 4px 12px rgba(124, 58, 237, 0.25);
        }

        .auth-logo i {
            font-size: 1.75rem;
            color: var(--white);
        }

        .auth-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--gray-900);
            margin-bottom: 0.375rem;
            line-height: 1.3;
        }

        .auth-subtitle {
            font-size: 0.8125rem;
            color: var(--gray-500);
            font-weight: 400;
        }

        /* ========================================
           FORM STYLES
           ======================================== */
        .auth-form {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .form-label {
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--gray-700);
        }

        .form-label.required::after {
            content: '*';
            color: var(--danger);
            margin-left: 0.25rem;
        }

        .form-input-wrapper {
            position: relative;
        }

        .form-input {
            width: 100%;
            padding: 0.75rem 1rem;
            font-size: 0.9375rem;
            font-family: inherit;
            color: var(--gray-800);
            background: var(--white);
            border: 1.5px solid var(--gray-200);
            border-radius: var(--radius-md);
            transition: all var(--transition-fast);
            outline: none;
        }

        .form-input:hover {
            border-color: var(--gray-300);
        }

        .form-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .form-input.error {
            border-color: var(--danger);
        }

        .form-input.error:focus {
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
        }

        .form-input::placeholder {
            color: var(--gray-400);
        }

        /* Input with icon */
        .form-input.has-icon {
            padding-left: 2.75rem;
        }

        .form-input-icon {
            position: absolute;
            left: 0.875rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray-400);
            font-size: 1rem;
            pointer-events: none;
            transition: color var(--transition-fast);
        }

        .form-input:focus ~ .form-input-icon,
        .form-input:focus ~ .form-input-icon {
            color: var(--primary);
        }

        /* Password toggle */
        .password-toggle {
            position: absolute;
            right: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--gray-400);
            cursor: pointer;
            padding: 0.25rem;
            font-size: 1rem;
            transition: color var(--transition-fast);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .password-toggle:hover {
            color: var(--gray-600);
        }

        .password-toggle:focus {
            outline: 2px solid var(--primary);
            outline-offset: 2px;
            border-radius: var(--radius-sm);
        }

        /* Error messages */
        .form-error {
            font-size: 0.8125rem;
            color: var(--danger);
            display: none;
            align-items: center;
            gap: 0.25rem;
        }

        .form-error.visible {
            display: flex;
        }

        .form-error i {
            font-size: 0.75rem;
        }

        /* ========================================
           SELECT DROPDOWN
           ======================================== */
        .form-select {
            width: 100%;
            padding: 0.75rem 1rem;
            font-size: 0.9375rem;
            font-family: inherit;
            color: var(--gray-800);
            background: var(--white);
            border: 1.5px solid var(--gray-200);
            border-radius: var(--radius-md);
            transition: all var(--transition-fast);
            outline: none;
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748b'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 1.25rem;
            padding-right: 2.5rem;
        }

        .form-select:hover {
            border-color: var(--gray-300);
        }

        .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        /* ========================================
           CHECKBOX
           ======================================== */
        .form-checkbox-group {
            display: flex;
            align-items: flex-start;
            gap: 0.625rem;
        }

        .form-checkbox {
            width: 1.125rem;
            height: 1.125rem;
            margin-top: 0.125rem;
            accent-color: var(--primary);
            cursor: pointer;
            flex-shrink: 0;
        }

        .form-checkbox-label {
            font-size: 0.8125rem;
            color: var(--gray-600);
            line-height: 1.5;
            cursor: pointer;
        }

        .form-checkbox-label a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
        }

        .form-checkbox-label a:hover {
            text-decoration: underline;
        }

        /* ========================================
           BUTTONS
           ======================================== */
        .btn-primary {
            width: 100%;
            padding: 0.8125rem 1.5rem;
            font-size: 0.9375rem;
            font-weight: 600;
            font-family: inherit;
            color: var(--white);
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            border: none;
            border-radius: var(--radius-md);
            cursor: pointer;
            transition: all var(--transition-normal);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            box-shadow: 0 2px 8px rgba(124, 58, 237, 0.25);
        }

        .btn-primary:hover:not(:disabled) {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(124, 58, 237, 0.35);
        }

        .btn-primary:active:not(:disabled) {
            transform: translateY(0);
        }

        .btn-primary:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }

        .btn-primary .spinner {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        /* ========================================
           DIVIDER
           ======================================== */
        .auth-divider {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin: 1rem 0;
        }

        .auth-divider::before,
        .auth-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--gray-200);
        }

        .auth-divider span {
            font-size: 0.8125rem;
            color: var(--gray-400);
            font-weight: 500;
        }

        /* ========================================
           SOCIAL LOGIN
           ======================================== */
        .social-buttons {
            display: flex;
            gap: 0.75rem;
        }

        .social-btn {
            flex: 1;
            padding: 0.625rem 1rem;
            font-size: 0.875rem;
            font-weight: 500;
            font-family: inherit;
            color: var(--gray-700);
            background: var(--white);
            border: 1.5px solid var(--gray-200);
            border-radius: var(--radius-md);
            cursor: pointer;
            transition: all var(--transition-fast);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            text-decoration: none;
        }

        .social-btn:hover {
            background: var(--gray-50);
            border-color: var(--gray-300);
            transform: translateY(-1px);
        }

        .social-btn:focus {
            outline: 2px solid var(--primary);
            outline-offset: 2px;
        }

        .social-btn i {
            font-size: 1.125rem;
        }

        .social-btn.google:hover {
            color: #ea4335;
            border-color: #ea4335;
            background: #fef8f7;
        }

        .social-btn.facebook:hover {
            color: #1877f2;
            border-color: #1877f2;
            background: #f0f7ff;
        }

        /* ========================================
           AUTH FOOTER
           ======================================== */
        .auth-footer {
            text-align: center;
            margin-top: 0.5rem;
            padding-top: 0.5rem;
            border-top: 1px solid var(--gray-100);
        }

        .auth-footer p {
            font-size: 0.875rem;
            color: var(--gray-500);
        }

        .auth-footer a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            transition: color var(--transition-fast);
        }

        .auth-footer a:hover {
            color: var(--primary-hover);
            text-decoration: underline;
        }

        /* ========================================
           ALERTS
           ======================================== */
        .alert {
            padding: 0.875rem 1rem;
            border-radius: var(--radius-md);
            font-size: 0.875rem;
            display: none;
            align-items: flex-start;
            gap: 0.625rem;
            margin-bottom: 1rem;
        }

        .alert.visible {
            display: flex;
        }

        .alert i {
            font-size: 1rem;
            margin-top: 0.125rem;
            flex-shrink: 0;
        }

        .alert-success {
            background: var(--success-light);
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .alert-success i {
            color: var(--success);
        }

        .alert-danger {
            background: var(--danger-light);
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .alert-danger i {
            color: var(--danger);
        }

        /* ========================================
           RESPONSIVE
           ======================================== */
        @media (max-width: 768px) {
            .auth-card {
                max-width: 100%;
                flex-direction: column;
            }

            .auth-branding-section {
                display: none !important;
            }

            .auth-form-section {
                padding: 0.75rem;
            }

            .auth-header {
                margin-bottom: 0.5rem;
            }
        }

        /* ========================================
           PASSWORD STRENGTH
           ======================================== */
        .password-strength {
            margin-top: 0.5rem;
            display: none;
        }

        .password-strength.visible {
            display: block;
        }

        .password-strength-bar {
            height: 4px;
            background: var(--gray-200);
            border-radius: 2px;
            overflow: hidden;
            margin-bottom: 0.375rem;
        }

        .password-strength-fill {
            height: 100%;
            width: 0;
            transition: all var(--transition-normal);
            border-radius: 2px;
        }

        .password-strength-fill.weak {
            width: 33%;
            background: var(--danger);
        }

        .password-strength-fill.medium {
            width: 66%;
            background: var(--warning);
        }

        .password-strength-fill.strong {
            width: 100%;
            background: var(--success);
        }

        .password-strength-text {
            font-size: 0.75rem;
            color: var(--gray-500);
        }

        /* ========================================
           UTILITY CLASSES
           ======================================== */
        .visually-hidden {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0;
        }

        .text-center {
            text-align: center;
        }

        .mb-0 {
            margin-bottom: 0;
        }

        .mb-1 {
            margin-bottom: 0.25rem;
        }

        .mb-2 {
            margin-bottom: 0.5rem;
        }

        .mb-3 {
            margin-bottom: 0.75rem;
        }

        .mb-4 {
            margin-bottom: 1rem;
        }

        .mb-5 {
            margin-bottom: 1.5rem;
        }
    </style>

    @stack('styles')
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-form-section">
                @yield('content')
            </div>
            <div class="auth-branding-section">
                @yield('branding')
            </div>
        </div>
    </div>

    <script>
        // Password toggle functionality
        document.addEventListener('DOMContentLoaded', function() {
            const toggleButtons = document.querySelectorAll('.password-toggle');

            toggleButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const input = this.previousElementSibling;
                    const icon = this.querySelector('i');

                    if (input.type === 'password') {
                        input.type = 'text';
                        icon.classList.remove('fa-eye');
                        icon.classList.add('fa-eye-slash');
                    } else {
                        input.type = 'password';
                        icon.classList.remove('fa-eye-slash');
                        icon.classList.add('fa-eye');
                    }
                });
            });

            // Auto-hide alerts after 5 seconds
            setTimeout(() => {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(alert => {
                    alert.classList.remove('visible');
                });
            }, 5000);
        });
    </script>

    @stack('scripts')
</body>
</html>
