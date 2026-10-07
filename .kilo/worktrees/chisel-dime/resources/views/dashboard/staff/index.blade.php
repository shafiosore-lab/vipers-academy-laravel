<x-layouts.dashboard-base :userType="$userType" :taskbars="$taskbars">
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-gray-900">Staff Dashboard</h2>
            <div class="flex items-center space-x-4">
                <span class="text-sm text-gray-500">Core Functions</span>
                <span class="px-3 py-1 bg-green-100 text-green-800 text-sm font-medium rounded-full">
                    {{ $userType['display_name'] }}
                </span>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Quick Stats -->
        <x-dashboard-grid columns="4" gap="lg" variant="default" class="stats-grid">
            @foreach($dashboardData['quick_stats'] as $stat)
                <x-dashboard-card variant="default" spacing="md">
                    <div class="stat-content">
                        <div class="stat-value">{{ $stat['value'] }}</div>
                        <div class="stat-label">{{ $stat['label'] }}</div>
                        <div class="stat-icon">
                            <i class="{{ $stat['icon'] }}"></i>
                        </div>
                    </div>
                </x-dashboard-card>
            @endforeach
        </x-dashboard-grid>

        <!-- Main Content Grid -->
        <x-dashboard-grid columns="3" gap="lg" variant="default" class="main-grid">
            <!-- Primary Actions -->
            <div class="lg:col-span-2">
                <x-dashboard-card variant="default" spacing="md">
                    <div class="card-header">
                        <h3>Quick Actions</h3>
                    </div>
                    <div class="card-body">
                        <x-dashboard-grid columns="2" gap="md" variant="minimal" class="actions-grid">
                            <a href="{{ route('staff.dashboard') }}" class="action-card">
                                <div class="action-icon">
                                    <i class="fas fa-tachometer-alt"></i>
                                </div>
                                <div class="action-content">
                                    <div class="action-title">Dashboard Overview</div>
                                    <div class="action-desc">View your personalized dashboard</div>
                                </div>
                            </a>

                            <a href="{{ route('staff.communication') }}" class="action-card">
                                <div class="action-icon">
                                    <i class="fas fa-comments"></i>
                                </div>
                                <div class="action-content">
                                    <div class="action-title">Communication Center</div>
                                    <div class="action-desc">Manage messages and announcements</div>
                                </div>
                            </a>

                            <a href="{{ route('staff.reports') }}" class="action-card">
                                <div class="action-icon">
                                    <i class="fas fa-file-alt"></i>
                                </div>
                                <div class="action-content">
                                    <div class="action-title">Reports & Analytics</div>
                                    <div class="action-desc">Generate and view reports</div>
                                </div>
                            </a>

                            <a href="{{ route('coach.training-sessions') }}" class="action-card">
                                <div class="action-icon">
                                    <i class="fas fa-dumbbell"></i>
                                </div>
                                <div class="action-content">
                                    <div class="action-title">Training Management</div>
                                    <div class="action-desc">Manage training sessions and schedules</div>
                                </div>
                            </a>

                            <a href="{{ route('coach.player-progress') }}" class="action-card">
                                <div class="action-icon">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                                <div class="action-content">
                                    <div class="action-title">Player Progress</div>
                                    <div class="action-desc">Track player development and performance</div>
                                </div>
                            </a>

                            <a href="{{ route('manager.equipment') }}" class="action-card">
                                <div class="action-icon">
                                    <i class="fas fa-box"></i>
                                </div>
                                <div class="action-content">
                                    <div class="action-title">Equipment Management</div>
                                    <div class="action-desc">Manage equipment inventory and distribution</div>
                                </div>
                            </a>
                        </x-dashboard-grid>
                    </div>
                </x-dashboard-card>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Recent Activity -->
                <x-dashboard-card variant="default" spacing="md">
                    <div class="card-header">
                        <h3>Recent Activity</h3>
                    </div>
                    <div class="card-body">
                        <div class="space-y-3">
                            @foreach($dashboardData['recent_activity'] as $activity)
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0">
                                        <i class="h-8 w-8 {{ $activity['icon'] }} text-{{ $activity['color'] }}-500 bg-{{ $activity['color'] }}-100 rounded-full p-2"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900">{{ $activity['title'] }}</p>
                                        <p class="text-xs text-gray-500">{{ $activity['time'] }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </x-dashboard-card>

                <!-- Notifications -->
                <x-dashboard-card variant="default" spacing="md">
                    <div class="card-header">
                        <h3>Notifications</h3>
                    </div>
                    <div class="card-body">
                        <div class="space-y-3">
                            @foreach($dashboardData['notifications'] as $notification)
                                <div class="flex items-center justify-between p-3 bg-{{ $notification['read'] ? 'gray' : 'blue' }}-50 rounded-lg">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $notification['title'] }}</p>
                                        <p class="text-xs text-gray-500">{{ $notification['time'] }}</p>
                                    </div>
                                    @unless($notification['read'])
                                        <span class="h-2 w-2 bg-blue-500 rounded-full"></span>
                                    @endunless
                                </div>
                            @endforeach
                        </div>
                    </div>
                </x-dashboard-card>

                <!-- Staff Tools -->
                <x-dashboard-card variant="default" spacing="md">
                    <div class="card-header">
                        <h3>Specialized Tools</h3>
                    </div>
                    <div class="card-body">
                        <div class="space-y-2">
                            @if(auth()->user()->hasAnyRole(['coach', 'assistant-coach', 'head-coach']))
                                <a href="{{ route('coach.training-sessions') }}" class="block text-sm text-gray-600 hover:text-gray-900">
                                    <i class="fas fa-dumbbell mr-2 text-gray-400"></i>
                                    Training Management
                                </a>
                                <a href="{{ route('coach.player-progress') }}" class="block text-sm text-gray-600 hover:text-gray-900">
                                    <i class="fas fa-chart-line mr-2 text-gray-400"></i>
                                    Player Progress
                                </a>
                            @endif
                            @if(auth()->user()->hasRole('team-manager'))
                                <a href="{{ route('manager.equipment') }}" class="block text-sm text-gray-600 hover:text-gray-900">
                                    <i class="fas fa-box mr-2 text-gray-400"></i>
                                    Equipment Management
                                </a>
                                <a href="{{ route('manager.logistics') }}" class="block text-sm text-gray-600 hover:text-gray-900">
                                    <i class="fas fa-truck mr-2 text-gray-400"></i>
                                    Logistics
                                </a>
                            @endif
                            @if(auth()->user()->hasRole('media-officer'))
                                <a href="{{ route('media.content') }}" class="block text-sm text-gray-600 hover:text-gray-900">
                                    <i class="fas fa-camera mr-2 text-gray-400"></i>
                                    Content Management
                                </a>
                            @endif
                            @if(auth()->user()->hasRole('safeguarding-officer'))
                                <a href="{{ route('welfare.attention-list') }}" class="block text-sm text-gray-600 hover:text-gray-900">
                                    <i class="fas fa-exclamation-triangle mr-2 text-gray-400"></i>
                                    Attention List
                                </a>
                                <a href="{{ route('welfare.compliance') }}" class="block text-sm text-gray-600 hover:text-gray-900">
                                    <i class="fas fa-shield-alt mr-2 text-gray-400"></i>
                                    Compliance Monitoring
                                </a>
                            @endif
                            @if(auth()->user()->hasPermission('view_financials') || auth()->user()->hasPermission('finance.view') || auth()->user()->hasAnyRole(['finance-officer', 'finance-admin', 'operations-admin', 'org-admin', 'super-admin']))
                                <a href="{{ route('finance.dashboard') }}" class="block text-sm text-gray-600 hover:text-gray-900">
                                    <i class="fas fa-chart-bar mr-2 text-gray-400"></i>
                                    Financial Dashboard
                                </a>
                                <a href="{{ route('finance.payments') }}" class="block text-sm text-gray-600 hover:text-gray-900">
                                    <i class="fas fa-money-bill mr-2 text-gray-400"></i>
                                    Payment Processing
                                </a>
                            @endif
                        </div>
                    </div>
                </x-dashboard-card>
            </div>
        </x-dashboard-grid>
    </div>
</x-layouts.dashboard-base>
