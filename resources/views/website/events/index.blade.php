@extends('layouts.academy')

@section('title', 'Events - Vipers Academy Kenya')
@section('meta_description', 'Upcoming events, tournaments, and activities at Vipers Academy Kenya.')

@section('content')

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events - Vipers Academy</title>
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

        .events-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 30px;
            margin-bottom: 50px;
        }

        .event-card {
            background: white;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            overflow: hidden;
            transition: all 0.2s ease;
        }

        .event-card:hover {
            border-color: #ea1c4d;
            box-shadow: 0 4px 12px rgba(234, 28, 77, 0.1);
        }

        .event-header {
            background: #ea1c4d;
            color: white;
            padding: 20px;
            text-align: center;
        }

        .event-date {
            font-size: 1.2em;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .event-title {
            font-size: 1.1em;
            font-weight: 600;
        }

        .event-content {
            padding: 25px;
        }

        .event-description {
            font-size: 0.95em;
            color: #6b7280;
            line-height: 1.5;
            margin-bottom: 15px;
        }

        .event-details {
            display: flex;
            justify-content: space-between;
            font-size: 0.9em;
            color: #6b7280;
        }

        .event-detail {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .upcoming-events {
            background: white;
            padding: 50px 30px;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            text-align: center;
        }

        .upcoming-events h2 {
            font-size: 1.8em;
            color: #1a1a1a;
            margin-bottom: 30px;
            font-weight: 600;
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
            margin-top: 20px;
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
            <h1>Upcoming Events</h1>
            <p>Stay updated with our latest tournaments, training camps, and academy events.</p>
        </div>

        <!-- Events Grid -->
        <div class="events-grid">
            <div class="event-card">
                <div class="event-header">
                    <div class="event-date">March 15, 2025</div>
                    <div class="event-title">Youth Tournament</div>
                </div>
                <div class="event-content">
                    <div class="event-description">
                        Annual youth football tournament featuring teams from across Kenya.
                    </div>
                    <div class="event-details">
                        <div class="event-detail">📍 Nairobi</div>
                        <div class="event-detail">⏰ 9:00 AM</div>
                    </div>
                </div>
            </div>

            <div class="event-card">
                <div class="event-header">
                    <div class="event-date">April 5, 2025</div>
                    <div class="event-title">Training Camp</div>
                </div>
                <div class="event-content">
                    <div class="event-description">
                        Intensive training camp for advanced players focusing on technical skills.
                    </div>
                    <div class="event-details">
                        <div class="event-detail">📍 Academy Grounds</div>
                        <div class="event-detail">⏰ 8:00 AM</div>
                    </div>
                </div>
            </div>

            <div class="event-card">
                <div class="event-header">
                    <div class="event-date">May 20, 2025</div>
                    <div class="event-title">Open Day</div>
                </div>
                <div class="event-content">
                    <div class="event-description">
                        Annual open day showcasing academy facilities and player demonstrations.
                    </div>
                    <div class="event-details">
                        <div class="event-detail">📍 Main Stadium</div>
                        <div class="event-detail">⏰ 10:00 AM</div>
                    </div>
                </div>
            </div>

            <div class="event-card">
                <div class="event-header">
                    <div class="event-date">June 10, 2025</div>
                    <div class="event-title">Parent Workshop</div>
                </div>
                <div class="event-content">
                    <div class="event-description">
                        Educational workshop for parents on player development and academy programs.
                    </div>
                    <div class="event-details">
                        <div class="event-detail">📍 Conference Room</div>
                        <div class="event-detail">⏰ 2:00 PM</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Upcoming Events Section -->
        <div class="upcoming-events">
            <h2>More Events Coming Soon</h2>
            <p>Stay tuned for more exciting events and tournaments throughout the year.</p>
            <a href="/contact" class="cta-button">Get Event Updates</a>
        </div>
    </div>
</body>
</html>
@endsection
