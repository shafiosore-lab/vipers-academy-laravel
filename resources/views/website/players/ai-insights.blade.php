@extends('layouts.academy')

@section('title', $player->name . ' - AI Insights - Vipers Academy')

@section('content')
@include('website.players.partials.shared-styles')

<div class="vipers-player-container">
    <div class="player-profile">
        @include('website.players.partials.shared-header', ['player' => $player])

        <!-- Navigation -->
        <div class="section-nav">
            <a href="{{ route('players.biography', $player->id) }}" class="nav-link">Biography</a>
            <a href="{{ route('players.statistics', $player->id) }}" class="nav-link">Statistics</a>
            <a href="{{ route('players.ai-insights', $player->id) }}" class="nav-link active">AI Insights</a>
        </div>

        <!-- Content Section -->
        <div class="content-section">
            {{-- AI Insights Modal Component --}}
            @include('website.players.partials.ai-insights-modal', [
                'player' => $player,
                'insights' => $groupedInsights ?? [],
                'dataFreshness' => $dataFreshness ?? [],
                'hasDynamicInsights' => $hasDynamicInsights ?? false,
            ])
        </div>
    </div>
</div>

{{-- Bootstrap Modal CSS if not already included --}}
@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
@endpush

{{-- Bootstrap Modal JS if not already included --}}
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endpush
@endsection
