@extends('layouts.admin')

@section('title', 'Subscription Management')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h2 mb-0 text-gray-900">Subscription Management</h1>
            <p class="mb-0 text-muted">Manage all organization subscriptions and billing</p>
        </div>
    </div>

    <!-- Filters and Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body">
                    <form action="{{ route('super-admin.subscriptions.index') }}" method="GET" id="filterForm">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="search">Search Organization</label>
                                    <input type="text" class="form-control" id="search" name="search"
                                           value="{{ request('search') }}" placeholder="Search organizations...">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select class="form-control" id="status" name="status">
                                        <option value="">All Statuses</option>
                                        @foreach($statuses as $status)
                                            <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                                {{ ucfirst($status) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="plan">Plan</label>
                                    <select class="form-control" id="plan" name="plan">
                                        <option value="">All Plans</option>
                                        @foreach($plans as $plan)
                                            <option value="{{ $plan->id }}" {{ request('plan') == $plan->id ? 'selected' : '' }}>
                                                {{ $plan->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="billing_cycle">Billing Cycle</label>
                                    <select class="form-control" id="billing_cycle" name="billing_cycle">
                                        <option value="">All Cycles</option>
                                        @foreach($billingCycles as $cycle)
                                            <option value="{{ $cycle }}" {{ request('billing_cycle') == $cycle ? 'selected' : '' }}>
                                                {{ ucfirst($cycle) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="date_from">Start Date From</label>
                                    <input type="date" class="form-control" id="date_from" name="date_from"
                                           value="{{ request('date_from') }}">
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <button type="submit" class="btn btn-primary btn-block">Filter</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions Bar -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <a href="{{ route('super-admin.subscriptions.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add Subscription
                    </a>
                    <a href="{{ route('super-admin.subscriptions.analytics') }}" class="btn btn-info ml-2">
                        <i class="fas fa-chart-bar"></i> Analytics
                    </a>
                </div>
                <div class="text-muted">
                    Showing {{ $subscriptions->firstItem() }} to {{ $subscriptions->lastItem() }} of {{ $subscriptions->total() }} subscriptions
                </div>
            </div>
        </div>
    </div>

    <!-- Subscriptions Table -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="subscriptionsTable">
                            <thead>
                                <tr>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'organization.name', 'direction' => request('direction') === 'asc' && request('sort') === 'organization.name' ? 'desc' : 'asc']) }}"
                                           class="text-decoration-none">
                                            Organization
                                            @if(request('sort') === 'organization.name')
                                                <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }}"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'plan.name', 'direction' => request('direction') === 'asc' && request('sort') === 'plan.name' ? 'desc' : 'asc']) }}"
                                           class="text-decoration-none">
                                            Plan
                                            @if(request('sort') === 'plan.name')
                                                <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }}"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th>Amount</th>
                                    <th>Billing Cycle</th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'status', 'direction' => request('direction') === 'asc' && request('sort') === 'status' ? 'desc' : 'asc']) }}"
                                           class="text-decoration-none">
                                            Status
                                            @if(request('sort') === 'status')
                                                <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }}"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Auto Renew</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($subscriptions as $subscription)
                                <tr>
                                    <td>
                                        <a href="{{ route('super-admin.organizations.show', $subscription->organization) }}"
                                           class="text-decoration-none font-weight-bold">
                                            {{ $subscription->organization->name }}
                                        </a>
                                        <br>
                                        <small class="text-muted">{{ $subscription->organization->email }}</small>
                                    </td>
                                    <td>
                                        @if($subscription->plan)
                                            <span class="badge badge-info">{{ $subscription->plan->name }}</span>
                                            <br>
                                            <small class="text-muted">{{ $subscription->plan->description }}</small>
                                        @else
                                            <span class="badge badge-secondary">No Plan</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="font-weight-bold">${{ number_format($subscription->amount, 2) }}</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-primary">{{ ucfirst($subscription->billing_cycle) }}</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $subscription->status === 'active' ? 'success' : ($subscription->status === 'trial' ? 'warning' : ($subscription->status === 'suspended' ? 'secondary' : 'danger')) }}">
                                            {{ ucfirst($subscription->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $subscription->start_date->format('M d, Y') }}</td>
                                    <td>{{ $subscription->end_date->format('M d, Y') }}</td>
                                    <td>
                                        <span class="badge badge-{{ $subscription->auto_renew ? 'success' : 'danger' }}">
                                            {{ $subscription->auto_renew ? 'Yes' : 'No' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('super-admin.subscriptions.show', $subscription) }}"
                                               class="btn btn-sm btn-info" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('super-admin.subscriptions.edit', $subscription) }}"
                                               class="btn btn-sm btn-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button"
                                                    class="btn btn-sm btn-{{ $subscription->status === 'active' ? 'danger' : 'success' }}"
                                                    onclick="updateStatus({{ $subscription->id }})"
                                                    title="{{ $subscription->status === 'active' ? 'Suspend' : 'Activate' }}">
                                                <i class="fas fa-{{ $subscription->status === 'active' ? 'pause' : 'play' }}"></i>
                                            </button>
                                            <button type="button"
                                                    class="btn btn-sm btn-warning"
                                                    onclick="renewSubscription({{ $subscription->id }})"
                                                    title="Renew">
                                                <i class="fas fa-sync"></i>
                                            </button>
                                            <button type="button"
                                                    class="btn btn-sm btn-danger"
                                                    onclick="cancelSubscription({{ $subscription->id }})"
                                                    title="Cancel">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="d-flex justify-content-center">
                                {{ $subscriptions->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Update subscription status
    window.updateStatus = function(subscriptionId) {
        const newStatus = prompt('Enter new status (active, trial, suspended, cancelled):');
        if (newStatus) {
            fetch('{{ route('super-admin.subscriptions.update-status', '') }}/' + subscriptionId, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    status: newStatus
                })
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while processing your request.');
            });
        }
    };

    // Renew subscription
    window.renewSubscription = function(subscriptionId) {
        if(confirm('Are you sure you want to renew this subscription?')) {
            fetch('{{ route('super-admin.subscriptions.renew', '') }}/' + subscriptionId, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while processing your request.');
            });
        }
    };

    // Cancel subscription
    window.cancelSubscription = function(subscriptionId) {
        if(confirm('Are you sure you want to cancel this subscription?')) {
            fetch('{{ route('super-admin.subscriptions.cancel', '') }}/' + subscriptionId, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while processing your request.');
            });
        }
    };
});
</script>
@endpush
@endsection
