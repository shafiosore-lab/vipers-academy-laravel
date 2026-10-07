@props(['eyebrow' => null, 'title' => null, 'lead' => null, 'images' => [], 'actions' => []])

{{-- Editorial collage hero: copy left, 3-tile asymmetric photo collage right. --}}
<section class="v-pagehero v-pagehero--art v-on-dark" aria-label="{{ strip_tags($eyebrow ?? $title ?? 'Page hero') }}">
    <span class="v-pagehero--art__glow" aria-hidden="true"></span>
    <div class="v-container v-pagehero--art__grid">
        <div class="v-reveal">
            @if ($eyebrow)
                <p class="v-eyebrow">{!! $eyebrow !!}</p>
            @endif
            <h1 class="v-h1 v-pagehero__title">{{ $title }}</h1>
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
        @if (count($images))
            <div class="v-pagehero--art__collage v-reveal" data-delay="1">
                @foreach (array_slice($images, 0, 3) as $i => $img)
                    @if (!empty($img['src']) && @file_exists(public_path($img['src'])))
                        <figure class="v-pagehero--art__tile v-pagehero--art__tile--{{ $i + 1 }}">
                            <img src="{{ asset($img['src']) }}" alt="{{ $img['alt'] ?? '' }}" loading="eager" decoding="async">
                        </figure>
                    @endif
                @endforeach
            </div>
        @endif
    </div>
</section>
