@extends('layouts.academy')

@section('title', 'Our Players - Vipers Academy')

 @php
     $isAdmin = auth()->check() && auth()->user()->is_admin;

     $filters = [
         'gender' => [
             ['label' => 'All', 'value' => '', 'icon' => ''],
             ['label' => 'Men', 'value' => 'M', 'icon' => 'mars'],
             ['label' => 'Women', 'value' => 'F', 'icon' => 'venus'],
         ],

         'category' => [
             ['label' => 'All', 'value' => '', 'icon' => ''],
             ['label' => 'Jr', 'value' => 'junior', 'icon' => 'seedling'],
             ['label' => 'Sr', 'value' => 'senior', 'icon' => 'crown'],
         ]
     ];
 @endphp

 @section('content')

 @if($isAdmin)
 <a href="{{ route('players.sync') }}" class="sync-btn" style="position:fixed;top:10px;right:10px;z-index:999;background:var(--primary-red);color:#fff;padding:6px 10px;border-radius:6px;text-decoration:none;font-size:12px;box-shadow:0 2px 6px rgba(0,0,0,0.15);">Sync</a>
 @endif

 <div class="p-container" style="max-width:1200px;margin:auto;padding:10px;font-family:system-ui,-apple-system,sans-serif;">

  <div style="margin-bottom:8px;display:flex;justify-content:space-between;align-items:center;">
   <h1 style="margin:0;font-size:20px;font-weight:700;color:#111;">Players</h1>
  </div>

  <div style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:12px;align-items:stretch;">

   <input type="text" id="search-input" placeholder="Search..." value="{{ $search ?? '' }}" style="flex:1;min-width:160px;padding:6px 28px 6px 10px;border:1px solid #ddd;border-radius:6px;font-size:13px;">

   <button type="button" id="clear-search" class="{{ empty($search) ? 'hidden' : '' }}" style="position:absolute;right:8px;top:50%;transform:translateY(-50%);border:none;background:none;cursor:pointer;font-size:14px;color:#999;padding:2px 4px;line-height:1;">✕</button>

   @foreach($filters as $type => $items)
    @foreach($items as $item)
     @php
         $active = (($$type ?? '') === $item['value']) || (empty($$type) && $item['value'] === '');
     @endphp
     <button type="button" class="filter-chip {{ $active ? 'active' : '' }}" data-filter="{{ $type }}" data-value="{{ $item['value'] }}" style="display:inline-flex;align-items:center;gap:4px;padding:5px 8px;border:1px solid #ddd;border-radius:99px;background:#fff;cursor:pointer;font-size:12px;font-weight:500;white-space:nowrap;flex-shrink:0;transition:all .15s ease;min-height:28px;{{ $active ? 'background:#e63946;color:#fff;border-color:#e63946;' : '' }}">
      @if($item['icon'])
       <i class="fas fa-{{ $item['icon'] }}" style="font-size:10px;"></i>
      @endif
      <span>{{ $item['label'] }}</span>
     </button>
    @endforeach
   @endforeach

  </div>

  @if(session('success'))
   <div style="background:#d4edda;color:#155724;padding:6px 10px;border-radius:4px;margin-bottom:10px;font-size:13px;">{{ session('success') }}</div>
  @endif

  <div id="players-content">

        @if($players->count())

            <div class="players-grid">

                @foreach($players as $player)

                    <a
                        href="{{ route('players.overview', $player->id) }}"
                        class="player-card"
                    >

                        {{-- IMAGE --}}
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

                            {{-- INFO --}}
                        <div class="player-info">

                            <h3 class="player-name">
                                {{ $player->name }}
                            </h3>

                            <div class="player-meta">
                                <span>{{ ucfirst($player->position) }}</span>

                                <span>
                                    {{ $player->standardized_category }}
                                </span>
                            </div>

                        </div>

                    </a>

                @endforeach

            </div>

        @else

            <div class="empty-state">
                <h3>No Players Found</h3>
                <p>Try adjusting your filters.</p>

                @if($isAdmin)
                    <a href="{{ route('players.sync') }}" class="sync-button">
                        Sync Players
                    </a>
                @endif
            </div>

        @endif

    </div>

    {{-- PAGINATION --}}
    @if($players->hasPages())

        <div class="pagination">

            <span>
                {{ $players->firstItem() }}
                -
                {{ $players->lastItem() }}
                of
                {{ $players->total() }}
            </span>

            <div class="pagination-buttons">

                <button
                    id="prev-page"
                    {{ $players->onFirstPage() ? 'disabled' : '' }}
                >
                    ←
                </button>

                <button
                    id="next-page"
                    {{ $players->hasMorePages() ? '' : 'disabled' }}
                >
                    →
                </button>

            </div>

        </div>

    @endif

