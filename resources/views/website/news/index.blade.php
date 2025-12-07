@extends('layouts.academy')
@section('title', isset($pageTitle) ? $pageTitle . ' - Vipers Academy' : 'Latest News - Vipers Academy - Football News & Updates')

@section('meta_description', 'Stay updated with the latest football news, academy updates, and industry insights from Vipers Academy.')

@section('content')
@php
function getCategoryIcon($category) {
    $icons = [
        'Achievements' => '🏆',
        'Events' => '🎓',
        'Training Updates' => '🏃',
        'Announcements' => '📢',
        'Match Reports' => '⚽',
        'Player Updates' => '👤',
        'Transfer News' => '🔄',
        'General' => '📰',
        'Other' => '📄'
    ];
    return $icons[$category] ?? '📰';
}
@endphp
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        line-height: 1.6;
        color: #333;
        background: #f8f9fa;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }

    /* Header */
    .header {
        text-align: center;
        padding: 40px 20px 30px;
        margin-bottom: 40px;
    }

    .header h1 {
        font-size: 2.2em;
        color: #1a1a1a;
        margin-bottom: 10px;
        font-weight: 700;
    }

    .header p {
        font-size: 1em;
        color: #6b7280;
    }

    /* Filter Tabs */
    .filters {
        display: flex;
        gap: 10px;
        margin-bottom: 40px;
        flex-wrap: wrap;
        justify-content: center;
    }

    .filter-btn {
        padding: 10px 20px;
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        color: #6b7280;
        font-size: 0.9em;
        font-weight: 500;
        text-decoration: none;
        display: inline-block;
        transition: all 0.2s ease;
    }

    .filter-btn:hover {
        border-color: #ea1c4d;
        color: #ea1c4d;
    }

    .filter-btn.active {
        background: #ea1c4d;
        color: white;
        border-color: #ea1c4d;
    }

    /* Featured News */
    .featured-news {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 40px;
        border: 1px solid #e5e7eb;
        transition: all 0.2s ease;
    }

    .featured-news:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .featured-image {
        width: 100%;
        height: 400px;
        background: linear-gradient(135deg, #ea1c4d 0%, #c0173f 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 4em;
        position: relative;
        overflow: hidden;
    }

    .featured-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .featured-content {
        padding: 30px;
    }

    .featured-badge {
        display: inline-block;
        padding: 6px 12px;
        background: #ea1c4d;
        color: white;
        border-radius: 6px;
        font-size: 0.75em;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 15px;
    }

    .featured-content h2 {
        font-size: 1.8em;
        color: #1a1a1a;
        margin-bottom: 12px;
        font-weight: 600;
    }

    .featured-meta {
        display: flex;
        gap: 15px;
        font-size: 0.9em;
        color: #6b7280;
        margin-bottom: 15px;
    }

    .featured-content p {
        font-size: 1em;
        color: #6b7280;
        line-height: 1.6;
        margin-bottom: 20px;
    }

    .read-more {
        display: inline-block;
        padding: 10px 20px;
        background: #ea1c4d;
        color: white;
        text-decoration: none;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.9em;
        transition: all 0.2s ease;
    }

    .read-more:hover {
        background: #c0173f;
        transform: translateY(-1px);
    }

    /* News Grid */
    .news-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 25px;
        margin-bottom: 40px;
    }

    .news-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid #e5e7eb;
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .news-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        transform: translateY(-2px);
    }

    .news-image {
        width: 100%;
        height: 200px;
        background: linear-gradient(135deg, #e5e7eb 0%, #d1d5db 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3em;
        color: #9ca3af;
        position: relative;
        overflow: hidden;
    }

    .news-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .news-content {
        padding: 20px;
    }

    .news-category {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 10px;
        background: #fef2f2;
        color: #ea1c4d;
        border-radius: 6px;
        font-size: 0.75em;
        font-weight: 600;
        text-transform: uppercase;
        margin-bottom: 12px;
    }

    .news-content h3 {
        font-size: 1.2em;
        color: #1a1a1a;
        margin-bottom: 10px;
        font-weight: 600;
        line-height: 1.4;
    }

    .news-excerpt {
        font-size: 0.9em;
        color: #6b7280;
        line-height: 1.5;
        margin-bottom: 15px;
    }

    .news-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.85em;
        color: #9ca3af;
        padding-top: 15px;
        border-top: 1px solid #f1f3f5;
    }

    /* Load More Button */
    .load-more-container {
        text-align: center;
        margin-top: 40px;
        padding-bottom: 40px;
    }

    .load-more {
        padding: 12px 32px;
        background: white;
        color: #ea1c4d;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.95em;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .load-more:hover {
        border-color: #ea1c4d;
        background: #ea1c4d;
        color: white;
    }

    .load-more:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    /* No News Message */
    .no-news {
        text-align: center;
        padding: 60px 20px;
        color: #6b7280;
    }

    .no-news h3 {
        font-size: 1.5em;
        margin-bottom: 10px;
        color: #374151;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .header h1 {
            font-size: 1.8em;
        }

        .featured-image {
            height: 250px;
        }

        .featured-content h2 {
            font-size: 1.4em;
        }

        .news-grid {
            grid-template-columns: 1fr;
        }

        .filters {
            justify-content: flex-start;
        }
    }
</style>

