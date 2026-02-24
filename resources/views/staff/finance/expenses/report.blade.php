@extends('layouts.staff')

@section('title', 'Expense Report')

@section('content')
<div class="main-content">
    <div class="page-header">
        <h3 class="page-title">
            <i class="fas fa-chart-bar mr-2"></i>
            Expense Report
        </h3>
        <div class="header-actions">
            <a href="{{ route('finance.expenses.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left mr-1"></i> Back to Expenses
            </a>
        </div>
    </div>

    <!-- Date Range Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="filter-form">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group mb-0">
                            <label>From</label>
                            <input type="date" name="date_from" class="form-control" value="{{ $from }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-0">
                            <label>To</label>
                            <input type="date" name="date_to" class="form-control" value="{{ $to }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-0">
                            <label>&nbsp;</label>
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-filter mr-1"></i> Generate Report
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary Stats -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h6 class="card-title">Total Paid</h6>
                    <h3 class="mb-0">{{ number_format($totalExpenses, 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h6 class="card-title">Pending</h6>
                    <h3 class="mb-0">{{ number_format($totalPending, 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h6 class="card-title">Approved</h6>
                    <h3 class="mb-0">{{ number_format($totalApproved, 2) }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- By Category -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">Expenses by Category</h5>
                </div>
                <div class="card-body">
                    @if($byCategory->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Category</th>
                                        <th class="text-right">Count</th>
                                        <th class="text-right">Amount</th>
                                        <th class="text-right">%</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($byCategory as $item)
                                        <tr>
                                            <td>
                                                <span class="badge" style="background-color: {{ $item->category?->color ?? '#6b7280' }}">
                                                    {{ $item->category?->name ?? 'Unknown' }}
                                                </span>
                                            </td>
                                            <td class="text-right">{{ $item->count }}</td>
                                            <td class="text-right">{{ number_format($item->total, 2) }}</td>
                                            <td class="text-right">
                                                {{ $totalExpenses > 0 ? round(($item->total / $totalExpenses) * 100, 1) : 0 }}%
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4 text-muted">
                            No data available.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- By Status -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">Expenses by Status</h5>
                </div>
                <div class="card-body">
                    @if($byStatus->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Status</th>
                                        <th class="text-right">Count</th>
                                        <th class="text-right">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($byStatus as $item)
                                        <tr>
                                            <td>
                                                <span class="badge badge-{{ match($item->status) {
                                                    'pending' => 'warning',
                                                    'approved' => 'info',
                                                    'rejected' => 'danger',
                                                    'paid' => 'success',
                                                    default => 'secondary'
                                                } }}">
                                                    {{ ucfirst($item->status) }}
                                                </span>
                                            </td>
                                            <td class="text-right">{{ $item->count }}</td>
                                            <td class="text-right">{{ number_format($item->total, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4 text-muted">
                            No data available.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Trend -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Monthly Trend</h5>
        </div>
        <div class="card-body">
            @if($monthlyData->count() > 0)
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Month</th>
                                <th class="text-right">Count</th>
                                <th class="text-right">Total Amount</th>
                                <th class="text-right">% of Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($monthlyData as $item)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($item->month . '-01')->format('F Y') }}</td>
                                    <td class="text-right">{{ $item->count }}</td>
                                    <td class="text-right">{{ number_format($item->total, 2) }}</td>
                                    <td class="text-right">
                                        <div class="progress" style="height: 15px; width: 100px; display: inline-block; vertical-align: middle;">
                                            <div class="progress-bar bg-success"
                                                 role="progressbar" style="width: {{ $totalExpenses > 0 ? ($item->total / $totalExpenses) * 100 : 0 }}%">
                                            </div>
                                        </div>
                                        {{ $totalExpenses > 0 ? round(($item->total / $totalExpenses) * 100, 1) : 0 }}%
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-4 text-muted">
                    No monthly data available.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
