@extends('layouts.admin')

@section('title', 'Expenses')

@section('content')
<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h5 class="mb-0"><i class="fas fa-receipt me-2"></i>Expenses</h5>
        <a href="{{ route('finance.expenses.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-1"></i> Add
        </a>
    </div>

    <!-- Compact Filters -->
    <div class="card mb-2">
        <div class="card-body py-2">
            <form method="GET" class="filter-form">
                <div class="row g-1">
                    <div class="col-md-2">
                        <input type="text" name="search" class="form-control form-control-sm" placeholder="Title, reference..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <select name="status" class="form-select form-select-sm">
                            <option value="">All Status</option>
                            @foreach($statuses as $value => $label)
                                <option value="{{ $value }}" {{ request('status') == $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="category_id" class="form-select form-select-sm">
                            <option value="">All Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="budget_id" class="form-select form-select-sm">
                            <option value="">All Budget</option>
                            @foreach($budgets as $budget)
                                <option value="{{ $budget->id }}" {{ request('budget_id') == $budget->id ? 'selected' : '' }}>{{ $budget->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-2">
                        <input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}">
                    </div>
                </div>
                <div class="row mt-1">
                    <div class="col-auto">
                        <button type="submit" class="btn btn-secondary btn-sm">
                            <i class="fas fa-filter"></i>
                        </button>
                        <a href="{{ route('finance.expenses.index') }}" class="btn btn-link btn-sm p-1">Clear</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Compact Table -->
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead class="table-light">
                        <tr class="small">
                            <th class="py-1">Reference</th>
                            <th class="py-1">Date</th>
                            <th class="py-1">Title</th>
                            <th class="py-1">Category</th>
                            <th class="py-1">Budget</th>
                            <th class="py-1">Amount</th>
                            <th class="py-1">Status</th>
                            <th class="py-1">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($expenses as $expense)
                            <tr>
                                <td class="py-1"><small class="text-muted">{{ $expense->reference }}</small></td>
                                <td class="py-1">{{ $expense->expense_date->format('d M Y') }}</td>
                                <td class="py-1">
                                    <strong>{{ $expense->title }}</strong>
                                    @if($expense->vendor)<br><small class="text-muted">{{ $expense->vendor }}</small>@endif
                                </td>
                                <td class="py-1">
                                    <span class="badge badge-sm" style="background-color: {{ $expense->category?->color ?? '#6b7280' }}">
                                        {{ $expense->category?->name ?? '-' }}
                                    </span>
                                </td>
                                <td class="py-1">
                                    @if($expense->budgetPlan)
                                        <a href="{{ route('finance.budgets.show', $expense->budgetPlan->id) }}">{{ $expense->budgetPlan->name }}</a>
                                    @else-
                                    @endif
                                </td>
                                <td class="py-1">KSh {{ number_format($expense->amount, 2) }}</td>
                                <td class="py-1">
                                    <span class="badge bg-{{ $expense->getStatusBadgeClass() }}">{{ ucfirst($expense->status) }}</span>
                                </td>
                                <td class="py-1">
                                    <a href="{{ route('finance.expenses.show', $expense->id) }}" class="btn btn-xs btn-info py-0 px-1"><i class="fas fa-eye"></i></a>
                                    @if($expense->status === 'pending')
                                        <a href="{{ route('finance.expenses.edit', $expense->id) }}" class="btn btn-xs btn-warning py-0 px-1"><i class="fas fa-edit"></i></a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-2">
                                    <p class="text-muted mb-0 small">No expenses found.</p>
                                    <a href="{{ route('finance.expenses.create') }}" class="small">Record your first expense</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($expenses->hasPages())
            <div class="card-footer py-1">
                {{ $expenses->links('pagination::bootstrap-4') }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

