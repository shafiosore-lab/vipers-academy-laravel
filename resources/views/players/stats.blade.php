@extends('layouts.academy')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-center mb-8">{{ $player->name }} Statistics</h1>

    <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-md p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Player Info -->
            <div>
                <h2 class="text-xl font-semibold mb-4">Player Information</h2>
                <div class="space-y-2">
                    <p><strong>Name:</strong> {{ $player->name }}</p>
                    <p><strong>Position:</strong> {{ ucfirst($player->position) }}</p>
                    <p><strong>Age:</strong> {{ $player->age }}</p>
                    <p><strong>Performance Rating:</strong> {{ $player->performance_rating }}/10</p>
                </div>
            </div>

            <!-- Player Image -->
            <div class="text-center">
                <img src="{{ $player->image ? asset('assets/img/players/' . $player->image) : asset('assets/img/placeholder.jpg') }}"
                     alt="{{ $player->name }} Headshot"
                     class="w-48 h-48 object-cover rounded-lg mx-auto">
            </div>
        </div>

        <!-- Statistics -->
        <div class="mt-8">
            <h2 class="text-xl font-semibold mb-4">Statistics</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="text-center p-4 bg-gray-50 rounded">
                    <div class="text-2xl font-bold text-blue-600">{{ $player->goals ?? 0 }}</div>
                    <div class="text-sm text-gray-600">Goals</div>
                </div>
                <div class="text-center p-4 bg-gray-50 rounded">
                    <div class="text-2xl font-bold text-green-600">{{ $player->assists ?? 0 }}</div>
                    <div class="text-sm text-gray-600">Assists</div>
                </div>
                <div class="text-center p-4 bg-gray-50 rounded">
                    <div class="text-2xl font-bold text-purple-600">{{ $player->matches_played ?? 0 }}</div>
                    <div class="text-sm text-gray-600">Matches</div>
                </div>
                <div class="text-center p-4 bg-gray-50 rounded">
                    <div class="text-2xl font-bold text-red-600">{{ $player->yellow_cards ?? 0 }}</div>
                    <div class="text-sm text-gray-600">Yellow Cards</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
