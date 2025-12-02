/**
 * Dropdown Interactions Module
 * Handles players dropdown toggle, outside click closing, and navigation positioning
 */

class DropdownInteractions {
    constructor() {
        this.playersDropdown = document.getElementById('playersDropdown');
        this.dropdownMenu = this.playersDropdown ? this.playersDropdown.nextElementSibling : null;
        this.slimFilterBar = document.querySelector('.slim-filter-bar');
        this.mainNavbar = document.querySelector('.main-navbar');
    }

    /**
     * Initialize dropdown functionality
     */
    init() {
        if (!this.playersDropdown || !this.dropdownMenu) return;

        this.bindDropdownEvents();
        this.bindOutsideClick();
        this.bindNavigationEvents();
    }

    /**
     * Bind dropdown toggle events
     */
    bindDropdownEvents() {
        this.playersDropdown.addEventListener('click', () => this.toggleDropdown());
    }

    /**
     * Toggle dropdown visibility
     */
    toggleDropdown() {
        const isExpanded = this.playersDropdown.getAttribute('aria-expanded') === 'true';
        this.playersDropdown.setAttribute('aria-expanded', !isExpanded);
        this.dropdownMenu.classList.toggle('show');
    }

    /**
     * Bind outside click to close dropdown
     */
    bindOutsideClick() {
        document.addEventListener('click', (event) => this.handleOutsideClick(event));
    }

    /**
     * Handle clicks outside dropdown to close it
     */
    handleOutsideClick(event) {
        if (!this.playersDropdown.contains(event.target) && !this.dropdownMenu.contains(event.target)) {
            this.closeDropdown();
        }
    }

    /**
     * Close dropdown programmatically
     */
    closeDropdown() {
        this.playersDropdown.setAttribute('aria-expanded', 'false');
        this.dropdownMenu.classList.remove('show');
    }

    /**
     * Bind navigation events for dropdown items
     */
    bindNavigationEvents() {
        const dropdownItems = this.dropdownMenu.querySelectorAll('.dropdown-item');
        dropdownItems.forEach(item => {
            item.addEventListener('click', () => this.handleNavigationClick());
        });
    }

    /**
     * Handle navigation click to ensure slim bar positioning
     */
    handleNavigationClick() {
        this.forceSlimModePositioning();
    }

    /**
     * Force slim mode positioning for smooth navigation transitions
     */
    forceSlimModePositioning() {
        const topBarHeight = this.getTopBarHeight();
        const mainNavbarHeight = this.mainNavbar.offsetHeight;

        this.slimFilterBar.style.top = `${topBarHeight + mainNavbarHeight}px`;
        this.slimFilterBar.classList.add('slim-mode');
    }

    /**
     * Get top bar height
     */
    getTopBarHeight() {
        const topBar = document.querySelector('.top-bar');
        return topBar ? topBar.offsetHeight : 32;
    }
}

export default DropdownInteractions;
