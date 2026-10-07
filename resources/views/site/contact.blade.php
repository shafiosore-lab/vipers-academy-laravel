@extends('layouts.site')

@section('title', 'Contact Mumias Vipers CBO')
@section('meta_description', 'Contact Mumias Vipers CBO in Mumias, Kenya about programmes, partnerships, sponsorship, volunteering or media enquiries.')

@php
    $contact = config('vipers.contact', []);
    $subject = request('subject', $contact['form_subject'] ?? 'general');
    $subjects = [
        'general' => 'General enquiry',
        'programs' => 'About a program',
        'partnership' => 'Partnership',
        'support' => 'Supporting your work',
        'volunteer' => 'Volunteering',
        'media' => 'Media enquiry',
    ];
@endphp


@section('content')
<x-site.split-hero
    eyebrow="Get in touch"
    title="Contact us"
    script="We reply within two working days."
    lead="Ask a question, request our organisation profile, or start a conversation about working together."
    image="assets/img/gallery/sen.jpeg"
    imageAlt="Young people at a leadership session"
    badge="Karibu — welcome"
    :actions="[
        ['label' => 'See our programs', 'href' => route('site.programs'), 'variant' => 'ghost-light'],
    ]" />

<section class="v-section">
    <div class="v-container">
        <div class="v-split" style="align-items:start">
            {{-- Form --}}
            <div class="v-reveal">
                <h2 class="v-h3">Send us a message</h2>

                @if (session('success'))
                    <div class="v-alert v-alert--success" role="status">{{ session('success') }}</div>
                @endif
                @if ($errors->any())
                    <div class="v-alert v-alert--error" role="alert">
                        Please check the highlighted fields and try again.
                    </div>
                @endif

                <form method="POST" action="{{ route('site.contact.submit') }}" novalidate>
                    @csrf

                    <div class="v-field">
                        <label for="name">Your name <span aria-hidden="true">*</span></label>
                        <input type="text" id="name" name="name" required autocomplete="name"
                               value="{{ old('name') }}"
                               @error('name') aria-invalid="true" aria-describedby="name-error" @enderror>
                        @error('name')<p class="v-field__error" id="name-error">{{ $message }}</p>@enderror
                    </div>

                    <div class="v-field">
                        <label for="email">Email address <span aria-hidden="true">*</span></label>
                        <input type="email" id="email" name="email" required autocomplete="email"
                               value="{{ old('email') }}"
                               @error('email') aria-invalid="true" aria-describedby="email-error" @enderror>
                        @error('email')<p class="v-field__error" id="email-error">{{ $message }}</p>@enderror
                    </div>

                    <div class="v-field">
                        <label for="phone">Phone <span class="v-field__hint">(optional)</span></label>
                        <input type="tel" id="phone" name="phone" autocomplete="tel" value="{{ old('phone') }}">
                    </div>

                    <div class="v-field">
                        <label for="subject">What is this about? <span aria-hidden="true">*</span></label>
                        <select id="subject" name="subject" required>
                            @foreach ($subjects as $value => $label)
                                <option value="{{ $value }}" @selected(old('subject', $subject) === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('subject')<p class="v-field__error">{{ $message }}</p>@enderror
                    </div>

                    <div class="v-field">
                        <label for="message">Message <span aria-hidden="true">*</span></label>
                        <textarea id="message" name="message" required
                                  @error('message') aria-invalid="true" aria-describedby="message-error" @enderror>{{ old('message') }}</textarea>
                        @error('message')<p class="v-field__error" id="message-error">{{ $message }}</p>@enderror
                    </div>

                    <button type="submit" class="v-btn v-btn--primary v-btn--block">Send message</button>
                </form>
            </div>

            {{-- Contact details --}}
            <div class="v-reveal" data-delay="1">
                <h2 class="v-h3">Where to find us</h2>

                <div class="v-panel" style="margin-bottom:1rem">
                    <ul class="v-footer__contact" style="color:var(--v-ink-2)">
                        <li>
                            <x-site.icon name="pin" class="v-text-gold" />
                            <span>{{ $contact['location'] ?? '' }}</span>
                        </li>
                        @if (!empty($contact['address']))
                            <li><x-site.icon name="pin" class="v-text-gold" /><span>{{ $contact['address'] }}</span></li>
                        @endif
                        @if (!empty($contact['phone']))
                            <li>
                                <x-site.icon name="phone" class="v-text-gold" />
                                <a href="tel:{{ preg_replace('/[^0-9+]/', '', $contact['phone']) }}">{{ $contact['phone'] }}</a>
                            </li>
                        @endif
                        @if (!empty($contact['email']))
                            <li>
                                <x-site.icon name="mail" class="v-text-gold" />
                                <a href="mailto:{{ $contact['email'] }}">{{ $contact['email'] }}</a>
                            </li>
                        @endif
                        @if (!empty($contact['office_hours']))
                            <li><x-site.icon name="clock" class="v-text-gold" /><span>{{ $contact['office_hours'] }}</span></li>
                        @endif
                    </ul>
                </div>

                @if (empty($contact['phone']) || empty($contact['email']))
                    <x-site.placeholder-note
                        eyebrow="Contact details to be published"
                        note="Our official phone number, email address and office hours are not yet published. Add them in config/vipers.php under contact and they will appear here and in the footer." />
                @endif

                <div class="v-panel v-panel--navy" style="margin-top:1rem">
                    <h3 class="v-panel__title v-h4">For partners</h3>
                    <p class="v-panel__body">
                        If you are approaching us about funding, request our organisation profile,
                        registration documents and supporting materials through the form — we share
                        sensitive documents directly rather than publishing them.
                    </p>
                </div>

                @if (!empty($contact['map_url']))
                    <p style="margin-top:1rem">
                        <a class="v-link-arrow" href="{{ $contact['map_url'] }}" target="_blank" rel="noopener noreferrer">
                            View on map
                        </a>
                    </p>
                @endif
            </div>
        </div>
    </div>
</section>

@endsection
