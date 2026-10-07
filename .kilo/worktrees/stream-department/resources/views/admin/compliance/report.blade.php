<!DOCTYPE html>
<html>
<head>
    <title>FIFA Compliance Report - {{ date('Y-m-d') }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .header { text-align: center; margin-bottom: 30px; }
        .stats { display: flex; flex-wrap: wrap; margin-bottom: 30px; }
        .stat-box {
            flex: 1;
            min-width: 200px;
            padding: 15px;
            margin: 5px;
            border: 1px solid #ddd;
            border-radius: 5px;
            text-align: center;
        }
        .stat-value { font-size: 24px; font-weight: bold; }
        .stat-label { color: #666; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        th { background-color: #f5f5f5; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Vipers Academy - FIFA Compliance Report</h1>
        <p>Generated on: {{ date('Y-m-d H:i:s') }}</p>
    </div>

    <div class="stats">
        <div class="stat-box">
            <div class="stat-value">{{ $totalPlayers }}</div>
            <div class="stat-label">Total Players</div>
        </div>
        <div class="stat-box">
            <div class="stat-value">{{ $fifaRegistered }}</div>
            <div class="stat-label">FIFA Registered</div>
        </div>
        <div class="stat-box">
            <div class="stat-value">{{ $safeguardingCompliant }}</div>
            <div class="stat-label">Safeguarding Compliant</div>
        </div>
        <div class="stat-box">
            <div class="stat-value">{{ $medicalCertificates }}</div>
            <div class="stat-label">Medical Certificates</div>
        </div>
        <div class="stat-box">
            <div class="stat-value">{{ $guardianConsents }}</div>
            <div class="stat-label">Guardian Consents</div>
        </div>
    </div>

    <h2>Recent Registrations</h2>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Category</th>
                <th>Registration Status</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @foreach($recentRegistrations as $player)
            <tr>
                <td>{{ $player->first_name }} {{ $player->last_name }}</td>
                <td>{{ $player->category ?? 'N/A' }}</td>
                <td>{{ $player->registration_status ?? 'N/A' }}</td>
                <td>{{ $player->created_at->format('Y-m-d') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
