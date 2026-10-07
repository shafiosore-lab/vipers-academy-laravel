<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>@yield('title', $reportTitle ?? 'Report')</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 11pt;
            line-height: 1.5;
            color: #333;
        }

        .letterhead-header {
            margin-bottom: 20px;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-logo {
            width: 90px;
            vertical-align: top;
        }

        .header-logo img {
            max-width: 80px;
            max-height: 70px;
        }

        .header-info {
            vertical-align: top;
            padding-left: 12px;
        }

        .org-name {
            font-size: 16pt;
            font-weight: bold;
            color: {{ $letterhead->primary_color ?? '#ea1c4d' }};
            margin-bottom: 3px;
        }

        .org-contact {
            font-size: 9pt;
            color: #555;
        }

        .org-contact span {
            margin-right: 10px;
        }

        .header-divider {
            border: none;
            border-top: 2px solid {{ $letterhead->primary_color ?? '#ea1c4d' }};
            margin-top: 12px;
            margin-bottom: 0;
        }

        .report-title-section {
            margin-top: 20px;
            margin-bottom: 15px;
            text-align: center;
        }

        .report-title {
            font-size: 14pt;
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }

        .report-date {
            font-size: 10pt;
            color: #666;
        }

        .report-content {
            margin-top: 20px;
        }

        .letterhead-footer {
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            position: fixed;
            bottom: 0;
            width: 100%;
        }

        .footer-table {
            width: 100%;
            border-collapse: collapse;
        }

        .footer-text {
            font-size: 8pt;
            color: #999;
        }

        .page-number {
            font-size: 8pt;
            color: #999;
            text-align: right;
        }

        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0.04;
            z-index: -1;
            pointer-events: none;
        }

        .watermark img {
            max-width: 350px;
        }

        @page {
            margin: 25mm 20mm 20mm 20mm;
        }

        @yield('extra-styles')
    </style>
</head>
<body>
    <!-- Watermark -->
    @if($letterhead && $letterhead->show_watermark)
    @if($letterheadOrganization && $letterheadOrganization->logo)
    <div class="watermark">
        <img src="{{ public_path('storage/' . $letterheadOrganization->logo) }}" alt="Watermark">
    </div>
    @endif
    @endif

    <!-- Header -->
    <div class="letterhead-header">
        <table class="header-table">
            <tr>
                @if($letterheadOrganization && $letterheadOrganization->logo)
                <td class="header-logo">
                    <img src="{{ public_path('storage/' . $letterheadOrganization->logo) }}" alt="Logo">
                </td>
                @endif
                <td class="header-info">
                    <div class="org-name">{{ $letterheadOrganization->name ?? 'Organization' }}</div>
                    <div class="org-contact">
                        @if($letterheadOrganization && $letterheadOrganization->phone)
                        <span>📞 {{ $letterheadOrganization->phone }}</span>
                        @endif
                        @if($letterheadOrganization && $letterheadOrganization->email)
                        <span>✉️ {{ $letterheadOrganization->email }}</span>
                        @endif
                        @if($letterheadOrganization && $letterheadOrganization->website)
                        <span>🌐 {{ $letterheadOrganization->website }}</span>
                        @endif
                    </div>
                    @if($letterheadOrganization && $letterheadOrganization->address)
                    <div class="org-contact" style="margin-top: 2px;">
                        <span>📍 {{ $letterheadOrganization->address }}</span>
                    </div>
                    @endif
                </td>
            </tr>
        </table>
        <hr class="header-divider">
    </div>

    <!-- Report Title -->
    @if(!empty($reportTitle))
    <div class="report-title-section">
        <div class="report-title">{{ $reportTitle }}</div>
        @if(!empty($reportDate))
        <div class="report-date">{{ $reportDate }}</div>
        @endif
    </div>
    @endif

    <!-- Report Content -->
    <div class="report-content">
        @yield('content')
    </div>

    <!-- Footer -->
    <div class="letterhead-footer">
        <table class="footer-table">
            <tr>
                <td class="footer-text">
                    {{ $letterhead ? $letterhead->getFooterText() : 'Generated via Mumias Vipers Sports Management SaaS Platform' }}
                </td>
                @if($letterhead && $letterhead->show_page_numbers)
                <td class="page-number">
                    Page {{ $page ?? 1 }} of {{ $totalPages ?? 1 }}
                </td>
                @endif
            </tr>
        </table>
    </div>
</body>
</html>
