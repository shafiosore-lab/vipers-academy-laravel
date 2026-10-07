@extends('layouts.admin')
@section('title', 'Blog Details - ' . $blog->title . ' - Admin Dashboard')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card">
            <div class="card-header bg-white d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <i class="fas fa-eye fa-lg me-3 text-info"></i>
                    <div>
                        <h4 class="card-title mb-0">Blog Details</h4>
                        <small class="opacity-75">View complete blog post</small>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.blog.edit', $blog->id) }}" class="btn btn-light btn-sm">
                        <i class="fas fa-edit me-1"></i>Edit
                    </a>
                    <a href="{{ route('admin.blog.index') }}" class="btn btn-outline-light btn-sm">
                        <i class="fas fa-arrow-left me-1"></i>Back to All Posts
                    </a>
                </div>
            </div>
            <div class="card-body">
                <!-- Blog Header -->
                <div class="row mb-4">
                    <div class="col-lg-8">
                        <h1 class="display-5 fw-bold text-primary mb-3">{{ $blog->title }}</h1>
                        <div class="d-flex align-items-center text-muted mb-4">
                            <i class="fas fa-calendar-alt me-2"></i>
                            <span>Published on {{ $blog->created_at->format('F j, Y \a\t g:i A') }}</span>
                            <span class="mx-3">•</span>
                            <i class="fas fa-clock me-2"></i>
                            <span>Last updated {{ $blog->updated_at->diffForHumans() }}</span>
                        </div>
                    </div>
                    <div class="col-lg-4 text-end">
                        @if($blog->image)
                            <img src="{{ asset('storage/' . $blog->image) }}" alt="{{ $blog->title }}"
                                 class="img-fluid rounded shadow" style="max-height: 200px;">
                        @endif
                    </div>
                </div>

                <!-- Blog Meta -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="bg-light rounded p-3 text-center">
                            <i class="fas fa-folder fa-2x text-primary mb-2"></i>
                            <div class="fw-bold">{{ $blog->category }}</div>
                            <small class="text-muted">Category</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="bg-light rounded p-3 text-center">
                            <i class="fas fa-user fa-2x text-success mb-2"></i>
                            <div class="fw-bold">{{ $blog->author }}</div>
                            <small class="text-muted">Author</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="bg-light rounded p-3 text-center">
                            <i class="fas fa-eye fa-2x text-warning mb-2"></i>
                            <div class="fw-bold">{{ number_format($blog->views) }}</div>
                            <small class="text-muted">Views</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="bg-light rounded p-3 text-center">
                            <i class="fas fa-check-circle fa-2x {{ $blog->published_at ? 'text-success' : 'text-secondary' }} mb-2"></i>
                            <div class="fw-bold">{{ $blog->published_at ? 'Published' : 'Draft' }}</div>
                            <small class="text-muted">Status</small>
                        </div>
                    </div>
                </div>

                <!-- Blog Content -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body p-4">
                                <h5 class="card-title mb-3">Content</h5>
                                <div class="blog-content">
                                    {!! nl2br(e($blog->content)) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="row mt-4">
                    <div class="col-lg-12">
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.blog.edit', $blog->id) }}" class="btn btn-warning">
                                <i class="fas fa-edit me-2"></i>Edit Blog Post
                            </a>
                            <form action="{{ route('admin.blog.destroy', $blog->id) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Are you sure you want to delete this blog post?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-trash me-2"></i>Delete Blog Post
                                </button>
                            </form>
                        </div>
                        <a href="{{ route('admin.blog.index') }}" class="btn btn-secondary mt-2">
                            <i class="fas fa-arrow-left me-2"></i>Back to All Posts
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .blog-content {
        line-height: 1.8;
        font-size: 1rem;
    }

    .blog-content p {
        margin-bottom: 1.5rem;
    }
</style>
@endsection
