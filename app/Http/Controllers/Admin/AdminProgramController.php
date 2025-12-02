<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Program;
use Illuminate\Http\Request;

class AdminProgramController extends Controller
{
    public function index()
    {
        $programs = Program::all();
        return view('admin.programs', compact('programs'));
    }

    public function show($id)
    {
        $program = Program::findOrFail($id);
        return view('admin.program_show', compact('program'));
    }

    public function create()
    {
        return view('admin.program_create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'age_group' => 'required|string|max:255',
            'description' => 'required|string',
            'schedule' => 'required|string',
            'duration' => 'nullable|string|max:255',
            'regular_fee' => 'nullable|numeric|min:0',
            'mumias_fee' => 'nullable|numeric|min:0',
            'mumias_discount_percentage' => 'nullable|integer|min:0|max:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only(['title', 'age_group', 'description', 'schedule', 'duration', 'regular_fee', 'mumias_fee', 'mumias_discount_percentage']);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('uploads/programs', 'public');
        }

        Program::create($data);

        return redirect()->route('admin.programs.index')->with('success', 'Program created successfully.');
    }

    public function edit($id)
    {
        $program = Program::findOrFail($id);
        return view('admin.program_edit', compact('program'));
    }

    public function update(Request $request, $id)
    {
        $program = Program::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'age_group' => 'required|string|max:255',
            'description' => 'required|string',
            'schedule' => 'required|string',
            'duration' => 'nullable|string|max:255',
            'regular_fee' => 'nullable|numeric|min:0',
            'mumias_fee' => 'nullable|numeric|min:0',
            'mumias_discount_percentage' => 'nullable|integer|min:0|max:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only(['title', 'age_group', 'description', 'schedule', 'duration', 'regular_fee', 'mumias_fee', 'mumias_discount_percentage']);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('uploads/programs', 'public');
        }

        $program->update($data);

        return redirect()->route('admin.programs.index')->with('success', 'Program updated successfully.');
    }

    public function destroy($id)
    {
        $program = Program::findOrFail($id);
        $program->delete();

        return redirect()->route('admin.programs.index')->with('success', 'Program deleted successfully.');
    }
}
