<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    :root {
        --mega-primary: #ea1c4d;
        --mega-primary-light: #ff2d5f;
        --mega-primary-dark: #c0173f;
        --mega-accent: #65c16e;
        --mega-bg-light: #f8fafc;
        --mega-bg-dark: #1e293b;
        --mega-text-dark: #1e293b;
        --mega-text-medium: #64748b;
        --mega-text-light: #94a3b8;
        --mega-border: #e2e8f0;
        --mega-shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.08);
        --mega-shadow-md: 0 8px 24px rgba(0, 0, 0, 0.12);
        --mega-shadow-lg: 0 20px 40px rgba(0, 0, 0, 0.16);
        --mega-radius-sm: 8px;
        --mega-radius-md: 12px;
        --mega-radius-lg: 16px;
        --mega-transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }


    /* Mega Menu Parent */
    .mega-parent {
        position: relative;
    }

    .mega-parent>a::after {
        content: '\f078';
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
        font-size: 10px;
        transition: var(--mega-transition);
        opacity: 0.6;
    }

    .mega-parent:hover>a::after {
        transform: rotate(180deg);
        opacity: 1;
    }

    /* Premium Mega Menu Container - FULL WIDTH SEAMLESS */
    .players-mega {
        position: fixed;
        top: var(--topbar-height);
        left: 0;
        right: 0;
        width: 100vw;
        height: 56.25vh;
        background: rgba(255, 255, 255, 0.98);
        padding: 0;
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
        transition: all var(--mega-transition);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
        border-top: none;
        border-bottom: none;
        z-index: 999;
        overflow: hidden;
        backdrop-filter: blur(30px);
        -webkit-backdrop-filter: blur(30px);
    }

    .mega-parent:hover .players-mega {
        opacity: 1;
        visibility: visible;
        pointer-events: auto;
    }

    .players-mega.shrink {
        top: 0;
        height: 0;
        opacity: 0;
        overflow: hidden;
    }


    /* Sticky state when clicked */
    .players-mega.sticky {
        display: block !important;
    }

    /* Overlay backdrop when sticky */
    .mega-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.3);
        z-index: 998;
        display: none;
    }

    .mega-overlay.active {
        display: block;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-30px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }


    /* Mega Container */
    .mega-container {
        width: 100vw;
        height: 100%;
        display: flex;
        flex-direction: column;
        padding: 16px 0;
        position: relative;
        z-index: 1;
    }


    /* Content Grid Layout */
    .mega-content {
        flex: 1;
        display: grid;
        grid-template-columns: 280px 1fr 280px;
        gap: 24px;
        overflow-y: auto;
        padding-bottom: 16px;
    }

    .mega-content-premium {
        padding: 20px 0;
        display: grid;
        gap: 16px;
        width: 100%;
    }

    /* Left Column - Featured Hero */
    .featured-hero-section {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .featured-hero {
        background: linear-gradient(135deg, var(--mega-primary) 0%, var(--mega-primary-dark) 100%);
        border-radius: var(--mega-radius-lg);
        padding: 32px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 20px 40px rgba(234, 28, 77, 0.3);
    }

    .featured-hero::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.15) 0%, transparent 70%);
        animation: pulse 4s ease-in-out infinite;
    }

    @keyframes pulse {

        0%,
        100% {
            transform: scale(1);
            opacity: 0.5;
        }

        50% {
            transform: scale(1.1);
            opacity: 1;
        }
    }

    .featured-hero-link {
        display: block;
        text-decoration: none;
        color: white;
        position: relative;
        z-index: 1;
    }

    .featured-badge {
        display: inline-block;
        background: rgba(255, 255, 255, 0.25);
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        margin-bottom: 16px;
        backdrop-filter: blur(10px);
    }

    .featured-hero-image {
        width: 100%;
        aspect-ratio: 1;
        border-radius: var(--mega-radius-md);
        object-fit: cover;
        border: 4px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
        margin-bottom: 20px;
        transition: var(--mega-transition);
    }

    .featured-hero-link:hover .featured-hero-image {
        transform: scale(1.05) rotate(-2deg);
        border-color: rgba(255, 255, 255, 0.5);
    }

    .featured-hero-name {
        font-size: 22px;
        font-weight: 900;
        margin-bottom: 4px;
        letter-spacing: -0.5px;
    }

    .featured-hero-position {
        font-size: 13px;
        opacity: 0.9;
        font-weight: 600;
        margin-bottom: 20px;
    }

    .featured-hero-stats {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
    }

    .hero-stat {
        background: rgba(255, 255, 255, 0.15);
        padding: 12px;
        border-radius: var(--mega-radius-sm);
        text-align: center;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .hero-stat-value {
        font-size: 20px;
        font-weight: 900;
        display: block;
        margin-bottom: 4px;
    }

    .hero-stat-label {
        font-size: 9px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        opacity: 0.9;
    }

    /* Quick Links Glass Card */
    .quick-links-card {
        background: rgba(255, 255, 255, 0.7);
        border-radius: var(--mega-radius-lg);
        padding: 20px;
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.4);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
    }

    .quick-links-title {
        font-size: 13px;
        font-weight: 800;
        color: var(--mega-primary);
        margin-bottom: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .quick-links {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .quick-links a {
        padding: 10px 14px;
        background: rgba(255, 255, 255, 0.8);
        border-radius: var(--mega-radius-sm);
        color: var(--mega-text-dark);
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        transition: var(--mega-transition);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .quick-links a:hover {
        background: white;
        color: var(--mega-primary);
        transform: translateX(4px);
        box-shadow: 0 4px 12px rgba(234, 28, 77, 0.2);
    }

    .quick-links a i {
        font-size: 10px;
        opacity: 0;
        transition: var(--mega-transition);
    }

    .quick-links a:hover i {
        opacity: 1;
    }

    /* Center Column - Categories */
    .categories-section {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
        align-content: start;
    }

    .category-card {
        background: rgba(255, 255, 255, 0.8);
        border-radius: var(--mega-radius-lg);
        padding: 24px;
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.4);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
        transition: var(--mega-transition);
    }

    .category-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.12);
        border-color: rgba(234, 28, 77, 0.3);
        background: rgba(255, 255, 255, 0.95);
    }

    .category-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 16px;
        padding-bottom: 12px;
        border-bottom: 2px solid var(--mega-bg-light);
    }

    .category-icon {
        width: 36px;
        height: 36px;
        background: linear-gradient(135deg, var(--mega-primary) 0%, var(--mega-primary-light) 100%);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 16px;
        box-shadow: 0 4px 12px rgba(234, 28, 77, 0.3);
    }

    .category-title {
        font-size: 14px;
        font-weight: 800;
        color: var(--mega-text-dark);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .category-links {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .category-links a {
        padding: 8px 12px;
        color: var(--mega-text-medium);
        text-decoration: none;
        font-size: 13px;
        font-weight: 500;
        border-radius: var(--mega-radius-sm);
        transition: var(--mega-transition);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .category-links a:hover {
        background: linear-gradient(90deg, var(--mega-bg-light) 0%, rgba(234, 28, 77, 0.05) 100%);
        color: var(--mega-primary);
        padding-left: 16px;
    }

    .category-links a::after {
        content: '\f054';
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
        font-size: 8px;
        opacity: 0;
        transition: var(--mega-transition);
    }

    .category-links a:hover::after {
        opacity: 1;
    }

    /* Right Column - Featured Players Grid */
    .featured-players-section {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 8px;
    }

    .section-title {
        font-size: 14px;
        font-weight: 800;
        color: var(--mega-text-dark);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .section-title::before {
        content: '';
        width: 4px;
        height: 18px;
        background: linear-gradient(180deg, var(--mega-primary) 0%, var(--mega-accent) 100%);
        border-radius: 2px;
    }

    .players-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 8px;
    }

    .player-card {
        background: rgba(255, 255, 255, 0.8);
        border-radius: var(--mega-radius-md);
        padding: 16px;
        text-decoration: none;
        display: flex;
        flex-direction: column;
        align-items: center;
        transition: var(--mega-transition);
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.4);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
        position: relative;
        overflow: hidden;
    }

    .player-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, var(--mega-primary) 0%, var(--mega-accent) 100%);
        transform: scaleX(0);
        transition: var(--mega-transition);
    }

    .player-card:hover::before {
        transform: scaleX(1);
    }

    .player-card:hover {
        transform: translateY(-6px) scale(1.03);
        box-shadow: 0 12px 32px rgba(234, 28, 77, 0.25);
        border-color: rgba(234, 28, 77, 0.4);
        background: white;
    }

    .player-image {
        width: 100%;
        aspect-ratio: 1;
        border-radius: var(--mega-radius-sm);
        object-fit: cover;
        border: 2px solid rgba(255, 255, 255, 0.8);
        margin-bottom: 12px;
        transition: var(--mega-transition);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
    }

    .player-card:hover .player-image {
        border-color: var(--mega-primary);
        box-shadow: 0 10px 30px rgba(234, 28, 77, 0.4);
        transform: scale(1.05);
    }

    .player-name {
        font-size: 12px;
        font-weight: 800;
        color: var(--mega-text-dark);
        text-align: center;
        margin-bottom: 4px;
        line-height: 1.2;
    }

    .player-position {
        font-size: 10px;
        color: var(--mega-text-medium);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
    }


    /* Scrollbar Styling */
    .mega-content::-webkit-scrollbar {
        width: 8px;
    }

    .mega-content::-webkit-scrollbar-track {
        background: rgba(0, 0, 0, 0.05);
        border-radius: 4px;
    }

    .mega-content::-webkit-scrollbar-thumb {
        background: linear-gradient(180deg, var(--mega-primary) 0%, var(--mega-accent) 100%);
        border-radius: 4px;
    }

    /* Responsive Design */
    @media (max-width: 1400px) {
        .mega-container {
            padding: 32px 40px;
        }

        .mega-content {
            grid-template-columns: 320px 1fr 300px;
            gap: 30px;
        }
    }

    @media (max-width: 1024px) {
        .players-mega {
            height: 112.5vh;
            /* Increased by another 50% */
        }

        .mega-content {
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .categories-section {
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
        }

        .players-grid {
            grid-template-columns: repeat(4, 1fr);
            gap: 6px;
        }
    }

    @media (max-width: 768px) {
        .demo-nav {
            padding: 0 20px;
        }

        .players-mega {
            top: 99px;
            height: 90vh;
            /* Increased by another 50% */
        }

        .mega-container {
            padding: 20px 16px;
        }

        .mega-content {
            gap: 16px;
            padding-bottom: 12px;
        }

        .categories-section {
            grid-template-columns: 1fr;
            gap: 8px;
        }

        .players-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 6px;
        }
    }
