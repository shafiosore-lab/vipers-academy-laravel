@extends('layouts.admin')

@section('title', 'Create Tournament')

@section('header')
<div class="flex items-center gap-4">
    <a href="{{ route('admin.tournaments.index') }}" class="inline-flex items-center justify-center w-10 h-10 rounded-lg border border-gray-300 bg-white text-gray-500 hover:bg-gray-50 hover:text-gray-700 transition">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
    </a>
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Create Tournament</h1>
        <p class="mt-1 text-sm text-gray-500">Set up a new tournament competition</p>
    </div>
</div>
@endsection

@section('content')
<form method="POST" action="{{ route('admin.tournaments.store') }}">
    @csrf
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Information -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Basic Information</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Tournament Name *</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required placeholder="e.g., Annual Youth Championship 2026" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('name') border-red-300 @enderror">
                        @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="organization_id" class="block text-sm font-medium text-gray-700 mb-1">Organization *</label>
                        <select name="organization_id" id="organization_id" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="">Select Organization</option>
                            @foreach($organizations as $org)
                                <option value="{{ $org->id }}" {{ old('organization_id') == $org->id ? 'selected' : '' }}>
                                    {{ $org->name }} ({{ ucfirst($org->type) }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="season" class="block text-sm font-medium text-gray-700 mb-1">Season</label>
                            <input type="text" name="season" id="season" value="{{ old('season') }}" placeholder="e.g., 2025-2026" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                        <div>
                            <label for="venue" class="block text-sm font-medium text-gray-700 mb-1">Venue</label>
                            <input type="text" name="venue" id="venue" value="{{ old('venue') }}" placeholder="Primary venue" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                    </div>
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="description" id="description" rows="3" placeholder="Brief description of the tournament..." class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Competition Format -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Competition Format</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label for="competition_format" class="block text-sm font-medium text-gray-700 mb-1">Format *</label>
                        <select name="competition_format" id="competition_format" onchange="updateFormatInfo()" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="">Select Format</option>
                            <option value="league" {{ old('competition_format') == 'league' ? 'selected' : '' }}>League - All teams play each other</option>
                            <option value="round_robin" {{ old('competition_format') == 'round_robin' ? 'selected' : '' }}>Round Robin - Home and away</option>
                            <option value="league_cup" {{ old('competition_format') == 'league_cup' ? 'selected' : '' }}>League + Cup - Group + Knockout</option>
                            <option value="knockout" {{ old('competition_format') == 'knockout' ? 'selected' : '' }}>Knockout - Single elimination</option>
                            <option value="knockout_plus" {{ old('competition_format') == 'knockout_plus' ? 'selected' : '' }}>Knockout with Third Place</option>
                            <option value="groups_knockout" {{ old('competition_format') == 'groups_knockout' ? 'selected' : '' }}>Groups + Knockout</option>
                            <option value="double_elimination" {{ old('competition_format') == 'double_elimination' ? 'selected' : '' }}>Double Elimination</option>
                        </select>
                    </div>
                    <div id="formatDescription" class="hidden bg-blue-50 border border-blue-200 rounded-md p-4">
                        <p class="text-sm text-blue-800" id="formatInfo"></p>
                    </div>
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label for="max_teams" class="block text-sm font-medium text-gray-700 mb-1">Max Teams</label>
                            <input type="number" name="max_teams" id="max_teams" value="{{ old('max_teams', 16) }}" min="2" oninput="updateEstimate()" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                        <div>
                            <label for="squad_limit" class="block text-sm font-medium text-gray-700 mb-1">Squad Limit</label>
                            <input type="number" name="squad_limit" id="squad_limit" value="{{ old('squad_limit', 25) }}" min="5" max="50" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                        <div>
                            <label for="estimated_matches" class="block text-sm font-medium text-gray-700 mb-1">Est. Matches</label>
                            <input type="text" id="estimated_matches" readonly class="w-full rounded-md border-gray-300 bg-gray-50 text-gray-500 sm:text-sm" placeholder="Auto-calculated">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Schedule -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Schedule</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                            <input type="date" name="start_date" id="start_date" value="{{ old('start_date') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                            <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                        <div>
                            <label for="registration_deadline" class="block text-sm font-medium text-gray-700 mb-1">Registration Deadline</label>
                            <input type="datetime-local" name="registration_deadline" id="registration_deadline" value="{{ old('registration_deadline') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Status -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Status</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Initial Status</label>
                        <select name="status" id="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="draft" {{ old('status', 'draft') == 'draft' ? 'selected' : '' }}>Draft - Not visible to teams</option>
                            <option value="open" {{ old('status') == 'open' ? 'selected' : '' }}>Open - Accepting registrations</option>
                        </select>
                    </div>
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input type="checkbox" name="is_public" id="is_public" value="1" {{ old('is_public', true) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="is_public" class="font-medium text-gray-700">Make public</label>
                            <p class="text-gray-500">Visible on public tournament listings</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Rules -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Rules</h3>
                </div>
                <div class="p-6">
                    <div>
                        <label for="rules" class="block text-sm font-medium text-gray-700 mb-1">Tournament Rules</label>
                        <textarea name="rules" id="rules" rows="4" placeholder="Enter tournament rules and regulations..." class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('rules') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 flex flex-col gap-3">
                    <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-sm text-white hover:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Create Tournament
                    </button>
                    <a href="{{ route('admin.tournaments.index') }}" class="w-full inline-flex justify-center items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-sm text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">
                        Cancel
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
const formatData = {
    league: { name: 'League', desc: 'All teams play each other once. Best for 4-20 teams.', calc: n => n < 2 ? 0 : n * (n - 1) / 2 },
    round_robin: { name: 'Round Robin', desc: 'Each team plays every other team home and away.', calc: n => n < 2 ? 0 : n * (n - 1) },
    league_cup: { name: 'League + Cup', desc: 'League phase followed by knockout finals.', calc: n => n < 2 ? 0 : (n * (n - 1) / 2) + (n - 1) },
    knockout: { name: 'Knockout', desc: 'Single elimination. Losers are immediately out.', calc: n => n < 2 ? 0 : n - 1 },
    knockout_plus: { name: 'Knockout + 3rd Place', desc: 'Knockout with a third-place playoff match.', calc: n => n < 2 ? 0 : n },
    groups_knockout: { name: 'Groups + Knockout', desc: 'Teams in groups, top teams advance to knockout.', calc: n => Math.ceil(n / 4) * 6 + (Math.pow(2, Math.ceil(Math.log2(n))) - 1) },
    double_elimination: { name: 'Double Elimination', desc: 'Teams eliminated after two losses.', calc: n => n < 2 ? 0 : 2 * n - 2 }
};

function updateFormatInfo() {
    const format = document.getElementById('competition_format').value;
    const descDiv = document.getElementById('formatDescription');
    const info = document.getElementById('formatInfo');
    if (format && formatData[format]) {
        info.textContent = formatData[format].desc;
        descDiv.classList.remove('hidden');
    } else {
        descDiv.classList.add('hidden');
    }
    updateEstimate();
}

function updateEstimate() {
    const format = document.getElementById('competition_format').value;
    const teams = parseInt(document.getElementById('max_teams')?.value) || 0;
    const estInput = document.getElementById('estimated_matches');
    if (format && formatData[format] && teams > 1) {
        estInput.value = formatData[format].calc(teams);
    } else {
        estInput.value = '';
    }
}

document.addEventListener('DOMContentLoaded', updateFormatInfo);
</script>
@endsection
