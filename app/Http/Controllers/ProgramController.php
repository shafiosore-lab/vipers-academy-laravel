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

        return view('website.programs', compact('programs', 'category'));
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $program = Program::findOrFail($id);
        return view('website.program_detail', compact('program'));
    }

    /**
     * Show the enrollment form.
     */
    public function showEnrollmentForm()
    {
        $programs = Program::all();
        return view('website.enroll', compact('programs'));
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
        return view('website.register_choice');
    }

    /**
     * Show player registration form.
     */
    public function registerPlayer()
    {
        return view('website.register_player');
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
            'age' => 'required|integer|min:6|max:18',
            'date_of_birth' => 'required|date',
            'parent_name' => 'required|string|max:255',
            'parent_phone' => 'required|string',
            'position' => 'nullable|string',
        ]);

        // Create player user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'user_type' => 'player',
            'status' => 'active',
        ]);

        auth()->login($user);

        return redirect()->route('registration.success', 'player')
                        ->with('success', 'Player registration successful!');
    }

    /**
     * Show partner registration form.
     */
    public function registerPartner()
    {
        return view('website.register_partner');
    }

    /**
     * Store partner registration.
     */
    public function storePartner(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'organization' => 'required|string|max:255',
            'phone' => 'required|string',
            'address' => 'required|string',
        ]);

        // Create partner user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'user_type' => 'partner',
            'status' => 'pending',
        ]);

        auth()->login($user);

        return redirect()->route('registration.success', 'partner')
                        ->with('success', 'Partner registration submitted for approval!');
    }

    /**
     * Show registration success page.
     */
    public function showRegistrationSuccess($type)
    {
        $typeMap = [
            'program' => 'Program Enrollment',
            'player' => 'Player Registration',
            'partner' => 'Partner Registration',
        ];

        $title = $typeMap[$type] ?? 'Registration';

        return view('website.registration_success', compact('title', 'type'));
    }

    /**
     * Show user's enrollments.
     */
    public function myEnrollments()
    {
        // This would typically show the logged-in user's enrollments
        // For now, return a placeholder view
        return view('website.my_enrollments');
    }
}
