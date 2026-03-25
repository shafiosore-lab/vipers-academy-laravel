<x-layouts.dashboard-base :userType="$userType" :taskbars="$taskbars">
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-gray-900">Academy Admin Dashboard</h2>
            <div class="flex items-center space-x-4">
                <span class="text-sm text-gray-500">Organization Management</span>
                <span class="px-3 py-1 bg-blue-100 text-blue-800 text-sm font-medium rounded-full">
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
                        'title' => 'Player Management',
                        'description' => 'Manage academy players and their profiles',
                        'url' => route('admin.players.index'),
                        'icon' => 'fas fa-users'
                    ],
                    [
                        'title' => 'Staff Management',
                        'description' => 'Manage coaching and administrative staff',
                        'url' => route('admin.staff.index'),
                        'icon' => 'fas fa-user-tie'
                    ],
                    [
                        'title' => 'Program Management',
                        'description' => 'Manage training programs and schedules',
                        'url' => route('admin.programs.index'),
                        'icon' => 'fas fa-calendar-alt'
                    ],
                    [
                        'title' => 'Financial Reports',
                        'description' => 'View financial reports and billing',
                        'url' => route('admin.finance.reports'),
                        'icon' => 'fas fa-chart-pie'
                    ],
                    [
                        'title' => 'Document Management',
                        'description' => 'Manage academy documents and compliance',
                        'url' => route('admin.documents.index'),
                        'icon' => 'fas fa-folder'
                    ],
                    [
                        'title' => 'Compliance Tracking',
                        'description' => 'Monitor compliance and regulatory requirements',
                        'url' => route('admin.compliance'),
                        'icon' => 'fas fa-check-circle'
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

                <!-- Quick Links -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Links</h3>
                    <div class="space-y-2">
                        <a href="{{ route('admin.dashboard') }}" class="block text-sm text-gray-600 hover:text-gray-900">
                            <i class="fas fa-tachometer-alt mr-2 text-gray-400"></i>
                            Main Dashboard
                        </a>
                        <a href="{{ route('admin.players.create') }}" class="block text-sm text-gray-600 hover:text-gray-900">
                            <i class="fas fa-user-plus mr-2 text-gray-400"></i>
                            Add New Player
                        </a>
                        <a href="{{ route('admin.staff.create') }}" class="block text-sm text-gray-600 hover:text-gray-900">
                            <i class="fas fa-user-tie mr-2 text-gray-400"></i>
                            Add New Staff
                        </a>
                        <a href="{{ route('admin.programs.create') }}" class="block text-sm text-gray-600 hover:text-gray-900">
                            <i class="fas fa-calendar-plus mr-2 text-gray-400"></i>
                            Create Program
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dashboard-base>
