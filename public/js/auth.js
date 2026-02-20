"use strict";

/* ════════════════════════════════════════════════════════════════
   ATANNEX — Universal Auth JavaScript (Laravel POST Optimized)
   Works with full page reload forms.
   Prevents double submits.
   Clean loading feedback.
   ════════════════════════════════════════════════════════════════ */

/* ────────────────────────────────────────────────────────────────
   1. SHARED UTILITIES
   ──────────────────────────────────────────────────────────────── */

function showToast(msg) {
    const t = document.getElementById("toast");
    if (!t) return;
    t.textContent = msg;
    t.classList.add("show");
    setTimeout(() => t.classList.remove("show"), 3200);
}

function _setLoadingState(btn, label) {
    if (!btn) return;
    btn.disabled = true;
    btn.dataset.original = btn.textContent;
    btn.textContent = label;
}

function _restoreButton(btn) {
    if (!btn) return;
    btn.disabled = false;
    if (btn.dataset.original) {
        btn.textContent = btn.dataset.original;
    }
}

/* ────────────────────────────────────────────────────────────────
   2. LOGIN PAGE
   ──────────────────────────────────────────────────────────────── */

function togglePwVisibility(inputId, showIconId, hideIconId) {
    const input = document.getElementById(inputId);
    if (!input) return;

    const showIcon = document.getElementById(showIconId);
    const hideIcon = document.getElementById(hideIconId);

    const reveal = input.type === "password";
    input.type = reveal ? "text" : "password";

    if (showIcon) showIcon.style.display = reveal ? "none" : "inline";
    if (hideIcon) hideIcon.style.display = reveal ? "inline" : "none";
}

function togglePw() {
    togglePwVisibility("login-pw", "eye-show", "eye-hide");
}

function handleLogin() {
    const email = document.getElementById("login-email");
    if (!email || !email.value.trim()) {
        showToast("⚠ Please enter your email address.");
        email?.focus();
        return false;
    }
    return true;
}

/* ────────────────────────────────────────────────────────────────
   3. REGISTER PAGE
   ──────────────────────────────────────────────────────────────── */

function checkStrength(val) {
    let score = 0;
    if (val.length >= 8) score++;
    if (/[A-Z]/.test(val)) score++;
    if (/[0-9]/.test(val)) score++;
    if (/[^A-Za-z0-9]/.test(val)) score++;

    const label = document.getElementById("strength-text");
    if (!label) return;

    const labels = ["", "Weak", "Fair", "Good", "Strong ✓"];
    label.textContent = val.length ? labels[score] : "";
}

function toggleTag(btn) {
    if (!btn) return;
    btn.classList.toggle("selected");
}

/* ────────────────────────────────────────────────────────────────
   4. FORGOT PASSWORD PAGE
   ──────────────────────────────────────────────────────────────── */

function validateEmail(input) {
    const hint = document.getElementById("email-hint");
    if (!hint || !input) return;

    const valid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(input.value);
    hint.textContent = input.value
        ? valid
            ? "✓ Looks good"
            : "Enter valid email"
        : "";
    hint.style.color = valid ? "#00cc55" : "#CC4400";
}

function selectType(type) {
    ["email", "google", "apple"].forEach((t) => {
        document.getElementById("type-" + t)?.classList.remove("selected");
    });
    document.getElementById("type-" + type)?.classList.add("selected");
}

function _initForgotPasswordPage() {
    const form = document.getElementById("forgot-form");
    const btn = document.getElementById("send-btn");

    if (!form || !btn) return;

    form.addEventListener("submit", function (e) {
        const selected = document.querySelector(".account-type-btn.selected");

        const type = selected ? selected.id.replace("type-", "") : "email";

        if (type !== "email") {
            e.preventDefault();
            showToast("⚠ Use the correct provider to reset.");
            return;
        }

        _setLoadingState(btn, "Sending…");
    });
}

/* ────────────────────────────────────────────────────────────────
   5. RESET PASSWORD PAGE
   ──────────────────────────────────────────────────────────────── */

function toggleNewPw(inputId, showIconId, hideIconId) {
    togglePwVisibility(inputId, showIconId, hideIconId);
}

function checkMatch() {
    const pw1 = document.getElementById("new-pw");
    const pw2 = document.getElementById("confirm-pw");
    const text = document.getElementById("match-text");

    if (!pw1 || !pw2 || !text) return;

    if (!pw2.value) {
        text.textContent = "";
        return;
    }

    text.textContent =
        pw1.value === pw2.value ? "Passwords match" : "Passwords do not match";
}

function _initResetPasswordPage() {
    const form = document.getElementById("reset-form");
    const btn = document.getElementById("reset-btn");

    if (!form || !btn) return;

    form.addEventListener("submit", function (e) {
        const pw1 = document.getElementById("new-pw")?.value ?? "";
        const pw2 = document.getElementById("confirm-pw")?.value ?? "";

        if (pw1.length < 8) {
            e.preventDefault();
            showToast("⚠ Password must be at least 8 characters.");
            return;
        }

        if (pw1 !== pw2) {
            e.preventDefault();
            showToast("⚠ Passwords do not match.");
            return;
        }

        _setLoadingState(btn, "Updating…");
    });
}

/* ────────────────────────────────────────────────────────────────
   6. VERIFY EMAIL
   ──────────────────────────────────────────────────────────────── */

function triggerResend() {
    document.getElementById("resend-form")?.submit();
}

/* ────────────────────────────────────────────────────────────────
   7. CONFIRM PASSWORD PAGE
   ──────────────────────────────────────────────────────────────── */

function _initConfirmPasswordPage() {
    const form = document.getElementById("confirm-form");
    const btn = document.getElementById("confirm-submit-btn");

    if (!form || !btn) return;

    form.addEventListener("submit", function () {
        _setLoadingState(btn, "Verifying…");
    });
}

/* ────────────────────────────────────────────────────────────────
   8. AUTO INIT
   ──────────────────────────────────────────────────────────────── */

document.addEventListener("DOMContentLoaded", () => {
    _initForgotPasswordPage();
    _initResetPasswordPage();
    _initConfirmPasswordPage();
});
