@props([
    'items' => [],
    'categories' => [],
    'showFilters' => true,
])

{{--
    Filterable photo grid. Each tile is a <button> so the lightbox can open on
    click and keyboard alike; captions sit in .v-gallery__cap because site.js
    reads them when composing the lightbox caption.
--}}

@if (!empty($items))
    @if ($showFilters && !empty($categories))
        <div class="v-gallery-filters" role="group" aria-label="Filter photos by category">
            <button class="v-filter" type="button" data-filter="all" aria-pressed="true">All</button>
            @foreach ($categories as $category)
                <button class="v-filter" type="button" data-filter="{{ $category }}" aria-pressed="false">
                    {{ $category }}
                </button>
            @endforeach
        </div>
    @endif

    <div class="v-gallery" data-gallery-grid>
        @foreach ($items as $item)
            @php
                $span = $item['span'] ?? 'normal';
                $spanClass = $span === 'wide' ? ' v-gallery__item--wide'
                    : ($span === 'tall' ? ' v-gallery__item--tall' : '');
                $exists = !empty($item['src']) && @file_exists(public_path($item['src']));
            @endphp

            @continue(!$exists)

            <button class="v-gallery__item{{ $spanClass }}" type="button"
                    data-category="{{ $item['category'] ?? 'Other' }}">
                <img src="{{ asset($item['src']) }}" alt="{{ $item['alt'] ?? '' }}"
                     loading="lazy" decoding="async">

                <span class="v-gallery__cap">
                    @if (!empty($item['category']))
                        <span class="v-gallery__cat">{{ $item['category'] }}</span>
                    @endif
                    <span>{{ $item['caption'] ?? ($item['alt'] ?? '') }}</span>
                </span>

                <span class="v-visually-hidden">
                    View photo{{ !empty($item['caption']) ? ': ' . $item['caption'] : '' }}
                </span>
            </button>
        @endforeach
    </div>
@else
    <x-site.placeholder-note
        eyebrow="Gallery"
        note="No photographs have been published yet. Add entries under gallery in config/vipers.php." />
@endif
