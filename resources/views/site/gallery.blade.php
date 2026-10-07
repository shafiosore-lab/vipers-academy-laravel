@extends('layouts.site')

@section('title', 'Gallery — Mumias Vipers CBO')
@section('meta_description', 'Photos from Mumias Vipers CBO — football training, STEM and coding, health education, leadership and community events in Mumias, Kenya.')

@php
    $gallery = config('vipers.gallery', []);
    $categories = config('vipers.gallery_categories', []);
@endphp


@section('content')
<x-site.page-hero
    title="Gallery"
    lead="Football, STEM, health, leadership and community events — the work as it actually looks."
    eyebrow="Photos" />

<section class="v-section">
    <div class="v-container">
        <x-site.gallery-grid :items="$gallery" :categories="$categories" />

        <p class="v-text-muted" style="margin-top:1.5rem;font-size:var(--v-fs-xs)">
            Photography is added as authentic programme photography becomes available. Images are used
            with the consent of those shown.
        </p>
    </div>
</section>

<x-site.cta-section image="assets/img/gallery/team.jpeg" />

@endsection
