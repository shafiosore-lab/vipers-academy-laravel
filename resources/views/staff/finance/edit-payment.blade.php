@extends('layouts.staff')

@section('title', 'Edit Payment - Vipers Academy')

@section('content')
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="mb-0">Edit Payment</h2>
                            <p class="mb-0">Reference: {{ $payment->payment_reference }}</p>
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

    @if($payment->needs_approval)
    <div class="alert alert-warning">
        <i class="fas fa-exclamation-triangle me-2"></i>
        This payment has pending changes awaiting admin approval.
    </div>
    @endif

    <form action="{{ route('finance.payments.update', $payment->id) }}" method="POST" id="paymentForm">
        @csrf
        @method('PUT')

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
                                <select name="player_id" id="player_id" class="form-select @error('player_id') is-invalid @enderror" required>
                                    <option value="">Select Player</option>
                                    @foreach($players as $player)
                                        <option value="{{ $player->id }}"
                                            {{ $payment->player_id == $player->id ? 'selected' : '' }}>
                                            {{ $player->first_name }} {{ $player->last_name }}
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
                                        <option value="{{ $key }}" {{ $payment->payment_type == $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="amount" class="form-label">Amount (KSh) <span class="text-danger">*</span></label>
                                <input type="number" name="amount" id="amount" class="form-control @error('amount') is-invalid @enderror"
                                    value="{{ old('amount', $payment->amount) }}" step="0.01" min="0.01" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="payment_method" class="form-label">Payment Method <span class="text-danger">*</span></label>
                                <select name="payment_method" id="payment_method" class="form-select" required>
                                    @foreach($paymentMethods as $method)
                                        <option value="{{ $method }}" {{ $payment->payment_method == $method ? 'selected' : '' }}>
                                            {{ ucfirst(str_replace('_', ' ', $method)) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="payment_reference" class="form-label">Payment Reference</label>
                                <input type="text" name="payment_reference" id="payment_reference" class="form-control"
                                    value="{{ $payment->payment_reference }}" readonly>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="paid_at" class="form-label">Payment Date <span class="text-danger">*</span></label>
                                <input type="date" name="paid_at" id="paid_at" class="form-control"
                                    value="{{ $payment->paid_at ? $payment->paid_at->format('Y-m-d') : '' }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="due_date" class="form-label">Due Date</label>
                                <input type="date" name="due_date" id="due_date" class="form-control"
                                    value="{{ $payment->due_date ? $payment->due_date->format('Y-m-d') : '' }}">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="payment_status" class="form-label">Payment Status <span class="text-danger">*</span></label>
                                <select name="payment_status" id="payment_status" class="form-select" required>
                                    <option value="pending" {{ $payment->payment_status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="completed" {{ $payment->payment_status == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="failed" {{ $payment->payment_status == 'failed' ? 'selected' : '' }}>Failed</option>
                                    <option value="cancelled" {{ $payment->payment_status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="payment_category_id" class="form-label">Payment Category</label>
                                <select name="payment_category_id" id="payment_category_id" class="form-select">
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ $payment->payment_category_id == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea name="description" id="description" class="form-control" rows="2">{{ $payment->description }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea name="notes" id="notes" class="form-control" rows="2">{{ $payment->notes }}</textarea>
                        </div>

                        <!-- Approval Reason for Significant Changes -->
                        <div class="alert alert-info">
                            <label for="approval_reason" class="form-label">
                                <i class="fas fa-info-circle me-1"></i> Reason for changes
                            </label>
                            <input type="text" name="approval_reason" id="approval_reason" class="form-control"
                                placeholder="Required when changing amount or status">
                            <small class="text-muted">Changes to amount or payment status will require admin approval.</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Payment Info -->
                <div class="card mb-4">
                    <div class="card-header bg-white">
                        <h6 class="mb-0">Current Status</h6>
                    </div>
                    <div class="card-body">
                        <dl class="mb-0">
                            <dt>Status</dt>
                            <dd><span class="badge bg-{{ $payment->getStatusBadgeClass() }}">{{ $payment->payment_status }}</span></dd>
                            <dt class="mt-2">Created</dt>
                            <dd>{{ $payment->created_at->format('M j, Y H:i') }}</dd>
                            @if($payment->created_by)
                            <dt class="mt-2">By</dt>
                            <dd>{{ $payment->createdBy->name ?? 'Unknown' }}</dd>
                            @endif
                        </dl>
                    </div>
                </div>

                <!-- Delete Option -->
                <div class="card border-danger">
                    <div class="card-header bg-danger text-white">
                        <h6 class="mb-0">Delete Payment</h6>
                    </div>
                    <div class="card-body">
                        <p class="small text-muted">Deleting a payment requires admin approval.</p>
                        <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="fas fa-trash me-1"></i> Request Deletion
                        </button>
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
                                <i class="fas fa-save me-1"></i> Save Changes
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Request Payment Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('finance.payments.delete', $payment->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body">
                        <p>You are requesting to delete payment: <strong>{{ $payment->payment_reference }}</strong></p>
                        <p class="text-muted">This action requires admin approval.</p>
                        <div class="mb-3">
                            <label for="delete_reason" class="form-label">Reason for deletion <span class="text-danger">*</span></label>
                            <textarea name="delete_reason" id="delete_reason" class="form-control" rows="3" required minlength="10"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Submit for Approval</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
