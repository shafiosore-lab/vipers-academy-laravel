@extends('layouts.admin')

@section('title', __('Document Management - Vipers Academy Admin'))

@push('styles')
<style>
    .documents-container {
        background: white;
        border-radius: 12px;
        box-shadow: var(--shadow-sm);
        overflow: hidden;
    }

    .document-stats {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
    }

    .stat-box {
        background: white;
        border-radius: 8px;
        padding: 15px;
        text-align: center;
        transition: transform 0.2s ease;
    }

    .stat-box:hover {
        transform: translateY(-2px);
    }

    .stat-value {
        font-size: 24px;
        font-weight: bold;
        color: #1e293b;
    }

    .stat-label {
        font-size: 12px;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-top: 5px;
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
        padding: 12px 20px;
        margin-right: 0;
        border-bottom: 3px solid transparent;
        transition: all 0.2s ease;
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

    .documents-table {
        background: white;
        border-radius: 8px;
        overflow: hidden;
    }

    .table-header {
        background: #f8fafc;
        padding: 16px 20px;
        border-bottom: 1px solid #e5e7eb;
    }

    .filters-row {
        background: #f9fafb;
        padding: 15px 20px;
        border-bottom: 1px solid #e5e7eb;
    }

    .document-actions {
        display: flex;
        gap: 8px;
        align-items: center;
    }

    .badge-custom {
        font-size: 11px;
        padding: 4px 8px;
        border-radius: 4px;
        font-weight: 500;
    }

    .badge-active {
        background: #d1fae5;
        color: #059669;
    }

    .badge-inactive {
        background: #fee2e2;
        color: #dc2626;
    }

    .badge-mandatory {
        background: #fef3c7;
        color: #92400e;
    }

    .file-icon {
        font-size: 18px;
        color: #6b7280;
    }

    .file-icon.pdf {
        color: #dc2626;
    }

    .file-icon.doc {
        color: #2563eb;
    }

    .file-icon.txt {
        color: #10b981;
    }

    .file-icon.rtf {
        color: #a855f7;
    }

    .btn-modern {
        padding: 8px 16px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-primary-modern {
        background: #3b82f6;
        color: white;
    }

    .btn-primary-modern:hover {
        background: #2563eb;
        transform: translateY(-1px);
    }

    .btn-danger-modern {
        background: #dc2626;
        color: white;
    }

    .btn-danger-modern:hover {
        background: #b91c1c;
        transform: translateY(-1px);
    }

    .btn-success-modern {
        background: #10b981;
        color: white;
    }

    .btn-success-modern:hover {
        background: #059669;
        transform: translateY(-1px);
    }

    .bulk-actions {
        display: flex;
        gap: 10px;
        align-items: center;
        padding: 10px 20px;
        background: #fefce8;
        border-bottom: 1px solid #e5e7eb;
    }

    .bulk-actions.hidden {
        display: none;
    }

    .document-row {
        transition: background 0.2s ease;
    }

    .document-row:hover {
        background: #f9fafb;
    }

    .tab-content-custom {
        padding: 20px;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #6b7280;
    }

    .empty-state i {
        font-size: 48px;
        margin-bottom: 15px;
        opacity: 0.3;
    }

    .pagination-modern {
        justify-content: center;
        gap: 5px;
    }

    .pagination-modern .page-link {
        border-radius: 6px;
        border: 1px solid #e5e7eb;
        padding: 8px 12px;
        color: #374151;
        transition: all 0.2s ease;
    }

    .pagination-modern .page-link:hover {
        background: #f3f4f6;
        border-color: #d1d5db;
    }

    .pagination-modern .page-item.active .page-link {
        background: #3b82f6;
        border-color: #3b82f6;
        color: white;
    }

    .search-container {
        position: relative;
        max-width: 400px;
    }

    .search-input {
        width: 100%;
        padding: 10px 15px;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        font-size: 14px;
        transition: border-color 0.2s ease;
    }

    .search-input:focus {
        outline: none;
        border-color: #3b82f6;
    }

    .category-filter {
        min-width: 180px;
    }

    .category-filter option {
        text-transform: capitalize;
    }

    @media (max-width: 768px) {
        .tab-content-custom {
            padding: 10px;
        }

        .documents-table {
            font-size: 14px;
        }

        .document-actions {
            flex-direction: column;
            gap: 4px;
        }

        .btn-modern {
            padding: 6px 12px;
            font-size: 12px;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold text-gray-900 mb-2">{{ __('Document Management') }}</h1>
            <p class="text-muted mb-0">{{ __('Manage academy documents, policies, and regulatory materials') }}</p>
        </div>
        <div class="d-flex gap-3">
            <a href="{{ route('admin.documents.create') }}" class="btn btn-primary btn-modern">
                <i class="fas fa-plus me-2"></i>{{ __('Create Document') }}
            </a>
        </div>
    </div>

    <!-- Statistics Overview -->
    <div class="document-stats mb-4">
        <div class="row g-3">
            <div class="col-lg-2 col-md-6">
                <div class="stat-box">
                    <div class="stat-value">{{ $statistics['total_documents'] ?? 0 }}</div>
                    <div class="stat-label">{{ __('Total Documents') }}</div>
                </div>
            </div>
            <div class="col-lg-2 col-md-6">
                <div class="stat-box">
                    <div class="stat-value">{{ $statistics['active_documents'] ?? 0 }}</div>
                    <div class="stat-label">{{ __('Active') }}</div>
                </div>
            </div>
            <div class="col-lg-2 col-md-6">
                <div class="stat-box">
                    <div class="stat-value">{{ $statistics['mandatory_documents'] ?? 0 }}</div>
                    <div class="stat-label">{{ __('Mandatory') }}</div>
                </div>
            </div>
            <div class="col-lg-2 col-md-6">
                <div class="stat-box">
                    <div class="stat-value">{{ $statistics['total_views'] ?? 0 }}</div>
                    <div class="stat-label">{{ __('Total Views') }}</div>
                </div>
            </div>
            <div class="col-lg-2 col-md-6">
                <div class="stat-box">
                    <div class="stat-value">{{ $statistics['total_signatures'] ?? 0 }}</div>
                    <div class="stat-label">{{ __('Signatures') }}</div>
                </div>
            </div>
            <div class="col-lg-2 col-md-6">
                <div class="stat-box">
                    <div class="stat-value">{{ $statistics['expiration_summary']['expiring_soon'] ?? 0 }}</div>
                    <div class="stat-label">{{ __('Expiring Soon') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Tabbed Interface -->
    <div class="documents-container">
        <ul class="nav nav-tabs nav-tabs-custom" id="documentTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" id="all-tab" data-bs-toggle="tab" href="#all" role="tab">
                    <i class="fas fa-file-alt me-2"></i>{{ __('All Documents') }}
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="active-tab" data-bs-toggle="tab" href="#active" role="tab">
                    <i class="fas fa-check-circle me-2 text-success"></i>{{ __('Active') }}
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="mandatory-tab" data-bs-toggle="tab" href="#mandatory" role="tab">
                    <i class="fas fa-exclamation-triangle me-2 text-warning"></i>{{ __('Mandatory') }}
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="expiring-tab" data-bs-toggle="tab" href="#expiring" role="tab">
                    <i class="fas fa-clock me-2 text-danger"></i>{{ __('Expiring Soon') }}
                </a>
            </li>
        </ul>

        <!-- Bulk Actions Bar -->
        <div class="bulk-actions hidden" id="bulkActions">
            <div class="d-flex justify-content-between align-items-center w-100">
                <div id="selectedCount">0 {{ __('documents selected') }}</div>
                <div class="d-flex gap-2">
                    <button class="btn btn-success btn-modern btn-sm" onclick="bulkAction('activate')">
                        <i class="fas fa-play me-1"></i>{{ __('Activate') }}
                    </button>
                    <button class="btn btn-warning btn-modern btn-sm" onclick="bulkAction('deactivate')">
                        <i class="fas fa-pause me-1"></i>{{ __('Deactivate') }}
                    </button>
                    <button class="btn btn-primary btn-modern btn-sm" onclick="bulkModal()">
                        <i class="fas fa-tag me-1"></i>{{ __('Categorize') }}
                    </button>
                    <button class="btn btn-info btn-modern btn-sm" onclick="bulkAction('duplicate')">
                        <i class="fas fa-copy me-1"></i>{{ __('Duplicate') }}
                    </button>
                    <button class="btn btn-danger btn-modern btn-sm" onclick="bulkAction('delete')">
                        <i class="fas fa-trash me-1"></i>{{ __('Delete') }}
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
                <h5 class="modal-title">{{ __('Categorize Selected Documents') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="bulkCategorizeForm">
                    @csrf
                    <div class="mb-3">
                        <label for="bulkCategory" class="form-label">{{ __('New Category') }}</label>
                        <select class="form-select category-filter" id="bulkCategory" name="category" required>
                            <option value="">{{ __('Select Category') }}</option>
                            @foreach($categories as $category)
                                <option value="{{ $category['value'] }}">{{ $category['label'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                <button type="button" class="btn btn-primary" onclick="submitBulkCategorize()">{{ __('Apply Category') }}</button>
            </div>
        </div>
    </div>
</div>

@include('admin.documents._table_script')
@endsection
