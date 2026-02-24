@extends('layouts.staff')

@section('title', 'Edit Budget Plan')

@section('content')
<div class="main-content">
    <div class="page-header">
        <h3 class="page-title">
            <i class="fas fa-edit mr-2"></i>
            Edit Budget Plan
        </h3>
    </div>

    <form method="POST" action="{{ route('finance.budgets.update', $budget->id) }}">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Budget Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Budget Name *</label>
                            <input type="text" name="name" class="form-control" required value="{{ $budget->name }}">
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Type *</label>
                                    <input type="text" class="form-control" value="{{ ucfirst($budget->type) }}" readonly>
                                    <input type="hidden" name="type" value="{{ $budget->type }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Year *</label>
                                    <input type="text" class="form-control" value="{{ $budget->year }}" readonly>
                                    <input type="hidden" name="year" value="{{ $budget->year }}">
                                </div>
                            </div>
                            @if($budget->type === 'monthly')
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Month *</label>
                                    <input type="text" class="form-control" value="{{ \App\Models\BudgetPlan::getAvailableMonths()[$budget->month] }}" readonly>
                                    <input type="hidden" name="month" value="{{ $budget->month }}">
                                </div>
                            </div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label>Total Budget Amount (KSh) *</label>
                            <input type="number" name="total_budget" class="form-control" required min="0" step="0.01" value="{{ $budget->total_budget }}">
                        </div>

                        <div class="form-group">
                            <label>Objectives</label>
                            <textarea name="objectives" class="form-control" rows="3">{{ $budget->objectives }}</textarea>
                        </div>

                        <div class="form-group">
                            <label>Notes</label>
                            <textarea name="notes" class="form-control" rows="2">{{ $budget->notes }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Budget Items -->
                <div class="">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mbcard mb-4-0">Budget Line Items</h5>
                        <button type="button" class="btn btn-sm btn-success" id="addItemBtn">
                            <i class="fas fa-plus mr-1"></i> Add Item
                        </button>
                    </div>
                    <div class="card-body">
                        <div id="budgetItems">
                            @foreach($budget->items as $index => $item)
                            <div class="budget-item-row mb-3 p-3 border rounded">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group mb-0">
                                            <label>Category</label>
                                            <select name="items[{{ $index }}][expense_category_id]" class="form-control">
                                                <option value="">Select Category</option>
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->id }}" {{ $item->expense_category_id == $category->id ? 'selected' : '' }}>
                                                        {{ $category->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-0">
                                            <label>Item Name *</label>
                                            <input type="text" name="items[{{ $index }}][name]" class="form-control" value="{{ $item->name }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group mb-0">
                                            <label>Budgeted Amount *</label>
                                            <input type="number" name="items[{{ $index }}][budgeted_amount]" class="form-control" min="0" step="0.01" value="{{ $item->budgeted_amount }}">
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <label>&nbsp;</label>
                                        <button type="button" class="btn btn-danger btn-block remove-item-btn" {{ $budget->items->count() <= 1 ? 'disabled' : '' }}>
                                            <i class="fas fa-times"></i>
                                        </button>
                                        <input type="hidden" name="items[{{ $index }}][id]" value="{{ $item->id }}">
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-12">
                                        <div class="form-group mb-0">
                                            <label>Description</label>
                                            <input type="text" name="items[{{ $index }}][description]" class="form-control" value="{{ $item->description }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Actions</h5>
                    </div>
                    <div class="card-body">
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-save mr-1"></i> Update Budget
                        </button>
                        <a href="{{ route('finance.budgets.show', $budget->id) }}" class="btn btn-secondary btn-block">
                            Cancel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        let itemCount = {{ $budget->items->count() }};

        $('#addItemBtn').click(function() {
            const html = `
                <div class="budget-item-row mb-3 p-3 border rounded">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group mb-0">
                                <label>Category</label>
                                <select name="items[${itemCount}][expense_category_id]" class="form-control">
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-0">
                                <label>Item Name *</label>
                                <input type="text" name="items[${itemCount}][name]" class="form-control" placeholder="e.g., Training Equipment">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mb-0">
                                <label>Budgeted Amount *</label>
                                <input type="number" name="items[${itemCount}][budgeted_amount]" class="form-control" min="0" step="0.01" placeholder="0.00">
                            </div>
                        </div>
                        <div class="col-md-1">
                            <label>&nbsp;</label>
                            <button type="button" class="btn btn-danger btn-block remove-item-btn">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-12">
                            <div class="form-group mb-0">
                                <label>Description</label>
                                <input type="text" name="items[${itemCount}][description]" class="form-control" placeholder="Optional description">
                            </div>
                        </div>
                    </div>
                </div>
            `;
            $('#budgetItems').append(html);
            itemCount++;
            updateRemoveButtons();
        });

        $(document).on('click', '.remove-item-btn', function() {
            $(this).closest('.budget-item-row').remove();
            updateRemoveButtons();
        });

        function updateRemoveButtons() {
            const count = $('.budget-item-row').length;
            $('.remove-item-btn').prop('disabled', count <= 1);
        }
    });
</script>
@endpush
@endsection
