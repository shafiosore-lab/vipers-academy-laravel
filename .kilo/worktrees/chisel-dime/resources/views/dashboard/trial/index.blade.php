<x-layouts.dashboard-base :userType="$userType" :taskbars="$taskbars">
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-gray-900">Trial Dashboard</h2>
            <div class="flex items-center space-x-4">
                <span class="text-sm text-gray-500">Limited Features</span>
                <span class="px-3 py-1 bg-orange-100 text-orange-800 text-sm font-medium rounded-full">
                    {{ $userType['display_name'] }}
                </span>
                @if(isset($dashboardData['trial_info']))
                    <span class="px-3 py-1 bg-red-100 text-red-800 text-sm font-medium rounded-full">
                        {{ $dashboardData['trial_info']['days_left'] }} days left
                    </span>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Trial Warning Banner -->
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-yellow-800">
                        Trial Account - Limited Features
                    </p>
                    <p class="mt-1 text-sm text-yellow-700">
                        You have access to basic features. Upgrade to unlock full functionality.
                    </p>
                </div>
                <div class="ml-auto pl-3">
                    <div class="-mx-1.5 -my-1.5">
                        <a href="{{ route('trial.upgrade') }}" class="inline-flex rounded-md bg-yellow-50 p-1.5 text-yellow-500 hover:bg-yellow-100 focus:outline-none focus:ring-2 focus:ring-yellow-600 focus:ring-offset-2 focus:ring-offset-yellow-50">
                            <span class="sr-only">Upgrade</span>
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>

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
                        'description' => 'View basic dashboard information',
                        'url' => route('trial.dashboard'),
                        'icon' => 'fas fa-tachometer-alt'
                    ],
                    [
                        'title' => 'Limited Player Management',
                        'description' => 'Basic player management features',
                        'url' => route('trial.players'),
                        'icon' => 'fas fa-users'
                    ],
                    [
                        'title' => 'Feature Demonstrations',
                        'description' => 'See what\'s available in full version',
                        'url' => route('trial.demos'),
                        'icon' => 'fas fa-play-circle'
                    ],
                    [
                        'title' => 'Upgrade Options',
                        'description' => 'View plans and upgrade your account',
                        'url' => route('trial.upgrade'),
                        'icon' => 'fas fa-arrow-up'
                    ]
                ]" />
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Available Features -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Available Features</h3>
                    <div class="space-y-2">
                        @foreach($dashboardData['trial_info']['features_available'] as $feature)
                            <div class="flex items-center space-x-2">
                                <svg class="h-4 w-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-sm text-gray-600">{{ $feature }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Upgrade CTA -->
                <div class="bg-gradient-to-br from-orange-500 to-red-500 rounded-lg p-6 text-white">
                    <h3 class="text-lg font-semibold mb-2">{{ $dashboardData['trial_info']['upgrade_cta'] }}</h3>
                    <p class="text-orange-100 text-sm mb-4">
                        Unlock full access to all features and take your academy management to the next level.
                    </p>
                    <a href="{{ route('trial.upgrade') }}" class="inline-flex items-center px-4 py-2 bg-white text-orange-600 text-sm font-medium rounded-md hover:bg-gray-100 transition-colors">
                        View Plans
                        <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                        </svg>
                    </a>
                </div>

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
            </div>
        </div>
    </div>
</x-layouts.dashboard-base>
