@extends('layouts.academy')

{{-- SEO Meta Tags - Handle null blogItem --}}
@if(isset($blogItem) && $blogItem)
    @section('title', $blogItem->title . ' - Vipers Academy Blog')
    @section('meta_description', Str::limit(strip_tags($blogItem->content), 160))

    {{-- Open Graph Meta Tags for Social Sharing --}}
    @section('og:title', $blogItem->title . ' - Vipers Academy Blog')
    @section('og:description', Str::limit(strip_tags($blogItem->content), 200))
    @section('og:image', $blogItem->image ? asset('storage/' . $blogItem->image) : asset('assets/img/logo/vps.jpeg'))
    @section('og:url', request()->url())
    @section('og:type', 'article')
    @section('og:site_name', 'Vipers Academy')
    @section('og:locale', 'en_US')

    {{-- Twitter Card Meta Tags --}}
    @section('twitter:card', 'summary_large_image')
    @section('twitter:title', $blogItem->title . ' - Vipers Academy Blog')
    @section('twitter:description', Str::limit(strip_tags($blogItem->content), 200))
    @section('twitter:image', $blogItem->image ? asset('storage/' . $blogItem->image) : asset('assets/img/logo/vps.jpeg'))

    {{-- Article Schema Meta Tags --}}
    @section('article:published_time', $blogItem->published_at ? $blogItem->published_at->toIso8601String() : $blogItem->created_at->toIso8601String())
    @section('article:author', $blogItem->author)
    @section('article:section', $blogItem->category ?? 'General')
    @if($blogItem->tags)
        @foreach(json_decode($blogItem->tags) as $tag)
            @section('article:tag', $tag)
        @endforeach
    @endif
@else
    @section('title', 'Article Not Found - Vipers Academy Blog')
    @section('meta_description', 'The article you are looking for does not exist or has been removed.')

    {{-- Open Graph Meta Tags for Error Page --}}
    @section('og:title', 'Article Not Found - Vipers Academy Blog')
    @section('og:description', 'The article you are looking for does not exist or has been removed.')
    @section('og:image', asset('assets/img/logo/vps.jpeg'))
    @section('og:url', request()->url())
    @section('og:type', 'website')
    @section('og:site_name', 'Vipers Academy')
    @section('og:locale', 'en_US')

    {{-- Twitter Card Meta Tags --}}
    @section('twitter:card', 'summary')
    @section('twitter:title', 'Article Not Found - Vipers Academy Blog')
    @section('twitter:description', 'The article you are looking for does not exist or has been removed.')
    @section('twitter:image', asset('assets/img/logo/vps.jpeg'))
@endif

