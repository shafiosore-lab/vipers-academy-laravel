<?php

namespace App\Http\Controllers\Website;

use Illuminate\Http\Request;
use App\Models\Gallery;

class GalleryController extends \App\Http\Controllers\Controller
{
    public function index()
    {
        $galleries = Gallery::all();
        return view('website.gallery.index', compact('galleries'));
    }
}
