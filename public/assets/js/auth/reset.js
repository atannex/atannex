/**
 * Manages password input validation, strength checking, and timezone initialization for a form.
 * @module PasswordValidation
 */

/**
 * Set the element with id "timezone" to the user's IANA timezone.
 *
 * If the element is missing, no change is made and a warning is logged.
 * If determining the timezone fails, an error is logged.
 * @private
 */
function initializeTimezone() {
    const timezoneInput = document.getElementById("timezone");
    if (!timezoneInput) {
        console.warn("Timezone input element not found");
        return;
    }
    try {
        timezoneInput.value = Intl.DateTimeFormat().resolvedOptions().timeZone;
    } catch (error) {
        console.error("Error setting timezone:", error);
    }
}

/**
 * Calculates a password strength score based on length and character variety.
 * @param {string} password - The password to evaluate.
 * @returns {number} A number between 0 and 5 inclusive where higher values indicate stronger passwords.
 * @private
 */
function calculatePasswordStrength(password) {
    let strength = 0;
    if (password.length >= 8) strength++;
    if (/[a-z]/.test(password)) strength++;
    if (/[A-Z]/.test(password)) strength++;
    if (/[0-9]/.test(password)) strength++;
    if (/[\W_]/.test(password)) strength++;
    return strength;
}

/**
 * Update UI elements to reflect a password strength score.
 *
 * @param {number} strength - Strength score from 0 to 5 where higher is stronger.
 * @param {HTMLElement} strengthBar - Progress indicator element whose width and styling will reflect strength.
 * @param {HTMLElement} strengthText - Text element that will display the strength label and receive styling.
 * @private
 */
function updateStrengthUI(strength, strengthBar, strengthText) {
    const percentage = (strength / 5) * 100;
    strengthBar.style.width = `${percentage}%`;

    if (strength <= 2) {
        strengthBar.className = "progress-bar bg-danger";
        strengthText.textContent = "Weak";
        strengthText.className = "form-text text-danger";
        return;
    }
    if (strength <= 4) {
        strengthBar.className = "progress-bar bg-warning";
        strengthText.textContent = "Moderate";
        strengthText.className = "form-text text-warning";
        return;
    }
    strengthBar.className = "progress-bar bg-success";
    strengthText.textContent = "Strong";
    strengthText.className = "form-text text-success";
}

/**
 * Update the provided status element to indicate whether the password and confirmation values match.
 * If the confirmation is empty, the status element is cleared.
 * @param {HTMLInputElement} passwordInput - The password input element whose value is compared.
 * @param {HTMLInputElement} passwordConfirm - The confirmation input element to compare against the password.
 * @param {HTMLElement} matchText - The element used to display the match status; text and color classes will be set.
 */
function checkPasswordMatch(passwordInput, passwordConfirm, matchText) {
    if (!passwordConfirm.value.length) {
        matchText.textContent = "";
        return;
    }
    matchText.textContent =
        passwordInput.value === passwordConfirm.value
            ? "Passwords match"
            : "Passwords do not match";
    matchText.className = `mt-1 form-text ${
        passwordInput.value === passwordConfirm.value
            ? "text-success"
            : "text-danger"
    }`;
}

/**
 * Toggles password visibility for an input field.
 * @function togglePasswordVisibility
 * @param {string} inputId - The ID of the password input element
 * @param {string} iconId - The ID of the toggle icon element
 * @global
 */
function togglePasswordVisibility(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(iconId);

    if (!input || !icon) {
        console.warn(`Element not found: ${!input ? "input" : "icon"}`);
        return;
    }

    const isPassword = input.type === "password";
    input.type = isPassword ? "text" : "password";
    icon.classList.toggle("fa-eye", !isPassword);
    icon.classList.toggle("fa-eye-slash", isPassword);
}

