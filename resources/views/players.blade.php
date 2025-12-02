@extends('layouts.academy')

@section('title', 'Elite Players - Vipers Academy')

@section('content')

<!-- Compact Filter Bar -->
<div class="filter-bar" id="filterBar">
    <div class="container-fluid px-4">
        <div class="filter-controls">
            <!-- Left: Filters -->
            <div class="filter-group">
                <select class="form-select form-select-sm" id="positionFilter">
                    <option value="">All Positions</option>
                    <option value="goalkeeper">Goalkeeper</option>
                    <option value="defender">Defender</option>
                    <option value="midfielder">Midfielder</option>
                    <option value="forward">Forward</option>
                </select>

                <select class="form-select form-select-sm" id="ageFilter">
                    <option value="">All Ages</option>
                    <option value="under-10">Under 10</option>
                    <option value="10-12">10-12 years</option>
                    <option value="13-15">13-15 years</option>
                    <option value="16-18">16-18 years</option>
                    <option value="over-18">Over 18</option>
                </select>

                <select class="form-select form-select-sm" id="skillFilter">
                    <option value="">All Levels</option>
                    <option value="beginner">Beginner</option>
                    <option value="intermediate">Intermediate</option>
                    <option value="advanced">Advanced</option>
                    <option value="elite">Elite</option>
                </select>

                <button class="btn btn-sm btn-outline-secondary" id="resetFilters">
                    <i class="fas fa-redo"></i> Reset
                </button>
            </div>

            <!-- Right: View & Sort -->
            <div class="filter-group">
                <div class="btn-group btn-group-sm view-toggle">
                    <button class="btn btn-outline-primary active" id="gridView">
                        <i class="fas fa-th"></i>
                    </button>
                    <button class="btn btn-outline-primary" id="listView">
                        <i class="fas fa-list"></i>
                    </button>
                </div>

                <select class="form-select form-select-sm" id="sortFilter">
                    <option value="name-asc">Name A-Z</option>
                    <option value="name-desc">Name Z-A</option>
                    <option value="age-asc">Age: Young First</option>
                    <option value="age-desc">Age: Old First</option>
                </select>
            </div>
        </div>
    </div>
</div>

<!-- Players Grid Section -->
<section class="players-section">
    <div class="container-fluid px-4">
        @if($players->count())
        <div class="row g-3" id="playersGrid">
            @foreach($players as $player)
            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 player-card"
                data-name="{{ strtolower($player->name) }}"
                data-position="{{ strtolower($player->position) }}"
                data-age="{{ $player->age ?? 0 }}"
                data-skill="{{ strtolower($player->skill_level ?? 'intermediate') }}"
                data-status="{{ strtolower($player->status ?? 'active') }}">

                <div class="modern-player-card">
                    <!-- Player Image with Overlay -->
                    <div class="player-image-wrapper">
                        <img src="{{ $player->image ? asset('storage/' . $player->image) : 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?auto=format&fit=crop&w=400&q=80' }}"
                            class="player-image" alt="{{ $player->name }}">

                        <!-- Gradient Overlay -->
                        <div class="image-gradient"></div>

                        <!-- Top Badge -->
                        <div class="position-badge">
                            <span class="badge-text">{{ strtoupper(substr($player->position ?? 'PLY', 0, 3)) }}</span>
                        </div>

                        <!-- Quick Action Hover -->
                        <div class="quick-actions">
                            <a href="{{ route('home.player.show', $player->id) }}" class="action-btn">
                                <i class="fas fa-eye"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Player Info -->
                    <div class="player-info">
                        <div class="player-header">
                            <div class="player-number">#{{ $player->jersey_number ?? rand(1, 99) }}</div>
                            <div class="player-status">
                                <span class="status-dot {{ $player->status === 'active' ? 'active' : 'inactive' }}"></span>
                            </div>
                        </div>

                        <h3 class="player-name">{{ $player->name }}</h3>
                        <p class="player-position">{{ $player->position ?? 'Player' }}</p>

                        <!-- Player Stats Mini -->
                        <div class="player-stats-mini">
                            <div class="stat-item">
                                <i class="fas fa-birthday-cake"></i>
                                <span>{{ $player->age ?? 'N/A' }}</span>
                            </div>
                            @if($player->skill_level)
                            <div class="stat-item">
                                <i class="fas fa-chart-line"></i>
                                <span>{{ ucfirst($player->skill_level) }}</span>
                            </div>
                            @endif
                        </div>

                        <!-- CTA Button -->
                        <a href="{{ route('home.player.show', $player->id) }}" class="view-profile-btn">
                            View Profile
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($players->hasPages())
        <div class="pagination-wrapper">
            {{ $players->links() }}
        </div>
        @endif

        @else
        <!-- Empty State -->
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fas fa-users"></i>
            </div>
            <h3>No Players Found</h3>
            <p>There are currently no players matching your criteria.</p>
            <button class="btn btn-primary" id="resetFiltersEmpty">
                <i class="fas fa-redo me-2"></i>Reset Filters
            </button>
        </div>
        @endif
    </div>
