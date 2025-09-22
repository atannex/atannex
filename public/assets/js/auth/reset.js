document.addEventListener("DOMContentLoaded", () => {

    const timezoneInput = document.getElementById("timezone");
    if (timezoneInput)
        timezoneInput.value = Intl.DateTimeFormat().resolvedOptions().timeZone;

    const passwordInput = document.getElementById("password");
    const strengthContainer = document.getElementById(
        "password-strength-container"
    );
    const strengthText = document.getElementById("password-strength-text");
    const strengthBar = document.getElementById("password-strength-bar");
    const passwordConfirm = document.getElementById("password-confirm");
    const matchText = document.getElementById("password-match-text");

    passwordInput.addEventListener("input", () => {

        strengthContainer.style.display = passwordInput.value.length > 0 ? "block" : "none";

        const value = passwordInput.value;
        let strength = 0;
        if (value.length >= 8) strength++;
        if (/[a-z]/.test(value)) strength++;
        if (/[A-Z]/.test(value)) strength++;
        if (/[0-9]/.test(value)) strength++;
        if (/[\W_]/.test(value)) strength++;

        const percentage = (strength / 5) * 100;
        strengthBar.style.width = `${percentage}%`;
        if (strength <= 2) {
            strengthBar.className = "progress-bar bg-danger";
            strengthText.textContent = "Weak";
            strengthText.className = "form-text text-danger";
        } else if (strength <= 4) {
            strengthBar.className = "progress-bar bg-warning";
            strengthText.textContent = "Moderate";
            strengthText.className = "form-text text-warning";
        } else {
            strengthBar.className = "progress-bar bg-success";
            strengthText.textContent = "Strong";
            strengthText.className = "form-text text-success";
        }

        checkPasswordMatch();
    });

    passwordConfirm.addEventListener("input", checkPasswordMatch);

    function checkPasswordMatch() {
        if (passwordConfirm.value.length === 0) {
            matchText.textContent = "";
            return;
        }
        if (passwordInput.value === passwordConfirm.value) {
            matchText.textContent = "Passwords match";
            matchText.className = "mt-1 form-text text-success";
        } else {
            matchText.textContent = "Passwords do not match";
            matchText.className = "mt-1 form-text text-danger";
        }
    }
});

function togglePasswordVisibility(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(iconId);
    if (input.type === "password") {
        input.type = "text";
        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash");
        return;
    }
    input.type = "password";
    icon.classList.remove("fa-eye-slash");
    icon.classList.add("fa-eye");
}
