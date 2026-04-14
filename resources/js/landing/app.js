/**
 * Landing Page App
 * Organized, scalable, production-ready
 */

class LandingApp {
    constructor() {
        this.init();
    }

    init() {
        this.handleSmoothScroll();
        this.handleMobileMenu();
        this.handleNavbarScroll();
    }

    /**
     * Smooth scrolling for anchor links
     */
    handleSmoothScroll() {
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', (e) => {
                const target = document.querySelector(anchor.getAttribute('href'));

                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start',
                    });
                }
            });
        });
    }

    /**
     * Mobile menu toggle
     */
    handleMobileMenu() {
        const button = document.querySelector('[data-menu-toggle]');
        const menu = document.querySelector('[data-menu]');

        if (!button || !menu) return;

        button.addEventListener('click', () => {
            menu.classList.toggle('hidden');
        });
    }

    /**
     * Navbar background on scroll
     */
    handleNavbarScroll() {
        const navbar = document.querySelector('[data-navbar]');

        if (!navbar) return;

        window.addEventListener('scroll', () => {
            if (window.scrollY > 20) {
                navbar.classList.add('bg-slate-900/80', 'backdrop-blur', 'shadow-lg');
            } else {
                navbar.classList.remove('bg-slate-900/80', 'backdrop-blur', 'shadow-lg');
            }
        });
    }
}

/**
 * Initialize when DOM is ready
 */
document.addEventListener('DOMContentLoaded', () => {
    new LandingApp();
});
