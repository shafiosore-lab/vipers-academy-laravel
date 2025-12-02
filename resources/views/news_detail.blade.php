@extends('layouts.academy')

@section('title', $newsItem->title . ' - Vipers Academy News')

@section('meta_description', Str::limit($newsItem->content, 160))

@section('content')
    {{-- News Detail Hero Section --}}
    <section class="news-detail-hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <div data-aos="fade-right">
                        {{-- Article Metadata --}}
                        <div class="article-meta mb-3">
                            <span class="badge bg-warning text-dark fs-6 px-3 py-2 me-3">
                                {{ $newsItem->category }}
                            </span>
                            <span class="text-white-50">|</span>
                            <span class="text-white-50 ms-3">
                                <i class="fas fa-calendar me-1"></i>
                                {{ $newsItem->published_at ? $newsItem->published_at->format('M d, Y') : $newsItem->created_at->format('M d, Y') }}
                            </span>
                            <span class="text-white-50 ms-3">
                                <i class="fas fa-clock me-1"></i>
                                {{ rand(3, 8) }} min read
                            </span>
                        </div>

                        {{-- Article Title & Excerpt --}}
                        <h1 class="display-4 fw-bold text-white mb-4">
                            {{ $newsItem->title }}
                        </h1>
                        <p class="lead text-white-50 mb-4">
                            {{ Str::limit($newsItem->content, 200) }}
                        </p>

                        {{-- Author Info --}}
                        <div class="d-flex align-items-center">
                            <div class="author-avatar">
                                VA
                            </div>
                            <div>
                                <div class="text-white fw-semibold">Vipers Academy</div>
                                <small class="text-white-50">Official Publication</small>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Featured Image --}}
                <div class="col-lg-4" data-aos="fade-left">
                    <img
                        src="{{ $newsItem->image ? asset('storage/' . $newsItem->image) : 'https://images.unsplash.com/photo-1461896836934-ffe607ba8211?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80' }}"
                        alt="{{ $newsItem->title }}"
                        class="img-fluid rounded-3 shadow"
                    >
                </div>
            </div>
        </div>
    </section>

    {{-- News Content Section --}}
    <section class="news-content py-5">
        <div class="container">
            <div class="row">
                {{-- Main Content Column --}}
                <div class="col-lg-8" data-aos="fade-up">
                    {{-- Article Content --}}
                    <article class="article-content bg-white p-4 p-md-5 rounded-3 shadow-sm mb-4">
                        <div class="article-text">
                            {!! nl2br(e($newsItem->content)) !!}
                        </div>
                    </article>

                    {{-- Social Share --}}
                    <div class="social-share bg-light p-4 rounded-3 mb-4">
                        <h5 class="mb-3">Share this article</h5>
                        <div class="d-flex flex-wrap gap-2">
                            <button class="btn btn-outline-primary btn-sm" data-share="facebook">
                                <i class="fab fa-facebook-f me-1"></i>Facebook
                            </button>
                            <button class="btn btn-outline-info btn-sm" data-share="twitter">
                                <i class="fab fa-twitter me-1"></i>Twitter
                            </button>
                            <button class="btn btn-outline-success btn-sm" data-share="whatsapp">
                                <i class="fab fa-whatsapp me-1"></i>WhatsApp
                            </button>
                            <button class="btn btn-outline-secondary btn-sm" data-share="copy">
                                <i class="fas fa-link me-1"></i>Copy Link
                            </button>
                        </div>
                    </div>

                    {{-- Related News --}}
                    @if($relatedNews->isNotEmpty())
                        <div class="related-news">
                            <h4 class="mb-4">Related Articles</h4>
                            <div class="row g-3 g-md-4">
                                @foreach($relatedNews as $related)
                                    <div class="col-md-4 col-sm-6">
                                        <div class="card border-0 shadow-sm h-100">
                                            <img
                                                src="{{ $related->image ? asset('storage/' . $related->image) : 'https://images.unsplash.com/photo-1461896836934-ffe607ba8211?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80' }}"
                                                class="card-img-top"
                                                alt="{{ $related->title }}"
                                            >
                                            <div class="card-body p-3">
                                                <h6 class="card-title fw-bold mb-2">
                                                    {{ Str::limit($related->title, 60) }}
                                                </h6>
                                                <a href="{{ route('news.show', $related->id) }}" class="btn btn-primary btn-sm">
                                                    Read More
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Sidebar Column --}}
                <div class="col-lg-4">
                    {{-- Latest News Widget --}}
                    <div class="sidebar-card card border-0 shadow-sm mb-4" data-aos="fade-up" data-aos-delay="200">
                        <div class="card-header bg-primary text-white fw-bold">
                            <i class="fas fa-newspaper me-2"></i>Latest News
                        </div>
                        <div class="card-body p-0">
                            @foreach($latestNews as $latest)
                                <a href="{{ route('news.show', $latest->id) }}" class="latest-item d-flex p-3 border-bottom border-light text-decoration-none">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1 fw-semibold text-dark">
                                            {{ Str::limit($latest->title, 50) }}
                                        </h6>
                                        <small class="text-muted">
                                            {{ $latest->published_at ? $latest->published_at->diffForHumans() : $latest->created_at->diffForHumans() }}
                                        </small>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>

                    {{-- Categories Widget --}}
                    <div class="sidebar-card card border-0 shadow-sm mb-4" data-aos="fade-up" data-aos-delay="300">
                        <div class="card-header bg-success text-white fw-bold">
                            <i class="fas fa-tags me-2"></i>Categories
                        </div>
                        <div class="card-body">
                            @foreach($categories as $category)
                                <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                                    <span>{{ $category->category }}</span>
                                    <span class="badge bg-primary">{{ $category->count }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Newsletter Widget --}}
                    <div class="sidebar-card card border-0 shadow-sm" data-aos="fade-up" data-aos-delay="400">
                        <div class="card-body text-center p-4">
                            <div class="newsletter-icon mb-3">
                                <i class="fas fa-envelope-open-text fa-3x text-primary"></i>
                            </div>
                            <h5 class="fw-bold mb-3">Stay Updated</h5>
                            <p class="text-muted mb-4 small">
                                Get the latest news delivered to your inbox
                            </p>
                            <form class="newsletter-form" action="{{ route('newsletter.subscribe') }}" method="POST">
                                @csrf
                                <div class="input-group mb-3">
                                    <input
                                        type="email"
                                        name="email"
                                        class="form-control form-control-sm"
                                        placeholder="Your email"
                                        required
                                    >
                                    <button class="btn btn-primary btn-sm" type="submit">
                                        <i class="fas fa-paper-plane"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Back to News Section --}}
    <section class="back-to-news py-4 bg-light border-top">
        <div class="container text-center">
            <a href="{{ route('news') }}" class="btn btn-outline-primary btn-lg">
                <i class="fas fa-arrow-left me-2"></i>Back to All News
            </a>
        </div>
    </section>
