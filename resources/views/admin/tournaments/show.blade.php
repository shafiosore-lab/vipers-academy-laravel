@extends('layouts.admin')

@section('title', $tournament->name)

@section('header')
<div class="flex items-center gap-4">
    <a href="{{ route('admin.tournaments.index') }}" class="inline-flex items-center justify-center w-10 h-10 rounded-lg border border-gray-300 bg-white text-gray-500 hover:bg-gray-50 hover:text-gray-700 transition">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
    </a>
    <div class="flex-1">
        <div class="flex items-center gap-3">
            <h1 class="text-2xl font-bold text-gray-900">{{ $tournament->name }}</h1>
            @php
                $statusColors = [
                    'draft' => 'bg-gray-100 text-gray-800',
                    'open' => 'bg-green-100 text-green-800',
                    'closed' => 'bg-yellow-100 text-yellow-800',
                    'ongoing' => 'bg-blue-100 text-blue-800',
                    'completed' => 'bg-indigo-100 text-indigo-800',
                    'cancelled' => 'bg-red-100 text-red-800',
                ];
            @endphp
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusColors[$tournament->status] ?? 'bg-gray-100 text-gray-800' }}">
                {{ ucfirst($tournament->status) }}
            </span>
        </div>
        <p class="mt-1 text-sm text-gray-500">{{ $tournament->organization->name ?? 'N/A' }} • {{ $tournament->season ?? 'No season' }}</p>
    </div>
    <a href="{{ route('admin.tournaments.edit', $tournament->id) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
        </svg>
        Edit
    </a>
</div>
@endsection

