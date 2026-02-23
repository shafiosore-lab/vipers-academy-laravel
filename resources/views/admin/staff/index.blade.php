@extends('layouts.admin')

@section('title', __('Staff Management - Vipers Academy Admin'))

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h4 mb-0">{{ __('Staff Management') }}</h1>
                    <p class="text-muted small mb-0">{{ __('Manage academy staff members and their roles') }}</p>
                </div>
                <a href="{{ route('admin.staff.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus me-1"></i>{{ __('Add New Staff') }}
                </a>
            </div>
        </div>
    </div>

    <!-- Compact Statistics Row -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="compact-stats-row">
                <div class="compact-stat-card">
                    <div class="stat-icon" style="background: linear-gradient(45deg, #667eea, #764ba2);">
                        <i class="fas fa-users text-white"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-value">{{ $totalStaff }}</span>
                        <span class="stat-label">Total Staff</span>
                    </div>
                    <div class="stat-meta">{{ $activeStaff }} active</div>
                </div>

                <div class="compact-stat-card">
                    <div class="stat-icon" style="background: linear-gradient(45deg, #f093fb, #f5576c);">
                        <i class="fas fa-user-tie text-white"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-value">{{ $coaches }}</span>
                        <span class="stat-label">Coaches</span>
                    </div>
                    <div class="stat-meta">Training staff</div>
                </div>

                <div class="compact-stat-card">
                    <div class="stat-icon" style="background: linear-gradient(45deg, #4facfe, #00f2fe);">
                        <i class="fas fa-cogs text-white"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-value">{{ $supportStaff }}</span>
                        <span class="stat-label">Support Staff</span>
                    </div>
                    <div class="stat-meta">Admin roles</div>
                </div>

                <div class="compact-stat-card">
                    <div class="stat-icon" style="background: linear-gradient(45deg, #43e97b, #38f9d7);">
                        <i class="fas fa-calendar-plus text-white"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-value">{{ $recentStaff }}</span>
                        <span class="stat-label">Recent</span>
                    </div>
                    <div class="stat-meta">This month</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Staff Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 py-2">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-users me-2 text-primary"></i>{{ __('All Staff Members') }}</h5>
                <div class="d-flex gap-2">
                    <input type="text" id="searchInput" class="form-control form-control-sm" placeholder="{{ __('Search...') }}" style="width: 180px;">
                    <select id="roleFilter" class="form-select form-select-sm" style="width: 130px;">
                        <option value="">{{ __('All Roles') }}</option>
                        <option value="coach">{{ __('Coach') }}</option>
                        <option value="assistant_coach">{{ __('Assistant') }}</option>
                        <option value="manager">{{ __('Manager') }}</option>
                        <option value="physiotherapist">{{ __('Physio') }}</option>
                        <option value="administrator">{{ __('Admin') }}</option>
                    </select>
                    <select id="statusFilter" class="form-select form-select-sm" style="width: 100px;">
                        <option value="">{{ __('Status') }}</option>
                        <option value="active">{{ __('Active') }}</option>
                        <option value="inactive">{{ __('Inactive') }}</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0" id="staffTable" style="font-size: 13px;">
                    <thead class="table-light" style="font-size: 11px;">
                        <tr>
                            <th class="py-2 px-2">{{ __('Staff Member') }}</th>
                            <th class="py-2 px-2">{{ __('Role') }}</th>
                            <th class="py-2 px-2">{{ __('Department') }}</th>
                            <th class="py-2 px-2">{{ __('Contact') }}</th>
                            <th class="py-2 px-2">{{ __('Status') }}</th>
                            <th class="py-2 px-2">{{ __('Joined') }}</th>
                            <th class="py-2 px-2">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($staff ?? [] as $member)
                        <tr>
                            <td class="py-1 px-2 align-middle">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 rounded-circle p-1 me-2" style="width: 28px; height: 28px;">
                                        <i class="fas fa-user text-primary small" style="font-size: 10px;"></i>
                                    </div>
                                    <div class="small">
                                        <div class="fw-semibold" style="font-size: 12px;">{{ $member->name }}</div>
                                        <small class="text-muted" style="font-size: 10px;">{{ $member->employee_id ?? 'ID: ' . $member->id }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="py-1 px-2 align-middle">
                                @if($member->roles->first())
                                    <span class="badge bg-info-subtle text-info border-0" style="font-size: 10px;">{{ $member->roles->first()->name }}</span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary border-0" style="font-size: 10px;">No Role</span>
                                @endif
                            </td>
                            <td class="py-1 px-2 align-middle">
                                <span class="badge bg-light text-dark border" style="font-size: 10px;">{{ ucfirst($member->department ?? 'General') }}</span>
                            </td>
                            <td class="py-1 px-2 align-middle" style="font-size: 11px;">
                                <div>{{ $member->email }}</div>
                                <small class="text-muted" style="font-size: 10px;">{{ $member->phone ?? 'No phone' }}</small>
                            </td>
                            <td class="py-1 px-2 align-middle">
                                @if($member->isApproved())
                                    <span class="badge bg-success-subtle text-success border-0" style="font-size: 10px;">{{ __('Active') }}</span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary border-0" style="font-size: 10px;">{{ __('Inactive') }}</span>
                                @endif
                            </td>
                            <td class="py-1 px-2 align-middle" style="font-size: 11px;">
                                <small class="text-muted">{{ $member->created_at->format('M j, Y') }}</small>
                            </td>
                            <td class="py-1 px-2 align-middle">
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('admin.staff.show', $member) }}" class="btn btn-sm btn-outline-primary py-0 px-1" title="{{ __('View Details') }}" style="font-size: 10px;">
                                        <i class="fas fa-eye" style="font-size: 10px;"></i>
                                    </a>
                                    <a href="{{ route('admin.staff.edit', $member) }}" class="btn btn-sm btn-outline-secondary py-0 px-1" title="{{ __('Edit Staff') }}" style="font-size: 10px;">
                                        <i class="fas fa-edit" style="font-size: 10px;"></i>
                                    </a>
                                    @if($member->isApproved())
                                    <form method="POST" action="{{ route('admin.staff.deactivate', $member) }}" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-sm btn-outline-warning py-0 px-1" title="{{ __('Deactivate') }}" style="font-size: 10px;">
                                            <i class="fas fa-pause" style="font-size: 10px;"></i>
                                        </button>
                                    </form>
                                    @else
                                    <form method="POST" action="{{ route('admin.staff.activate', $member) }}" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-sm btn-outline-success py-0 px-1" title="{{ __('Activate') }}" style="font-size: 10px;">
                                            <i class="fas fa-play" style="font-size: 10px;"></i>
                                        </button>
                                    </form>
                                    @endif
                                    <form method="POST" action="{{ route('admin.staff.destroy', $member) }}" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger py-0 px-1" title="{{ __('Delete Staff') }}"
                                                onclick="return confirm('{{ __('Are you sure you want to delete this staff member?') }}')" style="font-size: 10px;">
                                            <i class="fas fa-trash" style="font-size: 10px;"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-users fa-2x mb-2 opacity-50"></i>
                                    <h6>{{ __('No Staff Found') }}</h6>
                                    <p class="small mb-2">{{ __('Start by adding your first staff member.') }}</p>
                                    <a href="{{ route('admin.staff.create') }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-plus me-1"></i>{{ __('Add First Staff Member') }}
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
            <div class="d-flex justify-content-center py-2 border-top">
                {{ $staff->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<style>
    /* Compact Stats Row */
    .compact-stats-row {
        display: flex;
        gap: 0.75rem;
        padding: 0.5rem;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        overflow-x: auto;
    }

    .compact-stat-card {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 0.75rem;
        background: linear-gradient(135deg, #f8f9fa 0%, #fff 100%);
        border: 1px solid #e9ecef;
        border-radius: 6px;
        flex: 1;
        min-width: 140px;
    }

    .compact-stat-card .stat-icon {
        width: 32px;
        height: 32px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
    }

    .compact-stat-card .stat-content {
        display: flex;
        flex-direction: column;
    }

    .compact-stat-card .stat-value {
        font-size: 16px;
        font-weight: 700;
        color: #1a1a1a;
        line-height: 1.2;
    }

    .compact-stat-card .stat-label {
        font-size: 10px;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .compact-stat-card .stat-meta {
        font-size: 9px;
        color: #6c757d;
        margin-left: auto;
        white-space: nowrap;
    }

    /* Badge Styles */
    .bg-success-subtle {
        background-color: rgba(101, 193, 110, 0.15) !important;
        color: #28a745 !important;
    }

    .bg-info-subtle {
        background-color: rgba(23, 162, 184, 0.15) !important;
        color: #17a2b8 !important;
    }

    .bg-secondary-subtle {
        background-color: rgba(108, 117, 125, 0.15) !important;
        color: #6c757d !important;
    }

    /* Table Styles */
    .table td, .table th {
        padding: 0.25rem 0.5rem;
        vertical-align: middle;
    }

    .table-sm td, .table-sm th {
        padding: 0.2rem 0.4rem;
    }

    .btn-group .btn {
        margin-right: 1px;
    }

    .btn-group .btn:last-child {
        margin-right: 0;
    }

    @media (max-width: 768px) {
        .compact-stats-row {
            flex-wrap: wrap;
        }

        .compact-stat-card {
            min-width: calc(50% - 0.5rem);
        }
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
                const roleBadge = row.cells[1].querySelector('.badge')?.textContent.toLowerCase() || '';
                const statusBadge = row.cells[4].querySelector('.badge')?.textContent.toLowerCase() || '';

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
});
</script>
@endsection
