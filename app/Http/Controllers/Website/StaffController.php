<?php

namespace App\Http\Controllers\Website;

use Illuminate\Http\Request;
use App\Models\Leader;

class StaffController extends \App\Http\Controllers\Controller
{
    public function index()
    {
        $leaders = Leader::active()->get();

        return view('website.staff.index', compact('leaders'));
    }
}
