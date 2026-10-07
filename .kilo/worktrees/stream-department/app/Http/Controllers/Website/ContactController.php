<?php

namespace App\Http\Controllers\Website;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ContactController extends \App\Http\Controllers\Controller
{
    public function index()
    {
        return view('website.contact.index');
    }

    public function store(Request $request)
    {
        // Validate the form data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|in:admissions,programs,partnership,careers,general,support',
            'message' => 'required|string|max:2000',
            'newsletter' => 'nullable|boolean',
        ]);

        try {
            // Here you would typically send an email or save to database
            // For now, we'll just log the contact and send a success response

            // Log the contact submission
            Log::info('Contact form submission', [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'subject' => $validated['subject'],
                'message' => $validated['message'],
                'newsletter' => $validated['newsletter'] ?? false,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            // TODO: Send email notification to admin
            // You can implement email sending here using Laravel Mail

            // If newsletter checkbox was checked, you could add to mailing list
            if (isset($validated['newsletter']) && $validated['newsletter']) {
                // TODO: Add to newsletter subscription
                Log::info('Newsletter subscription requested', [
                    'email' => $validated['email'],
                    'name' => $validated['name'],
                ]);
            }

            return redirect()->back()->with('success', 'Thank you for your message! We\'ll get back to you within 24 hours.');

        } catch (\Exception $e) {
            Log::error('Contact form submission failed', [
                'error' => $e->getMessage(),
                'data' => $validated,
            ]);

            return redirect()->back()->with('error', 'Sorry, there was an error sending your message. Please try again or contact us directly.')->withInput();
        }
    }
}
