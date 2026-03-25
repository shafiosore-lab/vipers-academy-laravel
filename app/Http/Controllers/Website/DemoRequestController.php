<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DemoRequestController extends Controller
{
    /**
     * Store a new demo request.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'full_name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'nullable|string|max:50',
                'organization' => 'required|string|max:255',
                'role' => 'required|string|max:100',
                'organization_size' => 'required|string|max:50',
                'message' => 'nullable|string|max:1000',
            ]);

            // Insert into demo_requests table or use general table
            // Using page_content table as a fallback for demo requests
            DB::table('page_content')->insert([
                'page' => 'demo_request',
                'section' => 'form_submission',
                'content' => json_encode([
                    'full_name' => $validated['full_name'],
                    'email' => $validated['email'],
                    'phone' => $validated['phone'] ?? '',
                    'organization' => $validated['organization'],
                    'role' => $validated['role'],
                    'organization_size' => $validated['organization_size'],
                    'message' => $validated['message'] ?? '',
                    'submitted_at' => now()->toIso8601String(),
                ]),
                'status' => 'active',
                'order' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            Log::info('Demo request submitted', [
                'email' => $validated['email'],
                'organization' => $validated['organization'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Thank you for your interest! We will contact you shortly.'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Please fill in all required fields.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Demo request submission failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again later.'
            ], 500);
        }
    }
}
