/**
 * ═══════════════════════════════════════════════════════════════════
 *  ATANNEX — Universal JavaScript
 *  Covers: Login · Register · Forgot Password · Reset Password
 *          Verify Email · Confirm Password
 *
 *  Usage : <script src="{{ asset('js/atannex.js') }}"></script>
 *
 *  Every function guards with early-return if its target element
 *  doesn't exist — safe to load on every page with zero console errors.
 * ═══════════════════════════════════════════════════════════════════
 *
 *  TABLE OF CONTENTS
 *  ─────────────────
 *  1.  Shared Utilities
 *       1a. showToast
 *       1b. togglePwVisibility   (generic — used by ALL pages)
 *       1c. measureStrength      (generic strength meter)
 *       1d. initResendTimer      (generic countdown → enables a button)
 *
 *  2.  Login Page
 *       2a. togglePw             (wraps 1b for login field)
 *       2b. handleLogin          (client-side guard before POST)
 *
 *  3.  Register Page
 *       3a. checkStrength        (wraps 1c for register field)
 *       3b. toggleTag            (interest tag toggle)
 *       3c. goStep2 / goBack     (UI step switching)
 *       3d. handleRegister       (ToS guard — returns bool for onclick)
 *
 *  4.  Forgot Password Page  (forgot-password.blade.php)
 *       4a. validateEmail        (inline email hint)
 *       4b. selectType           (account type selector — cosmetic)
 *       4c. handleSendBtnClick   (loading state before real form POST)
 *
 *  5.  Reset Password Page   (reset-password.blade.php)
 *       5a. checkNewStrength     (strength meter + rules checklist)
 *       5b. checkMatch           (password match indicator)
 *       5c. toggleNewPw          (wraps 1b — named alias for Blade onclick)
 *       5d. handleResetSubmit    (client guard + loading state)
 *
 *  6.  Verify Email Page     (verify-email.blade.php)
 *       6a. triggerResend        (submits the hidden resend form)
 *
 *  7.  Confirm Password Page (confirm-password.blade.php)
 *       7a. Caps Lock detection  (keydown/keyup — auto-init on DOMReady)
 *       7b. Confirm form loading state (submit listener — auto-init)
 *
 *  8.  Auto-init on DOMContentLoaded
 *       · Resend timers (forgot-password step 2 + verify-email)
 *       · Caps Lock     (confirm-password)
 *       · Confirm form  (confirm-password)
 * ═══════════════════════════════════════════════════════════════════
 */

"use strict";

/* ─────────────────────────────────────────────────────────────────
   1a. TOAST NOTIFICATION
   ───────────────────────────────────────────────────────────────── */
function showToast(msg) {
    const t = document.getElementById("toast");
    if (!t) return;
    t.textContent = msg;
    t.classList.add("show");
    setTimeout(() => t.classList.remove("show"), 3200);
}

/* ─────────────────────────────────────────────────────────────────
   1b. PASSWORD VISIBILITY TOGGLE  (generic)
   Handles every password field on every page.

   @param {string} inputId    — id of the <input>
   @param {string} showIconId — id of "eye open" SVG  (visible when PW is hidden)
   @param {string} hideIconId — id of "eye closed" SVG (visible when PW is shown)
   ───────────────────────────────────────────────────────────────── */
function togglePwVisibility(inputId, showIconId, hideIconId) {
    const input = document.getElementById(inputId);
    const showIcon = document.getElementById(showIconId);
    const hideIcon = document.getElementById(hideIconId);
    if (!input) return;

    const reveal = input.type === "password";
    input.type = reveal ? "text" : "password";
    if (showIcon) showIcon.style.display = reveal ? "none" : "inline";
    if (hideIcon) hideIcon.style.display = reveal ? "inline" : "none";
}

/* ─────────────────────────────────────────────────────────────────
   1c. PASSWORD STRENGTH METER  (generic)

   @param {string}   val     — password value
   @param {string[]} segIds  — 4 strength-bar segment element ids
   @param {string}   labelId — strength label element id
   @returns {number}           score 0–4
   ───────────────────────────────────────────────────────────────── */
