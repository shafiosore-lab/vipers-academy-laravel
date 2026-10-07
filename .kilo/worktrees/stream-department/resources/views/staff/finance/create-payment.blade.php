@extends('layouts.staff')

@section('title', 'Record Payment - Vipers Academy')

@section('content')
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="mb-0">Record New Payment</h2>
                            <p class="mb-0">Enter payment details from player</p>
                        </div>
                        <div class="text-end">
                            <a href="{{ route('finance.payments') }}" class="btn btn-outline-light btn-sm">
                                <i class="fas fa-arrow-left"></i> Back to Payments
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('finance.payments.store') }}" method="POST" id="paymentForm">
        @csrf

        <div class="row">
            <!-- Main Form -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Payment Details</h5>
                    </div>
                    <div class="card-body">
                        <!-- Player Selection -->
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="player_id" class="form-label">Player <span class="text-danger">*</span></label>
                                <select name="player_id" id="player_id" class="form-select select2 @error('player_id') is-invalid @enderror" required>
                                    <option value="">Select Player</option>
                                    @foreach($players as $player)
                                        <option value="{{ $player->id }}"
                                            data-category="{{ $player->paymentCategory->name ?? 'None' }}"
                                            data-monthly="{{ $player->paymentCategory->monthly_amount ?? $player->fee_category }}"
                                            {{ old('player_id') == $player->id ? 'selected' : '' }}>
                                            {{ $player->first_name }} {{ $player->last_name }}
                                            ({{ $player->paymentCategory->name ?? 'No Category' }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('player_id')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="payment_type" class="form-label">Payment Type <span class="text-danger">*</span></label>
                                <select name="payment_type" id="payment_type" class="form-select @error('payment_type') is-invalid @enderror" required>
                                    <option value="">Select Type</option>
                                    @foreach($paymentTypes as $key => $label)
                                        <option value="{{ $key }}" {{ old('payment_type') == $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('payment_type')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="amount" class="form-label">Amount (KSh) <span class="text-danger">*</span></label>
                                <input type="number" name="amount" id="amount" class="form-control @error('amount') is-invalid @enderror"
                                    value="{{ old('amount') }}" step="0.01" min="0.01" required>
                                @error('amount')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="payment_method" class="form-label">Payment Method <span class="text-danger">*</span></label>
                                <select name="payment_method" id="payment_method" class="form-select @error('payment_method') is-invalid @enderror" required>
                                    <option value="">Select Method</option>
                                    @foreach($paymentMethods as $method)
                                        <option value="{{ $method }}" {{ old('payment_method') == $method ? 'selected' : '' }}>
                                            {{ ucfirst(str_replace('_', ' ', $method)) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('payment_method')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="payment_reference" class="form-label">Payment Reference</label>
                                <input type="text" name="payment_reference" id="payment_reference" class="form-control @error('payment_reference') is-invalid @enderror"
                                    value="{{ old('payment_reference') }}" placeholder="Auto-generated if left blank">
                                <small class="text-muted">Leave blank to auto-generate</small>
                                @error('payment_reference')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="paid_at" class="form-label">Payment Date <span class="text-danger">*</span></label>
                                <input type="date" name="paid_at" id="paid_at" class="form-control @error('paid_at') is-invalid @enderror"
                                    value="{{ old('paid_at', date('Y-m-d')) }}" required>
                                @error('paid_at')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="due_date" class="form-label">Due Date</label>
                                <input type="date" name="due_date" id="due_date" class="form-control @error('due_date') is-invalid @enderror"
                                    value="{{ old('due_date') }}">
                                @error('due_date')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="payment_category_id" class="form-label">Payment Category</label>
                                <select name="payment_category_id" id="payment_category_id" class="form-select @error('payment_category_id') is-invalid @enderror">
                                    <option value="">Select Category (Auto from Player)</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('payment_category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }} (KSh {{ number_format($category->monthly_amount) }}/month)
                                        </option>
                                    @endforeach
                                </select>
                                @error('payment_category_id')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea name="description" id="description" class="form-control" rows="2">{{ old('description') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea name="notes" id="notes" class="form-control" rows="2">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Quick Amount -->
                <div class="card mb-4">
                    <div class="card-header bg-white">
                        <h6 class="mb-0">Quick Amounts</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-outline-primary quick-amount" data-amount="100">KSh 100</button>
                            <button type="button" class="btn btn-outline-primary quick-amount" data-amount="200">KSh 200</button>
                            <button type="button" class="btn btn-outline-primary quick-amount" data-amount="500">KSh 500</button>
                            <button type="button" class="btn btn-outline-primary quick-amount" data-amount="1000">KSh 1,000</button>
                            <button type="button" class="btn btn-outline-primary quick-amount" data-amount="2000">KSh 2,000</button>
                            <button type="button" class="btn btn-outline-primary quick-amount" data-amount="5000">KSh 5,000</button>
                        </div>
                    </div>
                </div>

                <!-- Payment Tips -->
                <div class="card">
                    <div class="card-header bg-white">
                        <h6 class="mb-0">Recording Tips</h6>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0 small">
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Verify player details before recording</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Ensure payment method is selected</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Double-check the amount</li>
                            <li class="mb-0"><i class="fas fa-check text-success me-2"></i>Keep reference numbers for records</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="row mt-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('finance.payments') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-1"></i> Record Payment
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Quick amount buttons
    $('.quick-amount').click(function() {
        $('#amount').val($(this).data('amount'));
    });

    // Auto-fill category from player selection
    $('#player_id').change(function() {
        var option = $(this).find('option:selected');
        var categoryId = option.val();
        if (categoryId) {
            // Try to match category
            var categoryName = option.data('category');
            $('#payment_category_id option').each(function() {
                if ($(this).text().includes(categoryName)) {
                    $(this).prop('selected', true);
                    return false;
                }
            });
        }
    });
});
</script>
@endpush
