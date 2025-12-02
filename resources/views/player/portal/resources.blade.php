@extends('player.portal.layout')

@section('title', 'Resources & Learning - Player Portal')

@section('portal-content')
<div class="row animate-slide-in">
    <!-- Main Content Area -->
    <div class="col-12">
        <!-- Page Header -->
        <div class="portal-section" data-aos="fade-up">
            <div class="section-header">
                <div>
                    <h1 class="section-title">
                        <i class="fas fa-book-open me-3 text-primary"></i>Resources & Learning
                    </h1>
                    <p class="section-subtitle">Access training materials, tutorials, and educational content</p>
                </div>

                <div class="search-filter">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search resources..." id="resourceSearch">
                        <button class="btn btn-primary" type="button">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Resource Categories -->
        <div class="portal-section" data-aos="fade-up" data-aos-delay="100">
            <div class="section-header">
                <h3 class="section-title">Resource Categories</h3>
            </div>

            <div class="resource-grid">
                <div class="resource-category" data-category="videos" data-aos="zoom-in">
                    <div class="category-icon">
                        <i class="fas fa-play-circle"></i>
                    </div>
                    <h4 class="category-title">Video Tutorials</h4>
                    <p class="category-desc">Watch training videos and technique demonstrations</p>
                    <span class="category-count">24 videos</span>
                </div>

                <div class="resource-category" data-category="guides" data-aos="zoom-in" data-aos-delay="100">
                    <div class="category-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <h4 class="category-title">Training Guides</h4>
                    <p class="category-desc">Read detailed guides and training manuals</p>
                    <span class="category-count">12 guides</span>
                </div>

                <div class="resource-category" data-category="workouts" data-aos="zoom-in" data-aos-delay="200">
                    <div class="category-icon">
                        <i class="fas fa-dumbbell"></i>
                    </div>
                    <h4 class="category-title">Workout Plans</h4>
                    <p class="category-desc">Custom workout routines and fitness programs</p>
                    <span class="category-count">8 plans</span>
                </div>

                <div class="resource-category" data-category="nutrition" data-aos="zoom-in" data-aos-delay="300">
                    <div class="category-icon">
                        <i class="fas fa-utensils"></i>
                    </div>
                    <h4 class="category-title">Nutrition</h4>
                    <p class="category-desc">Dietary guidance and meal planning</p>
                    <span class="category-count">5 guides</span>
                </div>

                <div class="resource-category" data-category="mental" data-aos="zoom-in" data-aos-delay="400">
                    <div class="category-icon">
                        <i class="fas fa-brain"></i>
                    </div>
                    <h4 class="category-title">Mental Training</h4>
                    <p class="category-desc">Sports psychology and mental preparation</p>
                    <span class="category-count">6 resources</span>
                </div>

                <div class="resource-category" data-category="recovery" data-aos="zoom-in" data-aos-delay="500">
                    <div class="category-icon">
                        <i class="fas fa-heartbeat"></i>
                    </div>
                    <h4 class="category-title">Recovery</h4>
                    <p class="category-desc">Injury prevention and recovery techniques</p>
                    <span class="category-count">4 guides</span>
                </div>
            </div>
        </div>

        <!-- Featured Resources -->
        <div class="portal-section" data-aos="fade-up" data-aos-delay="200">
            <div class="section-header">
                <h3 class="section-title">Featured Resources</h3>
                <button class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-star me-1"></i>View All
                </button>
            </div>

            <div class="featured-resources">
                <div class="resource-card featured" data-aos="fade-left">
                    <div class="resource-thumbnail">
                        <img src="/assets/img/training/passing-technique.jpg" alt="Passing Technique Tutorial" onerror="this.src='https://via.placeholder.com/300x200?text=Video+Thumbnail'">
                        <div class="resource-type video">
                            <i class="fas fa-play"></i>
                        </div>
                        <div class="resource-duration">12:45</div>
                    </div>
                    <div class="resource-content">
                        <h4 class="resource-title">Mastering the Perfect Pass</h4>
                        <p class="resource-desc">Learn essential passing techniques from professional coaches with step-by-step guidance.</p>
                        <div class="resource-meta">
                            <span><i class="fas fa-user me-1"></i>Coach Wilson</span>
                            <span><i class="fas fa-clock me-1"></i>12 min read</span>
                            <span><i class="fas fa-eye me-1"></i>245 views</span>
                        </div>
                    </div>
                </div>

                <div class="resource-card featured" data-aos="fade-left" data-aos-delay="100">
                    <div class="resource-thumbnail">
                        <img src="/assets/img/training/fitness-guide.jpg" alt="Fitness Training Guide" onerror="this.src='https://via.placeholder.com/300x200?text=Guide+Thumbnail'">
                        <div class="resource-type guide">
                            <i class="fas fa-file-pdf"></i>
                        </div>
                    </div>
                    <div class="resource-content">
                        <h4 class="resource-title">Youth Fitness Development Program</h4>
                        <p class="resource-desc">Complete fitness guide tailored for young athletes aged 14-18 years.</p>
                        <div class="resource-meta">
                            <span><i class="fas fa-user me-1"></i>Fitness Team</span>
                            <span><i class="fas fa-clock me-1"></i>8 min read</span>
                            <span><i class="fas fa-eye me-1"></i>189 views</span>
                        </div>
                    </div>
                </div>

                <div class="resource-card featured" data-aos="fade-left" data-aos-delay="200">
                    <div class="resource-thumbnail">
                        <img src="/assets/img/training/tactics.jpg" alt="Tactics Training" onerror="this.src='https://via.placeholder.com/300x200?text=Tactics+Thumbnail'">
                        <div class="resource-type video">
                            <i class="fas fa-play"></i>
                        </div>
                        <div class="resource-duration">18:30</div>
                    </div>
                    <div class="resource-content">
                        <h4 class="resource-title">Advanced Tactical Understanding</h4>
                        <p class="resource-desc">Deep dive into match tactics, positioning, and strategic decision-making.</p>
                        <div class="resource-meta">
                            <span><i class="fas fa-user me-1"></i>Coach Davis</span>
                            <span><i class="fas fa-clock me-1"></i>18 min read</span>
                            <span><i class="fas fa-eye me-1"></i>312 views</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Resource Library -->
        <div class="portal-section" data-aos="fade-up" data-aos-delay="300">
            <div class="section-header">
                <h3 class="section-title">Resource Library</h3>
                <div class="filter-controls">
                    <select class="form-select form-select-sm" id="sortBy">
                        <option value="recent">Recently Added</option>
                        <option value="popular">Most Viewed</option>
                        <option value="alphabetical">A-Z</option>
                        <option value="type">By Type</option>
                    </select>
                </div>
            </div>

            <div class="resource-library">
                <div class="resource-item" data-aos="fade-up">
                    <div class="resource-item-icon">
                        <i class="fas fa-file-pdf text-danger"></i>
                    </div>
                    <div class="resource-item-content">
                        <h5>Academy Player Handbook</h5>
                        <p>Complete guide to academy rules, expectations, and resources</p>
                        <div class="resource-item-meta">
                            <span>Updated 2 days ago</span>
                            <span>2.4 MB</span>
                        </div>
                    </div>
                    <div class="resource-item-actions">
                        <button class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-eye me-1"></i>Preview
                        </button>
                        <button class="btn btn-sm btn-success">
                            <i class="fas fa-download me-1"></i>Download
                        </button>
                    </div>
                </div>

                <div class="resource-item" data-aos="fade-up" data-aos-delay="100">
                    <div class="resource-item-icon">
                        <i class="fas fa-play-circle text-danger"></i>
                    </div>
                    <div class="resource-item-content">
                        <h5>Dribbling Fundamentals</h5>
                        <p>Learn the basics of ball control and dribbling techniques</p>
                        <div class="resource-item-meta">
                            <span>Video • 8:42</span>
                            <span>High quality</span>
                        </div>
                    </div>
                    <div class="resource-item-actions">
                        <button class="btn btn-sm btn-primary">
                            <i class="fas fa-play me-1"></i>Watch
                        </button>
                    </div>
                </div>

                <div class="resource-item" data-aos="fade-up" data-aos-delay="200">
                    <div class="resource-item-icon">
                        <i class="fas fa-utensils text-success"></i>
                    </div>
                    <div class="resource-item-content">
                        <h5>Pre-Match Nutrition Guide</h5>
                        <p>Optimal fueling strategies before competition</p>
                        <div class="resource-item-meta">
                            <span>Guide • 5 pages</span>
                            <span>Nutrition Team</span>
                        </div>
                    </div>
                    <div class="resource-item-actions">
                        <button class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-eye me-1"></i>Read
                        </button>
                    </div>
                </div>

                <div class="resource-item" data-aos="fade-up" data-aos-delay="300">
                    <div class="resource-item-icon">
                        <i class="fas fa-brain text-info"></i>
                    </div>
                    <div class="resource-item-content">
                        <h5>Mental Preparation Techniques</h5>
                        <p>Focus and concentration exercises for athletes</p>
                        <div class="resource-item-meta">
                            <span>Article • 4 min read</span>
                            <span>Psychology Dept</span>
                        </div>
                    </div>
                    <div class="resource-item-actions">
                        <button class="btn btn-sm btn-outline-info">
                            <i class="fas fa-book-open me-1"></i>Read
                        </button>
                    </div>
                </div>
            </div>

            <!-- Load More -->
            <div class="text-center mt-4" data-aos="fade-up">
                <button class="btn btn-outline-primary">
                    <i class="fas fa-plus-circle me-1"></i>Load More Resources
                </button>
            </div>
        </div>

        <!-- Quick Resources Sidebar (Desktop Only) -->
        <div class="row">
            <div class="col-12" data-aos="fade-up" data-aos-delay="400">
                <div class="quick-resources">
                    <div class="quick-resource-item">
                        <div class="quick-resource-icon">
                            <i class="fas fa-question-circle"></i>
                        </div>
                        <div class="quick-resource-content">
                            <h5>Frequently Asked Questions</h5>
                            <p>Quick answers to common questions</p>
                        </div>
                        <div class="quick-resource-action">
                            <button class="btn btn-sm btn-outline-primary">Browse FAQ</button>
                        </div>
                    </div>

                    <div class="quick-resource-item">
                        <div class="quick-resource-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="quick-resource-content">
                            <h5>Need Help?</h5>
                            <p>Contact our support team</p>
                        </div>
                        <div class="quick-resource-action">
                            <button class="btn btn-sm btn-outline-info">Contact Support</button>
                        </div>
                    </div>

                    <div class="quick-resource-item">
                        <div class="quick-resource-icon">
                            <i class="fas fa-lightbulb"></i>
                        </div>
                        <div class="quick-resource-content">
                            <h5>Training Tips</h5>
                            <p>Daily tips from our coaches</p>
                        </div>
                        <div class="quick-resource-action">
                            <button class="btn btn-sm btn-outline-success">View Tips</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Resource Categories */
