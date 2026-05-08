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
        try {
            // Check if the table exists before querying
            if (!\Schema::hasTable('programs')) {
                \Log::warning('ProgramController@index: programs table does not exist');
                $programs = collect();
                return view('website.programs.index', compact('programs', 'category'));
            }

            $category = $request->get('category', 'all');

            // Define the desired program order
            $programOrder = [
                'Computer & Coding Classes',
                'Arduino Robotics & Electronics Program',
                'Weekend Football & Life-Skills Program',
                'Academic & Exposure Program',
                'Youth Development & Mentorship Program'
            ];

            $programs = Program::when($category !== 'all', function ($query) use ($category) {
                return $query->where('category', $category);
            })
            ->orderByRaw("FIELD(title, '" . implode("','", $programOrder) . "')")
            ->get();

            return view('website.programs.index', compact('programs', 'category'));
        } catch (\Exception $e) {
            // If table doesn't exist or other error, return empty collection
            $category = $request->get('category', 'all');
            $programs = collect();

            // Only log if it's not a "table doesn't exist" error
            if (strpos($e->getMessage(), ' doesn\'t exist') === false) {
                \Log::error('ProgramController@index: Exception occurred', [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }

            return view('website.programs.index', compact('programs', 'category'));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $program = Program::findOrFail($id);
            return view('website.programs.show', compact('program'));
        } catch (\Exception $e) {
            \Log::error('ProgramController@show: Exception occurred', [
                'message' => $e->getMessage(),
                'id' => $id
            ]);
            return redirect()->route('programs')->with('error', 'Program not found.');
        }
    }

    // Enrollment methods removed - replaced with new EnrollmentController
}
