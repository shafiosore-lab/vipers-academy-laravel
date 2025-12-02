<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\JobApplication;
use Illuminate\Http\Request;

class AdminJobController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jobs = Job::latest()->get();
        return view('admin.jobs', compact('jobs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.jobs_create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'required|string',
            'salary' => 'nullable|string|max:255',
            'location' => 'required|string|max:255',
            'type' => 'required|in:full-time,part-time,contract',
            'department' => 'nullable|string|max:255',
            'application_deadline' => 'nullable|date|after:today',
            'status' => 'required|in:open,closed,draft',
        ]);

        Job::create($request->all());

        return redirect()->route('admin.jobs.index')->with('success', 'Job created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $job = Job::with('applications')->findOrFail($id);
        return view('admin.jobs_show', compact('job'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $job = Job::findOrFail($id);
        return view('admin.jobs_edit', compact('job'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $job = Job::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'required|string',
            'salary' => 'nullable|string|max:255',
            'location' => 'required|string|max:255',
            'type' => 'required|in:full-time,part-time,contract',
            'department' => 'nullable|string|max:255',
            'application_deadline' => 'nullable|date',
            'status' => 'required|in:open,closed,draft',
        ]);

        $job->update($request->all());

        return redirect()->route('admin.jobs.index')->with('success', 'Job updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $job = Job::findOrFail($id);
        $job->delete();

        return redirect()->route('admin.jobs.index')->with('success', 'Job deleted successfully.');
    }
}
