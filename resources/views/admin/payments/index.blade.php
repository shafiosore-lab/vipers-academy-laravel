@extends('layouts.admin')

@section('title', __('Payments Management - Vipers Academy Admin'))

@section('content')
<!-- Page Header -->
<div class="page-header">
    <div>
        <h1 class="page-title">{{ __('Payments Management') }}</h1>
        <div class="page-breadcrumb">
            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a>
            <span>{{ __('Payments') }}</span>
        </div>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.payments.create') }}" class="btn btn-alibaba-primary">
            <i class="fas fa-plus me-2"></i>{{ __('Record Payment') }}
        </a>
        <a href="{{ route('admin.payments.financial-report') }}" class="btn btn-outline-primary">
            <i class="fas fa-chart-line me-2"></i>{{ __('Financial Report') }}
        </a>
    </div>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-card-icon primary">
                    <i class="fas fa-credit-card"></i>
                </div>
                <div>
                    <div class="stat-card-value">{{ $stats['total_payments'] }}</div>
                    <div class="stat-card-label">{{ __('Total Payments') }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-card-icon success">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div>
                    <div class="stat-card-value">KES {{ number_format($stats['total_revenue'], 0) }}</div>
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
                    <div class="stat-card-value">{{ $stats['pending_payments'] }}</div>
                    <div class="stat-card-label">{{ __('Pending Payments') }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-card-icon info">
                    <i class="fas fa-calendar"></i>
                </div>
                <div>
                    <div class="stat-card-value">KES {{ number_format($stats['monthly_revenue'], 0) }}</div>
                    <div class="stat-card-label">{{ __('This Month') }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Revenue by Type Chart -->
<div class="row mb-4">
    <div class="col-lg-8 mb-4">
        <div class="content-card">
            <div class="content-card-header">
                <h5 class="content-card-title">{{ __('Revenue by Payment Type') }}</h5>
            </div>
            <div class="content-card-body">
                <canvas id="revenueChart" height="200"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-4 mb-4">
        <div class="content-card">
            <div class="content-card-header">
                <h5 class="content-card-title">{{ __('Payment Types') }}</h5>
            </div>
            <div class="content-card-body">
                @foreach($revenueByType as $type => $amount)
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <div class="fw-semibold">{{ ucfirst(str_replace('_', ' ', $type)) }}</div>
                            <small class="text-muted">{{ __('Revenue') }}</small>
                        </div>
                        <div class="text-end">
                            <div class="fw-bold">KES {{ number_format($amount, 0) }}</div>
                            <small class="text-muted">{{ number_format(($amount / max(array_sum($revenueByType), 1)) * 100, 1) }}%</small>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Payments Table -->
<div class="content-card">
    <div class="content-card-header">
        <h5 class="content-card-title">{{ __('All Payments') }}</h5>
        <div class="d-flex gap-2">
            <!-- Search Form -->
            <form method="GET" class="d-flex gap-2">
                <input type="text" name="search" class="form-control" placeholder="{{ __('Search payments...') }}" value="{{ request('search') }}" style="width: 200px;">
                <select name="status" class="form-select" style="width: 150px;">
                    <option value="">{{ __('All Status') }}</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>{{ __('Completed') }}</option>
                    <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>{{ __('Failed') }}</option>
                    <option value="refunded" {{ request('status') == 'refunded' ? 'selected' : '' }}>{{ __('Refunded') }}</option>
                </select>
                <select name="type" class="form-select" style="width: 150px;">
                    <option value="">{{ __('All Types') }}</option>
                    <option value="registration_fee" {{ request('type') == 'registration_fee' ? 'selected' : '' }}>{{ __('Registration') }}</option>
                    <option value="program_fee" {{ request('type') == 'program_fee' ? 'selected' : '' }}>{{ __('Program') }}</option>
                    <option value="merchandise" {{ request('type') == 'merchandise' ? 'selected' : '' }}>{{ __('Merchandise') }}</option>
                    <option value="donation" {{ request('type') == 'donation' ? 'selected' : '' }}>{{ __('Donation') }}</option>
                </select>
                <select name="payer_type" class="form-select" style="width: 150px;">
                    <option value="">{{ __('All Payers') }}</option>
                    <option value="player" {{ request('payer_type') == 'player' ? 'selected' : '' }}>{{ __('Players') }}</option>
                    <option value="partner" {{ request('payer_type') == 'partner' ? 'selected' : '' }}>{{ __('Partners') }}</option>
                    <option value="customer" {{ request('payer_type') == 'customer' ? 'selected' : '' }}>{{ __('Customers') }}</option>
                </select>
                <button type="submit" class="btn btn-outline-primary">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>
    </div>
    <div class="content-card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>{{ __('Reference') }}</th>
                        <th>{{ __('Payer') }}</th>
                        <th>{{ __('Type') }}</th>
                        <th>{{ __('Amount') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Method') }}</th>
                        <th>{{ __('Date') }}</th>
                        <th>{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $payment)
                        <tr>
                            <td>
                                <a href="{{ route('admin.payments.show', $payment) }}" class="text-decoration-none fw-semibold">
                                    {{ $payment->payment_reference }}
                                </a>
                                @if($payment->transaction_id)
                                    <br><small class="text-muted">{{ $payment->transaction_id }}</small>
                                @endif
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $payment->getPayerName() }}</div>
                                <small class="text-muted">{{ ucfirst($payment->payer_type) }}</small>
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ ucfirst(str_replace('_', ' ', $payment->payment_type)) }}</span>
                            </td>
                            <td class="fw-semibold">{{ $payment->getFormattedAmount() }}</td>
                            <td>
                                <span class="badge bg-{{ $payment->payment_status === 'completed' ? 'success' : ($payment->payment_status === 'pending' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($payment->payment_status) }}
                                </span>
                            </td>
                            <td>{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</td>
                            <td>{{ $payment->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.payments.show', $payment) }}" class="btn btn-sm btn-outline-primary" title="{{ __('View') }}">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.payments.edit', $payment) }}" class="btn btn-sm btn-outline-secondary" title="{{ __('Edit') }}">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if($payment->isPending())
                                        <form method="POST" action="{{ route('admin.payments.destroy', $payment) }}" class="d-inline" onsubmit="return confirm('{{ __('Are you sure you want to delete this payment record?') }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="{{ __('Delete') }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <i class="fas fa-credit-card fa-2x text-muted mb-2"></i>
                                <div class="text-muted">{{ __('No payments found') }}</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($payments->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $payments->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Revenue by Type Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    const revenueChart = new Chart(revenueCtx, {
        type: 'doughnut',
        data: {
            labels: @json(array_keys($revenueByType)),
            datasets: [{
                data: @json(array_values($revenueByType)),
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
                        padding: 20,
                        usePointStyle: true,
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

    .stat-card-icon.primary { background: #fff5f0; color: #ea1c4d; }
    .stat-card-icon.success { background: #f0fdf4; color: #059669; }
    .stat-card-icon.warning { background: #fffbeb; color: #f59e0b; }
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
        display: flex;
        justify-content: space-between;
        align-items: center;
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

    .badge {
        font-size: 11px;
        font-weight: 600;
        padding: 6px 10px;
        border-radius: 20px;
    }
</style>
@endpush
