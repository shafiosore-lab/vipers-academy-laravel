@extends('layouts.admin')

@section('title', __('Payments Management - Vipers Academy Admin'))

@section('content')
<div class="container-fluid px-2">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0 fw-bold">Payments</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.payments.create') }}" class="btn btn-sm btn-primary">
                <i class="fas fa-plus me-1"></i>Record Payment
            </a>
            <a href="{{ route('admin.payments.financial-report') }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-chart-line me-1"></i>Report
            </a>
        </div>
    </div>

    <!-- Stats Cards Row -->
    <div class="row g-2 mb-3">
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; background: linear-gradient(135deg, #ea1c4d 0%, #c31432 100%);">
                                <i class="fas fa-credit-card text-white"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="fs-4 fw-bold text-dark">{{ $stats['total_payments'] }}</div>
                            <div class="text-muted small">Total Payments</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);">
                                <i class="fas fa-dollar-sign text-white"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="fs-5 fw-bold text-dark">KES {{ number_format($stats['total_revenue'], 0) }}</div>
                            <div class="text-muted small">Total Revenue</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);">
                                <i class="fas fa-clock text-white"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="fs-4 fw-bold text-dark">{{ $stats['pending_payments'] }}</div>
                            <div class="text-muted small">Pending</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);">
                                <i class="fas fa-calendar text-white"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="fs-5 fw-bold text-dark">KES {{ number_format($stats['monthly_revenue'], 0) }}</div>
                            <div class="text-muted small">This Month</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-2 mb-3">
        <!-- Revenue Chart -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header py-2 bg-white">
                    <h6 class="mb-0 small fw-bold">Revenue by Type</h6>
                </div>
                <div class="card-body p-2">
                    <canvas id="revenueChart" height="150"></canvas>
                </div>
            </div>
        </div>
        <!-- Payment Types List -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header py-2 bg-white">
                    <h6 class="mb-0 small fw-bold">Payment Types</h6>
                </div>
                <div class="card-body p-2">
                    @foreach($revenueByType as $type => $amount)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="small">
                                <div class="fw-semibold">{{ ucfirst(str_replace('_', ' ', $type)) }}</div>
                            </div>
                            <div class="text-end">
                                <div class="fw-bold small">KES {{ number_format($amount, 0) }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Payments Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header py-2 bg-white d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h6 class="mb-0 small fw-bold">All Payments</h6>
            <!-- Filters -->
            <form method="GET" class="d-flex gap-1">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Search..." value="{{ request('search') }}" style="width: 120px;">
                <select name="status" class="form-select form-select-sm" style="width: 100px;">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                </select>
                <select name="type" class="form-select form-select-sm" style="width: 100px;">
                    <option value="">All Types</option>
                    <option value="registration_fee" {{ request('type') == 'registration_fee' ? 'selected' : '' }}>Registration</option>
                    <option value="program_fee" {{ request('type') == 'program_fee' ? 'selected' : '' }}>Program</option>
                    <option value="donation" {{ request('type') == 'donation' ? 'selected' : '' }}>Donation</option>
                </select>
                <button type="submit" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>
        <div class="card-body p-2">
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead class="table-light">
                        <tr class="small">
                            <th class="py-1">Reference</th>
                            <th class="py-1">Payer</th>
                            <th class="py-1">Type</th>
                            <th class="py-1">Amount</th>
                            <th class="py-1">Status</th>
                            <th class="py-1">Method</th>
                            <th class="py-1">Date</th>
                            <th class="py-1"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                            <tr>
                                <td class="py-1 align-middle small">
                                    <a href="{{ route('admin.payments.show', $payment) }}" class="text-decoration-none fw-semibold">
                                        {{ $payment->payment_reference }}
                                    </a>
                                </td>
                                <td class="py-1 align-middle small">
                                    <div class="fw-semibold">{{ $payment->getPayerName() }}</div>
                                    <small class="text-muted">{{ ucfirst($payment->payer_type) }}</small>
                                </td>
                                <td class="py-1 align-middle">
                                    <span class="badge bg-secondary-subtle text-secondary" style="font-size: 10px;">
                                        {{ ucfirst(str_replace('_', ' ', $payment->payment_type)) }}
                                    </span>
                                </td>
                                <td class="py-1 align-middle fw-semibold small">{{ $payment->getFormattedAmount() }}</td>
                                <td class="py-1 align-middle">
                                    <span class="badge bg-{{ $payment->payment_status === 'completed' ? 'success' : ($payment->payment_status === 'pending' ? 'warning' : 'danger') }}-subtle text-{{ $payment->payment_status === 'completed' ? 'success' : ($payment->payment_status === 'pending' ? 'warning' : 'danger') }}" style="font-size: 10px;">
                                        {{ ucfirst($payment->payment_status) }}
                                    </span>
                                </td>
                                <td class="py-1 align-middle small">{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</td>
                                <td class="py-1 align-middle small">{{ $payment->created_at->format('M d, Y') }}</td>
                                <td class="py-1 align-middle">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('admin.payments.show', $payment) }}" class="btn btn-sm btn-outline-primary py-0 px-1" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.payments.edit', $payment) }}" class="btn btn-sm btn-outline-secondary py-0 px-1" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-3">
                                    <div class="text-muted small">No payments found</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($payments->hasPages())
                <div class="d-flex justify-content-center mt-2">
                    {{ $payments->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
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
                        padding: 10,
                        usePointStyle: true,
                        font: { size: 11 }
                    }
                }
            }
        }
    });
</script>
@endpush
