<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class AdminEnrollmentController extends Controller
{
    public function index(Request $request)
    {
        $programs = Program::where('status', 'active')->get();

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $programs = Program::where('status', 'active')
                ->where(function ($query) use ($searchTerm) {
                    $query->where('title', 'LIKE', "%{$searchTerm}%")
                          ->orWhere('description', 'LIKE', "%{$searchTerm}%")
                          ->orWhere('age_group', 'LIKE', "%{$searchTerm}%");
                })
                ->get();
        }

        return view('admin.enrollment.index', compact('programs'));
    }

    public function store(Request $request)
    {
        // DEBUG: Log incoming request
        Log::info('DEBUG: Enrollment request started', [
            'all_input' => $request->all(),
            'has_first_name' => !empty($request->first_name),
            'has_last_name' => !empty($request->last_name),
            'has_email' => !empty($request->email),
            'has_phone' => !empty($request->phone),
            'has_program_id' => !empty($request->program_id),
        ]);

        $request->validate([
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
            'residence' => 'required|string|max:255',
            'learning_option' => 'required|in:Online,Physical',
            'program_id' => 'required|exists:programs,id',
        ]);

        // Check if email already exists manually
        if (\App\Models\User::where('email', $request->email)->exists()) {
            return back()->withErrors(['email' => 'This email address is already registered.'])->withInput();
        }

        try {
            // Create user account (without role assignment for now)
            $userData = [
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'name' => $request->first_name . ' ' . $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => bcrypt($request->phone), // Use phone as password
                'user_type' => 'student',
                'approval_status' => 'approved', // Auto-approve enrolled students
            ];
            Log::info('Creating user with data', $userData);
            $user = \App\Models\User::create($userData);

            // Try to assign student role (optional - if roles system exists)
            try {
                $studentRole = \App\Models\Role::firstOrCreate(
                    ['slug' => 'student'],
                    ['name' => 'Student', 'description' => 'Enrolled student with access to learning materials']
                );
                if ($studentRole) {
                    $user->assignRole($studentRole);
                }
            } catch (\Exception $roleException) {
                // Log role assignment failure but don't fail enrollment
                Log::warning('Failed to assign student role, continuing without role', [
                    'user_id' => $user->id,
                    'error' => $roleException->getMessage(),
                ]);
            }

            Log::info('User created successfully', ['user_id' => $user->id]);
            // Store enrollment
            $enrollmentData = [
                'email' => $request->email,
                'phone' => $request->phone,
                'residence' => $request->residence,
                'learning_option' => $request->learning_option,
                'program_id' => $request->program_id,
            ];
            Log::info('Creating enrollment with data', $enrollmentData);
            $enrollment = Enrollment::create($enrollmentData);

            // DEBUG: Verify enrollment was saved
            Log::info('DEBUG: Enrollment saved successfully', [
                'enrollment_id' => $enrollment->id,
                'enrollment_count' => Enrollment::count(),
                'enrollments_this_week' => Enrollment::whereDate('created_at', '>=', now()->startOfWeek())->count(),
            ]);

            // Clear dashboard cache so counts update immediately
            \Cache::tags(['admin_dashboard'])->flush();
            Log::info('DEBUG: Dashboard cache cleared');

            // Send email notification
            $this->sendEnrollmentNotification($enrollment);

            // Update admin dashboard (this could trigger real-time updates)
            Log::info('New enrollment received - user account created', [
                'enrollment_id' => $enrollment->id,
                'user_id' => $user->id,
                'email' => $enrollment->email,
                'program_id' => $enrollment->program_id,
            ]);

            return back()->with('success', 'Data has been captured successfully!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Enrollment validation failed', [
                'errors' => $e->errors(),
                'request_data' => $request->all(),
            ]);
            return back()->withErrors($e->errors())->withInput();
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Database error during enrollment', [
                'error' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings(),
                'request_data' => $request->all(),
            ]);
            return back()->with('error', 'Database error occurred. Please contact support.')->withInput();
        } catch (\Exception $e) {
            Log::error('Unexpected error during enrollment', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
            ]);

            return back()->with('error', 'An unexpected error occurred. Please try again later.')->withInput();
        }
    }

    private function sendEnrollmentNotification(Enrollment $enrollment)
    {
        try {
            $program = $enrollment->program;

            $details = [
                'email' => $enrollment->email,
                'phone' => $enrollment->phone,
                'residence' => $enrollment->residence,
                'learning_option' => $enrollment->learning_option,
                'program_title' => $program->title,
                'program_age_group' => $program->age_group,
                'submitted_at' => $enrollment->created_at->format('Y-m-d H:i:s'),
            ];

            // Send email to mumiasvipersfa@gmail.com
            Mail::raw(
                "New Enrollment Received:\n\n" .
                "Email: {$details['email']}\n" .
                "Phone: {$details['phone']}\n" .
                "Residence: {$details['residence']}\n" .
                "Learning Option: {$details['learning_option']}\n" .
                "Program: {$details['program_title']} ({$details['program_age_group']})\n" .
                "Submitted At: {$details['submitted_at']}\n",
                function ($message) use ($details) {
                    $message->to('mumiasvipersfa@gmail.com')
                            ->subject('New Program Enrollment - ' . $details['program_title']);
                }
            );

        } catch (\Exception $e) {
            Log::error('Failed to send enrollment notification email', [
                'enrollment_id' => $enrollment->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
