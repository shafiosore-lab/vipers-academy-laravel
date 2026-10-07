<?php

/*
|--------------------------------------------------------------------------
| Public CBO site
|--------------------------------------------------------------------------
|
| These tests cover the public Mumias Vipers website only: that every
| registered page renders, that exactly one <h1> is present, and that the
| contact form validates and logs enquiries instead of inventing figures.
|
*/

/*
 * Indexed pairs, not an associative map: Pest passes an associative dataset
 * as a single argument, which would not match the two-parameter closure.
 */
$publicPages = [
    ['home', '/'],
    ['about', '/about'],
    ['programs', '/programs'],
    ['impact', '/impact'],
    ['stories', '/stories'],
    ['gallery', '/gallery'],
    ['get involved', '/get-involved'],
    ['partnership', '/partnership'],
    ['support', '/support'],
    ['contact', '/contact'],
];

it('renders every public page', function (string $name, string $uri) {
    $this->get($uri)->assertOk();
})->with($publicPages);

it('renders a programme detail page for every configured slug', function () {
    foreach (config('vipers.programs', []) as $program) {
        $this->get(route('site.programs.show', $program['slug']))->assertOk();
    }
});

it('returns 404 for an unknown programme slug', function () {
    $this->get('/programs/not-a-real-programme')->assertNotFound();
});

it('shows exactly one h1 on each public page', function (string $name, string $uri) {
    $html = $this->get($uri)->assertOk()->getContent();

    expect(substr_count($html, '<h1'))->toBe(1);
})->with($publicPages);

it('renders the shared layout chrome', function () {
    $html = $this->get('/')->assertOk()->getContent();

    // Skip link, white nav, mobile drawer, footer and the gallery lightbox.
    expect($html)->toContain('v-skip-link')
        ->toContain('v-nav--light')
        ->toContain('v-mobile')
        ->toContain('v-footer')
        ->toContain('v-lightbox');
});

it('keeps the main content inside the html document', function () {
    $html = $this->get('/')->assertOk()->getContent();

    // Guards against the old structural bug where <main> was rendered
    // after </html>, outside the document.
    $mainPos = strpos($html, '<main');
    $closePos = strpos($html, '</html>');

    expect($mainPos)->not->toBeFalse()
        ->and($closePos)->not->toBeFalse()
        ->and($mainPos)->toBeLessThan($closePos);
});

it('does not render the funding band on the homepage', function () {
    $html = $this->get('/')->assertOk()->getContent();

    // The "What funding enables" band was removed from the homepage; the
    // same content still lives on the support page.
    expect($html)->not->toContain('home-funding-heading');

    $support = $this->get('/support')->assertOk()->getContent();

    expect($support)->toContain('What funding enables');
});

it('serves a sitemap and robots file', function () {
    $this->get('/sitemap.xml')->assertOk();
    $this->get('/robots.txt')->assertOk();
});

it('publishes only verified evidence and never fabricates a figure', function () {
    $html = $this->get('/')->assertOk()->getContent();

    // Every fact in the evidence array must be explicitly marked verified.
    foreach (config('vipers.evidence', []) as $fact) {
        expect($fact['verified'] ?? false)->toBeTrue();
    }

    // With no unverified figures to show, the "awaiting verified data"
    // placeholder must not appear on the homepage.
    expect($html)->not->toContain('Awaiting verified data');
    expect($html)->not->toContain('v-impactbar__num--pending');

    // The documented facts must be visible.
    expect($html)->toContain('300+');
});

it('frames the scholarship work as connections, not awards', function () {
    $html = $this->get('/')->assertOk()->getContent();

    expect($html)->toContain('connected to sports scholarship opportunities');

    // Claims we must never publish.
    expect($html)->not->toContain('received scholarships');
    expect($html)->not->toContain('students received');
});

it('does not claim Peace Club of Kenya membership', function () {
    $html = $this->get('/programs/peace-justice')->assertOk()->getContent();

    expect($html)->toContain('pursuing engagement with Peace Club of Kenya');
    expect($html)->not->toContain('member of Peace Club of Kenya');
});

it('does not present health work as a medical service', function () {
    $html = $this->get('/programs/health-education')->assertOk()->getContent();

    expect($html)->toContain('not a medical service provider');
    expect($html)->toContain('Medsply');
});
describe('contact form', function () {
    it('rejects an invalid submission with field errors', function () {
        $this->from('/contact')
            ->post('/contact', ['name' => '', 'email' => 'not-an-email', 'message' => ''])
            ->assertSessionHasErrors(['name', 'email', 'subject', 'message']);
    });

    it('rejects a subject outside the allowed list', function () {
        $this->from('/contact')
            ->post('/contact', [
                'name' => 'Test Person',
                'email' => 'test@example.com',
                'subject' => 'not-a-real-subject',
                'message' => 'Hello there.',
            ])
            ->assertSessionHasErrors('subject');
    });

    it('accepts and logs a valid enquiry', function () {
        \Illuminate\Support\Facades\Log::shouldReceive('info')
            ->once()
            ->withArgs(fn ($message, $context) => $message === 'Public website enquiry');

        $this->from('/contact')
            ->post('/contact', [
                'name' => 'Test Person',
                'email' => 'test@example.com',
                'phone' => '+254700000000',
                'subject' => 'general',
                'message' => 'I would like to know more about your programmes.',
            ])
            ->assertRedirect('/contact')
            ->assertSessionHas('success');
    });
});
