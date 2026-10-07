@props(['items' => [], 'light' => false])

{{--
    Impact evidence.

    Three kinds of entry are handled:
      1. A verified number  -> rendered with a count-up.
      2. A verified text fact (e.g. a partnership being "in place") -> rendered
         as words, because there is no figure to count.
      3. Anything unverified -> an em dash in a muted tone plus a visible
         "awaiting verified data" marker. It is never invented.

    Publish a figure only when it can be evidenced. Precision is what makes
    the other numbers believable.
--}}

@if (!empty($items))
    <div class="v-stats {{ $light ? 'v-stats--light' : '' }}">
        @foreach ($items as $stat)
            @php
                $raw = $stat['value'] ?? null;
                $verified = $stat['verified'] ?? false;
                $isText = ($stat['type'] ?? '') === 'text';

                if ($isText) {
                    $shown = $stat['display'] ?? '';
                } elseif ($verified && $raw !== null) {
                    $shown = $raw . ($stat['suffix'] ?? '');
                } else {
                    $shown = '—';
                }
            @endphp

            <div class="v-stat">
                @if ($verified && ! $isText && $raw !== null)
                    <span class="v-stat__num" data-count="{{ (int) $raw }}">{{ $shown }}</span>
                @elseif ($isText)
                    <span class="v-stat__num v-stat__num--text">{{ $shown }}</span>
                @else
                    <span class="v-stat__num v-stat__num--pending" aria-hidden="true">{{ $shown }}</span>
                @endif

                <span class="v-stat__label">{{ $stat['label'] ?? '' }}</span>

                @if (!empty($stat['note']))
                    <span class="v-stat__note">{{ $stat['note'] }}</span>
                @endif

                @unless ($verified)
                    <span class="v-stat__pending">Awaiting verified data</span>
                    <span class="v-visually-hidden">This figure has not been verified yet.</span>
                @endunless
            </div>
        @endforeach
    </div>
@endif