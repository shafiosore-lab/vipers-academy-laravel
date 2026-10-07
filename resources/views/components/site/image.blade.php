@props([
    'src' => null,
    'alt' => '',
    'ratio' => null,             // e.g. "16 / 9"
    'placeholderLabel' => 'Photo coming soon',
    'loading' => 'lazy',
    'class' => '',
])

{{--
    Image wrapper that guarantees three things:
      1. An intrinsic aspect-ratio box, so a slow image never shifts layout.
      2. A graceful, clearly-labelled placeholder when the file is absent —
         broken images are worse than an honest empty slot.
      3. Decorative images get an empty alt, meaningful ones get real alt text.
--}}

@php
    $url = $src ? asset($src) : null;
    $exists = $url && @file_exists(public_path($src));

    // The ratio is passed as a custom property rather than written as an
    // inline `aspect-ratio`, so the stylesheet stays in charge of how the box
    // behaves and can override it responsively if it ever needs to.
    $style = $ratio ? "--v-media-ratio: {$ratio};" : null;

    // `v-media--fixed` is the opt-in that makes the box crop to the ratio.
    // Without a ratio the image simply keeps its own natural proportions.
    $classes = trim('v-media ' . ($ratio ? 'v-media--fixed ' : '') . $class);
@endphp

<div class="{{ $classes }}" @if ($style) style="{{ $style }}" @endif>
    @if ($exists)
        <img class="v-media__img" src="{{ $url }}" alt="{{ $alt }}" loading="{{ $loading }}" decoding="async">
    @else
        {{-- Honest empty slot: never render a broken <img> or a stock substitute --}}
        <div class="v-img-placeholder" role="img" aria-label="{{ $placeholderLabel }}">
            <x-site.icon name="quote" />
            <span>{{ $placeholderLabel }}</span>
        </div>
    @endif
</div>
