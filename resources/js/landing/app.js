/**
 * app.js
 * Production-ready JavaScript entry point - Consolidated & Deduplicated
 */

"use strict";

// =============================================
// DOM Ready Helper
// =============================================
const ready = (callback) => {
    if (document.readyState !== "loading") {
        callback();
    } else {
        document.addEventListener("DOMContentLoaded", callback, { once: true });
    }
};

// =============================================
// Smooth Scroll Handler (Single Implementation)
// =============================================
const initSmoothScroll = () => {
    document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
        anchor.addEventListener("click", function (e) {
            const targetId = this.getAttribute("href").slice(1);
            if (targetId === "") return; // Skip empty anchors

            const targetElement = document.getElementById(targetId);
            if (targetElement) {
                e.preventDefault();
                targetElement.scrollIntoView({
                    behavior: "smooth",
                    block: "start",
                });
            }
        });
    });
};

// =============================================
// Scroll Reveal Handler (Single Implementation)
// =============================================
const initScrollReveal = () => {
    const observerOptions = {
        threshold: 0.1,
        rootMargin: "0px 0px -100px 0px",
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add("visible");
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    document
        .querySelectorAll(
            ".scroll-reveal, .scroll-reveal-bottom, .scroll-reveal-top",
        )
        .forEach((el) => {
            observer.observe(el);
        });
};

// =============================================
// Mobile Menu Toggle
// =============================================
const initMobileMenu = () => {
    const mobileMenuButton = document.getElementById("mobile-menu-button");
    const mobileMenu = document.getElementById("mobile-menu");

    if (mobileMenuButton && mobileMenu) {
        mobileMenuButton.addEventListener("click", () => {
            mobileMenu.classList.toggle("hidden");
        });
    }
};

// =============================================
// Accessibility: Respect Prefers Reduced Motion
// =============================================
const initAccessibility = () => {
    if (window.matchMedia("(prefers-reduced-motion: reduce)").matches) {
        document.documentElement.style.setProperty("scroll-behavior", "auto");
    }

    if (process.env.NODE_ENV !== "production") {
        console.log("🚀 Development mode active");
    }
};

// =============================================
// Tab System with Progress Indicators
// =============================================
const initTabSystem = () => {
    const tabButtons = document.querySelectorAll(".tab-button");
    const tabContents = document.querySelectorAll(".tab-content");
    const progressDots = document.querySelectorAll(".progress-dot");

    if (!tabButtons.length || !tabContents.length) return;

    const showTab = (tabId) => {
        // Reset all tabs and buttons
        tabContents.forEach((tab) => tab.classList.remove("active"));
        tabButtons.forEach((btn) => btn.classList.remove("active"));

        // Activate selected tab and button
        const activeTab = document.getElementById(tabId);
        const activeButton = document.querySelector(`[data-tab="${tabId}"]`);

        if (activeTab) activeTab.classList.add("active");
        if (activeButton) activeButton.classList.add("active");

        // Update progress dots
        const tabNum = parseInt(tabId.split("-")[1]);
        progressDots.forEach((dot, idx) => {
            const dotNum = idx + 1;
            dot.classList.toggle("active", dotNum === tabNum);
            dot.classList.toggle("completed", dotNum < tabNum);
        });
    };

    // Tab button clicks
    tabButtons.forEach((button) => {
        button.addEventListener("click", () => {
            const tabId = button.getAttribute("data-tab");
            if (tabId) showTab(tabId);
        });
    });

    // Progress dot clicks
    progressDots.forEach((dot) => {
        dot.addEventListener("click", () => {
            const tabNum = dot.getAttribute("data-tab");
            if (tabNum) showTab(`tab-${tabNum}`);
        });
    });

    // Next / Previous buttons
    document.querySelectorAll(".tab-next, .tab-prev").forEach((btn) => {
        btn.addEventListener("click", (e) => {
            e.preventDefault();
            const currentTab = btn.closest(".tab-content");
            if (!currentTab) return;

            const tabNumber = parseInt(currentTab.id.split("-")[1]);
            const isNext = btn.classList.contains("tab-next");
            const targetTabId = `tab-${isNext ? tabNumber + 1 : tabNumber - 1}`;

            if (document.getElementById(targetTabId)) {
                showTab(targetTabId);
            }
        });
    });

    // Expose showTab globally for external use
    window.showTab = showTab;
};

// =============================================
// Star Rating System
// =============================================
const initStarRatings = () => {
    document.querySelectorAll(".star-rating").forEach((ratingGroup) => {
        const ratingType = ratingGroup.getAttribute("data-rating");
        if (!ratingType) return;

        const stars = ratingGroup.querySelectorAll(".star");
        const hiddenInput = document.querySelector(
            `input[name="${ratingType}"]`,
        );
        const textDisplay = document.querySelector(`.${ratingType}-text`);
        let selectedRating = 0;

        const updateStars = (value) => {
            stars.forEach((star) => {
                const starValue = parseInt(star.getAttribute("data-value"));
                star.classList.toggle("active", starValue <= value);
            });
        };

        stars.forEach((star) => {
            const value = parseInt(star.getAttribute("data-value"));

            star.addEventListener("click", () => {
                selectedRating = value;
                if (hiddenInput) hiddenInput.value = value;
                if (textDisplay) textDisplay.textContent = `${value} out of 5`;
                updateStars(value);
            });

            star.addEventListener("mouseover", () => updateStars(value));
        });

        ratingGroup.addEventListener("mouseleave", () =>
            updateStars(selectedRating),
        );
    });
};

// =============================================
// Character Counter
// =============================================
const initCharacterCounter = () => {
    const messageInput = document.getElementById("message");
    const charCount = document.getElementById("charCount");

    if (messageInput && charCount) {
        messageInput.addEventListener("input", (e) => {
            charCount.textContent = `${e.target.value.length} / 1000`;
        });
    }
};

// =============================================
// Photo Upload with Drag & Drop
// =============================================
const initPhotoUpload = () => {
    const photoDropZone = document.getElementById("photoDropZone");
    const photoInput = document.getElementById("photo");
    const photoPreview = document.getElementById("photoPreview");
    const photoName = document.getElementById("photoName");
    const removePhotoBtn = document.getElementById("removePhoto");

    if (!photoDropZone || !photoInput || !photoPreview || !photoName) return;

    const resetPhotoUpload = () => {
        photoInput.value = "";
        photoDropZone.style.display = "";
        photoPreview.classList.add("hidden");
    };

    const updatePhotoPreview = () => {
        if (photoInput.files[0]) {
            photoName.textContent = photoInput.files[0].name;
            photoDropZone.style.display = "none";
            photoPreview.classList.remove("hidden");
        }
    };

    // Click to upload
    photoDropZone.addEventListener("click", () => photoInput.click());

    // Drag & Drop handlers
    photoDropZone.addEventListener("dragover", (e) => {
        e.preventDefault();
        photoDropZone.style.borderColor = "#3b82f6";
    });

    photoDropZone.addEventListener("dragleave", () => {
        photoDropZone.style.borderColor = "";
    });

    photoDropZone.addEventListener("drop", (e) => {
        e.preventDefault();
        photoDropZone.style.borderColor = "";
        if (e.dataTransfer.files[0]) {
            photoInput.files = e.dataTransfer.files;
            updatePhotoPreview();
        }
    });

    photoInput.addEventListener("change", updatePhotoPreview);

    if (removePhotoBtn) {
        removePhotoBtn.addEventListener("click", (e) => {
            e.preventDefault();
            resetPhotoUpload();
        });
    }

    // Expose for external use
    window.resetPhotoUpload = resetPhotoUpload;
};

// =============================================
// Testimonials Filter System
// =============================================
const initTestimonialsFilter = () => {
    const filterBtns = document.querySelectorAll(".filter-btn");
    const testimonialCards = document.querySelectorAll(".testimonial-card");

    if (!filterBtns.length || !testimonialCards.length) return;

    // Show all testimonials on page load
    window.addEventListener("load", () => {
        testimonialCards.forEach((card) => {
            card.classList.add("visible");
            card.classList.remove("hidden");
        });
    });

    filterBtns.forEach((btn) => {
        btn.addEventListener("click", () => {
            const filterValue = btn.getAttribute("data-filter");

            // Update button states
            filterBtns.forEach((b) => b.classList.remove("active"));
            btn.classList.add("active");

            // Filter testimonials
            testimonialCards.forEach((card) => {
                const cardCategory = card.getAttribute("data-category");
                const shouldShow =
                    filterValue === "all" || cardCategory === filterValue;

                card.classList.toggle("hidden", !shouldShow);
                card.classList.toggle("visible", shouldShow);
            });
        });
    });
};

// =============================================
// Form Validation & Submission
// =============================================
const initFormSubmission = () => {
    const form = document.getElementById("testimonialForm");
    if (!form) return;

    const successMessage = document.getElementById("successMessage");
    const errorMessage = document.getElementById("errorMessage");
    const errorText = document.getElementById("errorText");

    const showMessage = (element, timeout = 5000) => {
        if (!element) return;
        element.classList.add("show");
        setTimeout(() => element.classList.remove("show"), timeout);
    };

    const resetForm = () => {
        form.reset();
        if (window.showTab) window.showTab("tab-1");

        // Reset star ratings
        document
            .querySelectorAll(
                'input[name="coverage"], input[name="impact"], input[name="overall"]',
            )
            .forEach((input) => {
                input.value = "";
            });

        // Reset photo upload
        if (window.resetPhotoUpload) window.resetPhotoUpload();
    };

    form.addEventListener("submit", (e) => {
        e.preventDefault();

        // Get form values
        const fullName = document.getElementById("fullName")?.value.trim();
        const email = document.getElementById("email")?.value.trim();
        const location = document.getElementById("location")?.value.trim();
        const role = document.getElementById("role")?.value.trim();
        const title = document.getElementById("title")?.value.trim();
        const message = document.getElementById("message")?.value.trim();
        const consent = document.getElementById("consent")?.checked;

        const coverage = document.querySelector(
            'input[name="coverage"]',
        )?.value;
        const impact = document.querySelector('input[name="impact"]')?.value;
        const overall = document.querySelector('input[name="overall"]')?.value;

        // Validation
        if (
            !fullName ||
            !email ||
            !location ||
            !role ||
            !title ||
            !message ||
            !consent ||
            !coverage ||
            !impact ||
            !overall
        ) {
            if (errorText)
                errorText.textContent =
                    "Please complete all required fields and accept consent.";
            showMessage(errorMessage);
            return;
        }

        if (message.length < 50) {
            if (errorText)
                errorText.textContent =
                    "Your story must be at least 50 characters long.";
            showMessage(errorMessage);
            return;
        }

        // Log submission (replace with actual API call)
        console.log("Form submitted:", {
            fullName,
            email,
            location,
            role,
            title,
            message,
            coverage,
            impact,
            overall,
        });

        // Show success message
        showMessage(successMessage, 6000);
        resetForm();
    });
};

// =============================================
// Modal Navigation Functionality
// =============================================
const initModalNav = () => {
    const navTrigger = document.getElementById("modalNavTrigger");
    const navClose = document.getElementById("modalNavClose");
    const modalNav = document.getElementById("modalBottomNav");
    const modalOverlay = document.getElementById("modalNavOverlay");
    const navItems = document.querySelectorAll(".modal-nav-item a");
    const body = document.body;

    if (!navTrigger || !modalNav || !modalOverlay || !navClose) return;

    const openModal = () => {
        modalNav.classList.add("active");
        modalOverlay.classList.add("active");
        body.classList.add("modal-nav-open");
        navTrigger.style.pointerEvents = "none";
    };

    const closeModal = () => {
        modalNav.classList.remove("active");
        modalOverlay.classList.remove("active");
        body.classList.remove("modal-nav-open");
        navTrigger.style.pointerEvents = "auto";
    };

    // Trigger button
    navTrigger.addEventListener("click", openModal);

    // Close button
    if (navClose) {
        navClose.addEventListener("click", closeModal);
    }

    // Overlay click
    modalOverlay.addEventListener("click", closeModal);

    // Nav items click (auto-close)
    navItems.forEach((item) => {
        item.addEventListener("click", closeModal);
    });

    // Keyboard (Escape key)
    document.addEventListener("keydown", (e) => {
        if (e.key === "Escape" && modalNav.classList.contains("active")) {
            closeModal();
        }
    });

    // Prevent body scroll when modal is open
    modalNav.addEventListener(
        "touchmove",
        (e) => {
            e.stopPropagation();
        },
        {
            passive: true,
        },
    );
};

// =============================================
// Main Application Initialization
// =============================================
ready(() => {
    console.log(
        "%c✅ Production App Initialized",
        "color: #3b82f6; font-weight: 600; font-size: 13px;",
    );

    // Initialize all modules in order
    initSmoothScroll();
    initScrollReveal();
    initMobileMenu();
    initAccessibility();
    initTabSystem();
    initStarRatings();
    initCharacterCounter();
    initPhotoUpload();
    initTestimonialsFilter();
    initFormSubmission();
    initModalNav();
});
