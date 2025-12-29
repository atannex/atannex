/**
 * Form Utilities
 * -----------------------------
 * - Timezone auto-detection
 * - Real-time password entropy analysis (zxcvbn)
 * - Password match validation
 * - Password visibility toggle
 * - Submit-time enforcement
 *
 * @module FormUtilities
 */

(() => {
    "use strict";

    /* ----------------------------------------------------------------------
     * Configuration
     * ---------------------------------------------------------------------- */

    const PASSWORD_POLICY = {
        MIN_SCORE: 3, // zxcvbn score (0–4)
    };

    const STRENGTH_UI = [
        {
            label: "Very Weak",
            class: "bg-danger",
            text: "text-danger",
            percent: 20,
        },
        { label: "Weak", class: "bg-danger", text: "text-danger", percent: 40 },
        {
            label: "Fair",
            class: "bg-warning",
            text: "text-warning",
            percent: 60,
        },
        { label: "Good", class: "bg-info", text: "text-info", percent: 80 },
        {
            label: "Strong",
            class: "bg-success",
            text: "text-success",
            percent: 100,
        },
    ];

    /* ----------------------------------------------------------------------
     * Utilities
     * ---------------------------------------------------------------------- */

    /**
     * Safely get DOM element by ID.
     */
    const el = (id) => document.getElementById(id);

    /**
     * Initialize timezone field.
     */
    function initializeTimezone() {
        const timezoneInput = el("timezone");
        if (!timezoneInput) return;

        try {
            timezoneInput.value =
                Intl.DateTimeFormat().resolvedOptions().timeZone;
        } catch (error) {
            console.error("Timezone detection failed:", error);
        }
    }

    /* ----------------------------------------------------------------------
     * Password Strength (zxcvbn)
     * ---------------------------------------------------------------------- */

    /**
     * Get password strength score using zxcvbn.
     *
     * @param {string} password
     * @returns {object|null}
     */
    function analyzePassword(password) {
        if (!password || typeof zxcvbn !== "function") {
            return null;
        }
        return zxcvbn(password);
    }

    /**
     * Update strength UI.
     */
    function updateStrengthUI(score, bar, text) {
        const config = STRENGTH_UI[score] || STRENGTH_UI[0];

        bar.style.width = `${config.percent}%`;
        bar.className = `progress-bar ${config.class}`;

        text.textContent = config.label;
        text.className = `form-text ${config.text}`;
    }

    /**
     * Update password feedback text.
     */
    function updateFeedback(result, feedbackEl) {
        if (!result || !feedbackEl) {
            feedbackEl.textContent = "";
            return;
        }

        const { warning, suggestions } = result.feedback;

        feedbackEl.innerHTML = `
            ${warning ? `<div class="text-warning">${warning}</div>` : ""}
            ${
                suggestions.length
                    ? `<ul class="mb-0">${suggestions
                          .map((s) => `<li>${s}</li>`)
                          .join("")}</ul>`
                    : ""
            }
        `;
    }

    /**
     * Validate password confirmation.
     */
    function checkPasswordMatch(password, confirm, output) {
        if (!confirm.value) {
            output.textContent = "";
            return false;
        }

        const match = password.value === confirm.value;

        output.textContent = match
            ? "Passwords match"
            : "Passwords do not match";

        output.className = `mt-1 form-text ${
            match ? "text-success" : "text-danger"
        }`;

        return match;
    }

    /**
     * Toggle password visibility.
     */
    window.togglePasswordVisibility = function (inputId, iconId) {
        const input = el(inputId);
        const icon = el(iconId);
        if (!input || !icon) return;

        const isHidden = input.type === "password";
        input.type = isHidden ? "text" : "password";

        icon.classList.toggle("fa-eye", !isHidden);
        icon.classList.toggle("fa-eye-slash", isHidden);
    };

    /* ----------------------------------------------------------------------
     * Initialization
     * ---------------------------------------------------------------------- */

    function initializePasswordValidation() {
        const password = el("password");
        const confirm = el("password-confirm");
        const bar = el("password-strength-bar");
        const text = el("password-strength-text");
        const container = el("password-strength-container");
        const matchText = el("password-match-text");
        const feedback = el("password-feedback");
        const form = password?.closest("form");

        if (
            !password ||
            !confirm ||
            !bar ||
            !text ||
            !container ||
            !matchText
        ) {
            console.error("Password validation elements missing.");
            return;
        }

        let currentScore = 0;

        password.addEventListener("input", () => {
            container.style.display = password.value ? "block" : "none";

            const result = analyzePassword(password.value);
            currentScore = result ? result.score : 0;

            updateStrengthUI(currentScore, bar, text);
            updateFeedback(result, feedback);
            checkPasswordMatch(password, confirm, matchText);
        });

        confirm.addEventListener("input", () => {
            checkPasswordMatch(password, confirm, matchText);
        });

        if (form) {
            form.addEventListener("submit", (e) => {
                const isStrongEnough =
                    currentScore >= PASSWORD_POLICY.MIN_SCORE;
                const isMatch = checkPasswordMatch(
                    password,
                    confirm,
                    matchText
                );

                if (!isStrongEnough || !isMatch) {
                    e.preventDefault();
                    alert(
                        "Please choose a stronger password and ensure both fields match."
                    );
                }
            });
        }
    }

    function initialize() {
        initializeTimezone();
        initializePasswordValidation();
    }

    document.addEventListener("DOMContentLoaded", initialize);
})();
