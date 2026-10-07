<?php

namespace App\Http\Controllers\Website;

use Illuminate\Http\Request;

class AboutController extends \App\Http\Controllers\Controller
{
    public function index()
    {
        return view('website.about.index');
    }
}
