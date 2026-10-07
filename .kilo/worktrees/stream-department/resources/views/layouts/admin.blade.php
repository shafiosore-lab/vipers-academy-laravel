<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', __('Admin Dashboard - Vipers Academy'))</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
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
        --gray-100: #f5f5f5;
        --bg-light: #f7f7f7;
        --white: #fff;
        --shadow-sm: 0 1px 2px rgba(0, 0, 0, 0.05);
        --shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        --shadow-lg: 0 10px 15px rgba(0, 0, 0, 0.1);
        --transition: 0.2s ease;
        --header-height: 60px;
        --sidebar-width: 260px;
    }

    * { margin: 0; padding: 0; box-sizing: border-box; }

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

    /* Top Header */
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
        padding: 0 1.5rem;
    }

    .admin-brand {
        display: flex;
        align-items: center;
        gap: 10px;
        text-decoration: none;
    }

    .admin-logo {
        width: 36px;
        height: 36px;
        object-fit: contain;
        border-radius: 6px;
    }

    .admin-brand-text h5 {
        font-size: 16px;
        font-weight: 700;
        color: var(--primary);
        margin: 0;
    }

    .admin-brand-text small {
        font-size: 10px;
        color: #999;
        text-transform: uppercase;
    }

    .header-actions {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-left: auto;
    }

    .header-action-btn {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        background: #f8f9fa;
        border: none;
        color: var(--gray-600);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        position: relative;
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
        width: 16px;
        height: 16px;
        font-size: 9px;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid var(--white);
    }

    .admin-user-menu {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 4px 10px;
        background: #f8f9fa;
        border-radius: 20px;
        cursor: pointer;
    }

    .admin-user-avatar {
        width: 28px;
        height: 28px;
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
    }

    /* Left Sidebar */
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
        box-shadow: 2px 0 10px rgba(0,0,0,0.05);
    }

    .admin-sidebar::-webkit-scrollbar { width: 4px; }
    .admin-sidebar::-webkit-scrollbar-thumb { background: var(--gray-300); border-radius: 2px; }

    .sidebar-nav { padding: 0.25rem 0; }

    /* Accordion Styles */
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

    .sidebar-accordion-header:hover {
        background: #f8f9fa;
        color: var(--primary);
    }

    .sidebar-accordion-header.active {
        background: #fff5f0;
        color: var(--primary);
    }

    .sidebar-accordion-header i {
        width: 18px;
        font-size: 13px;
        margin-right: 8px;
    }

    .sidebar-accordion-icon {
        font-size: 11px;
        color: var(--gray-600);
        transition: all 0.3s ease;
        font-weight: bold;
    }

    .sidebar-accordion-header.active .sidebar-accordion-icon {
        color: var(--primary);
    }

    .sidebar-accordion-header.active .sidebar-accordion-icon::before {
        content: "\2212";
    }

    .sidebar-accordion:not(.open) .sidebar-accordion-icon::before {
        content: "\002B";
    }

    .sidebar-accordion-content {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease;
    }

    .sidebar-accordion.open .sidebar-accordion-content {
        max-height: 500px;
    }

    .sidebar-accordion-content .sidebar-link {
        padding-left: 2.5rem;
    }

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

    .sidebar-link:hover {
        background: #f8f9fa;
        color: var(--primary);
    }

    .sidebar-link.active {
        background: #fff5f0;
        color: var(--primary);
        border-left-color: var(--primary);
        font-weight: 600;
    }

    .sidebar-link i {
        width: 16px;
        font-size: 13px;
        text-align: center;
    }

    .sidebar-badge {
        margin-left: auto;
        background: var(--danger);
        color: var(--white);
        font-size: 9px;
        font-weight: 600;
        padding: 2px 6px;
        border-radius: 10px;
    }

    /* Accordion Toggle Setting */
    .accordion-settings {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 1rem;
        margin-bottom: 8px;
        background: #f8f9fa;
        border-bottom: 1px solid var(--gray-300);
    }

    .accordion-settings-label {
        font-size: 11px;
        color: #999;
        font-weight: 500;
    }

    .accordion-toggle-switch {
        position: relative;
        width: 36px;
        height: 20px;
    }

    .accordion-toggle-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .accordion-toggle-slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: 0.3s;
        border-radius: 20px;
    }

    .accordion-toggle-slider:before {
        position: absolute;
        content: "";
        height: 14px;
        width: 14px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: 0.3s;
        border-radius: 50%;
    }

    .accordion-toggle-switch input:checked + .accordion-toggle-slider {
        background-color: var(--primary);
    }

    .accordion-toggle-switch input:checked + .accordion-toggle-slider:before {
        transform: translateX(16px);
    }

    /* Main Content - Right Side */
    .admin-content {
        margin-top: var(--header-height);
        margin-left: var(--sidebar-width);
        padding: 20px;
        min-height: calc(100vh - var(--header-height));
    }

    /* Alerts */
    .alert-custom {
        border-radius: 8px;
        padding: 1rem;
        border: 1px solid transparent;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .alert-custom.success { background: #f0fdf4; border-color: #bbf7d0; color: var(--secondary); }
    .alert-custom.error { background: #fef2f2; border-color: #fecaca; color: var(--danger); }

    /* Footer */
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

    /* Dropdown */
    .dropdown-menu {
        border-radius: 8px;
        border: 1px solid var(--gray-300);
        box-shadow: var(--shadow-lg);
        padding: 0.5rem;
    }

    .dropdown-item {
        border-radius: 6px;
        padding: 8px 12px;
        font-size: 13px;
    }

    .dropdown-item:hover {
        background: #f8f9fa;
        color: var(--primary);
    }

    .dropdown-header {
        font-size: 11px;
        font-weight: 600;
        color: #999;
        text-transform: uppercase;
    }

    /* Responsive */
    @media (max-width: 991.98px) {
        .admin-sidebar {
            transform: translateX(-100%);
            transition: transform 0.3s ease;
        }
        .admin-sidebar.show { transform: translateX(0); }
        .admin-content { margin-left: 0; }

        .sidebar-toggle-mobile {
            display: block !important;
        }
    }

    .sidebar-toggle-mobile {
        display: none;
        background: none;
        border: none;
        font-size: 20px;
        color: var(--gray-600);
        cursor: pointer;
    }

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
    </style>

    @stack('styles')
</head>

<body>
    {{-- Top Header --}}
    <header class="top-header">
        <div class="container-fluid">
            <button class="sidebar-toggle-mobile me-3" onclick="toggleSidebar()">
                <i class="fas fa-bars"></i>
            </button>

            @php
                $dashboardRoute = route('admin.dashboard');
                $user = auth()->user();
                if ($user->hasAnyRole(['coach', 'head-coach', 'assistant-coach'])) {
                    $dashboardRoute = route('coach.dashboard');
                } elseif ($user->hasRole('team-manager')) {
                    $dashboardRoute = route('manager.dashboard');
                } elseif ($user->hasRole('finance-officer')) {
                    $dashboardRoute = route('finance.dashboard');
                }
            @endphp

            <a href="{{ $dashboardRoute }}" class="admin-brand">
                <img src="{{ asset('assets/img/logo/vps.jpeg') }}" alt="Logo" class="admin-logo">
                <div class="admin-brand-text">
                    <h5>{{ __('Vipers Academy') }}</h5>
                    <small>
                        @if(auth()->user()->hasRole('super-admin')){{ __('Super Admin') }}
                        @else{{ __('Admin Panel') }}
                        @endif
                    </small>
                </div>
            </a>

            <nav class="header-actions">
                <div class="dropdown">
                    <button class="header-action-btn" data-bs-toggle="dropdown">
                        <i class="fas fa-bell"></i>
                        @php
                        $pendingPlayers = \App\Models\Player::where('registration_status', 'Pending')->count();
                        $pendingPartners = \App\Models\User::where('user_type', 'partner')->where('approval_status', 'pending')->count();
                        $totalNotifications = $pendingPlayers + $pendingPartners;
                        @endphp
                        @if($totalNotifications > 0)
                        <span class="notification-badge">{{ $totalNotifications }}</span>
                        @endif
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><h6 class="dropdown-header">{{ __('Notifications') }}</h6></li>
                        @if($pendingPlayers > 0)
                        <li><a class="dropdown-item" href="{{ route('admin.players.index') }}"><i class="fas fa-user-plus text-success me-2"></i>{{ $pendingPlayers }} {{ __('pending') }}</a></li>
                        @endif
                        @if($totalNotifications === 0)
                        <li><span class="dropdown-item text-muted">{{ __('No notifications') }}</span></li>
                        @endif
                    </ul>
                </div>

                <div class="dropdown">
                    <button class="admin-user-menu" data-bs-toggle="dropdown">
                        <div class="admin-user-avatar">{{ substr(Auth::user()->name, 0, 1) }}</div>
                        <span class="admin-user-name">{{ Auth::user()->name }}</span>
                        <i class="fas fa-chevron-down" style="font-size: 10px; color: #999;"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="fas fa-user me-2"></i>{{ __('Profile') }}</a></li>
                        <li><a class="dropdown-item" href="{{ route('players.index') }}" target="_blank"><i class="fas fa-external-link-alt me-2"></i>{{ __('Website') }}</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger"><i class="fas fa-sign-out-alt me-2"></i>{{ __('Logout') }}</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </header>

    {{-- Left Sidebar with Accordion --}}
    <aside class="admin-sidebar" id="adminSidebar">
        <nav class="sidebar-nav">
            {{-- Accordion Settings Toggle --}}
            <div class="accordion-settings">
                <span class="accordion-settings-label">{{ __('One section at a time') }}</span>
                <label class="accordion-toggle-switch">
                    <input type="checkbox" id="accordionMode" onchange="toggleAccordionMode()">
                    <span class="accordion-toggle-slider"></span>
                </label>
            </div>

            {{-- Dashboard (Direct Link) --}}
            <div class="sidebar-accordion">
                <a href="{{ route('admin.dashboard') }}" class="sidebar-accordion-header {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <span><i class="fas fa-th-large"></i> {{ __('Dashboard') }}</span>
                </a>
            </div>

            {{-- Players Accordion --}}
            <div class="sidebar-accordion" data-accordion="players">
                <button class="sidebar-accordion-header {{ request()->routeIs('admin.players.*', 'admin.attendance.*', 'admin.training-sessions.*', 'admin.game-statistics.*') ? 'active' : '' }}" onclick="toggleAccordion('players')">
                    <span><i class="fas fa-users"></i> {{ __('Players') }}</span>
                    <span class="sidebar-accordion-icon"></span>
                </button>
                <div class="sidebar-accordion-content">
                    <a href="{{ route('admin.players.index') }}" class="sidebar-link {{ request()->routeIs('admin.players.*') ? 'active' : '' }}">
                        <i class="fas fa-user"></i>
                        <span>{{ __('All Players') }}</span>
                        @php $pending = \App\Models\Player::where('registration_status', 'Pending')->count(); @endphp
                        @if($pending > 0)<span class="sidebar-badge">{{ $pending }}</span>@endif
                    </a>
                    <a href="{{ route('admin.attendance.index') }}" class="sidebar-link {{ request()->routeIs('admin.attendance.*') ? 'active' : '' }}">
                        <i class="fas fa-calendar-check"></i>
                        <span>{{ __('Attendance') }}</span>
                    </a>
                    <a href="{{ route('admin.training-sessions.index') }}" class="sidebar-link {{ request()->routeIs('admin.training-sessions.*') ? 'active' : '' }}">
                        <i class="fas fa-stopwatch"></i>
                        <span>{{ __('Training') }}</span>
                    </a>
                    <a href="{{ route('admin.game-statistics.index') }}" class="sidebar-link {{ request()->routeIs('admin.game-statistics.*') ? 'active' : '' }}">
                        <i class="fas fa-chart-bar"></i>
                        <span>{{ __('Statistics') }}</span>
                    </a>
                </div>
            </div>

            {{-- Communication Accordion --}}
            <div class="sidebar-accordion" data-accordion="communication">
                <button class="sidebar-accordion-header {{ request()->routeIs('admin.blog.*', 'admin.sms.*', 'admin.whatsapp.*', 'admin.messaging.*') ? 'active' : '' }}" onclick="toggleAccordion('communication')">
                    <span><i class="fas fa-bullhorn"></i> {{ __('Communication') }}</span>
                    <span class="sidebar-accordion-icon"></span>
                </button>
                <div class="sidebar-accordion-content">
                    <a href="{{ route('admin.messaging.quick') }}" class="sidebar-link {{ request()->routeIs('admin.messaging.quick') ? 'active' : '' }}">
                        <i class="fas fa-paper-plane"></i>
                        <span>{{ __('Quick Messaging') }}</span>
                    </a>
                    <a href="{{ route('admin.messaging.settings') }}" class="sidebar-link {{ request()->routeIs('admin.messaging.settings') ? 'active' : '' }}">
                        <i class="fas fa-cog"></i>
                        <span>{{ __('Message Settings') }}</span>
                    </a>
                    <a href="{{ route('admin.blog.index') }}" class="sidebar-link {{ request()->routeIs('admin.blog.*') ? 'active' : '' }}">
                        <i class="fas fa-newspaper"></i>
                        <span>{{ __('Announcements') }}</span>
                    </a>
                    <a href="{{ route('admin.sms.index') }}" class="sidebar-link {{ request()->routeIs('admin.sms.*') ? 'active' : '' }}">
                        <i class="fas fa-sms"></i>
                        <span>{{ __('Bulk SMS') }}</span>
                    </a>
                    <a href="{{ route('admin.whatsapp.index') }}" class="sidebar-link {{ request()->routeIs('admin.whatsapp.*') ? 'active' : '' }}">
                        <i class="fab fa-whatsapp"></i>
                        <span>{{ __('WhatsApp') }}</span>
                    </a>
                </div>
            </div>

            {{-- Competition Accordion --}}
            <div class="sidebar-accordion" data-accordion="competition">
                <button class="sidebar-accordion-header {{ request()->routeIs('admin.matches.*', 'admin.standings.*') ? 'active' : '' }}" onclick="toggleAccordion('competition')">
                    <span><i class="fas fa-futbol"></i> {{ __('Competition') }}</span>
                    <span class="sidebar-accordion-icon"></span>
                </button>
                <div class="sidebar-accordion-content">
                    <a href="{{ route('admin.matches.index') }}" class="sidebar-link {{ request()->routeIs('admin.matches.*') ? 'active' : '' }}">
                        <i class="fas fa-futbol"></i>
                        <span>{{ __('Matches') }}</span>
                    </a>
                    <a href="{{ route('admin.standings.index') }}" class="sidebar-link {{ request()->routeIs('admin.standings.*') ? 'active' : '' }}">
                        <i class="fas fa-trophy"></i>
                        <span>{{ __('Standings') }}</span>
                    </a>
                </div>
            </div>

            {{-- Finance Accordion --}}
            <div class="sidebar-accordion" data-accordion="finance">
                <button class="sidebar-accordion-header {{ request()->routeIs('admin.payments.*', 'admin.payment-categories.*') ? 'active' : '' }}" onclick="toggleAccordion('finance')">
                    <span><i class="fas fa-credit-card"></i> {{ __('Finance') }}</span>
                    <span class="sidebar-accordion-icon"></span>
                </button>
                <div class="sidebar-accordion-content">
                    <a href="{{ route('admin.payments.index') }}" class="sidebar-link {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
                        <i class="fas fa-credit-card"></i>
                        <span>{{ __('Payments') }}</span>
                    </a>
                    <a href="{{ route('admin.payment-categories.index') }}" class="sidebar-link {{ request()->routeIs('admin.payment-categories.*') ? 'active' : '' }}">
                        <i class="fas fa-tags"></i>
                        <span>{{ __('Categories') }}</span>
                    </a>
                </div>
            </div>

            {{-- Academy Accordion (includes Analytics) --}}
            <div class="sidebar-accordion" data-accordion="academy">
                <button class="sidebar-accordion-header {{ request()->routeIs('admin.programs.*', 'admin.teams.*', 'admin.staff.*', 'admin.partners.*', 'admin.performance.*', 'admin.compliance.*') ? 'active' : '' }}" onclick="toggleAccordion('academy')">
                    <span><i class="fas fa-graduation-cap"></i> {{ __('Academy') }}</span>
                    <span class="sidebar-accordion-icon"></span>
                </button>
                <div class="sidebar-accordion-content">
                    <a href="{{ route('admin.programs.index') }}" class="sidebar-link {{ request()->routeIs('admin.programs.*') ? 'active' : '' }}">
                        <i class="fas fa-football-ball"></i>
                        <span>{{ __('Programs') }}</span>
                    </a>
                    <a href="{{ route('admin.teams.index') }}" class="sidebar-link {{ request()->routeIs('admin.teams.*') ? 'active' : '' }}">
                        <i class="fas fa-shield-alt"></i>
                        <span>{{ __('Teams') }}</span>
                    </a>
                    <a href="{{ route('admin.staff.index') }}" class="sidebar-link {{ request()->routeIs('admin.staff.*') ? 'active' : '' }}">
                        <i class="fas fa-users-cog"></i>
                        <span>{{ __('Staff') }}</span>
                    </a>
                    <a href="{{ route('admin.partners.index') }}" class="sidebar-link {{ request()->routeIs('admin.partners.*') ? 'active' : '' }}">
                        <i class="fas fa-handshake"></i>
                        <span>{{ __('Partners') }}</span>
                    </a>
                    <div class="nav-dropdown-divider" style="height:1px;background:#e8e8e8;margin:8px 12px;"></div>
                    <a href="{{ route('admin.performance.overview') }}" class="sidebar-link {{ request()->routeIs('admin.performance.overview') ? 'active' : '' }}">
                        <i class="fas fa-chart-line"></i>
                        <span>{{ __('Analytics') }}</span>
                    </a>
                    <a href="{{ route('admin.compliance.report') }}" class="sidebar-link {{ request()->routeIs('admin.compliance.report') ? 'active' : '' }}">
                        <i class="fas fa-shield-alt"></i>
                        <span>{{ __('Compliance') }}</span>
                    </a>
                </div>
            </div>

            {{-- Content Accordion --}}
            <div class="sidebar-accordion" data-accordion="content">
                <button class="sidebar-accordion-header {{ request()->routeIs('admin.gallery.*', 'admin.website-players.*', 'admin.jobs.*', 'admin.documents.*') ? 'active' : '' }}" onclick="toggleAccordion('content')">
                    <span><i class="fas fa-images"></i> {{ __('Content') }}</span>
                    <span class="sidebar-accordion-icon"></span>
                </button>
                <div class="sidebar-accordion-content">
                    <a href="{{ route('admin.gallery.index') }}" class="sidebar-link {{ request()->routeIs('admin.gallery.*') ? 'active' : '' }}">
                        <i class="fas fa-images"></i>
                        <span>{{ __('Gallery') }}</span>
                    </a>
                    <a href="{{ route('admin.website-players.index') }}" class="sidebar-link {{ request()->routeIs('admin.website-players.*') ? 'active' : '' }}">
                        <i class="fas fa-users"></i>
                        <span>{{ __('Player Gallery') }}</span>
                    </a>
                    <a href="{{ route('admin.jobs.index') }}" class="sidebar-link {{ request()->routeIs('admin.jobs.*') ? 'active' : '' }}">
                        <i class="fas fa-briefcase"></i>
                        <span>{{ __('Careers') }}</span>
                    </a>
                    <a href="{{ route('admin.documents.index') }}" class="sidebar-link {{ request()->routeIs('admin.documents.*') ? 'active' : '' }}">
                        <i class="fas fa-file-alt"></i>
                        <span>{{ __('Documents') }}</span>
                    </a>
                    <a href="{{ route('admin.letterhead.index') }}" class="sidebar-link {{ request()->routeIs('admin.letterhead.*') ? 'active' : '' }}">
                        <i class="fas fa-file-signature"></i>
                        <span>{{ __('Letterhead') }}</span>
                    </a>
                </div>
            </div>
            {{-- System (Super Admin) Accordion --}}
            @if(auth()->user()->hasRole('super-admin'))
            <div class="sidebar-accordion" data-accordion="system">
                <button class="sidebar-accordion-header {{ request()->routeIs('super-admin.*', 'manager.equipment.*') ? 'active' : '' }}" onclick="toggleAccordion('system')">
                    <span><i class="fas fa-cog"></i> {{ __('System') }}</span>
                    <span class="sidebar-accordion-icon"></span>
                </button>
                <div class="sidebar-accordion-content">
                    <a href="{{ route('super-admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('super-admin.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-th-large"></i>
                        <span>{{ __('Overview') }}</span>
                    </a>
                    <a href="{{ route('super-admin.organizations.index') }}" class="sidebar-link {{ request()->routeIs('super-admin.organizations.*') ? 'active' : '' }}">
                        <i class="fas fa-building"></i>
                        <span>{{ __('Organizations') }}</span>
                    </a>
                    <a href="{{ route('super-admin.roles.index') }}" class="sidebar-link {{ request()->routeIs('super-admin.roles.*') ? 'active' : '' }}">
                        <i class="fas fa-user-shield"></i>
                        <span>{{ __('Roles') }}</span>
                    </a>
                    <a href="{{ route('organization.roles.index') }}" class="sidebar-link {{ request()->routeIs('organization.roles.*') ? 'active' : '' }}">
                        <i class="fas fa-users-cog"></i>
                        <span>{{ __('Org Roles') }}</span>
                    </a>
                    <a href="{{ route('manager.equipment.categories') }}" class="sidebar-link {{ request()->routeIs('manager.equipment.*') ? 'active' : '' }}">
                        <i class="fas fa-boxes"></i>
                        <span>{{ __('Equipment') }}</span>
                    </a>
                </div>
            </div>
            @endif
        </nav>
    </aside>

    {{-- Main Content (Right Side) --}}
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

        @yield('content')

        <footer class="admin-footer">
            <div class="admin-footer-content">
                <div>&copy; {{ date('Y') }} {{ __('Vipers Academy') }}. {{ __('All rights reserved.') }}</div>
            </div>
        </footer>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // Accordion functionality
    let oneSectionOpen = localStorage.getItem('sidebarOneSectionOpen') !== 'false';

    // Initialize accordion mode toggle
    document.getElementById('accordionMode').checked = oneSectionOpen;

    function toggleAccordionMode() {
        const checkbox = document.getElementById('accordionMode');
        oneSectionOpen = checkbox.checked;
        localStorage.setItem('sidebarOneSectionOpen', oneSectionOpen);
    }

    function toggleAccordion(accordionName) {
        const accordion = document.querySelector(`[data-accordion="${accordionName}"]`);
        const isOpen = accordion.classList.contains('open');

        // If one-section-open mode is enabled, close all others first
        if (oneSectionOpen && !isOpen) {
            document.querySelectorAll('.sidebar-accordion.open').forEach(openAccordion => {
                openAccordion.classList.remove('open');
            });
        }

        // Toggle current accordion
        accordion.classList.toggle('open');

        // Save state to localStorage
        saveAccordionState();
    }

    function saveAccordionState() {
        const openAccordions = [];
        document.querySelectorAll('.sidebar-accordion.open').forEach(acc => {
            openAccordions.push(acc.dataset.accordion);
        });
        localStorage.setItem('sidebarOpenAccordions', JSON.stringify(openAccordions));
    }

    function loadAccordionState() {
        const savedState = localStorage.getItem('sidebarOpenAccordions');
        if (savedState) {
            const openAccordions = JSON.parse(savedState);
            openAccordions.forEach(name => {
                const accordion = document.querySelector(`[data-accordion="${name}"]`);
                if (accordion) {
                    accordion.classList.add('open');
                }
            });
        } else {
            // Default: open section that contains active link
            const activeLink = document.querySelector('.sidebar-link.active');
            if (activeLink) {
                const content = activeLink.closest('.sidebar-accordion-content');
                if (content) {
                    content.parentElement.classList.add('open');
                }
            }
        }
    }

    function toggleSidebar() {
        document.getElementById('adminSidebar').classList.toggle('show');
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        loadAccordionState();

        // Auto-hide alerts
        const alerts = document.querySelectorAll('.alert-custom');
        alerts.forEach(alert => {
            setTimeout(() => {
                if (alert) {
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 300);
                }
            }, 5000);
        });
    });
    </script>
    @stack('scripts')
</body>
</html>
