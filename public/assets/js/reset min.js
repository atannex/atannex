(() => {
    "use strict";
    let e = { MIN_SCORE: 3 },
        t = [
            {
                label: "Very Weak",
                class: "bg-danger",
                text: "text-danger",
                percent: 20,
            },
            {
                label: "Weak",
                class: "bg-danger",
                text: "text-danger",
                percent: 40,
            },
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
        ],
        s = (e) => document.getElementById(e);
    function n() {
        let e = s("timezone");
        if (e)
            try {
                e.value = Intl.DateTimeFormat().resolvedOptions().timeZone;
            } catch (t) {
                console.error("Timezone detection failed:", t);
            }
    }
    function a(e) {
        return e && "function" == typeof zxcvbn ? zxcvbn(e) : null;
    }
    function r(e, s, n) {
        let a = t[e] || t[0];
        (s.style.width = `${a.percent}%`),
            (s.className = `progress-bar ${a.class}`),
            (n.textContent = a.label),
            (n.className = `form-text ${a.text}`);
    }
    function l(e, t) {
        if (!e || !t) {
            t.textContent = "";
            return;
        }
        let { warning: s, suggestions: n } = e.feedback;
        t.innerHTML = `
            ${s ? `<div class="text-warning">${s}</div>` : ""}
            ${
                n.length
                    ? `<ul class="mb-0">${n
                          .map((e) => `<li>${e}</li>`)
                          .join("")}</ul>`
                    : ""
            }
        `;
    }
    function o(e, t, s) {
        if (!t.value) return (s.textContent = ""), !1;
        let n = e.value === t.value;
        return (
            (s.textContent = n ? "Passwords match" : "Passwords do not match"),
            (s.className = `mt-1 form-text ${
                n ? "text-success" : "text-danger"
            }`),
            n
        );
    }
    function i() {
        let t = s("password"),
            n = s("password-confirm"),
            i = s("password-strength-bar"),
            c = s("password-strength-text"),
            d = s("password-strength-container"),
            u = s("password-match-text"),
            f = s("password-feedback"),
            g = t?.closest("form");
        if (!t || !n || !i || !c || !d || !u) {
            console.error("Password validation elements missing.");
            return;
        }
        let p = 0;
        t.addEventListener("input", () => {
            d.style.display = t.value ? "block" : "none";
            let e = a(t.value);
            r((p = e ? e.score : 0), i, c), l(e, f), o(t, n, u);
        }),
            n.addEventListener("input", () => {
                o(t, n, u);
            }),
            g &&
                g.addEventListener("submit", (s) => {
                    let a = p >= e.MIN_SCORE,
                        r = o(t, n, u);
                    (a && r) ||
                        (s.preventDefault(),
                        alert(
                            "Please choose a stronger password and ensure both fields match."
                        ));
                });
    }
    function c() {
        n(), i();
    }
    (window.togglePasswordVisibility = function (e, t) {
        let n = s(e),
            a = s(t);
        if (!n || !a) return;
        let r = "password" === n.type;
        (n.type = r ? "text" : "password"),
            a.classList.toggle("fa-eye", !r),
            a.classList.toggle("fa-eye-slash", r);
    }),
        document.addEventListener("DOMContentLoaded", c);
})();
