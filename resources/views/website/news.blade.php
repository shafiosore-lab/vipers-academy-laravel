@extends('layouts.academy')
@section('title', 'Latest News - Vipers Academy - Football News & Updates')

@section('meta_description', 'Stay updated with the latest football news, academy updates, and industry insights from
Vipers Academy.')

@section('content')

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vipers Academy - News & Updates</title>
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
            cursor: pointer;
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
        }

        .news-content {
            padding: 20px;
        }

        .news-category {
            display: inline-block;
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
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>News & Updates</h1>
            <p>Stay informed with the latest news, achievements, and announcements from Vipers Academy</p>
        </div>

        <!-- Filter Tabs -->
        <div class="filters">
            <button class="filter-btn active">All News</button>
            <button class="filter-btn">Achievements</button>
            <button class="filter-btn">Events</button>
            <button class="filter-btn">Training Updates</button>
            <button class="filter-btn">Announcements</button>
        </div>

        <!-- Featured News -->
        <div class="featured-news">
            <div class="featured-image">⚽</div>
            <div class="featured-content">
                <span class="featured-badge">Featured</span>
                <h2>Vipers Academy Players Selected for National Youth Team</h2>
                <div class="featured-meta">
                    <span>📅 December 1, 2024</span>
                    <span>🏆 Achievements</span>
                </div>
                <p>We are incredibly proud to announce that five of our academy players have been selected to represent Kenya in the upcoming East African Youth Championship. This milestone reflects our commitment to developing world-class talent through disciplined training and character development.</p>
                <a href="#" class="read-more">Read Full Story</a>
            </div>
        </div>

        <!-- News Grid -->
        <div class="news-grid">
            <div class="news-card">
                <div class="news-image">🎓</div>
                <div class="news-content">
                    <span class="news-category">Events</span>
                    <h3>December Holiday Camp Registration Now Open</h3>
                    <p class="news-excerpt">Join our intensive holiday training camp featuring technical skills development, tactical sessions, and friendly matches. Limited spots available.</p>
                    <div class="news-meta">
                        <span>Nov 28, 2024</span>
                        <span>→</span>
                    </div>
                </div>
            </div>

            <div class="news-card">
                <div class="news-image">🏃</div>
                <div class="news-content">
                    <span class="news-category">Training Updates</span>
                    <h3>New Sports Science Program Launched</h3>
                    <p class="news-excerpt">We've introduced advanced sports science protocols including nutrition planning, injury prevention, and performance tracking for all academy players.</p>
                    <div class="news-meta">
                        <span>Nov 25, 2024</span>
                        <span>→</span>
                    </div>
                </div>
            </div>

            <div class="news-card">
                <div class="news-image">🏆</div>
                <div class="news-content">
                    <span class="news-category">Achievements</span>
                    <h3>U-17 Team Wins Regional Championship</h3>
                    <p class="news-excerpt">Our U-17 squad secured the Nairobi Regional Championship with an impressive unbeaten run, showcasing exceptional teamwork and tactical discipline.</p>
                    <div class="news-meta">
                        <span>Nov 20, 2024</span>
                        <span>→</span>
                    </div>
                </div>
            </div>

            <div class="news-card">
                <div class="news-image">👨‍🏫</div>
                <div class="news-content">
                    <span class="news-category">Announcements</span>
                    <h3>UEFA Certified Coach Joins Coaching Staff</h3>
                    <p class="news-excerpt">We welcome Coach Michael Omondi, who brings 15 years of European coaching experience to strengthen our technical development program.</p>
                    <div class="news-meta">
                        <span>Nov 18, 2024</span>
                        <span>→</span>
                    </div>
                </div>
            </div>

            <div class="news-card">
                <div class="news-image">🤝</div>
                <div class="news-content">
                    <span class="news-category">Announcements</span>
                    <h3>Partnership with Leading Sports Equipment Brand</h3>
                    <p class="news-excerpt">Vipers Academy signs partnership deal to provide all players with premium training gear and equipment for the 2025 season.</p>
                    <div class="news-meta">
                        <span>Nov 15, 2024</span>
                        <span>→</span>
                    </div>
                </div>
            </div>

            <div class="news-card">
                <div class="news-image">🎯</div>
                <div class="news-content">
                    <span class="news-category">Training Updates</span>
                    <h3>Advanced Tactical Training Sessions Introduced</h3>
                    <p class="news-excerpt">New weekly tactical sessions focus on game intelligence, positioning, and decision-making using video analysis and match simulations.</p>
                    <div class="news-meta">
                        <span>Nov 12, 2024</span>
                        <span>→</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Load More -->
        <div class="load-more-container">
            <button class="load-more">Load More News</button>
        </div>
    </div>

    <script>
        // Filter functionality
        const filterBtns = document.querySelectorAll('.filter-btn');

        filterBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                filterBtns.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
            });
        });

        // News card click
        const newsCards = document.querySelectorAll('.news-card');

        newsCards.forEach(card => {
            card.addEventListener('click', () => {
                // Navigate to news detail page
                console.log('Navigate to news detail');
            });
        });

        // Load more functionality
        const loadMoreBtn = document.querySelector('.load-more');

        loadMoreBtn.addEventListener('click', () => {
            console.log('Load more news');
            // Add loading animation or fetch more news
        });
    </script>
</body>
</html>
@endsection
