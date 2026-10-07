@extends('layouts.site')

@section('title', $story['title'] . ' — Mumias Vipers CBO')
@section('meta_description', $story['excerpt'] ?? '')
@section('og_image', $story['image'] ?? null)
@section('og_type', 'article')

@php
    $date = !empty($story['date']) ? \Illuminate\Support\Carbon::parse($story['date']) : null;

    // Article structured data — only for genuinely published articles.
    $articleSchema = $date ? array_filter([
        '@context' => 'https://schema.org',
        '@type' => 'Article',
        'headline' => $story['title'],
        'description' => $story['excerpt'] ?? null,
        'datePublished' => $date->toDateString(),
        'image' => !empty($story['image']) ? asset($story['image']) : null,
        'author' => !empty($story['author'])
            ? ['@type' => 'Organization', 'name' => $story['author']]
            : ['@type' => 'Organization', 'name' => config('vipers.org.name')],
        'publisher' => [
            '@type' => 'Organization',
            'name' => config('vipers.org.name'),
            'logo' => ['@type' => 'ImageObject', 'url' => asset('assets/img/logo/vps.jpeg')],
        ],
        'mainEntityOfPage' => url()->current(),
    ]) : null;
@endphp

@push('structured-data')

@section('content')
    @if ($articleSchema)
        <script type="application/ld+json">{!! json_encode($articleSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    @endif
@endpush

<article>
    <x-site.split-hero
        eyebrow="Stories &amp; news{{ !empty($story['category']) ? ' — ' . $story['category'] : '' }}"
        :title="$story['title']"
        :lead="$story['excerpt'] ?? ''"
        :image="$story['image'] ?? null"
        :imageAlt="$story['title'] ?? ''"
        badge="Verified story"
        :actions="[
            ['label' => 'All stories', 'href' => route('site.stories'), 'variant' => 'ghost-light'],
        ]" />

    <section class="v-section">
        <div class="v-container v-container--narrow">
            <p class="v-story__meta">
                @if (!empty($story['category']))
                    <span class="v-badge v-badge--gold">{{ $story['category'] }}</span>
                @endif
                @if ($date)
                    <time datetime="{{ $date->toDateString() }}">{{ $date->format('j F Y') }}</time>
                @endif
                @if (!empty($story['author']))
                    <span class="v-story__dot" aria-hidden="true"></span>
                    <span>{{ $story['author'] }}</span>
                @endif
            </p>

            <div class="v-prose">
                @foreach ($story['body'] ?? [] as $block)
                    @if (!empty($block['heading']))
                        <h2>{{ $block['heading'] }}</h2>
                    @endif
                    <p>{{ $block['text'] }}</p>
                @endforeach
            </div>

            <div class="v-btn-row v-btn-row--tight" style="margin-top:2.5rem">
                <x-site.button :href="route('site.stories')" variant="ghost">All stories</x-site.button>
                <x-site.button :href="route('site.support')" variant="primary">Support our mission</x-site.button>
            </div>
        </div>
    </section>
</article>

@endsection
