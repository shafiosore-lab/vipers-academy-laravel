<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\OrganizationLetterhead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class SuperAdminLetterheadController extends Controller
{
    /**
     * Display letterhead management for super-admin (all organizations).
     */
    public function index(Request $request)
    {
        $organizations = Organization::with('letterheads')->orderBy('name')->get();
        $selectedOrgId = $request->get('organization_id');

        if ($selectedOrgId) {
            $organization = Organization::findOrFail($selectedOrgId);
        } else {
            $organization = $organizations->first();
        }

        if (!$organization) {
            return view('super-admin.letterhead.index', [
                'organizations' => $organizations,
                'organization' => null,
                'letterheads' => collect(),
            ]);
        }

        $letterheads = OrganizationLetterhead::where('organization_id', $organization->id)
            ->orderBy('is_default', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('super-admin.letterhead.index', compact('organizations', 'organization', 'letterheads'));
    }

    /**
     * Show the letterhead create form.
     */
    public function create(Request $request)
    {
        $organizationId = $request->get('organization_id');

        if (!$organizationId) {
            return redirect()->route('super-admin.letterhead.index')
                ->with('error', 'Please select an organization first.');
        }

        $organization = Organization::findOrFail($organizationId);
        $styles = OrganizationLetterhead::getStyles();
        $alignments = OrganizationLetterhead::getAlignments();

        return view('super-admin.letterhead.create', compact('organization', 'styles', 'alignments'));
    }

    /**
     * Store a new letterhead.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'organization_id' => 'required|exists:organizations,id',
            'name' => 'required|string|max:255',
            'style' => 'required|in:classic,modern,minimal',
            'header_alignment' => 'required|in:left,center',
            'primary_color' => 'required|string|max:20',
            'show_watermark' => 'boolean',
            'show_page_numbers' => 'boolean',
            'custom_footer_note' => 'nullable|string|max:500',
            'signature_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'signature_name' => 'nullable|string|max:255',
            'signature_title' => 'nullable|string|max:255',
            'is_default' => 'boolean',
        ]);

        $validated['show_watermark'] = $request->boolean('show_watermark', true);
        $validated['show_page_numbers'] = $request->boolean('show_page_numbers', true);
        $validated['is_default'] = $request->boolean('is_default', false);

        // Handle signature image upload
        if ($request->hasFile('signature_image')) {
            $path = $request->file('signature_image')->store('signatures', 'public');
            $validated['signature_image'] = $path;
        }

        // If setting as default, unset other defaults
        if ($validated['is_default']) {
            OrganizationLetterhead::where('organization_id', $validated['organization_id'])
                ->update(['is_default' => false]);
        }

        OrganizationLetterhead::create($validated);

        return redirect()->route('super-admin.letterhead.index', ['organization_id' => $validated['organization_id']])
            ->with('success', 'Letterhead created successfully.');
    }

    /**
     * Show the letterhead edit form.
     */
    public function edit(OrganizationLetterhead $letterhead)
    {
        $organization = Organization::findOrFail($letterhead->organization_id);
        $styles = OrganizationLetterhead::getStyles();
        $alignments = OrganizationLetterhead::getAlignments();

        return view('super-admin.letterhead.edit', compact('letterhead', 'organization', 'styles', 'alignments'));
    }

    /**
     * Update an existing letterhead.
     */
    public function update(Request $request, OrganizationLetterhead $letterhead)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'style' => 'required|in:classic,modern,minimal',
            'header_alignment' => 'required|in:left,center',
            'primary_color' => 'required|string|max:20',
            'show_watermark' => 'boolean',
            'show_page_numbers' => 'boolean',
            'custom_footer_note' => 'nullable|string|max:500',
            'signature_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'signature_name' => 'nullable|string|max:255',
            'signature_title' => 'nullable|string|max:255',
            'is_default' => 'boolean',
        ]);

        $validated['show_watermark'] = $request->boolean('show_watermark', true);
        $validated['show_page_numbers'] = $request->boolean('show_page_numbers', true);
        $validated['is_default'] = $request->boolean('is_default', false);

        // Handle signature image upload
        if ($request->hasFile('signature_image')) {
            // Delete old signature if exists
            if ($letterhead->signature_image) {
                Storage::disk('public')->delete($letterhead->signature_image);
            }
            $path = $request->file('signature_image')->store('signatures', 'public');
            $validated['signature_image'] = $path;
        }

        // If setting as default, unset other defaults
        if ($validated['is_default']) {
            OrganizationLetterhead::where('organization_id', $letterhead->organization_id)
                ->where('id', '!=', $letterhead->id)
                ->update(['is_default' => false]);
        }

        $letterhead->update($validated);

        return redirect()->route('super-admin.letterhead.index', ['organization_id' => $letterhead->organization_id])
            ->with('success', 'Letterhead updated successfully.');
    }

    /**
     * Delete a letterhead.
     */
    public function destroy(OrganizationLetterhead $letterhead)
    {
        $organizationId = $letterhead->organization_id;

        // Delete signature image if exists
        if ($letterhead->signature_image) {
            Storage::disk('public')->delete($letterhead->signature_image);
        }

        $letterhead->delete();

        return redirect()->route('super-admin.letterhead.index', ['organization_id' => $organizationId])
            ->with('success', 'Letterhead deleted successfully.');
    }
}