function measureStrength(val, segIds, labelId) {
    let score = 0;
    if (val.length >= 8) score++;
    if (/[A-Z]/.test(val)) score++;
    if (/[0-9]/.test(val)) score++;
    if (/[^A-Za-z0-9]/.test(val)) score++;

    const classes = ["", "s-weak", "s-fair", "s-good", "s-strong"];
    const labels = [
        "",
        "Weak — add numbers & symbols",
        "Fair — try a capital letter",
        "Good — almost there!",
        "Strong password ✓",
    ];
    const colors = ["", "#CC4400", "#CC8800", "#00AA44", "#00CC55"];

    segIds.forEach((id, i) => {
        const el = document.getElementById(id);
        if (!el) return;
        el.className = "strength-seg";
        if (i < score && classes[score]) el.classList.add(classes[score]);
    });

    const labelEl = document.getElementById(labelId);
    if (labelEl) {
        labelEl.textContent = val.length > 0 ? labels[score] : "";
        labelEl.style.color = colors[score] || "#666";
    }

    return score;
}

/* ─────────────────────────────────────────────────────────────────
   1d. GENERIC RESEND COUNTDOWN TIMER
   Counts down from `seconds`. When it reaches 0 it:
     • hides timerEl
     • enables btnEl
     • sets btnEl text to "Resend now"

   Used by forgot-password (step 2) and verify-email.
   Auto-called in section 8 — no inline calls needed.

   @param {HTMLElement|null} timerEl  — countdown display element
   @param {HTMLElement|null} btnEl    — button to unlock when done
   @param {number}           seconds  — starting value (default 59)
   @returns {number|null}              setInterval ID
   ───────────────────────────────────────────────────────────────── */
function initResendTimer(timerEl, btnEl, seconds = 59) {
    if (!timerEl || !btnEl) return null;

    btnEl.disabled = true;
    timerEl.textContent = `Resend in 0:${String(seconds).padStart(2, "0")}`;
    timerEl.style.display = "inline";

    const id = setInterval(() => {
        seconds--;
        if (seconds <= 0) {
            clearInterval(id);
            timerEl.style.display = "none";
            btnEl.disabled = false;
            btnEl.textContent = "Resend now";
        } else {
            timerEl.textContent = `Resend in 0:${String(seconds).padStart(2, "0")}`;
        }
    }, 1000);

    return id;
}

/* ═════════════════════════════════════════════════════════════════
   2. LOGIN PAGE
   ═════════════════════════════════════════════════════════════════ */

/* ── 2a. Login password toggle ── */
function togglePw() {
    togglePwVisibility("login-pw", "eye-show", "eye-hide");
}

/* ── 2b. Login client guard (optional — real auth is server-side) ── */
function handleLogin() {
    const input =
        document.getElementById("login-email") ??
        document.querySelector('input[type="email"]');

    if (!input || !input.value.trim()) {
        showToast("⚠ Please enter your email address.");
        if (input) input.focus();
        return false;
    }
    return true;
}

/* ═════════════════════════════════════════════════════════════════
   3. REGISTER PAGE
   ═════════════════════════════════════════════════════════════════ */

/* ── 3a. Register password strength (wraps generic) ── */
function checkStrength(val) {
    measureStrength(val, ["s1", "s2", "s3", "s4"], "strength-text");
}

/* ── 3b. Interest tag toggle ── */
function toggleTag(btn) {
    if (!btn) return;
    btn.classList.toggle("selected");
    // Mirror state to hidden checkbox for form submission
    const slug = (btn.dataset.topic || "")
        .toLowerCase()
        .replace(/[^a-z0-9]+/g, "-");
    const checkbox = document.getElementById("topic-" + slug);
    if (checkbox) checkbox.checked = btn.classList.contains("selected");
}

/* ── 3c. Multi-step navigation ── */
function goStep2() {
    const emailInput = document.getElementById("email");
    if (!emailInput || !emailInput.value.trim()) {
        showToast("⚠ Please enter your email address.");
        if (emailInput) emailInput.focus();
        return;
    }
    _swapVisible("form-step-1", "form-step-2");
    _setStepClass("step-1", "done");
    _setStepClass("step-2", "active");
}

function goBack() {
    _swapVisible("form-step-2", "form-step-1");
    _setStepClass("step-1", "active");
    _setStepClass("step-2", "inactive");
}

/* ── 3d. Register submit guard (return false to block, true to allow POST) ── */
function handleRegister() {
    const agreeBox = document.getElementById("agree");
    if (!agreeBox || !agreeBox.checked) {
        showToast("⚠ Please accept the Terms of Service to continue.");
        return false;
    }
    // Sync all interest-tag buttons → hidden checkboxes before the POST
    document.querySelectorAll(".interest-tag").forEach((btn) => {
        const slug = (btn.dataset.topic || "")
            .toLowerCase()
            .replace(/[^a-z0-9]+/g, "-");
        const checkbox = document.getElementById("topic-" + slug);
        if (checkbox) checkbox.checked = btn.classList.contains("selected");
    });
    _setStepClass("step-2", "done");
    _setStepClass("step-3", "done");
    return true;
}

