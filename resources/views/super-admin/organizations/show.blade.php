@extends('layouts.admin')

@section('title', 'Organization Details - ' . $organization->name)

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h2 mb-0 text-gray-900">{{ $organization->name }}</h1>
            <p class="mb-0 text-muted">Organization details and management</p>
        </div>
    </div>

    <!-- Organization Overview -->
    <div class="row">
        <div class="col-lg-8">
            <!-- Basic Information Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Organization Information</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                            <a class="dropdown-item" href="{{ route('super-admin.organizations.edit', $organization) }}">
                                <i class="fas fa-edit fa-sm fa-fw"></i> Edit Organization
                            </a>
                            <a class="dropdown-item" href="#" onclick="toggleStatus({{ $organization->id }})">
                                <i class="fas fa-{{ $organization->status === 'active' ? 'pause' : 'play' }} fa-sm fa-fw"></i>
                                {{ $organization->status === 'active' ? 'Suspend' : 'Activate' }}
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-danger" href="#" onclick="deleteOrganization({{ $organization->id }})">
                                <i class="fas fa-trash fa-sm fa-fw"></i> Delete Organization
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary font-weight-bold">Basic Information</h6>
                            <ul class="list-unstyled">
                                <li><strong>Name:</strong> {{ $organization->name }}</li>
                                <li><strong>Email:</strong> {{ $organization->email }}</li>
                                <li><strong>Domain:</strong> {{ $organization->domain }}</li>
                                <li><strong>Country:</strong> {{ $organization->country }}</li>
                                <li><strong>Status:</strong>
                                    <span class="badge badge-{{ $organization->status === 'active' ? 'success' : ($organization->status === 'trial' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($organization->status) }}
                                    </span>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-primary font-weight-bold">Subscription Information</h6>
                            @if($organization->subscription)
                                <ul class="list-unstyled">
                                    <li><strong>Plan:</strong>
                                        <span class="badge badge-info">{{ $organization->subscription->plan->name ?? 'No Plan' }}</span>
                                    </li>
                                    <li><strong>Amount:</strong> ${{ number_format($organization->subscription->amount, 2) }}</li>
                                    <li><strong>Billing Cycle:</strong> {{ ucfirst($organization->subscription->billing_cycle) }}</li>
                                    <li><strong>Status:</strong>
                                        <span class="badge badge-{{ $organization->subscription->status === 'active' ? 'success' : ($organization->subscription->status === 'trial' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($organization->subscription->status) }}
                                        </span>
                                    </li>
                                    <li><strong>Start Date:</strong> {{ $organization->subscription->start_date->format('M d, Y') }}</li>
                                    <li><strong>End Date:</strong> {{ $organization->subscription->end_date->format('M d, Y') }}</li>
                                </ul>
                            @else
                                <p class="text-muted">No subscription found</p>
                            @endif
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <h6 class="text-primary font-weight-bold">System Information</h6>
                            <ul class="list-unstyled">
                                <li><strong>Created:</strong> {{ $organization->created_at->format('M d, Y H:i') }}</li>
                                <li><strong>Updated:</strong> {{ $organization->updated_at->format('M d, Y H:i') }}</li>
                                <li><strong>Created By:</strong>
                                    @if($organization->createdBy)
                                        {{ $organization->createdBy->name }}
                                    @else
                                        System
                                    @endif
                                </li>
                                <li><strong>Updated By:</strong>
                                    @if($organization->updatedBy)
                                        {{ $organization->updatedBy->name }}
                                    @else
                                        Not updated
                                    @endif
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Users Section -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Users ({{ $organization->users->count() }})</h6>
                    <a href="#" class="btn btn-sm btn-primary">View All Users</a>
                </div>
                <div class="card-body">
                    @if($organization->users->isEmpty())
                        <p class="text-muted">No users found for this organization.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Status</th>
                                        <th>Created</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($organization->users->take(5) as $user)
                                    <tr>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            @if($user->roles->isNotEmpty())
                                                <span class="badge badge-info">{{ $user->roles->first()->name }}</span>
                                            @else
                                                <span class="badge badge-secondary">No Role</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ $user->status === 'active' ? 'success' : 'danger' }}">
                                                {{ ucfirst($user->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $user->created_at->format('M d, Y') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Quick Actions -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('super-admin.organizations.edit', $organization) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit Organization
                        </a>
                        <button type="button" class="btn btn-{{ $organization->status === 'active' ? 'danger' : 'success' }}" onclick="toggleStatus({{ $organization->id }})">
                            <i class="fas fa-{{ $organization->status === 'active' ? 'pause' : 'play' }}"></i>
                            {{ $organization->status === 'active' ? 'Suspend' : 'Activate' }}
                        </button>
                        <button type="button" class="btn btn-info" onclick="viewSubscriptionDetails()">
                            <i class="fas fa-file-invoice-dollar"></i> View Subscription
                        </button>
                        <button type="button" class="btn btn-secondary" onclick="viewUsers()">
                            <i class="fas fa-users"></i> View Users
                        </button>
                    </div>
                </div>
            </div>

            <!-- Statistics -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Organization Statistics</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="text-gray-900 font-weight-bold">{{ $organization->users->count() }}</div>
                            <div class="text-muted small">Total Users</div>
                        </div>
                        <div class="col-6">
                            <div class="text-gray-900 font-weight-bold">
                                @if($organization->subscription)
                                    ${{ number_format($organization->subscription->amount, 2) }}
                                @else
                                    $0.00
                                @endif
                            </div>
                            <div class="text-muted small">Monthly Revenue</div>
                        </div>
                    </div>
                    <hr>
                    <div class="text-center">
                        <div class="text-gray-900 font-weight-bold">
                            {{ $organization->subscription ? $organization->subscription->start_date->diffInDays($organization->subscription->end_date) : 0 }}
                        </div>
                        <div class="text-muted small">Days Remaining</div>
                    </div>
                </div>
            </div>

            <!-- Documents -->
            @if($organization->documents->isNotEmpty())
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Documents</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        @foreach($organization->documents->take(3) as $document)
                        <li class="mb-2">
                            <i class="fas fa-file text-gray-400"></i>
                            <a href="{{ route('super-admin.documents.show', $document) }}" class="text-decoration-none">
                                {{ $document->title }}
                            </a>
                            <small class="text-muted d-block">{{ $document->created_at->format('M d, Y') }}</small>
                        </li>
                        @endforeach
                    </ul>
                    @if($organization->documents->count() > 3)
                        <a href="#" class="btn btn-sm btn-outline-primary">View All Documents</a>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Related Data -->
    <div class="row">
        <!-- Documents -->
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Documents</h6>
                    <a href="#" class="btn btn-sm btn-primary">View All</a>
                </div>
                <div class="card-body">
                    @if($organization->documents->isEmpty())
                        <p class="text-muted">No documents found.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Type</th>
                                        <th>Status</th>
                                        <th>Created</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($organization->documents->take(5) as $document)
                                    <tr>
                                        <td>{{ $document->title }}</td>
                                        <td>{{ $document->type }}</td>
                                        <td>
                                            <span class="badge badge-{{ $document->status === 'active' ? 'success' : 'warning' }}">
                                                {{ ucfirst($document->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $document->created_at->format('M d, Y') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Letterheads -->
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Letterheads</h6>
                    <a href="#" class="btn btn-sm btn-primary">View All</a>
                </div>
                <div class="card-body">
                    @if($organization->letterheads->isEmpty())
                        <p class="text-muted">No letterheads found.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Template</th>
                                        <th>Status</th>
                                        <th>Created</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($organization->letterheads->take(5) as $letterhead)
                                    <tr>
                                        <td>{{ $letterhead->name }}</td>
                                        <td>{{ $letterhead->template_type }}</td>
                                        <td>
                                            <span class="badge badge-{{ $letterhead->status === 'active' ? 'success' : 'warning' }}">
                                                {{ ucfirst($letterhead->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $letterhead->created_at->format('M d, Y') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function toggleStatus(organizationId) {
    if(confirm('Are you sure you want to change the status of this organization?')) {
        fetch('{{ route('super-admin.organizations.toggle-status', $organization) }}', {
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
}

function deleteOrganization(organizationId) {
    if(confirm('Are you sure you want to delete this organization? This action cannot be undone.')) {
        fetch('{{ route('super-admin.organizations.destroy', $organization) }}', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                window.location.href = '{{ route('super-admin.organizations.index') }}';
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while processing your request.');
        });
    }
}

function viewSubscriptionDetails() {
    // Implementation for viewing subscription details
    alert('Subscription details would be shown here');
}

function viewUsers() {
    // Implementation for viewing users
    alert('Users list would be shown here');
}
</script>
@endpush
@endsection
