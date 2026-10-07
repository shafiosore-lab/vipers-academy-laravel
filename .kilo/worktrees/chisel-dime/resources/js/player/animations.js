/**
 * Player Profile Animations Module
 * Handles fade-in animations, 3D hover effects, and advanced text animations for player cards
 */

import Splitting from 'splitting';

class PlayerAnimations {
    constructor() {
        this.elements = document.querySelectorAll('.player-detail-page section, .stat-card, .related-player');
        this.animationDelay = 100;
        this.animationDuration = '0.5s';
        this.playerCards = document.querySelectorAll('.player-card');
        this.relatedCards = document.querySelectorAll('.related-player-card');
        this.maxRotation = 15; // degrees
        this.maxTilt = 10; // degrees for image
        this.heroTextElements = document.querySelectorAll('.hero-section [data-splitting]');

        // Counter animation properties
        this.counterElements = document.querySelectorAll('.counter-value');
        this.counterDuration = 2000; // 2 seconds
        this.counterDelay = 200; // delay between counters
        this.isAnimating = false;
        this.hasAnimated = false;
        this.intersectionObserver = null;
    }

    /**
      * Initialize fade-in animations for all target elements
      */
    init() {
        this.elements.forEach((element, index) => {
            // Set initial state
            element.style.opacity = '0';
            element.style.transform = 'translateY(20px)';

            // Apply animation with staggered delay
            setTimeout(() => {
                element.style.transition = `all ${this.animationDuration} ease`;
                element.style.opacity = '1';
                element.style.transform = 'translateY(0)';
            }, index * this.animationDelay);
        });

        this.initHeroTextAnimations();
        this.init3DCardEffects();
        this.initCounterAnimations();
    }

    /**
     * Initialize advanced text animations for hero section using Splitting.js
     */
    initHeroTextAnimations() {
        // Check for reduced motion preference
        const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        if (prefersReducedMotion) {
            return; // Skip animations for accessibility
        }

        if (this.heroTextElements.length === 0) return;

        // Split text elements
        Splitting();

        // Sequence animations with cinematic timing
        this.animatePlayerName();
        setTimeout(() => this.animatePositionBadge(), 800);
        setTimeout(() => this.animateMetaInfo(), 1200);
        setTimeout(() => this.animateQuickStats(), 1600);
    }

    /**
     * Animate player name with staggered char effects
     */
    animatePlayerName() {
        const nameElement = document.querySelector('.hero-section__name');
        if (!nameElement) return;

        const chars = nameElement.querySelectorAll('.char');
        chars.forEach((char, index) => {
            char.style.opacity = '0';
            char.style.transform = 'translateY(50px) rotateX(90deg)';
            char.style.transition = 'all 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94)';

            setTimeout(() => {
                char.style.opacity = '1';
                char.style.transform = 'translateY(0) rotateX(0deg)';
            }, index * 50);
        });
    }

    /**
     * Animate position badge
     */
    animatePositionBadge() {
        const badgeElement = document.querySelector('.hero-section__badge');
        if (!badgeElement) return;

        const chars = badgeElement.querySelectorAll('.char');
        chars.forEach((char, index) => {
            char.style.opacity = '0';
            char.style.transform = 'translateX(-30px) scale(0.8)';
            char.style.transition = 'all 0.6s ease-out';

            setTimeout(() => {
                char.style.opacity = '1';
                char.style.transform = 'translateX(0) scale(1)';
            }, index * 30);
        });
    }

    /**
     * Animate meta information (nationality, age, jersey)
     */
    animateMetaInfo() {
        const metaElements = document.querySelectorAll('.hero-section__nationality, .hero-section__age, .hero-section__jersey-number');
        metaElements.forEach((element, elementIndex) => {
            const chars = element.querySelectorAll('.char');
            chars.forEach((char, charIndex) => {
                char.style.opacity = '0';
                char.style.transform = 'translateY(20px)';
                char.style.transition = 'all 0.5s ease-out';

                setTimeout(() => {
                    char.style.opacity = '1';
                    char.style.transform = 'translateY(0)';
                }, (elementIndex * 200) + (charIndex * 20));
            });
        });
    }

    /**
     * Animate quick stats with slide and fade effects
     */
    animateQuickStats() {
        const statElements = document.querySelectorAll('.hero-section__quick-stat-value, .hero-section__quick-stat-label');
        statElements.forEach((element, index) => {
            const chars = element.querySelectorAll('.char');
            chars.forEach((char, charIndex) => {
                char.style.opacity = '0';
                char.style.transform = 'translateY(30px) rotateY(45deg)';
                char.style.transition = 'all 0.7s cubic-bezier(0.25, 0.46, 0.45, 0.94)';

                setTimeout(() => {
                    char.style.opacity = '1';
                    char.style.transform = 'translateY(0) rotateY(0deg)';
                }, (index * 100) + (charIndex * 15));
            });
        });
    }

    /**
     * Initialize 3D hover effects for player cards
     */
    init3DCardEffects() {
        const cards = [...this.playerCards, ...this.relatedCards];

        cards.forEach(card => {
            card.addEventListener('mousemove', (e) => this.handleMouseMove(e, card));
            card.addEventListener('mouseleave', (e) => this.handleMouseLeave(e, card));
        });
    }

