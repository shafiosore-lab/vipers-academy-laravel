@extends('layouts.admin')

@section('title', 'Budget Plans')

@section('content')
<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Budget Plans</h4>
        <a href="{{ route('finance.budgets.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Create Budget
        </a>
    </div>

    <!-- Filters -->
    <div class="mb-3">
        <form method="GET" action="{{ route('finance.budgets.index') }}" class="row g-2">
            <div class="col-auto">
                <select name="type" class="form-select form-select-sm">
                    <option value="">All Types</option>
                    <option value="monthly" {{ request('type') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                    <option value="yearly" {{ request('type') == 'yearly' ? 'selected' : '' }}>Yearly</option>
                </select>
            </div>
            <div class="col-auto">
                <select name="year" class="form-select form-select-sm">
                    <option value="">All Years</option>
                    @foreach($years as $year)
                        <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto">
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Statuses</option>
                    @foreach($statuses as $value => $label)
                        <option value="{{ $value }}" {{ request('status') == $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="fas fa-filter"></i>
                </button>
                <a href="{{ route('finance.budgets.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-redo"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Budget Plans Table -->
    <div class="card">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Budget Plans</h5>
            <span class="badge bg-primary">{{ $budgets->total() }} records</span>
        </div>
        <div class="card-body">
            @if($budgets->count() > 0)
                <div class="table-responsive">
                    <table class="table table-sm table-bordered" width="100%" cellspacing="0">
                        <thead class="table-light">
                            <tr class="small">
                                <th class="py-2">Name</th>
                                <th class="py-2">Type</th>
                                <th class="py-2">Period</th>
                                <th class="py-2">Budget</th>
                                <th class="py-2">Spent</th>
                                <th class="py-2">Balance</th>
                                <th class="py-2">Status</th>
                                <th class="py-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($budgets as $budget)
                                <tr>
                                    <td class="py-1 align-middle">
                                        <strong>{{ $budget->name }}</strong>
                                    </td>
                                    <td class="py-1 align-middle">
                                        <span class="badge bg-{{ $budget->type === 'monthly' ? 'info' : 'primary' }}">
                                            {{ ucfirst($budget->type) }}
                                        </span>
                                    </td>
                                    <td class="py-1 align-middle small">{{ $budget->getPeriodLabel() }}</td>
                                    <td class="py-1 align-middle text-end small">{{ number_format($budget->total_budget, 2) }}</td>
                                    <td class="py-1 align-middle text-end small {{ $budget->total_spent > $budget->total_budget ? 'text-danger' : '' }}">
                                        {{ number_format($budget->total_spent, 2) }}
                                    </td>
                                    <td class="py-1 align-middle text-end small">
                                        @php $balance = $budget->getBalance(); @endphp
                                        <span class="{{ $balance < 0 ? 'text-danger' : 'text-success' }}">
                                            {{ number_format($balance, 2) }}
                                        </span>
                                    </td>
                                    <td class="py-1 align-middle">
                                        <span class="badge bg-{{ $budget->getStatusBadgeClass() }}">
                                            {{ ucfirst($budget->status) }}
                                        </span>
                                    </td>
                                    <td class="py-1 align-middle">
                                        <div class="d-flex gap-1">
                                            <a href="{{ route('finance.budgets.show', $budget->id) }}" class="btn btn-sm btn-outline-info py-0 px-1" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if($budget->status === 'draft')
                                                <a href="{{ route('finance.budgets.edit', $budget->id) }}" class="btn btn-sm btn-outline-warning py-0 px-1" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $budgets->links() }}
            @else
                <div class="text-center py-4">
                    <p class="text-muted mb-2">No budget plans found.</p>
                    <a href="{{ route('finance.budgets.create') }}">Create your first budget plan</a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

