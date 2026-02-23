@extends('layouts.admin')

@section('title', __('Partners Management - Vipers Academy Admin'))

@section('content')
<div class="container-fluid px-2">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h5 mb-0">{{ __('Partners') }}</h1>
            <small class="text-muted">{{ __('Manage partner organizations') }}</small>
        </div>
        <a href="{{ route('admin.partners.create') }}" class="btn btn-sm btn-primary">
            <i class="fas fa-plus me-1"></i>{{ __('Add') }}
        </a>
    </div>

    <!-- Compact Stats Row -->
    <div class="compact-stats-row mb-3">
        <div class="compact-stat-card">
            <div class="compact-stat-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <i class="fas fa-building text-white"></i>
            </div>
            <div class="compact-stat-content">
                <div class="compact-stat-value">{{ $totalPartners }}</div>
                <div class="compact-stat-label">Total Partners</div>
                <small class="text-muted">{{ $activePartners }} active</small>
            </div>
        </div>
        <div class="compact-stat-card">
            <div class="compact-stat-icon" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <i class="fas fa-clock text-white"></i>
            </div>
            <div class="compact-stat-content">
                <div class="compact-stat-value">{{ $pendingPartners }}</div>
                <div class="compact-stat-label">Pending</div>
            </div>
        </div>
        <div class="compact-stat-card">
            <div class="compact-stat-icon" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                <i class="fas fa-check-circle text-white"></i>
            </div>
            <div class="compact-stat-content">
                <div class="compact-stat-value">{{ $activePartners }}</div>
                <div class="compact-stat-label">Active</div>
            </div>
        </div>
        <div class="compact-stat-card">
            <div class="compact-stat-icon" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                <i class="fas fa-users text-white"></i>
            </div>
            <div class="compact-stat-content">
                <div class="compact-stat-value">{{ $recentPartners->count() }}</div>
                <div class="compact-stat-label">Recent</div>
            </div>
        </div>
    </div>

    <!-- Partners Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header py-2 bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="mb-0 small"><i class="fas fa-handshake me-2 text-primary"></i>{{ __('All Partners') }}</h6>
                <div class="d-flex gap-2">
                    <input type="text" id="searchInput" class="form-control form-control-sm" placeholder="{{ __('Search...') }}" style="width: 150px;">
                    <select id="statusFilter" class="form-select form-select-sm" style="width: 100px;">
                        <option value="">{{ __('All') }}</option>
                        <option value="active">{{ __('Active') }}</option>
                        <option value="pending">{{ __('Pending') }}</option>
                        <option value="rejected">{{ __('Rejected') }}</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="card-body p-2">
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0" id="partnersTable" width="100%" cellspacing="0">
                    <thead class="table-light">
                        <tr class="small">
                            <th class="py-1 px-2">{{ __('Organization') }}</th>
                            <th class="py-1 px-2">{{ __('Contact') }}</th>
                            <th class="py-1 px-2">{{ __('Type') }}</th>
                            <th class="py-1 px-2">{{ __('Status') }}</th>
                            <th class="py-1 px-2">{{ __('Joined') }}</th>
                            <th class="py-1 px-2"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($partners ?? [] as $partner)
                        <tr>
                            <td class="py-1 align-middle">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 rounded-circle p-1 me-2">
                                        <i class="fas fa-building text-primary small"></i>
                                    </div>
                                    <div class="small">
                                        <div class="fw-semibold">{{ $partner->organization_name }}</div>
                                        <small class="text-muted">{{ $partner->name }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="py-1 align-middle small">
                                <div>{{ $partner->contact_person }}</div>
                                <small class="text-muted">{{ $partner->email }}</small>
                            </td>
                            <td class="py-1 align-middle">
                                <span class="badge bg-secondary-subtle text-secondary" style="font-size: 10px;">{{ ucfirst(str_replace('_', ' ', $partner->organization_type)) }}</span>
                            </td>
                            <td class="py-1 align-middle">
                                @if($partner->status === 'active')
                                    <span class="badge bg-success-subtle text-success" style="font-size: 10px;">{{ __('Active') }}</span>
                                @elseif($partner->status === 'pending')
                                    <span class="badge bg-warning-subtle text-warning" style="font-size: 10px;">{{ __('Pending') }}</span>
                                @elseif($partner->status === 'rejected')
                                    <span class="badge bg-danger-subtle text-danger" style="font-size: 10px;">{{ __('Rejected') }}</span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary" style="font-size: 10px;">{{ __('Inactive') }}</span>
                                @endif
                            </td>
                            <td class="py-1 align-middle small">
                                <small class="text-muted">{{ $partner->created_at->format('M j, Y') }}</small>
                            </td>
                            <td class="py-1 align-middle">
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('admin.partners.show', $partner) }}" class="btn btn-sm btn-outline-primary py-0 px-1" title="{{ __('View') }}">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.partners.edit', $partner) }}" class="btn btn-sm btn-outline-secondary py-0 px-1" title="{{ __('Edit') }}">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if($partner->status === 'pending')
                                    <form method="POST" action="{{ route('admin.partners.approve', $partner) }}" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-sm btn-success py-0 px-1" title="{{ __('Approve') }}">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-3">
                                <div class="text-muted">
                                    <i class="fas fa-handshake fa-2x mb-2 opacity-50"></i>
                                    <h6 class="mb-1">{{ __('No Partners Found') }}</h6>
                                    <p class="small mb-2">{{ __('Add your first partner') }}</p>
                                    <a href="{{ route('admin.partners.create') }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-plus me-1"></i>{{ __('Add First') }}
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if(isset($partners) && $partners->hasPages())
            <div class="d-flex justify-content-center mt-2">
                {{ $partners->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<style>
.compact-stats-row {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}

.compact-stat-card {
    flex: 1;
    min-width: 120px;
    background: white;
    border-radius: 8px;
    padding: 12px;
    display: flex;
    align-items: center;
    gap: 12px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.compact-stat-icon {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
}

.compact-stat-content {
    display: flex;
    flex-direction: column;
}

.compact-stat-value {
    font-size: 20px;
    font-weight: 700;
    line-height: 1.2;
    color: #2c3e50;
}

.compact-stat-label {
    font-size: 12px;
    color: #6c757d;
    font-weight: 500;
}

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
    font-size: 0.75rem;
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
    const statusFilter = document.getElementById('statusFilter');
    const tableRows = document.querySelectorAll('#partnersTable tbody tr');

    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase();
        const statusValue = statusFilter.value.toLowerCase();

        tableRows.forEach(row => {
            if (row.cells.length > 1) {
                const organizationName = row.cells[0].textContent.toLowerCase();
                const contactName = row.cells[1].textContent.toLowerCase();
                const statusBadge = row.cells[3].querySelector('.badge').textContent.toLowerCase();

                const matchesSearch = organizationName.includes(searchTerm) || contactName.includes(searchTerm);
                const matchesStatus = !statusValue || statusBadge.includes(statusValue);

                row.style.display = (matchesSearch && matchesStatus) ? '' : 'none';
            }
        });
    }

    searchInput.addEventListener('input', filterTable);
    statusFilter.addEventListener('change', filterTable);
});
</script>
@endsection
