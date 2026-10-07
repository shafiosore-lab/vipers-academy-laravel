@extends('layouts.academy')

@section('title', $player->name . ' - Statistics - Vipers Academy')

@section('content')
@include('website.players.partials.shared-styles')

<style>
    /* Statistics-specific styles - Compact & Small */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 12px;
        margin-bottom: 20px;
    }

    .stat-item {
        background: var(--card-bg);
        padding: 12px;
        border-radius: 10px;
        text-align: center;
        border: 1px solid rgba(234, 28, 77, 0.2);
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
        transition: var(--transition);
    }

    .stat-item:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 16px rgba(234, 28, 77, 0.12);
    }

    .stat-value {
        font-size: 24px;
        font-weight: bold;
        color: var(--primary-color);
        margin-bottom: 4px;
        line-height: 1;
    }

    .stat-label {
        font-size: 12px;
        color: var(--text-gray);
        font-weight: 500;
        line-height: 1.2;
    }

    .additional-info {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
        gap: 10px;
    }

    .info-item {
        background: var(--card-bg);
        padding: 10px 14px;
        border-radius: 8px;
        border: 1px solid rgba(234, 28, 77, 0.2);
        font-size: 14px;
        color: var(--text-medium);
    }

    /* Desktop - More columns for compact look */
    @media (min-width: 1024px) {
        .stats-grid {
            grid-template-columns: repeat(5, 1fr);
            gap: 14px;
        }

        .stat-item {
            padding: 14px;
        }

        .stat-value {
            font-size: 26px;
        }

        .stat-label {
            font-size: 13px;
        }
    }

    /* Tablet */
    @media (min-width: 769px) and (max-width: 1023px) {
        .stats-grid {
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
        }

        .stat-item {
            padding: 12px;
        }

        .stat-value {
            font-size: 22px;
        }
    }

    /* Mobile */
    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
        }

        .stat-item {
            padding: 10px 8px;
        }

        .stat-value {
            font-size: 20px;
        }

        .stat-label {
            font-size: 11px;
        }

        .additional-info {
            grid-template-columns: 1fr;
            gap: 8px;
        }

        .info-item {
            padding: 10px 12px;
            font-size: 13px;
        }
    }

    /* Small Mobile */
    @media (max-width: 480px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 8px;
        }

        .stat-item {
            padding: 8px 6px;
        }

        .stat-value {
            font-size: 18px;
        }

        .stat-label {
            font-size: 10px;
        }

        .info-item {
            padding: 8px 10px;
            font-size: 12px;
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
