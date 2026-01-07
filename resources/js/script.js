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
 * Initialize all toggle password visibility icons with a common data attribute.
 * This binds click events to toggle password fields on demand.
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
 * Toggle password input visibility and switch icon class accordingly.
 * @param {string} inputId - The ID of the password input element
 * @param {HTMLElement} toggleIcon - The icon element toggling the visibility
 */
function togglePasswordVisibility(inputId, toggleIcon) {
    const passwordInput = document.getElementById(inputId);
    if (!passwordInput) return;

    const isPassword = passwordInput.type === "password";

    passwordInput.type = isPassword ? "text" : "password";

    toggleIcon.classList.toggle("fa-eye", !isPassword);
    toggleIcon.classList.toggle("fa-eye-slash", isPassword);
}
