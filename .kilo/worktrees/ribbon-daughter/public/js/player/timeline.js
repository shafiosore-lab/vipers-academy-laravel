// Match Timeline Component
class MatchTimeline {
    constructor() {
        this.container = document.getElementById('matchTimeline');
        this.seasonFilter = document.getElementById('seasonFilter');
        this.competitionFilter = document.getElementById('competitionFilter');
        this.allMatches = [];
        this.filteredMatches = [];
        this.init();
    }

    init() {
        if (!this.container) return;

        // Get match data from the page
        this.allMatches = window.playerData?.matches || this.getSampleMatches();
        this.filteredMatches = [...this.allMatches];

        // Set up event listeners
        this.setupEventListeners();

        // Render initial timeline
        this.renderTimeline();

        // Add smooth scrolling for timeline navigation
        this.addScrollNavigation();
    }

    setupEventListeners() {
        // Debounced filter function for performance
        this.debouncedFilter = this.debounce(() => this.filterMatches(), 300);

        if (this.seasonFilter) {
            this.seasonFilter.addEventListener('change', this.debouncedFilter);
        }

        if (this.competitionFilter) {
            this.competitionFilter.addEventListener('change', this.debouncedFilter);
        }
    }

    filterMatches() {
        const seasonValue = this.seasonFilter?.value || 'all';
        const competitionValue = this.competitionFilter?.value || 'all';

        this.filteredMatches = this.allMatches.filter(match => {
            const seasonMatch = seasonValue === 'all' || this.getSeasonFromDate(match.date) === seasonValue;
            const competitionMatch = competitionValue === 'all' || match.competition === competitionValue;
            return seasonMatch && competitionMatch;
        });

        this.renderTimeline();
    }

    getSeasonFromDate(dateString) {
        const date = new Date(dateString);
        const year = date.getFullYear();
        const month = date.getMonth() + 1;

        // Football season typically runs from August to May
        if (month >= 8) {
            return `${year}/${year + 1}`;
        } else {
            return `${year - 1}/${year}`;
        }
    }

    renderTimeline() {
        if (!this.container) return;

        // Clear existing content
        this.container.innerHTML = '';

        if (this.filteredMatches.length === 0) {
            this.container.innerHTML = `
                <div class="text-center py-5">
                    <i class="fas fa-search fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No matches found</h5>
                    <p class="text-muted">Try adjusting your filters</p>
                </div>
            `;
            return;
        }

        // Sort matches by date (newest first)
        const sortedMatches = [...this.filteredMatches].sort((a, b) =>
            new Date(b.date) - new Date(a.date)
        );

        // Create match items
        sortedMatches.forEach(match => {
            const matchElement = this.createMatchElement(match);
            this.container.appendChild(matchElement);
        });
    }

    createMatchElement(match) {
        const matchDiv = document.createElement('div');
        matchDiv.className = `match-item match-${match.result}`;
        matchDiv.setAttribute('data-match-id', match.id);

        const date = new Date(match.date);
        const formattedDate = date.toLocaleDateString('en-GB', {
            day: 'numeric',
            month: 'short',
            year: 'numeric'
        });

        matchDiv.innerHTML = `
            <div class="match-item-header">
                <div class="match-date">${formattedDate}</div>
                <div class="match-opponent">${match.home ? 'vs' : '@'} ${match.opponent}</div>
                <div class="match-competition">${match.competition}</div>
            </div>
            <div class="match-item-body">
                <div class="match-result">
                    <div class="match-score">${match.score}</div>
                    <div class="match-outcome ${match.result}">${match.result}</div>
                </div>
                <div class="match-stats">
                    <div class="stat-item">
                        <span class="stat-value">${match.goals}</span>
                        <span class="stat-label">Goals</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-value">${match.assists}</span>
                        <span class="stat-label">Assists</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-value">${match.minutes}'</span>
                        <span class="stat-label">Minutes</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-value">${match.rating}</span>
                        <span class="stat-label">Rating</span>
                    </div>
                </div>
            </div>
        `;

        // Add click event for modal
        matchDiv.addEventListener('click', () => this.showMatchDetails(match));

        return matchDiv;
    }

