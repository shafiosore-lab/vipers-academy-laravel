<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Parent Portal - WebViper Academy">
    <title>@yield('title', 'Parent Dashboard') | WebViper Academy</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon.ico') }}">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .sidebar-link {
            @apply flex items-center gap-3 px-4 py-3 rounded-lg text-gray-300 hover:bg-gray-800 hover:text-white transition-all duration-200;
        }

        .sidebar-link.active {
            @apply bg-blue-600 text-white;
        }

        .stat-card {
            @apply bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow duration-200;
        }

        .btn-primary {
            @apply px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 font-medium;
        }

        .btn-secondary {
            @apply px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-200 font-medium;
        }

        .badge {
            @apply px-2 py-1 rounded-full text-xs font-medium;
        }

        .badge-success {
            @apply bg-green-100 text-green-800;
        }

        .badge-warning {
            @apply bg-yellow-100 text-yellow-800;
        }

        .badge-danger {
            @apply bg-red-100 text-red-800;
        }

        .badge-info {
            @apply bg-blue-100 text-blue-800;
        }

        .table-container {
            @apply overflow-x-auto rounded-lg border border-gray-200;
        }

        .data-table {
            @apply w-full text-sm text-left;
        }

        .data-table thead {
            @apply bg-gray-50 text-gray-600 uppercase text-xs;
        }

        .data-table th {
            @apply px-6 py-3 font-semibold;
        }

        .data-table td {
            @apply px-6 py-4;
        }

        .data-table tbody tr {
            @apply border-b border-gray-100 hover:bg-gray-50;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .mobile-toggle {
                display: block;
            }
        }

        .player-avatar {
            @apply w-10 h-10 rounded-full object-cover border-2 border-blue-500;
        }

        .dropdown-menu {
            @apply absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-1 hidden z-50;
        }

        .dropdown-menu.show {
            @apply block;
        }

        .progress-bar {
            @apply h-2 bg-gray-200 rounded-full overflow-hidden;
        }

        .progress-bar-fill {
            @apply h-full bg-blue-600 rounded-full transition-all duration-300;
        }
    </style>

    @yield('styles')