/**
 * Set up password-strength UI and password confirmation checking by attaching input event handlers.
 *
 * Ensures the required DOM elements are present; if any are missing, logs an error and aborts.
 * When active, shows or hides the strength container, updates the strength bar and text based on
 * the current password, and updates the match message and styling for the password confirmation.
 *
 * Required element IDs: "password", "password-strength-container", "password-strength-text",
 * "password-strength-bar", "password-confirm", "password-match-text".
 *
 * @private
 */
function initializePasswordValidation() {
    const elements = {
        passwordInput: document.getElementById("password"),
        strengthContainer: document.getElementById(
            "password-strength-container"
        ),
        strengthText: document.getElementById("password-strength-text"),
        strengthBar: document.getElementById("password-strength-bar"),
        passwordConfirm: document.getElementById("password-confirm"),
        matchText: document.getElementById("password-match-text"),
    };

    // Validate required elements
    if (!Object.values(elements).every((el) => el)) {
        console.error("One or more required form elements are missing");
        return;
    }

    // Password input event listener
    elements.passwordInput.addEventListener("input", () => {
        elements.strengthContainer.style.display =
            elements.passwordInput.value.length > 0 ? "block" : "none";

        const strength = calculatePasswordStrength(
            elements.passwordInput.value
        );
        updateStrengthUI(strength, elements.strengthBar, elements.strengthText);
        checkPasswordMatch(
            elements.passwordInput,
            elements.passwordConfirm,
            elements.matchText
        );
    });

    // Password confirmation event listener
    elements.passwordConfirm.addEventListener("input", () => {
        checkPasswordMatch(
            elements.passwordInput,
            elements.passwordConfirm,
            elements.matchText
        );
    });
}

/**
 * Initializes all form functionality when the DOM is fully loaded.
 * @function initialize
 * @private
 */
function initialize() {
    try {
        initializeTimezone();
        initializePasswordValidation();
    } catch (error) {
        console.error("Initialization error:", error);
    }
}

// Initialize when DOM is fully loaded
document.addEventListener("DOMContentLoaded", initialize);

/**
 * Create a manager for controlling a subscription popup's visibility and persistence.
 *
 * @param {boolean} alreadySubscribed - If true, prevents the popup from being shown.
 * @returns {{open: boolean, alreadySubscribed: boolean, HIDE_DURATION: number, SHOW_DELAY: number, SCROLL_THRESHOLD: number, init: function(): void, closePopup: function(): void, permanentlyHide: function(): void}} An object that tracks popup state and provides methods to initialize display triggers, close the popup (with short-term hide), and permanently hide it; the object persists hide choices in localStorage. 
 */
function popupSubscribe(alreadySubscribed) {
    return {
        open: false,
        alreadySubscribed: alreadySubscribed,
        HIDE_DURATION: 7 * 24 * 60 * 60 * 1000,
        SHOW_DELAY: 10000,
        SCROLL_THRESHOLD: 0.5,

        init() {
            const hideUntil = localStorage.getItem("hideSubscribePopup");
            const now = Date.now();

            if (
                this.alreadySubscribed ||
                hideUntil === "permanent" ||
                (hideUntil && now < Number(hideUntil))
            ) {
                return;
            }

            let isTriggered = false;

            const showPopup = () => {
                if (isTriggered) return;
                this.open = true;
                isTriggered = true;
                window.removeEventListener("scroll", handleScroll);
            };

            const handleScroll = () => {
                const scrollPosition = window.scrollY + window.innerHeight;
                const pageHeight = document.documentElement.scrollHeight;
                if (scrollPosition / pageHeight >= this.SCROLL_THRESHOLD)
                    showPopup();
            };

            setTimeout(showPopup, this.SHOW_DELAY);
            window.addEventListener("scroll", handleScroll);

            window.addEventListener("subscription-success", () => {
                this.closePopup();
                localStorage.setItem(
                    "hideSubscribePopup",
                    now + this.HIDE_DURATION
                );
            });
        },

        closePopup() {
            this.open = false;
            localStorage.setItem("hideSubscribePopup", Date.now());
        },

        permanentlyHide() {
            this.open = false;
            localStorage.setItem("hideSubscribePopup", "permanent");
        },
    };
}