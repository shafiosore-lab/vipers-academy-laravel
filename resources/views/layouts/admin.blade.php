<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', __('Admin Dashboard - Vipers Academy'))</title>

    {{-- Favicon --}}
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    {{-- Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    {{-- Bootstrap 5.3 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
    /* ========================================
           CSS VARIABLES
           ======================================== */
    :root {
        --primary: #ea1c4d;
        --primary-dark: #c0173f;
        --secondary: #65c16e;
        --accent: #fbc761;
        --danger: #dc2626;
        --info: #0891b2;
        --dark: #1a1a1a;
        --gray-900: #333;
        --gray-600: #666;
        --gray-300: #e8e8e8;
        --bg-light: #f7f7f7;
        --white: #fff;
        --shadow-sm: 0 1px 2px rgba(0, 0, 0, 0.05);
        --shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        --shadow-lg: 0 10px 15px rgba(0, 0, 0, 0.1);
        --transition: 0.2s ease;
        --sidebar-width: 260px;
        --header-height: 60px;
    }

    /* ========================================
           BASE STYLES
           ======================================== */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Roboto', sans-serif;
        font-size: 16px;
        background: var(--white);
        color: var(--gray-900);
        overflow-x: hidden;
    }

    h1,
    h2,
    h3,
    h4,
    h5,
    h6 {
        font-weight: 700;
        line-height: 1.2;
        color: var(--gray-900);
    }

    h1 {
        font-size: 30px;
        color: var(--primary);
    }

    h2 {
        font-size: 22px;
    }

    h3 {
        font-size: 16px;
    }

    /* ========================================
           TOP HEADER
           ======================================== */
    .top-header {
        background: var(--white);
        border-bottom: 1px solid var(--gray-300);
        height: var(--header-height);
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 1040;
        box-shadow: var(--shadow-sm);
    }

    .top-header .container-fluid {
        height: 100%;
        display: flex;
        align-items: center;
        padding: 0 2rem;
    }

    /* Brand */
    .admin-brand {
        display: flex;
        align-items: center;
        gap: 12px;
        text-decoration: none;
        margin-right: 3rem;
    }

    .admin-logo {
        width: 40px;
        height: 40px;
        object-fit: contain;
        border-radius: 8px;
    }

    .admin-brand-text h5 {
        font-size: 18px;
        font-weight: 700;
        color: var(--primary);
        margin: 0;
        line-height: 1.2;
    }

    .admin-brand-text small {
        font-size: 11px;
        color: #999;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Search */
    .admin-search {
        flex: 1;
        max-width: 500px;
        margin-right: 2rem;
    }

    .admin-search-form {
        display: flex;
        border: 1px solid var(--gray-300);
        border-radius: 6px;
        overflow: hidden;
        background: #f8f9fa;
        transition: var(--transition);
    }

    .admin-search-form:focus-within {
        border-color: var(--primary);
        background: var(--white);
        box-shadow: 0 0 0 3px rgba(234, 28, 77, 0.1);
    }

    .admin-search-input {
        flex: 1;
        border: none;
        padding: 10px 16px;
        font-size: 14px;
        outline: none;
        background: transparent;
    }

    .admin-search-btn {
        background: transparent;
        border: none;
        color: #999;
        padding: 0 16px;
        cursor: pointer;
        transition: var(--transition);
    }

    .admin-search-btn:hover {
        color: var(--primary);
    }

    /* Header Actions */
    .header-actions {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-left: auto;
    }

    .header-action-btn {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: #f8f9fa;
        border: none;
        color: var(--gray-600);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        position: relative;
        transition: var(--transition);
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
        border-radius: 50%;
        width: 18px;
        height: 18px;
        font-size: 10px;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid var(--white);
    }

    .language-selector {
        background: #f8f9fa;
        border: 1px solid var(--gray-300);
        border-radius: 6px;
        color: var(--gray-600);
        font-size: 13px;
        padding: 8px 12px;
        outline: none;
        cursor: pointer;
        transition: var(--transition);
    }

    .language-selector:hover,
    .language-selector:focus {
        border-color: var(--primary);
        background: var(--white);
    }

    /* User Menu */
    .admin-user-menu {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 6px 12px;
        background: #f8f9fa;
        border-radius: 8px;
        cursor: pointer;
        transition: var(--transition);
        margin-left: 12px;
    }

    .admin-user-menu:hover {
        background: #e9ecef;
    }

    .admin-user-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--white);
        font-size: 14px;
        font-weight: 600;
    }

    .admin-user-info {
        display: flex;
        flex-direction: column;
        line-height: 1.2;
    }

    .admin-user-name {
        font-size: 13px;
        font-weight: 600;
        color: var(--gray-900);
    }

    .admin-user-role {
        font-size: 11px;
        color: #999;
    }

    /* ========================================
           SIDEBAR
           ======================================== */
    .admin-sidebar {
        background: var(--white);
        width: var(--sidebar-width);
        position: fixed;
        top: var(--header-height);
        left: 0;
        bottom: 0;
        border-right: 1px solid var(--gray-300);
        overflow-y: auto;
        z-index: 1030;
        transition: left var(--transition);
    }

    .admin-sidebar::-webkit-scrollbar {
        width: 6px;
    }

    .admin-sidebar::-webkit-scrollbar-thumb {
        background: var(--gray-300);
        border-radius: 3px;
    }

    .sidebar-nav {
        padding: 1.5rem 1rem;
    }

    .nav-section-title {
        font-size: 11px;
        font-weight: 600;
        color: #999;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 0 12px;
        margin-bottom: 8px;
        margin-top: 20px;
    }

    .nav-section-title:first-child {
        margin-top: 0;
    }

    .sidebar-nav-link {
        display: flex;
        align-items: center;
        padding: 10px 12px;
        color: var(--gray-600);
        text-decoration: none;
        border-radius: 6px;
        margin-bottom: 4px;
        font-size: 14px;
        font-weight: 500;
        transition: var(--transition);
        position: relative;
    }

    .sidebar-nav-link:hover {
        background: #f8f9fa;
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
        margin-right: 12px;
        font-size: 16px;
    }

    .sidebar-nav-badge {
        margin-left: auto;
        background: var(--danger);
        color: var(--white);
        font-size: 10px;
        font-weight: 600;
        padding: 2px 6px;
        border-radius: 10px;
        min-width: 18px;
        text-align: center;
    }

    /* ========================================
           MAIN CONTENT
           ======================================== */
    .admin-main {
        margin-left: var(--sidebar-width);
        margin-top: var(--header-height);
        padding: 2rem;
        min-height: calc(100vh - var(--header-height));
    }

    /* ========================================
           ALERTS
           ======================================== */
    .alert-custom {
        border-radius: 8px;
        padding: 1rem 1.25rem;
        border: 1px solid transparent;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .alert-custom i {
        font-size: 18px;
    }

    .alert-custom.success {
        background: #f0fdf4;
        border-color: #bbf7d0;
        color: var(--secondary);
    }

    .alert-custom.error {
        background: #fef2f2;
        border-color: #fecaca;
        color: var(--danger);
    }

    /* ========================================
           FOOTER
           ======================================== */
    .admin-footer {
        margin-top: 3rem;
        padding: 1.5rem 0;
        border-top: 1px solid var(--gray-300);
    }

    .admin-footer-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
        font-size: 13px;
        color: #999;
    }

    .admin-footer-links {
        display: flex;
        gap: 1.5rem;
    }

    .admin-footer-links a {
        color: #999;
        text-decoration: none;
        transition: var(--transition);
    }

    .admin-footer-links a:hover {
        color: var(--primary);
    }

    /* ========================================
           DROPDOWN
           ======================================== */
    .dropdown-menu {
        border-radius: 8px;
        border: 1px solid var(--gray-300);
        box-shadow: var(--shadow-lg);
        padding: 0.5rem;
    }

    .dropdown-item {
        border-radius: 6px;
        padding: 8px 12px;
        font-size: 14px;
        transition: var(--transition);
    }

    .dropdown-item:hover {
        background: #f8f9fa;
        color: var(--primary);
    }

    .dropdown-header {
        font-size: 12px;
        font-weight: 600;
        color: #999;
        padding: 8px 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Mobile Toggle */
    .mobile-sidebar-toggle {
        display: none;
        background: none;
        border: none;
        color: var(--gray-600);
        font-size: 20px;
        cursor: pointer;
        margin-right: 1rem;
    }

    /* ========================================
            ACCESSIBILITY & UX ENHANCEMENTS
            ======================================== */

    /* Screen reader only content */
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

    .sr-only-focusable:focus {
        position: static;
        width: auto;
        height: auto;
        padding: 0.5rem;
        margin: 0;
        overflow: visible;
        clip: auto;
        white-space: normal;
        border: 2px solid var(--primary);
        background: var(--white);
        color: var(--primary);
        z-index: 9999;
    }

    /* Focus management */
    .sidebar-nav-link:focus,
    .header-action-btn:focus,
    .admin-search-input:focus,
    .language-selector:focus,
    .admin-user-menu:focus {
        outline: 2px solid var(--primary);
        outline-offset: 2px;
        box-shadow: 0 0 0 4px rgba(234, 28, 77, 0.2);
    }

    /* Loading indicator */
    .loading-indicator {
        position: fixed;
        top: var(--header-height);
        left: var(--sidebar-width);
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.9);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        backdrop-filter: blur(4px);
    }

    .loading-spinner {
        width: 40px;
        height: 40px;
        border: 4px solid var(--gray-300);
        border-top: 4px solid var(--primary);
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin-bottom: 1rem;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* Breadcrumb navigation */
    .breadcrumb-nav {
        background: var(--white);
        border-bottom: 1px solid var(--gray-300);
        padding: 0.75rem 0;
        font-size: 14px;
    }

    .breadcrumb-nav .container-fluid {
        padding: 0 2rem;
    }

    .breadcrumb {
        background: transparent;
        margin: 0;
        padding: 0;
    }

    .breadcrumb-item {
        display: flex;
        align-items: center;
    }

    .breadcrumb-item + .breadcrumb-item::before {
        content: "/";
        color: var(--gray-600);
        padding: 0 0.5rem;
    }

    .breadcrumb-item.active {
        color: var(--primary);
        font-weight: 600;
    }

    /* Enhanced dropdowns */
    .dropdown-menu {
        border-radius: 8px;
        border: 1px solid var(--gray-300);
        box-shadow: var(--shadow-lg);
        padding: 0.5rem;
        animation: fadeInDown 0.2s ease-out;
    }

    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* ========================================
            RESPONSIVE DESIGN
            ======================================== */
    @media (max-width: 992px) {
        .admin-sidebar {
            left: calc(-1 * var(--sidebar-width));
        }

        .admin-sidebar.show {
            left: 0;
            box-shadow: var(--shadow-lg);
        }

        .admin-main {
            margin-left: 0;
        }

        .mobile-sidebar-toggle {
            display: block;
        }

        .admin-search {
            max-width: 300px;
        }

        .loading-indicator {
            left: 0;
        }
    }

    @media (max-width: 768px) {
        .top-header .container-fluid {
            padding: 0 1rem;
        }

        .admin-brand {
            margin-right: 1rem;
        }

        .admin-brand-text {
            display: none;
        }

        .admin-search {
            display: none;
        }

        .admin-main {
            padding: 1rem;
        }

        .admin-user-info {
            display: none;
        }

        .header-actions {
            gap: 8px;
        }

        .breadcrumb-nav .container-fluid {
            padding: 0 1rem;
        }

        .language-selector-wrapper {
            display: none;
        }
    }

    @media (max-width: 480px) {
        .top-header .container-fluid {
            padding: 0 0.5rem;
        }

        .admin-main {
            padding: 0.5rem;
        }

        .header-actions .dropdown {
            display: none;
        }

        .header-actions .header-action-btn:not(.notification-btn) {
            display: none;
        }

        .alert-custom {
            margin-bottom: 1rem;
            padding: 0.75rem;
        }
    }

    /* High contrast mode support */
    @media (prefers-contrast: high) {
        :root {
            --primary: #0000ff;
            --secondary: #00ff00;
            --gray-900: #000;
            --white: #fff;
        }

        .sidebar-nav-link,
        .header-action-btn {
            border: 1px solid;
        }
    }

    /* Reduced motion support */
    @media (prefers-reduced-motion: reduce) {
        *,
        *::before,
        *::after {
            animation-duration: 0.01ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: 0.01ms !important;
        }

        .loading-spinner {
            animation: none;
        }
    }
    </style>

    @stack('styles')
</head>

<body>
    {{-- Top Header --}}
    <header class="top-header" role="banner">
        <div class="container-fluid">
            {{-- Mobile Sidebar Toggle --}}
            <button class="mobile-sidebar-toggle" id="mobileSidebarToggle"
                    aria-label="{{ __('Toggle navigation menu') }}"
                    aria-expanded="false"
                    aria-controls="adminSidebar">
                <i class="fas fa-bars" aria-hidden="true"></i>
            </button>

            {{-- Brand - Role-based --}}
            @php
                $dashboardRoute = route('admin.dashboard');
                $user = auth()->user();

                if ($user->hasAnyRole(['super-admin', 'admin-operations', 'operations-admin'])) {
                    $dashboardRoute = route('admin.dashboard');
                } elseif ($user->hasAnyRole(['coach', 'head-coach', 'assistant-coach'])) {
                    $dashboardRoute = route('coach.dashboard');
                } elseif ($user->hasRole('team-manager')) {
                    $dashboardRoute = route('manager.dashboard');
                } elseif ($user->hasRole('finance-officer')) {
                    $dashboardRoute = route('finance.dashboard');
                } elseif ($user->hasRole('media-officer')) {
                    $dashboardRoute = route('media.dashboard');
                } elseif ($user->hasRole('safeguarding-officer')) {
                    $dashboardRoute = route('welfare.dashboard');
                } elseif ($user->hasRole('player')) {
                    $dashboardRoute = route('player.portal.dashboard');
                } elseif ($user->hasAnyRole(['parent', 'partner'])) {
                    $dashboardRoute = route('partner.dashboard');
                }
            @endphp
            <a href="{{ $dashboardRoute }}" class="admin-brand" aria-label="{{ __('Go to dashboard') }}">
                <img src="{{ asset('assets/img/logo/vps.jpeg') }}"
                     alt="{{ __('Vipers Academy Logo') }}"
                     class="admin-logo"
                     loading="lazy">
                <div class="admin-brand-text">
                    <h1 class="sr-only">{{ __('Vipers Academy Admin Panel') }}</h1>
                    <h5>{{ __('Vipers Academy') }}</h5>
                    <small>
                        @if(auth()->user()->hasRole('super-admin')){{ __('Super Admin') }}
                        @elseif(auth()->user()->hasRole('operations-admin')){{ __('Operations Admin') }}
                        @elseif(auth()->user()->hasRole('admin-operations')){{ __('Admin Operations') }}
                        @elseif(auth()->user()->hasRole('marketing-admin')){{ __('Marketing Admin') }}
                        @elseif(auth()->user()->hasRole('scouting-admin')){{ __('Scouting Admin') }}
                        @elseif(auth()->user()->hasRole('coaching-admin')){{ __('Coaching Admin') }}
                        @elseif(auth()->user()->hasRole('finance-admin')){{ __('Finance Admin') }}
                        @else{{ __('Admin Panel') }}
                        @endif
                    </small>
                </div>
            </a>

            {{-- Search --}}
            <div class="admin-search">
                <form class="admin-search-form" action="{{ route('search') }}" method="GET" role="search">
                    <label for="global-search" class="sr-only">{{ __('Search') }}</label>
                    <input type="search"
                           id="global-search"
                           name="q"
                           class="admin-search-input"
                           placeholder="{{ __('Search players, programs...') }}"
                           aria-label="{{ __('Search the system') }}"
                           autocomplete="off">
                    <button type="submit" class="admin-search-btn" aria-label="{{ __('Submit search') }}">
                        <i class="fas fa-search" aria-hidden="true"></i>
                    </button>
                </form>
            </div>

            {{-- Header Actions --}}
            <nav class="header-actions" role="navigation" aria-label="{{ __('Header actions') }}">
                {{-- Language Selector --}}
                <div class="language-selector-wrapper">
                    <label for="language-selector" class="sr-only">{{ __('Select language') }}</label>
                    <select id="language-selector" class="language-selector" aria-label="{{ __('Select language') }}">
                        <option value="en" {{ app()->getLocale() === 'en' ? 'selected' : '' }}>🇬🇧 EN</option>
                        <option value="es" {{ app()->getLocale() === 'es' ? 'selected' : '' }}>🇪🇸 ES</option>
                        <option value="fr" {{ app()->getLocale() === 'fr' ? 'selected' : '' }}>🇫🇷 FR</option>
                        <option value="sw" {{ app()->getLocale() === 'sw' ? 'selected' : '' }}>🇰🇪 SW</option>
                    </select>
                </div>

                {{-- Notifications --}}
                <div class="dropdown">
                    <button class="header-action-btn notification-btn"
                            type="button"
                            data-bs-toggle="dropdown"
                            aria-label="{{ __('Notifications') }}"
                            aria-haspopup="true"
                            aria-expanded="false">
                        <i class="fas fa-bell" aria-hidden="true"></i>
                        @php
                        $pendingPlayers = \App\Models\Player::where('registration_status', 'Pending')->count();
                        $pendingPartners = \App\Models\User::where('user_type', 'partner')->where('approval_status', 'pending')->count();
                        $totalNotifications = $pendingPlayers + $pendingPartners;
                        @endphp
                        @if($totalNotifications > 0)
                        <span class="notification-badge" aria-label="{{ $totalNotifications }} notifications">{{ $totalNotifications }}</span>
                        @endif
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" style="width: 320px;" role="menu">
                        <li role="none">
                            <h6 class="dropdown-header" role="none">{{ __('Notifications') }}</h6>
                        </li>
                        @if($pendingPlayers > 0)
                        <li role="none">
                            <a class="dropdown-item" href="{{ route('admin.players.index') }}" role="menuitem">
                                <i class="fas fa-user-plus text-success me-2" aria-hidden="true"></i>
                                {{ $pendingPlayers }} {{ __('pending registrations') }}
                            </a>
                        </li>
                        @endif
                        @if($pendingPartners > 0)
                        <li role="none">
                            <a class="dropdown-item" href="{{ route('admin.partners.index') }}" role="menuitem">
                                <i class="fas fa-handshake text-primary me-2" aria-hidden="true"></i>
                                {{ $pendingPartners }} {{ __('partnership applications') }}
                            </a>
                        </li>
                        @endif
                        @if($totalNotifications === 0)
                        <li class="dropdown-item text-center text-muted" role="menuitem">{{ __('No notifications') }}</li>
                        @endif
                    </ul>
                </div>

                {{-- Quick Actions --}}
                <div class="dropdown">
                    <button class="header-action-btn"
                            type="button"
                            data-bs-toggle="dropdown"
                            aria-label="{{ __('Quick actions') }}"
                            aria-haspopup="true"
                            aria-expanded="false">
                        <i class="fas fa-plus" aria-hidden="true"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" role="menu">
                        <li role="none">
                            <h6 class="dropdown-header" role="none">{{ __('Quick Actions') }}</h6>
                        </li>
                        <li role="none">
                            <a class="dropdown-item" href="{{ route('admin.players.create') }}" role="menuitem">
                                <i class="fas fa-user-plus me-2" aria-hidden="true"></i>{{ __('Add Player') }}
                            </a>
                        </li>
                        <li role="none">
                            <a class="dropdown-item" href="{{ route('admin.programs.create') }}" role="menuitem">
                                <i class="fas fa-football-ball me-2" aria-hidden="true"></i>{{ __('Add Program') }}
                            </a>
                        </li>
                        <li role="none">
                            <a class="dropdown-item" href="{{ route('admin.staff.create') }}" role="menuitem">
                                <i class="fas fa-users-cog me-2" aria-hidden="true"></i>{{ __('Add Staff') }}
                            </a>
                        </li>
                    </ul>
                </div>

                {{-- User Menu --}}
                <div class="dropdown">
                    <button class="admin-user-menu"
                            data-bs-toggle="dropdown"
                            role="button"
                            aria-label="{{ __('User menu for') }} {{ Auth::user()->name }}"
                            aria-haspopup="true"
                            aria-expanded="false">
                        <div class="admin-user-avatar" aria-hidden="true">{{ substr(Auth::user()->name, 0, 1) }}</div>
                        <div class="admin-user-info">
                            <div class="admin-user-name">{{ Auth::user()->name }}</div>
                            <div class="admin-user-role">
                                @if(auth()->user()->hasRole('super-admin')){{ __('Super Admin') }}
                                @elseif(auth()->user()->hasRole('operations-admin')){{ __('Operations Admin') }}
                                @elseif(auth()->user()->hasRole('admin-operations')){{ __('Admin Operations') }}
                                @elseif(auth()->user()->hasRole('marketing-admin')){{ __('Marketing Admin') }}
                                @elseif(auth()->user()->hasRole('scouting-admin')){{ __('Scouting Admin') }}
                                @elseif(auth()->user()->hasRole('coaching-admin')){{ __('Coaching Admin') }}
                                @elseif(auth()->user()->hasRole('finance-admin')){{ __('Finance Admin') }}
                                @else{{ __('Administrator') }}
                                @endif
                            </div>
                        </div>
                        <i class="fas fa-chevron-down" aria-hidden="true" style="font-size: 12px; color: #999;"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" role="menu">
                        <li role="none">
                            <a class="dropdown-item" href="{{ route('profile.edit') }}" role="menuitem">
                                <i class="fas fa-user me-2" aria-hidden="true"></i>{{ __('My Profile') }}
                            </a>
                        </li>
                        <li role="none">
                            <a class="dropdown-item" href="{{ route('players.index') }}" target="_blank" role="menuitem">
                                <i class="fas fa-external-link-alt me-2" aria-hidden="true"></i>{{ __('View Website') }}
                            </a>
                        </li>
                        <li role="none">
                            <hr class="dropdown-divider">
                        </li>
                        <li role="none">
                            <form method="POST" action="{{ route('logout') }}" role="none">
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

    {{-- Sidebar --}}
    <aside class="admin-sidebar" id="adminSidebar" role="navigation" aria-label="{{ __('Main navigation') }}">
        <nav class="sidebar-nav">
            {{-- Main Menu --}}
            <div class="nav-section-title" role="heading" aria-level="2">{{ __('Main Menu') }}</div>
            <a href="{{ route('admin.dashboard') }}"
                class="sidebar-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                aria-current="{{ request()->routeIs('admin.dashboard') ? 'page' : 'false' }}">
                <i class="fas fa-th-large" aria-hidden="true"></i>
                <span>{{ __('Dashboard') }}</span>
            </a>

            {{-- Player Management --}}
            <div class="nav-section-title" role="heading" aria-level="2">{{ __('Player Management') }}</div>
            <a href="{{ route('admin.players.index') }}"
                class="sidebar-nav-link {{ request()->routeIs('admin.players.*') ? 'active' : '' }}"
                aria-current="{{ request()->routeIs('admin.players.*') ? 'page' : 'false' }}">
                <i class="fas fa-users" aria-hidden="true"></i>
                <span>{{ __('Players') }}</span>
                @php
                $pendingPlayersCount = \App\Models\Player::where('registration_status', 'Pending')->count();
                @endphp
                @if($pendingPlayersCount > 0)
                <span class="sidebar-nav-badge" aria-label="{{ $pendingPlayersCount }} pending players">{{ $pendingPlayersCount }}</span>
                @endif
            </a>
            <a href="{{ route('admin.attendance.index') }}"
                class="sidebar-nav-link {{ request()->routeIs('admin.attendance.*') ? 'active' : '' }}"
                aria-current="{{ request()->routeIs('admin.attendance.*') ? 'page' : 'false' }}">
                <i class="fas fa-calendar-check" aria-hidden="true"></i>
                <span>{{ __('Attendance') }}</span>
            </a>
            <a href="{{ route('admin.training-sessions.index') }}"
                class="sidebar-nav-link {{ request()->routeIs('admin.training-sessions.*') ? 'active' : '' }}"
                aria-current="{{ request()->routeIs('admin.training-sessions.*') ? 'page' : 'false' }}">
                <i class="fas fa-stopwatch" aria-hidden="true"></i>
                <span>{{ __('Training Sessions') }}</span>
            </a>
            <a href="{{ route('admin.game-statistics.index') }}"
                class="sidebar-nav-link {{ request()->routeIs('admin.game-statistics.*') ? 'active' : '' }}"
                aria-current="{{ request()->routeIs('admin.game-statistics.*') ? 'page' : 'false' }}">
                <i class="fas fa-chart-bar" aria-hidden="true"></i>
                <span>{{ __('Game Statistics') }}</span>
            </a>

            {{-- Competition Management --}}
            <div class="nav-section-title" role="heading" aria-level="2">{{ __('Competition') }}</div>
            <a href="{{ route('admin.matches.index') }}"
                class="sidebar-nav-link {{ request()->routeIs('admin.matches.*') ? 'active' : '' }}"
                aria-current="{{ request()->routeIs('admin.matches.*') ? 'page' : 'false' }}">
                <i class="fas fa-futbol" aria-hidden="true"></i>
                <span>{{ __('Matches') }}</span>
            </a>
            <a href="{{ route('admin.standings.index') }}"
                class="sidebar-nav-link {{ request()->routeIs('admin.standings.*') ? 'active' : '' }}"
                aria-current="{{ request()->routeIs('admin.standings.*') ? 'page' : 'false' }}">
                <i class="fas fa-trophy" aria-hidden="true"></i>
                <span>{{ __('Standings') }}</span>
            </a>

            {{-- Academy Management --}}
            <div class="nav-section-title" role="heading" aria-level="2">{{ __('Academy') }}</div>
            <a href="{{ route('admin.programs.index') }}"
                class="sidebar-nav-link {{ request()->routeIs('admin.programs.*') ? 'active' : '' }}"
                aria-current="{{ request()->routeIs('admin.programs.*') ? 'page' : 'false' }}">
                <i class="fas fa-football-ball" aria-hidden="true"></i>
                <span>{{ __('Programs') }}</span>
            </a>
            <a href="{{ route('admin.staff.index') }}"
                class="sidebar-nav-link {{ request()->routeIs('admin.staff.*') ? 'active' : '' }}"
                aria-current="{{ request()->routeIs('admin.staff.*') ? 'page' : 'false' }}">
                <i class="fas fa-users-cog" aria-hidden="true"></i>
                <span>{{ __('Staff') }}</span>
            </a>
            <a href="{{ route('admin.partners.index') }}"
                class="sidebar-nav-link {{ request()->routeIs('admin.partners.*') ? 'active' : '' }}"
                aria-current="{{ request()->routeIs('admin.partners.*') ? 'page' : 'false' }}">
                <i class="fas fa-handshake" aria-hidden="true"></i>
                <span>{{ __('Partners') }}</span>
                @php
                $pendingPartnersCount = \App\Models\User::where('user_type', 'partner')->where('approval_status', 'pending')->count();
                @endphp
                @if($pendingPartnersCount > 0)
                <span class="sidebar-nav-badge" aria-label="{{ $pendingPartnersCount }} pending partners">{{ $pendingPartnersCount }}</span>
                @endif
            </a>

            {{-- Content Management --}}
            <div class="nav-section-title" role="heading" aria-level="2">{{ __('Content') }}</div>
            <a href="{{ route('admin.blog.index') }}"
                class="sidebar-nav-link {{ request()->routeIs('admin.blog.*') ? 'active' : '' }}"
                aria-current="{{ request()->routeIs('admin.blog.*') ? 'page' : 'false' }}">
                <i class="fas fa-newspaper" aria-hidden="true"></i>
                <span>{{ __('Blog') }}</span>
            </a>
            <a href="{{ route('admin.gallery.index') }}"
                class="sidebar-nav-link {{ request()->routeIs('admin.gallery.*') ? 'active' : '' }}"
                aria-current="{{ request()->routeIs('admin.gallery.*') ? 'page' : 'false' }}">
                <i class="fas fa-images" aria-hidden="true"></i>
                <span>{{ __('Gallery') }}</span>
            </a>
            <a href="{{ route('admin.documents.index') }}"
                class="sidebar-nav-link {{ request()->routeIs('admin.documents.*') ? 'active' : '' }}"
                aria-current="{{ request()->routeIs('admin.documents.*') ? 'page' : 'false' }}">
                <i class="fas fa-file-alt" aria-hidden="true"></i>
                <span>{{ __('Documents') }}</span>
            </a>

            {{-- Analytics & Reports --}}
            <div class="nav-section-title" role="heading" aria-level="2">{{ __('Analytics') }}</div>
            <a href="{{ route('admin.performance.overview') }}"
                class="sidebar-nav-link {{ request()->routeIs('admin.performance.overview') ? 'active' : '' }}"
                aria-current="{{ request()->routeIs('admin.performance.overview') ? 'page' : 'false' }}">
                <i class="fas fa-chart-line" aria-hidden="true"></i>
                <span>{{ __('Overview') }}</span>
            </a>
            <a href="{{ route('admin.compliance.report') }}"
                class="sidebar-nav-link {{ request()->routeIs('admin.compliance.report') ? 'active' : '' }}"
                aria-current="{{ request()->routeIs('admin.compliance.report') ? 'page' : 'false' }}">
                <i class="fas fa-shield-alt" aria-hidden="true"></i>
                <span>{{ __('Compliance') }}</span>
            </a>
        </nav>
    </aside>

    {{-- Main Content --}}
    <main class="admin-main">
        {{-- Success Alert --}}
        @if(session('success'))
        <div class="alert-custom success">
            <i class="fas fa-check-circle"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close ms-auto" onclick="this.parentElement.remove()"
                aria-label="{{ __('Close') }}"></button>
        </div>
        @endif

        {{-- Error Alert --}}
        @if(session('error'))
        <div class="alert-custom error">
            <i class="fas fa-exclamation-circle"></i>
            <div>{{ session('error') }}</div>
            <button type="button" class="btn-close ms-auto" onclick="this.parentElement.remove()"
                aria-label="{{ __('Close') }}"></button>
        </div>
        @endif

        {{-- Validation Errors --}}
        @if($errors->any())
        <div class="alert-custom error">
            <i class="fas fa-exclamation-triangle"></i>
            <div>
                <strong>{{ __('Please fix the following errors:') }}</strong>
                <ul class="mb-0 mt-2" style="padding-left: 1.25rem;">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            <button type="button" class="btn-close ms-auto" onclick="this.parentElement.remove()"
                aria-label="{{ __('Close') }}"></button>
        </div>
        @endif

        {{-- Page Content --}}
        @yield('content')

        {{-- Footer --}}
        <footer class="admin-footer">
            <div class="container-fluid">
                <div class="admin-footer-content">
                    <div>&copy; {{ date('Y') }} {{ __('Vipers Academy') }}. {{ __('All rights reserved.') }}</div>
                    <div class="admin-footer-links">
                        <a href="#">{{ __('Privacy') }}</a>
                        <a href="#">{{ __('Terms') }}</a>
                        <a href="#">{{ __('Support') }}</a>
                    </div>
                </div>
            </div>
        </footer>
    </main>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    'use strict';

    // Enhanced accessibility and UX features
    document.addEventListener('DOMContentLoaded', function() {
        // Mobile sidebar toggle with accessibility
        const mobileSidebarToggle = document.getElementById('mobileSidebarToggle');
        const adminSidebar = document.getElementById('adminSidebar');

        if (mobileSidebarToggle && adminSidebar) {
            mobileSidebarToggle.addEventListener('click', function() {
                const isExpanded = adminSidebar.classList.contains('show');
                adminSidebar.classList.toggle('show');
                this.setAttribute('aria-expanded', !isExpanded);

                // Focus management
                if (!isExpanded) {
                    const firstLink = adminSidebar.querySelector('.sidebar-nav-link');
                    if (firstLink) firstLink.focus();
                }
            });

            // Close sidebar when clicking outside or pressing Escape
            document.addEventListener('click', (e) => {
                if (!adminSidebar.contains(e.target) && !mobileSidebarToggle.contains(e.target)) {
                    adminSidebar.classList.remove('show');
                    mobileSidebarToggle.setAttribute('aria-expanded', 'false');
                }
            });

            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && adminSidebar.classList.contains('show')) {
                    adminSidebar.classList.remove('show');
                    mobileSidebarToggle.setAttribute('aria-expanded', 'false');
                    mobileSidebarToggle.focus();
                }
            });
        }

        // Auto-hide alerts after 5 seconds with accessibility
        const alerts = document.querySelectorAll('.alert-custom');
        alerts.forEach((alert, index) => {
            setTimeout(() => {
                if (alert && alert.parentNode) {
                    alert.style.transition = 'opacity 0.3s ease';
                    alert.style.opacity = '0';
                    setTimeout(() => {
                        if (alert.parentNode) alert.remove();
                    }, 300);
                }
            }, 5000 + (index * 1000)); // Stagger removal for multiple alerts
        });

        // Language selector with proper form submission
        const languageSelector = document.getElementById('language-selector');
        if (languageSelector) {
            languageSelector.addEventListener('change', function() {
                // Create and submit a form to change language
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '/language'; // You'll need to create this route
                form.style.display = 'none';

                const csrfToken = document.querySelector('meta[name="csrf-token"]');
                if (csrfToken) {
                    const tokenInput = document.createElement('input');
                    tokenInput.type = 'hidden';
                    tokenInput.name = '_token';
                    tokenInput.value = csrfToken.content;
                    form.appendChild(tokenInput);
                }

                const langInput = document.createElement('input');
                langInput.type = 'hidden';
                langInput.name = 'language';
                langInput.value = this.value;
                form.appendChild(langInput);

                document.body.appendChild(form);
                form.submit();
            });
        }

        // Enhanced search functionality
        const searchInput = document.getElementById('global-search');
        if (searchInput) {
            let searchTimeout;
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    if (this.value.length > 2) {
                        // Implement live search suggestions if needed
                        console.log('Searching for:', this.value);
                    }
                }, 300);
            });

            // Clear search on Escape
            searchInput.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    this.value = '';
                    this.blur();
                }
            });
        }

        // Loading indicator functions
        window.showLoading = function(message = '{{ __("Loading...") }}') {
            const indicator = document.getElementById('loading-indicator');
            if (indicator) {
                const messageSpan = indicator.querySelector('span');
                if (messageSpan) messageSpan.textContent = message;
                indicator.style.display = 'flex';
            }
        };

        window.hideLoading = function() {
            const indicator = document.getElementById('loading-indicator');
            if (indicator) {
                indicator.style.display = 'none';
            }
        };

        // Keyboard navigation for dropdowns
        const dropdowns = document.querySelectorAll('.dropdown');
        dropdowns.forEach(dropdown => {
            const toggle = dropdown.querySelector('[data-bs-toggle="dropdown"]');
            if (toggle) {
                toggle.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        this.click();
                    }
                });
            }
        });

        // Table sorting accessibility
        const sortableHeaders = document.querySelectorAll('th[aria-sort]');
        sortableHeaders.forEach(header => {
            header.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    this.click();
                }
            });
        });

        // Form validation enhancement
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            form.addEventListener('submit', function(e) {
                const submitBtn = form.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>{{ __("Processing...") }}';
                }
            });
        });

        // Print functionality for reports
        window.printPage = function() {
            window.print();
        };

        // Dark mode toggle (if implemented)
        window.toggleDarkMode = function() {
            document.documentElement.classList.toggle('dark-mode');
            localStorage.setItem('darkMode', document.documentElement.classList.contains('dark-mode'));
        };

        // Initialize dark mode from localStorage
        if (localStorage.getItem('darkMode') === 'true') {
            document.documentElement.classList.add('dark-mode');
        }
    });

    // Global error handler
    window.addEventListener('error', function(e) {
        console.error('JavaScript error:', e.error);
        // You could send this to your error tracking service
    });

    // Service worker for offline functionality (if needed)
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('/sw.js')
            .then(registration => console.log('SW registered'))
            .catch(error => console.log('SW registration failed'));
    }
    </script>

    @stack('scripts')
</body>

</html>
