@extends('layouts.site')

@section('title', 'Our Programs — STEM, Health, Peace & Justice, Football | Mumias Vipers CBO')
@section('meta_description', 'Four programmes from Mumias Vipers CBO: STEM education, health education, peace and justice, and football development — building skills and creating opportunities for young people.')

@php
    $programs = config('vipers.programs', []);
@endphp


@section('content')
<x-site.page-hero
    title="Building skills. Creating opportunities. Changing lives."
    lead="Four programmes, one organisation. Football is how we reach young people; these are the doors we take them through."
    eyebrow="Our programs" />

<section class="v-section">
    <div class="v-container">
        <div class="v-grid v-grid--2">
            @foreach ($programs as $i => $program)
                <x-site.program-card :program="$program" :delay="$i % 2" />
            @endforeach
        </div>
    </div>
</section>

{{-- How the programmes connect --}}
<section class="v-section v-section--navy v-on-dark" aria-labelledby="connect-heading">
    <div class="v-container">
        <x-site.section-heading
            id="connect-heading"
            eyebrow="How it fits together"
            title="These are not four separate organisations"
            lead="A young person can move between all of them — football in the week, STEM and health education alongside it, leadership when they are ready." />

        <x-site.journey-steps :steps="config('vipers.journey', [])" />
    </div>
</section>

<x-site.cta-section image="assets/img/gallery/kids.jpeg" />

@endsection