</style>

<!-- MEGA MENU -->
<div class="players-mega" id="players-mega-menu">
        <div class="mega-container">
            <!-- Main Content Grid -->
            <div class="mega-content">
                <!-- Left Column: Featured Hero -->
                <div class="featured-hero-section">

                    <div class="quick-links-card">
                        <h4 class="quick-links-title">Quick Links</h4>
                        <div class="quick-links">

                            <a href="{{ route('about') }}"><span>About Us</span><i class="fas fa-arrow-right"></i></a>
                            <a href="{{ route('news') }}"><span>News & Updates</span><i
                                    class="fas fa-arrow-right"></i></a>
                            <a href="{{ route('standings') }}"><span>Standings</span><i
                                    class="fas fa-arrow-right"></i></a>
                            <a href="{{ route('careers.index') }}"><span>Careers</span><i
                                    class="fas fa-arrow-right"></i></a>
                            <a href="{{ route('news') }}"><span>Transfer News</span><i
                                    class="fas fa-arrow-right"></i></a>
                            <a href="{{ route('eliteplayers') }}"><span>Player Rankings</span><i
                                    class="fas fa-arrow-right"></i></a>
                            <a href="{{ route('eliteplayers') }}"><span>Team Rosters</span><i
                                    class="fas fa-arrow-right"></i></a>
                            <a href="{{ route('standings') }}"><span>Statistics Hub</span><i
                                    class="fas fa-arrow-right"></i></a>
                        </div>
                    </div>

                    <div class="featured-hero">
                        @if(isset($heroPlayer) && $heroPlayer)
                            <a href="{{ route('home.player.show', $heroPlayer->id) }}" class="featured-hero-link">
                                <span class="featured-badge">⚡ Player of the Month</span>
                                <img src="{{ $heroPlayer->photo ? asset('storage/' . $heroPlayer->photo) : 'https://images.unsplash.com/photo-1579952363873-27f3bade9f55?w=400&h=400&fit=crop' }}"
                                    alt="{{ $heroPlayer->name }}" class="featured-hero-image">
                                <h3 class="featured-hero-name">{{ $heroPlayer->name }}</h3>
                                <p class="featured-hero-position">{{ ucfirst($heroPlayer->position) }} • {{ $heroPlayer->age ?? 'N/A' }} years</p>
                                <div class="featured-hero-stats">
                                    <div class="hero-stat">
                                        <span class="hero-stat-value">{{ $heroPlayer->goals_scored ?? 0 }}</span>
                                        <span class="hero-stat-label">Goals</span>
                                    </div>
                                    <div class="hero-stat">
                                        <span class="hero-stat-value">{{ $heroPlayer->assists ?? 0 }}</span>
                                        <span class="hero-stat-label">Assists</span>
                                    </div>
                                    <div class="hero-stat">
                                        <span class="hero-stat-value">{{ number_format($heroPlayer->performance_rating ?? 0, 1) }}</span>
                                        <span class="hero-stat-label">Rating</span>
                                    </div>
                                </div>
                            </a>
                        @else
                            <!-- Fallback static hero -->
                            <a href="#" class="featured-hero-link">
                                <span class="featured-badge">⚡ Player of the Month</span>
                                <img src="https://images.unsplash.com/photo-1579952363873-27f3bade9f55?w=400&h=400&fit=crop"
                                    alt="Featured Player" class="featured-hero-image">
                                <h3 class="featured-hero-name">Featured Player</h3>
                                <p class="featured-hero-position">Position • Age</p>
                                <div class="featured-hero-stats">
                                    <div class="hero-stat">
                                        <span class="hero-stat-value">0</span>
                                        <span class="hero-stat-label">Goals</span>
                                    </div>
                                    <div class="hero-stat">
                                        <span class="hero-stat-value">0</span>
                                        <span class="hero-stat-label">Assists</span>
                                    </div>
                                    <div class="hero-stat">
                                        <span class="hero-stat-value">0.0</span>
                                        <span class="hero-stat-label">Rating</span>
                                    </div>
                                </div>
                            </a>
                        @endif
                    </div>


                </div>

                <!-- Center Column: Categories -->
                <div class="categories-section">
                    <div class="category-card">
                        <div class="category-header">
                            <div class="category-icon"><i class="fas fa-running"></i></div>
                            <h3 class="category-title">By Position</h3>
                        </div>
                        <div class="category-links">
                            <a href="{{ route('eliteplayers') }}?position=forward">Forwards</a>
                            <a href="{{ route('eliteplayers') }}?position=midfielder">Midfielders</a>
                            <a href="{{ route('eliteplayers') }}?position=defender">Defenders</a>
                            <a href="{{ route('eliteplayers') }}?position=goalkeeper">Goalkeepers</a>
                        </div>
                    </div>

                    <div class="category-card">
                        <div class="category-header">
                            <div class="category-icon"><i class="fas fa-globe"></i></div>
                            <h3 class="category-title">By League</h3>
                        </div>
                        <div class="category-links">
                            <a href="{{ route('standings') }}?league=premier">Premier League</a>
                            <a href="{{ route('standings') }}?league=laliga">La Liga</a>
                            <a href="{{ route('standings') }}?league=seriea">Serie A</a>
                            <a href="{{ route('standings') }}?league=bundesliga">Bundesliga</a>
                        </div>
                    </div>

                    <div class="category-card">
                        <div class="category-header">
                            <div class="category-icon"><i class="fas fa-chart-line"></i></div>
                            <h3 class="category-title">Performance</h3>
                        </div>
                        <div class="category-links">
                            <a href="{{ route('standings') }}?sort=goals">Top Scorers</a>
                            <a href="{{ route('standings') }}?sort=assists">Most Assists</a>
                            <a href="{{ route('standings') }}?sort=clean_sheets">Clean Sheets</a>
                            <a href="{{ route('eliteplayers') }}?sort=rating">Best Ratings</a>
                        </div>
                    </div>

                    <div class="category-card">
                        <div class="category-header">
                            <div class="category-icon"><i class="fas fa-star"></i></div>
                            <h3 class="category-title">Collections</h3>
                        </div>
                        <div class="category-links">
                            <a href="{{ route('eliteplayers') }}?category=rising">Rising Stars</a>
                            <a href="{{ route('eliteplayers') }}?category=legends">Legends</a>
                            <a href="{{ route('eliteplayers') }}?category=youth">Young Talents</a>
                            <a href="{{ route('eliteplayers') }}?category=free">Free Agents</a>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Featured Players -->
                <div class="featured-players-section">
                    <div class="section-header">
                        <h3 class="section-title">Featured Players</h3>
                    </div>

                    <div class="players-grid">
                        @if(isset($featuredPlayers) && $featuredPlayers->count() > 0)
                            @foreach($featuredPlayers->take(8) as $player)
                                <a href="{{ route('home.player.show', $player->id) }}" class="player-card">
                                    <img src="{{ $player->photo ? asset('storage/' . $player->photo) : 'https://images.unsplash.com/photo-1633332755192-727a05c4013d?w=200&h=200&fit=crop' }}"
                                        alt="{{ $player->name }}" class="player-image">
                                    <span class="player-name">{{ $player->name }}</span>
                                    <span class="player-position">{{ ucfirst($player->position) }}</span>
                                </a>
                            @endforeach
                        @else
                            <!-- Fallback static players if no dynamic data -->
                            <a href="{{ route('eliteplayers') }}" class="player-card">
                                <img src="https://images.unsplash.com/photo-1633332755192-727a05c4013d?w=200&h=200&fit=crop"
                                    alt="Featured Player" class="player-image">
                                <span class="player-name">Featured Player</span>
                                <span class="player-position">Position</span>
                            </a>
                        @endif
                    </div>
                </div>
            </div>

</div>
