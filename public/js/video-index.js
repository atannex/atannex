/* ── HERO CAROUSEL ── */
let slide = 0,
    hTimer;
const TOTAL = 5;

function goSlide(i) {
    document.querySelectorAll(".h-slide").forEach((el) => {
        el.style.opacity = el.dataset.si == i ? "1" : "0";
    });
    document.querySelectorAll(".h-content").forEach((el) => {
        el.style.display = el.dataset.si == i ? "" : "none";
    });
    document
        .querySelectorAll(".hero-dot")
        .forEach((d, j) => d.classList.toggle("active", j === i));
    document.getElementById("hcount").textContent =
        String(i + 1).padStart(2, "0") + " / " + String(TOTAL).padStart(2, "0");
    // Restart progress bar on active slide
    document.querySelectorAll(".h-content").forEach((el) => {
        if (el.dataset.si == i) {
            const bar = el.querySelector(".hero-prog-fill");
            if (bar) {
                bar.style.animation = "none";
                bar.offsetHeight;
                bar.style.animation = "drawLine 8s linear";
            }
        }
    });
    slide = i;
}

function startCarousel() {
    hTimer = setInterval(() => goSlide((slide + 1) % TOTAL), 8000);
}
document
    .getElementById("heroWrap")
    .addEventListener("mouseenter", () => clearInterval(hTimer));
document.getElementById("heroWrap").addEventListener("mouseleave", () => {
    clearInterval(hTimer);
    startCarousel();
});
startCarousel();

/* ── FILTER ── */
function filterCat(btn) {
    document
        .querySelectorAll(".cat-pill")
        .forEach((b) => b.classList.remove("active"));
    btn.classList.add("active");
    const cat = btn.dataset.cat || "";
    document.querySelectorAll("#videoGrid a.v-card").forEach((el) => {
        const show =
            !cat ||
            (cat === "exclusive"
                ? el.dataset.excl === "true"
                : el.dataset.cat === cat);
        el.style.display = show ? "" : "none";
    });
}

/* ── VIEW TOGGLE ── */
function setView(mode) {
    document.getElementById("gBtn").classList.toggle("on", mode === "grid");
    document.getElementById("lBtn").classList.toggle("on", mode === "list");
    const grid = document.getElementById("videoGrid");
    if (mode === "list") {
        grid.classList.add("list-mode");
        grid.querySelectorAll(".v-card").forEach((c) => {
            c.style.cssText = "flex-direction:row;height:120px";
            const t = c.querySelector(".v-thumb");
            if (t)
                t.style.cssText =
                    "width:190px;min-width:190px;padding-top:0;height:120px;flex-shrink:0";
        });
    } else {
        grid.classList.remove("list-mode");
        grid.querySelectorAll(".v-card").forEach((c) => {
            c.style.cssText = "";
            const t = c.querySelector(".v-thumb");
            if (t) t.style.cssText = "";
        });
    }
}

/* ── LOAD MORE ── */
document.getElementById("loadMoreBtn").addEventListener("click", function () {
    this.textContent = "Loading…";
    this.style.opacity = ".5";
    this.style.pointerEvents = "none";
    setTimeout(() => {
        this.innerHTML =
            '<svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg> Load More Videos';
        this.style.opacity = "1";
        this.style.pointerEvents = "auto";
    }, 1400);
});

/* ── FOLLOW ── */
function toggleFollow(btn) {
    const f = btn.dataset.f === "1";
    btn.dataset.f = f ? "" : "1";
    btn.textContent = f ? "Follow" : "Following ✓";
    btn.style.cssText = f
        ? ""
        : "border-color:var(--red);color:var(--red);background:rgba(217,0,27,.06)";
}

/* ── SUBSCRIBE ── */
function doSubscribe() {
    const el = document.getElementById("nlEmail");
    if (el.value.includes("@")) {
        document.getElementById("nlMsg").style.display = "block";
        el.value = "";
        el.placeholder = "Subscribed!";
    }
}

/* ── RESPONSIVE ── */
function resize() {
    const w = window.innerWidth;
    document.getElementById("deskNav").style.display =
        w < 1024 ? "none" : "flex";
    document.getElementById("menuBtn").style.display =
        w < 1024 ? "flex" : "none";
    document.getElementById("hdiv").style.display = w < 1024 ? "none" : "block";
    document.getElementById("hlbl").style.display = w < 1024 ? "none" : "block";
    document.getElementById("hsi").style.display = w < 480 ? "none" : "inline";
    document.getElementById("hlive").style.display = w < 420 ? "none" : "flex";
}
window.addEventListener("resize", resize);
resize();

/* ── PRELOADER ── */
(function () {
    const bar = document.getElementById("plBar");
    const txt = document.getElementById("plTxt");
    const loader = document.getElementById("preloader");
    const labels = ["Loading", "Fetching Stories", "Almost Ready"];
    let pct = 0,
        labelIdx = 0;
    const iv = setInterval(() => {
        // Accelerate faster toward 90, then slow down waiting for real load
        const step = pct < 70 ? 3 : pct < 90 ? 1.2 : 0.4;
        pct = Math.min(pct + step, 95);
        bar.style.width = pct + "%";
        const li = pct < 40 ? 0 : pct < 75 ? 1 : 2;
        if (li !== labelIdx) {
            labelIdx = li;
            txt.textContent = labels[li];
        }
    }, 60);

    function finish() {
        clearInterval(iv);
        bar.style.transition = "width .3s ease";
        bar.style.width = "100%";
        txt.textContent = "Ready";
        setTimeout(() => loader.classList.add("hidden"), 380);
    }

    if (document.readyState === "complete") {
        finish();
    } else {
        window.addEventListener("load", finish);
    }
    // Safety fallback — never block the page
    setTimeout(finish, 4500);
})();

/* ── SCROLL TO TOP ── */
(function () {
    const btn = document.getElementById("scrollTop");
    const ring = document.getElementById("sttProgress");
    let ticking = false;

    function onScroll() {
        if (!ticking) {
            requestAnimationFrame(() => {
                const scrolled = window.scrollY;
                const total =
                    document.documentElement.scrollHeight - window.innerHeight;
                const pct = total > 0 ? scrolled / total : 0;

                // Show/hide button
                if (scrolled > 320) {
                    btn.classList.add("visible");
                } else {
                    btn.classList.remove("visible");
                }

                // Conic progress ring
                const deg = Math.round(pct * 360);
                ring.style.background = `conic-gradient(rgba(255,255,255,.3) ${deg}deg, transparent ${deg}deg)`;

                ticking = false;
            });
            ticking = true;
        }
    }

    window.addEventListener("scroll", onScroll, {
        passive: true,
    });
})();

/* ── DRAWER ── */
function openDrawer() {
    document.getElementById("drawer").classList.add("open");
    document.body.style.overflow = "hidden";
}

function closeDrawer() {
    document.getElementById("drawer").classList.remove("open");
    document.body.style.overflow = "";
}
