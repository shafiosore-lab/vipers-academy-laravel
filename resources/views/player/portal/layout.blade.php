<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', __('Player Portal - Vipers Academy'))</title>

    <!-- Preload critical resources -->
    <link rel="dns-prefetch" href="//fonts.googleapis.com">
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link rel="dns-prefetch" href="//cdn.jsdelivr.net">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Bootstrap 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom Styles -->
    <style>
        /* ===== ROOT VARIABLES ===== */
        :root {
            /* Primary Colors */
            --primary-red: #ea1c4d;
            --primary-red-dark: #c0173f;
            --primary-red-light: rgba(234, 28, 77, 0.1);
            --accent-green: #65c16e;
            --accent-blue: #0891b2;
            --warning-yellow: #fbc761;
            --success-green: #059669;

            /* Semantic Colors */
            --bg-primary: #ffffff;
            --bg-secondary: #f8fafc;
            --bg-tertiary: #f1f5f9;
            --bg-dark: #1a1a1a;
            --bg-card: #ffffff;

            /* Text Colors */
            --text-primary: #1a1a1a;
            --text-secondary: #6b7280;
            --text-muted: #9ca3af;
            --text-white: #ffffff;

            /* Status Colors */
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #3b82f6;

            /* Spacing & Shadows */
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);

            /* Borders */
            --border: 1px solid #e5e7eb;
            --border-light: 1px solid #f3f4f6;

            /* Transitions */
            --transition-fast: 0.15s ease;
            --transition-normal: 0.3s ease;
            --transition-slow: 0.5s ease;

            /* Interactivity */
            --hover-transform: scale(1.02);
            --active-transform: scale(0.98);

            /* Responsive breakpoints */
            --mobile: 768px;
            --tablet: 1024px;
            --desktop: 1200px;
        }

        /* ===== RESET & BASE ===== */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html, body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            font-size: 14px;
            line-height: 1.6;
            color: var(--text-primary);
            background: var(--bg-secondary);
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* ===== ENHANCED TASKBAR ===== */
        .player-taskbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 72px;
            background: linear-gradient(135deg, var(--bg-primary) 0%, rgba(255, 255, 255, 0.95) 100%);
            backdrop-filter: blur(20px);
            border-bottom: var(--border);
            z-index: 1040;
            box-shadow: var(--shadow);
        }

        .taskbar-inner {
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 24px;
            max-width: 1400px;
            margin: 0 auto;
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            position: fixed;
            top: 72px;
            left: 0;
            width: 260px;
            height: calc(100vh - 72px);
            background: white;
            border-right: var(--border);
            box-shadow: var(--shadow);
            z-index: 1000;
            overflow-y: auto;
            transform: translateX(0);
            transition: transform 0.3s ease;
        }

        .sidebar.collapsed {
            transform: translateX(-260px);
        }

        .sidebar-mobile-overlay {
            display: none;
            position: fixed;
            top: 72px;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }

        .sidebar-mobile-overlay.show {
            display: block;
        }

        .sidebar-content {
            padding: 24px 16px;
        }

        .sidebar-brand {
            margin-bottom: 32px;
            padding-bottom: 16px;
            border-bottom: 2px solid var(--primary-red-light);
        }

        .sidebar-brand h3 {
            font-size: 18px;
            font-weight: 700;
            color: var(--primary-red);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .sidebar-brand i {
            color: var(--primary-red);
        }

        .sidebar-nav {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .sidebar-nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 16px;
            color: var(--text-secondary);
            text-decoration: none;
            border-radius: 8px;
            transition: var(--transition-normal);
            position: relative;
            font-weight: 500;
        }

        .sidebar-nav-item:hover {
            background: var(--bg-tertiary);
            color: var(--primary-red);
        }

        .sidebar-nav-item.active {
            background: var(--primary-red-light);
            color: var(--primary-red);
            font-weight: 600;
        }

        .sidebar-nav-item.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 24px;
            background: var(--primary-red);
            border-radius: 0 2px 2px 0;
        }

        .sidebar-nav-item.active {
            padding-left: 20px;
        }

        .sidebar-nav-item i {
            width: 20px;
            text-align: center;
        }

        .sidebar-toggle {
            position: absolute;
            top: 20px;
            right: -16px;
            width: 32px;
            height: 64px;
            background: var(--bg-primary);
            border: var(--border);
            border-left: none;
            border-radius: 0 8px 8px 0;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-secondary);
            transition: var(--transition-normal);
            box-shadow: var(--shadow);
            z-index: 1001;
        }

        .sidebar-toggle:hover {
            background: var(--primary-red-light);
            color: var(--primary-red);
        }

        /* Logo Section */
        .taskbar-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }

        .taskbar-logo img {
            width: 48px;
            height: 48px;
            object-fit: contain;
        }

        .taskbar-brand {
            font-size: 20px;
            font-weight: 700;
            color: var(--primary-red);
        }

        .taskbar-tagline {
            font-size: 10px;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
        }

        /* Main Navigation */
        .taskbar-nav {
            display: flex;
            gap: 8px;
            margin: 0 32px;
        }

        .nav-tab {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 12px 20px;
            color: var(--text-secondary);
            text-decoration: none;
            border-radius: 12px;
            transition: var(--transition-normal);
            position: relative;
            font-weight: 500;
        }

        .nav-tab:hover {
            color: var(--primary-red);
            background: var(--primary-red-light);
        }

        .nav-tab.active {
            color: var(--primary-red);
            background: var(--primary-red-light);
        }

        .nav-tab i {
            font-size: 16px;
        }

        /* Taskbar Tools */
        .taskbar-tools {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        /* Search */
        .taskbar-search {
            position: relative;
            width: 280px;
        }

        .taskbar-search form {
            position: relative;
        }

        .taskbar-search input {
            width: 100%;
            padding: 10px 44px 10px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 24px;
            font-size: 14px;
            transition: var(--transition-normal);
            background: white;
        }

        .taskbar-search input:focus {
            outline: none;
            border-color: var(--primary-red);
            box-shadow: 0 0 0 3px rgba(234, 28, 77, 0.1);
        }

        .taskbar-search button {
            position: absolute;
            right: 4px;
            top: 50%;
            transform: translateY(-50%);
            background: var(--primary-red);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 18px;
            font-size: 12px;
            cursor: pointer;
            transition: var(--transition-normal);
        }

        .taskbar-search button:hover {
            background: var(--primary-red-dark);
            transform: translateY(-50%) scale(1.05);
        }

        /* Notification Bell */
        .notification-bell {
            position: relative;
            background: none;
            border: none;
            color: var(--text-secondary);
            font-size: 18px;
            padding: 8px;
            cursor: pointer;
            transition: var(--transition-normal);
            border-radius: 8px;
        }

        .notification-bell:hover {
            background: #f3f4f6;
            color: var(--primary-red);
        }

        .notification-badge {
            position: absolute;
            top: 4px;
            right: 4px;
            background: var(--danger);
            color: white;
            border-radius: 50%;
            width: 16px;
            height: 16px;
            font-size: 10px;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid white;
        }

        /* User Menu */
        .user-menu {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px 16px;
            background: #f8fafc;
            border-radius: 24px;
            cursor: pointer;
            transition: var(--transition-normal);
        }

        .user-menu:hover {
            background: #e5e7eb;
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-red), var(--accent-green));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 14px;
        }

        .user-info {
            display: flex;
            flex-direction: column;
            line-height: 1.2;
        }

        .user-name {
            font-size: 13px;
            font-weight: 600;
            color: var(--text-primary);
        }

        .user-role {
            font-size: 10px;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .user-menu i {
            font-size: 12px;
            color: var(--text-muted);
        }

        /* Language/Currency Selector */
        .lang-currency-selector {
            display: flex;
            gap: 8px;
        }

        .selector-dropdown {
            background: none;
            border: 2px solid #e5e7eb;
            color: var(--text-secondary);
            font-size: 12px;
            padding: 6px 12px;
            border-radius: 20px;
            cursor: pointer;
            transition: var(--transition-normal);
        }

        .selector-dropdown:hover,
        .selector-dropdown:focus {
            border-color: var(--primary-red);
            color: var(--primary-red);
        }

        /* ===== MAIN CONTENT ===== */
        .portal-content {
            margin-top: 72px;
            margin-left: 260px;
            min-height: calc(100vh - 72px);
            transition: margin-left 0.3s ease;
        }

        .portal-content.sidebar-collapsed {
            margin-left: 0;
        }

        .content-wrapper {
            max-width: 1200px;
            margin: 0 auto;
            padding: 32px 24px;
        }

        /* ===== MOBILE RESPONSIVE ===== */
        @media (max-width: 1024px) {
            .taskbar-nav {
                display: none;
            }

            .taskbar-search {
                width: 200px;
            }
        }

        @media (max-width: 768px) {
            .taskbar-inner {
                padding: 0 16px;
            }

            .taskbar-brand {
                display: none;
            }

            .taskbar-search {
                display: none;
            }

            .lang-currency-selector {
                display: none;
            }

            .taskbar-tools {
                gap: 8px;
            }

            .content-wrapper {
                padding: 16px 12px;
            }
        }

        @media (max-width: 480px) {
            .user-info {
                display: none;
            }

            .notification-bell {
                font-size: 16px;
                padding: 6px;
            }
        }

        /* ===== UTILITIES ===== */
        .gradient-primary {
            background: linear-gradient(135deg, var(--primary-red), var(--primary-red-dark));
        }

        .text-primary {
            color: var(--primary-red);
        }

        .bg-primary-soft {
            background: var(--primary-red-light);
        }

        .border-primary {
            border-color: var(--primary-red);
        }

        .shadow-enhanced {
            box-shadow: var(--shadow-lg);
        }

        /* ===== ANIMATIONS ===== */
        @keyframes slideIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        @keyframes bounce-in {
            0% { transform: scale(0.3); opacity: 0; }
            50% { transform: scale(1.05); }
            70% { transform: scale(0.9); }
            100% { transform: scale(1); opacity: 1; }
        }

        .animate-slide-in {
            animation: slideIn 0.3s ease-out;
        }

        .animate-pulse {
            animation: pulse 2s infinite;
        }

        .animate-bounce-in {
            animation: bounce-in 0.6s ease-out;
        }

        /* ===== SEMANTIC ELEMENTS ===== */
        .portal-section {
            background: var(--bg-primary);
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 24px;
            box-shadow: var(--shadow);
            border: var(--border-light);
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: var(--border);
        }

        .section-title {
            font-size: 24px;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0;
        }

        .section-subtitle {
            font-size: 16px;
            color: var(--text-secondary);
            margin: 8px 0 0 0;
        }

        /* ===== MOBILE MENU DRAWER ===== */
        .mobile-menu-toggle {
            display: none;
            background: none;
            border: none;
            color: var(--text-secondary);
            font-size: 20px;
            padding: 8px;
            cursor: pointer;
            border-radius: 8px;
            transition: var(--transition-normal);
        }

        .mobile-menu-toggle:hover {
            background: #f3f4f6;
            color: var(--primary-red);
        }

        .mobile-nav-overlay {
            display: none;
            position: fixed;
            top: 72px;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1039;
        }

        .mobile-nav-drawer {
            position: absolute;
            top: 0;
            left: 0;
            width: 280px;
            height: 100%;
            background: var(--bg-primary);
            box-shadow: var(--shadow-xl);
            transform: translateX(-100%);
            transition: transform 0.3s ease;
            overflow-y: auto;
        }

        .mobile-nav-drawer.open {
            transform: translateX(0);
        }

        .mobile-nav-header {
            padding: 20px;
            border-bottom: var(--border);
            background: linear-gradient(135deg, var(--primary-red-light), rgba(255, 255, 255, 0.8));
        }

        .mobile-nav-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--primary-red);
        }

        .mobile-nav-close {
            position: absolute;
            top: 16px;
            right: 16px;
            background: none;
            border: none;
            font-size: 18px;
            color: var(--primary-red);
            cursor: pointer;
        }

        .mobile-nav-links {
            list-style: none;
            padding: 0;
        }

        .mobile-nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 16px 20px;
            color: var(--text-secondary);
            text-decoration: none;
            border-bottom: var(--border-light);
            transition: var(--transition-normal);
        }

        .mobile-nav-link:hover,
        .mobile-nav-link.active {
            background: var(--primary-red-light);
            color: var(--primary-red);
            border-left: 4px solid var(--primary-red);
            margin-left: 0;
            padding-left: 16px;
        }

        .mobile-nav-link i {
            font-size: 18px;
        }

        /* Sidebar Toggle Button in Taskbar */
        .sidebar-toggle-btn {
            background: none;
            border: 2px solid #e5e7eb;
            color: var(--text-secondary);
            padding: 8px 12px;
            border-radius: 8px;
            cursor: pointer;
            transition: var(--transition-normal);
            font-size: 16px;
        }

        .sidebar-toggle-btn:hover {
            background: var(--primary-red-light);
            border-color: var(--primary-red);
            color: var(--primary-red);
        }

        /* Cart Navigation Icon */
        .cart-nav-link {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 8px 12px;
            color: var(--text-secondary);
            text-decoration: none;
            border-radius: 8px;
            transition: var(--transition-normal);
            border: 2px solid transparent;
        }

        .cart-nav-link:hover {
            background: var(--primary-red-light);
            color: var(--primary-red);
            border-color: var(--primary-red);
            transform: scale(1.05);
        }

        .cart-nav-link i {
            font-size: 18px;
        }

        .cart-count {
            position: absolute;
            top: -8px;
            right: -8px;
            background: var(--danger);
            color: white;
            border-radius: 50%;
            min-width: 18px;
            height: 18px;
            font-size: 11px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: var(--transition-normal);
        }

        .cart-count.empty {
            display: none;
        }

        @media (max-width: 1024px) {
            .sidebar-toggle-btn {
                display: none;
            }

            .sidebar {
                display: none;
            }

            .portal-content {
                margin-left: 0 !important;
            }

            .mobile-menu-toggle {
                display: block;
            }

            .mobile-nav-overlay.show {
                display: block;
            }
        }
    </style>
