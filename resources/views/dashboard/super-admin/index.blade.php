<x-layouts.dashboard-base :userType="$userType" :taskbars="$taskbars">
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-gray-900">Super Admin Dashboard</h2>
            <div class="flex items-center space-x-4">
                <span class="text-sm text-gray-500">Platform Overview</span>
                <span class="px-3 py-1 bg-green-100 text-green-800 text-sm font-medium rounded-full">
                    System Status: Operational
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
                        'title' => 'Manage Organizations',
                        'description' => 'View and manage all academy organizations',
                        'url' => route('super-admin.organizations.index'),
                        'icon' => 'fas fa-building'
                    ],
                    [
                        'title' => 'User Management',
                        'description' => 'Manage platform users and permissions',
                        'url' => route('super-admin.users.index'),
                        'icon' => 'fas fa-users'
                    ],
                    [
                        'title' => 'System Analytics',
                        'description' => 'View platform-wide analytics and reports',
                        'url' => route('super-admin.analytics'),
                        'icon' => 'fas fa-chart-line'
                    ],
                    [
                        'title' => 'Role Management',
                        'description' => 'Configure roles and permissions',
                        'url' => route('super-admin.roles.index'),
                        'icon' => 'fas fa-user-shield'
                    ],
                    [
                        'title' => 'Subscription Plans',
                        'description' => 'Manage subscription plans and pricing',
                        'url' => route('super-admin.plans.index'),
                        'icon' => 'fas fa-credit-card'
                    ],
                    [
                        'title' => 'Audit Logs',
                        'description' => 'Review system audit logs and compliance',
                        'url' => route('super-admin.roles.audit'),
                        'icon' => 'fas fa-file-alt'
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

                <!-- System Health -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">System Health</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Database Status</span>
                            <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded">Healthy</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">API Response Time</span>
                            <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded">Optimal</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Storage Usage</span>
                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded">65%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dashboard-base>
