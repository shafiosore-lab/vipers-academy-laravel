@extends('layouts.admin')

@section('title', 'Letterhead Management')

@push('styles')
<style>
    .compact-header { margin-bottom: 12px; }
    .compact-header h1 { font-size: 1.25rem; margin-bottom: 0; }
    .compact-actions { display: flex; gap: 8px; flex-wrap: wrap; }
    .compact-actions .btn { font-size: 0.875rem; padding: 6px 12px; }
    .compact-card { margin-bottom: 12px; }
    .compact-card .card-header { padding: 8px 12px; }
    .compact-card .card-body { padding: 12px; }
    .compact-card h5 { font-size: 0.95rem; margin-bottom: 0; }
    .compact-table { font-size: 0.875rem; }
    .compact-table th { font-size: 0.8rem; padding: 6px 8px; }
    .compact-table td { padding: 6px 8px; vertical-align: middle; }
    .compact-badge { font-size: 0.7rem; padding: 2px 6px; }
    .compact-btn { font-size: 0.75rem; padding: 4px 8px; }
    .compact-logo { width: 60px; height: 60px; }
    .compact-org-info { font-size: 0.8rem; }
    .compact-org-info strong { font-weight: 600; }
    .compact-empty { padding: 16px; text-align: center; }
    .compact-empty i { font-size: 2rem; margin-bottom: 8px; }
    .compact-empty p { margin-bottom: 12px; font-size: 0.9rem; }
    .compact-stats { display: flex; gap: 16px; flex-wrap: wrap; margin-bottom: 8px; }
    .compact-stat { background: #f8f9fa; padding: 8px; border-radius: 6px; font-size: 0.8rem; }
    .compact-stat strong { font-weight: 600; }
    .compact-actions-row { display: flex; gap: 6px; }
    .compact-actions-row .btn { font-size: 0.75rem; padding: 4px 8px; }
    .compact-color-swatch { width: 16px; height: 16px; border-radius: 3px; border: 1px solid #ddd; display: inline-block; margin-right: 6px; }
    .compact-meta { font-size: 0.75rem; color: #6c757d; }
    .compact-title { font-size: 0.9rem; font-weight: 600; }
    .compact-excerpt { font-size: 0.75rem; color: #6c757d; margin-bottom: 0; }
    .compact-alert { font-size: 0.8rem; padding: 8px 12px; margin-bottom: 8px; }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center compact-header">
        <h1><i class="fas fa-file-alt"></i> Letterhead & Documents</h1>
        <div class="compact-actions">
            <a href="{{ route('admin.letterhead.create') }}" class="btn btn-primary compact-btn">
                <i class="fas fa-plus"></i> New Letterhead
            </a>
            <a href="{{ route('admin.letterhead.document.create') }}" class="btn btn-success compact-btn">
                <i class="fas fa-edit"></i> Create Document
            </a>
        </div>
    </div>

    <!-- Alerts -->
    @if(session('success'))
    <div class="alert alert-success compact-alert">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger compact-alert">
        {{ session('error') }}
    </div>
    @endif

    <!-- Organization Info -->
    <div class="card compact-card">
        <div class="card-header">
            <h5><i class="fas fa-building"></i> Organization Profile</h5>
        </div>
        <div class="card-body">
            <div class="d-flex align-items-center gap-3">
                <div>
                    @if($organization->logo)
                    <img src="{{ asset('storage/' . $organization->logo) }}" alt="Logo" class="compact-logo">
                    @else
                    <div class="bg-secondary text-white d-flex align-items-center justify-content-center compact-logo" style="border-radius: 6px;">
                        <i class="fas fa-building"></i>
                    </div>
                    @endif
                </div>
                <div class="flex-grow-1">
                    <div class="compact-org-info">
                        <strong>{{ $organization->name }}</strong><br>
                        <span>Email: {{ $organization->email }}</span> |
                        <span>Phone: {{ $organization->phone ?? 'N/A' }}</span> |
                        <span>Address: {{ $organization->address ?? 'N/A' }}</span>
                        @if($organization->website)
                        <br><span>Website: {{ $organization->website }}</span>
                        @endif
                    </div>
                </div>
                <div>
                    <a href="{{ route('super-admin.organizations.edit', $organization->id) }}" class="btn btn-sm btn-outline-primary compact-btn">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Letterheads -->
    <div class="card compact-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5><i class="fas fa-file-signature"></i> Letterhead Templates</h5>
            <span class="compact-meta">{{ $letterheads->count() }} templates</span>
        </div>
        <div class="card-body p-0">
            @if($letterheads->count() > 0)
            <div class="table-responsive">
                <table class="table compact-table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 25%">Name</th>
                            <th style="width: 15%">Style</th>
                            <th style="width: 15%">Align</th>
                            <th style="width: 15%">Color</th>
                            <th style="width: 10%">Default</th>
                            <th style="width: 15%">Created</th>
                            <th style="width: 5%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($letterheads as $lh)
                        <tr>
                            <td>
                                <div class="compact-title">{{ $lh->name }}</div>
                                @if($lh->is_default)
                                <span class="badge bg-primary compact-badge">Default</span>
                                @endif
                            </td>
                            <td>{{ ucfirst($lh->style) }}</td>
                            <td>{{ ucfirst($lh->header_alignment) }}</td>
                            <td>
                                <span class="compact-color-swatch" style="background-color: {{ $lh->primary_color }}"></span>
                                {{ $lh->primary_color }}
                            </td>
                            <td>
                                @if($lh->is_default)
                                <span class="badge bg-success compact-badge">Yes</span>
                                @else
                                <span class="badge bg-secondary compact-badge">No</span>
                                @endif
                            </td>
                            <td>{{ $lh->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="compact-actions-row">
                                    <a href="{{ route('admin.letterhead.edit', $lh->id) }}" class="btn btn-sm btn-primary compact-btn">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if(!$lh->is_default)
                                    <form action="{{ route('admin.letterhead.destroy', $lh->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger compact-btn" onclick="return confirm('Delete this letterhead?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="compact-empty">
                <i class="fas fa-file-alt text-muted"></i>
                <p class="mb-2">No letterhead templates yet.</p>
                <a href="{{ route('admin.letterhead.create') }}" class="btn btn-primary compact-btn">Create First Letterhead</a>
            </div>
            @endif
        </div>
    </div>

    <!-- Documents -->
    <div class="card compact-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5><i class="fas fa-file-contract"></i> Saved Documents</h5>
            <div class="d-flex gap-2">
                <span class="compact-meta">{{ $documents->count() }} documents</span>
                <a href="{{ route('admin.letterhead.documents') }}" class="btn btn-sm btn-outline-primary compact-btn">
                    View All <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            @if($documents->count() > 0)
            <div class="table-responsive">
                <table class="table compact-table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 35%">Title</th>
                            <th style="width: 15%">Status</th>
                            <th style="width: 10%">Pages</th>
                            <th style="width: 10%">Views</th>
                            <th style="width: 20%">Updated</th>
                            <th style="width: 10%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($documents->take(5) as $doc)
                        <tr>
                            <td>
                                <div class="compact-title">{{ $doc->title }}</div>
                                @if($doc->excerpt)
                                <div class="compact-excerpt">{{ $doc->excerpt }}</div>
                                @endif
                            </td>
                            <td>
                                @if($doc->status === 'published')
                                <span class="badge bg-success compact-badge">Published</span>
                                @else
                                <span class="badge bg-warning compact-badge">Draft</span>
                                @endif
                            </td>
                            <td>{{ $doc->page_count }}</td>
                            <td>{{ $doc->views }}</td>
                            <td>{{ $doc->updated_at->format('M d, Y H:i') }}</td>
                            <td>
                                <div class="compact-actions-row">
                                    <a href="{{ route('admin.letterhead.document.preview', $doc->id) }}" class="btn btn-sm btn-info compact-btn" target="_blank">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.letterhead.document.edit', $doc->id) }}" class="btn btn-sm btn-primary compact-btn">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{ route('admin.letterhead.document.download', $doc->id) }}" class="btn btn-sm btn-success compact-btn">
                                        <i class="fas fa-download"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($documents->count() > 5)
            <div class="p-2 text-center border-top">
                <a href="{{ route('admin.letterhead.documents') }}" class="btn btn-outline-primary compact-btn">
                    View All {{ $documents->count() }} Documents
                </a>
            </div>
            @endif
            @else
            <div class="compact-empty">
                <i class="fas fa-file text-muted"></i>
                <p class="mb-2">No documents yet.</p>
                <a href="{{ route('admin.letterhead.document.create') }}" class="btn btn-success compact-btn">Create First Document</a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

