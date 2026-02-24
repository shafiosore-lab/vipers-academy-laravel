@extends('layouts.staff')

@section('title', 'Budget vs Actual Comparison')

@section('content')
<div class="main-content">
    <div class="page-header">
        <h3 class="page-title">
            <i class="fas fa-balance-scale mr-2"></i>
            Budget vs Actual Comparison
        </h3>
        <div class="header-actions">
            <a href="{{ route('finance.budgets.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left mr-1"></i> Back to Budgets
            </a>
        </div>
    </div>

    <!-- Summary Stats -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h6 class="card-title">Total Budgeted</h6>
                    <h3 class="mb-0">{{ number_format($totalBudgeted, 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h6 class="card-title">Total Spent</h6>
                    <h3 class="mb-0">{{ number_format($totalSpent, 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card {{ $totalBalance >= 0 ? 'bg-success' : 'bg-danger' }} text-white">
                <div class="card-body">
                    <h6 class="card-title">{{ $totalBalance >= 0 ? 'Total Balance' : 'Total Excess' }}</h6>
                    <h3 class="mb-0">{{ number_format(abs($totalBalance), 2) }}</h3>
                </div>
            </div>
        </div>
    </div>

    @forelse($comparisonData as $budget)
        <div class="card mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">{{ $budget['name'] }}</h5>
                        <small class="text-muted">{{ $budget['period'] }} ({{ ucfirst($budget['type']) }})</small>
                    </div>
                    <div class="text-right">
                        <span class="badge badge-{{ $budget['spent_percentage'] > 100 ? 'danger' : ($budget['spent_percentage'] > 80 ? 'warning' : 'success') }} mr-2">
                            {{ $budget['spent_percentage'] }}% Used
                        </span>
                        <a href="{{ route('finance.budgets.show', $budget['id']) }}" class="btn btn-sm btn-info">
                            <i class="fas fa-eye"></i> View
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- Progress bar -->
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span>Overall Progress</span>
                        <span>{{ number_format($budget['total_spent'], 2) }} / {{ number_format($budget['total_budget'], 2) }}</span>
                    </div>
                    <div class="progress" style="height: 25px;">
                        <div class="progress-bar {{ $budget['spent_percentage'] > 100 ? 'bg-danger' : ($budget['spent_percentage'] > 80 ? 'bg-warning' : 'bg-success') }}"
                             role="progressbar" style="width: {{ min($budget['spent_percentage'], 100) }}%">
                            {{ $budget['spent_percentage'] }}%
                        </div>
                    </div>
                </div>

                @if(count($budget['items']) > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Category</th>
                                    <th class="text-right">Budgeted</th>
                                    <th class="text-right">Spent</th>
                                    <th class="text-right">Balance</th>
                                    <th class="text-center">Progress</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($budget['items'] as $item)
                                    <tr>
                                        <td>{{ $item['name'] }}</td>
                                        <td>{{ $item['category'] ?? '-' }}</td>
                                        <td class="text-right">{{ number_format($item['budgeted'], 2) }}</td>
                                        <td class="text-right {{ $item['spent'] > $item['budgeted'] ? 'text-danger' : '' }}">
                                            {{ number_format($item['spent'], 2) }}
                                        </td>
                                        <td class="text-right {{ $item['balance'] >= 0 ? 'text-success' : 'text-danger' }}">
                                            {{ number_format($item['balance'], 2) }}
                                        </td>
                                        <td>
                                            <div class="progress" style="height: 15px; width: 100px;">
                                                <div class="progress-bar {{ $item['percentage'] > 100 ? 'bg-danger' : ($item['percentage'] > 80 ? 'bg-warning' : 'bg-success') }}"
                                                     role="progressbar" style="width: {{ min($item['percentage'], 100) }}%">
                                                </div>
                                            </div>
                                            <small class="text-muted">{{ $item['percentage'] }}%</small>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="bg-light">
                                    <th colspan="2">Total</th>
                                    <th class="text-right">{{ number_format($budget['total_budget'], 2) }}</th>
                                    <th class="text-right">{{ number_format($budget['total_spent'], 2) }}</th>
                                    <th class="text-right {{ $budget['total_balance'] >= 0 ? 'text-success' : 'text-danger' }}">
                                        {{ number_format($budget['total_balance'], 2) }}
                                    </th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @else
                    <div class="text-center py-3 text-muted">
                        No budget items defined for this budget.
                    </div>
                @endif
            </div>
        </div>
    @empty
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                <h5>No Budget Plans Found</h5>
                <p class="text-muted">Create a budget plan to start tracking expenses.</p>
                <a href="{{ route('finance.budgets.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus mr-1"></i> Create Budget
                </a>
            </div>
        </div>
    @endforelse
</div>
@endsection
