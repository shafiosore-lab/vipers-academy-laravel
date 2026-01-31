<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Program;
use App\Models\User;

class ProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $category = $request->get('category', 'all');

        $programs = Program::when($category !== 'all', function ($query) use ($category) {
            return $query->where('category', $category);
        })->get();

        return view('website.programs.index', compact('programs', 'category'));
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $program = Program::findOrFail($id);
        return view('website.programs.show', compact('program'));
    }

    // Enrollment methods removed - replaced with new EnrollmentController
}
