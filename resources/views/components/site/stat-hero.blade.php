@props([
    'eyebrow' => null, 'title' => null, 'accent' => null, 'script' => null,
    'lead' => null, 'image' => null, 'imageAlt' => '',
    'statValue' => null, 'statLabel' => null, 'actions' => [],
])

{{-- Full-bleed stat hero: photo backdrop + scrim, copy left, glass proof-card right. --}}
<section class="v-pagehero v-pagehero--stat v-on-dark" aria-label="{{ strip_tags($eyebrow ?? $title ?? 'Page hero') }}">
    @if ($image && @file_exists(public_path($image)))
        <span class="v-pagehero--stat__bg" style="background-image: url('{{ asset($image) }}')" aria-hidden="true"></span>
    @endif
    <span class="v-pagehero--stat__scrim" aria-hidden="true"></span>
    <div class="v-container v-pagehero--stat__grid">
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
        @if ($statValue)
            <div class="v-pagehero--stat__glass v-reveal" data-delay="1">
                <p class="v-pagehero--stat__value">{{ $statValue }}</p>
                @if ($statLabel)
                    <p class="v-pagehero--stat__label">{{ $statLabel }}</p>
                @endif
            </div>
        @endif
    </div>
</section>
