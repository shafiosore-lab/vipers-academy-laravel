<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tournament;
use App\Models\TournamentVenue;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TournamentVenueController extends Controller
{
    /**
     * Display a listing of venues for a specific tournament.
     */
    public function index(Tournament $tournament)
    {
        $venues = $tournament->venues()->orderBy('name')->get();

        return view('admin.tournaments.venues.index', compact('tournament', 'venues'));
    }

    /**
     * Show the form for creating a new venue.
     */
    public function create(Tournament $tournament)
    {
        return view('admin.tournaments.venues.create', compact('tournament'));
    }

    /**
     * Store a newly created venue in storage.
     */
    public function store(Request $request, Tournament $tournament)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:500'],
            'city' => ['nullable', 'string', 'max:100'],
            'capacity' => ['nullable', 'integer', 'min:0'],
            'surface_type' => ['nullable', 'string', 'max:50'],
            'is_active' => ['boolean'],
        ]);

        $tournament->venues()->create($validated);

        return redirect()->route('tournaments.venues.index', $tournament)
            ->with('success', 'Venue created successfully.');
    }

    /**
     * Show the form for editing the specified venue.
     */
    public function edit(Tournament $tournament, TournamentVenue $venue)
    {
        // Ensure venue belongs to this tournament
        if ($venue->tournament_id !== $tournament->id) {
            abort(404);
        }

        return view('admin.tournaments.venues.edit', compact('tournament', 'venue'));
    }

    /**
     * Update the specified venue in storage.
     */
    public function update(Request $request, Tournament $tournament, TournamentVenue $venue)
    {
        // Ensure venue belongs to this tournament
        if ($venue->tournament_id !== $tournament->id) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:500'],
            'city' => ['nullable', 'string', 'max:100'],
            'capacity' => ['nullable', 'integer', 'min:0'],
            'surface_type' => ['nullable', 'string', 'max:50'],
            'is_active' => ['boolean'],
        ]);

        $venue->update($validated);

        return redirect()->route('tournaments.venues.index', $tournament)
            ->with('success', 'Venue updated successfully.');
    }

    /**
     * Remove the specified venue from storage.
     */
    public function destroy(Tournament $tournament, TournamentVenue $venue)
    {
        // Ensure venue belongs to this tournament
        if ($venue->tournament_id !== $tournament->id) {
            abort(404);
        }

        // Check if venue has matches
        if ($venue->matches()->count() > 0) {
            return redirect()->route('tournaments.venues.index', $tournament)
                ->with('error', 'Cannot delete venue with associated matches.');
        }

        $venue->delete();

        return redirect()->route('tournaments.venues.index', $tournament)
            ->with('success', 'Venue deleted successfully.');
    }

    /**
     * Get venues for AJAX selection
     */
    public function getVenues(Tournament $tournament)
    {
        $venues = $tournament->venues()
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'city', 'capacity']);

        return response()->json($venues);
    }
}
