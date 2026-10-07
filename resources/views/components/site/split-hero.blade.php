@props([
    'eyebrow' => null,
    'title' => null,
    'accent' => null,
    'script' => null,
    'lead' => null,
    'image' => null,
    'imageAlt' => '',
    'badge' => null,
    'actions' => [],
])

{{-- Split editorial hero: copy left, photo right with floating badge. --}}
<section class="v-pagehero v-pagehero--split v-on-dark" aria-label="{{ strip_tags($eyebrow ?? $title ?? 'Page hero') }}">
    <div class="v-container v-pagehero--split__grid">
        <div class="v-reveal">
            @if ($eyebrow)
                <p class="v-eyebrow">{!! $eyebrow !!}</p>
            @endif
            <h1 class="v-h1 v-pagehero__title">{!! $title !!}
                @if ($accent)
                    <em>{{ $accent }}</em>
                @endif
            </h1>
            @if ($script)
                <p class="v-script v-pagehero--split__script">{{ $script }}</p>
            @endif
            @if ($lead)
                <p class="v-lead v-pagehero__text">{{ $lead }}</p>
            @endif
            @if (count($actions))
                <div class="v-btn-row">
                    @foreach ($actions as $action)
                        <x-site.button :href="$action['href']" :variant="$action['variant'] ?? 'primary'">{{ $action['label'] }}</x-site.button>
                    @endforeach
                </div>
            @endif
        </div>
        @if ($image && @file_exists(public_path($image)))
            <div class="v-pagehero--split__media v-reveal" data-delay="1">
                <img src="{{ asset($image) }}" alt="{{ $imageAlt }}" loading="eager" decoding="async">
                @if ($badge)
                    <p class="v-abouthero__badge" aria-hidden="true">{{ $badge }}</p>
                @endif
            </div>
        @endif
    </div>
</section>
