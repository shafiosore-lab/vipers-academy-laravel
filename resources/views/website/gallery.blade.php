@extends('layouts.academy')

@section('title', 'Gallery - Vipers Academy')

@section('meta_description', 'Browse our gallery of images and videos showcasing Vipers Academy activities, events, and achievements.')

@section('content')
<!-- Gallery Hero Section -->
<section class="gallery-hero">
    <div class="container position-relative">
        <div class="row align-items-center">
            <div class="col-lg-8" data-aos="fade-right">
                <span class="badge bg-warning text-dark fs-6 px-3 py-2 mb-3">Photo Gallery</span>
                <h1 class="display-4 fw-bold text-white mb-4">
                    Moments That <span class="text-warning">Inspire</span>
                </h1>
                <p class="lead text-white-50 mb-4 fs-5">
                    Explore our collection of images and videos capturing the spirit, dedication, and achievements of Vipers Academy.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Gallery Content -->
<section class="gallery-content py-5 bg-light">
    <div class="container">
        @if($galleries->count() > 0)
            <div class="row g-4">
                @foreach($galleries as $gallery)
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                    <div class="gallery-card card border-0 shadow-sm h-100 overflow-hidden">
                        @if($gallery->media_type === 'image')
                            <div class="gallery-image-wrapper">
                                <img src="{{ $gallery->media_url }}"
                                     class="card-img-top gallery-image"
                                     alt="{{ $gallery->title }}"
                                     onerror="this.src='https://via.placeholder.com/400x300?text=Image+Not+Found'">
                            </div>
                        @elseif($gallery->media_type === 'video')
                            <div class="gallery-video-wrapper">
                                <video class="card-img-top gallery-video" controls>
                                    <source src="{{ $gallery->media_url }}" type="video/mp4">
                                    <source src="{{ $gallery->media_url }}" type="video/webm">
                                    Your browser does not support the video tag.
                                </video>
                            </div>
                        @endif

                        <div class="card-body p-4">
                            <h6 class="card-title fw-bold mb-2">{{ $gallery->title }}</h6>
                            <div class="d-flex align-items-center justify-content-between">
                                <span class="badge bg-{{ $gallery->media_type === 'image' ? 'success' : 'info' }} text-white">
                                    <i class="fas fa-{{ $gallery->media_type === 'image' ? 'image' : 'video' }} me-1"></i>
                                    {{ ucfirst($gallery->media_type) }}
                                </span>
                                <small class="text-muted">{{ $gallery->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-5" data-aos="fade-up">
                <div class="empty-gallery">
                    <i class="fas fa-images fa-4x text-muted mb-4"></i>
                    <h3 class="text-muted mb-3">Gallery Coming Soon</h3>
                    <p class="text-muted mb-4">We're working on adding amazing photos and videos of our academy activities.</p>
                    <a href="{{ route('home') }}" class="btn btn-primary">
                        <i class="fas fa-home me-2"></i>Back to Home
                    </a>
                </div>
            </div>
        @endif
    </div>
</section>

<!-- Call to Action -->
<section class="gallery-cta py-5">
    <div class="container text-center" data-aos="fade-up">
        <h2 class="display-5 fw-bold mb-4">Want to Be Featured?</h2>
        <p class="lead mb-4 opacity-75">
            Have photos or videos from our events? We'd love to showcase them in our gallery.
        </p>
        <div class="d-flex flex-column flex-md-row gap-3 justify-content-center align-items-center">
            <a href="{{ route('contact') }}" class="btn btn-light btn-lg px-5 py-3 fw-semibold">
                <i class="fas fa-envelope me-2"></i>Contact Us
            </a>
            <a href="mailto:gallery@vipersacademy.com" class="btn btn-outline-light btn-lg px-5 py-3 fw-semibold">
                <i class="fas fa-camera me-2"></i>Submit Media
            </a>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
.gallery-hero {
    position: relative;
    overflow: hidden;
    background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 50%, #0f3460 100%);
    min-height: 50vh;
    padding: 100px 0 80px;
    background-attachment: fixed;
    background-size: cover;
}

.gallery-card {
    transition: all 0.3s ease;
    border-radius: 15px;
    overflow: hidden;
}

.gallery-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
}

.gallery-image-wrapper,
.gallery-video-wrapper {
    position: relative;
    height: 250px;
    overflow: hidden;
}

.gallery-image,
.gallery-video {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.gallery-card:hover .gallery-image,
.gallery-card:hover .gallery-video {
    transform: scale(1.05);
}

.gallery-cta {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    position: relative;
}

.gallery-cta::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="2" fill="rgba(255,255,255,0.1)"/></svg>') repeat;
    opacity: 0.1;
}

.empty-gallery {
    max-width: 500px;
    margin: 0 auto;
}

/* Responsive Design */
@media (max-width: 768px) {
    .gallery-hero {
        min-height: 40vh;
        padding: 80px 0 60px;
    }

    .gallery-image-wrapper,
    .gallery-video-wrapper {
        height: 200px;
    }
}
</style>
@endpush
