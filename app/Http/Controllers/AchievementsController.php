<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AchievementsController extends Controller
{
    public function index()
    {
        return view('website.achievements.index');
    }
}
