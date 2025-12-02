/**
 * Sticky Mega Menu Module
 * Handles sticky behavior, dropdowns, and interactions for the players page mega menu
 */
class StickyMenu {
    constructor() {
        this.menu = null;
        this.navbar = null;
        this.lastScrollY = 0;
        this.ticking = false;
        this.isSticky = false;
        this.dropdowns = [];
        this.viewToggle = null;
        this.sortToggle = null;
        this.playersGrid = null;
    }

    init() {
        this.menu = document.getElementById('playersStickyMenu');
        this.navbar = document.querySelector('.main-navbar');
        this.playersGrid = document.getElementById('playersGrid');

        if (!this.menu) return;

        this.setupDropdowns();
        this.setupViewToggle();
        this.setupSortToggle();
        this.setupScrollBehavior();
        this.setupKeyboardNavigation();

        // Initial state
        this.updateStickyState();
    }

    setupDropdowns() {
        const dropdownButtons = this.menu.querySelectorAll('.players-sticky-menu__nav-link--dropdown');

        dropdownButtons.forEach(button => {
            const dropdown = button.nextElementSibling;
            if (dropdown) {
                this.dropdowns.push({ button, dropdown });

                // Click handler for mobile/touch
                button.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.toggleDropdown(button, dropdown);
                });

                // Mouse enter/leave for desktop
                button.addEventListener('mouseenter', () => {
                    if (window.innerWidth > 1023) {
                        this.showDropdown(dropdown);
                    }
                });

                button.addEventListener('mouseleave', () => {
                    if (window.innerWidth > 1023) {
                        this.hideDropdown(dropdown);
                    }
                });

                dropdown.addEventListener('mouseenter', () => {
                    if (window.innerWidth > 1023) {
                        this.showDropdown(dropdown);
                    }
                });

                dropdown.addEventListener('mouseleave', () => {
                    if (window.innerWidth > 1023) {
                        this.hideDropdown(dropdown);
                    }
                });

                // Handle dropdown item clicks
                const items = dropdown.querySelectorAll('.players-sticky-menu__dropdown-item');
                items.forEach(item => {
                    item.addEventListener('click', (e) => {
                        e.preventDefault();
                        this.handleDropdownSelection(item);
                        this.hideDropdown(dropdown);
                    });
                });
            }
        });

        // Close dropdowns when clicking outside
        document.addEventListener('click', (e) => {
            if (!e.target.closest('.players-sticky-menu__nav-item')) {
                this.closeAllDropdowns();
            }
        });
    }

    toggleDropdown(button, dropdown) {
        const isExpanded = button.getAttribute('aria-expanded') === 'true';

        if (isExpanded) {
            this.hideDropdown(dropdown);
        } else {
            this.closeAllDropdowns();
            this.showDropdown(dropdown);
        }
    }

    showDropdown(dropdown) {
        const button = dropdown.previousElementSibling;
        dropdown.style.opacity = '1';
        dropdown.style.visibility = 'visible';
        dropdown.style.transform = 'translateY(0)';
        button.setAttribute('aria-expanded', 'true');
    }

    hideDropdown(dropdown) {
        const button = dropdown.previousElementSibling;
        dropdown.style.opacity = '0';
        dropdown.style.visibility = 'hidden';
        dropdown.style.transform = 'translateY(-10px)';
        button.setAttribute('aria-expanded', 'false');
    }

    closeAllDropdowns() {
        this.dropdowns.forEach(({ button, dropdown }) => {
            this.hideDropdown(dropdown);
        });
    }

    handleDropdownSelection(item) {
        const text = item.textContent.trim();
        const button = item.closest('.players-sticky-menu__nav-item').querySelector('.players-sticky-menu__nav-link--dropdown');

        // Update button text to show selection
        const originalText = button.innerHTML.split('<i')[0].trim();
        button.innerHTML = `${text} <i class="fas fa-chevron-down" aria-hidden="true"></i>`;

        // Here you would implement filtering logic
        console.log('Selected:', text);

        // Reset button text after 3 seconds
        setTimeout(() => {
            button.innerHTML = `${originalText} <i class="fas fa-chevron-down" aria-hidden="true"></i>`;
        }, 3000);
    }

    setupViewToggle() {
        this.viewToggle = document.getElementById('viewToggle');
        if (this.viewToggle && this.playersGrid) {
            this.viewToggle.addEventListener('click', () => {
                this.toggleView();
            });
        }
    }

    toggleView() {
        if (!this.playersGrid) return;

        const isListView = this.playersGrid.classList.contains('list-view');
        const icon = this.viewToggle.querySelector('i');

        if (isListView) {
            // Switch to grid view
            this.playersGrid.classList.remove('list-view');
            icon.className = 'fas fa-th';
            this.viewToggle.setAttribute('aria-label', 'Switch to list view');
        } else {
            // Switch to list view
            this.playersGrid.classList.add('list-view');
            icon.className = 'fas fa-list';
            this.viewToggle.setAttribute('aria-label', 'Switch to grid view');
        }

        this.viewToggle.classList.toggle('active', !isListView);
    }

    setupSortToggle() {
        this.sortToggle = document.getElementById('sortToggle');
        if (this.sortToggle) {
            this.sortToggle.addEventListener('click', () => {
                this.showSortOptions();
            });
        }
    }

    showSortOptions() {
        // Create a temporary dropdown for sort options
        const existingDropdown = document.querySelector('.sort-dropdown');
        if (existingDropdown) {
            existingDropdown.remove();
            return;
        }

        const dropdown = document.createElement('div');
        dropdown.className = 'sort-dropdown';
        dropdown.innerHTML = `
            <div class="sort-option" data-sort="name-asc">Name A-Z</div>
            <div class="sort-option" data-sort="name-desc">Name Z-A</div>
            <div class="sort-option" data-sort="age-asc">Age: Young First</div>
            <div class="sort-option" data-sort="age-desc">Age: Old First</div>
        `;

        // Position dropdown
        const rect = this.sortToggle.getBoundingClientRect();
        dropdown.style.position = 'fixed';
        dropdown.style.top = `${rect.bottom + 8}px`;
        dropdown.style.right = `${window.innerWidth - rect.right}px`;
        dropdown.style.zIndex = '1000';

        document.body.appendChild(dropdown);

        // Handle sort selection
        dropdown.addEventListener('click', (e) => {
            if (e.target.classList.contains('sort-option')) {
                const sortValue = e.target.dataset.sort;
                this.sortPlayers(sortValue);
                dropdown.remove();
            }
        });

        // Close on outside click
        setTimeout(() => {
            document.addEventListener('click', function closeDropdown(e) {
                if (!dropdown.contains(e.target) && e.target !== this.sortToggle) {
                    dropdown.remove();
                    document.removeEventListener('click', closeDropdown);
                }
            });
        }, 10);
    }

    sortPlayers(sortValue) {
        if (!this.playersGrid) return;

        const cards = Array.from(this.playersGrid.querySelectorAll('.player-card'));

        cards.sort((a, b) => {
            const nameA = a.dataset.name?.toLowerCase() || '';
            const nameB = b.dataset.name?.toLowerCase() || '';
            const ageA = parseInt(a.dataset.age) || 0;
            const ageB = parseInt(b.dataset.age) || 0;

            switch(sortValue) {
                case 'name-asc':
                    return nameA.localeCompare(nameB);
                case 'name-desc':
                    return nameB.localeCompare(nameA);
                case 'age-asc':
                    return ageA - ageB;
                case 'age-desc':
                    return ageB - ageA;
                default:
                    return 0;
            }
        });

        // Reorder DOM elements
        cards.forEach(card => this.playersGrid.appendChild(card));
    }

    setupScrollBehavior() {
        window.addEventListener('scroll', () => {
            if (!this.ticking) {
                requestAnimationFrame(() => {
                    this.updateStickyState();
                    this.ticking = false;
                });
                this.ticking = true;
            }
        }, { passive: true });

        window.addEventListener('resize', () => {
            this.updateStickyState();
        });
    }

    updateStickyState() {
        if (!this.menu || !this.navbar) return;

        const scrollY = window.scrollY;
        const navbarHeight = this.navbar.offsetHeight;
        const menuTop = this.navbar.classList.contains('sticky') ? navbarHeight : navbarHeight + 32; // 32px is --space-xl

        const shouldBeSticky = scrollY > menuTop;

        if (shouldBeSticky !== this.isSticky) {
            this.isSticky = shouldBeSticky;
            this.menu.classList.toggle('sticky', shouldBeSticky);
        }

        this.lastScrollY = scrollY;
    }

    setupKeyboardNavigation() {
        // ESC key to close dropdowns
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.closeAllDropdowns();
                const sortDropdown = document.querySelector('.sort-dropdown');
                if (sortDropdown) sortDropdown.remove();
            }
        });

        // Arrow key navigation for dropdowns
        this.menu.addEventListener('keydown', (e) => {
            const activeDropdown = document.querySelector('.players-sticky-menu__dropdown[style*="opacity: 1"]');
            if (!activeDropdown) return;

            const items = activeDropdown.querySelectorAll('.players-sticky-menu__dropdown-item');
            const currentIndex = Array.from(items).findIndex(item => item === document.activeElement);

            switch (e.key) {
                case 'ArrowDown':
                    e.preventDefault();
                    const nextIndex = currentIndex < items.length - 1 ? currentIndex + 1 : 0;
                    items[nextIndex].focus();
                    break;
                case 'ArrowUp':
                    e.preventDefault();
                    const prevIndex = currentIndex > 0 ? currentIndex - 1 : items.length - 1;
                    items[prevIndex].focus();
                    break;
                case 'Enter':
                    e.preventDefault();
                    if (document.activeElement.classList.contains('players-sticky-menu__dropdown-item')) {
                        document.activeElement.click();
                    }
                    break;
            }
        });
    }
}

export default StickyMenu;