.resource-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 20px;
}

.resource-category {
    padding: 24px;
    background: white;
    border: var(--border-light);
    border-radius: 16px;
    text-align: center;
    transition: var(--transition-normal);
    cursor: pointer;
}

.resource-category:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow);
    border-color: var(--primary-red);
}

.category-icon {
    width: 64px;
    height: 64px;
    margin: 0 auto 16px;
    background: var(--primary-red-light);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: var(--primary-red);
}

.category-title {
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 8px;
    color: var(--text-primary);
}

.category-desc {
    color: var(--text-secondary);
    margin-bottom: 12px;
    line-height: 1.4;
}

.category-count {
    font-size: 12px;
    font-weight: 600;
    color: var(--primary-red);
    background: var(--primary-red-light);
    padding: 4px 12px;
    border-radius: 12px;
}

/* Featured Resources */
.featured-resources {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
}

.resource-card {
    background: white;
    border: var(--border-light);
    border-radius: 16px;
    overflow: hidden;
    transition: var(--transition-normal);
}

.resource-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow);
}

.resource-thumbnail {
    position: relative;
    height: 180px;
    overflow: hidden;
}

.resource-thumbnail img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.resource-type {
    position: absolute;
    top: 12px;
    left: 12px;
    width: 36px;
    height: 36px;
    background: var(--primary-red);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 14px;
}

