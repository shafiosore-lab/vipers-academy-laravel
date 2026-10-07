/**
 * Section Navigation Module
 * Handles slim filter bar navigation, smooth scrolling, and active section updates
 */

class SectionNavigation {
    constructor() {
        this.slimFilterBar = document.querySelector('.slim-filter-bar');
        this.sectionLinks = document.querySelectorAll('.section-link');
        this.mainNavbar = document.querySelector('.main-navbar');
        this.sections = ['overview', 'stats', 'rankings', 'season', 'bio', 'career'];
        this.scrollOffset = 100;
    }

    /**
     * Initialize section navigation functionality
     */
    init() {
        this.bindSectionLinkEvents();
        this.bindScrollEvents();
        this.updateActiveSection();
        this.updateSlimFilterBarPosition();

        // Force initial positioning
        setTimeout(() => this.updateSlimFilterBarPosition(), 100);
    }

    /**
     * Bind click events to section navigation links
     */
    bindSectionLinkEvents() {
        this.sectionLinks.forEach(link => {
            link.addEventListener('click', (e) => this.handleSectionLinkClick(e, link));
        });
    }

    /**
     * Handle section link click for smooth scrolling
     */
    handleSectionLinkClick(event, link) {
        event.preventDefault();

        const targetId = link.getAttribute('href');
        const targetElement = document.querySelector(targetId);

        if (targetElement) {
            this.forceSlimModePositioning();
            this.scrollToSection(targetElement);
            this.updateActiveLink(link);
        }
    }

    /**
     * Force slim mode positioning for smooth transitions
     */
    forceSlimModePositioning() {
        const topBarHeight = this.getTopBarHeight();
        const mainNavbarHeight = this.mainNavbar.offsetHeight;

        this.slimFilterBar.style.top = `${topBarHeight + mainNavbarHeight}px`;
        this.slimFilterBar.classList.add('slim-mode');
    }

    /**
     * Smooth scroll to target section
     */
    scrollToSection(targetElement) {
        const slimBarHeight = this.slimFilterBar.offsetHeight;
        const offsetTop = targetElement.offsetTop - slimBarHeight;

        window.scrollTo({
            top: offsetTop,
            behavior: 'smooth'
        });
    }

    /**
     * Update active link styling
     */
    updateActiveLink(activeLink) {
        this.sectionLinks.forEach(link => link.classList.remove('active'));
        activeLink.classList.add('active');
    }

    /**
     * Bind scroll event listeners
     */
    bindScrollEvents() {
        window.addEventListener('scroll', () => this.updateActiveSection());
        window.addEventListener('scroll', () => this.updateSlimFilterBarPosition());
    }

    /**
     * Update active section based on scroll position
     */
    updateActiveSection() {
        const scrollPosition = window.scrollY + this.slimFilterBar.offsetHeight + this.scrollOffset;

        this.sections.forEach(sectionId => {
            const section = document.getElementById(sectionId);
            if (section) {
                const { offsetTop: sectionTop, offsetHeight: sectionHeight } = section;
                const sectionBottom = sectionTop + sectionHeight;

                if (scrollPosition >= sectionTop && scrollPosition < sectionBottom) {
                    this.updateActiveLinkForSection(sectionId);
                }
            }
        });
    }

    /**
     * Update active link for a specific section
     */
    updateActiveLinkForSection(sectionId) {
        this.sectionLinks.forEach(link => {
            link.classList.remove('active');
            if (link.getAttribute('data-section') === sectionId) {
                link.classList.add('active');
            }
        });
    }

    /**
     * Update slim filter bar position based on scroll
     */
    updateSlimFilterBarPosition() {
        if (!this.slimFilterBar || !this.mainNavbar) return;

        const scrollY = window.scrollY;
        const mainNavbarHeight = this.mainNavbar.offsetHeight;
        const topBarHeight = this.getTopBarHeight();
        const categoryBarHeight = this.getCategoryBarHeight();

        const originalTop = topBarHeight + mainNavbarHeight + categoryBarHeight;

        if (scrollY > topBarHeight + mainNavbarHeight + categoryBarHeight - 10) {
            this.slimFilterBar.style.top = `${topBarHeight + mainNavbarHeight}px`;
            this.slimFilterBar.classList.add('slim-mode');
        } else {
            this.slimFilterBar.style.top = `${originalTop}px`;
            this.slimFilterBar.classList.remove('slim-mode');
        }
    }

    /**
     * Get top bar height
     */
    getTopBarHeight() {
        const topBar = document.querySelector('.top-bar');
        return topBar ? topBar.offsetHeight : 32;
    }

    /**
     * Get category bar height
     */
    getCategoryBarHeight() {
        const categoryBar = document.querySelector('.category-bar');
        return categoryBar ? categoryBar.offsetHeight : 48;
    }
}

export default SectionNavigation;
