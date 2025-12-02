@extends('layouts.academy')
@section('title', 'Latest News - Vipers Academy - Football News & Updates')

@section('meta_description', 'Stay updated with the latest football news, academy updates, and industry insights from
Vipers Academy.')

@section('content')
{{-- Hero Section with Featured News --}}
<section class="news-hero">
    <div class="container position-relative">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div data-aos="fade-right">
                    {{-- Page Badge & Stats --}}
                    <div class="d-flex align-items-center mb-3">
                        <span class="badge bg-warning text-dark fs-6 px-3 py-2 me-3">
                        Latest Updates
                        </span>
                        <span class="text-white-50">|</span>
                        <span class="text-white-50 ms-3">
                            {{ $totalArticles }} Articles Published
                        </span>
                    </div>

                    {{-- Hero Title --}}
                    <h1 class="display-4 fw-bold text-white mb-4">
                        Football News & <span class="text-warning">Academy Insights</span>
                    </h1>

                    {{-- Hero Description --}}
                    <p class="lead text-white-50 mb-4 fs-5">
                        Stay ahead of the game with exclusive coverage of academy developments, player spotlights,
                        and industry-leading football insights from Kenya's premier training facility.
                    </p>

                    {{-- CTA Buttons --}}
                    <div class="d-flex flex-wrap gap-3">
                        <button class="btn btn-warning btn-lg px-4 py-3 fw-semibold" data-action="scroll-to-news">
                            <i class="fas fa-newspaper me-2"></i>Read Latest News
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Sticky News Controls Bar --}}
<div class="news-controls-sticky">
    <div class="container py-3">
        <div class="row align-items-center">
            {{-- Category Filters --}}
            <div class="col-md-6 mb-3 mb-md-0">
                <div class="d-flex align-items-center gap-3 flex-wrap">
                    <span class="fw-semibold text-dark">Filter by:</span>
                    <div class="btn-group" role="group">
                        <input type="radio" class="btn-check" name="category" id="filter-all" value="all"
                            autocomplete="off" checked>
                        <label class="btn btn-outline-primary btn-sm" for="filter-all">All</label>

                        @foreach($categories as $category)
                        <input type="radio" class="btn-check" name="category"
                            id="filter-{{ Str::slug($category->category) }}" value="{{ $category->category }}"
                            autocomplete="off">
                        <label class="btn btn-outline-primary btn-sm" for="filter-{{ Str::slug($category->category) }}">
                            {{ $category->category }}
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Sort & View Controls --}}
            <div class="col-md-6">
                <div class="d-flex align-items-center justify-content-md-end gap-3 flex-wrap">
                    <select class="form-select form-select-sm" id="sortOrder" style="width: auto;">
                        <option value="latest">Latest First</option>
                        <option value="oldest">Oldest First</option>
                        <option value="popular">Most Popular</option>
                    </select>
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-outline-secondary btn-sm active" data-view="grid">
                            <i class="fas fa-th"></i>
                        </button>
                        <button type="button" class="btn btn-outline-secondary btn-sm" data-view="list">
                            <i class="fas fa-list"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Main News Grid --}}
