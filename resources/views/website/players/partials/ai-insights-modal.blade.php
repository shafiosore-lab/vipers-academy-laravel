{{-- AI Insights Modal Component --}}
{{-- Modal-based AI Insights interface for expandable AI-powered analytics platform --}}
{{-- Version 1.0 - Foundation for comprehensive AI suite --}}

@props([
    'player' => null,
    'insights' => [],
    'dataFreshness' => [],
    'hasDynamicInsights' => false,
])

@if(!$player)
    <div class="alert alert-danger">Error: Player data not available</div>
@else
    {{-- Data Freshness Indicator --}}
    @if($hasDynamicInsights && !empty($dataFreshness))
        <div class="ai-freshness-indicator mb-4">
            @php
                $freshnessStatus = $dataFreshness['freshness_score'] ?? 0;
                $statusColor = $freshnessStatus >= 80 ? 'success' : ($freshnessStatus >= 50 ? 'warning' : 'danger');
                $statusLabel = $dataFreshness['needs_refresh'] ? 'Needs Update' : 'Up to Date';
            @endphp
            <div class="d-flex align-items-center justify-content-between p-3 bg-light rounded">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-sync-alt text-{{ $statusColor }}"></i>
                    <span class="text-muted small">Data Freshness:</span>
                    <span class="badge bg-{{ $statusColor }}">{{ $statusLabel }}</span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="small text-muted">Score: {{ round($freshnessStatus) }}%</span>
                    @if(($dataFreshness['days_since_update'] ?? null) !== null)
                        <span class="small text-muted">Last update: {{ $dataFreshness['days_since_update'] }} days ago</span>
                    @endif
                </div>
            </div>
        </div>
    @endif

    {{-- AI Insights Grid --}}
    <div class="ai-insights-grid">
        {{-- Insight Cards --}}
        @forelse($insights as $type => $insight)
            <div class="ai-insight-card" data-insight-type="{{ $type }}">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        @switch($type)
                            @case('strength')
                                <i class="fas fa-star text-warning"></i>
                                <h5 class="mb-0">Strengths</h5>
                                @break
                            @case('development')
                                <i class="fas fa-chart-line text-primary"></i>
                                <h5 class="mb-0">Areas for Development</h5>
                                @break
                            @case('trend')
                                <i class="fas fa-trophy text-success"></i>
                                <h5 class="mb-0">Performance Trend</h5>
                                @break
                            @case('style')
                                <i class="fas fa-tactics text-info"></i>
                                <h5 class="mb-0">Playing Style</h5>
                                @break
                            @case('prediction')
                                <i class="fas fa-crystal-ball text-purple"></i>
                                <h5 class="mb-0">Predictions</h5>
                                @break
                            @default
                                <i class="fas fa-brain text-secondary"></i>
                                <h5 class="mb-0">{{ ucfirst(str_replace('_', ' ', $type)) }}</h5>
                        @endswitch
                    </div>

                    {{-- Confidence Badge --}}
                    @if(isset($insight['confidence_level']))
                        @php
                            $confidenceColors = [
                                'very_high' => 'success',
                                'high' => 'primary',
                                'medium' => 'warning',
                                'low' => 'secondary',
                            ];
                            $confidenceColor = $confidenceColors[$insight['confidence_level']] ?? 'secondary';
                        @endphp
                        <span class="badge bg-{{ $confidenceColor }}" title="Confidence: {{ $insight['confidence_score'] ?? 'N/A' }}%">
                            {{ ucfirst(str_replace('_', ' ', $insight['confidence_level'])) }} Confidence
                        </span>
                    @endif
                </div>

                <div class="card-body">
                    {{-- Main Insight Content --}}
                    <p class="insight-content">{{ $insight['insight_content'] ?? 'No insight available' }}</p>

                    {{-- Insight Data (Charts/Visualizations) --}}
                    @if(isset($insight['insight_data']) && is_array($insight['insight_data']))
                        <div class="insight-data mt-3">
                            @foreach($insight['insight_data'] as $key => $value)
                                @if(is_array($value))
                                    <div class="mb-2">
                                        <small class="text-muted text-uppercase">{{ str_replace('_', ' ', $key) }}:</small>
                                        @foreach($value as $k => $v)
                                            <div class="d-flex justify-content-between">
                                                <span class="small">{{ str_replace('_', ' ', $k) }}:</span>
                                                <span class="small fw-bold">{{ is_numeric($v) ? round($v, 2) : $v }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                @elseif(!in_array($key, ['strengths', 'areas', 'metrics', 'trend']))
                                    <div class="d-flex justify-content-between">
                                        <span class="small text-muted text-uppercase">{{ str_replace('_', ' ', $key) }}:</span>
                                        <span class="small fw-bold">{{ is_numeric($value) ? round($value, 2) : $value }}</span>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="card-footer d-flex justify-content-between align-items-center">
                    {{-- Generation Info --}}
                    @if(isset($insight['generated_at']))
                        <small class="text-muted">
                            Generated: {{ \Carbon\Carbon::parse($insight['generated_at'])->diffForHumans() }}
                        </small>
                    @else
                        <small class="text-muted">Auto-generated insight</small>
                    @endif

                    {{-- View Details Button (Triggers Modal) --}}
                    <button type="button"
                            class="btn btn-sm btn-outline-primary"
                            data-bs-toggle="modal"
                            data-bs-target="#insightModal{{ ucfirst($type) }}"
                            title="View detailed analysis">
                        <i class="fas fa-expand"></i> Details
                    </button>
                </div>
            </div>
        @empty
            {{-- Fallback Content (when no dynamic insights available) --}}
            @include('website.players.partials.ai-insights-fallback', ['player' => $player])
        @endforelse
    </div>

    {{-- Modal Templates for Each Insight Type --}}
    @foreach($insights as $type => $insight)
        <div class="modal fade" id="insightModal{{ ucfirst($type) }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fas fa-brain me-2"></i>
                            {{ ucfirst(str_replace('_', ' ', $type)) }} - Detailed Analysis
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        {{-- Detailed Content --}}
                        <div class="mb-4">
                            <h6>Analysis</h6>
                            <p>{{ $insight['insight_content'] ?? 'No analysis available' }}</p>
                        </div>

                        {{-- Data Points Used --}}
                        @if(isset($insight['data_points_used']))
                            <div class="alert alert-info">
                                <i class="fas fa-database me-2"></i>
                                Analysis based on {{ $insight['data_points_used'] }} data points
                            </div>
                        @endif

                        {{-- Confidence Score Visualization --}}
                        @if(isset($insight['confidence_score']))
                            <div class="mb-4">
                                <h6>Confidence Score</h6>
                                <div class="progress" style="height: 25px;">
                                    <div class="progress-bar bg-{{ $confidenceColors[$insight['confidence_level']] ?? 'secondary' }}"
                                         role="progressbar"
                                         style="width: {{ $insight['confidence_score'] }}%"
                                         aria-valuenow="{{ $insight['confidence_score'] }}"
                                         aria-valuemin="0"
                                         aria-valuemax="100">
                                        {{ round($insight['confidence_score']) }}%
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Additional Metrics --}}
                        @if(isset($insight['insight_data']['metrics']))
                            <div class="row">
                                @foreach($insight['insight_data']['metrics'] as $metric => $value)
                                    <div class="col-md-4 mb-2">
                                        <div class="card bg-light">
                                            <div class="card-body text-center">
                                                <h6 class="card-title text-muted small text-uppercase">{{ str_replace('_', ' ', $metric) }}</h6>
                                                <p class="card-text h4 mb-0">{{ is_numeric($value) ? round($value, 2) : $value }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        {{-- Model Information --}}
                        @if(isset($insight['model_name']) || isset($insight['version']))
                            <div class="mt-4 pt-3 border-top">
                                <small class="text-muted">
                                    <i class="fas fa-robot me-1"></i>
                                    Generated by {{ $insight['model_name'] ?? 'AI System' }} v{{ $insight['version'] ?? '1.0' }}
                                </small>
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">
                            <i class="fas fa-share-alt me-1"></i> Share
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    {{-- No Insights State --}}
    @if(!$hasDynamicInsights)
        <div class="text-center py-5">
            <i class="fas fa-brain fa-3x text-muted mb-3"></i>
            <h4>AI Insights Coming Soon</h4>
            <p class="text-muted">We're analyzing player performance data to generate personalized insights.</p>
            <p class="small text-muted">Check back after more game data is collected.</p>
        </div>
    @endif

    {{-- Styles for AI Insights --}}
    <style>
        .ai-insights-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 1.5rem;
        }

        .ai-insight-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            transition: transform 0.2s, box-shadow 0.2s;
            overflow: hidden;
        }

        .ai-insight-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(0,0,0,0.12);
        }

        .ai-insight-card .card-header {
            padding: 1rem 1.25rem;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-bottom: 1px solid #dee2e6;
        }

        .ai-insight-card .card-body {
            padding: 1.25rem;
        }

        .ai-insight-card .card-footer {
            padding: 0.75rem 1.25rem;
            background: #f8f9fa;
            border-top: 1px solid #dee2e6;
        }

        .insight-content {
            font-size: 0.95rem;
            line-height: 1.6;
            color: #333;
        }

        .text-purple {
            color: #6f42c1 !important;
        }

        .insight-data {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 0.75rem;
        }
    </style>
@endif