<div class="container">
    <!-- Header -->
    <div class="header">
        <h1>{{ isset($pageTitle) ? $pageTitle : 'News & Updates' }}</h1>
        <p>Stay informed with the latest news, achievements, and announcements from Vipers Academy</p>
    </div>

    <!-- Filter Tabs -->
    <div class="filters">
        <a href="{{ route('news') }}" class="filter-btn {{ request()->routeIs('news') ? 'active' : '' }}">All News</a>
        <a href="{{ route('achievements') }}" class="filter-btn {{ request()->routeIs('achievements') ? 'active' : '' }}">Achievements</a>
        <a href="{{ route('events') }}" class="filter-btn {{ request()->routeIs('events') ? 'active' : '' }}">Events</a>
        <a href="{{ route('training-updates') }}" class="filter-btn {{ request()->routeIs('training-updates') ? 'active' : '' }}">Training Updates</a>
        <a href="{{ route('announcements') }}" class="filter-btn {{ request()->routeIs('announcements') ? 'active' : '' }}">Announcements</a>
    </div>

    <!-- Featured News -->
    @if(isset($mainArticle) && $mainArticle)
    <div class="featured-news">
        <div class="featured-image">
            @if($mainArticle->image)
                <img src="{{ asset('storage/' . $mainArticle->image) }}" alt="{{ $mainArticle->title }}">
            @else
                ⚽
            @endif
        </div>
        <div class="featured-content">
            <span class="featured-badge">{{ $mainArticle->category ?? 'News' }}</span>
            <h2>{{ $mainArticle->title }}</h2>
            <div class="featured-meta">
                <span>📅 {{ $mainArticle->created_at->format('M d, Y') }}</span>
                <span>{{ getCategoryIcon($mainArticle->category ?? 'General') }} {{ $mainArticle->category ?? 'General' }}</span>
            </div>
            <p>{{ Str::limit(strip_tags($mainArticle->content), 200) }}</p>
            <a href="{{ route('news.show', $mainArticle->id) }}" class="read-more">Read Full Story</a>
        </div>
    </div>
    @endif

    <!-- News Grid -->
    @if(isset($articles) && $articles->count() > 0)
    <div class="news-grid" id="news-grid">
        @foreach($articles as $article)
        <div class="news-card" onclick="window.location.href='{{ route('news.show', $article->id) }}'">
            <div class="news-image">
                @if($article->image)
                    <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->title }}">
                @else
                    {{ getCategoryIcon($article->category ?? 'General') }}
                @endif
            </div>
            <div class="news-content">
                <span class="news-category">
                    {{ getCategoryIcon($article->category ?? 'General') }}
                    {{ $article->category ?? 'General' }}
                </span>
                <h3>{{ $article->title }}</h3>
                <p class="news-excerpt">{{ Str::limit(strip_tags($article->content), 150) }}</p>
                <div class="news-meta">
                    <span>{{ $article->created_at->format('M d, Y') }}</span>
                    <span>→</span>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Load More -->
    @if(isset($hasMoreArticles) && $hasMoreArticles)
    <div class="load-more-container">
        <button class="load-more" id="load-more-btn" data-page="2">Load More News</button>
    </div>
    @endif
    @else
    <div class="no-news">
        <h3>No news articles found</h3>
        <p>Check back later for the latest updates from Vipers Academy.</p>
    </div>
    @endif
</div>

<script>
    // Category icon mapping
    function getCategoryIcon(category) {
        const icons = {
            'Achievements': '🏆',
            'Events': '🎓',
            'Training Updates': '🏃',
            'Announcements': '📢',
            'Match Reports': '⚽',
            'Player Updates': '👤',
            'Transfer News': '🔄',
            'General': '📰',
            'Other': '📄'
        };
        return icons[category] || '📰';
    }

    // Set active filter based on current page
    document.addEventListener('DOMContentLoaded', function() {
        const currentPath = window.location.pathname;
        const filterBtns = document.querySelectorAll('.filter-btn');

        filterBtns.forEach(btn => {
            if (btn.getAttribute('href') === window.location.href) {
                btn.classList.add('active');
            } else {
                btn.classList.remove('active');
            }
        });

        // Load more functionality
        const loadMoreBtn = document.getElementById('load-more-btn');
        if (loadMoreBtn) {
            loadMoreBtn.addEventListener('click', function() {
                const page = parseInt(this.getAttribute('data-page'));
                const btn = this;

                // Disable button and show loading
                btn.disabled = true;
                btn.textContent = 'Loading...';

                // Get current category from URL
                const urlParams = new URLSearchParams(window.location.search);
                const category = urlParams.get('category') || '';

                // AJAX request to load more news
                fetch(`/api/news?page=${page}&category=${category}`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.articles && data.articles.length > 0) {
                        const newsGrid = document.getElementById('news-grid');

                        data.articles.forEach(article => {
                            const card = document.createElement('div');
                            card.className = 'news-card';
                            card.onclick = () => window.location.href = `/news/${article.id}`;

                            card.innerHTML = `
                                <div class="news-image">
                                    ${article.image ?
                                        `<img src="/storage/${article.image}" alt="${article.title}">` :
                                        getCategoryIcon(article.category || 'General')
                                    }
                                </div>
                                <div class="news-content">
                                    <span class="news-category">
                                        ${getCategoryIcon(article.category || 'General')}
                                        ${article.category || 'General'}
                                    </span>
                                    <h3>${article.title}</h3>
                                    <p class="news-excerpt">${article.excerpt}</p>
                                    <div class="news-meta">
                                        <span>${new Date(article.created_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}</span>
                                        <span>→</span>
                                    </div>
                                </div>
                            `;

                            newsGrid.appendChild(card);
                        });

                        // Update page number
                        btn.setAttribute('data-page', page + 1);

                        // Hide button if no more articles
                        if (!data.has_more) {
                            btn.style.display = 'none';
                        }
                    } else {
                        btn.style.display = 'none';
                    }
                })
                .catch(error => {
                    console.error('Error loading more news:', error);
                })
                .finally(() => {
                    btn.disabled = false;
                    btn.textContent = 'Load More News';
                });
            });
        }
    });
</script>
@endsection
