<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', __('Admin Dashboard - GameSuite'))</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        /* ========================================
           CSS VARIABLES
           ======================================== */
        :root {
            --primary:        #ea1c4d;
            --primary-dark:   #c0173f;
            --secondary:      #65c16e;
            --accent:         #fbc761;
            --danger:         #dc2626;
            --info:           #0891b2;
            --dark:           #1a1a1a;
            --gray-900:       #333;
            --gray-600:       #666;
            --gray-300:       #e8e8e8;
            --gray-100:       #f5f5f5;
            --bg-light:       #f7f7f7;
            --white:          #fff;
            --shadow-sm:      0 1px 2px rgba(0,0,0,0.05);
            --shadow:         0 1px 3px rgba(0,0,0,0.1);
            --shadow-lg:      0 10px 15px rgba(0,0,0,0.1);
            --transition:     0.2s ease;
            --header-height:  60px;
            --sidebar-width:  260px;
        }

        /* ========================================
           GLOBAL
           ======================================== */
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Roboto', sans-serif;
            font-size: 14px;
            background: var(--gray-100);
            color: var(--gray-900);
        }

        h1, h2, h3, h4, h5, h6 { font-weight: 700; color: var(--gray-900); }
        h1 { font-size: 28px; color: var(--primary); }
        h2 { font-size: 20px; }
        h3 { font-size: 16px; }

        .sr-only {
            position: absolute;
            width: 1px; height: 1px;
            padding: 0; margin: -1px;
            overflow: hidden;
            clip: rect(0,0,0,0);
            white-space: nowrap;
            border: 0;
        }

        /* ========================================
           TOP HEADER
           ======================================== */
        .top-header {
            background: var(--white);
            border-bottom: 1px solid var(--gray-300);
            height: var(--header-height);
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 1040;
            box-shadow: var(--shadow-sm);
        }

        .top-header .container-fluid {
            height: 100%;
            display: flex;
            align-items: center;
            padding: 0 1.5rem;
            gap: 1rem;
        }

        /* Mobile sidebar toggle — left edge */
        .sidebar-toggle-mobile {
            display: none;
            background: none;
            border: none;
            font-size: 20px;
            color: var(--gray-600);
            cursor: pointer;
            flex-shrink: 0;
        }

        /* Brand */
        .admin-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            flex-shrink: 0;
        }

        .admin-brand-text {
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--primary);
        }

        /* Push user menu to far right */
        .header-right {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-left: auto;
        }

        /* Icon action buttons */
        .header-action-btn {
            width: 34px; height: 34px;
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
            top: -2px; right: -2px;
            background: var(--danger);
            color: var(--white);
            border-radius: 50%;
            width: 16px; height: 16px;
            font-size: 9px;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid var(--white);
        }

        /* User menu button */
        .admin-user-menu {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 4px 10px;
            background: #f8f9fa;
            border-radius: 20px;
            cursor: pointer;
            border: none;
            transition: var(--transition);
        }

        .admin-user-menu:hover { background: #e9ecef; }

        .admin-user-avatar {
            width: 28px; height: 28px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--white);
            font-size: 12px;
            font-weight: 600;
        }

        .admin-user-name {
            font-size: 13px;
            font-weight: 600;
            color: var(--gray-900);
        }

        .admin-user-menu i { font-size: 10px; color: #999; }

        /* ========================================
           DROPDOWN MENUS
           ======================================== */
        .dropdown-menu {
            border-radius: 8px;
            border: 1px solid var(--gray-300);
            box-shadow: var(--shadow-lg);
            padding: 0.5rem;
            animation: dropdownSlide 0.2s ease;
        }

        @keyframes dropdownSlide {
            from { opacity: 0; transform: translateY(-10px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .dropdown-item {
            border-radius: 6px;
            padding: 8px 12px;
            font-size: 13px;
            transition: var(--transition);
        }

        .dropdown-item:hover { background: #f8f9fa; color: var(--primary); }

        .dropdown-header {
            font-size: 11px;
            font-weight: 600;
            color: #999;
            text-transform: uppercase;
        }

        /* User dropdown always opens to the right (end) */
        .user-dropdown .dropdown-menu {
            right: 0 !important;
            left: auto !important;
        }

        /* ========================================
           LEFT SIDEBAR
           ======================================== */
        .admin-sidebar {
            background: var(--white);
            width: var(--sidebar-width);
            position: fixed;
            top: var(--header-height);
            left: 0; bottom: 0;
            border-right: 1px solid var(--gray-300);
            overflow-y: auto;
            z-index: 1030;
            box-shadow: 2px 0 10px rgba(0,0,0,0.05);
        }

        .admin-sidebar::-webkit-scrollbar { width: 4px; }
        .admin-sidebar::-webkit-scrollbar-thumb {
            background: var(--gray-300);
            border-radius: 2px;
        }

        .sidebar-nav { padding: 0.25rem 0; }

        /* Accordion mode toggle */
        .accordion-settings {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 1rem;
            margin-bottom: 8px;
            background: #f8f9fa;
            border-bottom: 1px solid var(--gray-300);
        }

        .accordion-settings-label { font-size: 11px; color: #999; font-weight: 500; }

        .accordion-toggle-switch {
            position: relative;
            width: 36px; height: 20px;
        }

        .accordion-toggle-switch input { opacity: 0; width: 0; height: 0; }

        .accordion-toggle-slider {
            position: absolute;
            cursor: pointer;
            inset: 0;
            background-color: #ccc;
            transition: 0.3s;
            border-radius: 20px;
        }

        .accordion-toggle-slider::before {
            content: "";
            position: absolute;
            height: 14px; width: 14px;
            left: 3px; bottom: 3px;
            background-color: white;
            transition: 0.3s;
            border-radius: 50%;
        }

        .accordion-toggle-switch input:checked + .accordion-toggle-slider { background-color: var(--primary); }
        .accordion-toggle-switch input:checked + .accordion-toggle-slider::before { transform: translateX(16px); }

        /* Sidebar accordion wrapper */
        .sidebar-accordion { margin-bottom: 2px; }

        .sidebar-accordion-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 8px 1rem;
            color: var(--gray-900);
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            background: transparent;
            border: none;
            width: 100%;
            text-align: left;
        }

        .sidebar-accordion-header:hover { background: #f8f9fa; color: var(--primary); }
        .sidebar-accordion-header.active { background: #fff5f0; color: var(--primary); }

        .sidebar-accordion-header i { width: 18px; font-size: 13px; margin-right: 8px; }

        .sidebar-accordion-icon {
            font-size: 11px;
            color: var(--gray-600);
            transition: all 0.3s ease;
            font-weight: bold;
        }

        .sidebar-accordion-header.active .sidebar-accordion-icon { color: var(--primary); }
        .sidebar-accordion-header.active .sidebar-accordion-icon::before { content: "\2212"; }
        .sidebar-accordion:not(.open) .sidebar-accordion-icon::before { content: "\002B"; }

        .sidebar-accordion-content {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }

        .sidebar-accordion.open .sidebar-accordion-content { max-height: 500px; }
        .sidebar-accordion-content .sidebar-link { padding-left: 2.5rem; }

        /* Sidebar links */
        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 6px 1rem;
            color: var(--gray-600);
            text-decoration: none;
            font-size: 11px;
            font-weight: 500;
            transition: all 0.2s ease;
            border-left: 3px solid transparent;
        }

        .sidebar-link:hover { background: #f8f9fa; color: var(--primary); }

        .sidebar-link.active {
            background: #fff5f0;
            color: var(--primary);
            border-left-color: var(--primary);
            font-weight: 600;
        }

        .sidebar-link i { width: 16px; font-size: 13px; text-align: center; }

        .sidebar-badge {
            margin-left: auto;
            background: var(--danger);
            color: var(--white);
            font-size: 9px;
            font-weight: 600;
            padding: 2px 6px;
            border-radius: 10px;
        }

        .sidebar-divider { height: 1px; background: #e8e8e8; margin: 8px 12px; }
        .sidebar-section-label { padding: 4px 1rem; font-size: 10px; color: #999; font-weight: 600; text-transform: uppercase; }

        /* ========================================
           MAIN CONTENT
           ======================================== */
        .admin-content {
            margin-top: var(--header-height);
            margin-left: var(--sidebar-width);
            padding: 20px;
            min-height: calc(100vh - var(--header-height));
        }

        /* ========================================
           ALERTS
           ======================================== */
        .alert-custom {
            border-radius: 8px;
            padding: 1rem;
            border: 1px solid transparent;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 12px;
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateY(-10px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .alert-custom.success { background: #f0fdf4; border-color: #bbf7d0; color: var(--secondary); }
        .alert-custom.error   { background: #fef2f2; border-color: #fecaca; color: var(--danger); }

        /* ========================================
           FOOTER
           ======================================== */
        .admin-footer {
            margin-top: 2rem;
            padding: 1rem 0;
            border-top: 1px solid var(--gray-300);
        }

        .admin-footer-content {
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            color: #999;
        }

        /* ========================================
           RESPONSIVE
           ======================================== */
        @media (max-width: 991.98px) {
            .admin-sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
                position: fixed;
                top: var(--header-height);
                left: 0;
                z-index: 1030;
                height: calc(100vh - var(--header-height));
                box-shadow: 2px 0 15px rgba(0,0,0,0.2);
            }

            .admin-sidebar.show {
                transform: translateX(0);
            }

            .admin-content {
                margin-left: 0;
                padding: 15px;
            }

            .sidebar-toggle-mobile { display: block !important; }

            /* Fix content overlap when sidebar is open */
            .admin-sidebar.show ~ .admin-content {
                margin-left: 0;
                filter: blur(2px);
            }

            /* Improve mobile header */
            .top-header {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                z-index: 1040;
            }

            .admin-content {
                margin-top: var(--header-height);
            }
        }

        @media (max-width: 768px) {
            .top-header {
                height: auto;
                padding: 10px 15px;
                min-height: 60px;
            }

            .top-header .container-fluid {
                flex-wrap: wrap;
                gap: 10px;
                align-items: center;
            }

            .admin-brand-text {
                font-size: 1.25rem;
            }

            .admin-user-name {
                display: none;
            }

            /* Mobile stats cards - stack vertically */
            .admin-content .row.g-2.mb-3 {
                --bs-gutter-x: 1rem;
            }

            .admin-content .col-lg-8, .admin-content .col-lg-4 {
                flex: 0 0 100%;
                max-width: 100%;
            }

            /* Mobile table improvements */
            .table-responsive {
                border-radius: 8px;
                overflow: hidden;
            }

            .table th, .table td {
                font-size: 0.85rem;
                padding: 0.5rem;
            }

            /* Mobile pagination */
            .d-flex.justify-content-center.mt-2 {
                flex-direction: column;
                align-items: center;
                gap: 10px;
            }
        }

        @media (max-width: 576px) {
            .top-header {
                padding: 8px 12px;
                min-height: 55px;
            }

            .admin-brand-text {
                font-size: 1.1rem;
            }

            /* Further reduce padding on very small screens */
            .admin-content {
                padding: 10px;
            }

            /* Mobile header actions spacing */
            .header-right {
                gap: 6px;
            }

            .header-action-btn {
                width: 32px;
                height: 32px;
                font-size: 16px;
            }

            /* Mobile sidebar content adjustments */
            .sidebar-accordion-header {
                padding: 10px 12px;
                font-size: 12px;
            }

            .sidebar-link {
                padding: 8px 12px;
                font-size: 11px;
            }

            .sidebar-badge {
                font-size: 8px;
                padding: 1px 4px;
            }
        }

        /* Extra small screens optimization */
        @media (max-width: 425px) {
            .admin-content {
                padding: 8px;
            }

            .card.border-0.shadow-sm {
                margin-bottom: 10px;
            }

            .card-body.p-2 {
                padding: 8px !important;
            }

            /* Mobile table text wrapping */
            .table td.wrap, .table th.wrap {
                white-space: normal;
                word-wrap: break-word;
            }

            /* Mobile button sizing */
            .btn-sm {
                padding: 6px 10px;
                font-size: 12px;
            }
        }
    </style>

    @stack('styles')
</head>

<body>

    {{-- ===================== TOP HEADER ===================== --}}
    <header class="top-header" role="banner">
        <div class="container-fluid">

            {{-- Mobile sidebar toggle (left) --}}
            <button class="sidebar-toggle-mobile"
                    id="mobileSidebarToggle"
                    type="button"
                    aria-label="{{ __('Toggle navigation menu') }}"
                    aria-expanded="false"
                    aria-controls="adminSidebar"
                    onclick="toggleSidebar()">
                <i class="fas fa-bars"></i>
            </button>

            {{-- Brand --}}
            @php
                $dashboardRoute = route('admin.dashboard');
                $user = auth()->user();
                if ($user->hasAnyRole(['coach', 'head-coach', 'assistant-coach'])) {
                    $dashboardRoute = route('coach.dashboard');
                } elseif ($user->hasRole('team-manager')) {
                    $dashboardRoute = route('manager.dashboard');
                } elseif ($user->hasRole('finance-officer')) {
                    $dashboardRoute = route($user->hasRole('super-admin') ? 'super-admin.finance.dashboard' : 'finance.dashboard');
                }
            @endphp

            <a href="{{ $dashboardRoute }}" class="admin-brand">
                <span class="admin-brand-text">{{ __('GameSuite') }}</span>
            </a>

            {{-- Right-side actions (pushed to far right via margin-left: auto) --}}
            <nav class="header-right" role="navigation" aria-label="{{ __('Header actions') }}">

                {{-- Website link --}}
                <a href="{{ route('players.index') }}"
                   target="_blank"
                   class="header-action-btn"
                   aria-label="{{ __('View Website') }}"
                   title="{{ __('View Website') }}">
                    <i class="fas fa-external-link-alt"></i>
                </a>

                {{-- Notifications --}}
                @php
                    $pendingPlayers    = \App\Models\Player::where('registration_status', 'Pending')->count();
                    $pendingPartners   = \App\Models\User::where('user_type', 'partner')->where('approval_status', 'pending')->count();
                    $totalNotifications = $pendingPlayers + $pendingPartners;
                @endphp
                <div class="dropdown">
                    <button class="header-action-btn" data-bs-toggle="dropdown" aria-label="{{ __('Notifications') }}">
                        <i class="fas fa-bell"></i>
                        @if($totalNotifications > 0)
                            <span class="notification-badge">{{ $totalNotifications }}</span>
                        @endif
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><h6 class="dropdown-header">{{ __('Notifications') }}</h6></li>
                        @if($pendingPlayers > 0)
                            <li>
                                <a class="dropdown-item" href="{{ route('admin.players.index') }}">
                                    <i class="fas fa-user-plus text-success me-2"></i>
                                    {{ $pendingPlayers }} {{ __('pending players') }}
                                </a>
                            </li>
                        @endif
                        @if($totalNotifications === 0)
                            <li><span class="dropdown-item text-muted">{{ __('No notifications') }}</span></li>
                        @endif
                    </ul>
                </div>

                {{-- User menu (far right) --}}
                <div class="dropdown user-dropdown">
                    <button class="admin-user-menu" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="admin-user-avatar">{{ substr(Auth::user()->name, 0, 1) }}</div>
                        <span class="admin-user-name">{{ Auth::user()->name }}</span>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                <i class="fas fa-user me-2"></i>{{ __('Profile') }}
                            </a>
                        </li>
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

            </nav>
        </div>
    </header>

    {{-- ===================== LEFT SIDEBAR ===================== --}}
    <aside class="admin-sidebar" id="adminSidebar" role="navigation" aria-label="{{ __('Main navigation') }}">
        <nav class="sidebar-nav">

            {{-- Accordion mode toggle --}}
            <div class="accordion-settings">
                <span class="accordion-settings-label">{{ __('One section at a time') }}</span>
                <label class="accordion-toggle-switch">
                    <input type="checkbox" id="accordionMode" onchange="toggleAccordionMode()">
                    <span class="accordion-toggle-slider"></span>
                </label>
            </div>

            {{-- Dashboard (direct link) --}}
            <div class="sidebar-accordion">
                <a href="{{ route('admin.dashboard') }}"
                   class="sidebar-accordion-header {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <span><i class="fas fa-th-large"></i> {{ __('Dashboard') }}</span>
                </a>
            </div>

            {{-- Players --}}
            <div class="sidebar-accordion" data-accordion="players">
                <button class="sidebar-accordion-header {{ request()->routeIs('admin.players.*', 'admin.attendance.*', 'admin.training-sessions.*', 'admin.game-statistics.*', 'admin.website-players.*') ? 'active' : '' }}"
                        type="button"
                        onclick="toggleAccordion('players')">
                    <span><i class="fas fa-users"></i> {{ __('Players') }}</span>
                    <span class="sidebar-accordion-icon"></span>
                </button>
                <div class="sidebar-accordion-content">
                    @php $pending = \App\Models\Player::where('registration_status', 'Pending')->count(); @endphp
                    <a href="{{ route('admin.players.index') }}" class="sidebar-link {{ request()->routeIs('admin.players.*') ? 'active' : '' }}">
                        <i class="fas fa-user"></i><span>{{ __('All Players') }}</span>
                        @if($pending > 0)<span class="sidebar-badge">{{ $pending }}</span>@endif
                    </a>
                    <a href="{{ route('admin.attendance.index') }}" class="sidebar-link {{ request()->routeIs('admin.attendance.*') ? 'active' : '' }}">
                        <i class="fas fa-calendar-check"></i><span>{{ __('Attendance') }}</span>
                    </a>
                    <a href="{{ route('admin.training-sessions.index') }}" class="sidebar-link {{ request()->routeIs('admin.training-sessions.*') ? 'active' : '' }}">
                        <i class="fas fa-stopwatch"></i><span>{{ __('Training') }}</span>
                    </a>
                    <a href="{{ route('admin.game-statistics.index') }}" class="sidebar-link {{ request()->routeIs('admin.game-statistics.*') ? 'active' : '' }}">
                        <i class="fas fa-chart-bar"></i><span>{{ __('Statistics') }}</span>
                    </a>
                    <a href="{{ route('admin.website-players.index') }}" class="sidebar-link {{ request()->routeIs('admin.website-players.*') ? 'active' : '' }}">
                        <i class="fas fa-globe"></i><span>{{ __('Website Players') }}</span>
                    </a>
                </div>
            </div>

            {{-- Communication --}}
            <div class="sidebar-accordion" data-accordion="communication">
                <button class="sidebar-accordion-header {{ request()->routeIs('admin.blog.*', 'admin.sms.*', 'admin.whatsapp.*', 'admin.messaging.*') ? 'active' : '' }}"
                        type="button"
                        onclick="toggleAccordion('communication')">
                    <span><i class="fas fa-bullhorn"></i> {{ __('Communication') }}</span>
                    <span class="sidebar-accordion-icon"></span>
                </button>
                <div class="sidebar-accordion-content">
                    <a href="{{ route('admin.messaging.quick') }}" class="sidebar-link {{ request()->routeIs('admin.messaging.quick') ? 'active' : '' }}">
                        <i class="fas fa-paper-plane"></i><span>{{ __('Quick Messaging') }}</span>
                    </a>
                    <a href="{{ route('admin.messaging.settings') }}" class="sidebar-link {{ request()->routeIs('admin.messaging.settings') ? 'active' : '' }}">
                        <i class="fas fa-cog"></i><span>{{ __('Message Settings') }}</span>
                    </a>
                    <a href="{{ route('admin.blog.index') }}" class="sidebar-link {{ request()->routeIs('admin.blog.*') ? 'active' : '' }}">
                        <i class="fas fa-newspaper"></i><span>{{ __('Announcements') }}</span>
                    </a>
                    <a href="{{ route('admin.sms.index') }}" class="sidebar-link {{ request()->routeIs('admin.sms.*') ? 'active' : '' }}">
                        <i class="fas fa-sms"></i><span>{{ __('Bulk SMS') }}</span>
                    </a>
                    <a href="{{ route('admin.whatsapp.index') }}" class="sidebar-link {{ request()->routeIs('admin.whatsapp.*') ? 'active' : '' }}">
                        <i class="fab fa-whatsapp"></i><span>{{ __('WhatsApp') }}</span>
                    </a>
                </div>
            </div>

            {{-- Competition --}}
            <div class="sidebar-accordion" data-accordion="competition">
                <button class="sidebar-accordion-header {{ request()->routeIs('admin.matches.*', 'admin.standings.*', 'admin.tournaments.*', 'super-admin.matches.*', 'super-admin.standings.*', 'super-admin.tournaments.*') ? 'active' : '' }}"
                        type="button"
                        onclick="toggleAccordion('competition')">
                    <span><i class="fas fa-futbol"></i> {{ __('Competition') }}</span>
                    <span class="sidebar-accordion-icon"></span>
                </button>
                <div class="sidebar-accordion-content">
                    <a href="{{ route('super-admin.tournaments.overview') }}" class="sidebar-link {{ request()->routeIs('super-admin.tournaments.overview') ? 'active' : '' }}">
                        <i class="fas fa-chart-line"></i><span>{{ __('Tournament Overview') }}</span>
                    </a>
                    <a href="{{ route(auth()->user()->hasRole('super-admin') ? 'super-admin.tournaments.index' : 'admin.tournaments.index') }}"
                       class="sidebar-link {{ request()->routeIs('admin.tournaments.*', 'super-admin.tournaments.*') ? 'active' : '' }}">
                        <i class="fas fa-trophy"></i><span>{{ __('Tournaments') }}</span>
                    </a>
                    <a href="{{ route('admin.matches.index') }}" class="sidebar-link {{ request()->routeIs('admin.matches.*') ? 'active' : '' }}">
                        <i class="fas fa-futbol"></i><span>{{ __('Matches') }}</span>
                    </a>
                    <a href="{{ route('admin.standings.index') }}" class="sidebar-link {{ request()->routeIs('admin.standings.*') ? 'active' : '' }}">
                        <i class="fas fa-list-ol"></i><span>{{ __('Standings') }}</span>
                    </a>
                </div>
            </div>

            {{-- Finance (conditional) --}}
            @if(auth()->user()->hasPermission('view_financials') || auth()->user()->hasPermission('finance.view') || auth()->user()->hasAnyRole(['finance-officer', 'finance-admin', 'super-admin']))
            @php $isSuperAdmin = auth()->user()->hasRole('super-admin'); @endphp
            <div class="sidebar-accordion" data-accordion="finance">
                <button class="sidebar-accordion-header {{ request()->routeIs('admin.payments.*', 'admin.payment-categories.*', 'finance.*') ? 'active' : '' }}"
                        type="button"
                        onclick="toggleAccordion('finance')">
                    <span><i class="fas fa-credit-card"></i> {{ __('Finance') }}</span>
                    <span class="sidebar-accordion-icon"></span>
                </button>
                <div class="sidebar-accordion-content">
                    <a href="{{ route('admin.payments.index') }}" class="sidebar-link {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
                        <i class="fas fa-money-bill-wave"></i><span>{{ __('Payments (Revenue)') }}</span>
                    </a>
                    <a href="{{ route('admin.payment-categories.index') }}" class="sidebar-link {{ request()->routeIs('admin.payment-categories.*') ? 'active' : '' }}">
                        <i class="fas fa-tags"></i><span>{{ __('Payment Categories') }}</span>
                    </a>
                    <div class="sidebar-divider"></div>
                    <a href="{{ route($isSuperAdmin ? 'super-admin.finance.budgets.summary' : 'finance.budgets.summary') }}"
                       class="sidebar-link {{ request()->routeIs('finance.budgets.summary') ? 'active' : '' }}">
                        <i class="fas fa-chart-pie"></i><span>{{ __('Budget Summary') }}</span>
                    </a>
                    <a href="{{ route($isSuperAdmin ? 'super-admin.finance.budgets.index' : 'finance.budgets.index') }}"
                       class="sidebar-link {{ request()->routeIs('finance.budgets.index', 'finance.budgets.create', 'finance.budgets.show', 'finance.budgets.edit') ? 'active' : '' }}">
                        <i class="fas fa-calculator"></i><span>{{ __('Budget Plans') }}</span>
                    </a>
                    <a href="{{ route($isSuperAdmin ? 'super-admin.finance.budgets.comparison' : 'finance.budgets.comparison') }}"
                       class="sidebar-link {{ request()->routeIs('finance.budgets.comparison') ? 'active' : '' }}">
                        <i class="fas fa-balance-scale"></i><span>{{ __('Budget vs Actual') }}</span>
                    </a>
                    <div class="sidebar-divider"></div>
                    <a href="{{ route($isSuperAdmin ? 'super-admin.finance.expenses.index' : 'finance.expenses.index') }}"
                       class="sidebar-link {{ request()->routeIs('finance.expenses.index', 'finance.expenses.create', 'finance.expenses.show', 'finance.expenses.edit') ? 'active' : '' }}">
                        <i class="fas fa-receipt"></i><span>{{ __('Expenses') }}</span>
                    </a>
                    <a href="{{ route($isSuperAdmin ? 'super-admin.finance.expenses.report' : 'finance.expenses.report') }}"
                       class="sidebar-link {{ request()->routeIs('finance.expenses.report') ? 'active' : '' }}">
                        <i class="fas fa-chart-bar"></i><span>{{ __('Expense Reports') }}</span>
                    </a>
                </div>
            </div>
            @endif

            {{-- Academy --}}
            @php $isSuperAdmin = auth()->user()->hasRole('super-admin'); @endphp
            <div class="sidebar-accordion" data-accordion="academy">
                <button class="sidebar-accordion-header {{ request()->routeIs('admin.programs.*', 'admin.teams.*', 'admin.staff.*', 'admin.partners.*', 'admin.performance.*', 'admin.compliance.*', 'admin.equipment.*', 'admin.letterhead.*') ? 'active' : '' }}"
                        type="button"
                        onclick="toggleAccordion('academy')">
                    <span><i class="fas fa-graduation-cap"></i> {{ __('Academy') }}</span>
                    <span class="sidebar-accordion-icon"></span>
                </button>
                <div class="sidebar-accordion-content">
                    <a href="{{ route('admin.programs.index') }}" class="sidebar-link {{ request()->routeIs('admin.programs.*') ? 'active' : '' }}">
                        <i class="fas fa-football-ball"></i><span>{{ __('Programs') }}</span>
                    </a>
                    <a href="{{ route('admin.teams.index') }}" class="sidebar-link {{ request()->routeIs('admin.teams.*') ? 'active' : '' }}">
                        <i class="fas fa-shield-alt"></i><span>{{ __('Teams') }}</span>
                    </a>
                    <a href="{{ route('admin.staff.index') }}" class="sidebar-link {{ request()->routeIs('admin.staff.*') ? 'active' : '' }}">
                        <i class="fas fa-users-cog"></i><span>{{ __('Staff') }}</span>
                    </a>
                    <a href="{{ route('admin.partners.index') }}" class="sidebar-link {{ request()->routeIs('admin.partners.*') ? 'active' : '' }}">
                        <i class="fas fa-handshake"></i><span>{{ __('Partners') }}</span>
                    </a>
                    <a href="{{ route('manager.equipment.categories') }}" class="sidebar-link {{ request()->routeIs('manager.equipment.*') ? 'active' : '' }}">
                        <i class="fas fa-boxes"></i><span>{{ __('Equipment') }}</span>
                    </a>
                    <a href="{{ route($isSuperAdmin ? 'super-admin.letterhead.index' : 'admin.letterhead.index') }}"
                       class="sidebar-link {{ request()->routeIs('admin.letterhead.*', 'super-admin.letterhead.*') ? 'active' : '' }}">
                        <i class="fas fa-file-signature"></i><span>{{ __('Letterhead') }}</span>
                    </a>
                    <div class="sidebar-divider"></div>
                    <a href="{{ route('admin.performance.overview') }}" class="sidebar-link {{ request()->routeIs('admin.performance.overview') ? 'active' : '' }}">
                        <i class="fas fa-chart-line"></i><span>{{ __('Analytics') }}</span>
                    </a>
                    <a href="{{ route('admin.compliance.report') }}" class="sidebar-link {{ request()->routeIs('admin.compliance.report') ? 'active' : '' }}">
                        <i class="fas fa-shield-alt"></i><span>{{ __('Compliance') }}</span>
                    </a>
                </div>
            </div>

            {{-- Content --}}
            <div class="sidebar-accordion" data-accordion="content">
                <button class="sidebar-accordion-header {{ request()->routeIs('admin.website-players.*', 'admin.jobs.*', 'admin.documents.*') ? 'active' : '' }}"
                        type="button"
                        onclick="toggleAccordion('content')">
                    <span><i class="fas fa-images"></i> {{ __('Content') }}</span>
                    <span class="sidebar-accordion-icon"></span>
                </button>
                <div class="sidebar-accordion-content">
                    <a href="{{ route('admin.website-players.index') }}" class="sidebar-link {{ request()->routeIs('admin.website-players.*') ? 'active' : '' }}">
                        <i class="fas fa-users"></i><span>{{ __('Player Gallery') }}</span>
                    </a>
                    <a href="{{ route('admin.jobs.index') }}" class="sidebar-link {{ request()->routeIs('admin.jobs.*') ? 'active' : '' }}">
                        <i class="fas fa-briefcase"></i><span>{{ __('Careers') }}</span>
                    </a>
                    <a href="{{ route('admin.documents.index') }}" class="sidebar-link {{ request()->routeIs('admin.documents.*') ? 'active' : '' }}">
                        <i class="fas fa-file-alt"></i><span>{{ __('Documents') }}</span>
                    </a>
                </div>
            </div>

            {{-- System (super-admin only) --}}
            @if(auth()->user()->hasRole('super-admin'))
            <div class="sidebar-accordion" data-accordion="system">
                <button class="sidebar-accordion-header {{ request()->routeIs('super-admin.*') ? 'active' : '' }}"
                        type="button"
                        onclick="toggleAccordion('system')">
                    <span><i class="fas fa-cog"></i> {{ __('System') }}</span>
                    <span class="sidebar-accordion-icon"></span>
                </button>
                <div class="sidebar-accordion-content">
                    <a href="{{ url('/super-admin/organizations') }}" class="sidebar-link {{ request()->is('super-admin/organizations*') ? 'active' : '' }}">
                        <i class="fas fa-chart-line"></i><span>{{ __('Organization Management') }}</span>
                    </a>
                    <a href="{{ route('super-admin.organizations.index') }}" class="sidebar-link {{ request()->routeIs('super-admin.organizations.*') ? 'active' : '' }}">
                        <i class="fas fa-building"></i><span>{{ __('Organizations') }}</span>
                    </a>
                    <a href="{{ route('super-admin.users.index') }}" class="sidebar-link {{ request()->routeIs('super-admin.users.*') ? 'active' : '' }}">
                        <i class="fas fa-users"></i><span>{{ __('Users') }}</span>
                    </a>
                    <a href="{{ route('super-admin.plans.index') }}" class="sidebar-link {{ request()->routeIs('super-admin.plans.*') ? 'active' : '' }}">
                        <i class="fas fa-tags"></i><span>{{ __('Plans') }}</span>
                    </a>
                    <a href="{{ route('super-admin.roles.index') }}" class="sidebar-link {{ request()->routeIs('super-admin.roles.*') ? 'active' : '' }}">
                        <i class="fas fa-user-shield"></i><span>{{ __('Roles') }}</span>
                    </a>
                </div>
            </div>

            {{-- Website (super-admin only) --}}
            <div class="sidebar-accordion" data-accordion="website">
                <button class="sidebar-accordion-header {{ request()->routeIs('admin.page-content.*', 'leaders.*', 'admin.jobs.*', 'admin.blog.*') ? 'active' : '' }}"
                        type="button"
                        onclick="toggleAccordion('website')">
                    <span><i class="fas fa-globe"></i> {{ __('Website') }}</span>
                    <span class="sidebar-accordion-icon"></span>
                </button>
                <div class="sidebar-accordion-content">
                    <a href="{{ route('admin.page-content.index') }}" class="sidebar-link {{ request()->routeIs('admin.page-content.index') ? 'active' : '' }}">
                        <i class="fas fa-list-alt"></i><span>{{ __('All Pages') }}</span>
                    </a>
                    @foreach([
                        'home'          => ['fas fa-home',          __('Home Page')],
                        'about'         => ['fas fa-info-circle',    __('About Us')],
                        'programs'      => ['fas fa-clipboard-list', __('Programs Page')],
                        'announcements' => ['fas fa-bullhorn',       __('Announcements Page')],
                        'careers'       => ['fas fa-briefcase',      __('Careers Page')],
                    ] as $page => [$icon, $label])
                    <a href="{{ route('admin.page-content.show', ['page' => $page]) }}"
                       class="sidebar-link {{ request()->routeIs('admin.page-content.show') && request()->route('page') == $page ? 'active' : '' }}">
                        <i class="{{ $icon }}"></i><span>{{ $label }}</span>
                    </a>
                    @endforeach
                    <div class="sidebar-divider"></div>
                    <div class="sidebar-section-label">{{ __('Dynamic Content') }}</div>
                    <a href="{{ route('admin.leaders.index') }}" class="sidebar-link {{ request()->routeIs('admin.leaders.*') ? 'active' : '' }}">
                        <i class="fas fa-star"></i><span>{{ __('Leaders') }}</span>
                    </a>
                </div>
            </div>
            @endif

        </nav>
    </aside>

    {{-- ===================== MAIN CONTENT ===================== --}}
    <main class="admin-content">

        @if(session('success'))
        <div class="alert-custom success">
            <i class="fas fa-check-circle"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close ms-auto" onclick="this.parentElement.remove()"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert-custom error">
            <i class="fas fa-exclamation-circle"></i>
            <div>{{ session('error') }}</div>
            <button type="button" class="btn-close ms-auto" onclick="this.parentElement.remove()"></button>
        </div>
        @endif

        @auth
            @include('components.trial-notification')
        @endauth

        @yield('content')

        <footer class="admin-footer">
            <div class="admin-footer-content">
                <div>&copy; {{ date('Y') }} {{ __('GameSuite') }}. {{ __('All rights reserved.') }}</div>
            </div>
        </footer>

    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // ── Accordion ─────────────────────────────────────────────
        let oneSectionOpen = localStorage.getItem('sidebarOneSectionOpen') !== 'false';
        document.getElementById('accordionMode').checked = oneSectionOpen;

        function toggleAccordionMode() {
            oneSectionOpen = document.getElementById('accordionMode').checked;
            localStorage.setItem('sidebarOneSectionOpen', oneSectionOpen);
        }

        function toggleAccordion(name) {
            const accordion = document.querySelector(`[data-accordion="${name}"]`);
            const isOpen = accordion.classList.contains('open');

            if (oneSectionOpen && !isOpen) {
                document.querySelectorAll('.sidebar-accordion.open')
                    .forEach(el => el.classList.remove('open'));
            }

            accordion.classList.toggle('open');
            saveAccordionState();
        }

        function saveAccordionState() {
            const open = [...document.querySelectorAll('.sidebar-accordion.open')]
                .map(el => el.dataset.accordion);
            localStorage.setItem('sidebarOpenAccordions', JSON.stringify(open));
        }

        function loadAccordionState() {
            const saved = localStorage.getItem('sidebarOpenAccordions');
            if (saved) {
                JSON.parse(saved).forEach(name => {
                    const el = document.querySelector(`[data-accordion="${name}"]`);
                    if (el) el.classList.add('open');
                });
            } else {
                const activeLink = document.querySelector('.sidebar-link.active');
                if (activeLink) {
                    const content = activeLink.closest('.sidebar-accordion-content');
                    if (content) content.parentElement.classList.add('open');
                }
            }
        }

        // ── Mobile sidebar ─────────────────────────────────────────
        function toggleSidebar() {
            const sidebar   = document.getElementById('adminSidebar');
            const toggleBtn = document.getElementById('mobileSidebarToggle');
            const isExpanded = sidebar.classList.contains('show');
            sidebar.classList.toggle('show');
            if (toggleBtn) toggleBtn.setAttribute('aria-expanded', !isExpanded);
        }

        // ── Init ───────────────────────────────────────────────────
        document.addEventListener('DOMContentLoaded', function () {
            loadAccordionState();

            const toggleBtn = document.getElementById('mobileSidebarToggle');
            const sidebar   = document.getElementById('adminSidebar');

            if (toggleBtn && sidebar) {
                document.addEventListener('click', function (e) {
                    if (!sidebar.contains(e.target) && !toggleBtn.contains(e.target)) {
                        sidebar.classList.remove('show');
                        toggleBtn.setAttribute('aria-expanded', 'false');
                    }
                });

                document.addEventListener('keydown', function (e) {
                    if (e.key === 'Escape' && sidebar.classList.contains('show')) {
                        sidebar.classList.remove('show');
                        toggleBtn.setAttribute('aria-expanded', 'false');
                        toggleBtn.focus();
                    }
                });
            }

            // Auto-dismiss alerts after 5 s
            document.querySelectorAll('.alert-custom').forEach(alert => {
                setTimeout(() => {
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 300);
                }, 5000);
            });
        });
    </script>

    @stack('scripts')
</body>
</html>
