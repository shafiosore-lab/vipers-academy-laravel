@extends('layouts.staff')

@section('title', $budget->name)

@section('content')
<div class="main-content">
    <div class="page-header">
        <h3 class="page-title">
            <i class="fas fa-calculator mr-2"></i>
            {{ $budget->name }}
        </h3>
        <div class="header-actions">
            @if($budget->status === 'draft')
                <form method="POST" action="{{ route('finance.budgets.activate', $budget->id) }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-play mr-1"></i> Activate Budget
                    </button>
                </form>
            @elseif($budget->status === 'active')
                <form method="POST" action="{{ route('finance.budgets.close', $budget->id) }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-info">
                        <i class="fas fa-check-circle mr-1"></i> Close Budget
                    </button>
                </form>
            @endif
            <a href="{{ route('finance.budgets.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left mr-1"></i> Back
            </a>
        </div>
    </div>

    <!-- Budget Summary -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h6 class="card-title">Total Budget</h6>
                    <h3 class="mb-0">{{ number_format($budget->total_budget, 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h6 class="card-title">Total Spent</h6>
                    <h3 class="mb-0">{{ number_format($totalSpent, 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card {{ $budget->getBalance() >= 0 ? 'bg-success' : 'bg-danger' }} text-white">
                <div class="card-body">
                    <h6 class="card-title">{{ $budget->getBalance() >= 0 ? 'Balance' : 'Excess' }}</h6>
                    <h3 class="mb-0">{{ number_format(abs($budget->getBalance()), 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h6 class="card-title">% Spent</h6>
                    <h3 class="mb-0">{{ $budget->getSpentPercentage() }}%</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Budget Details -->
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Budget Line Items</h5>
                </div>
                <div class="card-body">
                    @if($budget->items->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>Category</th>
                                        <th>Budgeted</th>
                                        <th>Spent</th>
                                        <th>Balance</th>
                                        <th>%</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($budget->items as $item)
                                        @php
                                            $itemSpent = $item->expenses->sum('amount');
                                            $balance = $item->budgeted_amount - $itemSpent;
                                        @endphp
                                        <tr>
                                            <td>
                                                <strong>{{ $item->name }}</strong>
                                                @if($item->description)
                                                    <br><small class="text-muted">{{ $item->description }}</small>
                                                @endif
                                            </td>
                                            <td>{{ $item->category?->name ?? '-' }}</td>
                                            <td>{{ number_format($item->budgeted_amount, 2) }}</td>
                                            <td class="{{ $itemSpent > $item->budgeted_amount ? 'text-danger' : '' }}">
                                                {{ number_format($itemSpent, 2) }}
                                            </td>
                                            <td class="{{ $balance >= 0 ? 'text-success' : 'text-danger' }}">
                                                {{ number_format($balance, 2) }}
                                            </td>
                                            <td>
                                                @php $percent = $item->budgeted_amount > 0 ? round(($itemSpent / $item->budgeted_amount) * 100, 1) : 0; @endphp
                                                <div class="progress" style="height: 20px;">
                                                    <div class="progress-bar {{ $percent > 100 ? 'bg-danger' : ($percent > 80 ? 'bg-warning' : 'bg-success') }}"
                                                         role="progressbar" style="width: {{ min($percent, 100) }}%">
                                                        {{ $percent }}%
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="bg-light">
                                        <th colspan="2">Total</th>
                                        <th>{{ number_format($budget->items->sum('budgeted_amount'), 2) }}</th>
                                        <th>{{ number_format($totalSpent, 2) }}</th>
                                        <th class="{{ $budget->getBalance() >= 0 ? 'text-success' : 'text-danger' }}">
                                            {{ number_format($budget->getBalance(), 2) }}
                                        </th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4 text-muted">
                            No budget items defined.
                        </div>
                    @endif
                </div>
            </div>

            <!-- Expenses for this budget -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Expenses in this Budget</h5>
                </div>
                <div class="card-body">
                    @if($expenses->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Title</th>
                                        <th>Category</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($expenses as $expense)
                                        <tr>
                                            <td>{{ $expense->expense_date->format('d M Y') }}</td>
                                            <td>
                                                <a href="{{ route('finance.expenses.show', $expense->id) }}">
                                                    {{ $expense->title }}
                                                </a>
                                            </td>
                                            <td>{{ $expense->category?->name }}</td>
                                            <td>{{ number_format($expense->amount, 2) }}</td>
                                            <td>
                                                <span class="badge badge-{{ $expense->getStatusBadgeClass() }}">
                                                    {{ ucfirst($expense->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4 text-muted">
                            No expenses recorded for this budget.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Budget Information</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <td><strong>Type:</strong></td>
                            <td>{{ ucfirst($budget->type) }}</td>
                        </tr>
                        <tr>
                            <td><strong>Period:</strong></td>
                            <td>{{ $budget->getPeriodLabel() }}</td>
                        </tr>
                        <tr>
                            <td><strong>Status:</strong></td>
                            <td>
                                <span class="badge badge-{{ $budget->getStatusBadgeClass() }}">
                                    {{ ucfirst($budget->status) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Created:</strong></td>
                            <td>{{ $budget->created_at->format('d M Y') }}</td>
                        </tr>
                        @if($budget->objectives)
                            <tr>
                                <td><strong>Objectives:</strong></td>
                                <td>{{ $budget->objectives }}</td>
                            </tr>
                        @endif
                        @if($budget->notes)
                            <tr>
                                <td><strong>Notes:</strong></td>
                                <td>{{ $budget->notes }}</td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <a href="{{ route('finance.expenses.create') }}?budget_id={{ $budget->id }}" class="btn btn-primary btn-block mb-2">
                        <i class="fas fa-plus mr-1"></i> Add Expense
                    </a>
                    <a href="{{ route('finance.budgets.comparison') }}" class="btn btn-secondary btn-block">
                        <i class="fas fa-balance-scale mr-1"></i> View Comparison
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
