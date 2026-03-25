@extends('layouts.admin')

@section('title', 'All Users - Super Admin')

@push('styles')
<style>
.table-condensed { font-size: 12px; }
.table-condensed th, .table-condensed td { padding: 4px 8px !important; vertical-align: middle; }
.table-condensed .badge { font-size: 10px; padding: 2px 6px; }
.CompactDate { white-space: nowrap; }
</style>
@endpush

@section('content')
<div class="container-fluid py-2">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h5 class="mb-0">All Users</h5>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.staff.create') }}" class="btn btn-sm btn-primary">
                <i class="fas fa-plus"></i> Create User
            </a>
            <a href="{{ route('super-admin.dashboard') }}" class="btn btn-sm btn-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-2">
        <div class="card-body py-2">
            <form method="GET" action="{{ route('super-admin.users.index') }}" class="row g-2 align-items-center">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Search by name or email" value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="organization_id" class="form-select form-select-sm">
                        <option value="">All Organizations</option>
                        @foreach($organizations as $org)
                            <option value="{{ $org->id }}" {{ request('organization_id') == $org->id ? 'selected' : '' }}>
                                {{ $org->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="role" class="form-select form-select-sm">
                        <option value="">All Roles</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->slug }}" {{ request('role') == $role->slug ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-sm btn-primary w-100">Filter</button>
                </div>
                <div class="col-md-1">
                    <a href="{{ route('super-admin.users.index') }}" class="btn btn-sm btn-outline-secondary w-100">Clear</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Users Table -->
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-condensed table-sm table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 40px;">ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th style="width: 70px;">Type</th>
                            <th style="width: 65px;">Status</th>
                            <th style="width: 75px;">Approval</th>
                            <th style="width: 100px;">Organization</th>
                            <th style="width: 100px;">Roles</th>
                            <th style="width: 80px;">Created</th>
                            <th style="width: 80px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <span class="badge bg-{{ $user->user_type === 'admin' ? 'primary' : ($user->user_type === 'staff' ? 'info' : ($user->user_type === 'partner' ? 'warning' : 'success')) }}">
                                        {{ ucfirst($user->user_type) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $user->status === 'active' ? 'success' : ($user->status === 'pending' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($user->status) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $user->approval_status === 'approved' ? 'success' : ($user->approval_status === 'pending' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($user->approval_status) }}
                                    </span>
                                </td>
                                <td>{{ $user->organization?->name ?? '-' }}</td>
                                <td>
                                    @foreach($user->roles->take(2) as $role)
                                        <span class="badge bg-secondary">{{ $role->name }}</span>
                                    @endforeach
                                    @if($user->roles->count() > 2)
                                        <span class="badge bg-dark">+{{ $user->roles->count() - 2 }}</span>
                                    @endif
                                </td>
                                <td class="CompactDate">{{ $user->created_at->format('d M Y') }}</td>
                                <td>
                                    <form action="{{ route('super-admin.users.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirmDelete('{{ $user->name }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Delete User">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-2">No users found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($users->hasPages())
            <div class="card-footer py-1">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted" style="font-size: 11px;">
                        Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }} of {{ $users->total() }} users
                    </div>
                    {{ $users->links('pagination::bootstrap-4') }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function confirmDelete(userName) {
    return confirm('Are you sure you want to delete user "' + userName + '"?\n\nThis action will:\n• Deactivate the user account\n• Move the user to trash\n• Preserve related data (players, documents, payments)\n\nNote: You cannot delete your own account.');
}
</script>
@endpush

