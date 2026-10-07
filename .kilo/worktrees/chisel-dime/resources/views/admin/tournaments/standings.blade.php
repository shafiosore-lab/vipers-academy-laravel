@extends('layouts.admin')

@section('title', 'Standings - ' . $tournament->name)

@section('header')
<div class="flex items-center gap-4">
    <a href="{{ route('admin.tournaments.show', $tournament->id) }}" class="inline-flex items-center justify-center w-10 h-10 rounded-lg border border-gray-300 bg-white text-gray-500 hover:bg-gray-50 hover:text-gray-700 transition">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
    </a>
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Standings</h1>
        <p class="mt-1 text-sm text-gray-500">{{ $tournament->name }}</p>
    </div>
</div>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Teams</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $tournament->approvedTeams()->count() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Players</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $tournament->squads()->count() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Matches</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $tournament->matches()->count() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-indigo-500 rounded-md p-3">
                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Completed</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $tournament->matches()->where('status', 'completed')->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Standings Table -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-lg font-medium text-gray-900">League Table</h3>
            <form action="{{ route('admin.tournaments.recalculate-standings', $tournament->id) }}" method="POST">
                @csrf
                <button type="submit" class="inline-flex items-center px-3 py-1.5 border border-gray-300 text-gray-700 bg-white rounded-md text-sm font-medium hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Recalculate
                </button>
            </form>
        </div>
        @if($tournament->standings->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pos</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Team</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">P</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">W</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">D</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">L</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">GF</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">GA</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">GD</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider font-bold">Pts</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($tournament->standings->sortBy('position') as $index => $standing)
                            @php
                                $teamName = null;
                                if ($standing->team) {
                                    if ($standing->team->team && isset($standing->team->team->name)) {
                                        $teamName = $standing->team->team->name;
                                    } elseif (isset($standing->team->team_name)) {
                                        $teamName = $standing->team->team_name;
                                    }
                                }
                            @endphp
                            <tr class="{{ $standing->position <= 3 ? 'bg-green-50' : '' }} hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        @if($standing->position == 1)
                                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-yellow-400 text-white font-bold text-sm">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                            </span>
                                        @elseif($standing->position == 2)
                                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-400 text-white font-bold text-sm">
                                                {{ $standing->position }}
                                            </span>
                                        @elseif($standing->position == 3)
                                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-amber-600 text-white font-bold text-sm">
                                                {{ $standing->position }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-200 text-gray-600 font-medium text-sm">
                                                {{ $standing->position }}
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="font-medium text-gray-900">{{ $teamName ?? 'N/A' }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">{{ $standing->played }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">{{ $standing->won }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">{{ $standing->drawn }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">{{ $standing->lost }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">{{ $standing->goals_for }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">{{ $standing->goals_against }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-sm font-medium {{ $standing->goal_difference > 0 ? 'bg-green-100 text-green-800' : ($standing->goal_difference < 0 ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800') }}">
                                        {{ $standing->goal_difference > 0 ? '+' : '' }}{{ $standing->goal_difference }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="text-lg font-bold text-gray-900">{{ $standing->points }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No standings available</h3>
                <p class="mt-1 text-sm text-gray-500">Standings will be calculated once the tournament starts and matches are played.</p>
            </div>
        @endif
    </div>

    <!-- Legend -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="px-6 py-4">
            <h4 class="text-sm font-medium text-gray-900 mb-3">Legend</h4>
            <div class="flex flex-wrap gap-4 text-sm">
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-yellow-400 text-white">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    </span>
                    <span class="text-gray-600">Champion</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-gray-400 text-white text-xs font-bold">2</span>
                    <span class="text-gray-600">Runner-up</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-amber-600 text-white text-xs font-bold">3</span>
                    <span class="text-gray-600">Third Place</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center px-2 py-0.5 rounded bg-green-100 text-green-800 text-xs font-medium">+5</span>
                    <span class="text-gray-600">Positive Goal Difference</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center px-2 py-0.5 rounded bg-red-100 text-red-800 text-xs font-medium">-3</span>
                    <span class="text-gray-600">Negative Goal Difference</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
