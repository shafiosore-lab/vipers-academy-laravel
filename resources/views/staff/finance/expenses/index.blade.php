@extends('layouts.staff')

@section('title', 'Expenses')

@section('content')
<div class="main-content">
    <div class="page-header">
        <h3 class="page-title">
            <i class="fas fa-receipt mr-2"></i>
            Expenses
        </h3>
        <div class="header-actions">
            <a href="{{ route('finance.expenses.create') }}" class="btn btn-primary">
                <i class="fas fa-plus mr-1"></i> Add Expense
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="filter-form">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Search</label>
                            <input type="text" name="search" class="form-control" placeholder="Title, reference..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" class="form-control">
                                <option value="">All</option>
                                @foreach($statuses as $value => $label)
                                    <option value="{{ $value }}" {{ request('status') == $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Category</label>
                            <select name="category_id" class="form-control">
                                <option value="">All</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Budget</label>
                            <select name="budget_id" class="form-control">
                                <option value="">All</option>
                                @foreach($budgets as $budget)
                                    <option value="{{ $budget->id }}" {{ request('budget_id') == $budget->id ? 'selected' : '' }}>{{ $budget->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>From</label>
                            <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>To</label>
                            <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-secondary">
                            <i class="fas fa-filter mr-1"></i> Filter
                        </button>
                        <a href="{{ route('finance.expenses.index') }}" class="btn btn-link">Clear</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Expenses Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Reference</th>
                            <th>Date</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Budget</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($expenses as $expense)
                            <tr>
                                <td>
                                    <small class="text-muted">{{ $expense->reference }}</small>
                                </td>
                                <td>{{ $expense->expense_date->format('d M Y') }}</td>
                                <td>
                                    <strong>{{ $expense->title }}</strong>
                                    @if($expense->vendor)
                                        <br><small class="text-muted">{{ $expense->vendor }}</small>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge" style="background-color: {{ $expense->category?->color ?? '#6b7280' }}">
                                        {{ $expense->category?->name ?? '-' }}
                                    </span>
                                </td>
                                <td>
                                    @if($expense->budgetPlan)
                                        <a href="{{ route('finance.budgets.show', $expense->budgetPlan->id) }}">
                                            {{ $expense->budgetPlan->name }}
                                        </a>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ number_format($expense->amount, 2) }}</td>
                                <td>
                                    <span class="badge badge-{{ $expense->getStatusBadgeClass() }}">
                                        {{ ucfirst($expense->status) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('finance.expenses.show', $expense->id) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($expense->status === 'pending')
                                        <a href="{{ route('finance.expenses.edit', $expense->id) }}" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <p class="text-muted mb-0">No expenses found.</p>
                                    <a href="{{ route('finance.expenses.create') }}">Record your first expense</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $expenses->links() }}
        </div>
    </div>
</div>
@endsection