@section('content')
{{-- Error Handling: Check if blogItem exists --}}
@if(!isset($blogItem) || !$blogItem)
    {{-- Error Page --}}
    <div class="blog-error-page" style="min-height: 80vh; display: flex; align-items: center; justify-content: center; background: #f8f9fa;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="error-content text-center bg-white p-5 rounded-lg shadow-lg">
                        <div class="error-icon mb-4">
                            <i class="fas fa-exclamation-triangle text-warning" style="font-size: 4rem;"></i>
                        </div>
                        <h1 class="error-title mb-3" style="color: #212529; font-size: 1.75rem;">Article Not Found</h1>
                        @if(isset($errorMessage))
                        <p class="error-message mb-4" style="color: #6c757d;">{{ $errorMessage }}</p>
                        @else
                        <p class="error-message mb-4" style="color: #6c757d;">The article you're looking for doesn't exist or has been removed.</p>
                        @endif
                        <div class="d-flex gap-3 justify-content-center">
                            <a href="{{ route('blog') }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-arrow-left me-2"></i>Back to Blog
                            </a>
                            <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-lg">
                                <i class="fas fa-home me-2"></i>Home
                            </a>
                        </div>

                        {{-- Show available articles if any --}}
                        @if(isset($latestBlogs) && $latestBlogs->count() > 0)
                        <div class="mt-5 pt-4 border-top">
                            <h5 class="mb-3">Check out these articles instead:</h5>
                            <div class="list-group list-group-flush">
                                @foreach($latestBlogs->take(3) as $article)
                                <a href="{{ route('blog.show', $article->slug) }}" class="list-group-item list-group-item-action text-start py-3">
                                    <h6 class="mb-1">{{ $article->title }}</h6>
                                    <small class="text-muted">{{ $article->published_at ? $article->published_at->format('M d, Y') : $article->created_at->format('M d, Y') }}</small>
                                </a>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
    {{-- Blog Detail Hero Section --}}
    <section class="blog-detail-hero" style="background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); padding: 4rem 0 5rem;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    {{-- Breadcrumb Navigation --}}
                    <nav aria-label="breadcrumb" class="mb-4">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('home') }}" class="text-white-50">Home</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('blog') }}" class="text-white-50">Blog</a>
                            </li>
                            <li class="breadcrumb-item active text-white" aria-current="page">
                                {{ Str::limit($blogItem->title, 50) }}
                            </li>
                        </ol>
                    </nav>

                    {{-- Category and Meta Info --}}
                    <div class="text-center mb-4">
                        @if($blogItem->category)
                        <span class="badge bg-white text-primary mb-2" style="font-size: 0.9rem; padding: 0.5rem 1rem;">
                            <i class="fas fa-tag me-1"></i>
                            {{ $blogItem->category }}
                        </span>
                        @endif
                        <div class="text-white-50 mt-2" style="font-size: 0.9rem;">
                            <span class="me-3">
                                <i class="fas fa-calendar me-1"></i>
                                {{ $blogItem->published_at ? $blogItem->published_at->format('M d, Y') : $blogItem->created_at->format('M d, Y') }}
                            </span>
                            <span class="me-3">
                                <i class="fas fa-eye me-1"></i>
                                {{ number_format($blogItem->views) }} views
                            </span>
                            <span>
                                <i class="fas fa-clock me-1"></i>
                                {{ ceil(strlen(strip_tags($blogItem->content)) / 1000) }} min read
                            </span>
                        </div>
                    </div>

                    {{-- Article Title --}}
                    <h1 class="display-4 fw-bold text-white mb-4" style="font-size: 2.5rem; line-height: 1.3;">
                        {{ $blogItem->title }}
                    </h1>

                    {{-- Article Excerpt --}}
                    @if($blogItem->excerpt)
                    <p class="lead text-white-50 mb-4" style="font-size: 1.1rem; line-height: 1.7;">
                        {{ strip_tags($blogItem->excerpt) }}
                    </p>
                    @endif

                    {{-- Author Information --}}
                    <div class="d-flex align-items-center justify-content-center mt-4">
                        <div class="author-avatar me-3" style="width: 50px; height: 50px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-user text-white" style="font-size: 1.5rem;"></i>
                        </div>
                        <div class="text-start">
                            <div class="text-white fw-bold">{{ $blogItem->author ?? 'Vipers Academy' }}</div>
                            <small class="text-white-50">Author</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Blog Content Section --}}
    <section class="blog-content py-5" style="background: #f8f9fa;">
        <div class="container">
            <div class="row justify-content-center">
                {{-- Main Article Content --}}
                <div class="col-lg-8">
                    {{-- Featured Image --}}
                    @if($blogItem->image)
                    <div class="article-image mb-4" data-aos="fade-up">
                        <img src="{{ asset('storage/' . $blogItem->image) }}"
                             alt="{{ $blogItem->title }}"
                             class="img-fluid rounded-lg shadow-lg"
                             style="width: 100%; max-height: 500px; object-fit: cover;">
                    </div>
                    @endif

                    {{-- Article Text Content --}}
                    <div class="article-text bg-white p-4 p-lg-5 rounded-lg shadow-sm" data-aos="fade-up" data-aos-delay="100">
                        <div class="article-body" style="font-size: 1.05rem; line-height: 1.8; color: #495057;">
                            {!! nl2br(e($blogItem->content)) !!}
                        </div>

                        {{-- Tags Section --}}
                        @if($blogItem->tags && is_string($blogItem->tags) && json_decode($blogItem->tags))
                        <div class="tags-section mt-5 pt-4 border-top">
                            <h5 class="mb-3">
                                <i class="fas fa-tags me-2 text-primary"></i>Tags
                            </h5>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach(json_decode($blogItem->tags) as $tag)
                                <span class="tag-badge" style="background: #e9ecef; color: #495057; padding: 0.4rem 0.8rem; border-radius: 20px; font-size: 0.85rem;">
                                    {{ $tag }}
                                </span>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        {{-- Share Section --}}
                        <div class="share-section mt-5 pt-4 border-top">
                            <h5 class="mb-3">
                                <i class="fas fa-share-alt me-2 text-primary"></i>Share this article
                            </h5>
                            <div class="share-btns d-flex gap-2 flex-wrap">
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}"
                                   target="_blank" class="share-btn facebook"
                                   style="background: #1877f2; color: white; padding: 0.6rem 1.2rem; border-radius: 6px; text-decoration: none; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 0.5rem;">
                                    <i class="fab fa-facebook-f"></i> Facebook
                                </a>
                                <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($blogItem->title) }}"
                                   target="_blank" class="share-btn twitter"
                                   style="background: #1da1f2; color: white; padding: 0.6rem 1.2rem; border-radius: 6px; text-decoration: none; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 0.5rem;">
                                    <i class="fab fa-twitter"></i> Twitter
                                </a>
                                <a href="https://wa.me/?text={{ urlencode($blogItem->title . ' ' . request()->url()) }}"
                                   target="_blank" class="share-btn whatsapp"
                                   style="background: #25d366; color: white; padding: 0.6rem 1.2rem; border-radius: 6px; text-decoration: none; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 0.5rem;">
                                    <i class="fab fa-whatsapp"></i> WhatsApp
                                </a>
                                <a href="mailto:?subject={{ urlencode($blogItem->title) }}&body={{ urlencode(request()->url()) }}"
                                   class="share-btn email"
                                   style="background: #6c757d; color: white; padding: 0.6rem 1.2rem; border-radius: 6px; text-decoration: none; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 0.5rem;">
                                    <i class="fas fa-envelope"></i> Email
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Author Bio Card --}}
                    @if($blogItem->author)
                    <div class="author-bio-card bg-white p-4 p-lg-5 rounded-lg shadow-sm mt-4" data-aos="fade-up" data-aos-delay="200">
                        <div class="d-flex align-items-start">
                            <div class="author-avatar me-4" style="width: 80px; height: 80px; background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <i class="fas fa-user text-white" style="font-size: 2rem;"></i>
                            </div>
                            <div>
                                <h5 class="mb-2">About the Author</h5>
                                <h6 class="text-primary mb-2">{{ $blogItem->author }}</h6>
                                <p class="text-muted mb-0" style="font-size: 0.95rem;">
                                    Contributor at Vipers Academy. Passionate about football and developing young talent.
                                </p>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- Back Navigation --}}
                    <div class="back-navigation mt-4" data-aos="fade-up" data-aos-delay="300">
                        <a href="{{ route('blog') }}" class="btn btn-outline-primary btn-lg w-100" style="padding: 1rem;">
                            <i class="fas fa-arrow-left me-2"></i>Back to All Posts
                        </a>
                    </div>
                </div>

                {{-- Sidebar --}}
                <div class="col-lg-4 mt-5 mt-lg-0">
                    {{-- Related Blogs --}}
                    @if(isset($relatedBlogs) && $relatedBlogs->isNotEmpty())
                    <div class="related-blog mb-4" data-aos="fade-up" data-aos-delay="100">
                        <div class="card border-0 shadow-sm rounded-lg overflow-hidden">
                            <div class="card-header bg-primary text-white py-3">
                                <h5 class="mb-0" style="font-size: 1rem;">
                                    <i class="fas fa-link me-2"></i>Related Articles
                                </h5>
                            </div>
                            <div class="card-body p-0">
                                @foreach($relatedBlogs as $related)
                                <a href="{{ route('blog.show', $related->slug) }}" class="related-item d-flex align-items-center p-3 border-bottom" style="text-decoration: none; color: inherit; transition: background 0.3s;">
                                    @if($related->image)
                                        <img src="{{ asset('storage/' . $related->image) }}" alt="{{ $related->title }}" style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;" class="me-3">
                                    @else
                                        <div class="related-placeholder me-3" style="width: 60px; height: 60px; background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-newspaper text-white"></i>
                                        </div>
                                    @endif
                                    <div class="flex-grow-1 overflow-hidden">
                                        <h6 class="mb-1 text-truncate" style="font-size: 0.9rem; line-height: 1.4;">{{ Str::limit($related->title, 50) }}</h6>
                                        <small class="text-muted">{{ $related->published_at ? $related->published_at->format('M d, Y') : $related->created_at->format('M d, Y') }}</small>
                                    </div>
                                </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- Latest Blogs Widget --}}
                    @if(isset($latestBlogs) && $latestBlogs->isNotEmpty())
                    <div class="sidebar-card mb-4" data-aos="fade-up" data-aos-delay="200">
                        <div class="card border-0 shadow-sm rounded-lg overflow-hidden">
                            <div class="card-header bg-success text-white py-3">
                                <h5 class="mb-0" style="font-size: 1rem;">
                                    <i class="fas fa-newspaper me-2"></i>Latest Posts
                                </h5>
                            </div>
                            <div class="card-body p-0">
                                @foreach($latestBlogs as $latest)
                                <a href="{{ route('blog.show', $latest->slug) }}" class="latest-item d-flex align-items-center p-3 border-bottom" style="text-decoration: none; color: inherit; transition: background 0.3s;">
                                    @if($latest->image)
                                        <img src="{{ asset('storage/' . $latest->image) }}" alt="{{ $latest->title }}" style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;" class="me-3 flex-shrink-0">
                                    @else
                                        <div class="latest-placeholder me-3 flex-shrink-0" style="width: 60px; height: 60px; background: linear-gradient(135deg, var(--accent) 0%, #52a860 100%); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-newspaper text-white"></i>
                                        </div>
                                    @endif
                                    <div class="overflow-hidden">
                                        <h6 class="mb-1" style="font-size: 0.9rem; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">{{ Str::limit($latest->title, 45) }}</h6>
                                        <span class="latest-date" style="font-size: 0.8rem; color: #6c757d; display: flex; align-items: center; gap: 0.3rem;">
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                                <line x1="3" y1="10" x2="21" y2="10"></line>
                                            </svg>
                                            {{ $latest->published_at ? $latest->published_at->format('M d, Y') : $latest->created_at->format('M d, Y') }}
                                        </span>
                                    </div>
                                </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- Categories Widget --}}
                    @if(isset($categories) && $categories->isNotEmpty())
                    <div class="sidebar-card mb-4" data-aos="fade-up" data-aos-delay="300">
                        <div class="card border-0 shadow-sm rounded-lg overflow-hidden">
                            <div class="card-header bg-primary text-white py-3">
                                <h5 class="mb-0" style="font-size: 1rem;">
                                    <i class="fas fa-folder me-2"></i>Categories
                                </h5>
                            </div>
                            <div class="card-body p-0">
                                <div class="list-group list-group-flush">
                                    @foreach($categories as $cat)
                                    <a href="{{ route('blog') }}?category={{ urlencode($cat['name']) }}"
                                       class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3"
                                       style="border: none; transition: background 0.3s;">
                                        <span style="font-size: 0.9rem;">
                                            {{ $cat['icon'] }} {{ $cat['name'] }}
                                        </span>
                                        <span class="badge bg-primary rounded-pill">{{ $cat['count'] }}</span>
                                    </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- Newsletter Widget --}}
                    <div class="newsletter-widget bg-white p-4 rounded-lg shadow-sm" data-aos="fade-up" data-aos-delay="400" style="background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);">
                        <div class="newsletter-icon text-center mb-3">
                            <i class="fas fa-envelope-open-text text-white" style="font-size: 2.5rem;"></i>
                        </div>
                        <h5 class="text-white text-center mb-2">Stay Updated</h5>
                        <p class="text-white-50 text-center mb-3" style="font-size: 0.9rem;">Get the latest news delivered to your inbox</p>
                        <form class="newsletter-form" action="{{ route('blog.newsletter.subscribe') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <input type="email" name="email" class="form-control" placeholder="Your email address" required style="border: none; padding: 0.75rem 1rem;">
                            </div>
                            <button type="submit" class="btn btn-light w-100 fw-bold" style="color: var(--primary);">
                                <i class="fas fa-paper-plane me-2"></i>Subscribe
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif

