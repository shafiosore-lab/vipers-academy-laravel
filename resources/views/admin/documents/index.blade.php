@extends('layouts.admin')

@section('title', __('Document Management - Vipers Academy Admin'))

@push('styles')
<style>
.documents-container {
    background: white;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    overflow: hidden;
}

.compact-stats-row {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
    margin-bottom: 16px;
}

.compact-stat-card {
    flex: 1;
    min-width: 100px;
    background: white;
    border-radius: 8px;
    padding: 12px;
    display: flex;
    align-items: center;
    gap: 12px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.compact-stat-icon {
    width: 36px;
    height: 36px;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
}

.compact-stat-content {
    display: flex;
    flex-direction: column;
}

.compact-stat-value {
    font-size: 18px;
    font-weight: 700;
    line-height: 1.2;
    color: #2c3e50;
}

.compact-stat-label {
    font-size: 11px;
    color: #6c757d;
    font-weight: 500;
}

.nav-tabs-custom {
    border-bottom: 2px solid #e5e7eb;
    margin-bottom: 0;
}

.nav-tabs-custom .nav-link {
    border: none;
    border-radius: 0;
    font-weight: 500;
    color: #6b7280;
    padding: 10px 16px;
    font-size: 13px;
}

.nav-tabs-custom .nav-link:hover {
    background-color: #f9fafb;
    color: #374151;
}

.nav-tabs-custom .nav-link.active {
    background-color: transparent;
    color: #3b82f6;
    border-bottom-color: #3b82f6;
    font-weight: 600;
}

.filters-row {
    background: #f9fafb;
    padding: 12px 16px;
    border-bottom: 1px solid #e5e7eb;
}

.document-actions {
    display: flex;
    gap: 4px;
    align-items: center;
}

.badge-custom {
    font-size: 10px;
    padding: 3px 6px;
    border-radius: 4px;
    font-weight: 500;
}

.btn-modern {
    padding: 6px 12px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 500;
    border: none;
    cursor: pointer;
}

.bulk-actions {
    display: flex;
    gap: 10px;
    align-items: center;
    padding: 8px 12px;
    background: #fefce8;
    border-bottom: 1px solid #e5e7eb;
}

.bulk-actions.hidden {
    display: none;
}

.tab-content-custom {
    padding: 12px;
}

.table {
    font-size: 13px;
}

.table th {
    font-weight: 600;
    font-size: 11px;
    text-transform: uppercase;
    padding: 8px;
}

.table td {
    padding: 8px;
    vertical-align: middle;
}

.search-input {
    padding: 6px 10px;
    border: 1px solid #e5e7eb;
    border-radius: 4px;
    font-size: 12px;
}

.search-input:focus {
    outline: none;
    border-color: #3b82f6;
}

.category-filter {
    min-width: 120px;
    font-size: 12px;
    padding: 4px 8px;
}

.category-filter option {
    text-transform: capitalize;
}

.pagination-modern {
    justify-content: center;
    gap: 4px;
}

.pagination-modern .page-link {
    border-radius: 4px;
    border: 1px solid #e5e7eb;
    padding: 4px 8px;
    font-size: 12px;
    color: #374151;
}

.pagination-modern .page-item.active .page-link {
    background: #3b82f6;
    border-color: #3b82f6;
    color: white;
}

@media (max-width: 768px) {
    .compact-stat-card {
        min-width: 80px;
        padding: 8px;
    }

    .compact-stat-icon {
        width: 28px;
        height: 28px;
    }

    .compact-stat-value {
        font-size: 14px;
    }
}
</style>
@endpush

@section('content')
<div class="container-fluid px-2">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h5 mb-0">{{ __('Documents') }}</h1>
            <small class="text-muted">{{ __('Manage academy documents') }}</small>
        </div>
        <a href="{{ route('admin.documents.create') }}" class="btn btn-sm btn-primary btn-modern">
            <i class="fas fa-plus me-1"></i>{{ __('Create') }}
        </a>
    </div>

    <!-- Compact Stats Row -->
    <div class="compact-stats-row">
        <div class="compact-stat-card">
            <div class="compact-stat-icon" style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);">
                <i class="fas fa-file-alt text-white"></i>
            </div>
            <div class="compact-stat-content">
                <div class="compact-stat-value">{{ $statistics['total_documents'] ?? 0 }}</div>
                <div class="compact-stat-label">{{ __('Total Documents') }}</div>
            </div>
        </div>
        <div class="compact-stat-card">
            <div class="compact-stat-icon" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                <i class="fas fa-check-circle text-white"></i>
            </div>
            <div class="compact-stat-content">
                <div class="compact-stat-value">{{ $statistics['active_documents'] ?? 0 }}</div>
                <div class="compact-stat-label">{{ __('Active') }}</div>
            </div>
        </div>
        <div class="compact-stat-card">
            <div class="compact-stat-icon" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                <i class="fas fa-exclamation-triangle text-white"></i>
            </div>
            <div class="compact-stat-content">
                <div class="compact-stat-value">{{ $statistics['mandatory_documents'] ?? 0 }}</div>
                <div class="compact-stat-label">{{ __('Mandatory') }}</div>
            </div>
        </div>
        <div class="compact-stat-card">
            <div class="compact-stat-icon" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);">
                <i class="fas fa-eye text-white"></i>
            </div>
            <div class="compact-stat-content">
                <div class="compact-stat-value">{{ $statistics['total_views'] ?? 0 }}</div>
                <div class="compact-stat-label">{{ __('Views') }}</div>
            </div>
        </div>
        <div class="compact-stat-card">
            <div class="compact-stat-icon" style="background: linear-gradient(135deg, #ec4899 0%, #db2777 100%);">
                <i class="fas fa-pen text-white"></i>
            </div>
            <div class="compact-stat-content">
                <div class="compact-stat-value">{{ $statistics['total_signatures'] ?? 0 }}</div>
                <div class="compact-stat-label">{{ __('Signatures') }}</div>
            </div>
        </div>
        <div class="compact-stat-card">
            <div class="compact-stat-icon" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);">
                <i class="fas fa-clock text-white"></i>
            </div>
            <div class="compact-stat-content">
                <div class="compact-stat-value">{{ $statistics['expiration_summary']['expiring_soon'] ?? 0 }}</div>
                <div class="compact-stat-label">{{ __('Expiring') }}</div>
            </div>
        </div>
    </div>

    <!-- Main Tabbed Interface -->
    <div class="documents-container">
        <ul class="nav nav-tabs nav-tabs-custom" id="documentTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" id="all-tab" data-bs-toggle="tab" href="#all" role="tab">
                    <i class="fas fa-file-alt me-1"></i>{{ __('All') }}
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="active-tab" data-bs-toggle="tab" href="#active" role="tab">
                    <i class="fas fa-check-circle me-1 text-success"></i>{{ __('Active') }}
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="mandatory-tab" data-bs-toggle="tab" href="#mandatory" role="tab">
                    <i class="fas fa-exclamation-triangle me-1 text-warning"></i>{{ __('Mandatory') }}
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="expiring-tab" data-bs-toggle="tab" href="#expiring" role="tab">
                    <i class="fas fa-clock me-1 text-danger"></i>{{ __('Expiring') }}
                </a>
            </li>
        </ul>

        <!-- Bulk Actions Bar -->
        <div class="bulk-actions hidden" id="bulkActions">
            <div class="d-flex justify-content-between align-items-center w-100">
                <div id="selectedCount">0 {{ __('selected') }}</div>
                <div class="d-flex gap-1">
                    <button class="btn btn-success btn-modern btn-sm" onclick="bulkAction('activate')">
                        <i class="fas fa-play"></i>
                    </button>
                    <button class="btn btn-warning btn-modern btn-sm" onclick="bulkAction('deactivate')">
                        <i class="fas fa-pause"></i>
                    </button>
                    <button class="btn btn-danger btn-modern btn-sm" onclick="bulkAction('delete')">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="tab-content tab-content-custom" id="documentTabsContent">
            <!-- All Documents Tab -->
            <div class="tab-pane fade show active" id="all" role="tabpanel">
                @include('admin.documents._table', [
                    'documents' => $documents,
                    'categories' => $categories,
                    'tab' => 'all'
                ])
            </div>

            <!-- Active Documents Tab -->
            <div class="tab-pane fade" id="active" role="tabpanel">
                @include('admin.documents._table', [
                    'documents' => $activeDocuments,
                    'categories' => $categories,
                    'tab' => 'active'
                ])
            </div>

            <!-- Mandatory Documents Tab -->
            <div class="tab-pane fade" id="mandatory" role="tabpanel">
                @include('admin.documents._table', [
                    'documents' => $mandatoryDocuments,
                    'categories' => $categories,
                    'tab' => 'mandatory'
                ])
            </div>

            <!-- Expiring Soon Tab -->
            <div class="tab-pane fade" id="expiring" role="tabpanel">
                @include('admin.documents._table', [
                    'documents' => $expiringDocuments,
                    'categories' => $categories,
                    'tab' => 'expiring'
                ])
            </div>
        </div>
    </div>
</div>

<!-- Bulk Categorize Modal -->
<div class="modal fade" id="bulkCategorizeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Categorize') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="bulkCategorizeForm">
                    @csrf
                    <div class="mb-2">
                        <label for="bulkCategory" class="form-label">{{ __('Category') }}</label>
                        <select class="form-select category-filter" id="bulkCategory" name="category" required>
                            <option value="">{{ __('Select') }}</option>
                            @foreach($categories as $category)
                                <option value="{{ $category['value'] }}">{{ $category['label'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                <button type="button" class="btn btn-sm btn-primary" onclick="submitBulkCategorize()">{{ __('Apply') }}</button>
            </div>
        </div>
    </div>
</div>

@include('admin.documents._table_script')
@endsection