</head>
<body class="bg-gray-50">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside id="sidebar" class="sidebar fixed inset-y-0 left-0 z-30 w-64 bg-gray-900 text-white flex flex-col transform -translate-x-full md:translate-x-0 transition-transform duration-300">
            <!-- Logo -->
            <div class="flex items-center justify-between p-4 border-b border-gray-800">
                <a href="{{ route('parent.dashboard') }}" class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-shield-halved text-white text-lg"></i>
                    </div>
                    <span class="text-xl font-bold">WebViper</span>
                </a>
                <button id="mobile-close" class="md:hidden text-gray-400 hover:text-white">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 overflow-y-auto p-4 space-y-1">
                <a href="{{ route('parent.dashboard') }}" class="sidebar-link {{ request()->routeIs('parent.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home w-5"></i>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('parent.profile') }}" class="sidebar-link {{ request()->routeIs('parent.profile') ? 'active' : '' }}">
                    <i class="fas fa-user w-5"></i>
                    <span>My Child's Profile</span>
                </a>

                <a href="{{ route('parent.finances') }}" class="sidebar-link {{ request()->routeIs('parent.finances') ? 'active' : '' }}">
                    <i class="fas fa-credit-card w-5"></i>
                    <span>Finances</span>
                </a>

                <a href="{{ route('parent.training') }}" class="sidebar-link {{ request()->routeIs('parent.training') ? 'active' : '' }}">
                    <i class="fas fa-calendar-check w-5"></i>
                    <span>Training & Attendance</span>
                </a>

                <a href="{{ route('parent.matches') }}" class="sidebar-link {{ request()->routeIs('parent.matches') ? 'active' : '' }}">
                    <i class="fas fa-futbol w-5"></i>
                    <span>Match Records</span>
                </a>

                <a href="{{ route('parent.insights') }}" class="sidebar-link {{ request()->routeIs('parent.insights') ? 'active' : '' }}">
                    <i class="fas fa-brain w-5"></i>
                    <span>AI Insights</span>
                </a>

                <a href="{{ route('parent.media') }}" class="sidebar-link {{ request()->routeIs('parent.media') ? 'active' : '' }}">
                    <i class="fas fa-photo-video w-5"></i>
                    <span>Media Gallery</span>
                </a>

                <a href="{{ route('parent.announcements') }}" class="sidebar-link {{ request()->routeIs('parent.announcements') ? 'active' : '' }}">
                    <i class="fas fa-bullhorn w-5"></i>
                    <span>Announcements</span>
                </a>
            </nav>

            <!-- User Info -->
            <div class="p-4 border-t border-gray-800">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center">
                        <span class="text-white font-semibold">{{ substr(Auth::user()->name ?? 'P', 0, 1) }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-white truncate">{{ Auth::user()->name ?? 'Parent' }}</p>
                        <p class="text-xs text-gray-400 truncate">{{ Auth::user()->email ?? '' }}</p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col md:ml-64">
            <!-- Top Header -->
            <header class="bg-white border-b border-gray-200 sticky top-0 z-20">
                <div class="flex items-center justify-between px-4 py-3">
                    <!-- Mobile Menu Toggle -->
                    <button id="mobile-toggle" class="md:hidden text-gray-600 hover:text-gray-900">
                        <i class="fas fa-bars text-xl"></i>
                    </button>

                    <!-- Page Title -->
                    <h1 class="text-xl font-semibold text-gray-800">@yield('page-title', 'Dashboard')</h1>

                    <!-- Right Side -->
                    <div class="flex items-center gap-4">
                        <!-- Player Selector -->
                        @if(isset($players) && $players->count() > 1)
                        <div class="relative">
                            <button id="player-selector" class="flex items-center gap-2 px-3 py-2 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                                <img src="{{ $selectedPlayer->image_url ?? asset('assets/img/default-player.png') }}" alt="Player" class="w-8 h-8 rounded-full">
                                <span class="text-sm font-medium">{{ $selectedPlayer->full_name ?? 'Select Child' }}</span>
                                <i class="fas fa-chevron-down text-xs text-gray-500"></i>
                            </button>
                            <div id="player-dropdown" class="dropdown-menu">
                                @foreach($players as $player)
                                <a href="{{ route('parent.dashboard', ['player_id' => $player->id]) }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                                    {{ $player->full_name }}
                                </a>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Notifications -->
                        <button class="relative p-2 text-gray-600 hover:text-gray-900 transition-colors">
                            <i class="fas fa-bell text-lg"></i>
                            <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                        </button>

                        <!-- Logout -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="p-2 text-gray-600 hover:text-red-600 transition-colors" title="Logout">
                                <i class="fas fa-sign-out-alt text-lg"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Main Content Area -->
            <main class="flex-1 overflow-y-auto p-4 md:p-6">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Overlay for mobile -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-20 hidden md:hidden"></div>

    <script>
        // Mobile sidebar toggle
        const mobileToggle = document.getElementById('mobile-toggle');
        const mobileClose = document.getElementById('mobile-close');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebar-overlay');

        function openSidebar() {
            sidebar.classList.add('open');
            sidebarOverlay.classList.remove('hidden');
        }

        function closeSidebar() {
            sidebar.classList.remove('open');
            sidebarOverlay.classList.add('hidden');
        }

        if (mobileToggle) mobileToggle.addEventListener('click', openSidebar);
        if (mobileClose) mobileClose.addEventListener('click', closeSidebar);
        if (sidebarOverlay) sidebarOverlay.addEventListener('click', closeSidebar);

        // Player dropdown toggle
        const playerSelector = document.getElementById('player-selector');
        const playerDropdown = document.getElementById('player-dropdown');

        if (playerSelector && playerDropdown) {
            playerSelector.addEventListener('click', function(e) {
                e.stopPropagation();
                playerDropdown.classList.toggle('show');
            });

            document.addEventListener('click', function() {
                playerDropdown.classList.remove('show');
            });
        }
    </script>

    @yield('scripts')
</body>
</html>
