@extends('layouts.academy')

@section('title', 'Announcements - Vipers Academy Kenya')
@section('meta_description', 'Latest announcements, news, and important updates from Vipers Academy Kenya.')

@section('content')

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Announcements - Vipers Academy</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background: #f8f9fa;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .hero {
            text-align: center;
            padding: 40px 20px 30px;
            margin-bottom: 50px;
        }

        .hero h1 {
            font-size: 2.2em;
            color: #1a1a1a;
            margin-bottom: 12px;
            font-weight: 700;
        }

        .hero p {
            font-size: 1.05em;
            color: #666;
            max-width: 700px;
            margin: 0 auto;
            line-height: 1.6;
        }

        .announcements-section {
            background: white;
            padding: 50px 30px;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            margin-bottom: 50px;
        }

        .announcements-section h2 {
            font-size: 1.8em;
            color: #1a1a1a;
            margin-bottom: 30px;
            text-align: center;
            font-weight: 600;
        }

        .announcements-list {
            max-width: 900px;
            margin: 0 auto;
        }

        .announcement-item {
            border-left: 3px solid #ea1c4d;
            background: #fef2f2;
            padding: 25px;
            margin-bottom: 20px;
            border-radius: 0 8px 8px 0;
            transition: all 0.2s ease;
        }

        .announcement-item:hover {
            background: #fce7e7;
            transform: translateX(5px);
        }

        .announcement-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .announcement-title {
            font-size: 1.2em;
            font-weight: 600;
            color: #1a1a1a;
        }

        .announcement-date {
            font-size: 0.9em;
            color: #ea1c4d;
            font-weight: 600;
        }

        .announcement-content {
            font-size: 0.95em;
            color: #6b7280;
            line-height: 1.5;
        }

        .important-announcement {
            background: linear-gradient(135deg, #ea1c4d, #c0173f);
            color: white;
            border-left-color: #ea1c4d;
        }

        .important-announcement .announcement-title,
        .important-announcement .announcement-date {
            color: white;
        }

        .important-announcement .announcement-content {
            color: #ffe6e6;
        }

        .categories-section {
            background: white;
            padding: 50px 30px;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            margin-bottom: 50px;
        }

        .categories-section h2 {
            font-size: 1.8em;
            color: #1a1a1a;
            margin-bottom: 30px;
            text-align: center;
            font-weight: 600;
        }

        .categories-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            max-width: 800px;
            margin: 0 auto;
        }

        .category-card {
            background: #ea1c4d;
            padding: 25px 15px;
            border-radius: 10px;
            text-align: center;
            color: white;
            transition: all 0.2s ease;
        }

        .category-card:hover {
            background: #c0173f;
            transform: translateY(-2px);
        }

        .category-card h3 {
            font-size: 1.1em;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .category-card p {
            font-size: 0.9em;
            opacity: 0.9;
        }

        .newsletter-section {
            background: white;
            padding: 50px 30px;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            text-align: center;
        }

        .newsletter-section h2 {
            font-size: 1.8em;
            color: #1a1a1a;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .newsletter-section p {
            font-size: 1em;
            color: #6b7280;
            margin-bottom: 30px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .cta-button {
            display: inline-block;
            padding: 12px 32px;
            background: #ea1c4d;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.95em;
            transition: all 0.2s ease;
        }

        .cta-button:hover {
            background: #c0173f;
            transform: translateY(-1px);
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Hero Section -->
        <div class="hero">
            <h1>Announcements</h1>
            <p>Stay updated with the latest news, important updates, and announcements from Vipers Academy.</p>
        </div>

        <!-- Announcements Section -->
        <div class="announcements-section">
            <h2>Latest Announcements</h2>
            <div class="announcements-list">
                <div class="announcement-item important-announcement">
                    <div class="announcement-header">
                        <div class="announcement-title">Registration Now Open for 2025 Season</div>
                        <div class="announcement-date">December 1, 2024</div>
                    </div>
                    <div class="announcement-content">
                        We are excited to announce that registration for the 2025 season is now open! Limited spots available for all age groups. Early registration discounts apply until January 31st.
                    </div>
                </div>

                <div class="announcement-item">
                    <div class="announcement-header">
                        <div class="announcement-title">New Training Facilities Opening</div>
                        <div class="announcement-date">November 15, 2024</div>
                    </div>
                    <div class="announcement-content">
                        Our new state-of-the-art training facilities will be officially opening on December 15th. All members are invited to attend the opening ceremony and facility tour.
                    </div>
                </div>

                <div class="announcement-item">
                    <div class="announcement-header">
                        <div class="announcement-title">Holiday Training Schedule</div>
                        <div class="announcement-date">November 10, 2024</div>
                    </div>
                    <div class="announcement-content">
                        During the holiday season, we will be running special training camps from December 20th to January 5th. Separate registration required for holiday programs.
                    </div>
                </div>

                <div class="announcement-item">
                    <div class="announcement-header">
                        <div class="announcement-title">Coach Certification Program</div>
                        <div class="announcement-date">October 25, 2024</div>
                    </div>
                    <div class="announcement-content">
                        We are launching a comprehensive coach certification program. Applications are now being accepted for our first cohort starting in January 2025.
                    </div>
                </div>

                <div class="announcement-item">
                    <div class="announcement-header">
                        <div class="announcement-title">Annual General Meeting</div>
                        <div class="announcement-date">October 1, 2024</div>
                    </div>
                    <div class="announcement-content">
                        Our Annual General Meeting will be held on November 30th at the main academy grounds. All parents and guardians are welcome to attend.
                    </div>
                </div>
            </div>
        </div>

        <!-- Categories Section -->
        <div class="categories-section">
            <h2>Announcement Categories</h2>
            <div class="categories-grid">
                <div class="category-card">
                    <h3>Registration</h3>
                    <p>Program enrollment and deadlines</p>
                </div>
                <div class="category-card">
                    <h3>Events</h3>
                    <p>Tournaments and special events</p>
                </div>
                <div class="category-card">
                    <h3>Training</h3>
                    <p>Schedule and program updates</p>
                </div>
                <div class="category-card">
                    <h3>General</h3>
                    <p>Important academy news</p>
                </div>
            </div>
        </div>

        <!-- Newsletter Section -->
        <div class="newsletter-section">
            <h2>Stay Updated</h2>
            <p>Subscribe to our newsletter to receive the latest announcements and updates directly in your inbox.</p>
            <a href="/news" class="cta-button">Subscribe to Newsletter</a>
        </div>
    </div>
</body>
</html>
@endsection
