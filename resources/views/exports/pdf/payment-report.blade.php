<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: #333;
        }

        /* Header Section */
        .header {
            padding: 20px 0;
            border-bottom: 2px solid #1a5f7a;
            margin-bottom: 20px;
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .company-info {
            flex: 1;
        }

        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #1a5f7a;
            margin-bottom: 5px;
        }

        .company-address {
            font-size: 11px;
            color: #666;
            margin-bottom: 3px;
        }

        .logo-section {
            text-align: right;
        }

        .logo {
            max-width: 150px;
            max-height: 80px;
        }

        .report-title {
            font-size: 18px;
            font-weight: bold;
            color: #333;
            margin: 20px 0 10px 0;
            text-align: center;
        }

        .report-meta {
            font-size: 11px;
            color: #666;
            text-align: center;
            margin-bottom: 20px;
        }

        /* Summary Box */
        .summary-box {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 20px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }

        .summary-label {
            font-weight: bold;
            color: #555;
        }

        .summary-value {
            color: #1a5f7a;
            font-weight: bold;
        }

        /* Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th {
            background-color: #1a5f7a;
            color: white;
            padding: 10px 8px;
            text-align: left;
            font-weight: bold;
            font-size: 11px;
            text-transform: uppercase;
        }

        td {
            padding: 8px;
            border-bottom: 1px solid #dee2e6;
            font-size: 11px;
        }

        tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        tr:hover {
            background-color: #e9ecef;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        /* Status Badges */
        .status-completed {
            color: #28a745;
            font-weight: bold;
        }

        .status-pending {
            color: #ffc107;
            font-weight: bold;
        }

        .status-failed {
            color: #dc3545;
            font-weight: bold;
        }

        /* Footer */
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 15px 0;
            border-top: 1px solid #dee2e6;
            background-color: #f8f9fa;
        }

        .footer-content {
            display: flex;
            justify-content: space-between;
            font-size: 10px;
            color: #666;
        }

        .footer-separator {
            height: 1px;
            background-color: #dee2e6;
            margin-bottom: 15px;
        }

        /* Page Numbers */
        .page-number {
            text-align: center;
        }

        /* Signature Section */
        .signature-section {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
        }

        .signature-box {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
        }

        .signature-line {
            width: 200px;
            border-bottom: 1px solid #333;
            padding-bottom: 5px;
            margin-bottom: 5px;
        }

        .signature-label {
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <!-- Header with Company Branding -->
    <div class="header">
        <div class="header-content">
            <div class="company-info">
                <div class="company-name">
                    {{ $company?->company_name ?? 'Mumias Vipers Academy' }}
                </div>
                @if($company?->company_address)
                <div class="company-address">{{ $company->company_address }}</div>
                @endif
                @if($company?->company_phone)
                <div class="company-address">Phone: {{ $company->company_phone }}</div>
                @endif
                @if($company?->company_email)
                <div class="company-address">Email: {{ $company->company_email }}</div>
                @endif
            </div>
            <div class="logo-section">
                @if($company?->logo_path && file_exists(public_path($company->logo_path)))
                <img src="{{ public_path($company->logo_path) }}" alt="Company Logo" class="logo">
                @else
                <img src="{{ public_path('assets/img/logo.png') }}" alt="Company Logo" class="logo">
                @endif
            </div>
        </div>
    </div>

    <!-- Report Title -->
    <div class="report-title">{{ $title }}</div>
    <div class="report-meta">Generated on: {{ $generatedAt }}</div>

    <!-- Summary -->
    @if(isset($data['total_amount']))
    <div class="summary-box">
        <div class="summary-row">
            <span class="summary-label">Total Records:</span>
            <span class="summary-value">{{ number_format($data['record_count'] ?? 0) }}</span>
        </div>
        <div class="summary-row">
            <span class="summary-label">Total Amount:</span>
            <span class="summary-value">KES {{ number_format($data['total_amount'], 2) }}</span>
        </div>
    </div>
    @endif

    <!-- Data Table -->
    @if(!empty($data['headers']) && !empty($data['rows']))
    <table>
        <thead>
            <tr>
                @foreach($data['headers'] as $header)
                <th>{{ $header }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($data['rows'] as $row)
            <tr>
                @foreach($row as $index => $cell)
                <td class="{{ in_array($index, ['amount', 'total']) ? 'text-right' : '' }}">
                    @if(is_numeric($cell) && $index > 0 && $index < count($row) - 1)
                    {{ $cell }}
                    @else
                    {{ $cell }}
                    @endif
                </td>
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <p style="text-align: center; padding: 40px;">No data available for export.</p>
    @endif

    <!-- Signature Section -->
    <div class="signature-section">
        <div class="signature-box">
            <div>
                <div class="signature-line"></div>
                <div class="signature-label">Authorized Signature</div>
            </div>
            <div>
                <div class="signature-line"></div>
                <div class="signature-label">Date</div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    @if($company?->pdf_footer_enabled)
    <div class="footer-separator"></div>
    <div class="footer">
        <div class="footer-content">
            <div>
                @if($company?->company_website)
                <span>Website: {{ $company->company_website }}</span>
                @endif
            </div>
            <div class="page-number">
                Page {{ $page ?? 1 }} of {{ $pages ?? 1 }}
            </div>
            <div>
                @if($company?->company_email)
                <span>Email: {{ $company->company_email }}</span>
                @endif
                @if($company?->company_phone)
                <span> | Phone: {{ $company->company_phone }}</span>
                @endif
            </div>
        </div>
    </div>
    @endif
</body>
</html>
