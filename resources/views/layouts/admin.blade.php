<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', __('Admin Dashboard - Vipers Academy'))</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Bootstrap 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        :root {
            --primary-red: #ea1c4d;
            --primary-dark: #c0173f;
            --secondary-color: #65c16e;
            --success-color: #059669;
            --warning-color: #fbc761;
            --danger-color: #dc2626;
            --info-color: #0891b2;
            --dark-color: #1a1a1a;
            --light-color: #f7f7f7;
            --border-color: #e8e8e8;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            /* Override Bootstrap colors */
            --bs-warning: #fbc761;
            --bs-warning-rgb: 251, 199, 97;
            --bs-success: #65c16e;
            --bs-success-rgb: 101, 193, 110;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto', sans-serif;
            font-size: 16px;
            background-color: #FFFFFF;
            color: #333333;
            overflow-x: hidden;
        }

        /* Typography Rules */
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Roboto', sans-serif;
            font-weight: 700;
            line-height: 1.2;
            color: #333333;
        }

        h1 { font-size: 30px; color: #ea1c4d; }
        h2 { font-size: 22px; }
        h3 { font-size: 16px; }

        /* ==================== TOP HEADER BAR ==================== */
        .top-header {
            background: #fff;
            border-bottom: 1px solid var(--border-color);
            height: 60px;
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
            color: var(--primary-red);
            margin: 0;
            line-height: 1.2;
        }

        .admin-brand-text small {
            font-size: 11px;
            color: #999;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Search Bar */
        .admin-search {
            flex: 1;
            max-width: 500px;
            margin-right: 2rem;
        }

        .admin-search-form {
            display: flex;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            overflow: hidden;
            background: #f8f9fa;
            transition: all 0.3s;
        }

        .admin-search-form:focus-within {
            border-color: var(--primary-red);
            background: white;
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
            transition: color 0.2s;
        }

        .admin-search-btn:hover {
            color: var(--primary-red);
        }

        /* Top Header Actions */
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
            color: #666;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            position: relative;
            transition: all 0.2s;
        }

        .header-action-btn:hover {
            background: var(--primary-red);
            color: white;
        }

        .notification-badge {
            position: absolute;
            top: -2px;
            right: -2px;
            background: #dc2626;
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 10px;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid white;
        }

        .language-selector {
            background: #f8f9fa;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            color: #666;
            font-size: 13px;
            padding: 8px 12px;
            outline: none;
            cursor: pointer;
            transition: all 0.2s;
        }

        .language-selector:hover,
        .language-selector:focus {
            border-color: var(--primary-red);
            background: white;
        }

        .admin-user-menu {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 6px 12px;
            background: #f8f9fa;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
            margin-left: 12px;
        }

        .admin-user-menu:hover {
            background: #e9ecef;
        }

        .admin-user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-red), var(--secondary-color));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
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
            color: #333;
        }

        .admin-user-role {
            font-size: 11px;
            color: #999;
        }

        /* ==================== SIDEBAR ==================== */
        .admin-sidebar {
            background: white;
            width: 260px;
            position: fixed;
            top: 60px;
            left: 0;
            bottom: 0;
            border-right: 1px solid var(--border-color);
            overflow-y: auto;
            z-index: 1030;
            transition: all 0.3s;
        }

        .admin-sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .admin-sidebar::-webkit-scrollbar-thumb {
            background: #e8e8e8;
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
            color: #666;
            text-decoration: none;
            border-radius: 6px;
            margin-bottom: 4px;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s;
            position: relative;
        }

        .sidebar-nav-link:hover {
            background: #f8f9fa;
            color: var(--primary-red);
        }

        .sidebar-nav-link.active {
            background: #fff5f0;
            color: var(--primary-red);
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
            background: var(--primary-red);
            border-radius: 0 3px 3px 0;
        }

        .sidebar-nav-link i {
            width: 20px;
            margin-right: 12px;
            font-size: 16px;
        }

        .sidebar-nav-badge {
            margin-left: auto;
            background: #dc2626;
            color: white;
            font-size: 10px;
            font-weight: 600;
            padding: 2px 6px;
            border-radius: 10px;
            min-width: 18px;
            text-align: center;
        }

        /* ==================== MAIN CONTENT ==================== */
        .admin-main {
            margin-left: 260px;
            margin-top: 60px;
            padding: 2rem;
            min-height: calc(100vh - 60px);
        }

        /* Page Header */
        .page-header {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: var(--shadow-sm);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .page-title {
            font-size: 24px;
            font-weight: 700;
            color: #1a1a1a;
            margin: 0;
        }

        .page-breadcrumb {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: #999;
            margin-top: 4px;
        }

        .page-breadcrumb a {
            color: #999;
            text-decoration: none;
        }

        .page-breadcrumb a:hover {
            color: var(--primary-orange);
        }

        /* Stats Cards */
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: var(--shadow-sm);
            transition: all 0.3s;
            border: 1px solid var(--border-color);
        }

        .stat-card:hover {
            box-shadow: var(--shadow);
            transform: translateY(-2px);
        }

        .stat-card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
        }

        .stat-card-icon {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .stat-card-icon.primary { background: #fff5f0; color: var(--primary-orange); }
        .stat-card-icon.success { background: #f0fdf4; color: #65c16e; }
        .stat-card-icon.warning { background: #fffbeb; color: #fbc761; }
        .stat-card-icon.info { background: #f0f9ff; color: #0891b2; }

        .stat-card-value {
            font-size: 28px;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 4px;
        }

        .stat-card-label {
            font-size: 13px;
            color: #999;
            font-weight: 500;
        }

        .stat-card-trend {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 12px;
            font-weight: 600;
            padding: 4px 8px;
            border-radius: 6px;
            margin-top: 8px;
        }

        .stat-card-trend.up {
            background: #f0fdf4;
            color: #059669;
        }

        .stat-card-trend.down {
            background: #fef2f2;
            color: #dc2626;
        }

        /* Content Card */
        .content-card {
            background: white;
            border-radius: 12px;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border-color);
            overflow: hidden;
        }

        .content-card-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .content-card-title {
            font-size: 16px;
            font-weight: 600;
            color: #1a1a1a;
            margin: 0;
        }

        .content-card-body {
            padding: 1.5rem;
        }

        /* Buttons */
        .btn-alibaba {
            padding: 10px 20px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
        }

        .btn-alibaba-primary {
            background: var(--primary-orange);
            color: white;
        }

        .btn-alibaba-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
            box-shadow: var(--shadow);
        }

        .btn-alibaba-outline {
            background: white;
            color: var(--primary-orange);
            border: 1px solid var(--primary-orange);
        }

        .btn-alibaba-outline:hover {
            background: var(--primary-orange);
            color: white;
        }

        /* Alerts */
        .alert-alibaba {
            border-radius: 8px;
            padding: 1rem 1.25rem;
            border: 1px solid transparent;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .alert-alibaba i {
            font-size: 18px;
        }

        .alert-alibaba.success {
            background: #f0fdf4;
            border-color: #bbf7d0;
            color: #059669;
        }

        .alert-alibaba.error {
            background: #fef2f2;
            border-color: #fecaca;
            color: #dc2626;
        }

        .alert-alibaba.info {
            background: #f0f9ff;
            border-color: #bae6fd;
            color: #0891b2;
        }

        /* Footer */
        .admin-footer {
            margin-top: 3rem;
            padding: 1.5rem 0;
            border-top: 1px solid var(--border-color);
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
        }

        .admin-footer-links a:hover {
            color: var(--primary-orange);
        }

        /* Mobile Toggle */
        .mobile-sidebar-toggle {
            display: none;
            background: none;
            border: none;
            color: #666;
            font-size: 20px;
            cursor: pointer;
            margin-right: 1rem;
        }

        /* ==================== RESPONSIVE ==================== */
        @media (max-width: 992px) {
            .admin-sidebar {
                left: -260px;
            }

            .admin-sidebar.show {
                left: 0;
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

            .page-header {
                padding: 1rem;
            }

            .page-title {
                font-size: 20px;
            }

            .admin-user-info {
                display: none;
            }

            .header-actions {
                gap: 8px;
            }
        }

        /* Dropdown Menu */
        .dropdown-menu {
            border-radius: 8px;
            border: 1px solid var(--border-color);
            box-shadow: var(--shadow-lg);
            padding: 0.5rem;
        }

        .dropdown-item {
            border-radius: 6px;
            padding: 8px 12px;
            font-size: 14px;
            transition: all 0.2s;
        }

        .dropdown-item:hover {
            background: #f8f9fa;
            color: var(--primary-orange);
        }

        .dropdown-header {
            font-size: 12px;
            font-weight: 600;
            color: #999;
            padding: 8px 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
    </style>

    @stack('styles')
</head>
<body>
    <!-- Top Header -->
    <header class="top-header">
        <div class="container-fluid">
            <!-- Mobile Toggle -->
            <button class="mobile-sidebar-toggle" id="mobileSidebarToggle">
                <i class="fas fa-bars"></i>
            </button>

            <!-- Brand -->
            <a href="{{ route('admin.dashboard') }}" class="admin-brand">
                <img src="{{ asset('assets/img/logo/vps.png') }}" alt="Vipers Academy Logo" class="admin-logo">
                <div class="admin-brand-text">
                    <h5>Vipers Academy</h5>
                    <small>{{ __('Admin Panel') }}</small>
                </div>
            </a>

            <!-- Search -->
            <div class="admin-search">
                <form class="admin-search-form" action="{{ route('search') }}" method="GET">
                    <input type="search" name="q" class="admin-search-input" placeholder="{{ __('Search players, programs, news...') }}" required>
                    <button type="submit" class="admin-search-btn">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>

            <!-- Header Actions -->
            <div class="header-actions">
                <!-- Language Selector -->
                <select class="language-selector">
                    <option value="en" {{ app()->getLocale() === 'en' ? 'selected' : '' }}>🇬🇧 EN</option>
                    <option value="es" {{ app()->getLocale() === 'es' ? 'selected' : '' }}>🇪🇸 ES</option>
                    <option value="fr" {{ app()->getLocale() === 'fr' ? 'selected' : '' }}>🇫🇷 FR</option>
                    <option value="sw" {{ app()->getLocale() === 'sw' ? 'selected' : '' }}>🇰🇪 SW</option>
                </select>

                <!-- Notifications -->
                <div class="dropdown">
                    <button class="header-action-btn" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-bell"></i>
                        @php
                            $totalNotifications = \App\Models\Player::where('registration_status', 'Pending')->count() +
                                                 \App\Models\User::where('user_type', 'partner')->where('status', 'pending')->count();
                        @endphp
                        @if($totalNotifications > 0)
                            <span class="notification-badge">{{ $totalNotifications }}</span>
                        @endif
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" style="width: 320px;">
                        <li><h6 class="dropdown-header">{{ __('Notifications') }}</h6></li>
                        @if(\App\Models\Player::where('registration_status', 'Pending')->count() > 0)
                            <li>
                                <a class="dropdown-item" href="{{ route('admin.players.index') }}">
                                    <i class="fas fa-user-plus text-success me-2"></i>
                                    <span class="small">{{ \App\Models\Player::where('registration_status', 'Pending')->count() }} {{ __('pending player registrations') }}</span>
                                </a>
                            </li>
                        @endif
                        @if(\App\Models\User::where('user_type', 'partner')->where('status', 'pending')->count() > 0)
                            <li>
                                <a class="dropdown-item" href="{{ route('admin.partners.index') }}">
                                    <i class="fas fa-handshake text-primary me-2"></i>
                                    <span class="small">{{ \App\Models\User::where('user_type', 'partner')->where('status', 'pending')->count() }} {{ __('partnership applications') }}</span>
                                </a>
                            </li>
                        @endif
                        @if($totalNotifications === 0)
                            <li class="dropdown-item text-center text-muted small">{{ __('No new notifications') }}</li>
                        @endif
                    </ul>
                </div>

                <!-- Image Upload -->
                <button class="header-action-btn" title="{{ __('Upload Images') }}" onclick="window.location.href='{{ route('admin.image-upload') }}'">
                    <i class="fas fa-images"></i>
                </button>

                <!-- Help -->
                <button class="header-action-btn" title="{{ __('Help') }}">
                    <i class="fas fa-question-circle"></i>
                </button>

                <!-- User Menu -->
                <div class="dropdown">
                    <div class="admin-user-menu" data-bs-toggle="dropdown">
                        <div class="admin-user-avatar">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <div class="admin-user-info">
                            <div class="admin-user-name">{{ Auth::user()->name }}</div>
                            <div class="admin-user-role">{{ __('Administrator') }}</div>
                        </div>
                        <i class="fas fa-chevron-down" style="font-size: 12px; color: #999;"></i>
                    </div>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('profile.edit') }}">
                            <i class="fas fa-user me-2"></i>{{ __('My Profile') }}
                        </a></li>
                        <li><a class="dropdown-item" href="{{ route('home') }}" target="_blank">
                            <i class="fas fa-external-link-alt me-2"></i>{{ __('View Website') }}
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="fas fa-sign-out-alt me-2"></i>{{ __('Logout') }}
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </header>

    <!-- Sidebar -->
    <aside class="admin-sidebar" id="adminSidebar">
        <nav class="sidebar-nav">
            <div class="nav-section-title">{{ __('Main Menu') }}</div>

            <a href="{{ route('admin.dashboard') }}" class="sidebar-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-th-large"></i>
                <span>{{ __('Dashboard') }}</span>
            </a>

            <div class="nav-section-title">{{ __('Management') }}</div>

            <a href="{{ route('admin.players.index') }}" class="sidebar-nav-link {{ request()->routeIs('admin.players.*') ? 'active' : '' }}">
                <i class="fas fa-users"></i>
                <span>{{ __('Players') }}</span>
                @if(\App\Models\Player::where('registration_status', 'Pending')->count() > 0)
                    <span class="sidebar-nav-badge">{{ \App\Models\Player::where('registration_status', 'Pending')->count() }}</span>
                @endif
            </a>

            <a href="{{ route('admin.partners.index') }}" class="sidebar-nav-link {{ request()->routeIs('admin.partners.*') ? 'active' : '' }}">
                <i class="fas fa-handshake"></i>
                <span>{{ __('Partners') }}</span>
                @if(\App\Models\User::where('user_type', 'partner')->where('status', 'pending')->count() > 0)
                    <span class="sidebar-nav-badge">{{ \App\Models\User::where('user_type', 'partner')->where('status', 'pending')->count() }}</span>
                @endif
            </a>

            <a href="{{ route('admin.programs.index') }}" class="sidebar-nav-link {{ request()->routeIs('admin.programs.*') ? 'active' : '' }}">
                <i class="fas fa-football-ball"></i>
                <span>{{ __('Programs') }}</span>
            </a>

            <a href="{{ route('admin.game-statistics.index') }}" class="sidebar-nav-link {{ request()->routeIs('admin.game-statistics.*') ? 'active' : '' }}">
                <i class="fas fa-chart-bar"></i>
                <span>{{ __('Game Statistics') }}</span>
            </a>

            <a href="{{ route('admin.matches.index') }}" class="sidebar-nav-link {{ request()->routeIs('admin.matches.*') ? 'active' : '' }}">
                <i class="fas fa-futbol"></i>
                <span>{{ __('Matches') }}</span>
            </a>

            <a href="{{ route('admin.standings.index') }}" class="sidebar-nav-link {{ request()->routeIs('admin.standings.*') ? 'active' : '' }}">
                <i class="fas fa-trophy"></i>
                <span>{{ __('Standings') }}</span>
            </a>

            <div class="nav-section-title">{{ __('Content') }}</div>

            <a href="{{ route('admin.documents.index') }}" class="sidebar-nav-link {{ request()->routeIs('admin.documents.*') ? 'active' : '' }}">
                <i class="fas fa-file-alt"></i>
                <span>{{ __('Document Management') }}</span>
            </a>

            <a href="{{ route('admin.news.index') }}" class="sidebar-nav-link {{ request()->routeIs('admin.news.*') ? 'active' : '' }}">
                <i class="fas fa-newspaper"></i>
                <span>{{ __('News') }}</span>
            </a>

            <a href="{{ route('admin.gallery.index') }}" class="sidebar-nav-link {{ request()->routeIs('admin.gallery.*') ? 'active' : '' }}">
                <i class="fas fa-images"></i>
                <span>{{ __('Gallery') }}</span>
            </a>

            <a href="{{ route('admin.products.index') }}" class="sidebar-nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                <i class="fas fa-shopping-bag"></i>
                <span>{{ __('Merchandise') }}</span>
            </a>

            <div class="nav-section-title">{{ __('Commerce') }}</div>

            <a href="{{ route('admin.orders.index') }}" class="sidebar-nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                <i class="fas fa-shopping-cart"></i>
                <span>{{ __('Orders') }}</span>
                @if(\App\Models\Order::where('order_status', 'pending')->count() > 0)
                    <span class="sidebar-nav-badge">{{ \App\Models\Order::where('order_status', 'pending')->count() }}</span>
                @endif
            </a>

            <a href="{{ route('admin.payments.index') }}" class="sidebar-nav-link {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
                <i class="fas fa-credit-card"></i>
                <span>{{ __('Payments') }}</span>
                @if(\App\Models\Payment::pending()->count() > 0)
                    <span class="sidebar-nav-badge">{{ \App\Models\Payment::pending()->count() }}</span>
                @endif
            </a>

            <a href="{{ route('admin.payments.financial-report') }}" class="sidebar-nav-link {{ request()->routeIs('admin.payments.financial-report') ? 'active' : '' }}">
                <i class="fas fa-chart-line"></i>
                <span>{{ __('Financial Report') }}</span>
            </a>

            <a href="{{ route('admin.image-upload') }}" class="sidebar-nav-link {{ request()->routeIs('admin.image-upload') ? 'active' : '' }}">
                <i class="fas fa-upload"></i>
                <span>{{ __('Image Upload') }}</span>
            </a>

            <div class="nav-section-title">{{ __('Analytics') }}</div>

            <a href="{{ route('admin.performance.overview') }}" class="sidebar-nav-link {{ request()->routeIs('admin.performance.overview') ? 'active' : '' }}">
                <i class="fas fa-chart-line"></i>
                <span>{{ __('Overview') }}</span>
            </a>

            <a href="{{ route('admin.payments.financial-report') }}" class="sidebar-nav-link {{ request()->routeIs('admin.payments.financial-report') ? 'active' : '' }}">
                <i class="fas fa-chart-bar"></i>
                <span>{{ __('Financial Reports') }}</span>
            </a>

            <a href="{{ route('admin.compliance.report') }}" class="sidebar-nav-link {{ request()->routeIs('admin.compliance.report') ? 'active' : '' }}">
                <i class="fas fa-shield-alt"></i>
                <span>{{ __('Compliance Report') }}</span>
            </a>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="admin-main">
        <!-- Alerts -->
        @if(session('success'))
            <div class="alert-alibaba success">
                <i class="fas fa-check-circle"></i>
                <div>{{ session('success') }}</div>
                <button type="button" class="btn-close ms-auto" onclick="this.parentElement.remove()"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert-alibaba error">
                <i class="fas fa-exclamation-circle"></i>
                <div>{{ session('error') }}</div>
                <button type="button" class="btn-close ms-auto" onclick="this.parentElement.remove()"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert-alibaba error">
                <i class="fas fa-exclamation-triangle"></i>
                <div>
                    <strong>{{ __('Please fix the following errors:') }}</strong>
                    <ul class="mb-0 mt-2" style="padding-left: 1.25rem;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <button type="button" class="btn-close ms-auto" onclick="this.parentElement.remove()"></button>
            </div>
        @endif

        @yield('content')

        <!-- Footer -->
        <footer class="admin-footer">
            <div class="container-fluid">
                <div class="admin-footer-content">
                    <div>&copy; {{ date('Y') }} Vipers Academy. {{ __('All rights reserved.') }}</div>
                    <div class="admin-footer-links">
                        <a href="#">{{ __('Privacy Policy') }}</a>
                        <a href="#">{{ __('Terms of Service') }}</a>
                        <a href="#">{{ __('Support') }}</a>
                    </div>
                </div>
            </div>
        </footer>
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom JavaScript -->
    <script>
        // Mobile sidebar toggle
        document.getElementById('mobileSidebarToggle').addEventListener('click', function() {
            document.getElementById('adminSidebar').classList.toggle('show');
        });

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('adminSidebar');
            const toggle = document.getElementById('mobileSidebarToggle');

            if (!sidebar.contains(event.target) && !toggle.contains(event.target)) {
                sidebar.classList.remove('show');
            }
        });

        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert-alibaba');
            alerts.forEach(function(alert) {
                if (alert.querySelector('.btn-close')) {
                    alert.remove();
                }
            });
        }, 5000);

        // Language selector functionality
        document.querySelector('.language-selector').addEventListener('change', function() {
            const selectedLang = this.value;
            // Here you would typically make an AJAX request to change language
            console.log('Language changed to:', selectedLang);
        });


    </script>

    @stack('scripts')
</body>
</html>
