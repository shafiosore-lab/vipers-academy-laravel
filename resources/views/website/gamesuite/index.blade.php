@extends('layouts.academy')

@section('title', 'GameSuite - Professional Football Management Software')

@section('meta_description', 'GameSuite is a comprehensive football management platform offering tournament management, academy management, and financial tracking for football organizations worldwide.')

@section('content')

<style>
:root{
    --gs-primary:#0a1628;
    --gs-secondary:#1a3a52;
    --gs-gold:#ffd700;
    --gs-text:#64748b;
    --gs-radius:12px;
    --gs-transition:.3s ease;
}

/* =========================
   GLOBAL
========================= */
.content-wrapper,
body{
    margin:0;
    padding:0;
    overflow-x:hidden;
    max-width:100%;
}

.gs-hero-section,
.gs-features-section,
.gs-stats-section,
.gs-cta-section{
    position:relative;
}

.gs-btn-primary,
.gs-btn-secondary,
.gs-feature-card,
.gs-dashboard-card,
.gs-dashboard-img,
.gs-quick-action{
    transition:var(--gs-transition);
}

/* =========================
   HERO
========================= */
.gs-hero-section{
    min-height:90vh;
    display:flex;
    align-items:center;
    overflow:hidden;
    background:linear-gradient(135deg,var(--gs-primary),var(--gs-secondary));
    padding:5rem 0 2rem;
}

.gs-hero-pattern{
    position:absolute;
    inset:0;
    opacity:.05;
    background-image:repeating-linear-gradient(
        90deg,
        transparent,
        transparent 50px,
        rgba(255,255,255,.08) 50px,
        rgba(255,255,255,.08) 51px
    );
}

.gs-hero-content{
    position:relative;
    z-index:2;
}

.gs-badge{
    display:inline-flex;
    align-items:center;
    gap:.4rem;
    padding:.45rem .9rem;
    border-radius:999px;
    font-size:.72rem;
    font-weight:600;
    color:var(--gs-gold);
    background:rgba(255,215,0,.1);
    border:1px solid rgba(255,215,0,.25);
    margin-bottom:1rem;
}

.gs-hero-title{
    font-size:clamp(1.8rem,4vw,3.2rem);
    line-height:1.15;
    font-weight:800;
    color:#fff;
    margin-bottom:.9rem;
}

