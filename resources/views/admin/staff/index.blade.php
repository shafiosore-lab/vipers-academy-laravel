@extends('layouts.admin')

@section('title', __('Staff Management - Vipers Academy Admin'))

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">{{ __('Staff Management') }}</h1>
                    <p class="text-muted">{{ __('Manage academy staff members and their roles') }}</p>
                </div>
                <a href="{{ route('admin.staff.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>{{ __('Add New Staff') }}
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-lg-6 mb-4">
            <div class="card stat-card h-100" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-grow-1">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-users fa-2x me-3 opacity-75"></i>
                            <div>
                                <h2 class="h4 mb-0 fw-bold">{{ $totalStaff }}</h2>
                                <small class="opacity-75">{{ __('Total Staff') }}</small>
                            </div>
                        </div>
                        <div class="progress mt-3" style="height: 6px;">
                            <div class="progress-bar" role="progressbar" style="width: {{ $totalStaff > 0 ? ($activeStaff / $totalStaff) * 100 : 0 }}%"></div>
                        </div>
                        <small class="text-white-50 mt-1">{{ $activeStaff }} {{ __('active') }}</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 mb-4">
            <div class="card stat-card h-100" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-grow-1">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-user-tie fa-2x me-3 opacity-75"></i>
                            <div>
                                <h2 class="h4 mb-0 fw-bold">{{ $coaches }}</h2>
                                <small class="opacity-75">{{ __('Coaches') }}</small>
                            </div>
                        </div>
                        <div class="mt-3">
                            <small class="text-white-50">{{ __('Training staff') }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 mb-4">
            <div class="card stat-card h-100" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-grow-1">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-cogs fa-2x me-3 opacity-75"></i>
                            <div>
                                <h2 class="h4 mb-0 fw-bold">{{ $supportStaff }}</h2>
                                <small class="opacity-75">{{ __('Support Staff') }}</small>
                            </div>
                        </div>
                        <div class="mt-3">
                            <small class="text-white-50">{{ __('Administrative roles') }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 mb-4">
            <div class="card stat-card h-100" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-grow-1">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-calendar-plus fa-2x me-3 opacity-75"></i>
                            <div>
                                <h2 class="h4 mb-0 fw-bold">{{ $recentStaff }}</h2>
                                <small class="opacity-75">{{ __('Recent Additions') }}</small>
                            </div>
                        </div>
                        <div class="mt-3">
                            <small class="text-white-50">{{ __('This month') }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Staff Table -->
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-users me-2 text-primary"></i>{{ __('All Staff Members') }}</h5>
                <div class="d-flex gap-2">
                    <input type="text" id="searchInput" class="form-control form-control-sm" placeholder="{{ __('Search staff...') }}" style="width: 250px;">
                    <select id="roleFilter" class="form-select form-select-sm" style="width: 150px;">
                        <option value="">{{ __('All Roles') }}</option>
                        <option value="coach">{{ __('Coach') }}</option>
                        <option value="assistant_coach">{{ __('Assistant Coach') }}</option>
                        <option value="manager">{{ __('Manager') }}</option>
                        <option value="physiotherapist">{{ __('Physiotherapist') }}</option>
                        <option value="nutritionist">{{ __('Nutritionist') }}</option>
                        <option value="administrator">{{ __('Administrator') }}</option>
                    </select>
                    <select id="statusFilter" class="form-select form-select-sm" style="width: 120px;">
                        <option value="">{{ __('All Status') }}</option>
                        <option value="active">{{ __('Active') }}</option>
                        <option value="inactive">{{ __('Inactive') }}</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="staffTable">
                    <thead>
                        <tr>
                            <th>{{ __('Staff Member') }}</th>
                            <th>{{ __('Role') }}</th>
                            <th>{{ __('Department') }}</th>
                            <th>{{ __('Contact') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Joined') }}</th>
                            <th>{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($staff ?? [] as $member)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                        <i class="fas fa-user text-primary"></i>
                                    </div>
                                    <div>
                                        <div class="fw-semibold">{{ $member->name }}</div>
                                        <small class="text-muted">{{ $member->employee_id ?? 'ID: ' . $member->id }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($member->roles->first())
                                    <span class="badge bg-info">{{ $member->roles->first()->name }}</span>
                                @else
                                    <span class="badge bg-secondary">No Role</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ ucfirst($member->department ?? 'General') }}</span>
                            </td>
                            <td>
                                <div>
                                    <div>{{ $member->email }}</div>
                                    <small class="text-muted">{{ $member->phone ?? 'No phone' }}</small>
                                </div>
                            </td>
                            <td>
                                @if($member->isApproved())
                                    <span class="badge bg-success">{{ __('Active') }}</span>
                                @else
                                    <span class="badge bg-secondary">{{ __('Inactive') }}</span>
                                @endif
                            </td>
                            <td>
                                <small class="text-muted">{{ $member->created_at->format('M j, Y') }}</small>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.staff.show', $member) }}" class="btn btn-sm btn-outline-primary" title="{{ __('View Details') }}">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.staff.edit', $member) }}" class="btn btn-sm btn-outline-secondary" title="{{ __('Edit Staff') }}">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if($member->isApproved())
                                    <form method="POST" action="{{ route('admin.staff.deactivate', $member) }}" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-sm btn-warning" title="{{ __('Deactivate') }}">
                                            <i class="fas fa-pause"></i>
                                        </button>
                                    </form>
                                    @else
                                    <form method="POST" action="{{ route('admin.staff.activate', $member) }}" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-sm btn-success" title="{{ __('Activate') }}">
                                            <i class="fas fa-play"></i>
                                        </button>
                                    </form>
                                    @endif
                                    <form method="POST" action="{{ route('admin.staff.destroy', $member) }}" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="{{ __('Delete Staff') }}"
                                                onclick="return confirm('{{ __('Are you sure you want to delete this staff member?') }}')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-users fa-3x mb-3 opacity-50"></i>
                                    <h5>{{ __('No Staff Found') }}</h5>
                                    <p>{{ __('Start by adding your first staff member.') }}</p>
                                    <a href="{{ route('admin.staff.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus me-2"></i>{{ __('Add First Staff Member') }}
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if(isset($staff) && $staff->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $staff->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<style>
    .stat-card {
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    }

    .table th {
        border-top: none;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.875rem;
        letter-spacing: 0.5px;
    }

    .table td {
        vertical-align: middle;
    }

    .badge {
        font-weight: 500;
    }

    .btn-group .btn {
        margin-right: 2px;
    }

    .btn-group .btn:last-child {
        margin-right: 0;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const roleFilter = document.getElementById('roleFilter');
    const statusFilter = document.getElementById('statusFilter');
    const tableRows = document.querySelectorAll('#staffTable tbody tr');

    // Search functionality
    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase();
        const roleValue = roleFilter.value.toLowerCase();
        const statusValue = statusFilter.value.toLowerCase();

        tableRows.forEach(row => {
            if (row.cells.length > 1) { // Skip empty state row
                const staffName = row.cells[0].textContent.toLowerCase();
                const roleBadge = row.cells[1].querySelector('.badge').textContent.toLowerCase();
                const statusBadge = row.cells[4].querySelector('.badge').textContent.toLowerCase();

                const matchesSearch = staffName.includes(searchTerm);
                const matchesRole = !roleValue || roleBadge.includes(roleValue);
                const matchesStatus = !statusValue || statusBadge.includes(statusValue);

                row.style.display = (matchesSearch && matchesRole && matchesStatus) ? '' : 'none';
            }
        });
    }

    searchInput.addEventListener('input', filterTable);
    roleFilter.addEventListener('change', filterTable);
    statusFilter.addEventListener('change', filterTable);

    // Auto-refresh functionality (optional)
    setInterval(function() {
        // Could add real-time updates here if needed
        console.log('Staff table updated');
    }, 30000); // Refresh every 30 seconds
});
</script>
@endsection
