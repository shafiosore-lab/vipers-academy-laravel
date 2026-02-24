@extends('layouts.staff')

@section('title', 'Edit Expense')

@section('content')
<div class="main-content">
    <div class="page-header">
        <h3 class="page-title">
            <i class="fas fa-edit mr-2"></i>
            Edit Expense
        </h3>
    </div>

    <form method="POST" action="{{ route('finance.expenses.update', $expense->id) }}">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Expense Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Title *</label>
                            <input type="text" name="title" class="form-control" required value="{{ $expense->title }}">
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Category *</label>
                                    <select name="expense_category_id" class="form-control" required>
                                        <option value="">Select Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ $expense->expense_category_id == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
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
                                            <option value="{{ $budget->id }}" {{ $expense->budget_plan_id == $budget->id ? 'selected' : '' }}>
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
                                    <input type="number" name="amount" class="form-control" required min="0.01" step="0.01" value="{{ $expense->amount }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Quantity</label>
                                    <input type="number" name="quantity" class="form-control" min="1" value="{{ $expense->quantity }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Unit Price</label>
                                    <input type="number" name="unit_price" class="form-control" min="0" step="0.01" value="{{ $expense->unit_price }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Expense Date *</label>
                                    <input type="date" name="expense_date" class="form-control" required value="{{ $expense->expense_date->format('Y-m-d') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Vendor/Supplier</label>
                                    <input type="text" name="vendor" class="form-control" value="{{ $expense->vendor }}">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" class="form-control" rows="2">{{ $expense->description }}</textarea>
                        </div>

                        <div class="form-group">
                            <label>Notes</label>
                            <textarea name="notes" class="form-control" rows="2">{{ $expense->notes }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group mt-4">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="fas fa-save mr-2"></i> Update Expense
            </button>
            <a href="{{ route('finance.expenses.show', $expense->id) }}" class="btn btn-secondary btn-lg ml-2">Cancel</a>
        </div>
    </form>
</div>
@endsection
