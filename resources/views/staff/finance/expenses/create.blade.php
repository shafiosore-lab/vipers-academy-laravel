@extends('layouts.staff')

@section('title', 'Add Expense')

@section('content')
<div class="main-content">
    <div class="page-header">
        <h3 class="page-title">
            <i class="fas fa-receipt mr-2"></i>
            Add Expense
        </h3>
    </div>

    <form method="POST" action="{{ route('finance.expenses.store') }}">
        @csrf

        <div class="row">
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Expense Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Title *</label>
                            <input type="text" name="title" class="form-control" required placeholder="e.g., Training balls purchase">
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Category *</label>
                                    <select name="expense_category_id" class="form-control" required>
                                        <option value="">Select Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Budget Plan</label>
                                    <select name="budget_plan_id" class="form-control">
                                        <option value="">No Budget</option>
                                        @foreach($budgets as $budget)
                                            <option value="{{ $budget->id }}" {{ $selectedBudgetId == $budget->id ? 'selected' : '' }}>
                                                {{ $budget->name }} ({{ $budget->getPeriodLabel() }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Amount (KSh) *</label>
                                    <input type="number" name="amount" class="form-control" required min="0.01" step="0.01" placeholder="0.00">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Quantity</label>
                                    <input type="number" name="quantity" class="form-control" min="1" value="1">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Unit Price</label>
                                    <input type="number" name="unit_price" class="form-control" min="0" step="0.01" placeholder="0.00">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Expense Date *</label>
                                    <input type="date" name="expense_date" class="form-control" required value="{{ date('Y-m-d') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Vendor/Supplier</label>
                                    <input type="text" name="vendor" class="form-control" placeholder="e.g., Sports Direct">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" class="form-control" rows="2" placeholder="What was this expense for?"></textarea>
                        </div>

                        <div class="form-group">
                            <label>Notes</label>
                            <textarea name="notes" class="form-control" rows="2" placeholder="Additional notes"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Football Categories</h5>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            @foreach($categories as $category)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ $category->name }}
                                    <span class="badge badge-pill" style="background-color: {{ $category->color }}">&nbsp;</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group mt-4">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="fas fa-save mr-2"></i> Record Expense
            </button>
            <a href="{{ route('finance.expenses.index') }}" class="btn btn-secondary btn-lg ml-2">Cancel</a>
        </div>
    </form>
</div>
@endsection
