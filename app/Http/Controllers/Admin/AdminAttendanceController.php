<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Player;
use App\Models\ActivityLog;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class AdminAttendanceController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function index(Request $request)
    {
        $query = Attendance::with(['player', 'session', 'recorder']);

        // Filter by date if provided
        if ($request->filled('date')) {
            $query->where('session_date', $request->date);
        }

        // Filter by session type
        if ($request->filled('session_type')) {
            $query->where('session_type', $request->session_type);
        }

        // Filter by session type in related session
        if ($request->filled('session_type') && !$request->filled('session_id')) {
            $query->whereHas('session', function ($q) use ($request) {
                $q->where('session_type', $request->session_type);
            });
        }

        // Filter by session if provided
        if ($request->filled('session_id')) {
            $query->where('session_id', $request->session_id);
        }

        // For coaches, only show players from their partner
        if (Auth::user()->hasRole('coach')) {
            $query->whereHas('player', function ($q) {
                $q->where('partner_id', Auth::user()->partner_id);
            });
        }

        $attendances = $query->orderBy('session_date', 'desc')
            ->orderBy('check_in_time', 'desc')
            ->paginate(20);

        // Get active sessions for the selector
        $activeSessions = \App\Models\TrainingSession::active()
            ->when(Auth::user()->hasRole('coach'), function ($q) {
                // Coaches can only see sessions for their team categories
                // This would need to be enhanced based on coach's assigned teams
            })
            ->orderBy('scheduled_start_time', 'desc')
            ->get();

        return view('admin.attendance.index', compact('attendances', 'activeSessions'));
    }

    public function create()
    {
        $players = Player::where('status', 'active');

        // For coaches, only show their partner's players
        if (Auth::user()->hasRole('coach')) {
            $players->where('partner_id', Auth::user()->partner_id);
        }

        $players = $players->orderBy('first_name')->get();

        return view('admin.attendance.create', compact('players'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'player_id' => 'required|exists:players,id',
            'session_id' => 'required|exists:training_sessions,id',
        ]);

        $player = Player::find($request->player_id);
        $session = \App\Models\TrainingSession::find($request->session_id);

        // Check if coach has access to this player
        if (Auth::user()->hasRole('coach') && $player->partner_id !== Auth::user()->partner_id) {
            abort(403, 'Unauthorized access to this player.');
        }

        // Check if attendance already exists for this session
        $existing = Attendance::where('player_id', $request->player_id)
            ->where('session_id', $request->session_id)
            ->first();

        if ($existing) {
            return back()->with('error', 'Attendance already recorded for this session.');
        }

        // Additional check to prevent duplicates based on player, session type, and date
        $duplicateCheck = Attendance::where('player_id', $request->player_id)
            ->where('session_type', $session->session_type)
            ->where('session_date', $session->scheduled_start_time->toDateString())
            ->first();

        if ($duplicateCheck) {
            return back()->with('error', 'Attendance already recorded for this player on this date and session type.');
        }

        $attendance = Attendance::create([
            'player_id' => $request->player_id,
            'session_id' => $request->session_id,
            'session_type' => $session->session_type,
            'session_date' => $session->scheduled_start_time->toDateString(),
            'recorded_by' => Auth::id(),
        ]);

        // Update player attendance category
        $attendance->player->updateAttendanceCategory();

        // Log activity
        ActivityLog::log('marked_attendance', 'Attendance', $attendance->id, [
            'player_id' => $request->player_id,
            'session_id' => $request->session_id,
        ]);

        return redirect()->route('admin.attendance.index')->with('success', 'Attendance recorded successfully.');
    }

    public function checkIn($attendanceId)
    {
        $attendance = Attendance::findOrFail($attendanceId);

        // Check permissions
        if (Auth::user()->hasRole('coach') && $attendance->player->partner_id !== Auth::user()->partner_id) {
            abort(403);
        }

        try {
            $attendance->checkIn(Auth::id());
            // Send notification to parent
            $this->notificationService->sendCheckInNotification($attendance->player, $attendance->session_type, $attendance->check_in_time);

            // Log activity
            ActivityLog::log('clocked_player_in', 'Attendance', $attendance->id, [
                'player_id' => $attendance->player_id,
                'check_in_time' => $attendance->check_in_time
            ]);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Player checked in successfully.');
    }

    public function checkOut($attendanceId)
    {
        $attendance = Attendance::findOrFail($attendanceId);

        // Check permissions
        if (Auth::user()->hasRole('coach') && $attendance->player->partner_id !== Auth::user()->partner_id) {
            abort(403);
        }

        try {
            $attendance->checkOut();
            // Send notification to parent
            $this->notificationService->sendCheckOutNotification($attendance->player, $attendance->session_type, $attendance->check_out_time);

            // Log activity
            ActivityLog::log('clocked_player_out', 'Attendance', $attendance->id, [
                'player_id' => $attendance->player_id,
                'check_out_time' => $attendance->check_out_time
            ]);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Player checked out successfully.');
    }

    public function show($attendanceId)
    {
        $attendance = Attendance::with(['player', 'recorder'])->findOrFail($attendanceId);

        // Check permissions
        if (Auth::user()->hasRole('coach') && $attendance->player->partner_id !== Auth::user()->partner_id) {
            abort(403);
        }

        return view('admin.attendance.show', compact('attendance'));
    }

    public function showExportPage()
    {
        return view('admin.attendance.export');
    }

    public function export(Request $request)
    {
        $query = Attendance::with(['player.partner', 'recorder']);

        // Apply filters from the form
        if ($request->filled('start_date')) {
            $query->where('session_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->where('session_date', '<=', $request->end_date);
        }

        if ($request->filled('session_type')) {
            $query->where('session_type', $request->session_type);
        }

        if ($request->filled('status')) {
            // Map status to actual attendance status
            switch ($request->status) {
                case 'scheduled':
                    $query->whereNull('check_in_time');
                    break;
                case 'checked_in':
                    $query->whereNotNull('check_in_time')->whereNull('check_out_time');
                    break;
                case 'completed':
                    $query->whereNotNull('check_out_time');
                    break;
            }
        }

        if (Auth::user()->hasRole('coach')) {
            $query->whereHas('player', function ($q) {
                $q->where('partner_id', Auth::user()->partner_id);
            });
        }

        $attendances = $query->orderBy('session_date', 'desc')->get();

        $filename = 'attendance_export_' . now()->format('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $includePlayerDetails = $request->boolean('include_player_details');

        $callback = function () use ($attendances, $includePlayerDetails) {
            $file = fopen('php://output', 'w');

            // Add CSV headers
            $csvHeaders = [
                'Player Name',
                'Session Type',
                'Session Date',
                'Check In Time',
                'Check Out Time',
                'Duration (minutes)',
                'Recorded By',
                'Recorded At'
            ];

            if ($includePlayerDetails) {
                $csvHeaders = array_merge($csvHeaders, [
                    'Player Email',
                    'Player Phone',
                    'Date of Birth',
                    'Position',
                    'Partner'
                ]);
            }

            fputcsv($file, $csvHeaders);

            // Add data rows
            foreach ($attendances as $attendance) {
                $row = [
                    $attendance->player->full_name,
                    ucfirst($attendance->session_type),
                    $attendance->session_date->format('Y-m-d'),
                    $attendance->check_in_time ? $attendance->check_in_time->format('H:i:s') : 'N/A',
                    $attendance->check_out_time ? $attendance->check_out_time->format('H:i:s') : 'N/A',
                    $attendance->total_duration_minutes ?: 'N/A',
                    $attendance->recorder->name ?? 'System',
                    $attendance->created_at->format('Y-m-d H:i:s')
                ];

                if ($includePlayerDetails) {
                    $row = array_merge($row, [
                        $attendance->player->email ?? 'N/A',
                        $attendance->player->phone ?? 'N/A',
                        $attendance->player->date_of_birth ? $attendance->player->date_of_birth->format('Y-m-d') : 'N/A',
                        $attendance->player->position ?? 'N/A',
                        $attendance->player->partner->name ?? 'N/A'
                    ]);
                }

                fputcsv($file, $row);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }


}
