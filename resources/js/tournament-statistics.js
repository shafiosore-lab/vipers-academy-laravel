/**
 * Tournament Statistics Dashboard JavaScript
 * Provides interactive features and real-time updates for the tournament statistics dashboard
 */

class TournamentStatisticsDashboard {
    constructor() {
        this.tournamentId = null;
        this.refreshInterval = null;
        this.isRefreshing = false;
        this.init();
    }

    /**
     * Initialize the dashboard
     */
    init() {
        this.tournamentId = document.querySelector('meta[name="tournament-id"]')?.content;

        // Initialize components
        this.initTabs();
        this.initRealTimeUpdates();
        this.initExportFunctionality();
        this.initSearchFunctionality();
        this.initPrintFunctionality();

        // Add fade-in animation to cards
        this.addFadeInAnimations();

        console.log('Tournament Statistics Dashboard initialized');
    }

    /**
     * Initialize tab functionality
     */
    initTabs() {
        const tabs = document.querySelectorAll('.nav-tabs .nav-link');
        tabs.forEach(tab => {
            tab.addEventListener('click', (e) => {
                e.preventDefault();

                // Remove active class from all tabs
                tabs.forEach(t => t.classList.remove('active'));

                // Add active class to clicked tab
                tab.classList.add('active');

                // Show corresponding content
                const target = tab.getAttribute('data-bs-target');
                document.querySelectorAll('.tab-pane').forEach(pane => {
                    pane.classList.remove('show', 'active');
                });

                if (target) {
                    const content = document.querySelector(target);
                    if (content) {
                        content.classList.add('show', 'active');
                    }
                }
            });
        });
    }

