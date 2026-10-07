<x-layouts.dashboard-base :userType="$userType" :taskbars="$taskbars">
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-gray-900">Parent Dashboard</h2>
            <div class="flex items-center space-x-4">
                <span class="text-sm text-gray-500">Child Management</span>
                <span class="px-3 py-1 bg-teal-100 text-teal-800 text-sm font-medium rounded-full">
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
                        'title' => 'Player Profile',
                        'description' => 'View your child\'s player profile and progress',
                        'url' => route('parent.player-profile'),
                        'icon' => 'fas fa-user-circle'
                    ],
                    [
                        'title' => 'Training Progress',
                        'description' => 'Track training progress and development',
                        'url' => route('parent.training'),
                        'icon' => 'fas fa-chart-line'
                    ],
                    [
                        'title' => 'Matches & Events',
                        'description' => 'View match schedules and academy events',
                        'url' => route('parent.matches'),
                        'icon' => 'fas fa-futbol'
                    ],
                    [
                        'title' => 'Media & Photos',
                        'description' => 'Access photos and videos of your child',
                        'url' => route('parent.media'),
                        'icon' => 'fas fa-camera'
                    ],
                    [
                        'title' => 'Insights & Analytics',
                        'description' => 'View performance insights and analytics',
                        'url' => route('parent.insights'),
                        'icon' => 'fas fa-chart-bar'
                    ],
                    [
                        'title' => 'Announcements',
                        'description' => 'Stay updated with academy announcements',
                        'url' => route('parent.announcements'),
                        'icon' => 'fas fa-bullhorn'
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
                        <a href="{{ route('parent.player-profile') }}" class="block text-sm text-gray-600 hover:text-gray-900">
                            <i class="fas fa-user-circle mr-2 text-gray-400"></i>
                            Child Profile
                        </a>
                        <a href="{{ route('parent.training') }}" class="block text-sm text-gray-600 hover:text-gray-900">
                            <i class="fas fa-chart-line mr-2 text-gray-400"></i>
                            Training Progress
                        </a>
                        <a href="{{ route('parent.matches') }}" class="block text-sm text-gray-600 hover:text-gray-900">
                            <i class="fas fa-futbol mr-2 text-gray-400"></i>
                            Matches & Events
                        </a>
                        <a href="{{ route('parent.media') }}" class="block text-sm text-gray-600 hover:text-gray-900">
                            <i class="fas fa-camera mr-2 text-gray-400"></i>
                            Media Gallery
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dashboard-base>