</section>

@endsection

@push('styles')
<style>
/* ==================== FILTER BAR ==================== */
.filter-bar {
    background: rgba(255, 255, 255, 0.98);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    padding: 12px 0;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    border-bottom: 1px solid rgba(234, 28, 77, 0.1);
    position: fixed;
    left: 0;
    right: 0;
    z-index: 1045;
    transition: all 0.3s ease;
}

.filter-controls {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
}

.filter-group {
    display: flex;
    align-items: center;
    gap: 10px;
}

.form-select-sm {
    min-width: 140px;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    padding: 8px 12px;
    font-size: 13px;
    transition: all 0.2s ease;
}

.form-select-sm:focus {
    border-color: #ea1c4d;
    box-shadow: 0 0 0 3px rgba(234, 28, 77, 0.1);
}

.btn-sm {
    padding: 8px 16px;
    font-size: 13px;
    border-radius: 8px;
    font-weight: 500;
}

.view-toggle .btn {
    padding: 8px 12px;
}

/* ==================== PLAYERS SECTION ==================== */
.players-section {
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    min-height: calc(100vh - 200px);
    padding: 80px 0 40px;
}

/* ==================== MODERN PLAYER CARD ==================== */
.modern-player-card {
    background: #ffffff;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.modern-player-card:hover {
    transform: translateY(-12px);
    box-shadow: 0 20px 40px rgba(234, 28, 77, 0.2);
}

/* Player Image */
.player-image-wrapper {
    position: relative;
    width: 100%;
    padding-top: 125%; /* 5:4 aspect ratio */
    overflow: hidden;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.player-image {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
}

.modern-player-card:hover .player-image {
    transform: scale(1.1);
}

/* Image Gradient Overlay */
.image-gradient {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 50%;
    background: linear-gradient(to top, rgba(0, 0, 0, 0.7) 0%, transparent 100%);
    pointer-events: none;
}

/* Position Badge */
.position-badge {
    position: absolute;
    top: 12px;
    left: 12px;
    background: rgba(234, 28, 77, 0.95);
    backdrop-filter: blur(10px);
    padding: 6px 12px;
    border-radius: 8px;
    z-index: 2;
}

.badge-text {
    color: white;
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 1px;
}

/* Quick Actions */
.quick-actions {
    position: absolute;
    top: 12px;
    right: 12px;
    opacity: 0;
    transform: translateY(-10px);
    transition: all 0.3s ease;
    z-index: 2;
}

.modern-player-card:hover .quick-actions {
    opacity: 1;
    transform: translateY(0);
}

.action-btn {
    width: 36px;
    height: 36px;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #ea1c4d;
    text-decoration: none;
    transition: all 0.2s ease;
}

.action-btn:hover {
    background: #ea1c4d;
    color: white;
    transform: scale(1.1);
}

/* Player Info */
.player-info {
    padding: 16px;
    flex: 1;
    display: flex;
    flex-direction: column;
}

.player-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 8px;
}

.player-number {
    font-size: 20px;
    font-weight: 900;
    color: #ea1c4d;
    line-height: 1;
}

.player-status {
    display: flex;
    align-items: center;
}

.status-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #ccc;
}

