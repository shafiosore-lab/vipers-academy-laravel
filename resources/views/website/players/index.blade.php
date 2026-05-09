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
             ['label' => 'Junior', 'value' => 'junior', 'icon' => 'seedling', 'abbr' => 'Jr'],
             ['label' => 'Senior', 'value' => 'senior', 'icon' => 'crown', 'abbr' => 'Sr'],
         ]
     ];
@endphp

 @section('content')

 <div class="players-container">

     {{-- HEADER --}}
     <div class="page-header">
         <h1 class="page-title">Our Players</h1>

         @if($isAdmin)
             <a href="{{ route('players.sync') }}" class="sync-button">
                 <i class="fas fa-sync-alt"></i> Sync
             </a>
         @endif
     </div>

    {{-- FILTERS --}}
    <div class="filters-bar">

        {{-- SEARCH --}}
        <div class="search-box">
            <input
                type="text"
                id="search-input"
                placeholder="Search players..."
                value="{{ $search ?? '' }}"
            >

            <button
                type="button"
                id="clear-search"
                class="{{ empty($search) ? 'hidden' : '' }}"
            >
                ✕
            </button>
        </div>

        {{-- FILTER GROUPS --}}
        @foreach($filters as $type => $items)
            <div class="filter-group">

                @foreach($items as $item)

                     @php
                         $active =
                             (($$type ?? '') === $item['value']) ||
                             (empty($$type) && $item['value'] === '');
                         $icon = $item['icon'] ?? '';
                         $abbr = $item['abbr'] ?? $item['label'];
                     @endphp

                     <button
                         type="button"
                         class="filter-chip {{ $active ? 'active' : '' }}"
                         data-filter="{{ $type }}"
                         data-value="{{ $item['value'] }}"
                     >
                         @if($icon)
                             <i class="fas fa-{{ $icon }}"></i>
                         @endif
                         <span>{{ $abbr }}</span>
                     </button>

                @endforeach

            </div>
        @endforeach

    </div>

    {{-- SUCCESS --}}
    @if(session('success'))
        <div class="success-message">
            {{ session('success') }}
        </div>
    @endif

    {{-- PLAYERS --}}
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

@endsection


@push('styles')

<style>

 .players-container{
     max-width:1200px;
     margin:auto;
     padding:1rem;
 }

 @media(max-width:768px){
     .players-container{
         padding:.75rem;
     }
 }

  /* HEADER */

  .page-header{
      display:flex;
      justify-content:space-between;
      align-items:center;
      margin-bottom:.5rem;
      gap:.4rem;
  }

  @media(max-width:768px){
      .page-header{
          margin-bottom:.4rem;
          gap:.3rem;
      }
  }

  .page-title{
      font-size:1.5rem;
      font-weight:700;
  }

/* BUTTON */

.sync-button{
    padding:.6rem 1rem;
    background:var(--primary-red);
    color:#fff;
    border-radius:8px;
    text-decoration:none;
}

  /* FILTERS */

  .filters-bar{
      display:flex;
      gap:.4rem;
      flex-wrap:wrap;
      margin-bottom:.5rem;
      align-items:stretch;
  }

  @media(max-width:768px){
      .filters-bar{
          margin-bottom:.5rem;
          gap:.3rem;
      }
  }

  .search-box{
      position:relative;
      flex:1;
      min-width:180px;
  }

  .search-box input{
      width:100%;
      padding:.4rem 1.8rem .4rem .6rem;
      border:1px solid #ddd;
      border-radius:6px;
      font-size:.813rem;
  }

  #clear-search{
      position:absolute;
      right:.4rem;
      top:50%;
      transform:translateY(-50%);
      border:none;
      background:none;
      cursor:pointer;
      font-size:.875rem;
      color:#999;
      padding:.2rem;
      line-height:1;
  }

  .filter-group{
      display:flex;
      gap:.25rem;
      flex-wrap:nowrap;
      overflow-x:auto;
      -webkit-overflow-scrolling:touch;
      scrollbar-width:none;
      -ms-overflow-style:none;
      min-width:0;
      flex:1;
      max-width:100%;
      padding:.15rem 0;
      position:relative;
      mask-image:linear-gradient(to right, transparent, black 3%, black 97%, transparent);
      -webkit-mask-image:linear-gradient(to right, transparent, black 3%, black 97%, transparent);
  }

 .filter-group::-webkit-scrollbar{
     display:none;
 }

 .filter-group:after{
     content:'';
     position:sticky;
     right:0;
     top:0;
     bottom:0;
     width:20px;
     background:linear-gradient(to left, rgba(255,255,255,1), transparent);
     pointer-events:none;
     flex-shrink:0;
     display:none;
 }

 @media(max-width:768px){
     .filter-group:after{
         display:block;
     }
 }

  .filter-chip{
      display:inline-flex;
      align-items:center;
      gap:.2rem;
      padding:.35rem .5rem;
      border:1px solid #ddd;
      border-radius:999px;
      background:#fff;
      cursor:pointer;
      font-size:.75rem;
      font-weight:500;
      white-space:nowrap;
      flex-shrink:0;
      transition:all .15s ease;
      min-height:32px;
      scroll-margin-inline:4px;
  }

  .filter-chip i{
      font-size:.6rem;
      line-height:1;
  }

  @media(max-width:768px){
      .filter-group{
          scroll-snap-type:x mandatory;
          -webkit-overflow-scrolling:touch;
      }

      .filter-chip{
          scroll-snap-align:start;
          min-height:36px;
          padding:.4rem .55rem;
          font-size:.7rem;
      }

      .filter-chip i{
          font-size:.55rem;
      }
  }

 .filter-chip.active{
     background:var(--primary-red);
     color:#fff;
     border-color:var(--primary-red);
 }

 .filter-chip:active{
     transform:scale(.96);
 }

 /* GRID */

 .players-grid{
     display:grid;
     grid-template-columns:repeat(6,1fr);
     gap:.75rem;
     justify-content:start;
     justify-items:start;
     align-items:start;
 }

 /* CARD */

 .player-card{
     display:flex;
     flex-direction:column;
     gap:.5rem;
     padding:.75rem;
     border:1px solid #eee;
     border-radius:10px;
     background:#fff;
     text-decoration:none;
     color:inherit;
     transition:.2s;
     width:100%;
 }

 .player-card:hover{
     transform:translateY(-2px);
     border-color:var(--primary-red);
 }

 .player-avatar{
     width:60px;
     height:60px;
     border-radius:50%;
     overflow:hidden;
     flex-shrink:0;
     margin:0 auto;
 }

 .player-avatar img{
     width:100%;
     height:100%;
     object-fit:cover;
 }

 .avatar-placeholder{
     width:100%;
     height:100%;
     display:flex;
     align-items:center;
     justify-content:center;
     background:var(--primary-red);
     color:#fff;
     font-weight:700;
     font-size:1.1rem;
 }

 .player-name{
     font-size:.875rem;
     margin:0;
     line-height:1.25;
     text-align:center;
 }

 .player-meta{
     display:flex;
     flex-wrap:wrap;
     gap:.3rem;
     font-size:.7rem;
     color:#666;
     justify-content:center;
 }

