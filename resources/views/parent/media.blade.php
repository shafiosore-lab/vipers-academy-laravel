@extends('layouts.staff')

@section('title', 'Media Gallery')

@section('content')
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">Media Gallery</h2>
        <p class="text-muted mb-0">Photos and videos from the academy</p>
    </div>
</div>

<!-- Photo Gallery -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white border-0 py-3">
        <h5 class="mb-0">Photo Gallery</h5>
    </div>
    <div class="card-body">
        @if($galleries && $galleries->count() > 0)
        <div class="row g-3">
            @foreach($galleries as $gallery)
            <div class="col-6 col-md-4 col-lg-3">
                <div class="position-relative rounded overflow-hidden" style="aspect-ratio: 1;">
                    @if($gallery->image_path)
                    <img src="{{ asset('storage/' . $gallery->image_path) }}"
                         alt="{{ $gallery->title ?? 'Gallery Image' }}"
                         class="w-100 h-100 object-fit-cover">
                    @else
                    <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-light">
                        <i class="fas fa-image text-muted fs-1"></i>
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-5 text-muted">
            <i class="fas fa-images fs-1 mb-2 d-block opacity-25"></i>
            <p class="mb-0">No photos in gallery yet.</p>
        </div>
        @endif
    </div>
</div>

<!-- Videos Section -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white border-0 py-3">
        <h5 class="mb-0">Videos</h5>
    </div>
    <div class="card-body">
        <div class="row g-4">
            <div class="col-md-6">
                <div class="ratio ratio-16x9 bg-dark rounded">
                    <iframe src="https://www.youtube.com/embed/dQw4w9WgXcQ"
                            title="Academy Highlights"
                            allowfullscreen>
                    </iframe>
                </div>
            </div>
            <div class="col-md-6">
                <div class="h-100 d-flex align-items-center justify-content-center bg-light rounded">
                    <div class="text-center text-muted p-4">
                        <i class="fas fa-video fs-1 mb-2 d-block opacity-25"></i>
                        <p class="mb-0">More videos coming soon</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Latest News -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 py-3">
        <h5 class="mb-0">Latest News</h5>
    </div>
    <div class="card-body">
        @if($blogs && $blogs->count() > 0)
        <div class="list-group list-group-flush">
            @foreach($blogs as $blog)
            <div class="list-group-item px-0 py-3">
                <div class="d-flex gap-3">
                    @if($blog->image)
                    <img src="{{ asset('storage/' . $blog->image) }}"
                         alt="{{ $blog->title }}"
                         class="rounded flex-shrink-0"
                         style="width: 80px; height: 80px; object-fit: cover;">
                    @else
                    <div class="bg-primary bg-opacity-10 rounded flex-shrink-0 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                        <i class="fas fa-newspaper text-primary fs-3"></i>
                    </div>
                    @endif
                    <div class="flex-grow-1">
                        <h6 class="mb-1">{{ $blog->title }}</h6>
                        <p class="text-muted mb-2 small text-truncate">{{ Str::limit($blog->content, 100) }}</p>
                        <small class="text-muted">
                            <i class="fas fa-calendar-alt me-1"></i>
                            {{ $blog->published_at->format('M d, Y') }}
                            @if($blog->author)
                            <span class="ms-2"><i class="fas fa-user me-1"></i>{{ $blog->author->name }}</span>
                            @endif
                        </small>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-5 text-muted">
            <i class="fas fa-newspaper fs-1 mb-2 d-block opacity-25"></i>
            <p class="mb-0">No news articles available.</p>
        </div>
        @endif
    </div>
</div>
@endsection
