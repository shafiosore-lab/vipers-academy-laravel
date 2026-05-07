@extends('layouts.admin')

@section('title', 'Tournament Summary - ' . $tournament->name . ' - Admin')

@section('content')
<div class="container-fluid px-2">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h5 mb-0">Tournament Summary - {{ $tournament->name }}</h1>
            <small class="text-muted">
                <a href="{{ route('admin.tournaments.index') }}">Tournaments</a>
                <span class="mx-1">/</span>
                <a href="{{ route('admin.tournaments.show', $tournament->id) }}">{{ $tournament->name }}</a>
                <span class="mx-1">/</span>
                <a href="{{ route('admin.tournaments.statistics.index', $tournament) }}">Statistics</a>
                <span class="mx-1">/</span>
                Summary
            </small>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.tournaments.statistics.index', $tournament) }}" class="btn btn-sm btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Statistics
            </a>
            <button id="refresh-summary-btn" class="btn btn-sm btn-outline-primary" title="Refresh Summary">
                <i class="fas fa-sync-alt"></i> Refresh
            </button>
            <button id="export-summary-btn" class="btn btn-sm btn-outline-success" title="Export Summary">
                <i class="fas fa-download"></i> Export
            </button>
        </div>
    </div>

    <!-- Last Updated Indicator -->
    <div class="alert alert-info alert-dismissible fade show mb-3" role="alert">
        <i class="fas fa-clock me-2"></i>
        <span id="last-updated">Tournament summary last updated: {{ now()->format('M d, Y H:i') }}</span>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>

    <!-- Tournament Overview -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title text-muted">Tournament Status</h6>
                            <h4 class="mb-0">{{ ucfirst($tournament->status) }}</h4>
                        </div>
                        <div class="text-{{ $tournament->status === 'completed' ? 'success' : ($tournament->status === 'in_progress' ? 'warning' : 'info') }}">
                            <i class="fas fa-trophy fa-2x"></i>
                        </div>
                    </div>
                    <small class="text-muted">
                        {{ $tournament->start_date->format('M d, Y') }} - {{ $tournament->end_date->format('M d, Y') }}
                    </small>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title text-muted">Format</h6>
                            <h4 class="mb-0">{{ $formatInfo['name'] }}</h4>
                        </div>
                        <div class="text-primary">
                            <i class="fas fa-layer-group fa-2x"></i>
                        </div>
                    </div>
                    <small class="text-muted">{{ $formatInfo['description'] }}</small>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title text-muted">Total Teams</h6>
                            <h4 class="mb-0">{{ $summary['total_teams'] }}</h4>
                        </div>
                        <div class="text-info">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                    </div>
                    <small class="text-muted">{{ $summary['registered_teams'] }} registered</small>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title text-muted">Total Matches</h6>
                            <h4 class="mb-0">{{ $summary['total_matches'] }}</h4>
                        </div>
                        <div class="text-warning">
                            <i class="fas fa-futbol fa-2x"></i>
                        </div>
                    </div>
                    <small class="text-muted">{{ $summary['completed_matches'] }} completed</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Key Statistics -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title text-muted">Total Goals</h6>
                            <h4 class="mb-0">{{ $summary['total_goals'] }}</h4>
                        </div>
                        <div class="text-success">
                            <i class="fas fa-football-ball fa-2x"></i>
                        </div>
                    </div>
                    <small class="text-muted">Avg: {{ $summary['avg_goals_per_match'] }} per match</small>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title text-muted">Total Cards</h6>
                            <h4 class="mb-0">{{ $summary['total_cards'] }}</h4>
                        </div>
                        <div class="text-danger">
                            <i class="fas fa-gavel fa-2x"></i>
                        </div>
                    </div>
                    <small class="text-muted">{{ $summary['yellow_cards'] }} yellow, {{ $summary['red_cards'] }} red</small>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title text-muted">Attendance</h6>
                            <h4 class="mb-0">{{ number_format($summary['total_attendance']) }}</h4>
                        </div>
                        <div class="text-info">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                    </div>
                    <small class="text-muted">Avg: {{ number_format($summary['avg_attendance']) }} per match</small>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title text-muted">Revenue</h6>
                            <h4 class="mb-0">{{ $summary['total_revenue'] }}</h4>
                        </div>
                        <div class="text-success">
                            <i class="fas fa-dollar-sign fa-2x"></i>
                        </div>
                    </div>
                    <small class="text-muted">Ticket sales & sponsorships</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <ul class="nav nav-tabs mb-4" id="summaryTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview" type="button" role="tab">
                <i class="fas fa-tachometer-alt me-2"></i>Overview
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="performance-tab" data-bs-toggle="tab" data-bs-target="#performance" type="button" role="tab">
                <i class="fas fa-chart-bar me-2"></i>Performance
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="financials-tab" data-bs-toggle="tab" data-bs-target="#financials" type="button" role="tab">
                <i class="fas fa-chart-pie me-2"></i>Financials
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="reports-tab" data-bs-toggle="tab" data-bs-target="#reports" type="button" role="tab">
                <i class="fas fa-file-alt me-2"></i>Reports
            </button>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content" id="summaryTabContent">
        <!-- Overview Tab -->
        <div class="tab-pane fade show active" id="overview" role="tabpanel">
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white">
                            <h6 class="mb-0">Tournament Timeline</h6>
                        </div>
                        <div class="card-body">
                            <div class="timeline">
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-success"></div>
                                    <div class="timeline-content">
                                        <h6>Registration Open</h6>
                                        <small class="text-muted">{{ $tournament->registration_start_date->format('M d, Y') }}</small>
                                    </div>
                                </div>
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-info"></div>
                                    <div class="timeline-content">
                                        <h6>Registration Closed</h6>
                                        <small class="text-muted">{{ $tournament->registration_end_date->format('M d, Y') }}</small>
                                    </div>
                                </div>
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-primary"></div>
                                    <div class="timeline-content">
                                        <h6>Tournament Started</h6>
                                        <small class="text-muted">{{ $tournament->start_date->format('M d, Y') }}</small>
                                    </div>
                                </div>
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-warning"></div>
                                    <div class="timeline-content">
                                        <h6>Tournament Ended</h6>
                                        <small class="text-muted">{{ $tournament->end_date->format('M d, Y') }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white">
                            <h6 class="mb-0">Key Metrics</h6>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-6">
                                    <div class="display-6 text-success">{{ $summary['total_goals'] }}</div>
                                    <div class="text-muted">Total Goals</div>
                                </div>
                                <div class="col-6">
                                    <div class="display-6 text-warning">{{ $summary['total_matches'] }}</div>
                                    <div class="text-muted">Total Matches</div>
                                </div>
                                <div class="col-6 mt-3">
                                    <div class="display-6 text-info">{{ $summary['total_teams'] }}</div>
                                    <div class="text-muted">Participating Teams</div>
                                </div>
                                <div class="col-6 mt-3">
                                    <div class="display-6 text-danger">{{ $summary['total_cards'] }}</div>
                                    <div class="text-muted">Total Cards</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Performance Tab -->
        <div class="tab-pane fade" id="performance" role="tabpanel">
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white">
                            <h6 class="mb-0">Top Performers</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Category</th>
                                            <th>Winner</th>
                                            <th>Value</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><i class="fas fa-trophy text-warning me-2"></i>Top Scorer</td>
                                            <td>{{ $topScorers->first()->name ?? 'N/A' }}</td>
                                            <td class="fw-bold">{{ $topScorers->first()->goals_count ?? 0 }} goals</td>
                                        </tr>
                                        <tr>
                                            <td><i class="fas fa-assistive-listening-systems text-info me-2"></i>Most Assists</td>
                                            <td>{{ $topAssists->first()->name ?? 'N/A' }}</td>
                                            <td class="fw-bold">{{ $topAssists->first()->assists_count ?? 0 }} assists</td>
                                        </tr>
                                        <tr>
                                            <td><i class="fas fa-shield-alt text-success me-2"></i>Clean Sheets</td>
                                            <td>{{ $cleanSheets->first()->name ?? 'N/A' }}</td>
                                            <td class="fw-bold">{{ $cleanSheets->first()->clean_sheets ?? 0 }} clean sheets</td>
                                        </tr>
                                        <tr>
                                            <td><i class="fas fa-trophy text-primary me-2"></i>Best Team</td>
                                            <td>{{ $rankings->first()->team->team_name ?? 'N/A' }}</td>
                                            <td class="fw-bold">{{ $rankings->first()->points ?? 0 }} points</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white">
                            <h6 class="mb-0">Performance Analysis</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    <div class="text-center">
                                        <div class="display-6 text-success">{{ $summary['avg_goals_per_match'] }}</div>
                                        <div class="text-muted">Avg Goals/Match</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-center">
                                        <div class="display-6 text-warning">{{ $summary['cards_per_match'] }}</div>
                                        <div class="text-muted">Avg Cards/Match</div>
                                    </div>
                                </div>
                                <div class="col-6 mt-3">
                                    <div class="text-center">
                                        <div class="display-6 text-info">{{ $summary['attendance_per_match'] }}</div>
                                        <div class="text-muted">Avg Attendance</div>
                                    </div>
                                </div>
                                <div class="col-6 mt-3">
                                    <div class="text-center">
                                        <div class="display-6 text-primary">{{ $summary['revenue_per_match'] }}</div>
                                        <div class="text-muted">Avg Revenue/Match</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Financials Tab -->
        <div class="tab-pane fade" id="financials" role="tabpanel">
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white">
                            <h6 class="mb-0">Revenue Breakdown</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Source</th>
                                            <th>Amount</th>
                                            <th>Percentage</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Ticket Sales</td>
                                            <td>{{ $summary['ticket_revenue'] }}</td>
                                            <td>{{ $summary['ticket_percentage'] }}%</td>
                                        </tr>
                                        <tr>
                                            <td>Sponsorships</td>
                                            <td>{{ $summary['sponsorship_revenue'] }}</td>
                                            <td>{{ $summary['sponsorship_percentage'] }}%</td>
                                        </tr>
                                        <tr>
                                            <td>Merchandise</td>
                                            <td>{{ $summary['merchandise_revenue'] }}</td>
                                            <td>{{ $summary['merchandise_percentage'] }}%</td>
                                        </tr>
                                        <tr>
                                            <td>Other</td>
                                            <td>{{ $summary['other_revenue'] }}</td>
                                            <td>{{ $summary['other_percentage'] }}%</td>
                                        </tr>
                                        <tr class="table-dark">
                                            <td><strong>Total</strong></td>
                                            <td><strong>{{ $summary['total_revenue'] }}</strong></td>
                                            <td><strong>100%</strong></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white">
                            <h6 class="mb-0">Financial Summary</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    <div class="text-center">
                                        <div class="display-6 text-success">{{ $summary['total_revenue'] }}</div>
                                        <div class="text-muted">Total Revenue</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-center">
                                        <div class="display-6 text-danger">{{ $summary['total_expenses'] }}</div>
                                        <div class="text-muted">Total Expenses</div>
                                    </div>
                                </div>
                                <div class="col-6 mt-3">
                                    <div class="text-center">
                                        <div class="display-6 text-info">{{ $summary['net_profit'] }}</div>
                                        <div class="text-muted">Net Profit</div>
                                    </div>
                                </div>
                                <div class="col-6 mt-3">
                                    <div class="text-center">
                                        <div class="display-6 text-warning">{{ $summary['profit_margin'] }}%</div>
                                        <div class="text-muted">Profit Margin</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reports Tab -->
        <div class="tab-pane fade" id="reports" role="tabpanel">
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white">
                            <h6 class="mb-0">Available Reports</h6>
                        </div>
                        <div class="card-body">
                            <div class="list-group">
                                <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="fas fa-file-pdf text-danger me-2"></i>
                                        Full Tournament Report
                                    </div>
                                    <span class="badge bg-primary">PDF</span>
                                </a>
                                <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="fas fa-file-excel text-success me-2"></i>
                                        Statistics Export
                                    </div>
                                    <span class="badge bg-success">Excel</span>
                                </a>
                                <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="fas fa-file-csv text-info me-2"></i>
                                        Raw Data Export
                                    </div>
                                    <span class="badge bg-info">CSV</span>
                                </a>
                                <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="fas fa-chart-bar text-warning me-2"></i>
                                        Performance Analysis
                                    </div>
                                    <span class="badge bg-warning">PDF</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white">
                            <h6 class="mb-0">Export Options</h6>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <button class="btn btn-primary" onclick="exportReport('pdf')">
                                    <i class="fas fa-file-pdf me-2"></i>Export as PDF
                                </button>
                                <button class="btn btn-success" onclick="exportReport('excel')">
                                    <i class="fas fa-file-excel me-2"></i>Export as Excel
                                </button>
                                <button class="btn btn-info" onclick="exportReport('csv')">
                                    <i class="fas fa-file-csv me-2"></i>Export as CSV
                                </button>
                                <button class="btn btn-warning" onclick="exportReport('print')">
                                    <i class="fas fa-print me-2"></i>Print Report
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for Real-time Updates and Export -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const refreshBtn = document.getElementById('refresh-summary-btn');
    const exportBtn = document.getElementById('export-summary-btn');
    const lastUpdatedSpan = document.getElementById('last-updated');

    // Auto-refresh every 30 seconds
    setInterval(function() {
        refreshSummary();
    }, 30000);

    // Manual refresh on button click
    refreshBtn.addEventListener('click', function() {
        refreshSummary();
    });

    // Export functionality
    exportBtn.addEventListener('click', function() {
        exportSummary();
    });

    function refreshSummary() {
        fetch('{{ route('admin.tournaments.statistics.api.live', $tournament) }}')
            .then(response => response.json())
            .then(data => {
                // Update last updated time
                const now = new Date();
                lastUpdatedSpan.textContent = 'Tournament summary last updated: ' + now.toLocaleString();

                // Update summary (would need to implement DOM updates)
                console.log('Tournament summary refreshed:', data);
            })
            .catch(error => {
                console.error('Error refreshing tournament summary:', error);
            });
    }

    function exportSummary() {
        // Show export options modal or redirect to export endpoint
        alert('Export functionality would be implemented here');
    }

    function exportReport(format) {
        // Handle different export formats
        switch(format) {
            case 'pdf':
                alert('PDF export would be generated here');
                break;
            case 'excel':
                alert('Excel export would be generated here');
                break;
            case 'csv':
                alert('CSV export would be generated here');
                break;
            case 'print':
                window.print();
                break;
        }
    }
});
</script>
@endsection
