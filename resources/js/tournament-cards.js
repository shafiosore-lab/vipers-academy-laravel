/**
 * Tournament Card System JavaScript
 * Provides interactive features and animations for the card-based tournament interface
 */

class TournamentCardSystem {
    constructor() {
        this.init();
    }

    /**
     * Initialize the card system
     */
    init() {
        this.initCardAnimations();
        this.initExpandableCards();
        this.initActionButtons();
        this.initResponsiveGrids();
        this.initLoadingStates();
        this.initPrintFunctionality();

        console.log('Tournament Card System initialized');
    }

    /**
     * Initialize card animations
     */
    initCardAnimations() {
        // Add fade-in animation to cards on page load
        const cards = document.querySelectorAll('.tournament-card');
        cards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';

            setTimeout(() => {
                card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 100);
        });

        // Add hover effects
        cards.forEach(card => {
            card.addEventListener('mouseenter', () => {
                card.style.transform = 'translateY(-4px) scale(1.02)';
            });

            card.addEventListener('mouseleave', () => {
                card.style.transform = 'translateY(0) scale(1)';
            });
        });
    }

    /**
     * Initialize expandable card functionality
     */
    initExpandableCards() {
        const expandButtons = document.querySelectorAll('.card-header button[data-bs-toggle="collapse"]');

        expandButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                const icon = button.querySelector('i');
                const target = button.getAttribute('data-bs-target');
                const collapse = document.querySelector(target);

                if (collapse.classList.contains('show')) {
                    icon.classList.remove('fa-chevron-up');
                    icon.classList.add('fa-chevron-down');
                } else {
                    icon.classList.remove('fa-chevron-down');
                    icon.classList.add('fa-chevron-up');
                }
            });
        });

        // Auto-expand cards with hash in URL
        const hash = window.location.hash;
        if (hash) {
            const targetCard = document.querySelector(hash);
            if (targetCard) {
                targetCard.classList.add('show');
                const button = targetCard.previousElementSibling.querySelector('button');
                if (button) {
                    button.querySelector('i').classList.remove('fa-chevron-down');
                    button.querySelector('i').classList.add('fa-chevron-up');
                }
            }
        }
    }

    /**
     * Initialize action button functionality
     */
    initActionButtons() {
        // Add loading states to action buttons
        const actionButtons = document.querySelectorAll('.action-card .btn');

        actionButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                if (button.classList.contains('disabled')) {
                    e.preventDefault();
                    return;
                }

                this.setButtonLoading(button, true);

                // Auto remove loading state after 2 seconds if not manually removed
                setTimeout(() => {
                    if (button.classList.contains('btn-loading')) {
                        this.setButtonLoading(button, false);
                    }
                }, 2000);
            });
        });

        // Add confirmation dialogs for destructive actions
        const destructiveButtons = document.querySelectorAll('.btn-danger, .btn-outline-danger');

        destructiveButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                const action = button.textContent.trim().toLowerCase();
                const isDestructive = ['delete', 'remove', 'cancel', 'reject'].some(word => action.includes(word));

                if (isDestructive && !confirm('Are you sure you want to perform this action?')) {
                    e.preventDefault();
                }
            });
        });
    }

    /**
     * Initialize responsive grid layouts
     */
    initResponsiveGrids() {
        // Handle dynamic grid resizing
        const resizeObserver = new ResizeObserver(entries => {
            entries.forEach(entry => {
                const container = entry.target;
                const cards = container.querySelectorAll('.tournament-card');

                if (cards.length > 0) {
                    this.updateGridLayout(container);
                }
            });
        });

        // Observe grid containers
        const gridContainers = document.querySelectorAll('.tournament-card-grid, .tournament-card-row');
        gridContainers.forEach(container => {
            resizeObserver.observe(container);
        });

        // Handle window resize
        window.addEventListener('resize', () => {
            setTimeout(() => {
                gridContainers.forEach(container => {
                    this.updateGridLayout(container);
                });
            }, 100);
        });
    }

    /**
     * Update grid layout based on container width
     */
    updateGridLayout(container) {
        const containerWidth = container.clientWidth;
        const cards = container.querySelectorAll('.tournament-card');

        if (containerWidth < 576) {
            // Mobile: 1 column
            cards.forEach(card => {
                card.style.gridColumn = '1';
            });
        } else if (containerWidth < 768) {
            // Tablet: 2 columns
            cards.forEach((card, index) => {
                card.style.gridColumn = (index % 2) + 1;
            });
        } else {
            // Desktop: maintain CSS grid
            cards.forEach(card => {
                card.style.gridColumn = '';
            });
        }
    }

    /**
     * Initialize loading states
     */
    initLoadingStates() {
        // Add loading state functionality
        const loadingButtons = document.querySelectorAll('[data-loading]');

        loadingButtons.forEach(button => {
            button.addEventListener('click', () => {
                this.setCardLoading(button.closest('.tournament-card'), true);

                // Simulate loading completion after 2 seconds
                setTimeout(() => {
                    this.setCardLoading(button.closest('.tournament-card'), false);
                }, 2000);
            });
        });
    }

    /**
     * Initialize print functionality
     */
    initPrintFunctionality() {
        const printButtons = document.querySelectorAll('.btn-print');

        printButtons.forEach(button => {
            button.addEventListener('click', () => {
                // Add print-specific classes
                document.body.classList.add('printing');

                // Wait for styles to apply, then print
                setTimeout(() => {
                    window.print();
                    document.body.classList.remove('printing');
                }, 100);
            });
        });
    }

    /**
     * Set button loading state
     */
    setButtonLoading(button, isLoading) {
        if (isLoading) {
            button.classList.add('btn-loading', 'disabled');
            button.disabled = true;

            const originalText = button.innerHTML;
            button.dataset.originalText = originalText;

            button.innerHTML = `
                <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                Loading...
            `;
        } else {
            button.classList.remove('btn-loading', 'disabled');
            button.disabled = false;

            if (button.dataset.originalText) {
                button.innerHTML = button.dataset.originalText;
            }
        }
    }

    /**
     * Set card loading state
     */
    setCardLoading(card, isLoading) {
        if (!card) return;

        if (isLoading) {
            card.classList.add('card-loading');
            card.style.pointerEvents = 'none';
        } else {
            card.classList.remove('card-loading');
            card.style.pointerEvents = '';
        }
    }

    /**
     * Show notification
     */
    showNotification(message, type = 'info', duration = 3000) {
        // Remove existing notifications
        document.querySelectorAll('.toast-notification').forEach(el => el.remove());

        // Create notification element
        const notification = document.createElement('div');
        notification.className = `toast-notification alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show position-fixed`;
        notification.style.cssText = `
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        `;
        notification.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;

        document.body.appendChild(notification);

        // Auto-remove after duration
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, duration);
    }

    /**
     * Refresh card data
     */
    async refreshCardData(cardId, endpoint) {
        const card = document.getElementById(cardId);
        if (!card) return;

        this.setCardLoading(card, true);

        try {
            const response = await fetch(endpoint);
            const data = await response.json();

            // Update card content based on data
            this.updateCardContent(card, data);

            this.showNotification('Card data updated successfully', 'success', 2000);
        } catch (error) {
            console.error('Error refreshing card data:', error);
            this.showNotification('Failed to refresh data', 'error', 3000);
        } finally {
            this.setCardLoading(card, false);
        }
    }

    /**
     * Update card content with new data
     */
    updateCardContent(card, data) {
        // This is a generic implementation - specific cards would override this
        const valueElement = card.querySelector('.display-6');
        const subtitleElement = card.querySelector('.text-muted');

        if (valueElement && data.value !== undefined) {
            this.animateValueChange(valueElement, data.value);
        }

        if (subtitleElement && data.subtitle) {
            subtitleElement.textContent = data.subtitle;
        }
    }

    /**
     * Animate value changes
     */
    animateValueChange(element, newValue) {
        const currentValue = parseFloat(element.textContent.replace(/[^0-9.-]/g, ''));
        const targetValue = parseFloat(newValue);

        if (isNaN(currentValue) || isNaN(targetValue)) {
            element.textContent = newValue;
            return;
        }

        const duration = 1000;
        const startTime = performance.now();

        const animate = (currentTime) => {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);

            // Ease out quad
            const easeProgress = 1 - Math.pow(1 - progress, 2);

            const currentValue = currentValue + (targetValue - currentValue) * easeProgress;
            element.textContent = Math.floor(currentValue);

            if (progress < 1) {
                requestAnimationFrame(animate);
            } else {
                element.textContent = newValue;
            }
        };

        requestAnimationFrame(animate);
    }

    /**
     * Export card data
     */
    exportCardData(cardId, format = 'json') {
        const card = document.getElementById(cardId);
        if (!card) return;

        const cardData = {
            title: card.querySelector('.card-title')?.textContent || '',
            value: card.querySelector('.display-6')?.textContent || '',
            subtitle: card.querySelector('.text-muted')?.textContent || '',
            timestamp: new Date().toISOString()
        };

        if (format === 'json') {
            const blob = new Blob([JSON.stringify(cardData, null, 2)], { type: 'application/json' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `${cardId}-data.json`;
            document.body.appendChild(a);
            a.click();
            a.remove();
            URL.revokeObjectURL(url);
        } else if (format === 'csv') {
            const csv = Object.entries(cardData)
                .map(([key, value]) => `${key},${value}`)
                .join('\n');

            const blob = new Blob([csv], { type: 'text/csv' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `${cardId}-data.csv`;
            document.body.appendChild(a);
            a.click();
            a.remove();
            URL.revokeObjectURL(url);
        }
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.tournamentCardSystem = new TournamentCardSystem();
});

// Export for global access
window.TournamentCardSystem = TournamentCardSystem;