{{-- Additional Styles --}}
<style>
    /* Hover effects for related and latest items */
    .related-item:hover,
    .latest-item:hover {
        background: #f8f9fa !important;
    }

    /* Article body typography */
    .article-body p {
        margin-bottom: 1.5rem;
    }

    .article-body h2,
    .article-body h3,
    .article-body h4 {
        margin-top: 2rem;
        margin-bottom: 1rem;
        color: #212529;
    }

    .article-body ul,
    .article-body ol {
        margin-bottom: 1.5rem;
        padding-left: 2rem;
    }

    .article-body blockquote {
        border-left: 4px solid var(--primary);
        padding-left: 1.5rem;
        margin: 1.5rem 0;
        font-style: italic;
        color: #6c757d;
    }

    /* Responsive Design */
    @media (max-width: 1024px) {
        .blog-detail-hero {
            padding: 3rem 0 4rem !important;
        }

        .display-4 {
            font-size: 2rem !important;
        }
    }

    @media (max-width: 768px) {
        .blog-detail-hero {
            padding: 2rem 0 3rem !important;
        }

        .display-4 {
            font-size: 1.75rem !important;
        }

        .article-text {
            padding: 1.5rem !important;
        }

        .author-bio-card {
            padding: 1.5rem !important;
        }

        .author-avatar {
            width: 60px !important;
            height: 60px !important;
        }

        .share-btns {
            flex-direction: column;
        }

        .share-btn {
            justify-content: center;
        }
    }

    @media (max-width: 576px) {
        .blog-detail-hero {
            padding: 1.5rem 0 2rem !important;
        }

        .display-4 {
            font-size: 1.5rem !important;
        }

        .breadcrumb {
            font-size: 0.85rem;
        }

        .article-body {
            font-size: 1rem !important;
        }
    }

    /* Animation for cards */
    .card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15) !important;
    }

    /* Breadcrumb styling */
    .breadcrumb-item + .breadcrumb-item::before {
        color: rgba(255, 255, 255, 0.5);
    }

    /* List group hover effect */
    .list-group-item:hover {
        background: #f8f9fa !important;
    }
</style>
@endsection
