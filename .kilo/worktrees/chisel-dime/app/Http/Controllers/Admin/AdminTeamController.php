<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminTeamController extends Controller
{
    /**
     * Display a listing of teams.
     */
    public function index()
    {
        // Teams are managed through Programs in this system
        // This page serves as a simple team management interface
        $teams = \App\Models\Program::where('status', 'active')
            ->orderBy('title')
            ->get();

        return view('admin.teams.index', compact('teams'));
    }

    /**
     * Show the form for creating a new team.
     * Redirects to programs.create since teams are managed through programs
     */
    public function create()
    {
        return redirect()->route('admin.programs.create');
    }

    /**
     * Store a newly created team.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // Teams are stored as Programs in this system
        $program = \App\Models\Program::create($validated);

        return redirect()->route('admin.teams.index')
            ->with('success', 'Team created successfully.');
    }

    /**
     * Display the specified team.
     * Redirects to programs.show since teams are managed through programs
     */
    public function show($id)
    {
        return redirect()->route('admin.programs.show', $id);
    }

    /**
     * Show the form for editing the specified team.
     * Redirects to programs.edit since teams are managed through programs
     */
    public function edit($id)
    {
        return redirect()->route('admin.programs.edit', $id);
    }

    /**
     * Update the specified team.
     */
    public function update(Request $request, $id)
    {
        $team = \App\Models\Program::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $team->update($validated);

        return redirect()->route('admin.teams.index')
            ->with('success', 'Team updated successfully.');
    }

    /**
     * Remove the specified team.
     */
    public function destroy($id)
    {
        $team = \App\Models\Program::findOrFail($id);
        $team->delete();

        return redirect()->route('admin.teams.index')
            ->with('success', 'Team deleted successfully.');
    }
}
