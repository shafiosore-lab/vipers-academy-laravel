<?php

namespace App\Http\Controllers\Website;

use Illuminate\Http\Request;
use App\Models\Leader;
use App\Models\PageContent;

class StaffController extends \App\Http\Controllers\Controller
{
    public function index()
    {
        $leaders = Leader::active()->get();

        // Fetch dynamic page content
        $pageContent = [];
        try {
            $sections = ['leadership', 'team'];
            foreach ($sections as $section) {
                $pageContent[$section] = PageContent::getSection('staff', $section);
            }
        } catch (\Exception $e) {
            $pageContent = [];
        }

        return view('website.staff.index', compact('leaders', 'pageContent'));
    }
}
