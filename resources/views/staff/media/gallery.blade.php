@extends('layouts.staff')

@section('title', 'Gallery Management - Media Officer - Vipers Academy')

@section('content')
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="mb-0">Gallery Management</h2>
                            <p class="mb-0">Manage photos and videos</p>
                        </div>
                        <a href="{{ route('media.dashboard') }}" class="btn btn-light">
                            <i class="fas fa-arrow-left"></i> Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gallery List -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if($gallery->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Type</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($gallery as $item)
                                        <tr>
                                            <td>
                                                <strong>{{ $item->title }}</strong>
                                                @if($item->media_type === 'image')
                                                    <i class="fas fa-image text-muted ms-1"></i>
                                                @else
                                                    <i class="fas fa-video text-muted ms-1"></i>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $item->media_type === 'image' ? 'success' : 'info' }}">
                                                    {{ ucfirst($item->media_type) }}
                                                </span>
                                            </td>
                                            <td>{{ $item->created_at->format('M j, Y') }}</td>
                                            <td>
                                                <a href="{{ route('admin.gallery.show', $item->id) }}" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-eye"></i> View
                                                </a>
                                                <a href="{{ route('admin.gallery.edit', $item->id) }}" class="btn btn-sm btn-warning">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $gallery->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-images text-muted display-1"></i>
                            <h3 class="mt-3">No Gallery Items Yet</h3>
                            <p class="text-muted">Start uploading photos and videos.</p>
                            <a href="{{ route('admin.gallery.create') }}" class="btn btn-primary mt-2">
                                <i class="fas fa-plus"></i> Add Gallery Item
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
