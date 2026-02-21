<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompanySettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CompanySettingsController extends Controller
{
    /**
     * Display company settings page
     */
    public function index()
    {
        $settings = CompanySettings::getActive() ?? new CompanySettings();

        return view('admin.settings.company', compact('settings'));
    }

    /**
     * Update company settings
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_name' => ['required', 'string', 'max:255'],
            'company_email' => ['required', 'email', 'max:255'],
            'company_phone' => ['required', 'string', 'max:50', 'regex:/^[0-9+\s\-()]+$/'],
            'company_address' => ['nullable', 'string', 'max:500'],
            'company_website' => ['nullable', 'url', 'max:255'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'pdf_footer_enabled' => ['nullable', 'boolean'],
            'pdf_footer_text' => ['nullable', 'string', 'max:500'],
        ], [
            'company_phone.regex' => 'Please enter a valid phone number.',
            'company_website.url' => 'Please enter a valid URL (including http:// or https://).',
            'logo.max' => 'The logo file size must not exceed 2MB.',
            'logo.image' => 'The logo must be an image file (jpeg, png, jpg, gif, or svg).',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $data = $request->only([
                'company_name',
                'company_email',
                'company_phone',
                'company_address',
                'company_website',
                'pdf_footer_enabled',
                'pdf_footer_text',
            ]);

            // Handle logo upload
            if ($request->hasFile('logo')) {
                $logo = $request->file('logo');
                $filename = 'company_logo_' . time() . '.' . $logo->getClientOriginalExtension();

                // Delete old logo if exists
                $settings = CompanySettings::getActive();
                if ($settings && $settings->logo_path) {
                    $oldPath = public_path($settings->logo_path);
                    if (File::exists($oldPath)) {
                        File::delete($oldPath);
                    }
                }

                // Store new logo
                $logo->move(public_path('assets/img'), $filename);
                $data['logo_path'] = 'assets/img/' . $filename;
            }

            // Set as active
            $data['is_active'] = true;

            // Update or create settings
            $settings = CompanySettings::getActive();

            if ($settings) {
                $settings->update($data);
            } else {
                CompanySettings::create($data);
            }

            return back()->with('success', 'Company settings updated successfully!');

        } catch (\Exception $e) {
            Log::error("Company settings update failed: " . $e->getMessage());

            return back()->with('error', 'Failed to update settings: ' . $e->getMessage());
        }
    }

    /**
     * Preview PDF with current settings
     */
    public function preview()
    {
        $settings = CompanySettings::getActive();

        return view('admin.settings.pdf-preview', compact('settings'));
    }

    /**
     * Validate company email
     */
    public function validateEmail(Request $request)
    {
        $email = $request->input('email');

        if (CompanySettings::isRegisteredEmail($email)) {
            return response()->json([
                'valid' => true,
                'message' => 'This is a registered company email.'
            ]);
        }

        return response()->json([
            'valid' => false,
            'message' => 'This email is not registered in the system.'
        ]);
    }
}
