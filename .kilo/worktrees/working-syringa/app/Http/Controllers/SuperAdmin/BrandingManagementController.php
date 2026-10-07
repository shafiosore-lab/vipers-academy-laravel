<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\OrganizationBranding;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class BrandingManagementController extends Controller
{
    /**
     * Display the branding settings for an organization.
     */
    public function index(Organization $organization)
    {
        $this->authorize('viewAny', [OrganizationBranding::class, $organization]);

        $branding = $organization->branding ?? new OrganizationBranding();
        $defaultColors = OrganizationBranding::getDefaultColors();
        $fontFamilies = OrganizationBranding::getFontFamilies();
        $defaultLetterhead = OrganizationBranding::getDefaultLetterheadTemplate();
        $defaultEmail = OrganizationBranding::getDefaultEmailTemplate();

        return view('super-admin.branding.index', compact(
            'organization', 'branding', 'defaultColors', 'fontFamilies', 'defaultLetterhead', 'defaultEmail'
        ));
    }

    /**
     * Update the branding settings for an organization.
     */
    public function update(Request $request, Organization $organization)
    {
        $this->authorize('update', [OrganizationBranding::class, $organization]);

        $validator = Validator::make($request->all(), [
            'primary_color' => 'required|string|regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/',
            'secondary_color' => 'required|string|regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/',
            'accent_color' => 'required|string|regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/',
            'font_family' => 'required|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'favicon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,ico|max:512',
            'header_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'footer_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'letterhead_template' => 'nullable|array',
            'email_template' => 'nullable|array',
            'document_template' => 'nullable|array',
            'social_media_links' => 'nullable|array',
            'brand_guidelines' => 'nullable|array',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $validator->validated();

        // Handle logo uploads
        $logoPaths = $this->handleLogoUploads($request, $organization, $organization->branding);

        // Merge logo paths with other data
        $data = array_merge($data, $logoPaths);

        // Create or update branding
        $branding = $organization->branding ?? new OrganizationBranding();
        $branding->fill($data);
        $branding->organization_id = $organization->id;
        $branding->save();

        return redirect()->route('super-admin.organizations.branding.index', $organization)
            ->with('success', 'Branding settings updated successfully.');
    }

    /**
     * Reset branding to default values.
     */
    public function reset(Organization $organization)
    {
        $this->authorize('update', [OrganizationBranding::class, $organization]);

        $branding = $organization->branding;
        if ($branding) {
            $branding->delete();
        }

        return redirect()->route('super-admin.organizations.branding.index', $organization)
            ->with('success', 'Branding settings reset to defaults.');
    }

    /**
     * Handle logo file uploads.
     */
    protected function handleLogoUploads(Request $request, Organization $organization, ?OrganizationBranding $existingBranding = null)
    {
        $paths = [];

        $logoFields = [
            'logo' => 'logo_path',
            'favicon' => 'favicon_path',
            'header_logo' => 'header_logo_path',
            'footer_logo' => 'footer_logo_path',
        ];

        foreach ($logoFields as $inputName => $fieldName) {
            if ($request->hasFile($inputName)) {
                // Delete old file if exists
                if ($existingBranding && $existingBranding->$fieldName) {
                    Storage::disk('public')->delete($existingBranding->$fieldName);
                }

                $file = $request->file($inputName);
                $path = $file->store('organization_branding/' . $organization->id, 'public');
                $paths[$fieldName] = $path;
            }
        }

        return $paths;
    }

    /**
     * Preview branding changes.
     */
    public function preview(Request $request, Organization $organization)
    {
        $this->authorize('viewAny', [OrganizationBranding::class, $organization]);

        $brandingData = $request->all();
        $brandingData['organization'] = $organization;

        return view('super-admin.branding.preview', compact('brandingData'));
    }

    /**
     * Export branding assets.
     */
    public function export(Organization $organization)
    {
        $this->authorize('viewAny', [OrganizationBranding::class, $organization]);

        $branding = $organization->branding;
        if (!$branding) {
            return redirect()->back()->with('error', 'No branding settings found.');
        }

        $exportData = [
            'organization' => $organization->name,
            'branding' => [
                'primary_color' => $branding->primary_color,
                'secondary_color' => $branding->secondary_color,
                'accent_color' => $branding->accent_color,
                'font_family' => $branding->font_family,
                'letterhead_template' => $branding->letterhead_template,
                'email_template' => $branding->email_template,
                'document_template' => $branding->document_template,
                'social_media_links' => $branding->social_media_links,
                'brand_guidelines' => $branding->brand_guidelines,
            ],
            'exported_at' => now()->toIso8601String(),
        ];

        $filename = "branding_export_{$organization->id}_" . now()->format('Y-m-d_H-i-s') . '.json';

        return response()->json($exportData)
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
    }

    /**
     * Import branding assets.
     */
    public function import(Request $request, Organization $organization)
    {
        $this->authorize('update', [OrganizationBranding::class, $organization]);

        $validator = Validator::make($request->all(), [
            'import_file' => 'required|file|mimes:json|max:1024',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $file = $request->file('import_file');
        $content = $file->get();
        $importData = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return redirect()->back()
                ->withErrors(['import_file' => 'Invalid JSON file.'])
                ->withInput();
        }

        if (!isset($importData['branding'])) {
            return redirect()->back()
                ->withErrors(['import_file' => 'Invalid branding data format.'])
                ->withInput();
        }

        $brandingData = $importData['branding'];

        // Validate imported data
        $validator = Validator::make($brandingData, [
            'primary_color' => 'required|string|regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/',
            'secondary_color' => 'required|string|regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/',
            'accent_color' => 'required|string|regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/',
            'font_family' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Create or update branding with imported data
        $branding = $organization->branding ?? new OrganizationBranding();
        $branding->fill($brandingData);
        $branding->organization_id = $organization->id;
        $branding->save();

        return redirect()->route('super-admin.organizations.branding.index', $organization)
            ->with('success', 'Branding settings imported successfully.');
    }
}
