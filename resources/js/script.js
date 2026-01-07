document.addEventListener("DOMContentLoaded", () => {
    initDropdownPositioning();
    initPasswordToggle();
});

/**
 * Adjust dropdown menus so that they don't overflow off the right edge of the viewport.
 * Adds "dropdown-right" class when dropdown would be cut off.
 */
function initDropdownPositioning() {
    const dropdowns = document.querySelectorAll(".main-menu .sub-menu");

    dropdowns.forEach((dropdown) => {
        const parent = dropdown.parentElement;

        parent.addEventListener("mouseenter", () => {
            // Remove any previous positioning class
            dropdown.classList.remove("dropdown-right");

            const rect = dropdown.getBoundingClientRect();
            const viewportWidth = window.innerWidth;

            // Add class if dropdown goes beyond viewport width
            if (rect.right > viewportWidth) {
                dropdown.classList.add("dropdown-right");
            }
        });
    });
}

/**
 * Bind click handlers to elements with `data-toggle-password` to toggle visibility of their target password inputs.
 *
 * Each handler reads the target input ID from the element's `data-toggle-password` attribute and toggles that input's type and the toggle icon's classes to reflect visibility.
 */
function initPasswordToggle() {
    // Assuming you might want to handle multiple password toggles dynamically
    document
        .querySelectorAll("[data-toggle-password]")
        .forEach((toggleIcon) => {
            toggleIcon.addEventListener("click", () => {
                const inputId = toggleIcon.getAttribute("data-toggle-password");
                togglePasswordVisibility(inputId, toggleIcon);
            });
        });
}

/**
 * Toggle a password input between masked and visible and update the toggle icon classes.
 *
 * @param {string} inputId - ID of the target input element; no action is taken if no element with this ID exists.
 * @param {HTMLElement} toggleIcon - Icon element whose classes `fa-eye` and `fa-eye-slash` will be swapped to reflect visibility.
 */
function togglePasswordVisibility(inputId, toggleIcon) {
    const passwordInput = document.getElementById(inputId);
    if (!passwordInput) return;

    const isPassword = passwordInput.type === "password";

    passwordInput.type = isPassword ? "text" : "password";

    toggleIcon.classList.toggle("fa-eye", !isPassword);
    toggleIcon.classList.toggle("fa-eye-slash", isPassword);
}