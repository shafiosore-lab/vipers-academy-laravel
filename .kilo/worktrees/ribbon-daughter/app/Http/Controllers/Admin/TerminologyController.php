<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FootballTerminology;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TerminologyController extends Controller
{
    /**
     * Get all terminologies grouped by category.
     */
    public function index(): JsonResponse
    {
        $terminologies = FootballTerminology::getGroupedByCategory();
        return response()->json($terminologies);
    }

    /**
     * Enhance game summary text with proper terminology.
     */
    public function enhance(Request $request): JsonResponse
    {
        $request->validate([
            'text' => 'required|string|min:5',
        ]);

        $text = $request->input('text');
        $enhancedText = FootballTerminology::enhanceText($text);

        return response()->json([
            'original' => $text,
            'enhanced' => $enhancedText,
            'message' => 'Text enhanced with football terminology'
        ]);
    }

    /**
     * Search terminology by keyword.
     */
    public function search(Request $request): JsonResponse
    {
        $request->validate([
            'q' => 'required|string|min:2',
        ]);

        $results = FootballTerminology::search($request->input('q'));

        return response()->json($results);
    }
}
