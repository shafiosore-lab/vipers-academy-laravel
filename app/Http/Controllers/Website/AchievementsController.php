<?php

namespace App\Http\Controllers\Website;

use Illuminate\Http\Request;
use App\Models\PageContent;

class AchievementsController extends \App\Http\Controllers\Controller
{
    public function index()
    {
        // Fetch dynamic page content
        $pageContent = [];
        try {
            $sections = ['hero', 'achievements', 'stats'];
            foreach ($sections as $section) {
                $pageContent[$section] = PageContent::getSection('achievements', $section);
            }
        } catch (\Exception $e) {
            $pageContent = [];
        }

        return view('website.achievements.index', compact('pageContent'));
    }
}
