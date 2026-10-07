@props(['steps' => []])

{{-- PLAY → LEARN → LEAD → CHANGE. Intended for dark sections (journey body
     copy is set in a light-on-dark colour), so callers should add v-on-dark. --}}

@if (!empty($steps))
    <ol class="v-journey">
        @foreach ($steps as $i => $step)
            <li class="v-journey__item v-reveal" data-delay="{{ $i % 4 }}">
                <span class="v-journey__num" aria-hidden="true">{{ $i + 1 }}</span>
                <p class="v-journey__step">{{ $step['step'] ?? '' }}</p>
                <h3 class="v-journey__title">{{ $step['title'] ?? '' }}</h3>
                <p class="v-journey__body">{{ $step['body'] ?? '' }}</p>
            </li>
        @endforeach
    </ol>
@endif
