@extends('layouts.admin')

@section('title', __('Financial Report - Vipers Academy Admin'))

@section('content')
<!-- Page Header -->
<div class="page-header">
    <div>
        <h1 class="page-title">{{ __('Financial Report') }}</h1>
        <div class="page-breadcrumb">
            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a>
            <a href="{{ route('admin.payments.index') }}">{{ __('Payments') }}</a>
            <span>{{ __('Report') }}</span>
        </div>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.payments.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>{{ __('Back to Payments') }}
        </a>
    </div>
</div>

<!-- Report Filters -->
<div class="content-card mb-4">
    <div class="content-card-header">
        <h5 class="content-card-title">{{ __('Report Filters') }}</h5>
    </div>
    <div class="content-card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <label for="start_date" class="form-label">{{ __('Start Date') }}</label>
                <input type="date" class="form-control" id="start_date" name="start_date" value="{{ $startDate }}">
            </div>
            <div class="col-md-4">
                <label for="end_date" class="form-label">{{ __('End Date') }}</label>
                <input type="date" class="form-control" id="end_date" name="end_date" value="{{ $endDate }}">
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-alibaba-primary w-100">
                    <i class="fas fa-search me-2"></i>{{ __('Generate Report') }}
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Financial Summary -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-card-icon success">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div>
                    <div class="stat-card-value">KES {{ number_format($report['total_revenue'], 0) }}</div>
                    <div class="stat-card-label">{{ __('Total Revenue') }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-card-icon warning">
                    <i class="fas fa-clock"></i>
                </div>
                <div>
                    <div class="stat-card-value">KES {{ number_format($report['pending_payments'], 0) }}</div>
                    <div class="stat-card-label">{{ __('Pending Payments') }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-card-icon danger">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div>
                    <div class="stat-card-value">KES {{ number_format($report['overdue_payments'], 0) }}</div>
                    <div class="stat-card-label">{{ __('Overdue Payments') }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-card-icon info">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div>
                    <div class="stat-card-value">{{ count($report['revenue_by_type']) }}</div>
                    <div class="stat-card-label">{{ __('Active Revenue Types') }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Revenue by Type -->
    <div class="col-lg-6 mb-4">
        <div class="content-card">
            <div class="content-card-header">
                <h5 class="content-card-title">{{ __('Revenue by Payment Type') }}</h5>
            </div>
            <div class="content-card-body">
                <canvas id="revenueTypeChart" height="300"></canvas>
            </div>
        </div>
    </div>

    <!-- Revenue by Payer Type -->
    <div class="col-lg-6 mb-4">
        <div class="content-card">
            <div class="content-card-header">
                <h5 class="content-card-title">{{ __('Revenue by Payer Type') }}</h5>
            </div>
            <div class="content-card-body">
                <canvas id="revenuePayerChart" height="300"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Detailed Breakdown -->
<div class="content-card">
    <div class="content-card-header">
        <h5 class="content-card-title">{{ __('Detailed Revenue Breakdown') }}</h5>
    </div>
    <div class="content-card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>{{ __('Payment Type') }}</th>
                        <th>{{ __('Revenue') }}</th>
                        <th>{{ __('Percentage') }}</th>
                        <th>{{ __('Transactions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($report['revenue_by_type'] as $type => $amount)
                        <tr>
                            <td class="fw-semibold">{{ ucfirst(str_replace('_', ' ', $type)) }}</td>
                            <td>KES {{ number_format($amount, 2) }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="progress flex-grow-1 me-2" style="height: 8px;">
                                        <div class="progress-bar bg-primary" style="width: {{ ($amount / max($report['total_revenue'], 1)) * 100 }}%"></div>
                                    </div>
                                    {{ number_format(($amount / max($report['total_revenue'], 1)) * 100, 1) }}%
                                </div>
                            </td>
                            <td>{{ \App\Models\Payment::completed()->where('payment_type', $type)->whereBetween('paid_at', [$startDate, $endDate])->count() }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Revenue by Type Chart
    const revenueTypeCtx = document.getElementById('revenueTypeChart').getContext('2d');
    const revenueTypeChart = new Chart(revenueTypeCtx, {
        type: 'pie',
        data: {
            labels: @json(array_keys($report['revenue_by_type'])),
            datasets: [{
                data: @json(array_values($report['revenue_by_type'])),
                backgroundColor: ['#ea1c4d', '#059669', '#f59e0b', '#0891b2', '#8b5cf6', '#dc2626'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        generateLabels: function(chart) {
                            const data = chart.data;
                            return data.labels.map((label, i) => ({
                                text: label.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase()) +
                                      ' - KES ' + data.datasets[0].data[i].toLocaleString(),
                                fillStyle: data.datasets[0].backgroundColor[i],
                                index: i
                            }));
                        }
                    }
                }
            }
        }
    });

    // Revenue by Payer Type Chart
    const revenuePayerCtx = document.getElementById('revenuePayerChart').getContext('2d');
    const revenuePayerChart = new Chart(revenuePayerCtx, {
        type: 'doughnut',
        data: {
            labels: @json(array_keys($report['revenue_by_payer_type'])),
            datasets: [{
                data: @json(array_values($report['revenue_by_payer_type'])),
                backgroundColor: ['#3b82f6', '#10b981', '#f59e0b'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
</script>
@endpush

@push('styles')
<style>
    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        border: 1px solid #e8e8e8;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .stat-card-header {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .stat-card-icon {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }

    .stat-card-icon.success { background: #f0fdf4; color: #059669; }
    .stat-card-icon.warning { background: #fffbeb; color: #f59e0b; }
    .stat-card-icon.danger { background: #fef2f2; color: #dc2626; }
    .stat-card-icon.info { background: #f0f9ff; color: #0891b2; }

    .stat-card-value {
        font-size: 24px;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 4px;
    }

    .stat-card-label {
        font-size: 13px;
        color: #666;
        font-weight: 500;
    }

    .content-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        border: 1px solid #e8e8e8;
        overflow: hidden;
    }

    .content-card-header {
        padding: 1.5rem;
        border-bottom: 1px solid #e8e8e8;
        background: #f8f9fa;
    }

    .content-card-title {
        font-size: 16px;
        font-weight: 600;
        color: #1a1a1a;
        margin: 0;
    }

    .content-card-body {
        padding: 1.5rem;
    }

    .btn-alibaba-primary {
        background: #ea1c4d;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 6px;
        font-weight: 600;
        transition: all 0.2s;
    }

    .btn-alibaba-primary:hover {
        background: #d0173f;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(234, 28, 77, 0.3);
    }

    .table th {
        background: #f8f9fa;
        border-bottom: 2px solid #e8e8e8;
        font-weight: 600;
        font-size: 14px;
        color: #495057;
        padding: 1rem;
    }

    .table td {
        padding: 1rem;
        vertical-align: middle;
    }
</style>
@endpush
