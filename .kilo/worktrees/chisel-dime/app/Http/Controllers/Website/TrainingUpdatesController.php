<?php

namespace App\Http\Controllers\Website;

use Illuminate\Http\Request;

class TrainingUpdatesController extends \App\Http\Controllers\Controller
{
    public function index()
    {
        return view('website.training.index');
    }
}
