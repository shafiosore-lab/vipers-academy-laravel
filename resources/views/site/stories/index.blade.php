@extends('layouts.site')

@section('title', 'Stories From The Community — Mumias Vipers CBO')
@section('meta_description', 'News, updates and impact stories from Mumias Vipers CBO across football, STEM, health, peace and justice, education and community work.')

@php
    $stories = config('vipers.stories', []);
    $categories = config('vipers.story_categories', []);
@endphp


@section('content')
<x-site.art-hero
    eyebrow="Stories &amp; news"
    title="Stories from the community"
    lead="Updates, programme news and impact stories from Mumias — published when they are real and verified."
    :images="[
        ['src' => 'assets/img/gallery/kids.jpeg', 'alt' => 'Young players at a community football session'],
        ['src' => 'assets/img/gallery/coding.jpg', 'alt' => 'Young people learning coding'],
        ['src' => 'assets/img/gallery/team.jpeg', 'alt' => 'Vipers team group'],
    ]"
    :actions="[
        ['label' => 'See our impact', 'href' => route('site.impact'), 'variant' => 'primary'],
        ['label' => 'Get involved', 'href' => route('site.get-involved'), 'variant' => 'ghost-light'],
    ]" />

<section class="v-section">
    <div class="v-container">
        @if (count($stories))
            <div class="v-grid v-grid--3">
                @foreach ($stories as $i => $story)
                    <x-site.story-card :story="$story" :delay="$i % 3" />
                @endforeach
            </div>
        @else
            {{-- No sample or invented articles are published --}}
            <x-site.section-heading
                eyebrow="Nothing published yet"
                title="Real stories, as they happen"
                lead="We are not filling this page with sample articles. Stories appear here once they are written, checked and consented to by the young people and families they are about." />

            <div class="v-grid v-grid--3 v-grid--cozy">
                @foreach ($categories as $category)
                    <div class="v-card v-card--gold-top v-reveal">
                        <h3 class="v-card__title v-h4">{{ $category }}</h3>
                        <p class="v-card__text v-card__text--xs v-text-muted">
                            Stories in this category are to be published.
                        </p>
                    </div>
                @endforeach
            </div>

            <div style="margin-top:2rem">
                <x-site.placeholder-note
                    eyebrow="For the site administrator"
                    note="Add stories as arrays in config/vipers.php under stories, following the documented shape (slug, title, category, date, excerpt, image, body). They appear here and in the sitemap automatically." />
            </div>
        @endif
    </div>
</section>

<x-site.cta-section
    eyebrow="Stay connected"
    image="assets/img/gallery/kids.jpeg" />

@endsection