.status-dot.active {
    background: #65c16e;
    box-shadow: 0 0 0 3px rgba(101, 193, 110, 0.2);
    animation: pulse-dot 2s infinite;
}

@keyframes pulse-dot {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}

.player-name {
    font-size: 16px;
    font-weight: 800;
    color: #1a1a1a;
    margin: 0 0 4px 0;
    line-height: 1.3;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.player-position {
    font-size: 12px;
    color: #666;
    margin: 0 0 12px 0;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 600;
}

/* Player Stats Mini */
.player-stats-mini {
    display: flex;
    gap: 12px;
    margin-bottom: 12px;
    padding-bottom: 12px;
    border-bottom: 1px solid #f0f0f0;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 11px;
    color: #666;
}

.stat-item i {
    color: #ea1c4d;
    font-size: 12px;
}

/* View Profile Button */
.view-profile-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 10px 16px;
    background: linear-gradient(135deg, #ea1c4d 0%, #c0173f 100%);
    color: white;
    text-decoration: none;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 700;
    transition: all 0.3s ease;
    margin-top: auto;
}

.view-profile-btn:hover {
    background: linear-gradient(135deg, #c0173f 0%, #ea1c4d 100%);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(234, 28, 77, 0.3);
}

.view-profile-btn i {
    font-size: 11px;
    transition: transform 0.3s ease;
}

.view-profile-btn:hover i {
    transform: translateX(4px);
}

/* ==================== LIST VIEW ==================== */
#playersGrid.list-view {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

#playersGrid.list-view .player-card {
    max-width: 100%;
}

#playersGrid.list-view .modern-player-card {
    flex-direction: row;
    height: auto;
}

#playersGrid.list-view .player-image-wrapper {
    width: 180px;
    padding-top: 0;
    height: 180px;
    flex-shrink: 0;
}

#playersGrid.list-view .player-info {
    flex-direction: row;
    align-items: center;
    justify-content: space-between;
    padding: 20px;
}

/* ==================== EMPTY STATE ==================== */
.empty-state {
    text-align: center;
    padding: 80px 20px;
}

.empty-icon {
    width: 120px;
    height: 120px;
    margin: 0 auto 24px;
    background: linear-gradient(135deg, #ea1c4d 0%, #c0173f 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.empty-icon i {
    font-size: 48px;
    color: white;
}

.empty-state h3 {
    font-size: 24px;
    font-weight: 800;
    color: #1a1a1a;
    margin-bottom: 12px;
}

.empty-state p {
    font-size: 16px;
    color: #666;
    margin-bottom: 24px;
}

/* ==================== HIDDEN STATE ==================== */
.player-card.hidden {
    display: none;
}

/* ==================== RESPONSIVE ==================== */
@media (max-width: 992px) {
    .filter-controls {
        flex-wrap: wrap;
    }

    .filter-group {
        width: 100%;
        flex-wrap: wrap;
    }
}

@media (max-width: 768px) {
    .players-section {
        padding-top: 130px;
    }

    .form-select-sm {
        min-width: 120px;
        font-size: 12px;
    }

    .modern-player-card {
        border-radius: 12px;
    }

    #playersGrid.list-view .modern-player-card {
        flex-direction: column;
    }

    #playersGrid.list-view .player-image-wrapper {
        width: 100%;
        padding-top: 125%;
        height: auto;
    }
}

