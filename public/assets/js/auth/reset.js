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
     * Populate the input with id "timezone" with the user's IANA time zone.
     *
     * If the element is not present this function does nothing. If timezone
     * detection fails, the error is logged and the input value is left unchanged.
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
     * Determine the zxcvbn analysis result for a given password.
     *
     * @param {string} password - The password to analyze.
     * @returns {object|null} The result object returned by zxcvbn, or `null` if no password is provided or zxcvbn is unavailable.
     */
    function analyzePassword(password) {
        if (!password || typeof zxcvbn !== "function") {
            return null;
        }
        return zxcvbn(password);
    }

    /**
     * Update the password strength progress bar and label to reflect a given score.
     * 
     * Selects the corresponding strength configuration for `score` (falls back to the weakest)
     * and applies its percentage width and visual class to the progress `bar`, and sets the
     * label text and text color class on `text`.
     * 
     * @param {number} score - Strength score (typically 0–4).
     * @param {HTMLElement} bar - Progress bar element whose width and classes will be updated.
     * @param {HTMLElement} text - Text element where the strength label and text color class will be set.
     */
    function updateStrengthUI(score, bar, text) {
        const config = STRENGTH_UI[score] || STRENGTH_UI[0];

        bar.style.width = `${config.percent}%`;
        bar.className = `progress-bar ${config.class}`;

        text.textContent = config.label;
        text.className = `form-text ${config.text}`;
    }

    /**
     * Render password feedback (warning and suggestions) into the provided container element.
     *
     * If `result` or `feedbackEl` is falsy the element's text content is cleared.
     *
     * @param {Object|null} result - zxcvbn analysis result with a `feedback` object containing an optional `warning` string and `suggestions` array of strings.
     * @param {HTMLElement} feedbackEl - DOM element to receive the rendered feedback; warning is wrapped in a `div.text-warning` and suggestions are rendered as a `ul.mb-0` list.
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
     * Checks whether the password and confirmation inputs contain identical values and updates the output element with a match message and styling.
     * @param {HTMLInputElement} password - Password input element to compare.
     * @param {HTMLInputElement} confirm - Password confirmation input element; empty value clears the output and returns false.
     * @param {HTMLElement} output - Element where match/mismatch text and success/error styling will be written.
     * @returns {boolean} `true` if the values are identical, `false` otherwise.
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

    /**
     * Initialize live password validation UI and form enforcement for the current page.
     *
     * Sets up input listeners on the password and confirmation fields to:
     * - Show or hide the strength UI based on whether a password is present.
     * - Analyze the password strength and update the strength bar and label.
     * - Render actionable feedback (warnings and suggestions) for the entered password.
     * - Validate and display whether the password and confirmation match.
     *
     * If the required DOM elements are missing, logs an error and exits without attaching listeners.
     * If the password fields belong to a form, attaches a submit handler that prevents submission
     * and alerts the user when the password strength is below the configured minimum or the
     * confirmation does not match.
     */

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

    /**
     * Initialize page features: populate the timezone field and set up password strength, feedback, match checks, and related form handlers.
     */
    function initialize() {
        initializeTimezone();
        initializePasswordValidation();
    }

    document.addEventListener("DOMContentLoaded", initialize);
})();