<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', __('Staff Dashboard - Vipers Academy'))</title>

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
           CSS VARIABLES (Matching Admin)
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

    h1, h2, h3, h4, h5, h6 {
        font-weight: 700;
        line-height: 1.2;
        color: var(--gray-900);
    }

    h1 { font-size: 30px; color: var(--primary); }
    h2 { font-size: 22px; }
    h3 { font-size: 16px; }

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

    .staff-brand {
        display: flex;
        align-items: center;
        gap: 12px;
        text-decoration: none;
        margin-right: 3rem;
    }

    .staff-logo {
        width: 40px;
        height: 40px;
        object-fit: contain;
        border-radius: 8px;
    }

    .staff-brand-text h5 {
        font-size: 18px;
        font-weight: 700;
        color: var(--primary);
        margin: 0;
        line-height: 1.2;
    }

    .staff-brand-text small {
        font-size: 11px;
        color: #999;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .staff-search {
        flex: 1;
        max-width: 500px;
        margin-right: 2rem;
    }

    .staff-search-form {
        display: flex;
        border: 1px solid var(--gray-300);
        border-radius: 6px;
        overflow: hidden;
        background: #f8f9fa;
        transition: var(--transition);
    }

    .staff-search-form:focus-within {
        border-color: var(--primary);
        background: var(--white);
        box-shadow: 0 0 0 3px rgba(234, 28, 77, 0.1);
    }

    .staff-search-input {
        flex: 1;
        border: none;
        padding: 10px 16px;
        font-size: 14px;
        outline: none;
        background: transparent;
    }

    .staff-search-btn {
        background: transparent;
        border: none;
        color: #999;
        padding: 0 16px;
        cursor: pointer;
        transition: var(--transition);
    }

    .staff-search-btn:hover {
        color: var(--primary);
    }

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

    .staff-user-menu {
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

    .staff-user-menu:hover {
        background: #e9ecef;
    }

    .staff-user-avatar {
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

    .staff-user-info {
        display: flex;
        flex-direction: column;
        line-height: 1.2;
    }

    .staff-user-name {
        font-size: 13px;
        font-weight: 600;
        color: var(--gray-900);
    }

    .staff-user-role {
        font-size: 11px;
        color: #999;
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
        z-index: 1030;
        transition: left var(--transition);
    }

    .staff-sidebar::-webkit-scrollbar {
        width: 6px;
    }

    .staff-sidebar::-webkit-scrollbar-thumb {
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

    /* ========================================
           MAIN CONTENT
           ======================================== */
    .staff-main {
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
    .staff-footer {
        margin-top: 3rem;
        padding: 1.5rem 0;
        border-top: 1px solid var(--gray-300);
    }

    .staff-footer-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
        font-size: 13px;
        color: #999;
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

    /* Screen reader only class */
    .sr-only {
        position: absolute;
        width: 1px;
        height: 1px;
        padding: 0;
        margin: -1px;
        overflow: hidden;
        clip: rect(0, 0, 0, 0);
        white-space: nowrap;
        border-width: 0;
    }

    /* ========================================
           RESPONSIVE TABLE STYLES
           ======================================== */
    .table {
        table-layout: fixed;
        width: 100%;
        margin-bottom: 1rem;
    }

    .table th,
    .table td {
        padding: 0.5rem;
        vertical-align: middle;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .table th {
        font-weight: 600;
        background-color: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
    }

    /* Allow text wrapping in specific cells */
    .table td.wrap,
    .table th.wrap {
        white-space: normal;
        word-wrap: break-word;
    }

    /* Action buttons container */
    .table td .btn-group,
    .table td .btn-toolbar {
        white-space: nowrap;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .table th,
        .table td {
            padding: 0.4rem;
            font-size: 0.875rem;
        }

        .table-responsive {
            border: none;
        }
    }

    /* ========================================
           RESPONSIVE DESIGN
           ======================================== */
    @media (max-width: 992px) {
        .staff-sidebar {
            left: calc(-1 * var(--sidebar-width));
        }

        .staff-sidebar.show {
            left: 0;
            box-shadow: var(--shadow-lg);
        }

        .staff-main {
            margin-left: 0;
        }

        .mobile-sidebar-toggle {
            display: block;
        }

        .staff-search {
            max-width: 300px;
        }
    }

    @media (max-width: 768px) {
        .top-header .container-fluid {
            padding: 0 1rem;
        }

        .staff-brand {
            margin-right: 1rem;
        }

        .staff-brand-text {
            display: none;
        }

        .staff-search {
            display: none;
        }

        .staff-main {
            padding: 1rem;
        }

        .staff-user-info {
            display: none;
        }

        .header-actions {
            gap: 8px;
        }
    }

    @media (max-width: 480px) {
        .top-header .container-fluid {
            padding: 0 0.5rem;
        }

        .staff-main {
            padding: 0.5rem;
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
            <button class="mobile-sidebar-toggle"
                    id="mobileSidebarToggle"
                    type="button"
                    aria-label="{{ __('Toggle navigation menu') }}"
                    aria-expanded="false"
                    aria-controls="staffSidebar">
                <i class="fas fa-bars" aria-hidden="true"></i>
            </button>

            {{-- Brand - Role-based Dashboard Link --}}
            @php
                $dashboardRoute = null;
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
                    $dashboardRoute = route('finance.dashboard');
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
                <img src="{{ asset('assets/img/logo/vps.jpeg') }}"
                     alt="{{ __('Vipers Academy Logo') }}"
                     class="staff-logo"
                     loading="lazy">
                <div class="staff-brand-text">
                    <h1 class="sr-only">{{ __('Vipers Academy Staff Portal') }}</h1>
                    <h5>{{ __('Vipers Academy') }}</h5>
                    <small>
                        @if(auth()->user()->hasRole('super-admin')){{ __('Admin Portal') }}
                        @elseif(auth()->user()->hasRole('org-admin')){{ __('Organization Admin') }}
                        @elseif(auth()->user()->hasRole('operations-admin')){{ __('Admin Portal') }}
                        @elseif(auth()->user()->hasRole('head-coach')){{ __('Head Coach') }}
                        @elseif(auth()->user()->hasRole('coach')){{ __('Coach Dashboard') }}
                        @elseif(auth()->user()->hasRole('assistant-coach')){{ __('Coach Dashboard') }}
                        @elseif(auth()->user()->hasRole('team-manager')){{ __('Team Manager') }}
                        @elseif(auth()->user()->hasRole('finance-officer')){{ __('Finance Dashboard') }}
                        @elseif(auth()->user()->hasRole('media-officer')){{ __('Media Dashboard') }}
                        @elseif(auth()->user()->hasRole('safeguarding-officer')){{ __('Welfare Dashboard') }}
                        @elseif(auth()->user()->hasRole('player')){{ __('Player Portal') }}
                        @elseif(auth()->user()->hasRole('parent')){{ __('Parent Portal') }}
                        @else{{ __('Staff Portal') }}
                        @endif
                    </small>
                </div>
            </a>

            {{-- Search --}}
            <div class="staff-search">
                <form class="staff-search-form" action="{{ route('search') }}" method="GET" role="search">
                    <label for="global-search" class="sr-only">{{ __('Search') }}</label>
                    <input type="search"
                           id="global-search"
                           name="q"
                           class="staff-search-input"
                           placeholder="{{ __('Search players, programs...') }}"
                           aria-label="{{ __('Search the system') }}"
                           autocomplete="off">
                    <button type="submit" class="staff-search-btn" aria-label="{{ __('Submit search') }}">
                        <i class="fas fa-search" aria-hidden="true"></i>
                    </button>
                </form>
            </div>

            {{-- Header Actions --}}
            <nav class="header-actions" role="navigation" aria-label="{{ __('Header actions') }}">
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
                        @endphp
                        @if($pendingPlayers > 0)
                        <span class="notification-badge" aria-label="{{ $pendingPlayers }} {{ __('notifications') }}">{{ $pendingPlayers }}</span>
                        @endif
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" style="width: 320px;" role="menu">
                        <li role="none">
                            <h6 class="dropdown-header" role="none">{{ __('Notifications') }}</h6>
                        </li>
                        @if($pendingPlayers > 0)
                        <li role="none">
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
                        <li role="none">
                            <span class="dropdown-item text-center text-muted" role="menuitem">{{ __('No notifications') }}</span>
                        </li>
                        @endif
                    </ul>
                </div>

                {{-- User Menu --}}
                <div class="dropdown">
                    <button class="staff-user-menu"
                            type="button"
                            data-bs-toggle="dropdown"
                            aria-label="{{ __('User menu for') }} {{ Auth::user()->name }}"
                            aria-haspopup="true"
                            aria-expanded="false">
                        <div class="staff-user-avatar" aria-hidden="true">{{ substr(Auth::user()->name, 0, 1) }}</div>
                        <div class="staff-user-info">
                            <div class="staff-user-name">{{ Auth::user()->name }}</div>
                            <div class="staff-user-role">
                                @if(auth()->user()->hasRole('super-admin')){{ __('Super Admin') }}
                                @elseif(auth()->user()->hasRole('org-admin')){{ __('Organization Admin') }}
                                @elseif(auth()->user()->hasRole('operations-admin')){{ __('Operations Admin') }}
                                @elseif(auth()->user()->hasRole('head-coach')){{ __('Head Coach') }}
                                @elseif(auth()->user()->hasRole('coach')){{ __('Coach') }}
                                @elseif(auth()->user()->hasRole('assistant-coach')){{ __('Assistant Coach') }}
                                @elseif(auth()->user()->hasRole('team-manager')){{ __('Team Manager') }}
                                @elseif(auth()->user()->hasRole('finance-officer')){{ __('Finance Officer') }}
                                @elseif(auth()->user()->hasRole('media-officer')){{ __('Media Officer') }}
                                @elseif(auth()->user()->hasRole('safeguarding-officer')){{ __('Welfare Officer') }}
                                @elseif(auth()->user()->hasRole('player')){{ __('Player') }}
                                @elseif(auth()->user()->hasRole('parent')){{ __('Parent') }}
                                @else{{ __('Staff Member') }}
                                @endif
                            </div>
                        </div>
                        <i class="fas fa-chevron-down" aria-hidden="true" style="font-size: 12px; color: #999;"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" role="menu">
                        <li role="none">
                            <a class="dropdown-item" href="{{ $dashboardRoute }}" role="menuitem">
                                <i class="fas fa-th-large me-2"></i>{{ __('My Dashboard') }}
                            </a>
                        </li>
                        <li role="none">
                            <a class="dropdown-item" href="{{ route('profile.edit') }}" role="menuitem">
                                <i class="fas fa-user me-2"></i>{{ __('My Profile') }}
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

    {{-- Sidebar - Role-Based Access --}}
    <aside class="staff-sidebar" id="staffSidebar" role="navigation" aria-label="{{ __('Main navigation') }}">
        <nav class="sidebar-nav">

            {{-- COACHING ROLES SECTION --}}
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

            {{-- TEAM MANAGER SECTION --}}
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

                {{-- Equipment Management Section --}}
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

            {{-- FINANCE OFFICER SECTION --}}
            @if(auth()->user()->hasRole('finance-officer'))
                <div class="nav-section-title" role="heading" aria-level="2">{{ __('Finance') }}</div>
                <a href="{{ route('finance.dashboard') }}" class="sidebar-nav-link {{ request()->routeIs('finance.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-th-large" aria-hidden="true"></i><span>{{ __('Dashboard') }}</span>
                </a>
                <a href="{{ route('finance.payments') }}" class="sidebar-nav-link {{ request()->routeIs('finance.payments') ? 'active' : '' }}">
                    <i class="fas fa-money-bill-wave" aria-hidden="true"></i><span>{{ __('Payments') }}</span>
                </a>
                <a href="{{ route('finance.reports') }}" class="sidebar-nav-link {{ request()->routeIs('finance.reports') ? 'active' : '' }}">
                    <i class="fas fa-chart-bar" aria-hidden="true"></i><span>{{ __('Reports') }}</span>
                </a>
            @endif

            {{-- MEDIA OFFICER SECTION --}}
            @if(auth()->user()->hasRole('media-officer'))
                <div class="nav-section-title" role="heading" aria-level="2">{{ __('Media') }}</div>
                <a href="{{ route('media.dashboard') }}" class="sidebar-nav-link {{ request()->routeIs('media.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-th-large" aria-hidden="true"></i><span>{{ __('Dashboard') }}</span>
                </a>
                <a href="{{ route('media.blogs') }}" class="sidebar-nav-link {{ request()->routeIs('media.blogs') ? 'active' : '' }}">
                    <i class="fas fa-blog" aria-hidden="true"></i><span>{{ __('Blogs') }}</span>
                </a>
                <a href="{{ route('media.gallery') }}" class="sidebar-nav-link {{ request()->routeIs('media.gallery') ? 'active' : '' }}">
                    <i class="fas fa-images" aria-hidden="true"></i><span>{{ __('Gallery') }}</span>
                </a>
            @endif

            {{-- SAFEGUARDING OFFICER SECTION --}}
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

            {{-- PLAYER SECTION --}}
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

            {{-- PARTNER SECTION --}}
            @if(auth()->user()->hasAnyRole(['partner']))
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

            {{-- PARENT SECTION --}}
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

            {{-- ADMIN SECTION --}}
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
                @elseif(auth()->user()->hasAnyRole(['super-admin', 'operations-admin', 'admin']))
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

            {{-- QUICK LINKS - VISIBLE TO ALL --}}
            <div class="nav-section-title" role="heading" aria-level="2">{{ __('Quick Links') }}</div>
            <a href="{{ route('home') }}" target="_blank" class="sidebar-nav-link">
                <i class="fas fa-external-link-alt" aria-hidden="true"></i><span>{{ __('View Website') }}</span>
            </a>
            <a href="{{ route('profile.edit') }}" class="sidebar-nav-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                <i class="fas fa-user-cog" aria-hidden="true"></i><span>{{ __('Settings') }}</span>
            </a>

        </nav>
    </aside>

    {{-- Main Content --}}
    <main class="staff-main">
        {{-- Success Alert --}}
        @if(session('success'))
        <div class="alert-custom success">
            <i class="fas fa-check-circle" aria-hidden="true"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close ms-auto" onclick="this.parentElement.remove()" aria-label="{{ __('Close') }}"></button>
        </div>
        @endif

        {{-- Error Alert --}}
        @if(session('error'))
        <div class="alert-custom error">
            <i class="fas fa-exclamation-circle" aria-hidden="true"></i>
            <div>{{ session('error') }}</div>
            <button type="button" class="btn-close ms-auto" onclick="this.parentElement.remove()" aria-label="{{ __('Close') }}"></button>
        </div>
        @endif

        {{-- Validation Errors --}}
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

        {{-- Page Content --}}
        @yield('content')

        {{-- Footer --}}
        <footer class="staff-footer">
            <div class="container-fluid">
                <div class="staff-footer-content">
                    <div>&copy; {{ date('Y') }} {{ __('Vipers Academy') }}. {{ __('All rights reserved.') }}</div>
                </div>
            </div>
        </footer>
    </main>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    'use strict';

    document.addEventListener('DOMContentLoaded', function() {
        // Mobile sidebar toggle
        const mobileSidebarToggle = document.getElementById('mobileSidebarToggle');
        const staffSidebar = document.getElementById('staffSidebar');

        if (mobileSidebarToggle && staffSidebar) {
            mobileSidebarToggle.addEventListener('click', function() {
                const isExpanded = staffSidebar.classList.contains('show');
                staffSidebar.classList.toggle('show');
                this.setAttribute('aria-expanded', !isExpanded);
            });

            // Close sidebar when clicking outside
            document.addEventListener('click', function(e) {
                if (!staffSidebar.contains(e.target) && !mobileSidebarToggle.contains(e.target)) {
                    staffSidebar.classList.remove('show');
                    mobileSidebarToggle.setAttribute('aria-expanded', 'false');
                }
            });

            // Close sidebar when pressing Escape
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && staffSidebar.classList.contains('show')) {
                    staffSidebar.classList.remove('show');
                    mobileSidebarToggle.setAttribute('aria-expanded', 'false');
                    mobileSidebarToggle.focus();
                }
            });
        }

        // Auto-hide alerts after 5 seconds
        const alerts = document.querySelectorAll('.alert-custom');
        alerts.forEach(function(alert, index) {
            setTimeout(function() {
                if (alert && alert.parentNode) {
                    alert.style.transition = 'opacity 0.3s ease';
                    alert.style.opacity = '0';
                    setTimeout(function() {
                        if (alert.parentNode) {
                            alert.remove();
                        }
                    }, 300);
                }
            }, 5000 + (index * 1000));
        });
    });
    </script>

    @stack('scripts')
</body>

</html>
