@extends('layouts.admin')

@section('title', 'Document Management - ' . $organization->name)

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Document Management</h5>
                    <div class="d-flex gap-2">
                        <a href="{{ route('super-admin.organizations.branding.index', $organization) }}" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-palette me-2"></i>Branding
                        </a>
                        <a href="{{ route('super-admin.organizations.documents.create', $organization) }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-circle me-2"></i>Add Document
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Search and Filters -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <input type="text" class="form-control" id="searchInput" placeholder="Search documents...">
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="typeFilter">
                                <option value="">All Types</option>
                                @foreach($documentTypes as $key => $type)
                                    <option value="{{ $key }}">{{ $type }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="statusFilter">
                                <option value="">All Statuses</option>
                                @foreach($statuses as $key => $status)
                                    <option value="{{ $key }}">{{ $status }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="categoryFilter">
                                <option value="">All Categories</option>
                                @foreach($categories as $key => $category)
                                    <option value="{{ $key }}">{{ $category }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Documents Table -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Document Name</th>
                                    <th>Type</th>
                                    <th>Category</th>
                                    <th>Status</th>
                                    <th>Version</th>
                                    <th>Created By</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($documents as $document)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="me-2">
                                                @if($document->file_path)
                                                    <i class="bi bi-file-earmark-text text-primary"></i>
                                                @else
                                                    <i class="bi bi-file-text text-secondary"></i>
                                                @endif
                                            </div>
                                            <div>
                                                <div class="fw-semibold">{{ $document->name }}</div>
                                                <small class="text-muted">{{ $document->description }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $documentTypes[$document->document_type] ?? $document->document_type }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark">{{ $categories[$document->category] ?? $document->category }}</span>
                                    </td>
                                    <td>
                                        @php
                                            $statusClass = [
                                                'draft' => 'warning',
                                                'pending_approval' => 'info',
                                                'approved' => 'success',
                                                'rejected' => 'danger',
                                                'archived' => 'secondary',
                                                'expired' => 'dark'
                                            ][$document->status] ?? 'secondary';
                                        @endphp
                                        <span class="badge bg-{{ $statusClass }}">{{ $statuses[$document->status] ?? $document->status }}</span>
                                    </td>
                                    <td>{{ $document->version }}</td>
                                    <td>{{ $document->creator->name ?? 'N/A' }}</td>
                                    <td>{{ $document->created_at->format('M d, Y H:i') }}</td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('super-admin.organizations.documents.show', [$organization, $document]) }}" class="btn btn-outline-primary" title="View">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('super-admin.organizations.documents.edit', [$organization, $document]) }}" class="btn btn-outline-warning" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            @if($document->file_path)
                                                <a href="{{ route('super-admin.organizations.documents.download', [$organization, $document]) }}" class="btn btn-outline-info" title="Download">
                                                    <i class="bi bi-download"></i>
                                                </a>
                                            @endif
                                            <form action="{{ route('super-admin.organizations.documents.destroy', [$organization, $document]) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this document?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger" title="Delete">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            <p class="text-muted mb-0">
                                Showing {{ $documents->firstItem() }} to {{ $documents->lastItem() }} of {{ $documents->total() }} documents
                            </p>
                        </div>
                        <div>
                            {{ $documents->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const typeFilter = document.getElementById('typeFilter');
    const statusFilter = document.getElementById('statusFilter');
    const categoryFilter = document.getElementById('categoryFilter');
    const tableRows = document.querySelectorAll('tbody tr');

    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase();
        const typeValue = typeFilter.value;
        const statusValue = statusFilter.value;
        const categoryValue = categoryFilter.value;

        tableRows.forEach(row => {
            const text = row.textContent.toLowerCase();
            const typeCell = row.cells[1].textContent.toLowerCase();
            const statusCell = row.cells[3].textContent.toLowerCase();
            const categoryCell = row.cells[2].textContent.toLowerCase();

            const matchesSearch = text.includes(searchTerm);
            const matchesType = !typeValue || typeCell.includes(typeValue.toLowerCase());
            const matchesStatus = !statusValue || statusCell.includes(statusValue.toLowerCase());
            const matchesCategory = !categoryValue || categoryCell.includes(categoryValue.toLowerCase());

            if (matchesSearch && matchesType && matchesStatus && matchesCategory) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    searchInput.addEventListener('input', filterTable);
    typeFilter.addEventListener('change', filterTable);
    statusFilter.addEventListener('change', filterTable);
    categoryFilter.addEventListener('change', filterTable);
});
</script>
@endpush
@endsection