    /**
     * Initialize real-time updates
     */
    initRealTimeUpdates() {
        const refreshButtons = document.querySelectorAll('.btn-outline-primary[title*="Refresh"]');

        refreshButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                this.refreshData(btn);
            });
        });

        // Auto-refresh every 30 seconds
        this.refreshInterval = setInterval(() => {
            this.refreshData();
        }, 30000);
    }

    /**
     * Refresh data from server
     */
    async refreshData(button = null) {
        if (this.isRefreshing) return;

        this.isRefreshing = true;

        if (button) {
            this.setButtonLoading(button, true);
        }

        try {
            const response = await fetch(`/admin/tournaments/${this.tournamentId}/statistics/live`);
            const data = await response.json();

            // Update UI with new data
            this.updateUI(data);

            // Update last updated time
            this.updateLastUpdatedTime();

            console.log('Statistics refreshed successfully');

        } catch (error) {
            console.error('Error refreshing statistics:', error);
            this.showNotification('Error refreshing statistics', 'error');
        } finally {
            this.isRefreshing = false;
            if (button) {
                this.setButtonLoading(button, false);
            }
        }
    }

    /**
     * Update UI with new data
     */
    updateUI(data) {
        // Update summary cards
        this.updateSummaryCards(data.summary);

        // Update top scorers table
        if (data.top_scorers) {
            this.updateTopScorersTable(data.top_scorers);
        }

        // Update team cards table
        if (data.team_cards) {
            this.updateTeamCardsTable(data.team_cards);
        }

        // Update rankings table
        if (data.rankings) {
            this.updateRankingsTable(data.rankings);
        }
    }

    /**
     * Update summary cards
     */
    updateSummaryCards(summary) {
        const cards = {
            'total-teams': summary.total_teams,
            'total-matches': summary.total_matches,
            'total-goals': summary.total_goals,
            'total-cards': summary.total_cards
        };

        Object.entries(cards).forEach(([id, value]) => {
            const element = document.getElementById(id);
            if (element) {
                element.textContent = value;
                this.animateValueChange(element, value);
            }
        });

        // Update progress bar
        const progressBar = document.querySelector('.progress-bar');
        if (progressBar) {
            progressBar.style.width = `${summary.progress_percentage}%`;
            progressBar.textContent = `${summary.progress_percentage}%`;
        }
    }

    /**
     * Update top scorers table
     */
    updateTopScorersTable(topScorers) {
        const tbody = document.querySelector('#top-scorers tbody');
        if (!tbody) return;

        tbody.innerHTML = topScorers.map((player, index) => `
            <tr>
                <td class="text-center">
                    <span class="badge bg-${index < 3 ? 'warning' : 'secondary'}">
                        ${index + 1}
                    </span>
                </td>
                <td>
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm bg-light rounded-circle me-3 d-flex align-items-center justify-content-center">
                            ${player.name.charAt(0)}
                        </div>
                        <div>
                            <div class="fw-bold">${player.name}</div>
                            <small class="text-muted">${player.position}</small>
                        </div>
                    </div>
                </td>
                <td>${player.team_name || 'N/A'}</td>
                <td class="text-center">
                    <span class="badge bg-primary">${player.position}</span>
                </td>
                <td class="text-center fw-bold text-success">
                    ${player.goals_count || 0}
                </td>
                <td class="text-center">
                    ${player.assists_count || 0}
                </td>
                <td class="text-center">
                    ${player.matches_played || 0}
                </td>
                <td class="text-center">
                    ${player.matches_played > 0 ? (player.goals_count / player.matches_played).toFixed(2) : 0}
                </td>
            </tr>
        `).join('');
    }

    /**
     * Update team cards table
     */
    updateTeamCardsTable(teamCards) {
        const tbody = document.querySelector('#team-cards tbody');
        if (!tbody) return;

        tbody.innerHTML = teamCards.map((team, index) => `
            <tr>
                <td class="text-center">
                    <span class="badge bg-${index < 3 ? 'secondary' : 'light'}">
                        ${index + 1}
                    </span>
                </td>
                <td>
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm bg-light rounded-circle me-3 d-flex align-items-center justify-content-center">
                            ${team.team_name.charAt(0)}
                        </div>
                        <div>
                            <div class="fw-bold">${team.team_name}</div>
                            <small class="text-muted">${team.matches_played} matches</small>
                        </div>
                    </div>
                </td>
                <td class="text-center">
                    <span class="badge bg-warning">${team.yellow_cards}</span>
                </td>
                <td class="text-center">
                    <span class="badge bg-danger">${team.red_cards}</span>
                </td>
                <td class="text-center fw-bold">
                    ${team.yellow_cards + team.red_cards}
                </td>
                <td class="text-center">
                    ${team.matches_played}
                </td>
                <td class="text-center">
                    ${team.matches_played > 0 ? ((team.yellow_cards + team.red_cards) / team.matches_played).toFixed(2) : 0}
                </td>
                <td class="text-center">
                    <span class="badge bg-success">
                        ${100 - (team.yellow_cards * 1) - (team.red_cards * 3)}
                    </span>
                </td>
            </tr>
        `).join('');
    }

    /**
     * Update rankings table
     */
    updateRankingsTable(rankings) {
        const tbody = document.querySelector('#rankings tbody');
        if (!tbody) return;

        tbody.innerHTML = rankings.map((standing, index) => `
            <tr>
                <td class="text-center">
                    <span class="badge bg-${standing.position <= 3 ? 'success' : (standing.position <= 6 ? 'warning' : 'secondary')}">
                        ${standing.position}
                    </span>
                </td>
                <td>
                    <div class="d-flex align-items-center">
                        <div class="avatar-lg bg-light rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            ${standing.team.team_name.charAt(0)}
                        </div>
                        <div>
                            <div class="fw-bold">${standing.team.team_name}</div>
                            <small class="text-muted">${standing.team.organization?.name || 'N/A'}</small>
                        </div>
                    </div>
                </td>
                <td class="text-center">${standing.played}</td>
                <td class="text-center">${standing.won}</td>
                <td class="text-center">${standing.drawn}</td>
                <td class="text-center">${standing.lost}</td>
                <td class="text-center">${standing.goals_for}</td>
                <td class="text-center">${standing.goals_against}</td>
                <td class="text-center">
                    <span class="${standing.goal_difference >= 0 ? 'text-success' : 'text-danger'}">
                        ${standing.goal_difference >= 0 ? '+' : ''}${standing.goal_difference}
                    </span>
                </td>
                <td class="text-center fw-bold">${standing.points}</td>
                <td class="text-center">
                    ${this.getFormDisplay(standing.form)}
                </td>
            </tr>
        `).join('');
    }

    /**
     * Get form display HTML
     */
    getFormDisplay(form) {
        if (!form || !Array.isArray(form)) return '';

        return `
            <div class="form-display">
                ${form.map(result => `
                    <div class="form-item ${result === 'W' ? 'win' : result === 'D' ? 'draw' : 'loss'}">
                        ${result}
                    </div>
                `).join('')}
            </div>
        `;
    }

    /**
     * Animate value changes
     */
    animateValueChange(element, newValue) {
        element.style.transform = 'scale(1.1)';
        setTimeout(() => {
            element.style.transform = 'scale(1)';
        }, 200);
    }

    /**
     * Set button loading state
     */
    setButtonLoading(button, isLoading) {
        const icon = button.querySelector('i');
        if (isLoading) {
            button.disabled = true;
            if (icon) {
                icon.classList.remove('fa-sync-alt');
                icon.classList.add('fa-spinner', 'fa-spin');
            }
        } else {
            button.disabled = false;
            if (icon) {
                icon.classList.remove('fa-spinner', 'fa-spin');
                icon.classList.add('fa-sync-alt');
            }
        }
    }

    /**
     * Update last updated time
     */
    updateLastUpdatedTime() {
        const elements = document.querySelectorAll('[id*="last-updated"]');
        elements.forEach(element => {
            const now = new Date();
            element.textContent = `Last updated: ${now.toLocaleString()}`;
        });
    }

    /**
     * Initialize export functionality
     */
    initExportFunctionality() {
        const exportButtons = document.querySelectorAll('.btn-outline-success[title*="Export"]');

        exportButtons.forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                this.showExportModal();
            });
        });
    }

    /**
     * Show export modal
     */
    showExportModal() {
        // Create modal HTML
        const modalHtml = `
            <div class="modal fade show" id="exportModal" style="display: block;" aria-modal="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Export Tournament Statistics</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <button class="btn btn-primary w-100" onclick="dashboard.exportData('pdf')">
                                        <i class="fas fa-file-pdf me-2"></i>PDF
                                    </button>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <button class="btn btn-success w-100" onclick="dashboard.exportData('excel')">
                                        <i class="fas fa-file-excel me-2"></i>Excel
                                    </button>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <button class="btn btn-info w-100" onclick="dashboard.exportData('csv')">
                                        <i class="fas fa-file-csv me-2"></i>CSV
                                    </button>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <button class="btn btn-warning w-100" onclick="dashboard.exportData('print')">
                                        <i class="fas fa-print me-2"></i>Print
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;

        // Add modal to body
        document.body.insertAdjacentHTML('beforeend', modalHtml);

        // Add backdrop
        const backdrop = document.createElement('div');
        backdrop.className = 'modal-backdrop fade show';
        document.body.appendChild(backdrop);
    }

    /**
     * Export data
     */
    async exportData(format) {
        try {
            this.showNotification(`Exporting ${format.toUpperCase()}...`, 'info');

            // Close modal
            const modal = document.getElementById('exportModal');
            if (modal) {
                modal.remove();
                document.querySelector('.modal-backdrop')?.remove();
            }

            // Make export request
            const response = await fetch(`/admin/tournaments/${this.tournamentId}/statistics/export/${format}`);

            if (response.ok) {
                const blob = await response.blob();
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = `tournament-statistics-${format}.${format}`;
                document.body.appendChild(a);
                a.click();
                a.remove();
                window.URL.revokeObjectURL(url);

                this.showNotification(`${format.toUpperCase()} exported successfully!`, 'success');
            } else {
                throw new Error('Export failed');
            }
        } catch (error) {
            console.error('Export error:', error);
            this.showNotification('Export failed. Please try again.', 'error');
        }
    }

    /**
     * Initialize search functionality
     */
    initSearchFunctionality() {
        const searchInputs = document.querySelectorAll('input[type="search"]');

        searchInputs.forEach(input => {
            input.addEventListener('input', (e) => {
                const searchTerm = e.target.value.toLowerCase();
                const table = e.target.closest('.table-responsive')?.querySelector('table');

                if (table) {
                    this.filterTable(table, searchTerm);
                }
            });
        });
    }

    /**
     * Filter table rows
     */
    filterTable(table, searchTerm) {
        const rows = table.querySelectorAll('tbody tr');

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            if (text.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    /**
     * Initialize print functionality
     */
    initPrintFunctionality() {
        const printButtons = document.querySelectorAll('.btn-warning[onclick*="print"]');

        printButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                window.print();
            });
        });
    }

    /**
     * Add fade-in animations
     */
    addFadeInAnimations() {
        const cards = document.querySelectorAll('.card');
        cards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';

            setTimeout(() => {
                card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 100);
        });
    }

    /**
     * Show notification
     */
    showNotification(message, type = 'info') {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show position-fixed`;
        notification.style.cssText = `
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
        `;
        notification.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;

        document.body.appendChild(notification);

        // Auto-remove after 3 seconds
        setTimeout(() => {
            notification.remove();
        }, 3000);
    }
}

// Initialize dashboard when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.dashboard = new TournamentStatisticsDashboard();
});

// Export for global access
window.TournamentStatisticsDashboard = TournamentStatisticsDashboard;
