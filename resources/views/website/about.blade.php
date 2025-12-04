@extends('layouts.academy')

@section('title', 'About Us - Vipers Academy Kenya')
@section('meta_description', 'Learn about Vipers Academy, founded in 2017, building the next generation of elite Kenyan footballers through modern training, discipline, and development.')

@section('content')

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Vipers Academy</title>
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

        /* Hero Section */
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

        /* Stats Section */
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 20px;
            margin-bottom: 50px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .stat-card {
            background: white;
            padding: 25px 15px;
            border-radius: 12px;
            text-align: center;
            border: 1px solid #e5e7eb;
            transition: all 0.2s ease;
        }

        .stat-card:hover {
            border-color: #ea1c4d;
            box-shadow: 0 4px 12px rgba(234, 28, 77, 0.1);
        }

        .stat-number {
            font-size: 2em;
            font-weight: 700;
            color: #ea1c4d;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 0.9em;
            color: #6b7280;
            font-weight: 500;
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
            margin-top: 15px;
        }

        .cta-button:hover {
            background: #c0173f;
            transform: translateY(-1px);
        }

        /* Who We Are Section */
        .who-we-are {
            background: white;
            padding: 50px 30px;
            border-radius: 12px;
            margin-bottom: 50px;
            border: 1px solid #e5e7eb;
        }

        .who-we-are h2 {
            font-size: 1.8em;
            color: #1a1a1a;
            margin-bottom: 20px;
            text-align: center;
            font-weight: 600;
        }

        .who-we-are p {
            font-size: 1em;
            color: #6b7280;
            margin-bottom: 35px;
            text-align: center;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }

        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }

        .feature-card {
            background: #fef2f2;
            padding: 25px;
            border-radius: 10px;
            border-left: 3px solid #ea1c4d;
            transition: all 0.2s ease;
        }

        .feature-card:hover {
            background: #fce7e7;
            transform: translateX(5px);
        }

        .feature-icon {
            font-size: 1.8em;
            margin-bottom: 12px;
        }

        .feature-card h3 {
            font-size: 1.1em;
            color: #1a1a1a;
            margin-bottom: 8px;
            font-weight: 600;
        }

        .feature-card p {
            font-size: 0.95em;
            color: #6b7280;
            text-align: left;
            line-height: 1.5;
        }

        /* Timeline Section */
        .timeline-section {
            background: white;
            padding: 50px 30px;
            border-radius: 12px;
            margin-bottom: 50px;
            border: 1px solid #e5e7eb;
        }

        .timeline-section h2 {
            font-size: 1.8em;
            color: #1a1a1a;
            margin-bottom: 40px;
            text-align: center;
            font-weight: 600;
        }

        .timeline {
            position: relative;
            padding-left: 40px;
            max-width: 700px;
            margin: 0 auto;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 12px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #e5e7eb;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 30px;
            padding-left: 25px;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -35px;
            top: 3px;
            width: 14px;
            height: 14px;
            border-radius: 50%;
            background: #ea1c4d;
            border: 2px solid white;
            box-shadow: 0 0 0 2px #ea1c4d;
        }

        .timeline-year {
            font-size: 1.3em;
            font-weight: 700;
            color: #ea1c4d;
            margin-bottom: 6px;
        }

        .timeline-content {
            font-size: 0.95em;
            color: #6b7280;
            line-height: 1.5;
        }

        /* Values Section */
        .values-section {
            background: white;
            padding: 50px 30px;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
        }

        .values-section h2 {
            font-size: 1.8em;
            color: #1a1a1a;
            margin-bottom: 30px;
            text-align: center;
            font-weight: 600;
        }

        .values-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 15px;
            max-width: 700px;
            margin: 0 auto;
        }

        .value-card {
            background: #ea1c4d;
            padding: 25px 15px;
            border-radius: 10px;
            text-align: center;
            color: white;
            transition: all 0.2s ease;
        }

        .value-card:hover {
            background: #c0173f;
            transform: translateY(-2px);
        }

        .value-card h3 {
            font-size: 1.1em;
            font-weight: 600;
        }

        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2.2em;
            }

            .hero p {
                font-size: 1.1em;
            }

            .stat-number {
                font-size: 2.2em;
            }

            .timeline {
                padding-left: 30px;
            }

            .timeline-item {
                padding-left: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Hero Section -->
        <div class="hero">
            <h1>Built for Champions</h1>
            <p>Founded in 2017, Vipers Academy is shaping the future of Kenyan football through elite coaching, discipline, and a development-first approach.</p>

            <div class="stats">
                <div class="stat-card">
                    <div class="stat-number">2017</div>
                    <div class="stat-label">Year Founded</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">500+</div>
                    <div class="stat-label">Players Trained</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">50+</div>
                    <div class="stat-label">Pro Graduates</div>
                </div>
            </div>

            <a href="#" class="cta-button">Join Vipers Academy</a>
        </div>

        <!-- Who We Are Section -->
        <div class="who-we-are">
            <h2>Who We Are</h2>
            <p>Vipers Academy is a modern football development institution committed to raising disciplined, skilled, and mentally strong players. We integrate structured training, character building, sports science principles, and academic balance to prepare young athletes for elite performance.</p>

            <div class="features">
                <div class="feature-card">
                    <div class="feature-icon">⚽</div>
                    <h3>Elite Player Development</h3>
                    <p>European-inspired technical, tactical, physical, and mental training.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">📘</div>
                    <h3>Discipline & Character</h3>
                    <p>Respect, leadership, teamwork, and emotional intelligence embedded in every session.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🎓</div>
                    <h3>Education Friendly</h3>
                    <p>Training designed to complement school life and responsible academic focus.</p>
                </div>
            </div>
        </div>

        <!-- Timeline Section -->
        <div class="timeline-section">
            <h2>Our Journey Since 2017</h2>
            <div class="timeline">
                <div class="timeline-item">
                    <div class="timeline-year">2017</div>
                    <div class="timeline-content">Vipers Academy officially founded with the goal of transforming grassroots football.</div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-year">2019</div>
                    <div class="timeline-content">Expanded to multiple training centers and introduced structured academy programs.</div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-year">2021</div>
                    <div class="timeline-content">Developed the first batch of players progressing to national and club levels.</div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-year">2023</div>
                    <div class="timeline-content">Launched junior development, holiday camps, and coaching education programs.</div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-year">2024+</div>
                    <div class="timeline-content">Moving toward a fully equipped academy, sports science integration, and global partnerships.</div>
                </div>
            </div>
        </div>

        <!-- Values Section -->
        <div class="values-section">
            <h2>Our Values</h2>
            <div class="values-grid">
                <div class="value-card">
                    <h3>Discipline</h3>
                </div>
                <div class="value-card">
                    <h3>Respect</h3>
                </div>
                <div class="value-card">
                    <h3>Hard Work</h3>
                </div>
                <div class="value-card">
                    <h3>Excellence</h3>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
@endsection