@media (max-width: 576px) {
    .filter-bar {
        padding: 8px 0;
    }

    .filter-group {
        gap: 6px;
    }

    .form-select-sm {
        min-width: 100px;
        padding: 6px 10px;
        font-size: 11px;
    }

    .btn-sm {
        padding: 6px 12px;
        font-size: 11px;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterBar = document.getElementById('filterBar');
    const navbar = document.querySelector('.main-navbar');
    const playersGrid = document.getElementById('playersGrid');
    let lastScrollY = window.scrollY;
    let ticking = false;

    // Filter Bar Position
    function updateFilterBarPosition() {
        const currentScrollY = window.scrollY;
        const navbarHeight = navbar ? navbar.offsetHeight : 64;
        filterBar.style.top = `${navbarHeight}px`;
        lastScrollY = currentScrollY;
        ticking = false;
    }

    window.addEventListener('scroll', () => {
        if (!ticking) {
            window.requestAnimationFrame(updateFilterBarPosition);
            ticking = true;
        }
    }, { passive: true });

    window.addEventListener('resize', updateFilterBarPosition);
    updateFilterBarPosition();

    // Filter Elements
    const ageFilter = document.getElementById('ageFilter');
    const skillFilter = document.getElementById('skillFilter');
    const positionFilter = document.getElementById('positionFilter');
    const sortFilter = document.getElementById('sortFilter');
    const resetBtn = document.getElementById('resetFilters');
    const resetBtnEmpty = document.getElementById('resetFiltersEmpty');
    const gridViewBtn = document.getElementById('gridView');
    const listViewBtn = document.getElementById('listView');

    // Filter Function
    function filterPlayers() {
        const selectedAge = ageFilter.value;
        const selectedSkill = skillFilter.value.toLowerCase();
        const selectedPosition = positionFilter.value.toLowerCase();

        const cards = document.querySelectorAll('.player-card');

        cards.forEach(card => {
            const age = parseInt(card.dataset.age) || 0;
            const skill = card.dataset.skill.toLowerCase();
            const position = card.dataset.position.toLowerCase();

            let matchesAge = !selectedAge;
            if (selectedAge === 'under-10') matchesAge = age < 10;
            else if (selectedAge === '10-12') matchesAge = age >= 10 && age <= 12;
            else if (selectedAge === '13-15') matchesAge = age >= 13 && age <= 15;
            else if (selectedAge === '16-18') matchesAge = age >= 16 && age <= 18;
            else if (selectedAge === 'over-18') matchesAge = age > 18;

            const matchesSkill = !selectedSkill || skill === selectedSkill;
            const matchesPosition = !selectedPosition || position === selectedPosition;

            if (matchesAge && matchesSkill && matchesPosition) {
                card.classList.remove('hidden');
            } else {
                card.classList.add('hidden');
            }
        });
    }

    // Sort Function
    function sortPlayers() {
        const sortValue = sortFilter.value;
        const cards = Array.from(playersGrid.querySelectorAll('.player-card'));

        cards.sort((a, b) => {
            const nameA = a.dataset.name.toLowerCase();
            const nameB = b.dataset.name.toLowerCase();
            const ageA = parseInt(a.dataset.age) || 0;
            const ageB = parseInt(b.dataset.age) || 0;

            switch(sortValue) {
                case 'name-asc': return nameA.localeCompare(nameB);
                case 'name-desc': return nameB.localeCompare(nameA);
                case 'age-asc': return ageA - ageB;
                case 'age-desc': return ageB - ageA;
                default: return 0;
            }
        });

        cards.forEach(card => playersGrid.appendChild(card));
    }

    // Reset Filters
    function resetFilters() {
        ageFilter.value = '';
        skillFilter.value = '';
        positionFilter.value = '';
        sortFilter.value = 'name-asc';
        filterPlayers();
        sortPlayers();
    }

    // View Toggle
    function switchToGrid() {
        playersGrid.classList.remove('list-view');
        gridViewBtn.classList.add('active');
        listViewBtn.classList.remove('active');
    }

    function switchToList() {
        playersGrid.classList.add('list-view');
        listViewBtn.classList.add('active');
        gridViewBtn.classList.remove('active');
    }

    // Event Listeners
    ageFilter.addEventListener('change', () => { filterPlayers(); sortPlayers(); });
    skillFilter.addEventListener('change', () => { filterPlayers(); sortPlayers(); });
    positionFilter.addEventListener('change', () => { filterPlayers(); sortPlayers(); });
    sortFilter.addEventListener('change', sortPlayers);
    resetBtn.addEventListener('click', resetFilters);
    if (resetBtnEmpty) resetBtnEmpty.addEventListener('click', resetFilters);
    gridViewBtn.addEventListener('click', switchToGrid);
    listViewBtn.addEventListener('click', switchToList);

    // Initialize
    filterPlayers();
    sortPlayers();
});
</script>
@endpush
