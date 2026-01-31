@extends('layouts.academy')
@section('title', 'Blog & Updates - Vipers Academy')

@section('meta_description', 'Stay updated with the latest football news, academy updates, and industry insights from Vipers Academy.')

@section('content')
<div class="modern-blog-page">
    <div class="container">
        <!-- Featured Article -->
        @if(isset($mainArticle) && $mainArticle)
        <div class="featured-article" data-aos="fade-up">
            <div class="featured-grid">
                <div class="featured-image-wrapper">
                    @if($mainArticle->image)
                        <img src="{{ asset('storage/' . $mainArticle->image) }}" alt="{{ $mainArticle->title }}" class="featured-image">
                    @else
                        <div class="featured-placeholder">
                            <span class="placeholder-icon">{{ $mainArticle->category_icon ?? '📰' }}</span>
                        </div>
                    @endif
                    <div class="featured-badge">
                        <span class="badge-icon">{{ $mainArticle->category_icon ?? '📰' }}</span>
                        <span>{{ $mainArticle->category ?? 'Featured' }}</span>
                    </div>
                </div>
                <div class="featured-text">
                    <div class="featured-meta">
                        <span class="meta-date">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"/>
                                <path d="M12 6v6l4 2"/>
                            </svg>
                            {{ $mainArticle->created_at->format('M d, Y') }}
                        </span>
                        <span class="meta-dot">•</span>
                        <span class="meta-read">5 min read</span>
                    </div>
                    <h2 class="featured-title">{{ $mainArticle->title }}</h2>
                    <p class="featured-excerpt">{{ Str::limit(strip_tags($mainArticle->content), 200) }}</p>
                    <a href="{{ route('blog.show', $mainArticle->slug) }}" class="featured-cta">
                        <span>Read Full Story</span>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M5 12h14M12 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
        @endif

        <!-- Articles Grid -->
        @if(isset($articles) && $articles->count() > 0)
        <div class="articles-section">
            <div class="section-header">
                <h2 class="section-title">All Posts</h2>
                <div class="section-line"></div>
            </div>

            <div class="articles-grid" id="blog-grid">
                @foreach($articles as $article)
                <article class="article-card" data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}">
                    <a href="{{ route('blog.show', $article->slug) }}" class="card-link">
                        <div class="card-image-wrapper">
                            @if($article->image)
                                <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->title }}" class="card-image">
                            @else
                                <div class="card-placeholder">
                                    <span class="placeholder-icon">{{ $article->category_icon ?? '📰' }}</span>
                                </div>
                            @endif
                            <div class="card-overlay">
                                <span class="overlay-text">Read Article</span>
                            </div>
                        </div>
                        <div class="card-content">
                            <div class="card-category">
                                <span class="category-icon">{{ $article->category_icon ?? '📰' }}</span>
                                <span class="category-text">{{ $article->category ?? 'General' }}</span>
                            </div>
                            <h3 class="card-title">{{ $article->title }}</h3>
                            <p class="card-excerpt">{{ Str::limit(strip_tags($article->content), 120) }}</p>
                            <div class="card-footer">
                                <span class="footer-date">{{ $article->created_at->format('M d, Y') }}</span>
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="footer-arrow">
                                    <path d="M5 12h14M12 5l7 7-7 7"/>
                                </svg>
                            </div>
                        </div>
                    </a>
                </article>
                @endforeach
            </div>

            @if(isset($hasMoreArticles) && $hasMoreArticles)
            <div class="load-more-wrapper">
                <button class="load-more-btn" id="load-more-btn" data-page="2">
                    <span class="btn-text">Load More Posts</span>
                    <span class="btn-spinner" style="display: none;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="spinner">
                            <path d="M21 12a9 9 0 11-6.219-8.56"/>
                        </svg>
                    </span>
                </button>
            </div>
            @endif
        </div>
        @else
        <div class="empty-state">
            <div class="empty-icon">📝</div>
            <h3 class="empty-title">No Posts Yet</h3>
            <p class="empty-text">Check back soon for the latest updates from Vipers Academy.</p>
        </div>
        @endif
    </div>
</div>

<style>
.modern-blog-page {
    min-height: 100vh;
    background: #fafafa;
    padding: 3rem 0 4rem;
}

/* Featured Article */
.featured-article {
    background: white;
    border-radius: 24px;
    overflow: hidden;
    box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
    margin-bottom: 4rem;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.featured-article:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.1);
}

