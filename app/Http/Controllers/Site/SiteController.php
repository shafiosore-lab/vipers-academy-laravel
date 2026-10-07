<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * Public CBO website.
 *
 * All content is served from config/vipers.php so it can later be replaced
 * by a database / admin panel without changing these templates.
 */
class SiteController extends Controller
{
    /** Homepage. */
    public function home()
    {
        return view('site.home');
    }

    public function about()
    {
        return view('site.about');
    }

    public function programs()
    {
        return view('site.programs.index');
    }

    public function program(string $slug)
    {
        $program = collect(config('vipers.programs', []))
            ->firstWhere('slug', $slug);

        abort_if($program === null, 404);

        return view('site.programs.show', compact('program'));
    }

    public function impact()
    {
        return view('site.impact');
    }

    public function stories()
    {
        return view('site.stories.index');
    }

    public function story(string $slug)
    {
        $story = collect(config('vipers.stories', []))
            ->firstWhere('slug', $slug);

        abort_if($story === null, 404);

        return view('site.stories.show', compact('story'));
    }

    public function gallery()
    {
        return view('site.gallery');
    }

    public function getInvolved()
    {
        return view('site.get-involved');
    }

    public function partnership()
    {
        return view('site.partnership');
    }

    public function support()
    {
        return view('site.support');
    }

    public function contact()
    {
        return view('site.contact');
    }

    public function submitContact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:40',
            'subject' => 'required|string|in:general,programs,partnership,support,volunteer,media',
            'message' => 'required|string|max:2000',
        ]);

        // No delivery destination is configured yet, so we only record the
        // enquiry in the log rather than silently discarding it.
        \Illuminate\Support\Facades\Log::info('Public website enquiry', $validated + [
            'ip' => $request->ip(),
        ]);

        return redirect()
            ->route('site.contact')
            ->with('success', 'Thank you. Your message has been received and we will respond as soon as possible.');
    }
}
