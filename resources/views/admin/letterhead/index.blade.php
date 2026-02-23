@extends('layouts.admin')

@section('title', 'Letterhead Management')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-file-alt"></i> Letterhead & Documents</h1>
        <div>
            <a href="{{ route('admin.letterhead.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> New Letterhead
            </a>
            <a href="{{ route('admin.letterhead.document.create') }}" class="btn btn-success">
                <i class="fas fa-edit"></i> Create Document
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif

    <!-- Organization Info Card -->
    <div class="card mb-4">
        <div class="card-header">
            <h5><i class="fas fa-building"></i> Organization Profile</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-2">
                    @if($organization->logo)
                    <img src="{{ asset('storage/' . $organization->logo) }}" alt="Logo" style="max-width: 100px; max-height: 100px;">
                    @else
                    <div class="bg-secondary text-white p-4 text-center" style="width: 100px; height: 100px; border-radius: 8px;">
                        <i class="fas fa-building fa-3x"></i>
                    </div>
                    @endif
                </div>
                <div class="col-md-10">
                    <h4>{{ $organization->name }}</h4>
                    <p class="text-muted">
                        <strong>Email:</strong> {{ $organization->email }} |
                        <strong>Phone:</strong> {{ $organization->phone ?? 'N/A' }} |
                        <strong>Address:</strong> {{ $organization->address ?? 'N/A' }}
                    </p>
                    @if($organization->website)
                    <p><strong>Website:</strong> {{ $organization->website }}</p>
                    @endif
                    <a href="{{ route('super-admin.organizations.edit', $organization->id) }}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-edit"></i> Edit Organization Profile
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Letterheads Section -->
    <div class="card mb-4">
        <div class="card-header">
            <h5><i class="fas fa-file-signature"></i> Letterhead Templates</h5>
        </div>
        <div class="card-body">
            @if($letterheads->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Style</th>
                            <th>Alignment</th>
                            <th>Color</th>
                            <th>Default</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($letterheads as $lh)
                        <tr>
                            <td>
                                <strong>{{ $lh->name }}</strong>
                                @if($lh->is_default)
                                <span class="badge bg-primary">Default</span>
                                @endif
                            </td>
                            <td>{{ ucfirst($lh->style) }}</td>
                            <td>{{ ucfirst($lh->header_alignment) }}</td>
                            <td>
                                <span style="display: inline-block; width: 20px; height: 20px; background-color: {{ $lh->primary_color }}; border-radius: 4px; border: 1px solid #ddd;"></span>
                                {{ $lh->primary_color }}
                            </td>
                            <td>
                                @if($lh->is_default)
                                <span class="badge bg-success">Yes</span>
                                @else
                                <span class="badge bg-secondary">No</span>
                                @endif
                            </td>
                            <td>{{ $lh->created_at->format('M d, Y') }}</td>
                            <td>
                                <a href="{{ route('admin.letterhead.edit', $lh->id) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if(!$lh->is_default)
                                <form action="{{ route('admin.letterhead.destroy', $lh->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this letterhead?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-4">
                <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                <p>No letterhead templates yet.</p>
                <a href="{{ route('admin.letterhead.create') }}" class="btn btn-primary">Create Your First Letterhead</a>
            </div>
            @endif
        </div>
    </div>

    <!-- Documents Section -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5><i class="fas fa-file-contract"></i> Saved Documents</h5>
            <a href="{{ route('admin.letterhead.documents') }}" class="btn btn-sm btn-outline-primary">
                View All <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        <div class="card-body">
            @if($documents->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Status</th>
                            <th>Pages (est.)</th>
                            <th>Views</th>
                            <th>Last Updated</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($documents->take(5) as $doc)
                        <tr>
                            <td>
                                <strong>{{ $doc->title }}</strong>
                                @if($doc->excerpt)
                                <p class="text-muted small mb-0">{{ $doc->excerpt }}</p>
                                @endif
                            </td>
                            <td>
                                @if($doc->status === 'published')
                                <span class="badge bg-success">Published</span>
                                @else
                                <span class="badge bg-warning">Draft</span>
                                @endif
                            </td>
                            <td>{{ $doc->page_count }}</td>
                            <td>{{ $doc->views }}</td>
                            <td>{{ $doc->updated_at->format('M d, Y H:i') }}</td>
                            <td>
                                <a href="{{ route('admin.letterhead.document.preview', $doc->id) }}" class="btn btn-sm btn-info" target="_blank">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.letterhead.document.edit', $doc->id) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="{{ route('admin.letterhead.document.download', $doc->id) }}" class="btn btn-sm btn-success">
                                    <i class="fas fa-download"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($documents->count() > 5)
            <div class="text-center mt-3">
                <a href="{{ route('admin.letterhead.documents') }}" class="btn btn-outline-primary">
                    View All {{ $documents->count() }} Documents
                </a>
            </div>
            @endif
            @else
            <div class="text-center py-4">
                <i class="fas fa-file fa-3x text-muted mb-3"></i>
                <p>No documents yet.</p>
                <a href="{{ route('admin.letterhead.document.create') }}" class="btn btn-success">Create Your First Document</a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