.resource-duration {
    position: absolute;
    bottom: 12px;
    right: 12px;
    background: rgba(0, 0, 0, 0.8);
    color: white;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 500;
}

.resource-content {
    padding: 20px;
}

.resource-title {
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 8px;
    color: var(--text-primary);
}

.resource-desc {
    color: var(--text-secondary);
    line-height: 1.5;
    margin-bottom: 16px;
}

.resource-meta {
    display: flex;
    gap: 16px;
    flex-wrap: wrap;
    font-size: 13px;
    color: var(--text-muted);
}

/* Resource Library */
.resource-library {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.resource-item {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 20px;
    background: white;
    border: var(--border-light);
    border-radius: 12px;
    transition: var(--transition-normal);
}

.resource-item:hover {
    box-shadow: var(--shadow-sm);
}

.resource-item-icon {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: var(--bg-tertiary);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    color: var(--primary-red);
    flex-shrink: 0;
}

.resource-item-content {
    flex: 1;
}

.resource-item-content h5 {
    margin-bottom: 4px;
    font-size: 16px;
    font-weight: 600;
    color: var(--text-primary);
}

.resource-item-content p {
    color: var(--text-secondary);
    margin-bottom: 4px;
    font-size: 14px;
}

.resource-item-meta {
    display: flex;
    gap: 12px;
    font-size: 12px;
    color: var(--text-muted);
}

.resource-item-actions {
    display: flex;
    gap: 8px;
    flex-shrink: 0;
}

/* Quick Resources */
.quick-resources {
    margin-top: 20px;
}

.quick-resource-item {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px 20px;
    background: linear-gradient(135deg, var(--primary-red-light), rgba(255, 255, 255, 0.8));
    border-radius: 12px;
    margin-bottom: 12px;
}

.quick-resource-icon {
    width: 40px;
    height: 40px;
    background: var(--primary-red);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    flex-shrink: 0;
}

.quick-resource-title {
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 4px;
}

.quick-resource-content {
    flex: 1;
}

.quick-resource-content h5 {
    font-size: 16px;
    font-weight: 600;
    margin-bottom: 4px;
    color: var(--text-primary);
}

.quick-resource-content p {
    color: var(--text-secondary);
    font-size: 14px;
    margin: 0;
}

/* Search and Filters */
.search-filter {
    max-width: 300px;
}

.input-group .form-control {
    border-radius: 25px 0 0 25px;
}

.input-group .btn {
    border-radius: 0 25px 25px 0;
}

/* Responsive */
@media (max-width: 768px) {
    .resource-grid {
        grid-template-columns: 1fr;
    }

    .featured-resources {
        grid-template-columns: 1fr;
    }

    .resource-item {
        flex-direction: column;
        text-align: center;
        gap: 12px;
    }

    .quick-resource-item {
        flex-direction: column;
        text-align: center;
        gap: 12px;
    }
}
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Resource search functionality
    const searchInput = document.getElementById('resourceSearch');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            // Implement search filtering
            console.log('Searching for:', searchTerm);
        });
    }

    // Category filter functionality
    document.querySelectorAll('.resource-category').forEach(category => {
        category.addEventListener('click', function() {
            const categoryType = this.dataset.category;
            // Implement category filtering
            console.log('Filtering by category:', categoryType);
        });
    });
});
</script>
@endpush
@endsection