@endsection

@push('styles')
<style>
    /* Hero Section */
    .news-detail-hero {
        position: relative;
        background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
        padding: 120px 0 60px;
        background-attachment: fixed;
        background-size: cover;
    }

    /* Author Avatar */
    .author-avatar {
        width: 50px;
        height: 50px;
        background-color: var(--bs-primary);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        font-weight: 600;
        margin-right: 1rem;
    }

    /* Article Content */
    .article-content {
        font-size: 1.1rem;
        line-height: 1.8;
    }

    .article-text p {
        margin-bottom: 1.5rem;
    }

    /* Social Share */
    .social-share .btn {
        transition: all 0.3s ease;
    }

    .social-share .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    /* Sidebar Cards */
    .sidebar-card {
        border-radius: 15px;
        overflow: hidden;
    }

    .latest-item {
        transition: background-color 0.3s ease;
    }

    .latest-item:hover {
        background-color: rgba(0, 0, 0, 0.05);
    }

    /* Related News Cards */
    .related-news .card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .related-news .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    .related-news .card-img-top {
        height: 150px;
        object-fit: cover;
    }

    .related-news .card-title {
        font-size: 0.9rem;
        line-height: 1.3;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .news-detail-hero {
            padding: 80px 0 40px;
        }

        .article-content {
            font-size: 1rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Social Share Functionality
    const shareButtons = document.querySelectorAll('.social-share .btn[data-share]');

    shareButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();

            const url = encodeURIComponent(window.location.href);
            const title = encodeURIComponent(document.title);
            const shareType = this.dataset.share;

            const shareUrls = {
                facebook: `https://www.facebook.com/sharer/sharer.php?u=${url}`,
                twitter: `https://twitter.com/intent/tweet?url=${url}&text=${title}`,
                whatsapp: `https://wa.me/?text=${title}%20${url}`,
            };

            if (shareType === 'copy') {
                navigator.clipboard.writeText(window.location.href).then(() => {
                    showCopySuccess(this);
                }).catch(err => {
                    console.error('Failed to copy:', err);
                });
            } else if (shareUrls[shareType]) {
                window.open(shareUrls[shareType], '_blank', 'width=600,height=400');
            }
        });
    });

    // Copy Link Success Feedback
    function showCopySuccess(button) {
        const originalHTML = button.innerHTML;
        const originalClasses = button.className;

        button.innerHTML = '<i class="fas fa-check me-1"></i>Copied!';
        button.classList.remove('btn-outline-secondary');
        button.classList.add('btn-success');

        setTimeout(() => {
            button.innerHTML = originalHTML;
            button.className = originalClasses;
        }, 2000);
    }
});
</script>
@endpush
