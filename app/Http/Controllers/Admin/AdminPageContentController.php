<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PageContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminPageContentController extends Controller
{
    /**
     * Display list of pages that can be edited
     */
    public function index()
    {
        // Get unique pages from page_contents
        $pages = PageContent::select('page')
            ->distinct()
            ->orderBy('page')
            ->pluck('page');

        return view('admin.page-content.index', compact('pages'));
    }

    /**
     * Display sections for a specific page
     */
    public function showPage(string $page)
    {
        $sections = PageContent::where('page', $page)
            ->select('section')
            ->distinct()
            ->orderBy('section')
            ->pluck('section');

        return view('admin.page-content.show', compact('page', 'sections'));
    }

    /**
     * Display content for editing a specific section
     */
    public function editSection(string $page, string $section)
    {
        $contents = PageContent::where('page', $page)
            ->where('section', $section)
            ->orderBy('sort_order')
            ->get();

        if ($contents->isEmpty()) {
            return redirect()->route('admin.page-content.index')
                ->with('error', 'Section not found.');
        }

        return view('admin.page-content.edit', compact('page', 'section', 'contents'));
    }

    /**
     * Update page content
     */
    public function update(Request $request, string $page, string $section)
    {
        $request->validate([
            'contents' => 'required|array',
            'contents.*.id' => 'required|integer',
            'contents.*.value' => 'required|string',
        ]);

        try {
            foreach ($request->contents as $contentData) {
                $content = PageContent::find($contentData['id']);
                if ($content) {
                    $content->value = $contentData['value'];
                    $content->save();
                }
            }

            return redirect()->route('admin.page-content.show', ['page' => $page])
                ->with('success', 'Content updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating page content: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to update content. Please try again.')
                ->withInput();
        }
    }

    /**
     * Add a new timeline entry (for journey section)
     */
    public function addJourneyEntry(Request $request)
    {
        $request->validate([
            'year' => 'required|string|max:10',
            'description' => 'required|string',
        ]);

        try {
            // Check if year already exists
            $existing = PageContent::where('page', 'about')
                ->where('section', 'journey')
                ->where('key', 'year_' . $request->year)
                ->first();

            if ($existing) {
                return redirect()->back()
                    ->with('error', 'An entry for this year already exists.')
                    ->withInput();
            }

            // Get max sort_order
            $maxOrder = PageContent::where('page', 'about')
                ->where('section', 'journey')
                ->where('key', 'like', 'year_%')
                ->max('sort_order') ?? 0;

            PageContent::create([
                'page' => 'about',
                'section' => 'journey',
                'key' => 'year_' . $request->year,
                'value' => $request->description,
                'type' => 'textarea',
                'sort_order' => $maxOrder + 1,
                'is_active' => true,
            ]);

            return redirect()->route('admin.page-content.edit', ['page' => 'about', 'section' => 'journey'])
                ->with('success', 'Journey entry added successfully.');
        } catch (\Exception $e) {
            Log::error('Error adding journey entry: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to add entry. Please try again.')
                ->withInput();
        }
    }

    /**
     * Delete a timeline entry
     */
    public function deleteJourneyEntry(int $id)
    {
        try {
            $content = PageContent::findOrFail($id);
            $content->delete();

            return redirect()->route('admin.page-content.edit', ['page' => 'about', 'section' => 'journey'])
                ->with('success', 'Entry deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Error deleting journey entry: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to delete entry. Please try again.');
        }
    }

    /**
     * Add a new value entry
     */
    public function addValueEntry(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:100',
            'description' => 'required|string',
            'icon' => 'required|string|max:10',
        ]);

        try {
            // Create a slug from title
            $key = strtolower(str_replace(' ', '_', $request->title));
            $key = preg_replace('/[^a-z0-9_]/', '', $key);

            // Check if key already exists
            $existing = PageContent::where('page', 'about')
                ->where('section', 'values')
                ->where('key', $key)
                ->first();

            if ($existing) {
                return redirect()->back()
                    ->with('error', 'A value with this title already exists.')
                    ->withInput();
            }

            // Get max sort_order
            $maxOrder = PageContent::where('page', 'about')
                ->where('section', 'values')
                ->where('key', 'not_like', 'title%')
                ->max('sort_order') ?? 0;

            // Store as JSON for icon + description
            $value = json_encode([
                'icon' => $request->icon,
                'title' => $request->title,
                'description' => $request->description,
            ]);

            PageContent::create([
                'page' => 'about',
                'section' => 'values',
                'key' => $key,
                'value' => $value,
                'type' => 'json',
                'sort_order' => $maxOrder + 1,
                'is_active' => true,
            ]);

            return redirect()->route('admin.page-content.edit', ['page' => 'about', 'section' => 'values'])
                ->with('success', 'Value entry added successfully.');
        } catch (\Exception $e) {
            Log::error('Error adding value entry: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to add entry. Please try again.')
                ->withInput();
        }
    }

    /**
     * Delete a value entry
     */
    public function deleteValueEntry(int $id)
    {
        try {
            $content = PageContent::findOrFail($id);
            $content->delete();

            return redirect()->route('admin.page-content.edit', ['page' => 'about', 'section' => 'values'])
                ->with('success', 'Value deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Error deleting value entry: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to delete entry. Please try again.');
        }
    }
}
