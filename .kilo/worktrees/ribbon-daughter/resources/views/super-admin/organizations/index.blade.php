@extends('layouts.admin')

@section('title', 'Organization Management')

@section('content')
<div class="container-fluid py-3">
    <!-- Page Header -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h4 mb-0 text-gray-800">Organization Management</h1>
                    <small class="text-muted">Manage all organizations and their subscriptions</small>
                </div>
                <div>
                    <a href="{{ route('super-admin.organizations.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Add Org
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body p-3">
                    <form method="GET" action="{{ route('super-admin.organizations.index') }}" class="row g-2">
                        <div class="col-md-3">
                            <input type="text" name="search" class="form-control form-control-sm" placeholder="Search organizations..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <select name="status" class="form-select form-select-sm">
                                <option value="">All Statuses</option>
                                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="trial" {{ request('status') === 'trial' ? 'selected' : '' }}>Trial</option>
                                <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="plan" class="form-select form-select-sm">
                                <option value="">All Plans</option>
                                @foreach($plans as $plan)
                                    <option value="{{ $plan->id }}" {{ request('plan') == $plan->id ? 'selected' : '' }}>{{ $plan->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}">
                        </div>
                        <div class="col-md-2">
                            <input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}">
                        </div>
                        <div class="col-md-1">
                            <button type="submit" class="btn btn-primary btn-sm w-100">Filter</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Organizations Table -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center" style="width: 40px;">
                                        <input type="checkbox" id="select-all" onclick="toggleSelectAll(this)" class="form-check-input">
                                    </th>
                                    <th style="width: 25%">Organization</th>
                                    <th style="width: 20%">Contact</th>
                                    <th style="width: 15%">Status</th>
                                    <th style="width: 20%">Plan</th>
                                    <th style="width: 10%">Created</th>
                                    <th style="width: 10%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($organizations as $organization)
                                <tr>
                                    <td class="text-center">
                                        <input type="checkbox" name="organization_ids[]" value="{{ $organization->id }}" class="form-check-input">
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ $organization->name }}</div>
                                        <small class="text-muted">{{ $organization->domain }}</small>
                                    </td>
                                    <td>
                                        <div>{{ $organization->email }}</div>
                                        <small class="text-muted">{{ $organization->address }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $organization->status === 'active' ? 'success' : ($organization->status === 'trial' ? 'info' : 'warning') }} text-white">
                                            {{ ucfirst($organization->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="fw-semibold">
                                            {{ $organization->subscription && $organization->subscription->plan ? $organization->subscription->plan->name : 'No Plan' }}
                                        </div>
                                        <small class="text-muted">
                                            @if($organization->subscription)
                                                ${{ number_format($organization->subscription->amount, 2) }}
                                            @else
                                                No Subscription
                                            @endif
                                        </small>
                                    </td>
                                    <td>
                                        <small>{{ $organization->created_at->format('M d, Y') }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('super-admin.organizations.show', $organization) }}" class="btn btn-outline-primary" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('super-admin.organizations.edit', $organization) }}" class="btn btn-outline-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('super-admin.organizations.destroy', $organization) }}" method="POST" class="d-inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger" title="Delete" onclick="return confirm('Delete this organization?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                            <button type="button" class="btn btn-outline-{{ $organization->status === 'active' ? 'secondary' : 'success' }}" onclick="toggleStatus({{ $organization->id }})" title="{{ $organization->status === 'active' ? 'Suspend' : 'Activate' }}">
                                                <i class="fas fa-{{ $organization->status === 'active' ? 'pause' : 'play' }}"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="card-footer py-2">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <small class="text-muted">
                                    Showing {{ $organizations->firstItem() }} to {{ $organizations->lastItem() }} of {{ $organizations->total() }} organizations
                                </small>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex justify-content-end">
                                    {{ $organizations->links('pagination::bootstrap-4') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script>
    function toggleStatus(organizationId) {
        if (confirm('Are you sure you want to toggle the status of this organization?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("super-admin.organizations.toggle-status", "__ORG_ID__") }}'.replace('__ORG_ID__', organizationId);
            form.innerHTML = `<input type="hidden" name="_token" value="{{ csrf_token() }}">`;
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>
@endsection