/* ═════════════════════════════════════════════════════════════════
   4. FORGOT PASSWORD PAGE  (forgot-password.blade.php)
      Real form POSTs to password.email — these are UX decorations only.
   ═════════════════════════════════════════════════════════════════ */

/* ── 4a. Inline email format hint ── */
function validateEmail(input) {
    const hint = document.getElementById("email-hint");
    if (!hint || !input) return;
    const val = input.value;
    if (!val) {
        hint.textContent = "";
        return;
    }
    const valid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val);
    hint.textContent = valid ? "✓ Looks good" : "Enter a valid email address";
    hint.style.color = valid ? "#00cc55" : "#CC4400";
}

/* ── 4b. Account type selector (cosmetic — advises on social accounts) ── */
function selectType(type) {
    ["email", "google", "apple"].forEach((t) => {
        const el = document.getElementById("type-" + t);
        if (el) el.classList.remove("selected");
    });

    const selected = document.getElementById("type-" + type);
    if (selected) selected.classList.add("selected");

    const notice = document.getElementById("social-notice");
    if (!notice) return;

    const messages = {
        google: `<strong style="color:#ccaa00;">Google account detected</strong><br>
                 Accounts linked to Google don't have an Atannex password.
                 Visit <a href="https://accounts.google.com" target="_blank"
                 style="color:#ccaa00;">accounts.google.com</a> to reset your Google password.`,
        apple: `<strong style="color:#ccaa00;">Apple ID detected</strong><br>
                 Accounts linked with Apple ID don't have an Atannex password.
                 Visit <a href="https://appleid.apple.com" target="_blank"
                 style="color:#ccaa00;">appleid.apple.com</a> to reset your Apple password.`,
    };

    if (messages[type]) {
        notice.style.display = "block";
        notice.innerHTML = messages[type];
    } else {
        notice.style.display = "none";
    }
}

/* ── 4c. Send button loading state ── */
function handleSendBtnClick(btn) {
    // Block submit if social account type is selected
    const selectedEl = document.querySelector(".account-type-btn.selected");
    const selectedType = selectedEl
        ? selectedEl.id.replace("type-", "")
        : "email";

    if (selectedType !== "email") {
        showToast("⚠ Use the appropriate account provider to reset.");
        return false;
    }

    if (btn) {
        btn.disabled = true;
        btn.innerHTML = _spinnerHTML("Sending…");
    }
    return true; // allow form POST
}

/* ═════════════════════════════════════════════════════════════════
   5. RESET PASSWORD PAGE  (reset-password.blade.php)
      Real form POSTs to password.update — decorations + guard only.
   ═════════════════════════════════════════════════════════════════ */

/* ── 5a. New password strength + rules checklist ── */
function checkNewStrength(val) {
    measureStrength(val, ["ns1", "ns2", "ns3", "ns4"], "new-strength-text");
    _updateRule("rule-len", val.length >= 8);
    _updateRule("rule-upper", /[A-Z]/.test(val));
    _updateRule("rule-num", /[0-9]/.test(val));
    _updateRule("rule-special", /[^A-Za-z0-9]/.test(val));
}

/* ── 5b. Password match indicator ── */
function checkMatch() {
    const pw1 = document.getElementById("new-pw");
    const pw2 = document.getElementById("confirm-pw");
    const ind = document.getElementById("match-indicator");
    const txt = document.getElementById("match-text");
    if (!pw1 || !pw2 || !ind || !txt) return;

    if (!pw2.value) {
        ind.className = "match-indicator";
        txt.textContent = "";
        return;
    }

    const isMatch = pw1.value === pw2.value;
    ind.className = "match-indicator " + (isMatch ? "match" : "mismatch");
    txt.textContent = isMatch ? "Passwords match" : "Passwords do not match";
}

/* ── 5c. Password visibility toggles for the reset page ──
   toggleNewPw(inputId, showId, hideId) — generic signature used in Blade:
     onclick="toggleNewPw('new-pw','eye-new-show','eye-new-hide')"
     onclick="toggleNewPw('confirm-pw','eye-cf-show','eye-cf-hide')"

   Convenience zero-arg wrappers for cleaner markup if preferred:
     onclick="toggleNewPwField()"
     onclick="toggleConfirmPwField()"
   ───────────────────────────────────────────────────────────────── */
