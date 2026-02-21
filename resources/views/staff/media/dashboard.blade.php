@extends('layouts.staff')

@section('title', 'Media Officer Dashboard - Vipers Academy')

@section('content')
    <!-- Welcome Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="mb-0">Media Officer Dashboard</h2>
                            <p class="mb-0">Welcome back, {{ auth()->user()->name }}!</p>
                        </div>
                        <div class="text-end">
                            <p class="mb-0">{{ now()->format('l, F j, Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="display-4 text-primary">{{ $totalBlogs ?? 0 }}</div>
                    <p class="text-muted mb-0">Total Blogs</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="display-4 text-success">{{ $publishedBlogs ?? 0 }}</div>
                    <p class="text-muted mb-0">Published</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="display-4 text-warning">{{ $draftBlogs ?? 0 }}</div>
                    <p class="text-muted mb-0">Drafts</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="display-4 text-info">{{ $totalGallery ?? 0 }}</div>
                    <p class="text-muted mb-0">Gallery Items</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Recent Blogs -->
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Recent Blogs</h5>
                    <a href="{{ route('media.blogs') }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body">
                    @if(isset($recentBlogs) && $recentBlogs->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($recentBlogs as $blog)
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ Str::limit($blog->title, 40) }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $blog->category }}</small>
                                    </div>
                                    @if($blog->published_at)
                                        <span class="badge bg-success">Published</span>
                                    @else
                                        <span class="badge bg-warning">Draft</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted text-center py-4">No blog posts yet</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Gallery -->
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Recent Gallery</h5>
                </div>
                <div class="card-body">
                    @if(isset($recentGallery) && $recentGallery->count() > 0)
                        <div class="row g-2">
                            @foreach($recentGallery as $item)
                                <div class="col-4">
                                    <div class="bg-light rounded p-3 text-center">
                                        <i class="fas fa-image fa-2x text-muted"></i>
                                        <p class="small text-muted mb-0">{{ $item->title ?? 'Gallery Item' }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted text-center py-4">No gallery items yet</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <a href="{{ route('media.blogs.create') }}" class="btn btn-outline-primary w-100">
                                <i class="fas fa-plus-circle mb-2 d-block"></i>
                                Create Blog
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('media.blogs') }}" class="btn btn-outline-success w-100">
                                <i class="fas fa-list mb-2 d-block"></i>
                                Manage Blogs
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="#" class="btn btn-outline-warning w-100">
                                <i class="fas fa-bullhorn mb-2 d-block"></i>
                                Announcements
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('media.gallery') }}" class="btn btn-outline-info w-100">
                                <i class="fas fa-images mb-2 d-block"></i>
                                Gallery
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
