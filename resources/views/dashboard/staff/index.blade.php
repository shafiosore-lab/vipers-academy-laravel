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
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            @foreach($dashboardData['quick_stats'] as $stat)
                <x-dashboard.stats-card
                    :label="$stat['label']"
                    :value="$stat['value']"
                    :icon="$stat['icon']"
                    :bgClass="$stat['bgClass']"
                />
            @endforeach
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Primary Actions -->
            <div class="lg:col-span-2">
                <x-dashboard.quick-actions :actions="[
                    [
                        'title' => 'Dashboard Overview',
                        'description' => 'View your personalized dashboard',
                        'url' => route('staff.dashboard'),
                        'icon' => 'fas fa-tachometer-alt'
                    ],
                    [
                        'title' => 'Communication Center',
                        'description' => 'Manage messages and announcements',
                        'url' => route('staff.communication'),
                        'icon' => 'fas fa-comments'
                    ],
                    [
                        'title' => 'Reports & Analytics',
                        'description' => 'Generate and view reports',
                        'url' => route('staff.reports'),
                        'icon' => 'fas fa-file-alt'
                    ],
                    [
                        'title' => 'Training Management',
                        'description' => 'Manage training sessions and schedules',
                        'url' => route('coach.training-sessions'),
                        'icon' => 'fas fa-dumbbell'
                    ],
                    [
                        'title' => 'Player Progress',
                        'description' => 'Track player development and performance',
                        'url' => route('coach.player-progress'),
                        'icon' => 'fas fa-chart-line'
                    ],
                    [
                        'title' => 'Equipment Management',
                        'description' => 'Manage equipment inventory and distribution',
                        'url' => route('manager.equipment'),
                        'icon' => 'fas fa-box'
                    ]
                ]" />
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Recent Activity -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Activity</h3>
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

                <!-- Notifications -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Notifications</h3>
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

                <!-- Staff Tools -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Specialized Tools</h3>
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
            </div>
        </div>
    </div>
</x-layouts.dashboard-base>
