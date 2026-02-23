@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Organizations</h1>
        <a href="{{ route('super-admin.organizations.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> New Organization
        </a>
    </div>

    <!-- Filters -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('super-admin.organizations.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label for="search" class="form-label">Search</label>
                    <input type="text" class="form-control" id="search" name="search"
                           placeholder="Search by name or email..."
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="trial" {{ request('status') === 'trial' ? 'selected' : '' }}>Trial</option>
                        <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <a href="{{ route('super-admin.organizations.index') }}" class="btn btn-secondary w-100">
                        <i class="fas fa-redo"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Organizations Table -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-sm table-bordered" width="100%" cellspacing="0">
                    <thead class="table-light">
                        <tr class="small">
                            <th class="py-2">Name</th>
                            <th class="py-2">Email</th>
                            <th class="py-2">Status</th>
                            <th class="py-2">Plan</th>
                            <th class="py-2">Users</th>
                            <th class="py-2">Players</th>
                            <th class="py-2">Created</th>
                            <th class="py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($organizations as $org)
                        <tr>
                            <td>
                                <a href="{{ route('super-admin.organizations.show', $org) }}">
                                    {{ $org->name }}
                                </a>
                            </td>
                            <td>{{ $org->email }}</td>
                            <td>
                                <span class="badge bg-{{ $org->status === 'active' ? 'success' : ($org->status === 'trial' ? 'warning' : ($org->status === 'suspended' ? 'danger' : 'secondary')) }}">
                                    {{ ucfirst($org->status) }}
                                </span>
                            </td>
                            <td>{{ $org->subscriptionPlan->name ?? 'No Plan' }}</td>
                            <td>{{ $org->users()->count() }}</td>
                            <td><span class="text-muted">N/A</span></td>
                            <td>{{ $org->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('super-admin.organizations.show', $org) }}"
                                       class="btn btn-sm btn-info" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('super-admin.organizations.edit', $org) }}"
                                       class="btn btn-sm btn-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST"
                                          action="{{ route('super-admin.organizations.toggle-status', $org) }}"
                                          class="d-inline">
                                        @csrf
                                        <button type="submit"
                                                class="btn btn-sm {{ $org->status === 'active' ? 'btn-warning' : 'btn-success' }}"
                                                title="{{ $org->status === 'active' ? 'Suspend' : 'Activate' }}">
                                            <i class="fas {{ $org->status === 'active' ? 'fas fa-ban' : 'fas fa-check' }}"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">No organizations found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $organizations->links() }}
        </div>
    </div>
</div>
@endsection
