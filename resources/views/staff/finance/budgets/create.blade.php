@extends('layouts.staff')

@section('title', 'Create Budget Plan')

@section('content')
<div class="main-content">
    <div class="page-header">
        <h3 class="page-title">
            <i class="fas fa-calculator mr-2"></i>
            Create Budget Plan
        </h3>
    </div>

    <form method="POST" action="{{ route('finance.budgets.store') }}">
        @csrf

        <div class="row">
            <!-- Basic Information -->
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Budget Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Budget Name *</label>
                            <input type="text" name="name" class="form-control" required placeholder="e.g., February 2026 Monthly Budget">
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Type *</label>
                                    <select name="type" id="budgetType" class="form-control" required>
                                        <option value="monthly">Monthly</option>
                                        <option value="yearly">Yearly</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Year *</label>
                                    <select name="year" class="form-control" required>
                                        @foreach($years as $year)
                                            <option value="{{ $year }}" {{ $year == date('Y') ? 'selected' : '' }}>{{ $year }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4" id="monthField">
                                <div class="form-group">
                                    <label>Month *</label>
                                    <select name="month" class="form-control">
                                        @foreach($months as $num => $name)
                                            <option value="{{ $num }}" {{ $num == date('n') ? 'selected' : '' }}>{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Total Budget Amount (KSh) *</label>
                            <input type="number" name="total_budget" class="form-control" required min="0" step="0.01" placeholder="0.00">
                        </div>

                        <div class="form-group">
                            <label>Objectives</label>
                            <textarea name="objectives" class="form-control" rows="3" placeholder="Budget objectives and goals"></textarea>
                        </div>

                        <div class="form-group">
                            <label>Notes</label>
                            <textarea name="notes" class="form-control" rows="2" placeholder="Additional notes"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Budget Items -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Budget Line Items</h5>
                        <button type="button" class="btn btn-sm btn-success" id="addItemBtn">
                            <i class="fas fa-plus mr-1"></i> Add Item
                        </button>
                    </div>
                    <div class="card-body">
                        <div id="budgetItems">
                            <div class="budget-item-row mb-3 p-3 border rounded">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group mb-0">
                                            <label>Category</label>
                                            <select name="items[0][expense_category_id]" class="form-control">
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
                                            <input type="text" name="items[0][name]" class="form-control" placeholder="e.g., Training Equipment">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group mb-0">
                                            <label>Budgeted Amount *</label>
                                            <input type="number" name="items[0][budgeted_amount]" class="form-control" min="0" step="0.01" placeholder="0.00">
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <label>&nbsp;</label>
                                        <button type="button" class="btn btn-danger btn-block remove-item-btn" disabled>
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-12">
                                        <div class="form-group mb-0">
                                            <label>Description</label>
                                            <input type="text" name="items[0][description]" class="form-control" placeholder="Optional description">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="noItemsMessage" class="text-center py-3 text-muted" style="display: none;">
                            No budget items added. Click "Add Item" to add line items.
                        </div>
                    </div>
                </div>
            </div>

            <!-- Football Categories Reference -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-futbol mr-2"></i>
                            Football Budget Categories
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Player Wages & Bonuses
                                <span class="badge badge-primary badge-pill">KSh</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Coach & Staff Salaries
                                <span class="badge badge-primary badge-pill">KSh</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Equipment & Gear
                                <span class="badge badge-primary badge-pill">KSh</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Travel & Transportation
                                <span class="badge badge-primary badge-pill">KSh</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Match Day Expenses
                                <span class="badge badge-primary badge-pill">KSh</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Training Facility
                                <span class="badge badge-primary badge-pill">KSh</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Tournament Fees
                                <span class="badge badge-primary badge-pill">KSh</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Medical & Sports Science
                                <span class="badge badge-primary badge-pill">KSh</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Youth Development
                                <span class="badge badge-primary badge-pill">KSh</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Merchandise
                                <span class="badge badge-primary badge-pill">KSh</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group mt-4">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="fas fa-save mr-2"></i> Create Budget Plan
            </button>
            <a href="{{ route('finance.budgets.index') }}" class="btn btn-secondary btn-lg ml-2">Cancel</a>
        </div>
    </form>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        // Toggle month field based on type
        $('#budgetType').change(function() {
            if ($(this).val() === 'yearly') {
                $('#monthField').hide();
                $('select[name="month"]').val('');
            } else {
                $('#monthField').show();
            }
        });

        // Add budget item
        let itemCount = 1;
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

        // Remove budget item
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