    showMatchDetails(match) {
        const modalHtml = `
            <div class="modal fade match-details-modal" id="matchDetailsModal" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                ${match.home ? 'vs' : '@'} ${match.opponent}
                                <small class="text-white-50 ms-2">${match.competition}</small>
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <h6 class="text-primary mb-3">Match Result</h6>
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <span class="fw-bold fs-4">${match.score}</span>
                                        <span class="badge bg-${match.result === 'win' ? 'success' : match.result === 'draw' ? 'warning' : 'danger'} fs-6 px-3 py-2">${match.result.toUpperCase()}</span>
                                    </div>
                                    <p class="text-muted mb-0">
                                        ${new Date(match.date).toLocaleDateString('en-GB', {
                                            weekday: 'long',
                                            day: 'numeric',
                                            month: 'long',
                                            year: 'numeric'
                                        })}
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-primary mb-3">Player Performance</h6>
                                    <div class="match-details-stats">
                                        <div class="detail-stat">
                                            <span class="detail-stat-value">${match.goals}</span>
                                            <span class="detail-stat-label">Goals</span>
                                        </div>
                                        <div class="detail-stat">
                                            <span class="detail-stat-value">${match.assists}</span>
                                            <span class="detail-stat-label">Assists</span>
                                        </div>
                                        <div class="detail-stat">
                                            <span class="detail-stat-value">${match.minutes}'</span>
                                            <span class="detail-stat-label">Minutes</span>
                                        </div>
                                        <div class="detail-stat">
                                            <span class="detail-stat-value">${match.rating}</span>
                                            <span class="detail-stat-label">Rating</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="text-primary mb-3">Attacking Stats</h6>
                                    <div class="match-details-stats">
                                        <div class="detail-stat">
                                            <span class="detail-stat-value">${match.shots}</span>
                                            <span class="detail-stat-label">Shots</span>
                                        </div>
                                        <div class="detail-stat">
                                            <span class="detail-stat-value">${match.shotsOnTarget}</span>
                                            <span class="detail-stat-label">On Target</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-primary mb-3">Defensive Stats</h6>
                                    <div class="match-details-stats">
                                        <div class="detail-stat">
                                            <span class="detail-stat-value">${match.tackles}</span>
                                            <span class="detail-stat-label">Tackles</span>
                                        </div>
                                        <div class="detail-stat">
                                            <span class="detail-stat-value">${match.interceptions}</span>
                                            <span class="detail-stat-label">Interceptions</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <h6 class="text-primary mb-3">Passing</h6>
                                    <div class="match-details-stats">
                                        <div class="detail-stat">
                                            <span class="detail-stat-value">${match.passes}</span>
                                            <span class="detail-stat-label">Passes</span>
                                        </div>
                                        <div class="detail-stat">
                                            <span class="detail-stat-value">${match.passAccuracy}%</span>
                                            <span class="detail-stat-label">Accuracy</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-primary mb-3">Discipline</h6>
                                    <div class="match-details-stats">
                                        <div class="detail-stat">
                                            <span class="detail-stat-value">${match.fouls}</span>
                                            <span class="detail-stat-label">Fouls</span>
                                        </div>
                                        <div class="detail-stat">
                                            <span class="detail-stat-value">${match.yellowCards}</span>
                                            <span class="detail-stat-label">Yellow Cards</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            ${match.notes ? `
                                <div class="match-performance-notes">
                                    <h6>Performance Notes</h6>
                                    <p class="mb-0">${match.notes}</p>
                                </div>
                            ` : ''}
                        </div>
                    </div>
                </div>
            </div>
        `;

        // Remove existing modal if present
        const existingModal = document.getElementById('matchDetailsModal');
        if (existingModal) {
            existingModal.remove();
        }

        // Add modal to body
        document.body.insertAdjacentHTML('beforeend', modalHtml);

        // Show modal
        const modal = new bootstrap.Modal(document.getElementById('matchDetailsModal'));
        modal.show();

        // Clean up modal when hidden
        document.getElementById('matchDetailsModal').addEventListener('hidden.bs.modal', function() {
            this.remove();
        });
    }

    addScrollNavigation() {
        // Add keyboard navigation
        document.addEventListener('keydown', (e) => {
            if (!this.container) return;

            const scrollAmount = 300;
            if (e.key === 'ArrowLeft') {
                this.container.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
            } else if (e.key === 'ArrowRight') {
                this.container.scrollBy({ left: scrollAmount, behavior: 'smooth' });
            }
        });

        // Add touch/swipe support for mobile
        let startX = 0;
        let scrollLeft = 0;

        this.container.addEventListener('touchstart', (e) => {
            startX = e.touches[0].pageX - this.container.offsetLeft;
            scrollLeft = this.container.scrollLeft;
        });

        this.container.addEventListener('touchmove', (e) => {
            if (!startX) return;
            e.preventDefault();
            const x = e.touches[0].pageX - this.container.offsetLeft;
            const walk = (x - startX) * 2;
            this.container.scrollLeft = scrollLeft - walk;
        });
    }

    getSampleMatches() {
        return [
            {
                id: 1,
                date: '2024-11-20',
                opponent: 'Manchester City',
                competition: 'Premier League',
                home: false,
                score: '2-1',
                result: 'loss',
                goals: 0,
                assists: 1,
                minutes: 85,
                rating: 7.2,
                shots: 3,
                shotsOnTarget: 1,
                passes: 45,
                passAccuracy: 82,
                tackles: 4,
                interceptions: 2,
                fouls: 1,
                yellowCards: 0,
                redCards: 0,
                notes: 'Good performance despite the loss.'
            }
        ];
    }

    // Method to update timeline with new data
    updateTimeline(newMatches) {
        this.allMatches = newMatches;
        this.filteredMatches = [...this.allMatches];
        this.filterMatches();
    }

    // Utility function for debouncing
    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
}

// Initialize timeline when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    // Only initialize on player detail page
    if (document.querySelector('.player-profile-page')) {
        window.matchTimeline = new MatchTimeline();
    }
});

// Export for potential use in other modules
export default MatchTimeline;
