<?php

namespace App\Http\Controllers\Website;

use Illuminate\Http\Request;

class EventsController extends \App\Http\Controllers\Controller
{
    public function index()
    {
        return view('website.events.index');
    }
}
