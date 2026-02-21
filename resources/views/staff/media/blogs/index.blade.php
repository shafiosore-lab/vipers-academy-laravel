@extends('layouts.staff')

@section('title', 'Blog Management - Media Officer - Vipers Academy')

@section('content')
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="mb-0">Blog Management</h2>
                            <p class="mb-0">Create, edit, and manage blog posts</p>
                        </div>
                        <a href="{{ route('media.blogs.create') }}" class="btn btn-light">
                            <i class="fas fa-plus"></i> Create New Blog
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="display-4 text-primary">{{ $blogs->total() }}</div>
                    <p class="text-muted mb-0">Total Blogs</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="display-4 text-success">{{ $blogs->whereNotNull('published_at')->count() }}</div>
                    <p class="text-muted mb-0">Published</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="display-4 text-warning">{{ $blogs->whereNull('published_at')->count() }}</div>
                    <p class="text-muted mb-0">Drafts</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Blog List -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if($blogs->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Category</th>
                                        <th>Status</th>
                                        <th>Author</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($blogs as $blog)
                                        <tr>
                                            <td>
                                                <strong>{{ $blog->title }}</strong>
                                                @if($blog->image)
                                                    <i class="fas fa-image text-muted ms-1"></i>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-info">{{ $blog->category }}</span>
                                            </td>
                                            <td>
                                                @if($blog->published_at)
                                                    <span class="badge bg-success">Published</span>
                                                    <small class="d-block text-muted">{{ $blog->published_at->format('M j, Y') }}</small>
                                                @else
                                                    <span class="badge bg-warning">Draft</span>
                                                @endif
                                            </td>
                                            <td>{{ $blog->author ?? 'Admin' }}</td>
                                            <td>{{ $blog->created_at->format('M j, Y') }}</td>
                                            <td>
                                                <a href="{{ route('media.blogs.edit', $blog->id) }}" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                <a href="{{ route('blog.show', $blog->slug) }}" target="_blank" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i> View
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $blogs->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-blog text-muted display-1"></i>
                            <h3 class="mt-3">No Blog Posts Yet</h3>
                            <p class="text-muted">Start creating content for your blog.</p>
                            <a href="{{ route('media.blogs.create') }}" class="btn btn-primary mt-2">
                                <i class="fas fa-plus"></i> Create First Blog Post
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
