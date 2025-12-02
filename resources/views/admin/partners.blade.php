@extends('layouts.admin')

@section('title', __('Partners Management - Vipers Academy Admin'))

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">{{ __('Partners Management') }}</h1>
                    <p class="text-muted">{{ __('Manage partner organizations and their accounts') }}</p>
                </div>
                <a href="{{ route('admin.partners.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>{{ __('Add New Partner') }}
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
                            <i class="fas fa-building fa-2x me-3 opacity-75"></i>
                            <div>
                                <h2 class="h4 mb-0 fw-bold">{{ $totalPartners }}</h2>
                                <small class="opacity-75">{{ __('Total Partners') }}</small>
                            </div>
                        </div>
                        <div class="progress mt-3" style="height: 6px;">
                            <div class="progress-bar" role="progressbar" style="width: {{ $totalPartners > 0 ? ($activePartners / $totalPartners) * 100 : 0 }}%"></div>
                        </div>
                        <small class="text-white-50 mt-1">{{ $activePartners }} {{ __('active') }}</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 mb-4">
            <div class="card stat-card h-100" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-grow-1">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-clock fa-2x me-3 opacity-75"></i>
                            <div>
                                <h2 class="h4 mb-0 fw-bold">{{ $pendingPartners }}</h2>
                                <small class="opacity-75">{{ __('Pending Approval') }}</small>
                            </div>
                        </div>
                        <div class="mt-3">
                            <small class="text-white-50">{{ __('Awaiting review') }}</small>
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
                            <i class="fas fa-check-circle fa-2x me-3 opacity-75"></i>
                            <div>
                                <h2 class="h4 mb-0 fw-bold">{{ $activePartners }}</h2>
                                <small class="opacity-75">{{ __('Active Partners') }}</small>
                            </div>
                        </div>
                        <div class="mt-3">
                            <small class="text-white-50">{{ __('Approved partnerships') }}</small>
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
                            <i class="fas fa-users fa-2x me-3 opacity-75"></i>
                            <div>
                                <h2 class="h4 mb-0 fw-bold">{{ $recentPartners->count() }}</h2>
                                <small class="opacity-75">{{ __('Recent Partners') }}</small>
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

    <!-- Partners Table -->
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-handshake me-2 text-primary"></i>{{ __('All Partners') }}</h5>
                <div class="d-flex gap-2">
                    <input type="text" id="searchInput" class="form-control form-control-sm" placeholder="{{ __('Search partners...') }}" style="width: 250px;">
                    <select id="statusFilter" class="form-select form-select-sm" style="width: 150px;">
                        <option value="">{{ __('All Status') }}</option>
                        <option value="active">{{ __('Active') }}</option>
                        <option value="pending">{{ __('Pending') }}</option>
                        <option value="rejected">{{ __('Rejected') }}</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="partnersTable">
                    <thead>
                        <tr>
                            <th>{{ __('Organization') }}</th>
                            <th>{{ __('Contact') }}</th>
                            <th>{{ __('Type') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Joined') }}</th>
                            <th>{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($partners ?? [] as $partner)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                        <i class="fas fa-building text-primary"></i>
                                    </div>
                                    <div>
                                        <div class="fw-semibold">{{ $partner->organization_name }}</div>
                                        <small class="text-muted">{{ $partner->name }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <div>{{ $partner->contact_person }}</div>
                                    <small class="text-muted">{{ $partner->email }}</small>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ ucfirst(str_replace('_', ' ', $partner->organization_type)) }}</span>
                            </td>
                            <td>
                                @if($partner->status === 'active')
                                    <span class="badge bg-success">{{ __('Active') }}</span>
                                @elseif($partner->status === 'pending')
                                    <span class="badge bg-warning">{{ __('Pending') }}</span>
                                @elseif($partner->status === 'rejected')
                                    <span class="badge bg-danger">{{ __('Rejected') }}</span>
                                @else
                                    <span class="badge bg-secondary">{{ __('Inactive') }}</span>
                                @endif
                            </td>
                            <td>
                                <small class="text-muted">{{ $partner->created_at->format('M j, Y') }}</small>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.partners.show', $partner) }}" class="btn btn-sm btn-outline-primary" title="{{ __('View Details') }}">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.partners.edit', $partner) }}" class="btn btn-sm btn-outline-secondary" title="{{ __('Edit Partner') }}">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if($partner->status === 'pending')
                                    <form method="POST" action="{{ route('admin.partners.approve', $partner) }}" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-sm btn-success" title="{{ __('Approve') }}">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-handshake fa-3x mb-3 opacity-50"></i>
                                    <h5>{{ __('No Partners Found') }}</h5>
                                    <p>{{ __('Start by adding your first partner organization.') }}</p>
                                    <a href="{{ route('admin.partners.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus me-2"></i>{{ __('Add First Partner') }}
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
            <div class="d-flex justify-content-center mt-4">
                {{ $partners->links() }}
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
    const statusFilter = document.getElementById('statusFilter');
    const tableRows = document.querySelectorAll('#partnersTable tbody tr');

    // Search functionality
    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase();
        const statusValue = statusFilter.value.toLowerCase();

        tableRows.forEach(row => {
            if (row.cells.length > 1) { // Skip empty state row
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

    // Auto-refresh functionality (optional)
    setInterval(function() {
        // Could add real-time updates here if needed
        console.log('Partners table updated');
    }, 30000); // Refresh every 30 seconds
});
</script>
@endsection
