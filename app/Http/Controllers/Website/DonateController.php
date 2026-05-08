<?php

namespace App\Http\Controllers\Website;

use Illuminate\Http\Request;
use App\Models\PageContent;

class DonateController extends \App\Http\Controllers\Controller
{
    public function index()
    {
        // Fetch dynamic page content
        $pageContent = [];
        try {
            $sections = ['hero', 'impact', 'payment', 'faq'];
            foreach ($sections as $section) {
                $pageContent[$section] = PageContent::getSection('donate', $section);
            }
        } catch (\Exception $e) {
            $pageContent = [];
        }

        return view('website.donate.index', compact('pageContent'));
    }
}