<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-900">Quick Actions</h3>
        <span class="text-sm text-gray-500">Access your most used features</span>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($actions as $action)
            <a href="{{ $action['url'] }}"
               class="quick-action-btn group flex items-center p-4 rounded-lg hover:shadow-lg transition-all duration-300">
                <div class="flex-shrink-0">
                    <i class="{{ $action['icon'] }} text-white text-xl"></i>
                </div>
                <div class="ml-4 text-left">
                    <h4 class="font-medium text-white">{{ $action['title'] }}</h4>
                    <p class="text-xs text-white opacity-80 mt-1">{{ $action['description'] }}</p>
                </div>
                <div class="ml-auto">
                    <svg class="h-5 w-5 text-white opacity-80 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </div>
            </a>
        @endforeach
    </div>
</div>
