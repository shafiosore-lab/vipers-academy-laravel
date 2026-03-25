<x-layouts.dashboard-base :userType="$currentDashboard" :taskbars="[]">
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-gray-900">Dashboard Switcher</h2>
            <div class="flex items-center space-x-4">
                <span class="text-sm text-gray-500">Multi-Role Access</span>
                <span class="px-3 py-1 bg-blue-100 text-blue-800 text-sm font-medium rounded-full">
                    {{ $currentDashboard['display_name'] }}
                </span>
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Current Dashboard Info -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Current Dashboard</h3>
            <p class="text-gray-600">You are currently viewing the {{ $currentDashboard['display_name'] }} dashboard.</p>
        </div>

        <!-- Available Dashboards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($availableDashboards as $dashboardKey => $dashboard)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <i class="{{ $dashboard['icon'] }} text-2xl text-gray-400"></i>
                            <div>
                                <h4 class="text-lg font-semibold text-gray-900">{{ $dashboard['name'] }}</h4>
                                <p class="text-sm text-gray-500">Access {{ $dashboard['name'] }} features</p>
                            </div>
                        </div>
                        @if($dashboardKey === $currentDashboard['user_type'])
                            <span class="px-3 py-1 bg-green-100 text-green-800 text-sm font-medium rounded-full">
                                Current
                            </span>
                        @endif
                    </div>

                    <div class="flex space-x-3">
                        <a href="{{ route($dashboard['route']) }}"
                           class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-blue-700 transition-colors text-center">
                            Switch to {{ $dashboard['name'] }}
                        </a>
                        <a href="{{ route('dashboard.user-type', ['userType' => $dashboardKey]) }}"
                           class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md text-sm font-medium hover:bg-gray-50 transition-colors">
                            Preview
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Quick Actions -->
        <div class="mt-8 bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="{{ route('dashboard') }}"
                   class="block p-4 border border-gray-200 rounded-lg hover:border-blue-300 hover:bg-blue-50 transition-colors">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-tachometer-alt text-blue-600"></i>
                        <div>
                            <h4 class="font-medium text-gray-900">Main Dashboard</h4>
                            <p class="text-sm text-gray-500">Return to main dashboard</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('profile.show') }}"
                   class="block p-4 border border-gray-200 rounded-lg hover:border-green-300 hover:bg-green-50 transition-colors">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-user text-green-600"></i>
                        <div>
                            <h4 class="font-medium text-gray-900">Profile</h4>
                            <p class="text-sm text-gray-500">View your profile</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('dashboard.switcher') }}"
                   class="block p-4 border border-gray-200 rounded-lg hover:border-purple-300 hover:bg-purple-50 transition-colors">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-exchange-alt text-purple-600"></i>
                        <div>
                            <h4 class="font-medium text-gray-900">Switch Again</h4>
                            <p class="text-sm text-gray-500">Change dashboard again</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</x-layouts.dashboard-base>
