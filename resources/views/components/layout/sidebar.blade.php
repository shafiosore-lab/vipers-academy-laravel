{{--
    Unified Sidebar Component
    ========================
    This component provides a consistent sidebar across all dashboard types.

    Usage:
    @include('components.layout.sidebar', ['items' => $sidebarItems])

    Where $sidebarItems is an array of:
    [
        'title' => 'Section Title',
        'items' => [
            ['name' => 'Link Name', 'route' => 'route.name', 'icon' => 'fa-icon'],
            ...
        ]
    ]

    Role-based visibility is handled automatically via the $allowedRoles parameter.
--}}

@props([
    'items' => [],
    'allowedRoles' => [],
    'sidebarId' => 'sidebar',
    'activeRoutePatterns' => []
])

@php
    // If no items provided, use default based on user role
    $user = auth()->user();
    $currentRouteName = Route::currentRouteName();

    // Build default items based on user roles
    $defaultItems = [];

    // Super Admin / Admin items
    if ($user->hasAnyRole(['super-admin', 'admin', 'org-admin', 'operations-admin'])) {
        $defaultItems[] = [
            'title' => __('Administration'),
            'icon' => 'fa-cog',
            'items' => [
                ['name' => __('Dashboard'), 'route' => $user->hasRole('super-admin') ? 'super-admin.dashboard' : 'admin.dashboard', 'icon' => 'fa-th-large'],
            ]
        ];

        $defaultItems[] = [
            'title' => __('Players'),
            'icon' => 'fa-users',
            'items' => [
                ['name' => __('All Players'), 'route' => 'admin.players.index', 'icon' => 'fa-user'],
                ['name' => __('Attendance'), 'route' => 'admin.attendance.index', 'icon' => 'fa-calendar-check'],
                ['name' => __('Training'), 'route' => 'admin.training-sessions.index', 'icon' => 'fa-stopwatch'],
            ]
        ];

        // Website Management Section
        $defaultItems[] = [
            'title' => __('Website'),
            'icon' => 'fa-globe',
            'items' => [
                ['name' => __('Page Content'), 'route' => 'admin.page-content.index', 'icon' => 'fa-file-alt'],
                ['name' => __('Home Page'), 'route' => 'admin.page-content.show', 'route_params' => ['page' => 'home'], 'icon' => 'fa-home'],
                ['name' => __('About Us'), 'route' => 'admin.page-content.show', 'route_params' => ['page' => 'about'], 'icon' => 'fa-info-circle'],
                ['name' => __('Programs'), 'route' => 'admin.programs.index', 'icon' => 'fa-clipboard-list'],
                ['name' => __('Staff'), 'route' => 'admin.staff.index', 'icon' => 'fa-user-tie'],
                ['name' => __('Website Players'), 'route' => 'admin.website-players.index', 'icon' => 'fa-futbol'],
                ['name' => __('Gallery'), 'route' => 'admin.gallery.index', 'icon' => 'fa-images'],
                ['name' => __('Announcements'), 'route' => 'admin.blog.index', 'icon' => 'fa-newspaper'],
                ['name' => __('Careers'), 'route' => 'admin.jobs.index', 'icon' => 'fa-briefcase'],
                ['name' => __('Leaders'), 'route' => 'leaders.index', 'icon' => 'fa-star'],
            ]
        ];
    }

    // Coach items
    if ($user->hasAnyRole(['coach', 'head-coach', 'assistant-coach'])) {
        $defaultItems[] = [
            'title' => __('Coaching'),
            'icon' => 'fa-clipboard-coach',
            'items' => [
                ['name' => __('Dashboard'), 'route' => 'coach.dashboard', 'icon' => 'fa-th-large'],
                ['name' => __('Sessions'), 'route' => 'coach.sessions', 'icon' => 'fa-stopwatch'],
                ['name' => __('Players'), 'route' => 'coach.players', 'icon' => 'fa-users'],
            ]
        ];
    }

    // Team Manager items
    if ($user->hasRole('team-manager')) {
        $defaultItems[] = [
            'title' => __('Management'),
            'icon' => 'fa-user-tie',
            'items' => [
                ['name' => __('Dashboard'), 'route' => 'manager.dashboard', 'icon' => 'fa-th-large'],
                ['name' => __('Registrations'), 'route' => 'manager.registrations', 'icon' => 'fa-user-plus'],
            ]
        ];

        $defaultItems[] = [
            'title' => __('Equipment'),
            'icon' => 'fa-boxes',
            'items' => [
                ['name' => __('Categories'), 'route' => 'manager.equipment.categories', 'icon' => 'fa-tags'],
                ['name' => __('Inventory'), 'route' => 'manager.equipment.inventory', 'icon' => 'fa-boxes'],
                ['name' => __('Distribution'), 'route' => 'manager.equipment.distribution', 'icon' => 'fa-clipboard-list'],
            ]
        ];
    }

    // Finance Officer items
    if ($user->hasRole('finance-officer')) {
        $defaultItems[] = [
            'title' => __('Finance'),
            'icon' => 'fa-credit-card',
            'items' => [
                ['name' => __('Dashboard'), 'route' => 'finance.dashboard', 'icon' => 'fa-th-large'],
                ['name' => __('Payments'), 'route' => 'finance.payments', 'icon' => 'fa-money-bill-wave'],
            ]
        ];
    }

    // Use provided items or default
    $menuItems = !empty($items) ? $items : $defaultItems;

    // Helper function to check if route is active
    function isRouteActive($route, $patterns = []) {
        if (in_array($route, $patterns)) {
            return true;
        }
        return request()->routeIs($route);
    }
@endphp

<aside class="{{ $sidebarId }}" id="{{ $sidebarId }}" role="navigation" aria-label="{{ __('Main navigation') }}">
    <nav class="sidebar-nav">
        @forelse($menuItems as $section)
            @if(!empty($section['title']))
            <div class="nav-section-title" role="heading" aria-level="2">
                @if(!empty($section['icon']))<i class="{{ $section['icon'] }} me-2"></i>@endif
                {{ $section['title'] }}
            </div>
            @endif

            @if(!empty($section['items']))
                @foreach($section['items'] as $item)
                    @if(!empty($item['route']) && Route::has($item['route']))
                    @php
                        $routeParams = $item['route_params'] ?? [];
                    @endphp
                    <a href="{{ route($item['route'], $routeParams) }}"
                       class="sidebar-nav-link {{ isRouteActive($item['route'], $activeRoutePatterns) ? 'active' : '' }}"
                       @if(!empty($item['target'])) target="{{ $item['target'] }}" @endif>
                        @if(!empty($item['icon']))<i class="{{ $item['icon'] }}" aria-hidden="true"></i>@endif
                        <span>{{ $item['name'] }}</span>
                        @if(!empty($item['badge']))
                        <span class="sidebar-badge">{{ $item['badge'] }}</span>
                        @endif
                    </a>
                    @endif
                @endforeach
            @endif
        @empty
            {{ $slot }}
        @endforelse

        {{-- Always visible quick links --}}
        <div class="nav-section-title" role="heading" aria-level="2">{{ __('Quick Links') }}</div>
        <a href="{{ route('home') }}" target="_blank" class="sidebar-nav-link">
            <i class="fas fa-external-link-alt" aria-hidden="true"></i>
            <span>{{ __('View Website') }}</span>
        </a>
        <a href="{{ route('profile.edit') }}" class="sidebar-nav-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
            <i class="fas fa-user-cog" aria-hidden="true"></i>
            <span>{{ __('Settings') }}</span>
        </a>
    </nav>
</aside>
