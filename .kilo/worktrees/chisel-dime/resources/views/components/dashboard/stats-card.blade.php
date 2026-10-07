<div class="dashboard-card bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-lg transition-shadow duration-300">
    <div class="flex items-center justify-between">
        <div class="flex-1">
            <p class="text-sm font-medium text-gray-600">{{ $label }}</p>
            <p class="mt-1 text-2xl font-semibold text-gray-900">{{ $value }}</p>
            @if(isset($subtext))
                <p class="mt-1 text-xs text-gray-500">{{ $subtext }}</p>
            @endif
        </div>
        <div class="flex-shrink-0">
            <div class="h-12 w-12 rounded-md {{ $bgClass ?? 'bg-blue-500' }} flex items-center justify-center">
                <i class="{{ $icon }} text-white text-lg"></i>
            </div>
        </div>
    </div>

    @if(isset($trend))
        <div class="mt-4 flex items-center text-sm">
            <span class="flex items-center {{ $trend['direction'] === 'up' ? 'text-green-600' : 'text-red-600' }}">
                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="{{ $trend['direction'] === 'up' ? 'M13 7h8m0 0v8m0-8l-8 8-4-4-8-8' : 'M13 17h8m0 0V9m0 8l-8-8-4 4-8 8' }}"></path>
                </svg>
                {{ $trend['value'] }}% {{ $trend['direction'] === 'up' ? 'increase' : 'decrease' }}
            </span>
            <span class="ml-2 text-gray-500">from last month</span>
        </div>
    @endif
</div>
