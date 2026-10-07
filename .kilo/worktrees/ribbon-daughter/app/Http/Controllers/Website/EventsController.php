<?php

namespace App\Http\Controllers\Website;

use Illuminate\Http\Request;
use App\Models\PageContent;

class EventsController extends \App\Http\Controllers\Controller
{
    public function index()
    {
        // Fetch dynamic page content
        $pageContent = [];
        try {
            $sections = ['hero', 'events'];
            foreach ($sections as $section) {
                $pageContent[$section] = PageContent::getSection('events', $section);
            }
        } catch (\Exception $e) {
            $pageContent = [];
        }

        return view('website.events.index', compact('pageContent'));
    }
}
