@extends('layouts.academy')

@section('title', $player->name . ' - Vipers Academy Player')

@section('content')
@include('website.players.partials.player-styles')

<div class="player-detail-container">
    <div class="player-header">
        <a href="{{ route('players.index') }}" class="back-link">
            <i class="fas fa-arrow-left"></i> Back to Players
        </a>
    </div>

    @include('website.players.partials.player-card')

    <!-- Tabbed Content -->
    <div class="player-tabs-container">
        <div class="tabs-navigation">
            <button class="tab-button active" data-tab="ai-insights">
                <i class="fas fa-brain"></i> <span>AI Insights</span>
            </button>
            <button class="tab-button" data-tab="biography">
                <i class="fas fa-user"></i> <span>Biography</span>
            </button>
            <button class="tab-button" data-tab="statistics">
                <i class="fas fa-chart-bar"></i> <span>Statistics</span>
            </button>
        </div>

        <div class="tabs-content">
            <!-- AI Insights Tab -->
            <div class="tab-pane active" id="ai-insights">
                <div class="content-card">
                    @include('website.players.partials.ai-insights')
                </div>
            </div>

            <!-- Biography Tab -->
            <div class="tab-pane" id="biography">
                <div class="content-card">
                    @include('website.players.partials.biography')
                </div>
            </div>

            <!-- Statistics Tab -->
            <div class="tab-pane" id="statistics">
                <div class="content-card">
                    @include('website.players.partials.statistics')
                </div>
            </div>
        </div>
    </div>
</div>

@include('website.players.partials.player-scripts')
@endsection
