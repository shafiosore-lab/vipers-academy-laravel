@props(['program' => [], 'delay' => 0])

@php
    $slug = $program['slug'] ?? '';
    $accent = 'v-accent-' . ($program['accent'] ?? 'gold');
    $hasImage = !empty($program['image']) && @file_exists(public_path($program['image']));
@endphp

<article class="v-card v-card--link v-reveal {{ $accent }}" data-delay="{{ $delay % 4 }}">
    <div class="v-card__media">
        <span class="v-card__stripe" aria-hidden="true"></span>

        @if ($hasImage)
            <img src="{{ asset($program['image']) }}" alt="{{ $program['image_alt'] ?? '' }}"
                 loading="lazy" decoding="async">
        @else
            <div class="v-img-placeholder" role="img" aria-label="Programme photo to be supplied">
                <x-site.icon name="quote" />
                <span>Programme photo</span>
            </div>
        @endif

        <span class="v-card__chip">
            <span class="v-icon-chip">
                <x-site.icon :name="$program['icon'] ?? 'target'" />
            </span>
        </span>
    </div>

    <div class="v-card__body">
        <h3 class="v-card__title">{{ $program['name'] ?? '' }}</h3>
        <p class="v-card__text">{{ $program['tagline'] ?? ($program['summary'] ?? '') }}</p>
        {{-- Visual affordance only — the real link is the card overlay below,
             so the card exposes exactly one tab stop. --}}
        <p class="v-card__foot">
            <span class="v-link-arrow">
                Learn more
                <x-site.icon name="arrow" class="v-btn__icon" />
            </span>
        </p>
    </div>

    {{-- One link per card: the overlay keeps a single, correctly-labelled
         tab stop while the visible button stays an ordinary link. --}}
    @if ($slug)
        <a class="v-card__overlay" href="{{ route('site.programs.show', $slug) }}">
            <span class="v-visually-hidden">{{ $program['name'] ?? 'Programme' }} — learn more</span>
        </a>
    @endif
</article>
