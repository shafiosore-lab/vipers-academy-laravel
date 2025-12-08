<?php

namespace App\Http\Controllers\Website;

use Illuminate\Http\Request;

class StaffController extends \App\Http\Controllers\Controller
{
    public function index()
    {
        return view('website.staff.index');
    }
}
