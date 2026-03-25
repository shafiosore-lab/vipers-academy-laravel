<div class="taskbar-container">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex space-x-8">
            @foreach($taskbars as $taskbarKey => $taskbar)
                <div class="relative group">
                    <button class="flex items-center text-white px-4 py-3 text-sm font-medium taskbar-item {{ $loop->first ? 'active' : '' }}">
                        <i class="{{ $taskbar['icon'] }} mr-2"></i>
                        {{ $taskbar['name'] }}
                        <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <!-- Dropdown Menu -->
                    <div class="absolute left-0 mt-2 w-64 rounded-lg shadow-lg taskbar-dropdown hidden group-hover:block z-50">
                        <div class="py-2">
                            @foreach($taskbar['items'] as $item)
                                @if(isset($item['route']) && Route::has($item['route']))
                                    <a href="{{ route($item['route']) }}"
                                       class="taskbar-dropdown-item flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-50">
                                        <i class="{{ $item['icon'] }} mr-3 text-gray-400"></i>
                                        {{ $item['name'] }}
                                    </a>
                                @else
                                    <span class="taskbar-dropdown-item flex items-center px-4 py-3 text-sm text-gray-400 cursor-not-allowed">
                                        <i class="{{ $item['icon'] }} mr-3"></i>
                                        {{ $item['name'] }} <span class="ml-2 text-xs text-gray-500">(Coming Soon)</span>
                                    </span>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
