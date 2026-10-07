@props(['story' => [], 'delay' => 0])

@php
    $slug = $story['slug'] ?? '';
    $hasImage = !empty($story['image']) && @file_exists(public_path($story['image']));
    $date = !empty($story['date']) ? \Carbon\Carbon::parse($story['date']) : null;
@endphp

<article class="v-card v-card--link v-reveal" data-delay="{{ $delay % 3 }}">
    <div class="v-card__media">
        @if ($hasImage)
            <img src="{{ asset($story['image']) }}" alt="{{ $story['image_alt'] ?? '' }}"
                 loading="lazy" decoding="async">
        @else
            <div class="v-img-placeholder" role="img" aria-label="Story photo to be supplied">
                <x-site.icon name="quote" />
                <span>Story photo</span>
            </div>
        @endif
    </div>

    <div class="v-card__body">
        <div class="v-story__meta">
            @if (!empty($story['category']))
                <span>{{ $story['category'] }}</span>
            @endif
            @if ($date)
                <span class="v-story__dot" aria-hidden="true"></span>
                <time datetime="{{ $date->toDateString() }}">{{ $date->format('j M Y') }}</time>
            @endif
        </div>

        <h3 class="v-card__title">{{ $story['title'] ?? '' }}</h3>
        <p class="v-card__text">{{ $story['excerpt'] ?? '' }}</p>

        <p class="v-card__foot">
            <span class="v-link-arrow">
                Read story
                <x-site.icon name="arrow" class="v-btn__icon" />
            </span>
        </p>
    </div>

    @if ($slug)
        <a class="v-card__overlay" href="{{ route('site.stories.show', $slug) }}">
            <span class="v-visually-hidden">{{ $story['title'] ?? 'Story' }} — read the full story</span>
        </a>
    @endif
</article>
