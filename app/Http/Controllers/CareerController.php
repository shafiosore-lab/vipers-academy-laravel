<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CareerController extends Controller
{
    public function index(Request $request)
    {
        $query = Job::where('status', 'open');

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhere('location', 'LIKE', "%{$search}%")
                  ->orWhere('department', 'LIKE', "%{$search}%");
            });
        }

        // Filter by type
        if ($request->has('type') && !empty($request->type)) {
            $query->where('type', $request->type);
        }

        // Filter by location
        if ($request->has('location') && !empty($request->location)) {
            $query->where('location', 'LIKE', "%{$request->location}%");
        }

        // Filter by department
        if ($request->has('department') && !empty($request->department)) {
            $query->where('department', $request->department);
        }

        $jobs = $query->orderBy('created_at', 'desc')->get();

        // Get unique values for filters
        $types = Job::where('status', 'open')->distinct()->pluck('type')->sort();
        $locations = Job::where('status', 'open')->distinct()->pluck('location')->sort();
        $departments = Job::where('status', 'open')->whereNotNull('department')->distinct()->pluck('department')->sort();

        return view('website.careers', compact('jobs', 'types', 'locations', 'departments'));
    }

    public function show($id)
    {
        $job = Job::where('status', 'open')->findOrFail($id);
        return view('website.job_detail', compact('job'));
    }

    public function store(Request $request, $jobId)
    {
        $job = Job::where('status', 'open')->findOrFail($jobId);

        $request->validate([
            'applicant_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'resume' => 'required|file|mimes:pdf,doc,docx|max:5120', // 5MB max
            'cover_letter' => 'nullable|string|max:2000',
        ]);

        $resumePath = null;
        if ($request->hasFile('resume')) {
            $resumePath = $request->file('resume')->store('resumes', 'public');
        }

        JobApplication::create([
            'job_id' => $job->id,
            'applicant_name' => $request->applicant_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'resume_path' => $resumePath,
            'cover_letter' => $request->cover_letter,
        ]);

        return redirect()->route('careers.application.success');
    }

    public function applicationSuccess()
    {
        return view('website.application_success');
    }
}
