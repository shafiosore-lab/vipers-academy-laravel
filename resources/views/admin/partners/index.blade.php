@extends('layouts.admin')

@section('title', __('Partners Management - Vipers Academy Admin'))

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0">{{ __('Partners Management') }}</h1>
                <p class="text-muted">{{ __('Manage partnership applications and organizations') }}</p>
            </div>
            <a href="{{ route('admin.partners.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>{{ __('Add Partner') }}
            </a>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-lg-6 mb-4">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center">
                <div class="flex-grow-1">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-handshake fa-2x me-3 opacity-75"></i>
                        <div>
                            <h2 class="h4 mb-0 fw-bold">{{ $partners->total() }}</h2>
                            <small class="opacity-75">{{ __('Total Partners') }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-lg-6 mb-4">
        <div class="card stat-card success h-100">
            <div class="card-body d-flex align-items-center">
                <div class="flex-grow-1">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-check-circle fa-2x me-3 opacity-75"></i>
                        <div>
                            <h2 class="h4 mb-0 fw-bold">{{ \App\Models\User::where('user_type', 'partner')->where('status', 'active')->count() }}</h2>
                            <small class="opacity-75">{{ __('Active Partners') }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-lg-6 mb-4">
        <div class="card stat-card warning h-100">
            <div class="card-body d-flex align-items-center">
                <div class="flex-grow-1">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-clock fa-2x me-3 opacity-75"></i>
                        <div>
                            <h2 class="h4 mb-0 fw-bold">{{ \App\Models\User::where('user_type', 'partner')->where('status', 'pending')->count() }}</h2>
                            <small class="opacity-75">{{ __('Pending Approval') }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-lg-6 mb-4">
        <div class="card stat-card danger h-100">
            <div class="card-body d-flex align-items-center">
                <div class="flex-grow-1">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-times-circle fa-2x me-3 opacity-75"></i>
                        <div>
                            <h2 class="h4 mb-0 fw-bold">{{ \App\Models\User::where('user_type', 'partner')->where('status', 'rejected')->count() }}</h2>
                            <small class="opacity-75">{{ __('Rejected') }}</small>
                        </div>
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
            <h5 class="mb-0">{{ __('All Partners') }}</h5>
            <div class="d-flex gap-2">
                <select class="form-select form-select-sm" id="statusFilter" style="width: auto;">
                    <option value="">{{ __('All Status') }}</option>
                    <option value="active">{{ __('Active') }}</option>
                    <option value="pending">{{ __('Pending') }}</option>
                    <option value="rejected">{{ __('Rejected') }}</option>
                </select>
                <input type="text" class="form-control form-control-sm" id="searchInput" placeholder="{{ __('Search partners...') }}" style="width: 200px;">
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="partnersTable">
                <thead>
                    <tr>
                        <th>{{ __('Organization') }}</th>
                        <th>{{ __('Contact Person') }}</th>
                        <th>{{ __('Type') }}</th>
                        <th>{{ __('Partnership Type') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Registered') }}</th>
                        <th>{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($partners as $partner)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                        <i class="fas fa-building text-primary"></i>
                                    </div>
                                    <div>
                                        <div class="fw-semibold">{{ $partner->partner_details['organization_name'] ?? 'N/A' }}</div>
                                        <small class="text-muted">{{ $partner->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <div class="fw-semibold">{{ $partner->partner_details['contact_person'] ?? 'N/A' }}</div>
                                    <small class="text-muted">{{ $partner->partner_details['contact_position'] ?? '' }}</small>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ ucfirst($partner->partner_details['organization_type'] ?? 'other') }}</span>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ ucwords(str_replace('_', ' ', $partner->partner_details['partnership_type'] ?? 'platform_access')) }}</span>
                            </td>
                            <td>
                                @if($partner->status === 'active')
                                    <span class="badge bg-success">{{ __('Active') }}</span>
                                @elseif($partner->status === 'pending')
                                    <span class="badge bg-warning">{{ __('Pending') }}</span>
                                @else
                                    <span class="badge bg-danger">{{ __('Rejected') }}</span>
                                @endif
                            </td>
                            <td>
                                <small class="text-muted">{{ $partner->created_at->format('M d, Y') }}</small>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.partners.show', $partner) }}" class="btn btn-sm btn-outline-primary" title="{{ __('View Details') }}">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.partners.edit', $partner) }}" class="btn btn-sm btn-outline-secondary" title="{{ __('Edit') }}">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if($partner->status === 'pending')
                                        <form method="POST" action="{{ route('admin.partners.approve', $partner) }}" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-sm btn-outline-success" title="{{ __('Approve') }}" onclick="return confirm('{{ __('Are you sure you want to approve this partner?') }}')">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.partners.reject', $partner) }}" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="{{ __('Reject') }}" onclick="return confirm('{{ __('Are you sure you want to reject this partner?') }}')">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                    @endif
                                    <form method="POST" action="{{ route('admin.partners.destroy', $partner) }}" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="{{ __('Delete') }}" onclick="return confirm('{{ __('Are you sure you want to delete this partner?') }}')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="fas fa-handshake fa-3x text-muted mb-3"></i>
                                <div class="text-muted">{{ __('No partners found') }}</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($partners->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $partners->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const table = document.getElementById('partnersTable');
    const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase();
        const statusValue = statusFilter.value.toLowerCase();

        for (let i = 0; i < rows.length; i++) {
            const row = rows[i];
            const cells = row.getElementsByTagName('td');
            let showRow = true;

            if (cells.length > 0) {
                // Search filter
                if (searchTerm) {
                    const organization = cells[0].textContent.toLowerCase();
                    const contact = cells[1].textContent.toLowerCase();
                    const email = cells[0].querySelector('.text-muted')?.textContent.toLowerCase() || '';

                    if (!organization.includes(searchTerm) && !contact.includes(searchTerm) && !email.includes(searchTerm)) {
                        showRow = false;
                    }
                }

                // Status filter
                if (statusValue && showRow) {
                    const statusBadge = cells[4].querySelector('.badge');
                    if (statusBadge) {
                        const statusText = statusBadge.textContent.toLowerCase().trim();
                        if (!statusText.includes(statusValue)) {
                            showRow = false;
                        }
                    }
                }
            }

            row.style.display = showRow ? '' : 'none';
        }
    }

    searchInput.addEventListener('input', filterTable);
    statusFilter.addEventListener('change', filterTable);
});
</script>
@endpush

<style>
.stat-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 1rem;
    padding: 1.5rem;
    margin-bottom: 1rem;
}

.stat-card.success { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); }
.stat-card.warning { background: linear-gradient(135deg, #fcb045 0%, #fd1d1d 100%); }
.stat-card.danger { background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%); }

.btn-group .btn {
    margin-right: 2px;
}

.table th {
    border-top: none;
    font-weight: 600;
    color: #6c757d;
}

@media (max-width: 768px) {
    .table-responsive {
        font-size: 0.875rem;
    }

    .btn-group {
        flex-direction: column;
    }

    .btn-group .btn {
        margin-bottom: 2px;
        margin-right: 0;
    }
}
</style>
