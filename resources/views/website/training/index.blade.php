@extends('layouts.academy')

@section('title', 'Training Updates - Vipers Academy Kenya')
@section('meta_description', 'Latest training updates, schedules, and program information from Vipers Academy.')

@section('content')

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Training Updates - Vipers Academy</title>
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

        .updates-section {
            background: white;
            padding: 50px 30px;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            margin-bottom: 50px;
        }

        .updates-section h2 {
            font-size: 1.8em;
            color: #1a1a1a;
            margin-bottom: 30px;
            text-align: center;
            font-weight: 600;
        }

        .updates-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }

        .update-card {
            background: #fef2f2;
            padding: 25px;
            border-radius: 10px;
            border-left: 3px solid #ea1c4d;
            transition: all 0.2s ease;
        }

        .update-card:hover {
            background: #fce7e7;
            transform: translateX(5px);
        }

        .update-date {
            font-size: 0.9em;
            color: #ea1c4d;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .update-card h3 {
            font-size: 1.1em;
            color: #1a1a1a;
            margin-bottom: 10px;
            font-weight: 600;
        }

        .update-card p {
            font-size: 0.95em;
            color: #6b7280;
            line-height: 1.5;
        }

        .schedule-section {
            background: white;
            padding: 50px 30px;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            margin-bottom: 50px;
        }

        .schedule-section h2 {
            font-size: 1.8em;
            color: #1a1a1a;
            margin-bottom: 30px;
            text-align: center;
            font-weight: 600;
        }

        .schedule-table {
            max-width: 800px;
            margin: 0 auto;
            border-collapse: collapse;
            width: 100%;
        }

        .schedule-table th,
        .schedule-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }

        .schedule-table th {
            background: #ea1c4d;
            color: white;
            font-weight: 600;
        }

        .schedule-table tr:hover {
            background: #f9f9f9;
        }

        .contact-section {
            background: white;
            padding: 50px 30px;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            text-align: center;
        }

        .contact-section h2 {
            font-size: 1.8em;
            color: #1a1a1a;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .contact-section p {
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
            <h1>Training Updates</h1>
            <p>Stay informed about our training programs, schedules, and latest developments.</p>
        </div>

        <!-- Updates Section -->
        <div class="updates-section">
            <h2>Latest Updates</h2>
            <div class="updates-grid">
                <div class="update-card">
                    <div class="update-date">December 2024</div>
                    <h3>New Training Facilities</h3>
                    <p>Exciting news! Our new state-of-the-art training facilities are now operational, featuring modern equipment and expanded space for all age groups.</p>
                </div>
                <div class="update-card">
                    <div class="update-date">November 2024</div>
                    <h3>Winter Training Program</h3>
                    <p>Our winter intensive training program is now accepting registrations. Limited spots available for advanced technical training sessions.</p>
                </div>
                <div class="update-card">
                    <div class="update-date">October 2024</div>
                    <h3>Youth Development Focus</h3>
                    <p>Enhanced focus on youth development with specialized training programs for players aged 8-12, emphasizing fundamental skills and character building.</p>
                </div>
                <div class="update-card">
                    <div class="update-date">September 2024</div>
                    <h3>Coach Certification Program</h3>
                    <p>Launching our coach certification program to ensure all our training staff maintain the highest standards of coaching excellence.</p>
                </div>
            </div>
        </div>

        <!-- Schedule Section -->
        <div class="schedule-section">
            <h2>Weekly Training Schedule</h2>
            <table class="schedule-table">
                <thead>
                    <tr>
                        <th>Day</th>
                        <th>Time</th>
                        <th>Program</th>
                        <th>Age Group</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Monday</td>
                        <td>4:00 PM - 6:00 PM</td>
                        <td>Technical Training</td>
                        <td>U13 - U17</td>
                    </tr>
                    <tr>
                        <td>Tuesday</td>
                        <td>5:00 PM - 7:00 PM</td>
                        <td>Goalkeeper Training</td>
                        <td>All Ages</td>
                    </tr>
                    <tr>
                        <td>Wednesday</td>
                        <td>4:00 PM - 6:00 PM</td>
                        <td>Tactical Sessions</td>
                        <td>U15 - Senior</td>
                    </tr>
                    <tr>
                        <td>Thursday</td>
                        <td>4:00 PM - 6:00 PM</td>
                        <td>Physical Conditioning</td>
                        <td>All Ages</td>
                    </tr>
                    <tr>
                        <td>Friday</td>
                        <td>5:00 PM - 7:00 PM</td>
                        <td>Match Practice</td>
                        <td>U13 - Senior</td>
                    </tr>
                    <tr>
                        <td>Saturday</td>
                        <td>9:00 AM - 11:00 AM</td>
                        <td>Youth Development</td>
                        <td>U8 - U12</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Contact Section -->
        <div class="contact-section">
            <h2>Questions About Training?</h2>
            <p>Contact our training department for more information about programs, schedules, or enrollment.</p>
            <a href="/contact" class="cta-button">Contact Us</a>
        </div>
    </div>
</body>
</html>
@endsection