<section class="main-news py-5 bg-light" id="newsGrid">
    <div class="container">
        <div class="row g-4">
            {{-- Main Featured Article --}}
            <div class="col-lg-8" data-aos="fade-up">
                @if($mainArticle)
                <article class="main-article card border-0 shadow-lg overflow-hidden">
                    <div class="row g-0">
                        <div class="col-md-8">
                            <div class="card-body p-4">
                                {{-- Article Meta --}}
                                <div class="d-flex align-items-center mb-3">
                                    <span class="badge bg-primary me-2">{{ $mainArticle->category }}</span>
                                    <small class="text-muted">
                                        <i class="fas fa-calendar me-1"></i>
                                        {{ $mainArticle->published_at ? $mainArticle->published_at->format('M d, Y') : $mainArticle->created_at->format('M d, Y') }}
                                        <i class="fas fa-clock ms-2 me-1"></i>
                                        {{ $mainArticle->read_time ?? rand(3, 8) }} min read
                                    </small>
                                </div>

                                {{-- Article Title & Excerpt --}}
                                <h3 class="card-title fw-bold mb-2">{{ $mainArticle->title }}</h3>
                                <p class="card-text text-muted mb-4">
                                    {{ Str::limit($mainArticle->content, 150) }}
                                </p>

                                {{-- Article Footer --}}
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <div class="author-avatar me-2">VA</div>
                                        <div>
                                            <div class="fw-semibold small">Vipers Academy</div>
                                            <small class="text-muted">Official Publication</small>
                                        </div>
                                    </div>
                                    <a href="{{ route('news.show', $mainArticle->id) }}"
                                        class="btn btn-primary btn-sm px-4">
                                        Read More <i class="fas fa-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <img src="{{ $mainArticle->image ? asset('storage/' . $mainArticle->image) : asset('images/default-news.jpg') }}"
                                class="img-fluid h-100 main-article-img" alt="{{ $mainArticle->title }}">
                        </div>
                    </div>
                </article>
                @endif
            </div>

            {{-- Sidebar --}}
            <div class="col-lg-4">
                {{-- Trending News Widget --}}
                <div class="sidebar-card card border-0 shadow-sm mb-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0 fw-bold">
                            <i class=></i>Trending Now
                        </h6>
                    </div>
                    <div class="card-body p-0">
                        @foreach($trendingNews as $index => $trending)
                        <a href="{{ route('news.show', $trending->id) }}"
                            class="trending-item d-flex p-3 border-bottom border-light text-decoration-none">
                            <div class="trending-number">{{ $index + 1 }}</div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1 fw-semibold text-dark trending-title">
                                    {{ Str::limit($trending->title, 50) }}
                                </h6>
                                <small class="text-muted">
                                    {{ $trending->published_at ? $trending->published_at->diffForHumans() : $trending->created_at->diffForHumans() }}
                                </small>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- News Grid --}}
        <div class="row g-4 mt-4" id="newsArticles">
            @foreach($articles as $article)
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-category="{{ $article->category }}">
                <article class="news-card card border-0 shadow-sm h-100 overflow-hidden">
                    <div class="position-relative">
                        <img src="{{ $article->image ? asset('storage/' . $article->image) : asset('images/default-news.jpg') }}"
                            class="card-img-top news-card-img" alt="{{ $article->title }}">
                        <div class="news-category-badge">
                            <span class="badge bg-primary">{{ $article->category }}</span>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        {{-- Article Meta --}}
                        <div class="d-flex align-items-center mb-2">
                            <small class="text-muted me-3">
                                <i class="fas fa-calendar me-1"></i>
                                {{ $article->published_at ? $article->published_at->format('M d') : $article->created_at->format('M d') }}
                            </small>
                            <small class="text-muted">
                                <i class="fas fa-clock me-1"></i>
                                {{ $article->read_time ?? rand(2, 6) }} min read
                            </small>
                        </div>

                        {{-- Article Title & Excerpt --}}
                        <h6 class="card-title fw-bold mb-2 news-card-title">
                            {{ $article->title }}
                        </h6>
                        <p class="card-text text-muted mb-3 news-card-excerpt">
                            {{ Str::limit($article->content, 80) }}
                        </p>

                        {{-- Article Footer --}}
                        <div class="d-flex align-items-center justify-content-between">
                            <small class="text-muted">Vipers Academy</small>
                            <a href="{{ route('news.show', $article->id) }}"
                                class="btn btn-outline-primary btn-sm px-3">
                                Read More
                            </a>
                        </div>
                    </div>
                </article>
            </div>
            @endforeach
        </div>

        {{-- Load More Button --}}
        @if($hasMoreArticles)
        <div class="text-center mt-5" data-aos="fade-up">
            <button class="btn btn-outline-primary btn-lg px-5 py-3 fw-semibold" id="loadMoreBtn" data-page="2">
                <i class="fas fa-plus me-2"></i>Load More Articles
            </button>
        </div>
        @endif
    </div>
</section>

{{-- Footer CTA --}}
<section class="news-footer-cta py-5">
    <div class="container text-center" data-aos="fade-up">
        <h2 class="display-5 fw-bold mb-4">Want to Share Your Story?</h2>
        <p class="lead mb-4 opacity-75">
            Have news, achievements, or insights to share? We'd love to hear from you.
        </p>
        <div class="d-flex flex-column flex-md-row gap-3 justify-content-center align-items-center">
            <a href="{{ route('contact') }}" class="btn btn-light btn-lg px-5 py-3 fw-semibold">
                <i class="fas fa-envelope me-2"></i>Contact Our Team
            </a>
            <a href="mailto:news@vipersacademy.com" class="btn btn-outline-light btn-lg px-5 py-3 fw-semibold">
                <i class="fas fa-newspaper me-2"></i>Submit News Tip
            </a>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
/* Hero Section */
.news-hero {
    position: relative;
    overflow: hidden;
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
    min-height: 60vh;
    padding: 120px 0 80px;
    background-attachment: fixed;
    background-size: cover;
}

/* Featured Stats Card */
.featured-stats {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border-radius: 1rem;
    padding: 1.5rem;
    color: white;
    border: 1px solid rgba(255, 255, 255, 0.2);
}

/* Sticky Controls */
.news-controls-sticky {
    position: sticky;
    top: 80px;
    z-index: 1040;
    backdrop-filter: blur(15px);
    background: rgba(255, 255, 255, 0.95);
    border-bottom: 1px solid rgba(0, 0, 0, 0.1);
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

/* Featured Cards */
.featured-card {
    transition: all 0.3s ease;
    border-radius: 15px;
    overflow: hidden;
}

.featured-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
}

.featured-card-img {
    height: 250px;
    object-fit: cover;
}

.featured-badge {
    position: absolute;
    top: 0;
    right: 0;
    margin: 1rem;
    z-index: 2;
}

.featured-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    padding: 1.5rem;
    background: linear-gradient(transparent, rgba(0, 0, 0, 0.8));
}

