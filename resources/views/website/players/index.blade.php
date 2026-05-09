@extends('layouts.academy')

@section('title', 'Our Players - Mumias Vipers Academy')

@php
    $isAdmin = auth()->check() && auth()->user()->is_admin;

    $filters = [
        'gender' => [
            ['label' => 'All',   'value' => '',  'icon' => ''],
            ['label' => 'Men',   'value' => 'M', 'icon' => 'mars'],
            ['label' => 'Women', 'value' => 'F', 'icon' => 'venus'],
        ],
        'category' => [
            ['label' => 'All', 'value' => '',       'icon' => ''],
            ['label' => 'Jr',  'value' => 'junior', 'icon' => 'seedling'],
            ['label' => 'Sr',  'value' => 'senior', 'icon' => 'crown'],
        ],
    ];
@endphp

@push('styles')
<style>
    /* ── Variables ──────────────────────────────────────── */
    :root {
        --primary-red: #e63946;
    }

    /* ── Layout ─────────────────────────────────────────── */
    .p-container {
        max-width: 1200px;
        margin: auto;
        padding: 10px;
        font-family: system-ui, -apple-system, sans-serif;
    }

    /* ── Search bar row ─────────────────────────────────── */
    .search-row {
        display: flex;
        gap: 6px;
        margin-bottom: 10px;
        align-items: center;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;          /* Firefox */
    }

    .search-row::-webkit-scrollbar {
        display: none;                  /* Chrome/Safari */
    }

    .search-wrapper {
        position: relative;
        flex-shrink: 0;
        width: 110px;                   /* compact fixed width on mobile */
    }

    .search-wrapper input {
        width: 100%;
        padding: 5px 24px 5px 8px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 12px;
        outline: none;
        transition: border-color 0.15s ease;
        height: 28px;
        box-sizing: border-box;
    }

    .search-wrapper input:focus {
        border-color: var(--primary-red);
    }

    .clear-btn {
        position: absolute;
        right: 5px;
        top: 50%;
        transform: translateY(-50%);
        border: none;
        background: none;
        cursor: pointer;
        font-size: 11px;
        color: #999;
        padding: 0;
        line-height: 1;
    }

    /* ── Filter chips ───────────────────────────────────── */
    .filter-chip {
        display: inline-flex;
        align-items: center;
        gap: 3px;
        padding: 4px 8px;
        border: 1px solid #ddd;
        border-radius: 4px;
        background: #fff;
        cursor: pointer;
        font-size: 11px;
        font-weight: 500;
        white-space: nowrap;
        flex-shrink: 0;
        transition: all 0.15s ease;
        height: 28px;
        color: #555;
    }

    .filter-chip:hover {
        border-color: var(--primary-red);
        color: var(--primary-red);
    }

    .filter-chip.active {
        background: var(--primary-red);
        color: #fff;
        border-color: var(--primary-red);
    }

    /* ── Divider between filter groups ─────────────────── */
    .filter-divider {
        width: 1px;
        height: 20px;
        background: #ddd;
        flex-shrink: 0;
    }

    /* ── Players grid — 4 col mobile, 6 col desktop ─────── */
    .players-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 6px;
    }

    @media (min-width: 768px) {
        .players-grid {
            grid-template-columns: repeat(6, 1fr);
            gap: 10px;
        }

        .search-wrapper {
            width: 160px;
        }
    }

    /* ── Player card ────────────────────────────────────── */
    .player-card {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 5px;
        padding: 8px 6px;
        border: 0.5px solid #e5e5e5;
        border-radius: 8px;
        background: #fff;
        text-decoration: none;
        color: inherit;
        transition: transform 0.15s ease, box-shadow 0.15s ease, border-color 0.15s ease;
        cursor: pointer;
        min-width: 0;                   /* prevent grid blowout */
    }

    .player-card:hover {
        transform: translateY(-2px);
        border-color: var(--primary-red);
        box-shadow: 0 4px 10px rgba(230, 57, 70, 0.12);
    }

    /* ── Avatar ─────────────────────────────────────────── */
    .player-avatar {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        overflow: hidden;
        flex-shrink: 0;
        border: 2px solid var(--primary-red);
    }

    @media (min-width: 768px) {
        .player-avatar {
            width: 56px;
            height: 56px;
        }
    }

    .player-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .avatar-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--primary-red);
        color: #fff;
        font-weight: 700;
        font-size: 16px;
    }

    @media (min-width: 768px) {
        .avatar-placeholder {
            font-size: 20px;
        }
    }

    /* ── Player info ────────────────────────────────────── */
    .player-info {
        text-align: center;
        width: 100%;
        min-width: 0;
    }

    .player-name {
        font-size: 11px;
        font-weight: 600;
        line-height: 1.2;
        color: #111;
        margin: 0 0 3px 0;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    @media (min-width: 768px) {
        .player-name {
            font-size: 13px;
        }
    }

    .player-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 2px;
        justify-content: center;
        font-size: 10px;
        color: #666;
    }

    @media (min-width: 768px) {
        .player-meta {
            font-size: 11px;
            gap: 4px;
        }
    }

    .player-meta span {
        background: #f1efe8;
        padding: 1px 5px;
        border-radius: 99px;
        white-space: nowrap;
    }

    /* ── Pagination ─────────────────────────────────────── */
    .pagination {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 14px;
        font-size: 12px;
        color: #666;
    }

    .pagination-buttons {
        display: flex;
        gap: 6px;
    }

    .pagination-buttons button {
        width: 30px;
        height: 30px;
        border: 1px solid #ddd;
        background: #fff;
        border-radius: 6px;
        cursor: pointer;
        font-size: 13px;
        transition: border-color 0.15s ease, color 0.15s ease;
    }

    .pagination-buttons button:hover:not(:disabled) {
        border-color: var(--primary-red);
        color: var(--primary-red);
    }

    .pagination-buttons button:disabled {
        opacity: 0.4;
        cursor: not-allowed;
    }

    /* ── Empty state ────────────────────────────────────── */
    .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: #666;
    }

    .empty-state h3 {
        font-size: 16px;
        margin: 0 0 6px 0;
        color: #111;
    }

    .empty-state p {
        font-size: 13px;
        margin: 0;
    }

    .sync-button {
        display: inline-block;
        margin-top: 12px;
        background: var(--primary-red);
        color: #fff;
        padding: 7px 14px;
        border-radius: 6px;
        text-decoration: none;
        font-size: 12px;
    }

    /* ── Sync btn (fixed top-right, admin only) ─────────── */
    .sync-btn {
        position: fixed;
        top: 10px;
        right: 10px;
        z-index: 999;
        background: var(--primary-red);
        color: #fff;
        padding: 6px 10px;
        border-radius: 6px;
        text-decoration: none;
        font-size: 12px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
    }

    /* ── Flash message ──────────────────────────────────── */
    .flash-success {
        background: #d4edda;
        color: #155724;
        padding: 6px 10px;
        border-radius: 4px;
        margin-bottom: 10px;
        font-size: 13px;
    }

    /* ── Utility ────────────────────────────────────────── */
    .hidden {
        display: none !important;
    }