    /**
     * Handle mouse movement over card for 3D rotation
     */
    handleMouseMove(e, card) {
        const rect = card.getBoundingClientRect();
        const centerX = rect.left + rect.width / 2;
        const centerY = rect.top + rect.height / 2;

        const deltaX = (e.clientX - centerX) / (rect.width / 2);
        const deltaY = (e.clientY - centerY) / (rect.height / 2);

        const rotateY = deltaX * this.maxRotation;
        const rotateX = -deltaY * this.maxRotation;

        // Apply 3D transform
        card.style.transform = `
            perspective(1000px)
            rotateX(${rotateX}deg)
            rotateY(${rotateY}deg)
            scale(1.08)
            translateZ(30px)
        `;

        // Apply image tilt
        const image = card.querySelector('.player-card__image') || card.querySelector('.card-img-top');
        if (image) {
            const imageRotateX = -deltaY * this.maxTilt;
            const imageRotateY = deltaX * this.maxTilt;
            image.style.transform = `
                scale(1.15)
                rotateX(${imageRotateX}deg)
                rotateY(${imageRotateY}deg)
                translateZ(20px)
            `;
        }
    }

    /**
     * Handle mouse leaving card - reset transforms
     */
    handleMouseLeave(e, card) {
        card.style.transform = '';
        const image = card.querySelector('.player-card__image') || card.querySelector('.card-img-top');
        if (image) {
            image.style.transform = '';
        }
    }

    /**
     * Initialize counter animations with intersection observer
     */
    initCounterAnimations() {
        if (this.counterElements.length === 0) return;

        // Check for reduced motion preference
        const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        if (prefersReducedMotion) {
            // Set final values immediately for accessibility
            this.counterElements.forEach(element => {
                const target = parseFloat(element.dataset.target) || 0;
                element.textContent = this.formatNumber(target);
            });
            return;
        }

        // Set up intersection observer
        this.setupIntersectionObserver();

        // Add hover restart functionality
        this.setupHoverRestart();
    }

    /**
     * Set up intersection observer to trigger animations when stats come into view
     */
    setupIntersectionObserver() {
        const options = {
            threshold: 0.3,
            rootMargin: '0px 0px -50px 0px'
        };

        this.intersectionObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting && !this.hasAnimated) {
                    this.startCounterAnimations();
                }
            });
        }, options);

        // Observe the quick stats container
        const statsContainer = document.querySelector('.hero-section__quick-stats');
        if (statsContainer) {
            this.intersectionObserver.observe(statsContainer);
        }
    }

    /**
     * Set up hover effects to restart counter animations
     */
    setupHoverRestart() {
        this.counterElements.forEach(element => {
            const statElement = element.closest('.hero-section__quick-stat');
            if (statElement) {
                statElement.addEventListener('mouseenter', () => {
                    if (!this.isAnimating) {
                        this.restartCounterAnimation(element);
                    }
                });
            }
        });
    }

    /**
     * Start counter animations for all elements
     */
    startCounterAnimations() {
        if (this.isAnimating || this.hasAnimated) return;

        this.isAnimating = true;
        this.hasAnimated = true;

        this.counterElements.forEach((element, index) => {
            setTimeout(() => {
                this.animateCounter(element);
            }, index * this.counterDelay);
        });

        // Reset animation flag after all animations complete
        setTimeout(() => {
            this.isAnimating = false;
        }, this.counterDuration + (this.counterElements.length * this.counterDelay));
    }

    /**
     * Animate a single counter element
     */
    animateCounter(element) {
        const target = parseFloat(element.dataset.target) || 0;
        const startValue = 0;
        const startTime = performance.now();

        const animate = (currentTime) => {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / this.counterDuration, 1);

            // Apply easing function
            const easedProgress = this.easeOutCubic(progress);
            const currentValue = startValue + (target - startValue) * easedProgress;

            element.textContent = this.formatNumber(currentValue);

            if (progress < 1) {
                requestAnimationFrame(animate);
            }
        };

        requestAnimationFrame(animate);
    }

    /**
     * Restart animation for a specific counter element
     */
    restartCounterAnimation(element) {
        const target = parseFloat(element.dataset.target) || 0;
        const startTime = performance.now();

        const animate = (currentTime) => {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / (this.counterDuration * 0.5), 1); // Faster restart

            const easedProgress = this.easeOutCubic(progress);
            const currentValue = target * easedProgress;

            element.textContent = this.formatNumber(currentValue);

            if (progress < 1) {
                requestAnimationFrame(animate);
            }
        };

        requestAnimationFrame(animate);
    }

    /**
     * Easing function for smooth animations (ease-out cubic)
     */
    easeOutCubic(t) {
        return 1 - Math.pow(1 - t, 3);
    }

    /**
     * Format numbers appropriately (handle decimals for ratings)
     */
    formatNumber(value) {
        if (value % 1 === 0) {
            // Integer values
            return Math.floor(value).toLocaleString();
        } else {
            // Decimal values (for ratings)
            return value.toFixed(1);
        }
    }

    /**
     * Cleanup method for removing observers
     */
    destroy() {
        if (this.intersectionObserver) {
            this.intersectionObserver.disconnect();
        }
    }
}

export default PlayerAnimations;
