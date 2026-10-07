<?php

namespace App\Http\Controllers\Website;

use Illuminate\Http\Request;

class AchievementsController extends \App\Http\Controllers\Controller
{
    public function index()
    {
        return view('website.achievements.index');
    }
}
