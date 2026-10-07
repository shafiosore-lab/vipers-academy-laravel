<!-- Compact Analytics Dashboard Component -->
<div class="compact-analytics">
    <!-- Slim Chart Navigation -->
    <div class="analytics-nav">
        <ul class="nav nav-pills" id="analyticsTab" role="tablist">
            <li class="nav-item">
                <button class="nav-link active" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview" type="button">
                    <i class="fas fa-th-large"></i> Overview
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="stats-tab" data-bs-toggle="tab" data-bs-target="#stats" type="button">
                    <i class="fas fa-chart-bar"></i> Statistics
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="enrollments-tab" data-bs-toggle="tab" data-bs-target="#enrollments" type="button">
                    <i class="fas fa-user-plus"></i> Enrollments
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="revenue-tab" data-bs-toggle="tab" data-bs-target="#revenue" type="button">
                    <i class="fas fa-dollar-sign"></i> Revenue
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="programs-tab" data-bs-toggle="tab" data-bs-target="#programs" type="button">
                    <i class="fas fa-futbol"></i> Programs
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="activity-tab" data-bs-toggle="tab" data-bs-target="#activity" type="button">
                    <i class="fas fa-clock"></i> Recent Activity
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="documents-tab" data-bs-toggle="tab" data-bs-target="#documents" type="button">
                    <i class="fas fa-file-alt"></i> Documents
                </button>
            </li>
        </ul>
    </div>

    <!-- Chart Content -->
    <div class="tab-content" id="analyticsTabContent">
        <!-- Overview Tab -->
        <div class="tab-pane fade show active" id="overview" role="tabpanel">
            <div class="row g-2">
                <!-- Key Metrics -->
                <div class="col-12">
                    <div class="metrics-row">
                        <div class="metric-item">
                            <span class="metric-value">{{ $metrics['total_enrollments'] ?? 0 }}</span>
                            <span class="metric-label">Enrollments</span>
                        </div>
                        <div class="metric-item">
                            <span class="metric-value">KES {{ number_format($metrics['total_revenue'] ?? 0, 0) }}</span>
                            <span class="metric-label">Revenue</span>
                        </div>
                        <div class="metric-item">
                            <span class="metric-value">{{ $metrics['active_programs'] ?? 0 }}</span>
                            <span class="metric-label">Programs</span>
                        </div>
                        <div class="metric-item">
                            <span class="metric-value">{{ $metrics['attendance_rate'] ?? 0 }}%</span>
                            <span class="metric-label">Attendance</span>
                        </div>
                    </div>
                </div>
                <!-- Mini Charts -->
                <div class="col-md-4">
                    <div class="mini-chart-container">
                        <canvas id="miniEnrollmentChart"></canvas>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mini-chart-container">
                        <canvas id="miniRevenueChart"></canvas>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mini-chart-container">
                        <canvas id="miniProgramChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Tab (Academy Statistics Table) -->
        <div class="tab-pane fade" id="stats" role="tabpanel">
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0" style="font-size: 12px;">
                    <thead class="table-light" style="font-size: 10px;">
                        <tr>
                            <th class="py-1 px-2">Metric</th>
                            <th class="py-1 px-2">Value</th>
                            <th class="py-1 px-2">Change</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="py-0">
                            <td class="py-1 px-2"><i class="fas fa-users text-muted me-1"></i>Total Players</td>
                            <td class="py-1 px-2 fw-bold text-primary">{{ $metrics['total_enrollments'] ?? 0 }}</td>
                            <td class="py-1 px-2"><span class="badge bg-success-subtle text-success border-0">+0%</span></td>
                        </tr>
                        <tr class="py-0">
                            <td class="py-1 px-2"><i class="fas fa-check-circle text-success me-1"></i>Active</td>
                            <td class="py-1 px-2 fw-bold">{{ $metrics['active_programs'] ?? 0 }}</td>
                            <td class="py-1 px-2"><small class="text-muted">programs</small></td>
                        </tr>
                        <tr class="py-0">
                            <td class="py-1 px-2"><i class="fas fa-clock text-warning me-1"></i>Pending</td>
                            <td class="py-1 px-2 fw-bold">0</td>
                            <td class="py-1 px-2"><small class="text-muted">pending</small></td>
                        </tr>
                        <tr class="py-0">
                            <td class="py-1 px-2"><i class="fas fa-bullseye text-primary me-1"></i>Active Programs</td>
                            <td class="py-1 px-2 fw-bold">{{ $metrics['active_programs'] ?? 0 }}</td>
                            <td class="py-1 px-2"><span class="badge bg-success-subtle text-success border-0">+0%</span></td>
                        </tr>
                        <tr class="py-0">
                            <td class="py-1 px-2"><i class="fas fa-handshake text-info me-1"></i>Partnerships</td>
                            <td class="py-1 px-2 fw-bold">0</td>
                            <td class="py-1 px-2"><span class="badge bg-secondary-subtle text-secondary border-0">0 pending</span></td>
                        </tr>
                        <tr class="py-0">
                            <td class="py-1 px-2"><i class="fas fa-user-plus text-purple me-1"></i>Enrollments</td>
                            <td class="py-1 px-2 fw-bold">{{ $metrics['total_enrollments'] ?? 0 }}</td>
                            <td class="py-1 px-2"><span class="badge bg-info-subtle text-info border-0">+0 this week</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Enrollments Tab -->
        <div class="tab-pane fade" id="enrollments" role="tabpanel">
            <div class="compact-chart-container">
                <canvas id="compactEnrollmentChart"></canvas>
            </div>
        </div>

        <!-- Revenue Tab -->
        <div class="tab-pane fade" id="revenue" role="tabpanel">
            @php
                $revenueBreakdown = $metrics['revenue_breakdown'] ?? null;
            @endphp
            @if($revenueBreakdown)
            <div class="revenue-breakdown-table">
                <table class="table table-sm table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Revenue Source</th>
                            <th class="text-end">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><i class="fas fa-building text-primary me-2"></i>Player Subscriptions</td>
                            <td class="text-end fw-bold">KES {{ number_format($revenueBreakdown['subscription_revenue'] ?? 0, 0) }}</td>
                        </tr>
                        <tr>
                            <td><i class="fas fa-calendar-alt text-success me-2"></i>Monthly Payments</td>
                            <td class="text-end fw-bold">KES {{ number_format($revenueBreakdown['monthly_payments'] ?? 0, 0) }}</td>
                        </tr>
                        <tr>
                            <td><i class="fas fa-futbol text-warning me-2"></i>Program Payments</td>
                            <td class="text-end fw-bold">KES {{ number_format($revenueBreakdown['program_payments'] ?? 0, 0) }}</td>
                        </tr>
                        <tr class="table-active border-top">
                            <td class="fw-bold"><i class="fas fa-wallet me-2"></i>Total Revenue</td>
                            <td class="text-end fw-bold text-primary">KES {{ number_format($revenueBreakdown['total_revenue'] ?? 0, 0) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            @else
            <div class="compact-chart-container">
                <canvas id="compactRevenueChart"></canvas>
            </div>
            @endif
        </div>

        <!-- Programs Tab -->
        <div class="tab-pane fade" id="programs" role="tabpanel">
            <div class="row g-2">
                <div class="col-md-6">
                    <div class="compact-chart-container">
                        <canvas id="compactProgramFeesChart"></canvas>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="compact-chart-container">
                        <canvas id="compactProgramCategoryChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity Tab -->
        <div class="tab-pane fade" id="activity" role="tabpanel">
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Type</th>
                            <th>Description</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($metrics['recent_activity']) && count($metrics['recent_activity']) > 0)
                            @foreach($metrics['recent_activity'] as $activity)
                            <tr>
                                <td><span class="me-2">{{ $activity['icon'] ?? '📋' }}</span>{{ $activity['type'] ?? 'Activity' }}</td>
                                <td><small>{{ $activity['description'] ?? 'No description' }}</small></td>
                                <td><small class="text-muted">{{ $activity['date'] ?? '-' }}</small></td>
                                <td>
                                    @if(isset($activity['status']))
                                        @if($activity['status'] === 'completed' || $activity['status'] === 'active')
                                            <span class="badge bg-success">{{ ucfirst($activity['status']) }}</span>
                                        @elseif($activity['status'] === 'pending')
                                            <span class="badge bg-warning">{{ ucfirst($activity['status']) }}</span>
                                        @else
                                            <span class="badge bg-secondary">{{ ucfirst($activity['status']) }}</span>
                                        @endif
                                    @else
                                        <span class="badge bg-info">New</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        @else
                        <tr>
                            <td colspan="4" class="text-center text-muted py-3">
                                <i class="fas fa-inbox me-2"></i>No recent activity
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Documents Tab -->
        <div class="tab-pane fade" id="documents" role="tabpanel">
            @php
                $docStats = $metrics['document_stats'] ?? null;
            @endphp
            @if($docStats)
            <div class="row g-2 mb-2">
                <!-- Document Summary Cards -->
                <div class="col-4">
                    <div class="doc-summary-card">
                        <span class="doc-count">{{ $docStats['total_documents'] ?? 0 }}</span>
                        <span class="doc-label">Total</span>
                    </div>
                </div>
                <div class="col-4">
                    <div class="doc-summary-card">
                        <span class="doc-count">{{ $docStats['active_documents'] ?? 0 }}</span>
                        <span class="doc-label">Active</span>
                    </div>
                </div>
                <div class="col-4">
                    <div class="doc-summary-card">
                        <span class="doc-count">{{ $docStats['documents_this_month'] ?? 0 }}</span>
                        <span class="doc-label">This Month</span>
                    </div>
                </div>
            </div>
            @endif

            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Document</th>
                            <th>Category</th>
                            <th>Uploaded</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($docStats && isset($docStats['recent_documents']) && count($docStats['recent_documents']) > 0)
                            @foreach($docStats['recent_documents'] as $doc)
                            <tr>
                                <td>
                                    <span class="me-2">{{ $doc['icon'] ?? '📄' }}</span>
                                    <span class="text-truncate" style="max-width: 120px; display: inline-block;">{{ $doc['title'] ?? 'Untitled' }}</span>
                                </td>
                                <td><small class="text-muted">{{ $doc['category'] ?? '-' }}</small></td>
                                <td><small class="text-muted">{{ $doc['date'] ?? '-' }}</small></td>
                                <td>
                                    @if(isset($doc['status']))
                                        @if($doc['status'] === 'Active')
                                            <span class="badge bg-success">{{ $doc['status'] }}</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $doc['status'] }}</span>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        @else
                        <tr>
                            <td colspan="4" class="text-center text-muted py-3">
                                <i class="fas fa-folder-open me-2"></i>No documents found
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <!-- Category Breakdown -->
            @if($docStats && isset($docStats['category_breakdown']) && count($docStats['category_breakdown']) > 0)
            <div class="mt-2 pt-2 border-top">
                <h6 class="small text-muted mb-2"><i class="fas fa-chart-pie me-1"></i>Categories</h6>
                <div class="d-flex flex-wrap gap-1">
                    @foreach($docStats['category_breakdown'] as $cat)
                    <span class="badge bg-light text-dark border">
                        {{ $cat['display_name'] ?? $cat['category'] }}: {{ $cat['count'] }}
                    </span>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@push('styles')
