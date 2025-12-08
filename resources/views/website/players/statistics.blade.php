@extends('layouts.academy')

@section('content')
<style>
    :root {
        --primary-color: #ea1c4d;
        --primary-gradient: linear-gradient(135deg, #ea1c4d 0%, #f05a7a 100%);
        --secondary-color: #65c16e;
        --text-dark: #1e293b;
        --text-gray: #64748b;
        --text-medium: #374151;
        --bg-gradient: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 50%, #e2e8f0 100%);
        --card-bg: rgba(255, 255, 255, 0.95);
        --border-radius: 20px;
        --transition: all 0.3s ease;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    .vipers-player-container {
        font-family: 'Poppins', sans-serif;
        background: var(--bg-gradient);
        color: var(--text-dark);
        padding: 40px 20px;
        min-height: 100vh;
    }

    .player-profile {
        max-width: 1150px;
        margin: auto;
    }

    /* Player Header Card */
    .player-header {
        display: grid;
        grid-template-columns: 280px 1fr;
        gap: 30px;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        padding: 30px;
        border-radius: var(--border-radius);
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(234, 28, 77, 0.2);
        margin-bottom: 30px;
    }

    /* Photo */
    .player-photo {
        width: 280px;
        height: 320px;
        object-fit: cover;
        border-radius: var(--border-radius);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .player-photo-placeholder {
        background: linear-gradient(135deg, #1a3a1a 0%, #0a1f0a 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 60px;
        font-weight: bold;
        color: var(--secondary-color);
    }

    /* Info Section */
    .player-info {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        min-height: 320px;
    }

    .player-details {
        flex: 1;
    }

    .player-name {
        font-size: 42px;
        margin-bottom: 10px;
        font-weight: 700;
        background: var(--primary-gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .player-position {
        font-size: 20px;
        color: var(--primary-color);
        margin-bottom: 15px;
        font-weight: 600;
    }

    .player-description {
        color: var(--text-gray);
        font-size: 16px;
        line-height: 1.6;
    }

    /* Radar Chart */
    .player-radar-container {
        width: 100%;
        max-width: 200px;
        height: 200px;
        margin-top: 20px;
        align-self: flex-end;
    }

    .player-radar-container canvas {
        width: 100% !important;
        height: 100% !important;
    }

    /* Navigation */
    .section-nav {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
        margin-bottom: 30px;
    }

    .nav-link {
        padding: 12px 30px;
        background: var(--card-bg);
        border-radius: 10px;
        text-decoration: none;
        border: 2px solid transparent;
        transition: var(--transition);
        font-weight: 600;
        color: var(--text-gray);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .nav-link.active {
        border-color: var(--primary-color);
        color: var(--primary-color);
        background: rgba(234, 28, 77, 0.05);
    }

    .nav-link:hover {
        border-color: var(--primary-color);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(234, 28, 77, 0.2);
    }

    /* Content Section */
    .content-section {
        background: var(--card-bg);
        border-radius: var(--border-radius);
        padding: 30px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(234, 28, 77, 0.2);
    }

    /* Statistics Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-item {
        background: var(--card-bg);
        padding: 20px;
        border-radius: 15px;
        text-align: center;
        border: 1px solid rgba(234, 28, 77, 0.2);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        transition: var(--transition);
    }

    .stat-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(234, 28, 77, 0.15);
    }

    .stat-value {
        font-size: 32px;
        font-weight: bold;
        color: var(--primary-color);
        margin-bottom: 5px;
    }

    .stat-label {
        font-size: 14px;
        color: var(--text-gray);
        font-weight: 500;
    }

    /* Additional Info */
    .additional-info {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
    }

    .info-item {
        background: var(--card-bg);
        padding: 15px 20px;
        border-radius: 10px;
        border: 1px solid rgba(234, 28, 77, 0.2);
        font-size: 16px;
        color: var(--text-medium);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .vipers-player-container {
            padding: 20px 15px;
        }

        .player-header {
            grid-template-columns: 1fr;
            padding: 25px;
            gap: 25px;
        }

        .player-photo {
            width: 100%;
            max-width: 280px;
            height: 280px;
            margin: 0 auto;
        }

        .player-info {
            min-height: auto;
        }

        .player-radar-container {
            max-width: 180px;
            height: 180px;
            margin: 20px auto 0;
            align-self: center;
        }

        .player-name {
            font-size: 32px;
            text-align: center;
        }

        .player-position {
            font-size: 18px;
            text-align: center;
        }

        .player-description {
            text-align: center;
        }

        .section-nav {
            justify-content: center;
            gap: 10px;
        }

        .nav-link {
            padding: 10px 20px;
            font-size: 14px;
        }

        .content-section {
            padding: 25px 20px;
        }

        .stats-grid {
            grid-template-columns: repeat(auto-fit, minmax(130px, 1fr));
            gap: 15px;
        }

        .stat-value {
            font-size: 28px;
        }

        .additional-info {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 480px) {
        .vipers-player-container {
            padding: 15px 10px;
        }

        .player-header {
            padding: 20px;
            gap: 20px;
        }

        .player-photo {
            max-width: 240px;
            height: 240px;
        }

        .player-radar-container {
            max-width: 150px;
            height: 150px;
            margin-top: 15px;
        }

        .player-name {
            font-size: 26px;
        }

        .player-position {
            font-size: 16px;
        }

        .player-description {
            font-size: 14px;
        }

        .section-nav {
            gap: 8px;
        }

        .nav-link {
            padding: 8px 16px;
            font-size: 13px;
            flex: 1;
            min-width: 100px;
            text-align: center;
        }

        .content-section {
            padding: 20px 15px;
        }

        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }

        .stat-item {
            padding: 15px 10px;
        }

        .stat-value {
            font-size: 24px;
        }

        .stat-label {
            font-size: 12px;
        }

        .info-item {
            padding: 12px 15px;
            font-size: 14px;
        }
    }
</style>

<div class="vipers-player-container">
    <div class="player-profile">
        @include('website.players.partials.shared-header', ['player' => $player])

        <!-- Navigation -->
        <div class="section-nav">
            <a href="{{ route('players.biography', $player->id) }}" class="nav-link">Biography</a>
            <a href="{{ route('players.statistics', $player->id) }}" class="nav-link active">Statistics</a>
            <a href="{{ route('players.ai-insights', $player->id) }}" class="nav-link">AI Insights</a>
        </div>

        <!-- Content Section -->
        <div class="content-section">
            @include('website.players.partials.statistics', ['player' => $player])
        </div>
    </div>
</div>

<script>
(function() {
    const script = document.createElement('script');
    script.src = 'https://cdn.jsdelivr.net/npm/chart.js';
    script.onload = function() {
        initCharts();
    };
    script.onerror = function() {
        console.warn('Chart.js failed to load. Charts will not be displayed.');
    };
    document.head.appendChild(script);
})();

function initCharts() {
    // AI Extraction Toggle
    document.getElementById('use_ai_extraction')?.addEventListener('change', function() {
        const container = document.getElementById('game_summary_container');
        if (container) {
            container.style.display = this.checked ? 'block' : 'none';
        }
    });

    // Player stats selector (for switching between players)
    const playerStatsSelect = document.getElementById('player-stats-select');
    if (playerStatsSelect) {
        playerStatsSelect.addEventListener('change', function() {
            const selectedUrl = this.value;
            if (selectedUrl) {
                window.location.href = selectedUrl;
            }
        });
    }

    // Form Submission Handler
    document.getElementById('statsForm')?.addEventListener('submit', function(e) {
        e.preventDefault();

        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Saving...';
        submitBtn.disabled = true;

        // Get form data
        const formData = new FormData(this);
        const selectedPlayerId = document.getElementById('player_select').value;

        if (!selectedPlayerId) {
            showErrorMessage('Please select a player.');
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
            return;
        }

        // Update form action to use selected player ID
        const baseUrl = window.location.origin;
        const submitUrl = `${baseUrl}/players/${selectedPlayerId}/record-stats`;

        // Submit via AJAX
        fetch(submitUrl, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update statistics on the page for the selected player
                updatePlayerStatistics(data.player);
                showSuccessMessage(`Game statistics recorded successfully for ${data.player.first_name} ${data.player.last_name}!`);

                // Reset form fields
                this.reset();
                // Reset date to today
                document.getElementById('game_date').valueAsDate = new Date();
                // Hide AI summary container
                document.getElementById('game_summary_container').style.display = 'none';
            } else {
                showErrorMessage(data.message || 'An error occurred while saving statistics.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showErrorMessage('An error occurred while saving statistics.');
        })
        .finally(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        });
    });
}

// Utility functions for messages and updates
function showSuccessMessage(message) {
    // Create and show success message
    const alertDiv = document.createElement('div');
    alertDiv.className = 'alert alert-success alert-dismissible fade show position-fixed';
    alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    alertDiv.innerHTML = `
        <i class="fas fa-check-circle me-2"></i>${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    document.body.appendChild(alertDiv);

    // Auto remove after 5 seconds
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 5000);
}

function showErrorMessage(message) {
    // Create and show error message
    const alertDiv = document.createElement('div');
    alertDiv.className = 'alert alert-danger alert-dismissible fade show position-fixed';
    alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    alertDiv.innerHTML = `
        <i class="fas fa-exclamation-triangle me-2"></i>${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    document.body.appendChild(alertDiv);

    // Auto remove after 5 seconds
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 5000);
}

function updatePlayerStatistics(playerData) {
    // Update metric cards
    const goalsCard = document.querySelector('.metric-card:nth-child(1) .metric-value');
    const assistsCard = document.querySelector('.metric-card:nth-child(2) .metric-value');
    const appearancesCard = document.querySelector('.metric-card:nth-child(3) .metric-value');

    if (goalsCard) goalsCard.textContent = playerData.goals || 0;
    if (assistsCard) assistsCard.textContent = playerData.assists || 0;
    if (appearancesCard) appearancesCard.textContent = playerData.appearances || 0;

    // Update statistics list
    const statsList = document.querySelector('.extra-stats ul');
    if (statsList) {
        const goalsLi = statsList.querySelector('li:nth-child(1) strong');
        const assistsLi = statsList.querySelector('li:nth-child(2) strong');
        const appearancesLi = statsList.querySelector('li:nth-child(3) strong');
        const yellowCardsLi = statsList.querySelector('li:nth-child(4) strong');
        const redCardsLi = statsList.querySelector('li:nth-child(5) strong');

        if (goalsLi) goalsLi.nextSibling.textContent = playerData.goals || 0;
        if (assistsLi) assistsLi.nextSibling.textContent = playerData.assists || 0;
        if (appearancesLi) appearancesLi.nextSibling.textContent = playerData.appearances || 0;
        if (yellowCardsLi) yellowCardsLi.nextSibling.textContent = playerData.yellow_cards || 0;
        if (redCardsLi) redCardsLi.nextSibling.textContent = playerData.red_cards || 0;
    }
}
</script>
@endsection
