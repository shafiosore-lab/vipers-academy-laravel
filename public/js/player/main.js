/**
 * Player Profile Main Module
 * Entry point for initializing all player profile JavaScript functionality
 */

import PlayerAnimations from './animations.js';
import SectionNavigation from './sectionNavigation.js';
import DropdownInteractions from './dropdownInteractions.js';

/**
 * Initialize all player profile modules when DOM is ready
 */
const initializePlayerProfile = () => {
    // Initialize animations
    const animations = new PlayerAnimations();
    animations.init();

    // Initialize section navigation
    const sectionNav = new SectionNavigation();
    sectionNav.init();

    // Initialize dropdown interactions
    const dropdown = new DropdownInteractions();
    dropdown.init();
};

// Initialize when DOM is fully loaded
document.addEventListener('DOMContentLoaded', initializePlayerProfile);

// Export for potential external use
export { PlayerAnimations, SectionNavigation, DropdownInteractions };
