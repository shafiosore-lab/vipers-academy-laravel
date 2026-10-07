@props(['eyebrow' => null, 'title' => null, 'lead' => null, 'cards' => []])

{{-- Action-cards hero: headline on top, clickable pathway cards below. --}}
<section class="v-pagehero v-pagehero--cards v-on-dark" aria-label="{{ strip_tags($eyebrow ?? $title ?? 'Page hero') }}">
    <div class="v-container">
        <div class="v-pagehero--cards__head v-reveal">
            @if ($eyebrow)
                <p class="v-eyebrow">{!! $eyebrow !!}</p>
            @endif
            <h1 class="v-h1 v-pagehero__title">{{ $title }}</h1>
            @if ($lead)
                <p class="v-lead v-pagehero__text">{{ $lead }}</p>
            @endif
        </div>
        @if (count($cards))
            <ul class="v-pagehero--cards__grid">
                @foreach ($cards as $i => $card)
                    <li class="v-pagehero--cards__card v-reveal" data-delay="{{ $i % 3 }}">
                        <span class="v-icon-chip" aria-hidden="true"><x-site.icon :name="$card['icon'] ?? 'arrow'" /></span>
                        <strong>{{ $card['title'] ?? '' }}</strong>
                        @if (!empty($card['body']))
                            <span>{{ $card['body'] }}</span>
                        @endif
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</section>