@section('content')
<div x-data="{ activeTab: window.location.hash ? window.location.hash.substring(1) : 'overview' }" x-init="window.addEventListener('hashchange', () => activeTab = window.location.hash ? window.location.hash.substring(1) : 'overview'); $watch('activeTab', (value) => window.location.hash = value);">

    <!-- Tab Navigation -->
    <div class="border-b border-gray-200 mb-6">
        <nav class="-mb-px flex space-x-8">
            <button @click="activeTab = 'overview'" :class="activeTab === 'overview' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition">
                <svg class="w-5 h-5 inline-block mr-2 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Overview
            </button>
            <button @click="activeTab = 'matches'" :class="activeTab === 'matches' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition">
                <svg class="w-5 h-5 inline-block mr-2 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Matches
            </button>
            <button @click="activeTab = 'teams'" :class="activeTab === 'teams' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition">
                <svg class="w-5 h-5 inline-block mr-2 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                Teams
            </button>
            <button @click="activeTab = 'standings'" :class="activeTab === 'standings' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition">
                <svg class="w-5 h-5 inline-block mr-2 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                </svg>
                Standings
            </button>
        </nav>
    </div>

    <!-- Stats Row - Compact (Visible on all tabs) -->
    <div class="grid grid-cols-4 gap-3">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-3">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-blue-500 rounded p-2">
                    <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-xs font-medium text-gray-500">Teams</p>
                    <p class="text-sm font-semibold text-gray-900">{{ $approvedTeams->count() }}<span class="text-xs text-gray-500">/{{ $tournament->max_teams ?? '∞' }}</span></p>
                </div>
            </div>
        </div>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-3">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-green-500 rounded p-2">
                    <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-xs font-medium text-gray-500">Completed</p>
                    <p class="text-sm font-semibold text-gray-900">{{ $tournament->matches()->where('status', 'completed')->count() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-3">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-yellow-500 rounded p-2">
                    <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-xs font-medium text-gray-500">Scheduled</p>
                    <p class="text-sm font-semibold text-gray-900">{{ $tournament->matches()->where('status', 'scheduled')->count() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-3">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-indigo-500 rounded p-2">
                    <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-xs font-medium text-gray-500">Total Matches</p>
                    <p class="text-sm font-semibold text-gray-900">{{ $tournament->matches()->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Overview Tab -->
    <div x-show="activeTab === 'overview'" x-cloak>
        <div class="space-y-4">

    <!-- Quick Actions - Compact -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="px-4 py-2 border-b border-gray-200">
            <h3 class="text-sm font-medium text-gray-900">Quick Actions</h3>
        </div>
        <div class="p-3">
            <div class="flex flex-wrap gap-2">
                <!-- Status Actions -->
                @if(in_array($tournament->status, ['draft', 'closed']))
                    <form action="{{ route('admin.tournaments.open-registration', $tournament->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-green-600 border border-transparent rounded font-semibold text-xs text-white hover:bg-green-700 active:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/>
                            </svg>
                            Open Registration
                        </button>
                    </form>
                @endif
                @if($tournament->status === 'open')
                    <form action="{{ route('admin.tournaments.close-registration', $tournament->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-yellow-600 border border-transparent rounded font-semibold text-xs text-white hover:bg-yellow-700 active:bg-yellow-800 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            Close Registration
                        </button>
                    </form>
                @endif
                @if($tournament->status === 'closed' && $approvedTeams->count() >= 2)
                    <form action="{{ route('admin.tournaments.start', $tournament->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-blue-600 border border-transparent rounded font-semibold text-xs text-white hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Start Tournament
                        </button>
                    </form>
                @endif
                @if($tournament->status === 'ongoing')
                    <form action="{{ route('admin.tournaments.complete', $tournament->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-indigo-600 border border-transparent rounded font-semibold text-xs text-white hover:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Complete Tournament
                        </button>
                    </form>
                @endif

                <!-- Match Center Button -->
                @if(in_array($tournament->status, ['open', 'closed', 'ongoing']))
                    <button onclick="openMatchCenter()" class="inline-flex items-center px-3 py-1.5 bg-purple-600 border border-transparent rounded font-semibold text-xs text-white hover:bg-purple-700 active:bg-purple-800 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        Match Center
                    </button>
                @endif
                <a href="{{ route('admin.tournaments.matches.create', $tournament->id) }}" class="inline-flex items-center px-3 py-1.5 bg-indigo-600 border border-transparent rounded font-semibold text-xs text-white hover:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Create Match
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Upcoming Matches - Compact -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="px-4 py-2 border-b border-gray-200 flex items-center justify-between">
                    <h3 class="text-sm font-medium text-gray-900">Upcoming Matches</h3>
                    <a href="{{ route('admin.tournaments.matches.index', $tournament->id) }}" class="text-xs text-indigo-600 hover:text-indigo-900">View all →</a>
                </div>
                <div class="divide-y divide-gray-200">
                    @php
                        $upcomingMatches = $tournament->matches()
                            ->with(['homeTeam', 'awayTeam'])
                            ->where('status', 'scheduled')
                            ->where('kickoff_time', '>', now())
                            ->orderBy('kickoff_time', 'asc')
                            ->limit(5)
                            ->get();
                    @endphp
                    @forelse($upcomingMatches as $match)
                        <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-50">
                            <div class="flex-1">
                                <div class="flex items-center gap-4">
                                    <div class="text-right flex-1">
                                        <p class="font-medium text-gray-900">{{ $match->homeTeam->team_name ?? 'TBD' }}</p>
                                    </div>
                                    <div class="flex-shrink-0 w-16 text-center">
                                        <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-gray-100 text-gray-600 font-semibold text-sm">vs</span>
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-medium text-gray-900">{{ $match->awayTeam->team_name ?? 'TBD' }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="ml-6 text-right">
                                <p class="text-sm font-medium text-gray-900">{{ $match->kickoff_time ? \Carbon\Carbon::parse($match->kickoff_time)->format('M d') : 'TBD' }}</p>
                                <p class="text-sm text-gray-500">{{ $match->kickoff_time ? \Carbon\Carbon::parse($match->kickoff_time)->format('H:i') : '' }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-8 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <p class="mt-2 text-sm text-gray-500">No upcoming matches scheduled</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Recent Results - Compact -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="px-4 py-2 border-b border-gray-200 flex items-center justify-between">
                    <h3 class="text-sm font-medium text-gray-900">Recent Results</h3>
                    <a href="{{ route('admin.tournaments.matches.index', $tournament->id) }}" class="text-xs text-indigo-600 hover:text-indigo-900">View all →</a>
                </div>
                <div class="divide-y divide-gray-200">
                    @php
                        $recentMatches = $tournament->matches()
                            ->with(['homeTeam', 'awayTeam'])
                            ->where('status', 'completed')
                            ->orderBy('kickoff_time', 'desc')
                            ->limit(5)
                            ->get();
                    @endphp
                    @forelse($recentMatches as $match)
                        <a href="{{ route('admin.tournaments.matches.show', [$tournament->id, $match->id]) }}" class="block px-4 py-3 hover:bg-gray-50">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3">
                                        <div class="text-right flex-1">
                                            <p class="text-sm font-medium {{ $match->home_score > $match->away_score ? 'text-gray-900' : 'text-gray-500' }}">{{ $match->homeTeam->team_name ?? 'TBD' }}</p>
                                        </div>
                                        <div class="flex-shrink-0 w-16 text-center">
                                            <span class="inline-flex items-center justify-center px-2 py-0.5 rounded {{ $match->home_score > $match->away_score ? 'bg-green-100 text-green-800' : ($match->home_score < $match->away_score ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800') }} text-xs font-bold">
                                                {{ $match->home_score }} - {{ $match->away_score }}
                                            </span>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm font-medium {{ $match->away_score > $match->home_score ? 'text-gray-900' : 'text-gray-500' }}">{{ $match->awayTeam->team_name ?? 'TBD' }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="ml-4 text-right">
                                    <p class="text-xs text-gray-500">{{ $match->kickoff_time ? \Carbon\Carbon::parse($match->kickoff_time)->format('M d') : '' }}</p>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="px-4 py-6 text-center">
                            <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <p class="mt-1 text-xs text-gray-500">No completed matches yet</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="space-y-6">
            <!-- Tournament Details - Compact -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="px-4 py-2 border-b border-gray-200">
                    <h3 class="text-sm font-medium text-gray-900">Details</h3>
                </div>
                <div class="p-4 space-y-3">
                    @if($tournament->competition_format)
                        <div>
                            <dt class="text-xs font-medium text-gray-500">Format</dt>
                            <dd class="text-sm text-gray-900">{{ $tournament->getFormatInfo()['name'] ?? $tournament->competition_format }}</dd>
                        </div>
                    @endif
                    <div>
                        <dt class="text-xs font-medium text-gray-500">Venue</dt>
                        <dd class="text-sm text-gray-900">{{ $tournament->venue ?? 'Not specified' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-500">Schedule</dt>
                        <dd class="text-sm text-gray-900">
                            @if($tournament->start_date)
                                {{ \Carbon\Carbon::parse($tournament->start_date)->format('M d') }}
                                @if($tournament->end_date)
                                    - {{ \Carbon\Carbon::parse($tournament->end_date)->format('M d, Y') }}
                                @endif
                            @else
                                Not set
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-500">Squad</dt>
                        <dd class="text-sm text-gray-900">Max: {{ $tournament->squad_limit }} | Min: {{ $tournament->min_players }}</dd>
                    </div>
                    @if($tournament->description)
                        <div>
                            <dt class="text-xs font-medium text-gray-500">Description</dt>
                            <dd class="text-sm text-gray-900">{{ Str::limit($tournament->description, 100) }}</dd>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Teams -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                    <h3 class="text-lg font-medium text-gray-900">Teams</h3>
                    <a href="{{ route('admin.tournaments.teams.create', $tournament->id) }}" class="text-sm text-indigo-600 hover:text-indigo-900">+ Add</a>
                </div>
                <div class="divide-y divide-gray-200 max-h-96 overflow-y-auto">
                    @forelse($approvedTeams as $team)
                        <div class="px-6 py-3 flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-900">{{ $team->team_name }}</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $team->squads()->count() }} players
                            </span>
                        </div>
                    @empty
                        <div class="px-6 py-8 text-center">
                            <p class="text-sm text-gray-500">No teams registered yet</p>
                        </div>
                    @endforelse
                    @if($pendingTeams->count() > 0)
                        <div class="px-6 py-3 bg-yellow-50">
                            <p class="text-xs font-semibold text-yellow-800 mb-2">Pending Approval ({{ $pendingTeams->count() }})</p>
                            @foreach($pendingTeams as $team)
                                <div class="flex items-center justify-between py-1">
                                    <span class="text-sm text-yellow-700">{{ $team->team_name }}</span>
                                    <form action="{{ route('admin.tournaments.teams.approve', [$tournament->id, $team->id]) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="text-xs text-green-600 hover:text-green-900 font-medium">Approve</button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
                <div class="px-6 py-3 border-t border-gray-200">
                    <a href="{{ route('admin.tournaments.teams.index', $tournament->id) }}" class="text-sm text-indigo-600 hover:text-indigo-900">View all teams →</a>
                </div>
            </div>

            <!-- Standings - Compact -->
            @if($standings->count() > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="px-4 py-2 border-b border-gray-200">
                        <h3 class="text-sm font-medium text-gray-900">Standings</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Team</th>
                                    <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase">P</th>
                                    <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase">W</th>
                                    <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase">D</th>
                                    <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase">L</th>
                                    <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase">GD</th>
                                    <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase font-bold">Pts</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($standings->take(5) as $standing)
                                    <tr class="{{ $loop->iteration <= 3 ? 'bg-green-50' : '' }}">
                                        <td class="px-3 py-2 text-xs {{ $standing->position <= 3 ? 'font-bold text-green-700' : 'text-gray-500' }}">{{ $standing->position }}</td>
                                        <td class="px-3 py-2 text-xs font-medium text-gray-900">{{ $standing->team->team_name ?? 'N/A' }}</td>
                                        <td class="px-3 py-2 text-xs text-center text-gray-500">{{ $standing->played }}</td>
                                        <td class="px-3 py-2 text-xs text-center text-gray-500">{{ $standing->won }}</td>
                                        <td class="px-3 py-2 text-xs text-center text-gray-500">{{ $standing->drawn }}</td>
                                        <td class="px-3 py-2 text-xs text-center text-gray-500">{{ $standing->lost }}</td>
                                        <td class="px-3 py-2 text-xs text-center {{ $standing->goal_difference > 0 ? 'text-green-600' : ($standing->goal_difference < 0 ? 'text-red-600' : 'text-gray-500') }}">
                                            {{ $standing->goal_difference > 0 ? '+' : '' }}{{ $standing->goal_difference }}
                                        </td>
                                        <td class="px-3 py-2 text-xs text-center font-bold text-gray-900">{{ $standing->points }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($standings->count() > 5)
                        <div class="px-4 py-2 border-t border-gray-200">
                            <a href="#" @click.prevent="activeTab = 'standings'" class="text-xs text-indigo-600 hover:text-indigo-900">View full standings →</a>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
    </div><!-- End Overview Tab -->

    <!-- Matches Tab -->
    <div x-show="activeTab === 'matches'" x-cloak>
        <div class="space-y-4">
            <!-- Upcoming Matches - Full -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="px-4 py-3 border-b border-gray-200 flex items-center justify-between">
                    <h3 class="text-sm font-medium text-gray-900">Upcoming Matches</h3>
                    <a href="{{ route('admin.tournaments.matches.create', $tournament->id) }}" class="inline-flex items-center px-3 py-1.5 bg-indigo-600 border border-transparent rounded font-semibold text-xs text-white hover:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Create Match
                    </a>
                </div>
                <div class="divide-y divide-gray-200">
                    @php
                        $upcomingMatches = $tournament->matches()
                            ->with(['homeTeam', 'awayTeam'])
                            ->where('status', 'scheduled')
                            ->where('kickoff_time', '>', now())
                            ->orderBy('kickoff_time', 'asc')
                            ->limit(10)
                            ->get();
                    @endphp
                    @forelse($upcomingMatches as $match)
                        <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-50">
                            <div class="flex-1">
                                <div class="flex items-center gap-4">
                                    <div class="text-right flex-1">
                                        <p class="font-medium text-gray-900">{{ $match->homeTeam->team_name ?? 'TBD' }}</p>
                                    </div>
                                    <div class="flex-shrink-0 w-16 text-center">
                                        <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-gray-100 text-gray-600 font-semibold text-sm">vs</span>
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-medium text-gray-900">{{ $match->awayTeam->team_name ?? 'TBD' }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="ml-6 text-right">
                                <p class="text-sm font-medium text-gray-900">{{ $match->kickoff_time ? \Carbon\Carbon::parse($match->kickoff_time)->format('M d, Y') : 'TBD' }}</p>
                                <p class="text-sm text-gray-500">{{ $match->kickoff_time ? \Carbon\Carbon::parse($match->kickoff_time)->format('H:i') : '' }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-8 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <p class="mt-2 text-sm text-gray-500">No upcoming matches scheduled</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Recent Results - Full -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="px-4 py-3 border-b border-gray-200">
                    <h3 class="text-sm font-medium text-gray-900">Recent Results</h3>
                </div>
                <div class="divide-y divide-gray-200">
                    @php
                        $recentMatches = $tournament->matches()
                            ->with(['homeTeam', 'awayTeam'])
                            ->where('status', 'completed')
                            ->orderBy('kickoff_time', 'desc')
                            ->limit(10)
                            ->get();
                    @endphp
                    @forelse($recentMatches as $match)
                        <a href="{{ route('admin.tournaments.matches.show', [$tournament->id, $match->id]) }}" class="block px-6 py-4 hover:bg-gray-50">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center gap-4">
                                        <div class="text-right flex-1">
                                            <p class="font-medium {{ $match->home_score > $match->away_score ? 'text-gray-900' : 'text-gray-500' }}">{{ $match->homeTeam->team_name ?? 'TBD' }}</p>
                                        </div>
                                        <div class="flex-shrink-0 w-20 text-center">
                                            <span class="inline-flex items-center justify-center px-3 py-1 rounded {{ $match->home_score > $match->away_score ? 'bg-green-100 text-green-800' : ($match->home_score < $match->away_score ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800') }} text-sm font-bold">
                                                {{ $match->home_score }} - {{ $match->away_score }}
                                            </span>
                                        </div>
                                        <div class="flex-1">
                                            <p class="font-medium {{ $match->away_score > $match->home_score ? 'text-gray-900' : 'text-gray-500' }}">{{ $match->awayTeam->team_name ?? 'TBD' }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="ml-6 text-right">
                                    <p class="text-sm text-gray-500">{{ $match->kickoff_time ? \Carbon\Carbon::parse($match->kickoff_time)->format('M d, Y') : '' }}</p>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="px-6 py-8 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <p class="mt-2 text-sm text-gray-500">No completed matches yet</p>
                        </div>
                    @endforelse
                </div>
                <div class="px-6 py-3 border-t border-gray-200">
                    <a href="{{ route('admin.tournaments.matches.index', $tournament->id) }}" class="text-sm text-indigo-600 hover:text-indigo-900">View all matches →</a>
                </div>
            </div>
        </div>
    </div><!-- End Matches Tab -->

    <!-- Teams Tab -->
    <div x-show="activeTab === 'teams'" x-cloak>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900">Teams</h3>
                <a href="{{ route('admin.tournaments.teams.create', $tournament->id) }}" class="inline-flex items-center px-3 py-1.5 bg-indigo-600 border border-transparent rounded font-semibold text-xs text-white hover:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add Team
                </a>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($approvedTeams as $team)
                    <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-50">
                        <div class="flex items-center gap-3">
                            <div class="flex-shrink-0 w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                <span class="text-indigo-600 font-semibold text-sm">{{ substr($team->team_name, 0, 1) }}</span>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">{{ $team->team_name }}</p>
                                <p class="text-sm text-gray-500">{{ $team->squads()->count() }} players</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.tournaments.teams.show', [$tournament->id, $team->id]) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">View</a>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <p class="mt-2 text-sm text-gray-500">No teams registered yet</p>
                    </div>
                @endforelse
                @if($pendingTeams->count() > 0)
                    <div class="px-6 py-4 bg-yellow-50">
                        <p class="text-sm font-semibold text-yellow-800 mb-3">Pending Approval ({{ $pendingTeams->count() }})</p>
                        @foreach($pendingTeams as $team)
                            <div class="flex items-center justify-between py-2 border-t border-yellow-200">
                                <span class="text-sm text-yellow-700">{{ $team->team_name }}</span>
                                <div class="flex gap-2">
                                    <form action="{{ route('admin.tournaments.teams.approve', [$tournament->id, $team->id]) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="text-xs text-green-600 hover:text-green-900 font-medium">Approve</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
            <div class="px-6 py-3 border-t border-gray-200">
                <a href="{{ route('admin.tournaments.teams.index', $tournament->id) }}" class="text-sm text-indigo-600 hover:text-indigo-900">View all teams →</a>
            </div>
        </div>
    </div><!-- End Teams Tab -->

    <!-- Standings Tab -->
    <div x-show="activeTab === 'standings'" x-cloak>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900">Standings</h3>
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
            @if($standings->count() > 0)
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
                            @foreach($standings as $standing)
                                <tr class="{{ $standing->position <= 3 ? 'bg-green-50' : '' }} hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($standing->position == 1)
                                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-yellow-400 text-white font-bold text-sm">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                            </span>
                                        @elseif($standing->position == 2)
                                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-400 text-white font-bold text-sm">{{ $standing->position }}</span>
                                        @elseif($standing->position == 3)
                                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-amber-600 text-white font-bold text-sm">{{ $standing->position }}</span>
                                        @else
                                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-200 text-gray-600 font-medium text-sm">{{ $standing->position }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="font-medium text-gray-900">{{ $standing->team->team_name ?? 'N/A' }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">{{ $standing->played }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">{{ $standing->won }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">{{ $standing->drawn }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">{{ $standing->lost }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">{{ $standing->goals_for ?? 0 }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">{{ $standing->goals_against ?? 0 }}</td>
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
                <!-- Legend -->
                <div class="px-6 py-4 border-t border-gray-200">
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
                            <span class="text-gray-600">Positive GD</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center px-2 py-0.5 rounded bg-red-100 text-red-800 text-xs font-medium">-3</span>
                            <span class="text-gray-600">Negative GD</span>
                        </div>
                    </div>
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
    </div><!-- End Standings Tab -->

    </div><!-- End space-y-4 -->
    </div><!-- End Alpine.js container -->

    <!-- Match Center Modal -->
    <div id="matchCenterModal" class="fixed inset-0 z-50 hidden" aria-labelledby="matchCenterModalLabel" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeMatchCenter()"></div>
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-4xl">
                    <!-- Modal Header -->
                    <div class="bg-purple-600 px-4 py-3 sm:px-6">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold leading-6 text-white" id="matchCenterModalLabel">
                                <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                                Match Center - {{ $tournament->name }}
                            </h3>
                            <button type="button" onclick="closeMatchCenter()" class="text-white hover:text-gray-200">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                        <!-- Reshuffle Counter -->
                        <div class="mt-2">
                            <span id="reshuffleCounter" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $reshuffleCount >= $maxReshuffles ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800' }}">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                                Reshuffles: {{ $reshuffleCount }}/{{ $maxReshuffles }}
                            </span>
                        </div>
                    </div>

                    <!-- Modal Tabs -->
                    <div class="border-b border-gray-200">
                        <nav class="-mb-px flex space-x-8 px-4" aria-label="Tabs">
                            <button onclick="switchMatchCenterTab('pools')" id="mc-tab-pools" class="mc-tab border-indigo-500 text-indigo-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                                <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                                Teams & Pools
                            </button>
                            <button onclick="switchMatchCenterTab('fixtures')" id="mc-tab-fixtures" class="mc-tab border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                                <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                Generate Fixtures
                            </button>
                            <button onclick="switchMatchCenterTab('actions')" id="mc-tab-actions" class="mc-tab border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                                <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                                Quick Actions
                            </button>
                        </nav>
                    </div>

                    <!-- Modal Body -->
                    <div class="bg-white px-4 py-5 sm:p-6 max-h-[60vh] overflow-y-auto">
                        <!-- Pools Tab Content -->
                        <div id="mc-content-pools" class="mc-content">
                            <!-- Reshuffle Controls -->
                            <div class="mb-4 flex flex-wrap gap-2 items-center">
                                <button onclick="performReshuffle()" id="btnReshuffle" class="inline-flex items-center px-3 py-1.5 bg-purple-600 border border-transparent rounded font-semibold text-xs text-white hover:bg-purple-700 transition" {{ $reshuffleCount >= $maxReshuffles ? 'disabled' : '' }}>
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                    {{ $reshuffleCount == 0 ? 'Auto-Shuffle' : 'Reshuffle Again' }}
                                </button>
                                <button onclick="performReshuffle(true)" id="btnReshufflePerformance" class="inline-flex items-center px-3 py-1.5 bg-blue-600 border border-transparent rounded font-semibold text-xs text-white hover:bg-blue-700 transition" {{ $reshuffleCount >= $maxReshuffles ? 'disabled' : '' }}>
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Shuffle by Performance
                                </button>
                                @if($reshuffleCount > 0)
                                <button onclick="resetReshuffleCount()" class="inline-flex items-center px-3 py-1.5 border border-gray-300 rounded font-semibold text-xs text-gray-700 hover:bg-gray-50 transition">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                    Reset Counter
                                </button>
                                @endif
                                <a href="{{ route('admin.tournaments.pools.reshuffle', $tournament->id) }}" class="inline-flex items-center px-3 py-1.5 border border-gray-300 rounded font-semibold text-xs text-gray-700 hover:bg-gray-50 transition ml-auto">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                    </svg>
                                    Full Page View
                                </a>
                            </div>

                            <!-- Pools Container -->
                            <div id="poolsContainer" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                @foreach($pools as $pool)
                                <div class="border rounded-lg overflow-hidden">
                                    <div class="bg-gray-800 text-white px-3 py-2 flex justify-between items-center">
                                        <span class="font-semibold text-sm">{{ $pool->name }}</span>
                                        <span class="text-xs bg-white text-gray-800 px-2 py-0.5 rounded">{{ count($teamsByPool[$pool->id] ?? []) }} teams</span>
                                    </div>
                                    <div class="p-2 bg-gray-50 min-h-[150px] pool-drop-zone" data-pool-id="{{ $pool->id }}" ondrop="handleDrop(event)" ondragover="handleDragOver(event)" ondragleave="handleDragLeave(event)">
                                        @foreach($teamsByPool[$pool->id] ?? [] as $team)
                                        <div class="bg-white border rounded p-2 mb-2 cursor-move team-card" draggable="true" data-team-id="{{ $team->id }}" ondragstart="handleDragStart(event)" ondragend="handleDragEnd(event)">
                                            <div class="flex justify-between items-center">
                                                <span class="text-sm font-medium">{{ $team->team->team_name ?? 'Team' }}</span>
                                                @if($team->seed_number)
                                                <span class="text-xs bg-blue-100 text-blue-800 px-1.5 py-0.5 rounded">#{{ $team->seed_number }}</span>
                                                @endif
                                            </div>
                                            @if($team->standing)
                                            <div class="text-xs text-gray-500 mt-1">{{ $team->standing->points }} pts</div>
                                            @endif
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endforeach
                                <!-- Unassigned Pool -->
                                @if(count($unassignedTeams) > 0)
                                <div class="border border-yellow-300 rounded-lg overflow-hidden">
                                    <div class="bg-yellow-500 text-white px-3 py-2 flex justify-between items-center">
                                        <span class="font-semibold text-sm">Unassigned</span>
                                        <span class="text-xs bg-white text-yellow-800 px-2 py-0.5 rounded">{{ count($unassignedTeams) }} teams</span>
                                    </div>
                                    <div class="p-2 bg-yellow-50 min-h-[150px] pool-drop-zone" data-pool-id="unassigned" ondrop="handleDrop(event)" ondragover="handleDragOver(event)" ondragleave="handleDragLeave(event)">
                                        @foreach($unassignedTeams as $team)
                                        <div class="bg-white border rounded p-2 mb-2 cursor-move team-card" draggable="true" data-team-id="{{ $team->id }}" ondragstart="handleDragStart(event)" ondragend="handleDragEnd(event)">
                                            <span class="text-sm font-medium">{{ $team->team->team_name ?? 'Team' }}</span>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                            </div>
                            <div class="mt-4 text-center">
                                <button onclick="savePositions()" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded font-semibold text-sm text-white hover:bg-green-700 transition">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Save Positions
                                </button>
                            </div>
                        </div>

                        <!-- Fixtures Tab Content -->
                        <div id="mc-content-fixtures" class="mc-content hidden">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="border rounded-lg p-4 hover:shadow-md transition">
                                    <h4 class="font-semibold text-gray-900 mb-2">League Schedule</h4>
                                    <p class="text-sm text-gray-500 mb-4">Generate round-robin matches where each team plays every other team.</p>
                                    <form action="{{ route('admin.tournaments.matches.generate-league', $tournament->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-full inline-flex items-center justify-center px-3 py-2 bg-blue-600 border border-transparent rounded font-semibold text-xs text-white hover:bg-blue-700 transition">
                                            Generate League
                                        </button>
                                    </form>
                                </div>
                                <div class="border rounded-lg p-4 hover:shadow-md transition">
                                    <h4 class="font-semibold text-gray-900 mb-2">Knockout Bracket</h4>
                                    <p class="text-sm text-gray-500 mb-4">Generate single-elimination tournament bracket.</p>
                                    <form action="{{ route('admin.tournaments.matches.generate-knockout', $tournament->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-full inline-flex items-center justify-center px-3 py-2 bg-orange-600 border border-transparent rounded font-semibold text-xs text-white hover:bg-orange-700 transition">
                                            Generate Knockout
                                        </button>
                                    </form>
                                </div>
                                <div class="border rounded-lg p-4 hover:shadow-md transition">
                                    <h4 class="font-semibold text-gray-900 mb-2">Group Stage</h4>
                                    <p class="text-sm text-gray-500 mb-4">Generate group stage matches based on current pools.</p>
                                    <form action="{{ route('admin.tournaments.matches.generate-group-stage', $tournament->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-full inline-flex items-center justify-center px-3 py-2 bg-green-600 border border-transparent rounded font-semibold text-xs text-white hover:bg-green-700 transition">
                                            Generate Groups
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <div class="mt-6 border-t pt-4">
                                <h4 class="font-semibold text-gray-900 mb-3">Manage Fixtures</h4>
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.tournaments.matches.create', $tournament->id) }}" class="inline-flex items-center px-3 py-1.5 bg-indigo-600 border border-transparent rounded font-semibold text-xs text-white hover:bg-indigo-700 transition">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                        </svg>
                                        Create Match
                                    </a>
                                    <a href="{{ route('admin.tournaments.matches.index', $tournament->id) }}" class="inline-flex items-center px-3 py-1.5 border border-gray-300 rounded font-semibold text-xs text-gray-700 hover:bg-gray-50 transition">
                                        View All Matches
                                    </a>
                                    <form action="{{ route('admin.tournaments.matches.delete-fixtures', $tournament->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete all fixtures?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-600 border border-transparent rounded font-semibold text-xs text-white hover:bg-red-700 transition">
                                            Delete All Fixtures
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Actions Tab Content -->
                        <div id="mc-content-actions" class="mc-content hidden">
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                <a href="{{ route('admin.tournaments.matches.create', $tournament->id) }}" class="border rounded-lg p-4 hover:shadow-md transition text-center">
                                    <svg class="w-8 h-8 mx-auto text-indigo-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    <span class="text-sm font-medium text-gray-900">Create Match</span>
                                </a>
                                <a href="{{ route('admin.tournaments.matches.index', $tournament->id) }}" class="border rounded-lg p-4 hover:shadow-md transition text-center">
                                    <svg class="w-8 h-8 mx-auto text-blue-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <span class="text-sm font-medium text-gray-900">View Matches</span>
                                </a>
                                <a href="{{ route('admin.tournaments.schedule.index', $tournament->id) }}" class="border rounded-lg p-4 hover:shadow-md transition text-center">
                                    <svg class="w-8 h-8 mx-auto text-green-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span class="text-sm font-medium text-gray-900">Schedule</span>
                                </a>
                                <a href="{{ route('admin.tournaments.pools.index', $tournament->id) }}" class="border rounded-lg p-4 hover:shadow-md transition text-center">
                                    <svg class="w-8 h-8 mx-auto text-purple-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                    </svg>
                                    <span class="text-sm font-medium text-gray-900">Manage Pools</span>
                                </a>
                            </div>
                            <div class="mt-6 border-t pt-4">
                                <h4 class="font-semibold text-gray-900 mb-3">Tournament Actions</h4>
                                <div class="flex flex-wrap gap-2">
                                    <form action="{{ route('admin.tournaments.recalculate-standings', $tournament->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center px-3 py-1.5 border border-gray-300 rounded font-semibold text-xs text-gray-700 hover:bg-gray-50 transition">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                            </svg>
                                            Recalculate Standings
                                        </button>
                                    </form>
                                    <a href="{{ route('admin.tournaments.pools.reshuffle', $tournament->id) }}" class="inline-flex items-center px-3 py-1.5 border border-gray-300 rounded font-semibold text-xs text-gray-700 hover:bg-gray-50 transition">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                        </svg>
                                        Full Reshuffle Page
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 flex justify-end">
                        <button type="button" onclick="closeMatchCenter()" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded font-semibold text-xs text-gray-700 hover:bg-gray-50 transition">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let draggedTeam = null;
        let reshuffleCount = {{ $reshuffleCount }};
        const maxReshuffles = {{ $maxReshuffles }};
        const tournamentId = {{ $tournament->id }};

        function getRoutePrefix() {
            return window.location.pathname.includes('super-admin') ? 'super-admin' : 'admin';
        }

        function openMatchCenter() {
            document.getElementById('matchCenterModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeMatchCenter() {
            document.getElementById('matchCenterModal').classList.add('hidden');
            document.body.style.overflow = '';
        }

        function switchMatchCenterTab(tab) {
            document.querySelectorAll('.mc-tab').forEach(t => {
                t.classList.remove('border-indigo-500', 'text-indigo-600');
                t.classList.add('border-transparent', 'text-gray-500');
            });
            document.querySelectorAll('.mc-content').forEach(c => c.classList.add('hidden'));

            document.getElementById('mc-tab-' + tab).classList.remove('border-transparent', 'text-gray-500');
            document.getElementById('mc-tab-' + tab).classList.add('border-indigo-500', 'text-indigo-600');
            document.getElementById('mc-content-' + tab).classList.remove('hidden');
        }

        function handleDragStart(e) {
            draggedTeam = e.target;
            e.target.classList.add('opacity-50');
            e.dataTransfer.effectAllowed = 'move';
            e.dataTransfer.setData('text/plain', e.target.dataset.teamId);
        }

        function handleDragEnd(e) {
            e.target.classList.remove('opacity-50');
            document.querySelectorAll('.pool-drop-zone').forEach(z => z.classList.remove('bg-blue-100', 'border-blue-400'));
        }

        function handleDragOver(e) {
            e.preventDefault();
            e.currentTarget.classList.add('bg-blue-100', 'border-blue-400');
        }

        function handleDragLeave(e) {
            e.currentTarget.classList.remove('bg-blue-100', 'border-blue-400');
        }

        function handleDrop(e) {
            e.preventDefault();
            e.currentTarget.classList.remove('bg-blue-100', 'border-blue-400');
            if (draggedTeam) {
                e.currentTarget.appendChild(draggedTeam);
                draggedTeam = null;
            }
        }

        async function savePositions() {
            const teams = [];
            document.querySelectorAll('.pool-drop-zone').forEach(zone => {
                const poolId = zone.dataset.poolId;
                zone.querySelectorAll('.team-card').forEach((card, index) => {
                    teams.push({
                        id: parseInt(card.dataset.teamId),
                        pool_id: poolId === 'unassigned' ? null : parseInt(poolId),
                        position: index + 1
                    });
                });
            });

            try {
                const prefix = getRoutePrefix();
                const response = fetch(`/{{ Request::segment(1) }}/${prefix}/tournaments/${tournamentId}/pools/update-positions`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ teams })
                }).then(r => r.json()).then(data => {
                    if (data.success) {
                        alert('Positions saved successfully!');
                    } else {
                        alert('Error: ' + (data.error || 'Unknown error'));
                    }
                });
            } catch (error) {
                alert('Error saving positions');
            }
        }

        async function performReshuffle(usePerformance = false) {
            if (reshuffleCount >= maxReshuffles) {
                alert('Maximum reshuffle limit reached!');
                return;
            }

            try {
                const prefix = getRoutePrefix();
                const url = `/{{ Request::segment(1) }}/${prefix}/tournaments/${tournamentId}/pools/reshuffle`;
                const formData = new FormData();
                if (usePerformance) formData.append('use_performance', '1');

                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const data = await response.json();
                if (data.success) {
                    reshuffleCount = data.reshuffle_count;
                    updateReshuffleCounter();
                    location.reload();
                } else {
                    alert('Error: ' + (data.error || 'Unknown error'));
                }
            } catch (error) {
                alert('Error performing reshuffle');
            }
        }

        async function resetReshuffleCount() {
            try {
                const prefix = getRoutePrefix();
                const response = await fetch(`/{{ Request::segment(1) }}/${prefix}/tournaments/${tournamentId}/pools/reshuffle/reset`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();
                if (data.success) {
                    reshuffleCount = 0;
                    updateReshuffleCounter();
                    alert('Counter reset!');
                }
            } catch (error) {
                alert('Error resetting counter');
            }
        }

        function updateReshuffleCounter() {
            const counter = document.getElementById('reshuffleCounter');
            counter.textContent = `Reshuffles: ${reshuffleCount}/${maxReshuffles}`;
            if (reshuffleCount >= maxReshuffles) {
                counter.classList.remove('bg-yellow-100', 'text-yellow-800');
                counter.classList.add('bg-red-100', 'text-red-800');
                document.getElementById('btnReshuffle').disabled = true;
                document.getElementById('btnReshufflePerformance').disabled = true;
            }
        }

        // Close modal on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeMatchCenter();
        });
    </script>
@endsection
