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
        $category = $request->get('category', 'all');

        $programs = Program::when($category !== 'all', function ($query) use ($category) {
            return $query->where('category', $category);
        })->get();

        return view('website.programs.index', compact('programs', 'category'));
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $program = Program::findOrFail($id);
        return view('website.programs.show', compact('program'));
    }

    /**
     * Show the enrollment form.
     */
    public function showEnrollmentForm()
    {
        $programs = Program::all();
        return view('website.enrollment.index', compact('programs'));
    }

    /**
     * Handle program enrollment.
     */
    public function enroll(Request $request)
    {
        $request->validate([
            'program_id' => 'required|exists:programs,id',
            'student_name' => 'required|string|max:255',
            'student_age' => 'required|integer|min:6|max:18',
            'parent_name' => 'required|string|max:255',
            'parent_email' => 'required|email',
            'parent_phone' => 'required|string',
            'emergency_contact' => 'required|string',
            'medical_conditions' => 'nullable|string',
        ]);

        // Here you would typically store the enrollment data
        // For now, we'll just redirect with a success message

        return redirect()->route('registration.success', 'program')
                        ->with('success', 'Enrollment submitted successfully!');
    }

    /**
     * Show registration choice page.
     */
    public function showRegistrationChoice()
    {
        return view('website.register.index');
    }

    /**
     * Show player registration form.
     */
    public function registerPlayer()
    {
        return view('website.register.player');
    }

    /**
     * Store player registration.
     */
    public function storePlayer(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'terms_accepted' => 'required|accepted',
        ]);

        // Split name into first and last
        $nameParts = explode(' ', $request->name, 2);
        $firstName = $nameParts[0] ?? '';
        $lastName = $nameParts[1] ?? '';

        // Create player user with pending status
        $user = User::create([
            'first_name' => $firstName,
            'last_name' => $lastName,
            'name' => $request->name, // Keep for compatibility
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'user_type' => 'player',
            'approval_status' => 'pending',
        ]);

        // Assign player role
        $playerRole = \App\Models\Role::where('slug', 'player')->first();
        if ($playerRole) {
            $user->assignRole($playerRole);
        }

        // Auto-login the user
        auth()->login($user);

        return redirect()->route('registration.success', 'player')
                        ->with('success', 'Player registration submitted for approval!');
    }

    /**
     * Show partner registration form.
     */
    public function registerPartner()
    {
        return view('website.register.partner');
    }

    /**
     * Store partner registration.
     */
    public function storePartner(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'organization_name' => 'required|string|max:255',
            'organization_type' => 'required|string',
            'contact_person' => 'required|string|max:255',
            'contact_position' => 'required|string|max:255',
            'phone' => 'required|string|unique:users',
            'country' => 'required|string',
            'city' => 'required|string',
            'address' => 'required|string',
            'partnership_type' => 'required|string',
            'expected_users' => 'required|integer|min:1',
            'terms_accepted' => 'required|accepted',
            'additional_requirements' => 'nullable|string',
        ]);

        // Split contact_person into first and last
        $nameParts = explode(' ', $request->contact_person, 2);
        $firstName = $nameParts[0] ?? '';
        $lastName = $nameParts[1] ?? '';

        // Create partner user with pending status
        $user = User::create([
            'first_name' => $firstName,
            'last_name' => $lastName,
            'name' => $request->contact_person, // Keep for compatibility
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => bcrypt($request->password),
            'user_type' => 'partner',
            'approval_status' => 'pending',
            'partner_details' => [
                'organization_name' => $request->organization_name,
                'organization_type' => $request->organization_type,
                'contact_position' => $request->contact_position,
                'country' => $request->country,
                'city' => $request->city,
                'address' => $request->address,
                'partnership_type' => $request->partnership_type,
                'expected_users' => $request->expected_users,
                'additional_requirements' => $request->additional_requirements,
            ],
        ]);

        // Assign partner role (generic partner role, can be upgraded later)
        $partnerRole = \App\Models\Role::where('slug', 'partner-marketing')->first(); // Default partner role
        if ($partnerRole) {
            $user->assignRole($partnerRole);
        }

        // Auto-login the user
        auth()->login($user);

        return redirect()->route('registration.success', 'partner')
                        ->with('success', 'Partner registration submitted for approval!');
    }

    /**
     * Show registration success page.
     */
    public function showRegistrationSuccess($type)
    {
        $typeData = [
            'program' => [
                'title' => 'Program Enrollment Successful!',
                'message' => 'Welcome to Vipers Academy Programs!',
                'description' => 'Your enrollment has been submitted successfully. Our team will review your application and contact you within 24-48 hours.',
                'color' => '#10b981',
                'icon' => 'fas fa-graduation-cap'
            ],
            'player' => [
                'title' => 'Player Registration Submitted!',
                'message' => 'Welcome to Vipers Academy!',
                'description' => 'Your player registration has been submitted for approval. You will receive an email notification once your account is approved. You can log in to check your approval status.',
                'color' => '#8b5cf6',
                'icon' => 'fas fa-futbol'
            ],
            'partner' => [
                'title' => 'Partner Registration Submitted!',
                'message' => 'Thank you for your interest in partnering with Vipers Academy!',
                'description' => 'Your partnership application has been submitted for review. Our team will contact you within 24-48 hours to discuss next steps and approve your account.',
                'color' => '#f59e0b',
                'icon' => 'fas fa-handshake'
            ],
        ];

        $data = $typeData[$type] ?? [
            'title' => 'Registration Successful!',
            'message' => 'Welcome!',
            'description' => 'Your registration has been processed successfully.',
            'color' => '#6b7280',
            'icon' => 'fas fa-check-circle'
        ];

        return view('website.register.success', compact('data', 'type'));
    }

    /**
     * Show user's enrollments.
     */
    public function myEnrollments()
    {
        // This would typically show the logged-in user's enrollments
        // For now, return a placeholder view
        return view('website.enrollment.my-enrollments');
    }
}
