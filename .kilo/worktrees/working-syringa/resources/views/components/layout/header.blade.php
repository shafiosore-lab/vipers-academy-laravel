{{--
    Unified Header/Taskbar Component
    ================================
    This component provides a consistent header across all dashboard types.
    Used as the top navigation/taskbar for all dashboards.

    Usage:
    @include('components.layout.header')

    Role-based elements are automatically handled.
--}}

@props([
    'title' => null,
    'breadcrumbs' => [],
    'actions' => null,
    'headerClass' => 'dashboard-header'
])

@php
    $user = auth()->user();

    // Determine dashboard route based on role
    $dashboardRoute = null;

    if ($user->hasRole('super-admin')) {
        $dashboardRoute = route('super-admin.dashboard');
    } elseif ($user->hasAnyRole(['admin', 'operations-admin'])) {
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

    // Get role display name
    $roleDisplayName = match(true) {
        $user->hasRole('super-admin') => __('Super Admin'),
        $user->hasRole('org-admin') => __('Organization Admin'),
        $user->hasRole('operations-admin') => __('Operations Admin'),
        $user->hasRole('admin') => __('Admin'),
        $user->hasRole('head-coach') => __('Head Coach'),
        $user->hasRole('coach') => __('Coach'),
        $user->hasRole('assistant-coach') => __('Assistant Coach'),
        $user->hasRole('team-manager') => __('Team Manager'),
        $user->hasRole('finance-officer') => __('Finance Officer'),
        $user->hasRole('media-officer') => __('Media Officer'),
        $user->hasRole('safeguarding-officer') => __('Welfare Officer'),
        $user->hasRole('player') => __('Player'),
        $user->hasRole('parent') => __('Parent'),
        $user->hasRole('partner') => __('Partner'),
        default => __('Staff Portal')
    };

    // Get notification count
    $pendingPlayers = \App\Models\Player::where('registration_status', 'Pending')->count();
    $pendingPartners = \App\Models\User::where('user_type', 'partner')->where('approval_status', 'pending')->count();
    $totalNotifications = $pendingPlayers + $pendingPartners;
@endphp

<header class="{{ $headerClass }}" role="banner">
    <div class="dashboard-header-container">
        {{-- Mobile Toggle Button --}}
        <button class="dashboard-mobile-toggle"
                type="button"
                aria-label="{{ __('Toggle navigation menu') }}"
                aria-expanded="false"
                aria-controls="sidebar"
                onclick="toggleSidebar()">
            <i class="fas fa-bars" aria-hidden="true"></i>
        </button>

        {{-- Brand / Logo --}}
        <a href="{{ $dashboardRoute }}" class="dashboard-brand" aria-label="{{ __('Go to dashboard') }}">
            <span class="dashboard-logo-text" style="font-weight: 700; font-size: 1.25rem; color: #ea1c4d;">GameSuite</span>
        </a>

        {{-- Optional Breadcrumbs --}}
        @if(!empty($breadcrumbs))
        <nav class="dashboard-breadcrumbs" aria-label="{{ __('Breadcrumb navigation') }}">
            <ol class="breadcrumb">
                @foreach($breadcrumbs as $breadcrumb)
                    @if(isset($breadcrumb['url']) && !$loop->last)
                        <li class="breadcrumb-item"><a href="{{ $breadcrumb['url'] }}">{{ $breadcrumb['label'] }}</a></li>
                    @else
                        <li class="breadcrumb-item active" aria-current="page">{{ $breadcrumb['label'] }}</li>
                    @endif
                @endforeach
            </ol>
        </nav>
        @endif

        {{-- Header Actions --}}
        <div class="dashboard-header-actions">
            {{-- Slot for custom actions --}}
            @if($actions)
                {{ $actions }}
            @endif

            {{-- Notifications --}}
            <div class="dropdown">
                <button class="dashboard-action-btn notification-btn"
                        type="button"
                        data-bs-toggle="dropdown"
                        aria-label="{{ __('Notifications') }}"
                        aria-haspopup="true"
                        aria-expanded="false">
                    <i class="fas fa-bell" aria-hidden="true"></i>
                    @if($totalNotifications > 0)
                    <span class="dashboard-notification-badge" aria-label="{{ $totalNotifications }} {{ __('notifications') }}">
                        {{ $totalNotifications > 9 ? '9+' : $totalNotifications }}
                    </span>
                    @endif
                </button>
                <div class="dropdown-menu dropdown-menu-end dashboard-dropdown-menu" role="menu">
                    <div class="dropdown-header">{{ __('Notifications') }}</div>
                    @if($pendingPlayers > 0)
                        @if($user->hasAnyRole(['super-admin', 'operations-admin', 'coach', 'head-coach', 'team-manager']))
                        <a class="dropdown-item dashboard-dropdown-item" href="{{ route('admin.players.index') }}" role="menuitem">
                            <i class="fas fa-user-plus text-success me-2"></i>
                            {{ $pendingPlayers }} {{ __('pending registrations') }}
                        </a>
                        @else
                        <a class="dropdown-item dashboard-dropdown-item" href="{{ route('coach.players') }}" role="menuitem">
                            <i class="fas fa-user-plus text-success me-2"></i>
                            {{ $pendingPlayers }} {{ __('pending registrations') }}
                        </a>
                        @endif
                    @endif
                    @if($totalNotifications === 0)
                        <span class="dropdown-item-text text-muted">{{ __('No notifications') }}</span>
                    @endif
                </div>
            </div>

            {{-- User Menu --}}
            <div class="dropdown">
                <button class="dashboard-user-menu"
                        type="button"
                        data-bs-toggle="dropdown"
                        aria-label="{{ __('User menu for') }} {{ $user->name }}"
                        aria-haspopup="true"
                        aria-expanded="false">
                    <div class="dashboard-user-avatar" aria-hidden="true">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                    <span class="dashboard-user-name">{{ $user->name }}</span>
                    <i class="fas fa-chevron-down" aria-hidden="true"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end dashboard-dropdown-menu" role="menu">
                    <a class="dropdown-item dashboard-dropdown-item" href="{{ $dashboardRoute }}" role="menuitem">
                        <i class="fas fa-th-large me-2"></i>{{ __('My Dashboard') }}
                    </a>
                    <a class="dropdown-item dashboard-dropdown-item" href="{{ route('profile.edit') }}" role="menuitem">
                        <i class="fas fa-user me-2"></i>{{ __('My Profile') }}
                    </a>
                    <a class="dropdown-item dashboard-dropdown-item" href="{{ route('players.index') }}" target="_blank" role="menuitem">
                        <i class="fas fa-external-link-alt me-2"></i>{{ __('View Website') }}
                    </a>
                    <div class="dropdown-divider"></div>
                    <form method="POST" action="{{ route('logout') }}" role="none">
                        @csrf
                        <button type="submit" class="dropdown-item dashboard-dropdown-item text-danger" role="menuitem">
                            <i class="fas fa-sign-out-alt me-2"></i>{{ __('Logout') }}
                        </button>
                    </form>
                </ul>
            </div>
        </div>
    </div>
</header>