</head>

<body>
    <!-- Enhanced Taskbar -->
    <header class="player-taskbar">
        <div class="taskbar-inner">
            <!-- Logo -->
            <a href="{{ route('player.portal.dashboard') }}" class="taskbar-logo">
                <img src="{{ asset('assets/img/logo/vps.jpeg') }}" alt="Vipers Academy Logo">
                <div>
                    <div class="taskbar-brand">Vipers Academy</div>
                    <div class="taskbar-tagline">Player Portal</div>
                </div>
            </a>

            <!-- Mobile Menu Toggle -->
            <button class="mobile-menu-toggle" id="mobileMenuToggle">
                <i class="fas fa-bars"></i>
            </button>

            <!-- Sidebar Toggle Button -->
            <button class="sidebar-toggle-btn" id="sidebarToggle" title="Toggle Sidebar">
                <i class="fas fa-bars"></i>
            </button>

            <!-- Taskbar Tools -->
            <div class="taskbar-tools">
                <!-- Quick Search -->
                <div class="taskbar-search">
                    <form action="{{ route('search') }}" method="GET">
                        <input type="search" name="q" placeholder="Quick search..." autocomplete="off">
                        <button type="submit"><i class="fas fa-search"></i></button>
                    </form>
                </div>

                <!-- Notifications -->
                <button class="notification-bell" title="Notifications">
                    <i class="fas fa-bell"></i>
                    <!-- <span class="notification-badge">3</span> -->
                </button>

                <!-- Language/Currency Selector -->
                <div class="lang-currency-selector">
                    <select class="selector-dropdown" title="Language">
                        <option value="en">🇬🇧 EN</option>
                        <option value="es">🇪🇸 ES</option>
                        <option value="fr">🇫🇷 FR</option>
                        <option value="sw">🇰🇪 SW</option>
                    </select>
                    <select class="selector-dropdown" title="Currency">
                        <option value="usd">💵 USD</option>
                        <option value="eur">💶 EUR</option>
                        <option value="kes">🇰🇪 KES</option>
                    </select>
                </div>

                <!-- Store Icon -->
                <a href="{{ route('products') }}" class="cart-nav-link" title="Academy Store" target="_blank">
                    <i class="fas fa-store"></i>
                </a>

                <!-- Cart Icon -->
                <a href="{{ route('cart.index') }}" class="cart-nav-link" title="Shopping Cart">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="cart-count" id="nav-cart-count">0</span>
                </a>

                <!-- User Menu -->
                <div class="dropdown">
                    <div class="user-menu" data-bs-toggle="dropdown">
                        <div class="user-avatar">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div class="user-info">
                            <div class="user-name">{{ auth()->user()->name }}</div>
                            <div class="user-role">Player</div>
                        </div>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <ul class="dropdown-menu dropdown-menu-end" style="margin-top: 8px; border-radius: 12px; box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);">
                        <li><a class="dropdown-item" href="{{ route('player.portal.profile') }}">
                            <i class="fas fa-user me-2"></i>My Profile
                        </a></li>
                        <li><a class="dropdown-item" href="{{ route('profile.edit') }}">
                            <i class="fas fa-cog me-2"></i>Account Settings
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </header>

    <!-- Mobile Navigation Drawer -->
    <div class="mobile-nav-overlay" id="mobileNavOverlay">
        <div class="mobile-nav-drawer" id="mobileNavDrawer">
            <div class="mobile-nav-header">
                <div class="mobile-nav-title">Menu</div>
                <button class="mobile-nav-close" id="mobileNavClose">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <ul class="mobile-nav-links">
                <li><a href="{{ route('player.portal.dashboard') }}" class="mobile-nav-link{{ request()->routeIs('player.portal.dashboard') ? ' active' : '' }}">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a></li>
                <li><a href="{{ route('player.portal.programs') }}" class="mobile-nav-link{{ request()->routeIs('player.portal.programs') ? ' active' : '' }}">
                    <i class="fas fa-graduation-cap"></i>
                    <span>My Programs</span>
                </a></li>
                <li><a href="{{ route('player.portal.training') }}" class="mobile-nav-link{{ request()->routeIs('player.portal.training') ? ' active' : '' }}">
                    <i class="fas fa-chart-line"></i>
                    <span>Training & Progress</span>
                </a></li>
                <li><a href="{{ route('player.portal.schedule') }}" class="mobile-nav-link{{ request()->routeIs('player.portal.schedule') ? ' active' : '' }}">
                    <i class="fas fa-calendar-check"></i>
                    <span>Schedule & Attendance</span>
                </a></li>
                <li><a href="{{ route('player.portal.resources') }}" class="mobile-nav-link{{ request()->routeIs('player.portal.resources') ? ' active' : '' }}">
                    <i class="fas fa-book-open"></i>
                    <span>Resources & Learning</span>
                </a></li>
                <li><a href="{{ route('player.portal.orders') }}" class="mobile-nav-link{{ request()->routeIs('player.portal.orders') ? ' active' : '' }}">
                    <i class="fas fa-shopping-bag"></i>
                    <span>Order History</span>
                </a></li>
                <li><a href="{{ route('player.portal.communication') }}" class="mobile-nav-link{{ request()->routeIs('player.portal.communication') ? ' active' : '' }}">
                    <i class="fas fa-comments"></i>
                    <span>Communication</span>
                </a></li>
                <li><a href="{{ route('player.portal.profile') }}" class="mobile-nav-link{{ request()->routeIs('player.portal.profile') ? ' active' : '' }}">
                    <i class="fas fa-user"></i>
                    <span>Profile & Settings</span>
                </a></li>
                <li><a href="{{ route('player.portal.support') }}" class="mobile-nav-link{{ request()->routeIs('player.portal.support') ? ' active' : '' }}">
                    <i class="fas fa-life-ring"></i>
                    <span>Support & Help</span>
                </a></li>
            </ul>
        </div>
    </div>

    <!-- Sidebar -->
    <aside id="mainSidebar" class="sidebar">
        <button class="sidebar-toggle" id="sidebarToggle">
            <i class="fas fa-chevron-left"></i>
        </button>

        <div class="sidebar-content">
            <div class="sidebar-brand">
                <h3><i class="fas fa-user-graduate"></i>Player Portal</h3>
            </div>

            <nav class="sidebar-nav">
                <a href="{{ route('player.portal.dashboard') }}" class="sidebar-nav-item{{ request()->routeIs('player.portal.dashboard') ? ' active' : '' }}">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('player.portal.programs') }}" class="sidebar-nav-item{{ request()->routeIs('player.portal.programs') ? ' active' : '' }}">
                    <i class="fas fa-graduation-cap"></i>
                    <span>Programs</span>
                </a>
                <a href="{{ route('player.portal.training') }}" class="sidebar-nav-item{{ request()->routeIs('player.portal.training') ? ' active' : '' }}">
                    <i class="fas fa-chart-line"></i>
                    <span>Training</span>
                </a>
                <a href="{{ route('player.portal.schedule') }}" class="sidebar-nav-item{{ request()->routeIs('player.portal.schedule') ? ' active' : '' }}">
                    <i class="fas fa-calendar-check"></i>
                    <span>Schedule</span>
                </a>
                <a href="{{ route('player.portal.resources') }}" class="sidebar-nav-item{{ request()->routeIs('player.portal.resources') ? ' active' : '' }}">
                    <i class="fas fa-book-open"></i>
                    <span>Resources</span>
                </a>
                <a href="{{ route('player.portal.orders') }}" class="sidebar-nav-item{{ request()->routeIs('player.portal.orders') ? ' active' : '' }}">
                    <i class="fas fa-shopping-bag"></i>
                    <span>Orders</span>
                </a>
                <a href="{{ route('player.portal.communication') }}" class="sidebar-nav-item{{ request()->routeIs('player.portal.communication') ? ' active' : '' }}">
                    <i class="fas fa-comments"></i>
                    <span>Messages</span>
                </a>
                <a href="{{ route('player.portal.support') }}" class="sidebar-nav-item{{ request()->routeIs('player.portal.support') ? ' active' : '' }}">
                    <i class="fas fa-life-ring"></i>
                    <span>Support</span>
                </a>
            </nav>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="portal-content">
        <div class="content-wrapper">
            @yield('portal-content')
        </div>
    </main>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>

    <script>
        // Initialize AOS
        AOS.init({
            duration: 600,
            once: true,
            offset: 50
        });

        // Mobile menu functionality
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuToggle = document.getElementById('mobileMenuToggle');
            const mobileNavOverlay = document.getElementById('mobileNavOverlay');
            const mobileNavDrawer = document.getElementById('mobileNavDrawer');
            const mobileNavClose = document.getElementById('mobileNavClose');

            function openMobileMenu() {
                mobileNavOverlay.classList.add('show');
                mobileNavDrawer.classList.add('open');
                document.body.style.overflow = 'hidden';
            }

            function closeMobileMenu() {
                mobileNavOverlay.classList.remove('show');
                mobileNavDrawer.classList.remove('open');
                document.body.style.overflow = '';
            }

            mobileMenuToggle?.addEventListener('click', openMobileMenu);
            mobileNavOverlay?.addEventListener('click', closeMobileMenu);
            mobileNavClose?.addEventListener('click', closeMobileMenu);
        });

        // Enhanced search with loading states
        document.querySelector('.taskbar-search form')?.addEventListener('submit', function(e) {
            const button = this.querySelector('button');
            const originalText = button.innerHTML;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            button.disabled = true;

            // Reset after 3 seconds
            setTimeout(() => {
                button.innerHTML = originalText;
                button.disabled = false;
            }, 3000);
        });

        // Language selector functionality (placeholder for i18n)
        document.querySelectorAll('.selector-dropdown').forEach(select => {
            select.addEventListener('change', function() {
                console.log(`${this.title} changed to: ${this.value}`);
                // Here you would typically make an AJAX request to change language/currency
                // For now, just log the change
            });
        });

        // Notification bell hover effect
        document.querySelector('.notification-bell')?.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.05)';
        });

        document.querySelector('.notification-bell')?.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
        });

        // Sidebar toggle functionality
        const sidebarToggleBtn = document.getElementById('sidebarToggle');
        const sidebarToggleOnSidebar = document.querySelector('.sidebar-toggle');
        const sidebar = document.getElementById('mainSidebar');
        const portalContent = document.querySelector('.portal-content');

        function toggleSidebar() {
            const isCollapsed = sidebar.classList.contains('collapsed');
            sidebar.classList.toggle('collapsed');
            portalContent.classList.toggle('sidebar-collapsed');

            // Update toggle button icon
            const toggleIcon = document.querySelector('.sidebar-toggle i');
            if (isCollapsed) {
                toggleIcon.className = 'fas fa-chevron-left';
            } else {
                toggleIcon.className = 'fas fa-chevron-right';
            }
        }

        sidebarToggleBtn?.addEventListener('click', toggleSidebar);
        sidebarToggleOnSidebar?.addEventListener('click', toggleSidebar);

        // Auto-collapse sidebar on mobile
        function checkScreenSize() {
            if (window.innerWidth <= 1024 && !sidebar.classList.contains('collapsed')) {
                sidebar.classList.add('collapsed');
                portalContent.classList.add('sidebar-collapsed');
                const toggleIcon = document.querySelector('.sidebar-toggle i');
                toggleIcon.className = 'fas fa-chevron-right';
            }
        }

        // ---------- Cart Count Update ----------
        function updateCartCount() {
            fetch('{{ route("cart.summary") }}')
                .then(response => response.json())
                .then(data => {
                    const cartCountElement = document.getElementById('nav-cart-count');
                    if (cartCountElement) {
                        const count = data.count || 0;
                        cartCountElement.textContent = count > 99 ? '99+' : count;
                        cartCountElement.classList.toggle('empty', count === 0);
                    }
                })
                .catch(error => {
                    console.log('Error updating cart count:', error);
                });
        }

        // Update cart count on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateCartCount();
        });

        // Update cart count after adding/removing items (listen for custom events from cart page)
        window.addEventListener('cartUpdated', function() {
            updateCartCount();
        });

        window.addEventListener('resize', checkScreenSize);
        checkScreenSize(); // Check on initial load
    </script>

    @stack('scripts')
</body>
</html>
