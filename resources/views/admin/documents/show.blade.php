@extends('layouts.admin')

@section('title', __('Document Details - Vipers Academy Admin'))

@push('styles')
<style>
    .document-show-container {
        background: white;
        border-radius: 12px;
        box-shadow: var(--shadow-sm);
        padding: 0;
        overflow: hidden;
    }

    .document-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 32px;
        position: relative;
    }

    .document-header:before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="80" cy="20" r="4" fill="%23ffffff" opacity="0.1"/><circle cx="60" cy="80" r="6" fill="%23ffffff" opacity="0.1"/><circle cx="20" cy="60" r="3" fill="%23ffffff" opacity="0.1"/></svg>');
        background-size: cover;
    }

    .document-title-container {
        position: relative;
        z-index: 2;
    }

    .document-icon {
        font-size: 48px;
        margin-bottom: 16px;
        opacity: 0.9;
    }

    .document-title {
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 4px;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    }

    .document-subtitle {
        font-size: 16px;
        opacity: 0.9;
        margin: 0;
    }

    .document-content {
        padding: 32px;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 24px;
        margin-bottom: 32px;
    }

    .info-card {
        background: #f8fafc;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 24px;
        transition: all 0.3s ease;
    }

    .info-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        border-color: #3b82f6;
    }

    .info-card-icon {
        font-size: 24px;
        color: #3b82f6;
        margin-right: 12px;
    }

    .info-card-title {
        font-size: 14px;
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }

    .info-card-value {
        font-size: 18px;
        font-weight: 600;
        color: #1f2937;
        line-height: 1.4;
    }

    .info-card-value.sm {
        font-size: 14px;
        font-weight: 500;
    }

    .badge-modern {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .badge-success-modern {
        background: #dcfce7;
        color: #166534;
        border: 1px solid #bbf7d0;
    }

    .badge-warning-modern {
        background: #fef3c7;
        color: #92400e;
        border: 1px solid #fde68a;
    }

    .badge-info-modern {
        background: #dbeafe;
        color: #1e40af;
        border: 1px solid #bfdbfe;
    }

    .badge-secondary-modern {
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #e2e8f0;
    }

    .document-sections {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 32px;
        margin-top: 32px;
    }

    .main-content {
        min-height: 400px;
    }

    .section-card {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 24px;
        margin-bottom: 24px;
        transition: all 0.3s ease;
    }

    .section-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .section-header {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 12px;
        border-bottom: 2px solid #f1f5f9;
    }

    .section-icon {
        font-size: 20px;
        color: #3b82f6;
        margin-right: 12px;
        width: 24px;
    }

    .section-title {
        font-size: 18px;
        font-weight: 600;
        color: #1e293b;
        margin: 0;
    }

    .file-preview-section {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 24px;
        text-align: center;
    }

    .file-icon-display {
        font-size: 64px;
        color: #9ca3af;
        margin-bottom: 16px;
        display: block;
    }

    .file-format-badge {
        display: inline-block;
        background: #3b82f6;
        color: white;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        margin-bottom: 12px;
    }

    .file-info {
        font-size: 14px;
        color: #6b7280;
        line-height: 1.5;
    }

    .file-info strong {
        color: #374151;
    }

    .action-buttons {
        display: flex;
        gap: 12px;
        margin-top: 16px;
    }

    .btn-modern {
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 500;
        font-size: 14px;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        justify-content: center;
    }

    .btn-primary-modern {
        background: #3b82f6;
        color: white;
    }

    .btn-primary-modern:hover {
        background: #2563eb;
        transform: translateY(-1px);
    }

    .btn-secondary-modern {
        background: #f8fafc;
        color: #374151;
        border: 1px solid #e5e7eb;
    }

    .btn-secondary-modern:hover {
        background: #f1f5f9;
        border-color: #d1d5db;
    }

    .btn-success-modern {
        background: #10b981;
        color: white;
    }

    .btn-success-modern:hover {
        background: #059669;
        transform: translateY(-1px);
    }

    .document-meta {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .sidebar-card {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
    }

    .metadata-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 0;
        border-bottom: 1px solid #f1f5f9;
    }

    .metadata-item:last-child {
        border-bottom: none;
    }

    .metadata-label {
        font-weight: 500;
        color: #6b7280;
        font-size: 14px;
    }

    .metadata-value {
        font-weight: 600;
        color: #374151;
        font-size: 14px;
        text-align: right;
    }

    .stats-card {
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        color: white;
        padding: 20px;
        border-radius: 12px;
    }

    .stats-title {
        font-size: 14px;
        opacity: 0.9;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }

    .stats-value {
        font-size: 24px;
        font-weight: 700;
        margin: 0;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
        margin-top: 16px;
    }

    .stats-item {
        background: rgba(255, 255, 255, 0.1);
        padding: 12px;
        border-radius: 8px;
        text-align: center;
    }

    .stats-item-label {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        opacity: 0.8;
    }

    .stats-item-value {
        font-size: 16px;
        font-weight: 600;
        display: block;
    }

    .target-role-display {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 8px;
    }

    .role-tag {
        background: #e0e7ff;
        color: #3730a3;
        padding: 4px 8px;
        border-radius: 16px;
        font-size: 12px;
        font-weight: 500;
    }

    .timeline {
        position: relative;
        padding-left: 30px;
    }

    .timeline:before {
        content: '';
        position: absolute;
        left: 15px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #e5e7eb;
    }

    .timeline-item {
        position: relative;
        margin-bottom: 20px;
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 16px;
    }

    .timeline-item:before {
        content: '';
        position: absolute;
        left: -23px;
        top: 16px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #3b82f6;
        border: 3px solid white;
        box-shadow: 0 0 0 2px #e5e7eb;
    }

    .timeline-item:last-child:before {
        background: #10b981;
    }

    .timeline-date {
        font-size: 12px;
        color: #6b7280;
        margin-bottom: 4px;
    }

    .timeline-title {
        font-size: 14px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 4px;
    }

    .timeline-desc {
        font-size: 13px;
        color: #6b7280;
        margin: 0;
    }

    .actions-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px 32px;
        background: #f8fafc;
        border-top: 1px solid #e5e7eb;
        margin: 0;
        border-radius: 0 0 12px 12px;
    }

    .action-buttons {
        display: flex;
        gap: 12px;
    }

    @media (max-width: 1024px) {
        .document-sections {
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .info-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .document-header, .document-content {
            padding: 20px;
        }

        .document-title {
            font-size: 24px;
        }

        .actions-bar {
            padding: 16px 20px;
            flex-direction: column;
            gap: 12px;
        }

        .action-buttons {
            width: 100%;
        }

        .btn-modern {
            flex: 1;
            justify-content: center;
        }

        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <div class="document-show-container">
        <!-- Document Header -->
        <div class="document-header">
            <div class="document-title-container">
                <div class="document-icon">
                    @php
                        $icons = [
                            'codes_of_conduct' => 'fa-gavel',
                            'safety_protection' => 'fa-shield-alt',
                            'academy_policies' => 'fa-university',
                            'contracts_agreements' => 'fa-file-contract',
                            'academy_information' => 'fa-info-circle',
                            'administrative' => 'fa-clipboard',
                            'training' => 'fa-graduation-cap',
                            'medical' => 'fa-plus-circle',
                            'financial' => 'fa-dollar-sign',
                            'legal' => 'fa-balance-scale'
                        ];
                    @endphp
                    <i class="fas {{ $icons[$document->category] ?? 'fa-file-alt' }}"></i>
                </div>
                <h1 class="document-title">{{ $document->title }}</h1>
                <p class="document-subtitle">{{ $document->description ?: 'No description provided' }}</p>
            </div>
        </div>

        <!-- Document Summary -->
        <div class="document-content">
            <div class="info-grid">
                <div class="info-card">
                    <div class="info-card-title">
                        <i class="fas fa-file-invoice info-card-icon"></i>
                        Document Status
                    </div>
                    <div class="info-card-value">
                        @if($document->is_active)
                            <span class="badge badge-success-modern">Active</span>
                        @else
                            <span class="badge badge-secondary-modern">Inactive</span>
                        @endif
                    </div>
                </div>

                <div class="info-card">
                    <div class="info-card-title">
                        <i class="fas fa-tags info-card-icon"></i>
                        Category
                    </div>
                    <div class="info-card-value">
                        @php
                            $categories = [
                                'codes_of_conduct' => 'Codes of Conduct',
                                'safety_protection' => 'Safety & Protection',
                                'academy_policies' => 'Academy Policies',
                                'contracts_agreements' => 'Contracts & Agreements',
                                'academy_information' => 'Academy Information',
                                'administrative' => 'Administrative',
                                'training' => 'Training Materials',
                                'medical' => 'Medical Forms',
                                'financial' => 'Financial Documents',
                                'legal' => 'Legal Documents'
                            ];
                        @endphp
                        {{ $categories[$document->category] ?? ucfirst(str_replace(['_', '-'], ' ', $document->category)) }}
                    </div>
                </div>

                <div class="info-card">
                    <div class="info-card-title">
                        <i class="fas fa-file-alt info-card-icon"></i>
                        File Information
                    </div>
                    <div class="info-card-value">{{ $document->file_name }}</div>
                    <div class="info-card-value sm">{{ number_format($document->file_size / 1024, 1) }} KB • {{ strtoupper($document->mime_type) }}</div>
                </div>

                <div class="info-card">
                    <div class="info-card-title">
                        <i class="fas fa-users info-card-icon"></i>
                        Access Requirements
                    </div>
                    <div class="info-card-value">
                        @if($document->is_mandatory)
                            <span class="badge badge-warning-modern">Mandatory</span>
                        @else
                            <span class="badge badge-info-modern">Optional</span>
                        @endif
                        @if($document->requires_signature)
                            <span class="badge badge-info-modern ms-1">Signature Required</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="document-sections">
                <div class="main-content">
                    <!-- Document Details -->
                    <div class="section-card">
                        <div class="section-header">
                            <i class="fas fa-info-circle section-icon"></i>
                            <h3 class="section-title">{{ __('Document Details') }}</h3>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="metadata-item">
                                    <span class="metadata-label">Document ID</span>
                                    <span class="metadata-value">{{ $document->document_id }}</span>
                                </div>
                                <div class="metadata-item">
                                    <span class="metadata-label">Version</span>
                                    <span class="metadata-value">{{ $document->version }}</span>
                                </div>
                                <div class="metadata-item">
                                    <span class="metadata-label">Language</span>
                                    <span class="metadata-value">{{ strtoupper($document->language) }}</span>
                                </div>
                                @if($document->subcategory)
                                <div class="metadata-item">
                                    <span class="metadata-label">Subcategory</span>
                                    <span class="metadata-value">{{ $document->subcategory }}</span>
                                </div>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <div class="metadata-item">
                                    <span class="metadata-label">Created</span>
                                    <span class="metadata-value">{{ $document->created_at->format('M d, Y H:i') }}</span>
                                </div>
                                <div class="metadata-item">
                                    <span class="metadata-label">Last Updated</span>
                                    <span class="metadata-value">{{ $document->updated_at->format('M d, Y H:i') }}</span>
                                </div>
                                @if($document->expiry_days)
                                <div class="metadata-item">
                                    <span class="metadata-label">Expiry Period</span>
                                    <span class="metadata-value">{{ $document->expiry_days }} days</span>
                                </div>
                                @else
                                <div class="metadata-item">
                                    <span class="metadata-label">Expiry Period</span>
                                    <span class="metadata-value">No expiry</span>
                                </div>
                                @endif
                            </div>
                        </div>

                        @if($document->metadata)
                            <div class="mt-4">
                                <h6 class="section-title mb-3"><i class="fas fa-database me-2"></i>Additional Metadata</h6>
                                <div class="row">
                                    <div class="col-12">
                                        <div style="background: #f8fafc; border: 1px solid #e5e7eb; border-radius: 8px; padding: 16px;">
                                            <pre style="margin: 0; font-size: 12px; color: #374151;">{{ json_encode(json_decode($document->metadata), JSON_PRETTY_PRINT) }}</pre>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- User Documents Activity -->
                    @if($document->userDocuments->count() > 0)
                    <div class="section-card">
                        <div class="section-header">
                            <i class="fas fa-history section-icon"></i>
                            <h3 class="section-title">{{ __('Recent Activity') }}</h3>
                        </div>

                        <div class="timeline">
                            @foreach($document->userDocuments->sortByDesc('created_at')->take(10) as $userDoc)
                            <div class="timeline-item">
                                <div class="timeline-date">{{ $userDoc->created_at->format('M d, Y H:i') }}</div>
                                <div class="timeline-title">
                                    {{ $userDoc->user->name ?? 'Unknown User' }} - {{ ucfirst($userDoc->status) }}
                                </div>
                                <div class="timeline-desc">
                                    @if($userDoc->accepted_at)
                                        Accepted: {{ $userDoc->accepted_at->format('M d, Y H:i') }}
                                        @if($userDoc->ip_address)
                                        • IP: {{ $userDoc->ip_address }}
                                        @endif
                                    @else
                                        Current status: {{ ucfirst($userDoc->status) }}
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>

                        @if($document->userDocuments->count() > 10)
                        <div class="mt-3 text-center">
                            <small class="text-muted">Showing 10 most recent activities. Total: {{ $document->userDocuments->count() }}</small>
                        </div>
                        @endif
                    </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div>
                    <!-- File Preview -->
                    <div class="section-card">
                        <div class="section-header">
                            <i class="fas fa-file-alt section-icon"></i>
                            <h3 class="section-title">{{ __('File Preview') }}</h3>
                        </div>

                        <div class="file-preview-section">
                            @if($document->isPdf())
                            <i class="fas fa-file-pdf file-icon-display" style="color: #dc2626;"></i>
                            @elseif(str_contains($document->mime_type, 'word') || str_contains($document->mime_type, 'document'))
                            <i class="fas fa-file-word file-icon-display" style="color: #1d4ed8;"></i>
                            @elseif(str_contains($document->mime_type, 'text'))
                            <i class="fas fa-file-alt file-icon-display" style="color: #16a34a;"></i>
                            @else
                            <i class="fas fa-file file-icon-display"></i>
                            @endif

                            <div class="file-format-badge">
                                {{ strtoupper(pathinfo($document->file_name, PATHINFO_EXTENSION)) }}
                            </div>

                            <div class="file-info">
                                <strong>{{ $document->file_name }}</strong><br>
                                {{ number_format($document->file_size / 1024, 1) }} KB<br>
                                Type: {{ $document->mime_type }}
                            </div>

                            <div class="action-buttons">
                                @if(file_exists(storage_path('app/public/' . $document->file_path)))
                                <a href="{{ route('admin.documents.download', $document) }}" class="btn btn-primary-modern">
                                    <i class="fas fa-download"></i>
                                    Download
                                </a>
                                @if($document->isPdf() || str_contains($document->mime_type, 'text'))
                                <a href="{{ route('admin.documents.preview', $document) }}" target="_blank" class="btn btn-success-modern">
                                    <i class="fas fa-eye"></i>
                                    Preview
                                </a>
                                @endif
                                @else
                                <span class="text-danger">File not found</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Access Control -->
                    <div class="section-card">
                        <div class="section-header">
                            <i class="fas fa-users section-icon"></i>
                            <h3 class="section-title">{{ __('Target Roles') }}</h3>
                        </div>

                        <div class="target-role-display">
                            @foreach($document->target_roles as $role)
                                @php
                                    $roleNames = [
                                        'player' => 'Players',
                                        'parent' => 'Parents/Guardians',
                                        'coach' => 'Coaches',
                                        'staff' => 'Staff',
                                        'admin' => 'Administrators'
                                    ];
                                @endphp
                                <span class="role-tag">{{ $roleNames[$role] ?? ucfirst($role) }}</span>
                            @endforeach
                        </div>
                    </div>

                    <!-- Document Stats -->
                    <div class="stats-card">
                        <h6 class="stats-title">{{ __('Document Statistics') }}</h6>
                        <div class="stats-value">{{ $document->userDocuments->where('status', 'signed')->count() }}</div>

                        <div class="stats-grid">
                            <div class="stats-item">
                                <span class="stats-item-label">{{ __('Views') }}</span>
                                <span class="stats-item-value">{{ $document->userDocuments->sum('download_count') ?: 0 }}</span>
                            </div>
                            <div class="stats-item">
                                <span class="stats-item-label">{{ __('Signatures') }}</span>
                                <span class="stats-item-value">{{ $document->userDocuments->where('status', 'signed')->count() }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions Bar -->
        <div class="actions-bar">
            <a href="{{ route('admin.documents.index') }}" class="btn btn-secondary-modern">
                <i class="fas fa-arrow-left"></i>
                {{ __('Back to Documents') }}
            </a>

            <div class="action-buttons">
                <a href="{{ route('admin.documents.edit', $document) }}" class="btn btn-primary-modern">
                    <i class="fas fa-edit"></i>
                    {{ __('Edit Document') }}
                </a>
                <form action="{{ route('admin.documents.destroy', $document) }}" method="POST" class="d-inline"
                      onsubmit="return confirm('{{ __('Are you sure you want to delete this document?') }}')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-secondary-modern text-danger">
                        <i class="fas fa-trash"></i>
                        {{ __('Delete') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
