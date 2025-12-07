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
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Bootstrap 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Chart.js -->
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
            --bg-light: #f7f7f7;
            --white: #fff;
            --shadow-sm: 0 1px 2px rgba(0,0,0,0.05);
            --shadow: 0 1px 3px rgba(0,0,0,0.1);
            --shadow-lg: 0 10px 15px rgba(0,0,0,0.1);
            --transition: 0.2s ease;
        }

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

        /* Top Header */
        .top-header {
            background: var(--white);
            border-bottom: 1px solid var(--gray-300);
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

        /* Sidebar */
        .admin-sidebar {
            background: var(--white);
            width: 260px;
            position: fixed;
            top: 60px;
            left: 0;
            bottom: 0;
            border-right: 1px solid var(--gray-300);
            overflow-y: auto;
            z-index: 1030;
            transition: var(--transition);
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

        /* Main Content */
        .admin-main {
            margin-left: 260px;
            margin-top: 60px;
            padding: 2rem;
            min-height: calc(100vh - 60px);
        }

        /* Alerts */
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

        /* Footer */
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
        }

        .admin-footer-links a:hover {
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

        /* Responsive */
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

            .admin-user-info {
                display: none;
            }

            .header-actions {
                gap: 8px;
            }
        }
    </style>

    @stack('styles')
</head>
<body>
    <!-- Top Header -->
    <header class="top-header">
        <div class="container-fluid">
            <button class="mobile-sidebar-toggle" id="mobileSidebarToggle">
                <i class="fas fa-bars"></i>
            </button>

            <a href="{{ route('admin.dashboard') }}" class="admin-brand">
                <img src="{{ asset('assets/img/logo/vps.jpeg') }}" alt="Vipers Academy" class="admin-logo">
                <div class="admin-brand-text">
                    <h5>Vipers Academy</h5>
                    <small>{{ __('Admin Panel') }}</small>
                </div>
            </a>

            <div class="admin-search">
                <form class="admin-search-form" action="{{ route('search') }}" method="GET">
                    <input type="search" name="q" class="admin-search-input" placeholder="{{ __('Search...') }}" required>
                    <button type="submit" class="admin-search-btn">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>

            <div class="header-actions">
                <select class="language-selector">
                    <option value="en" {{ app()->getLocale() === 'en' ? 'selected' : '' }}>🇬🇧 EN</option>
                    <option value="es" {{ app()->getLocale() === 'es' ? 'selected' : '' }}>🇪🇸 ES</option>
                    <option value="fr" {{ app()->getLocale() === 'fr' ? 'selected' : '' }}>🇫🇷 FR</option>
                    <option value="sw" {{ app()->getLocale() === 'sw' ? 'selected' : '' }}>🇰🇪 SW</option>
                </select>

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
                                    {{ \App\Models\Player::where('registration_status', 'Pending')->count() }} {{ __('pending registrations') }}
                                </a>
                            </li>
                        @endif
                        @if(\App\Models\User::where('user_type', 'partner')->where('status', 'pending')->count() > 0)
                            <li>
                                <a class="dropdown-item" href="{{ route('admin.partners.index') }}">
                                    <i class="fas fa-handshake text-primary me-2"></i>
                                    {{ \App\Models\User::where('user_type', 'partner')->where('status', 'pending')->count() }} {{ __('partnership applications') }}
                                </a>
                            </li>
                        @endif
                        @if($totalNotifications === 0)
                            <li class="dropdown-item text-center text-muted">{{ __('No notifications') }}</li>
                        @endif
                    </ul>
                </div>

                <button class="header-action-btn" onclick="window.location.href='{{ route('admin.image-upload') }}'">
                    <i class="fas fa-images"></i>
                </button>

                <button class="header-action-btn">
                    <i class="fas fa-question-circle"></i>
                </button>

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
                <i class="fas fa-th-large"></i>{{ __('Dashboard') }}
            </a>

            <div class="nav-section-title">{{ __('Management') }}</div>
            <a href="{{ route('admin.players.index') }}" class="sidebar-nav-link {{ request()->routeIs('admin.players.*') ? 'active' : '' }}">
                <i class="fas fa-users"></i>{{ __('Players') }}
                @if(\App\Models\Player::where('registration_status', 'Pending')->count() > 0)
                    <span class="sidebar-nav-badge">{{ \App\Models\Player::where('registration_status', 'Pending')->count() }}</span>
                @endif
            </a>
            <a href="{{ route('admin.partners.index') }}" class="sidebar-nav-link {{ request()->routeIs('admin.partners.*') ? 'active' : '' }}">
                <i class="fas fa-handshake"></i>{{ __('Partners') }}
                @if(\App\Models\User::where('user_type', 'partner')->where('status', 'pending')->count() > 0)
                    <span class="sidebar-nav-badge">{{ \App\Models\User::where('user_type', 'partner')->where('status', 'pending')->count() }}</span>
                @endif
            </a>
            <a href="{{ route('admin.programs.index') }}" class="sidebar-nav-link {{ request()->routeIs('admin.programs.*') ? 'active' : '' }}">
                <i class="fas fa-football-ball"></i>{{ __('Programs') }}
            </a>
            <a href="{{ route('admin.game-statistics.index') }}" class="sidebar-nav-link {{ request()->routeIs('admin.game-statistics.*') ? 'active' : '' }}">
                <i class="fas fa-chart-bar"></i>{{ __('Game Statistics') }}
            </a>
            <a href="{{ route('admin.matches.index') }}" class="sidebar-nav-link {{ request()->routeIs('admin.matches.*') ? 'active' : '' }}">
                <i class="fas fa-futbol"></i>{{ __('Matches') }}
            </a>
            <a href="{{ route('admin.standings.index') }}" class="sidebar-nav-link {{ request()->routeIs('admin.standings.*') ? 'active' : '' }}">
                <i class="fas fa-trophy"></i>{{ __('Standings') }}
            </a>

            <div class="nav-section-title">{{ __('Content') }}</div>
            <a href="{{ route('admin.documents.index') }}" class="sidebar-nav-link {{ request()->routeIs('admin.documents.*') ? 'active' : '' }}">
                <i class="fas fa-file-alt"></i>{{ __('Documents') }}
            </a>
            <a href="{{ route('admin.news.index') }}" class="sidebar-nav-link {{ request()->routeIs('admin.news.*') ? 'active' : '' }}">
                <i class="fas fa-newspaper"></i>{{ __('News') }}
            </a>
            <a href="{{ route('admin.gallery.index') }}" class="sidebar-nav-link {{ request()->routeIs('admin.gallery.*') ? 'active' : '' }}">
                <i class="fas fa-images"></i>{{ __('Gallery') }}
            </a>
            <a href="{{ route('admin.products.index') }}" class="sidebar-nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                <i class="fas fa-shopping-bag"></i>{{ __('Merchandise') }}
            </a>

            <div class="nav-section-title">{{ __('Commerce') }}</div>
            <a href="{{ route('admin.orders.index') }}" class="sidebar-nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                <i class="fas fa-shopping-cart"></i>{{ __('Orders') }}
                @if(\App\Models\Order::where('order_status', 'pending')->count() > 0)
                    <span class="sidebar-nav-badge">{{ \App\Models\Order::where('order_status', 'pending')->count() }}</span>
                @endif
            </a>
            <a href="{{ route('admin.payments.index') }}" class="sidebar-nav-link {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
                <i class="fas fa-credit-card"></i>{{ __('Payments') }}
                @if(\App\Models\Payment::pending()->count() > 0)
                    <span class="sidebar-nav-badge">{{ \App\Models\Payment::pending()->count() }}</span>
                @endif
            </a>
            <a href="{{ route('admin.payments.financial-report') }}" class="sidebar-nav-link {{ request()->routeIs('admin.payments.financial-report') ? 'active' : '' }}">
                <i class="fas fa-chart-line"></i>{{ __('Financial Report') }}
            </a>
            <a href="{{ route('admin.image-upload') }}" class="sidebar-nav-link {{ request()->routeIs('admin.image-upload') ? 'active' : '' }}">
                <i class="fas fa-upload"></i>{{ __('Image Upload') }}
            </a>

            <div class="nav-section-title">{{ __('Analytics') }}</div>
            <a href="{{ route('admin.performance.overview') }}" class="sidebar-nav-link {{ request()->routeIs('admin.performance.overview') ? 'active' : '' }}">
                <i class="fas fa-chart-line"></i>{{ __('Overview') }}
            </a>
            <a href="{{ route('admin.compliance.report') }}" class="sidebar-nav-link {{ request()->routeIs('admin.compliance.report') ? 'active' : '' }}">
                <i class="fas fa-shield-alt"></i>{{ __('Compliance') }}
            </a>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="admin-main">
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
                <button type="button" class="btn-close ms-auto" onclick="this.parentElement.remove()"></button>
            </div>
        @endif

        @yield('content')

        <footer class="admin-footer">
            <div class="container-fluid">
                <div class="admin-footer-content">
                    <div>&copy; {{ date('Y') }} Vipers Academy. {{ __('All rights reserved.') }}</div>
                    <div class="admin-footer-links">
                        <a href="#">{{ __('Privacy') }}</a>
                        <a href="#">{{ __('Terms') }}</a>
                        <a href="#">{{ __('Support') }}</a>
                    </div>
                </div>
            </div>
        </footer>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Mobile sidebar toggle
        document.getElementById('mobileSidebarToggle').addEventListener('click', () => {
            document.getElementById('adminSidebar').classList.toggle('show');
        });

        // Close sidebar on outside click
        document.addEventListener('click', (e) => {
            const sidebar = document.getElementById('adminSidebar');
            const toggle = document.getElementById('mobileSidebarToggle');
            if (!sidebar.contains(e.target) && !toggle.contains(e.target)) {
                sidebar.classList.remove('show');
            }
        });

        // Auto-hide alerts
        setTimeout(() => {
            document.querySelectorAll('.alert-custom').forEach(alert => alert.remove());
        }, 5000);

        // Language selector
        document.querySelector('.language-selector').addEventListener('change', function() {
            console.log('Language:', this.value);
        });
    </script>

    @stack('scripts')
</body>
</html>
