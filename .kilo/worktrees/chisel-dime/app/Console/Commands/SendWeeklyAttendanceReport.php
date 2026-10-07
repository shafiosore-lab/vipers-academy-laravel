<?php

namespace App\Console\Commands;

use App\Models\Attendance;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class SendWeeklyAttendanceReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'attendance:send-weekly-report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate and send weekly attendance report via email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Generating weekly attendance report...');

        // Get attendance data for the past week
        $startDate = now()->subWeek()->startOfWeek();
        $endDate = now()->subWeek()->endOfWeek();

        $attendances = Attendance::with(['player.partner', 'recorder'])
            ->whereBetween('session_date', [$startDate, $endDate])
            ->orderBy('session_date', 'desc')
            ->get();

        $this->info("Found {$attendances->count()} records between {$startDate} and {$endDate}");

        if ($attendances->isEmpty()) {
            $this->info('No attendance records found for the specified period.');
            return;
        }

        // Generate CSV content
        $csvContent = $this->generateCsvContent($attendances);

        // Generate PDF
        $pdf = Pdf::loadView('reports.weekly-attendance-pdf', compact('attendances', 'startDate', 'endDate'));

        // Save files to temporary storage
        $csvFilename = 'weekly_attendance_report_' . now()->format('Y-m-d') . '.csv';
        $pdfFilename = 'weekly_attendance_report_' . now()->format('Y-m-d') . '.pdf';

        $csvFilePath = 'temp/' . $csvFilename;
        $pdfFilePath = 'temp/' . $pdfFilename;

        Storage::put($csvFilePath, $csvContent);
        Storage::put($pdfFilePath, $pdf->output());

        // Send email with both attachments
        $this->sendEmail($csvFilePath, $csvFilename, $pdfFilePath, $pdfFilename, $startDate, $endDate, $attendances->count());

        // Clean up
        Storage::delete([$csvFilePath, $pdfFilePath]);

        $this->info('Weekly attendance report sent successfully!');
    }

    private function generateCsvContent($attendances)
    {
        $csvData = [];

        // Add headers
        $csvData[] = [
            'Player Name',
            'Session Type',
            'Session Date',
            'Check In Time',
            'Check Out Time',
            'Duration (minutes)',
            'Recorded By',
            'Recorded At',
            'Player Email',
            'Player Phone',
            'Date of Birth',
            'Position',
            'Partner'
        ];

        // Add data rows
        foreach ($attendances as $attendance) {
            $csvData[] = [
                $attendance->player->full_name,
                ucfirst($attendance->session_type),
                $attendance->session_date->format('Y-m-d'),
                $attendance->check_in_time ? $attendance->check_in_time->format('H:i:s') : 'N/A',
                $attendance->check_out_time ? $attendance->check_out_time->format('H:i:s') : 'N/A',
                $attendance->total_duration_minutes ?: 'N/A',
                $attendance->recorder->name ?? 'System',
                $attendance->created_at->format('Y-m-d H:i:s'),
                $attendance->player->email ?? 'N/A',
                $attendance->player->phone ?? 'N/A',
                $attendance->player->date_of_birth ? \Carbon\Carbon::parse($attendance->player->date_of_birth)->format('Y-m-d') : 'N/A',
                $attendance->player->position ?? 'N/A',
                $attendance->player->partner->name ?? 'N/A'
            ];
        }

        // Convert to CSV string
        $csvString = '';
        foreach ($csvData as $row) {
            $csvString .= implode(',', array_map(function($field) {
                return '"' . str_replace('"', '""', $field) . '"';
            }, $row)) . "\n";
        }

        return $csvString;
    }

    private function sendEmail($csvFilePath, $csvFilename, $pdfFilePath, $pdfFilename, $startDate, $endDate, $recordCount)
    {
        $email = 'mumiasvipersfa@gmail.com';

        Mail::raw('', function ($message) use ($csvFilePath, $csvFilename, $pdfFilePath, $pdfFilename, $startDate, $endDate, $recordCount, $email) {
            $message->to($email)
                    ->subject('Weekly Attendance Report - ' . $startDate->format('M j, Y') . ' to ' . $endDate->format('M j, Y'))
                    ->attach(Storage::path($csvFilePath), [
                        'as' => $csvFilename,
                        'mime' => 'text/csv'
                    ])
                    ->attach(Storage::path($pdfFilePath), [
                        'as' => $pdfFilename,
                        'mime' => 'application/pdf'
                    ])
                    ->html("
                        <h2>Weekly Attendance Report</h2>
                        <p>Please find attached the attendance report for the week of {$startDate->format('M j, Y')} to {$endDate->format('M j, Y')}.</p>
                        <p><strong>Total Records:</strong> {$recordCount}</p>
                        <p><strong>Attachments:</strong></p>
                        <ul>
                            <li>{$csvFilename} - CSV format for data analysis</li>
                            <li>{$pdfFilename} - PDF format with letterhead</li>
                        </ul>
                        <p>This report was automatically generated by the Mumias Vipers Academy system.</p>
                        <br>
                        <p>Best regards,<br>Mumias Vipers Academy System</p>
                    ");
        });
    }
}
