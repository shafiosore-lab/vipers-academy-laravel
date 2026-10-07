<?php

namespace App\Http\Controllers\Website;

use Illuminate\Http\Request;
use App\Models\PageContent;

class AboutController extends \App\Http\Controllers\Controller
{
    public function index()
    {
        // Fetch dynamic page content
        $pageContent = [];
        try {
            $sections = ['mission', 'vision', 'values', 'journey', 'team'];
            foreach ($sections as $section) {
                $pageContent[$section] = PageContent::getSection('about', $section);
            }
        } catch (\Exception $e) {
            $pageContent = [];
        }

        return view('website.about.index', compact('pageContent'));
    }
}
