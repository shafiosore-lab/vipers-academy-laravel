@extends('layouts.academy')

@section('title', $player->name . ' - Biography - Vipers Academy')

@section('content')
@include('website.players.partials.shared-styles')

<div class="vipers-player-container">
    <div class="player-profile">
        @include('website.players.partials.shared-header', ['player' => $player])

        <!-- Navigation -->
        <div class="section-nav">
            <a href="{{ route('players.biography', $player->id) }}" class="nav-link active">Biography</a>
            <a href="{{ route('players.statistics', $player->id) }}" class="nav-link">Statistics</a>
            <a href="{{ route('players.ai-insights', $player->id) }}" class="nav-link">AI Insights</a>
        </div>

        <!-- Content Section -->
        <div class="content-section">
            @include('website.players.partials.biography', ['player' => $player])
        </div>
    </div>
</div>
@endsection