.featured-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0;
}

.featured-image-wrapper {
    position: relative;
    height: 100%;
    min-height: 400px;
    overflow: hidden;
}

.featured-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.featured-article:hover .featured-image {
    transform: scale(1.05);
}

.featured-placeholder {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #198754 0%, #146c43 100%);
    display: flex;
    align-items: center;
    justify-content: center;
}

.placeholder-icon {
    font-size: 6rem;
    opacity: 0.3;
}

.featured-badge {
    position: absolute;
    top: 1.5rem;
    left: 1.5rem;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    padding: 0.5rem 1rem;
    border-radius: 50px;
    font-weight: 600;
    font-size: 0.85rem;
    color: #198754;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.1);
}

.badge-icon {
    font-size: 1.1rem;
}

.featured-text {
    padding: 3rem;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.featured-meta {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    color: #6c757d;
    font-size: 0.9rem;
    margin-bottom: 1rem;
}

.meta-date {
    display: flex;
    align-items: center;
    gap: 0.375rem;
}

.meta-dot {
    opacity: 0.5;
}

.featured-title {
    font-size: 2rem;
    font-weight: 800;
    line-height: 1.3;
    margin: 0 0 1rem 0;
    color: #212529;
    letter-spacing: -0.02em;
}

.featured-excerpt {
    font-size: 1.1rem;
    line-height: 1.7;
    color: #6c757d;
    margin: 0 0 2rem 0;
}

.featured-cta {
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    color: #198754;
    font-weight: 700;
    font-size: 1rem;
    text-decoration: none;
    transition: gap 0.3s ease;
}

.featured-cta:hover {
    gap: 1rem;
}

.featured-cta svg {
    transition: transform 0.3s ease;
}

.featured-cta:hover svg {
    transform: translateX(4px);
}

/* Articles Section */
.articles-section {
    margin-top: 4rem;
}

.section-header {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    margin-bottom: 2.5rem;
}

.section-title {
    font-size: 1.75rem;
    font-weight: 800;
    color: #212529;
    margin: 0;
    white-space: nowrap;
}

.section-line {
    flex: 1;
    height: 2px;
    background: linear-gradient(90deg, #dee2e6 0%, transparent 100%);
}

.articles-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
    gap: 2rem;
}

/* Article Cards */
.article-card {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
    transition: all 0.3s ease;
}

.article-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 32px rgba(0, 0, 0, 0.12);
}

.card-link {
    text-decoration: none;
    color: inherit;
    display: block;
}

.card-image-wrapper {
    position: relative;
    height: 220px;
    overflow: hidden;
    background: #f8f9fa;
}

.card-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.article-card:hover .card-image {
    transform: scale(1.1);
}

.card-placeholder {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #198754 0%, #146c43 100%);
    display: flex;
    align-items: center;
    justify-content: center;
}

.card-placeholder .placeholder-icon {
    font-size: 3.5rem;
    opacity: 0.3;
}

.card-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(180deg, transparent 0%, rgba(0, 0, 0, 0.7) 100%);
    display: flex;
    align-items: flex-end;
    justify-content: center;
    padding: 1.5rem;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.article-card:hover .card-overlay {
    opacity: 1;
}

.overlay-text {
    color: white;
    font-weight: 700;
    font-size: 0.95rem;
    transform: translateY(10px);
    transition: transform 0.3s ease;
}

.article-card:hover .overlay-text {
    transform: translateY(0);
}

.card-content {
    padding: 1.75rem;
}

.card-category {
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
    background: #f8f9fa;
    padding: 0.375rem 0.875rem;
    border-radius: 50px;
    font-size: 0.8rem;
    font-weight: 600;
    color: #198754;
    margin-bottom: 1rem;
}

.category-icon {
    font-size: 1rem;
}

.card-title {
    font-size: 1.25rem;
    font-weight: 700;
    line-height: 1.4;
    margin: 0 0 0.75rem 0;
    color: #212529;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.card-excerpt {
    font-size: 0.95rem;
    line-height: 1.6;
    color: #6c757d;
    margin: 0 0 1.25rem 0;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.card-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 1rem;
    border-top: 1px solid #f0f0f0;
}

.footer-date {
    font-size: 0.85rem;
    color: #6c757d;
}

.footer-arrow {
    color: #198754;
    transition: transform 0.3s ease;
}

.article-card:hover .footer-arrow {
    transform: translateX(4px);
}