<style>
.compact-analytics {
    background: #fff;
    border-radius: 8px;
    padding: 1rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.compact-analytics .analytics-nav {
    margin-bottom: 1rem;
    border-bottom: 1px solid #eee;
    padding-bottom: 0.75rem;
}

.compact-analytics .nav-pills {
    gap: 0.5rem;
}

.compact-analytics .nav-pills .nav-link {
    border-radius: 20px;
    padding: 6px 14px;
    font-size: 12px;
    font-weight: 500;
    color: #6c757d;
    background: #f8f9fa;
    transition: all 0.2s ease;
}

.compact-analytics .nav-pills .nav-link.active {
    background: linear-gradient(45deg, #ea1c4d, #c0173f);
    color: white;
    box-shadow: 0 2px 8px rgba(234, 28, 77, 0.25);
}

.compact-analytics .nav-pills .nav-link:not(.active):hover {
    background: #e9ecef;
    color: #ea1c4d;
}

.compact-analytics .nav-pills .nav-link i {
    font-size: 11px;
    margin-right: 4px;
}

/* Metrics Row */
.compact-analytics .metrics-row {
    display: flex;
    justify-content: space-between;
    gap: 0.75rem;
    margin-bottom: 1rem;
    padding: 0.75rem;
    background: linear-gradient(135deg, #f8f9fa 0%, #fff 100%);
    border-radius: 8px;
    border: 1px solid #eee;
}

.compact-analytics .metric-item {
    text-align: center;
    flex: 1;
    padding: 0.5rem;
}

.compact-analytics .metric-value {
    display: block;
    font-size: 18px;
    font-weight: 700;
    color: #1a1a1a;
}

.compact-analytics .metric-label {
    display: block;
    font-size: 10px;
    color: #6c757d;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Mini Chart Containers */
.compact-analytics .mini-chart-container {
    height: 120px;
    position: relative;
}

.compact-analytics .compact-chart-container {
    height: 200px;
    position: relative;
}

/* Responsive */
@media (max-width: 768px) {
    .compact-analytics .metrics-row {
        flex-wrap: wrap;
    }

    .compact-analytics .metric-item {
        flex: 1 1 45%;
    }

    .compact-analytics .nav-pills .nav-link {
        padding: 5px 10px;
        font-size: 11px;
    }

    .compact-analytics .nav-pills {
        flex-wrap: wrap;
    }
}

/* Recent Activity Tab */
.compact-analytics .table th {
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.compact-analytics .table td {
    vertical-align: middle;
    font-size: 13px;
}

.compact-analytics .badge {
    font-size: 10px;
    padding: 3px 8px;
}

/* Compact Badge Styles */
.compact-analytics .badge.bg-success-subtle {
    background-color: rgba(101, 193, 110, 0.15);
    color: #28a745;
}

.compact-analytics .badge.bg-secondary-subtle {
    background-color: rgba(108, 117, 125, 0.15);
    color: #6c757d;
}

.compact-analytics .badge.bg-info-subtle {
    background-color: rgba(23, 162, 184, 0.15);
    color: #17a2b8;
}

.compact-analytics .table td,
.compact-analytics .table th {
    padding: 0.25rem 0.5rem;
    vertical-align: middle;
}

.compact-analytics .table-sm td,
.compact-analytics .table-sm th {
    padding: 0.15rem 0.4rem;
}

.compact-analytics .text-purple {
    color: #6f42c1;
}

/* Document Summary Cards */
.compact-analytics .doc-summary-card {
    background: linear-gradient(135deg, #f8f9fa 0%, #fff 100%);
    border: 1px solid #e9ecef;
    border-radius: 6px;
    padding: 0.5rem;
    text-align: center;
}

.compact-analytics .doc-count {
    display: block;
    font-size: 16px;
    font-weight: 700;
    color: #ea1c4d;
}

.compact-analytics .doc-label {
    display: block;
    font-size: 9px;
    color: #6c757d;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Revenue Breakdown Table */
.compact-analytics .revenue-breakdown-table {
    background: #fff;
    border-radius: 8px;
}

.compact-analytics .revenue-breakdown-table .table {
    margin-bottom: 0;
}

.compact-analytics .revenue-breakdown-table td {
    padding: 0.5rem 0.75rem;
    vertical-align: middle;
}

.compact-analytics .revenue-breakdown-table .table-active {
    background-color: rgba(234, 28, 77, 0.05);
}

.compact-analytics .revenue-breakdown-table .fw-bold {
    font-weight: 600;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Only initialize if Chart.js is loaded
    if (typeof Chart === 'undefined') return;

    const colors = {
        primary: '#ea1c4d',
        secondary: '#65c16e',
        accent: '#fbc761',
        info: '#17a2b8',
        purple: '#6f42c1'
    };

    const colorArray = [colors.primary, colors.secondary, colors.accent, colors.info, colors.purple];

    // Mini Enrollment Chart
    const miniEnrollmentCtx = document.getElementById('miniEnrollmentChart');
    if (miniEnrollmentCtx) {
        const enrollmentData = @json($chartData['monthly_enrollments'] ?? []);

        new Chart(miniEnrollmentCtx.getContext('2d'), {
            type: 'line',
            data: {
                labels: enrollmentData.map(item => item.month),
                datasets: [{
                    data: enrollmentData.map(item => item.count),
                    borderColor: colors.primary,
                    backgroundColor: 'rgba(234, 28, 77, 0.1)',
                    fill: true,
                    tension: 0.4,
                    pointRadius: 3,
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false }, tooltip: { enabled: true } },
                scales: {
                    y: { display: false },
                    x: { display: true, grid: { display: false }, ticks: { font: { size: 9 } } }
                },
                animation: { duration: 1000 }
            }
        });
    }

    // Mini Revenue Chart
    const miniRevenueCtx = document.getElementById('miniRevenueChart');
    if (miniRevenueCtx) {
        const revenueData = @json($chartData['monthly_revenue'] ?? []);

        new Chart(miniRevenueCtx.getContext('2d'), {
            type: 'line',
            data: {
                labels: revenueData.map(item => item.month),
                datasets: [{
                    data: revenueData.map(item => item.amount),
                    borderColor: colors.secondary,
                    backgroundColor: 'rgba(101, 193, 110, 0.1)',
                    fill: true,
                    tension: 0.4,
                    pointRadius: 3,
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false }, tooltip: { enabled: true } },
                scales: {
                    y: { display: false },
                    x: { display: true, grid: { display: false }, ticks: { font: { size: 9 } } }
                },
                animation: { duration: 1000 }
            }
        });
    }

    // Mini Program Chart
    const miniProgramCtx = document.getElementById('miniProgramChart');
    if (miniProgramCtx) {
        const programData = @json($chartData['program_categories'] ?? []);
        let labels = [], data = [];

        if (typeof programData === 'object' && Object.keys(programData).length > 0) {
            labels = Object.keys(programData).slice(0, 4);
            data = Object.values(programData).slice(0, 4);
        } else {
            labels = ['No Data'];
            data = [1];
        }

        new Chart(miniProgramCtx.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: colorArray,
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: { legend: { display: false }, tooltip: { enabled: true } },
                animation: { duration: 1000 }
            }
        });
    }

    // Compact Enrollment Chart
    const compactEnrollmentCtx = document.getElementById('compactEnrollmentChart');
    if (compactEnrollmentCtx) {
        const enrollmentData = @json($chartData['monthly_enrollments'] ?? []);

        new Chart(compactEnrollmentCtx.getContext('2d'), {
            type: 'line',
            data: {
                labels: enrollmentData.map(item => item.month),
                datasets: [{
                    label: 'Enrollments',
                    data: enrollmentData.map(item => item.count),
                    borderColor: colors.primary,
                    backgroundColor: 'rgba(234, 28, 77, 0.1)',
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: true, position: 'top', labels: { usePointStyle: true, padding: 15, font: { size: 11 } } },
                    tooltip: { enabled: true, cornerRadius: 6 }
                },
                scales: {
                    y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' }, ticks: { font: { size: 10 } } },
                    x: { grid: { display: false }, ticks: { font: { size: 10 } } }
                },
                animation: { duration: 1200, easing: 'easeOutQuart' }
            }
        });
    }

    // Compact Revenue Chart
    const compactRevenueCtx = document.getElementById('compactRevenueChart');
    if (compactRevenueCtx) {
        const revenueData = @json($chartData['monthly_revenue'] ?? []);

        new Chart(compactRevenueCtx.getContext('2d'), {
            type: 'line',
            data: {
                labels: revenueData.map(item => item.month),
                datasets: [{
                    label: 'Revenue (KES)',
                    data: revenueData.map(item => item.amount),
                    borderColor: colors.secondary,
                    backgroundColor: 'rgba(101, 193, 110, 0.1)',
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: true, position: 'top', labels: { usePointStyle: true, padding: 15, font: { size: 11 } } },
                    tooltip: {
                        enabled: true,
                        cornerRadius: 6,
                        callbacks: { label: ctx => 'KES ' + ctx.parsed.y.toLocaleString() }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0,0,0,0.05)' },
                        ticks: { font: { size: 10 }, callback: v => 'KES ' + (v/1000) + 'k' }
                    },
                    x: { grid: { display: false }, ticks: { font: { size: 10 } } }
                },
                animation: { duration: 1200, easing: 'easeOutQuart' }
            }
        });
    }

    // Compact Program Fees Chart
    const compactProgramFeesCtx = document.getElementById('compactProgramFeesChart');
    if (compactProgramFeesCtx) {
        const programFeesData = @json($chartData['program_fees'] ?? []);

        new Chart(compactProgramFeesCtx.getContext('2d'), {
            type: 'bar',
            data: {
                labels: programFeesData.map(item => item.title ? item.title.substring(0, 12) + (item.title.length > 12 ? '...' : '') : 'N/A'),
                datasets: [
                    { label: 'Regular', data: programFeesData.map(item => item.regular_fee || 0), backgroundColor: colors.primary, borderRadius: 4 },
                    { label: 'Mumias', data: programFeesData.map(item => item.mumias_fee || 0), backgroundColor: colors.accent, borderRadius: 4 }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: true, position: 'top', labels: { usePointStyle: true, padding: 10, font: { size: 10 } } },
                    tooltip: { enabled: true, cornerRadius: 6 }
                },
                scales: {
                    y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' }, ticks: { font: { size: 9 } } },
                    x: { grid: { display: false }, ticks: { font: { size: 9 }, maxRotation: 45 } }
                },
                animation: { duration: 1200 }
            }
        });
    }

    // Compact Program Category Chart
    const compactProgramCategoryCtx = document.getElementById('compactProgramCategoryChart');
    if (compactProgramCategoryCtx) {
        const programCategoryData = @json($chartData['program_categories'] ?? []);
        let labels = [], data = [];

        if (typeof programCategoryData === 'object' && Object.keys(programCategoryData).length > 0) {
            labels = Object.keys(programCategoryData);
            data = Object.values(programCategoryData);
        } else {
            labels = ['No Data'];
            data = [1];
        }

        new Chart(compactProgramCategoryCtx.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: colorArray,
                    borderWidth: 0,
                    hoverOffset: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '60%',
                plugins: {
                    legend: { display: true, position: 'right', labels: { usePointStyle: true, padding: 10, font: { size: 10 } } },
                    tooltip: { enabled: true, cornerRadius: 6 }
                },
                animation: { duration: 1200 }
            }
        });
    }
});
</script>
@endpush
