@extends('layouts.staff')

@section('title', $expense->title)

@section('content')
<div class="main-content">
    <div class="page-header">
        <h3 class="page-title">
            <i class="fas fa-receipt mr-2"></i>
            Expense Details
        </h3>
        <div class="header-actions">
            <a href="{{ route('finance.expenses.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left mr-1"></i> Back
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Expense Information</h5>
                        <span class="badge badge-{{ $expense->getStatusBadgeClass() }} badge-lg">
                            {{ ucfirst($expense->status) }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="30%">Reference:</th>
                            <td>{{ $expense->reference }}</td>
                        </tr>
                        <tr>
                            <th>Title:</th>
                            <td><strong>{{ $expense->title }}</strong></td>
                        </tr>
                        <tr>
                            <th>Category:</th>
                            <td>
                                <span class="badge" style="background-color: {{ $expense->category?->color ?? '#6b7280' }}">
                                    {{ $expense->category?->name ?? '-' }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Amount:</th>
                            <td><h4 class="mb-0">{{ number_format($expense->amount, 2) }}</h4></td>
                        </tr>
                        <tr>
                            <th>Quantity:</th>
                            <td>{{ $expense->quantity }}</td>
                        </tr>
                        @if($expense->unit_price)
                        <tr>
                            <th>Unit Price:</th>
                            <td>{{ number_format($expense->unit_price, 2) }}</td>
                        </tr>
                        @endif
                        <tr>
                            <th>Total:</th>
                            <td><strong>{{ number_format($expense->getTotalAmount(), 2) }}</strong></td>
                        </tr>
                        <tr>
                            <th>Expense Date:</th>
                            <td>{{ $expense->expense_date->format('d M Y') }}</td>
                        </tr>
                        @if($expense->vendor)
                        <tr>
                            <th>Vendor:</th>
                            <td>{{ $expense->vendor }}</td>
                        </tr>
                        @endif
                        @if($expense->receipt_number)
                        <tr>
                            <th>Receipt #:</th>
                            <td>{{ $expense->receipt_number }}</td>
                        </tr>
                        @endif
                        @if($expense->budgetPlan)
                        <tr>
                            <th>Budget Plan:</th>
                            <td>
                                <a href="{{ route('finance.budgets.show', $expense->budgetPlan->id) }}">
                                    {{ $expense->budgetPlan->name }}
                                </a>
                            </td>
                        </tr>
                        @endif
                        @if($expense->description)
                        <tr>
                            <th>Description:</th>
                            <td>{{ $expense->description }}</td>
                        </tr>
                        @endif
                        @if($expense->notes)
                        <tr>
                            <th>Notes:</th>
                            <td>{{ $expense->notes }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>

            <!-- Actions for pending expenses -->
            @if($expense->status === 'pending')
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <form method="POST" action="{{ route('finance.expenses.approve', $expense->id) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-block">
                                        <i class="fas fa-check mr-1"></i> Approve
                                    </button>
                                </form>
                            </div>
                            <div class="col-md-4">
                                <button type="button" class="btn btn-danger btn-block" data-toggle="modal" data-target="#rejectModal">
                                    <i class="fas fa-times mr-1"></i> Reject
                                </button>
                            </div>
                            <div class="col-md-4">
                                <form method="POST" action="{{ route('finance.expenses.mark-paid', $expense->id) }}">
                                    @csrf
                                    <div class="input-group">
                                        <select name="payment_method" class="form-control" required>
                                            <option value="">Payment Method</option>
                                            <option value="cash">Cash</option>
                                            <option value="mpesa">M-Pesa</option>
                                            <option value="bank_transfer">Bank Transfer</option>
                                            <option value="cheque">Cheque</option>
                                            <option value="card">Card</option>
                                        </select>
                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-primary">
                                                Mark Paid
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Mark as paid for approved expenses -->
            @if($expense->status === 'approved')
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Mark as Paid</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('finance.expenses.mark-paid', $expense->id) }}" class="form-inline">
                            @csrf
                            <div class="form-group mr-3">
                                <select name="payment_method" class="form-control" required>
                                    <option value="">Payment Method</option>
                                    <option value="cash">Cash</option>
                                    <option value="mpesa">M-Pesa</option>
                                    <option value="bank_transfer">Bank Transfer</option>
                                    <option value="cheque">Cheque</option>
                                    <option value="card">Card</option>
                                </select>
                            </div>
                            <div class="form-group mr-3">
                                <input type="date" name="payment_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-check mr-1"></i> Mark as Paid
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Details</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <td><strong>Created:</strong></td>
                            <td>{{ $expense->created_at->format('d M Y h:i A') }}</td>
                        </tr>
                        @if($expense->createdBy)
                        <tr>
                            <td><strong>Created By:</strong></td>
                            <td>{{ $expense->createdBy->name }}</td>
                        </tr>
                        @endif
                        @if($expense->approvedBy)
                        <tr>
                            <td><strong>Approved By:</strong></td>
                            <td>{{ $expense->approvedBy->name }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Rejection Modal -->
@if($expense->status === 'pending')
<div class="modal fade" id="rejectModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form method="POST" action="{{ route('finance.expenses.reject', $expense->id) }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Reject Expense</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Rejection Reason *</label>
                        <textarea name="rejection_reason" class="form-control" rows="3" required placeholder="Please provide a reason for rejection"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject Expense</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endif
@endsection