</div>

  <div id="players-content"></div>

 </div>

 @endsection

 @push('scripts')
 <script>
 document.addEventListener('DOMContentLoaded', () => {
     const searchInput = document.getElementById('search-input');
     const clearBtn = document.getElementById('clear-search');

     let filters = {
         search: @json($search ?? ''),
         gender: @json($gender ?? ''),
         category: @json($category ?? ''),
         page: {{ $players->currentPage() }}
     };

     function escapeHtml(text) {
         if (text === null || text === undefined) return '';
         return String(text)
             .replace(/&/g, '&amp;')
             .replace(/</g, '&lt;')
             .replace(/>/g, '&gt;')
             .replace(/"/g, '&quot;')
             .replace(/'/g, '&#039;');
     }

     function toggleClearButton() {
         clearBtn.classList.toggle('hidden', !searchInput.value.trim());
     }
     toggleClearButton();

     let timeout;
     searchInput?.addEventListener('input', () => {
         clearTimeout(timeout);
         toggleClearButton();
         timeout = setTimeout(() => {
             filters.search = searchInput.value.trim();
             filters.page = 1;
             fetchPlayers();
         }, 300);
     });

     clearBtn?.addEventListener('click', () => {
         searchInput.value = '';
         filters.search = '';
         filters.page = 1;
         toggleClearButton();
         fetchPlayers();
     });

     document.addEventListener('click', e => {
         const chip = e.target.closest('.filter-chip');
         if (!chip) return;
         const group = chip.parentElement;
         group.querySelectorAll('.filter-chip').forEach(btn => btn.classList.remove('active'));
         chip.classList.add('active');
         filters[chip.dataset.filter] = chip.dataset.value;
         filters.page = 1;
         if (window.innerWidth <= 768) {
             chip.scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' });
         }
         fetchPlayers();
     });

     function fetchPlayers() {
         const params = new URLSearchParams(filters);
         window.history.pushState({}, '', `${window.location.pathname}?${params}`);
         fetch(`/api/players?${params}`)
             .then(res => { if (!res.ok) throw new Error('Network error'); return res.json(); })
             .then(data => renderPlayers(data))
             .catch(err => console.error('Fetch error:', err));
     }

     function renderPlayers(response) {
         const container = document.getElementById('players-content');
         if (!response.success || !response.data || response.data.length === 0) {
             container.innerHTML = `
              <div style="text-align:center;padding:30px 10px;">
               <h3 style="margin:0 0 8px 0;font-size:16px;color:#333;">No Players Found</h3>
               <p style="margin:0 0 12px 0;color:#666;font-size:13px;">Adjust filters</p>
               @if($isAdmin)
                <a href="{{ route('players.sync') }}" class="sync-button" style="display:inline-block;background:var(--primary-red);color:#fff;padding:6px 12px;border-radius:6px;text-decoration:none;font-size:12px;">Sync</a>
               @endif
              </div>`;
             return;
         }

         const players = response.data;
         let html = '<div style="display:grid;grid-template-columns:repeat(6,1fr);gap:10px;justify-content:start;justify-items:stretch;align-items:start;">';
         html += players.map(player => {
             const name = escapeHtml(player.name);
             const initial = name ? name.charAt(0) : '';
             const position = player.position ? escapeHtml(player.position.charAt(0).toUpperCase() + player.position.slice(1)) : '';
             const category = escapeHtml(player.standardized_category || player.category);
             const imageUrl = player.image_url ? escapeHtml(player.image_url) : null;
             return `
              <a href="/players/${player.id}/overview" class="player-card" style="display:flex;flex-direction:column;gap:6px;padding:10px;border:1px solid #eee;border-radius:8px;background:#fff;text-decoration:none;color:inherit;transition:.2s;width:100%;">
               <div class="player-avatar" style="width:50px;height:50px;border-radius:50%;overflow:hidden;flex-shrink:0;margin:0 auto;border:2px solid var(--primary-red,#e63946);">
                ${imageUrl ? `<img src="${imageUrl}" alt="${name}" loading="lazy" style="width:100%;height:100%;object-fit:cover;">` : `<div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:var(--primary-red,#e63946);color:#fff;font-weight:700;font-size:14px;">${initial}</div>`}
               </div>
               <div class="player-info" style="text-align:center;">
                <h3 class="player-name" style="margin:0;font-size:13px;line-height:1.2;color:#111;">${name}</h3>
                <div class="player-meta" style="display:flex;flex-wrap:wrap;gap:4px;justify-content:center;font-size:11px;color:#666;margin-top:2px;">
                 <span>${position}</span>
                 <span>${category}</span>
                </div>
               </div>
              </a>`;
         }).join('') + '</div>';

         if (response.pagination && response.pagination.last_page > 1) {
             const prevDisabled = response.pagination.current_page <= 1 ? 'disabled style="opacity:.5;cursor:not-allowed;"' : '';
             const nextDisabled = response.pagination.current_page >= response.pagination.last_page ? 'disabled style="opacity:.5;cursor:not-allowed;"' : '';
             html += `
              <div class="pagination" style="display:flex;justify-content:space-between;align-items:center;margin-top:12px;">
               <span style="font-size:12px;color:#666;">${response.pagination.from} - ${response.pagination.to} of ${response.pagination.total}</span>
               <div class="pagination-buttons" style="display:flex;gap:6px;">
                <button id="prev-page" ${prevDisabled} style="width:28px;height:28px;border:1px solid #ddd;background:#fff;border-radius:6px;cursor:pointer;font-size:12px;">←</button>
                <button id="next-page" ${nextDisabled} style="width:28px;height:28px;border:1px solid #ddd;background:#fff;border-radius:6px;cursor:pointer;font-size:12px;">→</button>
               </div>
              </div>`;
         }

         container.innerHTML = html;
     }

     document.addEventListener('click', (e) => {
         if (e.target.id === 'prev-page' && !e.target.disabled) {
             filters.page = Math.max(1, filters.page - 1);
             fetchPlayers();
         }
         if (e.target.id === 'next-page' && !e.target.disabled) {
             filters.page = filters.page + 1;
             fetchPlayers();
         }
     });

 });
 </script>
 @endpush


@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', () => {

    const searchInput = document.getElementById('search-input');
    const clearBtn = document.getElementById('clear-search');

    let filters = {
        search: @json($search ?? ''),
        gender: @json($gender ?? ''),
        category: @json($category ?? ''),
        page: {{ $players->currentPage() }}
    };

    // Utility: escape HTML to prevent XSS
    function escapeHtml(text) {
        if (text === null || text === undefined) return '';
        return String(text)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    /* SEARCH */

    let timeout;

    searchInput?.addEventListener('input', () => {

        clearTimeout(timeout);

        toggleClearButton();

        timeout = setTimeout(() => {
            filters.search = searchInput.value.trim();
            filters.page = 1; // Reset to page 1 on new search
            fetchPlayers();
        }, 300);

    });

    clearBtn?.addEventListener('click', () => {

        searchInput.value = '';
        filters.search = '';
        filters.page = 1;

        toggleClearButton();

        fetchPlayers();

    });

    function toggleClearButton() {
        clearBtn.classList.toggle(
            'hidden',
            !searchInput.value.trim()
        );
    }

    // Set initial clear button state
    toggleClearButton();

    /* FILTERS */

      document.addEventListener('click', e => {

          const chip = e.target.closest('.filter-chip');

          if(!chip) return;

          const group = chip.parentElement;

          group.querySelectorAll('.filter-chip')
              .forEach(btn => btn.classList.remove('active'));

          chip.classList.add('active');

          filters[chip.dataset.filter] = chip.dataset.value;
          filters.page = 1; // Reset to first page when filter changes

          // Scroll active chip into view center on mobile
          if(window.innerWidth <= 768){
              chip.scrollIntoView({
                  behavior: 'smooth',
                  inline: 'center',
                  block: 'nearest'
              });
          }

          fetchPlayers();

      });

    /* FETCH */

    function fetchPlayers(){

        const params = new URLSearchParams(filters);

        // Update URL without page reload
        window.history.pushState(
            {},
            '',
            `${window.location.pathname}?${params}`
        );

        fetch(`/api/players?${params}`)
            .then(res => {
                if (!res.ok) {
                    throw new Error('Network response was not ok');
                }
                return res.json();
            })
            .then(data => {
                renderPlayers(data);
            })
            .catch(err => {
                console.error('Fetch error:', err);
            });

    }

    function renderPlayers(response) {
        const container = document.getElementById('players-content');

        // Empty state
        if (!response.success || !response.data || response.data.length === 0) {
            container.innerHTML = `
                <div class="empty-state">
                    <h3>No Players Found</h3>
                    <p>Try adjusting your filters.</p>
                    @if($isAdmin)
                        <a href="{{ route('players.sync') }}" class="sync-button">
                            Sync Players
                        </a>
                    @endif
                </div>
            `;
            return;
        }

        const players = response.data;

        // Build players grid
        let html = '<div class="players-grid">';
        html += players.map(player => {
            const name = escapeHtml(player.name);
            const initial = name ? name.charAt(0) : '';
            const position = player.position ? escapeHtml(player.position.charAt(0).toUpperCase() + player.position.slice(1)) : '';
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
                </a>
            `;
        }).join('') + '</div>';

        // Add pagination if multiple pages
        if (response.pagination && response.pagination.last_page > 1) {
            html += `
                <div class="pagination">
                    <span>${response.pagination.from} - ${response.pagination.to} of ${response.pagination.total}</span>
                    <div class="pagination-buttons">
                        <button id="prev-page" ${response.pagination.current_page <= 1 ? 'disabled' : ''}>←</button>
                        <button id="next-page" ${response.pagination.current_page >= response.pagination.last_page ? 'disabled' : ''}>→</button>
                    </div>
                </div>
            `;
        }

        container.innerHTML = html;
    }

    // Pagination button event delegation
    document.addEventListener('click', (e) => {
        if (e.target.id === 'prev-page' && !e.target.disabled) {
            filters.page = Math.max(1, filters.page - 1);
            fetchPlayers();
        }
        if (e.target.id === 'next-page' && !e.target.disabled) {
            filters.page = filters.page + 1;
            fetchPlayers();
        }
    });

});

</script>

@endpush