.gs-hero-title span{
    background:linear-gradient(135deg,var(--gs-gold),#ffed4e);
    -webkit-background-clip:text;
    -webkit-text-fill-color:transparent;
}

.gs-hero-desc{
    color:rgba(255,255,255,.82);
    font-size:.98rem;
    max-width:520px;
    margin-bottom:1.2rem;
}

.gs-hero-cta,
.gs-cta-buttons{
    display:flex;
    gap:.75rem;
    flex-wrap:wrap;
}

.gs-btn-primary,
.gs-btn-secondary{
    min-height:42px;
    padding:.75rem 1.2rem;
    border-radius:10px;
    font-weight:600;
    text-decoration:none;
    display:inline-flex;
    align-items:center;
    justify-content:center;
}

.gs-btn-primary{
    background:linear-gradient(135deg,var(--gs-gold),#ffb700);
    color:var(--gs-primary);
}

.gs-btn-primary:hover{
    transform:translateY(-2px);
    color:var(--gs-primary);
}

.gs-btn-secondary{
    border:1px solid rgba(255,255,255,.2);
    color:#fff;
    background:rgba(255,255,255,.08);
    backdrop-filter:blur(8px);
}

.gs-btn-secondary:hover{
    background:rgba(255,255,255,.15);
    color:#fff;
}

/* =========================
   TRUST
========================= */
.gs-trust-indicators{
    display:flex;
    gap:1.5rem;
    margin-top:1.5rem;
    padding-top:1rem;
    border-top:1px solid rgba(255,255,255,.08);
    flex-wrap:wrap;
}

.gs-trust-item{
    display:flex;
    align-items:center;
    gap:.5rem;
}

.gs-trust-number{
    font-size:1.4rem;
    font-weight:800;
}

.gs-trust-label{
    font-size:.72rem;
    color:rgba(255,255,255,.7);
}

/* =========================
   DASHBOARD
========================= */
.gs-dashboard-card{
    background:#fff;
    border-radius:0 0 18px 18px;
    overflow:hidden;
    box-shadow:0 18px 40px rgba(0,0,0,.18);
}

.gs-dashboard-header{
    padding:1rem;
    background:linear-gradient(135deg,var(--gs-secondary),var(--gs-primary));
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.gs-dashboard-title{
    color:#fff;
    font-weight:700;
    font-size:.92rem;
}

.gs-dashboard-subtitle{
    color:rgba(255,255,255,.65);
    font-size:.72rem;
}

.gs-live-badge{
    font-size:.65rem;
    padding:.2rem .55rem;
    border-radius:5px;
    background:#22c55e;
    color:#fff;
}

.gs-dashboard-body{
    padding:1rem;
}

.gs-metrics-row{
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:.6rem;
    margin-bottom:1rem;
}

.gs-metric-card{
    padding:.75rem;
    border-radius:10px;
    background:#f8fafc;
    border:1px solid #e2e8f0;
}

.gs-metric-label{
    font-size:.68rem;
    color:var(--gs-text);
}

.gs-metric-value{
    font-size:1.1rem;
    font-weight:800;
    color:#0f172a;
}

.gs-metric-bar{
    height:4px;
    margin-top:.45rem;
    border-radius:999px;
    background:#e2e8f0;
    overflow:hidden;
}

.gs-metric-bar-fill{
    height:100%;
}

/* =========================
   TABS & ACTIONS
========================= */
.gs-dashboard-tabs{
    display:flex;
    flex-wrap:wrap;
    gap:.45rem;
    margin-bottom:1rem;
}

.gs-dashboard-tab{
    border:none;
    cursor:pointer;
    padding:.4rem .7rem;
    border-radius:7px;
    font-size:.72rem;
    background:#f1f5f9;
    color:#475569;
}

.gs-dashboard-tab.active{
    background:linear-gradient(135deg,var(--gs-secondary),var(--gs-primary));
    color:#fff;
}

.gs-quick-actions{
    display:grid;
    grid-template-columns:repeat(2,1fr);
    gap:.5rem;
}

.gs-quick-action{
    padding:.7rem;
    border-radius:8px;
    text-align:center;
    text-decoration:none;
    color:#fff;
    font-size:.74rem;
    font-weight:600;
}

.gs-quick-action:hover{
    transform:translateY(-2px);
    color:#fff;
}

/* =========================
   FEATURES
========================= */
.gs-features-section{
    padding:4rem 0;
    background:#fff;
}

.gs-section-header{
    text-align:center;
    margin-bottom:2.5rem;
}

.gs-section-tag{
    display:inline-block;
    padding:.4rem .9rem;
    border-radius:999px;
    background:rgba(26,58,82,.08);
    color:var(--gs-secondary);
    font-size:.75rem;
    font-weight:700;
    margin-bottom:.8rem;
}

.gs-section-title{
    font-size:clamp(1.5rem,3vw,2.2rem);
    font-weight:800;
    color:var(--gs-primary);
    margin-bottom:.6rem;
}

.gs-section-desc{
    max-width:620px;
    margin:auto;
    color:var(--gs-text);
}

.gs-feature-grid{
    display:grid;
    grid-template-columns:repeat(2,1fr);
    gap:1.2rem;
}

.gs-feature-card{
    padding:1.4rem;
    border-radius:16px;
    border:1px solid;
}

.gs-feature-card:hover{
    transform:translateY(-4px);
    box-shadow:0 10px 25px rgba(0,0,0,.08);
}

.gs-feature-icon{
    width:48px;
    height:48px;
    border-radius:12px;
    display:flex;
    align-items:center;
    justify-content:center;
    margin-bottom:1rem;
}

.gs-feature-title{
    font-size:1rem;
    font-weight:800;
    margin-bottom:.5rem;
    color:var(--gs-primary);
}

.gs-feature-desc{
    font-size:.88rem;
    color:var(--gs-text);
    margin-bottom:.9rem;
}

.gs-feature-list{
    display:flex;
    flex-direction:column;
    gap:.45rem;
}

.gs-feature-item{
    display:flex;
    align-items:center;
    gap:.45rem;
    font-size:.82rem;
    color:#475569;
}

/* =========================
   STATS
========================= */
.gs-stats-section,
.gs-cta-section{
    padding:4rem 0;
    background:linear-gradient(135deg,var(--gs-primary),var(--gs-secondary));
    text-align:center;
}

.gs-stats-title,
.gs-cta-title{
    font-size:clamp(1.5rem,3vw,2.1rem);
    color:#fff;
    font-weight:800;
}

.gs-stats-subtitle,
.gs-cta-desc{
    color:rgba(255,255,255,.75);
    margin-bottom:2rem;
}

.gs-stats-grid{
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:1rem;
}

.gs-stat-number{
    font-size:1.8rem;
    font-weight:800;
}

.gs-stat-label{
    font-size:.78rem;
    color:rgba(255,255,255,.7);
}

/* =========================
   MOBILE
========================= */
@media(max-width:992px){

    .gs-hero-section{
        min-height:auto;
        padding:4rem 0 2rem;
    }

    .gs-feature-grid{
        grid-template-columns:1fr;
    }

    .gs-metrics-row,
    .gs-stats-grid{
        grid-template-columns:repeat(2,1fr);
    }
}

@media(max-width:576px){

    .gs-hero-section{
        padding:3.5rem 0 1.5rem;
    }

    .gs-hero-cta,
    .gs-cta-buttons{
        flex-wrap:nowrap;
    }

    .gs-btn-primary,
    .gs-btn-secondary{
        flex:1;
        font-size:.74rem;
        min-height:36px;
        padding:.6rem .7rem;
    }

    .gs-dashboard-body,
    .gs-dashboard-header{
        padding:.7rem;
    }

    .gs-metrics-row{
        gap:.4rem;
    }

    .gs-metric-card{
        padding:.5rem;
    }

    .gs-metric-value{
        font-size:.9rem;
    }

    .gs-dashboard-tab{
        font-size:.62rem;
        padding:.3rem .5rem;
    }

    .gs-quick-action{
        min-height:34px;
        padding:.45rem;
        font-size:.65rem;
    }

    .gs-feature-card{
        padding:1rem;
    }

    .gs-stat-number{
        font-size:1.2rem;
    }

    .gs-trust-indicators{
        gap:.8rem;
    }

    .gs-trust-number{
        font-size:1rem;
    }

    .gs-trust-label{
        font-size:.6rem;
    }
}

@media(max-width:380px){

    .gs-hero-title{
        font-size:1.05rem;
    }

    .gs-hero-desc{
        font-size:.74rem;
    }

    .gs-btn-primary,
    .gs-btn-secondary{
        font-size:.68rem;
        padding:.5rem;
    }

    .gs-metrics-row,
    .gs-stats-grid{
        gap:.35rem;
    }

    .gs-metric-value{
        font-size:.8rem;
    }

    .gs-feature-title{
        font-size:.88rem;
    }

    .gs-feature-desc,
    .gs-feature-item{
        font-size:.72rem;
    }
}
</style>

<script>
function scrollToSection(sectionId){
    const element = document.getElementById(sectionId);

    if(element){
        element.scrollIntoView({
            behavior:'smooth',
            block:'start'
        });
    }
}
</script>

<!-- HERO -->
<section class="gs-hero-section">

    <div class="gs-hero-pattern"></div>

    <div class="container">
        <div class="row align-items-center g-4">

            <!-- LEFT -->
            <div class="col-lg-5">
                <div class="gs-hero-content">

                    <div class="gs-badge">
                        <i class="fas fa-bolt"></i>
                        TRUSTED BY 500+ ORGANIZATIONS
                    </div>

                    <h1 class="gs-hero-title">
                        Run Your Football Organization
                        <span>Like a Pro</span>
                    </h1>

                    <p class="gs-hero-desc">
                        Complete tournament management, academy operations, and player development—all in one powerful platform built for grassroots football.
                    </p>

                    <div class="gs-hero-cta">
                        <a href="#demo" class="gs-btn-primary">
                            <i class="fas fa-play-circle me-2"></i>
                            Request Demo
                        </a>

                        <a href="{{ route('register') }}?trial=true" class="gs-btn-secondary">
                            Start Free Trial
                        </a>
                    </div>

                    <div class="gs-trust-indicators">

                        <div class="gs-trust-item">
                            <div class="gs-trust-number text-warning">
                                {{ number_format($totalPlayers) }}+
                            </div>

                            <div class="gs-trust-label">
                                Active Players
                            </div>
                        </div>

                        <div class="gs-trust-item">
                            <div class="gs-trust-number text-success">
                                {{ $activePrograms }}+
                            </div>

                            <div class="gs-trust-label">
                                Programs
                            </div>
                        </div>

                        <div class="gs-trust-item">
                            <div class="gs-trust-number text-info">
                                99.9%
                            </div>

                            <div class="gs-trust-label">
                                Uptime
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- RIGHT -->
            <div class="col-lg-7">

                <div class="gs-dashboard-card">

                    <div class="gs-dashboard-header">

                        <div>
                            <div class="gs-dashboard-title">
                                <i class="fas fa-futbol me-2"></i>
                                GameSuite Dashboard
                            </div>

                            <div class="gs-dashboard-subtitle">
                                Real-time analytics & metrics
                            </div>
                        </div>

                        <span class="gs-live-badge">
                            Live
                        </span>

                    </div>

                    <div class="gs-dashboard-body">

                        <!-- METRICS -->
                        <div class="gs-metrics-row">

                            <div class="gs-metric-card">
                                <div class="gs-metric-label">Players</div>

                                <div class="gs-metric-value">
                                    {{ number_format($totalPlayers) }}
                                </div>

                                <div class="gs-metric-bar">
                                    <div class="gs-metric-bar-fill bg-primary"
                                         style="width:{{ $playerCompletion }}%">
                                    </div>
                                </div>
                            </div>

                            <div class="gs-metric-card">
                                <div class="gs-metric-label">Programs</div>

                                <div class="gs-metric-value">
                                    {{ $activePrograms }}
                                </div>

                                <div class="gs-metric-bar">
                                    <div class="gs-metric-bar-fill bg-success"
                                         style="width:{{ $programCompletion }}%">
                                    </div>
                                </div>
                            </div>

                            <div class="gs-metric-card">
                                <div class="gs-metric-label">Goals</div>

                                <div class="gs-metric-value">
                                    {{ number_format($totalGoals) }}
                                </div>

                                <div class="gs-metric-bar">
                                    <div class="gs-metric-bar-fill bg-warning"
                                         style="width:75%">
                                    </div>
                                </div>
                            </div>

                            <div class="gs-metric-card">
                                <div class="gs-metric-label">Revenue</div>

                                <div class="gs-metric-value">
                                    ${{ number_format($totalRevenue,0) }}
                                </div>

                                <div class="gs-metric-bar">
                                    <div class="gs-metric-bar-fill bg-danger"
                                         style="width:85%">
                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- TABS -->
                        <div class="gs-dashboard-tabs">
                            <button class="gs-dashboard-tab active">Actions</button>
                            <button class="gs-dashboard-tab">Stats</button>
                            <button class="gs-dashboard-tab">Players</button>
                            <button class="gs-dashboard-tab">Activity</button>
                        </div>

                        <!-- ACTIONS -->
                        <div class="gs-quick-actions">

                            <a href="{{ route('admin.players.create') }}"
                               class="gs-quick-action"
                               style="background:linear-gradient(135deg,#0284c7,#0369a1);">

                                <i class="fas fa-user-plus me-1"></i>
                                Add Player
                            </a>

                            <a href="{{ route('admin.tournaments.create') }}"
                               class="gs-quick-action"
                               style="background:linear-gradient(135deg,#16a34a,#15803d);">

                                <i class="fas fa-trophy me-1"></i>
                                Tournament
                            </a>

                            <a href="{{ route('admin.messaging.quick') }}"
                               class="gs-quick-action"
                               style="background:linear-gradient(135deg,#f59e0b,#d97706);">

                                <i class="fas fa-envelope me-1"></i>
                                Message
                            </a>

                            <a href="{{ route('admin.training-sessions.create') }}"
                               class="gs-quick-action"
                               style="background:linear-gradient(135deg,#8b5cf6,#7c3aed);">

                                <i class="fas fa-calendar-plus me-1"></i>
                                Schedule
                            </a>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

</section>

<!-- FEATURES -->
<section class="gs-features-section" id="features">

    <div class="container">

        <div class="gs-section-header">

            <span class="gs-section-tag">
                CORE FEATURES
            </span>

            <h2 class="gs-section-title">
                Everything You Need, Nothing You Don't
            </h2>

            <p class="gs-section-desc">
                Professional-grade tools designed specifically for football organizations.
            </p>

        </div>

        <div class="gs-feature-grid">

            <!-- FEATURE -->
            <div class="gs-feature-card"
                 style="background:#f0f9ff;border-color:#dbeafe;">

                <div class="gs-feature-icon"
                     style="background:linear-gradient(135deg,#0284c7,#0369a1);">

                    <i class="fas fa-trophy text-white"></i>
                </div>

                <h4 class="gs-feature-title">
                    Tournament Management
                </h4>

                <p class="gs-feature-desc">
                    Run professional tournaments from registration to final whistle.
                </p>

                <div class="gs-feature-list">
                    <div class="gs-feature-item">
                        <i class="fas fa-check-circle text-success"></i>
                        Automated fixtures
                    </div>

                    <div class="gs-feature-item">
                        <i class="fas fa-check-circle text-success"></i>
                        Live standings
                    </div>

                    <div class="gs-feature-item">
                        <i class="fas fa-check-circle text-success"></i>
                        Team verification
                    </div>
                </div>

            </div>

            <!-- FEATURE -->
            <div class="gs-feature-card"
                 style="background:#f0fdf4;border-color:#dcfce7;">

                <div class="gs-feature-icon"
                     style="background:linear-gradient(135deg,#16a34a,#15803d);">

                    <i class="fas fa-graduation-cap text-white"></i>
                </div>

                <h4 class="gs-feature-title">
                    Academy Management
                </h4>

                <p class="gs-feature-desc">
                    Comprehensive player development & tracking system.
                </p>

                <div class="gs-feature-list">
                    <div class="gs-feature-item">
                        <i class="fas fa-check-circle text-success"></i>
                        Player database
                    </div>

                    <div class="gs-feature-item">
                        <i class="fas fa-check-circle text-success"></i>
                        Performance analytics
                    </div>

                    <div class="gs-feature-item">
                        <i class="fas fa-check-circle text-success"></i>
                        Parent portal
                    </div>
                </div>

            </div>

        </div>

    </div>

</section>

<!-- STATS -->
<section class="gs-stats-section">

    <div class="container">

        <h2 class="gs-stats-title">
            Trusted by Organizations Worldwide
        </h2>

        <p class="gs-stats-subtitle">
            Real impact, measurable results
        </p>

        <div class="gs-stats-grid">

            <div>
                <div class="gs-stat-number text-warning">
                    {{ number_format($totalPlayers) }}+
                </div>

                <div class="gs-stat-label">
                    Active Players
                </div>
            </div>

            <div>
                <div class="gs-stat-number text-success">
                    {{ $totalPrograms * 10 }}+
                </div>

                <div class="gs-stat-label">
                    Tournaments
                </div>
            </div>

            <div>
                <div class="gs-stat-number text-info">
                    {{ $totalPartners }}+
                </div>

                <div class="gs-stat-label">
                    Partners
                </div>
            </div>

            <div>
                <div class="gs-stat-number text-danger">
                    {{ $yearsActive }}
                </div>

                <div class="gs-stat-label">
                    Years Active
                </div>
            </div>

        </div>

    </div>

</section>

<!-- CTA -->
<section class="gs-cta-section" id="demo">

    <div class="container">

        <h2 class="gs-cta-title">
            Ready to Transform Your Organization?
        </h2>

        <p class="gs-cta-desc">
            Join 500+ organizations running professional football operations with GameSuite.
        </p>

        <div class="gs-cta-buttons">

            <a href="{{ route('register') }}?trial=true"
               class="gs-btn-primary">

                <i class="fas fa-rocket me-2"></i>
                Start Free Trial
            </a>

            <a href="{{ route('contact') }}"
               class="gs-btn-secondary">

                Contact Sales
            </a>

        </div>

    </div>

</section>

@endsection
