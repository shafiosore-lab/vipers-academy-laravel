<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weekly Attendance Report</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #ea1c4d;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .logo-section {
            text-align: right;
        }

        .logo {
            max-width: 120px;
            height: auto;
        }

        .company-info {
            text-align: left;
            margin-top: 10px;
        }

        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #ea1c4d;
            margin-bottom: 5px;
        }

        .company-details {
            font-size: 10px;
            line-height: 1.3;
        }

        .report-title {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin: 20px 0;
            color: #ea1c4d;
        }

        .report-info {
            margin-bottom: 20px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }

        .info-label {
            font-weight: bold;
            width: 120px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 10px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #ea1c4d;
        }

        tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 10px;
            color: #666;
        }

        .summary {
            margin-top: 20px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }

        .summary h4 {
            margin: 0 0 10px 0;
            color: #ea1c4d;
        }

        .status-badges {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }

        .badge {
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
        }

        .badge-success { background-color: #d4edda; color: #155724; }
        .badge-warning { background-color: #fff3cd; color: #856404; }
        .badge-danger { background-color: #f8d7da; color: #721c24; }
        .badge-info { background-color: #d1ecf1; color: #0c5460; }
    </style>
</head>
<body>
    <!-- Header with Logo and Company Info -->
    <div class="header">
        <div class="company-info">
            <div class="company-name">Mumias Vipers Football Academy</div>
            <div class="company-details">
                <strong>Address:</strong> Mumias, Kenya<br>
                <strong>Website:</strong> www.mumiasvipers.com<br>
                <strong>Email:</strong> info@mumiasvipers.com<br>
                <strong>Phone:</strong> +254 XXX XXX XXX
            </div>
        </div>
        <div class="logo-section">
            <img src="{{ public_path('assets/img/logo/vps.jpeg') }}" alt="Mumias Vipers Logo" class="logo">
        </div>
    </div>

    <!-- Report Title -->
    <h1 class="report-title">Weekly Attendance Report</h1>

    <!-- Report Information -->
    <div class="report-info">
        <div class="info-row">
            <span class="info-label">Report Period:</span>
            <span>{{ $startDate->format('M j, Y') }} - {{ $endDate->format('M j, Y') }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Generated On:</span>
            <span>{{ now()->format('M j, Y H:i') }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Total Records:</span>
            <span>{{ $attendances->count() }}</span>
        </div>
    </div>

    <!-- Attendance Table -->
    <table>
        <thead>
            <tr>
                <th>Player Name</th>
                <th>Session Type</th>
                <th>Session Date</th>
                <th>Check In</th>
                <th>Check Out</th>
                <th>Duration</th>
                <th>Status</th>
                <th>Recorded By</th>
            </tr>
        </thead>
        <tbody>
            @foreach($attendances as $attendance)
            <tr>
                <td>{{ $attendance->player->full_name }}</td>
                <td>{{ ucfirst($attendance->session_type) }}</td>
                <td>{{ $attendance->session_date->format('M j, Y') }}</td>
                <td>{{ $attendance->check_in_time ? $attendance->check_in_time->format('H:i') : 'N/A' }}</td>
                <td>{{ $attendance->check_out_time ? $attendance->check_out_time->format('H:i') : 'N/A' }}</td>
                <td>{{ $attendance->total_duration_minutes ? $attendance->total_duration_minutes . ' min' : 'N/A' }}</td>
                <td>
                    @if($attendance->check_out_time)
                        <span class="badge badge-success">Completed</span>
                    @elseif($attendance->check_in_time)
                        <span class="badge badge-info">In Progress</span>
                    @else
                        <span class="badge badge-warning">Scheduled</span>
                    @endif
                </td>
                <td>{{ $attendance->recorder->name ?? 'System' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Summary Section -->
    <div class="summary">
        <h4>Attendance Summary</h4>
        <div class="info-row">
            <span class="info-label">Total Players:</span>
            <span>{{ $attendances->unique('player_id')->count() }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Completed Sessions:</span>
            <span>{{ $attendances->whereNotNull('check_out_time')->count() }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">In Progress:</span>
            <span>{{ $attendances->whereNotNull('check_in_time')->whereNull('check_out_time')->count() }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Scheduled:</span>
            <span>{{ $attendances->whereNull('check_in_time')->count() }}</span>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>This report was automatically generated by the Mumias Vipers Academy management system.</p>
        <p>For any questions or concerns, please contact the academy administration.</p>
    </div>
</body>
</html>