</style>
@endpush

@section('content')

@if($isAdmin)
    <a href="{{ route('players.sync') }}" class="sync-btn">Sync</a>
@endif

<div class="p-container">

    {{-- Header --}}
    <div style="margin-bottom:8px;">
        <h1 style="margin:0;font-size:20px;font-weight:700;color:#111;">Players</h1>
    </div>

    {{-- Search & Filters --}}
    <div class="search-row">

        <div class="search-wrapper">
            <input
                type="text"
                id="search-input"
                placeholder="Search..."
                value="{{ $search ?? '' }}"
            >
            <button
                type="button"
                id="clear-search"
                class="clear-btn {{ empty($search) ? 'hidden' : '' }}"
                aria-label="Clear search"
            >✕</button>
        </div>

        @foreach($filters as $type => $items)
            <span class="filter-divider" aria-hidden="true"></span>
            @foreach($items as $item)
                @php
                    $active = (($$type ?? '') === $item['value']) || (empty($$type) && $item['value'] === '');
                @endphp
                <button
                    type="button"
                    class="filter-chip {{ $active ? 'active' : '' }}"
                    data-filter="{{ $type }}"
                    data-value="{{ $item['value'] }}"
                >
                    @if($item['icon'])
                        <i class="fas fa-{{ $item['icon'] }}" style="font-size:9px;" aria-hidden="true"></i>
                    @endif
                    <span>{{ $item['label'] }}</span>
                </button>
            @endforeach
        @endforeach

    </div>
    {{-- /.search-row --}}

    {{-- Flash message --}}
    @if(session('success'))
        <div class="flash-success">{{ session('success') }}</div>
    @endif

    {{-- Players content — replaced by JS on filter/search/paginate --}}
    <div id="players-content">

        @if($players->count())

            <div class="players-grid">

                @foreach($players as $player)
                    <a href="{{ route('players.overview', $player->id) }}" class="player-card">

                        <div class="player-avatar">
                            @if($player->image_url)
                                <img
                                    src="{{ $player->image_url }}"
                                    alt="{{ $player->name }}"
                                    loading="lazy"
                                >
                            @else
                                <div class="avatar-placeholder">
                                    {{ strtoupper(substr($player->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>

                        <div class="player-info">
                            <h3 class="player-name">{{ $player->name }}</h3>
                            <div class="player-meta">
                                <span>{{ ucfirst($player->position) }}</span>
                                <span>{{ $player->standardized_category }}</span>
                            </div>
                        </div>

                    </a>
                @endforeach

            </div>
            {{-- /.players-grid --}}

        @else

            <div class="empty-state">
                <h3>No Players Found</h3>
                <p>Try adjusting your filters.</p>
                @if($isAdmin)
                    <a href="{{ route('players.sync') }}" class="sync-button">Sync Players</a>
                @endif
            </div>

        @endif

    </div>
    {{-- /#players-content --}}

    {{-- Pagination (server-rendered initial state) --}}
    @if($players->hasPages())
        <div class="pagination">
            <span>
                {{ $players->firstItem() }} - {{ $players->lastItem() }} of {{ $players->total() }}
            </span>
            <div class="pagination-buttons">
                <button id="prev-page" {{ $players->onFirstPage() ? 'disabled' : '' }}>←</button>
                <button id="next-page" {{ $players->hasMorePages() ? '' : 'disabled' }}>→</button>
            </div>
        </div>
    @endif

</div>
{{-- /.p-container --}}

@endsection

@push('styles')
<style>
/* Player Cards Grid */
.players-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

/* Player Card */
.player-card {
    position: relative;
    display: block;
    text-decoration: none;
    background: var(--white);
    border-radius: 12px;
    box-shadow: var(--shadow-sm);
    overflow: hidden;
    transition: all var(--duration-fast) var(--transition);
    border: 1px solid var(--gray-200);
}

.player-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-md);
    border-color: var(--primary);
}

/* Player Avatar */
.player-avatar {
    position: relative;
    width: 100%;
    height: 180px;
    background: var(--gray-100);
    overflow: hidden;
}

.player-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform var(--duration-normal) var(--transition);
}

.player-card:hover .player-avatar img {
    transform: scale(1.05);
}

.avatar-placeholder {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
    font-size: 3rem;
    font-weight: 700;
    color: var(--gray-600);
    background: linear-gradient(135deg, var(--gray-100), var(--gray-200));
}

/* Player Info */
.player-info {
    padding: 1.25rem;
    background: var(--white);
}

.player-name {
    font-family: 'Roboto', sans-serif;
    font-size: 1.125rem;
    font-weight: 600;
    color: var(--gray-900);
    margin: 0 0 0.75rem 0;
    line-height: 1.3;
    letter-spacing: -0.025em;
}

.player-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 0.875rem;
    font-weight: 500;
    color: var(--gray-600);
    gap: 0.5rem;
}

.player-meta span {
    flex: 1;
    text-align: center;
    padding: 0.25rem 0.5rem;
    background: var(--gray-100);
    border-radius: 6px;
    font-weight: 600;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 3rem 1rem;
    color: var(--gray-600);
}

.empty-state h3 {
    font-family: 'Roboto', sans-serif;
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--gray-900);
    margin-bottom: 0.5rem;
}

.empty-state p {
    font-size: 1rem;
    margin-bottom: 1rem;
}

.sync-button {
    display: inline-block;
    padding: 0.75rem 1.5rem;
    background: var(--primary-red);
    color: var(--white);
    text-decoration: none;
    border-radius: 8px;
    font-weight: 600;
    transition: background var(--duration-fast);
}

.sync-button:hover {
    background: var(--primary-red-dark);
}

/* Pagination */
.pagination {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 0;
    border-top: 1px solid var(--gray-200);
    margin-top: 2rem;
    font-size: 0.875rem;
    color: var(--gray-700);
}

.pagination-buttons {
    display: flex;
    gap: 0.5rem;
}

.pagination-buttons button {
    padding: 0.5rem 1rem;
    border: 1px solid var(--gray-300);
    background: var(--white);
    color: var(--gray-900);
    border-radius: 6px;
    cursor: pointer;
    font-weight: 500;
    transition: all var(--duration-fast);
}

.pagination-buttons button:hover:not(:disabled) {
    background: var(--primary);
    color: var(--white);
    border-color: var(--primary);
}

.pagination-buttons button:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .players-grid {
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 1rem;
    }

    .player-avatar {
        height: 160px;
    }

    .player-info {
        padding: 1rem;
    }

    .player-name {
        font-size: 1rem;
    }

    .player-meta {
        font-size: 0.8rem;
    }

    .pagination {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {

    const searchInput = document.getElementById('search-input');
    const clearBtn    = document.getElementById('clear-search');

    let filters = {
        search:   @json($search   ?? ''),
        gender:   @json($gender   ?? ''),
        category: @json($category ?? ''),
        page:     {{ $players->currentPage() }}
    };

    /* ── Helpers ─────────────────────────────────────────── */

    function escapeHtml(text) {
        if (text === null || text === undefined) return '';
        return String(text)
            .replace(/&/g,  '&amp;')
            .replace(/</g,  '&lt;')
            .replace(/>/g,  '&gt;')
            .replace(/"/g,  '&quot;')
            .replace(/'/g,  '&#039;');
    }

    function toggleClearButton() {
        clearBtn.classList.toggle('hidden', !searchInput.value.trim());
    }

    toggleClearButton();

    /* ── Search ──────────────────────────────────────────── */

    let timeout;

    searchInput?.addEventListener('input', () => {
        clearTimeout(timeout);
        toggleClearButton();
        timeout = setTimeout(() => {
            filters.search = searchInput.value.trim();
            filters.page   = 1;
            fetchPlayers();
        }, 300);
    });

    clearBtn?.addEventListener('click', () => {
        searchInput.value = '';
        filters.search    = '';
        filters.page      = 1;
        toggleClearButton();
        fetchPlayers();
    });

    /* ── Filter chips ────────────────────────────────────── */

    document.addEventListener('click', e => {
        const chip = e.target.closest('.filter-chip');
        if (!chip) return;

        // Deactivate all chips sharing the same data-filter group
        const filterKey = chip.dataset.filter;
        document.querySelectorAll(`.filter-chip[data-filter="${filterKey}"]`)
            .forEach(btn => btn.classList.remove('active'));

        chip.classList.add('active');
        filters[filterKey] = chip.dataset.value;
        filters.page = 1;

        if (window.innerWidth <= 768) {
            chip.scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' });
        }

        fetchPlayers();
    });

    /* ── Pagination (delegated — works on both server & JS-rendered buttons) ── */

    document.addEventListener('click', e => {
        if (e.target.id === 'prev-page' && !e.target.disabled) {
            filters.page = Math.max(1, filters.page - 1);
            fetchPlayers();
        }
        if (e.target.id === 'next-page' && !e.target.disabled) {
            filters.page += 1;
            fetchPlayers();
        }
    });

    /* ── Fetch ───────────────────────────────────────────── */

    function fetchPlayers() {
        const params = new URLSearchParams(filters);
        window.history.pushState({}, '', `${window.location.pathname}?${params}`);

        fetch(`/api/players?${params}`)
            .then(res => {
                if (!res.ok) throw new Error('Network error');
                return res.json();
            })
            .then(data => renderPlayers(data))
            .catch(err => console.error('Fetch error:', err));
    }

    /* ── Render ──────────────────────────────────────────── */

    function renderPlayers(response) {
        const container = document.getElementById('players-content');

        if (!response.success || !response.data || response.data.length === 0) {
            container.innerHTML = `
                <div class="empty-state">
                    <h3>No Players Found</h3>
                    <p>Try adjusting your filters.</p>
                    @if($isAdmin)
                        <a href="{{ route('players.sync') }}" class="sync-button">Sync Players</a>
                    @endif
                </div>`;
            return;
        }

        let html = '<div class="players-grid">';

        html += response.data.map(player => {
            const name     = escapeHtml(player.name);
            const initial  = name ? name.charAt(0) : '';
            const position = player.position
                ? escapeHtml(player.position.charAt(0).toUpperCase() + player.position.slice(1))
                : '';
            const category = escapeHtml(player.standardized_category || player.category);
            const imageUrl = player.image_url ? escapeHtml(player.image_url) : null;

            return `
                <a href="/players/${player.id}/overview" class="player-card">
                    <div class="player-avatar">
                        ${imageUrl
                            ? `<img src="${imageUrl}" alt="${name}" loading="lazy">`
                            : `<div class="avatar-placeholder">${initial}</div>`
                        }
                    </div>
                    <div class="player-info">
                        <h3 class="player-name">${name}</h3>
                        <div class="player-meta">
                            <span>${position}</span>
                            <span>${category}</span>
                        </div>
                    </div>
                </a>`;
        }).join('');

        html += '</div>'; {{-- /.players-grid --}}

        if (response.pagination && response.pagination.last_page > 1) {
            const p = response.pagination;
            html += `
                <div class="pagination">
                    <span>${p.from} - ${p.to} of ${p.total}</span>
                    <div class="pagination-buttons">
                        <button id="prev-page" ${p.current_page <= 1             ? 'disabled' : ''}>←</button>
                        <button id="next-page" ${p.current_page >= p.last_page   ? 'disabled' : ''}>→</button>
                    </div>
                </div>`;
        }

        container.innerHTML = html;
    }

});
</script>
@endpush
