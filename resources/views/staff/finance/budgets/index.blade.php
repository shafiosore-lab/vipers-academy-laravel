@extends('layouts.staff')

@section('title', 'Budget Plans')

@section('content')
<div class="main-content">
    <div class="page-header">
        <h3 class="page-title">
            <i class="fas fa-calculator mr-2"></i>
            Budget Plans
        </h3>
        <div class="header-actions">
            <a href="{{ route('finance.budgets.create') }}" class="btn btn-primary">
                <i class="fas fa-plus mr-1"></i> Create Budget
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="filter-form">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Type</label>
                            <select name="type" class="form-control">
                                <option value="">All Types</option>
                                <option value="monthly" {{ request('type') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                <option value="yearly" {{ request('type') == 'yearly' ? 'selected' : '' }}>Yearly</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Year</label>
                            <select name="year" class="form-control">
                                <option value="">All Years</option>
                                @foreach($years as $year)
                                    <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" class="form-control">
                                <option value="">All Statuses</option>
                                @foreach($statuses as $value => $label)
                                    <option value="{{ $value }}" {{ request('status') == $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <button type="submit" class="btn btn-secondary btn-block">
                                <i class="fas fa-filter mr-1"></i> Filter
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Budget Plans Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Period</th>
                            <th>Budget</th>
                            <th>Spent</th>
                            <th>Balance</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($budgets as $budget)
                            <tr>
                                <td>
                                    <strong>{{ $budget->name }}</strong>
                                </td>
                                <td>
                                    <span class="badge badge-{{ $budget->type === 'monthly' ? 'info' : 'primary' }}">
                                        {{ ucfirst($budget->type) }}
                                    </span>
                                </td>
                                <td>{{ $budget->getPeriodLabel() }}</td>
                                <td>{{ number_format($budget->total_budget, 2) }}</td>
                                <td>
                                    <span class="{{ $budget->total_spent > $budget->total_budget ? 'text-danger' : '' }}">
                                        {{ number_format($budget->total_spent, 2) }}
                                    </span>
                                </td>
                                <td>
                                    @php $balance = $budget->getBalance(); @endphp
                                    <span class="{{ $balance < 0 ? 'text-danger' : 'text-success' }}">
                                        {{ number_format($balance, 2) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-{{ $budget->getStatusBadgeClass() }}">
                                        {{ ucfirst($budget->status) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('finance.budgets.show', $budget->id) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($budget->status === 'draft')
                                        <a href="{{ route('finance.budgets.edit', $budget->id) }}" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <p class="text-muted mb-0">No budget plans found.</p>
                                    <a href="{{ route('finance.budgets.create') }}">Create your first budget plan</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $budgets->links() }}
        </div>
    </div>
</div>
@endsection
