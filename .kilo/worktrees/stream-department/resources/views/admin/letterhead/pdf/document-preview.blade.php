<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $document->title }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 12pt;
            line-height: 1.6;
            color: #333;
        }

        .letterhead-header {
            margin-bottom: 25px;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-logo {
            width: 100px;
            vertical-align: top;
        }

        .header-logo img {
            max-width: 90px;
            max-height: 80px;
        }

        .header-info {
            vertical-align: top;
            padding-left: 15px;
        }

        .org-name {
            font-size: 18pt;
            font-weight: bold;
            color: {{ $letterhead->primary_color ?? '#ea1c4d' }};
            margin-bottom: 5px;
        }

        .org-tagline {
            font-size: 10pt;
            color: #666;
            font-style: italic;
            margin-bottom: 8px;
        }

        .org-contact {
            font-size: 9pt;
            color: #555;
        }

        .org-contact span {
            margin-right: 15px;
        }

        .header-divider {
            border: none;
            border-top: 2px solid {{ $letterhead->primary_color ?? '#ea1c4d' }};
            margin-top: 15px;
            margin-bottom: 0;
        }

        .document-content {
            margin-top: 30px;
            min-height: 600px;
        }

        .document-title {
            font-size: 16pt;
            font-weight: bold;
            color: #333;
            margin-bottom: 20px;
            text-align: center;
        }

        .letterhead-footer {
            margin-top: 40px;
            padding-top: 15px;
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
            opacity: 0.05;
            z-index: -1;
            pointer-events: none;
        }

        .watermark img {
            max-width: 400px;
        }

        @page {
            margin: 30mm 25mm 25mm 25mm;
        }
    </style>
</head>
<body>
    <!-- Watermark -->
    @if($letterhead && $letterhead->show_watermark)
    <div class="watermark">
        @if($organization->logo)
        <img src="{{ public_path('storage/' . $organization->logo) }}" alt="Watermark">
        @endif
    </div>
    @endif

    <!-- Header -->
    <div class="letterhead-header">
        <table class="header-table">
            <tr>
                @if($organization->logo)
                <td class="header-logo">
                    <img src="{{ public_path('storage/' . $organization->logo) }}" alt="Logo">
                </td>
                @endif
                <td class="header-info">
                    <div class="org-name">{{ $organization->name }}</div>
                    @if($organization->description)
                    <div class="org-tagline">{{ $organization->description }}</div>
                    @endif
                    <div class="org-contact">
                        @if($organization->phone)
                        <span>📞 {{ $organization->phone }}</span>
                        @endif
                        @if($organization->email)
                        <span>✉️ {{ $organization->email }}</span>
                        @endif
                        @if($organization->website)
                        <span>🌐 {{ $organization->website }}</span>
                        @endif
                    </div>
                    @if($organization->address)
                    <div class="org-contact" style="margin-top: 3px;">
                        <span>📍 {{ $organization->address }}</span>
                    </div>
                    @endif
                </td>
            </tr>
        </table>
        <hr class="header-divider">
    </div>

    <!-- Document Content -->
    <div class="document-content">
        <div class="document-title">{{ $document->title }}</div>
        {!! $document->content !!}
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
                    Page {{ $page ?? 1 }} of {{ $document->page_count }}
                </td>
                @endif
            </tr>
        </table>
    </div>
</body>
</html>
