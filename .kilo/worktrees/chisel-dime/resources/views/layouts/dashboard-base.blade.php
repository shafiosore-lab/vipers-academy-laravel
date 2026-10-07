<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name', 'Mumias Vipers Academy') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* ========================================
           CSS VARIABLES
           ======================================== */
        :root {
            --primary:        #667eea;
            --primary-dark:   #764ba2;
            --secondary:      #10b981;
            --secondary-dark: #06b6d4;
            --warning:        #f59e0b;
            --danger:         #ef4444;
            --transition:     0.3s ease;
        }

        /* ========================================
           TASKBAR NAVIGATION
           ======================================== */
        .taskbar-container {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
            overflow-x: auto;
        }

        .taskbar-item {
            transition: all var(--transition);
            border-bottom: 3px solid transparent;
            white-space: nowrap;
        }

        .taskbar-item:hover {
            background: rgba(255,255,255,0.1);
            transform: translateY(-2px);
        }

        .taskbar-item.active {
            background: rgba(255,255,255,0.2);
            border-bottom-color: #fff;
        }

        .taskbar-dropdown {
            background: rgba(255,255,255,0.98);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(0,0,0,0.05);
            animation: dropdownSlide 0.2s ease;
        }

        @keyframes dropdownSlide {
            from { opacity: 0; transform: translateY(-10px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .taskbar-dropdown-item { transition: all 0.2s ease; }

        .taskbar-dropdown-item:hover:not(.disabled) {
            background: #f3f4f6;
            transform: translateX(4px);
        }

        .taskbar-dropdown-item.disabled {
            cursor: not-allowed;
            opacity: 0.5;
        }

        /* ========================================
           BADGES
           ======================================== */
        .user-badge {
            background: linear-gradient(135deg, var(--secondary) 0%, var(--secondary-dark) 100%);
            color: white;
            font-weight: 600;
            box-shadow: 0 2px 4px rgba(16,185,129,0.2);
        }

        .user-badge.primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            box-shadow: 0 2px 4px rgba(102,126,234,0.3);
        }

        .user-badge.elevated {
            background: linear-gradient(135deg, var(--warning) 0%, var(--danger) 100%);
            box-shadow: 0 2px 4px rgba(245,158,11,0.3);
            animation: pulse 2s infinite;
        }

        .trial-warning {
            background: linear-gradient(135deg, var(--warning) 0%, var(--danger) 100%);
            color: white;
            font-weight: 600;
            animation: pulse 2s infinite;
            box-shadow: 0 2px 4px rgba(245,158,11,0.3);
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50%       { opacity: 0.85; }
        }

        /* ========================================
           SIDEBAR
           ======================================== */
        .sidebar-item { transition: all 0.2s ease; }

        .sidebar-item:hover {
            background: #f3f4f6;
            transform: translateX(2px);
        }

        .sidebar-item.active {
            background: #e5e7eb;
            border-left: 4px solid var(--primary);
        }

        /* ========================================
           CARDS
           ======================================== */
        .dashboard-card {
            transition: all var(--transition);
            border: 1px solid #e5e7eb;
            background: white;
        }

        .dashboard-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
        }

        .stat-card {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            transition: all var(--transition);
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 20px rgba(102,126,234,0.3);
        }

        .stat-card.secondary { background: linear-gradient(135deg, var(--secondary) 0%, #3b82f6 100%); }
        .stat-card.warning   { background: linear-gradient(135deg, var(--warning) 0%, var(--danger) 100%); }

        /* ========================================
           BUTTONS
           ======================================== */
        .quick-action-btn {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            border: none;
            transition: all var(--transition);
        }

        .quick-action-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 16px rgba(102,126,234,0.4);
        }

        /* ========================================
           USER MENU
           ======================================== */
        .user-menu { position: relative; }

        .user-menu-button {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s ease;
        }

        .user-menu-button:hover { opacity: 0.8; }

        .user-menu-dropdown {
            position: absolute;
            right: 0;
            margin-top: 0.5rem;
            width: 12rem;
            background: white;
            border-radius: 0.5rem;
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
            padding: 0.25rem;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.2s ease;
            z-index: 50;
        }

        .user-menu:hover .user-menu-dropdown,
        .user-menu-dropdown:hover {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .user-menu-item {
            display: block;
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            color: #374151;
            transition: all 0.2s ease;
            border-radius: 0.375rem;
        }

        .user-menu-item:hover              { background: #f3f4f6; }
        .user-menu-item.danger:hover       { background: #fee2e2; color: var(--danger); }

        /* ========================================
           NOTIFICATIONS
           ======================================== */
        .notification-btn {
            position: relative;
            padding: 0.5rem;
            color: #6b7280;
            transition: all 0.2s ease;
        }

        .notification-btn:hover { color: #111827; }

        .notification-badge {
            position: absolute;
            top: 0.25rem; right: 0.25rem;
            width: 0.5rem; height: 0.5rem;
            background: var(--danger);
            border-radius: 50%;
            border: 2px solid white;
            animation: ping 2s infinite;
        }

        @keyframes ping {
            0%, 100% { transform: scale(1);   opacity: 1;   }
            50%       { transform: scale(1.2); opacity: 0.7; }
        }

        /* ========================================
           ROLE SWITCHER
           ======================================== */
        .role-switcher { position: relative; }

        .role-switcher-btn {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.375rem 0.75rem;
            background: rgba(255,255,255,0.2);
            border-radius: 0.375rem;
            color: white;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .role-switcher-btn:hover { background: rgba(255,255,255,0.3); }

        .role-switcher-dropdown {
            position: absolute;
            right: 0; top: 100%;
            margin-top: 0.5rem;
            width: 16rem;
            background: white;
            border-radius: 0.5rem;
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
            padding: 0.25rem;
            z-index: 50;
        }

        .role-option {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 0.75rem;
            border-radius: 0.375rem;
            color: #374151;
            transition: all 0.2s ease;
        }

        .role-option:hover          { background: #f3f4f6; }
        .role-option.active         { background: #eff6ff; color: var(--primary); }
        .role-option.primary-role   { background: #f0fdf4; color: #166534; }

        /* ========================================
           RESPONSIVE
           ======================================== */
        @media (max-width: 768px) {
            .user-badge,
            .trial-warning,
            .role-switcher-btn { font-size: 0.75rem; padding: 0.25rem 0.5rem; }
        }
    </style>

    @livewireStyles
</head>

<body class="font-sans antialiased bg-gray-50">
<div class="min-h-screen">

    {{-- ===================== TOP NAV ===================== --}}
    <nav class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">

                {{-- Left: Logo + Badges --}}
                <div class="flex items-center gap-4">
                    <div class="flex-shrink-0">
                        <a href="{{ route('dashboard') }}">
                            <x-application-logo class="block h-10 w-auto text-gray-800" />
                        </a>
                    </div>

                    @if(isset($userType['display_name']))
                        <span class="px-3 py-1 rounded-full text-sm font-medium user-badge primary">
                            {{ $userType['display_name'] }}
                        </span>

                        {{-- Role switcher (only when elevated + can switch) --}}
                        @if(!empty($userType['show_elevated']) && !empty($userType['can_switch']))
                            <div class="role-switcher relative">
                                <button class="role-switcher-btn" type="button">
                                    <i class="fas fa-exchange-alt"></i>
                                    <span>Switch Role</span>
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>

                                @if(!empty($userType['available_dashboards']) && count($userType['available_dashboards']) > 1)
                                    <div class="role-switcher-dropdown hidden">
                                        @foreach($userType['available_dashboards'] as $dashboard)
                                            <a href="{{ route($dashboard['route']) }}"
                                               class="role-option {{ !empty($dashboard['is_primary']) ? 'primary-role' : '' }}">
                                                <i class="{{ $dashboard['icon'] }}"></i>
                                                <span class="flex-1">{{ $dashboard['name'] }}</span>
                                                @if(!empty($dashboard['is_primary']))
                                                    <span class="text-xs text-green-600">(Primary)</span>
                                                @endif
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endif
                    @endif

                    {{-- Trial warning --}}
                    @if(!empty($userType['is_trial']))
                        <span class="px-3 py-1 rounded-full text-sm font-medium trial-warning">
                            Trial — {{ $userType['trial_days_left'] ?? 'Expires Soon' }}
                        </span>
                    @endif
                </div>

                {{-- Centre: Nav links --}}
                <div class="hidden md:flex md:items-center md:space-x-8">
                    <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium transition">
                        Home
                    </a>
                    <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium transition">
                        Dashboard
                    </a>
                    <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium transition">
                        Help
                    </a>
                </div>

                {{-- Right: Notifications + User menu --}}
                <div class="flex items-center gap-4">

                    <button class="notification-btn" aria-label="{{ __('Notifications') }}">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        <span class="notification-badge"></span>
                    </button>

                    <div class="user-menu">
                        <button class="user-menu-button">
                            <img class="h-8 w-8 rounded-full object-cover"
                                 src="{{ Auth::user()->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) }}"
                                 alt="{{ Auth::user()->name }}">
                            <span class="hidden sm:inline text-sm font-medium text-gray-700">{{ Auth::user()->name }}</span>
                            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div class="user-menu-dropdown">
                            <a href="{{ route('profile.show') }}" class="user-menu-item">Profile</a>
                            <a href="{{ route('profile.edit') }}" class="user-menu-item">Settings</a>
                            <div class="border-t border-gray-100 my-1"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="user-menu-item danger w-full text-left">Sign Out</button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </nav>

    {{-- ===================== TASKBAR ===================== --}}
    @if(!empty($taskbars) && count($taskbars) > 0)
        <div class="taskbar-container">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex space-x-8">
                    @foreach($taskbars as $taskbar)
                        @if(!empty($taskbar['items']) && count($taskbar['items']) > 0)
                            <div class="relative group">
                                <button class="flex items-center text-white px-4 py-3 text-sm font-medium taskbar-item {{ $loop->first ? 'active' : '' }}"
                                        type="button"
                                        aria-expanded="false"
                                        aria-haspopup="true">
                                    <i class="{{ $taskbar['icon'] }} mr-2"></i>
                                    {{ $taskbar['name'] }}
                                    <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>

                                <div class="absolute left-0 mt-2 w-64 rounded-lg shadow-lg taskbar-dropdown hidden group-hover:block z-50">
                                    <div class="py-2">
                                        @foreach($taskbar['items'] as $item)
                                            @if(isset($item['route']) && Route::has($item['route']))
                                                <a href="{{ route($item['route']) }}"
                                                   class="taskbar-dropdown-item flex items-center px-4 py-3 text-sm text-gray-700">
                                                    <i class="{{ $item['icon'] }} mr-3 text-gray-400"></i>
                                                    {{ $item['name'] }}
                                                </a>
                                            @else
                                                <span class="taskbar-dropdown-item disabled flex items-center px-4 py-3 text-sm text-gray-400">
                                                    <i class="{{ $item['icon'] }} mr-3"></i>
                                                    {{ $item['name'] }}
                                                    <span class="ml-auto text-xs">(Soon)</span>
                                                </span>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    {{-- ===================== PAGE HEADER ===================== --}}
    @if(isset($header))
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
    @endif

    {{-- ===================== PAGE CONTENT ===================== --}}
    <main class="flex-1">
        {{ $slot }}
    </main>

</div>

@livewireScripts

<script>
'use strict';

document.addEventListener('DOMContentLoaded', function () {
    const roleSwitcherBtn      = document.querySelector('.role-switcher-btn');
    const roleSwitcherDropdown = document.querySelector('.role-switcher-dropdown');

    // Role switcher toggle
    if (roleSwitcherBtn && roleSwitcherDropdown) {
        roleSwitcherBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            roleSwitcherDropdown.classList.toggle('hidden');
        });
    }

    // Taskbar: highlight clicked item
    document.querySelectorAll('.taskbar-item').forEach(function (item) {
        item.addEventListener('click', function () {
            document.querySelectorAll('.taskbar-item').forEach(i => i.classList.remove('active'));
            this.classList.add('active');
        });
    });

    // Close all dropdowns on outside click
    document.addEventListener('click', function (e) {
        if (roleSwitcherBtn && roleSwitcherDropdown) {
            if (!roleSwitcherBtn.contains(e.target) && !roleSwitcherDropdown.contains(e.target)) {
                roleSwitcherDropdown.classList.add('hidden');
            }
        }

        document.querySelectorAll('.taskbar-container .group').forEach(function (group) {
            if (!group.contains(e.target)) {
                const dropdown = group.querySelector('.taskbar-dropdown');
                if (dropdown) dropdown.classList.add('hidden');
            }
        });
    });

    // Stop clicks inside taskbar dropdowns from bubbling up
    document.querySelectorAll('.taskbar-dropdown').forEach(function (dropdown) {
        dropdown.addEventListener('click', function (e) { e.stopPropagation(); });
    });

    // Escape closes all dropdowns
    document.addEventListener('keydown', function (e) {
        if (e.key !== 'Escape') return;
        document.querySelectorAll('.taskbar-dropdown').forEach(d => d.classList.add('hidden'));
        if (roleSwitcherDropdown) roleSwitcherDropdown.classList.add('hidden');
    });
});
</script>

</body>
</html>
