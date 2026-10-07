<?php

use App\Http\Controllers\Site\SiteController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| MUMIAS VIPERS CBO — PUBLIC WEBSITE
|--------------------------------------------------------------------------
|
| The organisation's public-facing site. Loaded as its own route file so the
| existing application (dashboards, tournaments, legacy website pages) stays
| untouched. Program detail routes are constrained to the configured slugs so
| the legacy numeric /programs/{id} route continues to resolve separately.
|
*/

Route::get('/', [SiteController::class, 'home'])->name('site.home');
Route::get('/about', [SiteController::class, 'about'])->name('site.about');

Route::get('/programs', [SiteController::class, 'programs'])->name('site.programs');
Route::get('/programs/{slug}', [SiteController::class, 'program'])
    ->whereIn('slug', array_column(config('vipers.programs', []), 'slug'))
    ->name('site.programs.show');

Route::get('/impact', [SiteController::class, 'impact'])->name('site.impact');

Route::get('/stories', [SiteController::class, 'stories'])->name('site.stories');
Route::get('/stories/{slug}', [SiteController::class, 'story'])->name('site.stories.show');

Route::get('/gallery', [SiteController::class, 'gallery'])->name('site.gallery');
Route::get('/get-involved', [SiteController::class, 'getInvolved'])->name('site.get-involved');
Route::get('/partnership', [SiteController::class, 'partnership'])->name('site.partnership');
Route::get('/support', [SiteController::class, 'support'])->name('site.support');

Route::get('/contact', [SiteController::class, 'contact'])->name('site.contact');
Route::post('/contact', [SiteController::class, 'submitContact'])->name('site.contact.submit');

/*
|--------------------------------------------------------------------------
| SITEMAP & ROBOTS
|--------------------------------------------------------------------------
*/

Route::get('/sitemap.xml', function () {
    $programs = array_map(
        fn ($p) => route('site.programs.show', ['slug' => $p['slug']]),
        config('vipers.programs', [])
    );

    $stories = array_map(
        fn ($s) => route('site.stories.show', ['slug' => $s['slug']]),
        config('vipers.stories', [])
    );

    $urls = array_merge([
        route('site.home'),
        route('site.about'),
        route('site.programs'),
        route('site.impact'),
        route('site.stories'),
        route('site.gallery'),
        route('site.get-involved'),
        route('site.partnership'),
        route('site.support'),
        route('site.contact'),
    ], $programs, $stories);

    return response()
        ->view('site.sitemap', ['urls' => array_values(array_unique($urls))])
        ->header('Content-Type', 'application/xml');
})->name('site.sitemap');

Route::get('/robots.txt', function () {
    $lines = [
        'User-agent: *',
        'Allow: /',
        'Disallow: /dashboard',
        'Disallow: /admin',
        'Disallow: /organization',
        'Disallow: /staff',
        'Disallow: /player',
        'Disallow: /api/',
        '',
        'Sitemap: '.route('site.sitemap'),
    ];

    return response(implode(PHP_EOL, $lines), 200)
        ->header('Content-Type', 'text/plain');
})->name('site.robots');
