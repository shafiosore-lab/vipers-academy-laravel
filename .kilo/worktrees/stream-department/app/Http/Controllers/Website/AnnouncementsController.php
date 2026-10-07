<?php

namespace App\Http\Controllers\Website;

use Illuminate\Http\Request;

class AnnouncementsController extends \App\Http\Controllers\Controller
{
    public function index()
    {
        return view('website.news.index');
    }
}
