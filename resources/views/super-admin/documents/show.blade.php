 @extends('layouts.admin')

@section('title', $document->name . ' - ' . $organization->name)

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ $document->name }}</h5>
                    <div class="d-flex gap-2">
                        <a href="{{ route('super-admin.organizations.documents.edit', [$organization, $document]) }}" class="btn btn-warning btn-sm">
                            <i class="bi bi-pencil me-2"></i>Edit
                        </a>
                        <a href="{{ route('super-admin.organizations.documents.index', $organization) }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-left me-2"></i>Back to Documents
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <!-- Document Details -->
                            <div class="mb-4">
                                <h6 class="text-muted mb-3">Document Information</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label text-muted">Document Type</label>
                                            <div class="fw-semibold">{{ $documentTypes[$document->document_type] ?? $document->document_type }}</div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label text-muted">Category</label>
                                            <div class="fw-semibold">{{ $categories[$document->category] ?? $document->category }}</div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label text-muted">Version</label>
                                            <div class="fw-semibold">{{ $document->version }}</div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label text-muted">Template</label>
                                            <div class="fw-semibold">
                                                @if($document->is_template)
                                                    <span class="badge bg-success">Yes</span>
                                                @else
                                                    <span class="badge bg-secondary">No</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label text-muted">Status</label>
                                            <div>
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
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label text-muted">Created By</label>
                                            <div class="fw-semibold">{{ $document->creator->name ?? 'N/A' }}</div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label text-muted">Created At</label>
                                            <div class="fw-semibold">{{ $document->created_at->format('M d, Y H:i') }}</div>
                                        </div>
                                        @if($document->approved_at)
                                        <div class="mb-3">
                                            <label class="form-label text-muted">Approved At</label>
                                            <div class="fw-semibold">{{ $document->approved_at->format('M d, Y H:i') }}</div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Description -->
                            @if($document->description)
                            <div class="mb-4">
                                <h6 class="text-muted mb-3">Description</h6>
                                <p class="text-muted">{{ $document->description }}</p>
                            </div>
                            @endif

                            <!-- Content -->
                            @if($document->content)
                            <div class="mb-4">
                                <h6 class="text-muted mb-3">Content</h6>
                                <pre class="bg-light p-3 rounded">{{ json_encode($document->content, JSON_PRETTY_PRINT) }}</pre>
                            </div>
                            @endif

                            <!-- File Attachment -->
                            @if($document->file_path)
                            <div class="mb-4">
                                <h6 class="text-muted mb-3">File Attachment</h6>
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-file-earmark-text fs-4 text-primary me-3"></i>
                                                <div>
                                                    <div class="fw-semibold">{{ basename($document->file_path) }}</div>
                                                    <small class="text-muted">{{ $document->mime_type }} • {{ number_format($document->file_size / 1024, 2) }} KB</small>
                                                </div>
                                            </div>
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('super-admin.organizations.documents.download', [$organization, $document]) }}" class="btn btn-primary btn-sm">
                                                    <i class="bi bi-download me-2"></i>Download
                                                </a>
                                                <a href="{{ Storage::disk('public')->url($document->file_path) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                                    <i class="bi bi-eye me-2"></i>View
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>

                        <div class="col-md-4">
                            <!-- Approval Workflow -->
                            @if($document->approvals->isNotEmpty())
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h6 class="mb-0">Approval Workflow</h6>
                                </div>
                                <div class="card-body">
                                    <div class="timeline">
                                        @foreach($document->approvals as $approval)
                                        <div class="timeline-item mb-3">
                                            <div class="d-flex align-items-center mb-2">
                                                <div class="badge bg-{{ $approval->status === 'approved' ? 'success' : ($approval->status === 'rejected' ? 'danger' : 'warning') }} me-2">
                                                    {{ $approval->getLevelLabel() }}
                                                </div>
                                                <span class="text-muted">{{ $approval->statusLabel }}</span>
                                            </div>
                                            <div class="small text-muted">
                                                {{ $approval->approver->name ?? 'N/A' }}
                                                @if($approval->approved_at)
                                                    <br><span class="text-success">Approved: {{ $approval->approved_at->format('M d, Y H:i') }}</span>
                                                @endif
                                                @if($approval->comments)
                                                    <br><strong>Comments:</strong> {{ $approval->comments }}
                                                @endif
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- Actions -->
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Actions</h6>
                                </div>
                                <div class="card-body">
                                    <div class="d-grid gap-2">
                                        @if($document->status === 'pending_approval')
                                        <form action="{{ route('super-admin.organizations.documents.approve', [$organization, $document]) }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="approval_level" value="1">
                                            <button type="submit" class="btn btn-success" onclick="return confirm('Approve this document?')">
                                                <i class="bi bi-check-circle me-2"></i>Approve
                                            </button>
                                        </form>
                                        <form action="{{ route('super-admin.organizations.documents.reject', [$organization, $document]) }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="approval_level" value="1">
                                            <button type="submit" class="btn btn-danger" onclick="return confirm('Reject this document?')">
                                                <i class="bi bi-x-circle me-2"></i>Reject
                                            </button>
                                        </form>
                                        @endif

                                        @if($document->file_path)
                                        <a href="{{ route('super-admin.organizations.documents.download', [$organization, $document]) }}" class="btn btn-primary">
                                            <i class="bi bi-download me-2"></i>Download Document
                                        </a>
                                        @endif

                                        <form action="{{ route('super-admin.organizations.documents.destroy', [$organization, $document]) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this document?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger">
                                                <i class="bi bi-trash me-2"></i>Delete Document
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.timeline-item {
    position: relative;
    padding-left: 2rem;
}

.timeline-item::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0.5rem;
    width: 1rem;
    height: 1rem;
    border-radius: 50%;
    background-color: #dee2e6;
}

.timeline-item::after {
    content: '';
    position: absolute;
    left: 0.5rem;
    top: 1.5rem;
    bottom: -1rem;
    width: 2px;
    background-color: #dee2e6;
}

.timeline-item:last-child::after {
    display: none;
}
</style>
@endpush
