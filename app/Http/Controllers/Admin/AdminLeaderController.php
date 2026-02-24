<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Leader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminLeaderController extends Controller
{
    public function index()
    {
        $leaders = Leader::orderBy('display_order')->paginate(15);

        return view('admin.leaders.index', compact('leaders'));
    }

    public function show(Leader $leader)
    {
        return view('admin.leaders.show', compact('leader'));
    }

    public function create()
    {
        return view('admin.leaders.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'credentials' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'linkedin_url' => 'nullable|url',
            'display_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $data = $request->except('photo');

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('leaders', 'public');
            $data['photo_path'] = $path;
        }

        // Set default display order if not provided
        if (!isset($data['display_order'])) {
            $data['display_order'] = Leader::max('display_order') + 1;
        }

        $data['is_active'] = $request->has('is_active');

        Leader::create($data);

        return redirect()->route('admin.leaders.index')->with('success', 'Leader created successfully.');
    }

    public function edit(Leader $leader)
    {
        return view('admin.leaders.edit', compact('leader'));
    }

    public function update(Request $request, Leader $leader)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'credentials' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'linkedin_url' => 'nullable|url',
            'display_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $data = $request->except('photo');

        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($leader->photo_path) {
                Storage::disk('public')->delete($leader->photo_path);
            }
            $path = $request->file('photo')->store('leaders', 'public');
            $data['photo_path'] = $path;
        }

        $data['is_active'] = $request->has('is_active');

        $leader->update($data);

        return redirect()->route('admin.leaders.index')->with('success', 'Leader updated successfully.');
    }

    public function destroy(Leader $leader)
    {
        // Delete photo if exists
        if ($leader->photo_path) {
            Storage::disk('public')->delete($leader->photo_path);
        }

        $leader->delete();

        return redirect()->route('admin.leaders.index')->with('success', 'Leader deleted successfully.');
    }

    public function toggleStatus(Leader $leader)
    {
        $leader->update(['is_active' => !$leader->is_active]);

        $status = $leader->is_active ? 'activated' : 'deactivated';
        return redirect()->back()->with('success', "Leader {$status} successfully.");
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'leaders' => 'required|array',
            'leaders.*.id' => 'required|exists:leaders,id',
            'leaders.*.display_order' => 'required|integer|min:0',
        ]);

        foreach ($request->leaders as $leaderData) {
            Leader::where('id', $leaderData['id'])->update(['display_order' => $leaderData['display_order']]);
        }

        return response()->json(['success' => true, 'message' => 'Leaders reordered successfully.']);
    }
}
