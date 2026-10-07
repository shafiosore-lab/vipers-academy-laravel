@props([
    'eyebrow' => null,
    'title' => null,
    'lead' => null,
    'align' => null,   // center | split | split-group
    'id' => null,
    'tag' => 'h2',
])

@php
    $classes = ['v-section-head'];
    if ($align === 'center') {
        $classes[] = 'v-section-head--center';
    } elseif (in_array($align, ['split', 'split-group'], true)) {
        $classes[] = 'v-section-head--split';
    }

    /* split-group wraps eyebrow + title in an inner div so the heading block
     * and the lead sit in the two grid columns; plain split keeps them as
     * separate grid items. */
    $grouped = $align === 'split-group';
@endphp

<div class="{{ implode(' ', $classes) }}">
    @if ($grouped)
        <div>
    @endif

    @if ($eyebrow)
        <p class="v-eyebrow">{{ $eyebrow }}</p>
    @endif

    @if ($title)
        <{{ $tag }} @if ($id) id="{{ $id }}" @endif class="v-h2">{{ $title }}</{{ $tag }}>
    @endif

    @if ($grouped)
        </div>
    @endif

    @if ($lead)
        <p class="v-lead">{{ $lead }}</p>
    @endif

    {{ $slot }}
</div>
