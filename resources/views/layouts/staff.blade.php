<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', __('Staff Dashboard - GameSuite'))</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        /* ========================================
           CSS VARIABLES (Enhanced for Responsive)
           ======================================== */
        :root {
            /* Primary Colors */
            --primary:       #ea1c4d;
            --primary-dark:  #c0173f;
            --secondary:     #65c16e;
            --accent:        #fbc761;
            --danger:        #dc2626;
            --success:       #10b981;
            --warning:       #f59e0b;
            --info:          #0891b2;
            --dark:          #1a1a1a;

            /* Neutrals */
            --gray-900:      #333;
            --gray-800:      #4a4a4a;
            --gray-700:      #5c5c5c;
            --gray-600:      #666;
            --gray-500:      #888;
            --gray-400:      #aaa;
            --gray-300:      #e8e8e8;
            --gray-200:      #eee;
            --gray-100:      #f5f5f5;
            --white:         #fff;

            /* Shadows */
            --shadow-sm:     0 1px 2px rgba(0,0,0,0.05);
            --shadow:        0 1px 3px rgba(0,0,0,0.1);
            --shadow-md:     0 4px 6px rgba(0,0,0,0.1);
            --shadow-lg:     0 10px 15px rgba(0,0,0,0.1);
            --shadow-xl:     0 20px 25px rgba(0,0,0,0.15);

            /* Spacing */
            --spacing-xs: 0.25rem;
            --spacing-sm: 0.5rem;
            --spacing-md: 1rem;
            --spacing-lg: 1.5rem;
            --spacing-xl: 2rem;
            --spacing-2xl: 3rem;

            /* Breakpoints */
            --breakpoint-xs: 0;
            --breakpoint-sm: 576px;
            --breakpoint-md: 768px;
            --breakpoint-lg: 992px;
            --breakpoint-xl: 1200px;
            --breakpoint-xxl: 1400px;

            /* Layout */
            --header-height:  60px;
            --sidebar-width:  260px;

            /* Border Radius */
            --radius-sm: 4px;
            --radius: 6px;
            --radius-md: 8px;
            --radius-lg: 12px;
            --radius-full: 9999px;

            /* Transitions */
            --transition: 0.2s ease;
            --transition-fast: 0.15s ease;
            --transition-slow: 0.3s ease;

            /* Z-Index */
            --z-dropdown: 1000;
            --z-sticky: 1020;
            --z-fixed: 1030;
            --z-modal-backdrop: 1040;
            --z-modal: 1050;
        }

        /* ========================================
           GLOBAL STYLES
           ======================================== */
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: var(--font-family);
            font-size: var(--font-size-base);
            background: var(--white);
            color: var(--gray-900);
            line-height: 1.6;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* ========================================
           TYPOGRAPHY - FLUID
           ======================================== */
        h1, h2, h3, h4, h5, h6 {
            font-weight: 700;
            line-height: 1.2;
            color: var(--gray-900);
            margin-bottom: var(--spacing-md);
        }

        h1 { font-size: clamp(1.5rem, 4vw, var(--font-size-3xl)); color: var(--primary); }
        h2 { font-size: clamp(1.25rem, 3vw, var(--font-size-2xl)); }
        h3 { font-size: clamp(1.125rem, 2.5vw, var(--font-size-xl)); }
        h4 { font-size: clamp(1rem, 2vw, var(--font-size-lg)); }
        h5 { font-size: clamp(0.875rem, 1.5vw, var(--font-size-base)); }
        h6 { font-size: clamp(0.75rem, 1.2vw, var(--font-size-sm)); }

        /* ========================================
           TOP HEADER
           ======================================== */
        .top-header {
            background: var(--white);
            border-bottom: 1px solid var(--gray-300);
            height: var(--header-height);
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: var(--z-fixed);
            box-shadow: var(--shadow-sm);
            transition: height var(--transition-slow);
        }

        .top-header .container-fluid {
            height: 100%;
            display: flex;
            align-items: center;
            padding: 0 var(--spacing-lg);
            gap: var(--spacing-md);
        }

        /* Mobile sidebar toggle */
        .mobile-sidebar-toggle {
            display: none;
            background: none;
            border: none;
            color: var(--gray-600);
            font-size: 20px;
            cursor: pointer;
            padding: var(--spacing-sm);
            border-radius: var(--radius);
            transition: var(--transition);
            min-width: 44px;
            min-height: 44px;
        }

        .mobile-sidebar-toggle:hover {
            background: var(--gray-100);
            color: var(--primary);
        }

        .mobile-sidebar-toggle:focus-visible {
            outline: 2px solid var(--primary);
            outline-offset: 2px;
        }

        /* Brand */
        .staff-brand {
            display: flex;
            align-items: center;
            gap: var(--spacing-md);
            text-decoration: none;
            flex-shrink: 0;
        }

        .staff-brand-text {
            font-weight: 700;
            font-size: clamp(1.1rem, 2vw, 1.25rem);
            color: var(--primary);
            line-height: 1.2;
        }

        /* Right-side actions */
        .header-right {
            display: flex;
            align-items: center;
            gap: var(--spacing-sm);
            margin-left: auto;
        }

        /* Icon action buttons */
        .header-action-btn {
            width: 36px;
            height: 36px;
            border-radius: var(--radius-full);
            background: var(--gray-100);
            border: none;
            color: var(--gray-600);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            position: relative;
            transition: var(--transition);
        }

        @media (max-width: 768px) {
            .header-action-btn {
                width: 40px;
                height: 40px;
                min-width: 44px;
                min-height: 44px;
            }
        }

        .header-action-btn:hover {
            background: var(--primary);
            color: var(--white);
        }

        .notification-badge {
            position: absolute;
            top: -2px;
            right: -2px;
            background: var(--danger);
            color: var(--white);
            border-radius: var(--radius-full);
            min-width: 16px;
            height: 16px;
            font-size: 9px;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid var(--white);
            padding: 0 3px;
        }

        /* User menu */
        .staff-user-menu {
            display: flex;
            align-items: center;
            gap: var(--spacing-sm);
            padding: var(--spacing-xs) var(--spacing-sm);
            background: var(--gray-100);
            border-radius: var(--radius-md);
            cursor: pointer;
            border: none;
            transition: var(--transition);
            min-height: 44px;
        }

        @media (max-width: 768px) {
            .staff-user-menu {
                padding: var(--spacing-sm);
            }

            .staff-user-name {
                display: none;
            }
        }

        .staff-user-menu:hover { background: var(--gray-200); }

        .staff-user-avatar {
            width: 32px;
            height: 32px;
            border-radius: var(--radius-full);
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--white);
            font-size: var(--font-size-sm);
            font-weight: 600;
        }

        @media (max-width: 768px) {
            .staff-user-avatar {
                width: 36px;
                height: 36px;
                font-size: var(--font-size-base);
            }
        }

        .staff-user-name {
            font-size: var(--font-size-sm);
            font-weight: 600;
            color: var(--gray-900);
        }

        /* ========================================
           DROPDOWN
           ======================================== */
        .dropdown-menu {
            border-radius: var(--radius-md);
            border: 1px solid var(--gray-300);
            box-shadow: var(--shadow-lg);
            padding: var(--spacing-sm);
            animation: dropdownSlide 0.2s ease;
        }

        @keyframes dropdownSlide {
            from { opacity: 0; transform: translateY(-10px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .dropdown-item {
            border-radius: var(--radius);
            padding: var(--spacing-sm) var(--spacing-md);
            font-size: var(--font-size-sm);
            transition: var(--transition);
        }

        .dropdown-item:hover {
            background: var(--gray-100);
            color: var(--primary);
        }

        .user-dropdown .dropdown-menu {
            right: 0 !important;
            left: auto !important;
        }

        /* ========================================
           SIDEBAR
           ======================================== */
        .staff-sidebar {
            background: var(--white);
            width: var(--sidebar-width);
            position: fixed;
            top: var(--header-height);
            left: 0;
            bottom: 0;
            border-right: 1px solid var(--gray-300);
            overflow-y: auto;
            z-index: calc(var(--z-fixed) - 10);
            transition: transform var(--transition-slow);
        }

        .staff-sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .staff-sidebar::-webkit-scrollbar-thumb {
            background: var(--gray-300);
            border-radius: 3px;
        }

        .staff-sidebar::-webkit-scrollbar-thumb:hover {
            background: var(--gray-400);
        }

        .sidebar-nav {
            padding: var(--spacing-lg) var(--spacing-md);
        }

        .nav-section-title {
            font-size: var(--font-size-xs);
            font-weight: 600;
            color: var(--gray-500);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 0 var(--spacing-md);
            margin-top: var(--spacing-lg);
            margin-bottom: var(--spacing-sm);
        }

        .nav-section-title:first-child { margin-top: 0; }

        .nav-section-title.sub {
            font-size: var(--font-size-xs);
            margin-top: var(--spacing-md);
        }

        .sidebar-nav-link {
            display: flex;
            align-items: center;
            gap: var(--spacing-sm);
            padding: var(--spacing-sm) var(--spacing-md);
            color: var(--gray-600);
            text-decoration: none;
            border-radius: var(--radius);
            margin-bottom: 2px;
            font-size: var(--font-size-sm);
            font-weight: 500;
            transition: var(--transition);
            position: relative;
            min-height: 44px;
        }

        @media (max-width: 768px) {
            .sidebar-nav-link {
                font-size: var(--font-size-base);
                padding: var(--spacing-md);
            }
        }

        .sidebar-nav-link:hover {
            background: var(--gray-100);
            color: var(--primary);
        }

        .sidebar-nav-link.active {
            background: #fff5f0;
            color: var(--primary);
            font-weight: 600;
        }

        .sidebar-nav-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 3px;
            height: 20px;
            background: var(--primary);
            border-radius: 0 3px 3px 0;
        }

        .sidebar-nav-link i {
            width: 20px;
            margin-right: var(--spacing-sm);
            font-size: var(--font-size-sm);
            text-align: center;
        }

        /* Mobile close button */
        .sidebar-close-btn {
            display: none;
            position: absolute;
            top: var(--spacing-md);
            right: var(--spacing-md);
            background: var(--gray-100);
            border: none;
            width: 36px;
            height: 36px;
            border-radius: var(--radius-full);
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: var(--transition);
            z-index: 10;
            min-width: 44px;
            min-height: 44px;
        }

        .sidebar-close-btn:hover {
            background: var(--primary);
            color: var(--white);
        }

        @media (max-width: 992px) {
            .sidebar-close-btn {
                display: flex;
            }
        }

        /* ========================================
           MOBILE SIDEBAR OVERLAY
           ======================================== */
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: calc(var(--z-fixed) - 1);
            opacity: 0;
            visibility: hidden;
            transition: opacity var(--transition-slow), visibility var(--transition-slow);
            backdrop-filter: blur(2px);
            -webkit-backdrop-filter: blur(2px);
        }

        .sidebar-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        /* ========================================
           MAIN CONTENT
           ======================================== */
        .staff-main {
            margin-top: var(--header-height);
            margin-left: var(--sidebar-width);
            padding: var(--spacing-xl);
            min-height: calc(100vh - var(--header-height));
            transition: margin-left var(--transition-slow);
        }

        /* ========================================
           ALERTS
           ======================================== */
        .alert-custom {
            border-radius: var(--radius-md);
            padding: var(--spacing-md) var(--spacing-lg);
            border: 1px solid transparent;
            margin-bottom: var(--spacing-md);
            display: flex;
            align-items: center;
            gap: var(--spacing-md);
        }

        .alert-custom.success {
            background: #d1fae5;
            border-color: #10b981;
            color: #065f46;
        }

        .alert-custom.error {
            background: #fef2f2;
            border-color: #fecaca;
            color: var(--danger);
        }

        /* ========================================
           TABLE RESPONSIVENESS
           ======================================== */
        .table-responsive-wrapper {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            margin-bottom: var(--spacing-md);
            border-radius: var(--radius-md);
        }

        .table {
            width: 100%;
            min-width: 600px;
            border-collapse: collapse;
            font-size: var(--font-size-sm);
        }

        @media (max-width: 768px) {
            .table {
                font-size: 0.8125rem;
            }

            .table th,
            .table td {
                padding: 0.5rem;
                white-space: nowrap;
            }
        }

        /* ========================================
           CARD GRID RESPONSIVENESS
           ======================================== */
        .card-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: var(--spacing-md);
        }

        @media (max-width: 768px) {
            .card-grid {
                grid-template-columns: 1fr;
                gap: var(--spacing-sm);
            }
        }

        @media (min-width: 576px) and (max-width: 991px) {
            .card-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        /* ========================================
           FORM RESPONSIVENESS
           ======================================== */
        .form-control {
            width: 100%;
            padding: var(--spacing-sm) var(--spacing-md);
            font-size: var(--font-size-sm);
            line-height: 1.5;
            color: var(--gray-900);
            background-color: var(--white);
            border: 1px solid var(--gray-300);
            border-radius: var(--radius);
            transition: border-color var(--transition), box-shadow var(--transition);
            min-height: 40px;
        }

        @media (max-width: 768px) {
            .form-control {
                min-height: 44px;
                font-size: 16px;
                padding: var(--spacing-md);
            }
        }

        .form-control:focus {
            outline: 0;
            border-color: var(--primary);
            box-shadow: 0 0 0 0.2rem rgba(234, 28, 77, 0.25);
        }

        .form-label {
            display: block;
            margin-bottom: var(--spacing-xs);
            font-weight: 500;
            color: var(--gray-700);
            font-size: var(--font-size-sm);
        }

        /* ========================================
           BUTTON RESPONSIVENESS
           ======================================== */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: var(--spacing-sm);
            padding: var(--spacing-sm) var(--spacing-lg);
            font-size: var(--font-size-sm);
            font-weight: 500;
            line-height: 1.5;
            border-radius: var(--radius);
            transition: all var(--transition);
            min-height: 38px;
            cursor: pointer;
            text-decoration: none;
            border: 1px solid transparent;
        }

        @media (max-width: 768px) {
            .btn {
                min-height: 44px;
                font-size: var(--font-size-base);
                padding: var(--spacing-md) var(--spacing-lg);
            }

            .btn-sm {
                min-height: 38px;
                padding: var(--spacing-xs) var(--spacing-md);
                font-size: 0.875rem;
            }
        }

        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
            color: var(--white);
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
            color: var(--white);
            transform: translateY(-1px);
        }

        /* ========================================
           RESPONSIVE STYLES
           ======================================== */

        /* Extra Small Devices (max-width: 575.98px) */
        @media (max-width: 575.98px) {
            :root {
                --header-height: 56px;
            }

            .top-header .container-fluid {
                padding: 0 var(--spacing-sm);
                gap: var(--spacing-xs);
            }

            .mobile-sidebar-toggle {
                display: flex;
                margin-right: var(--spacing-xs);
                padding: var(--spacing-xs);
            }

            .staff-brand-text {
                font-size: 1.1rem;
            }

            .header-action-btn {
                width: 32px;
                height: 32px;
                font-size: 14px;
            }

            .staff-main {
                padding: var(--spacing-sm);
            }
        }

        /* Small Devices (min-width: 576px) and (max-width: 767.98px) */
        @media (min-width: 576px) and (max-width: 767.98px) {
            .top-header .container-fluid {
                padding: 0 var(--spacing-md);
            }

            .staff-brand-text {
                font-size: 1.2rem;
            }

            .staff-main {
                padding: var(--spacing-md);
            }
        }

        /* Medium Devices (max-width: 991.98px) */
        @media (max-width: 991.98px) {
            .staff-sidebar {
                transform: translateX(-100%);
                position: fixed;
                top: var(--header-height);
                left: 0;
                z-index: calc(var(--z-fixed) + 100);
                height: calc(100vh - var(--header-height));
                box-shadow: var(--shadow-xl);
            }

            .staff-sidebar.show {
                transform: translateX(0);
            }

            .staff-sidebar.show ~ .sidebar-overlay {
                opacity: 1;
                visibility: visible;
            }

            .staff-main {
                margin-left: 0;
                padding: var(--spacing-md);
            }

            .mobile-sidebar-toggle {
                display: flex !important;
            }

            .top-header {
                z-index: calc(var(--z-fixed) + 101);
            }
        }

        /* Large Devices (min-width: 992px) */
        @media (min-width: 992px) {
            .staff-sidebar {
                transform: translateX(0);
            }

            .staff-main {
                margin-left: var(--sidebar-width);
                padding: var(--spacing-xl);
            }

            .mobile-sidebar-toggle {
                display: none !important;
            }

            .sidebar-close-btn {
                display: none !important;
            }
        }

        /* Extra Large Devices */
        @media (min-width: 1200px) {
            :root {
                --header-height: 64px;
            }

            .staff-main {
                padding: var(--spacing-xl);
            }
        }

        /* ========================================
           SAFE AREA INSETS
           ======================================== */
        @supports (padding: max(0px)) {
            .top-header .container-fluid {
                padding-left: max(var(--spacing-lg), env(safe-area-inset-left));
                padding-right: max(var(--spacing-lg), env(safe-area-inset-right));
            }

            .staff-sidebar {
                padding-left: max(var(--spacing-md), env(safe-area-inset-left));
                padding-right: max(var(--spacing-md), env(safe-area-inset-right));
            }

            .staff-main {
                padding-left: max(var(--spacing-xl), env(safe-area-inset-left));
                padding-right: max(var(--spacing-xl), env(safe-area-inset-right));
            }
        }

        /* ========================================
           REDUCED MOTION PREFERENCES
           ======================================== */
        @media (prefers-reduced-motion: reduce) {
            * {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
                scroll-behavior: auto !important;
            }
        }

        /* ========================================
           HIGH CONTRAST MODE
           ======================================== */
        @media (prefers-contrast: high) {
            .top-header {
                border-bottom: 2px solid var(--gray-900);
            }

            .staff-sidebar {
                border-right: 2px solid var(--gray-900);
            }

            .btn-primary {
                border: 2px solid var(--white);
            }
        }

        /* ========================================
           TOUCH DEVICE OPTIMIZATIONS
           ======================================== */
        @media (hover: none) and (pointer: coarse) {
            .staff-user-menu,
            .header-action-btn,
            .sidebar-nav-link,
            .btn,
            .sidebar-accordion-header {
                min-height: 44px;
                min-width: 44px;
            }

            .sidebar-nav-link,
            .sidebar-accordion-header {
                padding: var(--spacing-md);
            }

            /* Remove hover effects */
            .btn-primary:hover {
                transform: none;
            }

            a, button {
                -webkit-tap-highlight-color: rgba(234, 28, 77, 0.2);
            }
        }

        /* ========================================
           PRINT STYLES
           ======================================== */
        @media print {
            .top-header,
            .staff-sidebar,
            .sidebar-overlay,
            .mobile-sidebar-toggle {
                display: none !important;
            }

            .staff-main {
                margin-left: 0 !important;
                padding: 0 !important;
            }

            body {
                background: white;
            }
        }

        /* ========================================
           LANDSCAPE ORIENTATION
           ======================================== */
        @media (max-width: 767px) and (orientation: landscape) {
            .top-header .container-fluid {
                padding: 0 var(--spacing-sm);
                gap: var(--spacing-xs);
            }

            .staff-brand-text {
                font-size: 1rem;
            }

            .mobile-sidebar-toggle {
                padding: var(--spacing-xs);
            }
        }

        /* ========================================
           FOCUS VISIBLE
           ======================================== */
        .mobile-sidebar-toggle:focus-visible,
        .sidebar-close-btn:focus-visible,
        .sidebar-nav-link:focus-visible,
        .btn:focus-visible {
            outline: 2px solid var(--primary);
            outline-offset: 2px;
            box-shadow: 0 0 0 4px rgba(234, 28, 77, 0.2);
        }

        /* ========================================
           UTILITY CLASSES
           ======================================== */
        .sr-only {
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

        .text-primary { color: var(--primary) !important; }
        .text-secondary { color: var(--secondary) !important; }
        .text-success { color: var(--success) !important; }
        .text-danger { color: var(--danger) !important; }
        .text-warning { color: var(--warning) !important; }
        .text-muted { color: var(--gray-500) !important; }
    </style>

    @stack('styles')
</head>

<body>

    {{-- ===================== TOP HEADER ===================== --}}
    <header class="top-header" role="banner">
        <div class="container-fluid">

            {{-- Mobile sidebar toggle (left) --}}
            <button class="mobile-sidebar-toggle"
                    id="mobileSidebarToggle"
                    type="button"
                    aria-label="{{ __('Toggle navigation menu') }}"
                    aria-expanded="false"
                    aria-controls="staffSidebar">
                <i class="fas fa-bars" aria-hidden="true"></i>
            </button>

            {{-- Brand --}}
            @php
                $user = auth()->user();
                if ($user->hasAnyRole(['super-admin', 'admin-operations', 'operations-admin'])) {
                    $dashboardRoute = route('admin.dashboard');
                } elseif ($user->hasRole('org-admin')) {
                    $dashboardRoute = route('organization.dashboard');
                } elseif ($user->hasAnyRole(['coach', 'head-coach', 'assistant-coach'])) {
                    $dashboardRoute = route('coach.dashboard');
                } elseif ($user->hasRole('team-manager')) {
                    $dashboardRoute = route('manager.dashboard');
                } elseif ($user->hasRole('finance-officer')) {
                    $dashboardRoute = route($user->hasRole('super-admin') ? 'super-admin.finance.dashboard' : 'finance.dashboard');
                } elseif ($user->hasRole('media-officer')) {
                    $dashboardRoute = route('media.dashboard');
                } elseif ($user->hasRole('safeguarding-officer')) {
                    $dashboardRoute = route('welfare.dashboard');
                } elseif ($user->hasRole('player')) {
                    $dashboardRoute = route('player.portal.dashboard');
                } elseif ($user->hasRole('parent')) {
                    $dashboardRoute = route('parent.dashboard');
                } elseif ($user->hasRole('partner')) {
                    $dashboardRoute = route('partner.dashboard');
                } else {
                    $dashboardRoute = route('home');
                }
            @endphp

            <a href="{{ $dashboardRoute }}" class="staff-brand" aria-label="{{ __('Go to dashboard') }}">
                <span class="staff-brand-text">{{ __('GameSuite') }}</span>
            </a>

            {{-- Right-side actions (far right via margin-left: auto) --}}
            <nav class="header-right" role="navigation" aria-label="{{ __('Header actions') }}">

                {{-- Website link --}}
                <a href="{{ route('home') }}"
                   target="_blank"
                   class="header-action-btn"
                   aria-label="{{ __('View Website') }}"
                   title="{{ __('View Website') }}">
                    <i class="fas fa-external-link-alt" aria-hidden="true"></i>
                </a>

                {{-- Notifications --}}
                @php
                    $pendingPlayers = \App\Models\Player::where('registration_status', 'Pending')->count();
                @endphp
                <div class="dropdown">
                    <button class="header-action-btn"
                            type="button"
                            data-bs-toggle="dropdown"
                            aria-label="{{ __('Notifications') }}"
                            aria-haspopup="true"
                            aria-expanded="false">
                        <i class="fas fa-bell" aria-hidden="true"></i>
                        @if($pendingPlayers > 0)
                            <span class="notification-badge" aria-label="{{ $pendingPlayers }} {{ __('notifications') }}">
                                {{ $pendingPlayers }}
                            </span>
                        @endif
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" style="width: 320px;" role="menu">
                        <li><h6 class="dropdown-header">{{ __('Notifications') }}</h6></li>
                        @if($pendingPlayers > 0)
                            <li>
                                @if(auth()->user()->hasAnyRole(['super-admin', 'operations-admin', 'coach', 'head-coach', 'team-manager']))
                                    <a class="dropdown-item" href="{{ route('admin.players.index') }}" role="menuitem">
                                        <i class="fas fa-user-plus text-success me-2" aria-hidden="true"></i>
                                        {{ $pendingPlayers }} {{ __('pending registrations') }}
                                    </a>
                                @else
                                    <a class="dropdown-item" href="{{ route('coach.players') }}" role="menuitem">
                                        <i class="fas fa-user-plus text-success me-2" aria-hidden="true"></i>
                                        {{ $pendingPlayers }} {{ __('pending registrations') }}
                                    </a>
                                @endif
                            </li>
                        @else
                            <li><span class="dropdown-item text-center text-muted" role="menuitem">{{ __('No notifications') }}</span></li>
                        @endif
                    </ul>
                </div>

                {{-- User menu (far right) --}}
                <div class="dropdown user-dropdown">
                    <button class="staff-user-menu"
                            type="button"
                            data-bs-toggle="dropdown"
                            aria-label="{{ __('User menu for') }} {{ Auth::user()->name }}"
                            aria-haspopup="true"
                            aria-expanded="false">
                        <div class="staff-user-avatar" aria-hidden="true">{{ substr(Auth::user()->name, 0, 1) }}</div>
                        <span class="staff-user-name">{{ Auth::user()->name }}</span>
                        <i class="fas fa-chevron-down" aria-hidden="true" style="font-size: 10px; color: #999;"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" role="menu">
                        <li>
                            <a class="dropdown-item" href="{{ route('profile.edit') }}" role="menuitem">
                                <i class="fas fa-user me-2" aria-hidden="true"></i>{{ __('Profile') }}
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger" role="menuitem">
                                    <i class="fas fa-sign-out-alt me-2" aria-hidden="true"></i>{{ __('Logout') }}
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>

            </nav>
        </div>
    </header>

     {{-- ===================== SIDEBAR ===================== --}}
     <aside class="staff-sidebar" id="staffSidebar" role="navigation" aria-label="{{ __('Main navigation') }}">
         {{-- Mobile Close Button --}}
         <button class="sidebar-close-btn"
                 type="button"
                 aria-label="{{ __('Close navigation menu') }}"
                 onclick="toggleSidebar()">
             <i class="fas fa-times" aria-hidden="true"></i>
         </button>

         <nav class="sidebar-nav">

            {{-- COACHING --}}
            @if(auth()->user()->hasAnyRole(['coach', 'head-coach', 'assistant-coach']))
                <div class="nav-section-title" role="heading" aria-level="2">{{ __('Coaching') }}</div>
                <a href="{{ route('coach.dashboard') }}" class="sidebar-nav-link {{ request()->routeIs('coach.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-th-large" aria-hidden="true"></i><span>{{ __('Dashboard') }}</span>
                </a>
                <a href="{{ route('coach.sessions') }}" class="sidebar-nav-link {{ request()->routeIs('coach.sessions') ? 'active' : '' }}">
                    <i class="fas fa-stopwatch" aria-hidden="true"></i><span>{{ __('Training Sessions') }}</span>
                </a>
                <a href="{{ route('coach.dashboard') }}" class="sidebar-nav-link">
                    <i class="fas fa-calendar-check" aria-hidden="true"></i><span>{{ __('Attendance') }}</span>
                </a>
                <a href="{{ route('coach.players') }}" class="sidebar-nav-link {{ request()->routeIs('coach.players') ? 'active' : '' }}">
                    <i class="fas fa-users" aria-hidden="true"></i><span>{{ __('Players') }}</span>
                </a>
            @endif

            {{-- TEAM MANAGER --}}
            @if(auth()->user()->hasRole('team-manager'))
                <div class="nav-section-title" role="heading" aria-level="2">{{ __('Management') }}</div>
                <a href="{{ route('manager.dashboard') }}" class="sidebar-nav-link {{ request()->routeIs('manager.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-th-large" aria-hidden="true"></i><span>{{ __('Dashboard') }}</span>
                </a>
                <a href="{{ route('manager.registrations') }}" class="sidebar-nav-link {{ request()->routeIs('manager.registrations') ? 'active' : '' }}">
                    <i class="fas fa-user-plus" aria-hidden="true"></i><span>{{ __('Registrations') }}</span>
                </a>
                <a href="{{ route('manager.logistics') }}" class="sidebar-nav-link {{ request()->routeIs('manager.logistics') ? 'active' : '' }}">
                    <i class="fas fa-truck" aria-hidden="true"></i><span>{{ __('Logistics') }}</span>
                </a>

                <div class="nav-section-title" role="heading" aria-level="2">{{ __('Equipment Management') }}</div>
                <a href="{{ route('manager.equipment.categories') }}" class="sidebar-nav-link {{ request()->routeIs('manager.equipment.categories') ? 'active' : '' }}">
                    <i class="fas fa-tags" aria-hidden="true"></i><span>{{ __('Equipment Categories') }}</span>
                </a>
                <a href="{{ route('manager.equipment.inventory') }}" class="sidebar-nav-link {{ request()->routeIs('manager.equipment.inventory') ? 'active' : '' }}">
                    <i class="fas fa-boxes" aria-hidden="true"></i><span>{{ __('Inventory Counts') }}</span>
                </a>
                <a href="{{ route('manager.equipment.distribution') }}" class="sidebar-nav-link {{ request()->routeIs('manager.equipment.distribution') ? 'active' : '' }}">
                    <i class="fas fa-clipboard-list" aria-hidden="true"></i><span>{{ __('Distribution Records') }}</span>
                </a>
                <a href="{{ route('manager.equipment.compliance') }}" class="sidebar-nav-link {{ request()->routeIs('manager.equipment.compliance') ? 'active' : '' }}">
                    <i class="fas fa-file-contract" aria-hidden="true"></i><span>{{ __('Sponsor Compliance') }}</span>
                </a>
            @endif

            {{-- FINANCE --}}
            @if(auth()->user()->hasPermission('view_financials') || auth()->user()->hasPermission('finance.view') || auth()->user()->hasAnyRole(['finance-officer', 'finance-admin', 'super-admin', 'org-admin', 'admin']))
                @php $isSuperAdmin = auth()->user()->hasRole('super-admin'); @endphp
                <div class="nav-section-title" role="heading" aria-level="2">{{ __('Finance') }}</div>
                <a href="{{ route($isSuperAdmin ? 'super-admin.finance.dashboard' : 'finance.dashboard') }}"
                   class="sidebar-nav-link {{ request()->routeIs('finance.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-th-large" aria-hidden="true"></i><span>{{ __('Dashboard') }}</span>
                </a>
                <a href="{{ route($isSuperAdmin ? 'super-admin.finance.payments' : 'finance.payments') }}"
                   class="sidebar-nav-link {{ request()->routeIs('finance.payments*') ? 'active' : '' }}">
                    <i class="fas fa-money-bill-wave" aria-hidden="true"></i><span>{{ __('Payments') }}</span>
                </a>

                <div class="nav-section-title sub" role="heading" aria-level="3">{{ __('Budget Planning') }}</div>
                <a href="{{ route($isSuperAdmin ? 'super-admin.finance.budgets.summary' : 'finance.budgets.summary') }}"
                   class="sidebar-nav-link {{ request()->routeIs('finance.budgets.summary') ? 'active' : '' }}">
                    <i class="fas fa-chart-pie" aria-hidden="true"></i><span>{{ __('Budget Summary') }}</span>
                </a>
                <a href="{{ route($isSuperAdmin ? 'super-admin.finance.budgets.index' : 'finance.budgets.index') }}"
                   class="sidebar-nav-link {{ request()->routeIs('finance.budgets.index', 'finance.budgets.create', 'finance.budgets.show', 'finance.budgets.edit') ? 'active' : '' }}">
                    <i class="fas fa-calculator" aria-hidden="true"></i><span>{{ __('Budget Plans') }}</span>
                </a>
                <a href="{{ route($isSuperAdmin ? 'super-admin.finance.budgets.comparison' : 'finance.budgets.comparison') }}"
                   class="sidebar-nav-link {{ request()->routeIs('finance.budgets.comparison') ? 'active' : '' }}">
                    <i class="fas fa-balance-scale" aria-hidden="true"></i><span>{{ __('Budget vs Actual') }}</span>
                </a>

                <div class="nav-section-title sub" role="heading" aria-level="3">{{ __('Expenses') }}</div>
                <a href="{{ route($isSuperAdmin ? 'super-admin.finance.expenses.index' : 'finance.expenses.index') }}"
                   class="sidebar-nav-link {{ request()->routeIs('finance.expenses.index', 'finance.expenses.create', 'finance.expenses.show', 'finance.expenses.edit') ? 'active' : '' }}">
                    <i class="fas fa-receipt" aria-hidden="true"></i><span>{{ __('All Expenses') }}</span>
                </a>
                <a href="{{ route($isSuperAdmin ? 'super-admin.finance.expenses.report' : 'finance.expenses.report') }}"
                   class="sidebar-nav-link {{ request()->routeIs('finance.expenses.report') ? 'active' : '' }}">
                    <i class="fas fa-chart-bar" aria-hidden="true"></i><span>{{ __('Expense Reports') }}</span>
                </a>

                <div class="nav-section-title sub" role="heading" aria-level="3">{{ __('Reports') }}</div>
                <a href="{{ route($isSuperAdmin ? 'super-admin.finance.reports' : 'finance.reports') }}"
                   class="sidebar-nav-link {{ request()->routeIs('finance.reports') ? 'active' : '' }}">
                    <i class="fas fa-file-invoice-dollar" aria-hidden="true"></i><span>{{ __('Revenue Reports') }}</span>
                </a>
                <a href="{{ route($isSuperAdmin ? 'super-admin.finance.analytics' : 'finance.analytics') }}"
                   class="sidebar-nav-link {{ request()->routeIs('finance.analytics') ? 'active' : '' }}">
                    <i class="fas fa-analytics" aria-hidden="true"></i><span>{{ __('Analytics') }}</span>
                </a>
            @endif

            {{-- MEDIA OFFICER --}}
            @if(auth()->user()->hasRole('media-officer'))
                <div class="nav-section-title" role="heading" aria-level="2">{{ __('Media') }}</div>
                <a href="{{ route('media.dashboard') }}" class="sidebar-nav-link {{ request()->routeIs('media.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-th-large" aria-hidden="true"></i><span>{{ __('Dashboard') }}</span>
                </a>
                <a href="{{ route('media.blogs') }}" class="sidebar-nav-link {{ request()->routeIs('media.blogs') ? 'active' : '' }}">
                    <i class="fas fa-blog" aria-hidden="true"></i><span>{{ __('Blogs') }}</span>
                </a>
            @endif

            {{-- SAFEGUARDING OFFICER --}}
            @if(auth()->user()->hasRole('safeguarding-officer'))
                <div class="nav-section-title" role="heading" aria-level="2">{{ __('Welfare') }}</div>
                <a href="{{ route('welfare.dashboard') }}" class="sidebar-nav-link {{ request()->routeIs('welfare.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-th-large" aria-hidden="true"></i><span>{{ __('Dashboard') }}</span>
                </a>
                <a href="{{ route('welfare.attention.list') }}" class="sidebar-nav-link {{ request()->routeIs('welfare.attention.list') ? 'active' : '' }}">
                    <i class="fas fa-exclamation-circle" aria-hidden="true"></i><span>{{ __('Attention List') }}</span>
                </a>
                <a href="{{ route('welfare.compliance') }}" class="sidebar-nav-link {{ request()->routeIs('welfare.compliance') ? 'active' : '' }}">
                    <i class="fas fa-shield-alt" aria-hidden="true"></i><span>{{ __('Compliance') }}</span>
                </a>
            @endif

            {{-- PLAYER --}}
            @if(auth()->user()->hasRole('player') && !auth()->user()->hasAnyRole(['coach', 'head-coach', 'assistant-coach', 'team-manager', 'finance-officer', 'media-officer', 'safeguarding-officer', 'super-admin', 'admin', 'operations-admin', 'partner', 'parent']))
                <div class="nav-section-title" role="heading" aria-level="2">{{ __('My Portal') }}</div>
                <a href="{{ route('player.portal.dashboard') }}" class="sidebar-nav-link {{ request()->routeIs('player.portal.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-th-large" aria-hidden="true"></i><span>{{ __('Dashboard') }}</span>
                </a>
                <a href="{{ route('player.portal.profile') }}" class="sidebar-nav-link {{ request()->routeIs('player.portal.profile') ? 'active' : '' }}">
                    <i class="fas fa-user" aria-hidden="true"></i><span>{{ __('My Profile') }}</span>
                </a>
                <a href="{{ route('player.portal.programs') }}" class="sidebar-nav-link {{ request()->routeIs('player.portal.programs') ? 'active' : '' }}">
                    <i class="fas fa-graduation-cap" aria-hidden="true"></i><span>{{ __('Programs') }}</span>
                </a>
                <a href="{{ route('player.portal.training') }}" class="sidebar-nav-link {{ request()->routeIs('player.portal.training') ? 'active' : '' }}">
                    <i class="fas fa-running" aria-hidden="true"></i><span>{{ __('Training') }}</span>
                </a>
                <a href="{{ route('player.portal.schedule') }}" class="sidebar-nav-link {{ request()->routeIs('player.portal.schedule') ? 'active' : '' }}">
                    <i class="fas fa-calendar-alt" aria-hidden="true"></i><span>{{ __('Schedule') }}</span>
                </a>
                <a href="{{ route('player.portal.resources') }}" class="sidebar-nav-link {{ request()->routeIs('player.portal.resources') ? 'active' : '' }}">
                    <i class="fas fa-book" aria-hidden="true"></i><span>{{ __('Resources') }}</span>
                </a>
                <a href="{{ route('player.portal.orders') }}" class="sidebar-nav-link {{ request()->routeIs('player.portal.orders') ? 'active' : '' }}">
                    <i class="fas fa-shopping-bag" aria-hidden="true"></i><span>{{ __('Orders') }}</span>
                </a>
                <a href="{{ route('player.portal.communication') }}" class="sidebar-nav-link {{ request()->routeIs('player.portal.communication') ? 'active' : '' }}">
                    <i class="fas fa-comments" aria-hidden="true"></i><span>{{ __('Messages') }}</span>
                </a>
                <a href="{{ route('player.portal.support') }}" class="sidebar-nav-link {{ request()->routeIs('player.portal.support') ? 'active' : '' }}">
                    <i class="fas fa-life-ring" aria-hidden="true"></i><span>{{ __('Support') }}</span>
                </a>
            @endif

            {{-- PARTNER --}}
            @if(auth()->user()->hasRole('partner'))
                <div class="nav-section-title" role="heading" aria-level="2">{{ __('Partner Portal') }}</div>
                <a href="{{ route('partner.dashboard') }}" class="sidebar-nav-link {{ request()->routeIs('partner.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-th-large" aria-hidden="true"></i><span>{{ __('Dashboard') }}</span>
                </a>
                <a href="{{ route('partner.players') }}" class="sidebar-nav-link {{ request()->routeIs('partner.players') ? 'active' : '' }}">
                    <i class="fas fa-users" aria-hidden="true"></i><span>{{ __('My Players') }}</span>
                </a>
                <a href="{{ route('partner.analytics') }}" class="sidebar-nav-link {{ request()->routeIs('partner.analytics') ? 'active' : '' }}">
                    <i class="fas fa-chart-line" aria-hidden="true"></i><span>{{ __('Analytics') }}</span>
                </a>
            @endif

            {{-- PARENT --}}
            @if(auth()->user()->hasRole('parent'))
                <div class="nav-section-title" role="heading" aria-level="2">{{ __('Parent Portal') }}</div>
                <a href="{{ route('parent.dashboard') }}" class="sidebar-nav-link {{ request()->routeIs('parent.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-th-large" aria-hidden="true"></i><span>{{ __('Dashboard') }}</span>
                </a>
                <a href="{{ route('parent.profile') }}" class="sidebar-nav-link {{ request()->routeIs('parent.profile') ? 'active' : '' }}">
                    <i class="fas fa-user" aria-hidden="true"></i><span>{{ __('My Child') }}</span>
                </a>
                <a href="{{ route('parent.finances') }}" class="sidebar-nav-link {{ request()->routeIs('parent.finances') ? 'active' : '' }}">
                    <i class="fas fa-credit-card" aria-hidden="true"></i><span>{{ __('Finances') }}</span>
                </a>
                <a href="{{ route('parent.training') }}" class="sidebar-nav-link {{ request()->routeIs('parent.training') ? 'active' : '' }}">
                    <i class="fas fa-calendar-check" aria-hidden="true"></i><span>{{ __('Training') }}</span>
                </a>
                <a href="{{ route('parent.matches') }}" class="sidebar-nav-link {{ request()->routeIs('parent.matches') ? 'active' : '' }}">
                    <i class="fas fa-futbol" aria-hidden="true"></i><span>{{ __('Matches') }}</span>
                </a>
                <a href="{{ route('parent.insights') }}" class="sidebar-nav-link {{ request()->routeIs('parent.insights') ? 'active' : '' }}">
                    <i class="fas fa-brain" aria-hidden="true"></i><span>{{ __('AI Insights') }}</span>
                </a>
                <a href="{{ route('parent.media') }}" class="sidebar-nav-link {{ request()->routeIs('parent.media') ? 'active' : '' }}">
                    <i class="fas fa-photo-video" aria-hidden="true"></i><span>{{ __('Media') }}</span>
                </a>
                <a href="{{ route('parent.announcements') }}" class="sidebar-nav-link {{ request()->routeIs('parent.announcements') ? 'active' : '' }}">
                    <i class="fas fa-bullhorn" aria-hidden="true"></i><span>{{ __('News') }}</span>
                </a>
            @endif

            {{-- ADMIN --}}
            @if(auth()->user()->hasAnyRole(['super-admin', 'org-admin', 'operations-admin', 'admin']))
                <div class="nav-section-title" role="heading" aria-level="2">{{ __('Admin') }}</div>

                @if(auth()->user()->hasRole('org-admin'))
                    <a href="{{ route('organization.dashboard') }}" class="sidebar-nav-link {{ request()->routeIs('organization.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-th-large" aria-hidden="true"></i><span>{{ __('Dashboard') }}</span>
                    </a>
                    <a href="{{ route('organization.attendance.index') }}" class="sidebar-nav-link {{ request()->routeIs('organization.attendance.*') ? 'active' : '' }}">
                        <i class="fas fa-calendar-check" aria-hidden="true"></i><span>{{ __('Attendance') }}</span>
                    </a>
                    <a href="{{ route('admin.programs.index') }}" class="sidebar-nav-link {{ request()->routeIs('admin.programs.*') ? 'active' : '' }}">
                        <i class="fas fa-graduation-cap" aria-hidden="true"></i><span>{{ __('Programs') }}</span>
                    </a>
                    <a href="{{ route('admin.staff.index') }}" class="sidebar-nav-link {{ request()->routeIs('admin.staff.*') ? 'active' : '' }}">
                        <i class="fas fa-users-cog" aria-hidden="true"></i><span>{{ __('Staff') }}</span>
                    </a>
                    <a href="{{ route('organization.roles.index') }}" class="sidebar-nav-link {{ request()->routeIs('organization.roles.*') ? 'active' : '' }}">
                        <i class="fas fa-user-shield" aria-hidden="true"></i><span>{{ __('Roles & Permissions') }}</span>
                    </a>
                    <a href="{{ route('admin.settings.company') }}" class="sidebar-nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                        <i class="fas fa-cog" aria-hidden="true"></i><span>{{ __('Settings') }}</span>
                    </a>

                @elseif(auth()->user()->hasRole('super-admin'))
                    <a href="{{ route('super-admin.dashboard') }}" class="sidebar-nav-link {{ request()->routeIs('super-admin.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-th-large" aria-hidden="true"></i><span>{{ __('Super Admin') }}</span>
                    </a>
                    <a href="{{ route('admin.dashboard') }}" class="sidebar-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt" aria-hidden="true"></i><span>{{ __('Admin Dashboard') }}</span>
                    </a>

                @elseif(auth()->user()->hasAnyRole(['operations-admin', 'admin']))
                    <a href="{{ route('admin.dashboard') }}" class="sidebar-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-th-large" aria-hidden="true"></i><span>{{ __('Dashboard') }}</span>
                    </a>
                    <a href="{{ route('admin.players.index') }}" class="sidebar-nav-link {{ request()->routeIs('admin.players.*') ? 'active' : '' }}">
                        <i class="fas fa-users" aria-hidden="true"></i><span>{{ __('Players') }}</span>
                    </a>
                    <a href="{{ route('admin.attendance.index') }}" class="sidebar-nav-link {{ request()->routeIs('admin.attendance.*') ? 'active' : '' }}">
                        <i class="fas fa-calendar-check" aria-hidden="true"></i><span>{{ __('Attendance') }}</span>
                    </a>
                    <a href="{{ route('admin.programs.index') }}" class="sidebar-nav-link {{ request()->routeIs('admin.programs.*') ? 'active' : '' }}">
                        <i class="fas fa-graduation-cap" aria-hidden="true"></i><span>{{ __('Programs') }}</span>
                    </a>
                    <a href="{{ route('admin.staff.index') }}" class="sidebar-nav-link {{ request()->routeIs('admin.staff.*') ? 'active' : '' }}">
                        <i class="fas fa-users-cog" aria-hidden="true"></i><span>{{ __('Staff') }}</span>
                    </a>
                    <a href="{{ route('admin.partners.index') }}" class="sidebar-nav-link {{ request()->routeIs('admin.partners.*') ? 'active' : '' }}">
                        <i class="fas fa-handshake" aria-hidden="true"></i><span>{{ __('Partners') }}</span>
                    </a>
                @endif
            @endif

            {{-- SYSTEM (super-admin + team-manager) --}}
            @if(auth()->user()->hasAnyRole(['super-admin', 'team-manager']))
                <div class="nav-section-title" role="heading" aria-level="2">{{ __('System') }}</div>
                <a href="{{ route('super-admin.organizations.index') }}" class="sidebar-nav-link {{ request()->routeIs('super-admin.organizations.*') ? 'active' : '' }}">
                    <i class="fas fa-building" aria-hidden="true"></i><span>{{ __('Organizations') }}</span>
                </a>
                <a href="{{ route('super-admin.users.index') }}" class="sidebar-nav-link {{ request()->routeIs('super-admin.users.*') ? 'active' : '' }}">
                    <i class="fas fa-users" aria-hidden="true"></i><span>{{ __('Users') }}</span>
                </a>
                <a href="{{ route('super-admin.plans.index') }}" class="sidebar-nav-link {{ request()->routeIs('super-admin.plans.*') ? 'active' : '' }}">
                    <i class="fas fa-tags" aria-hidden="true"></i><span>{{ __('Plans') }}</span>
                </a>
                <a href="{{ route('super-admin.roles.index') }}" class="sidebar-nav-link {{ request()->routeIs('super-admin.roles.*') ? 'active' : '' }}">
                    <i class="fas fa-user-shield" aria-hidden="true"></i><span>{{ __('Roles') }}</span>
                </a>
                <a href="{{ route('super-admin.attendance.index') }}" class="sidebar-nav-link {{ request()->routeIs('super-admin.attendance.*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-check" aria-hidden="true"></i><span>{{ __('Attendance') }}</span>
                </a>
                <a href="{{ route('organization.roles.index') }}" class="sidebar-nav-link {{ request()->routeIs('organization.roles.*') ? 'active' : '' }}">
                    <i class="fas fa-users-cog" aria-hidden="true"></i><span>{{ __('Org Roles') }}</span>
                </a>
                <a href="{{ route('manager.equipment.categories') }}" class="sidebar-nav-link {{ request()->routeIs('manager.equipment.*') ? 'active' : '' }}">
                    <i class="fas fa-boxes" aria-hidden="true"></i><span>{{ __('Equipment') }}</span>
                </a>

                <div class="nav-section-title" role="heading" aria-level="2">{{ __('Website') }}</div>
                <a href="{{ route('admin.blog.index') }}" class="sidebar-nav-link {{ request()->routeIs('admin.blog.*') ? 'active' : '' }}">
                    <i class="fas fa-newspaper" aria-hidden="true"></i><span>{{ __('Announcements') }}</span>
                </a>
                <a href="{{ route('admin.website-players.index') }}" class="sidebar-nav-link {{ request()->routeIs('admin.website-players.*') ? 'active' : '' }}">
                    <i class="fas fa-users" aria-hidden="true"></i><span>{{ __('Player Gallery') }}</span>
                </a>
                <a href="{{ route('admin.jobs.index') }}" class="sidebar-nav-link {{ request()->routeIs('admin.jobs.*') ? 'active' : '' }}">
                    <i class="fas fa-briefcase" aria-hidden="true"></i><span>{{ __('Careers') }}</span>
                </a>
                <a href="{{ route('admin.documents.index') }}" class="sidebar-nav-link {{ request()->routeIs('admin.documents.*') ? 'active' : '' }}">
                    <i class="fas fa-file-alt" aria-hidden="true"></i><span>{{ __('Documents') }}</span>
                </a>
            @endif

            {{-- QUICK LINKS (all users) --}}
            <div class="nav-section-title" role="heading" aria-level="2">{{ __('Quick Links') }}</div>
            <a href="{{ route('home') }}" target="_blank" class="sidebar-nav-link">
                <i class="fas fa-external-link-alt" aria-hidden="true"></i><span>{{ __('View Website') }}</span>
            </a>
            <a href="{{ route('profile.edit') }}" class="sidebar-nav-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                <i class="fas fa-user-cog" aria-hidden="true"></i><span>{{ __('Settings') }}</span>
            </a>

         </nav>
     </aside>

     {{-- Mobile Sidebar Overlay --}}
     <div class="sidebar-overlay" id="sidebarOverlay" aria-hidden="true" onclick="toggleSidebar()"></div>

     {{-- ===================== MAIN CONTENT ===================== --}}
     <main class="staff-main">

        @if(session('success'))
        <div class="alert-custom success">
            <i class="fas fa-check-circle" aria-hidden="true"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close ms-auto" onclick="this.parentElement.remove()" aria-label="{{ __('Close') }}"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert-custom error">
            <i class="fas fa-exclamation-circle" aria-hidden="true"></i>
            <div>{{ session('error') }}</div>
            <button type="button" class="btn-close ms-auto" onclick="this.parentElement.remove()" aria-label="{{ __('Close') }}"></button>
        </div>
        @endif

        @if($errors->any())
        <div class="alert-custom error">
            <i class="fas fa-exclamation-triangle" aria-hidden="true"></i>
            <div>
                <strong>{{ __('Please fix the following errors:') }}</strong>
                <ul class="mb-0 mt-2" style="padding-left: 1.25rem;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            <button type="button" class="btn-close ms-auto" onclick="this.parentElement.remove()" aria-label="{{ __('Close') }}"></button>
        </div>
        @endif

        @auth
            @include('components.trial-notification')
        @endauth

        @yield('content')

        <footer class="staff-footer">
            <div class="container-fluid">
                <div class="staff-footer-content">
                    <div>&copy; {{ date('Y') }} {{ __('GameSuite') }}. {{ __('All rights reserved.') }}</div>
                </div>
            </div>
        </footer>

    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        const toggleBtn = document.getElementById('mobileSidebarToggle');
        const sidebar   = document.getElementById('staffSidebar');
        const overlay   = document.getElementById('sidebarOverlay');

        if (toggleBtn && sidebar) {
            toggleBtn.addEventListener('click', function () {
                const isExpanded = sidebar.classList.contains('show');
                sidebar.classList.toggle('show');

                if (overlay) {
                    overlay.classList.toggle('active');
                }

                this.setAttribute('aria-expanded', !isExpanded);
            });

            // Close on overlay click
            if (overlay) {
                overlay.addEventListener('click', function() {
                    sidebar.classList.remove('show');
                    overlay.classList.remove('active');
                    toggleBtn.setAttribute('aria-expanded', 'false');
                });
            }

            document.addEventListener('click', function (e) {
                if (!sidebar.contains(e.target) && !toggleBtn.contains(e.target) && !overlay?.contains(e.target)) {
                    sidebar.classList.remove('show');
                    if (overlay) overlay.classList.remove('active');
                    toggleBtn.setAttribute('aria-expanded', 'false');
                }
            });

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && sidebar.classList.contains('show')) {
                    sidebar.classList.remove('show');
                    if (overlay) overlay.classList.remove('active');
                    toggleBtn.setAttribute('aria-expanded', 'false');
                    toggleBtn.focus();
                }
            });
        }

        // Auto-dismiss alerts after 5 s (staggered)
        document.querySelectorAll('.alert-custom').forEach(function (alert, index) {
            setTimeout(function () {
                if (!alert.parentNode) return;
                alert.style.transition = 'opacity 0.3s ease';
                alert.style.opacity = '0';
                setTimeout(function () { if (alert.parentNode) alert.remove(); }, 300);
            }, 5000 + index * 1000);
        });
    });
    </script>

    @stack('scripts')
</body>
</html>
