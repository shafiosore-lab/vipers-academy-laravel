{{-- Player Styles Partial --}}
@push('styles')
<style>
    .player-detail-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 2rem 1rem;
    }

    .player-header {
        margin-bottom: 2rem;
    }

    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: #6b7280;
        text-decoration: none;
        font-weight: 500;
        transition: color 0.3s ease;
    }

    .back-link:hover {
        color: #3b82f6;
    }

    /* Main Player Card */
    .player-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 1.5rem;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        margin-bottom: 2rem;
        overflow: hidden;
    }

    .player-card-content {
        display: grid;
        grid-template-columns: 300px 1fr 400px;
        gap: 2rem;
        padding: 2.5rem;
        align-items: center;
    }

    .player-image-wrapper {
        position: relative;
        display: flex;
        justify-content: center;
    }

    .player-image,
    .player-placeholder {
        width: 240px;
        height: 240px;
        border-radius: 1rem;
        object-fit: cover;
        border: 5px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
    }

    .player-placeholder {
        background: rgba(255, 255, 255, 0.15);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 5rem;
        color: white;
        font-weight: 700;
    }

    .player-number {
        position: absolute;
        bottom: -10px;
        right: 20px;
        background: white;
        color: #667eea;
        font-size: 2rem;
        font-weight: 700;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }

    .player-info {
        color: white;
    }

    .player-name {
        font-size: 3rem;
        font-weight: 700;
        margin: 0 0 0.5rem 0;
        color: white;
        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
    }

    .player-position {
        font-size: 1.5rem;
        text-transform: uppercase;
        letter-spacing: 0.15em;
        margin: 0 0 2rem 0;
        opacity: 0.9;
        font-weight: 600;
    }

    .player-quick-stats {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .quick-stat {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 1.1rem;
        opacity: 0.95;
    }

    .quick-stat i {
        width: 24px;
        text-align: center;
    }

    .player-radar {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        border-radius: 1rem;
        padding: 1.5rem;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .radar-title {
        color: white;
        font-size: 1.25rem;
        font-weight: 600;
        margin: 0 0 1rem 0;
        text-align: center;
    }

    /* Tabs */
    .player-tabs-container {
        background: white;
        border-radius: 1.5rem;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .tabs-navigation {
        display: flex;
        border-bottom: 2px solid #e5e7eb;
        background: #f9fafb;
    }

    .tab-button {
        flex: 1;
        padding: 1.25rem 2rem;
        background: none;
        border: none;
        font-size: 1rem;
        font-weight: 600;
        color: #6b7280;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        position: relative;
    }

    .tab-button:hover {
        color: #667eea;
        background: rgba(102, 126, 234, 0.05);
    }

    .tab-button.active {
        color: #667eea;
        background: white;
    }

    .tab-button.active::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, #667eea, #764ba2);
    }

    .tabs-content {
        padding: 2.5rem;
    }

    .tab-pane {
        display: none;
    }

    .tab-pane.active {
        display: block;
    }

    /* Content Card */
    .content-card {
        background: #fafafa;
        border-radius: 0.75rem;
        padding: 2rem;
        border: 1px solid #e0e0e0;
    }

    /* AI Insights */
    .insight-section {
        margin-bottom: 1.5rem;
    }

    .insight-section:last-child {
        margin-bottom: 0;
    }

    .insight-section h3 {
        font-size: 1.1rem;
        font-weight: 600;
        color: #667eea;
        margin: 0 0 0.75rem 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .insight-section p {
        color: #333;
        line-height: 1.7;
        margin: 0;
    }

    /* Biography */
    .professional-bio {
        max-width: none;
    }

    .bio-header {
        text-align: center;
        margin-bottom: 2rem;
        padding-bottom: 1.5rem;
        border-bottom: 2px solid #e1e5e9;
    }

    .player-professional-name {
        font-size: 2rem;
        font-weight: 700;
        color: #667eea;
        margin: 0 0 0.5rem 0;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .player-professional-title {
        font-size: 1.1rem;
        color: #6b7280;
        margin: 0;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .bio-section {
        margin-bottom: 2rem;
    }

    .bio-section:last-child {
        margin-bottom: 0;
    }

    .section-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #667eea;
        margin: 0 0 1rem 0;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #e1e5e9;
        display: inline-block;
    }

    .summary-content p {
        color: #374151;
        line-height: 1.8;
        margin: 0;
        font-size: 1.05rem;
        text-align: justify;
    }

    .profile-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1rem;
    }

    .profile-item {
        background: #f8fafc;
        padding: 1rem;
        border-radius: 0.5rem;
        border-left: 4px solid #667eea;
        font-size: 0.95rem;
        line-height: 1.5;
    }

    .profile-item strong {
        color: #667eea;
        font-weight: 600;
    }

    .highlights-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .highlights-list li {
        position: relative;
        padding: 0.75rem 0 0.75rem 2rem;
        color: #374151;
        line-height: 1.6;
        border-bottom: 1px solid #f1f5f9;
    }

    .highlights-list li:last-child {
        border-bottom: none;
    }

    .highlights-list li:before {
        content: '✓';
        position: absolute;
        left: 0;
        top: 0.75rem;
        color: #667eea;
        font-weight: bold;
        font-size: 1.1rem;
    }

    /* Statistics */
    .stats-compact {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .stat-item {
        background: white;
        border-radius: 0.75rem;
        padding: 1.25rem;
        text-align: center;
        border: 2px solid #667eea;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.5rem;
    }

    .stat-icon {
        font-size: 2rem;
    }

    .stat-info {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .stat-value {
        font-size: 1.75rem;
        font-weight: 700;
        color: #667eea;
        line-height: 1;
    }

    .stat-label {
        font-size: 0.85rem;
        color: #666;
        font-weight: 500;
    }

    .performance-chart {
        background: white;
        border-radius: 0.75rem;
        padding: 1.5rem;
        border: 1px solid #e0e0e0;
    }

    .performance-chart h4 {
        font-size: 1.1rem;
        font-weight: 600;
        color: #1f2937;
        margin: 0 0 1rem 0;
    }

    /* Mobile Responsive */
    @media (max-width: 1024px) {
        .player-card-content {
            grid-template-columns: 1fr;
            gap: 1.5rem;
            padding: 1.5rem;
        }

        .player-radar {
            max-width: 300px;
            margin: 0 auto;
        }
    }

    @media (max-width: 768px) {
        .player-detail-container {
            padding: 1rem 0.5rem;
        }

        .tabs-navigation {
            flex-direction: column;
        }

        .tab-button {
            justify-content: flex-start;
            padding: 0.875rem 1rem;
        }

        .tab-button span {
            display: inline;
        }

        .tabs-content {
            padding: 1.25rem;
        }

        .content-card {
            padding: 1.25rem;
        }

        .player-name {
            font-size: 2rem;
        }

        .player-position {
            font-size: 1.1rem;
        }

        .player-quick-stats {
            font-size: 0.9rem;
            gap: 1rem;
        }

        .stats-compact {
            grid-template-columns: repeat(2, 1fr);
        }

        .player-image,
        .player-placeholder {
            width: 180px;
            height: 180px;
        }
    }

    @media (max-width: 480px) {
        .player-card-content {
            padding: 1rem;
        }

        .player-image,
        .player-placeholder {
            width: 160px;
            height: 160px;
        }

        .player-number {
            font-size: 1.25rem;
            padding: 0.3rem 0.6rem;
        }

        .player-name {
            font-size: 1.75rem;
        }

        .tabs-content {
            padding: 1rem;
        }

        .content-card {
            padding: 1rem;
        }

        .stats-compact {
            grid-template-columns: 1fr;
            gap: 0.75rem;
        }

        .stat-item {
            padding: 1rem;
        }

        .insight-section h3 {
            font-size: 1rem;
        }

        .insight-section p {
            font-size: 0.95rem;
        }
    }
</style>
@endpush
