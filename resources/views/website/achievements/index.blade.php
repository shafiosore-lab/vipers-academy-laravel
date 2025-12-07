@extends('layouts.academy')

@section('title', 'Achievements - Vipers Academy Kenya')
@section('meta_description', 'Celebrating the achievements and successes of Vipers Academy players and programs.')

@section('content')

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Achievements - Vipers Academy</title>
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

        .achievements-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-bottom: 50px;
        }

        .achievement-card {
            background: white;
            padding: 30px;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            transition: all 0.2s ease;
        }

        .achievement-card:hover {
            border-color: #ea1c4d;
            box-shadow: 0 4px 12px rgba(234, 28, 77, 0.1);
        }

        .achievement-icon {
            font-size: 2.5em;
            margin-bottom: 15px;
        }

        .achievement-card h3 {
            font-size: 1.3em;
            color: #1a1a1a;
            margin-bottom: 10px;
            font-weight: 600;
        }

        .achievement-card p {
            font-size: 0.95em;
            color: #6b7280;
            line-height: 1.5;
        }

        .stats-section {
            background: white;
            padding: 50px 30px;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            text-align: center;
        }

        .stats-section h2 {
            font-size: 1.8em;
            color: #1a1a1a;
            margin-bottom: 30px;
            font-weight: 600;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 20px;
            max-width: 800px;
            margin: 0 auto;
        }

        .stat-card {
            background: #ea1c4d;
            padding: 25px 15px;
            border-radius: 10px;
            color: white;
            transition: all 0.2s ease;
        }

        .stat-card:hover {
            background: #c0173f;
            transform: translateY(-2px);
        }

        .stat-number {
            font-size: 2em;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 0.9em;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Hero Section -->
        <div class="hero">
            <h1>Our Achievements</h1>
            <p>Celebrating the milestones and successes that define Vipers Academy's journey in developing elite football talent.</p>
        </div>

        <!-- Achievements Grid -->
        <div class="achievements-grid">
            <div class="achievement-card">
                <div class="achievement-icon">🏆</div>
                <h3>Professional Graduates</h3>
                <p>Over 50 players have progressed to professional football clubs and national team selections.</p>
            </div>
            <div class="achievement-card">
                <div class="achievement-icon">🌍</div>
                <h3>International Recognition</h3>
                <p>Players representing Kenya at various international youth tournaments and competitions.</p>
            </div>
            <div class="achievement-card">
                <div class="achievement-icon">📚</div>
                <h3>Academic Excellence</h3>
                <p>Maintaining high academic standards while pursuing football excellence.</p>
            </div>
            <div class="achievement-card">
                <div class="achievement-icon">⚽</div>
                <h3>Tournament Success</h3>
                <p>Multiple championship wins in regional and national youth competitions.</p>
            </div>
            <div class="achievement-card">
                <div class="achievement-icon">🤝</div>
                <h3>Community Impact</h3>
                <p>Contributing to grassroots football development and community programs.</p>
            </div>
            <div class="achievement-card">
                <div class="achievement-icon">🎓</div>
                <h3>Player Development</h3>
                <p>Comprehensive development programs focusing on technical, tactical, and mental skills.</p>
            </div>
        </div>

        <!-- Stats Section -->
        <div class="stats-section">
            <h2>By the Numbers</h2>
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number">500+</div>
                    <div class="stat-label">Players Trained</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">50+</div>
                    <div class="stat-label">Pro Graduates</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">10+</div>
                    <div class="stat-label">Years Experience</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">15+</div>
                    <div class="stat-label">Tournaments Won</div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
@endsection
