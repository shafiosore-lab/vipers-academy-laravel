<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>FIFA Compliance Report - Vipers Academy</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 20px;
        }

        .header {
            text-align: center;
            border-bottom: 3px solid #1e40af;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .logo-section {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
        }

        .logo {
            width: 80px;
            height: 80px;
            background: #1e40af;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 36px;
            font-weight: bold;
            margin-right: 20px;
        }

        .header-text h1 {
            color: #1e40af;
            font-size: 28px;
            margin: 0;
            font-weight: bold;
        }

        .header-text p {
            color: #666;
            margin: 5px 0;
            font-size: 14px;
        }

        .report-info {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 30px;
            border-left: 4px solid #1e40af;
        }

        .stats-grid {
            display: table;
            width: 100%;
            margin-bottom: 30px;
        }

        .stat-card {
            display: table-cell;
            width: 25%;
            padding: 15px;
            text-align: center;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            margin: 0 5px;
        }

        .stat-number {
            font-size: 32px;
            font-weight: bold;
            color: #1e40af;
            display: block;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .compliance-section {
            margin-bottom: 30px;
        }

        .section-title {
            font-size: 18px;
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 15px;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 5px;
        }

        .compliance-grid {
            display: table;
            width: 100%;
        }

        .compliance-item {
            display: table-cell;
            width: 50%;
            padding: 10px;
            vertical-align: top;
        }

        .compliance-card {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 15px;
            height: 100px;
        }

        .compliance-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            margin-bottom: 10px;
        }

        .icon-success { background: #dcfce7; color: #166534; }
        .icon-warning { background: #fef3c7; color: #92400e; }
        .icon-info { background: #dbeafe; color: #1e40af; }

        .compliance-title {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .compliance-value {
            font-size: 24px;
            font-weight: bold;
            color: #1e40af;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .table th,
        .table td {
            padding: 8px 12px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }

        .table th {
            background: #f8f9fa;
            font-weight: bold;
            color: #374151;
        }

        .status-badge {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-active { background: #dcfce7; color: #166534; }
        .status-pending { background: #fef3c7; color: #92400e; }
        .status-inactive { background: #fee2e2; color: #991b1b; }

        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #e2e8f0;
            text-align: center;
            color: #666;
            font-size: 10px;
        }

        .signature-section {
            margin-top: 40px;
            display: table;
            width: 100%;
        }

        .signature-box {
            display: table-cell;
            width: 50%;
            padding: 20px;
            text-align: center;
        }

        .signature-line {
            border-bottom: 1px solid #333;
            width: 200px;
            margin: 40px auto 10px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="logo-section">
            <div class="logo">⚽</div>
            <div class="header-text">
                <h1>Vipers Academy</h1>
                <p>FIFA Compliance Report</p>
                <p>Generated on {{ date('F j, Y \a\t g:i A') }}</p>
            </div>
        </div>
    </div>

    <!-- Report Info -->
    <div class="report-info">
        <strong>Report Period:</strong> Current Academy Status<br>
        <strong>Generated By:</strong> {{ Auth::user()->name }}<br>
        <strong>Academy Location:</strong> Nairobi, Kenya<br>
        <strong>FIFA Affiliation:</strong> Licensed Youth Development Academy
    </div>

    <!-- Key Statistics -->
    <div class="stats-grid">
        <div class="stat-card">
            <span class="stat-number">{{ $totalPlayers }}</span>
            <span class="stat-label">Total Players</span>
        </div>
        <div class="stat-card">
            <span class="stat-number">{{ $fifaRegistered }}</span>
            <span class="stat-label">FIFA Registered</span>
        </div>
        <div class="stat-card">
            <span class="stat-number">{{ $safeguardingCompliant }}</span>
            <span class="stat-label">Safeguarding Compliant</span>
        </div>
        <div class="stat-card">
            <span class="stat-number">{{ $medicalCertificates }}</span>
            <span class="stat-label">Medical Certificates</span>
        </div>
    </div>

    <!-- FIFA Compliance Status -->
    <div class="compliance-section">
        <h2 class="section-title">📋 FIFA Compliance Status</h2>

        <div class="compliance-grid">
            <div class="compliance-item">
                <div class="compliance-card">
                    <div class="compliance-icon icon-success">✓</div>
                    <div class="compliance-title">FIFA Registration</div>
                    <div class="compliance-value">{{ $fifaRegistered }}/{{ $totalPlayers }}</div>
                    <small>{{ number_format(($fifaRegistered / max($totalPlayers, 1)) * 100, 1) }}% compliance rate</small>
                </div>
            </div>

            <div class="compliance-item">
                <div class="compliance-card">
                    <div class="compliance-icon icon-info">🛡️</div>
                    <div class="compliance-title">Safeguarding Policy</div>
                    <div class="compliance-value">{{ $safeguardingCompliant }}/{{ $totalPlayers }}</div>
                    <small>{{ number_format(($safeguardingCompliant / max($totalPlayers, 1)) * 100, 1) }}% compliance rate</small>
                </div>
            </div>

            <div class="compliance-item">
                <div class="compliance-card">
                    <div class="compliance-icon icon-warning">🏥</div>
                    <div class="compliance-title">Medical Certificates</div>
                    <div class="compliance-value">{{ $medicalCertificates }}/{{ $totalPlayers }}</div>
                    <small>{{ number_format(($medicalCertificates / max($totalPlayers, 1)) * 100, 1) }}% compliance rate</small>
                </div>
            </div>

            <div class="compliance-item">
                <div class="compliance-card">
                    <div class="compliance-icon icon-success">📝</div>
                    <div class="compliance-title">Guardian Consents</div>
                    <div class="compliance-value">{{ $guardianConsents }}/{{ $totalPlayers }}</div>
                    <small>{{ number_format(($guardianConsents / max($totalPlayers, 1)) * 100, 1) }}% compliance rate</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Age Group Distribution -->
    <div class="compliance-section">
        <h2 class="section-title">👥 Age Group Distribution</h2>

        <div class="compliance-grid">
            <div class="compliance-item">
                <div class="compliance-card">
                    <div class="compliance-icon icon-info">🎓</div>
                    <div class="compliance-title">Youth Players (U-8 to U-18)</div>
                    <div class="compliance-value">{{ $youthPlayers }}</div>
                    <small>{{ number_format(($youthPlayers / max($totalPlayers, 1)) * 100, 1) }}% of total players</small>
                </div>
            </div>

            <div class="compliance-item">
                <div class="compliance-card">
                    <div class="compliance-icon icon-success">👨</div>
                    <div class="compliance-title">Senior Players</div>
                    <div class="compliance-value">{{ $seniorPlayers }}</div>
                    <small>{{ number_format(($seniorPlayers / max($totalPlayers, 1)) * 100, 1) }}% of total players</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Registrations -->
    <div class="compliance-section">
        <h2 class="section-title">📅 Recent Player Registrations</h2>

        <table class="table">
            <thead>
                <tr>
                    <th>Player Name</th>
                    <th>Age Group</th>
                    <th>Registration Date</th>
                    <th>FIFA Status</th>
                    <th>Safeguarding</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentRegistrations as $player)
                <tr>
                    <td>{{ $player->first_name }} {{ $player->last_name }}</td>
                    <td>{{ $player->age_group ?? 'N/A' }}</td>
                    <td>{{ $player->created_at->format('M j, Y') }}</td>
                    <td>
                        @if($player->fifa_registration_number)
                            <span class="status-badge status-active">Registered</span>
                        @else
                            <span class="status-badge status-pending">Pending</span>
                        @endif
                    </td>
                    <td>
                        @if($player->safeguarding_policy_acknowledged)
                            <span class="status-badge status-active">Compliant</span>
                        @else
                            <span class="status-badge status-inactive">Pending</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align: center; color: #666;">No recent registrations found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Compliance Summary -->
    <div class="compliance-section">
        <h2 class="section-title">📊 Compliance Summary</h2>

        <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; border-left: 4px solid #1e40af;">
            <h3 style="margin-top: 0; color: #1e40af;">Overall Compliance Status</h3>

            @php
                $overallCompliance = (
                    ($fifaRegistered / max($totalPlayers, 1)) * 25 +
                    ($safeguardingCompliant / max($totalPlayers, 1)) * 25 +
                    ($medicalCertificates / max($totalPlayers, 1)) * 25 +
                    ($guardianConsents / max($totalPlayers, 1)) * 25
                );
            @endphp

            <div style="margin: 20px 0;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                    <span>Overall Compliance Score</span>
                    <strong>{{ number_format($overallCompliance, 1) }}%</strong>
                </div>
                <div style="width: 100%; height: 20px; background: #e2e8f0; border-radius: 10px;">
                    <div style="width: {{ $overallCompliance }}%; height: 100%; background: linear-gradient(90deg, #1e40af, #3b82f6); border-radius: 10px;"></div>
                </div>
            </div>

            <div style="display: table; width: 100%; margin-top: 20px;">
                <div style="display: table-cell; width: 50%; padding: 10px;">
                    <h4 style="margin: 0 0 10px 0; color: #059669;">✅ Strengths</h4>
                    <ul style="margin: 0; padding-left: 20px;">
                        @if($fifaRegistered > 0)
                            <li>{{ $fifaRegistered }} players FIFA registered</li>
                        @endif
                        @if($safeguardingCompliant > 0)
                            <li>{{ $safeguardingCompliant }} players safeguarding compliant</li>
                        @endif
                        @if($medicalCertificates > 0)
                            <li>{{ $medicalCertificates }} medical certificates on file</li>
                        @endif
                    </ul>
                </div>
                <div style="display: table-cell; width: 50%; padding: 10px;">
                    <h4 style="margin: 0 0 10px 0; color: #dc2626;">⚠️ Areas for Improvement</h4>
                    <ul style="margin: 0; padding-left: 20px;">
                        @if($fifaRegistered < $totalPlayers)
                            <li>{{ $totalPlayers - $fifaRegistered }} players need FIFA registration</li>
                        @endif
                        @if($safeguardingCompliant < $totalPlayers)
                            <li>{{ $totalPlayers - $safeguardingCompliant }} players need safeguarding acknowledgment</li>
                        @endif
                        @if($medicalCertificates < $totalPlayers)
                            <li>{{ $totalPlayers - $medicalCertificates }} players need medical certificates</li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Signatures -->
    <div class="signature-section">
        <div class="signature-box">
            <div class="signature-line"></div>
            <strong>{{ Auth::user()->name }}</strong><br>
            <small>Academy Administrator</small><br>
            <small>Date: {{ date('M j, Y') }}</small>
        </div>
        <div class="signature-box">
            <div class="signature-line"></div>
            <strong>FIFA Compliance Officer</strong><br>
            <small>Authorized Signature</small><br>
            <small>Date: {{ date('M j, Y') }}</small>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>This report is generated automatically by the Vipers Academy Management System.<br>
        For questions or concerns regarding compliance, please contact the academy administration.<br>
        <strong>Confidential Document - FIFA Compliance Standards Apply</strong></p>
    </div>
</body>
</html>