function toggleNewPw(inputId, showIconId, hideIconId) {
    togglePwVisibility(inputId, showIconId, hideIconId);
}

function toggleNewPwField() {
    togglePwVisibility("new-pw", "eye-new-show", "eye-new-hide");
}

function toggleConfirmPwField() {
    togglePwVisibility("confirm-pw", "eye-cf-show", "eye-cf-hide");
}

/* ── 5d. Reset form submit guard + loading state ── */
function handleResetSubmit(btn) {
    const pw1 = document.getElementById("new-pw")?.value ?? "";
    const pw2 = document.getElementById("confirm-pw")?.value ?? "";

    if (!pw1 || pw1.length < 8) {
        showToast("⚠ Password must be at least 8 characters.");
        document.getElementById("new-pw")?.focus();
        return false;
    }
    if (pw1 !== pw2) {
        showToast("⚠ Passwords do not match.");
        document.getElementById("confirm-pw")?.focus();
        return false;
    }

    if (btn) {
        btn.disabled = true;
        btn.innerHTML = _spinnerHTML("Updating…");
    }
    return true; // allow form POST
}

/* ═════════════════════════════════════════════════════════════════
   6. VERIFY EMAIL PAGE  (verify-email.blade.php)
   ═════════════════════════════════════════════════════════════════ */

/* ── 6a. Submit the hidden resend form via the visible button ── */
function triggerResend() {
    const form = document.getElementById("resend-form");
    if (form) form.submit();
}

/* ═════════════════════════════════════════════════════════════════
   7. CONFIRM PASSWORD PAGE  (confirm-password.blade.php)
      Both features are initialised in section 8 — no inline calls needed.
   ═════════════════════════════════════════════════════════════════ */

function _initConfirmPasswordPage() {
    /* ── 7a. Caps Lock warning ── */
    const cpInput = document.getElementById("confirm-pw-input");
    const cpWarn = document.getElementById("capslock-warn");

    if (cpInput && cpWarn) {
        const toggleWarn = (e) => {
            cpWarn.style.display = e.getModifierState("CapsLock")
                ? "flex"
                : "none";
        };
        cpInput.addEventListener("keydown", toggleWarn);
        cpInput.addEventListener("keyup", toggleWarn);
    }

    /* ── 7b. Submit loading state ── */
    const form = document.getElementById("confirm-form");
    const btn = document.getElementById("confirm-submit-btn");

    if (form && btn) {
        form.addEventListener("submit", () => {
            btn.disabled = true;
            btn.innerHTML = _spinnerHTML("Verifying…");
        });
    }
}

/* ═════════════════════════════════════════════════════════════════
   8. AUTO-INIT
   ═════════════════════════════════════════════════════════════════ */
document.addEventListener("DOMContentLoaded", () => {
    /* Forgot-password step 2: resend countdown */
    initResendTimer(
        document.getElementById("resend-timer"),
        document.getElementById("resend-btn"),
    );

    /* Verify-email page: resend countdown */
    initResendTimer(
        document.getElementById("verify-timer"),
        document.getElementById("verify-resend-trigger"),
    );

    /* Confirm-password page */
    _initConfirmPasswordPage();
});

/* ═════════════════════════════════════════════════════════════════
   PRIVATE HELPERS  (prefixed _ — not for inline onclick use)
   ═════════════════════════════════════════════════════════════════ */

/** Hide `hideId`, show `showId` */
function _swapVisible(hideId, showId) {
    const a = document.getElementById(hideId);
    const b = document.getElementById(showId);
    if (a) a.style.display = "none";
    if (b) b.style.display = "block";
}

/** Set className on a .step progress element */
function _setStepClass(id, state) {
    const el = document.getElementById(id);
    if (el) el.className = `step ${state}`;
}

/** Toggle a password rule row's passed/failed visual state */
function _updateRule(id, passed) {
    const el = document.getElementById(id);
    if (!el) return;
    el.classList.toggle("passed", passed);
    const dot = el.querySelector(".rule-dot");
    if (dot) dot.textContent = passed ? "●" : "○";
}

/** Inline spinning SVG + label for button loading states */
function _spinnerHTML(label) {
    return `<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"
                 viewBox="0 0 24 24"
                 style="display:inline;margin-right:8px;vertical-align:-2px;animation:spin 0.8s linear infinite;">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993
                         0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25
                         0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/>
            </svg>${label}`;
}