/* Load More */
.load-more-wrapper {
    text-align: center;
    margin-top: 4rem;
}

.load-more-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    background: #198754;
    color: white;
    border: none;
    padding: 1rem 2.5rem;
    border-radius: 50px;
    font-weight: 700;
    font-size: 1rem;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 16px rgba(25, 135, 84, 0.2);
}

.load-more-btn:hover:not(:disabled) {
    background: #146c43;
    transform: translateY(-2px);
    box-shadow: 0 6px 24px rgba(25, 135, 84, 0.3);
}

.load-more-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.spinner {
    animation: spin 0.8s linear infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 5rem 2rem;
    background: white;
    border-radius: 24px;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
}

.empty-icon {
    font-size: 5rem;
    margin-bottom: 1.5rem;
    opacity: 0.5;
}

.empty-title {
    font-size: 1.75rem;
    font-weight: 800;
    color: #212529;
    margin: 0 0 0.75rem 0;
}

.empty-text {
    font-size: 1.1rem;
    color: #6c757d;
    margin: 0;
}

/* Responsive Design */
@media (max-width: 1024px) {
    .featured-grid {
        grid-template-columns: 1fr;
    }

    .featured-image-wrapper {
        min-height: 300px;
    }

    .articles-grid {
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1.5rem;
    }
}

@media (max-width: 768px) {
    .modern-blog-page {
        padding: 2rem 0 3rem;
    }

    .featured-text {
        padding: 2rem;
    }

    .featured-title {
        font-size: 1.5rem;
    }

    .featured-excerpt {
        font-size: 1rem;
    }

    .articles-grid {
        grid-template-columns: 1fr;
    }

    .section-title {
        font-size: 1.5rem;
    }
}

@media (max-width: 576px) {
    .featured-text {
        padding: 1.5rem;
    }

    .card-content {
        padding: 1.5rem;
    }

    .load-more-btn {
        width: 100%;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const loadMoreBtn = document.getElementById('load-more-btn');

    if (loadMoreBtn) {
        loadMoreBtn.addEventListener('click', function() {
            const btn = this;
            const page = btn.dataset.page;
            const btnText = btn.querySelector('.btn-text');
            const btnSpinner = btn.querySelector('.btn-spinner');

            btn.disabled = true;
            btnText.style.display = 'none';
            btnSpinner.style.display = 'block';

            fetch(`/api/blog?page=${page}`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.articles && data.articles.length > 0) {
                    const blogGrid = document.getElementById('blog-grid');

                    data.articles.forEach((article, index) => {
                        const card = document.createElement('article');
                        card.className = 'article-card';
                        card.setAttribute('data-aos', 'fade-up');
                        card.setAttribute('data-aos-delay', index * 50);

                        card.innerHTML = `
                            <a href="/blog/${article.slug}" class="card-link">
                                <div class="card-image-wrapper">
                                    ${article.image ?
                                        `<img src="/storage/${article.image}" alt="${article.title}" class="card-image">` :
                                        `<div class="card-placeholder">
                                            <span class="placeholder-icon">${article.category_icon || '📰'}</span>
                                        </div>`
                                    }
                                    <div class="card-overlay">
                                        <span class="overlay-text">Read Article</span>
                                    </div>
                                </div>
                                <div class="card-content">
                                    <div class="card-category">
                                        <span class="category-icon">${article.category_icon || '📰'}</span>
                                        <span class="category-text">${article.category || 'General'}</span>
                                    </div>
                                    <h3 class="card-title">${article.title}</h3>
                                    <p class="card-excerpt">${article.excerpt}</p>
                                    <div class="card-footer">
                                        <span class="footer-date">${article.formatted_date}</span>
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="footer-arrow">
                                            <path d="M5 12h14M12 5l7 7-7 7"/>
                                        </svg>
                                    </div>
                                </div>
                            </a>
                        `;

                        blogGrid.appendChild(card);
                    });

                    if (data.has_more) {
                        btn.dataset.page = parseInt(page) + 1;
                        btn.disabled = false;
                        btnText.style.display = 'block';
                        btnSpinner.style.display = 'none';
                    } else {
                        btn.style.display = 'none';
                    }
                } else {
                    btn.style.display = 'none';
                }
            })
            .catch(error => {
                console.error('Error loading more blogs:', error);
                btn.disabled = false;
                btnText.style.display = 'block';
                btnSpinner.style.display = 'none';
            });
        });
    }
});
</script>
@endsection
