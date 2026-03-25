{{--
    Unified Sidebar Component (Advanced)
    =====================================
    This component provides a consistent sidebar with both accordion and simple modes.
    Supports admin-style accordion navigation and staff-style simple navigation.

    Usage:
    // Simple mode (staff-style)
    @include('components.layout.sidebar-unified', ['mode' => 'simple'])

    // Accordion mode (admin-style)
    @include('components.layout.sidebar-unified', ['mode' => 'accordion'])

    Role-based visibility is handled automatically.
--}}

@props([
    'mode' => 'simple', // 'simple' or 'accordion'
    'sidebarId' => 'sidebar',
    'accordionMode' => true
])

@php
    $user = auth()->user();
    $currentRouteName = Route::currentRouteName();

    // Build menu items based on user roles
    $menuItems = [];

    // =====================
    // SUPER ADMIN / ADMIN MENU
    // =====================
    if ($user->hasAnyRole(['super-admin', 'admin', 'org-admin', 'operations-admin'])) {
        $menuItems['dashboard'] = [
            'title' => __('Dashboard'),
            'icon' => 'fa-th-large',
            'route' => $user->hasRole('super-admin') ? 'super-admin.dashboard' : 'admin.dashboard',
            'roles' => ['super-admin', 'admin', 'org-admin', 'operations-admin']
        ];

        $menuItems['players'] = [
            'title' => __('Players'),
            'icon' => 'fa-users',
            'accordion' => true,
            'roles' => ['super-admin', 'admin', 'operations-admin'],
            'items' => [
                ['name' => __('All Players'), 'route' => 'admin.players.index', 'icon' => 'fa-user'],
                ['name' => __('Attendance'), 'route' => 'admin.attendance.index', 'icon' => 'fa-calendar-check'],
                ['name' => __('Training'), 'route' => 'admin.training-sessions.index', 'icon' => 'fa-stopwatch'],
                ['name' => __('Statistics'), 'route' => 'admin.game-statistics.index', 'icon' => 'fa-chart-bar'],
            ]
        ];

        $menuItems['communication'] = [
            'title' => __('Communication'),
            'icon' => 'fa-bullhorn',
            'accordion' => true,
            'roles' => ['super-admin', 'admin', 'operations-admin'],
            'items' => [
                ['name' => __('Quick Messaging'), 'route' => 'admin.messaging.quick', 'icon' => 'fa-paper-plane'],
                ['name' => __('Message Settings'), 'route' => 'admin.messaging.settings', 'icon' => 'fa-cog'],
                ['name' => __('Announcements'), 'route' => 'admin.blog.index', 'icon' => 'fa-newspaper'],
                ['name' => __('Bulk SMS'), 'route' => 'admin.sms.index', 'icon' => 'fa-sms'],
                ['name' => __('WhatsApp'), 'route' => 'admin.whatsapp.index', 'icon' => 'fab fa-whatsapp'],
                ['name' => __('WhatsApp Settings'), 'route' => 'admin.whatsapp.settings', 'icon' => 'fa-cogs'],
            ]
        ];

        $menuItems['competition'] = [
            'title' => __('Competition'),
            'icon' => 'fa-futbol',
            'accordion' => true,
            'roles' => ['super-admin', 'admin', 'operations-admin'],
            'items' => [
                ['name' => __('Tournaments'), 'route' => 'admin.tournaments.index', 'icon' => 'fa-trophy'],
                ['name' => __('All Tournaments'), 'route' => 'super-admin.tournaments.index', 'icon' => 'fa-globe', 'roles' => ['super-admin']],
                ['name' => __('Tournament Overview'), 'route' => 'super-admin.tournaments.overview', 'icon' => 'fa-chart-pie', 'roles' => ['super-admin']],
                ['name' => __('Matches'), 'route' => 'admin.matches.index', 'icon' => 'fa-futbol'],
                ['name' => __('Standings'), 'route' => 'admin.standings.index', 'icon' => 'fa-list-ol'],
            ]
        ];

        $menuItems['finance'] = [
            'title' => __('Finance'),
            'icon' => 'fa-credit-card',
            'accordion' => true,
            'roles' => ['super-admin', 'admin', 'operations-admin'],
            'items' => [
                ['name' => __('Payments'), 'route' => 'admin.payments.index', 'icon' => 'fa-credit-card'],
                ['name' => __('Categories'), 'route' => 'admin.payment-categories.index', 'icon' => 'fa-tags'],
            ]
        ];

        $menuItems['academy'] = [
            'title' => __('Academy'),
            'icon' => 'fa-graduation-cap',
            'accordion' => true,
            'roles' => ['super-admin', 'admin', 'operations-admin', 'org-admin'],
            'items' => [
                ['name' => __('Programs'), 'route' => 'admin.programs.index', 'icon' => 'fa-football-ball'],
                ['name' => __('Teams'), 'route' => 'admin.teams.index', 'icon' => 'fa-shield-alt'],
                ['name' => __('Staff'), 'route' => 'admin.staff.index', 'icon' => 'fa-users-cog'],
                ['name' => __('Partners'), 'route' => 'admin.partners.index', 'icon' => 'fa-handshake'],
                ['name' => __('Equipment'), 'route' => 'equipment.categories', 'icon' => 'fa-boxes'],
                ['name' => __('Analytics'), 'route' => 'admin.performance.overview', 'icon' => 'fa-chart-line'],
                ['name' => __('Compliance'), 'route' => 'admin.compliance.report', 'icon' => 'fa-shield-alt'],
                ['name' => __('Letterhead'), 'route' => $user->hasRole('super-admin') ? 'super-admin.letterhead.index' : 'admin.letterhead.index', 'icon' => 'fa-file-signature'],
            ]
        ];

        // Website section - separate for super-admin vs admin
        if ($user->hasRole('super-admin')) {
            $menuItems['website'] = [
                'title' => __('Website'),
                'icon' => 'fa-globe',
                'accordion' => true,
                'roles' => ['super-admin'],
                'items' => [
                    ['name' => __('Page Content'), 'route' => 'super-admin.page-content.index', 'icon' => 'fa-file-alt'],
                    ['name' => __('Home Page'), 'route' => 'super-admin.page-content.show', 'route_params' => ['page' => 'home'], 'icon' => 'fa-home'],
                    ['name' => __('About Us'), 'route' => 'super-admin.page-content.show', 'route_params' => ['page' => 'about'], 'icon' => 'fa-info-circle'],
                    ['name' => __('Programs Page'), 'route' => 'super-admin.page-content.show', 'route_params' => ['page' => 'programs'], 'icon' => 'fa-clipboard-list'],
                    ['name' => __('Announcements'), 'route' => 'super-admin.page-content.show', 'route_params' => ['page' => 'announcements'], 'icon' => 'fa-newspaper'],
                    ['name' => __('Careers'), 'route' => 'super-admin.page-content.show', 'route_params' => ['page' => 'careers'], 'icon' => 'fa-briefcase'],
                    ['name' => __('Leaders'), 'route' => 'super-admin.page-content.show', 'route_params' => ['page' => 'leaders'], 'icon' => 'fa-star'],
                    ['name' => __('Website Players'), 'route' => 'admin.website-players.index', 'icon' => 'fa-futbol'],
                    ['name' => __('Partners'), 'route' => 'admin.partners.index', 'icon' => 'fa-handshake'],
                    ['name' => __('Blog/News'), 'route' => 'admin.blog.index', 'icon' => 'fa-newspaper'],
                    ['name' => __('Jobs'), 'route' => 'admin.jobs.index', 'icon' => 'fa-briefcase'],
                ]
            ];
        } elseif ($user->hasAnyRole(['admin', 'operations-admin'])) {
            $menuItems['website'] = [
                'title' => __('Website'),
                'icon' => 'fa-globe',
                'accordion' => true,
                'roles' => ['admin', 'operations-admin'],
                'items' => [
                    ['name' => __('Page Content'), 'route' => 'admin.page-content.index', 'icon' => 'fa-file-alt'],
                    ['name' => __('Home Page'), 'route' => 'admin.page-content.show', 'route_params' => ['page' => 'home'], 'icon' => 'fa-home'],
                    ['name' => __('About Us'), 'route' => 'admin.page-content.show', 'route_params' => ['page' => 'about'], 'icon' => 'fa-info-circle'],
                    ['name' => __('Programs'), 'route' => 'admin.programs.index', 'icon' => 'fa-clipboard-list'],
                    ['name' => __('Website Players'), 'route' => 'admin.website-players.index', 'icon' => 'fa-futbol'],
                    ['name' => __('Announcements'), 'route' => 'admin.blog.index', 'icon' => 'fa-newspaper'],
                    ['name' => __('Careers'), 'route' => 'admin.jobs.index', 'icon' => 'fa-briefcase'],
                    ['name' => __('Leaders'), 'route' => 'admin.leaders.index', 'icon' => 'fa-star'],
                ]
            ];
        }
    }

    // =====================
    // COACH MENU
    // =====================
    if ($user->hasAnyRole(['coach', 'head-coach', 'assistant-coach'])) {
        $menuItems['coach_dashboard'] = [
            'title' => __('Coaching'),
            'icon' => 'fa-clipboard-coach',
            'roles' => ['coach', 'head-coach', 'assistant-coach'],
            'items' => [
                ['name' => __('Dashboard'), 'route' => 'coach.dashboard', 'icon' => 'fa-th-large'],
                ['name' => __('Sessions'), 'route' => 'coach.sessions', 'icon' => 'fa-stopwatch'],
                ['name' => __('Players'), 'route' => 'coach.players', 'icon' => 'fa-users'],
                ['name' => __('Attendance'), 'route' => 'coach.dashboard', 'icon' => 'fa-calendar-check'],
            ]
        ];
    }

    // =====================
    // TEAM MANAGER MENU
    // =====================
    if ($user->hasRole('team-manager')) {
        $menuItems['manager_dashboard'] = [
            'title' => __('Management'),
            'icon' => 'fa-user-tie',
            'roles' => ['team-manager'],
            'items' => [
                ['name' => __('Dashboard'), 'route' => 'manager.dashboard', 'icon' => 'fa-th-large'],
                ['name' => __('Registrations'), 'route' => 'manager.registrations', 'icon' => 'fa-user-plus'],
                ['name' => __('Logistics'), 'route' => 'manager.logistics', 'icon' => 'fa-truck'],
            ]
        ];

        $menuItems['equipment'] = [
            'title' => __('Equipment Management'),
            'icon' => 'fa-boxes',
            'roles' => ['team-manager'],
            'items' => [
                ['name' => __('Categories'), 'route' => 'manager.equipment.categories', 'icon' => 'fa-tags'],
                ['name' => __('Inventory'), 'route' => 'manager.equipment.inventory', 'icon' => 'fa-boxes'],
                ['name' => __('Distribution'), 'route' => 'manager.equipment.distribution', 'icon' => 'fa-clipboard-list'],
                ['name' => __('Compliance'), 'route' => 'manager.equipment.compliance', 'icon' => 'fa-file-contract'],
            ]
        ];
    }

    // =====================
    // FINANCE OFFICER MENU
    // =====================
    if ($user->hasRole('finance-officer')) {
        $menuItems['finance'] = [
            'title' => __('Finance'),
            'icon' => 'fa-credit-card',
            'roles' => ['finance-officer'],
            'items' => [
                ['name' => __('Dashboard'), 'route' => 'finance.dashboard', 'icon' => 'fa-th-large'],
                ['name' => __('Payments'), 'route' => 'finance.payments', 'icon' => 'fa-money-bill-wave'],
                ['name' => __('Reports'), 'route' => 'finance.reports', 'icon' => 'fa-chart-bar'],
            ]
        ];
    }

    // =====================
    // MEDIA OFFICER MENU
    // =====================
    if ($user->hasRole('media-officer')) {
        $menuItems['media'] = [
            'title' => __('Media'),
            'icon' => 'fa-camera',
            'roles' => ['media-officer'],
            'items' => [
                ['name' => __('Dashboard'), 'route' => 'media.dashboard', 'icon' => 'fa-th-large'],
                ['name' => __('Blogs'), 'route' => 'media.blogs', 'icon' => 'fa-blog'],
            ]
        ];
    }

    // =====================
    // SAFEGUARDING OFFICER MENU
    // =====================
    if ($user->hasRole('safeguarding-officer')) {
        $menuItems['welfare'] = [
            'title' => __('Welfare'),
            'icon' => 'fa-heart',
            'roles' => ['safeguarding-officer'],
            'items' => [
                ['name' => __('Dashboard'), 'route' => 'welfare.dashboard', 'icon' => 'fa-th-large'],
                ['name' => __('Attention List'), 'route' => 'welfare.attention.list', 'icon' => 'fa-exclamation-circle'],
                ['name' => __('Compliance'), 'route' => 'welfare.compliance', 'icon' => 'fa-shield-alt'],
            ]
        ];
    }

    // =====================
    // PLAYER MENU
    // =====================
    if ($user->hasRole('player') && !$user->hasAnyRole(['coach', 'head-coach', 'assistant-coach', 'team-manager'])) {
        $menuItems['player_portal'] = [
            'title' => __('My Portal'),
            'icon' => 'fa-user',
            'roles' => ['player'],
            'items' => [
                ['name' => __('Dashboard'), 'route' => 'player.portal.dashboard', 'icon' => 'fa-th-large'],
                ['name' => __('My Profile'), 'route' => 'player.portal.profile', 'icon' => 'fa-user'],
                ['name' => __('Programs'), 'route' => 'player.portal.programs', 'icon' => 'fa-graduation-cap'],
                ['name' => __('Training'), 'route' => 'player.portal.training', 'icon' => 'fa-running'],
                ['name' => __('Schedule'), 'route' => 'player.portal.schedule', 'icon' => 'fa-calendar-alt'],
                ['name' => __('Resources'), 'route' => 'player.portal.resources', 'icon' => 'fa-book'],
                ['name' => __('Orders'), 'route' => 'player.portal.orders', 'icon' => 'fa-shopping-bag'],
                ['name' => __('Messages'), 'route' => 'player.portal.communication', 'icon' => 'fa-comments'],
                ['name' => __('Support'), 'route' => 'player.portal.support', 'icon' => 'fa-life-ring'],
            ]
        ];
    }

    // =====================
    // PARENT MENU
    // =====================
    if ($user->hasRole('parent')) {
        $menuItems['parent_portal'] = [
            'title' => __('Parent Portal'),
            'icon' => 'fa-users',
            'roles' => ['parent'],
            'items' => [
                ['name' => __('Dashboard'), 'route' => 'parent.dashboard', 'icon' => 'fa-th-large'],
                ['name' => __('My Child'), 'route' => 'parent.profile', 'icon' => 'fa-user'],
                ['name' => __('Finances'), 'route' => 'parent.finances', 'icon' => 'fa-credit-card'],
                ['name' => __('Training'), 'route' => 'parent.training', 'icon' => 'fa-calendar-check'],
                ['name' => __('Matches'), 'route' => 'parent.matches', 'icon' => 'fa-futbol'],
                ['name' => __('AI Insights'), 'route' => 'parent.insights', 'icon' => 'fa-brain'],
                ['name' => __('Media'), 'route' => 'parent.media', 'icon' => 'fa-photo-video'],
                ['name' => __('News'), 'route' => 'parent.announcements', 'icon' => 'fa-bullhorn'],
            ]
        ];
    }

    // =====================
    // PARTNER MENU
    // =====================
    if ($user->hasRole('partner')) {
        $menuItems['partner_portal'] = [
            'title' => __('Partner Portal'),
            'icon' => 'fa-handshake',
            'roles' => ['partner'],
            'items' => [
                ['name' => __('Dashboard'), 'route' => 'partner.dashboard', 'icon' => 'fa-th-large'],
                ['name' => __('My Players'), 'route' => 'partner.players', 'icon' => 'fa-users'],
                ['name' => __('Analytics'), 'route' => 'partner.analytics', 'icon' => 'fa-chart-line'],
            ]
        ];
    }

    // =====================
    // SYSTEM MENU (Super Admin only)
    // =====================
    if ($user->hasRole('super-admin')) {
        $menuItems['system'] = [
            'title' => __('System'),
            'icon' => 'fa-cog',
            'accordion' => true,
            'roles' => ['super-admin'],
            'items' => [
                ['name' => __('Overview'), 'route' => 'super-admin.dashboard', 'icon' => 'fa-th-large'],
                ['name' => __('Organizations'), 'route' => 'super-admin.organizations.index', 'icon' => 'fa-building'],
                ['name' => __('Users'), 'route' => 'super-admin.users.index', 'icon' => 'fa-users'],
                ['name' => __('Plans'), 'route' => 'super-admin.plans.index', 'icon' => 'fa-tags'],
                ['name' => __('Roles'), 'route' => 'super-admin.roles.index', 'icon' => 'fa-user-shield'],
                ['name' => __('Org Roles'), 'route' => 'organization.roles.index', 'icon' => 'fa-users-cog'],
            ]
        ];
    }

    // Filter items based on user roles
    $filteredItems = [];
    foreach ($menuItems as $key => $item) {
        $hasRole = false;
        if (isset($item['roles'])) {
            foreach ($item['roles'] as $role) {
                if ($user->hasRole($role)) {
                    $hasRole = true;
                    break;
                }
            }
        }
        if ($hasRole) {
            $filteredItems[$key] = $item;
        }
    }

    // Helper function to check if route is active
    function isRouteActive($route, $currentRoute) {
        if (!$route) return false;
        return request()->routeIs($route);
    }

    function isAccordionActive($items, $currentRoute) {
        if (!$items) return false;
        foreach ($items as $item) {
            if (isset($item['route']) && request()->routeIs($item['route'])) {
                return true;
            }
        }
        return false;
    }
