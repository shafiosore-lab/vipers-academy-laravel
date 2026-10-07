<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\PageContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SuperAdminPageContentController extends Controller
{
    /**
     * Display list of all pages (both global and organization-specific)
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', PageContent::class);

        $type = $request->get('type', 'all'); // all, global, organization

        $query = PageContent::select('page')
            ->distinct()
            ->orderBy('page');

        if ($type === 'global') {
            $query->global();
        } elseif ($type === 'organization') {
            $query->whereNotNull('organization_id');
        }

        $pages = $query->pluck('page');

        return view('super-admin.page-content.index', compact('pages', 'type'));
    }

    /**
     * Display sections for a specific page
     */
    public function showPage(Request $request, string $page)
    {
        $this->authorize('viewAny', PageContent::class);

        $organizationId = $request->get('organization_id');

        $query = PageContent::where('page', $page)
            ->select('section', 'organization_id')
            ->distinct()
            ->orderBy('section');

        if ($organizationId) {
            $query->forOrganization($organizationId);
        } else {
            // Show all (global + org specific)
        }

        $sections = $query->get()->groupBy('section');

        return view('super-admin.page-content.show', compact('page', 'sections'));
    }

    /**
     * Display content for editing a specific section
     */
    public function editSection(Request $request, string $page, string $section)
    {
        $this->authorize('viewAny', PageContent::class);

        $organizationId = $request->get('organization_id');

        $query = PageContent::where('page', $page)
            ->where('section', $section)
            ->orderBy('sort_order');

        if ($organizationId) {
            $contents = $query->get();
        } else {
            // Show all content for this section
            $contents = $query->get();
        }

        if ($contents->isEmpty()) {
            return redirect()->route('super-admin.page-content.index')
                ->with('error', 'Section not found.');
        }

        return view('super-admin.page-content.edit', compact('page', 'section', 'contents', 'organizationId'));
    }

    /**
     * Update page content
     */
    public function update(Request $request, string $page, string $section)
    {
        $this->authorize('viewAny', PageContent::class);

        $request->validate([
            'contents' => 'required|array',
            'contents.*.id' => 'required|integer',
            'contents.*.value' => 'required|string',
        ]);

        try {
            foreach ($request->contents as $contentData) {
                $content = PageContent::find($contentData['id']);
                if ($content) {
                    // Check permission to update this specific content
                    $this->authorize('update', $content);

                    $content->value = $contentData['value'];
                    $content->save();
                }
            }

            return redirect()->route('super-admin.page-content.show', ['page' => $page])
                ->with('success', 'Content updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating page content: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to update content. Please try again.')
                ->withInput();
        }
    }

    /**
     * Create new content item
     */
    public function store(Request $request, string $page, string $section)
    {
        $this->authorize('create', PageContent::class);

        $request->validate([
            'key' => 'required|string|max:255',
            'value' => 'required|string',
            'type' => 'required|string|in:text,textarea,json,image,number',
            'organization_id' => 'nullable|integer',
        ]);

        try {
            // Check if user can create global content
            $organizationId = $request->organization_id;

            if (is_null($organizationId)) {
                $this->authorize('createGlobal', PageContent::class);
            } else {
                $this->authorize('manageOrganization', [PageContent::class, $organizationId]);
            }

            // Check for duplicate key in same page/section/org
            $existing = PageContent::where('page', $page)
                ->where('section', $section)
                ->where('key', $request->key)
                ->when($organizationId, fn($q) => $q->where('organization_id', $organizationId))
                ->when(!$organizationId, fn($q) => $q->global())
                ->first();

            if ($existing) {
                return redirect()->back()
                    ->with('error', 'A content item with this key already exists.')
                    ->withInput();
            }

            // Get max sort_order
            $maxOrder = PageContent::where('page', $page)
                ->where('section', $section)
                ->when($organizationId, fn($q) => $q->where('organization_id', $organizationId))
                ->max('sort_order') ?? 0;

            PageContent::create([
                'organization_id' => $organizationId,
                'page' => $page,
                'section' => $section,
                'key' => $request->key,
                'value' => $request->value,
                'type' => $request->type,
                'sort_order' => $maxOrder + 1,
                'is_active' => true,
            ]);

            return redirect()->route('super-admin.page-content.edit', ['page' => $page, 'section' => $section])
                ->with('success', 'Content created successfully.');
        } catch (\Exception $e) {
            Log::error('Error creating page content: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to create content. Please try again.')
                ->withInput();
        }
    }

    /**
     * Delete content item
     */
    public function destroy(int $id)
    {
        try {
            $content = PageContent::findOrFail($id);

            $this->authorize('delete', $content);

            $page = $content->page;
            $section = $content->section;

            $content->delete();

            return redirect()->route('super-admin.page-content.edit', ['page' => $page, 'section' => $section])
                ->with('success', 'Content deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Error deleting page content: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to delete content. Please try again.');
        }
    }

    /**
     * Toggle content active status
     */
    public function toggleStatus(int $id)
    {
        try {
            $content = PageContent::findOrFail($id);

            $this->authorize('update', $content);

            $content->is_active = !$content->is_active;
            $content->save();

            return redirect()->back()
                ->with('success', 'Content status updated.');
        } catch (\Exception $e) {
            Log::error('Error toggling page content status: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to update status. Please try again.');
        }
    }

    /**
     * Copy content from global to organization
     */
    public function copyToOrganization(Request $request, int $id)
    {
        $this->authorize('viewAny', PageContent::class);

        $request->validate([
            'organization_id' => 'required|integer',
        ]);

        try {
            $sourceContent = PageContent::findOrFail($id);

            // Verify user can manage target organization
            $this->authorize('manageOrganization', [PageContent::class, $request->organization_id]);

            // Check if content already exists for this org
            $existing = PageContent::where('page', $sourceContent->page)
                ->where('section', $sourceContent->section)
                ->where('key', $sourceContent->key)
                ->forOrganization($request->organization_id)
                ->first();

            if ($existing) {
                return redirect()->back()
                    ->with('error', 'Content already exists for this organization.');
            }

            // Copy content
            PageContent::create([
                'organization_id' => $request->organization_id,
                'page' => $sourceContent->page,
                'section' => $sourceContent->section,
                'key' => $sourceContent->key,
                'value' => $sourceContent->value,
                'type' => $sourceContent->type,
                'sort_order' => $sourceContent->sort_order,
                'is_active' => $sourceContent->is_active,
            ]);

            return redirect()->back()
                ->with('success', 'Content copied to organization successfully.');
        } catch (\Exception $e) {
            Log::error('Error copying page content: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to copy content. Please try again.');
        }
    }

    /**
     * Get organizations for dropdown
     */
    public function getOrganizations()
    {
        $organizations = \App\Models\Organization::active()
            ->orderBy('name')
            ->pluck('name', 'id');

        return response()->json($organizations);
    }
}