/* Main Article */
.main-article {
    border-radius: 15px;
    overflow: hidden;
}

.main-article-img {
    object-fit: cover;
}

.author-avatar {
    width: 32px;
    height: 32px;
    background-color: var(--bs-primary);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.8rem;
    font-weight: bold;
}

/* News Cards */
.news-card {
    transition: all 0.3s ease;
    border-radius: 15px;
    overflow: hidden;
}

.news-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

.news-card-img {
    height: 200px;
    object-fit: cover;
}

.news-card-title {
    font-size: 0.95rem;
    line-height: 1.3;
}

.news-card-excerpt {
    font-size: 0.85rem;
}

.news-category-badge {
    position: absolute;
    top: 0;
    right: 0;
    margin: 1rem;
    z-index: 2;
}

/* Sidebar Cards */
.sidebar-card {
    border-radius: 15px;
    overflow: hidden;
}

.trending-item {
    transition: background-color 0.3s ease;
}

.trending-item:hover {
    background-color: rgba(0, 0, 0, 0.05);
}

.trending-number {
    width: 30px;
    height: 30px;
    background-color: var(--bs-warning);
    color: var(--bs-dark);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 0.8rem;
    margin-right: 1rem;
    flex-shrink: 0;
}

.trending-title {
    font-size: 0.9rem;
    line-height: 1.3;
}

/* Footer CTA */
.news-footer-cta {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    position: relative;
}

.news-footer-cta::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="2" fill="rgba(255,255,255,0.1)"/></svg>') repeat;
    opacity: 0.1;
}

/* Button Groups */
.btn-group .btn {
    border-radius: 8px !important;
    margin: 0 2px;
}

.btn-group .btn.active {
    background: var(--bs-primary);
    border-color: var(--bs-primary);
    color: white;
}

/* Swiper Customization */
.swiper-button-next,
.swiper-button-prev {
    color: var(--bs-primary);
    background: rgba(255, 255, 255, 0.9);
    border-radius: 50%;
    width: 40px;
    height: 40px;
}

.swiper-button-next::after,
.swiper-button-prev::after {
    font-size: 16px;
}

.swiper-pagination-bullet-active {
    background: var(--bs-primary);
}

/* Responsive Design */
@media (max-width: 768px) {
    .news-hero {
        min-height: 50vh;
        padding: 100px 0 60px;
    }

    .news-controls-sticky {
        position: static;
        top: auto;
    }

    .main-article .row>div:last-child {
        order: -1;
    }
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Action Handlers
    const actionHandlers = {
        'scroll-to-news': () => {
            document.getElementById('newsGrid')?.scrollIntoView({
                behavior: 'smooth'
            });
        }
    };

    // Delegate action clicks
    document.addEventListener('click', function(e) {
        const actionBtn = e.target.closest('[data-action]');
        if (actionBtn) {
            const action = actionBtn.dataset.action;
            if (actionHandlers[action]) {
                e.preventDefault();
                actionHandlers[action]();
            }
        }
    });

    // Initialize Featured News Swiper
    const featuredSwiper = new Swiper('.featuredSwiper', {
        slidesPerView: 1,
        spaceBetween: 30,
        loop: true,
        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        breakpoints: {
            768: {
                slidesPerView: 2
            },
            1024: {
                slidesPerView: 3
            },
        },
    });

    // Category Filtering
    const filterButtons = document.querySelectorAll('input[name="category"]');
    const newsArticles = document.querySelectorAll('[data-category]');

    filterButtons.forEach(button => {
        button.addEventListener('change', function() {
            const category = this.value;

            newsArticles.forEach(article => {
                if (category === 'all' || article.dataset.category === category) {
                    article.style.display = '';
                } else {
                    article.style.display = 'none';
                }
            });
        });
    });

    // View Toggle
    const viewButtons = document.querySelectorAll('[data-view]');
    const newsGrid = document.getElementById('newsArticles');

    viewButtons.forEach(button => {
        button.addEventListener('click', function() {
            viewButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');

            const view = this.dataset.view;
            if (view === 'list') {
                newsGrid.classList.add('list-view');
            } else {
                newsGrid.classList.remove('list-view');
            }
        });
    });

    // Load More Functionality
    const loadMoreBtn = document.getElementById('loadMoreBtn');
    if (loadMoreBtn) {
        loadMoreBtn.addEventListener('click', function() {
            const page = parseInt(this.dataset.page);
            const originalHTML = this.innerHTML;

            this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Loading...';
            this.disabled = true;

            // Simulate AJAX call (replace with actual implementation)
            setTimeout(() => {
                this.innerHTML = originalHTML;
                this.disabled = false;
                this.dataset.page = page + 1;
                // Load actual articles via AJAX here
            }, 1500);
        });
    }

    // Sort Order
    const sortSelect = document.getElementById('sortOrder');
    if (sortSelect) {
        sortSelect.addEventListener('change', function() {
            const order = this.value;
            // Implement sorting logic or trigger AJAX reload
            console.log('Sorting by:', order);
        });
    }
});
</script>
@endpush