@endphp

<aside class="dashboard-sidebar" id="{{ $sidebarId }}" role="navigation" aria-label="{{ __('Main navigation') }}">
    <nav class="dashboard-sidebar-nav">
        {{-- Accordion Mode Setting (Admin style) --}}
        @if($mode === 'accordion')
        <div class="accordion-settings">
            <span class="accordion-settings-label">{{ __('One section at a time') }}</span>
            <label class="accordion-toggle-switch">
                <input type="checkbox" id="accordionMode" {{ $accordionMode ? 'checked' : '' }} onchange="toggleAccordionMode()">
                <span class="accordion-toggle-slider"></span>
            </label>
        </div>
        @endif

        {{-- Menu Items --}}
        @foreach($filteredItems as $key => $section)
            @if($mode === 'accordion' && isset($section['accordion']) && $section['accordion'])
                {{-- Accordion Style (Admin) --}}
                <div class="sidebar-accordion" data-accordion="{{ $key }}">
                    <button class="sidebar-accordion-header {{ isAccordionActive($section['items'] ?? [], $currentRouteName) ? 'active' : '' }}"
                            onclick="toggleAccordion('{{ $key }}')">
                        <span>
                            @if(isset($section['icon']))<i class="{{ $section['icon'] }}"></i>@endif
                            {{ $section['title'] }}
                        </span>
                        <span class="sidebar-accordion-icon"></span>
                    </button>
                    <div class="sidebar-accordion-content">
                        @if(isset($section['items']))
                            @foreach($section['items'] as $item)
                                @php
                                    // Check if item has specific roles - if so, filter by those roles
                                    $itemHasRole = true;
                                    if (isset($item['roles'])) {
                                        $itemHasRole = false;
                                        foreach ($item['roles'] as $role) {
                                            if ($user->hasRole($role)) {
                                                $itemHasRole = true;
                                                break;
                                            }
                                        }
                                    }
                                @endphp
                                @if($itemHasRole && !empty($item['route']) && Route::has($item['route']))
                                @php
                                    $routeParams = $item['route_params'] ?? [];
                                @endphp
                                <a href="{{ route($item['route'], $routeParams) }}"
                                   class="sidebar-link {{ isRouteActive($item['route'], $currentRouteName) ? 'active' : '' }}">
                                    @if(isset($item['icon']))<i class="{{ $item['icon'] }}"></i>@endif
                                    <span>{{ $item['name'] }}</span>
                                    @if(isset($item['badge']))<span class="sidebar-badge">{{ $item['badge'] }}</span>@endif
                                </a>
                                @endif
                            @endforeach
                        @endif
                    </div>
                </div>
            @elseif(isset($section['items']) && is_array($section['items']))
                {{-- Simple Style (Staff) with Section Header --}}
                <div class="dashboard-nav-section-title">
                    @if(isset($section['icon']))<i class="{{ $section['icon'] }} me-2"></i>@endif
                    {{ $section['title'] }}
                </div>
                @foreach($section['items'] as $item)
                    @if(!empty($item['route']) && Route::has($item['route']))
                    <a href="{{ route($item['route']) }}"
                       class="sidebar-nav-link {{ isRouteActive($item['route'], $currentRouteName) ? 'active' : '' }}">
                        @if(isset($item['icon']))<i class="{{ $item['icon'] }}"></i>@endif
                        <span>{{ $item['name'] }}</span>
                        @if(isset($item['badge']))<span class="sidebar-badge">{{ $item['badge'] }}</span>@endif
                    </a>
                    @endif
                @endforeach
            @elseif(isset($section['route']))
                {{-- Single Link (Direct dashboard link) --}}
                <a href="{{ route($section['route']) }}"
                   class="sidebar-nav-link {{ isRouteActive($section['route'], $currentRouteName) ? 'active' : '' }}">
                    @if(isset($section['icon']))<i class="{{ $section['icon'] }}"></i>@endif
                    <span>{{ $section['title'] }}</span>
                </a>
            @endif
        @endforeach

        {{-- Quick Links (Always Visible) --}}
        <div class="dashboard-nav-section-title">{{ __('Quick Links') }}</div>
        <a href="{{ route('home') }}" target="_blank" class="sidebar-nav-link">
            <i class="fas fa-external-link-alt"></i>
            <span>{{ __('View Website') }}</span>
        </a>
        <a href="{{ route('profile.edit') }}" class="sidebar-nav-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
            <i class="fas fa-user-cog"></i>
            <span>{{ __('Settings') }}</span>
        </a>
    </nav>
</aside>

