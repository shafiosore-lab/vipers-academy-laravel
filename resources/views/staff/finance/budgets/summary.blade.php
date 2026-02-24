@extends('layouts.staff')

@section('title', 'Budget Summary')

@section('content')
<div class="main-content">
    <div class="page-header">
        <h3 class="page-title">
            <i class="fas fa-chart-pie mr-2"></i>
            Budget Summary Dashboard
        </h3>
        <div class="header-actions">
            <a href="{{ route('finance.budgets.create') }}" class="btn btn-primary">
                <i class="fas fa-plus mr-1"></i> New Budget
            </a>
            <a href="{{ route('finance.budgets.comparison') }}" class="btn btn-info">
                <i class="fas fa-balance-scale mr-1"></i> Comparison
            </a>
        </div>
    </div>

    <!-- Current Period Summary -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card border-primary">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-calendar-alt mr-2"></i>
                        Current Month ({{ \App\Models\BudgetPlan::getAvailableMonths()[$currentMonth] }} {{ $currentYear }})
                    </h5>
                </div>
                <div class="card-body">
                    @if($monthlyBudget)
                        <div class="row text-center">
                            <div class="col-4">
                                <h6 class="text-muted">Budget</h6>
                                <h4>{{ number_format($monthlyBudget->total_budget, 2) }}</h4>
                            </div>
                            <div class="col-4">
                                <h6 class="text-muted">Spent</h6>
                                <h4 class="text-warning">{{ number_format($monthlyBudget->total_spent, 2) }}</h4>
                            </div>
                            <div class="col-4">
                                <h6 class="text-muted">Balance</h6>
                                <h4 class="{{ $monthlyBudget->getBalance() >= 0 ? 'text-success' : 'text-danger' }}">
                                    {{ number_format($monthlyBudget->getBalance(), 2) }}
                                </h4>
                            </div>
                        </div>
                        <div class="mt-3">
                            <div class="progress" style="height: 20px;">
                                <div class="progress-bar {{ $monthlyBudget->getSpentPercentage() > 100 ? 'bg-danger' : ($monthlyBudget->getSpentPercentage() > 80 ? 'bg-warning' : 'bg-success') }}"
                                     role="progressbar" style="width: {{ min($monthlyBudget->getSpentPercentage(), 100) }}%">
                                    {{ $monthlyBudget->getSpentPercentage() }}%
                                </div>
                            </div>
                        </div>
                        <div class="text-center mt-2">
                            <a href="{{ route('finance.budgets.show', $monthlyBudget->id) }}" class="btn btn-sm btn-primary">
                                View Details
                            </a>
                        </div>
                    @else
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-plus-circle fa-2x mb-2"></i>
                            <p class="mb-0">No active monthly budget for this month.</p>
                            <a href="{{ route('finance.budgets.create') }}">Create Monthly Budget</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-info">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-calendar mr-2"></i>
                        Year {{ $currentYear }}
                    </h5>
                </div>
                <div class="card-body">
                    @if($yearlyBudget)
                        <div class="row text-center">
                            <div class="col-4">
                                <h6 class="text-muted">Budget</h6>
                                <h4>{{ number_format($yearlyBudget->total_budget, 2) }}</h4>
                            </div>
                            <div class="col-4">
                                <h6 class="text-muted">Spent</h6>
                                <h4 class="text-warning">{{ number_format($yearlyBudget->total_spent, 2) }}</h4>
                            </div>
                            <div class="col-4">
                                <h6 class="text-muted">Balance</h6>
                                <h4 class="{{ $yearlyBudget->getBalance() >= 0 ? 'text-success' : 'text-danger' }}">
                                    {{ number_format($yearlyBudget->getBalance(), 2) }}
                                </h4>
                            </div>
                        </div>
                        <div class="mt-3">
                            <div class="progress" style="height: 20px;">
                                <div class="progress-bar {{ $yearlyBudget->getSpentPercentage() > 100 ? 'bg-danger' : ($yearlyBudget->getSpentPercentage() > 80 ? 'bg-warning' : 'bg-success') }}"
                                     role="progressbar" style="width: {{ min($yearlyBudget->getSpentPercentage(), 100) }}%">
                                    {{ $yearlyBudget->getSpentPercentage() }}%
                                </div>
                            </div>
                        </div>
                        <div class="text-center mt-2">
                            <a href="{{ route('finance.budgets.show', $yearlyBudget->id) }}" class="btn btn-sm btn-info">
                                View Details
                            </a>
                        </div>
                    @else
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-plus-circle fa-2x mb-2"></i>
                            <p class="mb-0">No active yearly budget for this year.</p>
                            <a href="{{ route('finance.budgets.create') }}">Create Yearly Budget</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Year-to-Date Overview</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <h6 class="text-muted">YTD Budget</h6>
                            <h3>{{ number_format($ytdBudget, 2) }}</h3>
                        </div>
                        <div class="col-6">
                            <h6 class="text-muted">YTD Expenses</h6>
                            <h3>{{ number_format($ytdExpenses, 2) }}</h3>
                        </div>
                    </div>
                    @if($ytdBudget > 0)
                    <div class="mt-3">
                        <div class="progress" style="height: 20px;">
                            <div class="progress-bar bg-info"
                                 role="progressbar" style="width: {{ min(($ytdExpenses / $ytdBudget) * 100, 100) }}%">
                                {{ round(($ytdExpenses / $ytdBudget) * 100, 1) }}%
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <a href="{{ route('finance.expenses.create') }}" class="btn btn-primary btn-block mb-2">
                                <i class="fas fa-receipt mr-1"></i> Add Expense
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('finance.expenses.index') }}" class="btn btn-secondary btn-block mb-2">
                                <i class="fas fa-list mr-1"></i> All Expenses
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Expenses -->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Recent Expenses</h5>
                </div>
                <div class="card-body p-0">
                    @if($recentExpenses->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm mb-0">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Title</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentExpenses as $expense)
                                        <tr>
                                            <td>{{ $expense->expense_date->format('d M') }}</td>
                                            <td>
                                                <a href="{{ route('finance.expenses.show', $expense->id) }}">
                                                    {{ $expense->title }}
                                                </a>
                                            </td>
                                            <td>{{ number_format($expense->amount, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4 text-muted">
                            No recent expenses.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Pending Approvals</h5>
                </div>
                <div class="card-body p-0">
                    @if($pendingExpenses->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm mb-0">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Amount</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pendingExpenses as $expense)
                                        <tr>
                                            <td>{{ $expense->title }}</td>
                                            <td>{{ number_format($expense->amount, 2) }}</td>
                                            <td>
                                                <a href="{{ route('finance.expenses.show', $expense->id) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4 text-muted">
                            No pending approvals.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