/* EMPTY */

.empty-state{
    text-align:center;
    padding:3rem 1rem;
}

/* PAGINATION */

.pagination{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-top:2rem;
}

.pagination-buttons{
    display:flex;
    gap:.5rem;
}

.pagination button{
    width:36px;
    height:36px;
    border:1px solid #ddd;
    background:#fff;
    border-radius:8px;
    cursor:pointer;
}

.pagination button:disabled{
    opacity:.4;
    cursor:not-allowed;
}

/* UTIL */

.hidden{
    display:none;
}

 @media(max-width:768px){

     .players-container{
         padding:.75rem;
     }

      .page-header{
          margin-bottom:.4rem;
          gap:.3rem;
      }

      .page-title{
          font-size:1.25rem;
      }

      .sync-button{
          padding:.45rem .65rem;
          font-size:.7rem;
      }

      .filters-bar{
          flex-direction:column;
          gap:.3rem;
      }

      .search-box{
          flex:0 0 auto;
          width:100%;
      }

      .search-box input{
          padding:.4rem 1.8rem .4rem .6rem;
          font-size:.813rem;
      }

      .filter-group{
          flex:0 0 auto;
          width:100%;
          overflow-x:auto;
          -webkit-overflow-scrolling:touch;
          scrollbar-width:none;
          -ms-overflow-style:none;
          padding:.1rem 0;
          scroll-behavior:smooth;
          position:relative;
          mask-image:linear-gradient(to right, transparent, black 3%, black 97%, transparent);
          -webkit-mask-image:linear-gradient(to right, transparent, black 3%, black 97%, transparent);
          scroll-snap-type:x mandatory;
          gap:.2rem;
      }

     .filter-group::-webkit-scrollbar{
         display:none;
     }

       .filter-chip{
           scroll-snap-align:start;
           min-height:38px;
           padding:.45rem .6rem;
           font-size:.7rem;
           border-width:1px;
           border-radius:999px;
           scroll-margin-inline:5px;
       }

     .filter-chip i{
         font-size:.65rem;
     }

     .players-grid{
         grid-template-columns:repeat(5,1fr);
         gap:.5rem;
     }

     .player-card{
         padding:.5rem;
         gap:.4rem;
         border-radius:8px;
     }

     .player-avatar{
         width:48px;
         height:48px;
     }

     .player-avatar img,
     .avatar-placeholder{
         font-size:.9rem;
     }

     .player-name{
         font-size:.75rem;
         line-height:1.2;
     }

     .player-meta{
         font-size:.6rem;
         gap:.2rem;
     }

     .empty-state{
         padding:2rem .75rem;
     }

     .empty-state h3{
         font-size:1.1rem;
     }

     .empty-state p{
         font-size:.85rem;
     }

     .pagination{
         margin-top:1.25rem;
         gap:.5rem;
     }

      .pagination-buttons button{
          width:40px;
          height:40px;
          font-size:1rem;
          border-radius:8px;
      }
  }


</style>

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
